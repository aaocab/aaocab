<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class UsersController extends BaseController
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
			/* $ri	 = array('/validate', '/validateversion', '/citiesList', '/editinfo_bk', '/social_link',
			  '/signup', '/signin', '/verifyOTP', '/editinfo', '/edit', '/change_password','/registerDriver',
			  '/logout', '/forgotpass', '/newpassword', '/statusDetails','/social_login','/verifyOTP','/devicefcmtoken');
			 */
			$ri	 = array('/validate', '/validateversion', '/citiesList', '/social_link', '/signup', '/signin', 'statusDetails',
				'/verifyOTP', '/registerDriver', '/forgotpass', '/citiesListNew', '/fetchRatingNew', '/driverPrefLanguage',
				'/social_login', '/forgotpassNew', '/updateLastLocationNew', '/devicefcmtoken', '/fetchRating',
				'/validateSession', '/getAgentCommision', '/temporaryLogin', '/tempSessionValidation',
				'/validateUnsyncdata', '/social_link_v1', '/temporaryLogin_v1', '/social_login_v1', '/statusDetails_V1');

			foreach($ri as $value)
			{
				if(strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.post.citiesListNew.render', function () {
			return $this->renderJSON($this->citiesListNew()
			);
		});

		$this->onRest('req.post.fetchRatingNew.render', function () {
			return $this->renderJSON($this->fetchRatingNew()
			);
		});
		$this->onRest('req.post.driverPrefLanguage.render', function () {
			return $this->renderJSON($this->driverPrefLanguage()
			);
		});

		$this->onRest('req.post.updateLastLocationNew.render', function () {
			return $this->renderJSON($this->updateLastLocationNew()
			);
		});
		$this->onRest('req.post.forgotpassNew.render', function () {
			return $this->renderJSON($this->forgotpassNew()
			);
		});
		$this->onRest('req.post.social_link_v2.render', function () {
			return $this->renderJSON($this->social_linkV2());
		});

		$this->onRest('req.post.updateFcm.render', function () {
			return $this->renderJSON($this->updateFcm());
		});

		$this->onRest('req.get.signup.render', function () {
			$process_sync_data		 = Yii::app()->request->getParam('data');
			$data1					 = CJSON::decode($process_sync_data, true);
			$data					 = array_filter($data1);
			$data['drv_username']	 = $data['drv_email'];
			$result					 = $this->register($data);
			if($result['success'] == true)
			{
				$loginResult = $this->loginDriver($data);
				$data		 = ['login' => $loginResult, 'model' => JSONUtil::convertModelToArray($result['data'])];
			}
			else
			{
				$data = ['errors' => $result['errors']];
			}
			return $this->renderJSON(['type' => 'raw', 'data' => ['success' => $result['success'],] + $data,]);
		});

		$this->onRest('req.get.checkSignin.render', function () {
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($process_sync_data, true);
		});

		//Signin with username and password
		$this->onRest('req.post.signin.render', function () {
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($process_sync_data, true);

			$result = $this->loginDriver($data);

			$multi = false;
			if($result == true)
			{
				$success	 = true;
				$userId		 = UserInfo::getUserId();
				$rating		 = DriverStats::fetchRating($userId);
				$sessionId	 = Yii::app()->getSession()->getSessionId();
				$userModel	 = Drivers::model()->findByUserid($userId);
				$driver_id	 = UserInfo::getEntityId();
				$userData	 = Users::model()->findByPk($userId);

				if(is_numeric($data['username']))
				{
					$multi			 = false;
					$countPhoneNo	 = Drivers::model()->getCountByPhoneNo($driver_id);
					$count			 = $countPhoneNo['count'];
					if($count > 1)
					{
						$multi = true;
					}
					if($data['tripId'] != '')
					{
						$multi = false;
					}
				}
				$userName	 = $userModel->drv_name;
				$userPhone	 = $userData->usr_mobile;
				$userEmail	 = $userData->usr_email;
				$msg		 = "Login Successful";
			}
			else
			{
				$success = false;
				$msg	 = "Invalid Username/Password";
			}
			$response = ['success' => $success, 'userPhone' => $userPhone, 'message' => $msg, 'sessionId' => $sessionId, 'userId' => $userId, 'driverId' => $driver_id, 'userEmail' => $userEmail, 'userName' => $userName, 'multi' => $multi, 'rating' => $rating];
			Logger::trace("Response: " . json_encode($response));
			return CJSON::encode($response);
		});

		//new registration process for driver
		$this->onRest('req.post.registerDriver.render', function () {
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($process_sync_data, 'true');

			//check existance of user id
			$check_existence = Users::model()->checkUserExistance($data['phone_number'], $data['driving_licence'], $data['Booking_id']);
			//print_r($check_existence); exit;
			if($check_existence)
			{
				return $this->renderJSON([
							'type'	 => 'raw',
							'data'	 => array(
								'success'	 => $check_existence['result'],
								'message'	 => $check_existence['msg'],
								'driver_id'	 => $check_existence['driver_id'],
							)
				]);
			}
		});

		//Cities list against stateId
		$this->onRest('req.post.citiesList.render', function () {
			$success = false;
			$errors	 = 'Something went wrong';

			//$stateId	 = Yii::app()->request->getParam('state_id');
			//$flagData	 = Yii::app()->request->getParam('city_int_name');

			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($process_sync_data, 'true');

			$stateId	 = $data['state_id'];
			$flagData	 = $data['city_int_name'];

			$citiesList	 = Cities::model()->getCitiesListByIntCityName($stateId, $flagData);
			$model		 = CJSON::decode($citiesList);
			if($model != [])
			{
				$success = true;
				$error	 = null;
			}
			else
			{
				$success = false;
				$error	 = "No records found";
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'error'		 => $error,
					'data'		 => $model,
				)
			]);
		});

		// edit information show
		$this->onRest('req.post.editinfo.render', function () {
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);

			Logger::create("token :: " . $token);
			$errors = array();
			if($check)
			{
				$success = false;

				$userID		 = UserInfo::getUserId();
				$driverId	 = UserInfo::getEntityId();

				/* @var $model Drivers */
				$model		 = Drivers::model()->findByPk($driverId);
				//$modelDad	 = DriversAddDetails::model()->findByDriverId($driverId);
				$modelDad	 = Drivers::model()->findByDriverId($driverId);
				$resDriver	 = $model->getApiMappingByDriver();
				//$modelDad[0]['dad_bank_name'];
//				if ($model->drv_city != '' && $model->drv_city != null)
//				{
//					$modelCity = Cities::model()->findByPk($model->drv_city);
//				}
//				if ($model->drv_state != '' && $model->drv_state != null)
//				{
//					$modelState = States::model()->findByPk($model->drv_state);
//				}
				if($resDriver['drv_city'] != '' && $resDriver['drv_city'] != null)
				{
					$modelCity = Cities::model()->findByPk($resDriver['drv_city']);
				}
				if($resDriver['drv_state'] != '' && $resDriver['drv_state'] != null)
				{
					$modelState = States::model()->findByPk($resDriver['drv_state']);
				}
				if($model != '')
				{
					$success = true;

					$langArr	 = Contact::model()->language();
					$language	 = Contact::model()->getJSON($langArr);
					//print'<pre>';print_r($language);exit;
					$data		 = array(
						'drv_id'				 => $model->drv_id,
						'drv_name'				 => $model->drv_name,
						'drv_paytm_phone'		 => ($model->drv_paytm_phone != '' && $model->drv_paytm_phone != null) ? $model->drv_paytm_phone : '',
						'drv_email'				 => ($resDriver['drv_email'] != '' && $resDriver['drv_email'] != null) ? $resDriver['drv_email'] : '',
						'drv_phone'				 => ($resDriver['drv_phone'] != '' && $resDriver['drv_phone'] != null) ? $resDriver['drv_phone'] : '',
						'drv_lic_exp_date'		 => ($resDriver['drv_lic_exp_date'] != '' && $resDriver['drv_lic_exp_date'] != null) ? $resDriver['drv_lic_exp_date'] : '',
						'drv_dob_date'			 => ($model->drv_dob_date != '' && $model->drv_dob_date != null) ? $model->drv_dob_date : '',
						'drv_address'			 => ($resDriver['drv_address'] != '' && $resDriver['drv_address'] != null) ? $resDriver['drv_address'] : '',
						'drv_country_code'		 => ($resDriver['drv_country_code'] != '' && $resDriver['drv_country_code'] != null) ? $resDriver['drv_country_code'] : '',
						'drv_state'				 => ($resDriver['drv_state'] != '' && $resDriver['drv_state'] != null) ? $resDriver['drv_state'] : '',
						'drv_state_name'		 => ($resDriver['drv_state'] != '' && $resDriver['drv_state'] != null) ? $modelState->stt_name : '',
						'drv_city'				 => ($resDriver['drv_city'] != '' && $resDriver['drv_city'] != null) ? $resDriver['drv_city'] : '',
						'drv_city_name'			 => ($resDriver['drv_city'] != '' && $resDriver['drv_city'] != null) ? $modelCity->cty_name : '',
						'drv_zip'				 => ($model->drv_zip != '' && $model->drv_zip != null) ? $model->drv_zip : '',
						'drv_lic_number'		 => ($resDriver['drv_lic_number'] != '' && $resDriver['drv_lic_number'] != null) ? $resDriver['drv_lic_number'] : '',
						'drv_issue_auth'		 => ($resDriver['drv_issue_auth'] != '' && $resDriver['drv_issue_auth'] != null) ? $resDriver['drv_issue_auth'] : '',
						'drv_is_attached'		 => ($model->drv_is_attached != '' && $model->drv_is_attached != null) ? $model->drv_is_attached : '',
						'drv_photo_path'		 => ($resDriver['drv_photo_path'] != '' && $resDriver['drv_photo_path'] != null) ? $resDriver['drv_photo_path'] : '',
						'drv_known_language'	 => $model->drvContact->ctt_known_language,
						'dad_bank_name'			 => ($resDriver['dad_bank_name'] != '' && $resDriver['dad_bank_name'] != null) ? $resDriver['dad_bank_name'] : '',
						'dad_bank_branch'		 => ($resDriver['dad_bank_branch'] != '' && $resDriver['dad_bank_branch'] != null) ? $resDriver['dad_bank_branch'] : '',
						'dad_beneficiary_name'	 => ($resDriver['dad_beneficiary_name'] != '' && $resDriver['dad_beneficiary_name'] != null) ? $resDriver['dad_beneficiary_name'] : '',
						'dad_beneficiary_id'	 => ($resDriver['dad_beneficiary_id'] != '' && $resDriver['dad_beneficiary_id'] != null) ? $resDriver['dad_beneficiary_id'] : '',
						'dad_account_type'		 => ($resDriver['dad_account_type'] != '' && $resDriver['dad_account_type'] != null) ? $resDriver['dad_account_type'] : '',
						'dad_bank_ifsc'			 => ($resDriver['dad_bank_ifsc'] != '' && $resDriver['dad_bank_ifsc'] != null) ? $resDriver['dad_bank_ifsc'] : '',
						'dad_bank_account_no'	 => ($resDriver['dad_bank_account_no'] != '' && $resDriver['dad_bank_account_no'] != null) ? $resDriver['dad_bank_account_no'] : '',
					);

					$data['rating']	 = DriverStats::fetchRating($driverId);
					//$dataDocs		 = DriverDocs::model()->getDocsByDrvId($model->drv_id);
					$dataDocs		 = Document::model()->getDocsByDrvId($model->drv_id, $model->drv_contact_id);
					unset($dataDocs['drv_voter_id'], $dataDocs['drv_voter_back_id'], $dataDocs['drv_police_ver']);
					unset($dataDocs['drv_pan_id'], $dataDocs['drv_pan_back_id']);
					unset($dataDocs['drv_licence_id'], $dataDocs['drv_licence_back_id']);
					unset($dataDocs['drv_aadhaar_id'], $dataDocs['drv_aadhaar_back_id']);
					$newData		 = array_merge($data, $dataDocs);
				}
			}
			else
			{
				$errors	 = 'Unauthorised Driver';
				$success = false;
			}
			$language = CJSON::decode($language, true);
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $success,
					'errors'		 => $errors,
					'data'			 => $newData,
					'drv_language'	 => $language,
				)
			]);
		});

		//edit information
		$this->onRest('req.post.edit.render', function () {
			$process_sync_data	 = Yii::app()->request->getParam('data');
			Logger::info("<====Request===>:" . $process_sync_data);
			$token				 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check				 = Drivers::model()->authoriseDriver($token);
			if($check)
			{
				$success	 = false;
				$errors		 = [];
				$oldData	 = $newData	 = $oldDocsData = $newDocsData = false;

				$userID		 = UserInfo::getUserId();
				$driverId	 = UserInfo::getEntityId();
				$user_type	 = 3;
				$userInfo	 = UserInfo::getInstance();

				$wholeData1	 = Yii::app()->request->getParam('data');
				$wholeData	 = CJSON::decode($wholeData1);
				$driverPic	 = $wholeData['driverPic'];

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

				Logger::info("Files =>" . json_encode($_FILES));

				$data	 = CJSON::decode($wholeData['data']); //$wholeData['data'];
				$model	 = Drivers::model()->findById($driverId);

				$oldDocsData['photoFile']	 = $model->drvContact->ctt_profile_path;
				$newDocsData['photoFile']	 = $model->drvContact->ctt_profile_path;
				$oldData					 = $model->attributes;
				$dataSet					 = $model->getApiMappingByDriver();
				$oldData					 = array_merge($oldData, $dataSet);
				$newData					 = $data;
				$getOldDifference			 = array_diff_assoc($oldData, $newData);

				$user_info = UserInfo::getInstance();

				if($driverPic == 0)
				{
					$transaction = DBUtil::beginTransaction();
					try
					{

						$model		 = Drivers::model()->findByPk($driverId);
						$userModel	 = Users::model()->findByPk($userID);
						if($model == '')
						{
							$model = new Drivers();
						}

						$model->scenario = 'updateDriverApp';
						//$model->drv_name = ($data['drv_name'] != '') ? $data['drv_name'] : $drvName;

						if(isset($data['drv_lic_exp_date']) && $data['drv_lic_exp_date'] != '')
						{
							$model->drvContact->ctt_license_exp_date = $data['drv_lic_exp_date'];
						}
						if(isset($data['drv_dob_date']) && $data['drv_dob_date'] != '')
						{
							$model->drv_dob_date = $data['drv_dob_date'];
						}
						if(isset($data['drv_address']) && $data['drv_address'] != '')
						{
							$model->drvContact->ctt_address = $data['drv_address'];
						}
//						if (isset($data['drv_country_code']) && $data['drv_country_code'] != '')
//						{
//							$model->drv_country_code = $data['drv_country_code'];
//						}
						/** if (isset($data['drv_state']) && $data['drv_state'] != '')
						  {
						  $model->drvContact->ctt_state = $data['drv_state'];
						  }
						  if (isset($data['drv_city']) && $data['drv_city'] != '')
						  {
						  $model->drvContact->ctt_city = $data['drv_city'];
						  } */
						if(isset($data['drv_zip']) && $data['drv_zip'] != '')
						{
							$model->drv_zip = $data['drv_zip'];
						}
						if(isset($data['drv_lic_number']) && $data['drv_lic_number'] != '')
						{
							$model->drvContact->ctt_license_no = $data['drv_lic_number'];
						}
						if(isset($data['drv_paytm_phone']) && $data['drv_paytm_phone'] != '')
						{
							$model->drv_paytm_phone = $data['drv_paytm_phone'];
						}
						if(isset($data['dad_bank_name']) && $data['dad_bank_name'] != '')
						{
							$model->drvContact->ctt_bank_name = $data['dad_bank_name'];
						}
						if(isset($data['dad_bank_branch']) && $data['dad_bank_branch'] != '')
						{
							$model->drvContact->ctt_bank_branch = $data['dad_bank_branch'];
						}
						if(isset($data['dad_beneficiary_name']) && $data['dad_beneficiary_name'] != '')
						{
							$model->drvContact->ctt_beneficiary_name = $data['dad_beneficiary_name'];
						}

						if(isset($data['dad_beneficiary_id']) && $data['dad_beneficiary_id'] != '')
						{
							$model->drvContact->ctt_beneficiary_id = $data['dad_beneficiary_id'];
						}
						if(isset($data['dad_account_type']) && $data['dad_account_type'] != '')
						{
							$model->drvContact->ctt_account_type = $data['dad_account_type'];
						}
						if(isset($data['dad_bank_account_no']) && $data['dad_bank_account_no'] != '')
						{
							$model->drvContact->ctt_bank_account_no = $data['dad_bank_account_no'];
						}
						if(isset($data['dad_bank_ifsc']) && $data['dad_bank_ifsc'] != '')
						{
							$model->drvContact->ctt_bank_ifsc = $data['dad_bank_ifsc'];
						}
						if(isset($data['drv_known_language']) && $data['drv_known_language'] != '')
						{
							$model->drvContact->ctt_known_language = $data['drv_known_language'];
						}


						if($model->validate())
						{

							if($model->save())
							{
								$model->drvContact->isApp	 = true;
								$model->drvContact->addType	 = -1;
								$model->drvContact->update();

								$errors = [];

								$driverAddDetails = DriversAddDetails::model()->find('dad_drv_id=:driverid', ['driverid' => $driverId]);

								$oldDadValue				 = $driverAddDetails->attributes;
								$newDadValue				 = $data;
								$getOldDadDifference		 = array_diff_assoc($oldDadValue, $newDadValue);
								$modificationBankDetailsMsg	 = $this->getModificationBankDetailsMSG($getOldDadDifference, false);

								$modificationMsg = $this->getModificationMSG($getOldDifference, false);
								if($modificationMsg != '')
								{
									$changesForLog		 = "<br> Old Values: " . $modificationMsg;
									$changesForDadLog	 = "<br> Bank Details Old Values: " . $modificationBankDetailsMsg;
									$event_id			 = DriversLog::DRIVER_MODIFIED;
									$desc				 = "Driver modified | ";
									$desc				 .= $changesForLog;
									//$desc				 .= $changesForDadLog;
									DriversLog::model()->createLog($driverId, $desc, $userInfo, $event_id, false, false);
								}



								if($driverAddDetails != '')
								{
									$driverAddDetailsmodel = DriversAddDetails::model()->findByDriverId($driverId);
//									$driverAddDetailsmodel->dad_bank_name		 = $data['dad_bank_name'];
//									$driverAddDetailsmodel->dad_bank_branch		 = $data['dad_bank_branch'];
//									$driverAddDetailsmodel->dad_beneficiary_name = $data['dad_beneficiary_name'];
//									$driverAddDetailsmodel->dad_beneficiary_id	 = $data['dad_beneficiary_id'];
//									$driverAddDetailsmodel->dad_account_type	 = $data['dad_account_type'];
//									$driverAddDetailsmodel->dad_bank_account_no	 = $data['dad_bank_account_no'];
//									$driverAddDetailsmodel->dad_bank_ifsc		 = $data['dad_bank_ifsc'];
//									
//									
//									



									$driverAddDetailsmodel->save();
								}
								else
								{
									$driverAddDetailsmodel				 = new DriversAddDetails();
									$driverAddDetailsmodel->dad_drv_id	 = $driverId;
//									$driverAddDetailsmodel->dad_drv_id		 = $data['drv_id'];
//									$driverAddDetailsmodel->dad_bank_name		 = $data['dad_bank_name'];
//									$driverAddDetailsmodel->dad_bank_branch		 = $data['dad_bank_branch'];
//									$driverAddDetailsmodel->dad_beneficiary_name = $data['dad_beneficiary_name'];
//									$driverAddDetailsmodel->dad_beneficiary_id	 = $data['dad_beneficiary_id'];
//									$driverAddDetailsmodel->dad_account_type	 = $data['dad_account_type'];
//									$driverAddDetailsmodel->dad_bank_account_no	 = $data['dad_bank_account_no'];
//									$driverAddDetailsmodel->dad_bank_ifsc		 = $data['dad_bank_ifsc'];
									$driverAddDetailsmodel->dad_active	 = 1;
//									
//									
									if($driverAddDetailsmodel->validate())
									{
										$driverAddDetailsmodel->save();
									}
									else
									{
										$getErrors = json_encode($driverAddDetailsmodel->getErrors());
										throw new Exception("Bank Validation is failed.." . $getErrors);
									}
								}
								$success = DBUtil::commitTransaction($transaction);
							}
							else
							{
								$success	 = false;
								$getErrors	 = json_encode($model->getErrors());
								throw new Exception("Driver Validation is failed.." . $getErrors);
							}
						}
						else
						{

							$success	 = false;
							$getErrors	 = json_encode($model->getErrors());
							throw new Exception("Driver Validation is failed.." . $getErrors);
						}
					}
					catch(Exception $ex)
					{
						$msg		 = $ex->getMessage();
						DBUtil::rollbackTransaction($transaction);
						//throw new Exception($ex->getMessage());
						$errors[]	 = $getErrors;
						$event_id	 = DriversLog::DRIVER_MODIFIED;
						$desc		 = "Driver Modification Failed.";
						DriversLog::model()->createLog($driverId, $desc, $userInfo, $event_id, false, false);
					}
				}
				//else if ($driverPic == 1)
				//{
				try
				{
					if($photo != '')
					{
						Logger::info('Test photo checking after ===========>: ' . $photo, CLogger::LEVEL_TRACE);
						$type			 = 'profile';
						$result1		 = $this->saveDriverImage($photo, $photo_tmp, $driverId, $model->drv_contact_id, $type);
						$modelDocPhoto	 = new Document();
						$path1			 = str_replace("\\", "\\\\", $result1['path']);
						$qry1			 = "UPDATE contact SET ctt_profile_path = '" . $path1 . "' WHERE ctt_id = " . $model->drv_contact_id;
						$recorset1		 = Yii::app()->db->createCommand($qry1)->execute();

						if($recorset1)
						{
							$success					 = true;
							$errors						 = [];
							$newDocsData['photoFile']	 = $result1['path'];
							$getOldDifferenceDocs		 = array_diff_assoc($oldDocsData, $newDocsData);
							$changesForLog				 = "<br> Old Values: Driver Selfie " . $this->getModificationMSG($getOldDifferenceDocs, false);
							$event_id					 = DriversLog::DRIVER_MODIFIED;
							$desc						 = "Driver modified | ";
							$desc						 .= $changesForLog;
							DriversLog::model()->createLog($driverId, $desc, $userInfo, $event_id, false, false);
						}
					}
					if($voter_id != '')
					{
						$type = 'voterid';

//						$result2 = $this->saveDriverImage($voter_id, $voter_id_tmp, $driverId, $type);
//						$path2	 = str_replace("'\'", "\\\\", $result2['path']);
//						$modeld	 = new DriverDocs();
//						$success = $modeld->saveDocument($driverId, $path2, $userInfo, $type);
//                      $errors	 = [];
						if($model->drvContact->ctt_voter_doc_id != "")
						{
							$checkApprove = Document::model()->checkApproveDocById($model->drvContact->ctt_voter_doc_id);
						}
						if($checkApprove == 1)
						{
							$success	 = false;
							$errors[]	 = ("Voter Front Id already exists.\n\t\t");
						}
						else
						{
							$result1			 = $this->saveDriverImage($voter_id, $voter_id_tmp, $driverId, $model->drv_contact_id, $type);
							$modelDocVoterFront	 = new Document();
							$path1				 = str_replace("\\", "\\\\", $result1['path']);

							if(!empty($model->drvContact->ctt_voter_doc_id))
							{
								$qry2 = "UPDATE document SET doc_file_front_path = '" . $path1 . "', doc_status=0 WHERE doc_id = " . $model->drvContact->ctt_voter_doc_id;
								Yii::app()->db->createCommand($qry2)->execute();
							}
							else
							{
								$modelDocVoterFront->doc_file_front_path = $result1['path'];
								$modelDocVoterFront->doc_type			 = 2;
								$modelDocVoterFront->save();

								$qry2 = "UPDATE contact SET ctt_voter_doc_id = '" . $modelDocVoterFront->doc_id . "' WHERE ctt_id = " . $model->drv_contact_id;
								Yii::app()->db->createCommand($qry2)->execute();
							}

							$success = $model->saveDocument($driverId, $path1, $user_info, $type);

							if($success == true)
							{
								$errors = [];
								Logger::create('SUCCESS =====> : ' . "Voter Id : " . $path1, CLogger::LEVEL_INFO);
							}
							else
							{
								throw new Exception("Voter Id creation failed.\n\t\t" . json_encode($modelDocVoterFront->getErrors()));
							}
						}
					}
					if($aadhaar != '')
					{
						Logger::create('Test photo checking ===========>: ' . $aadhaar, CLogger::LEVEL_TRACE);
						$type = 'aadhar';

						if($model->drvContact->ctt_aadhar_doc_id != "")
						{
							$checkApprove = Document::model()->checkApproveDocById($model->drvContact->ctt_aadhar_doc_id);
						}
						if($checkApprove == 1)
						{
							$success = false;
							$errors	 = ("Aadhar Id already exists.\n\t\t");
						}
						else
						{
							$modelDocAadharFront = new Document();
							$result1			 = $this->saveDriverImage($aadhaar, $aadhaar_tmp, $driverId, $model->drv_contact_id, $type);
							$path1				 = str_replace("\\", "\\\\", $result1['path']);

							if(!empty($model->drvContact->ctt_aadhar_doc_id))
							{
								$qry2 = "UPDATE document SET doc_file_front_path = '" . $path1 . "' , doc_status=0 WHERE doc_id = " . $model->drvContact->ctt_aadhar_doc_id;
								Yii::app()->db->createCommand($qry2)->execute();
							}
							else
							{
								$modelDocAadharFront->doc_file_front_path	 = $result1['path'];
								$modelDocAadharFront->doc_type				 = 3;
								$modelDocAadharFront->save();
								$qry2										 = "UPDATE contact SET ctt_aadhar_doc_id = '" . $modelDocAadharFront->doc_id . "' WHERE ctt_id = " . $model->drv_contact_id;
								Yii::app()->db->createCommand($qry2)->execute();
							}
							$success = $model->saveDocument($driverId, $path1, $user_info, $type);
							if($success == true)
							{
								$errors = [];
								Logger::create('SUCCESS =====> : ' . "Aadhar Id : " . $path1, CLogger::LEVEL_INFO);
							}
							else
							{
								throw new Exception("Aadhar creation failed.\n\t\t" . json_encode($modelDocAadharFront->getErrors()));
							}
						}
					}
					if($pan != '')
					{
						$type = 'pan';
//						$result4 = $this->saveDriverImage($pan, $pan_tmp, $driverId, $type);
//						$path4	 = str_replace("'\'", "\\\\", $result4['path']);
//						$modeld	 = new DriverDocs();
//						$success = $modeld->saveDocument($driverId, $path4, $userInfo, $type);
//						$errors	 = [];
						if($model->drvContact->ctt_pan_doc_id != "")
						{
							$checkApprove = Document::model()->checkApproveDocById($model->drvContact->ctt_pan_doc_id);
						}
						if($checkApprove == 1)
						{
							$success	 = false;
							$errors[]	 = ("Pan already exists.\n\t\t");
						}
						else
						{
							$modelDocPanFront	 = new Document();
							$result1			 = $this->saveDriverImage($pan, $pan_tmp, $driverId, $model->drv_contact_id, $type);
							$path1				 = str_replace("\\", "\\\\", $result1['path']);
							if(!empty($model->drvContact->ctt_pan_doc_id))
							{
								$qry2	 = "UPDATE document SET doc_file_front_path = '" . $path1 . "' , doc_status=0 WHERE doc_id = " . $model->drvContact->ctt_pan_doc_id;
								$success = Yii::app()->db->createCommand($qry2)->execute();
							}
							else
							{
								$modelDocPanFront->doc_file_front_path	 = $result1['path'];
								$modelDocPanFront->doc_type				 = 4;
								//$modelDocPanFront->doc_active = 1;
								$modelDocPanFront->save();
								$qry2									 = "UPDATE contact SET ctt_pan_doc_id = '" . $modelDocPanFront->doc_id . "'  WHERE ctt_id = " . $model->drv_contact_id;
								$success								 = Yii::app()->db->createCommand($qry2)->execute();
							}

							if($success == true)
							{
								$errors = [];
								Logger::create('SUCCESS =====> : ' . "Pan Id : " . $path1, CLogger::LEVEL_INFO);
							}
							else
							{
								throw new Exception("Pan creation failed.\n\t\t" . json_encode($modelDocPanFront->getErrors()));
							}
						}
					}
					if($licence != '')
					{
						$type = 'license';
//						$result5 = $this->saveDriverImage($licence, $licence_tmp, $driverId, $type);
//						$path5	 = str_replace("'\'", "\\\\", $result5['path']);
//						$modeld	 = new DriverDocs();
//						$success = $modeld->saveDocument($driverId, $path5, $userInfo, $type);
//						$errors	 = [];
						if($model->drvContact->ctt_license_doc_id != "")
						{
							$checkApprove = Document::model()->checkApproveDocById($model->drvContact->ctt_license_doc_id);
						}
						if($checkApprove == 1)
						{
							$success	 = false;
							$errors[]	 = ("License Front already exists.\n\t\t");
						}
						else
						{
							$result1				 = $this->saveDriverImage($licence, $licence_tmp, $driverId, $model->drv_contact_id, $type);
							$modelDocLicenseFront	 = new Document();
							$path1					 = str_replace("\\", "\\\\", $result1['path']);

							if(!empty($model->drvContact->ctt_license_doc_id))
							{
								$qry2 = "UPDATE document SET doc_file_front_path = '" . $path1 . "' , doc_status=0 WHERE doc_id = " . $model->drvContact->ctt_license_doc_id;
								Yii::app()->db->createCommand($qry2)->execute();
							}
							else
							{
								$modelDocLicenseFront->doc_file_front_path	 = $result1['path'];
								$modelDocLicenseFront->doc_type				 = 5;
								$modelDocLicenseFront->save();
								$qry2										 = "UPDATE contact SET ctt_license_doc_id = '" . $modelDocLicenseFront->doc_id . "' WHERE ctt_id = " . $model->drv_contact_id;
								Yii::app()->db->createCommand($qry2)->execute();
							}
							$success = $model->saveDocument($driverId, $path1, $user_info, $type);

							if($success == true)
							{
								$errors = [];
								Logger::create('SUCCESS =====> : ' . "License Front Id : " . $path1, CLogger::LEVEL_INFO);
							}
							else
							{
								throw new Exception("License creation failed.\n\t\t" . json_encode($modelDocLicenseFront->getErrors()));
							}
						}
					}
					if($verification != '')
					{
						$type = 'policever';
//						$result6 = $this->saveDriverImage($verification, $verification_tmp, $driverId, $type);
//						$path6	 = str_replace("'\'", "\\\\", $result6['path']);
//						$modeld	 = new DriverDocs();
//						$success = $modeld->saveDocument($driverId, $path6, $userInfo, $type);
//						$errors	 = [];
						if($model->drvContact->ctt_police_doc_id != "")
						{
							$checkApprove = Document::model()->checkApproveDocById($model->drvContact->ctt_police_doc_id);
						}
						if($checkApprove == 1)
						{
							$success	 = false;
							$errors[]	 = ("Police verification already exists.\n\t\t");
						}
						else
						{
							$result1				 = $this->saveDriverImage($verification, $verification_tmp, $driverId, $model->drv_contact_id, $type);
							$modelDocPoliceVerify	 = new Document();
							$path1					 = str_replace("\\", "\\\\", $result1['path']);

							if(!empty($model->drvContact->ctt_police_doc_id))
							{
								$qry2 = "UPDATE document SET doc_file_front_path = '" . $path1 . "', doc_status=0 WHERE doc_id = " . $model->drvContact->ctt_police_doc_id;

								Yii::app()->db->createCommand($qry2)->execute();
							}
							else
							{
								$modelDocPoliceVerify->doc_file_front_path	 = $result1['path'];
								$modelDocPoliceVerify->doc_type				 = 7;
								//$modelDocMemo->doc_active = 1;
								$modelDocPoliceVerify->save();
								$qry2										 = "UPDATE contact SET ctt_police_doc_id = '" . $modelDocPoliceVerify->doc_id . "' WHERE ctt_id = " . $model->drv_contact_id;
								Yii::app()->db->createCommand($qry2)->execute();
							}
							$success = $model->saveDocument($driverId, $path1, $user_info, $type);

							if($success == true)
							{
								$errors = [];
								Logger::create('SUCCESS =====> : ' . "Police Verification : " . $path1, CLogger::LEVEL_INFO);
							}
							else
							{
								throw new Exception("Police Verification creation failed.\n\t\t" . json_encode($modelDocPoliceVerify->getErrors()));
							}
						}
					}
				}
				catch(Exception $e)
				{
					Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
					Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
					throw $e;
				}
				//}

				$driverModel = Drivers::model()->findByPk($driverId);
				$driverInfo	 = $driverModel->getApiMappingByDriver();
				if($driverInfo['drv_city'] != '' && $driverInfo['drv_city'] != null)
				{
					$modelCity = Cities::model()->findByPk($driverInfo['drv_city']);
				}
				if($driverInfo['drv_state'] != '' && $driverInfo['drv_state'] != null)
				{
					$modelState = States::model()->findByPk($driverInfo['drv_state']);
				}
				if($driverModel != '')
				{
					$modelDad		 = DriversAddDetails::model()->findByDriverId($driverModel->drv_id);
					$data			 = array(
						'drv_id'				 => $driverModel->drv_id,
						'drv_name'				 => $driverModel->drv_name,
						'drv_paytm_phone'		 => ($driverModel->drv_paytm_phone != '' && $driverModel->drv_paytm_phone != null) ? $driverModel->drv_paytm_phone : '',
						'drv_email'				 => ($driverInfo['drv_email'] != '' && $driverInfo['drv_email'] != null) ? $driverInfo['drv_email'] : '',
						'drv_phone'				 => ($driverInfo['drv_phone'] != '' && $driverInfo['drv_phone'] != null) ? $driverInfo['drv_phone'] : '',
						'drv_lic_exp_date'		 => ($driverInfo['drv_lic_exp_date'] != '' && $driverInfo['drv_lic_exp_date'] != null) ? $driverInfo['drv_lic_exp_date'] : '',
						'drv_dob_date'			 => ($driverModel->drv_dob_date != '' && $driverModel->drv_dob_date != null) ? $driverModel->drv_dob_date : '',
						'drv_address'			 => ($driverInfo['drv_address'] != '' && $driverInfo['drv_address'] != null) ? $driverInfo['drv_address'] : '',
						'drv_country_code'		 => ($driverInfo['drv_country_code'] != '' && $driverInfo['drv_country_code'] != null) ? $driverInfo['drv_country_code'] : '',
						'drv_state'				 => ($driverInfo['drv_state'] != '' && $driverInfo['drv_state'] != null) ? $driverInfo['drv_state'] : '',
						'drv_state_name'		 => ($driverInfo['drv_state'] != '' && $driverInfo['drv_state'] != null) ? $modelState->stt_name : '',
						'drv_city'				 => ($driverInfo['drv_city'] != '' && $driverInfo['drv_city'] != null) ? $driverInfo['drv_city'] : '',
						'drv_city_name'			 => ($driverInfo['drv_city'] != '' && $driverInfo['drv_city'] != null) ? $modelCity->cty_name : '',
						'drv_zip'				 => ($driverModel->drv_zip != '' && $driverModel->drv_zip != null) ? $driverModel->drv_zip : '',
						'drv_lic_number'		 => ($driverInfo['drv_lic_number'] != '' && $driverInfo['drv_lic_number'] != null) ? $driverInfo['drv_lic_number'] : '',
						'drv_issue_auth'		 => ($driverInfo['drv_issue_auth'] != '' && $driverInfo['drv_issue_auth'] != null) ? $driverInfo['drv_issue_auth'] : '',
						'drv_is_attached'		 => ($driverModel->drv_is_attached != '' && $driverModel->drv_is_attached != null) ? $driverModel->drv_is_attached : '',
						'drv_photo_path'		 => ($driverInfo['drv_photo_path'] != '' && $driverInfo['drv_photo_path'] != null) ? $driverInfo['drv_photo_path'] : '',
						'drv_known_language'	 => ($driverInfo['drv_known_language'] != '' && $driverInfo['drv_known_language'] != null) ? $driverInfo['drv_known_language'] : '',
//						'dad_bank_name'			 => ($modelDad->dad_bank_name != '' && $modelDad->dad_bank_name != null) ? $modelDad->dad_bank_name : '',
//						'dad_bank_branch'		 => ($modelDad->dad_bank_branch != '' && $modelDad->dad_bank_branch != null) ? $modelDad->dad_bank_branch : '',
//						'dad_beneficiary_name'	 => ($modelDad->dad_beneficiary_name != '' && $modelDad->dad_beneficiary_name != null) ? $modelDad->dad_beneficiary_name : '',
//						'dad_beneficiary_id'	 => ($modelDad->dad_beneficiary_id != '' && $modelDad->dad_beneficiary_id != null) ? $modelDad->dad_beneficiary_id : '',
//						'dad_account_type'		 => ($modelDad->dad_account_type != '' && $modelDad->dad_account_type != null) ? $modelDad->dad_account_type : '',
//						'dad_bank_ifsc'			 => ($modelDad->dad_bank_ifsc != '' && $modelDad->dad_bank_ifsc != null) ? $modelDad->dad_bank_ifsc : '',
//						'dad_bank_account_no'	 => ($modelDad->dad_bank_account_no != '' && $modelDad->dad_bank_account_no != null) ? $modelDad->dad_bank_account_no : '',
						'dad_bank_name'			 => ($driverInfo['dad_bank_name'] != '' && $driverInfo['dad_bank_name'] != null) ? $driverInfo['dad_bank_name'] : '',
						'dad_bank_branch'		 => ($driverInfo['dad_bank_branch'] != '' && $driverInfo['dad_bank_branch'] != null) ? $driverInfo['dad_bank_branch'] : '',
						'dad_beneficiary_name'	 => ($driverInfo['dad_beneficiary_name'] != '' && $driverInfo['dad_beneficiary_name'] != null) ? $driverInfo['dad_beneficiary_name'] : '',
						'dad_beneficiary_id'	 => ($driverInfo['dad_beneficiary_id'] != '' && $driverInfo['dad_beneficiary_id'] != null) ? $driverInfo['dad_beneficiary_id'] : '',
						'dad_account_type'		 => ($driverInfo['dad_account_type'] != '' && $driverInfo['dad_account_type'] != null) ? $driverInfo['dad_account_type'] : '',
						'dad_bank_ifsc'			 => ($driverInfo['dad_bank_ifsc'] != '' && $driverInfo['dad_bank_ifsc'] != null) ? $driverInfo['dad_bank_ifsc'] : '',
						'dad_bank_account_no'	 => ($driverInfo['dad_bank_account_no'] != '' && $driverInfo['dad_bank_account_no'] != null) ? $driverInfo['dad_bank_account_no'] : '',
					);
					$data['rating']	 = DriverStats::fetchRating($driverModel->drv_id);
					//$dataDocs		 = DriverDocs::model()->getDocsByDrvId($driverModel->drv_id);
					$dataDocs		 = Document::model()->getDocsByDrvId($driverModel->drv_id, $driverModel->drv_contact_id);
					unset($dataDocs['drv_voter_id'], $dataDocs['drv_voter_back_id'], $dataDocs['drv_police_ver']);
					unset($dataDocs['drv_pan_id'], $dataDocs['drv_pan_back_id']);
					unset($dataDocs['drv_licence_id'], $dataDocs['drv_licence_back_id']);
					unset($dataDocs['drv_aadhaar_id'], $dataDocs['drv_aadhaar_back_id']);

					$newData = array_merge($data, $dataDocs);
					//Logger::create('Return Data ===========>: ' . json_encode($newData) . ' Success ===>' . $success, CLogger::LEVEL_INFO);
				}
				///////
			}
			else
			{
				$errors	 = 'Unauthorised Driver';
				$success = false;
			}
			Logger::info('<====Response===>: ' . json_encode(['success' => $success, 'errors' => $errors, 'data' => $newData]));
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => $newData,
				),
			]);
		});

		//Change Passowrd
		$this->onRest('req.post.change_password.render', function () {
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);
			if($check)
			{

				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$data				 = array_filter($data1);

				$result	 = $this->changePassword($data);
				$success = $result['status'];
				$message = $result['message'];
			}
			else
			{
				$message = 'Unauthorised Driver';
				$success = false;
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'messages'	 => $message
				),
			]);
		});

		//Logout
		$this->onRest('req.get.logout.render', function () {

			$token = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);

			$applogout	 = AppTokens::model()->find('apt_token_id = :token', array('token' => $token));
			$userTypes	 = '3,5';
			$logout		 = false;
			$rows		 = AppTokens::logoutUserTypeOnDevice($applogout->apt_device_uuid, $userTypes);
			if($rows > 0)
			{
				$logout					 = true;
				Logger::create("LOGOUT TOKEN  =>" . $token, CLogger::LEVEL_TRACE);
				$applogout->apt_status	 = 0;
				$applogout->apt_logout	 = new CDbExpression('NOW()');
				$logout					 = $applogout->save();
				Yii::app()->user->logout();
			}
			if($logout)
			{
				$data = [
					'success'	 => true,
					'message'	 => "User logged out successfully"];
			}
			else
			{
				$data = [
					'success'	 => false,
					'errors'	 => ['error' => ['Error in logout']]];
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $data
			]);
		});

		//Validate Session
		$this->onRest('req.post.validateSession.render', function () {

			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			
			$check	 = Drivers::model()->authoriseDriver($token);
			$drvId	 = 0;
			
			if($check)
			{
				$drvId				 = UserInfo::getEntityId();
				$result['message']	 = 'Driver Authorised';
				$result['success']	 = true;
			}
			else
			{
				Logger::info("Current Session ID:( " . $token . " )");
				Logger::info("Validation Session Status: " . $check);
				$result['message']	 = 'Unauthorised Driver';
				$result['success']	 = false;
			}

			$resultSet = array(
				'success'	 => $result['success'],
				'message'	 => $result['message'] . " driverId: " . $drvId,
			);
			if($drvId)
			{
				$islinked = Drivers::model()->checkSocialLinking($drvId);

				$resultSet['data']	 = ['isLinked' => (int) $islinked];
				$drvModel			 = Drivers::model()->findByPk($drvId);
				$logLevels			 = '';
				if($drvModel->drv_log_levels != '')
				{
					$logLevels			 = json_decode($drvModel->drv_log_levels, true);
					$resultSet['data']	 = ['isLinked' => (int) $islinked, 'logLevels' => $logLevels];
				}
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $resultSet
			]);
		});
		$this->onRest('req.post.getAgentCommision.render', function () {
			$returnSet	 = new ReturnSet();
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check		 = Vendors::model()->authoriseVendor($token);
			if($check)
			{
				$returnSet->setStatus(true);
				$agtCommission	 = Yii::app()->params['vendorDriverSalesCommission'];
				$data			 = ['agt_com' => $agtCommission];
				$returnSet->setData($data);
			}
			return $this->renderJSON([
				'data' => $returnSet]);
		});

		//Validate app
		$this->onRest('req.get.validate.render', function () {

			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);
			if($check)
			{

				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$data				 = array_filter($data1);
				$activeVersion		 = Config::get("Version.Android.driver"); //Yii::app()->params['versionCheck']['driver'];
				//echo $id					 = Yii::app()->user->id;
				//$id				 = UserInfo::getEntityId(); // driver id
				$id					 = UserInfo::getUserId();  // user id

				$result			 = $this->getValidationApp($data, $id, $activeVersion);
				$englishYoutue	 = Yii::app()->params['driverEnglishYouTubeURL'];
				$hindiYoutube	 = Yii::app()->params['driverHindiYouTubeURL'];
				Yii::log("validate session " . json_encode($result), CLogger::LEVEL_INFO, 'system.api.validate');
			}
			else
			{
				$result['message']	 = 'Unauthorised Driver';
				$result['success']	 = false;
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $result['success'],
					'message'	 => $result['message'],
					'active'	 => $result['active'],
					'version'	 => $activeVersion,
				)
			]);
		});

		//Validate version
		$this->onRest('req.get.validateversion.render', function () {
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$isDco = 1;
			Logger::create("Request for validateversion : " . $process_sync_data);
			$data1				 = CJSON::decode($process_sync_data, true);
			$data				 = array_filter($data1);
			$token				 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);

			$appToken = AppTokens::model()->getByToken($token);
			if($token)
			{
				$appToken = AppTokens::model()->find('apt_token_id = :token AND apt_status = 1', array('token' => $token));
				if($appToken)
				{
					$appToken->apt_apk_version = $data['apt_apk_version'];
					$appToken->save();

					$driverId	 = UserInfo::getEntityId();
					$contactId	 = ContactProfile::getByDrvId($driverId);

					$entityType	 = UserInfo::TYPE_VENDOR;
					$vendorArr	 = ContactProfile:: getEntityById($contactId, $entityType);
					/*$isDco = 1;
					$vendorId	 = $vendorArr['id'];
					if($vendorId < 1)
					{
						//$isDco = Drivers::checkForceDCO($driverId);
						$isDco = 1;
					}*/
				}
			}
			$activeVersion = Config::get("Version.Android.driver"); //Yii::app()->params['versionCheck']['driver'];
			if(version_compare($data['apt_apk_version'], $activeVersion) < 0)
			{
				$active			 = 0;
				$success		 = false;
				$msg			 = "Invalid Version";
				$sessioncheck	 = Yii::app()->params['driverappsessioncheck'];
			}
			else
			{
				$active			 = 1;
				$success		 = true;
				$msg			 = "Valid Version";
				$sessioncheck	 = '';
				$englishYoutue	 = Yii::app()->params['driverEnglishYouTubeURL'];
				$hindiYoutube	 = Yii::app()->params['driverHindiYouTubeURL'];
			}

			$languageArray		 = [];
			$languageArray[0]	 = ['lang' => 'hindi', 'url' => $hindiYoutube];
			$languageArray[1]	 = ['lang' => 'english', 'url' => $englishYoutue];
			$result				 = array('active' => $active, 'success' => $success, 'message' => $msg, 'sessioncheck' => $sessioncheck);

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $result['success'],
					'message'		 => $result['message'],
					'active'		 => $result['active'],
					'sessioncheck'	 => $result['sessioncheck'],
					'version'		 => $activeVersion,
					'training'		 => $languageArray,
					'forceDcoApp'	 => $isDco)
			]);
		});

		//forgot password				
		$this->onRest('req.post.forgotpass.render', function () {
			/*
			  $phone		 = Yii::app()->request->getParam('phone');
			  $code		 = Yii::app()->request->getParam('code');
			  $newPassword = Yii::app()->request->getParam('new_password');

			 */

			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($process_sync_data, true);
			$phone				 = $data['phone'];
			$code				 = $data['code'];
			$newPassword		 = $data['new_password'];

			$newPassword = md5($newPassword);
			$status		 = false;
			$arr		 = [];
			$result		 = Drivers::model()->forgotPassword($phone, $code, $newPassword, $arr, $status);
			$success	 = $result[0]['success'];
			$message	 = $result[0]['message'];

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'message'	 => $message
				)
			]);
		});

		//new password				
		$this->onRest('req.get.newpassword.render', function () {
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);
			if($check)
			{

				$userId		 = UserInfo::getUserId();
				$newPassword = Yii::app()->request->getParam('new_password');
				$success	 = false;
				$model		 = Users::model()->findByPk($userId);

				if($model != '')
				{
					$model->usr_password = md5($newPassword);
					if($model->update())
					{
						$success = true;
						$message = "password changed successfully.";
					}
					else
					{
						$success = false;
						$message = "error occurred while changing password.";
					}
				}
				else
				{
					$success = false;
					$message = "error occurred while changing password.";
				}
			}
			else
			{
				$message = 'Unauthorised Driver';
				$success = false;
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => ['success' => $success, 'message' => $message]
			]);
		});

		/*
		 * Social Linking for drivers
		 */
		$this->onRest('req.post.social_link.render', function () {
			/* Old
			  http://localhost:82/api/driver/users/social_link?drvid=77293&provider=Google
			 * &data={"displayName":"gozo test","email":"testgozo90@gmail.com","expirationTime":1550847039,"familyName":"test","givenName":"gozo","grantedScopes":"[https://www.googleapis.com/auth/userinfo.email, https://www.googleapis.com/auth/userinfo.profile, profile, email, openid]","id":"101394939299427630688","obfuscatedIdentifier":"0719644844EB4175453B379AD4CEA4BC"}
			 * &devicedata={"nameValuePairs":{"apk_version":"1.22.81120","os_version":23,"device_id":"7c650f2a67a0ccb","device_info":"Xiaomi Redmi 3S","apt_device_token":"NA"}}
			 */

			/* New
			 * {"drvid":77293,
			  "provider":"Google",
			  "data":{"displayName":"gozo test","email":"testgozo90@gmail.com","expirationTime":1550847039,"familyName":"test","givenName":"gozo","grantedScopes":"[https://www.googleapis.com/auth/userinfo.email, https://www.googleapis.com/auth/userinfo.profile, profile, email, openid]","id":"101394939299427630688","obfuscatedIdentifier":"0719644844EB4175453B379AD4CEA4BC"},
			  "devicedata":{"nameValuePairs":{"apk_version":"1.22.81120","os_version":23,"device_id":"7c650f2a67a0ccb","device_info":"Xiaomi Redmi 3S","apt_device_token":"NA"}}}
			 */

			$is_logged_in	 = 0;
			$full_data1		 = Yii::app()->request->getParam('data', '');
			$full_data		 = CJSON::decode($full_data1, true);

			$driver_id			 = $full_data['drvid'];
			$drvModel			 = Drivers::model()->findByPk($driver_id);
			$process_sync_data2	 = $full_data['data'];
			$userData_2			 = CJSON::decode($process_sync_data2, true);
			$provider			 = $full_data['provider'];
			$deviceData1		 = $full_data['devicedata'];

			if(count($drvModel) > 0)
			{
				$userId	 = $drvModel->drv_user_id;
				$drvCode = $drvModel->drv_code;
				$result	 = Users::model()->linkAppDriver($userId, $provider, $process_sync_data2);
				if($result['success'] == 1 && $result['driv_user_id'] > 0)
				{
					$driverDetails = Drivers::model()->getAllDriverIdsByUserId($result['driv_user_id']);
					if(count($driverDetails) > 0)
					{
						$success = false;
						$msg	 = "Already linked with other user.";
					}
					else
					{
						$drvModel->drv_user_id	 = $result['driv_user_id'];
						//$drvModel->drv_email	 = $result['email'];
						$drvModel->save();
						$contactEmail			 = ContactEmail::model()->findEmailIdByEmail($result['email']);
						if(count($contactEmail) == 0)
						{
							$email		 = $result['email'];
							$sql		 = "INSERT INTO contact_email (`eml_contact_id`,`eml_email_address`,`eml_is_verified`,`eml_is_primary`,`eml_active`) VALUES ('$drvModel->drv_contact_id','$email',1,0,1)";
							$resultRow	 = Yii::app()->db->createCommand($sql)->execute();
						}

						$is_logged_in		 = 1;
						$data_after_login	 = Drivers::model()->socialDriverlogin($provider, $process_sync_data2, $deviceData1);
						Logger::create("loggedin_data =>" . $data_after_login, CLogger::LEVEL_TRACE);
					}
				}
				else
				{
					$success = false;
					$msg	 = $result['msg'];
				}
			}
			else
			{
				$success = false;
				$msg	 = 'DriverID not found.';
				goto result_error;
			}


			result_error:
			if($is_logged_in == 1)
			{
				return $data_after_login;
			}
			else
			{
				$response = ['success' => $success, 'userID' => (int) $userId, 'drv_code' => $drvCode, 'msg' => $msg];
				return CJSON::encode($response);
			}
		});
		$this->onRest('req.post.social_link_v1.render', function () {
			return $this->renderJSON($this->social_link());
		});

		/*
		 * Social Login for driver
		 */
		$this->onRest('req.post.social_login_v1.render', function () {
			return $this->renderJSON($this->socialLogin());
		});

		/*
		 * @deprecated
		 * New Service: social_login_v1
		 */
		$this->onRest('req.post.social_login.render', function () {

			$full_data1		 = Yii::app()->request->getParam('data', ''); //'{"data":"{\"displayName\":\"gozo test\",\"email\":\"testgozo90@gmail.com\",\"familyName\":\"test\",\"givenName\":\"gozo\",\"grantedScopes\":\"[https:\/\/www.googleapis.com\/auth\/userinfo.profile, https:\/\/www.googleapis.com\/auth\/userinfo.email, openid, profile, email]\",\"id\":\"101394939299427630688\"}","devicedata":"{\"nameValuePairs\":{\"apk_version\":\"2.2.90412\",\"os_version\":26,\"device_id\":\"84e73fe2820f6c2f\",\"device_info\":\"HUAWEI BND-AL10\",\"apt_device_token\":\"d5PdgPOpQNI:APA91bFStbJnWR6v4TyFyMMoScyE9d4FcqLdDkKSxFQJrWnay-FmpdIkcau_xCYbz01DbgqAu3ZyjcJ48qYFYhzTv1JlkAACqRerfDevTkxl4R8EJjMjq87Ailuu9tl-dJq4YsXEHlXT\"}}","provider":"Google"}';
			$full_data		 = CJSON::decode($full_data1, true);
			$provider		 = $full_data['provider'];
			$processSyncdata = $full_data['data'];
			$deviceData1	 = $full_data['devicedata'];
			$result			 = Drivers::model()->socialDriverlogin($provider, $processSyncdata, $deviceData1);
			Logger::trace("Response: " . json_encode($result));
			return $result;
		});

		$this->onRest('req.post.driver_pref_language.render', function () {

			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);

			if($check)
			{
				$langArr			 = Contact::model()->language();
				$language			 = Contact::model()->getJSON($langArr);
				$process_sync_data	 = Yii::app()->request->getParam('data');
				Logger::info("<===Request===>" . $process_sync_data);
				$data				 = CJSON::decode($process_sync_data, true);
				$drv_pref_language	 = $data['drv_pref_language'];
				$userID				 = UserInfo::getUserId();
				$driverId			 = UserInfo::getEntityId();
				$model				 = Drivers::model()->findByPk($driverId);
				if($drv_pref_language != "")
				{
					$model->drvContact->ctt_preferred_language	 = $drv_pref_language;
					$model->drvContact->save();
					$success									 = true;
					$errors										 = [];
				}
				else
				{
					$success = false;
					$errors	 = "Please select preferred language";
				}
			}
			else
			{
				$errors	 = 'Unauthorised Driver';
				$success = false;
			}

			Logger::info("<===Response===>" . json_encode(['type' => 'raw', 'data' => array('success' => $success, 'errors' => $errors, 'drv_pref_language' => json_decode($language),)]));

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'			 => $success,
					'errors'			 => $errors,
					'drv_pref_language'	 => json_decode($language),
				)
			]);
		});

		/**
		 * Deprecated status details service 
		 * new service  : statusDetails_V1
		 */
		$this->onRest('req.get.statusDetails.render', function () {
			$token = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);

			$check = Drivers::model()->authoriseDriver($token);

			if($check)
			{
				$drvId						 = UserInfo::getEntityId();
				$errors						 = '';
				$result						 = $this->getDetails($drvId);
				$success					 = true;
				$drvSosStatus				 = 0;
				$isSocialLinkingMandatory	 = true;
				$drvSocialLinking			 = Drivers::model()->checkSocialLinking($drvId);
				if($drvSocialLinking)
				{
					$isSocialLinkingMandatory = false;
				}
				$drvModel	 = Drivers::model()->findByPk($drvId);
				$listDocs	 = Document::model()->findAllByDrvId($drvModel->drv_contact_id);
				$drvDocs	 = Document::model()->getUnapprovedDoc($drvId, $drvModel->drv_contact_id);
				Logger::create('19 DriverId Docs' . $drvDocs, CLogger::LEVEL_TRACE);
				if($listDocs[0]['doc_police_status'] == null || $listDocs[0]['doc_police_status'] == 2)
				{
					$isPoliceVerification	 = 0;
					$message				 = "Your Police verification certification needs to be submitted. Please upload the document from your profile within Gozo Driver app";
				}
				else
				{
					$isPoliceVerification = 1;
				}
				$resDriver	 = $drvModel->getApiMappingByDriver();
				$photo		 = ($resDriver['drv_photo_path'] != '') ? 1 : 0;
				if((isset($drvDocs['count']) && $drvDocs['count'] > 0) || ($resDriver['drv_photo_path'] == '' || $resDriver['drv_photo_path'] == null))
				{
					$result1[] = ['entity_type' => '2', 'entity_id' => $drvModel->drv_id, 'drv_name' => $drvModel->drv_name, 'drv_phone' => $resDriver['drv_phone'], 'drv_photo' => $resDriver['drv_photo_path'], 'vhc_number' => null, 'vht_model' => null, 'car_type' => null, 'vhc_insurance_exp_date' => null, 'vhc_reg_exp_date' => null, 'docs' => $drvDocs,];
				}
				$server_datetime	 = DBUtil::getCurrentTime();
				$server_timestamp	 = ((strtotime($server_datetime)) * 1000);
			}
			else
			{
				$errors	 = 'Unauthorised Driver';
				$success = false;
			}


			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => [
					'success'					 => $success,
					'drv_social_link'			 => $drvSocialLinking,
					'linking_required'			 => $isSocialLinkingMandatory,
					'errors'					 => $errors,
					'server_timestamp'			 => $server_timestamp,
					'photo'						 => $photo,
					'docs'						 => $drvDocs,
					'police_verification_status' => ['police_verification' => (int) $isPoliceVerification, 'message' => $message],
					'result'					 => $result]
			]);
		});

		//verify otp for driver
		$this->onRest('req.post.verifyOTP.render', function () {
			/* $driver_id	 = Yii::app()->request->getParam('drvid');	
			  $otp	     = Yii::app()->request->getParam('otp'); */

			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($process_sync_data, true);
			$driver_id			 = $data['drvid'];
			$otp				 = $data['otp'];

			if(trim($driver_id) != "" && trim($otp) != "")
			{
				$result = Drivers::model()->sendAndVerifyOTP($driver_id, $otp);
			}
			else
			{
				if(trim($driver_id) == "")
				{
					$result['message'] = 'Driver ID cannot be blank.';
				}
				if(trim($otp) == "")
				{
					$result['message'] = 'OTP cannot be blank.';
				}
				$result['success']	 = false;
				$result['driver_id'] = $driver_id;
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $result['success'],
					'msg'		 => $result['message'],
					'driver_id'	 => $result['driver_id'],
				)
			]);
		});

		$this->onRest('req.post.devicefcmtoken.render', function () {
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$returnSet			 = new ReturnSet();
			$data1				 = CJSON::decode($process_sync_data, true);
			$data				 = array_filter($data1);
			Logger::info("data =====>" . $data);
			$token				 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			try
			{
				if($token == "")
				{
					$returnSet->setStatus(false);
					$returnSet->setMessage("Invalid token: ");
					$returnSet->setErrors(['error' => "Invalid token:"], ReturnSet::ERROR_VALIDATION);
					goto skip;
				}

				$appToken = AppTokens::model()->find('apt_token_id = :token', array('token' => $token));
				//$appToken = AppTokens::model()->find('apt_token_id = $token AND apt_status = 1');
				if(!empty($appToken))
				{
					$appToken->apt_device_token	 = $data['apt_device_token'];
					$appToken->apt_device_uuid	 = $data['apt_device_uuid'];
					$appToken->scenario			 = 'fcm';
					$appToken->apt_user_type	 = 5;
					$appToken->apt_status		 = 1;

					$success = $appToken->save();
					$returnSet->setStatus($success);
				}
				else
				{
					$returnSet->setStatus(false);
					$returnSet->setMessage("Invalid token: ");
					$returnSet->setErrors(['error' => "Invalid token:"], ReturnSet::ERROR_VALIDATION);
				}
				skip:
			}
			catch(Exception $e)
			{
				Logger::exception($e);
				$returnSet = $returnSet->setException($e);
			}
			Yii::log("device token : " . $data['apt_device_token'], CLogger::LEVEL_INFO);

			return $this->renderJSON(['data' => $returnSet]);
			//return $this->renderJSON(['type' => 'raw', 'data' => ['success' => $success, 'errors' => $appToken->getErrors()]]);
		});

		$this->onRest('req.get.fetchRating.render', function () {
			$driverId = UserInfo::getEntityId();
			Logger::create('32 driverId ' . $driverId, CLogger::LEVEL_TRACE);
			if($driverId > 0)
			{
				$drvRating = DriverStats::fetchRating($driverId);
			}
			return $this->renderJSON(['type' => 'raw', 'data' => ['rating' => $drvRating]]);
		});

		$this->onRest('req.post.updateLastLocation.render', function () {
			$token					 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			//$check	 = Drivers::model()->authoriseDriver($token);			
			$process_sync_data		 = Yii::app()->request->getParam('data');
			Logger::info("Update Location Request =====>" . $process_sync_data);
			$data					 = CJSON::decode($process_sync_data, true);
			$details->coordinates	 = new \Stub\common\Coordinates($data['lat'], $data['lon']);
			$bkgId					 = $data['bkg_id'];
			$bModel					 = Booking::model()->findByPk($bkgId);
			$sosStatus				 = ($bModel->bkgTrack->bkg_drv_sos_sms_trigger == 2) ? true : false;
			if((!empty($details->coordinates->latitude)) && (!empty($details->coordinates->longitude)))
			{
				$result		 = DriverStats::model()->updateLastLocation($data);
				$userInfo	 = UserInfo::getInstance();
				Location::addLocation($data, $userInfo);
				AppTokens::updateLastLocation($details, $token);
				BookingTrack::updateLastLocation($details, $bkgId);
			}
			Logger::info("Update Location Response =====>" . json_encode(['data' => ['success' => $result, 'sosStatus' => $sosStatus]]));
			return $this->renderJSON(['type' => 'raw', 'data' => ['success' => $result, 'sosStatus' => $sosStatus]]);
		});

		$this->onRest('req.post.sosSmsTriggerDriver.render', function () {
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($process_sync_data, true);
			$token				 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check				 = Drivers::model()->authoriseDriver($token);
			if($check)
			{
				Logger::create("PROCESS DATA  sosSMSTrigger ::" . $process_sync_data, CLogger::LEVEL_TRACE);
				$userInfo	 = UserInfo::getInstance();
				$userId		 = $userInfo->getUserId();
				$driverId	 = $userInfo->getEntityId();
				Logger::create("USER ID :: " . $userId, CLogger::LEVEL_TRACE);
				Logger::create("Driver ID :: " . $driverId, CLogger::LEVEL_TRACE);
				$bModel		 = Booking::model()->findByPk($data['bkg_id']);
				$deviceId	 = $bModel->bkgTrack->bkg_sos_device_id;
				$transaction = DBUtil::beginTransaction();
				try
				{
					if($data != '' && $data['bkg_id'] != null)
					{
						if($deviceId == null && $data['deviceId'] != null)
						{
							$sosContactList	 = Drivers::model()->sendNotificationDriverSosToContact($userId, $driverId, $data);
							$success		 = ($sosContactList['sosSmsTrigger'] == 2) ? true : false;
							if($success)
							{
								$isSosSmsTrigger = DriverStats::model()->saveDriverSosLocation($sosContactList['sosSmsTrigger'], $data, $driverId);
								$message		 = "S.O.S. Turned on successfully";
							}
						}
						else
						{
							$success		 = false;
							$isSosSmsTrigger = 1;
							$message		 = "Unable to start S.O.S.";
						}

						DBUtil::commitTransaction($transaction);
					}
					else
					{
						$success		 = false;
						$isSosSmsTrigger = 1;
						$message		 = "Unable to start S.O.S.";
					}
				}
				catch(Exception $ex)
				{
					DBUtil::rollbackTransaction($transaction);
					$message = $ex->getMessage();
					Logger::create("Errors.\n\t\t" . $message, CLogger::LEVEL_ERROR);
				}
			}
			else
			{
				$success		 = false;
				$isSosSmsTrigger = 1;
				$message		 = "Unauthorized Driver";
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'			 => $success,
					'sos_sms_trigger'	 => $isSosSmsTrigger,
					'message'			 => $message,
				)
			]);
		});

		$this->onRest('req.post.updateDriverSosTrigger.render', function () {
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($process_sync_data, true);
			Logger::create("data ::" . $process_sync_data, CLogger::LEVEL_TRACE);
			$token				 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check				 = Drivers::model()->authoriseDriver($token);
			if($check)
			{
				$model = BookingTrack::model()->getByBkgId($data['bkg_id']);
				if($model->bkg_drv_sos_sms_trigger == 1)
				{
					$success = true;
					$message = 'The S.O.S. has been already turn off by GozoTeam';
				}
				else
				{
					$userInfo	 = UserInfo::model();
					$userId		 = $userInfo->getUserId();
					$driverId	 = $userInfo->getEntityId();
					$result		 = DriverStats::model()->updateDriverSosTriggerFlag($data, $userId, $driverId);
					$success	 = $result;
					$message	 = ($success) ? 'S.O.S. turned off' : 'Unable to turn off S.O.S.';
				}
			}
			else
			{
				$success = false;
				$message = 'Unauthorised Driver';
			}
			Logger::create("S.O.S. Message ::" . $message, CLogger::LEVEL_TRACE);
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'message'	 => $message,
				)
			]);
		});

		$this->onRest('req.post.temporaryLogin.render', function () {
			$returnSet = new ReturnSet();

			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($process_sync_data, true);

			$driverId	 = $data['drvid'];
			$bkgId		 = $data['bkg_id'];
			$deviceInfo	 = $data['devicedata'];
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$drvModel	 = Drivers::model()->mergedDriverId($driverId);
			$deviceData	 = CJSON::decode($deviceInfo, true);
			$ipAddress	 = \Filter::getUserIP();
			$sessionId	 = Yii::app()->getSession()->getSessionId();
			//	$msg		 = "LOGIN--BKG=====" . $bkgId . "====DRV====" . $driverId . "====ENTITY===" . $drvModel->drv_id . "====SESSION===" . $sessionId;
			///	\Sentry\captureMessage($msg, null);
			try
			{
				Logger::create("driver::users::temporaryLogin sessionId: " . $sessionId . " drvModel: " . $drvModel->drv_id, CLogger::LEVEL_INFO);
				if(trim($sessionId) != '' && $sessionId != null && $deviceData != '' && $drvModel != '' && $bkgId)
				{
					$userList = Drivers::model()->getContactByDrvId($drvModel->drv_id);
					if($userList['UserId'] || $userList['status'] == 1)
					{
						$userId = $userList['UserId'];
					}
					if($userId)
					{
						Logger::create("driver::users::temporaryLogin userId: " . $userId, CLogger::LEVEL_INFO);
						// $userId = $drvModel->drv_user_id; 
						/* @var $webUser GWebUser */
						$webUser	 = Yii::app()->user;
						$webUser->setUserType(UserInfo::TYPE_DRIVER);
						$appToken	 = AppTokens::model()->findAll('apt_device_uuid=:device and apt_user_type=:type', array('device' => $deviceData['nameValuePairs']['device_id'], 'type' => 5));
						foreach($appToken as $app)
						{
							if(count($app) > 0)
							{
								$app->apt_status = 0;
								$app->update();
							}
						}
						$appTokenModel					 = new AppTokens();
						$appTokenModel->apt_user_id		 = $userId;
						$appTokenModel->apt_entity_id	 = $drvModel->drv_id;
						$appTokenModel->apt_token_id	 = $sessionId;
						$appTokenModel->apt_device		 = $deviceData['nameValuePairs']['device_info'];
						$appTokenModel->apt_last_login	 = new CDbExpression('NOW()');
						$appTokenModel->apt_device_uuid	 = $deviceData['nameValuePairs']['device_id'];
						$appTokenModel->apt_user_type	 = 5;
						$appTokenModel->apt_apk_version	 = $deviceData['nameValuePairs']['apk_version'];
						$appTokenModel->apt_ip_address	 = $ipAddress;
						$appTokenModel->apt_os_version	 = $deviceData['nameValuePairs']['os_version'];
						$appTokenModel->apt_device_token = $deviceData['nameValuePairs']['apt_device_token'];
						$result							 = $appTokenModel->insert();
						Yii::log('Driver Login ' . json_encode($appTokenModel), CLogger::LEVEL_INFO);
						Logger::create("driver::users::temporaryLogin appToken result: " . $result, CLogger::LEVEL_INFO);
						if($result == true)
						{
							$rating		 = DriverStats::fetchRating($drvModel->drv_id);
							$userName	 = $drvModel->drv_name;
							$phnNo		 = ContactPhone::model()->getContactPhoneById($drvModel->drv_contact_id);
							$emlId		 = ContactEmail::model()->getContactEmailById($drvModel->drv_contact_id);
							$drvCode	 = $drvModel->drv_code;
							$userPhone	 = $phnNo;
							$userEmail	 = $emlId;
							$drvPrefLang = $drvModel->drvContact->ctt_preferred_language;
							$msg		 = "Login Successful";
							$returnSet->setStatus(true);
							$data		 = ['userPhone' => $userPhone, 'message' => $msg, 'sessionId' => $sessionId, 'userId' => $userId, 'driverId' => $drvModel->drv_id, 'userEmail' => $userEmail, 'userName' => $userName, 'rating' => $rating, 'drv_code' => $drvCode, 'driverPrefLang' => $drvPrefLang];
							$returnSet->setData($data);
						}
					}
					else
					{
						$returnSet->setStatus(false);
						$returnSet->setMessage($userList['message']);
						$returnSet->setErrors(['error' => $userList['message']], ReturnSet::ERROR_VALIDATION);
					}
				}
				else
				{
					$returnSet->setStatus(false);
					$returnSet->setMessage("Unable to temporary login");
					$returnSet->setErrors(['error' => "Unable to temporary login"], ReturnSet::ERROR_VALIDATION);
				}
			}
			catch(Exception $e)
			{
				Logger::exception($e);
				$returnSet = $returnSet->getError($e);
			}
			Logger::create("driver::users::temporaryLogin  Returnset: " . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
			return $this->renderJSON([
				'data' => $returnSet,
			]);
		});
		$this->onRest('req.post.temporaryLogin_v1.render', function () {
			return $this->renderJSON($this->temporaryLogin());
		});

		$this->onRest('req.post.tempSessionValidation.render', function () {
			$returnSet			 = new ReturnSet();
			$token				 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check				 = Drivers::model()->authoriseDriver($token);
			$process_sync_data	 = Yii::app()->request->getParam('data');
			Logger::create("Request data =>" . $process_sync_data, CLogger::LEVEL_TRACE);
			$data				 = CJSON::decode($process_sync_data, true);
			$bkgId				 = $data['bkg_id'];
			$bkgModel			 = Booking::model()->findByPk($bkgId);
			try
			{
				if($check && $bkgId)
				{
					if($bkgModel->bkgTrack->bkg_ride_complete == 0)
					{
						$data = ['message' => 'Driver Authorised'];
						$returnSet->setStatus(true);
						$returnSet->setData($data);
					}
					else
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors(['error' => "Unauthorised Driver"]);
					}
				}
				else
				{
					$returnSet->setStatus(false);
					$returnSet->setErrors(['error' => "Invalid Data"]);
				}
			}
			catch(Exception $e)
			{
				Logger::exception($e);
				$returnSet = $returnSet->getError($e);
			}

			return $this->renderJSON([
				'data' => $returnSet,
			]);
		});

		$this->onRest("req.post.validateUnsyncdata.render", function () {
			return $this->renderJSON($this->validateUnsyncdata());
		});

		/**
		 * Status Details New Service
		 */
		$this->onRest('req.get.statusDetails_V1.render', function () {
			return $this->renderJSON($this->statusDetails_V1());
		});

		/**
		 * Login Verification By OTP
		 */
		$this->onRest('req.get.loginByOtp.render', function () {
			return $this->renderJSON($this->loginByOtp());
		});

		/**
		 *  Validate Login By OTP
		 */
		$this->onRest('req.post.validateLoginByOtp.render', function () {
			return $this->renderJSON($this->validateLoginByOtp());
		});
	}

	/**
	 * This function is used for driver social linking
	 * @return type
	 * @throws Exception
	 */
