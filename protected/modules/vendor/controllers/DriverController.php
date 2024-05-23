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
			$ri	 = array(  '/citylist',   "/checkEmail", "/checkPhone");
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
		 * @deprecated
		 */
		$this->onRest('req.post.vendor_add_driver.render', function () {
			Logger::create('42 vendor_add_driver ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{
				try
				{
					$process_sync_data		 = Yii::app()->request->getParam('data');
					//$process_sync_data = '{"drv_name":"Test Driver91","drv_email":"test933@gmail.com","drv_country_code":"91","drv_phone":"8874125099","drv_lic_number":"licerno551","drv_aadhaar_number":"Adh3457"}';
					$data					 = CJSON::decode($process_sync_data, true);
					$vendorId				 = UserInfo::getEntityId();
					$drvModel				 = new Drivers();
					$data['drv_vendor_id1']	 = $vendorId;
					$drvModel->isApp		 = true;
					$returnData				 = $drvModel->saveInfo($data);
					$drv_id					 = $returnData->getData()['drv_id'];
					$success				 = $returnData->getStatus();
					$errors					 = $returnData->getError('errkey');
					$drv_ids				 = $returnData->getData()['drv_ids'];
				}
				catch (Exception $e)
				{
					$errors = $e->getMessage();
					Logger::create("Driver details not saved. -->" . $e->getMessage(), CLogger::LEVEL_ERROR);
				}
			}
			else
			{
				$success = false;
				$errors	 = 'Unauthorised Vendor';
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => $drv_id,
					'drv_ids'	 => $drv_ids
				)
			]);
		});
		/*
		 * @deprecated editinfo1 
		 * new services editInfoNew
		 */

		$this->onRest('req.post.editinfo1.render', function () {

			Logger::create('46 editinfo1 ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{
				$success			 = false;
				$errors				 = 'Something went wrong';
				//$driverId	 = Yii::app()->request->getParam('drv_id');
				/* @var $model Drivers */
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$driverId			 = $data1['drv_id'];
				$model				 = Drivers::model()->findByPk($driverId);
				$driverInfo			 = $model->getApiMappingByDriver();
				if ($driverInfo['drv_city'] != '' && $driverInfo['drv_city'] != null)
				{
					$modelCity = Cities::model()->findByPk($driverInfo['drv_city']);
				}
				if ($driverInfo['drv_state'] != '' && $driverInfo['drv_state'] != null)
				{
					$modelState = States::model()->findByPk($driverInfo['drv_state']);
				}

				if ($model != '')
				{
					$success = true;
					$errors	 = '';

					$data		 = array(
						'drv_id'			 => $model->drv_id,
						'drv_name'			 => $model->drv_name,
						'drv_email'			 => ($driverInfo['drv_email'] != '' && $driverInfo['drv_email'] != null) ? $driverInfo['drv_email'] : '',
						'drv_phone'			 => ($driverInfo['drv_phone'] != '' && $driverInfo['drv_phone'] != null) ? $driverInfo['drv_phone'] : '',
						'drv_photo'			 => ($driverInfo['drv_photo_path'] != '' && $driverInfo['drv_photo_path'] != null) ? $driverInfo['drv_photo_path'] : '',
						'drv_lic_exp_date'	 => ($driverInfo['drv_lic_exp_date'] != '' && $driverInfo['drv_lic_exp_date'] != null) ? $driverInfo['drv_lic_exp_date'] : '',
						'drv_dob_date'		 => ($model->drv_dob_date != '' && $model->drv_dob_date != null) ? $model->drv_dob_date : '',
						'drv_address'		 => ($driverInfo['drv_address'] != '' && $driverInfo['drv_address'] != null) ? $driverInfo['drv_address'] : '',
						'drv_country_code'	 => ($driverInfo['drv_country_code'] != '' && $driverInfo['drv_country_code'] != null) ? $driverInfo['drv_country_code'] : '',
						'drv_state'			 => ($driverInfo['drv_state'] != '' && $driverInfo['drv_state'] != null) ? $driverInfo['drv_state'] : '',
						'drv_state_name'	 => ($driverInfo['drv_state'] != '' && $driverInfo['drv_state'] != null) ? $modelState->stt_name : '',
						'drv_city'			 => ($driverInfo['drv_city'] != '' && $driverInfo['drv_city'] != null) ? $driverInfo['drv_city'] : '',
						'drv_city_name'		 => ($driverInfo['drv_city'] != '' && $driverInfo['drv_city'] != null) ? $modelCity->cty_name : '',
						'drv_zip'			 => ($model->drv_zip != '' && $model->drv_zip != null) ? $model->drv_zip : '',
						'drv_lic_number'	 => ($driverInfo['drv_lic_number'] != '' && $driverInfo['drv_lic_number'] != null) ? $driverInfo['drv_lic_number'] : '',
						'drv_issue_auth'	 => ($driverInfo['drv_issue_auth'] != '' && $driverInfo['drv_issue_auth'] != null) ? $driverInfo['drv_issue_auth'] : '',
						'drv_is_attached'	 => ($model->drv_is_attached != '' && $model->drv_is_attached != null) ? $model->drv_is_attached : '',
					);
					//$dataDocs	 = DriverDocs::model()->getDocsByDrvId($model->drv_id);
					$dataDocs	 = Document::model()->getDocsByDrvId($model->drv_id, $model->drv_contact_id);
					unset($dataDocs['drv_voter_id'], $dataDocs['drv_voter_back_id'], $dataDocs['drv_police_ver']);
					unset($dataDocs['drv_pan_id'], $dataDocs['drv_pan_back_id']);
					unset($dataDocs['drv_licence_id'], $dataDocs['drv_licence_back_id']);
					unset($dataDocs['drv_aadhaar_id'], $dataDocs['drv_aadhaar_back_id']);
					$newData	 = array_merge($data, $dataDocs);
				}
			}
			else
			{
				$success = false;
				$errors	 = "Vendor Unauthorised";
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => $newData,
				)
			]);
		});

		/**
		 * @deprecated since version 17-12-2019
		 */
		$this->onRest('req.post.edit_details_doc1.render', function () {

			Logger::create('48 edit_details_doc ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{
				$success	 = false;
				$oldData	 = $newData	 = $oldDocsData = $newDocsData = false;

				$process_sync_data = Yii::app()->request->getParam('data');

				Logger::create("Request =>" . $process_sync_data, CLogger::LEVEL_TRACE);

				//$driverPic					 = Yii::app()->request->getParam('driverPic');
				$data1		 = CJSON::decode($process_sync_data, true);
				$driverData	 = CJSON::decode($data1['data']);
				$driverId	 = $driverData['drv_id'];
				$driverPic	 = $data1['driverPic'];
				//Logger::create("Files =>". json_encode($_FILES), CLogger::LEVEL_TRACE);			
				$data		 = $driverData;
				$doc_type	 = $data1['doc_type'];
				$doc_subtype = $data1['doc_subtype'];

				$photo		 = $_FILES['photo']['name'];
				$photo_tmp	 = $_FILES['photo']['tmp_name'];

				$vendorId = UserInfo::getEntityId();

				$model						 = Drivers::model()->findById($driverId);
				//$modelDocs					 = DriverDocs::model()->findAllByDrvId($driverId);
				$modelDocs					 = Document::model()->findAllByDrvId($model->drv_contact_id);
				$oldDocsData['photoFile']	 = $model->drvContact->ctt_profile_path;
				$newDocsData['photoFile']	 = $model->drvContact->ctt_profile_path;
				$oldData					 = $model->attributes;
				$newData					 = $data;
				$dataSet					 = $model->getApiMappingByDriver($oldData);
				$oldData					 = array_merge($oldData, $dataSet);
				$getOldDifference			 = array_diff_assoc($oldData, $newData);
				$user_info					 = UserInfo::getInstance();
				try
				{
					$model = Drivers::model()->findByPk($driverId);
					if ($driverPic == 1)
					{
						if ($model == '')
						{
							$model = new Drivers();
						}
						$model->scenario							 = 'update';
						//$model->attributes	 = $data;
						$model->drv_name							 = $data['drv_name'];
						$model->drv_dob_date						 = $data['drv_dob_date'];
						$model->drv_zip								 = $data['drv_zip'];
						$model->drvContact->ctt_address				 = $data['drv_address'];
						$model->drvContact->ctt_city				 = $data['drv_city'];
						$model->drvContact->ctt_state				 = $data['drv_state'];
						$model->drvContact->ctt_license_no			 = $data['drv_lic_number'];
						$model->drvContact->ctt_license_exp_date	 = $data['drv_lic_exp_date'];
						$model->drvContact->ctt_dl_issue_authority	 = $data['drv_issue_auth'];

						ContactPhone::model()->updatePhoneByContactId($data['drv_phone'], $model->drv_contact_id);
						ContactEmail::model()->updateEmailByContactId($data['drv_email'], $model->drv_contact_id);
						$model->drvContact->isApp = true;
						if ($model->save())
						{
							$model->drvContact->isApp					 = true;
							$model->drvContact->addType					 = -1;
							$model->drvContact->locale_license_exp_date	 = DateTimeFormat::DateToDatePicker($model->drvContact->ctt_license_exp_date);
							$model->drvContact->save();
							if ($data['drv_email'] != NULL)
							{
								$model->drvContact->contactEmails = $model->drvContact->convertToContactEmailObjects($data['drv_email']);
								$model->drvContact->saveEmails();
								ContactEmail::setPrimaryEmail($model->drv_contact_id);
							}

							$success		 = true;
							$errors			 = [];
							$modificationMsg = $this->getModificationMSG($getOldDifference, false);
							if ($modificationMsg != '')
							{
								$changesForLog	 = "<br> Old Values: " . $modificationMsg;
								$event_id		 = DriversLog::DRIVER_MODIFIED;
								$desc			 = "Driver modified | ";
								$desc			 .= $changesForLog;
								DriversLog::model()->createLog($driverId, $desc, UserInfo::getInstance(), $event_id, false, false);
							}
						}
						else
						{
							$success = false;
							throw new Exception("Driver update failed.\n\t\t" . json_encode($model->getErrors()));
						}
//					
						if ($vendorId > 0)
						{
							$data1	 = ['driver' => $model->drv_id, 'vendor' => $vendorId];
							$linked	 = VendorDriver::model()->checkAndSave($data1);
						}
					}
					else if ($driverPic == 2)
					{

						if ($photo != '')
						{
							$type			 = 'profile';
							$result1		 = $this->saveDriverImage($photo, $photo_tmp, $driverId, $model->drv_contact_id, $type);
							$modelDocPhoto	 = new Document();
							$path1			 = str_replace("\\", "\\\\", $result1['path']);
							$qry1			 = "UPDATE contact SET ctt_profile_path = '" . $path1 . "' WHERE ctt_id = " . $model->drv_contact_id;
							$recorset1		 = Yii::app()->db->createCommand($qry1)->execute();
//							$path1		 = str_replace("\\", "\\\\", $result1['path']);
//							$qry1		 = "UPDATE drivers SET drv_photo_path = '" . $path1 . "' WHERE drv_id = " . $driverId;
//							$recorset1	 = Yii::app()->db->createCommand($qry1)->execute();

							if ($recorset1)
							{
								$success					 = true;
								$errors						 = [];
								$newDocsData['photoFile']	 = $result1['path'];
								$getOldDifferenceDocs		 = array_diff_assoc($oldDocsData, $newDocsData);
								$changesForLog				 = "<br> Old Values: Driver Selfie " . $this->getModificationMSG($getOldDifferenceDocs, false);
								$event_id					 = DriversLog::DRIVER_MODIFIED;
								$desc						 = "Driver modified | ";
								$desc						 .= $changesForLog;
								DriversLog::model()->createLog($driverId, $desc, UserInfo::getInstance(), $event_id, false, false);
								Logger::create('SUCCESS =====> : ' . "Driver Photo : " . $path1, CLogger::LEVEL_INFO);
							}
							else
							{
								$success = false;
								throw new Exception("Driver Photo creation failed.\n\t\t");
							}
						}

						if ($doc_subtype == 'voter_id')
						{
							$type = 'voterid';
							if ($model->drvContact->ctt_voter_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->drvContact->ctt_voter_doc_id);
							}
							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("Voter Front Id already exists.\n\t\t");
							}
							else
							{
								if ($model->drvContact->ctt_voter_doc_id != "")
								{
									$modelDocVoterFront = Document::model()->findByPk($model->drvContact->ctt_voter_doc_id);
									//$modelDocVoterFront->doc_status = 0;
								}
								else
								{
									$modelDocVoterFront = new Document();
								}

								$modelDocVoterFront->isDocsApp					 = true;
								$modelDocVoterFront->local_doc_file_front_path	 = $doc_subtype;
								$modelDocVoterFront->entity_id					 = $model->drv_contact_id;
								$modelDocVoterFront->doc_type					 = $doc_type;
								$modelDocVoterFront->add();

								Contact::model()->updateContact($modelDocVoterFront->doc_id, $modelDocVoterFront->doc_type, $model->drv_contact_id, '');
								$success = $model->saveDocument($driverId, $modelDocVoterFront->doc_file_front_path, $user_info, $type);

								if ($success)
								{

									Logger::create('SUCCESS =====> : ' . "Voter Id : " . $modelDocVoterFront->doc_file_front_path, CLogger::LEVEL_INFO);
									$errors = [];
								}
								else
								{
									throw new Exception("Voter Id creation failed.\n\t\t" . json_encode($modelDocVoterFront->getErrors()));
								}
							}
						}
						if ($doc_subtype == 'voter_back_id')
						{
							$type = 'voterbackid';
							if ($model->drvContact->ctt_voter_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->drvContact->ctt_voter_doc_id);
							}

							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("Voter Back Id already exists.\n\t\t");
							}
							else
							{
								if ($model->drvContact->ctt_voter_doc_id != "")
								{
									$modelDocVoterBack = Document::model()->findByPk($model->drvContact->ctt_voter_doc_id);
									//$modelDocVoterBack->doc_status = 0;
								}
								else
								{
									$modelDocVoterBack = new Document();
								}

								$modelDocVoterBack->isDocsApp				 = true;
								$modelDocVoterBack->local_doc_file_back_path = $doc_subtype;
								$modelDocVoterBack->entity_id				 = $model->drv_contact_id;
								$modelDocVoterBack->doc_type				 = $doc_type;
								$modelDocVoterBack->add();
								Contact::model()->updateContact($modelDocVoterBack->doc_id, $modelDocVoterBack->doc_type, $model->drv_contact_id, '');
								$success									 = $model->saveDocument($driverId, $modelDocVoterBack->doc_file_back_path, $user_info, $type);
								if ($success == true)
								{
									$errors = [];
									Logger::create('SUCCESS =====> : ' . "Voter Back Id : " . $modelDocVoterBack->doc_file_back_path, CLogger::LEVEL_INFO);
								}
								else
								{
									throw new Exception("Voter Back Id creation failed.\n\t\t" . json_encode($modelDocVoterBack->getErrors()));
								}
							}
						}
						if ($doc_subtype == 'aadhaar')
						{
							$type = 'aadhar';
							if ($model->drvContact->ctt_aadhar_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->drvContact->ctt_aadhar_doc_id);
							}
							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("Aadhar Id already exists.\n\t\t");
							}
							else
							{
								if ($model->drvContact->ctt_aadhar_doc_id != "")
								{
									$modelDocAadharFront = Document::model()->findByPk($model->drvContact->ctt_aadhar_doc_id);
									//$modelDocAadharFront->doc_status = 0;
								}
								else
								{
									$modelDocAadharFront = new Document();
								}

								$modelDocAadharFront->isDocsApp					 = true;
								$modelDocAadharFront->local_doc_file_front_path	 = $doc_subtype;
								$modelDocAadharFront->entity_id					 = $model->drv_contact_id;
								$modelDocAadharFront->doc_type					 = $doc_type;
								$modelDocAadharFront->add();
								Contact::model()->updateContact($modelDocAadharFront->doc_id, $modelDocAadharFront->doc_type, $model->drv_contact_id, '');
								$success										 = $model->saveDocument($driverId, $modelDocAadharFront->doc_file_front_path, $user_info, $type);
								if ($success == true)
								{
									$errors = [];
									Logger::create('SUCCESS =====> : ' . "Aadhar Id : " . $modelDocAadharFront->doc_file_front_path, CLogger::LEVEL_INFO);
								}
								else
								{
									throw new Exception("Aadhar creation failed.\n\t\t" . json_encode($modelDocAadharFront->getErrors()));
								}
							}
						}
						if ($doc_subtype == 'aadhaar_back')
						{
							$type = 'aadharback';
							//$checkApprove	 = DriverDocs::model()->checkApproveDocByDrvId($driverId, $type);
							if ($model->drvContact->ctt_aadhar_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->drvContact->ctt_aadhar_doc_id);
							}
							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("Aadhar Back Id already exists.\n\t\t");
							}
							else
							{
								if ($model->drvContact->ctt_aadhar_doc_id != "")
								{
									$modelDocAadharBack = Document::model()->findByPk($model->drvContact->ctt_aadhar_doc_id);
									//$modelDocAadharBack->doc_status = 0;
								}
								else
								{
									$modelDocAadharBack = new Document();
								}

								$modelDocAadharBack->isDocsApp					 = true;
								$modelDocAadharBack->local_doc_file_back_path	 = $doc_subtype;
								$modelDocAadharBack->entity_id					 = $model->drv_contact_id;
								$modelDocAadharBack->doc_type					 = $doc_type;
								$modelDocAadharBack->add();
								Contact::model()->updateContact($modelDocAadharBack->doc_id, $modelDocAadharBack->doc_type, $model->drv_contact_id, '');
								$success										 = $model->saveDocument($driverId, $modelDocAadharBack->doc_file_back_path, $user_info, $type);
								if ($success == true)
								{
									$errors = [];
									Logger::create('SUCCESS =====> : ' . "Aadhar Back Id : " . $modelDocAadharBack->doc_file_back_path, CLogger::LEVEL_INFO);
								}
								else
								{
									throw new Exception("Aadhar Back creation failed.\n\t\t" . json_encode($modelDocAadharBack->getErrors()));
								}
							}
						}
						if ($doc_subtype == 'pan')
						{
							$type = 'pan';
							if ($model->drvContact->ctt_pan_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->drvContact->ctt_pan_doc_id);
							}
							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("Pan already exists.\n\t\t");
							}
							else
							{
								if ($model->drvContact->ctt_pan_doc_id != "")
								{
									$modelDocPanFront = Document::model()->findByPk($model->drvContact->ctt_pan_doc_id);
									//$modelDocPanFront->doc_status = 0;
								}
								else
								{
									$modelDocPanFront = new Document();
								}

								$modelDocPanFront->isDocsApp				 = true;
								$modelDocPanFront->local_doc_file_front_path = $doc_subtype;
								$modelDocPanFront->entity_id				 = $model->drv_contact_id;
								$modelDocPanFront->doc_type					 = $doc_type;
								$modelDocPanFront->add();
								Contact::model()->updateContact($modelDocPanFront->doc_id, $modelDocPanFront->doc_type, $model->drv_contact_id, '');
								$success									 = $model->saveDocument($driverId, $modelDocPanFront->doc_file_front_path, $user_info, $type);
								if ($success == true)
								{
									$errors = [];
									Logger::create('SUCCESS =====> : ' . "Pan Id : " . $modelDocPanFront->doc_file_front_path, CLogger::LEVEL_INFO);
								}
								else
								{
									throw new Exception("Pan creation failed.\n\t\t" . json_encode($modelDocPanFront->getErrors()));
								}
							}
						}
						if ($doc_subtype == 'pan_back')
						{
							$type = 'panback';
							if ($model->drvContact->ctt_pan_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->drvContact->ctt_pan_doc_id);
							}

							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("Pan Back already exists.\n\t\t");
							}
							else
							{
								if ($model->drvContact->ctt_pan_doc_id != "")
								{
									$modelDocPanBack = Document::model()->findByPk($model->drvContact->ctt_pan_doc_id);
									//$modelDocPanBack->doc_status = 0;
								}
								else
								{
									$modelDocPanBack = new Document();
								}

								$modelDocPanBack->isDocsApp					 = true;
								$modelDocPanBack->local_doc_file_back_path	 = $doc_subtype;
								$modelDocPanBack->entity_id					 = $model->drv_contact_id;
								$modelDocPanBack->doc_type					 = $doc_type;
								$modelDocPanBack->add();
								Contact::model()->updateContact($modelDocPanBack->doc_id, $modelDocPanBack->doc_type, $model->drv_contact_id, '');
								$success									 = $model->saveDocument($driverId, $modelDocPanBack->doc_file_back_path, $user_info, $type);

								if ($success == true)
								{
									$errors = [];
									Logger::create('SUCCESS =====> : ' . "Pan Back Id : " . $modelDocPanBack->doc_file_back_path, CLogger::LEVEL_INFO);
								}
								else
								{
									throw new Exception("Pan Back creation failed.\n\t\t" . json_encode($modelDocPanBack->getErrors()));
								}
							}
						}
						if ($doc_subtype == 'license')
						{
							$type = 'license';
							if ($model->drvContact->ctt_license_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->drvContact->ctt_license_doc_id);
							}
							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("License Front already exists.\n\t\t");
							}
							else
							{
								if ($model->drvContact->ctt_license_doc_id != "")
								{
									$modelDocLicenseFront = Document::model()->findByPk($model->drvContact->ctt_license_doc_id);
									//$modelDocLicenseFront->doc_status = 0;
								}
								else
								{
									$modelDocLicenseFront = new Document();
								}
								$modelDocLicenseFront->isDocsApp				 = true;
								$modelDocLicenseFront->local_doc_file_front_path = $doc_subtype;
								$modelDocLicenseFront->entity_id				 = $model->drv_contact_id;
								$modelDocLicenseFront->doc_type					 = $doc_type;
								$modelDocLicenseFront->add();
								Contact::model()->updateContact($modelDocLicenseFront->doc_id, $modelDocLicenseFront->doc_type, $model->drv_contact_id, '');
								$success										 = $model->saveDocument($driverId, $modelDocLicenseFront->doc_file_front_path, $user_info, $type);
								if ($success == true)
								{
									$errors = [];
									Logger::create('SUCCESS =====> : ' . "License Front Id : " . $modelDocLicenseFront->doc_file_front_path, CLogger::LEVEL_INFO);
								}
								else
								{
									throw new Exception("License creation failed.\n\t\t" . json_encode($modelDocLicenseFront->getErrors()));
								}
							}
						}
						if ($doc_subtype == 'license_back')
						{
							$type = 'licenseback';
							if ($model->drvContact->ctt_license_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->drvContact->ctt_license_doc_id);
							}
							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("License Back already exists.\n\t\t");
							}
							else
							{
								if ($model->drvContact->ctt_license_doc_id != "")
								{
									$modelDocLicenseBack = Document::model()->findByPk($model->drvContact->ctt_license_doc_id);
									//$modelDocLicenseBack->doc_status = 0;
								}
								else
								{
									$modelDocLicenseBack = new Document();
								}

								$modelDocLicenseBack->isDocsApp					 = true;
								$modelDocLicenseBack->local_doc_file_back_path	 = $doc_subtype;
								$modelDocLicenseBack->entity_id					 = $model->drv_contact_id;
								$modelDocLicenseBack->doc_type					 = $doc_type;
								$modelDocLicenseBack->add();
								Contact::model()->updateContact($modelDocLicenseBack->doc_id, $modelDocLicenseBack->doc_type, $model->drv_contact_id, '');
								$success										 = $model->saveDocument($driverId, $modelDocLicenseBack->doc_file_back_path, $user_info, $type);
								if ($success == true)
								{
									$errors = [];
									Logger::create('SUCCESS =====> : ' . "License Back Id : " . $modelDocLicenseBack->doc_file_back_path, CLogger::LEVEL_INFO);
								}
								else
								{
									throw new Exception("License Back creation failed.\n\t\t" . json_encode($modelDocLicenseBack->getErrors()));
								}
							}
						}
						if ($doc_subtype == 'pvc_verification')
						{
							$type = 'policever';

							if ($model->drvContact->ctt_police_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->drvContact->ctt_police_doc_id);
							}
							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("Police verification already exists.\n\t\t");
							}
							else
							{

								if ($model->drvContact->ctt_police_doc_id != "")
								{
									$modelDocPoliceVerify							 = Document::model()->findByPk($model->drvContact->ctt_police_doc_id);
									$modelDocPoliceVerify->local_doc_file_front_path = 'verification';
								}
								else
								{
									$modelDocPoliceVerify							 = new Document();
									$modelDocPoliceVerify->local_doc_file_front_path = trim($doc_subtype);
								}

								$modelDocPoliceVerify->isDocsApp = true;
								$modelDocPoliceVerify->entity_id = $model->drv_contact_id;
								$modelDocPoliceVerify->doc_type	 = $doc_type;
								$modelDocPoliceVerify->add();
								Contact::model()->updateContact($modelDocPoliceVerify->doc_id, $modelDocPoliceVerify->doc_type, $model->drv_contact_id, '');
								$success						 = $model->saveDocument($driverId, $modelDocPoliceVerify->doc_file_front_path, $user_info, $type);
								Logger::create('SUCCESS =====> : ' . $success, CLogger::LEVEL_INFO);
								if ($success == true)
								{
									$errors = [];
									Logger::create('SUCCESS =====> : ' . "Police Verification : " . $modelDocPoliceVerify->doc_file_front_path, CLogger::LEVEL_INFO);
								}
								else
								{
									throw new Exception("Police Verification creation failed.\n\t\t" . json_encode($modelDocPoliceVerify->getErrors()));
								}
							}
						}
					}
					else
					{
						throw new Exception("Request category not matched.\n\t\t");
					}
				}
				catch (Exception $e)
				{
					$errors = $e->getMessage();
					Logger::create("Driver details or document not saved. -->" . $e->getMessage(), CLogger::LEVEL_ERROR);
				}
			}
			else
			{
				$success = false;
				$errors	 = 'Vendor Unathorised';
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
				//'data'		 => $model,
				),
			]);
		});
		/*
		 * @deprecated edit_details_doc 
		 * new services editDoc
		 */
		$this->onRest('req.post.edit_details_doc.render', function () {

			Logger::create('48 edit_details_doc ', CLogger::LEVEL_TRACE);

			$returnSet	 = new ReturnSet();
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result		 = Vendors::model()->authoriseVendor($token);

			if (!$result)
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors('Vendor Unathorised');
				goto resultResponse;
			}


			$process_sync_data	 = Yii::app()->request->getParam('data');
			Logger::create("Request =>" . $process_sync_data, CLogger::LEVEL_TRACE);
			$data1				 = CJSON::decode($process_sync_data, true);
			$data				 = CJSON::decode($data1['data']);
			$data['vendor_id']	 = UserInfo::getEntityId();
			$isFileData			 = $data1['driverPic'];
			$transaction		 = DBUtil::beginTransaction();
			try
			{
				if ($isFileData == 1)
				{
					$returnSet = $this->editDriverDetails($data);
				}
				else if ($isFileData == 2)
				{
					$returnSet = Document::model()->updateDriverDoc($data['drv_id'], $_FILES['photo']['name'], $_FILES['photo']['tmp_name'], $data1['doc_type'], $data1['doc_subtype']);
				}
				if (!$returnSet->isSuccess())
				{
					DBUtil::rollbackTransaction($transaction);
					goto resultResponse;
				}
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $e)
			{
				DBUtil::rollbackTransaction($transaction);
				//$returnSet->setStatus(false);
				//$returnSet->setErrors($e->getMessage());
				$returnSet = ReturnSet::setException($e);
			}
			resultResponse:

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $returnSet->isSuccess(),
					'errors'	 => $returnSet->getErrors(),
				),
			]);
		});

		/* @deprecated edit1

		 * New function updateInfo()for data only.

		 */

		$this->onRest('req.post.edit1.render', function () {

			$success = false;
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);

			if ($result == true)
			{
				$process_sync_data = Yii::app()->request->getParam('data');
				Logger::create('49 DATA =============' . $process_sync_data, CLogger::LEVEL_TRACE);

				$data1			 = CJSON::decode($process_sync_data, true);
				$userInfo		 = UserInfo::getInstance();
				$vendorId		 = UserInfo::getEntityId();
				$driverPic		 = $data1['driverPic'];
				$data			 = CJSON::decode($data1['data'], true);
				$licence		 = $_FILES['license']['name'];
				$licence_tmp	 = $_FILES['license']['tmp_name'];
				$licence_back	 = $_FILES['license_back']['name'];
				$driverId		 = $data['drv_id'];
				$doc_type		 = $data1['doc_type'];
				$doc_subtype	 = $data1['doc_subtype'];
				$newData		 = $data;
				try
				{
					$transaction = DBUtil::beginTransaction();
					if ($data['drv_id'] > 0)
					{
						$model	 = Drivers::model()->findByPk($data['drv_id']);
						$oldData = $model->attributes;
						$dataSet = $model->getApiMappingByDriver($oldData);
						$oldData = array_merge($oldData, $dataSet);

						switch ($driverPic)
						{
							case 0;
								$model->scenario = 'update';
								break;
							case 1:
								$model->scenario = 'update';
								break;
							case 2:
								$model->scenario = 'updateApproval';

								break;
						}
					}
					else
					{
						$model			 = new Driver();
						$model->scenario = 'insertadminapp';
					}
					$model->attributes = $data;
					if ($model->drv_contact_id == '')
					{
						$contactModel						 = new Contact();
						$contactModel->ctt_license_no		 = $data['drv_lic_number'];
						$contactModel->ctt_license_exp_date	 = $data['drv_lic_exp_date'];
					}
					else
					{
						$model->drvContact->ctt_license_no		 = $data['drv_lic_number'];
						$model->drvContact->ctt_license_exp_date = $data['drv_lic_exp_date'];
					}


					if ($model->validate())
					{
						if ($model->save())
						{

							if ($model->drv_contact_id == '')
							{
								$model->drv_contact_id			 = $contactModel->ctt_id;
								$model->save();
								$contEmailModel->eml_contact_id	 = $contactModel->ctt_id;
								$contEmailModel->save();
								$conPhoneModel->phn_contact_id	 = $conPhoneModel->ctt_id;
								$conPhoneModel->save();
							}
							else
							{

								$model->drvContact->update();
							}
							$driverId = ($data['drv_id'] > 0) ? $data['drv_id'] : Yii::app()->db->lastInsertID;
							if ($data['drv_id'] > 0)
							{
								$getOldDifference	 = array_diff_assoc($oldData, $newData);
								$changesForLog		 = "<br> Old Values: " . $this->getModificationMSG($getOldDifference, false);
								$desc				 = "Driver modified | ";
								$desc				 .= $changesForLog;
								$event_id			 = DriversLog::DRIVER_MODIFIED;
							}
							else
							{
								$desc		 = "Driver created |";
								$event_id	 = DriversLog::DRIVER_CREATED;
							}
							DriversLog::model()->createLog($driverId, $desc, UserInfo::getInstance(), $event_id, false, false);
							if ($vendorId > 0)
							{
								$data1	 = ['driver' => $model->drv_id, 'vendor' => $vendorId];
								$linked	 = VendorDriver::model()->checkAndSave($data);
							}
						}

						//licence 
						if ($doc_subtype == 'license')
						{
							$type = 'license';
							if ($model->drvContact->ctt_license_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->drvContact->ctt_license_doc_id);
							}
							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("License Front already exists.\n\t\t");
							}
							else
							{

								if ($model->drvContact->ctt_license_doc_id == "" || $model->drvContact->ctt_license_doc_id == 0)
								{
									$modelDocLicenseFront = new Document();
									//$modelDocLicenseFront->doc_status = 0;
								}
								else
								{
									$modelDocLicenseFront = Document::model()->findByPk($model->drvContact->ctt_license_doc_id);
								}

								$modelDocLicenseFront->isDocsApp				 = true;
								$modelDocLicenseFront->local_doc_file_front_path = $doc_subtype;
								$modelDocLicenseFront->entity_id				 = $model->drv_contact_id;
								$modelDocLicenseFront->doc_type					 = $doc_type;
								$modelDocLicenseFront->add();
								Contact::model()->updateContact($modelDocLicenseFront->doc_id, $modelDocLicenseFront->doc_type, $model->drv_contact_id, '');
								$success										 = $model->saveDocument($driverId, $modelDocLicenseFront->doc_file_front_path, $userInfo, $type);

								if ($success)
								{
									$errors = [];
									Logger::create('SUCCESS =====> : ' . "license:" . $driverId . " - " . $modelDocLicenseFront->doc_file_front_path, CLogger::LEVEL_INFO);
								}
								else
								{
									$getErrors = "license not uploaded. Please upload.";
									throw new Exception($getErrors);
								}
							}
						}
						if ($doc_subtype == 'license_back')
						{
							$type = 'licenseback';
							if ($model->drvContact->ctt_license_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->drvContact->ctt_license_doc_id);
							}

							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("License Back already exists.\n\t\t");
							}
							else
							{
								if ($model->drvContact->ctt_license_doc_id == "" || $model->drvContact->ctt_license_doc_id == 0)
								{
									$modelDocLicenseBack = new Document();
									//$modelDocLicenseBack->doc_status = 0;
								}
								else
								{
									$modelDocLicenseBack = Document::model()->findByPk($model->drvContact->ctt_license_doc_id);
								}

								$modelDocLicenseBack->isDocsApp					 = true;
								$modelDocLicenseBack->local_doc_file_back_path	 = $doc_subtype;
								$modelDocLicenseBack->entity_id					 = $model->drvContact;
								$modelDocLicenseBack->doc_type					 = $doc_type;
								$modelDocLicenseBack->add();
								Contact::model()->updateContact($modelDocLicenseBack->doc_id, $modelDocLicenseBack->doc_type, $model->drv_contact_id, '');
								$success										 = $model->saveDocument($driverId, $modelDocLicenseBack->doc_file_back_path, $userInfo, $type);
								if ($success)
								{
									Logger::create('SUCCESS =====> : ' . "License Back Id : " . $modelDocLicenseBack->doc_file_back_path, CLogger::LEVEL_INFO);
									$errors = "";
								}
								else
								{
									$errors = ("License Back creation failed.\n\t\t");
								}
							}
						}



						DBUtil::commitTransaction($transaction);
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
			}
			else
			{
				$success = 'false';
				$errors	 = 'Vendor Unauthorised';
			}

			Logger::create("Response: " . json_encode($model->attributes) . " Errors : " . $errors . " Success : " . $success, CLogger::LEVEL_INFO);

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => $model
				),
			]);
		});

		/* ======================================================================== */
		$this->onRest('req.post.add.render', function () {
			$success = false;
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data				 = CJSON::decode($process_sync_data, true);
				$vendorId			 = UserInfo::getEntityId();
				$drvModel			 = new Drivers();
				$success			 = $drvModel->add($data, $vendorId);
				$data				 = [];
				if (!$success)
				{
					$data = ['errors' => $drvModel->getErrors()];
				}
				else
				{
					$data = ['data' => ['id' => $drvModel->drv_id]];
				}
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
			'success' => $success,
				) + $data
			]);
		});
		/* ======================================================================= */
		$this->onRest('req.post.vendor_cab_driver_list.render', function () {

			Logger::create('30 vendor_cab_driver_list ', CLogger::LEVEL_TRACE);
			//$vendorId	 = Yii::app()->user->getId();
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{
				$vendorId			 = UserInfo::getEntityId();
				//$decision	 = Yii::app()->request->getParam('decision');
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$decision			 = $data1['decision'];
				if ($decision == "" || $decision == "cab")
				{
					$cabModel = Vehicles::model()->getcabDetails($vendorId);
					if ($cabModel != [])
					{
						$success	 = true;
						$caberror	 = null;
					}
					else
					{
						$success	 = false;
						$caberror	 = "Error occured while fetching list";
					}
				}
				if ($decision == "" || $decision == "driver")
				{
					$driverModel = Drivers::model()->getdriverDetails($vendorId);

					if ($driverModel != [])
					{
						$success	 = true;
						$drivererror = null;
					}
					else
					{
						$success	 = false;
						$drivererror = "Error occured while fetching list";
					}
				}
				if ($caberror == null && $drivererror == null)
				{
					$error = null;
				}
				elseif ($caberror != null && $drivererror == null)
				{
					$error = $caberror;
				}
				elseif ($caberror == null && $drivererror != null)
				{
					$error = $drivererror;
				}
				elseif ($caberror != null && $drivererror != null)
				{
					$error = $caberror;
				}
			}
			else
			{
				$success = false;
				$error	 = 'Vendor Unauthorised';
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'error'		 => $error,
					'cab'		 => $cabModel,
					'driver'	 => $driverModel,
				)
			]);
		});

		/* ============================================================= */
		$this->onRest('req.post.cab_driver_list.render', function () {
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{
				$vendorId			 = UserInfo::getEntityId();
				$data				 = [];
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$decision			 = $data1['decision'];
				//$decision	 = Yii::app()->request->getParam('decision');
				if ($decision == "" || $decision == "cab")
				{
					$cabModel = Vehicles::model()->getcabDetails($vendorId);
					if ($cabModel)
					{
						$success = true;
						$data	 = ['data' => ['cabs' => $cabModel]];
					}
					else
					{
						$success = false;
						$data	 = ['errors' => ['error' => ["Error occured while fetching list"]]];
					}
				}
				if ($decision == "" || $decision == "driver")
				{
					$driverModel = Drivers::model()->getdriverDetails($vendorId);
					if ($driverModel)
					{
						$success = true;
						$data	 = ['data' => ['drivers' => $driverModel]];
					}
					else
					{
						$success = false;
						$data	 = ['errors' => ['error' => ["Error occured while fetching list"]]];
					}
				}
			}
			else
			{
				$success = false;
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
			'success' => $success,
				) + $data
			]);
		});
		/* ============================================================= */

		$this->onRest('req.post.list.render', function () {

			Logger::create('43 list ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{
				$vendorId	 = UserInfo::getEntityId();
				$driverModel = Drivers::model()->getDetails($vendorId);
				if ($driverModel != [])
				{
					$success = true;
					$error	 = null;
				}
				else
				{
					$success = false;
					$error	 = "Error occured while fetching list";
				}
			}
			else
			{
				$success = false;
				$error	 = "Vendor Unauthorised";
			}
			//Logger::create('DRIVER LIST:'.'SUCCESS:'.$success.'ERRROR:'.$error.'DRIVER MODEL:'. json_encode($driverModel), CLogger::LEVEL_TRACE);
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'error'		 => $error,
					'model'		 => $driverModel,
				)
			]);
		});

		$this->onRest('req.post.citylist.render', function () {

			Logger::create('43 citylist ', CLogger::LEVEL_TRACE);

			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$state_id			 = $data1['state_id'];
			$search_txt			 = $data1['search_txt'];
			$cityModel			 = Cities::model()->getCityList1($state_id, $search_txt);
			if ($cityModel != [])
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
					'success'	 => $success,
					'error'		 => $error,
					'model'		 => $cityModel,
				)
			]);
		});

		$this->onRest('req.post.edit.render', function () {

			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{
				$vendorId			 = UserInfo::getEntityId();
				$success			 = false;
				$errors				 = 'Something went wrong while uploading';
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$driverPic			 = Yii::app()->request->getParam('driverPic');
				$photo				 = $_FILES['photo']['name'];
				$photo_tmp			 = $_FILES['photo']['tmp_name'];
				$voter_id			 = $_FILES['voter_id']['name'];
				$voter_id_tmp		 = $_FILES['voter_id']['tmp_name'];
				$aadhaar			 = $_FILES['aadhaar']['name'];
				$aadhaar_tmp		 = $_FILES['aadhaar']['tmp_name'];
				$pan				 = $_FILES['pan']['name'];
				$pan_tmp			 = $_FILES['pan']['tmp_name'];
				$licence			 = $_FILES['license']['name'];
				$licence_tmp		 = $_FILES['license']['tmp_name'];
				$verification		 = $_FILES['verification']['name'];
				$verification_tmp	 = $_FILES['verification']['tmp_name'];
				$data				 = CJSON::decode($process_sync_data, true);
				$driverId			 = $data['drv_driver_id'];
				if ($driverPic == 0)
				{
					$model = DriversInfo::model()->find('drv_driver_id=:id', ['id' => $driverId]);
					if ($model == '')
					{
						$model = new DriversInfo();
					}
					$model->attributes		 = $data;
					$model->drv_approved	 = 2;
					$model->scenario == 'editDriver';
					$model->drv_vendor_id	 = $vendorId;
					if ($model->validate())
					{
						$success					 = $model->save();
						$driver_model				 = Drivers::model()->findByPk($driverId);
						$driver_model->drv_approved	 = 2;
						$driver_model->save();
					}
					$errors = $model->getErrors();
				}
				try
				{
					if ($photo != '')
					{
						$type		 = 'profile';
						$result1	 = $this->saveDriverImage($photo, $photo_tmp, $driverId, $type);
						$path1		 = str_replace("\\", "\\\\", $result1['path']);
						$qry1		 = "UPDATE drivers_info SET drv_photo_path = '" . $path1 . "' WHERE drv_driver_id = " . $driverId;
						$recorset1	 = Yii::app()->db->createCommand($qry1)->execute();
						if ($recorset1)
						{
							$success = true;
							$errors	 = [];
						}
					}
					if ($voter_id != '')
					{
						$type		 = 'voterid';
						$result2	 = $this->saveDriverImage($voter_id, $voter_id_tmp, $driverId, $type);
						$path2		 = str_replace("\\", "\\\\", $result2['path']);
						$qry2		 = "UPDATE drivers_info SET drv_voter_id_img_path = '" . $path2 . "' WHERE drv_driver_id = " . $driverId;
						$recordset2	 = Yii::app()->db->createCommand($qry2)->execute();
						if ($recordset2)
						{
							$success = true;
							$errors	 = [];
						}
					}
					if ($aadhaar != '')
					{
						$type		 = 'adhar';
						$result3	 = $this->saveDriverImage($aadhaar, $aadhaar_tmp, $driverId, $type);
						$path3		 = str_replace("\\", "\\\\", $result3['path']);
						$qry3		 = "UPDATE drivers_info SET drv_aadhaar_img_path = '" . $path3 . "' WHERE drv_driver_id = " . $driverId;
						$recordset3	 = Yii::app()->db->createCommand($qry3)->execute();
						if ($recordset3)
						{
							$success = true;
							$errors	 = [];
						}
					}
					if ($pan != '')
					{
						$type		 = 'pan';
						$result4	 = $this->saveDriverImage($pan, $pan_tmp, $driverId, $type);
						$path4		 = str_replace("\\", "\\\\", $result4['path']);
						$qry4		 = "UPDATE drivers_info SET drv_pan_img_path = '" . $path4 . "' WHERE drv_driver_id = " . $driverId;
						$recordset4	 = Yii::app()->db->createCommand($qry4)->execute();
						if ($recordset4)
						{
							$success = true;
							$errors	 = [];
						}
					}
					if ($licence != '')
					{
						$type		 = 'license';
						$result5	 = $this->saveDriverImage($licence, $licence_tmp, $driverId, $type);
						$path5		 = str_replace("\\", "\\\\", $result5['path']);
						$qry5		 = "UPDATE drivers_info SET drv_licence_path = '" . $path5 . "' WHERE drv_driver_id = " . $driverId;
						$recordset5	 = Yii::app()->db->createCommand($qry5)->execute();
						if ($recordset5)
						{
							$success = true;
							$errors	 = [];
						}
					}
					if ($verification != '')
					{
						$type		 = 'police';
						$result6	 = $this->saveDriverImage($verification, $verification_tmp, $driverId, $type);
						$path6		 = str_replace("\\", "\\\\", $result6['path']);
						$qry6		 = "UPDATE drivers_info SET drv_police_certificate = '" . $path6 . "' WHERE drv_driver_id = " . $driverId;
						$recordset6	 = Yii::app()->db->createCommand($qry6)->execute();
						if ($recordset6)
						{
							$success = true;
							$errors	 = [];
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
				$errors	 = "Vendor Unauthorised";
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => $model,
				),
			]);
		});

		$this->onRest('req.post.statusDetails.render', function () {

			Logger::create('37 statusDetails ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{
				$vendorId			 = UserInfo::getEntityId();
				$success			 = false;
				$errors				 = [];
				//$drvId		 = Yii::app()->request->getParam('drv_id');
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$drvId				 = $data1['drv_id'];
				$modelDrvId			 = Drivers::model()->findByPk($drvId);
				if (isset($modelDrvId->drv_id) && $modelDrvId->drv_id > 0)
				{
					$status				 = true;
					$approveStatus		 = (int) $modelDrvId->drv_approved;
					$activeStatus		 = (int) $modelDrvId->drv_active;
					//$listDocs			 = DriverDocs::model()->findAllByDrvId($drvId);
					$listDocs			 = Document::model()->findAllByDrvId($modelDrvId->drv_contact_id);
					$driLicenceStatus	 = 0;
					$count				 = 0;

					if (($listDocs[0]['doc_status5'] == 0 || $listDocs[0]['doc_status5'] == 1) && $listDocs[0]['doc_file_front_path5'] != '')
					{
						$driLicenceStatus	 = 1;
						$count				 = 1;
					}
					if ($listDocs[0]['doc_police_status'] == null || $listDocs[0]['doc_police_status'] == 2)
					{
						$isPoliceVerification	 = 0;
						$message				 = "Police Verification certificate for this driver needs to be uploaded. Please upload the documents in Gozo Partner app within 15 days.";
					}
					else
					{
						$isPoliceVerification = 1;
					}
					$isDocumentUploaded	 = ($count == 1) ? true : false;
					$docs				 = ['driver_licence_front' => (int) $driLicenceStatus];
					$success			 = true;
				}
				else
				{
					$errors = "Driver not exist.";
				}
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'				 => $success,
					'errors'				 => $errors,
					'isDocumentUploaded'	 => $isDocumentUploaded,
					'docs'					 => $docs,
					'police_verification'	 => ['police_verification' => (int) $isPoliceVerification, 'message' => $message],
				)
			]);
		});

		$this->onRest('req.get.getlist.render', function () {
			return $this->getList();
		});
		$this->onRest('req.post.tripGetList.render', function () {
			return $this->tripGetList();
		});

		$this->onRest('req.post.driverList.render', function () {
			return $this->renderJSON($this->driverList());
		});

		$this->onRest("req.get.checkEmail.render", function () {
			return $this->renderJSON($this->checkEmail());
		});

		$this->onRest("req.get.checkPhone.render", function () {
			return $this->renderJSON($this->checkPhone());
		});
		$this->onRest("req.post.verifyLinkDriver.render", function () {
			return $this->renderJSON($this->verifyLinkDriver());
		});
		$this->onRest("req.post.addContact.render", function () {
			return $this->renderJSON($this->addContactNew());
		});
		$this->onRest("req.post.editInfoNew.render", function () {
			return $this->renderJSON($this->editInfo());
		});
		$this->onRest("req.post.cityListNew.render", function () {

			return $this->renderJSON($this->cityList());
		});
		$this->onRest("req.post.updateInfo.render", function () {

			return $this->renderJSON($this->updateInfo());
		});
		$this->onRest("req.post.editDoc.render", function () {

			return $this->renderJSON($this->editDoc());
		});
		$this->onRest("req.post.uploadPckImages.render", function () {

			return $this->renderJson($this->uploadPckImages());
		});
		$this->onRest('req.post.checkLinking.render', function () {

			return $this->renderJSON($this->checkLinking());
		});

		$this->onRest('req.post.assignment.render', function () {
			return $this->renderJSON($this->assignment());
		});

	}

	public static function editDoc()
	{

		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{

			$vndId = UserInfo::getEntityId();
			if (!$vndId)
			{
				throw new Exception("Invalid Vendor.", ReturnSet::ERROR_UNAUTHORISED);
			}
			#$data = Yii::app()->request->rawBody;
			$data = Yii::app()->request->getParam('data');
			Logger::create('Info DATA Edit=============' . $data, CLogger::LEVEL_TRACE);
			if (!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj = CJSON::decode($data, false);

			$dvrResponse = new \Stub\common\Driver();
			$model		 = $dvrResponse->setDocumentData($jsonObj);
			if (!empty($model))
			{
				$returnSet	 = Document::model()->updateDriverDocument($model, $_FILES['photo']['name'], $_FILES['photo']['tmp_name']);
				$driverId	 = $model->id;
				
			}
			if (!$returnSet->isSuccess())
			{
				DBUtil::rollbackTransaction($transaction);
				throw new Exception("File not updated", ReturnSet::ERROR_INVALID_DATA);
			}
			else
			{
				$returnSet->setMessage("Document added successfully");
			}

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public static function updateInfo()
	{
		$returnSet = new ReturnSet();

		try
		{

			$vndId = UserInfo::getEntityId();
			if (!$vndId)
			{
				throw new Exception("Invalid Vendor.", ReturnSet::ERROR_UNAUTHORISED);
			}
			$process_sync_data	 = Yii::app()->request->rawBody;
			$data				 = CJSON::decode($process_sync_data, true);

			Logger::create('Info DATA update=============' . $process_sync_data, CLogger::LEVEL_TRACE);
			$dvrResponse = new \Stub\common\Driver();
			$model		 = $dvrResponse->getProfileData($data);

			#$modelOld = Contact::model()->findByPk($model->cttId);
			$success = drivers::model()->updateDriverdata($model);

			if ($success)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Driver data updated successfully");
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("Error Occure");
			}
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public static function cityList()
	{

		$returnSet = new ReturnSet();

		try
		{
			//Vendors::model()->authoriseVendor($token);
			$vndId = UserInfo::getEntityId();
			if (!$vndId)
			{
				throw new Exception("Invalid Vendor.", ReturnSet::ERROR_UNAUTHORISED);
			}
			$process_sync_data	 = Yii::app()->request->rawBody;
			$data				 = CJSON::decode($process_sync_data, true);
			$state_id			 = $data['id'];
			$search_txt			 = $data['search_txt'];
			if($state_id == 0)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$cityModel			 = Cities::model()->getCityList1($state_id, $search_txt);
			#print_r($cityModel);
			$cityResponse		 = new \Stub\common\Cities();
			$cityResponse->CityStateList($cityModel);
			$response			 = Filter::removeNull($cityResponse);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public static function editInfo()
	{
		$returnSet = new ReturnSet();
		try
		{
			$vndId = UserInfo::getEntityId();
			//

			if (empty($vndId))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			$data		 = Yii::app()->request->rawBody;
			$data1		 = CJSON::decode($data, true);
			//Logger::create('Info DATA =============' . $data, CLogger::LEVEL_TRACE);
			$driverId	 = $data1['id'];

			if (empty($driverId))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			$driverModel = Drivers::model()->findByPk($driverId);

			$contactModel = Contact::model()->findByPk($driverModel->drv_contact_id);
			if (empty($driverModel) || empty($contactModel))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}

			$dvrResponse = new \Stub\common\Driver();
			$dvrResponse->setProfileData($driverModel, $contactModel);

			$response = Filter::removeNull($dvrResponse);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch (Exception $ex)
		{

			Logger::error($ex->getMessage());
			$returnSet->setException($ex);
		}

		return $returnSet;
	}

	public static function driverList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$vndId = UserInfo::getEntityId();
			if (empty($vndId))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}

			$driverList	 = Drivers::model()->getDetails($vndId);
			$drvList	 = new Stub\common\Driver();
			$drvList->getList($driverList);
			$response	 = Filter::removeNull($drvList);
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

	/**
	 * Sample request: {"emailId": ""}
	 * This function is used for find the email addresses whether its already added or not
	 * 
	 * @return type
	 * @throws Exception
	 */
	public static function checkEmail()
	{
		$returnSet = new ReturnSet();
		try
		{
			$requestInstance = Yii::app()->request;
			$emailId		 = $requestInstance->getParam("emailId");

			if (empty($emailId))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}

			//Check function defination for param details
			$returnSet = ContactEmail::findEmail($emailId, 0, 1, 1);

			if (!$returnSet->getStatus())
			{
				goto SkipAll;
			}

			$response = [];
			foreach ($returnSet->getData() as $value)
			{
				$contactResponse = new Stub\common\ContactResponse();
				/** @var \Stub\common\ContactResponse $contactResponse */
				$contactResponse->setData($value, \Stub\common\ContactMedium::TYPE_EMAIL);
			}

			$data = Filter::removeNull($contactResponse);
			$returnSet->setStatus(true);
			$returnSet->setData($data);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		SkipAll:
		return $returnSet;
	}

	/** Sample request: {"phoneNumber": ""}
	 * This function is used for find the phone number whether its already added or not
	 * 
	 * @return type
	 * @throws Exception
	 */
	public static function checkPhone()
	{
		$returnSet = new ReturnSet();
		try
		{
			$requestInstance = Yii::app()->request;
			$phNo			 = $requestInstance->getParam("phNo");

			if (empty($phNo))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}

			//Check function defination for param details
			$returnSet = ContactPhone::findPhone($phNo, 0, 1, 1);

			if (!$returnSet->getStatus())
			{
				goto SkipAll;
			}

			$response = [];
			foreach ($returnSet->getData() as $value)
			{
				$contactResponse = new Stub\common\ContactResponse();
				/** @var \Stub\common\ContactResponse $contactResponse */
				$contactResponse->setData($value, \Stub\common\ContactMedium::TYPE_PHONE);
			}

			$data = Filter::removeNull($contactResponse);
			$returnSet->setStatus(true);
			$returnSet->setData($data);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		SkipAll:
		return $returnSet;
	}

	/**
	 * @deprecated since version 28/04/2020
	 * new function name - addContactNew
	 * Sample request : {"documents":{"Licence":{"refValue":"FGHVFJHFHG"}},"email":"sk@gmail.com","firstName":"Sudhansu ","lastName":"Roy","primaryContact":{"code":91,"number":"9609275445"}}
	 * This function is used for adding driver contact from vendor app.
	 * 
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function addContact()
	{
		$returnSet = new ReturnSet();
		try
		{
			$requestInstance = Yii::app()->request;
			$receivedData	 = json_decode($requestInstance->rawBody);
			if (empty($receivedData))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}

			//Logger::create(json_encode($receivedData));

			$jsonMapper			 = new JsonMapper();
			$tmpContactStub		 = new Stub\common\ContactMedium();
			/** @var JsonMapper $obj */
			$obj				 = $jsonMapper->map($receivedData, $tmpContactStub);
			$contactMediumModel	 = $obj->getMedium();
			$returnSet			 = $contactMediumModel->validateContactItem();

			if ($returnSet->getErrors())
			{
				$returnSet->setMessage("Failed to create driver");
				goto skipAll;
			}

			//Means contact exists
			if ($returnSet->getStatus())
			{
				goto skipAll;
			}

			/** @var Contact $contactMediumModel */
			$returnSet = $contactMediumModel->handleContact();
			if (!$returnSet->getStatus())
			{
				goto skipAll;
			}
			/** @var Drivers $contactMediumModel */
			$returnSet = $contactMediumModel->handleEntity($returnSet->getData());
			if (!$returnSet->getStatus())
			{
				goto skipAll;
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		skipAll:
		return $returnSet;
	}

	public function getList()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->getParam('search_txt', '');

		try
		{
			//$drvData	 = Drivers::getListByVendor(UserInfo::getEntityId(), trim($data));
			$vendorId = UserInfo::getEntityId();
			$drvData	 = Drivers::getLstByVendor(UserInfo::getEntityId(), trim($data));
			$drvList	 = new Stub\common\Driver();
			$drvList->getList($drvData);
			$response	 = Filter::removeNull($drvList);
			if (!$response)
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			if($vendorId == 78626)
			{
			
				Logger::trace("<===Response===>".json_encode($response));
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

	/** Sample request: {"tripId": ""}
	 * This function is used to show all driver for this trip with availability
	 * 
	 * @return type
	 * @throws Exception
	 */
	public function tripGetList()
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
			$jsonObj	 = CJSON::decode($data, false);
			$tripId		 = $jsonObj->tripId;
			$search_txt	 = $jsonObj->search_txt;

			$drvData	 = Drivers::getListByVendor(UserInfo::getEntityId(), $search_txt);
			$drvList	 = new Stub\common\Driver();
			$drvList->getList($drvData, $tripId);
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
		Logger::trace("<===Response===>" . json_encode($returnSet));
		return $this->renderJSON($returnSet);
	}

	public function getModificationMSG($diff, $user = false)
	{
		$msg = '';
		if (count($diff) > 0)
		{
			if ($diff ['drv_name'])
			{
				$msg .= ' Driver name: ' . $diff['drv_name'] . ',';
			}
			if ($diff ['drv_phone'])
			{
				$msg .= ' Driver Phone: ' . $diff['drv_phone'] . ',';
			}
			if ($diff ['drv_lic_number'])
			{
				$msg .= ' Licence Number: ' . $diff['drv_lic_number'] . ',';
			}
//            if ($diff['drv_voter_id'])
//            {
//                $msg .= ' VoterId: ' . $diff['drv_voter_id'] . ',';
//            }
//            if ($diff['drv_aadhaar_no'])
//            {
//                $msg .= ' Aadhaar No: ' . $diff['drv_aadhaar_no'] . ',';
//            }
//            if ($diff ['drv_pan_no'])
//            {
//                $msg .= ' Pan No: ' . $diff['drv_pan_no'] . ',';
//            }
			if ($diff['drv_issue_auth'])
			{
				$msg .= ' Issue Authorized by: ' . $diff['drv_issue_auth'] . ',';
			}
			if ($diff['drv_lic_exp_date'])
			{
				$msg .= ' Licence Exp Date: ' . $diff['drv_lic_exp_date'] . ',';
			}
			if ($diff['drv_address'])
			{
				$msg .= ' Address: ' . $diff['drv_address'] . ',';
			}
			if ($diff['drv_email'])
			{
				$msg .= ' Email: ' . $diff['drv_email'] . ',';
			}
			if ($diff['drv_dob_date'])
			{
				$msg .= ' Date of Birth: ' . $diff['drv_dob_date'] . ',';
			}
//            if ($diff['drv_doj'])
//            {
//                $msg .= ' Date of Joining: ' . $diff['drv_doj'] . ',';
//            }
			if ($diff['drv_state'])
			{
				$smodel	 = States::model()->findByPk($diff['drv_state']);
				$msg	 .= ' State: ' . $smodel->stt_name . ',';
			}
			if ($diff['drv_city'])
			{
				$cmodel	 = Cities::model()->findByPk($diff['drv_city']);
				$msg	 .= ' City: ' . $cmodel->cty_name . ',';
			}
			if ($diff['drv_zip'])
			{
				$msg .= ' Zip: ' . $diff['drv_zip'] . ',';
			}
//            if($diff['drv_approved'])
//            {
//                $approveStatus = ($diff['drv_approved']==1) ? 'Yes':'No';
//                $msg .=' Is Approved: ' . $approveStatus . ',';
//            }


			if ($diff['photoFile'] != '')
			{
				$msg .= ' : ' . $diff['photoFile'] . ',';
			}
			if ($diff['voterCardFile'] != '')
			{
				$msg .= ' : ' . $diff['voterCardFile'] . ',';
			}
			if ($diff['panCardFile'] != '')
			{
				$msg .= ' : ' . $diff['panCardFile'] . ',';
			}
			if ($diff['aadhaarCardFile'] != '')
			{
				$msg .= ' : ' . $diff['aadhaarCardFile'] . ',';
			}
			if ($diff['licenseFile'] != '')
			{
				$msg .= ' : ' . $diff['licenseFile'] . ',';
			}
			if ($diff['policeFile'] != '')
			{
				$msg .= '  : ' . $diff['policeFile'] . ',';
			}
			$msg = rtrim($msg, ',');
		}
		return $msg;
	}

//	public function saveDriverImage($image, $imagetmp, $driverId, $type)
//	{
//		try
//		{
//			$path = "";
//			if ($image != '')
//			{
//				$image	 = $driverId . "-" . $type . "-" . date('YmdHis') . "." . $image;
//				$dir	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
//				if (!is_dir($dir))
//				{
//					mkdir($dir);
//				}
//				$dirFolderName = $dir . DIRECTORY_SEPARATOR . 'drivers';
//				if (!is_dir($dirFolderName))
//				{
//					mkdir($dirFolderName);
//				}
//				$dirByVehicleId = $dirFolderName . DIRECTORY_SEPARATOR . $driverId;
//				if (!is_dir($dirByVehicleId))
//				{
//					mkdir($dirByVehicleId);
//				}
//				$file_path	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'drivers' . DIRECTORY_SEPARATOR . $driverId;
//				$file_name	 = basename($image);
//				$f			 = $file_path;
//				$file_path	 = $file_path . DIRECTORY_SEPARATOR . $file_name;
//				file_put_contents(PUBLIC_PATH . '/testFile.txt', $f . ' ==== ' . $file_name);
//				Yii::log("Image Path: \n\t Temp: " . $imagetmp . "\n\t Path: " . $f, CLogger::LEVEL_INFO, 'system.api.images');
//				if (Vehicles::model()->img_resize($imagetmp, 1200, $f, $file_name))
//				{
//					$path	 = substr($file_path, strlen(PUBLIC_PATH));
//					$result	 = ['path' => $path];
//				}
//			}
//		}
//		catch (Exception $e)
//		{
//			Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
//			Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
//			throw $e;
//		}
//		return $result;
//	}

	public function saveDriverImage($image, $imagetmp, $driverId, $cttid, $type)
	{

		try
		{
			$path	 = "";
			$DS		 = DIRECTORY_SEPARATOR;
			if ($image != '')
			{

				$path	 = Yii::app()->basePath;
				$image	 = $cttid . "-" . $type . "-" . date('YmdHis') . "-" . $image;

				$dir = $path . $DS . 'contact';

				if (!is_dir($dir))
				{
					mkdir($dir);
				}
				$dirFolderName = $dir . $DS . 'document';
				if (!is_dir($dirFolderName))
				{
					mkdir($dirFolderName);
				}
				$dirByContactId = $dirFolderName . $DS . $cttid;
				if (!is_dir($dirByContactId))
				{
					mkdir($dirByContactId);
				}
				$dirByType = $dirByContactId . $DS . $type;
				if (!is_dir($dirByType))
				{
					mkdir($dirByType);
				}

				$file_path	 = $dirByType . $DS . $image;
				$folder_path = $dirByType . $DS;

				$file_name = basename($image);

				$f			 = $file_path;
				//echo $f;exit;
				$file_path1	 = $file_path . DIRECTORY_SEPARATOR;

				file_put_contents($f, file_get_contents($imagetmp));  // parameter1=> target, parameter2 => source


				Yii::log("Image Path: \n\t Temp: " . $imagetmp . "\n\t Path: " . $f, CLogger::LEVEL_INFO, 'system.api.images');
				if (Vehicles::model()->img_resize($imagetmp, 1200, $folder_path, $file_name))
				{
					if ($type == 'agreement' || $type == 'digital_sign')
					{
						$path = substr($file_path, strlen(PUBLIC_PATH));
					}
					else
					{
						$path = substr($file_path, strlen($path));
					}
					$result = ['path' => $path];
				}
			}
		}
		catch (Exception $e)
		{
			ReturnSet::setException($e);
			throw $e;
		}
		return $result;
	}

	public function editDriverDetails($data)
	{
		$returnSet		 = new ReturnSet();
		$returnSet->setStatus(true);
		$model			 = Drivers::model()->findByPk($data['drv_id']);
		$contactModel	 = Contact::model()->findByPk($model->drv_contact_id);
		if ($model == '')
		{
			$model = new Drivers();
		}
		if ($contactModel == '')
		{
			$contactModel = new Contact();
		}
		$model->scenario						 = 'update';
		$model->drv_name						 = $data['drv_name'];
		$model->drv_dob_date					 = $data['drv_dob_date'];
		$model->drv_zip							 = $data['drv_zip'];
		$contactModel->ctt_address				 = $data['drv_address'];
		$contactModel->ctt_city					 = $data['drv_city'];
		$contactModel->ctt_state				 = $data['drv_state'];
		$contactModel->ctt_license_no			 = $data['drv_lic_number'];
		$contactModel->locale_license_exp_date	 = DateTimeFormat::DateToDatePicker($data['drv_lic_exp_date']);
		$contactModel->ctt_dl_issue_authority	 = $data['drv_issue_auth'];
		//ContactPhone::model()->updatePhoneByContactId($data['drv_phone'], $model->drv_contact_id);
		//ContactEmail::model()->updateEmailByContactId($data['drv_email'], $model->drv_contact_id);
		//ContactEmail::model()->updateEmailByContact($data['drv_email'], $model->drv_contact_id);

		$model->drvContact->isApp	 = true;
		$contactModel->addType		 = -1;

		$cModel		 = new Stub\common\ContactMedium();
		$emailModel	 = $cModel->getEmailModel($data['drv_email']);
		$phoneModel	 = $cModel->getPhoneModel($data['drv_phone'], $data["drv_country_code"]);

		$arrEmail	 = [];
		$arrPhone	 = [];
		array_push($arrEmail, $emailModel);
		array_push($arrPhone, $phoneModel);

		$contactModel->contactEmails = $arrEmail;
		$contactModel->contactPhones = $arrPhone;
		$returnSet					 = $contactModel->add();

		if (!$returnSet->getStatus())
		{
			return $returnSet;
		}

		$returnSet = $model->saveInfo($data);
		if (!$returnSet->isSuccess())
		{
			return $returnSet;
		}

		if ($data['vendor_id'] > 0)
		{
			$linked = VendorDriver::model()->checkAndSave(['driver' => $model->drv_id, 'vendor' => $data['vendor_id']]);
			if (!$linked)
			{

				$returnSet->setStatus(false);
				$returnSet->setErrors("Failed to link driver with vendor.");
				return $returnSet;
			}
		}
		return $returnSet;
	}

	/**
	 * This function is the replacement of the previous addContact
	 * @return type
	 */
	public function addContactNew()
	{
		$returnSet		 = new ReturnSet();
		$requestInstance = Yii::app()->request;
		try
		{
			$reciveData = json_decode($requestInstance->rawBody);
			if (empty($reciveData))
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
				goto skipAll;
			}

			$jsonMapper	 = new JsonMapper();
			$stub		 = new Stub\common\Person();
			$obj		 = $jsonMapper->map($reciveData, $stub);

			/** @var Stub\common\Person $obj */
			$contactModel	 = $obj->init();
			$returnSet		 = Drivers::addByContact($contactModel);
		}
		catch (Exception $ex)
		{
			$error		 = json_decode($ex->getMessage());
			$errorMsg	 = implode(',', $error->ctt_id);
			$returnSet->setStatus(false);
			$returnSet->setMessage($errorMsg);
		}
		skipAll:
		return $returnSet;
	}

	public function uploadPckImages()
	{
		$returnSet	 = new ReturnSet();
		$vendorId	 = UserInfo::getEntityId();
		try
		{
			CUploadedFile::getInstanceByName('img1');
			$wholeData1		 = Yii::app()->request->getParam('data');
			$wholeData		 = CJSON::decode($wholeData1, true);
			$vehicleId		 = $wholeData['cabId'];
			$uploadedFile	 = CUploadedFile::getInstanceByName('img1');
			$type			 = $wholeData['type'];
			$typeArr		 = array('front' => 8, 'back' => 9, 'left' => 10, 'right' => 11);
			$package_type	 = $typeArr[$type];
			$success		 = VehicleDocs::model()->uploadPackages($uploadedFile, $package_type, $vehicleId);
			if ($success)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Gozo Packages uploaded successfully");
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("No record found");
			}
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet->setMessage("No record found");
		}
		return $returnSet;
	}

	public function verifyLinkDriver()
	{
		$returnSet		 = new ReturnSet();
		$vendorId		 = UserInfo::getEntityId();
		$requestInstance = Yii::app()->request;
		$reciveData		 = json_decode($requestInstance->rawBody);
		if (empty($reciveData))
		{
			$returnSet->setStatus(false);
			$returnSet->setMessage("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			goto skipAll;
		}

		$jsonMapper	 = new JsonMapper();
		$stub		 = new Stub\common\Driver();
		$obj		 = $jsonMapper->map($reciveData, $stub);

		/** @var Stub\common\Driver $obj */
		$drvModel = $obj->setDLData();
		if ($drvModel->drv_id == "")
		{
			$returnSet->setStatus(false);
			$returnSet->setMessage("No driver linked", ReturnSet::ERROR_NO_RECORDS_FOUND);
			goto skipAll;
		}
		$returnSet = Drivers::verifyDlDates($drvModel, $vendorId);
		if ($returnSet == "")
		{
			$returnSet->setStatus(false);
			$returnSet->setMessage("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
		}
		skipAll:
		return $returnSet;
	}

	public function checkLinking()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		Logger::trace("<===Request===>" . $data);
		$isLinked	 = 0;
		try
		{
			if (empty($data))
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj = CJSON::decode($data, false);
			$drvId	 = $jsonObj->id;
			$vndId	 = UserInfo::getEntityId();

			$driverData = Drivers::model()->findByPk($drvId);
			if ($driverData->drv_ref_code != $drvId)
			{
				throw new Exception("Invalid driver", ReturnSet::ERROR_INVALID_DATA);
			}
			if (!$driverData->getDriverApproveStatus())
			{
				throw new Exception("Driver is not approved", ReturnSet::ERROR_INVALID_DATA);
			}
			$result = VendorDriver::getLinking($vndId, $drvId);
			if (empty($result))
			{
				$arr = ['driver' => $drvId, 'vendor' => $vndId];
				$res = VendorDriver::model()->checkAndSave($arr);
				if ($res)
				{
					$isLinked = 1;
				}
			}
			$drvData			 = new Stub\common\Driver();
			$drvData->setData($driverData);
			$drvData->isLinked	 = $isLinked;
			$response			 = Filter::removeNull($drvData);
			if (!$response)
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($e);
		}
		Logger::trace("<===Response===>" . json_encode($returnSet));
		return $returnSet;
	}

	/**
	 * This function is used to update track events
	 * return Data
	 */
	public function assignment()
	{
		$data		 = Yii::app()->request->rawBody;
		$jsonValue	 = CJSON::decode($data, false);
		$jsonObj	 = $jsonValue->data;

		/** @var Booking $model */
		$model		 = Booking::model()->findByBookingid($jsonObj->orderReferenceNumber);
		if(!$model || $model == null)
		{
			return false;
		}
		
		$operatorId	 = Operator::getOperatorId($model->bkg_booking_type);
		$objOperator = Operator::getInstance($operatorId);
		
		$typeAction	 = OperatorApiTracking::getActionType($jsonObj->eventType);
		
		switch ($typeAction)
		{
			case OperatorApiTracking::CAB_DRIVER_ALLOCATION:
				/* @var $objOperator Operator */
				$objOperator = $objOperator->assignChauffeur($model, $operatorId, $jsonObj);	
				break;
			case OperatorApiTracking::REASSIGN:
				/* @var $objOperator Operator */
				$objOperator = $objOperator->assignChauffeur($model, $operatorId, $jsonObj);
				break;
			case OperatorApiTracking::UNASSIGN_VENDOR:
				/* @var $objOperator Operator */
				$objOperator = $objOperator->unAssign($model->bkg_id, $operatorId, $jsonObj);
				break;
		}
		return $objOperator;
	}

}
