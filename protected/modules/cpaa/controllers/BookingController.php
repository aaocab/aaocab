<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class BookingController extends Controller
{

	public $layout = 'admin1';
	public $email_receipient, $pageTitle1, $pageDesc;

	//public $parner_salt = 'CPART123SALT';
	public function filters()
	{
		return array(
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

	public function accessRules()
	{
		return array(
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array(''),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('ledgerbooking', 'list', 'cp_cab_list_latest', 'vendorAssignList', 'is_channel_partner', 'cp_credit_balence', 'cp_latest_credit', 'cp_transaction_details', 'cp_show_booking_amount', 'ajaxverify', 'index', 'iew', 'vendorAssignList', 'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array(),
				'users'		 => array('admin'),
			),
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

		$this->onRest('post.filter.req.auth.user', function($validation)
		{
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array('/cp_cab_list_latest', '/vendorAssignList', '/cp_make_booking', '/cp_make_recharge', '/cp_credit_balence', '/cp_latest_credit', '/cp_transaction_details', '/cp_show_booking_amount', '/agentAcountHistory', '/bookingHistory');

			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		/*
		 * Showing cablist according to route...
		 */
		$this->onRest('req.post.cp_cab_list_latest.render', function()
		{
			//$header  = getallheaders();
			//echo $_SERVER['HTTP_OWNER_ID'] 
			// this agentValidation function should be used for every agent api checksum validation.
			//$this->agentValidation($header);
			//$partnerId              =  $header['X-Rest-Agntid'];
			$header	 = array();
			$header	 = $_SERVER;
			$this->agentValidation($header);

			$partnerId		 = $header['HTTP_X_REST_AGNTID'];
			$triptype		 = Yii::app()->request->getParam('trip_type');
			$data1			 = Yii::app()->request->getParam('data');
			$data			 = json_decode($data1);
			$count1			 = count($data);
			$route			 = [];
			$leadRouteArr	 = [];
			$bkgInvoice		 = new BookingInvoice();
			foreach ($data as $key => $val)
			{
				$routeModel							 = new BookingRoute();
				$routeModel->brt_from_city_id		 = $val->pickup_city;
				$routeModel->brt_to_city_id			 = $val->drop_city;
				$routeModel->brt_pickup_datetime	 = date('Y-m-d', strtotime($val->date)) . ' ' . date('H:i:s', strtotime($val->time));
				$routeModel->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($val->date);
				$routeModel->brt_pickup_date_time	 = date('h:i A', strtotime($val->date));
				if ($key == 1)
				{
					$estimatedPickuptime				 = Route::model()->getRoundtripEstimatedPickupbyReturnDateTime($routeModel->brt_from_city_id, $routeModel->brt_to_city_id, $val->date);
					$routeModel->brt_pickup_datetime	 = $estimatedPickuptime;
					$routeModel->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($estimatedPickuptime);
					$routeModel->brt_pickup_date_time	 = date('h:i A', strtotime($estimatedPickuptime));
				}
				$routeModel->brt_to_location	 = $val->drop_address;
				$routeModel->brt_from_location	 = $val->pickup_address;
				$routeModel->brt_to_pincode		 = $val->drop_pincode;
				$routeModel->brt_from_pincode	 = $val->pickup_pincode;
				$leadRouteArr[]					 = array_filter($routeModel->attributes);
				$route[]						 = $routeModel;
			}
			$btmodel						 = new BookingTemp();
			$btmodel->bookingRoutes			 = $route;
			$leadDataArr					 = CJSON::encode($leadRouteArr);
			$pickupDate						 = $data[0]->pickupdate . ' ' . $data[0]->time;
			$btmodel->bkg_route_data		 = $leadDataArr;
			$btmodel->bkg_from_city_id		 = $data[0]->pickup_city;
			$btmodel->bkg_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($data[0]->date);
			$btmodel->bkg_pickup_date_time	 = date('h:i:s', strtotime($data[0]->date));
			$btmodel->bkg_booking_type		 = $triptype;
			$btmodel->bkg_pickup_date		 = date('Y-m-d', strtotime($pickupDate)) . ' ' . date('H:i:s', strtotime($pickupDate));
			$btmodel->bkg_create_date		 = date("Y-m-d H:i:s");
			if ($btmodel->bkg_booking_type == 4)
			{
				$btmodel->bkg_transfer_type = $transfertype;
			}
			$btmodel->bkg_to_city_id = $data[$count1 - 1]->drop_city;
			$btmodel->bkg_platform	 = Booking::Platform_App;
			if ($btmodel->bkg_id == "")
			{
				$btmodel->bkg_id = null;
			}
			$user_id = (Yii::app()->user->getId() > 0) ? Yii::app()->user->getId() : '';
			if ($user_id)
			{
				$btmodel->bkg_user_id	 = $user_id;
				$usrmodel				 = Users::model()->findByPk($user_id);
				if ($is_corporate)
				{
					$btmodel->bkg_agent_id = $usrmodel->usr_corporate_id;
				}
			}
			$transaction	 = Yii::app()->db->beginTransaction();
			$errorMessages	 = '';
			$success		 = true;
			if ($success)
			{
				$quote				 = new Quote();
				$quote->routes		 = $route;
				$quote->tripType	 = $triptype;
				$quote->partnerId	 = $partnerId;
				$quote->quoteDate	 = $btmodel->bkg_create_date;
				$quote->pickupDate	 = $btmodel->bkg_pickup_date;  
				$quote->setCabTypeArr();
                Quote::$updateCounter = true;
				$resultQuote		 = $quote->getQuote();

				//$masterVehicles	 = VehicleTypes::model()->getMasterCarType();
				$masterVehicles = SvcClassVhcCat::model()->getVctSvcList('list');
				$agtType		 = Agents::model()->findByPk($partnerId)->agt_type;
				$routeRates		 = [];
				foreach ($resultQuote as $key => $value)
				{
					$routeRates								 = $value->routeRates;
					$bkgInvoice->bkg_night_pickup_included	 = $routeRates->isNightPickupIncluded;
					$bkgInvoice->bkg_night_drop_included	 = $routeRates->isNightDropIncluded;

					if ($agtType == 0 || $agtType == 1)
					{
						$arrQuote			 = Agents::model()->getBaseDiscFare($routeRates, $agtType, $partnerId);
						$value->routeRates	 = $arrQuote;
					}
					if (!in_array($key, array_keys($masterVehicles)))
					{
						unset($resultQuote[$key]);
					}
					if ($value->success == '')
					{
						$value->success = 1;
					}
				}
				$resultQuotMap	 = Booking::model()->mapQuotecablistNew($resultQuote);
				$resultCab		 = $resultQuotMap['cabList'];
				$cab_List		 = array();
				foreach ($resultCab as $key => $value)
				{
					$cab_List[] = $value;
				}
				$path_Route									 = "";
				$path_Route									 = implode('-', $resultQuotMap['routeData']['routeDesc']);
				$resultQuotMap['routeData']['route']		 = $path_Route;
				$resultQuotMap['routeData']['startTripDate'] = $data[0]->pickupdate;
				$resultQuotMap['routeData']['time']			 = $data[0]->time;
				$errors										 = [];

				array_walk_recursive($cab_List, function (&$value)
				{
					$value = (string) $value;
				});
				array_walk_recursive($resultQuotMap['routeData'], function (&$value)
				{
					$value = (string) $value;
				});

				$result = ['cabList' => $cab_List, 'routeData' => $resultQuotMap['routeData'], 'km' => $resultQuote[1]->routeDistance->tripDistance];
				Logger::create("Get Quote data: " . json_encode($result));
				
			}

			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'		 => $success,
							'errors'		 => $errors,
							'errorMessages'	 => $errorMessages,
							'data'			 => $result,
							'night_trip'	 => $bkgInvoice->bkg_night_pickup_included
						)
			]);

			//coding end here		  
		});

		/*
		 * Create New Booking...
		 */
		$this->onRest('req.post.cp_make_booking.render', function()
		{
			$header		 = array();
			$header		 = $_SERVER;
			$this->agentValidation($header);
			$partnerId	 = $header['HTTP_X_REST_AGNTID'];
			$user_id	 = $header['HTTP_X_REST_UID'];
			$userInfo	 = UserInfo::getInstance();
			$route1		 = Yii::app()->request->getParam('route');
			$route		 = CJSON::decode($route1); //show route details
			$user_data1	 = Yii::app()->request->getParam('data'); // show user details
			$user_data	 = CJSON::decode($user_data1);
			$error1		 = 0;
			$error2		 = 0;

			if (array_key_exists("cab_type_id", $user_data) && trim($user_data['cab_type_id']) != "" && array_key_exists("country_code", $user_data) && trim($user_data['country_code']) != "" && array_key_exists("trip_type", $user_data) && trim($user_data['trip_type']) != "" && array_key_exists("payBy", $user_data) && trim($user_data['payBy']) != "")
			{
				$error1 = 0;
			}
			else
			{
				$error1 = 1;
			}
			if ($user_data['trip_type'] == 2)
			{
				foreach ($route as $key => $bval)
				{
					if (array_key_exists("date", $bval) && trim($bval['date']) != ""
							//&& array_key_exists("drop_address",$bval) && trim($bval['drop_address']) != ""
							&& array_key_exists("drop_city", $bval) && trim($bval['drop_city']) != ""
							//&& array_key_exists("pickup_address",$bval) && trim($bval['pickup_address']) != ""
							&& array_key_exists("pickup_city", $bval) && trim($bval['pickup_city']) != "" && array_key_exists("pickupdate", $bval) && trim($bval['pickupdate']) != "" && array_key_exists("time", $bval) && trim($bval['time']) != "")
					{
						$error2 = 0;
					}
					else
					{
						$error2 = 1;
						break;
					}
				}
			}

			/*if ($user_data['trip_type'] == 1)
			{
				if (array_key_exists("date", $route[0]) && trim($route[0]['date']) != ""  && array_key_exists("drop_city", $route[0]) && trim($route[0]['drop_city']) != "" && array_key_exists("pickup_address", $route[0]) && trim($route[0]['pickup_address']) != "" && array_key_exists("pickup_city", $route[0]) && trim($route[0]['pickup_city']) != "" && array_key_exists("pickupdate", $route[0]) && trim($route[0]['pickupdate']) != "" && array_key_exists("time", $route[0]) && trim($route[0]['time']) != "")
				{
					$error2 = 0;
				}
				else
				{
					$error2 = 1;
				}
			}*/

			if ($error1 == 1 )
			{
				
				$success = false;
				$message = "Validation Error Occurred.";
				goto response;
			}
			if($route[0]['pickup_address']=="")
			{
				
				$route[0]['pickup_address'] =$route[0]['pickup_point'];
			}
			//$bkg_drop_address  = ($user_data['trip_type']==2?$route[0]['pickup_address']:$route[0]['drop_address']);
			if ($user_data['trip_type'] == 2)
			{
				if ($route[1]['drop_address'] !== '')
				{
					$bkg_drop_address = $route[1]['drop_address'];
				}
				else
				{
					$bkg_drop_address = $route[0]['pickup_address'];
				}
			}
			else
			{
				$bkg_drop_address = $route[0]['drop_address'];
			}
			
			if($bkg_drop_address=="")
			{
				$bkg_drop_address =$route[0]['drop_point'];
				$route[0]['drop_address'] =$route[0]['drop_point'];
			}
			$pickupdate	 = date('Y-m-d', strtotime($route[0]['pickupdate']));
			$pickup_time = date('H:i:s', strtotime($route[0]['time']));
			$date_time	 = (string) $pickupdate . ' ' . $pickup_time;
			if ($user_data['trip_type'] == 2)
			{
				$pickupdate			 = date('Y-m-d', strtotime($route[1]['pickupdate']));
				$pickup_time		 = date('H:i:s', strtotime($route[1]['time']));
				$return_date_time	 = (string) $pickupdate . ' ' . $pickup_time;
			}
            
			$preBookData	 = array('bkg_status'				 => 1,
				'bkg_active'				 => 1,
				'bkg_agent_id'				 => $partnerId,
				'bkg_booking_type'			 => $user_data['trip_type'],
				'bkg_from_city_id'			 => $route[0]['pickup_city'],
				'bkg_to_city_id'			 => $route[0]['drop_city'],
				'bkg_vehicle_type_id'		 => $user_data['cab_type_id'],
				'bkg_pickup_date'			 => $date_time,
				'bkg_return_date'			 => $return_date_time,
				'bkg_pickup_address'		 => $route[0]['pickup_address'],
				'bkg_drop_address'			 => $bkg_drop_address,
				'bkg_country_code'			 => $user_data['country_code'],
				'bkg_alt_country_code'		 => '91',
				'bkg_crp_country_code'		 => '91',
				'bkg_phone_verified'		 => 0,
				'bkg_email_verified'		 => 0,
				'bkg_user_fname'			 => $user_data['fname'],
				'bkg_user_lname'			 => $user_data['lname'],
				"bkg_contact_no"			 => $user_data['phone'],
				"bkg_user_email"			 => $user_data['email'],
				"bui_id"					 => null,
				"bui_bkg_id"				 => null,
				"bkg_user_id"				 => null,
				"bkg_alt_contact_no"		 => null,
				"bkg_user_city"				 => null,
				"bkg_user_country"			 => null,
				"bkg_crp_name"				 => null,
				"bkg_crp_email"				 => null,
				"bkg_crp_phone"				 => null,
				"bkg_verifycode_email"		 => null,
				"bkg_verification_code"		 => null,
				"bkg_bill_fullname"			 => null,
				"bkg_bill_contact"			 => null,
				"bkg_bill_email"			 => null,
				"bkg_bill_address"			 => null,
				"bkg_bill_country"			 => null,
				"bkg_bill_state"			 => null,
				"bkg_bill_city"				 => null,
				"bkg_bill_postalcode"		 => null,
				"bkg_user_last_updated_on"	 => null);
			$preRutData[0]	 = array('brt_active'			 => '1',
				'brt_from_city_id'		 => $route[0]['pickup_city'],
				'brt_from_location'		 => $route[0]['pickup_address'],
				'brt_pickup_datetime'	 => $date_time,
				'brt_status'			 => 1,
				'brt_to_city_id'		 => $route[0]['drop_city'],
				'brt_to_location'		 => $route[0]['drop_address']);
			if ($user_data['trip_type'] == 2)
			{
				$pickupDate		 = date('Y-m-d', strtotime($route[1]['pickupdate']));
				$pickupTime		 = date("H:i:s", strtotime($route[1]['time']));
				$dateTime1		 = (string) $pickupDate . ' ' . $pickupTime;
				$preRutData[1]	 = array('brt_active'			 => 1,
					'brt_from_city_id'		 => $route[1]['pickup_city'],
					'brt_from_location'		 => $route[1]['pickup_address'],
					'brt_pickup_datetime'	 => $dateTime1,
					'brt_status'			 => 1,
					'brt_to_city_id'		 => $route[1]['drop_city'],
					'brt_to_location'		 => $route[1]['drop_address']);
			}
			$data['bkg_booking_type']	 = $user_data['trip_type'];
			//$data['isRechargeAccount']	 = 0;
			$data['payBy']				 = $user_data['payBy'];
			$data['rechargeAmount']		 = 1;
			$data['rechargeMethod']		 = "";
			$data['preBookData']		 = $preBookData;
			$data['preRutData']			 = $preRutData;
			$data['bkg_booking_type']	 = $data['preBookData']['bkg_booking_type'];
			($data['payBy'] == 1 ? ($data['payByAgent']			 = 1) : ($data['payByCustomer']		 = 1));

			// recharge amount start here
			$result					 = AccountTransDetails::accountTotalSummary($partnerId);
			$rechargeAmount			 = abs(($result['totAmount']));
			$data['rechargeAmount']	 = $rechargeAmount;
			$data['rechargeMethod']	 = null;
			$preRutData				 = $data['preRutData'];
			$res_data				 = [];
			$message				 = "";
			$is_credit_available	 = 0;
			$model					 = new Booking();
			$bkgInvoice				 = new BookingInvoice();
			$bkgUser				 = new BookingUser();
			$bkgAddInfo				 = new BookingAddInfo();
			$bkgTrail				 = new BookingTrail();
			$bkgTrack				 = new BookingTrack();
			$bkgPref				 = new BookingPref();
            $bkgPriceFactor          = new BookingPriceFactor();
			$model->attributes		 = array_filter($data['preBookData']);
			$model->bkg_pickup_date	 = $data['preBookData']['bkg_pickup_date'];
			$bkgUser->attributes	 = array_filter($data['preBookData']);
			$model->bkg_agent_id	 = $partnerId;
			$model->preData			 = ['preBookData' => $data['preBookData'], 'preRutData' => array_filter($preRutData)];
			foreach ($preRutData as $key => $route)
			{
				$bookingRoute = new BookingRoute();
				if ($key == 1)
				{
					$estimatedPickuptime			 = Route::model()->getRoundtripEstimatedPickupbyReturnDateTime($route['brt_from_city_id'], $route['brt_to_city_id'], $route['brt_pickup_datetime']);
					$route['brt_pickup_datetime']	 = $estimatedPickuptime;
				}
				$bookingRoute->attributes	 = $route;
				$bookingRoute->with('brtFromCity', 'brtToCity');
				$bookingRoutesObj[]			 = $bookingRoute;
			}
			//$carType							 = VehicleTypes::model()->getVehicleTypeById($model->bkg_vehicle_type_id);	
			$carType							 = $model->bkgSvcClassVhcCat->scv_vct_id;
			$quote								 = new Quote();
			$quote->routes						 = $bookingRoutesObj;
			$quote->tripType					 = $model->bkg_booking_type;
			$quote->partnerId					 = $partnerId;
			$quote->quoteDate					 = $model->bkg_create_date;
			$quote->pickupDate					 = $model->bkg_pickup_date;
			$quote->returnDate					 = $model->bkg_return_date;
			$quote->sourceQuotation				 = Quote::Platform_Partner_Spot;
			$quote->isB2Cbooking				 = false;
			$quote->setCabTypeArr();
			$qt									 = $quote->getQuote($carType);
			$quoteData							 = $qt[$carType];
			$routeRates							 = $quoteData->routeRates;
			$model->bkg_agent_id				 = $partnerId;
			$agtModel							 = Agents::model()->findByPk($model->bkg_agent_id);
			$arrQuote							 = Agents::model()->getBaseDiscFare($quoteData->routeRates, $agtModel->agt_type, $model->bkg_agent_id);
			$routeRates							 = $arrQuote; //$quoteData->routeRates;
			$routeDistance						 = $quoteData->routeDistance;
			$routeDuration						 = $quoteData->routeDuration;
			$amount								 = $routeRates->baseAmount;
			$bkgInvoice->bkg_gozo_base_amount	 = $amount;
			$bkgInvoice->bkg_base_amount		 = $routeRates->baseAmount;
			$model->bkg_trip_distance			 = $routeDistance->tripDistance; // $routeData['minimumChargeableDistance'];
			$model->bkg_trip_duration			 = $routeDuration->totalMinutes;
			$bkgInvoice->bkg_rate_per_km_extra		 = round($routeRates->ratePerKM);
			$bkgInvoice->bkg_rate_per_km			 = round($routeRates->costPerKM);
			$bkgInvoice->bkg_driver_allowance_amount = $routeRates->driverAllowance;
			$bkgInvoice->bkg_chargeable_distance	 = $routeDistance->quotedDistance;
			$bkgTrack->bkg_garage_time				 = $routeDuration->totalMinutes + $routeDuration->garageTimeEnd + $routeDuration->garageTimeStart;
			$bkgInvoice->bkg_vendor_amount			 = round($routeRates->vendorAmount);
			$bkgInvoice->bkg_vendor_amount;
			$bkgInvoice->bkg_is_toll_tax_included	 = $routeRates->isTollIncluded | 0; //$cabData['tolltax'];
			$bkgInvoice->bkg_is_state_tax_included	 = $routeRates->isStateTaxIncluded | 0; //$cabData['statetax'];
			$bkgInvoice->bkg_toll_tax				 = $routeRates->tollTaxAmount; //$cabData['toll_tax'];
			$bkgInvoice->bkg_state_tax				 = $routeRates->stateTax; //$cabData['state_tax'];
			$bkgInvoice->bkg_is_airport_fee_included = $routeRates->isAirportEntryFeeIncluded;
            $bkgInvoice->bkg_airport_entry_fee       = $routeRates->airportEntryFee;
			$bkgInvoice->bkg_quoted_vendor_amount	 = round($routeRates->vendorAmount);
			if ($bkgAddInfo->bkg_spl_req_carrier == 1)
			{
				$bkgInvoice->bkg_additional_charge			 = 150;
				$bkgInvoice->bkg_additional_charge_remark	 = 'Carrier Requested for Rs.150';
			}
			else
			{
				$bkgInvoice->bkg_additional_charge			 = 0;
				$bkgInvoice->bkg_additional_charge_remark	 = '';
			}
            if(SvcClassVhcCat::getClassById($model->bkg_vehicle_type_id)== ServiceClass::CLASS_ECONOMIC){
                $bkgPref->bkg_cng_allowed = 1;
            }
			$bkgPref->bkg_send_email = 1;
			$bkgPref->bkg_send_sms	 = 1;
			if ($model->bkg_agent_id != '')
			{
				$agtModel = Agents::model()->findByPk($model->bkg_agent_id);
				if ($agtModel->agt_city == 30706)
				{
					$bkgInvoice->bkg_cgst	 = Yii::app()->params['cgst'];
					$bkgInvoice->bkg_sgst	 = Yii::app()->params['sgst'];
					$bkgInvoice->bkg_igst	 = 0;
				}
				else
				{
					$bkgInvoice->bkg_igst	 = Yii::app()->params['igst'];
					$bkgInvoice->bkg_cgst	 = 0;
					$bkgInvoice->bkg_sgst	 = 0;
				}
			}
			if ($amount > 0)
			{
				$bkgInvoice->calculateConvenienceFee(0);
				$bkgInvoice->populateAmount(true, false, true, true, $model->bkg_agent_id);
			}
			//validate recharge amount			
			//$isRechargeAccount = AccountTransDetails::model()->checkCreditLimit($model->bkg_agent_id, '', '', $bkgInvoice->bkg_total_amount, '', 3, false);

			/*
			  ///////////////////////
			  $isRechargeAccount = 0;
			  $data['rechargeAmount']=100000;
			  ///////////////////////
			 */

            
			//if ($isRechargeAccount && $data['payBy'] == 1 && ( $data['rechargeAmount'] < $bkgInvoice->bkg_total_amount || !isset($data['rechargeMethod']) ))
			if ($data['payBy'] == 1 && ( $data['rechargeAmount'] < $bkgInvoice->bkg_total_amount))
			{
				$message			 = " Insufficient Credit Balance.";
				$is_credit_available = 1;
				$success			 = false;
				goto response;
			}
			$bkgUser->bkg_user_last_updated_on = new CDbExpression('NOW()');
			if ($user_id == '')
			{
				$userModel = Users::model()->linkUserByEmail($model->bkg_id, Booking::Platform_Agent);
				if ($userModel)
				{
					$user_id = $userModel->user_id;
				}
			}
			if ($user_id)
			{
				$bkgUser->bkg_user_id = $user_id;
			}
			$bkgTrail->bkg_platform = Booking::Platform_Partner_Spot;
			if ($bkgAddInfo->bkg_info_source == '')
			{
				$bkgAddInfo->bkg_info_source = 6;
			}
			$bkgTrail->bkg_user_ip		 = \Filter::getUserIP();
			$cityinfo					 = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
			$bkgUser->bkg_user_city		 = $cityinfo['city'];
			$bkgUser->bkg_user_country	 = $cityinfo['country'];
			$bkgTrail->bkg_user_device	 = UserLog::model()->getDevice();
			$bkgTrail->setPaymentExpiryTime($model->bkg_pickup_date);
			$model->scenario			 = 'cabRate';
			$bkgUser->scenario			 = 'step_cpaaApp';
			//$bkgUser->save();	
			
			if ($model->validate() && !$model->hasErrors())
			{
				$transaction = DBUtil::beginTransaction();
				try
				{
					$bkgUser->bkg_user_last_updated_on	 = new CDbExpression('NOW()');
					$tmodel								 = Terms::model()->getText(1);
					$bkgTrail->bkg_tnc_id				 = $tmodel->tnc_id;
					$bkgTrail->bkg_tnc_time				 = new CDbExpression('NOW()');
					$isRealtedBooking					 = $model->findRelatedBooking($model->bkg_id);
					$model->bkg_status					 = 15;
					$model->bkg_reconfirm_flag			 = 0;
					$model->bkg_booking_id				 = 'temp';
					$bkgTrail->bkg_is_related_booking	 = ($isRealtedBooking) ? 1 : 0;
					$userInfo							 = UserInfo::getInstance();
					$bkgTrail->bkg_create_user_type		 = $userInfo->userType;
					$bkgTrail->bkg_create_user_id		 = $userInfo->userId;
					$bkgTrail->bkg_create_type			 = BookingTrail::CreateType_Self;
					if (!$model->save())
					{
						throw new Exception("Failed to create booking", 101);
					}
					$bkgAddInfo->bad_bkg_id	 = $model->bkg_id;
					$bkgInvoice->biv_bkg_id	 = $model->bkg_id;
					$bkgPref->bpr_bkg_id	 = $model->bkg_id;
					$bkgTrack->btk_bkg_id	 = $model->bkg_id;
					$bkgTrail->btr_bkg_id	 = $model->bkg_id;
					$bkgUser->bui_bkg_id	 = $model->bkg_id;
                    $bkgPriceFactor->bpf_bkg_id = $model->bkg_id;
					$bkgAddInfo->save();
					$bkgInvoice->save();
					$bkgPref->save();
					$bkgTrack->save();
					$bkgTrail->save();
                    $bkgPriceFactor->save();

					//update partner commission and gozoamount
					$model->bkgInvoice->refresh();
					$model->bkgInvoice->calculateDues();
					$model->bkgInvoice->save();

					//$bkgUser->save();
					if ($bkgUser->validate() && !$bkgUser->hasErrors())
					{
						$contactId 					= Contact::createbyBookingUser($bkgUser);
						$bkgUser->bkg_contact_id 	= ($bkgUser->bkg_contact_id) ? $bkgUser->bkg_contact_id : $contactId;
						$bkgUser->save();
					}
					else
					{
						$error[] = $bkgUser->getErrors();
						$message = "Please enter valid phone number or email.";
						$success = false;
						goto response;
					}
					foreach ($bookingRoutesObj as $k => $t)
					{
						$t->scenario	 = "asd";
						$t->attributes	 = $t;
						$t->brt_bkg_id	 = $model->bkg_id;
						$t->save();
					}
					if ($bkgPref == '')
					{
						$bkgPref			 = new BookingPref();
						$bkgPref->bpr_bkg_id = $model->bkg_id;
					}
					$bkgUser->bkg_crp_name			 = $agtModel->agt_copybooking_name;
					$bkgPref->bkg_crp_send_email	 = 1;
					$bkgPref->bkg_crp_send_sms		 = 1;
					$bkgPref->bpr_vnd_recmnd		 = ($user_data['is_recommended_vendor'] > 0) ? 1 : 0;
					$bkgUser->bkg_crp_email			 = $agtModel->agt_copybooking_email;
					$bkgUser->bkg_crp_phone			 = $agtModel->agt_copybooking_phone;
					$bkgUser->bkg_crp_country_code	 = $agtModel->agt_phone_country_code;
					$bkgUser->save();
					$bkgPref->save();
					$arrEvents						 = AgentMessages::model()->getEvents();
					foreach ($arrEvents as $key => $value)
					{
						$bookingMessages = BookingMessages::model()->getByEventAndBookingId($model->bkg_id, $key);
						if ($bookingMessages == '')
						{
							$bookingMessages = new BookingMessages();
							$bookingMessages->getMessageDefaults($model->bkg_agent_id, $key);
							if ($_POST['payBy'] == 2 && $model->bkg_status == 15)
							{
								if ($key == AgentMessages::BOOKING_CONF_WITH_PAYMENTINFO || $key == AgentMessages::PAYMENT_CONFIRM || $key == AgentMessages::PAYMENT_FAILED || $key == AgentMessages::INVOICE || $key == AgentMessages::RATING_AND_REVIEWS || $key == AgentMessages::CANCEL_TRIP)
								{
									$bookingMessages->bkg_agent_email	 = 1;
									$bookingMessages->bkg_agent_sms		 = 1;
									$bookingMessages->bkg_agent_whatsapp		 = 1;
									$bookingMessages->bkg_trvl_email	 = 1;
									$bookingMessages->bkg_trvl_sms		 = 1;
									$bookingMessages->bkg_trvl_whatsapp		 = 1;
								}
								if ($key == AgentMessages::BOOKING_CONF_WITHOUT_PAYMENTINFO || $key == AgentMessages::CAB_ASSIGNED)
								{
									$bookingMessages->bkg_trvl_email = 0;
									$bookingMessages->bkg_trvl_sms	 = 0;
									$bookingMessages->bkg_trvl_whatsapp	 = 0;
								}
							}
							$bookingMessages->bkg_booking_id = $model->bkg_id;
							$bookingMessages->bkg_event_id	 = $key;
							$bookingMessages->save();
						}
					}
					$bookingCab = $model->getBookingCabModel();
					if ($bookingCab == '')
					{
						$bookingCab = new BookingCab();
					}
					$bookingCab->bcb_vendor_amount	 = $bkgInvoice->bkg_vendor_amount;
					$bookingCab->bcb_bkg_id1		 = $model->bkg_id;
					$bookingCab->save();
					BookingRoute::model()->setBookingCabStartEndTime($bookingCab->bcb_id, $bookingCab->bcb_bkg_id1);
					$model->refresh();
					$model->bkg_bcb_id				 = $bookingCab->bcb_id;
					$booking_id						 = $model->generateBookingid($model);
					$model->bkg_booking_id			 = $booking_id;
					$model->save();
					$bkgTrack						 = BookingTrack::model()->sendTripOtp($model->bkg_id, false);
					$bkgTrack->save();
					$processedRoute					 = BookingLog::model()->logRouteProcessed($quoteData, $model->bkg_id);
					$desc							 = "Booking created by Agent - $processedRoute";
					$eventid						 = BookingLog::BOOKING_CREATED;
					BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventid);
                    BookingPriceFactor::model()->getQuotedFactor($model->bkg_id);
					//if ($data['payBy'] == 1 && !$isRechargeAccount && $model->bkg_status == 1)
					if ($data['payBy'] == 1 && $model->bkg_status == 15)
					{
						$bkgInvoice->bkg_corporate_remunerator	 = 2;
						$amount									 = $bkgInvoice->bkg_total_amount | 0;
						$bkgInvoice->save();
						$model->with('bkgPref', 'bkgAgent', 'bkgUserInfo', 'bkgInvoice', 'bkgAddInfo', 'bkgTrack', 'bkgTrail')->updateAdvance($amount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, UserInfo::getInstance(), null, "Partner Wallet Used");
						$model->bkg_status						 = 3;
                        $model->bkg_booking_id	 = Booking::model()->generateBookingid($model);
						$model->bkg_reconfirm_flag				 = 1;
						$model->save();
                        $bcbModel	 = BookingCab::model()->findByPk($model->bkg_bcb_id);
                        $bcbModel->bcb_vendor_id = $user_data['vendor_id'];
                        $bcbModel->save();
						if ($model->bkg_status == 3)
						{
							$bkgTrail->bkg_confirm_user_type = $userInfo->userType;
							$bkgTrail->bkg_confirm_user_id	 = $userInfo->userId;
							$bkgTrail->bkg_confirm_datetime	 = new CDbExpression('NOW()');
							$bkgTrail->bkg_confirm_type		 = BookingTrail::ConfirmType_Self;
							$bkgTrail->save();
							$emailCom						 = new emailWrapper();
							$emailCom->gotBookingemailCpaa($model->bkgUserInfo, $model->bkg_id, UserInfo::TYPE_SYSTEM, $model->bkg_agent_id);
							$emailCom->gotBookingAgentUser($model->bkg_id);

							$msgCom = new smsWrapper();
							$msgCom->gotBookingCpp($model, UserInfo::TYPE_SYSTEM);
						}
					}
					DBUtil::commitTransaction($transaction);
					if ($data['payBy'] == 2 && $model->bkg_status == 15)
					{
						$isAlready2Sms = SmsLog::model()->getCountVerifySms($model->bkg_id);

						if ($isAlready2Sms <= 2)
						{
							$bkgUser->sendVerificationCode(10, false);
						}
					}
					$message	 = "Booking ID $model->bkg_booking_id  created. $desc";
					$res_data	 = ['booking_id' => $model->bkg_booking_id];
					if ($data['payBy'] == 2 && $user_data['vendor_id'] > 0)
					{
						if ($model->bkg_status == 15)
						{
							$bcbModel	 = BookingCab::model()->findByPk($model->bkg_bcb_id);
							//$result		 = $bcbModel->assignVendor($model->bkg_bcb_id, $user_data['vendor_id'], '', '', $userInfo, 1, null, $isSpot= 1); // assignmode=1
                            $bcbModel->bcb_vendor_id	 = $user_data['vendor_id'];
                            //$bcbModel->save();
                            if ($bcbModel->save())
							{
								$message .= " And Assigned to Vendor :" . $user_data['vendor_name'];
							}
							else
							{
								$message .= " But failed to Assign to recommended vendor";
							}
						}
					}
					$success = true;
				}
				catch (Exception $e)
				{
					$model->addError('bkg_id', $e->getMessage());
					DBUtil::rollbackTransaction($transaction);
					$message = $e->getMessage();
					$success = false;
				}
			}
			else
			{
				$message = "Validation Error Occurred.";
				$success = false;
			}
			response:
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'				 => $success,
							'bkg_id'				 => $model->bkg_booking_id,
							'message'				 => $message,
							'is_credit_available'	 => $is_credit_available,
							'res_data'				 => $res_data,
						)
			]);
		});


		/*
		 * Assign Booking to Vendor  
		 */
