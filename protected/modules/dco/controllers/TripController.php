<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class TripController extends BaseController
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

			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.post.gnowSnoozeNotification.render', function () {
			return $this->renderJSON($this->gnowSnoozeNotification());
		});
		$this->onRest('req.post.pendingJobs.render', function () {
			return $this->renderJSON($this->pendingJobs());
		});
		$this->onRest('req.post.jobList.render', function () {
			return $this->renderJSON($this->jobList());
		});
	}

	/** Need to modify snooze notification should be shifted to notification beans
	 * gnowSnoozeNotification 
	 * @return type
	 * @throws Exception
	 */
	public function gnowSnoozeNotification()
	{
		$returnSet	 = new ReturnSet();
		$rawData	 = Yii::app()->request->rawBody;
		$data		 = CJSON::decode($rawData, true);
		Logger::trace("<===Requset===>" . $data);
		try
		{
			if ($data == "")
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$vendorId	 = UserInfo::getEntityId();
			$userInfo	 = UserInfo::getInstance();

			if (!$vendorId)
			{
				throw new Exception("Unauthorized Vendor", ReturnSet::ERROR_INVALID_DATA);
			}

			$tripId			 = $data['tripId'];
			$startTime		 = date("Y-m-d H:i:s");
			$snoozeTime		 = $data['snoozeTime'];
			$convertedTime	 = date('Y-m-d H:i:s', strtotime('+' . $snoozeTime . ' min', strtotime($startTime)));

			$snoozeTime = $convertedTime;

			if (BookingVendorRequest::addSnoozeTime($tripId, $snoozeTime, $vendorId))
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage('Request processed successfully');
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function pendingJobs()
	{
		$returnSet = new ReturnSet();
		try
		{
			$requestData = Yii::app()->request->rawBody;
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj = CJSON::decode($requestData, false);

			$vendorId = $this->getVendorId(false);
			if (!$vendorId)
			{
				throw new Exception("No vendor id found.", ReturnSet::ERROR_UNAUTHORISED);
			}
			$vndModel = Vendors::model()->findByPk($vendorId);
			if ($vndModel->vnd_active != 1)
			{
				$statusList	 = Vendors::model()->getStatusList();
				$status		 = $statusList[$vndModel->vnd_active];
				throw new Exception("Your account is $status.", ReturnSet::ERROR_UNAUTHORISED);
			}

			$jsonMapper	 = new JsonMapper();
			$obj		 = $jsonMapper->map($reqObj, new Beans\booking\VendorPendingRequest());
			$filter		 = $obj->setData();

			$offSetCount = $filter->pageSize;
			$pageCount	 = $filter->pageCount;

//			$result		 = BookingVendorRequest::getPendingBookingRequest($vendorId, $pageCount, $filter, $offSetCount);
			$result = BookingVendorRequest::getPendingRequestV2($vendorId, $pageCount, $filter, $offSetCount);

			$dependencyMsg = \VendorStats::getDependencyMessage($vendorId);
			if ($result->getRowCount() > 0)
			{

				$response	 = new \Beans\vendor\TripDetailResponse();
				$responsedt	 = $response->setPendingResponse($result, $dependencyMsg);
				$data		 = Filter::removeNull($responsedt);

				$returnSet->setData($data);
				$returnSet->setStatus(true);
			}
			else
			{
				throw new Exception("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
				
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		Logger::profile("response : " . $vendorId . ":" . json_encode($returnSet));
		return $returnSet;
	}

	public function jobList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$requestData = Yii::app()->request->rawBody;
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$reqObj = CJSON::decode($requestData, false);

			$vendorId = $this->getVendorId(false);
			if (!$vendorId)
			{
				throw new Exception("No vendor id found.", ReturnSet::ERROR_UNAUTHORISED);
			}
			$vndModel = Vendors::model()->findByPk($vendorId);

			if (!$vndModel || in_array($vndModel->vnd_active, [2, 3]))
			{
				if (!$vndModel)
				{
					$status = 'Blocked';
				}
				else
				{
					$activeList	 = Vendors::model()->vendorStatus;
					$status		 = $activeList[$vndModel->vnd_active];
				}
				throw new Exception("Your account is in $status status.", ReturnSet::ERROR_UNAUTHORISED);
			}

			$jsonMapper	 = new JsonMapper();
			/** @var \Beans\booking\VendorRequest() $obj */
			$obj		 = $jsonMapper->map($reqObj, new Beans\booking\VendorRequest());
			$filter		 = $obj->processRequest($obj);

			$offSetCount = $filter->pageSize;
			$pageCount	 = $filter->pageCount;

//			$result		 = BookingVendorRequest::getPendingBookingRequest($vendorId, $pageCount, $filter, $offSetCount);
			$result = BookingVendorRequest::getPendingRequestV2($vendorId, $pageCount, $filter, $offSetCount);

			if ($result->getRowCount() > 0)
			{
				$dependencyMsg	 = \VendorStats::getDependencyMessage($vendorId);
				$response		 = new \Beans\vendor\TripDetailResponse();
				$responsedt		 = $response->setPendingResponse($result, $dependencyMsg);
				$data			 = Filter::removeNull($responsedt);

				$returnSet->setData($data);
				$returnSet->setStatus(true);
			}
			else
			{
				throw new Exception("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
				
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		Logger::profile("response : " . $vendorId . ":" . json_encode($returnSet));
		return $returnSet;
	}
}
