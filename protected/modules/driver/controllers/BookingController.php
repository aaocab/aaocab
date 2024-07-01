<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class BookingController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = 'column1';
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
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array(),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS', 'uploads'),
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
		$this->onRest('req.cors.access.control.allow.methods', function () {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function ($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			//$ri	 = array('/list', '/currentList', '/syncLog', '/updateNoshowval', '/tripCommentsSyncData', '/tripLagging', '/tripComments', '/tripTrackingLog', '/saveDriverVoucher', '/removeDriverVoucher', '/driver_account_bonus', '/redeem_driver_bonus', '/driver_ride_complete');
			$ri	 = array('/list', '/syncLog', '/updateNoshowval', '/tripLagging', '/syncBooking', '/drvBookingValidation', '/sendOtpToCustomer', '/drivertripdetails', '/saveCustomerSignature', '/currentList', '/addDestinationNote', '/currentListNew', '/getDestinationNoteList', '/getDestinationNoteAreaType', '/resendOtpToDriver', '/validateOtpFromDriver', '/drvContactValidation_V2', '/otpVerifyForLogin');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.post.getDestinationNoteList.render', function () {

			return $this->renderJSON($this->getDestinationNoteList());
		});
		$this->onRest('req.post.addDestinationNote.render', function () {

			return $this->renderJSON($this->addDestinationNote());
		});
		$this->onRest('req.get.getDestinationNoteAreaType.render', function () {

			return $this->renderJSON($this->getDestinationNoteAreaType());
		});
		$this->onRest('req.post.tripDetailsV1.render', function () {

			return $this->renderJSON($this->tripDetailsV1());
		});
		$this->onRest('req.post.gnowTripDetails.render', function () {
			return $this->renderJSON($this->gnowTripDetails());
		});

		// All Driver Ongoing and Upcoming trip Result
		$this->onRest('req.post.drivertripdetails.render', function () {
			$token = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);

			$check = Drivers::model()->authoriseDriver($token);
			if ($check)
			{
				$driverId = UserInfo::getEntityId();

				$result1 = Drivers::model()->getTripDetails($driverId);

				$result2 = Drivers::model()->getNextTripDetails($driverId);

				if ($result1 == false)
				{
					$result1 = array("ongoing_trip" => "0");
				}

				if ($result2 == false)
				{
					$result2 = array("next_trip_start" => "0", "upcoming_trip" => "0");
				}

				$result = array_merge($result1, $result2);

				if ($result)
				{
					$success = true;
					$error	 = "No error";
				}
				else
				{
					$success = false;
					$error	 = "Something went wrong";
				}
			}
			else
			{
				$success = false;
				$error	 = "Unauthorized Driver";
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'error'		 => $error,
					'data'		 => $result,
				)
			]);
		});

		// All Assigned Booking List Upto 5 Days
		$this->onRest('req.post.list.render', function () {
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);

			if ($check)
			{
				$obj		 = UserInfo::getInstance();
				Logger::create("Request User :: " . json_encode($obj) . " User :: " . Yii::app()->user->getId(), CLogger::LEVEL_TRACE);
				//$driverId	 = $obj->userId;
				$driverId	 = UserInfo::getEntityId();
				#$showCustomer = $this->customerDataShow($bkgModel->bkg_pickup_date);
				$driverModel = Booking::model()->getDriverListing($driverId);
				if ($driverModel != [])
				{
					$success = true;
					$error	 = null;
				}
				else
				{
					$success = false;
					$error	 = "No records found";
				}
				Logger::create("Response :: " . json_encode($driverModel), CLogger::LEVEL_INFO);
			}
			else
			{
				$error	 = 'Unauthorised Driver';
				$success = false;
			}
			$data->dataList = $driverModel;
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'error'		 => $error,
					'data'		 => $data,
				)
			]);
		});
		/**
		 * @deprecated 
		 * New service : currentBookings
		 * From 19/03/20
		 */
		$this->onRest('req.post.currentList.render', function () {
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);
			Logger::create("Request token =>" . $token, CLogger::LEVEL_TRACE);

			if ($check)
			{
				$driverModel = [];
				$obj		 = UserInfo::getInstance();
				$driverId	 = UserInfo::getEntityId();
				//3736;//
				Logger::create("Request driver =>" . $driverId, CLogger::LEVEL_TRACE);
				$data		 = BookingTrackLog::model()->checkData($driverId);
				Logger::create("Request data =>" . $data, CLogger::LEVEL_TRACE);
				$apiData	 = Yii::app()->request->getParam('data');
				$bkgData	 = CJSON::decode($apiData, true);

				#$offline_bkg_id = $bkgData['offline_bkg_id'];

				$bookingModel	 = Booking::model()->findByPk($bkgData['bkg_id']);
				$tempData		 = BookingTrackLog::model()->checkData($bookingModel->bkgBcb->bcb_driver_id);
				if ($tempData['bkg_id'] != '')
				{
					$driverModel = Booking::model()->getDriverListingCurrent($bookingModel->bkgBcb->bcb_driver_id, $tempData['bkg_id'], $tempData['flag']);
				}
				elseif ($data['bkg_id'] != '')
				{
					$driverModel = Booking::model()->getDriverListingCurrent($driverId, $data['bkg_id'], $data['flag']);
				}

				/**
				 * NOTE: offline_bkg_id this functionality has been removed from the driver app, always relying on the data received from the service
				 */
				/* if ($offline_bkg_id != '')
				  {
				  $offLineData = Booking::model()->getOfflineDriverListingCurrent($offline_bkg_id);

				  if ($offLineData['driverId'] == $driverId)
				  {
				  if (in_array($offLineData['bookingStatus'], [6, 7]))
				  {
				  $offLineData['message'] = "Booking is already served.";
				  $offLineData['status']  = 0;
				  }
				  if ($offLineData['bookingStatus'] == 9)
				  {
				  $offLineData['message'] = "Booking is already Cancelled.";
				  $offLineData['status']  = 0;
				  }
				  }
				  else
				  {
				  $offLineData['message'] = "You are not assigned for this booking.";
				  $offLineData['status']  = 0;
				  }
				  } */

				$is_flexxi			 = false;
				$server_datetime	 = DBUtil::getCurrentTime();
				$server_timestamp	 = ((strtotime($server_datetime)) * 1000);
				foreach ($driverModel as $drv)
				{
					$is_flexxi = ($drv['is_flexxi'] == 1) ? true : false;
				}
				if ($driverModel != [])
				{
					$success = true;
					$error	 = null;
				}
				else
				{
					$success	 = false;
					$error		 = "No records found";
					$errorCode	 = 160;
				}
			}
			else
			{
				$error	 = 'Unauthorised Driver';
				$success = false;
			}
			$dataModel->dataList = $driverModel;
			Logger::create("Response =>" . $data, CLogger::LEVEL_TRACE);
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'			 => $success,
					'error'				 => $error,
					'errorCode'			 => $errorCode,
					'is_flexxi'			 => $is_flexxi,
					'server_timestamp'	 => $server_timestamp,
					'data'				 => $dataModel,
					'offLineData'		 => $offLineData,
				)
			]);
		});

		//Current Booking


		$this->onRest('req.post.currentBookings_V2.render', function () {

			return $this->renderJSON($this->currentBookings_v2());
		});

		// Booking sync
		/**
		 * @deprecated since version number
		 */
		$this->onRest('req.post.syncBooking.render', function () {
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);
			if ($check)
			{
				$success			 = false;
				$errors				 = 'Something went wrong while uploading';
				$process_sync_data	 = Yii::app()->request->getParam('data');
				// $process_sync_data   = '{ "ttg_bkg_id": "867204",   "ttg_driver_id": "77394",   "ttg_bcb_id": "921858",   "bkg_start_odometer_path": "\/storage\/emulated\/0\/gozo_Admin\/aaocab_image\/20190402_185141_0.5670519741692476.jpg",   "bkg_start_odometer": "1230",   "ttg_trip_start_time": "2019-04-02 18:48:50",   "bkg_ride_start": "1",   "bpr_trip_otp": "556954",   "bpr_is_trip_verified": "1",   "ttg_start_odometer": "1230",   "ttg_time_stamp": "2019-04-02 18:48:50",   "ttg_latitude": "18.5577436",   "ttg_longitude": "73.9509249",   "ttg_event_type": "215",   "tlg_id": 14 }';
				// print_r($process_sync_data);exit;                
				Logger::create("Request =>" . $process_sync_data, CLogger::LEVEL_TRACE);

				//$process_sync_data = '{"ttg_bkg_id":"686504","ttg_driver_id":"3736","ttg_bkg_bcb_id":"439523","blg_desc":"Driver has paused trip.","ttg_latitude":"22.5753862","ttg_longitude":"88.4336882","ttg_time_stamp":"2018-08-13 15:29:14","ttg_event_type":"216","tlg_id":1}';
				//$process_sync_data = '{"ttg_bkg_id":"425090","ttg_driver_id":"3736","ttg_bkg_bcb_id":"446025","blg_desc":"Driver arrived for pickup","ttg_latitude":"22.5754224","ttg_longitude":"88.4336881","ttg_time_stamp":"2018-08-14 16:59:57","ttg_event_type":"104","tlg_id":1}';
				$data = CJSON::decode($process_sync_data, true);

				//$userId				 = $obj->userId;
				$userId = UserInfo::getEntityId(); // Driiver ID

				$userInfo			 = UserInfo::getInstance();
				$userInfo->userType	 = UserInfo::TYPE_DRIVER;

				$eventId	 = $data['ttg_event_type'];
				$desc		 = $desc['event_type'];
				$unique_id	 = TripTracking::model()->checkDuplicateIds($data['ttg_bkg_id'], $userId, $data['ttg_time_stamp'], $eventId);
				if ($unique_id == '')
				{
					//Yii::log("this is for test: $process_sync_data \r\n\t ", CLogger::LEVEL_WARNING);			
					$start_odometer					 = $_FILES['start_odometer']['name'];
					$start_odometer_tmp				 = $_FILES['start_odometer']['tmp_name'];
					$end_odometer					 = $_FILES['end_odometer']['name'];
					$end_odometer_tmp				 = $_FILES['end_odometer']['tmp_name'];
					$bkg_id							 = $data['ttg_bkg_id'];
					$trip_otp						 = $data['bpr_trip_otp'];
					$is_trip_verified				 = $data['bpr_is_trip_verified'];
					$bkgExtraCharge					 = $data['bkg_extra_km_charge'];
					$bkgExtraTotalKm				 = $data['bkg_extra_km'];
					$bkgExtraTollTax				 = $data['bkg_extra_toll_tax'];
					$bkgExtraStateTax				 = $data['bkg_extra_state_tax'];
					$bkgParkingCharge				 = $data['bkg_parking_charge'];
					$vendorActualCollected			 = $data['bkg_vendor_collected'];
					$lat							 = $data['ttg_latitude'];
					$long							 = $data['ttg_longitude'];
					/* @var $bookingModel Booking */
					$bookingModel					 = Booking::model()->findByPk($bkg_id);
					$params['blg_booking_status']	 = $bookingModel->bkg_status;
					$params['blg_ref_id']			 = $data['blg_ref_id'];
					$tripId							 = $bookingModel->bkg_bcb_id;

					$model					 = new TripTracking();
					$model->attributes		 = $data;
					$model->ttg_driver_id	 = $userId;
					$model->ttg_bcb_id		 = $tripId;
					$old_additional_charge	 = $bookingModel->bkgInvoice->bkg_additional_charge;
					Logger::create('start_odometer: ' . $start_odometer, CLogger::LEVEL_TRACE);

					if ($eventId > 0)
					{

						/* @var $model TripTracking */
						$model					 = new TripTracking();
						$model->attributes		 = $data;
						$model->ttg_driver_id	 = $userId;
						$model->ttg_bcb_id		 = $tripId;
						$model->ttg_event_type	 = $eventId;
						$location				 = " Location : ( " . "$lat" . " , " . $long . " )";
						$time					 = " Time : " . date('d/M/Y h:i A', strtotime(date('Y-m-d H:i:s')));
						Logger::create('eventID=>' . $eventId, CLogger::LEVEL_TRACE);
						switch ($eventId)
						{
							case 93:
								$desc		 = "Cab Arrived." . $location . " and " . $time;
								BookingLog::model()->createLog($bkg_id, $desc, $userInfo, $eventId, false, $params);
								$returnArr	 = BookingTrail::model()->updateDriverScoreByBkg($bkg_id, $eventId);
								if ($returnArr['success'] == true)
								{
									//Logger::create($eventId . "- " . $desc, CLogger::LEVEL_INFO);
								}
								break;
							case 104:
								$desc					 = "Driver left for pickup." . $location . " and " . $time;
								BookingLog::model()->createLog($bkg_id, $desc, $userInfo, $eventId, false, $params);
								//Logger::create($eventId . "- " . $desc, CLogger::LEVEL_INFO);
								break;
							case 253:
								$model->ttg_trip_late	 = $data['ttg_trip_late'];
								$desc					 = "Cab will be late " . $data['ttg_trip_late'] . " min. " . $location . " and " . $time;
								BookingLog::model()->createLog($bkg_id, $desc, $userInfo, $eventId, false, $params);
								//Logger::create($eventId . "- " . $desc, CLogger::LEVEL_INFO);
								break;
							case 89:

								$bookingCabModel = BookingCab::model()->findByPk($bookingModel->bkg_bcb_id);
								if ($bookingCabModel->bcb_driver_id == $data['ttg_driver_id'])
								{
									$bookingModel->bkgtrack->bkg_is_no_show = 1;
									if ($bookingModel->bkgPref->save())
									{
										$desc = "Consumer No Show has been set.";
										BookingLog::model()->createLog($bkg_id, $desc, $userInfo, $eventId, false, $params);
										//Logger::create($eventId . "- " . $desc, CLogger::LEVEL_INFO);
									}
									else
									{
										throw Exception("No Show not yet saved.\n\t\t" . json_encode($bookingModel->getErrors()));
									}
								}
								else
								{
									$errors = "Driver name not matched.";
									throw Exception($errors . "\n\t\t");
								}

								break;
							case 215:

								$transaction = DBUtil::beginTransaction();
								try
								{
									if ($model->validate())
									{
										if ($model->save())
										{
											//	if ($start_odometer != '' && $start_odometer != null)
											{
												$type	 = 'start_odometer';
												$result1 = $this->saveImage($start_odometer, $start_odometer_tmp, $bkg_id, $type);
												Logger::create("path1test1 ->" . $result1['path'], CLogger::LEVEL_INFO);

												$path1 = str_replace("\\", "\\\\", $result1['path']);
												Logger::create("path1test2 ->" . $path1, CLogger::LEVEL_INFO);

												$qry1		 = "UPDATE trip_tracking SET ttg_start_odometer_path = '" . $path1 . "' WHERE ttg_id = LAST_INSERT_ID() ";
												$recorset1	 = Yii::app()->db->createCommand($qry1)->execute();
												//if ($recorset1)
												{
													$desc = "Customer on board" . $location . " and " . $time;
													BookingLog::model()->createLog($bkg_id, $desc, $userInfo, 215, false, $params);
													Logger::create($eventId . "- " . $desc, CLogger::LEVEL_INFO);
													if ($trip_otp > 0)
													{
														$pickupDate			 = $bookingModel->bkg_pickup_date;
														$estimateStart		 = date("Y-m-d H:i:s", strtotime($pickupDate . "-30 minutes"));
														$nowTime			 = date("Y-m-d H:i:s");
														$estimateComplete	 = date("Y-m-d H:i:s", strtotime($pickupDate . "+ $bookingModel->bkg_trip_duration minutes"));
														$pickupTime			 = date("Y-m-d H:i:s", strtotime($bookingModel->bkg_pickup_date));
														$tripTrackDetails	 = TripTracking::model()->getByBkg($bkg_id, 93);
														$arrivedTime		 = date("Y-m-d H:i:s", strtotime($tripTrackDetails[0]['ttg_time_stamp']));
														$pickupLat			 = $bookingModel->bookingRoutes->brt_from_latitude;
														$pickupLong			 = $bookingModel->bookingRoutes->brt_from_longitude;
														$dalat				 = $tripTrackDetails[0]['ttg_latitude'];
														$dalong				 = $tripTrackDetails[0]['ttg_longitude'];

														/**
														 * case1:if pickup time greater then arrive time,if success then check lat/long;
														 * case2:if pickup time less then arrive time,then go to elseBlock;
														 */
														if ($pickupTime > $arrivedTime)
														{
															$platform		 = TripOtplog::Platform_DRIVERAPP;
															$bookingTrack	 = $bookingModel->bkgTrack;
															$returnSet		 = $bookingTrack->startTrip($platform, $trip_otp, '', '', $start_odometer);
															$success		 = $returnSet->getStatus();
														}
														else
														{
															if ($estimateStart < $nowTime && $nowTime > $estimateComplete)
															{
																$returnSet								 = new ReturnSet();
																$bookingModel->bkgTrack->bkg_ride_start	 = 1;
																$bookingModel->bkgTrack->save();
																$eventId								 = BookingLog::RIDE_STATUS;
																$desc									 = "Overdue Ride started.";
																$userInfo->userId						 = $bookingModel->bkgBcb->bcb_driver_id;
																$userInfo->userType						 = UserInfo::TYPE_DRIVER;
																BookingLog::model()->createLog($bkg_id, $desc, $userInfo, $eventId, $oldModel, $params);
																$returnSet->setData(['message' => "Overdue Ride started. OTP not verified)."]);
																$returnSet->setStatus(true);
																$prows									 = PenaltyRules::getValueByPenaltyType(PenaltyRules::PTYPE_RIDE_START_OVERDUE);
																$penaltyAmount							 = $prows['plt_value'];
																
																if ($penaltyAmount > 0)
																{
																	$vendor_id		 = $bookingModel->bkgBcb->bcb_vendor_id;
																	$bkg_booking_id	 = $bookingModel->bkg_booking_id;
																	$remarks		 = "Ride start overdue for booking ID #$bkg_booking_id";
																	$penaltyType	 = PenaltyRules::PTYPE_RIDE_START_OVERDUE;
																	$result			 = AccountTransactions::checkAppliedPenaltyByType($bkg_id, $penaltyType);
																	if ($result)
																	{
																		AccountTransactions::model()->addVendorPenalty($bkg_id, $vendor_id, $penaltyAmount, $remarks, '', $penaltyType);
																	}
																}
															}
															else if ($nowTime > $arrivedTime && $nowTime < $estimateComplete)
															{

																$platform		 = TripOtplog::Platform_DRIVERAPP;
																$bookingTrack	 = $bookingModel->bkgTrack;
																$returnSet		 = $bookingTrack->startTrip($platform, $trip_otp, '', '', $start_odometer);
																$success		 = $returnSet->getStatus();
															}
															else
															{
																throw new Exception("Oops! Unable to start trip");
															}
														}
													}
												}

												$returnArr = BookingTrail::model()->updateDriverScoreByBkg($bkg_id, $eventId);
												if ($returnArr['success'] != true)
												{
													throw new Exception("Driver score is not save in table.\n\t\t" . json_encode($returnArr['errors']));
												}
												$errors	 = [];
												$success = DBUtil::commitTransaction($transaction);
												Logger::create("SUCCESS : " . $success . " - EVENT ID : " . $eventId . " - DESC :" . $desc, CLogger::LEVEL_INFO);
											}
										}
										else
										{
											throw new Exception("OTP not yet saved in table.\n\t\t" . json_encode($model->getErrors()));
										}
									}
									else
									{
										throw new Exception("Not Validate.\n\t\t" . json_encode($model->getErrors()));
									}
								}
								catch (Exception $ex)
								{
									$errors = $ex->getMessage();
									DBUtil::rollbackTransaction($transaction);
								}
								break;
							case 251:

								$transaction = DBUtil::beginTransaction();
								try
								{
									$bkg_ids = BookingSub::model()->getBkgsByFlexxiBkg($bkg_id);
									if (count($bkg_ids) > 0)
									{
										foreach ($bkg_ids as $bkg)
										{
											if ($bkg['bkg_ride_complete'] <> 1)
											{
												$bkg_id					 = $bkg['bkg_id'];
												$model					 = new TripTracking();
												$model->attributes		 = $data;
												$model->ttg_driver_id	 = $userId;
												$model->ttg_bcb_id		 = $tripId;
												$model->ttg_event_type	 = $eventId;
												$model->ttg_bkg_id		 = $bkg_id;
												if ($model->save())
												{
													$desc = "Trip Paused." . $location . " and " . $time;
													BookingLog::model()->createLog($bkg_id, $desc, $userInfo, $eventId, false, $params);
													//Logger::create($eventId . "- " . $desc, CLogger::LEVEL_INFO);
												}
											}
										}
										$success = DBUtil::commitTransaction($transaction);
										$errors	 = [];
										Logger::create("SUCCESS : " . $success . " - EVENT ID : " . $eventId . " - DESC :" . $desc, CLogger::LEVEL_INFO);
									}
								}
								catch (Exception $ex)
								{
									$errors = $ex->getMessage();
									DBUtil::rollbackTransaction($transaction);
								}




								break;
							case 252:

								$transaction = DBUtil::beginTransaction();
								try
								{
									$bkg_ids = BookingSub::model()->getBkgsByFlexxiBkg($bkg_id);
									if (count($bkg_ids) > 0)
									{
										foreach ($bkg_ids as $bkg)
										{
											if ($bkg['bkg_ride_complete'] <> 1)
											{
												$bkg_id					 = $bkg['bkg_id'];
												$model					 = new TripTracking();
												$model->attributes		 = $data;
												$model->ttg_driver_id	 = $userId;
												$model->ttg_bcb_id		 = $tripId;
												$model->ttg_event_type	 = $eventId;
												$model->ttg_bkg_id		 = $bkg_id;
												if ($model->save())
												{
													$desc = "Trip Resume." . $location . " and " . $time;
													BookingLog::model()->createLog($bkg_id, $desc, $userInfo, $eventId, false, $params);
													//Logger::create($eventId . "- " . $desc, CLogger::LEVEL_INFO);
												}
											}
										}
										$success = DBUtil::commitTransaction($transaction);
										$errors	 = [];
										Logger::create("SUCCESS : " . $success . " - EVENT ID : " . $eventId . " - DESC :" . $desc, CLogger::LEVEL_INFO);
									}
								}
								catch (Exception $ex)
								{
									$errors = $ex->getMessage();
									DBUtil::rollbackTransaction($transaction);
								}




								break;
							case 216:

								$transaction = DBUtil::beginTransaction();
								try
								{

									if ($model->validate() && $model->save())
									{
										if ($end_odometer != '')
										{
											$type	 = 'end_odometer';
											$result2 = $this->saveImage($end_odometer, $end_odometer_tmp, $bkg_id, $type);
											Logger::create("endtest1 ->" . $result2['path'], CLogger::LEVEL_INFO);

											$path2 = str_replace("\\", "\\\\", $result2['path']);
											Logger::create("endtest2 ->" . $path2, CLogger::LEVEL_INFO);

											Logger::create("bkg_id=> " . $bkg_id, CLogger::LEVEL_INFO);
											Logger::create("eventid=> " . $eventId, CLogger::LEVEL_INFO);

											$qry2		 = "UPDATE trip_tracking SET ttg_end_odometer_path = '" . $path2 . "' WHERE ttg_id = LAST_INSERT_ID() ";
											$recordset2	 = Yii::app()->db->createCommand($qry2)->execute();
											//if ($recordset2)

											$modelsub = new BookingSub();
											if ($modelsub->addExtraCharges($bkg_id, 0, $bkgExtraTotalKm, $bkgExtraTollTax, $bkgExtraStateTax, $bkgParkingCharge, $userInfo, $vendorActualCollected) == true)
											{
												$desc = "Trip Completed" . $location . " and " . $time;
												BookingLog::model()->createLog($bkg_id, $desc, $userInfo, 216, false, $params);
											}

											$returnArr = BookingTrail::model()->updateDriverScoreByBkg($bkg_id, $eventId);
											if (!$returnArr['success'])
											{
												throw new Exception("Driver score is not save in table.\n\t\t" . json_encode($returnArr['errors']));
											}
											if (!$bookingModel->tripMarkComplete($bkg_id, 3))
											{
												throw new Exception("Driver unable to complete trip.\n\t\t" . json_encode($bookingModel->getErrors()));
											}
											$success = DBUtil::commitTransaction($transaction);
											$errors	 = [];
											Logger::create("SUCCESS : " . $success . " - EVENT ID : " . $eventId . " - DESC :" . $desc, CLogger::LEVEL_INFO);
										}
									}
									else
									{
										throw new Exception("OTP not yet saved in table.\n\t\t" . json_encode($model->getErrors()));
									}
								}
								catch (Exception $ex)
								{
									$errors = $ex->getMessage();
									DBUtil::rollbackTransaction($transaction);
								}

								break;
						}

						//if ($eventId != 215 || $eventId != 216 || $eventId != 251 || $eventId != 252)
						if (!in_array($eventId, [215, 216, 251, 252]))
						{
							$transaction = DBUtil::beginTransaction();
							try
							{
								if ($model->validate())
								{
									if (!$model->save())
									{
										throw new Exception("OTP not yet saved in table.\n\t\t" . json_encode($model->getErrors()));
									}
									$model->save();
									$success = DBUtil::commitTransaction($transaction);
									$errors	 = [];
									Logger::create("SUCCESS : " . $success . " - EVENT ID : " . $eventId . " - DESC except (215,216,251,252) :" . $desc, CLogger::LEVEL_INFO);
								}
								else
								{
									throw new Exception("Not Validate.\n\t\t" . json_encode($model->getErrors()));
								}
							}
							catch (Exception $ex)
							{
								$errors = $ex->getMessage();
								DBUtil::rollbackTransaction($transaction);
							}
						}
					}
				}
				else
				{
					$success = true;
					$errors	 = 'Duplicate entry';
				}
			}
			else
			{
				$errors	 = 'Unauthorised Driver';
				$success = false;
			}

			Logger::create("response =>" . json_encode($data), CLogger::LEVEL_INFO);
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => ($success) ? $data : ''
				)
			]);
		});

		// Tracing Trip Comment
		$this->onRest('req.post.tripComments.render', function () {
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);
			if ($check)
			{
				/*
				  $bkgId		 = Yii::app()->request->getParam('bkgId');
				  $desc		 = Yii::app()->request->getParam('remarks');
				  $latitude	 = Yii::app()->request->getParam('latitude');
				  $longitude	 = Yii::app()->request->getParam('longitude');
				 */
				$wholeData1	 = Yii::app()->request->getParam('data');
				$wholeData	 = CJSON::decode($wholeData1, true);

				$bkgId		 = $wholeData['bkgId'];
				$desc		 = $wholeData['remarks'];
				$latitude	 = $wholeData['latitude'];
				$longitude	 = $wholeData['longitude'];

				$userInfo			 = UserInfo::getInstance();
				$userInfo->userType	 = UserInfo::TYPE_DRIVER;

				//$user_id	 = $userInfo->userId;
				$user_id = UserInfo::getEntityId();

				$eventId = BookingLog::REMARKS_ADDED;
				$success = true;
				if ($desc != '')
				{
					$remarks = $desc . ":: (" . $latitude . "," . $longitude . ")";
					$data	 = BookingLog::model()->createLog($bkgId, $remarks, $userInfo, $eventId);
				}
				$dataReader	 = BookingLog::getCommentTraceByDriverId($user_id, $eventId, $bkgId);
				$success	 = ($dataReader->rowCount > 0 ) ? true : false;
				$model		 = $dataReader->readAll();
			}
			else
			{
				$model	 = 'Unauthorised Driver';
				$success = false;
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'data'		 => $model
				)
			]);
		});

		//Trip Comment Sync
		$this->onRest('req.post.tripCommentsSyncData.render', function () {
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);
			if ($check)
			{
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data				 = CJSON::decode($process_sync_data, true);
				foreach ($data as $val)
				{
					$bkgId				 = $val['msg_bkg_id'];
					$desc				 = $val['msg'];
					$latitude			 = $val['msg_lat'];
					$longitude			 = $val['msg_long'];
					$userInfo			 = UserInfo::getInstance();
					$driverId			 = UserInfo::getEntityId();
					$userInfo->userType	 = UserInfo::TYPE_DRIVER;
					$eventId			 = BookingLog::REMARKS_ADDED;
					$success			 = false;
					if ($desc != '')
					{
						$remarks		 = $desc . ":: (" . $latitude . "," . $longitude . ")";
						$blgDriverData	 = ['blg_driver_id' => $driverId];
						$data1			 = BookingLog::model()->createLog($bkgId, $remarks, $userInfo, $eventId, '', $blgDriverData);
						$success		 = true;
					}
				}

				///
			}
			else
			{
				$data	 = 'Unauthorised Driver';
				$success = false;
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'data'		 => $data
				)
			]);
		});

		//trip log
		$this->onRest('req.post.tripTrackingLog.render', function () {

			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);
			if ($check)
			{
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data				 = CJSON::decode($process_sync_data, true);
				$path				 = "";
				$dir				 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'tripTracking';
				if (!is_dir($dir))
				{
					mkdir($dir);
				}
				$dirFolderName = $dir . DIRECTORY_SEPARATOR . 'tripTrackingLog';
				if (!is_dir($dirFolderName))
				{
					mkdir($dirFolderName);
				}
				$dirByBkgId = $dirFolderName . DIRECTORY_SEPARATOR . $data[0]['bkg_id'];
				if (!is_dir($dirByBkgId))
				{
					mkdir($dirByBkgId);
				}
				$success	 = false;
				$file_path	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'tripTracking' . DIRECTORY_SEPARATOR . 'tripTrackingLog' . DIRECTORY_SEPARATOR . $data[0]['bkg_id'];
				$date		 = date("Y-d-m", strtotime($data[0]['time_stamp']));
				if ($data != '')
				{
					$file = $file_path . "/tripTracking_{$date}.csv";
					if (!file_exists($file))
					{
						$csv = "Booking ID, Row Id, Server Sync, Time Stamp, Trip Id, Trip Lat, Trip Long \n";
						foreach ($data as $row)
						{
							$csv .= $row['bkg_id'] . ',' . $row['row_id'] . ',' . $row['server_sync'] . ',' . $row['time_stamp'] . ',' . $row['trip_id'] . ',' . $row['trip_lat'] . ',' . $row['trip_long'];
						}
						file_put_contents($file_path . "/tripTracking_{$date}.csv", $br . $csv . PHP_EOL, FILE_APPEND);
					}
					else
					{
						foreach ($data as $row)
						{
							$csv .= $row['bkg_id'] . ',' . $row['row_id'] . ',' . $row['server_sync'] . ',' . $row['time_stamp'] . ',' . $row['trip_id'] . ',' . $row['trip_lat'] . ',' . $row['trip_long'];
						}
						file_put_contents($file_path . "/tripTracking_{$date}.csv", $br . $csv . PHP_EOL, FILE_APPEND);
					}
					$success = true;
					$message = 'Data Inserted Successfully';
				}
				///
			}
			else
			{
				$message = 'Unauthorised Driver';
				$success = false;
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'message'	 => $message,
					'data'		 => $data
				)
			]);
		});

		$this->onRest('req.post.saveCustomerSignature.render', function () {
			Logger::create("saveCustomerSignature: " . json_encode($_FILES), CLogger::LEVEL_INFO);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);
			Logger::create("Request token =>" . $token, CLogger::LEVEL_TRACE);
			if ($check)
			{
				$success			 = false;
				$message			 = [];
				$userInfo			 = UserInfo::getInstance();
				$userInfo->userType	 = UserInfo::TYPE_DRIVER;

				$driver_id	 = UserInfo::getEntityId();
				$user_id	 = $userInfo->userId;

				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data				 = CJSON::decode($process_sync_data, true);
				$bkg_id				 = $data['bkg_id'];

				Logger::create("Request Signature: " . $process_sync_data, CLogger::LEVEL_INFO);

				$signature_file		 = $_FILES['signature']['name'];
				$signature_file_tmp	 = $_FILES['signature']['tmp_name'];
				$signature_file_size = $_FILES['signature']['size'];

				$process_sync_data2 = "BKG ID : " . $bkg_id . ", FILE : " . $signature_file . " , Driver Id : " . $driver_id;
				Logger::create('saveCustomerSignature get data: ' . $process_sync_data2, CLogger::LEVEL_INFO);

				$transaction = DBUtil::beginTransaction();
				try
				{
					$model	 = new BookingPayDocs();
					$result	 = $this->saveImage($signature_file, $signature_file_tmp, $bkg_id, $type	 = 6);
					if ($driver_id == '' || $driver_id == NULL)
					{
						$getErrors = "Driver Id not found.";
						throw new Exception($getErrors);
					}
					$appToken = AppTokens::model()->getByUserTypeAndUserId($user_id, 5);
					if ($result['path'] == '')
					{
						$getErrors = "Signature not uploaded. Please try again.";
						throw new Exception($getErrors);
					}
					$params = ['bkg_id' => $bkg_id, 'signature_path' => $result['path'], 'device_id' => $appToken['apt_device_uuid']];

					$model->bpay_date			 = new CDbExpression('NOW()');
					$model->bpay_bkg_id			 = $params['bkg_id'];
					$model->bpay_type			 = 6;
					$model->bpay_app_type		 = 5;
					$model->bpay_image_signature = $params['signature_path'];
					$model->bpay_device_id		 = $params['device_id'];
					$model->bpay_status			 = 1;

					if ($model->validate())
					{
						if ($model->save())
						{
							Logger::create('saveCustomerSignature get data:' . $signature_file, CLogger::LEVEL_INFO);

							$desc	 = "Customer Signature uploaded";
							$success = BookingLog::model()->createLog($bkg_id, $desc, $userInfo, BookingLog::SIGNATURE_UPLOAD, false, false);
							if ($success == true)
							{
								$success = DBUtil::commitTransaction($transaction);
								if ($success)
								{
									$message	 = [];
									$resmsg		 = 'Thank you for using aaocab';
									$returnData	 = ['response_message' => $resmsg];
									Logger::create("Final Data.\n\t\t" . json_encode($returnData), CLogger::LEVEL_INFO);
								}
							}
							else
							{
								$getErrors = "Errors in the booking log.";
								throw new Exception($getErrors);
							}
						}
					}
				}
				catch (Exception $ex)
				{
					DBUtil::rollbackTransaction($transaction);
					$message = $getErrors;
					Logger::create("Errors.\n\t\t" . $ex->getMessage(), CLogger::LEVEL_ERROR);
				}
			}
			else
			{
				$error	 = 'Unauthorised Driver';
				$success = false;
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $message,
					'data'		 => ($success) ? $returnData : ''
				)
			]);
		});

		$this->onRest('req.post.saveDriverVoucher.render', function () {
			Logger::create("saveDriverVoucher: " . json_encode($_FILES), CLogger::LEVEL_INFO);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);
			if ($check)
			{

				$success			 = false;
				$message			 = [];
				$userInfo			 = UserInfo::getInstance();
				$userInfo->userType	 = UserInfo::TYPE_DRIVER;
				//$voucher_type		 = Yii::app()->request->getParam('voucher_type');
				//$bkg_id				 = Yii::app()->request->getParam('bkg_id');

				$wholeData1		 = Yii::app()->request->getParam('data');
				$wholeData		 = CJSON::decode($wholeData1, true);
				$voucher_type	 = $wholeData['voucher_type'];
				$bkg_id			 = $wholeData['bkg_id'];

				Logger::create("Request Voucher: " . $wholeData1, CLogger::LEVEL_INFO);

				//$driver_id			 = $userInfo->userId;
				$driver_id	 = UserInfo::getEntityId();
				$user_id	 = $userInfo->userId;

				$voucher_file		 = $_FILES['voucher']['name'];
				$voucher_file_tmp	 = $_FILES['voucher']['tmp_name'];
				$voucher_file_size	 = $_FILES['voucher']['size'];
				$process_sync_data	 = "Voucher Type : " . $voucher_type . " , BKG ID : " . $bkg_id . ", Voucher FILE : " . $voucher_file . " , Driver Id : " . $driver_id;
				Logger::create('saveDriverVoucher get data: ' . $process_sync_data, CLogger::LEVEL_INFO);

				$transaction = DBUtil::beginTransaction();
				try
				{
					$model	 = new BookingPayDocs();
					//$result = BookingPayDocs::model()->saveBookingVendor($voucher_file, $voucher_file_tmp, $bkg_id, $voucher_type);
					$result	 = $this->saveBookingVendor($voucher_file, $voucher_file_tmp, $bkg_id, $voucher_type);

					Logger::create("Upload Data.\n\t\t" . $result, CLogger::LEVEL_INFO);
					if ($driver_id == '' || $driver_id == NULL)
					{
						$getErrors = "Driver Id not found.";
						throw new Exception($getErrors);
					}
					$appToken = AppTokens::model()->getByUserTypeAndUserId($user_id, 5);

					if ($result['path'] == '')
					{
						$getErrors = "Voucher not uploaded. Please try again.";
						throw new Exception($getErrors);
					}
					$params = ['bkg_id' => $bkg_id, 'voucher_type' => $voucher_type, 'voucher_path' => $result['path'], 'device_id' => $appToken['apt_device_uuid']];

					$model->bpay_date		 = new CDbExpression('NOW()');
					$model->bpay_bkg_id		 = $params['bkg_id'];
					$model->bpay_type		 = $voucher_type;
					$model->bpay_app_type	 = 5;
					$model->bpay_image		 = $params['voucher_path'];
					$model->bpay_device_id	 = $params['device_id'];
					$model->bpay_status		 = 1;

					if ($model->validate())
					{

						if ($model->save())
						{
							Logger::create('saveDriverVoucher get data:' . $voucher_file1, CLogger::LEVEL_INFO);
							$voucherTypeName = $model->getTypeByVoucherId($model->bpay_type);
							$desc			 = "Voucher Type : " . $voucherTypeName;
							$success		 = BookingLog::model()->createLog($bkg_id, $desc, $userInfo, BookingLog::VOUCHER_UPLOAD, false, false);
							if ($success == true)
							{
								$success = DBUtil::commitTransaction($transaction);
								if ($success)
								{
									$message	 = [];
									$returnData	 = ['bkg_id' => (int) $bkg_id, 'voucher_type' => (int) $voucher_type, 'voucher_path' => $result['path'], 'bpay_id' => $model['bpay_id']];
									Logger::create("Final Data.\n\t\t" . json_encode($returnData), CLogger::LEVEL_INFO);
								}
							}
							else
							{
								$getErrors = "Errors in the booking log.";
								throw new Exception($getErrors);
							}
						}
					}
					else
					{
						$getErrors = $model->getErrors();
						throw new Exception("Validation Errors :: " . $getErrors);
					}
				}
				catch (Exception $ex)
				{

					DBUtil::rollbackTransaction($transaction);
					$message = $getErrors;
					Logger::create("Errors.\n\t\t" . $ex->getMessage(), CLogger::LEVEL_ERROR);
				}


				////
			}
			else
			{
				$error	 = 'Unauthorised Driver';
				$success = false;
			}



			Logger::create('response:' . json_encode(['type' => 'raw', 'data' => array('success' => $success, 'errors' => $message, 'data' => ($success) ? $returnData : '')]), CLogger::LEVEL_INFO);

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $message,
					'data'		 => ($success) ? $returnData : ''
				)
			]);
		});

		$this->onRest('req.post.removeDriverVoucher.render', function () {
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);
			if ($check)
			{
				$success = false;
				$errors	 = [];
				//$bpay_id			 = Yii::app()->request->getParam('bpay_id');
				//$bpay_id				 = 156;

				$wholeData1	 = Yii::app()->request->getParam('data');
				$wholeData	 = CJSON::decode($wholeData1, true);
				$bpay_id	 = $wholeData['bpay_id'];

				$removeVoucher		 = BookingPayDocs::model()->findByPk($bpay_id);
				$driverId			 = $removeVoucher->bpayBkg->bkgBcb->bcb_driver_id;
				$userInfo			 = UserInfo::getInstance();
				$userInfo->userType	 = UserInfo::TYPE_DRIVER;
				Logger::create("removeDriverVoucher data: " . json_encode($userInfo), CLogger::LEVEL_INFO);

				$removeVoucher->bpay_status = 0;
				if ($removeVoucher->save())
				{

					$bkg_id			 = $removeVoucher->bpay_bkg_id;
					$voucherTypeName = BookingPayDocs::model()->getTypeByVoucherId($removeVoucher->bpay_type);
					$desc			 = "Voucher Type : " . $voucherTypeName;
					$success		 = BookingLog::model()->createLog($bkg_id, $desc, $userInfo, BookingLog::VOUCHER_DELETED, false, false);

					//unlink(Yii::app()->basePath . "/../" . $removeVoucher->bpay_image);
					//$success = true;
					$errors = [];
				}


				////
			}
			else
			{
				$error	 = 'Unauthorised Driver';
				$success = false;
			}


			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors
				)
			]);
		});

		$this->onRest('req.post.listDriverVoucher.render', function () {

			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);
			if ($check)
			{
				$success = false;
				$errors	 = [];
				//$bkg_id			 = Yii::app()->request->getParam('bkg_id');
				//$bkg_id				 = 473071;//Yii::app()->request->getParam('bkg_id');

				$wholeData1	 = Yii::app()->request->getParam('data');
				$wholeData	 = CJSON::decode($wholeData1, true);
				$bkg_id		 = $wholeData['bkg_id'];

				$vendorDoclist = BookingPayDocs::model()->getVendorDocList($bkg_id);
				if ($bkg_id)
				{
					$success = true;
					$errors	 = [];
					$docList = $vendorDoclist;
				}
			}
			else
			{
				$errors	 = 'Unauthorised Driver';
				$success = false;
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => ($success) ? $docList : ''
				)
			]);
		});

		$this->onRest('req.post.driver_account_bonus.render', function () {

			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);
			if ($check)
			{
				$userInfo = UserInfo::getInstance();

				//$driverId			 = $userInfo->userId;
				//$driverId			 = Yii::app()->user->getId();
				$driverId = UserInfo::getEntityId();

				$redeemDriverBonus	 = Yii::app()->params['redeemDriverBonus'];
				$tripArr			 = [];
				/* 				
				  $date1				 = Yii::app()->request->getParam('date1');
				  $date2				 = Yii::app()->request->getParam('date2');
				 */

				$wholeData1	 = Yii::app()->request->getParam('data');
				$wholeData	 = CJSON::decode($wholeData1, true);
				$date1		 = $wholeData['date1'];
				$date2		 = $wholeData['date2'];

				$newDate1	 = ($date1 != '') ? date('Y-m-d', strtotime($date1)) : date('Y-m-d', strtotime("-30 days"));
				$newDate2	 = ($date2 != '') ? date('Y-m-d', strtotime($date2)) : date('Y-m-d');
				$tripArr	 = AccountTransDetails::driverTransactionList($driverId, $newDate1, $newDate2);
				$i			 = 0;
				foreach ($tripArr as $t)
				{
					$aArr[$i]['booking_id']			 = $t['booking_id'];
					$aArr[$i]['drv_trans_date']		 = $t['drv_trans_date'];
					$aArr[$i]['drv_createdate']		 = $t['drv_createdate'];
					$aArr[$i]['drv_bonus_amount']	 = -1 * ($t['drv_bonus_amount']);
					$aArr[$i]['drv_remarks']		 = $t['drv_remarks'];
					$aArr[$i]['adm_name']			 = $t['adm_name'];
					$aArr[$i]['ledgerNames']		 = $t['ledgerNames'];
					$aArr[$i]['openBalance']		 = $t['openBalance'];
					$aArr[$i]['runningBalance']		 = -1 * ($t['runningBalance']);

					$i++;
				}
				$tripArr			 = ($aArr) ? $aArr : [];
				$driverBonusAmount	 = AccountTransDetails::calBonusAmountByDriverId($driverId);
				$totalbouns			 = ($driverBonusAmount['bonus_amount'] < 0) ? (-1 * $driverBonusAmount['bonus_amount']) : 0;
				//$isAccountAdded		 = (int) DriversAddDetails::model()->isBankAccountAdded($driverId);
				$isAccountAdded		 = (int) Drivers::model()->isBankAccountAdded($driverId);
				$success			 = true;

				/////////////////
				$driverAddDetailsmodel = DriversAddDetails::model()->findByDriverId($driverId);
				if (empty($driverAddDetailsmodel))
				{
					$driverAddDetailsmodel				 = new DriversAddDetails();
					$driverAddDetailsmodel->dad_drv_id	 = $driverId;
					$driverAddDetailsmodel->dad_active	 = 1;
					$driverAddDetailsmodel->save();
				}
				//////////////////
			}
			else
			{
				$tripArr = 'Unauthorised Driver';
				$success = false;
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $success,
					'isAccountAdded' => $isAccountAdded,
					'totalbouns'	 => $totalbouns,
					'redeem'		 => $redeemDriverBonus,
					'data'			 => $tripArr
				)
			]);
		});

