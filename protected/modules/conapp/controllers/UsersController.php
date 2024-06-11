<?php

use Booking;

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
				'actions' => array(),
				'users'	  => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array(
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'	  => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions' => array(),
				'users'	  => array('admin'),
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
			$ri	 = array('/signIn', '/signUp', '/sosTrigger', '/changePassword', '/registerDevice', '/forgotPass',
				'/creditHistory', '/sosContactList', '/notificationCredit', '/profileDetails', '/updateProfile', '/getExistingAddress',
				'/userLogout', '/updateSosTrigger', '/validateApp', '/sosAddContact', '/updateFcm', '/generateReferCode', '/updateProfile', '/sendOtp', '/sendOtpV1', '/verifyOTP', '/verifyUser');

			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		// Consumer (userName) Validate :: 
		$this->onRest('req.post.verifyUser.render', function () {
			return $this->renderJSON($this->validateUsernameV1());
		});

		// Consumer Validate V1 :: 
		$this->onRest('req.post.verifyUserV1.render', function () {
			return $this->renderJSON($this->validateUsername());
		});

		// Consumer Validate V2 :: 
		$this->onRest('req.post.verifyUserV2.render', function () {
			return $this->renderJSON($this->validateUsername(true));
		});

		// Consumer Login :: done
		$this->onRest('req.post.signIn.render', function () {
			return $this->renderJSON($this->signIn());
		});

		// Consumer Signup :: Done
		/** @deprecated use req.post.signUpV2 */
		$this->onRest('req.post.signUp.render', function () {
			return $this->renderJSON($this->signUpV1());
		});

		$this->onRest('req.post.signUpV2.render', function () {
			return $this->renderJSON($this->signUpV2());
		});

		// Send Otp ::  done
		$this->onRest('req.post.sendOtp.render', function () {
			return $this->renderJSON($this->sendOtp());
		});

		// Send Otp new ::  done
		$this->onRest('req.post.sendOtpV1.render', function () {
			return $this->renderJSON($this->sendOtp(true));
		});

		// Verifty Otp :: done
		$this->onRest('req.post.verifyOTP.render', function () {
			return $this->renderJSON($this->verifyOTP());
		});

		$this->onRest('req.post.verifyOTPV1.render', function () {
			return $this->renderJSON($this->verifyOTP());
		});

		// Consumer Logout
		$this->onRest('req.get.userLogout.render', function () {
			return $this->renderJSON($this->signOut());
		});

		/*
		 * old service : validate and validateversion merged to new service validateApp
		 * now validateApp is obsolute
		 */
		$this->onRest('req.post.validateApp.render', function () {
			return $this->renderJSON($this->validateApp());
		});

		// Change Password
		$this->onRest('req.post.changePassword.render', function () {
			return $this->renderJSON($this->changedPassword());
		});

		// use registerDevice for devicetokenfcm and version checking and validate
		$this->onRest('req.post.registerDevice.render', function () {
			return $this->renderJSON($this->registerDevice());
		});

		//Forgot Password
		$this->onRest('req.post.forgotPass.render', function () {
			return $this->renderJSON($this->forgotPass());
		});

		//Forgot Password
		$this->onRest('req.post.forgotPassword.render', function () {
			return $this->renderJSON($this->forgotPassword());
		});

		$this->onRest('req.post.forgotPasswordV1.render', function () {
			return $this->renderJSON($this->forgotPassword(!Filter::checkIOSDevice()));
		});

		$this->onRest('req.post.resetPassword.render', function () {
			return $this->renderJSON($this->resetPassword());
		});

		//Profile Details of Consumer
		$this->onRest('req.get.profileDetails.render', function () {
			return $this->renderJSON($this->profileDetails());
		});

		//Profile Update of Consumer
		$this->onRest('req.post.updateProfile.render', function () {
			return $this->renderJSON($this->updateProfile());
		});

		//Profile Image Update of Consumer
		$this->onRest('req.post.updateProfileImage.render', function () {
			return $this->renderJSON($this->updateProfileImage());
		});

		// Credit History of Consumer
		$this->onRest('req.post.creditHistory.render', function () {
			return $this->renderJSON($this->creditHistory());
		});

		//Credit Notification of Consumer
		$this->onRest('req.post.notificationCredit.render', function () {
			return $this->renderJSON($this->notificationCredit());
		});

		// Status Details of Consumer
		$this->onRest('req.get.statusDetails.render', function () {
			return $this->renderJSON($this->statusDetails());
		});

		//SOS Contact List
		$this->onRest('req.get.sosContactList.render', function () {
			return $this->renderJSON($this->sosContactList());
		});

		// Add SOS Contact
		$this->onRest('req.post.sosAddContact.render', function () {
			return $this->renderJSON($this->sosAddContact());
		});

		//SOS TRUN ON AN OFF
		$this->onRest('req.post.sos.render', function () {
			return $this->renderJSON($this->sos());
		});

		//SOS TRUN ON
		$this->onRest('req.post.sosTrigger.render', function () {
			return $this->renderJSON($this->sosTrigger());
		});

		//SOS TRUN OFF
		$this->onRest('req.post.updateSosTrigger.render', function () {
			return $this->renderJSON($this->updateSosTrigger());
		});

		$this->onRest('req.post.updateFcm.render', function () {
			return $this->renderJSON($this->updateFcm());
		});

		// GENARATE REFERRAL CODE
		$this->onRest('req.get.generateReferCode.render', function () {
			return $this->renderJSON($this->generateReferCode());
		});

		// WALLET HISTORY
		$this->onRest('req.get.walletHistory.render', function () {
			return $this->renderJSON($this->walletHistory());
		});

		// REDEEM VOUCHER 
		$this->onRest('req.post.redeemVoucher.render', function () {
			return $this->renderJSON($this->redeemVoucher());
		});

		$this->onRest('req.post.notificationLog.render', function () {
			return $this->renderJSON($this->notificationLog());
		});

		$this->onRest('req.post.getExistingAddress.render', function () {
			return $this->renderJSON($this->getExistingAddress());
		});

		$this->onRest('req.post.deactivate.render', function () {
			return $this->renderJSON($this->deactivate());
		});
	}

	public function validateUsernameV1()
	{
		$returnSet = $this->validateUsername();
		if ($returnSet->hasErrors())
		{
			$message = implode("\n", Filter::getNestedValues($returnSet->getErrors()));
			$returnSet->setErrors($message);
		}
		return $returnSet;
	}

	/**
	 * @param integer $isMCrypt
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function validateUsername($isEncrypted = false)
	{
		$returnSet = new ReturnSet();
		if (!Filter::checkIOSDevice() && !$isEncrypted)
		{
			throw new CHttpException(400, "Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}
		try
		{
			$obj	   = Yii::app()->request->getJSONObject(new \Stub\common\Auth(), $isEncrypted);
			Logger::info("Request : " . json_encode($obj));
			$authModel = $obj->getSocialModel();
			$userModel = clone $authModel;
			$userModel->setScenario("userLogin");
			$isNewUser = true;
			if (!$userModel->validate())
			{
				$isEmailOrPhone = Users::isEmailOrPhone($userModel->username);
				if (!$isEmailOrPhone)
				{
					throw new Exception(json_encode('Please enter valid email/phone number'), ReturnSet::ERROR_VALIDATION);
				}
				$pageRequest  = BookFormRequest::createInstance(null);
				$objCttVerify = $pageRequest->getContact($userModel->usernameType, $userModel->username);
				if ($objCttVerify->type == \Stub\common\ContactVerification::TYPE_PHONE)
				{
					Filter::parsePhoneNumber($objCttVerify->value, $code, $number);
					$canSendSMS = ContactPhone::checkTosendSMS($code, $number, SmsLog::SMS_LOGIN_REGISTER);
				}
				Contact::verifyOTP($objCttVerify, $canSendSMS);
				$contactDetails = Yii::app()->JWT->encode([["type" => $objCttVerify->type, "value" => $objCttVerify->value, "isSendSMS" => $objCttVerify->isSendSMS]]);

				/** @var $obj \Stub\common\SignUpResponse  */
				$response = new \Stub\consumer\SignUpResponse();
				$response->setModelInfo($userModel, $isNewUser, null, $contactDetails, $pageRequest->getEncrptedData(), \SocialAuth::Provider_aaocab, $authModel->username, $isEmailOrPhone);
				goto skipExiting;
			}

			$contactId = $userModel->usr_contact_id;
			$userId	   = ContactProfile::getUserId($contactId);
			if (!$userId)
			{
				$userId = Users::getByContactId($contactId);
			}
			if (!$userId)
			{
				$userModel = Users::createbyContact($contactId);
			}
			else
			{
				$userModel = Users::model()->findByPk($userId);
			}
			$isNewUser = false;
			if (!$userModel)
			{
				throw new Exception("Sorry, You are not registered with us", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}





			/** @var $obj \Stub\common\SignUpResponse  */
			$response = new \Stub\consumer\SignUpResponse();
			$response->setModelInfo($userModel, $isNewUser, null, null, null, \SocialAuth::Provider_aaocab, $authModel->username);
			skipExiting:
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
		}
		Logger::info("Response : " . json_encode($returnSet));
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function signIn()
	{
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$transaction = null;
		$returnSet	 = new ReturnSet();
		try
		{
			/** @var Stub\common\Auth $obj */
			$obj	   = Yii::app()->request->getJSONObject(new Stub\common\Auth());
			Logger::info("Request : " . json_encode($obj));
			//$oAuthModel = $socialModel->linkContact($obj->accessToken, $contactId);
			$authModel = $obj->getSocialModel();
			if ($obj->provider == \SocialAuth::Provider_aaocab)
			{
				$model = Users::login($authModel, true);
			}
			else
			{
				//$model = $authModel->getUserModel();
				$model = Users::login($authModel, true);
			}
			$usrInfo			 = UserInfo::getInstance();
			/** @var CWebUser $webUser */
			$webUser			 = Yii::app()->user;
			$obj->device->authId = $token;
			$transaction		 = DBUtil::beginTransaction();

			$aptModel	  = AppTokens::registerToken($usrInfo, $obj->device->getAppToken());
			$userModel	  = Users::model()->findByPk($model->user_id);
			$authResponse = new \Stub\consumer\Session();
			$authResponse->setModelData($aptModel->apt_token_id, $userModel);
			$response	  = Filter::removeNull($authResponse);
			if ($response)
			{
				$returnSet->setStatus(true);
				$returnSet->setData($response);
			}

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
		}
		Logger::info("Response : " . json_encode($returnSet));
		return $returnSet;
	}

	/**
	 * @deprecated use UserController::signUpV2
	 */
	public function signUpV1()
	{
		$returnSet = $this->signUpV2();
		if ($returnSet->hasErrors())
		{
			$message = implode("\n", Filter::getNestedValues($returnSet->getErrors()));
			$returnSet->setErrors($message);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @param \Beans\common\Verification $verificationObj 
	 * @return Beans\common\ContactVerification
	 * @throws Exception
	 */
	public function verifyOTPObject($verificationObj)
	{
		if ($verificationObj->requestData == null || $verificationObj->verifyData == null)
		{
			throw new Exception("Invalid request: ", ReturnSet::ERROR_VALIDATION);
		}
		$pageRequest  = BookFormRequest::createInstance($verificationObj->requestData);
		$objPage	  = $pageRequest;
		$objCttVerify = $objPage->contactVerifications[0];
		$curOtp		  = $verificationObj->otp;
		if (!$objCttVerify->isOTPActive())
		{
			throw new Exception("OTP Expired. Please enter new OTP.", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}
		$success = $objCttVerify->verifyOTP($curOtp);

		if (!$success)
		{
			throw new Exception("OTP Mistmached. Please try again", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}

		return $objCttVerify;
	}

	/**
	 * 
	 * @param string $requestData
	 * @param string $verifyData
	 * @return array
	 * @throws Exception
	 */
	public static function verifyOTPObj($requestData, $verifyData, $otp = null)
	{
		$pageRequest   = BookFormRequest::createInstance($requestData);
		$arrVerifyData = Yii::app()->JWT->decode($verifyData);
		$data1		   = json_decode($arrVerifyData);
		if ($data1->type == 1 || $data1->type == 2)
		{
			$data = $data1;
			goto down;
		}
		if ($arrVerifyData->type == null || $arrVerifyData->type == false)
		{
			$data = $arrVerifyData[0];
		}
		else
		{
			$data = $arrVerifyData;
		}
		down:
		if (!$data)
		{
			throw new Exception("Invalid Request", ReturnSet::ERROR_INVALID_DATA);
		}
		$curOtp		  = ($otp > 0) ? $otp : ($data->otp);
		$objCttVerify = $pageRequest->getContact($data->type, $data->value);
		if (!$objCttVerify->isOTPActive())
		{
			throw new Exception("OTP Expired. Please enter new OTP.", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}
		$success = $objCttVerify->verifyOTP($curOtp);
		if (!$success)
		{
			throw new Exception("OTP Mistmached. Please try again", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}
		else
		{
			return $data;
		}
	}

	public function signUpV2()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
		$transaction = DBUtil::beginTransaction();
		try
		{
			if (!$data)
			{
				throw new Exception('Invalid Request', ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj	= CJSON::decode($data, false);
			Logger::create("Response : " . $data, CLogger::LEVEL_INFO);
			/* @var JsonMapper $jsonMapper */
			$jsonMapper = new JsonMapper();
			/* @var $obj \Stub\consumer\SignUpRequest */
			$obj		= Yii::app()->request->getJSONObject(new Stub\consumer\SignUpRequest());

			$authModel = $obj->getSocialModel(true);
			$appModel  = $obj->device->getAppToken();
			$userModel = clone $authModel;
			$type	   = $userModel->validateSignupUsername();
			if ($userModel->hasErrors())
			{
				switch ($type)
				{
					case 1:
						$errorType = ReturnSet::ERROR_EMAILEXIST;
						break;
					case 2:
						$errorType = ReturnSet::ERROR_PHONEEXIST;
						break;
					default:
						$errorType = ReturnSet::ERROR_VALIDATION;
						break;
				}
				throw new Exception(json_encode($userModel->getErrors()), $errorType);
			}

			$veriftyObj				 = new \Beans\common\Verification();
			$veriftyObj->requestData = $obj->requestData;
			$veriftyObj->verifyData	 = $obj->verifyData;
			$veriftyObj->otp		 = $obj->otp;
			$contactVerification	 = $this->verifyOTPObject($veriftyObj);

			$signupObj	= new \Stub\consumer\SignUpRequest();
			$obj		= $signupObj->setData($userModel);
			$objSignUp	= $obj;
			$objProfile = $objSignUp->profile;

			$cttModel  = $objProfile->getContactModel();
			$returnSet = $cttModel->create(true, UserInfo::TYPE_CONSUMER);
			if (!$returnSet->isSuccess())
			{
				$ex = $returnSet->getException();
				Logger::warning($ex, true);
				throw $ex;
			}
			$sendPassword = 1;
			$userModel	  = Users::createbyContact($cttModel->ctt_id, $sendPassword);
			if (!$userModel)
			{
				throw new Exception("Sorry, unable to create your accounts", ReturnSet::ERROR_FAILED);
			}

			if ($userModel)
			{
				$userModel->username = $authModel->username;
				$model				 = Users::login($userModel, true);
				$usrInfo			 = UserInfo::getInstance();
				/** @var CWebUser $webUser */
				$webUser			 = Yii::app()->user;
				$aptModel			 = AppTokens::registerToken($usrInfo, $appModel);
			}
			/** @var $obj \Stub\consumer\SignUpResponse  */
			$response = new \Stub\consumer\SignUpResponse();
			$response->setModelData($userModel, $jsonObj->device, $aptModel->apt_token_id);
			$response = Filter::removeNull($response);
			if ($response)
			{
				$returnSet->setStatus(true);
				$returnSet->setData($response);
			}
			DBUtil::commitTransaction($transaction);
			Logger::info("Response : " . json_encode($returnSet));
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * @return type
	 * @throws Exception\
	 */
	public function signUp()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
		$transaction = DBUtil::beginTransaction();
		try
		{
			if (!$data)
			{
				throw new Exception('Invalid Request', ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj	= CJSON::decode($data, false);
			Logger::create("Response : " . $data, CLogger::LEVEL_INFO);
			/* @var JsonMapper $jsonMapper */
			$jsonMapper = new JsonMapper();
			/* @var $obj \Stub\consumer\SignUpRequest */
			$obj		= $jsonMapper->map($jsonObj, new \Stub\consumer\SignUpRequest());

			$contactSet = Contact::createContact($jsonObj, 0, UserInfo::TYPE_CONSUMER);
			/* @var $userModel Users */
			$contactId	= $contactSet->getData()['id'];
			$userSet	= Users::create($obj->getSocialModel(), true, Users::Platform_App, $contactId, null, $userData);
			if (!$userSet->isSuccess())
			{
				$errors	   = ($userSet->getErrors());
				$e		   = new Exception($errors[0], ReturnSet::ERROR_VALIDATION);
				$returnSet = ReturnSet::setException($e);
				$returnSet->setErrors($errors);
				goto endSignup;
			}
			$userId	   = $userSet->getData()['userId'];
			$userModel = Users::model()->findByPk($userId);
			/** @var $obj \Stub\consumer\SignUpResponse  */
			$response  = new \Stub\consumer\SignUpResponse();
			$response->setModelData($userModel, $jsonObj->device);
			$response  = Filter::removeNull($response);
			if ($response)
			{
				$returnSet->setStatus(true);
				$returnSet->setData($response);
			}
			DBUtil::commitTransaction($transaction);
			Logger::create("Response : " . json_encode($returnSet->getData()), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
			Logger::create("Response Errors : " . json_encode($returnSet), CLogger::LEVEL_INFO);
			DBUtil::rollbackTransaction($transaction);
		}
		endSignup:
		return $returnSet;
	}

	public function sendOtp($isEncrypted = false)
	{
		$returnSet = new ReturnSet();

		if (!Filter::checkIOSDevice() && !$isEncrypted)
		{
			throw new CHttpException(400, "Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}
		try
		{
			$obj = Yii::app()->request->getJSONObject(new \Stub\common\Auth(), $isEncrypted);

			if (!$obj->userName)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}

			$pageRequest = BookFormRequest::createInstance(null);

			/** @var Stub\common\Auth $obj */
			//$obj		 = Yii::app()->request->getJSONObject(new Stub\common\Auth());
			Logger::info("Request : " . json_encode($obj));
			//$oAuthModel = $socialModel->linkContact($obj->accessToken, $contactId);
			$authModel = $obj->getSocialModel();
			$userModel = clone $authModel;
			$userModel->setScenario("userLogin");

			if (!$userModel->validate())
			{
				$isEmailOrPhone = Users::isEmailOrPhone($userModel->username);
				if (!$isEmailOrPhone)
				{
					throw new Exception(json_encode('Please enter valid email/phone number'), ReturnSet::ERROR_VALIDATION);
				}
			}

			$objPage	  = $pageRequest;
			$objCttVerify = $objPage->getContact($userModel->usernameType, $userModel->username);
			if ($objCttVerify->type == 2)
			{
				Filter::parsePhoneNumber($objCttVerify->value, $code, $number);
				$canSendSMS = ContactPhone::checkTosendSMS($code, $number, SmsLog::SMS_LOGIN_REGISTER);
			}
			Contact::verifyOTP($objCttVerify, $canSendSMS);
			$contactDetails = Yii::app()->JWT->encode([["type" => $objCttVerify->type, "value" => $objCttVerify->value, "isSendSMS" => $objCttVerify->isSendSMS]]);

			$params = [
				'verifyData'  => $contactDetails,
				'requestData' => $objPage->getEncrptedData()
			];
			$returnSet->setStatus(true);
			$returnSet->setData($params);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function verifyOTP($isNew = 0)
	{
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$returnSet	 = new ReturnSet();
		$transaction = null;
		try
		{
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			/** @var Stub\common\Auth $obj */
			$obj	   = Yii::app()->request->getJSONObject(new Stub\common\Auth());
			Logger::info("Request : " . json_encode($obj));
			//$otpModel		 = $obj->getVerificationData();
			$authModel = $obj->getSocialModel();
			$userModel = clone $authModel;

			$veriftyObj				 = new \Beans\common\Verification();
			$veriftyObj->requestData = $obj->requestData;
			$veriftyObj->verifyData	 = $obj->verifyData;
			$veriftyObj->otp		 = $obj->otp;
			if ($isNew > 0)
			{
				$data		= self::verifyOTPObj($veriftyObj->requestData, $veriftyObj->verifyData, $veriftyObj->otp);
				if ($data->type = Stub\common\ContactVerification::TYPE_EMAIL)
				{
					$sessEmail = $data->value;
				}
				if ($contactVerification->type = Stub\common\ContactVerification::TYPE_PHONE)
				{
					$sessPhone = $data->value;
				}
			}
			else
			{
				$contactVerification	   = $this->verifyOTPObject($veriftyObj);
				if ($contactVerification->type = Stub\common\ContactVerification::TYPE_EMAIL)
				{
					$sessEmail = $data->value;
				}
				if ($contactVerification->type = Stub\common\ContactVerification::TYPE_PHONE)
				{
					$sessPhone = $data->value;
				}
			}


			$createIfNotExist = true;
			$transaction	  = DBUtil::beginTransaction();
			$contactId		  = Contact::getByEmailPhone($sessEmail, $sessPhone, $createIfNotExist);
			if (!$contactId)
			{
				throw new Exception("Sorry, we couldn't find this data in our records", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			//$userModel = Users::createbyContact($contactId);
			$model = Users::login($userModel, false);

			$usrInfo			 = UserInfo::getInstance();
			/** @var CWebUser $webUser */
			$webUser			 = Yii::app()->user;
			$obj->device->authId = $token;

			$aptModel	  = AppTokens::registerToken($usrInfo, $obj->device->getAppToken());
			$userModel	  = Users::model()->findByPk($model->user_id);
			$authResponse = new \Stub\consumer\Session();
			$authResponse->setModelData($aptModel->apt_token_id, $userModel);
			$response	  = Filter::removeNull($authResponse);
			DBUtil::commitTransaction($transaction);
			if ($response)
			{
				$returnSet->setStatus(true);
				$returnSet->setData($response);
			}
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function sos()
	{
		$returnSet = new ReturnSet();
		try
		{
			$syncDetails = Yii::app()->request->rawBody;
			Logger::trace("<======Request =====>" . $syncDetails);
			$jsonObj	 = CJSON::decode($syncDetails, false);
			if (empty($jsonObj))
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("No Record Found.");
				goto end;
			}
			$userInfo			= UserInfo::getInstance();
			$userInfo->userId	= UserInfo::getUserId();
			$userInfo->userType = UserInfo::TYPE_CONSUMER;  //UserInfo::getUserType();
			$userInfo->platform = UserInfo::$platform;

			$jsonMapper = new JsonMapper();
			$response	= [];
			/** @var Stub\booking\SyncRequest $obj */
			foreach ($jsonObj as $event)
			{
				$syncRequest = new Stub\booking\SyncRequest();
				/** @var Stub\booking\SyncRequest $obj */
				$obj		 = $jsonMapper->map($event, $syncRequest);
				Logger::trace("Stub\booking\SyncRequest: " . json_encode($obj));
				$eventModel	 = $obj->getModel($userInfo);
				$sosArr		 = ReportIssue::checkStatusForSos($eventModel->btl_bkg_id);
				if (!$sosArr['bkg_id'])
				{
					throw new Exception("Issue reported only for Ongoing Trip for this booking.", ReturnSet::ERROR_INVALID_DATA);
					goto skipProcess;
				}

				$eventResponse = $eventModel->handleEvents($obj);

				Logger::trace("BookingTrackLog: " . json_encode($eventModel));

				$bookingModel = Booking::model()->findByPk($obj->bookingId);

				if ($bookingModel->bkg_agent_id != '' || $bookingModel->bkg_agent_ref_code != '')
				{
					$reff_id = $bookingModel->bkg_agent_ref_code;
				}


				$res					= new Stub\booking\SyncResponse();
				$res->setData($eventResponse, $eventModel, $reff_id);
				$responsedt->dataList[] = $res;

				if (!$eventResponse->getStatus())
				{
					$hitUrl			 = Yii::app()->request->hostInfo . Yii::app()->request->url;
					$uploadTodb		 = true;
					$res->status	 = false;
					$res->syncStatus = 3;
					if ($uploadTodb)
					{
						$res->status	 = true;
						$res->syncStatus = 2;
					}
					$res->syncError = ($eventResponse->getErrors() != null) ? json_encode($eventResponse->getErrors()) : $eventResponse->getMessage();
					Logger::trace("Stub\booking\SyncResponse: " . json_encode($res));
				}
				else
				{
					$eventModel->addLocation();
				}

				BookingTrackLog::checkApiDiscrepancy($eventModel->btl_bkg_id, $syncDetails);
			}
			$response = $responsedt;
			$data	  = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($data);

			$cnt = 0;
			foreach ($data->dataList as $v)
			{
				if (!$v->status)
				{
					Logger::trace("Booking Sync Failed : " . json_encode($v));
					$cnt++;
				}
			}
			Logger::trace("<======Response =====>" . json_encode($returnSet));
			if ($cnt > 0)
			{
				Logger::pushTraceLogs();
			}
		}
		catch (Exception $e)
		{
			skipProcess:
			$returnSet = ReturnSet::setException($e);
		}
		end:
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function registerDevice()
	{
		$returnSet		= new ReturnSet();
		$token			= $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		Logger::trace("token: $token");
		$isVersionCheck = false;
		$isSessionCheck = false;
		try
		{
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	   = new JsonMapper();
			$jsonObj	   = CJSON::decode($data, false);
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			/* @var $obj JsonMapper */
			$obj		   = $jsonMapper->map($jsonObj, new \Stub\common\Platform());
			//$obj		 = $obj->setData();
			$obj->authId   = $token;
			$activeVersion = AppTokens::getVersionByApp($obj->platform); //AppTokens::getMinimumAppVersion('consumer', $obj->type);
			Logger::trace("activeVersion: " . json_encode($activeVersion));
			/** @var CWebUser $webUser */
			$webUser	   = Yii::app()->user;
			/* @var $usrInfo UserInfo */
			$usrInfo	   = UserInfo::getInstance();
			/* @var $model AppTokens */
			$model		   = AppTokens::registerToken($usrInfo, $obj->getAppToken(), $activeVersion, $obj->platform);

			$versionResult = AppTokens::verifyVerison($model->apt_apk_version, $activeVersion);
			$tokenResult   = AppTokens::verifyToken($model->apt_token_id);
			Logger::trace("versionResult: " . json_encode($versionResult));
			Logger::trace("tokenResult: " . json_encode($tokenResult));

			$isVersionCheck = $versionResult['success'];
			$isSessionCheck = $tokenResult['success'];
			if ($isVersionCheck == false)
			{
				$message = $versionResult['message'];
			}
			else if ($isSessionCheck == false)
			{
				$message = $tokenResult['message'];
			}

			$info = [
				'versionCheck' => $isVersionCheck,
				'sessionCheck' => $isSessionCheck,
				'message'	   => $message,
				'currentDate'  => date('Y-m-d H:i:s')];

			//$resultData = AppTokens::updateFcm($model->apt_token_id, $fcmToken);

			$userModel = Users::model()->findByPk($model->apt_user_id);
			$response  = new \Stub\consumer\DeviceResponse();
			$response->setModel($model, $userModel, $info);
			$response  = Filter::removeNull($response);
			if ($response)
			{
				$returnSet->setStatus(true);
				$returnSet->setData($response);
			}
			Logger::create("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function signOut()
	{
		$returnSet = new ReturnSet();
		$token	   = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$model = AppTokens::validateToken($token);
			if (!$model)
			{
				$returnSet->setMessage("Unauthorized token.");
			}
			if (Users::doLogout($token))
			{
				VoucherOrder::unsetCartSession();
				$returnSet->setStatus(true);
				$returnSet->setMessage("User logged out successfully.");
			}
			Logger::create("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
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
	public function generateReferCode()
	{
		$returnSet = new ReturnSet();
		$token	   = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$userInfo  = UserInfo::getInstance();
		$userId	   = $userInfo->userId;
		try
		{
			Logger::info("Request token " . $token);
			$userModel = Users::model()->findByPk($userId);
			if (!$userModel)
			{
				throw new Exception("Invalid User ", ReturnSet::ERROR_INVALID_DATA);
			}
//			$refCode					 = Users::getUniqueReferCode($userModel);
//			$userModel->usr_refer_code	 = $refCode;
//			$userModel->scenario		 = 'refcode';
//			if ($userModel->validate())
//			{
//				if (!$userModel->update())
//				{
//					$errors = $userModel->getErrors();
//				}
//			}
//			$response = Users::model()->getRefercode($userId);
//			if ($response == false)
//			{
//				throw new Exception("Unable to save refer code. ", ReturnSet::ERROR_INVALID_DATA);
//			}

			if ($userModel->usr_qr_code_path == '')
			{
				$ret = QrCode::processData($userId);
				if (!$ret->getStatus())
				{
					throw new Exception($ret->getMessage());
				}
			}
			$path			= Users::getUserPathById($userId);
			$qrModel		= QrCode::model()->find('qrc_ent_type=1 AND qrc_ent_id = :userid', array('userid' => $userId));
			$referralCode	= ($qrModel) ? $qrModel->qrc_code : '';
			$referralCode	= 'https://gozo.cab/c/' . $referralCode;
			$amount			= Yii::app()->params['invitedAmount'];
			$referalMessage = 'Dear Friend, I wanted to introduce you to aaocab.com. I used it recently for my long distance taxi travel. You may find them useful to address your long distance travel needs and quality service. aaocab is India’s leader in long distance taxi travel. Please visit  ' . $referralCode . '  to register and get a credit of ' . $amount . ' points towards your future travel needs';
			$returnSet->setStatus(true);
			//$referalMessage = Config::get('user.referral.message');
			$returnSet->setData(['code' => $referralCode, 'showMessage' => $referalMessage]);
			$returnSet->setMessage($referalMessage);
			Logger::info("Response : " . CJSON::encode($returnSet));
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
			Logger::exception($ex);
		}
		return $returnSet;
	}

	public function forgotPassword($isEncrypted = false)
	{
		if (!Filter::checkIOSDevice() && !$isEncrypted)
		{
			throw new CHttpException(400, "Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}

		$returnSet = new ReturnSet();
		try
		{
			$obj	   = Yii::app()->request->getJSONObject(new \Stub\common\Auth(), $isEncrypted);
			/** @var Stub\common\Auth $obj */
			//$obj		 = Yii::app()->request->getJSONObject(new Stub\common\Auth());
			Logger::info("Request : " . json_encode($obj));
			$authModel = $obj->getSocialModel();
			$userModel = clone $authModel;
			$userModel->setScenario("userLogin");

			$isEmailOrPhone = Users::isEmailOrPhone($userModel->username);
			if (!$isEmailOrPhone)
			{
				throw new Exception(json_encode('Please enter valid email/phone number'), ReturnSet::ERROR_VALIDATION);
			}
			$typeUsr = $isEmailOrPhone['type'];

			if ($isEmailOrPhone['type'] == 1)
			{
				$pageRequest	 = BookFormRequest::createInstance(null);
				$objEmailContact = $pageRequest->getContact($isEmailOrPhone['type'], $userModel->username);

				//$objEmailContact = $this->pageRequest->getContact($isEmailOrPhone['type'], $isEmailOrPhone['value']);
				Contact::verifyOTP($objEmailContact, true, null, false);
				$arrVerifyData = ["type"		   => $objEmailContact->type,
					"value"		   => $objEmailContact->value,
					'otp'		   => $objEmailContact->otp, 'otpValidTill' => $objEmailContact->otpValidTill, 'otpLastSent'  => $objEmailContact->otpLastSent];
				$arrTime	   = ['otpValidTill' => $objEmailContact->otpValidTill, 'otpLastSent' => $objEmailContact->otpLastSent];
				$otpObj		   = $objEmailContact;

				$email			= $isEmailOrPhone['value'];
				$contactId		= ContactEmail::findById($email);
				$contactModel	= Contact::model()->findByPk($contactId);
				$contactProfile = ContactProfile::model()->findByContactId($contactId);

				$user_id = $contactProfile->cr_is_consumer;

				$userModel = Users::model()->findByPk($user_id);
				$key	   = md5($userModel->usr_password);
				$hash	   = Yii::app()->shortHash->hash($user_id);
				$link	   = Yii::app()->createAbsoluteUrl('users/resetpassword', array('key' => $key, 'uid' => $hash));
				$username  = $contactModel->ctt_first_name;
				$emailCom  = new emailWrapper();
				$isSend	   = $emailCom->sendResetPasswordLinkWithOTP($otpObj->value, $otpObj->otp, $link, $username, $user_id);
			}
			else
			{
				$pageRequest	 = BookFormRequest::createInstance(null);
				$objPhoneContact = $pageRequest->getContact($isEmailOrPhone['type'], $userModel->username);

				Filter::parsePhoneNumber($userModel->username, $code, $number);
				$canSendSMS	   = ContactPhone::checkTosendSMS($code, $number, SmsLog::SMS_FORGET_PASSWORD);
				$smsLogType	   = SmsLog::SMS_FORGET_PASSWORD;
				Contact::verifyOTP($objPhoneContact, $canSendSMS, null, false, $smsLogType);
				$arrVerifyData = ["type"		   => $objPhoneContact->type, "value"		   => $objPhoneContact->value,
					"isSendSMS"	   => $objPhoneContact->isSendSMS, 'otp'		   => $objPhoneContact->otp, 'otpValidTill' => $objPhoneContact->otpValidTill, 'otpLastSent'  => $objPhoneContact->otpLastSent];
				$arrTime	   = ['otpValidTill' => $objPhoneContact->otpValidTill, 'otpLastSent' => $objPhoneContact->otpLastSent];
				$otpObj		   = $objPhoneContact;
				$isSend		   = $objPhoneContact->isSendSMS;
			}
//			if ($isNew > 0)
//			{
//				$contactDetails = Yii::app()->JWT->encode([$arrVerifyData]);
//			}
//			else
//			{
//				$contactDetails = $arrVerifyData;
//			}
			$contactDetails = $arrVerifyData;
			$returnSet->setData(
					[
						'verifyData'	 => $contactDetails,
						'verifyValidity' => $arrTime,
						'requestData'	 => $pageRequest->getEncrptedData(),
						'typeUsr'		 => $typeUsr,
						'typeID'		 => $isEmailOrPhone['value']
			]);

			$returnSet->setStatus(true);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	public function resetPassword()
	{
		$returnSet = new ReturnSet();
		$token	   = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception('Invalid Request : ', ReturnSet::ERROR_INVALID_DATA);
			}
			//$data        = '{"newPassword":"abcd123415","repeatPassword":"abcd123456"}';
			$jsonObj	= CJSON::decode($data, false);
			$jsonMapper = new JsonMapper();
			/** @var \Stub\consumer\ChangepassRequest $obj */
			$obj		= $jsonMapper->map($jsonObj, new \Stub\common\Changepass());

			$userInfo				= UserInfo::getInstance();
			$userId					= $userInfo->userId;
			$model					= Users::model()->findByPk($userId);
			$model->new_password	= $obj->newPassword;
			$model->repeat_password = $obj->repeatPassword;

			if (!$model)
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			$model->usr_password = md5($model->new_password);
			$model->save();

			//$usrModel = Users::login($model, false);

			/** @var CWebUser $webUser */
			$webUser			 = Yii::app()->user;
			$obj->device->authId = $token;

			//$aptModel		 = AppTokens::registerToken($userInfo, $obj->device->getAppToken());
			//$userModel		 = Users::model()->findByPk($usrModel->user_id);
			$authResponse = new \Stub\consumer\Session();
			$authResponse->setModelData($token, $model);
			$response	  = Filter::removeNull($authResponse);

			if ($response)
			{
				$returnSet->setStatus(true);
				$returnSet->setData($response);
				$returnSet->setMessage("Reset Password Successfully.");
			}
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
	public function forgotPass()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody; //$data	 = '{"profile":{"email": "romanayek1810@aaocab1.in"}}';
			if (!$data)
			{
				throw new Exception('Invalid Request : ', ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj	= CJSON::decode($data, false);
			Logger::create("Request : " . json_encode($data), CLogger::LEVEL_INFO);
			$jsonMapper = new JsonMapper();
			$obj		= $jsonMapper->map($jsonObj, new \Stub\common\Forgotpass());
			$model		= $obj->getModel();
			if (!$model)
			{
				throw new Exception('Invalid Request: ', ReturnSet::ERROR_INVALID_DATA);
			}
			$returnSet = Users::checkForgotPass($model->usr_email);
			Logger::create("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
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
	public function changedPassword()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		//AppTokens::validateToken($token);
		$transaction = DBUtil::beginTransaction();
		try
		{
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			//$data        = '{"oldPassword":"abcd12349","newPassword":"abcd123415","repeatPassword":"abcd123456"}';
			$jsonObj	= CJSON::decode($data, false);
			$jsonMapper = new JsonMapper();
			/** @var \Stub\consumer\ChangepassRequest $obj */
			$obj		= $jsonMapper->map($jsonObj, new \Stub\common\Changepass());
			$userInfo	= UserInfo::getInstance();
			$userId		= $userInfo->userId;
			if (!$userId)
			{
				throw new Exception("User not found", ReturnSet::ERROR_INVALID_DATA);
			}
			$model = $obj->getModel();
			Logger::create("Request: " . json_encode($model->getAttributes()), CLogger::LEVEL_INFO);
			if (!$model)
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("Invalid Request: ", $returnSet::ERROR_INVALID_DATA);
			}
			$response = Users::model()->changePass($userId, $model);

			$returnSet->setStatus(true);
			$returnSet->setMessage("Password Changed.");
			DBUtil::commitTransaction($transaction);
			Logger::create("Response: " . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($e);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function profileDetails()
	{
		$returnSet = new ReturnSet();
		$token	   = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$userInfo  = UserInfo::getInstance();
		$userId	   = $userInfo->userId;
		try
		{
			Logger::create("Request token : " . $token, CLogger::LEVEL_INFO);
			if (!$userId)
			{
				throw new Exception("Invalid User ", ReturnSet::ERROR_INVALID_DATA);
			}
			/* @var $userModel Users */
			$userModel = Users::model()->findByPk($userId);
			$contactId = Contact::getByEmailPhone($userModel->usr_email, $userModel->usr_mobile);
			if (!$contactId)
			{
				throw new Exception("Sorry, we couldn't find this data in our records", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			//	Logger::info("contact id");
			$model = Users::createbyContact($contactId);
			if (!$model)
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("Profile Show Failed", $returnSet::ERROR_INVALID_DATA);
				goto endProfile;
			}


			$response = new \Stub\consumer\ProfiledetailsResponse();
			//$response->setModelData($model, $contactId);
			$response->setDataSet($model);
			$response = Filter::removeNull($response);

			$returnSet->setStatus(true);
			$returnSet->setData($response);
			Logger::create("Response ::" . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
			Logger::create("Exception :" . CJSON::encode($returnSet), CLogger::LEVEL_INFO);
		}
		endProfile:
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function updateProfile()
	{
		$returnSet = new ReturnSet();
		$token	   = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$data = Yii::app()->request->rawBody;
			if ($data == null)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj	= CJSON::decode($data, false);
			$jsonMapper = new JsonMapper();
			$userInfo	= UserInfo::getInstance();
			if (!$userInfo->userId)
			{
				throw new Exception("Invalid User ", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);

			$phNumber	  = "+" . $jsonObj->profile->primaryContact->code . $jsonObj->profile->primaryContact->number;
			$isValidPhone = Filter::validatePhoneNumber($phNumber);
			if (!$isValidPhone)
			{
				throw new Exception('Please enter valid phone number', ReturnSet::ERROR_VALIDATION);
			}
			$entityId  = $userInfo->userId;
			$contactId = ContactProfile::getByEntityId($entityId, UserInfo::TYPE_CONSUMER);
			if ($contactId)
			{
				$responseSet = Contact::modifyContact($jsonObj, $contactId, 1, UserInfo::TYPE_CONSUMER);
			}
			else
			{
				$responseSet = Contact::createContact($jsonObj, 0, UserInfo::TYPE_CONSUMER);
			}
//			if ($responseSet->getStatus() == false)
//			{
//				$returnSet->setErrorCode($responseSet->getErrorCode());
//				$returnSet->setErrors($responseSet->getErrors());
//				$returnSet->setMessage($responseSet->getErrors()[0]);
//				goto skipProfileUpdate;
//			}

			$cttModel  = Contact::model()->findByPk($contactId);
			$userModel = Users::createbyContact($cttModel->ctt_id);

			if (!$userModel)
			{
				throw new Exception("Sorry, unable to create your accounts", ReturnSet::ERROR_FAILED);
			}
			$userModel->usr_address1 = $jsonObj->profile->address;
			$userModel->usr_state	 = $jsonObj->profile->state;
			$userModel->usr_country	 = $jsonObj->profile->country;
			$userModel->usr_gender	 = $jsonObj->profile->gender;
			$userModel->usr_zip		 = $jsonObj->profile->pincode;
			$userModel->updateProfileinfo($userModel, $entityId);

			$response = new Stub\consumer\ProfileUpdateResponse();
			$response->setDataSet($userModel);
			$response = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
			Logger::create("Response " . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet = $returnSet->setException($ex);
			$returnSet->setMessage($ex->getMessage());
		}
		skipProfileUpdate:
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function updateProfileImage()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$transaction = DBUtil::beginTransaction();
		try
		{
			$userInfo = UserInfo::getInstance();
			$userId	  = $userInfo->userId;
			if (!$userId)
			{
				throw new Exception("Invalid User ", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . $userId, CLogger::LEVEL_INFO);
			$model = Users::model()->updateProfileinfo("", $userId, 1);
			$fullImagePath = '';
			if($model->usr_profile_pic!='')
			{
				$fullImagePath = \Yii::app()->params['fullAPPBaseURL'] . \AttachmentProcessing::ImagePath($model->usr_profile_pic);
			}
			$returnSet->setStatus(true);
			$returnSet->setData(["userProfilePic" => $fullImagePath]);
			DBUtil::commitTransaction($transaction);
			Logger::create("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return type
	 * @throws Exception
	 */
	public function validateApp()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody;
			//$data	 = '{"version":"3.10.90513","osVersion":21,"uniqueId":"76aa324e50917aea","authId":"4dRLt6P9yxcgpF78kRTwu2","type":3}';
			if (!$data)
			{
				throw new Exception('Invalid Request : ', ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	   = new JsonMapper();
			$jsonObj	   = CJSON::decode($data, false);
			Logger::create("Request: " . json_encode($data), CLogger::LEVEL_INFO);
			/* @var $obj JsonMapper */
			$obj		   = $jsonMapper->map($jsonObj, new \Stub\common\Platform());
			/* @var $model AppTokens */
			$objModel	   = $obj->getAppToken();
			$userId		   = \UserInfo::getUserId();
			$entityId	   = \UserInfo::getEntityId();
			$activeVersion = AppTokens::getMinimumAppVersion('consumer', $obj->type);
			/** @var CWebUser $webUser */
			$webUser	   = Yii::app()->user;
			$model		   = AppTokens::registerToken($webUser, $obj->getAppToken());
			/* @var $resultSet AppTokens */
			$resultSet	   = AppTokens::model()->verify($model->apt_token_id, $model->apt_apk_version, $userId, $activeVersion);
			if ($resultSet['success'] == true)
			{
				$tokenModel					= AppTokens::model()->getByToken($model->apt_token_id);
				$tokenModel->apt_last_login = new CDbExpression('NOW()');
				$tokenModel->apt_entity_id	= $webUser->getEntityID();
				if (!$tokenModel->save())
				{
					throw new Exception(CJSON::encode($tokenModel->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
			}

			$info		 = ['message'	   => $resultSet['message'],
				'sessionCheck' => $resultSet['sessionCheck'],
				'versionCheck' => $resultSet['versionCheck']];
			$umodel		 = Users::model()->findByPk($userId);
			$valResponse = new \Stub\consumer\ValidateResponse();
			$valResponse->setData($model, $umodel, $userId, $info);
			$response	 = Filter::removeNull($valResponse);
			$returnSet->setStatus($resultSet['success']);
			$returnSet->setData($response);
			Logger::create("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function creditHistory()
	{
		$returnSet = new ReturnSet();
		$token	   = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		AppTokens::validateToken($token);
		try
		{
			$data	 = Yii::app()->request->rawBody;
			//$data		 = '{"decision":1}'; 2->false, 1->true
			$jsonObj = CJSON::decode($data, false);
			if (!in_array($jsonObj->decision, ['1', '2']))
			{
				throw new Exception("Invalid request: ", ReturnSet::ERROR_VALIDATION);
			}
			Logger::create("Request ::" . json_encode($data), CLogger::LEVEL_INFO);
			$jsonMapper = new JsonMapper();
			$obj		= $jsonMapper->map($jsonObj, new \Stub\consumer\CredithistoryRequest());
			$model		= $obj->getModel();
			if (!$model)
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("Invalid Request", $returnSet::ERROR_INVALID_DATA);
			}
			$userInfo = UserInfo::getInstance();
			$userId	  = $userInfo->userId;
			if (empty($userId))
			{
				throw new Exception("User not found", ReturnSet::ERROR_VALIDATION);
			}
			$result		   = $model->getCreditsList($model->creditStatus, $userId, $flag		   = 1);
			$activeCredits = UserCredits::model()->getTotalActiveCredits($userId);

			if ($result == '')
			{
				throw new Exception("Data not found", ReturnSet::ERROR_VALIDATION);
			}
			/* @var $obj Stub\booking\CreditHistoryResponse */
			$response = new Stub\booking\CreditHistoryResponse();
			$response->setData($result, $activeCredits);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
			Logger::create("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
			Logger::exception($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function sosContactList()
	{
		$returnSet = new ReturnSet();
		$token	   = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			AppTokens::validateToken($token);
			$response = [];
			$userInfo = UserInfo::getInstance();
			if (!$userInfo->userId)
			{
				throw new Exception("User not registered with us", ReturnSet::ERROR_INVALID_DATA);
			}
			$responseSet = Users::model()->getSosContactList($userInfo->userId);
			if (!$responseSet)
			{
				$returnSet->setMessage("No Record Found.");
			}
			$returnSet->setStatus(true);
			$returnSet->setData($responseSet);
			Logger::create("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function sosAddContact()
	{
		$returnSet = new ReturnSet();
		$token	   = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			AppTokens::validateToken($token);
			$data	  = Yii::app()->request->rawBody; // '[{"name":"ritumob","phon_no":"8053195630"},{"name":"Rohit2","phon_no":"+917404301760"}]';
			Logger::create("Request : " . json_encode($data), CLogger::LEVEL_INFO);
			$userInfo = UserInfo::getInstance();
			$userId	  = $userInfo->userId; // 324752;			
			if (empty($userId))
			{
				throw new Exception("User not found", ReturnSet::ERROR_INVALID_DATA);
			}
			$isValid = json_decode($data, true);
			if (count($isValid) > 3)
			{
				throw new Exception("Contact Limit Exceeds", ReturnSet::ERROR_INVALID_DATA);
			}
			if (Users::addContactToSOS($userId, $data))
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage('Contacts Saved Successfully.');
			}
			Logger::create("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 * SOS TURN ON
	 */
	public function sosTrigger()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		/** @var \Stub\consumer\SOSRequest $obj */
		$transaction = DBUtil::beginTransaction();
		try
		{
			AppTokens::validateToken($token);
			//$userInfo = UserInfo::getInstance();
			//$userInfo->platform = UserInfo::$platform;
			$userInfo			= new UserInfo();
			$userInfo->userId	= UserInfo::getUserId();
			$userInfo->userType = UserInfo::getUserType();
			$userInfo->platform = 1;
			Logger::create("User Data : " . json_encode($userInfo), CLogger::LEVEL_INFO);
			$data				= Yii::app()->request->rawBody;  // "{'location':{'latitude' : 28.644800,'longitude' : 77.216721}, 'device':{'uniqueId' : '2F1C677C-46CD-4D6A-BF7D-F51E16B86AF6'},'bookingId' : 1196607}";
			if (!$data)
			{
				throw new Exception("Invalid Request ", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$jsonObj	   = CJSON::decode($data, false);
			/* @var $jsonMapper JsonMapper */
			$jsonMapper	   = new JsonMapper();
			/** @var Stub\booking\SyncRequest $obj */
			$obj		   = $jsonMapper->map($jsonObj, new \Stub\booking\SyncRequest());
			$eventModel	   = $obj->getModel($userInfo);
			$eventResponse = $eventModel->handleEvents();

			$res	 = new Stub\booking\SyncResponse();
			$res->setData($eventResponse, $eventModel);
			$data	 = Filter::removeNull($res);
			$returnSet->setStatus(true);
			$returnSet->setData($data);
			$message = ($data->remarks) ? $data->remarks : $eventResponse->getMessage();
			$returnSet->setMessage($message);
			Logger::create("Response Message : " . json_encode($returnSet), CLogger::LEVEL_INFO);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet = $returnSet->setException($e);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 * SOS TURN OFF
	 */
	public function updateSosTrigger()
	{
		$returnSet = new ReturnSet();
		$token	   = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			AppTokens::validateToken($token);

			$data = Yii::app()->request->rawBody; // '{'location':{'latitude' : 28.644800,'longitude' : 77.216721}, 'device':{'uniqueId' : '2F1C677C-46CD-4D6A-BF7D-F51E16B86AF6'},'bookingId' : 1196607,'comments':""}'
			if (!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj	= CJSON::decode($data, false);
			Logger::create("Request ::" . json_encode($data), CLogger::LEVEL_INFO);
			$jsonMapper = new JsonMapper();

			$obj = $jsonMapper->map($jsonObj, new \Stub\consumer\SOSRequest());

			/* @var $bookingModel Booking */
			$bookingModel = Booking::model()->findByPk($obj->bookingId);
			/* @var $obj \Stub\consumer\SOSRequest */
			$model		  = $obj->getModel($bookingModel);
			if (!$model)
			{
				throw new Exception('Invalid Request', ReturnSet::ERROR_INVALID_DATA);
			}

			$responseSOS = $model->bkgTrack->updateSOS();

			$returnSet->setStatus($responseSOS->isSuccess());
			$returnSet->setMessage($responseSOS->getMessage());

			Logger::create("Response ::" . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @return type ReturnSet
	 * @throws Exception
	 */
	public function notificationCredit()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$transaction = DBUtil::beginTransaction();
		try
		{
			AppTokens::validateToken($token);
			$data = Yii::app()->request->rawBody; //$data = '{"ntfId":99531}';
			if (!$data)
			{
				throw new Exception('Invalid Request', ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj  = CJSON::decode($data, false);
			Logger::create("Request::" . json_encode($data), CLogger::LEVEL_INFO);
			$userInfo = UserInfo::getInstance();
			$userId	  = $userInfo->userId;
			$ntfId	  = $jsonObj->ntfId;
			if (empty($userId))
			{
				throw new Exception("User not found", ReturnSet::ERROR_VALIDATION);
			}
			$model	   = UserNotification::model()->findByUserAndNtf($userId, $ntfId);
			$coinValue = $model->unfNtf->ntf_coin_value;

			if (UserCredits::model()->addCreditsForNotification($userId, $ntfId, $coinValue))
			{
				$returnSet->setStatus(true);
				$messages = ['message' => 'Congratulations! You have earned ₹' . $coinValue . ' gozo coins'];
			}
			else
			{
				$returnSet->setStatus(false);
				$messages = ['message' => 'Sorry you have already got gozo coins for this notification'];
			}

			$returnSet->setMessage($messages['message']);
			DBUtil::commitTransaction($transaction);
			Logger::create("Response ::" . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @return type ReturnSet
	 * @throws Exception
	 */
	public function statusDetails()
	{
		$returnSet = new ReturnSet();
		$token	   = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{

			Logger::create("Token Request: " . $token, CLogger::LEVEL_INFO);
			$userInfo	  = UserInfo::getInstance();
			$userId		  = $userInfo->userId; //36281; //301843; 
			$isSosFlag	  = 0;
			$isLastReview = 0;
			$isRated	  = 1;
			$lastBkgId	  = null;
			$lastRoute	  = null;
			if (!$userId)
			{
				throw new Exception("Invalid User ", ReturnSet::ERROR_INVALID_DATA);
			}
			$data = Users::getBookingsByUserId($userId);
			//$sosContactAlert = Users::isSosContactList($userId);
			if ($data['bkg_id'] > 0)
			{
				$isSosFlag = $data['bkg_sos_sms_trigger'];
				$sosBkgId  = $data['bkg_id'];
			}
			else
			{
				$isSosFlag = 0;
			}

			$result		   = Users::findLastBookingReviewById($userId);
			$lastBkgId	   = $result['bkg_id'];
			$lastBookingId = $result['bkg_booking_id'];
			$isRated	   = $result['isRated'];
			$lastRoute	   = $result['lastRouteName'];
			$returnSet->setStatus(true);
			$bundle		   = [
				'isSosFlag'		=> $isSosFlag,
				'sosBkgId'		=> $sosBkgId,
				'lastBkgId'		=> $lastBkgId,
				'lastBookingId' => $lastBookingId,
				'isRated'		=> $isRated,
				'lastRoute'		=> json_decode($lastRoute)];

			$response = new \Stub\consumer\StatusDetailsResponse();
			$response->setData($bundle);
			$response = Filter::removeNull($response);
			$returnSet->setData($response);
			Logger::create("Response :" . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/** 	 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function updateFcm()
	{
		$returnSet = new ReturnSet();
		$authToken = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		Logger::trace("header request : " . $authToken);
		try
		{
			$data = Yii::app()->request->rawBody; // '{"fcmId":"test123415"}';
			Logger::trace("request : " . $data);
			if (!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj	= CJSON::decode($data, false);
			$fcmToken	= $jsonObj->fcmId;
			$resultData = AppTokens::updateFcm($authToken, $fcmToken);
			Logger::trace("response : " . json_encode($resultData));
			if ($resultData['success'] == false)
			{
				$ex = new Exception($resultData['message'], ReturnSet::ERROR_INVALID_DATA);
				Logger::warning($ex, true);
				throw $ex;
			}

			$returnSet->setStatus(true);
			$returnSet->setMessage("Token updated sucessfully");
			Logger::create("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/** 	 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function walletHistory()
	{
		$returnSet = new ReturnSet();
		try
		{
			$userInfo = UserInfo::getInstance();
			$userId	  = $userInfo->userId;
			if (empty($userId))
			{
				throw new Exception("User not available", ReturnSet::ERROR_INVALID_DATA);
			}
			$walletBallance = UserWallet::getBalance($userId);
			$walletData		= UserWallet::model()->getTransHistory($userId, Accounting::LI_WALLET)->getData();
			/* @var $obj Stub\booking\WalletHistory */
			$response		= new Stub\booking\WalletHistory();
			$response->setData($walletData, $walletBallance);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
			Logger::create("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/** 	 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function redeemVoucher()
	{
		$returnSet = new ReturnSet();
		$authToken = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$data = Yii::app()->request->rawBody; // '{"code":"YRL23184VQ6A5PEW"}';
			if (!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj  = CJSON::decode($data, false);
			$cardCode = $jsonObj->code;
			//$code	  = md5($cardCode);	
			$code	  = $cardCode;
			$res	  = VoucherSubscriber::redeem($code);
			if (!$res->getStatus())
			{
				$returnSet->setStatus(false);
				$msg = (!empty($res->getMessage())) ? $res->getMessage() : 'Sorry! Invalid voucher code.Please try again.';
				$returnSet->setMessage($msg);
			}
			else
			{
				$returnSet->setStatus(true);
				$msg = $res->getMessage();
				$returnSet->setMessage($msg);
			}
			Logger::create("Response : " . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public static function notificationLog()
	{
		$returnSet = new ReturnSet();
		try
		{
			$usrId = UserInfo::getEntityId();
			if (empty($usrId))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}

			$ntlLogList = NotificationLog::getDetails($usrId);
			$ntlList	= new Stub\common\Notification();
			$ntlList->getList($ntlLogList);
			$response	= Filter::removeNull($ntlList);
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

	public function getExistingAddress()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody;  // '{"cityId":99531}';
			if (!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj  = CJSON::decode($data, false);
			$cityId	  = $jsonObj->cityId;
			$userInfo = UserInfo::getInstance();
			$userId	  = $userInfo->getUserId();
			if (empty($userId))
			{
				throw new Exception("Invalid User", ReturnSet::ERROR_INVALID_DATA);
			}
			//$response	 = [];
			$data = BookingRoute::getUserAddressesByCity($cityId, $userId);
			$ctr  = 0;
			foreach ($data as $key => $val)
			{
				$ctr++;
				$jsonValue		= CJSON::decode($val['id']);
				$coordinates[]	= null;
				$coordinates	= ['latitude' => $jsonValue['coordinates']['latitude'], 'longitude' => $jsonValue['coordinates']['longitude']];
				$responseData[] = null;
				$responseData	= [
					'name'		  => ($jsonValue['name'] != null) ? $jsonValue['name'] : null,
					'alias'		  => ($jsonValue['alias'] != null) ? $jsonValue['alias'] : null,
					'coordinates' => $coordinates,
					'types'		  => ($jsonValue['types'] != null) ? $jsonValue['types'] : '',
					'address'	  => $jsonValue['address'],
					'place_id'	  => $jsonValue['place_id'],
					'bounds'	  => ($jsonValue['name'] != null) ? $jsonValue['bounds'] : '',
					'review'	  => 1];
				$response[]		= $responseData;
			}
			$returnSet->setStatus(true);
			$returnSet->setData($response, false);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function deactivate()
	{
		$returnSet = new ReturnSet();
		$authToken = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		try
		{
			$JWToken = \JWTokens::generateToken($authToken);
			$url	 = $this->getUrlByJwt($JWToken);
			$returnSet->setStatus(true);
			$returnSet->setData($url);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	private function getUrlByJwt($JWToken)
	{
		$dataArr  = ['jwtoken' => $JWToken, 'type' => 'jwtValidation'];
		$jsonData = \Filter::removeNull(json_encode($dataArr));
		$encrdata = \Filter::encrypt($jsonData);
		$urlenc	  = Yii::app()->createAbsoluteUrl('users/deactive', ['rdata' => $encrdata]);
		$url	  = \Filter::shortUrl($urlenc);
		return $url;
	}
}