//	public function socialLink()
//	{
//		$returnSet	 = new ReturnSet();
//		$transaction = DBUtil::beginTransaction();
//		try
//		{
//			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
//			$data		 = Yii::app()->request->rawBody;
//
//			/* @var $usrInfo UserInfo */
//			$usrInfo = UserInfo::getInstance();
//			if (empty($data))
//			{
//				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
//			}
//
//			$appToken			 = AppTokens::model()->getByToken($token);
//			if (!$appToken)
//			{
//				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
//			}
//			$appToken->apt_last_login	 = new CDbExpression('NOW()');
//			$appToken->save();
//
//			$vendorModel = Drivers::model()->resetScope()->find('drv_id=:id AND drv_active >0', ['id' => $appToken->apt_entity_id]);
//			
//			$jsonMapper	 = new JsonMapper();
//			$jsonObj	 = CJSON::decode($data, false);
//
//			/** @var \Stub\vendor\AuthRequest $obj */
//			$obj		 = $jsonMapper->map($jsonObj, new \Stub\common\Auth());
//			$model = Users::login($obj->getSocialModel(), true);
//
//			/** @var CWebUser $webUser */
//			$webUser			 = Yii::app()->user;
//			$obj->device->authId = $token;
//			$userModel			 = Users::model()->findByPk($model->user_id);
//			$authResponse		 = new \Stub\consumer\Session();
//			$authResponse->setModelData($token, $userModel);
//
//			$returnSet			 = Contact::linkContact($authResponse, $vendorModel->vnd_id, UserInfo::TYPE_DRIVER, $jsonObj->provider);
//
//			DBUtil::commitTransaction($transaction);
//		}
//		catch (Exception $ex)
//		{
//			$returnSet->setStatus(false);
//			$returnSet = $returnSet->setException($ex);
//			DBUtil::rollbackTransaction($transaction);
//			Logger::create("Errors : " . json_encode($returnSet), CLogger::LEVEL_INFO);
//		}
//		return $returnSet;
//	}


	public function register($data)
	{
		$model					 = new Drivers('signup');
		$model->attributes		 = $data;
		$model->drv_tnc_datetime = new CDbExpression('NOW()');
		if($model->drv_password1 != "")
		{
			$model->drv_password = md5($model->drv_password1);
		}
		$tmodel				 = Terms::model()->getText(3);
		$model->drv_tnc_id	 = $tmodel->tnc_id;
		$success			 = false;
		$errors				 = [];
		if($model->validate())
		{
			try
			{
				$reg = $model->save();
				if(!$reg)
				{
					$errors = $model->getErrors();
				}
				else
				{
					$success = true;
				}
			}
			catch(Exception $e)
			{
				$errors = [$e->getMessage()];
			}
		}
		else
		{
			$errors = $model->getErrors();
		}
		$result = array('success'	 => $success,
			'errors'	 => $errors,
			'data'		 => $model
		);
		return $result;
	}

	public function loginDriver($data)
	{
		$ipAddress	 = \Filter::getUserIP();
		$username	 = $data['username'];
		$data1		 = Users::model()->findByPhoneorEmail($username);

		if(count($data1) > 1)
		{
			$email	 = $data1['usr_email'];
			$phone	 = $data1['usr_mobile'];

			if($email != "" && $phone != "")
			{
				$identity		 = new UserIdentity($email, md5($data['password']));
				$count			 = 1;
				//$phoneNoMatched	 = 0;	
				$phoneNoMatched	 = 1;
				if($phoneNoMatched == 1 || !is_numeric($phone))
				{

					if($identity->authenticate())
					{

						$userID		 = $identity->getId();
						$userModel	 = Drivers::model()->findByUserid($userID);

						if(count($userModel) > 0)
						{
							$identity->setEntityID($userModel->drv_id);
							$driver_id = $identity->entityId;

							/* @var $webUser GWebUser */
							$webUser = Yii::app()->user;
							$webUser->login($identity);
							$webUser->setUserType(UserInfo::TYPE_DRIVER);

							$sessionId	 = Yii::app()->getSession()->getSessionId();
							$appToken	 = AppTokens::model()->findAll('apt_device_uuid=:device and apt_user_type=:type', array('device' => $data['device_id'], 'type' => 5));

							foreach($appToken as $app)
							{
								if(count($app) > 0)
								{
									$app->apt_status = 0;
									$app->update();
								}
							}
							$appTokenModel					 = new AppTokens();
							$appTokenModel->apt_user_id		 = $userID;
							$appTokenModel->apt_entity_id	 = $driver_id;
							$appTokenModel->apt_token_id	 = Yii::app()->getSession()->getSessionId();
							$appTokenModel->apt_device		 = $data['device_info'];
							$appTokenModel->apt_last_login	 = new CDbExpression('NOW()');
							$appTokenModel->apt_device_uuid	 = $data['device_id'];
							$appTokenModel->apt_user_type	 = 5;
							$appTokenModel->apt_apk_version	 = $data['apk_version'];
							$appTokenModel->apt_ip_address	 = $ipAddress;
							$appTokenModel->apt_os_version	 = $data['os_version'];
							$appTokenModel->apt_device_token = $data['apt_device_token'];
							$appTokenModel->insert();
							Yii::log('driver login ' . json_encode($appTokenModel), CLogger::LEVEL_INFO);
							$success						 = true;
						}
						else
						{
							$success = false;
							$msg	 = "Invalid Username/Password";
						}
					}
					else
					{
						$success = false;
						$msg	 = "Invalid Username/Password";
					}
				}
				else
				{
					$success = false;
					$msg	 = "Trip Id Not Match";
				}
			}
			else
			{
				$success = false;
				$msg	 = "Invalid Username/Password";
			}
		}
		else
		{
			$success = false;
			$msg	 = "Invalid Username/Password";
		}

		return $success;
	}

	public function changePassword($data)
	{
		$userId		 = UserInfo::getUserId();
		$model		 = Users::model()->findByPk($userId);
		$oldPassword = $data['old_password'];
		$newPassword = $data['new_password'];
		$rePassword	 = $data['repeat_password'];
		$success	 = false;
		//$model->scenario		 = 'changepassword';



		$result = Users::model()->changePassword($userId, $oldPassword, $newPassword, $rePassword);
		return $result;
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
//				$dirByDriverId = $dirFolderName . DIRECTORY_SEPARATOR . $driverId;
//				if (!is_dir($dirByDriverId))
//				{
//					mkdir($dirByDriverId);
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
			$path = "";
			if($image != '')
			{
				//echo $type;exit;
				$path	 = Yii::app()->basePath;
				$image	 = $cttid . "-" . $type . "-" . date('YmdHis') . "-" . $image;
				//$image = $vendorId . "-" . $type . "-" . date('YmdHis') . "." . $image;

				$dir = $path . DIRECTORY_SEPARATOR . 'contact';
				//$dir = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'contact';

				if(!is_dir($dir))
				{
					mkdir($dir);
				}
				$dirFolderName = $dir . DIRECTORY_SEPARATOR . 'document';
				if(!is_dir($dirFolderName))
				{
					mkdir($dirFolderName);
				}
				$dirByContactId = $dirFolderName . DIRECTORY_SEPARATOR . $cttid;
				if(!is_dir($dirByContactId))
				{
					mkdir($dirByContactId);
				}
				$dirByType = $dirByContactId . DIRECTORY_SEPARATOR . $type;
				if(!is_dir($dirByType))
				{
					mkdir($dirByType);
				}

				$file_path	 = $dirByType . DIRECTORY_SEPARATOR . $image;
				$folder_path = $dirByType . DIRECTORY_SEPARATOR;

				//$file_path	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . $vendorId;

				$file_name = basename($image);

				$f			 = $file_path;
				//echo $f;exit;
				$file_path1	 = $file_path . DIRECTORY_SEPARATOR;

				//$u = file_put_contents(PUBLIC_PATH . '/testFile.txt', $f . ' ==== ' . $file_name);
				//file_put_contents($imagetmp, $f . ' ==== ' . $file_name);
				////////////////
				file_put_contents($f, file_get_contents($imagetmp));  // parameter1=> target, parameter2 => source
				///////////////
				//echo $f; exit();

				Yii::log("Image Path: \n\t Temp: " . $imagetmp . "\n\t Path: " . $f, CLogger::LEVEL_INFO, 'system.api.images');
				if(Vehicles::model()->img_resize($imagetmp, 1200, $folder_path, $file_name))
				{
					if($type == 'agreement' || $type == 'digital_sign')
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
		catch(Exception $e)
		{
			Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
			Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
			throw $e;
		}
		return $result;
	}

	public function getValidationApp($data, $id, $activeVersion)
	{
		if($activeVersion > $data['apt_apk_version'])
		{
			$active	 = 1;
			$success = false;
			$msg	 = "Invalid Version";
		}
		else
		{
			if($id != '')
			{
				$validate	 = AppTokens::model()->getAppValidations($data, $id);
				$active		 = 2;
				$success	 = true;
				$msg		 = "Validation Done";
			}
			else
			{
				$active	 = 3;
				$success = false;
				$msg	 = "Invalid User";
			}
		}
		$result = array('active' => $active, 'success' => $success, 'message' => $msg);
		return $result;
	}

	public function getModificationMSG($diff, $user = false)
	{
		$msg = '';
		if(count($diff) > 0)
		{
			if($diff ['drv_name'])
			{
				$msg .= ' Driver name: ' . $diff['drv_name'] . ',';
			}
			if($diff ['drv_phone'])
			{
				$msg .= ' Driver Phone: ' . $diff['drv_phone'] . ',';
			}
			if($diff ['drv_lic_number'])
			{
				$msg .= ' Licence Number: ' . $diff['drv_lic_number'] . ',';
			}
			if($diff['drv_issue_auth'])
			{
				$msg .= ' Issue Authorized by: ' . $diff['drv_issue_auth'] . ',';
			}
			if($diff['drv_lic_exp_date'])
			{
				$msg .= ' Licence Exp Date: ' . $diff['drv_lic_exp_date'] . ',';
			}
			if($diff['drv_address'])
			{
				$msg .= ' Address: ' . $diff['drv_address'] . ',';
			}
			if($diff['drv_email'])
			{
				$msg .= ' Email: ' . $diff['drv_email'] . ',';
			}
			if($diff['drv_dob_date'])
			{
				$msg .= ' Date of Birth: ' . $diff['drv_dob_date'] . ',';
			}
			if($diff['drv_state'])
			{
				$smodel	 = States::model()->findByPk($diff['drv_state']);
				$msg	 .= ' State: ' . $smodel->stt_name . ',';
			}
			if($diff['drv_city'])
			{
				$cmodel	 = Cities::model()->findByPk($diff['drv_city']);
				$msg	 .= ' City: ' . $cmodel->cty_name . ',';
			}
			if($diff['drv_zip'])
			{
				$msg .= ' Zip: ' . $diff['drv_zip'] . ',';
			}
			if($diff['photoFile'] != '')
			{
				$msg .= ' : ' . $diff['photoFile'] . ',';
			}
			if($diff['voterCardFile'] != '')
			{
				$msg .= ' : ' . $diff['voterCardFile'] . ',';
			}
			if($diff['panCardFile'] != '')
			{
				$msg .= ' : ' . $diff['panCardFile'] . ',';
			}
			if($diff['aadhaarCardFile'] != '')
			{
				$msg .= ' : ' . $diff['aadhaarCardFile'] . ',';
			}
			if($diff['licenseFile'] != '')
			{
				$msg .= ' : ' . $diff['licenseFile'] . ',';
			}
			if($diff['policeFile'] != '')
			{
				$msg .= '  : ' . $diff['policeFile'] . ',';
			}

			if($diff ['dad_bank_name'])
			{
				$msg .= ' Bank name: ' . $diff['dad_bank_name'] . ',';
			}
			if($diff ['dad_bank_branch'])
			{
				$msg .= ' Bank Branch: ' . $diff['dad_bank_branch'] . ',';
			}
			if($diff ['dad_beneficiary_name'])
			{
				$msg .= ' Beneficiary Name: ' . $diff['dad_beneficiary_name'] . ',';
			}
			if($diff['dad_beneficiary_id'])
			{
				$msg .= ' Beneficiary Id: ' . $diff['dad_beneficiary_id'] . ',';
			}
			if($diff['dad_account_type'])
			{
				$msg .= ' Account Type: ' . $diff['dad_account_type'] . ',';
			}
			if($diff['dad_bank_ifsc'])
			{
				$msg .= ' Bank IFSC: ' . $diff['dad_bank_ifsc'] . ',';
			}
			if($diff['dad_bank_account_no'])
			{
				$msg .= ' Bank A/C No.: ' . $diff['dad_bank_account_no'] . ',';
			}

			$msg = rtrim($msg, ',');
		}
		return $msg;
	}

	public function getModificationBankDetailsMSG($diff, $user = false)
	{
		$msg = '';
		if(count($diff) > 0)
		{
			if($diff ['dad_bank_name'])
			{
				$msg .= ' Bank name: ' . $diff['dad_bank_name'] . ',';
			}
			if($diff ['dad_bank_branch'])
			{
				$msg .= ' Bank Branch: ' . $diff['dad_bank_branch'] . ',';
			}
			if($diff ['dad_beneficiary_name'])
			{
				$msg .= ' Beneficiary Name: ' . $diff['dad_beneficiary_name'] . ',';
			}
			if($diff['dad_beneficiary_id'])
			{
				$msg .= ' Beneficiary Id: ' . $diff['dad_beneficiary_id'] . ',';
			}
			if($diff['dad_account_type'])
			{
				$msg .= ' Account Type: ' . $diff['dad_account_type'] . ',';
			}
			if($diff['dad_bank_ifsc'])
			{
				$msg .= ' Bank IFSC: ' . $diff['dad_bank_ifsc'] . ',';
			}
			if($diff['dad_bank_account_no'])
			{
				$msg .= ' Bank A/C No.: ' . $diff['dad_bank_account_no'] . ',';
			}
			$msg = rtrim($msg, ',');
		}
		return $msg;
	}

	public function getDetails($drvId)
	{
		$rows = VehicleStats::model()->getUnapprovedDocByDrvId($drvId);
		return $rows;
	}

	public function citiesListNew()
	{
		$drvId		 = UserInfo::getInstance();
		$usetType	 = UserInfo::getUserType();
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		try
		{
			if($data)
			{
				$jsonObj = CJSON::decode($data, true);
				$stateId = 80; //$jsonObj->stateId;
				$ctyName = 'Tirupati'; //$jsonObj->cityIntName;
				$ctyList = Cities::searchCityListByStateId($stateId, $ctyName);
				$list	 = Stub\common\Cities::getList($ctyList);
				if($ctyList != [])
				{
					$returnSet->setStatus(true);
					$returnSet->setData($ctyList);
				}
				else
				{
					$returnSet->setStatus(false);
					$returnSet->setErrors("SERVER ERROR", ReturnSet::ERROR_SERVER);
				}
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("ERROR UNAUTHORISED", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		catch(Exception $e)
		{

			$returnSet = $returnSet->setException($e);
		}
		return $returnSet;
	}

	public function fetchRatingNew()
	{
		$returnSet = new ReturnSet();
		try
		{
			$driverId = 25213; //UserInfo::getEntityId();
			if($driverId)
			{
				$drvRating	 = DriverStats::fetchRating($driverId, $flag		 = 1);
				$rating		 = ($drvRating['drs_drv_overall_rating'] > 0) ? ($drvRating['drs_drv_overall_rating']) : 4;
				$rate		 = ['rating' => $rating];
				$returnSet->setStatus(true);
				$returnSet->setData($rate);
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("ERROR UNAUTHORISED", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		catch(Exception $e)
		{
			$returnSet = $returnSet->setException($e);
		}
		return $returnSet;
	}

	public function driverPrefLanguage()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$check		 = Drivers::model()->authoriseDriver($token);
		$data		 = Yii::app()->request->rawBody;
		try
		{
			if($data)
			{
				$langArr		 = Contact::model()->language();
				$language		 = Contact::model()->getJSON($langArr);
				$lang			 = json_decode($language);
				$jsonObj		 = CJSON::decode($data, true);
				$drvPrefLanguage = 2; //$jsonObj->drvPrefLanguage;
				$driverId		 = 19802; //UserInfo::getEntityId();
				$model			 = Drivers::model()->findByPk($driverId);
				if($drvPrefLanguage != "")
				{
					$cntModel = Contact::model()->updateLang($model->drv_contact_id, $drvPrefLanguage);
					$returnSet->setStatus(true);
					$returnSet->setData($lang);
				}
				else
				{
					$returnSet->setStatus(false);
					$returnSet->setErrors("DATA NOT FOUND", ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("ERROR UNAUTHORISED", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		catch(Exception $e)
		{
			$returnSet = $returnSet->setException($e);
		}
		return $returnSet;
	}

	public function forgotpassNew()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		try
		{
			if($data)
			{
				$jsonObj	 = CJSON::decode($data, true);
				$phone		 = $jsonObj->phone;
				$code		 = $jsonObj->code;
				$newPassword = $jsonObj->new_password;

				$newPassword = md5($newPassword);
				$status		 = false;
				$arr		 = [];
				$result		 = Drivers::model()->forgotPassword($phone, $code, $newPassword, $arr, $status);
				if($result)
				{
					$success = $result[0]['success'];
					$message = $result[0]['message'];
					$returnSet->setStatus($success);
					$returnSet->setData($message);
				}
				else
				{
					$returnSet->setStatus(false);
					$returnSet->setErrors("ERROR FAILED", ReturnSet::ERROR_FAILED);
				}
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("ERROR UNAUTHORISED", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		catch(Exception $e)
		{
			$returnSet = $returnSet->setException($e);
		}
		return $returnSet;
	}

	public function updateLastLocationNew()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$data		 = Yii::app()->request->rawBody;
		try
		{
			if($data)
			{
				$jsonObj	 = CJSON::decode($data, true);
				$bModel		 = Booking::model()->findByPk($jsonObj->bkg_id);
				$sosStatus	 = ($bModel->bkgTrack->bkg_drv_sos_sms_trigger == 2) ? true : false;
				$success	 = DriverStats::model()->updateLastLocation($jsonObj);
				if($success != false)
				{
					$userInfo = UserInfo::getInstance();
					Location::addLocation($jsonObj, $userInfo);
					$returnSet->setStatus($success);
					$returnSet->setMessage('Last location updated');
				}
				else
				{
					$returnSet->setStatus(false);
					$returnSet->setErrors("ERROR FAILED", ReturnSet::ERROR_FAILED);
				}
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("NO DATA FOUND.", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		catch(Exception $e)
		{
			$returnSet = $returnSet->setException($e);
		}

		return $returnSet;
	}

	public function validateUnsyncdata()
	{
		$returnSet = new ReturnSet();
		try
		{
			$details = Yii::app()->request->rawBody;
			$jsonObj = CJSON::decode($details, false);

			if(empty($jsonObj))
			{
				goto catchBlock;
			}

			$event = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			if($event)
			{
				$userInfo	 = UserInfo::getInstance();
				$driverId	 = $userInfo->getEntityId();
			}
			$bookingId				 = $jsonObj->bookingId;
			$jsonObj->unsynced_data	 = 0;
			$bkgModel				 = Booking::model()->findByPk($bookingId);
			$driverId				 = $bkgModel->bkgBcb->bcb_driver_id;
			$success				 = DriverStats::model()->updateStat($jsonObj, $driverId);
			if($success)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage('UPDATED DRIVER LOGIN');
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("ERROR FAILED", ReturnSet::ERROR_FAILED);
			}
		}
		catch(Exception $e)
		{
			catchBlock:
			$returnSet = $returnSet->setException($e);
			Logger::exception($e);
		}
		return $returnSet;
	}

	public function temporaryLogin()
	{
		$returnSet = new ReturnSet();
		//    $jsonMapper    = new JsonMapper();

		$sessionId		 = Yii::app()->getSession()->getSessionID();
		$isLoggedIn		 = !Yii::app()->user->isGuest;
//		$token			 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$msgUloadFirst	 = "";
		try
		{
			if($isLoggedIn)
			{
				// throw new Exception("Already Logged In", ReturnSet::ERROR_VALIDATION);
			}
			$details	 = Yii::app()->request->rawBody;
			Logger::trace("Request : " . $details);
			/* @var $obj \Stub\driver\LoginRequest */
			$obj		 = Yii::app()->request->getJSONObject(new \Stub\driver\LoginRequest());
			$fcmToken	 = $obj->device->token;
			$ipAddress	 = \Filter::getUserIP();
			$checkDriver = DriverStats::model()->getSyncActivity($obj->drvid);
			if(is_array($checkDriver))
			{
				Logger::create("driver temporaryLogin  drvid: {$obj->drvid} DriverStats::getSyncActivity = " . json_encode($checkDriver), CLogger::LEVEL_INFO);
			}
			if($checkDriver['lock'] == 1)
			{
				if($obj->device->uniqueId != $checkDriver['device']['uniqueId'] && $obj->device->deviceName != $checkDriver['device']['deviceName'])
				{
					$uploadMsg	 = "Your session is locked. You previously logged in from  " . $checkDriver['device']['deviceName'] . " .Please complete your sync process and logout from that phone before you can proceed here.";
					$errors		 = [$uploadMsg];
					$returnSet->setStatus(false);
					$returnSet->setErrors($errors, ReturnSet::ERROR_NO_RECORDS_FOUND);
					Logger::create("driver temporaryLogin  session is locked.", CLogger::LEVEL_INFO);
					goto skipAll;
				}
			}

			$row		 = Drivers::getUserContact($obj->drvid);
			$userId		 = $row["userId"];
			$contactId	 = $row["contactId"];

			$drvModel		 = Drivers::model()->findByPk($row["driverId"]);
			$contactModel	 = Contact::model()->findByPk($contactId);
			Logger::create("driver temporaryLogin  contactId: {$contactId} userId: {$userId} driverId: {$row['driverId']}", CLogger::LEVEL_INFO);
			if($contactId == '')
			{
				throw new Exception("Unable to validate driver contact", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			if($userId == null)
			{
				$userModel	 = Users::createbyContact($contactId);
				$userId		 = $userModel->user_id;
				Logger::create("driver temporaryLogin  createbyContact  userId: {$userId}", CLogger::LEVEL_INFO);
			}
			else
			{
				$userModel = Users::model()->findByPk($userId);
			}

			if(!$userId)
			{
				throw new Exception("User Account not found", ReturnSet::ERROR_FAILED);
			}

			if($token == '')
			{
				$token = $sessionId;
			}

			$appTokenModel = AppTokens::model()->addLogin($drvModel->drv_id, $userId, $obj->device, $ipAddress, $token, $fcmToken);

			$msg = "Login Successful";

			$res			 = new \Stub\common\Business();
			$res->setDriverdata($userModel, $drvModel, $contactModel);
			$res->sessionId	 = $token;

			AppTokens::removeDuplicateDevice($appTokenModel->apt_device_uuid, $appTokenModel->apt_device_token, AppTokens::Platform_Driver, $userId);

			AppTokens::logoutSessionsForAuthToken($obj->drvid, $token, AppTokens::Platform_Driver);
			$res->id = $res->selfId; // according to AK sir contact id ooverright to driverId for app 18:08:22
			$data1	 = Filter::removeNull($res);
			$returnSet->setStatus(true);
			$returnSet->setMessage($msg . $msgUloadFirst);
			$returnSet->setData($data1);
		}
		catch(Exception $ex)
		{
			Logger::create("driver temporaryLogin  main exception " . $ex->getMessage(), CLogger::LEVEL_INFO);
			$returnSet = ReturnSet::setException($ex);
		}
		skipAll:
		Logger::trace("Respnse : " . json_encode($returnSet));
		Logger::create("driver temporaryLogin  response: " . json_encode($returnSet), CLogger::LEVEL_INFO);
		return $returnSet;
	}

	public function social_link()
	{
		$returnSet	 = new ReturnSet();
		$jsonMapper	 = new JsonMapper();

		$transaction = DBUtil::beginTransaction();
		try
		{
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$data	 = Yii::app()->request->rawBody;

			$jsonObj = CJSON::decode($data, false);
			if(empty($data))
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}

			$appToken = AppTokens::model()->getByToken($token);
			if(!$appToken)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$appToken->apt_last_login = new CDbExpression('NOW()');
			$appToken->save();

			/** @var \Stub\common\Auth $obj */
			$obj = $jsonMapper->map($jsonObj, new \Stub\common\Auth());

			/** @var SocialAuth $model */
			$model		 = $obj->getSocialModel();
			$row		 = Drivers::getUserContact($appToken->apt_entity_id);
			$userId		 = $row["userId"];
			$contactId	 = $row["contactId"];

			$oAuthModel = $model->linkContact($obj->accessToken, $contactId);

			$appToken->apt_user_id = $oAuthModel->user_id;
			$appToken->save();

			DBUtil::commitTransaction($transaction);
			$returnSet->setStatus(true);
			$returnSet->setMessage("Account linked successfully");
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	public function socialLogin()
	{

		$token			 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$returnSet		 = new ReturnSet();
		$data			 = Yii::app()->request->rawBody;
		$multi			 = false;
		$msgUloadFirst	 = "";
		$transaction	 = DBUtil::beginTransaction();
		try
		{
			throw new Exception("This app has been discontinued. Please install & use Gozo Partner+ app from Google play store.", ReturnSet::ERROR_NO_RECORDS_FOUND);
			if(!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			if(!empty($token))
			{
				$deactivateToken = " UPDATE app_tokens SET apt_status = 0 WHERE apt_token_id = '$token'";
				DBUtil::command($deactivateToken)->execute();
			}
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$jsonMapper		 = new JsonMapper();
			$jsonObj		 = CJSON::decode($data, false);
			/** @var \Stub\common\Auth $obj */
			$obj			 = $jsonMapper->map($jsonObj, new \Stub\common\Auth());
			$activeVersion	 = Config::get("Version.Android.driver"); //Yii::app()->params['versionCheck']['driver'];

			/* version check */
			if(version_compare($obj->device->version, $activeVersion) < 0)
			{
				throw new Exception('Please update to the latest version of the Gozo Driver App', ReturnSet::ERROR_UNAUTHORISED);
			}


			$authModel = $obj->getSocialModel();

			$userModel = $authModel->getUserModel();
			if(!$userModel)
			{
				$userModel = Users::login($authModel, true);
			}

			if(!$userModel)
			{
				throw new Exception("Unable to authenticate user", ReturnSet::ERROR_FAILED);
			}

			$drvModel = Drivers::getByUserId($userModel->user_id);
			if(!$drvModel)
			{
				throw new Exception("Account not registered as driver", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			$drvID = $drvModel->drv_id;

			$checkDriver = DriverStats::model()->getSyncActivity($drvID);

			if($checkDriver['lock'] == 1)
			{

				if($obj->device->uniqueId != $checkDriver['device']['uniqueId'] && $obj->device->deviceName != $checkDriver['device']['deviceName'])
				{
					//$returnSet->setStatus(false);
					$uploadMsg = "Your session is locked. You previously logged in from  " . $checkDriver['device']['deviceName'] . " .Please complete your sync process and logout from that phone before you can proceed here.";
					//$returnSet->setMessage($uploadMsg);
					//	$uploadMsg = "You are locked.Please upload from " . $checkDriver['device']['deviceName'] . " device and complete sync process.";

					$errors = [$uploadMsg];
					$returnSet->setStatus(false);
					$returnSet->setErrors($errors, ReturnSet::ERROR_NO_RECORDS_FOUND);

					goto skipAll;
				}
				$msgUloadFirst = "Your session is locked.Please upload data related your previous booking ftom {$obj->device->deviceName}";
			}

			$deviceData					 = $obj->device;
			$data						 = Drivers::model()->Login($userModel, $deviceData);
			$contactModel				 = Contact::model()->findByPk($data['driver_contact_id']);
			$authResponse				 = new \Stub\common\Business();
			$authResponse->setBusinessdata($userModel, $data, $contactModel);
			$authResponse->id			 = $drvID;  // according to AK sir contact id overright to driverId for app 18:08:22
			$authResponse->code			 = $data['drvcode'];
			$authResponse->multiplelogin = $data['multiplelogin'];
			$authResponse->multi		 = $multi;

			$response = Filter::removeNull($authResponse);

			if($response)
			{
				$returnSet->setStatus(true);
				$returnSet->setData($response);
				$returnSet->getMessage("Login Successful" . $msgUloadFirst);
				Logger::trace("Response : " . json_encode($response));
			}
			DBUtil::commitTransaction($transaction);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		skipAll:
		return $returnSet;
	}

	function statusDetails_V1()
	{
		$returnSet = new ReturnSet();
		try
		{
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);
			if($check)
			{
				$drvId						 = UserInfo::getEntityId();
				$errors						 = '';
				$result						 = $this->getDetails($drvId);
				$success					 = true;
				$drvSosStatus				 = 0;
				$isSocialLinkingMandatory	 = true;
				$drvSocialLinking			 = Drivers::model()->checkSocialLinking($drvId);
				if($drvSocialLinking)
				{
					$isSocialLinkingMandatory = false;
				}
				$drvModel				 = Drivers::model()->findByPk($drvId);
				$isVaccineUpdateRequired = false; //Contact::model()->isVaccineUpdateRequired($drvModel->drv_contact_id);
				$listDocs				 = Document::model()->findAllByDrvId($drvModel->drv_contact_id);
				$drvDocs				 = Document::model()->getUnapprovedDoc($drvId, $drvModel->drv_contact_id);
				Logger::create('19 DriverId Docs' . $drvDocs, CLogger::LEVEL_TRACE);
				if($listDocs[0]['doc_police_status'] == null || $listDocs[0]['doc_police_status'] == 2)
				{
					$isPoliceVerification	 = 0;
					$message				 = "Your Police verification certification needs to be submitted. Please upload the document from your profile within Gozo Driver app";
				}
				else
				{
					$isPoliceVerification = 1;
				}
				$resDriver	 = $drvModel->getApiMappingByDriver();
				$photo		 = ($resDriver['drv_photo_path'] != '') ? 1 : 0;
				if((isset($drvDocs['count']) && $drvDocs['count'] > 0) || ($resDriver['drv_photo_path'] == '' || $resDriver['drv_photo_path'] == null))
				{
					$result1[] = ['entity_type' => '2', 'entity_id' => $drvModel->drv_id, 'drv_name' => $drvModel->drv_name, 'drv_phone' => $resDriver['drv_phone'], 'drv_photo' => $resDriver['drv_photo_path'], 'vhc_number' => null, 'vht_model' => null, 'car_type' => null, 'vhc_insurance_exp_date' => null, 'vhc_reg_exp_date' => null, 'docs' => $drvDocs,];
				}
				$server_datetime	 = DBUtil::getCurrentTime();
				$server_timestamp	 = ((strtotime($server_datetime)) * 1000);

				$data = [
					'success'					 => $success,
					'drv_social_link'			 => $drvSocialLinking,
					'linking_required'			 => $isSocialLinkingMandatory,
					'vaccineUpdateRequired'		 => $isVaccineUpdateRequired,
					'errors'					 => $errors,
					'server_timestamp'			 => $server_timestamp,
					'photo'						 => $photo,
					'docs'						 => $drvDocs,
					'police_verification_status' => ['police_verification' => (int) $isPoliceVerification, 'message' => $message],
					'result'					 => $result];

				$returnSet->setStatus(true);
				$returnSet->setData($data);
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors('Unauthorised Driver', ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function loginByOtp()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$drvId			 = UserInfo::getEntityId();
			$drvData		 = Drivers::getUserContact($drvId);
			$cttId			 = $drvData['contactId'];
			$cttPhoneData	 = ContactPhone::findByContactID($cttId);
			if(empty($cttPhoneData))
			{
				throw new Exception("Sorry, unable to find any phone number related to driver.", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$result = Drivers::sendLoginVerificationOtp($cttPhoneData[0]->phn_phone_no, $cttPhoneData[0]->phn_phone_country_code);
			if($result['status'])
			{
				$returnSet->setStatus(true);
				$returnSet->setData(['loginOtp' => $result['verifyCode']]);
				$returnSet->setMessage('OTP sent successfully');
			}
			else
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors(['Sorry, unable to send  OTP'], ReturnSet::ERROR_FAILED);
			}
		}
		catch(Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function validateLoginByOtp()
	{
		$returnSet	 = new ReturnSet();
		$jsonMapper	 = new JsonMapper();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$data	 = Yii::app()->request->rawBody;
			$jsonObj = CJSON::decode($data, false);
			if(empty($data))
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$obj	 = $jsonMapper->map($jsonObj, new \Stub\common\Platform());
			$device	 = $obj->getAppToken();
			$drvId	 = UserInfo::getEntityId();
			$drvData = Drivers::getUserContact($drvId);
			$userId	 = $drvData['userId'];
			if(empty($userId))
			{
				throw new Exception("Invalid User: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$result = AppTokens::updateUserAndDevice($token, $device->apt_device_token, $userId, $drvId);
			if(!$result['success'])
			{
				throw new Exception($result['message'], ReturnSet::ERROR_INVALID_DATA);
			}
			$userType = UserInfo::TYPE_DRIVER;
			AppTokens::removeDuplicateDevice($device->apt_device_uuid, $device->apt_device_token, $userType, $userId);

//			AppTokens::logoutAllPreviousSessions($drvId, $device->apt_device_token, AppTokens::Platform_Driver);

			$returnSet->setStatus(true);
			$returnSet->setMessage('Login Successful.');
		}
		catch(Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}

		return $returnSet;
	}

	public function social_linkv2()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$data		 = Yii::app()->request->rawBody;
		if(!$token || !$data)
		{
			throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}
		try
		{
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			/** @var \Stub\common\Auth $obj */
			$obj		 = $jsonMapper->map($jsonObj, new \Stub\common\Auth());
			$authModel	 = $obj->getSocialModel();
			#print_r($authModel);exit;
			$accessToken = $obj->accessToken;
			$drvId		 = UserInfo::getEntityId();

			$contactId	 = ContactProfile::getByEntityId($drvId, UserInfo::TYPE_DRIVER);
			$userId		 = ContactProfile::getUserId($contactId);
			$flag1		 = 'driver-app';
			if($userId != '')
			{
				$oAuthModel = $authModel->linkUser($accessToken, $userId);
			}
			else
			{
				$oAuthModel = $authModel->linkContact($accessToken, $contactId);
			}

			if(!$oAuthModel)
			{
				throw new Exception("Failed to link social account", ReturnSet::ERROR_FAILED);
			}

			$returnSet->setStatus(true);
			$returnSet->setMessage("Account Linked Successfully");
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	function updateFcm()
	{
		$returnSet	 = new ReturnSet();
		$authToken	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$data		 = Yii::app()->request->rawBody;

		if(!$authToken || !$data)
		{
			throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}
		try
		{
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			/** @var \Stub\common\Platform $obj */
			$obj		 = $jsonMapper->map($jsonObj, new \Stub\common\Platform());

			$result = AppTokens::updateFcm($authToken, $obj->token);

			if(!$result['success'])
			{
				throw new Exception("Failed to link social account", ReturnSet::ERROR_FAILED);
			}
			$returnSet->setStatus(true);
			$returnSet->setMessage("FCM updated");
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}
}