//        $this->onRest('req.post.cp_assign_booking.render', function() {
//
//            $header = array();
//            $header = $_SERVER;
//            $this->agentValidation($header);
//            $partnerId  = $header['HTTP_X_REST_AGNTID'];
//            $user_id   = $header['HTTP_X_REST_UID'];
//            $data1     = Yii::app()->request->getParam('data'); 
//            $data      = CJSON::decode($data1);
//            $userInfo  = UserInfo::getInstance();
//            $bookingId = $data['booking_id'];
//            $vendorId  = $data['vendor_id'];
//            $bkgModel  = Booking::model()->findByPk($bookingId);
//            $bcbModel  = BookingCab::model()->findByPk($bkgModel->bkg_bcb_id);
//            if ($bkgModel && $bkgModel->bkg_status == 2) {
//                $trasaction = DBUtil::beginTransaction();
//                try {
//                    $success = $bcbModel->assignVendor($bkgModel->bkg_bcb_id, $vendorId, '', '', $userInfo);
//                    if ($success) {
//                        $message = 'Booking ID: ' . $bkgModel->bkg_booking_id . ' has been assigned to vendor ' . $data['vendor_name'];
//                        DBUtil::commitTransaction($trasaction);
//                    } else {
//                        $message = 'Failed to assign booking.Please Contanct Gozo Care.';
//                        DBUtil::rollbackTransaction($trasaction);
//                    }
//                } catch (Exception $ex) {
//                    $errors = 'Failed to assign booking.Please Contanct Gozo Care.';
//                    DBUtil::rollbackTransaction($trasaction);
//                }
//            }
//            return $this->renderJSON([
//                        'type' => 'raw',
//                        'data' => array(
//                            'success'  => $success,
//                            'errors'   => $errors,
//                            'messages' => $messages,
//                        ),
//            ]);
//        });


		/*
		 * Recharging agent's account 
		 */
		$this->onRest('req.post.cp_make_recharge.render', function()
		{

			/* $header  = getallheaders();
			  // this agentValidation function should be used for every agent api checksum validation.
			  $this->agentValidation($header);

			  $agentId           = $header['X-Rest-Agntid'];
			  $user_id           = $header['X-Rest-Uid']; */
			$header		 = array();
			$header		 = $_SERVER;
			$this->agentValidation($header);
			$agentId	 = $header['HTTP_X_REST_AGNTID'];
			$user_id	 = $header['HTTP_X_REST_UID'];
			$agent_data	 = Agents::model()->getDetailsbyId($agentId);
			$data1		 = Yii::app()->request->getParam('data');
			$data		 = CJSON::decode($data1);

			$amount		 = $data['recharge_amount'];
			$param_list	 = [];
			$errors		 = "";
			$messages	 = "";
			$transaction = Yii::app()->db->beginTransaction();

			if ($amount >= 500 && $data['paymentOpt'] == 2)
			{ // Payment By PayUMoney				
				try
				{
					$paymentType						 = 6;  // 3=>paytm, 6=>payumoney							
					$paymentGateway						 = new PaymentGateway();
					$paymentGateway->apg_acc_trans_type	 = Accounting::AT_PARTNER;
					$paymentGateway->apg_trans_ref_id	 = $agentId;
					$paymentGateway->apg_booking_id		 = '';
					$paymentGateway->apg_ptp_id			 = $paymentType;
					$paymentGateway->apg_amount			 = $amount;
					$paymentGateway->apg_remarks		 = "Payment Initiated";
					$paymentGateway->apg_ref_id			 = '';
					$paymentGateway->apg_user_type		 = UserInfo::TYPE_AGENT;
					$paymentGateway->apg_user_id		 = $agentId;
					$paymentGateway->apg_status			 = 0;
					$paymentGateway->apg_date			 = new CDbExpression('NOW()');
					$bankLedgerId						 = PaymentType::model()->ledgerList($paymentType);
					$paymentGateway						 = $paymentGateway->payment($bankLedgerId);

					if ($paymentType === PaymentType::TYPE_PAYUMONEY && $amount > 0)
					{
						$param_list					 = array();
						$param_list['key']			 = Yii::app()->payu->merchant_key;
						$order_id					 = $paymentGateway->apg_code;
						$param_list['txnid']		 = $order_id;
						$param_list['amount']		 = number_format($paymentGateway->apg_amount, 1, ".", "");
						$param_list['productinfo']	 = 'partnercode/' . $agent_data['agt_agent_id'];
						$param_list['firstname']	 = $agent_data['agt_fname'] . ' ' . $agent_data['agt_lname'];
						$param_list['email']		 = $agent_data['agt_email'];
						$param_list['address1']		 = $agent_data['agt_address'];
						$param_list['city']			 = '';
						$param_list['state']		 = '';
						$param_list['country']		 = '';
						$param_list['phone']		 = $agent_data['agt_phone'];
						$param_list['surl']			 = "http:/203.163.247.10:8081/payu/partnerresponse"; //YII::app()->createAbsoluteUrl('payu/agentresponse?ptpid=6&app=1');
						$param_list['furl']			 = "http:/203.163.247.10:8081/payu/partnerresponse"; //YII::app()->createAbsoluteUrl('payu/agentresponse?ptpid=6&app=1'); 
//						$param_list['surl']				 = YII::app()->createAbsoluteUrl('payment/partnerresponse?ptpid=6&app=1');
//						$param_list['furl']				 = YII::app()->createAbsoluteUrl('payment/partnerresponse?ptpid=6&app=1'); 


						$param_list['service_provider']	 = 'payu_paisa';
						$param_list['merchant_id']		 = Yii::app()->payu->merchant_id;
						$checkSum						 = Yii::app()->payu->getChecksumFromArray($param_list);
						//$param_list['CHECKSUMHASH']		 = $checkSum;		
						$param_list['hash']				 = $checkSum['hash'];
						$param_list['action']			 = $checkSum['action'];
					}
					$messages	 = "Channel Partner Recharge.";
					Logger::create("param_list: " . json_encode($checkSum));
					$success	 = true;
					$transaction->commit();
				}
				catch (Exception $e)
				{
					$success = false;
					$errors	 = $e->getMessage();
					DBUtil::rollbackTransaction($transaction);
				}
			}
			else if ($amount >= 500 && $data['paymentOpt'] == 1)
			{ // Payment By PayTM					
				try
				{
					$paymentType						 = 3;  // 3=>paytm, 6=>payumoney					
					$paymentGateway						 = new PaymentGateway();
					$paymentGateway->apg_booking_id		 = '';
					$paymentGateway->apg_acc_trans_type	 = Accounting::AT_PARTNER;
					$paymentGateway->apg_trans_ref_id	 = $agentId;
					$paymentGateway->apg_ptp_id			 = $paymentType;
					$paymentGateway->apg_amount			 = $amount;
					$paymentGateway->apg_remarks		 = "Payment Initiated";
					$paymentGateway->apg_ref_id			 = '';
					$paymentGateway->apg_user_type		 = UserInfo::TYPE_AGENT;
					$paymentGateway->apg_user_id		 = $agentId;
					$paymentGateway->apg_status			 = 0;
					$paymentGateway->apg_date			 = new CDbExpression('NOW()');
					$bankLedgerId						 = PaymentType::model()->ledgerList($paymentType);
					$paymentGateway						 = $paymentGateway->payment($bankLedgerId);
					$params['blg_ref_id']				 = $paymentGateway->apg_id;



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
							$param_list['CUST_ID']			 = $agentId;
							$param_list['MOBILE_NO']		 = $agent_data['agt_phone'];
							$param_list['EMAIL']			 = $agent_data['agt_email'];
							$param_list['CALLBACK_URL']		 = YII::app()->createAbsoluteUrl('paytm/appresponse');
							$checkSum						 = Yii::app()->paytm->getChecksumFromArray($param_list);
							$param_list['CHECKSUMHASH']		 = $checkSum;
						}
					}
					DBUtil::commitTransaction($transaction);
				}
				catch (Exception $e)
				{
					$success = false;
					$errors	 = $e->getMessage();
					DBUtil::rollbackTransaction($transaction);
				}
			}
			else
			{
				$success = false;
				$errors	 = " Payment amount should be greater than or equals to 500.";
			}
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'	 => $success,
							'data'		 => $param_list,
							'errors'	 => $errors,
							'messages'	 => $messages,
						),
			]);
		});
		/*
		 * * Agent balance credit to show
		 */
		$this->onRest('req.get.cp_credit_balence.render', function()
		{
			/* $header  = getallheaders();
			  // this agentValidation function should be used for every agent api checksum validation.
			  $this->agentValidation($header);
			  $partnerId             = $header['X-Rest-Agntid'];
			  $user_id               = $header['X-Rest-Uid']; */
			$header	 = array();
			$header	 = $_SERVER;

			$this->agentValidation($header);
			$partnerId				 = $header['HTTP_X_REST_AGNTID'];
			$user_id				 = $header['HTTP_X_REST_UID'];
			$result					 = AccountTransDetails::accountTotalSummary($partnerId);
			$agent_credit			 = abs($result['totAmount']);
			$param_list['balance']	 = $agent_credit;
			$messages				 = 'Remaining balance';
			$success				 = true;
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'		 => $success,
							'errors'		 => $errors,
							'agent_credit'	 => $param_list,
							'messages'		 => $messages,
						),
			]);
		});

		/*
		 * Get agent last credit 
		 */
		$this->onRest('req.get.cp_latest_credit.render', function()
		{
			/* $header  = getallheaders();
			  // this agentValidation function should be used for every agent api checksum validation.
			  $this->agentValidation($header);
			  $partnerId             = $header['X-Rest-Agntid'];
			  $user_id               = $header['X-Rest-Uid']; */

			$header			 = array();
			$header			 = $_SERVER;
			$this->agentValidation($header);
			$partnerId		 = $header['HTTP_X_REST_AGNTID'];
			$userId			 = $header['HTTP_X_REST_UID'];
			$transaction_id	 = trim($_REQUEST['trans_id']);
			Logger::create("Transaction ID: " . $transaction_id);
			$result			 = AccountTransDetails::accountTotalSummary($partnerId);

			if (!empty($transaction_id))
			{
				$last_recharge_amount = AccountTransactions::lastRechargeAmount($partnerId, $transaction_id);

				$param_list['lastrecharge'] = abs($last_recharge_amount['apg_amount']);

				$agent_credit			 = abs($result['totAmount']);
				$param_list['balance']	 = $agent_credit;
				$messages				 = 'Transaction Successful';
				$success				 = true;
			}
			else
			{
				$last_recharge_data	 = AccountTransactions::lastRechargeAmount($partnerId, $transaction_id);
				$param_list			 = $last_recharge_data;
				//print_r($param_list);
				if ($last_recharge_data['t_status'])
				{
					$agent_credit			 = abs($result['totAmount']);
					$param_list['balance']	 = $agent_credit;
					$messages				 = $param_list['msg'];
					$success				 = true;
				}
				else
				{
					$success = false;
				}
			}
			Logger::create("Response Data: " . json_encode($param_list));
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'		 => $success,
							'errors'		 => $errors,
							'agent_credit'	 => $param_list,
							'messages'		 => $messages,
						),
			]);
		});

		/*
		 * Get Transaction History
		 */
		$this->onRest('req.get.cp_transaction_details.render', function()
		{
			/* $header  = getallheaders();
			  // this agentValidation function should be used for every agent api checksum validation.
			  $this->agentValidation($header);
			  $partnerId             = $header['X-Rest-Agntid'];
			  $user_id               = $header['X-Rest-Uid']; */
			$header		 = array();
			$header		 = $_SERVER;
			$this->agentValidation($header);
			$partnerId	 = $header['HTTP_X_REST_AGNTID'];
			$userId		 = $header['HTTP_X_REST_UID'];

			$data1		 = Yii::app()->request->getParam('data');
			$data		 = CJSON::decode($data1);
			$transDate1	 = $data['startdate'];
			$transDate2	 = $data['enddate'];
			$result_data = [];
			$current_bal = AccountTransDetails::accountTotalSummary($partnerId);


			$recordSet = AccountTransDetails::getTransactionData($partnerId, $transDate1, $transDate2);
			if (count($recordSet) > 0)
			{
				foreach ($recordSet as $k => $v)
				{
					$result_data[] = $v;
				}
				$success	 = "true";
				$messages	 = "";
			}
			else
			{
				$success	 = "false";
				$messages	 = "No Records Found.";
			}
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'		 => $success,
							'currentbalance' => abs($current_bal["totAmount"]),
							'data'			 => $result_data,
							'messages'		 => $messages,
						),
			]);
		});
		/*
		 * show booking amount...
		 */
		$this->onRest('req.post.cp_show_booking_amount.render', function()
		{
			/* $header  = getallheaders();
			  // this agentValidation function should be used for every agent api checksum validation.
			  $this->agentValidation($header);
			  $partnerId              =  $header['X-Rest-Agntid'];
			  $user_id                =  $header['X-Rest-Uid']; */
			$header		 = array();
			$header		 = $_SERVER;
			$this->agentValidation($header);
			$partnerId	 = $header['HTTP_X_REST_AGNTID'];
			$userId		 = $header['HTTP_X_REST_UID'];
			$route1		 = Yii::app()->request->getParam('route');
			$route		 = CJSON::decode($route1); //show route details
			$user_data	 = Yii::app()->request->getParam('data'); // show user details
			$user_data	 = CJSON::decode($user_data);


			//$bkg_to_city_id =  ($user_data['trip_type']==2?$route[0]['pickup_city']:$route[0]['drop_city']);
			$bkg_drop_address	 = ($user_data['trip_type'] == 2 ? $route[0]['pickup_address'] : $route[0]['drop_address']);
			$pickupdate			 = date('Y-m-d', strtotime($route[0]['pickupdate']));
			$pickup_time		 = date('H:i:s', strtotime($route[0]['time']));
			$date_time			 = (string) $pickupdate . ' ' . $pickup_time;
			if ($user_data['trip_type'] == 2)
			{
				$pickupdate			 = date('Y-m-d', strtotime($route[1]['pickupdate']));
				$pickup_time		 = date('H:i:s', strtotime($route[1]['time']));
				$return_date_time	 = (string) $pickupdate . ' ' . $pickup_time;
			}
			$preBookData	 = array('bkg_status'			 => 1,
				'bkg_active'			 => 1,
				'bkg_agent_id'			 => $partnerId,
				'bkg_booking_type'		 => $user_data['trip_type'],
				'bkg_from_city_id'		 => $route[0]['pickup_city'],
				'bkg_to_city_id'		 => $route[0]['drop_city'],
				'bkg_vehicle_type_id'	 => $user_data['cab_type_id'],
				'bkg_pickup_date'		 => $date_time,
				'bkg_return_date'		 => $return_date_time,
				//'bkg_pickup_date'=> '2018-12-27 09:00:00',
				'bkg_pickup_address'	 => $route[0]['pickup_address'],
				'bkg_drop_address'		 => $bkg_drop_address,
			);
			$preRutData[0]	 = array('brt_active'			 => '1',
				'brt_from_city_id'		 => $route[0]['pickup_city'],
				'brt_from_location'		 => $route[0]['pickup_address'],
				'brt_pickup_datetime'	 => $date_time,
				'brt_status'			 => 1,
				'brt_to_city_id'		 => $route[0]['drop_city'],
				'brt_to_location'		 => $route[0]['drop_address']);
			if ($user_data['trip_type'] == 2)
			{
				$pickupDate		 = date('Y-m-d', strtotime($route[1]['pickupdate']));
				$pickupTime		 = date("H:i:s", strtotime($route[1]['time']));
				$dateTime1		 = (string) $pickupDate . ' ' . $pickupTime;
				$preRutData[1]	 = array('brt_active'			 => 1,
					'brt_from_city_id'		 => $route[1]['pickup_city'],
					'brt_from_location'		 => $route[1]['pickup_address'],
					//'brt_pickup_datetime'=>$route[1]['pickupdate'],
					'brt_pickup_datetime'	 => $dateTime1,
					'brt_status'			 => 1,
					'brt_to_city_id'		 => $route[1]['drop_city'],
					'brt_to_location'		 => $route[1]['drop_address']);
			}
			//$payByType      = ($user_data['payBy']==1?$data['payByAgent']:$data['payByCustomer']);
			$data['bkg_booking_type']	 = $user_data['trip_type'];
			//$data['isRechargeAccount']	 = 0;
			$data['payBy']				 = $user_data['payBy'];
			$data['rechargeAmount']		 = 1;
			$data['rechargeMethod']		 = "";
			$data['preBookData']		 = $preBookData;
			$data['preRutData']			 = $preRutData;
			$data['bkg_booking_type']	 = $data['preBookData']['bkg_booking_type'];
			$preRutData					 = $data['preRutData'];
			$res_data					 = [];
			$message					 = "";

			$model					 = new Booking();
			$bkgInvoice				 = new BookingInvoice();
			//$bkgUser    = new BookingUser();
			$bkgAddInfo				 = new BookingAddInfo();
			//$bkgTrail   = new BookingTrail();
			$bkgTrack				 = new BookingTrack();
			$model->attributes		 = array_filter($data['preBookData']);
			$model->bkg_pickup_date	 = $data['preBookData']['bkg_pickup_date'];
			//$bkgUser->attributes    = array_filter($data['preBookData']);
			$model->bkg_agent_id	 = $partnerId;
			$model->preData			 = ['preBookData' => $data['preBookData'], 'preRutData' => array_filter($preRutData)];

			foreach ($preRutData as $key => $route)
			{
				$bookingRoute = new BookingRoute();
				if ($key == 1)
				{

					$estimatedPickuptime			 = Route::model()->getRoundtripEstimatedPickupbyReturnDateTime($route['brt_from_city_id'], $route['brt_to_city_id'], $route['brt_pickup_datetime']);
					$route['brt_pickup_datetime']	 = $estimatedPickuptime;
				}
				$bookingRoute->attributes	 = $route;
				$bookingRoute->with('brtFromCity', 'brtToCity');
				$bookingRoutesObj[]			 = $bookingRoute;
			}

			//$carType				 = VehicleTypes::model()->getVehicleTypeById($model->bkg_vehicle_type_id);
			$carType				 = $model->bkg_vehicle_type_id;
			$quote					 = new Quote();
			$quote->routes			 = $bookingRoutesObj;
			$quote->tripType		 = $model->bkg_booking_type;
			$quote->partnerId		 = $partnerId;
			$quote->quoteDate		 = $model->bkg_create_date;
			$quote->pickupDate		 = $model->bkg_pickup_date;
			$quote->returnDate		 = $model->bkg_return_date;
			$quote->sourceQuotation	 = Quote::Platform_Partner_Spot;
			$quote->isB2Cbooking	 = false;
			$quote->setCabTypeArr();
			$qt						 = $quote->getQuote($carType);
			$quoteData				 = $qt[$carType];

			$routeRates								 = $quoteData->routeRates;
			$model->bkg_agent_id					 = $partnerId;
			$agtModel								 = Agents::model()->findByPk($model->bkg_agent_id);
			$arrQuote								 = Agents::model()->getBaseDiscFare($quoteData->routeRates, $agtModel->agt_type, $model->bkg_agent_id);
			$routeRates								 = $arrQuote; //$quoteData->routeRates;
			$routeDistance							 = $quoteData->routeDistance;
			$routeDuration							 = $quoteData->routeDuration;
			$amount									 = $routeRates->baseAmount;
			$bkgInvoice->bkg_gozo_base_amount		 = $amount;
			$bkgInvoice->bkg_base_amount			 = $routeRates->baseAmount;
			$model->bkg_trip_distance				 = $routeDistance->tripDistance; // $routeData['minimumChargeableDistance'];
			$model->bkg_trip_duration				 = $routeDuration->totalMinutes;
			$bkgInvoice->bkg_driver_allowance_amount = $routeRates->driverAllowance;
			$bkgInvoice->bkg_chargeable_distance	 = $routeDistance->quotedDistance;
			$bkgTrack->bkg_garage_time				 = $routeDuration->totalMinutes + $routeDuration->garageTimeEnd + $routeDuration->garageTimeStart;
			//  $cabData['totalGarage'];
			$bkgInvoice->bkg_vendor_amount			 = round($routeRates->vendorAmount);
			$bkgInvoice->bkg_vendor_amount;
			$bkgInvoice->bkg_is_toll_tax_included	 = $routeRates->isTollIncluded | 0; //$cabData['tolltax'];
			$bkgInvoice->bkg_is_state_tax_included	 = $routeRates->isStateTaxIncluded | 0; //$cabData['statetax'];
			$bkgInvoice->bkg_toll_tax				 = $routeRates->tollTaxAmount; //$cabData['toll_tax'];
			$bkgInvoice->bkg_state_tax				 = $routeRates->stateTax; //$cabData['state_tax'];
			$bkgInvoice->bkg_quoted_vendor_amount	 = round($routeRates->vendorAmount);

			if ($bkgAddInfo->bkg_spl_req_carrier == 1)
			{
				$bkgInvoice->bkg_additional_charge			 = 150;
				$bkgInvoice->bkg_additional_charge_remark	 = 'Carrier Requested for Rs.150';
			}
			else
			{
				$bkgInvoice->bkg_additional_charge			 = 0;
				$bkgInvoice->bkg_additional_charge_remark	 = '';
			}

			if ($model->bkg_agent_id != '')
			{
				$agtModel = Agents::model()->findByPk($model->bkg_agent_id);
				if ($agtModel->agt_city == 30706)
				{
					$bkgInvoice->bkg_cgst	 = Yii::app()->params['cgst'];
					$bkgInvoice->bkg_sgst	 = Yii::app()->params['sgst'];
					$bkgInvoice->bkg_igst	 = 0;
				}
				else
				{
					$bkgInvoice->bkg_igst	 = Yii::app()->params['igst'];
					$bkgInvoice->bkg_cgst	 = 0;
					$bkgInvoice->bkg_sgst	 = 0;
				}
			}
			if ($amount > 0)
			{
				$bkgInvoice->calculateConvenienceFee(0);
				$bkgInvoice->populateAmount(true, false, true, true, $model->bkg_agent_id);

				$totalAmount = $bkgInvoice->bkg_total_amount;
				$success	 = true;
			}
			else
			{
				$success	 = false;
				$totalAmount = "";
			}
			response:
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'		 => $success,
							'message'		 => "Total booking amount",
							'total_amount'	 => $totalAmount
						)
			]);

			//show booking price end here
		});

		$this->onRest('req.post.agentAcountHistory.render', function()
		{
			$header	 = array();
			$header	 = $_SERVER;
			$agtId	 = $header['HTTP_X_REST_AGNTID'];
			$userId	 = $header['HTTP_X_REST_UID'];
			$success = false;
			if ($agtId != '' && $userId != '')
			{
				$token		 = Agents::model()->createToken($userId, $agtId);
				$url		 = Yii::app()->params['fullBaseURL'] . "/cpaa/booking/ledgerbooking?authtoken=" . $token;
				$acountUrl	 = stripslashes($url);
				$success	 = true;
			}
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'	 => $success,
							'url'		 => $acountUrl,
							'token'		 => $token,
						),
			]);
		});

		$this->onRest('req.post.bookingHistory.render', function()
		{
			$header	 = array();
			$header	 = $_SERVER;
			$agtId	 = $header['HTTP_X_REST_AGNTID'];
			$userId	 = $header['HTTP_X_REST_UID'];
			$success = false;
			if ($agtId != '' && $userId != '')
			{
				$token		 = Agents::model()->createToken($userId, $agtId);
				$url		 = Yii::app()->params['fullBaseURL'] . "/cpaa/booking/list?authtoken=" . $token;
				$acountUrl	 = stripslashes($url);
				$success	 = true;
			}
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'	 => $success,
							'url'		 => $acountUrl,
							'token'		 => $token,
						),
			]);
		});

		$this->onRest('req.get.vendorAssignList.render', function()
		{
			$header		 = array();
			$header		 = $_SERVER;
			$this->agentValidation($header);
			$partnerId	 = $header['HTTP_X_REST_AGNTID'];
			$userId		 = $header['HTTP_X_REST_UID'];
			$success	 = false;
			Logger::trace("Partner id====>".$partnerId);
			Logger::trace("User id====>".$userId);


			$result				 = [];
			$agtModel			 = Agents::model()->findByPk($partnerId);
			$creditLimit		 = $agtModel->agt_effective_credit_limit;
			$isCreditAvailable	 = ($creditLimit > 0) ? 1 : 0;




			/* $vendorDetails = Vendors::model()->getActiveVendorDetails($userId);         
			  if (count($vendorDetails) > 1) {
			  $success = true;
			  $result[] = array('id' => $vendorDetails['vnd_id'], 'name' => $vendorDetails['vnd_name']);
			  } else {
			  $driverDetails = Drivers::model()->findByUserid($userId);
			  if ($driverDetails > 0) {
			  $success = true;
			  $drv_id = $driverDetails->drv_id;
			  $vendors = VendorDriver::model()->getActiveVendorListbyDriverId($drv_id);
			  foreach ($vendors as $vb) {
			  $result[] = array('id' => $vb['vnd_id'], 'name' => $vb['vnd_name']);
			  }
			  } else {
			  $result[] = 'Invalid User';
			  }
			  } */

			$driverDetails = Drivers::model()->findByUserid($userId);
			if (count($driverDetails) > 0)
			{
				$success = true;
				$drv_id	 = $driverDetails->drv_id;
				$vendors = VendorDriver::model()->getActiveVendorListbyDriverId($drv_id);
				foreach ($vendors as $vb)
				{
					$result[] = array('id' => $vb['vnd_id'], 'name' => $vb['vnd_name']);
				}
			}
			else
			{
				$vendorDetails = Vendors::model()->getActiveVendorDetails($userId);

				if (count($vendorDetails) > 1)
				{
					$success	 = true;
					$result[]	 = array('id' => $vendorDetails['vnd_id'], 'name' => $vendorDetails['vnd_name']);
				}
				else
				{
					$result[] = 'Invalid User';
				}
			}

			Logger::trace("Details====>".json_encode($result)); 


			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'			 => $success,
							'creditLimit'		 => $creditLimit,
							'is_credit_avaiable' => $isCreditAvailable,
							'vendor'			 => $result
						)
			]);
		});
	}

	public function getValidationApp($data, $id, $activeVersion)
	{
		if ($activeVersion > $data['apt_apk_version'])
		{
			$active	 = 1;
			$success = false;
			$msg	 = "Invalid Version";
		}
		else
		{
			if ($id != '')
			{
				$validate	 = AppTokens::model()->getAppValidations($data, $id);
				$active		 = 2;
				$success	 = true;
				$msg		 = "Validation Done";
			}
			else
			{
				$active	 = 3;
				$success = false;
				$msg	 = "Invalid User";
			}
		}
		$result = array('active' => $active, 'success' => $success, 'message' => $msg);
		return $result;
	}

	public function actionIndex()
	{
		echo 'Module created';
	}

	public function agentValidation($header)
	{
		// agent validation function start

		$userValidation = Agents::model()->validateAgent($header);

		if ($userValidation != true)
		{

			echo $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => false,
					'errorMessages'	 => 'Unauthorised user',
					'data'			 => null
				)
			]);
			exit;
		}

		//agent validation function end
	}

	public function actionLedgerbooking()
	{
		$authtoken		 = Yii::app()->request->getParam('authtoken');
		$agentDetails	 = Agents::model()->getAgentIdByAuthtoken($authtoken);
		$agtId			 = $agentDetails['agt_id'];
		$token			 = $agentDetails['agt_verify_myacc_section'];
		$agentModel		 = Agents::model()->findByPk($agtId);
		$this->pageTitle = "Partner Accounts";
		$transDate1		 = '';
		$transDate2		 = '';
		$model			 = new AccountTransDetails();
		$model->scenario = "ledgerbooking";
		if ($authtoken == $token)
		{

			$agentModel->agt_verify_myacc_section = null;
			$agentModel->save();
			if ($_REQUEST['AccountTransDetails']['trans_create_date1'] != '' && $_REQUEST['AccountTransDetails']['trans_create_date2'] != '')
			{
				$transDate1					 = $_REQUEST['AccountTransDetails']['trans_create_date1'];
				$transDate2					 = $_REQUEST['AccountTransDetails']['trans_create_date2'];
				$model->trans_create_date1	 = $transDate1;
				$model->trans_create_date2	 = $transDate2;
			}
			if (!isset($_REQUEST['AccountTransDetails']) && $_REQUEST['AccountTransDetails']['trans_create_date1'] == '' && $_REQUEST['AccountTransDetails']['trans_create_date2'] == '')
			{
				$transDate1					 = $model->trans_create_date1	 = date('Y-m-d', strtotime('today - 29 days'));
				$transDate2					 = $model->trans_create_date2	 = date('Y-m-d', strtotime('today'));
			}

			$recordSet							 = AccountTransDetails::transactionList($agtId, $transDate1, $transDate2);
			$totalRecords						 = count($recordSet);
			$agentList							 = new CArrayDataProvider($recordSet, array('pagination' => array('pageSize' => 500)));
			$agentList->getPagination()->params	 = array_filter($_GET + $_POST);
			$agentList->getSort()->params		 = array_filter($_GET + $_POST);
			$agentModels						 = $agentList->getData();
			$agentAmount						 = AccountTransDetails::accountTotalSummary($agtId, $transDate1, $transDate2);
			if (isset($_REQUEST['export_from']) && isset($_REQUEST['export_to']))
			{
				$arr		 = array();
				$fromDate	 = Yii::app()->request->getParam('export_from');
				$toDate		 = Yii::app()->request->getParam('export_to');
				$arr['data'] = AccountTransDetails::transactionList($agtId, $fromDate, $toDate);
				if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
				{
					header("Content-type: application/csv");
					header("Content-Disposition: attachment; filename=\"LedgerReport_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
					header("Pragma: no-cache");
					header("Expires: 0");
					$handle = fopen("php://output", 'w');
					fputcsv($handle, array("Transaction Date", "Booking ID", "Partner Reference ID", "Pickup Date", "Booking Info", "Amount", "Notes", "Who", "Running Balance"));
					foreach ($arr['data'] as $req)
					{

						$bookingInfo = $req['bookingInfo'];

						fputcsv($handle, array($req['act_date'], $req['bookingId'], $req['bkg_agent_ref_code'], $req['bkg_pickup_date'], $bookingInfo, number_format(round($req['adt_amount'])), $req['act_remarks'], trim($req['adminName']), number_format($req['runningBalance'])));
					}
					fclose($handle);
					exit;
				}
			}
		}

		$this->render('ledgerbooking', ['model' => $model, 'agentList' => $agentList, 'agentmodels' => $agentModels, 'agentAmount' => $agentAmount, 'totalRecords' => $totalRecords]);
	}

	public function actionList()
	{
		$this->pageTitle = "Booking History";
		$authtoken		 = Yii::app()->request->getParam('authtoken');
		$agentDetails	 = Agents::model()->getAgentIdByAuthtoken($authtoken);
		$agtId			 = $agentDetails['agt_id'];
		$token			 = $agentDetails['agt_verify_myacc_section'];
		$agentModel		 = Agents::model()->findByPk($agtId);
		if ($agentModel != '' && $authtoken == $token)
		{

			$agentModel->agt_verify_myacc_section = null;
			$agentModel->update();

			$model		 = new Booking('search');
			$paramArray	 = [];
			if (isset($_REQUEST['Booking']))
			{
				$model->attributes		 = Yii::app()->request->getParam('Booking');
				$paramArray				 = Yii::app()->request->getParam('Booking');
				$model->bkg_pickup_date1 = $paramArray['bkg_pickup_date1'];
				$model->bkg_pickup_date2 = $paramArray['bkg_pickup_date2'];
				$model->bkg_create_date1 = $paramArray['bkg_create_date1'];
				$model->bkg_create_date2 = $paramArray['bkg_create_date2'];
			}
			else
			{
				$model->bkg_status		 = '';
				$model->bkg_pickup_date1 = ($paramArray['bkg_pickup_date1'] == '') ? date('Y-m-d', strtotime('-1 month')) : $paramArray['bkg_pickup_date1'];
				$model->bkg_pickup_date2 = ($paramArray['bkg_pickup_date2'] == '') ? date('Y-m-d', strtotime('+11 month')) : $paramArray['bkg_pickup_date2'];
				$model->bkg_create_date1 = ($paramArray['bkg_create_date1'] == '') ? date('Y-m-d', strtotime('-1 month')) : $paramArray['bkg_create_date1'];
				$model->bkg_create_date2 = ($paramArray['bkg_create_date2'] == '') ? date('Y-m-d') : $paramArray['bkg_create_date2'];
			}


			$agentBkgStatus	 = BookingSub::model()->getAgentActiveBookingStatusList($agtId);
			$statusJSON		 = VehicleTypes::model()->getJSON($agentBkgStatus);
			$dataProvider	 = BookingSub::model()->listByAgent($agtId, array_filter($model->attributes + $paramArray));
		}
		$this->render('list', ['dataProvider' => $dataProvider, 'model' => $model, 'statusJSON' => $statusJSON, 'agentBkgStatus' => $agentBkgStatus, 'Param_Arr' => $paramArray]);
	}

}
