<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class UsersController extends BaseController
{

	public $token = '';

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
				'actions'	 => array('signin', 'registerDevice', 'updateFCM', 'sendOTP', 'resendOTP', 'isExisting', 'validateOtp', 'processOTP', 'validateVersion', 'validateProvider',
					'logout',
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
			$ri	 = array('/signin', '/signinV1', '/registerDevice', '/updateFCM', '/sendOTP', '/resendOTP',
				'/isExisting', 'validateOtp', 'validateOtpV1', 'processOTP', '/validateVersion', '/validateProvider'
				, '/logout'
			);
			foreach($ri as $value)
			{
				if(strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});
		$this->onRest('req.post.validateVersion.render', function () {
			return $this->renderJSON($this->validateVersion());
		});
		$this->onRest('req.post.registerDevice.render', function () {
			return $this->renderJSON($this->registerDevice());
		});
		$this->onRest('req.post.updateFCM.render', function () {
			return $this->renderJSON($this->updateFCM());
		});

		$this->onRest('req.post.signin.render', function () {
			return $this->renderJSON($this->signIn());
		});
		$this->onRest('req.post.signinV1.render', function () {
			return $this->renderJSON($this->signInV1());
		});
		$this->onRest('req.post.sendOTP.render', function () {
			return $this->renderJSON($this->sendOTP());
		});
		$this->onRest('req.post.resendOTP.render', function () {
			return $this->renderJSON($this->resendOTP());
		});

		$this->onRest('req.get.validateSession.render', function () {
			return $this->renderJSON($this->validateSession());
		});
		$this->onRest('req.get.validateSessionV1.render', function () {
			return $this->renderJSON($this->validateSessionV1());
		});
		$this->onRest('req.post.isExisting.render', function () {
			return $this->renderJSON($this->isExisting());
		});
		$this->onRest('req.post.isExistingV1.render', function () {
			return $this->renderJSON($this->isExisting());
		});
		$this->onRest('req.post.validateOtp.render', function () {
			return $this->renderJSON($this->validateOtp());
		});
		$this->onRest('req.post.validateOtpV1.render', function () {
			return $this->renderJSON($this->validateOtpV1());
		});

//		$this->onRest('req.post.processOTP.render', function () {
//			return $this->renderJSON($this->processOTP());
//		});
		$this->onRest('req.get.getProfile.render', function () {
			return $this->renderJSON($this->getProfile());
		});
		$this->onRest('req.get.getProfileV1.render', function () {
			return $this->renderJSON($this->getProfileV1());
		});

		$this->onRest('req.get.getNotificationStatus.render', function () {
			return $this->renderJSON($this->getNotificationStatus());
		});

		$this->onRest('req.post.modifyNotificationStatus.render', function () {
			return $this->renderJSON($this->modifyNotificationStatus());
		});

		$this->onRest('req.post.socialLinking.render', function () {
			return $this->renderJSON($this->socialLinking());
		});

		$this->onRest('req.post.locationUpdate.render', function () {
			return $this->renderJSON($this->locationUpdate());
		});

		$this->onRest('req.post.logout.render', function () {
			return $this->renderJSON($this->logout());
		});

		$this->onRest('req.post.setPrefLanguage.render', function () {
			return $this->renderJSON($this->setPrefLanguage());
		});
		$this->onRest('req.get.getLanguageList.render', function () {
			return $this->renderJSON($this->getLanguageList());
		});

		$this->onRest('req.post.validateProvider.render', function () {
			return $this->renderJSON($this->validateProvider());
		});

		$this->onRest('req.get.getCityList.render', function () {
			return $this->renderJSON($this->getCityList());
		});
		$this->onRest('req.get.getStateList.render', function () {
			return $this->renderJSON($this->getStateList());
		});

		$this->onRest('req.get.getCityListByState.render', function () {
			return $this->renderJSON($this->getCityListByState());
		});

		$this->onRest('req.post.updateProfileDoc.render', function () {
			return $this->renderJSON($this->updateProfileDoc());
		});
		$this->onRest('req.post.updateProfileInfo.render', function () {
			return $this->renderJSON($this->updateProfileInfo());
		});
		$this->onRest('req.post.updateProfilePic.render', function () {
			return $this->renderJSON($this->updateProfilePic());
		});
		$this->onRest('req.post.uploadAgreement.render', function () {
			return $this->renderJSON($this->uploadAgreement());
		});
		$this->onRest('req.get.statusDetails.render', function () {
			return $this->renderJSON($this->statusDetails());
		});
		$this->onRest('req.get.agreementInformation.render', function () {
			return $this->renderJSON($this->agreementInformation());
		});
		$this->onRest('req.post.uploadSoftAgreement.render', function () {
			return $this->renderJSON($this->uploadSoftAgreement());
		});

		$this->onRest('req.post.muteNotification.render', function () {
			return $this->renderJSON($this->muteNotification());
		});

		$this->onRest('req.get.vendorInfo.render', function () {
			return $this->renderJSON($this->vendorInfo());
		});

		$this->onRest('req.get.showMatrix.render', function () {
			return $this->renderJSON($this->showMatrix());
		});
		$this->onRest('req.get.penaltyRate.render', function () {
			return $this->renderJSON($this->penaltyRate());
		});
	}

	/**
	 * 
	 * @return type
	 * @throws Exception
	 */
	public function registerDevice()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody;
			if(!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			//Yii::app()->user->logout();
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);

			/* @var $obj JsonMapper */
			$obj = $jsonMapper->map($jsonObj, new \Beans\common\DeviceInfo());

			/* @var $appTokenModel AppTokens */
			$usr = new UserInfo();
			$usr->getEntityId();

			$platform		 = AppTokens::Platform_DCO;
			$activeVersion	 = Config::get("Version.Android.dco");
			$appTokenModel	 = AppTokens::registerDevice($obj->getAppToken(), $usr, $activeVersion, $platform);

			if(!$appTokenModel)
			{
				throw new Exception(json_encode("Unable to register device ."), ReturnSet::ERROR_VALIDATION);
			}

			$returnSet->setStatus(true);
			$resObj = new \Beans\common\DeviceInfo();

			$returnSet->setData($resObj->setSessToken($appTokenModel));
			$returnSet->setMessage('Device registered successfully.');
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	/**
	 * 
	 * @return type
	 * @throws Exception
	 */
	public function updateFCM()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$authToken	 = Yii::app()->request->getRestToken();
		$transaction = DBUtil::beginTransaction();
		try
		{
			if(!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj	 = CJSON::decode($data, false);
			/* @var $obj \Beans\common\DeviceInfo() */
			$jsonMapper	 = new JsonMapper();
			$obj		 = $jsonMapper->map($jsonObj, new \Beans\common\DeviceInfo());
			if(empty($authToken) || empty($obj->fcmToken))
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$resultData = AppTokens::updateFcm($authToken, $obj->fcmToken);
			if($resultData['success'] == false)
			{
				throw new Exception($resultData['message'], ReturnSet::ERROR_INVALID_DATA);
			}
			DBUtil::commitTransaction($transaction);
			$returnSet->setStatus(true);
			$returnSet->setMessage("Token updated sucessfully");
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function signInOld()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$authToken	 = Yii::app()->request->getRestToken();
		try
		{
			if(!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::trace("request : " . $data);
			$jsonObj	 = CJSON::decode($data, false);
			$jsonMapper	 = new JsonMapper();

			$objAuth = new \Beans\common\AuthRequest();

			/** @var \Beans\common\AuthRequest $obj */
			$obj = $jsonMapper->map($jsonObj, $objAuth);

			$deviceObj	 = new \Beans\common\DeviceInfo();
			$appModel	 = \AppTokens::validateToken($authToken);
//			$appModel	 = AppTokens::model()->getByToken($authToken);
//			if (!$appModel)
//			{
//				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
//			}
			$deviceObj->setData($appModel);
			$obj->device = $deviceObj;
			$loginType	 = $obj->type;

			switch($loginType)
			{
				case 1 : //password

					$userModel = $obj->getUserLoginModel();

					$result = CActiveForm::validate($userModel);
					if($result != '[]')
					{
						$errors = $userModel->getErrors();
						throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
					}
					$userModel->usr_password = md5($userModel->usr_password);

					$passWord	 = $userModel->usr_password;
					$email		 = $userModel->usr_email;
					$identity	 = new UserIdentity($email, $passWord);
					if(!$identity->authenticate())
					{
						throw new Exception("Unable to authenticate", 400);
					}
					$userId		 = $identity->getId();
					$userModel	 = Users::model()->findByPk($userId);
					break;

				case 2 : //otp

					$userModel = $this->processOTP($obj);
					break;
				case 3 : //Google

					$authModel	 = $obj->socialResponse->getSocialModel();
					$userModel	 = $authModel->getUserModel();
					if(!$userModel)
					{
						$userModel = Users::validateInstance($authModel, true);
					}
					break;

				default:
					break;
			}

			if(!$userModel)
			{
				throw new Exception(json_encode(["Unable to login."]), ReturnSet::ERROR_VALIDATION);
			}
			$identity = $this->authenticateUser($userModel);
			if(!$identity)
			{
				throw new Exception(json_encode(["Unable to get user identity."]), ReturnSet::ERROR_VALIDATION);
			}
			//Logger::trace("response : " . json_encode($userModel));

//			$contactData	 = ContactProfile::getEntitybyUserId($userModel->user_id);

			$refContactData	 = ContactProfile::getEntitybyUserId($userModel->user_id);
			$contactData	 = ContactProfile::getPrimaryEntitiesByContact($refContactData['ctt_id']);

			$approvalStatus = 1;

//
			$userType	 = UserInfo::TYPE_VENDOR;
			$vndId		 = $contactData['cr_is_vendor'];
			$drvId		 = $contactData['cr_is_driver'];

			$vndModel = Vendors::model()->findByPk($vndId);

			if($drvId > 0 && (!$vndModel || ($vndModel->vnd_active != 1)))
			{
				$drvStatus		 = Drivers::model()->findByPk($drvId)->drv_approved;
				$approvalStatus	 = ($drvStatus == 1) ? 1 : 0;

				$userType	 = UserInfo::TYPE_DRIVER;
				$JWToken	 = $this->getJWToken($userModel, $appModel, $userType);
				$drvId		 = $this->getDriverId();

				goto registerUrl;
			}

			if(!$vndId || $vndId == '')
			{
				$userType		 = ($userType == UserInfo::TYPE_VENDOR) ? UserInfo::TYPE_CONSUMER : $userType;
				$approvalStatus	 = 0;
				goto registerUrl;
			}
			if(!$drvId || $drvId == '')
			{
				$userType		 = ($vndId > 0) ? $userType : UserInfo::TYPE_CONSUMER;
				$approvalStatus	 = 0;
				goto registerUrl;
			}

//
			$vndStatus = Vendors::model()->findByPk($vndId)->vnd_active;

			if(!in_array($vndStatus, [1, 2]))
			{
				$approvalStatus = 0;
				goto registerUrl;
			}


//			$userModel = Users::validateInstance($userModel);
//			if (!$userModel)
//			{
//				throw new Exception("Unable to authenticate.", ReturnSet::ERROR_NO_RECORDS_FOUND);
//			}



			registerUrl:
			$userInfo = UserInfo::getInstance();

			$JWToken = $this->getJWToken($userModel, $appModel, $userType);

			$userSession = new \Beans\common\UserSession();
			$userSession->setLoginResponse($contactData);

			$resArr						 = [];
			$resArr['jwtoken']			 = $JWToken;
			$resArr['type']				 = $loginType;
			$resArr['profile']			 = $userSession;
			$resArr['approvalStatus']	 = $approvalStatus;
			if($approvalStatus == 0)
			{
				$resArr['url'] = $this->getUrlByJwt($JWToken);
			}


			$phoneData = \ContactPhone::getPrimaryNumber($contactData['cr_contact_id'], true);
			if(!$phoneData)
			{
				$resArr['missingData'][] = 1;
			}

			$authResponse = new \Beans\common\AuthResponse();
			$authResponse->setData($resArr);

			$returnSet->setData($authResponse);
			Logger::trace("response : " . json_encode($resArr));
			end:
			$returnSet->setStatus(true);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	/*	 * @deprecated
	 * new function signInV1
	 */

	public function signIn()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$authToken	 = Yii::app()->request->getRestToken();
		try
		{
			if(!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::trace("request : " . $data);
			$jsonObj	 = CJSON::decode($data, false);
			$jsonMapper	 = new JsonMapper();

			$objAuth = new \Beans\common\AuthRequest();

			/** @var \Beans\common\AuthRequest $obj */
			$obj = $jsonMapper->map($jsonObj, $objAuth);

			$deviceObj	 = new \Beans\common\DeviceInfo();
			$appModel	 = \AppTokens::validateToken($authToken);

			$deviceObj->setData($appModel);
			$obj->device = $deviceObj;
			$loginType	 = $obj->type;

			switch($loginType)
			{
				case 1 : //password

					$userModel = $obj->getUserLoginModel();

					$result = CActiveForm::validate($userModel);
					if($result != '[]')
					{
						$errors = $userModel->getErrors();
						throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
					}
					$userModel->usr_password = md5($userModel->usr_password);

					$passWord	 = $userModel->usr_password;
					$email		 = $userModel->usr_email;
					$identity	 = new UserIdentity($email, $passWord);
					if(!$identity->authenticate())
					{
						throw new Exception("Unable to authenticate", 400);
					}
					$userId		 = $identity->getId();
					$userModel	 = Users::model()->findByPk($userId);
					break;

				case 2 : //otp

					$userModel = $this->processOTP($obj);
					break;
				case 3 : //Google

					$authModel	 = $obj->socialResponse->getSocialModel();
					$userModel	 = $authModel->getUserModel();
					if(!$userModel)
					{
						$userModel = Users::validateInstance($authModel, true);
					}
					break;

				default:
					break;
			}

			if(!$userModel)
			{
				throw new Exception(json_encode(["Unable to login."]), ReturnSet::ERROR_VALIDATION);
			}
			$identity = $this->authenticateUser($userModel);
			if(!$identity)
			{
				throw new Exception(json_encode(["Unable to get user identity."]), ReturnSet::ERROR_VALIDATION);
			}
			//Logger::trace("response : " . json_encode($userModel));

//			$contactData = ContactProfile::getEntitybyUserId($userModel->user_id);

			$refContactData	 = ContactProfile::getEntitybyUserId($userModel->user_id);
			$contactData	 = ContactProfile::getPrimaryEntitiesByContact($refContactData['ctt_id']);

			$vndId			 = $contactData['cr_is_vendor'];
			$drvId			 = $contactData['cr_is_driver'];
			$approvalStatus	 = 1;
			// function used for approval status

			if($vndId > 0)
			{
				$userType		 = UserInfo::TYPE_VENDOR;
				$approvalStatus	 = VendorDriver::checkApprovalStatus($vndId);
				goto registerUrl;
			}

			if($drvId > 0)
			{
				$userType = UserInfo::TYPE_DRIVER;

				$drvStatus		 = Drivers::model()->findByPk($drvId)->drv_approved;
				$approvalStatus	 = ($drvStatus == 1) ? 1 : 0;
				goto registerUrl;
			}
			if($vndId == '' && $drvId == '')
			{
				$userType		 = UserInfo::TYPE_CONSUMER;
				$approvalStatus	 = 0;
				goto registerUrl;
			}


			registerUrl:

			$userInfo = UserInfo::getInstance();

			$JWToken = $this->getJWToken($userModel, $appModel, $userType);

			$userSession = new \Beans\common\UserSession();
			$userSession->setLoginResponse($contactData);

			$resArr						 = [];
			$resArr['jwtoken']			 = $JWToken;
			$resArr['type']				 = $loginType;
			$resArr['profile']			 = $userSession;
			$resArr['approvalStatus']	 = $approvalStatus;
			if($approvalStatus == 0)
			{
				$resArr['url'] = $this->getUrlByJwt($JWToken);
			}


			$phoneData = \ContactPhone::getPrimaryNumber($contactData['cr_contact_id'], true);
			if(!$phoneData)
			{
				$resArr['missingData'][] = 1;
			}

			$authResponse = new \Beans\common\AuthResponse();
			$authResponse->setData($resArr);

			$returnSet->setData($authResponse);
			Logger::trace("response : " . json_encode($resArr));
			end:
			$returnSet->setStatus(true);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	/*	 * new function
	 * old function signIn
	 */

	public function signInV1()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$authToken	 = Yii::app()->request->getRestToken();
		try
		{
			if(!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::trace("request : " . $data);
			$jsonObj	 = CJSON::decode($data, false);
			$jsonMapper	 = new JsonMapper();

			$objAuth = new \Beans\common\AuthRequest();

			/** @var \Beans\common\AuthRequest $obj */
			$obj = $jsonMapper->map($jsonObj, $objAuth);

			$deviceObj	 = new \Beans\common\DeviceInfo();
			$appModel	 = \AppTokens::validateToken($authToken);

			$deviceObj->setData($appModel);
			$obj->device = $deviceObj;
			$loginType	 = $obj->type;

			switch($loginType)
			{
				case 1 : //password

					$userModel = $obj->getUserLoginModel();

					$result = CActiveForm::validate($userModel);
					if($result != '[]')
					{
						$errors = $userModel->getErrors();
						throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
					}
					$userModel->usr_password = md5($userModel->usr_password);

					$passWord	 = $userModel->usr_password;
					$email		 = $userModel->usr_email;
					$identity	 = new UserIdentity($email, $passWord);
					if(!$identity->authenticate())
					{
						throw new Exception("Unable to authenticate", 400);
					}
					$userId		 = $identity->getId();
					$userModel	 = Users::model()->findByPk($userId);
					break;

				case 2 : //otp

					$userModel = $this->processOTP($obj);
					break;
				case 3 : //Google

					$authModel	 = $obj->socialResponse->getSocialModel();
					$userModel	 = $authModel->getUserModel();
					if(!$userModel)
					{
						$userModel = Users::validateInstance($authModel, true);
					}
					break;

				default:
					break;
			}

			if(!$userModel)
			{
				throw new Exception(json_encode(["Unable to login."]), ReturnSet::ERROR_VALIDATION);
			}
			$identity = $this->authenticateUser($userModel);
			if(!$identity)
			{
				throw new Exception(json_encode(["Unable to get user identity."]), ReturnSet::ERROR_VALIDATION);
			}
			//Logger::trace("response : " . json_encode($userModel));
//			$contactData	 = ContactProfile::getEntitybyUserId($userModel->user_id); 

			$refContactData	 = ContactProfile::getEntitybyUserId($userModel->user_id);
			$contactData	 = ContactProfile::getPrimaryEntitiesByContact($refContactData['ctt_id']);

			$vndId			 = $contactData['cr_is_vendor'];
			$drvId			 = $contactData['cr_is_driver'];
			$approvalStatus	 = 1;
			// function used for approval status
			if($drvId > 0)
			{
				$userType = UserInfo::TYPE_DRIVER;

				$drvStatus		 = Drivers::model()->findByPk($drvId)->drv_approved;
				$approvalStatus	 = ($drvStatus == 1) ? 1 : 0;
				$drvStat		 = Drivers::model()->findByPk($drvId)->drv_is_freeze;
				if($drvStat == 1)
				{
					$driverBlock				 = 1;
					$contactData['cr_is_driver'] = 0;
				}
				//goto registerUrl;
			}
			if($vndId > 0)
			{
				$userType	 = UserInfo::TYPE_VENDOR;
				//$approvalStatus	 = VendorDriver::checkApprovalStatus($vndId);
				$vndStatus	 = Vendors::model()->findByPk($vndId)->vnd_active;
				if($vndStatus == 2)
				{
					$vendorBlock				 = 1;
					$contactData['cr_is_vendor'] = 0;
				}
				//goto registerUrl;
			}


			if($vndId == '' && $drvId == '')
			{
				$userType		 = UserInfo::TYPE_CONSUMER;
				$approvalStatus	 = 0;
				goto registerUrl;
			}
			if($vendorBlock == 1 && $driverBlock == 1)
			{
				throw new Exception(json_encode(["Unable to login, your account is blocked."]), ReturnSet::ERROR_VALIDATION);
			}
			if($vndId == '' && $driverBlock == 1)
			{
				throw new Exception(json_encode(["Unable to login, your account is blocked."]), ReturnSet::ERROR_VALIDATION);
			}
			if($drvId == '' && $vendorBlock == 1)
			{
				throw new Exception(json_encode(["Unable to login, your account is blocked."]), ReturnSet::ERROR_VALIDATION);
			}
			registerUrl:



			$JWToken = $this->getJWToken($userModel, $appModel, $userType,$identity);

			/* $userSession = new \Beans\common\UserSession();
			  $userSession->setLoginResponse($contactData); */
			$userProfile = new \Beans\common\UserSession();
			$userProfile->setProfileV1($contactData);

			$resArr						 = [];
			$resArr['jwtoken']			 = $JWToken;
			$resArr['type']				 = $loginType;
			$resArr['profile']			 = $userProfile;
			$resArr['approvalStatus']	 = $approvalStatus;
			if($approvalStatus == 0)
			{
				$resArr['url'] = $this->getUrlByJwt($JWToken);
			}

			$refCttId	 = $contactData['cr_contact_id'];
			$phoneData	 = \ContactPhone::getPrimaryNumber($contactData['cr_contact_id'], true, $refCttId);
			if(!$phoneData)
			{
				$resArr['missingData'][] = 1;
			}

			$authResponse = new \Beans\common\AuthResponse();
			$authResponse->setData($resArr);

			$returnSet->setData($authResponse);
			Logger::trace("response : " . json_encode($resArr));
			end:
			$returnSet->setStatus(true);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	/**
	 * 
	 * @return type
	 * @throws Exception
	 */
	public function sendOTP()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$authToken	 = Yii::app()->request->getRestToken();
		try
		{

			if(!$authToken)
			{
				throw new Exception("Invalid login ", ReturnSet::ERROR_UNAUTHORISED);
			}
			if(!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}

			$jsonObj	 = CJSON::decode($data, false);
			$jsonMapper	 = new JsonMapper();

			/** @var \Beans\common\AuthRequest $obj */
			$obj = $jsonMapper->map($jsonObj, new \Beans\common\AuthRequest());

			$appModel = AppTokens::validateToken($authToken);

			$username	 = $obj->userName;
			$isEmail	 = Filter::validateEmail($username);
			$isPhone	 = false;

			if(!$isEmail)
			{
				$isPhone = Filter::processPhoneNumber($username);
			}

			if(!$isEmail && !$isPhone)
			{
				throw new Exception(json_encode(["Please enter valid email/phone number. Unable to authenticate."]), ReturnSet::ERROR_INVALID_DATA);
			}
			$userEmail	 = '';
			$userPhone	 = $username;
			if($isEmail)
			{
				$userEmail	 = $username;
				$userPhone	 = '';
			}
			$contactId = \Contact::getByEmailPhone($userEmail, $userPhone);
			if($contactId == '')
			{
				throw new Exception(("Sorry, this contact is not registered with us"), ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			$type		 = ($isEmail) ? 1 : (($isPhone) ? 2 : 0);
			$value		 = $username;
			$ctVerify	 = new \Beans\common\ContactVerification($type, $value);

			$ctVerify->getModel();
			$isCerfAllowed = 0;
			if(property_exists($jsonObj, 'isCerf'))
			{
				$isCerfAllowed = $jsonObj->isCerf;
			}
			$otp		 = $isCerfAllowed ? rand(100001, 999999) : rand(1001, 9999);
			$otpType	 = $ctVerify->type;
			$inputValue	 = $ctVerify->value;

			$isSend = $this->dispatchOTP($otp, $otpType, $inputValue, $isCerfAllowed, Booking::Platform_App);

			if(!$isSend)
			{
				throw new Exception('Sorry, unable to send  OTP', ReturnSet::ERROR_FAILED);
			}

			$dataArr = ['otp' => $otp, 'type' => $otpType, 'value' => $inputValue];

			$jsonData					 = Filter::removeNull(json_encode($dataArr));
			$objResponse				 = new \Beans\common\AuthRequest();
			$objResponse->encodedHash	 = Filter::encrypt($jsonData);

			$returnSet->setStatus(true);
			$returnSet->setData($objResponse);
			$returnSet->setMessage('OTP sent successfully');
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function validateProvider()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$authToken	 = Yii::app()->request->getRestToken();
		try
		{
			if(!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			AppTokens::validateToken($authToken);

			$jsonObj	 = CJSON::decode($data, false);
			$username	 = $jsonObj->userName;
			$isEmail	 = Filter::validateEmail($username);
			$isPhone	 = false;

			if(!$isEmail)
			{
				$isPhone = Filter::processPhoneNumber($username);
			}

			if(!$isEmail && !$isPhone)
			{
				throw new Exception(json_encode(["Please enter valid email/phone number. Unable to validate the provider type."]), ReturnSet::ERROR_INVALID_DATA);
			}

			$tenantAppId				 = Config::get('cerf.int.sms.XTenantIDAPP.value');
			$deptId						 = Config::get("cerf.int.sms.deptId.value");
			$secret						 = Config::get("cerf.int.sms.secret.value");
			$isCerfAllowed				 = Config::get("cerf.int.sms.isAllowed.value");
			$dataArr					 = ['TENANT_ID' => $tenantAppId, 'DEPT_ID' => $deptId, 'SECRET' => $secret, 'isCerfAllowed' => ($isPhone && $isCerfAllowed) ? 1 : 0];
			$jsonData					 = json_encode($dataArr);
			$objResponse				 = new \Beans\common\AuthResponse();
			$MCryptSecurity				 = new MCryptSecurity();
			$dataEncsecret				 = $MCryptSecurity->encrypt($jsonData);
			$objResponse->encodedHash	 = base64_encode($dataEncsecret);
			$returnSet->setStatus(true);
			$returnSet->setData($objResponse);
			$returnSet->setMessage('Cerf data send successfully');
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @return type
	 * @throws Exception
	 */
	public function resendOTP()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$authToken	 = Yii::app()->request->getRestToken();
		try
		{
			if(!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}

			$jsonObj	 = CJSON::decode($data, false);
			$jsonMapper	 = new JsonMapper();

			/** @var \Beans\common\AuthRequest $obj */
			$obj = $jsonMapper->map($jsonObj, new \Beans\common\AuthRequest());

			$appModel = AppTokens::validateToken($authToken);

			$encryptedData	 = $obj->encodedHash;
			$decryptData	 = Filter::decrypt($encryptedData);
			$decryptArr		 = json_decode($decryptData);

			$value	 = $decryptArr->value;
			$type	 = $decryptArr->type;

			$ctVerify = new \Beans\common\ContactVerification($type, $value);

			if(!$ctVerify->getModel())
			{
				throw new Exception('Sorry, unable to send  OTP', ReturnSet::ERROR_FAILED);
			}

			$isCerfAllowed = 0;
			if(property_exists($jsonObj, 'isCerf'))
			{
				$isCerfAllowed = $jsonObj->isCerf;
			}

			$otp		 = $isCerfAllowed ? rand(100001, 999999) : rand(1001, 9999);
			$otpType	 = $ctVerify->type;
			$inputValue	 = $ctVerify->value;

			$isSend = $this->dispatchOTP($otp, $otpType, $inputValue, $isCerfAllowed, Booking::Platform_App);

			if(!$isSend)
			{
				throw new Exception('Sorry, unable to send  OTP', ReturnSet::ERROR_FAILED);
			}

			$dataArr = ['otp' => $otp, 'type' => $otpType, 'value' => $inputValue];

			$jsonData					 = Filter::removeNull(json_encode($dataArr));
			$objResponse				 = new \Beans\common\AuthResponse();
			$objResponse->encodedHash	 = Filter::encrypt($jsonData);

			$returnSet->setStatus(true);
			$returnSet->setData($objResponse);
			$returnSet->setMessage('OTP sent successfully');
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function dispatchOTP($otp, $otpType, $inputValue, $isCerf = 0, $platform = Booking::Platform_App)
	{

		$isSend = false;

		switch($otpType)
		{
			case 1: //sms
				$isSend	 = emailWrapper::sendOtp($inputValue, $otp);
				break;
			case 2: //email
				Filter::parsePhoneNumber($inputValue, $code, $number);
				$isSend	 = smsWrapper::sendOtp($code, $number, $otp, SmsLog::SMS_LOGIN_REGISTER, $isCerf, $platform);
				break;

			default:
				break;
		}
		return true;
	}

	public function processOTP($obj, $validate = true)
	{
		$decryptArr = $this->verifyOTP($obj);

		$userEmail	 = '';
		$userPhone	 = $decryptArr->value;
		if($decryptArr->type == 1)
		{
			$userEmail	 = $decryptArr->value;
			$userPhone	 = '';
		}

		$contactId = \Contact::getByEmailPhone($userEmail, $userPhone);

		\Contact::markVerified($contactId, $decryptArr->type, $decryptArr->value);
 

		if(!$validate && !$contactId)
		{
			return false;
		}
		if(!$contactId)
		{
			throw new Exception("Sorry, we couldn't find this data in our records", ReturnSet::ERROR_INVALID_DATA);
		}

		$userModel			 = Users::createbyContact($contactId);
		$userModel->username = $decryptArr->value;
		return $userModel;
	}

	public function verifyOTP($obj)
	{
		$encryptedData	 = $obj->encodedHash;
		$decryptData	 = Filter::decrypt($encryptedData);
		$otp			 = $obj->password; //otpSent
		$decryptArr		 = json_decode($decryptData);
		$decryptOtp		 = $decryptArr->otp;  //otpDecrypted
		if($decryptOtp != $otp)
		{
			throw new Exception("OTP Mistmached. Please try again", ReturnSet::ERROR_INVALID_DATA);
		}
		return $decryptArr;
	}

	public function getJWToken($userModel, $deviceModel, $userType = UserInfo::TYPE_VENDOR, $identity = false)
	{
		if(!$identity){
			$identity = $this->authenticateUser($userModel);
		}
		//	$contactData = ContactProfile::getEntitybyUserId($userModel->user_id);

		Logger::trace("UserModel: " . json_encode($userModel));

		$refContactData	 = ContactProfile::getEntitybyUserId($userModel->user_id);
		$contactData	 = ContactProfile::getPrimaryEntitiesByContact($refContactData['ctt_id']);
		Logger::trace("contactData: " . json_encode($contactData));

		if(empty($contactData))
		{
			throw new Exception("Unable to link user", ReturnSet::ERROR_FAILED);
		}
		$entityId = null;
		if($userType == UserInfo::TYPE_CONSUMER && $contactData['cr_is_consumer'] > 0)
		{
			$entityId = $contactData['cr_is_consumer'];
		}
		if($userType == UserInfo::TYPE_VENDOR && $contactData['cr_is_vendor'] > 0)
		{
			$entityId = $contactData['cr_is_vendor'];
		}

		if($userType == UserInfo::TYPE_DRIVER && $contactData['cr_is_driver'] > 0)
		{
			$entityId = $contactData['cr_is_driver'];
		}
		if($entityId == null)
		{
			throw new Exception("Unable to link entity", ReturnSet::ERROR_FAILED);
		}

		if($entityId > 0)
		{
			$identity->setEntityID($entityId);
			$deviceModel->apt_entity_id = $entityId;
		}

		$identity->setUserType($userType);

		$webUser = Yii::app()->user;
		$webUser->login($identity);

		$userInfo					 = new UserInfo($userType, $userModel->user_id);
		$deviceModel->apt_user_type	 = $userType;
//		$deviceModel->apt_entity_id	 = $entityId;
		$deviceModel->save();
		$appModel					 = AppTokens::registerToken($userInfo, $deviceModel);
		$appToken					 = $appModel->apt_token_id;
		$JWToken					 = JWTokens::generateToken($appToken);
		return $JWToken;
	}

	public function validateSession()
	{
		$returnSet	 = new ReturnSet();
		$JWToken	 = Yii::app()->request->getAuthorizationHeader(false);
		try
		{
			$userInfo = UserInfo::getInstance();
			if($userInfo->userId > 0)
			{
				$returnSet->setStatus(true);
			}
			else
			{
				$approvalStatus = 0;
				goto registerUrl;
			}

//			$contactData = ContactProfile::getEntitybyUserId($userInfo->userId);

			$refContactData	 = ContactProfile::getEntitybyUserId($userInfo->userId);
			$contactData	 = ContactProfile::getPrimaryEntitiesByContact($refContactData['ctt_id']);

			$approvalStatus = 1;

			$vndId	 = $contactData['cr_is_vendor'];
			$drvId	 = $contactData['cr_is_driver'];

			$vndModel = Vendors::model()->findByPk($vndId);

			/* if ($drvId > 0 && (!$vndModel || ($vndModel->vnd_active != 1)))
			  {
			  $drvStatus		 = Drivers::model()->findByPk($drvId)->drv_approved;
			  $approvalStatus	 = ($drvStatus == 1) ? 1 : 0;

			  $drvId = $this->getDriverId();

			  goto registerUrl;
			  }

			  if (!$vndId || $vndId == '')
			  {

			  $approvalStatus = 0;
			  goto registerUrl;
			  }
			  if (!$drvId || $drvId == '')
			  {

			  $approvalStatus = 0;
			  goto registerUrl;
			  }

			  //
			  $vndStatus = Vendors::model()->findByPk($vndId)->vnd_active;

			  if (!in_array($vndStatus, [1, 2]))
			  {
			  $approvalStatus = 0;
			  goto registerUrl;
			  }
			 */
			if($vndId > 0)
			{
				$userType		 = UserInfo::TYPE_VENDOR;
				$approvalStatus	 = VendorDriver::checkApprovalStatus($vndId);
				goto registerUrl;
			}

			if($drvId > 0)
			{
				$userType		 = UserInfo::TYPE_DRIVER;
				$drvId			 = $contactData['cr_is_driver'];
				$drvStatus		 = Drivers::model()->findByPk($drvId)->drv_approved;
				$approvalStatus	 = ($drvStatus == 1) ? 1 : 0;
				goto registerUrl;
			}
			if(!$drvId || $drvId == '')
			{
				$userType		 = ($vndId > 0) ? $userType : UserInfo::TYPE_CONSUMER;
				$approvalStatus	 = 0;
				goto registerUrl;
			}

			registerUrl:
			$resArr = [];

			$res = JWTokens::validateAppToken($JWToken);

			$entityid = UserInfo::getEntityId();
			if(!$entityid || !$res->aud)
			{
				$entityId = AppTokens::getEntity($res->token);

				if($entityId > 0)
				{
					$JWToken = JWTokens::generateToken($res->token);
				}
			}
			$resArr['jwtoken'] = $JWToken;

			if($contactData)
			{
				$userSession		 = new \Beans\common\UserSession();
				$userSession->setLoginResponse($contactData);
				$resArr['profile']	 = $userSession;
			}

			$resArr['approvalStatus'] = $approvalStatus;
			if($approvalStatus == 0)
			{
				$resArr['url'] = $this->getUrlByJwt($JWToken);
			}
			$phoneData = \ContactPhone::getPrimaryNumber($contactData['cr_contact_id'], true);
			if(!$phoneData)
			{
				$resArr['missingData'][] = 1;
			}

			$authResponse = new \Beans\common\AuthResponse();
			$authResponse->setData($resArr);

			$returnSet->setData($authResponse);
		}
		catch(Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function validateSessionV1()
	{
		$returnSet	 = new ReturnSet();
		$JWToken	 = Yii::app()->request->getAuthorizationHeader(false);
		try
		{
			$userInfo = UserInfo::getInstance();
			if($userInfo->userId > 0)
			{
				$returnSet->setStatus(true);
			}
			else
			{
				$approvalStatus = 0;
				goto registerUrl;
			}

//			$contactData	 = ContactProfile::getEntitybyUserId($userInfo->userId);

			$refContactData	 = ContactProfile::getEntitybyUserId($userInfo->userId);
			$contactData	 = ContactProfile::getPrimaryEntitiesByContact($refContactData['ctt_id']);

			$approvalStatus = 1;

			$vndId	 = $contactData['cr_is_vendor'];
			$drvId	 = $contactData['cr_is_driver'];

			$vndModel = Vendors::model()->findByPk($vndId);

			if($vndId > 0)
			{
				$userType		 = UserInfo::TYPE_VENDOR;
				$approvalStatus	 = VendorDriver::checkApprovalStatus($vndId);
				goto registerUrl;
			}

			if($drvId > 0)
			{
				$userType		 = UserInfo::TYPE_DRIVER;
				$drvId			 = $contactData['cr_is_driver'];
				$drvStatus		 = Drivers::model()->findByPk($drvId)->drv_approved;
				$approvalStatus	 = ($drvStatus == 1) ? 1 : 0;
				goto registerUrl;
			}
			if(!$drvId || $drvId == '')
			{
				$userType		 = ($vndId > 0) ? $userType : UserInfo::TYPE_CONSUMER;
				$approvalStatus	 = 0;
				goto registerUrl;
			}

			registerUrl:
			$resArr = [];

			$res = JWTokens::validateAppToken($JWToken);

			$entityid = UserInfo::getEntityId();
			if(!$entityid || !$res->aud)
			{
				$entityId = AppTokens::getEntity($res->token);

				if($entityId > 0)
				{
					$JWToken = JWTokens::generateToken($res->token);
				}
			}
			$resArr['jwtoken'] = $JWToken;

			if($contactData)
			{
				$userProfile		 = new \Beans\common\UserSession();
				$userProfile->setProfileV1($contactData);
				$resArr['profile']	 = $userProfile;
			}

			$resArr['approvalStatus'] = $approvalStatus;
			if($approvalStatus == 0)
			{
				$resArr['url'] = $this->getUrlByJwt($JWToken);
			}
			$phoneData = \ContactPhone::getPrimaryNumber($contactData['cr_contact_id'], true);
			if(!$phoneData)
			{
				$resArr['missingData'][] = 1;
			}

			$authResponse = new \Beans\common\AuthResponse();
			$authResponse->setData($resArr);

			$returnSet->setData($authResponse);
		}
		catch(Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function getProfile()
	{
		$returnSet = new ReturnSet();
		try
		{
			$userInfo		 = UserInfo::getInstance();
//			$contactData	 = ContactProfile::getEntitybyUserId($userInfo->userId);
			$refContactData	 = ContactProfile::getEntitybyUserId($userInfo->userId);
			$contactData	 = ContactProfile::getPrimaryEntitiesByContact($refContactData['ctt_id']);

			$userProfile = new \Beans\common\UserSession();
			$userProfile->setProfile($contactData);
			$returnSet->setData($userProfile);
			$returnSet->setStatus(true);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function getProfileV1()
	{
		$returnSet = new ReturnSet();
		try
		{
			$userInfo		 = UserInfo::getInstance();
//			$contactData = ContactProfile::getEntitybyUserId($userInfo->userId);
			$refContactData	 = ContactProfile::getEntitybyUserId($userInfo->userId);
			$contactData	 = ContactProfile::getPrimaryEntitiesByContact($refContactData['ctt_id']);

			$userProfile = new \Beans\common\UserSession();
			$userProfile->setProfileV1($contactData);
			$returnSet->setData($userProfile);
			$returnSet->setStatus(true);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function validateVersion()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$authToken	 = Yii::app()->request->getRestToken();

		try
		{
			if($authToken)
			{
				$appModel = AppTokens::validateToken($authToken);
			}

			if(!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj		 = CJSON::decode($data, false);
			$activeVersion	 = Config::get("Version.Android.dco");
			if(!(version_compare($jsonObj->apkVersion, $activeVersion) < 0))
			{
				$returnSet->setStatus(true);
			}
		}
		catch(Exception $ex)
		{
			if($ex->getCode() == 401)
			{
				throw new CHttpException($ex->getCode(), $ex->getMessage());
			}
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function socialLinking()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		try
		{
			if(!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj = CJSON::decode($data, false);

			$jsonMapper	 = new JsonMapper();
			$objAuth	 = new \Beans\common\SocialResponse();

			/** @var \Beans\common\SocialResponse $obj */
			$obj = $jsonMapper->map($jsonObj, $objAuth);

			$authProfile = $obj->getSocialProfile();

			$email	 = $authProfile->email;
			$cttid	 = ContactEmail::getLinkedContactIds($email);

			$vndId		 = UserInfo::getEntityId();
			$vndCttId	 = \ContactProfile::getByVndId($vndId);

			if(!$cttid)
			{
				$sAuthModel	 = $obj->initiateSocialAuth();
				$token		 = $obj->accessToken;

				$oAuthModel = $sAuthModel->linkContact($token, $vndCttId);
				ContactEmail::model()->updateEmailByContact($email, $vndCttId);

				if(!$oAuthModel)
				{
					throw new Exception("Failed to link social account", ReturnSet::ERROR_FAILED);
				}
			}
			else if($vndCttId == $cttid)
			{
				$sAuthModel	 = $obj->initiateSocialAuth();
				$token		 = $obj->accessToken;
				$userId		 = UserInfo::getUserId();

				$oAuth = $sAuthModel->findByIdentifier($obj->provider, $obj->identifier);
				if(!$oAuth)
				{
					$oAuthModel = $sAuthModel->linkUser($token, $userId);
					if(!$oAuthModel)
					{
						throw new Exception("Failed to link social account", ReturnSet::ERROR_FAILED);
					}
				}
			}
			else if($cttid)
			{
				throw new Exception("Email already linked ", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}
			else
			{
				throw new Exception("Some error occured", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}

			$returnSet->setStatus(true);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function logout()
	{
		$returnSet = new ReturnSet();
		try
		{
//////////////////
//			$userId		 = UserInfo::getUserId();
//			$user_type	 = UserInfo::getUserType();
//			$res		 = AppTokens::model()->deactivateByUserIdandUserType($userId, $user_type);
//			Yii::app()->user->logout();
//			$returnSet->setStatus(true);
//			return;
///////////

			$jwtToken	 = Yii::app()->request->getAuthorizationHeader(false);
			$success	 = AppTokens::logoutDCO($jwtToken);
			if(!$success)
			{
				throw new Exception("Error in logout", ReturnSet::ERROR_INVALID_DATA);
			}
			Yii::app()->user->logout();
			$returnSet->setStatus(true);
			$returnSet->setMessage("Logged out successfully");
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function getNotificationStatus()
	{
		$returnSet = new ReturnSet();
		try
		{
			$vndId				 = $this->getVendorId();
			$notificationStatus	 = VendorPref::getGNowNotificationStatus($vndId);
			$returnSet->setStatus($notificationStatus);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function modifyNotificationStatus()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$jsonObj	 = \CJSON::decode($data, false);
		try
		{
			if(empty($data))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			$vndId = $this->getVendorId();

			$gnowStatus	 = $jsonObj->gnowStatus;
			$snoozeTime	 = $jsonObj->snoozeTime;

			$stat = \VendorPref::updateGnowStatus($vndId, $gnowStatus, $snoozeTime);
			if($stat)
			{
				$message = "Notication flag status modified";
				$returnSet->setStatus(true);
				$returnSet->setMessage($message);
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	public function locationUpdate()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;

		try
		{
			if(!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj = CJSON::decode($data, false);

			$jsonMapper = new JsonMapper();

			/** @var \Beans\common\Location $obj */
			$obj = $jsonMapper->map($jsonObj, new \Beans\common\Location());

			$jwtToken	 = Yii::app()->request->getAuthorizationHeader(false);
			Logger::trace("jwtToken: {$jwtToken}");
			/** @var \AppTokens $appRecord */
			$appRecord	 = \AppTokens::getModelByJWT($jwtToken);
			if(!$appRecord)
			{
				throw new Exception("Device not recognised", ReturnSet::ERROR_INVALID_DATA);
			}
			$device		 = $appRecord->apt_device_uuid;
			$vndId		 = $this->getVendorId(false);
			$returnSet	 = \VendorStats::updateLastLocationDCO($obj, $device, $vndId);
		}
		catch(Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}

		return $returnSet;
	}

	public function setPrefLanguage()
	{

		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$jsonObj	 = CJSON::decode($data, false);
		try
		{
			if(empty($data))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			$preferredLanguage = $jsonObj->id;

			$cttModel = \Contact::model()->findByPk($this->getContactId());

			$cttModel->ctt_preferred_language	 = $preferredLanguage;
			$success							 = $cttModel->save();
			if($success)
			{
				$returnSet->setMessage("Preferred language updated successfully");
			}
			$returnSet->setStatus($success);
		}
		catch(Exception $ex)
		{
			$returnSet = \ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function getLanguageList()
	{

		$returnSet = new ReturnSet();
		try
		{
			$langList	 = \Contact::languageList();
			$langArr	 = [];
			foreach($langList as $lang)
			{
				$langArr[] = \Beans\common\ValueObject::setIdlabel($lang['id'], $lang['text'], $lang['val']);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($langArr);
		}
		catch(Exception $ex)
		{
			$returnSet = \ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function isExisting()
	{
		$returnSet		 = new ReturnSet();
		$jwtToken		 = Yii::app()->request->getAuthorizationHeader(false);
		$userLogged		 = false;
		$isExistingUser	 = false;
		$approvalStatus	 = 0;
		if($jwtToken)
		{
			/** @var \AppTokens $appRecord */
			$appRecord		 = \AppTokens::getModelByJWT($jwtToken);
			$authToken		 = $appRecord->apt_token_id;
			$userLogged		 = true;
			$isExistingUser	 = true;
			$approvalStatus	 = 1;
		}
		else
		{
			$authToken = Yii::app()->request->getRestToken();
		}
		$data = Yii::app()->request->rawBody;

		try
		{
			if(!$data)
			{
				throw new Exception("no request found", \ReturnSet::ERROR_INVALID_DATA);
			}
			if(!$authToken)
			{
				throw new Exception("no token found", \ReturnSet::ERROR_INVALID_DATA);
			}

			/** @var AppTokens $appModel */
			$appModel = AppTokens::validateToken($authToken);

			$jsonObj	 = \CJSON::decode($data, false);
			$jsonMapper	 = new JsonMapper();

			/** @var \Beans\common\AuthRequest $obj */
			$obj = $jsonMapper->map($jsonObj, new \Beans\common\AuthRequest());

			$username	 = $obj->userName;
			$isPhone	 = \Filter::validatePhoneNumber($username);

			if(!$isPhone)
			{
				throw new Exception(json_encode(["Please enter your valid phone number."]), ReturnSet::ERROR_INVALID_DATA);
			}
			$userEmail	 = '';
			$type		 = 2;
			$userPhone	 = $username;

			// 
			###Profile Block Start###
			$contactId = \Contact::getByEmailPhone($userEmail, $userPhone);

			if($contactId == '')
			{
				goto skipProfile;
			}


			if($userLogged)
			{

				$loggedCttid = $this->getContactId();
//				$phoneData	 = \ContactPhone::getPrimaryNumber($contactId, true);
				if($contactId != '' && $loggedCttid != $contactId)
				{
					throw new Exception(json_encode(["Sorry, this number is linked with other user"]), ReturnSet::ERROR_VALIDATION);
				}
			}


			$contactData = \ContactProfile::getCodeByCttId($contactId);

			if($contactData['cr_is_consumer'] > 0)
			{

				$appModel->apt_user_id	 = $contactData['cr_is_consumer'];
				$appModel->save();
				$isExistingUser			 = true;
			}

			if($contactData['cr_is_driver'] > 0 && !$contactData['cr_is_vendor'])
			{
				$drvId			 = $contactData['cr_is_driver'];
				$drvStatus		 = Drivers::model()->findByPk($drvId)->drv_approved;
				$approvalStatus	 = ($drvStatus == 1) ? 1 : 0;

				goto skipCheck;
			}



			if(!$contactData['cr_is_vendor'] || $contactData['cr_is_vendor'] == '')
			{
				$approvalStatus = 0;
				goto skipProfile;
				//throw new Exception("No vendor is linked with the contact", ReturnSet::ERROR_VALIDATION);
			}
			if(!$contactData['cr_is_driver'] || $contactData['cr_is_driver'] == '')
			{
				$approvalStatus = 0;
				goto skipProfile;
				//throw new Exception("No driver is linked with the contact", ReturnSet::ERROR_VALIDATION);
			}



			$vndId		 = $contactData['cr_is_vendor'];
			$vndStatus	 = Vendors::model()->findByPk($vndId)->vnd_active;

			if(!in_array($vndStatus, [1, 2]))
			{
				$approvalStatus = 0;
				goto skipProfile;
			}
			skipCheck:
			$approvalStatus = 1;

			$isExistingUser			 = true;
			$userProfile			 = new \Beans\common\UserSession();
			$userProfile->setBasicUserProfile($contactData);
			$objResponse			 = new \Beans\common\AuthResponse();
			$objResponse->profile	 = $userProfile;

			$phoneData = \ContactPhone::getPrimaryNumber($contactId);
			if(!$phoneData)
			{
				$objResponse->missingData[] = 1;
			}
			###Profile Block End###
			skipProfile:



			###SMS Block Start###
			$ctVerify = new \Stub\common\ContactVerification($type, $username);
			$ctVerify->getModel();

			$isCerfAllowed = 0;
			if(property_exists($jsonObj, 'isCerf'))
			{
				$isCerfAllowed = $jsonObj->isCerf;
			}
			$otp		 = $isCerfAllowed ? rand(100001, 999999) : rand(1001, 9999);
			$otpType	 = $ctVerify->type;
			$inputValue	 = $ctVerify->value;

			$isSend = $this->dispatchOTP($otp, $otpType, $inputValue, $isCerfAllowed, Booking::Platform_App);
			if(!$isSend)
			{
				throw new Exception('Sorry, unable to send  OTP', ReturnSet::ERROR_FAILED);
			}
			$dataArr = ['otp' => $otp, 'type' => $otpType, 'value' => $inputValue];

			$jsonData					 = \Filter::removeNull(json_encode($dataArr));
			$objResponse				 = new \Beans\common\AuthResponse();
			$objResponse->encodedHash	 = \Filter::encrypt($jsonData);

			###SMS Block End###



			$dataExist = Contact::searchTempContactsByPhone($userPhone);
			if($dataExist)
			{
				$userProfile			 = new \Beans\common\UserSession();
				$userProfile->setTempUserProfile($dataExist);
				$objResponse->profile	 = $userProfile;
			}

			$objResponse->isExistingUser = $isExistingUser;
			$objResponse->approvalStatus = $approvalStatus;
			$returnSet->setStatus(true);
			$returnSet->setData($objResponse);
			$returnSet->setMessage('OTP sent successfully');

			##Start Check temp contact
			$isExist = \Contact::checkExistingTempContacts($userPhone);
			if($isExist)
			{
				\Contact::updateTempContactsAttemptedByPhone($userPhone);
			}
			##End temp contact
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function isExistingV1()
	{
		$returnSet		 = new ReturnSet();
		$jwtToken		 = Yii::app()->request->getAuthorizationHeader(false);
		$userLogged		 = false;
		$isExistingUser	 = false;
		$approvalStatus	 = 0;
		if($jwtToken)
		{
			/** @var \AppTokens $appRecord */
			$appRecord		 = \AppTokens::getModelByJWT($jwtToken);
			$authToken		 = $appRecord->apt_token_id;
			$userLogged		 = true;
			$isExistingUser	 = true;
			$approvalStatus	 = 1;
		}
		else
		{
			$authToken = Yii::app()->request->getRestToken();
		}
		$data = Yii::app()->request->rawBody;

		try
		{
			if(!$data)
			{
				throw new Exception("no request found", \ReturnSet::ERROR_INVALID_DATA);
			}
			if(!$authToken)
			{
				throw new Exception("no token found", \ReturnSet::ERROR_INVALID_DATA);
			}

			/** @var AppTokens $appModel */
			$appModel = AppTokens::validateToken($authToken);

			$jsonObj	 = \CJSON::decode($data, false);
			$jsonMapper	 = new JsonMapper();

			/** @var \Beans\common\AuthRequest $obj */
			$obj = $jsonMapper->map($jsonObj, new \Beans\common\AuthRequest());

			$username	 = $obj->userName;
			$isPhone	 = \Filter::validatePhoneNumber($username);

			if(!$isPhone)
			{
				throw new Exception(json_encode(["Please enter your valid phone number."]), ReturnSet::ERROR_INVALID_DATA);
			}
			$userEmail	 = '';
			$type		 = 2;
			$userPhone	 = $username;

			// 
			###Profile Block Start###
			$contactId = \Contact::getByEmailPhone($userEmail, $userPhone);

			if($contactId == '')
			{
				goto skipProfile;
			}


			if($userLogged)
			{

				$loggedCttid = $this->getContactId();
//				$phoneData	 = \ContactPhone::getPrimaryNumber($contactId, true);
				if($contactId != '' && $loggedCttid != $contactId)
				{
					throw new Exception(json_encode(["Sorry, this number is linked with other user"]), ReturnSet::ERROR_VALIDATION);
				}
			}


			$contactData = \ContactProfile::getCodeByCttId($contactId);

			if($contactData['cr_is_consumer'] > 0)
			{

				$appModel->apt_user_id	 = $contactData['cr_is_consumer'];
				$appModel->save();
				$isExistingUser			 = true;
			}

			if($contactData['cr_is_driver'] > 0 && !$contactData['cr_is_vendor'])
			{
				$drvId			 = $contactData['cr_is_driver'];
				$drvStatus		 = Drivers::model()->findByPk($drvId)->drv_approved;
				$approvalStatus	 = ($drvStatus == 1) ? 1 : 0;

				goto skipCheck;
			}



			if(!$contactData['cr_is_vendor'] || $contactData['cr_is_vendor'] == '')
			{
				$approvalStatus = 0;
				goto skipProfile;
				//throw new Exception("No vendor is linked with the contact", ReturnSet::ERROR_VALIDATION);
			}
			if(!$contactData['cr_is_driver'] || $contactData['cr_is_driver'] == '')
			{
				$approvalStatus = 0;
				goto skipProfile;
				//throw new Exception("No driver is linked with the contact", ReturnSet::ERROR_VALIDATION);
			}



			$vndId		 = $contactData['cr_is_vendor'];
			$vndStatus	 = Vendors::model()->findByPk($vndId)->vnd_active;

			if(!in_array($vndStatus, [1, 2]))
			{
				$approvalStatus = 0;
				goto skipProfile;
			}
			skipCheck:
			$approvalStatus = 1;

			$isExistingUser			 = true;
			$userProfile			 = new \Beans\common\UserSession();
			$userProfile->setBasicUserProfile($contactData);
			$objResponse			 = new \Beans\common\AuthResponse();
			$objResponse->profile	 = $userProfile;

			$phoneData = \ContactPhone::getPrimaryNumber($contactId);
			if(!$phoneData)
			{
				$objResponse->missingData[] = 1;
			}
			###Profile Block End###
			skipProfile:



			###SMS Block Start###
			$ctVerify = new \Stub\common\ContactVerification($type, $username);
			$ctVerify->getModel();

			$isCerfAllowed = 0;
			if(property_exists($jsonObj, 'isCerf'))
			{
				$isCerfAllowed = $jsonObj->isCerf;
			}
			$otp		 = $isCerfAllowed ? rand(100001, 999999) : rand(1001, 9999);
			$otpType	 = $ctVerify->type;
			$inputValue	 = $ctVerify->value;

			$isSend = $this->dispatchOTP($otp, $otpType, $inputValue, $isCerfAllowed, Booking::Platform_App);
			if(!$isSend)
			{
				throw new Exception('Sorry, unable to send  OTP', ReturnSet::ERROR_FAILED);
			}
			$dataArr = ['otp' => $otp, 'type' => $otpType, 'value' => $inputValue];

			$jsonData					 = \Filter::removeNull(json_encode($dataArr));
			$objResponse				 = new \Beans\common\AuthResponse();
			$objResponse->encodedHash	 = \Filter::encrypt($jsonData);

			###SMS Block End###



			$dataExist = Contact::searchTempContactsByPhone($userPhone);
			if($dataExist)
			{

				$userProfile			 = new \Beans\common\UserSession();
				$userProfile->setProfileV1($contactData);
				$objResponse->profile	 = $userProfile;
			}

			$objResponse->isExistingUser = $isExistingUser;
			$objResponse->approvalStatus = $approvalStatus;
			$returnSet->setStatus(true);
			$returnSet->setData($objResponse);
			$returnSet->setMessage('OTP sent successfully');

			##Start Check temp contact
			$isExist = \Contact::checkExistingTempContacts($userPhone);
			if($isExist)
			{
				\Contact::updateTempContactsAttemptedByPhone($userPhone);
			}
			##End temp contact
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function validateOtp()
	{
		$returnSet = new ReturnSet();

		$jwtToken	 = Yii::app()->request->getAuthorizationHeader(false);
		$userLogged	 = false;
		if($jwtToken)
		{
			/** @var \AppTokens $appRecord */
			$appRecord	 = \AppTokens::getModelByJWT($jwtToken);
			$authToken	 = $appRecord->apt_token_id;
			$userLogged	 = true;
		}
		else
		{

			$authToken = Yii::app()->request->getRestToken();
		}
		$data = Yii::app()->request->rawBody;

		try
		{
			if(!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			if(!$authToken)
			{
				throw new Exception("no token found", \ReturnSet::ERROR_INVALID_DATA);
			}

			$jsonObj	 = CJSON::decode($data, false);
			$jsonMapper	 = new JsonMapper();
			/** @var \Beans\common\AuthRequest $obj */
			$objReg		 = $jsonMapper->map($jsonObj, new \Beans\contact\Register());

			$obj = $objReg->auth;

			$deviceObj	 = new \Beans\common\DeviceInfo();
			$appModel	 = AppTokens::validateToken($authToken);

			$deviceObj->setData($appModel);
			$obj->device	 = $deviceObj;
			$approvalStatus	 = 1;
			if($userLogged)
			{
				$decryptArr = $this->verifyOTP($obj);

				$userPhone	 = $decryptArr->value;
				$userId		 = UserInfo::getUserId();
				$userModel	 = Users::model()->findByPk($userId);
			}
			else
			{
				$userModel = $this->processOTP($obj, false);
			}

			$resArr = [];
			if(!$userModel)
			{
				$approvalStatus = 0;
				goto registerUrl;
			}

//			$contactData = ContactProfile::getEntitybyUserId($userModel->user_id);

			$refContactData	 = \ContactProfile::getEntitybyUserId($userModel->user_id);
			$contactData	 = \ContactProfile::getPrimaryEntitiesByContact($refContactData['ctt_id']);

			$cttId = $contactData['cr_contact_id'];
			if($userLogged)
			{
				Filter::parsePhoneNumber($userPhone, $code, $number);

				$conModel							 = ContactPhone::model();
				$conModel->phn_phone_no				 = $number;
				$conModel->phn_phone_country_code	 = $code;
				$primaryPhone						 = $conModel->validatePrimary($cttId, $number);
				$returnSet							 = ContactPhone::model()->add($cttId, $number, 1, $code, 1, $primaryPhone, 1, 1);

				Logger::profile("DCO usercontroller::validateOtp phone added : " . json_encode($returnSet));
			}

			$userType = UserInfo::TYPE_VENDOR;
			if($contactData['cr_is_driver'] > 0 && !$contactData['cr_is_vendor'])
			{
				$userType	 = UserInfo::TYPE_DRIVER;
				$JWToken	 = $this->getJWToken($userModel, $appModel, $userType);

				$drvId			 = $this->getDriverId();
				$drvStatus		 = Drivers::model()->findByPk($drvId)->drv_approved;
				$approvalStatus	 = ($drvStatus == 1) ? 1 : 0;
				if($approvalStatus == 0)
				{
					goto userModel;
				}
				goto skipVendor;
			}


			if($contactData['cr_is_driver'] == '')
			{
				$approvalStatus = 0;
				goto userModel;
			}
			if($contactData['cr_is_vendor'] == '')
			{
				$approvalStatus = 0;
				goto userModel;
			}
			$vndId		 = $contactData['cr_is_vendor'];
			$vndStatus	 = Vendors::model()->findByPk($vndId)->vnd_active;

			/** @var \Contact $cttModel */
			if(!in_array($vndStatus, [1, 2]))
			{
				$approvalStatus = 0;
				goto userModel;
			}


			#Registered user Block Start 
			$JWToken = $this->getJWToken($userModel, $appModel, $userType);

			skipVendor:

			$userSession = new \Beans\common\UserSession();
			$userSession->setLoginResponse($contactData, 2);

			$resArr['jwtoken']	 = $JWToken;
			$resArr['profile']	 = $userSession;

			$returnSet->setData($resArr);
			Logger::profile("response : " . json_encode($resArr));

			goto skipAll;
			#Un-registered user Block Start

			registerUrl:
			$encryptedData	 = $obj->encodedHash;
			$decryptData	 = Filter::decrypt($encryptedData);
			$objReg->updateProfileData($decryptData);

			/** @var \Beans\contact\Register $objReg */
			$cttModel = $objReg->getContactModel();

			$transaction = DBUtil::beginTransaction();

			$returnSet = $cttModel->create(true, UserInfo::TYPE_CONSUMER);
			if(!$returnSet->isSuccess())
			{
				throw new Exception("Sorry, unable to create your accounts", ReturnSet::ERROR_FAILED);
			}
			$userModel = Users::createbyContact($cttModel->ctt_id);
			DBUtil::commitTransaction($transaction);
			#User model
			userModel:

			$appToken				 = $appModel->apt_token_id;
			$appModel->apt_user_id	 = $userModel->user_id;
			$appModel->save();
//			exit;

			generateUrl;
			$JWToken			 = \JWTokens::generateToken($appToken);
			$resArr['jwtoken']	 = $JWToken;
			$resArr['url']		 = $this->getUrlByJwt($JWToken);

			skipAll:
			$resArr['approvalStatus'] = $approvalStatus;

			$authResponse = new \Beans\common\AuthResponse();
			$authResponse->setData($resArr);

			$returnSet->setData($authResponse);

			$returnSet->setStatus(true);

			##Start Check temp contact
			$cttModel	 = \Contact::model()->getByUserId($userModel->user_id);
			$phone		 = $cttModel->contactPhones[0]->phn_phone_no;
			$isExist	 = \Contact::checkExistingTempContacts($phone);
			if($isExist)
			{
				\Contact::updateTempContactsRegisteredByPhone($phone, $cttModel->ctt_id);
			}
			##end Check temp contact
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function validateOtpV1()
	{
		$returnSet = new ReturnSet();

		$jwtToken	 = Yii::app()->request->getAuthorizationHeader(false);
		$userLogged	 = false;
		if($jwtToken)
		{
			/** @var \AppTokens $appRecord */
			$appRecord	 = \AppTokens::getModelByJWT($jwtToken);
			$authToken	 = $appRecord->apt_token_id;
			$userLogged	 = true;
		}
		else
		{

			$authToken = Yii::app()->request->getRestToken();
		}
		$data = Yii::app()->request->rawBody;

		try
		{
			if(!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			if(!$authToken)
			{
				throw new Exception("no token found", \ReturnSet::ERROR_INVALID_DATA);
			}

			$jsonObj	 = CJSON::decode($data, false);
			$jsonMapper	 = new JsonMapper();
			/** @var \Beans\common\AuthRequest $obj */
			$objReg		 = $jsonMapper->map($jsonObj, new \Beans\contact\Register());

			$obj = $objReg->auth;

			$deviceObj	 = new \Beans\common\DeviceInfo();
			$appModel	 = AppTokens::validateToken($authToken);

			$deviceObj->setData($appModel);
			$obj->device	 = $deviceObj;
			$approvalStatus	 = 1;
			if($userLogged)
			{
				$decryptArr = $this->verifyOTP($obj);

				$userPhone	 = $decryptArr->value;
				$userId		 = UserInfo::getUserId();
				$userModel	 = Users::model()->findByPk($userId);
			}
			else
			{
				$userModel = $this->processOTP($obj, false);
			}

			$resArr = [];
			if(!$userModel)
			{
				$approvalStatus = 0;
				goto registerUrl;
			}

//			$contactData = ContactProfile::getEntitybyUserId($userModel->user_id);

			$refContactData	 = ContactProfile::getEntitybyUserId($userModel->user_id);
			$contactData	 = ContactProfile::getPrimaryEntitiesByContact($refContactData['ctt_id']);

			$cttId = $contactData['cr_contact_id'];
			if($userLogged)
			{
				Filter::parsePhoneNumber($userPhone, $code, $number);

				$conModel							 = ContactPhone::model();
				$conModel->phn_phone_no				 = $number;
				$conModel->phn_phone_country_code	 = $code;
				$primaryPhone						 = $conModel->validatePrimary($cttId, $number);
				$returnSet							 = ContactPhone::model()->add($cttId, $number, 1, $code, 1, $primaryPhone, 1, 1);

				Logger::profile("DCO usercontroller::validateOtp phone added : " . json_encode($returnSet));
			}

			$userType = UserInfo::TYPE_VENDOR;
			if($contactData['cr_is_driver'] > 0 && !$contactData['cr_is_vendor'])
			{
				$userType	 = UserInfo::TYPE_DRIVER;
				$JWToken	 = $this->getJWToken($userModel, $appModel, $userType);

				$drvId			 = $this->getDriverId();
				$drvStatus		 = Drivers::model()->findByPk($drvId)->drv_approved;
				$approvalStatus	 = ($drvStatus == 1) ? 1 : 0;
				$drvStat		 = Drivers::model()->findByPk($drvId)->drv_is_freeze;
				if($drvStat == 1)
				{
					$driverBlock				 = 1;
					$contactData['cr_is_driver'] = 0;
				}
				if($approvalStatus == 0)
				{
					goto userModel;
				}
				goto skipVendor;
			}


			/* if($contactData['cr_is_driver'] == '')
			  {
			  $approvalStatus = 0;
			  goto userModel;
			  }
			  if($contactData['cr_is_vendor'] == '')
			  {
			  $approvalStatus = 0;
			  goto userModel;
			  } */
			$vndId		 = $contactData['cr_is_vendor'];
			$vndStatus	 = Vendors::model()->findByPk($vndId)->vnd_active;
			if($vndStatus == 2)
			{
				$vendorBlock				 = 1;
				//throw new Exception(json_encode(["Unable to login, your account is blocked."]), ReturnSet::ERROR_VALIDATION);
				$contactData['cr_is_vendor'] = 0;
				$approvalStatus				 = 0;
				goto userModel;
			}

			/** @var \Contact $cttModel */
			/* if(!in_array($vndStatus, [1, 2]))
			  {
			  $approvalStatus = 0;
			  goto userModel;
			  }
			 */

			#Registered user Block Start 
			$JWToken = $this->getJWToken($userModel, $appModel, $userType);

			skipVendor:

			/*			 * $userSession = new \Beans\common\UserSession();
			  $userSession->setLoginResponse($contactData, 2);* */
			$userProfile = new \Beans\common\UserSession();
			$userProfile->setProfileV1($contactData);

			$resArr['jwtoken']	 = $JWToken;
			$resArr['profile']	 = $userProfile;

			$returnSet->setData($resArr);
			Logger::profile("response : " . json_encode($resArr));

			goto skipAll;
			#Un-registered user Block Start

			registerUrl:
			$encryptedData	 = $obj->encodedHash;
			$decryptData	 = Filter::decrypt($encryptedData);
			$objReg->updateProfileData($decryptData);

			/** @var \Beans\contact\Register $objReg */
			$cttModel = $objReg->getContactModel();

			$transaction = DBUtil::beginTransaction();

			$returnSet = $cttModel->create(true, UserInfo::TYPE_CONSUMER);
			if(!$returnSet->isSuccess())
			{
				throw new Exception("Sorry, unable to create your accounts", ReturnSet::ERROR_FAILED);
			}
			$userModel = Users::createbyContact($cttModel->ctt_id);
			DBUtil::commitTransaction($transaction);
			#User model
			userModel:
			if($vendorBlock == 1 && $driverBlock == 1)
			{
				throw new Exception(json_encode(["Unable to login, your account is blocked."]), ReturnSet::ERROR_VALIDATION);
			}
			if($vndId < 1 && $driverBlock == 1)
			{
				throw new Exception(json_encode(["Unable to login, your account is blocked."]), ReturnSet::ERROR_VALIDATION);
			}
			if($drvId < 1 && $vendorBlock == 1)
			{
				throw new Exception(json_encode(["Unable to login, your account is blocked."]), ReturnSet::ERROR_VALIDATION);
			}

			$appToken				 = $appModel->apt_token_id;
			$appModel->apt_user_id	 = $userModel->user_id;
			$appModel->save();
//			exit;

			generateUrl;
			$JWToken			 = \JWTokens::generateToken($appToken);
			$resArr['jwtoken']	 = $JWToken;
			$resArr['url']		 = $this->getUrlByJwt($JWToken);

			skipAll:
			$resArr['approvalStatus'] = $approvalStatus;

			$authResponse = new \Beans\common\AuthResponse();
			$authResponse->setData($resArr);

			$returnSet->setData($authResponse);

			$returnSet->setStatus(true);

			##Start Check temp contact
			$cttModel	 = \Contact::model()->getByUserId($userModel->user_id);
			$phone		 = $cttModel->contactPhones[0]->phn_phone_no;
			$isExist	 = \Contact::checkExistingTempContacts($phone);
			if($isExist)
			{
				\Contact::updateTempContactsRegisteredByPhone($phone, $cttModel->ctt_id);
			}
			##end Check temp contact
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	private function getUrlByJwt($JWToken)
	{
		$dataArr	 = ['jwtoken' => $JWToken, 'type' => 'jwtValidation'];
		$jsonData	 = \Filter::removeNull(json_encode($dataArr));
		$encrdata	 = \Filter::encrypt($jsonData);
		$urlenc		 = Yii::app()->createAbsoluteUrl('operator/landingpage', ['rdata' => $encrdata]);
		$url		 = \Filter::shortUrl($urlenc);
		return $url;
	}

	public function getCityList()
	{
		$returnSet	 = new ReturnSet();
		$query		 = Yii::app()->request->getParam('q');
		try
		{
			$datafromcity	 = Cities::model()->getJSONSourceCities($query);
			$datafromcityArr = json_decode($datafromcity);
			$cityArr		 = \Beans\common\City::getList($datafromcityArr);

			$returnSet->setStatus(true);
			$returnSet->setData($cityArr);
		}
		catch(Exception $ex)
		{
			$returnSet = \ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function getStateList()
	{
		$returnSet	 = new ReturnSet();
		$query		 = Yii::app()->request->getParam('q');
		try
		{
			$datafromState		 = States::model()->getJSONSourceState($query);
			$datafromStateArr	 = json_decode($datafromState);
			$stateArr			 = \Beans\common\State::getList($datafromStateArr);

			$returnSet->setStatus(true);
			$returnSet->setData($stateArr);
		}
		catch(Exception $ex)
		{
			$returnSet = \ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function getCityListByState()
	{

		$returnSet	 = new ReturnSet();
		$stateId	 = Yii::app()->request->getParam('state');
		$cityQry	 = Yii::app()->request->getParam('q');
		Logger::info("request : " . $stateId);
		try
		{
			if(!$stateId)
			{
				throw new Exception("Select a state first", ReturnSet::ERROR_INVALID_DATA);
			}
			$datafromcity = Cities::getCityListByState($stateId, $cityQry);

			$cityArr = \Beans\common\City::getListByData($datafromcity);

			$returnSet->setStatus(true);
			$returnSet->setData($cityArr);
		}
		catch(Exception $ex)
		{
			$returnSet = \ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	private function authenticateUser($userModel)
	{
		$passWord			 = $userModel->usr_password;
		$username			 = ($userModel->username) ? $userModel->username : $userModel->usr_email;
		$identity			 = new UserIdentity($username, $passWord);
		$identity->userId	 = $userModel->user_id;
		if(!$identity->authenticate())
		{
			throw new Exception("Unable to authenticate", 400);
		}
		return $identity;
	}

	public function updateProfileDoc()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->getParam('data');
		Logger::trace("request : " . $data);
		try
		{
			$jsonObj = CJSON::decode($data, false);
			if(empty($jsonObj))
			{
				throw new Exception(json_encode("No data Found."), ReturnSet::ERROR_VALIDATION);
			}
			$type = $jsonObj->docType;

			$image		 = $_FILES['image'];
			$docImage	 = CUploadedFile::getInstanceByName('image');
			if(empty($image))
			{
				throw new Exception(json_encode("No Image Found."), ReturnSet::ERROR_VALIDATION);
			}

			$cttId		 = $this->getContactId();
			$returnSet	 = Document::saveDcoImage($docImage, $cttId, $type);
			if($returnSet->getStatus())
			{
				$cttLogDesc = ContactLog::model()->eventList($type);
				ContactLog::model()->createLog($cttId, $cttLogDesc, $type);

				$vendorId = $this->getVendorId(false);
				if($vendorId > 0)
				{
					$event_id	 = VendorsLog::VENDOR_FILE_UPLOAD;
					$docTypeName = VendorsLog::docTypeDCO($type);
					if($docTypeName != '')
					{
						$logArray	 = VendorsLog::model()->getLogByDocumentType($docTypeName);
						$logDesc	 = VendorsLog::model()->getEventByEventId($logArray['upload']);
						$userInfo	 = UserInfo::getInstance();
						VendorsLog::model()->createLog($vendorId, $logDesc, $userInfo, $event_id, false, false);
					}
				}
				$driverId = $this->getDriverId(false);
				if($driverId > 0)
				{
					$event_id	 = DriversLog::DRIVER_FILE_UPLOAD;
					$docTypeName = DriversLog::docTypeDCO($type);
					if($docTypeName != '')
					{
						$logArray	 = DriversLog::model()->getLogByDocumentType($docTypeName);
						$logDesc	 = DriversLog::model()->getEventByEventId($logArray['upload']);
						$userInfo	 = UserInfo::getInstance();
						DriversLog::model()->createLog($driverId, $logDesc, $userInfo, $event_id, false, false);
					}
				}

				$message = "Document uploaded successfully";
				$returnSet->setStatus(true);
				$returnSet->setMessage($message);
			}
		}
		catch(Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		Logger::trace("response : " . json_encode($returnSet->getData()));
		return $returnSet;
	}

	public function updateProfileInfo()
	{
		$returnSet = new ReturnSet();
		try
		{
			$cttId	 = $this->getContactId();
			$vndId	 = $this->getVendorId(false);
			$jsonval = Yii::app()->request->rawBody;

			if(!$jsonval)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}

			$reqObj		 = CJSON::decode($jsonval, false);
			$jsonMapper	 = new JsonMapper();

			/** @var \Beans\common\UserSession $reqData */
			$reqData = $jsonMapper->map($reqObj, new \Beans\common\UserSession());

			$contactRequest = $reqData->contact;

			$preferenceRequest = $reqData->vendor->preferences;

			if($contactRequest)
			{
				$success = Contact::updateProfileDCO($contactRequest, $cttId);
			}

			if($vndId > 0 && $preferenceRequest)
			{
				$success = VendorPref::updatePreferenceService($preferenceRequest, $vndId);
			}
			$returnSet = $this->getProfileV1();
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function updateProfilePic()
	{
		$returnSet = new ReturnSet();

		try
		{
			$cttId	 = $this->getContactId();
			/** @var \Contact $model */
			$model	 = Contact::model()->findByPk($cttId);
			$image	 = $_FILES['image'];
			if(empty($image))
			{
				throw new Exception(json_encode("No Image Found."), ReturnSet::ERROR_VALIDATION);
			}
			$path			 = null;
			$profileImage	 = CUploadedFile::getInstanceByName('image');
			if($profileImage != "")
			{
				$path = $model->saveDcoProfileImage($profileImage);
			}
			if(!$path)
			{
				throw new Exception(json_encode("Error in profile image upload"), ReturnSet::ERROR_VALIDATION);
			}
			$message = "Profile picture uploaded successfully";
			$returnSet->setStatus(true);
			$returnSet->setMessage($message);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @return type
	 * @throws Exception
	 */
	public function uploadAgreement()
	{
		$returnSet = new ReturnSet();

		try
		{
			$vndId			 = $this->getVendorId();
			$userInfo		 = UserInfo::getInstance();
			$agreementDate	 = date("Y-m-d");
			$image			 = $_FILES['image'];
			if(empty($image))
			{
				throw new Exception(json_encode("No Image Found."), ReturnSet::ERROR_VALIDATION);
			}
			$path			 = null;
			$uploadedFile	 = CUploadedFile::getInstanceByName('image');
			if($uploadedFile != "")
			{
				$path = Vendors::model()->uploadVendorFiles($uploadedFile, $vndId, 'agreement');
				if(!$path)
				{
					throw new Exception(json_encode("Error in profile image upload"), ReturnSet::ERROR_VALIDATION);
				}

				$success = VendorAgreement::model()->saveDocument($vndId, $path, $userInfo, 'agreement', $agreementDate);
				if(!$success)
				{
					throw new Exception('Failed to save vendor agreement.');
				}
			}

			$message = "Agreement uploaded successfully";
			$returnSet->setStatus(true);
			$returnSet->setMessage($message);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function uploadSoftAgreement()
	{
		$returnSet = new ReturnSet();

		try
		{
			$vndId		 = $this->getVendorId();
			$userInfo	 = UserInfo::getInstance();
			$userId		 = $userInfo->userId;
			$cttId		 = $this->getContactId();
			$image		 = $_FILES['vnd_digital_sign'];

			if(empty($image))
			{
				throw new Exception(json_encode("No Image Found."), ReturnSet::ERROR_VALIDATION);
			}
			$jsonval = Yii::app()->request->getParam('data');
			if(!$jsonval)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$vndDigital		 = $_FILES['vnd_digital_sign']['name'];
			$vndDigitalTmp	 = $_FILES['vnd_digital_sign']['tmp_name'];
			$type			 = "digital_sign";
			$result2		 = Document::model()->saveVendorImage($vndDigital, $vndDigitalTmp, $vndId, $cttId, $type);
			if(empty($result2))
			{
				throw new Exception(json_encode("Image not Uploaded."), ReturnSet::ERROR_VALIDATION);
			}
			$path1 = str_replace("\\", "\\\\", $result2['path']);

			$jsonObj = CJSON::decode($jsonval, false);
			if(VendorAgreement::model()->updateSignature($vndId, $path1))
			{

				$appToken	 = AppTokens::model()->getByUserTypeAndUserId($userId, 2);
				$digitalRes	 = new \Beans\common\Document();
				$modelDig	 = $digitalRes->getDigitalData($jsonObj, $appToken, $vndId);
				if($modelDig->update())
				{

					if($model->vendorPrefs->vnp_is_freeze == 2)
					{
						$model->vendorPrefs->vnp_is_freeze = 0;
						$model->vendorPrefs->save();
					}
					$returnSet->setStatus(true);
					$returnSet->setMessage("Agreement updated successfully");
				}
				else
				{
					$errors = $model->getErrors();
				}
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function statusDetails()
	{
		$returnSet = new ReturnSet();

		try
		{
			$userId	 = UserInfo::getUserId();
			$jsonval = Yii::app()->request->getParam('data');
			if(!$jsonval)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}

			$jwtToken	 = Yii::app()->request->getAuthorizationHeader(false);
			$decodedData = Yii::app()->JWT->decode($jwtToken);
			$token		 = $decodedData->token;
			$token		 = AppTokens::updateDeviceSettings($token, $jsonval);
			$vndId		 = $this->getVendorId(false);
			$drvId		 = $this->getDriverId(false);
			$data		 = Users::model()->getDCOStatusDetails($vndId, $drvId);
			$status		 = new \Beans\common\User();
			$dataList	 = $status->getStatusDetails($data);
			$dataList	 = Filter::removeNull($dataList);
			$returnSet->setStatus(true);
			$returnSet->setData($dataList);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function agreementInformation()
	{
		$returnSet = new ReturnSet();
		try
		{
			$vendorId = $this->getVendorId(false);
			if(!$vendorId)
			{
				throw new Exception("Invalid vendor", ReturnSet::ERROR_UNAUTHORISED);
			}
			$vendorModel = Vendors::model()->findByPk($vendorId);
			if(!empty($vendorModel))
			{
				$contactId		 = ContactProfile::getByVendorId($vendorId);
				$contactModel	 = Contact::model()->findByPk($contactId);
				$agrResponse	 = new \Beans\common\User;
				$agrResponse->setAgreementData($vendorModel, $contactModel);
				$response		 = Filter::removeNull($agrResponse);
				$returnSet->setStatus(true);
				$returnSet->setData($response);
			}
		}
		catch(Exception $ex)
		{
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function muteNotification()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		logger::trace("Request" . $data);
		$jsonObj	 = CJSON::decode($data, false);
		try
		{
			if(empty($data))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			$vndId		 = UserInfo::getEntityId();
			$gnowStat	 = $jsonObj->gnowNotificationStat;
			$snoozeTime	 = $jsonObj->muteTime;

			$stat = VendorPref::updateGnowStatus($vndId, $gnowStat, $snoozeTime);
			if($stat)
			{
				$message = "GozoNow flag status modified";
				$returnSet->setStatus(true);
				$returnSet->setMessage($message);
			}
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	public function vendorInfo()
	{
		$returnSet = new ReturnSet();
		try
		{
			$userId	 = UserInfo::getUserId();
			$vndId	 = $this->getVendorId(false);
			$drvId	 = $this->getDriverId(false);

			$data		 = Vendors::model()->spInfo($vndId, $drvId);
			$vndObj		 = new \Beans\Vendor();
			$vndObj->setInfo($data, $vndId);
			$response	 = Filter::removeNull($vndObj);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch(Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	public function showMatrix()
	{
		$returnSet	 = new ReturnSet();
		$vendorId	 = UserInfo::getEntityId();
		try
		{
			$matrixData = VendorStats::fetchMetric($vendorId);

			if(!empty($matrixData))
			{
				$showModel = new \Beans\Vendor();
				$showModel->getMatrix($matrixData);

				$response = Filter::removeNull($showModel);
				$returnSet->setStatus(true);
				$returnSet->setData($response);
			}
			else
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("No record found");
			}
		}
		catch(Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet->setMessage("No record found");
		}
		return $returnSet;
	}

	public function penaltyRate()
	{
		$returnSet	 = new ReturnSet();
		$success	 = false;
		try
		{
			$penaltyArray	 = [];
			$reasons		 = Yii::app()->params['PenaltyReason'];
			//$amount			 = Yii::app()->params['PenaltyAmount'];
			$ctr			 = 0;
			foreach($reasons as $r)
			{
				$penaltyArray[$ctr]['reason'] = $r;
				$ctr++;
			}
			$response = Filter::removeNull($showModel);
			$returnSet->setStatus(true);
			$returnSet->setData($penaltyArray);
		}
		catch(Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet->setMessage("No record found");
		}
		return $returnSet;
	}
}
