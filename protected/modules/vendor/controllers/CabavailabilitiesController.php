<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class CabavailabilitiesController extends BaseController
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
		$this->onRest('req.cors.access.control.allow.methods', function() {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});


		$this->onRest('post.filter.req.auth.user', function($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array( 'cabCategoryList');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.get.cabCategoryList.render', function() {
			return $this->renderJSON($this->cabCategoryList());
		});
		$this->onRest('req.post.add1.render', function() {
			return $this->create();
			exit;
			$sync_data	 = Yii::app()->request->rawBody;
			$data		 = CJSON::decode($sync_data, true);
//$process_sync_data = '{"cav_cab_id":"56957","cav_from_city":"30893","cav_to_cities":"31975","cav_driver_id":"66857","cav_date_time":"","cav_amount":"2580","cav_duration":"4"}';
//			$process_sync_data		 = Yii::app()->request->getParam('data');
//			$data					 = CJSON::decode($process_sync_data, true);
			$result		 = Vehicles::model()->getVendorDetails($data['cav_cab_id']);
			$cavModel	 = new CabAvailabilities();
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);

			if (Vendors::model()->authoriseVendor($token))
			{
				$data['cav_vendor_id']	 = UserInfo::getEntityId();
				$success				 = $cavModel->add($data);
				if ($success)
				{
					$apiURL = 'http://localhost:99/api/victoria/cabAvailability?api=UXVFya4Ov64qsuAVAx81xU9AAweaUE9x';

//					$apiURL				 = 'http://www.taxiotaxi.com/api/victoria/cabAvailability?api=UXVFya4Ov64qsuAVAx81xU9AAweaUE9x';
					$requestParamList	 = ['senderPhone'		 => $result['vnd_phone'],
						'cabNumber'			 => $result['vhc_number'],
						'senderCountryCode'	 => '91',
						'fromCityId'		 => $data['cav_from_city'],
						'toCityId'			 => $data['cav_to_cities'],
						'dateTime'			 => $data['cav_date_time'],
						'amount'			 => $data['cav_amount']];
					$jsonData			 = json_encode($requestParamList);
					$ch					 = curl_init($apiURL);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json'
							)
					);
					$jsonResponse		 = curl_exec($ch);
					$responseParamList	 = json_decode($jsonResponse, true);
					$response			 = $responseParamList["success"];
				}
				$data = [];
				if (!$success)
				{
					$data = ['errors' => $cavModel->getErrors()];
				}
				else
				{
					$data = ['data' => ['id' => $cavModel->cav_id]];
				}
			}
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => ['success' => $success] + $data
			]);
		});

		$this->onRest('req.post.create.render', function() {
			return $this->create();
		});


		$this->onRest('req.get.remove.render', function() {

			return $this->removeListRow();
		});

		$this->onRest('req.post.list.render', function() {
			return $this->getList();
		});
	}

	public function create()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);
		$transaction = DBUtil::beginTransaction();
		try
		{
			$obj		 = $jsonMapper->map($jsonObj, new \Stub\vendor\CabAvailabilities());
			/** @var CabAvailabilities $model */
			$UserInfo	 = UserInfo::getInstance();
			$model		 = $obj->getModel(null, $UserInfo);
			if (!$model)
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			$resModel = $model->addNew();
			if ($resModel->hasErrors())
			{
				$errors = $resModel->getErrors();
				throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
			}
			if ($jsonObj->cab->hasCNG != null)
			{
				$isCng	 = $jsonObj->cab->hasCNG;
				$cabId	 = $jsonObj->cab->id;
				$res	 = Vehicles::updateCng($cabId, $isCng);
			}
			$returnSet->setStatus(true);
			$message = 'Availability registered successfully';
			$returnSet->setMessage($message);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet	 = ReturnSet::setException($e);
			$errorMsg	 = "Please enter valid data and try again";
			$returnSet->setMessage($errorMsg);
		}
		return $this->renderJSON($returnSet);
	}

	public function removeListRow()
	{
		$dataID		 = Yii::app()->request->getParam('cavId');
		$returnSet	 = new ReturnSet();

		$transaction = DBUtil::beginTransaction();
		try
		{
			$vnd		 = UserInfo::getEntityId();
			$delResult	 = CabAvailabilities::deactivateByIdnVnd($dataID, $vnd);
			if ($delResult != 1)
			{
				throw new Exception("There was some ", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$returnSet->setStatus(true);
			$message = 'Availability deleted successfully';
			$returnSet->setMessage($message);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($e);
		}
		return $this->renderJSON($returnSet);
	}

	public function getList()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$jsonObj	 = CJSON::decode($data, false);
		$transaction = DBUtil::beginTransaction();
		try
		{
			$pageSize	 = ($jsonObj->pageSize > 0) ? $jsonObj->pageSize : 30;
			$pageCount	 = ($jsonObj->currentPage > 0) ? $jsonObj->currentPage : 1;
			$search_txt	 = $jsonObj->search_txt;
			$entityId	 = UserInfo::getEntityId();
			if (!$entityId)
			{
				throw new Exception("Unauthorised", ReturnSet::ERROR_UNAUTHORISED);
			}
			$totalCount	 = CabAvailabilities::getList($entityId, "vendor", $search_txt, true);
			$cabList	 = CabAvailabilities::getList($entityId, "vendor", $search_txt, false, $pageSize, $pageCount);
			if (!$cabList)
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			$dataObj	 = new Stub\vendor\CabAvailabilities();
			$dataObj->getList($cabList, $totalCount, $pageSize, $pageCount);
			$response	 = Filter::removeNull($dataObj);
			if (!$response)
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($response);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($e);
		}
		return $this->renderJSON($returnSet);
	}

	public function cabCategoryList()
	{
		$returnSet		 = new ReturnSet();
		$model			 = new SvcClassVhcCat('search');
		$returnType      = "list";
		$dataProvider	 = $model->getVctSvcList($returnType);
		
		$dataObj		 = new Stub\common\Vehicle();
		$dataObj		 = $dataObj->setVehicleCategory($dataProvider);
		#print_r($dataObj);
		$response = Filter::removeNull($dataObj);
		if (!$response)
		{
			throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}
		$returnSet->setStatus(true);
		$returnSet->setData($response);
		return $returnSet;
	}

}
