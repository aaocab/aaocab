<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class BookingController extends BaseController
{

	public $layout = 'main';
	public $email_receipient, $pageTitle1, $pageDesc;

	public function filters()
	{
		return array(
//            array(
//                'application.filters.HttpsFilter + create',
//                'bypass' => false),
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
				'actions'	 => array('list','tripdetails','gridview','pendinglist','bidaccept','assigncab'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('test',
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array(),
				'users'		 => array('admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
				'deniedCallback' => function() { Yii::app()->controller->redirect(array ('/supplier/user/signin')); }

			),
		);
	}

	public function actionList()
	{
		$strstatus = Yii::app()->request->getParam('status');
		
		if($strstatus=='new' || $strstatus == 'partnernew')
		{
			$status = 2;
		}
		else if($strstatus=='assigned')
		{
			$status = 3;
		}
		else if($strstatus=='allocated')
		{
			$status = 5;
		}
		else if($strstatus=='completed')
		{
			$status = 6;
		}
		else if($strstatus=='cancelled')
		{
			$status = 9;
		}
		$vndId = Yii::app()->user->getEntityID();
		$filterModel = new stdClass();
		$filterModel->pickupDateRange->fromDate = date('Y-m-d', strtotime('-1 day'));
		$filterModel->pickupDateRange->toDate = date('Y-m-d', strtotime('+1 month'));
		$model = new Booking();
		$model->bkg_pickup_date1	 = date('Y-m-d', strtotime('-1 day'));
		$model->bkg_pickup_date2	 = date('Y-m-d', strtotime('+1 month'));
		$model->bkg_status            = $status;
		if (isset($_REQUEST['Booking']))
		{
			$arr  = Yii::app()->request->getParam('Booking');
			$model->bkg_status            = ($status=='')?$arr['bkg_status']:$status;
			$model->bkg_create_date1		 = $arr['bkg_create_date1'];
			$model->bkg_create_date2		 = $arr['bkg_create_date2'];
			$model->bkg_pickup_date1		 = $arr['bkg_pickup_date1'];
			$model->bkg_pickup_date2		 = $arr['bkg_pickup_date2'];
			$model->bkg_vehicle_type_id	     = $arr['bkg_vehicle_type_id'];
			$model->search					 = $arr['search'];
			$model->trip_id					 = trim($arr['trip_id']);
			$model->bkg_name			     = trim($arr['bkg_name']);
			$model->bkgtypes				 = $arr['bkgtypes'];		
		}
		if($model->bkg_status == 2)
		{
			$filterModel->pickupDateRange->fromDate	 = date('Y-m-d');

			if (isset($_REQUEST['Booking']))
		    {
				$arr  = Yii::app()->request->getParam('Booking');
				$filterModel->trip_id = trim($arr['trip_id']);
				$filterModel->pickupDateRange->fromDate = $arr['bkg_pickup_date1'];
				$filterModel->pickupDateRange->toDate = $arr['bkg_pickup_date2'];
				$filterModel->bkg_vehicle_type_id = $arr['bkg_vehicle_type_id'];
				$filterModel->bkgtypes = $arr['bkgtypes'];	
				$filterModel->search			= $arr['search'];
		    }
		//	$dataProvider = BookingVendorRequest::getPendingRequestWebV2($vndId,null,false,$model);
			if($strstatus == 'partnernew')
			{
			   $filterModel->agent_id = AgentVendorRelation::getByVnd($vndId);
			}

			$dataProvider = BookingVendorRequest::getPendingRequestV2($vndId,-1,$filterModel,null,true);
			if($strstatus == 'partnernew')
			{
			   $model->bkg_status = 'partnernew';
			}
		}
		else
		{
			$dataProvider = $model->getSupplierBookingList($vndId);
		}

		$dataProvider->getPagination()->params = array_filter($_GET + $_POST);
        $dataProvider->getSort()->params       = array_filter($_GET + $_POST);

		$this->render('list',['model'=>$model,'dataProvider'=>$dataProvider]);
	}

	public function actionTripdetails()
	{
		$tripId = Yii::app()->request->getParam('tripId');
		$vndId = Yii::app()->user->getEntityID();
		$bkgStatus = DBUtil::queryScalar("SELECT bkg_status FROM booking where bkg_bcb_id=:tripid",null,['tripid'=>$tripId]);
		if($bkgStatus == 2)
		{
			$filterModel->bcbId = $tripId;
			$booking = BookingVendorRequest::getPendingRequestV2($vndId,0,$filterModel);
		}
		else
		{
			$booking = Booking::getSupplierTripDetails($tripId,$vndId);
		}
		$this->render('tripDetails',['booking'=>$booking]);
	}

	public function actionGridview()
	{
		$tripId = Yii::app()->request->getParam('tripId');
		$booking = Booking::getSupplierTripDetails($tripId,Yii::app()->user->getEntityID());
		$this->render('gridview',['booking'=>$booking]);
	}

	public function actionBidaccept()
	{
		$requestData->tripId = Yii::app()->request->getParam('tripId');
		$requestData->amount = Yii::app()->request->getParam('amount');
		$requestData->action = Yii::app()->request->getParam('action');
		
		$returnSet = new ReturnSet();
		try
		{
			if($requestData->action==0 && !$requestData->tripId)
			{
			   throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			else if(in_array($requestData->action, [1,2]) && (!$requestData->tripId || !$requestData->amount))
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}

			$reqObj		 = $requestData;
			$obj		 = new Beans\booking\BidAction($reqObj);
			$bidAction = $obj->action; //0=>deny,1=>bid,2=>direct accept
			$bidAmount = $obj->amount;

			$userInfo = UserInfo::getInstance();
			$vendorId = Yii::app()->user->getEntityID();
			$tripId = (int) trim($obj->tripId);
			if (!$tripId || $tripId==0)
			{
				$error = "No trip found with the data";
				throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
			}
			$cabModel = BookingCab::model()->findByPk($tripId);
			if (!$cabModel)
			{
				$error = "No trip found with the data";
				throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
			}
			$vndModel = Vendors::model()->findByPk($vendorId);
			if (in_array($vndModel->vnd_active, [0, 2, 3, 4]))
			{

				$activeList	 = $vndModel->vendorStatus;
				$status		 = $activeList[$vndModel->vnd_active];

				throw new Exception("Your account is in $status status.", ReturnSet::ERROR_UNAUTHORISED);
			}

			$bModels	 = $cabModel->bookings;
			$bkgModel	 = $bModels[0];
			if ($bModels[0]->bkg_bcb_id != $tripId)
			{

				throw new Exception("Sorry! This trip no longer exists. Please refresh your screen.", ReturnSet::ERROR_INVALID_DATA);
			}

			$isAccessible = BookingCab::checkVendorTripRelation($tripId, $vendorId);
			if (!$isAccessible)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Data added successfully");
				goto skip;
			}

			
			
			$isDirectAccept	 = ($bidAction == 2) ? true : false;

			$acceptBidAmount = BookingVendorRequest::getDirectAcceptAmount($vendorId, $tripId);
			if ($bidAction == 2 && !$bidAmount)
			{
				$bidAmount = $acceptBidAmount;
			}
			

			$bModels	 = $cabModel->bookings;
			$isGozoNow	 = $bModels[0]->bkgPref->bkg_is_gozonow;
			if ($isGozoNow == 1 && $obj->action > 0)
			{
				$obj->setGNOwAcceptData($reqObj);
			}

			switch ($bidAction)
			{
				case 0: //Deny

					if ($isGozoNow == 1)
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
				
					if ($isGozoNow == 1)
					{
						$returnSet = BookingCab::processGNowAcceptBidding($cabModel, $obj, $vendorId);
					}
					else
					{
						$validateArr		 = BookingVendorRequest::model()->validateCondition($tripId, $bidAmount, $vendorId, '', $bidAction);
						$allowDirectAccept	 = $validateArr['allowDirectAccept'];
						$message			 = $validateArr['message'];
						if ($isDirectAccept && !$allowDirectAccept)
						{
							$isDirectAccept	 = false;
							$error			 = $message;
							throw new Exception(json_encode($error), ReturnSet::ERROR_INVALID_DATA);
						}
						if (!$isDirectAccept && $allowDirectAccept && $acceptBidAmount >= $bidAmount)
						{
							$isDirectAccept = true;
						}

						$returnSet = BookingVendorRequest::acceptTripByVendor($tripId, $vendorId, $bidAmount, $userInfo, $isDirectAccept, $bidAction, $message);
					}
					break;
			}
			skip:
			if ($returnSet->getStatus(true))
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
		catch (Exception $ex)
		{

			$returnSet	 = ReturnSet::setException($ex);
			$returnSet->setMessage($ex->getMessage());
			$errors		 = $returnSet->getErrors();
			$returnSet->setErrors($errors);
		}
		if (!$returnSet->getStatus() && $returnSet->hasErrors() && $bidAction !=0)
		{


			$errorDesc = implode('; ', $errors);
			$cabModel->logFailedVendorAssignment($errorDesc, $userInfo, $vendorId, $bidAmount);
		}
		$returnSet->setData(['isDirectAccept'=>$isDirectAccept]);
		echo json_encode($returnSet);
		Yii::app()->end();
	}

	public function actionAssigncab()
	{
		$tripId	 = Yii::app()->request->getParam('id');
		$vndId	 = Yii::app()->user->getEntityID();
		$model	 = new BookingCab();
		$arrJSON = array();
		$cabs	 = CJSON::encode($arrJSON);
		$drivers = CJSON::encode($arrJSON);

		$model = BookingCab::model()->find('bcb_id=:bcbId AND bcb_vendor_id=:vndId', ['bcbId' => $tripId, 'vndId' => $vndId]);
		if ($model == '')
		{
			throw new Exception("Invalid Data", ReturnSet::ERROR_VALIDATION);
		}
		if (isset($_POST['BookingCab']))
		{
			try
			{
				$tripId				 = $tripId;
				$vehicleId			 = $_POST['BookingCab']['bcb_cab_id'];
				$driverId			 = $_POST['BookingCab']['bcb_driver_id'];
				$drvcontactNumber	 = $_POST['BookingCab']['bcb_driver_phone'];
				if ($drvcontactNumber == "")
				{
					$model->addError('bcb_cab_id', json_encode("Driver contact number is missing."));
					goto renderPage;
				}
				if ($vehicleId == "")
				{
					$model->addError('bcb_cab_id', json_encode("Cab is mandatory."));
					goto renderPage;
				}
				if ($driverId == "")
				{
					$model->addError('bcb_driver_id', json_encode("Driver is mandatory."));
					goto renderPage;
				}

				$transaction = DBUtil::beginTransaction();
				$result		 = BookingCab::checkCabDriverBeforeAssignment($tripId, $vehicleId, $driverId, $drvcontactNumber);
				$tripModel	 = $result[0];
				if (empty($tripModel))
				{
					DBUtil::rollbackTransaction($transaction);
					$model->addError('bcb_cab_id', json_encode($tripModel->getErrors()));
					goto renderPage;
				}
				$cabType				 = $result[1];
				$tripModel->chk_user_msg = [0, 1];  // sms for user and driver
				Vehicles::approveVehicleStatus($vehicleId);
				$success				 = $tripModel->assignCabDriver($vehicleId, $driverId, $cabType, UserInfo::getInstance());
				if (!$success)
				{
					DBUtil::rollbackTransaction($transaction);
					$model->addError('bcb_cab_id', json_encode($tripModel->getErrors()));
					goto renderPage;
				}
				DBUtil::commitTransaction($transaction);
				$this->redirect("list?status=allocated");
				
			}
			catch (Exception $ex)
			{
				if ($transaction != '')
				{
					DBUtil::rollbackTransaction($transaction);
				}
				$model->addError('bcb_cab_id', json_encode($ex->getMessage()));
			}
		}

		renderPage:
		$cabList	 = Vehicles::getCabListByVendor($vndId, '');
		$res		 = \Beans\common\Cab::getList($cabList);
		$response	 = Filter::removeNull($res);
		$arrJSON	 = array();
		foreach ($response as $key => $val)
		{
			$arrJSON[] = array("id" => $val->id, "text" => $val->number . " (" . $val->category->allowedModels[0]->make . " " . $val->category->allowedModels[0]->model . ")");
		}
		$cabs = CJSON::encode($arrJSON);

		$drvData	 = Drivers::getAllLstByVendor($vndId, '');
		$drvList	 = \Beans\Driver::getList($drvData);
		$response1	 = Filter::removeNull($drvList);
		$arrJSON1	 = array();
		foreach ($response1 as $key => $val)
		{
			$arrJSON1[] = array("id" => $val->id, "text" => $val->firstName . " " . $val->lastName);
		}
		$drivers = CJSON::encode($arrJSON1);
		$this->render('assigncabform', ['model' => $model, 'cabs' => $cabs, 'drivers' => $drivers]);
	}

}
