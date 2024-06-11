<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class DriverController extends BaseController
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
		$this->onRest('req.get.getlist.render', function () {
			return $this->renderJSON($this->getlist());
		});
		$this->onRest('req.get.getAssignedList.render', function () {
			return $this->renderJSON($this->getAssignedList());
		});
		$this->onRest('req.get.getAssignedDutyDetail.render', function () {
			return $this->renderJSON($this->getAssignedDutyDetail());
		});

		$this->onRest('req.post.addBookingComments.render', function () {
			return $this->renderJSON($this->addBookingComments());
		});
		$this->onRest('req.get.getProfile.render', function () {
			return $this->renderJSON($this->getProfile());
		});
		$this->onRest('req.post.getCommentList.render', function () {
			return $this->renderJSON($this->getCommentList());
		});
		$this->onRest('req.post.bonusHistory.render', function () {
			return $this->renderJSON($this->bonusHistory());
		});
		$this->onRest('req.post.uploadVhcVerifyImg.render', function () {
			return $this->renderJSON($this->uploadVhcVerifyImg());
		});
		$this->onRest('req.post.checkLicense.render', function () {
			return $this->renderJSON($this->checkLicense());
		});
		$this->onRest('req.post.addBasicInfo.render', function () {
			return $this->renderJSON($this->addBasicInfo());
		});

		$this->onRest('req.post.showDetails.render', function () {
			return $this->renderJSON($this->showDetails());
		});
		$this->onRest('req.post.updateProfileInfo.render', function () {
			return $this->renderJSON($this->updateProfileInfo());
		});
		$this->onRest('req.post.uploadDoc.render', function () {
			return $this->renderJSON($this->uploadDoc());
		});
		$this->onRest('req.post.removeDriver.render', function () {
			return $this->renderJSON($this->removeDriver());
		});
		$this->onRest('req.post.accountBonus.render', function () {
			return $this->renderJSON($this->accountBonus());
		});
		$this->onRest('req.post.redemBonus.render', function () {
			return $this->renderJSON($this->redemBonus());
		});
		$this->onRest('req.post.ratingCustomer.render', function () {
			return $this->renderJSON($this->ratingCustomer());
		});
	}

	public function getlist()
	{

		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->getParam('search_txt', '');
		try
		{
			$drvData = Drivers::getAllLstByVendor($this->getVendorId(), trim($data));

			$drvList	 = \Beans\Driver::getList($drvData);
			$response	 = Filter::removeNull($drvList);
			if (!$response)
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function getAssignedList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$drvId = $this->getDriverId(false);

			if (!$drvId)
			{
				throw new Exception(json_encode("No driver found in your DCO profile"), ReturnSet::ERROR_VALIDATION);
			}

			$returnSet = BookingSub::populateAssignedListForDriver($drvId);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function getAssignedDutyDetail()
	{
		$requestData = Yii::app()->request->rawBody;
		$returnSet	 = new ReturnSet();
		try
		{
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj = CJSON::decode($requestData, false);

			$bkgId	 = $reqObj->bkgId;
			$drvId	 = $this->getDriverId(false);

			if (!$drvId)
			{
				throw new Exception(json_encode("No driver found in your DCO profile"), ReturnSet::ERROR_VALIDATION);
			}
			$isDriver = BookingCab::checkDriverBookingRelation($bkgId, $drvId);

			if (!$isDriver)
			{
				throw new Exception("You are not authorized to view the booking details.", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}
			$objBooking = \Beans\Booking::setDataById($bkgId);
			$returnSet->setStatus(true);
			$returnSet->setData($objBooking);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function getProfile()
	{

		$returnSet = new ReturnSet();
		try
		{
			$drvId	 = $this->getDriverId();
			$drvData = Drivers::getStatusDetailbyid($drvId);

			//$obj		 = new \Beans\Driver();
			$obj		 = \Beans\Driver::setByStatusByData($drvData);
			$response	 = Filter::removeNull($obj);
			if (!$response)
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function addBookingComments()
	{

		$returnSet = new ReturnSet();
		try
		{
			$requestData = Yii::app()->request->rawBody;
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj		 = CJSON::decode($requestData, false);
			$jsonMapper	 = new JsonMapper();
			/** @var \Beans\booking\DriverComment() $obj */
			$obj		 = $jsonMapper->map($reqObj, new Beans\booking\DriverComment());

			$bkgId = $obj->booking->id;
			if (!$bkgId)
			{
				throw new Exception(json_encode("No booking provided"), ReturnSet::ERROR_VALIDATION);
			}
			$drvId		 = $this->getDriverId();
			$isSuccess	 = \BookingCab::checkDriverBookingRelation($bkgId, $drvId);
			if (!$isSuccess)
			{
				throw new Exception(json_encode("The booking is not assigned to you"), ReturnSet::ERROR_VALIDATION);
			}

			if (trim($obj->remarks) != '')
			{
				$userInfo	 = UserInfo::getInstance();
				$platform	 = AppTokens::Platform_DCO;
				/** @var \BookingTrackLog $btlModel */
				$btlModel	 = $obj->setTrackLogModel($userInfo, $platform);
				$success	 = $btlModel->saveData();
				if ($success)
				{
					$eventId				 = BookingLog::REMARKS_ADDED;
					$desc					 = $obj->remarks;
					$remarks				 = $desc;
					$params['blg_driver_id'] = (int) $drvId;
					$success				 = BookingLog::model()->createLog($bkgId, $remarks, $userInfo, $eventId, null, $params);
				}
			}
			$dataReader = \BookingLog::getCommentTraceByDriverId($drvId, $eventId, $bkgId);

			/** @var \Beans\booking\DriverComment() $obj */
			$data = $obj->setList($dataReader);
			$returnSet->setData($data);
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
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
			$requestData = Yii::app()->request->rawBody;
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj		 = CJSON::decode($requestData, false);
			$jsonMapper	 = new JsonMapper();
			/** @var \Beans\booking\DriverComment() $obj */
			$obj		 = $jsonMapper->map($reqObj, new Beans\booking\DriverComment());

			$bkgId = (int) $obj->booking->id;

			$drvId = $this->getDriverId(false);
			if (!$drvId)
			{
				throw new Exception(json_encode("No driver found in your DCO profile"), ReturnSet::ERROR_VALIDATION);
			}
			$isSuccess = BookingCab::checkDriverBookingRelation($bkgId, $drvId);
			if (!$isSuccess)
			{
				throw new Exception(json_encode("Not authorised to proceed"), ReturnSet::ERROR_VALIDATION);
			}
			$eventId	 = BookingLog::REMARKS_ADDED;
			$dataReader	 = BookingLog::getCommentTraceByDriverId($drvId, $eventId, $bkgId);
			if ($dataReader->getRowCount() == 0)
			{
				throw new Exception("No remarks to show", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			/** @var \Beans\booking\DriverComment() $obj */
			$data = $obj->setList($dataReader);
			$returnSet->setData($data);
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function bonusHistory()
	{
		$returnSet = new ReturnSet();
		try
		{
			$requestData = Yii::app()->request->rawBody;
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}

			$drvId = $this->getDriverId();
			if (!$drvId)
			{
				throw new Exception("Invalid Driver", ReturnSet::ERROR_UNAUTHORISED);
			}
			$reqObj		 = CJSON::decode($requestData, false);
			$jsonMapper	 = new JsonMapper();

			/** @var \Beans\accounts\Request $reqData */
			$reqData = $jsonMapper->map($reqObj, new \Beans\accounts\Request())->getRequest();

			$dateRangeObj	 = $reqData->dateRange;
			$pageRef		 = $reqData->pageRef;

			$tripArr = AccountTransDetails::driverBonusList($drvId, $dateRangeObj, $pageRef);

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
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function uploadVhcVerifyImg()
	{

		$returnSet = new ReturnSet();
		try
		{
			$userInfo		 = UserInfo::getInstance();
			$uploadedFile	 = CUploadedFile::getInstanceByName('img');
			if (empty($uploadedFile))
			{
				throw new Exception(json_encode("No Image Found."), ReturnSet::ERROR_VALIDATION);
			}

			$rawData		 = Yii::app()->request->getParam('data');
			$data			 = json_decode($rawData);
			$bookingId		 = $data->bkgId;
			$vehicleId		 = $data->cabId;
			$type			 = $data->type;
			$typeArr		 = array('front' => 8, 'back' => 9);
			$package_type	 = $typeArr[$type];
			$app_type		 = 5;
			$returnFile		 = VehicleDocs::model()->savePackageImage($uploadedFile, $package_type, $vehicleId);
			if (!$returnFile)
			{
				throw new Exception(json_encode("Error in image upload"), ReturnSet::ERROR_VALIDATION);
			}
			VehicleStats::addRelatedBooking($vehicleId, $bookingId);
			//update Vehicle verify flag
			VehicleStats::updateVerifyFlag($vehicleId);
			$systemChkSum = md5_file($uploadedFile->getTempName());

			$success = BookingPayDocs::model()->uploadCarVerifyImageV1($package_type, $app_type, $bookingId, $returnFile, $systemChkSum);
			if ($success)
			{
				$userInfo					 = UserInfo::getInstance();
				$userInfo->userType			 = UserInfo::TYPE_DRIVER;
				$eventId					 = BookingLog::CAB_VERIFIED;
				$desc						 = "Document uploaded for verification";
				$driverId					 = $this->getDriverId();
				$params['blg_driver_id']	 = $driverId;
				$params['blg_entity_type']	 = $userInfo->userType;
				BookingLog::model()->createLog($bookingId, $desc, $userInfo, $eventId, false, $params);
				$res1						 = new Beans\common\Cab;
				$res1->setDocData($data, $systemChkSum);
				$returnSet->setData($res1);
				$returnSet->setStatus(true);
			}
		}
		catch (Exception $ex)
		{

			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function checkLicense()
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$data = Yii::app()->request->rawBody;

			Logger::trace("<===Request===>" . $data);
			if (!$data)
			{
				throw new Exception("Invalid Data.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj		 = CJSON::decode($data, false);
			$licenseNumber	 = $jsonObj->license;

			$data1			 = Contact::model()->getLicenseCtt($licenseNumber);
			$cttId			 = $data1->getData();
			$drvContactData	 = ContactProfile::getDriverData($cttId);
			$drvId			 = $drvContactData["entityId"];

			if ($drvId > 0)
			{

				if (empty($cttId))
				{
					throw new \Exception("Invalid User : ", \ReturnSet::ERROR_INVALID_DATA);
				}
				if ($drvId > 0)
				{
					$drvObj = new \Beans\Driver();
					$drvObj->setData($drvId, $cttId);
				}
				$response = Filter::removeNull($drvObj);
				$returnSet->setData($response);
			}
			else
			{
				$returnSet->setMessage("No driver found");
			}
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{

			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function addBasicInfo()
	{
		$returnSet = new ReturnSet();
		try
		{
			$requestData = Yii::app()->request->rawBody;
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj		 = CJSON::decode($requestData, false);
			$vendorId	 = $this->getVendorId();
			$vndModel	 = Vendors::model()->findByPk($vendorId);
			if (in_array($vndModel->vnd_active, [0, 2, 3, 4]))
			{
				$activeList	 = $vndModel->vendorStatus;
				$status		 = $activeList[$vndModel->vnd_active];

				throw new Exception("Your account is in $status status.", ReturnSet::ERROR_UNAUTHORISED);
			}
			$jsonMapper	 = new JsonMapper();
			$beans		 = new \Beans\contact\Person();
			$obj		 = $jsonMapper->map($reqObj, $beans);

			$contactModel = $obj->getData($reqObj);

			/** @var Beans\contact\Person $obj */
			$transaction = DBUtil::beginTransaction();
			$returnSet	 = Drivers::addByContact($contactModel, $contactModel->ctt_driver);
			if ($returnSet->getStatus() == false)
			{
				throw new Exception("Unable to add driver.", ReturnSet::ERROR_INVALID_DATA);
			}


			$returnData	 = $returnSet->getData();
			$driverId	 = $returnData->id;

			$data		 = ['vendor' => $vendorId, 'driver' => $driverId];
			$resLinked	 = VendorDriver::model()->checkAndSave($data);
			if (!$resLinked)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			VendorStats::model()->updateCountDrivers($vendorId);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function showDetails()
	{
		$returnSet = new ReturnSet();
		try
		{
			$requestData = Yii::app()->request->rawBody;
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj		 = CJSON::decode($requestData, false);
			$driverId	 = $reqObj->id;
			$drvObj		 = new \Beans\Driver();
			$drvObj->setProfile($driverId);
			$returnSet->setStatus(true);
			$returnSet->setData($drvObj);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function updateProfileInfo()
	{
		$returnSet = new ReturnSet();
		try
		{

			$requestData = Yii::app()->request->rawBody;
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj			 = CJSON::decode($requestData, false);
			$driverId		 = $reqObj->driver->id;
			$contactId		 = ContactProfile::getByDrvId($driverId);
			$contactRequest	 = $reqObj->contact;
			$obj			 = new \Beans\contact\Person();
			$cttModel		 = $obj->setdata($reqObj->contact, $contactId);

			//$cttModel->save();
			$driverModel			 = Drivers::model()->findByPk($driverId);
			$driverModel->drvContact = $cttModel;
			//check phone email and license validation

			$validate				 = Contact::phoneEmailValidation($cttModel);
			$driverModel->drv_dob	 = $reqObj->driver->birthDate;
			$driverModel->drv_zip	 = $reqObj->contact->address->pincode;
			$success				 = Drivers::addDetailsInfo($driverModel);
			if ($success)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Driver data updated successfully");
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function uploadDoc()
	{
		$returnSet = new ReturnSet();
		try
		{
			$vendorId	 = $this->getVendorId();
			$vndModel	 = Vendors::model()->findByPk($vendorId);
			if (in_array($vndModel->vnd_active, [0, 2, 3, 4]))
			{
				$activeList	 = $vndModel->vendorStatus;
				$status		 = $activeList[$vndModel->vnd_active];

				throw new Exception("Your account is in $status status.", ReturnSet::ERROR_UNAUTHORISED);
			}

			$requestData = Yii::app()->request->getParam('data');
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj	 = CJSON::decode($requestData, false);
			$driverId	 = $jsonObj->driver->id;
			$dvrResponse = new \Beans\Driver();
			$model		 = $dvrResponse->setDocumentData($jsonObj);
			if (!empty($model))
			{
				$returnSet	 = Document::model()->updateDriverDocument($model, $_FILES['photo']['name'], $_FILES['photo']['tmp_name']);
				$driverId	 = $model->id;
			}
			if (!$returnSet->isSuccess())
			{
				throw new Exception("File not updated", ReturnSet::ERROR_INVALID_DATA);
			}
			else
			{
				$returnSet->setMessage("Document added successfully");
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function removeDriver()
	{
		$returnSet = new ReturnSet();
		try
		{
			$vndId		 = $this->getVendorId(false);
			$requestData = Yii::app()->request->rawBody;
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj		 = CJSON::decode($requestData, false);
			$driverId	 = $reqObj->id;
			if ($driverId > 0)
			{
				$vdrv_id = VendorDriver::getVndDrvId($driverId, $vndId);
				$success = VendorDriver::unlinkByVendorDriverId($vdrv_id);
				if ($success == true)
				{
					$returnSet->setStatus(true);
					$returnSet->setMessage("Driver unlink successfully");
				}
				else
				{
					throw new Exception("No driver found.", ReturnSet::ERROR_INVALID_DATA);
				}
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function accountBonus()
	{
		$returnSet	 = new ReturnSet();
		$driverId	 = $this->getDriverId();
		if ($driverId < 1)
		{
			throw new Exception("No driver found.", ReturnSet::ERROR_INVALID_DATA);
		}
		$requestData = Yii::app()->request->rawBody;
		$wholeData	 = CJSON::decode($requestData, true);
		if (!empty($wholeData))
		{
			$date1		 = $wholeData['date1'];
			$date2		 = $wholeData['date2'];
			$newDate1	 = ($date1 != '') ? date('Y-m-d', strtotime($date1)) : date('Y-m-d', strtotime("-30 days"));
			$newDate2	 = ($date2 != '') ? date('Y-m-d', strtotime($date2)) : date('Y-m-d');
		}
		try
		{
			$tripArr = AccountTransDetails::drvTransactionList($driverId, $newDate1, $newDate2);
			if (!empty($tripArr))
			{
				$showModel = new \Beans\Driver();
				$showModel->getTransactionList($tripArr);

				$response = Filter::removeNull($showModel);
				$returnSet->setStatus(true);
				$returnSet->setData($response);
			}
			else
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("No record found");
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function redemBonus()
	{
		$returnSet = new ReturnSet();
		try
		{
			$drvId		 = $this->getDriverId(false);
			$requestData = Yii::app()->request->rawBody;
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$dmodel				 = Drivers::model()->findByPk($drvId);
			$drv_account_number	 = $dmodel->drvContact->ctt_bank_account_no;
			if ($drv_account_number == NULL)
			{
				throw new Exception("Please update your bank details.", ReturnSet::ERROR_INVALID_DATA);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function ratingCustomer()
	{
		$returnSet = new ReturnSet();
		try
		{
			$drvId		 = $this->getDriverId();
			$success == false;
			$requestData = Yii::app()->request->rawBody;
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$data	 = CJSON::decode($requestData, true);
			$res	 = Ratings::driverGivenRating($data);
			$success = $res;
			if ($success == true)
			{
				$msg = "Your Rating is added";
				$returnSet->setStatus($success);
				$returnSet->setMessage($msg);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}
}
