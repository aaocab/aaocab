<?php

class Hornok extends CComponent
{

	public static $_instance	 = [];
	public static $_apiData		 = [];
	public $_operator			 = null;
	public static $tripTypes	 = ['Local' => '12'];
	public static $vehicleTypes	 = ['Hatchback' => '1', 'Sedan' => '3', 'SUV' => '2', 'SUV_LUXURY' => '16', 'ANY' => '15'];
	public $objOperator			 = null;
	public static $data			 = [];

	/**
	 * 
	 * @param type $route
	 * @param type $cabType
	 * @param type $tripType
	 * @param type $operatorId
	 * @return boolean|\Stub\common\QuoteResponse
	 */
	public static function getQuote_NR($route, $cabType, $tripType, $operatorId)
	{
		$type				 = '/search';
		/* @var $obj Stub\operator\QuickRide\QuoteRequest */
		$obj				 = Stub\operator\QuickRide\QuoteRequest::getInstance($route);
		$operatorRequest	 = Filter::removeNull($obj);
		$requestKey			 = md5(json_encode($operatorRequest)); //5c5ba4baf497ded2c551b3ddb33eb85b
		$previousRequestkey	 = $requestKey;

		$time				 = Filter::getExecutionTime();
		$responseParamList	 = self::callAPI($operatorRequest, $type, $operatorId);
		$operatorResponse	 = self::parseResponse((object) $responseParamList);
		$time				 = Filter::getExecutionTime() - $time;

		$responseKey = md5(json_encode($operatorResponse)); //9b41370c25388456c8d6df6bcbd128ec
		$typeAction	 = OperatorApiTracking::GET_QUOTE;

		if (!isset(self::$_apiData[$requestKey]) || self::$_apiData[$requestKey] != $responseKey)
		{
			$oatModel	 = OperatorApiTracking::model()->add($typeAction, $operatorResponse, $operatorId, $model		 = null, $route, $tripType, null, null, $orderRefId);
		}
		self::$_apiData[$requestKey] = $responseKey;

		$checkExpiryTime = self::checkExpiryTime($time, $type			 = 1);
		if ($checkExpiryTime == false)
		{
			$quoteResponse = false;
			return $quoteResponse;
		}

		foreach ($operatorResponse->fare as $data)
		{
			$qrCabType = self::$vehicleTypes[$data['vehicleClass']];
			if ($qrCabType == $cabType)
			{
				$quoteResponse	 = new Stub\common\QuoteResponse();
				$quoteResponse->setData($data, $route, NULL, $tripType);
				$orderRefId		 = $quoteResponse->fare->routeRates->fixedFareId;
				$oatModel->updateData($operatorResponse, $status			 = 1, $bkgId			 = null, $operatorId, $orderRefId, null, null);
			}
		}
		return $quoteResponse;
	}

	/**
	 *
	 * @param  $model
	 * @return boolean|string
	 */
	public static function holdBooking($bkgId, $operatorId)
	{
		$returnSet	 = new ReturnSet();
		$success	 = false;
		$type		 = 'createGZBooking';

		/* @var $obj Beans\operator\hornok\Hold */
		$obj			 = Beans\operator\hornok\Hold::getInstance($bkgId);
		$operatorRequest = Filter::removeNull($obj);	

		#$userInfo	 = UserInfo::getInstance();
		$typeAction	 = OperatorApiTracking::CREATE_BOOKING;

		$oatModel	 = OperatorApiTracking::add($typeAction, $operatorRequest, $operatorId, $bkgId, null);
		$returnSet->setStatus(true);
		$returnSet->setData($operatorRequest);

		$responseParamList	 = self::callAPI($returnSet, $type, $operatorId);
		$operatorResponse	 = self::parseResponse((object) $responseParamList);	
		
		/* @var $holdResponse Beans\operator\hornok\HoldResponse */
		$holdResponse		 = Beans\operator\hornok\HoldResponse::setData($operatorResponse, $bkgId);
		if ($holdResponse->success == true)
		{
			$status		 = 1;
			$errorMsg	 = null;
			$tripId		 = $holdResponse->tripId;
			if($tripId != '')
			{
				$bkgModel = Booking::model()->findByPk($bkgId);
				$bookingCab	= $bkgModel->bkgBcb;
				$bookingCab->bcb_vendor_ref_code = $tripId;
				$bookingCab->save();
			}
		}
		else
		{
			$status		 = 2;
			$errorMsg	 = $operatorResponse->error;
		}
		$oatModel->updateData($operatorResponse, $status, $bkgId, $operatorId, $errorMsg, null);		
		$returnSet->setData([$status,$errorMsg]);
		return $returnSet;
	}

