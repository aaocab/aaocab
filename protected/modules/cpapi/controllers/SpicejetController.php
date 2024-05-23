<?php

class SpicejetController extends Controller
{

	/** @var BookingRoute[] */
	public $routes = [];

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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(
					'REST.GET', 'REST.PUT', 'REST.REQUEST', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			['allow',
				'actions'	 => ['new', 'list'],
				'users'		 => ['@']
			],
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

		$this->onRest('req.post.hold.render', function () {
			return $this->hold();
		});

		$this->onRest('req.post.update.render', function () {
			return $this->update();
		});

		$this->onRest('req.post.cancel.render', function () {
			return $this->cancel();
		});
	}

	/** Use to hold booking */
	public function hold()
	{
		$patModel	 = null;
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);
		$response	 = new Stub\spicejet\Response();
		try
		{
			/* @var $obj Stub\spicejet\CreateRequest */
			$obj			 = $jsonMapper->map($jsonObj, new Stub\spicejet\CreateRequest());
			/** @var Booking $model */
			$userInfo		 = \UserInfo::getInstance();
			$agentId		 = $userInfo->userId;
			$model			 = $obj->getModel();

			$cnt = Booking::model()->checkDuplicateSpiceReferenceId($obj->order_reference_number);
            if ($cnt > 0)
            {
                throw new \Exception('Reference Id already exists', \ReturnSet::ERROR_INVALID_DATA);
            }

			$typeAction		 = PartnerApiTracking::CREATE_BOOKING;
			$patModel		 = PartnerApiTracking::add($typeAction, $jsonObj, $agentId, $model, $model->bkg_pickup_date);
			$model->scenario = 'validateData';
			$errors			 = CActiveForm::validate($model, null, false);

			$model->validateSpicejetPickupTime();

			if ($errors == '[]')
			{
				$patRes = Booking::checkAirportPackage($model);
				if ($patRes['pat_id'])
				{
					if($jsonObj->destination_latitude == '' || $jsonObj->destination_latitude == NULL)
					{
						$model->bkg_trip_distance = $patRes['pat_minimum_km'];
						$model->bookingRoutes->brt_trip_distance = $patRes['pat_minimum_km'];
					}
					$model->addNew();
					$model->confirm(false, false);
					BookingPref::changeDrvAppRequirementStatus($model->bkg_id);
				}
				else
				{
					throw new Exception('Pickup/ drop not available from/ to this airport', ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
				/* @var $obj Stub\spicejet\CreateResponse */
				$response		 = new Stub\spicejet\CreateResponse();
				$response->setData($model);
				$response		 = Filter::removeNull($response);
				$patModel->updateData($response, 1, $model->bkg_id, null, null);
				$response->code	 = NULL;
				$response->error = NULL;
			}
			else
			{
				$errors = $model->getErrors();
				throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
			}

		}
		catch (Exception $e)
		{
			$ret = ReturnSet::setException($e);
			$response->setError($ret);
			Logger::exception($e);
			if ($patModel)
			{
				$patModel->updateData($response, 2, null, $e->getCode(), $e->getMessage());
			}
		}
		$error_type	 = $response->code;
		$error_msg	 = $response->error;

		return $this->renderJSON([
					'type'	 => 'raw',
					'data'	 => $response
		]);
	}

	/** Use to update booking */
	public function update()
	{
		$canResp	 = new Stub\spicejet\UpdateResponse();
		$patModel	 = null;
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);
		$response	 = new Stub\spicejet\Response();
		try
		{
			/* @var $obj Stub\spicejet\UpdateRequest */
			$obj		 = $jsonMapper->map($jsonObj, new Stub\spicejet\UpdateRequest());
			/** @var Booking $model */
			$model		 = $obj->getModel();
			$userInfo	 = UserInfo::getInstance();
			$typeAction	 = PartnerApiTracking::UPDATE_BOOKING;
			$patModel = PartnerApiTracking::add($typeAction, $jsonObj, $userInfo->userId, $model, $model->bkg_pickup_date);


			// Validate
			$model->setscenario('spicejetUpdate');
			$validated = $model->validate();
			if (!$validated)
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
				$canResp->success = false;
			}


			
			$returnSet					 = $model->updateSpicejet($model);
			$response->response->success = true;
			$response->code				 = NULL;
			$response->error			 = NULL;
			$patModel->updateData($response, 1, $model->bkg_id, null, null);
			//$response->setData($response);
		}
		catch (Exception $e)
		{
			$ret = ReturnSet::setException($e);
			$response->setError($ret);

			$response->response->success = false;
			if ($patModel)
		{
			$patModel->updateData($response, 2, $model->bkg_id, $e->getCode(), $e->getMessage());
		}
		}
		$error_type	 = $response->code;
		$error_msg	 = $response->error;
		
		return $this->renderJSON([
					'type'	 => 'raw',
					'data'	 => $response
		]);
	}

	/** Use to cancel booking */
	public function cancel()
	{
		$canResp	 = new Stub\spicejet\CancelResponse();
		$data		 = Yii::app()->request->rawBody;
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);

		$response = new Stub\spicejet\Response();
		try
		{
			/* @var $obj Stub\spicejet\CancelRequest */
			$obj	 = $jsonMapper->map($jsonObj, new Stub\spicejet\CancelRequest());
			/** @var Booking $model */
			$model	 = $obj->getModel(null);
			if (!$model)
			{
				throw new Exception('Invalid request data', \ReturnSet::ERROR_INVALID_DATA);
			}

			$userInfo	 = \UserInfo::getInstance();
			$agentId	 = $userInfo->userId;

			$typeAction	 = PartnerApiTracking::CANCEL;
			$patModel	 = PartnerApiTracking::add($typeAction, $jsonObj, $userInfo->userId, $model, $model->bkg_pickup_date);

			// Validate
			$model->setscenario('spicejetCancel');
			$validated = $model->validate();
			if (!$validated)
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			if ($model->bkgTrack->bkg_arrived_for_pickup != 1)
			{
				$cancellationReason	 = $obj->cancellation_reason;
				$reasonId			 = 4;
				$cancelStatus		 = 1;
				$success			 = $model->canbooking($model->bkg_id, $cancellationReason, $reasonId, null, $cancelStatus);
				$canResp->success	 = true;
			}
			else
			{
				ServiceCallQueue::createByPartner($model);
				throw new \Exception('Driver has arrived, Trip already started', \ReturnSet::ERROR_FAILED);
				$canResp->success = false;
			}

			if (!$success)
			{
				$canResp->success = false;
				throw new \Exception('Error while cancel booking', \ReturnSet::ERROR_FAILED);
			}
			$response->setData($canResp);
		}
		catch (Exception $e)
		{
			$ret						 = ReturnSet::setException($e);
			$response->setError($ret);
			$response->response->success = false;
		}


		if (!$patModel)
		{
			$patModel = PartnerApiTracking::add($typeAction, $jsonObj, $userInfo->userId, $model, $model->bkg_pickup_date);
		}
		$time		 = Filter::getExecutionTime();
		$error_type	 = $response->code;
		$error_msg	 = $response->error;

		if ($success)
		{
			$status = 1;
		}
		else
		{
			$status = 2;
		}
		$patModel->updateData($response, 1, $model->bkg_id, $error_type, $error_msg);
		return $this->renderJSON([
					'type'	 => 'raw',
					'data'	 => $response
		]);
	}

}