//		$this->onRest('req.post.redeem_driver_bonus.render', function()
//		{
//
//			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
//			$check	 = Drivers::model()->authoriseDriver($token);
//			if ($check)
//			{
//				$success = false;
//				$msg	 = "";
//
//
//				//$isAccountAdded		 = Yii::app()->request->getParam('isAccountAdded');
//				//$isAccountAdded		 = 1;
//				$wholeData1		 = Yii::app()->request->getParam('data');
//				$wholeData		 = CJSON::decode($wholeData1, true);
//				$isAccountAdded	 = $wholeData['isAccountAdded'];
//
//
//				$redeemDriverBonus = Yii::app()->params['redeemDriverBonus'];
//
//
//
//				$userInfo = UserInfo::getInstance();
//
//				//$driverId			 = $userInfo->userId;
//				//$driverId = 3736;
//				$driverId = UserInfo::getEntityId();
//
//				$data	 = array(
//					'dad_bank_name'			 => null,
//					'dad_bank_branch'		 => null,
//					'dad_beneficiary_name'	 => null,
//					'dad_account_type'		 => null,
//					'dad_bank_ifsc'			 => null,
//					'dad_bank_account_no'	 => null,
//					'dad_beneficiary_id'	 => null);
//				$model	 = DriversAddDetails::model()->findByDriverId($driverId);
//				$dmodel	 = Drivers::model()->findByPk($driverId);
//				if ($model->dad_id != '')
//				{
//					$data['dad_bank_name']			 = $dmodel->drvContact->ctt_bank_name;
//					$data['dad_bank_branch']		 = $dmodel->drvContact->ctt_bank_branch;
//					$data['dad_beneficiary_name']	 = $dmodel->drvContact->ctt_beneficiary_name;
//					$data['dad_account_type']		 = (int) $dmodel->drvContact->ctt_account_type;
//					$data['dad_bank_ifsc']			 = $dmodel->drvContact->ctt_bank_ifsc;
//					$data['dad_bank_account_no']	 = $dmodel->drvContact->ctt_bank_account_no;
//					$data['dad_beneficiary_id']		 = $dmodel->drvContact->ctt_beneficiary_id;
//				}
//				$data['drv_name']	 = $dmodel->drv_name;
//				$data['drv_phone']	 = ContactPhone::model()->getContactPhoneById($dmodel->drv_contact_id);
//				switch ($isAccountAdded)
//				{
//					case 0:
//						Logger::create("Get Data.\n\t\t" . json_encode($userInfo) . " , isAccountAdded => " . $isAccountAdded, CLogger::LEVEL_INFO);
//						//$isAccountAdded	 = (int) DriversAddDetails::model()->isBankAccountAdded($driverId);
//						$isAccountAdded	 = (int) Drivers::model()->isBankAccountAdded($driverId);
//						$msg			 = ($isAccountAdded == 0) ? "Please update your bank details." : "";
//						$success		 = true;
//						break;
//					case 1:
//						//$bkgId			 = Yii::app()->request->getParam('bkg_id');
//						//$redeemBonus	 = round(Yii::app()->request->getParam('redeemBonus'));
//						$redeemBonus	 = $wholeData['redeemBonus'];
//						Logger::create("Get Data.\n\t\t" . json_encode($userInfo) . " , isAccountAdded => " . $isAccountAdded . " , redeemBonus" . $redeemBonus, CLogger::LEVEL_INFO);
//						if (($redeemDriverBonus <= $redeemBonus) && $isAccountAdded > 0)
//						{
//							$driverRedeemRequest = DriversAddDetails::model()->findByPk($model->dad_id);
//
//							if ($driverRedeemRequest->dad_redeem_request != 1)
//							{
//								$driverRedeemRequest->dad_redeem_amount	 = $redeemBonus;
//								$driverRedeemRequest->dad_redeem_request = 1;
//								$driverRedeemRequest->dad_request_date	 = new CDbExpression('NOW()');
//								if ($driverRedeemRequest->validate())
//								{
//									if ($driverRedeemRequest->save())
//									{
//										$success = true;
//										$msg	 = "Your Request has been submitted successfully";
//									}
//								}
//							}
//							else
//							{
//								$success = true;
//								$msg	 = "Your Request is already submitted";
//							}
//						}
//						else
//						{
//							$msg = "Redeem amount must be equal or greater than $redeemDriverBonus";
//						}
//						break;
//				}
//				Logger::create("Final Data.\n\t\t , isAccountAdded => " . $isAccountAdded . " , redeem => " . $redeemBonus . " , systemRedeem => " . $redeemDriverBonus, CLogger::LEVEL_INFO);
//			}
//			else
//			{
//				$msg	 = 'Unauthorised Driver';
//				$success = false;
//			}
//
//			return $this->renderJSON([
//						'type'	 => 'raw',
//						'data'	 => array(
//							'success'		 => $success,
//							'msg'			 => $msg,
//							'isAccountAdded' => $isAccountAdded,
//							'redeem'		 => $redeemDriverBonus,
//							'data'			 => $data
//						)
//			]);
//		});


		$this->onRest('req.post.redeem_driver_bonus.render', function () {

			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);
			if ($check)
			{
				$success			 = false;
				$msg				 = "";
				$data				 = Yii::app()->request->rawBody;
				$wholeData			 = CJSON::decode($data, true);
				$redeemDriverBonus	 = $wholeData['redeemBonus'];
				$userInfo			 = UserInfo::getInstance();
				$driverId			 = UserInfo::getEntityId();

				$data	 = array(
					'dad_bank_name'			 => null,
					'dad_bank_branch'		 => null,
					'dad_beneficiary_name'	 => null,
					'dad_account_type'		 => null,
					'dad_bank_ifsc'			 => null,
					'dad_bank_account_no'	 => null,
					'dad_beneficiary_id'	 => null);
				$model	 = DriversAddDetails::model()->findByDriverId($driverId);
				$dmodel	 = Drivers::model()->findByPk($driverId);
				if ($model->dad_id != '')
				{
					$data['dad_bank_name']			 = $dmodel->drvContact->ctt_bank_name;
					$data['dad_bank_branch']		 = $dmodel->drvContact->ctt_bank_branch;
					$data['dad_beneficiary_name']	 = $dmodel->drvContact->ctt_beneficiary_name;
					$data['dad_account_type']		 = (int) $dmodel->drvContact->ctt_account_type;
					$data['dad_bank_ifsc']			 = $dmodel->drvContact->ctt_bank_ifsc;
					$data['dad_bank_account_no']	 = $dmodel->drvContact->ctt_bank_account_no;
					$data['dad_beneficiary_id']		 = $dmodel->drvContact->ctt_beneficiary_id;
				}
				$data['drv_name']	 = $dmodel->drv_name;
				$data['drv_phone']	 = ContactPhone::model()->getContactPhoneById($dmodel->drv_contact_id);
				if ($redeemDriverBonus > 0)
				{
					$isAccountAdded = (int) Drivers::model()->isBankAccountAdded($driverId);
					if ($isAccountAdded > 0)
					{
						$driverRedeemRequest = DriversAddDetails::model()->findByPk($model->dad_id);
						if ($driverRedeemRequest->dad_redeem_request != 1)
						{
							$driverRedeemRequest->dad_redeem_amount	 = $redeemBonus;
							$driverRedeemRequest->dad_redeem_request = 1;
							$driverRedeemRequest->dad_request_date	 = new CDbExpression('NOW()');
							if ($driverRedeemRequest->validate())
							{
								if ($driverRedeemRequest->save())
								{
									$success = true;
									$msg	 = "Your Request has been submitted successfully";
								}
							}
						}
						else
						{
							$success = true;
							$msg	 = "Your Request is already submitted";
						}
					}
					else
					{
						$msg	 = 'Please update your bank details.';
						$success = false;
					}
				}
			}
			else
			{
				$msg	 = 'Unauthorised Driver';
				$success = false;
			}
			$respone = ['success'		 => $success,
				'msg'			 => $msg,
				'isAccountAdded' => $isAccountAdded,
				'redeem'		 => $redeemDriverBonus,
				'data'			 => $data];
			Logger::trace("<===Response===>" . json_encode($respone));
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $success,
					'msg'			 => $msg,
					'isAccountAdded' => $isAccountAdded,
					'redeem'		 => $redeemDriverBonus,
					'data'			 => $data
				)
			]);
		});

