<?php

use Booking;

include_once(dirname(__FILE__) . '/BaseController.php');

/** @property BookFormRequest $pageRequest */
class BookingController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = 'column1';
	public $newHome		 = '';
	public $email_receipient;
	public $useUserReturnUrl;
	public $current_page = '';
	public $backUrl;
	public $rdata		 = '';

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
			'postOnly + delete,pickup,rates,AddmoreItinerary', // we only allow deletion via POST request
			array(
				'RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'
			),
				/* array(
				  'COutputCache + routes',
				  'duration'			 => 60 * 60 * 24 * 7,
				  'varyByExpression'	 => 'Filter::checkTheme()',
				  'varyByParam'		 => array('route'),
				  'varyByRoute'		 => true,
				  'requestTypes'		 => ['GET'],
				  'dependency'		 => new CacheDependency("HomePage")
				  ), */
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
			['allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('view', 'gozocoinsremove', 'verifybooking', 'ajaxverification', 'confirmbookingemail',
					'receipt', 'edit', 'editnew', 'gozoNowShowVndList', 'creditapply', 'editPickupTime',
					'savepickuptime', 'reschedulebooking', 'showPaymentDetails'),
				'users'		 => array('@'),
			],
			['allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('showPackage', 'list', 'verifytrip', 'verify1', 'verifycontact', 'discountadvance', 'index', 'cabrate', 'userdetails', 'confirmmobile',
					'toproutelist', 'billing', 'summary', 'checkcode', 'process', 'verify', 'promoapply', 'paynow', 'paynow1', 'vendorpaynow', 'vendorpay', 'payment',
					'confirm', 'sendreviewmail', 'codeverify', 'package', 'step1', 'getminpickupval', 'promoremove', 'route', 'carshared', 'addroute', 'mff', 'Sulafest',
					'sunburn', 'supersonic', 'Nh7weekender', 'sunsplash', 'comiccon', 'moodindigo', 'kumbh', 'getrut', 'citylinks', 'pickupcityairport', 'type1', 'type2',
					'route1', 'route2', 'routesmulti', 'cabratedetail', 'additionaldetail', 'finalbook', 'updateReconfirm', 'getPackageDetail', 'cabratepartial',
					'codeverification', 'routes', 'new', 'modifyroute', 'leavelog', 'getduration', 'getleastdepart', 'validateSearch', 'validateDayRentalSearch', 'cities',
					'sendpaymentlink', 'sendconfirmation', 'amountinwords', 'getaddressdata', 'getcanceldesctext', 'getroutename', 'reconfirm', 'unverified', 'unvFollowupCS',
					'unvthankyou', 'reconfirmsubmit', 'redirect', 'fbflexxishare', 'flexxiavailableslots', 'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS',
					'atirudram2017', 'flexximatch', 'flexxisearch', 'FlashSale', 'flexxibook', 'alert', 'notifyCustomer', 'list', 'viewCustomerDetails', 'bookNow', 'pickup', 'pickupnew',
					'rates', 'ratesvo', 'details', 'detailsvo', 'address', 'info', 'summary1', 'rates2', 'flexisearch', 'bookflexislots', 'routerating', 'shuttle', 'summaryadditionalinfo', 'validateAirport', 'packageQuote',
					'validateOneway', 'validateDayRental', 'autoMarkerAddress', 'calVehicleModelAmount', 'getQuoteByVehicleModel', 'chooseVehicleModel', 'addFromCityId',
					'getGNowReqData', 'processGNowbidaccept', 'processGNowOfferDeny', 'refreshAddressWidget', 'bkGNowInventory', 'gnowBidTimer', 'saveGnowDropAddress', 'saveGnowPickAddress', 'showtimer', 'resettimer', 'gnowDropAddress', 'notifyGnowVendor', 'gnowaddress',
					'applyPromo', 'autoaddress', 'updaterouteaddress', 'downloadDocs', 'addmoreroute', 'addmoreitinerary', 'showmoreitinerary', 'CheckTripStatus', 'applyaddon', 'vendortrip', 'bookNowVO', 'typeContent', 'tierQuotes', 'removeitinerary', 'moreTierQuotes', 'review', 'pay', 'saveLead', 'paymentreview', 'cancelgnow',
					'bookNow1', 'checkAccount', 'signin', 'resendOtp', 'otpVerify', 'tripType', 'bkgType', 'itinerary', 'catQuotes', 'previousStep', 'bkgConfirmation', 'paymentv3', 'book', 'fareBrkup', 'gmap', 'existAddress', 'applyWallet', 'addressForm', 'showDriverDetails', 'saveAdditionalRequest', 'finalPay', 'evalCharges', 'confirmbooking', 'GnowDropAddress', 'track', 'editAddress', 'getpromobyid', 'checkAddress', 'addons', 'refreshQuote', 'intraCatQuotes', 'refreshtravellerinfo', 'travellerinfo', 'cab', 'airport',
					'autofurcustomer', 'traveller', 'travellercontact', 'checkPayAmmount', 'canbooking', 'SendOTPPartnerCancel', 'VerifyOTPPartnerCancel', 'ReSendOTPPartnerCancel', 'tripsuggestions', 'suggestedtripselect', 'reschedulereview', 'checkCancellation','infoNew'),
				'users'		 => array('*'),
			],
			['allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array('admin'), 'users'		 => array('admin'),
			],
			['allow', 'actions' => ['invoice', 'invoiceCopy'], 'users' => ['*']],
			['deny', // deny all users
				'users' => array('*'),
			],
		);
	}

	public function restEvents()
	{
		$this->onRest('req.cors.access.control.allow.methods', function () {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});
		$this->onRest('req.get.transactionsummary.render', function () {
			Logger::setCategory("trace.controller.booking");
			Logger::create('61 transactionsummary ', CLogger::LEVEL_TRACE);

			$process_sync_data	 = Yii::app()->request->getParam('data');
			Logger::create('PAYU VENDOR  ' . $process_sync_data, CLogger::LEVEL_TRACE);
			$credit_amount		 = Yii::app()->request->getParam('credit_amount') | 0;
			$data				 = CJSON::decode($process_sync_data, true);
			$id					 = $data['bkg_id'];
			$transCode			 = $data['order_id'];

			try
			{
				$count = 1;
				a:
				if ($id > 0)
				{
					$model1 = Booking::model()->findByPk($id);
				}
				if (!$model1)
				{
					Yii::log($model1, CLogger::LEVEL_INFO);
					throw new CHttpException(400, 'Invalid data');
				}
				if (isset($data['order_id']) && $data['order_id'] == '')
				{
					$tModel		 = PaymentGateway::model()->find('apg_booking_id=:bkg_id', ['bkg_id' => $model1->bkg_id]);
					$transCode	 = $tModel->apg_code;
				}
				if (isset($data['order_id']) && $data['order_id'] != '')
				{
					$tModel		 = PaymentGateway::model()->find('apg_code=:apg_code', ['apg_code' => $data['order_id']]);
					$transCode	 = $tModel->apg_code;
					if ($tModel->apg_ptp_id == 16)
					{
						$upResult = PaymentGateway::model()->updateEmptyPGResponse($tModel, 1);
					}
				}
				if ($transCode)
				{
					$transModel	 = PaymentGateway::model()->getByCode($transCode);
					$transResult = PaymentGateway::model()->getTransdetailByTranscode($transCode);
					$status		 = $transResult['paymentStatus'];
					$tranStatus	 = ($transResult['paymentStatus'] == 1) ? true : false;
					if ($tranStatus == false && $count != 3)
					{
						$count += 1;
						goto a;
					}
					if ($tranStatus && $credit_amount > 0)
					{

						$platform = Booking::Platform_App;
						AccountTransactions::model()->paymentCreditsUsed($model1->bkg_id, PaymentType::TYPE_GOZO_COINS, $credit_amount, $platform, $oldApp);
					}
					$transId = $transResult['transId'];
					if ($transModel->apg_booking_id != $id)
					{
						Yii::log($transModel->apg_booking_id, CLogger::LEVEL_INFO);
						throw new CHttpException(400, 'Invalid data');
					}
				}
				if ($model1 != '')
				{
					$model						 = Booking::model()->with('bkgSvcClassVhcCat')->findbyPk($model1->bkg_id);
					$hr							 = date('G', mktime(0, $model->bkg_trip_duration)) . " Hr";
					$min						 = (date('i', mktime(0, $model->bkg_trip_duration)) != '00') ? ' ' . date('i', mktime(0, $model->bkg_trip_duration)) . " min" : '';
					$model->trip_duration_format = $hr . $min;
					$model->trip_distance_format = $model->bkg_trip_distance . ' Km';
				}

				$datareturn = $model->apiMapping();
				Logger::create('transaction summary :--  ' . json_encode($datareturn), CLogger::LEVEL_TRACE);
			}
			catch (Exception $e)
			{
				Logger::create('Exception:--------  ' . $e->getMessage(), CLogger::LEVEL_TRACE);
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'model'		 => $datareturn,
					'status'	 => $status,
					'success'	 => $tranStatus,
					'transid'	 => $transId,
				)
			]);
		});
		$this->onRest('req.post.transaction1.render', function () {


			Logger::trace('59 transaction1 ');
			$success				 = true;
			$errors					 = [];
			$process_sync_data		 = Yii::app()->request->getParam('data');
			//$process_sync_data       = '{"amount":"139","bkg_bill_address":"aa","bkg_bill_city":"a","bkg_bill_contact":"9474108124","bkg_bill_country":"IND","bkg_bill_email":"chiranjit@gozocabs.in","bkg_bill_fullname":"Chiranjit Hazra","bkg_bill_postalcode":"123456","bkg_bill_state":"a","bkg_id":"686505","payment_type":"4","partialPayment":"139"}';
			//$process_sync_data ='{"amount":100,"payment_type":3,"payment_by":2,"vendor_id":43}';
			Logger::trace('process_sync_data : ' . $process_sync_data);
			$data					 = CJSON::decode($process_sync_data, true);
			$amount					 = $data['amount'];
			$paymentType			 = $data['payment_type'];
			$paymentBy				 = $data['payment_by'];
			$vendorId				 = $data['vendor_id'];
			$data['partialPayment']	 = $data['amount'];
			$transaction			 = Yii::app()->db->beginTransaction();
			if ($paymentBy == 2)
			{
				try
				{
					$model								 = Vendors::model()->findByPk($vendorId);
					$paymentGateway						 = new PaymentGateway();
					$paymentGateway->apg_booking_id		 = '';
					$paymentGateway->apg_acc_trans_type	 = Accounting::AT_OPERATOR;
					$paymentGateway->apg_trans_ref_id	 = $vendorId;
					$paymentGateway->apg_ptp_id			 = $paymentType;
					$paymentGateway->apg_amount			 = $amount;
					$paymentGateway->apg_remarks		 = "Payment Initiated";
					$paymentGateway->apg_ref_id			 = '';
					$paymentGateway->apg_user_type		 = BookingLog::Vendor;
					$paymentGateway->apg_user_id		 = $vendorId;
					$paymentGateway->apg_status			 = 0;
					//$paymentGateway->apg_parent_id		 = 0;
					$paymentGateway->apg_date			 = new CDbExpression('NOW()');
					$bankLedgerId						 = PaymentType::model()->ledgerList($paymentType);
					$paymentGateway						 = $paymentGateway->payment($bankLedgerId);

					if ($paymentType == PaymentType::TYPE_PAYTM && $amount > 0)
					{
						if ($paymentGateway)
						{
							$param_list						 = array();
							$param_list['MID']				 = Yii::app()->paytm->merchant_id;
							$param_list['INDUSTRY_TYPE_ID']	 = Yii::app()->paytm->industry_type_id;
							$param_list['CHANNEL_ID']		 = Yii::app()->paytm->channel_app_id;
							$param_list['WEBSITE']			 = Yii::app()->paytm->appwebsite;
							$order_id						 = $paymentGateway->apg_code;
							$param_list['ORDER_ID']			 = $order_id;
							$param_list['TXN_AMOUNT']		 = $paymentGateway->apg_amount;
							$param_list['CUST_ID']			 = $vendorId;
							$vendorPhone					 = ContactPhone::getContactPhoneById($model->vnd_contact_id);
							$param_list['MOBILE_NO']		 = $vendorPhone;
							$vendorEmail					 = ContactEmail::getContactEmailById($model->vnd_contact_id);
							$param_list['EMAIL']			 = $vendorEmail;
							$param_list['CALLBACK_URL']		 = YII::app()->createAbsoluteUrl('paytm/vendorappresponse');
							$checkSum						 = Yii::app()->paytm->getChecksumFromArray($param_list);
							$param_list['CHECKSUMHASH']		 = $checkSum;
						}
					}
					DBUtil::commitTransaction($transaction);
				}
				catch (Exception $e)
				{
					$success = false;
					DBUtil::rollbackTransaction($transaction);
				}
			}
			else
			{
				try
				{
					$model = Booking::model()->findByPk($data['bkg_id']);

					if ($paymentType == PaymentType::TYPE_PAYTM && $amount > 0)
					{
						$model->bkgUserInfo->scenario	 = 'step3Paytm';
						$model->bkgInvoice->scenario	 = 'step3Paytm';
					}
					else
					{
						$model->bkgUserInfo->scenario	 = 'step3';
						$model->bkgInvoice->scenario	 = 'step3';
					}


					$model->bkgUserInfo->attributes					 = $data;
					$model->bkgUserInfo->bkg_user_last_updated_on	 = new CDbExpression('NOW()');
					$user_id										 = (Yii::app()->user->getId() > 0) ? Yii::app()->user->getId() : '';
					if ($user_id == '')
					{
						$userModel = Users::model()->linkUserByEmail($model->bkg_id, Booking::Platform_App);
						if ($userModel)
						{
							$user_id = $userModel->user_id;
						}
					}
					if ($user_id)
					{
						$model->bkgUserInfo->bkg_user_id = $user_id;
						$usrmodel						 = new Users();
						$usrmodel->resetScope()->findByPk($user_id);
					}
					$model->bkgInvoice->partialPayment = $amount;
					$model->save();
					$model->bkgUserInfo->save();

					$paymentGateway						 = new PaymentGateway();
					$paymentGateway->apg_booking_id		 = $model->bkg_id;
					$paymentGateway->apg_acc_trans_type	 = Accounting::AT_BOOKING;
					$paymentGateway->apg_trans_ref_id	 = $model->bkg_id;
					$paymentGateway->apg_ptp_id			 = $paymentType;
					$paymentGateway->apg_amount			 = $amount;
					$paymentGateway->apg_remarks		 = "Payment Initiated";
					$paymentGateway->apg_ref_id			 = '';
					$paymentGateway->apg_user_type		 = UserInfo::TYPE_CONSUMER;
					$paymentGateway->apg_user_id		 = $model->bkgUserInfo->bkg_user_id;
					$paymentGateway->apg_status			 = 0;
					$paymentGateway->apg_date			 = new CDbExpression('NOW()');
					$bankLedgerId						 = PaymentType::model()->ledgerList($paymentType);
					$paymentGateway						 = $paymentGateway->payment($bankLedgerId);
					$params['blg_ref_id']				 = $paymentGateway->apg_id;
					BookingLog::model()->createLog($model->bkg_id, "Online payment initiated ({$paymentGateway->getPaymentType()} - {$paymentGateway->apg_code})", UserInfo::getInstance(), BookingLog::PAYMENT_INITIATED, '', $params);
					if ($paymentType == PaymentType::TYPE_PAYTM && $amount > 0)
					{
						if ($paymentGateway)
						{
							$param_list						 = array();
							$param_list['MID']				 = Yii::app()->paytm->merchant_id;
							$param_list['INDUSTRY_TYPE_ID']	 = Yii::app()->paytm->industry_type_id;
							$param_list['CHANNEL_ID']		 = Yii::app()->paytm->channel_app_id;
							$param_list['WEBSITE']			 = Yii::app()->paytm->appwebsite;
							$bkgid							 = $paymentGateway->apg_booking_id;
							$bkModel						 = Booking::model()->findByPk($bkgid);
							if (!$bkgid)
							{
								throw new CHttpException(400, "Invalid Payment Request", 400);
							}
							$booking_info				 = ($bkModel->bkgUserInfo->bkg_user_id == '') ? $bkModel->bkg_booking_id : $bkModel->bkgUserInfo->bkg_user_id;
							$order_id					 = $paymentGateway->apg_code;
							$param_list['ORDER_ID']		 = $order_id;
							$param_list['TXN_AMOUNT']	 = $paymentGateway->apg_amount;
							$param_list['CUST_ID']		 = $booking_info;
							$param_list['MOBILE_NO']	 = $bkModel->bkgUserInfo->bkg_contact_no;
							$param_list['EMAIL']		 = $bkModel->bkgUserInfo->bkg_user_email;
							$param_list['CALLBACK_URL']	 = YII::app()->createAbsoluteUrl('paytm/appresponse');
							$checkSum					 = Yii::app()->paytm->getChecksumFromArray($param_list);
							$param_list['CHECKSUMHASH']	 = $checkSum;
							$purl						 = $url						 = Yii::app()->createAbsoluteUrl('paytm/intiate', ['accTransDetailId' => $paymentGateway->apg_id]);
						}
					}
					if ($paymentType == PaymentType::TYPE_EBS && $amount > 0)
					{
						if ($paymentGateway)
						{
							$param_list					 = array();
							$param_list['amount']		 = $paymentGateway->apg_amount;
							$param_list['reference_no']	 = $paymentGateway->apg_code;
						}
						$purl = Yii::app()->createAbsoluteUrl('booking/paynow', ['id' => $model->bkg_id, 'hash' => Yii::app()->shortHash->hash($model->bkg_id), 'platform' => 3]);
					}
					if ($paymentType == PaymentType::TYPE_PAYNIMO && $amount > 0)
					{
						if ($paymentGateway)
						{
							$param_list				 = array();
							$param_list['amount']	 = $paymentGateway->apg_amount;
							$param_list['TXN_ID']	 = $paymentGateway->apg_code;
							$param_list['MID']		 = Yii::app()->params['paynimo']['merchantCode'];
							$order_id				 = $paymentGateway->apg_code;
							$bkgid					 = $paymentGateway->apg_booking_id;
							$bkModel				 = Booking::model()->findByPk($bkgid);
							if (!$bkgid)
							{
								throw new CHttpException(400, "Invalid Payment Request", 400);
							}
							$param_list['ORDER_ID']		 = $order_id;
							$param_list['TXN_AMOUNT']	 = $paymentGateway->apg_amount;
							$param_list['CUST_ID']		 = $booking_info;
							$param_list['MOBILE_NO']	 = $bkModel->bkgUserInfo->bkg_contact_no;
							$param_list['EMAIL']		 = $bkModel->bkgUserInfo->bkg_user_email;
							$param_list['CALLBACK_URL']	 = 'https://www.gozocabs.com/payment/response/ptpid/16/app/1';
						}
					}
					$datareturn = $model->apiMapping();

					DBUtil::commitTransaction($transaction);
				}
				catch (Exception $e)
				{
					DBUtil::rollbackTransaction($transaction);
					$success = false;
					$model->addError('bkg_id', $e->getMessage());
					$errors	 = $model->getErrors();
				}
			}

			$param_list = $param_list + ['pay_url' => $purl];

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'data'		 => $param_list,
					'model'		 => ($paymentBy == 2) ? "" : $datareturn,
					'errors'	 => $errors,
				)
			]);
		});
	}

	public function actionIndex()
	{
		$this->redirect('/');
	}

	public function actionList($qry = [])
	{
		$this->checkV3Theme();

		$this->layout = 'column2';
		if (Yii::app()->user->isGuest)
		{
			$this->redirect(array('users/view'));
		}
		// $this->layout = 'column2';
		$this->current_page	 = 'Booking_History';
		$this->pageTitle	 = 'My Booking History';
		$userId				 = Yii::app()->user->getId();

		$pageSize = Yii::app()->params['listPerPage'];

		$tab		 = Yii::app()->request->getParam('tab');
		$Bookings	 = Booking::model()->fetchListbyUser($userId, 0);
		$usersList	 = new CArrayDataProvider($Bookings, array('pagination' => array('pageSize' => $pageSize),));
		$models		 = $usersList->getData();
		$this->render('list', array('models' => $models, 'usersList' => $usersList, 'tab' => $tab));
	}

	public function actionView()
	{
		$this->checkV3Theme();
		$this->layout	 = 'column2';
		$this->pageTitle = 'Booking Details';
		$userId			 = Yii::app()->user->getId();
		$bookingID		 = Yii::app()->request->getParam('bookingID');
		$models			 = Booking::model()->findByUsernBookingid($bookingID, $userId);
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('view', array('model' => $models, 'isAjax' => $outputJs), false, $outputJs);
	}

	public function actionCanbooking()
	{
		$this->checkV3Theme();

		$bkid				 = Yii::app()->request->getParam('booking_id');
		$reasonid			 = trim(Yii::app()->request->getParam('bkreason'));
		$reasonText			 = Yii::app()->request->getParam('bkreasontext');
		$view				 = Yii::app()->request->getParam('view', 'web');
		$isBkpn				 = Yii::app()->request->getParam('bkpnlogin');
		$isBkpnAction		 = Yii::app()->request->getParam('bkpnaction');
		$bk_id				 = Yii::app()->request->getParam('bk_id');
		$isCancelReschedule	 = Yii::app()->request->getParam('cancelReschedule', 0);
		if (Yii::app()->user->isGuest && $isBkpnAction == 1)
		{
			throw new CHttpException(403, "Login required.", ReturnSet::ERROR_UNAUTHORISED);
		}
		if (Yii::app()->user->isGuest && $isBkpn != 1)
		{
			$this->redirect('signin');
		}
		if ($reasonText == '')
		{
			$model						 = Booking::model()->findByPk($bkid);
			//$model->scenario			 = 'deny_vendor';
			$model->bkg_pickup_date_date = date('d-m-Y', strtotime($model->bkg_pickup_date));
			$model->bkg_pickup_date_time = date('h:i A', strtotime($model->bkg_pickup_date));
			$model->bkg_pickup_date_date = str_replace("-", "/", $model->bkg_pickup_date_date);
		}
		if (($reasonid != '' || $isCancelReschedule == 1) && $bk_id != '')
		{
			$model = Booking::model()->findByPk($bk_id);
			if ($isCancelReschedule == 1)
			{
				$reasonid	 = CancelReasons::CR_BOOKING_RESCHEDULED;
				$reasonText	 = "Reschedule request cancelled. (previous booking ID: " . Booking::model()->getCodeById($model->bkgPref->bpr_rescheduled_from);
			}

			if ($model->bkg_agent_id == Config::get('Kayak.partner.id') && $isBkpn == 1)
			{

				$verified = Yii::app()->request->getParam('partnerOtpVerified');
				if ($verified != 1)
				{
					$this->forward('booking/SendOTPPartnerCancel');
				}
			}
			$userInfo	 = UserInfo::getInstance();
			$oldModel	 = clone $model;
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
					//$submodel	 = Booking::model()->findByPk($val);
					$bkgid	 = Booking::model()->canBooking($val, $reasonText, $reasonid, $userInfo);
					$desc	 = "Booking cancelled manually.(Reason: " . $reasonText . ")";
					$eventid = BookingLog::BOOKING_CANCELLED;
					BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);
					emailWrapper::bookingCancellationMail($bkgid);
				}
			}



			$userInfo = UserInfo::getInstance();

			if ($model->bkg_agent_id == Config::get('Kayak.partner.id') && Yii::app()->request->getParam('bkpnlogin') == 1)
			{
				$userInfo->userId	 = $model->bkg_agent_id;
				$userInfo->userType	 = UserInfo::TYPE_AGENT;
			}

			Logger::setCategory("trace.module.default.booking.canbooking");
			Logger::trace("booking/canbooking Booking::canBooking started   booking:{$bk_id} reasonid:{$reasonid}");
			$bkgid = Booking::model()->canBooking($bk_id, $reasonText, $reasonid, $userInfo);
			Logger::trace("booking/canbooking Booking::canBooking end");
			Logger::unsetCategory("trace.module.default.booking.canbooking");
			if ($model->bkg_booking_type == 1 && $scvVctId == VehicleCategory::SHARED_SEDAN_ECONOMIC && $model->bkg_flexxi_type == 2 && $model->bkg_fp_id != '')
			{
				$promoterBooking											 = Booking::model()->findByPk($model->bkg_fp_id);
				$fareDetails												 = $promoterBooking->bkgInvoice->calculatePromoterFare($bkgid, true);
				$promoterBooking->bkgInvoice->bkg_base_amount				 = $fareDetails->baseAmount;
				$promoterBooking->bkgInvoice->bkg_state_tax					 = $fareDetails->stateTax;
				$promoterBooking->bkgInvoice->bkg_toll_tax					 = $fareDetails->tollTaxAmount;
				$promoterBooking->bkgInvoice->bkg_driver_allowance_amount	 = $fareDetails->driverAllowance;
				$promoterBooking->bkgInvoice->bkg_service_tax				 = $fareDetails->gst;
				$promoterBooking->bkgInvoice->populateAmount(false, false, true, false);
				$promoterBooking->save();
			}



			if ($bkgid)
			{
				$desc	 = "Booking cancelled by user.(Reason: " . $reasonText . ")";
				$eventid = BookingLog::BOOKING_CANCELLED;
				BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);
				emailWrapper::bookingCancellationMail($bkgid);
				if ($isCancelReschedule == 1)
				{
					$model									 = Booking::model()->findByPk($bkgid);
					$model->bkgPref->bpr_rescheduled_from	 = 0;
					if (!$model->bkgPref->save())
					{
						echo json_encode(['success' => false]);
						Yii::app()->end();
					}
					echo json_encode(['success' => true]);
					Yii::app()->end();
				}
			}
			if ($view == 'mobile')
			{
				echo "success";
				Yii::app()->end();
			}
			else
			{
				if ($model->bkg_agent_id == Config::get('Kayak.partner.id') && $isBkpn == 1)
				{
					$status = ($bkgid) ? 1 : 0;
					echo json_encode(['success' => $status]);
					Yii::app()->end();
				}
				else if ($isBkpnAction == 1)
				{
					$hash	 = Yii::app()->shortHash->hash($bk_id);
					$url	 = Yii::app()->createUrl('booking/paynow', ['id' => $bk_id, 'hash' => $hash]);
					$this->redirect($url);
				}
				else
				{
					$this->redirect(array('list'));
				}
			}
			//}
		}
		if ($model->validate())
		{
			if ($view == 'mobile')
			{
				echo "";
				Yii::app()->end();
			}
			else
			{
				$this->renderPartial('canbooking', array('bkid' => $bkid, 'isBkpn' => $isBkpn, 'isBkpnAction' => $isBkpnAction));
			}
		}
//		else
//		{
//			echo "Pickup time should be atleast 4 hours to cancel. Please contact our customer support.";
//			Yii::app()->end();
//		}
	}

	public function actionEdit()
	{
		$this->checkV2Theme();

		$this->pageTitle = "Edit booking";
		$bkg_id			 = Yii::app()->request->getParam('bkg_id');
		$model			 = Booking::model()->findByPk($bkg_id);
		$model->scenario = 'deny_consumer';
		if ($model->validate())
		{
			if (isset($_REQUEST['Booking']) || isset($_REQUEST['BookingAddInfo']) || isset($_REQUEST['BookingUser']))
			{
				$model->attributes	 = Yii::app()->request->getParam('Booking');
				$model->attributes	 = Yii::app()->request->getParam('BookingAddInfo');
				$model->attributes	 = Yii::app()->request->getParam('BookingUser');

				if ($model->bkgAddInfo->bkg_info_source == '')
				{
					$model->bkgAddInfo->bkg_info_source = 'Others';
				}
				$model->scenario = 'deny_consumer';
				if ($model->validate())
				{
					$bkmodel								 = Booking::model()->findByPk($model->bkg_id);
					$oldModel								 = clone $bkmodel;
					$oldData								 = Booking::model()->getDetailsbyId($model->bkg_id);
					$model->bkgAddInfo->save();
					$isRealtedBooking						 = $model->findRelatedBooking($model->bkg_id);
					$model->bkgTrail->bkg_is_related_booking = ($isRealtedBooking) ? 1 : 0;
					$newData								 = Booking::model()->getDetailsbyId($model->bkg_id);
					$getDifference							 = array_diff_assoc($newData, $oldData);
					$getOldDifference						 = array_diff_assoc($oldData, $newData);
					$changesForConsumer						 = $this->getModificationMSG($getDifference, 'consumer');
					$changesForVendor						 = $this->getModificationMSG($getDifference, 'vendor');
					$changesForDriver						 = $this->getModificationMSG($getDifference, 'driver');
					$changesForLog							 = " Old Values: " . $this->getModificationMSG($getOldDifference, 'log');
					$number									 = $model->bkgUserInfo->bkg_country_code . $model->bkgUserInfo->bkg_contact_no;
					$ext									 = $model->bkgUserInfo->bkg_country_code;
					$phone									 = $model->bkgUserInfo->bkg_contact_no;
					$bookingID								 = $model->bkg_booking_id;

					$cabmodel	 = $model->getBookingCabModel();
					$msgCom		 = new smsWrapper();
					if ($model->bkgUserInfo->bkg_contact_no != '' && trim($changesForConsumer) != '')
					{
						$logType = UserInfo::TYPE_SYSTEM;
						notificationWrapper::customerNotifyBookingModified($model->bkg_id, $changesForConsumer);

						//$logType = UserInfo::TYPE_SYSTEM;
						//$msgCom->informChangesToCustomer($ext, $phone, $bookingID, $changesForConsumer, $logType);
					}
					if ($cabmodel->bcb_driver_phone != '' && trim($changesForDriver) != '')
					{
						$logType = UserInfo::TYPE_SYSTEM;
						$msgCom->informChangesToDriver('91', $model->bkg_extdriver_contact, $bookingID, $changesForDriver, $logType);
					}
					if ($cabmodel->bcbVendor->vnd_phone != '' && trim($changesForVendor) != '')
					{
						$logType = UserInfo::TYPE_SYSTEM;
						$msgCom->informChangesToVendor('91', $model->bkgAgent->agt_phone, $bookingID, $changesForVendor, $logType);
					}
					if ($cabmodel->bcb_vendor_id != '' && $model->bkg_status > 2 && trim($changesForVendor) != '')
					{
						$tripStatus		 = $cabmodel->getLowestBookingStatusByTrip($cabmodel->bcb_id, $cabmodel->bcb_pending_status);
						$tripBkgStatus	 = 0;
						if ($tripStatus)
						{
							$tripBkgStatus = $tripStatus;
						}
						$payLoadData = ['tripId' => $cabmodel->bcb_id, 'Status' => $tripBkgStatus, 'EventCode' => Booking::CODE_MODIFIED];
						$success	 = AppTokens::model()->notifyVendor($cabmodel->bcb_vendor_id, $payLoadData, $changesForVendor, $model->bkg_booking_id . " details has been modified.");
					}
					if ($model->bkgUserInfo->bkg_user_id != '' && trim($changesForConsumer) != '')
					{
//						$notificationId	 = substr(round(microtime(true) * 1000), -5);
//						$payLoadData1	 = ['bookingId' => $model->bkg_booking_id, 'EventCode' => Booking::CODE_MODIFIED];
//						$success1		 = AppTokens::model()->notifyConsumer($model->bkgUserInfo->bkg_user_id, $payLoadData1, $notificationId, $changesForConsumer, $model->bkg_booking_id . " details has been modified.");
					}
					$logDesc	 = "Booking modified";
					$eventid	 = BookingLog::BOOKING_MODIFIED;
					$desc		 = $logDesc . $changesForLog;
					$bkgid		 = $model->bkg_id;
					$userInfo	 = UserInfo::getInstance();
					BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);
					$data		 = ['success' => true];
					if (Yii::app()->request->isAjaxRequest)
					{
						echo json_encode($data);
						Yii::app()->end();
					}
				}
				else
				{
					if ($model->hasErrors())
					{
						$result = [];
						foreach ($model->getErrors() as $attribute => $errors)
						{
							$result[CHtml::activeId($model, $attribute)] = $errors;
						}

						$data = ['success' => false, 'errors' => $result];
					}
					if (Yii::app()->request->isAjaxRequest)
					{
						echo json_encode($data);
						Yii::app()->end();
					}
				}
			}
			$this->renderPartial('edit', array('model' => $model), false, true);
		}
		else
		{
			echo "<span style='color:red'>Pickup time should be atleast 36 hours to modify. Please contact our customer support.</span>";
			Yii::app()->end();
		}
	}

	public function actionInvoice()
	{
		$this->checkV2Theme();
		$this->actionReceipt();
	}

	public function actionInvoiceCopy()
	{
		$bkgId	 = Yii::app()->request->getParam('bkgId');
		$hash	 = Yii::app()->request->getParam('hash');

		if ($bkgId != Yii::app()->shortHash->unHash($hash))
		{
			throw new Exception('Invalid Data', ReturnSet::ERROR_INVALID_DATA);
		}
		#BookingInvoice::generatePDFInvoice($bkgId);
		$this->actionReceipt();
	}

	public function actionReceipt()
	{
		$this->checkV3Theme();

		$bkgId	 = Yii::app()->request->getParam('bkgId');
		$bkg	 = Yii::app()->request->getParam('bkg');
		$hash	 = Yii::app()->request->getParam('hash');
		$hsh	 = Yii::app()->request->getParam('hsh');
		$email	 = Yii::app()->request->getParam('email', 0);
		$isPdf	 = Yii::app()->request->getParam('pdf', 1);
		$bkgId	 = $bkgId . $bkg;
		$hash	 = $hash . $hsh;
		$address = Config::getGozoAddress();

		//		if ($bkgId != Yii::app()->shortHash->unHash($hash))
		//		{
		//			throw new CHttpException(400, 'Invalid data. ');
		//		}
		$model = Booking::model()->findByPk($bkgId);
		if ($model->bkg_status > 7 && $model->bkg_status != 9)
		{
			throw new Exception('Booking not active', 401);
		}
		$errorStr = false;
		if ($model->bkg_pickup_date < '2023-04-01')
		{
			echo $errorStr = 'Link expired';
			Yii::app()->end();
		}
		$invoiceList		 = Booking::model()->getInvoiceByBooking($bkgId);
		$totPartnerCredit	 = AccountTransDetails::getTotalPartnerCredit($bkgId);
		$totAdvance			 = PaymentGateway::model()->getTotalAdvance($bkgId);
		$totAdvanceOnline	 = PaymentGateway::model()->getTotalOnlinePayment($bkgId);
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
				'totAdvanceOnline'	 => $totAdvanceOnline,
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
				$html2pdf->Output($file, 'D');
				echo $file;
				Yii::app()->end();
			}
			else
			{
				$filename = $model->bkg_booking_id . date('Ymd', strtotime($model->bkg_pickup_date)) . '.pdf';
				ob_start();
				$html2pdf->Output($filename, 'D');
			}
		}
		else
		{
			$this->renderPartial('//invoice/view', array('invoiceList'		 => $invoiceList,
				'totPartnerCredit'	 => $totPartnerCredit,
				'errorStr'			 => $errorStr,
				'totAdvance'		 => $totAdvance,
				'totAdvanceOnline'	 => $totAdvanceOnline,
				'address'			 => $address,
				'isPDF'				 => false), false, true);
		}
	}

	public function actionVerifybooking()
	{
		$this->checkV2Theme();
		$bkid			 = Yii::app()->request->getParam('booking_id');
		$model			 = Booking::model()->findByPk($bkid);
		$model->scenario = 'adminupdate';
		if ($model->validate())
		{
			if (Yii::app()->request->isAjaxRequest)
			{
				echo true;
				Yii::app()->end();
			}
		}
		else
		{
			if (Yii::app()->request->isAjaxRequest)
			{
				echo false;
				Yii::app()->end();
			}
		}
	}

	public function canbooking1($id, $reason1, $reasonId)
	{
		$reason		 = trim($reason1);
		$model		 = Booking::model()->findByPk($id);
		$oldModel	 = clone $model;
		$userInfo	 = UserInfo::getInstance();
		$success	 = Booking::model()->canBooking($id, $reason, $reasonId, $userInfo);
		if ($success)
		{
			$bkgid		 = $success;
			$desc		 = "Booking cancelled by user.(Reason: " . $reason . ")";
			$eventid	 = BookingLog::BOOKING_CANCELLED;
			BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);
			$emailObj	 = new emailWrapper();
			$emailObj->bookingCancellationMail($model->bkg_id);
			return true;
		}
		return false;
	}

	public function actionAjaxverification()
	{
		$arr	 = Yii::app()->request->getParam('Booking');
		$vcode	 = trim($arr['bkg_verification_code1']);
		$success = 'unsuccess';

		echo $success;
		Yii::app()->end();
	}

	public function cabrateService()
	{
		$bkgid	 = Yii::app()->request->getParam('bkg_id');
		$model	 = new BookingTemp;
		if ($bkgid != '')
		{
			$model = BookingTemp::model()->findbyPk($bkgid);
		}
		$model->bkg_user_id		 = Yii::app()->request->getParam('bkg_user_id');
		$model->bkg_from_city_id = Yii::app()->request->getParam('bkg_from_city_id');
		$model->bkg_to_city_id	 = Yii::app()->request->getParam('bkg_to_city_id');
		$model->bkg_route_id	 = Route::model()->getRutidbyCities($model->bkg_from_city_id, $model->bkg_to_city_id);
		$date1					 = Yii::app()->request->getParam('bkg_pickup_date_date');
		$time1					 = Yii::app()->request->getParam('bkg_pickup_date_time');
		if ($date1 != "" && $time1 != "")
		{
			$date					 = date('Y-m-d', strtotime($date1));
			$time					 = date('H:i:00', strtotime($time1));
			$model->bkg_pickup_date	 = $date . " " . $time;
			$model->bkg_pickup_time	 = $time;
		}
		$model->bkg_pickup_date_date	 = str_replace("-", "/", $date1);
		$model->bkg_user_ip				 = \Filter::getUserIP();
		$model->bkg_user_device			 = "old consumer app";
		$model->bkg_user_ip				 = \Filter::getUserIP();
		$cityinfo						 = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
		$model->bkg_user_city			 = $cityinfo['city'];
		$model->bkg_user_country		 = $cityinfo['country'];
		$model->bkg_user_device			 = UserLog::model()->getDevice();
		$model->bkg_platform			 = Booking::Platform_App;
		$fcityname						 = Cities::getName(Yii::app()->request->getParam('bkg_from_city_id'));
		$tcityname						 = Cities::getName(Yii::app()->request->getParam('bkg_to_city_id'));
		$fcityname						 = str_replace(" ", "%20", $fcityname);
		$tcityname						 = str_replace(" ", "%20", $tcityname);
		$from_city_geocode				 = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . $fcityname . '&sensor=false');
		$to_city_geocode				 = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . $tcityname . '&sensor=false');
		$arr							 = json_decode($from_city_geocode);
		$from_lat_long					 = $arr->results[0]->geometry->location->lat . "," . $arr->results[0]->geometry->location->lng;
		$arr1							 = json_decode($to_city_geocode);
		$to_lat_long					 = $arr1->results[0]->geometry->location->lat . "," . $arr1->results[0]->geometry->location->lng;
		$geocodeFrom					 = file_get_contents('http://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $from_lat_long . '&destinations=' . $to_lat_long . '&sensor=false&units=metric&mode=driving');
		$arr_distance					 = json_decode($geocodeFrom);
		$model->trip_distance_format	 = $arr_distance->rows[0]->elements[0]->distance->text;
		$model->trip_duration_format	 = $arr_distance->rows[0]->elements[0]->duration->text;
		$model->bkg_trip_distance		 = round(($arr_distance->rows[0]->elements[0]->distance->value) / 1000);
		$model->bkg_trip_duration		 = round(($arr_distance->rows[0]->elements[0]->duration->value) / 60);
		$model->scenario				 = 'step1';
		$model->bkg_user_last_updated_on = new CDbExpression('NOW()');
		$model->save();
		$model->bkg_booking_type		 = 1;
		$bktypArr						 = ['1' => 'OW', '2' => 'RT'];
		$booking_id						 = $bktypArr[$model->bkg_booking_type] . date('Y') . str_pad($model->bkg_id, 4, 0, STR_PAD_LEFT);
		$model->bkg_booking_id			 = $booking_id;
		$model->bkg_user_last_updated_on = new CDbExpression('NOW()');
		$model->save();
		return $model;
	}

	public function bookingList1()
	{
		$userid	 = Yii::app()->user->getId();
		$sort	 = Yii::app()->request->getParam('sort');
		$model	 = Booking::model()->fetchListbyUserforMob($userid, $sort);
		return $model;
	}

	public function actionConfirm()
	{
		$id		 = Yii::app()->request->getParam('id');
		$bModel	 = Booking::model()->findByPk($id);

		//$vmodel = VehicleTypes::model()->findbyPk($bModel->bkg_vehicle_type_id);
		$vehicleCatId	 = $bModel->bkgSvcClassVhcCat->scv_vct_id;
		$vmodel			 = VehicleCategory::model()->findbyPk($vehicleCatId);
		$this->render('confirm', array('model' => $bModel, 'vmodel' => $vmodel));
	}

	public function actionCodeverify()
	{
		if (isset($_POST['bkgid']))
		{
			$bkgid		 = Yii::app()->request->getParam('bkgid');
			$bkgvcode	 = Yii::app()->request->getParam('bkgvcode');
			$model		 = Booking::model()->findbyPk($bkgid);
			$vcode		 = $model->confirmVerification($bkgvcode);
			//   $model = Booking::model()->findbyPk($_POST['bkgid']);
			if ($vcode)
			{
				$verifyresult = 'true';
			}
			else
			{
				$verifyresult = 'false';
			}
		}
		echo CJSON::encode(['vcode'	 => $verifyresult
			, 'status' => $model->bkg_status
		]);
	}

	public function actionCodeverification()
	{
		if (isset($_REQUEST['bkgid']))
		{
			$bkgid		 = Yii::app()->request->getParam('bkgid');
			$bkgvcode	 = Yii::app()->request->getParam('bkgvcode');
			$model		 = Booking::model()->findbyPk($bkgid);

			if ($model->bkg_verification_code == $bkgvcode)
			{
				$verifyresult = 'true';
			}
			else
			{
				$verifyresult = 'false';
			}
		}
		echo CJSON::encode(['vcode' => $verifyresult]);
	}

	public function actionSendreviewmail()
	{
		echo $bkgid		 = Yii::app()->request->getParam('bkid');
		$emailCom	 = new emailWrapper();
		$emailCom->markComplete($bkgid);
	}

	// One Way Trip
	public function actionStep1()
	{
		$model = new Booking('step1');
		if (isset($_REQUEST) && $_REQUEST['rut_id'] != '')
		{
			$rut_id					 = Yii::app()->request->getParam('rut_id');
			$model->attributes		 = Yii::app()->request->getParam('Booking');
			$route					 = Route::model()->findByPk($rut_id);
			$pickdateid				 = Yii::app()->request->getParam('pickdate');
			$model->bkg_pickup_date	 = date('Y-m-d H:i:s', strtotime($pickdateid));
			$model->bkg_from_city_id = $route->rut_from_city_id;
			$model->bkg_to_city_id	 = $route->rut_to_city_id;
		}

		$this->renderPartial('step1', array('model' => $model));
	}

	public function actionValidateSearch()
	{
		$return	 = ['success' => false];
		$model	 = new BookingRoute('validate');

		if (isset($_REQUEST['BookingRoute']))
		{
			$arr = Yii::app()->request->getParam('BookingRoute');

			$model->attributes = $arr;

			$result = CActiveForm::validate($model);

			if ($result == '[]')
			{
				$return['success'] = true;
			}
			else
			{
				$return['errors'] = CJSON::decode($result);
			}
		}
		echo CJSON::encode($return);
		Yii::app()->end();
	}

	public function actionValidateDayRentalSearch()
	{
		$return	 = ['success' => false];
		$model	 = new BookingRoute('validateDayRental');

		if (isset($_REQUEST['BookingRoute']))
		{
			$arr				 = Yii::app()->request->getParam('BookingRoute');
			$model->tripType	 = 9;
			$model->attributes	 = $arr;
			$result				 = CActiveForm::validate($model);

			if ($result == '[]')
			{
				$return['success'] = true;
			}
			else
			{
				$return['errors'] = CJSON::decode($result);
			}
		}
		echo CJSON::encode($return);
		Yii::app()->end();
	}

	public function actionValidateOneway()
	{
		$fromCityId		 = Yii::app()->request->getParam('fromCityId');
		$toCityId		 = Yii::app()->request->getParam('toCityId');
		$isAirport		 = 0;
		$airportRadius	 = 0;
		$success		 = false;
		$bkType			 = 1;
		$transferType	 = 0;
		if ($fromCityId > 0 && $toCityId > 0)
		{
			$routeArrs1	 = Cities::model()->getDetailsByCityId($fromCityId);
			$routeArrs2	 = Cities::model()->getDetailsByCityId($toCityId);
			$success	 = true;
			if ($routeArrs1['cty_is_airport'] == 1)
			{
				$isAirport		 += 1;
				$airportRadius	 = Cities::getCtyRadiusByCtyId($routeArrs1['cty_id']);
				$transferType	 = 1;
			}
			elseif ($routeArrs2['cty_is_airport'] == 1)
			{
				$isAirport		 += 1;
				$airportRadius	 = Cities::getCtyRadiusByCtyId($routeArrs2['cty_id']);
				$transferType	 = 2;
			}

			if ($isAirport > 0 && $airportRadius > 0)
			{
				$distance	 = ROUND(SQRT(POW(69.1 * ($routeArrs1['cty_lat'] - $routeArrs2['cty_lat']), 2) + POW(69.1 * ($routeArrs2['cty_long'] - $routeArrs1['cty_long']) * COS($routeArrs1['cty_lat'] / 57.3), 2)), 2);
				$dis		 = $distance * 1.60934;
				if ($dis <= $airportRadius && $dis > 0)
				{
					$bkType	 = 4;
					$success = true;
				}
			}
		}
		//  $sessKey = Yii::app()->session['_cus_key'];
		$result = ['success' => $success, 'bkType' => $bkType, 'from' => $routeArrs1, 'to' => $routeArrs2, 'transferType' => $transferType];
		echo json_encode($result);
	}

	public function actionValidateDayRental()
	{
		$fromCityId	 = Yii::app()->request->getParam('fromCityId');
		$bkType		 = Yii::app()->request->getParam('bkType');

		$isAirport		 = 0;
		$airportRadius	 = 0;
		$success		 = false;
		$errorMsg		 = '';

		if ($fromCityId > 0)
		{
			$routeArrs1 = Cities::model()->getDetailsByCityId($fromCityId);
		}
		else
		{
			$success	 = false;
			$errorMsg	 = 'Select Source City';
			goto skipStep;
		}

		if ($bkType == '')
		{
			$success	 = false;
			$errorMsg	 = 'Select Rental Type';
			goto skipStep;
		}

		if ($routeArrs1 > 0)
		{
			$success = true;
		}
		else
		{
			$success	 = false;
			$errorMsg	 = 'Day rental service not provide on that selected city';
		}

		skipStep:
		$result = ['success' => $success, 'bkType' => $bkType, 'from' => $routeArrs1, 'to' => $routeArrs1, 'errorMsg' => $errorMsg];
		echo json_encode($result);
	}

	public function actionValidateAirport()
	{
		$return = ['success' => false];

		if (!isset($_REQUEST['BookingRoute']))
		{
			goto end;
		}

		$model	 = new BookingRoute('validate');
		$btModel = new BookingTemp();
		$arr	 = Yii::app()->request->getParam('BookingRoute');
		$btArr	 = Yii::app()->request->getParam('BookingTemp');

		$ctr = Yii::app()->request->getParam('ctr', 0);
		if ($ctr > 0)
		{
			$model->brt_pickup_date_date = $arr[$ctr]['brt_pickup_date_date'];
		}
		$btModel->attributes = $btArr;
		$model->attributes	 = $arr;

		$model->populateAirport($btModel->bkg_transfer_type);

		if (!$model->validate())
		{
			$return['errors'] = $model->getErrors();
			goto end;
		}

		$res = $model->setRouteForAirportTransfer($model);
		if ($res == '[]')
		{
			$return['success'] = true;
			goto end;
		}

		$r1 = CJSON::decode($res);
		if (isset($r1['bkType']))
		{
			$return['success'] = true;
		}

		$return['errors'] = $r1;

		end:
		echo CJSON::encode($return);
		Yii::app()->end();
	}

	public function actionBookNow()
	{
		try
		{
			$this->enableClarity();

			$view	 = 'booknow';
			$request = Yii::app()->request;
			// Model
			$model	 = $this->getModel();
			if (!$model)
			{
				$model = new BookingTemp();
				$model->loadDefaults();
			}

			$cabtype = $request->getParam('cabsegmentation');

			// Step
			$step				 = $request->getParam('step', 0);
			$url				 = Yii::app()->request->requestUri;
			$url_arr			 = explode("/", $url);
			$modelAttr			 = $request->getParam('BookingTemp');
			$model->attributes	 = $modelAttr;
			$model->bktyp		 = $modelAttr['bktyp'];
			$routes[]			 = $request->getParam('BookingRoute');

			if ($request->cookies->contains('gozo_agent_id'))
			{
				$model->bkg_agent_id = Yii::app()->request->cookies['gozo_agent_id']->value;
			}
			if ($request->cookies->contains('gozo_qr_id'))
			{
				$model->bkg_qr_id = Yii::app()->request->cookies['bkg_qr_id']->value;
			}
			if ($url_arr[2] != "")
			{
				$step = $request->getParam('step', 1);

				$trip = $url_arr[2];

				if (array_key_exists($trip, $model->booking_type_url))
				{
					$tripType = $model->booking_type_url[$trip];
				}
				else
				{
					$tripType = 1;
				}
				$from_city		 = $url_arr[3];
				$to_city		 = $url_arr[4];
				$from_city_id	 = Cities::model()->getIdByCityAlias($from_city);
				$to_city_id		 = Cities::model()->getIdByCityAlias($to_city);

				$routes[0]['brt_from_city_id']	 = $from_city_id;
				$routes[0]['brt_to_city_id']	 = $to_city_id;
				$model['bkg_booking_type']		 = $tripType;
			}
			else
			{
				if (!$request->isPostRequest)
				{
					goto skipStep2;
				}
				if (in_array($model->bkg_booking_type, [9, 10, 11]))
				{
					$model->bkg_to_city_id = $model->bkg_from_city_id;
				}
			}

			if (in_array($model->bkg_booking_type, [4]) && $step == 1)
			{
				$brtModel				 = new BookingRoute('validate');
				$brtModel->attributes	 = $routes[0];
				$brtModel->populateAirport($model->bkg_transfer_type);
				$routes[0]				 = $brtModel;
			}

			$model->setRoutes($routes);

			$quotes = false;

			if (Yii::app()->request->isPostRequest && UserInfo::isLoggedIn() && !in_array($model->bkg_booking_type, [2, 3]) && $step == 1)
			{
				$model->scenario = 'validateStep1';
				$result			 = CActiveForm::validate($model, null, false);
				if ($result != '[]')
				{
					goto skipStep2;
				}

				$errors = BookingRoute::validateRoutes($model->bookingRoutes);
				if (count($errors) > 0)
				{
					goto skipStep2;
				}

				$quotes		 = $model->createLeadAndGetQuotes($isAllowed	 = true);
				$step		 = 2;
			}
			if (in_array($model->bkg_booking_type, [2, 3]))
			{
				$count		 = count($model->bookingRoutes);
				$lastRoute	 = $model->bookingRoutes[$count - 1];
				$errors		 = BookingRoute::validateRoutes($model->bookingRoutes, $model->bkg_booking_type);
			}

			skipStep2:
		}
		catch (Exception $ex)
		{
			$data = ReturnSet::renderJSONException($ex);
			echo CJSON::encode($data);
		}


		$organisationSchemaRaw			 = StructureData::getOrganisation();
		$jsonproviderStructureMarkupData = json_encode($organisationSchemaRaw, JSON_UNESCAPED_SLASHES);

		$routeBreadcumbStructureMarkupData = StructureData::breadCrumbSchema($from_city_id, $to_city_id, 'trip_type');

		if ($tripType > 0)
		{

			$jsonStructureMarkupData = StructureData::getProductSchemaforTrip($model->bookingRoutes, $tripType);
		}
		$this->renderAuto($view, ['model'								 => $model,
			'jsonStructureMarkupData'			 => $jsonStructureMarkupData,
			'routeBreadcumbStructureMarkupData'	 => $routeBreadcumbStructureMarkupData,
			'jsonproviderStructureMarkupData'	 => $jsonproviderStructureMarkupData,
			'step'								 => $step, 'cabtype'							 => $cabtype, 'estArrTime'						 => $lastRoute->arrival_time, 'quotes'							 => $quotes]);
	}

	public function actionPickup()
	{
		$request = Yii::app()->request;
		$model	 = $this->getModel();
		if (!$model)
		{
			$model = new BookingTemp();
			$model->loadDefaults();
		}
		// Steps
		$step				 = $request->getParam('step', 1);
		$model->attributes	 = $request->getParam('BookingTemp');
		$model->stepOver	 = 1;
		if ($model->bookingRoutes == null)
		{
			$model->bookingRoutes	 = [];
			$model->bookingRoutes[]	 = new BookingRoute();
		}
		$this->renderAuto("bkRoute", ['model' => $model, 'step' => $step], false, true);
	}

	public function actionPickupnew()
	{
		$request = Yii::app()->request;
		$model	 = $this->getModel();
		if (!$model)
		{
			$model = new BookingTemp();
			$model->loadDefaults();
		}
		// Steps
		$step					 = $request->getParam('step', 1);
		$model->attributes		 = $request->getParam('BookingTemp');
		$model->stepOver		 = 1;
		$bookingType			 = explode("_", $model->bkg_booking_type);
		$model->bkg_booking_type = $bookingType[0];
		$type					 = $bookingType[1];

		if ($model->bookingRoutes == null)
		{
			$model->bookingRoutes	 = [];
			$model->bookingRoutes[]	 = new BookingRoute();
		}

		$this->renderAuto("bkRoute", ['model' => $model, 'step' => $step, 'bkgtype' => $type], false, true);
	}

	public function actionRates()
	{
		try
		{
			/** @var CHttpRequest $request */
			$request = Yii::app()->request;
			$model	 = $this->getModel();

			if (!$model)
			{
				$model = new BookingTemp();
				$model->loadDefaults();
			}
			// Steps
			$step				 = $request->getParam('step', 2);
			$modelAttr			 = $request->getParam('BookingTemp');
			$model->attributes	 = $modelAttr;
			$model->bktyp		 = $modelAttr['bktyp'];
			$model->stepOver	 = $modelAttr['stepOver'];
			Logger::beginProfile("Rates1");

			/////////////
			if (isset($request->getParam('BookingTemp')['fullContactNumber']))
			{
				$dataTemp				 = $request->getParam('BookingTemp');
				$fullContactNumber		 = str_replace(' ', '', $dataTemp['fullContactNumber']);
				$model->bkg_contact_no	 = str_replace(' ', '', $model->bkg_contact_no);
				if ($fullContactNumber != $model->bkg_contact_no)
				{
					Filter::parsePhoneNumber($fullContactNumber, $code, $number);
					$model->bkg_country_code = $code;
					$model->bkg_contact_no	 = $number;
				}
			}
			$routes = $request->getParam('BookingRoute');

			if (in_array($model->bkg_booking_type, [1, 4]) && $model->bktyp == 4)
			{
				$brtModel					 = new BookingRoute('validate');
				$brtModel->attributes		 = $routes;
				$brtModel->populateAirport($model->bkg_transfer_type);
				$routes						 = [$brtModel];
				$model->bkg_pickup_date_date = $brtModel->brt_pickup_date_date;
				$model->bkg_pickup_date_time = $brtModel->brt_pickup_date_time;
			}
			if ($model->bkg_booking_type == 5 && $model->bkg_package_id > 0)
			{
				$pckid					 = $model->bkg_package_id;
				$date					 = DateTimeFormat::DatePickerToDate($model->bkg_pickup_date_date);
				$time					 = date('H:i:00', strtotime($model->bkg_pickup_date_time));
				$model->bkg_pickup_date	 = $date . " " . $time;
				$pickupDt				 = $model->bkg_pickup_date;
				$routes					 = BookingRoute::model()->populateRouteByPackageId($pckid, $pickupDt);
			}

			$model->setRoutes($routes);

			// Validate
			$model->scenario = 'validateStep1';
			$result			 = CActiveForm::validate($model, null, false);
			Logger::endProfile("Rates1");

			if ($result != '[]')
			{
				throw new Exception($result, ReturnSet::ERROR_VALIDATION);
			}
			if ($request->cookies->contains('gozo_agent_id'))
			{
				$model->bkg_agent_id = Yii::app()->request->cookies['gozo_agent_id']->value;
			}
			if ($request->cookies->contains('gozo_qr_id'))
			{
				$model->bkg_qr_id = Yii::app()->request->cookies['bkg_qr_id']->value;
			}
			// BookingTemp
			if ($model->bkg_booking_type == 7)
			{
				$model->save();
				$arr['fromCity'] = $model->bkg_from_city_id;
				$arr['toCity']	 = $model->bkg_to_city_id;
				$arr['pickDate'] = DateTimeFormat::DatePickerToDate($model->bkg_pickup_date_date);

				$rModel						 = Route::model()->populate($model->bkg_from_city_id, $model->bkg_to_city_id);
				$model->bkg_trip_distance	 = $rModel['model']->rut_estm_distance;
				$model->bkg_trip_duration	 = $rModel['model']->rut_estm_time;

				$shuttles = Shuttle::model()->fetchData($arr, true);
			}
			else
			{
				$quotes		 = $model->createLeadAndGetQuotes($isAllowed	 = true);
//var_dump($quotes);exit;
				if (empty($quotes))
				{
					throw new Exception("No cabs available for this route", ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
			}
		}
		catch (Exception $ex)
		{
			ReturnSet::renderJSONException($ex);
		}
		$view	 = ($model->bkg_booking_type == 7) ? 'shuttleQuote' : 'bkQuoteNew';
		$view	 = ($model->bkg_is_gozonow == 1) ? 'bkQuoteGozoNow' : $view;

		$this->renderAuto($view, [
			'model'		 => $model,
			'step'		 => $step,
			'quotes'	 => $quotes,
			'shuttles'	 => $shuttles,
			'stepOver'	 => $model->stepOver]);
	}

	public function actionRatesVO()
	{
		try
		{
			/** @var CHttpRequest $request */
			$userInfo	 = UserInfo::getInstance();
			$userId		 = $userInfo->userId;

			$request = Yii::app()->request;
			$model	 = $this->getModel();

			if (!$model)
			{
				$model = new BookingTemp();
				$model->loadDefaults();
			}
			// Steps
			$step				 = $request->getParam('step', 2);
			$modelAttr			 = $request->getParam('BookingTemp');
			$model->attributes	 = $modelAttr;
			$model->bktyp		 = $modelAttr['bktyp'];
			$model->stepOver	 = $modelAttr['stepOver'];
			Logger::beginProfile("Rates1");
			/////////////
			if (isset($request->getParam('BookingTemp')['fullContactNumber']))
			{
				$dataTemp				 = $request->getParam('BookingTemp');
				$fullContactNumber		 = str_replace(' ', '', $dataTemp['fullContactNumber']);
				$model->bkg_contact_no	 = str_replace(' ', '', $model->bkg_contact_no);
				if ($fullContactNumber != $model->bkg_contact_no)
				{
					Filter::parsePhoneNumber($fullContactNumber, $code, $number);
					$model->bkg_country_code = $code;
					$model->bkg_contact_no	 = $number;
				}
			}
			$routes = $request->getParam('BookingRoute');

			if ($model->bkg_booking_type == 2 && count($routes) > 0)
			{
				$keyRoutes = array_keys($routes);
				if ($routes[$keyRoutes[0]]['brt_from_city_id'] != '' && $routes[$keyRoutes[0]]['brt_to_city_id'] != '')
				{
					$routes[$keyRoutes[1]]['brt_from_city_id']		 = $routes[$keyRoutes[0]]['brt_to_city_id'];
					$routes[$keyRoutes[1]]['brt_pickup_date_date']	 = $routes[$keyRoutes[1]]['brt_return_date_date'];
					$routes[$keyRoutes[1]]['brt_pickup_date_time']	 = $routes[$keyRoutes[1]]['brt_return_date_time'];
				}
			}
			if (in_array($model->bkg_booking_type, [1, 4]) && $model->bktyp == 4)
			{
				$brtModel					 = new BookingRoute('validate');
				$brtModel->attributes		 = $routes;
				$brtModel->populateAirport($model->bkg_transfer_type);
				$routes						 = [$brtModel];
				$model->bkg_pickup_date_date = $brtModel->brt_pickup_date_date;
				$model->bkg_pickup_date_time = $brtModel->brt_pickup_date_time;
			}
			if ($model->bkg_booking_type == 5 && $model->bkg_package_id > 0)
			{
				$pckid					 = $model->bkg_package_id;
				$date					 = DateTimeFormat::DatePickerToDate($model->bkg_pickup_date_date);
				$time					 = date('H:i:00', strtotime($model->bkg_pickup_date_time));
				$model->bkg_pickup_date	 = $date . " " . $time;
				$pickupDt				 = $model->bkg_pickup_date;
				$routes					 = BookingRoute::model()->populateRouteByPackageId($pckid, $pickupDt);
			}

			$model->setRoutes($routes);

			// Validate
			$model->scenario = 'validateStep1';
			$result			 = CActiveForm::validate($model, null, false);
			Logger::endProfile("Rates1");

			if ($result != '[]')
			{
				throw new Exception($result, ReturnSet::ERROR_VALIDATION);
			}
			if ($request->cookies->contains('gozo_agent_id'))
			{
				$model->bkg_agent_id = Yii::app()->request->cookies['gozo_agent_id']->value;
			}
			if ($request->cookies->contains('gozo_qr_id'))
			{
				$model->bkg_qr_id = Yii::app()->request->cookies['bkg_qr_id']->value;
			}

			// BookingTemp
			if ($model->bkg_booking_type == 7)
			{
				$model->save();
				$arr['fromCity'] = $model->bkg_from_city_id;
				$arr['toCity']	 = $model->bkg_to_city_id;
				$arr['pickDate'] = DateTimeFormat::DatePickerToDate($model->bkg_pickup_date_date);

				$rModel						 = Route::model()->populate($model->bkg_from_city_id, $model->bkg_to_city_id);
				$model->bkg_trip_distance	 = $rModel['model']->rut_estm_distance;
				$model->bkg_trip_duration	 = $rModel['model']->rut_estm_time;

				$shuttles = Shuttle::model()->fetchData($arr, true);
			}
			else
			{

				if (!$userId)
				{
//print_r($request->getParam('BookingRoute'));
//exit;
					$jsonObj	 = CJSON::decode($request, false);
					$jsonMapper	 = new JsonMapper();
					$obj		 = $jsonMapper->map($jsonObj, new \Stub\booking\CreateRequest());

					/** @var Booking $model */
					$model1 = $obj->getModelVO($model);
					$request;
				}
				$quotes		 = $model->createLeadAndGetQuotes($isAllowed	 = true);
				if (empty($quotes))
				{
					throw new Exception("No cabs available for this route", ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
			}
		}
		catch (Exception $ex)
		{
			ReturnSet::renderJSONException($ex);
		}
		$view = ($model->bkg_booking_type == 7) ? 'shuttleQuote' : 'bkQuoteNew';
		$this->renderAuto($view, [
			'model'		 => $model,
			'step'		 => $step,
			'quotes'	 => $quotes,
			'shuttles'	 => $shuttles,
			'stepOver'	 => $model->stepOver]);
	}

	public function actionDetails()
	{
		try
		{
			$request = Yii::app()->request;
			$step	 = $request->getParam('step', 3);
			$catId	 = Yii::app()->request->getParam('catid');
			$model	 = $this->getModel();
			$result	 = CActiveForm::validate($model, null, false);
			if ($result != '[]')
			{
				throw new Exception($result, ReturnSet::ERROR_VALIDATION);
			}
			$model->getRoutes();
			$quotes = $model->getQuote();
		}
		catch (Exception $ex)
		{
			ReturnSet::renderJSONException($ex);
		}
		$view = 'bkDetails';
		$this->renderAuto($view, [
			'model'		 => $model,
			'quotes'	 => $quotes,
			'step'		 => $step,
			'category'	 => $catId
		]);
	}

	public function actionDetailsVO()
	{
		try
		{
			$request = Yii::app()->request;
			$step	 = $request->getParam('step', 3);
			$catId	 = Yii::app()->request->getParam('catid');
			$model	 = $this->getModel();
			$result	 = CActiveForm::validate($model, null, false);
			if ($result != '[]')
			{
				throw new Exception($result, ReturnSet::ERROR_VALIDATION);
			}
			$model->getRoutes();
			$quotes = $model->getQuote();
		}
		catch (Exception $ex)
		{
			ReturnSet::renderJSONException($ex);
		}
		$view = 'bkDetails';
		$this->renderAuto($view, [
			'model'		 => $model,
			'quotes'	 => $quotes,
			'step'		 => $step,
			'category'	 => $catId
		]);
	}

	public function actionPackageQuote()
	{
		$bkgId	 = Yii::app()->request->getParam('bkgid');
		$cab	 = Yii::app()->request->getParam('cab', 0);
		if ($cab != '')
		{
			$vhcCategoryId = SvcClassVhcCat::getCatIdBySvcid($cab);
		}
		if ($bkgId > 0)
		{
			$model = BookingTemp::model()->findByPk($bkgId);
		}
		if ($model != '')
		{
			$quotePackages = $model->getRelatedPackageQuotes($cab);
		}
		$this->renderPartial('package_quote', array('quotePackages' => $quotePackages, 'vhcCategoryId' => $vhcCategoryId), false, true);
	}

	public function actionFlexisearch()
	{
		$request = Yii::app()->request;
		$model	 = $this->getModel(true);
		if (!$model)
		{
			$model = new BookingTemp();
			$model->loadDefaults();
		}
		if (!$request->isPostRequest)
		{
			$model->locale_from_date = DateTimeFormat::DateTimeToDatePicker($model->bkg_pickup_date);
			$model->locale_from_time = DateTimeFormat::DateTimeToTimePicker($model->bkg_pickup_date);
			$toDate					 = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($model->bkg_pickup_date)));
			$model->locale_to_date	 = DateTimeFormat::DateTimeToDatePicker($toDate);
			$model->locale_to_time	 = DateTimeFormat::DateTimeToTimePicker($toDate);
			goto skipPost;
		}
		if ($reqData['bkg_booking_type'] == 1 && $reqData['bkg_flexxi_type'] == 2)
		{
			if ($reqData['bkg_flexxi_quick_booking'] == 1 && $reqData['time1'] != '' && $reqData['time2'] != '')
			{
				$model->bkg_flexxi_quick_booking = $reqData['bkg_flexxi_quick_booking'];
				$model->time1					 = $reqData['time1'];
				$model->time2					 = $reqData['time2'];
				$model->bkg_pickup_date_date	 = $reqData['bkg_pickup_date_date'];
			}

			exit;
		}
		skipPost:
		$this->renderAuto('match_flexxi', ['model' => $model]);
	}

	public function actionBookflexislots()
	{
		$reqData				 = Yii::app()->request->getParam('BookingTemp');
		$selectedPickupTime		 = Yii::app()->request->getParam('pickUpTime');
		$selectedTimeSlot		 = Yii::app()->request->getParam('timeslot');
		$selectedPickupDateTime	 = Yii::app()->request->getParam('pickUpDateTime');
		$model					 = $this->getModel(true);
		if ($selectedPickupTime != '' && $selectedTimeSlot != '')
		{
			$pdate						 = date('d/m/Y', strtotime($selectedPickupDateTime));
			$ptime						 = $selectedPickupTime;
			$model->bkg_pickup_date_date = $pdate;
			$model->bkg_pickup_date		 = $selectedPickupDateTime;
			$model->bkg_pickup_time		 = $ptime;
			$model->bkg_flexxi_time_slot = $selectedTimeSlot;
		}
		$transaction = null;
		try
		{
			$result = CActiveForm:: validate($model);
			if ($result == '[]')
			{
				$transaction = DBUtil::beginTransaction();
				if (!$model->save())
				{
					throw new Exception("Failed to create booking", 101);
				}
				DBUtil::commitTransaction($transaction);
				$GLOBALS["bkg_id"]	 = $model->bkg_id;
				$model->hash		 = Yii::app()->shortHash->hash($model->bkg_id);
				$GLOBALS["hash"]	 = Yii::app()->shortHash->hash($model->bkg_id);
				$return				 = ['success' => true];
			}
			else
			{
				$return = ['success' => false, 'errors' => CJSON::decode($result)];
			}
		}
		catch (Exception $ex)
		{
			$model->addError('bkg_id', $ex->getMessage());
			DBUtil::rollbackTransaction($transaction);
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			echo CJSON::encode($return);
			Yii::app()->end();
		}
	}

	public function actionInfo()
	{
		$flashRequest = Yii::app()->request->getParam('flashBooking');
		if ($flashRequest == 1)
		{
			$cavhash = Yii::app()->request->getParam('cavhash');
			$cavId	 = Yii::app()->shortHash->unHash($cavhash);
			$model	 = BookingTemp::model()->populateFromCabAvailabilities($cavId);
			goto renderView;
		}
		$request = Yii::app()->request;
		$model	 = $this->getModel(true);
		if (!$request->isPostRequest)
		{
			throw new CHttpException(400, "Invalid Request");
		}

		$islogin			 = $request->getParam('islogin', 0);
		// Steps
		$step				 = $request->getParam('step', 3);
		$model->attributes	 = $request->getParam('BookingTemp');
		if ($model->bkg_package_id > 0)
		{
			$model->bkg_booking_type = 5;
			$pckid					 = $model->bkg_package_id;
			$pickupDt				 = $model->bkg_pickup_date;
			$model->bookingRoutes	 = BookingRoute::model()->populateRouteByPackageId($pckid, $pickupDt);
			$model->setRoutes($model->bookingRoutes);
		}
		if ($model->bkg_vht_id > 0)
		{
			$model->bkg_vehicle_type_id = SvcClassVhcCat::getIdByModelId($model->bkg_vht_id);
		}
		$model->save();
		$model->refresh();
		// Start Note section
		$bookingRouteModel = CJSON::decode($model->bkg_route_data);
		foreach ($bookingRouteModel as $key => $bookingRoute)
		{
			$pickupCity[]	 = $bookingRoute['brt_from_city_id'];
			$dropCity[]		 = $bookingRoute['brt_to_city_id'];
			$pickup_date[]	 = $bookingRoute['brt_pickup_datetime'];
			$temp_last_date	 = strtotime($bookingRoute['brt_pickup_datetime']) + $bookingRoute['brt_trip_duration'];
			$drop_date_time	 = date('Y-m-d H:i:s', $temp_last_date);
		}
		$pickup_date_time	 = $pickup_date[0];
		$locationArr		 = array_unique(array_merge($pickupCity, $dropCity));
		$dateArr			 = array($pickup_date_time, $drop_date_time);
		$noteArr			 = DestinationNote::model()->showBookingNotes($locationArr, $dateArr, $showNoteTo			 = 1);
		// endof Show Note section

		renderView:
		$this->renderAuto("bkInfo", ['model' => $model, 'step' => $step, 'islogin' => $islogin, 'note' => $noteArr]);
	}
    
	public function actionInfoNew()
	{   
		$request		 = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_FLASHBOOKING);
		
		if (Yii::app()->user->isGuest)
		{
			throw new CHttpException(403, "Login required.", ReturnSet::ERROR_UNAUTHORISED);
		}

		$flashRequest = Yii::app()->request->getParam('flashBooking');
		if($request->isPostRequest && $flashRequest == 1)
		{
			$cavhash = Yii::app()->request->getParam('cavhash');
			$pickupDate = Yii::app()->request->getParam('pickupDate');
			$cavId	 = Yii::app()->shortHash->unHash($cavhash);
			$model					 = BookingTemp::model()->populateFromCabAvailabilities($cavId,$pickupDate);
			$model->bkg_route_data	 = CJSON::encode($model->bookingRoutes);
			$model->bkg_user_device	 = UserLog::model()->getDevice();
            
            if(!Yii::app()->user->isGuest && UserInfo::isLoggedIn())
            {
                $userModel = UserInfo::getUser()->loadUser();
                $model->loadDefaultUser($userModel->user_id);
            }
            
			$bkgModel = Booking::getNewInstance();

			$bkgModel->populateFromLead($model, true);
            if($model->bkg_user_id != '' || $model->bkg_user_id > 0)
            {
                $contactId = ContactProfile::getByUserId($model->bkg_user_id);
                $bkgModel->bkgUserInfo->bkg_contact_id = $contactId;
            }
            $bkgModel->bkgPref->bkg_cancel_rule_id = 9;
			$result		 = $bkgModel->createQuote();

			CabAvailabilities::deactivateById($bkgModel->bkg_cav_id);
			$hash	 = Yii::app()->shortHash->hash($bkgModel->bkg_id);
			//$url	 = $_SERVER['HTTP_HOST'] . '/bkpn/' . $bkgModel->bkg_id . '/' . $hash;
			//$data	 = ['url' => $url];
            $url = Yii::app()->createUrl('bkpn/' . $bkgModel->bkg_id . '/' . $hash);
            $this->redirect($url);
			//echo CJSON::encode($data);
			Yii::app()->end();
		}
        //$this->renderAuto("bkInfo", ['model' => $model, 'step' => $step, 'islogin' => $islogin, 'note' => $noteArr]);
	}

	public function actionSummary()
	{
		$this->enableClarity();

		if ($this->layoutSufix != "")
		{
			$this->layout = 'column_booking';
			$this->render('summary');
			exit;
		}
		$id			 = Yii::app()->request->getParam('id');
		$hash		 = Yii::app()->request->getParam('hash');
		$actiondone	 = Yii::app()->request->getParam('action');
		$ctype		 = Yii::app()->request->getParam('ctype');

		if ($actiondone == 'done')
		{
			$showAdditional = true;
		}

		//$transCode = Yii::app()->request->getParam('tinfo');
		$transCode = Yii::app()->request->getParam('tinfo');

		if ($id > 0)
		{
			$model = Booking::model()->findByPk($id);
		}
		if ($id != Yii::app()->shortHash->unHash($hash))
		{
			throw new CHttpException(400, 'Invalid data');
		}
		if (!$model)
		{
			throw new CHttpException(400, 'Invalid data');
		}

		if ($model->bkgUserInfo->bkg_user_id != null && $model->bkg_agent_id == null)
		{
			$GLOBALS["GA4_USER_ID"] = $model->bkgUserInfo->bkg_user_id;
		}

		$this->pageTitle = "Thank you for choosing Gozocabs!";

		$model->hash = $hash;
		$model->bkgAddInfo->setScenario("additionalInfo");
		$transId	 = '';
		$succ		 = '';
		$errorMsg	 = '';
		if ($transCode != '')
		{
			// #get additional Info: start
			$bkgAddModel = BookingAddInfo::model()->find('bad_bkg_id=:bkg_id', ['bkg_id' => $model->bkg_id]);
			if (!$bkgAddModel)
			{
				$bkgAddModel = new BookingAddInfo();
			}

			// a#get additional Info: end
			$payment	 = true;
			$transResult = $this->getTransdetailByTranscode($transCode);
			if ($transResult)
			{
				$transId	 = $transResult['transId'];
				$succ		 = $transResult['succ'];
				$tranStatus	 = $transResult['tranStatus'];
			}
		}
		$this->forward('paynow');
		Yii::app()->end();
		//$this->redirect(array('/bkpn/' . $id . '/' . $hash));

		if ($model->bkg_agent_id != '')
		{
			if (($model->bkg_status == 1) && ($model->bkg_agent_id != '') && ($model->bkgInvoice->bkg_advance_amount > 0))
			{
				$logType = UserInfo::TYPE_SYSTEM;
				//Booking::model()->confirmBooking($logType, true, $model->bkg_id);
				Booking::model()->confirm(true, true, $model->bkg_id);
			}
			$model->refresh();

			/* @var $app CWebApplication */
			$app			 = Yii::app();
			$returnUrlAgt	 = $app->getSession()->get("aps_" . $model->bkg_id);
			if ($returnUrlAgt != '')
			{
				$this->redirect($returnUrlAgt, true);
			}
		}

		if ($model->bkgPref->bkg_is_confirm_cash == 1 && $model->bkgInvoice->bkg_advance_amount > 0)
		{
			BookingPref::model()->resetConfirmCash($model->bkg_id, UserInfo::getInstance());
		}

		if ($model->bkgInvoice->bkg_promo1_code == 'FLATRE1')
		{
			$flsPickupDate	 = $model->bkg_pickup_date;
			$flsFromCityId	 = $model->bkg_from_city_id;
			$flsToCityId	 = $model->bkg_to_city_id;
			$isPayment		 = 1;
			$flashSaleStatus = FlashSale::model()->findByRouteCities($flsPickupDate, $flsFromCityId, $flsToCityId, $isPayment);
			if ($flashSaleStatus > 0)
			{
				$errorMsg = json_encode("Sorry your booking could not created as all 4 seats are already sold out . Your payment will be refunded.");
				Booking::model()->canBooking($model->bkg_id, $errorMsg, 29, UserInfo::getInstance());
			}
		}
		if (isset($_POST['Booking']) || isset($_POST['BookingAddInfo']) || isset($_POST['BookingTrail']))
		{
			$reqData		 = Yii::app()->request->getParam('Booking');
			$reqDataAdd		 = Yii::app()->request->getParam('BookingAddInfo');
			$reqDataTrail	 = Yii::app()->request->getParam('BookingTrail');
			$reqRtData		 = Yii::app()->request->getParam('BookingRoute');

			$bkgid	 = $reqData['bkg_id'];
			$hash	 = $reqData['hash'];

			if ($bkgid != Yii::app()->shortHash->unHash($hash))
			{
				throw new CHttpException(400, 'Invalid data');
			}
			/* @var $model Booking */
			$model							 = Booking::model()->findByPk($bkgid);
			$model->bkgAddInfo->setScenario("additionalInfo");
			$model->attributes				 = $reqData;
			$model->bkgAddInfo->attributes	 = $reqDataAdd;
			$model->bkgTrail->attributes	 = $reqDataTrail;
			$showAdditional					 = false;
			$brtArr							 = [];
			foreach ($reqRtData as $brtVal)
			{
				$brtArr[] = $brtVal;
			}
			$cntBrt	 = sizeof($reqRtData);
			$result	 = CActiveForm::validate($model, null, false);
			$result1 = '[]';
			if (isset($_POST['BookingAddInfo']))
			{
				$result1 = CActiveForm::validate($model->bkgAddInfo, null, false);
			}
			if ($result == '[]' && $result1 == '[]')
			{
				$splRemark = 'Carrier Requested for Rs.150';
				if ($model->bkgAddInfo->bkg_spl_req_carrier == 1 && !strstr($model->bkgInvoice->bkg_additional_charge_remark, $splRemark))
				{
					$model->bkgInvoice->bkg_additional_charge = $model->bkgInvoice->bkg_additional_charge + 150;

					$model->bkgInvoice->bkg_additional_charge_remark = ($model->bkgInvoice->bkg_additional_charge_remark == '') ? $splRemark : $model->bkgInvoice->bkg_additional_charge_remark . ', ' . $splRemark;
				}
				elseif ($model->bkgAddInfo->bkg_spl_req_carrier == 0 && strstr($model->bkgInvoice->bkg_additional_charge_remark, $splRemark))
				{
					$model->bkgInvoice->bkg_additional_charge		 = $model->bkgInvoice->bkg_additional_charge - 150;
					$model->bkgInvoice->bkg_additional_charge_remark = trim(str_replace($splRemark, '', $model->bkgInvoice->bkg_additional_charge_remark));
					$model->bkgInvoice->bkg_additional_charge_remark = rtrim($model->bkgInvoice->bkg_additional_charge_remark, ',');
				}

				$lunchbreakrmk = ' Minutes for Journey Break';
				if ($model->bkgAddInfo->bkg_spl_req_lunch_break_time == 30)
				{
					$model->bkgInvoice->bkg_additional_charge		 = $model->bkgInvoice->bkg_additional_charge + 150;
					$brkrmk											 = 30 . $lunchbreakrmk;
					$model->bkgInvoice->bkg_additional_charge_remark = ($model->bkgInvoice->bkg_additional_charge_remark == '') ? $brkrmk : $model->bkgInvoice->bkg_additional_charge_remark . ', ' . $brkrmk;
				}

				if ($model->bkgAddInfo->bkg_spl_req_lunch_break_time == 60)
				{
					$model->bkgInvoice->bkg_additional_charge		 = $model->bkgInvoice->bkg_additional_charge + 300;
					$brkrmk											 = 60 . $lunchbreakrmk;
					$model->bkgInvoice->bkg_additional_charge_remark = ($model->bkgInvoice->bkg_additional_charge_remark == '') ? $brkrmk : $model->bkgInvoice->bkg_additional_charge_remark . ', ' . $brkrmk;
				}

				if ($model->bkgAddInfo->bkg_spl_req_lunch_break_time == 90)
				{
					$model->bkgInvoice->bkg_additional_charge		 = $model->bkgInvoice->bkg_additional_charge + 450;
					$brkrmk											 = 90 . $lunchbreakrmk;
					$model->bkgInvoice->bkg_additional_charge_remark = ($model->bkgInvoice->bkg_additional_charge_remark == '') ? $brkrmk : $model->bkgInvoice->bkg_additional_charge_remark . ', ' . $brkrmk;
				}

				if ($model->bkgAddInfo->bkg_spl_req_lunch_break_time == 120)
				{
					$model->bkgInvoice->bkg_additional_charge		 = $model->bkgInvoice->bkg_additional_charge + 600;
					$brkrmk											 = 120 . $lunchbreakrmk;
					$model->bkgInvoice->bkg_additional_charge_remark = ($model->bkgInvoice->bkg_additional_charge_remark == '') ? $brkrmk : $model->bkgInvoice->bkg_additional_charge_remark . ', ' . $brkrmk;
				}

				if ($model->bkgAddInfo->bkg_spl_req_lunch_break_time == 150)
				{
					$model->bkgInvoice->bkg_additional_charge		 = $model->bkgInvoice->bkg_additional_charge + 750;
					$brkrmk											 = 150 . $lunchbreakrmk;
					$model->bkgInvoice->bkg_additional_charge_remark = ($model->bkgInvoice->bkg_additional_charge_remark == '') ? $brkrmk : $model->bkgInvoice->bkg_additional_charge_remark . ', ' . $brkrmk;
				}

				if ($model->bkgAddInfo->bkg_spl_req_lunch_break_time == 180)
				{
					$model->bkgInvoice->bkg_additional_charge		 = $model->bkgInvoice->bkg_additional_charge + 900;
					$brkrmk											 = 180 . $lunchbreakrmk;
					$model->bkgInvoice->bkg_additional_charge_remark = ($model->bkgInvoice->bkg_additional_charge_remark == '') ? $brkrmk : $model->bkgInvoice->bkg_additional_charge_remark . ', ' . $brkrmk;
				}

				//$model->calculateConvenienceFee();
				//$model->calculateTotal();
				// $model->populateAmount();
				// $model->calculateVendorAmount();

				$model->bkgInvoice->populateAmount(true, false, true, true, $model->bkg_agent_id);

				$msgArr = array(trim(($model->bkgAddInfo->bkg_spl_req_carrier == '0' || $model->bkgAddInfo->bkg_spl_req_carrier == 0) ? "" : "Carrier Requested for Rs.150"), (($model->bkgAddInfo->bkg_spl_req_lunch_break_time != '0' || $model->bkgAddInfo->bkg_spl_req_lunch_break_time != 0) ? $model->bkgAddInfo->bkg_spl_req_lunch_break_time . " Minutes for Journey Break" : ""));
				foreach ($msgArr as $msg)
				{
					if ($msg != '')
					{
						$userInfo	 = UserInfo::getInstance();
						$eventId	 = BookingLog::REMARKS_ADDED;
						$remark		 = "Additional Instruction to Vendor/Driver: " . $msg;
						if ($model->bkg_instruction_to_driver_vendor == '')
						{
							$model->bkg_instruction_to_driver_vendor = $msg;
						}
						else
						{
							$model->bkg_instruction_to_driver_vendor .= "," . $msg;
						}
						BookingLog::model()->createLog($model->bkg_id, $remark, $userInfo, $eventId);
					}
				}

				// $model->sendConfirmation($userType);

				$model->bkgUserInfo->bkg_user_last_updated_on	 = new CDbExpression('NOW()');
				$transaction									 = Yii::app()->db->beginTransaction();
				try
				{
					$preBrtid	 = 0;
					$s			 = 0;
					foreach ($reqRtData as $brtid => $brtVal)
					{
						$brtModel = BookingRoute::model()->findByPk($brtid);
						$brtModel->setScenario('rtupdate');
						if ($s > 0)
						{
							$brtModel->brt_from_pincode = $reqRtData[$preBrtid]['brt_to_pincode'];
						}
						if ($s == 0)
						{
							//$model->bkg_pickup_pincode  = $brtVal['brt_from_pincode'];
							$brtModel->brt_from_pincode = $brtVal['brt_from_pincode'];
						}
						$brtModel->brt_to_pincode = $brtVal['brt_to_pincode'];

						$brtModel->brt_id;
						$brtModel->brt_bkg_id;
						$brtModel->save();
						$preBrtid = $brtid;
						$s++;
					}

					//$model->bkg_drop_pincode = $reqRtData[$brtid]['brt_to_pincode'];
					$model->save();
					$model->bkgInvoice->save();
					$model->bkgUserInfo->save();
					$model->bkgAddInfo->save();
					$model->bkgTrail->save();
					//$suctrl = $model->bkgTrail->getErrors();
					//$sucadd = $model->bkgAddInfo->getErrors();

					if ($msg != '')
					{
						$bkgid		 = $model->bkg_id;
						$desc		 = "Additional Details added to Booking by user.";
						$userInfo	 = UserInfo::getInstance();
						$eventid	 = BookingLog::BOOKING_MODIFIED;
						BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid);
					}

					$transaction->commit();
					$GLOBALS["bkg_id"]	 = $model->bkg_id;
					$model->hash		 = Yii::app()->shortHash->hash($model->bkg_id);
					$GLOBALS["hash"]	 = Yii::app()->shortHash->hash($model->bkg_id);

					$emailCom = new emailWrapper();
					$emailCom->gotBookingemail($model->bkg_id, UserInfo::TYPE_SYSTEM, $model->bkg_agent_id);
					if ($model->bkg_agent_id > 0)
					{
						$emailCom->gotBookingAgentUser($model->bkg_id);
					}
					$msgCom = new smsWrapper();
					$msgCom->gotBooking($model, UserInfo::TYPE_SYSTEM);
				}
				catch (Exception $e)
				{
					$model->addError('bkg_id', $e->getMessage());
					$transaction->rollback();
				}

				$success = !$model->hasErrors();
			}

			if ($success)
			{
				$data = ['success' => $success, 'id' => $bkgid, 'hash' => Yii::app()->shortHash->hash($bkgid)];
			}
			else
			{
				$arrErrors	 = ($result == '[]') ? $result1 : $result;
				$data		 = ['success' => false, 'id' => $bkgid, 'hash' => Yii::app()->shortHash->hash($bkgid), 'error' => json_decode($arrErrors, true)];
			}

			if (Yii::app()->request->isAjaxRequest)
			{
				$obj->data = json_decode($data);
				echo CJSON::encode($data);
				Yii::app()->end();
			}
		}
		$platform = 1;

		if (isset($_COOKIE['mobplatform']))
		{
			$platform = 3;
			setcookie('mobplatform', 'mobile', time() - 60, "/");
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . (($outputJs) ? "Partial" : "");
		if ($platform == 3)
		{
			$this->layout	 = "head1";
			$outputJs		 = true;
		}


		$this->$method('summary', ['model'			 => $model,
			'succ'			 => $succ,
			'errorMsg'		 => $errorMsg,
			'transid'		 => $transId,
			'platform'		 => $platform,
			'payment'		 => $payment,
			'showAdditional' => $showAdditional,
			'bkgAddInfo'	 => $bkgAddModel->attributes,
			'ctype'			 => $ctype], false, $outputJs);

		//  $this->render('summary', ['model' => $model, 'succ' => $succ, 'transid' => $transId, 'payment' => $payment, 'showAdditional' => $showAdditional, 'ctype' => $ctype]);
	}

	public function actionSummary1()
	{

		$this->enableClarity();

		$transaction = DBUtil::beginTransaction();
		try
		{
			$request	 = Yii::app()->request;
			$btRequest	 = $request->getParam('BookingTemp');
			$cavBook	 = false;
			if (isset($btRequest['cavhash']))
			{
				$cavhash = $btRequest['cavhash'];
				$cavId	 = Yii::app()->shortHash->unHash($cavhash);
				if ($cavId != $btRequest['bkg_cav_id'])
				{
					throw new CHttpException(400, "Invalid Request");
				}
				$model					 = BookingTemp::model()->populateFromCabAvailabilities($cavId);
				$model->bkg_route_data	 = CJSON::encode($model->bookingRoutes);
				$model->bkg_user_device	 = UserLog::model()->getDevice();
				$cavBook				 = true;
			}
			else
			{
				$model = $this->getModel(true);
			}
			/** var $model BookingTemp * */
			if (!$request->isPostRequest)
			{
				throw new CHttpException(400, "Invalid Request");
			}
			// Steps
			$step = $request->getParam('step', 4);

			// Update BookingTemp
			$model->attributes		 = $btRequest;
			$model->bkg_contact_no	 = str_replace(' ', '', $model->bkg_contact_no);
			//	$routes					 = []; //$request->getParam('BookingRoute');
			//	$model->updateRoutes($routes);
			if (!$cavBook)
			{
				$model->save();
			}

			// Create Quote
			/** var $bkgModel Booking * */
			$bkgModel = Booking::getNewInstance();

			$bkgModel->populateFromLead($model, $isAllowed	 = true);
			$result		 = $bkgModel->createQuote();
			$promoId	 = (empty($bkgModel->quote->routeRates->promoRow['prm_id'])) ? 0 : $bkgModel->quote->routeRates->promoRow['prm_id'];
			// Confirm Lead
			if (!$result)
			{
				throw new CHttpException(1, CJSON::encode($bkgModel->getErrors()));
			}

			if (!$cavBook)
			{
				$result = $model->confirmLead($bkgModel);
				if (!$result)
				{
					throw new CHttpException(1, CJSON::encode($bkgModel->getErrors()));
				}
				$refcode			 = "";
				$whatappShareLink	 = "";
				if ($bkgModel->bkgUserInfo->bkg_user_id > 0)
				{
					$users				 = Users::model()->findByPk($bkgModel->bkgUserInfo->bkg_user_id);
					$refcode			 = $users->usr_refer_code;
					$whatappShareLink	 = Users::model()->whatsappShareTemplate($refcode);
				}
			}

			if ($bkgModel->bkgUserInfo->bkg_user_id != null && $bkgModel->bkg_agent_id == null)
			{
				$GLOBALS["GA4_USER_ID"] = $bkgModel->bkgUserInfo->bkg_user_id;
			}

			$addOns			 = AddonServiceClassRule::getApplicableAddons($bkgModel->bkg_from_city_id, $bkgModel->bkgSvcClassVhcCat->scc_ServiceClass->scc_id, $bkgModel->bkgInvoice->bkg_base_amount);
			$routeRates		 = BookingInvoice::getRatesWithAddons($addOns, $bkgModel->bkgInvoice);
			$creditVal		 = UserCredits::getApplicableCredits(UserInfo::getUserId(), $bkgModel->bkgInvoice->bkg_base_amount, true, $bkgModel->bkg_from_city_id, $bkgModel->bkg_to_city_id);
			$walletBalance	 = UserWallet::getBalance(UserInfo::getUserId());
			DBUtil::commitTransaction($transaction);

			//$emailCom		 = new emailWrapper();
			//$emailCom->gotBookingemail($bkgModel->bkg_id, UserInfo::TYPE_SYSTEM, $bkgModel->bkg_agent_id);
			if ($cavBook)
			{
				CabAvailabilities::deactivateById($bkgModel->bkg_cav_id);
				$hash	 = Yii::app()->shortHash->hash($bkgModel->bkg_id);
				$url	 = $_SERVER['HTTP_HOST'] . '/bkpn/' . $bkgModel->bkg_id . '/' . $hash;
				$data	 = ['url' => $url];
				echo CJSON::encode($data);
				Yii::app()->end();
			}
			foreach ($model->bookingRoutes as $key => $bookingRoute)
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
			$noteArr			 = DestinationNote::model()->showBookingNotes($locationArr, $dateArr, $showNoteTo			 = 1);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$data = ReturnSet::renderJSONException($ex);
			echo CJSON::encode($data);
			Yii::app()->end();
		}
		if ($bkgModel->bkgPref->bkg_is_gozonow == 1)
		{
			$bkgId	 = $bkgModel->bkg_id;
			$hash	 = Yii::app()->shortHash->hash($bkgModel->bkg_id);
			$url	 = Yii::app()->createAbsoluteUrl('gznow/' . $bkgId . '/' . $hash);

			$this->redirect($url);
			Yii::app()->end();
		}
		$this->renderAuto("bkSummary", ['model' => $bkgModel, 'note' => $noteArr, 'step' => $step, 'applicableAddons' => $addOns, 'routeRatesArr' => $routeRates, 'creditVal' => $creditVal, 'walletBalance' => $walletBalance, 'refcode' => $refcode, 'whatappShareLink' => $whatappShareLink, 'promoId' => $promoId]);
	}

	public function actionPayment()
	{
		$id					 = Yii::app()->request->getParam('id');
		$hash				 = Yii::app()->request->getParam('hash');
		$phash				 = Yii::app()->request->getParam('pHash');
		$ehash				 = Yii::app()->request->getParam('eHash');
		$additionalParams	 = Yii::app()->request->getParam('additionalParams');

		if ($id == '')
		{
			$bkgVal	 = Yii::app()->request->getParam('Booking');
			$id		 = $bkgVal['bkg_id'];
			$hash	 = $bkgVal['hash'];
		}

		$platform		 = Yii::app()->request->getParam('platform');
		$iscreditapplied = Yii::app()->request->getParam('iscreditapplied', 0);
		$src			 = Yii::app()->request->getParam('src', 2);
		$gozocoinApply	 = 0;
		$cashbackStatus	 = true;
		$agent_id		 = isset(Yii::app()->request->cookies['gozo_agent_id']) ? Yii::app()->request->cookies['gozo_agent_id']->value : '';

		$bivResp	 = Yii::app()->request->getParam('BookingInvoice');
		$payubolt	 = $bivResp['payubolt'];
		if ($payubolt == 1)
		{
			$bkgVal	 = Yii::app()->request->getParam('Booking');
			$id		 = $bkgVal['bkg_id'];
			$hash	 = $bkgVal['hash'];
		}

		$model					 = Booking::model()->findByPk($id);
		$bkgUserModel			 = BookingUser::model()->find('bui_bkg_id=:bkg_id', ['bkg_id' => $model->bkg_id]);
		$bkgUserModel->scenario	 = 'advance_pay';
		$bkgInvModel			 = BookingInvoice::model()->find('biv_bkg_id=:bkg_id', ['bkg_id' => $model->bkg_id]);
		$bkgInvModel->scenario	 = 'advance_pay';

		if (!$model || !$bkgUserModel || !$bkgInvModel)
		{
			throw new CHttpException(400, 'Invalid data');
		}
		if ($model->bkg_status > 7 && $model->bkg_status != 15)
		{
			throw new CHttpException(401, 'Booking not active');
		}

		if (isset($phash) && $phash != '')
		{
			$otpPhone = Yii::app()->shortHash->unHash($phash);
			if (($otpPhone == $model->bkgUserInfo->bkg_verification_code) && ($model->bkgUserInfo->bkg_phone_verified == 0))
			{
				$bkgUserModel->bkg_phone_verified = 1;
				$bkgUserModel->save();
			}
		}
		if (isset($ehash) && $ehash != '')
		{
			$otpEmail = Yii::app()->shortHash->unHash($ehash);
			if (($otpEmail == $model->bkgUserInfo->bkg_verifycode_email) && ($model->bkgUserInfo->bkg_email_verified == 0))
			{
				$bkgUserModel->bkg_email_verified = 1;
				$bkgUserModel->save();
			}
		}
		$minDiff = $model->getPaymentExpiryTimeinMinutes();
		if ($minDiff > 0)
		{

			//	$preBillingDetail = true;
			//if ($model->bkgUserInfo->bkg_bill_postalcode == ''){
			//{
			//$preBillingDetail = false;
			//	}

			if (isset($_POST['Booking']) || isset($_POST['BookingInvoice']) || isset($_POST['BookingUser']))
			{
				$payment						 = new BraintreeCCForm('charge');
				$model->attributes				 = Yii::app()->request->getParam('Booking');
				$bkgInvModel->isAdvPromoPaynow	 = $_POST['BookingInvoice']['isAdvPromoPaynow'];
				$bkgInvModel->attributes		 = Yii::app()->request->getParam('BookingInvoice');

				$paymentType	 = Yii::app()->request->getParam('BookingInvoice')['paymentType'];
				$partialPayment	 = Yii::app()->request->getParam('BookingInvoice')['partialPayment'];

				$bkgUserModel->attributes	 = Yii::app()->request->getParam('BookingUser');
				$bankCode1					 = Yii::app()->request->getParam('BookingUser')['bkg_bill_bankcode1'];
				$bankCode2					 = Yii::app()->request->getParam('BookingUser')['bkg_bill_bankcode2'];
				if ($bankCode1 != '')
				{
					$bkgUserModel->bkg_bill_bankcode = $bankCode1;
				}
				elseif ($bankCode2 != '')
				{
					$bkgUserModel->bkg_bill_bankcode = $bankCode2;
				}

				if ($_POST['AdvPromoRadio'] != '')
				{
					$promoModel1 = Promos::model()->getByCode($_POST['AdvPromoRadio']);
					if ($promoModel1->prm_activate_on == 1)
					{
						$model->bkgInvoice->bkg_promo1_code	 = $_POST['AdvPromoRadio'];
						$model->bkgInvoice->bkg_promo1_id	 = $promoModel1->prm_id;
					}
				}

				if ($paymentType == 14 || $paymentType == 15)
				{
					$model->scenario		 = 'lazypay';
					$bkgUserModel->scenario	 = 'lazypay';
					$bkgInvModel->scenario	 = 'lazypay';
				}
				$ebsOpt									 = $model->ebsOpt;
				$bModel									 = clone $model;
				$bModel->bkgInvoice->isAdvPromoPaynow	 = $model->bkgInvoice->isAdvPromoPaynow;
				if ($bModel->bkgInvoice->bkg_advance_amount > 0)
				{
					$creditsUse = 0;
				}
				else
				{
					$creditsUse = Yii::app()->request->getParam('isPayNowCredits', 0);
				}
				$allowCreditApply = false;
				//				if ($creditsUse > 0 && $bkgInvModel->bkg_credits_used == 0)
				//				{
				//					$allowCreditApply				 = true;
				//					$bkgInvModel->bkg_credits_used	 = ($bkgInvModel->bkg_credits_used > 0) ? ($bkgInvModel->bkg_credits_used + $creditsUse) : $creditsUse;
				//				}

				if ($_POST['isWalletUsed'] != '' && $_POST['isWalletUsed'] == 1 && $_POST['walletUsedAmt'] > 0)
				{
					$bkgInvModel->bkg_is_wallet_selected = 1;
					$bkgInvModel->bkg_wallet_used		 = $_POST['walletUsedAmt'];
				}
				$bkgUserModel->ptype = $paymentType;

				$additionalObj = json_decode($additionalParams);
				$model->bkgInvoice->savePromoCoins($additionalObj->code, $additionalObj->coins);

				$result	 = CActiveForm::validate($bModel);
				$result1 = CActiveForm::validate($bkgUserModel);
				$result2 = CActiveForm::validate($bkgInvModel);
				if ($paymentType == 9)
				{
					$ccData					 = $_POST['BraintreeCCForm'];
					$payment->setScenario('custom');
					$payment->amount		 = $partialPayment;
					$payment->paymentType	 = $paymentType;

					$result1 = CActiveForm::validate($payment);
				}
				$return	 = ['success' => false, 'url' => ''];
				$models	 = [];
				if ($result == '[]' && $result1 == '[]' && $result2 == '[]')
				{
					$transaction = DBUtil::beginTransaction();
					try
					{
						$bookingInvoice = BookingInvoice::model()->getByBookingID($model->bkg_id);
						if ($_POST['isWalletUsed'] != '' && $_POST['isWalletUsed'] == 1 && $_POST['walletUsedAmt'] > 0)
						{
							$bookingInvoice->bkg_is_wallet_selected	 = 1;
							$bookingInvoice->bkg_wallet_used		 = $_POST['walletUsedAmt'];
						}
						if ($partialPayment == 0 && $additionalObj->wallet > 0)
						{
							$paymentType								 = PaymentType::TYPE_WALLET;
							$amount										 = $additionalObj->wallet;
							$payubolt									 = 0;
							$model->bkgInvoice->bkg_is_wallet_selected	 = 1;
							$model->bkgInvoice->bkg_wallet_used			 = $amount;
							$hash										 = Yii::app()->shortHash->hash($model->bkg_id);
							$date										 = date();
							$model->bkgInvoice->save();
							//							$preAdvance									 = ($model->bkgInvoice->bkg_advance_amount == '') ? 0 : $model->bkgInvoice->bkg_advance_amount;
							$retSet										 = $model->useWalletPayment();
							if ($retSet->getStatus())
							{
								$model->confirm(true, true);
							}
							$url			 = Yii::app()->createUrl('booking/summary/id/' . $model->bkg_id . '/hash/' . $hash);
							$return['url']	 = $url;

							$model->bkgUserInfo->bkg_user_last_updated_on = new CDbExpression('NOW()');
							$model->update();
							$bkgUserModel->update();
							$model->bkgInvoice->save();

							$model->refresh();
							if ($model->bkgPref->bkg_is_gozonow == 1 && $model->bkg_status == 2)
							{
								BookingCab::assignPreferredVendorDriverCab($model->bkg_bcb_id);
							}

							DBUtil::commitTransaction($transaction);
							$return['success']		 = true;
							$return['id']			 = $id;
							$return['hash']			 = $hash;
							$return['onlywallet']	 = true;
							//							$this->redirect([$url]);
							echo CJSON::encode($return);

							Yii::app()->end();
						}
						$model->bkgUserInfo->bkg_user_last_updated_on = new CDbExpression('NOW()');
						$model->update();
						$bkgUserModel->update();
						//						if ($allowCreditApply)
						//						{
//
						//							if ($bkgInvModel->bkg_credits_used > 0 && $bookingInvoice->bkg_temp_credits == 0 && Yii::app()->user->getId() != '' && ($model->bkgInvoice->bkg_advance_amount == 0 || $model->bkgInvoice->bkg_advance_amount == ''))
						//							{
						//								$bookingInvoice->bkg_temp_credits = ($bookingInvoice->bkg_credits_used > 0) ? ($bookingInvoice->bkg_credits_used + $bkgInvModel->bkg_credits_used) : $bkgInvModel->bkg_credits_used;
						//							}
						//						}
						$bookingInvoice->save();

						$return['success']					 = true;
						$return['id']						 = $id;
						$return['hash']						 = $hash;
						$model->bkgInvoice->partialPayment	 = $partialPayment;

						if (($paymentType > 0 && $return['success'] && $partialPayment > 0))
						{
							$paymentGateway = PaymentGateway::model()->add($paymentType, $partialPayment, $model->bkg_id, $model->bkg_id, $userInfo);
							if ($paymentGateway)
							{
								$params['blg_ref_id'] = $paymentGateway->apg_id;
								BookingLog::model()->createLog($model->bkg_id, "Online payment initiated ({$paymentGateway->getPaymentType()} - {$paymentGateway->apg_code})", UserInfo::getInstance(), BookingLog::PAYMENT_INITIATED, '', $params);
								if (PaymentType::isOnline($paymentType))
								{
									$url = $paymentGateway->paymentUrl;
								}

								if ($payubolt == 1 && Yii::app()->request->isAjaxRequest)
								{
									$apg_id		 = $paymentGateway->apg_id;
									$payRequest	 = PaymentGateway::model()->getPGRequest($apg_id);
									$pgObject	 = Filter::GetPGObject($payRequest->payment_type);

									$return = $pgObject->initiateRequest($payRequest);
									if ($payRequest->payment_type != PaymentType::TYPE_RAZORPAY)
									{
										$return['success'] = true;
									}
									echo CJSON::encode($return);
									DBUtil::commitTransaction($transaction);
									Yii::app()->end();
								}

								if ($paymentType == PaymentType::TYPE_INTERNATIONAL_CARD)
								{
									$ccData			 = $_POST['BraintreeCCForm'];
									$btreeResponse	 = $this->btreeResponse($payment, $model, $ccData, $paymentGateway);
									$url			 = $btreeResponse['url'];
									$result			 = $btreeResponse['result'];
									$success		 = $btreeResponse['success'];
									if (!$success)
									{
										$model->addError('bkg_id', 'Error in payment. Please try again.');
										$return['success']		 = false;
										$return['errormessage']	 = $btreeResponse['resArray']['message'];
									}
								}
								if ($platform == 3)
								{
									setcookie('mobplatform', 'mobile', time() + (60 * 30), "/");
//                                    isset($_COOKIE['mobplatform'])
								}
								else
								{
									if (isset($_COOKIE['mobplatform']))
									{
										setcookie('mobplatform', 'mobile', time() - 60, "/");
									}
								}
								$return['url'] = $url;
							}
						}
						$models[]	 = $model;
						$errors		 = [];
						foreach ($payment->getErrors() as $attribute => $error)
						{
							if ($attribute == 'creditCard_number' && sizeof($payment->getErrors()) == 1 && !$error[0])
							{
								$error[0] = $return['errormessage'];
							}
							$errors[CHtml::activeId($payment, $attribute)] = $error;
						}
						if (count($errors) > 0)
						{
							$return['success']	 = false;
							$return['error']	 = $errors;
						}
						DBUtil::commitTransaction($transaction);
					}
					catch (Exception $e)
					{
						$model->addError('bkg_id', $e->getMessage());
						$models[]			 = $model;
						DBUtil::rollbackTransaction($transaction);
						$return['success']	 = false;
						$return['error']	 = CActiveForm::validate($models, null, false);
					}
				}
				else
				{
					$return['success'] = false;
					if ($result != '[]')
					{
						$return['error'] = CJSON::decode($result);
					}
					if ($result1 != '[]')
					{
						$return['error'] = CJSON::decode($result1);
					}
					if ($result2 != '[]')
					{
						$return['error'] = CJSON::decode($result2);
					}
				}
				echo CJSON::encode($return);

				Yii::app()->end();
			}
		}
	}

	public function actionConfirmation()
	{
		
	}

	public function getModel($throwException = false)
	{
		$model = false;
		try
		{
			$leadId = trim(Yii::app()->request->getParam("lead"));
			if ($leadId == '')
			{
				$attributes	 = Yii::app()->request->getParam('BookingTemp');
				$leadId		 = trim($attributes['bkg_id']);
			}
			if ($leadId == "")
			{
				throw new CHttpException(400, "Invalid Request");
			}

			if ($leadId > 0)
			{
				$model		 = BookingTemp::model($leadId);
				$model->hash = Yii::app()->shortHash->hash($model->bkg_id);
			}
			if (!$model)
			{
				throw new CHttpException(400, "Request not found");
			}
		}
		catch (Exception $ex)
		{
			if ($throwException)
			{
				throw $ex;
			}
		}
		return $model;
	}

	public function getQuotes($throwException = false)
	{
		$quote = false;
		try
		{
			$quote = new Quote();
			if (!$quote)
			{
				throw new CHttpException(404, "Request not found");
			}
		}
		catch (Exception $ex)
		{
			if ($throwException)
			{
				throw $ex;
			}
		}
		return $quote;
	}

	public function actionAddroute()
	{
		$scity	 = Yii::app()->request->getParam('scity'); //tocity
		$pscity	 = Yii::app()->request->getParam('pscity'); //fromcity
		$pdate	 = Yii::app()->request->getParam('pdate');
		$ptime	 = Yii::app()->request->getParam('ptime');
		$btype	 = Yii::app()->request->getParam('btype');
		$index	 = Yii::app()->request->getParam('index');

		$rutModel = Route::model()->getbyCities($pscity, $scity);
		if (!$rutModel)
		{
			$result1 = Route::model()->populate($pscity, $scity);
			if ($result1['success'])
			{
				$rutModel = $result1['model'];
			}
		}


		$model				 = BookingRoute::model();
		$bmodel				 = Booking::model();
		DateTimeFormat::concatDateTime($pdate, $ptime, $dateTime);
		$dateTime			 = new DateTime($dateTime);
		$dateTime->add(new DateInterval('PT' . $rutModel->rut_estm_time . 'M'));
		$seconds			 = $dateTime->getTimestamp();
		$rounded_seconds	 = ceil($seconds / (15 * 60)) * (15 * 60);
		$dateTime->setTimestamp($rounded_seconds);
		$minTime			 = $dateTime->format('Y-m-d H:i:s');
		$model->brt_min_date = $dateTime->format('Y-m-d');
		$estArrTime			 = $dateTime->format('d/m/Y h:iA');
		$model->estArrTime	 = $estArrTime;
		$model->parsePickupDateTime($minTime);
		$this->renderPartial('addroute', ['model' => $model, 'bmodel' => $bmodel, 'sourceCity' => $scity, 'previousCity' => $pscity, 'btype' => $btype, 'index' => $index, 'estArrTime' => $estArrTime], false, true);
	}

	public function actionAddmoreroute()
	{
		$request = Yii::app()->request;
		if (!$request->isPostRequest)
		{
			throw new CHttpException(400, "Invalid Request");
		}

		$modelAttr	 = $request->getParam('BookingTemp');
		$routes		 = $request->getParam("BookingRoute");

		$prevarvtime = $request->getParam("prevarvtime");
		$arvlcnt	 = $request->getParam("arvlcnt");
		$arvlcnt	 = $arvlcnt + 1;
		$model		 = $this->getModel();

		if (!$model)
		{
			$model = new BookingTemp();
			$model->loadDefaults();
		}

		$model->attributes	 = $modelAttr;
		$model->bktyp		 = $modelAttr['bktyp'];
		$model->setRoutes($routes);
		$count				 = count($model->bookingRoutes);
		$lastRoute			 = $model->bookingRoutes[$count - 1];
		$errors				 = BookingRoute::validateRoutes($model->bookingRoutes, $model->bkg_booking_type);
		if (!empty($errors))
		{
			$returnSet = new ReturnSet();
			$returnSet->setErrors($errors, ReturnSet::ERROR_VALIDATION);
			echo json_encode($returnSet);
			Yii::app()->end();
		}
		$brtModel = BookingRoute::model();

		$brtModel->brt_min_date			 = $lastRoute->arrival_time;
		$brtModel->brt_from_city_id		 = $lastRoute->brt_to_city_id;
		$brtModel->brt_pickup_datetime	 = $lastRoute->arrival_time;
		if ($model['bkg_booking_type'] == 3)
		{
			$brtModel->brt_pickup_datetime = date('Y-m-d H:i:s', ceil(strtotime($lastRoute->arrival_time) / 1800) * 1800);
		}
		$brtModel->decodeAttributes();

		$this->renderPartial('addroute', ['model'			 => $brtModel, 'bmodel'		 => $model,
			'btype'			 => $model->bktyp, 'sourceCity'	 => $brtModel->brt_from_city_id, 'previousCity'	 => $lastRoute->brt_from_city_id,
			'index'			 => $count, 'estArrTime'	 => $lastRoute->arrival_time, 'prevarvtime'	 => $prevarvtime, 'arvlcnt'		 => $arvlcnt], false, true);
	}

	public function actionAddmoreItinerary()
	{
		/** @var HttpRequest $request */
		$request = Yii::app()->request;

		$objPage				 = $this->getRequestData();
		$objBooking				 = $objPage->booking;
		$modelAttr				 = $request->getParam('BookingTemp');
		$route					 = $request->getParam("BookingRoute");
		$model					 = new BookingTemp();
		$model->attributes		 = $modelAttr;
		$brtModel				 = new BookingRoute();
		$brtModel->attributes	 = $route;
		$model->bktyp			 = $modelAttr['bktyp'];
		if ($objBooking->routes[0]->source->code == 0)
		{
			$objBooking->routes = [];
		}
		$routes1 = \Stub\common\Itinerary::getRouteModels($objBooking->routes);
		$routes	 = [];
		foreach ($routes1 as $route)
		{
			if ($route->validate())
			{
				$routes[] = $route;
			}
		}
		$routes[]	 = $brtModel;
		$routeObj	 = Stub\common\Itinerary::setModelsData($model->setRoutes($routes));

		$count		 = count($routes);
		$lastRoute	 = $routes[$count - 1];
		$errors		 = BookingRoute::validateRoutes($routes, $model->bkg_booking_type);
		if (!empty($errors))
		{
			$returnSet = new ReturnSet();
			$returnSet->setErrors($errors, ReturnSet::ERROR_VALIDATION);
			echo json_encode($returnSet);
			Yii::app()->end();
		}

		$objBooking->routes = $routeObj;

		$brtModel						 = BookingRoute::model();
		$brtModel->brt_min_date			 = $lastRoute->arrival_time;
		$brtModel->brt_from_city_id		 = $lastRoute->brt_to_city_id;
		$brtModel->brt_pickup_datetime	 = $lastRoute->arrival_time;

		if ($model['bkg_booking_type'] == 3)
		{
			$brtModel->brt_pickup_datetime = date('Y-m-d H:i:s', ceil(strtotime($lastRoute->arrival_time) / 1800) * 1800);
		}
		$brtModel->decodeAttributes();
		$this->pageRequest->updatePostData();
		$this->renderPartial('additinerary', ['model' => $brtModel, 'bmodel' => $model, 'btype' => $model->bktyp, 'cookieActive' => false], false, true);
	}

	public function actionShowmoreItinerary()
	{
		$objPage = $this->getRequestData();
		$this->renderPartial('showitinerary', [], false, true);
	}

	public function actionRemoveItinerary()
	{
		$routeIndex	 = Yii::app()->request->getParam('routeIndex');
		$objPage	 = $this->getRequestData();
		$objBooking	 = $objPage->booking;

		$model		 = new BookingTemp();
		$routes		 = \Stub\common\Itinerary::getRouteModels($objBooking->routes);
		$countRoutes = count($routes);
		if ($countRoutes > $routeIndex + 1)
		{
			$routeFromCityId							 = $routes[$routeIndex]['brt_from_city_id'];
			$routes[$routeIndex + 1]->brt_from_city_id	 = $routeFromCityId;
		}

		array_splice($routes, $routeIndex, 1);
		$errors = BookingRoute::validateRoutes($routes, 3);
		if (!empty($errors))
		{
			$returnSet = new ReturnSet();
			$returnSet->setErrors($errors, ReturnSet::ERROR_VALIDATION);
			echo json_encode($returnSet);
			Yii::app()->end();
		}
		$routeObj			 = Stub\common\Itinerary::setModelsData($model->setRoutes($routes));
		$objBooking->routes	 = $routeObj;
		$objPage->updatePostData();
		$this->renderPartial('showitinerary', ['bmodel' => $model], false, true);
	}

	public function actionUpdateReconfirm()
	{
		$userInfo	 = UserInfo::getInstance();
		$bkgId		 = Yii::app()->request->getParam('id');
		$type		 = Yii::app()->request->getParam('type');
		switch ($type)
		{
			case 1:
				$success	 = Booking::model()->setReconfirm($bkgId);
				Booking::model()->confirmMessages($bkgId);
				$message	 = ($success == true) ? "You have reconfirmed your booking. Now, please make payment below." : "Request Unsuccessful.";
				break;
			case 3:
				$success	 = false;
				$message	 = "Request Unsuccessful.";
				$reasonText	 = "Customer Cancelled from payment link";
				$reasonId	 = 23;
				$bookingID	 = Booking::model()->canBooking($bkgId, $reasonText, $reasonId, $userInfo);
				if ($bookingID > 0)
				{
					BookingLog::model()->createLog($bkgId, $reasonText, $userInfo, BookingLog::BOOKING_CANCELLED, false);
					$success = true;
					$message = "Booking has been successfully cancelled";
				}
				break;
		}
		$return = ['success' => $success, 'message' => $message];
		if (Yii::app()->request->isAjaxRequest)
		{
			echo CJSON::encode($return);
			Yii::app()->end();
		}
		Yii::app()->end();
	}

	public function actionBilling($bkg_id)
	{
		if ($bkg_id > 0)
		{
			$model	 = Booking::model()->findbyPk($bkg_id);
//            if ($_POST['BookingTemp']['bkg_id']) {
//                $model = BookingTemp::model()->findbyPk($_POST['BookingTemp']['bkg_id']);
//            }
//            if ($_POST['cabid']) {
//                $model->bkg_vehicle_type_id = $_POST['cabid'];
//            }
			$vmodel	 = VehicleCategory::model()->findbyPk($model->bkgSvcClassVhcCat->scv_vct_id);
		}
		$this->renderPartial('sideform', [
			'model'	 => $model, 'vmodel' => $vmodel]);
	}

	public function actionPromoapply()
	{
		$result = $this->promoService();
		echo CJSON::encode($result);
	}

	public function actionCreditapply()
	{
		$result = $this->creditService();
		echo CJSON::encode($result);
	}

	public function actionPromoremove()
	{
		$this->promoRemoveService();
	}

	public function actionGozoCoinsRemove()
	{
		$bkgid	 = Yii::app()->request->getParam('bkg_id');
		$hash	 = Yii::app()->request->getParam('bkghash');
		$web	 = Yii::app()->request->getParam('web', 0);

		$flag = 1;

		$this->gozoCoinsRemoveData($bkgid, $hash, $web, $flag);
	}

	public function gozoCoinsRemoveData($bkgid, $hash, $web, $flag = 0)
	{
		$isWalletUsed	 = Yii::app()->request->getParam('isWalletUsed', 0);
		$amtWalletUsed	 = Yii::app()->request->getParam('amtWalletUsed', 0);
		if ($bkgid != Yii::app()->shortHash->unHash($hash))
		{
			throw new CHttpException(400, 'Invalid data');
		}
		if (isset($bkgid))
		{
			$isAdvDiscount	 = false;
			$model			 = Booking::model()->findbyPk($bkgid);
			if (!$model)
			{
				throw new CHttpException(400, 'Invalid data');
			}
			$model1 = clone $model;
			if ($model1->bkgInvoice->bkg_promo1_id > 0)
			{
				//$promoModel = Promos::model()->getByCode($model1->bkgInvoice->bkg_promo1_code);
				$promoModel = Promos::model()->findByPk($model1->bkgInvoice->bkg_promo1_id);

				if (!$promoModel)
				{
					throw new Exception('Invalid Promo code');
				}
				$promoModel->promoCode	 = $model1->bkgInvoice->bkg_promo1_code;
				$promoModel->totalAmount = $model1->bkgInvoice->bkg_base_amount;
				$promoModel->createDate	 = $model1->bkg_create_date;
				$promoModel->pickupDate	 = $model1->bkg_pickup_date;
				$promoModel->fromCityId	 = $model1->bkg_from_city_id;
				$promoModel->toCityId	 = $model1->bkg_to_city_id;
				$promoModel->userId		 = $model1->bkgUserInfo->bkg_user_id;
				$promoModel->platform	 = $model1->bkgTrail->bkg_platform;
				$promoModel->carType	 = $model1->bkg_vehicle_type_id;
				$promoModel->bookingType = $model1->bkg_booking_type;
				$promoModel->noOfSeat	 = $model1->bkgAddInfo->bkg_no_person;
				$promoModel->bkgId		 = $model1->bkg_id;
				$promoModel->email		 = '';
				$promoModel->phone		 = '';
				$promoModel->imEfect	 = '';

				$discountArr = $promoModel->applyPromoCode();
				if ($discountArr != false)
				{
					if ($discountArr['cash'] > 0 && $discountArr['prm_activate_on'] == 1)
					{
						if (isset($discountArr['nextTripApply']) && $discountArr['nextTripApply'] == 1)
						{
							$discountArr['cash']	 = 0;
							$discountArr['coins']	 = 0;
						}
						if ($discountArr['pcn_type'] == 1 || $discountArr['pcn_type'] == 3)
						{
							$model2->bkgInvoice->bkg_discount_amount = $discountArr['cash'];
							$model2->bkgInvoice->bkg_promo1_amt		 = $discountArr['cash'];
						}
						$isAdvDiscount = true;
					}
					if ($discountArr['cash'] > 0 || $discountArr['coins'] > 0)
					{
						$isPromoApplied	 = true;
						$isPromo		 = false;
					}
					if ($discountArr['pcn_type'] == 1)
					{
						if ($discountArr['prm_activate_on'] == 1)
						{
							$msg = 'Promo ' . $model->bkgInvoice->bkg_promo1_code . ' applied successfully .You will get discount worth ?' . $discountArr["cash"] . ' when you make payment.';
						}
						else
						{
							$msg = 'Promo ' . $model->bkgInvoice->bkg_promo1_code . ' used successfully.';
						}
					}
					if ($discountArr['pcn_type'] == 2)
					{
						$msg = "Promo applied successfully. You got Gozo Coins worth ?" . $discountArr['coins'] . ". You may redeem these Gozo Coins against your future bookings with us.";
						Logger::create("PRM TYPE 2 Platform:- " . $model->bkgTrail->bkg_platform);
						if ($model->bkgTrail->bkg_platform != 3)
						{
							$msg .= "*<a href='#' onclick='showTcGozoCoins2()'>T&C</a> Apply";
						}
					}
					if ($discountArr['pcn_type'] == 3)
					{
						$msg = "Promo applied successfully. You will get discount worth ?" . $discountArr['cash'] . " and Gozo Coins worth ?" . $discountArr['coins'] . ".* You may redeem these Gozo Coins against your future bookings with us.";
						Logger::create("PRM TYPE 2 Platform:- " . $model->bkgTrail->bkg_platform);
						if ($model->bkgTrail->bkg_platform != 3)
						{
							$msg .= "*<a href='#' onclick='showTcGozoCoins2()'>T&C</a> Apply";
						}
					}
					if (isset($discountArr['nextTripApply']) && $discountArr['nextTripApply'] == 1)
					{
						$msg = "Promo applied successfully. You will be benefited on your next trip.";
					}
					$prmType			 = $discountArr['pcn_type'];
					$promoDescription	 = $promoModel->prm_desc;
					$promoCode			 = $promoModel->prm_code;
					$promo_id			 = $promoModel->prm_id;
					$isPromoApplied		 = true;
				}
			}

			if ($isWalletUsed == 1 && UserInfo::getUserId() > 0 && $amtWalletUsed > 0)
			{
				$totWalletBalance						 = UserWallet::getBalance(UserInfo::getUserId());
				$totWalletBalance						 = ($model1->bkgInvoice->bkg_due_amount < $totWalletBalance) ? $model1->bkgInvoice->bkg_due_amount : $totWalletBalance;
				$amtWalletUsed							 = ($amtWalletUsed > $totWalletBalance) ? $totWalletBalance : $amtWalletUsed;
				$model1->bkgInvoice->bkg_advance_amount	 += $amtWalletUsed;
			}
			$model1->bkgInvoice->bkgFlexxiMinPay = 1;
			$usepromo							 = ($model->bkgInvoice->bkg_promo1_id == 0);
			$model1->bkgInvoice->calculateConvenienceFee(0);
			$model1->bkgInvoice->calculateTotal();
			$model1->bkgInvoice->calculateVendorAmount();
			$MaxCredits							 = UserCredits::getApplicableCredits($model->bkgUserInfo->bkg_user_id, $model1->bkgInvoice->bkg_base_amount, $usepromo, $model->bkg_from_city_id, $model->bkg_to_city_id);
			$creditVal							 = $MaxCredits['credits'];
			$refundCredits						 = $MaxCredits['refundCredits']; //added for show proper coin
			$result								 = true;
			$minpay								 = $model1->bkgInvoice->calculateMinPayment();
			$dueWithoutConvFee					 = $model1->bkgInvoice->bkg_due_amount;
			$taxWithoutConvFee					 = $model1->bkgInvoice->bkg_service_tax;
			$driver_allowance					 = $model1->bkgInvoice->bkg_driver_allowance_amount;
			$model1->bkgInvoice->bkgFlexxiMinPay = 0;
			//data without cod
			$model2								 = clone $model;
			$model2->bkgInvoice->calculateConvenienceFee(0);
			$model2->bkgInvoice->calculateTotal();
			//data without cod
			$totAmount							 = $model2->bkgInvoice->bkg_total_amount;

			$convFee			 = $model->bkgInvoice->bkg_total_amount - $model2->bkgInvoice->bkg_total_amount;
			$amountWithConvFee	 = round($model->bkgInvoice->bkg_due_amount);
			$isPromoUsed		 = ($model->bkgInvoice->bkg_promo1_id != 0);
			$isPromo			 = ($model->bkgInvoice->bkg_promo1_id != 0 && $result) ? false : true;
//                  clone $mode=$model1;
//                 $mode->bkg_discount_amount =round($mode->bkg_base_amount*0.05);
//                 $mode->calculateTotal();
			$discAdvDue			 = $model1->bkgInvoice->bkg_due_amount;

			if ($flag == 1)
			{
				$userInfo				 = UserInfo::model();
				$userInfo->userType		 = UserInfo::TYPE_CONSUMER;
				$userInfo->userId		 = $model->bkgUserInfo->bkg_user_id;
				$eventid				 = BookingLog::BOOKING_PROMO;
				$params['blg_ref_id']	 = BookingLog::REF_PROMO_GOZOCOINS_REMOVED;
				BookingLog::model()->createLog($model->bkg_id, "Gozocoin removed successfully.", $userInfo, $eventid, false, $params);

				$status = ['message'			 => $msg,
					'base_amount'		 => $model->bkgInvoice->bkg_base_amount,
					'promo_type'		 => $prmType,
					'isPromoUsed'		 => $isPromoUsed,
					'promo_id'			 => $promo_id,
					'isCreditUsed'		 => false,
					'totCredits'		 => $creditVal,
					'refundCredits'		 => $refundCredits,
					'creditused'		 => 0,
					'result'			 => $result,
					'due_amount'		 => $dueWithoutConvFee,
					'promo_code'		 => $promoCode,
					'discount'			 => $model1->bkgInvoice->bkg_discount_amount,
					'promo_desc'		 => $promoDescription,
					'service_tax'		 => $taxWithoutConvFee,
					'driver_allowance'	 => $driver_allowance,
					'total_amount'		 => $totAmount,
					'convFee'			 => $convFee,
					'amountWithConvFee'	 => $amountWithConvFee,
					'minPayable'		 => $minpay,
					'isCredit'			 => true,
					'isPromo'			 => $isPromo,
					'discAdv'			 => $discAdvDue,
					'isAdvDiscount'		 => $isAdvDiscount,
					'creditRemove'		 => $result,
					'amtWalletUsed'		 => $amtWalletUsed,
					'isPromoApplied'	 => $isPromoApplied,
					'isGozoCoinsApplied' => false,
					'isWalletApplied'	 => ($amtWalletUsed > 0) ? true : false,
					'isRefundCredits'	 => ($refundCredits > 0) ? true : false
				];

				echo CJSON::encode($status);
			}
		}
	}

	public function actionCabratepartial()
	{
		$this->renderPartial('cabratepartial', array());
	}

	public function actionEditnew()
	{
		$this->checkV3Theme();
		$bkgid							 = Yii::app()->request->getParam('bkg_id');
		$model							 = Booking::model()->findbyPk($bkgid);
		$cab							 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
		//$model->scenario				 = 'modifybooking';
		$model->bkgUserInfo->scenario	 = 'modifybooking';

		if (isset($_REQUEST['Booking']) || isset($_REQUEST['BookingUser']))
		{
			$arr		 = Yii::app()->request->getParam('Booking');
			$arrBkgUsr	 = Yii::app()->request->getParam('BookingUser');
			$hash		 = Yii::app()->request->getParam('hash');
			if ($arr['bkg_id'] != Yii::app()->shortHash->unHash($hash))
			{
				throw new CHttpException(400, 'Invalid data');
			}
			// $model = $this->loadModel($arr['bkg_id']);
			$obj							 = new stdClass();
			$obj->success					 = false;
			//$oldModel = clone $model;
			$oldData						 = Booking::model()->getDetailsbyId($model->bkg_id);
			$model->attributes				 = $arr;
			$model->bkgUserInfo->attributes	 = $arrBkgUsr;
			$result1						 = CActiveForm::validate($model->bkgUserInfo, null, false);
			//if ($result == '[]' && $result1	= '[]')
			if ($result1						 = '[]')
			{
				$obj->success	 = true;
				$model->save();
				$model->bkgUserInfo->save();
				$jsonObj		 = new stdClass();
				$response		 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
				if ($response->getStatus())
				{
					$firstName	 = $response->getData()->email['firstName'];
					$lastName	 = $response->getData()->email['lastName'];
				}
				$jsonObj->profile->firstName				 = $firstName;
				$jsonObj->profile->lastName					 = $lastName;
				$jsonObj->profile->email					 = $arrBkgUsr['bkg_user_email'];
				$jsonObj->profile->primaryContact->number	 = $arrBkgUsr['bkg_contact_no'];
				$jsonObj->profile->primaryContact->code		 = $arrBkgUsr['bkg_country_code'];
				if ($model->bkgUserInfo->bkg_contact_id)
				{
					Contact::modifyContact($jsonObj, $model->bkgUserInfo->bkg_contact_id, 0, UserInfo::TYPE_CONSUMER);
				}
				else
				{
					$returnSet = Contact::createContact($jsonObj, 0, UserInfo::TYPE_CONSUMER);
				}
				$success			 = 1;
				$newData			 = Booking::model()->getDetailsbyId($model->bkg_id);
				$getOldDifference	 = array_diff_assoc($oldData, $newData);
				$changesForLog		 = " Old Values: " . $this->getModificationMSG($getOldDifference, 'log');
				$logDesc			 = "Booking modified:";
				$eventid			 = BookingLog::BOOKING_MODIFIED;
				$desc				 = $logDesc . $changesForLog;
				$bkgid				 = $model->bkg_id;
				$userInfo			 = UserInfo::getInstance();
				BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				$obj->data = json_decode($result);
				echo CJSON::encode($obj);
				Yii::app()->end();
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('editnew', array('model' => $model, 'cab' => $cab), false, $outputJs);
	}

	public function actionModifyroute()
	{
		$model		 = new Booking();
		$fcity		 = Yii::app()->request->getParam('fcity');
		$tcity		 = Yii::app()->request->getParam('tcity');
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('modifyroute', array('fcity' => $fcity, 'tcity' => $tcity, 'model' => $model), false, $outputJs);
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

	public function actionRoutes($route = '')
	{
		$this->checkV2Theme();

		Logger::profile("Start");

		$this->ampPageEnabled	 = 1;
		$arrSubject				 = explode("-", $route);
		$arrSearch				 = array('sriseilam');
		$arrReplace				 = array('srisailam');
		$newphrase				 = str_replace($arrSearch, $arrReplace, $arrSubject, $count);
		if ($count > 0)
		{
			Yii::app()->getRequest()->redirect(implode('-', $newphrase), true, 301);
			Yii::app()->end();
		}


		$routeMap = ['delhi_airport-chandigarh'	 => 'delhi-chandigarh',
			'delhi-mahakumbh'			 => 'delhi-haridwar',
			'delhi_airport-manali'		 => 'delhi-manali',
			'delhi_airport-mussoorie'	 => 'delhi-mussoorie',
			'delhi_airport-shimla'		 => 'delhi-shimla',
			'delhi_airport-rishikesh'	 => 'delhi-rishikesh',
			'delhi_airport-dehradun'	 => 'delhi-dehradun',
			'delhi_airport-ludhiana'	 => 'delhi-ludhiana',
			'delhi_airport-jaipur'		 => 'delhi-jaipur'];

		$selected_cities = array('Delhi', 'Mumbai', 'Hyderabad', 'Chennai', 'Bangalore', 'Pune', 'Goa', 'Jaipur');
		Logger::profile("INIT");

		$model		 = new BookingTemp('Route');
		$brtModel	 = new BookingRoute();
		$model->loadDefaults();
		Logger::profile("Default Loaded");
		if (array_key_exists($route, $routeMap))
		{
			$route = $routeMap [$route];
		}

		if ($route != '')
		{
			$rModel				 = Route::model()->getByName($route);
			#print_r($route);
			#exit;
			$GLOBALS['rutName']	 = $route;
		}

		$url_path = $GLOBALS['rutName'];

		if ($rModel)
		{
			goto skipSearch;
		}

		/* @var $cModel  Cities */
		$cModel = Cities::model()->getByCity($route);
		if ($cModel)
		{
			Logger::trace("redirecting $route to {$cModel->cty_alias_path}");
			Logger::warning("Redirecting route page to car rental alias", true);
			$this->redirect(["index/cities", "city" => $cModel->cty_alias_path], true, 301);
		}

		$rModel = Route::getByMatchingKeyword($route);

		if ($rModel)
		{
			Logger::trace("redirecting $route to {$rModel->rut_name}");
			Logger::warning("Redirecting routes page to nearest alias", true);
			$this->redirect(["booking/routes", "route" => $rModel->rut_name], true, 301);
		}

		throw new CHttpException(404, "Route/City not found", ReturnSet::ERROR_NO_RECORDS_FOUND);

		skipSearch:
		Logger::profile("Route Loaded");
		if ($rModel)
		{
			$model->bkg_from_city_id	 = $rModel->rut_from_city_id;
			$model->bkg_to_city_id		 = $rModel->rut_to_city_id;
			$model->bkg_trip_distance	 = $rModel->rut_estm_distance;

			$this->setRouteTags($rModel->rut_id);
			$model->bkg_booking_type = 1;

			if (in_array($rModel->rutFromCity->cty_name, $selected_cities, true))
			{
				$model->is_luxury_from_city = 1;
			}
			else
			{
				$model->is_luxury_from_city = 0;
			}
		}
		Logger::profile("Model Initialised");

		$routeQuot		 = Route::getBasicOnewayQuote($model->bkg_from_city_id, $model->bkg_to_city_id);
		$from_alias_path = Cities::getAliasPath($model->bkg_from_city_id);
		$to_alias_path	 = Cities::getAliasPath($model->bkg_to_city_id);
		$compact_id		 = 1;

		Logger::profile("Quote Loaded");
		//echo '<pre>';print_r($rModel);exit;
		if ($rModel && $rModel->rutFromCity->cty_active == 1 && $rModel->rutToCity->cty_active == 1)
		{

			// Structure Markup Data
//			$arrStructureMarkupData				 = Route::model()->getStructuredMarkupForRoute($rModel, $routeQuot);
			$jsonStructureMarkupData	 = StructureData::getRouteServiceOfferSchema($rModel);
			$jsonStructureProductSchema	 = StructureData::getProductSchemaforRoute($rModel);

			// breadcumbwise structure data for markup
//			$routeBreadcumbStructureMarkupData	 = Route::model()->getBreadcumbMarkupForRoute($rModel->rut_from_city_id, $rModel->rut_to_city_id, 'route_type');
			$routeBreadcumbStructureMarkupData	 = StructureData::breadCrumbSchema($rModel->rut_from_city_id, $rModel->rut_to_city_id, 'route_type');
			//provider structured data for markup
			#$providerStructureMarkupData		 = StructureData::providerDetails();
			#$jsonproviderStructureMarkupData	 = json_encode($providerStructureMarkupData, JSON_UNESCAPED_SLASHES);
			$jsonproviderStructureMarkupData	 = '';

			//rating and count
			$ratingCountArr = Ratings::getRouteSummary($rModel->rut_from_city_id, $rModel->rut_to_city_id);
			foreach ($routeQuot as $key => $baseQuot)
			{
				$scvIdsWithVht = SvcClassVhcCat::getSvcsWithVhcModel();
				if (in_array($key, explode(",", $scvIdsWithVht)))
				{
					continue;
				}
				if ($baseQuot->success)
				{
					$amtArr[$key] = $baseQuot->routeRates->baseAmount;

					$baseAmt = min($amtArr);
				}
			}
			$base_amount = $routeQuot[$compact_id]->routeRates->baseAmount;

			$suv_id				 = 2;
			$suv_base_amount	 = $routeQuot[$suv_id]->routeRates->baseAmount;
			$sedan_id			 = 3;
			$sedan_base_amount	 = $routeQuot[$sedan_id]->routeRates->baseAmount;
			$sedanAmount		 = (int) $routeQuot[$sedan_id]->routeRates->baseAmount;
			$compactAmount		 = (int) $routeQuot[$compact_id]->routeRates->baseAmount;
			#$arr = array($routeQuot[$sedan_id]->routeRates->baseAmount,$routeQuot[$compact_id]->routeRates->baseAmount);
			#echo $minPrice = min($routeQuot[$sedan_id],$routeQuot[$compact_id]);

			$amtArr[$key]	 = $baseQuot->routeRates->baseAmount;
			$minPrice		 = $baseAmt;

			$faqSchema				 = StructureData::faqSchema($rModel, $minPrice, $routeQuot[1]->routeRates->ratePerKM, $model->bkg_trip_distance);
			$jsonStructureFAQSchema	 = json_encode($faqSchema, JSON_UNESCAPED_SLASHES);

			$this->pageTitle		 = $rModel->rutFromCity->cty_name . " to " . $rModel->rutToCity->cty_name . " cabs from ₹" . $baseAmt . " | Book taxi online";
			$this->metaDescription	 = "Book " . $rModel->rutFromCity->cty_name . " to " . $rModel->rutToCity->cty_name . " cabs online starting from ₹" . $baseAmt . " Book " . $rModel->rutFromCity->cty_name . " to " . $rModel->rutToCity->cty_name . " taxi service online with the cheapest fare for oneway and roundtrip with Gozocabs";
			$this->metaKeywords		 = $rModel->rutFromCity->cty_name . " to " . $rModel->rutToCity->cty_name . " oneway," . $rModel->rutFromCity->cty_name . " to " . $rModel->rutToCity->cty_name . " taxi fare, online cab booking " . $rModel->rutFromCity->cty_name . " to " . $rModel->rutToCity->cty_name . ", cabs for " . $rModel->rutFromCity->cty_name . " to " . $rModel->rutToCity->cty_name . ", " . $rModel->rutFromCity->cty_name . " to " . $rModel->rutToCity->cty_name . " car rental, outstation taxi " . $rModel->rutFromCity->cty_name . " to " . $rModel->rutToCity->cty_name . ", outstation cabs in " . $rModel->rutFromCity->cty_name . ", " . $rModel->rutFromCity->cty_name . " to " . $rModel->rutToCity->cty_name . " taxi, " . $rModel->rutFromCity->cty_name . " to " . $rModel->rutToCity->cty_name . " distance, " . $rModel->rutFromCity->cty_name . " to " . $rModel->rutToCity->cty_name . " round trip taxi fare, outstation cab booking " . $rModel->rutFromCity->cty_name . " to " . $rModel->rutToCity->cty_name . "";
			Logger::profile("Structured Data Loaded");

			$this->render('home', array(
				'model'								 => $model,
				'brtModel'							 => $brtModel,
				'type'								 => 'route',
				'rmodel'							 => $rModel,
				'allQuot'							 => $routeQuot,
				'basePriceOW'						 => $routeQuot[1]->routeRates->baseAmount,
				'minPrice'							 => $minPrice,
				'fcitystate'						 => $rModel->rutFromCity->cty_name . ',' . $rModel->rutFromCity->ctyState->stt_name,
				'tcitystate'						 => $rModel->rutToCity->cty_name . ',' . $rModel->rutToCity->ctyState->stt_name,
				'jsonStructureMarkupData'			 => $jsonStructureMarkupData,
				'routeBreadcumbStructureMarkupData'	 => $routeBreadcumbStructureMarkupData,
				'jsonproviderStructureMarkupData'	 => $jsonproviderStructureMarkupData,
				'jsonStructureProductSchema'		 => $jsonStructureProductSchema,
				'jsonStructureFAQSchema'			 => $jsonStructureFAQSchema,
				'ratingCountArr'					 => $ratingCountArr,
				'aliash_path'						 => $from_alias_path . '-' . $to_alias_path,
				'mpath_url'							 => $url_path
			));
		}
		elseif ($cModel)
		{
			$this->pageTitle = "Book cab in one way cab, round trip cab, local cab and outstation packages";
			//$this->metaKeywords = 'your, keywords, here';
			$topRoutes		 = Route::model()->getRoutesByCityId($cModel->cty_id);
			$count			 = Route::model()->countRouteCities();

			$this->render('city_details', array(
				'model'		 => $model,
				'cmodel'	 => $cModel,
				'topRoutes'	 => $topRoutes,
				'count'		 => $count,
				'type'		 => 'city',
			));
		}
		else
		{
			$this->redirect('/index');
		}
	}

	public function actionCities()
	{
		$city	 = Yii::app()->request->getParam('city');
		/* @var $cmodel  Cities */
		$model	 = new BookingTemp('Route');
		$cmodel	 = Cities::model()->getByCity2($city);
		if ($cmodel->cty_id)
		{
			$GLOBALS['rutName']	 = $cmodel->cty_name;
			$topRoutes			 = Route::model()->getRoutesByCityId($cmodel->cty_id);
			$topCitiesKm		 = Cities::model()->getTopCitiesByKm($cmodel->cty_id);
		}
		if (!$cmodel)
		{
			throw new CHttpException(404, "City not found", 404);
		}
		$this->pageTitle	 = "Book cab in " . $cmodel->cty_name . " - one way cab, round trip cab, local cab and outstation packages";
		$topCitiesByRegion	 = Cities::model()->getTopCitiesByAllRegion();
		$count				 = Route::model()->countRouteCities();
		if ($cmodel)
		{
			$this->render(
					'city_details',
	 array('model'				 => $model,
						'cmodel'			 => $cmodel,
						'topRoutes'			 => $topRoutes,
						'topCitiesByRegion'	 => $topCitiesByRegion,
						'topCitiesKm'		 => $topCitiesKm,
						'count'				 => $count,
						'type'				 => 'city'
					)
			);
		}
	}

	public function actionLeavelog()
	{
		/* @var $rutModel Route */
		if (isset($_REQUEST['log_from_city_id2']))
		{
			$model						 = new BookingTemp('lead_create');
			//$model->bkg_route_id = Yii::app()->request->getParam('log_route_id2');
			$model->bkg_from_city_id	 = Yii::app()->request->getParam('log_from_city_id2');
			$model->bkg_to_city_id		 = Yii::app()->request->getParam('log_to_city_id2');
			$rutModel					 = Route::model()->findByPk($model->bkg_route_id);
			$model->bkg_trip_distance	 = $rutModel->rut_estm_distance;
			$model->bkg_trip_duration	 = $rutModel->rut_estm_time;
			$model->bkg_user_ip			 = \Filter::getUserIP();
			$cityinfo					 = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
			$model->bkg_user_city		 = $cityinfo['city'];
			$model->bkg_user_country	 = $cityinfo['country'];
			$model->bkg_user_device		 = UserLog::model()->getDevice();
			$model->bkg_booking_type	 = 1;
			$model->bkg_platform		 = Booking::Platform_User;
			$pdate						 = Yii::app()->request->getParam('log_pickup_date_date2');
			$ptime						 = Yii::app()->request->getParam('log_pickup_date_time2');

			if ($pdate == '' || $ptime == '')
			{
				$model->bkg_pickup_date	 = date('Y-m-d H:i:s', strtotime('+4 hour'));
				$model->bkg_pickup_time	 = date('H:i:s', strtotime('+4 hour'));
			}
			else
			{
				$model->bkg_pickup_date_date = $pdate;
				$model->bkg_pickup_date_time = $ptime;
			}
			$model->bkg_lead_source	 = '2';
			// $model->bkg_log_type = "changed my mind";
			$model->bkg_log_comment	 = Yii::app()->request->getParam('comment2');
			$model->bkg_log_phone	 = Yii::app()->request->getParam('phone2');
			$model->bkg_log_email	 = Yii::app()->request->getParam('email2');

			if ($model->validate())
			{
				$model->save();
			}
		}
		if (isset($_REQUEST['log_from_city_id1']))
		{
			$model						 = new BookingTemp('lead_create');
			$model->bkg_booking_type	 = 1;
			$model->bkg_route_id		 = Yii::app()->request->getParam('log_route_id1');
			$rutModel					 = Route::model()->findByPk($model->bkg_route_id);
			$model->bkg_trip_distance	 = $rutModel->rut_estm_distance;
			$model->bkg_trip_duration	 = $rutModel->rut_estm_time;
			$model->bkg_from_city_id	 = Yii::app()->request->getParam('log_from_city_id1');
			$model->bkg_to_city_id		 = Yii::app()->request->getParam('log_to_city_id1');
			$model->bkg_user_ip			 = \Filter::getUserIP();
			$cityinfo					 = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
			$model->bkg_user_city		 = $cityinfo['city'];
			$model->bkg_user_country	 = $cityinfo['country'];
			$model->bkg_user_device		 = UserLog::model()->getDevice();
			$model->bkg_platform		 = Booking::Platform_User;
			$pdate						 = Yii::app()->request->getParam('log_pickup_date_date1');
			$ptime						 = Yii::app()->request->getParam('log_pickup_date_time1');
			if ($pdate == '' || $ptime == '')
			{
				$model->bkg_pickup_date	 = date('Y-m-d H:i:s', strtotime(
								'+4 hour'
				));
				$model->bkg_pickup_time	 = date('H:i:s', strtotime('+4 hour'));
			}
			else
			{
				$model->bkg_pickup_date_date = $pdate;
				$model->bkg_pickup_date_time = $ptime;
			}
			$model->bkg_lead_source			 = '1';
			//$model->bkg_log_type = 'call me to make booking';
			$model->bkg_log_phone			 = Yii::app()->request->getParam('phone1');
			$model->bkg_user_last_updated_on = new CDbExpression('NOW()');
			if ($model->validate())
			{
				$model->save();
			}
		}

		$session = Yii::app()->session;
		$session->remove("booking_temp_id");
		//   unset($session[])
		$this->redirect('index');
	}

	public function actionPaynow()
	{
		$this->enableClarity();
		$request = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_BKPN);

		$id							 = Yii::app()->request->getParam('id');
		$hash						 = Yii::app()->request->getParam('hash');
		$phash						 = Yii::app()->request->getParam('pHash');
		$ehash						 = Yii::app()->request->getParam('eHash');
		$transCode					 = Yii::app()->request->getParam('tinfo');
		$platform					 = Yii::app()->request->getParam('platform');
		$iscreditapplied			 = Yii::app()->request->getParam('iscreditapplied', 0);
		$src						 = Yii::app()->request->getParam('src', 2);
		$actiondone					 = Yii::app()->request->getParam('action');
		$isRescheduleWalletPayment	 = Yii::app()->request->getParam('isreschedule', 0);
		$gozocoinApply				 = 0;
		$cashbackStatus				 = true;
		$agent_id					 = isset(Yii::app()->request->cookies['gozo_agent_id']) ? Yii::app()->request->cookies['gozo_agent_id']->value : '';
		if ($request->cookies->contains('gozo_qr_id'))
		{
			$qrId = Yii::app()->request->cookies['bkg_qr_id']->value;
		}
		$bivResp	 = Yii::app()->request->getParam('BookingInvoice');
		$payubolt	 = $bivResp['payubolt'];
		if ($payubolt == 1)
		{
			$bkgVal	 = Yii::app()->request->getParam('Booking');
			$id		 = $bkgVal['bkg_id'];
			$hash	 = $bkgVal['hash'];
		}
		$userInfo = UserInfo::getInstance();
		if ($id != Yii::app()->shortHash->unHash($hash))
		{
			throw new CHttpException(400, 'Invalid data');
		}
		$isAgent = false;
		/* @var $model Booking */
		$model	 = Booking::model()->findByPk($id);
		if ($userInfo->userType == UserInfo::TYPE_CONSUMER && $userInfo->userId == 0)
		{
//			$webUser = Yii::app()->user;
//			$webUser->setId($model->bkgUserInfo->bkg_user_id);
		}

		if ($model->bkgUserInfo->bkg_user_id != null && $model->bkg_agent_id == null)
		{
			$GLOBALS["GA4_USER_ID"] = $model->bkgUserInfo->bkg_user_id;
		}

		if ($model->bkgPref->bkg_is_gozonow == 1 && $model->bkg_reconfirm_flag == 0)
		{
			$dataexist = BookingVendorRequest::getPreferredVendorbyBooking($model->bkg_bcb_id);
			if (!$dataexist)
			{
				$bkgId	 = $model->bkg_id;
				$hash	 = Yii::app()->shortHash->hash($model->bkg_id);
//				$url	 = Yii::app()->createAbsoluteUrl('/gznow/' . $bkgId . '/' . $hash);

				$this->redirect('/gznow/' . $bkgId . '/' . $hash);
				Yii::app()->end();
			}
		}


		if (isset($phash) && $phash != '')
		{
			$otpPhone = Yii::app()->shortHash->unHash($phash);
			if (($otpPhone == $model->bkgUserInfo->bkg_verification_code) && ($model->bkgUserInfo->bkg_phone_verified == 0))
			{
				$bkgUserModel						 = BookingUser::model()->find('bui_bkg_id=:bkg_id', ['bkg_id' => $model->bkg_id]);
				$bkgUserModel->bkg_phone_verified	 = 1;
				$bkgUserModel->save();
				$modelPhone							 = ContactPhone::model()->findPhoneIdByPhoneNumber($model->bkgUserInfo->bkg_contact_no);
				if ($modelPhone != null)
				{
					$modelPhn					 = ContactPhone::model()->findByPk($modelPhone->phn_id);
					$modelPhn->phn_is_verified	 = 1;
					$error						 = CActiveForm::validate($modelPhn);
					if ($error == '[]')
					{
						$modelPhn->save();
					}
				}
			}
		}
		if (isset($ehash) && $ehash != '')
		{
			$otpEmail = Yii::app()->shortHash->unHash($ehash);
			if (($otpEmail == $model->bkgUserInfo->bkg_verifycode_email) && ($model->bkgUserInfo->bkg_email_verified == 0))
			{
				$bkgUserModel						 = BookingUser::model()->find('bui_bkg_id=:bkg_id', ['bkg_id' => $model->bkg_id]);
				$bkgUserModel->bkg_email_verified	 = 1;
				$bkgUserModel->save();
				$modelEmail							 = ContactEmail::model()->findEmailIdByEmail($model->bkgUserInfo->bkg_user_email);
				if ($modelEmail != null)
				{
					$modelEml					 = ContactEmail::model()->findByPk($modelEmail->eml_id);
					$modelEml->eml_is_verified	 = 1;
					$error						 = CActiveForm::validate($modelEml);
					if ($error == '[]')
					{
						$modelEml->save();
					}
				}
			}
		}
		$this->pageTitle = "Booking ID " . $model->bkg_booking_id . " - " . $model->bkgFromCity->cty_name . '&#10147;' . $model->bkgToCity->cty_name . ' - ' . ucwords($model->getBookingType($model->bkg_booking_type, 'Trip'));
		if ($model->bkg_agent_id != '')
		{
			$isAgent	 = true;
			$returnUrl	 = Yii::app()->request->getParam("returnUrl");
			if ($returnUrl != '')
			{
				/* @var $app CWebApplication */
				$app			 = Yii::app();
				$returnUrlAgt	 = $app->getSession()->add("aps_" . $model->bkg_id, $returnUrl);
			}
			//$this->layout = "head1";
			//     $view = 'agentPayNow';
		}
		if ($agent_id != '')
		{
			$model->bkg_agent_id = $agent_id;
		}
		if ($qrId != '')
		{
			$model->bkg_qr_id = $qrId;
		}
		$showUserInfoPickup = Booking::model()->customerInfoByPickup1($model->bkg_id, 6); //Booking::model()->customerInfoByPickup($model->bkg_id, 10);

		$model->hash = $hash;
		//$model->scenario = 'advance_pay';
		//$model->bkgUserInfo->scenario = 'advance_pay';
		foreach ($model->bookingRoutes as $key => $bookingRoute)
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
		$noteArr			 = DestinationNote::model()->showBookingNotes($locationArr, $dateArr, $showNoteTo			 = 1);

		$bkgUserModel			 = BookingUser::model()->find('bui_bkg_id=:bkg_id', ['bkg_id' => $model->bkg_id]);
		$bkgUserModel->scenario	 = 'advance_pay';
		$bkgInvModel			 = BookingInvoice::model()->find('biv_bkg_id=:bkg_id', ['bkg_id' => $model->bkg_id]);
		$bkgInvModel->scenario	 = 'advance_pay';

		if (!$model || !$bkgUserModel || !$bkgInvModel)
		{
			throw new CHttpException(400, 'Invalid data');
		}
		if ($model->bkg_status > 7 && $model->bkg_status != 15 && $model->bkg_status != 9 && $model->bkg_status != 10)
		{
			throw new CHttpException(401, 'Booking not active');
		}

		//$bookingPickupTime = $model->bkg_pickup_date;
		//$pickdiff = $model->getPickupDiffinMinutes();
		$minDiff = $model->getPaymentExpiryTimeinMinutes();

		if ($transCode != '')
		{
			$paymentdone = true;
			$payment	 = true;
			$transResult = $this->getTransdetailByTranscode($transCode);
			$succ		 = 'fail';
			if ($transResult)
			{
				$transId	 = $transResult['transId'];
				$succ		 = $transResult['succ'];
				$tranStatus	 = $transResult['tranStatus'];
				if ($succ == "success")
				{
					QuotesSituation::setConFirmData($model->bkg_id);
					QuotesZoneSituation::setConFirmData($model->bkg_id);
				}
			}
		}
		/////////////////////
		// 5% discount =advpay=
		if ($model->bkgInvoice->bkg_promo1_id > 0 && ($model->bkgInvoice->bkg_advance_amount == 0 || $model->bkgInvoice->bkg_advance_amount == '' || $model->bkgInvoice->bkg_advance_amount == null) && ($model->bkgInvoice->bkg_discount_amount == 0 || $model->bkgInvoice->bkg_discount_amount == '' || $model->bkgInvoice->bkg_discount_amount == null) && ($model->bkgInvoice->bkg_credits_used == 0 || $model->bkgInvoice->bkg_credits_used == '' || $model->bkgInvoice->bkg_credits_used == null))
		{
			//$promoModel = Promos::model()->getByCode($model->bkgInvoice->bkg_promo1_code);
			$promoModel = Promos::model()->findByPk($model->bkgInvoice->bkg_promo1_id);
			if (!$promoModel)
			{
				throw new Exception('Invalid Promo code');
			}
			if ($promoModel->prm_activate_on == 1)
			{
				$promoModel->promoCode	 = $model->bkgInvoice->bkg_promo1_code;
				$promoModel->totalAmount = $model->bkgInvoice->bkg_base_amount;
				$promoModel->createDate	 = $model->bkg_create_date;
				$promoModel->pickupDate	 = $model->bkg_pickup_date;
				$promoModel->fromCityId	 = $model->bkg_from_city_id;
				$promoModel->toCityId	 = $model->bkg_to_city_id;
				$promoModel->userId		 = $model->bkgUserInfo->bkg_user_id;
				$promoModel->platform	 = $model->bkgTrail->bkg_platform;
				$promoModel->carType	 = $model->bkg_vehicle_type_id;
				$promoModel->bookingType = $model->bkg_booking_type;
				$promoModel->noOfSeat	 = $model->bkgAddInfo->bkg_no_person;
				$promoModel->bkgId		 = $model->bkg_id;
				$promoModel->email		 = '';
				$promoModel->phone		 = '';
				$promoModel->imEfect	 = '';

				$discountArr = $promoModel->applyPromoCode();
				//				if ($discountArr['pcn_type'] == 2 || $discountArr['pcn_type'] == 3)
				//				{
				//					$discountArr['cash'] = 0;
				//				}
				if (isset($discountArr['nextTripApply']) && $discountArr['nextTripApply'] == 1)
				{
					$discountArr['cash']	 = 0;
					$discountArr['coins']	 = 0;
				}
				$model->bkgInvoice->bkg_discount_amount	 = $discountArr['cash'];
				$model->bkgInvoice->bkg_promo1_amt		 = $discountArr['cash'];
				if ($discountArr['coins'] > 0)
				{
					$cashbackStatus = UserCredits::checkDuplicateCashbackStatus($model->bkg_id, $model->bkgUserInfo->bkg_user_id, $discountArr['coins']);
				}
			}
		}

		//		else if ($model->bkgInvoice->bkg_promo1_id != 0 && ($model->bkgInvoice->bkg_advance_amount == 0 || $model->bkgInvoice->bkg_advance_amount == '' || $model->bkgInvoice->bkg_advance_amount == null) && ($model->bkgInvoice->bkg_discount_amount == 0 || $model->bkgInvoice->bkg_discount_amount == '' || $model->bkgInvoice->bkg_discount_amount == null) && ($model->bkgInvoice->bkg_credits_used == 0 || $model->bkgInvoice->bkg_credits_used == '' || $model->bkgInvoice->bkg_credits_used == null))
		//		{
		//			$promoModel		 = Promos::model()->getByCode($model->bkgInvoice->bkg_promo1_code);
		//			$promoCalModel	 = PromoCalculation::model()->getByPromoId($promoModel->prm_id);
		//			if ($promoModel->prm_activate_on == 1 && ($promoCalModel->pcn_type == 1 || $promoCalModel->pcn_type == 3))
		//			{
		//				$bookingModel								 = Booking::model()->findByPk($model->bkg_id);
		//				$bookingModel->bkgInvoice->bkg_promo1_code	 = '';
		//				$bookingModel->bkgInvoice->bkg_promo1_id	 = '0';
		//				$bookingModel->bkgInvoice->bkg_promo1_amt	 = '0';
		//				$bookingModel->save();
		//			}
		//		}
		if ($iscreditapplied > 0)
		{
			$model->bkgInvoice->bkg_credits_used = $iscreditapplied;
		}



		$model->bkgInvoice->calculateConvenienceFee(0);
		$model->bkgInvoice->calculateTotal();
		if ($minDiff > 0)
		{
			$amount								 = ($model->bkgInvoice->bkg_advance_amount > 0 || $model->bkgInvoice->bkg_credits_used > 0) ? $model->bkgInvoice->bkg_due_amount : $model->bkgInvoice->bkg_total_amount;
			$model->bkgInvoice->partialPayment	 = round($amount);
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . (($outputJs) ? "Partial" : "");
		if ($platform == 3)
		{
			$this->layout	 = "head1";
			$outputJs		 = true;
		}
		$promoRule				 = Promos::model()->getExpTimeAdvPromo($model->bkg_create_date, $model->bookingRoutes[0]->brt_pickup_datetime);
		$promoModel				 = new Promos();
		$promoModel->promoCode	 = '';
		$promoModel->totalAmount = $model->bkgInvoice->bkg_base_amount;
		$promoModel->createDate	 = $model->bkg_create_date;
		$promoModel->pickupDate	 = $model->bkg_pickup_date;
		$promoModel->fromCityId	 = $model->bkg_from_city_id;
		$promoModel->toCityId	 = $model->bkg_to_city_id;
		$promoModel->userId		 = $model->bkgUserInfo->bkg_user_id;
		$promoModel->platform	 = $model->bkgTrail->bkg_platform;
		$promoModel->carType	 = $model->bkg_vehicle_type_id;
		$promoModel->bookingType = $model->bkg_booking_type;
		$promoModel->bkgId		 = $model->bkg_id;
		//$promoArr				 = Promos::model()->getApplicableCodes($model->bkg_pickup_date, $model->bkg_create_date, $model->bkg_from_city_id, $model->bkg_to_city_id, $model->bkgUserInfo->bkg_user_id, $model->bkgInvoice->bkg_base_amount, $model->bkgTrail->bkg_platform);
		if (!$model->bkg_cav_id)
		{
			$promoArr = $promoModel->getApplicableCodes();
		}
		$model1				 = clone $model;
		$model1->bkgInvoice->calculateConvenienceFee(0);
		$userCreditStatus	 = UserCredits::model()->getGozocoinsUsesStatus($model->bkgUserInfo->bkg_user_id);
		if ($userCreditStatus == 1 && ($model1->bkgInvoice->bkg_advance_amount == 0 || $model1->bkgInvoice->bkg_advance_amount == '') && $cashbackStatus)
		{
			$gozocoinApply	 = 1;
			$usepromo		 = true;
		}
		else
		{
			$usepromo = ($model->bkgInvoice->bkg_promo1_id == 0);
		}
		$MaxCredits	 = UserCredits::getApplicableCredits($model->bkgUserInfo->bkg_user_id, $model->bkgInvoice->bkg_base_amount, $usepromo, $model->bkg_from_city_id, $model->bkg_to_city_id);
		$creditVal	 = $MaxCredits['credits'];

		$model->bkgUserInfo	 = $bkgUserModel;
		$walletBalance		 = UserWallet::model()->getBalance(UserInfo::getUserId());
		$refcode			 = "";
		$whatappShareLink	 = "";
		if ($model->bkgUserInfo->bkg_user_id > 0)
		{
			$users				 = Users::model()->findByPk($model->bkgUserInfo->bkg_user_id);
			$refcode			 = $users->usr_refer_code;
			$whatappShareLink	 = Users::model()->whatsappShareTemplate($refcode);
		}
		$addOns			 = AddonServiceClassRule::getApplicableAddons($model->bkg_from_city_id, $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_id, $model->bkgInvoice->bkg_base_amount);
		$routeRates		 = BookingInvoice::getRatesWithAddons($addOns, $model->bkgInvoice);
		$isRescheduled	 = 0;

		if ($transCode != '')
		{
			$pgModel = PaymentGateway::model()->getByCode($transCode);
		}
		if ($model->bkgPref->bpr_rescheduled_from > 0)
		{
			if ($pgModel->apg_status == 1 || $isRescheduleWalletPayment == 1)
			{
				$oldModel = Booking::model()->findByPk($model->bkgPref->bpr_rescheduled_from);
				if (!in_array($oldModel->bkg_status, [9, 10]) && !in_array($model->bkg_status, [9, 10]))
				{
					if ($oldModel->bkgTrack->bkg_ride_start == 1)
					{
						$isRescheduled	 = 2;
						$userInfo		 = UserInfo::getInstance();
						if ($model->bkgInvoice->bkg_advance_amount - $model->bkgInvoice->bkg_refund_amount > 0)
						{
							PaymentGateway::model()->refundByRefId($amount, $model->bkg_id, Accounting:: AT_BOOKING, $userInfo, false, false, true);
							BookingLog::model()->createLog($model->bkg_id, "Payment reverted to wallet, as ride already started, reschedule failed.", $userInfo, BookingLog::REFUND_PROCESS_COMPLETED);
							$model->refresh();
						}
					}
					else
					{
						$returnSet = Booking::cancelOnReschedule($model->bkg_id, $model->bkgPref->bpr_rescheduled_from);
						if ($returnSet->getStatus())
						{
							$isRescheduled = 1;
							$model->refresh();
						}
					}
				}
			}
//			else
//			{
//				$isRescheduled = 3;
//			}
		}
		$this->$method('booksummary', array('isredirct'			 => true, 'model'				 => $model,
			'creditVal'			 => $creditVal, 'model1'			 => $model1, 'showUserInfoPickup' => $showUserInfoPickup,
			'paymentdone'		 => $paymentdone, 'transid'			 => $transId, 'succ'				 => $succ, 'note'				 => $noteArr,
			'promoArr'			 => $promoArr, 'userCreditStatus'	 => $userCreditStatus, 'gozocoinApply'		 => $gozocoinApply, 'walletBalance'		 => $walletBalance,
			'refcode'			 => $refcode, 'whatappShareLink'	 => $whatappShareLink, 'applicableAddons'	 => $addOns, 'routeRatesArr'		 => $routeRates, 'actiondone'		 => $actiondone, 'isRescheduled'		 => $isRescheduled), false, $outputJs);

		//		$this->$method($view, array
//			('model'				 => $model, 'model1'			 => $model1, 'ccmodel'			 => $payment, 'minPay'			 => $minPay, 'minDiff'			 => $minDiff, 'src'				 => $src, 'isAgent'			 => $isAgent, 'platform'			 => $platform,
//			'cod'				 => $cod, 'serviceTax'		 => $serviceTax, 'amountWithCod'		 => $amountWithCod, 'showUserInfoPickup' => $showUserInfoPickup, 'succ'				 => $succ, 'paymentdone'		 => $paymentdone), false, $outputJs);
	}

	public function actionVendorpaynow()
	{
		$id		 = Yii::app()->request->getParam('id');
		$hash	 = Yii::app()->request->getParam('hash');
		if ($id != Yii::app()->shortHash->unHash($hash))
		{
			throw new CHttpException(400, 'Invalid data');
		}
		/* @var $model Booking */
		$model		 = Booking::model()->findByPk($id);
		$model->hash = $hash;
		$cabModel	 = $model->getBookingCabModel();
		$bModels	 = $cabModel->bookings;
		$payment	 = new BraintreeCCForm('creditcard');
		if (!$model)
		{
			throw new CHttpException(400, 'Invalid data');
		}
		if ($model->bkg_status > 7)
		{
			throw new CHttpException(401, 'Booking not active');
		}
		$pickupData			 = Booking::model()->customerInfoByPickup($model->bkg_id);
		$showUserInfoPickup	 = $pickupData['showUserInfoPickup'];

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('vendor_summary', array('bModels'			 => $bModels, 'model'				 => $model,
			'cabModel'			 => $cabModel,
			'ccmodel'			 => $payment,
			'minDiff'			 => $minDiff,
			'showUserInfoPickup' => $showUserInfoPickup), false, $outputJs);
	}

	public function actionVendorpay()
	{
		$hashId		 = Yii::app()->request->getParam('id');
		$hashVndId	 = Yii::app()->request->getParam('vndId');

		$Id		 = Yii::app()->shortHash->unHash($hashId);
		$vndId	 = Yii::app()->shortHash->unHash($hashVndId);
		if (!($Id > 0) || !($vndId > 0))
		{
			throw new CHttpException(400, 'Invalid data');
		}
		/* @var $model Booking */
		$model				 = Booking::model()->findByPk($Id);
		$this->pageTitle	 = '' . $model->bkgFromCity->cty_name . ' to ' . $model->bkgToCity->cty_name;
		$cabModel			 = $model->getBookingCabModel();
		$bModels			 = $cabModel->bookings;
		$cancelTimes_new	 = CancellationPolicy::initiateRequest($model);
		$bookingRouteModel	 = BookingRoute::model()->findAll('brt_bkg_id=:id', ['id' => $model->bkg_id]);
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
		$noteArr			 = DestinationNote::model()->showBookingNotes($locationArr, $dateArr);

//		if (($model->bkg_id <> $Id) || ($cabModel->bcb_vendor_id <> $vndId))
//		{
//			throw new CHttpException(400, 'Invalid data');
//		}
//		$payment = new BraintreeCCForm('creditcard');
//		if (!$model)
//		{
//			throw new CHttpException(400, 'Invalid data');
//		}
//		if ($model->bkg_status > 7)
//		{
//			throw new CHttpException(401, 'Booking not active');
//		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$pageName	 = 'bkvnSummary';
		//$pageName   = 'vendor_summary';
		$this->$method($pageName, array('bModels'			 => $bModels,
			'model'				 => $model,
			'cancelTimes_new'	 => $cancelTimes_new,
			'note'				 => $noteArr,
			'cabModel'			 => $cabModel,
			'ccmodel'			 => $payment), false, $outputJs);
	}

	public function actionVendortrip()
	{
		$this->checkV2Theme();
		$hashId		 = Yii::app()->request->getParam('tripHash');
		$hashVndId	 = Yii::app()->request->getParam('vndHash');

		$tripId	 = Yii::app()->shortHash->unHash($hashId);
		$vndId	 = Yii::app()->shortHash->unHash($hashVndId);

		if (!($tripId > 0) || !($vndId > 0))
		{
			//throw new CHttpException(400, 'Invalid data');
			$error = "No data found";
		}

		/* @var $model Booking */
		$tripDetailQry = BookingCab::getTripDetails($tripId);
//		$routeCount		 = $tripDetailQry->getRowCount();
//		$bookingRouteModel	 = BookingRoute::model()->findAll('brt_bkg_id=:id', ['id' => $bModels[0]->bkg_id]);
		foreach ($tripDetailQry as $key => $bookingRoute)
		{
			if ($key == 0)
			{
				$fromCityName	 = $bookingRoute['fromCityName'];
				$locationArr[]	 = $bookingRoute['brt_from_city_id'];
			}
			$toCityName = $bookingRoute['toCityName'];

			$locationArr[]		 = $bookingRoute['brt_to_city_id'];
			$pickup_date[$key]	 = $bookingRoute['brt_pickup_datetime'];
			$temp_last_date		 = strtotime($bookingRoute['brt_pickup_datetime']) + $bookingRoute['brt_trip_duration'];
			$drop_date_time		 = date('Y-m-d H:i:s', $temp_last_date);
		}

		$this->pageTitle = '' . $fromCityName . ' to ' . $toCityName . ' (Trip ID:' . $bookingRoute['tripId'] . ')';
		$cabModel		 = BookingCab::model()->findByPk($tripId);
		$bModels		 = $cabModel->bookings;

//		$pickup_date_time	 = $pickup_date[0];
//		$locationArr		 = array_unique(array_merge($pickupCity, $dropCity));
//		$dateArr			 = array($pickup_date_time, $drop_date_time);
//		$noteArr			 = DestinationNote::model()->showBookingNotes($locationArr, $dateArr);

		if ($bModels[0]->bkg_status != 2)
		{
			#throw new CHttpException(401, 'Booking already taken');
			$error = "Booking already taken by another vendor";
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$pageName	 = 'bkvnTripSummary';
		$this->$method($pageName, array('bModels'	 => $bModels,
			'cabModel'	 => $cabModel, 'error'		 => $error), false, $outputJs);
	}

	public function actionViewCustomerDetails()
	{
		$bkg_id	 = Yii::app()->request->getParam('booking_id');
		$type	 = Yii::app()->request->getParam('type');
		$success = false;
		$model	 = Booking::model()->findByPk($bkg_id);
		if ($model)
		{
			if ($model->bkg_status != 9)
			{
				if ($type == 2)
				{
					$success = $model->bkgTrack->updateCustomerDetailsViewedFlag();
				}
				else
				{
					$success = $model->bkgTrack->updateDriverDetailsViewedFlag();
				}
				$drvViewTime = $model->bkgTrack->btk_drv_details_viewed_datetime;
			}
		}

		$data = ['success' => $success, 'drvviewtime' => $drvViewTime];
		echo json_encode($data);
	}

	public function btreeResponse($payment, $model, $ccData, $transModel)
	{
		$success					 = false;
		$amount						 = $model->partialPayment;
		$payment->setScenario('customer');
		$name						 = trim($model->bkg_bill_fullname);
		$name1						 = explode(" ", $name);
		$lname						 = array_pop($name1);
		$fname						 = (empty($name1)) ? $lname : implode(' ', $name1);
		$dollarToRupeeRate			 = Yii::app()->params['dollarToRupeeRate'];
		$damount					 = round($amount / $dollarToRupeeRate, 2);
		$payment->customer_firstName = $model->bkg_user_fname;
		$payment->customer_lastName	 = $model->bkg_user_lname;
		$payment->customer_email	 = $model->bkg_user_email;
		$payment->amount			 = $damount;
		$payment->orderId			 = $transModel->apg_code;
		$payment->descriptor		 = $model->bkgFromCity->cty_name . '/' . $model->bkgToCity->cty_name . '/' . $model->bkg_booking_id;

		$payment->billing_firstName		 = $fname;
		$payment->billing_lastName		 = $lname;
		$payment->billing_company		 = '';
		$payment->billing_streetAddress	 = $model->bkg_bill_address;
		$payment->billing_postalCode	 = $model->bkg_bill_postalcode;
		$payment->creditCard_name		 = $ccData['creditCard_name'];
		$payment->creditCard_number		 = $ccData['creditCard_number'];
		$payment->creditCard_cvv		 = $ccData['creditCard_cvv'];
		$payment->creditCard_month		 = $ccData['creditCard_month'];
		$payment->creditCard_year		 = $ccData['creditCard_year'];
		$result1						 = CActiveForm::validate($payment, null, false);
		if ($result1 == '[]')
		{
			try
			{
				$payment->setScenario('customer');
				$return1 = $payment->send();

				$payment->setScenario('creditcard');
				//$payment->setAttributes($ccData);
				$payment->customerId = $return1['result']->customer->id;
				$result1			 = CActiveForm::validate($payment, null, false);
				if ($result1 == '[]')
				{
					$return1 = $payment->send();
					$payment->setScenario('charge');
					$api	 = $payment->BraintreeApi;

					$api->setAmount($damount);
					$res = $api->singleCharge();

					$resCode = $res['result']->transaction->processorResponseCode;

					$url			 = '';
					$error			 = '';
					$success		 = false;
					//					if ($res['status'])
					//					{
					$succresponseArr = array_values((array) $res['result']->transaction);

					//  $succresponse
					$resCode			 = $res['result']->transaction->processorResponseCode;
					$respArr			 = $succresponseArr[0];
					$respArr['resCode']	 = $resCode;
					$respArr['status']	 = $res['status'];
					//$succresponse = json_encode($succresponseArr[0]);
					$ptype				 = PaymentType::TYPE_INTERNATIONAL_CARD;
					PaymentGateway::model()->updatePGResponse($succresponseArr[0], $ptype);
				}
			}
			catch (Exception $e)
			{
				$data = ['result' => $result1, 'url' => $url, 'success' => $success, 'errors' => $e->getMessage(), 'transcode' => $transModel->trans_code];
			}
		}
		$data = ['result' => $result1, 'url' => $url, 'success' => $success, 'resArray' => $resArray, 'errors' => $payment->getErrors(), 'transcode' => $transModel->trans_code];
		return $data;
	}

	public function actionAmountinwords($amount = 0)
	{
		$amount	 = Yii::app()->request->getParam('amount');
		$rupees	 = '';
		if ($amount > 0)
		{
			$filter	 = new Filter();
			$rupees	 = 'Rupees' . ucwords($filter->convertNumberToWord($amount)) . ' only.';
		}
		echo $rupees;
	}

	public function getModificationMSG($diff, $user)
	{
		$msg = '';
		if (count($diff) > 0)
		{
			if ($diff ['consumer_name'])
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
			if ($diff ['consumer_alt_phone'])
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
			if ($diff ['pick_date'])
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
			if ($diff['bkg_drop_address'])
			{
				$msg .= ' Drop Address: ' . $diff['bkg_drop_address'] . ',';
			}
			if ($diff ['cab_type'])
			{
				$msg .= ' Cab Type: ' . $diff ['cab_type'] . ',';
			}
			if ($diff['cab_assigned'] && $diff['cab_assigned'] != '')
			{
				$msg .= ' Cab Assigned: ' . $diff['cab_assigned'] . ',';
			}
			if ($diff['bkg_additional_charge'])
			{
				$msg .= ' Additional Charge: ' . $diff['bkg_additional_charge'] . ',';
			}
			if ($diff['payable_amount'])
			{
				$msg .= ' Payable Amount: ' . $diff['payable_amount'] . ',';
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
				if ($diff['bkg_invoice'])
				{
					$msg .= ' Invoice Requirement Changed,';
				}
			}
			$msg = rtrim($msg, ',');
		}
		return $msg;
	}

	public function actionGetaddressdata()
	{
		/* @var $model Booking */
		$bkgid	 = Yii::app()->request->getParam('bkgid');
		$bkghash = Yii::app()->request->getParam('bkghash');
		$opt	 = Yii::app()->request->getParam('opt');
		if ($bkgid != Yii::app()->shortHash->unHash($bkghash))
		{
			throw new CHttpException(400, 'Invalid data');
		}
		$model			 = Booking::model()->findByPk($bkgid);
		$res			 = [];
		$res['country']	 = ($model->bkgUserInfo->bkg_bill_country == '') ? 'IN' : $model->bkgUserInfo->bkg_bill_country;
		if ($opt == 1)
		{
			$res['city']	 = $model->bkgFromCity->cty_name;
			$res['state']	 = $model->bkgFromCity->ctyState->stt_name;
		}
		if ($opt == 2)
		{
			$res['city']	 = $model->bkgToCity->cty_name;
			$res['state']	 = $model->bkgToCity->ctyState->stt_name;
		}
		echo CJSON::encode($res);
	}

	public function actionCheckcode()
	{
		$bcode	 = Yii::app()->request->getParam('bcode');
		$model	 = Booking::model()->getByCode($bcode);
		$result	 = [];
		if ($model)
		{
			$result['bkgid'] = $model->bkg_id;
			$amount_due		 = ($model->bkg_advance_amount > 0) ? $model->bkg_due_amount : $model->bkg_total_amount;
			$amount_paid	 = ($model->bkg_advance_amount > 0) ? $model->bkg_advance_amount : 0;

			$result['success']		 = true;
			$result['amount_due']	 = $amount_due;
			$result['amount_paid']	 = $amount_paid;
			$result['amount_net']	 = $model->bkg_base_amount;
		}
		else
		{
			$result['success'] = false;
		}
		echo CJSON::encode($result);
	}

	public function actionGetroutename()
	{
		$fcity		 = Yii::app()->request->getParam('fcity');
		$tcity		 = Yii::app()->request->getParam('tcity');
		$bkgtype	 = Yii::app()->request->getParam('bkgtype', 1);
		$model		 = Route::model()->getbyCities($fcity, $tcity);
		$rutid		 = $model->rut_id;
		$rutname	 = ($model->rut_name != '') ? $model->rut_name : false;
		$distance	 = $model->rut_estm_distance * $bkgtype;
		$duration	 = $model->rut_estm_time * $bkgtype;
		echo CJSON::encode(['rutname' => $rutname, 'rutid' => $rutid, 'distance' => $distance, 'duration' => $duration]);
	}

	public function actionGetcanceldesctext()
	{
		$rval		 = Yii::app()->request->getParam('rval');
		$reasonText	 = CancelReasons::model()->getUserDescTextbyId($rval);
		echo CJSON::encode(['rtext' => $reasonText]);
	}

	public function actionGetduration()
	{
		$fromCity	 = Yii::app()->request->getParam('fromCity') . ',India';
		$toCity		 = Yii::app()->request->getParam('toCity') . ',India';
		$fromCityId	 = Yii::app()->request->getParam('fromCityId');
		$toCityId	 = Yii::app()->request->getParam('toCityId');
		$arqot[]	 = ['pickup_city'	 => $fromCityId,
			'drop_city'		 => $toCityId,
		];

		$gf		 = json_encode($arqot);
		$qotData = json_decode($gf);

		$resArr			 = Quotation::model()->calculateDistance(3, $qotData);
		//  $resArr['endTripDate'] = ;
		//$resArr = Booking::model()->getDistance($fromCity, $toCity);
		$duration		 = $resArr['calculateTime'];
		$data['dura']	 = $duration;
		echo CJSON::encode($data);
		Yii::app()->end();
	}

	public function additionaldetails($data, $routes, $is_corporate = '', $lead_id, $isPromoApplied)
	{
		Logger::create("Bkg id: " . $data['bkg_id']);
		$oldBkgId = $data['bkg_id'];
		if ($oldBkgId > 0)
		{
			$model = Booking::model()->findByPk($oldBkgId);
			if ($model)
			{
				$model->scenario = 'new';
			}
		}
		else
		{
			$model = new Booking('new');

			$model->bkgUserInfo = new BookingUser();

			$model->bkgInvoice = new BookingInvoice();

			$model->bkgTrail = new BookingTrail();

			$model->bkgTrack	 = new BookingTrack();
			$model->bkgAddInfo	 = new BookingAddInfo();
			$model->bkgPref		 = new BookingPref();
			$model->bkgPf		 = new BookingPriceFactor();
		}
		//$leadModel							 = BookingTemp::model()->findByPk($lead_id);
		if ($lead_id > 0)
		{
			$leadModel = BookingTemp::model()->findByPk($lead_id);
		}
		else
		{
			$leadModel = new BookingTemp('new');
		}
		$model->attributes					 = $data;
		$model->bkgUserInfo->attributes		 = $data;
		$model->bkgUserInfo->bkg_user_fname	 = $data['bkg_user_name'];
		$model->bkgInvoice->attributes		 = $data;
		$model->bkgTrail->attributes		 = $data;
		$model->bkgTrack->attributes		 = $data;
		$model->bkgAddInfo->attributes		 = $data;
		$model->bkgPref->attributes			 = $data;
		$model->bkgPf->attributes			 = $data;
		//print_r($model['attributes']);

		$data	 = ['success' => false, 'data' => $model, "errors" => $model->getErrors()];
		$result	 = CActiveForm::validate($model);
		if ($result == '[]')
		{
			$model->bkgTrail->bkg_platform = Booking::Platform_App;
			if ($model->bkg_id == '')
			{
				$model->bkg_id = null;
			}
			//			$carType = VehicleTypes::model()->getVehicleTypeById($model->bkg_vehicle_type_id);
			$carType = $model->bkg_vehicle_type_id;
			$route	 = [];
			foreach ($routes as $key => $val)
			{
				$routeModel							 = new BookingRoute();
				$routeModel->brt_from_city_id		 = $val->pickup_city;
				$routeModel->brt_to_city_id			 = $val->drop_city;
				$routeModel->brt_pickup_datetime	 = $val->date;
				$routeModel->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($val->date);
				$routeModel->brt_pickup_date_time	 = date('h:i A', strtotime($val->date));
				$routeModel->brt_to_location		 = $val->drop_address;
				$routeModel->brt_from_location		 = $val->pickup_address;
				$routeModel->brt_to_pincode			 = $val->drop_pincode;
				$routeModel->brt_from_pincode		 = $val->pickup_pincode;
				$routeModel->brt_from_latitude		 = $val->pickup_lat;
				$routeModel->brt_from_longitude		 = $val->pickup_long;
				$routeModel->brt_to_latitude		 = $val->drop_lat;
				$routeModel->brt_to_longitude		 = $val->drop_long;
				$route[]							 = $routeModel;
			}
			//  $bkgType = ($model->bkg_booking_type == 4) ? 1 : $model->bkg_booking_type; //treating transfers as oneway
			$bkgType	 = $model->bkg_booking_type;
			$partnerId	 = ($model->bkg_agent_id > 0) ? $model->bkg_agent_id : Yii::app()->params['gozoChannelPartnerId'];
			// $model->bkg_agent_id	 = $partnerId;
//            $qt        = Quotation::model()->getQuote1($route, $bkgType, $partnerId, $carType);

			$quote					 = new Quote();
			$quote->routes			 = $route;
			$quote->tripType		 = $bkgType;
			$quote->partnerId		 = $partnerId;
			$quote->quoteDate		 = $model->bkg_create_date;
			$quote->pickupDate		 = $model->bkg_pickup_date;
			$quote->returnDate		 = $model->bkg_return_date;
			$quote->sourceQuotation	 = Quote::Platform_App;
			$quote->setCabTypeArr();
			$qt						 = $quote->getQuote($carType);
			$arrQuot				 = $qt[$carType];

			$routeRates		 = $arrQuot->routeRates;
			$routeDistance	 = $arrQuot->routeDistance;
			$routeDuration	 = $arrQuot->routeDuration;
			if (!$arrQuot->success)
			{
				throw new Exception("Request cannot be processed", 102);
			}
			$rCount = count($routes);

			$model->bkg_from_city_id						 = $route[0]->brt_from_city_id;
			$model->bkg_to_city_id							 = $route[$rCount - 1]->brt_to_city_id;
			$model->bkg_trip_distance						 = $routeDistance->quotedDistance; //$qt['routeData']['quoted_km'];
			$model->bkg_trip_duration						 = (string) $routeDuration->tripDuration; // $qt['routeData']['days']['totalMin'];
			$model->bkg_pickup_address						 = $routes[0]->pickup_address;
			$model->bkg_drop_address						 = $routes[$rCount - 1]->drop_address;
			//$model->bkg_pickup_pincode			 = $routes[0]->pickup_pincode;
			//$model->bkg_drop_pincode			 = $routes[$rCount - 1]->drop_pincode;
			$model->bkg_pickup_date							 = $routeDuration->fromDate; // $qt['routeData']['startTripDate'];
			$model->bkgInvoice->bkg_chargeable_distance		 = $routeDistance->quotedDistance; //$arrQuot['chargeableDistance'];
			$model->bkgTrack->bkg_garage_time				 = $routeDuration->totalMinutes + $routeDuration->garageTimeEnd + $routeDuration->garageTimeStart; //$qt['routeData']['totalGarage'];
			//$model->bkg_pickup_time				 = date('H:i:00', strtotime($routeDuration->fromDate));
			$model->bkgInvoice->bkg_driver_allowance_amount	 = $routeRates->driverAllowance; //$arrQuot['driverAllowance'];
			$model->bkgInvoice->bkg_is_toll_tax_included	 = $routeRates->isTollIncluded | 0; //$arrQuot['tolltax'];
			$model->bkgInvoice->bkg_is_state_tax_included	 = $routeRates->isStateTaxIncluded | 0; //$arrQuot['statetax'];
			$model->bkgInvoice->bkg_gozo_base_amount		 = round($routeRates->baseAmount); //round($arrQuot['gozo_base_amount']);
			$model->bkgInvoice->bkg_base_amount				 = round($routeRates->baseAmount);
			$model->bkgInvoice->bkg_total_amount			 = round($routeRates->totalAmount);

			if ($isPromoApplied)
			{
				$promoModel				 = new Promos();
				$promoModel->promoCode	 = '';
				$promoModel->totalAmount = $routeRates->baseAmount;
				$promoModel->createDate	 = $model->bkg_create_date;
				$promoModel->pickupDate	 = $model->bkg_pickup_date;
				$promoModel->fromCityId	 = $model->bkg_from_city_id;
				$promoModel->toCityId	 = $model->bkg_to_city_id;
				$promoModel->userId		 = $model->bkgUserInfo->bkg_user_id;
				$promoModel->platform	 = $model->bkgTrail->bkg_platform;
				$promoModel->carType	 = $model->bkg_vehicle_type_id;
				$promoModel->bookingType = $model->bkg_booking_type;
				//$promoModel->email	 = $model->bkg_user_email;
				$promoModel->phone		 = $model->bkgUserInfo->bkg_contact_no;
				$promoModel->bkgId		 = $model->bkg_id;
				$promoArr				 = $promoModel->getApplicableCodes(true);

				if (count($promoArr) == 0)
				{
					$promoValue	 = 0;
					$promoMax	 = 0;
					$promoMin	 = 0;
					$promoCode	 = "";
				}
				else
				{
					$promoArr		 = array_values($promoArr);
					$promoValue[0]	 = $promoArr[0]['pcn_value_cash'];
					$promoMax[0]	 = $promoArr[0]['pcn_max_cash'];
					$promoMin[0]	 = $promoArr[0]['pcn_min_cash'];
					$promoCode[0]	 = $promoArr[0]['prm_code'];
					$promoID[0]		 = $promoArr[0]['prm_id'];
				}
				$discountedArr							 = [];
				$discountedArr['dicounted_base_price']	 = $routeRates->baseAmount - PromoCalculation::model()->calculatePromoAmount($promoMax[0], $promoMin[0], 1, $promoValue[0], $routeRates->baseAmount);
				$model->bkgInvoice->bkg_promo1_code		 = $promoCode[0];
				$model->bkgInvoice->bkg_promo1_id		 = $promoID[0];
				$model->bkgInvoice->bkg_discount_amount	 = $routeRates->baseAmount - $discountedArr['dicounted_base_price'];
				$staxrate								 = BookingInvoice::getGstTaxRate($model->bkg_agent_id, $model->bkg_booking_type);
				$model->bkgInvoice->bkg_service_tax		 = round(($discountedArr['dicounted_base_price'] + $routeRates->tollTaxAmount + $routeRates->stateTax + $routeRates->driverAllowance + $routeRates->parkingAmount ) * ($staxrate) * 0.01);
				$model->bkgInvoice->calculateTotal();
			}
			$model->bkgInvoice->bkg_vendor_amount			 = round($routeRates->vendorAmount);
			$model->bkgInvoice->bkg_quoted_vendor_amount	 = round($routeRates->vendorAmount | 0);
			$model->bkgInvoice->bkg_rate_per_km_extra		 = round($routeRates->ratePerKM);
			$model->bkgInvoice->bkg_rate_per_km				 = round($routeRates->costPerKM);
			$model->bkgInvoice->bkg_toll_tax				 = round($routeRates->tollTaxAmount | 0);
			$model->bkgInvoice->bkg_state_tax				 = round($routeRates->stateTax | 0);
			$model->bkgInvoice->bkg_night_pickup_included	 = $routeRates->isNightPickupIncluded | 0;
			$model->bkgInvoice->bkg_night_drop_included		 = $routeRates->isNightDropIncluded | 0;

			$model->bkgInvoice->bkg_surge_differentiate_amount	 = $routeRates->differentiateSurgeAmount;
			$model->bkgPf->bpf_bkg_id							 = $model->bkg_id;
			$model->bkgPref->bkg_driver_app_required			 = 1;
			if (SvcClassVhcCat::getClassById($model->bkg_vehicle_type_id) == (ServiceClass::CLASS_VALUE_CNG))
			{
				$model->bkgPref->bkg_cng_allowed = 1;
			}


			if ($model->bkg_booking_type == 2 || $model->bkg_booking_type == 3)
			{
				$returnDate					 = $routeDuration->toDate;
				$model->bkg_return_date_date = DateTimeFormat::DateTimeToDatePicker($returnDate);
				$model->bkg_return_date_time = date('H:i:00', strtotime($returnDate));
				$model->bkg_return_date		 = $returnDate;
				//$model->bkg_return_time		 = date('H:i:00', strtotime($returnDate));
			}
			//remove promo if back pressed
			if ($model->bkgInvoice->bkg_promo1_id > 0 && $isPromoApplied != 1)
			{
				//$promoModel = Promos::model()->getByCode($model->bkgInvoice->bkg_promo1_code);
				$promoModel = Promos::model()->findByPk($model->bkgInvoice->bkg_promo1_id);
				if (!$promoModel)
				{
					throw new Exception('Invalid Promo code');
				}
				$promoModel->promoCode	 = $model->bkgInvoice->bkg_promo1_code;
				$promoModel->totalAmount = $model->bkgInvoice->bkg_base_amount;
				$promoModel->createDate	 = $model->bkg_create_date;
				$promoModel->pickupDate	 = $model->bkg_pickup_date;
				$promoModel->fromCityId	 = $model->bkg_from_city_id;
				$promoModel->toCityId	 = $model->bkg_to_city_id;
				$promoModel->userId		 = $model->bkgUserInfo->bkg_user_id;
				$promoModel->platform	 = $model->bkgTrail->bkg_platform;
				$promoModel->carType	 = $model->bkg_vehicle_type_id;
				$promoModel->bookingType = $model->bkg_booking_type;
				$promoModel->noOfSeat	 = $model->bkgAddInfo->bkg_no_person;
				$promoModel->bkgId		 = $model->bkg_id;
				$promoModel->email		 = '';
				$promoModel->phone		 = '';
				$promoModel->imEfect	 = '';

				$discountArr = $promoModel->applyPromoCode();

				if ($discountArr != false)
				{
					if (isset($discountArr['nextTripApply']) && $discountArr['nextTripApply'] == 1)
					{
						$discountArr['cash']	 = 0;
						$discountArr['coins']	 = 0;
					}
					if ($discountArr['pcn_type'] == 2 || $discountArr['pcn_type'] == 3)
					{
						$discountArr['cash'] = 0;
					}
					$prmdiscount = $discountArr['cash'];
				}
				else
				{
					$prmdiscount = $model->bkgInvoice->bkg_promo1_amt;
				}
				$model->bkgInvoice->bkg_promo1_code		 = '';
				$model->bkgInvoice->bkg_promo1_id		 = '0';
				$model->bkgInvoice->bkg_promo1_amt		 = '0';
				$remainigDiscount						 = $model->bkgInvoice->bkg_discount_amount - $prmdiscount;
				$discount								 = ($remainigDiscount > 0) ? $remainigDiscount : 0;
				$model->bkgInvoice->bkg_discount_amount	 = $discount;
			}
			//remove promo if back pressed
			if ($is_corporate)
			{
				$userId				 = Yii::app()->user->getId();
				$userModel			 = Users::model()->findByPk($userId);
				$corporateId		 = $userModel->usr_corporate_id;
				$model->bkgInvoice->populateCorporateAmount($corporateId);
				$model->bkgInvoice->calculateVendorAmount();
				$model->bkg_agent_id = $corporateId;
			}
			else
			{
				//$model->bkgInvoice->calculateConvenienceFee();
				// $model->calculateConvenienceFee(0); //waive off
				//$model->bkgInvoice->calculateTotal();
				$model->bkgInvoice->populateAmount(true, true, true, true, $model->bkg_agent_id);
				//$model->calculateVendorAmount();
			}
			if ($model->bkg_agent_id != '')
			{
				$agtModel = Agents::model()->findByPk($model->bkg_agent_id);
				if ($agtModel->agt_city == 30706)
				{
					$model->bkgInvoice->bkg_cgst = Yii::app()->params['cgst'];
					$model->bkgInvoice->bkg_sgst = Yii::app()->params['sgst'];
					$model->bkgInvoice->bkg_igst = 0;
				}
				else
				{
					$model->bkgInvoice->bkg_igst = Yii::app()->params['igst'];
					$model->bkgInvoice->bkg_cgst = 0;
					$model->bkgInvoice->bkg_sgst = 0;
				}
			}
			else
			{
				if ($model->bkg_from_city_id == 30706)
				{
					$model->bkgInvoice->bkg_cgst = Yii::app()->params['cgst'];
					$model->bkgInvoice->bkg_sgst = Yii::app()->params['sgst'];
					$model->bkgInvoice->bkg_igst = 0;
				}
				else
				{
					$model->bkgInvoice->bkg_igst = Yii::app()->params['igst'];
					$model->bkgInvoice->bkg_cgst = 0;
					$model->bkgInvoice->bkg_sgst = 0;
				}
			}
			$model->bkgUserInfo->bkg_user_last_updated_on	 = new CDbExpression('NOW()');
			$model->bkgTrail->bkg_user_ip					 = \Filter::getUserIP();
			$cityinfo										 = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
			$model->bkgUserInfo->bkg_user_city				 = $cityinfo['city'];
			$model->bkgUserInfo->bkg_user_country			 = $cityinfo['country'];
			$model->bkgTrail->bkg_user_device				 = UserLog::model()->getDevice();
			$model->bkgTrail->setPaymentExpiryTime($model->bkg_pickup_date);
			$model->bkg_status								 = 15;
			$model->scenario								 = 'cabRate';
			$model->bkg_booking_id							 = 'temp';
			$transaction									 = DBUtil::beginTransaction();
			if ($model->validate())
			{
				try
				{
					$model->bkgUserInfo->bkg_user_last_updated_on	 = new CDbExpression('NOW()');
					$tmodel											 = Terms::model()->getText(1);
					$model->bkgTrail->bkg_tnc_id					 = $tmodel->tnc_id;
					$model->bkgTrail->bkg_tnc_time					 = new CDbExpression('NOW()');
					if (!$model->save())
					{
						throw new Exception("Failed to create booking", 101);
					}
					$model->bkgUserInfo->bui_bkg_id = $model->bkg_id;
					if (!$model->bkgUserInfo->save())
					{
						throw new Exception("Failed to create booking", 101);
					}
					$model->bkgInvoice->biv_bkg_id = $model->bkg_id;
					if (!$model->bkgInvoice->save())
					{
						throw new Exception("Failed to create booking", 101);
					}
					$model->bkgTrail->btr_bkg_id = $model->bkg_id;
					if (!$model->bkgTrail->save())
					{
						throw new Exception("Failed to create booking", 101);
					}
					$model->bkgTrack->btk_bkg_id = $model->bkg_id;
					if (!$model->bkgTrack->save())
					{
						throw new Exception("Failed to create booking", 101);
					}
					$model->bkgAddInfo->bad_bkg_id = $model->bkg_id;
					if (!$model->bkgAddInfo->save())
					{
						throw new Exception("Failed to create booking", 101);
					}
					$model->bkgPref->bpr_bkg_id = $model->bkg_id;
					if (!$model->bkgPref->save())
					{
						throw new Exception("Failed to create booking", 101);
					}
					$model->bkgPf->bpf_bkg_id = $model->bkg_id;
					if (!$model->bkgPf->updateFromQuote($arrQuot))
					{
						throw new Exception("Failed to create booking", 101);
					}

					$user_id = (Yii::app()->user->getId() > 0) ? Yii::app()->user->getId() : '';
					if ($user_id == '')
					{
						$userModel = Users::model()->linkUserByEmail($model->bkg_id, Booking::Platform_App);
						if ($userModel)
						{
							$user_id = $userModel->user_id;
						}
					}
					if ($user_id)
					{
						$model->bkgUserInfo->bkg_user_id = $user_id;
						$usrmodel						 = new Users();
						$usrmodel->resetScope()->findByPk($user_id);
					}
					if (!Yii::app()->user->isGuest)
					{
						$user							 = Yii::app()->user->loadUser();
						$model->bkgUserInfo->bkg_user_id = $user->user_id;
					}
					$booking_id								 = Booking::model()->generateBookingid($model);
					$model->bkg_booking_id					 = $booking_id;
					$model->bkgTrail->setPaymentExpiryTime($model->bkg_pickup_date);
					$isRealtedBooking						 = $model->findRelatedBooking($model->bkg_id);
					$model->bkgTrail->bkg_is_related_booking = ($isRealtedBooking) ? 1 : 0;
					$model->bkgTrail->bkg_create_user_type	 = $userInfo->userType;
					$model->bkgTrail->bkg_create_user_id	 = $model->bkgUserInfo->bkg_user_id;
					$model->bkgTrail->bkg_create_type		 = BookingTrail::CreateType_Self;
					if (!$model->save())
					{
						throw new Exception("Failed to create booking", 101);
					}
					if (!$model->bkgUserInfo->save())
					{
						throw new Exception("Failed to create booking", 101);
					}
					if (!$model->bkgTrail->save())
					{
						throw new Exception("Failed to create booking", 101);
					}
					Logger::create("11");
					$bookingCab						 = new BookingCab('matchtrip');
					$bookingCab->bcb_vendor_amount	 = $model->bkgInvoice->bkg_vendor_amount;
					$bookingCab->bcb_bkg_id1		 = $model->bkg_id;
					$bookingCab->save();
					$model->bkg_bcb_id				 = $bookingCab->bcb_id;
					$model->update();

					$bookingPref = BookingPref::model()->getByBooking($model->bkg_id);
					if ($bookingPref == '')
					{
						$bookingPref			 = new BookingPref();
						$bookingPref->bpr_bkg_id = $model->bkg_id;
						$bookingPref->insert();
					}
					$bkgPfModel						 = BookingPriceFactor::model()->find("bpf_bkg_id='$model->bkg_id'");
					$bkgPfModel->bkg_surge_applied	 = $routeRates->surgeFactorUsed;
					$bkgPfModel->update();
					/* Dynamic Surge Applied */

					if ($oldBkgId > 0)
					{
						$rModels = BookingRoute::model()->getAllByBkgid($oldBkgId);
						if ($rModels)
						{
							foreach ($rModels as $key => $rModel)
							{
								$rModel->delete();
							}
						}
					}
					foreach ($route as $rmodel)
					{
						$rmodel->brt_bkg_id	 = $model->bkg_id;
						$rmodel->brt_bcb_id	 = $bookingCab->bcb_id;
						$rmodel->save();
					}
					BookingRoute::model()->setBookingCabStartEndTime($bookingCab->bcb_id, $model->bkg_id);
					$bkgid		 = $model->bkg_id;
					$userInfo	 = UserInfo::getInstance();
					if ($model->bkgInvoice->bkg_promo1_id > 0 && $model->bkgInvoice->bkg_discount_amount > 0)
					{
						$desc	 = 'Promo ' . $model->bkgInvoice->bkg_promo1_code . ' applied successfully .(not confirmed)';
						$eventid = BookingLog::BOOKING_PROMO;
						BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, false, ['blg_ref_id' => BookingLog::REF_PROMO_APPLIED]);
					}
					BookingUser::model()->saveVerificationOtp($bkgid);
					$desc			 = "Booking created by user";
					$processedRoute	 = BookingLog::model()->logRouteProcessed($arrQuot, $bkgid);

					$desc	 .= " - $processedRoute";
					$eventid = BookingLog::BOOKING_CREATED;
					BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid);

					$leadModel->bkg_ref_booking_id	 = $model->bkg_id;
					$leadModel->bkg_status			 = 13;
					$leadModel->bkg_lead_source		 = 8; //'Incomplete booking',
					$leadModel->bkg_follow_up_status = 13;
					/* new paramter added */
					$leadModel->bkg_booking_id		 = $model->bkg_booking_id;
					$leadModel->bkg_booking_type	 = $model->bkg_booking_type;
					$leadModel->bkg_pickup_date		 = $model->bkg_pickup_date;
					$leadModel->bkg_pickup_time		 = date('H:i:s', strtotime($model->bkg_pickup_date));
					$leadModel->bkg_from_city_id	 = $model->bkg_from_city_id;
					$leadModel->bkg_to_city_id		 = $model->bkg_to_city_id;
					$leadModel->bkg_user_device		 = UserLog::model()->getDevice();
					//$leadModel->bkg_user_device      = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36';
					/* new paramter added */
					$leadModel->save();
					$desc							 = 'Converted to booking by user';
					$user_id						 = ($leadModel->bkg_user_id > 0) ? $leadModel->bkg_user_id : 0;
					$followStatus					 = 13;
					$eventid						 = 13;
					$refid							 = $model->bkg_id;
					LeadLog::model()->createLog($leadModel->bkg_id, $desc, $userInfo, '', $followStatus, $eventid, $refid);

					BookingTemp::model()->deactivateRelatedLeads($model->bkg_id);
					Booking::model()->deactivateRelatedBookings($model->bkg_id);

					DBUtil::commitTransaction($transaction);
				}
				catch (Exception $e)
				{
					$model->addError('bkg_id', $e->getMessage());
					DBUtil::rollbackTransaction($transaction);
				}
			}
			$success = !$model->hasErrors();
			$model1	 = clone $model;
			$model1->bkgInvoice->calculateConvenienceFee(0);
			$model1->bkgInvoice->calculateTotal();
			$model1->bkgInvoice->calculateVendorAmount();
			$credits = 0;
			if ($model->bkgUserInfo->bkg_user_id != '')
			{
				$credits = UserCredits::getApplicableCredits($model->bkgUserInfo->bkg_user_id, $model1->bkgInvoice->bkg_base_amount, true, $model->bkg_from_city_id, $model->bkg_to_city_id);
				$credits = $credits['credits'];
			}
			$total_day	 = $routeDuration->durationInWords;
			//$cab		 = VehicleTypes::model()->getCarByCarType($carType);
			$cab		 = SvcClassVhcCat::model()->getVctSvcList('string', '', $carType);
			if ($success)
			{
				$data = ['success' => true, 'data' => $model, 'data1' => $model1, 'days' => $total_day, 'cab' => $cab, 'credits' => $credits];
			}
			else
			{
				$data = ['success' => false, 'data' => $model, 'data1' => $model1, "errors" => $model->getErrors(), 'days' => $total_day, 'cab' => $cab, 'credits' => $credits];
			}
		}
		else
		{
			$data = ['success' => false, 'data' => $model, 'data1' => $model1, "errors" => $model->getErrors(), 'days' => $total_day, 'cab' => $cab, 'credits' => $credits];
		}
		return $data;
	}

	public function finalBook1($data1, $oldApp, $otp, $validate)
	{
		$model = Booking::model()->findByPk($data1['bkg_id']);
		if (!$model)
		{
			throw new CHttpException(400, 'Invalid data');
		}
		$model->attributes	 = $data1;
		$data				 = ['success' => false, 'data' => $model, "errors" => $model->getErrors()];
		$model->scenario	 = 'tnc';
		//$model->addCorporateCredit();
		//$model->calculateVendorAmount();
		$result				 = CActiveForm::validate($model);
		if ($result == '[]')
		{
			if ($otp == '')
			{
				if ($validate == 0)
				{
					$otp							 = rand(100100, 999999);
					$model->bkg_verification_code	 = $otp;
					$model->save();
					$msgCom							 = new smsWrapper();
					$username						 = $model->bkg_user_name . " " . $model->bkg_user_lname . "(" . $model->bkg_user_email . ")";
					$msgCom->linkBookingOTP($model->bkg_country_code, $model->bkg_contact_no, $otp, $username, $model->bkg_booking_id);
					$success						 = true;
				}
				else
				{
					$success = true;
				}
			}
			else
			{
				if ($validate == 0)
				{
					if ($otp == $model->bkg_verification_code)
					{
						$model->bkg_verification_code	 = '';
						$model->bkg_phone_verified		 = 1;
						$model->save();
						$success						 = true;
					}
					else
					{
						$success = false;
						$errors	 = "OTP Not Matched";
					}
				}
				else
				{
					$success = true;
				}
			}
			//$success = !$model->hasErrors();
			if ($success)
			{
				$data = ['success' => true, 'data' => $model];
			}
			else
			{
				$data = ['success' => false, 'data' => $model, "errors" => $errors];
			}
		}
		return $data;
	}

	public function actionGetleastdepart()
	{
		$dpVal			 = Yii::app()->request->getParam('dpVal');
		$tpVal			 = Yii::app()->request->getParam('tpVal');
		$duraVal1		 = Yii::app()->request->getParam('duraVal');
		$duraVal		 = (ceil(($duraVal1 * 1.1 ) / 30) ) * 30;
		$date			 = DateTimeFormat::DatePickerToDate($dpVal);
		$time			 = DateTimeFormat::TimeToSQLTime($tpVal);
		$bkg_pickup_date = $date . ' ' . $time;
		$st				 = date('Y-m-d H:i:s', strtotime("+$duraVal  minutes", strtotime($bkg_pickup_date)));
		$newDp			 = DateTimeFormat::DateTimeToDatePicker($st);
		$newTp			 = date('h:i A', strtotime($st));
		$data['newDp']	 = $newDp;
		$data['newTp']	 = $newTp;
		echo CJSON::encode($data);
		Yii::app()->end();
	}

	public function actionGetminpickupval()
	{
		$dpVal			 = Yii::app()->request->getParam('dpVal');
		$tpVal			 = Yii::app()->request->getParam('tpVal');
		$date			 = DateTimeFormat::DatePickerToDate($dpVal);
		$time			 = DateTimeFormat::TimeToSQLTime($tpVal);
		$bkg_pickup_date = $date . ' ' . $time;
		$diff			 = floor((strtotime($bkg_pickup_date) - time() ) / 3600);
		$valid			 = true;
		if ($diff < 4)
		{
			$valid = false;
		}
		$data['valid'] = $valid;

		echo CJSON::encode($data);
		Yii::app()->end();
	}

	public function actionReconfirm()
	{
		$bookingId	 = Yii::app()->request->getParam('bookingId');
		$type		 = Yii::app()->request->getParam('type');
		$hash		 = Yii::app()->request->getParam('hash');
		$model		 = Booking::model()->findByPk($bookingId);
		$defaultDate = date('Y-m-d H:i:s', strtotime('+4 hour'));
		$pdate		 = DateTimeFormat::DateTimeToDatePicker($defaultDate);
		$ptime		 = date('h:i A', strtotime('+1 hour'));
		if ($model->bkgPref->bkg_is_gozonow == 1)
		{
			$this->redirect('/gznow/' . $bookingId . '/' . $hash);
		}
		if (isset($_REQUEST['Booking']))
		{
			$model->attributes = $_REQUEST['Booking'];
			if (isset($type) && $type == 2)
			{
				$oldModel	 = clone $model;
				$userInfo	 = UserInfo::getInstance();
				$bkgId		 = Booking::model()->canBooking($model->bkg_id, $model->bkg_cancel_delete_reason, $model->bkg_cancel_id, $userInfo);
				if ($bkgId)
				{
					$bookingModel					 = Booking::model()->findByPk($bkgId);
//					if ($bookingModel != '' && $bookingModel->bkgUserInfo->bkg_user_id != '')
//					{
//						$notificationId	 = substr(round(microtime(true) * 1000), -5);
//						$payLoadData	 = ['bookingId' => $bookingModel->bkg_booking_id, 'EventCode' => Booking::CODE_USER_CANCEL];
//						$success		 = AppTokens::model()->notifyConsumer($bookingModel->bkgUserInfo->bkg_user_id, $payLoadData, $notificationId, "(" . $bookingModel->bkg_booking_id . ") " . $bookingModel->bkgFromCity->cty_name . " to " . $bookingModel->bkgToCity->cty_name . " on " . $bookingModel->bkg_pickup_date, $bookingModel->bkg_booking_id . " booking cancelled");
//					}
					$desc							 = "Booking Cancelled By User On Reconfirm.(Reason: " . $model->bkg_cancel_delete_reason . ")";
					$userInfo						 = UserInfo::model();
					$userInfo->userType				 = UserInfo::TYPE_CONSUMER;
					$userInfo->userId				 = $bookingModel->bkgUserInfo->bkg_user_id;
					$eventId						 = BookingLog::BOOKING_CANCELLED;
					$params['blg_booking_status']	 = $model->bkg_status;
					BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId, $oldModel);
					emailWrapper::bookingCancellationMail($bkgId);
					$msg							 = 'bookings cancelled less than 24 hours before the scheduled pickup time shall be subject to Cancellation & Refund Policy as laid down in our Terms & Conditions page on our website';
				}
			}
			elseif (isset($type) && $type == 3)
			{
				$rescheduleDate	 = $model->bkg_pickup_date_date;
				$rescheduleTime	 = $model->bkg_pickup_date_time;
				$rescheduleAddr	 = $model->bkg_pickup_address;
				/* @var $modelsub BookingSub */
				$modelsub		 = new BookingSub();

				$return = $modelsub->setReschedule($bookingId, $rescheduleDate, $rescheduleTime, $rescheduleAddr);
				if ($return == true)
				{
					$msg = 'OK we will reschedule at no rescheduling charge assuming the rescheduling is being done 24 hours before scheduled trip start. You will be liable for fare differences and charges due to change in pickup date, address and/or time.  When would you to reschedule your pickup time to';
				}
			}
		}
		else
		{
			if (isset($type) && $type == 1)
			{
				$return = Booking::model()->setReconfirm($bookingId);
				if ($return == true)
				{
					$msg = 'Great! We are reconfirming your trip. Thank you';
				}
			}
		}
		$this->render('reconfirm', array('model'			 => $model,
			'pdate'			 => $pdate,
			'ptime'			 => $ptime,
			'messsage'		 => $msg,
			'bookingcode'	 => $model->bkg_booking_id));
	}

	public function actionUnvFollowupCS()
	{
		$bkgId		 = Yii::app()->request->getParam('id');
		$hash		 = Yii::app()->request->getParam('hash');
		$pHash		 = Yii::app()->request->getParam('pHash');
		$eHash		 = Yii::app()->request->getParam('eHash');
		$type		 = Yii::app()->request->getParam('type');
		$success	 = false;
		$message	 = "";
		$leadModel	 = LeadFollowup::model()->getByBkgId($bkgId);
		if (!$leadModel)
		{
			$leadModel = new LeadFollowup();
		}
		try
		{
			/* @var $webUser CWebUser */
			$webUser			 = Yii::app()->user;
			$userInfo			 = new UserInfo();
			$userInfo->userType	 = UserInfo::TYPE_CONSUMER;
			$model				 = Booking::model()->findByPk($bkgId);
			$webUser->setId($model->bkgUserInfo->bkg_user_id);
			if (isset($_POST['bkg_id']) && $_POST['bkg_id'] > 0)
			{
				$bkgId		 = trim($_POST['bkg_id']);
				$reasonText	 = "No, I am not interested";
				$resultBkgId = Booking::model()->canBooking($model->bkg_id, $reasonText, 4, $userInfo);
				if ($resultBkgId > 0)
				{
					BookingLog::model()->createLog($model->bkg_id, $reasonText, $userInfo, BookingLog::BOOKING_CANCELLED, $model);
					$success = true;
					$message = "You have successfully cancelled this booking.";
				}
			}
			else
			{
				if ($hash == 1)
				{
					/* @var $model Booking */
					$model = Booking::model()->findByPk($bkgId);
					if ($model->bkg_status != 1)
					{
						throw new Exception('This link has expired.', 410);
					}
					$resultLoc = BookingTrail::updateFinalFollowupCountTime($bkgId, $userInfo);
					if ($resultLoc['success'] == false)
					{
						throw new Exception(json_encode($resultLoc['errors']));
					}
					if ($eHash != '' && $eHash != null)
					{
						BookingUser::model()->isEmailVerify($bkgId, $eHash);
						$paymentUrl = BookingUser::getPaymentLinkByEmail($bkgId);
					}
					if ($pHash != '' && $pHash != null)
					{
						BookingUser::model()->isPhoneVerify($bkgId, $pHash);
						$paymentUrl = BookingUser::getPaymentLinkByPhone($bkgId);
					}
					$success = true;
				}
				elseif ($hash == 2)
				{
					/* @var $model BookingTemp */
					$model = BookingTemp::model()->findByPk($bkgId);
					if (!$model)
					{
						throw new Exception('This link has expired.', 410);
					}
					$success = true;
				}
			}
			$resultSet = ['success'	 => $success,
				'message'	 => $message,
				'model'		 => $model,
				'leadModel'	 => $leadModel,
				'paymentUrl' => $paymentUrl];
		}
		catch (Exception $ex)
		{
			$success	 = false;
			$errors		 = $ex->getMessage();
			$errorCode	 = $ex->getCode();
			$resultSet	 = ['success'	 => $success,
				'errors'	 => $errors,
				'errorCode'	 => $errorCode,
				'model'		 => $model,
				'leadModel'	 => $leadModel,
				'paymentUrl' => $paymentUrl];
		}

		$this->render('unvfollowupcs', $resultSet);
	}

	public function actionUnverified()
	{
		$bkgId				 = Yii::app()->request->getParam('id');
		$hash				 = Yii::app()->request->getParam('hash');
		$pHash				 = Yii::app()->request->getParam('pHash');
		$eHash				 = Yii::app()->request->getParam('eHash');
		$type				 = Yii::app()->request->getParam('type');
		$reasonId			 = Yii::app()->request->getParam('rid');
		/* @var $webUser CWebUser */
		$webUser			 = Yii::app()->user;
		$userInfo			 = new UserInfo();
		$userInfo->userType	 = UserInfo::TYPE_CONSUMER;
		if ($hash != 1 && $hash != 2 && $hash != '')
		{
			if ($hash != '' && $pHash != '')
			{
				$this->redirect(array('u/' . $bkgId . '/1/p/' . $pHash));
			}
			elseif ($hash != '' && $eHash != '')
			{
				$this->redirect(array('u/' . $bkgId . '/1/e/' . $eHash));
			}
		}
		$userId = null;
		if ($hash == 1)
		{
			$model	 = Booking::model()->findByPk($bkgId);
			$userId	 = $model->bkgUserInfo->bkg_user_id;
		}
		elseif ($hash == 2)
		{
			$model	 = BookingTemp::model()->findByPk($bkgId);
			$userId	 = $model->bkg_user_id;
		}

		$bkgGroup = ($hash > 0) ? $hash : null;

		$model2	 = new LeadFollowup();
		$model3	 = LeadFollowup::model()->getByBkgId($bkgId);
		if (isset($_REQUEST['LeadFollowup']))
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$arr					 = Yii::app()->request->getParam('LeadFollowup');
				$userId					 = Yii::app()->request->getParam('userid');
				$model2->attributes		 = $arr;
				$model2->lfu_phone_no	 = $arr['lfu_phone_no'];
				$model2->lfu_ref_id		 = $bkgId;
				$model2->lfu_type		 = $type;
				$model2->lfu_ref_type	 = $bkgGroup;
				if ($type == 4)
				{
					$model2->lfu_followup = trim($arr['lfu_followup']);
				}
				$model2->lfu_status	 = 1;
				$model2->scenario	 = 'insertLead';
				if ($model2->validate() && $model2->save())
				{
					if ($model2->lfu_type > 0)
					{
						switch ($model2->lfu_type)
						{
							case '1':
								$desc			 = "Customer says price was high. Comments ---- " . $model2->lfu_cmt;
								LeadFollowup::model()->updateLeadForPriceHigh($bkgId, $bkgGroup, $desc, $userInfo);
								break;
							case '2':
								$dateRequested	 = date('d/m/Y h:i A', strtotime($model2->lfu_date));
								$desc			 = "I was just looking, will book later at " . $dateRequested;
								$isTentative	 = ($model2->lfu_bkg_tentative_booking > 0) ? 1 : 0;
								LeadFollowup::model()->updateLeadForJustLooking($bkgId, $bkgGroup, $dateRequested, $userInfo);
								break;
							case '3':
								$desc			 = "Customer has comments ------- " . $model2->lfu_tellus;
								LeadFollowup::model()->updateLeadForOther($bkgId, $bkgGroup, $desc, $userInfo);
								break;
							case '4':
								LeadFollowup::model()->updateLeadForCallMe($bkgId, $bkgGroup, $model2->lfu_followup, $userInfo);
								$desc			 = "Call me please. " . (isset($model2->lfu_followup) && $model2->lfu_followup != '') ? $model2->lfu_followup : null;
								break;
						}
						$row					 = array();
						$model					 = $bkgGroup == 1 ? Booking::model()->findByPk($bkgId) : BookingTemp::model()->findByPk($bkgId);
						$row['bkg_contact_no']	 = $bkgGroup == 1 ? $model->bkgUserInfo->bkg_contact_no : $model->bkg_contact_no;

						$row['bkg_user_id']	 = $bkgGroup == 1 ? $model->bkgUserInfo->bkg_user_id : $userId;
						$row['type']		 = $bkgGroup == 1 ? 2 : 1;
						$row['bkg_id']		 = $bkgId;
						$row['desc']		 = (isset($model2->lfu_followup) && $model2->lfu_followup != '') ? $model2->lfu_followup : null;
						$row['desc']		 = $desc;
						$row['csrRank']		 = 100;  // as per discussion with Abhisheik sir 
						ServiceCallQueue::updateLead($row);
						DBUtil::commitTransaction($transaction);
						$this->redirect(array('unvthankyou', 'id' => $bkgId));
					}
				}
				else
				{
					$message = "Validate errors : " . json_decode($model2->getErrors());
					throw new Exception($message);
				}
			}
			catch (Exception $e)
			{
				$model2->addError("bkg_id", $e->getMessage());
				DBUtil::rollbackTransaction($transaction);
			}
		}
		else
		{
			$bkgGroup = null;
			if ($hash == 1)
			{
				if ($model->bkg_status != 1 && $model->bkg_status != 15)
				{
					throw new Exception('Sorry this link has been expired.', 410);
				}
				$resultLoc = BookingTrail::updateUnverifiedCountTime($bkgId, $userInfo);
				if ($resultLoc['success'] == false)
				{
					throw new Exception(json_encode($resultLoc['errors']));
				}
				if ($eHash != '' && $eHash != null)
				{
					BookingUser::model()->isEmailVerify($bkgId, $eHash);
				}
				if ($pHash != '' && $pHash != null)
				{
					BookingUser::model()->isPhoneVerify($bkgId, $pHash);
				}
				//$webUser->setId($model->bkgUserInfo->bkg_user_id);
				$bkgGroup = $hash;
			}
			elseif ($hash == 2)
			{
				if (!$model)
				{
					throw new Exception('Sorry this link has been expired.', 410);
				}
				$resultLoc = BookingTemp::updateLeadCountTime($bkgId, $userInfo);
				if ($resultLoc['success'] == false)
				{
					throw new Exception(json_encode($resultLoc['errors']));
				}
				//$webUser->setId($model->bkg_user_id);
				$bkgGroup = $hash;
			}
		}



		$this->render('unvconfirm', array('model'			 => $model,
			'model2'		 => $model2,
			'model3'		 => $model3, 'userId'		 => $userId,
			'bookingcode'	 => $model->bkg_booking_id,
			'id'			 => $bkgId,
			'type'			 => $type, 'reasonId'		 => $reasonId));
	}

	public function actionUnvThankyou()
	{
		$bkgId	 = Yii::app()->request->getParam('id');
		$model	 = LeadFollowup::model()->getByBkgId($bkgId);
		$this->render('unvthankyou', array('model'	 => $model,
			'type'	 => $type));
	}

	public function actionReconfirmSubmit()
	{
		echo "<pre>";
		$arr			 = print_r($_POST);
		$type			 = Yii::app()->request->getParam('type');
		$cancelId		 = Yii::app()->request->getParam('Booking_bkg_cancel_id');
		$cancelReason	 = Yii::app()->request->getParam('Booking_bkg_cancel_delete_reason');
		$date			 = Yii::app()->request->getParam('Booking_bkg_pickup_date_date');
		$time			 = Yii::app()->request->getParam('Booking_bkg_pickup_date_date');
		$address		 = Yii::app()->request->getParam('Booking_bkg_pickup_date_date');
		if (isset($type) && $type <> '')
		{
			switch ($type)
			{
				case '1':
					break;
				case '2':
					break;
				case '3':
					$date	 = Yii::app()->request->getParam('bkg_pickup_date_date');
					$time	 = Yii::app()->request->getParam('bkg_pickup_date_time');
					$address = Yii::app()->request->getParam('bkg_pickup_address');
					break;
			}
		}
	}

	public function extraAdditional($data, $routes)
	{
		$model							 = Booking::model()->findbyPk($data['bkg_id']);
		$model->attributes				 = $data;
		$model->bkgAddInfo->attributes	 = $data;
		$model->bkgPref->attributes		 = $data;
		$countRoutes					 = count($routes);
		//$model->bkg_pickup_pincode			 = $routes[0]->pickup_pincode;
		//$model->bkg_drop_pincode			 = $routes[($countRoutes - 1)]->drop_pincode;
		$model->bkgUserInfo->scenario	 = 'stepMobile3';
		$result							 = CActiveForm::validate($model->bkgUserInfo);
		$data							 = ['success' => false, 'errors' => $result];
		if ($result == '[]')
		{
			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				$splRemark = 'Carrier Requested for Rs.150';
				if ($model->bkgAddInfo->bkg_spl_req_carrier == 1 && !strstr($model->bkgInvoice->bkg_additional_charge_remark, $splRemark))
				{
					$model->bkgInvoice->bkg_additional_charge		 = $model->bkgInvoice->bkg_additional_charge + 150;
					$model->bkgInvoice->bkg_additional_charge_remark = ($model->bkgInvoice->bkg_additional_charge_remark == '') ? $splRemark : $model->bkginvoice->bkg_additional_charge_remark . ', ' . $splRemark;
					//	$model->bkgInvoice->calculateTotal();
					$model->bkgInvoice->populateAmount(true, false, true, true, $model->bkg_agent_id);
					//	$model->bkgInvoice->calculateVendorAmount();
					$userInfo										 = UserInfo::getInstance();
					$eventId										 = BookingLog::REMARKS_ADDED;
					$remark											 = $splRemark;
					BookingLog::model()->createLog($model->bkg_id, $remark, $userInfo, $eventId);
					$model->sendConfirmation($userInfo->userType);
				}
				$routeModels = BookingRoute::model()->getAllByBkgid($model->bkg_id);
				foreach ($routes as $key => $val)
				{
					$routeModels[$key]->brt_to_pincode	 = $val->drop_pincode;
					$routeModels[$key]->brt_from_pincode = $val->pickup_pincode;
					$routeModels[$key]->scenario		 = 'rtupdate';
					$routeModels[$key]->save();
				}
				$model->bkgUserInfo->bkg_user_last_updated_on = new CDbExpression('NOW()');
				if (!$model->save())
				{
					throw new Exception("Failed to save data", 101);
				}
				if (!$model->bkgUserInfo->save())
				{
					throw new Exception("Failed to save data", 101);
				}
				if (!$model->bkgInvoice->save())
				{
					throw new Exception("Failed to save data", 101);
				}
				if (!$model->bkgTrail->save())
				{
					throw new Exception("Failed to save data", 101);
				}
				if (!$model->bkgTrack->save())
				{
					throw new Exception("Failed to save data", 101);
				}
				if (!$model->bkgAddInfo->save())
				{
					throw new Exception("Failed to save data", 101);
				}
				if (!$model->bkgPref->save())
				{
					throw new Exception("Failed to save data", 101);
				}
				$transaction->commit();
			}
			catch (Exception $e)
			{
				$model->addError('bkg_id', $e->getMessage());
				$transaction->rollback();
			}
			$success = false;
			if (!$model->hasErrors())
			{
				if (!$model->bkgUserInfo->hasErrors())
				{
					if (!$model->bkgInvoice->hasErrors())
					{
						if (!$model->bkgTrail->hasErrors())
						{
							if (!$model->bkgTrack->hasErrors())
							{
								if (!$model->bkgAddInfo->hasErrors())
								{
									if (!$model->bkgPref->hasErrors())
									{
										$success = true;
									}
								}
							}
						}
					}
				}
			}
			if ($success)
			{
				$data = ['success' => true];
			}
			else
			{
				$data = ['success' => false, 'errors' => $model->getErrors()];
			}
		}
		else
		{
			$data = ['success' => false, 'errors' => $model->getErrors()];
		}
		return $data;
	}

	public function actionMff111()
	{
		$model = new Booking('new');
		if (!Yii::app()->user->isGuest)
		{
			$user					 = Yii::app()->user->loadUser();
			$model->bkg_user_id		 = $user->user_id;
			$model->bkg_user_name	 = $user->usr_name;

			$model->bkg_user_lname	 = $user->usr_lname;
			$model->bkg_user_email	 = $user->usr_email;
			$model->bkg_contact_no	 = $user->usr_mobile;
			$model->bkg_country_code = $user->usr_country_code;
		}




		$model->bkg_pickup_address	 = '';
		$model->bkg_drop_address	 = '';
		if (isset($_REQUEST['Booking']))
		{
			$model->scenario		 = 'new';
			$reqData				 = Yii::app()->request->getParam('Booking');
			$model->bkg_booking_type = 1;

			$model->attributes	 = $reqData;
			$model->bkg_platform = Booking::Platform_User;

			$user_id = (Yii::app()->user->getId() > 0) ? Yii::app()->user->getId() : '';

			if ($user_id)
			{
				$model->bkg_user_id	 = $user_id;
				$usrmodel			 = new Users();
				$usrmodel->resetScope()->findByPk($user_id);
			}



			$model->bkg_user_ip				 = \Filter::getUserIP();
			$cityinfo						 = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
			$model->bkg_user_city			 = $cityinfo['city'];
			$model->bkg_user_country		 = $cityinfo['country'];
			$model->bkg_user_device			 = UserLog::model()->getDevice();
			$model->bkg_user_last_updated_on = new CDbExpression('NOW()');
			$tmodel							 = Terms::model()->getText(1);
			$model->bkg_tnc_id				 = $tmodel->tnc_id;
			$model->bkg_tnc_time			 = new CDbExpression('NOW()');
			$model->bkg_booking_id			 = 'temp';

			$transaction = Yii::app()->db->beginTransaction();
			$result		 = CActiveForm::validate($model, null, false);

			if ($result == '[]')
			{
				try
				{
					if (!$model->save())
					{
						throw new Exception("Failed to create booking", 101);
					}
					$booking_id				 = Booking::model()->generateBookingid($model);
					$model->bkg_booking_id	 = $booking_id;
					$brtModels				 = [];

					$brtModel						 = new BookingRoute();
					$brtModel->brt_from_city_id		 = $model->bkg_from_city_id;
					$brtModel->brt_to_city_id		 = $model->bkg_to_city_id;
					$brtModel->brt_pickup_date_date	 = $model->bkg_pickup_date_date;
					$brtModel->brt_pickup_date_time	 = $model->bkg_pickup_date_time;
					$brtModel->brt_bkg_id			 = $model->bkg_id;
					$brtModel->save();
					$brtModels[]					 = $brtModel;
					$model->bookingRoutes			 = $brtModels;
					$model->save();
					$bkgid							 = $model->bkg_id;

					$desc		 = "Booking created by user.";
					$eventid	 = BookingLog::BOOKING_CREATED;
					$userInfo	 = UserInfo::getInstance();
					BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid);

					$GLOBALS["bkg_id"]	 = $model->bkg_id;
					$model->hash		 = Yii::app()->shortHash->hash($model->bkg_id);
					$GLOBALS["hash"]	 = Yii::app()->shortHash->hash($model->bkg_id);
					$transaction->commit();
					//  $this->redirect('/booking/additionaldetail');
					$this->forward("booking/new", true);
				}
				catch (Exception $e)
				{
					$transaction->rollback();
				}
			}
			else
			{
				$transaction->rollback();
				// $return = ['success' => false, 'errors' => CJSON::decode($result)];
			}
//            if (Yii::app()->request->isAjaxRequest) {
//                echo CJSON::encode($return);
//                Yii::app()->end();
//            }
		}
		$this->render('mff', array('model' => $model));
	}

	public function actionRedirect($route)
	{
		$qry = $_SERVER['QUERY_STRING'];
		$qry = ($qry != "") ? "?" . $qry : "";
		$this->redirect('book-taxi/' . $route . $qry, true, 301);
	}

	public function actionMff()
	{
		$this->checkV2Theme();
		$model			 = new BookingTemp('new');
		$this->pageTitle = "Travel to Magnetic Fields 2018, Alsisar Mahal in Alsisar, Rajasthan";
		setcookie('gozo_mff', 'mff', time() + (60 * 60 * 12), "/");
		$this->render('mff', array('model' => $model));
	}

	public function actionSunburn()
	{
		$this->checkV2Theme();
		$model			 = new BookingTemp('new');
		$this->pageTitle = "Book your cabs for Sunburn 2018, Pune";
		setcookie('gozo_sunburn', 'sunburn', time() + (60 * 60 * 12), "/");
		$this->render('sunburn', array('model' => $model));
	}

	public function actionSupersonic()
	{
		$this->checkV2Theme();
		$model			 = new BookingTemp('new');
		$this->pageTitle = "Book your cabs for Vh1 SUPERSONIC 2019";
		setcookie('gozo_supersonic', 'supersonic', time() + (60 * 60 * 12), "/");
		$this->render('supersonic', array('model' => $model));
	}

	public function actionSulafest()
	{
		$this->checkV2Theme();
		$model			 = new BookingTemp('new');
		$this->pageTitle = "Book outstation cabs for Sula Fest 2019";
		setcookie('gozo_sulafest', 'sulafest', time() + (60 * 60 * 12), "/");
		$this->render('sulafest', array('model' => $model));
	}

	public function actionNh7weekender()
	{
		$this->checkV2Theme();
		$model			 = new BookingTemp('new');
		$this->pageTitle = "nh7-weekender";
		setcookie('gozo_nh7-weekender', 'nh7-weekender', time() + (60 * 60 * 12), "/");
		$this->render('nh7-weekender', array('model' => $model));
	}

	public function actionSunsplash()
	{
		$this->checkV2Theme();
		$model			 = new BookingTemp('new');
		$this->pageTitle = "Book your cabs for Goa Sunsplash 2019";
		setcookie('gozo_sunsplash', 'sunsplash', time() + (60 * 60 * 12), "/");
		$this->render('sunsplash', array('model' => $model));
	}

	public function actionComiccon()
	{
		$this->checkV2Theme();
		$model			 = new BookingTemp('new');
		$this->pageTitle = " Go Gozo to Mumbai Comic Con 2018";
		setcookie('gozo_comiccon_mumbai', 'comiccon_mumbai', time() + (60 * 60 * 12), "/");
		$this->render('comic_con', array('model' => $model));
	}

	public function actionMoodIndigo()
	{
		$this->checkV2Theme();
		$model			 = new BookingTemp('new');
		$this->pageTitle = " Book outstation cabs for Mood Indigo 2018";
		setcookie('gozo_moodindigo_mumbai', 'moodindigo_mumbai', time() + (60 * 60 * 12), "/");
		$this->render('mood_indigo', array('model' => $model));
	}

	public function actionKumbh()
	{
		$this->checkV2Theme();
		$model			 = new BookingTemp('new');
		$this->pageTitle = " Book outstation cabs for Kumbh 2019";
		setcookie('gozo_kumbh_allahabad', 'kumbh_allahabad', time() + (60 * 60 * 12), "/");
		$this->render('kumbh', array('model' => $model));
	}

	public function actionAtirudram2017()
	{
		$this->checkV2Theme();
		$model			 = new BookingTemp('new');
		$this->pageTitle = "Atirudram2017: Gozocabs. Outstation cab's across India. Great service. Guaranteed prices.";
		$this->render('atirudram2017', array('model' => $model));
	}

	public function actionGetrut()
	{
		$fcity	 = Yii::app()->request->getParam('fct');
		$tcity	 = Yii::app()->request->getParam('tct');
		$rut	 = Yii::app()->request->getParam('rtid');

		//$bkgtype = Yii::app()->request->getParam('bkgtype', 1);
		$rtmodel		 = Rate::model()->getCabRatebyCities($fcity, $tcity);
		$rutid			 = $rtmodel[0]['rut_id'];
		$rtInfo			 = $rtmodel[0];
		$from_address	 = $rtInfo['from_address'];
		$to_address		 = $rtInfo['to_address'];
		if ($rutid == $rut)
		{
			$rutname = $rtmodel[0]['from_city_name'] . ' to ' . $rtmodel[0]['to_city_name'];

			$rtArr = [];
			foreach ($rtmodel as $v)
			{
				$rtArr[$v['cab_type_id']] = $v['rtamount'];
			}
		}
		echo CJSON::encode(['rutname'		 => $rutname,
			'rtArr'			 => $rtArr,
			'from_address'	 => $from_address,
			'to_address'	 => $to_address
		]);
	}

	public function promoService($flag = 0, $val = '')
	{
		//new promoservice
		$result			 = false;
		$bkgid			 = Yii::app()->request->getParam('bkg_id');
		$bkgpcode		 = ($val == '') ? (Yii::app()->request->getParam('bkg_pcode')) : $val;
		$creditapplied	 = Yii::app()->request->getParam('credit_amount', 0);
		$hash			 = Yii::app()->request->getParam('bkghash');
		$web			 = Yii::app()->request->getParam('web', 0);
		$flag			 = ($flag == 0) ? (Yii::app()->request->getParam('flag')) : $flag;
		$coinPromoStatus = Yii::app()->request->getParam('coinPromoStatus', 0);
		$isWalletUsed	 = Yii::app()->request->getParam('isWalletUsed', 0);
		$amtWalletUsed	 = Yii::app()->request->getParam('amtWalletUsed', 0);
		$prm_id			 = Yii::app()->request->getParam('prm_id', 0);
		//	$creditapplied		 = ($creditapplied == 0 || $creditapplied == '') ? (Yii::app()->request->getParam('amount', 0)) : $creditapplied;


		$model = Booking::model()->findbyPk($bkgid);
		if (!$model && $model->bkg_status != 1)
		{
			throw new CHttpException(400, 'Invalid data');
		}
		if ($model->bkgTrail->bkg_platform != 3)
		{
			if ($bkgid != Yii::app()->shortHash->unHash($hash))
			{
				throw new CHttpException(400, 'Invalid data');
			}
		}


		//remove promo if already applied
		if ($flag == 1)
		{
			$creditapplied = ($creditapplied > 0) ? $creditapplied : 0;
			$this->promoRemoveServiceData($bkgid, $hash, $creditapplied);
		}
		//remove coin if already applied
		if ($coinPromoStatus == 1)
		{
			$this->gozoCoinsRemoveData($bkgid, $hash, $web);
		}

		if ($prm_id > 0)
		{
			$promoModel1 = Promos::model()->findByPk($prm_id);
			$bkgpcode	 = $promoModel1->prm_code;
		}

		//Apply Promo
		if ($model->bkgInvoice->bkg_promo1_code != '' || $bkgpcode != '')
		{
			$credits		 = UserCredits::getApplicableCredits($model->bkgUserInfo->bkg_user_id, $model->bkgInvoice->bkg_base_amount, false, $model->bkg_from_city_id, $model->bkg_to_city_id);
			$creditVal		 = $credits['credits'];
			$refundCredits	 = $credits['refundCredits'];
			if ($refundCredits == 0 && $creditapplied > 0 && $model->bkgInvoice->bkg_promo1_code != '' && $bkgpcode == '')
			{
				goto next;
			}
			$bkgpcode	 = ($bkgpcode == '') ? $model->bkgInvoice->bkg_promo1_code : $bkgpcode;
			$returnSet	 = Promos::usePromo($bkgpcode, $model->bkg_id);
			if ($returnSet->isSuccess())
			{
				$isPromoApplied	 = true;
				$msg			 = $returnSet->getData()['message'];
				$prmType		 = $returnSet->getData()['promoType'];
				$dueAmount		 = $returnSet->getData()['dueAmt'];
				$prmCode		 = $returnSet->getData()['promoCode'];
				$promoDiscount	 = $returnSet->getData()['promoDiscount'];
				$promoDesc		 = $returnSet->getData()['promoDesc'];
				$serviceTax		 = $returnSet->getData()['gst'];
				$DA				 = $returnSet->getData()['da'];
				$totAmt			 = $returnSet->getData()['totAmt'];
				$baseAmt		 = $returnSet->getData()['baseAmt'];
				$minPay			 = $returnSet->getData()['minPay'];
				$promoId		 = $returnSet->getData()['promoId'];
				$usePromoCredit	 = ($isPromoApplied) ? false : true;
				$credits		 = UserCredits::getApplicableCredits($model->bkgUserInfo->bkg_user_id, $baseAmt, $usePromoCredit, $model->bkg_from_city_id, $model->bkg_to_city_id);
				$creditVal		 = $credits['credits'];
				if ($creditVal > 0)
				{
					$isCredit		 = true;
					$isRefundCredits = true;
					$refundCredits	 = $creditVal;
				}
				$isPromo			 = false;
				$isDiscAfterPayment	 = $returnSet->getData()['isDiscAfterPayment'];
				$result				 = true;
			}
		}
		next:
		//Apply Gozocoins
		if ($creditapplied > 0)
		{
			$usePromoCredit							 = ($isPromoApplied) ? false : true;
			$credits								 = UserCredits::getApplicableCredits($model->bkgUserInfo->bkg_user_id, $model->bkgInvoice->bkg_base_amount, $usePromoCredit, $model->bkg_from_city_id, $model->bkg_to_city_id);
			$creditVal								 = $credits['credits'];
			$refundCredits							 = $credits['refundCredits'];
			$dueAmount								 = ($dueAmount == '') ? $model->bkgInvoice->bkg_due_amount : $dueAmount;
			$model->refresh();
			$model->bkgInvoice->bkg_discount_amount	 = ($isPromoApplied) ? $promoDiscount : 0;
			$model->bkgInvoice->bkg_credits_used	 = min([$creditapplied, $creditVal, $dueAmount]);
			$model->bkgInvoice->populateAmount();
			$dueAmount								 = $model->bkgInvoice->bkg_due_amount;
			$serviceTax								 = $model->bkgInvoice->bkg_service_tax;
			$DA										 = $model->bkgInvoice->bkg_driver_allowance_amount;
			$totAmt									 = $model->bkgInvoice->bkg_total_amount;
			$baseAmt								 = $model->bkgInvoice->bkg_base_amount;
			$minPay									 = $model->bkgInvoice->calculateMinPayment();
			$creditapplied							 = $model->bkgInvoice->bkg_credits_used;
			$isGozoCoinsApplied						 = true;
			$isCredit								 = false;
			$credit									 = true;
			$result									 = true;
			$isPromo								 = ($isPromoApplied) ? false : (($refundCredits > 0) ? true : false);
			$userCreditStatus						 = UserCredits::model()->getGozocoinsUsesStatus($model->bkgUserInfo->bkg_user_id);
			if ($creditVal == 0)
			{
				$isGozoCoinsApplied = false;
			}
			if ($creditVal > 0)
			{
				$userInfo				 = UserInfo::model();
				$userInfo->userType		 = UserInfo::TYPE_CONSUMER;
				$userInfo->userId		 = $model->bkgUserInfo->bkg_user_id;
				$eventid				 = BookingLog::BOOKING_PROMO;
				$params['blg_ref_id']	 = BookingLog::REF_PROMO_GOZOCOINS_APPLIED;
				if ($creditapplied > 0)
				{
					BookingLog::model()->createLog($model->bkg_id, "Gozocoin " . $creditapplied . " applied successfully.", $userInfo, $eventid, false, $params);
				}
			}
		}

		//Apply Wallet
		if ($amtWalletUsed > 0 && $isWalletUsed == 1 && UserInfo::getUserId() > 0)
		{
			$totWalletBalance						 = UserWallet::getBalance(UserInfo::getUserId());
			$totWalletBalance						 = ($model->bkgInvoice->bkg_due_amount < $totWalletBalance) ? $model->bkgInvoice->bkg_due_amount : $totWalletBalance;
			$amtWalletUsed							 = ($amtWalletUsed > $totWalletBalance) ? $totWalletBalance : $amtWalletUsed;
			$model->bkgInvoice->bkg_discount_amount	 = ($isPromoApplied) ? $promoDiscount : 0;
			$model->bkgInvoice->bkg_credits_used	 = ($isGozoCoinsApplied) ? $creditapplied : 0;
			$model->bkgInvoice->bkg_advance_amount	 += $amtWalletUsed;
			$model->bkgInvoice->calculateTotal();
			$minPay									 = $model->bkgInvoice->calculateMinPayment();
			$dueAmount								 = $model->bkgInvoice->bkg_due_amount;
			$isWalletApplied						 = true;
			$result									 = true;
		}

		//app
		$data = [
			'result'					 => $result, //a
			'due_amount'				 => $dueAmount, //a
			'service_tax'				 => $serviceTax, //a
			'driver_allowance'			 => $DA, //a
			'total_amount'				 => $totAmt, //a
			'base_amount'				 => $baseAmt, //a
			'convFee'					 => 0, //a
			'amountWithConvFee'			 => $dueAmount, //a
			'minPayable'				 => $minPay, //a
			'apply'						 => $result, //c
			'newtotal'					 => $totAmt, //c
			'creditStatus'				 => $userCreditStatus, //c
			'refundCredits'				 => $refundCredits, //c
			'credit'					 => $credit, //c
			'credits_used'				 => $creditapplied, //c
			'totCredits'				 => $creditVal, //c
			'creditused'				 => $creditapplied, //c
			'coinPromoStatus'			 => $coinPromoStatus, //c
			'isCredit'					 => $isCredit, //c
			'isGozoCoinsApplied'		 => $isGozoCoinsApplied, //c
			'isRefundCredits'			 => $isRefundCredits,
			'isPromoCodeUsed'			 => $isPromoApplied, //p
			'service_tax_with_conv_fee'	 => $serviceTax, //p
			'isPromo'					 => $isPromo, //p
			'promo'						 => $isPromoApplied, //p
			'isPromoApplied'			 => $isPromoApplied, //p
			'message'					 => $msg, //p
			'promo_code'				 => $prmCode, //p
			'promo_type'				 => $prmType, //p
			'discount'					 => $promoDiscount, //p
			'isAdvDiscount'				 => $isDiscAfterPayment, //p
			'promo_desc'				 => $promoDesc, //p
			'promo_id'					 => $promoId, //p
			'amtWalletUsed'				 => $amtWalletUsed, //w
			'isWalletApplied'			 => $isWalletApplied, //w
		];
		return $data;
	}

	public function creditService($val = 0, $ispromoApp = 0)
	{
		//new promoservice
		$bkgid			 = Yii::app()->request->getParam('bkg_id');
		$creditVal		 = ($val == 0) ? (Yii::app()->request->getParam('amount', 0)) : $val;
		$hash			 = Yii::app()->request->getParam('bkghash');
		$web			 = Yii::app()->request->getParam('web', 0);
		$coinPromoStatus = Yii::app()->request->getParam('coinPromoStatus', 0);
		$flag			 = Yii::app()->request->getParam('flag');
		$isWalletUsed	 = Yii::app()->request->getParam('isWalletUsed', 0);
		$amtWalletUsed	 = Yii::app()->request->getParam('amtWalletUsed', 0);

		if ($flag == 1)
		{
			$this->promoRemoveServiceData($bkgid, $hash, $creditVal);
		}
		$isAdvDiscount		 = false;
		$userCreditStatus	 = 0;
		/* @var $model Booking */
		$model				 = Booking::model()->findbyPk($bkgid);
		if (!$model)
		{
			throw new CHttpException(400, 'Invalid data');
		}
		if ($model->bkgTrail->bkg_platform != 3)
		{
			if ($bkgid != Yii::app()->shortHash->unHash($hash))
			{
				throw new CHttpException(400, 'Invalid data');
			}
		}
		if ($model != '' && Yii::app()->user->getId() != '' && $creditVal > 0)
		{
			// credits applied only to show the user  ,credits used will be saved and deducted from user credits after booking confirm  or paynow button clicked
			$transModel1 = true;
		}
		if ($transModel1)
		{
			//data without COD
			$model2 = clone $model;
			//advdisc auto promo applied
			if ($model2->bkgInvoice->bkg_promo1_id > 0)
			{
				//$promoModel = Promos::model()->getByCode($model2->bkgInvoice->bkg_promo1_code);\
				$promoModel = Promos::model()->findByPk($model2->bkgInvoice->bkg_promo1_id);
				if (!$promoModel)
				{
					throw new Exception('Invalid Promo code');
				}
				$promoModel->promoCode	 = $model2->bkgInvoice->bkg_promo1_code;
				$promoModel->totalAmount = $model2->bkgInvoice->bkg_base_amount;
				$promoModel->createDate	 = $model2->bkg_create_date;
				$promoModel->pickupDate	 = $model2->bkg_pickup_date;
				$promoModel->fromCityId	 = $model2->bkg_from_city_id;
				$promoModel->toCityId	 = $model2->bkg_to_city_id;
				$promoModel->userId		 = $model2->bkgUserInfo->bkg_user_id;
				$promoModel->platform	 = $model2->bkgTrail->bkg_platform;
				$promoModel->carType	 = $model2->bkg_vehicle_type_id;
				$promoModel->bookingType = $model2->bkg_booking_type;
				$promoModel->noOfSeat	 = $model2->bkgAddInfo->bkg_no_person;
				$promoModel->bkgId		 = $model2->bkg_id;
				$promoModel->email		 = '';
				$promoModel->phone		 = '';
				$promoModel->imEfect	 = '';

				$discountArr = $promoModel->applyPromoCode();
				if ($discountArr != false)
				{
					if ($discountArr['cash'] > 0 && $discountArr['prm_activate_on'] == 1)
					{
						if (isset($discountArr['nextTripApply']) && $discountArr['nextTripApply'] == 1)
						{
							$discountArr['cash']	 = 0;
							$discountArr['coins']	 = 0;
						}
						if ($discountArr['pcn_type'] == 1 || $discountArr['pcn_type'] == 3)
						{
							$model2->bkgInvoice->bkg_discount_amount = $discountArr['cash'];
							$model2->bkgInvoice->bkg_promo1_amt		 = $discountArr['cash'];
						}
						$isAdvDiscount = true;
					}
					if ($discountArr['cash'] > 0 || $discountArr['coins'] > 0)
					{
						$isPromoApplied	 = true;
						$isPromo		 = false;
					}
					if ($discountArr['pcn_type'] == 1)
					{
						if ($discountArr['prm_activate_on'] == 1)
						{
							$msg = 'Promo ' . $model->bkgInvoice->bkg_promo1_code . ' applied successfully .You will get discount worth ?' . $discountArr["cash"] . ' when you make payment.';
						}
						else
						{
							$msg = 'Promo ' . $model->bkgInvoice->bkg_promo1_code . ' used successfully.';
						}
					}
					if ($discountArr['pcn_type'] == 2)
					{
						$msg = "Promo applied successfully. You got Gozo Coins worth ?" . $discountArr['coins'] . ". You may redeem these Gozo Coins against your future bookings with us.";
						Logger::create("PRM TYPE 2 Platform:- " . $model->bkgTrail->bkg_platform);
						if ($model->bkgTrail->bkg_platform != 3)
						{
							$msg .= "*<a href='#' onclick='showTcGozoCoins2()'>T&C</a> Apply";
						}
					}
					if ($discountArr['pcn_type'] == 3)
					{
						$msg = "Promo applied successfully. You will get discount worth ?" . $discountArr['cash'] . " and Gozo Coins worth ?" . $discountArr['coins'] . ".* You may redeem these Gozo Coins against your future bookings with us.";
						Logger::create("PRM TYPE 2 Platform:- " . $model->bkgTrail->bkg_platform);
						if ($model->bkgTrail->bkg_platform != 3)
						{
							$msg .= "*<a href='#' onclick='showTcGozoCoins2()'>T&C</a> Apply";
						}
					}
					if (isset($discountArr['nextTripApply']) && $discountArr['nextTripApply'] == 1)
					{
						$msg = "Promo applied successfully. You will be benefited on your next trip.";
					}
					$prmType			 = $discountArr['pcn_type'];
					$promoDescription	 = $promoModel->prm_desc;
					$promoCode			 = $promoModel->prm_code;
					$promo_id			 = $promoModel->prm_id;
				}
			}
			else
			{
				$userCreditStatus = UserCredits::model()->getGozocoinsUsesStatus($model2->bkgUserInfo->bkg_user_id);
			}
			$model2->bkgInvoice->calculateConvenienceFee(0);
			$model2->bkgInvoice->calculateTotal();
			$usepromo								 = ($model->bkgInvoice->bkg_promo1_id == 0);
			$result									 = true;
			$MaxCredits								 = UserCredits::getApplicableCredits($model2->bkgUserInfo->bkg_user_id, $model2->bkgInvoice->bkg_base_amount, $usepromo, $model->bkg_from_city_id, $model->bkg_to_city_id);
			$model2->bkgInvoice->bkg_credits_used	 = min([$creditVal, $MaxCredits["credits"], $model2->bkgInvoice->bkg_total_amount]);
			if ($isWalletUsed == 1 && UserInfo::getUserId() > 0 && $amtWalletUsed > 0)
			{
				$totWalletBalance						 = UserWallet::getBalance(UserInfo::getUserId());
				$totWalletBalance						 = ($model2->bkgInvoice->bkg_due_amount < $totWalletBalance) ? $model2->bkgInvoice->bkg_due_amount : $totWalletBalance;
				$amtWalletUsed							 = ($amtWalletUsed > $totWalletBalance) ? $totWalletBalance : $amtWalletUsed;
				$model2->bkgInvoice->bkg_advance_amount	 += $amtWalletUsed;
			}
			$model2->bkgInvoice->calculateTotal();
			$amtWithoutConvFee	 = $model2->bkgInvoice->bkg_total_amount;
			$dueWithoutConvFee	 = $model2->bkgInvoice->bkg_due_amount;
			$taxWithoutConvFee	 = round($model2->bkgInvoice->bkg_service_tax);
			//data without COD
			//data with COD
			$model1				 = clone $model2;
			$model1->bkgInvoice->calculateConvenienceFee(0);
			$model1->bkgInvoice->calculateTotal();
			$taxWithConvFee		 = round($model1->bkgInvoice->bkg_service_tax);
			//data with COD

			$refundAmount	 = $MaxCredits['refundCredits'];
			$baseAmt		 = $model2->bkgInvoice->bkg_base_amount;
			$driverAllowance = $model2->bkgInvoice->bkg_driver_allowance_amount;
			$creditused		 = $model2->bkgInvoice->bkg_credits_used;
			$discount		 = $model2->bkgInvoice->bkg_discount_amount;
			$minpay			 = $model2->bkgInvoice->calculateMinPayment();
			$convFee		 = $model1->bkgInvoice->bkg_total_amount - $model2->bkgInvoice->bkg_total_amount; //+ Filter::getServiceTax($model->bkg_convenience_charge);

			$amountWithConvFee = round($model1->bkgInvoice->bkg_due_amount);
			if ($isAdvDiscount)
			{
				$model->bkgInvoice->bkg_credits_used = $creditused;
				$model->bkgInvoice->calculateTotal();
				$amountWithConvFee					 = round($model->bkgInvoice->bkg_due_amount);
			}

			$percent30ofAmt = round(($amtWithoutConvFee * 0.3));

			if ($creditused >= $percent30ofAmt)
			{
				$convFee							 = 0;
				$model->bkgInvoice->bkg_credits_used = $creditused;
				$model->bkgInvoice->calculateConvenienceFee(0);
				$model->bkgInvoice->calculateTotal();
				$amountWithConvFee					 = $model->bkgInvoice->bkg_due_amount;
			}
			if ($creditused > 0)
			{
				$credit					 = true;
				$userInfo				 = UserInfo::model();
				$userInfo->userType		 = UserInfo::TYPE_CONSUMER;
				$userInfo->userId		 = $model->bkgUserInfo->bkg_user_id;
				$eventid				 = BookingLog::BOOKING_PROMO;
				$params['blg_ref_id']	 = BookingLog::REF_PROMO_GOZOCOINS_APPLIED;
				BookingLog::model()->createLog($model->bkg_id, "Gozocoin " . $creditused . " applied successfully.", $userInfo, $eventid, false, $params);
			}
			$isPromoCodeUsed = ($model->bkgInvoice->bkg_promo1_id != 0) ? 1 : 0;
			//$isPromo		 = ($isPromoCodeUsed == 0 && $creditused > 0 && $creditused <= $refundAmount);
		}
		else
		{
			$result = false;
			if ($isWalletUsed == 1 && UserInfo::getUserId() > 0 && $amtWalletUsed > 0)
			{
				$totWalletBalance						 = UserWallet::getBalance(UserInfo::getUserId());
				$totWalletBalance						 = ($model->bkgInvoice->bkg_due_amount < $totWalletBalance) ? $model->bkgInvoice->bkg_due_amount : $totWalletBalance;
				$amtWalletUsed							 = ($amtWalletUsed > $totWalletBalance) ? $totWalletBalance : $amtWalletUsed;
				$model->bkgInvoice->bkg_advance_amount	 += $amtWalletUsed;
				$model->bkgInvoice->calculateTotal();
				$dueWithoutConvFee						 = $model->bkgInvoice->bkg_due_amount;
				$minpay									 = $model->bkgInvoice->calculateMinPayment();
			}
		}
		$status = [
			'result'					 => $result,
			'apply'						 => $result,
			'newtotal'					 => $amtWithoutConvFee,
			'isPromoCodeUsed'			 => $isPromoCodeUsed,
			'refundCredits'				 => $refundAmount,
			'credits_used'				 => $creditused,
			'due_amount'				 => $dueWithoutConvFee,
			'discount'					 => $discount,
			'service_tax'				 => $taxWithoutConvFee,
			'service_tax_with_conv_fee'	 => $taxWithConvFee,
			'driver_allowance'			 => $driverAllowance,
			'total_amount'				 => $amtWithoutConvFee,
			'base_amount'				 => $baseAmt,
			'convFee'					 => $convFee,
			'amountWithConvFee'			 => $amountWithConvFee,
			'minPayable'				 => $minpay,
			'isPromo'					 => $isPromo,
			'promo_desc'				 => $promoDescription,
			'promo_code'				 => $promoCode,
			'promo_type'				 => $prmType,
			'message'					 => $msg,
			'isCredit'					 => false,
			'isAdvDiscount'				 => $isAdvDiscount,
			'credit'					 => $credit,
			'creditStatus'				 => $userCreditStatus,
			'amtWalletUsed'				 => $amtWalletUsed,
			'isPromoApplied'			 => $isPromoApplied | false,
			'isGozoCoinsApplied'		 => $credit,
			'isWalletApplied'			 => ($amtWalletUsed > 0) ? true : false,
			'isRefundCredits'			 => ($refundAmount > 0) ? true : false,
			'promo_id'					 => $promo_id
		];
		return $status;
	}

	private function getTransdetailByTranscode($transCode)
	{
		$transResult = PaymentGateway::model()->getTransdetailByTranscode($transCode);
		return $transResult;
	}

	public function actionDiscountAdvance()
	{
		$result			 = false;
		$bkgid			 = Yii::app()->request->getParam('bkg_id');
		$creditapplied	 = Yii::app()->request->getParam('credit_amount');
		$hash			 = Yii::app()->request->getParam('bkghash');
		$isDiscAdvance	 = Yii::app()->request->getParam('isDiscAdv');
		$prmType		 = 0;
		$creditVal		 = 0;
		$msg			 = "";
		/* @var $model Booking */
		$model			 = Booking::model()->findbyPk($bkgid);
		if (!$model)
		{
			throw new CHttpException(400, 'Invalid data');
		}
		if ($model->bkg_platform != 3)
		{
			if ($bkgid != Yii::app()->shortHash->unHash($hash))
			{
				throw new CHttpException(400, 'Invalid data');
			}
		}

		if ($isDiscAdvance == 1)
		{
			$model2						 = clone $model;
			$model2->bkg_credits_used	 = $creditapplied;
			$model2->calculateConvenienceFee(0);
			$model2->calculateTotal();
			$discount					 = round($model2->bkg_base_amount * 0.05);
			if ($discount > 0 && $discount <= $model2->bkg_due_amount && ($model2->bkg_discount_amount == 0 || $model2->bkg_discount_amount == ''))
			{
				$model->bkg_discount_amount		 = $discount;
				//$model->calculateConvenienceFee();
				$model->populateAmount(true, true, true, false, $model->bkg_agent_id);
				$model->bkg_user_last_updated_on = new CDbExpression('NOW()');
				if ($model->save())
				{
					$result	 = true;
					$promo	 = true;
				}
				if ($creditapplied > 0)
				{
					$model->bkg_credits_used = $creditapplied;
				}
				$model->calculateTotal();

				$model1				 = clone $model;
				$model1->calculateConvenienceFee(0);
				$model1->calculateTotal();
				$model1->calculateVendorAmount();
				$credits			 = UserCredits::getApplicableCredits($model->bkg_user_id, $model1->bkg_base_amount, false, $model->bkg_from_city_id, $model->bkg_to_city_id);
				$creditVal			 = $credits['credits'];
				$discount			 = $model1->bkg_discount_amount;
				$promocode			 = $model1->bkg_promo_code;
				$baseAmt			 = $model1->bkg_base_amount;
				$amtWithoutConvFee	 = $model1->bkg_total_amount;
				$dueWithoutConvFee	 = $model1->bkg_due_amount;
				$taxWithoutConvFee	 = round($model1->bkg_service_tax);
				$driver_allowance	 = $model1->bkg_driver_allowance_amount;
				$creditused			 = ($model1->bkg_credits_used > 0) ? $model1->bkg_credits_used : 0;
				$minpay				 = $model1->calculateMinPayment();

				$convFee			 = $model->bkg_due_amount - $model1->bkg_due_amount;
				$amountWithConvFee	 = round($model->bkg_due_amount);
				$percent30ofAmt		 = round($model1->bkg_total_amount * 0.3);
				if ($creditused >= $percent30ofAmt)
				{
					$convFee			 = 0;
					$amountWithConvFee	 = $dueWithoutConvFee;
				}
				$refundCredits	 = $credits['refundCredits'];
				$isCredit		 = ($refundCredits > 0 && $creditused == 0 && !Yii::app()->user->isGuest);
				$credit			 = ($creditapplied > 0);
			}
		}

		$status = ['message'			 => $msg,
			'promo_type'		 => $prmType,
			'refundCredits'		 => $refundCredits,
			'credit'			 => $credit,
			'credits_used'		 => $creditapplied,
			'totCredits'		 => $creditVal,
			'creditused'		 => $creditused,
			'result'			 => $result,
			'due_amount'		 => $dueWithoutConvFee,
			'promo_code'		 => $promocode,
			'discount'			 => $discount,
			'service_tax'		 => $taxWithoutConvFee,
			'driver_allowance'	 => $driver_allowance,
			'total_amount'		 => $amtWithoutConvFee,
			'base_amount'		 => $baseAmt,
			'convFee'			 => $convFee,
			'amountWithConvFee'	 => $amountWithConvFee,
			'minPayable'		 => $minpay,
			'isCredit'			 => $isCredit,
			'isDiscAdv'			 => 1,
			'isPromo'			 => false,
			'promo'				 => $promo,
		];

		echo CJSON::encode($status);
	}

	public function promoRemoveService($isRemoveOnly = false, $service = 0)
	{
		$bkgid			 = Yii::app()->request->getParam('bkg_id');
		$hash			 = Yii::app()->request->getParam('bkghash');
		$creditapplied	 = Yii::app()->request->getParam('credit_amount', 0);
		$isRemoveOnly	 = ($isRemoveOnly) ? $isRemoveOnly : Yii::app()->request->getParam('isRemoveOnly');
		$flag2			 = 1;
		if ($service == 0)
		{
			$this->promoRemoveServiceData($bkgid, $hash, $creditapplied, $flag2, $isRemoveOnly);
		}
		else
		{
			return $this->promoRemoveServiceData($bkgid, $hash, $creditapplied, $flag2, $isRemoveOnly, $service);
		}
	}

	public function promoRemoveServiceData($bkgid, $hash, $creditapplied, $flag2 = 0, $isRemoveOnly = false, $service = 0)
	{
		$isWalletUsed	 = Yii::app()->request->getParam('isWalletUsed', 0);
		$amtWalletUsed	 = Yii::app()->request->getParam('amtWalletUsed', 0);

		if (isset($bkgid) && $bkgid > 0)
		{
			$model = Booking::model()->findbyPk($bkgid);
			if (!$model)
			{
				throw new CHttpException(400, 'Invalid data');
			}
		}
		if ($model->bkgTrail->bkg_platform != 3)
		{
			if ($bkgid != Yii::app()->shortHash->unHash($hash))
			{
				throw new CHttpException(400, 'Invalid data');
			}
		}
		if (isset($bkgid))
		{
			Logger::create("PROMOCODE OLD:---: " . $model->bkgInvoice->bkg_promo1_code, CLogger::LEVEL_INFO);
			if ($model->bkgInvoice->bkg_promo1_id > 0)
			{
				$oldPromo	 = $model->bkgInvoice->bkg_promo1_code;
				//data with COD
				//$promoModel	 = Promos::model()->getByCode($model->bkgInvoice->bkg_promo1_code);
				$promoModel	 = Promos::model()->findByPk($model->bkgInvoice->bkg_promo1_id);
				if (!$promoModel)
				{
					throw new Exception('Invalid Promo code');
				}
				$promoModel->promoCode	 = $model->bkgInvoice->bkg_promo1_code;
				$promoModel->totalAmount = $model->bkgInvoice->bkg_base_amount;
				$promoModel->createDate	 = $model->bkg_create_date;
				$promoModel->pickupDate	 = $model->bkg_pickup_date;
				$promoModel->fromCityId	 = $model->bkg_from_city_id;
				$promoModel->toCityId	 = $model->bkg_to_city_id;
				$promoModel->userId		 = $model->bkgUserInfo->bkg_user_id;
				$promoModel->platform	 = $model->bkgTrail->bkg_platform;
				$promoModel->carType	 = $model->bkg_vehicle_type_id;
				$promoModel->bookingType = $model->bkg_booking_type;
				$promoModel->noOfSeat	 = $model->bkgAddInfo->bkg_no_person;
				$promoModel->bkgId		 = $model->bkg_id;
				$promoModel->email		 = '';
				$promoModel->phone		 = '';
				$promoModel->imEfect	 = '';

				$discountArr = $promoModel->applyPromoCode();
				//Logger::create("Discount ARRAY:---: " . CJSON::encode($discountArr), CLogger::LEVEL_INFO);
				if ($discountArr != false)
				{
					if ($discountArr['pcn_type'] == 2)
					{
						$discountArr['cash'] = 0;
					}
					if ($discountArr['prm_activate_on'] == 1)
					{
						$discountArr['cash'] = 0;
					}
					if (isset($discountArr['nextTripApply']) && $discountArr['nextTripApply'] == 1)
					{
						$discountArr['cash']	 = 0;
						$discountArr['coins']	 = 0;
					}

					$model->bkgInvoice->bkg_promo1_code				 = '';
					$model->bkgInvoice->bkg_promo1_id				 = '0';
					$remainigDiscount								 = $model->bkgInvoice->bkg_discount_amount - $discountArr['cash'];
					//$remainigPromo1amount							 = $model->bkgInvoice->bkg_promo1_amt - $prmdiscount;
					$discount										 = ($remainigDiscount > 0) ? $remainigDiscount : 0;
					$model->bkgInvoice->bkg_discount_amount			 = 0;
					$model->bkgInvoice->bkg_promo1_amt				 = '0';
					$model->bkgUserInfo->bkg_user_last_updated_on	 = new CDbExpression('NOW()');
					$model->bkgInvoice->calculateConvenienceFee(0);
					$model->bkgInvoice->calculateTotal();
					$model->bkgInvoice->bkg_total_amount			 = $model->bkgInvoice->bkg_total_amount + $discountArr['cash'];
					if ($model->bkgInvoice->save() && $model->bkgUserInfo->save())
					{
						$result					 = true;
						$promoRemove			 = true;
						$userInfo				 = UserInfo::model();
						$userInfo->userType		 = UserInfo::TYPE_CONSUMER;
						$userInfo->userId		 = $model->bkgUserInfo->bkg_user_id;
						$eventid				 = BookingLog::BOOKING_PROMO;
						$params['blg_ref_id']	 = BookingLog::REF_PROMO_REMOVED;
						BookingLog::model()->createLog($model->bkg_id, "Promo '$oldPromo' removed successfully.", $userInfo, $eventid, false, $params);
					}

					$model->bkgInvoice->bkg_credits_used = $creditapplied;
					$model->bkgInvoice->calculateTotal();
					if ($isWalletUsed == 1 && UserInfo::getUserId() > 0 && $amtWalletUsed > 0)
					{
						$totWalletBalance						 = UserWallet::getBalance(UserInfo::getUserId());
						$totWalletBalance						 = ($model->bkgInvoice->bkg_due_amount < $totWalletBalance) ? $model->bkgInvoice->bkg_due_amount : $totWalletBalance;
						$amtWalletUsed							 = ($amtWalletUsed > $totWalletBalance) ? $totWalletBalance : $amtWalletUsed;
						$model->bkgInvoice->bkg_advance_amount	 += $amtWalletUsed;
						$model->bkgInvoice->calculateTotal();
					}
					//data with COD
					//data without COD
					$model1				 = clone $model;
					$model1->bkgInvoice->calculateConvenienceFee(0);
					$model1->bkgInvoice->calculateTotal();
					$amtWithoutConvFee	 = round($model1->bkgInvoice->bkg_total_amount);
					$dueWithoutConvFee	 = round($model1->bkgInvoice->bkg_due_amount);
					$taxWithoutConvFee	 = round($model1->bkgInvoice->bkg_service_tax);
					//data without COD



					$discount			 = $model1->bkgInvoice->bkg_discount_amount;
					//$promocode			 = $model1->bkgInvoice->bkg_promo1_code;
					$promocode			 = $oldPromo;
					$baseAmt			 = round($model1->bkgInvoice->bkg_base_amount);
					$driver_allowance	 = round($model1->bkgInvoice->bkg_driver_allowance_amount);
					$minpay				 = $model1->bkgInvoice->calculateMinPayment();
					$convFee			 = $model->bkgInvoice->bkg_due_amount - $model1->bkgInvoice->bkg_due_amount; // + Filter::getServiceTax($model->bkg_convenience_charge);
					$creditused			 = round($model1->bkgInvoice->bkg_credits_used);
					$amountWithConvFee	 = round($model->bkgInvoice->bkg_due_amount);

					$percent30ofAmt = round(($amtWithoutConvFee * 0.3));
					if ($creditused >= $percent30ofAmt)
					{
						$convFee			 = 0;
						$amountWithConvFee	 = $dueWithoutConvFee;
					}
					$MaxCredits		 = UserCredits::getApplicableCredits($model->bkgUserInfo->bkg_user_id, $model1->bkgInvoice->bkg_base_amount, true, $model->bkg_from_city_id, $model->bkg_to_city_id);
					$creditVal		 = $MaxCredits['credits'];
					$isCredit		 = ($creditVal > 0 && $creditapplied == 0 && !Yii::app()->user->isGuest);
					$isCreditUsed	 = ($creditused > 0);
					$discAdvDue		 = $model1->bkgInvoice->bkg_due_amount;
				}
				else
				{
					$result = false;
				}
			}
			else
			{
				$result = false;
			}

			if ($flag2 == '1')
			{
				$status = [
					'result'			 => $result,
					'message'			 => "",
					'isPromoUsed'		 => false,
					'isCreditUsed'		 => $isCreditUsed,
					'promo_type'		 => 0,
					'totCredits'		 => $creditVal,
					'creditused'		 => $creditused,
					'result'			 => $result,
					'due_amount'		 => $dueWithoutConvFee,
					'promo_code'		 => $promocode,
					'discount'			 => 0,
					'service_tax'		 => $taxWithoutConvFee,
					'driver_allowance'	 => $driver_allowance,
					'total_amount'		 => $amtWithoutConvFee,
					'base_amount'		 => $baseAmt,
					'convFee'			 => $convFee,
					'amountWithConvFee'	 => $amountWithConvFee,
					'minPayable'		 => $minpay,
					'promoRemove'		 => $promoRemove,
					'isCredit'			 => $isCredit,
					'discAdv'			 => $discAdvDue,
					'isAdvDiscount'		 => false,
					'isPromo'			 => true,
					'success'			 => ($result) ? 'true' : 'false',
					'amtWalletUsed'		 => $amtWalletUsed,
					'isWalletApplied'	 => ($amtWalletUsed > 0) ? true : false,
				];
				if ($service == 1)
				{
					return $status;
				}
				if ($isRemoveOnly)
				{
					echo CJSON::encode($status);
				}
			}
		}
	}

	public function actionToproutelist()
	{
		$scity	 = Yii::app()->request->getParam('var'); //tocity
		$pscity	 = Yii::app()->request->getParam('var2'); //fromcity
		Route::model()->topRoute($scity, $pscity);
	}

	public function actionPickupcityairport()
	{
		$fromCity	 = Yii::app()->request->getParam('fromCity');
		$maxDistance = Yii::app()->request->getParam('maxDistance');
		$forAirport	 = Yii::app()->request->getParam('forAirport');
		$queryStr	 = Yii::app()->request->getParam('queryStr');
		$limit		 = Yii::app()->request->getParam('limit');

		$airportData = Cities::model()->getNearestCitiesDistanceListbyId($fromCity, $maxDistance, true, $queryStr, $limit);
		echo $airportData[0]['cty_name'];
	}

	public function actionConfirmmobile()
	{
		if (isset($_REQUEST['BookingUser']['bkg_verification_code1']))
		{
			$arr		 = Yii::app()->request->getParam('BookingUser');
			$ctype		 = Yii::app()->request->getParam('ctype');
			$vcode		 = trim($arr['bkg_verification_code1']);
			$bkgid1		 = $arr['bui_bkg_id'];
			$hash1		 = $arr['hash'];
			$transaction = DBUtil::beginTransaction();
			$arrmanual	 = [];
			if ($bkgid1 != Yii::app()->shortHash->unHash($hash1))
			{
				throw new CHttpException(400, 'Invalid data');
			}
			$success	 = false;
			$bmodel		 = Booking::model()->findbyPk($bkgid1);
			$verified	 = false;

			if ($bmodel->bkgUserInfo->bkg_phone_verified == 1 || $bmodel->bkgUserInfo->bkg_email_verified == 1)
			{
				if ($_REQUEST['manual'] == "manual")
				{
					$arrmanual = ['manual' => 'manual'];
				}
				echo CJSON::encode(['success' => true, 'bkg_id' => $bmodel->bkg_id, 'hash' => Yii::app()->shortHash->hash($bmodel->bkg_id)] + $arrmanual);
				Yii::app()->end();
			}


			if ($bmodel->bkgUserInfo->bkg_verification_code == $vcode && $bmodel->bkgUserInfo->bkg_verification_code != '')
			{
				$bmodel->bkgUserInfo->bkg_verification_code	 = '';
				$bmodel->bkgUserInfo->bkg_phone_verified	 = 1;
				$verified									 = true;
			}
			if ($bmodel->bkgUserInfo->bkg_verifycode_email == $vcode && $bmodel->bkgUserInfo->bkg_verifycode_email != '')
			{
				$bmodel->bkgUserInfo->bkg_email_verified	 = 1;
				$bmodel->bkgUserInfo->bkg_verifycode_email	 = '';
				$verified									 = true;
			}
			try
			{
				if ($verified)
				{
					$bmodel->bkgUserInfo->save();

					if ($_REQUEST['manual'] == "manual")
					{
						if ($bmodel->bkgUserInfo->bkg_phone_verified == 1)
						{
							$desc = "Phone manually verified by user";
						}
						if ($bmodel->bkgUserInfo->bkg_email_verified == 1)
						{
							$desc = "Email manually verified by user";
						}
						$eventid	 = BookingLog::BOOKING_VERIFIED;
						$userInfo	 = UserInfo::getInstance();
						BookingLog::model()->createLog($bmodel->bkg_id, $desc, $userInfo, $eventid, $oldModel);
						//  $bmodel->sendConfirmation(UserInfo::TYPE_Consumers);
						$arrmanual	 = ['manual' => 'manual'];
					}
					else
					{
						if ($ctype == 'c1')
						{
							$bmodel->confirmBooking();
							$bmodel->bkgTrack	 = BookingTrack::model()->sendTripOtp($bmodel->bkg_id, $sendOtp			 = false);
							$bmodel->bkgTrack->save;
							$bmodel->sendConfirmation(UserInfo::TYPE_CONSUMER);
						}
						if ($bmodel->bkgUserInfo->bkg_phone_verified == 1)
						{
							$desc = "Phone verified by user";
						}
						if ($bmodel->bkgUserInfo->bkg_email_verified == 1)
						{
							$desc = "Email verified by user";
						}
						$eventid	 = BookingLog::BOOKING_VERIFIED;
						$userInfo	 = UserInfo::getInstance();
						BookingLog::model()->createLog($bmodel->bkg_id, $desc, $userInfo, $eventid, $oldModel);
					}
					$verifyresult	 = true;
					$response		 = Contact::referenceUserData($bmodel->bkgUserInfo->bui_id, 3);
					if ($response->getStatus())
					{
						$email	 = $response->getData()->email['email'];
						$phone	 = $response->getData()->phone['number'];
					}
					if ($bmodel->bkgUserInfo->bkg_email_verified == 1)
					{
						/* @var $bmodel booking */
						$emlModels = ContactEmail::model()->findAll('eml_email_address=:email', ['email' => $email]);
						foreach ($emlModels as $emlModel)
						{
							if ($emlModel != '' && $emlModel->eml_is_verified != 1)
							{
								$emlModel->eml_is_verified = 1;
								$emlModel->save();
							}
						}
					}
					if ($bmodel->bkgUserInfo->bkg_phone_verified == 1)
					{
						/* @var $bmodel booking */
						$phnModels = ContactPhone::model()->findAll('phn_phone_no=:phone', ['phone' => $phone]);
						foreach ($phnModels as $phnModel)
						{
							if ($phnModel != '' && $phnModel->phn_is_verified != 1)
							{
								$phnModel->phn_is_verified = 1;
								$phnModel->save();
							}
						}
					}
					DBUtil::commitTransaction($transaction);
				}
				else
				{
					$verifyresult = false;
				}
			}
			catch (Exception $e)
			{
				$bmodel->addError("bkg_id", $e->getMessage());
				DBUtil::rollbackTransaction($transaction);
			}

			//return $verifyresult;
			echo CJSON::encode(['success' => $verifyresult, 'bkg_id' => $bmodel->bkg_id, 'hash' => Yii::app()->shortHash->hash($bmodel->bkg_id)] + $arrmanual);
			Yii::app()->end();
		}
		else
		{
			$bkgid	 = Yii::app()->request->getParam('bid');
			$hash	 = Yii::app()->request->getParam('hsh');
			$manual	 = Yii::app()->request->getParam('manual');
			$resend	 = Yii::app()->request->getParam('resend');
			$ctype	 = Yii::app()->request->getParam('ctype');
			if ($bkgid != Yii::app()->shortHash->unHash($hash))
			{
				throw new CHttpException(400, 'Invalid data');
			}
			$success = false;
			if (isset($bkgid))
			{
				$model						 = Booking::model()->findbyPk($bkgid);
				$model->hash				 = $hash;
				$model->bkgUserInfo->hash	 = $hash;
				if (!$model)
				{
					throw new CHttpException(400, 'Invalid data');
				}
			}
			$isAlready2Sms = SmsLog::model()->getCountVerifySms($bkgid);
			if ($isAlready2Sms > 0)
			{
				if ($resend == 'resend')
				{
					if ($isAlready2Sms < 2)
					{
						$model->bkgUserInfo->sendVerificationCode(10, true);
						echo json_encode(['success' => true]);
						exit;
					}
					else
					{
						echo json_encode(['success' => false]);
						exit;
					}
				}
			}
			else
			{
				$model->bkgUserInfo->sendVerificationCode(10, true);
			}
		}
		$this->renderPartial('verifymobile', array('model' => $model, 'manual' => $manual, 'isAlready2Sms' => $isAlready2Sms, 'ctype' => $ctype), false, true);
	}

	public function actionVerify1()
	{
		$id		 = Yii::app()->request->getParam('id');
		$hash	 = Yii::app()->request->getParam('hash');

		if ($id != Yii::app()->shortHash->unHash($hash))
		{
			throw new CHttpException(400, 'Invalid data');
		}
		$bmodel = Booking::model()->findbyPk($id);
		if (isset($_POST['otpvalue']) && $_POST['otpvalue'] != "")
		{
			$verified = false;
			if (trim($bmodel->bkgUserInfo->bkg_verification_code) == trim($_POST['otpvalue']) && $bmodel->bkgUserInfo->bkg_verification_code != '')
			{
				$verified								 = true;
				$bmodel->bkgUserInfo->bkg_phone_verified = 1;
				$phoneVerified							 = true;
			}
			if (trim($bmodel->bkgUserInfo->bkg_verifycode_email) == trim($_POST['otpvalue']) && $bmodel->bkgUserInfo->bkg_verifycode_email != '')
			{
				$verified								 = true;
				$bmodel->bkgUserInfo->bkg_email_verified = 1;
				$emailVerified							 = true;
			}

			if ($verified)
			{
				$bmodel->scenario = 'adminupdate';
				if ($bmodel->validate())
				{
					if ($bmodel->bkgUserInfo->bkg_phone_verified == 1)
					{
						$bmodel->bkgUserInfo->bkg_verification_code = '';
					}
					if ($bmodel->bkgUserInfo->bkg_email_verified == 1)
					{
						$bmodel->bkgUserInfo->bkg_verifycode_email = '';
					}
					$bmodel->save();
					$bmodel->bkgUserInfo->save();
					$response = Contact::referenceUserData($bmodel->bkgUserInfo->bui_id, 3);
					if ($response->getStatus())
					{
						$email	 = $response->getData()->email['email'];
						$phone	 = $response->getData()->phone['number'];
					}
					if ($bmodel->bkgUserInfo->bkg_email_verified == 1)
					{
						/* @var $bmodel booking */
						$emlModels = ContactEmail::model()->findAll('eml_email_address=:email', ['email' => $email]);
						foreach ($emlModels as $emlModel)
						{
							if ($emlModel != '' && $emlModel->eml_is_verified != 1)
							{
								$emlModel->eml_is_verified = 1;
								$emlModel->save();
							}
						}
					}
					if ($bmodel->bkgUserInfo->bkg_phone_verified == 1)
					{
						/* @var $bmodel booking */
						$phnModels = ContactPhone::model()->findAll('phn_phone_no=:phone', ['phone' => $phone]);
						foreach ($phnModels as $phnModel)
						{
							if ($phnModel != '' && $phnModel->phn_is_verified != 1)
							{
								$phnModel->phn_is_verified = 1;
								$phnModel->save();
							}
						}
					}
					if ($bmodel->bkg_status == 1)
					{
						$bmodel->confirmBooking();
						$desc		 = "Booking verified manually by user";
						$eventid	 = BookingLog::BOOKING_VERIFIED;
						$userInfo	 = UserInfo::getInstance();
						BookingLog::model()->createLog($bmodel->bkg_id, $desc, $userInfo, $eventid, $oldModel);
						$bmodel->sendConfirmation(UserInfo::TYPE_CONSUMER);
						$success	 = true;
						$url		 = Yii::app()->createAbsoluteUrl('booking/paynow', ['id' => $bmodel->bkg_id, 'hash' => $hash]);
					}
					else
					{
						$error = "Contact info already verified";
						if ($emailVerified)
						{
							$error = "Your Email ID has been verified successfully.";
						}
						if ($phoneVerified)
						{
							$error = "Your Phone Number has been verified successfully.";
						}
					}
				}
				else
				{
					$error = "Incomplete booking data";
				}
			}
			else
			{
				$error = "Invalid OTP";
				if ($bmodel->bkgUserInfo->bkg_phone_verified == 1 && $bmodel->bkgUserInfo->bkg_email_verified == 1)
				{
					$error = "Contact info already verified";
				}
			}
		}
		else
		{
			if (isset($_POST['submitotp']))
			{
				$error = "Please enter OTP";
			}
		}
		$this->render('verify1', ['error' => $error, 'success' => $success, 'url' => $url, 'model' => $bmodel]);
	}

	public function actionVerifycontact()
	{
		$id		 = Yii::app()->request->getParam('id');
		$hash	 = Yii::app()->request->getParam('hash');
		if ($id != Yii::app()->shortHash->unHash($hash))
		{
			throw new CHttpException(400, 'Invalid data');
		}
		$bmodel		 = Booking::model()->findbyPk($id);
		$verified	 = false;
		$success	 = false;
		if (isset($_POST['otpvalue']) && $_POST['otpvalue'] != "")
		{
			if (trim($bmodel->bkgUserInfo->bkg_verification_code) == trim($_POST['otpvalue']) && $bmodel->bkgUserInfo->bkg_verification_code != '')
			{
				$verified								 = true;
				$bmodel->bkgUserInfo->bkg_phone_verified = 1;
				$phoneVerified							 = true;
			}
			if (trim($bmodel->bkgUserInfo->bkg_verifycode_email) == trim($_POST['otpvalue']) && $bmodel->bkgUserInfo->bkg_verifycode_email != '')
			{
				$verified								 = true;
				$bmodel->bkgUserInfo->bkg_email_verified = 1;
				$emailVerified							 = true;
			}

			if ($verified)
			{
				$bmodel->scenario	 = 'adminupdate';
				$transaction		 = DBUtil::beginTransaction();
				try
				{
					if ($bmodel->validate())
					{
						if ($bmodel->bkgUserInfo->bkg_phone_verified == 1)
						{
							$bmodel->bkgUserInfo->bkg_verification_code = '';
						}
						if ($bmodel->bkgUserInfo->bkg_email_verified == 1)
						{
							$bmodel->bkgUserInfo->bkg_verifycode_email = '';
						}
						$bmodel->save();
						$bmodel->bkgUserInfo->save();
						$response = Contact::referenceUserData($bmodel->bkgUserInfo->bui_id, 3);
						if ($response->getStatus())
						{
							$email	 = $response->getData()->email['email'];
							$phone	 = $response->getData()->phone['number'];
						}
						if ($bmodel->bkgUserInfo->bkg_email_verified == 1)
						{
							/* @var $bmodel booking */
							$emlModels = ContactEmail::model()->findAll('eml_email_address=:email', ['email' => $email]);
							foreach ($emlModels as $emlModel)
							{
								if ($emlModel != '' && $emlModel->eml_is_verified != 1)
								{
									$emlModel->eml_is_verified = 1;
									$emlModel->save();
								}
							}
						}
						if ($bmodel->bkgUserInfo->bkg_phone_verified == 1)
						{
							/* @var $bmodel booking */
							$phnModels = ContactPhone::model()->findAll('phn_phone_no=:phone', ['phone' => $phone]);
							foreach ($phnModels as $phnModel)
							{
								if ($phnModel != '' && $phnModel->phn_is_verified != 1)
								{
									$phnModel->phn_is_verified = 1;
									$phnModel->save();
								}
							}
						}
						if ($bmodel->bkg_status == 1)
						{
							$bmodel->confirmBooking();

							$bmodel->bkgTrack	 = BookingTrack::model()->sendTripOtp($bmodel->bkg_id, $sendOtp			 = false);
							$bmodel->bkgTrack->save();
							$desc				 = "Booking verified manually by user";
							$eventid			 = BookingLog::BOOKING_VERIFIED;
							$userInfo			 = UserInfo::getInstance();
							BookingLog::model()->createLog($bmodel->bkg_id, $desc, $userInfo, $eventid, $oldModel);
							$bmodel->sendConfirmation(UserInfo::TYPE_CONSUMER);
							$success			 = true;
							if ($emailVerified)
							{
								$msg = "Your Email ID has been verified successfully.";
							}
							if ($phoneVerified)
							{
								$msg = "Your Phone Number has been verified successfully.";
							}
						}
						else
						{
							$msg = "Contact info already verified";
							if ($emailVerified)
							{
								$msg = "Your Email ID has been verified successfully.";
							}
							if ($phoneVerified)
							{
								$msg = "Your Phone Number has been verified successfully.";
							}
							$success = true;
						}
						DBUtil::commitTransaction($transaction);
					}
					else
					{
						$msg = "Incomplete booking data";
						DBUtil::rollbackTransaction($transaction);
					}
				}
				catch (Exception $e)
				{
					$bmodel->addError("bkg_id", $e->getMessage());
					DBUtil::rollbackTransaction($transaction);
				}
			}
			else
			{
				$msg = "Invalid OTP";
				if ($bmodel->bkgUserInfo->bkg_phone_verified == 1 && $bmodel->bkgUserInfo->bkg_email_verified == 1)
				{
					$msg	 = "Contact info already verified";
					$success = true;
				}
			}
		}
		else
		{
			$msg = "Please enter OTP";
		}
		echo json_encode(['success' => $success, 'errors' => $msg]);
		Yii::app()->end();
	}

	public function actionVerifytrip()
	{
		$this->layout	 = 'head';
		$bookingId		 = Yii::app()->request->getParam('id');
		$hash			 = Yii::app()->request->getParam('hash');
		if ($bookingId != Yii::app()->shortHash->unHash($hash))
		{
			throw new CHttpException(400, 'Invalid url');
		}
		$bookingModel		 = Booking::model()->findByPk($bookingId);
		$bkgTrackModel		 = $bookingModel->bkgTrack;
		$isAllowVerify		 = BookingPref::model()->isAllowTripVerify($bookingId);
		$is_trip_verified	 = $bkgTrackModel->bkg_is_trip_verified;
		$transaction		 = DBUtil::beginTransaction();
		if (isset($_POST['otp']) && $isAllowVerify && $is_trip_verified == 0)
		{
			try
			{
				$tripotp		 = $_POST['otp'];
				$platform		 = 3;
				$bookingTrack	 = $bookingModel->bkgTrack;
				$returnSet		 = $bookingTrack->startTrip($platform, $tripotp);
				$success		 = ($returnSet->getStatus()) ? 1 : 2;
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $e)
			{
				DBUtil::rollbackTransaction($transaction);
				$model->addError("trl_id", $e->getMessage());
			}
		}
		$this->render('verifytrip', ['isAllowVerify' => $isAllowVerify, 'success' => $success, 'BookingId' => $bookingModel->bkg_booking_id, 'isAlreadyVerified' => $is_trip_verified]);
	}

	public function actionFlexxisearch()
	{
		$model = new BookingTemp();

		if (isset($_POST['BookingTemp']))
		{
			if ($_POST['BookingTemp']['bkg_id'] != '')
			{
				$model = BookingTemp::model()->findByPk($_POST['BookingTemp']['bkg_id']);
			}
			$model->attributes	 = $_POST['BookingTemp'];
			$model->hash		 = Yii::app()->shortHash->hash($model->bkg_id);
			$pickup1			 = DateTimeFormat::DatePickerToDate($model->locale_from_date) . " " . DateTimeFormat::TimeToSQLTime($model->locale_from_time);
			$pickup2			 = DateTimeFormat::DatePickerToDate($model->locale_to_date) . " " . DateTimeFormat::TimeToSQLTime($model->locale_to_time);
			$gender				 = $_POST['BookingTemp']['bkgGender'];
			if (isset($_POST['checkSearchValidation']) && $_POST['checkSearchValidation'] == 0)
			{
				$promoterBkgId = $_POST['promotorBkgId'];
			}
			$results = $model->searchFlexxi($model->bkg_from_city_id, $model->bkg_to_city_id, $model->bkg_no_person, $model->bkg_num_large_bag, $model->bkg_num_small_bag, $pickup1, $pickup2, $gender, $promoterBkgId);
		}

		$this->renderPartial('search_flexxi', ['model' => $model, 'results' => $results], false, true);
	}

	public function actionFlexximatch()
	{
		$modelTemp	 = new BookingTemp();
		$bkgId		 = Yii::app()->request->getParam('bkg_id');
		$hash		 = Yii::app()->shortHash->hash($bkgId);
		if ($bkgId == Yii::app()->shortHash->unHash($hash))
		{
			$model					 = Booking::model()->findByPk($bkgId);
			$modelTemp->attributes	 = $model->attributes;
			$modelTemp->save();
		}
		$this->render('match_flexxi', ['model' => $modelTemp]);
	}

	public function actionFbflexxishare()
	{
		$fpBkgId = Yii::app()->request->getParam('id');
		$hash	 = Yii::app()->request->getParam('hash');
		if ($fpBkgId != Yii::app()->shortHash->unHash($hash))
		{
			throw new CHttpException(400, 'Invalid data');
		}
		$fpModel = Booking::model()->findByPk($fpBkgId);
		$fcity	 = Cities::getName($fpModel->bkg_from_city_id);
		$tcity	 = Cities::getName($fpModel->bkg_to_city_id);
		$link	 = "https://www.gozocabs.com/bknw/$fpModel->bkg_id/$hash";

		$this->pageTitle = 'I am going from ' . $fcity . ' to ' . $tcity . ' on ' . date_format($fpModel->bkg_pickup_date, 'd/m/Y') . ' ' . date_format($fpModel->bkg_pickup_date, 'g:i A') . ' and have a few empty seats in my cab. Share the cab with me and book your seat directly on ' . $link;
		$this->renderPartial('fbflexxishare', ['fcity' => $fcity, 'tcity' => $tcity, 'link' => $link, 'date' => $fpModel->bkg_pickup_date]);
	}

	public function actionFlexxiavailableslots()
	{
		$date		 = Yii::app()->request->getParam('pickupDate');
		$fromCity	 = Yii::app()->request->getParam('fromCity');
		$toCity		 = Yii::app()->request->getParam('toCity');
		//$date = '10-09-2018';
		$pickupDate	 = date('Y-m-d', strtotime($date));
		$this->renderPartial('flexxislots_cardview', ['date' => $pickupDate, 'fromCity' => $fromCity, 'toCity' => $toCity]);
	}

	public function actionAlert()
	{
		$firstName	 = Yii::app()->request->getParam('firstName');
		$lastName	 = Yii::app()->request->getParam('lastName');
		$email		 = Yii::app()->request->getParam('email');
		$fromDate	 = Yii::app()->request->getParam('fromDate');
		$toDate		 = Yii::app()->request->getParam('toDate');
		$fromTime	 = Yii::app()->request->getParam('fromTime');
		$toTime		 = Yii::app()->request->getParam('toTime');
		$fromCity	 = Yii::app()->request->getParam('fromCity');
		$toCity		 = Yii::app()->request->getParam('toCity');
		$bookingId	 = Yii::app()->request->getParam('bookingId');

		$fTime	 = date('h:i:s', strtotime($fromTime));
		$tTime	 = date('h:i:s', strtotime($toTime));
		$fDate	 = DateTimeFormat::DatePickerToDate($fromDate);
		$tDate	 = DateTimeFormat::DatePickerToDate($toDate);

		$alertModel					 = new BookingAlert();
		$alertModel->alr_name		 = $firstName . ' ' . $lastName;
		$alertModel->alr_email		 = $email;
		$alertModel->alr_from_city	 = $fromCity;
		$alertModel->alr_to_city	 = $toCity;
		$alertModel->alr_from_date	 = $fDate . ' ' . $fTime;
		$alertModel->alr_to_date	 = $tDate . ' ' . $tTime;
		$alertModel->alr_bkg_id		 = $bookingId;

		$result = $alertModel->saveData();

		echo json_encode($result);
	}

	public function actionNotifyCustomer()
	{
		$notifyData = BookingAlert::model()->getNotifyData();
		foreach ($notifyData as $key => $value)
		{
			$notifyBooking = Booking::model()->getBooingIdForNotifyCustomer($value['alr_from_date'], $value['alr_to_date'], $value['alr_from_city'], $value['alr_to_city']);
			if ($notifyBooking > 0)
			{
				$formCity	 = Cities::getDisplayName($value['alr_from_city']);
				$toCity		 = Cities::getDisplayName($value['alr_to_city']);
				$emailObj	 = new emailWrapper();
				$emailObj->flexxiBookingAlert($value['alr_name'], $value['alr_email'], $formCity, $toCity, $value['alr_from_date'], $value['alr_to_date'], $value['alr_bkg_id'], $value['alr_id'], $notifyBooking);
			}
		}
	}

	public function actionGetPackageDetail()
	{
		$pckageID			 = Yii::app()->request->getParam('pckageID');
		$pickupDt			 = Yii::app()->request->getParam('pickupDt');
		$formatPickUpDt		 = DateTimeFormat::DatePickerToDate($pickupDt);
		$formatPickUpTime	 = Yii::app()->params['defaultPackagePickupTime'];

		$pickupDtTime	 = $formatPickUpDt . ' ' . $formatPickUpTime;
		$packageDetails	 = PackageDetails::model()->getDetails($pckageID);

		$packagemodel = Package::model()->findByPk($pckageID);

		$routeModel		 = $packagemodel->packageDetails;
		$multijsondata	 = BookingRoute::model()->setTripRouteInfo($routeModel, 5, $pickupDtTime);
		echo json_encode(['success'		 => true, //'packageModel' => $packageDetails,
			'multijsondata'	 => $multijsondata]);
	}

	public function actionShowPackage()
	{
		$this->pageTitle = "Package Details";
		$pck_id			 = Yii::app()->request->getParam('pck_id');
		$listShow		 = Yii::app()->request->getParam('listshow', false);

		$model		 = new BookingTemp();
		$resultset	 = PackageDetails::model()->resetScope()->getDetailsById($pck_id, true);

		$this->renderPartial('showpackage', array('model'		 => $model, 'toSubmit'	 => !$listShow,
			'resultset'	 => $resultset), false, true);
	}

	public function actionPackage()
	{
		$pck_name	 = Yii::app()->request->getParam('packageName');
		$type		 = Yii::app()->request->getParam('app');
		$model		 = new BookingTemp();
		if ($pck_name == '')
		{
			$this->pageTitle	 = 'Packages';
			$model->min_nights	 = 0;
			$model->max_nights	 = 10;
			$qry				 = [];
			if (isset($_REQUEST['BookingTemp']))
			{
				$model->from_city	 = $qry['city']		 = Yii::app()->request->getParam('BookingTemp')['from_city'];
				$model->min_nights	 = $qry['min_nights']	 = Yii::app()->request->getParam('BookingTemp')['min_nights'];
				$model->max_nights	 = $qry['max_nights']	 = Yii::app()->request->getParam('BookingTemp')['max_nights'];
			}
			$pmodel = Package::model()->getListtoShow('', $qry);
			/* Quote Start */
			foreach ($pmodel as $key => $pck)
			{
				if ($pck['prt_package_rate'] != '')
				{
					$formatPickUpDt			 = date('Y-m-d', strtotime('+7 days'));
					$formatPickUpTime		 = '09:00:00';
					$pickupDtTime			 = $formatPickUpDt . ' ' . $formatPickUpTime;
					$packagemodel			 = Package::model()->findByPk($pck['pck_id']);
					$routes					 = BookingRoute::model()->populateRouteByPackageId($pck['pck_id'], $pickupDtTime);
					$model->setRoutes($routes);
					$quote					 = new Quote();
					$quote->routes			 = $model->bookingRoutes;
					$quote->tripType		 = 5; // package
					$quote->quoteDate		 = date("Y-m-d H:i:s");
					$quote->pickupDate		 = $pickupDtTime;
					$quote->sourceQuotation	 = Quote::Platform_System;

					/* package */
					$quote->packageID		 = $pck['pck_id'];
					$quote->suggestedPrice	 = $pck['prt_package_rate'];
					$quote->partnerId		 = Yii::app()->params['gozoChannelPartnerId'];

					$quote->setCabTypeArr(Quote::Platform_System);
					$qt							 = $quote->getQuote($pck['prt_package_cab_type']);
					$quoteData[$pck['pck_id']]	 = $qt[$pck['prt_package_cab_type']];
				}
			}
			/* Quote End */

			$this->render('package', ['pmodel' => $pmodel, 'model' => $model, 'quoteData' => $quoteData]);
		}
		else
		{
			$this->pageTitle = 'Packages Details';
			$pck_id			 = PackageDetails::getIdByUrlName($pck_name);
			if ($pck_id != "")
			{
				$pck_city_details	 = PackageDetails::getCItyByPackid($pck_id);
				$resultset			 = PackageDetails::model()->resetScope()->getDetailsById($pck_id, true);
			}
			if ($type == 1)
			{
				$this->layout = "head";
			}
			$this->render('package_view', array('model' => $model, 'resultset' => $resultset, 'pck_city_details' => $pck_city_details));
		}
	}

	public function actionRouteRating($route = '')
	{
		$this->checkV3Theme();
		$this->pageTitle = "Route Review";
		$pageSize		 = 20;
		try
		{
			$arrSubject	 = explode("-", $route);
			$dataCity	 = [];
			if ($route != '')
			{
				$rModel						 = Route::model()->getByName($route);
				$formCityName				 = $rModel->rutFromCity->cty_name;
				$toCityName					 = $rModel->rutToCity->cty_name;
				$this->pageTitle			 = "Route Ratings " . $formCityName . '-' . $toCityName;
				$dataCity['from_city_id']	 = trim($rModel->rut_from_city_id);
				$dataCity['to_city_id']		 = trim($rModel->rut_to_city_id);
			}
			if (!$rModel)
			{
				if ($arrSubject[0] != "")
				{
					$cModel						 = Cities::model()->getByCity($arrSubject[0]);
					$dataCity['from_city_id']	 = trim($cModel->cty_id);
				}
				if ($arrSubject[1] != "")
				{
					$cModel					 = Cities::model()->getByCity($arrSubject[1]);
					$dataCity['to_city_id']	 = trim($cModel->cty_id);
				}
			}
			if (!$rModel && !$cModel)
			{
				throw new CHttpException(404, "Route/City not found", 404);
			}
			elseif ($dataCity['from_city_id'] == "" || $dataCity['to_city_id'] == "")
			{
				throw new CHttpException(404, "Route/City not found", 404);
			}
			else
			{
				$drRatingList	 = Ratings::routeRating($dataCity['from_city_id'], $dataCity['to_city_id']);
				$modelList		 = new CArrayDataProvider($drRatingList, array('pagination' => array('pageSize' => $pageSize, 'route' => 'route-rating/' . $route, 'params' => array('route' => 'default'))));
				$models			 = $modelList->getData();

				$this->render('route_rating', array('model' => $models, 'usersList' => $modelList));
			}
		}
		catch (Exception $e)
		{
			echo $message = $e->getMessage();
			ReturnSet::setException($e);
		}
	}

	public function actionShuttle()
	{
		exit;
		$this->pageTitle = 'Shuttle';
		$model			 = new Booking();

		$this->render('shuttle', ['model' => $model]);
	}

	public function actionEvalCharges()
	{
		$id		 = Yii::app()->request->getParam('id');
		$hash	 = Yii::app()->request->getParam('hash');

		$model = Booking::model()->findByPk($id);
		if (!Yii::app()->request->isPostRequest || $id != Yii::app()->shortHash->unhash($hash) || !$model)
		{
			throw new CHttpException(400, "Invalid Request");
		}
		$minDiff = $model->getPaymentExpiryTimeinMinutes();

		if (!in_array($model->bkg_status, [15, 2, 3]) || $minDiff <= 0)
		{
			throw new CHttpException(410, "Payment link expired");
		}


		$this->evaluateCharges($model, $_POST, false);

		$obj = new Stub\common\Fare();
		$obj->setInvoiceData($model->bkgInvoice);

		$returnSet = new ReturnSet();
		$returnSet->setStatus(true);
		$returnSet->setData($obj);
		echo json_encode($returnSet);
		Yii::app()->end();
	}

	public function actionFinalPay()
	{
		Logger::trace("start");
		$request = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_FINALPAY);

		$id		 = Yii::app()->request->getParam('id');
		$hash	 = Yii::app()->request->getParam('hash');

		$model = Booking::model()->findByPk($id);
		try
		{
//            if (Yii::app()->user->isGuest && $phone == "" && $request->isAjaxRequest)
//			{
//                
//				throw new CHttpException(401, "Login required.", ReturnSet::ERROR_UNAUTHORISED);
//			}
			if (!Yii::app()->request->isPostRequest || $id != Yii::app()->shortHash->unhash($hash) || !$model)
			{
				throw new CHttpException(400, "Invalid Request");
			}

			$curtime	 = strtotime(Filter::getDBDateTime());
			$expiryTime	 = strtotime($model->bkgTrail->bkg_payment_expiry_time);
			if ($curtime > $expiryTime)
			{
				$url = Yii::app()->createUrl('bkpn/' . $id . '/' . $hash);
				$this->redirect($url);
//				throw new CHttpException(410, "Payment link expired");
			}

			$this->pageTitle		 = "Thank you for choosing Gozocabs!";
			$model->hash			 = $hash;
			$skipPromoEval			 = true;
			$this->evaluateCharges($model, $_POST, true, $skipPromoEval);
			$model->pickup_later_chk = Yii::app()->request->getParam('addData')['pickup_later_chk'];
			$model->drop_later_chk	 = Yii::app()->request->getParam('addData')['drop_later_chk'];

			$adrsData = Yii::app()->request->getParam('addData');
			if ($adrsData != '')
			{
				$revArr	 = array_reverse($adrsData);
				$key	 = array_search('Booking[pickup_later_chk]', array_column($revArr, 'name'));
				if ($key != false)
				{
					$model->pickup_later_chk = $revArr[$key]['value'];
				}

				$key = array_search('Booking[drop_later_chk]', array_column($revArr, 'name'));
				if ($key != false)
				{
					$model->drop_later_chk = $revArr[$key]['value'];
				}
			}

			if (!BookingRoute::validateAddress($model))
			{
				Logger::create('ERROR DATA =====>: ' . json_encode($model->getErrors()), CLogger::LEVEL_INFO);
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			/**
			 * log for update address successfully
			 */
			if ($_POST['isForword'] == 1)
			{
				$params = false;
				if (in_array($model->bkg_status, [2, 3, 5, 6, 7]))
				{
					$params['blg_ref_id'] = BookingLog::INITIAL_INFO_CHANGED;
				}
				BookingLog::model()->createLog($model->bkg_id, "Address update successfully", UserInfo::getInstance(), BookingLog::BOOKING_ADDRESS_SUCCESSFULLY, false, $params);
			}

			$obj	 = new Stub\common\Fare();
			$obj->setInvoiceData($model->bkgInvoice);
			$data	 = [
				'success'	 => true,
				'data'		 => $obj,
				'url'		 => $this->getURL(["booking/paymentreview", "id" => $model->bkg_id, "hash" => Yii::app()->shortHash->hash($model->bkg_id)])
			];
			echo CJSON::encode($data);
			Logger::trace("end");
		}
		catch (Exception $e)
		{
			ReturnSet::renderJSONException($e);
			Logger::trace("Errors.\n\t\t", CLogger::LEVEL_ERROR);
		}
	}

	/** @param Booking $model */
	public function evaluateAdditionalInfo($model, $save = false)
	{
		$adtModel	 = clone $model->bkgAddInfo;
		$adtModel->refresh();
		$splRemark	 = 'Carrier Requested for Rs.150';
		if ($model->bkgAddInfo->bkg_spl_req_carrier == 1 && $adtModel->bkg_spl_req_carrier != 1)
		{
			$model->bkgInvoice->bkg_additional_charge = $model->bkgInvoice->bkg_additional_charge + 150;

			$model->bkgInvoice->bkg_additional_charge_remark = ($model->bkgInvoice->bkg_additional_charge_remark == '') ? $splRemark : $model->bkgInvoice->bkg_additional_charge_remark . ', ' . $splRemark;
		}
		elseif ($model->bkgAddInfo->bkg_spl_req_carrier == 0 && $adtModel->bkg_spl_req_carrier != 0)
		{
			$model->bkgInvoice->bkg_additional_charge		 = $model->bkgInvoice->bkg_additional_charge - 150;
			$model->bkgInvoice->bkg_additional_charge_remark = trim(str_replace($splRemark, '', $model->bkgInvoice->bkg_additional_charge_remark));
			$model->bkgInvoice->bkg_additional_charge_remark = rtrim($model->bkgInvoice->bkg_additional_charge_remark, ',');
		}
		$lunchbreakrmk = ' Minutes for Journey Break';

		$exitingCharges = 150 * round($adtModel->bkg_spl_req_lunch_break_time / 30);
		if ($exitingCharges > 0)
		{
			$existingRemarks = $adtModel->bkg_spl_req_lunch_break_time . $lunchbreakrmk;
		}

		$newCharges = 150 * round($model->bkgAddInfo->bkg_spl_req_lunch_break_time / 30);
		if ($newCharges > 0)
		{
			$newRemarks = $adtModel->bkg_spl_req_lunch_break_time . $lunchbreakrmk;
		}
		$remarks = explode(", ", $model->bkgInvoice->bkg_additional_charge_remark);
		if ($newCharges != $exitingCharges)
		{
			$model->bkgInvoice->bkg_additional_charge	 += $newCharges - $exitingCharges;
			if ($existingRemarks != '' && ($key										 = array_search($existingRemarks, $remarks)))
			{
				unset($remarks[$key]);
			}
			if ($newRemarks != '')
			{
				$remarks[] = $newRemarks;
			}
		}
		$model->bkgInvoice->bkg_additional_charge = max([0, $model->bkgInvoice->bkg_additional_charge]);

		$model->bkgInvoice->bkg_additional_charge_remark = implode(", ", $remarks);

		$model->bkgInvoice->populateAmount(true, false, true, true, $model->bkg_agent_id);

		$model->bkgUserInfo->bkg_user_last_updated_on = new CDbExpression('NOW()');

		if (!$save)
		{
			return;
		}

		$transaction = DBUtil::beginTransaction();
		try
		{
			$userInfo	 = UserInfo::getInstance();
			$eventId	 = BookingLog::REMARKS_ADDED;

			$isAirportDestination = BookingSub::isAirportDestination($model->bkg_id);
			if ($isAirportDestination == 1)
			{
				$remark = 'Customer has a flight to catch. WE CANNOT BE LATE.';
				if ($model->bkg_instruction_to_driver_vendor != '')
				{
					$remark = " | " . $remark;
				}
				$model->bkg_instruction_to_driver_vendor = $model->bkg_instruction_to_driver_vendor . $remark;
				BookingLog::model()->createLog($model->bkg_id, $remark, $userInfo, $eventId);
			}
			$msgArr	 = array(trim(($model->bkgAddInfo->bkg_spl_req_carrier == '0' || $model->bkgAddInfo->bkg_spl_req_carrier == 0) ? "" : "Carrier Requested for Rs.150"), (($model->bkgAddInfo->bkg_spl_req_lunch_break_time != 0) ? $model->bkgAddInfo->bkg_spl_req_lunch_break_time . " Minutes for Journey Break" : ""));
			$message = $model->bkg_instruction_to_driver_vendor;
			foreach ($msgArr as $msg)
			{
				if ($msg != '')
				{
					continue;
				}
				if ($message == '')
				{
					$message = $msg;
				}
				else
				{
					$message .= "," . $msg;
				}
			}
			if ($msg != '')
			{
				$userInfo	 = UserInfo::getInstance();
				$eventId	 = BookingLog::REMARKS_ADDED;
				$remark		 = "Additional Instruction to Vendor/Driver: " . $msg;
				BookingLog::model()->createLog($model->bkg_id, $remark, $userInfo, $eventId);
			}

			if (!$model->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			if (!$model->bkgInvoice->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			if (!$model->bkgAddInfo->save())
			{
				throw new Exception(json_encode($model->bkgAddInfo->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			throw $ex;
		}
	}

	/** @param Booking $model */
	public function saveGstnDetails($model)
	{
		$model->bkgUserInfo->bkg_user_last_updated_on = new CDbExpression('NOW()');

		$transaction = DBUtil::beginTransaction();
		try
		{
			$model->bkgUserInfo->bkg_bill_contact	 = ($model->bkgUserInfo->bkg_bill_contact != '') ? $model->bkgUserInfo->bkg_bill_contact : $model->bkgUserInfo->bkg_contact_no;
			$model->bkgUserInfo->bkg_bill_email		 = ($model->bkgUserInfo->bkg_bill_email != '') ? $model->bkgUserInfo->bkg_bill_email : $model->bkgUserInfo->bkg_user_email;
			$model->bkgUserInfo->bkg_bill_state		 = ($model->bkgUserInfo->bkg_bill_state > 0) ? States::model()->getStateById($model->bkgUserInfo->bkg_bill_state) : null;
			$model->bkgUserInfo->bkg_bill_city		 = ($model->bkgUserInfo->bkg_bill_city > 0) ? Cities::getName($model->bkgUserInfo->bkg_bill_city) : null;
			$model->bkgUserInfo->bkg_bill_country	 = ($model->bkgUserInfo->bkg_bill_country != "") ? Countries::getByCode($model->bkgUserInfo->bkg_bill_country) : null;
			$model->bkgUserInfo->scenario			 = "updateGstin";
			if (!$model->bkgUserInfo->save())
			{
				throw new Exception(json_encode($model->bkgUserInfo->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			throw $ex;
		}
	}

	public function SummaryAdditionalInfo($model, $accordian)
	{
		$success	 = false;
		$returnSet	 = new ReturnSet();
		$result1	 = CActiveForm::validate($model->bkgAddInfo, null, false);
		if ($result1 != '[]')
		{
			$returnSet = ReturnSet::setException(new Exception(json_encode($model->bkgAddInfo->getErrors()), ReturnSet::ERROR_VALIDATION));
			goto skipProcess;
		}

		$this->evaluateAdditionalInfo($model);

		$msgArr = array(trim(($model->bkgAddInfo->bkg_spl_req_carrier == '0' || $model->bkgAddInfo->bkg_spl_req_carrier == 0) ? "" : "Carrier Requested for Rs.150"), (($model->bkgAddInfo->bkg_spl_req_lunch_break_time != '0' || $model->bkgAddInfo->bkg_spl_req_lunch_break_time != 0) ? $model->bkgAddInfo->bkg_spl_req_lunch_break_time . " Minutes for Journey Break" : ""));
		foreach ($msgArr as $msg)
		{
			if ($msg != '')
			{
				$userInfo	 = UserInfo::getInstance();
				$eventId	 = BookingLog::REMARKS_ADDED;
				$remark		 = "Additional Instruction to Vendor/Driver: " . $msg;
				if ($model->bkg_instruction_to_driver_vendor == '')
				{
					$model->bkg_instruction_to_driver_vendor = $msg;
				}
				else
				{
					$model->bkg_instruction_to_driver_vendor .= "," . $msg;
				}
				BookingLog::model()->createLog($model->bkg_id, $remark, $userInfo, $eventId);
			}
		}

		$transaction = DBUtil::beginTransaction();
		try
		{
			if (!$model->bkgInvoice->save())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			//$model->bkg_drop_pincode = $reqRtData[$brtid]['brt_to_pincode'];
			if (!$model->bkgAddInfo->save())
			{
				throw new Exception(json_encode($model->bkgAddInfo->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			if ($msg != '')
			{
				$bkgid		 = $model->bkg_id;
				$desc		 = "Additional Details added to Booking by user.";
				$userInfo	 = UserInfo::getInstance();
				$eventid	 = BookingLog::BOOKING_MODIFIED;
				BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid);
			}
			$GLOBALS["bkg_id"]	 = $model->bkg_id;
			$model->hash		 = Yii::app()->shortHash->hash($model->bkg_id);
			$GLOBALS["hash"]	 = Yii::app()->shortHash->hash($model->bkg_id);

			$returnSet->setStatus(true);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($e);
		}
		skipProcess:
		if ($returnSet->isSuccess())
		{
			$model->bkgInvoice->applyPromoCode($model->bkgInvoice->bkg_promo1_code);
			$model->bkgInvoice->calculateTotal();

			$totalAmount			 = $model->bkgInvoice->bkg_total_amount;
			$dueAmount				 = $model->bkgInvoice->bkg_due_amount;
			$additionalAmount		 = $model->bkgInvoice->bkg_additional_charge;
			$additionalAmountremarks = $model->bkgInvoice->bkg_additional_charge_remark;
			$taxWithoutConvFee		 = round($model->bkgInvoice->bkg_service_tax);

			$minPay = $model->bkgInvoice->calculateMinPayment();

			$data = [
				'success'					 => true,
				'id'						 => $model->bkg_id,
				'hash'						 => Yii::app()->shortHash->hash($model->bkg_id),
				'totalAmount'				 => $totalAmount,
				'dueAmount'					 => $dueAmount,
				'additionalAmount'			 => $additionalAmount,
				'additionalAmountremarks'	 => $additionalAmountremarks,
				'minPay'					 => $minPay,
				'servicetax'				 => $taxWithoutConvFee,
				'walletAmount'				 => $walletAmount,
				'creditUsed'				 => $creditUsed];
		}
		else
		{
			$data = $returnSet;
		}
		return $data;
	}

	/** @param Booking $model */
	public function evaluateCharges($model, $data, $save = false, $skipPromoEval = false)
	{
		$result				 = [];
		$additionalCharges	 = $data["adtData"];
		parse_str($additionalCharges, $result);
		$splCharges			 = $data["splData"];
		parse_str($splCharges, $result1);
		$confAddress		 = $data["addData"];
		$gstnData			 = $data["gstnData"];
		parse_str($gstnData, $strGstnDataArr);
		$arr				 = [];
		foreach ($confAddress as $key => $val)
		{
			$arr[$val["name"]] = $val["value"];
		}

		$str = http_build_query($arr);

		parse_str($str, $result2);

		$result = array_replace_recursive($result, $result1);

		$bookingRoutes = $result2["BookingRoute"];
		BookingRoute::completeUpdateAddresses($bookingRoutes, $model);

		$arrResult	 = BookingRoute::updateDistance($model);
		$success	 = true;
		if ($arrResult['oldBaseFare'] != $arrResult['fare']['netBaseFare'])
		{
			$desc = "Address Updated . Additional Kms:" . $arrResult['additional_km'] . ". Old Base Fare:₹" .
					$arrResult['oldBaseFare'] . ". New Base fare:₹" . $arrResult['fare']['netBaseFare'];
			BookingLog::model()->createLog($model->bkg_id, $desc, UserInfo::getInstance(), BookingLog::BOOKING_MODIFIED, false);
		}
		$model->bkgAddInfo->setAttributes($result["BookingAddInfo"]);
		$this->evaluateAdditionalInfo($model, $save);
		$model->bkgUserInfo->setAttributes($strGstnDataArr['BookingUser']);
		$this->saveGstnDetails($model);
		if (!$skipPromoEval)
		{
			$json		 = CJSON::decode(json_encode($data["promoData"]), false);
			$jsonObj	 = $json->content;
			$jsonMapper	 = new JsonMapper();
			/** @var Stub\common\Promotions $obj */
			$obj		 = $jsonMapper->map($jsonObj, new Stub\common\Promotions());

			$gozoCoins	 = $obj->gozoCoins;
			$promoCode	 = $obj->promo->code;
			$eventType	 = $obj->eventType;
			if ($model->bkg_reconfirm_flag == 0)
			{
				$model->bkgInvoice->evaluatePromoCoins($model, $eventType, $gozoCoins, $promoCode, $save);
			}
		}
	}

	public function getAirportValidation()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);

		try
		{
			/* @var $obj Stub\consumer\AirportRequest */
			$obj	 = $jsonMapper->map($jsonObj, new Stub\consumer\AirportRequest());
			/** @var Booking $model */
			$model	 = $obj->getModel();
			$dataSet = Cities::getCityDetails($model);
			if ($dataSet)
			{
				$responseData	 = new Stub\consumer\AirportResponse();
				$responseData->setData($dataSet);
				$response		 = Filter::removeNull($responseData);

				$returnSet->setStatus(true);
				$returnSet->setData($response);
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setData(['message' => 'Cab not available within that distance']);
				//$returnSet->setErrors('No Records Found', ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function actionFlashsale()
	{
		//$this->checkV2Theme();
		$this->pageTitle = "FLASH SALE!! GREAT PRICES FOR LAST MINUTE TRAVEL";
		$model			 = new CabAvailabilities;
		$qry			 = Yii::app()->request->getParam('CabAvailabilities');
		$type			 = Yii::app()->request->getParam('app');
		if ($type == 1)
		{
			$this->layout = "head";
		}
		if (isset($qry))
		{
			$model->attributes = $qry;
			if ($model->from_date != '')
			{
				$model->cav_date_time	 = DateTimeFormat::DatePickerToDate($model->from_date);
				$qry['cav_date_time']	 = $model->cav_date_time;
			}
		}
		
        $bookings    = $model->fetchFlashSale($qry);
        $pageSize    = Yii::app()->params['listPerPage'];
        $usersList	 = new CArrayDataProvider($bookings, array('pagination' => array('pageSize' => $pageSize),));
		$models		 = $usersList->getData();
        $this->render('flashsale', ['model' => $model, 'models' => $models , 'bkModel' => $bkModel, 'usersList' => $usersList]);
	}

	public function actionAutoMarkerAddress($city = '')
	{
		$this->renderAuto("bkMapLocation", ['city' => $city]);
	}

	/**
	 * @deprecated since version number 2021-09-02
	 * */
	public function actionChooseVehicleModel()
	{
		$this->pageTitle = "Vehicle Model Details";
		$fromCityId		 = Yii::app()->request->getParam('fromcityid');
		$scvId			 = Yii::app()->request->getParam('scvId');
		$bkgId			 = Yii::app()->request->getParam('bkgid');
		$baseAmt		 = Yii::app()->request->getParam('baseAmt');

		$cabModel	 = AreaSelectModel::model()->getByCityVhcModelList($fromCityId);
		$model		 = BookingTemp::model()->findByPk($bkgId);

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('showVehicleModel', array('model' => $model, 'baseAmt' => $baseAmt, 'asmmodel' => $cabModel, 'bkgid' => $bkgId, 'scvid' => $scvId, 'success' => $success), false, $outputJs);
	}

	/**
	 * @deprecated since version number 2021-09-02
	 */
	public function actionCalVehicleModelAmount()
	{
		$srvClassModel	 = Yii::app()->request->getParam('srvclassmodel');
		$baseamount		 = Yii::app()->request->getParam('baseamount');
		$city			 = Yii::app()->request->getParam('city');
		$discAmount		 = Yii::app()->request->getParam('discamount');
		$modelMarkupVal	 = AreaSelectModel::getByCityVhcModel($city, $srvClassModel);
		$amount			 = AreaSelectModel::model()->getCalculatedMarkupByBaseFare($modelMarkupVal, $baseamount, $discAmount);

		$data = ['extraamount' => $amount['calAmount'], 'discount' => $amount['discount']];
		if (Yii::app()->request->isAjaxRequest)
		{
			//$obj->data = json_decode($data);
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	public function actionGetQuoteByVehicleModel()
	{
		$scvId		 = Yii::app()->request->getParam('scvId');
		$bkgId		 = Yii::app()->request->getParam('bkgid');
		$asmModelid	 = Yii::app()->request->getParam('asmmodelid');

		$quote			 = new Quote();
		$bkgTempModel	 = BookingTemp::model()->findByPk($bkgId);
		$routeData		 = json_decode($bkgTempModel->bkg_route_data);
		$rteValue		 = $routeData[0];

		$routeModel						 = new BookingRoute();
		$routeModel->brt_from_city_id	 = $rteValue->brt_from_city_id;
		$routeModel->brt_to_city_id		 = $rteValue->brt_to_city_id;
		$routeModel->brt_from_location	 = $rteValue->brt_from_location;
		$routeModel->brt_to_location	 = $rteValue->brt_to_location;
		$routeModel->brt_pickup_datetime = $rteValue->brt_pickup_datetime;

		$routeModel->brt_from_latitude	 = $rteValue->brt_from_latitude;
		$routeModel->brt_from_longitude	 = $rteValue->brt_from_longitude;
		$routeModel->brt_to_latitude	 = $rteValue->brt_to_latitude;
		$routeModel->brt_to_longitude	 = $rteValue->brt_to_longitude;

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

		$routesArr[]			 = $routeModel;
		$booking_type			 = $bkgTempModel->bkg_booking_type;
		$pckageID				 = $bkgTempModel->bkg_package_id;
		$partnerId				 = $bkgTempModel->bkg_agent_id;
		$qrId					 = $bkgTempModel->bkg_qr_id;
		$bookingCPId			 = ($partnerId > 0) ? $partnerId : Yii::app()->params['gozoChannelPartnerId'];
		$quote->routes			 = $routesArr;
		$quote->tripType		 = ($booking_type == 5 && $pckageID == '') ? 3 : $booking_type; // package
		//new line added for package error price;
		$quote->tripType		 = 3;
		$quote->partnerId		 = $bookingCPId;
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
		$quote->setCabTypeArr(Quote::Platform_Admin);
		$quote->vehicleModelId	 = $asmModelid;
		$qt						 = $quote->getQuote($scvId);
		$quoteData				 = $qt[$scvId];
		$routeRates				 = $quoteData->routeRates;
		$routesArr				 = $quoteData->routes;
		$routeDistance			 = $quoteData->routeDistance;
		$routeDuration			 = $quoteData->routeDuration;

		$baseamount			 = $routeRates->baseAmount;
		$amount				 = $routeRates->totalAmount;
		$tax				 = $routeRates->gst;
		$discount			 = $routeRates->discount;
		$tollTax			 = $routeRates->tollTaxAmount;
		$stateTax			 = $routeRates->stateTax;
		$promoCode			 = $routeRates->promoRow['prm_code'];
		$coinDiscount		 = $routeRates->coinDiscount;
		$driverAllowance	 = $routeRates->driverAllowance;
		$tollTaxIncluded	 = $routeRates->isTollIncluded;
		$stateTaxIncluded	 = $routeRates->isStateTaxIncluded;

		$success = true;
		$data	 = ['baseamount'		 => $baseamount, 'amount'			 => $amount, 'discount'			 => $discount, 'taxgst'			 => $tax,
			'tollTax'			 => $tollTax, 'stateTax'			 => $stateTax, 'promocode'			 => $promoCode, 'coinDiscount'		 => $coinDiscount,
			'driverAllowance'	 => $driverAllowance, 'tollIncluded'		 => $tollTaxIncluded, 'stateTaxIncluded'	 => $stateTaxIncluded];
		if (Yii::app()->request->isAjaxRequest)
		{
			$obj->data = json_decode($data);
			echo CJSON::encode($data);
			return $data;
			Yii::app()->end();
		}
	}

	/**
	 *
	 * @deprecated since version 06/12/2021
	 */
	public function actionAddFromCityId()
	{
		$ctyId		 = Yii::app()->request->getParam("cityid");
		$scvId		 = Yii::app()->request->getParam('scvid');
		$baseamt	 = Yii::app()->request->getParam('baseamt');
		$srvClass	 = Yii::app()->request->getParam('srvclass');
		$vctId		 = SvcClassVhcCat::model()->getCatIdBySvcid($scvId);

		$carModelsSelectTier = ServiceClassRule::model()->getCabByClassId($srvClass, $baseamt, $vctId);
		if (Yii::app()->request->isAjaxRequest)
		{
			echo CJSON::encode($carModelsSelectTier);
			return $carModelsSelectTier;
			Yii::app()->end();
		}
	}

	/**
	 * {"bookingId":1585297,"promo":{"code":"",},"gozoCoins":10,"wallet":10}
	 *
	 * */
	public function actionApplyPromo()
	{
		$returnSet = new ReturnSet();
		try
		{
			$json		 = CJSON::decode(json_encode($_POST), false);
			$jsonObj	 = $json->content;
			$jsonMapper	 = new JsonMapper();
			/** @var Stub\common\Promotions $obj */
			$obj		 = $jsonMapper->map($jsonObj, new Stub\common\Promotions());
			$bkgId		 = $obj->bookingId;
			$gozoCoins	 = $obj->gozoCoins;
			$promoCode	 = $obj->promo->code;
			$eventType	 = $obj->eventType;
			$userId		 = UserInfo::getUserId();
			/** @var  BookingUser $bUserModel */
			$bkgModel	 = Booking::model()->findByPk($bkgId);
			if ($bkgModel->bkgInvoice->bkg_extra_discount_amount > 0)
			{
				throw new Exception("Cannot apply promotion or coin. Sience the best price is already applied", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}
			if ($bkgModel->bkg_reconfirm_flag == 1)
			{
				throw new Exception("Cannot apply promotions. Booking already confirmed", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}

			$bUserModel	 = $bkgModel->bkgUserInfo;
			$bkgUserId	 = $bUserModel->bkg_user_id;
			if ($userId != $bkgUserId && $eventType == 3)
			{
				throw new Exception("Please login with the account used to create the booking", ReturnSet::ERROR_UNAUTHORISED);
			}
			BookingInvoice::evaluatePromoCoins($bkgModel, $eventType, $gozoCoins, $promoCode, true);
			if ($bkgModel->bkgInvoice->bkg_addon_charges == 0)
			{
				goto skipAddons;
			}

			$cancelRuleId	 = $bkgModel->bkgPref->bkg_cancel_rule_id;
			$CPdetails		 = CancellationPolicyDetails::model()->findByPk($cancelRuleId);
			if ($CPdetails != '')
			{
				$bkgModel->bkgInvoice->addonLabel = $CPdetails->cnp_label;
			}

			skipAddons:
			$response	 = new Stub\common\Promotions();
			$response->setData($bkgModel->bkgInvoice, $eventType);
			$message	 = $obj->getMessage($bkgModel->bkgInvoice, $eventType);

			$returnSet->setStatus(true);
			$returnSet->setData($response);
			$returnSet->setMessage($message);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		echo CJSON::encode($returnSet);
	}

	public function actionApplyWallet()
	{
		$transaction = null;
		$returnSet	 = new ReturnSet();
		try
		{
			$json		 = CJSON::decode(json_encode($_POST), false);
			$jsonObj	 = $json->content;
			$jsonMapper	 = new JsonMapper();
			/** @var Stub\common\Promotions $obj */
			$obj		 = $jsonMapper->map($jsonObj, new Stub\common\Promotions());
			$bkgId		 = $obj->bookingId;
			$walletBal	 = $obj->wallet;
			$eventType	 = $obj->eventType;
			$userId		 = UserInfo::getUserId();

			/** @var  BookingUser $bUserModel */
			$bUserModel	 = BookingUser::model()->getByBkgId($bkgId);
			$bkgUserId	 = $bUserModel->bkg_user_id;
			$hash		 = Yii::app()->shortHash->hash($bkgId);
			if ($userId != $bkgUserId)
			{
				throw new Exception("Please login with the account used to create the booking", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}

			$model		 = Booking::model()->findByPk($bkgId);
			$bkgInvModel = $model->bkgInvoice;
			//$transaction = DBUtil::beginTransaction();


			BookingInvoice::applyWallet($bkgId, $eventType, $userId, $walletBal);
			$bkgInvModel->refresh();
			$userWalletBalance = UserWallet::model()->getBalance(UserInfo::getUserId());
			if ($bkgInvModel->bkg_is_wallet_selected > 0 && ($eventType == 7 ))
			{
				echo json_encode(['success'			 => true, 'walletUsed'		 => $bkgInvModel->bkg_wallet_used,
					'remainingWallet'	 => ($userWalletBalance - $bkgInvModel->bkg_wallet_used)]);
				Yii::app()->end();
			}


			$transaction = DBUtil::beginTransaction();

			$totalAdvance	 = $bkgInvModel->bkg_net_advance_amount + $bkgInvModel->bkg_wallet_used;
			$minPayment		 = $bkgInvModel->calculateMinPayment();
			if ($model->bkg_status == 15)
			{
				$bkgInvModel->applyPromoCode($bkgInvModel->bkg_promo1_code);
			}

			if (($totalAdvance > $minPayment || ($model->bkgPref->bpr_rescheduled_from > 0 && $totalAdvance < $minPayment && $totalAdvance > 0)) && ($eventType != 6 ))
			{
				$result2 = CActiveForm::validate($bkgInvModel);

				if ($result2 !== '[]')
				{
					throw new Exception(json_encode($bkgInvModel->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
				if ($model->bkgInvoice->bkg_is_wallet_selected == 1 && $model->bkgInvoice->bkg_wallet_used > 0)
				{
					$retSet = $model->useWalletPayment();
					if (!$retSet->getStatus())
					{
						throw new Exception(json_encode($retSet->getErrors()), $retSet->getErrorCode());
					}

					$bkgInvModel->refresh();
					$model->refresh();
					$model->bkgUserInfo->bkg_user_last_updated_on	 = new CDbExpression('NOW()');
					$model->save();
					$model->bkgInvoice->save();
					$sendConfirmMessages							 = true;
					$isReschedule									 = false;
//reschedulebooking new
					if ($model->bkgPref->bpr_rescheduled_from > 0)
					{
						$oldModel = Booking::model()->findByPk($model->bkgPref->bpr_rescheduled_from);
						if (!in_array($oldModel->bkg_status, [9, 10]))
						{
							$sendConfirmMessages = false;
							$isReschedule		 = true;
						}
					}
					$model->confirm(true, $sendConfirmMessages);
//					if ($model->bkg_status == 2)
//					{
//						$model->confirmMessages();
//					}
					DBUtil::commitTransaction($transaction);

					$url = Yii::app()->createUrl('booking/summary/id/' . $model->bkg_id . '/hash/' . $hash);
					if ($isReschedule)
					{
						$url = Yii::app()->createUrl('booking/summary/id/' . $model->bkg_id . '/hash/' . $hash . '/isreschedule/1');
					}
					$this->redirect($url);
					Yii::app()->end();
				}
				else
				{
					Logger::error("Booking confirmation failed. Unable to validate payment mode");
					throw new Exception("Payment mode validation failed..", 400);
				}
			}
			else
			{
				$objFare = new Stub\common\Fare();
				$objFare->setInvoiceData($bkgInvModel);

//				$response	 = new Stub\common\Promotions();
//				$response->setData($modelArr['model'], $modelArr['eventType']);
//				$returnSet->setData($response);
//				$message	 = $obj->getMessage($modelArr['model'], $modelArr['eventType']);
//				$returnSet->setMessage($message);

				$url = Yii::app()->createUrl('booking/paymentreview/id/' . $model->bkg_id . '/hash/' . $hash);
				$this->redirect($url);
				Yii::app()->end();
			}

			DBUtil::commitTransaction($transaction);
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
		}
		echo CJSON::encode($returnSet);
	}

	public function actionAutoaddress()
	{
		$request		 = Yii::app()->request;
		$booking		 = $request->getParam('Booking', null);
		$bookingRoutes	 = $request->getParam('BookingRoute', null);

		if ($booking != null && $bookingRoutes != null)
		{
			$bkgid		 = $booking['bkg_id'];
			$bkgModel	 = Booking::model()->findByPk($booking['bkg_id']);
			$transaction = Filter::beginTransaction();
			try
			{

				BookingRoute::updateAddresses($bookingRoutes, $bkgModel);
				$bkgModel->refresh();
				$arrResult	 = BookingRoute::updateDistance($bkgModel);
				$success	 = true;
				$desc		 = "Address Updated . Additional Kms:" . $arrResult['additional_km'] . ". Old Base Fare:₹" .
						$arrResult['oldBaseFare'] . ". New Base fare:₹" . $arrResult['fare']['netBaseFare'];
				BookingLog::model()->createLog($bkgid, $desc, UserInfo::getInstance(), BookingLog::BOOKING_MODIFIED, false);
				Filter::commitTransaction($transaction);
			}
			catch (Exception $e)
			{
				$success = false;
				Filter::rollbackTransaction($transaction);
			}
			echo json_encode(['success' => $success, 'data' => $arrResult]);
			Yii::app()->end();
		}
		$this->render('autoAddressWidget', [], false, true);
	}

	public function actionUpdateRouteAddress()
	{
		$request = Yii::app()->request;

		if (!$request->isPostRequest)
		{
			goto render;
		}
		$booking = $request->getParam('Booking', null);
		$routes	 = $request->getParam('BookingRoute', null);

		$bkgModel	 = Booking::model()->findByPk($booking['bkg_id']);
		$transaction = Filter::beginTransaction();
		try
		{
			Logger::info("Update Route Address Start");

			BookingRoute::updateRouteAddresses($bkgModel, $routes);
			Logger::trace("BookingId: " . $booking['bkg_id'] . " route address updated");
			$bkgModel->refresh();
			$arrResult	 = BookingRoute::updateDistance($bkgModel);
			Logger::trace("BookingId: " . $booking['bkg_id'] . " route rates  updated =>" . json_encode($arrResult));
			$success	 = true;
			if (!empty($arrResult))
			{
				$desc = "Address Updated . Additional Kms:" . $arrResult['additional_km'] . ". Old Base Fare:₹" .
						$arrResult['oldBaseFare'] . ". New Base fare:₹" . $arrResult['fare']['baseFare'];
			}
			else
			{
				$desc = "Address Updated . No Additional Kms";
			}
			BookingLog::model()->createLog($booking['bkg_id'], $desc, UserInfo::getInstance(), BookingLog::BOOKING_MODIFIED, false);
			Logger::trace("BookingId: " . $booking['bkg_id'] . " route address updated Log added");
			Filter::commitTransaction($transaction);

			//Notify vendors on pickup address update only
			$tripId		 = $bkgModel->bkg_bcb_id;
			$dataexist	 = BookingVendorRequest::getPreferredVendorbyBooking($tripId);

			if (!$dataexist && $bkgModel->bkgPref->bkg_is_gozonow == 1)
			{
//				BookingCab::gnowNotify($tripId);
			}
		}
		catch (Exception $e)
		{
			Filter::rollbackTransaction($transaction);
			$success = false;
			ReturnSet::setException($e);
		}
		echo json_encode(['success' => $success, 'data' => $arrResult]);
		Yii::app()->end();

		render:
		$this->render('autoAddressWidget', [], false, true);
	}

	public function actionDownloadDocs()
	{
		try {
			$success	 = false;
			$filename	 = Yii::app()->request->getParam('filename');
			$id			 = Yii::app()->request->getParam('birId');
			$biqmodel	 = BookingInvoiceRequest::model()->findByPk($id);
			if ($biqmodel->bir_download_link == $filename && ($biqmodel->bir_request_type == 1 || $biqmodel->bir_request_type == 3))
			{
				$filePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . "invoice" . DIRECTORY_SEPARATOR . $filename;
				if (file_exists($filePath))
				{
					Yii::app()->request->downloadFile($filePath);
				}
			}
			if ($biqmodel->bir_download_link == $filename && $biqmodel->bir_request_type == 2)
			{
				$path = PUBLIC_PATH . "/uploads/sheet/" . $filename;

				//$success = true;
				header('Content-Type: text/csv');
				header('Content-disposition: attachment; filename=' . $filename);
				header('Content-Length: ' . filesize($filename));
				readfile($path);
				exit;
			}

		} catch (Exception $ex) {
			echo $ex->getMessage();
			exit;
		}
	}

	public function actionCheckTripStatus()
	{
		$this->checkV3Theme();
		$success = false;
		$bkgId	 = Yii::app()->request->getParam('booking_id');
		$model	 = Booking::model()->findByPk($bkgId);

		$currentTime = time();
		$pickupTime	 = strtotime($model->bkg_pickup_date);
		if ($currentTime > $pickupTime)
		{
			$message = "Sorry, we are unable to process the Booking Cancellation request as the pickup time of the Booking has already elapsed. We are arranging a call back request for you. Our Customer Support team would be happy to help you. Thank you.";
			goto skiptripstatus;
		}
		$tripTimeDiff		 = Filter::getTimeDiff($model->bkg_pickup_date);
		$bkgAmount			 = $model->bkgInvoice->bkg_total_amount;
		$advanceAmt			 = PaymentGateway::model()->getTotalAdvance($model->bkg_id);
		$totalAdvance		 = ($advanceAmt != NULL) ? $advanceAmt : 0;
		$rule				 = 6;
		$arrRules			 = BookingPref::model()->getCancelChargeRule($rule);
		$cancellationCharge	 = BookingPref::model()->calculateCancellationCharge($arrRules, $bkgAmount, $tripTimeDiff, $totalAdvance, 23, '', false);
		//$cancelCharge		 = ($cancellationCharge != NULL) ? $cancellationCharge : 0;
		$cancelFee			 = CancellationPolicy::initiateRequest($model);

		$cancelCharge = $cancelFee->charges;

		$refund	 = $totalAdvance - $cancelCharge;
		$success = true;
		$message = "Your total advance is  ₹" . round($totalAdvance) . " and If you cancel booking, your cancellation fees will be: ₹" . round($cancelCharge) . " and refund amount will be ₹" . round($refund);
		skiptripstatus:
		$return	 = ['success' => $success, 'message' => $message];
		if (Yii::app()->request->isAjaxRequest)
		{
			echo CJSON::encode($return);
			Yii::app()->end();
		}
		Yii::app()->end();
	}

	public function actionApplyaddon()
	{
		$returnSet = new ReturnSet();
		try
		{
			$addOnId	 = Yii::app()->request->getParam('addonId');
			$bkgId		 = Yii::app()->request->getParam('bkgId');
			$addonType	 = Yii::app()->request->getParam('addonType');
			$bkgModel	 = Booking::model()->findByPk($bkgId);
			$userId		 = UserInfo::getUserId();
			$bUserModel	 = BookingUser::model()->getByBkgId($bkgId);
			$bkgUserId	 = $bUserModel->bkg_user_id;
			if ($userId != $bkgUserId)
			{
				throw new Exception("Please login with the account used to create the booking", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}
			if ($bkgModel != "")
			{
				$bkgModel->bkgInvoice->applyAddon($addOnId, $addonType);
				if ($addonType == 1)
				{
					$cancelRuleId							 = AddonCancellationPolicy::getCancelRuleById($addOnId);
					$bkgModel->bkgPref->bkg_cancel_rule_id	 = ($cancelRuleId) ? $cancelRuleId : 1;
					$bkgModel->bkgPref->save();
				}
				if ($addonType == 2)
				{
					$cabType = ($addOnId) ? AddonCabModels::model()->findByPk($addOnId)->acm_svc_id_to : $bkgModel->bkg_vehicle_type_id;
					if ($addOnId == 0)
					{
						$cabType = SvcClassVhcCat::model()->findByPk($bkgModel->bkg_vehicle_type_id)->scv_parent_id;
					}
					$bkgModel->bkg_vehicle_type_id	 = ($cabType > 0) ? $cabType : $bkgModel->bkg_vehicle_type_id;
					$bkgModel->bkg_vht_id			 = SvcClassVhcCat::model()->findByPk($cabType)->scv_model;
					$bkgModel->save();
				}
				$this->actionApplyPromo();
				goto render;
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		echo CJSON::encode($returnSet);
		render:
	}

	public function actionGozoNowShowVndListV0()
	{
		if (!Config::checkGozoNowEnabled())
		{
			throw new CHttpException(400, 'Cannot proceed');
		}
		$bkgId	 = Yii::app()->request->getParam('id');
		$hashval = Yii::app()->request->getParam('hash');

		$bkgModel = Booking::model()->findByPk($bkgId);
		if (!$bkgModel)
		{
			throw new CHttpException(400, 'This booking does not exist.');
		}

		$hash = Yii::app()->shortHash->hash($bkgId);
		if ($hash != $hashval)
		{
			throw new CHttpException(400, 'Invalid data');
		}
		if ($bkgModel->bkgPref->bkg_is_gozonow != 1)
		{
			throw new CHttpException(400, 'This booking does not comes under Gozo Now scenario.');
		}
		$dataexist = BookingVendorRequest::getPreferredVendorbyBooking($bkgModel->bkg_bcb_id);

		if ($dataexist)
		{
			$bkgId	 = $bkgModel->bkg_id;
			$hash	 = Yii::app()->shortHash->hash($bkgModel->bkg_id);
			$url	 = Yii::app()->createAbsoluteUrl('bkpn/' . $bkgId . '/' . $hash);
			$this->redirect($url);
			Yii::app()->end();
		}


		$data = BookingVendorRequest::getGNowAcceptedList($bkgModel->bkg_bcb_id);
		$this->renderAuto("bkGZNowBidList", ['model' => $bkgModel, 'data' => $data, 'hash' => $hash]);
	}

	public function actionGetGNowReqDataOld()
	{
		if (!Config::checkGozoNowEnabled())
		{
			throw new CHttpException(400, 'Cannot proceed');
		}
		$bkgid		 = Yii::app()->request->getParam('booking_id');
		$bkgModel	 = Booking::model()->findByPk($bkgid);
		$tripId		 = $bkgModel->bkg_bcb_id;
		if ($bkgModel->bkgPref->bkg_is_gozonow != 1)
		{
			throw new CHttpException(400, 'This booking does not comes under Gozo Now scenario.');
		}

		if (in_array($bkgModel->bkg_status, [9, 10, 8]))
		{
			$timerStat = [
				'stepValidation' => '0_0_1',
				'message'		 => 'The request is cancelled. Please contact customer care'
			];

			echo json_encode(['cnt' => 0, 'timerStat' => $timerStat]);
			Yii::app()->end();
		}

		if (in_array($bkgModel->bkg_status, [6, 7]))
		{
			$timerStat = [
				'stepValidation' => '0_0_1',
				'message'		 => 'The booking is already processed'
			];

			echo json_encode(['cnt' => 0, 'timerStat' => $timerStat]);
			Yii::app()->end();
		}
		$hashval = Yii::app()->request->getParam('hash');
		$hash	 = Yii::app()->shortHash->hash($bkgid);
		if ($hash != $hashval)
		{
			throw new CHttpException(400, 'Invalid data');
		}
		$bkgId		 = $bkgModel->bkg_id;
		$dataexist	 = BookingVendorRequest::getPreferredVendorbyBooking($tripId);
		if ($dataexist)
		{
			if ($bkgModel->bkg_drop_address == $bkgModel->bkgToCity->cty_garage_address)
			{
				$this->redirect(['booking/address', "bkgid" => $bkgId, 'hash' => $hash]);
			}

			$hash	 = Yii::app()->shortHash->hash($bkgId);
			$url	 = Yii::app()->createUrl('booking/review', ['bkgid' => $bkgId, 'hash' => $hash]);
//			$url	 = Yii::app()->createAbsoluteUrl('bkpn/' . $bkgId . '/' . $hash);
			echo json_encode(['type' => 'url', 'url' => $url]);
		}
		else
		{
			$bdIds		 = Yii::app()->request->getParam('bdids');
			$data		 = BookingVendorRequest::getGNowAcceptedData($tripId);
			$returnData	 = [];

			$rowCount = $data->getRowCount();

//			$rowCount	 = 3;
			$createDate = $bkgModel->bkg_create_date;
//			$createDate	 = '2022-03-25 15:29:00'; //date('Y-m-d H:i:s', strtotime('- 50 SECOND'));

			$timerStat		 = [];
			$cachekey		 = "getGNowAcceptedListHtml_{$tripId}_{$rowCount}";
			$timerLog		 = [];
			$timerLogJson	 = BookingTrail::getGnowTimerLog($bkgId);
			if (!$timerLogJson)
			{
				$startTime = Filter::getDBDateTime();

				$timerLog		 = ['count' => 1, 'startTime' => $startTime];
				$timerLogJson	 = json_encode($timerLog);
				BookingTrail::updateGnowTimerLog($bkgId, $timerLogJson);
			}
			$bidrows = $data->readAll();

			$res = BookingVendorRequest::getBidTimerStat($bidrows, $timerLogJson);

			$returnTimer	 = '';
			$step1DiffSecs	 = $res['durationRemaining'];
			if ($step1DiffSecs > 0 && $timerLog['count'] === 1)
			{
				$userInfo	 = UserInfo::getInstance();
				$desc		 = "Bid timer started";
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, BookingLog::BID_TIMER_START, false);
			}
			if ($res['timerRunning'] == 'timer1')
			{
				$returnTimer = $this->renderPartial('gnowTimerStep1', ['model' => $bkgModel, 'step1DiffSecs' => $step1DiffSecs], true);
			}
			if ($res['timerRunning'] == 'timer2')
			{
				$returnTimer = $this->renderPartial('gnowTimerStep2', ['model' => $bkgModel, 'step2DiffSecs' => $step1DiffSecs], true);
			}
			$timerStat = ['type'				 => 'html',
				'dataHtml'			 => $returnTimer,
				'step1DiffSecs'		 => $step1DiffSecs,
				'durationRemaining'	 => $res['durationRemaining'],
				'timerRunning'		 => $res['timerRunning'],
				'stepValidation'	 => $res['stepValidation']
			];

			if ($timerStat['durationRemaining'] == 0)
			{
				BookingLog::gnowOfferSearchTimeout($bkgid, $timerStat);
			}

			if ($returnData == false && $rowCount > 0)
			{
				$dataList	 = BookingVendorRequest::getGNowAcceptedList($tripId);
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
						$returnData[$value['bvr_id']] = $this->renderPartial('bkGZNowBidListTemplate', ['value' => $value], true);
					}
				}


				if ($timerStat['stepValidation'] == '1_1_2')
				{
					BookingLog::gnowOfferDisplayedToCustomer($bkgid);
				}

//				$returnData['html']	 = $datahtml;
				Yii::app()->cache->set($cachekey2, $returnData, 6000);
				echo json_encode(['type' => 'html', 'dataHtml' => $returnData, 'cnt' => $countBid, 'bidIds' => $bidIds, 'timerStat' => $timerStat]);
				Yii::app()->end();
			}

			echo json_encode(['cnt' => $rowCount, 'timerStat' => $timerStat]);
		}
		Yii::app()->end();
	}

	public function actionGetGNowReqData()
	{
		if (!Config::checkGozoNowEnabled())
		{
			throw new CHttpException(400, 'Cannot proceed');
		}
		$bkgid		 = Yii::app()->request->getParam('booking_id');
		$bkgModel	 = Booking::model()->findByPk($bkgid);
		$tripId		 = $bkgModel->bkg_bcb_id;
		if ($bkgModel->bkgPref->bkg_is_gozonow != 1)
		{
			throw new CHttpException(400, 'This booking does not comes under Gozo Now scenario.');
		}

		if (in_array($bkgModel->bkg_status, [9, 10, 8]))
		{
			$timerStat = [
				'stepValidation' => '0_0_1',
				'message'		 => 'The request is cancelled. Contact customer care'
			];

			echo json_encode(['cnt' => 0, 'timerStat' => $timerStat]);
			Yii::app()->end();
		}

		if (in_array($bkgModel->bkg_status, [6, 7]))
		{
			$timerStat = [
				'stepValidation' => '0_0_1',
				'message'		 => 'The booking is already processed'
			];

			echo json_encode(['cnt' => 0, 'timerStat' => $timerStat]);
			Yii::app()->end();
		}
		$hashval = Yii::app()->request->getParam('hash');
		$hash	 = Yii::app()->shortHash->hash($bkgid);
		if ($hash != $hashval)
		{
			throw new CHttpException(400, 'Invalid data');
		}
		$bkgId		 = $bkgModel->bkg_id;
		$vndNotified = BookingVendorRequest::getRecordCountByBkg($bkgId);

//		$dataexist = BookingVendorRequest::getPreferredVendorbyBooking($tripId);
//		if ($dataexist)
//		{
//			if ($bkgModel->bkg_drop_address == $bkgModel->bkgToCity->cty_garage_address)
//			{
//				$this->redirect(['booking/address', "bkgid" => $bkgId, 'hash' => $hash]);
//			}
//
//			$hash	 = Yii::app()->shortHash->hash($bkgId);
//			$url	 = Yii::app()->createUrl('booking/review', ['bkgid' => $bkgId, 'hash' => $hash]);
////			$url	 = Yii::app()->createAbsoluteUrl('bkpn/' . $bkgId . '/' . $hash);
//
//			$timerStat = [
//				'vndNotified' => $vndNotified
//			];
//
//			echo json_encode(['type' => 'url', 'url' => $url, 'timerStat' => $timerStat]);
//		}
//		else
		{
			$bdIds		 = Yii::app()->request->getParam('bdids');
			$data		 = BookingVendorRequest::getGNowAcceptedData($tripId);
			$returnData	 = [];

			$rowCount = $data->getRowCount();

			$createDate		 = $bkgModel->bkg_create_date;
			BookingTrail::updateGnowTimerCustomerLastSync($bkgId);
			$timerStat		 = [];
			$cachekey		 = "getGNowAcceptedListHtml_{$tripId}_{$rowCount}";
			$timerLog		 = [];
			$timerLogJson	 = BookingTrail::getGnowTimerLog($bkgId);
			if (!$timerLogJson)
			{
				$startTime = Filter::getDBDateTime();

				$timerLog		 = ['count' => 1, 'startTime' => $startTime];
				$timerLogJson	 = json_encode($timerLog);
				BookingTrail::updateGnowTimerLog($bkgId, $timerLogJson);
				$timerCount		 = $timerLog['count'];
				if ($timerCount === 1)
				{

					$userInfo	 = UserInfo::getInstance();
					$desc		 = "Bid timer started";
					BookingLog::model()->createLog($bkgId, $desc, $userInfo, BookingLog::BID_TIMER_START, false);
				}
			}
			$bidrows = $data->readAll();
			$res	 = [];
//			$res = BookingVendorRequest::getBidTimerStat($bidrows, $timerLogJson);

			$returnTimer = '';

			$pickupDiffMinutes = Filter::getTimeDiff($bkgModel->bkg_pickup_date);

			$expiryTime			 = $bkgModel->checkGozoNowExpiryTime();
			$expiryDiffSeconds	 = Filter::getTimeDiffinSeconds($expiryTime);
			$expiryDiffMinutes	 = Filter::getTimeDiff($expiryTime);

			//$minimumPickupDuration	 = Config::getMinGozoNowPickupDuration(1);
			$minimumPickupDuration	 = Config::getMinGozoNowPickupDuration($bkgModel->bkg_booking_type, '', $bkgModel->bkgTrail->bkg_create_user_type);
			$timerMaxSeconds		 = 10 * 60;

			$timerLeftShow = $expiryDiffSeconds % $timerMaxSeconds;

			$timerLog	 = json_decode($timerLogJson, true);
			$timerCount	 = $timerLog['count'];

			$createDate		 = $timerLog['startTime'];
			$createDiffSecs	 = -1 * Filter::getTimeDiffinSeconds($createDate);

			$durationRemaining	 = $pickupDiffMinutes - $minimumPickupDuration;
//			$durationRemaining	 =$expiryDiffMinutes;
//			$step1DiffSecs		 = (($timerMaxSeconds - $createDiffSecs ) > 0) ? ($timerMaxSeconds - $createDiffSecs) : 0;
			$step1DiffSecs		 = (($timerLeftShow ) > 0) ? ($timerLeftShow) : 0;
			$step1DiffSecs		 = max(min(($durationRemaining * 60), $step1DiffSecs), 0);

			$res['stepValidation']	 = '1_1_1';
			$res['timerRunning']	 = '1';

			if ($step1DiffSecs > 0)
			{
				$res['timerRunning'] = 'timer1';
			}


			if ($res['timerRunning'] == 'timer1')
			{
//				$returnTimer = $this->renderPartial('gnowTimerStep1', ['model' => $bkgModel, 'step1DiffSecs' => $step1DiffSecs], true);
			}
			$timerStat = ['type'				 => 'html',
				'dataHtml'			 => $returnTimer,
				'step1DiffSecs'		 => $step1DiffSecs,
				'durationRemaining'	 => $durationRemaining,
				'timerRunning'		 => $res['timerRunning'],
				'stepValidation'	 => $res['stepValidation'],
				'vndNotified'		 => $vndNotified
			];

			if ($durationRemaining <= 0)
			{
				$timerStat = [
					'stepValidation' => '0_0_1',
					'message'		 => "The pickup time needs to be at least $minimumPickupDuration minutes from now"
				];
				echo json_encode(['cnt' => 0, 'timerStat' => $timerStat]);
				Yii::app()->end();
			}
			if ($durationRemaining < 0)
			{
				BookingLog::gnowOfferSearchTimeout($bkgid, $timerStat);
			}

			if ($returnData == false && $rowCount > 0)
			{
				$dataList	 = BookingVendorRequest::getGNowAcceptedList($tripId);
				$countBid	 = $rowCount;
				$data		 = $dataList;
				$bidIds		 = [];
				if ($data['success'])
				{
					$existingBidIds = explode(',', $bdIds);
					foreach ($data['data'] as $key => $value)
					{
//					$bidIds[] = $value['bvr_id'];
						$bidIdVal	 = 'bid' . '_' . $value['bvr_id'] . '_' . $value['bvr_bid_amount'];
						$bidIds[]	 = $bidIdVal;

						if (in_array($bidIdVal, $existingBidIds))
						{
							continue;
						}
						$returnData[$bidIdVal] = $this->renderPartial('bkGZNowBidListTemplate', ['value' => $value], true);
					}
					$bidArrDiff	 = array_diff($existingBidIds, $bidIds);
					$removeIds	 = [];
					if (sizeof($bidArrDiff) > 0 && $bdIds != '')
					{
//						$countBid = $countBid - sizeof($bidArrDiff);
						$removeIds = array_values($bidArrDiff);
					}
				}


				Yii::app()->cache->set($cachekey2, $returnData, 6000);
				echo json_encode(['type' => 'html', 'dataHtml' => $returnData, 'cnt' => $countBid, 'bidIds' => $bidIds, 'removeIds' => $removeIds, 'timerStat' => $timerStat]);
				Yii::app()->end();
			}

			echo json_encode(['cnt' => $rowCount, 'timerStat' => $timerStat]);
		}
		Yii::app()->end();
	}

	public function actionProcessGNowbidaccept()
	{
		if (!Config::checkGozoNowEnabled())
		{
			throw new CHttpException(400, 'Cannot proceed');
		}
		$bvrid		 = Yii::app()->request->getParam('bidId');
		$bookingId	 = Yii::app()->request->getParam('bookingId');
		$hash		 = Yii::app()->request->getParam('hash');

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
			if ($hash != Yii::app()->shortHash->hash($bkgId))
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
			if ($bkgModel->bkgPref->bkg_is_gozonow != 1)
			{
				throw new CHttpException(400, 'This booking does not comes under Gozo Now scenario.');
			}

			BookingVendorRequest::cancelPreferredVendor($bkgId);
			$dataexist = BookingVendorRequest::getPreferredVendorbyBooking($bkgModel->bkg_bcb_id);
			if (!$dataexist)
			{

				$success = $bvrModel->updatePreferredVendor();
				$model	 = BookingSub::processForGNowFromVendorAmount($bkgId, $bvrModel->bvr_bid_amount, $bvrModel->bvr_vendor_id);
				if (!$model)
				{
					throw new Exception("Some error occured while processing the booking", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}
			}
			DBUtil::commitTransaction($transaction);

			$url = Yii::app()->createUrl('booking/gnowDropAddress', ['bkgid' => $bkgId, 'hash' => $hash]);
			/** @var Booking $bkgModel */
			if ($bkgModel->bkg_drop_address != '' && ($bkgModel->bkgToCity->cty_name != $bkgModel->bkg_drop_address || $bkgModel->bkgToCity->cty_is_airport == 1))
			{
				//$url = Yii::app()->createAbsoluteUrl('bkpn/' . $bkgId . '/' . $hash);
				$url = $this->getURL(["booking/paymentreview", "id" => $bkgModel->bkg_id, "hash" => Yii::app()->shortHash->hash($bkgModel->bkg_id)]);
			}
			echo json_encode(['success' => $success, 'url' => $url]);
			Yii::app()->end();
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
		}
	}

	public function actionProcessGNowOfferDeny()
	{
		if (!Config::checkGozoNowEnabled())
		{
			throw new CHttpException(400, 'Cannot proceed');
		}
		$returnSet	 = new ReturnSet();
		$bvridStr	 = Yii::app()->request->getParam('bidId');
		$bkgId		 = Yii::app()->request->getParam('bookingId');
		$hash		 = Yii::app()->request->getParam('hash');

		if ($bvridStr != '')
		{
			$bvrIdArr	 = explode('_', $bvridStr);
			$bvrid		 = $bvrIdArr[1];
		}
		/** @var Booking $bkgModel */
		$bkgModel = Booking::model()->findByPk($bkgId);
		try
		{
			if ($hash != Yii::app()->shortHash->hash($bkgId))
			{
				throw new CHttpException(400, 'Invalid booking data');
			}

			if (!$bkgModel)
			{
				throw new CHttpException(400, 'Invalid data');
			}
			$returnSet = $bkgModel->proceedGNowOfferDeny($bvrid);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		echo json_encode(['success' => $returnSet->getStatus()]);
		Yii::app()->end();
	}

	public function actionRefreshAddressWidget()
	{
		$bookingId	 = Yii::app()->request->getParam('booking_id');
		$hash		 = Yii::app()->request->getParam('hash');
		/** @var Booking $model */
		$model		 = Booking::model()->findByPk($bookingId);

		$this->renderPartial('addressWidgetShow', ['model' => $model]);
	}

	public function actionBookJourney()
	{
		$url		 = Yii::app()->request->requestUri;
		$url_arr	 = explode("/", $url);
		$tripType	 = Yii::app()->request->getParam('tripType');

		$agent_id	 = '';
		$fromCtyId	 = $toCtyId	 = 0;
		Logger::profile("Request Initialized");

		$model = new BookingTemp('new');
		$model->loadDefaults();

		$this->newHome				 = true;
		$ptime						 = date('h:i A', strtotime('+4 hour'));
		$model->bkg_pickup_date_time = $ptime;

		$mdata = [];
		if (isset($_REQUEST) && $_REQUEST['bkid'] != '')
		{
			$bk_id	 = Yii::app()->request->getParam('bkid');
			$model	 = BookingTemp::model()->findByPK((int) $bk_id);
			$mdata	 = $_REQUEST;
		}


		Logger::profile("Request Validated");
		$credit = Yii::app()->request->getParam('credit');

		if ($credit > 0 && $credit != '')
		{
			Yii::app()->user->setFlash('credits', 'Create a booking now to redeem your accumulated Gozo Coins.');
		}

		$this->render('searchjourney', array('model'		 => $model,
			'brtModel'	 => $model->bookingRoutes,
			'tripType'	 => $tripType));
	}

	/** @var tripType
	 * Specify the page by booking type
	 *
	 *  */
	public function actionJourney()
	{
//		$url		 = Yii::app()->request->requestUri;
//		$url_arr	 = explode("/", $url);
		$tripType				 = Yii::app()->request->getParam('tripType');
		$model					 = new BookingTemp();
		$model->bkg_booking_type = $tripType;

		switch ($model->bkg_booking_type)
		{
			case 1:
				$view	 = "oneWay";
				break;
			case 2:
			case 3:
				$view	 = "roundMulti";
				break;
			case 4:
				$view	 = "airportPickupDrop";
				break;
			case 9:
			case 10:
			case 11:
				$view	 = "dayRental";
				break;
		}
		$this->renderPartial($view, array('model' => $model), false, true);
	}

	public function actionBookNowVO()
	{
		try
		{
			$view	 = 'booknow';
			$request = Yii::app()->request;
			// Model
			$model	 = $this->getModel();
			if (!$model)
			{
				$model = new BookingTemp();
				$model->loadDefaults();
			}

			$cabtype = $request->getParam('cabsegmentation');

			// Step
			$step				 = $request->getParam('step', 0);
			$url				 = Yii::app()->request->requestUri;
			$url_arr			 = explode("/", $url);
			$modelAttr			 = $request->getParam('BookingTemp');
			$model->attributes	 = $modelAttr;
			$model->bktyp		 = $modelAttr['bktyp'];
			$routes[]			 = $request->getParam('BookingRoute');

			if ($url_arr[2] != "")
			{
				$step = $request->getParam('step', 1);

				$trip = $url_arr[2];

				if (array_key_exists($trip, $model->booking_type_url))
				{
					$tripType = $model->booking_type_url[$trip];
				}
				else
				{
					$tripType = 1;
				}
				$from_city		 = $url_arr[3];
				$to_city		 = $url_arr[4];
				$from_city_id	 = Cities::model()->getIdByCityAlias($from_city);
				$to_city_id		 = Cities::model()->getIdByCityAlias($to_city);

				$routes[0]['brt_from_city_id']	 = $from_city_id;
				$routes[0]['brt_to_city_id']	 = $to_city_id;
				$model['bkg_booking_type']		 = $tripType;
			}
			else
			{
				if (!$request->isPostRequest)
				{
					goto skipStep2;
				}
				if (in_array($model->bkg_booking_type, [9, 10, 11]))
				{
					$model->bkg_to_city_id = $model->bkg_from_city_id;
				}
			}

			if (in_array($model->bkg_booking_type, [4]) && $step == 1)
			{
				$brtModel				 = new BookingRoute('validate');
				$brtModel->attributes	 = $routes[0];
				$brtModel->populateAirport($model->bkg_transfer_type);
				$routes[0]				 = $brtModel;
			}

			$model->setRoutes($routes);

			$quotes = false;

			if (Yii::app()->request->isPostRequest && UserInfo::isLoggedIn() && !in_array($model->bkg_booking_type, [2, 3]) && $step == 1)
			{
				$model->scenario = 'validateStep1';
				$result			 = CActiveForm::validate($model, null, false);
				if ($result != '[]')
				{
					goto skipStep2;
				}

				$errors = BookingRoute::validateRoutes($model->bookingRoutes);
				if (count($errors) > 0)
				{
					goto skipStep2;
				}

				$quotes		 = $model->createLeadAndGetQuotes($isAllowed	 = true);
				$step		 = 2;
			}
			if (in_array($model->bkg_booking_type, [2, 3]))
			{
				$count		 = count($model->bookingRoutes);
				$lastRoute	 = $model->bookingRoutes[$count - 1];
				$errors		 = BookingRoute::validateRoutes($model->bookingRoutes, $model->bkg_booking_type);
			}

			skipStep2:
		}
		catch (Exception $ex)
		{
			$data = ReturnSet::renderJSONException($ex);
			echo CJSON::encode($data);
		}


		$organisationSchemaRaw			 = StructureData::getOrganisation();
		$jsonproviderStructureMarkupData = json_encode($organisationSchemaRaw, JSON_UNESCAPED_SLASHES);

		$routeBreadcumbStructureMarkupData = StructureData::breadCrumbSchema($from_city_id, $to_city_id, 'trip_type');

		if ($tripType > 0)
		{

			$jsonStructureMarkupData = StructureData::getProductSchemaforTrip($model->bookingRoutes, $tripType);
		}
		$this->renderAuto($view, ['model'								 => $model,
			'jsonStructureMarkupData'			 => $jsonStructureMarkupData,
			'routeBreadcumbStructureMarkupData'	 => $routeBreadcumbStructureMarkupData,
			'jsonproviderStructureMarkupData'	 => $jsonproviderStructureMarkupData,
			'step'								 => $step, 'cabtype'							 => $cabtype, 'estArrTime'						 => $lastRoute->arrival_time, 'quotes'							 => $quotes]);
	}

	public function actionBookNow11()
	{
		VisitorTrack::track(CJSON::encode($_REQUEST));
		$this->layout	 = 'column_booking';
		$isLoggedIn		 = UserInfo::isLoggedIn();
		$this->getRequestData();
		$url			 = Yii::app()->createUrl('booking/tripType');
		if ($isLoggedIn)
		{
			$this->pageRequest->updatePostData();
			$this->forward('booking/tripType');
		}
		else
		{
			$this->forward('booking/checkAccount');
		}
	}

	public function actionBookNow1()
	{
		VisitorTrack::track(CJSON::encode($_REQUEST));
		$this->enableClarity();
		$this->layout	 = 'column_booking';
		$isLoggedIn		 = UserInfo::isLoggedIn();
		$this->getRequestData();
		$url			 = Yii::app()->createUrl('booking/tripType');
		if ($isLoggedIn)
		{
			$this->pageRequest->updatePostData();
		}
		$this->forward('booking/bkgType');
	}

	public function actionCheckAccount()
	{

		$this->layout	 = 'column_booking';
		$request		 = Yii::app()->request;
		$postParams		 = $request->getParam('BookingTemp');

		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_CHECKACCOUNT);

		$step	 = 1;
		$objPage = $this->getRequestData();

		$params = [];
		if ($rid != null)
		{
			$params['rid'] = $rid;
		}
		if ($request->isPostRequest && isset($postParams['isNewUser']))
		{
			$model	 = new BookingTemp("checkUser");
			$url	 = $tripUrl = Yii::app()->createUrl('booking/tripType', $params);
			switch ($postParams['isNewUser'])
			{
				case 1:
					Yii::app()->user->setReturnUrl($tripUrl);
					$this->pageRequest->afterLoginStep	 = 4;
					$this->pageRequest->updatePostData();
					$url								 = Yii::app()->createUrl('booking/signin');
					$this->renderAuto("proceedLogin", ["returnURL" => $tripUrl, "signup" => 1]);
					Yii::app()->end();
					break;
				case 2:
				default:
					$url								 = $tripUrl;
					break;
			}

			$this->forward("booking/tripType");
		}
		$objPage->step = $step;
		$this->renderAuto('checkgozoaccount', ['pageRequest' => $objPage], false, true);
	}

	public function actionSignin()
	{

		/** @var HttpRequest $request */
		$request = Yii::app()->request;

		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType());
		$this->layout	 = 'column_booking';
		$this->pageTitle = "Login";
		$step			 = 2;
		$success		 = false;
		$view			 = "signin";
		$contactId		 = '';
		$params			 = [];

		$pageID			 = $request->getParam('step');
		$objPage		 = $this->getRequestData();
		$signup			 = Yii::app()->request->getParam('signup', 1);
		$previousStep	 = Yii::app()->request->getParam('pstep');
		$params["rdata"] = $this->pageRequest->getEncrptedData();

		if ($objPage->booking == null)
		{
			$model = new BookingTemp();
			$model->loadDefaults();
			$this->parseFriendlyUrl($model);
			$objPage->setBookingModel($model);
		}
		else
		{
			$objBooking	 = $objPage->booking;
			$model		 = $objBooking->getLeadModel();
		}


		$returnUrl = Yii::app()->user->getReturnUrl();
		if ($returnUrl == '')
		{
			$returnUrl = $this->getURL('booking/tripType', $params);
		}
		if (UserInfo::isLoggedIn())
		{
			$this->redirect($returnUrl);
		}
		try
		{
			$contactModel	 = new Contact();
			$phoneModel		 = new ContactPhone('validate');
			$emailModel		 = new ContactEmail('validate');
			$type			 = Stub\common\ContactVerification::TYPE_PHONE;
			$success		 = false;
			if (!$request->isPostRequest || !in_array($pageID, [2, 3]))
			{
				$objCttVerify = $objPage->getContactObject();
				if (!$objCttVerify)
				{
					goto skipModelInit;
				}
				$model	 = $objCttVerify->getModel();
				$type	 = $objCttVerify->type;
				if ($type == Stub\common\ContactVerification::TYPE_EMAIL)
				{
					$emailModel = $model;
				}
				if ($type == Stub\common\ContactVerification::TYPE_PHONE)
				{
					$phoneModel = $model;
				}

				skipModelInit:
				$params = [
					"pageRequest"	 => $objPage,
					'type'			 => $type,
					'model'			 => $contactModel,
					'phoneModel'	 => $phoneModel,
					'emailModel'	 => $emailModel,
					"pageId"		 => $step,
					"step"			 => $step,
					"signup"		 => $signup,
					"returnUrl"		 => $returnUrl
				];
				goto view;
			}

			$type = $request->getParam('checkaccount');
			if ($request->getParam('ContactPhone')['phn_phone_no'] == '9799331555')
			{
				Logger::setCategory("info.module.default.controller.booking.signin");
			}
			if ($type == Stub\common\ContactVerification::TYPE_EMAIL)
			{
				$contactEmail			 = $request->getParam('ContactEmail');
				$emailModel->attributes	 = $contactEmail;
				if (!$emailModel->validate())
				{
					throw new Exception(json_encode($emailModel->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
				$value		 = $emailModel->eml_email_address;
				$contactId	 = Contact::getByEmailPhone($value);
			}

			if ($type == Stub\common\ContactVerification::TYPE_PHONE)
			{
				$contactPhone			 = $request->getParam('ContactPhone');
				$phoneModel->attributes	 = $contactPhone;
				$value					 = $phoneModel->phn_phone_country_code . $phoneModel->phn_phone_no;
				if (!$phoneModel->validate())
				{
					throw new Exception(json_encode($phoneModel->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
				$contactId = Contact::getByEmailPhone('', $value);
			}

			if ($value == '')
			{
				throw new Exception("Please enter proper value", ReturnSet::ERROR_INVALID_DATA);
			}

			$contactDetails = Yii::app()->JWT->encode(["type" => $type, "value" => $value]);

			if ($contactId == '' && $signup == 1)
			{
				Logger::info("if contactId blank and signup = 1");
				$params	 = ['type' => $type, 'model' => $contactModel, 'phoneModel' => $phoneModel, 'emailModel' => $emailModel, "pageId" => $step, "pstep" => $previousStep];
				$errtext = ($type == 1) ? "Sorry, we couldn't find this email address in our database" : "Sorry, we couldn't find this phone number in our database";
				throw new Exception($errtext, ReturnSet::ERROR_INVALID_DATA);
			}
			else
			{
				$signup = 2;
			}


			$objCttVerify	 = $objPage->getContact($type, $value);
			Contact::verifyOTP($objCttVerify);
			$objPage->updatePostData();
			$step			 = 3;

			$params	 = [
				'verifyData'	 => $contactDetails,
				"pageRequest"	 => $objPage,
				"type"			 => $type,
				"pageId"		 => $step,
				"signup"		 => $signup,
				'verifyotp'		 => $objCttVerify->otp,
				"pstep"			 => $previousStep
			];
			$view	 = "otpVerify";
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);

			if ($request->isAjaxRequest)
			{
				echo json_encode($returnSet);
				Yii::app()->end();
			}

			$params['hasErrors']	 = true;
			$params['errorMessage']	 = $e->getMessage();
		}
		view:
		$objPage->step = $step;
		$this->renderAuto($view, $params, false, true);
	}

	public function actionOtpVerify()
	{
		/** @var HttpRequest $request */
		$request = Yii::app()->request;

		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType());
		$this->layout	 = 'column_booking';
		$this->pageTitle = "OTP verify";
		$returnset		 = new ReturnSet();

		$objPage = $this->getRequestData();
		$signup	 = $request->getParam('signup', 1);

		if ($objPage->booking == null)
		{
			$model = new BookingTemp();
			$model->loadDefaults();
			$this->parseFriendlyUrl($model);
			//	$objBooking	 = $model->getStub();
		}
		else
		{

			$objBooking	 = $objPage->booking;
			$model		 = $objBooking->getLeadModel();
		}

		$params = [];
		try
		{
			$returnUrl = Yii::app()->user->getReturnUrl();
			if ($returnUrl == '')
			{
				$returnUrl = Yii::app()->createUrl('booking/tripType', $params);
			}

			$verifyData	 = $request->getParam('verifyData');
			$data		 = Yii::app()->JWT->decode($verifyData);
			$curOtp		 = $request->getParam('otp');

			if (!$data)
			{
				throw new Exception("Invalid Request", ReturnSet::ERROR_INVALID_DATA);
			}

			$objCttVerify = $objPage->getContact($data->type, $data->value);

			if ($objCttVerify->isVerified())
			{
				
			}

			if (!$objCttVerify->isOTPActive())
			{
				throw new Exception("OTP Expired. Please enter new OTP.", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$success = $objCttVerify->verifyOTP($curOtp);
			if (!$success)
			{
				throw new Exception("OTP Mistmached. Please try again", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			if ($data->type == Stub\common\ContactVerification::TYPE_EMAIL)
			{
				$sessEmail = $data->value;
			}
			if ($data->type == Stub\common\ContactVerification::TYPE_PHONE)
			{
				$sessPhone = $data->value;
			}
			$createIfNotExist	 = ($signup == 2);
			$contactId			 = Contact::getByEmailPhone($sessEmail, $sessPhone, $createIfNotExist);
			if (!$contactId)
			{
				throw new Exception("Sorry, we couldn't find this data in our records", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			$userModel = Users::createbyContact($contactId);
			if (count($userModel) > 0)
			{
				$identity			 = new UserIdentity($userModel->usr_name, null);
				$identity->userId	 = $userModel->user_id;
				if ($identity->authenticate())
				{
					$objPage->clearContact();
					Yii::app()->user->login($identity);
					$this->createLog($identity);
					$returnset->setStatus(true);
					$this->renderAuto("otpVerified");
					Yii::app()->end();
				}
			}
		}
		catch (Exception $e)
		{
			$returnset = ReturnSet::setException($e);
		}
		skipAll:
		echo json_encode($returnset);
		Yii::app()->end();
	}

	public function actionTripType()
	{
		$this->enableClarity();

		/** @var HttpRequest $request */
		$request		 = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_CABSEGMENTATION);
		$step			 = 4;
		$this->layout	 = 'column_booking';

		$objPage		 = $this->getRequestData();
		$tripCategory	 = $request->getParam('cabsegmentation');
		if ($objPage->booking != null)
		{
			$objBooking	 = $objPage->booking;
			$model		 = $objBooking->getLeadModel();
		}
		$objPage->step = $step;

		$tncType = TncPoints::getTncIdsByStep($step);
		$tncArr	 = TncPoints::getTypeContent($tncType);

		if ($request->isPostRequest && $tripCategory != '')
		{
			$this->pageRequest->tripCategory = $tripCategory;
			$this->pageRequest->updatePostData();
			$this->forward('booking/bkgType');
		}
		$this->renderAuto('cabsegmentation', ['pageRequest' => $objPage, 'pageid' => $step, 'step' => $step, 'tncArr' => $tncArr], false);
	}

	public function actionBkgType()
	{
		$this->enableClarity();

		/** @var HttpRequest $request */
		$request		 = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_SERVICETYPE);
		$this->layout	 = 'column_booking';
		$step			 = 4;

		$pageID			 = $request->getParam('step');
		$objPage		 = $this->getRequestData();
		$tripCategory	 = $request->getParam('cabsegmentation');

		if ($objPage->booking == null)
		{
			$model = new BookingTemp();
			$model->loadDefaults();
			$this->parseFriendlyUrl($model);
			$objPage->setBookingModel($model);
		}
		else
		{

			$objBooking	 = $objPage->booking;
			$model		 = $objBooking->getLeadModel();
		}

		if ($request->isPostRequest && $pageID == 4)
		{
			$model->attributes	 = $request->getParam('BookingTemp');
			$bookingType		 = explode("_", $model->bkg_booking_type);

			if (count($bookingType) > 1)
			{
				$params = ["bkgType" => $bookingType[0], "type" => $bookingType[1]];
			}
			else
			{
				$params = ["bkgType" => $bookingType[0]];
			}

			$model->bkg_booking_type = $bookingType[0];

			$model->bkg_transfer_type = isset($bookingType[1]) ? $bookingType[1] : 0;
			$objPage->setBookingModel($model);
			$objPage->updatePostData();
			$this->forward('booking/itinerary');
		}
		$tncType		 = TncPoints::getTncIdsByStep($step);
		$tncArr			 = TncPoints::getTypeContent($tncType);
		$objPage->step	 = $step;
		$this->renderAuto('servicetypes', array('cabtype' => $tripCategory, 'model' => $model, 'step' => $step, 'tncArr' => $tncArr), false);
	}

	private function parseFriendlyUrl($model = null)
	{
		if ($model == null)
		{
			$model = new BookingTemp();
		}
		/** @var HttpRequest $request */
		$request					 = Yii::app()->request;
		$model->bkg_booking_type	 = $request->getParam("bkgType");
		$model->bkg_transfer_type	 = $request->getParam("type", 0);
		$fromCity					 = $request->getParam("fcity");
		$toCity						 = $request->getParam("tcity");
		$leadId						 = $request->getParam("lid");
		$hash						 = $request->getParam("hash");

		if ($leadId != '' && $hash != Yii::app()->shortHash->hash($leadId))
		{
			throw new Exception("Invalid Request", 400);
		}
		if ($leadId > 0)
		{
			$model = BookingTemp::model()->findByPk($leadId);
			if (!$model)
			{
				throw new CHttpException(400, "Invalid Lead Request", 400);
			}
			$model->getRoutes();
			if ($model)
			{
				goto end;
			}
		}
		$bkgId = $request->getParam("bid");
		if ($bkgId > 0)
		{
			$bkgModel = Booking::model()->findByPk($bkgId);
			if (!$bkgModel)
			{
				throw new CHttpException(400, "Invalid Request", 400);
			}
			$model->bookingRoutes	 = $bkgModel->bookingRoutes;
			$model->attributes		 = $bkgModel->attributes;
			goto end;
		}

		if (count($model->bookingRoutes) > 0)
		{
			$brtModel = $model->bookingRoutes[0];
		}
		else
		{
			$brtModel				 = new BookingRoute();
			$model->bookingRoutes[]	 = $brtModel;
		}

		if ($fromCity != '')
		{
			$fcityModel = Cities::model()->getByAliasPath($fromCity);
			if (!in_array($model->bkg_booking_type, [12, 4]) || ($fcityModel && in_array($model->bkg_booking_type, [12, 4]) && $fcityModel->cty_is_airport == 1))
			{
				$model->bkg_from_city_id	 = ($fcityModel) ? $fcityModel->cty_id : null;
				$brtModel->brt_from_city_id	 = $model->bkg_from_city_id;
			}
			else if ($toCity == '')
			{
				$model->bkg_to_city_id		 = ($fcityModel) ? $fcityModel->cty_id : null;
				$brtModel->brt_to_city_id	 = $model->bkg_from_city_id;
			}

			if ($fcityModel && in_array($model->bkg_booking_type, [12, 4]) && $model->bkg_transfer_type == 0)
			{
				$model->bkg_transfer_type = ($fcityModel->cty_is_airport == 1) ? 1 : 2;
			}
		}

		if ($toCity != '')
		{
			$tcityModel = Cities::model()->getByAliasPath($toCity);

			$model->bkg_to_city_id		 = ($tcityModel) ? $tcityModel->cty_id : null;
			$brtModel->brt_to_city_id	 = $model->bkg_to_city_id;
			$isFCityAirport				 = ($fcityModel && $fcityModel->cty_is_airport == 1);
			if ($tcityModel && !$isFCityAirport && $tcityModel->cty_is_airport == 1 && in_array($model->bkg_booking_type, [12, 4]))
			{
				$model->bkg_transfer_type = 2;
			}
		}

		if (($fcityModel && $fcityModel->cty_is_airport != 1 || $tcityModel && $tcityModel->cty_is_airport != 1) && in_array($model->bkg_booking_type, [12, 4]))
		{
			$radius		 = false;
			$distance	 = false;
			if ($fcityModel->cty_is_airport == 1)
			{
				$radius = $fcityModel->cty_radius;
			}
			else if ($tcityModel->cty_is_airport == 1)
			{
				$radius = $tcityModel->cty_radius;
			}

			if ($fcityModel && $tcityModel)
			{
				$distance = Filter::calculateDistance($fcityModel->cty_lat, $fcityModel->cty_long, $tcityModel->cty_lat, $tcityModel->cty_long);
				if ($radius == false || ($distance !== false && $radius < $distance))
				{
					$model->bkg_booking_type = 1;
				}
				if ($fcityModel->cty_is_airport != 1 && $tcityModel->cty_is_airport != 1)
				{
					$model->bkg_from_city_id	 = $fcityModel->cty_id;
					$brtModel->brt_from_city_id	 = $model->bkg_from_city_id;
					$model->bkg_booking_type	 = 1;
					$model->bkg_transfer_type	 = 0;
				}
			}
		}

		if (in_array($model->bkg_booking_type, [12, 4]))
		{
			if ($fcityModel && $fcityModel->cty_is_airport != 1)
			{
				$model->bkg_from_city_id	 = null;
				$brtModel->brt_from_city_id	 = null;
			}

			if ($tcityModel && $tcityModel->cty_is_airport != 1)
			{
				$model->bkg_to_city_id		 = null;
				$brtModel->brt_to_city_id	 = null;
			}
		}


		if ($request->getParam("cabsegmentation") != '')
		{
			$this->pageRequest->tripCategory = $request->getParam("cabsegmentation");
		}
		end:
		return $model;
	}

	/**
	 *  @return BookFormRequest
	 * 	@throws Exception
	 */
	private function getRequestData()
	{
		if ($this->pageRequest != null)
		{
			goto end;
		}

		$rData				 = Yii::app()->request->getParam("rdata");
		$rDataCookie		 = \Yii::app()->request->cookies['gozo_rdata']->value;
		$rData				 = ($rDataCookie != '' && $rData == '') ? $rDataCookie : $rData;

		$rDataSession		 = \Yii::app()->session['_gz_rdata_skiplogin'];
		$rData				 = ($rDataSession != '' && $rData == '') ? $rDataSession : $rData;

		$this->pageRequest	 = BookFormRequest::createInstance($rData);
		$objPage			 = $this->pageRequest;

		if ($objPage->booking == null)
		{
			$model	 = new BookingTemp();
			$model->loadDefaults();
			$model	 = $this->parseFriendlyUrl($model);
			$objPage->setBookingModel($model);
		}

		end:
		return $this->pageRequest;
	}

	public function actionItinerary()
	{
		//Yii::app()->request->cookies->clear();
		$this->enableClarity();

		/** @var HttpRequest $request */
		$request		 = Yii::app()->request;
		Logger::info("Booking::Itinerary===START==");
		Logger::profile("start");
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_ITINERARY);
		$this->layout	 = 'column_booking';
		$step			 = 5;
		Logger::profile("VisitorTrack::track");

		$pageID				 = $request->getParam('step');
		$secondaryTraveller	 = $request->getParam('secondaryTraveller');

		$objPage = $this->getRequestData();

		Logger::profile("this->getRequestData");
		if (!$request->isPostRequest && $objPage->booking->id == '' && in_array($objPage->booking->tripType, ['', '0']))
		{
			//$this->forward("booking/tripType");
			$this->forward("booking/bkgType");
		}
		if ($objPage->booking == null)
		{

			$model		 = new BookingTemp();
			$model->loadDefaults();
			Logger::profile("model->loadDefaults");
			$this->parseFriendlyUrl($model);
			$objPage->setBookingModel($model);
			$objBooking	 = $objPage->booking;
		}
		else
		{
			$objBooking	 = $objPage->booking;
			$model		 = $objBooking->getLeadModel();
			if ($model->bkg_user_id != null && $model->bkg_agent_id == null)
			{
				$GLOBALS["GA4_USER_ID"] = $model->bkg_user_id;
			}
		}

		if ($request->isPostRequest && $pageID == 5)
		{
			$phone = $request->getParam("userPhone");
			Logger::info("booking phone:" . $phone);

			$agentId = ($objBooking->agentId) ? $objBooking->agentId : $model->bkg_agent_id;
			if ($agentId == Config::get('Mobisign.partner.id') || $agentId == Config::get('Kayak.partner.id'))
			{
				goto skiploggedin;
			}
			if (Yii::app()->user->isGuest && $phone == "" && $request->isAjaxRequest && $request->getParam("skipLogin")!=1)
			{
				throw new CHttpException(401, "Login required.", ReturnSet::ERROR_UNAUTHORISED);
			}
			skiploggedin:

			Logger::info("BookingTemp Attributes:" . json_encode($request->getParam('BookingTemp')));
			$modelAttr = $request->getParam('BookingTemp');

			$model->setAttributes($modelAttr);
			Logger::info("phone no:" . $phone);
			if ($phone != '')
			{
				$model->setContactNumber($phone);
			}

			$isValidPhone = true;
			if ($model->bkg_contact_no == '' && UserInfo::isLoggedIn())
			{
				$objPhone = Users::getPrimaryPhone(UserInfo::getUserId(), true);
				if ($objPhone)
				{
					$model->setContactNumber($objPhone->getCountryCode() . $objPhone->getNationalNumber());
				}
			}
			elseif ($model->bkg_contact_no != '')
			{
				$isValidPhone = Filter::validatePhoneNumber('+' . $model->bkg_country_code . $model->bkg_contact_no);
			}

			if ($request->isAjaxRequest && ($model->bkg_contact_no == '' || !$isValidPhone) && $model->bkg_agent_id != Config::get('Kayak.partner.id') && $request->getParam("skipLogin")!=1)
			{
				throw new CHttpException(403, "Phone number required.", ReturnSet::ERROR_UNAUTHORISED);
			}


			$routes = [];
			try
			{
// $routeParam = $request->getParam('BookingRoute');
				$routeParam		 = $request->getParam('BookingRoute');
				// if(!$request->isAjaxRequest)
				// {
				$routeParamAll	 = $request->getParam('BookingTemp');
				if ($routeParamAll['bkg_booking_type'] == 3)
				{
					$routeParamCookie = json_decode($routeParamAll['bkg_route_data']);
				}
				else
				{
					$routeParamCookie = $request->getParam('BookingRoute');
				}

				if (($routeParamAll['bkg_booking_type'] == 4 ) || ($routeParamAll['bkg_booking_type'] != 3 && $routeParam['brt_from_city_id'] != '' && $routeParam['brt_to_city_id'] != '') || ($routeParamAll['bkg_booking_type'] == 3 && $routeParamCookie[0]->brt_to_city_id != '' && $routeParamCookie[0]->brt_from_city_id != ''))
				{
					$rawItineraryCookie									 = new CHttpCookie('rawItineraryCookie', $routeParamCookie);
					Yii::app()->request->cookies['rawItineraryCookie']	 = $rawItineraryCookie;
					$routeData											 = \Beans\booking\Route::setData($routeParamCookie, '', $routeParamAll['bkg_booking_type']);

					$itineraryCookie								 = new CHttpCookie('itineraryCookie', $routeData);
					Yii::app()->request->cookies['itineraryCookie']	 = $itineraryCookie;
				}
				// }

				if ($model->bkg_booking_type == 3)
				{
					$routes = $model->getRoutes();
				}

				if (count($routes) > 0 && !$routes[count($routes) - 1]->validate() && $routeParam != null)
				{
					array_pop($routes);
				}

				$brtModel = new BookingRoute();
				if ($model->bkg_agent_id == Config::get('Mobisign.partner.id') && in_array($model->bkg_booking_type, [9, 10, 11]))
				{
					$brtModel->attributes = $model->bookingRoutes[0]->attributes;
				}
				$brtModel->attributes = $routeParam;
//                

				if (($model->bkg_booking_type == 4 || $model->bkg_booking_type == 12))
				{
					if (!$brtModel->airportValidate())
					{
						throw new CHttpException(104, json_encode($brtModel->errors), ReturnSet::ERROR_UNAUTHORISED);
					}
					$brtModel->populateAirport($model->bkg_transfer_type);
				}
				else

				if ($model->bkg_booking_type == 14)
				{
					$brtModel->populateIntraCity($model->bkg_transfer_type);
				}

				if ($model->bkg_booking_type == 15)
				{
					$brtModel->railway = $routeParam['railway'];
					$brtModel->populateRailwayBus($model->bkg_transfer_type);
				}

				if (in_array($model->bkg_booking_type, [1]) && !$brtModel->validate())
				{
					throw new CHttpException(104, json_encode($brtModel->errors), ReturnSet::ERROR_UNAUTHORISED);
				}

				if ($model->bkg_booking_type != 3 || $brtModel->validate())
				{
					$routes[] = $brtModel;
				}

				$model->setRoutes($routes);
				$model->scenario = 'validateStep1';
				$result			 = CActiveForm::validate($model, null, false);

				if ($result != '[]')
				{
					if (count($routes) >= 1 && $routes[count($routes) - 1]->validate())
					{
						$brtModel						 = new BookingRoute();
						$brtModel->brt_from_city_id		 = $routes[count($routes) - 1]->brt_to_city_id;
						$brtModel->brt_min_date			 = $routes[count($routes) - 1]->arrival_time;
						$brtModel->brt_pickup_datetime	 = $routes[count($routes) - 1]->arrival_time;
						$model->bookingRoutes[]			 = $brtModel;
					}

					$objPage->setBookingModel($model);
					Logger::info("object page+++++++++:" . json_encode($objPage));
					throw new Exception($result, ReturnSet::ERROR_VALIDATION);
				}

				$objPage->booking = \Stub\common\Booking::setModel($model);
				Logger::info("booking object:" . json_encode($objPage->booking));
				if ($request->isAjaxRequest || !Yii::app()->user->isGuest)
				{

					Logger::trace("actionItinerary - Model Attributes :: " . json_encode($model->getAttributes()));
					$objPage->updatePostData();
					Logger::trace("actionItinerary - objPage :: " . json_encode($objPage));

					if ($model->bkg_booking_type != 14)
					{
						//$this->forward('booking/catQuotes');
						$this->forward('booking/tierQuotes');
					}
					else
					{
						$this->forward('booking/intraCatQuotes');
					}
				}
			}
			catch (Exception $ex)
			{
				Logger::Error('City ID not found in itinerary ==== lead id:' . $model->bkg_booking_id . $ex->getMessage());
				$returnSet = ReturnSet::setException($ex);

				if ($request->isAjaxRequest)
				{
					echo json_encode($returnSet);
					Yii::app()->end();
				}
			}
		}
		$objPage->step = $step;
		$this->renderAuto('bkItinerary', array('step' => $step, 'pageRequest' => $objPage, 'pageid' => $step), false, true);
	}

	public function actionIntraCatQuotes()
	{
		/** @var HttpRequest $request */
		$request = Yii::app()->request;

		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_QUOTE);
		$this->layout	 = 'column_booking';
		$step			 = 6;

		$pageID	 = $request->getParam('step');
		$objPage = $this->getRequestData();
		Logger::trace("actionCatQuotes - objPage :: " . json_encode($objPage));
		if (!$request->isPostRequest && $objPage->booking->id == '')
		{
			$this->forward("booking/itinerary");
		}
		$objBooking	 = $objPage->booking;
		$userId		 = UserInfo::getUserId();
		$profile	 = $objBooking->profile;
		Logger::info("booking savelead:ProfileInfo" . CJSON::encode($profile));
		$hasPhone	 = ($objBooking != null) && ($profile != null) && ($profile->primaryContact != null) && ($profile->primaryContact->number != '');
		if ($request->isPostRequest && $pageID == 5 && ($userId > 0 || $hasPhone))
		{
			$model = $this->pageRequest->saveLead();
			Logger::trace("booking savelead:model " . json_encode($model->getAttributes()));
		}

		if (!$model)
		{
			$model = $objPage->booking->getLeadModel();
			Logger::trace("booking false:model " . json_encode($model->getAttributes()));
		}

		$objPage->populateQuote($model);

		$scvVehicleId	 = [1, 2, 3];
		$intraQuote		 = $this->pageRequest->quote->cabRate;
		$fare			 = [];
		$quotefare		 = [];
		$pageID;
		$minIntraQuote	 = [];
		if ($pageID == 5)
		{
			foreach ($scvVehicleId as $key1 => $scvVehicleIdeach)
			{
				foreach ($intraQuote as $key => $intracabRate)
				{
					if ($intracabRate->cab->cabCategory->scvVehicleId == $scvVehicleIdeach)
					{
						$fare[$key1][$key] = $intracabRate->fare->baseFare;
					}
				}
			}
			foreach ($fare as $key2 => $value)
			{
				//$selectedFare[array_search(min($value), $value)] = min($value);
				$selectedFare[array_search(min($value), $value)] = min($value);
			}
			foreach ($selectedFare as $key4 => $value)
			{
				$minIntraQuote[] = $intraQuote[$key4];
			}
		}
		if ($request->isPostRequest && $pageID == 6)
		{
			$cabCategory = $request->getParam('cabcategory');
			$cabType	 = $request->getParam('cabclass' . $cabCategory);
			$cabId		 = $request->getParam('cabid' . $cabCategory);

			if ($objBooking->cab == null)
			{
				$objBooking->cab = new Stub\common\Cab();
			}

			if ($objBooking->cab->cabCategory == null)
			{
				$objBooking->cab->cabCategory = new \Stub\common\CabCategory();
			}
			$objBooking->cab->cabCategory->setData($cabType);

			$objBooking->cab->categoryId = $cabCategory;
			$objPage->updatePostData();

			$serviceClass					 = $objBooking->cab->cabCategory->scvVehicleServiceClass;
			$objBooking->cabServiceClass	 = $serviceClass;
			$objBooking->bkg_vehicle_type_id = $cabId;
			$objBooking->cabType			 = $cabId; //$cabCategory;
			$objBooking->cab->id			 = $cabId;

			$objQuote = $objPage->quote;
			foreach ($objQuote->cabRate as $key => $rate)
			{
				if ($rate->cab->id == $cabId)
				{
					$objBooking->fare->advanceReceived	 = $rate->fare->advanceReceived;
					$objBooking->fare->totalAmount		 = $rate->fare->totalAmount;
				}
			}


			$objPage->updatePostData();
			if (Yii::app()->user->isGuest)
			{
				throw new CHttpException(403, "Login required.", ReturnSet::ERROR_UNAUTHORISED);
			}
			else
			{

				$this->pageRequest->step = 13;
				$this->pageRequest->updatePostData();
				$objPage->updatePostData();
				$objPage->saveLead();

				$objBooking = $objPage->booking;

				$bkgModel	 = Booking::getNewInstance();
				$model		 = $objBooking->getLeadModel();

				$bkgId		 = $bkgModel->saveBooking($model->bkg_id);
				$hash		 = Yii::app()->shortHash->hash($bkgId);
				Logger::info("booking intraCity:beforeUrl" . $model->bkg_id);
				$tripId		 = Booking::model()->findByPk($bkgId)->bkg_bcb_id;
				$dataexist	 = BookingVendorRequest::getPreferredVendorbyBooking($tripId);
				$url		 = Yii::app()->createAbsoluteUrl('gznow/' . $bkgId . '/' . $hash);
				Logger::info("booking intraCity:Url" . $url);
				echo json_encode(['success' => true, 'url' => $url]);
				Yii::app()->end();
			}
		}



		$this->renderAuto('bkQuoteIntra',
					array('step'			 => $step,
					'pageid'		 => $step,
					'nextStep'		 => $nextStep, 'minIntraQuote'	 => $minIntraQuote), false, true);
	}

	public function actionCatQuotes()
	{
		$this->enableClarity();

		/** @var HttpRequest $request */
		$request = Yii::app()->request;

		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_QUOTE);
		$this->layout	 = 'column_booking';
		$step			 = 6;

		$pageID	 = $request->getParam('step');
		$objPage = $this->getRequestData();
		Logger::trace("actionCatQuotes - objPage :: " . json_encode($objPage));
		if(!$request->isPostRequest && $request->getParam('rid')!='')
		{
			$this->forward('booking/tierQuotes');
			Yii::app()->end();
		}
		if (!$request->isPostRequest && $objPage->booking->id == '')
		{
			$this->forward("booking/itinerary");
		}

		$objBooking	 = $objPage->booking;
		$userId		 = UserInfo::getUserId();
		$userCredit	 = UserCredits::getUserCoin($userId);
		$profile	 = $objBooking->profile;
		Logger::info("booking savelead:ProfileInfo" . CJSON::encode($profile));
		$hasPhone	 = ($objBooking != null) && ($profile != null) && ($profile->primaryContact != null) && ($profile->primaryContact->number != '');
		if ($request->isPostRequest && $pageID == 5 && ($userId > 0 || $hasPhone))
		{
			$model = $this->pageRequest->saveLead();
			Logger::trace("booking savelead:model " . json_encode($model->getAttributes()));
		}

		if (!$model)
		{
			$model = $objPage->booking->getLeadModel();
			Logger::trace("booking false:model " . json_encode($model->getAttributes()));
		}

		$objPage->populateQuote($model);

		Logger::trace("actionCatQuotes - populateQuote objPage :: " . json_encode($objPage));

		if ($objPage->booking->isGozoNow == 1)
		{
			//	Logger::pushTraceLogs();
		}

		if ($request->isPostRequest && $pageID == 6)
		{

			$cabCategory = $request->getParam('cabcategory');
			$cabType	 = $request->getParam('cabclass');
			if ($objBooking->cab == null)
			{
				$objBooking->cab = new Stub\common\Cab();
			}
			$objBooking->cab->categoryId = $cabCategory;
			$objPage->updatePostData();
			$this->forward('booking/tierQuotes');
			Yii::app()->end();
		}
		/************* Flash Sale *******************************************/
        if($model->bkg_booking_type == 1)
        {
            $flashModel = new CabAvailabilities();
            $flashBooking    = $flashModel->fetchFlashSaleBooking($model);
        }
        /************* Flash Sale *******************************************/
		$this->pageRequest->step = $step;
		$view					 = ($objBooking->agentId == Config::get('Mobisign.partner.id')) ? 'bkQuoteNew_M' : 'bkQuoteNew';
		$this->renderAuto($view,
					array('step'		 => $step,
					'pageid'	 => $step,
					'nextStep'	 => $nextStep, 'userCredit' => $userCredit, 'flashBooking' => $flashBooking), false, true);
	}

	public function actionTierQuotes()
	{
		$this->enableClarity();

		/** @var HttpRequest $request */
		$request		 = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_TIRE);
		$this->layout	 = 'column_booking';
		$step			 = 6;
		$pageID			 = $request->getParam('step');
		$refreshQuotes   = $request->getParam('refreshQuotes');
		//$step			 = 9;
		//$pageID			 = $request->getParam('step');
		$userCredit		 = UserCredits::getUserCoin(UserInfo::getUserId());
		$params			 = [];
		$params['pstep'] = $step;
		$objPage		 = $this->getRequestData();
		$objBooking		 = $objPage->booking;

		$cabCategory = $request->getParam('cabcategory');

		if (!$request->isPostRequest && $objPage->booking->id == '' && $request->getParam('rid')=='')
		{
			$this->forward("booking/itinerary");
		}
		
		if ($pageID == 5 )//|| $refreshQuotes)
		{
			$this->pageRequest->populateQuote();
		}

		$userId = UserInfo::getUserId();

		$profile	 = $objBooking->profile;
		Logger::info("booking savelead:ProfileInfo" . CJSON::encode($profile));
		$hasPhone	 = ($objBooking != null) && ($profile != null) && ($profile->primaryContact != null) && ($profile->primaryContact->number != '');
		// $contactPhone = ContactPhone::getPhoneNo($userId, UserInfo::TYPE_CONSUMER);
		if ($profile->primaryContact->number == '' && $objBooking->agentId != Config::get('Kayak.partner.id') && $request->getParam('skipLogin')!=1 && $request->getParam('rid')=='')
		{
			Logger::error("Phone number can not be blank while create a booking.");
			$this->forward("booking/travellerInfo");
		}

		if ($request->isPostRequest && $pageID == 5 && ($userId > 0 || $hasPhone || $objBooking->agentId == Config::get('Mobisign.partner.id') || $objBooking->agentId == Config::get('Kayak.partner.id')))
		{
			$model = $this->pageRequest->saveLead();
			Logger::trace("booking savelead:model " . json_encode($model->getAttributes()));
		}

		if (!$model)
		{
			$model = $objPage->booking->getLeadModel();
			Logger::trace("booking false:model " . json_encode($model->getAttributes()));
		}

		if ($model && $model->bkg_user_id != '' && $model->bkg_agent_id == null)
		{
			$GLOBALS["GA4_USER_ID"] = $model->bkg_user_id;
		}

		//	$objPage->populateQuote($model);

		Logger::trace("actionCatQuotes - populateQuote objPage :: " . json_encode($objPage));

		if ($objPage->booking->isGozoNow == 1)
		{
//			Logger::pushTraceLogs();
		}

		$svcModelCat = SvcClassVhcCat::model()->getVctSvcList('', 0, $cabCategory, 0, true);

		$objQuote = $objPage->quote;
		Logger::error("SVC populated");

		$baseFare	 = [];
		$arrQuoteCat = [];

		foreach ($objQuote->cabRate as $key => $rate)
		{
			foreach ($svcModelCat as $key1 => $cat)
			{
				if ($rate->cab->id == $key1 && $rate->cab->cabCategory->scvVehicleModel == 0)
				{
					$cabId				 = $rate->cab->id;
					$svcModel			 = SvcClassVhcCat::model()->findByPk($cabId);
					$tier				 = $svcModel->scv_scc_id;
					$arrQuoteCat[$tier]	 = $rate;
					//$sortCat[$rate->fare->baseFare]	 = $key1;
					if ($rate->fare->minBaseFare > 0 && $model->bkg_is_gozonow == 0 && $model->bkg_agent_id == null)
					{
						$model->bkg_is_gozonow		 = 1;
						$objPage->booking->isGozoNow = 1;
					}
				}
			}
		}

		if (($model->bkg_agent_id == Config::get('Kayak.partner.id')) && $request->getParam('cabclass') != null)
		{
			$pageID = 6;
		}

		/************* Skip Login *******************************************/
		if(Yii::app()->user->isGuest && $model->bkg_agent_id == '' && $request->getParam('rid')=='')
		{
			Yii::app()->session['_gz_rdata_skiplogin']	 = $objPage->getEncrptedData();
		}
		/************* Skip Login *******************************************/

		if ($request->isPostRequest && $pageID == 6)
		{
			$cabType = $request->getParam('cabclass');
			if ($objBooking->cab == null)
			{
				$objBooking->cab = new Stub\common\Cab();
			}
			if ($objBooking->cab->cabCategory == null)
			{
				$objBooking->cab->cabCategory = new \Stub\common\CabCategory();
			}
			$objBooking->cab->cabCategory->setData($cabType);
			$serviceClass				 = $objBooking->cab->cabCategory->scvVehicleServiceClass;
			$objBooking->cabServiceClass = $serviceClass;
			$objBooking->cab->categoryId = $objBooking->cab->cabCategory->scvVehicleId;
			if ($serviceClass == 4)
			{
				$objPage->updatePostData();
				$this->forward('booking/moreTierQuotes');
			}
			else
			{
				$objBooking->bkg_vehicle_type_id = $cabType;
				$objBooking->cabType			 = $cabType;
				foreach ($objQuote->cabRate as $key => $rate)
				{
					if ($rate->cab->id == $cabType)
					{
						$objBooking->fare->advanceReceived	 = $rate->fare->advanceReceived;
						$objBooking->fare->totalAmount		 = $rate->fare->totalAmount;
					}
				}

				$objPage->updatePostData();

				if ($objBooking->agentId == Config::get('Mobisign.partner.id') || $objBooking->agentId == Config::get('Kayak.partner.id'))
				{
					goto skiploggedin;
				}
				if (Yii::app()->user->isGuest)
				{
					throw new CHttpException(403, "Login required.", ReturnSet::ERROR_UNAUTHORISED);
				}
				else
				{
					skiploggedin:
					$this->pageRequest->step = $pageID;
					$objPage->updatePostData();
					$objPage->saveLead();
					$model->refresh();
					$result					 = $model->validateTime('bkg_pickup_date_date', $request);
					if (!$result)
					{
						echo CJSON::encode(['success' => false, 'errors' => $model->getErrors()]);
						Yii::app()->end();
					}
					if ($model->bkg_is_gozonow == 1)
					{
						goto skipgozonow;
					}

					skipgozonow:
					if ($model->bkg_agent_id == Config::get('Kayak.partner.id'))
					{
						$bkgId	 = $model->bkg_id;
						$hash	 = Yii::app()->shortHash->hash($model->bkg_id);
						$url	 = Yii::app()->createUrl('book-cab/travellerinfo/' . $bkgId . '/' . $hash);

						$this->redirect($url);
						Yii::app()->end();
					}
					if($model->bkg_agent_id != Config::get('Mobisign.partner.id') && $refreshQuotes == 1)
					{
//							$url = $this->getURL($objPage->getQuoteURL());
//							$this->redirect($url);
							echo CJSON::encode(['success'=>true,'refreshQuotes'=>1]);
							Yii::app()->end();
					}

					$isAirport = $model->bookingRoutes[0]->brtFromCity->cty_is_airport;
					if ($model->bkg_agent_id == Config::get('Mobisign.partner.id') || $model->bkg_booking_type == 4 || ($isAirport == 1 && $model->bkg_is_gozonow == 1) || ($isAirport == 1 && (in_array($model->bkg_booking_type, [9, 10, 11]))))
					{
						if (!BookingRoute::validateAddress($model))
						{
							$this->forward("booking/address");
						}
						$return = Booking::saveBookingWithAddress($model);
						if ($return['isMAgent'] != 1)
						{
							$this->redirect($return['url']);
						}
						echo CJSON::encode($return);
						Yii::app()->end();
					}

					$this->forward("booking/address");

					Yii::app()->end();
				}
			}
		}
		renderview:
        /************* Flash Sale *******************************************/
        if($model->bkg_booking_type == 1)
        {
            $flashModel = new CabAvailabilities();
            $flashBooking    = $flashModel->fetchFlashSaleBooking($model);
        }
        /************* Flash Sale *******************************************/
        
		$this->pageRequest->step = $step;
		$view					 = ($objBooking->agentId == Config::get('Mobisign.partner.id')) ? 'bkQuoteNew_M' : 'bkQuoteNew';
		$this->renderAuto($view, array('step' => $step, 'pageid' => $step, 'model' => $model, 'tncArr' => $tncArr, 'serviceClassDesc' => $serviceClassDesc, 'tierBrkUp' => $arrQuoteCat, 'prefCategory' => $prefCategory, 'userCredit' => $userCredit, 'flashBooking' => $flashBooking), false, true);
	}

	public function actionBkGNowInventory()
	{

		$rid = Yii::app()->request->getParam('rid');
		if ($rid == '')
		{
			throw new Exception('Invalid Request', 400);
		}
		$showPriceRange = Yii::app()->request->getParam('showPriceRange');
		if (!$showPriceRange)
		{
			$this->renderAuto('bkGNowInventory', ['rid' => $rid]);
			Yii::app()->end();
		}
	}

	public function actionAddress()
	{
		$this->enableClarity();
		$request = Yii::app()->request;

		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_ADDRESS);
		$this->layout = 'column_booking';

		$objPage	 = $this->getRequestData();
		$objBooking	 = $objPage->booking;

		if (!$request->isAjaxRequest && $objBooking->cabType == '')
		{
			$this->forward("booking/catQuotes");
		}

		if ($objBooking->cab == null)
		{
			$objBooking->cab = new Stub\common\Cab();
		}

		$objBooking->cab->sClass = Yii::app()->request->getParam('cabclass');
		$step					 = 13;
		$pageID					 = $request->getParam('step');

		$flashRequest	 = Yii::app()->request->getParam('flashBooking');
		$bkgModel		 = Booking::getNewInstance();
		$model			 = $objBooking->getLeadModel();
		$model->bkg_platform = Booking::Platform_User;
		if (!$request->isPostRequest)
		{
			$this->pageRequest->populateQuote($model);

			if ($this->pageRequest->isSelectAvailable())
			{
				$this->pageRequest->step = 10;
			}
			else if ($model->bkg_is_gozonow > 0)
			{
				$this->pageRequest->step = 8;
			}
			else
			{
				$this->pageRequest->step = 9;
			}
		}

		if ($model && $model->bkg_user_id != '' && $model->bkg_agent_id == null)
		{
			$GLOBALS["GA4_USER_ID"] = $model->bkg_user_id;
		}
		$ussermodel = Yii::app()->user->loadUser();
		if ($flashRequest == 1)
		{
			$cavhash = Yii::app()->request->getParam('cavhash');
			$cavId	 = Yii::app()->shortHash->unHash($cavhash);
			$model	 = BookingTemp::model()->populateFromCabAvailabilities($cavId);
			goto renderView;
		}

		if ($request->isPostRequest && $pageID == 13)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$bookingRoutes = $request->getParam("BookingRoute");
				if ($request->cookies->contains('itineraryCookie'))
				{
					$cookieObj			 = Yii::app()->request->cookies['itineraryCookie']->value;
					$rawItineraryCookie	 = Yii::app()->request->cookies['rawItineraryCookie']->value;

					$routeData										 = \Beans\booking\Route::setData($rawItineraryCookie, $bookingRoutes, $model->bkg_booking_type);
					$itineraryCookie								 = new CHttpCookie('itineraryCookie', $routeData);
					Yii::app()->request->cookies['itineraryCookie']	 = $itineraryCookie;
				}
				$bookingTemp			 = $request->getParam("BookingTemp");
				$success				 = $model->save();
				$model->pickup_later_chk = $bookingTemp['pickup_later_chk'];
				$model->drop_later_chk	 = $bookingTemp['drop_later_chk'];
				if (!$success)
				{
					throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
				$routes = $model->bookingRoutes;
				foreach ($bookingRoutes as $key => $brtRoute)
				{
					if (isset($brtRoute["from_place"]) && $brtRoute["from_place"] != '')
					{
						$routes[$key]->applyPlace($brtRoute["from_place"], 1);
					}
					if (isset($brtRoute["to_place"]) && $brtRoute["to_place"] != '')
					{
						if ($key > 0)
						{
							$routes[$key]->applyPlace($bookingRoutes[$key - 1]["to_place"], 1);
						}
						$routes[$key]->applyPlace($brtRoute["to_place"], 2);
					}
				}
				if (!BookingRoute::validateAddress($model))
				{
					throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
				}

				$model->setRoutes($routes);
				$success = $model->save();
				if (!$success)
				{
					throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
				$bkgModel->bkgTravellBy	 = $model->bkgTravellBy;
				$bkgId					 = $bkgModel->saveBooking($model->bkg_id);
				$bkgModel				 = Booking::model()->findByPk($bkgId);

				if ($model->bkgAddonDetails != null)
				{
					BookingInvoice::model()->updateAddonsData($model->bkgAddonDetails, $bkgModel);
				}
				if ($model->bkg_agent_id == Config::get('Mobisign.partner.id') || $model->bkg_agent_id == Config::get('Kayak.partner.id'))
				{
					goto skipusercontact;
				}
				$userModel = Users::model()->findByPk($bkgModel->bkgUserInfo->bkg_user_id);
				if ($model->bkgTravellBy == null)
				{
					$contactId = ContactProfile::getByEntityId($userModel->user_id);
					if ($contactId == '')
					{
						$contactId = $userModel->usr_contact_id;
					}
					if ($contactId != '')
					{
						$contactModel = Contact::model()->findByPk($contactId);
					}

					$emailId				 = ContactEmail::getContactEmailById($contactId);
					//$model->bkgTravellBy = 1;
					$model->bkg_user_name	 = $contactModel->ctt_first_name;
					$model->bkg_user_lname	 = $contactModel->ctt_last_name;
					$model->bkg_user_email	 = $emailId;

					if (!Yii::app()->user->isGuest)
					{
						if ($model->bkg_user_name == '' && Yii::app()->user->loadUser()->usr_name != '')
						{
							$model->bkg_user_name	 = Yii::app()->user->loadUser()->usr_name;
							$userModel->usr_name	 = $model->bkg_user_name;
							if ($contactModel != null)
							{
								$contactModel->ctt_first_name = $model->bkg_user_name;
								$contactModel->save();
							}
							$userModel->save();
						}
						if ($model->bkg_user_lname == '' && Yii::app()->user->loadUser()->usr_lname != '')
						{
							$model->bkg_user_lname	 = Yii::app()->user->loadUser()->usr_lname;
							$userModel->usr_lname	 = $model->bkg_user_lname;
							if ($contactModel != null)
							{
								$contactModel->ctt_last_name = $model->bkg_user_lname;
								$contactModel->save();
							}
							$userModel->save();
						}
					}
					$model->save();
				}
				$bkgModel->bkgUserInfo->bkg_contact_id = ($bkgModel->bkgUserInfo->bkg_contact_id=='')?(($contactModel->ctt_id==0 || $contactModel->ctt_id==null)?$userModel->usr_contact_id:$contactModel->ctt_id):$bkgModel->bkgUserInfo->bkg_contact_id;
				skipusercontact:
				$bkgModel->bkgUserInfo->bkg_user_fname		 = ($model->bkg_user_name == '') ? $userModel->usr_name : $model->bkg_user_name;
				$bkgModel->bkgUserInfo->bkg_user_lname		 = ($model->bkg_user_lname == '') ? $userModel->usr_lname : $model->bkg_user_lname;
				$bkgModel->bkgUserInfo->bkg_country_code	 = ($bkgModel->bkgUserInfo->bkg_country_code == 0) ? $userModel->usr_country_code : $bkgModel->bkgUserInfo->bkg_country_code;
				$bkgModel->bkgUserInfo->bkg_contact_no		 = ($model->bkg_contact_no=='')?$userModel->usr_mobile:$model->bkg_contact_no;
				$bkgModel->bkgUserInfo->bkg_traveller_type	 = $model->bkg_traveller_type;
				
				$bkgModel->bkgUserInfo->bkg_user_email	 = ($model->bkg_user_email == '') ? $userModel->usr_email : $model->bkg_user_email;
				$result									 = BookingUser::model()->updateData($bkgModel->bkgUserInfo, $bkgId);
				if ($result)
				{

					$arrResult = BookingRoute::updateDistance($bkgModel, $bkgId);
				}
				DBUtil::commitTransaction($transaction); 
				//DBUtil::rollbackTransaction($transaction);
				$success = true;
				$hash	 = Yii::app()->shortHash->hash($bkgId);

				$url = Yii::app()->createUrl('booking/review', ['bkgid' => $bkgId, 'hash' => $hash]);

//				if ($bkgModel->bkgPref->bkg_is_gozonow == 1 && ($bkgModel->bkg_drop_address == $bkgModel->bkgToCity->cty_garage_address))
				if ($bkgModel->bkgPref->bkg_is_gozonow == 1)
				{
					$tripId		 = $bkgModel->bkg_bcb_id;
					$dataexist	 = BookingVendorRequest::getPreferredVendorbyBooking($tripId);

					if (!$dataexist)
					{
						//actionNotifyVendor
					}

					$url = Yii::app()->createAbsoluteUrl('gznow/' . $bkgId . '/' . $hash);
				}
				$response = ['success' => $success, 'url' => $url, 'data' => $arrResult];
				if ($success)
				{
					$ga4Data			 = Beans\ga4\Ecommerce::addToCart($bkgModel);
					$response["ga4data"] = Filter::removeNull($ga4Data);
				}
				echo json_encode($response);
				Yii::app()->end();
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				$returnSet = ReturnSet::setException($ex);
				echo json_encode($returnSet);
				Yii::app()->end();
			}
		}
		$tripId = $bkgModel->bkg_bcb_id;

		renderView:
		$prevstep				 = $this->pageRequest->step;
		$this->pageRequest->step = $step;
		$this->renderAuto("bkAddress", ['step'			 => $step, 'prevstep'		 => $prevstep,
			'pageid'		 => $step, 'model'			 => $model, 'bookingTemp'	 => $model,
			'usermodel'		 => $bkgModel->bkgUserInfo, 'hash'			 => $hash]);
	}

	public function actionMoreTierQuotes()
	{
		/** @var HttpRequest $request */
		$request = Yii::app()->request;

		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_MORETIRE);

		$this->layout	 = 'column_booking';
		$step			 = 10;
		$pageID			 = $request->getParam('pageID');
		$params			 = [];
		$params['pstep'] = $step;
		$objPage		 = $this->getRequestData();
		$objBooking		 = $objPage->booking;
		$cabCategory	 = $objBooking->cab->categoryId;
		$serviceClass	 = $objBooking->cab->cabCategory->scvVehicleServiceClass;

		if ($request->isPostRequest && $pageID == 10)
		{
			$vehicleTypeId					 = $request->getParam('cabmodel');
			$objBooking->cabServiceClass	 = $serviceClass;
			$objBooking->bkg_vehicle_type_id = $vehicleTypeId;
			$objBooking->cabType			 = $vehicleTypeId;
			$objBooking->cab->cabCategory->setData($vehicleTypeId);
			$objBooking->cabModel			 = $objBooking->cab->cabCategory->scvVehicleModel;
			foreach ($objPage->quote->cabRate as $key => $rate)
			{
				if ($rate->cab->id == $objBooking->cab->cabCategory->id)
				{
					$objBooking->fare->advanceReceived	 = $rate->fare->advanceReceived;
					$objBooking->fare->totalAmount		 = $rate->fare->totalAmount;
				}
			}

			$objPage->updatePostData();
			if (Yii::app()->user->isGuest)
			{
				throw new CHttpException(403, "Login required.", ReturnSet::ERROR_UNAUTHORISED);
			}
			else
			{
				$this->pageRequest->step = $step;
				$objPage->updatePostData();
				$this->forward("booking/address");
				Yii::app()->end();
			}
		}
		$this->renderAuto('showVehicleModel', array('step' => $step, 'pageid' => $step, 'svcSelectModel' => $baseFare), false, true);
	}

	public function actionSaveLead()
	{

		$objPage	 = $this->getRequestData();
		$objBooking	 = $objPage->booking;

		$model	 = $objBooking->getLeadModel();
		$user	 = Yii::app()->user->loadUser();

		$userInfo = UserInfo::model();

		$model->bkg_user_device	 = UserLog::model()->getDevice();
		$model->bkg_platform	 = Booking::Platform_User;
		$model->bkg_contact_no	 = $user->usr_mobile;
		$model->bkg_user_email	 = $user->usr_email;
		$model->bkg_country_code = $user->usr_country_code;
		$model->bkg_user_id		 = UserInfo::getUserId();

		//$model->save();
		$routeDt				 = $model->setRoutes($model->bookingRoutes);
		$model->bkg_route_data	 = CJSON::encode($routeDt);

		$qt		 = $model->createLeadAndGetQuotes();
		$objPage = \Stub\common\PageRequest::getInstance();
		$objPage->clearQuote($rid);
		$objPage->clearRequest($rid);

		$this->redirect(['booking/book', "lead" => $model->bkg_id]);
	}

	public function actionBook()
	{
		$leadId = Yii::app()->request->getParam('lead', null);
		if (!$leadId)
		{
			throw new Exception('Invalid Request', 400);
		}
		$bkgID	 = Booking::model()->saveBooking($leadId);
		$hash	 = Yii::app()->shortHash->hash($bkgID);
		$model	 = Booking::model()->findByPk($bkgID);
		if ($model->bkgPref->bkg_is_gozonow == 1)
		{
			$desc = 'Gozo now initiated';
			BookingLog::model()->createLog($model->bkg_id, $desc, UserInfo::getInstance(), BookingLog::BOOKING_CREATED, false);
		}


		$this->redirect(['booking/address', "bkgid" => $model->bkg_id, 'hash' => $hash]);
	}

	public function actionReview()
	{
		$this->enableClarity();

		/** @var HttpRequest $request */
		$request = Yii::app()->request;

		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_REVIEW);

		$step			 = 11;
		$id				 = Yii::app()->request->getParam("bkgid");
		$hash			 = Yii::app()->request->getParam('hash');
		$gozocoinApply	 = 0;
		$cashbackStatus	 = true;
		$agent_id		 = isset(Yii::app()->request->cookies['gozo_agent_id']) ? Yii::app()->request->cookies['gozo_agent_id']->value : '';
		$hashVal		 = Yii::app()->shortHash->hash($id);

		$model	 = Booking::model()->findByPk($id);
		$isAgent = (int) ( $model->bkg_agent_id > 0 && $model->bkg_agent_id != 1249) ? 1 : 0;

		if ($hash != $hashVal)
		{
			throw new CHttpException(400, 'Invalid booking data');
		}
		/** @var Booking $model */
		foreach ($model->bookingRoutes as $key => $bookingRoute)
		{
			$pickupCity[]	 = $bookingRoute->brt_from_city_id;
			$dropCity[]		 = $bookingRoute->brt_to_city_id;
			$pickup_date[]	 = $bookingRoute->brt_pickup_datetime;
			$temp_last_date	 = strtotime($bookingRoute->brt_pickup_datetime) + $bookingRoute->brt_trip_duration;
			$drop_date_time	 = date('Y-m-d H:i:s', $temp_last_date);
		}
		$pickup_date_time		 = $pickup_date[0];
		$locationArr			 = array_unique(array_merge($pickupCity, $dropCity));
		$dateArr				 = array($pickup_date_time, $drop_date_time);
		$noteArr				 = DestinationNote::model()->showBookingNotes($locationArr, $dateArr, $showNoteTo				 = 1);
		$bkgUserModel			 = BookingUser::model()->find('bui_bkg_id=:bkg_id', ['bkg_id' => $model->bkg_id]);
		$bkgUserModel->scenario	 = 'advance_pay';
		$bkgInvModel			 = BookingInvoice::model()->find('biv_bkg_id=:bkg_id', ['bkg_id' => $model->bkg_id]);
		$bkgInvModel->scenario	 = 'advance_pay';

		if (!$model || !$bkgUserModel || !$bkgInvModel)
		{
			throw new CHttpException(400, 'Invalid data');
		}
		if ($model->bkg_status > 7 && $model->bkg_status != 15)
		{
			throw new CHttpException(401, 'Booking not active');
		}
		$minDiff = $model->getPaymentExpiryTimeinMinutes();

		if ($bkgUserModel->bkg_user_id != null && $model->bkg_agent_id == null)
		{
			$GLOBALS["GA4_USER_ID"] = $bkgUserModel->bkg_user_id;
		}


		if ($transCode != '')
		{
			$paymentdone = true;
			$payment	 = true;
			$transResult = $this->getTransdetailByTranscode($transCode);
			$succ		 = 'fail';
			if ($transResult)
			{
				$transId	 = $transResult['transId'];
				$succ		 = $transResult['succ'];
				$tranStatus	 = $transResult['tranStatus'];
			}
		}
		/////////////////////
		// 5% discount =advpay=
		if ($model->bkgInvoice->bkg_promo1_id > 0 && ($model->bkgInvoice->bkg_advance_amount == 0 || $model->bkgInvoice->bkg_advance_amount == '' || $model->bkgInvoice->bkg_advance_amount == null) && ($model->bkgInvoice->bkg_discount_amount == 0 || $model->bkgInvoice->bkg_discount_amount == '' || $model->bkgInvoice->bkg_discount_amount == null) && ($model->bkgInvoice->bkg_credits_used == 0 || $model->bkgInvoice->bkg_credits_used == '' || $model->bkgInvoice->bkg_credits_used == null) && $isAgent == 0)
		{
			$promoModel = Promos::model()->findByPk($model->bkgInvoice->bkg_promo1_id);
			if (!$promoModel)
			{
				throw new Exception('Invalid Promo code');
			}
			if ($promoModel->prm_activate_on == 1)
			{
				$promoModel->promoCode	 = $model->bkgInvoice->bkg_promo1_code;
				$promoModel->totalAmount = $model->bkgInvoice->bkg_base_amount;
				$promoModel->createDate	 = $model->bkg_create_date;
				$promoModel->pickupDate	 = $model->bkg_pickup_date;
				$promoModel->fromCityId	 = $model->bkg_from_city_id;
				$promoModel->toCityId	 = $model->bkg_to_city_id;
				$promoModel->userId		 = $model->bkgUserInfo->bkg_user_id;
				$promoModel->platform	 = $model->bkgTrail->bkg_platform;
				$promoModel->carType	 = $model->bkg_vehicle_type_id;
				$promoModel->bookingType = $model->bkg_booking_type;
				$promoModel->noOfSeat	 = $model->bkgAddInfo->bkg_no_person;
				$promoModel->bkgId		 = $model->bkg_id;
				$promoModel->email		 = '';
				$promoModel->phone		 = '';
				$promoModel->imEfect	 = '';

				$discountArr = $promoModel->applyPromoCode();
				if (isset($discountArr['nextTripApply']) && $discountArr['nextTripApply'] == 1)
				{
					$discountArr['cash']	 = 0;
					$discountArr['coins']	 = 0;
				}
				$model->bkgInvoice->bkg_discount_amount	 = $discountArr['cash'];
				$model->bkgInvoice->bkg_promo1_amt		 = $discountArr['cash'];
				if ($discountArr['coins'] > 0)
				{
					$cashbackStatus = UserCredits::checkDuplicateCashbackStatus($model->bkg_id, $model->bkgUserInfo->bkg_user_id, $discountArr['coins']);
				}
			}
		}

		if ($iscreditapplied > 0)
		{
			$model->bkgInvoice->bkg_credits_used = $iscreditapplied;
		}

		$model->bkgInvoice->calculateConvenienceFee(0);
		$model->bkgInvoice->calculateTotal();
		if ($minDiff > 0)
		{
			$amount								 = ($model->bkgInvoice->bkg_advance_amount > 0 || $model->bkgInvoice->bkg_credits_used > 0) ? $model->bkgInvoice->bkg_due_amount : $model->bkgInvoice->bkg_total_amount;
			$model->bkgInvoice->partialPayment	 = round($amount);
		}

		$promoModel				 = new Promos();
		$promoModel->promoCode	 = '';
		$promoModel->totalAmount = $model->bkgInvoice->bkg_base_amount;
		$promoModel->createDate	 = $model->bkg_create_date;
		$promoModel->pickupDate	 = $model->bkg_pickup_date;
		$promoModel->fromCityId	 = $model->bkg_from_city_id;
		$promoModel->toCityId	 = $model->bkg_to_city_id;
		$promoModel->userId		 = $model->bkgUserInfo->bkg_user_id;
		$promoModel->platform	 = $model->bkgTrail->bkg_platform;
		$promoModel->carType	 = $model->bkg_vehicle_type_id;
		$promoModel->bookingType = $model->bkg_booking_type;
		$promoModel->bkgId		 = $model->bkg_id;
		if (!$model->bkg_cav_id)
		{
			$promoArr = $promoModel->getApplicableCodes();
		}
		if ($isAgent == 1)
		{
			$promoArr = [];
		}
		$model1				 = clone $model;
		$model1->bkgInvoice->calculateConvenienceFee(0);
		$userCreditStatus	 = UserCredits::model()->getGozocoinsUsesStatus($model->bkgUserInfo->bkg_user_id);
		if ($userCreditStatus == 1 && ($model1->bkgInvoice->bkg_advance_amount == 0 || $model1->bkgInvoice->bkg_advance_amount == '') && $cashbackStatus)
		{
			$gozocoinApply	 = 1;
			$usepromo		 = true;
		}
		else
		{
			$usepromo = ($model->bkgInvoice->bkg_promo1_id == 0);
		}
		$MaxCredits	 = UserCredits::getApplicableCredits($model->bkgUserInfo->bkg_user_id, $model->bkgInvoice->bkg_base_amount, $usepromo, $model->bkg_from_city_id, $model->bkg_to_city_id);
		$creditVal	 = $MaxCredits['credits'];

		$model->bkgUserInfo	 = $bkgUserModel;
		$walletBalance		 = UserWallet::model()->getBalance(UserInfo::getUserId());
		$refcode			 = "";
		$whatappShareLink	 = "";
		if ($model->bkgUserInfo->bkg_user_id > 0)
		{
			$users				 = Users::model()->findByPk($model->bkgUserInfo->bkg_user_id);
			$refcode			 = $users->usr_refer_code;
			$whatappShareLink	 = Users::model()->whatsappShareTemplate($refcode);
		}
		$addOns		 = AddonServiceClassRule::getApplicableAddons($model->bkg_from_city_id, $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_id, $model->bkgInvoice->bkg_base_amount);
		$routeRates	 = BookingInvoice::getRatesWithAddons($addOns, $model->bkgInvoice);
		$this->renderAuto('bkReview', array('hash'				 => $hash, 'isredirct'			 => true, 'model'				 => $model,
			'creditVal'			 => $creditVal, 'model1'			 => $model1, 'showUserInfoPickup' => $showUserInfoPickup,
			'paymentdone'		 => $paymentdone, 'transid'			 => $transId, 'succ'				 => $succ, 'note'				 => $noteArr,
			'promoArr'			 => $promoArr, 'userCreditStatus'	 => $userCreditStatus, 'gozocoinApply'		 => $gozocoinApply, 'walletBalance'		 => $walletBalance, 'refcode'			 => $refcode, 'whatappShareLink'	 => $whatappShareLink, 'applicableAddons'	 => $addOns, 'routeRatesArr'		 => $routeRates, 'pageId'			 => $step), false, true);
	}

	public function actionBkgConfirmation()
	{
		//$this->layout	 = 'column_booking';
		/** @var HttpRequest $request */
		$bkgId	 = Yii::app()->request->getParam('id');
		$model	 = Booking::model()->findByPk($bkgId);
		$request = Yii::app()->request;

		$this->renderAuto('bkconfirmation', array('isredirct' => true, 'model' => $model), false, true);
	}

	public function actionTypeContent()
	{
		$type	 = Yii::app()->request->getParam('type');
		$data	 = TncPoints::getTypeContent($type);
		$result	 = ['text' => $data, 'type' => $type];
		echo json_encode($result);
	}

	public function actionResendOtp()
	{

		$request = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType());

		$this->pageTitle = "Resend Otp";
		$returnset		 = new ReturnSet();

		$requestId			 = $request->getParam('rid');
		$verifyData			 = $request->getParam('verifyData');
		$existingContactOTP	 = $request->getParam('existingContactOTP');

		$data = Yii::app()->JWT->decode($verifyData);
		if ($data->type == null && $existingContactOTP == 1)
		{
			$decode	 = Yii::app()->JWT->decode($verifyData);
			$data	 = $decode[0];
		}
		try
		{
			/* @var $page \Stub\common\PageRequest */
//			$objPage		 = \Stub\common\PageRequest::getInstance();
			//$objPage->addRequest($requestId);
			$objPage = $this->getRequestData();

			$objCttVerify = $objPage->getContact($data->type, $data->value);

			if ($objCttVerify->otpRetry >= 3)
			{
				throw new Exception("Time exceed you can send it later", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			if ($objCttVerify->otpValidTill > time())
			{
				throw new Exception("OTP not send", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
// 			Contact::verifyOTP($objCttVerify);
//
//	
//					$objPage->updateSession();
			if ($data->value == 2)
			{
				Filter::parsePhoneNumber($data->value, $code, $number);
				$canSendSMS = ContactPhone::checkTosendSMS($code, $number, SmsLog::SMS_LOGIN_REGISTER);
				if ($canSendSMS == false && $code != 91)
				{
					throw new Exception("International sms limit over.", ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
			}
			Contact::verifyOTP($objCttVerify);
			$objPage->updatePostData();

			$arrVerifyData	 = ["type" => $objCttVerify->type, "value" => $objCttVerify->value, "isSendSMS" => $objCttVerify->isSendSMS, 'otp' => $objCttVerify->otp];
			$otpObj			 = $objCttVerify;
			$otpObjectEnp	 = Yii::app()->JWT->encode($otpObj);
			$verifyData		 = Yii::app()->JWT->encode($arrVerifyData);

			Filter::removeNull($objPage);
			if ($objPage)
			{
				$returnset->setStatus(true);
				$returnset->setData(['rdata'		 => $this->pageRequest->getEncrptedData(),
					'verifyData' => $verifyData, 'otpObject'	 => $otpObjectEnp]);
			}
		}
		catch (Exception $e)
		{
			$returnset = ReturnSet::setException($e);
		}
//		$result = ['success' => $success];
//		echo json_encode($result);
		echo json_encode($returnset);
		Yii::app()->end();
	}

	private function createLog($identity)
	{
		$ip							 = \Filter::getUserIP();
		$sessionid					 = Yii::app()->getSession()->getSessionId();
		$logModel					 = new UserLog();
		$logModel->log_in_time		 = new CDbExpression('Now()');
		$logModel->log_ip			 = $ip;
		$logModel->log_session		 = $sessionid;
		$logModel->log_device_info	 = $_SERVER['HTTP_USER_AGENT'];
		$logModel->log_user			 = $identity->getId();

		$logModel->save();
		return true;
	}

	public function actionPreviousStep()
	{
		$request	 = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType());
		$requestId	 = $request->getParam('rid');
		$step		 = $request->getParam('step');
		$objPage	 = \Stub\common\PageRequest::getInstance();
		$params		 = [];

		if ($requestId != '')
		{
			$params["rid"] = $requestId;
		}

		if ($step == 1)
		{
			$routes = ["booking/bookNow1"] + $params;
		}
		if ($step == 2)
		{
			$routes = ["booking/bookNow1"] + $params;
		}
		if ($step == 3)
		{
			$routes = ["booking/signin"] + $params;
		}
		if ($step == 4)
		{
			$routes = ["booking/bookNow1"] + $params;
		}

		if ($step == 5)
		{
			$routes = ["booking/tripType"] + $params;
		}

		if ($step == 6)
		{
			$routes = ["booking/bkgType"] + $params;
		}

		if ($step == 7)
		{
			$routes = ["booking/itinerary"] + $params;
		}
		if ($step == 8)
		{
			$routes = ["booking/catQuotes"] + $params;
		}

		if ($step == 9)
		{
			$routes = ["booking/tierQuotes"] + $params;
		}

		if ($step == 10)
		{
			$routes = ["booking/tierQuotes"] + $params;
		}

		if ($step == 11)
		{
			$routes = ["booking/address"] + $params;
		}

		if ($routes == null || (!$objPage->hasRequest($requestId) && $step >= 7))
		{
			$routes = ["booking/bookNow1"];
		}

		$this->redirect($routes);
	}

	public function actionPaymentReview()
	{
		$this->enableClarity();
		$request			 = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_PAY);
		$step				 = 12;
		$hash				 = Yii::app()->request->getParam('hash');
		$bkgId				 = Yii::app()->shortHash->unhash($hash);
		$hashVal			 = Yii::app()->shortHash->hash($bkgId);
		$additionalParams	 = Yii::app()->request->getParam('additionalParams');
		$minPayExtra		 = Yii::app()->request->getParam('minPayExtra');
		if ($hash != $hashVal)
		{
			$this->layout	 = 'head';
			$massage		 = 'Invalid booking data';
			$this->renderAuto("linkExpired", array('massage' => $massage));
			goto skipStep;
		}
		$model = Booking::model()->findByPk($bkgId);

		if (!$model || !in_array($model->bkg_status, [15, 2, 3, 5]))
		{
			$url = Yii::app()->createUrl('booking/paynow', ['id' => $bkgId, 'hash' => $hash]);
			$this->redirect($url);
			goto skipStep;
		}
		if ($model->bkg_reconfirm_flag != 1)
		{
			//	$model->bkgInvoice->applyPromoCode($model->bkgInvoice->bkg_promo1_code);
		}
		$model->bkgInvoice->calculateTotal();
		$bkgDueAmount	 = $model->bkgInvoice->bkg_due_amount;
		$bkgMinAmount	 = $model->bkgInvoice->calculateMinPayment();
		$prevAdvance	 = 0;
//		if($minPayExtra>0)
//		{
//			$bkgMinAmount = $minPayExtra;
		if ($model->bkgPref->bpr_rescheduled_from > 0 && in_array($model->bkg_status, [1, 15]))
		{
			$prevModel = Booking::model()->findByPk($model->bkgPref->bpr_rescheduled_from);
			if ($prevModel->bkg_status == 2)
			{
				$model->calculateExtraMinPayOnReschedule($prevModel);
				$bkgMinAmount	 = $model->minPayExtra;
				$prevAdvance	 = Booking::getMaxToTransferOnReschedule($model->bkgPref->bpr_rescheduled_from);
			}
		}
//      }

		$walletBalance = UserWallet::model()->getBalance(UserInfo::getUserId());
		$this->renderAuto('bkPayment', array('model' => $model, 'bkgMinAmount' => $bkgMinAmount, 'bkgDueAmount' => $bkgDueAmount, 'walletBalance' => $walletBalance, 'additionalParams' => $additionalParams, 'pageId' => $step, 'minPayExtra' => $minPayExtra, 'prevAdvance' => $prevAdvance), false, true);
		skipStep:
	}

	public function actionPaymentv3()
	{
		$request			 = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_PAYINTERNAL);
		$id					 = Yii::app()->request->getParam('id');
		$hash				 = Yii::app()->request->getParam('hash');
		$phash				 = Yii::app()->request->getParam('pHash');
		$ehash				 = Yii::app()->request->getParam('eHash');
		$additionalParams	 = Yii::app()->request->getParam('additionalParams');

		if ($id == '')
		{
			$bkgVal	 = Yii::app()->request->getParam('Booking');
			$id		 = $bkgVal['bkg_id'];
			$hash	 = $bkgVal['hash'];
		}

		$platform		 = Yii::app()->request->getParam('platform');
		$iscreditapplied = Yii::app()->request->getParam('iscreditapplied', 0);
		$src			 = Yii::app()->request->getParam('src', 2);
		$gozocoinApply	 = 0;
		$cashbackStatus	 = true;
		$agent_id		 = isset(Yii::app()->request->cookies['gozo_agent_id']) ? Yii::app()->request->cookies['gozo_agent_id']->value : '';

		$bivResp	 = Yii::app()->request->getParam('BookingInvoice');
		$payubolt	 = $bivResp['payubolt'];
		if ($payubolt == 1)
		{
			$bkgVal	 = Yii::app()->request->getParam('Booking');
			$id		 = $bkgVal['bkg_id'];
			$hash	 = $bkgVal['hash'];
		}

		$model			 = Booking::model()->findByPk($id);
		$bkgUserModel	 = BookingUser::model()->find('bui_bkg_id=:bkg_id', ['bkg_id' => $model->bkg_id]);
		$paymentRzType	 = Yii::app()->request->getParam('BookingInvoice')['paymentType'];
		if ($paymentRzType != 21)
		{
			$bkgUserModel->scenario = 'advance_pay';
		}

		$bkgInvModel			 = BookingInvoice::model()->find('biv_bkg_id=:bkg_id', ['bkg_id' => $model->bkg_id]);
		$bkgInvModel->scenario	 = 'advance_pay';

		if (!$model || !$bkgUserModel || !$bkgInvModel)
		{
			throw new CHttpException(400, 'Invalid data');
		}
		if ($model->bkg_status > 7 && $model->bkg_status != 15)
		{
			throw new CHttpException(401, 'Booking not active');
		}

		if (isset($phash) && $phash != '')
		{
			$otpPhone = Yii::app()->shortHash->unHash($phash);
			if (($otpPhone == $model->bkgUserInfo->bkg_verification_code) && ($model->bkgUserInfo->bkg_phone_verified == 0))
			{
				$bkgUserModel->bkg_phone_verified = 1;
				$bkgUserModel->save();
			}
		}
		if (isset($ehash) && $ehash != '')
		{
			$otpEmail = Yii::app()->shortHash->unHash($ehash);
			if (($otpEmail == $model->bkgUserInfo->bkg_verifycode_email) && ($model->bkgUserInfo->bkg_email_verified == 0))
			{
				$bkgUserModel->bkg_email_verified = 1;
				$bkgUserModel->save();
			}
		}
		$minDiff = $model->getPaymentExpiryTimeinMinutes();
		if ($minDiff > 0)
		{

			//	$preBillingDetail = true;
			//if ($model->bkgUserInfo->bkg_bill_postalcode == ''){
			//{
			//$preBillingDetail = false;
			//	}

			if (isset($_POST['Booking']) || isset($_POST['BookingInvoice']) || isset($_POST['BookingUser']))
			{
				$payment						 = new BraintreeCCForm('charge');
				$model->attributes				 = Yii::app()->request->getParam('Booking');
				$bkgInvModel->isAdvPromoPaynow	 = $_POST['BookingInvoice']['isAdvPromoPaynow'];
				$bkgInvModel->attributes		 = Yii::app()->request->getParam('BookingInvoice');

				$paymentType	 = Yii::app()->request->getParam('BookingInvoice')['paymentType'];
				$partialPayment	 = Yii::app()->request->getParam('BookingInvoice')['partialPayment'];

				$bkgUserModel->attributes	 = Yii::app()->request->getParam('BookingUser');
				$bankCode1					 = Yii::app()->request->getParam('BookingUser')['bkg_bill_bankcode1'];
				$bankCode2					 = Yii::app()->request->getParam('BookingUser')['bkg_bill_bankcode2'];
				if ($bankCode1 != '')
				{
					$bkgUserModel->bkg_bill_bankcode = $bankCode1;
				}
				elseif ($bankCode2 != '')
				{
					$bkgUserModel->bkg_bill_bankcode = $bankCode2;
				}

				if ($_POST['AdvPromoRadio'] != '')
				{
					$promoModel1 = Promos::model()->getByCode($_POST['AdvPromoRadio']);
					if ($promoModel1->prm_activate_on == 1)
					{
						$model->bkgInvoice->bkg_promo1_code	 = $_POST['AdvPromoRadio'];
						$model->bkgInvoice->bkg_promo1_id	 = $promoModel1->prm_id;
					}
				}

				if ($paymentType == 14 || $paymentType == 15)
				{
					$model->scenario		 = 'lazypay';
					$bkgUserModel->scenario	 = 'lazypay';
					$bkgInvModel->scenario	 = 'lazypay';
				}
				$ebsOpt									 = $model->ebsOpt;
				$bModel									 = clone $model;
				$bModel->bkgInvoice->isAdvPromoPaynow	 = $model->bkgInvoice->isAdvPromoPaynow;
				if ($bModel->bkgInvoice->bkg_advance_amount > 0)
				{
					$creditsUse = 0;
				}
				else
				{
					$creditsUse = Yii::app()->request->getParam('isPayNowCredits', 0);
				}
				$allowCreditApply = false;
				//				if ($creditsUse > 0 && $bkgInvModel->bkg_credits_used == 0)
				//				{
				//					$allowCreditApply				 = true;
				//					$bkgInvModel->bkg_credits_used	 = ($bkgInvModel->bkg_credits_used > 0) ? ($bkgInvModel->bkg_credits_used + $creditsUse) : $creditsUse;
				//				}

				if ($_POST['isWalletUsed'] != '' && $_POST['isWalletUsed'] == 1 && $_POST['walletUsedAmt'] > 0)
				{
					$bkgInvModel->bkg_is_wallet_selected = 1;
					$bkgInvModel->bkg_wallet_used		 = $_POST['walletUsedAmt'];
				}
				$bkgUserModel->ptype = $paymentType;

				$additionalObj = json_decode($additionalParams);
				try
				{
					$model->bkgInvoice->savePromoCoins($additionalObj->code, $additionalObj->coins);
					$bkgInvModel->applyPromoCode($bkgInvModel->bkg_promo1_code);
				}
				catch (Exception $e)
				{
					$return['success']	 = false;
					$return['error']	 = json_decode($e->getMessage());
					echo json_encode($return);
					Yii::app()->end();
				}
				$result	 = CActiveForm::validate($bModel);
				$result1 = CActiveForm::validate($bkgUserModel);
				$result2 = CActiveForm::validate($bkgInvModel);
				if ($paymentType == 9)
				{
					$ccData					 = $_POST['BraintreeCCForm'];
					$payment->setScenario('custom');
					$payment->amount		 = $partialPayment;
					$payment->paymentType	 = $paymentType;

					$result1 = CActiveForm::validate($payment);
				}
				$return	 = ['success' => false, 'url' => ''];
				$models	 = [];
				if ($result == '[]' && $result1 == '[]' && $result2 == '[]')
				{
					$transaction = DBUtil::beginTransaction();
					try
					{
						$bookingInvoice = BookingInvoice::model()->getByBookingID($model->bkg_id);
						if ($_POST['isWalletUsed'] != '' && $_POST['isWalletUsed'] == 1 && $_POST['walletUsedAmt'] > 0)
						{
							$bookingInvoice->bkg_is_wallet_selected	 = 1;
							$bookingInvoice->bkg_wallet_used		 = $_POST['walletUsedAmt'];
						}
						if ($partialPayment == 0 && $additionalObj->wallet > 0)
						{
							$paymentType								 = PaymentType::TYPE_WALLET;
							$amount										 = $additionalObj->wallet;
							$payubolt									 = 0;
							$model->bkgInvoice->bkg_is_wallet_selected	 = 1;
							$model->bkgInvoice->bkg_wallet_used			 = $amount;
							$hash										 = Yii::app()->shortHash->hash($model->bkg_id);
							$date										 = date();
							$model->bkgInvoice->save();
							//							$preAdvance									 = ($model->bkgInvoice->bkg_advance_amount == '') ? 0 : $model->bkgInvoice->bkg_advance_amount;
							$retSet										 = $model->useWalletPayment();
							if ($retSet->getStatus())
							{
								$model->confirm(true, true);
							}
							$url			 = Yii::app()->createUrl('booking/summary/id/' . $model->bkg_id . '/hash/' . $hash);
							$return['url']	 = $url;

							$model->bkgUserInfo->bkg_user_last_updated_on	 = new CDbExpression('NOW()');
							$model->update();
							$bkgUserModel->update();
							$model->bkgInvoice->save();
							DBUtil::commitTransaction($transaction);
							$return['success']								 = true;
							$return['id']									 = $id;
							$return['hash']									 = $hash;
							$return['onlywallet']							 = true;
							//							$this->redirect([$url]);
							echo CJSON::encode($return);

							Yii::app()->end();
						}
						$model->bkgUserInfo->bkg_user_last_updated_on = new CDbExpression('NOW()');
						$model->update();
						$bkgUserModel->update();
						//						if ($allowCreditApply)
						//						{
//
						//							if ($bkgInvModel->bkg_credits_used > 0 && $bookingInvoice->bkg_temp_credits == 0 && Yii::app()->user->getId() != '' && ($model->bkgInvoice->bkg_advance_amount == 0 || $model->bkgInvoice->bkg_advance_amount == ''))
						//							{
						//								$bookingInvoice->bkg_temp_credits = ($bookingInvoice->bkg_credits_used > 0) ? ($bookingInvoice->bkg_credits_used + $bkgInvModel->bkg_credits_used) : $bkgInvModel->bkg_credits_used;
						//							}
						//						}
						$bookingInvoice->save();

						$return['success']					 = true;
						$return['id']						 = $id;
						$return['hash']						 = $hash;
						$model->bkgInvoice->partialPayment	 = $partialPayment;

						if (($paymentType > 0 && $return['success'] && $partialPayment > 0))
						{
							$paymentGateway = PaymentGateway::model()->add($paymentType, $partialPayment, $model->bkg_id, $model->bkg_id, $userInfo);
							if ($paymentGateway)
							{
								$params['blg_ref_id'] = $paymentGateway->apg_id;

								BookingTrail::extendTimeByQuoteExpireTime($model->bkg_id);

								BookingLog::model()->createLog($model->bkg_id, "Online payment initiated ({$paymentGateway->getPaymentType()} - {$paymentGateway->apg_code})", UserInfo::getInstance(), BookingLog::PAYMENT_INITIATED, '', $params);

								if ($model->bkgPref->bkg_is_gozonow > 0)
								{
									BookingVendorRequest::notifyVendorOnPaymentInitiated($model->bkg_bcb_id);
								}

								if (PaymentType::isOnline($paymentType))
								{
									$url = $paymentGateway->paymentUrl;
								}

								if ($payubolt == 1 && Yii::app()->request->isAjaxRequest)
								{
									$apg_id		 = $paymentGateway->apg_id;
									$payRequest	 = PaymentGateway::model()->getPGRequest($apg_id);
									$pgObject	 = Filter::GetPGObject($payRequest->payment_type);

									$return = $pgObject->initiateRequest($payRequest);
									if (!in_array($payRequest->payment_type, [PaymentType::TYPE_RAZORPAY, PaymentType::TYPE_EASEBUZZ]))
									{
										$return['success'] = true;
									}
									echo CJSON::encode($return);
									DBUtil::commitTransaction($transaction);
									Yii::app()->end();
								}

								if ($paymentType == PaymentType::TYPE_INTERNATIONAL_CARD)
								{
									$ccData			 = $_POST['BraintreeCCForm'];
									$btreeResponse	 = $this->btreeResponse($payment, $model, $ccData, $paymentGateway);
									$url			 = $btreeResponse['url'];
									$result			 = $btreeResponse['result'];
									$success		 = $btreeResponse['success'];
									if (!$success)
									{
										$model->addError('bkg_id', 'Error in payment. Please try again.');
										$return['success']		 = false;
										$return['errormessage']	 = $btreeResponse['resArray']['message'];
									}
								}
								if ($platform == 3)
								{
									setcookie('mobplatform', 'mobile', time() + (60 * 30), "/");
//                                    isset($_COOKIE['mobplatform'])
								}
								else
								{
									if (isset($_COOKIE['mobplatform']))
									{
										setcookie('mobplatform', 'mobile', time() - 60, "/");
									}
								}
								$return['url'] = $url;
							}
						}
						$models[]	 = $model;
						$errors		 = [];
						foreach ($payment->getErrors() as $attribute => $error)
						{
							if ($attribute == 'creditCard_number' && sizeof($payment->getErrors()) == 1 && !$error[0])
							{
								$error[0] = $return['errormessage'];
							}
							$errors[CHtml::activeId($payment, $attribute)] = $error;
						}
						if (count($errors) > 0)
						{
							$return['success']	 = false;
							$return['error']	 = $errors;
						}
						DBUtil::commitTransaction($transaction);
					}
					catch (Exception $e)
					{
						$model->addError('bkg_id', $e->getMessage());
						$models[]			 = $model;
						DBUtil::rollbackTransaction($transaction);
						$return['success']	 = false;
						$return['error']	 = CActiveForm::validate($models, null, false);
					}
				}
				else
				{
					$return['success'] = false;
					if ($result != '[]')
					{
						$return['error'] = CJSON::decode($result);
					}
					if ($result1 != '[]')
					{
						$return['error'] = CJSON::decode($result1);
					}
					if ($result2 != '[]')
					{
						$return['error'] = CJSON::decode($result2);
					}
				}
				echo CJSON::encode($return);

				Yii::app()->end();
			}
		}
	}

	public function actionConfirmbooking()
	{
		$request	 = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType());
		$id			 = Yii::app()->request->getParam('id');
		$hash		 = Yii::app()->request->getParam('hash');
		$cash		 = Yii::app()->request->getParam('cash');
		$transaction = null;
		$model		 = Booking::model()->findByPk($id);
		try
		{
			if (!Yii::app()->request->isPostRequest || $id != Yii::app()->shortHash->unhash($hash) || !$model)
			{
				throw new CHttpException(400, "Invalid Request");
			}
			$bkgUserModel			 = BookingUser::model()->find('bui_bkg_id=:bkg_id', ['bkg_id' => $model->bkg_id]);
			$bkgUserModel->scenario	 = 'advance_pay';
			$bkgInvModel			 = BookingInvoice::model()->find('biv_bkg_id=:bkg_id', ['bkg_id' => $model->bkg_id]);
			$bkgInvModel->scenario	 = 'advance_pay';

			if (!$model || !$bkgUserModel || !$bkgInvModel)
			{
				throw new CHttpException(400, 'Invalid data');
			}
			if (!in_array($model->bkg_status, [2, 3, 5]) && $cash == null)
			{
				throw new CHttpException(400, "Payment not allowed for the booking");
			}

			$minDiff = $model->getPaymentExpiryTimeinMinutes();
			if ($minDiff <= 0)
			{
				throw new Exception("Payment time expired", 410);
			}
			if (!isset($_POST['BookingInvoice']))
			{
				throw new CHttpException(400, 'Invalid data');
			}
			$bkgInvModel->attributes = Yii::app()->request->getParam('BookingInvoice');
			$partialPayment			 = $bkgInvModel->partialPayment;

			$bModel = clone $model;
			$bkgInvModel->applyPromoCode($bkgInvModel->bkg_promo1_code);

			$transaction = Filter::beginTransaction();

			$result2 = CActiveForm::validate($bkgInvModel);

			if ($result2 !== '[]')
			{
				throw new Exception(json_encode($bkgInvModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			if (($model->bkgInvoice->bkg_is_wallet_selected == 1 && $model->bkgInvoice->bkg_wallet_used > 0) || $cash == 1)
			{
				if ($cash == 1)
				{
					$model->bkgPref->bkg_is_confirm_cash = 1;
					$model->bkgPref->save();
				}
				else
				{
					$retSet = $model->useWalletPayment();
					if (!$retSet->getStatus())
					{
						throw new Exception(json_encode($retSet->getErrors()), $retSet->getErrorCode());
					}
				}

				$model->confirm(true, true);
				$model->refresh();
				$model->bkgUserInfo->bkg_user_last_updated_on = new CDbExpression('NOW()');
				$model->save();
				$model->bkgInvoice->save();
				Filter::commitTransaction($transaction);

				$url = Yii::app()->createUrl('booking/summary/id/' . $model->bkg_id . '/hash/' . $hash);
				$this->redirect($url);
				Yii::app()->end();
			}
			else
			{
				Logger::error("Booking confirmation failed. Unable to validate payment mode");
				throw new Exception("Payment mode validation failed..", 400);
			}
		}
		catch (Exception $e)
		{
			Filter::rollbackTransaction($transaction);
			$returnset = ReturnSet::setException($e);
		}
		echo CJSON::encode($returnset);
		Yii::app()->end();
	}

	public function actionGozoNowShowVndList()
	{
		$this->enableClarity();
		$formatDateTime = 'Y-m-d H:i:s';
		if (!Config::checkGozoNowEnabled())
		{
			throw new CHttpException(400, 'Cannot proceed');
		}
		$bkgId	 = Yii::app()->request->getParam('id');
		$hashval = Yii::app()->request->getParam('hash');

		$bkgModel = Booking::model()->findByPk($bkgId);
		if (!$bkgModel)
		{
			throw new CHttpException(400, 'This booking does not exist.');
		}

		$hash = Yii::app()->shortHash->hash($bkgId);
		if ($hash != $hashval)
		{
			throw new CHttpException(400, 'Invalid data');
		}
		if ($bkgModel->bkgPref->bkg_is_gozonow != 1)
		{
			throw new CHttpException(400, 'This booking does not comes under Gozo Now scenario.');
		}
		$dataexist = BookingVendorRequest::getPreferredVendorbyBooking($bkgModel->bkg_bcb_id);

		/** Redirect disabled for selected offer ****************************** */
//		if ($dataexist)
//		{
//			$cntRoute = count($bkgModel->bookingRoutes);
//			if ($bkgModel->bkg_drop_address == $bkgModel->bkgToCity->cty_garage_address)
//			{
//				$this->redirect(['booking/address', "bkgid" => $bkgModel->bkg_id, 'hash' => $hash]);
//			}
//			$url = Yii::app()->createUrl('booking/review', ['bkgid' => $bkgId, 'hash' => $hash]);
//			$this->redirect($url);
//			Yii::app()->end();
//		}


		$step1Duration	 = 1;
		$step2Duration	 = 5;
		$step1Date		 = date($formatDateTime, strtotime($bkgModel->bkg_create_date . "+ {$step1Duration} MINUTE"));
		$step2Date		 = date($formatDateTime, strtotime($bkgModel->bkg_create_date . "+ {$step2Duration} MINUTE"));

		$currentDateTime = Filter::getDBDateTime();
		$pickupDiffSecs	 = Filter::getTimeDiffinSeconds($bkgModel->bkg_pickup_date);
		$createDiffSecs	 = Filter::getTimeDiffinSeconds($bkgModel->bkg_create_date);
		$step1DiffSecs	 = Filter::getTimeDiffinSeconds($step1Date);
		$step2DiffSecs	 = Filter::getTimeDiffinSeconds($step2Date);

		$pickupDiffMinutes = Filter::getTimeDiff($bkgModel->bkg_pickup_date);

		$minimumPickupDuration	 = Config::getMinGozoNowPickupDuration($bkgModel->bkg_booking_type, '', $bkgModel->bkgTrail->bkg_create_user_type);
		$timerMaxSeconds		 = 10 * 60;
		$expiryTime				 = $bkgModel->checkGozoNowExpiryTime();
		$expiryDiffSeconds		 = Filter::getTimeDiffinSeconds($expiryTime);
		$timerLeftShow			 = $expiryDiffSeconds % $timerMaxSeconds;
		$timerLog				 = [];
		$timerLogJson			 = BookingTrail::getGnowTimerLog($bkgId);
		if (!$timerLogJson)
		{
			$startTime = Filter::getDBDateTime();

			$timerLog		 = ['count' => 1, 'startTime' => $startTime];
			$timerLogJson	 = json_encode($timerLog);
			BookingTrail::updateGnowTimerLog($bkgId, $timerLogJson);
			if ($timerLog['count'] === 1)
			{
				$userInfo	 = UserInfo::getInstance();
				$desc		 = "Bid timer started";
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, BookingLog::BID_TIMER_START, false);
			}
		}
		$timerLog	 = json_decode($timerLogJson, true);
		$timerCount	 = $timerLog['count'];

		$createDate		 = $timerLog['startTime'];
		$createDiffSecs	 = -1 * Filter::getTimeDiffinSeconds($createDate);

		$durationRemaining	 = $pickupDiffMinutes - $minimumPickupDuration; //;
		$step1DiffSecs		 = (($timerMaxSeconds - $createDiffSecs ) > 0) ? ($timerMaxSeconds - $createDiffSecs) : 0;
		$step1DiffSecs		 = (($timerLeftShow ) > 0) ? ($timerLeftShow) : 0;
		$step1DiffSecs		 = max(min(($durationRemaining * 60), $step1DiffSecs), 0);

		$this->renderAuto('bkGnowBidTimer', [
			'model'				 => $bkgModel,
			'currentDateTime'	 => $currentDateTime,
			'pickupDiffSecs'	 => $pickupDiffSecs,
			'createDiffSecs'	 => $createDiffSecs,
//			'step1Duration'		 => $step1Duration,
//			'step2Duration'		 => $step2Duration,
			'step1DiffSecs'		 => $step1DiffSecs,
//			'step2DiffSecs'		 => $step2DiffSecs,
			'step1Date'			 => $step1Date,
			'step2Date'			 => $step2Date,
			'hash'				 => $hash
				], false, true);
	}

	public function actionGnowDropAddress()
	{
		$request	 = Yii::app()->request;
		$bkgId		 = Yii::app()->request->getParam('bkgid');
		$bookingUser = $request->getParam('BookingUser');
		$hash		 = Yii::app()->request->getParam('hash');
		$booking	 = $request->getParam('Booking', null);
		$routes		 = $request->getParam('BookingRoute', null);

		if (!$booking['bkg_id'])
		{
			$booking['bkg_id'] = $bkgId;
		}
		$bkgModel = Booking::model()->findByPk($booking['bkg_id']);

		if ($bkgModel->bkgPref->bkg_is_gozonow == 1 && $bkgModel->bkg_booking_type != 1)
		{
			$this->redirect(array("booking/review/bkgid/$bkgId/hash/$hash"));
		}

		if (!$request->isPostRequest)
		{
			goto render;
		}
		$returnSet	 = BookingRoute::updateDropAddress($bkgModel, $routes);
		$success	 = $returnSet->getStatus();
		$desc		 = $returnSet->getMessage();

		if (!$success)
		{
			echo json_encode($returnSet);
			Yii::app()->end();
		}
		$url = Yii::app()->createUrl('booking/review', ['bkgid' => $booking['bkg_id'], 'hash' => $hash]);
		echo json_encode(['success' => $success, 'data' => $desc, 'url' => $url]);
		Yii::app()->end();

		render:
		$this->renderAuto("savegnowdropaddress", ['model' => $bkgModel, 'hash' => $hash]);
	}

	public function actionSaveGnowPickAddress()
	{
		$request	 = Yii::app()->request;
		$booking	 = $request->getParam('Booking', null);
		$routes		 = $request->getParam('BookingRoute', null);
		$bookingUser = $request->getParam('BookingUser', null);
		if (!$booking['bkg_id'])
		{
			
		}
		$bkgModel = Booking::model()->findByPk($booking['bkg_id']);
		if (!$request->isPostRequest)
		{
			goto render;
		}
		try
		{
			$bkgUserModel					 = BookingUser::model()->getByBkgId($booking['bkg_id']);
			$bkgUserModel->bkg_user_fname	 = $bookingUser['bkg_user_fname'];
			$bkgUserModel->bkg_user_lname	 = $bookingUser['bkg_user_lname'];
			$bkgUserModel->scenario			 = 'modifybooking';
			$bkgUserModel->save();

			Logger::info("Update Route Address Start");
			$transaction = DBUtil::beginTransaction();
			BookingRoute::updateRouteAddresses($bkgModel, $routes);
			Logger::trace("BookingId: " . $booking['bkg_id'] . " route address updated");
			$bkgModel->refresh();
			$arrResult	 = BookingRoute::updateDistance($bkgModel);
			Logger::trace("BookingId: " . $booking['bkg_id'] . " route rates  updated =>" . json_encode($arrResult));
			$success	 = true;
			if (!empty($arrResult))
			{
				$desc = "Address Updated . Additional Kms:" . $arrResult['additional_km'] . ". Old Base Fare:₹" .
						$arrResult['oldBaseFare'] . ". New Base fare:₹" . $arrResult['fare']['baseFare'];
			}
			else
			{
				$desc = "Address Updated . No Additional Kms";
			}
			BookingLog::model()->createLog($booking['bkg_id'], $desc, UserInfo::getInstance(), BookingLog::BOOKING_MODIFIED, false);
			Logger::trace("BookingId: " . $booking['bkg_id'] . " route address updated Log added");
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$success = false;
			ReturnSet::setException($e);
		}
		echo json_encode(['success' => $success, 'data' => $desc]);
		Yii::app()->end();

		render:
		$this->renderAuto("savegnowpickaddress", ['model' => $bkgModel]);
	}

	public function actionFareBrkup()
	{
		$arrQuoteCat = [];
		$request	 = Yii::app()->request;
		$rid		 = $request->getParam('rid');
		$cabId		 = $request->getParam('cabId');
		$cabData	 = $request->getParam('cabData');
		if ($rid == '')
		{
			throw new Exception('Invalid Request', 400);
		}

		$objPage				 = \Stub\common\PageRequest::getInstance();
		$createRequestBySession	 = $objPage->addQuote($rid);
		foreach ($createRequestBySession->cabRate as $rate)
		{
			if ($rate->cab->id == $cabId)
			{
				$arrQuoteCat = $rate->fare;
			}
		}
		$success = true;
		//echo json_encode(['success' => $success, 'arrQuoteCat' => $arrQuoteCat]);
		//Yii::app()->end();
		$this->renderAuto("fareBrkup", ['arrQuoteCat' => $arrQuoteCat, 'scvId' => $cabId, 'cabData' => $cabData]);
	}

	public function actionResettimer()
	{
		$bkgId	 = Yii::app()->request->getParam('bookingId');
		$hash	 = Yii::app()->request->getParam('hash');
		$hashval = Yii::app()->shortHash->hash($bkgId);

		$model		 = Booking::model()->findByPk($bkgId);
		$dataexist	 = BookingVendorRequest::getPreferredVendorbyBooking($model->bkg_bcb_id);
		try
		{
			if ($hash != $hashval)
			{
				throw new CHttpException(400, 'Invalid data');
			}

			if (!$model)
			{
				throw new CHttpException(400, 'This booking does not exist.');
			}

			if ($model->bkgPref->bkg_is_gozonow != 1)
			{
				throw new CHttpException(400, 'This booking does not comes under Gozo Now scenario.');
			}

			if ($dataexist)
			{
				return false;
			}

			$pickupDiffMinutes		 = Filter::getTimeDiff($model->bkg_pickup_date);
			$minimumPickupDuration	 = Config::getMinGozoNowPickupDuration($model->bkg_booking_type);
			$timerDurations			 = BookingVendorRequest::getBidTimerDurations();

			$step1CSecDuration	 = $timerDurations['1C']['duration'];
			$step1CDuration		 = $step1CSecDuration / 60;
			if ($pickupDiffMinutes - $step1CDuration <= $minimumPickupDuration)
			{
				throw new CHttpException(400, 'The pickup time needs to be at least 60 minutes from now');
			}


			$timerLogJson = BookingTrail::getUpdatedGnowTimerLog($bkgId);

			$res = BookingVendorRequest::getBidTimerStat([], $timerLogJson);

			$returnTimer	 = '';
			$step1DiffSecs	 = $res['durationRemaining'];
			if ($res['timerRunning'] == 'timer1')
			{
				if ($step1DiffSecs > 0)
				{
					$userInfo	 = UserInfo::getInstance();
					$desc		 = "Bid timer restarted";
					BookingLog::model()->createLog($bkgId, $desc, $userInfo, BookingLog::BID_TIMER_RESTART, false);
				}
				$returnTimer = $this->renderPartial('gnowTimerStep1', ['model' => $model, 'step1DiffSecs' => $step1DiffSecs], true);
			}
			if ($res['timerRunning'] == 'timer2')
			{
				if ($step1DiffSecs > 0)
				{
					$userInfo	 = UserInfo::getInstance();
					$desc		 = "Bid timer restarted";
					BookingLog::model()->createLog($bkgId, $desc, $userInfo, BookingLog::BID_TIMER_RESTART, false);
				}
				$returnTimer = $this->renderPartial('gnowTimerStep2', ['model' => $model, 'step2DiffSecs' => $step1DiffSecs], true);
			}

			$timerStat = ['type'				 => 'html',
				'dataHtml'			 => $returnTimer,
				'step1DiffSecs'		 => $step1DiffSecs,
				'durationRemaining'	 => $res['durationRemaining'],
				'timerRunning'		 => $res['timerRunning'],
				'stepValidation'	 => $res['stepValidation']
			];

			echo json_encode(['success' => true, 'cnt' => 0, 'timerStat' => $timerStat]);
		}
		catch (Exception $e)
		{
			echo json_encode(['success' => false, 'cnt' => 0, 'timerStat' => 0, 'message' => $e->getMessage()]);
		}
		Yii::app()->end();
	}

	public function actionExistAddress()
	{
		$bkgId		 = Yii::app()->request->getParam('lead');
		$type		 = Yii::app()->request->getParam('type');
		$objPage	 = $this->getRequestData();
		$objBooking	 = $objPage->booking;
		$model		 = BookingTemp::model()->findByPk($bkgId);
		$brtRoutes	 = $model->bkg_route_data;
		$page		 = ($type == 'pickup') ? ("pickup") : ("dropoff");
		$this->renderAuto($page, ['brtRoutes' => $brtRoutes, 'model' => $model], false, true);
	}

	public function actionAddressForm()
	{
		$map	 = Yii::app()->request->getParam('mapContent');
		$lat	 = Yii::app()->request->getParam('lat');
		$long	 = Yii::app()->request->getParam('long');
		$address = Yii::app()->request->getParam('address');

		$this->renderAuto('addressForm', ['mapContent' => $map, 'lat' => $lat, 'long' => $long, 'address' => $address], false, true);
	}

	public function actionShowDriverDetails()
	{
		$bkg_id	 = Yii::app()->request->getParam('bookingid');
		$success = false;
		$model	 = Booking::model()->findByPk($bkg_id);
		$this->renderPartial("bkDriverCabDetails", ["model" => $model], false);
	}

	public function actionNotifyGnowVendor()
	{
		$bkgId		 = Yii::app()->request->getParam('id');
		$hash		 = Yii::app()->request->getParam('hash');
		$hashval	 = Yii::app()->shortHash->hash($bkgId);
		$returnSet	 = new ReturnSet();
		$model		 = Booking::model()->findByPk($bkgId);

		try
		{
			if ($hash != $hashval)
			{
				throw new CHttpException(400, 'Invalid data');
			}
			if (!$model)
			{
				throw new CHttpException(400, 'This booking does not exist.');
			}

			if ($model->bkgPref->bkg_is_gozonow != 1)
			{
				throw new CHttpException(400, 'This booking does not comes under Gozo Now scenario.');
			}
			$tripId = $model->bkg_bcb_id;

			$timerLogJson	 = BookingTrail::getGnowTimerLog($bkgId);
			$timerLog		 = json_decode($timerLogJson, true);
			$timerCount		 = $timerLog['count'];

			$pickupDiffMinutes		 = Filter::getTimeDiff($model->bkg_pickup_date);
			$minimumPickupDuration	 = Config::getMinGozoNowPickupDuration($model->bkg_booking_type, '', $model->bkgTrail->bkg_create_user_type);
			if ($pickupDiffMinutes >= $minimumPickupDuration && $timerCount === 1)
			{
				$message = BookingCab::gnowNotifyBulk($tripId);
//				$message = BookingCab::gnowNotify($tripId);
			}
			$returnSet->setStatus(true);
			$returnSet->setMessage($message);
		}
		catch (Exception $e)
		{
			$success = false;
			if ($e->getCode() == 500)
			{
				$e = new Exception($e->getMessage(), ReturnSet::ERROR_FAILED);
			}

			$returnSet = ReturnSet::setException($e);
		}
		echo json_encode($returnSet);
		Yii::app()->end();
	}

	public function actionTrack()
	{
		$bookingId			 = trim(Yii::app()->request->getParam('booking_id'));
		$bookingModel		 = Booking::model()->findByPk($bookingId);
		$bkgTrackLogModel	 = BookingTrackLog::model()->getInfoByEvent($bookingId);
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$method				 = "render" . ($outputJs ? "Partial" : "");
		$this->renderPartial('btTrack', array('trackLogmodel'	 => $bkgTrackLogModel,
			'bkgId'			 => $bookingId,
			'bookingModel'	 => $bookingModel), false, $outputJs);
	}

	public function actionEditAddress()
	{


		$id = Yii::app()->request->getParam("bkgid");

		$model = Booking::model()->findByPk($id);
		if (!$id)
		{
			throw new CHttpException(400, 'Invalid booking data');
		}
		foreach ($model->bookingRoutes as $key => $bookingRoute)
		{
			$pickupCity[]	 = $bookingRoute->brt_from_city_id;
			$dropCity[]		 = $bookingRoute->brt_to_city_id;
			$pickup_date[]	 = $bookingRoute->brt_pickup_datetime;
			$temp_last_date	 = strtotime($bookingRoute->brt_pickup_datetime) + $bookingRoute->brt_trip_duration;
			$drop_date_time	 = date('Y-m-d H:i:s', $temp_last_date);
		}
		$pickup_date_time	 = $pickup_date[0];
		$locationArr		 = array_unique(array_merge($pickupCity, $dropCity));

		$this->renderAuto('bkConfirmAddress', array(
			'model' => $model
				)
				, false, $outputJs);
	}

	public function actionGetPromoById()
	{
		$bkgId	 = Yii::app()->request->getParam('id');
		$hash	 = Yii::app()->request->getParam('hash');
		$hashval = Yii::app()->shortHash->hash($bkgId);

		$model = booking::model()->findByPk($bkgId);

		$appliedPrmCode = $model->bkgInvoice->bkg_promo1_code;

		try
		{
			if ($hash != $hashval)
			{
				throw new CHttpException(400, 'Invalid data');
			}

			if (!$model)
			{
				throw new CHttpException(400, 'This booking does not exist.');
			}

			if (!$appliedPrmCode)
			{
				$dataset	 = Promos::allApplicableCodes($model);
				$rowCount	 = $dataset->getRowCount();
				if ($rowCount > 0)
				{
					$promoRow = $dataset->read();
					echo json_encode(['success' => true, 'prmcode' => $promoRow['prm_code']]);
				}
				else
				{
					echo json_encode(['success' => false, 'message' => 'No Promo Found', 'count' => $rowCount]);
				}
			}
			else
			{
				echo json_encode(['success' => true, 'prmcode' => $appliedPrmCode]);
			}
		}
		catch (Exception $ex)
		{

			echo json_encode(['success' => false, 'message' => $ex->getMessage()]);
		}

		Yii::app()->end();
	}

	public function actionCancelgnow()
	{
		$bkgId	 = Yii::app()->request->getParam('bookingId');
		$hash	 = Yii::app()->request->getParam('hash');
		$hashval = Yii::app()->shortHash->hash($bkgId);
		$success = false;
		try
		{
			if ($hash != $hashval)
			{
				throw new CHttpException(400, 'Invalid data');
			}
			$success = Booking::cancelGNow($bkgId);
		}
		catch (Exception $ex)
		{
			ReturnSet::setException($ex);
		}

		$result = ['success' => $success];
		echo json_encode($result);
		Yii::app()->end();
	}

	public function actionGnowaddress()
	{
		$request = Yii::app()->request;

		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_ADDRESS);
		$this->layout = 'column_booking';

		$bkgId	 = Yii::app()->request->getParam('id');
		$hash	 = Yii::app()->request->getParam('hash');
		$hashval = Yii::app()->shortHash->hash($bkgId);

		$bkgModel = Booking::model()->findByPk($bkgId);

		// Steps
		if ($request->isPostRequest && $this->pageRequest->step == 12)
		{
			$transaction = DBUtil::beginTransaction();
			try
			{
				$bookingUser		 = $request->getParam("BookingUser");
				$bookingRoutes		 = $request->getParam("BookingRoute");
				$model->attributes	 = $bookingUser;
				$success			 = $model->save();

				if (!$success)
				{
					throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
				}

				$routes = $model->bookingRoutes;
				foreach ($bookingRoutes as $key => $brtRoute)
				{
					if (isset($brtRoute["from_place"]) && $brtRoute["from_place"] != '')
					{
						$routes[$key]->applyPlace($brtRoute["from_place"], 1);
					}
					if (isset($brtRoute["to_place"]) && $brtRoute["to_place"] != '')
					{
						$routes[$key]->applyPlace($brtRoute["to_place"], 2);
					}
				}

				if (!BookingRoute::validateAddress($model))
				{
					throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
				}

				$model->setRoutes($routes);
				$success = $model->save();
				if (!$success)
				{
					throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
				}

				$bkgId		 = $bkgModel->saveBooking($model->bkg_id);
				$bkgModel	 = Booking::model()->findByPk($bkgId);

				$userModel = Users::model()->findByPk($bkgModel->bkgUserInfo->bkg_user_id);
				if ($userModel && $request->getParam("travelling") == 1)
				{
					$userModel->usr_name	 = $bookingUser['bkg_user_fname'];
					$userModel->usr_lname	 = $bookingUser['bkg_user_lname'];
					$userModel->usr_mobile	 = $bookingUser['bkg_contact_no'];
					$userModel->save();
				}
				$bkgModel->bkgUserInfo->bkg_user_fname	 = $bookingUser['bkg_user_fname'];
				$bkgModel->bkgUserInfo->bkg_user_lname	 = $bookingUser['bkg_user_lname'];
				$bkgModel->bkgUserInfo->bkg_country_code = ($bkgModel->bkgUserInfo->bkg_country_code == 0) ? $userModel->usr_country_code : $bkgModel->bkgUserInfo->bkg_country_code;
				$bkgModel->bkgUserInfo->bkg_contact_no	 = $bookingUser['bkg_contact_no'];

				$result = BookingUser::model()->updateData($bkgModel->bkgUserInfo, $bkgId);
				if ($result)
				{

					$arrResult = BookingRoute::updateDistance($bkgModel, $bkgId);
				}
				DBUtil::commitTransaction($transaction);
				$success = true;
				$hash	 = Yii::app()->shortHash->hash($bkgId);

				$url = Yii::app()->createUrl('booking/review', ['bkgid' => $bkgId, 'hash' => $hash]);

//				if ($bkgModel->bkgPref->bkg_is_gozonow == 1 && ($bkgModel->bkg_drop_address == $bkgModel->bkgToCity->cty_garage_address))
				if ($bkgModel->bkgPref->bkg_is_gozonow == 1)
				{
					$tripId		 = $bkgModel->bkg_bcb_id;
					$dataexist	 = BookingVendorRequest::getPreferredVendorbyBooking($tripId);

					if (!$dataexist)
					{
						//actionNotifyVendor
					}

					$url = Yii::app()->createAbsoluteUrl('gznow/' . $bkgId . '/' . $hash);
				}
				echo json_encode(['success' => $success, 'url' => $url, 'data' => $arrResult]);
				Yii::app()->end();
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				$returnSet = ReturnSet::setException($ex);
				echo json_encode($returnSet);
				Yii::app()->end();
			}
		}
		$tripId = $bkgModel->bkg_bcb_id;

		renderView:
		$prevstep				 = $this->pageRequest->step;
		$this->pageRequest->step = $step;
		$this->renderAuto("bkAddress", ['step'			 => $step, 'prevstep'		 => $prevstep,
			'pageid'		 => $step, 'model'			 => $model, 'bookingTemp'	 => $model,
			'usermodel'		 => $bkgModel->bkgUserInfo, 'hash'			 => $hash,
			'pageID'		 => $step, 'gnowPickupHide' => $gnowPickupHide, 'gnowDropHide'	 => $gnowDropHide]);
	}

	public function actionCheckAddress()
	{
		$returnSet	 = new ReturnSet();
		$request	 = Yii::app()->request;
		$id			 = $request->getParam('id');
		$model		 = Booking::model()->findByPk($id);
		$bkgId		 = $model->bkg_id;
		$isBlocked	 = 'unBlocked';
		try
		{
			$data		 = $this->compareCharges($model, $_POST, true);
			$routeData	 = $this->getRouteData($model, $_POST, true);

			if ($routeData['fromLatLong'])
			{
				$placeObj			 = Stub\common\Place::init($routeData['fromLatLong']['fromLat'], $routeData['fromLatLong']['fromLong']);
				$isBlockedLocation	 = BlockedLocations::getBlockedLocation($placeObj);
			}

			if ($routeData['toLatLong'])
			{
				$placeObj			 = Stub\common\Place::init($routeData['toLatLong']['toLat'], $routeData['toLatLong']['toLong']);
				$isBlockedLocation	 = BlockedLocations::getBlockedLocation($placeObj);
			}

			if ($isBlockedLocation)
			{
				$isBlocked = 'blocked';
			}

			//$outputJs	 = Yii::app()->request->isAjaxRequest;
			$html = $this->renderPartial('fare', array('model' => $data['rateChart']['model'], 'bkgid' => $id), true);
			$returnSet->setStatus(true);
			$returnSet->setData(["fare" => $html, "extraCharge" => $data['charge'], "extrakm" => $data['km'], "isBlocked" => $isBlocked]);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::renderJSONException($e);
		}


		echo json_encode($returnSet);
		Yii::app()->end();
	}

	/** @param Booking $model */
	public function compareCharges($model, $data, $save = false)
	{
		$result		 = [];
		$confAddress = $data['addData'];
		$arr		 = [];
		foreach ($confAddress as $key => $val)
		{
			$arr[$val["name"]] = $val["value"];
		}

		$str	 = http_build_query($arr);
		parse_str($str, $result2);
		$result	 = array_replace_recursive($result, $result1);

		$bookingRoutes = $result2["BookingRoute"];
		BookingRoute::completeUpdateAddresses($bookingRoutes, $model, false);

		$distanceArr = BookingRoute::updateDistance($model, [], false);
		if (!$distanceArr)
		{
			$arrResult	 = ['model' => $model];
			$distanceArr = ['success' => true, 'charge' => 0, 'km' => 0, 'msg' => 'No changes', 'rateChart' => $arrResult];
		}
		return $distanceArr;
	}

	public function actionAddons()
	{
		$request = Yii::app()->request;

		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_ADDONS);
		$this->layout = 'column_booking';

		$step	 = 12;
		$pageID	 = $request->getParam('step');

		$objPage	 = $this->getRequestData();
		$objBooking	 = $objPage->booking;

		$cabType = $objBooking->cab->cabCategory->id;
		if (!$request->isPostRequest && $cabType != '')
		{
			if ($objBooking->cab == null)
			{
				$objBooking->cab = new Stub\common\Cab();
			}
			$this->pageRequest->populateQuote();
		}

		$objPage->updatePostData();
		if ($request->isPostRequest && $pageID == 12)
		{
			$addonparams				 = $request->getParam('addonparams');
			$addonidcabmodel			 = $request->getParam('addonidcabmodel');
			$objBooking->addonDetails	 = $addonparams;

			$cabType						 = ($addonidcabmodel) ? AddonCabModels::model()->findByPk($addonidcabmodel)->acm_svc_id_to : $cabType;
			$objBooking->cabServiceClass	 = $objBooking->cab->cabCategory->scvVehicleServiceClass;
			$objBooking->bkg_vehicle_type_id = $cabType;
			$objBooking->cabType			 = $cabType;
			$objBooking->cab->cabCategory->setData($cabType);
			$objBooking->cabModel			 = $objBooking->cab->cabCategory->scvVehicleModel;
			foreach ($objPage->quote->cabRate as $key => $rate)
			{
				if ($rate->cab->id == $cabType)
				{
					$objBooking->fare->advanceReceived	 = $rate->fare->advanceReceived;
					$objBooking->fare->totalAmount		 = $rate->fare->totalAmount;
				}
			}
			$objPage->updatePostData();
			$this->forward('booking/address');
		}
		$this->pageRequest->step = $step;
		$this->renderAuto("bkAddons", ['baseAmount' => $baseAmount, 'addonsObj' => $addonsObj, 'addonsCmObj' => $addonsCmObj, 'step' => $step, 'pageid' => $step], false, true);
	}

	public function actionRefreshQuote()
	{
		$bkgId	 = Yii::app()->request->getParam('bid');
		$objPage = $this->getRequestData();
		if ($objPage->booking == null)
		{
			$model = new BookingTemp();
			$model->loadDefaults();
			$this->parseFriendlyUrl($model);
			$objPage->setBookingModel($model);
		}
		else
		{
			$objBooking	 = $objPage->booking;
			$model		 = $objBooking->getLeadModel();
		}

		if ($model->bkg_contact_no == '' && UserInfo::isLoggedIn())
		{
			$objPhone = Users::getPrimaryPhone(UserInfo::getUserId(), true);
			if ($objPhone)
			{
				$model->setContactNumber($objPhone->getCountryCode() . $objPhone->getNationalNumber());
			}
		}

		if ($request->isAjaxRequest && $model->bkg_contact_no == '')
		{
			throw new CHttpException(403, "Phone number required.", ReturnSet::ERROR_UNAUTHORISED);
		}

		$objPage->populateQuote($model);

		$existModel			 = Booking::model()->findByPk($bkgId);
		$bookingUser		 = $existModel->bkgUserInfo;
		$model->attributes	 = $bookingUser;
		$success			 = $model->save();

		if (!$success)
		{
			throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		$routes = $model->bookingRoutes;
		foreach ($bookingRoutes as $key => $brtRoute)
		{
			if (isset($brtRoute["from_place"]) && $brtRoute["from_place"] != '')
			{
				$routes[$key]->applyPlace($brtRoute["from_place"], 1);
			}
			if (isset($brtRoute["to_place"]) && $brtRoute["to_place"] != '')
			{
				$routes[$key]->applyPlace($brtRoute["to_place"], 2);
			}
		}

		if (!BookingRoute::validateAddress($model))
		{
			throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
		}

		$model->setRoutes($routes);
		$success = $model->save();
		if (!$success)
		{
			throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
		}
		$bkgModel	 = Booking::getNewInstance();
		$bkgId		 = $bkgModel->saveBooking($model->bkg_id);
		$bkgModel	 = Booking::model()->findByPk($bkgId);
	}

	public function actionEditPickupTime()
	{
		$this->checkV3Theme();
		$bkgid		 = Yii::app()->request->getParam('bkg_id');
		$model		 = Booking::model()->findByPk($bkgid);
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('editpickuptime', array('model' => $model), false, $outputJs);
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
			$getBookingLogInfo	 = BookingLog::model()->getRescheduleTimeLog($bkgId);
			if ($getBookingLogInfo != '')
			{
				$model->addError("bkg_id", "Can't change reschedule pickup time more than once.");
				$return['error'] = $model->getErrors();
				$success		 = false;
				goto skip;
			}
			$success = $model->saveRescheduleTime($bkgId, $isPrePostTime, $timeSchedule);
			if ($success == true)
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					$data = ['success' => $success, 'message' => 'Pickup Time modified successfully. Booking ID : ' . $model->bkg_booking_id];

					echo json_encode($data);
					Yii::app()->end();
				}
			}
			else
			{
				skip:
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

	public function actionTravellerInfo()
	{
		$this->enableClarity();

		$request	 = Yii::app()->request;
		$objPage	 = $this->getRequestData();
		$objBooking	 = $objPage->booking;
		$model		 = $objBooking->getLeadModel();
		if ($model && $model->bkg_user_id != '' && $model->bkg_agent_id == null)
		{
			$GLOBALS["GA4_USER_ID"] = $model->bkg_user_id;
		}

		$isBkpn	 = $request->getParam('isbkpn');
		$bkgId	 = Yii::app()->request->getParam('bkg_id');

		if ($isBkpn == 1)
		{
			$model = Booking::model()->findByPk($bkgId);
		}

		if (Yii::app()->user->isGuest && $isBkpn == 1)
		{
			throw new CHttpException(403, "Login required.", ReturnSet::ERROR_UNAUTHORISED);
		}

		$bkgUserAttr = $request->getParam('BookingUser');
		if (!$bkgUserAttr && $isBkpn == 1)
		{
			goto view;
		}

		if ($isBkpn == 1 && $bkgUserAttr && $request->isPostRequest)
		{
			BookingUser::chageTravellerInfo($model, $bkgUserAttr, $bkgId);
		}
		if (!$request->isPostRequest)
		{
			goto view;
		}
		$modelAttr = $request->getParam('BookingTemp');
		if (!$modelAttr)
		{
			goto view;
		}
		$model->setAttributes($modelAttr);

		$userId			 = $model->bkg_user_id;
		$contactId		 = ContactProfile::getByEntityId($userId);
		$contactModel	 = Contact::model()->findByPk($contactId);

		$userModel = Users::model()->findByPk($userId);
		if ($model->bkg_traveller_type == "2")
		{
			goto skipDefaultUser;
		}
		if ($modelAttr['bkg_user_email'])
		{
			ContactEmail::model()->add($modelAttr['bkg_user_email'], $contactId, 0);
			$userModel->usr_email = $modelAttr['bkg_user_email'];
		}
		if ($modelAttr['bkg_contact_no'])
		{
			$phoneno				 = '+' . $modelAttr['bkg_country_code'] . $modelAttr['bkg_contact_no'];
			Filter::parsePhoneNumber($phoneno, $code, $number);
			ContactPhone::model()->add($contactId, $number, 0, $code, null, 0, 0, 0);
			$userModel->usr_mobile	 = $code . $number;
		}
		if ($userModel->usr_name == '' || $userModel->usr_lname == '' || $contactModel->ctt_first_name == '' || $contactModel->ctt_last_name == '')
		{
			$contactModel->ctt_first_name	 = $modelAttr['bkg_user_name'];
			$contactModel->ctt_last_name	 = $modelAttr['bkg_user_lname'];

			$userModel->usr_name	 = $modelAttr['bkg_user_name'];
			$userModel->usr_lname	 = $modelAttr['bkg_user_lname'];
		}
		$contactModel->save();
		$userModel->save();
		$model->bkg_user_name;
		$model->validate();

		skipDefaultUser:
		if ($userModel->usr_email == "" || $userModel->usr_mobile = "" || $userModel->usr_name == '' || $userModel->usr_lname == '')
		{
			Logger::error("Phone number/email/name can not be blank while create a booking.");
		}
		if (!$model->save())
		{
			$data = ['success' => false, 'rdata' => $this->pageRequest->getEncrptedData(), 'errors' => $model->errors];
		}
		else
		{
			$objPage->setBookingModel($model);
			$objPage->updatePostData();
			if ($model->bkg_traveller_type == "2")
			{
				$objPerson			 = $objPage->booking->profile;
				$secondaryTraveller	 = Filter::encrypt(json_encode($objPerson));
			}

			$usr_name	 = ($model->bkg_user_name != '') ? $model->bkg_user_name : $userModel->usr_name;
			$usr_lname	 = ($model->bkg_user_lname != '') ? $model->bkg_user_lname : $userModel->usr_lname;

			$data = ['success' => true, 'username' => trim($usr_name . ' ' . $usr_lname), 'rdata' => $this->pageRequest->getEncrptedData(), 'secondaryTraveller' => $secondaryTraveller];
		}
		echo json_encode($data);
		Yii::app()->end();

		view:
		$this->renderAuto("bkTravellInfo", ['model' => $model, 'isbkpn' => $isBkpn, 'bkgId' => $bkgId], false, true);
	}

	public function actionRefreshTravellerInfo()
	{
		$this->renderPartial('bkUserTravellInfo');
	}

	/**
	 * 
	 * @param type $model
	 * @param type $data
	 * @param type $save
	 * @return array
	 */
	public function getRouteData($model, $data, $save = false)
	{
		$result		 = [];
		$confAddress = $data['addData'];
		$arr		 = [];
		foreach ($confAddress as $key => $val)
		{
			$arr[$val["name"]] = $val["value"];
		}

		$str	 = http_build_query($arr);
		parse_str($str, $result2);
		$result	 = array_replace_recursive($result, $result1);

		$bookingRoutes = $result2["BookingRoute"];
		foreach ($bookingRoutes as $key => $brtRoute)
		{
			$fromPlace	 = $brtRoute["from_place"];
			$toPlace	 = $brtRoute["to_place"];
		}

		$fromLatLong = [];
		$toLatLong	 = [];
		if ($fromPlace)
		{
			$fromPlaceObj	 = json_decode($fromPlace);
			$fromLat		 = $fromPlaceObj->coordinates->latitude;
			$fromLong		 = $fromPlaceObj->coordinates->longitude;
			$fromLatLong	 = ['fromLat' => $fromLat, 'fromLong' => $fromLong];
		}

		if ($toPlace)
		{
			$toPlaceObj	 = json_decode($toPlace);
			$toLat		 = $toPlaceObj->coordinates->latitude;
			$toLong		 = $toPlaceObj->coordinates->longitude;
			$toLatLong	 = ['toLat' => $toLat, 'toLong' => $toLong];
		}

		return ['fromLatLong' => $fromLatLong, 'toLatLong' => $toLatLong];
	}

	public function actionCab()
	{
		$this->enableClarity();

		$request = Yii::app()->request;

		$visitorCookie = Yii::app()->request->cookies['gvid'];
		if (!$visitorCookie)
		{
			Filter::setVisitorCookie();
		}
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], BookFormRequest::URL_REDIRECTED_BOOKING);

		$model	 = Kayak::initiate($request);
		$objPage = $this->getRequestData();
		$objPage->setBookingModel($model);

		$objCookie1										 = new CHttpCookie('gozo_agent_id', $model->bkg_agent_id);
		$objCookie1->expire								 = time() + 21600;
		Yii::app()->request->cookies['gozo_agent_id']	 = $objCookie1;

		$objCookies											 = new CHttpCookie('gozo_partner_ref_id', $model->bkg_partner_ref_id);
		$objCookies->expire									 = time() + 21600;
		Yii::app()->request->cookies['gozo_partner_ref_id']	 = $objCookies;

		$model->scenario = 'redirectedBooking';
		$errors			 = CActiveForm::validate($model);
		$errors1		 = BookingRoute::validateRoutes($model->bookingRoutes, $model->bkg_booking_type, null, $model->bkg_agent_id);

		if ($errors !== '[]' || !empty($errors1))
		{
			if ($model->bkg_booking_type == null)
			{
				$this->forward('booking/bkgtype');
			}
			$this->layout	 = 'column_booking';
			$objPage->step	 = 5;
			$step			 = 5;
			$this->renderAuto('bkItinerary', array('step' => $step, 'pageRequest' => $objPage, 'pageid' => $step, 'model' => $model), false, true);
			Yii::app()->end();
		}
		$objPage->saveLead(false);

		$leadModel = BookingTemp::model()->findByPk($objPage->booking->id);
		if ($leadModel != '')
		{
			$leadModel->getRoutes();
			if (in_array($leadModel->bkg_booking_type, [1, 10, 11]))
			{
				unset($leadModel->bookingRoutes[1]);
			}
			$quotData	 = Quote::populateFromModel($leadModel, $leadModel->bkg_vehicle_type_id);
			$response	 = new \Stub\booking\QuoteResponse();
			$response->setData($quotData);
			//$quoteArr[$leadModel->bkg_booking_type] = $response;
			$cabQuote	 = $response;
			//print'<pre>';print_r($cabQuote);exit;
		}

		$this->layout					 = 'column_booking';
		$objPage->step					 = 7;
		$step							 = 7;
		$objPage->isRedirectedBooking	 = 1;
		$model							 = new BookingUser();
		$objPage->booking->defLeadId	 = $objPage->booking->id;
		$this->renderAuto('bkTravellerConatctInfo', array('step' => $step, 'pageRequest' => $objPage, 'pageid' => $step, 'model' => $model, 'cabQuote' => $cabQuote), false, true);
		Yii::app()->end();
	}

	public function actionTravellercontact()
	{
		$this->enableClarity();

		$this->layout	 = 'column_booking';
		$bkgUserModel	 = new BookingUser();
		$step			 = 7;
		$request		 = Yii::app()->request;
		$objPage		 = $this->getRequestData();
		$objPage->step	 = 7;
		$model			 = $objPage->booking->getLeadModel();
		$modelAttr		 = $request->getParam('BookingUser');
		if ($request->isPostRequest && $modelAttr != '')
		{
			$transaction = DBUtil::beginTransaction();
			try
			{


				$bkgUserModel->attributes	 = $modelAttr;
				$model->bkg_user_name		 = $modelAttr['bkg_user_fname'];
				$model->attributes			 = $modelAttr;
				if (!$model->validatePhone('bkg_contact_no', $model->attributes))
				{
					echo json_encode(['success' => false, 'errMessage' => 'Please enter valid phone number.']);
					Yii::app()->end();
				}
				if (!$model->validateEmail('bkg_user_email', $model->attributes))
				{
					echo json_encode(['success' => false, 'errMessage' => 'Please enter valid email address.']);
					Yii::app()->end();
				}
				$model->save();

				$bkgId		 = Booking::model()->saveBooking($model->bkg_id);
				$bkgModel	 = Booking::model()->findByPk($bkgId);

				if ($bkgModel->bkg_agent_id == Config::get('Kayak.partner.id'))
				{
					$bkgModel->bkg_partner_ref_id = $model->bkg_partner_ref_id;
					$bkgModel->save();
				}

				DBUtil::commitTransaction($transaction);
				$success = true;
				$hash	 = Yii::app()->shortHash->hash($bkgId);

				$url = Yii::app()->createUrl('booking/review', ['bkgid' => $bkgId, 'hash' => $hash]);

				if ($bkgModel->bkgPref->bkg_is_gozonow == 1)
				{
					$tripId		 = $bkgModel->bkg_bcb_id;
					$dataexist	 = BookingVendorRequest::getPreferredVendorbyBooking($tripId);

					if (!$dataexist)
					{
						//actionNotifyVendor
					}

					$url = Yii::app()->createAbsoluteUrl('gznow/' . $bkgId . '/' . $hash);
				}
				echo json_encode(['success' => true, 'url' => $url]);
				Yii::app()->end();
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				echo json_encode(['success' => false, 'errMessage' => 'Unable to process your request at this moment.']);
				Yii::app()->end();
			}
		}
		else
		{
			$model->getRoutes();
			if (in_array($model->bkg_booking_type, [1, 10, 11]))
			{
				unset($model->bookingRoutes[1]);
			}
			$quotData	 = Quote::populateFromModel($model, $model->bkg_vehicle_type_id);
			$response	 = new \Stub\booking\QuoteResponse();
			$response->setData($quotData);
			$cabQuote	 = $response;
		}
		if ($_REQUEST['dlid'] != '')
		{
			$objPage->booking->defLeadId = $_REQUEST['dlid'];
		}
		$this->renderAuto('bkTravellerConatctInfo', array('step' => $step, 'pageRequest' => $objPage, 'pageid' => $step, 'model' => $bkgUserModel, 'cabQuote' => $cabQuote), false, true);
		Yii::app()->end();
	}

	public function actionAirport()
	{

		$http_origin = $_SERVER['HTTP_ORIGIN'];
		if ($http_origin == "https://ixigo.bookairportcab.com" || $http_origin == "https://bookairportcab.com" || $http_origin == "https://www.bookairportcab.com")
		{
			header("Access-Control-Allow-Origin: $http_origin");
			header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
		}
		$request = Yii::app()->request;

		$visitorCookie = Yii::app()->request->cookies['gvid'];
		if (!$visitorCookie)
		{
			Filter::setVisitorCookie();
		}
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], BookFormRequest::URL_PARTNER_BOOKING);
		$model	 = Mobisign::initiate($request);
		$objPage = $this->getRequestData();
		$objPage->setBookingModel($model);

		$objCookie1										 = new CHttpCookie('gozo_agent_id', $model->bkg_agent_id);
		$objCookie1->expire								 = time() + 21600;
		Yii::app()->request->cookies['gozo_agent_id']	 = $objCookie1;

		if (in_array($model->bkg_booking_type, [9, 10, 11]))
		{
			goto skipquotepage;
		}

		$errors = CActiveForm::validate($model);
		if ($errors == [] || $errors == "[]")
		{
			$objPage->saveLead(false);
			$url = $this->getURL($objPage->getQuoteURL());
			$this->redirect($url);
		}
		skipquotepage:
		$model->scenario = "partnerRedirected";
		$errors			 = CActiveForm::validate($model);

		if ($model->bkg_booking_type == null)
		{
			$this->forward('booking/bkgtype');
		}
		$this->layout	 = 'column_booking';
		$objPage->step	 = 5;
		$step			 = 5;
		$isAgent		 = false;
		if ($model->bkg_from_city_id == '' || $model->bkg_to_city_id == '')
		{
			$isAgent = true;
		}
		$this->renderAuto('bkItinerary', array('step' => $step, 'pageRequest' => $objPage, 'pageid' => $step, 'model' => $model, 'isAgent' => $isAgent), false, true);
		Yii::app()->end();
	}

	public function actionAutoFURCustomer()
	{
		$bookingId	 = Yii::app()->request->getParam('booking_id');
		$message	 = Yii::app()->request->getParam('message');
		$returnSet	 = ServiceCallQueue::autoFURCustomerBookingCancellation($bookingId, $message);
		echo json_encode($returnSet);
		Yii::app()->end();
	}

	public function actionTraveller()
	{
		$this->enableClarity();

		$success			 = true;
		$bookingId			 = Yii::app()->request->getParam('booking_id');
		$model				 = BookingTemp::model()->findByPk($bookingId);
		$loggedinUserName	 = Yii::app()->user->loadUser()->usr_name;
		$loggedinUserLName	 = Yii::app()->user->loadUser()->usr_lname;
		$username			 = $loggedinUserName . ' ' . $loggedinUserLName;
		if ($model)
		{
			$usr_name	 = ($model->bkg_user_name != '') ? $model->bkg_user_name : $loggedinUserName;
			$usr_lname	 = ($model->bkg_user_lname != '') ? $model->bkg_user_lname : $loggedinUserLName;
			$username	 = $usr_name . ' ' . $usr_lname;
		}
		$data = ['success' => $success, 'username' => $username];
		echo json_encode($data);
		Yii::app()->end();
	}

//	public function actionSecondaryTravellerInfo()
//	{
//		$success				 = true;
//		$secondaryTravellerInfo	 = Yii::app()->request->getParam('secondaryTravellerInfo');
//		if (!$secondaryTravellerInfo)
//		{
//			//throw new Exception('Data not active', 401);
//			$usrData = ['fname' => Yii::app()->user->loadUser()->usr_name, 'lname' => Yii::app()->user->loadUser()->usr_lname];
//			goto endline;
//		}
//		$jsonData	 = Filter::decrypt($secondaryTravellerInfo);
//		$usrData	 = json_decode($jsonData);
//		endline:
//		$data		 = ['success' => $success, 'usrData' => $usrData];
//		echo json_encode($data);
//		Yii::app()->end();
//	}


	public function actionCheckPayAmmount()
	{
		$request	 = Yii::app()->request;
		$id			 = Yii::app()->request->getParam('id');
		$hash		 = Yii::app()->request->getParam('hash');
		$transaction = null;
		$model		 = Booking::model()->findByPk($id);

		if ($id != Yii::app()->shortHash->unhash($hash) || !$model)
		{
			throw new CHttpException(400, "Invalid Request");
		}
		if ($model->bkgInvoice->bkg_is_wallet_selected == 1 && $model->bkgInvoice->bkg_wallet_used > 0)
		{

			$model->bkgInvoice->bkg_is_wallet_selected;
			$model->bkgInvoice->bkg_wallet_used;
			$minamount				 = $model->bkgInvoice->calculateMinPayment();
			$bkgDueAmount			 = $model->bkgInvoice->bkg_due_amount;
			$advance				 = $model->bkgInvoice->getAdvanceReceived();
			$maxPaymentWithDiscount	 = round($model->bkgInvoice->bkg_total_amount) - $advance;
			$maxPaymentWithDiscount	 = ($bkgDueAmount == '') ? $maxPaymentWithDiscount : $bkgDueAmount;
			$defaultAmount			 = ($model->bkgInvoice->bkg_advance_amount > 0) ? $maxPaymentWithDiscount : $minamount;
		}
	}

	public function actionSendOTPPartnerCancel()
	{
		$request = Yii::app()->request;

		$bkid		 = Yii::app()->request->getParam('bk_id');
		$bkreasonid	 = Yii::app()->request->getParam('bkreason');
		$reasonText	 = Yii::app()->request->getParam('bkreasontext');
		$isBkpn		 = Yii::app()->request->getParam('bkpnlogin');

		$model	 = Booking::model()->findByPk($bkid);
		$phoneNo = $model->bkgUserInfo->bkg_contact_no;
		$pcode	 = $model->bkgUserInfo->bkg_country_code;

		$objPage		 = BookFormRequest::createInstance();
		$phoneNo		 = '+' . $pcode . $phoneNo;
		$objCttVerify	 = $objPage->getContact(2, $phoneNo);

		if ($objCttVerify->type == 2)
		{
			Filter::parsePhoneNumber($phoneNo, $code, $number);
			$canSendSMS = ContactPhone::checkTosendSMS($code, $number, SmsLog::SMS_LOGIN_REGISTER);
			if (!$canSendSMS)
			{
				throw new Exception(json_encode(['error' => "Problem while send otp. Please contact support."]), ReturnSet::ERROR_VALIDATION);
			}
		}

		Contact::verifyOTP($objCttVerify, false);

		$isSend = smsWrapper::sendCancelOTP($code, $number, $objCttVerify->otp, $bkid, SmsLog::Consumers);
		if ($isSend)
		{
			$objCttVerify->isSendSMS = 1;
		}
		$contactDetails = Yii::app()->JWT->encode([["type" => $objCttVerify->type, "value" => $objCttVerify->value, "isSendSMS" => $objCttVerify->isSendSMS]]);

		$params	 = [
			'verifyData'	 => $contactDetails,
			'verifyotp'		 => $objCttVerify->otp,
			'phoneno'		 => $phoneNo,
			'verifyURL'		 => $this->getURL("booking/verifyOTP"),
			'pageRequest'	 => $objPage,
			'bkgId'			 => $bkid,
			'bkreasonId'	 => $bkreasonid,
			'reasonText'	 => $reasonText,
			'isBkpn'		 => $isBkpn,
		];
		$view	 = "otpVerifyPartner";
		$this->renderAuto($view, $params, false, true);
	}

	public function actionReSendOTPPartnerCancel()
	{
		$this->pageTitle = "Resend OTP";
		$returnset		 = new ReturnSet();
		$request		 = Yii::app()->request;

		$bkgid			 = Yii::app()->request->getParam('bkgid');
		$verifyData		 = $request->getParam('verifyData');
		$arrVerifyData	 = Yii::app()->JWT->decode($verifyData);
		try
		{
			if ($this->pageRequest == null)
			{
				$rData				 = Yii::app()->request->getParam("rdata");
				$this->pageRequest	 = BookFormRequest::createInstance($rData);
			}
			$objPage = $this->pageRequest;
			foreach ($arrVerifyData as $data)
			{
				$objCttVerify = $objPage->getContact($data->type, $data->value);

				if ($objCttVerify->otpRetry >= 3)
				{
					throw new Exception("Time exceed you can send it later", ReturnSet::ERROR_FAILED);
				}
				if ($objCttVerify->otpValidTill > time())
				{
					throw new Exception("OTP not send", ReturnSet::ERROR_FAILED);
				}

				Contact::verifyOTP($objCttVerify, false);
				Filter::parsePhoneNumber($objCttVerify->value, $code, $number);
				$isSend = smsWrapper::sendCancelOTP($code, $number, $objCttVerify->otp, $bkgid, SmsLog::Consumers);
				if ($isSend)
				{
					$objCttVerify->isSendSMS = 1;
				}
			}


			Filter::removeNull($objPage);
			if ($objPage)
			{
				$returnset->setStatus(true);
				$returnset->setData(['rdata' => $this->pageRequest->getEncrptedData()]);
			}
		}
		catch (Exception $e)
		{
			$returnset = ReturnSet::setException($e);
		}
		echo json_encode($returnset);
		Yii::app()->end();
	}

	public function actionVerifyOTPPartnerCancel()
	{
		/** @var HttpRequest $request */
		$this->pageTitle = "OTP verify";
		$request		 = Yii::app()->request;
		$returnset		 = new ReturnSet();

		if ($this->pageRequest == null)
		{
			$rData				 = Yii::app()->request->getParam("rdata");
			$this->pageRequest	 = BookFormRequest::createInstance($rData);
		}
		$objPage = $this->pageRequest;

		$params = [];
		try
		{
			$curOtp			 = $request->getParam('otp');
			$verifyData		 = $request->getParam('verifyData');
			$arrVerifyData	 = Yii::app()->JWT->decode($verifyData);
			$data			 = $arrVerifyData[0];
			if (!$data)
			{
				throw new Exception("Invalid Request", ReturnSet::ERROR_INVALID_DATA);
			}

			$objCttVerify = $objPage->getContact($data->type, $data->value);

			if (!$objCttVerify->isOTPActive())
			{
				throw new Exception("OTP Expired. Please enter new OTP.", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$success = $objCttVerify->verifyOTP($curOtp);
			if (!$success)
			{
				throw new Exception("OTP Mistmached. Please try again", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			if ($success)
			{
				$bkid		 = Yii::app()->request->getParam('bk_id');
				$bkreasonid	 = Yii::app()->request->getParam('bkreason');
				$reasonText	 = Yii::app()->request->getParam('bkreasontext');
				$isBkpn		 = Yii::app()->request->getParam('bkpnlogin');
				$params		 = [
					'bkgId'		 => $bkid,
					'bkreasonId' => $bkreasonid,
					'reasonText' => $reasonText,
					'isBkpn'	 => $isBkpn,
					'success'	 => $success,
				];
			}

			$this->renderAuto("otpVerifiedPartner", $params, false, true);
			Yii::app()->end();
		}
		catch (Exception $e)
		{
			$returnset = ReturnSet::setException($e);
		}
		skipAll:
		echo json_encode($returnset);
		Yii::app()->end();
	}

	/**
	 * This action is used only for kayak partner redirected booking
	 */
	public function actionTripsuggestions()
	{
		$leadId		 = Yii::app()->request->getParam('bkg_id');
		$defleadId	 = Yii::app()->request->getParam('dlid') | 0;
		$model		 = BookingTemp::model()->findByPk($leadId);
		$cabQuote	 = Kayak::getSuggestedTrips($leadId, $defleadId);
		$view		 = "bkTripQuoteSuggestion";
		$this->renderAuto($view, array('cabQuote' => $cabQuote, 'model' => $model, 'deflid' => $defleadId), false, true);
	}

	/**
	 * This action is used only for kayak partner redirected booking
	 */
	public function actionSuggestedtripselect()
	{

		$triptype	 = Yii::app()->request->getParam('tripType');
		$leadid		 = Yii::app()->request->getParam('leadid');
		$objPage	 = $this->getRequestData();

		if ($leadid > 0)
		{
			$defModel = BookingTemp::model()->findByPk($leadid);
			if ($defModel->bkg_booking_type == $triptype)
			{
				$objPage->booking->id		 = $leadid;
				$routeDataArr				 = $defModel->getRoutes();
				$objPage->booking->routes	 = \Stub\common\Itinerary::setModelsData($routeDataArr);
			}
		}

		$model = $objPage->booking->getLeadModel();

		$model->bkg_booking_type = $triptype;
		$model->getRoutes();
		if (in_array($triptype, [1, 10, 11]))
		{
			unset($model->bookingRoutes[1]);
			unset($objPage->booking->routes[1]);
		}
		$quotData					 = Quote::populateFromModel($model, $model->bkg_vehicle_type_id);
		$response					 = new \Stub\booking\QuoteResponse();
		$response->setData($quotData);
		$objPage->quote				 = $response;
		$objPage->booking->distance	 = $response->quotedDistance;
		$objPage->booking->duration	 = $response->estimatedDuration;
		$objPage->booking->tripType	 = $triptype;
		$objPage->tripType			 = $triptype;
		if ($defModel->bkg_booking_type != $triptype)
		{
			$objPage->booking->id = null;
		}

		//bkg_amount, return date time, bkg_drop_date, bkg_drop_time, bkg_rate_per_km, bkg_rate_per_km_extra, bkg_net_charge, bkg_route_data,bkg_parking_charge
		$objPage->saveLead();

		if ($leadid > 0)
		{
			$objPage->booking->defLeadId = $leadid;
		}
		if ($objPage->booking->defLeadId == '' || $objPage->booking->defLeadId == 0)
		{
			$objPage->booking->defLeadId = $objPage->booking->id;
		}
		$hash	 = Yii::app()->shortHash->hash($objPage->booking->id);
		$url	 = Yii::app()->createUrl('book-cab/travellerinfo/' . $objPage->booking->id . '/' . $hash . '/' . $objPage->booking->defLeadId . '/' . Yii::app()->shortHash->hash($objPage->booking->defLeadId));
		echo json_encode(['success' => true, 'url' => $url]);
		Yii::app()->end();
	}

	public function actionReschedulebooking()
	{
		$this->checkV3Theme();
		$bkgid			 = Yii::app()->request->getParam('bkg_id');
		$newPickupDate	 = Yii::app()->request->getParam('newPickupDate');
		$newPickupTime	 = Yii::app()->request->getParam('newPickupTime');
		$isCommit		 = Yii::app()->request->getParam('isCommit') | 0;
		$prevModel		 = Booking::model()->findByPk($bkgid);
		$brtRoute		 = new BookingRoute();

		$isValidate = $prevModel->validateOnReschedule();

		if (!$isValidate)
		{
			echo json_encode(['success' => false, 'errors' => $prevModel->getErrors()]);
			Yii::app()->end();
		}

		$bkgPrefModel = BookingPref::model()->findBySql("SELECT bpr_bkg_id FROM booking_pref WHERE bpr_rescheduled_from = {$prevModel->bkg_id}");
		if ($bkgPrefModel != '')
		{
			$existBookingModel = Booking::model()->findByPk($bkgPrefModel->bpr_bkg_id);
			if (in_array($existBookingModel->bkg_status, [1, 15]))
			{
				$existBookingModel->initReschedule($prevModel);
				$outputJs = Yii::app()->request->isAjaxRequest;
				$this->renderPartial('rescheduleReview_exists', array('newModel' => $existBookingModel, 'prevModel' => $prevModel), false, $outputJs);
				Yii::app()->end();
			}
		}

		if ($newPickupDate != '' && $newPickupTime != '' && $isCommit == 1)
		{
			$result = $prevModel->confirmReschedule($newPickupDate, $newPickupTime);
			echo json_encode($result);
			Yii::app()->end();
		}
		if ($newPickupDate != '' && $newPickupTime != '')
		{
			$result = $prevModel->reschedule($newPickupDate, $newPickupTime);
			if (!$result)
			{
				echo json_encode(['success' => false, 'errors' => $prevModel->getErrors()]);
				Yii::app()->end();
			}
			if ($result instanceof Booking)
			{
				$newModel	 = $result;
				$outputJs	 = Yii::app()->request->isAjaxRequest;
				$this->renderPartial('rescheduleReview1', array('newModel' => $newModel, 'prevModel' => $prevModel), false, $outputJs);
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
		$this->$method('reschedulebooking', array('model' => $prevModel, 'brtRoute' => $brtRoute), false, $outputJs);
	}

	public function actionShowPaymentDetails()
	{
		$this->checkV3Theme();
		$bkgid		 = Yii::app()->request->getParam('bkg_id');
		$pgData		 = PaymentGateway::fetchTransactionsByBooking($bkgid);
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('showPayments', array('pgData' => $pgData), false, $outputJs);
	}

	public function actionCheckCancellation()
	{
		$bkg_id			 = Yii::app()->request->getParam('bookingid');
		$success		 = 0;
		$arr			 = [];
		$model			 = Booking::model()->findByPk($bkg_id);
		$cancelCharges	 = $model->calculateRefund();
		$dtArr			 = array_keys($cancelCharges->slabs);
		$now			 = Filter::getDBDateTime();

		if (strtotime($now) < strtotime($dtArr[0]))
		{
			$success = 1;
		}

		$arr = ['slab' => $cancelCharges->slabs, 'showAlert' => $success];
		echo json_encode($arr);
		Yii::app()->end();
	}
}
