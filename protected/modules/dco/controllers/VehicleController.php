<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class VehicleController extends BaseController
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
				'actions'	 => array(''),
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
			['allow', 'actions' => ['undertakingPreview'], 'users' => ['*']],
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

		$this->onRest('req.get.getlist.render', function () {
			return $this->renderJSON($this->getlist());
		});
		$this->onRest('req.get.getTypeList.render', function () {
			return $this->renderJSON($this->getTypeList());
		});
		$this->onRest('req.post.validateAdd.render', function () {
			return $this->renderJSON($this->validateAdd());
		});
		$this->onRest('req.post.addBasicInfo.render', function () {
			return $this->renderJSON($this->addBasicInfo());
		});
		$this->onRest('req.post.getDetails.render', function () {
			return $this->renderJSON($this->getDetails());
		});
		$this->onRest('req.post.updateInfo.render', function () {
			return $this->renderJSON($this->updateInfo());
		});
		$this->onRest('req.post.uploadDoc.render', function () {
			return $this->renderJSON($this->uploadDoc());
		});
		$this->onRest('req.post.setUnderTaking.render', function () {
			return $this->renderJSON($this->setUnderTaking());
		});
		$this->onRest('req.post.undertakingInfo.render', function () {
			return $this->renderJSON($this->undertakingInfo());
		});
		$this->onRest('req.post.unlinkVendor.render', function () {
			return $this->renderJSON($this->unlinkVendor());
		});
	}

	public function getlist()
	{
		$returnSet	 = new ReturnSet();
		$searchTxt	 = Yii::app()->request->getParam('searchTxt', '');
		try
		{
			$vndId = $this->getVendorId(false);
			if (!$vndId)
			{
				throw new Exception("You are not a vendor.", ReturnSet::ERROR_UNAUTHORISED);
			}
			$cabList	 = Vehicles::getCabListByVendor($vndId, $searchTxt);
			$res		 = \Beans\common\Cab::getList($cabList);
			$response	 = Filter::removeNull($res);
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

	public function validateAdd()
	{
		$requestData = Yii::app()->request->rawBody;

		$returnSet = new ReturnSet();
		try
		{
			$reqObj		 = CJSON::decode($requestData, false);
			$jsonMapper	 = new JsonMapper();
			$flag		 = 0;
			/* @var JsonMapper $obj  */
			$obj		 = $jsonMapper->map($reqObj, new \Beans\common\Cab());
//			/** @var Vehicles $vhcModel  */
			$vhcId		 = \Vehicles::getIdByNumber($obj->number);
			if ($vhcId > 0)
			{
				$data['id']	 = (int) $vhcId;
				$flag		 = 1;
			}
			$data['isExist'] = $flag;
			$returnSet->setData($data);
			$returnSet->setStatus(true);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function getTypeList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data		 = VehicleTypes::getModelList();
			$dataList	 = \Beans\common\AllowedModels::setTypeList($data);
			$returnSet->setData($dataList);
			$returnSet->setStatus(true);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function addBasicInfo()
	{
		$requestData = Yii::app()->request->rawBody;
		$success	 = false;
		$returnSet	 = new ReturnSet();
		try
		{
			$vndId = $this->getVendorId(false);

			if (!$vndId)
			{
				throw new Exception("You are not a vendor.", ReturnSet::ERROR_UNAUTHORISED);
			}
			//$cttId = \ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);

			$vndModel = Vendors::model()->findByPk($vndId);
			if (in_array($vndModel->vnd_active, [0, 2, 3, 4]))
			{
				$activeList	 = $vndModel->vendorStatus;
				$status		 = $activeList[$vndModel->vnd_active];

				throw new Exception("Your account is in $status status.", ReturnSet::ERROR_UNAUTHORISED);
			}

			$reqObj		 = CJSON::decode($requestData, false);
			$jsonMapper	 = new JsonMapper();

			/* @var \Beans\common\Cab $obj  */
			$obj	 = $jsonMapper->map($reqObj, new \Beans\common\Cab());
			/** @var Vehicles $vhcModel  */
			$vhcId	 = \Vehicles::getIdByNumber($obj->number);
			if ($vhcId > 0)
			{
				$vhcModel = Vehicles::model()->findByPk($vhcId);
			}
			if ($vhcId > 0 && !$obj->id)//cab exists but id not provided in request
			{
				throw new Exception(json_encode(["Cab already exist"]), ReturnSet::ERROR_VALIDATION);
			}

			if ($vhcId != $obj->id)
			{
				throw new Exception("Cab id not matching", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			$vhcModel = $obj->setInfoDataForModel($vhcModel);

			$vhcModel1			 = clone $vhcModel;
			$vhcModel1->scenario = "vendorAttach";

			$resData = [];
			$result	 = [];

			if ($vhcModel1->validate())
			{
				$dataArr = $vhcModel->addVehicle_V2([], $vndId, $vhcModel, false);

				$success = $dataArr['success'];
				if (!$success && $dataArr['errors'] != '')
				{
					throw new Exception(json_encode([$dataArr['errors']]), ReturnSet::ERROR_VALIDATION);
				}
				$id = $dataArr['vehicleId'];
				if ($id > 0)
				{
					$resData['id'] = $id;
				}
				$returnSet->setData($resData);
				if ($success)
				{
					$returnSet->setMessage('Cab registered successfully');
				}
				$returnSet->setStatus($success);
			}
			elseif ($vhcModel1->hasErrors())
			{
				foreach ($vhcModel1->getErrors() as $attribute => $error)
				{
					$result[] = $error[0];
				}
				$success = false;
			}
			if (!$success && sizeof($result) > 0)
			{
				$returnSet->setErrors($result, ReturnSet::ERROR_VALIDATION);
			}
			$returnSet->setStatus($success);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function getDetails()
	{
		$data		 = Yii::app()->request->rawBody;
		$returnSet	 = new ReturnSet();
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);
		try
		{
			Logger::trace("getDetails Request: " . $data);
			if (!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$vndId = $this->getVendorId(false);
			if (!$vndId)
			{
				throw new Exception("You are not a vendor.", ReturnSet::ERROR_UNAUTHORISED);
			}
			/** @var $obj \Beans\common\Cab */
			$obj		 = $jsonMapper->map($jsonObj, new \Beans\common\Cab());
			/** @var Vehicle $vhcModel */
			$vhcId		 = $obj->id;
			$vhcModel	 = Vehicles::model()->resetScope()->findByPk($vhcId);
			if (!$vhcModel)
			{
				throw new Exception("No cab found", ReturnSet::ERROR_INVALID_DATA);
			}
			$linkWithVnd		 = false;
			$vndModel			 = Vendors::model()->findByPk($vndId);
			$vndName			 = trim($vndModel->vndContact->ctt_first_name . ' ' . $vndModel->vndContact->ctt_last_name);
			$vehicleOwnerName	 = trim($vhcModel->vhc_reg_owner . ' ' . $vhcModel->vhc_reg_owner_lname);
			if (strtoupper($vndName) == strtoupper($vehicleOwnerName))
			{
				$linkWithVnd = true;
			}
			$obj1 = \Beans\common\Cab::setByModel($vhcModel, $vndId, $linkWithVnd);
			$returnSet->setData($obj1);
			$returnSet->setStatus(true);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function updateInfo()
	{
		$requestData = Yii::app()->request->rawBody;
		$success	 = false;
		$returnSet	 = new ReturnSet();
		try
		{
			Logger::trace("updateInfo Request: " . $requestData);
			$vndId = $this->getVendorId(false);
			if (!$vndId)
			{
				throw new Exception("You are not a vendor.", ReturnSet::ERROR_UNAUTHORISED);
			}

 
			$vndModel = Vendors::model()->findByPk($vndId);
			if (in_array($vndModel->vnd_active, [0, 2, 3, 4]))
			{
				$activeList	 = $vndModel->vendorStatus;
				$status		 = $activeList[$vndModel->vnd_active];

				throw new Exception("Your account is in $status status.", ReturnSet::ERROR_UNAUTHORISED);
			}


			$reqObj		 = CJSON::decode($requestData, false);
			$jsonMapper	 = new JsonMapper();

			/* @var \Beans\common\Cab $obj  */
			$obj	 = $jsonMapper->map($reqObj, new \Beans\common\Cab());
			/** @var \Vehicles $vhcModel  */
			$vhcId	 = $obj->id;
			if ($vhcId > 0)
			{
				$vhcModel = Vehicles::model()->findByPk($vhcId);
			}
			if (!$vhcModel)
			{
				throw new Exception("No cab found", ReturnSet::ERROR_INVALID_DATA);
			}
			$vhcModel			 = $obj->setModelForUpdate($vhcModel);
			$vhcModel1			 = clone $vhcModel;
			$vhcModel1->scenario = "insertadminapp";

			$result = [];

			$vhcModel->vhc_owned_or_rented = $vhcModel->vhc_owned_or_rented | 0;

			if ($vhcModel1->validate())
			{
				$linkWithVnd = false;
				if ($obj->isOwner == 1)
				{
					$vndModel			 = Vendors::model()->findByPk($vndId);
					$vndName			 = trim($vndModel->vndContact->ctt_first_name . ' ' . $vndModel->vndContact->ctt_last_name);
					$vehicleOwnerName	 = trim($vhcModel->vhc_reg_owner . ' ' . $vhcModel->vhc_reg_owner_lname);
					if (strtoupper($vndName) == strtoupper($vehicleOwnerName))
					{
						$linkWithVnd = true;
					}
				}
				$dataArr = $vhcModel->addVehicle_V2([], $vndId, $vhcModel, $linkWithVnd);
				$success = $dataArr['success'];
				if (!$success && $dataArr['errors'] != '')
				{
					throw new Exception(json_encode([$dataArr['errors']]), ReturnSet::ERROR_VALIDATION);
				}
				$vendorVehicleModel	 = VendorVehicle::model()->findByVndVhcId($vndId, $vhcId);
				$resArr				 = ['isLouRequired' => 0];
				if (!($vendorVehicleModel && $vendorVehicleModel->vvhc_active == 1) || $vendorVehicleModel->vvhc_is_lou_required == 1)
				{
					$resArr = ['isLouRequired' => 1];
				}
				$returnSet->setData($resArr);
				$id = $dataArr['vehicleId'];

				$returnSet->setMessage('Information updated');
				$returnSet->setStatus($success);
			}
			elseif ($vhcModel1->hasErrors())
			{
				foreach ($vhcModel1->getErrors() as $attribute => $error)
				{
					$result[] = $error[0];
				}
				$success = false;
			}
			if (!$success && sizeof($result) > 0)
			{
				$returnSet->setErrors($result, ReturnSet::ERROR_VALIDATION);
			}
			$returnSet->setStatus($success);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function uploadDoc()
	{
		$returnSet	 = new ReturnSet();
		$requestData = Yii::app()->request->getParam('data');

		try
		{
			if (!$requestData)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$uploadedFile	 = CUploadedFile::getInstanceByName("docImage");
			$vndId			 = $this->getVendorId(false);
			if (!$vndId)
			{
				throw new Exception("You are not a vendor.", ReturnSet::ERROR_UNAUTHORISED);
			}
			$jsonObj = CJSON::decode($requestData, false);

			$jsonMapper = new JsonMapper();

			/* @var \Beans\common\Cab $obj  */
			$obj = $jsonMapper->map($jsonObj, new \Beans\common\Document());

			$vhcId		 = $obj->getVehicleId();
			$vhcModel	 = Vehicles::model()->findByPk($vhcId);
			if (!$vhcModel)
			{
				throw new Exception("No cab found", ReturnSet::ERROR_INVALID_DATA);
			}

			$userInfo	 = UserInfo::getInstance();
			$docType	 = $obj->documentType;
			if (!$docType)
			{
				throw new Exception("document type not given", ReturnSet::ERROR_INVALID_DATA);
			}
			$docTypeList = VehicleDocs::model()->doctypeTxt;
			$doctypeTxt	 = $docTypeList[$docType];
			if ($uploadedFile)
			{
				$success = VehicleDocs::saveDoc($uploadedFile, $vhcId, $docType, $userInfo);
				if ($success)
				{
					$returnSet->setStatus(true);
					$vhcModel->vhc_modified_at	 = new CDbExpression('NOW()');
					$success					 = $vhcModel->save();
					$vhcModel->pendingApproval($vhcId, $userInfo);
				}
			}
			if (!$returnSet->isSuccess())
			{
				throw new Exception("Document not uploaded", ReturnSet::ERROR_INVALID_DATA);
			}
			$returnSet->setMessage("Document for $doctypeTxt uploaded successfully");
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function setUnderTaking()
	{
		$returnSet	 = new ReturnSet();
		$reqData	 = Yii::app()->request->rawBody;
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($reqData, false);
		$transaction = null;
		try
		{
			Logger::trace("setUnderTaking  Request: " . $reqData);
			$vndId			 = $this->getVendorId();
 
			$vndModel = Vendors::model()->findByPk($vndId);
			if (in_array($vndModel->vnd_active, [0, 2, 3, 4]))
			{
				$activeList	 = $vndModel->vendorStatus;
				$status		 = $activeList[$vndModel->vnd_active];

				throw new Exception("Your account is in $status status.", ReturnSet::ERROR_UNAUTHORISED);
			}


			/* @var $objCab \Beans\common\Cab */
			$objCab			 = $jsonMapper->map($jsonObj, new Beans\common\Cab());
			$objOwner		 = $jsonMapper->map($jsonObj->owner, new Beans\contact\Person());
			$objCab->owner	 = $objOwner;
			$vhcId			 = $objCab->id;
			$vhcModel		 = Vehicles::model()->findByPk($vhcId);
			if (!$vhcModel)
			{
				throw new Exception("No cab found", ReturnSet::ERROR_INVALID_DATA);
			}
			if (!$objCab->LOU->validTill)
			{
				throw new Exception("LOU expiry date is missing", ReturnSet::ERROR_INVALID_DATA);
			}
			$licenseNo	 = trim($objCab->owner->dlNumber);
			$pan		 = trim($objCab->owner->pan);

			if ($licenseNo && $pan)
			{
				$cttOwnerId = \Contact::getIdByLicensePan($pan, $licenseNo);
			}

//			if(!$pan)
//			{
//				throw new Exception(json_encode(["PAN is required to proceed"]), ReturnSet::ERROR_VALIDATION);
//			}
//			
//			if(!$cttOwnerId)
//			{
//				$cttOwnerModel	 = \Beans\contact\Person::getModeldata($objCab->owner);
//				$tempValue		 = 1;
//				$cttReturnSet	 = $cttOwnerModel->add($tempValue);
//				if($cttReturnSet->getStatus())
//				{
//					$data		 = $cttReturnSet->getData();
//					$cttOwnerId	 = $data['id'];
//				}
//				else
//				{
//					throw new Exception(json_encode(["Unable to create owner data"]), ReturnSet::ERROR_VALIDATION);
//				}
//			}
			if ($cttOwnerId)
			{
				$objCab->owner->id = $cttOwnerId;
			}

			if (trim($vhcModel->vhc_reg_owner) == '')
			{
				$vhcModel->vhc_reg_owner		 = $objCab->owner->firstName;
				$vhcModel->vhc_reg_owner_lname	 = $objCab->owner->lastName;
			}

			$transaction = DBUtil::beginTransaction();

			$dataArr = $vhcModel->addVehicle_V2([], $vndId, $vhcModel);

			$success = $dataArr['success'];
			if (!$success && $dataArr['errors'] != '')
			{
				throw new Exception(json_encode([$dataArr['errors']]), ReturnSet::ERROR_VALIDATION);
			}

			$vendorVehicleModel = VendorVehicle::model()->findByVndVhcId($vndId, $vhcId);

			$linkId = ($vendorVehicleModel->vvhc_id > 0) ? $vendorVehicleModel->vvhc_id : null;

			/* @var $objCab \Beans\common\Cab */
			$modelVvhc = $objCab->setLOUData($vhcModel, $vndId, $linkId);

			$modelVvhc->scenario = 'updateUnderTaking';
			if ($modelVvhc->validate())
			{
				if ($modelVvhc->vvhcVhc->save())
				{
					if (!$modelVvhc->save())
					{
						$errors = $modelVvhc->getErrors();
						throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
					}

					DBUtil::commitTransaction($transaction);
					$linkId		 = $modelVvhc->vvhc_id;
					$hashLink	 = Yii::app()->shortHash->hash($linkId);
					$vhcIdLink	 = Yii::app()->shortHash->hash($vhcId);
					$basePath	 = \Yii::app()->params['fullAPIBaseURL'];

					$url	 = $basePath . "/dco/vehicle/undertakingPreview?linkHash=" . $hashLink . $vhcIdLink;
					$data	 = ['url' => $url];

					$returnSet->setStatus(true);

					$message = "Cab linked";
				}
				$returnSet->setData($data);
				$returnSet->setMessage($message);
				Logger::create("Update Under Taking Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
			}
			else
			{
				$errors = $modelVvhc->getErrors();
				throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
			}
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
			Logger::exception($e);
			DBUtil::rollbackTransaction($transaction);
		}
		Logger::trace("<===Response===>" . json_encode($returnSet));
		return $returnSet;
	}

	public function undertakingInfo()
	{
		$returnSet	 = new ReturnSet();
		$reqData	 = Yii::app()->request->rawBody;
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($reqData, false);
		try
		{
			$vndId		 = $this->getVendorId(false);
			$objCab		 = $jsonMapper->map($jsonObj, new \Beans\common\Cab());
			$vhcModel	 = \Vehicles::model()->findByPk($objCab->id);
			if (!$vhcModel)
			{
				throw new Exception("No cab found", ReturnSet::ERROR_INVALID_DATA);
			}
			$response	 = \Beans\common\Cab::getLOUData($vhcModel, $vndId);
			$data		 = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($data);
			Logger::create("Under Taking Information Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function actionUndertakingPreview()
	{
		$vhcId	 = Yii::app()->request->getParam('vhc_id', 0);
		$vndId	 = Yii::app()->request->getParam('vnd_id', 0);
		$linkId	 = Yii::app()->request->getParam('linkId', 0);

		$linkHash = Yii::app()->request->getParam('linkHash', '');

		if ($linkHash)
		{
			$linkIdHash	 = substr($linkHash, 0, 5);
			$linkId		 = Yii::app()->shortHash->unHash($linkIdHash);
			$vhcIdHash	 = substr($linkHash, 5, 5);
			$vhcId		 = Yii::app()->shortHash->unHash($vhcIdHash);
		}
		if ($vhcId == 0 && $vndId == 0 && $linkId == 0)
		{
			echo "No data provided";
			Yii::app()->end();
		}
		$data = VendorVehicle::model()->findUndertakingByVndVhcId($vhcId, $vndId, $linkId);
		if (!$data['vvhc_id'])
		{
			echo "There is some issue in linking";
			Yii::app()->end();
		}
		$data['vvhc_digital_flag']	 = 1;
		$imgPath					 = VendorVehicle::model()->getLOUPathS3($data['vvhc_id']);
		$this->renderPartial('generate_undertaking', array('model' => $data, 'data' => $data, 'docPath' => $imgPath), false, true);
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function unlinkVendor()
	{
		$returnSet	 = new ReturnSet();
		$reqData	 = Yii::app()->request->rawBody;
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($reqData, false);
		$transaction = null;
		try
		{
			$vndId = $this->getVendorId(false);

			$vndModel = Vendors::model()->findByPk($vndId);
			if (in_array($vndModel->vnd_active, [0, 2, 3, 4]))
			{
				$activeList	 = $vndModel->vendorStatus;
				$status		 = $activeList[$vndModel->vnd_active];

				throw new Exception("Your account is in $status status.", ReturnSet::ERROR_UNAUTHORISED);
			}


			$objCab		 = $jsonMapper->map($jsonObj, new \Beans\common\Cab());
			$vhcModel	 = \Vehicles::model()->findByPk($objCab->id);
			if (!$vhcModel)
			{
				throw new Exception("No cab found", ReturnSet::ERROR_INVALID_DATA);
			}
			$transaction = DBUtil::beginTransaction();
			$result		 = VendorVehicle::model()->unlinkByVendorVehicleId($vndId, $objCab->id);
			if (!$result)
			{
				throw new Exception(json_encode(["Cab was not linked with you"]), ReturnSet::ERROR_VALIDATION);
			}
			$returnSet->setStatus(true);
			$returnSet->setMessage("Cab removed successfully.");
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}
}
