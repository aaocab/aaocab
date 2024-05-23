<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class BookingController extends BaseController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */

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
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
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
			$ri	 = array();

			foreach($ri as $value)
			{
				if(strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.post.pendingRequest.render', function () {
			return $this->renderJSON($this->pendingRequest());
		});
		$this->onRest('req.post.bidAction.render', function () {
			return $this->renderJSON($this->bidAction());
		});
		$this->onRest('req.post.bidAcceptAction.render', function () {
			return $this->renderJSON($this->bidAcceptAction());
		});

		$this->onRest('req.post.tripDetails.render', function () {
			return $this->renderJSON($this->tripDetails());
		});

		$this->onRest('req.post.bookingDetails.render', function () {
			return $this->renderJSON($this->bookingDetails());
		});

		$this->onRest('req.get.bidStatusList.render', function () {
			return $this->renderJSON($this->bidStatusList());
		});

		$this->onRest('req.post.unassign.render', function () {
			return $this->renderJSON($this->unassign());
		});

		$this->onRest('req.post.assignDriverCab.render', function () {
			return $this->renderJSON($this->assignDriverCab());
		});

		// GozoNow

		$this->onRest('req.get.gnowDenyReasons.render', function () {
			return $this->renderJSON($this->gnowDenyReasons());
		});
		$this->onRest('req.get.unassignReasonList.render', function () {
			return $this->renderJSON($this->unassignReasonList());
		});

		$this->onRest('req.post.gnowReadyToGo.render', function () {
			return $this->renderJSON($this->gnowReadyToGo());
		});
		$this->onRest('req.post.gnowSomeProblemToGo.render', function () {
			return $this->renderJSON($this->gnowSomeProblemToGo());
		});
		$this->onRest('req.post.gnowTripDetails.render', function () {
			return $this->renderJSON($this->gnowTripDetails());
		});
		$this->onRest('req.post.getServedList.render', function () {
			return $this->renderJSON($this->getServedList());
		});
		$this->onRest('req.post.getServedTripList.render', function () {
			return $this->renderJSON($this->getServedTripList());
		});
		$this->onRest('req.post.resendBkgStartOtp.render', function () {
			return $this->renderJSON($this->resendBkgStartOtp());
		});

		$this->onRest('req.post.addComment.render', function () {
			return $this->renderJSON($this->addComment());
		});
		$this->onRest('req.post.getCommentList.render', function () {
			return $this->renderJSON($this->getCommentList());
		});
		$this->onRest('req.post.getDestinationNoteList.render', function () {

			return $this->renderJSON($this->getDestinationNoteList());
		});
		$this->onRest('req.post.bidRank.render', function () {

			return $this->renderJSON($this->showBidRank());
		});
		$this->onRest('req.post.assignBooking.render', function () {

			return $this->renderJSON($this->assignBooking());
		});
		$this->onRest('req.get.showDestinationArea.render', function () {

			return $this->renderJSON($this->showDestinationArea());
		});
		
		$this->onRest('req.post.addDestinationNoteList.render', function () {

			return $this->renderJSON($this->addDestinationNoteList());
		});
	}

	/**
	 * @deprecated since version 31-03-2023
	 * @author madhumita
	 * New one trip controller pendingJobs
	 */
	public function pendingRequest()
	{
		$returnSet = new ReturnSet();
		try
		{
			$requestData = Yii::app()->request->rawBody;
			if(!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj		 = CJSON::decode($requestData, false);
			$jsonMapper	 = new JsonMapper();
			$obj		 = $jsonMapper->map($reqObj, new Beans\booking\VendorPendingRequest());
			$filter		 = $obj->setData();
			$vendorId	 = $this->getVendorId();
			if(!$vendorId)
			{
				throw new Exception("Vendor not authorized", ReturnSet::ERROR_UNAUTHORISED);
			}
			$offSetCount = $filter->pageSize;
			$pageCount	 = $filter->pageCount;
//			$result		 = BookingVendorRequest::getPendingBookingRequest($vendorId, $pageCount, $filter, $offSetCount);
			$result		 = BookingVendorRequest::getPendingRequestV2($vendorId, $pageCount, $filter, $offSetCount);

			$dependencyMsg = \VendorStats::getDependencyMessage($vendorId);
			if($result->getRowCount() > 0)
			{

				$response	 = new \Beans\vendor\TripDetailResponse();
				$responsedt	 = $response->setResponseData($result, $dependencyMsg);
				$data		 = Filter::removeNull($responsedt);

				$returnSet->setData($data);
				$returnSet->setStatus(true);
			}
			else
			{
				throw new Exception("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		Logger::profile("response : " . $vendorId . ":" . json_encode($returnSet));
		return $returnSet;
	}

	/**
	 * @deprecated new service bidAcceptAction
	 * @return type
	 * @throws Exception
	 */
	public function bidAction()
	{

//$requestData='{
//    "tripId": "2434019",
//    "amount": 4000,
//    "action":  1,
//    "cab": {
//        "id": 3033
//    },
//    "driver": {
//        "id": 5570,
//		"phone": [
//			{
//			  "createDate": "2022-11-30 19:37:12",
//			  "fullNumber": "918981062934",
//			  "isPrimary": 1,
//			  "isVerified": 1,
//			  "isdCode": "91",
//			  "number": "8981062934",
//			  "verifiedDate": "2022-11-30 19:42:31"
//			}
//    },
//    "reachingAfterMinutes": 10 ,
//    "reason":{
//        "id":1
//    }
//}';
		$requestData = Yii::app()->request->rawBody;

		$returnSet = new ReturnSet();
		try
		{
			if(!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}

			$reqObj		 = CJSON::decode($requestData, false);
			$jsonMapper	 = new JsonMapper();
			$obj		 = new Beans\booking\BidAction($reqObj);

			//	$obj = $jsonMapper->map($reqObj, $bAction);

			$tripId = (int) trim($obj->tripId);

			/** @var BookingCab $cabModel */
			if($tripId == '' || $tripId == 0)
			{
				$error = "Invalide data";
				throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
			}
			$cabModel = BookingCab::model()->findByPk($tripId);
			if(!$cabModel)
			{
				$error = "No trip found with the data";
				throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
			}
			$bidAmount		 = $obj->amount;
			$bidAction		 = $obj->action; //0=>deny,1=>bid,2=>direct accept
			$isDirectAccept	 = ($bidAction == 2) ? true : false;
			$arrAllowedBids	 = $cabModel->getMinMaxAllowedBidAmount();
			$vendorId		 = $this->getVendorId();
			if($bidAction == 2 && !$bidAmount)
			{
				$bidAmount = BookingVendorRequest::getDirectAcceptAmount($vendorId, $tripId);
			}

			if($bidAction != 0 && (($bidAmount < $arrAllowedBids['minBid'] || ($bidAmount > $arrAllowedBids['maxBid'] && $arrAllowedBids['maxBid'] > 0)) || ( $bidAmount < $arrAllowedBids['minBid'] )))
			{
				$error = "Bid amount out of range (too low or too high)";
				throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
			}
			$userInfo = UserInfo::getInstance();

			$bModels	 = $cabModel->bookings;
			$isGozoNow	 = $bModels[0]->bkgPref->bkg_is_gozonow;
			if($isGozoNow == 1 && $obj->action > 0)
			{
				$obj->setGNOwAcceptData($reqObj);
			}

			switch($bidAction)
			{
				case 0: //Deny

					if($isGozoNow == 1)
					{
						$returnSet = BookingCab::processGNowDenyBidding($cabModel, $obj, $vendorId);
					}
					else
					{
						$returnSet = BookingVendorRequest::denyTripByVendor($tripId, $vendorId, $userInfo);
					}
					break;
				case 2: //Direct Accept
				case 1: //Bid
					$returnSet = BookingCab::validateVendorTripForBidding($tripId, $vendorId);

					if($returnSet->getStatus())
					{
						if($isGozoNow == 1)
						{
							$returnSet = BookingCab::processGNowAcceptBidding($cabModel, $obj, $vendorId);
						}
						else
						{

							$returnSet = BookingVendorRequest::acceptTripByVendor($tripId, $vendorId, $bidAmount, $userInfo, $isDirectAccept);
						}
					}

					break;

				default:
					break;
			}

			if($returnSet->getStatus(true))
			{
				$returnSet->setMessage("Request processed successfully");
			}
		}
		catch(Exception $ex)
		{

			$returnSet = ReturnSet::setException($ex);
			$returnSet->setMessage($ex->getMessage());
		}
		if(!$returnSet->getStatus() && $returnSet->hasErrors())
		{
			$errors		 = $returnSet->getErrors();
			$errorDesc	 = implode('; ', $errors);
			$cabModel->logFailedVendorAssignment($errorDesc, $userInfo, $vendorId);
		}
		return $returnSet;
	}

	/**
	 * new function bidAcceptAction
	 * previous one bidAction
	 * @return type
	 * @throws Exception
	 */
	public function bidAcceptAction()
	{
		$requestData = Yii::app()->request->rawBody;

		$returnSet = new ReturnSet();
		try
		{
			if(!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}

			$reqObj		 = CJSON::decode($requestData, false);
			$jsonMapper	 = new JsonMapper();
			$obj		 = new Beans\booking\BidAction($reqObj);

			//	$obj = $jsonMapper->map($reqObj, $bAction);

			$tripId = (int) trim($obj->tripId);

			$cabModel = BookingCab::model()->findByPk($tripId);
			if(!$cabModel)
			{
				$error = "No trip found with the data";
				throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
			}

			$vendorId = $this->getVendorId();
// 

			$vndModel = Vendors::model()->findByPk($vendorId);
			if($vndModel->vnd_active != 1)
			{
				$statusList	 = $vndModel->getStatusList();
				$status		 = $statusList($vndModel->vnd_active);
				throw new Exception("Your account is $status.", ReturnSet::ERROR_UNAUTHORISED);
			}

			$bModels	 = $cabModel->bookings;
			$bkgModel	 = $bModels[0];
			if($bModels[0]->bkg_bcb_id != $tripId)
			{

				throw new Exception("Sorry! This trip no longer exists. Please refresh your screen.", ReturnSet::ERROR_INVALID_DATA);
			}

			$isAccessible = BookingCab::checkVendorTripRelation($tripId, $vendorId);
			if(!$isAccessible)
			{
				if(in_array($bkgModel->bkg_status, [3, 5, 6, 7]))
				{
					throw new Exception(json_encode(["Booking is already allocated to other"]), ReturnSet::ERROR_VALIDATION);
				}
				throw new Exception(json_encode(["Cannot show details"]), ReturnSet::ERROR_VALIDATION);
			}

//

			$bidAmount		 = $obj->amount;
			$bidAction		 = $obj->action; //0=>deny,1=>bid,2=>direct accept
			$isDirectAccept	 = ($bidAction == 2) ? true : false;

			$acceptBidAmount = BookingVendorRequest::getDirectAcceptAmount($vendorId, $tripId);
			if($bidAction == 2 && !$bidAmount)
			{

				$bidAmount = $acceptBidAmount;
			}
			$userInfo = UserInfo::getInstance();

			$bModels	 = $cabModel->bookings;
			$isGozoNow	 = $bModels[0]->bkgPref->bkg_is_gozonow;
			if($isGozoNow == 1 && $obj->action > 0)
			{
				$obj->setGNOwAcceptData($reqObj);
			}

			switch($bidAction)
			{
				case 0: //Deny

					if($isGozoNow == 1)
					{
						$returnSet = BookingCab::processGNowDenyBidding($cabModel, $obj, $vendorId);
					}
					else
					{
						$returnSet = BookingVendorRequest::denyTripByVendor($tripId, $vendorId, $userInfo);
					}
					break;
				case 2: //Direct Accept
				case 1: //Bid
					//$returnSet = BookingCab::validateVendorTripForBidding($tripId, $vendorId);

					if($isGozoNow == 1)
					{
						$returnSet = BookingCab::processGNowAcceptBidding($cabModel, $obj, $vendorId);
					}
					else
					{
						$validateArr		 = bookingVendorRequest::model()->validateCondition($tripId, $bidAmount, $vendorId);
						$allowDirectAccept	 = $validateArr['allowDirectAccept'];
						$message			 = $validateArr['message'];
						if($isDirectAccept && !$allowDirectAccept)
						{
							$isDirectAccept	 = false;
							$error			 = $message;
							throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
						}
						if(!$isDirectAccept && $allowDirectAccept && $acceptBidAmount >= $bidAmount)
						{
							$isDirectAccept = true;
						}

						$returnSet = BookingVendorRequest::acceptTripByVendor($tripId, $vendorId, $bidAmount, $userInfo, $isDirectAccept, $bidAction, $message);
					}

					break;
			}

			if($returnSet->getStatus(true))
			{
				$msg	 = $returnSet->getMessage();
				$message = trim($error . '  ' . $msg);
				$returnSet->setMessage($message);
			}
			else
			{
				$error = $returnSet->getErrors();
				$returnSet->setErrorCode(107);
				$returnSet->setErrors($error);
			}
		}
		catch(Exception $ex)
		{

			$returnSet	 = ReturnSet::setException($ex);
			$returnSet->setMessage($ex->getMessage());
			$errors		 = $returnSet->getErrors();
			$returnSet->setErrors($errors);
		}
		if(!$returnSet->getStatus() && $returnSet->hasErrors())
		{
			//$errors = $returnSet->getErrors();

			$errorDesc = implode('; ', $errors);
			$cabModel->logFailedVendorAssignment($errorDesc, $userInfo, $vendorId, $bidAmount);
		}

		return $returnSet;
	}

	public function tripDetails()
	{
		$requestData = Yii::app()->request->rawBody;
		$returnSet	 = new ReturnSet();
		try
		{
			if(!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj = CJSON::decode($requestData, false);

			$tripId			 = $reqObj->tripId;
			$status			 = $reqObj->status;
			$dependency_msg	 = "";
			$vendorId		 = $this->getVendorId();
			$bcbModel		 = \BookingCab::model()->findByPk($tripId);
			$bkgModels		 = $bcbModel->bookings;
			$bkgModel		 = $bkgModels[0];
			$isAccessible	 = BookingCab::checkVendorTripRelation($tripId, $vendorId);
			if(!$isAccessible)
			{
				if(in_array($bkgModel->bkg_status, [3, 5, 6, 7]))
				{
					throw new Exception(json_encode(["Booking is already allocated to other"]), ReturnSet::ERROR_VALIDATION);
				}
				throw new Exception(json_encode(["Cannot show details"]), ReturnSet::ERROR_VALIDATION);
			}

			$tripData = Booking::getTripDetails1($tripId, $status, $vendorId, 1);
			if(sizeof($tripData) == 0)
			{
				throw new Exception("Trip is not in an assignable status", ReturnSet::ERROR_INVALID_DATA);
			}

			$directAcptAmount			 = BookingVendorRequest::getDirectAcceptAmount($vendorId, $tripId);
			$tripData[0]['acptAmount']	 = $directAcptAmount;
			if($tripData != [])
			{
				$bkgId = $tripData[0]['bkg_id'];
				$returnSet->setStatus(true);

				$noteArrList = DestinationNote::model()->showNoteApi($bkgId, UserInfo::getUserType());
				$countNote	 = count($noteArrList);
				if($countNote > 0)
				{
					$tripData[0]['isDestinationNote'] = 1;
				}
				else
				{
					$tripData[0]['isDestinationNote'] = 0;
				}
				if($tripData[0]['is_biddable'] == 1)
				{
					$tripData[0]['dependencyMsg'] = \VendorStats::getDependencyMessage($vendorId);
				}
				$tripData[0]['cab_type_tier'] = $tripData[0]['cab_model'] . '(' . $tripData[0]['cab_lavel'] . '-' . $tripData[0]['vht_make'] . ' ' . $tripData[0]['vht_model'] . ')';
				if($tripData[0]['cab_lavel'] == 'Select' || $tripData[0]['cab_lavel'] == 'Select Plus')
				{
					$tripData[0]['cab_model']	 = $tripData[0]['vht_model'];
					$tripData[0]['cab_lavel']	 = 'Select';
				}
				if($tripData[0]['scc_id'] == 2)
				{
					$tripData[0]['is_cng_allowed'] = '2';
				}
			}

			$objData	 = \Filter::convertToObject($tripData);
			$objTripData = \Filter::convertToObject($tripData[0]);

			$cttid	 = ContactProfile::getByVendorId($vendorId);
			$objTrip = \Beans\vendor\TripDetailResponse::setTripData($objTripData, true, $cttid);

			$objBooking			 = \Beans\Booking::setBookingData($objData);
			$objTrip->bookings	 = $objBooking;
			$returnSet->setStatus(true);
			$returnSet->setData($objTrip);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function unassign()
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$requestData = Yii::app()->request->rawBody;
			if(!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj		 = CJSON::decode($requestData, false);
			$tripId		 = $reqObj->tripId;
			$reasonStr	 = $reqObj->reason;
			$reasonId	 = ($reqObj->reasonId > 0) ? $reqObj->reasonId : 7;
			$reason		 = (!$reasonStr || $reasonStr == '') ? 'App' : $reasonStr;
			$result		 = BookingCab::unassignDCO($tripId, $reason, $reasonId);
			if($result)
			{
				$msg = "Booking unassigned successfully.";
				$returnSet->setMessage($msg);
				$returnSet->setStatus(true);
				DBUtil::commitTransaction($transaction);
			}
		}
		catch(Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function bookingDetails()
	{
		$requestData = Yii::app()->request->rawBody;
		$returnSet	 = new ReturnSet();
		try
		{
			if(!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj = CJSON::decode($requestData, false);

			$bkgId			 = $reqObj->bkgId;
			$status			 = $reqObj->status;
			$dependency_msg	 = "";

			$bkgModel = \Booking::model()->findByPk($bkgId);

			if(!in_array($bkgModel->bkg_status, [3, 5, 6, 7]))
			{
				throw new Exception(json_encode(["Cannot show details"]), ReturnSet::ERROR_VALIDATION);
			}

			$tripId		 = $bkgModel->bkg_bcb_id;
			$vendorId	 = $this->getVendorId(false);
			$drvId		 = $this->getDriverId(false);

			$isVendor	 = ($vendorId > 0);
			$isDriver	 = ($drvId > 0);
			if(!$isVendor && !$isDriver)
			{
				throw new Exception("Cannot show details", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}
			$cttid			 = $this->getContactId();
			$isVndAccessible = BookingCab::checkVendorTripRelation($tripId, $vendorId);
			$objBooking = null;
			if((UserInfo::getUserType() == UserInfo::TYPE_VENDOR || $isVendor ) && $isVndAccessible)
			{
				$hashBkgId			 = Yii::app()->shortHash->hash($bkgId);
				$hashVndId			 = Yii::app()->shortHash->hash($vendorId);
				$objBooking			 = \Beans\Booking::setDataById($bkgId, '', $cttid);
				$objBooking->bkvnUrl = Yii::app()->params['fullBaseURL'] . '/bkvn/' . $hashBkgId . '/' . $hashVndId;
				goto skipAll;
			}
			$isDrvAccessible = BookingCab::checkDriverBookingRelation($bkgId, $drvId);
			if((UserInfo::getUserType() == UserInfo::TYPE_DRIVER || $isDriver) && $isDrvAccessible)
			{
				$objBooking = \Beans\Booking::setDataById($bkgId, 'driver', $cttid, true);
			}
			skipAll:
			if(!$objBooking)
			{
				throw new Exception(json_encode(["Booking is already allocated to other"]), ReturnSet::ERROR_VALIDATION);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($objBooking);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function assignDriverCab()
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$requestData = Yii::app()->request->rawBody;
			if(!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$userInfo	 = UserInfo::getInstance();
			$jsonMapper	 = new JsonMapper();
			$reqObj		 = CJSON::decode($requestData, false);
			/** @var \Beans\booking\Trip() $obj */
			$obj		 = \Beans\booking\Trip::setCabDriver($reqObj);

			$tripId				 = $obj->tripId;
			$vehicleId			 = $obj->cab->id;
			$driverId			 = $obj->driver->id;
			$drvcontactNumber	 = $obj->driver->phone[0]->number;
			if($drvcontactNumber == "")
			{
				throw new Exception(json_encode("Driver contact number is missing."), ReturnSet::ERROR_VALIDATION);
			}
			$result		 = BookingCab::checkCabDriverBeforeAssignment($tripId, $vehicleId, $driverId, $drvcontactNumber);
			$tripModel	 = $result[0];
			if(empty($tripModel))
			{
				throw new Exception(json_encode("Invalid Trip Details"), ReturnSet::ERROR_VALIDATION);
			}
			$cabType				 = $result[1];
			$tripModel->chk_user_msg = [0, 1];  // sms for user and driver
			Vehicles::approveVehicleStatus($vehicleId);
			$success				 = $tripModel->assignCabDriver($vehicleId, $driverId, $cabType, UserInfo::getInstance());
			if(!$success)
			{
				throw new Exception(json_encode($tripModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			$msg = "Successfully assigned Cab & Driver.";
			$returnSet->setMessage($msg);
			DBUtil::commitTransaction($transaction);
			$returnSet->setStatus(true);
		}
		catch(Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
		}
		/*		 * if (!$returnSet->getStatus() && $returnSet->hasErrors())
		  {

		  $bcbModel	 = BookingCab::model()->findByPk($tripId);
		  $errors		 = $returnSet->getErrors();
		  $errorDesc	 = implode('; ', $errors);
		  $bcbModel->logFailedCabDriverAssignment($errorDesc, $userInfo);
		  }* */
		return $returnSet;
	}

	public function bidStatusList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$vndId	 = $this->getVendorId();
			$data	 = BookingVendorRequest::getBidStatusByVendor($vndId);

			$response	 = new \Beans\vendor\TripDetailResponse();
			$res		 = $response->getBidList($data);
			$returnSet->setData($res);
			$returnSet->setStatus(true);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function gnowDenyReasons()
	{
		$returnSet = new ReturnSet();

		try
		{
			$reasonList	 = BookingSub::getGNowBidDenyReasonList();
			$dataArr	 = [];
			foreach($reasonList as $key => $row)
			{
				$dataArr[] = \Beans\common\ValueObject::setIdlabel($key, $row);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($dataArr);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function unassignReasonList()
	{
		$returnSet = new ReturnSet();

		try
		{
			$unassignReason = Config::get('booking.unassignReason');
			if(!empty($unassignReason))
			{
				$showReasonArr	 = CJSON::decode($unassignReason);
				//$showReasonArr = Vendors::model()->getCancelReasonList();
				$dataArr		 = [];
				foreach($showReasonArr as $key => $row)
				{
					$dataArr[] = \Beans\common\ValueObject::setIdlabel($key, $row);
				}
				$returnSet->setStatus(true);
				$returnSet->setData($dataArr);
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function gnowReadyToGo()
	{
		$returnSet = new ReturnSet();
		try
		{
			$vendorId = $this->getVendorId();
			if(!$vendorId)
			{
				throw new Exception("Unauthorized Vendor", ReturnSet::ERROR_INVALID_DATA);
			}
			$rawData		 = Yii::app()->request->rawBody;
			$data			 = CJSON::decode($rawData, true);
			$tripId			 = $data['tripId'];
			$bcbModel		 = \BookingCab::model()->findByPk($tripId);
			$bkgModels		 = $bcbModel->bookings;
			$bkgModel		 = $bkgModels[0];
			$isAccessible	 = BookingCab::checkVendorTripRelation($tripId, $vendorId);
			if(!$isAccessible)
			{
				if(in_array($bkgModel->bkg_status, [3, 5, 6, 7]))
				{
					throw new Exception(json_encode(["Booking is already allocated to other"]), ReturnSet::ERROR_VALIDATION);
				}
				throw new Exception(json_encode(["Cannot show details"]), ReturnSet::ERROR_VALIDATION);
			}
			$returnSet = BookingCab::setReadyToGo($tripId, $vendorId);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function gnowSomeProblemToGo()
	{
		$returnSet = new ReturnSet();
		try
		{
			$vendorId = $this->getVendorId();

			if(!$vendorId)
			{
				throw new Exception("Unauthorized Vendor", ReturnSet::ERROR_INVALID_DATA);
			}
			$rawData = Yii::app()->request->rawBody;
			$data	 = CJSON::decode($rawData, true);
			$tripId	 = $data['tripId'];

			$returnSet = BookingCab::setGnowSomeProblemToGo($tripId, $vendorId);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function gnowAllocatedBidDetails()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$result		 = Vendors::model()->authoriseVendor($token);
		if($result)
		{
			$formData		 = Yii::app()->request->getParam('data');
			$rawData		 = Yii::app()->request->rawBody;
			$processSyncData = $formData . $rawData;
			Logger::trace("<===Requset===>" . $processSyncData);
			$data			 = CJSON::decode($processSyncData, true);
			$tripId			 = $data['trip_id'];

			$vendorId = $this->getVendorId();

			$dataRow = BookingVendorRequest::getPreferredVendorbyBooking($tripId);
			if(isset($dataRow['bvr_vendor_id']) && $dataRow['bvr_vendor_id'] != $vendorId)
			{
				throw new Exception(json_encode("Booking already assigned to other vendor"), ReturnSet::ERROR_VALIDATION);
			}
			$model = Booking::model()->findByAttributes(['bkg_bcb_id' => $tripId]);
			if(!in_array($model->bkg_status, [3, 5]))
			{
				throw new Exception("Cannot show details", ReturnSet::ERROR_UNAUTHORISED);
			}
			$isAccessible = BookingCab::checkVendorTripRelation($tripId, $vendorId);
			if(!$isAccessible)
			{
				throw new Exception("Not authorised to proceed", ReturnSet::ERROR_UNAUTHORISED);
			}


			if($model != [])
			{

				$response = new \Stub\common\Booking();
				$response->setAllocatedGnowData($model);

				$gnowBid			 = new \Stub\vendor\GnowBid();
				$gnowBid->setAllocatedBidData($dataRow);
				$response->bidInfo	 = $gnowBid;
				$rtArr				 = json_decode($model->bkg_route_city_names);

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
			$returnSet->setErrors("Unauthorised Vendor", ReturnSet::ERROR_UNAUTHORISED_VENDOR);
		}
		return $returnSet;
	}

	public function tripDetailsV2()
	{
		$returnSet = new ReturnSet();

		$data = Yii::app()->request->getParam('data');
		try
		{
			if($data == "")
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}

			$jsonObj = CJSON::decode($data, false);
			$tripId	 = $jsonObj->trip_id;
			$status	 = $jsonObj->status;

			$vendorId = $this->getVendorId();

			$bcbModel		 = \BookingCab::model()->findByPk($tripId);
			$bkgModels		 = $bcbModel->bookings;
			$bkgModel		 = $bkgModels[0];
			$isAccessible	 = BookingCab::checkVendorTripRelation($tripId, $vendorId);
			if(!$isAccessible)
			{
				if(in_array($bkgModel->bkg_status, [3, 5, 6, 7]))
				{
					throw new Exception(json_encode(["Booking is already allocated to other"]), ReturnSet::ERROR_VALIDATION);
				}
				throw new Exception(json_encode(["Cannot show details"]), ReturnSet::ERROR_VALIDATION);
			}



			$data = Booking::getTripDetails1($tripId, $status, $vendorId, 1);
			if(!$data)
			{
				$returnSet->setMessage("No Record Found.");
			}
			else
			{
				$res = new \Stub\vendor\TripDetailsResponse();
				$res->setTripData($data);

//				foreach ($data as $res)
//				{
//					$model					 = Booking::model()->findByPk($res['bkg_id']);
//					/* @var $response \Stub\vendor\TripDetailsResponse */
//					$res					 = new \Stub\vendor\TripDetailsResponse();
//					$res->setTripData($model);
//					$returnSet->setStatus(true);
//					$response				 = Filter::removeNull($response);
//					$responsedt->dataList[]	 = $res;
//				}
				$returnSet->setStatus(true);
				#$response = $responsedt;
				$returnSet->setData($res);
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function gnowTripDetails()
	{

		$requestData = Yii::app()->request->rawBody;
		$returnSet	 = new ReturnSet();
		try
		{
			if(!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj = CJSON::decode($requestData, false);

			$tripId = $reqObj->tripId;

			$dependency_msg	 = "";
			$vendorId		 = $this->getVendorId();
			$bcbModel		 = \BookingCab::model()->findByPk($tripId);
			$bkgModels		 = $bcbModel->bookings;
			$bkgModel		 = $bkgModels[0];
			$isAccessible	 = BookingCab::checkVendorTripRelation($tripId, $vendorId);
			if(!$isAccessible)
			{
				if(in_array($bkgModel->bkg_status, [3, 5, 6, 7]))
				{
					throw new Exception(json_encode(["Booking is already allocated to other"]), ReturnSet::ERROR_VALIDATION);
				}
				throw new Exception(json_encode(["Cannot show details"]), ReturnSet::ERROR_VALIDATION);
			}
			$directAcptAmount = BookingVendorRequest::getDirectAcceptAmount($vendorId, $tripId);

			$cttId								 = ContactProfile::getByVendorId($vendorId);
			/** @var \BookingCab $bcbModel */
			$bcbModel->recommended_vendor_amount = $directAcptAmount;

			$objTrip = \Beans\booking\Trip::setByModel($bcbModel, $bkgModel, '', $cttId);

//			$bookings			 = new \Beans\Booking();
//			$bookings->setBookingData($bkgModels);
			$objTrip->bookings = \Beans\Booking::setBookingData($bkgModels);

			$returnSet->setStatus(true);
			$returnSet->setData($objTrip);
			$ntlId = NotificationLog::getIdForGozonow($vendorId, $tripId);
			if($ntlId > 0)
			{
				$ntlDataArr	 = ['id' => $ntlId, 'isRead' => 1];
				$resultData	 = NotificationLog::updateReadNotification($ntlDataArr);
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function getServedList()
	{
		$requestData = Yii::app()->request->rawBody;

		$reqArr		 = CJSON::decode($requestData, true);
		$returnSet	 = new ReturnSet();
		try
		{
			//	$vndId		 = $this->getVendorId();
			//	$returnSet	 = BookingSub::populateGetServedIdListByEntity($vndId, UserInfo::TYPE_VENDOR, $reqArr);

			$entId		 = UserInfo::getEntityId();
			$entType	 = UserInfo::getUserType();
			$returnSet	 = BookingSub::populateGetServedIdListByTrip($entId, $entType, $reqArr);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * Pending will work on it
	 * @return type
	 */
	public function getServedTripList()
	{
		$requestData = Yii::app()->request->rawBody;

		$reqArr		 = CJSON::decode($requestData, true);
		$returnSet	 = new ReturnSet();
		try
		{
			$vndId		 = $this->getVendorId();
			$returnSet	 = BookingSub::populateGetServedIdListByTrip($vndId, UserInfo::TYPE_VENDOR, $reqArr);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * resend OTP for booking verification
	 */
	public function resendBkgStartOtp()
	{
		$requestData = Yii::app()->request->rawBody;

		$reqArr		 = CJSON::decode($requestData, true);
		$returnSet	 = new ReturnSet();
		try
		{
			$bkgId	 = ($reqArr['id'] > 0) ? $reqArr['id'] : $reqArr['bkg_id'];
			$result	 = Users::model()->sendTripOtp($bkgId);
			if($result == false)
			{
				throw new Exception("Invalid Booking Id.", ReturnSet::ERROR_INVALID_DATA);
			}
			$returnSet->setStatus(true);
			$returnSet->setMessage("OTP is being sent to customer by SMS. Tell customer to give you OTP at time of starting trip.");
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function addComment()
	{
		$returnSet = new ReturnSet();
		try
		{

			$vendorId = (int) $this->getVendorId();

			$requestData = Yii::app()->request->rawBody;
			if(!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj		 = CJSON::decode($requestData, false);
			$jsonMapper	 = new JsonMapper();
			/** @var \Beans\booking\DriverComment() $obj */
			$obj		 = $jsonMapper->map($reqObj, new Beans\booking\DriverComment());
			$desc		 = $obj->setDesc();
			$userInfo	 = UserInfo::getInstance();
			$bkgId		 = (int) $obj->booking->id;
			$model		 = Booking::model()->findByPk($bkgId);

			$tripId = $model->bkg_bcb_id;

			$isAccessible = BookingCab::checkVendorTripRelation($tripId, $vendorId);
			if(!$isAccessible)
			{
				throw new Exception(json_encode("Not authorised to proceed"), ReturnSet::ERROR_VALIDATION);
			}

			$driverId = $model->bkgBcb->bcb_driver_id;

			$eventId = BookingLog::REMARKS_ADDED;
			if($desc != '')
			{
				$success = BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId);
			}
			if($success)
			{
				$returnSet->setMessage('Remarks added successfully');
			}
			$dataReader	 = BookingLog::getCommentTraceByDCO($vendorId, $driverId, $bkgId);
			$dataReader->getRowCount();
			/** @var \Beans\booking\DriverComment() $obj */
			$data		 = $obj->setList($dataReader);
			$returnSet->setData($data);
			$returnSet->setStatus($success);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function getCommentList()
	{
		$returnSet = new ReturnSet();
		try
		{

			$vendorId = (int) $this->getVendorId();

			$requestData = Yii::app()->request->rawBody;
			if(!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj		 = CJSON::decode($requestData, false);
			$jsonMapper	 = new JsonMapper();
			/** @var \Beans\booking\DriverComment() $obj */
			$obj		 = $jsonMapper->map($reqObj, new Beans\booking\DriverComment());

			$bkgId	 = (int) $obj->booking->id;
			$model	 = Booking::model()->findByPk($bkgId);

			$tripId = $model->bkg_bcb_id;

			$isAccessible = BookingCab::checkVendorTripRelation($tripId, $vendorId);
			if(!$isAccessible)
			{
				throw new Exception(json_encode("Not authorised to proceed"), ReturnSet::ERROR_VALIDATION);
			}

			$driverId = $model->bkgBcb->bcb_driver_id;

			$dataReader	 = BookingLog::getCommentTraceByDCO($vendorId, $driverId, $bkgId);
			$dataReader->getRowCount();
			/** @var \Beans\booking\DriverComment() $obj */
			$data		 = $obj->setList($dataReader);
			$returnSet->setData($data);
			$returnSet->setStatus(true);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function getDestinationNoteList()
	{
		$returnSet = new ReturnSet();
		//$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			//AppTokens::validateToken($token);
			$data = Yii::app()->request->rawBody;
			if(!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj = CJSON::decode($data, false);
			$bkg_id	 = $jsonObj->id;
			if($bkg_id == "")
			{
				$trip_id = $jsonObj->tripId;
				$bkgArr	 = BookingCab::model()->getBkgIdByTripId($trip_id);
				$bkg_id	 = $bkgArr['bkg_ids'];
			}

			if($bkg_id == "")
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}

			Logger::create("Show Destination Request : " . $data, CLogger::LEVEL_INFO);
			$userInfo	 = UserInfo::getInstance();
			$userId		 = $userInfo->userId;
			$vendorId	 = (int) $this->getVendorId(false);
			$driverid	 = (int) $this->getDriverId(false);
			if($vendorId != "" || $driverid != "")
			{
				$usertype = "both";
				goto query;
			}
			if($vendorId != "")
			{
				$usertype = 2;
				goto query;
			}
			if($driverId != "")
			{
				$usertype = 3;
				goto query;
			}
			query:
			//$userId				 = $userInfo->userId	 = empty($userInfo->userId) ? 123 : $userInfo->userId;
			if(!$userId)
			{
				throw new Exception("Invalid User.", ReturnSet::ERROR_UNAUTHORISED);
			}
			$userInfo->userType	 = UserInfo::getUserType(); //Vendor type = 2
			$userInfo->platform	 = 2; //Platform type =2	

			$noteArrList = DestinationNote::model()->showNoteApi($bkg_id, $usertype);

			$response	 = [];
			$jsonMapper	 = new JsonMapper();
			if($noteArrList != false)
			{
				/** @var $res \Beans\common\DestinationNote */
				$res		 = new \Beans\common\DestinationNote();
				$responseDt	 = $res->getData($noteArrList);
			}
			foreach($responseDt as $res)
			{
				$responsedt->dataList[] = $res;
			}
			$response = $responsedt;
			//print_r($response);
			if(!empty($response))
			{
				$returnSet->setData($response);
			}
			else
			{
				$returnSet->setMessage("No Travel advisories found.");
			}

			$returnSet->setStatus(true);

			Logger::create("Show Detination Response  : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function showBidRank()
	{
		$returnSet = new ReturnSet();

		try
		{
			//$process_sync_data	 = Yii::app()->request->getParam('data');
			$process_sync_data	 = Yii::app()->request->rawBody;
			$data				 = CJSON::decode($process_sync_data, true);

			$tripId		 = $data['id'];
			$getBkgId	 = BookingCab::getBkgIdByTripId($tripId);
			$bookingIds	 = explode(",", $getBkgId[bkg_ids]);
			$bookingId	 = $bookingIds[0];

			$vendorId	 = UserInfo::getEntityId();
			$showArr	 = BookingVendorRequest::showBidRank($bookingId, $vendorId);

			if(!empty($showArr))
			{

				$returnSet->setStatus(true);
				$returnSet->setMessage($showArr);
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public static function assignBooking()
	{
		$returnSet = new ReturnSet();
		try
		{
			//$process_sync_data	 = Yii::app()->request->getParam('data');
			$process_sync_data	 = Yii::app()->request->rawBody;
			$data				 = CJSON::decode($process_sync_data, true);

			$status		 = $data['status'];
			$time		 = $data['time'];
			$vendorId	 = UserInfo::getEntityId();
			$recordData	 = Vendors::model()->getassignList($vendorId, $status, $time);
			$count		 = count($recordData);
			if($count < 1)
			{
				throw new Exception("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$objBooking	 = \Beans\booking\Trip::setTripList($recordData);
			$datalist	 = $objBooking;

			$returnSet->setStatus(true);
			$returnSet->setData($datalist);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}
	public function showDestinationArea()
	{
		$returnSet = new ReturnSet();
		//$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{


			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($process_sync_data, true);
			$areaType			 = $data['areaType'];
			$searchTxt			 = $data['search_txt'];

			$showArr = DestinationNote::model()->getArea($areaType, $searchTxt);
			
			$res					 = new \Beans\common\DestinationNote();
			$responsedt = $res->setAreaData($showArr);
			//$responsedt->dataList	 = $showArr;
			$response				 = $responsedt;
			$data					 = Filter::removeNull($response);
			if(empty($data))
			{
				throw new Exception("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($data);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function addDestinationNoteList()
	{
		$returnSet = new ReturnSet();
		try
		{

			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);

			/** @var \Beans\common\DestinationNote $obj */
			$obj					 = $jsonMapper->map($jsonObj, new Beans\common\DestinationNote());
			Logger::profile("Request Mapped");
			/** @var DestinationNote $model */
			$model					 = $obj->getModel();
			$userInfo				 = UserInfo::getInstance();
			$model->dnt_created_by	 = UserInfo::getUserId();

			$model->dnt_created_by_role	 = UserInfo::getUserType();
			$fromDate					 = $model->dnt_valid_from_date;
			$fromTime					 = $model->dnt_valid_from_time;
			$model->dnt_valid_from		 = $fromDate . ' ' . $fromTime;

			$toDate				 = $model->dnt_valid_to_date;
			$toTime				 = $model->dnt_valid_to_time;
			$model->dnt_valid_to = $toDate . ' ' . $toTime;

			$model->scenario = 'addValid';
			$errors			 = CActiveForm::validate($model);
			if ($errors != "[]")
			{
				$errors = $model->getErrors();
				throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
			}
			if ($model->save())
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Destination note added");
			}
		}
		catch (Exception $ex)
		{
			$returnSet	 = ReturnSet::setException($ex);
			$errorMsg	 = "Please enter valid data and try again";
			$returnSet->setMessage($errorMsg);
		}

		return $returnSet;
	}
}
