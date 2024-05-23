<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class TrackController extends BaseController
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

		$this->onRest('req.post.syncRideData.render', function () {
			return $this->renderJSON($this->syncRideData());
		});

		$this->onRest("req.post.syncRideFiles.render", function () {
			return $this->renderJSON($this->syncRideFiles());
		});

		$this->onRest("req.post.calculateFares.render", function () {
			return $this->renderJSON($this->calculateFares());
		});
		$this->onRest("req.post.routeDirection.render", function () {
			return $this->renderJSON($this->routeDirection());
		});
		
	}


	public function syncRideData()
	{
		$returnSet = new ReturnSet();
		$transaction =null;
		$responseSet = [];
		try
		{
			$requestData = Yii::app()->request->rawBody;
			Logger::trace($requestData);
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj = CJSON::decode($requestData, false);
			foreach ($reqObj as $eventData)
			{
				
				$result		 = \Beans\booking\TrackEvent::setTrackModel($eventData);
				$jsonMapper	 = new JsonMapper();
				
				$model			 = $result[0];
				$trackObj		 = $result[1];
				if($eventData->eventType == 303)
				{
				$reqData = $jsonMapper->map($eventData, new \Beans\contact\Scq());
				}
				$checkLog		 = DrvUnsyncLog::model()->checkExist($eventData->refId, $eventData->eventType);
				$dco			 = 1;
				$eventResponse	 = $model->handleEvents($trackObj, $dco,$reqData);
				$bookingModel	 = Booking::model()->findByPk($eventData->refId);
				
				 if ($bookingModel->bkg_agent_id != '' || $bookingModel->bkg_agent_ref_code != '')
                {
                    $reff_id = $bookingModel->bkg_agent_ref_code;
                }
				$res = Beans\booking\TrackEvent::setResponse($eventResponse, $model, $refId);
				if ($eventResponse->getStatus())
				{
					$model->addLocation();
				}
				$responseSet[] = $res;
				
				BookingTrackLog::checkApiDiscrepancy($model->btl_bkg_id, $requestData);
			}
			$data = Filter::removeNull($responseSet);
			Logger::trace("response".$data);
			$returnSet->setStatus(true);
			$returnSet->setData($data);
		}
		catch (Exception $ex)
		{
			
			$returnSet = ReturnSet::setException($ex);
			
		}
		
		return $returnSet;
	}

	public function syncRideFiles()
	{
		$resultSet		 = new ReturnSet();
		$response		 = [];
		$uploadedFile	 = null;
		$allFiles		 = $_FILES;
		$rawData = Yii::app()->request->getParam('data');
		Logger::trace("Request:".Yii::app()->request->getParam('data'));
	
		
		$data = CJSON::decode(Yii::app()->request->getParam('data'), false);
		
		//$data        = CJSON::decode($data,false);
		try
		{
			#Logger::trace("Request:".$data);
			if (empty($data))
			{
				throw new Exception(json_encode("No data Found."), ReturnSet::ERROR_VALIDATION);
			}
			foreach ($data as $eventData)
			{
				$result			 = Beans\booking\TrackEvent::setTrackModel($eventData);
				$model			 = $result[0];
				$eventResponse	 = $model->handleEvents();
				$errors		 = $eventResponse->getErrors();
				$responseId = $model->btl_appsync_id;
			
				if(!empty($errors))
				{
					throw new Exception(json_encode($errors[0]), ReturnSet::ERROR_VALIDATION);
				}
				$deviceArr		 = json_decode($model->btl_device_info);
				$bkgId			 = $model->btl_bkg_id;
				$event			 = $model->btl_event_type_id;
				$deviceUniqueID	 = $deviceArr->uuid;
				$discrepancies	 = $data->discrepancies;
				$eventVal		 = $model->payDocModel->bpay_type;
				$payDocsId       = $model->payDocModel->bpay_id;

				if (empty($allFiles))
				{
					throw new Exception(json_encode("No Files Found."), ReturnSet::ERROR_VALIDATION);
				}
				foreach ($allFiles as $key => $val)
				{

					$uploadedFile	 = CUploadedFile::getInstanceByName($key);
					$returnSet		 = BookingPayDocs::uploadDocs($uploadedFile, $bkgId, $deviceUniqueID, $event, $discrepancies, $checksum, $eventVal,$payDocsId);
					$response[]		 = Beans\booking\TrackEvent::setFileResponse($returnSet,$responseId);
				}
			}
			Logger::trace("Response===============:".json_encode($response));
			$resultSet->setStatus(true);
			$resultSet->setData($response);
		}
		catch (Exception $e)
		{
			$returnSet	 = ReturnSet::setException($e);
			$response[]	 = $e->getMessage();
		}

		return $resultSet;
	}

	public function calculateFares()
	{
		$returnSet = new ReturnSet();

		$responseSet = [];
		try
		{
			$requestData = Yii::app()->request->rawBody;
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj		 = CJSON::decode($requestData, false);
			$jsonMapper	 = new JsonMapper();
			$obj		 = $jsonMapper->map($reqObj, new Beans\booking\Transaction());

			$result = BookingInvoice::calculateExtramount($obj);

			$invoiceData = $result['invoicedata'];
			$status		 = $result['status'];
			$response	 = \Beans\booking\Fare::setByInvoiceModel($invoiceData, $status);

			$data = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($data);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @return type
	 * @throws CHttpException
	 */
	public function routeDirection()
	{
		//$data = '[{"lat":28.552415,"lng":77.131125},{"lat":28.552413,"lng":77.131123}]';
		$data = Yii::app()->request->rawBody;
		if (!$data)
		{
			throw new CHttpException(404, "Data not found", 404);
		}
		$dataArr	 = CJSON::decode($data, true);
		$directions	 = RouteDirectionMaster::getDirections($dataArr);
		return $directions;
	}

}
