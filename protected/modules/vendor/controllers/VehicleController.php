<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class VehicleController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = 'column1';
	public $pageTitle	 = '';
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
				'actions'	 => array('getdriver'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('vehiclelist', 'edit', 'editdriver', 'cityfromstate1', 'add', 'adddriver', 'edit1',
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array('getdriver'),
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
			//$ri = array();
			$ri	 = array(  '/edit1',   '/info', '/statusDetails',   '/updateCngRooftopFlag');
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
		 *  Old Service :  update_undertaking ( METHOD POST )
		 *  New Service :  updateUnderTaking  ( METHOD POST )
		 */
		$this->onRest('req.post.updateUnderTaking.render', function () {

			return $this->renderJSON($this->updateUnderTaking());
		});
		/*
		 *  old service : unlink_vendor_driver  (method : POST)
		 *  new service : unlinkVendorDriver    (method : POST)
		 */
		$this->onRest('req.post.unlinkVendorDriver.render', function () {

			return $this->renderJSON($this->unlinkVendorDriver());
		});

		/*
		 *  old service : unlink_vendor_vehicle  (method : POST)
		 *  new service : unlinkVendorVehicle    (method : POST)
		 */
		$this->onRest('req.post.unlinkVendorVehicle.render', function () {

			return $this->renderJSON($this->unlinkVendorVehicle());
		});

		/*
		 *  old service : statusDetails         (method : POST)
		 *  new service : vehicleStatusDetails   (method : POST)
		 */
		$this->onRest('req.post.vehicleStatusDetails.render', function () {

			return $this->renderJSON($this->vehicleStatusDetails());
		});

		/*
		 *  old service : vendor_add_cab  (method : POST)
		 *  new service : vendorAddCab_v1    (method : POST)
		 */
		$this->onRest('req.post.vendorAddCab_v1.render', function () {

			return $this->renderJSON($this->vendorAddCab());
		});
		/*
		 *  old service :          (method : GET)
		 *  new service : getList  (method : GET)
		 */
		$this->onRest('req.get.getList.render', function () {



			return $this->renderJSON($this->getList());
		});

		$this->onRest('req.post.tripGetList.render', function () {

			return $this->renderJSON($this->tripGetList());
		});
		/*
		 *  old service : vendor_cab_driver_list from driver controller (method : GET)
		 *  new service : getCabList     (method : GET)
		 */
		$this->onRest('req.get.getCabList.render', function () {
			return $this->renderJSON($this->getCabList());
		});
		/*
		 *  old service : editinfo1        (method : POST)
		 *  new service : editInformation  NOT IN USE (method : POST)
		 */
		$this->onRest('req.post.editInformation.render', function () {
			return $this->renderJSON($this->editInformation());
		});
		/*
		 *  old service : edit1              (method : POST)
		 *  new service : updateInformation  (method : POST)
		 */
		$this->onRest('req.post.updateInformation.render', function () {
			return $this->renderJSON($this->updateInformation());
		});
		/*
		 *  old service :              (method : POST)
		 *  new service : uploadFiles  (method : POST)
		 */
		$this->onRest('req.post.uploadFiles.render', function () {
			return $this->renderJSON($this->uploadFiles());
		});
		/*
		 *  old service :                    (method : POST)
		 *  new service : uploadContactFile  (method : POST)
		 */
		$this->onRest('req.post.uploadContactFile.render', function () {
			return $this->renderJSON($this->uploadContactFile());
		});
		/*
		 *  old service : undertaking_info (method : POST)
		 *  new service : undertakingInfo  (method : POST)
		 */
		$this->onRest('req.post.undertakingInfo.render', function () {
			return $this->renderJSON($this->undertakingInfo());
		});

		$this->onRest('req.get.louList.render', function () {
			return $this->renderJSON($this->getLouList());
		});

		/*
		 *  old service : undertaking_info (method : POST)
		 *  new service : undertakingInfo  (method : POST)
		 */
		$this->onRest('req.post.checkDuplicateVehicle_v1.render', function () {
			return $this->renderJSON($this->checkDuplicateVehicle());
		});

		$this->onRest('req.post.getVehicleDetails.render', function () {
			return $this->renderJSON($this->getDetails());
		});
		$this->onRest('req.post.editVehicleInfo.render', function () {
			return $this->renderJSON($this->updateInformation_v1());
		});

		$this->onRest('req.post.vendor_add_cab.render', function () {
			Logger::create('41 vendor_add_cab ', CLogger::LEVEL_TRACE);
			$token				 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result				 = Vendors::model()->authoriseVendor($token);
			$isDocumentUploaded	 = false;
			$isLinked			 = false;
			$success			 = false;
			$errors				 = '';
			if ($result == true)
			{
				$vendorId			 = UserInfo::getEntityId();
				$process_sync_data	 = Yii::app()->request->getParam('data');
				//$process_sync_data = '{"vhc_type_id":"16","vhc_year":"2015","vhc_number":"TY 56 TY 5678","vhc_desc":"fty"}';
				$data				 = CJSON::decode($process_sync_data, true);
				$userInfo			 = UserInfo::getInstance();
				$vhc_number			 = strtolower(trim($data['vhc_number']));
				$data1				 = Vehicles::model()->checkDuplicateVehicleNo($vhc_number);
				if ($data1[0]['vhc_id'] > 0)
				{
					$success	 = true;
					$vhcModel	 = Vehicles::model()->findByPk($data1[0]['vhc_id']);
					if ($vendorId > 0 && $data1[0]['vhc_id'] > 0)
					{
						$value	 = ['vendor' => $vendorId, 'vehicle' => $data1[0]['vhc_id'], 'vhcOwner' => $data1[0]['vhc_reg_owner']];
						$linked	 = VendorVehicle::model()->checkAndSave($value);
						if ($linked)
						{
							//unlink other
							$unlink		 = VendorVehicle::model()->unlinkOther($data1[0]['vhc_id'], $vendorId);
							$isLinked	 = true;
							$docs		 = [];
							$count		 = 0;
							$docs		 = ['reg_certificate' => 0, 'insurance' => 0];
							$listDocs	 = VehicleDocs::model()->findAllByVhcId($data1[0]['vhc_id']);
							if ($data1[0]['vhc_insurance_exp_date'] != NULL && $data1[0]['vhc_reg_exp_date'] != NULL)
							{
								$insurance_exp_date		 = strtotime($data1[0]['vhc_insurance_exp_date']);
								$insurance_today_date	 = strtotime(date('Y-m-d'));
								$vhc_reg_exp_date		 = strtotime($data1[0]['vhc_reg_exp_date']);
								$vhc_reg_today_date		 = strtotime(date('Y-m-d'));
								if ($insurance_exp_date >= $insurance_today_date)
								{
									foreach ($listDocs as $doc)
									{
										$type	 = $doc['vhd_type'];
										$status	 = $doc['vhd_status'];
										switch ($doc['vhd_type'])
										{
											case 1:
												if ($doc['vhd_status'] == 0 || $doc['vhd_status'] == 1)
												{
													$docs['insurance']	 = 1;
													$count				 = ($count + 1);
												}
												break;
										}
									}
								}
								else
								{
									$docs['insurance'] = 0;
								}

								if ($vhc_reg_exp_date >= $vhc_reg_today_date)
								{
									foreach ($listDocs as $doc)
									{
										$type	 = $doc['vhd_type'];
										$status	 = $doc['vhd_status'];
										switch ($doc['vhd_type'])
										{
											case 5:
												if ($doc['vhd_status'] == 0 || $doc['vhd_status'] == 1)
												{
													$docs['reg_certificate'] = 1;
													$count					 = ($count + 1);
												}
												break;
										}
									}
								}
								else
								{
									$docs['reg_certificate'] = 0;
								}
							}
							else if ($data1[0]['vhc_insurance_exp_date'] != NULL)
							{
								$insurance_exp_date		 = strtotime($data1[0]['vhc_insurance_exp_date']);
								$insurance_today_date	 = strtotime(date('Y-m-d'));
								if ($insurance_exp_date >= $insurance_today_date)
								{
									foreach ($listDocs as $doc)
									{
										$type	 = $doc['vhd_type'];
										$status	 = $doc['vhd_status'];
										switch ($doc['vhd_type'])
										{
											case 1:
												if ($doc['vhd_status'] == 0 || $doc['vhd_status'] == 1)
												{
													$docs['insurance']	 = 1;
													$count				 = ($count + 1);
												}
												break;
										}
									}
								}
								else
								{
									$docs['insurance'] = 0;
								}
								$docs ['reg_certificate'] = 0;
							}
							else if ($data1[0]['vhc_reg_exp_date'] != NULL)
							{
								$vhc_reg_exp_date	 = strtotime($data1[0]['vhc_reg_exp_date']);
								$vhc_reg_today_date	 = strtotime(date('Y-m-d'));
								if ($vhc_reg_exp_date >= $vhc_reg_today_date)
								{
									foreach ($listDocs as $doc)
									{
										$type	 = $doc['vhd_type'];
										$status	 = $doc['vhd_status'];
										switch ($doc['vhd_type'])
										{
											case 5:
												if ($doc['vhd_status'] == 0 || $doc['vhd_status'] == 1)
												{
													$docs['reg_certificate'] = 1;
													$count					 = ($count + 1);
												}
												break;
										}
									}
								}
								else
								{
									$docs['reg_certificate'] = 0;
								}
								$docs ['insurance'] = 0;
							}
							$isDocumentUploaded	 = $count == 2 ? true : false;
							$success			 = true;
						}
					}
				}
				else
				{
					$vhcModel	 = new Vehicles();
					$returnData	 = $vhcModel->addVehicle($data, $vendorId);
					VendorStats::model()->updateCarTypeCount($vendorId);
					$success	 = $returnData['success'];
					$errors		 = $returnData['errors'];
				}
			}
			else
			{
				$errors = 'Vendor Unauthorised';
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'isLinked'			 => $isLinked,
					'success'			 => $success,
					'errors'			 => $errors,
					'vechileId'			 => $vhcModel->vhc_id,
					'vechileNo'			 => $data['vhc_number'],
					'isDocumentUploaded' => $isDocumentUploaded,
					'docs'				 => $docs,
				)
			]);
		});

		$this->onRest('req.post.editinfo1.render', function () {

			Logger::create('47 editinfo1 ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{

				$vendorId				 = UserInfo::getEntityId();
				$success				 = false;
				$errors					 = 'Something went wrong';
				$process_sync_data		 = Yii::app()->request->getParam('data');
				$data1					 = CJSON::decode($process_sync_data, true);
				//$vehicleId				 = Yii::app()->request->getParam('vhc_id');
				$vehicleId				 = $data1['vhc_id'];
				$model					 = Vehicles::model()->findByPk($vehicleId);
				$insurance_exp_date		 = ($model->vhc_insurance_exp_date != NULL) ? $model->vhc_insurance_exp_date : '';
				$insurance_exp_date		 = (date("Y-m-d", strtotime($model->vhc_insurance_exp_date)) != '1970-01-01') ? $model->vhc_insurance_exp_date : '';
				$pollution_exp_date		 = ($model->vhc_pollution_exp_date != NULL) ? $model->vhc_pollution_exp_date : '';
				$pollution_exp_date		 = (date("Y-m-d", strtotime($model->vhc_pollution_exp_date)) != '1970-01-01') ? $model->vhc_pollution_exp_date : '';
				$reg_exp_date			 = ($model->vhc_reg_exp_date != NULL) ? $model->vhc_reg_exp_date : '';
				$reg_exp_date			 = (date("Y-m-d", strtotime($model->vhc_reg_exp_date)) != '1970-01-01') ? $model->vhc_reg_exp_date : '';
				$commercial_exp_date	 = ($model->vhc_commercial_exp_date != NULL && $model->vhc_commercial_exp_date != '1970-01-01') ? $model->vhc_commercial_exp_date : '';
				$commercial_exp_date	 = (date("Y-m-d", strtotime($model->vhc_commercial_exp_date)) != '1970-01-01') ? $model->vhc_commercial_exp_date : '';
				$fitness_cert_end_date	 = ($model->vhc_fitness_cert_end_date != NULL && $model->vhc_fitness_cert_end_date != '1970-01-01') ? $model->vhc_fitness_cert_end_date : '';
				$fitness_cert_end_date	 = (date("Y-m-d", strtotime($model->vhc_fitness_cert_end_date)) != '1970-01-01') ? $model->vhc_fitness_cert_end_date : '';
				$tax_exp_date			 = ($model->vhc_tax_exp_date != NULL) ? $model->vhc_tax_exp_date : '';
				$tax_exp_date			 = (date("Y-m-d", strtotime($model->vhc_tax_exp_date)) != '1970-01-01') ? $model->vhc_tax_exp_date : '';
				$dop					 = ($model->vhc_dop != NULL) ? $model->vhc_dop : '';
				$dop					 = (date("Y-m-d", strtotime($model->vhc_dop)) != '1970-01-01') ? $model->vhc_dop : '';

				$data								 = ['vhc_id'					 => $model->vhc_id
					, 'vhc_type_id'				 => $model->vhc_type_id
					, 'vhc_number'				 => $model->vhc_number
					, 'vhc_year'					 => $model->vhc_year
					, 'vhc_color'					 => $model->vhc_color
					, 'vhc_has_cng'				 => $model->vhc_has_cng
					, 'vhc_has_rooftop_carrier'	 => $model->vhc_has_rooftop_carrier
					, 'vhc_reg_owner'				 => ($model->vhc_reg_owner != NULL) ? $model->vhc_reg_owner : ''
					, 'vhc_owned_or_rented'		 => $model->vhc_owned_or_rented
					, 'vhc_is_attached'			 => $model->vhc_is_attached
					, 'vhc_front_plate'			 => ($model->vhc_front_plate != NULL) ? $model->vhc_front_plate : ''
					, 'vhc_rear_plate'			 => ($model->vhc_rear_plate != NULL) ? $model->vhc_rear_plate : ''
					, 'vhc_insurance_proof'		 => ($model->vhc_insurance_proof != NULL) ? $model->vhc_insurance_proof : ''
					, 'vhc_dop'					 => $dop
					, 'vhc_insurance_exp_date'	 => $insurance_exp_date
					, 'vhc_pollution_exp_date'	 => $pollution_exp_date
					, 'vhc_reg_exp_date'			 => $reg_exp_date
					, 'vhc_commercial_exp_date'	 => $commercial_exp_date
					, 'vhc_fitness_cert_end_date'	 => $fitness_cert_end_date
					, 'vhc_tax_exp_date'			 => $tax_exp_date];
				$dataDocs							 = VehicleDocs::model()->findAllByVhcId($vehicleId);
				$data['vhc_insurance_proof']		 = '';
				$data['vhc_front_plate']			 = '';
				$data['vhc_rear_plate']				 = '';
				$data['vhc_pollution_certificate']	 = '';
				$data['vhc_reg_certificate']		 = '';
				$data['vhc_permits_certificate']	 = '';
				$data['vhc_fitness_certificate']	 = '';
				if (count($dataDocs) > 0)
				{
					foreach ($dataDocs as $mdoc)
					{
						switch ($mdoc['vhd_type'])
						{
							case 1:
								$data['vhc_insurance_proof']		 = ($mdoc['vhd_status'] == 2) ? '' : $mdoc['vhd_file'];
								break;
							case 2:
								$data['vhc_front_plate']			 = ($mdoc['vhd_status'] == 2) ? '' : $mdoc['vhd_file'];
								break;
							case 3:
								$data['vhc_rear_plate']				 = ($mdoc['vhd_status'] == 2) ? '' : $mdoc['vhd_file'];
								break;
							case 4:
								$data['vhc_pollution_certificate']	 = ($mdoc['vhd_status'] == 2) ? '' : $mdoc['vhd_file'];
								break;
							case 5:
								$data['vhc_reg_certificate']		 = ($mdoc['vhd_status'] == 2) ? '' : $mdoc['vhd_file'];
								break;
							case 6:
								$data['vhc_permits_certificate']	 = ($mdoc['vhd_status'] == 2) ? '' : $mdoc['vhd_file'];
								break;
							case 7:
								$data['vhc_fitness_certificate']	 = ($mdoc['vhd_status'] == 2) ? '' : $mdoc['vhd_file'];
								break;
						}
					}
				}
				if ($model != '')
				{
					$success = true;
					$errors	 = '';
				}
			}
			else
			{
				$success = false;
				$errors	 = 'Vendor Unauthorised';
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => $data,
					'dataDocs'	 => $dataDocs
				)
			]);
		});

		$this->onRest('req.get.editinfo.render', function () {

			Logger::create('45 editinfo ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{
				//$zonId	 = Yii::app()->request->getParam('zon_id');
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$vehicleId			 = $data1['vhc_id'];
				//$vehicleId	 = Yii::app()->request->getParam('vhc_id');
				//$vendorId	 = Yii::app()->user->getId();
				$vendorId			 = UserInfo::getEntityId();
				$model				 = VehiclesInfo::model()->find('vhc_vehicle_id=:id', ['id' => $vehicleId]);
				$type				 = 'vehicle_info';
				if ($model == '')
				{
					$model	 = Vehicles::model()->findByPk($vehicleId);
					$type	 = 'vehicle';
				}
				$data = JSONUtil::convertModelToArray($model);
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'data'	 => $data,
					'type'	 => $type,
				)
			]);
		});

		$this->onRest('req.post.edit1.render', function () {

			Logger::create('50 edit1 ', CLogger::LEVEL_TRACE);

			$oldData = $newData = false;
			$success = false;
			$errors	 = '';

			$process_sync_data	 = Yii::app()->request->getParam('data');
			Logger::info("<Vendor Vehicle Edit1 =====>" . $process_sync_data);
			Logger::info("Vendor Vehicle Edit1 _FILES DATA: " . json_encode($_FILES));
			$data1				 = CJSON::decode($process_sync_data, true);
			$vehiclePic			 = $data1['vehiclePic'];

			$insuranceFile				 = CUploadedFile::getInstanceByName("insurance");
			$front_plateFile			 = CUploadedFile::getInstanceByName("front_plate");
			$rear_plateFile				 = CUploadedFile::getInstanceByName("rear_plate");
			$pollution_certificateFile	 = CUploadedFile::getInstanceByName("pollution_certificate");
			$reg_certificateFile		 = CUploadedFile::getInstanceByName("reg_certificate");
			$permit_certificateFile		 = CUploadedFile::getInstanceByName("permit_certificate");
			$fitness_certificateFile	 = CUploadedFile::getInstanceByName("fitness_certificate");

			$data = CJSON::decode($data1['data'], true);

			$userInfo	 = UserInfo::getInstance();
			//$vendorId	 = $userInfo->userId;
			$vendorId	 = UserInfo::getEntityId();

			if (isset($data1['vhc_id']) && $data1['vhc_id'] > 0)
			{
				$vehicleId = $data1['vhc_id'];
			}
			else
			{
				$vehicleId = $data['vhc_id'];
			}
			$data['vhc_id']	 = $vehicleId;
			$model			 = Vehicles::model()->findById($data['vhc_id']);
			$oldData		 = $model->attributes;
			$newData		 = $data;

			try
			{
				$transaction = DBUtil::beginTransaction();
				if ($data['vhc_id'] > 0)
				{
					/* @var model Vehicles */
					$model	 = Vehicles::model()->findByPk($data['vhc_id']);
					$oldData = $model->attributes;
					switch ($vehiclePic)
					{
						case 0;
							$model->scenario = 'update';
							break;
						case 1:
							$model->scenario = 'update';
							break;
						case 2:
							if (isset($insurance) && $insurance != '')
							{
								$model->scenario = 'updateApprovalIns';
							}
							else if (isset($reg_certificate) && $reg_certificate != '')
							{
								$model->scenario = 'updateApprovalRC';
							}
							break;
					}
				}
				else
				{
					$model			 = new Vehicles();
					$model->scenario = 'insertadminapp';
				}
				$model->attributes = $data;
				if ($model->validate())
				{
					if ($model->save())
					{
						$vehicleId = ($data['vhc_id'] > 0) ? $data['vhc_id'] : Yii::app()->db->lastInsertID;
						if ($data['vhc_id'] > 0)
						{
							$getOldDifference	 = array_diff_assoc($oldData, $newData);
							$changesForLog		 = "<br> Old Values: " . $this->getModificationMSG($getOldDifference, false);
							$desc				 = "Cab modified | ";
							$desc				 .= $changesForLog;
							$event_id			 = VehiclesLog::VEHICLE_MODIFIED;
						}
						else
						{
							$desc		 = "Vehicle created | ";
							$event_id	 = VehiclesLog::VEHICLE_CREATED;
						}
						VehiclesLog::model()->createLog($vehicleId, $desc, $userInfo, $event_id, false, false);
						if ($vendorId > 0 && $data['vhc_id'] > 0)
						{
							$data1	 = ['vendor' => $vendorId, 'vehicle' => $vehicleId, 'vhcOwner' => $model->vhc_reg_owner];
							$linked	 = VendorVehicle::model()->checkAndSave($data1);
						}
					}


					if ($insuranceFile != '')
					{
						$this->saveImageBycat($vehicleId, $insuranceFile, $userInfo, 1);
					}


					if ($front_plateFile != '')
					{
						$this->saveImageBycat($vehicleId, $front_plateFile, $userInfo, 2);
					}


					if ($rear_plateFile != '')
					{
						$this->saveImageBycat($vehicleId, $rear_plateFile, $userInfo, 3);
					}


					if ($pollution_certificateFile != '')
					{
						$this->saveImageBycat($vehicleId, $pollution_certificateFile, $userInfo, 4);
					}


					if ($reg_certificateFile != '')
					{
						$this->saveImageBycat($vehicleId, $reg_certificateFile, $userInfo, 5);
					}


					if ($permit_certificateFile != '')
					{
						$this->saveImageBycat($vehicleId, $permit_certificateFile, $userInfo, 6);
					}


					if ($fitness_certificateFile != '')
					{
						$this->saveImageBycat($vehicleId, $fitness_certificateFile, $userInfo, 7);
					}

					$success = DBUtil::commitTransaction($transaction);
				}
				else
				{
					$getErrors = $model->getErrors();
					throw new Exception("Validate Errors : " . $getErrors);
				}
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::create('FALIURE ===========>: [' . $getErrors . ']', CLogger::LEVEL_ERROR);
				$errors = $getErrors;
			}


			Logger::create("Response: " . json_encode($model->attributes) . " Errors : " . $errors . " Success : " . $success, CLogger::LEVEL_INFO);

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => ['vhc_id' => $model->vhc_id]
				),
			]);
		});

		$this->onRest('req.post.info.render', function () {
			$success			 = false;
			$errors				 = 'Something went wrong';
			//$vhcId	 = Yii::app()->request->getParam('vhc_id');
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$vhcId				 = $data1['vhc_id'];
			/* @var $model Vehicles */
			$model				 = Vehicles::model()->findByPk($vhcId);
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

		//Depricatted
		$this->onRest('req.post.undertaking_info.render', function () {
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{
				$vendorId	 = UserInfo::getEntityId();
				$userId		 = UserInfo::getUserId();

				Logger::create('33 undertaking_info ', CLogger::LEVEL_TRACE);
				$process_sync_data						 = Yii::app()->request->getParam('data');
				$data1									 = CJSON::decode($process_sync_data, true);
				$vhcId									 = $data1['vhc_id'];
				//$vhcId									 = Yii::app()->request->getParam('vhc_id');
				//$vndId									 = Yii::app()->request->getParam('vnd_id');
				$vndId									 = $vendorId;
				$data									 = VendorVehicle::model()->findUndertakingByVndVhcId($vhcId, $vendorId);
				$data['vnd_firm_pan']					 = ($data['vnd_firm_pan'] != '' && $data['vnd_firm_pan'] != '') ? $data['vnd_firm_pan'] : NULL;
				$data['vnd_firm_ccin']					 = ($data['vnd_firm_ccin'] != '' && $data['vnd_firm_ccin'] != '') ? $data['vnd_firm_ccin'] : NULL;
				$data['vnd_firm_type']					 = ($data['vnd_firm_type'] > 0) ? $data['vnd_firm_type'] : 0;
				$data['vhc_owner']						 = ($data['vvhc_vhc_owner'] != '' && $data['vvhc_vhc_owner'] != null) ? $data['vvhc_vhc_owner'] : NULL;
				//$data['vhc_owner']						 = ($data['vhc_owner'] != '' && $data['vhc_owner'] != null) ? $data['vhc_owner'] : NULL;
				$data['vvhc_vhc_owner_auth_valid_date']	 = ($data['vvhc_vhc_owner_auth_valid_date'] != '' && $data['vvhc_vhc_owner_auth_valid_date'] != null) ? $data['vvhc_vhc_owner_auth_valid_date'] : NULL;
				if ($data != [])
				{
					$success = true;
					$error	 = null;
				}
				else
				{
					$success = false;
					$error	 = "No records found";
				}
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'error'		 => $error,
					'data'		 => $data,
				)
			]);
		});

		$this->onRest('req.post.update_undertaking.render', function () {
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{
				$vendorId								 = UserInfo::getEntityId();
				$userId									 = UserInfo::getUserId();
				$success								 = false;
				$errors									 = 'Something went wrong while uploading';
				$process_sync_data						 = Yii::app()->request->getParam('data');
				Logger::create('POST DATA ===========>: ' . $process_sync_data, CLogger::LEVEL_TRACE);
				$data									 = CJSON::decode($process_sync_data, true);
				$model									 = VendorVehicle::model()->findByPk($data['vvhc_id']);
				$model->vvhc_vhc_owner					 = $data['vhc_owner'];
				$model->vvhc_vhc_owner_auth_valid_date	 = $data['vvhc_vhc_owner_auth_valid_date'];
				$phone									 = $data['vhc_owner_phone'];
				$email									 = $data['vhc_owner_email'];
				$proof_img								 = $_FILES['vhc_owner_proof']['name'];
				$proof_img_temp							 = $_FILES['vhc_owner_proof']['tmp_name'];
				$model->scenario						 = 'update_undertaking';
				$success								 = false;
				$transaction							 = DBUtil::beginTransaction();
				try
				{
					if ($model->validate())
					{
						$path							 = $this->saveImage($proof_img, $proof_img_temp, $model->vvhc_vnd_id);
						$model->vhc_owner_proof			 = $path[path];
						$model->vvhc_digital_is_agree	 = 0;
						if ($model->save())
						{
							$success = DBUtil::commitTransaction($transaction);
							$errors	 = [];
							$desc	 = $modelv->vnd_id . " - " . $modelv->vnd_name . " - Updated";
						}
						else
						{
							$getErrors = json_encode($model->getErrors());
							throw new Exception($getErrors);
						}
					}
					else
					{
						$getErrors = json_encode($model->getErrors());
						throw new Exception($getErrors);
					}
				}
				catch (Exception $ex)
				{
					$errors = $ex->getMessage();
					DBUtil::rollbackTransaction($transaction);
				}
			}
			else
			{
				$success = false;
				$message = "Vendor Unauthorised";
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => array("vvhc_id" => $model->vvhc_id, "vvhc_vnd_id" => $model->vvhc_vnd_id, "vvhc_vhc_id" => $model->vvhc_vhc_id)
				),
			]);
		});

		$this->onRest('req.post.undertaking_sign.render', function () {

			Logger::create('52 undertaking_sign ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{
				$success = false;
				$errors	 = 'Something went wrong while saving';

				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$vehicleKey			 = $data1['vehicleKey'];
				$digitalLat			 = $data1['digitalLat'];
				$digitalLong		 = $data1['digitalLong'];
				$static				 = $data1['static'];
				$data				 = CJSON::decode($data1['data']);
				$vvhcDigital		 = $_FILES['vvhc_digital_sign']['name'];
				$vvhcDigitalTmp		 = $_FILES['vvhc_digital_sign']['tmp_name'];

				Logger::create('POST DATA  ===========>: ' . $process_sync_data, CLogger::LEVEL_TRACE);
				$params		 = $vehicleKey . " - " . $vvhcDigital . " - " . $vvhcDigitalTmp . " - " . $digitalLat . " - " . $digitalLong;
				Logger::create('POST DATA 2 ===========>: ' . $params, CLogger::LEVEL_TRACE);
				$userInfo	 = UserInfo::getInstance();
				//$data				 = CJSON::decode($process_sync_data, true);
				//$vendorId			 = $data['vnd_id'];
				$vendorId	 = UserInfo::getEntityId();
				$userId		 = UserInfo::getUserId();
				$vehicleId	 = $data['vhc_id'];
				$vvhcId		 = $data['vvhc_id'];

				if ($vehicleKey == 2)
				{
					$appToken	 = AppTokens::model()->getByUserTypeAndUserId($userId, 2);
					$type		 = 'digital_vehicle_sign';
					$result2	 = $this->saveVehicleImage($vvhcDigital, $vvhcDigitalTmp, $vehicleId, $type);
					$path1		 = str_replace("\\", "\\\\", $result2['path']);
					Logger::create('IMAGE UPLOAD ===========>: ' . $path1, CLogger::LEVEL_TRACE);
					if (VendorVehicle::model()->updateSignature($vendorId, $vehicleId, $path1) == 1)
					{
						/* @var $model VendorVehicle */
						$model							 = VendorVehicle::model()->findByVndVhcId($vendorId, $vehicleId);
						$model->vvhc_digital_sign		 = $path1;
						$model->vvhc_digital_flag		 = 1;
						$model->vvhc_digital_lat		 = $digitalLat;
						$model->vvhc_digital_long		 = $digitalLong;
						$model->vvhc_digital_os			 = $appToken['apt_os_version'];
						$model->vvhc_digital_uuid		 = $appToken['apt_device_uuid'];
						$model->vvhc_digital_ip			 = $appToken['apt_ip_address'];
						$model->vvhc_digital_device_id	 = $appToken['apt_device'];
						$model->vvhc_active				 = 1;
						$model->vvhc_digital_date		 = date('Y-m-d H:i:s');
						$model->vvhc_digital_is_agree	 = 1;
						$model->vvhc_lou_approved		 = 3;
						$model->vvhc_lou_created_date	 = new CDbExpression('NOW()');
						if ($model->validate())
						{
							if ($model->save())
							{
								$success = true;
								$errors	 = [];
								VehiclesLog::model()->createLog($vehicleId, "LOU signature updated/added", $userInfo, VehiclesLog::VEHICLE_MODIFIED, false, false);
								Logger::create('SUCCESS ===========> : ' . "VEHCILE ID :" . $model->vvhc_vhc_id . " VENDOR ID :" . $model->vvhc_vnd_id, CLogger::LEVEL_INFO);
							}
							else
							{
								$success = false;
								$errors	 = $model->getErrors();
							}
						}
						else
						{
							$success = false;
							$errors	 = $model->getErrors();
						}
					}
					else
					{
						$success = false;
						$errors	 = $modeld->getErrors();
					}
				}
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => array("vvhc_id" => $model->vvhc_id, "vvhc_vnd_id" => $model->vvhc_vnd_id, "vvhc_vhc_id" => $model->vvhc_vhc_id),
				)
			]);
		});

		$this->onRest('req.post.unlink_vendor_driver.render', function () {

			Logger::create('35 unlink_vendor_driver ', CLogger::LEVEL_TRACE);
			$success = false;
			$errors	 = [];
			$data	 = [];

			//$vdrvId		 = Yii::app()->request->getParam('vdrvId');
			$process_sync_data = Yii::app()->request->getParam('data');

			$data1	 = CJSON::decode($process_sync_data, true);
			$vdrvId	 = $data1['vdrvId'];

			$userInfo	 = UserInfo::getInstance();
			$transaction = DBUtil::beginTransaction();
			try
			{
				if ($vdrvId > 0)
				{
					$model = VendorDriver::model()->findByPk($vdrvId);

					$data				 = array("vdrv_id" => $model->vdrv_id, "vdrv_vnd_id" => $model->vdrv_vnd_id, "vdrv_drv_id" => $model->vdrv_drv_id);
					Logger::create('POST DATA  =====>: ' . $data, CLogger::LEVEL_TRACE);
					$model->vdrv_active	 = 0;
					if ($model->save())
					{
						$descDriver	 = DriversLog::model()->getEventByEventId(DriversLog::DRIVER_VENDOR_DELETE);
						$descVendor	 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_DRIVER_DELETE);
						DriversLog::model()->createLog($model->vdrv_drv_id, $descDriver, $userInfo, DriversLog::DRIVER_VENDOR_DELETE, false, false);
						VendorsLog::model()->createLog($model->vdrv_vnd_id, $descVendor, $userInfo, VendorsLog::VENDOR_DRIVER_DELETE, false, false);
						Logger::create('SUCCESS UNLINK =====> : ' . "DRIVER ID :" . $data['vdrv_drv_id'] . " VENDOR ID :" . $data['vdrv_vnd_id'], CLogger::LEVEL_INFO);
						$success	 = true;
						if ($success == true)
						{
							DBUtil::commitTransaction($transaction);
						}
					}
					else
					{
						$errors = $model->getErrors();
					}
				}
				else
				{
					$errors = "Unlink Id can not blank.";
				}
			}
			catch (Exception $e)
			{
				DBUtil::rollbackTransaction($transaction);
				Logger::create('ERRORS =====> : ' . "Exception :" . $e->getMessage() . " Errors :" . $errors, CLogger::LEVEL_ERROR);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => $data
				),
			]);
		});

		$this->onRest('req.post.unlink_vendor_vehicle.render', function () {

			Logger::create('34 unlink_vendor_vehicle ', CLogger::LEVEL_TRACE);
			$success			 = false;
			$errors				 = 'Something went wrong while deleting';
			//$vvhcId	 = Yii::app()->request->getParam('vvhcId');
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$vvhcId				 = $data1['vvhcId'];
			$dataSet			 = [];
			if ($vvhcId != '')
			{
				$model		 = VendorVehicle::model()->findByPk($vvhcId);
				$dataSet	 = array("vvhc_id" => $model->vvhc_id, "vvhc_vnd_id" => $model->vvhc_vnd_id, "vvhc_vhc_id" => $model->vvhc_vhc_id);
				$userInfo	 = UserInfo::getInstance();
				$vhcId		 = $model->vvhc_vhc_id;
				$event_id	 = VehiclesLog::VEHICLE_VENDOR_DELETE;
				$desc		 = VehiclesLog::model()->getEventByEventId($event_id);
				$event_id2	 = VendorsLog::VENDOR_VEHICLE_DELETE;
				$desc2		 = VehiclesLog::model()->getEventByEventId($event_id2);
				if ($model->delete())
				{
					VehiclesLog::model()->createLog($vhcId, $desc, $userInfo, $event_id, false, false);
					//VendorsLog::model()->createLog($model->vvhc_vnd_id, $desc2, $userInfo, $event_id2, false, false);
					$success = true;
					$errors	 = [];
				}
				else
				{
					$success = false;
					$errors	 = $model->getErrors();
				}
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => $dataSet
				),
			]);
		});

		$this->onRest('req.post.statusDetails.render', function () {

			Logger::create('36 statusDetails ', CLogger::LEVEL_TRACE);
			$success			 = false;
			$errors				 = [];
			$isDocumentUploaded	 = false;
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			Logger::create('36 statusDetails ' . $process_sync_data, CLogger::LEVEL_TRACE);
			$vhcId				 = $data1['vhc_id'];
			$modelVhcId			 = Vehicles::model()->findByPk($vhcId);
			if ($modelVhcId > 0)
			{
				$status				 = true;
				$approveStatus		 = (int) $modelVhcId->vhc_approved;
				$activeStatus		 = (int) $modelVhcId->vhc_active;
				$isCngFlag			 = $modelVhcId->vhc_has_cng;
				$isRooftopCarrier	 = $modelVhcId->vhc_has_rooftop_carrier;
				$listDocs			 = VehicleDocs::model()->findAllByVhcId($vhcId);

				$regStatus	 = 0;
				$insStatus	 = 0;
				foreach ($listDocs as $doc)
				{
					$type		 = $doc['vhd_type'];
					$status		 = $doc['vhd_status'];
					static $cout = 0;
					switch ($doc['vhd_type'])
					{
						case 1:
							if ($doc['vhd_status'] == 0 || $doc['vhd_status'] == 1)
							{
								$insStatus	 = 1;
								$cout		 = ($cout + 1);
							}

							break;
						case 5:
							if ($doc['vhd_status'] == 0 || $doc['vhd_status'] == 1)
							{
								$regStatus	 = 1;
								$cout		 = ($cout + 1);
							}

							break;
					}
				}
				if ($isCngFlag == null || $isRooftopCarrier == null)
				{
					$isDocumentUploaded = false;
				}
				else
				{
					$isDocumentUploaded = ($cout == 2) ? true : false;
				}
				$docs	 = ['reg_certificate' => (int) $regStatus, 'insurance' => (int) $insStatus];
				$success = true;
			}
			else
			{
				$errors = "Vehicle not exist.";
			}
			$bcb_row		 = BookingCab::model()->getBkgIdByTripId($data1['bcb_id']);
			$bookingIDs		 = $bcb_row['bkg_ids'];
			$hasCngAllowed	 = BookingCab::model()->isCngAllowed($bookingIDs, $modelVhcId);
			$isCabAllowed	 = (!$hasCngAllowed['success']) ? 0 : 1;
			$cabInfo		 = (!$hasCngAllowed['success']) ? ['msg' => $hasCngAllowed['msg']] : '';

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'			 => $success,
					'errors'			 => $errors,
					'isDocumentUploaded' => $isDocumentUploaded,
					'isCngFlag'			 => $isCngFlag,
					'isRooftopCarrier'	 => $isRooftopCarrier,
					'docs'				 => $docs,
					'isCabAllowed'		 => $isCabAllowed,
					'cabInfo'			 => $cabInfo,
				)
			]);
		});

		$this->onRest('req.post.checkDuplicateVehicleNo.render', function () {

			Logger::create('37 checkVechileNo ', CLogger::LEVEL_TRACE);
			$token				 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result				 = Vendors::model()->authoriseVendor($token);
			$success			 = false;
			$isExist			 = false;
			$errors				 = '';
			$dataSet			 = [];
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$vhc_number			 = trim($data1['vhc_number']); //strtolower(trim($data1['vhc_number']));
			if ($result == true)
			{
				$vendorId	 = UserInfo::getEntityId();
				$modelVhcId	 = Vehicles::model()->checkDuplicateVehicleNo($vhc_number);
				if (count($modelVhcId) > 0)
				{
					$isExist			 = true;
					$array['vendor']	 = $vendorId;
					$array['vehicle']	 = $modelVhcId[0]['vhc_id'];
					$isSameVendor		 = VendorVehicle::model()->checkExisting($array);
					if ($isSameVendor)
					{
						$approveStatus	 = (int) $modelVhcId[0]['vhc_approved'];
						$success		 = true;
						if ($approveStatus != 1)
						{
							$vhc_id			 = $modelVhcId[0]['vhc_id'];
							$vhc_year		 = $modelVhcId[0]['vhc_year'] != NUll ? $modelVhcId[0]['vhc_year'] : "";
							$vhc_description = $modelVhcId[0]['vhc_description'] != NUll ? $modelVhcId[0]['vhc_description'] : "";
							$vhc_type_id	 = $modelVhcId[0]['vhc_type_id'] != NULL ? $modelVhcId[0]['vhc_type_id'] : "";
							$dataSet		 = array("vhc_year" => $vhc_year, "vhc_description" => $vhc_description, "vhc_type_id" => $vhc_type_id);
						}
					}
					else
					{
						$vhc_id			 = $modelVhcId[0]['vhc_id'];
						$vhc_year		 = $modelVhcId[0]['vhc_year'] != NUll ? $modelVhcId[0]['vhc_year'] : "";
						$vhc_description = $modelVhcId[0]['vhc_description'] != NUll ? $modelVhcId[0]['vhc_description'] : "";
						$vhc_type_id	 = $modelVhcId[0]['vhc_type_id'] != NULL ? $modelVhcId[0]['vhc_type_id'] : "";
						$dataSet		 = array("vhc_year" => $vhc_year, "vhc_description" => $vhc_description, "vhc_type_id" => $vhc_type_id);
						$success		 = true;
					}
				}
				else
				{
					$success = true;
				}
			}
			else
			{
				$errors = "Unauthorized access";
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'isExist'	 => $isExist,
					'data'		 => $dataSet
				)
			]);
		});

		$this->onRest('req.post.updateCngRooftopFlag.render', function () {

			$success			 = false;
			$errors				 = [];
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($process_sync_data, true);
			Logger::create('updateCngRooftopFlag ' . $process_sync_data, CLogger::LEVEL_TRACE);
			$vhcId				 = $data['vhc_id'];
			$modelVhcId			 = Vehicles::model()->findByPk($vhcId);
			if ($modelVhcId > 0)
			{
				$modelVhcId->vhc_id					 = $vhcId;
				$modelVhcId->vhc_has_cng			 = $data['vhc_has_cng_carrier'];
				$modelVhcId->vhc_has_rooftop_carrier = $data['vhc_has_rooftop_carrier'];
				$success							 = ($modelVhcId->update()) ? true : false;
				Logger::create('Success ' . $success, CLogger::LEVEL_TRACE);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
				)
			]);
		});

		$this->onRest('req.post.checkLinking.render', function () {

			return $this->renderJSON($this->checkLinking());
		});
	}

	public function checkLinking()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		Logger::trace("<===Request===>" . $data);
		try
		{
			if (empty($data))
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj = CJSON::decode($data, false);
			$vhcId	 = $jsonObj->id;
			$vndId	 = UserInfo::getEntityId();
			$result	 = VendorVehicle::checkLinking($vndId, $vhcId);
			$result1 = $result;
			$success = true;

			if ($result['isLOURequired'] == 0)
			{
				$success = VendorVehicle::activateLinkingUnlinkOthers($vndId, $vhcId);
				$result1 = VendorVehicle::checkLinking($vndId, $vhcId);
			}

			$vehicle = new Stub\common\Vehicle();
			$vehicle->linkingData($result1);
			$resdata = Filter::removeNull($vehicle);

			if ($resdata->isLOURequired == 1)
			{
				$message = "LOU is required for this cab.";
				$returnSet->setMessage($message);
			}

			$returnSet->setStatus($success);
			$returnSet->setData($resdata);
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($e);
		}
		Logger::trace("<===Response===>" . json_encode($returnSet));
		return $returnSet;
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
			if ($diff['vhc_has_cng'])
			{
				$cngStatus	 = ($diff['vhc_has_cng'] == 1) ? 'Yes' : 'No';
				$msg		 .= ' Cng Status: ' . $cngStatus . ',';
			}
			if ($diff['vhc_has_rooftop_carrier'])
			{
				$rooftopCarrierStatus	 = ($diff['vhc_has_rooftop_carrier'] == 1) ? 'Yes' : 'No';
				$msg					 .= ' Rooftop Carrier Status: ' . $rooftopCarrierStatus . ',';
			}

			if ($diff['vhc_owned_or_rented'])
			{
				$ownedrentedStatus	 = ($diff['vhc_owned_or_rented'] == 1) ? ' Yes' : 'No';
				$msg				 .= ' Vehicle owned or rented: ' . $ownedrentedStatus . ',';
			}
			if ($diff['insuranceFile'])
			{
				$msg .= ' : ' . $diff['insuranceFile'] . ',';
			}
			if ($diff['frontLicenseFile'])
			{
				$msg .= ' : ' . $diff['frontLicenseFile'] . ',';
			}
			if ($diff['rearLicenseFile'])
			{
				$msg .= ' : ' . $diff['rearLicenseFile'] . ',';
			}
			if ($diff['pollutionFile'])
			{
				$msg .= ' : ' . $diff['pollutionFile'] . ',';
			}
			if ($diff['registrationFile'])
			{
				$msg .= ' : ' . $diff['registrationFile'] . ',';
			}
			if ($diff['permitFile'])
			{
				$msg .= ' : ' . $diff['permitFile'] . ',';
			}
			if ($diff['fitnessFile'])
			{
				$msg .= ' : ' . $diff['fitnessFile'] . ',';
			}
			$msg = rtrim($msg, ',');
		}
		return $msg;
	}

	/**
	 * @deprecated since version 10-10-2019
	 * @author ramala
	 */
	public function actionVehiclelist()
	{
		$this->layout	 = 'admin1';
		$this->pageTitle = "Vehicle List";
		$hashId			 = Yii::app()->request->getParam('code');
		$vendorId		 = Yii::app()->request->getParam('id');
		$model			 = new Vehicles();
		$modelDriver	 = new Drivers();
		if (isset($_REQUEST['Vehicles']))
		{
			$model->attributes = $_REQUEST['Vehicles'];
		}
		if (isset($_REQUEST['Drivers']))
		{
			$modelDriver->attributes = $_REQUEST['Drivers'];
		}
		if ($hashId != '' && $vendorId != '' && Yii::app()->shortHash->hash($vendorId) == $hashId)
		{
			$model->vendorVehicles->vvhc_vnd_id	 = $vendorId;
			$model->vhc_vendor_id1				 = $vendorId;
			$model->vhc_active					 = 1;
			$dataProvider						 = $model->resetScope()->listToVerify();
			$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
			$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
			$driverDataProvider					 = $modelDriver->resetScope()->listToVerify($vendorId, 1);
			$driverDataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
			$driverDataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		}
		$this->render('list', array('dataProvider' => $dataProvider, 'model' => $model, 'modelDriver' => $modelDriver, 'driverDataProvider' => $driverDataProvider));
	}

	public function actionEdit()
	{
		$this->layout	 = 'admin1';
		$this->pageTitle = "Edit Vehicle";
		$vehicleId		 = Yii::app()->request->getParam('vhcid');
		$vendorId		 = Yii::app()->request->getParam('id');
		$hashId			 = Yii::app()->request->getParam('code');
		if ($hashId != '' && $vendorId != '' && Yii::app()->shortHash->hash($vendorId) == $hashId)
		{

			if (isset($_POST['VehiclesInfo']))
			{
				if ($vehicleId != "")
				{
					$model				 = VehiclesInfo::model()->find('vhc_vehicle_id=:id', ['id' => $vehicleId]);
					$vModel				 = Vehicles::model()->findByPk($vehicleId);
					$vModel->scenario	 = 'approveinsert';
					if ($model == '')
					{
						$model = new VehiclesInfo('addnew');
					}
					else
					{
						$model->scenario = "isEdited";
					}
				}
				else
				{
					if ($_POST['VehiclesInfo']['vhc_vehicle_id'] != '')
					{
						$model				 = VehiclesInfo::model()->find('vhc_vehicle_id=:id', ['id' => $_POST['VehiclesInfo']['vhc_vehicle_id']]);
						$vModel				 = Vehicles::model()->findByPk($_POST['VehiclesInfo']['vhc_vehicle_id']);
						$vModel->scenario	 = "approveinsert";
					}
					else
					{
						$model	 = new VehiclesInfo('addnew');
						$vModel	 = new Vehicles('approveinsert');
					}
				}
				$model->oldAttributes		 = $model->attributes;
				$model->attributes			 = array_filter($_POST['VehiclesInfo']);
				$model->vhc_owned_or_rented	 = $_POST['VehiclesInfo']['vhc_owned_or_rented'];
				if ($_POST ['VehiclesInfo']['vhc_is_attached'][0] != 1)
				{
					$model->vhc_is_attached = 0;
				}
				else
				{
					$model->vhc_is_attached = 1;
				}

				if ($_POST['VehiclesInfo'] ['vhc_is_commercial'][0] != 1)
				{
					$model->vhc_is_commercial = 0;
				}
				else
				{
					$model->vhc_is_commercial = 1;
				}
				$model->vhc_vendor_id	 = $vendorId;
				$model->vhc_approved	 = 2;
				$uploadedFile1			 = CUploadedFile::getInstance($model, "vhc_insurance_proof");
				$uploadedFile2			 = CUploadedFile::getInstance($model, "vhc_front_plate");
				$uploadedFile3			 = CUploadedFile::getInstance($model, "vhc_rear_plate");
				$uploadedFile4			 = CUploadedFile::getInstance($model, "vhc_pollution_certificate");
				$uploadedFile5			 = CUploadedFile::getInstance($model, "vhc_reg_certificate");
				$uploadedFile6			 = CUploadedFile::getInstance($model, "vhc_permits_certificate");
				$uploadedFile7			 = CUploadedFile::getInstance($model, "vhc_fitness_certificate");

				$attributesVehicleInfo	 = $model->attributes;
				$attributesInfo			 = $model->attributes;
				unset($attributesVehicleInfo['vhc_id']);
				$vModel->attributes		 = array_filter($attributesVehicleInfo);
				$vModel->vhc_vendor_id1	 = $attributesVehicleInfo['vhc_vendor_id'];
				if ($model->validate() && $vModel->validate())
				{
					$model->attributes = array_filter($attributesInfo);
					if ($vehicleId == '')
					{
						$success = $vModel->save();
						if ($success)
						{
							$model->vhc_vehicle_id = $vModel->vhc_id;
						}
					}
					else
					{
						$vModel->vhc_approved = 2;
						$vModel->save();
					}

					$folderName = "vehicles";
					if ($uploadedFile1 != '')
					{
						$type						 = "INSURANCE";
						$path						 = $this->uploadAttachments($uploadedFile1, $type, $model->vhc_vehicle_id, $folderName);
						$model->vhc_insurance_proof	 = $path;
					}
					if ($uploadedFile2 != '')
					{
						$type					 = "FrontLicensePlate";
						$path					 = $this->uploadAttachments($uploadedFile2, $type, $model->vhc_vehicle_id, $folderName);
						$model->vhc_front_plate	 = $path;
					} if ($uploadedFile3 != '')
					{
						$type					 = "RearLicensePlate";
						$path					 = $this->uploadAttachments($uploadedFile3, $type, $model->vhc_vehicle_id, $folderName);
						$model->vhc_rear_plate	 = $path;
					} if ($uploadedFile4 != '')
					{
						$type								 = "PUC";
						$path								 = $this->uploadAttachments($uploadedFile4, $type, $model->vhc_vehicle_id, $folderName);
						$model->vhc_pollution_certificate	 = $path;
					}
					if ($uploadedFile5 != '')
					{
						$type						 = "RC";
						$path						 = $this->uploadAttachments($uploadedFile5, $type, $model->vhc_vehicle_id, $folderName);
						$model->vhc_reg_certificate	 = $path;
					}
					if ($uploadedFile6 != '')
					{
						$type							 = "Permit";
						$path							 = $this->uploadAttachments($uploadedFile6, $type, $model->vhc_vehicle_id, $folderName);
						$model->vhc_permits_certificate	 = $path;
					}
					if ($uploadedFile7 != '')
					{
						$type							 = "Fitness";
						$path							 = $this->uploadAttachments($uploadedFile7, $type, $model->vhc_vehicle_id, $folderName);
						$model->vhc_fitness_certificate	 = $path;
					}
					$success = $model->save();
					if ($success)
					{
						if ($vehicleId == '')
						{
							$vModel->attributes					 = array_filter($attributesVehicleInfo);
							$vModel->vhc_insurance_proof		 = $model->vhc_insurance_proof;
							$vModel->vhc_front_plate			 = $model->vhc_front_plate;
							$vModel->vhc_rear_plate				 = $model->vhc_rear_plate;
							$vModel->vhc_pollution_certificate	 = $model->vhc_pollution_certificate;
							$vModel->vhc_reg_certificate		 = $model->vhc_reg_certificate;
							$vModel->vhc_permits_certificate	 = $model->vhc_permits_certificate;
							$vModel->vhc_fitness_certificate	 = $model->vhc_fitness_certificate;
							$vModel->scenario					 = "approveinsert";
							$success							 = $vModel->save();
						}
						$vehicleId = $vModel->vhc_id;
					}
				}

				if ($success)
				{
					Yii::app()->user->setFlash('success', 'Vehicle details add/update successfully');
				}
				else
				{
					Yii::app()->user->setFlash('error', 'Vehicle details add/update not success<br/>');
					if ($model->hasErrors())
					{
						foreach ($model->getErrors() as $attribute => $errors)
						{
							foreach ($errors as $value)
							{
								Yii::app()->user->setFlash('error', 'Vehicle details update not success<br/>' . $value . "<br/>");
							}
						}
					}
					else
					if ($vModel->hasErrors())
					{
						foreach ($vModel->getErrors() as $attribute => $errors)
						{
							foreach ($errors as $value)
							{
								Yii::app()->user->setFlash('error', 'Vehicle details update not success<br/>' . $value . "<br/>");
							}
						}
					}
				}
			}

			if ($vehicleId != '')
			{
				$model					 = VehiclesInfo::model()->find('vhc_vehicle_id=:id', ['id' => $vehicleId]);
				$modelVehicle			 = Vehicles::model()->findByPk($vehicleId);
				$modelVehicle->scenario	 = 'approveinsert';
				if ($model == '' || $model == null)
				{
					$model = new VehiclesInfo();
					if ($modelVehicle != '')
					{
						$model->attributes = array_filter($modelVehicle->attributes);
						unset($model->vhc_id);
					}
				}
				$model->vhc_vehicle_id	 = $vehicleId;
				$model->vhc_vendor_id	 = $vendorId;
				$modelVendor			 = Vendors::model()->findByPk($vendorId);
				if ($modelVendor != '')
				{
					$vendorName = strtoupper($modelVendor->vnd_name);
				}
			}
			else
			{
				$model			 = new VehiclesInfo('addnew');
				$modelVehicle	 = new Vehicles('approveinsert');
				$this->pageTitle = "Add Vehicle";
			}

			$this->render('add', array('model' => $model, 'modelVehicle' => $modelVehicle, 'vendorName' => $vendorName));
		}
	}

	public function uploadAttachments($uploadedFile, $type, $vehicleId, $folderName)
	{
		$fileName	 = $vehicleId . "-" . $type . "-" . date('YmdHis') . "." . pathinfo($uploadedFile, PATHINFO_EXTENSION);
		$dir		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
		if (!is_dir($dir))
		{
			mkdir($dir);
		}


		$dirFolderName = $dir . DIRECTORY_SEPARATOR . $folderName;
		if (!is_dir($dirFolderName))
		{
			mkdir($dirFolderName);
		}
		$dirByVehicleId = $dirFolderName . DIRECTORY_SEPARATOR . $vehicleId;
		if (!is_dir($dirByVehicleId))
		{
			mkdir($dirByVehicleId);
		}
		$foldertoupload	 = $dirByVehicleId . DIRECTORY_SEPARATOR . $fileName;
		$extention		 = pathinfo($uploadedFile, PATHINFO_EXTENSION);
		if (strtolower($extention) == 'png' || strtolower($extention) == 'jpg' || strtolower($extention) == 'jpeg' || strtolower($extention) == 'gif')
		{
			Vehicles::model()->img_resize($uploadedFile->tempName, 1200, $dirByVehicleId . DIRECTORY_SEPARATOR, $fileName);
		}
		else
		{
			$uploadedFile->saveAs($foldertoupload);
		}

		$path = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . $vehicleId . DIRECTORY_SEPARATOR . $fileName;
		return $path;
	}

	public function actionEditdriver()
	{
		exit;
		$this->layout	 = 'admin1';
		$this->pageTitle = "Edit And Verify Driver";
		$driverId		 = Yii::app()->request->getParam('drvid');
		$vendorId		 = Yii::app()->request->getParam('id');
		$hashId			 = Yii::app()->request->getParam('code');

		if ($hashId != '' && $vendorId != '' && $driverId != '' && Yii::app()->shortHash->hash($vendorId) == $hashId)
		{
			$model = DriversInfo::model()->find('drv_driver_id=:id', ['id' => $driverId]);
			if ($model == '' || $model == null)
			{
				$model		 = new DriversInfo();
				$modelDriver = Drivers::model()->findByPk($driverId);
				if ($modelDriver != '')
				{
					$model->attributes = $modelDriver->attributes;
					unset($model->drv_id);
				}
			}
			$model->scenario = 'editDriver';
			if (isset($_POST['DriversInfo']))
			{
				$arrDrv					 = $_POST ['DriversInfo'];
				$model->oldAttributes	 = array_filter($model->attributes);
				$model->attributes		 = array_filter($arrDrv);
				// $model->drv_vendor_id = $arrDrv['drv_vendor_id1'];
				$model->drv_country_code = $_POST['DriversInfo']['drv_country_code'];
				$model->drv_driver_id	 = $driverId;
				$model->drv_vendor_id	 = $vendorId;
				$uploadedFile1			 = CUploadedFile::getInstance($model, "drv_photo_path");
				$uploadedFile2			 = CUploadedFile::getInstance($model, "drv_aadhaar_img_path");
				$uploadedFile3			 = CUploadedFile::getInstance($model, "drv_pan_img_path");
				$uploadedFile4			 = CUploadedFile::getInstance($model, "drv_voter_id_img_path");
				$uploadedFile5			 = CUploadedFile::getInstance($model, "drv_licence_path");
				$uploadedFile6			 = CUploadedFile::getInstance($model, "drv_adrs_proof1");
				$uploadedFile7			 = CUploadedFile::getInstance($model, "drv_adrs_proof2");
				$uploadedFile8			 = CUploadedFile::getInstance($model, "drv_police_certificate");

				if ($model->validate())
				{

					$model->drv_bg_checked	 = ($_POST['DriversInfo']['drv_bg_checked'][0] != 1) ? 0 : 1;
					$model->drv_is_attached	 = ($_POST['DriversInfo']['drv_is_attached'][0] != 1) ? 0 : 1;
					$model->drv_country_code = $_POST['DriversInfo']['drv_country_code'];
					$model->drv_dob_date	 = $_POST['DriversInfo']['drv_dob_date'];
					$model->drv_active		 = 1;
					$model->drv_driver_id	 = $driverId;
					$model->drv_vendor_id	 = $vendorId;
					$model->drv_approved	 = 2;

					$success = $model->save();
					if ($success)
					{
						$model		 = DriversInfo::model()->findByPk($model->drv_id);
						$folderName	 = "drivers";
						if ($uploadedFile1 != '')
						{
							$type					 = "profile";
							$path					 = $this->uploadAttachments($uploadedFile1, $type, $model->drv_driver_id, $folderName);
							$model->drv_photo_path	 = $path;
						}

						if ($uploadedFile2 != '')
						{
							$type						 = "adhar";
							$path						 = $this->uploadAttachments($uploadedFile2, $type, $model->drv_driver_id, $folderName);
							$model->drv_aadhaar_img_path = $path;
						}

						if ($uploadedFile3 != '')
						{
							$type					 = "pan";
							$path					 = $this->uploadAttachments($uploadedFile3, $type, $model->drv_driver_id, $folderName);
							$model->drv_pan_img_path = $path;
						}

						if ($uploadedFile4 != '')
						{
							$type							 = "voterid";
							$path							 = $this->uploadAttachments($uploadedFile4, $type, $model->drv_driver_id, $folderName);
							$model->drv_voter_id_img_path	 = $path;
						}

						if ($uploadedFile5 != '')
						{
							$type					 = "license";
							$path					 = $this->uploadAttachments($uploadedFile5, $type, $model->drv_driver_id, $folderName);
							$model->drv_licence_path = $path;
						}

						if ($uploadedFile6 != '')
						{
							$type					 = "address1";
							$path					 = $this->uploadAttachments($uploadedFile6, $type, $model->drv_driver_id, $folderName);
							$model->drv_adrs_proof1	 = $path;
						}

						if ($uploadedFile7 != '')
						{
							$type					 = "address2";
							$path					 = $this->uploadAttachments($uploadedFile7, $type, $model->drv_driver_id, $folderName);
							$model->drv_adrs_proof2	 = $path;
						}

						if ($uploadedFile8 != '')
						{
							$type							 = "police";
							$path							 = $this->uploadAttachments($uploadedFile8, $type, $model->drv_driver_id, $folderName);
							$model->drv_police_certificate	 = $path;
						}
						$success = $model->save();

						if ($success)
						{
							$modelDriver					 = Drivers::model()->findByPk($driverId);
							$modelDriver->drv_vendor_id1	 = $vendorId;
							$modelDriver->drv_approved		 = 2;
							$modelDriver->drv_country_code	 = $model->drv_country_code;
							$modelDriver->scenario			 = 'addverify';
							$success						 = $modelDriver->save();
						}
					}
				}
				if ($success)
				{
					Yii::app()->user->setFlash('success', 'Driver details updated successfully');
				}
				else
				{
					if ($model->hasErrors())
					{
						foreach ($model->getErrors() as $attribute => $errors)
						{
							foreach ($errors as $value)
							{
								Yii::app()->user->setFlash('error', 'Driver details update not success<br/>' . $value . "<br/>");
							}
						}
					}
					else
					{
						if ($modelDriver->hasErrors())
						{
							foreach ($modelDriver->getErrors() as $attribute => $errors)
							{
								foreach ($errors as $value)
								{
									Yii::app()->user->setFlash('error', 'Driver details update not success<br/>' . $value . "<br/>");
								}
							}
						}
					}
				}
			}

			$model->drv_driver_id = $driverId;
			$this->render('driveredit', array('model' => $model, 'driverId' => $driverId, 'vendorId' => $vendorId));
		}
	}

	public function actionCityfromstate1()
	{
		$stateId	 = Yii::app()->request->getParam('id');
		$cityList	 = CHtml::listData(Cities::model()->findAll(array("condition" => "cty_state_id ='$stateId'", "order" => "cty_name")), 'cty_id', 'cty_name');
		$data		 = VehicleTypes ::model()->getJSON($cityList);
		echo $data;
	}

	public function actionAdd()
	{
		$this->layout	 = 'admin1';
		$this->pageTitle = "Add Vehicle";
		$vendorId		 = Yii::app()->request->getParam('id');
		$hashId			 = Yii::app()->request->getParam('code');
		if ($hashId != '' && $vendorId != '' && Yii::app()->shortHash->hash($vendorId) == $hashId)
		{
			$model		 = new VehiclesInfo('addnew');
			$modelVendor = Vendors::model()->findByPk($vendorId);
			if ($modelVendor != '')
			{
				$vendorName = strtoupper($modelVendor->vnd_name);
			}
			if (isset($_POST ['VehiclesInfo']))
			{
				$vehicleModel = new Vehicles();
				if ($_POST['VehiclesInfo'] ['vhc_vehicle_id'] != '')
				{
					$model			 = VehiclesInfo::model()->find('vhc_vehicle_id=:id', ['id' => $_POST['VehiclesInfo']['vhc_vehicle_id']]);
					$model->scenario = 'addnew';
					$vehicleModel	 = Vehicles::model()->findByPk($model->vhc_vehicle_id);
				}
				$vehicleModel->scenario = 'approveinsert';

				$model->attributes			 = array_filter($_POST['VehiclesInfo']);
				unset($model->vhc_id);
				$model->vhc_owned_or_rented	 = $_POST['VehiclesInfo']['vhc_owned_or_rented'];
				if ($_POST['VehiclesInfo']['vhc_is_attached'][0] != 1)
				{
					$model->vhc_is_attached = 0;
				}
				else
				{
					$model->vhc_is_attached = 1;
				}
				$model->vhc_active		 = 1;
				$model->vhc_vendor_id	 = $vendorId;

				$vehicleModel->attributes					 = array_filter($model->attributes);
				unset($vehicleModel->vhc_insurance_proof);
				unset($vehicleModel->vhc_front_plate);
				unset($vehicleModel->vhc_rear_plate);
				unset($vehicleModel->vhc_pollution_certificate);
				unset($vehicleModel->vhc_pollution_exp_date);
				unset($vehicleModel->vhc_reg_certificate);
				unset($vehicleModel->vhc_reg_exp_date);
				unset($vehicleModel->vhc_permits_certificate);
				unset($vehicleModel->vhc_commercial_exp_date);
				unset($vehicleModel->vhc_fitness_certificate);
				unset($vehicleModel->vhc_fitness_cert_end_date);
				$vehicleModel->vhc_insurance_exp_date_date	 = $vehicleModel->vhc_insurance_exp_date;
				$vehicleModel->vhc_tax_exp_date_date		 = $vehicleModel->vhc_tax_exp_date;
				$vehicleModel->vhc_dop_date					 = $vehicleModel->vhc_dop;
				if ($vehicleModel->validate())
				{
					if ($vehicleModel->save())
					{
						$model->vhc_vehicle_id = $vehicleModel->vhc_id;
					}
					$uploadedFile1	 = CUploadedFile::getInstance($model, "vhc_insurance_proof");
					$uploadedFile2	 = CUploadedFile::getInstance($model, "vhc_front_plate");
					$uploadedFile3	 = CUploadedFile::getInstance($model, "vhc_rear_plate");
					$uploadedFile4	 = CUploadedFile::getInstance($model, "vhc_pollution_certificate");
					$uploadedFile5	 = CUploadedFile::getInstance($model, "vhc_reg_certificate");
					$uploadedFile6	 = CUploadedFile::getInstance($model, "vhc_permits_certificate");
					$uploadedFile7	 = CUploadedFile::getInstance($model, "vhc_fitness_certificate");
					$folderName		 = "vehicles";
					if ($uploadedFile1 != '')
					{
						$type						 = "INSURANCE";
						$path						 = $this->uploadAttachments($uploadedFile1, $type, $model->vhc_vehicle_id, $folderName);
						$model->vhc_insurance_proof	 = $path;
					}
					if ($uploadedFile2 != '')
					{
						$type					 = "FrontLicensePlate";
						$path					 = $this->uploadAttachments($uploadedFile2, $type, $model->vhc_vehicle_id, $folderName);
						$model->vhc_front_plate	 = $path;
					} if ($uploadedFile3 != '')
					{
						$type					 = "RearLicensePlate";
						$path					 = $this->uploadAttachments($uploadedFile3, $type, $model->vhc_vehicle_id, $folderName);
						$model->vhc_rear_plate	 = $path;
					} if ($uploadedFile4 != '')
					{
						$type								 = "PUC";
						$path								 = $this->uploadAttachments($uploadedFile4, $type, $model->vhc_vehicle_id, $folderName);
						$model->vhc_pollution_certificate	 = $path;
					}
					if ($uploadedFile5 != '')
					{
						$type						 = "RC";
						$path						 = $this->uploadAttachments($uploadedFile5, $type, $model->vhc_vehicle_id, $folderName);
						$model->vhc_reg_certificate	 = $path;
					}
					if ($uploadedFile6 != '')
					{
						$type							 = "Permit";
						$path							 = $this->uploadAttachments($uploadedFile6, $type, $model->vhc_vehicle_id, $folderName);
						$model->vhc_permits_certificate	 = $path;
					}
					if ($uploadedFile7 != '')
					{
						$type							 = "Fitness";
						$path							 = $this->uploadAttachments($uploadedFile7, $type, $model->vhc_vehicle_id, $folderName);
						$model->vhc_fitness_certificate	 = $path;
					}
					if ($model->validate())
					{
						$success = $model->save();
						$vhc_id	 = $model->vhc_id;
					}
				}
				if ($success)
				{
					Yii ::app()->user->setFlash('success', 'Vehicle details updated successfully');
				}
				else
				{
					Yii::app()->user->setFlash('error', 'Vehicle details update not success');
					foreach ($vehicleModel->getErrors() as $attribute => $errors)
					{
						foreach ($errors as $value)
						{
							Yii::app()->user->setFlash('error', 'Vehicle details update not success<br/>' . $value . "<br/>");
						}
					}
				}
				if ($vhc_id != '')
				{
					$model = VehiclesInfo::model()->findByPk($vhc_id);
				}
				$vehicleId = $model->vhc_vehicle_id;
			}

			$this->render('add', array('model' => $model, 'modelVehicle' => $model, 'vendorId' => $vendorId, 'vendorName' => $vendorName, 'vhc_id' => $vhc_id, 'vehicleId' => $vehicleId));
		}
	}

	public function actionAdddriver()
	{
		exit;
		$this->layout	 = 'admin1';
		$this->pageTitle = "Add Driver";
		$vendorId		 = Yii::app()->request->getParam('id');
		$hashId			 = Yii::app()->request->getParam('code');
		if ($hashId != '' && $vendorId != '' && Yii::app()->shortHash->hash($vendorId) == $hashId)
		{

			$model					 = new DriversInfo;
			$model->scenario		 = 'verifyDriver';
			$driverModel			 = new Drivers;
			$driverModel->scenario	 = 'addverify';

			if (isset($_POST['DriversInfo']))
			{

				//driversinfo model

				$model->attributes		 = array_filter($_POST ['DriversInfo']);
				$model->drv_bg_checked	 = ($_POST['DriversInfo']['drv_bg_checked'][0] != 1) ? 0 : 1;
				$model->drv_is_attached	 = ($_POST['DriversInfo']['drv_is_attached'][0] != 1) ? 0 : 1;
				$model->drv_country_code = $_POST['DriversInfo']['drv_country_code'];
				$model->drv_dob_date	 = $_POST['DriversInfo']['drv_dob_date'];
				$model->drv_active		 = 1;
				$model->drv_vendor_id	 = $vendorId;
				$model->drv_approved	 = 2;
				$uploadedFile1			 = CUploadedFile::getInstance($model, "drv_photo_path");
				$uploadedFile2			 = CUploadedFile::getInstance($model, "drv_aadhaar_img_path");
				$uploadedFile3			 = CUploadedFile::getInstance($model, "drv_pan_img_path");
				$uploadedFile4			 = CUploadedFile::getInstance($model, "drv_voter_id_img_path");
				$uploadedFile5			 = CUploadedFile::getInstance($model, "drv_licence_path");
				$uploadedFile6			 = CUploadedFile::getInstance($model, "drv_adrs_proof1");
				$uploadedFile7			 = CUploadedFile::getInstance($model, "drv_adrs_proof2");
				$uploadedFile8			 = CUploadedFile::getInstance($model, "drv_police_certificate");

				//driver model

				$data	 = $model->attributes;
				$data1	 = $driverModel->attributes;
				foreach ($data as $attr => $val)
				{
					if ($val == null || $val == '' || $attr == 'drv_id')
					{
						unset($data[$attr]);
						unset($data1[$attr]);
					}
					else
					{
						$driverModel->setAttribute($attr, $val);
					}
				}
				$driverModel->drv_approved	 = 2;
				$driverModel->drv_vendor_id1 = $vendorId;

				$result			 = CActiveForm::validate($model);
				$resultDriver	 = CActiveForm::validate($driverModel);
				if ($result == '[]' && $resultDriver == '[]')
				{
					$success = $driverModel->save();
					if ($success)
					{
						$driverModel			 = Drivers::model()->findByPk($driverModel->drv_id);
						$model->drv_driver_id	 = $driverModel->drv_id;
						$folderName				 = "drivers";
						if ($uploadedFile1 != '')
						{
							$type						 = "profile";
							$path						 = $this->uploadAttachments($uploadedFile1, $type, $model->drv_driver_id, $folderName);
							$driverModel->drv_photo_path = $path;
							$model->drv_photo_path		 = $path;
						}
						if ($uploadedFile2 != '')
						{
							$type								 = "adhar";
							$path								 = $this->uploadAttachments($uploadedFile2, $type, $model->drv_driver_id, $folderName);
							$driverModel->drv_aadhaar_img_path	 = $path;
							$model->drv_aadhaar_img_path		 = $path;
						}
						if ($uploadedFile3 != '')
						{
							$type							 = "pan";
							$path							 = $this->uploadAttachments($uploadedFile3, $type, $model->drv_driver_id, $folderName);
							$driverModel->drv_pan_img_path	 = $path;
							$model->drv_pan_img_path		 = $path;
						}
						if ($uploadedFile4 != '')
						{
							$type								 = "voterid";
							$path								 = $this->uploadAttachments($uploadedFile4, $type, $model->drv_driver_id, $folderName);
							$driverModel->drv_voter_id_img_path	 = $path;
							$model->drv_voter_id_img_path		 = $path;
						}
						if ($uploadedFile5 != '')
						{
							$type							 = "license";
							$path							 = $this->uploadAttachments($uploadedFile5, $type, $model->drv_driver_id, $folderName);
							$driverModel->drv_licence_path	 = $path;
							$model->drv_licence_path		 = $path;
						}
						if ($uploadedFile6 != '')
						{
							$type							 = "address1";
							$path							 = $this->uploadAttachments($uploadedFile6, $type, $model->drv_driver_id, $folderName);
							$driverModel->drv_adrs_proof1	 = $path;
							$model->drv_adrs_proof1			 = $path;
						}
						if ($uploadedFile7 != '')
						{
							$type							 = "address2";
							$path							 = $this->uploadAttachments($uploadedFile7, $type, $model->drv_driver_id, $folderName);
							$driverModel->drv_adrs_proof2	 = $path;
							$model->drv_adrs_proof2			 = $path;
						}
						if ($uploadedFile8 != '')
						{
							$type								 = "police";
							$path								 = $this->uploadAttachments($uploadedFile8, $type, $model->drv_driver_id, $folderName);
							$driverModel->drv_police_certificate = $path;
							$model->drv_police_certificate		 = $path;
						}

						$success = $model->save();
						if ($success)
						{
							$driverModel->scenario	 = 'addverify';
							$success				 = $driverModel->save();
							if ($success)
							{
								$model = DriversInfo::model()->findByPk($model->drv_id);
							}
						}
					}
				}
				else
				{
					$success = false;
					$model->addErrors($result);
				}


				if ($success)
				{
					Yii::app()->user->setFlash('success', 'Driver added successfully');
				}
				else
				{
					if ($model->hasErrors())
					{
						foreach ($model->getErrors() as $attribute => $errors)
						{
							foreach ($errors as $value)
							{
								Yii::app()->user->setFlash('error', 'Driver add not success<br/>' . $value . "<br/>");
							}
						}
					}
					else
					{
						if ($driverModel->hasErrors())
						{
							foreach ($driverModel->getErrors() as $attribute => $errors)
							{
								foreach ($errors as $value)
								{
									Yii::app()->user->setFlash('error', 'Driver add not success<br/>' . $value . "<br/>");
								}
							}
						}
					}
				}
			}

			$this->render('driveredit', array('model' => $model, 'vendorId' => $vendorId));
		}
	}

	public function saveVehicleImage($image, $imagetmp, $vehicleId, $type)
	{
		try
		{
			$path = "";

			if ($image != '')
			{
				$image = $vehicleId . "-" . $type . "-" . date('YmdHis') . "." . $image;

				// Attachments
				$dir = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
				if (!is_dir($dir))
				{
					mkdir($dir);
				}

				// Server Id
				$serverId			 = Config::getServerID();
				$serverFolderName	 = $dir . DIRECTORY_SEPARATOR . $serverId;
				if (!is_dir($serverFolderName))
				{
					mkdir($serverFolderName);
				}

				// Vehicle
				$dirFolderName = $serverFolderName . DIRECTORY_SEPARATOR . 'vehicles';
				if (!is_dir($dirFolderName))
				{
					mkdir($dirFolderName);
				}

				// Vehicle Id
				$dirByVehicleId = $dirFolderName . DIRECTORY_SEPARATOR . $vehicleId;
				if (!is_dir($dirByVehicleId))
				{
					mkdir($dirByVehicleId);
				}

				// Folder Path	
				$file_path = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $serverId . DIRECTORY_SEPARATOR . 'vehicles' . DIRECTORY_SEPARATOR . $vehicleId;

				// File Path
				$file_name	 = basename($image);
				$f			 = $file_path;
				$file_path	 = $file_path . DIRECTORY_SEPARATOR . $file_name;

				#file_put_contents(PUBLIC_PATH . '/testFile.txt', $f . ' ==== ' . $file_name);
				#Yii::log("Image Path: \n\t Temp: " . $imagetmp . "\n\t Path: " . $f, CLogger::LEVEL_INFO, 'system.api.images');
				// Image Resize
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

	public function saveImage($image, $imagetmp, $vndID)
	{
		try
		{
			$path = "";
			if ($image != '')
			{


				$dir = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
				if (!is_dir($dir))
				{
					mkdir($dir);
				}
				$dirFolderName = $dir . DIRECTORY_SEPARATOR . 'vendor';

				if (!is_dir($dirFolderName))
				{
					mkdir($dirFolderName);
				}
				$dirByVehicleId = $dirFolderName . DIRECTORY_SEPARATOR . $vndID;
				if (!is_dir($dirByVehicleId))
				{
					mkdir($dirByVehicleId);
				}
				$dirByVehicleIdLOU = $dirByVehicleId . DIRECTORY_SEPARATOR . 'lou';
				if (!is_dir($dirByVehicleIdLOU))
				{
					mkdir($dirByVehicleIdLOU);
				}
				//$file_path = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'vehicles' . DIRECTORY_SEPARATOR . $vehicleId;
				$file_path	 = $dirByVehicleIdLOU;
				$file_name	 = basename($image);
				$f			 = $file_path;
				$file_path	 = $file_path . DIRECTORY_SEPARATOR . $file_name;

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

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function getCabList()
	{

		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{

			Vendors::model()->authoriseVendor($token);
			$vndId = UserInfo::getEntityId();
			if (!$vndId)
			{
				throw new Exception("Invalid Vendor.", ReturnSet::ERROR_UNKNOWN);
			}
			Logger::create("Cab List Vendor Id : " . $vndId, CLogger::LEVEL_INFO);
			$cabData = Vehicles::getcabDetails($vndId);
			Logger::trace("Cab List Vendor data : " . json_encode($cabData));
			if (empty($cabData))
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			/* @var $vehicle Stub\common\Vehicle */
			$vehicle = new Stub\common\Vehicle();
			$vehicle->getList($cabData);
			#Logger::create("cab data : =>>>>>>>" . CJSON::encode($vehicle), CLogger::LEVEL_INFO);
			$returnSet->setStatus(true);
			$returnSet->setData($vehicle);
			#Logger::create("Cab List Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function getList()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$data		 = Yii::app()->request->getParam('search_txt', '');
		$transaction = DBUtil::beginTransaction();
		try
		{
			Vendors::model()->authoriseVendor($token);
			$vndId = UserInfo::getEntityId();
			if (!$vndId)
			{
				throw new Exception("Invalid Vendor.", ReturnSet::ERROR_UNAUTHORISED);
			}
			Logger::create("Get List Vendor Id : " . $vndId, CLogger::LEVEL_INFO);
			$cabData	 = Vehicles::getListByVendor($vndId, trim($data), $is_freeze	 = 0, $approved	 = 1, $vehiclelist = 1);
			$vehicle	 = new Stub\common\Vehicle();
			$vehicle->getList($cabData);

			if (empty($vehicle->dataList))
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($vehicle);
			$transaction = DBUtil::commitTransaction($transaction);
			Logger::create("List Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet	 = ReturnSet::setException($e);
			$transaction = DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	public function tripGetList()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		Logger::trace("<===Request===> : " . $data);
		try
		{
			if (empty($data))
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj			 = CJSON::decode($data, false);
			$tripId				 = $jsonObj->tripId;
			$search_txt			 = $jsonObj->search_txt;
			$bookingCabModel	 = BookingCab::model()->findByPk($tripId);
			$bookingModels		 = $bookingCabModel->bookings;
			//$bookingClass = $bookingModels[0]->bkgSvcClassVhcCat->scc_ServiceClass->scc_id;
			$bkgVehicleTypeId	 = $bookingModels[0]->bkg_vehicle_type_id;
			//$bkgVehicleTypeId = $bookingModels[0]->bkg_vehicle_type_id;
			#$booking_class = $bookingModels[0]->bkgSvcClassVhcCat->scc_ServiceClass->scc_id;
			$vndId				 = UserInfo::getEntityId();
			$cabData			 = Vehicles::getListForTripByVendor($vndId, $bkgVehicleTypeId, $search_txt);
			$vehicle			 = new Stub\common\Vehicle();
			$vehicle->getList($cabData, $tripId);

			if (empty($vehicle->dataList))
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($vehicle);
			$transaction = DBUtil::commitTransaction($transaction);
			//Logger::create("List Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
			Logger::trace("<===Response===> : " . CJSON::encode($returnSet));
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet	 = ReturnSet::setException($e);
			$transaction = DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function editInformation()
	{
		$returnSet = new ReturnSet();

		$vehicleId	 = Yii::app()->request->getParam('vhc_id');
		$transaction = DBUtil::beginTransaction();
		try
		{

			$userInfo			 = UserInfo::getInstance();
			$userInfo->userId	 = UserInfo::getUserId();
			$userInfo->userType	 = UserInfo::getUserType();
			$vndId				 = UserInfo::getEntityId();
			Logger::create("Edit Information Vehicle Id : " . $vehicleId, CLogger::LEVEL_INFO);
			$model				 = Vehicles::model()->findByPk($vehicleId);
			if (empty($model))
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
//echo $vehicleId;
			/* @var $response Stub\common\Vehicle */
			$response	 = new Stub\common\Vehicle();
			Logger::create("chk data: ", CLogger::LEVEL_INFO);
			$response->setModelData($model);
			//$response->owner->documents	 = (object) $response->owner->documents;
			Logger::create("response Information : " . CJSON::encode($response), CLogger::LEVEL_INFO);
			//$data		 = Filter::removeNull($response);
//			print_r($response);
//			exit;
			$returnSet->setStatus(true);
			$returnSet->setData($response);
			Logger::create("Edit Information Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
			$transaction = DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet	 = $returnSet->setException($e);
			Logger::exception($e);
			$transaction = DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function updateInformation()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$data		 = Yii::app()->request->rawBody;
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);
		$transaction = DBUtil::beginTransaction();
		try
		{

			$userInfo			 = UserInfo::getInstance();
			$userInfo->userId	 = UserInfo::getUserId();
			$userInfo->userType	 = UserInfo::getUserType();
			$vndID				 = UserInfo::getEntityId();
			Logger::create("Update Information Request : " . $data, CLogger::LEVEL_INFO);
			$vhcDocument		 = $jsonObj->documents;
			$docUpdate			 = new Stub\common\VehicleDoc();
			$docUpdate->getData($vhcDocument, $jsonObj->id, $userInfo);

			/* @var $obj Stub\common\Vehicle */
			$obj				 = $jsonMapper->map($jsonObj, new Stub\common\Vehicle());
			$model				 = $obj->getModelData();
//			if ($jsonObj->owner->id)
//			{
//				$model->vehicleContact->update();
//			}
			$vendorVehicleModel	 = VendorVehicle::model()->findByVndVhcId($vndID, $model->vhc_id);
			$vehicleModel		 = Vehicles::model()->findByPk($model->vhc_id);
			$vendorModel		 = Vendors::model()->findByPk($vndID);
			$vendorName			 = Contact::getVendorName($vendorModel->vnd_contact_id);
			$vehicleOwnerName	 = $model->vhc_reg_owner . ' ' . $model->vhc_reg_owner_lname;
			$isOwned			 = $model->vhc_owned_or_rented;
			//add flag accrding to vendor vehicle

			$vendorVehicleModel->vvhc_owner_or_not = $isOwned;
			$vendorVehicleModel->save();

			if (($isOwned == 2) || ($isOwned == 1 && ($vendorName['vndName'] != $vehicleOwnerName)))
			{
				
			}
			else
			{
				if ($vendorVehicleModel)
				{

					$vendorVehicleModel->vvhc_active = 1;
					$vendorVehicleModel->save();
				}
				$returnSet->setStatus(true);
			}
			$model->scenario = 'lou';
			$model->save();

			/* @var $response Stub\common\Vehicle */
			$response	 = new Stub\common\Vehicle();
			$response->setModelData($model);
			$data		 = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($data);
			Logger::create("Update Information Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
			$transaction = DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet	 = $returnSet->setException($e);
			Logger::exception($e);
			$transaction = DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function uploadFiles()
	{
		$returnSet = new ReturnSet();
		try
		{

			$uploadedFile	 = CUploadedFile::getInstanceByName("img1");
			Logger::trace("Cab data : " . json_encode($uploadedFile));
			$result			 = [];

			$userInfo			 = UserInfo::getInstance();
			$userInfo->userId	 = empty($userInfo->userId) ? 123 : $userInfo->userId;
			$userInfo->userType	 = UserInfo::getUserType(); //Vendor type = 3
			$userInfo->platform	 = 2; //Platform type =2
			$vndID				 = UserInfo::getEntityId();
			$jsonMapper			 = new JsonMapper();

			if ($uploadedFile != '')
			{

				$dataDetails[] = VehicleDocs::model()->uploadFiles($uploadedFile);
			}
			$response = [];
			foreach ($dataDetails as $res)
			{
				$result					 = $res['model'];
				$message				 = $res['message'];
				$res					 = new \Stub\common\VehicleDoc();
				$res->setVhcDocModelData($result, $message);
				$responsedt->dataList[]	 = $res;
			}
			$response	 = $responsedt;
			$data		 = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($data);
			Logger::create("Vehicle Booking File Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
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
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function undertakingInfo()
	{
		$returnSet	 = new ReturnSet();
		$vehicleId	 = Yii::app()->request->getParam('vhc_id');
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		Logger::trace("<===Request===>" . $vehicleId);
		$transaction = DBUtil::beginTransaction();
		try
		{
			AppTokens::validateToken($token);
			$userInfo			 = UserInfo::getInstance();
			$userInfo->userId	 = UserInfo::getUserId();
			$userInfo->userType	 = UserInfo::getUserType();
			$vndId				 = UserInfo::getEntityId();
			
			Logger::create("Under Taking Information Vehicle Id : " . $vehicleId, CLogger::LEVEL_INFO);
			/* @var $response Stub\common\VendorVehicle */
			$response			 = new Stub\common\VendorVehicle();
			$response->setModelData($vndId, $vehicleId);
			$data				 = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($data);
			Logger::create("Under Taking Information Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
			$transaction		 = DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet	 = ReturnSet::setException($e);
			Logger::exception($e);
			$transaction = DBUtil::rollbackTransaction($transaction);
		}
		Logger::trace("<===Response===>" . json_encode($returnSet));
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function unlinkVendorVehicle()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$transaction = DBUtil::beginTransaction();
		try
		{
			AppTokens::validateToken($token);
			$userInfo	 = UserInfo::getInstance();
			$userId		 = $userInfo->userId;
			$vndId		 = UserInfo::getEntityId();
			Logger::create("Unlink Vendor Vehicle token : " . $token, CLogger::LEVEL_INFO);
			if (!$userId)
			{
				throw new Exception("Invalid User. ", ReturnSet::ERROR_UNAUTHORISED);
			}
			Logger::create("Unlink Vendor Vehicle User Id : " . $token, CLogger::LEVEL_INFO);
			if (!$vndId)
			{
				throw new Exception("Invalid Vendor.", ReturnSet::ERROR_UNAUTHORISED);
			}
			$vehicleId = Yii::app()->request->getParam('vhc_id');
			if (!$vehicleId)
			{
				throw new Exception("No Vehicle Id.", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Vendor Vehicle Id : " . $vehicleId, CLogger::LEVEL_INFO);
			$result = VendorVehicle::model()->unlinkByVendorVehicleId($vndId, $vehicleId);

			if (!$result)
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("Cab already removed.");
			}
			else
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Cab removed successfully.");
			}
			Logger::create("Vendor Vehicle Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
			Logger::exception($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function unlinkVendorDriver()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$transaction = DBUtil::beginTransaction();
		try
		{
			AppTokens::validateToken($token);
			$userInfo	 = UserInfo::getInstance();
			$userId		 = $userInfo->userId;
			$vndId		 = UserInfo::getEntityId();
			Logger::create("Unlink Vendor Driver token : " . $token, CLogger::LEVEL_INFO);
			if (!$userId)
			{
				throw new Exception("Invalid User. ", ReturnSet::ERROR_UNAUTHORISED);
			}
			if (!$vndId)
			{
				throw new Exception("Invalid Vendor.", ReturnSet::ERROR_UNAUTHORISED);
			}
			$vdrvId = Yii::app()->request->getParam('vdrvId');
			if (!$vdrvId)
			{
				throw new Exception("No Vendor Driver Id.", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Vendor Driver Id : " . $vdrvId, CLogger::LEVEL_INFO);

			$result = VendorDriver::unlinkByVendorDriverId($vdrvId);
			if (!$result)
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("Driver already removed.");
			}
			else
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Driver removed successfully.");
			}
			Logger::create("Unlink Vendor Driver Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
			Logger::exception($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function vehicleStatusDetails()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$transaction = DBUtil::beginTransaction();
		try
		{
			AppTokens::validateToken($token);
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$userInfo	 = UserInfo::getInstance();
			$userId		 = $userInfo->userId;
			$vndId		 = UserInfo::getEntityId();
			Logger::create("Vendor Status Details Token : " . $token, CLogger::LEVEL_INFO);
			if (!$userId)
			{
				throw new Exception("Invalid User.", ReturnSet::ERROR_UNAUTHORISED);
			}
			if (!$vndId)
			{
				throw new Exception("Invalid Vendor.", ReturnSet::ERROR_UNAUTHORISED);
			}
			$vehicleId	 = $jsonObj->vehicleId;
			$tripId		 = $jsonObj->tripId;
			if (!$vehicleId)
			{
				throw new Exception("No Vehicle Id.", ReturnSet::ERROR_INVALID_DATA);
			}
			if (!$tripId)
			{
				throw new Exception("No Trip Id.", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Vendor Status Details Vehicle Id : " . $vehicleId, CLogger::LEVEL_INFO);
			$model		 = Vehicles::model()->findByPk($vehicleId);
			$dataRow	 = BookingCab::getBkgIdByTripId($tripId);
			$result		 = BookingCab::model()->isCngAllowed($dataRow['bkg_ids'], $model->vhc_id);
			/* @var $response Stub\common\Vehicle */
			$response	 = new Stub\common\Vehicle();
			$response->setStatusData($model, $result);
			$data		 = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($data);
			Logger::create("Vendor Status Details Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
			Logger::exception($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function updateUnderTaking()
	{
		$returnSet	 = new ReturnSet();
		//$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$data		 = Yii::app()->request->rawBody;
		Logger::trace("<===Request===>" . $data);
		$status		 = false;
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);
		$transaction = DBUtil::beginTransaction();
		try
		{
			//AppTokens::validateToken($token);
			//Logger::create("Update Under Taking Token: " . $token, CLogger::LEVEL_INFO);
			Logger::create("Update Under Taking Request: " . $data, CLogger::LEVEL_INFO);
			$userInfo			 = UserInfo::getInstance();
			$userInfo->userId	 = UserInfo::getUserId();
			$userInfo->userType	 = UserInfo::getUserType();
			$vndId				 = UserInfo::getEntityId();
			/* @var $obj \Stub\common\VendorVehicle */
			$obj				 = $jsonMapper->map($jsonObj, new Stub\common\VendorVehicle());
			/** @var VendorVehicle $model */
			$model				 = $obj->getModel();
			$model->scenario	 = 'updateUnderTaking';
			if ($model->validate())
			{
				if ($model->vvhcVhc->update())
				{
					if (!$model->save())
					{
						$errors = $model->getErrors();
						throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
					}
					DBUtil::commitTransaction($transaction);
					$response = new Stub\common\Vehicle();
					$response->setModel($model->vvhcVhc);

					$data	 = Filter::removeNull($response);
					$status	 = true;
					if ($data->owner->documents->Licence->documentType == 5)
					{
						$message = "Licence Successfully Updated.";
					}
					else
					{
						$message = "Pan Successfully Updated.";
					}
				}
				$returnSet->setData($data);
				$returnSet->setStatus($status);
				$returnSet->setMessage($message);
				Logger::create("Update Under Taking Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
			}
			else
			{
				$errors = $model->getErrors();
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

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function vendorAddCab()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$transaction = DBUtil::beginTransaction();
		try
		{
			AppTokens::validateToken($token);
			$userInfo			 = UserInfo::getInstance();
			$vndId				 = UserInfo::getEntityId();
			$userInfo->userId	 = UserInfo::getUserId();
			$userInfo->userType	 = UserInfo::getUserType();
			$data				 = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Data.", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Vendor Add Cab Request: " . $data, CLogger::LEVEL_INFO);
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			/** @var $obj Stub\common\Vehicle */
			$obj		 = $jsonMapper->map($jsonObj, new \Stub\common\Vehicle());
			/** @var Vehicle $model */
			$model		 = $obj->getNewModel();

			$result = $model->addVehicle_V2('', $vndId, $model);

			if (!$result[success])
			{
				if (!$result[errors])
				{
					$returnSet->setErrors("Vehicle add Failed.", $returnSet::ERROR_FAILED);
				}
				else
				{
					$returnSet->setErrors($result[errors], $returnSet::ERROR_VALIDATION);
				}
				$returnSet->setStatus(false);
			}
			else
			{
				/** @var $docData \Stub\common\Vehicle() */
				$docData	 = new \Stub\common\Vehicle();
				$response	 = $docData->init($model);
				$response	 = Filter::removeNull($response);
				$returnSet->setStatus(true);
				$returnSet->setMessage("Vehicle Added Successfully.");
				VendorStats::model()->updateCarTypeCount($vndId);
			}

			$returnSet->setData($response);
			Logger::create("Add Cab Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
			Logger::exception($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function uploadContactFile()
	{
		$returnSet = new ReturnSet();
		try
		{
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$ownerdt			 = CJSON::decode($process_sync_data, true);

			$image		 = $_FILES['vhc_owner_proof']['name'];
			$imagetmp	 = $_FILES['vhc_owner_proof']['tmp_name'];
			$result		 = [];

			//$conctID = $ownerdt['id'];
			//$docType = $ownerdt['type'];

			$conctID = Yii::app()->request->getParam('ownerId');
			$docType = Yii::app()->request->getParam('type');
			;

			if ($docType == 4)
			{
				$docID = Contact::model()->findByPk($conctID)->ctt_pan_doc_id;
			}
			else
			{
				$docID = Contact::model()->findByPk($conctID)->ctt_license_doc_id;
			}
			$userInfo			 = UserInfo::getInstance();
			$userInfo->userId	 = empty($userInfo->userId) ? 123 : $userInfo->userId;
			$userInfo->userType	 = UserInfo::getUserType(); //Driver type = 3
			$userInfo->platform	 = 2; //Platform type =5
			$vndID				 = UserInfo::getEntityId();
			$jsonMapper			 = new JsonMapper();
			//$cttId				 = Vendors::model()->findByPk($vndID)->vnd_contact_id;


			if ($image != '')
			{
				$fileChecksum	 = md5_file($_FILES['vhc_owner_proof']['tmp_name']);
				//$dataDetails[]	 = VehicleDocs::model()->uploadFiles($image, $imagetmp, $fileChecksum, $vndID);
				$dataDetails[]	 = Document::model()->uploadByChecksum($image, $imagetmp, $fileChecksum, $docID, $conctID, $docType);
			}
			$response = [];
			foreach ($dataDetails as $res)
			{
				$result					 = $res['model'];
				$message				 = $res['message'];
				$res					 = new \Stub\common\Documents();
				$res->setData($result, $message);
				$responsedt->dataList[]	 = $res;
//$responsedt	 = $res;
			}
			$response	 = $responsedt;
			$data		 = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($data);
			Logger::create("Upload Contact File Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($e);
			Logger::exception($e);
		}
		return $returnSet;
	}

	// getLouList

	public function getLouList()
	{

		$returnSet = new ReturnSet();

		$transaction = DBUtil::beginTransaction();
		try
		{
			$userInfo			 = UserInfo::getInstance();
			$userInfo->userId	 = UserInfo::getUserId();
			$userInfo->userType	 = UserInfo::getUserType();
			$vndId				 = UserInfo::getEntityId();

			$cabData = VendorVehicle::getVvhcForLou($vndId);
			if (empty($cabData))
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			/* @var $vehicle Stub\common\Vehicle */
			$vehicle = new Stub\common\Vehicle();
			$vehicle->getList($cabData);
			$returnSet->setStatus(true);
			$returnSet->setData($vehicle);
			Logger::info("Cab List Response : " . CJSON::encode($returnSet));
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function checkDuplicateVehicle()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$transaction = DBUtil::beginTransaction();
		try
		{
			AppTokens::validateToken($token);
			$userInfo			 = UserInfo::getInstance();
			$vndId				 = UserInfo::getEntityId();
			$userInfo->userId	 = UserInfo::getUserId();
			$userInfo->userType	 = UserInfo::getUserType();
			$data				 = Yii::app()->request->rawBody;
			Logger::trace("<===Request===>" . $data);
			if (!$data)
			{
				throw new Exception("Invalid Data.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			/** @var $obj Stub\vendor\checkDuplicateVehicleRequest */
			$obj		 = $jsonMapper->map($jsonObj, new \Stub\vendor\checkDuplicateVehicleRequest());
			/** @var Vehicle $model */
			$model		 = $obj->setModel();
			$data1		 = Vehicles::model()->checkDuplicateVehicleNo($model->vhc_number);
			$isExists	 = false;
			if ($data1[0]['vhc_id'] > 0)
			{
				$vhcModel = Vehicles::model()->findByPk($data1[0]['vhc_id']);
				if ($vhcModel)
				{
					/** @var $vhcData \Stub\common\Vehicle */
					$vhcData	 = new \Stub\common\Vehicle();
					$response	 = $vhcData->init($vhcModel);
					$returnSet->setStatus(true);
					$returnSet->setData($response);
				}
				else
				{
					$returnSet->setMessage("Vehicle Not found");
				}
			}
			else
			{
				$returnSet->setStatus(false);
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
			DBUtil::rollbackTransaction($transaction);
		}
		Logger::trace("<===Response===>" . json_encode($returnSet));
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function updateInformation_v1()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$transaction = DBUtil::beginTransaction();
		try
		{

			AppTokens::validateToken($token);
			$userInfo			 = UserInfo::getInstance();
			$vndId				 = UserInfo::getEntityId();
			$userInfo->userId	 = UserInfo::getUserId();
			$userInfo->userType	 = UserInfo::getUserType();
			$data				 = Yii::app()->request->rawBody;
			Logger::trace("<===Request===> : " . $data);
			if (!$data)
			{
				throw new Exception("Invalid Data.", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Vendor Add Cab Request: " . $data, CLogger::LEVEL_INFO);
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			/** @var $obj Stub\common\Vehicle */
			$obj		 = $jsonMapper->map($jsonObj, new \Stub\common\Vehicle());
			/** @var Vehicle $model */
			$model		 = $obj->getNewModel();
			//$result			 = $model->addVehicle_V2('', $vndId, $model);
			$result		 = Vehicles::model()->addVehicle_V2('', $vndId, $model);

			$model->scenario = 'lou';
			$model->save();
			if (!$result[success])
			{
				if (!$result[errors])
				{
					$returnSet->setErrors("Vehicle update Failed.", $returnSet::ERROR_FAILED);
				}
				else
				{
					$returnSet->setErrors($result[errors], $returnSet::ERROR_VALIDATION);
				}
				$returnSet->setStatus(false);
			}
			else
			{
				/* @var $response Stub\common\Vehicle */
				$response	 = new Stub\common\Vehicle();
				$response->init($model);
				$data		 = Filter::removeNull($response);
				$returnSet->setStatus(true);
				$returnSet->setData($data);
				//Logger::create("Update Information Response : " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);				
				$transaction = DBUtil::commitTransaction($transaction);
				VendorStats::model()->updateCarTypeCount($vndId);
			}
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet	 = $returnSet->setException($e);
			Logger::exception($e);
			$transaction = DBUtil::rollbackTransaction($transaction);
		}
		Logger::trace("<===Response===> : " . CJSON::encode($returnSet));
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function getDetails()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$transaction = DBUtil::beginTransaction();
		try
		{
			AppTokens::validateToken($token);
			$userInfo			 = UserInfo::getInstance();
			$vndId				 = UserInfo::getEntityId();
			$userInfo->userId	 = UserInfo::getUserId();
			$userInfo->userType	 = UserInfo::getUserType();
			$data				 = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Data.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			/** @var $obj Stub\vendor\getVehicleDetailsRequest */
			$obj		 = $jsonMapper->map($jsonObj, new \Stub\vendor\getVehicleDetailsRequest());
			/** @var Vehicle $model */
			$model		 = $obj->setModel();

			$vhcModel = Vehicles::model()->findByPk($model->vhc_id);
			if ($vhcModel)
			{
				/** @var $vhcData \Stub\common\Vehicle */
				$vhcData	 = new \Stub\common\Vehicle();
				$response	 = $vhcData->init($vhcModel);
				$returnSet->setStatus(true);
				$returnSet->setData($response);
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("Vehicle Not found", $returnSet::ERROR_NO_RECORDS_FOUND);
			}


			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	public function saveImageBycat($vehicleId, $file, $userInfo, $vhc_type)
	{
		$checkApprove = VehicleDocs::model()->checkApproveDocByVhcId($vehicleId, 1);
		if ($checkApprove <> $vhc_type)
		{
			$type		 = VehicleDocs::model()->getDocType($vhc_type);
			$typeText	 = VehicleDocs::model()->getDocTypeText($vhc_type);
			$modeld		 = new VehicleDocs();
			$success	 = $modeld->saveDocument($vehicleId, $file->getTempName(), $userInfo, 1);
			if ($success)
			{
				$path				 = VehicleDocs::model()->saveVehicleImage($file, $vehicleId, $type, $modeld->vhd_id);
				$modeld->vhd_file	 = $path;
				$modeld->save();
				$errors				 = [];
			}
			else
			{
				$getErrors = "{$typeText} not uploaded. Please upload.";
				throw new Exception($getErrors);
			}
		}
		return $success;
	}

}
