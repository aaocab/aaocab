<?php

use Booking;

include_once(dirname(__FILE__) . '/BaseController.php');

class BookingController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = 'main';
	public $email_receipient, $useUserReturnUrl;
	public $current_page = '';
	public $title		 = '';
	public $packageName;

//public $layout = '//layouts/column2';pre

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter + create',
				'bypass' => false),
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
				'actions'	 => array(),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(
					'REST.GET', 'REST.PUT', 'REST.REQUEST', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS', 'generateInvoice'),
				'users'		 => array('*'),
			),
			['allow',
				'actions'	 => ['new', 'list'],
				'users'		 => ['@']
			],
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
			$ri	 = array('/getQuote', '/getQuoteV2', '/getCancellationList', '/updateBooking', '/cancel', '/create', '/otpVerify', '/emergencyAlert'
				, '/routeValidate', '/sosTripTracking', '/extraAdditional', '/cancelReasonList', '/validationAirport'
				, '/notifyGNowVendor', '/getGNowInventoryMessage', '/getGNowOfferList', '/processGNowbidAccept', '/processGNowbidReject', '/cancelGnow'
				, '/routeDetails', '/addAddress', '/getDetailsUnAuth', '/getPackages', '/getPackageDetails', '/popularRoutes', '/getFinalDetails', '/getDetails'
				, '/showBooking', '/getCabService', '/viewDrvDetails', '/getBanner', '/cancelWarningMsg', '/applyAddon');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

//Getting qoutes with cab lists
		$this->onRest('req.post.getQuote.render', function () {
			return $this->renderJSON($this->getQuoteV1());
		});

//Getting qoutes with cab lists
		$this->onRest('req.post.getQuoteV2.render', function () {
			return $this->renderJSON($this->getQuoteV2());
		});

//Creating a new booking
		$this->onRest('req.post.create.render', function () {
			return $this->renderJSON($this->create());
		});

		//Confirm a new booking as ( Confirm as cash ) 
		$this->onRest('req.post.cashConfirm.render', function () {
			return $this->renderJSON($this->cashConfirm());
		});

//Send notification requests to vendors for the booking
		$this->onRest('req.post.notifyGNowVendor.render', function () {
			return $this->renderJSON($this->notifyGNowVendor());
		});

// Getting details of a booking
		$this->onRest('req.post.getDetails.render', function () {
			return $this->renderJSON($this->getDetails());
		});
		//Send notification requests to vendors for the booking
		$this->onRest('req.post.getGNowOfferList.render', function () {
			return $this->renderJSON($this->getGNowOfferList());
		});
		//Send notification requests to vendors for the booking
		$this->onRest('req.post.processGNowbidAccept.render', function () {
			return $this->renderJSON($this->processGNowbidAccept());
		});
		$this->onRest('req.post.processGNowbidReject.render', function () {
			return $this->renderJSON($this->processGNowbidReject());
		});
		$this->onRest('req.post.cancelGnow.render', function () {
			return $this->renderJSON($this->cancelGnow());
		});
		$this->onRest('req.post.getFinalDetails.render', function () {
			return $this->renderJSON($this->getFinalDetails());
		});

// Getting details of a booking for unauthorized
		$this->onRest('req.post.getDetailsUnAuth.render', function () {
			return $this->renderJSON($this->getDetailsUnAuth());
		});

// Get booking list
		$this->onRest('req.post.list.render', function () {
			return $this->renderJSON($this->bookingList());
		});

// Verify OTP
		$this->onRest('req.post.otpVerify.render', function () {
			return $this->renderJSON($this->otpVerify());
		});

// Emergency Alert
		$this->onRest('req.get.emergencyAlert.render', function () {
			return $this->renderJSON($this->emergencyAlert());
		});

// Validating Routes
		$this->onRest('req.post.routeValidate.render', function () {
			return $this->renderJSON($this->routeValidate());
		});

// SOS Trip Tracking
		$this->onRest('req.post.sosTripTracking.render', function () {
			return $this->renderJSON($this->sosTripTracking());
		});

// Cancelling a booking
		$this->onRest('req.post.cancel.render', function () {
			return $this->renderJSON($this->cancel());
		});
		//Driver & Cab Details viewed
		$this->onRest('req.post.viewDrvDetails.render', function () {
			return $this->renderJSON($this->updateDriverDetailsViewed());
		});
// Add extra details of booking
		$this->onRest('req.post.extraAdditional.render', function () {
			return $this->renderJSON($this->extraAdditionalData());
		});

		$this->onRest('req.post.updateBooking.render', function () {
			return $this->renderJSON($this->updateBooking());
		});

		$this->onRest('req.post.updateBillingInfo.render', function () {
			return $this->renderJSON($this->updateBilling());
		});

		$this->onRest('req.get.cancelReasonList.render', function () {
			return $this->renderJSON($this->cancelReasonList());
		});

		$this->onRest('req.post.validationAirport.render', function () {
			return $this->renderJSON($this->getAirportValidation());
		});

		$this->onRest('req.post.routeDetails.render', function () {
			return $this->renderJSON($this->routeDetails());
		});

		$this->onRest('req.post.getPackages.render', function () {
			return $this->renderJSON($this->getPackages());
		});

		$this->onRest('req.post.getPackageDetails.render', function () {
			return $this->renderJSON($this->getPackageDetails());
		});

// one way cab listing 
		$this->onRest('req.post.popularRoutes.render', function () {
			return $this->renderJSON($this->popularRoutes());
		});

		$this->onRest('req.post.showBooking.render', function () {
			return $this->renderJSON($this->showBooking());
		});

		$this->onRest('req.get.getCabService.render', function () {
			Logger::create($this->renderJSON($this->getCabService()));
			return $this->renderJSON($this->getCabService());
		});

		$this->onRest('req.post.addAddress.render', function () {
			return $this->renderJSON($this->addAddress());
		});

		$this->onRest('req.post.getUserAddress.render', function () {
			return $this->renderJSON($this->getUserAddress());
		});

		$this->onRest('req.get.getBanner.render', function () {
			return $this->renderJSON($this->getBanner());
		});

		// Warning message for cancel booking
		$this->onRest('req.post.cancelWarningMsg.render', function () {
			return $this->renderJSON($this->cancelWarningMsg());
		});

		// Addon Apply
		$this->onRest('req.post.applyAddon.render', function () {
			return $this->renderJSON($this->applyAddon());
		});

		$this->onRest('req.post.reportIssueCat.render', function () {
			return $this->renderJSON($this->reportIssueCategory());
		});

		$this->onRest('req.post.postIssue.render', function () {
			return $this->renderJSON($this->postIssue());
		});

		$this->onRest('req.post.reschedule.render', function () {
			return $this->renderJSON($this->reschedule());
		});

		$this->onRest('req.post.processReschedule.render', function () {
			return $this->renderJSON($this->processReschedule());
		});

		$this->onRest('req.post.confirmReschedule.render', function () {
			return $this->renderJSON($this->confirmRescheduleV1());
		});
	}

	public function showBooking()
	{
		$data		 = Yii::app()->request->rawBody;
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);

//		$curl = curl_init();
//		curl_setopt_array($curl, array(
//		  CURLOPT_URL => "/api/conapp/booking/getDetailsUnAuth",
//		  CURLOPT_RETURNTRANSFER => true,
//		  CURLOPT_ENCODING => "",
//		  CURLOPT_MAXREDIRS => 10,
//		  CURLOPT_TIMEOUT => 0,
//		  CURLOPT_FOLLOWLOCATION => true,
//		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//		  CURLOPT_CUSTOMREQUEST => "POST",
//		  CURLOPT_POSTFIELDS =>"{id:".$jsonObj->id."}",
//		  CURLOPT_HTTPHEADER => array(
//			"Content-Type: text/plain"
//		  ),
//		));
//		$response = curl_exec($curl);
//		curl_close($curl);
//		echo $response;


		require_once 'HTTP/Request2.php';
		$request = new HTTP_Request2();
		$request->setUrl('http://192.168.1.175:81/api/conapp/booking/showBooking');
		$request->setMethod(HTTP_Request2::METHOD_POST);
		$request->setConfig(array(
			'follow_redirects' => TRUE
		));
		$request->setHeader(array(
			'Content-Type' => 'text/plain'
		));
		$request->setBody("'" . $data . "'");
		try
		{
			$response = $request->send();
			if ($response->getStatus() == 200)
			{
				echo $response->getBody();
			}
			else
			{
				echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
				$response->getReasonPhrase();
			}
		}
		catch (HTTP_Request2_Exception $e)
		{
			echo 'Error: ' . $e->getMessage();
		}
	}

