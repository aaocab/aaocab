<?php

use Booking;

include_once(dirname(__FILE__) . '/BaseController.php');

class EdenController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = 'main';
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
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			['allow',
				'actions'	 => [],
				'users'		 => ['@']
			],
//            array('allow', // allow authenticated user to perform 'create' and 'update' actions
//                'actions' => array(
//                    'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
//                'users' => array('*'),
//            ),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function restEvents()
	{
		$this->onRest('req.cors.access.control.allow.methods', function() {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function($validation) {
			//  throw  new CHttpException(404,"Resource Not Found");
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array('/review', '/getQuote');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			//  return false;
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.post.request.render', function() {
			$data = Yii::app()->request->getParam('data');

			$requestType = Yii::app()->request->getParam('requestType');
			if ($data == "")
			{
				$data = Yii::app()->request->rawBody;
			}
			$data = CJSON::decode($data, true);
			if ($data['requestType'] == 'SEARCH' || $requestType == 'SEARCH')
			{
				return $this->emitRest("req.post.getQuote.render");
			}
			if ($data['requestType'] == 'QUOTE' || $requestType == 'QUOTE')// new service for MMT
			{
				return $this->emitRest("req.post.quote.render");
			}
			if ($data['requestType'] == 'HOLD')
			{
				return $this->emitRest("req.post.holdBooking.render");
			}
			if ($data['requestType'] == 'CREATE' || $requestType == 'CREATE')
			{
				return $this->emitRest("req.post.hold.render");
			}
			if ($data['requestType'] == 'CONFIRM')
			{
				return $this->emitRest("req.post.confirmBooking.render");
			}
			if (data['requestType'] == 'CONBOOKING' || $requestType == 'CONBOOKING')
			{
				return $this->emitRest("req.post.confirm.render");
			}
			if ($data['requestType'] == 'CANCEL')
			{
				return $this->emitRest("req.post.cancelBooking.render");
			}

			if ($data['requestType'] == 'BOOKING_DETAILS')
			{
				return $this->emitRest("req.post.getDetails.render");
			}
			if ($data['requestType'] == 'UPDATE_BOOKING')
			{
				return $this->emitRest("req.post.updateBooking.render");
			}
		});

		$this->onRest('req.post.getQuote.render', function() {
			Logger::create("getQuote START:\t", CLogger::LEVEL_PROFILE);
			$data = Yii::app()->request->getParam('data');
			if ($data == "")
			{
				$data = Yii::app()->request->rawBody;
			}
			$data		 = CJSON::decode($data, true);
			$aatType	 = AgentApiTracking::TYPE_GET_QUOTE;
			$error_msg	 = '';
			$error_type	 = '';
			$pickupDate	 = DateTimeFormat::DatePickerToDate($data['departureDate']);
			if ($data['pickupTime'] != '')
			{
				$pickupTime = $data['pickupTime'] . ':00';
			}
			else
			{
				if ($pickupDate == date('Y-m-d'))
				{
					$pickupTime = date('H:i:s', strtotime('+ 240 minute'));
				}
				else
				{
					$pickupTime = '06:00:00';
				}
			}
			$bmodel					 = Booking::model();
			$pickupDateTime			 = $pickupDate . ' ' . $pickupTime;
			$bmodel->bkg_pickup_date = $pickupDateTime;
			if ($data['toCityCode'] == 'PROXY')
			{
				$point					 = $data['destPoint'];
				$placeObj				 = new \Stub\common\Place();
				$placeObj->address		 = $point['address'];
				$placeObj->coordinates	 = new \Stub\common\Coordinates($point['latitude'], $point['longitude']);
				$placeObj->place_id		 = $point['placeId'];
				$latModel				 = LatLong::findNearest($placeObj);
				if ($latModel)
				{
					$dropCity = $latModel->ltg_city_id;
					goto skipDropCity;
				}

				$ctyModel = Cities::getByNearestBound($placeObj);
				if ($ctyModel && $ctyModel->is_partial == 0)
				{
					$dropCity = $ctyModel->cty_id;
					goto skipDropCity;
				}

				$lModel = LatLong::getDetailsByPlace($placeObj, 15);
				if ($lModel)
				{
					$dropCity = $lModel->ltg_city_id;
				}
			}
			else
			{
				$dropCity = MmtCity::model()->getCityId($data['toCityCode']);
			}

			skipDropCity:
			$bmodel->bkg_to_city_id	 = $dropCity;
			$dropAddress			 = $data['destPoint']['address'];
			if ($data['fromCityCode'] == 'PROXY')
			{
				$point					 = $data['srcPoint'];
				$placeObj				 = new \Stub\common\Place();
				$placeObj->address		 = $point['address'];
				$placeObj->coordinates	 = new \Stub\common\Coordinates($point['latitude'], $point['longitude']);
				$placeObj->place_id		 = $point['placeId'];
				$latModel				 = LatLong::findNearest($placeObj);
				if ($latModel)
				{
					$pickupCity = $latModel->ltg_city_id;
					goto skipPickupCity;
				}

				$ctyModel = Cities::getByNearestBound($placeObj, 15);
				if ($ctyModel && $ctyModel->is_partial == 0)
				{
					$pickupCity = $ctyModel->cty_id;
					goto skipPickupCity;
				}

				$lModel = LatLong::getDetailsByPlace($placeObj);
				if ($lModel)
				{
					$pickupCity = $lModel->ltg_city_id;
				}
			}
			else
			{
				$pickupCity = MmtCity::model()->getCityId($data['fromCityCode']);
			}

			skipPickupCity:
			$bmodel->bkg_from_city_id	 = $pickupCity;
			$pickupAddress				 = $data['srcPoint']['address'];
			$isAirport					 = Cities::model()->findByPk($pickupCity)->cty_is_airport;

			if ($data['tripType'] == 'OW')
			{
				$triptype					 = 1;
				$bmodel->bkg_booking_type	 = 1;
			}
			elseif ($data['tripType'] == 'RT')
			{
				$triptype					 = 2;
				$bmodel->bkg_booking_type	 = 2;
			}
			elseif ($data['tripType'] == 'AT')
			{
				$triptype					 = 4;
				$bmodel->bkg_booking_type	 = 4;
				if ($dropCity != '' && $pickupCity != '')
				{
					$isDropAirport = Cities::model()->findByPk($dropCity)->cty_is_airport;
					if ($isAirport == 1)
					{
						$bmodel->bkg_transfer_type = 1;
					}
					else if ($isDropAirport == 1)
					{
						$bmodel->bkg_transfer_type = 2;
					}
					else
					{
						$status		 = 2;
						$result		 = ['response'		 => '', 'status'		 => 'error',
							'response_type'	 => 'SEARCH', 'errors'		 => ['Route' => ['0' => 'No airport is chosen in the route']]];
						$error_msg	 = 'No airport is chosen in the route';
						$error_type	 = 1;
					}
				}
			}
			else
			{
				Logger::create("Error: " . json_encode($data), CLogger::LEVEL_INFO);
				$status		 = 2;
				$result		 = ['response' => '', 'status' => 'error', 'response_type' => 'SEARCH', 'errors' => ['triptype' => ['0' => 'Trip type not supported']]];
				$error_msg	 = 'Trip type not supported';
				$error_type	 = 1;
			}
			$aatModel = AgentApiTracking::model()->add($aatType, $data, $bmodel, \Filter::getUserIP(), $data['toCityCode'], $data['fromCityCode']);
			if ($dropCity != '' && $pickupCity != '' && $error_msg == '')
			{



				if ($data['returnDate'] != '')
				{
					$returnDate				 = DateTimeFormat::DatePickerToDate($data['returnDate']);
					$returnTime				 = date('H:i:s', strtotime($data['dropTime']));
					$returnDateTime			 = $returnDate . ' ' . $returnTime;
					$routeDuration			 = Route::model()->getRouteDurationbyCities($dropCity, $pickupCity);
					$pickupDateTime2		 = date('Y-m-d H:i:s', strtotime($returnDateTime . '- ' . $routeDuration . ' minute'));
					$pickupDate2			 = date('Y-m-d', strtotime($pickupDateTime2));
					$pickupTime2			 = date('H:i:s', strtotime($pickupDateTime2));
					$bmodel->bkg_return_date = $returnDateTime;
					//$bmodel->bkg_return_time = $returnTime;
				}
				if ($triptype == 2)
				{
					$routes = [
						0	 =>
						['pickupDate'	 => $pickupDate, 'pickupTime'	 => $pickupTime,
							'dropCity'		 => $dropCity, 'dropPincode'	 => '', 'dropAddress'	 => $dropAddress,
							'pickupCity'	 => $pickupCity, 'pickupPincode'	 => '', 'pickupAddress'	 => $pickupAddress],
						1	 =>
						['pickupDate'	 => $pickupDate2, 'pickupTime'	 => $pickupTime2,
							'dropCity'		 => $pickupCity, 'dropPincode'	 => '', 'dropAddress'	 => $pickupAddress,
							'pickupCity'	 => $dropCity, 'pickupPincode'	 => '', 'pickupAddress'	 => $dropAddress]
					];
					if (BookingSub::model()->getApplicable($pickupCity, $dropCity, 3) && BookingSub::model()->getApplicable($dropCity, $pickupCity, 3))
					{
						$isFullPayment = false;
					}
					else
					{
						$isFullPayment = true;
					}
				}
				elseif ($triptype == 1)
				{
					$routes = [0 => ['pickupDate' => $pickupDate, 'pickupTime' => $pickupTime, 'dropCity' => $dropCity, 'dropPincode' => '', 'dropAddress' => $dropAddress, 'pickupCity' => $pickupCity, 'pickupPincode' => '', 'pickupAddress' => $pickupAddress]];
					if (BookingSub::model()->getApplicable($pickupCity, $dropCity, 3))
					{
						$isFullPayment = false;
					}
					else
					{
						$isFullPayment = true;
					}
				}
				elseif ($triptype == 4)
				{
					$routes = [0 =>
						[
							'pickupDate'	 => $pickupDate,
							'pickupTime'	 => $pickupTime,
							'dropCity'		 => $dropCity,
							'dropPincode'	 => '',
							'dropAddress'	 => $dropAddress,
							'pickupCity'	 => $pickupCity,
							'pickupPincode'	 => '',
							'pickupAddress'	 => $pickupAddress
						]
					];
					if (BookingSub::model()->getApplicable($pickupCity, $dropCity, 3))
					{
						$isFullPayment = false;
					}
					else
					{
						$isFullPayment = true;
					}
				}
				Logger::create("2");
				$routes	 = json_encode($routes);
				$routes	 = json_decode($routes);

				$bmodel->bkg_booking_type	 = $triptype;
				$routeArr					 = [];
				$rCount						 = count($routes);
				$bmodel->bkg_from_city_id	 = $routes[0]->pickupCity;
				$bmodel->bkg_to_city_id		 = $routes[$rCount - 1]->dropCity;
				foreach ($routes as $key => $value)
				{
					$routeModel							 = new BookingRoute();
					$routeModel->brt_from_city_id		 = $value->pickupCity;
					$routeModel->brt_to_city_id			 = $value->dropCity;
					$routeModel->brt_pickup_datetime	 = $value->pickupDate . " " . $value->pickupTime;
					$routeModel->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($value->pickupDate . " " . $value->pickupTime);
					$routeModel->brt_pickup_date_time	 = date('h:i A', strtotime($value->pickupDate . " " . $value->pickupTime));
					$routeModel->brt_to_location		 = $value->dropAddress;
					$routeModel->brt_from_location		 = $value->pickupAddress;
					$routeModel->brt_to_pincode			 = $value->dropPincode;
					$routeModel->brt_from_pincode		 = $value->pickupPincode;
					$routeArr[]							 = $routeModel;
				}
				try
				{
					if ($triptype == 1 && $data['mmtApproxDistance'] > 0)
					{
						$routeModel						 = $routeArr[0];
						$routeModel->brt_trip_distance	 = $data['mmtApproxDistance'];
						$result							 = Route::model()->populate($routeModel->brt_from_city_id, $routeModel->brt_to_city_id);
						if ($result['success'])
						{
							$rutModel						 = $result['model'];
							$routeModel->brt_trip_duration	 = $rutModel->rut_estm_time;
						}
					}

					if ($triptype == 2)
					{
						foreach ($routeArr as $routeModel)
						{
							$result = Route::model()->populate($routeModel->brt_from_city_id, $routeModel->brt_to_city_id);
							if ($result['success'])
							{
								$rutModel						 = $result['model'];
								$routeModel->brt_trip_duration	 = $rutModel->rut_estm_time;
								$routeModel->brt_trip_distance	 = $rutModel->rut_actual_distance > 0 ? $rutModel->rut_actual_distance : $rutModel->rut_estm_distance;
							}
						}
					}
				}
				catch (Exception $e)
				{
					$error_type	 = 2;
					$status		 = 2;
					$error_msg	 = 'Route not supported';
					$result		 = ['response' => '', 'status' => 'error', 'response_type' => 'SEARCH', 'errors' => ["routes" => [$error_msg]]];
					goto returnRespose;
				}


				$bmodel->bookingRoutes	 = $routeArr;
				$bmodel->setScenario('apiroutes1');
				$bmodel->bkg_agent_id	 = 450;
				$result					 = CActiveForm::validate($bmodel, null, false);
				if ($result == '[]')
				{
					$arr = [];
					foreach ($routeArr as $key => $brtModel)
					{
						$arr[$key]['date']			 = $brtModel->brt_pickup_datetime;
						$arr[$key]['drop_city']		 = $brtModel->brt_to_city_id;
						$arr[$key]['pickup_city']	 = $brtModel->brt_from_city_id;
						$arr[$key]['distance']		 = $brtModel->brt_trip_distance;
					}
					$arr	 = json_encode($arr);
					$arr	 = json_decode($arr);
					$quote	 = $this->cab_list($arr, $triptype, $data['mmtApproxDistance']);

					if (!$quote || count($quote) == 0)
					{
						$status		 = 2;
						$result		 = ['response'		 => '', 'status'		 => 'error',
							'response_type'	 => 'SEARCH', 'errors'		 => 'No cab is supported in this route'];
						$error_msg	 = 'No cab is supported in this route';
						$error_type	 = 4;
					}
					else
					{
						$diff		 = ((strtotime($pickupDateTime) - time()) / 60);
						$currentHour = (int) date('H');
						$diffCheck	 = (180 + $quote[0]['graceTime']);
						$slot		 = 0;
						if ($currentHour < 2 || $currentHour >= 22)
						{
							$slot		 = 1;
							$diffCheck	 = (240 + $quote[0]['graceTime']);
						}
						else if ($currentHour < 5 || $currentHour > 21)
						{
							$slot		 = 2;
							$diffCheck	 = (210 + $quote[0]['graceTime']);
						}
						if ($diff >= $diffCheck)
						{
							Logger::create("3");

							foreach ($quote as $i => $k)
							{

								$diffCheck	 = (180 + $quote[$i]['graceTime']);
								$slot		 = 0;
								if ($currentHour <= 2 || $currentHour >= 22)
								{
									$slot		 = 1;
									$diffCheck	 = (240 + $quote[$i]['graceTime']);
								}
								else if ($currentHour < 5 || $currentHour > 21)
								{
									$slot		 = 2;
									$diffCheck	 = (210 + $quote[$i]['graceTime']);
								}
								if ($diff < $diffCheck)
								{
									unset($quote[$i]);
									continue;
								}

								$model									 = Booking::model();
								$bkgInvoiceModel						 = BookingInvoice::model();
								$bkgInvoiceModel->bkg_gozo_base_amount	 = $quote[$i]['base_amt'];
								$bkgInvoiceModel->bkg_base_amount		 = $quote[$i]['base_amt'];
								if ($triptype == 2)
								{
									$bkgInvoiceModel->bkg_base_amount = round($bkgInvoiceModel->bkg_base_amount * 1);
								}
								if ($quote[$i]['tolltax'] == 1 && $isAirport == 1)
								{
									$bkgInvoiceModel->bkg_base_amount = $bkgInvoiceModel->bkg_base_amount + 100;
								}

								if (($slot == 1 && $diff <= 240))
								{
									$bkgInvoiceModel->bkg_base_amount = round($bkgInvoiceModel->bkg_base_amount * 1.20);
								}

								if ($currentHour == 2 && $diff > 1400 && $diff < 3900)
								{
									$bkgInvoiceModel->bkg_base_amount = round($bkgInvoiceModel->bkg_base_amount * 1.2);
								}

								$bkgInvoiceModel->bkg_driver_allowance_amount	 = $quote[$i]['driverAllowance'];
								$bkgInvoiceModel->bkg_toll_tax					 = $quote[$i]['toll_tax'];
								$bkgInvoiceModel->bkg_state_tax					 = $quote[$i]['state_tax'];
								$bkgInvoiceModel->bkg_is_toll_tax_included		 = $quote[$i]['tolltax'] | 0;
								$bkgInvoiceModel->bkg_is_state_tax_included		 = $quote[$i]['statetax'] | 0;
								$bkgInvoiceModel->bkg_is_parking_included		 = $quote[$i]['parkingInc'] | 0;
								$model->bkg_agent_id							 = Yii::app()->user->getId();
								//$model->populateAmount();
								$bkgInvoiceModel->populateAmount(true, false, true, false, $model->bkg_agent_id);
								$quote[$i]['base_amt']							 = $bkgInvoiceModel->bkg_base_amount;
								$quote[$i]['service_tax']						 = $bkgInvoiceModel->bkg_service_tax;
								$quote[$i]['total_amt']							 = $bkgInvoiceModel->bkg_total_amount;
							}
							$includeTax				 = true;
							$package_zero_inclusive	 = $package_one_inclusive	 = $package_two_inclusive	 = false;
							if (!$includeTax)
							{
								$package_zero		 = ($quote[0]['total_amt'] - (int) $quote[0]['toll_tax'] - (int) $quote[0]['state_tax']) - $quote[0]['service_tax'];
								$package_zero_gst	 = round($package_zero * (Yii::app()->params['gst'] / 100));
								if ($quote[0]['toll_tax'] == 0 && $quote[0]['state_tax'] == 0 && $quote[0]['tolltax'] == 1)
								{
									$package_zero_inclusive = true;
								}
								$package_one	 = ($quote[1]['total_amt'] - (int) $quote[1]['toll_tax'] - (int) $quote[1]['state_tax']) - $quote[1]['service_tax'];
								$package_one_gst = round($package_one * (Yii::app()->params['gst'] / 100));
								if ($quote[1]['toll_tax'] == 0 && $quote[1]['state_tax'] == 0 && $quote[1]['tolltax'] == 1)
								{
									$package_one_inclusive = true;
								}
								$package_two	 = ($quote[2]['total_amt'] - (int) $quote[2]['toll_tax'] - (int) $quote[2]['state_tax']) - $quote[2]['service_tax'];
								$package_two_gst = round($package_two * (Yii::app()->params['gst'] / 100));
								if ($quote[2]['toll_tax'] == 0 && $quote[2]['state_tax'] == 0 && $quote[2]['tolltax'] == 1)
								{
									$package_two_inclusive = true;
								}
							}
							else
							{
								$package_zero			 = $quote[0]['total_amt'] - $quote[0]['service_tax'];
								$package_zero_gst		 = $quote[0]['service_tax'];
								$package_zero_inclusive	 = $quote[0]['tolltax'];

								$package_one			 = $quote[1]['total_amt'] - $quote[1]['service_tax'];
								$package_one_gst		 = $quote[1]['service_tax'];
								$package_one_inclusive	 = $quote[1]['tolltax'];

								$package_two			 = $quote[2]['total_amt'] - $quote[2]['service_tax'];
								$package_two_gst		 = $quote[2]['service_tax'];
								$package_two_inclusive	 = $quote[2]['tolltax'];
							}
							$cablist = [];
							//($i = 0; $i < count($quote); $i++)
							foreach ($quote as $i => $k)
							{
								$cablist[] = ['type'				 => $this->getCabType($quote[$i]['cab_type_id']),
									'vehicle_model'		 => $this->getCabModel($quote[$i]['cab_type_id']),
									'vehicle_image'		 => YII::app()->createAbsoluteUrl($quote[$i]['image']),
									'availability'		 => 85,
									'seat_capacity'		 => $quote[$i]['capacity'],
									'luggage_allowance'	 => $quote[$i]['big_bag_capacity'] + $quote[$i]['bag_capacity'],
									'is_ac'				 => true,
									'duration'			 => $quote[$i]['total_day'],
									'fare_details'		 =>
									['0' =>
										[
											'base_fare'							 => $quote[$i]['base_amt'],
											'per_km_charge'						 => $quote[$i]['km_rate'],
											'per_km_extra_charge'				 => $quote[$i]['km_rate'],
											'driver_charge'						 => $quote[$i]['driverAllowance'],
											'min_km_per_day'					 => $quote[$i]['km_per_day'],
											'package_rate'						 => $quote[$i]['total_amt'] - $quote[$i]['service_tax'],
											'is_refundable'						 => true,
											'approx_distance'					 => $quote[$i]['min_chargeable'],
											'total_days_charged'				 => $quote[$i]['days'],
											'service_tax_percent'				 => Yii::app()->params['gst'],
											'service_tax'						 => $quote[$i]['service_tax'],
											'gst_percent'						 => Yii::app()->params['gst'],
											'gst'								 => $quote[$i]['service_tax'],
											'total_amount'						 => $quote[$i]['total_amt'],
											'night_charges'						 => $quote[$i]['nightCharges'],
											'other_charges_percent'				 => 0,
											'other_charges'						 => 0,
											'is_service_tax_paid_by_customer'	 => true,
											'is_full_payment'					 => ($quote[$i]['fullPayment'] == 1) ? true : false,
											'is_all_inclusive'					 => $quote[$i]['tolltax'],
											'extra_fare_params_map'				 => new stdClass()]]];
							}
							$array = ["cab_list" => $cablist];


							Logger::create("4");

							if ($quote[0]['error'] == 0)
							{
								$status	 = 1;
								$result	 = ['response' => $array, 'status' => 'success', 'response_type' => 'SEARCH'];
							}
							else
							{
								$status		 = 2;
								$result		 = ['response' => '', 'status' => 'error', 'response_type' => 'SEARCH', 'errors' => $quote[0]['error']];
								$error_msg	 = $quote[0]['error'];
								$error_type	 = 4;
							}
						}
						else
						{
							$hour		 = floor(($diffCheck) / 60);
							$min		 = ($diffCheck % 60);
							$strTime	 = $hour . ' hour(s)' . (($min > 0) ? ' ' . $min . ' min(s)' : '');
							$status		 = 2;
							$result		 = ['response' => '', 'status' => 'error', 'response_type' => 'SEARCH', 'errors' => ['routes' => ['0' => "Departure time should be at least $strTime hence"]]];
							$error_msg	 = "Departure time should be at least $strTime hence";
							$error_type	 = 4;
						}
					}
				}
				else
				{
					$errors = [];
					foreach ($bmodel->getErrors() as $key => $value)
					{
						$key			 = $this->errorMapping($key);
						$errors[$key]	 = $value;
						$error_msg		 = $value[0];
					}
					if ($error_msg == 'Route not supported')
					{
						$error_type = 2;
					}
					else
					{
						$error_type = 4;
					}
					$status	 = 2;
					$result	 = ['response' => '', 'status' => 'error', 'response_type' => 'SEARCH', 'errors' => $errors];
				}
			}
			else
			{

				if ($error_msg == '')
				{
					$status		 = 2;
					$result		 = ['response' => '', 'status' => 'error', 'response_type' => 'SEARCH', 'errors' => ['Route' => ['0' => $error_msg]]];
					$error_msg	 = 'City not found';
					$error_type	 = 1;
				}
			}
			returnRespose:
			$time = Filter::getExecutionTime();
			$aatModel->updateResponse($result, null, $status, $error_type, $error_msg, $time);
			Logger::create("MMT SEARCH DONE.", CLogger::LEVEL_INFO);
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => $result
			]);
		});

		$this->onRest('req.post.holdBooking.render', function() {

			$data = Yii::app()->request->getParam('data');
			if ($data == "")
			{
				$data = Yii::app()->request->rawBody;
			}
			Logger::error($data);
			Logger::create("1");
			$data			 = CJSON::decode($data, true);
			$error_msg		 = '';
			$error_type		 = '';
			$pickupDate		 = DateTimeFormat::DatePickerToDate($data['tripDetails']['departureDate']);
			$pickupTime		 = $data['tripDetails']['pickupTime'] . ':00';
			$pickupDateTime	 = $pickupDate . ' ' . $pickupTime;
			$pickupLat		 = $data['tripDetails']['sourceLocation']['latitude'];
			$pickupLng		 = $data['tripDetails']['sourceLocation']['longitude'];
			$pickupPlaceId	 = $data['tripDetails']['sourceLocation']['placeId'];
			$dropLat		 = $data['tripDetails']['destinationLocation']['latitude'];
			$dropLng		 = $data['tripDetails']['destinationLocation']['longitude'];
			$dropPlaceId	 = $data['tripDetails']['destinationLocation']['placeId'];

			if ($data['tripDetails']['destinationCity'] == 'PROXY')
			{
				$point					 = $data['tripDetails']['destinationLocation'];
				$placeObj				 = new \Stub\common\Place();
				$placeObj->address		 = $point['address'];
				$placeObj->coordinates	 = new \Stub\common\Coordinates($point['latitude'], $point['longitude']);
				$placeObj->place_id		 = $point['placeId'];
				$latModel				 = LatLong::findNearest($placeObj);
				if ($latModel)
				{
					$dropCity = $latModel->ltg_city_id;
					goto skipDropCity;
				}

				$ctyModel = Cities::getByNearestBound($placeObj, 15);
				if ($ctyModel && $ctyModel->is_partial == 0)
				{
					$dropCity = $ctyModel->cty_id;
					goto skipDropCity;
				}
				$lModel = LatLong::getDetailsByPlace($placeObj);
				if ($lModel)
				{
					$dropCity = $lModel->ltg_city_id;
				}
			}
			else
			{
				$dropCity = MmtCity::model()->getCityId($data['tripDetails']['destinationCity']);
			}
			skipDropCity:
			$dropCityName = Cities::getName($dropCity);
			if ($data['tripDetails']['destinationLocation']['address'] != '')
			{
				$dropAddress = $data['tripDetails']['destinationLocation']['address'];
			}
			else
			{
				$dropAddress = $dropCityName;
			}

			if ($data['tripDetails']['sourceCity'] == 'PROXY')
			{
				$point					 = $data['tripDetails']['sourceLocation'];
				$placeObj				 = new \Stub\common\Place();
				$placeObj->address		 = $point['address'];
				$placeObj->coordinates	 = new \Stub\common\Coordinates($point['latitude'], $point['longitude']);
				$placeObj->place_id		 = $point['placeId'];
				$latModel				 = LatLong::findNearest($placeObj);
				if ($latModel)
				{
					$pickupCity = $latModel->ltg_city_id;
					goto skipPickupCity;
				}

				$ctyModel = Cities::getByNearestBound($placeObj);
				if ($ctyModel && $ctyModel->is_partial == 0)
				{
					$pickupCity = $ctyModel->cty_id;
					goto skipPickupCity;
				}
				$lModel = LatLong::getDetailsByPlace($placeObj);
				if ($lModel)
				{
					$pickupCity = $lModel->ltg_city_id;
				}
			}
			else
			{
				$pickupCity = MmtCity::model()->getCityId($data['tripDetails']['sourceCity']);
			}
			skipPickupCity:
			$pickupAddress	 = $data['tripDetails']['pickupAddress'];
			Logger::create("2");
			$bookingType	 = $data['tripDetails']['tripType'];
			$aatType		 = AgentApiTracking::TYPE_HOLD_BOOKING;
			$toMmtCode		 = $data['tripDetails']['destinationCity'];
			$fromMmtCode	 = $data['tripDetails']['sourceCity'];
			if ($dropCity != '' && $pickupCity != '')
			{
				if ($data['tripDetails']['returnDate'] != '')
				{
					$returnDate				 = DateTimeFormat::DatePickerToDate($data['tripDetails']['returnDate']);
					$returnTime				 = date('H:i:s', strtotime($data['tripDetails']['dropTime']));
					$returnDateTime			 = $returnDate . ' ' . $returnTime;
					$routeDuration			 = Route::model()->getRouteDurationbyCities($dropCity, $pickupCity);
					$pickupDateTime2		 = date('Y-m-d H:i:s', strtotime($returnDateTime . '- ' . $routeDuration . ' minute'));
					$pickupDate2			 = date('Y-m-d', strtotime($pickupDateTime2));
					$pickupTime2			 = date('H:i:s', strtotime($pickupDateTime2));
					$bmodel->bkg_return_date = $returnDateTime;
					// $bmodel->bkg_return_time = $returnTime;
				}
				if ($bookingType == 'RT')
				{
					$triptype	 = 2;
					$routes		 = [
						0	 => ['pickupDate'	 => $pickupDate, 'pickupTime'	 => $pickupTime,
							'dropCity'		 => $dropCity, 'dropPincode'	 => '', 'dropPlaceId'	 => $dropPlaceId,
							'dropAddress'	 => $dropAddress, 'dropLat'		 => $dropLat, 'dropLong'		 => $dropLng,
							'pickupCity'	 => $pickupCity, 'pickupLat'		 => $pickupLat, 'pickupLong'	 => $pickupLng,
							'pickupPlaceId'	 => $pickupPlaceId, 'pickupPincode'	 => '', 'pickupAddress'	 => $pickupAddress, ''],
						1	 => ['pickupDate'	 => $pickupDate2, 'pickupTime'	 => $pickupTime2,
							'dropCity'		 => $pickupCity, 'dropPincode'	 => '', 'dropLat'		 => $pickupLat,
							'dropLong'		 => $pickupLng, 'dropAddress'	 => $pickupAddress, 'dropPlaceId'	 => $pickupPlaceId,
							'pickupCity'	 => $dropCity, 'pickupLat'		 => $dropLat, 'pickupLong'	 => $dropLng,
							'pickupPlaceId'	 => $dropPlaceId, 'pickupPincode'	 => '', 'pickupAddress'	 => $dropAddress]];
				}
				if ($bookingType == 'OW')
				{
					$triptype	 = 1;
					$routes		 = [0 => ['pickupDate'	 => $pickupDate, 'pickupTime'	 => $pickupTime,
							'dropCity'		 => $dropCity, 'dropPincode'	 => '', 'dropAddress'	 => $dropAddress, 'dropLat'		 => $dropLat, 'dropLong'		 => $dropLng, 'dropPlaceId'	 => $dropPlaceId,
							'pickupCity'	 => $pickupCity, 'pickupPincode'	 => '', 'pickupAddress'	 => $pickupAddress, 'pickupLat'		 => $pickupLat, 'pickupLong'	 => $pickupLng, 'pickupPlaceId'	 => $pickupPlaceId]];
				}
				if ($bookingType == 'AT')
				{
					$triptype	 = 4;
					$routes		 = [0 => ['pickupDate'	 => $pickupDate, 'pickupTime'	 => $pickupTime,
							'dropCity'		 => $dropCity, 'dropPincode'	 => '', 'dropAddress'	 => $dropAddress, 'dropLat'		 => $dropLat, 'dropLong'		 => $dropLng, 'dropPlaceId'	 => $dropPlaceId,
							'pickupCity'	 => $pickupCity, 'pickupPincode'	 => '', 'pickupAddress'	 => $pickupAddress, 'pickupLat'		 => $pickupLat, 'pickupLong'	 => $pickupLng, 'pickupPlaceId'	 => $pickupPlaceId]];
				}
				$bmodel						 = Booking::model();
				$bmodel->bkg_booking_type	 = $triptype;
				$bmodel->bkg_pickup_date	 = $pickupDateTime;
				$routeArr					 = [];
				$rCount						 = count($routes);
				$bmodel->bkg_from_city_id	 = $routes[0]['pickupCity'];
				$bmodel->bkg_to_city_id		 = $routes[$rCount - 1]['dropCity'];
				$aatModel					 = AgentApiTracking::model()->add($aatType, $data, $bmodel, \Filter::getUserIP(), $toMmtCode, $fromMmtCode);
				foreach ($routes as $key => $value)
				{
					$routeModel							 = new BookingRoute();
					$routeModel->brt_from_city_id		 = $value['pickupCity'];
					$routeModel->brt_to_city_id			 = $value['dropCity'];
					$routeModel->brt_pickup_datetime	 = $value['pickupDate'] . " " . $value['pickupTime'];
					$routeModel->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($value['pickupDate'] . " " . $value['pickupTime']);
					$routeModel->brt_pickup_date_time	 = date('h:i A', strtotime($value['pickupDate'] . " " . $value['pickupTime']));
					$routeModel->brt_to_location		 = $value['dropAddress'];
					$routeModel->brt_from_location		 = $value['pickupAddress'];
					$routeModel->brt_to_pincode			 = $value['dropPincode'];
					$routeModel->brt_from_pincode		 = $value['pickupPincode'];
					$routeModel->brt_from_latitude		 = $value['pickupLat'];
					$routeModel->brt_to_latitude		 = $value['dropLat'];
					$routeModel->brt_from_longitude		 = $value['pickupLong'];
					$routeModel->brt_to_longitude		 = $value['dropLong'];
					$routeModel->brt_from_place_id		 = $value['pickupPlaceId'];
					$routeModel->brt_to_place_id		 = $value['dropPlaceId'];
					$routeArr[]							 = $routeModel;
				}

				if ($triptype == 1 && $data["fareDetails"]["approx_distance"] > 0)
				{
					$routeModel						 = $routeArr[0];
					$routeModel->brt_trip_distance	 = $data["fareDetails"]["approx_distance"];
					$result							 = Route::model()->populate($routeModel->brt_from_city_id, $routeModel->brt_to_city_id);
					if ($result['success'])
					{
						$rutModel						 = $result['model'];
						$routeModel->brt_trip_duration	 = $rutModel->rut_estm_time;
					}
				}

				if ($triptype == 2)
				{
					foreach ($routeArr as $routeModel)
					{
						$result = Route::model()->populate($routeModel->brt_from_city_id, $routeModel->brt_to_city_id);
						if ($result['success'])
						{
							$rutModel						 = $result['model'];
							$routeModel->brt_trip_duration	 = $rutModel->rut_estm_time;
							$routeModel->brt_trip_distance	 = $rutModel->rut_actual_distance > 0 ? $rutModel->rut_actual_distance : $rutModel->rut_estm_distance;
						}
					}
				}
				$bmodel->bookingRoutes	 = $routeArr;
				$bmodel->bkg_agent_id	 = 450;
				$bmodel->setScenario('apiroutes');
				$result					 = CActiveForm::validate($bmodel, null, false);
				Logger::create("3");
				if ($result == '[]')
				{
					$arr = [];
					foreach ($routes as $key => $val)
					{
						$arr[$key]['date']				 = $val['pickupDate'] . " " . $val['pickupTime'];
						$arr[$key]['drop_city']			 = $val['dropCity'];
						$arr[$key]['pickup_city']		 = $val['pickupCity'];
						$arr[$key]['drop_address']		 = $val['dropAddress'];
						$arr[$key]['drop_pincode']		 = $val['dropPincode'];
						$arr[$key]['pickup_address']	 = $val['pickupAddress'];
						$arr[$key]['pickup_pincode']	 = $val['pickupPincode'];
						$arr[$key]['pickup_lat']		 = $val['pickupLat'];
						$arr[$key]['pickup_long']		 = $val['pickupLong'];
						$arr[$key]['pickup_place_id']	 = $val['pickupPlaceId'];
						$arr[$key]['drop_lat']			 = $val['dropLat'];
						$arr[$key]['drop_long']			 = $val['dropLong'];
						$arr[$key]['drop_place_id']		 = $val['dropPlaceId'];
					}
					$arr	 = json_encode($arr);
					$arr	 = json_decode($arr);
					$result	 = $this->holdBooking($data, $arr, $triptype, $data['bookingId'], $data['fareDetails']['total_amount'], $dropCityName);
					$status	 = 1;
					Logger::create("4");

					if ($result['errors'] != '')
					{
						$errors = [];
						foreach ($result['errors'] as $key => $value)
						{
							$key			 = $this->errorMapping($key);
							$errors[$key]	 = $value;
							$error_msg		 = $value[0];
						}
						if ($error_msg == 'Prices have increased')
						{
							$error_type = 3;
						}
						else
						{
							$error_type = 4;
						}
						$result['errors']	 = $errors;
						$status				 = 2;
					}
				}
				else
				{
					$errors = [];
					foreach ($bmodel->getErrors() as $key => $value)
					{
						$key			 = $this->errorMapping($key);
						$errors[$key]	 = $value;
						$error_msg		 = $value[0];
					}
					if ($error_msg == 'Route not supported')
					{
						$error_type = 2;
					}
					else
					{
						$error_type = 4;
					}
					$status	 = 2;
					$result	 = ['booking_id' => $data['bookingId'], 'hold_key' => '', 'vendor_response' => ['message' => 'HOLD Failed: ' . $errors[$key][0], 'is_success' => false], 'response_type' => 'HOLD', 'status' => 'error', 'errors' => $errors];
				}
			}
			else
			{
				$status		 = 2;
				$result		 = ['booking_id' => $data['bookingId'], 'hold_key' => '', 'vendor_response' => ['message' => 'HOLD Failed: City not found', 'is_success' => false], 'response_type' => 'HOLD', 'status' => 'error', 'errors' => ['route' => ['0' => 'City not found']]];
				$error_msg	 = 'City not found';
				$error_type	 = 1;
			}
			Logger::create("5");
			$time = Filter::getExecutionTime();
			$aatModel->updateResponse($result, $result['hold_key'], $status, $error_type, $error_msg, $time);
			Logger::create("MMT HOLD DONE.", CLogger::LEVEL_INFO);

			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => $result
			]);
		});

		$this->onRest('req.post.hold.render', function() {
			return $this->hold();
		});
		$this->onRest('req.post.confirm.render', function() {
			return $this->confirm();
		});

		$this->onRest('req.post.confirmBooking.render', function() {
			$GLOBALS['enableProfiling']	 = false;
			$data						 = Yii::app()->request->getParam('data');
			if ($data == "")
			{
				$data = Yii::app()->request->rawBody;
			}
			Logger::create("1");
			$data = CJSON::decode($data, true);
			if ($data && $data['holdKey'] > 0)
			{
				$model = Booking::model()->findByPk($data['holdKey']);
			}
			if ($model)
			{
				$aatType	 = AgentApiTracking::TYPE_CREATE_BOOKING;
				$fromMmtCode = $data['tripDetails']['sourceCity'];
				$toMmtCode	 = $data['tripDetails']['destinationCity'];
				$aatModel	 = AgentApiTracking::model()->add($aatType, $data, $model, \Filter::getUserIP(), $toMmtCode, $fromMmtCode);
				$transaction = DBUtil::beginTransaction();
				try
				{
					$model->bkg_agent_ref_code = $data['mmtBookingId'];
					if ($model->bkg_status == 1 && $model->bkg_agent_id == 450)
					{
						$logType	 = UserInfo::TYPE_SYSTEM;
						//$success = $model->confirmBooking($logType);
						/* @var $returnSet ReturnSet */
						$returnSet	 = $model->confirm(true, false);
						$success	 = $returnSet->isSuccess();
						if ($success)
						{
							$model->refresh();
							$scvVctId = SvcClassVhcCat::model()->getCatIdBySvcid($model->bkg_vehicle_type_id);
							if ($scvVctId == VehicleCategory::SUV_ECONOMIC || $scvVctId == VehicleCategory::ASSURED_INNOVA_ECONOMIC)
							{
								$splRemark								 = 'Require vehicle with Carrier';
								$model->bkgAddInfo->bkg_spl_req_carrier	 = 1;
								if ($model->save() && $model->bkgAddInfo->save())
								{
									$eventId = BookingLog::REMARKS_ADDED;
									$remark	 = $splRemark;
									BookingLog::model()->createLog($model->bkg_id, $remark, UserInfo::getInstance(), $eventId);
								}
							}
							$model->bkgTrack = BookingTrack::model()->sendTripOtp($model->bkg_id, $sendOtp		 = false);
							if ($model->bkgTrack != '')
							{
								$model->bkgTrack->save();
							}

							$amount = $data['advancePaid'] | 0; //Credit added by agent;
							if (isset($data['advance_amount_paid']) && $data['advance_amount_paid'] > 0)
							{
								$amount = $data['advance_amount_paid'];
							}

							if ($amount > 0)
							{
								$desc			 = "Credits used";
								$bankLedgerID	 = PaymentType::model()->ledgerList(PaymentType::TYPE_AGENT_CORP_CREDIT);
								$model->updateAdvance($amount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, UserInfo::getInstance(), null, "Partner Wallet Used");
							}
						}
					}
					if ($success)
					{
						$status	 = 1;
						$result	 = ['booking_id' => $data['mmtBookingId'], 'confirm_status' => 'BOOKED', 'vendor_response' => ['message' => 'BOOKING CONFIRM  successfully', 'is_success' => true], 'response_type' => 'CONFIRM', 'status' => 'success'];
					}
					else
					{
						$status	 = 2;
						$result	 = ['booking_id' => $data['mmtBookingId'], 'confirm_status' => 'FAILED', 'vendor_response' => ['message' => 'CONFIRM FAILED', 'is_success' => false], 'response_type' => 'CONFIRM', 'status' => 'error'];
					}

					DBUtil::commitTransaction($transaction);
				}
				catch (Exception $e)
				{
					DBUtil::rollbackTransaction($transaction);
					$status	 = 2;
					$result	 = ['booking_id' => $data['mmtBookingId'], 'confirm_status' => 'FAILED', 'vendor_response' => ['message' => 'CONFIRM FAILED (' . $e->getMessage() . ')', 'is_success' => false], 'response_type' => 'CONFIRM', 'status' => 'error'];
				}
				$time = Filter::getExecutionTime();
				$aatModel->updateResponse($result, $model->bkg_id, $status, $error_type, $error_msg, $time);

				Logger::create("3");
			}
			else
			{
				return false;
			}
			Logger::create("MMT CONFIRM DONE.", CLogger::LEVEL_INFO);
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => $result
			]);
		});


		//////////////////////////Review/////////////////////////////////////////
		//Booking rating and reviews
		$this->onRest('req.post.review.render', function() {
			$process_sync_data	 = '{
                                    "cabBookingReviews": [
                                    {
                                    "mmtid": "NC771473770029354",
                                    "createdOn": 1523770762000,
                                    "onTime": false,
                                    "overAllRating": 4,
                                    "driverBehavior": 3,
                                    "cabCondition": 4,
                                    "cabCleanliness": 2,
                                    "reviewOnTime": null,
                                    "reviewDriverBehavior": null,
                                    "reviewCabCondition": null,
                                    "reviewOverall": "Overallanicetrip",
                                    "cabNumber": null,
                                    "cabType": "HATCHBACK",
                                    "cabModelName": "Indica, Swift or similar"
                                    },
                                    {
                                    "mmtid": "NC771473770029354",
                                    "createdOn": 1523770762000,
                                    "onTime": false,
                                    "overAllRating": 2,
                                    "driverBehavior": 1,
                                    "cabCondition": 2,
                                    "cabCleanliness": 2,
                                    "reviewOnTime": null,
                                    "reviewDriverBehavior": "Driverwasveryrude",
                                    "reviewCabCondition": null,
                                    "reviewOverall": "Overallbadexperiencewiththistrip",
                                    "cabNumber": null,
                                    "cabType": "HATCHBACK",
                                    "cabModelName": "Indica, Swift or similar"
                                    }
                                    ]
                                    }';
			$data				 = CJSON::decode($process_sync_data, true);
			foreach ($data as $val)
			{
				foreach ($val as $val2)
				{
					$booking						 = Booking::model()->find('bkg_agent_ref_code=:ref', ['ref' => $val2['mmtid']]);
					$rating							 = new Ratings();
					$rating->rtg_booking_id			 = $booking->bkg_id;
					$rating->rtg_driver_ontime		 = $val2['onTime'];
					$rating->rtg_customer_overall	 = $val2['overAllRating'];
					$rating->rtg_driver_softspokon	 = $val2['driverBehavior'];
					$rating->rtg_car_good_cond		 = $val2['cabCondition'];
					$rating->rtg_car_clean			 = $val2['cabCleanliness'];
					$data							 = [
						"mmtid"					 => $val2['mmtid'],
						"createdOn"				 => $val2['createdOn'],
						"reviewDriverBehavior"	 => $val2['reviewDriverBehavior'],
						"reviewCabCondition"	 => $val2['reviewCabCondition'],
						"reviewOverall"			 => $val2['reviewOverall'],
						"cabNumber"				 => $val2['cabNumber'],
						"cabType"				 => $val2['cabType'],
						"cabModelName"			 => $val2['cabModelName']
					];
					$reviewdata						 = json_encode($data);
					$rating->rtg_customer_review	 = $reviewdata;

					if ($rating->save())
					{
						$success = true;
						$message = "Data Inserted Successfully";
						$errors	 = null;
					}
					else
					{
						$message = "Data error ";
						$errors	 = "No records found";
					}
				}
			}
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'	 => $success,
							'errors'	 => $errors,
							'message'	 => $message,
							'data'		 => $data
						)
			]);
		});



		//////////////////////////////////////////////////////////////////////////////



		$this->onRest('req.post.cancelBooking.render', function() {
			$data = Yii::app()->request->getParam('data');

			if ($data == "")
			{
				$data = Yii::app()->request->rawBody;
			}
			$data			 = CJSON::decode($data, true);
			$result			 = ['vendor_refund_amount' => '', 'booking_id' => $data['mmtBookingId'], 'hold_key' => $data['holdKey'], 'vendor_response' => ['is_success' => false, 'message' => 'CANCEL FAILED', 'errors' => 'Booking not found'], 'response_type' => 'CANCEL', 'status' => 'error'];
			$bookingId		 = $data['holdKey'];
			$reason			 = $data['cancellationReason'];
			$reasonId		 = 4;
			$bookingModel	 = Booking::model()->findByPk($bookingId);
			$aatType		 = AgentApiTracking::TYPE_CANCEL_BOOKING;
			$aatModel		 = AgentApiTracking::model()->add($aatType, $data, $bookingModel, \Filter::getUserIP());
			if ($bookingModel != '' && $bookingModel->bkg_agent_id == 450 && !in_array($bookingModel->bkg_status, [1, 2, 3, 5]))
			{
				$strCancel = 'Booking already cancelled';
				if ($bookingModel->bkg_status == 6 || $bookingModel->bkg_status == 7)
				{
					$strCancel = "Booking already marked as completed";
				}
				$result	 = ['vendor_refund_amount' => '', 'booking_id' => $data['mmtBookingId'], 'hold_key' => $data['holdKey'], 'vendor_response' => ['is_success' => false, 'message' => "CANCEL FAILED: $strCancel"], 'response_type' => 'CANCEL', 'status' => 'error', 'errors' => 'Booking already cancelled or completed'];
				$status	 = 2;
			}
			else
			{
				$diff = ((strtotime($bookingModel->bkg_pickup_date) - time()));
				if ($bookingModel != '' && $bookingModel->bkg_agent_id == 450)
				{
					try
					{
						$tripTimeDiff	 = Booking::model()->getPickupDifferencebyBkgid($bookingModel->bkg_id);
						$createTimeDiff	 = $bookingModel->getCreateDifferencebyBkgid($bookingModel->bkg_id);
						$totalAdvance	 = PaymentGateway::model()->getTotalAdvance($bookingModel->bkg_id);
						$success		 = $this->canbooking($bookingModel->bkg_id, $reason, $reasonId);
						if ($bookingModel->bkg_agent_id > 0)
						{
							$agentModel = Agents::model()->find('agt_id=:id', ['id' => $bookingModel->bkg_agent_id]);
							if ($agentModel->agt_cancel_rule != '')
							{
								$rule = $agentModel->agt_cancel_rule;
							}
						}
						if ($bookingModel->bkg_agent_id == 450)
						{
							$refundArr = BookingPref::model()->calculateRefundMMT($tripTimeDiff, $bookingModel->bkgInvoice->bkg_total_amount, $totalAdvance, $rule, $createTimeDiff, $bookingModel->bkg_id);
						}
						else
						{
							$refundArr = BookingPref::model()->calculateRefund($tripTimeDiff, $bookingModel->bkgInvoice->bkg_total_amount, $totalAdvance, $rule, $bookingModel->bkg_id);
						}
						if ($diff < 0)
						{
							$bookingModel->bkgPref->bkg_account_flag = 1;
							$bookingModel->bkg_status				 = 9;
							$accountingFlagSet						 = true;
							$bookingModel->save();
							$bookingModel->bkgPref->save();
						}
						if ($accountingFlagSet)
						{
							$eventId						 = BookingLog::SET_ACCOUNTING_FLAG;
							$desc							 = "Operator cancel the trip after pickup time has passed, please review";
							$params['blg_booking_status']	 = $bookingModel->bkg_status;
							BookingLog::model()->createLog($bookingModel->bkg_id, $desc, UserInfo::model(), $eventId, $oldModel, $params);
						}
						$refundAmount		 = ($refundArr['refund'] > 0) ? round($refundArr['refund']) : 0;
						$cancellationCharge	 = $refundArr['cancelCharge'];
						if ($reasonId != "" && $success)
						{
							$status	 = 1;
							$result	 = ['vendor_refund_amount' => $refundAmount, 'booking_id' => $data['mmtBookingId'], 'hold_key' => $data['holdKey'], 'vendor_response' => ['is_success' => true, 'message' => 'CANCEL Booking successful'], 'response_type' => 'CANCEL', 'status' => 'success'];
						}
					}
					catch (Exception $e)
					{
						
					}
				}
			}
			$time = Filter::getExecutionTime();
			$aatModel->updateResponse($result, $bookingModel->bkg_id, $status, $error_type, $error_msg, $time);
			Logger::create("MMT CANCEL DONE.", CLogger::LEVEL_INFO);
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => $result
			]);
		});

		$this->onRest('req.post.getDetails.render', function() {
			$data = Yii::app()->request->getParam('data');

			if ($data == "")
			{
				$data = Yii::app()->request->rawBody;
			}
			$data		 = CJSON::decode($data, true);
			$aatType	 = AgentApiTracking::TYPE_GET_DETAILS;
			$status		 = 2;
			$result		 = ['booking_id' => $data['mmtBookingId'], 'hold_key' => $data['holdKey'], 'booking_status' => 'INVALID', 'trip_details' => [], 'driver_details' => [], 'response_type' => 'BOOKING_DETAILS', 'status' => 'error'];
			$model		 = Booking::model()->findByPk($data['holdKey']);
			$aatModel	 = AgentApiTracking::model()->add($aatType, $data, $model, \Filter::getUserIP());
			$cModel		 = $model->bkgBcb;
			$rModel		 = $model->bookingRoutes;
			if ($model != '' && $cModel != '' && $rModel != '' && $model->bkg_agent_id == 450)
			{
				if ($model->bkg_status == 1)
				{
					$booking_status = 'HOLD';
				}
				if ($model->bkg_status == 2 || $model->bkg_status == 3 || $model->bkg_status == 5)
				{
					$booking_status = 'BOOKED';
				}
				if ($model->bkg_status == 6 || $model->bkg_status == 7)
				{
					$booking_status = 'COMPLETED';
				}
				if ($model->bkg_status == 9)
				{
					$booking_status = 'CANCELLED';
				}
				if ($model->bkg_status == 8)
				{
					$booking_status = 'DELETED';
				}
				$fromCity	 = MmtCity::model()->getMmtCode($rModel[0]->brt_from_city_id);
				$toCity		 = MmtCity::model()->getMmtCode($rModel[0]->brt_to_city_id);
				if ($model->bkg_booking_type == 1)
				{
					$tripType	 = 'OW';
					$returnDate	 = '';
				}
				if ($model->bkg_booking_type == 4)
				{
					$tripType	 = 'AT';
					$returnDate	 = '';
				}
				if ($model->bkg_booking_type == 2)
				{
					$tripType	 = 'RT';
					$returnDate	 = DateTimeFormat::DateTimeToDatePicker($model->bkg_return_date);
				}
				$departureDate	 = DateTimeFormat::DateTimeToDatePicker($model->bkg_pickup_date);
				$pickupTime		 = date('H:i', strtotime($model->bkg_pickup_date));
				$category		 = $this->getCabType($model->bkg_vehicle_type_id);
				$advance		 = $model->bkgInvoice->bkg_advance_amount;
				$totalAmount	 = $model->bkgInvoice->bkg_total_amount;
				if ($cModel->bcb_driver_name != '')
				{
					$driver_name = $cModel->bcb_driver_name;
				}
				else
				{
					$driver_name = '';
				}
				if ($cModel->bcb_driver_phone != '')
				{
					$driver_mobile = $cModel->bcb_driver_phone;
				}
				else
				{
					$driver_mobile = '';
				}
				if ($cModel->bcb_cab_number != '')
				{
					$cab_number = $cModel->bcb_cab_number;
				}
				else
				{
					$cab_number = '';
				}
				$status	 = 1;
				$result	 = ['booking_id' => $data['mmtBookingId'], 'hold_key' => $data['holdKey'], 'booking_status' => $booking_status, 'trip_details' => ['fromCity' => $fromCity, 'toCity' => $toCity, 'tripType' => $tripType, 'departureDate' => $departureDate, 'pickupTime' => $pickupTime, 'returnDate' => $returnDate, 'category' => $category, 'advance' => $advance, 'totalAmount' => $totalAmount], 'driver_details' => ['driver_name' => $driver_name, 'driver_mobile' => $driver_mobile, 'cab_number' => $cab_number], 'response_type' => 'BOOKING_DETAILS', 'status' => 'success'];
			}
			$time = Filter::getExecutionTime();
			$aatModel->updateResponse($result, $result['hold_key'], $status, $error_type, $error_msg, $time);
			Logger::create("MMT DETAILS DONE.", CLogger::LEVEL_INFO);
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => $result
			]);
		});

		$this->onRest('req.post.updateBooking.render', function() {
			$data = Yii::app()->request->getParam('data');
			if ($data == "")
			{
				$data = Yii::app()->request->rawBody;
			}
			$data		 = CJSON::decode($data, true);
			$aatType	 = AgentApiTracking::TYPE_UPDATE_BOOKING;
			$status		 = 2;
			$result		 = ['booking_id' => $data['mmtBookingId'], 'hold_key' => $data['holdKey'], 'response_type' => 'UPDATE_BOOKING', 'vendor_response' => ['message' => 'UPDATE Failed: Booking not found', 'is_success' => false], 'status' => 'error', 'errors' => 'Booking not found'];
			$model		 = Booking::model()->findByPk($data['holdKey']);
			$oldModel	 = clone $model;
			$oldData	 = Booking::model()->getDetailsbyId($data['holdKey']);
			$cModel		 = $model->bkgBcb;
			$rModel		 = $model->bookingRoutes;
			$aatModel	 = AgentApiTracking::model()->add($aatType, $data, $model, \Filter::getUserIP());
			if ($model != '' && $cModel != '' && $rModel != '' && $model->bkg_agent_id == 450)
			{
				if ($data['customerRequest'] != '')
				{
					$count = 0;
					foreach ($data['customerRequest'] as $value)
					{
						if ($count == 0)
						{
							if ($model->bkg_instruction_to_driver_vendor != '')
							{
								$previousString = $model->bkg_instruction_to_driver_vendor . ', ';
							}
							else
							{
								$previousString = '';
							}
							$model->bkg_instruction_to_driver_vendor = $previousString . $value;
						}
						else
						{
							$model->bkg_instruction_to_driver_vendor = $model->bkg_instruction_to_driver_vendor . ', ' . $value;
						}
						$count = $count + 1;
					}
				}
				if ($data['pickupLocation']['address'] != '')
				{
					$model->bkg_pickup_address		 = $data['pickupLocation']['address'];
					$rModel[0]->brt_from_location	 = $data['pickupLocation']['address'];
					if ($model->bkg_booking_type == 2)
					{
						$model->bkg_drop_address	 = $data['pickupLocation']['address'];
						$rModel[1]->brt_to_location	 = $data['pickupLocation']['address'];
					}
				}
				if ($data['pickupTime'] != '')
				{
					//$model->bkg_pickup_time         = $data['pickupTime'] . ':00';
					$model->bkg_pickup_date			 = date('Y-m-d', strtotime($model->bkg_pickup_date)) . ' ' . $data['pickupTime'] . ':00';
					$rModel[0]->brt_pickup_datetime	 = $model->bkg_pickup_date;
					$model->bkg_pickup_date_time	 = '';
				}
				if ($data['mobileNumber'] != '')
				{
					$model->bkgUserInfo->bkg_contact_no = $data['mobileNumber'];
				}
				if ($data['emailId'] != '')
				{
					$model->bkgUserInfo->bkg_user_email = $data['emailId'];
				}
				if ($model->save() && $model->bkgUserInfo->save() && $rModel[0]->save())
				{
					if ($model->bkg_booking_type == 2)
					{
						$rModel[1]->save();
					}
					$status	 = 1;
					$result	 = ['booking_id' => $data['mmtBookingId'], 'hold_key' => $data['holdKey'], 'response_type' => 'UPDATE_BOOKING', 'vendor_response' => ['message' => 'UPDATE Booking successful', 'is_success' => true], 'status' => 'success', 'errors' => ''];
				}
				else
				{
					$errors = [];
					foreach ($model->getErrors() as $key => $value)
					{
						$key			 = $this->errorMapping($key);
						$errors[$key]	 = $value;
					}
					foreach ($model->bkgUserInfo->getErrors() as $key => $value)
					{
						$key			 = $this->errorMapping($key);
						$errors[$key]	 = $value;
					}
					$status	 = 2;
					$result	 = ['booking_id' => $data['mmtBookingId'], 'hold_key' => $data['holdKey'], 'response_type' => 'UPDATE_BOOKING', 'vendor_response' => ['message' => 'UPDATE Failed: ' . $errors[$key][0], 'is_success' => false], 'status' => 'error', 'errors' => $errors];
				}
				if ($data['includedKms'] != '' && $data['revisedTotal'] != '' && $result['status'] == 'success')
				{
					if ($model->bkg_trip_distance < $data['includedKms'])
					{
						$diffKMS					 = $data['includedKms'] - $model->bkg_trip_distance;
						$extraFare					 = round($diffKMS * $model->bkgInvoice->bkg_rate_per_km_extra);
						$model->bkg_trip_distance	 = $data['includedKms'];
						if ($model->bkg_booking_type == 1)
						{
							$rModel[0]->brt_trip_distance = $rModel[0]->brt_trip_distance + $diffKMS;
						}
						else
						{
							$rModel[0]->brt_trip_distance	 = $rModel[0]->brt_trip_distance + round($diffKMS / 2);
							$rModel[1]->brt_trip_distance	 = $rModel[1]->brt_trip_distance + round($diffKMS / 2);
						}
						$model->bkgInvoice->bkg_base_amount = $model->bkgInvoice->bkg_base_amount + $extraFare;
					}
					$model->bkgInvoice->populateAmount(true, false, true, false, $model->bkg_agent_id);
					$effectiveTotalAmount = (int) round(0.98 * $model->bkgInvoice->bkg_total_amount);
					if ($effectiveTotalAmount <= $data['revisedTotal'])
					{
						$difference = $model->bkgInvoice->bkg_total_amount - $data['revisedTotal'];
						if ($difference > 0)
						{
							$newDifference						 = ((100 - Yii::app()->params['gst']) / 100) * $difference;
							$model->bkgInvoice->bkg_base_amount	 = (int) round($model->bkgInvoice->bkg_base_amount - $newDifference);
							$model->bkgInvoice->populateAmount(true, false, true, false, $model->bkg_agent_id);
							$model->bkgInvoice->calculateVendorAmount();
						}
						if ($difference < 0)
						{
							$newDifference						 = ((100 - Yii::app()->params['gst']) / 100) * (-1) * $difference;
							$model->bkgInvoice->bkg_base_amount	 = (int) round($model->bkgInvoice->bkg_base_amount + $newDifference);
							$model->bkgInvoice->populateAmount(true, false, true, true, $model->bkg_agent_id);
						}
						if ($model->save() && $model->bkgInvoice->save() && $rModel[0]->save())
						{
							if ($model->bkg_booking_type == 2)
							{
								$rModel[1]->save();
							}
							$status	 = 1;
							$result	 = ['booking_id' => $data['mmtBookingId'], 'hold_key' => $data['holdKey'], 'response_type' => 'UPDATE_BOOKING', 'vendor_response' => ['message' => 'UPDATE Booking successful', 'is_success' => true], 'status' => 'success', 'errors' => ''];
						}
					}
					else
					{
						$status	 = 2;
						$result	 = ['booking_id' => $data['mmtBookingId'], 'hold_key' => $data['holdKey'], 'response_type' => 'UPDATE_BOOKING', 'vendor_response' => ['message' => 'UPDATE Failed: Price not matched', 'is_success' => false], 'status' => 'error', 'errors' => 'Price not matched'];
					}
				}
				$hr24BeforePick	 = date('Y-m-d H:i:s', strtotime($model->bkg_pickup_date . '- ' . 24 . ' hour'));
				$d1				 = new DateTime();
				$d2				 = new DateTime($hr24BeforePick);
				if ($data['amountPaid'] != '' && $d1 < $d2 && $result['status'] == 'success')
				{
					$currentAdvanceAmount	 = $data['amountPaid'] - $model->bkgInvoice->bkg_advance_amount;
					$amount					 = $currentAdvanceAmount | 0;
					if ($amount > 0)
					{
						$agtcomm = $model->updateAdvance($amount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, UserInfo::getInstance(), null, "Partner Wallet Used");
						$status	 = 1;
						$result	 = ['booking_id' => $data['mmtBookingId'], 'hold_key' => $data['holdKey'], 'response_type' => 'UPDATE_BOOKING', 'vendor_response' => ['message' => 'UPDATE Booking successful', 'is_success' => true], 'status' => 'success', 'errors' => ''];
					}
					else
					{
						$status	 = 2;
						$result	 = ['booking_id' => $data['mmtBookingId'], 'hold_key' => $data['holdKey'], 'response_type' => 'UPDATE_BOOKING', 'vendor_response' => ['message' => 'UPDATE Failed: Amount should be greater than zero', 'is_success' => false], 'status' => 'error', 'errors' => 'Amount should be greater than zero'];
					}
				}
				if ($data['amountPaid'] != '' && $d1 >= $d2 && $result['status'] == 'success')
				{
					$status	 = 2;
					$result	 = ['booking_id' => $data['mmtBookingId'], 'hold_key' => $data['holdKey'], 'response_type' => 'UPDATE_BOOKING', 'vendor_response' => ['message' => 'UPDATE Failed: Advance update should be minimum 24 hours before the pickup time', 'is_success' => false], 'status' => 'error', 'errors' => 'Advance update should be minimum 24 hours before the pickup time'];
				}
				$newData			 = Booking::model()->getDetailsbyId($model->bkg_id);
				$getDifference		 = array_diff_assoc($newData, $oldData); // $this->getDifference($oldData, $newData);
				$getOldDifference	 = array_diff_assoc($oldData, $newData);
				$changesForVendor	 = $this->getModificationMSG($getDifference, 'vendor');
				$changesForLog		 = " Old Values: " . $this->getModificationMSG($getOldDifference, 'log');
				$newChangesForLog	 = " New Values: " . $this->getModificationMSG($getDifference, 'log');
				$logDesc			 = "Booking modified | ";
				$eventid			 = BookingLog::BOOKING_MODIFIED;
				$desc				 = $logDesc . $changesForLog . $newChangesForLog;
				$bkgid				 = $model->bkg_id;
				$userInfo			 = UserInfo::getInstance();
				$bookingID			 = $model->bkg_booking_id;
				if ($status != 2)
				{
					BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);
				}

				$vndModel	 = Vendors::model()->findByPk($cModel->bcbVendor->vnd_id);
				$phone		 = ContactPhone::getContactPhoneById($vndModel->vnd_contact_id);

				if ($phone != '' && trim($changesForVendor) != '' && $cModel->bcb_vendor_id != '' && $model->bkg_status > 2)
				{
					$logType		 = UserInfo::TYPE_SYSTEM;
					$msgCom			 = new smsWrapper();
					$msgCom->informChangesToVendor('91', $phone, $bookingID, $changesForVendor, $logType);
					$tripStatus		 = $cModel->getLowestBookingStatusByTrip($cModel->bcb_id, $cModel->bcb_pending_status);
					$tripBkgStatus	 = 0;
					if ($tripStatus)
					{
						$tripBkgStatus = $tripStatus;
					}
					$payLoadData = ['tripId' => $cModel->bcb_id, 'Status' => $tripBkgStatus, 'EventCode' => Booking::CODE_MODIFIED];
					$success	 = AppTokens::model()->notifyVendor($cModel->bcb_vendor_id, $payLoadData, $changesForVendor, $model->bkg_booking_id . " details has been modified.");
				}
			}
			$time = Filter::getExecutionTime();
			$aatModel->updateResponse($result, $model->bkg_id, $status, $error_type, $error_msg, $time);
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => $result
			]);
		});


		$this->onRest('req.post.quote.render', function() {
			return $this->getQuote();
		});
	}

	public function canbooking($id, $reason1, $reasonId)
	{
		$reason		 = trim($reason1);
		$model		 = Booking::model()->findByPk($id);
		$oldModel	 = clone $model;
		$userInfo	 = UserInfo::getInstance();
		$success	 = Booking::model()->canBooking($id, $reason, $reasonId, $userInfo);
		if ($success)
		{
			$bkgid	 = $success;
			$desc	 = "Booking cancelled by agent.";
			$eventid = BookingLog::BOOKING_CANCELLED;
			BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);
			return true;
		}
		return false;
	}

	public function errorMapping($key)
	{
		if ($key == 'bkg_booking_type')
		{
			$key = 'triptype';
		}
		if ($key == 'bkg_instruction_to_driver_vendor')
		{
			$key = 'customerRequest';
		}
		if ($key == 'bkg_id' || $key == 'bkg_booking_id')
		{
			$key = 'bookingId';
		}
		if ($key == 'bkg_from_city_id' || $key == 'brt_from_city_id')
		{
			$key = 'pickupCity';
		}
		if ($key == 'bkg_to_city_id' || $key == 'brt_to_city_id')
		{
			$key = 'dropCity';
		}
		if ($key == 'bkg_contact_no')
		{
			$key = 'customerMobile';
		}
		if ($key == 'bkg_alt_contact_no')
		{
			$key = 'customerAlternateMobile';
		}
		if ($key == 'bkg_user_email')
		{
			$key = 'customerEmail';
		}
		if ($key == 'bkg_pickup_address' || $key == 'brt_from_location')
		{
			$key = 'pickupAddress';
		}
		if ($key == 'bkg_drop_address' || $key == 'brt_to_location')
		{
			$key = 'dropAddress';
		}
		if ($key == 'bkg_pickup_date' || $key == 'brt_pickup_datetime')
		{
			$key = 'pickupDate';
		}
		/*  if ($key == 'bkg_pickup_time')
		  {
		  $key = 'pickupTime';
		  } */
		if ($key == 'bkg_vehicle_type_id')
		{
			$key = 'cabModel';
		}
		if ($key == 'bkg_user_fname')
		{
			$key = 'firstName';
		}
		if ($key == 'bkg_user_lname')
		{
			$key = 'lastName';
		}
		if ($key == 'bkg_route_id')
		{
			$key = 'route';
		}
		return $key;
	}

	public function cab_list($data, $triptype, $approxDistance = 0)
	{
		$route = [];
		foreach ($data as $key => $val)
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
			if ($val->distance > 0)
			{
				$routeModel->brt_trip_distance = $val->distance;
			}
			$route[] = $routeModel;
		}
		$partnerId = Yii::app()->user->getId();

		$quoteM					 = new Quote();
		$quoteM->routes			 = $route;
		$quoteM->tripType		 = $triptype;
		$quoteM->partnerId		 = $partnerId;
		$quoteM->quoteDate		 = date("Y-m-d H:i:s");
		$quoteM->pickupDate		 = $data[0]->date;
		$quoteM->sourceQuotation = Quote::Platform_Agent;
		$quoteM->minRequiredKms	 = $approxDistance;
		$quoteM->setCabTypeArr();
		Quote::$updateCounter	 = true;

		Logger::create("Quote Initialized: " . json_encode($quoteM), CLogger::LEVEL_INFO);
		$quoteData		 = $quoteM->getQuote([1, 2, 3], true, false);
		$cabArr			 = [VehicleCategory::COMPACT_ECONOMIC, VehicleCategory::SEDAN_ECONOMIC, VehicleCategory::SUV_ECONOMIC];
		$cabArrAirport	 = [VehicleCategory::COMPACT_ECONOMIC, VehicleCategory::SUV_ECONOMIC, VehicleCategory::SEDAN_ECONOMIC];
		if ($triptype == 4)
		{
			$cabArr = $cabArrAirport;
		}

		$result = [];
		if (count($quoteData) == 0)
		{
			return false;
		}

		foreach ($cabArr as $k => $cab)
		{
			$additionalTime	 = 0;
			$fullPayment	 = false;
			if (!isset($quoteData[$cab]) || !$quoteData[$cab]->success)
			{
				continue;
			}
			if (in_array($cab, [VehicleCategory::ASSURED_DZIRE_ECONOMIC, VehicleCategory::ASSURED_INNOVA_ECONOMIC]) && $quoteData[$cab]->routeRates->isTollIncluded != 1)
			{
				continue;
			}
			else if (in_array($cab, [VehicleCategory::ASSURED_DZIRE_ECONOMIC, VehicleCategory::ASSURED_INNOVA_ECONOMIC]))
			{
				$fullPayment = true;
			}
			//$vhtmodel		 = VehicleTypes::model()->getCarModel($cab, 1);
			$vhtmodel		 = SvcClassVhcCat::getVctSvcList("detail", 0, 0, $cab);
			$routeDistance	 = $quoteData[$cab]->routeDistance;
			$routeDuration	 = $quoteData[$cab]->routeDuration;
			$routeRates		 = $quoteData[$cab]->routeRates;
			$priceRule		 = $quoteData[$cab]->priceRule;
			$graceTime		 = ($routeDuration->garageTimeStart > 30) ? $routeDuration->garageTimeStart - 30 : 0;
			//$CabList[$ctr]['advanceRequired'] = ($startDistance > 60) ? 1 : 0;
			// $CabList[$ctr]['fullPayment'] = ($startDistance > 120) ? 1 : 0;
			$resData		 = [
				'graceTime'			 => $graceTime + $additionalTime,
				'fullPayment'		 => ($routeDuration->garageTimeStart > 120 || $fullPayment) ? 1 : 0,
				'nightAllowance'	 => $routeRates->driverNightAllowance,
				'nightCharges'		 => $priceRule->prr_night_driver_allowance,
				'state_tax'			 => $routeRates->stateTax | 0,
				'toll_tax'			 => $routeRates->tollTaxAmount | 0,
				'min_chargeable'	 => $routeDistance->quotedDistance, // $quote['routeData']['quoted_km'],
				'km_per_day'		 => $priceRule->prr_min_km, //$quote['routeData']['rateConfig']['perDayMinimumKM'],
				'days'				 => $routeDuration->calendarDays, //$quote['routeData']['days']['calendarDays'],
				'total_min'			 => $routeDuration->totalMinutes, //$quote[$cab]['total_min'],
				'cab'				 => $cab,
				'cab_type_id'		 => $cab,
				'actual_amt'		 => $routeRates->totalAmount - $routeRates->gst,
				'base_amt'			 => $routeRates->baseAmount,
				'gozo_base_amt'		 => $routeRates->baseAmount,
				'service_tax'		 => $routeRates->gst,
				'total_amt'			 => $routeRates->totalAmount,
				'quote_km'			 => $routeDistance->quotedDistance,
				'total_day'			 => $routeDuration->durationInWords,
				'km_rate'			 => $routeRates->ratePerKM, //   $quote[$cab]['km_rate'],
				'addional_km'		 => 0,
				'total_km'			 => $routeDistance->quotedDistance,
				'route'				 => $quoteM->routeDistance->routeDesc,
				'error'				 => 0,
				'image'				 => $vhtmodel[vct_image],
				'capacity'			 => $vhtmodel[vct_capacity],
				'bag_capacity'		 => $vhtmodel[vct_small_bag_capacity],
				'big_bag_capacity'	 => $vhtmodel[vct_big_bag_capacity],
				'cab_model'			 => $vhtmodel[vct_desc],
				'startTripDate'		 => $routeDuration->fromDate,
				'endTripDate'		 => $routeDuration->toDate, //$quote['routeData']['endTripDate'],
				'driverAllowance'	 => $routeRates->driverAllowance,
				'tolltax'			 => $routeRates->isTollIncluded | 0,
				'statetax'			 => $routeRates->isStateTaxIncluded | 0,
				'parkingInc'		 => $routeRates->isParkingIncluded | 0,
				'servicetax'		 => $routeRates->gst, // $quote[$i]['servicetax'],
				'startTripCity'		 => $quoteM->routes[0]->brt_from_city_id,
				'endTripCity'		 => $quoteM->routes[count($quoteM->routes) - 1]->brt_to_city_id,
				'cab_id'			 => $vhtmodel[scv_id], //$quote[$i]['cab_id']
			];
			//echo '<pre>';
			//print_r($result);
			//echo '<pre>';
			//exit();
			$result[$k]		 = $resData;
		}
		return $result;
	}

	public function getCabModel($typeId = 0)
	{
		$arrModel = [
			VehicleCategory::COMPACT_ECONOMIC			 => 'Indica, Swift or similar',
			VehicleCategory::SUV_ECONOMIC				 => 'Innova, Tavera or similar',
			VehicleCategory::SEDAN_ECONOMIC				 => 'Dzire, Etios or similar',
			VehicleCategory::TEMPO_TRAVELLER_ECONOMIC	 => 'Tempo Traveller 15 seater',
			VehicleCategory::ASSURED_DZIRE_ECONOMIC		 => 'Maruti Suzuki Dzire',
			VehicleCategory::ASSURED_INNOVA_ECONOMIC	 => 'Toyota Innova',
		];
		if ($typeId != 0)
		{
			return $arrModel[$typeId];
		}
		else
		{
			return $arrModel;
		}
	}

	public function getCabType($typeId = 0)
	{
		$arrType = [
			VehicleCategory::COMPACT_ECONOMIC			 => 'hatchback',
			VehicleCategory::SUV_ECONOMIC				 => 'suv',
			VehicleCategory::SEDAN_ECONOMIC				 => 'sedan',
			VehicleCategory::TEMPO_TRAVELLER_ECONOMIC	 => 'traveller_15',
			VehicleCategory::ASSURED_DZIRE_ECONOMIC		 => 'MMT_ASSURED_DZIRE',
			VehicleCategory::ASSURED_INNOVA_ECONOMIC	 => 'MMT_ASSURED_INNOVA',
		];
		if ($typeId != 0)
		{
			return $arrType[$typeId];
		}
		else
		{
			return $arrType;
		}
	}

	public function getCabId($type = '')
	{
		$arrType = [
			'hatchback'			 => VehicleCategory::COMPACT_ECONOMIC,
			'suv'				 => VehicleCategory::SUV_ECONOMIC,
			'sedan'				 => VehicleCategory::SEDAN_ECONOMIC,
			'traveller_15'		 => VehicleCategory::TEMPO_TRAVELLER_ECONOMIC,
			'mmt_assured_dzire'	 => VehicleCategory::ASSURED_DZIRE_ECONOMIC,
			'mmt_assured_innova' => VehicleCategory::ASSURED_INNOVA_ECONOMIC,
		];
		if ($type != '')
		{
			return $arrType[$type];
		}
		else
		{
			return $arrType;
		}
	}

	public function holdBooking($data, $routes, $tripType, $mmtBookingId, $mmtTotalAmount, $dropCityName)
	{
		$includeTax		 = true;
		$mmtIncludedKms	 = $data["fareDetails"]["approx_distance"];
		$arraymodel		 = $this->mapping($data, $dropCityName);

		$model			 = $arraymodel['booking'];
		$bkgUserModel	 = $arraymodel['bkgUserModel'];
		$bkgPrefModel	 = $arraymodel['bkgPrefModel'];
		$bkgTrailModel	 = $arraymodel['bkgTrailModel'];
		$bkgInvoiceModel = $arraymodel['bkgInvoiceModel'];
		$bkgAddInfoModel = new BookingAddInfo();
		$bkgTrackModel	 = new BookingTrack();
		$bkgPfModel		 = new BookingPriceFactor();

		$model->bkg_booking_type			 = $tripType;
		$model->bkg_agent_id				 = 450;
		//$bkgTrailModel->bkg_info_source	 = 'Agent';
		$bkgAddInfoModel->bkg_info_source	 = 21;
		$bkgTrailModel->bkg_platform		 = Booking::Platform_Agent;

		$contactId						 = Contact::createbyBookingUser($bkgUserModel, $model->bkg_agent_id);
		$bkgUserModel->bkg_contact_id	 = ($bkgUserModel->bkg_contact_id) ? $bkgUserModel->bkg_contact_id : $contactId;

		$result = CActiveForm::validate($model);
		Logger::create("31");

		if ($result == '[]')
		{
			$trans = DBUtil::beginTransaction();
			try
			{
				if ($model->bkg_id == '')
				{
					$model->bkg_id = null;
				}
				//$carType = VehicleTypes::model()->getVehicleTypeById($model->bkg_vehicle_type_id);
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
					$routeModel->brt_to_latitude		 = $val->drop_lat;
					$routeModel->brt_from_longitude		 = $val->pickup_long;
					$routeModel->brt_to_longitude		 = $val->drop_long;
					$routeModel->brt_from_place_id		 = $val->pickup_place_id;
					$routeModel->brt_to_place_id		 = $val->drop_place_id;
					$route[]							 = $routeModel;
				}

				if ($mmtIncludedKms > 0 && $tripType == 1)
				{
					$route[0]->brt_trip_distance = $mmtIncludedKms;
				}
				Logger::create("32");

				$quoteM							 = new Quote();
				$quoteM->routes					 = $route;
				$quoteM->tripType				 = $tripType;
				$quoteM->partnerId				 = $model->bkg_agent_id;
				$quoteM->quoteDate				 = date("Y-m-d H:i:s");
				$quoteM->pickupDate				 = $routes[0]->date;
				$quoteM->sourceQuotation		 = Quote::Platform_Agent;
				$quoteM->isDynamicSurgeAvialable = DynamicPriceAvailability::Available;
				$quoteM->minRequiredKms			 = $mmtIncludedKms;
				$quoteM->setCabTypeArr();
				$qt								 = $quoteM->getQuote($carType, true, false);


				//            $qt = Quotation::model()->getQuote($route, $model->bkg_booking_type, $model->bkg_agent_id, $carType, true, false);
				Logger::create("33");

				$arrQuot = $qt[$carType];
				if (!$arrQuot->success)
				{
					throw new Exception("Request cannot be processed: " . $arrQuot->errorText, $arrQuot->errorCode);
				}
				$routeDistance	 = $arrQuot->routeDistance;
				$routeDuration	 = $arrQuot->routeDuration;
				$routeRates		 = $arrQuot->routeRates;

				$servingRoute = $arrQuot->servingRoute;

				$rCount										 = count($routes);
				$bkgInvoiceModel->bkg_rate_per_km_extra		 = $routeRates->ratePerKM; // $arrQuot['km_rate'];
				$bkgInvoiceModel->bkg_rate_per_km			 = $routeRates->ratePerKM; // $arrQuot['km_rate'];
				$bkgInvoiceModel->bkg_toll_tax				 = 0;
				$bkgInvoiceModel->bkg_state_tax				 = 0;
				$bkgInvoiceModel->bkg_is_toll_tax_included	 = 0;
				$bkgInvoiceModel->bkg_is_state_tax_included	 = 0;
				$model->bkg_from_city_id					 = $arrQuot->routes[0]->brt_from_city_id; // $qt['routeData']['pickupCity'];
				$model->bkg_to_city_id						 = $arrQuot->routes[count($arrQuot->routes) - 1]->brt_to_city_id; //$qt['routeData']['dropCity'];
				$model->bkg_trip_distance					 = $routeDistance->quotedDistance; //$qt['routeData']['quoted_km'];
				$model->bkg_trip_duration					 = (string) $routeDuration->totalMinutes; // $qt['routeData']['days']['totalMin'];
				if ($model->bkg_trip_distance <= 300)
				{
					$bkgPrefModel->bkg_cng_allowed = 1;
				}

//$model->bkg_pickup_pincode			 = $routes[0]->pickup_pincode;
				//$model->bkg_drop_pincode			 = $routes[$rCount - 1]->drop_pincode;
				$model->bkg_pickup_date						 = $routeDuration->fromDate; //$qt['routeData']['startTripDate'];
				$bkgInvoiceModel->bkg_chargeable_distance	 = $routeDistance->quotedDistance; // $arrQuot['chargeableDistance'];
				//$bkgTrackModel->bkg_garage_time				 = $routeDuration->totalGarage; //  $qt['routeData']['totalGarage'];
				$bkgTrackModel->bkg_garage_time				 = $routeDistance->totalGarage;
				//$model->bkg_pickup_time				 = date('H:i:00', strtotime($routeDuration->fromDate));
				if ($model->bkg_booking_type == 2)
				{
					$model->bkg_return_date = $routeDuration->toDate;
					//$model->bkg_return_time	 = date('H:i:00', strtotime($routeDuration->toDate));
				}
				$bkgInvoiceModel->bkg_driver_allowance_amount	 = $routeRates->driverAllowance; //$arrQuot['driverAllowance'];
				$bkgInvoiceModel->bkg_gozo_base_amount			 = round($routeRates->baseAmount); //round($arrQuot['gozo_base_amount']);

				$bkgInvoiceModel->bkg_surge_differentiate_amount = $routeRates->differentiateSurgeAmount;
				$bkgPfModel->bkg_ddbp_base_amount				 = $routeRates->dynamicSurge->baseFare;
				$bkgPfModel->bkg_ddbp_surge_factor				 = $routeRates->dynamicSurge->factor;
				$bkgPfModel->bkg_manual_base_amount				 = $routeRates->manualBaseAmount;
				$bkgPfModel->bkg_regular_base_amount			 = $routeRates->regularBaseAmount;
				$bkgPfModel->bkg_surge_applied					 = $routeRates->surgeFactorUsed;
				$bkgPfModel->bkg_ddbp_route_flag				 = $routeRates->dynamicSurge->routeFlag;
				$bkgPfModel->bkg_ddbp_master_flag				 = $routeRates->dynamicSurge->globalFlag;

				$bkgInvoiceModel->bkg_base_amount = round($routeRates->baseAmount); //round($arrQuot['base_amt']);
//				if ($model->bkg_trip_distance < $mmtIncludedKms)
//				{
//					$diffKMS							 = $mmtIncludedKms - $model->bkg_trip_distance;
//					$extraFare							 = round(($diffKMS * $bkgInvoiceModel->bkg_rate_per_km_extra) / 1.05);
//					$model->bkg_trip_distance			 = $mmtIncludedKms;
//					$bkgInvoiceModel->bkg_base_amount	 = $bkgInvoiceModel->bkg_base_amount + $extraFare;
//				}
				//      $model->bkg_vendor_amount = (round($arrQuot['vendor_amount']) - round($arrQuot['toll_tax']) - round($arrQuot['state_tax']));
				//      $model->bkg_quoted_vendor_amount = (round($arrQuot['vendor_amount']) - round($arrQuot['toll_tax']) - round($arrQuot['state_tax']));

				$isAirport		 = Cities::model()->findByPk($model->bkg_from_city_id)->cty_is_airport;
				$isDropAirport	 = Cities::model()->findByPk($model->bkg_to_city_id)->cty_is_airport;
				//    if ((round($arrQuot['toll_tax']) == 0 && round($arrQuot['state_tax']) == 0) || ($carType == 5 || $carType == 6)) {

				$bkgInvoiceModel->bkg_is_toll_tax_included	 = $routeRates->isTollIncluded | 0;
				$bkgInvoiceModel->bkg_is_state_tax_included	 = $routeRates->isStateTaxIncluded | 0;
				$bkgInvoiceModel->bkg_is_parking_included	 = $routeRates->isParkingIncluded | 0;
				$bkgInvoiceModel->bkg_toll_tax				 = round($routeRates->tollTaxAmount);
				$bkgInvoiceModel->bkg_state_tax				 = round($routeRates->stateTax);
				$bkgInvoiceModel->bkg_vendor_amount			 = round($routeRates->vendorAmount);
				$bkgInvoiceModel->bkg_quoted_vendor_amount	 = round($routeRates->vendorAmount);
				if (($routeRates->isTollIncluded == 1 && $isAirport == 1))
				{
					$bkgInvoiceModel->bkg_base_amount	 = $bkgInvoiceModel->bkg_base_amount + 100;
					$bkgInvoiceModel->bkg_vendor_amount	 = $bkgInvoiceModel->bkg_vendor_amount + 100;
				}
				//    }
				if ($model->bkg_booking_type == 2)
				{
//					$bkgInvoiceModel->bkg_base_amount	 = round($bkgInvoiceModel->bkg_base_amount * 0.98);
//					$difference								 = $routeRates->baseAmount - $bkgInvoiceModel->bkg_base_amount;
//					$bkgInvoiceModel->bkg_vendor_amount	 = round($bkgInvoiceModel->bkg_vendor_amount - ($difference * 0.5));
				}
				if ($model->bkg_booking_type == 4)
				{

					if ($isAirport == 1)
					{
						$model->bkg_transfer_type = 1;
					}
					elseif ($isDropAirport == 1)
					{
						$model->bkg_transfer_type = 2;
					}
				}

				$difference	 = ((strtotime($model->bkg_pickup_date) - time()) / 60);
				$currentHour = (int) date('H');
				$slot		 = 0;
				if ($currentHour < 2 || $currentHour >= 22)
				{
					$slot = 1;
				}
				else if ($currentHour < 5 || $currentHour > 21)
				{
					$slot = 2;
				}

				if (($slot == 1 && $difference <= 240))
				{
					$bkgInvoiceModel->bkg_base_amount = round($bkgInvoiceModel->bkg_base_amount * 1.20);
				}

				if ($currentHour == 2 && $difference > 1400 && $difference < 3900)
				{
					$bkgInvoiceModel->bkg_base_amount = round($bkgInvoiceModel->bkg_base_amount * 1.2);
				}

				$model->bkg_booking_id = 'temp';
				if (!$model->save())
				{
					throw new Exception("Failed to create booking", 101);
				}
				if ($model->bkg_id)
				{
					$bkgUserModel->setAttribute('bui_bkg_id', $model->bkg_id);
					$bkgInvoiceModel->setAttribute('biv_bkg_id', $model->bkg_id);
					$bkgAddInfoModel->setAttribute('bad_bkg_id', $model->bkg_id);
					$bkgTrailModel->setAttribute('btr_bkg_id', $model->bkg_id);
					$bkgTrackModel->setAttribute('btk_bkg_id', $model->bkg_id);
					$bkgPrefModel->setAttribute('bpr_bkg_id', $model->bkg_id);
				}
				$bkgInvoiceModel->populateAmount(true, false, true, true, $model->bkg_agent_id);
				//$model->calculateVendorAmount();
				if ($model->bkg_agent_id != '')
				{
					$agtModel = Agents::model()->findByPk($model->bkg_agent_id);
					if ($agtModel->agt_city == 30706)
					{
						$bkgInvoiceModel->bkg_cgst	 = Yii::app()->params['cgst'];
						$bkgInvoiceModel->bkg_sgst	 = Yii::app()->params['sgst'];
						$bkgInvoiceModel->bkg_igst	 = 0;
					}
					else
					{
						$bkgInvoiceModel->bkg_igst	 = Yii::app()->params['igst'];
						$bkgInvoiceModel->bkg_cgst	 = 0;
						$bkgInvoiceModel->bkg_sgst	 = 0;
					}
				}
				else
				{
					if ($model->bkg_from_city_id == 30706)
					{
						$bkgInvoiceModel->bkg_cgst	 = Yii::app()->params['cgst'];
						$bkgInvoiceModel->bkg_sgst	 = Yii::app()->params['sgst'];
						$bkgInvoiceModel->bkg_igst	 = 0;
					}
					else
					{
						$bkgInvoiceModel->bkg_igst	 = Yii::app()->params['igst'];
						$bkgInvoiceModel->bkg_cgst	 = 0;
						$bkgInvoiceModel->bkg_sgst	 = 0;
					}
				}
				Logger::create("34");

				$bkgUserModel->bkg_user_last_updated_on	 = new CDbExpression('NOW()');
				$model->scenario						 = 'cabRateAgent';
				$bkgUserModel->scenario					 = 'cabRateAgent';
				$effectiveTotalAmount					 = (int) round(0.99 * $bkgInvoiceModel->bkg_total_amount);
				if ($effectiveTotalAmount <= $mmtTotalAmount)
				{
					$difference = $bkgInvoiceModel->bkg_total_amount - $mmtTotalAmount;
					if ($difference > 0)
					{
						$newDifference						 = ((100 - Yii::app()->params['gst']) / 100) * $difference;
						$bkgInvoiceModel->bkg_base_amount	 = (int) round($bkgInvoiceModel->bkg_base_amount - $newDifference);
					}
					if ($difference < 0)
					{
						$newDifference						 = ((100 - Yii::app()->params['gst']) / 100) * (-1) * $difference;
						$bkgInvoiceModel->bkg_base_amount	 = (int) round($bkgInvoiceModel->bkg_base_amount + $newDifference);
					}
					$bkgInvoiceModel->populateAmount(true, false, true, true, $model->bkg_agent_id);
					//$model->calculateVendorAmount();
					if ($model->validate() && $bkgUserModel->validate() && $bkgInvoiceModel->validate() && $bkgAddInfoModel->validate() && $bkgTrailModel->validate() && $bkgTrackModel->validate() && $bkgPrefModel->validate())
					{
						//try {
						Logger::create("341");
						$sendConf								 = false;
						//                        if ($model->bkg_user_id == NULL || $model->bkg_user_id == '') {
						//                            $userModel = Users::model()->linkUserByEmail($model->bkg_user_email, $model->bkg_contact_no, $model->bkg_user_name, $model->bkg_user_lname, $model->bkg_country_code, $model->bkg_id, Booking::Platform_Agent, false);
						//                            if ($userModel) {
						//                                $model->bkg_user_id = $userModel->user_id;
						//                            }
						//                        }
						Logger::create("35");
						$bkgUserModel->bkg_user_last_updated_on	 = new CDbExpression('NOW()');
						$tmodel									 = Terms::model()->getText(1);
						$bkgTrailModel->bkg_tnc_id				 = $tmodel->tnc_id;
						$bkgTrailModel->bkg_tnc_time			 = new CDbExpression('NOW()');
						$bkgPfModel->bpf_bkg_id					 = $model->bkg_id;
						$userInfo								 = UserInfo::getInstance();
						$bkgTrailModel->bkg_create_user_type	 = $userInfo->userType;
						$bkgTrailModel->bkg_create_user_id		 = $userInfo->userId;
						$bkgTrailModel->bkg_create_type			 = BookingTrail::CreateType_Self;
						if (!$bkgPfModel->updateFromQuote($arrQuot) || !$model->save() || !$bkgUserModel->save() || !$bkgInvoiceModel->save() || !$bkgAddInfoModel->save() || !$bkgTrailModel->save() || !$bkgTrackModel->save() || !$bkgPrefModel->save())
						{
							throw new Exception("Failed to create booking", 101);
						}

						$booking_id				 = Booking::model()->generateBookingid($model);
						$model->bkg_booking_id	 = $booking_id;
						$bkgTrailModel->setPaymentExpiryTime();

						Logger::create("351");

						$isRealtedBooking = false;
						//             $isRealtedBooking = $model->findRelatedBooking($model->bkg_id);
						Logger::create("352");

						$bkgTrailModel->bkg_is_related_booking = ($isRealtedBooking) ? 1 : 0;

						if (!$bkgTrailModel->save())
						{
							throw new Exception("Failed to create booking", 101);
						}

						$bookingCab						 = new BookingCab('matchtrip');
						$bookingCab->bcb_vendor_amount	 = $bkgInvoiceModel->bkg_vendor_amount;
						$bookingCab->bcb_bkg_id1		 = $model->bkg_id;
						$bookingCab->save();
						$model->bkg_bcb_id				 = $bookingCab->bcb_id;
						$model->update();
						Logger::create("353");
						foreach ($route as $rmodel)
						{
							$rmodel->brt_bkg_id	 = $model->bkg_id;
							$rmodel->brt_bcb_id	 = $bookingCab->bcb_id;
							$rmodel->save();
						}
						BookingRoute::model()->setBookingCabStartEndTime($bookingCab->bcb_id, $model->bkg_id);
						if ($model->bkg_status == 1)
						{
							$logType	 = UserInfo::TYPE_SYSTEM;
							$sendConf	 = false;
						}
						$bkgid			 = $model->bkg_id;
						$processedRoute	 = BookingLog::model()->logRouteProcessed($arrQuot, $model->bkg_id);
						$desc			 = "Booking created by agent - $processedRoute";

						$eventid = BookingLog::BOOKING_CREATED;
						BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid);
						Logger::create("354");

						if ($sendConf)
						{
							$model->sendConfirmation($logType);
							$emailWrapper = new emailWrapper();
							$emailWrapper->gotBookingAgentUser($bkgid);
						}
						Logger::create("355");
					}

					Logger::create("36");

					$success		 = !$model->hasErrors();
					$successUser	 = !$bkgUserModel->hasErrors();
					$successInvoice	 = !$bkgInvoiceModel->hasErrors();
					$successAddinfo	 = !$bkgAddInfoModel->hasErrors();
					$successTrail	 = !$bkgTrailModel->hasErrors();
					$successTrack	 = !$bkgTrackModel->hasErrors();
					$successPref	 = !$bkgPrefModel->hasErrors();
					$successPf		 = !$bkgPfModel->hasErrors();



					if ($success && $successPf && $successUser && $successInvoice && $successAddinfo && $successTrail && $successTrack && $successPref)
					{
						DBUtil::commitTransaction($trans);
						//update partner commission and gozoamount
						$bkgInvoiceModel->refresh();
						$bkgInvoiceModel->calculateDues();
						$bkgInvoiceModel->save();
						$data = ['booking_id'		 => $mmtBookingId,
							'hold_key'			 => $model->bkg_id,
							'vendor_response'	 => ['message' => 'HOLD Booking success', 'is_success' => true],
							'response_type'		 => 'HOLD', 'status'			 => 'success'];
					}
					else
					{
						$errors = [];
						foreach ($model->getErrors() as $key => $value)
						{
							$key			 = $this->errorMapping($key);
							$errors[$key]	 = $value;
						}

						foreach ($bkgUserModel->getErrors() as $key => $value)
						{
							$key			 = $this->errorMapping($key);
							$errors[$key]	 = $value;
						}

						foreach ($bkgInvoiceModel->getErrors() as $key => $value)
						{
							$key			 = $this->errorMapping($key);
							$errors[$key]	 = $value;
						}

						foreach ($bkgAddInfoModel->getErrors() as $key => $value)
						{
							$key			 = $this->errorMapping($key);
							$errors[$key]	 = $value;
						}

						foreach ($bkgTrailModel->getErrors() as $key => $value)
						{
							$key			 = $this->errorMapping($key);
							$errors[$key]	 = $value;
						}

						foreach ($bkgTrackModel->getErrors() as $key => $value)
						{
							$key			 = $this->errorMapping($key);
							$errors[$key]	 = $value;
						}

						foreach ($bkgPrefModel->getErrors() as $key => $value)
						{
							$key			 = $this->errorMapping($key);
							$errors[$key]	 = $value;
						}

						DBUtil::rollbackTransaction($trans);
						$data = ['booking_id'		 => $mmtBookingId,
							'hold_key'			 => '',
							'vendor_response'	 => ['message' => 'HOLD Failed: ' . $errors[$key][0], 'is_success' => false],
							'response_type'		 => 'HOLD', 'status'			 => 'error',
							'errors'			 => $errors];
					}
				}
				else
				{
					DBUtil::rollbackTransaction($trans);
					$data = ['booking_id'		 => $mmtBookingId,
						'hold_key'			 => '',
						'vendor_response'	 => ['message' => 'HOLD Failed: Prices have increased', 'is_success' => false],
						'response_type'		 => 'HOLD',
						'status'			 => 'error',
						'errors'			 => ['Price' => ['0' => 'Prices have increased']]];
				}
			}
			catch (Exception $e)
			{
				$model->addError('bkg_id', $e->getMessage());
				DBUtil::rollbackTransaction($trans);
			}
		}
		else
		{
			$errors = [];
			foreach ($model->getErrors() as $key => $value)
			{
				$key			 = $this->errorMapping($key);
				$errors[$key]	 = $value;
			}

			$data = ['booking_id'		 => $mmtBookingId,
				'hold_key'			 => '',
				'vendor_response'	 => ['message' => 'HOLD Failed: ' . $errors[$key][0], 'is_success' => false],
				'response_type'		 => 'HOLD',
				'status'			 => 'error',
				'errors'			 => $errors];
		}

		Logger::create("37");
		return $data;
	}

	public function mapping($data, $dropCity)
	{
		$model							 = new Booking('new');
		$bkgUserModel					 = new BookingUser();
		$bkgPrefModel					 = new BookingPref();
		$bkgTrailModel					 = new BookingTrail();
		$bkgInvoiceModel				 = new BookingInvoice();
		$model->bkg_agent_ref_code		 = $data['bookingId'];
		$bkgUserModel->bkg_user_fname	 = $data['customerDetails']['firstName'];
		$bkgUserModel->bkg_user_lname	 = $data['customerDetails']['lastName'];
		$model->bkg_pickup_address		 = $data['tripDetails']['pickupAddress'];
		if ($data['tripDetails']['tripType'] == 'OW')
		{
			$model->bkg_booking_type = 1;
			if ($data['tripDetails']['destinationLocation']['address'] != '')
			{
				$model->bkg_drop_address = $data['tripDetails']['destinationLocation']['address'];
			}
			else
			{
				$model->bkg_drop_address = $dropCity;
			}
		}
		if ($data['tripDetails']['tripType'] == 'AT')
		{
			$model->bkg_booking_type = 4;
			if ($data['tripDetails']['destinationLocation']['address'] != '')
			{
				$model->bkg_drop_address = $data['tripDetails']['destinationLocation']['address'];
			}
			else
			{
				$model->bkg_drop_address = $dropCity;
			}
		}
		if ($data['tripDetails']['tripType'] == 'RT')
		{
			$model->bkg_booking_type = 2;
			$model->bkg_drop_address = $data['tripDetails']['pickupAddress'];
			$returnDate				 = DateTimeFormat::DatePickerToDate($data['tripDetails']['returnDate']);
			$returnTime				 = date('H:i:s', strtotime($data['tripDetails']['dropTime']));
			$returnDateTime			 = $returnDate . ' ' . $returnTime;

			$bmodel->bkg_return_date = $returnDateTime;
			// $bmodel->bkg_return_time = $returnTime;
		}
		$bkgUserModel->bkg_country_code	 = 91;
		$bkgUserModel->bkg_contact_no	 = $data['customerDetails']['mobileNo'];
		$bkgUserModel->bkg_user_email	 = $data['customerDetails']['emailId'];
		$model->bkg_vehicle_type_id		 = $this->getCabId(strtolower($data['vehicleType']));
		$bkgPrefModel->bkg_send_email	 = 0;
		$bkgPrefModel->bkg_send_sms		 = 0;
		$bkgTrailModel->bkg_tnc			 = 1;

		if ($data['advancePaid'] != '')
		{
			$bkgInvoiceModel->bkg_corporate_credit = $data['advancePaid'];
		}
		else
		{
			$bkgInvoiceModel->bkg_corporate_credit = 0;
		}

		$arrayModel = ['booking' => $model, 'bkgUserModel' => $bkgUserModel, 'bkgPrefModel' => $bkgPrefModel, 'bkgTrailModel' => $bkgTrailModel, 'bkgInvoiceModel' => $bkgInvoiceModel];
		return $arrayModel;
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

	public function getQuote()
	{
		Logger::create("getQuote START:\t", CLogger::LEVEL_PROFILE);
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);

		try
		{
			/** @var \Stub\mmt\QuoteRequest $obj */
			$obj	 = $jsonMapper->map($jsonObj, new Stub\mmt\QuoteRequest());
			/** @var Booking $model */
			$model	 = $obj->getModel();

			$quotData = Quote::populateFromModel($model, $model->bkg_vehicle_type_id, false, false);

			$userInfo	 = UserInfo::getInstance();
			$aatType	 = AgentApiTracking::TYPE_GET_QUOTE;
			$aatModel	 = AgentApiTracking::model()->add($aatType, $data, $model, \Filter::getUserIP());

			#$patModel	 = PartnerApiTracking::add($typeAction, $jsonObj, $userInfo->userId, $model, $model->bkg_pickup_date);
			$response	 = new Stub\mmt\QuoteResponse();
			$response->setData($quotData);
			$data		 = Filter::removeNull($response);
		}
		catch (Exception $e)
		{
			throw new \Exception($e->getMessage(), \ReturnSet::ERROR_VALIDATION);
			Logger::exception($e);
		}
		return $this->renderJSON([
					'type'	 => 'raw',
					'data'	 => $data
		]);
	}

	public function hold()
	{
		Logger::create("hold START:\t", CLogger::LEVEL_PROFILE);
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);

		try
		{
			/** @var \Stub\mmt\HoldRequest $obj */
			$obj	 = $jsonMapper->map($jsonObj, new Stub\mmt\HoldRequest());
			/** @var Booking $model */
			$model	 = $obj->getModel();
			$model->addNew(false);

			$userInfo	 = UserInfo::getInstance();
			$aatType	 = AgentApiTracking::TYPE_HOLD_BOOKING;
			$aatModel	 = AgentApiTracking::model()->add($aatType, $data, $model, \Filter::getUserIP());
			$response	 = new Stub\mmt\HoldResponse();
			$response->setData($model);

			$data = Filter::removeNull($response);
		}
		catch (Exception $e)
		{
			throw new \Exception($e->getMessage(), \ReturnSet::ERROR_VALIDATION);
			Logger::exception($e);
		}
		return $this->renderJSON([
					'type'	 => 'raw',
					'data'	 => $data
		]);
	}

	public function confirm()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;

		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);

		try
		{
			$obj = $jsonMapper->map($jsonObj, new Stub\mmt\ConfirmRequest());
			$obj->getModel();
			if ($obj->holdKey > 0)
			{
				$model = Booking::model()->findByPk($obj->holdKey);

				$aatType					 = AgentApiTracking::TYPE_CREATE_BOOKING;
				$aatModel					 = AgentApiTracking::model()->add($aatType, $data, $model, \Filter::getUserIP(), '', '');
				$model->bkg_agent_ref_code	 = $obj->mmtBookingId;

				if ($model->bkg_status == 15 && $model->bkg_agent_id == 450)
				{

					$logType	 = UserInfo::TYPE_SYSTEM;
					$returnSet	 = $model->confirm(true, false);
					$success	 = $returnSet->isSuccess();

					if ($success)
					{

						$model->refresh();
						$update			 = BookingAddInfo::updataDataMMT($obj->cab_type_id, $model);
						$model->bkgTrack = BookingTrack::model()->sendTripOtp($model->bkg_id, $sendOtp		 = false);
						if ($model->bkgTrack != '')
						{
							$model->bkgTrack->save();
						}
						$amount = $obj->advancePaid; //Credit added by agent;

						if ($amount > 0)
						{
							$desc			 = "Credits used";
							$bankLedgerID	 = PaymentType::model()->ledgerList(PaymentType::TYPE_AGENT_CORP_CREDIT);
							$model->updateAdvance($amount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, UserInfo::getInstance(), null, "Partner Wallet Used");
						}
						$response	 = new Stub\mmt\ConfirmResponse();
						$response->setData($model);
						$data		 = Filter::removeNull($response);
						$returnSet->setStatus(true);
						$returnSet->setData($data);
					}
				}
				else
				{
					$returnSet->setStatus(false);
					throw new Exception("Only quoted booking can eligible", ReturnSet::ERROR_INVALID_DATA);
				}
			}
		}
		catch (Exception $e)
		{
			throw new \Exception($e->getMessage(), \ReturnSet::ERROR_VALIDATION);
			Logger::exception($e);
		}
		return $this->renderJSON([
					'type'	 => 'raw',
					'data'	 => $data
		]);
	}

}
