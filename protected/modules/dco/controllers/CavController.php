<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class CavController extends BaseController
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

		$this->onRest('req.post.add.render', function () {
			return $this->renderJSON($this->add());
		});
		$this->onRest('req.post.remove.render', function () {
			return $this->renderJSON($this->remove());
		});
		$this->onRest('req.post.getDetails.render', function () {
			return $this->renderJSON($this->getDetails());
		});
		$this->onRest('req.post.getList.render', function () {
			return $this->renderJSON($this->getList());
		});
	}

	public function add()
	{ 
 
		$requestData = Yii::app()->request->rawBody;

		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			if(!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}

			$reqObj		 = CJSON::decode($requestData, false);
			$jsonMapper	 = new JsonMapper();
			/** @var \Beans\common\CabAvailabilities $obj */
			$obj		 = $jsonMapper->map($reqObj, new \Beans\common\CabAvailabilities());
			$userInfo	 = UserInfo::getInstance();

			if($userInfo->userType == UserInfo::TYPE_VENDOR)
			{
				$vendorId		 = $this->getVendorId();
				$obj->vendor	 = new \Beans\Vendor();
				$obj->vendor->id = $vendorId | 0;
			}
			if($userInfo->userType == UserInfo::TYPE_DRIVER)
			{
				$driverId		 = $this->getDriverId();
				$obj->driver	 = new \Beans\Driver();
				$obj->driver->id = $driverId | 0;
			}
			$obj->validateInputs();
			 
			if(!$obj->isOneWay && !$obj->isShared && !$obj->isLocalTrip)
			{
				$obj->isOneWay = 1;
			}
			/** @var \CabAvailabilities $model */
			$model = $obj->getRegisterModel();
			if(!$model)
			{
				throw new Exception("Invalid Data", \ReturnSet::ERROR_INVALID_DATA);
			}
			$errors = [];
			if($model->validate())
			{
				$resModel = $model->addNew();
				if($resModel->hasErrors())
				{
					$errors = $resModel->getErrors();
				}
			}
			if($model->hasErrors())
			{
				$errors = $model->getErrors();
			}
			if($errors)
			{
				throw new \Exception(CJSON::encode($errors), \ReturnSet::ERROR_VALIDATION);
			}
			$returnSet->setStatus(true);
			$message = 'Availability registered successfully';
			$returnSet->setMessage($message);
			DBUtil::commitTransaction($transaction);
		}
		catch(Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = \ReturnSet::setException($e);
		}
		Logger::trace('Response : '. json_encode($returnSet));
		return $returnSet;
	}

	public function getDetails()
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
			/** @var \Beans\common\CabAvailabilities $obj */
			$obj		 = $jsonMapper->map($reqObj, new \Beans\common\CabAvailabilities());

			$cavId = $obj->id;

			/** @var \CabAvailabilities $model */
			$data = \CabAvailabilities::getDetailById($cavId);

			$obj->setData($data);

			$returnSet->setData($obj);
		}
		catch(Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function getList()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$reqObj		 = CJSON::decode($data, false);

		try
		{
			$userType = UserInfo::getUserType();
			if($userType == UserInfo::TYPE_VENDOR)
			{
				$entId = $this->getVendorId();
			}
			if($userType == UserInfo::TYPE_DRIVER)
			{
				$entId = $this->getDriverId();
			}
			$filter = [];
			if($reqObj)
			{
				$jsonMapper	 = new JsonMapper();
				$obj		 = $jsonMapper->map($reqObj, new \Beans\common\CabAvailabilities());
				$filter		 = (array) Filter::removeNull($obj);
			}
			$dataReaderList = \CabAvailabilities::getListByEntity($entId, $userType, $filter);
			if($dataReaderList->getRowCount() == 0)
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			$response = \Beans\common\CabAvailabilities::getList($dataReaderList);

			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch(Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function remove()
	{
		$requestData = Yii::app()->request->rawBody;

		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			if(!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}

			$reqObj		 = CJSON::decode($requestData, false);
			$jsonMapper	 = new JsonMapper();
			/** @var \Beans\common\CabAvailabilities $obj */
			$obj		 = $jsonMapper->map($reqObj, new \Beans\common\CabAvailabilities());
			$userInfo	 = UserInfo::getInstance();

			if($userInfo->userType == UserInfo::TYPE_VENDOR)
			{
				$vendorId = $this->getVendorId();
			}
			$cavId		 = $obj->id;
			$delResult	 = CabAvailabilities::deactivateByIdnVnd($cavId, $vendorId);
			if($delResult != 1)
			{
				throw new Exception("There was some ", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$returnSet->setStatus(true);
			$message = 'Availability deleted successfully';
			$returnSet->setMessage($message);
			DBUtil::commitTransaction($transaction);
		}
		catch(Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}
}