//////////////////////////////////////////////////////////

	public function getAirportValidation()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data		 = Yii::app()->request->rawBody;
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			/* @var $obj \Stub\consumer\AirportRequest */
			$obj		 = $jsonMapper->map($jsonObj, new \Stub\consumer\AirportRequest());
			/** @var Booking $model */
			$model		 = $obj->getModel();
			if ($model->bookingRoutes[0]->brt_from_city_is_airport == null && $model->bookingRoutes[0]->brt_to_city_is_airport == null)
			{
				throw new Exception("Invalid Request", ReturnSet::ERROR_INVALID_DATA);
			}
			$dataSet = Cities::getCityDetails($model);
			if ($dataSet)
			{
				foreach ($dataSet->bookingRoutes as $key => $value)
				{
					$dataSet->bookingRoutes[$key]->brt_from_latitude	 = $dataSet->bookingRoutes[$key]->brt_from_place_lat;
					$dataSet->bookingRoutes[$key]->brt_from_longitude	 = $dataSet->bookingRoutes[$key]->brt_from_place_long;
					$dataSet->bookingRoutes[$key]->brt_to_latitude		 = $dataSet->bookingRoutes[$key]->brt_to_place_lat;
					$dataSet->bookingRoutes[$key]->brt_to_longitude		 = $dataSet->bookingRoutes[$key]->brt_to_place_long;
				}
				/* @var $responseData \Stub\consumer\AirportResponse */
				$responseData	 = new \Stub\consumer\AirportResponse();
				$responseData->setData($dataSet);
				$response		 = Filter::removeNull($responseData);

				$returnSet->setStatus(true);
				$returnSet->setData($response);
				Logger::create("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage('Cab not available within that distance');
				//$returnSet->setErrors('No Records Found', ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = $returnSet->setException($ex);
			Logger::exception($ex);
		}
		return $returnSet;
	}

	public function getQuoteV1()
	{
		if (Filter::checkIOSDevice())
		{
			$data = Yii::app()->request->rawBody;
			return $this->getQuote($data);
		}
		else
		{
			throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}
	}

	public function getQuoteV2()
	{
		$data = Yii::app()->request->rawBody;

		$objMCrypt	 = new MCrypt('');
		$data		 = $objMCrypt->decrypt($data);

		return $this->getQuote($data);
	}

	public function getQuote($data)
	{
		$returnSet = new ReturnSet();

		if (!$data)
		{
			throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}
		Logger::info('getQuote=>Request');
		Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);
		try
		{
			$userData = UserInfo::getInstance();

			/* @var $obj Stub\booking\UserQuoteRequest */
			$obj = $jsonMapper->map($jsonObj, new \Stub\booking\UserQuoteRequest());

			/** @var Booking $model */
			$model			 = $obj->getModelData();
			$leadPhone		 = $obj->userInfo->primaryContact->number;
			$leadEmail		 = $obj->userInfo->email;
			$leadPhoneCode	 = $obj->userInfo->primaryContact->code;

			Logger::info('getQuote=>bookingRoutes');
			Logger::info(json_encode($model->bookingRoutes));

			// Check for Spam
			$flgSpam = Filter::checkSpam($leadEmail);
			if ($flgSpam)
			{
				throw new Exception("Max Request Exceeded.", ReturnSet::ERROR_INVALID_DATA);
			}

			$tempModel = BookingTemp::createLeadModel($model, $userData, $leadPhone, $leadEmail, $leadPhoneCode);
			if (!$tempModel)
			{
				goto handleErrors;
			}

			Logger::info('getQuote=>tempModel');

			$tempModel->bkg_platform = Booking::Platform_App;
			$vehicleType			 = $model->bkg_vehicle_type_id;

			if ($vehicleType == null)
			{
				$vehicleType = SvcClassVhcCat::getIds();
			}

			$quotData	 = Quote::populateFromModel($tempModel, $vehicleType, false, true, true, 0, true);
			$response	 = new \Stub\booking\QuoteResponse();
			$response->setData($quotData);
			$firstKey	 = array_key_first($quotData);
			if ($quotData[$firstKey]->gozoNow)
			{
				$message = [
					'Inventory is limited & prices are changing too fast for your date & time of travel.',
					'we will show you price ranges for cars. As always, we will provide you a final price before you book.'];

				$response->GNowInventoryMessage = $message;
			}
			$data = Filter::removeNull($response);
			if ($data == null)
			{
				throw new Exception(CJSON::encode("No cabs are available."), ReturnSet::ERROR_VALIDATION);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($data);

			Logger::info('getQuote=>Response');
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $e)
		{
			handleErrors:
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($e);
		}
		return $returnSet;
	}

	public function create()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		if (!$data)
		{
			throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}
		Logger::trace("Request : " . $data);
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);
		try
		{
			/* @var $obj \Stub\booking\CreateRequest */
			$obj	 = $jsonMapper->map($jsonObj, new \Stub\booking\CreateRequest());
			/** @var Booking $model */
			$model	 = $obj->getModel(null, null);
			if (in_array($obj->tripType, [9, 10, 11]))
			{
				$model->bkg_booking_type = $obj->tripType;
			}

			if ($model->bkg_booking_type == 5 && $model->bkg_package_id > 0)
			{
				$model->bkg_pickup_date	 = $model->bkg_pickup_date . " " . Yii::app()->params['defaultPackagePickupTime'];
				$routes					 = BookingRoute::model()->populateRouteByPackageId($model->bkg_package_id, $model->bkg_pickup_date);
				$model->bookingRoutes	 = $routes;
				$rCount					 = count($routes);
				$model->bkg_from_city_id = $routes[0]->brt_from_city_id;
				$model->bkg_to_city_id	 = $routes[$rCount - 1]->brt_to_city_id;
			}
			$svcModel							 = \SvcClassVhcCat::model()->getVctSvcList('object', 0, 0, $model->bkg_vehicle_type_id);
			$isGozonow							 = $model->bkgPref->bkg_is_gozonow;
			$cancelRuleId						 = \CancellationPolicy::getCancelRuleId($model->bkg_agent_id, $svcModel->scv_id, $model->bkg_from_city_id, $model->bkg_to_city_id, $model->bkg_booking_type, $isGozonow);
			Logger::info("Params  cancelRuleId : " . $model->bkg_agent_id . " - " . $svcModel->scv_id . " - " . $model->bkg_from_city_id . " - " . $model->bkg_to_city_id . " - " . $model->bkg_booking_type);
			$model->bkgPref->bkg_cancel_rule_id	 = $cancelRuleId;

			unset($model->bkg_agent_id);
			//$model->scenario				 = 'validateData';
			$model->scenario = 'validateStep1';
			$errors			 = CActiveForm::validate($model, null, false);
			if ($errors != '[]')
			{
				goto handleErrors;
			}
			$model->addNew(true, true);
			//Logger::info("Booking Created Successfully");
			if ($model->hasErrors())
			{
				goto handleErrors;
			}
			$response = new \Stub\booking\CreateResponse();
			$response->setData($model);

			$sccId						 = SvcClassVhcCat::getClassById($model->bkg_vehicle_type_id);
			$addOns						 = new \Stub\common\Addons;
			$response->applicableAddons	 = []; //$addOns->getData($model->bkg_from_city_id, $sccId, $model->bkgInvoice->bkg_base_amount);

			if ($model->bkgPref->bkg_is_gozonow == 1)
			{
				$message				 = BookingCab::gnowNotifyBulk($model->bkg_bcb_id);
				$returnSet->setMessage($message);
				$result					 = Booking::getGNowTimerData($model);
				$response->gozoNowData	 = $result;
			}


			$data1 = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($data1);
			Logger::trace("Response : " . CJSON::encode($returnSet));
			goto result;

			handleErrors:
			$errors = $model->getErrors();
			throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
			Logger::exception($e);
		}

		result:
		return $returnSet;
	}

	public function cashConfirm()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$obj	 = Yii::app()->request->getJSONObject(new Beans\common\WalletPayment());
			Logger::create("Request : " . CJSON::encode($obj), CLogger::LEVEL_INFO);
			AppTokens::validateToken($token);
			$bkgId	 = $obj->bookingId;
			$cash	 = $obj->isCash;
			/* var @model Booking */
			$model = Booking::model()->findByPk($bkgId);
			if ($cash == 1 && $model!='')
			{
				if($model->bkgPref->bkg_is_confirm_cash==1 && in_array($model->bkg_status, [2, 3, 5, 6, 7]))
				{
					goto end;
				}
				$model->bkgPref->bkg_is_confirm_cash = 1;
				$model->bkgPref->save();

				$model->confirm(true, true);
				$model->bkgUserInfo->bkg_user_last_updated_on = new CDbExpression('NOW()');
				$model->save();
				$model->bkgInvoice->save();

				goto end;	
			}
			else
			{
				throw new Exception("Payment mode validation failed..", ReturnSet::ERROR_VALIDATION);
			}

			end:

			$responseObj = new \Beans\common\WalletPayment();
			$response	 = $responseObj->setDataForCash($model->bkgInvoice, $cash);
			$message	 = "Booking confirm as cash.";
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			Logger::error($ex);
		}
		$returnSet->setData($response);
		$returnSet->setMessage($message);
		return $returnSet;
	}

	/**
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function getDetails()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$userInfo	 = UserInfo::getInstance();
		try
		{
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			$returnSet	 = self::details($jsonObj);
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($e);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function getDetailsUnAuth()
	{
		$returnSet	 = new ReturnSet();
		$userInfo	 = UserInfo::getInstance();
		try
		{
			/* @var $model AppTokens */
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			$returnSet	 = self::details($jsonObj);
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($e);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param object $jsonObj
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public static function details($jsonObj)
	{
		$returnSet = new ReturnSet();
		if (property_exists($jsonObj, 'id'))
		{
			$model = \Booking::model()->findByPk($jsonObj->id);
		}
		else if (property_exists($jsonObj, 'code'))
		{
			$model = \Booking::model()->getByCode($jsonObj->code);
		}
		if (!$model)
		{
			throw new Exception("Invalid Booking", ReturnSet::ERROR_INVALID_DATA);
		}
// FOR PACKAGE
		if ($model->bkg_booking_type == 5)
		{
			$model->bookingRoutes	 = BookingRoute::model()->populateRouteByPackageId($model->bkg_package_id, $model->bkg_pickup_date, true);
			$packageData			 = Package::model()->getPackage($model->bkg_package_id);
			$packageName			 = $packageData['pck_name'];
		}

		if (in_array($model->bkg_status, [2,3,5]) && $model->bkgPref->bpr_rescheduled_from > 0)
		{
			$prevModel = \Booking::model()->findByPk($model->bkgPref->bpr_rescheduled_from);
			if (in_array($prevModel->bkg_status, [9, 10]) || in_array($model->bkg_status, [9, 10]))
			{
				goto skipCancel;
			}
			$returnOnSet = Booking::cancelOnReschedule($model->bkg_id, $model->bkgPref->bpr_rescheduled_from);
			if ($returnOnSet->getStatus())
			{
				$isRescheduled = 1;
			}
		}
		skipCancel:

		$response	 = new \Stub\booking\GetDetailsResponse();
		$response->setData($model, $packageName);
		$response	 = Filter::removeNull($response);
		$returnSet->setData($response);
		$returnSet->setStatus(true);
		return $returnSet;
	}

	/**
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 * this function is deprecated
	 */
	public function transactionSummary()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
