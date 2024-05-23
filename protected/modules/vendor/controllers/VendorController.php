<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class VendorController extends BaseController
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
		$this->onRest('req.cors.access.control.allow.methods', function () {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function ($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array( '/joining',  "/covidInstructions");
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.get.version_check.render', function () {
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{
				$vendorId		 = Yii::app()->user->getId();
				$versionCheck	 = VendorAgreement::model()->checkVersionStatusByVndId($vendorId);
				if ($versionCheck > 0)
				{
					$success = true;
					$error	 = [];
				}
				else
				{
					$success = false;
					$error	 = 'Version not matched';
				}
			}
			else
			{
				$success = false;
				$error	 = 'Vendor Unauthorised';
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'error'		 => $error
				)
			]);
		});

		$this->onRest("req.get.addDriver.render", function () {
			return $this->renderJSON($this->addDriver());
		});
		$this->onRest("req.post.addRate.render", function () {
			return $this->renderJSON($this->addRate());
		});
		$this->onRest("req.get.showPreferredRate.render", function () {
			return $this->renderJSON($this->showPreferredRate());
		});
		$this->onRest("req.get.showMatrix.render", function () {
			return $this->renderJson($this->showMatrix());
		});
		$this->onRest("req.get.covidInstructions.render", function () {
			return $this->renderJson($this->covidInstructions());
		});
		$this->onRest("req.post.addBoost.render", function () {
			return $this->renderJson($this->addBoost());
		});
		$this->onRest("req.post.requestPackages.render", function () {
			return $this->renderJson($this->requestPackages());
		});
		$this->onRest("req.get.showSendPackages.render", function () {

			return $this->renderJson($this->sendPackagesList());
		});
		$this->onRest("req.post.receiveConfirmation.render", function () {

			return $this->renderJson($this->receiveConfirmation());
		});
		$this->onRest("req.post.uploadPckImages.render", function () {

			return $this->renderJson($this->uploadPckImages());
		});

		/*
		 * @deprecated since version 12-01-2022
		 * new services notificationLogv2
		 */
		$this->onRest('req.post.notificationLog.render', function () {
			return $this->renderJSON($this->notificationLog());
		});
		$this->onRest('req.post.notificationLogv2.render', function () {
			return $this->renderJSON($this->notifyLog());
		});

		$this->onRest('req.post.updateNotificationLog.render', function () {
			return $this->renderJSON($this->updateNotificationLog());
		});

		$this->onRest('req.post.processReadNotification.render', function () {
			return $this->renderJSON($this->processReadNotification());
		});
		$this->onRest('req.post.updateActionValue.render', function () {
			return $this->renderJSON($this->updateActionValue());
		});

		$this->onRest('req.post.updateOperatingServices.render', function () {
			return $this->renderJSON($this->updateOperatingServices());
		});

		$this->onRest('req.post.OperatingServices.render', function () {
			return $this->renderJSON($this->OperatingServices());
		});
		$this->onRest('req.post.gozoNowStatus.render', function () {
			return $this->renderJSON($this->gNowStatus());
		});
		$this->onRest('req.get.coinList.render', function () {
			return $this->renderJSON($this->coinList());
		});
		$this->onRest('req.post.totalVendorCoin.render', function () {
			return $this->renderJSON($this->totalVendorCoin());
		});
		$this->onRest('req.post.redeemVendorCoin.render', function () {
			return $this->renderJSON($this->redeemVendorCoin());
		});
		$this->onRest('req.post.adjustLockedAmount.render', function () {
			return $this->renderJSON($this->adjustLockedAmount());
		});
	}

	/**
	 * 
	 * @param type $param
	 * @return type
	 */
	public function addDriver()
	{
		$returnSet = new ReturnSet();
		try
		{
			$requestInstance = Yii::app()->request;
			$receivedData	 = json_decode($requestInstance->rawBody);

			//$receivedData = json_decode('{"vndId": 145, "drvContactId":103031,"firstName":"AB","lastName":"CD"}');
			if (empty($receivedData))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			};

			$jsonMapper	 = new JsonMapper();
			$driverStub	 = new Stub\common\Driver();

			/** @var JsonMapper $obj */
			$obj		 = $jsonMapper->map($receivedData, $driverStub);
			$driverModel = $obj->getModel();
			$returnSet	 = $driverModel->handleDriver($receivedData->vndId);
		}
		catch (Exception $e)
		{
			$errors = $e->getMessage();
			Logger::create("Driver details not saved. -->" . $e->getMessage(), CLogger::LEVEL_ERROR);
		}
		return $returnSet;
	}

	public function addRate()
	{
		$returnSet = new ReturnSet();
		try
		{

			$data = Yii::app()->request->rawBody;
			if (empty($data))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			};

			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);

			$obj	 = $jsonMapper->map($jsonObj, new \Stub\common\PreferredRate);
			$model	 = $obj->getModel();

			$model->user_type	 = 2;
			$vendorId			 = UserInfo::getEntityId();
			$model->entity_id	 = $vendorId;
			$model->created_date = new CDbExpression('NOW()');
			if (!$model->save())
			{
				throw new Exception("Preferred rate is not added", ReturnSet::ERROR_INVALID_DATA);
			}
			$returnSet->setStatus(true);
			$returnSet->setMessage('Preferred rate added');
		}
		catch (Exception $e)
		{
			throw new Exception("Data not saving", ReturnSet::ERROR_INVALID_DATA);
		}
		return $returnSet;
	}

	public function showPreferredRate()
	{
		$returnSet	 = new ReturnSet();
		$type		 = Yii::app()->request->getParam('type', '');
		$text		 = Yii::app()->request->getParam('text', '');
		if ($type == "")
		{
			throw new Exception("Invalid Param", ReturnSet::ERROR_INVALID_DATA);
		}
		try
		{
			$vendorId		 = UserInfo::getEntityId();
			$rateType		 = ($type == 'city' ? 1 : 2);
			$priceListModel	 = PreferredRate::model()->getDetails($vendorId, $rateType, $text);

			if (!empty($priceListModel))
			{
				$showModel	 = new \Stub\common\PreferredRate;
				$showModel->setModel($priceListModel);
				$response	 = Filter::removeNull($showModel);
				$returnSet->setData($response);
				$returnSet->setStatus(true);
			}
			else
			{
				$returnSet->setMessage("No record found");
			}
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet->setMessage("No record found");
		}
		return $returnSet;
	}

	public function showMatrix()
	{
		$returnSet	 = new ReturnSet();
		$vendorId	 = UserInfo::getEntityId();
		try
		{
			$matrixData = VendorStats::fetchMetric($vendorId);

			if (!empty($matrixData))
			{
				$showModel = new \Stub\vendor\InfoResponse();
				$showModel->getMatrix($matrixData);

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
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet->setMessage("No record found");
		}
		return $returnSet;
	}

	public function covidInstructions()
	{
		$returnSet = new ReturnSet();
		$returnSet->setStatus(false);
		$returnSet->setData(["dataList" => []]);
		if (Yii::app()->params['covidFlag'] == 1)
		{
			$returnSet->setStatus(true);
			$data = ["dataList" => Filter::getCovidInstructions(1)];
			$returnSet->setData($data);
		}
		return $returnSet;
	}

	public function addBoost()
	{
		$returnSet	 = new ReturnSet();
		$vendorId	 = UserInfo::getEntityId();
		try
		{
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);

			/** @var \Stub\vendor\BootRequest $obj */
			$obj	 = $jsonMapper->map($jsonObj, new Stub\vendor\BoostRequest());
			Logger::profile("Request Mapped");
			/** @var VendorBoost $model */
			$model	 = $obj->getModel();

			$model->vbt_vendor_id = $vendorId;

			$model->scenario = 'addBoost';
			$errors			 = CActiveForm::validate($model);

			if ($errors != "[]")
			{
				$errors = $model->getErrors();
				throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
			}
			if ($model->save())
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Gozo Boost added successfully");
			}
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet->setMessage("No record found");
		}

		return $returnSet;
	}

	public function requestPackages()
	{
		$returnSet	 = new ReturnSet();
		$vendorId	 = UserInfo::getEntityId();
		try
		{
			$data = Yii::app()->request->rawBody;
			logger::trace("Request" . $data);
			if (!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);

			/** @var \Stub\vendor\PackageRequest $obj */
			$obj	 = $jsonMapper->map($jsonObj, new Stub\vendor\PackageRequest());
			/** @var VendorBoost $model */
			$model	 = $obj->getModel();

			$car	 = $model->vpk_vhc_id;
			$type	 = $model->vpk_type;

			$showUniqueCar = VendorPackages::checkExistence($car, $type, $vendorId);

			if (empty($showUniqueCar))
			{
				// $returnSet->setStatus(false);
				//$returnSet->setMessage("Package request already added ");
				throw new Exception("Package request already added", ReturnSet::ERROR_FAILED);
			}
			else
			{

				$carStr				 = implode(",", $showUniqueCar);
				$model->vpk_vhc_id	 = $carStr;

				$model->vpk_vnd_id = $vendorId;

				$model->scenario = 'requestPackages';
				$errors			 = CActiveForm::validate($model);

				if ($errors != "[]")
				{
					$errors = $model->getErrors();
					throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
				}
				if ($model->save())
				{
					$returnSet->setStatus(true);
					$returnSet->setMessage("Gozo Packages requested successfully");
				}
			}
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
			// $errorMsg	 = "Unable to add package request";
			//$returnSet->setMessage($errorMsg);
		}

		return $returnSet;
	}

	public function sendPackagesList()
	{
		$returnSet	 = new ReturnSet();
		$vendorId	 = UserInfo::getEntityId();
		try
		{
			$sendData = VendorPackages::sendList($vendorId);
			if (!empty($sendData))
			{

				$response	 = new \Stub\vendor\PackageResponse();
				$responsedt	 = $response->getSendList($sendData);

				$response = Filter::removeNull($responsedt);

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
			$returnSet->setStatus(false);
			$returnSet->setMessage("No record found");
		}
		return $returnSet;
	}

	public function receiveConfirmation()
	{

		$returnSet	 = new ReturnSet();
		$vendorId	 = UserInfo::getEntityId();
		try
		{

			$data = Yii::app()->request->rawBody;
			logger::trace("Request" . $data);
			if (!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);

			/** @var \Stub\vendor\PackageRequest $obj */
			$obj	 = $jsonMapper->map($jsonObj, new Stub\vendor\PackageRequest($model));
			/** @var PackageRequest $model */
			$model	 = $obj->getReceive();
			#print_r($model);
			if ($model->update())
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Gozo Package received confirmation done");
			}
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet->setMessage("No record found");
		}
		return $returnSet;
	}

	public function uploadPckImages()
	{
		$returnSet	 = new ReturnSet();
		$vendorId	 = UserInfo::getEntityId();
		try
		{
			CUploadedFile::getInstanceByName('img1');
			$wholeData1		 = Yii::app()->request->getParam('data');
			$wholeData		 = CJSON::decode($wholeData1, true);
			$vehicleId		 = $wholeData['cabId'];
			$uploadedFile	 = CUploadedFile::getInstanceByName('img1');
			$type			 = $wholeData['type'];
			$typeArr		 = array('front' => 8, 'back' => 9, 'left' => 10, 'right' => 11);
			$package_type	 = $typeArr[$type];
			//$bookingId  = $wholeData['bookingId'];

			$success = VehicleDocs::model()->uploadPackages($uploadedFile, $package_type, $vehicleId);

			if ($success)
			{
				$boost_enabled_status = 2;
				VehicleStats::modifyBoostStatus($boost_enabled_status, $vehicleId);
				//VehicleStats::addRelatedBooking($vehicleId,$bookingId);


				$returnSet->setStatus(true);
				$returnSet->setMessage("Gozo Packages uploaded successfully");
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("No record found");
			}
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet->setMessage("No record found");
		}
		return $returnSet;
	}

	public static function notificationLog()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$jsonObj	 = CJSON::decode($data, false);
		try
		{
			$vndId = UserInfo::getEntityId();
			if (empty($vndId))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}

			$pageSize	 = ($jsonObj->pageSize > 0) ? $jsonObj->pageSize : 20;
			$pageCount	 = ($jsonObj->currentPage > 0) ? $jsonObj->currentPage : 1;

			//$ntlLogList = NotificationLog::getDetails($vndId, 2);

			$totalCount	 = NotificationLog::getDetails($vndId, 2, true);
			$ntlLogList	 = NotificationLog::getDetails($vndId, 2, false, $pageSize, $pageCount);

			$ntlList	 = new \Stub\common\Notification();
			$ntlList->getList($ntlLogList, $totalCount, $pageSize, $pageCount);
			$response	 = Filter::removeNull($ntlList);
			if (!$response)
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	public static function notifyLog()
	{
		$returnSet = new ReturnSet();

		$data	 = Yii::app()->request->rawBody;
		$jsonObj = CJSON::decode($data, false);
		try
		{
			$vndId = UserInfo::getEntityId();
			if (empty($vndId))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			$id		 = $jsonObj->id;
			$isNew	 = $jsonObj->type;

			$ntlLogList = NotificationLog::getList($vndId, 2, $id, $isNew);

			$ntlList	 = new \Stub\common\Notification();
			$ntlList->getLogList($ntlLogList);
			$response	 = Filter::removeNull($ntlList);
			//$message			 = "List";
			if (!$response)
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$returnSet->setStatus(true);
			//$returnSet->setMessage($message);
			$returnSet->setData($response);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	public function updateNotificationLog()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$data		 = Yii::app()->request->rawBody;
		try
		{
			if ($data)
			{
				$jsonObj	 = CJSON::decode($data, true);
				$resultData	 = NotificationLog::updateLogById($jsonObj);

				if ($resultData['success'] == false)
				{
					throw new Exception($resultData['message'], ReturnSet::ERROR_INVALID_DATA);
				}
				$returnSet->setStatus(true);
				$returnSet->setMessage("Data Updated");
				Logger::create("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("NO DATA FOUND.", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		catch (Exception $e)
		{
			$returnSet = $returnSet->setException($e);
			Logger::exception($e);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @return type
	 * @throws Exception
	 */
	public function processReadNotification()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$data		 = Yii::app()->request->rawBody;
		try
		{
			if ($data)
			{
				$vendorId	 = UserInfo::getEntityId();
				$success	 = false;
				$ntlDataArr	 = CJSON::decode($data, true);
				$ntlId		 = $ntlDataArr['id'];
				$batchId	 = $ntlDataArr['batchId'];
				if (isset($ntlDataArr['batchId']))
				{
					$ntlId = NotificationLog::getIdByBatchIdVendorId($batchId, $vendorId);
					if ($ntlId)
					{
						$ntlDataArr ['id'] = $ntlId;
					}
					else
					{
						$success = false;
						goto result;
					}
				}

				$resultData = NotificationLog::updateReadNotification($ntlDataArr);
				if ($resultData['success'] == false)
				{
					throw new Exception($resultData['message'], ReturnSet::ERROR_INVALID_DATA);
				}
				$success = $resultData['success'];
				result:
				$returnSet->setStatus($success);
				$returnSet->setMessage("Data Updated");
				Logger::create("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
			}
			else
			{
				$returnSet->setStatus($success);
				$returnSet->setErrors("NO DATA FOUND.", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		catch (Exception $e)
		{
			$returnSet = $returnSet->setException($e);
			Logger::exception($e);
		}
		return $returnSet;
	}

	public function updateActionValue()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$data		 = Yii::app()->request->rawBody;
		try
		{
			if ($data)
			{
				$jsonObj	 = CJSON::decode($data, true);
				$resultData	 = NotificationLog::processNotificationValue($jsonObj);

				if ($resultData['success'] == false)
				{
					throw new Exception($resultData['message'], ReturnSet::ERROR_INVALID_DATA);
				}
				$returnSet->setStatus(true);
				$returnSet->setMessage("Data Updated");
				Logger::create("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("NO DATA FOUND.", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		catch (Exception $e)
		{
			$returnSet = $returnSet->setException($e);
			Logger::exception($e);
		}
		return $returnSet;
	}

	public static function updateOperatingServices()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$jsonObj	 = CJSON::decode($data, false);
		try
		{
			$vndId = UserInfo::getEntityId();
			if (empty($vndId))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			$serviceTypeDate = new \Stub\common\ServiceType();
			$serviceTypeDate->setData($jsonObj);
			$response		 = Filter::removeNull($serviceTypeDate);
			if (!$response)
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$model					 = VendorPref::model()->getbyVendorId($vndId);
			$jsonRequestedOld		 = json_decode($model->vnp_vnd_requested_services);
			$jsonApprovedServices	 = json_decode($model->vnp_admin_approved_services);
			$serviceRequestFlag		 = 0;
			if (($jsonRequestedOld->vnp_oneway != $jsonObj->vnp_oneway) && ($jsonRequestedOld->vnp_oneway < $jsonObj->vnp_oneway))
			{
				// create sr
				$serviceRequestFlag					 = 1;
				$jsonApprovedServices->vnp_oneway	 = "0";
				$model->vnp_admin_approved_services	 = json_encode($jsonApprovedServices);
			}
			else if ($jsonRequestedOld->vnp_oneway != $jsonObj->vnp_oneway)
			{
				$jsonApprovedServices->vnp_oneway	 = "0";
				$model->vnp_admin_approved_services	 = json_encode($jsonApprovedServices);
			}


			if (($jsonRequestedOld->vnp_round_trip != $jsonObj->vnp_round_trip) && ($jsonRequestedOld->vnp_round_trip < $jsonObj->vnp_round_trip))
			{
				$serviceRequestFlag						 = 1;
				$jsonApprovedServices->vnp_round_trip	 = "0";
				$model->vnp_admin_approved_services		 = json_encode($jsonApprovedServices);
			}
			else if ($jsonRequestedOld->vnp_round_trip != $jsonObj->vnp_round_trip)
			{
				$jsonApprovedServices->vnp_round_trip	 = "0";
				$model->vnp_admin_approved_services		 = json_encode($jsonApprovedServices);
			}

			if (($jsonRequestedOld->vnp_multi_trip != $jsonObj->vnp_multi_trip) && ($jsonRequestedOld->vnp_multi_trip < $jsonObj->vnp_multi_trip))
			{
				$serviceRequestFlag						 = 1;
				$jsonApprovedServices->vnp_multi_trip	 = "0";
				$model->vnp_admin_approved_services		 = json_encode($jsonApprovedServices);
			}
			else if ($jsonRequestedOld->vnp_multi_trip != $jsonObj->vnp_multi_trip)
			{
				$jsonApprovedServices->vnp_multi_trip	 = "0";
				$model->vnp_admin_approved_services		 = json_encode($jsonApprovedServices);
			}

			if (($jsonRequestedOld->vnp_airport != $jsonObj->vnp_airport) && ($jsonRequestedOld->vnp_airport < $jsonObj->vnp_airport))
			{
				$serviceRequestFlag					 = 1;
				$jsonApprovedServices->vnp_airport	 = "0";
				$model->vnp_admin_approved_services	 = json_encode($jsonApprovedServices);
			}
			else if ($jsonRequestedOld->vnp_airport != $jsonObj->vnp_airport)
			{
				$jsonApprovedServices->vnp_airport	 = "0";
				$model->vnp_admin_approved_services	 = json_encode($jsonApprovedServices);
			}



			if (($jsonRequestedOld->vnp_package != $jsonObj->vnp_package) && ($jsonRequestedOld->vnp_package < $jsonObj->vnp_package))
			{
				$serviceRequestFlag					 = 1; // create sr
				$jsonApprovedServices->vnp_package	 = "0";
				$model->vnp_admin_approved_services	 = json_encode($jsonApprovedServices);
			}
			else if ($jsonRequestedOld->vnp_package != $jsonObj->vnp_package)
			{
				$jsonApprovedServices->vnp_package	 = "0";
				$model->vnp_admin_approved_services	 = json_encode($jsonApprovedServices);
			}

			if (($jsonRequestedOld->vnp_flexxi != $jsonObj->vnp_flexxi) && ($jsonRequestedOld->vnp_flexxi < $jsonObj->vnp_flexxi))
			{
				$serviceRequestFlag					 = 1;
				$jsonApprovedServices->vnp_flexxi	 = "0";
				$model->vnp_admin_approved_services	 = json_encode($jsonApprovedServices);
			}
			else if ($jsonRequestedOld->vnp_flexxi != $jsonObj->vnp_flexxi)
			{
				$jsonApprovedServices->vnp_flexxi	 = "0";
				$model->vnp_admin_approved_services	 = json_encode($jsonApprovedServices);
			}

			if (($jsonRequestedOld->vnp_daily_rental != $jsonObj->vnp_daily_rental) && ($jsonRequestedOld->vnp_daily_rental < $jsonObj->vnp_daily_rental))
			{
				$serviceRequestFlag						 = 1;
				$jsonApprovedServices->vnp_daily_rental	 = "0";
				$model->vnp_admin_approved_services		 = json_encode($jsonApprovedServices);
			}
			else if ($jsonRequestedOld->vnp_daily_rental != $jsonObj->vnp_daily_rental)
			{
				$jsonApprovedServices->vnp_daily_rental	 = "0";
				$model->vnp_admin_approved_services		 = json_encode($jsonApprovedServices);
			}

			if (($jsonRequestedOld->vnp_tempo_traveller != $jsonObj->vnp_tempo_traveller) && ($jsonRequestedOld->vnp_tempo_traveller < $jsonObj->vnp_tempo_traveller))
			{
				$serviceRequestFlag							 = 1;
				$jsonApprovedServices->vnp_tempo_traveller	 = "0";
				$model->vnp_admin_approved_services			 = json_encode($jsonApprovedServices);
			}
			else if ($jsonRequestedOld->vnp_tempo_traveller != $jsonObj->vnp_tempo_traveller)
			{
				$jsonApprovedServices->vnp_tempo_traveller	 = "0";
				$model->vnp_admin_approved_services			 = json_encode($jsonApprovedServices);
			}


			if (($jsonRequestedOld->vnp_lastmin_booking != $jsonObj->vnp_lastmin_booking) && ($jsonRequestedOld->vnp_lastmin_booking < $jsonObj->vnp_lastmin_booking))
			{
				$serviceRequestFlag							 = 1;
				$jsonApprovedServices->vnp_lastmin_booking	 = "0";
				$model->vnp_admin_approved_services			 = json_encode($jsonApprovedServices);
			}
			else if ($jsonRequestedOld->vnp_lastmin_booking != $jsonObj->vnp_lastmin_booking)
			{
				$jsonApprovedServices->vnp_lastmin_booking	 = "0";
				$model->vnp_admin_approved_services			 = json_encode($jsonApprovedServices);
			}

			if ($serviceRequestFlag == 1)
			{
				ServiceCallQueue::autoFURVendorUpdateService($vndId);
			}
			$model->vnp_vnd_requested_services = json_encode($response);
			if (!$model->save())
			{
				throw new Exception("Fail to save data", ReturnSet::ERROR_VALIDATION);
			}
			$returnSet->setStatus(true);
			$returnSet->setMessage("Date saved successfully");
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	public static function OperatingServices()
	{
		$returnSet = new ReturnSet();
		try
		{
			$vndId = UserInfo::getEntityId();
			if (empty($vndId))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			$model			 = VendorPref::model()->getbyVendorId($vndId);
			$serviceTypeDate = new \Stub\common\ServiceType();
			$serviceTypeDate->setData(json_decode($model->vnp_vnd_requested_services));
			$response		 = Filter::removeNull($serviceTypeDate);
			if (!$response)
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	public static function gNowStatus()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		 logger::trace("Request" . $data);
		$jsonObj	 = CJSON::decode($data, false);
		try
		{
			if (empty($data))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			$vndId = UserInfo::getEntityId();

			$gnowStat	 = $jsonObj->isGozoNow;
			$snoozeTime	 = $jsonObj->snoozeTime;

			$stat = VendorPref::updateGnowStatus($vndId, $gnowStat, $snoozeTime);
			if ($stat)
			{
				$message = "GozoNow flag status modified";
				$returnSet->setStatus(true);
				$returnSet->setMessage($message);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	public static function coinList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$vndId = UserInfo::getEntityId();
			if (empty($vndId))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
	}
			 $totalCoin		 = VendorCoins::totalCoin($vndId);
			$vendorCoinModel = VendorCoins::model()->findAll('vnc_vnd_id=:vndId', ['vndId' => $vndId]);
			if ($vendorCoinModel != '')
			{
				$response	 = new \Stub\vendor\VendorCoin();
				$responsedt	 = $response->getData($vendorCoinModel,$totalCoin);
				$data		 = Filter::removeNull($responsedt);

				$returnSet->setStatus(true);
				$returnSet->setData($data);
}
			else
			{
				$returnSet->setStatus(false);
				$error = "No records found";
				$returnSet->setMessage($error);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	public static function totalVendorCoin()
	{
		$returnSet = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		logger::trace("Request" . $data);
		$jsonObj	 = CJSON::decode($data, false);
		try
		{
			$vndId = UserInfo::getEntityId();
			if (empty($vndId))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
}
			$totalCoin = VendorCoins::totalCoin($vndId);
			$actId	     = $jsonObj->act_id;
			$response	 = new \Stub\vendor\VendorCoin();
			$responsedt	 = $response->getTotalCoin($totalCoin);
			$data		 = Filter::removeNull($responsedt);
			$msg         = VendorCoins::checkReedemStatus($actId,$totalCoin);
			$returnSet->setStatus(true);
			$returnSet->setData($data);
			$returnSet->setMessage($msg);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	public static function redeemVendorCoin()
	{
		try
		{
			#throw new Exception("Currently under maintanance", ReturnSet::ERROR_INVALID_DATA);
			$data		 = Yii::app()->request->rawBody;
			if(!$data)
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			
			$jsonObj = CJSON::decode($data, true);
			$accTransId = $jsonObj['act_id'];
			$vendorId = UserInfo::getEntityId();
			$returnSet = VendorCoins::redeemPenalty($accTransId, $vendorId);
			
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}
	
	/**
	 @deprecated not in use now
	 */
	
	
	public function adjustLockedAmount()
	{
		$requestData = Yii::app()->request->rawBody;
		$returnSet	 = new ReturnSet();

		try
		{
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj		 = CJSON::decode($requestData, false);
			$vendorId	 = UserInfo::getEntityId();
			$status	 = false;
			$paymentFlag =1;
			if (!$vendorId)
			{
				throw new Exception("Vendor not authorized", ReturnSet::ERROR_UNAUTHORISED);
			}
			$adjustableAmount	= $reqObj->amount;
			$bidType         =  $reqObj->type;
			/*if($adjustableAmount >0)
			{
				$result = Vendors::adjustLockedAmount($vendorId, $adjustableAmount);
			}*/
			
			
			$response	 = new Beans\accounts\TransactionStatement();
			$responsedt	 = $response->showData($adjustableAmount,$bidType);
		
			$data		 = Filter::removeNull($responsedt);
			$returnSet->setStatus($status);
			$returnSet->setMessage($msg);
			$returnSet->setData($data);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}
	

}