	/**
	 * 
	 * @param type $bkgId
	 * @param type $operatorId
	 * @return boolean
	 */
	public function cancelBooking($bkgId, $operatorId)
	{

		$returnSet	 = new ReturnSet();
		$success	 = false;
		$type		 = 'updateGZBooking';

		/* @var $obj Beans\operator\hornok\Cancel */
		$obj			 = Beans\operator\hornok\Cancel::getInstance($bkgId);
		$operatorRequest = Filter::removeNull($obj);	

		$userInfo	 = UserInfo::getInstance();
		$typeAction	 = OperatorApiTracking::CANCEL_BOOKING;
		$oatModel	 = OperatorApiTracking::add($typeAction, $operatorRequest, $operatorId, $bkgId, null);
		
		$response	 = Filter::removeNull($operatorRequest);
		$returnSet->setStatus(true);
		$returnSet->setData($response);

		$responseParamList	 = self::callAPI($returnSet, $type, $operatorId);
		$operatorResponse	 = self::parseResponse((object) $responseParamList);	

		/* @var $response Beans\operator\hornok\CancelResponse */
		$response		 = Beans\operator\hornok\CancelResponse::setData($operatorResponse, $bkgId);
		if ($operatorResponse->success == true)
		{
			$status		 = 1;
			$errorMsg	 = null;
		}
		else
		{
			$status		 = 2;
			$errorMsg	 = $operatorResponse->userMsg;
		}
		Logger::create("Response ===> ".CJSON::encode($operatorResponse), CLogger::LEVEL_WARNING);
		$operatorResponse = Filter::removeNull($operatorResponse);
		$oatModel->updateData($operatorResponse, $status, $bkgId, $operatorId, $errorMsg, null);		
		$returnSet->setData([$status,$errorMsg]);
		return $returnSet;
	}

	/**
	 *
	 * @param  $model
	 * @return boolean|string
	 */
	public static function updateBooking($bkgId, $operatorId)
	{
		$returnSet	 = new ReturnSet();
		$success	 = false;
		$type		 = 'updateGZBooking';

		/* @var $obj Beans\operator\hornok\Update */
		$obj			 = Beans\operator\hornok\Update::getInstance($bkgId);
		$operatorRequest = Filter::removeNull($obj);
		
		$userInfo	 = UserInfo::getInstance();
		$typeAction	 = OperatorApiTracking::UPDATE_BOOKING;
		$oatModel	 = OperatorApiTracking::add($typeAction, $operatorRequest, $operatorId, $bkgId, null);
		$returnSet->setStatus(true);
		$returnSet->setData($operatorRequest);

		$responseParamList	 = self::callAPI($returnSet, $type, $operatorId);
		$operatorResponse	 = self::parseResponse((object) $responseParamList);

		if ($operatorResponse->success == true)
		{
			$status		 = 1;
			$errorMsg	 = null;
		}
		else
		{
			$status		 = 2;
			$errorMsg	 = $operatorResponse->userMsg;
		}
		Logger::create("Response ===> ".CJSON::encode($operatorResponse), CLogger::LEVEL_WARNING);
		$operatorResponse = Filter::removeNull($operatorResponse);
		$oatModel->updateData($operatorResponse, $status, $bkgId, $operatorId, $errorMsg, null);		
		$returnSet->setData([$status,$errorMsg]);
		return $returnSet;
	}

