<?php

class VehicleController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';
	public $email_receipient;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete1', // we only allow deletion via POST request
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
				'actions'	 => array('REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			['allow', 'actions' => ['typelist'], 'roles' => ['vehicleModelList']],
			['allow', 'actions' => ['addtype'], 'roles' => ['vehicleModelAdd']],
			['allow', 'actions' => ['add', 'checkexisting'], 'roles' => ['vehicleAdd', 'vehicleDelete']],
			['allow', 'actions' => ['list'], 'roles' => ['vehicleList']],
			['allow', 'actions' => ['delvehicle'], 'roles' => ['vehicleDelete']],
			['allow', 'actions' => ['mapcab'], 'roles' => ['vehicleMapping']],
			['allow', 'actions' => ['serviceclassType'], 'roles' => ['vehicleAddService']],
			['allow', 'actions' => ['addcategory'], 'roles' => ['vehicleAddCategory']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('viewOld', 'approvelist', 'approve', 'freeze', 'loadvehicle', 'checkapprovedntottrips', 'loadvendorlist',
					'delvehicletype', 'getbyvendor', 'getvehicletyperate', 'addavailability', 'getdriver', 'getvehicle', 'vehiclemodelbytype',
					'availabilitylist', 'delavailability', 'markedbadlist', 'resetmarkedbad', 'showlog', 'view',
					'updatevehicledoc', 'rejectvehicledoc', 'approvedoc', 'docapprovallist', 'showdocimg', 'mapcategory', 'showboostdocimg', 'approveAllCarImages', 'approveAllCarImagesNew', 'showCarImg',
					'approvedocimg', 'imagerotate', 'undertakingPreview', 'VTypeVCatMapping', 'ScVcMapping', 'generateAgreementForVehicle', 'assureCabInfo', 'uberapprove', 'categorylist', 'changevctstatus', 'viewtypes', 'saveProfileImage', 'serviceclasslist', 'changeservicestatus', 'vehicleTypeById', 'vehicleDetails', 'showdoc', 'UpdateDetails', 'CarVerifyDoclist', 'showAllCarImg', 'approveBoostCarImages', 'OdometerView', 'rulelist', 'addcabrule', 'status', 'ruleStatus', 'modellist', 'showRulesLog', 'addremark', 'showDocumentLog'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index', 'create', 'json', 'ratejson'),
				'users'		 => array('@'),
			),
			['allow', 'actions' => ['assureCabInfo', 'generateAgreementForVehicle', 'undertakingPreview'], 'users' => ['*']],
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
			$ri	 = array('/list', '/checkExisting', '/edit', '/info');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.get.list.render', function () {
			$vhc_ids		 = Yii::app()->request->getParam('vhc_ids');
			$search_txt		 = Yii::app()->request->getParam('search_txt');
			$page_no		 = (int) Yii::app()->request->getParam('page_no');
			$page_number	 = ($page_no > 0) ? $page_no : 0;
			$vehicleModel	 = Vehicles::model()->getCabDetailsAdmin($page_number, 0, $search_txt, $vhc_ids);
			$res			 = Vehicles::model()->getCabDetailsAdmin($page_number, 1, $search_txt, $vhc_ids);
			$count			 = $res[0]['cnt'] | 0;
			if ($count != 0)
			{
				$pageCount = ceil($count / 20);
			}
			if ($vehicleModel != [])
			{
				$success = true;
				$error	 = null;
			}
			else
			{
				$success = false;
				$error	 = "Error occured while fetching list";
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $success,
					'error'			 => $error,
					'model'			 => $vehicleModel,
					'count'			 => $count,
					'total_pages'	 => $pageCount
				)
			]);
		});

		$this->onRest('req.get.info.render', function () {
			$success = false;
			$errors	 = 'Something went wrong';
			$vhcId	 = Yii::app()->request->getParam('vhc_id');
			/* @var $model Vehicles */
			$model	 = Vehicles::model()->findByPk($vhcId);
			unset($model->vhc_log, $model->vhc_insurance_proof, $model->vhc_reg_certificate, $model->vhc_front_plate, $model->vhc_rear_plate, $model->vhc_permits_certificate, $model->vhc_fitness_certificate);
			if ($model != '')
			{
				$success	 = true;
				$errors		 = [];
				$data		 = $model->getAttributes();
				$dataDocs	 = VehicleDocs::model()->getDocsByVhcId($model->vhc_id);
				$newData	 = array_merge($data, $dataDocs);

				$returnData = array(
					'vhc_insurance_exp_date'			 => ($newData['vhc_insurance_exp_date'] != NULL && $newData['vhc_insurance_exp_date'] != '1970-01-01') ? $newData['vhc_insurance_exp_date'] : "",
					'vhc_tax_exp_date'					 => ($newData['vhc_tax_exp_date'] != NULL && $newData['vhc_tax_exp_date'] != '1970-01-01') ? $newData['vhc_tax_exp_date'] : "",
					'vhc_pollution_exp_date'			 => ($newData['vhc_pollution_exp_date'] != NULL && $newData['vhc_pollution_exp_date'] != '1970-01-01') ? $newData['vhc_pollution_exp_date'] : "",
					'vhc_reg_exp_date'					 => ($newData['vhc_reg_exp_date'] != NULL && $newData['vhc_reg_exp_date'] != '1970-01-01') ? $newData['vhc_reg_exp_date'] : "",
					'vhc_commercial_exp_date'			 => ($newData['vhc_commercial_exp_date'] != NULL && $newData['vhc_commercial_exp_date'] != '1970-01-01') ? $newData['vhc_commercial_exp_date'] : "",
					'vhc_fitness_cert_end_date'			 => ($newData['vhc_fitness_cert_end_date'] != NULL && $newData['vhc_fitness_cert_end_date'] != '1970-01-01') ? $newData['vhc_fitness_cert_end_date'] : "",
					'vhc_owned_or_rented'				 => ($newData['vhc_owned_or_rented'] != NULL) ? $newData['vhc_owned_or_rented'] : "",
					'vhc_dop'							 => ($newData['vhc_dop'] != NULL) ? $newData['vhc_dop'] : "",
					'vhc_year'							 => ($newData['vhc_year'] != NULL) ? $newData['vhc_year'] : "",
					'vhc_color'							 => ($newData['vhc_color'] != NULL) ? $newData['vhc_color'] : "",
					'vhc_is_attached'					 => ($newData['vhc_is_attached'] != NULL) ? $newData['vhc_is_attached'] : "",
					'vhc_is_commercial'					 => ($newData['vhc_is_commercial'] != NULL) ? $newData['vhc_is_commercial'] : "",
					'vhc_approved'						 => ($newData['vhc_approved'] != NULL) ? $newData['vhc_approved'] : "",
					'vhc_type_id'						 => ($newData['vhc_type_id'] != NULL) ? $newData['vhc_type_id'] : "",
					'vhc_number'						 => $newData['vhc_number'],
					'vhc_id'							 => $newData['vhc_id'],
					'vhc_insurance_proof'				 => $newData['vhc_insurance_proof'],
					'vhc_front_plate'					 => $newData['vhc_front_plate'],
					'vhc_rear_plate'					 => $newData['vhc_rear_plate'],
					'vhc_pollution_certificate'			 => $newData['vhc_pollution_certificate'],
					'vhc_reg_certificate'				 => $newData['vhc_reg_certificate'],
					'vhc_permits_certificate'			 => $newData['vhc_permits_certificate'],
					'vhc_fitness_certificate'			 => $newData['vhc_fitness_certificate'],
					'vhc_insurance_proof_status'		 => $newData['vhc_insurance_proof_status'],
					'vhc_front_plate_status'			 => $newData['vhc_front_plate_status'],
					'vhc_rear_plate_status'				 => $newData['vhc_rear_plate_status'],
					'vhc_pollution_certificate_status'	 => $newData['vhc_pollution_certificate_status'],
					'vhc_permits_certificate_status'	 => $newData['vhc_permits_certificate_status'],
					'vhc_reg_certificate_status'		 => $newData['vhc_reg_certificate_status'],
					'vhc_fitness_certificate_status'	 => $newData['vhc_fitness_certificate_status']
				);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => $returnData,
				)
			]);
		});

		$this->onRest('req.get.checkExisting.render', function () {
			$vehicleModel	 = '';
			$vendorIds		 = '';
			$success		 = true;
			$error			 = '';
			$vehicle		 = Yii::app()->request->getParam('vhc_number');
			$vendorId		 = Yii::app()->request->getParam('vhc_vendor_id');
			$checkAccess	 = Yii::app()->user->checkAccess('vehicleAdd');
			if ($checkAccess)
			{
				$data	 = ['vhc_number' => $vehicle, 'vhc_vendor_id' => $vendorId];
				$result	 = Vehicles::model()->checkExistingVehicle($data);
				if ($result['success'] == 1)
				{
					$success = true;
					$vnd_ids = $result['vnd_ids'];
					$error	 = $result['msg'];
					Logger::create('FAILURE ===========>: ' . $result, CLogger::LEVEL_INFO);
				}
				else
				{
					$success = false;
					$vnd_ids = $result['vnd_ids'];
					$error	 = $result['msg'];
					Logger::create('SUCCESS ===========>: ' . $result, CLogger::LEVEL_INFO);
				}
			}
			else
			{
				$success = false;
				$error	 = 'You do not have privilage to add vehicle.';
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'error'		 => $error,
					'vnd_ids'	 => $vnd_ids,
					'data'		 => $data
				)
			]);
		});

		$this->onRest('req.post.edit.render', function () {
			$success			 = false;
			$errors				 = 'Something went wrong while uploading';
			$process_sync_data	 = Yii::app()->request->getParam('data');
			//$process_sync_data = '{"vhc_vendor_id":"43","vhc_id":"","vhc_type_id":"16","vhc_number":"BA 01 676155","vhc_year":"2016","vhc_color":"u8ihhRGB","vhc_insurance_exp_date":"2018-03-25","vhc_tax_exp_date":"2018-02-13","vhc_dop":"2018-02-25 00:00:00","vhc_owned_or_rented":"2","vhc_is_attached":"1","vhc_is_commercial":"1","vhc_pollution_exp_date":"2018-02-03","vhc_reg_exp_date":"2018-02-25","vhc_commercial_exp_date":"2018-02-25","vhc_fitness_cert_end_date":"2018-02-22"}';
			Logger::create('POST DATA ===========>: ' . $process_sync_data, CLogger::LEVEL_TRACE);
			$data				 = CJSON:: decode($process_sync_data, true);
			$checkAccess		 = Yii::app()->user->checkAccess('vehicleEdit');
			if ($checkAccess)
			{
				if ($data['vhc_id'] > 0)
				{
					/* @var model Vehicles */
					$model			 = Vehicles::model()->findByPk($data['vhc_id']);
					$oldData		 = $model->attributes;
					$model->scenario = 'update';
				}
				else
				{
					$model			 = new Vehicles();
					$model->scenario = 'insertadminapp';
				}

				$model->attributes = $data;
				if ($model->vhc_approved == 1)
				{
					$model->vhc_approved_by = Yii::app()->user->getId();
				}
				if ($model->validate())
				{
					$newData = $model->attributes;
					if ($model->save())
					{
						$vehicleId	 = Yii::app()->db->lastInsertID;
						$success	 = true;
						$errors		 = [];
						if ($data['vhc_vendor_id'] > 0 && $vehicleId > 0)
						{
							$data1	 = ['vendor' => $data['vhc_vendor_id'], 'vehicle' => $vehicleId];
							$linked	 = VendorVehicle::model()->checkAndSave($data1);
						}
						if ($success)
						{
							$userInfo	 = UserInfo::getInstance();
							$vhcId		 = ($data['vhc_id'] > 0) ? $data['vhc_id'] : $vehicleId;
							if ($data['vhc_id'] > 0)
							{
								$getOldDifference	 = array_diff_assoc($oldData, $newData);
								$changesForLog		 = "<br>Old Values: " . $this->getModificationMSG($getOldDifference, false);
								$desc				 = "Cab modified | ";
								$desc				 .= $changesForLog;
								$event_id			 = VehiclesLog::VEHICLE_MODIFIED;
							}
							else
							{
								$desc		 = "Vehicle created |";
								$event_id	 = VehiclesLog::VEHICLE_CREATED;
							}
							VehiclesLog::model()->createLog($vhcId, $desc, $userInfo, $event_id, false, false);
						}
						Logger::create('SUCCESS ===========>: [' . $vhcId . ' - ' . $model->vhc_number . ' ]', CLogger::LEVEL_INFO);
					}
				}
				else
				{
					$success = false;
					foreach ($model->getErrors() as $attribute => $error)
						$errors	 .= json_encode($error);

					Logger::create('FALIURE ===========>: [' . $vhcId . ' - ' . $model->vhc_number . ' ]', CLogger::LEVEL_INFO);
				}
			}
			else
			{
				$success = false;
				$errors	 = 'You do not have privilage to edit vehicle.';
			}



			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'vhc_id'	 => $vhcId
				),
			]);
		});

		$this->onRest('req.post.editdoc.render', function () {
			$success					 = false;
			$process_sync_data			 = Yii::app()->request->getParam('data');
			$vhc_id						 = Yii::app()->request->getParam('vhc_id');
			$userInfo					 = UserInfo::getInstance();
			$insurance					 = $_FILES['insurance']['name'];
			$insurance_tmp				 = $_FILES['insurance']['tmp_name'];
			$front_plate				 = $_FILES['front_plate']['name'];
			$front_plate_tmp			 = $_FILES['front_plate']['tmp_name'];
			$rear_plate					 = $_FILES['rear_plate']['name'];
			$rear_plate_tmp				 = $_FILES['rear_plate']['tmp_name'];
			$pollution_certificate		 = $_FILES['pollution_certificate']['name'];
			$pollution_certificate_tmp	 = $_FILES['pollution_certificate']['tmp_name'];
			$reg_certificate			 = $_FILES['reg_certificate']['name'];
			$reg_certificate_tmp		 = $_FILES['reg_certificate']['tmp_name'];
			$permit_certificate			 = $_FILES['permit_certificate']['name'];
			$permit_certificate_tmp		 = $_FILES['permit_certificate']['tmp_name'];
			$fitness_certificate		 = $_FILES['fitness_certificate']['name'];
			$fitness_certificate_tmp	 = $_FILES['fitness_certificate']['tmp_name'];
			Logger::create('POST DATA Vehicle ID ===========>: ' . $vhc_id, CLogger::LEVEL_TRACE);

			$postdata	 = "Insurance ==> " . $insurance . " - " . $insurance_tmp;
			$postdata	 .= "Front License ->" . $front_plate . " - " . $front_plate_tmp;
			$postdata	 .= "Rear License ->" . $rear_plate . " - " . $rear_plate_tmp;
			$postdata	 .= "PUC ->" . $pollution_certificate . " - " . $pollution_certificate_tmp;
			$postdata	 .= "Registration ->" . $reg_certificate . " - " . $reg_certificate_tmp;
			$postdata	 .= "Permit ->" . $permit_certificate . " - " . $permit_certificate_tmp;
			$postdata	 .= "Fitness ->" . $fitness_certificate . " - " . $fitness_certificate_tmp;
			Logger::create("POST DATA  ===========> : " . $postdata, CLogger::LEVEL_TRACE);
			$checkAccess = Yii::app()->user->checkAccess('vehicleEdit');
			if ($checkAccess)
			{
				try
				{

					if ($insurance != '')
					{

						$type	 = VehicleDocs::model()->getDocType(1);
						$result1 = $this->saveVehicleImage($insurance, $insurance_tmp, $vhc_id, $type);
						$path1	 = str_replace("\\", "\\\\", $result1['path']);
						$modeld	 = new VehicleDocs();
						$success = $modeld->saveDocument($vhc_id, $path1, $userInfo, 1);
						$errors	 = [];
						if ($success)
						{
							Logger::create('SUCCESS =====> : ' . "CAR insurance Id:" . $vhc_id . " - " . $path1, CLogger::LEVEL_INFO);
						}
					}
					if ($front_plate != '')
					{
						$type	 = VehicleDocs::model()->getDocType(2);
						$result1 = $this->saveVehicleImage($front_plate, $front_plate_tmp, $vhc_id, $type);
						$path1	 = str_replace("\\", "\\\\", $result1['path']);
						$modeld	 = new VehicleDocs();
						$success = $modeld->saveDocument($vhc_id, $path1, $userInfo, 2);
						$errors	 = [];
						if ($success)
						{
							Logger::create('SUCCESS =====> : ' . "CAR front license Id:" . $vhc_id . " - " . $path1, CLogger::LEVEL_INFO);
						}
					}
					if ($rear_plate != '')
					{
						$type	 = VehicleDocs::model()->getDocType(3);
						$result1 = $this->saveVehicleImage($rear_plate, $rear_plate_tmp, $vhc_id, $type);
						$path1	 = str_replace("\\", "\\\\", $result1['path']);
						$modeld	 = new VehicleDocs();
						$success = $modeld->saveDocument($vhc_id, $path1, $userInfo, 3);
						$errors	 = [];
						if ($success)
						{
							Logger::create('SUCCESS =====> : ' . "CAR rear license Id:" . $vhc_id . " - " . $path1, CLogger::LEVEL_INFO);
						}
					}
					if ($pollution_certificate != '')
					{
						$type	 = VehicleDocs::model()->getDocType(4);
						$result1 = $this->saveVehicleImage($pollution_certificate, $pollution_certificate_tmp, $vhc_id, $type);
						$path1	 = str_replace("\\", "\\\\", $result1['path']);
						$modeld	 = new VehicleDocs();
						$success = $modeld->saveDocument($vhc_id, $path1, UserInfo::getInstance(), 4);
						$errors	 = [];
						if ($success)
						{
							Logger::create('SUCCESS =====> : ' . "CAR PUC Id:" . $vhc_id . " - " . $path1, CLogger::LEVEL_INFO);
						}
					}
					if ($reg_certificate != '')
					{
						$type	 = VehicleDocs::model()->getDocType(5);
						$result1 = $this->saveVehicleImage($reg_certificate, $reg_certificate_tmp, $vhc_id, $type);
						$path1	 = str_replace("\\", "\\\\", $result1['path']);
						$modeld	 = new VehicleDocs();
						$success = $modeld->saveDocument($vhc_id, $path1, UserInfo::getInstance(), 5);
						$errors	 = [];
						if ($success)
						{
							Logger::create('SUCCESS =====> : ' . "CAR registration certificate Id:" . $vhc_id . " - " . $path1, CLogger::LEVEL_INFO);
						}
					}
					if ($permit_certificate != '')
					{
						$type	 = VehicleDocs::model()->getDocType(6);
						$result1 = $this->saveVehicleImage($permit_certificate, $permit_certificate_tmp, $vhc_id, $type);
						$path1	 = str_replace("\\", "\\\\", $result1['path']);
						$modeld	 = new VehicleDocs();
						$success = $modeld->saveDocument($vhc_id, $path1, UserInfo::getInstance(), 6);
						$errors	 = [];
						if ($success)
						{
							Logger::create('SUCCESS =====> : ' . "CAR permit Id:" . $vhc_id . " - " . $path1, CLogger::LEVEL_INFO);
						}
					}
					if ($fitness_certificate != '')
					{
						$type	 = VehicleDocs::model()->getDocType(7);
						$result1 = $this->saveVehicleImage($fitness_certificate, $fitness_certificate_tmp, $vhc_id, $type);
						$path1	 = str_replace("\\", "\\\\", $result1['path']);
						$modeld	 = new VehicleDocs();
						$success = $modeld->saveDocument($vhc_id, $path1, UserInfo::getInstance(), 7);
						$errors	 = [];
						if ($success)
						{
							Logger::create('SUCCESS =====> : ' . "CAR fitness Id:" . $driverId . " - " . $path1, CLogger::LEVEL_INFO);
						}
					}
				}
				catch (Exception $e)
				{
					Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
					Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
					throw $e;
				}
			}
			else
			{
				$success = false;
				$errors	 = 'You do not have privilage to add/edit vehicle.';
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'vhc_id'	 => $vhc_id,
				),
			]);
		});
	}

	/**
	 * @deprecated since version 13-09-2019
	 * This function has been deprecated and the new function name: actionAddType()
	 */
	public function actionAddtypeOld()
	{

		$this->pageTitle = "Add Vehicle Model";
		$vehicleid		 = Yii::app()->request->getParam('vhtid');
		$oldData		 = false;
		if ($vehicleid > 0)
		{
			$ftype					 = 'Modify';
			$model					 = VehicleTypes::model()->findByPk($vehicleid);
			$oldData				 = $model->attributes;
			$model->vht_fuel_type	 = $model->vht_fuel_type;
			$this->pageTitle		 = "Edit Vehicle Model";
		}
		else
		{
			$ftype	 = 'Add';
			$model	 = new VehicleTypes;
		}

		if (isset($_REQUEST['VehicleTypes']))
		{
			echo print_r($_POST, true);
			exit;
			$arr1				 = Yii::app()->request->getParam('VehicleTypes');
			$model->attributes	 = $arr1;
			$newData			 = $model->attributes;
			if ($model->scenario == 'insert' || $model->scenario == 'update')
			{
				if ($model->validate())
				{
					if ($model->scenario == 'update')
					{
						$model->vht_log = $model->addLog($oldData, $newData);
					}
					$chkvalue				 = $arr1['vht_fuel_type'];
					$model->vht_fuel_type	 = $chkvalue;
					$model->vht_parent_id	 = $arr1['vht_car_type'];
					$model->save();
					if ($model->scenario == 'insert')
					{


						$status = "added";
					}
					else if ($model->scenario == 'insert')
					{
						$status = "updated";
					}
					$this->redirect(array('typelist'));
				}
			}
		}
		$this->render('addtype', array('model' => $model, 'isNew' => $ftype, 'post' => $_POST));
	}

	/**
	 * This function is used for adding vehicle model types
	 */
	public function actionAddType()
	{

		$pagetitle		 = "Modify Vehicle Model";
		$ftype			 = 'Modify';
		$vehicleTypeId	 = Yii::app()->request->getParam("vhtid");
		$model			 = VehicleTypes::model()->findByPk($vehicleTypeId);

		$oldData = false;
		if ($model == "")
		{
			$model		 = new VehicleTypes();
			$pagetitle	 = "Add Vehicle Model";
			$ftype		 = 'Add';
		}
		else
		{
			$oldData = $model->attributes;
		}
		$this->pageTitle = $pagetitle;

		if (isset($_REQUEST['VehicleTypes']))
		{
			$model->attributes	 = Yii::app()->request->getParam('VehicleTypes');
			$carType			 = Yii::app()->request->getParam('VehicleTypes')["carType"];
			$newData			 = $model->attributes;
			$result				 = CActiveForm::validate($model);
			$transaction		 = DBUtil::beginTransaction();
			if ($result == '[]' && $carType != '')
			{
				try
				{
					$createVehicleType = $model->create($oldData, $newData, $carType);
					DBUtil::commitTransaction($transaction);
				}
				catch (Exception $e)
				{
					echo json_encode(['success'	 => false,
						'errors'	 => [
							'code'		 => 2,
							'message'	 => (!is_array($e->getMessage())) ? trim($e->getMessage(), '"') : $e->getMessage()
						]
					]);
					DBUtil::rollbackTransaction($transaction);
					Yii::app()->end();
				}
				$this->redirect(array('typelist'));
			}
			else
			{
				$return = ['success' => false, 'errors' => CJSON::decode($result)];
			}
		}
		$this->render("addtype", array("model" => $model, "isNew" => $ftype, "post" => $_POST));
	}

	public function actionAdd()
	{
		$this->pageTitle = "Edit Vehicle";
		$vehicleId		 = Yii::app()->request->getParam('veditid');
		$model			 = Vehicles::model()->findByPk($vehicleId);
		$oldDocsData	 = array();
		$newDocsData	 = array();
		$vhcLog			 = 0;
		$request		 = Yii::app()->request;
		if ($model == '')
		{
			$this->pageTitle = "Add Vehicle";
			$model			 = new Vehicles();
			$isNew			 = true;
		}
		else
		{
			$modelVendor = Vendors::model()->findByPk($model->vhc_vendor_id1);
			if ($modelVendor != '')
			{
				$model->vnd_name = $modelVendor->vnd_name;
			}
			$isNew	 = false;
			$vhcLog	 = 1;
		}
		$model->scenario = 'insertAdmin';
		if ($request->getPost('Vehicles'))
		{

			$arr1					 = Yii::app()->request->getParam('Vehicles');
			$oldData				 = $model->attributes;
			$model->oldAttributes	 = $model->attributes;
			if ($arr1['vhc_id'] > 0)
			{
				$model			 = Vehicles::model()->findById($arr1['vhc_id']);
				$model->scenario = 'updateAdmin';
			}
			$model->attributes = array_filter($arr1);
			if ($request->getPost('Vehicles')['vhc_is_attached'][0] != 1)
			{
				$model->vhc_is_attached = 0;
			}
			else
			{
				$model->vhc_is_attached = 1;
			}
			if ($request->getPost('Vehicles')['vhc_is_commercial'][0] != 1)
			{
				$model->vhc_is_commercial = 0;
			}
			else
			{
				$model->vhc_is_commercial = 1;
			}
			if ($request->getPost('Vehicles')['isPartitioned'][0] != 1)
			{
				$model->isPartitioned = 0;
			}
			else
			{
				$model->isPartitioned = 1;
			}
			$model->vhcStat->vhs_is_partition = $model->isPartitioned;
			if ($request->getPost('Vehicles')['isBoostVerify'][0] != 1)
			{
				$model->isBoostVerify = 0;
			}
			else
			{
				$model->isBoostVerify = 1;
			}
			$model->vhcStat->vhs_boost_verify = $model->isBoostVerify;
			if ($model->vhcStat != '')
			{
				$model->vhcStat->save();
			}

			$model->vhc_active = 1;

			$uploadedFile1	 = CUploadedFile::getInstance($model, "vhc_insurance_proof");
			$uploadedFile2	 = CUploadedFile::getInstance($model, "vhc_front_plate");
			$uploadedFile3	 = CUploadedFile::getInstance($model, "vhc_rear_plate");
			$uploadedFile4	 = CUploadedFile::getInstance($model, "vhc_pollution_certificate");
			$uploadedFile5	 = CUploadedFile::getInstance($model, "vhc_reg_certificate");
			$uploadedFile6	 = CUploadedFile::getInstance($model, "vhc_permits_certificate");
			$uploadedFile7	 = CUploadedFile::getInstance($model, "vhc_fitness_certificate");
			$uploadedFile8	 = CUploadedFile::getInstance($model, "vhc_back_reg_certificate");
			$transaction	 = Yii::app()->db->beginTransaction();
			if ($model->validate())
			{
				try
				{
					$model->attributes			 = array_filter($_POST['Vehicles']);
					$model->vhc_owned_or_rented	 = $_POST['Vehicles']['vhc_owned_or_rented'];
					if ($request->getPost('Vehicles')['vhc_is_attached'][0] != 1)
					{
						$model->vhc_is_attached = 0;
					}
					else
					{
						$model->vhc_is_attached = 1;
					}
					if ($request->getPost('Vehicles')['vhc_is_commercial'][0] != 1)
					{
						$model->vhc_is_commercial = 0;
					}
					else
					{
						$model->vhc_is_commercial = 1;
					}

					if ($request->getPost('Vehicles')['vhc_approved'][0] != 1)
					{
						$model->vhc_approved = 2;
					}
					else
					{
						$model->vhc_approved	 = 1;
						$model->vhc_approved_by	 = UserInfo::getInstance()->getUserId();
					}

					if ($request->getPost('Vehicles')['vhc_is_uber_approved'][0] != 1)
					{
						$model->vhc_is_uber_approved = 0;
					}
					else
					{
						$model->vhc_is_uber_approved = 1;
					}

					////////////

					if ($request->getPost('Vehicles')['vhc_has_cng'][0] != 1)
					{
						$model->vhc_has_cng = 0;
					}
					else
					{
						$model->vhc_has_cng = 1;
					}

					if ($request->getPost('Vehicles')['vhc_has_rooftop_carrier'][0] != 1)
					{
						$model->vhc_has_rooftop_carrier = 0;
					}
					else
					{
						$model->vhc_has_rooftop_carrier = 1;
					}
					if ($request->getPost('Vehicles')['vhc_has_electric'][0] != 1)
					{
						$model->vhc_has_electric = 0;
					}
					else
					{
						$model->vhc_has_electric = 1;
					}

					$tempInsuranceApprove = 0;
					if ($request->getPost('Vehicles')['vhc_temp_insurance_approved'][0] == 1)
					{
						$tempInsuranceApprove = 1;
					}
					$tempRegCertificateApprove = 0;
					if ($request->getPost('Vehicles')['vhc_temp_reg_certificate_approved'][0] == 1)
					{
						$tempRegCertificateApprove = 1;
					}
					if ($request->getPost('Vehicles')['vhc_back_temp_reg_certificate_approved'][0] == 1)
					{
						$tempRegCertificateApprove = 1;
					}
					/* if($request->getPost('Vehicles')['vhc_reg_owner'][0] != '' )
					  {
					  $ownerName = explode(" ",$_POST['Vehicles']['vhc_reg_owner']);
					  $model->vhc_reg_owner = $ownerName[0];
					  $model->vhc_reg_owner_lname = $ownerName[1];
					  } */


					$model->vhc_active		 = 1;
					$model->vhc_modified_at	 = new CDbExpression('NOW()');
					if (isset($model->vhc_trip_type) && $model->vhc_trip_type != '')
					{
						$model->vhc_trip_type = implode(',', $model->vhc_trip_type);
					}
					$success	 = $model->save();
					$userInfo	 = UserInfo::getInstance();

					if ($success)
					{

						$codeArr = Filter::getCodeById($model->vhc_id, "car");
						if ($codeArr['success'] == 1)
						{
							$model->vhc_code = $codeArr['code'];
							$model->save();
						}
						$model = Vehicles::model()->findByPk($model->vhc_id);
						if ($arr1['vhc_vendor_id1'] > 0)
						{
							$model				 = Vehicles::model()->findByPk($model->vhc_id);
							$data				 = ['vendor' => $arr1['vhc_vendor_id1'], 'vehicle' => $model->vhc_id];
							$linked				 = VendorVehicle::model()->checkAndSave($data);
							$vendorUnlink		 = VendorVehicle::model()->unlinkOther($model->vhc_id, $arr1['vhc_vendor_id1']); //inactive other vendor car if new vendor added
							$vendorVehicleModel	 = VendorVehicle::model()->findByVndVhcId($arr1['vhc_vendor_id1'], $model->vhc_id);
							if ($vendorVehicleModel)
							{

								if ($request->getPost('Vehicles')['vhc_owned_or_rented'] != "")
								{
									$vendorVehicleModel->vvhc_owner_or_not = $request->getPost('Vehicles')['vhc_owned_or_rented'];
								}
								$vendorVehicleModel->vvhc_active = 1;
								$vendorVehicleModel->save();
							}
						}
						$folderName = "vehicles";
						if ($uploadedFile1 != '')
						{
							$type	 = VehicleDocs::model()->getDocType(1);
							$result	 = VehicleDocs::model()->saveImage($uploadedFile1, $model->vhc_id, $type, NULL);
							$modeld	 = new VehicleDocs();
							$success = $modeld->saveDocumentNew($model->vhc_id, $result['path'], $userInfo, 1, $tempInsuranceApprove);
						}
						if ($uploadedFile2 != '')
						{
							$type	 = VehicleDocs::model()->getDocType(2);
							$result	 = VehicleDocs::model()->saveImage($uploadedFile2, $model->vhc_id, $type, NULL);
							$modeld	 = new VehicleDocs();
							$success = $modeld->saveDocumentNew($model->vhc_id, $result['path'], $userInfo, 2);
						}
						if ($uploadedFile3 != '')
						{
							$type	 = VehicleDocs::model()->getDocType(3);
							$result	 = VehicleDocs::model()->saveImage($uploadedFile3, $model->vhc_id, $type, NULL);
							$modeld	 = new VehicleDocs();
							$success = $modeld->saveDocumentNew($model->vhc_id, $result['path'], $userInfo, 3);
						}
						if ($uploadedFile4 != '')
						{
							$type	 = VehicleDocs::model()->getDocType(4);
							$result	 = VehicleDocs::model()->saveImage($uploadedFile4, $model->vhc_id, $type, NULL);
							$modeld	 = new VehicleDocs();
							$success = $modeld->saveDocument($model->vhc_id, $result['path'], $userInfo, 4);
						}
						if ($uploadedFile5 != '')
						{
							$type	 = VehicleDocs::model()->getDocType(5);
							$result	 = VehicleDocs::model()->saveImage($uploadedFile5, $model->vhc_id, $type, NULL);
							$modeld	 = new VehicleDocs();
							$success = $modeld->saveDocument($model->vhc_id, $result['path'], $userInfo, 5, $tempRegCertificateApprove);
						}
						if ($uploadedFile6 != '')
						{
							$type	 = VehicleDocs::model()->getDocType(6);
							$result	 = VehicleDocs::model()->saveImage($uploadedFile6, $model->vhc_id, $type, NULL);
							$modeld	 = new VehicleDocs();
							$success = $modeld->saveDocument($model->vhc_id, $result['path'], $userInfo, 6);
						}
						if ($uploadedFile7 != '')
						{
							$type	 = VehicleDocs::model()->getDocType(7);
							$result	 = VehicleDocs::model()->saveImage($uploadedFile7, $model->vhc_id, $type, NULL);
							$modeld	 = new VehicleDocs();
							$success = $modeld->saveDocument($model->vhc_id, $result['path'], $userInfo, 7);
						}
						if ($uploadedFile8 != '')
						{
							$type	 = VehicleDocs::model()->getDocType(13);
							$result	 = VehicleDocs::model()->saveImage($uploadedFile8, $model->vhc_id, $type, NULL);
							$modeld	 = new VehicleDocs();
							$success = $modeld->saveDocument($model->vhc_id, $result['path'], $userInfo, 13);
						}

						$newData				 = $model->attributes;
						$getOldDifferenceDocs	 = array_diff_assoc($oldDocsData, $newDocsData);
						if ($oldData['vhc_approved'] == 1)
						{
							$model->vhc_approved = 1;
						}
						if ($model->validate())
						{
							$model->vhc_modified_at	 = new CDbExpression('NOW()');
							$success				 = $model->save();

							if ($model->vhc_id != '')
							{
								$getOldDifference	 = array_diff_assoc($oldData, $newData);
								$changesForLog		 = "<br> Old Values: " . $this->getModificationMSG($getOldDifference, false);
								$changesForLog		 .= "<br>" . $this->getModificationMSG($getOldDifferenceDocs, false);
								$desc				 = "Cab modified | ";
								$desc				 .= $changesForLog;
								$event_id			 = VehiclesLog::VEHICLE_MODIFIED;
							}
							else
							{
								$desc		 = "Vehicle created |";
								$event_id	 = VehiclesLog::VEHICLE_CREATED;
								//check and insert in vehicle stat table
								$linked		 = VehicleStats::model()->checkAndSave($model->vhc_id);
							}
							VehiclesLog::model()->createLog($model->vhc_id, $desc, $userInfo, $event_id, false, false);
							if (!$success)
							{
								throw new Exception("Could not update Vehicle Log. (" . json_encode($model->getErrors()) . ")");
							}
							$model = Vehicles::model()->findByPk($model->vhc_id);
						}
					}
					if ($success && $model->vhc_id != '')
					{
						BookingCab::model()->updateVendorPayment($flag = 1, $model->vhc_id);
					}
					$transaction->commit();
				}
				catch (Exception $e)
				{
					$model->addError("vhc_id", $e->getMessage());
					$transaction->rollback();
				}
			}
			if ($success)
			{
				if ($arr1['vhc_vendor_id1'] > 0)
				{
					VendorStats::model()->updateCarTypeCount($arr1['vhc_vendor_id1']);
				}
				$this->redirect(array('list'));
				Yii::app()->user->setFlash('success', 'Vehicle details updated successfully');
			}
			else
			{
				Yii::app()->user->setFlash('error', 'Vehicle details update not success<br/>');
				foreach ($model->getErrors() as $attribute => $errors)
				{
					foreach ($errors as $value)
					{
						Yii::app()->user->setFlash('error', 'Vehicle details update not success<br/>' . $value . "<br/>");
					}
				}
			}
		}
		$docModel = VehicleDocs::model()->findAllActiveDocByVhcId($model->vhc_id);

		$model->vehicleDocs = $docModel;
		//$model->vhc_type = $model->vhcType->vht_car_type; //Commented out this line.
		$this->render('form', array('model' => $model, 'isNew' => $isNew));
	}

	public function actionAddcategory()
	{
		$category_id = Yii::app()->request->getParam('category_id');
		$model		 = VehicleCategory::model()->findByPk($category_id);
		if ($model == '')
		{
			$category_id = 0;
			$model		 = new VehicleCategory('save');
		}
		if (isset($_POST['VehicleCategory']))
		{
			$arrRequest			 = $_POST['VehicleCategory'];
			unset($arrRequest['vct_image']);
			unset($arrRequest['vct_id']);
			$model->attributes	 = $arrRequest;
			$result				 = VehicleCategory::model()->exists('(vct_label = :vct_label OR vct_desc = :vct_desc) AND (vct_id !=' . $category_id . ')',
					array(':vct_label' => $model->vct_label, ':vct_desc' => $model->vct_desc)
			);

			if (!$result)
			{
//				if($model->vct_image==""){
//	                     unset($model->vct_image);
//				}
				$imageFile = CUploadedFile::getInstance($model, "vct_image");
				if ($imageFile == "" && $model->vct_image == "")
				{
					$model->addError('vct_image', 'Please upload image.');
					goto last;
				}
				if (!$model->save())
				{
					goto last;
				}
				$id = $model->vct_id; //Yii::app()->db->getLastInsertID();

				$this->saveProfileImage($id, $imageFile);
				if ($category_id == 0)
				{
					Yii::app()->user->setFlash('success', "Category added successfully.");
				}
				else
				{
					Yii::app()->user->setFlash('success', "Category Updated Successfully.");
				}
			}
			else
			{
				$model->addError('vct_label', 'label already exists');
			}
		}
		last:
		$this->render('addcategory', array('model' => $model));
	}

	/**
	 * @deprecated since version 16-09-2019
	 * New Function Name: actionCategoryList
	 *  
	 * @param type $qry
	 */
	public function actionCategorylistOld($qry = [])
	{
		$this->pageTitle = "vehicle category";
		$pageSize		 = Yii::app()->params['listPerPage'];

		$model = new VehicleCategory('search');
		if (isset($_REQUEST['VehicleCategory']))
		{
			$model->attributes = Yii::app()->request->getParam('VehicleCategory');
		}
		$dataProvider = $model->getList();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('category_list', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'qry'			 => $qry));
	}

	/**
	 * This function is being used for fetching the vehicle category list and search
	 */
	public function actionCategorylist()
	{
		/**
		 * Case 1: Fetch all data when page loads
		 * Case 2: Fetch all data based on search parameter
		 */
		$this->pageTitle = "Vehicle Category List"; //Sets the page title
		$pageSize		 = Yii::app()->params["listPerPage"];
		$requestInstance = Yii::app()->request;

		$vehicleCategoryListmodel = new VehicleCategory();

		if (empty($requestInstance->getParam("VehicleCategory")))
		{
			goto skipToDefault;
		}

		$receivedVehicleTypeDetails = $requestInstance->getParam("VehicleCategory");

		$requestData = array
			(
			"vehicleCategoryLabel"	 => $receivedVehicleTypeDetails["vct_label"],
			"vehicleCategoryDesc"	 => $receivedVehicleTypeDetails["vct_desc"]
		);

		$dataProvider = VehicleCategory::fetchVehicleCategoryDetalis($requestData); //Fetches the data based on search params
		goto skipToSearch;

		skipToDefault:
		$dataProvider = VehicleCategory::fetchVehicleCategoryDetalis(); //Fetches all the data

		skipToSearch:
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);

		$this->render("category_list", array
			(
			"model"			 => $vehicleCategoryListmodel,
			"dataProvider"	 => $dataProvider
		));
	}

	public function actionViewtypes()
	{
		$vct_id = Yii::app()->request->getParam('vct_id');

		$model = new VehicleTypes();
		if (isset($_REQUEST['VehicleTypes']))
		{
			$model->attributes = $_REQUEST['VehicleTypes'];
		}
		$dataProvider							 = $model->getByVehicleCategoryId($vct_id);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);

		$this->renderPartial('viewvehicletypes', array('model' => $model, 'dataProvider' => $dataProvider), false, true);
	}

	public function actionChangevctstatus()
	{
		$category_id		 = Yii::app()->request->getParam('vct_id');
		$is_category_active	 = Yii::app()->request->getParam('vct_active');

		$model = VehicleCategory::model()->resetScope()->findByPk($category_id);

		/* @var $logModel DriversLog */
		$logModel = new VehicleCategory();

		$success = false;

		switch ($is_category_active)
		{
			case 0:
				$model->vct_active = 1;

				break;
			case 1:
				$model->vct_active = 0;
				break;
		}
		if ($model->save())
		{
			$success = true;
		}
		else
		{
			$success = false;
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => true];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	public function saveProfileImage($category_id, $imageFile)
	{
		$model			 = VehicleCategory::model()->findByPk($category_id);
		$categoryImage	 = $imageFile;
		if ($categoryImage != "")
		{
			$path				 = VehicleCategory::model()->uploadDocument($category_id, 'category_image', $categoryImage, '');
			$model->vct_image	 = $path[0];
			$model->update();
		}
	}

	public function actionServiceclassType()
	{
		$service_id	 = Yii::app()->request->getParam('service_id');
		$model		 = ServiceClass::model()->findByPk($service_id);
		if ($model == '')
		{
			$service_id	 = 0;
			$model		 = new ServiceClass('save');
		}
		if (isset($_POST['ServiceClass']))
		{
			$arrRequest = $_POST['ServiceClass'];

			unset($arrRequest['scc_id']);
			$model->attributes			 = $arrRequest;
			$model->scc_markup_type		 = $arrRequest['scc_markup_type'];
			$model->scc_markup			 = $arrRequest['scc_markup'];
			$model->scc_model_year		 = $arrRequest['scc_model_year'];
			$model->scc_is_cng			 = $arrRequest['scc_is_cng'];
			$model->scc_is_petrol_diesel = $arrRequest['scc_is_petrol_diesel'];
			$result						 = ServiceClass::model()->exists('(scc_label = :scc_label) AND (scc_id !=' . $service_id . ')',
					array(':scc_label' => $model->scc_label,)
			);

			if (!$result)
			{
//				if($model->vct_image==""){
//	                     unset($model->vct_image);
//				}
				if ($_POST['ServiceClass']['scc_is_cng'][0] != 1)
				{
					$model->scc_is_cng = 0;
				}
				else
				{
					$model->scc_is_cng = 1;
				}
				if ($_POST['ServiceClass']['scc_is_petrol_diesel'][0] != 1)
				{
					$model->scc_is_petrol_diesel = 0;
				}
				else
				{
					$model->scc_is_petrol_diesel = 1;
				}
				if (!$model->save())
				{
					goto last;
				}
				$id = $model->scc_id; //Yii::app()->db->getLastInsertID();				
				if ($service_id == 0)
				{
					Yii::app()->user->setFlash('success', "Service class added successfully.");
				}
				else
				{
					Yii::app()->user->setFlash('success', "Service class updated successfully.");
				}
			}
			else
			{
				$model->addError('scc_label', 'label already exists');
			}
		}
		last:
		$this->render('addserviceclass', array('model' => $model));
	}

	public function actionServiceclasslist($qry = [])
	{
		$this->pageTitle = "service class list";
		$pageSize		 = Yii::app()->params['listPerPage'];

		$model = new ServiceClass('search');
		if (isset($_REQUEST['ServiceClass']))
		{
			$model->attributes = Yii::app()->request->getParam('ServiceClass');
		}
		$dataProvider = $model->getList();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('service_class_list', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'qry'			 => $qry));
	}

	public function actionChangeservicestatus()
	{
		$service_id			 = Yii::app()->request->getParam('scc_id');
		$is_service_active	 = Yii::app()->request->getParam('scc_active');

		$model = ServiceClass::model()->resetScope()->findByPk($service_id);

		/* @var $logModel DriversLog */
		$logModel = new ServiceClass();

		$success		 = false;
		$model->scenario = 'statuschange';

		switch ($is_service_active)
		{
			case 0:
				$model->scc_active = 1;

				break;
			case 1:
				$model->scc_active = 0;
				break;
		}
		if ($model->save())
		{
			$success = true;
		}
		else
		{
			$success = false;
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => true];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	public function uploadMultifile($model, $attr, $path)
	{
		/*
		 * path when uploads folder is on site root.
		 * $path='/uploads/doc/'
		 */
		if ($sfile = CUploadedFile::getInstances($model, $attr))
		{
			foreach ($sfile as $i => $file)
			{
				// $formatName=time().$i.'.'.$file->getExtensionName();
				$fileName	 = "{$sfile[$i]}";
				$formatName	 = time() . $i . '_' . $fileName;
				$file->saveAs(Yii::app()->basePath . $path . $formatName);
				$ffile[$i]	 = $formatName;
			}
			return ($ffile);
		}
	}

	public function actionUpdateVehicleDoc()
	{
		$vhd_id		 = Yii::app()->request->getParam('vhd_id');
		$vhd_status	 = Yii::app()->request->getParam('vhd_status');
		if ($vhd_status == 1 || $vhd_status == 2)
		{
			$modeld							 = VehicleDocs::model()->findByPk($vhd_id);
			$modeld->vhd_status				 = $vhd_status;
			$modeld->vhd_approve_by			 = ($vhd_status == 1) ? Yii::app()->user->getId() : NULL;
			$modeld->vhd_appoved_at			 = ($vhd_status == 1) ? date("Y-m-d H:i:s") : NULL;
			$modeld->vhd_temp_approved		 = NULL;
			$modeld->vhd_temp_approved_at	 = NULL;
			$modeld->save();
			$retrunVal						 = '';
			$event_id						 = 0;
			switch ($modeld->vhd_type)
			{
				case 1:
					$fileType = "#insurance";
					if ($modeld->vhd_status == 1)
					{
						$event_id	 = VehiclesLog::VEHICLE_INSURANCE_APPROVE;
						$desc		 = VehiclesLog::model()->getEventByEventId(VehiclesLog::VEHICLE_INSURANCE_APPROVE);
					}
					else if ($modeld->vhd_status == 2)
					{
						$event_id	 = VehiclesLog::VEHICLE_INSURANCE_REJECT;
						$desc		 = VehiclesLog::model()->getEventByEventId(VehiclesLog::VEHICLE_INSURANCE_REJECT);
					}
					break;
				case 2:
					$fileType = "#frontLicense";
					if ($modeld->vhd_status == 1)
					{
						$event_id	 = VehiclesLog::VEHICLE_FRONT_LICENSE_APPROVE;
						$desc		 = VehiclesLog::model()->getEventByEventId(VehiclesLog::VEHICLE_FRONT_LICENSE_APPROVE);
					}
					else if ($modeld->vhd_status == 2)
					{
						$event_id	 = VehiclesLog::VEHICLE_FRONT_LICENSE_REJECT;
						$desc		 = VehiclesLog::model()->getEventByEventId(VehiclesLog::VEHICLE_FRONT_LICENSE_REJECT);
					}
					break;
				case 3:
					$fileType = "#rearLicense";
					if ($modeld->vhd_status == 1)
					{
						$event_id	 = VehiclesLog::VEHICLE_REAR_LICENSE_APPROVE;
						$desc		 = VehiclesLog::model()->getEventByEventId(VehiclesLog::VEHICLE_REAR_LICENSE_APPROVE);
					}
					else if ($modeld->vhd_status == 2)
					{
						$event_id	 = VehiclesLog::VEHICLE_REAR_LICENSE_REJECT;
						$desc		 = VehiclesLog::model()->getEventByEventId(VehiclesLog::VEHICLE_REAR_LICENSE_REJECT);
					}
					break;
				case 4:
					$fileType = "#pollution";
					if ($modeld->vhd_status == 1)
					{
						$event_id	 = VehiclesLog::VEHICLE_PUC_APPROVE;
						$desc		 = VehiclesLog::model()->getEventByEventId(VehiclesLog::VEHICLE_PUC_APPROVE);
					}
					else if ($modeld->vhd_status == 2)
					{
						$event_id	 = VehiclesLog::VEHICLE_PUC_REJECT;
						$desc		 = VehiclesLog::model()->getEventByEventId(VehiclesLog::VEHICLE_PUC_REJECT);
					}
					break;
				case 5:
					$fileType = "#registration";
					if ($modeld->vhd_status == 1)
					{
						$event_id	 = VehiclesLog::VEHICLE_REGISTRATION_APPROVE;
						$desc		 = VehiclesLog::model()->getEventByEventId(VehiclesLog::VEHICLE_REGISTRATION_APPROVE);
					}
					else if ($modeld->vhd_status == 2)
					{
						$event_id	 = VehiclesLog::VEHICLE_REGISTRATION_REJECT;
						$desc		 = VehiclesLog::model()->getEventByEventId(VehiclesLog::VEHICLE_REGISTRATION_REJECT);
					}
					break;
				case 6:
					$fileType = "#commercialPermit";
					if ($modeld->vhd_status == 1)
					{
						$event_id	 = VehiclesLog::VEHICLE_PERMITS_APPROVE;
						$desc		 = VehiclesLog::model()->getEventByEventId(VehiclesLog::VEHICLE_PERMITS_APPROVE);
					}
					else if ($modeld->vhd_status == 2)
					{
						$event_id	 = VehiclesLog::VEHICLE_PERMITS_REJECT;
						$desc		 = VehiclesLog::model()->getEventByEventId(VehiclesLog::VEHICLE_PERMITS_REJECT);
					}
					break;
				case 7:
					$fileType = "#fitnessCertificate";
					if ($modeld->vhd_status == 1)
					{
						$event_id	 = VehiclesLog::VEHICLE_FITNESS_APPROVE;
						$desc		 = VehiclesLog::model()->getEventByEventId(VehiclesLog::VEHICLE_FITNESS_APPROVE);
					}
					else if ($modeld->vhd_status == 2)
					{
						$event_id	 = VehiclesLog::VEHICLE_FITNESS_REJECT;
						$desc		 = VehiclesLog::model()->getEventByEventId(VehiclesLog::VEHICLE_PERMITS_REJECT);
					}
					break;
				case 13:
					$fileType = "#registrationBack";
					if ($modeld->vhd_status == 1)
					{
						$event_id	 = VehiclesLog::VEHICLE_REGISTRATION_APPROVE;
						$desc		 = VehiclesLog::model()->getEventByEventId(VehiclesLog::VEHICLE_REGISTRATION_APPROVE);
					}
					else if ($modeld->vhd_status == 2)
					{
						$event_id	 = VehiclesLog::VEHICLE_REGISTRATION_REJECT;
						$desc		 = VehiclesLog::model()->getEventByEventId(VehiclesLog::VEHICLE_REGISTRATION_REJECT);
					}
					break;
			}
			$userInfo	 = UserInfo::getInstance();
			VehiclesLog::model()->createLog($modeld->vhd_vhc_id, $desc, $userInfo, $event_id, false, false);
			$retrunVal	 = $fileType . "~" . $modeld->vhd_status . "~" . $modeld->vhd_remarks;
		}
		else
		{
			$modeld = VehicleDocs::model()->findByPk($vhd_id);
			switch ($modeld->vhd_type)
			{
				case 1:
					$fileType	 = "#insurance";
					break;
				case 2:
					$fileType	 = "#frontLicense";
					break;
				case 3:
					$fileType	 = "#rearLicense";
					break;
				case 4:
					$fileType	 = "#pollution";
					break;
				case 5:
					$fileType	 = "#registration";
					break;
				case 6:
					$fileType	 = "#commercialPermit";
					break;
				case 7:
					$fileType	 = "#fitnessCertificate";
					break;
				case 13:
					$fileType	 = "#registrationBack";
			}
			$retrunVal = $fileType . "~" . '3';
		}
		echo $retrunVal;
	}

	public function actionRejectVehicleDoc()
	{
		$vhd_id			 = Yii::app()->request->getParam('vhd_id');
		$vhd_status		 = Yii::app()->request->getParam('vhd_status');
		$success		 = false;
		/* @var $dmodel VehicleDocs */
		$dmodel			 = VehicleDocs::model()->findByPk($vhd_id);
		$model			 = new VehicleDocs();
		$model->scenario = 'reject';
		if (isset($_POST['VehicleDocs']))
		{
			$model->attributes	 = Yii::app()->request->getParam('VehicleDocs');
			$arr				 = $model->attributes;
			switch ($dmodel->vhd_type)
			{
				case 1:
					$event_id	 = VehiclesLog::VEHICLE_INSURANCE_REJECT;
					$fileType	 = "#insurance";
					break;
				case 2:
					$event_id	 = VehiclesLog::VEHICLE_FRONT_LICENSE_REJECT;
					$fileType	 = "#frontLicense";
					break;
				case 3:
					$event_id	 = VehiclesLog::VEHICLE_REAR_LICENSE_REJECT;
					$fileType	 = "#rearLicense";
					break;
				case 4:
					$event_id	 = VehiclesLog::VEHICLE_PUC_REJECT;
					$fileType	 = "#pollution";
					break;
				case 5:
					$event_id	 = VehiclesLog::VEHICLE_REGISTRATION_REJECT;
					$fileType	 = "#registration";
					break;
				case 6:
					$event_id	 = VehiclesLog::VEHICLE_PERMITS_REJECT;
					$fileType	 = "#commercialPermit";
					break;
				case 7:
					$event_id	 = VehiclesLog::VEHICLE_FITNESS_REJECT;
					$fileType	 = "#fitnessCertificate";
					break;
				case 13:
					$event_id	 = VehiclesLog::VEHICLE_REGISTRATION_REJECT;
					$fileType	 = "#registrationBack";
					break;
			}
			$dmodel->vhd_remarks			 = $arr['vhd_remarks'];
			$dmodel->vhd_status				 = $vhd_status;
			$dmodel->vhd_temp_approved		 = NULL;
			$dmodel->vhd_temp_approved_at	 = NULL;

			if ($dmodel->save())
			{
				$userInfo	 = UserInfo::getInstance();
				VehiclesLog::model()->createLog($dmodel->vhd_vhc_id, $arr['vhd_remarks'], $userInfo, $event_id, false, false);
				$success	 = true;
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				$remarks = '<i>' . $dmodel->vhd_remarks . '</i>';
				$data	 = ['success' => $success, 'file_type' => $fileType, 'status' => $dmodel->vhd_status, 'remarks' => $remarks];
				echo json_encode($data);
				Yii::app()->end();
			}
		}
		$this->renderPartial('rejectremarks', array('vhd_id'	 => $vhd_id,
			'vhd_status' => $vhd_status,
			'model'		 => $model,
			'dmodel'	 => $dmodel), false, true);
	}

	public function getModificationMSG($diff, $user = false)
	{
		$msg = '';
		if (count($diff) > 0)
		{
			if ($diff ['vhc_number'])
			{
				$msg .= ' Vehicle Number: ' . $diff['vhc_number'] . ',';
			}
			if ($diff ['vhc_year'])
			{
				$msg .= ' Vehicle Year: ' . $diff['vhc_year'] . ',';
			}
			if ($diff['vhc_has_cng'] === '1')
			{
				$cngStatus	 = 'YES';
				$msg		 .= ' Vehicle CNG: ' . $cngStatus . ',';
			}
			elseif ($diff['vhc_has_cng'] === '0')
			{
				$cngStatus	 = "NO";
				$msg		 .= ' Vehicle CNG: ' . $cngStatus . ',';
			}
			if ($diff['vhc_has_electric'] === '1')
			{
				$elctricStatus	 = 'YES';
				$msg			 .= ' Vehicle Elctric: ' . $elctricStatus . ',';
			}
			elseif ($diff['vhc_has_electric'] === '0')
			{
				$elctricStatus	 = "NO";
				$msg			 .= ' Vehicle Elctric: ' . $elctricStatus . ',';
			}

			if ($diff['vhc_has_rooftop_carrier'] === '1')
			{
				$roofTopCarrierStatus	 = 'YES';
				$msg					 .= ' Vehicle Rooftop Carrier: ' . $roofTopCarrierStatus . ',';
			}
			elseif ($diff['vhc_has_rooftop_carrier'] === '0')
			{
				$roofTopCarrierStatus	 = "NO";
				$msg					 .= ' Vehicle Rooftop Carrier: ' . $roofTopCarrierStatus . ',';
			}

			if ($diff['vhc_color'])
			{
				$msg .= ' Vehicle Color: ' . $diff['vhc_color'] . ',';
			}
			if ($diff['vhc_type_id'])
			{
				$vhtModel	 = VehicleTypes::model()->findByPk($diff['vhc_type_id']);
				$vehicle	 = ($vhtModel->vht_make . " " . $vhtModel->vht_model);
				$msg		 .= ' Vehicle Type: ' . $vehicle . ',';
			}
			if ($diff['vhc_is_attached'])
			{
				$exclusiveStatus = ($diff['vhc_is_attached'] == 1) ? 'Yes' : 'No';
				$msg			 .= ' Is exclusive to Gozo: ' . $exclusiveStatus . ',';
			}
			if ($diff['vhc_is_commercial'])
			{
				$commercialStatus	 = ($diff['vhc_is_commercial'] == 1) ? 'Yes' : 'No';
				$msg				 .= ' Is Commercial: ' . $commercialStatus . ',';
			}

			if ($diff ['vhc_insurance_exp_date'])
			{
				$msg .= ' Insurance exp date: ' . $diff['vhc_insurance_exp_date'] . ',';
			}
			if ($diff ['vhc_pollution_exp_date'])
			{
				$msg .= ' Pollution certificate exp date: ' . $diff['vhc_pollution_exp_date'] . ',';
			}
			if ($diff ['vhc_reg_exp_date'])
			{
				$msg .= ' Registration exp date: ' . $diff['vhc_reg_exp_date'] . ',';
			}
			if ($diff['vhc_trip_type'])
			{

				$msg .= ' Trip Type : ' . Vehicles::getType($diff['vhc_trip_type']) . ',';
			}

			if ($diff ['vhc_commercial_exp_date'])
			{
				$msg .= ' Commercial Permit exp date: ' . $diff['vhc_commercial_exp_date'] . ',';
			}
			if ($diff ['vhc_fitness_cert_end_date'])
			{
				$msg .= ' FITNESS exp date: ' . $diff['vhc_fitness_cert_end_date'] . ',';
			}
			if ($diff['vhc_approved'] <> '')
			{
				switch ($diff['vhc_approved'])
				{
					case 0;
						$approveStatus	 = 'Not Verified';
						break;
					case 1;
						$approveStatus	 = 'Approved';
						break;
					case 2;
						$approveStatus	 = 'Pending Approval';
						break;
					case 3;
						$approveStatus	 = 'Rejected';
						break;
				}
				//$approveStatus = ($diff['vhc_approved']==1) ? 'Yes':'No';
				$msg .= ' Is Approved: ' . $approveStatus . ',';
			}
			if ($diff['vhc_owned_or_rented'])
			{
				$ownedrentedStatus	 = ($diff['vhc_owned_or_rented'] == 1) ? ' Yes' : 'No';
				$msg				 .= ' Vehicle owned or rented: ' . $ownedrentedStatus . ',';
			}
			if ($diff['insuranceFile'])
			{
				$msg .= ' Insurance : ' . $diff['insuranceFile'] . ',';
			}
			if ($diff['frontLicenseFile'])
			{
				$msg .= ' Front License : ' . $diff['frontLicenseFile'] . ',';
			}
			if ($diff['rearLicenseFile'])
			{
				$msg .= ' Rear License : ' . $diff['rearLicenseFile'] . ',';
			}
			if ($diff['pollutionFile'])
			{
				$msg .= ' Pollution under control : ' . $diff['pollutionFile'] . ',';
			}
			if ($diff['registrationFile'])
			{
				$msg .= ' Registration certificate : ' . $diff['registrationFile'] . ',';
			}
			if ($diff['permitFile'])
			{
				$msg .= ' Commercial permits : ' . $diff['permitFile'] . ',';
			}
			if ($diff['fitnessFile'])
			{
				$msg .= ' Fitness certificate : ' . $diff['fitnessFile'] . ',';
			}

			$msg = rtrim($msg, ',');
		}
		return $msg;
	}

	public function uploadAttachments($uploadedFile, $type, $vehicleId, $folderName)
	{
		$fileName	 = $vehicleId . "-" . $type . "-" . date('YmdHis') . "." . pathinfo($uploadedFile, PATHINFO_EXTENSION);
		$dir		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';

		if (!is_dir($dir))
		{
			mkdir($dir);
		}

		$dirFolderName = $dir . DIRECTORY_SEPARATOR . Config::getServerID();
		if (!is_dir($dirFolderName))
		{
			mkdir($dirFolderName);
		}

		$dirFolderName .= DIRECTORY_SEPARATOR . $folderName;
		if (!is_dir($dirFolderName))
		{
			mkdir($dirFolderName);
		}
		$dirByVehicleId = $dirFolderName . DIRECTORY_SEPARATOR . $vehicleId;
		if (!is_dir($dirByVehicleId))
		{
			mkdir($dirByVehicleId);
		}


		$foldertoupload = $dirByVehicleId . DIRECTORY_SEPARATOR . $fileName;

		$extention = pathinfo($uploadedFile, PATHINFO_EXTENSION);
		if (strtolower($extention) == 'png' || strtolower($extention) == 'jpg' || strtolower($extention) == 'jpeg' || strtolower($extention) == 'gif')
		{
			Vehicles::model()->img_resize($uploadedFile->tempName, 1200, $dirByVehicleId . DIRECTORY_SEPARATOR, $fileName);
		}
		else
		{
			$uploadedFile->saveAs($foldertoupload);
		}

		file_put_contents(file_get_contents($uploadedFile->tempName), $foldertoupload);

		$path = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . Config::getServerID() . DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . $vehicleId . DIRECTORY_SEPARATOR . $fileName;

		return $path;
	}

	public function actionList($qry = [])
	{

		$this->pageTitle = 'Car List';

		/* @var $model Vehicles */
		$model = new Vehicles('search');

		$pageSize				 = Yii::app()->params['listPerPage'];
		$code					 = Yii::app()->request->getParam('code', '');
		$model->vhc_source		 = Yii::app()->request->getParam('source', '');
		$model->vhc_vendor_id1	 = Yii::app()->request->getParam('vnd', '');

		//	$model->vhc_trip_type    = '1';
		if ($model->vhc_source == 223)
		{
			$this->pageTitle = "Car List ( Cars ready for approval )";
		}
		$approve = Yii::app()->request->getParam('approve', '0');

		if ($approve > 0)
		{
			$model->vhc_approved = $approve;
		}
		if (isset($code) && $code != '')
		{
			$model->vhc_number = $code;
		}
		$model->vndlist = Yii::app()->request->getParam('vndlist', '');
		if ($model->vhc_vendor_id1 > 0 && $model->vndlist == 1)
		{
			$model->vhc_approved = '';
		}

		if ($_REQUEST['Vehicles'])
		{
			$arr				 = Yii::app()->request->getParam('Vehicles');
			$model->attributes	 = $arr;
			if (trim(Yii::app()->request->getParam('searchmarkvehicle')))
			{
				$qry['searchmarkvehicle'] = 1;
			}
			if (trim(Yii::app()->request->getParam('searchcngvehicle')))
			{
				$qry['searchcngvehicle'] = 1;
			}
			if (trim(Yii::app()->request->getParam('searchIsPartitioned')))
			{
				$qry['searchIsPartitioned'] = 1;
			}
			if (count($arr['vhc_trip_type']) > 0)
			{
				$model->vhc_trip_type = implode(',', $arr['vhc_trip_type']);
			}
			else
			{
				$model->vhc_trip_type = '';
			}
		}
		$dataProvider							 = $model->getList($qry, false);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->render('list', array('dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'qry'			 => $qry));
	}

	/**
	 * @deprecated since version 13-09-2019
	 * This function has been deprecated. 
	 * New function name : actionTypeList
	 */
	public function actionTypelistOld()
	{
		$this->pageTitle = "Vehicle Model List";
		$pageSize		 = Yii::app()->params[
				'listPerPage'];
		$model			 = new VehicleTypes('search');

		if (isset($_REQUEST['VehicleTypes']))
		{
			$model->attributes = Yii::app()->request->getParam('VehicleTypes');
		}
		$dataProvider							 = $model->search();
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);

		$this->render('typelist', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	/**
	 * This function is used for fetching the vehicle type list details and 
	 * Also filters data based on search parameters
	 */
	public function actionTypeList()
	{
		$this->pageTitle = "Vehicle Model List"; //Sets the page title
		$pageSize		 = Yii::app()->params["listPerPage"];
		$requestInstance = Yii::app()->request;

		$vehicleTypeListmodel = new VehicleTypes();

		if (empty($requestInstance->getParam("VehicleTypes")))
		{
			goto skipToDefault;
		}

		$receivedVehicleTypeDetails = $requestInstance->getParam("VehicleTypes");

		$requestData = array
			(
			"vehicleMake"			 => $receivedVehicleTypeDetails["vht_make"],
			"vehicleModel"			 => $receivedVehicleTypeDetails["vht_model"],
			"vehicleMileage"		 => $receivedVehicleTypeDetails["vht_average_mileage"],
			"vehicleSeatCapacity"	 => $receivedVehicleTypeDetails["vht_capacity"]
		);

		$dataProvider = Vehicles::fetchVehicleTypeDetalis($requestData); //Fetches the data based on search params
		goto skipToSearch;

		skipToDefault:
		$dataProvider = Vehicles::fetchVehicleTypeDetalis(); //Fetches all the data

		skipToSearch:
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);

		$this->render("typelist", array
			(
			"model"			 => $vehicleTypeListmodel,
			"dataProvider"	 => $dataProvider
		));
	}

	public function actionDelvehicle()
	{

		$id = Yii::app()->request->getParam('vid');
		if ($id != '')
		{
			$model = Vehicles::model()->findByPk($id);
			if (count($model) == 1)
			{

				$model->vhc_active = 0;
				$model->save();
			}
		}
		$this->redirect(array('list'));
	}

	public function actionDelvehicletype()
	{

		$id = Yii::app()->request->getParam('vid');
		if ($id != '')
		{
			$model = VehicleTypes::model()->findByPk($id);
			if ($model != '')
			{

				$model->vht_active = 0;
				$model->save();
			}
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			echo "true";
			Yii::app()->end();
		}
		$this->redirect(array
			('typelist'));
	}

	public function actionCreate()
	{

		$agtid	 = Yii::app()->request->getParam('agtid');
		$vhtid	 = Yii::app()->request->getParam('vhtid');

		if (isset($_REQUEST['Vehicles']))
		{
			$arrVhc	 = $_REQUEST['Vehicles'];
			$data1	 = Vehicles::model()->checkExisting($arrVhc);
			if ($data1[0]['vhc_id'] > 0)
			{
				$model			 = Vehicles::model()->findByPk($data1[0]['vhc_id']);
				$model->scenario = 'update';
			}
			else
			{
				$model = new Vehicles('insert');
			}
			$model->attributes	 = $arrVhc;
			$model->vhc_type_id	 = $vhtid;
			$result				 = CActiveForm::validate($model);
			$success			 = false;
			if ($result == '[]')
			{
				try
				{
					$success = $model->save();
					if ($success)
					{
						$arr = ['vehicle' => $model->vhc_id, 'vendor' => $agtid];
						VendorVehicle::model()->checkAndSave($arr);
					}
				}
				catch (Exception $e)
				{
					$success = false;
					$model->addError('vhc_id', $e->getMessage());
				}
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				echo $result;
				Yii::app()->end();
			}
		}
		$model					 = new Vehicles('insert');
		$model->vhc_vendor_id1	 = $agtid;
		$model->vhc_type_id		 = $vhtid;
		$outputJs				 = Yii::app()->request->isAjaxRequest;
		$method					 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('create', array('model' => $model), false, $outputJs);
	}

	public function actionJson()
	{

		$agtid	 = Yii::app()->request->getParam('agtid');
		$vtype	 = Yii::app()->request->getParam('vhtid');

		$vehicleModel = Vehicles::model()->getVehiclebyType($vtype, $agtid);

//		$arrvehicle		 = array();
//		foreach ($vehicleModel as $key => $val)
//		{
//			$arrvehicle[] = array("id" => $key, "text" => $val);
//		}
		$data = CJSON::encode($vehicleModel);
		echo $data;
		Yii::app()->end();
	}

	public function actionGetbyvendor()
	{
		$vendor		 = Yii::app()->request->getParam('vendor');
		$vehicles	 = Vehicles::model()->getJSONbyVendor($vendor);
		echo $vehicles;
		Yii::app()->end();
	}

	public function actionGetvehicletyperate()
	{
		$vtype		 = Yii::app()->request->getParam('vtype');
		$vehicles	 = VehicleTypes::model()->findByPk($vtype);
		$rate		 = $vehicles->vht_estimated_cost;
		$data		 = CJSON::encode(['rate' => $rate]);
		echo $data;
		Yii::app()->end();
	}

	public function actionRatejson()
	{
		$rutid	 = Yii::app()->request->getParam('rutid');
		$data	 = Rate::model()->getCabRateDetailsbyRutJSON($rutid);
		echo $data;
		Yii::app()->end();
	}

	public function actionAddavailability()
	{

		$cavID = Yii::app()->request->getParam('vhtid');
		if ($cavID != '')
		{
			$model			 = CabAvailabilities::model()->findByPk($cavID);
			$vnd_id			 = Vehicles::model()->findByPk($model->cav_cab_id)->vendorVehicles[0]->vvhc_vnd_id;
			$model->vnd_id	 = $vnd_id;
		}
		else
		{
			$model = new CabAvailabilities();
		}
		if (isset($_REQUEST['CabA vailabil ities']))
		{
			$arr					 = Yii::app()->request->getParam('CabAvailabilities');
			$model->attributes		 = $arr;
			$date					 = DateTimeFormat::DatePickerToDate($arr['cav_date']);
			$time					 = DateTime::createFromFormat('h:i A', $arr['cav_time'])->format('H:i:00');
			$datetime				 = $date . ' ' . $time;
			$model->cav_date_time	 = $datetime;
			$model->cav_status		 = 1;
			$result					 = CActiveForm::validate($model);

			if ($result == '[]')
			{
				$model->save();
			}
			else
			{
				throw new Exception("Could not updated availabilities. (" . json_encode($model->getErrors()) . ")");
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				echo $result;
				Yii::app()->end();
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('addAvailability', array
			('model' => $model), false, $outputJs);

// $this->render('addAvailability', ['model' => $model]);
	}

	public function actionGetdriver()
	{
		$success	 = false;
		$params		 = $_GET;
		$sagt		 = (Yii::app()->request->getParam('vendorId') == "") ? 0 : Yii::app()->request->getParam('vendorId');
		$arrDriver	 = Drivers::model()->getDriverByVendor($sagt);
		$arrSkill	 = array();
		foreach ($arrDriver as $sklModel)
		{
			$arrSkill[$sklModel->
					drv_id] = $sklModel->drv_name;
		}

		$arrJSON = [];
		foreach ($arrSkill as $key => $val)
		{
			$arrJSON[] = array("id" => $key, "text" => $val);
		}
		if (count($arrDriver) > 0)
		{
			$success = true;
		}

		$data = $arrJSON;
		echo CJSON::encode(['success' => $success, 'data' => $data]);
	}

	public function actionGetvehicle()
	{
		$success	 = false;
		$params		 = $_GET;
		$sagt		 = (Yii::app()->request->getParam('vendorId') == "") ? 0 : Yii::app()->request->getParam('vendorId');
		$arrCab		 = Vehicles::model()->getCabByVendor($sagt);
		$arrSkill	 = array(
		);
		foreach ($arrCab as $sklModel)
		{
			$arrSkill[$sklModel->vhc_id] = $sklModel->vhc_number;
		}

		$arrJSON = [];
		foreach ($arrSkill as $key => $val)
		{
			$arrJSON[] = array("id" => $key, "text" => $val);
		}
		if (count($arrCab) > 0)
		{
			$success = true;
		}

		$data = $arrJSON;
		echo CJSON::encode(['success' => $success, 'data' => $data]);
	}

	public function actionAvailabilitylist()
	{
		$this->pageTitle = 'Cab Availability List';
		$create_date1	 = $create_date2	 = "";
		$source			 = Yii::app()->request->getParam('source');
		$vndid			 = Yii::app()->request->getParam('vndid', 0);
		$showListOnly	 = false;

		$model = new CabAvailabilities('search');
		if ($source == 'mycall')
		{
			$showListOnly	 = true;
			$create_date1	 = date('Y-m-d');
			$create_date2	 = date('Y-m-d', strtotime('+2 MONTH'));
			$vnd_id			 = $vndid;
		}
		$request = Yii::app()->request;
		if ($request->getParam('CabAvailabilities'))
		{
			$model->attributes	 = $request->getParam('CabAvailabilities');
			$arr				 = $model->attributes;
			$create_date1		 = $model->from_date;
			$create_date2		 = $model->to_date;
			$from_city			 = $model->from_city;
			$to_city			 = $model->to_city;
			$vnd_id				 = $model->vnd_id;
		}
		if ($create_date1 == "" && $create_date2 == "")
		{
			$create_date2	 = DateTimeFormat::DateToLocale(date('Y-m-d'));
			$create_date1	 = DateTimeFormat::DatePickerToDate($create_date1);
			$create_date2	 = DateTimeFormat::DatePickerToDate($create_date2);
		}

		if ($request->getParam('export') == true)
		{
			$create_date1	 = $request->getParam('from_date');
			$create_date2	 = $request->getParam('to_date');
			$vnd_id			 = $request->getParam('vnd_id');
			$from_city		 = $request->getParam('from_city');
			$to_city		 = $request->getParam('to_city');
			$rows			 = $model->fetchList($from_city, $to_city, $vnd_id, $create_date1, $create_date2, 'command');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"VehicleAvailabilityReport_{$create_date1}_{$create_date2}_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$handle			 = fopen("php://output", 'w');
			fputcsv($handle, ['Vendor Name', 'Cab Number', 'Cab Model', 'Cab Type', 'From City', 'To City', 'Driver Name', 'Date/Time']);
			foreach ($rows as $row)
			{
				$rowArray					 = array();
				$rowArray['vnd_name']		 = $row['vnd_name'];
				$rowArray['vhc_number']		 = $row['vhc_number'];
				$rowArray['vht_make_model']	 = $row['vht_make_model'];
				$rowArray['cab_type']		 = $row['cab_type'];
				$rowArray['from_city']		 = $row['from_city'];
				$rowArray['to_city']		 = $row['to_city'];
				$rowArray['drv_name']		 = $row['drv_name'];
				$rowArray['cav_date_time']	 = $row['cav_date_time'];
				$row1						 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$model->from_city						 = $from_city;
		$model->to_city							 = $to_city;
		$model->vnd_id							 = $vnd_id;
		$dataProvider							 = $model->fetchList($from_city, $to_city, $vnd_id, $create_date1, $create_date2);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->renderAuto('cabavailabilitylist', array('dataProvider' => $dataProvider, 'model' => $model, 'qry' => $qry, 'showListOnly' => $showListOnly));
	}

	public function actionDelavailability()
	{
		$id = Yii::app()->request->getParam('vhtid');
		if ($id != '')
		{
			$model = CabAvailabilities::model()->findByPk($id);
			if (count($model) == 1)
			{
				$model->cav_status = 0;
				$model->update();
			}
		}
		$this->redirect(array('addavailabilitylist'));
	}

	public function actionMarkedbadlist()
	{
		$vhcId			 = Yii::app()->request->getParam('vhc_id');
		/* var $model Vehicles */
		$model			 = new Vehicles();
		$dataProvider	 = $model->markedBadListByVehicleId($vhcId);
		$this->renderPartial('markedbadlist', array('model'			 => $model,
			'dataProvider'	 => $dataProvider, 'vhcId'			 => $vhcId), false, true);
	}

	public function actionResetmarkedbad()
	{
		$refId				 = Yii::app()->request->getParam('refId');
		/* var $model Vehicles */
		$vhcModel			 = Vehicles::model()->findByPk($refId);
		$old_markbad_count	 = $vhcModel->vhc_mark_car_count;
		$remark				 = $vhcModel->vhc_log;
		$vhcModel->scenario	 = 'reset';
		if (isset($_POST['Vehicles']))
		{
			$arr					 = Yii::app()->request->getParam('Vehicles');
			$vhcModel->attributes	 = $arr;
			$vhcModel->resetScope();
			$dt						 = date('Y-m-d H:i:s');
			$user					 = Yii::app()->user->getId();
			$new_remark				 = $arr['vhc_reset_desc'];
			$succes					 = false;
			if ($new_remark != '')
			{
				if ($vhcModel->validate())
				{
					if ($new_remark != '')
					{
						if (is_string($remark))
						{
							$newcomm = CJSON::decode($remark);
							if ($remark != '' && CJSON::decode($remark) == '')
							{
								$newcomm = array(array(0 => $user, 1 => $vhcModel->vhc_created_at, 2 => $remark, 3 => $old_markbad_count));
							}
						}
						else if (is_array($remark))
						{
							$newcomm = $remark;
						}
						if ($newcomm == false)
						{
							$newcomm = array();
						}
						while (count($newcomm) >= 50)
						{
							array_pop($newcomm);
						}
						array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $new_remark, 3 => $old_markbad_count));
						$vhcModel->vhc_log = CJSON::encode($newcomm);
						try
						{
							$vhcModel->vhc_mark_car_count	 = 0;
							$vhcModel->save();
							$succes							 = true;
						}
						catch (Exception $e)
						{
							echo $e;
						}
					}
				}
				else
				{
					$errors = $vhcModel->getErrors();
				}
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => $succes];
				echo json_encode($data);
				Yii::app()->end();
			}
			$this->redirect(array('list', 'tab' => $tab));
		}
		$this->renderPartial('resetmarkedbad', array('refId' => $refId, 'vhcModel' => $vhcModel), false, true);
	}

	/**
	 * @deprecated since version 11-10-2019
	 * @author ramala
	 */
	public function actionApproveList1()
	{
		$this->pageTitle	 = '';
		//vehicleList
		$model				 = new VehiclesInfo();
		$model->vhc_approved = 2;
		if (isset($_REQUEST['VehiclesInfo']))
		{
			$model->attributes = Yii::app()->request->getParam('VehiclesInfo');
		}

		$dataProvider							 = $model->fetchList();
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->render('listtoapprove', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionApproveList()
	{
		$this->pageTitle	 = 'Vehicles Approval List';
		$model				 = new Vehicles();
		$model->vhc_approved = 2;
		$arr				 = [];
		$request			 = Yii::app()->request;
		if ($request->getParam('Vehicles'))
		{
			$model->vhcnumber		 = $request->getParam('Vehicles')['vhcnumber'];
			$model->vhc_vendor_id	 = $request->getParam('Vehicles')['vhc_vendor_id'];
			$model->vhc_year		 = $request->getParam('Vehicles')['vhc_year'];
			$model->vhc_color		 = $request->getParam('Vehicles')['vhc_color'];
			$model->vht_capacity	 = $request->getParam('Vehicles')['vht_capacity'];
			$arr['vhc_vendor_id']	 = $request->getParam('Vehicles')['vhc_vendor_id'];
			$arr['vhcnumber']		 = $request->getParam('Vehicles')['vhcnumber'];
			$arr['vhc_year']		 = $request->getParam('Vehicles')['vhc_year'];
			$arr['vhc_color']		 = $request->getParam('Vehicles')['vhc_color'];
			$arr['vht_capacity']	 = $request->getParam('Vehicles')['vht_capacity'];
		}
		$dataProvider							 = $model->getPendingApprovalList($arr);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->render('listtoapprove', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionApprove()
	{
		$success			 = false;
		$vhcId				 = Yii::app()->request->getParam('vhcid');
		$modelVehiclesInfo	 = VehiclesInfo::model()->findByPk($vhcId);
		$mVehicle			 = Vehicles::model()->findByPk($modelVehiclesInfo->vhc_vehicle_id);
		if (isset($_POST['VehiclesInfo']) && isset($_POST['verifysubmit']))
		{
			$modelVehiclesInfo	 = VehiclesInfo::model()->findByPk($_POST['VehiclesInfo']['vhc_id']);
			$vehicles			 = Vehicles::model()->findAll("vhc_number='$modelVehiclesInfo->vhc_number' AND vhc_active=1");

			// approve all by same vehicle number
			foreach ($vehicles as $modelVehicle)
			{
				$modelVehicle->scenario						 = "approve";
				$modelVehicle->setAttributes(array_filter($modelVehiclesInfo->attributes));
				$modelVehicle->vhc_insurance_exp_date		 = $modelVehiclesInfo->vhc_insurance_exp_date;
				unset($modelVehicle->vhc_id);
				//   unset($modelVehicle->vhc_vendor_id);
				unset($modelVehicle->vhc_modified_at);
				$modelVehicle->vhc_approved					 = 1;
				$modelVehicle->vhc_approved_by				 = Yii::app()->user->getId();
				$modelVehicle->vhc_ver_number				 = isset($_POST['chk1']) ? 1 : 0;
				$modelVehicle->vhc_ver_model_year_color		 = isset($_POST['chk2']) ? 1 : 0;
				$modelVehicle->vhc_ver_rc					 = isset($_POST['chk3']) ? 1 : 0;
				$modelVehicle->vhc_ver_front_license		 = isset($_POST['chk4']) ? 1 : 0;
				$modelVehicle->vhc_ver_rear_license			 = isset($_POST['chk5']) ? 1 : 0;
				$modelVehicle->vhc_ver_license_commercial	 = isset($_POST['chk6']) ? 1 : 0;
				$modelVehicle->vhc_ver_insurance			 = isset($_POST['chk7']) ? 1 : 0;
				$modelVehicle->vhc_ver_permit				 = isset($_POST['chk8']) ? 1 : 0;
				$modelVehicle->vhc_ver_fitness				 = isset($_POST['chk9']) ? 1 : 0;
				$modelVehicle->vhc_approved_by				 = Yii::app()->user->getId();
				if ($modelVehicle->save())
				{
					$modelVehiclesInfo->vhc_approved	 = 1;
					$modelVehiclesInfo->vhc_is_edited	 = 0;
					$modelVehiclesInfo->update();
					$success							 = true;
				}
				else
				{
					$modelVehicle->getErrors();
				}
			}
			if ($success)
			{
				$this->redirect(['approveList']);
			}
		}
		if (isset($_POST['VehiclesInfo']) && isset($_POST['verifysave']))
		{
			$vehicles = Vehicles::model()->findAll("vhc_number='$modelVehiclesInfo->vhc_number' AND vhc_active=1");

			// save all by same vehicle number
			foreach ($vehicles as $modelVehicle)
			{
				$modelVehicle->vhc_ver_number				 = isset($_POST['chk1']) ? 1 : 0;
				$modelVehicle->vhc_ver_model_year_color		 = isset($_POST['chk2']) ? 1 : 0;
				$modelVehicle->vhc_ver_rc					 = isset($_POST['chk3']) ? 1 : 0;
				$modelVehicle->vhc_ver_front_license		 = isset($_POST['chk4']) ? 1 : 0;
				$modelVehicle->vhc_ver_rear_license			 = isset($_POST['chk5']) ? 1 : 0;
				$modelVehicle->vhc_ver_license_commercial	 = isset($_POST['chk6']) ? 1 : 0;
				$modelVehicle->vhc_ver_insurance			 = isset($_POST['chk7']) ? 1 : 0;
				$modelVehicle->vhc_ver_permit				 = isset($_POST['chk8']) ? 1 : 0;
				$modelVehicle->vhc_ver_fitness				 = isset($_POST['chk9']) ? 1 : 0;

				if ($modelVehicle->vhc_ver_number == 1)
				{
					$modelVehicle->vhc_number = $modelVehiclesInfo->vhc_number;
				}
				if ($modelVehicle->vhc_ver_model_year_color == 1)
				{
					$modelVehicle->vhc_type_id	 = $modelVehiclesInfo->vhc_type_id;
					$modelVehicle->vhc_year		 = $modelVehiclesInfo->vhc_year;
					$modelVehicle->vhc_color	 = $modelVehiclesInfo->vhc_color;
				}
				if ($modelVehicle->vhc_ver_rc == 1)
				{
					$modelVehicle->vhc_reg_certificate	 = $modelVehiclesInfo->vhc_reg_certificate;
					$modelVehicle->vhc_reg_exp_date		 = $modelVehiclesInfo->vhc_reg_exp_date;
				}
				if ($modelVehicle->vhc_ver_front_license == 1)
				{
					$modelVehicle->vhc_front_plate = $modelVehiclesInfo->vhc_front_plate;
				}
				if ($modelVehicle->vhc_ver_rear_license == 1)
				{
					$modelVehicle->vhc_rear_plate = $modelVehiclesInfo->vhc_rear_plate;
				}
				if ($modelVehicle->vhc_ver_license_commercial == 1)
				{
					$modelVehicle->vhc_is_commercial = 1;
				}
				if ($modelVehicle->vhc_ver_insurance == 1)
				{
					$modelVehicle->vhc_insurance_proof		 = $modelVehiclesInfo->vhc_insurance_proof;
					$modelVehicle->vhc_insurance_exp_date	 = $modelVehiclesInfo->vhc_insurance_exp_date;
				}
				if ($modelVehicle->vhc_ver_permit == 1)
				{
					$modelVehicle->vhc_permits_certificate	 = $modelVehiclesInfo->vhc_permits_certificate;
					$modelVehicle->vhc_commercial_exp_date	 = $modelVehiclesInfo->vhc_commercial_exp_date;
				}
				if ($modelVehicle->vhc_ver_fitness == 1)
				{
					$modelVehicle->vhc_fitness_certificate	 = $modelVehiclesInfo->vhc_fitness_certificate;
					$modelVehicle->vhc_fitness_cert_end_date = $modelVehiclesInfo->vhc_fitness_cert_end_date;
				}
				if ($modelVehicle->vhc_ver_number == 1 && $modelVehicle->vhc_ver_rc == 1 && $modelVehicle->vhc_ver_insurance == 1 && $modelVehicle->vhc_ver_license_commercial)
				{
					$modelVehiclesInfo->vhc_approved = 1;
					$modelVehicle->vhc_approved		 = 1;
				}
				else
				{
					$modelVehiclesInfo->vhc_approved = 2;
					$modelVehicle->vhc_approved		 = 2;
				}
				$modelVehiclesInfo->vhc_is_edited	 = 0;
				$modelVehiclesInfo->save();
				$modelVehicle->vhc_approved_by		 = Yii::app()->user->getId();
				$modelVehicle->save();
			}

			$this->redirect(['approveList']);
		}
		if (isset($_POST['VehiclesInfo']) && isset($_POST['rejectsave']))
		{
			$vehicles = Vehicles::model()->findAll("vhc_number='$modelVehiclesInfo->vhc_number' AND vhc_active=1");
			// approve all by same vehicle number
			foreach ($vehicles as $modelVehicle)
			{
				$modelVehicle->vhc_approved		 = 3;
				$modelVehiclesInfo->vhc_approved = 3;
				$modelVehicle->vhc_approved_by	 = Yii::app()->user->getId();
				$modelVehiclesInfo->save();
				$modelVehicle->save();
			}
			$this->redirect(['approveList']);
		}

		$this->renderPartial('detailtoapprove', ['model' => $modelVehiclesInfo, 'modelVehicle' => $mVehicle], false, true);
	}

	public function actionFreeze()
	{
		$vhcId		 = Yii::app()->request->getParam('vhc_id');
		$vhcIsFreeze = Yii::app()->request->getParam('vhc_is_freeze');
		$userInfo	 = UserInfo::getInstance();
		$checkaccess = Yii::app()->user->checkAccess('vendorChangestatus');
		if (!$checkaccess)
		{
			$commentText = "You are not authorized for this action. Contact your operation manager.";
		}
		else
		{
			/* @var $model Vehicles */
			$model				 = Vehicles::model()->resetScope()->findByPk($vhcId);
			$commentText		 = ($model->vhc_is_freeze > 0) ? 'Add comments on why the vehicle is being frozen. What actions are needed before unfreezing them?' : 'Add comments on why the vehicle is being not frozen. What actions are needed before freezing them?';
			/* @var $logModel VehiclesLog */
			$logModel			 = new VehiclesLog();
			$logModel->scenario	 = 'updateFreeze';
			$success			 = false;
			if (isset($_POST['VehiclesLog']))
			{
				$logModel->attributes	 = Yii::app()->request->getParam('VehiclesLog');
				$arr					 = $logModel->attributes;
				switch ($vhcIsFreeze)
				{
					case 0:
						$model->vhc_is_freeze	 = 1;
						$eventId				 = VehiclesLog::VEHICLE_FREEZE;
						break;
					case 1:
						$model->vhc_is_freeze	 = 0;
						$eventId				 = VehiclesLog::VEHICLE_UNFREEZE;
						break;
				}
				$model->scenario = 'updateFreeze';
				if ($model->save())
				{
					VehiclesLog::model()->createLog($arr['clg_vhc_id'], $arr['clg_desc'], $userInfo, $eventId, false, false);
					$success = true;
				}
				else
				{
					$data['errors']	 = CJSON::encode($model->getErrors());
					$success		 = false;
				}
				if (Yii::app()->request->isAjaxRequest)
				{
					$data['success'] = $success;
					echo json_encode($data);
					Yii::app()->end();
				}
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('freeze', array('model'			 => $model,
			'logModel'		 => $logModel, 'checkaccess'	 => $checkaccess,
			'comment'		 => $commentText), FALSE, $outputJs);
	}

	/**
	 * @deprecated since version 09-10-2019
	 * @author ramala
	 */
	public function actionVehiclemodelbytype()
	{
		$vhcTypeId		 = Yii::app()->request->getParam('vhcTypeId');
		$vehicleModel	 = Vehicles::model()->getVehicleModel($vhcTypeId);

		$arrJSON = array();
		foreach ($vehicleModel as $val)
		{
			$arrJSON[] = array("id" => $val['vht_id'], "text" => $val['vht_model']);
		}
		$data = CJSON::encode($arrJSON);

		echo $data;
		Yii::app()->end();
	}

	public function actionCheckexisting()
	{
		$vndid		 = Yii::app()->request->getParam('vndid');
		$vhcnumber	 = Yii::app()->request->getParam('vhcnumber');

		$qry1	 = [
			'vhc_vendor_id1' => $vndid,
			'vhc_number'	 => $vhcnumber,
		];
		$qry	 = array_filter($qry1);
		$cnt	 = count($qry);
		$found	 = Vehicles::model()->checkExisting($qry);
		$vhc	 = $found[0];
		if (!$vhc['vhc_id'])
		{
			$vhc['vhc_id'] = '0';
		}
		else
		{
			$vhc += Vehicles::model()->getDetailListbyId($vhc['vhc_id']);
			//$vhc = $vhc +
		}
		//  $vhc['vcount'] = $cnt;
		$v = [];
		if ($vhc['vendorids'])
		{
			$v = explode(',', $vhc['vendorids']);
			if (in_array($vndid, $v))
			{
				$vhc['this_vendor'] = 1;
			}
			else
			{
				$vhc['this_vendor'] = 0;
			}
		}
		$data	 = array_diff($vhc, ['']);
		$dataVal = CJSON::encode($data);
		echo $dataVal;
		Yii::app()->end();
	}

	public function actionLoadvehicle()
	{
		$vhcid	 = Yii::app()->request->getParam('vhcid');
		$model	 = Vehicles::model()->findByPk($vhcid);
		if ($model != null)
		{
			$oldData = array_filter($model->attributes) + ['vhc_type' => $model->vhcType->vht_VcvCatVhcType->vcv_vct_id];
			$dataVal = CJSON::encode($oldData);
			echo $dataVal;
		}
		Yii::app()->end();
	}

	public function actionLoadvendorlist()
	{
		$vhcid = Yii::app()->request->getParam('vhcid');
		if ($vhcid > 0)
		{
			$data = VendorVehicle::model()->getVendorListbyVehicleid($vhcid);
		}
		$h = '';
		if (sizeof($data) > 0)
		{
			$h	 .= '<div class="col-xs-12" style="background:#fff">';
			$h	 .= "<h4>Vendor assigned : </h4>";

			$h .= "<ul style='padding-left:10px'>";
			foreach ($data as $val)
			{
				$h	 .= "<li>";
				$h	 .= $val['vnd_name'];
				$h	 .= "</li>";
			}
			$h .= "</ul></div>";
		}
		echo $h;
		Yii::app()->end();
	}

	public function saveVehicleImage($image, $imagetmp, $vehicleId, $type)
	{
		try
		{
			$path = "";
			if ($image != '')
			{
				$image	 = $vehicleId . "-" . $type . "-" . date('YmdHis') . "." . $image;
				$dir	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
				if (!is_dir($dir))
				{
					mkdir($dir);
				}
				$dirFolderName = $dir . DIRECTORY_SEPARATOR . 'vehicles';
				if (!is_dir($dirFolderName))
				{
					mkdir($dirFolderName);
				}
				$dirByVehicleId = $dirFolderName . DIRECTORY_SEPARATOR . $vehicleId;
				if (!is_dir($dirByVehicleId))
				{
					mkdir($dirByVehicleId);
				}
				$file_path	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'vehicles' . DIRECTORY_SEPARATOR . $vehicleId;
				$file_name	 = basename($image);
				$f			 = $file_path;
				$file_path	 = $file_path . DIRECTORY_SEPARATOR . $file_name;
				file_put_contents(PUBLIC_PATH . '/testFile.txt', $f . ' ==== ' . $file_name);
				Yii::log("Image Path: \n\t Temp: " . $imagetmp . "\n\t Path: " . $f, CLogger::LEVEL_INFO, 'system.api.images');
				if (Vehicles::model()->img_resize($imagetmp, 1200, $f, $file_name))
				{
					$path	 = substr($file_path, strlen(PUBLIC_PATH));
					$result	 = ['path' => $path];
				}
			}
		}
		catch (Exception $e)
		{
			Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
			Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
			throw $e;
		}
		return $result;
	}

	public function actionShowlog()
	{
		$request	 = Yii::app()->request;
		$vhcId		 = $request->getParam('vhcId');
		$id			 = $request->getParam('id');
		$viewType	 = $request->getParam('view');
		$vhcId		 = $vhcId . $id;
		if ($vhcId != '')
		{
			$dataProvider = VehiclesLog::model()->getByVehicleId($vhcId, $viewType);
		}
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->renderPartial('showlog', array(
			'model'			 => $model,
			'dataProvider'	 => $dataProvider), false, true);
	}

	public function actionView()
	{
		$request = Yii::app()->request;
		$vhcId	 = $request->getParam('id');
		$vhcCode = $request->getParam('code');
		$view	 = $request->getParam('view', 'view');
		if ($vhcId != '')
		{
			$data = Vehicles::model()->getDetailsById($vhcId);
		}
		if ($vhcCode != '')
		{
			$vhc	 = Vehicles::model()->getIdByCode($vhcCode);
			$vhcId	 = $vhc['vhc_id'];
			$data	 = Vehicles::model()->getDetailsById($vhcId);
		}
		$this->pageTitle = 'License Plate: ' . $data['vhc_number'];
		$pastData		 = Vehicles::model()->getPastTripList($vhcId);
		$louData		 = VendorVehicle::getLouListByVehicle($vhcId);
		$odometerData	 = Vehicles::getOdometerHistory($vhcId);
		$reviewData		 = Vehicles::model()->getReviewHistory($vhcId);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method($view, array('data'		 => $data,
			'pastData'	 => $pastData,
			'louData'	 => $louData,
			'odoData'	 => $odometerData,
			'revData'	 => $reviewData,
			'isAjax'	 => $outputJs
				), false, $outputJs);
	}

	public function actionViewOld()
	{
		$request = Yii::app()->request;
		$vhcId	 = $request->getParam('id');
		$view	 = $request->getParam('view', 'view');
		if ($vhcId != '')
		{
			$data = Vehicles::model()->getDetailsById($vhcId);
		}
		$this->pageTitle = 'License Plate: ' . $data['vhc_number'];
		$pastData		 = Vehicles::model()->getPastTripList($vhcId);
		$louData		 = VendorVehicle::getLouListByVehicle($vhcId);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('viewOld', array('data'		 => $data,
			'pastData'	 => $pastData,
			'louData'	 => $louData,
			'isAjax'	 => $outputJs
				), false, $outputJs);
	}

	public function actionCheckapprovedntottrips()
	{
		$vhcId = Yii::app()->request->getParam('vhcid');

		$model				 = Vehicles::model()->getDetailListbyId($vhcId);
		$totaltrip			 = $model['vhc_total_trips'] | 0;
		$data['showMessage'] = false;
		if ($model['vhc_approved'] != 1 && $totaltrip == 0)
		{
			$data['showMessage'] = true;
		}
		$dataVal = CJSON::encode($data);
		echo $dataVal;
		Yii::app()->end();
	}

//    public function actionApprovedoc() {
//
//    }

	public function actionDocapprovallist()
	{
		$model			 = new VehicleDocs();
		//  $vmodel = new Vehicles();
		$arr			 = [];
		$this->pageTitle = 'Document Pending Approval';
		$request		 = Yii::app()->request;
		$vhcid			 = $request->getParam('cabid', 0);
		if ($vhcid > 0)
		{
			$arr['vhc_id']		 = $vhcid;
			$vhcModel			 = Vehicles::model()->resetScope()->findByPk($vhcid);
			$arr['vhcnumber']	 = $vhcModel->vhc_number;
			$model->vhcnumber	 = $arr['vhcnumber'];
		}
		if ($request->getParam('VehicleDocs'))
		{
			$arr				 = $request->getParam('VehicleDocs');
			$model->vhcnumber	 = $arr['vhcnumber'];
			$model->vhd_type	 = $arr['vhd_type'];
			$model->newestVhc	 = $arr['newestVhc'];
		}

		$dataProvider							 = $model->getUnapproved($arr);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$outputJs								 = $request->isAjaxRequest;
		$method									 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('docapprovallist', array('model' => $model, 'dataProvider' => $dataProvider), false, $outputJs);
	}

	public function actionCarVerifyDoclist()
	{

		$model			 = new VehicleDocs();
		//  $vmodel = new Vehicles();
		$arr			 = [];
		$this->pageTitle = 'Car Verify Document Pending Approval';
		$request		 = Yii::app()->request;
		if ($request->getParam('ctype') == 'boost-verify')
		{
			$arr['vhd_type'] = 2;
			$model->vhd_type = 2;
		}
		$vhcid = $request->getParam('cabid', 0);

		if ($vhcid > 0)
		{
			$arr['vhc_id']		 = $vhcid;
			$vhcModel			 = Vehicles::model()->resetScope()->findByPk($vhcid);
			$arr['vhcnumber']	 = $vhcModel->vhc_number;
			$model->vhcnumber	 = $arr['vhcnumber'];
		}
		if ($request->getParam('VehicleDocs'))
		{
			$arr				 = $request->getParam('VehicleDocs');
			$model->vhcnumber	 = $arr['vhcnumber'];
			$model->vhd_type	 = $arr['vhd_type'];
		}
		$dropType								 = array(1 => "Cab Verify", 2 => "Boost Verify");
		$dataProvider							 = $model->getCarVerifyImage($arr);
//$dataProvider->getPagination()->params['page'];
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$outputJs								 = $request->isAjaxRequest;
		$method									 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('carApprovaList', array('model' => $model, 'dataProvider' => $dataProvider, 'dropType' => $dropType), false, $outputJs);
	}

	public function actionShowdocimg()
	{
		$vhdid	 = Yii::app()->request->getParam('vhdid');
		$boost	 = Yii::app()->request->getParam('boost') | 0;
		$vmodel	 = VehicleDocs::model()->findByPk($vhdid);
		//type =5 registration front show back also with front;
		if ($vmodel->vhd_type == 5)
		{
			$otherType[] = 13; //registration back =13
			$docModel	 = VehicleDocs::model()->findByVhcId($vmodel->vhd_vhc_id, $otherType);
			$regBack	 = $docModel[0]['vhd_file'];
			$regBackId	 = $docModel[0]['vhd_id'];
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('picshow', ['vmodel' => $vmodel, 'boost' => $boost, 'rcback' => $regBackId], false, $outputJs);
	}

	public function actionShowCarImg()
	{
		$bpayId		 = Yii::app()->request->getParam('bpayId');
		$vhcId		 = Yii::app()->request->getParam('vhcId');
		$bmodel		 = BookingPayDocs::model()->findByPk($bpayId);
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('carpicshow', ['bmodel' => $bmodel, 'vhcId' => $vhcId], false, $outputJs);
	}

	public function actionApproveAllCarImages()
	{
		$btntype			 = Yii::app()->request->getParam('btntype');
		$vhcId				 = Yii::app()->request->getParam('vhcId');
		$remarks			 = Yii::app()->request->getParam('remarks');
		$verificationType	 = Yii::app()->request->getParam('verificationType');
		$success			 = false;

		$model = VehicleDocs::model()->getBoostDocsByVhcId($vhcId);
		if (count($model) > 0)
		{
			foreach ($model as $val)
			{
				$vmodel = VehicleDocs::model()->updateDocStatus($val['vhd_id'], $btntype, $val['vhd_type'], $val['vhd_vhc_id'], $remarks);
				if ($vmodel == true)
				{
					if ($verificationType == 1)
					{
						$vhsModel = VehicleStats::model()->carApprovalStatus($vhcId, $btntype);
					}
				}
				if ($vhsModel == true)
				{
					$success = true;
				}
			}
			if ($sucess == true)
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					$data1 = ['success' => $success];
					echo json_encode($data1);
					Yii::app()->end();
				}
			}
		}
		else
		{
			if (Yii::app()->request->isAjaxRequest)
			{
				$result = [];
				if ($vhcId == '')
				{
					$result['vhc_id'] = 'Vehicle Id empty.';
				}
				$data1 = ['success' => $success, 'errors' => $result];
				echo json_encode($data1);
				Yii::app()->end();
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('showboostdocimg', ['vmodel' => $vmodel, 'data1' => $data1], false, $outputJs);
	}

	public function actionApproveBoostCarImages()
	{
		$btntype			 = Yii::app()->request->getParam('btntype');
		$vhcId				 = Yii::app()->request->getParam('vhcId');
		$remarks			 = Yii::app()->request->getParam('remarks');
		$verificationType	 = Yii::app()->request->getParam('verificationType');
		$success			 = false;

		$model = VehicleDocs::model()->getBoostDocsByVhcId($vhcId);
		if (count($model) > 0)
		{
			$eventId = "";
			$descLog = "";
			foreach ($model as $val)
			{
				$status	 = ($btntype == 3) ? 1 : $btntype;
				$vmodel	 = VehicleDocs::model()->updateDocStatus($val['vhd_id'], $status, $val['vhd_type'], $val['vhd_vhc_id'], $remarks);
				if ($vmodel == true)
				{
					$vhkStatsModel = VehicleStats::model()->getbyVehicleId($vhcId);
					if (empty($vhkStatsModel))
					{
						VehicleStats::checkAndSave($vhcId);
					}
					if ($btntype == 3)
					{
						$vhsModel	 = VehicleStats::model()->carVerifyBoostUnverify($vhcId, $btntype);
						$eventId	 = VehiclesLog::VEHICLE_CAR_APPROVE_BOOST_REJECT;
						$descLog	 = "Cab Approved & Boost rejected";
					}
					else if ($btntype == 2)
					{
						$vhsModel	 = VehicleStats::model()->carDisapprovalStatus($vhcId, $btntype);
						$eventId	 = VehiclesLog::VEHICLE_CAR_REJECT_BOOST_REJECT;
						$descLog	 = "Cab & Boost rejected";
					}
					else
					{
						$vhsModel	 = VehicleStats::model()->boostApprovalStatus($vhcId, $btntype);
						$eventId	 = VehiclesLog::VEHICLE_CAR_APPROVE_BOOST_ENABLE;
						$descLog	 = "Cab & Boost approved";
					}
				}
				if ($vhsModel == true)
				{

					$success = true;
				}
			}
			$userInfo = UserInfo::getInstance();
			VehiclesLog::model()->createLog($vhcId, $descLog, $userInfo, $eventId, false, false);
			if ($success == true)
			{
				if ($btntype == 1)
				{
					//Modification in vendor end


					$vendorId		 = Package::model()->getVendorByVehicleId($vhcId);
					$updateVendor	 = VendorPref::model()->updateBoostCount($vendorId);
					$boostPercentage = Vehicles::calculateVendorBoost($vendorId);
					$updateVendor	 = VendorStats::model()->updateBoostPercentage($vendorId, $boostPercentage);
				}
				if (Yii::app()->request->isAjaxRequest)
				{
					$data1 = ['success' => $success];
					echo json_encode($data1);
					Yii::app()->end();
				}
			}
		}
		else
		{
			if (Yii::app()->request->isAjaxRequest)
			{
				$result = [];
				if ($vhcId == '')
				{
					$result['vhc_id'] = 'Vehicle Id empty.';
				}
				$data1 = ['success' => $success, 'errors' => $result];
				echo json_encode($data1);
				Yii::app()->end();
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('showboostdocimg', ['vmodel' => $vmodel, 'data1' => $data1], false, $outputJs);
	}

	public function actionApproveAllCarImagesNew()
	{
		$status				 = Yii::app()->request->getParam('btntype');
		$vhcId				 = Yii::app()->request->getParam('vhcId');
		$remarks			 = Yii::app()->request->getParam('remarks');
		$bkgId				 = Yii::app()->request->getParam('bkgId');
		$verificationType	 = Yii::app()->request->getParam('verificationType');
		$success			 = false;

		$model		 = BookingPayDocs::getBoostDocsByBkgId($bkgId);
		$bookingId	 = Booking::model()->getCodeById($bkgId);

		if (count($model) > 0)
		{
			$userInfo			 = UserInfo::getInstance();
			$userInfo->userId	 = UserInfo::getUserId();
			$name				 = Admins::model()->getFullNameById($userInfo->userId);
			foreach ($model as $val)
			{
				$updateData = BookingPayDocs::updateDocsById($val['bpay_id'], $status);
				if ($updateData == true)
				{
					if ($status != 2)
					{
						$vmodel = VehicleDocs::model()->updateAllBoostCarImages($val['bpay_id'], $status, $val['bpay_type'], $val['vhc_id'], $remarks, $val['bpay_image']);
					}
					if ($verificationType == 1)
					{
						$vhsModel = VehicleStats::model()->carApprovalStatus($vhcId, $status);
					}
					else
					{
						if ($status == 3)
						{
							$vhsModel			 = VehicleStats::model()->carVerifyBoostUnverify($vhcId, $status);
							$boostRejectNotify	 = 1;
						}
						else
						{
							$vhsModel = VehicleStats::model()->boostApprovalStatus($vhcId, $status);
						}
					}
				}

				if ($vhsModel == true)
				{
					$success = true;
				}
			}

			if ($status == 1 && $success == true)
			{


				if ($verificationType == 1)
				{
					$eventId = BookingLog::CAB_VERIFIED;
					$desc	 = "Cab verification successfully which is approved by $name(See attachment in BKG ID " . $bookingId . ")";
				}
				else
				{
					$eventId = BookingLog::BOOST_VERIFIED;
					$desc	 = "Boost verification successfully which is approved by $name(See attachment in BKG ID " . $bookingId . ")";
				}
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId);
			}

			# add penaly and frezed vendor for reject car
			if ($status == 2 && $success = true)
			{
				$vhsModel = VehicleStats::model()->freezeVendorGivePenalty($bkgId);
			}
			if ($boostRejectNotify == 1)
			{
				//notification:
				$bookingmodel	 = Booking::model()->findByPk($bkgId);
				$bcb_id			 = $bookingmodel->bkg_bcb_id;
				$bcbmodel		 = BookingCab::model()->findByPk($bcb_id);
				$vehicleId		 = $bcbmodel->bcb_cab_id;
				$vehicleModel	 = Vehicles::model()->findByPk($vehicleId);
				$vehicleNumber	 = $vehicleModel->vhc_number;
				$vendorId		 = $bcbmodel->bcb_vendor_id;
				$payLoadData	 = ['vendorId' => $vendorId, 'EventCode' => BookingLog::CAB_VERIFIED];
				$message		 = "Boost verification failed for  " . $vehicleNumber;
				$success		 = AppTokens::model()->notifyVendor($vendorId, $payLoadData, $message, "Cab verification failed.");

				$eventId = BookingLog::CAB_VERIFIED;
				$desc	 = "Car verified but Boost rejected by $name (See attachment in BKG ID " . $bookingId . ")";
				BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId);
			}
			if ($success == true)
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					$data1 = ['success' => $success];
					echo json_encode($data1);
					Yii::app()->end();
				}
			}
		}
		else
		{
			if (Yii::app()->request->isAjaxRequest)
			{
				$result = [];
				if ($vhcId == '')
				{
					$result['vhc_id'] = 'Vehicle Id empty.';
				}
				$data1 = ['success' => $success, 'errors' => $result];
				echo json_encode($data1);
				Yii::app()->end();
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('showallcarimg', ['vmodel' => $vmodel, 'data1' => $data1], false, $outputJs);
	}

	public function actionApprovedocimg()
	{
		$btntype = Yii::app()->request->getParam('btntype');
		$vhdDocs = Yii::app()->request->getParam('VehicleDocs');

		$userInfo	 = UserInfo::getInstance();
		$user_id	 = $userInfo->userId;

		$vhdid		 = $vhdDocs['vhd_id'];
		$vhdTempStat = $vhdDocs['vhd_temp_approved'];
		$vmodel		 = VehicleDocs::model()->resetScope()->findByPk($vhdid);
		$oldDocData	 = $vmodel->attributes;
		$userInfo	 = UserInfo::getInstance();
		$fileType	 = '';
		$fileType1	 = [];
		if ($vmodel)
		{
			$vhcModel = Vehicles::model()->resetScope()->findByPk($vmodel->vhd_vhc_id);

			$vhcNumber	 = strtolower(str_replace(' ', '', $vhcModel->vhc_number));
			$vhcData	 = Vehicles::model()->checkDuplicateVehicleNo($vhcNumber, $vhcModel->vhc_id);

			if (trim($btntype) == 'approve' && count($vhcData) > 0 && $vhcData[0]['vhc_id'] > 0)
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					$result					 = [];
					$result['vhc_number']	 = 'Another vehicle with same number exists.';

					$data = ['success' => false, 'errors' => $result];
					echo json_encode($data);
					Yii::app()->end();
				}
			}
			$oldVhcData	 = $vhcModel->attributes;
			$vhc		 = Yii::app()->request->getParam('Vehicles');

			if ($btntype == 'approve')
			{
				$vmodel->vhd_status	 = 1;
				$vmodel->scenario	 = 'approve';
				$action				 = "approved";
			}
			else if ($btntype == 'problem')
			{
				$vmodel->vhd_status	 = 2;
				$vmodel->scenario	 = 'reject';
				$action				 = "disapproved";
			}
			else
			{
				throw new CHttpException(400, 'Invalid data');
			}
			$vhcChange = 0;

			if ($vhc['vhc_number'] != '' && $vhcModel->vhc_number != trim($vhc['vhc_number']))
			{
				$vhcChange++;
				$vhcModel->vhc_number = trim($vhc['vhc_number']);
			}
			if ($vhc['vhc_type_id'] != '' && $vhcModel->vhc_type_id != trim($vhc['vhc_type_id']))
			{
				$vhcChange++;
				$vhcModel->vhc_type_id = trim($vhc['vhc_type_id']);
			}
			if ($vhc['vhc_year'] != '' && $vhcModel->vhc_year != trim($vhc['vhc_year']))
			{
				$vhcChange++;
				$vhcModel->vhc_year = trim($vhc['vhc_year']);
			}
			if ($vhc['vhc_color'] != '' && $vhcModel->vhc_color != trim($vhc['vhc_color']))
			{
				$vhcChange++;
				$vhcModel->vhc_color = trim($vhc['vhc_color']);
			}
			if ($vhc['vhc_dop'] != '' && $vhcModel->vhc_dop != trim($vhc['vhc_dop']))
			{
				$vhcChange++;
				$vhcModel->vhc_dop = trim($vhc['vhc_dop']);
			}

			switch ($vmodel->vhd_type)
			{

				case 1:
					//$event_id	 = ($btntype == 'approve') ? VehiclesLog::VEHICLE_INSURANCE_APPROVE : VehiclesLog::VEHICLE_INSURANCE_REJECT;
					if ($btntype == 'approve')
					{
						$event_id = ($vhdTempStat != 1) ? VehiclesLog::VEHICLE_INSURANCE_APPROVE : VehiclesLog::VEHICLE_INSURANCE_TEMP_APPROVE;
					}
					else
					{
						$event_id = VehiclesLog::VEHICLE_INSURANCE_REJECT;
					}
					$fileType = "#insurance";
					if ($vhc['vhc_insurance_exp_date'] != '' && $vhcModel->vhc_insurance_exp_date != DateTimeFormat::DatePickerToDate($vhc['vhc_insurance_exp_date']))
					{
						$vhcChange++;
						$vhcModel->vhc_insurance_exp_date = DateTimeFormat::DatePickerToDate($vhc['vhc_insurance_exp_date']);
					}
					break;
				case 2:
					$event_id	 = ($btntype == 'approve') ? VehiclesLog::VEHICLE_FRONT_LICENSE_APPROVE : VehiclesLog::VEHICLE_FRONT_LICENSE_REJECT;
					$fileType	 = "#frontLicense";
					break;
				case 3:
					$event_id	 = ($btntype == 'approve') ? VehiclesLog::VEHICLE_REAR_LICENSE_APPROVE : VehiclesLog::VEHICLE_REAR_LICENSE_REJECT;
					$fileType	 = "#rearLicense";
					break;
				case 4:
					$event_id	 = ($btntype == 'approve') ? VehiclesLog::VEHICLE_PUC_APPROVE : VehiclesLog::VEHICLE_PUC_REJECT;
					$fileType	 = "#pollution";
					if ($vhc['vhc_pollution_exp_date'] != '' && $vhcModel->vhc_pollution_exp_date != DateTimeFormat::DatePickerToDate($vhc['vhc_pollution_exp_date']))
					{
						$vhcChange++;
						$vhcModel->vhc_pollution_exp_date = DateTimeFormat::DatePickerToDate($vhc['vhc_pollution_exp_date']);
					}
					break;
				case 5:
					//$event_id	 = ($btntype == 'approve') ? VehiclesLog::VEHICLE_REGISTRATION_APPROVE : VehiclesLog::VEHICLE_REGISTRATION_REJECT;

					if ($btntype == 'approve')
					{
						$event_id	 = ($vhdTempStat != 1) ? VehiclesLog::VEHICLE_REGISTRATION_APPROVE : VehiclesLog::VEHICLE_REGISTRATION_TEMP_APPROVE;
						$status		 = 1;
					}
					else
					{
						$status		 = 2;
						$event_id	 = VehiclesLog::VEHICLE_REGISTRATION_REJECT;
					}
					// approve reject back image also;
					$type		 = 13;
					$approveBack = VehicleDocs::modifyDocStatus($vmodel->vhd_vhc_id, $type, $status);

					$fileType = "#registration";
					if ($vhc['vhc_reg_exp_date'] != '' && $vhcModel->vhc_reg_exp_date != DateTimeFormat::DatePickerToDate($vhc['vhc_reg_exp_date']))
					{
						$vhcChange++;
						$vhcModel->vhc_reg_exp_date = DateTimeFormat::DatePickerToDate($vhc['vhc_reg_exp_date']);
					}
					if ($vhc['vhc_is_commercial'][0] == 1)
					{
						$vhcChange++;
						$vhcModel->vhc_is_commercial = 1;
					}
					else
					{
						$vhcChange++;
						$vhcModel->vhc_is_commercial = 0;
					}

					if ($vhc['vhc_owned_or_rented'][0] == 1)
					{
						$vhcChange++;
						$vhcModel->vhc_owned_or_rented = 1;
					}
					else
					{
						$vhcChange++;
						$vhcModel->vhc_owned_or_rented = 2;
					}
					if (count($vhc['vhc_trip_type']) > 0)
					{
						$vhcModel->vhc_trip_type = implode(',', $vhc['vhc_trip_type']);
						$vhcChange++;
					}
					if ($vhc['vhc_has_cng'][0] == '')
					{

						$vhcChange++;
						$vhcModel->vhc_has_cng = $vhc['vhc_has_cng'];
					}
					else
					{
						$vhcChange++;
						$vhcModel->vhc_has_cng = 1;
					}
					if ($vhc['vhc_reg_owner'] != "")
					{
						$vhcChange++;
						$vhcModel->vhc_reg_owner = $vhc['vhc_reg_owner'];
					}
					if ($vhc['vhc_reg_owner_lname'] != "")
					{
						$vhcChange++;
						$vhcModel->vhc_reg_owner_lname = $vhc['vhc_reg_owner_lname'];
					}


					break;
				case 6:
					$event_id	 = ($btntype == 'approve') ? VehiclesLog::VEHICLE_PERMITS_APPROVE : VehiclesLog::VEHICLE_PERMITS_REJECT;
					$fileType	 = "#commercialPermit";
					if ($vhc['vhc_commercial_exp_date'] != '' && $vhcModel->vhc_commercial_exp_date != DateTimeFormat::DatePickerToDate($vhc['vhc_commercial_exp_date']))
					{
						$vhcChange++;
						$vhcModel->vhc_commercial_exp_date = DateTimeFormat::DatePickerToDate($vhc['vhc_commercial_exp_date']);
					}
					break;
				case 7:
					$event_id	 = ($btntype == 'approve') ? VehiclesLog::VEHICLE_FITNESS_APPROVE : VehiclesLog::VEHICLE_FITNESS_REJECT;
					$fileType	 = "#fitnessCertificate";
					if ($vhc['vhc_fitness_cert_end_date'] != '' && $vhcModel->vhc_fitness_cert_end_date != DateTimeFormat::DatePickerToDate($vhc['vhc_fitness_cert_end_date']))
					{
						$vhcChange++;
						$vhcModel->vhc_fitness_cert_end_date = DateTimeFormat::DatePickerToDate($vhc['vhc_fitness_cert_end_date']);
					}
					break;

				case 8:
					$event_id	 = ($btntype == 'approve') ? VehiclesLog::VEHICLE_CAR_FRONT_APPROVE : VehiclesLog::VEHICLE_CAR_FRONT_REJECT;
					$fileType	 = "#Car(Front Image)";
					$vhcChange++;
					break;

				case 9:
					$event_id	 = ($btntype == 'approve') ? VehiclesLog::VEHICLE_CAR_BACK_APPROVE : VehiclesLog::VEHICLE_CAR_BACK_REJECT;
					$fileType	 = "#Car(Back Image)";
					$vhcChange++;
					break;

				case 10:
					$event_id	 = ($btntype == 'approve') ? VehiclesLog::VEHICLE_CAR_LEFT_APPROVE : VehiclesLog::VEHICLE_CAR_LEFT_REJECT;
					$fileType	 = "#Car(Left Image)";
					$vhcChange++;
					break;
				case 11:
					$event_id	 = ($btntype == 'approve') ? VehiclesLog::VEHICLE_CAR_RIGHT_APPROVE : VehiclesLog::VEHICLE_CAR_RIGHT_REJECT;
					$fileType	 = "#Car(Right Image)";
					$vhcChange++;
					break;
			}
			if ($vhcChange > 0)
			{
				if ($vhcModel->save())
				{
					$newVhcData			 = $vhcModel->attributes;
					$descLog			 = "Modified $fileType expiry date on car $action";
					$getOldDifferenceVhc = array_diff_assoc($oldVhcData, $newVhcData);
					$getNewDifferenceVhc = array_diff_assoc($newVhcData, $oldVhcData);
					$change				 = $this->getModificationMSG($getOldDifferenceVhc, false);
					$changeNew			 = $this->getModificationMSG($getNewDifferenceVhc, false);
					if ($change != '')
					{
						$changesForVhcLog	 = "<br> Old Values: " . $change;
						$descLog			 .= $changesForVhcLog;
					}
					else if ($changeNew != '')
					{
						$changesForVhcLog	 = "<br> New Values: " . $changeNew;
						$descLog			 .= $changesForVhcLog;
					}
					VehiclesLog::model()->createLog($vhcModel->vhc_id, $descLog, $userInfo, $event_id, false, false);
					$success = true;
				}
				else
				{
					$success = false;
					$result	 = [];
					foreach ($vhcModel->getErrors() as $attribute => $errors)
					{
						$result[CHtml::activeId($vhcModel, $attribute)] = $errors;
					}
					$data = ['success' => $success, 'errors' => $result];
					echo json_encode($data);
					Yii::app()->end();
				}
			}
			$remarks				 = trim($vhdDocs['vhd_remarks']);
			$newDocData				 = $vmodel->attributes;
			$vmodel->vhd_remarks	 = $remarks;
			$vmodel->vhd_appoved_at	 = new CDbExpression('NOW()');
			$vmodel->vhd_approve_by	 = $user_id;
			$result1				 = CActiveForm::validate($vmodel);
			//$return = ['success' => false];
			$success				 = false;
			if ($result1 == '[]')
			{
				$transaction = Yii::app()->db->beginTransaction();
				try
				{
					$success			 = $vmodel->save();
					$remarkAdded		 = ($remarks != '') ? "($remarks)" : '';
					$vhc_id				 = $vmodel->vhd_vhc_id;
					$desc				 = "The document for $fileType of the car is $action $remarkAdded";
					$getOldDifferenceDoc = array_diff_assoc($oldDocData, $newDocData);
					$changes			 = $this->getModificationMSG($getOldDifferenceDoc, false);
					if ($changes != '')
					{
						$changesForDocLog	 = "<br> Old Values: " . $changes;
						$desc				 .= $changesForDocLog;
					}

					VehiclesLog::model()->createLog($vhc_id, $desc, $userInfo, $event_id, false, false);
					$transaction->commit();
					if (Yii::app()->request->isAjaxRequest)
					{
						$data = ['success' => true];
						echo json_encode($data);
						Yii::app()->end();
					}
				}
				catch (Exception $e)
				{
					$vmodel->addError("bkg_id", $e->getMessage());
					$transaction->rollback();
				}

				$success = true;
			}
			else
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					$result = [];
					foreach ($model->getErrors() as $attribute => $errors)
					{
						$result[CHtml::activeId($model, $attribute)] = $errors;
					}
					$data = ['success' => $success, 'errors' => $result];
					echo json_encode($data);
					Yii::app()->end();
				}
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('picshow', ['vmodel' => $vmodel], false, $outputJs);
	}

	public function actionImagerotate()
	{
		$vhdid		 = Yii::app()->request->getParam('vhdid');
		$rttype		 = Yii::app()->request->getParam('rttype');
		$vhdModel	 = VehicleDocs::model()->findByPk($vhdid);
		if ($vhdModel->vhd_s3_data != '')
		{
			/** @var Stub\common\SpaceFile $spaceFile */
			$spaceFile		 = \Stub\common\SpaceFile::populate($vhdModel->vhd_s3_data);
			$filePath		 = $vhdModel->vhd_file;
			$fileInArr		 = explode("/", $filePath);
			$imagePath		 = $fileInArr[count($fileInArr) - 1];
			$path			 = VehicleDocs::model()->createFolderPath($vhdModel->vhd_vhc_id, $imagePath);
			$localFilename	 = Yii::app()->basePath . $path;
			if ($spaceFile->key != NULL)
			{
				$files = $spaceFile->getFile();
				$spaceFile->getFile()->download($localFilename);
				if ($files)
				{
					$vhdModel->vhd_file		 = $path;
					$vhdModel->vhd_s3_data	 = NULL;
					$vhdModel->save();
				}
			}
		}
		$fileType = pathinfo($vhdModel->vhd_file, PATHINFO_EXTENSION);
		if ($vhdModel && $fileType != 'pdf')
		{
			$rotateFilename	 = Yii::app()->basePath . $vhdModel->vhd_file; // PATH
			$degrees		 = 90;
			if ($rttype == 'right')
			{
				$degrees = 270;
			}
			if ($fileType == 'png' || $fileType == 'PNG')
			{
				header('Content-type: image/png');
				$source	 = imagecreatefrompng($rotateFilename);
				$bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);
				// Rotate
				$rotate	 = imagerotate($source, $degrees, $bgColor);
				imagesavealpha($rotate, true);
				imagepng($rotate, $rotateFilename);
			}
			if ($fileType == 'jpg' || $fileType == 'jpeg')
			{
				header('Content-type: image/jpeg');
				$source	 = imagecreatefromjpeg($rotateFilename);
				// Rotate
				$rotate	 = imagerotate($source, $degrees, 0);
				imagejpeg($rotate, $rotateFilename);
			}
			imagedestroy($source);
			imagedestroy($rotate);
			$picpath = VehicleDocs::getDocPathById($vhdModel->vhd_id);
			echo json_encode(['success' => true, 'imagefile' => $picpath]);
			Yii::app()->end();
		}
	}

	public function actionUndertakingPreview()
	{
		$vhcId	 = Yii::app()->request->getParam('vhc_id', 0);
		$vndId	 = Yii::app()->request->getParam('vnd_id', 0);
		$vvhcId	 = Yii::app()->request->getParam('linkId', 0);
		if (!$vhcId && !$vndId && !$vvhcId)
		{
			echo "No data provided";
			Yii::app()->end();
		}

		$data = VendorVehicle::model()->findUndertakingByVndVhcId($vhcId, $vndId, $vvhcId);
		if (!$data['vvhc_id'])
		{
			echo "There is some issue in linking";
			Yii::app()->end();
		}
		$data['vvhc_digital_flag']	 = 1; 
		$imgPath					 = VendorVehicle::model()->getLOUPathS3($data['vvhc_id']);
		$this->renderPartial('generate_undertaking', array('model' => $data, 'data' => $data, 'docPath' => $imgPath), false, true);
	}

	public function actionAssureCabInfo()
	{
		$bkgId	 = Yii::app()->request->getParam('bkg_id');
		$show	 = Yii::app()->request->getParam('show');
		$model	 = Booking::model()->findByPk($bkgId);
		$this->renderPartial('assurecabinfo', array('model' => $model, 'show' => $show), false, true);
	}

	public function actionGenerateAgreementForVehicle()
	{
		$vvhcId	 = urldecode(Yii::app()->request->getParam('vvhcId'));
		$ds		 = urldecode(Yii::app()->request->getParam('ds'));
		$email	 = Yii::app()->request->getParam('email');
		$address = Config::getGozoAddress(Config::Corporate_address, true);
		if ($vvhcId > 0)
		{
			if ($ds == 1)
			{
				$data = VendorVehicle::model()->findUndertakingByVndVhcId(0, 0, $vvhcId);
			}

			$data['host'] = Yii::app()->params['host'];
		}
		$html2pdf					 = Yii::app()->ePdf->mPdf();
		$css						 = file_get_contents(Yii::getPathOfAlias('webroot.assets.css') . '/vendorpdf.css');
		$html2pdf->writeHTML($css, 1);
		$html2pdf->setAutoTopMargin	 = 'stretch';
		$html2pdf->setHTMLHeader('<table border="0" cellpadding="0" cellspacing="0" width="702" class="no-border header">
                    <tbody>
						<tr>
							<td style="text-align: left"><img src="http://www.gozocabs.com/images/print-logo.png" style="height: 60px"/><br><span style="font-weight: normal; font-size: 12pt">Gozo Technologies Private Limited</span></td>
							<td style="text-align: right"><table class="borderless"><tr><td style="text-align: left; font-size: 10pt">
									<strong>Corporate Office:</strong><br>
									' . $address . '
										</td></tr></table></td>
						</tr></tbody></table><hr>');
		$html2pdf->setHTMLFooter('<table id="footer" style="width: 100%"> <tr><td style="text-align: center"><hr>www.gozocabs.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;info@gozocabs.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9051 877 000</td></tr></table>');
		$html2pdf->writeHTML($this->renderPartial('generate_undertaking', array(
					'data' => $data,
						), true));
		if ($email == 1)
		{
			$filename		 = $vvhcId . '-undertaking-' . date('Y-m-d') . '.pdf';
			$fileBasePath	 = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'vendors';
			if (!is_dir($fileBasePath))
			{
				mkdir($fileBasePath);
			}
			$filePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . $vendorId;
			if (!is_dir($filePath))
			{
				mkdir($filePath);
			}
			$file = $filePath . DIRECTORY_SEPARATOR . $filename;
			$html2pdf->Output($file, 'F');
			echo $file;
			Yii::app()->end();
		}
		else
		{
			$html2pdf->Output();
		}
	}

	public function actionUberapprove()
	{
		$vhcId				 = Yii::app()->request->getParam('vhc_id');
		$vhcIsUberApproved	 = Yii::app()->request->getParam('vhc_is_uber_approved');
		$userInfo			 = UserInfo::getInstance();
		$checkaccess		 = Yii::app()->user->checkAccess('vendorChangestatus');
		if (!$checkaccess)
		{
			$success = false;
			$error	 = "You are not authorized for this action. Contact your operation manager.";
		}
		else
		{
			/* @var $model Vehicles */
			$model	 = Vehicles::model()->resetScope()->findByPk($vhcId);
			$success = false;
			if (isset($vhcIsUberApproved) && $vhcIsUberApproved != '')
			{
				$model->vhc_is_uber_approved = $vhcIsUberApproved;
				if ($model->save())
				{
					$success = true;
				}
				else
				{
					$error	 = "Data no saved correctly";
					$success = false;
				}
				if (Yii::app()->request->isAjaxRequest)
				{
					$data = ['success' => $success, 'error' => $error];
					echo json_encode($data);
					Yii::app()->end();
				}
			}
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'error' => $error];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	/**
	 * This function is used for mapping both models to category and
	 * also vehicle category to service class
	 */
	public function actionMapCab()
	{
		$requestInstance = Yii::app()->request;

		/**
		 * This value determines what type to data needs to rendered
		 * 1 = Vehicle type to vehicle category mapping details
		 * 2 = Vehicle category to service class mapping details
		 */
		$type = $requestInstance->getParam("type");

		if ($type == 1)
		{
			$this->pageTitle = "Map Model";
			$dataToRender	 = VcvCatVhcType::VTypeVCatMapping("array");
		}

		if ($type == 2)
		{
			$this->pageTitle = "Map Category";
			$dataToRender	 = SvcClassVhcCat::ScVcMapping("array");
		}

		//This function is used for updating and inserting new mappings
		if ($requestInstance->isAjaxRequest)
		{
			$receivedData	 = $requestInstance->getParam("dataToMap");
			$return			 = Lookup::updateDetails($receivedData);

			echo json_encode($return);
			exit;
		}

		$render = "render";
		if (Yii::app()->request->isAjaxRequest)
		{
			$render = "renderPartial";
		}

		$this->$render("mapModel", array("dataToRender" => $dataToRender, "post" => $_POST));
	}

	/**
	 * This function is used for validating the vehicle bag capacity
	 */
	public function actionVehicleTypeById()
	{
		$success	 = false;
		$scvId		 = Yii::app()->request->getParam("vehicleId");
		$scvMapModel = SvcClassVhcCat:: getVctSvcList("object", 0, 0, $scvId);

		//$vhtmodel	 = VehicleTypes::model()->findbyPk($vhtid);
		if ($scvMapModel)
		{
			$success = true;
		}
		$data = [
			"success"				 => $success,
			"vht_capacity"			 => $scvMapModel->vct_capacity,
			"vht_bag_capacity"		 => $scvMapModel->vct_small_bag_capacity,
			"vht_big_bag_capacity"	 => $scvMapModel->vct_big_bag_capacity
		];

		echo json_encode($data);
	}

	public function actionVehicleDetails()
	{
		$zoneId			 = Yii::app()->request->getParam('id');
		$tierId			 = Yii::app()->request->getParam('tier');
		$qry			 = [];
		$qry['zone_id']	 = trim(Yii::app()->request->getParam('id'));
		$data			 = Vehicles::getDetailsByZoneID($zoneId, $tierId);

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->renderPartial('vehicleDetails', array('dataProvider' => $data, 'qry' => $qry), false, $outputJs);
	}

	public function actionShowdoc()
	{
		$vhdid		 = Yii::app()->request->getParam('vhdid');
		$vmodel		 = VehicleDocs::model()->findByPk($vhdid);
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('showdoc', ['vmodel' => $vmodel], false, $outputJs);
	}

	public function actionUpdateDetails()
	{
		$vhc_id		 = Yii::app()->request->getParam('vhc_id');
		$returnSet	 = new ReturnSet();
		if ($vhc_id > 0)
		{
			$returnSet = Vehicles::model()->updateDetails($vhc_id);
			echo json_encode(['success' => $returnSet->getStatus(), 'message' => $returnSet->getMessage()]);
		}
		else
		{
			echo json_encode(['success' => false, 'message' => "Please provide your Cab Id "]);
		}
		Yii::app()->end();
	}

	public function actionShowboostdocimg()
	{
		$vhcId		 = Yii::app()->request->getParam('vhcId');
		$boost		 = Yii::app()->request->getParam('boost') | 0;
		$vmodel		 = VehicleDocs::model()->getBoostDocsByVhcId($vhcId);
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('showboostdocimg', ['vmodel' => $vmodel, 'boost' => $boost], false, $outputJs);
	}

	public function actionShowAllCarImg()
	{
		$bpayBkgId	 = Yii::app()->request->getParam('bpayBkgId');
		$rootPage	 = Yii::app()->request->getParam('rootPage');
		$Type		 = [8, 9, 10, 11];
		$bmodel		 = BookingPayDocs::model()->getBoostDocsByBkgId($bpayBkgId);
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('showallcarimg', ['bmodel' => $bmodel, 'rootPage' => $rootPage], false, $outputJs);
	}

	public function actionOdometerView()
	{
		$request		 = Yii::app()->request;
		$pagetitle		 = "View Odometer";
		$this->pageTitle = $pagetitle;
		$bkg_id			 = Yii::app()->request->getParam('id');
		$type			 = Yii::app()->request->getParam('type');
		if ($bkg_id != '')
		{
			$odometerData = Vehicles::model()->getOdometerImage($bkg_id, $type);
		}
		else
		{
			throw new CHttpException(404, 'Image not found.');
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('showodometer', ['document' => $odometerData], false, $outputJs);
	}

	public function actionRulelist()
	{
		$pagetitle		 = "Rules for cab area wise";
		$this->pageTitle = $pagetitle;
		$model			 = new ServiceClassRule();
		$tripType		 = Filter::bookingTypes();

		$model->scr_scv_id						 = Yii::app()->request->getParam('ServiceClassRule')['scr_scv_id'];
		$model->scr_zone_id						 = Yii::app()->request->getParam('ServiceClassRule')['scr_zone_id'];
		$model->scr_state_id					 = Yii::app()->request->getParam('ServiceClassRule')['scr_state_id'];
		$model->scr_city_id						 = Yii::app()->request->getParam('ServiceClassRule')['scr_city_id'];
		$model->scr_region_id					 = Yii::app()->request->getParam('ServiceClassRule')['scr_region_id'];
		$model->scr_trip_type					 = Yii::app()->request->getParam('ServiceClassRule')['scr_trip_type'];
		$model->scr_is_allowed					 = Yii::app()->request->getParam('ServiceClassRule')['scr_is_allowed'][0];
		$sort									 = Yii::app()->request->getParam('sort');
		$dataProvider							 = $model->getList($sort);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$outputJs								 = Yii::app()->request->isAjaxRequest;
		$method									 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('cabrulelist', array('model' => $model, 'dataProvider' => $dataProvider, 'tripType' => $tripType), null, $outputJs);
	}

	public function actionAddcabrule()
	{
		$this->pageTitle = "Add Cab Rules";
		$model			 = new ServiceClassRule();
		$isAllow		 = ['No', 'Yes'];
		$id				 = Yii::app()->request->getParam('id');
		if ($id > 0)
		{
			$model = ServiceClassRule::model()->findByPk($id);
		}
		if (!empty($_POST['ServiceClassRule']))
		{
			$ServiceClassRuleData = Yii::app()->request->getParam('ServiceClassRule');
			if ($id > 0)
			{
				$logJson = ServiceClassRule::getLogJson($model->scr_log, $model, $ServiceClassRuleData);
			}
			$model->attributes = $ServiceClassRuleData;
			if ($logJson)
			{
				$model->scr_log = $logJson;
			}
			if ($model->isNewRecord)
			{
				$data					 = SvcClassVhcCat::model()->getVctIdSccIdByScvId($model['scr_scv_id']);
				$model->scr_scc_class	 = $data['scv_scc_id'];
				$model->scr_vhc_category = $data['scv_vct_id'];
				$model->scr_vht_id		 = $data['scv_model'];
				$model->scenario		 = 'create';
			}
			if ($model->save())
			{
				$this->redirect('rulelist');
				Yii::app()->user->setFlash('success', "Cab Rules added successfully.");
			}
			else
			{
				Yii::app()->user->setFlash('error', 'Cab Rules update not success<br/>');
				foreach ($model->getErrors() as $attribute => $errors)
				{
					foreach ($errors as $value)
					{
						Yii::app()->user->setFlash('error', 'Cab Rules update not success<br/>' . $value . "<br/>");
					}
				}
			}
		}
		$this->render('addcabrule',
				array('model'		 => $model,
					'isAllow'	 => $isAllow,
				), false, true);
	}

	public function actionStatus_old()
	{
		$id					 = Yii::app()->getRequest()->getParam('id');
		$model				 = ServiceClassRule::model()->findByPk($id);
		$model->scr_active	 = 1 - $model->scr_active;
		if ($model->save())
		{
			$this->redirect('rulelist');
		}
	}

	public function actionRuleStatus()
	{
		$id				 = Yii::app()->getRequest()->getParam('id');
		$is_rule_active	 = Yii::app()->request->getParam('scr_active');

		$model = ServiceClassRule::model()->resetScope()->findByPk($id);

		/* @var $ruleModel DriversLog */
		$ruleModel = new ServiceClassRule();

		$success = false;

		switch ($is_rule_active)
		{
			case 0:
				$model->scr_active = 1;

				break;
			case 1:
				$model->scr_active = 0;
				break;
		}
		if ($model->save())
		{
			$success = true;
		}
		else
		{
			$success = false;
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => true];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	public function actionModellist()
	{
		$sccId	 = Yii::app()->request->getParam('sccId');
		$vctId	 = Yii::app()->request->getParam('vctId');

		$carModelsSelectTier = ServiceClassRule::model()->getCabByClassId($sccId, 0, $vctId);
		if (Yii::app()->request->isAjaxRequest)
		{
			echo CJSON::encode($carModelsSelectTier);
			return $carModelsSelectTier;
			Yii::app()->end();
		}
	}

	public function actionShowRulesLog()
	{
		$rule_id	 = Yii::app()->request->getParam('id');
		$viewType	 = Yii::app()->request->getParam('view');
		if ($rule_id > 0)
		{
			$model = ServiceClassRule::model()->findByPk($rule_id);
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . (($outputJs) ? "Partial" : "");

		$this->$method('showRulesLog', ['model' => $model], false, true);
	}

	public function actionAddremark()
	{
		$vhc_id	 = Yii::app()->request->getParam('vhc_id');
		$reason	 = Yii::app()->request->getParam('vhc_remark');
		$model	 = Vehicles::model()->findByPk($vhc_id);
		$success = false;
		if (isset($_POST['vhc_id']) && $_POST['vhc_id'] == $model->vhc_id)
		{
			if (isset($_POST['vhc_remark']) && trim($reason) != '')
			{
				if ($model->update())
				{
					$event_id	 = VehiclesLog::VEHICLE_REMARK_ADDED;
					$desc		 = "Remarks : " . trim($reason);
					VehiclesLog::model()->createLog($model->vhc_id, $desc, UserInfo::getInstance(), $event_id, false, false);
					$success	 = true;
				}
				else
				{
					$result			 = [];
					$result['error'] = 'Some Error occured';
				}
				$result['success'] = $success;
			}
			else
			{
				$result				 = [];
				$result['error']	 = 'Remarks is blank';
				$result['success']	 = $success;
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				echo json_encode($result);
				Yii::app()->end();
			}
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('addremark', array('model' => $model), FALSE, $outputJs);
	}

	public function actionShowDocumentLog()
	{
		$request	 = Yii::app()->request;
		$vhcId		 = $request->getParam('vhcId');
		$id			 = $request->getParam('id');
		$viewType	 = $request->getParam('view');
		$vhcId		 = $vhcId . $id;
		if ($vhcId != '')
		{
			$dataProvider = VehiclesLog::model()->getDocumentLogByVehicleId($vhcId, $viewType);
		}
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->renderPartial('documentlog', array(
			'model'			 => $model,
			'dataProvider'	 => $dataProvider), false, true);
	}
}