//AppTokens::validateToken($token);
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception('Invalid Request : ', ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj		 = CJSON::decode($data, false);
			$creditAmount	 = ($jsonObj->creditAmount > 0) ? $jsonObj->creditAmount : 0;
			$bookingId		 = $jsonObj->bookingId;
			$transCode		 = $jsonObj->orderId;
			$count			 = 1;
			a:
			if ($bookingId > 0)
			{
				$model = Booking::model()->findByPk($bookingId);
			}
			if (!$model)
			{
				throw new Exception('Invalid Data : ', ReturnSet::ERROR_INVALID_DATA);
			}
			if (isset($transCode) && $transCode == '')
			{
				$tModel		 = PaymentGateway::model()->find('apg_booking_id=:bkg_id', ['bkg_id' => $model->bkg_id]);
				$transCode	 = $tModel->apg_code;
			}
			if (isset($transCode) && $transCode != '')
			{
				$tModel		 = PaymentGateway::model()->find('apg_code=:apg_code', ['apg_code' => $transCode]);
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
				if ($transModel->apg_booking_id != $bookingId)
				{
					Yii::log($transModel->apg_booking_id, CLogger::LEVEL_INFO);
					throw new Exception('Invalid Data : ', ReturnSet::ERROR_INVALID_DATA);
				}
			}
			if ($model != '')
			{

//$bookModel						 = Booking::model()->with('bkgVehicleType')->findbyPk($model->bkg_id);
				$bookModel						 = Booking::model()->findbyPk($model->bkg_id);
				$hr								 = date('G', mktime(0, $bookModel->bkg_trip_duration)) . " Hr";
				$min							 = (date('i', mktime(0, $bookModel->bkg_trip_duration)) != '00') ? ' ' . date('i', mktime(0, $bookModel->bkg_trip_duration)) . " min" : '';
				$bookModel->trip_duration_format = $hr . $min;
				$bookModel->trip_distance_format = $bookModel->bkg_trip_distance . ' Km';
			}

			$fetchModel	 = \Booking::model()->getDetailbyIdCustomer($model->bkg_id);
			$response	 = new Stub\booking\TransactionSummaryResponse();
			$response->setModelData($fetchModel, $transId, $tranStatus);
			$response	 = Filter::removeNull($response);

			$returnSet->setData($response);
			$returnSet->setStatus(true);
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function bookingList()
	{
		$returnSet = new ReturnSet();
		/* @var $model AppTokens */


		try
		{

			$data		 = Yii::app()->request->rawBody;
			$userInfo	 = UserInfo::getInstance();
			$userId		 = $userInfo->userId;
			if (!$userId)
			{
				throw new Exception("Invalid User.", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			$bookingData = Booking::getListByUser($userId, $jsonObj->sort, $jsonObj->pageNumber);

			$response	 = new \Stub\booking\ListResponse();
			$response->getData($bookingData);
			$data		 = Filter::removeNull($response->bookings);
			if (count($bookingData) == 0)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Bookings not available.");
				$returnSet->setErrors(ReturnSet::ERROR_NO_RECORDS_FOUND);
				goto bookList;
			}
			$returnSet->setStatus(true);
			$returnSet->setData($data);
			bookList:
			Logger::create("Response :" . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function routeValidate()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		try
		{
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			if (!$jsonObj)
			{
				throw new Exception(CJSON::encode('Invalid Request'), ReturnSet::ERROR_VALIDATION);
			}
			/** @var QuoteRequest $obj */
			$obj	 = $jsonMapper->map($jsonObj, new \Stub\booking\QuoteRequest());
			/** @var Stub\common\Booking $model */
			$model	 = $obj->getModel();
			$errors	 = BookingRoute::validateRoutes($model->bookingRoutes, $model->bkg_booking_type, null);
			if ($errors != [])
			{
				throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
			}
			$returnSet->setStatus(true);
			$returnSet->setMessage("Valid Route data");
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($e);
		}
		Logger::info("Response ===>" . json_encode($returnSet));
		return $returnSet;
	}

	public function otpVerify()
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$data = Yii::app()->request->rawBody;
//$data	 = '{"bookingId":1195516,"otp":1000}';
//$data = '{"bookingId":1195516,"otp":""}';
			if (!$data)
			{
				throw new Exception('Invalid Request : ', ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$jsonObj = CJSON::decode($data, false);

			$model = Booking::model()->findByPk($jsonObj->bookingId);
			if (!$model)
			{
				throw new Exception('Invalid Booking ID: ', ReturnSet::ERROR_INVALID_DATA);
			}
			$responseSet = BookingUser::model()->verifyOtpConsumer($jsonObj, $model);
			if ($responseSet['success'])
			{
				$returnSet->setStatus(true);
			}
			else
			{
				$returnSet->setStatus(false);
			}
			$returnSet->setData(['isVerifyOtp' => $responseSet['isVerifyOtp']]);
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function emergencyAlert()
	{
		$returnSet = new ReturnSet();
		try
		{
			$userInfo	 = UserInfo::getInstance();
			$userId		 = $userInfo->userId; //100499; //82030; // 				
			if (!$userId)
			{
				throw new Exception("User not found", ReturnSet::ERROR_VALIDATION);
			}
			$bkgDetails = Users::getBookingsByUserId($userId);
			if (!empty($bkgDetails))
			{
				$bModel				 = Booking::model()->findByPk($bkgDetails[0]['bkg_id']);
				$bkgModel			 = Booking::model()->getDetailbyId($bModel->bkg_bcb_id);
				$sosSmsTriggerFlag	 = $bModel->bkgTrack->bkg_sos_sms_trigger;

				if ($bkgModel != '' && $sosSmsTriggerFlag == 2)
				{
					$response	 = new \Stub\booking\EmergencyAlertResponse();
					$response->setData($bkgModel[0], $bModel->bkgTrack);
					$response	 = Filter::removeNull($response);
					$returnSet->setStatus(true);
					$returnSet->setData($response);
				}
				else
				{
					$returnSet->setStatus(true);
					$returnSet->setMessage('Booking Details Not Found');
				}
			}
			else
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage('No Records Found');
			}
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($e);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function sosTripTracking()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$transaction = DBUtil::beginTransaction();
		try
		{
			AppTokens::validateToken($token);
			$data = Yii::app()->request->rawBody;
			/* {
			  'location': {
			  'longitude': 88.4335553640326,
			  'latitude': 22.575336278763515
			  },
			  'device': {
			  'uniqueId': '2F1C677C-46CD-4D6A-BF7D-F51E16B86AF6'
			  },
			  'bookingId': '1351819',
			  'timeStamp': 46813256466
			  } */
			if (!$data)
			{
				throw new Exception('Invalid Request : ', ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$userInfo		 = UserInfo::getInstance();
			$userId			 = $userInfo->userId; // 374;		
			$jsonObj		 = CJSON::decode($data, false);
			/* @var $obj \Stub\consumer\SOSRequest */
			$jsonMapper		 = new JsonMapper();
			$obj			 = $jsonMapper->map($jsonObj, new \Stub\consumer\SOSRequest());
			$bookingModel	 = Booking::model()->findByPk($obj->bookingId);
			$model			 = $obj->getModel($bookingModel);

			$dir = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'sostriptracking';
			if (!is_dir($dir))
			{
				mkdir($dir);
			}
			$dirTrip = $dir . DIRECTORY_SEPARATOR . $userId;
			if (!is_dir($dirTrip))
			{
				mkdir($dirTrip);
			}
			$dirFolderName = $dirTrip . DIRECTORY_SEPARATOR . $model->bkgTrack->btk_bkg_id;
			if (!is_dir($dirFolderName))
			{
				mkdir($dirFolderName);
			}
			$date		 = date("Y-d-m H:i:s");
			$dataResult	 = [];

			$updateRows	 = [];
			$file		 = $dirFolderName . "/sosTripTracking.csv";
			if (!file_exists($file))
			{
				$handle			 = fopen($file, 'w');
				fputcsv($handle, array("Bkg_Id", "Time_Stamp", "SOS_Lat", "SOS_Long", "Recived_On", "Device_Id"));
				fputcsv($handle, array($model->bkgTrack->btk_bkg_id, $model->bkgTrack->book_time, $model->bkgTrack->bkg_sos_latitude, $model->bkgTrack->bkg_sos_longitude, $date, $model->bkgTrack->bkg_sos_device_id));
				$updateRows[]	 = $model->bkgTrack->btk_bkg_id;
				fclose($handle);
			}
			else
			{
				$handle			 = fopen($file, 'a');
				fputcsv($handle, array($model->bkgTrack->btk_bkg_id, $model->bkgTrack->book_time, $model->bkgTrack->bkg_sos_latitude, $model->bkgTrack->bkg_sos_longitude, $date, $model->bkgTrack->bkg_sos_device_id));
				$updateRows[]	 = $model->bkgTrack->btk_bkg_id;
				fclose($handle);
			}
			$returnSet->setStatus(true);
			$message = 'SOS Trip Tracking Done Successfully for BookingID: ' . $model->bkg_booking_id;
			$returnSet->setMessage($message);
			DBUtil::commitTransaction($transaction);
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($e);
		}
		return $returnSet;
	}

	public function updateBooking()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);

		try
		{
			AppTokens::validateToken($token);
			$data		 = Yii::app()->request->rawBody;
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);

			/* @var $obj Stub\booking\UpdateRequest */
			$obj			 = $jsonMapper->map($jsonObj, new Stub\booking\UpdateRequest());
			/** @var Booking $model */
			$model			 = $obj->getModel();
			$model->scenario = 'validateData';
			$errors			 = CActiveForm::validate($model, null, false);
			if ($errors == '[]')
			{
				$userInfo	 = UserInfo::getInstance();
				$bkgModel	 = Booking::model()->getBkgIdByBookingId($model->bkg_booking_id);
				$returnSet	 = $model->updateBookingApi($model, $bkgModel);
			}
			else
			{
				$errors = $model->getErrors();
				throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
			}
		}
		catch (Exception $e)
		{

			$returnSet = ReturnSet::setException($e);
			Logger::exception($e);
		}
		return $returnSet;
	}

	public function extraAdditionalData()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody;  //$data = '{"bookingId":1195926,"sendEmail":1,"sendSms":1,"additionalInfo":{"noOfPerson":100,"bkg_num_large_bag":2,"noOfSmallBags":0,"carrierRequired":0,"driverEnglishSpeaking":1,"driverHindiSpeaking":1,"kidsTravelling":1,"specialInstructions":"99Hello World!!!123","seniorCitizenTravelling":1,"womanTravelling":1}}';
			if (!$data)
			{
				throw new Exception('Invalid Request: ', ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$jsonObj	 = CJSON::decode($data, false);
			$jsonMapper	 = new JsonMapper();
			$obj		 = $jsonMapper->map($jsonObj, new \Stub\consumer\AdditionalRequest());
			$model		 = $obj->getData();
			if (!$model)
			{
				throw new Exception('Invalid Request: ', ReturnSet::ERROR_INVALID_DATA);
			}
			$mData					 = (array) $model->bkgAddInfo->attributes;
			$mData['bkg_id']		 = $model->bkg_id;
			$mData['bkg_send_email'] = $model->bkgPref->bkg_send_email;
			$mData['bkg_send_sms']	 = $model->bkgPref->bkg_send_sms;
			$mData					 = Filter::removeEmptyKeysFromArray($mData);

			$datasuccess = $this->extraAdditional($mData);
			$returnSet->setStatus(true);
			$returnSet->setMessage('Additional Details saved sucessfully.');
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($e);
		}
		return $returnSet;
	}

	public function cancel()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$data		 = Yii::app()->request->rawBody; // '{"bookingId":1196384,"reason":"abc","reasonId":"1"}';
		try
		{
			if (!$data)
			{
				throw new Exception('Invalid Request: ', ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$jsonObj	 = json_decode($data, false);
			/* @var $tokenModel AppTokens */
			$tokenModel	 = AppTokens::validateToken($token);
			$model		 = Booking::model()->getBkgIdByBookingId($jsonObj->bookingId);
			if ($model->bkg_agent_id > 0)
			{
				$result = Booking::model()->cancelBookingApi($jsonObj);
			}
			else
			{
				$result = Booking::model()->cancelBooking($jsonObj);
			}
			if ($result['success'] == true)
			{
				$response = new Stub\booking\CancelResponse();
				$response->setData($result);
				$returnSet->setStatus(true);
				$returnSet->setData($response);
				$returnSet->setMessage("Booking cancelled successfully");
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage($result['errors']);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = $returnSet->setException($ex);
		}

		return $returnSet;
	}

	public function extraAdditional($data)
	{
		$model							 = Booking::model()->findbyPk($data['bkg_id']);
		$model->attributes				 = $data;
		$model->bkgAddInfo->attributes	 = $data;
		$model->bkgPref->attributes		 = $data;
//$countRoutes					 = count($routes);
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
				$model->bkgUserInfo->bkg_user_last_updated_on	 = new CDbExpression('NOW()');
				$model->bkgUserInfo->bkg_user_id				 = $userInfo->userId;

				if (!$model->save())
				{
					throw new Exception("Failed to save data", ReturnSet::ERROR_VALIDATION);
				}
				if (!$model->bkgUserInfo->save())
				{
					throw new Exception("Failed to save data", ReturnSet::ERROR_VALIDATION);
				}
				if (!$model->bkgInvoice->save())
				{
					throw new Exception("Failed to save data", ReturnSet::ERROR_VALIDATION);
				}
				if (!$model->bkgTrail->save())
				{
					throw new Exception("Failed to save data", ReturnSet::ERROR_VALIDATION);
				}
				if (!$model->bkgTrack->save())
				{
					throw new Exception("Failed to save data", ReturnSet::ERROR_VALIDATION);
				}
				if (!$model->bkgAddInfo->save())
				{
					throw new Exception("Failed to save data", ReturnSet::ERROR_VALIDATION);
				}
				if (!$model->bkgPref->save())
				{
					throw new Exception("Failed to save data", ReturnSet::ERROR_VALIDATION);
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

	/**
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 * this function is deprecated
	 */
	public function transactionPayuOLD()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$transaction = DBUtil::beginTransaction();
		try
		{
			AppTokens::validateToken($token);
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception('Invalid Request : ', ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$jsonObj	 = CJSON::decode($data, false);
			$jsonMapper	 = new JsonMapper();
			$obj		 = $jsonMapper->map($jsonObj, new \Stub\common\TransactionDetails());
			$bModel		 = $obj->getConsumerDetails();
			if (!$bModel)
			{
				throw new Exception('Invalid Request: ', ReturnSet::ERROR_INVALID_DATA);
			}
			$model = Booking::model()->findByPk($bModel->bkg_id);
			if (!$model)
			{
				throw new Exception('Invalid Request: ', ReturnSet::ERROR_INVALID_DATA);
			}

			$paymentType									 = $bModel->bkgUserInfo->ptype;
			$amount											 = $bModel->bkgInvoice->partialPayment;
			$model->bkg_id									 = $bModel->bkg_id;
			$model->bkgUserInfo->attributes					 = $bModel->bkgUserInfo;
			$model->bkgInvoice->partialPayment				 = $amount;
			$model->bkgUserInfo->bkg_user_last_updated_on	 = new CDbExpression('NOW()');
			$userInfo										 = UserInfo::getInstance();
			$userId											 = $userInfo->userId;
			if (!$userId)
			{
				$userModel = Users::model()->linkUserByEmail($bModel->bkg_id, Booking::Platform_App);
				if ($userModel)
				{
					$userId = $userModel->user_id;
				}
			}
			$model->bkgUserInfo->bkg_user_id = $userId;

			if (!$model->save())
			{
				Logger::create("Validate Errors : " . json_encode($model->getErrors()));
				throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			if (!$model->bkgUserInfo->save())
			{
				Logger::create("Validate Errors : " . json_encode($model->bkgUserInfo->getErrors()));
				throw new Exception(CJSON::encode($model->bkgUserInfo->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			$paymentGateway = PaymentGateway::model()->add($paymentType, $amount, $model->bkg_id, $model->bkg_id, UserInfo::getInstance());
			if ($paymentGateway->apg_id > 0)
			{
				$params['blg_ref_id'] = $paymentGateway->apg_id;
				BookingLog::model()->createLog($model->bkg_id, "Online payment initiated ({$paymentGateway->getPaymentType()} - {$paymentGateway->apg_code})", UserInfo::getInstance(), BookingLog::PAYMENT_INITIATED, '', $params);
				if ($paymentType == PaymentType::TYPE_PAYUMONEY && $amount > 0)
				{
					$suurl = "https://www.gozocabs.com/payment/response/app/1";
					if ($paymentGateway)
					{
						$param_list						 = array();
						$param_list['key']				 = Yii::app()->payu->merchant_key;
						$order_id						 = $paymentGateway->apg_code;
						$param_list['txnid']			 = $order_id;
						$param_list['amount']			 = number_format($paymentGateway->apg_amount, 1, ".", "");
						$param_list['productinfo']		 = $model->bkgFromCity->cty_name . '/' . $model->bkgToCity->cty_name . '/' . $model->bkg_booking_id;
						$param_list['firstname']		 = $model->bkgUserInfo->bkg_bill_fullname;
						$param_list['email']			 = $model->bkgUserInfo->bkg_bill_email;
						$param_list['address1']			 = $model->bkgUserInfo->bkg_bill_address;
						$param_list['city']				 = $model->bkgUserInfo->bkg_bill_city;
						$param_list['state']			 = $model->bkgUserInfo->bkg_bill_state;
						$param_list['country']			 = $model->bkgUserInfo->bkg_bill_country;
						$param_list['phone']			 = $model->bkgUserInfo->bkg_bill_contact;
						$param_list['surl']				 = YII::app()->createAbsoluteUrl('payment/response/ptpid/6/app/1');
						$param_list['furl']				 = YII::app()->createAbsoluteUrl('payment/response/ptpid/6/app/1');
//$param_list['surl']				 = "https://www.gozocabs.com/payment/response/ptpid/6/app/1";
//$param_list['furl']				 = "https://www.gozocabs.com/payment/response/ptpid/6/app/1";
						$param_list['service_provider']	 = 'payu_paisa';
						$checkSum						 = Yii::app()->payu->getChecksumFromArray($param_list);
						$param_list['CHECKSUMHASH']		 = $checkSum;
						$checkSum['merchant_id']		 = Yii::app()->payu->merchant_id;
					}
				}
			}

			$returnSet->setStatus(true);
			$returnSet->setData(["checksum" => $checkSum, "bookingId" => (int) $model->bkg_id]);
			DBUtil::commitTransaction($transaction);
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function cancelReasonList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$rDetail = CancelReasons::model()->getListbyUserType(1);
			if (!$rDetail)
			{
				throw new Exception('Invalid Data : ', ReturnSet::ERROR_INVALID_DATA);
			}
			foreach ($rDetail[0] as $key => $val)
			{
				$data[] = array("id" => $key, "text" => $val, "placeholder" => $rDetail[1][$key]);
			}
			$returnSet->setData($data);
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function routeDetails()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data		 = Yii::app()->request->rawBody;
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);

			if (!$jsonObj->source && !$jsonObj->destination)
			{
				throw new Exception('Invalid Request : ', ReturnSet::ERROR_INVALID_DATA);
			}

			$fromCity	 = $jsonObj->source;
			$toCity		 = $jsonObj->destination;
			$routeModel	 = Route::model()->getbyCities($fromCity, $toCity);
			if (!$routeModel)
			{
				$result = Route::model()->populate($fromCity, $toCity);
				if ($result['success'])
				{
					$routeModel = $result['model'];
				}
			}
			if (!$routeModel)
			{
				throw new Exception('Invalid Request ', ReturnSet::ERROR_INVALID_DATA);
			}
			/* @var $response \Stub\common\Itinerary() */
			$response	 = new \Stub\common\Itinerary();
			$response->getRouteData($routeModel);
			$response	 = Filter::removeNull($response);

			$returnSet->setStatus(true);
			$returnSet->setData($response);
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($e);
			Logger::exception($e);
		}
		return $returnSet;
	}

	public function getPackages()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception('Invalid Request : ', ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			$args		 = ["city" => $jsonObj->fromCity, "min_nights" => $jsonObj->minNights, "max_nights" => $jsonObj->maxNights];
			$pageNumber	 = $jsonObj->pageNumber;
			$isMobileApp = true;
			$pckData	 = Package::model()->getListtoShow('', $args, $isMobileApp, $pageNumber);
			if (!$pckData)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Packages not available.");
				goto packageList;
			}
			$response	 = new \Stub\common\Package();
			$pckResponse = $response->setData($pckData);
			$returnSet->setStatus(true);
			$returnSet->setData($pckResponse);
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
			packageList:
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function getPackageDetails()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception('Invalid Request : ', ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$jsonMapper		 = new JsonMapper();
			$jsonObj		 = CJSON::decode($data, false);
			$packageId		 = $jsonObj->packageId;
			$pickupDate		 = $jsonObj->pickupDt;
			$pickupDate		 = (empty($pickupDate)) ? date("Y-m-d", strtotime('+7 days')) : $pickupDate;
			$ptimePackage	 = Yii::app()->params['defaultPackagePickupTime'];
			$pickupDtTime	 = $pickupDate . " " . $ptimePackage;
			$packagemodel	 = Package::model()->findByPk($packageId);
			if (!$packagemodel)
			{
				throw new Exception('Invalid Data : ', ReturnSet::ERROR_INVALID_DATA);
			}
			$routeModel	 = $packagemodel->packageDetails;
			$resultData	 = BookingRoute::model()->setTripRouteInfo($routeModel, 5, $pickupDtTime);
			if (!$resultData)
			{
				throw new Exception('Invalid Data : ', ReturnSet::ERROR_INVALID_DATA);
			}
			$routeData = BookingRoute::model()->populateRouteByPackageId($packageId, $pickupDtTime, true);
			if (!$routeData)
			{
				throw new Exception('Invalid Data : ', ReturnSet::ERROR_INVALID_DATA);
			}
			$response			 = new \Stub\common\Package();
			$packageRouteInfo	 = $response->setRouteInfo($resultData);

			$response	 = new \Stub\common\Itinerary();
			$routes		 = $response->setModelsData($routeData);
			$routes		 = Filter::removeNull($routes);
			$returnSet->setStatus(true);
//$returnSet->setData($packageRouteInfo);
			$returnSet->setData(["routeInfo" => $packageRouteInfo, "routes" => $routes]);
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function popularRoutes()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data			 = Yii::app()->request->rawBody;
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$jsonMapper		 = new JsonMapper();
			$jsonObj		 = CJSON::decode($data, false);
			$routeArrList	 = [
				'delhi-jaipur',
				'delhi-shimla',
				'delhi-nainital',
				'delhi-agra',
				'chennai-tirupati',
				'jaipur-ajmer'
			];
			$imageList		 = [
				'delhi-jaipur'		 => 'add1.jpg',
				'delhi-shimla'		 => 'add2.jpg',
				'delhi-nainital'	 => 'add3.jpg',
				'delhi-agra'		 => 'add4.jpg',
				'chennai-tirupati'	 => 'add6.jpg',
				'jaipur-ajmer'		 => 'add8.jpg'
			];
			$routeList		 = Yii::app()->cache->get("getPopularRoutes");
			if ($routeList == false)
			{
				$routeList = Route::model()->getRouteDetailsbyNameList($routeArrList, true, $jsonObj->sort);
				Yii::app()->cache->set("getPopularRoutes", $routeList, 86400, new CacheDependency("Routes"));
			}

			/* @var $response OnewayRoutes */
			$response	 = new \Stub\common\OnewayRoutes();
			$routes		 = $response->setRoutesData($routeList, $imageList);
			$routes		 = Filter::removeNull($routes);
			$returnSet->setStatus(true);
			$returnSet->setData($routes);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function getCabService()
	{
		$returnSet = new ReturnSet();
		try
		{
			$response	 = new \Stub\common\Cab();
			$data		 = $response->mapCategoryServiceClass();
			$returnSet->setData($data);
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function addAddress()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		if (!$data)
		{
			throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}
		Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);
		$transaction = Filter::beginTransaction();
		try
		{
			/* @var $obj \Stub\booking\RouteRequest() */
			$obj = $jsonMapper->map($jsonObj, new \Stub\booking\RouteRequest());

			/** @var Booking $model */
			$model			 = $obj->getModel();
			$bookingModel	 = Booking::model()->findByPk($model->bkg_id);
			$routeModel		 = BookingRoute::addAddresses($bookingModel, $model);
			$arrResult		 = BookingRoute::updateDistance($bookingModel, $routeModel);
			if ($arrResult)
			{
				$bookingModel->updateDistance = $arrResult;
			}
			$success = true;
			$errors	 = CActiveForm::validate($bookingModel, null, false);
			if ($errors != '[]')
			{
				goto handleErrors;
			}

			if ($bookingModel->hasErrors())
			{
				goto handleErrors;
			}
			DBUtil::commitTransaction($transaction);

			$response	 = new \Stub\booking\RouteResponse();
			$response->setData($bookingModel);
			$data		 = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($data);
			Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
			goto result;

			handleErrors:
			$errors = $model->getErrors();
			throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
			Filter::rollbackTransaction($transaction);
		}

		result:
		return $returnSet;
	}

	public function updateDriverDetailsViewed()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$data		 = Yii::app()->request->rawBody; // '{"bookingId":OW001886348}';
		try
		{
			if (!$data)
			{
				throw new Exception('Invalid Request: ', ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$jsonObj	 = json_decode($data, false);
			/* @var $tokenModel AppTokens */
			$tokenModel	 = AppTokens::validateToken($token);
			$model		 = Booking::model()->getBkgIdByBookingId($jsonObj->bookingId);

			if ($model)
			{
				if ($model->bkg_status != 9)
				{
					$success = $model->bkgTrack->updateDriverDetailsViewedFlag();
				}
				else
				{
					$returnSet->setErrors("Booking cancelled");
				}
			}
			else
			{
				$returnSet->setErrors("Invalid BookingID");
			}

			if ($success)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Cancellation charges will now apply if booking is cancelled.");
			}
			else
			{
				$returnSet->setStatus(false);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function getUserAddress()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		try
		{
			if (!$data)
			{
				throw new Exception('Invalid Request: ', ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$jsonObj		 = CJSON::decode($data, false);
			$jsonMapper		 = new JsonMapper();
			$obj			 = $jsonMapper->map($jsonObj, new \Stub\booking\CreateRequest());
			$actualRoutes	 = $obj->routes;
			$cities			 = [];
			$result			 = [];
			$userInfo		 = UserInfo::getInstance();
			$userId			 = $userInfo->userId;
			foreach ($actualRoutes as $v)
			{
				if (!in_array($v->source->code, $cities))
				{
					$result[$v->source->code]	 = BookingRoute::getUserAddressesByCity($v->source->code, $userId);
					$cities[]					 = $v->source->code;
				}
				if (!in_array($v->destination->code, $cities))
				{
					$result[$v->destination->code]	 = BookingRoute::getUserAddressesByCity($v->destination->code, $userId);
					$cities[]						 = $v->destination->code;
				}
			}
			$returnSet->setStatus(true);
			$returnSet->setData($result);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			Logger::exception($ex);
		}
		return $returnSet;
	}

	public function getBanner()
	{
		$returnSet = new ReturnSet();
		try
		{
			$baseURL = Yii::app()->params['fullBaseURL'];
			$banner	 = [
				["title" => "FlashSale", "description" => "", "image" => $baseURL . "/images/reviews/02.png", "link" => $baseURL . "/flashsale?app=1"],
				["title" => "Voucher", "description" => "", "image" => $baseURL . "/images/reviews/06.png", "link" => ""],
				["title" => "Refer a friend", "description" => "", "image" => $baseURL . "/images/reviews/04.png", "link" => ""],
				["title" => "Courtesy Notice", "description" => "For the safety of our patrons and our partners, we are following Govt. guidelines for lockdown. All future bookings will remain subject to cancellation due to the pandemic.", "image" => $baseURL . "/images/covid_19.png", "link" => ""]
			];

			$returnSet->setStatus(true);
			$returnSet->setData($banner);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
			Logger::exception($ex);
		}
		return $returnSet;
	}

	public function cancelWarningMsg()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$data		 = Yii::app()->request->rawBody; // '{"bookingId":1196384,"reason":"abc","reasonId":"1"}';
		try
		{
			if (!$data)
			{
				throw new Exception('Invalid Request: ', ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$jsonObj		 = json_decode($data, false);
			/* @var $tokenModel AppTokens */
			$tokenModel		 = AppTokens::validateToken($token);
			$model			 = Booking::model()->findByPk($jsonObj->bookingId);
			$tripTimeDiff	 = Filter::getTimeDiff($model->bkg_pickup_date);
			$bkgAmount		 = $model->bkgInvoice->bkg_total_amount;
			$advanceAmt		 = PaymentGateway::model()->getTotalAdvance($model->bkg_id);
			$totalAdvance	 = ($advanceAmt != NULL) ? $advanceAmt : 0;
			$rule			 = 6;
			//$arrRules			 = BookingPref::model()->getCancelChargeRule($rule);
			//$cancellationCharge	 = BookingPref::model()->calculateCancellationCharge($arrRules, $bkgAmount, $tripTimeDiff, $totalAdvance, 23, '', false);
			//$cancelCharge		 = ($cancellationCharge != NULL) ? $cancellationCharge : 0;
			$cancelFee		 = CancellationPolicy::initiateRequest($model);
			$cancelCharge	 = $cancelFee->charges;
			$refund			 = $totalAdvance - $cancelCharge;
			$returnSet->setStatus(true);
			$message		 = "Your total advance is ₹" . round($totalAdvance) . " and If you cancel booking, your cancellation fees will be: ₹" . round($cancelCharge) . " and refund amount will be ₹" . round($refund);
			$returnSet->setMessage($message);
		}
		catch (Exception $ex)
		{
			$returnSet = $returnSet->setException($ex);
		}

		return $returnSet;
	}

	public function applyAddon()
	{
		$returnSet = new ReturnSet();
		try
		{
			/* @var $obj Stub\common\Addons */
			$obj = Yii::app()->request->getJSONObject(new Stub\common\Addons());
			Logger::create("Request : " . CJSON::encode($obj), CLogger::LEVEL_INFO);

			$bookingId	 = $obj->bookingId;
			$addonId	 = $obj->addonId;
			$walletBal	 = $obj->content->wallet;
			$gozoCoins	 = $obj->content->gozoCoins;
			$promoCode	 = $obj->content->promo->code;
			$eventType	 = $obj->content->eventType;

			$userId		 = UserInfo::getUserId();
			$bkgModel	 = Booking::model()->findByPk($bookingId);
			if ($bkgModel != "")
			{
				$bkgModel->bkgInvoice->useAddon($addonId, 1);
				BookingInvoice::evaluatePromoCoins($bkgModel, $eventType, $gozoCoins, $promoCode, true);
				$message	 = $obj->getMessage($bkgModel->bkgInvoice, $addonId);
				$response	 = new Stub\common\Addons();
				$response->setData($bkgModel->bkgInvoice, $eventType);

				$returnSet->setStatus(true);
				$returnSet->setData($response);
				$returnSet->setMessage($message);
				Logger::create("Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function notifyGNowVendor()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		if (!$data)
		{
			throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}
		Logger::trace("Request : " . $data);

		$jsonObj = CJSON::decode($data, false);
		try
		{
			$bkgId = $jsonObj->bkgId;

			$bkgModel = Booking::model()->findByPk($bkgId);
			if (!$bkgModel)
			{
				throw new CHttpException(400, 'This booking does not exist.');
			}

//			$hash = Yii::app()->shortHash->hash($bkgId);
//			if ($hash != $hashval)
//			{
//				throw new CHttpException(400, 'Invalid data');
//			}
			if ($bkgModel->bkgPref->bkg_is_gozonow != 1)
			{
				throw new CHttpException(400, 'This booking does not comes under Gozo Now scenario.');
			}
			$dataexist = BookingVendorRequest::getPreferredVendorbyBooking($bkgModel->bkg_bcb_id);

			if ($dataexist)
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage('An offer is selected');
			}

			$result = Booking::getGNowTimerData($bkgModel);
			$returnSet->setStatus(true);
			$returnSet->setData($result);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
			Logger::exception($e);
		}

		result:
		return $returnSet;
	}

	public function getGNowOfferList()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		if (!$data)
		{
			throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}
		Logger::trace("Request : " . $data);

		$jsonObj = CJSON::decode($data, false);
		try
		{
			$bkgId	 = $jsonObj->id;
			$result	 = Booking::getGNowOfferList($bkgId, 'list', 'list');

			$responseData = new \Stub\booking\GNowOffers();
			$responseData->setData($result);

			if (isset($result['timerStat']['message']))
			{
				$returnSet->setMessage($result['timerStat']['message']);
			}
			else
			{
				$returnSet->setStatus(true);
				$returnSet->setData($responseData);
			}
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
			$returnSet->setMessage($e->getMessage());
			Logger::exception($e);
		}

		result:
		return $returnSet;
	}

	public function processGNowbidAccept()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		if (!$data)
		{
			throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}
		Logger::trace("Request : " . $data);

		$jsonObj = CJSON::decode($data, false);

		if (!Config::checkGozoNowEnabled())
		{
			throw new CHttpException(400, 'Cannot proceed');
		}
		$bvrid		 = $jsonObj->bidId;
		$bookingId	 = $jsonObj->id;
		$hash		 = $jsonObj->hash;

		/** @var BookingVendorRequest $bvrModel */
		$bvrModel	 = BookingVendorRequest::model()->findByPk($bvrid);
		$bkgId		 = $bvrModel->bvr_booking_id;
		$bkgModel	 = Booking::model()->findByPk($bkgId);
		$transaction = DBUtil::beginTransaction();

		try
		{
			if (!$bkgModel)
			{
				throw new CHttpException(400, 'Invalid data');
			}
			if (!$bvrModel)
			{
				throw new CHttpException(400, 'Invalid bid data');
			}
			if ($bookingId != $bkgId)
			{
				throw new CHttpException(400, 'Invalid booking data');
			}
			if ($hash != Yii::app()->shortHash->hash($bkgId))
			{
//				throw new CHttpException(400, 'Invalid booking data');
			}

			/** @var Booking $bkgModel */
			if ($bkgModel->bkg_status != 2)
			{
				throw new CHttpException(401, 'Already processed');
			}
			if ($bkgModel->bkgPref->bkg_is_gozonow != 1)
			{
				throw new CHttpException(400, 'This booking does not comes under Gozo Now scenario.');
			}
			$dataexist = BookingVendorRequest::getPreferredVendorbyBooking($bkgModel->bkg_bcb_id);
			if (!$dataexist)
			{
				$success = $bvrModel->updatePreferredVendor();
				$model	 = BookingSub::processForGNowFromVendorAmount($bkgId, $bvrModel->bvr_bid_amount, $bvrModel->bvr_vendor_id);
				if (!$model)
				{
					throw new CHttpException(ReturnSet::ERROR_REQUEST_CANNOT_PROCEED, "Some error occured while processing the booking");
				}
			}
			else
			{
				throw new CHttpException(401, 'Already selected');
			}
			DBUtil::commitTransaction($transaction);

			$dropAddressRequired = true;
			$tStateName			 = $bkgModel->bkgToCity->ctyState->stt_name;
			$tCityName			 = $bkgModel->bkgToCity->cty_name;
			/** @var Booking $bkgModel */
			if ($bkgModel->bkg_drop_address != '' && (( $bkgModel->bkg_drop_address != $tCityName . ', ' . $tStateName && $tCityName != $bkgModel->bkg_drop_address ) || $bkgModel->bkgToCity->cty_is_airport == 1))
			{
				$dropAddressRequired = false;
			}
			$returnSet->setStatus(true);
			$returnSet->setData(['isDropAddressRequired' => $dropAddressRequired]);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
			$returnSet->setMessage($e->getMessage());
			Logger::exception($e);
		}
		return $returnSet;
	}

	public function processGNowbidReject()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		if (!$data)
		{
			throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}
		Logger::trace("Request : " . $data);

		$jsonObj = CJSON::decode($data, false);

		if (!Config::checkGozoNowEnabled())
		{
			throw new CHttpException(400, 'Cannot proceed');
		}
		$bvrid		 = $jsonObj->bidId;
		$bkgId		 = $jsonObj->id;
		$hash		 = $jsonObj->hash;
		$bkgModel	 = Booking::model()->findByPk($bkgId);
		try
		{
//			if ($hash != Yii::app()->shortHash->hash($bkgId))
//			{
//				throw new CHttpException(400, 'Invalid booking data');
//			}

			if (!$bkgModel)
			{
				throw new Exception('Invalid Data : ', ReturnSet::ERROR_INVALID_DATA);
			}
			$returnSet = $bkgModel->proceedGNowOfferDeny($bvrid);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function cancelGnow()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		if (!$data)
		{
			throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}
		$jsonObj = CJSON::decode($data, false);

		if (!Config::checkGozoNowEnabled())
		{
			throw new CHttpException(400, 'Cannot proceed');
		}

		$bkgId	 = $jsonObj->id;
		$hash	 = $jsonObj->hash;

		$hashval = Yii::app()->shortHash->hash($bkgId);

		try
		{
//			if ($hash != $hashval)
//			{
//				throw new CHttpException(400, 'Invalid data');
//			}
			$success = Booking::cancelGNow($bkgId);
			$returnSet->setStatus($success);
			if ($success)
			{
				$returnSet->setMessage('The request is cancelled. Contact customer care for more details');
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	public function getFinalDetails()
	{
		$returnSet = new ReturnSet();
		try
		{
			$rdata = Yii::app()->request->rawBody;
			if (!$rdata)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$data	 = CJSON::decode($rdata, true);
			$bkgId	 = $data['id'];

			$userId = UserInfo::getEntityId();

			$model = Booking::model()->findByPk($bkgId);
			if ($model)
			{
				$response = new \Stub\common\Booking();
				$response->setDetails($model);

				$rtArr = json_decode($model->bkg_route_city_names);

				$response->routeName = implode(' - ', $rtArr);
				$returnSet->setData($response);
				$returnSet->setStatus(true);
			}
			else
			{
				$returnSet->setErrors("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function updateBilling()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$data	 = Yii::app()->request->rawBody;
			$jsonObj = CJSON::decode($data, false);
			if ($data == null)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$obj		 = $jsonMapper->map($jsonObj, new \Beans\Booking());

			Logger::info("Request : " . json_encode($obj));

			$model = \Beans\booking\BillingDetails::getModel($obj);

			if ($model == false)
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}

			if (($model->bkg_bill_address == '' || $model->bkg_bill_address == null) && $jsonObj->billing->address->address != '')
			{
				$model->bkg_bill_address = $jsonObj->billing->address->address;
			}
			$model->bkg_user_last_updated_on = new CDbExpression('NOW()');
			if (!$model->save())
			{
				throw new Exception("details not saved", ReturnSet::ERROR_VALIDATION);
			}

			$message = "Billing details successfully updated.";
			$returnSet->setStatus(true);
			$returnSet->setMessage($message);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function reportIssueCategory()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$data	 = Yii::app()->request->rawBody;
			$jsonObj = CJSON::decode($data, false);
			if ($data == null)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$obj		 = $jsonMapper->map($jsonObj, new \Beans\booking\ReportIssue());
			Logger::info("Request : " . json_encode($obj));
			$model		 = \Beans\booking\ReportIssue::setData($obj);
			$isShowIssue = \ReportIssue::checkStatusToShowIssue($model->bkg_id);
			if (!$isShowIssue)
			{
				throw new Exception("Issue reported only for Ongoing Trip for this booking.", ReturnSet::ERROR_INVALID_DATA);
			}

			$data = \Beans\booking\ReportIssue::getData();
			if (count($data) == 0)
			{
				$returnSet->setErrors("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			else
			{
				$returnSet->setData($data);
				$returnSet->setStatus(true);
			}
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function postIssue()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$data	 = Yii::app()->request->rawBody;
			$jsonObj = CJSON::decode($data, false);
			if ($data == null)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$obj		 = $jsonMapper->map($jsonObj, new \Beans\booking\ReportIssue());
			Logger::info("Request : " . json_encode($obj));
			$issueModel	 = \Beans\booking\ReportIssue::setModelData($obj);

			$id			 = $obj->booking->id;
			$bkgModel	 = \Booking::model()->findByPk($id);
			if (!$bkgModel)
			{
				throw new Exception("Invalid booking", ReturnSet::ERROR_VALIDATION);
			}

			$userId		 = \UserInfo::getUserId();
			$userType	 = \UserInfo::TYPE_CONSUMER;

			$resSet = \ReportIssue::postAnIssue($bkgModel->bkg_id, $issueModel->rpi_id, $issueModel->rpi_type, $issueModel->report_issue_desc, $userId, $userType);

			if ($resSet->getStatus() == false)
			{
				goto skipPostIssue;
			}
			$returnSet->setStatus($resSet->getStatus());
			$returnSet->setMessage("Issue reported successfully. You will receive a call back shortly.");
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		skipPostIssue:
		return $returnSet;
	}


   public function reschedule()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data	 = Yii::app()->request->rawBody;
			$jsonObj = CJSON::decode($data, false);
			$jsonMapper	 = new JsonMapper();
			$obj		 = $jsonMapper->map($jsonObj, new \Beans\booking\Reschedule());
			$model		 = \Beans\booking\Reschedule::setBookingData($obj);
			if (!$model)
			{
				throw new Exception("Invalid booking", ReturnSet::ERROR_INVALID_DATA);
			}
			if ($model->bkgInvoice->bkg_advance_amount > 0)
			{
				$cancelObj			 = CancellationPolicy::initiateRequest($model);
				$rescheduleCharge	 = $model->bkgInvoice->calculateRescheduleCharge($cancelObj->charges, $obj->pickUpDate);
			}
			$response = \Beans\booking\Reschedule::getData($obj, $rescheduleCharge);
			$returnSet->setData($response);
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}



	public function processReschedule()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data	 = Yii::app()->request->rawBody;
			$jsonObj = CJSON::decode($data, false);
			if ($data == null)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper		 = new JsonMapper();
			$obj			 = $jsonMapper->map($jsonObj, new \Beans\booking\Reschedule());
			$bkgId			 = $obj->booking->id;
			$newPickupDate	 = $obj->pickUpDate;
			$newPickupTime	 = $obj->pickUpTime;
			$isConfirm		 = $obj->isConfirm;
			$model			 = Booking::model()->findByPk($bkgId);
			if (!$model)
			{
				throw new Exception("Invalid booking", ReturnSet::ERROR_VALIDATION);
			}
			$isValidate = $model->validateOnReschedule();
			if (!$isValidate)
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			$bkgPrefModel = BookingPref::model()->findBySql("SELECT bpr_bkg_id FROM `booking_pref` WHERE bpr_rescheduled_from = {$model->bkg_id}");
			if ($bkgPrefModel != '')
			{
				$existBookingModel = Booking::model()->findByPk($bkgPrefModel->bpr_bkg_id);
				if (in_array($existBookingModel->bkg_status, [1, 15]))
				{
					$existBookingModel->initReschedule($model);
					$refBookingId	 = $existBookingModel->bkg_id;
					$newModel		 = $existBookingModel;
					goto skipRescheduleIfExists;
				}
			}

			if ($newPickupDate != '' && $newPickupTime != '')
			{
				$newPickupDate	 = DateTimeFormat::DateToDatePicker($newPickupDate);
				$result			 = $model->reschedule($newPickupDate, $newPickupTime);
				if (!$result)
				{
					throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
				if ($result instanceof Booking)
				{
					$newModel = $result;
					goto skipConfirmReschedule;
				}

				skipRescheduleIfExists:

				if ($isConfirm)
				{
					$newPickupDate	 = DateTimeFormat::DateToDatePicker($newPickupDate);
					$result			 = $model->confirmReschedule($newPickupDate, $newPickupTime);
					$newBkgId		 = $result->getData()['bkgId'];
					$payUrl			 = $result->getData()['payUrl'];
					$newModel		 = Booking::model()->findByPk($newBkgId);
				}

				skipConfirmReschedule:

				$params			 = BookingPref::getAttrParamsReschedule($model, $newModel);
				$params['refId'] = $refBookingId;

				$response = \Beans\booking\Reschedule::getModelData($obj, $newModel->bkgInvoice, $params);
				$returnSet->setStatus(true);
				$returnSet->setData($response);
				if ($payUrl == null && $newBkgId > 0)
				{
					$message = "Booking rescheduled successfully. New Booking ID is " . $newModel->bkg_booking_id;
					$returnSet->setMessage($message);
				}
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function confirmRescheduleV1()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data	 = Yii::app()->request->rawBody;
			$jsonObj = CJSON::decode($data, false);
			if ($data == null)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper		 = new JsonMapper();
			$obj			 = $jsonMapper->map($jsonObj, new \Beans\booking\Reschedule());
			//$model			 = \Beans\booking\Reschedule::setBookingData($obj);
			$bkgId			 = $obj->booking->id;
			$newPickupDate	 = $obj->pickUpDate;
			$newPickupTime	 = $obj->pickUpTime;
			$model			 = Booking::model()->findByPk($bkgId);
			$newPickupDate	 = DateTimeFormat::DateToDatePicker($newPickupDate);
			if (!$model)
			{
				throw new Exception("Invalid booking", ReturnSet::ERROR_VALIDATION);
			}
			$isValidate = $model->validateOnReschedule();
			if (!$isValidate)
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			$bkgPrefModel = BookingPref::model()->findBySql("SELECT bpr_bkg_id FROM `booking_pref` WHERE bpr_rescheduled_from = {$model->bkg_id}");
			if ($bkgPrefModel != '')
			{
				$existBookingModel = Booking::model()->findByPk($bkgPrefModel->bpr_bkg_id);
				if (in_array($existBookingModel->bkg_status, [1, 15]))
				{
					$existBookingModel->initReschedule($model);
				}

				$params = BookingPref::getAttrParamsReschedule($model, $existBookingModel);
				goto skipReschedule;
			}
			
			// Quote for Reschedule Booking
			$result			 = $model->reschedule($newPickupDate, $newPickupTime);
			if (!$result)
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			if ($result instanceof Booking)
			{
				$bkgModel = $result;
			}
			$params = BookingPref::getAttrParamsReschedule($model, $bkgModel);
	

			skipReschedule:

			// Confirm for Reschedule Booking
			$result			 = $model->confirmReschedule($newPickupDate, $newPickupTime);
			$newBkgId		 = $result->getData()['bkgId'];
			$payUrl			 = $result->getData()['payUrl'];

			$newModel = Booking::model()->findByPk($newBkgId);
			if (!$newModel)
			{
				throw new Exception("Invalid Booking", ReturnSet::ERROR_VALIDATION);
			}

			$response = \Beans\booking\Reschedule::getModelData($obj, $newModel->bkgInvoice, $params);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
			if ($payUrl == null)
			{
				$message = "Booking rescheduled successfully. New Booking ID is " . $newModel->bkg_booking_id;
				$returnSet->setMessage($message);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

}