//    ================= DEPRECATED===============
		$this->onRest('req.post.driver_ride_complete.render', function () {

			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);
			if ($check)
			{
				$success	 = false;
				$errors		 = [];
				$wholeData1	 = Yii::app()->request->getParam('data');
				$wholeData	 = CJSON::decode($wholeData1, true);
				$bkg_id		 = $wholeData['bkg_id'];

				$model									 = Booking::model()->findByPk($bkg_id);
				$userInfo								 = UserInfo::getInstance();
				$userInfo->userType						 = UserInfo::TYPE_DRIVER;
				$model->bkgTrack->bkg_ride_complete		 = 1;
				$model->bkgTrack->bkg_trip_end_time		 = new CDbExpression('NOW()');
				$model->bkgTrack->bkg_trip_end_user_id	 = $userInfo->userId;
				$model->bkgTrack->bkg_trip_end_user_type = $userInfo->userType;

				if ($model->bkgTrack->save())
				{
					$success = true;
					$errors	 = [];
//					$eventId						 = BookingLog::REF_RIDE_COMPLETE;
//					$desc							 = "Ride completed by the driver.";
//					$params['blg_booking_status']	 = $model->bkg_status;
//					BookingLog::model()->createLog($bkg_id, $desc, $userInfo, $eventId, false, $params);
				}
				else
				{
					$success = false;
					$errors	 = "Something went wrong";
				}
			}
			else
			{
				$errors	 = 'Unauthorised Driver';
				$success = false;
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
				)
			]);
		});
		//Validate Driver With booking
		/**
		 * @deprecated 
		 * New service : drvBookingValidation_V1		  
		 */
		$this->onRest('req.post.drvBookingValidation.render', function () {
			$returnSet = new ReturnSet();

			$process_sync_data	 = Yii::app()->request->getParam('data');
			throw new CHttpException(400, "This app has been discontinued. Please install & use Gozo Partner+ app from Google play store.", ReturnSet::ERROR_NO_RECORDS_FOUND);
			Logger::create("driver::booking::drvBookingValidation request: " . $process_sync_data);
			$data				 = CJSON::decode($process_sync_data, true);
			$driverModel		 = Drivers::model()->getDetailsByBkgId($data['bkg_id']);
			$bkgList			 = Drivers::model()->getBkgIdByDriverId($driverModel['drv_id']);
			if ($bkgList)
			{
				$currentBkgId = $bkgList['next_bkg_id'];
			}
			else
			{
				$currentBkgId = $driverModel['bkg_id'];
			}
			Logger::create("driver::booking::drvBookingValidation currentBkgId: " . $currentBkgId);
			try
			{
				if ($data != '' && $driverModel)
				{
					$data = ['driverInfo' => $driverModel, 'currentBkgId' => $currentBkgId];
					$returnSet->setStatus(true);
					$returnSet->setData($data);
				}
				else
				{
					$returnSet->setStatus(false);
					$returnSet->setErrors(['error' => "Driver missing primary/verified phone no."]);
				}
			}
			catch (Exception $e)
			{
				$returnSet = $returnSet->getError($e);
			}
			Logger::create("driver::booking::drvBookingValidation currentBkgId: " . json_encode($returnSet));
			return $this->renderJSON([
				'data' => $returnSet
			]);
		});

		$this->onRest('req.post.sendOtpToCustomer.render', function () {
			$returnSet			 = new ReturnSet();
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($process_sync_data, true);
			try
			{
				if ($data != '')
				{
					Logger::create("Error: " . json_encode($data), CLogger::LEVEL_INFO);
					$result = Users::model()->sendTripOtp($data['bkg_id']);
					if ($result == true)
					{
						$returnSet->setStatus(true);
						$data = ['msg' => 'OTP is being sent to customer by SMS. Tell customer to give you OTP at time of starting trip.'];
						$returnSet->setData($data);
						Logger::create("data list: " . json_encode($data), CLogger::LEVEL_INFO);
					}
					else
					{
						$returnSet->setStatus(false);
						$returnSet->addError("Invalid Booking", ReturnSet::ERROR_INVALID_BOOKING);
					}
				}
				else
				{
					$returnSet->setStatus(false);
					$returnSet->addError("Invalid Booking", ReturnSet::ERROR_INVALID_BOOKING);
				}
			}
			catch (Exception $e)
			{
				$returnSet = $returnSet->getError($e);
			}
			return $this->renderJSON([
				'data' => $returnSet,
			]);
		});

		$this->onRest("req.post.currentListNew.render", function () {
			return $this->renderJSON($this->currentListNew());
		});

		/*
		 * Validate Driver With booking (NEW)
		 */
		$this->onRest('req.post.drvBookingValidation_V1.render', function () {
			return $this->renderJSON($this->drvBookingValidationCheck());
		});

		/*
		 * Resend Otp To Driver 
		 */
		$this->onRest('req.post.resendOtpToDriver.render', function () {
			return $this->renderJSON($this->resendOtpToDriver());
		});

		/*
		 * Validate Otp From Driver 
		 */
		$this->onRest('req.post.validateOtpFromDriver.render', function () {
			return $this->renderJSON($this->validateOtpFromDriver());
		});
		/*
		 * validate driver send otp for login
		 */
		$this->onRest('req.post.drvContactValidation_V2.render', function () {
			return $this->renderJSON($this->drvContactValidation_V2());
		});
		$this->onRest('req.post.otpVerifyForLogin.render', function () {
			return $this->renderJSON($this->otpVerifyForLogin());
		});

		$this->onRest('req.post.driverCarBreakDown.render', function () {
			return $this->renderJSON($this->driverCarBreakDown());
		});

		$this->onRest('req.post.cancelCMB.render', function () {
			return $this->renderJSON($this->cancelCMB());
		});
		$this->onRest('req.post.getDriverAllocatedDetails.render', function () {

			return $this->renderJSON($this->getDriverAllocatedDetails());
		});

		$this->onRest('req.post.responseReadyToGo.render', function () {

			return $this->renderJSON($this->responseReadyToGo());
		});
		$this->onRest('req.post.responseDenyToGo.render', function () {

			return $this->renderJSON($this->responseDenyToGo());
		});
		$this->onRest('req.post.bidAdd.render', function () {
			return $this->renderJSON($this->bidAdd());
		});
		$this->onRest('req.post.bidDenny.render', function () {
			return $this->renderJSON($this->bidDenny());
		});
	}

	/**
	 * This function is used for cancel call back request created by driver
	 * @return $returnSet
	 * @throws Exception
	 */
	public function cancelCMB()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$driverId	 = UserInfo::getEntityId();
			$userId		 = UserInfo::getUserId();
			if (!$driverId)
			{
				throw new Exception("Unauthorized Driver", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonval = Yii::app()->request->rawBody;
			$jsonObj = CJSON::decode($jsonval, true);
			if (empty($jsonObj))
			{
				throw new Exception("Mandatory data not passed", ReturnSet::ERROR_INVALID_DATA);
			}
			$scqId = $jsonObj['scqId'];

			$row = ServiceCallQueue::deactivateById($userId, $scqId);
			if ($row > 0)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("call back cancelled successfully");
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used for creating call back request for driver car break down
	 * @return $returnSet
	 * @throws Exception
	 */
	public function driverCarBreakDown()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$jsonval = Yii::app()->request->rawBody;
			$data	 = CJSON::decode($jsonval, true);
			if (!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$entityId	 = UserInfo::getEntityId();
			$entityType	 = UserInfo::TYPE_DRIVER;
			if (!$entityId)
			{
				throw new Exception("Unauthorized Driver", ReturnSet::ERROR_INVALID_DATA);
			}
			if (empty(trim($data['desc'])))
			{
				throw new Exception("Remark is mandatory ", ReturnSet::ERROR_INVALID_DATA);
			}
			$userId = UserInfo::getUserId();
			if (!$userId)
			{
				throw new Exception("Unauthorized User", ReturnSet::ERROR_INVALID_DATA);
			}
			$scqType = $data['scqType'];
			$bkgId	 = $data['refId'];
			if ($scqType != null && $bkgId != null)
			{
				$followupId = ServiceCallQueue::getIdByUserId($userId, $scqType, $bkgId);
			}
			else
			{
				$followupId = ServiceCallQueue::getIdByUserId($userId, $scqType);
			}
			$success = false;
			if ($followupId > 0)
			{
				$fpModel		 = ServiceCallQueue::model()->findbyPk(followupId);
				$queueData		 = ServiceCallQueue::getQueueNumber($followupId, $fpModel->scq_follow_up_queue_type);
				$queNo			 = $queueData['queNo'];
				$waitTime		 = $queueData['waitTime'];
				$followupCode	 = $fpModel->scq_unique_code;
				$data			 = ['followupId'	 => $followupId, 'followupCode'	 => $followupCode, 'queNo'			 => $queNo, 'waitTime'		 => $waitTime,
					'active'		 => 1];
				$success		 = true;
				$returnSet->setData($data);
				$returnSet->setStatus($success);
				if ($returnSet->getStatus())
				{
					$returnSet->setMessage('Call Back request has already been initiated please cancel  it to create it again . All the calls will be recorded for training and quality assurance purposes.');
				}
			}
			else
			{
				$contactId									 = ContactProfile::getByEntityId($entityId, UserInfo::TYPE_DRIVER);
				$arrPhoneByPriority							 = Contact::getPhoneNoByPriority($contactId);
				$code										 = $arrPhoneByPriority['phn_phone_country_code'];
				$number										 = $arrPhoneByPriority['phn_phone_no'];
				$model										 = new ServiceCallQueue();
				$model->scq_follow_up_queue_type			 = $scqType == 9 ? ServiceCallQueue::TYPE_IMNTERNAL : $scqType;
				$model->scq_to_be_followed_up_with_value	 = $code . $number;
				$model->scq_creation_comments				 = trim($data['desc']);
				$model->scq_creation_comments				 = filter_var($model->scq_creation_comments, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$model->contactRequired						 = 0;
				$model->scq_to_be_followed_up_with_entity_id = $entityId;
				$model->scq_to_be_followed_up_with_contact	 = ContactProfile::getByEntityId($entityId, $entityType);
				$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
				if ($scqType == 6)
				{
					$model->scq_to_be_followed_up_by_type	 = 1;
					$model->scq_to_be_followed_up_by_id		 = 9;
				}
				if (isset($bkgId) && trim($bkgId) != '')
				{
					$model->scq_related_bkg_id = $bkgId;
				}
				$platform						 = ServiceCallQueue::PLATFORM_DRIVER_APP;
				$model->scq_follow_up_priority	 = 5;
				$returnSet						 = ServiceCallQueue::model()->create($model, $entityType, $platform);
				if ($returnSet->getStatus())
				{
					$returnSet->setMessage('We will call you or inform via notification to give an update. Calls could be recorded for quality and training purposees.');
				}


				$desc		 = "Driver informed that car breakdown happend";
				$userInfo	 = UserInfo::getInstance();
				$eventId	 = BookingLOG::BOOKING_CAR_BREAKDOWN;
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId, false, false);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		Logger::trace("<===Response===>" . json_encode($returnSet));
		return $returnSet;
	}

	public function actionUploads()
	{
		$bkg_id = Yii::app()->request->getParam('bkg_id');
		$this->render('uploads', array('bkg_id' => $bkg_id));
	}

	public function getDestinationNoteList()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{

			AppTokens::validateToken($token);

			Logger::create("Token : " . $token, CLogger::LEVEL_INFO);
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj = CJSON::decode($data, false);
			$bkg_id	 = $jsonObj->bookingId;
			if ($bkg_id == "")
			{
				$tripId	 = $jsonObj->tripId;
				$bkgArr	 = BookingCab::model()->getBkgIdByTripId($tripId);
				$bkg_id	 = $bkgArr['bkg_ids'];
			}
			if (!bkg_id)
			{
				throw new Exception("Invalid Booking Id.", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Driver Show Detination Request : " . $data, CLogger::LEVEL_INFO);
			$userInfo	 = UserInfo::getInstance();
			$userId		 = $userInfo->userId;
			if (!$userId)
			{
				throw new Exception("Invalid User.", ReturnSet::ERROR_UNAUTHORISED);
			}
			$userInfo->userType	 = UserInfo::getUserType(); //Driver type = 3
			$userInfo->platform	 = 3; //Platform type =3	

			$noteArrList = DestinationNote::model()->showNoteApi($bkg_id, $showNoteTo	 = 3);
			$response	 = [];
			$jsonMapper	 = new JsonMapper();
			if ($noteArrList != false || $noteList != NULL)
			{
				/** @var $res \Stub\common\DestinationNote */
				$res		 = new \Stub\common\DestinationNote();
				$responseDt	 = $res->getData($noteArrList);
			}
			foreach ($responseDt as $res)
			{
				$responsedt->dataList[] = $res;
			}
			$response = $responsedt;

			if (!$response)
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("No Record Found.");
			}
			else
			{
				$returnSet->setData($response);
				$returnSet->setStatus(true);
			}
			Logger::create("Driver Show Detination Response  : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function addDestinationNote()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			AppTokens::validateToken($token);
			Logger::create("Token : " . $token, CLogger::LEVEL_INFO);
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj			 = CJSON::decode($data, false);
			Logger::create("Driver Add Detination Note Request : " . $data, CLogger::LEVEL_INFO);
			$userInfo			 = UserInfo::getInstance();
			$userInfo->userType	 = UserInfo::getUserType(); //Driver type = 3
			$jsonMapper			 = new JsonMapper();
			/** @var $obj \Stub\common\DestinationNote */
			$obj				 = $jsonMapper->map($jsonObj, new \Stub\common\DestinationNote());
			$model				 = $obj->getModel();

			$result = DestinationNote::addNote($model);
			if ($result['success'] == true)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Add Destination Note By Driver.");
			}
			else
			{
				throw new Exception(CJSON::encode($result['error']), ReturnSet::ERROR_VALIDATION);
			}
			Logger::create("Driver Add Detination Note Response  : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet	 = $returnSet->setException($ex);
			$errorMsg	 = "Please enter valid data and try again";
			$returnSet->setMessage($errorMsg);
		}
		return $returnSet;
	}

	public function getDestinationNoteAreaType()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			AppTokens::validateToken($token);
			Logger::create("Token : " . $token, CLogger::LEVEL_INFO);
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($process_sync_data, true);
			$searchAreaType		 = $data['areaType'];
			if ($searchAreaType == 'city')
			{
				$dataAreaType = Cities::getAllCityListDrop();
			}
			else
			{
				$dataAreaType = States::model()->getJSON();
			}
			$dataList = json_decode($dataAreaType);
			if (!$dataList)
			{
				throw new Exception('Invalid Data : ', ReturnSet::ERROR_INVALID_DATA);
			}
			$response	 = [];
			$jsonMapper	 = new JsonMapper();
			if ($dataList != false || $dataList != NULL)
			{
				/** @var $res \Stub\common\DestinationNote */
				$res		 = new \Stub\common\DestinationNote();
				$response	 = $res->getAreaData($dataList);
			}
			foreach ($response as $res)
			{
				$responsedt->dataList[] = $res;
			}
			$returnSet->setData($responsedt);
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function saveImage($image, $imagetmp, $bkgId, $type)
	{
		try
		{
			$path = "";
			if ($image != '')
			{
				$image	 = $bkgId . "-" . $type . "-" . date('YmdHis') . "." . $image;
				$dir	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
				if (!is_dir($dir))
				{
					mkdir($dir);
				}
				$dirFolderName = $dir . DIRECTORY_SEPARATOR . 'bookings';
				if (!is_dir($dirFolderName))
				{
					mkdir($dirFolderName);
				}
				$dirByBkgId = $dirFolderName . DIRECTORY_SEPARATOR . $bkgId;
				if (!is_dir($dirByBkgId))
				{
					mkdir($dirByBkgId);
				}
				$file_path	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'bookings' . DIRECTORY_SEPARATOR . $bkgId;
				$file_name	 = basename($image);
				$f			 = $file_path;
				$file_path	 = $file_path . DIRECTORY_SEPARATOR . $file_name;
				file_put_contents(PUBLIC_PATH . '/testFile.txt', $f . ' ==== ' . $file_name);
				Yii::log("Image Path: \n\t Temp: " . $imagetmp . "\n\t Path: " . $f, CLogger::LEVEL_INFO, 'system.api.images');
				if (Vehicles::model()->img_resize($imagetmp, 1200, $f, $file_name))
				{
					$path	 = substr($file_path, strlen(PUBLIC_PATH));
					$result	 = ['path' => $path];
				}
			}
		}
		catch (Exception $e)
		{
			Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
			Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
			throw $e;
		}
		return $result;
	}

	public function saveBookingVendor($image, $imagetmp, $bkgId, $type)
	{
		try
		{
			$path = "";
			if ($image != '')
			{
				$image = $bkgId . "-" . $type . "-" . date('YmdHis') . "." . $image;

				$dir = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
				if (!is_dir($dir))
				{
					mkdir($dir);
				}
				$dirFolderName = $dir . DIRECTORY_SEPARATOR . 'bookings';
				if (!is_dir($dirFolderName))
				{
					mkdir($dirFolderName);
				}
				$dirByBookingId = $dirFolderName . DIRECTORY_SEPARATOR . $bkgId;

				if (!is_dir($dirByBookingId))
				{
					mkdir($dirByBookingId);
				}
				$file_path = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'bookings' . DIRECTORY_SEPARATOR . $bkgId;

				$file_name	 = basename($image);
				$f			 = $file_path;
				$file_path	 = $file_path . DIRECTORY_SEPARATOR . $file_name;

				file_put_contents(PUBLIC_PATH . '/testFile.txt', $f . ' ==== ' . $file_name);
				Logger::create("Image Path: \n\t Temp: " . $imagetmp . "\n\t Path: " . $f, CLogger::LEVEL_INFO, 'system.api.images');
				if (Vehicles::model()->img_resize($imagetmp, 3500, $f, $file_name))
				{
					$path	 = substr($file_path, strlen(PUBLIC_PATH));
					$result	 = ['path' => $path];
				}
			}
		}
		catch (Exception $e)
		{
			Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
			Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
			throw $e;
		}
		return $result;
	}

	public function drvBookingValidationCheck()
	{
		$returnSet = new ReturnSet();
		try
		{
			$returnSet			 = new ReturnSet();
			$process_sync_data	 = Yii::app()->request->getParam('data');
			throw new CHttpException(400, "This app has been discontinued. Please install & use Gozo Partner+ app from Google play store.", ReturnSet::ERROR_NO_RECORDS_FOUND);
			if (!$process_sync_data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$data			 = CJSON::decode($process_sync_data, true);
			$driverModel	 = Drivers::model()->getDetailsByBkgId($data['bkg_id']);
			$currentBkgId	 = $driverModel['bkg_id'];

			$driverModel['ctt_license_no']	 = str_replace(' ', '', $driverModel['ctt_license_no']); // remove space
			$bkgStatus						 = Booking::model()->getStatusByCode($data['bkg_id']);
			if ($bkgStatus != 5)
			{
				throw new Exception("Booking is not valid.", ReturnSet::ERROR_INVALID_DATA);
			}
			if (empty($driverModel['bkg_driver_number']))
			{
				throw new Exception("Contact is not linked with your driver. Please ask your vendor to add a phone number . ", ReturnSet::ERROR_INVALID_DATA);
			}
			if ($driverModel['phn_is_verified'] == 0)
			{
				ContactPhone::resendContactVerificationOtp($driverModel['bkg_driver_number'], $driverModel['drv_contact_id'], UserInfo::TYPE_DRIVER, $data['bkg_id']);
			}
			$driverModel['phn_otp']	 = null;
			$islinked				 = Drivers::model()->checkSocialLinking($driverModel['drv_id']);
			$data					 = ['driverInfo' => $driverModel, 'currentBkgId' => $currentBkgId, 'isPhoneVerified' => (int) $driverModel['phn_is_verified'], 'isLinked' => (int) $islinked];
			$returnSet->setStatus(true);
			$returnSet->setData($data);
			Logger::info("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function drvContactValidation_V2()
	{
		$returnSet = new ReturnSet();
		try
		{
			$process_sync_data = Yii::app()->request->rawBody;
			if (!$process_sync_data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$drvData	 = CJSON::decode($process_sync_data, true);
			$obj		 = Yii::app()->request->getJSONObject(new \Stub\driver\TempLoginRequest());
			/* echo $obj->phoneNumber;
			  if($obj->phoneNumber!=$driverModel['bkg_driver_number'])
			  {
			  throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			  } */
			$driverModel = Drivers::model()->getDetailsByBkgId($obj->bkg_id);

			$verifyCode	 = rand(10000, 99999);
			//$result			 = Drivers::sendLoginVerificationOtp($driverModel['bkg_driver_number'], $driverModel['bkg_driver_code']);
			Filter::parsePhoneNumber($driverModel['bkg_driver_number'], $code, $number);
			$isSend		 = smsWrapper::sendOtp($code, $number, $verifyCode, SmsLog::SMS_LOGIN_REGISTER);

			$drvencData['otp']		 = $verifyCode;
			$drvencData['driverId']	 = $driverModel['drv_id'];
			$data1					 = Filter::removeNull(json_encode($drvencData));
			$encriptedData			 = Filter::encrypt($data1);

			if ($isSend)
			{
				$returnSet->setStatus(true);
				$returnSet->setData(['loginOtp' => $result['verifyCode'], 'encCode' => $encriptedData]);
				#$returnSet->setData(['encCode' => Filter::encrypt($data1)]);
				$returnSet->setMessage('OTP sent successfully');
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors(['Sorry, unable to send  OTP'], ReturnSet::ERROR_FAILED);
			}

			//$sendData
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function resendOtpToDriver()
	{
		$returnSet = new ReturnSet();
		try
		{
			$returnSet	 = new ReturnSet();
			$data		 = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj	 = CJSON::decode($data, false);
			$driverModel = Drivers::model()->getDetailsByBkgId($jsonObj->bookingId);
			if (empty($driverModel['bkg_driver_number']))
			{
				throw new Exception("Phone Number Not Found: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$result = ContactPhone::resendContactVerificationOtp($driverModel['bkg_driver_number'], $driverModel['drv_contact_id'], UserInfo::TYPE_DRIVER, $jsonObj->bookingId, 1);
			if (!$result->getStatus())
			{
				$returnSet->setStatus(false);
				$msg = 'Sorry! Please try again.';
				$returnSet->setErrors([$msg], $returnSet::ERROR_VALIDATION);
			}
			else
			{
				$returnSet->setStatus(true);
				$msg = 'OTP resent sucessfully.';
				$returnSet->setMessage($msg);
			}
			Logger::info("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function validateOtpFromDriver()
	{
		$returnSet = new ReturnSet();
		try
		{
			$returnSet	 = new ReturnSet();
			$data		 = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj	 = CJSON::decode($data, false);
			$driverData	 = Drivers::model()->getDetailsByBkgId($jsonObj->bookingId);
			if (empty($driverData['bkg_driver_number']))
			{
				throw new Exception("Phone Number Not Found: ", ReturnSet::ERROR_INVALID_DATA);
			}
			if (trim($jsonObj->otp) == $driverData['phn_otp'])
			{
				$result = ContactPhone::model()->updateVerifyStatus($driverData['drv_contact_id'], $driverData['bkg_driver_number']);
				if ($result->getStatus())
				{
					$driverModel			 = Drivers::model()->getDetailsByBkgId($jsonObj->bookingId);
					$currentBkgId			 = $driverModel['bkg_id'];
					$islinked				 = Drivers::model()->checkSocialLinking($driverModel['drv_id']);
					$driverModel['phn_otp']	 = null;
					$data					 = ['driverInfo' => $driverModel, 'currentBkgId' => $currentBkgId, 'isPhoneVerified' => (int) $driverModel['phn_is_verified'], 'isLinked' => (int) $islinked];
					$returnSet->setStatus(true);
					$returnSet->setData($data);
				}
				else
				{
					throw new Exception("Something went wrong: ", ReturnSet::ERROR_INVALID_DATA);
				}
			}
			else
			{
				$returnSet->setStatus(false);
				$msg = "Sorry! OTP doesn't match.";
				$returnSet->setErrors([$msg], $returnSet::ERROR_VALIDATION);
			}
			Logger::info("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function currentBookings_v2()
	{

		$driverModel = [];
		$obj		 = UserInfo::getInstance();
		$driverId	 = UserInfo::getEntityId();
		$returnSet	 = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody;
			if ($data != "")
			{
				$jsonObj	 = CJSON::decode($data, false);
				$bookingId	 = $jsonObj->id;
			}
			$driverModel = Drivers::getCurrentListBookings($driverId, $bookingId);
		
			//Logger::info('<===DRIVER ID===>'.$driverId);			
			if (!$driverModel)
			{
				throw new Exception("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$is_flexxi			 = false;
			$server_datetime	 = DBUtil::getCurrentTime();
			$server_timestamp	 = ((strtotime($server_datetime)) * 1000);
			foreach ($driverModel as $drv)
			{

				$bkgModel	 = Booking::model()->findByPk($drv['bkg_id']);
				$is_flexxi	 = ($drv['is_flexxi'] == 1) ? true : false;

				$showCustomer = Filter::customerDataShow($bkgModel->bkg_pickup_date);
				$showHelpLine = Drivers::helplineDataShow($bkgModel->bkg_pickup_date,$bkgModel->bkg_return_date);

				$response = new Stub\booking\CreateResponse();
				$response->setCurrentData($bkgModel, $showCustomer,$showHelpLine);

				

				$responsedt->dataList[] = $response;

				$response->lastEvent				 = $drv['btl_event_type_id'];
				$response->isOverDue				 = $drv['isOverDue'];
				$response->isFlexxi					 = $drv['is_flexxi'];
				$response->bpr_is_trip_verified		 = $drv['bpr_is_trip_verified'];
				$response->isBoostEnabled			 = $drv['isBoostEnabled'];
				$response->creditBooking			 = $drv['credit_booking'];
				$response->routeName				 = str_replace("-", "to", $drv['bkg_route_name']);
				$response->cabVerify				 = $drv['cab_verify'];
				$response->assignedCabModel			 = $drv['bkg_cab_assigned'];
				$response->isAirportEntryFeeIncluded = (int) $drv['bkg_is_airport_fee_included'];
				$hashBkgId							 = Yii::app()->shortHash->hash($drv['bkg_id']);
				$hashVndId							 = Yii::app()->shortHash->hash($bkgModel->bkgBcb->bcb_vendor_id);
				$response->bkvnUrl					 = Yii::app()->params['fullBaseURL'] . '/bkvn/' . $hashBkgId . '/' . $hashVndId;

				$address1				 = explode(',', $bkgModel->bkg_pickup_address);
				$response->startKmLimit	 = (int) ( count($address1) > 3) ? 5 : Config::get('ride.startkmlimit');
				//Logger::info('<===BOOKING-ID===>'.$response->bookingId);		
			}
			$response = $responsedt;

			$response->server_timestamp	 = $server_timestamp;
			
			$response->total_count		 = $total_count;
			$response->offLineData		 = $offLineData;

			#$data						 = Filter::removeNull($response);
			$data = $response;

			$returnSet->setStatus(true);
			$returnSet->setData($data);
			$total_count = count($driverModel);

			//$dataModel->dataList = $driverModel;
		}
		catch (Exception $e)
		{
			//catchBlock:
			$returnSet = ReturnSet::setException($e);
		}
		Logger::info('<===Response===>' . json_encode($returnSet));
		return $returnSet;
	}

	public function otpVerifyForLogin()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}

			/* @var $obj \Stub\driver\TempLoginRequest */
			$obj			 = Yii::app()->request->getJSONObject(new \Stub\driver\TempLoginRequest());
			$encryptedData	 = $obj->encCode;
			$decriptData	 = Filter::decrypt($encryptedData);
			$otp			 = $obj->otp;
			$decriptArr		 = json_decode($decriptData);
			#print_r($decriptArr);
			$decriptOtp		 = $decriptArr->otp;
			$driverId		 = $decriptArr->driverId;
			if ($decriptOtp != $otp)
			{

				$msg = "Sorry! OTP doesn't match.";
				throw new Exception($msg, ReturnSet::ERROR_INVALID_DATA);
			}
			$checkDriver = DriverStats::model()->getSyncActivity($driverId);
			if ($checkDriver['lock'] == 1)
			{
				if ($obj->device->uniqueId != $checkDriver['device']['uniqueId'] && $obj->device->deviceName != $checkDriver['device']['deviceName'])
				{
					$uploadMsg	 = "Your session is locked. You previously logged in from  " . $checkDriver['device']['deviceName'] . " .Please complete your sync process and logout from that phone before you can proceed here.";
					$errors		 = [$uploadMsg];
					$returnSet->setStatus(false);
					$returnSet->setErrors($errors, ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
			}
			$row		 = Drivers::getUserContact($driverId);
			$userId		 = $row["userId"];
			$contactId	 = $row["contactId"];
			if ($contactId == '')
			{
				throw new Exception("Unable to validate driver contact", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$sessionId		 = Yii::app()->getSession()->getSessionID();
			$drvModel		 = Drivers::model()->findByPk($row["driverId"]);
			$contactModel	 = Contact::model()->findByPk($contactId);
			$userModel		 = Users::model()->findByPk($userId);

			$appTokenModel	 = AppTokens::model()->addLogin($drvModel->drv_id, $userId, $obj->device, $ipAddress, $sessionId, $fcmToken);
			$msg			 = "Login Successful";

			$res			 = new \Stub\common\Driver();
			$res->setProfileData($drvModel, $contactModel);
			$res->session	 = $sessionId;
			$data1			 = Filter::removeNull($res);
			$returnSet->setStatus(true);
			$returnSet->setMessage($msg);
			$returnSet->setData($data1);
		}
		catch (Exception $e)
		{
			//catchBlock:
			$returnSet = $returnSet->setException($e);
			Logger::exception($e);
		}

		return $returnSet;
	}

	public function bidAdd()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			$obj		 = $jsonMapper->map($jsonObj, new Stub\driver\Bid());
			/** @var $gnbid $req */
			$bcbId		 = $obj->tripId;
			$bidAmount	 = ceil($obj->bidAmount);
			$driverId	 = UserInfo::getEntityId();
			if ($bcbId == '' || $bcbId == 0)
			{
				$error = "Invalid data";
				throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
			}
			if ($bidAmount == '' || $bidAmount == 0)
			{
				$error = "Please re-check your bid amount.";
				throw new Exception(json_encode($error), ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}
			$cabModel	 = BookingCab::model()->findByPk($bcbId);
			$bModels	 = $cabModel->bookings;
			$bkgId		 = $bModels[0]->bkg_id;
			$isGozoNow	 = $bModels[0]->bkgPref->bkg_is_gozonow;
			$params		 = [
				'tripId'	 => $bcbId,
				'bkgId'		 => $bkgId,
				'bidAmount'	 => $bidAmount,
				'isAccept'	 => true,
				'isGozoNow'	 => $isGozoNow
			];

			$success = BookingDriverRequest::storeBidRequest($params, $driverId);
			if ($success)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Request processed successfully");
			}
		}
		catch (Exception $e)
		{
			//catchBlock:
			$returnSet = $returnSet->setException($e);
			Logger::exception($e);
		}

		return $returnSet;
	}

	public function bidDenny()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			$obj		 = $jsonMapper->map($jsonObj, new Stub\driver\Bid());
			/** @var $gnbid $req */
			$bcbId		 = $obj->tripId;
			$driverId	 = UserInfo::getEntityId();
			if ($bcbId == '' || $bcbId == 0)
			{
				$error = "Invalid data";
				throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
			}

			$cabModel	 = BookingCab::model()->findByPk($bcbId);
			$bModels	 = $cabModel->bookings;
			$bkgId		 = $bModels[0]->bkg_id;
			$isGozoNow	 = $bModels[0]->bkgPref->bkg_is_gozonow;
			$params		 = [
				'tripId'	 => $bcbId,
				'bkgId'		 => $bkgId,
				'bidAmount'	 => $bidAmount,
				'isAccept'	 => false,
				'isGozoNow'	 => $isGozoNow
			];

			$success = BookingDriverRequest::storeBidRequest($params, $driverId);
			if ($success)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Request processed successfully");
			}
		}
		catch (Exception $e)
		{
			//catchBlock:
			$returnSet = $returnSet->setException($e);
			Logger::exception($e);
		}

		return $returnSet;
	}

	public function tripDetailsV1()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$check = Drivers::model()->authoriseDriver($token);
			if ($check)
			{
				$data = Yii::app()->request->getParam('data');
				if ($data == "")
				{
					$returnSet->setErrors("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
				}
				$jsonMapper		 = new JsonMapper();
				$jsonObj		 = CJSON::decode($data, false);
				$tripId			 = $jsonObj->trip_id;
				$status			 = $jsonObj->status;
				$dependency_msg	 = "";
				$driverId		 = UserInfo::getEntityId();
				$model			 = Booking::getTripDetailsForDriver($tripId, $status, $driverId, 1);
				if ($model != [])
				{
					$userInfo->userType	 = UserInfo::getUserType(); //Vendor type = 2
					$bkg_id				 = $model[0][bkg_id];
					$noteArrList		 = DestinationNote::model()->showNoteApi($bkg_id, $userInfo->userType);
					$countNote			 = count($noteArrList);
					if ($countNote > 0)
					{
						$model[0]['isDestinationNote'] = 1;
					}
					else
					{
						$model[0]['isDestinationNote'] = 0;
					}
					if ($model[0]['vht_make'] != '' || $model[0]['vht_make'] != NULL)
					{
						$vhtTypeModel = '-' . $model[0]['vht_make'] . ' ' . $model[0]['vht_model'];
					}
					$model[0]['cab_type_tier'] = $model[0]['cab_model'] . ' (' . $model[0]['cab_lavel'] . $vhtTypeModel . ')';
					if ($model[0]['cab_lavel'] == 'Select' || $model[0]['cab_lavel'] == 'Select Plus')
					{
						$model[0]['cab_model']	 = $model[0]['vht_model'];
						$model[0]['cab_lavel']	 = 'Select';
					}
					if ($model[0]['scc_id'] == 2)
					{
						$model[0]['is_cng_allowed'] = '2';
					}
				}
				$data = ['tripId'		 => $model[0]['bcb_id'],
					'routeName'		 => $model[0]['bkg_route_name'],
					'maxBidAmount'	 => $model[0]['max_bid_amount'],
					'minBidAmount'	 => $model[0]['min_bid_amount'],
					'isAssured'		 => $model[0]['is_assured'],
					'finalTripType'	 => $model[0]['bkg_booking_type'],
					'isFlexxi'		 => $model[0]['isFlexxi'],
					'vendorAmount'	 => $model[0]['vendor_ammount'],
					'acceptAmount'	 => $model[0]['vendor_ammount'],
					'isCngAllowed'	 => $model[0]['is_cng_allowed'],
					'bookingList'	 => $model
				];
			}
			else
			{
				$returnSet->setErrors("Unauthorised Vendor", ReturnSet::ERROR_INVALID_DATA);
			}

			$returnSet->setStatus(true);
			$returnSet->setData($data);
		}
		catch (Exception $ex)
		{
			$returnSet = $returnSet->setException($ex);
			Logger::exception($ex);
		}
		return $returnSet;
	}

	public function gnowTripDetails()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$result		 = Drivers::model()->authoriseDriver($token);
		try
		{
			if ($result)
			{
				$processSyncData = Yii::app()->request->rawBody;
				Logger::trace("<===Requset===>" . $processSyncData);
				$data			 = CJSON::decode($processSyncData, true);
				$tripId			 = $data['trip_id'];
				$status			 = $data['status'];

				$driverId = UserInfo::getEntityId();

				$model = Booking::model()->findByAttributes(['bkg_bcb_id' => $tripId]);
				if ($model->bkg_status != 2)
				{
					$error = "Booking is not in assignable state";
					throw new Exception(json_encode($error), ReturnSet::ERROR_VALIDATION);
				}

				if ($model != [])
				{
					$response = new \Stub\common\Booking();
					$response->setDetails($model);

					$noteArrList = \DestinationNote::model()->showNoteApi($model->bkg_id, $showNoteTo	 = 3);
					if ($noteArrList != false || $noteArrList != NULL)
					{
						$res		 = new \Stub\common\DestinationNote();
						$responseDt	 = $res->getData($noteArrList);
						foreach ($responseDt as $res)
						{
							$responseDt->dataList[] = $res;
						}
					}
					$response->destinationNote	 = $responseDt;
					$rtArr						 = json_decode($model->bkg_route_city_names);

					$response->routeName = implode(' - ', $rtArr);
					$returnSet->setData($response);
					$returnSet->setStatus(true);
				}
				else
				{
					$returnSet->setErrors("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
			}
			else
			{
				$returnSet->setErrors("Unauthorised Vendor", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		Logger::trace("<===Response===>" . json_encode($returnSet));
		return $returnSet;
	}

	public function responseReadyToGo()
	{
		$returnSet = new ReturnSet();
		try
		{
			$drvId		 = UserInfo::getEntityId();
			$userInfo	 = UserInfo::getInstance();
			if (!$drvId)
			{
				throw new Exception("Unauthorized Driver", ReturnSet::ERROR_INVALID_DATA);
			}
			$rawData	 = Yii::app()->request->rawBody;
			$jsonObj	 = CJSON::decode($rawData, false);
			// $bkgId	 = $jsonObj->bkgId;
			//$bmodel = Booking::model()->findByPk($bkgId);
			$tripId		 = $jsonObj->tripId;
			$bcbmodel	 = BookingCab::model()->findByPk($tripId);
			$bmodels	 = $bcbmodel->bookings;
			$bmodel		 = $bmodels[0];
			if (!in_array($bmodel->bkg_status, [5]))
			{
				throw new Exception("Booking status changed", ReturnSet::ERROR_VALIDATION);
			}

			if ($bcbmodel->bcb_driver_id != $drvId)
			{
				throw new Exception("Not authorised to proceed", ReturnSet::ERROR_VALIDATION);
			}

			$resp = 1;
			foreach ($bmodels as $bmodel)
			{
				$bkgId	 = $bmodel->bkg_id;
				$numrows = BookingTrack::updateDriverReadyToPickupConfirmation($bkgId, $resp);
				$eventId = BookingLog::ONTHEWAY_FOR_PICKUP;
				$desc	 = "Driver confirmed that he is going for pickup";

				BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId);
			}

			$returnSet->setStatus(true);
			$returnSet->setMessage('Thank You, Customer is waiting at pickup point');
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function responseDenyToGo()
	{
		$returnSet = new ReturnSet();
		try
		{
			$drvId		 = UserInfo::getEntityId();
			$userInfo	 = UserInfo::getInstance();
			if (!$drvId)
			{
				throw new Exception("Unauthorized Driver", ReturnSet::ERROR_UNAUTHORISED);
			}
			$rawData	 = Yii::app()->request->rawBody;
			$jsonObj	 = CJSON::decode($rawData, false);
			// $bkgId	 = $jsonObj->bkgId;
			//$bmodel = Booking::model()->findByPk($bkgId);
			$tripId		 = $jsonObj->tripId;
			$bcbmodel	 = BookingCab::model()->findByPk($tripId);
			$bmodels	 = $bcbmodel->bookings;
			$bmodel		 = $bmodels[0];

			if (!in_array($bmodel->bkg_status, [5]))
			{
				throw new Exception("Booking status changed", ReturnSet::ERROR_VALIDATION);
			}

			if ($bcbmodel->bcb_driver_id != $drvId)
			{
				throw new Exception("Not authorised to proceed", ReturnSet::ERROR_VALIDATION);
			}

			$resp = 2;
			foreach ($bmodels as $bmodel)
			{
				$bkgId	 = $bmodel->bkg_id;
				$numrows = BookingTrack::updateDriverReadyToPickupConfirmation($bkgId, $resp);

				$scqReturnSet = ServiceCallQueue::autoFURDriverDenyToGo($bkgId);

				if ($scqReturnSet->getStatus())
				{
					$result['isNewFollowup'] = true;
					$returnSet->setMessage('A call back is generated.');
				}

				$returnSet->setData($result);
				$eventId = BookingLog::DRIVER_NOT_GOING;
				$desc	 = "Driver is not going for pickup";
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId);
			}
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function getDriverAllocatedDetails()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);

		$formData		 = Yii::app()->request->getParam('data');
		$rawData		 = Yii::app()->request->rawBody;
		$processSyncData = $formData . $rawData;
		Logger::trace("<===Requset===>" . $processSyncData);
		$data			 = CJSON::decode($processSyncData, true);
		$bkgId			 = $data['bkgId'];

		$drvId = UserInfo::getEntityId();

		$model = Booking::model()->findByPk($bkgId);
		if (!in_array($model->bkg_status, [5]))
		{
			throw new Exception("Cannot show details", ReturnSet::ERROR_UNAUTHORISED);
		}

		if ($model->bkgBcb->bcb_driver_id != $drvId)
		{
			throw new Exception("Not authorised to proceed", ReturnSet::ERROR_UNAUTHORISED);
		}
		if ($model != [])
		{
			$response	 = new \Stub\common\Booking();
			$response->setAllocatedGnowData($model);
			$rtArr		 = json_decode($model->bkg_route_city_names);

			$response->routeName = implode(' - ', $rtArr);
			$returnSet->setData($response);
			$returnSet->setStatus(true);
		}
		else
		{
			$returnSet->setErrors("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}

		return $returnSet;
	}

}
