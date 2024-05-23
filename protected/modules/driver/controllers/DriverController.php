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
		$this->onRest('req.cors.access.control.allow.methods', function () {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function ($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array('/list', '/editinfo', '/citylist', '/edit', '/statusDetails', '/covidInstructions', '/getCurrentServerTimeStamp');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});
		$this->onRest('req.post.getlist.render', function () {
			return $this->getList();
		});
		$this->onRest("req.get.covidInstructions.render", function () {
			return $this->renderJson($this->covidInstructions());
		});
		$this->onRest("req.post.uploadPckImages.render", function () {

			return $this->renderJson($this->uploadPckImages());
		});
		$this->onRest("req.post.uploadPckImages_V1.render", function () {

			return $this->renderJson($this->uploadPckImages_V1());
		});

		$this->onRest("req.get.getCurrentServerTimeStamp.render", function () {
			return $this->renderJson($this->getCurrentTimeStamp());
		});

		$this->onRest("req.get.getCurrentServerTimeStamp_V1.render", function () {
			return $this->renderJson($this->getCurrentTimeStamp_V1());
		});

		$this->onRest('req.post.notificationLog.render', function () {
			return $this->renderJSON($this->notificationLog());
		});
	}

	public function getList()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->getParam('search_txt', '');
		try
		{
			$drvId		 = UserInfo::getEntityId();
			$usetType	 = UserInfo::getUserType();

			if (!$drvId)
			{
				throw new Exception("Unauthorised", ReturnSet::ERROR_UNAUTHORISED);
			}
			$drvData	 = Drivers::getDetailbyid($drvId);
			$drvList	 = new Stub\common\Driver();
			$drvList->getList($drvData);
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
		return $this->renderJSON($returnSet);
	}

	public function covidInstructions()
	{
		$returnSet = new ReturnSet();
		$returnSet->setStatus(false);
		$returnSet->setData(["dataList" => []]);
		if (Yii::app()->params['covidFlag'] == 1)
		{
			$returnSet->setStatus(true);
			$data = ["dataList" => Filter::getCovidInstructions(2)];
			$returnSet->setData($data);
		}
		return $returnSet;
	}

	/* @deprecated function
	 * new Function uploadPckImages_V1
	 */

	public function uploadPckImages()
	{
		$returnSet	 = new ReturnSet();
		$vendorId	 = UserInfo::getEntityId();

		try
		{

			CUploadedFile::getInstanceByName('img1');
			$wholeData1		 = Yii::app()->request->getParam('data');
			Logger::trace("Upload Packeage Images Request*********" . $wholeData1);
			$wholeData		 = CJSON::decode($wholeData1, true);
			Logger::trace("Upload Packeage Images Request*********" . $wholeData);
			$vehicleNumber	 = $wholeData['cabNumber'];
			$vehicleId		 = Vehicles::model()->getIdByNumber($vehicleNumber);
			Logger::trace(" Upload Packeage Images .... Vehicleid*********" . $vehicleId);
			$bookingId		 = $wholeData['bookingId'];
			//$vehicleId = $wholeData['cabId'];
			$uploadedFile	 = CUploadedFile::getInstanceByName('img1');

			if ($uploadedFile == "")
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("No Image found");
				goto returnmsg;
			}
			/* $checkSum = $wholeData['chkSum'];

			  $systemChkSum = md5_file($uploadedFile->getTempName());

			  if($systemChkSum!=$checkSum)
			  {
			  $returnSet->setStatus(false);
			  $returnSet->setMessage("Image Upload fail please try again");
			  goto returnmsg;
			  }
			 */

			$type			 = $wholeData['type'];
			$typeArr		 = array('front' => 8, 'back' => 9, 'left' => 10, 'right' => 11, 'numberPlate' => 12);
			$package_type	 = $typeArr[$type];
			Logger::trace(" Upload Packeage Images .... Upload File *********" . $uploadedFile);
			$app_type		 = "5";
			// $returnSet = VehicleDocs::model()->uploadPackages($uploadedFile, $package_type, $vehicleId);
			$returnFile		 = VehicleDocs::model()->savePackageImage($uploadedFile, $package_type, $vehicleId);

			if ($returnFile)
			{

				VehicleStats::addRelatedBooking($vehicleId, $bookingId);

				$image				 = $returnFile;
				$success			 = BookingPayDocs::model()->uploadCarVerifyImage($package_type, $app_type, $bookingId, $image, $systemChkSum);
				$userInfo			 = UserInfo::getInstance();
				$userInfo->userType	 = UserInfo::TYPE_DRIVER;
				$eventId			 = BookingLog::CAB_VERIFIED;
				$desc				 = "Document uploaded for verification";
				BookingLog::model()->createLog($bookingId, $desc, $userInfo, $eventId, false);

				$res1 = new \Stub\common\VehicleDoc();
				$res1->setDocData($wholeData);
				$returnSet->setData($res1);
				$returnSet->setStatus(true);
			}
			else
			{
				$returnSet->setStatus(false);
				Logger::trace("Packeage Images Error *************" . json_encode($ex));
				$returnSet->setMessage("No record found1");
			}
			Logger::trace("Packeage Images response *************" . json_encode($success));
		}
		catch (Exception $ex)
		{


			$returnSet->setStatus(false);
			$returnSet->setMessage("No record found");
		}
		returnmsg:
		return $returnSet;
	}

	public function uploadPckImages_V1()
	{
		$returnSet	 = new ReturnSet();
		$vendorId	 = UserInfo::getEntityId();

		try
		{

			CUploadedFile::getInstanceByName('img1');
			$wholeData1	 = Yii::app()->request->getParam('data');
			logger::pushTraceLogs();
			Logger::trace("Upload Packeage Images Request*********" . $wholeData1);
			$wholeData	 = CJSON::decode($wholeData1, true);

			$vehicleNumber	 = $wholeData['cabNumber'];
			$vehicleId		 = Vehicles::getIdByNumber($vehicleNumber);
			Logger::trace(" Upload Packeage Images .... Vehicl id*********" . $vehicleId);
			$bookingId		 = $wholeData['bookingId'];
			//$vehicleId = $wholeData['cabId'];
			$uploadedFile	 = CUploadedFile::getInstanceByName('img1');

			if ($uploadedFile == "")
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("No Image found");
				goto returnmsg;
			}
			$checkSum = $wholeData['chkSum'];
			if ($checkSum == "")
			{
				// $returnSet->setStatus(true);
				//$returnSet->setMessage("Image Upload fail please try again");
				// Logger::trace("Image Upload fail *************" . $wholeData1);
				// \Sentry\captureMessage("Message " ."Image Upload fail Checksum blank ".$wholeData1);
				//goto returnmsg;
			}

			$systemChkSum = md5_file($uploadedFile->getTempName());
			#echo $systemChkSum;
			if ($systemChkSum != $checkSum)
			{
				//$returnSet->setStatus(true);
				//$returnSet->setMessage("Image Upload fail please try again");
				//  Logger::trace("Image Upload fail *************" . $wholeData1);
				// \Sentry\captureMessage("Message " ."Image Upload fail checksum mismatch ".$wholeData1);
				//goto returnmsg;
			}


			$type			 = $wholeData['type'];
			$typeArr		 = array('front' => 8, 'back' => 9, 'left' => 10, 'right' => 11, 'numberPlate' => 12);
			$package_type	 = $typeArr[$type];
			Logger::trace(" Upload Packeage Images .... Upload File *********" . $uploadedFile);
			$app_type		 = "5";

			$returnFile = VehicleDocs::model()->savePackageImage($uploadedFile, $package_type, $vehicleId);

			if ($returnFile)
			{

				VehicleStats::addRelatedBooking($vehicleId, $bookingId);
				//update Vehicle verify flag
				VehicleStats::updateVerifyFlag($vehicleId);

				$image	 = $returnFile;
				$success = BookingPayDocs::model()->uploadCarVerifyImageV1($package_type, $app_type, $bookingId, $image, $systemChkSum);
				if ($success)
				{
					$userInfo			 = UserInfo::getInstance();
					$userInfo->userType	 = UserInfo::TYPE_DRIVER;
					$eventId			 = BookingLog::CAB_VERIFIED;
					$desc				 = "Document uploaded for verification";
					BookingLog::model()->createLog($bookingId, $desc, $userInfo, $eventId, false);

					$res1 = new \Stub\common\VehicleDoc();
					$res1->setDocData($wholeData, $systemChkSum);
					$returnSet->setData($res1);
					$returnSet->setStatus(true);
				}
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("No record found1");
				// Logger::trace("Packeage Images Is not uploaded *************" . $wholeData1);
				// \Sentry\captureMessage("Message" . $wholeData1);
			}
		}
		catch (Exception $ex)
		{
			Logger::trace("Packeage Images Error *************" . json_encode($ex));
			\Sentry\captureMessage("Error" . $ex);

			$returnSet->setStatus(false);
			$returnSet->setMessage("No record found");
		}
		returnmsg:
		return $returnSet;
	}

	// Returns Current Time
	public function getCurrentTimeStamp()
	{
		$returnSet = new ReturnSet();
		try
		{
			$currentDate = Filter::getDBDateTime();
			$response	 = ["date" => $currentDate, "timeStamp" => strtotime($currentDate)];
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	// Returns Current Time in milliseconds
	public function getCurrentTimeStamp_V1()
	{
		$returnSet = new ReturnSet();
		try
		{
			$currentDate = Filter::getDBDateTime();
			$response	 = ["date" => $currentDate, "timeStamp" => strtotime($currentDate) * 1000]; // timeStamp in milliseconds 			
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public static function notificationLog()
	{
		$returnSet = new ReturnSet();
		try
		{
			$drvId = UserInfo::getEntityId();
			if (empty($drvId))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			$ntlLogList			 = NotificationLog::getDetails($drvId, 3);
			$ntlList			 = new \Stub\common\Notification();
			$notificationList	 = $ntlList->getList($ntlLogList);
			$response			 = Filter::removeNull($notificationList);
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
}
