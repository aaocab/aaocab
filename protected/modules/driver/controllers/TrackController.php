<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class TrackController extends BaseController
{

	public function filters()
	{
		return array
			(
			array
				(
				"application.filters.HttpsFilter + create",
				"bypass" => false
			),
			"accessControl", // perform access control for CRUD operations
			"postOnly + delete", // we only allow deletion via POST request
			array
				(
				"RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS"
			),
		);
	}

	public function actions()
	{
		return array
			(
			"REST." => "RestfullYii.actions.ERestActionProvider",
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array
			(
			array
				("allow", // allow all users to perform "index" and "view" actions
				"actions"	 => array(),
				"users"		 => array("@"),
			),
			array
				("allow", // allow authenticated user to perform "create" and "update" actions
				"actions"	 => array
					(
					"REST.GET", "REST.PUT", "REST.POST", "REST.DELETE", "REST.OPTIONS", "uploads"
				),
				"users"		 => array("*"),
			),
			array
				("allow", // allow admin user to perform "admin" and "delete" actions
				"actions"	 => array(),
				"users"		 => array("@"),
			),
			array
				("deny", // deny all users
				"users" => array("*"),
			),
		);
	}

	/**
	 * This holds the REST API Events that's needs to be used
	 */
	public function restEvents()
	{
		$this->onRest("req.cors.access.control.allow.methods", function () {
			return ["GET", "POST", "PUT", "DELETE", "OPTIONS"]; //List of allowed http methods (verbs)
		});

		$this->onRest("post.filter.req.auth.user", function ($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array();
			//"syncBookingDetails","/syncBookingCoordinates", "/syncBookingFiles";
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		/**
		 * This function is used for sync booking track details
		 */
		$this->onRest("req.post.syncBookingDetails.render", function () {

			return $this->renderJSON($this->syncDetails());
		});
		/**
		 * This function is used for sync booking Coordinates
		 */
		$this->onRest("req.post.syncBookingCoordinates.render", function () {

			return $this->renderJSON($this->syncCoordinates());
		});
		/**
		  @depreacated
		 * new function syncBookingFilesV1
		 */
		$this->onRest("req.post.syncBookingFiles.render", function () {
			return $this->renderJSON($this->syncBookingFiles());
		});
		/**
		 * This function is used for sync booking files
		 */
		$this->onRest("req.post.syncBookingFilesV1.render", function () {
			return $this->renderJSON($this->syncBookingFilesV1());
		});
		$this->onRest("req.get.syncIncompleteBookingDetails.render", function () {
			return $this->renderJSON($this->syncIncompleteBookingDetails());
		});
	}

	/**
	 * This function handles the booking track sync
	 * @return array
	 */
	public function syncDetails()
    {
        $returnSet = new ReturnSet();
        try
        {
            $syncDetails = Yii::app()->request->rawBody;
            Logger::trace("<======Request =====>" . $syncDetails);
            $jsonObj     = CJSON::decode($syncDetails, false);
            if (empty($jsonObj))
            {
                $returnSet->setStatus(false);
                $returnSet->setMessage("No Record Found.");
                goto end;
            }

            $userInfo            = UserInfo::getInstance();
            $userInfo->userId    = UserInfo::getUserId();
            $userInfo->userType  = UserInfo::TYPE_DRIVER; //UserInfo::getUserType();
            $userInfo->platform  = UserInfo::$platform;

            $jsonMapper  = new JsonMapper();
            $response    = [];
            /** @var Stub\booking\SyncRequest $obj */
            foreach ($jsonObj as $event)
            {
                $syncRequest = new Stub\booking\SyncRequest();
                /** @var Stub\booking\SyncRequest $obj */
                $obj         = $jsonMapper->map($event, $syncRequest);
                Logger::trace("Stub\booking\SyncRequest: " . json_encode($obj));

                $eventModel = $obj->getModel($userInfo);

                $checkLog        = DrvUnsyncLog::model()->checkExist($obj->bookingId, $obj->type);
                $eventResponse   = $eventModel->handleEvents($obj);
                
                Logger::trace("BookingTrackLog: " . json_encode($eventModel));
                
                $bookingModel    = Booking::model()->findByPk($obj->bookingId);

                if ($bookingModel->bkg_agent_id != '' || $bookingModel->bkg_agent_ref_code != '')
                {
                    $reff_id = $bookingModel->bkg_agent_ref_code;
                }


                $res                     = new Stub\booking\SyncResponse();
                $res->setData($eventResponse, $eventModel, $reff_id);
                $responsedt->dataList[]  = $res;

                if (!$eventResponse->getStatus())
                {
                    $hitUrl          = Yii::app()->request->hostInfo . Yii::app()->request->url;
                    #$uploadTodb      = DrvUnsyncLog::model()->add($eventModel->btl_bkg_id, $eventModel->btl_event_type_id, $userInfo, $syncDetails, $eventResponse, $hitUrl);
                    $uploadTodb      = true;
                    $res->status     = false;
                    $res->syncStatus = 3;
                    if ($uploadTodb)
                    {
                        $res->status     = true;
                        $res->syncStatus = 2;
                    }

                    $res->syncError = ($eventResponse->getErrors() != null) ? json_encode($eventResponse->getErrors()) : $eventResponse->getMessage();
                    Logger::trace("Stub\booking\SyncResponse: " . json_encode($res));
                }
                else
                {
                    $eventModel->addLocation();
                }


                BookingTrackLog::checkApiDiscrepancy($eventModel->btl_bkg_id, $syncDetails);
            }
            $response    = $responsedt;
            $data        = Filter::removeNull($response);
            $returnSet->setStatus(true);
            $returnSet->setData($data);

            $cnt = 0;
            foreach ($data->dataList as $v)
            {
                if (!$v->status)
                {
                    Logger::trace("Booking Sync Failed : " . json_encode($v));
                    $cnt++;
                }
            }
            Logger::trace("<======Response =====>" . json_encode($returnSet));
            if ($cnt > 0)
            {
                Logger::pushTraceLogs();
            }
        }
        catch (Exception $e)
        {
            if ($eventModel != null)
            {
                BookingTrackLog::checkApiDiscrepancy($eventModel->btl_bkg_id, $syncDetails);
            }
            $returnSet = ReturnSet::setException($e);
        }
        end:
        return $returnSet;
    }
		

	/**
	 * 
	 * @return type
	 */
	public function syncCoordinates()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data	 = Yii::app()->request->rawBody;
			//$data    = '[{"bookingId":1372358,"bookingTripId":1453696,"bookingType":1,"type":101,"dateTime":"2019-11-05 15:45:51","remarks":"Driver triggered no show reset","device":{"type":1,"version":"6.02","osVersion":21,"uniqueId":"359e20f24fe25f6c","deviceName":"LENOVO Lenovo K50a40"},"coordinate":{"latitude":"28.5961","longitude":"77.1587"},"odometer":{"value":1000,"frontPath":"/sjsafk/safksjfdk","checksum":"addafaffwq654f8768768574"}}]';
			$jsonObj = CJSON::decode($data, false);
			if (empty($jsonObj))
			{
				throw new Exception("Mandatory data not passed", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$userInfo			 = UserInfo::getInstance();
			$userInfo->userId	 = empty($userInfo->userId) ? 123 : $userInfo->userId;
			$userInfo->userType	 = UserInfo::getUserType(); //Driver type = 3
			$userInfo->platform	 = 5; //Platform type
			$jsonMapper			 = new JsonMapper();

			$response	 = [];
			/** @var Stub\booking\SyncRequest $obj */
			$syncRequest = new Stub\booking\SyncRequest();
			foreach ($jsonObj as $event)
			{
				$obj			 = $jsonMapper->map($event, $syncRequest);
				$eventModel		 = $obj->getModel($userInfo);
				/** @var BookingTrackLog $eventModel */
				$eventResponse	 = $eventModel->syncCoordinates();
				$res			 = new Stub\booking\SyncResponse();
				$res->setData($eventResponse, $eventModel);
				$response[]		 = $res;
			}
			$returnSet->setStatus(true);
			$returnSet->setData($response);
			Logger::create("Response : " . CJSON::encode($returnSet));
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($e);
			Logger::exception($e);
		}
		return $returnSet;
	}

	/**
	 * @deprecated since version number 2022-05-27
	 * new function syncBookingFilesV1
	 * created by Madhumita
	 */
	public function syncBookingFiles()
	{
		Logger::info("_FILES DATA: " . json_encode($_FILES));
		$resultSet		 = new ReturnSet();
		$response		 = [];
		Logger::info("REQUEST: " . Yii::app()->request->getParam('data'));
		$data			 = CJSON::decode(Yii::app()->request->getParam('data'));
		$bkgId			 = $data['bookingId'];
		$event			 = $data['type'];
		$deviceUniqueID	 = $data['device']['uniqueId'];
		$discrepancies	 = $data['discrepancies'];
		$responsedt		 = new stdClass();
		/* if($data['type'] == 503) 
		  {
		  Logger::info("<======Checksum =====>".$data['odometer']['checksum']."||<======BpayType =====>".$data['odometer']['eventValue']);
		  } */
		foreach ($_FILES as $key => $val)
		{
			try
			{
				$uploadedFile	 = CUploadedFile::getInstanceByName($key);
				$returnSet		 = BookingPayDocs::uploadDocsByChecksum($uploadedFile, $bkgId, $deviceUniqueID, $event, $discrepancies);
				if ($returnSet->isSuccess())
				{
					/* Iread comment On 
					  $docId = $returnSet->getData()->bpay_id;
					  switch ($event)
					  {
					  case 107:
					  Ireaddocs::add($docId, $docType = 2, $type    = 3);
					  break;
					  case 101:
					  case 104:
					  case 8:
					  case 9:
					  Ireaddocs::add($docId, $docType = 2, $type    = 2);
					  break;
					  default:
					  break;
					  } */
					$res1		 = new \Stub\common\Document();
					$res1->setDocModelData($returnSet->getData(), $returnSet->getMessage());
					$returnSet->setData($res1);
					$response[]	 = $res1;
				}
				else
				{
					$error	 = "\n\tFILE [{$key}] Data: " . json_encode($vall);
					$error	 .= "\n\tReturnSet: " . json_encode($returnSet);
					throw new Exception($error, ReturnSet::ERROR_FAILED);
				}
			}
			catch (Exception $e)
			{
				$returnSet				 = ReturnSet::setException($e);
				$data['fileChecksum']	 = md5_file($uploadedFile->getTempName());
				$data['status']			 = $returnSet->getStatus();
				$returnSet->setData($data);
				$response[]				 = $data;
			}
		}
		$responsedt->dataList	 = $response;
		$resultSet->setStatus(true);
		$resultSet->setData($responsedt);
		$cnt					 = 0;
		foreach ($responsedt->dataList as $v)
		{
			if (!$v->status)
			{
				Logger::info("File Sync Failed : " . json_encode($v));
				$cnt++;
			}
		}
		if ($cnt > 0)
		{
			logger::pushTraceLogs();
		}
		Logger::info("RESPONSE=> " . json_encode($resultSet));
		return $resultSet;
	}

	/**
	 * New Version
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function syncBookingFilesV1()
	{

		$resultSet	 = new ReturnSet();
		$response	 = [];
		$data		 = json_decode(Yii::app()->request->getParam('data'));
		if (empty($data))
		{
			$resultSet->setStatus(false);
			$resultSet->setMessage("No Record Found.");
			goto end;
		}
		Logger::trace("Request=> " . json_encode($data));

		$jsonMapper = new JsonMapper();

		/** @var Stub\booking\SyncRequest $obj */
		$obj = $jsonMapper->map($data, new Stub\booking\SyncRequest());
		//print_r($obj);exit;
		#$obj->init(UserInfo::getInstance());

		/** @var BookingTrackLog $btlModel */
		$btlModel = $obj->init(UserInfo::getInstance());

		$bkgId			 = $btlModel->btl_bkg_id;
		$event			 = $obj->type;
		$eventValue		 = $obj->odometer->eventValue;
		$deviceUniqueID	 = $obj->device->uniqueId;
		$discrepancies	 = $obj->discrepancies;
		$checksum		 = $btlModel->btl_doc_checksum;
		$responsedt		 = new stdClass();
		/* if($data['type'] == 503) 
		  {
		  Logger::info("<======Checksum =====>".$data['odometer']['checksum']."||<======BpayType =====>".$data['odometer']['eventValue']);
		  } */
		foreach ($_FILES as $key => $val)
		{
			try
			{
				$uploadedFile	 = CUploadedFile::getInstanceByName($key);
				$returnSet		 = BookingPayDocs::uploadDocsByChecksum($uploadedFile, $bkgId, $deviceUniqueID, $event, $discrepancies, $checksum, $eventValue);
				if ($returnSet->isSuccess())
				{
					/* Iread comment On 
					  $docId = $returnSet->getData()->bpay_id;
					  switch ($event)
					  {
					  case 107:
					  Ireaddocs::add($docId, $docType = 2, $type    = 3);
					  break;
					  case 101:
					  case 104:
					  case 8:
					  case 9:
					  Ireaddocs::add($docId, $docType = 2, $type    = 2);
					  break;
					  default:
					  break;
					  } */
					$res1 = new \Stub\common\Document();
					$res1->setDocModelData($returnSet->getData(), $returnSet->getMessage());
					if ($res1->appId == '')// this conditon added for old booking (internet data useges solving issue booking) where data uploaded but appsync id in database is null
					{
						$res1->appId = $obj->appId;
					}
					$returnSet->setData($res1);
					$response[] = $res1;
				}
				else
				{
					$error	 = "\n\tFILE [{$key}] Data: " . json_encode($vall);
					$error	 .= "\n\tReturnSet: " . json_encode($returnSet);
					throw new Exception($error, ReturnSet::ERROR_FAILED);
				}
			}
			catch (Exception $e)
			{
				$returnSet			 = ReturnSet::setException($e);
				$data->fileChecksum	 = md5_file($uploadedFile->getTempName());
				$data->status		 = $returnSet->getStatus();
				$returnSet->setData($data);
				$response[]			 = $data;
			}
		}
		$responsedt->dataList	 = $response;
		$resultSet->setStatus(true);
		$resultSet->setData($responsedt);
		$cnt					 = 0;
		foreach ($responsedt->dataList as $v)
		{
			if (!$v->status)
			{
				Logger::info("File Sync Failed : " . json_encode($v));
				$cnt++;
			}
		}
		if ($cnt > 0)
		{
			logger::pushTraceLogs();
		}
		Logger::info("RESPONSE=> " . json_encode($resultSet));
		end:
		return $resultSet;
	}

	public function syncIncompleteBookingDetails()
	{
		$returnSet = new ReturnSet();
		try
		{
			$userInfo			 = UserInfo::getInstance();
			$userInfo->userId	 = UserInfo::getUserId();
			$userInfo->userType	 = UserInfo::TYPE_DRIVER;
			$userInfo->platform	 = UserInfo::$platform;

			$drvId		 = UserInfo::getEntityId(); // AppTokens::getEntityByUserInfo($userInfo->userId, $userInfo->userType);
			$bookingId	 = BookingTrack::getOngoingBkgByDrv($drvId);

			if (empty($bookingId))
			{
				throw new Exception("No incomplete booking found", ReturnSet::ERROR_NO_RECORDS_FOUND);
				goto end;
			}

			$model			 = Booking::model()->findByPk($bookingId);
			$bkgRideStatus	 = $model->bkgTrack->bkg_ride_start;
			$lastEvent		 = $model->bkgTrack->btk_last_event;

			if ($lastEvent != NULL)
			{
				$bkgTrackLogModel = BookingTrackLog::model()->getAllPreviousEventByBkgId($bookingId);
				foreach ($bkgTrackLogModel as $val)
				{
					$res = new Stub\booking\SyncRequest();
					$res->setData($val, $model);

					$responsedt->dataList[] = $res;
				}
			}
			$response	 = $responsedt;
			$data		 = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($data);
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($e);
			//Logger::exception($e);
		}
		end:
		return $returnSet;
	}

}