	/**
	 * 
	 * @param type $bModel
	 * @param type $operatorId
	 * @param type $jsonData
	 * @return \ReturnSet
	 */
	
	public function assignChauffeur($bModel, $operatorId, $jsonData)
	{
		$returnSet = new ReturnSet();
		try
		{
			$typeAction		=   OperatorApiTracking::CAB_DRIVER_ALLOCATION;
			$oatModel		=   OperatorApiTracking::add($typeAction, $jsonData, $operatorId, $bModel->bkg_id, null);

			/* @var $drvData Drivers */
			$drvData = Drivers::model()->addOperator($jsonData, $operatorId);
		
			/* @var $vhcData Vehicles */
			$vhcData = Vehicles::model()->addHornOk($jsonData, $operatorId); 

			$cttId			=	ContactProfile::getByDrvId($drvData['driverId']);
			$drvphone		=	ContactPhone::getContactPhoneById($cttId);
			
			//booking cab data update
			$bModel->bkg_status;
			$bCabModel					 = $bModel->bkgBcb;
			$bCabModel->bcb_vendor_id;
			$bCabModel->bcb_driver_phone = $drvphone;
			$bCabModel->bcb_cab_id		 = $vhcData['vehicleId'];
			$bCabModel->bcb_driver_id	 = $drvData['driverId'];
			$cab_type					 = $bModel->bkgSvcClassVhcCat->scv_vct_id;
			
			/* @var $bCabModel BookingCab */
			$success					 = $bCabModel->assignCabDriver($vhcData['vehicleId'], $drvData['driverId'], $cab_type, UserInfo::getInstance());
			if($success)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Successfully assign chauffeur");
				$status = 1;
				$errorMsg = null;
			}
			else
			{
				$errors = implode(" ",Filter::getNestedValues($bCabModel->getErrors()));			
				$returnSet->setStatus(false);
				$returnSet->setMessage($errors);
				$errorMsg = $errors;
				$status = 2;
			}
			$oatModel->updateData($returnSet, $status, $bModel->bkg_id, $operatorId, $errorMsg, null);
		}
		catch (Exception $e)
		{
			Logger::warning("Failed to get refid: " . $e->getMessage());
			$ret = ReturnSet::setException($e);
			$response->setError($ret);
		}
		return $returnSet;
	}

	/* used to confirm operator booking */
	/**
	 * 
	 * @param type $bkgId
	 * @param type $operatorId
	 * @param type $response
	 * @return Boolean
	 * @throws Exception
	 */
	public static function confirmTrip($bkgId, $operatorId, $response)
	{
		$returnSet = new ReturnSet();
		try
		{
			$model										 = \Booking::model()->findByPk($bkgId);
			$model->bkgBcb->bcb_block_autounassignment	 = 1;
			$model->bkgBcb->save();
			$userInfo									 = \UserInfo::getInstance();
			$vendorId									 = UserInfo::getEntityId();
			$typeAction									 = OperatorApiTracking::CONFIRM_BOOKING;
			$oatModel									 = OperatorApiTracking::add($typeAction, $response, $operatorId, $bkgId, null);
			$bidAcceptId								 = $model->bkgBcb->bcb_id;
			$acceptVendorAmount							 = $response->acceptedVendorAmount;
			$errors										 = [];

			if (in_array($model->bkg_status, [9, 10]))
			{
				throw new Exception("Sorry booking already cancelled", ReturnSet::ERROR_INVALID_DATA);
			}

			if (empty($vendorId))
			{
				throw new Exception("Invalid Vendor Data", ReturnSet::ERROR_INVALID_DATA);
			}

			if (($acceptVendorAmount <= $model->bkgBcb->bcb_vendor_amount) && ($model->bkg_status == 2))
			{
				$status = BookingVendorRequest::DirectAccept($acceptVendorAmount, $vendorId, $bidAcceptId, $userInfo);
				if ($status == true)
				{
					$message = "Booking confirm successfully";
					/* @var $confirmResponse Beans\operator\hornok\ConfirmResponse */
					$confirmResponse = new Beans\operator\hornok\ConfirmResponse();
					$confirmResponse->getData($status, $message);
					$data			 = Filter::removeNull($confirmResponse);
					$returnSet->setStatus(true);
					$returnSet->setData($data);
					$errorMsg		 = null;
					$statusData		 = 1;
					$model->bkgBcb->refresh();
					if((Config::get('hornok.operator.id') === $model->bkgBcb->bcb_vendor_id) && $model->bkg_agent_id !=18190)
					{
						$scheduleTime = Config::get('hornok.sendcustinfo.min');
						BookingScheduleEvent::addPushTravellerDetailsEvent($model, $model->bkg_pickup_date, $scheduleTime);
					}
					
				}
				else
				{
					$message	 = "Sorry we can not process this request";
					$confirmResponse = new Beans\operator\hornok\ConfirmResponse();
					$confirmResponse->getData($status, $message);
					$errorMsg = $message;
					$statusData	 = 2;
				}
			}
//			elseif (($acceptVendorAmount <= $model->bkgBcb->bcb_vendor_amount) && ($model->bkg_status == 3))
//			{
//				$message = "Booking confirm successfully";
//				/* @var $confirmResponse Beans\operator\hornok\ConfirmResponse */
//				$confirmResponse = new Beans\operator\hornok\ConfirmResponse();
//				$confirmResponse->getData(true, $message);
//				$data			 = Filter::removeNull($confirmResponse);
//				$returnSet->setStatus(true);
//				$returnSet->setData($data);
//				$errorMsg		 = null;
//				$statusData		 = 1;
//				if((Config::get('hornok.operator.id') == $model->bkgBcb->bcb_vendor_id) && $model->bkg_agent_id !=18190)
//				{
//					$scheduleTime = Config::get('hornok.sendcustinfo.min');
//					BookingScheduleEvent::addPushTravellerDetailsEvent($model->bkg_id, $model->bkg_pickup_date, $scheduleTime);
//				}
//			}
//			else
//			{
//				/** @var BookingVendorRequest $result */
//				$result = BookingVendorRequest::model()->createRequest($acceptVendorAmount, $bidAcceptId, $vendorId);
//				if (!$result)
//				{
//					Logger::trace("Errors: " . json_encode($errors));
//					throw new Exception("Something went wrong", ReturnSet::ERROR_FAILED);
//				}
//
//				$vendorStat				 = VendorStats::model()->getbyVendorId($vendorId);
//				$vendorStat->vrs_tot_bid = $vendorStat->vrs_tot_bid + 1;
//				$vendorStat->save();
//
//				$eventId = BookingLog::BID_SET;
//				$desc	 = "Bid of ₹" . $acceptVendorAmount . " provided.";
//				$message = "Your bid is accepted, once booking assigned will be informed";
//
//				$res = BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventId);
//				if (!$res)
//				{
//					$message = "Your bid related data not added in booking log. " . $response;
//				}
//				$success		 = false;
//				/* @var $confirmResponse Beans\operator\hornok\ConfirmResponse */
//				$confirmResponse = new Beans\operator\hornok\ConfirmResponse();
//				$confirmResponse->getData($success, $message);
//				$data			 = Filter::removeNull($confirmResponse);
//				$returnSet->setData($data);
//				$errorMsg		 = $message;
//				$statusData		 = 2;
//			}

			$oatModel->updateData($returnSet, $statusData, $bkgId, $operatorId, $errorMsg, null);
		}
		catch (Exception $e)
		{
			Logger::warning("Failed to get refid: " . $e->getMessage());
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($e);
			$oatModel->updateData($returnSet, $statusData, $bkgId, $operatorId, $errorMsg, null);
		}
		return $returnSet;
	}

	public static function callAPI($obj, $type, $operatorId)
	{
		$jsonData	 = json_encode($obj);
		$data		 = md5($jsonData);
		$key		 = "HORNOK_OPERATOR::{$data}::{$type}";
		if (isset(Hornok::$data[$key]))
		{
			return Hornok::$data[$key];
		}

		$apiServerUrl		 = self::getServerUrl($type, $operatorId);
		$ch					 = curl_init($apiServerUrl['apiServerUrl']);
		$authorization		 = $apiServerUrl['authorization'];
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: ' . $authorization,
			'Content-Length: ' . strlen($jsonData))
		);
		$jsonResponse		 = curl_exec($ch);
		$responseParamList	 = json_decode($jsonResponse, true);

		Logger::trace('APISERVER URL: ' . $apiServerUrl['apiServerUrl']);
		Logger::trace('JSON DATA: ' . $jsonData);
		Logger::trace('JSON RESPONSE: ' . $jsonResponse);

		Hornok::$data[$key] = $responseParamList;
		return $responseParamList;
	}


	public static function getServerUrl($type, $operatorId)
	{
		$arrConfig = [];
		switch ($operatorId)
		{
			case Config::get('hornok.operator.id'):
				$apiServerConfig = json_decode(\Config::get('hornok.server.api.config'), true);
				$arrConfig['apiServerUrl']	 = $apiServerConfig['serverurl'] . $type;
				$arrConfig['authorization']	 = $apiServerConfig['authorization'];
				break;
			default:
				break;
		}
		return $arrConfig;
	}

	public static function parseResponse($responseParamList)
	{
		$response			 = new PartnerResponse();
		$response->status	 = 2;
		if ($responseParamList->serviceableArea == true)
		{
			$response->status = 1;
		}
		return $responseParamList;
	}

	/**
	 * 
	 * @param type $time
	 * @param type $type
	 * @return boolean
	 */
	public static function checkExpiryTime($time, $type)
	{
		switch ($type)
		{
			case 1:
				$duration	 = 100;
				break;
			case 2:
				$duration	 = 100;
				break;
			case 3:
				$duration	 = 100;
				break;
		}
		$success = false;
		$seconds = $time % 60;
		if ($seconds < $duration)
		{
			$success = true;
		}
		return $success;
	}

	/**
	 * 	
	 * Used to cancel operator booking
	 * @param type $bkgId
	 * @param type $operatorId
	 * @param type $data
	 * @return array
	 */
	public static function unassignVendor($bkgId, $operatorId, $response)
	{
		$returnSet = new ReturnSet();
		try
		{
			$model = \Booking::model()->findByPk($bkgId);
			
			$userInfo			 = \UserInfo::getInstance();
			$typeAction			 = OperatorApiTracking::UNASSIGN_VENDOR;
			$oatModel			 = OperatorApiTracking::add($typeAction, $response, $operatorId, $bkgId);
			$reason				 = "Driver not available";

			$success = Booking::model()->canVendor($model->bkg_bcb_id, $reason, $userInfo);
			if ($success == true)
			{
				$desc		 = "Booking cancelled by operator";
				$returnSet->setStatus(true);
				$returnSet->setMessage($desc);
				$errorMsg = null;
				$status = 1;
			}
			else
			{
				$errorMsg = "request not accepte";
				$returnSet->setStatus(false);
				$status = 2;
			}
			$oatModel->updateData($returnSet, $status, $bkgId, $operatorId, $errorMsg, null);
		}
		catch (Exception $e)
		{
			Logger::warning("Failed to get refid: " . $e->getMessage());
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($e);
		}
		return $returnSet;
	}


	/**
	 * 	
	 * Used to complete for update last location
	 * @param type $bkgId
	 * @param type $operatorId
	 * @param type $response
	 * @return array
	 */
	public static function updateLatLocation($bkgId, $operatorId, $response)
	{
		$returnSet	 = new ReturnSet();		
		try
		{
			$model = \Booking::model()->findByPk($bkgId);
			if (empty($model))
			{
				throw new Exception("Invalid Booking", ReturnSet::ERROR_INVALID_DATA);
			}

			$typeAction				= OperatorApiTracking::UPDATE_LAST_LOCATION;
			$oatModel				= OperatorApiTracking::add($typeAction, $response, $operatorId, $bkgId, null);

			$cordinates				= $response->latitude . ',' . $response->longitude;
			$event					= BookingTrack::UPDATE_LAST_LOCATION;
			$trackDetailStatus		= BookingTrack::updateTrackingDetails($model, $cordinates, $response,$event);
			if ($trackDetailStatus == true)
			{
				$message = "Update last location successfully";
				$returnSet->setMessage($message);
				$returnSet->setStatus(true);
				$status = 1;
				$errorMsg = null;
			}
			else
			{
				$returnSet->setMessage("Sorry unable to process your request");
				$status = 2;
				$errorMsg = "Sorry unable to process your request"; 
			}		
			$oatModel->updateData($returnSet, $status, $bkgId, $operatorId, $errorMsg, null);
		}
		catch (Exception $e)
		{			
			Logger::warning("Failed to get refid: " . $e->getMessage());
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($e);
		}
		return $returnSet;
	}


	/**
	 * 
	 * @param type $bModel
	 * @param type $operatorId
	 * @param type $jsonObj
	 * @return type
	 */
	public function syncRideData($bkgId, $operatorId, $jsonObj)
	{
		$returnSet	 = new ReturnSet();
		$responseSet = [];
		try
		{
			$transaction = DBUtil::beginTransaction();

			$typeAction	 = OperatorApiTracking::getActionType($jsonObj->eventType);
			$oatModel	 = OperatorApiTracking::add($typeAction, $jsonObj, $operatorId, $bkgId, null);

			$result	 = \Beans\booking\TrackEvent::setTrackModel($jsonObj, $isDCO	 = false);

			$model		 = $result[0];
			$trackObj	 = $result[1];

			$checkLog		 = DrvUnsyncLog::model()->checkExist($model->btl_bkg_id, $model->btl_event_type_id);
			$eventResponse	 = $model->handleEvents($trackObj, NULL);

			$res = Beans\booking\TrackEvent::setResponse($eventResponse, $model);
			if ($eventResponse->getStatus())
			{
				$model->addLocation();
			}
			$responseSet[] = $res;
			DBUtil::commitTransaction($transaction);

			$data		 = Filter::removeNull($responseSet);
			$returnSet->setStatus(true);
			$returnSet->setData($data);
			$errorMsg	 = $returnSet->getErrors();
			$status		 = $returnSet->getStatus();

			$oatModel->updateData($returnSet, $status, $bkgId, $operatorId, $errorMsg, null);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	/**
	 * 
	 * @param type $bModel
	 * @param type $operatorId
	 * @param type $jsonData
	 * @return \ReturnSet
	 */
	
	public function unAssign($bkgId, $operatorId, $jsonData)
	{
		$returnSet = new ReturnSet();
		try
		{
			$model = \Booking::model()->findByPk($bkgId);
			
			$userInfo			 = \UserInfo::getInstance();
			$typeAction			 = OperatorApiTracking::UNASSIGN_VENDOR;
			$oatModel			 = OperatorApiTracking::add($typeAction, $jsonData, $operatorId, $bkgId);
			$reason				 = "Driver not available";

			$success = Booking::model()->canVendor($model->bkg_bcb_id, $reason, $userInfo);
			if ($success == true)
			{
				$desc		 = "Successfully accept your request";
				$returnSet->setStatus(true);
				$returnSet->setMessage($desc);
				$errorMsg = null;
				$status = 1;
			}
			else
			{
				$errorMsg = "Request not accepted";
				$returnSet->setStatus(false);
				$status = 2;
			}
			$oatModel->updateData($returnSet, $status, $bkgId, $operatorId, $errorMsg, null);
		}
		catch (Exception $e)
		{
			Logger::warning("Failed to get refid: " . $e->getMessage());
			$ret = ReturnSet::setException($e);
			$response->setError($ret);
		}
		return $returnSet;
	}

}
