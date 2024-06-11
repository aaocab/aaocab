<?php

use CJSON;

include_once(dirname(__FILE__) . '/BaseController.php');

class UsersController extends BaseController
{

	public $newHome	 = '';
	public $layout	 = '//layouts/column1';
	public $afterVal = '';
	public $email_receipient;

	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter + create, signin',
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
//		Yii::log("Google sign in data1: " . serialize($_REQUEST), CLogger::LEVEL_WARNING, 'system.api.images');
//		Yii::log("Google sign in data2: " . serialize($_REQUEST), CLogger::LEVEL_ERROR, "system.api.images");
		$pass		= uniqid(rand(), TRUE);
		$isVendor	= Yii::app()->request->cookies['isVendor']->value;
		$telegramId = Yii::app()->request->cookies['telegramId']->value;
		if ($_REQUEST['isFlexxi'])
		{
			setcookie('isFlexxi', true);
		}
		return array(
			'oauth'		 => array(
// the list of additional properties of this action is below
				'class'			  => 'ext.hoauth.HOAuthAction',
				// Yii alias for your user's model, or simply class name, when it already on yii's import path
// default value of this property is: User
				'model'			  => 'Users',
				'alwaysCheckPass' => false,
				'useYiiUser'	  => false,
				// map model attributes to attributes of user's social profile
// model attribute => profile attribute
// the list of avaible attributes is below
				'attributes'	  => array(
					'usr_email'			   => 'email',
					'email'				   => 'email',
					'gender'			   => 'gender',
					'usr_name'			   => 'firstName',
					'usr_lname'			   => 'lastName',
					'usr_create_platform'  => 1,
					'usr_country'		   => 'country',
					'usr_mobile'		   => 'phone',
					'usr_address1'		   => 'address',
					'usr_address2'		   => 'region',
					'usr_city'			   => 'city',
					'usr_zip'			   => 'zip',
					'usr_profile_pic_path' => 'photoURL',
					'usr_email_verify'	   => 1,
					'usr_password'		   => $pass,
					'new_password'		   => $pass,
					'repeat_password'	   => $pass,
				),
			),
			// this is an admin action that will help you to configure HybridAuth
// (you must delete this action, when you'll be ready with configuration, or
// specify rules for admin role. User shouldn't have access to this action!)
			'oauthadmin' => array(
				'class' => 'ext.hoauth.HOAuthAdminAction',
			),
			'REST.'		 => 'RestfullYii.actions.ERestActionProvider',
			'captcha'	 => array(
				'class' => 'CaptchaExtendedAction',
			),
		);
	}

	/**
	 * 
	 * @param Users $user
	 * @param integer $isNewUser
	 * @return boolean
	 */
	public function hoauthAfterLogin(Users $user, $isNewUser)
	{

		if ($isNewUser || (!file_exists(PUBLIC_PATH . DIRECTORY_SEPARATOR . $user->usr_profile_pic)) || $user->usr_profile_pic == '' || filesize(PUBLIC_PATH . DIRECTORY_SEPARATOR . $user->usr_profile_pic) == 0 || $user->usr_gender == '')
		{
			if ($isNewUser && Yii::app()->request->cookies['gozo_refferal_id']->value != '')
			{
				$refferalCode = Yii::app()->request->cookies['gozo_refferal_id']->value;
				Users::processReferralCode($user, $refferalCode);
			}
			$fetchdata	 = UserOAuth::model()->attributes;
			$profileData = json_decode(json_encode(unserialize($fetchdata['profile_cache'])));
			$picdata	 = $profileData->photoURL;
			if ($user->usr_gender == '' && $profileData->gender != '')
			{
				$genderList		  = Users::model()->reverseGenderList;
				$user->usr_gender = $genderList[$profileData->gender];
			}

			if (((!file_exists(PUBLIC_PATH . DIRECTORY_SEPARATOR . $user->usr_profile_pic)) || $user->usr_profile_pic == '' || filesize(PUBLIC_PATH . DIRECTORY_SEPARATOR . $user->usr_profile_pic) == 0) && $picdata != '')
			{
				$arrContextOptions = array(
					"ssl" => array(
						"verify_peer"	   => false,
						"verify_peer_name" => false,
					),
				);
				if ($user->usr_profile_pic_path == '' && $picdata != '')
				{
					$user->usr_profile_pic_path = $picdata;
				}
				if ($user->usr_profile_pic_path)
				{
					$profilePic			   = strtolower('images/profiles/' . $user->user_id . str_replace(' ', '', $user->usr_name)) . rand(10000, 99999) . '.jpg';
					file_put_contents(
							$profilePic, file_get_contents($user->usr_profile_pic_path, false, stream_context_create($arrContextOptions))
					);
					$user->usr_profile_pic = '/' . $profilePic;
				}
			}
		}

		if (Yii::app()->user->returnUrl != null && Yii::app()->user->returnUrl != '/')
		{
			$this->redirect(Yii::app()->user->returnUrl);
			return;
		}
		if (isset($_COOKIE['datavalpost']))
		{
			$cookie = $_COOKIE['datavalpost'];
			$cookie = stripslashes($cookie);
			$arr	= json_decode($cookie, true);
			if ($arr['returnUrl'] != '')
			{
				$this->redirect($arr['returnUrl']);
			}
		}
		if (isset($_COOKIE['isFlexxi']) && $_COOKIE['isFlexxi'] == 1)
		{
			unset($_COOKIE['isFlexxi']);
			$res = setcookie('isFlexxi', '', time() - 3600);
			$this->redirect(['index/index', 'isFlexxi' => 1]);
		}

		$isVendor	= Yii::app()->request->cookies['isVendor']->value;
		$telegramId = Yii::app()->request->cookies['telegramId']->value;
		if ($isVendor == 1)
		{
			$this->redirect(['index/VendorAuthentication', 'isVendor' => $isVendor, 'telegramId' => $telegramId, 'fetchdata' => $fetchdata, 'contactId' => $contactId]);
		}

		$this->redirect('/');
	}

	public function accessRules()
	{
		return array(
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions' => array('creditlist', 'index', 'create', 'logout', 'logoutv3', 'refreshuserdata',
					'sideprofile', 'countrytostate', 'profile', 'redeemgiftcard', 'usewallet', 'transfer', 'savebankdetails', 'paytransfer'),
				'users'	  => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array('fbShareTemplate', 'refer', 'fbShareTemplate1', 'fbShareLink', 'gsharetemplate', 'index', 'refreshNav', 'signin', 'signup', 'createLog', 'view', 'changePassword', 'confirmsignup', 'forgotpass', 'oauthadmin', 'oauth',
					'forgotpassword', 'userforgotpassword', 'resetpassword', 'VerifyAndResetPassword', 'partialsignin',
					'partialsignup', 'verifyemail', 'validateemail', 'userdata', 'agentapi', 'validategenderflexxi',
					'sociallogin', 'linkVendor', 'getUserIdAfterSocialLogin', 'sosUrl', 'loginVO', 'auth', 'deactive', 'deactiveV1', 'partnerDeactiveV1', 'partnerDeactive',
					'otpVerify', 'regVisitor', 'ResendOtp', 'sendOTP', 'VerifyPass', 'SignupOTP', 'ProcessSignup', 'verifyOTP', 'GetQRPathById', 'GetQRCode', 'captchaVerify', 'captchaVerifySignup', 'verifyUserName', 'verifyPassword', 'signupOTPNew', 'CreateOTPObj', 'captchaVerifyNew', 'resendOtpForForgotPassword', 'captchaskiplogin',
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'	  => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions' => array('admin', 'delete'),
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

			$ri = array('/forgotpass', '/profiledetails1',
				'/changepassword',
				'/validate', '/validateversion', '/registration', '/login', '/devicetokenregister', '/notification_credit', '/source_citylist', '/destination_citylist', '/devicetokenregister1', '/credit_history', '/custbookingdetails', '/statusdetails', '/addSOSContact', '/sosContactList', '/sosSmsTrigger', '/updateSosTriggers', '/devicetokenfcm');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.get.devicetokenregister.render', function () {
			$successDeviceToken = false;
			$errorsDeviceToken	= "";
			$process_sync_data	= Yii::app()->request->getParam('data');
			$dataDeviceToken	= array_filter(CJSON::decode($process_sync_data, true));
			$token				= $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$appToken			= AppTokens::model()->find('apt_token_id = :token', array('token' => $token));
			if (!$appToken)
			{
				$appToken = new AppTokens();
			}
			if (count($dataDeviceToken) > 0)
			{
				$appToken->apt_device		= $dataDeviceToken['device_info'];
				$appToken->apt_last_login	= new CDbExpression('NOW()');
				$appToken->apt_user_type	= 1;
				$appToken->apt_apk_version	= $dataDeviceToken['apk_version'];
				$appToken->apt_ip_address	= \Filter::getUserIP();
				$appToken->apt_os_version	= $dataDeviceToken['version'];
				$appToken->apt_device_token = $dataDeviceToken['apt_device_token'];
				$appToken->apt_device_uuid	= $dataDeviceToken['apt_device_uuid'];
				$appToken->scenario			= 'gcm';
				$appToken->apt_user_type	= 1;
				$successDeviceToken			= $appToken->save();
				$errorsDeviceToken			= $appToken->getErrors();
			}
			$emailGroup		 = Yii::app()->request->getParam('emailgroup', "");
			$success		 = "";
			$userId			 = "";
			$sessionId		 = "";
			$userModel		 = "";
			$errorsAutologin = "";
			$corporateModel	 = "";
			if ($emailGroup != "")
			{
				$data	= CJSON::decode($emailGroup, true);
				$emails = [];
				foreach ($data as $value)
				{
					$okay = filter_var($value, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $value);
					if ($okay)
					{
						array_push($emails, $value);
					}
				}
				$exist = $this->isEmailAlreadyExist1($emails, $dataDeviceToken);
				if ($exist)
				{
					$success   = 'existlogin';
					$userId	   = Yii::app()->user->getId();
					$sessionId = Yii::app()->getSession()->getSessionId();
					$userModel = Users::model()->findByPk($userId);
					$userName  = $userModel->usr_name;
					$msg	   = "Login Successfull";
				}
				else
				{
					$registeremail = $this->registernew($data);
					if ($registeremail != '')
					{
						$exist1		= Users::model()->findByEmail($registeremail);
						$emailGroup = $exist1->usr_email;
						$password	= $exist1->usr_password;
						$identity	= new UserIdentity($emailGroup, $password);
						if ($identity->authenticate())
						{
							$userID	   = $identity->getId();
							$userModel = Users::model()->findByPk($userID);
							Yii::app()->user->login($identity);
							$sessionId = Yii::app()->getSession()->getSessionId();
							$msg	   = "Login Successfull";

							$appTokenModel1 = AppTokens::model()->findAll('apt_device_uuid=:device AND apt_device_token<>:token AND apt_status=1', array('device' => $dataDeviceToken['apt_device_uuid'], 'token' => $dataDeviceToken['apt_device_token']));
							foreach ($appTokenModel1 as $appTokenM)
							{
								if (count($appTokenM) > 0)
								{
									$appTokenM->apt_status = 0;
									$appTokenM->update();
								}
							}



							$appTokenModel = AppTokens::model()->find('apt_device_uuid=:device AND apt_device_token=:token AND apt_status=1', array('device' => $dataDeviceToken['apt_device_uuid'], 'token' => $dataDeviceToken['apt_device_token']));
							if (count($appTokenModel) > 0)
							{
								
							}
							else
							{
								$appTokenModel = new AppTokens();
							}

							$appTokenModel->apt_device		 = $dataDeviceToken['device_info'];
							$appTokenModel->apt_last_login	 = new CDbExpression('NOW()');
							$appTokenModel->apt_apk_version	 = $dataDeviceToken['apk_version'];
							$appTokenModel->apt_ip_address	 = \Filter::getUserIP();
							$appTokenModel->apt_os_version	 = $dataDeviceToken['version'];
							$appTokenModel->apt_device_uuid	 = $dataDeviceToken['apt_device_uuid'];
							$appTokenModel->apt_user_id		 = $userID;
							$appTokenModel->apt_token_id	 = Yii::app()->getSession()->getSessionId();
							$appTokenModel->apt_user_type	 = 1;
							$appTokenModel->apt_device_token = $dataDeviceToken['apt_device_token'];
							$suc							 = $appTokenModel->save();
							$errorsAutologin				 = $appTokenModel->getErrors();
							Yii::log("new app token saved :" . $suc . ":  success", CLogger::LEVEL_INFO);
							$success						 = 'newregisterlogin';
							$userId							 = Yii::app()->user->getId();
							$sessionId						 = Yii::app()->getSession()->getSessionId();
							$userModel						 = Users::model()->findByPk($userId);
							$userName						 = $userModel->usr_name;
							$msg							 = "Login Successfull";
						}
					}
				}
			}
			$userModel->usr_password = '';
//            if ($userModel->usr_corporate_id != '') {
//                $corporateModel = Corporate::model()->findByPk($userModel->usr_corporate_id);
//            }
			return $this->renderJSON([
				'type' => 'raw',
				'data' => array(
					'successAutoLogin'	 => $success,
					'successDeviceToken' => $successDeviceToken,
					'userID'			 => $userId,
					'sessionId'			 => $sessionId,
					'userModel'			 => $userModel,
					'errors'			 => $errorsDeviceToken,
					'errorsAutologin'	 => $errorsAutologin,
					'corporateModel'	 => $corporateModel,
				)
			]);
		});

		$this->onRest('req.get.devicetokenregister1.render', function () {
			$success		   = false;
			$errors			   = "";
			$process_sync_data = Yii::app()->request->getParam('data');
			$data			   = array_filter(CJSON::decode($process_sync_data, true));
			$token			   = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$appToken		   = AppTokens::model()->find('apt_token_id = :token', array('token' => $token));
			if (!$appToken)
			{
				$appToken = new AppTokens();
			}
			if (count($data) > 0)
			{
				$appToken->apt_device		= $data['device_info'];
				$appToken->apt_last_login	= new CDbExpression('NOW()');
				$appToken->apt_user_type	= 1;
				$appToken->apt_apk_version	= $data['apk_version'];
				$appToken->apt_ip_address	= \Filter::getUserIP();
				$appToken->apt_os_version	= $data['version'];
				$appToken->apt_device_token = $data['apt_device_token'];
				$appToken->apt_device_uuid	= $data['apt_device_uuid'];
				$appToken->apt_token_id		= $token;
				$appToken->scenario			= 'gcm';
				$success					= $appToken->save();
				$errors						= $appToken->getErrors();
			}
			return $this->renderJSON([
				'type' => 'raw',
				'data' => array(
					'success' => $success,
					'errors'  => $errors,
				)
			]);
		});

		$this->onRest('req.post.registration.render', function () {
			if ($_REQUEST != '')
			{
				$register_data	   = Yii::app()->request->getParam('data');
				$isSocialLogin	   = Yii::app()->request->getParam('isSocialLogin');
				Logger::create("isSocialLogin :: " . $isSocialLogin, CLogger::LEVEL_INFO);
				$provider		   = Yii::app()->request->getParam('provider');
				$process_sync_data = Yii::app()->request->getParam('social_data');
				$profile_image_url = Yii::app()->request->getParam('social_profile_image_url', '');
				Logger::create("provider :: " . $provider, CLogger::LEVEL_INFO);
				Logger::create("social_data :: " . $process_sync_data, CLogger::LEVEL_INFO);
				Logger::create("profile_image_url :: " . $profile_image_url, CLogger::LEVEL_INFO);
				Logger::create("data1 params register_data :: " . $register_data, CLogger::LEVEL_INFO);
				$type			   = 'signup';
				$data			   = CJSON::decode($register_data, true);
				$userModel		   = Users::model()->getByEmail($data['usr_email']);
				if (!$userModel)
				{

					if ($isSocialLogin == 1)
					{
						$pass					 = uniqid(rand(), TRUE);
						$data['usr_password']	 = $pass;
						$data['repeat_password'] = $pass;
					}


					$result = $this->registerpost($data);
					if ($result['success'] == 'true')
					{
						$status = $this->loginpost($data, $type);
						if ($status['success'] == true)
						{
							$success				 = true;
							$userId					 = Yii::app()->user->getId();
							$refArr					 = Users::model()->getRefercode($userId);
							$refMsg					 = $refArr['refMessage'];
							$userModel				 = Users::model()->findByPk($userId);
							$userName				 = $userModel->usr_name;
							$userModel->usr_password = '';
							$msg					 = "Login Successful";
							$sessionId				 = $status['sessionId'];
						}
						else
						{
							$success = false;
							$msg	 = "Invalid Username/Password";
						}
						return CJSON::encode(['success'		=> $success, 'get'			=> $_GET, 'message'		=> $msg,
									'refer_message' => $refMsg,
									'sessionId'		=> $sessionId, 'userId'		=> $userId,
									'userModel'		=> $userModel, 'userName'		=> $userName]);
					}
				}
				else
				{
					$success = false;
					$msg	 = 'email exist';
				}
				return $this->renderJSON([
							'type'	 => 'raw',
							'data'	 => $result,
							'get'	 => $_GET,
							'result' => $result,
							'userID' => $result['userID'],
							'errors' => $result['errors']
				]);
			}
		});

		$this->onRest('req.get.login.render', function () {
			if ($_REQUEST != '')
			{
				$login_data = Yii::app()->request->getParam('data');
				Logger::create("data1 params :: " . $login_data, CLogger::LEVEL_INFO);
				$type		= 'login';

				$data = CJSON::decode($login_data, true);

				$result = $this->loginpost($data, $type);
				if ($result['success'] == true)
				{
					$success					= true;
					$userId						= Yii::app()->user->getId();
					$refArr						= Users::model()->getRefercode($userId);
					$refMsg						= $refArr['refMessage'];
					$userModel					= Users::model()->findByPk($userId);
					$userName					= $userModel->usr_name;
					$userModel->usr_password	= '';
					$msg						= "Login Successful";
					$sessionId					= $result['sessionId'];
					$userModel->usr_profile_pic = Users::getImageUrl($userModel->usr_profile_pic);
				}
				else
				{
					$success = false;
					$msg	 = ($result['message']) ? $result['message'] : "Invalid Username/Password";
				}
				return CJSON::encode(['success'		=> $success,
//					'get' => $_GET,
							'message'		=> $msg,
							'refer_message' => $refMsg,
							'sessionId'		=> $sessionId,
							'userId'		=> $userId,
							'userModel'		=> $userModel,
							'userName'		=> $userName,
				]);
			}
		});

		$this->onRest('req.get.changepassword.render', function () {
			$status = $this->changePassword();
			return $this->renderJSON([
				'type' => 'raw',
				'data' => array(
					'success' => $status['status'],
					'message' => $status['message']
					, 'data'	  => Yii::app()->getSession()->getSessionId()
				),
			]);
		});

		$this->onRest('req.get.forgotpass.render', function () {
			$result = $this->actionForgotpassword();
			return $this->renderJSON(['type' => $result['type'], 'success' => $result['success'], 'code' => $result['code']]);
		});

		$this->onRest('req.get.userlogout.render', function () {
			$token	   = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$applogout = AppTokens::model()->find('apt_token_id = :token', array('token' => $token));
			if ($applogout)
			{
				$applogout->apt_status = 0;
				$applogout->apt_logout = new CDbExpression('NOW()');
				$applogout->save();
			}
			return $this->renderJSON([
				'type' => 'raw',
				'data' => array(
					'success' => true,
					'message' => "User logged out successfully",
				)
			]);
		});

		$this->onRest('req.post.source_citylist.render', function () {
			$last_updated = Yii::app()->request->getParam('last_update');
			$cities		  = Cities::model()->getSourceCityList($last_updated);
			if ($cities)
			{
				$result = ['success' => true, 'cities' => $cities];
			}
			else
			{
				$result = ['success' => false, 'errors' => ['1' => 'Something went wrong']];
			}
			return $this->renderJSON([
				'type' => 'raw',
				'data' => array(
					'result' => $result,
				)
			]);
		});

		$this->onRest('req.get.destination_citylist.render', function () {
			$scity	= Yii::app()->request->getParam('scity');
			$cities = Cities::model()->getDestinationCityList($scity);
			if ($cities)
			{
				$result = ['success' => true, 'cities' => $cities];
			}
			else
			{
				$result = ['success' => false, 'errors' => ['1' => 'Something went wrong']];
			}
			return $this->renderJSON([
				'type' => 'raw',
				'data' => array(
					'result' => $result,
				)
			]);
		});

		$this->onRest('req.post.profile.render', function () {
			try
			{
				$success	= false;
				$saved_path = "";
				$adrs1		= Yii::app()->request->getParam('address1');
				$adrs2		= Yii::app()->request->getParam('address2');
				$adrs3		= Yii::app()->request->getParam('address3');
				$zip		= Yii::app()->request->getParam('zipcode');
				$city		= Yii::app()->request->getParam('city');
				$phonecode	= Yii::app()->request->getParam('usr_country_code');
				$phone		= Yii::app()->request->getParam('phone');
				$gender		= Yii::app()->request->getParam('gender');
				$username	= Yii::app()->request->getParam('name');
				$lastname	= Yii::app()->request->getParam('lname');
				$userId		= Yii::app()->user->getId();
				$userModel	= Users::model()->findByPk($userId);
				if ($userModel != '')
				{
					$userModel->usr_address1	 = $adrs1;
					$userModel->usr_address2	 = $adrs2;
					$userModel->usr_address3	 = $adrs3;
					$userModel->usr_zip			 = $zip;
					$userModel->usr_city		 = $city;
					$userModel->usr_country_code = $phonecode;
					$userModel->usr_mobile		 = $phone;
					$userModel->usr_gender		 = $gender;
					$userModel->usr_name		 = $username;
					$userModel->usr_lname		 = $lastname;
					$image						 = $_FILES['image']['name'];
					$imagetmp					 = $_FILES['image']['tmp_name'];
					if ($image != '')
					{
						$image	   = $userId . "_" . date('Ymd_His') . $image;
						$file_path = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'uploadedFiles' . DIRECTORY_SEPARATOR . $userId;
						if (!is_dir($file_path))
						{
							mkdir($file_path);
						}
						$file_name = basename($image);
						$f		   = $file_path;
						$file_path = $file_path . DIRECTORY_SEPARATOR . $file_name;
						file_put_contents(PUBLIC_PATH . '/testFile.txt', $f . ' ==== ' . $file_name);
						$efile	   = $file_path . DIRECTORY_SEPARATOR . $file_name;
						Yii::log("Image Path: \n\t Temp: " . $imagetmp . "\n\t Path: " . $f, CLogger::LEVEL_INFO, 'system.api.images');
						if ($this->img_resize($imagetmp, 1200, $f, $file_name))
						{
							$saved_path						 = $userModel->usr_profile_pic_path = substr($file_path, strlen(PUBLIC_PATH));
						}
					}
					$userModel->save();
					$success = true;
					$message = $userModel->error;
				}
			}
			catch (Exception $e)
			{
				Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
				Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
				throw $e;
			}
			return $this->renderJSON([
				'type' => 'raw',
				'data' => array(
					'success'  => $success,
					'message'  => $message,
					'img_path' => $saved_path,
					'data'	   => Yii::app()->getSession()->getSessionId()
				),
			]);
		});

		$this->onRest('req.post.profileupdate.render', function () {
			$success	= false;
			$saved_path = "";
			$adrs1		= Yii::app()->request->getParam('address1');
			$adrs2		= Yii::app()->request->getParam('address2');
			$adrs3		= Yii::app()->request->getParam('address3');
			$zip		= Yii::app()->request->getParam('zipcode');
			$city		= Yii::app()->request->getParam('city');
			$phonecode	= Yii::app()->request->getParam('usr_country_code');
			$phone		= Yii::app()->request->getParam('phone');
			$gender		= Yii::app()->request->getParam('gender');
			$username	= Yii::app()->request->getParam('fname');
			$lastname	= Yii::app()->request->getParam('lname');
			$userInfo	= UserInfo::getInstance();
			$userId		= $userInfo->getUserId();
			$userModel	= Users::model()->findByPk($userId);
			Logger::create("userId :: " . $userId);

			if ($userModel)
			{

				try
				{
					$userModel->usr_address1	 = $adrs1 != '' ? $adrs1 : $userModel->usr_address1;
					$userModel->usr_address2	 = $adrs2 != '' ? $adrs2 : $userModel->usr_address2;
					$userModel->usr_address3	 = $adrs3 != '' ? $adrs3 : $userModel->usr_address3;
					$userModel->usr_zip			 = $zip != '' ? $zip : $userModel->usr_zip;
					$userModel->usr_city		 = $city != '' ? $city : $userModel->usr_city;
					$userModel->usr_country_code = $phonecode != '' ? $phonecode : $userModel->usr_country_code;
					$userModel->usr_mobile		 = $phone != '' ? $phone : $userModel->usr_mobile;
					$userModel->usr_gender		 = $gender != '' ? $gender : $userModel->usr_gender;
					$userModel->usr_name		 = $username != '' ? $username : $userModel->usr_name;
					$userModel->usr_lname		 = $lastname != '' ? $lastname : $userModel->usr_lname;
					$image						 = $_FILES['image']['name'];
					$imagetmp					 = $_FILES['image']['tmp_name'];
					$dataR						 = $_REQUEST;

					Yii::log("Full data: " . json_encode($dataR));
					//$tempImage	 = $_POST['image'];
					//$name	 = $_POST['name'];
					Logger::create("Data:: " . json_encode($dataR));
					//$image = base64_decode($imagetmp);
					if ($image != '')
					{
						$name	   = $userId . "_" . date('Ymd_His') . $image;
						$file_path = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'profiles';
						$file_name = basename($name);
						$f		   = $file_path;
						$file_path = $file_path . DIRECTORY_SEPARATOR . $file_name;
						file_put_contents($file_path, $image);
						Yii::log("Image Path: \n\t Temp: " . $image . "\n\t Path: " . $f, CLogger::LEVEL_INFO, 'system.api.images');
						if ($this->img_resize($imagetmp, 1200, $f, $name))
						{
							$userModel->usr_profile_pic_path = substr($file_path, strlen(PUBLIC_PATH));
							$userModel->usr_profile_pic_path = str_replace("\\", "/", $userModel->usr_profile_pic_path);
						}
					}
					$userModel->usr_profile_pic = $userModel->usr_profile_pic_path;

					if ($userModel->save())
					{
						Yii::log(" profile saved", CLogger::LEVEL_INFO);
					}
					else
					{
						Yii::log(" profile not saved :: " . $userModel->error, CLogger::LEVEL_INFO);
					}
					$saved_path					= $userModel->usr_profile_pic_path;
					$success					= true;
					$userModel->usr_password	= '';
					$userModel->usr_profile_pic = Users::getImageUrl($userModel->usr_profile_pic);
					$saved_path					= $userModel->usr_profile_pic;
				}
				catch (Exception $e)
				{
					Logger::create("Exception   ");
					$message = $userModel->error;
					Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
					Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
					throw $e;
				}
			}
			return $this->renderJSON([
				'type' => 'raw',
				'data' => array(
					'success'  => $success,
					'message'  => $message,
					'img_path' => $saved_path,
					'data'	   => $userModel,
				),
			]);
		});

		$this->onRest('req.post.profiledetails1.render', function () {
			$userInfo = UserInfo::getInstance();
			$userId	  = $userInfo->getUserId();

			Logger::create("userId :: " . $userId);
			$userModel = Users::model()->profiledetails($userId);

			if ($userModel != "")
			{
				unset($userModel['usr_password']);

				$success = true;
			}
			else
			{
				$error	 = $userModel->error;
				$success = false;
			}
			$userModel['usr_profile_pic'] = Users::getImageUrl($userModel['usr_profile_pic']);
			Logger::create("userModel :: " . json_encode($userModel));

//			$data = JSONUtil::convertModelToArray($userModel);
			return $this->renderJSON([
				'type' => 'raw',
				'data' => array(
					'success' => $success,
					'error'	  => $error,
					'data'	  => $userModel,
				)
			]);
		});

		$this->onRest('req.get.validateversion.render', function () {
			$success = false;
			$active	 = 0;

			$msg				  = "Invalid Version";
			$sessioncheck		  = '';
			$process_sync_data	  = Yii::app()->request->getParam('data');
			$data1				  = CJSON::decode($process_sync_data, true);
			$data				  = array_filter($data1);
			$plateform			  = Yii::app()->request->getParam('platform');
			$activeAndroidVersion = Config::get("Version.Android.consumer"); // Yii::app()->params['versionCheck']['consumer'];
			$activeIOSVersion	  = Config::get("Version.Ios.consumer"); //Yii::app()->params['versionCheck']['consumerios'];
			$apt_apk_version	  = $data['apt_apk_version'];
			$activeVersion		  = ($plateform == 'IOS') ? $activeIOSVersion : $activeAndroidVersion;
			if (version_compare($apt_apk_version, $activeVersion) >= 0)
			{
				$active		  = 1;
				$success	  = true;
				$msg		  = "Valid Version";
				$sessioncheck = Yii::app()->params['consumerappsessioncheck'];
			}
			$result = array('active' => $active, 'success' => $success, 'message' => $msg, 'sessioncheck' => $sessioncheck);
			return $this->renderJSON([
				'type' => 'raw',
				'data' => array(
					'success'	   => $result['success'],
					'message'	   => $result['message'],
					'active'	   => $result['active'],
					'sessioncheck' => $result['sessioncheck'],
					'version'	   => $activeVersion,
					'sessionId'	   => Yii::app()->getSession()->getSessionId(),
				)
			]);
		});

		$this->onRest('req.get.validate.render', function () {
			$process_sync_data = Yii::app()->request->getParam('data');
//			$process_sync_data	 ='{"apt_os_version":25,"apt_apk_version":"3.7.81127","apt_ip_address":"","apt_token_id":"8e75008e1d8113705ab2fae08cc676ee"}';
			$data1			   = CJSON::decode($process_sync_data, true);
			$data			   = array_filter($data1);
			$activeVersion	   = Config::get("Version.Android.consumer"); //Yii::app()->params['versionCheck']['consumer'];
			$id				   = Yii::app()->user->id;
			$result			   = $this->getValidationApp($data, $id, $activeVersion);
			if ($result['success'])
			{
				$model = Users::model()->findByPk($id);
			}
			return $this->renderJSON([
				'type' => 'raw',
				'data' => array(
					'success' => $result['success'],
					'message' => $result['message'],
					'active'  => $result['active'],
					'version' => $activeVersion,
					'data'	  => $model,
				)
			]);
		});

		$this->onRest('req.get.credit_history.render', function () {
			$decision				  = Yii::app()->request->getParam('decision');
			$creditModel			  = new UserCredits();
			$creditModel->ucr_user_id = Yii::app()->user->getId();
			$success				  = false;
			if ($decision == 'active')
			{
				$data	 = $creditModel->getCreditsList('1');
				$success = true;
			}
			else
			{
				$data	 = $creditModel->getCreditsList('2');
				$success = true;
			}
			foreach ($data['recordSet'] as $key => $value)
			{
				unset($data['recordSet'][$key]['ucr_maxuse_type']);
				unset($data['recordSet'][$key]['STATUS']);
				unset($data['recordSet'][$key]['status']);
			}
			$totalAmount = $creditModel->getTotalActiveCredits();
			return $this->renderJSON([
				'type' => 'raw',
				'data' => array(
					'recordSet'	  => $data['recordSet'],
					'count'		  => $data['count'],
					'totalAmount' => $totalAmount,
					'success'	  => $success
				)
			]);
		});

		$this->onRest('req.get.notification_credit.render', function () {
			$userId	   = Yii::app()->user->getId();
			$ntfId	   = Yii::app()->request->getParam('ntf_id');
			$coinValue = UserNotification::model()->findByUserAndNtf($userId, $ntfId)->unfNtf->ntf_coin_value;
			$success   = UserCredits::model()->addCreditsForNotification($userId, $ntfId, $coinValue);
			if ($success)
			{
				$result = ['success' => $success, 'message' => 'Congratulations! You have earned ₹' . $coinValue . ' gozo coins'];
			}
			else
			{
				$result = ['success' => $success, 'errors' => 'Sorry you have already got gozo coins for this notification'];
			}
			return $this->renderJSON([
				'type' => 'raw',
				'data' => array(
					'result' => $result,
				)
			]);
		});

		$this->onRest('req.get.linkcorporate.render', function () {
			$code	 = Yii::app()->request->getParam('corporate_code');
			$success = false;
			$id		 = Yii::app()->user->id;
			if ($id != '')
			{
				$userModel = Users::model()->findByPk($id);
				// $corporateModel = Corporate::model()->find('crp_code=:code', ['code' => $code]);
				if ($corporateModel)
				{
					if ($corporateModel->crp_contact != '')
					{
						$otp							  = rand(100100, 999999);
						$userModel->usr_verification_code = $otp;
						$userModel->save();
						$msgCom							  = new smsWrapper();
						$username						  = $userModel->usr_name . " " . $userModel->usr_lname . "(" . $userModel->usr_email . ")";
						$msgCom->linkCorporateOTP($corporateModel->crp_country_code, $corporateModel->crp_contact, $otp, $username);
						$emailCom						  = new emailWrapper();
						$emailCom->linkCorporateOTP($userModel, $corporateModel->crp_email);
						$success						  = true;
						$message						  = 'OTP sent successfully';
					}
					else
					{
						$message = 'Please update your corporate contact';
					}
				}
				else
				{
					$message = 'Corporate not found';
				}
			}

			return $this->renderJSON([
				'type' => 'raw',
				'data' => array(
					'success' => $success,
					'message' => $message,
				)
			]);
		});

		$this->onRest('req.get.verifycorporate.render', function () {
			$code		   = Yii::app()->request->getParam('verification_code');
			$CorporateCode = Yii::app()->request->getParam('corporate_code');
			$success	   = false;
			$message	   = 'Something went wrong';
			$id			   = Yii::app()->user->id;
			if ($id != '')
			{
				$userModel = Users::model()->findByPk($id);
				if ($userModel->usr_verification_code != '')
				{
					if ($userModel->usr_verification_code == $code)
					{
						$userModel->usr_verification_code = '';
						$corporateModel					  = Corporate::model()->find('crp_code=:code', ['code' => $CorporateCode]);
						$userModel->usr_corporate_id	  = $corporateModel->crp_id;
						$userModel->save();
						$success						  = true;
						$message						  = 'Verification Success';
						$corporateData					  = JSONUtil::convertModelToArray($corporateModel, ['crp_company', 'crp_owner', 'crp_contact', 'crp_email', 'crp_address']);
					}
					else
					{
						$message = 'Verification code not matched';
					}
				}
			}

			return $this->renderJSON([
				'type' => 'raw',
				'data' => array(
					'success' => $success,
					'message' => $message,
					'data'	  => $corporateData,
				)
			]);
		});

		$this->onRest('req.get.statusdetails.render', function () {
			$userInfo	  = UserInfo::model();
			$userId		  = $userInfo->getUserId();
			$success	  = false;
			$isSosFlag	  = 0;
			$isLastReview = 0;
			$message	  = "";
			try
			{
				$isRating  = 1;
				$lastBkgId = null;
				$lastRoute = null;
				if ($userId)
				{
					//get bookingId (on the way) 
					$data			 = Users::model()->getBookingsByUserId($userId);
					$sosContactAlert = Users::model()->isSosContactList($userId);
					foreach ($data as $value)
					{
						if ($value['bkg_id'] != '' && ($value['bkg_ride_start'] == 1) && $value['iscompleted'] == 0)
						{
							//is sosSMS Send then isSosFlag = 2 Or resolve issue isSosFlag = 1
							$isSosFlag	  = ($value['bkg_sos_sms_trigger'] == 2) ? 2 : 1;
							$sosBookingId = $value['bkg_booking_id'];
							$sosBkgId	  = $value['bkg_id'];
						}
						//if completed return 0
						if ($value['iscompleted'] == 1)
						{
							$isSosFlag = 0;
						}
					}
					$result		   = Users::model()->findLastBookingReviewById($userId);
					$lastBkgId	   = $result['bkg_id'];
					$lastBookingId = $result['bkg_booking_id'];
					$isRated	   = $result['isRated'];
					$lastRoute	   = $result['route'];
					$success	   = true;
				}
				else
				{
					throw new CHttpException(400, 'Invalid data');
				}
			}
			catch (Exception $ex)
			{
				$message = $ex->getMessage();
			}

			return $this->renderJSON([
				'type' => 'raw',
				'data' => array(
					'success'		  => $success,
					'sos_flag'		  => $isSosFlag,
					'sos_booking_id'  => $sosBookingId,
					'sos_bkg_id'	  => $sosBkgId,
					'sosContactAlert' => $sosContactAlert,
					'bkg_bkg_id'	  => $lastBkgId,
					'bkg_booking_id'  => $lastBookingId,
					'isRated'		  => $isRated,
					'route'			  => $lastRoute,
					'message'		  => $message
				)
			]);
		});

		$this->onRest('req.post.addSOSContact.render', function () {
			$data	  = Yii::app()->request->getParam('data');
			Logger::create("check Data  ::" . $data, CLogger::LEVEL_TRACE);
			$userInfo = UserInfo::model();
			$userId	  = $userInfo->getUserId();
			$success  = false;
			if ($data != '' && $userId > 0)
			{
				$userModel = Users::model()->findByPk($userId);
				if ($userModel != '')
				{
					$userModel->usr_sos = null;
					if ($userModel->save())
					{
						$userModel->usr_sos = trim($data);
						$userModel->update();
						$success			= true;
						$message			= 'Contacts Saved Successfully';
					}
				}
			}
			else
			{
				$success = false;
				$message = 'Contacts Not Saved';
			}
			return $this->renderJSON([
				'type' => 'raw',
				'data' => array(
					'success' => $success,
					'message' => $message,
				),
			]);
		});

		$this->onRest('req.get.sosContactList.render', function () {
			$success  = false;
			$userInfo = UserInfo::model();
			$userId	  = $userInfo->getUserId();
			$success  = false;
			Logger::create("UserID-->" . $userId, CLogger::LEVEL_TRACE);
			if ($userId)
			{
				$sosContactList = Users::model()->getSosContactList($userId);
				$success		= ($sosContactList != NULL) ? 'true' : 'false';
			}
			return $this->renderJSON([
				'type' => 'raw',
				'data' => array(
					'success' => $success,
					'data'	  => $sosContactList,
				)
			]);
		});

		$this->onRest('req.post.sosSmsTrigger.render', function () {
			$process_sync_data = Yii::app()->request->getParam('data');
			$data			   = CJSON::decode($process_sync_data, true);
			Logger::create("PROCESS DATA  sosSMSTrigger ::" . $process_sync_data, CLogger::LEVEL_TRACE);
			$userInfo		   = UserInfo::model();
			$userId			   = $userInfo->getUserId(); //48024;
			$bModel			   = Booking::model()->findByPk($data['bkg_id']);
			$deviceId		   = $bModel->bkgTrack->bkg_sos_device_id;
			$transaction	   = DBUtil::beginTransaction();
			try
			{
				if ($data != '')
				{
					if ($deviceId == null)
					{
						$sosContactList = Users::model()->sendNotificationToSosContact($userId, $data);
						Logger::create("SOS contact List ::" . CJSON::encode($sosContactList), CLogger::LEVEL_TRACE);
						$success		= ($sosContactList['sosContactList'] != Null) ? true : false;
						if ($success == true)
						{
							$isSosSmsTrigger = BookingTrack::model()->saveSosLocation($sosContactList['sosSmsTrigger'], $data, $userId);
						}
						Logger::create("SOS SMS TRIGGER ::" . $isSosSmsTrigger, CLogger::LEVEL_TRACE);
					}
					else
					{
						$success		 = true;
						$isSosSmsTrigger = 0;
					}

					DBUtil::commitTransaction($transaction);
				}
			}
			catch (Exception $ex)
			{
				DBUtil::rollbackTransaction($transaction);
				$message = $ex->getMessage();
				Logger::create("Errors.\n\t\t" . $message, CLogger::LEVEL_ERROR);
			}
			return $this->renderJSON([
				'type' => 'raw',
				'data' => array(
					'success'		  => $success,
					'sos_sms_trigger' => $isSosSmsTrigger,
				)
			]);
		});

		$this->onRest('req.post.updateSosTriggers.render', function () {
			$process_sync_data = Yii::app()->request->getParam('data');
			Logger::create("data ::" . $process_sync_data, CLogger::LEVEL_TRACE);
			$data			   = CJSON::decode($process_sync_data, true);
			/* @var $btrackModel BookingTrack  */
			$btrackModel	   = BookingTrack::model()->getByBkgId($data['bkg_id']);
			if ($btrackModel->bkg_sos_sms_trigger == 1)
			{
				$resultSet = ['success' => true, 'message' => 'The SOS has been already turn off by GozoTeam'];
			}
			else
			{
				$userInfo  = UserInfo::getInstance();
				$userId	   = $userInfo->getUserId();
				$UserModel = Users::model()->findByPk($userId);
				$userName  = $UserModel->usr_name . " " . $UserModel->usr_lname;
				Logger::create("user ::" . json_encode($userInfo), CLogger::LEVEL_INFO);
				$result	   = BookingTrack::model()->updateSosTriggerFlag($data, $userInfo, $userName);
				if ($result['success'] == true)
				{
					$resultSet = ['success' => $result['success'], 'message' => $result['message']];
				}
				else
				{
					$resultSet = ['success' => $result['success'], 'errors' => $result['message']];
				}
			}

			Logger::create("resultSet ::" . json_encode($resultSet), CLogger::LEVEL_INFO);
			$success = $result['success'];
			return $this->renderJSON([
				'type' => 'raw',
				'data' => $resultSet
			]);
		});

		$this->onRest('req.get.custbookingdetails.render', function () {
			$userInfo		   = UserInfo::getInstance();
			$userId			   = $userInfo->getUserId();
			$process_sync_data = Yii::app()->request->getParam('data');
			Logger::create('Customer Booking Details  ' . $process_sync_data, CLogger::LEVEL_TRACE);
			$credit_amount	   = Yii::app()->request->getParam('credit_amount') | 0;
			$data			   = CJSON::decode($process_sync_data, true);
			$id				   = $data['bkg_id'];
			$datareturn		   = array();
			$booking1		   = array();
			$success		   = false;
			try
			{
				$count = 1;
				a:
				if ($id > 0)
				{
					$model = Booking::model()->findByPk($id);
				}
				if (!$model)
				{
					throw new CHttpException(400, 'Invalid data');
				}
				if ($model != '')
				{
					//$model						 = Booking::model()->findByPk($model1->bkg_id);

					$hr							 = date('G', mktime(0, $model->bkg_trip_duration)) . " Hr";
					$min						 = (date('i', mktime(0, $model->bkg_trip_duration)) != '00') ? ' ' . date('i', mktime(0, $model->bkg_trip_duration)) . " min" : '';
					$model->trip_duration_format = $hr . $min;
					$model->trip_distance_format = $model->bkg_trip_distance . ' Km';
				}

				$datareturn['route']					   = BookingRoute::model()->getRouteName($model->bkg_id);
				$modelRating							   = Ratings::model()->getCustRatingbyBookingId($model->bkg_id);
				$datareturn['name']						   = $model->bkgUserInfo->bkg_user_fname;
				$datareturn['bkg_id']					   = $model->bkg_id;
				$datareturn['bkg_booking_id']			   = $model->bkg_booking_id;
				$datareturn['bkg_create_date']			   = $model->bkg_create_date;
				$datareturn['bkg_user_id']				   = $model->bkgUserInfo->bkg_user_id;
				$datareturn['bkg_user_name']			   = $model->bkgUserInfo->bkg_user_fname;
				$datareturn['bkg_user_lname']			   = $model->bkgUserInfo->bkg_user_lname;
				$datareturn['bkg_country_code']			   = $model->bkgUserInfo->bkg_country_code;
				$datareturn['bkg_contact_no']			   = $model->bkgUserInfo->bkg_contact_no;
				$datareturn['bkg_alternate_contact']	   = $model->bkgUserInfo->bkg_alt_contact_no;
				$datareturn['bkg_alt_country_code']		   = $model->bkgUserInfo->bkg_alt_country_code;
				$datareturn['bkg_user_email']			   = $model->bkgUserInfo->bkg_user_email;
				$datareturn['bcb_driver_phone']			   = $model->bkgBcb->bcb_driver_phone;
				$datareturn['cab_type']					   = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . '(' . $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . ')';
				$datareturn['bkg_pickup_address']		   = $model->bkg_pickup_address;
				$datareturn['bkg_drop_address']			   = $model->bkg_drop_address;
				$datareturn['no_of_days']				   = $model->no_of_days;
				$datareturn['trip_duration_format']		   = $model->trip_duration_format;
				$datareturn['trip_distance_format']		   = $model->trip_distance_format;
				$datareturn['bkg_bcb_id']				   = $model->bkg_bcb_id;
				$datareturn['bkg_pickup_date']			   = $model->bkg_pickup_date;
				$datareturn['bkg_pickup_time']			   = date('h:i:s', strtotime($model->bkg_pickup_date));
				$datareturn['bkg_booking_type']			   = $model->bkg_booking_type;
				$datareturn['bkg_transfer_type']		   = $model->bkg_transfer_type;
				$datareturn['bkg_ride_start']			   = $model->bkgTrack->bkg_ride_start;
				$datareturn['bkg_from_city_id']			   = $model->bkg_from_city_id;
				$datareturn['bkg_to_city_id']			   = $model->bkg_to_city_id;
				$datareturn['bkg_user_trip_type']		   = $model->bkgAddInfo->bkg_user_trip_type;
				$datareturn['bkg_trip_distance']		   = $model->bkg_trip_distance;
				$datareturn['bkg_trip_duration']		   = $model->bkg_trip_duration;
				$datareturn['bkg_flexxi_type']			   = $model->bkg_flexxi_type;
				$datareturn['bkg_send_email']			   = $model->bkgPref->bkg_send_email;
				$datareturn['bkg_send_sms']				   = $model->bkgPref->bkg_send_sms;
				$datareturn['bkg_gozo_base_amount']		   = $model->bkgInvoice->bkg_gozo_base_amount;
				$datareturn['bkg_base_amount']			   = $model->bkgInvoice->bkg_base_amount;
				$datareturn['bkg_flexxi_base_amount']	   = $model->bkgInvoice->bkg_flexxi_base_amount;
				$datareturn['bkg_discount_amount']		   = $model->bkgInvoice->bkg_discount_amount;
				$datareturn['bkg_corporate_credit']		   = $model->bkgInvoice->bkg_corporate_credit;
				$datareturn['bkg_total_amount']			   = $model->bkgInvoice->bkg_total_amount;
				$datareturn['bkg_vendor_amount']		   = $model->bkgInvoice->bkg_vendor_amount;
				$datareturn['bkg_quoted_vendor_amount']	   = $model->bkgInvoice->bkg_quoted_vendor_amount;
				$datareturn['bkg_vendor_collected']		   = $model->bkgInvoice->bkg_vendor_collected;
				$datareturn['bkg_gozo_amount']			   = $model->bkgInvoice->bkg_gozo_amount;
				$datareturn['bkg_due_amount']			   = $model->bkgInvoice->bkg_due_amount;
				$datareturn['bkg_advance_amount']		   = $model->bkgInvoice->bkg_advance_amount;
				$datareturn['bkg_refund_amount']		   = $model->bkgInvoice->bkg_refund_amount;
				$datareturn['bkg_chargeable_distance']	   = $model->bkgInvoice->bkg_chargeable_distance;
				$datareturn['bkg_driver_allowance_amount'] = $model->bkgInvoice->bkg_driver_allowance_amount;
				$datareturn['bkg_additional_charge']	   = $model->bkgInvoice->bkg_additional_charge;
				$datareturn['bkg_is_toll_tax_included']	   = $model->bkgInvoice->bkg_is_toll_tax_included;
				$datareturn['bkg_is_state_tax_included']   = $model->bkgInvoice->bkg_is_state_tax_included;
				$datareturn['bkg_is_parking_included']	   = $model->bkgInvoice->bkg_is_parking_included;
				$datareturn['bkg_toll_tax']				   = $model->bkgInvoice->bkg_toll_tax;
				$datareturn['bkg_state_tax']			   = $model->bkgInvoice->bkg_state_tax;
				$datareturn['bkg_service_tax']			   = $model->bkgInvoice->bkg_service_tax;
				$datareturn['bkg_service_tax_rate']		   = $model->bkgInvoice->bkg_service_tax_rate;
				$datareturn['bkg_extra_km_charge']		   = $model->bkgInvoice->bkg_extra_km_charge;
				$datareturn['bkg_extra_km']				   = $model->bkgInvoice->bkg_extra_km;
				$datareturn['bkg_parking_charge']		   = $model->bkgInvoice->bkg_parking_charge;
				$datareturn['bkg_status']				   = $model->bkg_status;
				$datareturn['bkg_active']				   = $model->bkg_active;
				$datareturn['bkg_trip_otp']				   = $model->bkgTrack->bkg_trip_otp;
				$datareturn['bkg_account_flag']			   = $model->bkgPref->bkg_account_flag;
				$datareturn['bkg_credits_used']			   = $model->bkgInvoice->bkg_credits_used;
				$datareturn['bkg_trip_status']			   = $model->bkgBcb->bcb_trip_status;
				$datareturn['bkg_corporate_discount']	   = $model->bkgInvoice->bkg_corporate_discount;
				$datareturn['bkg_convenience_charge']	   = $model->bkgInvoice->bkg_convenience_charge;
				$datareturn['bkg_arrived_for_pickup']	   = $model->bkgTrack->bkg_arrived_for_pickup;
				$datareturn['is_rated']					   = ($modelRating['rtg_id'] > 0) ? '1' : '0';
				$datareturn['rtg_customer_overall']		   = $modelRating['rtg_customer_overall'];
				$success								   = true;
				$driver									   = [];
				$vehicle								   = [];
				if ($model->bkgBcb->bcb_driver_id > 0)
				{
					$driverModel	  = Drivers::model()->findByPk($model->bkgBcb->bcb_driver_id);
					$driverStatsModel = DriverStats::model()->getOverallRatingbyDriverId($model->bkgBcb->bcb_driver_id);
				}
				if ($model->bkgBcb->bcb_cab_id > 0)
				{
					$cabModel = Vehicles::model()->findByPk($model->bkgBcb->bcb_cab_id);
				}
				if (in_array($model->bkg_status, [5, 6, 7]))
				{
					$driver['bcb_driver_phone'] = $model->bkgBcb->bcb_driver_phone;
					$driver['driver_name']		= $driverModel->drv_name;
					$driver['driver_code']		= $driverModel->drv_code;
					if ($driverModel->drvContact->ctt_profile_path != null)
					{
						$driver['driver_profile_path'] = $driverModel->drvContact->ctt_profile_path;
					}
					$driver['driver_rating'] = ($driverStatsModel['driver_rating'] != null) ? $driverStatsModel['driver_rating'] : 4;
					$vehicle['cab_rating']	 = ($cabModel->vhc_overall_rating != null) ? $cabModel->vhc_overall_rating : 0;
					$vehicle['cab_number']	 = $cabModel->vhc_number;
					$vehicle['cab_model']	 = $cabModel->vhcType->vht_make . ' ' . $cabModel->vhcType->vht_model;
					$vehicle['cab_type']	 = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label . '(' . $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . ')';
					;
//         $cabModel->vhcType->vht_car_type;
				}
			}
			catch (Exception $e)
			{
				$errors = $e->getMessage();
				Logger::create('Errors : --------  ' . $errors, CLogger::LEVEL_INFO);
			}
			if ($success == true)
			{
				Logger::create('Data : --------  ' . json_encode($datareturn), CLogger::LEVEL_INFO);
			}
			return $this->renderJSON([
				'type' => 'raw',
				'data' => array_filter(array(
					'success'	 => $success,
					'model'		 => $datareturn,
					'driverInfo' => $driver,
					'cabInfo'	 => $vehicle
				))
			]);
		});

		$this->onRest('req.post.devicetokenfcm.render', function () {
			$process_sync_data = Yii::app()->request->getParam('data');
			Logger::create('process_sync_data======>' . $process_sync_data, CLogger::LEVEL_TRACE);
			$data1			   = CJSON::decode($process_sync_data, true);
			$data			   = array_filter($data1);
			$token			   = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$appToken1		   = AppTokens::model()->find('apt_device_token = :token', array('token' => $data['apt_device_token']));
			Logger::create('Token' . CJSON::encode($token) . "|||", CLogger::LEVEL_TRACE);
			Logger::create('AppTokem======>' . CJSON::encode($appToken1) . "|||", CLogger::LEVEL_TRACE);
			if ($appToken1 != '')
			{
				$appToken1->apt_status = 0;
				$appToken1->update();
			}
			if ($token == "" || $token == NULL)
			{
				$appToken = new AppTokens();
				$token	  = Yii::app()->getSession()->getSessionId();
			}
			else
			{
				$appToken = AppTokens::model()->find('apt_token_id = :token', array('token' => $token));
			}
			$appToken->apt_device_token = $data['apt_device_token'];
			$appToken->apt_device_uuid	= $data['apt_device_uuid'];
			$appToken->scenario			= 'fcm';
			$appToken->apt_user_type	= 1;
			$appToken->apt_token_id		= $token;
			$success					= $appToken->save();
			Logger::create("Response : " . CJSON::encode(['type' => 'raw', 'data' => ['success' => $success, 'sessionId' => $token, 'errors' => $appToken->getErrors()]]), CLogger::LEVEL_INFO);
			return $this->renderJSON(['type' => 'raw', 'data' => ['success' => $success, 'sessionId' => $token, 'errors' => $appToken->getErrors()]]);
		});
	}

	public function existemail($data)
	{
		foreach ($data as $value)
		{
			$email = $value;
			$exist = Users::model()->findByEmail($email);
			if (!empty($exist))
			{
				$email	  = $exist->usr_email;
				$password = $exist->usr_password;
				$identity = new UserIdentity($email, $password);
				if ($identity->authenticate())
				{
					$userID	   = $identity->getId();
					//$userModel	 = Users::model()->findByPk($userID);
					Yii::app()->user->login($identity);
					$sessionId = Yii::app()->getSession()->getSessionId();
					$msg	   = "Login Successfull";
					$appToken  = AppTokens::model()->find('apt_device_uuid=:device', array('device' => $deviceID));
					if (count($appToken) > 0)
					{
						$appToken->apt_status = 0;
						$appToken->update();
					}
					$appTokenModel				   = new AppTokens();
					$appTokenModel->apt_user_id	   = $userID;
					$appTokenModel->apt_token_id   = Yii::app()->getSession()->getSessionId();
					$appTokenModel->apt_last_login = new CDbExpression('NOW()');
					$appTokenModel->apt_user_type  = 1;
					$appTokenModel->insert();
					$success					   = 'true';
					return true;
				}
			}
			else
			{
				$success = 'false';
			}
		}
		return false;
	}

	public function isEmailAlreadyExist1($data, $dataDeviceToken)
	{
		foreach ($data as $value)
		{
			$email = $value;
			$exist = Users::model()->findByEmail($email);
			if (!empty($exist))
			{
				$email	  = $exist->usr_email;
				$password = $exist->usr_password;
				$identity = new UserIdentity($email, $password);
				if ($identity->authenticate())
				{
					$userID			= $identity->getId();
					//$userModel		 = Users::model()->findByPk($userID);
					Yii::app()->user->login($identity);
					$sessionId		= Yii::app()->getSession()->getSessionId();
					$msg			= "Login Successfull";
					$appTokenModel1 = AppTokens::model()->findAll('apt_device_uuid=:device AND apt_device_token<>:token AND apt_status=1', array('device' => $dataDeviceToken['apt_device_uuid'], 'token' => $dataDeviceToken['apt_device_token']));
					foreach ($appTokenModel1 as $appTokenM)
					{
						if (count($appTokenM) > 0)
						{
							$appTokenM->apt_status = 0;
							$appTokenM->update();
						}
					}
					$appTokenModel = AppTokens::model()->find('apt_device_uuid=:device AND apt_device_token=:token AND apt_status=1', array('device' => $dataDeviceToken['apt_device_uuid'], 'token' => $dataDeviceToken['apt_device_token']));
					if (count($appTokenModel) > 0)
					{
						
					}
					else
					{
						$appTokenModel = new AppTokens();
					}
					$appTokenModel->apt_device		 = $dataDeviceToken['device_info'];
					$appTokenModel->apt_last_login	 = new CDbExpression('NOW()');
					$appTokenModel->apt_apk_version	 = $dataDeviceToken['apk_version'];
					$appTokenModel->apt_ip_address	 = \Filter::getUserIP();
					$appTokenModel->apt_os_version	 = $dataDeviceToken['version'];
					$appTokenModel->apt_device_uuid	 = $dataDeviceToken['apt_device_uuid'];
					$appTokenModel->apt_user_id		 = $userID;
					$appTokenModel->apt_token_id	 = Yii::app()->getSession()->getSessionId();
					$appTokenModel->apt_last_login	 = new CDbExpression('NOW()');
					$appTokenModel->apt_user_type	 = 1;
					$appTokenModel->apt_device_token = $dataDeviceToken['apt_device_token'];
					$appTokenModel->save();
					$success						 = 'true';
					return true;
				}
			}
			else
			{
				$success = 'false';
			}
		}
		return false;
	}

	public function isEmailAlreadyExist($data, $apt_device_token)
	{
		foreach ($data as $value)
		{
			$email = $value;
			$exist = Users::model()->findByEmail($email);
			if (!empty($exist))
			{
				$email	  = $exist->usr_email;
				$password = $exist->usr_password;
				$identity = new UserIdentity($email, $password);
				if ($identity->authenticate())
				{
					$userID	   = $identity->getId();
					$userModel = Users::model()->findByPk($userID);
					Yii::app()->user->login($identity);
					$sessionId = Yii::app()->getSession()->getSessionId();
					$msg	   = "Login Successfull";
					$appToken  = AppTokens::model()->find('apt_device_uuid=:device', array('device' => $deviceID));
					if (count($appToken) > 0)
					{
						$appToken->apt_status = 0;
						$appToken->update();
					}
					$appToken1 = AppTokens::model()->find('apt_device_token=:device', array('device' => $apt_device_token));
					if (count($appToken1) > 0)
					{
						$appToken1->apt_status = 0;
						$appToken1->update();
					}
					$appTokenModel					 = new AppTokens();
					$appTokenModel->apt_user_id		 = $userID;
					$appTokenModel->apt_token_id	 = Yii::app()->getSession()->getSessionId();
					$appTokenModel->apt_last_login	 = new CDbExpression('NOW()');
					$appTokenModel->apt_user_type	 = 1;
					$appTokenModel->apt_device_token = $apt_device_token;
					$appTokenModel->insert();
					$success						 = 'true';
					return true;
				}
			}
			else
			{
				$success = 'false';
			}
		}
		return false;
	}

	public function registernew($data)
	{
		foreach ($data as $value)
		{

			$unamevar			 = explode("@", $value);
			$model				 = new Users;
			$model->usr_email	 = $value;
			$model->usr_name	 = $unamevar[0];
			// $pass = "abcd";
			$pass				 = rand(999, 99999);
			$model->usr_password = $pass . '';
			$model->usr_active	 = 1;
			$model->usr_ip		 = \Filter::getUserIP();
			$model->usr_device	 = UserLog::model()->getDevice();
			$model->insert();
			$user_id			 = $model->user_id;
			if ($model->usr_email != '')
			{
				$email = new emailWrapper();
				//$email->signupEmail($user_id);
				$email->signupEmailInfo($user_id, $pass);
			}
			$status = 1;
			return $model->usr_email;
		}
	}

	public function loginweb()
	{


		$email	  = Yii::app()->request->getParam('usr_email');
		$password = Yii::app()->request->getParam('usr_password');
// $type = Yii::app()->request->getParam('ect_type', 1);
		$deviceID = Yii::app()->request->getParam('deviceid');
// $macAddress = Yii::app()->request->getParam('mac_address');
// $deviceSerial = Yii::app()->request->getParam('serial');

		$deviceVersion = Yii::app()->request->getParam('version');
//$IMEI = Yii::app()->request->getParam('IMEI');
		$apkVersion	   = Yii::app()->request->getParam('apk_version');
		$ipAddress	   = Yii::app()->request->getParam('ip_address');
		$deviceInfo	   = Yii::app()->request->getParam('device_info');

		$identity = new UserIdentity($email, md5($password));

		if ($identity->authenticate())
		{

			$userID	   = $identity->getId();
			Yii::app()->user->login($identity);
			$sessionId = Yii::app()->getSession()->getSessionId();
			$msg	   = "Login Successfull";

			$appToken = AppTokens::model()->find('apt_device_uuid=:device', array('device' => $deviceID));
			if (count($appToken) > 0)
			{
				$appToken->apt_status = 0;
				$appToken->update();
			}
			$appTokenModel					= new AppTokens();
			$appTokenModel->apt_user_id		= $userID;
			$appTokenModel->apt_token_id	= Yii::app()->getSession()->getSessionId();
			$appTokenModel->apt_device		= $deviceInfo;
			$appTokenModel->apt_last_login	= new CDbExpression('NOW()');
			$appTokenModel->apt_device_uuid = $deviceID;
			$appTokenModel->apt_user_type	= 1;
			$appTokenModel->apt_apk_version = $apkVersion;
			$appTokenModel->apt_ip_address	= $ipAddress;
			$appTokenModel->apt_os_version	= $deviceVersion;
			$appTokenModel->insert();
			$success						= 'true';
		}
		else
		{
			$success = 'false';
			$msg	 = "Invalid Username/Password";
// $active = 4;
		}
		return $success;
	}

	public function loginpost($data, $type)
	{
		Logger::create("callType :: " . $type, CLogger::LEVEL_INFO);
		$email			  = $data['usr_email'];
		$password		  = $data['usr_password'];
		$deviceID		  = $data['deviceid'];
		$deviceVersion	  = $data['version'];
		$apkVersion		  = $data['apk_version'];
		$ipAddress		  = \Filter::getUserIP();
		$deviceInfo		  = $data['device_info'];
		$apt_device_token = $data['apt_device_token'];
		$isSocialLogin	  = Yii::app()->request->getParam('isSocialLogin', 0);
		$social_data	  = Yii::app()->request->getParam('social_data', '');
		Logger::create("isSocialLogin :: " . $isSocialLogin, CLogger::LEVEL_INFO);
		if ($isSocialLogin == 1)
		{
			if ($type == 'signup')
			{
				$result = Users::model()->linkAppUser(0, $social_data);
				if ($result['success'])
				{
					$user_id	 = $result['user_id'];
					$userModel	 = Users::model()->findByPk($user_id);
					$email		 = $userModel->usr_email;
					$md5password = $userModel->usr_password;
				}
			}
			else
			{
				Logger::create(" SocialLogin :: Entered", CLogger::LEVEL_INFO);
				$provider		   = Yii::app()->request->getParam('provider');
				$process_sync_data = Yii::app()->request->getParam('social_data');
				$userData		   = CJSON::decode($process_sync_data, true);
				$email			   = $userData['email'];
				Logger::create("provider :: " . $provider, CLogger::LEVEL_INFO);
				Logger::create("social_data :: " . $process_sync_data, CLogger::LEVEL_INFO);
				Logger::create("email :: " . $email, CLogger::LEVEL_INFO);

				$result['success'] = true;
				$userModel		   = Users::model()->getByEmail($email);
				if (!$userModel)
				{
					$result = ['success' => false, 'message' => 'User not found'];
					Logger::create("message :: User not found", CLogger::LEVEL_INFO);
					return $result;
				}
				else
				{

					$userid			= $userModel->user_id;
					Logger::create("message :: User exists with userid : $userid", CLogger::LEVEL_INFO);
					$isSocialLinked = Users::model()->checkSocialLinking($userModel->user_id, $provider);

					Logger::create("isSocialLinked :: " . ($isSocialLinked), CLogger::LEVEL_INFO);

					if (!$isSocialLinked)
					{
						$result = Users::model()->linkAppUser($userid);
						$userid = $result['user_id'];
						Logger::create("isSocialLinked result :: " . $result['success'], CLogger::LEVEL_INFO);
					}
					if ($result['success'])
					{

						$userModel	 = Users::model()->findByPk($userid);
						$email		 = $userModel->usr_email;
						$md5password = $userModel->usr_password;
					}
				}
			}
		}
		else
		{
			$md5password = md5($password);
		}

		$identity = new UserIdentity($email, $md5password);
		if ($identity->authenticate())
		{
			$userID	  = $identity->getId();
			Yii::app()->user->login($identity);
			$token	  = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$appToken = AppTokens::model()->findAll('((apt_device_uuid=:device AND apt_device_uuid<>\'\') OR (apt_device_token=:gcmtoken AND apt_device_token<>\'\')) AND apt_token_id<>:token', array('device' => $deviceID, 'gcmtoken' => $apt_device_token, 'token' => $token));
			if ($appToken != '')
			{
				foreach ($appToken as $value)
				{
					if (count($value) > 0)
					{
						$value->apt_status = 0;
						$value->update();
					}
				}
			}
			$appTokenModel = AppTokens::model()->find('apt_token_id = :token AND apt_token_id<>\'\' AND apt_status = :status', array('token' => $token, 'status' => 1));
			if (!$appTokenModel)
			{
				$appTokenModel				 = new AppTokens();
				$appTokenModel->apt_token_id = Yii::app()->getSession()->getSessionId();
			}
			$appTokenModel->apt_user_id		 = $userID;
			$appTokenModel->apt_device		 = $deviceInfo;
			$appTokenModel->apt_last_login	 = new CDbExpression('NOW()');
			$appTokenModel->apt_device_uuid	 = $deviceID;
			$appTokenModel->apt_user_type	 = 1;
			$appTokenModel->apt_apk_version	 = $apkVersion;
			$appTokenModel->apt_ip_address	 = $ipAddress;
			$appTokenModel->apt_os_version	 = $deviceVersion;
			$appTokenModel->apt_device_token = $apt_device_token;
			$appTokenModel->save();
			$result							 = ['success' => true, 'sessionId' => $appTokenModel->apt_token_id, 'errors' => $appTokenModel->getErrors()];
		}
		else
		{
			$result = ['success' => false];
		}
		return $result;
	}

	public function loginvendor()
	{
		$email		   = Yii::app()->request->getParam('vnd_username');
		$password	   = Yii::app()->request->getParam('vnd_password');
		$deviceID	   = Yii::app()->request->getParam('deviceid');
		$deviceVersion = Yii::app()->request->getParam('version');
		$apkVersion	   = Yii::app()->request->getParam('apk_version');
		$ipAddress	   = Yii::app()->request->getParam('ip_address');
		$deviceInfo	   = Yii::app()->request->getParam('device_info');
		$identity	   = new VendorIdentity($email, md5($password));

		if ($identity->authenticate())
		{
			$userID	   = $identity->getId();
			$userModel = Vendors::model()->findByPk($userID);
			Yii::app()->user->login($identity);
			$sessionId = Yii::app()->getSession()->getSessionId();
			$appToken  = AppTokens::model()->find('apt_device_uuid=:device', array('device' => $deviceID));
			if (count($appToken) > 0)
			{
				$appToken->apt_status = 0;
				$appToken->update();
			}
			$appTokenModel					= new AppTokens();
			$appTokenModel->apt_user_id		= $userID;
			$appTokenModel->apt_token_id	= Yii::app()->getSession()->getSessionId();
			$appTokenModel->apt_device		= $deviceInfo;
			$appTokenModel->apt_last_login	= new CDbExpression('NOW()');
			$appTokenModel->apt_device_uuid = $deviceID;
			$appTokenModel->apt_user_type	= 2;
			$appTokenModel->apt_apk_version = $apkVersion;
			$appTokenModel->apt_ip_address	= $ipAddress;
			$appTokenModel->apt_os_version	= $deviceVersion;
			$appTokenModel->insert();
			$success						= 'true';
		}
		else
		{
			$success = 'false';
			$msg	 = "Invalid Username/Password";
		}
		return $success;
	}

	public function registerpost($data)
	{

		$model						= new Users();
		$model->attributes			= $data;
		$model->usr_device			= $data['deviceid'];
		$model->usr_create_platform = Users::Platform_App;
		$model->usr_acct_type		= Users::AcctType_Verify;
		$model->usr_ip				= \Filter::getUserIP();
		$model->new_password		= $data['usr_password'];
		$model->repeat_password		= $data['repeat_password'];
		$code						= $data['usr_referred_code'];
		$success					= true;
		if ($code != '')
		{
			$userModel = Users::model()->getByReferCode($data['usr_referred_code']);
			if ($userModel)
			{
				if ($code != '')
				{
					$model->usr_referred_code = $data['usr_referred_code'];
				}
			}
			else
			{
				$errMsg	 = 'Invalid Referral Code';
				$success = false;
			}
		}
		// $refCodeMsg               = '';
		$errMsg = '';
		if ($model->validate() && $success)
		{
			$model->scenario = 'mobinsert';
			$reg			 = $model->save();
			if (!$reg)
			{
				$success = false;
				$msg	 = 'email exist';
			}
			else
			{
				$user_id = $model->user_id;
				if ($model->usr_email != '')
				{
					$email = new emailWrapper();
					$email->signupEmail($user_id);
				}
				$status	 = 1;
				$success = true;
			}
		}
		else
		{
			$errors	 = $model->errors;
			$success = 'false';
			foreach ($errors as $value)
			{
				$errMsg = $value[0];
				break;
			}
		}


		Yii::log("lname: " . $model->usr_lname, CLogger::LEVEL_INFO, 'system.api.inspection');
		$model->usr_password = '';
		$result				 = array('success' => $success,
			'errors'  => $errors,
			'error'	  => $errMsg,
			'get'	  => $data,
			'userID'  => $user_id,
			'data'	  => $model,
			'code'	  => $code
		);

		return $result;
	}

	public function register()
	{

		$model						= new Users();
// $code = rand(999, 99999);
		$lname						= Yii::app()->request->getParam('usr_lname');
		$model->usr_name			= Yii::app()->request->getParam('usr_name');
		$model->usr_lname			= $lname;
		$model->repeat_password		= Yii::app()->request->getParam('repeat_password');
		$model->new_password		= Yii::app()->request->getParam('usr_password');
		$model->usr_email			= Yii::app()->request->getParam('usr_email');
		$model->usr_password		= Yii::app()->request->getParam('usr_password');
		$model->usr_mobile			= Yii::app()->request->getParam('usr_mobile');
		$model->usr_country_code	= Yii::app()->request->getParam('usr_country_code');
		$deviceInfo					= Yii::app()->request->getParam('device_info');
		$ipAddress					= Yii::app()->request->getParam('ip_address');
		$model->usr_create_platform = Users::Platform_App;
		$model->usr_ip				= $ipAddress;
		$model->usr_device			= $deviceInfo;
		Yii::log("lname: " . $model->usr_lname, CLogger::LEVEL_INFO, 'system.api.inspection');

		if ($model->validate())
		{

			$model->scenario = 'mobinsert';
			$reg			 = $model->save();

			if (!$reg)
			{
				$success = 'false';
				$msg	 = 'email exist';
			}
			else
			{

				$user_id = $model->user_id;
				if ($model->usr_email != '')
				{
					$email = new emailWrapper();
					$email->signupEmail($user_id);
				}
				$status	 = 1;
				$success = 'true';
			}
		}
		else
		{
			$msg	 = $model->errors;
			$success = 'false';
		}

		$result = array('success' => $success,
			'message' => $msg,
			'get'	  => $data,
			'userID'  => $user_id,
			'data'	  => $model,
			'code'	  => $code
		);
		return $result;
	}

	public function register111()
	{

		$model = new Users();
// $code = rand(999, 99999);

		$model->usr_name		= Yii::app()->request->getParam('usr_name');
		$model->repeat_password = Yii::app()->request->getParam('repeat_password');

		$model->usr_email		 = Yii::app()->request->getParam('usr_email');
		$model->usr_password	 = trim(md5(Yii::app()->request->getParam('usr_password')));
		$model->usr_mobile		 = Yii::app()->request->getParam('usr_mobile');
		$model->usr_country_code = Yii::app()->request->getParam('usr_country_code');

		$deviceID	   = Yii::app()->request->getParam('deviceid');
//$macAddress = Yii::app()->request->getParam('mac_address');
		$deviceSerial  = Yii::app()->request->getParam('serial');
		$deviceInfo	   = Yii::app()->request->getParam('device_info');
		$deviceVersion = Yii::app()->request->getParam('version');
// $IMEI = Yii::app()->request->getParam('IMEI');
		$apkVersion	   = Yii::app()->request->getParam('apk_version');
		$ipAddress	   = Yii::app()->request->getParam('ip_address');

		$model->usr_ip	   = $ipAddress;
		$model->usr_device = $deviceInfo;

		if ($model->validate())
		{
// $model->scenario='mobinsert';
			$model->save('mobinsert');
//            $pKey = $model->getPrimaryKey();
//            $model->usr_activation_key = uniqid($pKey . '-');
//            $model->save();
//            $mobile = '+91' . Yii::app()->request->getParam('mobile');
			$user_id = $model->user_id;
//            $userModel = Users::model()->findByPk($user_id);
//            // $response = file_get_contents('https://rest.nexmo.com/sms/json?api_key=040da76e&api_secret=b6e9b34b&from=NEXMO&to=' . $mobile . '&text=Your+verification+code+is+:+' . $code);
//            $success = true;
//            $this->email_receipient = $userModel->usr_email;
//            $mail = new YiiMailer();
//            $mail->setView('signup');
//            $link = Yii::app()->createAbsoluteUrl('users/useremailval', array('id' => $user_id, 'key' => $userModel->usr_activation_key));
//            $mail->setData(
//                    array(
//                        'username' => $userModel->usr_name,
//                        'email' => $userModel->usr_email,
//                        'link' => $link
//            ));
//            $mail->setLayout('mail');
//            $mail->setFrom('alerts@impind.com', 'Longocabs.com');
//            $mail->setTo($userModel->usr_email, $userModel->usr_name);
//            $mail->setSubject('Welcome to Longocabs');
// $mail->Send();
			$status	 = 1;
			$success = 'true';
		}
		else
		{
			$msg	 = $model->errors;
			$success = 'false';
		}
		$result = array('success' => $success,
			'message' => $msg,
			'userID'  => $user_id,
			'data'	  => $model,
			'code'	  => $code
		);
		return $result;
	}

	public function actionChangepassword()
	{
		$this->checkV3Theme();
		$this->layout = 'column2';

		$userId = Yii::app()->user->getId();
		$model	= Users::model()->findByPk($userId);

		if (isset($_REQUEST['Users']))
		{
			$data		 = Yii::app()->request->getParam('Users');
			$oldPassword = $data['old_password'];
			$newPassword = $data['new_password'];
			$rePassword	 = $data['repeat_password'];
			if ($model->usr_password != md5($oldPassword))
			{
				$model->addError('old_password', 'Please enter correct password');
				$message = 'Please enter correct password';
			}
			else
			{
				$model->old_password	= $oldPassword;
				$model->new_password	= $newPassword;
				$model->repeat_password = $rePassword;
				$status					= 'false';
				if ($model->validate())
				{
					$model->scenario = 'change';
					if ($model->usr_password == md5($model->old_password))
					{
						$model->usr_password	   = md5($model->new_password);
						$model->usr_changepassword = 2;
						if ($model->save())
						{
							$status	 = 'true';
							$message = 'Password Changed';
						}
						else
						{
							$status	 = 'false';
							$message = 'Password Not Changed';
						}
					}
					else
					{

						$status	 = 'false';
						$message = 'Old Password not matching';
					}
				}
			}
		}
		if ($status == 'true')
		{
			$this->actionLogout('pusucc');
		}

		if ($this->layoutSufix != "")
		{
			$this->layout = 'column_booking';
		}

		$this->render('changepassword' . $this->layoutSufix, ['model' => $model, 'message' => $message, 'status' => $status]);
	}

	public function changePassword()
	{
		$userId = Yii::app()->user->getId();
// $userId2 = Yii::app()->request->getParam('userid');

		$model = Users::model()->findByPk($userId);

		$oldPassword = Yii::app()->request->getParam('old_password');

		$newPassword = Yii::app()->request->getParam('new_password');

		$rePassword = Yii::app()->request->getParam('repeat_password');

		$model->old_password	= $oldPassword;
		$model->new_password	= $newPassword;
		$model->repeat_password = $rePassword;
		$status					= 'false';
		if ($model->validate())
		{
			$model->scenario = 'change';
			if ($model->usr_password == md5($model->old_password))
			{
				$model->usr_password = md5($model->new_password);
				if ($model->save())
				{
					$status	 = 'true';
					$message = 'Password Changed';
				}
				else
				{
					$status	 = 'false';
					$message = 'Password Not Changed';
				}
			}
			else
			{

				$status	 = 'false';
				$message = 'Old Password not matching';
			}
		}

		$result = array('type'	  => 'raw',
			'message' => $message,
			'status'  => $status);
		return $result;
	}

	public function changeVendorPassword()
	{
		$userId = Yii::app()->user->getId();
// $userId2 = Yii::app()->request->getParam('userid');

		$model = Vendors::model()->findByPk($userId);

		$oldPassword = Yii::app()->request->getParam('old_password');

		$newPassword = Yii::app()->request->getParam('new_password');

		$rePassword = Yii::app()->request->getParam('repeat_password');

		$model->old_password	= $oldPassword;
		$model->new_password	= $newPassword;
		$model->repeat_password = $rePassword;
		$status					= 'false';
		if ($model->validate())
		{
			$model->scenario = 'change';
			if ($model->usr_password == md5($model->old_password))
			{
				$model->usr_password = md5($model->new_password);
				if ($model->save())
				{
					$status	 = 'true';
					$message = 'Password Changed';
				}
				else
				{
					$status	 = 'false';
					$message = 'Password Not Changed';
				}
			}
			else
			{

				$status	 = 'false';
				$message = 'Old Password not matching';
			}
		}
//return $status;
//
//
//        if ($result) {
//
//            $succ = 1;
//            $message = "Your password has been updated successfully";
//            $status = 'true';
//        }
//        else {
//            $message = "New paswword and repeat password are not matching";
//            $status = 'false';
//        }
//        }
//        else {
//            $message = "The current password you have entered is incorrect.Please enter correct password";
//            $status = 'false';
//        }
		$result = array('type'	  => 'raw',
			'message' => $message,
			'status'  => $status);
		return $result;
	}

	public function forgetPassword()
	{
		$email	  = Yii::app()->request->getParam('email');
		$insSerch = Yii::app()->db->createCommand()
				->select('*')
				->from('users')
				->where('usr_email=' . "'" . $email . "'" . ' AND usr_active >0')
				->queryRow();
		if (is_array($insSerch) && count($insSerch) > 0)
		{
			$user_id = $insSerch['user_id'];
			$key	 = md5($insSerch['password']);
			$link	 = Yii::app()->createAbsoluteUrl('users/resetpassword', array('key' => $key, 'id' => $user_id));
// $this->email_receipient = $email;
			$mail	 = new YiiMailer();
			$mail->setView('fmail');
			$mail->setData(
					array(
						'username' => $insSerch['usr_name'],
						'link'	   => $link,
						'email'	   => $email
					)
			);

			$mail->setLayout('mail');
			$mail->setFrom('info@aaocab.com', 'Info aaocab');
			$mail->setTo($email, $insSerch['usr_name']);
			$mail->setSubject('Link to Reset your Password');
// $mail->Send();
			$mobile = $insSerch['usr_mobile'];
			if ($mobile != '')
			{
				$countrycode = Yii::app()->params['countrycode'];
				if ($countrycode != '')
				{
					$mobile = $countrycode . $mobile;
				}
				else
				{
					$mobile = '+91' . $mobile;
				}
				$mobile = '+91' . $mobile;
				$code	= rand(999, 99999);
//  $response = file_get_contents('https://rest.nexmo.com/sms/json?api_key=040da76e&api_secret=b6e9b34b&from=NEXMO&to=' . $mobile . '&text=Your+verification+code+is+:+' . $code);
			}
			$success = true;
		}
		else
		{
			$success = false;
		}
		$result = array('type'	  => 'raw',
			'success' => $success,
			'code'	  => $code);
		return $result;
	}

	public function addbooking()
	{
		$model						= new Booking();
		$model->bkg_user_id			= Yii::app()->user->getId();
		$model->bkg_journey_date	= Yii::app()->request->getParam('bkg_journey_date');
		$model->bkg_journey_time	= Yii::app()->request->getParam('bkg_journey_time');
		$model->bkg_route_id		= Yii::app()->request->getParam('bkg_route_id');
		$model->bkg_amount			= Yii::app()->request->getParam('bkg_amount');
		$model->bkg_pickup_location = Yii::app()->request->getParam('bkg_pickup_location');
		$model->bkg_pickup_lat		= Yii::app()->request->getParam('bkg_pickup_lat');
		$model->bkg_pickup_long		= Yii::app()->request->getParam('bkg_pickup_long');
		$model->bkg_contact_no		= Yii::app()->request->getParam('bkg_contact_no');
		$model->bkg_vehicle_type_id = Yii::app()->request->getParam('bkg_vehicle_type_id');
		$model->bkg_no_person		= Yii::app()->request->getParam('bkg_no_person');
		$model->bkg_user_ip			= Yii::app()->request->getParam('ip_address');
		$model->bkg_user_device		= Yii::app()->request->getParam('device_info');
		$model->bkg_create_date		= new CDbExpression('NOW()');
		$model->bkg_is_approved		= 2;
		$model->save();
		$error						= $model->errors;
		if ($error)
		{
			$message = "Not inserted";
		}
		else
		{
			$message = "Successfully Inserted";
		}
		$result = array('msg' => $message, 'error' => $error);
		return $result;
	}

	public function actionIndex1()
	{

		echo "Comming Soon";

		/*   $model = new Booking();
		  $model->bkg_user_id = 8;
		  $model->bkg_journey_date = '2015-06-20';
		  $model->bkg_journey_time = '13:23:11';
		  $model->bkg_route_id = 1;
		  $model->bkg_pickup_location = 'School';
		  $model->bkg_pickup_lat = '5.3212541';
		  $model->bkg_pickup_long = '2.3212541';
		  $model->bkg_contact_no = '9874589665';
		  $model->bkg_vehicle_type_id = 1;
		  $model->bkg_no_person = 4;
		  $model->bkg_user_ip = \Filter::getUserIP();
		  $model->bkg_user_device = $_SERVER['HTTP_USER_AGENT'];
		  $model->bkg_create_date = new CDbExpression('NOW()');
		  $model->bkg_is_approved = 2;
		  $model->save();
		  $error = $model->errors;
		  echo "<pre>";
		  print_r($error); */
	}

	public function actionIndex()
	{
		if (!Yii::app()->user->isGuest)
		{
			$this->redirect('/index');
		}
		else
		{
			$this->redirect(array('/signin'));
		}
	}

	public function actionView()
	{
		$returnSet = new ReturnSet();
		$request   = yii::app()->request;
		if (Yii::app()->user->isGuest)
		{
			$this->redirect('/index');
		}
		try
		{
			$this->checkV3Theme();

			$this->layout	 = 'column2';
			$this->pageTitle = 'My Profile';
			$city			 = [];
			$userId			 = Yii::app()->user->getId();
			$model			 = Users::model()->findByPk($userId);
			$contactModel	 = Contact::model()->getByUserId($userId);
			if ($contactModel)
			{
				//    Logger::trace("After getting contact ID ==================".$contactModel->ctt_id);
				$contactId	 = $contactModel->ctt_id;
				$emailModels = ContactEmail::model()->findByConId($contactId);
				$phoneModels = ContactPhone::model()->findByConId($contactId);
				$emailModel	 = $emailModels[0];
				$phoneModel	 = $phoneModels[0];
			}
			else
			{
				$flag = true;
			}
			if (!$contactModel)
			{
				$contactModel = new Contact();
			}
			if ($emailModel == null)
			{
				$emailModel = new ContactEmail();
			}
			if ($phoneModel == null)
			{
				$phoneModel = new ContactPhone();
			}

			if ($request->getPost('Users'))
			{
				//unset($request->getPost('ContactEmail')['eml_email_address']);
				$arr1			   = $request->getParam('Users');
				$contactArray	   = $request->getParam('Contact');
				$phoneIsArray	   = $request->getParam('ContactPhone');
				$emailIsArray	   = $request->getParam('ContactEmail');
				$phoneArray		   = $phoneIsArray[0];
				$emailArray		   = $emailIsArray[0];
				$model->attributes = $arr1;

				$model						   = Users::userContactItem($model, $contactArray, $phoneArray, $emailArray);
				$model->scenario			   = 'updateProfile';
				$phoneModel->phn_phone_no	   = $phoneArray['phn_phone_no'];
				$emailModel->eml_email_address = $emailArray['eml_email_address'];
				$contactModel->ctt_state	   = $contactArray['ctt_state'];
				$contactModel->ctt_city		   = $contactArray['ctt_city'];
				$contactModel->ctt_address	   = $contactArray['ctt_address'];

				//  $contactModel->ctt_last_name   = $contactArray['ctt_last_name'];
				//   Logger::trace("After POST getting contact ==================".$contactArray['ctt_first_name'].$contactArray['ctt_last_name']);

				$contactScenario = "";
				if ($contactId > 0)
				{
					$contactProfileModel = $contactModel->contactProfile;

					if (($contactArray['ctt_first_name'] != $contactModel->ctt_first_name) || ($contactArray['ctt_last_name'] != $contactModel->ctt_last_name))
					{
						if ($contactProfileModel->cr_is_driver != NULL)
						{
							$isDriver	 = $contactProfileModel->cr_is_driver;
							$driverModel = Drivers::model()->findByPk($isDriver);
							$drvStatus	 = $driverModel->getDriverApproveStatus();
						}
						if ($contactProfileModel->cr_is_vendor != NULL)
						{
							$isVendor	 = $contactProfileModel->cr_is_vendor;
							$vendorModel = Vendors::model()->findByPk($isVendor);
							$vndStatus	 = $vendorModel->isApproved($isVendor);
						}
						if ($drvStatus == true || $vndStatus == 1)
						{
							$contactModel->scenario = 'nameValidation';

							//  $contactScenario ='nameValidation';
							$model->scenario = 'nameValidation';
						}
					}
				}


				if (!$model->validate())
				{
					throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
				$response = Contact::userContact($contactArray, $phoneArray, $emailArray, '', $contactModel);
				$result	  = Users::userData($model, $arr1, $response, $contactModel);

				if ($model->save())
				{
					ContactProfile::linkUserId($contactModel->ctt_id, $userId);
					$contactModel->refresh();
					Yii::app()->user->setFlash('success', "Personal Information updated successfully ");
				}
			}
			if ($contactModel->ctt_city)
			{
				$city = Cities::model()->getCityNameById($contactModel->ctt_city);
				$city = $city[0];
			}
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			//  Logger::error("NOT SAVED====GET ERROR USER",json_encode($ex));
			$returnSet = ReturnSet::setException($ex);
		}

		$this->render('view', [
			'returnSet'	   => $returnSet,
			'model'		   => $model,
			'contactModel' => $contactModel,
			'emailModel'   => $emailModel,
			'phoneModel'   => $phoneModel,
			'city'		   => $city,
			'flag'		   => $flag]);
	}

	public function actionSignup1()
	{
		$this->checkV2Theme();
		$this->pageTitle = "Sign Up";
		if (!Yii::app()->user->isGuest)
		{
			$this->redirect(array('view'));
		}
		$model		  = new Users('insert');
		$contactModel = new Contact();
		$phoneModel	  = new ContactPhone();
		$emailModel	  = new ContactEmail();
		$isPartial	  = Yii::app()->request->getParam('is_partial');
		$success	  = false;
		$userId		  = '';
		$rNav		  = '';
		if (yii::app()->request->getPost('Users'))
		{
			$contactData = Yii::app()->request->getParam('Contact');
			$userData	 = Yii::app()->request->getParam('Users');
			$phoneData	 = Yii::app()->request->getParam('ContactPhone');
			$emailData	 = Yii::app()->request->getParam('ContactEmail');

			$signupObj	= new \Stub\consumer\SignUpRequest();
			$obj		= $signupObj->setModelData($contactData, $emailData, $phoneData, $userData);
			$contactSet = Contact::createContact($obj, 0, UserInfo::TYPE_CONSUMER);
			$contactId	= $contactSet->getData()['id'];
			$userSet	= Users::create($obj->getSocialModel(), true, Users::Platform_Web, $contactId, null, $userData);
			if ($userSet->isSuccess())
			{
				if ($isPartial == 1)
				{
					$userId	   = $userSet->getData()['userId'];
					/* @var $userModel Users */
					$userModel = Users::model()->findByPk($userId);
					$identity  = new UserIdentity($userModel->usr_email, $userModel->usr_password);
					/// Automatic Login  After Registration /// 
					if ($identity->authenticate())
					{
						Yii::app()->user->login($identity); //logsession
						$this->createLog($identity);
						$success	 = true;
						$userId		 = Yii::app()->user->getId();
						$userNavData = $this->getUserNavData();
						$rNav		 = $userNavData['rNav'];
						$userData	 = $userNavData['userData'];
					}
				}
				else
				{
					$this->redirect(['signin', 'status' => 'signupsuccess']);
				}
			}
			else
			{
				$errors = ($userSet->getErrors());
			}
			if ($isPartial == 1)
			{
				echo CJSON::encode(array('id' => $userId, 'success' => $success, 'userdata' => CJSON::encode($userData), 'rNav' => $rNav, 'errors' => $errors));
				Yii::app()->end();
			}
		}
		if ($isPartial == 1)
		{
			$outputJs = Yii::app()->request->isAjaxRequest;
			$method	  = "render" . ($outputJs ? "Partial" : "");
			$this->$method('partialsignup', array('model' => $model, 'contactModel' => $contactModel, 'phoneModel' => $phoneModel, 'emailModel' => $emailModel, 'errors' => $errors), false, $outputJs);
		}
		else
		{

			$this->render('signup', array('model'		   => $model,
				'contactModel' => $contactModel,
				'phoneModel'   => $phoneModel,
				'emailModel'   => $emailModel, 'errors'	   => $errors));
		}
	}

	/** @deprecated since version number 2021-04-03* */
	public function actionSignupOld()
	{
		$this->checkForMobileTheme();
		$this->pageTitle = "Sign Up";
		if (!Yii::app()->user->isGuest)
		{
			$this->redirect(array('view'));
		}
		$model		  = new Users('insert');
		$contactModel = new Contact();
		$phoneModel	  = new ContactPhone();
		$emailModel	  = new ContactEmail();
		$isPartial	  = 0;
		$partialCall  = Yii::app()->request->getParam('is_partial');
		if ($partialCall == 1)
		{
			$isPartial = 1;
		}

		Yii::app()->request->cookies['gozo_refferal_id']->value = null;
		if (Yii::app()->request->getParam('refcode') != '')
		{
			$referredCode = Yii::app()->request->getParam('refcode');
			$userModel	  = Users::model()->getByReferCode($referredCode);
			if ($userModel != '')
			{
				if ($_COOKIE['invite_clicked_per_user'] != 1)
				{
					$userModel->usr_invite_clicked = $userModel->usr_invite_clicked + 1;
					$userModel->update();
				}
				setcookie('invite_clicked_per_user', 1);
			}
			Yii::app()->request->cookies['gozo_refferal_id'] = new CHttpCookie('gozo_refferal_id', $referredCode);
		}

		$statusreferral = false;
		$refid			= '';
		$success		= false;
		$userId			= '';
		if (yii::app()->request->getPost('Users'))
		{
			$contactArray			  = Yii::app()->request->getParam('Contact');
			$arr1					  = Yii::app()->request->getParam('Users');
			$phoneArray				  = Yii::app()->request->getParam('ContactPhone');
			$emailArray				  = Yii::app()->request->getParam('ContactEmail');
			$model->attributes		  = $arr1;
			$contactModel->attributes = $contactArray;
			if (trim($arr1['new_password']) != trim($arr1['repeat_password']))
			{
				$status = 'errors';
				goto end;
			}
			/// Getting actual phone number///
			$actualPhoneNumber;
			$phoneCode;
			Filter::parsePhoneNumber($phoneArray['phn_phone_no'], $phoneCode, $actualPhoneNumber);
			$phoneArray['phn_phone_no']			  = $actualPhoneNumber;
			$phoneArray['phn_phone_country_code'] = $phoneCode;

			$isPhoneDuplicate = ContactPhone::model()->validatePhoneEmail($phoneArray['phn_phone_no'], $params			  = '');
			$isEmailDuplicate = ContactEmail::model()->validatePhoneEmail($emailArray['eml_email_address'], $params			  = '');
			if ($isPhoneDuplicate && $isEmailDuplicate)
			{
				// This fuction has to be removed in future (userContactItem)
				$model				   = Users::userContactItem($model, $contactArray, $phoneArray, $emailArray);
				$model				   = Users::userData($model, $arr1);
				$model->usr_refer_code = Users::getUniqueReferCode($model);
				$model->scenario	   = 'captchaRequired';
				$status				   = '';

				if ($model->validate())
				{
					$response			   = Contact::userContact($contactArray, $phoneArray, $emailArray);
					$model->usr_contact_id = $response->getData()['id'];
					$result				   = $model->save('insert');
					if (Yii::app()->request->cookies['gozo_refferal_id']->value != '')
					{
						$refferalCode = Yii::app()->request->cookies['gozo_refferal_id']->value;
						Users::processReferralCode($model, $refferalCode);
					}
					if ($arr1['usr_referred_code'] != '' && $isPartial == 1)
					{
						$refferalCode = $arr1['usr_referred_code'];
						Users::processReferralCode($model, $refferalCode);
					}
				}
				if ($result)
				{
					ContactProfile::setProfile($response->getData()['id'], UserInfo::TYPE_CONSUMER);
					if ($isPartial == 1)
					{
						$identity = new UserIdentity($model->usr_email, $model->usr_password);
						/// Automatic Login  After Registration /// 
						if ($identity->authenticate())
						{
							Yii::app()->user->login($identity); //logsession
							$this->createLog($identity);
							$success	 = true;
							$userId		 = Yii::app()->user->getId();
							$userNavData = $this->getUserNavData();
							$rNav		 = $userNavData['rNav'];
							$userData	 = $userNavData['userData'];
						}
					}
					else
					{
						$this->redirect(['signin', 'status' => 'signupsuccess']);
					}
				}
				else
				{
					$status = 'captcha';
				}
			}
			else
			{
				if (!$isPhoneDuplicate)
				{
					$status = 'phnext';
				}
				if (!$isEmailDuplicate)
				{
					$status = 'emlext';
				}
			}
			if ($isPartial == 1)
			{

				echo CJSON::encode(array('id' => $userId, 'success' => $success, 'data' => CJSON::decode($result), 'userdata' => CJSON::encode($userData), 'rNav' => $rNav, 'status' => $status));
				Yii::app()->end();
			}
			end:
		}
		if ($isPartial == 1)
		{
			$outputJs = Yii::app()->request->isAjaxRequest;
			$method	  = "render" . ($outputJs ? "Partial" : "");
			$this->$method('partialsignup', array('model' => $model, 'contactModel' => $contactModel, 'phoneModel' => $phoneModel, 'emailModel' => $emailModel), false, $outputJs);
		}
		else
		{
			if ($this->layoutSufix != "")
			{
				$this->layout = 'column_booking';
			}
			$this->render('signup', array('model' => $model, 'contactModel' => $contactModel, 'phoneModel' => $phoneModel, 'emailModel' => $emailModel, 'status' => $status));
		}
	}

	public function actionConfirmsignup()
	{
		$this->checkV2Theme();
		if (!Yii::app()->user->isGuest)
		{
			$this->redirect(array('view'));
		}
		$contactModel = new Contact();
		$phoneModel	  = new ContactPhone();
		$emailModel	  = new ContactEmail();
		$userid		  = Yii::app()->request->getParam('id');
		$hash		  = Yii::app()->request->getParam('hash');
		if ($userid != Yii::app()->shortHash->unhash($hash))
		{
			throw new CHttpException(400, 'Invalid Request');
		}
		if ($userid > 0)
		{
			$model = Users::model()->findByPk($userid);
		}
		if ($model->usr_acct_type == 2)
		{
			$model->usr_email = "";
		}
		if (yii::app()->request->getPost('Users'))
		{
			$arr1				 = Yii::app()->request->getParam('Users');
			$model->attributes	 = $arr1;
			$contactArray		 = Yii::app()->request->getParam('Contact');
//$arr1					 = Yii::app()->request->getParam('Users');
			$phoneArray			 = Yii::app()->request->getParam('ContactPhone');
			$emailArray			 = Yii::app()->request->getParam('ContactEmail');
			$userModel			 = Users::userContactItem($model, $contactArray, $phoneArray, $emailArray);
			$result				 = Users::userData($userModel, $arr1);
//			$model->usr_create_platform	 = Users::Platform_Web;
//			$model->usr_ip				 = \Filter::getUserIP();
//			$model->usr_device			 = UserLog::model()->getDevice();
			$model->usr_password = $model->encrypt($arr1['new_password'] . '');
			if ($model->validate())
			{
				$response			   = Contact::userContact($contactArray, $phoneArray, $emailArray);
				$model->usr_contact_id = $response->getData()['id'];
				$model->usr_acct_type  = '0';
				$result				   = $model->save();
				if ($result)
				{
					$email = new emailWrapper();
					$email->signupEmail($model->user_id);
					$this->redirect(['signin', 'status' => 'signupsuccess']);
				}
			}
		}

		$this->render('confirmsignup', array('model' => $model, 'contactModel' => $contactModel, 'phoneModel' => $phoneModel, 'emailModel' => $emailModel));
	}

	public function actionCreate()
	{

		$this->layout = 'column1';
		if (!Yii::app()->user->isGuest)
		{
			$this->redirect(array('view'));
		}
		$model = new Users;
		$refid = '';
		if (isset($_REQUEST['Users']))
		{
			$model->attributes	 = Yii::app()->request->getParam('Users');
			$model->usr_name	 = Yii::app()->request->getParam('username');
			$model->new_password = Yii::app()->request->getParam('newpassword');

			$model->usr_email		 = Yii::app()->request->getParam('email');
			$model->usr_password	 = trim(md5(Yii::app()->request->getParam('password')));
			$model->usr_country_code = Yii::app()->request->getParam('countrycode');
			$model->usr_mobile		 = Yii::app()->request->getParam('mobile');
			$model->usr_gender		 = Yii::app()->request->getParam('gender');
// $model->usr_verification_code = $code;
			$model->usr_ip			 = \Filter::getUserIP();
			$model->usr_device		 = UserLog::model()->getDevice();

			$model->email	 = trim($model->email);
			$model->password = trim($model->password);

//  $code = rand(999, 99999);
//  $model->attributes = $_GET;

			if ($model->validate())
			{
				$model->save();
			}
		}
		$this->render('create', array('model' => $model));
	}

	public function actionv2Signin($status = '')
	{
		if ($this->layoutSufix != "")
		{
			$this->layout = 'column_booking';
		}
		$this->pageTitle = "Log In";
		if (!Yii::app()->user->isGuest)
		{
			$this->redirect(array('view'));
		}
		$model		  = new Users;
		$emailModel	  = new ContactEmail();
		$contactModel = new Contact();
		$phoneModel	  = new ContactPhone();
		$email		  = Yii::app()->request->getParam('usr_email');
		$password	  = Yii::app()->request->getParam('usr_password');

		if (($email != '' && $password != '') || (yii::app()->request->getPost('Users')))
		{
			$arr1		  = Yii::app()->request->getParam('Users');
			$emailArray	  = Yii::app()->request->getParam('ContactEmail');
			$email		  = ($email != '') ? $email : $emailArray['eml_email_address'];
			$contactId	  = ContactEmail::findById($email);
			$emailDetails = Users::findByEmailId($email);
			foreach ($emailDetails as $emailDetail)
			{
				if ($emailDetail["usr_contact_id"] == $contactId)
				{
					continue;
				}
				else
				{
					Users::inactiveStatus($emailDetail["usr_contact_id"], $email);
				}
			}
			$userModel	  = Users::model()->findByContactID($contactId);
			$contactModel = Contact::model()->findByPk($contactId);
			$pass		  = ($password != '') ? $password : $arr1['usr_password'];

			if (count($userModel) > 0)
			{

				if (1 == 1)
				{
					$identity = new UserIdentity($email, md5($pass));
					if ($identity->authenticate())
					{
						$userID = $identity->getId();
						Yii::app()->user->login($identity);
						$this->createLog($identity);
						//$this->checkValidAttempt(1, $email);
						$data	= $this->checkValidAttempt(1, $email);
						if (Yii::app()->request->isAjaxRequest)
						{
							$userData			   = [];
							$userData['usr_name']  = $userModel[0]->usr_name;
							$userData['usr_lname'] = $userModel[0]->usr_lname;

							if ($userModel[0]->usr_mobile != '')
							{
								$userData['usr_mobile']		  = $userModel[0]->usr_mobile;
								$userData['usr_country_code'] = $userModel[0]->usr_country_code;
							} if ($userModel[0]->usr_email != '')
							{
								$userData['usr_email'] = $userModel[0]->usr_email;
							}

							$userData['user_id'] = $userID;
							echo CJSON::encode($userData);
							Yii::app()->end();
						}
						$this->redirect(array('view'));
					}
					else
					{
						$data = $this->checkValidAttempt(0, $email);
						if ($data == 5)
						{
							$this->doStatusUpdate(1, $email);
						}
						$status = 'error';
						if (Yii::app()->request->isAjaxRequest)
						{
							Yii::app()->end();
						}
						$this->redirect(array('signin', 'id' => $identity->getId(), 'status' => $status));
					}
				}
				else
				{
					if (Yii::app()->request->isAjaxRequest)
					{
						Yii::app()->end();
					}
					$status = 'emailerror';
					$this->redirect(array('signin', 'id' => $userModel->user_id, 'status' => $status, 'category' => $category, 'b_id' => $b_id, 'type' => $type, 'ref' => $ref));
				}
			}
			else
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					Yii::app()->end();
				}
				$status = 'error';
				$this->redirect(array('signin', 'id' => $userModel->user_id, 'status' => $status, 'category' => $category, 'b_id' => $b_id, 'type' => $type, 'ref' => $ref));
			}
		}
		$modelfg = new Users('forgotpass');
		$this->render('signin' . $this->layoutSufix, array('model' => $model, 'contactModel' => $contactModel, 'phoneModel' => $phoneModel, 'emailModel' => $emailModel, 'modelfg' => $modelfg, 'status' => $status));
	}

	public function actionLogin()
	{
		$this->checkForMobileTheme();
		if (Yii::app()->user->isGuest)
		{
			$model = new UserLogin;
			// collect user input data
			if (isset($_POST['UserLogin']))
			{
				$model->attributes = $_POST['UserLogin'];
				// validate user input and redirect to previous page if valid
				if ($model->validate())
				{
					$this->lastViset();
					if (Yii::app()->user->returnUrl == '/index.php')
						$this->redirect(Yii::app()->controller->module->returnUrl);
					else
						$this->redirect(Yii::app()->user->returnUrl);
				}
			}
			// display the login form
			//$getUrl = Yii::app()->user->returnUrl;
			$this->render('login', array('model' => $model));
			Yii::app()->end();
		}
		else
		if (Yii::app()->user->returnUrl != null && Yii::app()->user->returnUrl != '/')
		{
			$this->redirect(Yii::app()->user->returnUrl);
			return;
		}
		$this->redirect('/');
	}

	public function actionProfile()
	{
		$this->checkForMobileTheme();
		$user_id = Yii::app()->user->getId();

		$this->pageTitle	= "My Profile";
		$this->current_page = "my_profile";

		$umodel					  = Users::model()->findByPk($user_id);
		$umodel->scenario		  = "profile";
		$umodel->usr_country_code = ($umodel->usr_country_code == '') ? Yii::app()->params['countrycode'] : $umodel->usr_country_code;

		if (isset($_POST['Users']))
		{

			$umodel->attributes = $_POST['Users'];
			$char				= array("(", ")", "-", "_", " ");
			$phone				= str_replace($char, "", $_POST['Users']['mobile']);
			$output				= CActiveForm::validate($umodel);
			if ($output == '[]')
			{

				if ($umodel->usr_mobile != $phone)
				{
					$umodel->usr_mobile_verify = 0;
				}
				/* @var $umodel Users */

				$umodel->attributes = $_POST['Users'];
				$umodel->usr_mobile = $phone;
				$umodel->save();
				Yii::app()->user->setFlash('success', 'Your profile has been successfully updated!');
			}
			else
			{
				$result = array('success' => false, 'message' => 'Validation Failed', 'error' => json_decode($output));
				echo json_encode($result);
				Yii::app()->end();
			}
			$result = array('success' => true);
			echo json_encode($result);
			Yii::app()->end();
		}
		$umodel->mobile = $umodel->usr_mobile;
		if ($umodel->usr_active == 2)
		{
			Yii::app()->user->setFlash('danger', 'Your profile is not activate. Verify your contact details.');
		}
		if ($umodel->usr_active == 3)
		{
			Yii::app()->user->setFlash('danger', 'Your profile is deactivated. Contact us.');
		}

		$this->render('view', array('model' => $umodel));
	}

	private function createLog($identity)
	{
		// Logger::info("entry createlog for user create" . $identity->getId());
		$ip						   = \Filter::getUserIP();
		$sessionid				   = Yii::app()->getSession()->getSessionId();
		$logModel				   = new UserLog();
		$logModel->log_in_time	   = new CDbExpression('Now()');
		$logModel->log_ip		   = $ip;
		$logModel->log_session	   = $sessionid;
		$logModel->log_device_info = $_SERVER['HTTP_USER_AGENT'];
		$logModel->log_user		   = $identity->getId();
		$logModel->save();
		//	Logger::info("createlog entry errors" . CJSON::encode($logModel->errors));
		return true;
	}

	public function checkValidAttempt($login = 1, $email)
	{
		$model = Users::model()->getByEmail($email);
		if ($login == 1)
		{
			$model->usr_log_count = 0;
		}
		else if ($login == 0)
		{
			$model->usr_log_count = ($model->usr_log_count + 1);
		}
		$model->save();
		return $model->usr_log_count;
	}

	public function doStatusUpdate($status = 1, $email)
	{
		if ($email != '' && $status == 1)
		{
			$model			   = Users::model()->resetScope()->getByEmail($email);
			$model->usr_active = 2;
			$model->update();
			$success		   = true;
		}
	}

	public function actionLogout($status = null)
	{

		$sessionid = Yii::app()->getSession()->getSessionId();
//        $logModel = new UserLog();
//        $logModel = $logModel->getLogBySession($sessionid);
//        $logModel->log_out_time = new CDbExpression('Now()');
//        $logModel->update();
		Yii::app()->user->logout();
		VoucherOrder::unsetCartSession();
		$status	   = ($status == '') ? 'logout' : $status;
		//$this->redirect(array('users/signin', 'status' => $status));
		$this->redirect('/index');
	}

	public function actionLogoutv3()
	{
		Yii::app()->user->logout();
		VoucherOrder::unsetCartSession();
		$this->redirect('/index');
	}

	function img_resize($tmpname, $size, $save_dir, $save_name, $maxisheight = 0)
	{
		$arr	  = array();
		$save_dir .= ( substr($save_dir, -1) != DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : "";
		$arr[1]	  = $save_dir;
		$gis	  = getimagesize($tmpname);
		$arr[21]  = $tmpname;
		$type	  = $gis[2];
		$arr[2]	  = $gis;
		switch ($type)
		{
			case "1": $imorig = imagecreatefromgif($tmpname);
				break;
			case "2": $imorig = imagecreatefromjpeg($tmpname);
				break;
			case "3": $imorig = imagecreatefrompng($tmpname);
				break;
			default: $imorig = imagecreatefromjpeg($tmpname);
		}

		$x = imagesx($imorig);
		$y = imagesy($imorig);

		$woh = (!$maxisheight) ? $gis[0] : $gis[1];

		if ($woh <= $size)
		{
			$aw = $x;
			$ah = $y;
		}
		else
		{
			if (!$maxisheight)
			{
				$aw = $size;
				$ah = $size * $y / $x;
			}
			else
			{
				$aw = $size * $x / $y;
				$ah = $size;
			}
		}
		$im = imagecreatetruecolor($aw, $ah);
		if (imagecopyresampled($im, $imorig, 0, 0, 0, 0, $aw, $ah, $x, $y))
		{
			if (imagejpeg($im, $save_dir . $save_name))
			{
				Yii::log("Image Resampled: " . $save_dir . $save_name, CLogger::LEVEL_INFO, 'system.api.images');
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	public function getTotalBooking($status)
	{

		$criteria		  = new CDbCriteria;
		$criteria->select = "count(*) as total";
		$criteria->compare('bkg_active', 1);
		$criteria->compare('bkg_status', $status);
		$count			  = $this->find($criteria);
		return $count->total;
	}

	public function actionRefreshNav()
	{
		$this->renderPartial('navbarsign');
	}

	public function actionSideprofile()
	{

		$model	= new Booking();
		$umodel = new Users();
		$this->renderPartial('sideprofile', ['model' => $model, 'umodel' => $umodel]);
	}

	public function actionCountrytostate()
	{
		$countryId = Yii::app()->request->getParam('countryid') != "" ? Yii::app()->request->getParam('countryid') : '99';
		$stateList = CHtml::listData(States::model()->findAll(array("condition" => "stt_country_id = $countryId")), 'stt_id', 'stt_name');
		$data	   = VehicleTypes::model()->getJSON($stateList);
		echo $data;
	}

	public function actionResetpassword_old()
	{

		$this->layout	 = 'Signin';
		$this->pageTitle = "Reset Password";
// $this->loadUserBusiness();
		$request		 = Yii::app()->request;
		$userId			 = $request->getParam('uid');
		$key			 = $request->getParam('key');

		$user_id = Yii::app()->shortHash->unHash($userId);

		if ($user_id == 0)
		{
			$status = "inv";
		}
		//$model = new Users;
		$arr = Users::model()->findByPk($user_id); //$model->findByPk($user_id);
		// echo $arr->usr_password;
		if ($key != md5($arr->usr_password))
		{
			$this->redirect(array('signin'));
			throw new CHttpException(400, 'Invalid Request');
		}

		if (isset($_REQUEST['signup']) && $user_id > 0 || Yii::app()->request->isAjaxRequest)
		{
			$password  = $request->getParam('txtuserPass');
			$cpassword = $request->getParam('cpassword');
			if ($password == $cpassword)
			{
				$arr->usr_password		 = md5($cpassword);
				$arr->usr_changepassword = 2;
				$arr->save();
				$this->redirect(array('signin', 'id' => 'null', 'status' => 'pusucc'));
			}
			else
			{
				$status = "errors";
			}
		}
		$this->render('resetpassword', array(
			'status'   => $status,
			'user_id'  => $user_id,
			'username' => $arr->usr_name
		));
	}

	public function actionResetpassword()
	{

		$this->layout	 = 'Signin';
		$this->pageTitle = "Reset Password";
		$request		 = Yii::app()->request;
		$returnset		 = new ReturnSet();
		$userId			 = $request->getParam('uid');
		$key			 = $request->getParam('key');
		$user_id		 = Yii::app()->shortHash->unHash($userId);
		$params			 = [];
		$returnUrl		 = Yii::app()->createUrl('/', $params);
		if ($user_id == 0)
		{
			//  $status = "inv";
			throw new CHttpException(400, 'Invalid Request');
		}
		try
		{
			$userModel = Users::model()->findByPk($user_id); //$model->findByPk($user_id);
			if ($key != md5($userModel->usr_password))
			{
				// $this->redirect(array('signin'));
				throw new CHttpException(400, 'Invalid Request');
			}
			$param = ['user_id' => $user_id, 'username' => $userModel->usr_name, 'redirectBy' => 'page'];
			if (isset($_REQUEST['signup']) && $user_id > 0 || Yii::app()->request->isAjaxRequest)
			{
				$password  = $request->getParam('txtuserPass');
				$cpassword = $request->getParam('cpassword');
				if ($password == $cpassword)
				{
					$userModel->usr_password	   = md5($cpassword);
					$userModel->usr_changepassword = 2;
					if (!$userModel->save())
					{
						throw new Exception(json_encode($userModel->getErrors()), ReturnSet::ERROR_VALIDATION);
					}
					$param = ['user_id' => $user_id, 'username' => $userModel->usr_name, 'message' => "successfully changed password"];
					$returnset->setStatus(true);
					// $this->redirect(array('signin', 'id' => 'null', 'status' => 'pusucc'));
				}
				else
				{
					// $status = "errors";
					throw new CHttpException(105, 'ERROR_INVALID_DATA');
				}
				$returnset->setData($param);
				Filter::removeNull($returnset);
				echo json_encode($returnset);
				//$this->renderAuto("otpVerified", ['returnUrl' => $returnUrl]);
				Yii::app()->end();
			}
		}
		catch (Exception $ex)
		{
			$returnset = ReturnSet::setException($ex);
		}
//        echo json_encode($returnset);
//        Yii::app()->end();
		$this->renderAuto('resetpassword', $param, false, true);
	}

	public function actionForgotpassword_old()
	{
		//$this->checkForMobileTheme();
		$request = Yii::app()->request;
		$email	 = Yii::app()->request->getParam('forgotemail');
		if ($email == '')
		{
			$users = $request->getParam("Users");
			$email = $users['username'];
		}
		$contactId	  = ContactEmail::findById($email);
		$contactModel = Contact::model()->findByPk($contactId);
//$users			 = Users::model()->findByEmail($email);
		$users		  = ($contactId == null || $contactId == "") ? array() : Users::model()->findByContactID($contactId);
		if (count($users) > 0)
		{
			$user_id				= $users[0]->user_id;
			$hash					= Yii::app()->shortHash->hash($user_id);
//$username				 = $users->usr_name;
			$username				= $contactModel->ctt_first_name;
			$key					= md5($users[0]->usr_password);
			$link					= Yii::app()->createAbsoluteUrl('users/resetpassword', array('key' => $key, 'uid' => $hash));
			$this->email_receipient = $email;
			$mail					= new YiiMailer();
			$mail					= EIMailer::getInstance(EmailLog::SEND_SERVICE_EMAIL);
			$mail->setView('fmailweb');
			$mail->setData(
					array(
						'username'		   => $username,
						'link'			   => $link,
						'userId'		   => $user_id,
						'email_receipient' => $email
			));

			$mail->setLayout('mail');
			$mail->setFrom(Yii::app()->params['mail']['noReplyMail'], 'Info aaocab');
			$mail->setTo($email, $username);
			$mail->setSubject('Reset your Password');
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body	  = $mail->Body;
			$usertype = EmailLog::Consumers;
			$subject  = 'Reset your Password';
			$refId	  = $user_id;
			$refType  = EmailLog::REF_USER_ID;
			emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
			$status	  = 'true';
		}
		else
		{
			$status = 'false';
		}
		echo CJSON::encode(array('status' => $status));
		Yii::app()->end();
	}

	public function actionForgotpassword()
	{
		$request		   = Yii::app()->request;
		$this->pageRequest = BookFormRequest::createInstance();
		$users			   = $request->getParam("Users");
		$isEmailOrPhone	   = Users::isEmailOrPhone($users['username']);
		$typeUsr		   = $isEmailOrPhone['type'];
		$returnSet		   = new ReturnSet();
		try
		{
			if ($isEmailOrPhone['type'] == 1)
			{
				$objEmailContact = $this->pageRequest->getContact($isEmailOrPhone['type'], $isEmailOrPhone['value']);
				Contact::verifyOTP($objEmailContact, true, null, false);
				$arrVerifyData	 = ["type"		   => $objEmailContact->type,
					"value"		   => $objEmailContact->value,
					'otp'		   => $objEmailContact->otp, 'otpValidTill' => $objEmailContact->otpValidTill, 'otpLastSent'  => $objEmailContact->otpLastSent];
				$arrTime		 = ['otpValidTill' => $objEmailContact->otpValidTill, 'otpLastSent' => $objEmailContact->otpLastSent];
				$otpObj			 = $objEmailContact;

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
				$objPhoneContact = $this->pageRequest->getContact($isEmailOrPhone['type'], $isEmailOrPhone['value']);
				Filter::parsePhoneNumber($users['username'], $code, $number);
				$canSendSMS		 = ContactPhone::checkTosendSMS($code, $number, SmsLog::SMS_FORGET_PASSWORD);
				$smstextType	 = "webOTP";
				$smsLogType		 = SmsLog::SMS_FORGET_PASSWORD;
				Contact::verifyOTP($objPhoneContact, $canSendSMS, $smstextType, false, $smsLogType);
				//   , 'otpLastSent'  => $objPhoneContact->otpLastSent, 'otp'          => $objPhoneContact->otp, 'otpValidTill' => $objPhoneContact->otpValidTill
				$arrVerifyData	 = ["type"		=> $objPhoneContact->type, "value"		=> $objPhoneContact->value,
					"isSendSMS" => $objPhoneContact->isSendSMS];
				$arrTime		 = ['otpValidTill' => $objPhoneContact->otpValidTill, 'otpLastSent' => $objPhoneContact->otpLastSent];
				$otpObj			 = $objPhoneContact;
				$isSend			 = $objPhoneContact->isSendSMS;
			}
			$contactDetails = Yii::app()->JWT->encode([$arrVerifyData]);
			$this->pageRequest->updatePostData();
			$returnSet->setData(
					[
						'status'		 => $isSend,
						'verifyData'	 => $contactDetails,
						'verifyValidity' => $arrTime,
						'rdata'			 => $this->pageRequest->getEncrptedData(),
						'typeUsr'		 => $typeUsr,
						'typeID'		 => $isEmailOrPhone['value']
			]);
			$returnSet->setStatus(true);
		}
		catch (Exception $e)
		{
			$returnset = ReturnSet::setException($e);
		}
		echo json_encode($returnSet);
		Yii::app()->end();
	}

	public function actionForgotform()
	{
		$modelfg = new Users('forgotpass');
		if ($this->layoutSufix != "")
		{
			$this->layout = 'column_booking';
		}
		$this->renderPartial('forgotpass' . $this->layoutSufix, ['modelfg' => $modelfg]);
	}

	public function actionAgentapi()
	{
		$sql	   = "SELECT bkg_id, bkg_pickup_date FROM booking WHERE bkg_agent_id = 450 and bkg_status IN (2,3,5,6,7,9) and bkg_agent_ref_code IS NULL";
		$resultset = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($resultset as $result)
		{
			$query = "SELECT aat_response FROM agent_api_tracking WHERE aat_type = 8 and aat_response LIKE " . "'" . '%"hold_key":"' . $result['bkg_id'] . '"%' . "'" . " and aat_created_at < " . "'" . $result['bkg_pickup_date'] . "' LIMIT 0,1";
			$data  = Yii::app()->db->createCommand($query)->queryRow();
			if ($data)
			{
				$request = CJSON::decode($data['aat_response'], true);
				$qry	 = "UPDATE booking SET bkg_agent_ref_code = '" . $request['booking_id'] . "' where bkg_id = " . $result['bkg_id'];
				Yii::app()->db->createCommand($qry)->execute();
			}
		}
	}

	public function actionuserforgotpassword()
	{
		$this->checkV2Theme();
		if (UserInfo::isLoggedIn())
		{
			$this->redirect('/');
		}
		$this->checkForMobileTheme();
		$this->pageTitle = "Forgot your password?";
		$modelfg		 = new Users('forgotpass');
		if ($this->layoutSufix != "")
		{
			$this->layout = 'column_booking';
		}
		$this->render('userforgotpassword' . $this->layoutSufix, ['modelfg' => $modelfg]);
	}

	public function actionPartialsignin()
	{
		$this->checkV2Theme();
		$isDeskTopTheme = Yii::app()->request->getParam('desktheme');
		if ($isDeskTopTheme == 1)
		{
			$this->checkForDesktopTheme();
		}
		$this->checkForMobileTheme();
		$request	  = Yii::app()->request;
		$model		  = new Users('login');
		$emailModel	  = new ContactEmail();
		$contactModel = new Contact();
		$phoneModel	  = new ContactPhone();
		$uemail		  = $request->getParam('uemail');
		if ($uemail != '')
		{
			$model->email = $uemail;
		}
		if ($request->isPostRequest)
		{
			$model						= new Users('login');
			$model->attributes			= $request->getParam('Users'); //$_REQUEST['Users'];
			$success					= false;
			$userId						= '';
			$model->usr_create_platform = Users::Platform_Web;

			$model->email = $request->getParam('ContactEmail')['eml_email_address'];

			$result = CActiveForm::validate($model);
			if ($result == '[]')
			{
				Yii::app()->user->login($model->_identity);
				$this->createLog($model->_identity);
				$success = true;
				$userId	 = Yii::app()->user->getId();
			}

			$userNavData = $this->getUserNavData();
			$rNav		 = $userNavData['rNav'];
			$userData	 = $userNavData['userData'];
			//$rNav = $this->renderPartial('navbarsign', [], true);
			echo CJSON::encode(array('id' => $userId, 'success' => $success, 'data' => CJSON::decode($result), 'userdata' => CJSON::encode($userData), 'rNav' => $rNav));
			Yii::app()->end();
		}
		$outputJs = Yii::app()->request->isAjaxRequest;
		$method	  = "render" . ($outputJs ? "Partial" : "");
		$this->$method('partialsignin' . $this->layoutSufix, array('model' => $model, 'emailModel' => $emailModel, 'contactModel' => $contactModel, 'phoneModel' => $phoneModel, 'status' => $status), false, $outputJs);
	}

	public function actionPartialsignup()
	{
		$model		  = new Users('insert');
		$contactModel = new Contact();
		$phoneModel	  = new ContactPhone();
		$emailModel	  = new ContactEmail();

		if (yii::app()->request->getPost('Users'))
		{
			$userModel				= new Users('insert');
			$contactArray			= Yii::app()->request->getParam('Contact');
			$arr1					= Yii::app()->request->getParam('Users');
			$phoneArray				= Yii::app()->request->getParam('ContactPhone');
			$emailArray				= Yii::app()->request->getParam('ContactEmail');
			$userModel->attributes	= $arr1;
			$emailModel->attributes = $emailArray;

			$userModel = Users::userContactItem($userModel, $contactArray, $phoneArray, $emailArray);
			$userModel = Users::userData($userModel, $arr1);

			$pass	 = $userModel->usr_password;
			$success = false;
			$userId	 = '';
			$result	 = CActiveForm::validate($userModel, null, false);

			if ($result == '[]')
			{
				$response				   = Contact::userContact($contactArray, $phoneArray, $emailArray);
				$userModel->usr_contact_id = $response->getData()['id'];
				$userModel->usr_password   = md5($pass);
				if ($userModel->save())
				{
					ContactProfile::setProfile($response->getData()['id'], UserInfo::TYPE_CONSUMER);
				}
				if ($emailModel->eml_email_address != '')
				{
					$email = new emailWrapper();
					$email->signupEmail($userModel->user_id);
				}
				$identity = new UserIdentity($emailModel->eml_email_address, $userModel->usr_password);

				if ($identity->authenticate())
				{
					Yii::app()->user->login($identity); //logsession
					$this->createLog($identity);
					$success = true;
					$userId	 = Yii::app()->user->getId();

					$userNavData = $this->getUserNavData();
					$rNav		 = $userNavData['rNav'];
					$userData	 = $userNavData['userData'];
				}
			}
			echo CJSON::encode(array('id' => $userId, 'success' => $success, 'data' => CJSON::decode($result), 'userdata' => CJSON::encode($userData), 'rNav' => $rNav));
			Yii::app()->end();
		}

		$outputJs = Yii::app()->request->isAjaxRequest;
		$method	  = "render" . ($outputJs ? "Partial" : "");
		$this->$method('partialsignup', array('model' => $model, 'contactModel' => $contactModel, 'phoneModel' => $phoneModel, 'emailModel' => $emailModel), false, $outputJs);
	}

	public function getValidationApp($data, $id, $activeVersion)
	{
		if ($activeVersion > $data['apt_apk_version'])
		{
			return $result = array('active' => 1, 'success' => false, 'message' => "Invalid Version");
		}
		else
		{
			if ($id != '')
			{
				$validate = AppTokens::model()->getAppValidations($data, $id);
				if ($validate)
				{
					$umodel = Users::model()->find('user_id=:id', ['id' => $id]);
					if ($umodel != '')
					{
						return $result = array('active'  => 2,
							'success' => true,
							'message' => "Validation Done");
					}
				}
			}
		}
		return array('active' => 3, 'success' => false, 'message' => "Invalid User");
	}

	public function actionUserdata()
	{
		$user	  = Yii::app()->user->loadUser();
		$userData = [];

		$userData['usr_name']  = $user->usr_name;
		$userData['usr_lname'] = $user->usr_lname;

		if ($user->usr_mobile != '')
		{
			$userData['usr_mobile']		  = $user->usr_mobile;
			$userData['usr_country_code'] = $user->usr_country_code;
		} if ($user->usr_email != '')
		{
			$userData['usr_email'] = $user->usr_email;
		}
		echo CJSON::encode($userData);
	}

	public function actionRefer()
	{
		try
		{
			$errMsg			 = '';
			$this->checkV3Theme();
			$this->layout	 = 'column2';
			$this->pageTitle = 'Refer Friend';
			if (Yii::app()->user->isGuest)
			{
				$this->redirect(array('/refer-friend'));
			}
			$userId					   = Yii::app()->user->getId();
			$userModel				   = Users::model()->findByPk($userId);
			$refCode				   = Users::getUniqueReferCode($userModel);
			$userModel->usr_refer_code = $refCode;
			$userModel->scenario	   = 'refcode';
			if ($userModel->validate())
			{
				if (!$userModel->update())
				{
					$errors = $userModel->getErrors();
				}
			}
			if ($userModel->usr_qr_code_path == '')
			{
				$ret = QrCode::processData($userId);
				if (!$ret->getStatus())
				{
					throw new Exception($ret->getMessage());
				}
			}
			$path	 = Users::getUserPathById($userId);
			$qrModel = QrCode::model()->find('qrc_ent_type=1 AND qrc_ent_id = :userid', array('userid' => $userId));
			$code	 = ($qrModel) ? $qrModel->qrc_code : '';
		}
		catch (Exception $ex)
		{
			$errMsg = $ex->getMessage();
		}
		$this->render('refer', ['model' => $userModel, 'qrpath' => $path, 'errMsg' => $errMsg, 'refCode' => $code, 'qrcode' => $code, 'name' => $userModel->usr_name . ' ' . $modelUser->usr_lname, 'amount' => Yii::app()->params['invitedAmount']]);
	}

	public function actionFbShareLink()
	{
		$refCode = $_REQUEST['refcode'];
		$hash	 = $_REQUEST['hash'];
		$id		 = $_REQUEST['id'];
		$text	 = $_REQUEST['text'];

		$bkgid = Yii::app()->shortHash->unHash($hash);
		if ($id != $bkgid)
		{
			throw new CHttpException(400, 'Invalid Request');
		}
		$bModel		 = Booking::model()->findByPk($bkgid);
		//old app id 488018534722292
		$image		 = 'http://www.aaocab.com/images/logosquare.png';
		$link		 = 'http://www.aaocab.com/invite/' . $refCode;
		$urlReturn	 = 'http://www.facebook.com';
		$title		 = 'I traveled with aaocab and loved it';
		$description = "Try Gozo with the URL below and both you and I will get Rs. 200 "
				. "Gozo Coins for our next trip. '" . $link
				. "'. Here is my review from my trip " . $bModel->bkg_booking_id . ' : "'
				. $bModel->ratings[0]['rtg_customer_review'] . '"';
		if ($text != '')
		{
			//	$link = "https://".Yii::app()->params['host']."/fbflexxishare/".$bModel->bkg_id."/".Yii::app()->shortHash->hash($bModel->bkg_id)."";
			$link = "http://www.aaocab.com/bknw/$bModel->bkg_id/" . Yii::app()->shortHash->hash($bModel->bkg_id) . "";
			$this->redirect('http://www.facebook.com/dialog/share?app_id=1132716233459596&href=' . $link . '&picture=' . $image . '&name=' . $title . '&caption=www.aaocab.com/fbflexxishare/' . $bModel->bkg_id . '/' . Yii::app()->shortHash->hash($bModel->bkg_id) . '&description=' . $text . '&redirect_uri=' . $urlReturn . '&display=popup');
		}
		$this->redirect('http://www.facebook.com/dialog/feed?app_id=1132716233459596&link=' . $link . '&picture=' . $image . '&name=' . $title . '&caption=www.aaocab.com&description=' . $description . '&redirect_uri=' . $urlReturn . '&display=popup');
	}

	public function actionFbShareTemplate()
	{
		$refCode	 = $_REQUEST['refcode'];
		$amount		 = Yii::app()->params['invitedAmount'];
		//old app id 488018534722292
		$image		 = 'http://www.aaocab.com/images/logosquare.png';
		$link		 = 'http://www.aaocab.com/invite/' . $refCode;
		$urlReturn	 = 'http://www.facebook.com';
		$title		 = "I've traveled with aaocab and loved it.";
		$description = "Travel with Gozo through my URL " . $link . ", you and I will both get Rs. " . $amount . "/- Gozo Coins to use with Gozo.​ ​Visit them at www.aaocab.com. Its a cool service. Clear pricing. Awesome service and great customer reviews";
		$this->redirect('http://www.facebook.com/dialog/feed?app_id=1132716233459596&link=' . $link . '&picture=' . $image . '&name=' . $title . '&caption=www.aaocab.com&description=' . $description . '&redirect_uri=' . $urlReturn . '&display=popup');
	}

	public function actionCreditlist()
	{
		$this->checkV3Theme();

		$this->layout			  = 'column2';
		$this->pageTitle		  = 'Gozo Wallet History';
		/* var $model UserCredits */
		$creditModel			  = new UserCredits();
		$userId					  = Yii::app()->user->getId();
		$creditModel->ucr_user_id = $userId;
		$status					  = '1';
		// Active Credits
		$data					  = $creditModel->getCreditsList('1');
		// Pending Credits
		$data2					  = $creditModel->getCreditsList('2');
		//Total Active Credits
		$totalAmount			  = $creditModel->getTotalActiveCredits($creditModel->ucr_user_id);

		$walletBallance = UserWallet::model()->getBalance($userId);
		$dataProvider3	= UserWallet::model()->getTransHistory($userId, Accounting::LI_WALLET);

		$this->render('creditlist', ['model'			 => $creditModel,
			'dataProvider'	 => $data['dataProvider'],
			'dataProvider2'	 => $data2['dataProvider'],
			'datarecordSet'	 => $data['recordSet'],
			'datarecordSet2' => $data2['recordSet'],
			'dataProvider3'	 => $dataProvider3,
			'totalAmount'	 => $totalAmount,
			'walletBalance'	 => $walletBallance]);
	}

	public function actionGshareTemplate()
	{
		$refCode = Yii::app()->request->getParam('refCode'); //Yii::app()->shortHash->hash($userId, 6);
		$amount	 = Yii::app()->request->getParam('amount');
		$this->renderPartial('fbTemplate', ['refCode' => $refCode, 'amount' => $amount], false, true);
	}

	public function actionVerifyemail()
	{
		$email	   = Yii::app()->request->getParam('email');
		$code	   = Yii::app()->request->getParam('code');
		$userModel = Users::model()->find('usr_email=:email', ['email' => $email]);
		if ($userModel != '' && $userModel->usr_verification_code == $code)
		{
			$userModel->usr_verification_code = '';
			$userModel->usr_email_verify	  = 1;
			if ($userModel->update())
			{
				echo json_encode(['success' => true]);
				Yii::app()->end();
			}
		}
		echo json_encode(['success' => false, 'message' => 'Invalid verification code']);
		Yii::app()->end();
	}

	public function actionValidateemail()
	{
		$email	   = Yii::app()->request->getParam('email');
		$userModel = Users::model()->find('usr_email=:email', ['email' => $email]);
		if ($userModel)
		{

			echo json_encode(['success' => true]);
			Yii::app()->end();
		}
		else
		{
			echo json_encode(['success' => false, 'message' => 'Email address not registered.']);
			Yii::app()->end();
		}
	}

	public function actionRefreshuserdata()
	{
		echo json_encode($this->getUserNavData());
	}

	public function getUserNavData()
	{
		$this->checkForDesktopTheme();
		$user	  = Yii::app()->user->loadUser();
		$userData = [];
		if ($user->usr_contact_id)
		{
			$contactModel		   = Contact::model()->findByPk($user->usr_contact_id);
			$emailModel			   = ContactEmail::model()->findByConId($user->usr_contact_id);
			$phoneModel			   = ContactPhone::model()->findByConId($user->usr_contact_id);
			$userData['usr_name']  = $contactModel->ctt_first_name;
			$userData['usr_lname'] = $contactModel->ctt_last_name;

			if ($phoneModel[0]->phn_phone_no != '')
			{
				$userData['usr_mobile']		  = $phoneModel[0]->phn_phone_no;
				$userData['usr_country_code'] = $phoneModel[0]->phn_phone_country_code;
			} if ($emailModel[0]->eml_email_address != '')
			{
				$userData['usr_email'] = $emailModel[0]->eml_email_address;
			}
			if ($user->usr_gender != '')
			{
				$userData['usr_gender'] = $user->usr_gender;
			}
			$rNav	= $this->renderPartial('navbarsign', [], true);
			$result = ['rNav' => $rNav, 'userData' => $userData];
			return $result;
		}
	}

	public function actionValidategenderflexxi()
	{
		echo json_encode(['success' => TRUE, 'message' => 'Sorry. This Flexxi share is offered only to ' . Users::model()->genderList[$fpBooking->bkgUser->usr_gender] . ' passengers. Please go back to search for a Flexxi that is offered for ' . Users::model()->genderList[$fsUser->usr_gender] . ' co-passengers.']);
		Yii::app()->end();
		$fpId = Yii::app()->request->getParam('fpId');
		$fsId = Yii::app()->request->getParam('fsId');
		$hash = Yii::app()->request->getParam('hash');
		if (Yii::app()->user->isGuest)
		{
			echo json_encode(['success' => FALSE, 'message' => 'Please login to facebook to continue booking.']);
			Yii::app()->end();
		}
		if ($fsId != '' && $fsId != Yii::app()->shortHash->unHash($hash))
		{
			throw new Exception("Invalid Data", 101);
		}
		$fsBooking = BookingTemp::model()->findByPk($fsId);

		$fsuserId	  = Users::model()->getFbLogin($fsBooking->bkg_user_id, $fsBooking->bkg_user_email, $fsBooking->bkg_contact_no, true);
		$isFbLoggedIn = Users::model()->getFbLogin($fsBooking->bkg_user_id, $fsBooking->bkg_user_email, $fsBooking->bkg_contact_no, false);
		if (!$isFbLoggedIn)
		{
			echo json_encode(['success' => FALSE, 'message' => 'Please login to facebook to continue booking.']);
			Yii::app()->end();
		}
		else
		{
			if ($fpId != '')
			{
				$fpBooking = Booking::model()->with('bkgUserInfo.bkgUser')->findByPk($fpId);
				$fsUser	   = Users::model()->findByPk($fsuserId);
				if ($fpBooking->bkgUserInfo->bkgUser->usr_gender == $fsUser->usr_gender)
				{
					echo json_encode(['success' => TRUE, 'message' => 'Gender matched']);
					Yii::app()->end();
				}
			}
			else
			{
				echo json_encode(['success' => TRUE, 'message' => 'Gender matched']);
				Yii::app()->end();
			}
		}
		echo json_encode(['success' => FALSE, 'message' => 'Sorry. This Flexxi share is offered only to ' . Users::model()->genderList[$fpBooking->bkgUserInfo->bkgUser->usr_gender] . ' passengers. Please go back to search for a Flexxi share that is offered for ' . Users::model()->genderList[$fsUser->usr_gender] . ' co-passengers.']);
		Yii::app()->end();
	}

	public function actionSociallogin()
	{
		$this->pageTitle = "Social Login";
		//$vndId			 = Yii::app()->request->getParam('id');
		$vndHash		 = Yii::app()->request->getParam('vndhash');
		$vndId			 = Yii::app()->shortHash->unHash($vndHash);
		$created		 = Yii::app()->request->getParam('createdstamp');
		$dateStamp		 = date("Y-m-d H:i:s", $created);
		$datetime1		 = strtotime($dateStamp);
		$timeNow		 = Filter::getDBDateTime();
		$datetime2		 = strtotime($timeNow);
		$interval		 = abs($datetime2 - $datetime1);
		$intervalMin	 = round($interval / 60);
		$this->render('sociallogin', array('id' => $vndId, 'hash' => $vndHash, 'timediff' => $intervalMin));
	}

	public function actionAuth()
	{
		try
		{
			$isNew	   = false;
			$auth	   = new Google_Client();
			$auth->setAuthConfig(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'client_secret_google.json');
			$payload   = $auth->verifyIdToken($_REQUEST['response']['credential']);
			$email	   = $payload["email"];
			$contactId = Contact::getByEmailPhone($email, '', false);
			if (!$contactId)
			{
				$socialProfile				  = new Hybrid_User_Profile();
				$socialProfile->firstName	  = $payload['given_name'];
				$socialProfile->lastName	  = $payload['family_name'];
				$socialProfile->email		  = $payload['email'];
				$socialProfile->emailVerified = $payload['email'];
				$socialProfile->photoURL	  = $payload['picture'];
				$returnSet					  = Contact::createBySocialProfile($socialProfile, SocialAuth::Eml_Google);
				if (!$returnSet->getStatus())
				{
					throw new Exception($returnSet->getMessage(), $returnSet->getErrorCode());
				}
				$isNew	   = true;
				$contactId = $returnSet->getData()["contactId"];
			}
			$userModel = Users::createbyContact($contactId);
			if ($userModel)
			{
				$identity		  = new UserIdentity($userModel->usr_name, null);
				$identity->userId = $userModel->user_id;
				if ($identity->authenticate())
				{
					Yii::app()->user->login($identity);
					$this->createLog($identity);
					$returnSet = new ReturnSet();
					$returnSet->setStatus(true);
					$returnSet->setData(["isNew" => $isNew]);
				}
			}
			else
			{
				throw new Exception("User not found..", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
		}
		catch (Exception $exc)
		{
			$returnSet = ReturnSet::setException($exc);
		}

		echo json_encode($returnSet);

		Yii::app()->end();
	}

	public function actionLinkVendor()
	{
		$email	 = Yii::app()->request->getParam('email');
		$hash	 = Yii::app()->request->getParam('hash');
		$id		 = Yii::app()->request->getParam('id');
		$vndId	 = Yii::app()->shortHash->unHash($hash);
		$isExist = 0;
		if ($id == $vndId)
		{
			$vndModel  = Vendors::model()->findByPk($vndId);
			$userModel = Users::model()->find('usr_email=:email', ['email' => $email]);

			if ($vndModel != '' && $userModel != '')
			{
				$isExistVendor = Vendors::model()->checkExistingVendor($userModel->user_id);
				if ($isExistVendor > 0)
				{
					$vndModel->vnd_user_id = $userModel->user_id;
					if ($vndModel->update())
					{
						$success = 'true';
					}
					else
					{
						$success = 'false';
					}
				}
				else
				{
					$success = 'false';
					$isExist = 1;
				}
			}
		}
		else
		{
			$success = 'false';
		}
		echo json_encode(['success' => $success, 'isexist' => $isExist]);
		Yii::app()->end();
	}

	public function actionGetUserIdAfterSocialLogin()
	{
		$email	   = Yii::app()->request->getParam('email');
		$userModel = Users::model()->find('usr_email=:email', ['email' => $email]);
		if ($userModel != '')
		{
			$isExistVendor = Vendors::model()->checkExistingVendor($userModel->user_id);
			if ($isExistVendor > 0)
			{
				$success = 'false';
				$userId	 = 0;
			}
			else
			{
				$success = 'true';
				$userId	 = $userModel->user_id;
			}
		}
		else
		{
			$success = 'false';
			$userId	 = 0;
		}

		echo json_encode(['success' => $success, 'userid' => $userId]);
		Yii::app()->end();
	}

	public function actionSosUrl()
	{
		$this->layout = 'sos_layout';
		$urlHash	  = Yii::app()->request->getParam('v');
		$urlArr		  = Users::model()->unhashSOSUrl($urlHash);
		$bkgId		  = $urlArr['bkgId'];
		$userId		  = $urlArr['userId'];
		$bModel		  = Booking::model()->findByPk($bkgId);
		$bookingId	  = $bModel->bkg_booking_id;
		$UserModel	  = Users::model()->findByPk($userId);
		$userName	  = $UserModel->usr_name;
		if ($bModel->bkgTrack->bkg_sos_sms_trigger == 1 || $bModel->bkgTrack->bkg_sos_sms_trigger == 2)
		{
			$coordinate	  = explode(',', $bModel->bkgTrack->bkg_trip_end_coordinates);
			$sosLatitude  = $coordinate[0];
			$sosLongitude = $coordinate[1];
			//	$dateTime		 = $bModel->bkgTrack->bkg_sos_enable_datetime;
		}
		if ($bModel->bkgTrack->bkg_drv_sos_sms_trigger == 1 || $bModel->bkgTrack->bkg_drv_sos_sms_trigger == 2)
		{
			$coordinate	  = explode(',', $bModel->bkgTrack->bkg_trip_end_coordinates);
			$coordinate	  = explode(',', $bModel->bkgTrack->bkg_trip_end_coordinates);
			$sosLatitude  = $coordinate[0];
			$sosLongitude = $coordinate[1];
//			$sosLatitude	 = $bModel->bkgTrack->bkg_drv_sos_latitude;
//			$sosLongitude	 = $bModel->bkgTrack->bkg_drv_sos_longitude;
//			$dateTime		 = $bModel->bkgTrack->bkg_drv_sos_enable_datetime;
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			echo json_encode($urlHash);
			Yii::app()->end();
		}


		$usrArr = ['bkgId' => $bookingId, 'userName' => $userName, 'dateTime' => $dateTime, 'lat' => $sosLatitude, 'lon' => $sosLongitude, 'url' => $urlHash];
		$this->render('sosUrl', array('arr' => $usrArr));
	}

	public function actionRedeemgiftcard()
	{
		$this->checkV2Theme();
		$this->layout	 = 'column2';
		$this->pageTitle = 'Add GIFT to wallet';
		$success		 = false;
		if (isset($_POST['btnRedeem']) && isset($_POST['gcc1']))
		{
			$GiftCard = md5($_POST['gcc1']);
			$success  = GiftCardSubscriber::model()->redeemGiftCard($GiftCard, UserInfo::getUserId());
			if (!$success)
			{
				Yii::app()->user->setFlash('error', 'Sorry! Invalid gift card code.Please try again.');
			}
			else
			{
				Yii::app()->user->setFlash('success', 'Congratulations! You have successfully redeemed your gift card.<br>Go to wallet history for details.');
			}
		}

		$this->render('redeemgiftcard', array());
	}

	public function actionUsewallet()
	{
		$bkgId			 = Yii::app()->request->getParam('bkg_id');
		$bkghash		 = Yii::app()->request->getParam('bkghash');
		$flagUseOrRemove = Yii::app()->request->getParam('flagUseWallet');
		$amount			 = Yii::app()->request->getParam('amount');
		$credit_amount	 = Yii::app()->request->getParam('credit_amount');

		if ($bkgId != '' && $bkghash != '' && Yii::app()->shortHash->hash($bkgId) == $bkghash)
		{
			$returnSet = UserWallet::useWallet(UserInfo::getUserId(), $bkgId, $flagUseOrRemove, false, $amount, $credit_amount);
			$data	   = [];
			if ($returnSet->getStatus())
			{
				$data = $returnSet->getData();
			}
			echo json_encode(['result' => $returnSet->getStatus()] + $data);
			Yii::app()->end();
		}
		echo json_encode(['success' => false, 'error' => 'Invalid Request']);
		Yii::app()->end();
	}

	public function actionRedeemvoucher()
	{
		$this->checkForMobileTheme();
		$this->layout	 = 'column2';
		$this->pageTitle = 'Redeem Voucher';
		$success		 = false;
		$cardCode		 = Yii::app()->request->getParam('gcc1');
		if (isset($_POST['btnRedeem']) && isset($cardCode))
		{
//$code	 = md5($cardCode);
			$res = VoucherSubscriber::redeemVoucher($code);
			if (!$res->getStatus())
			{
				Yii::app()->user->setFlash('error', 'Sorry! Invalid voucher code.Please try again.');
			}
			else
			{
				Yii::app()->user->setFlash('success', $res->getMessage());
			}
		}

		$this->render('redeemvoucher', array());
	}

	public function actionTransfer()
	{
		$userId			 = Yii::app()->user->getId();
		$showBankDetails = Yii::app()->request->getParam('showbankDetails', 0);
		$modeldata		 = Contact::model()->getByUserId($userId);
		$model			 = Contact::model()->findbyPk($modeldata->ctt_id);
		$bank			 = new \Stub\common\Bank();
		$data			 = $bank->setData($model);
		if (($data->accountNumber == '' || $data->ifsc == '') || $showBankDetails == 1)
		{
			$view	   = 'bankdetails';
			$pagetitle = 'Please provide your bank account details';
		}
		else
		{
			$amount = UserWallet::getBalance($userId);
			if ($amount > 0)
			{
				if (!Yii::app()->icici->api_live)
				{
					$amount = 1;
				}
				$pagetitle = 'Transfer from Gozo wallet to your bank';
				$view	   = 'transferForm';
			}
			else
			{
				$pagetitle = 'Insufficient Balance';
				echo 'You have no sufficient balance to transfer';
				Yii::app()->end();
			}
		}
		$outputJs = Yii::app()->request->isAjaxRequest;
		$method	  = "render" . ($outputJs ? "Partial" : "");
		$this->$method($view . $this->layoutSufix, array('model' => $data, 'amount' => $amount, 'bank' => $bank, 'pagetitle' => $pagetitle), false, $outputJs);
	}

	public function actionSavebankdetails()
	{
		$userId	   = Yii::app()->user->getId();
		$modeldata = Contact::model()->getByUserId($userId);
		$model	   = Contact::model()->findbyPk($modeldata->ctt_id);
		$bank	   = new \Stub\common\Bank();
		$req	   = Yii::app()->request->getParam('Bank');
		if (isset($req))
		{
			foreach ($req as $k => $val)
			{
				$req[$k] = trim($val);
			}
			$data = CJSON::encode($req);

			$jsonMapper = new JsonMapper();
			$jsonObj	= CJSON::decode($data, false);
			$obj		= $jsonMapper->map($jsonObj, $bank);
			$model		= $obj->getData($model);
			$model->save();
			$this->redirect('creditlist');
			Yii::app()->end();
		}

		$data = $bank->setData($model);
		if ($data->accountNumber == '' || $data->ifsc == '')
		{
			$view = 'bankdetails';
		}
		else
		{
			$view = 'transferForm';
		}
		$outputJs = Yii::app()->request->isAjaxRequest;
		$method	  = "render" . ($outputJs ? "Partial" : "");
		$this->$method($view . $this->layoutSufix, array('model' => $data), false, $outputJs);
	}

	public function actionPaytransfer()
	{
		$userId	   = Yii::app()->user->getId();
		$modeldata = Contact::model()->getByUserId($userId);
		$model	   = Contact::model()->findbyPk($modeldata->ctt_id);
		$bank	   = new \Stub\common\Bank();
		$data	   = $bank->setData($model);
		$req	   = Yii::app()->request->getParam('Pay');
		if (isset($req))
		{
			$amount		   = $req['AMOUNT'];
			$remarks	   = substr(trim($req['REMARKS']), 0, 35);
			$walletbalance = UserWallet::getBalance($userId);
			if ($amount > $walletbalance || $amount <= 0)
			{
				return false;
			}
			$uniqueId = round(microtime(true) * 1000) . '';

			$entityArr['entity_type'] = 1;
			$entityArr['entity_id']	  = $userId;
			$userInfo				  = UserInfo::getInstance();
			$added					  = Yii::app()->icici->registerRequest($bank, $uniqueId, $amount, $entityArr, $remarks, $userInfo);

			if ($added)
			{
				$bankModel = OnlineBanking::model()->getByUniqueId($uniqueId);
				$bankModel->processPayment();
			}



			$this->redirect('creditlist', ['returnset' => $returnset]);
			Yii::app()->end();
		}


		if ($data->accountNumber == '' || $data->ifsc == '')
		{
			$view = 'bankdetails';
		}
		else
		{
			$view = 'transferForm';
		}
		$outputJs = Yii::app()->request->isAjaxRequest;
		$method	  = "render" . ($outputJs ? "Partial" : "");
		$this->$method($view . $this->layoutSufix, array('model' => $data), false, $outputJs);
	}

	public function actionVerifyAndResetPassword()
	{
		//$this->layout	 = 'column1';
		$this->checkV2Theme();
		$this->pageTitle = "Reset Password";
		$urlHash		 = Yii::app()->request->getParam('id');
		$hashArray		 = explode('_', $urlHash);
		$user_id		 = Yii::app()->shortHash->unhash($hashArray[0]);
		$value			 = base64_decode($hashArray[1]);
		$contactId		 = Yii::app()->shortHash->unhash($hashArray[2]);
		$agentId		 = Yii::app()->shortHash->unhash($hashArray[3]);
		$contactModel	 = Contact::model()->findByPk($contactId);
		if (filter_var($value, FILTER_VALIDATE_EMAIL))
		{
			$emailModel	 = ContactEmail::model()->findByEmailAndContact($value, $contactId);
			$expiredLink = $emailModel->eml_is_expired;
		}
		else
		{
			$phoneModel	 = ContactPhone::model()->findByPhoneAndContact($value, $contactId);
			$expiredLink = $phoneModel->phn_is_expired;
		}

		$userName = $contactModel->ctt_first_name . " " . $contactModel->ctt_last_name;
		if ($user_id == 0)
		{
			$status = "inv";
		}
		if (Yii::app()->request->getPost('signup'))
		{
			$password  = Yii::app()->request->getParam('txtuserPass');
			$cpassword = Yii::app()->request->getParam('cpassword');
			if ($password == $cpassword)
			{
				if (filter_var($value, FILTER_VALIDATE_EMAIL))
				{
					$status = Users::saveData($user_id, $cpassword, $value, $contactId);
				}
				else
				{
					$status = Users::savePhoneData($user_id, $cpassword, $value, $contactId);
				}

				if ($agentId)
				{
					$this->redirect(array('/agent/users/signin', 'status' => 'resetpass'));
				}
				else
				{
					$this->redirect(array('/users/signin', 'status' => 'pusucc'));
				}
			}
			else
			{
				$status = "errors";
			}
		}

		$this->render('resetpassword', array(
			'status'   => $status,
			'user_id'  => $user_id,
			'username' => $userName,
			'link'	   => $expiredLink
		));
	}

	/* first click for signin */

	public function actionSignin()
	{
		$this->layout	 = 'Signin';
		$this->pageTitle = "Login";
		$step			 = 2;
		$view			 = "signin";
		$contactId		 = '';

		/** @var HttpRequest $request */
		$request   = Yii::app()->request;
		$showPhone = $request->getParam("phone", 0);
		if ($this->pageRequest == null)
		{
			$rData			   = Yii::app()->request->getParam("rdata");
			$this->pageRequest = BookFormRequest::createInstance($rData);
		}
		$vAttach = $request->getParam("vAttach", null);

		$objPage = $this->pageRequest;

		$returnUrl = Yii::app()->user->getReturnUrl();
		if ($returnUrl == '')
		{
			$returnUrl = $this->getURL('/', $params);
		}
		if (UserInfo::isLoggedIn() && $showPhone == 0)
		{
			$this->redirect($returnUrl);
		}

		try
		{
			$userModel	  = new Users("userLogin");
			$contactModel = new Contact();
			$phoneModel	  = new ContactPhone();
			$emailModel	  = new ContactEmail();
			$params		  = [
				"userModel"	   => $userModel,
				'contactModel' => $contactModel,
				'phoneModel'   => $phoneModel,
				'emailModel'   => $emailModel,
				"showPhone"	   => $showPhone
			];
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);

			if ($request->isAjaxRequest)
			{
				echo json_encode($returnSet);
				Yii::app()->end();
			}
			$params['hasErrors']	= true;
			$params['errorMessage'] = $e->getMessage();
		}

		$sessSkipLoginCnt	   = Yii::app()->session['_gz_skip_login_count'];
		$skipLoginContactLimit = json_decode(Config::get('quote.guest'))->contactLimit;
		if ($sessSkipLoginCnt > 0 && $sessSkipLoginCnt > $skipLoginContactLimit)
		{
			$params['hideSkipLogin'] = 1;
		}

		view:
		$this->renderAuto($view, $params, false, true);
	}

//	public function actionSignupOTP()
//	{
//
//		$request = Yii::app()->request;
//		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_SIGNUP);
//
//		$this->pageRequest	 = BookFormRequest::createInstance();
//		$contactData		 = Yii::app()->request->getParam('Contact');
//		$userData			 = Yii::app()->request->getParam('Users');
//		$phoneData			 = Yii::app()->request->getParam('ContactPhone');
//		$emailData			 = Yii::app()->request->getParam('ContactEmail');
//
//		try
//		{
//			$signupObj	 = new \Stub\consumer\SignUpRequest();
//			$obj		 = $signupObj->setModelData($contactData, $emailData, $phoneData, $userData);
//			$isEmail	 = Filter::validateEmail($obj->profile->primaryEmail->value, true);
//			if (!$isEmail)
//			{
//				throw new Exception("Please enter valid email address", ReturnSet::ERROR_VALIDATION);
//			}
//			$phNumber	 = $obj->profile->primaryContact->code . $obj->profile->primaryContact->number;
//			$isPhone	 = Filter::processPhoneNumber($obj->profile->primaryContact->number, $obj->profile->primaryContact->code);
//			if (!$isPhone)
//			{
//				throw new Exception("Please enter valid phone number", ReturnSet::ERROR_VALIDATION);
//			}
//
//			if ($isEmail)
//			{
//				$emailModel						 = new ContactEmail();
//				$type							 = Stub\common\ContactVerification::TYPE_EMAIL;
//				$emailModel->eml_email_address	 = $obj->profile->primaryEmail->value;
//				$value							 = $emailModel->eml_email_address;
//				$contactId						 = Contact::getByEmailPhone($value);
//				if ($contactId != '')
//				{
//					throw new Exception("Sorry, this email is already registered with us", ReturnSet::ERROR_VALIDATION);
//				}
//				$objEmailContact = $this->pageRequest->getContact($type, $value);
//			}
//
//			if ($isPhone)
//			{
//				$phoneModel	 = new ContactPhone();
//				$type		 = Stub\common\ContactVerification::TYPE_PHONE;
//				Filter::parsePhoneNumber($isPhone, $code, $phone);
//
//				$phoneModel->phn_phone_country_code	 = $code;
//				$phoneModel->phn_phone_no			 = $phone;
//
//				$value		 = "+" . $code . $phone;
//				$contactId	 = Contact::getByEmailPhone('', $value);
//				if ($contactId != '')
//				{
//					throw new Exception("Sorry, this phone is already registered with us", ReturnSet::ERROR_VALIDATION);
//				}
//
//
//				$objPhoneContact = $this->pageRequest->getContact($type, $value);
//			}
//
//			$arrVerifyData = [];
//			if ($objPhoneContact)
//			{
//
//				$canSend = ContactPhone::checkTosendSMS($phoneModel->phn_phone_country_code, $phoneModel->phn_phone_no, SmsLog::SMS_LOGIN_REGISTER, 1);
//				if (!$canSend)
//				{
//					//$arrVerifyData[] = ["isSendSMS" => $objPhoneContact->isSendSMS];
//					goto SMSstuck;
//				}
//
//				Contact::verifyOTP($objPhoneContact, $canSend);
//				SMSstuck:
//				$arrVerifyData[] = ["type" => $objPhoneContact->type, "value" => $objPhoneContact->value, "isSendSMS" => $objPhoneContact->isSendSMS];
//			}
//
//			if ($objEmailContact)
//			{
//				Contact::verifyOTP($objEmailContact);
//				$arrVerifyData[] = ["type" => $objEmailContact->type, "value" => $objEmailContact->value];
//			}
//
//
//
//			$this->pageRequest->signupRequest	 = $obj;
//			$verifyData							 = Yii::app()->JWT->encode($arrVerifyData);
//			if ($phoneModel->phn_phone_country_code != 91 && $canSend)
//			{
//				$userModel	 = new Users();
//				$params		 = [
//					'userModel'	 => $userModel,
//					'verifyData' => $verifyData,
//					'verifyURL'	 => $this->getURL("users/captchaVerifySignup")
//				];
//				$view		 = "captchaVerify";
//				goto result;
//			}
//
//			$verifyURL	 = $this->getURL("users/processSignup");
//			//$this->render("otpVerify", ["verifyData" => $verifyData, "verifyURL" => $verifyURL]);
//			$params		 = [
//				'verifyData' => $verifyData,
//				'verifyURL'	 => $verifyURL
//			];
//			$view		 = "otpVerify";
//		}
//		catch (Exception $exc)
//		{
//			$view = "signin";
//			if ($exc->getCode() == ReturnSet::ERROR_VALIDATION)
//			{
//				$returnSet = new ReturnSet();
//				$returnSet->setErrors($exc->getMessage(), ReturnSet::ERROR_VALIDATION);
//				echo json_encode($returnSet);
//				Yii::app()->end();
//			}
//			else
//			{
//				$returnSet = ReturnSet::renderJSONException($exc);
//			}
//			$params = $returnSet;
//		}
//		result:
//		$this->renderAuto($view, $params, false, true);
//	}

	public function actionProcessSignup()
	{
		/** @var HttpRequest $request */
		$request = Yii::app()->request;
		try
		{
			$returnset		   = new ReturnSet();
			$verifyData		   = $request->getParam('verifyData');
			$rdata			   = $request->getParam('rdata');
			$this->pageRequest = BookFormRequest::createInstance($rdata);
			$objPage		   = $this->pageRequest;
			$data			   = Yii::app()->JWT->decode($verifyData);
			$curOtp			   = $request->getParam('otp');

			if (!$data)
			{
				throw new Exception("Invalid Request", ReturnSet::ERROR_INVALID_DATA);
			}

			//$jsonMapper	 = new JsonMapper();
			//$jsonObj	 = CJSON::decode($data, false);

			if (!$data)
			{
				throw new Exception("Invalid Request", ReturnSet::ERROR_INVALID_DATA);
			}

			$objSignUp	= $objPage->signupRequest;
			$objProfile = $objSignUp->profile;

			$objEmailContact = $objPage->getContact(Stub\common\ContactVerification::TYPE_EMAIL, $objProfile->primaryEmail->value);
			$objPhoneContact = $objPage->getContact(Stub\common\ContactVerification::TYPE_PHONE, $objProfile->primaryContact->getFullNumber());

			if ($objEmailContact->verifyOTP($curOtp) && !$objEmailContact->isOTPActive())
			{
				throw new Exception("OTP Expired. Please enter new OTP.", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			if ($objPhoneContact->verifyOTP($curOtp) && !$objPhoneContact->isOTPActive())
			{
				throw new Exception("OTP Expired. Please enter new OTP.", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			if (!$objPhoneContact->verifyOTP($curOtp) && !$objEmailContact->verifyOTP($curOtp))
			{
				throw new Exception("OTP Mistmached. Please try again", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			if ($objPhoneContact->verifyOTP($curOtp))
			{
				$objProfile->primaryContact->isVerified = true;
			}

			if ($objEmailContact->verifyOTP($curOtp))
			{
				$objProfile->primaryEmail->isVerified = true;
			}

			$cttModel  = $objProfile->getContactModel();
			$returnSet = $cttModel->create(true, UserInfo::TYPE_CONSUMER);
			if (!$returnSet->isSuccess())
			{
				throw new Exception("Sorry, unable to create your accounts", ReturnSet::ERROR_FAILED);
			}

			$userModel = Users::createbyContact($cttModel->ctt_id);
			if ($userModel)
			{

				Users::processReferralCode($userModel, $objSignUp->referredCode);

				$identity		  = new UserIdentity($userModel->user_email, null);
				$identity->userId = $userModel->user_id;
				if ($identity->authenticate())
				{

					Yii::app()->user->login($identity);
					$this->createLog($identity);
					$returnset->setStatus(true);
					$this->renderAuto("otpVerified");
					Yii::app()->end();
				}
			}
		}
		catch (Exception $e)
		{
			$returnset = ReturnSet::setException($e);
		}
		skipAll:
		echo json_encode($returnset);
		Yii::app()->end();
	}

	public function actionVerifyPass()
	{
		$request = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_SIGNINBYPASSWORD);
		try
		{
			$request = Yii::app()->request;
			if ($this->pageRequest == null)
			{
				$rData			   = Yii::app()->request->getParam("rdata");
				$this->pageRequest = BookFormRequest::createInstance($rData);
			}

			$objPage   = $this->pageRequest;
			$users	   = $request->getParam('Users');
			$userModel = new Users("userLogin");
			$userModel->setAttributes($users);

			if (!$userModel->validate())
			{
				throw new Exception(json_encode($userModel->getErrors()), ReturnSet::ERROR_VALIDATION);
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

			$userIdentity = new UserIdentity($users['username'], md5($users['new_password']));
			$userIdentity->setEntityID($userModel->user_id);
			$userIdentity->setUserType(UserInfo::TYPE_CONSUMER);

			if (!$userIdentity->authenticate())
			{
				throw new Exception("Sorry, authentication failed", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$userModel->loginIdentity($userIdentity);
			$returnSet = new ReturnSet();
			$returnSet->setStatus(true);
		}
		catch (Exception $exc)
		{
			$returnSet = ReturnSet::setException($exc);
		}

		echo json_encode($returnSet);
		Yii::app()->end();
	}

	public function actionSendOTP()
	{
		try
		{
			$request = Yii::app()->request;
			VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_SIGNINBYOTP);
			if ($this->pageRequest == null)
			{
				$rData			   = Yii::app()->request->getParam("rdata");
				$this->pageRequest = BookFormRequest::createInstance($rData);
			}
			$ref	   = Yii::app()->request->getParam("ref", null);
			$objPage   = $this->pageRequest;
			$users	   = $request->getParam('Users');
			$userModel = new Users("userLogin");
			$userModel->setAttributes($users);

			if (!$userModel->validate())
			{
				throw new Exception(json_encode($userModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			$objCttVerify = $objPage->getContact($userModel->usernameType, $userModel->username);
			if ($objCttVerify->type == 2)
			{
				$userModel->scenario = 'captchaRequired';
				Filter::parsePhoneNumber($objCttVerify->value, $code, $number);
				$canSendSMS			 = ContactPhone::checkTosendSMS($code, $number, SmsLog::SMS_LOGIN_REGISTER);
				if (!$canSendSMS)
				{
					throw new Exception(json_encode(['error' => "Problem while signin. Please contact support."]), ReturnSet::ERROR_VALIDATION);
				}

				if ($code != 91)
				{

					$params = [
						'userModel' => $userModel,
						'verifyURL' => $this->getURL("users/captchaVerify")
					];
					$view	= "captchaVerifyLogin";
					goto result;
				}
			}
			$smstextType	= "webOTP";
			Contact::verifyOTP($objCttVerify, $canSendSMS, $smstextType);
			$contactDetails = Yii::app()->JWT->encode([["type" => $objCttVerify->type, "value" => $objCttVerify->value, "isSendSMS" => $objCttVerify->isSendSMS]]);
			$objPage->updatePostData();
			$params			= [
				'verifyData' => $contactDetails,
				'verifyotp'	 => $objCttVerify->otp,
				'verifyURL'	 => $this->getURL("users/verifyOTP"),
				'ref'		 => $ref
			];
			$view			= "otpVerify";
		}
		catch (Exception $exc)
		{
			$view = "signin";
			if ($exc->getCode() == ReturnSet::ERROR_VALIDATION)
			{
				$returnSet = new ReturnSet();
				$returnSet->setErrors(json_decode($exc->getMessage()), ReturnSet::ERROR_VALIDATION);
				echo json_encode($returnSet);
				Yii::app()->end();
			}
			else
			{
				$returnSet = ReturnSet::renderJSONException($exc);
			}
			$params = $returnSet;
		}
		result:
		$this->renderAuto($view, $params, false, true);
	}

	public function actionVerifyOTP()
	{
		/** @var HttpRequest $request */
		//Logger::info("intiate process");
		$request		 = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType());
		$this->layout	 = 'column_booking';
		$this->pageTitle = "OTP verify";
		$returnset		 = new ReturnSet();

		$request = Yii::app()->request;
		if ($this->pageRequest == null)
		{
			$rData			   = Yii::app()->request->getParam("rdata");
			$this->pageRequest = BookFormRequest::createInstance($rData);
		}
		$objPage = $this->pageRequest;
		$signup	 = $request->getParam('signup', 1);
		$ref	 = Yii::app()->request->getParam("ref", null);
		$params	 = [];
		try
		{
			$returnUrl = Yii::app()->user->getReturnUrl();
			if ($returnUrl == '')
			{
				$returnUrl = Yii::app()->createUrl('/', $params);
			}
			if ($ref == 'vendorAttach')
			{
				$returnUrl = Yii::app()->createUrl('/vendor/attach');
			}

			$newContactComponent = json_decode($request->getParam('newContactComponent'));
			$newContactComponent->type;
			$curOtp				 = $request->getParam('otp');
			$verifyData			 = $request->getParam('verifyData');
			$arrVerifyData		 = Yii::app()->JWT->decode($verifyData);

			$data1 = json_decode($arrVerifyData);
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

			$objCttVerify = $objPage->getContact($data->type, $data->value);

			if (!$objCttVerify->isOTPActive())
			{
				throw new Exception("OTP Expired. Please enter new OTP.", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$success = $objCttVerify->verifyOTP($curOtp);
			if (!$success)
			{
				throw new Exception("OTP Mistmached. Please try again", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			if ($data->type == Stub\common\ContactVerification::TYPE_EMAIL)
			{
				$sessEmail = $data->value;
			}
			if ($data->type == Stub\common\ContactVerification::TYPE_PHONE)
			{
				$sessPhone = $data->value;
			}
			$createIfNotExist = ($signup == 2);
			$contactId		  = Contact::getByEmailPhone($sessEmail, $sessPhone, $createIfNotExist);
			if (!$contactId)
			{
				throw new Exception("Sorry, we couldn't find this data in our records", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			//	Logger::info("contact id");
			$userModel = Users::createbyContact($contactId);

			if ($ref == "resetPassword")
			{

				$userArr = ['username' => $userModel->usr_name . ' ' . $userModel->usr_lname, 'uid' => Yii::app()->shortHash->hash($userModel->user_id), 'key' => md5($userModel->usr_password)];
				$returnset->setStatus(true);
				$returnset->setData($userArr);
				echo json_encode($returnset);
				Yii::app()->end();
			}

			if (count($userModel) > 0)
			{
				//Logger::info("get user model".$userModel->user_id);
				$identity		  = new UserIdentity($userModel->usr_name, null);
				$identity->userId = $userModel->user_id;

				if ($newContactComponent != "")
				{
					$newType = $newContactComponent->type;
					if ($newType != $data->type)
					{
						if ($newType == 2)
						{
							Filter::parsePhoneNumber($newContactComponent->value, $code, $phnumber);
							ContactPhone::model()->add($contactId, $phnumber, 0, $code, null, 0, 0, 1);
						}

						if ($newType == 1)
						{
							ContactEmail::model()->addNew($contactId, $newContactComponent->value, SocialAuth::Eml_aaocab, 0, 1, 1);
						}
					}
				}



				//Logger::info("user id");
				if ($identity->authenticate())
				{
					$objPage->clearContact();
					Yii::app()->user->login($identity);
					//Logger::info("get identity user id" . $identity->getId());
					$this->createLog($identity);
					$returnset->setStatus(true);
					$returnUrl = Yii::app()->user->getReturnUrl();

					$this->renderAuto("otpVerified", ['returnUrl' => $returnUrl]);
					Yii::app()->end();
				}
			}
		}
		catch (Exception $e)
		{
			$returnset = ReturnSet::setException($e);
			echo json_encode($returnset);
			Yii::app()->end();
		}
		skipAll:
		echo json_encode($returnset);
		Yii::app()->end();
	}

	public function actionResendOtp()
	{
		//VisitorTrack::track(CJSON::encode($_REQUEST), Filter::method());

		$this->pageTitle = "Resend OTP";
		$returnset		 = new ReturnSet();
		$request		 = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType());

		$verifyData	   = $request->getParam('verifyData');
		$arrVerifyData = Yii::app()->JWT->decode($verifyData);
		try
		{
			$request = Yii::app()->request;
			if ($this->pageRequest == null)
			{
				$rData			   = Yii::app()->request->getParam("rdata");
				$this->pageRequest = BookFormRequest::createInstance($rData);
			}
			$objPage = $this->pageRequest;
			foreach ($arrVerifyData as $data)
			{
				$objCttVerify = $objPage->getContact($data->type, $data->value);

				if ($objCttVerify->otpRetry >= 3)
				{
					throw new Exception("Time exceed you can send it later", ReturnSet::ERROR_FAILED);
				}
				if ($objCttVerify->otpValidTill > time())
				{
					throw new Exception("OTP not send", ReturnSet::ERROR_FAILED);
				}

				Contact::verifyOTP($objCttVerify);
			}
			$objPage->updatePostData();

			Filter::removeNull($objPage);
			if ($objPage)
			{
				$returnset->setStatus(true);
				$returnset->setData(['rdata' => $this->pageRequest->getEncrptedData()]);
			}
		}
		catch (Exception $e)
		{
			$returnset = ReturnSet::setException($e);
		}
		echo json_encode($returnset);
		Yii::app()->end();
	}

	public function actionRegVisitor()
	{
		$visitorCookie = Yii::app()->request->cookies['gvid'];
		if ($visitorCookie)
		{
			echo $visitorCookie->value;
			Yii::app()->end();
		}
		if (!$visitorCookie)
		{
			$sessionid = Yii::app()->getSession()->getSessionId();
			$date	   = date('Y-m-d H:i:s');
			$vistorId  = md5($sessionid . $date . SERVER_ID);

			$visitorCookie			 = new CHttpCookie('gvid', $vistorId);
			//$visitorCookie->domain	 = Yii::app()->params['domain'];
			$visitorCookie->sameSite = CHttpCookie::SAME_SITE_STRICT;
			$visitorCookie->httpOnly = true;
			$visitorCookie->expire	 = time() + (60 * 24 * 365 * 2);
			Yii::app()->request->getCookies()->add($visitorCookie->name, $visitorCookie);
		}
		echo $visitorCookie->value;
		Yii::app()->end();
	}

	public function actionCaptchaVerify()
	{
		/** @var HttpRequest $request */
		//	$this->layout	 = 'column_booking';
		$this->pageTitle = "Captcha";
		$returnset		 = new ReturnSet();

		$request = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_CAPTCHA_VERIFY);

		if ($this->pageRequest == null)
		{
			$rData			   = Yii::app()->request->getParam("rdata");
			$this->pageRequest = BookFormRequest::createInstance($rData);
		}

		//$canSendSMS		 = Yii::app()->request->getParam("canSendSMS");
		$objPage	  = $this->pageRequest;
		$att		  = BookFormRequest::decryptData($rData);
		$objCttVerify = $att->contactVerifications[0];
		$users		  = Yii::app()->request->getParam("Users");
		$verifyCode	  = $users['verifyCode'];
		$userModel	  = new Users("captchaRequired");
		try
		{
			$userModel->verifyCode			= $verifyCode;
			$userModel->usr_create_platform = 1;

			if (!$userModel->validate())
			{
				throw new Exception(json_encode($userModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			$contactDetails = Yii::app()->JWT->encode([["type" => $objCttVerify->type, "value" => $objCttVerify->value]]);
			Contact::verifyOTP($objCttVerify);

			$objPage->contactVerifications[0] = $objCttVerify;
			$objPage->updatePostData();
			$params							  = [
				'verifyData' => $contactDetails,
				'verifyotp'	 => $objCttVerify->otp,
				'verifyURL'	 => $this->getURL("users/verifyOTP")
			];
			$view							  = "otpVerify";
		}
		catch (Exception $exc)
		{
			$returnset = ReturnSet::setException($exc);
			ReturnSet::renderJSONException($exc);
			$params	   = [
				'userModel' => $userModel,
				'verifyURL' => $this->getURL("users/captchaVerify")
			];
			$view	   = "captchaVerifyLogin";
		}

		$this->renderAuto($view, $params, false, true);
	}

	public function actionGetQRPathById()
	{
		$userId	   = Yii::app()->request->getParam('userId');
		$userModel = Users::model()->findByPk($userId);
		$s3data	   = $userModel->usr_s3_data;
		$imgPath   = $userModel->usr_qr_code_path;

		if ($imgPath == '' || $imgPath == NULL)
		{
			$returnSet = QrCode::processData($userId);
			$success   = $returnSet->getStatus();
			if ($success)
			{
				$userModel = Users::model()->findByPk($userId);
				$s3data	   = $userModel->usr_s3_data;
				$imgPath   = $userModel->usr_qr_code_path;
			}
		}

		$filePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . $imgPath;
		if (file_exists($filePath))
		{
			if (!file_exists($filePath))
			{
				return false;
			}
			$mimeType = CFileHelper::getMimeType($filePath);
			if ($fileName == "")
			{
				$fileName = basename($filePath);
			}
			$ext = CFileHelper::getExtension($filePath);
			if ($ext == "")
			{
				$ext	  = CFileHelper::getExtensionByMimeType($filePath);
				$fileName .= "." . $ext;
			}
			$content = file_get_contents($filePath);
			$size = filesize($filePath);
			Logger::trace("FileSize: {$size}");
			#$content = file_get_contents($filePath);
			if ($mimeType === null)
			{
				if (($mimeType = CFileHelper::getMimeTypeByExtension($fileName)) === null)
					$mimeType = 'text/plain';
			}

			$fileSize	  = (function_exists('mb_strlen') ? mb_strlen($content, '8bit') : strlen($content));
			$contentStart = 0;
			$contentEnd	  = $fileSize - 1;

			$httpVersion = Yii::app()->request->getHttpVersion();
			if (isset($_SERVER['HTTP_RANGE']))
			{
				$terminate = true;	

				header('Accept-Ranges: bytes');

				//client sent us a multibyte range, can not hold this one for now
				if (strpos($_SERVER['HTTP_RANGE'], ',') !== false)
				{
					header("Content-Range: bytes $contentStart-$contentEnd/$fileSize");
					throw new CHttpException(416, 'Requested Range Not Satisfiable');
				}

				$range = str_replace('bytes=', '', $_SERVER['HTTP_RANGE']);

				//range requests starts from "-", so it means that data must be dumped the end point.
				if ($range[0] === '-')
					$contentStart = $fileSize - substr($range, 1);
				else
				{
					$range		  = explode('-', $range);
					$contentStart = $range[0];

					// check if the last-byte-pos presents in header
					if ((isset($range[1]) && is_numeric($range[1])))
						$contentEnd = $range[1];
				}

				/* Check the range and make sure it's treated according to the specs.
				 * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html
				 */
				// End bytes can not be larger than $end.
				$contentEnd = ($contentEnd > $fileSize) ? $fileSize - 1 : $contentEnd;

				// Validate the requested range and return an error if it's not correct.
				$wrongContentStart = ($contentStart > $contentEnd || $contentStart > $fileSize - 1 || $contentStart < 0);

				if ($wrongContentStart)
				{
					header("Content-Range: bytes $contentStart-$contentEnd/$fileSize");
					throw new CHttpException(416, 'Requested Range Not Satisfiable');
				}

				header("HTTP/$httpVersion 206 Partial Content");
				header("Content-Range: bytes $contentStart-$contentEnd/$fileSize");
			}
			else
				header("HTTP/$httpVersion 200 OK");

			$length = $contentEnd - $contentStart + 1; // Calculate new content length

			header('Pragma: public');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header("Content-Type: $mimeType");
			header("Content-Disposition: attachment; filename=\"$fileName\"");
			header('Content-Transfer-Encoding: binary');
			$content = function_exists('mb_substr') ? mb_substr($content, $contentStart, $length, '8bit') : substr($content, $contentStart, $length);
			Logger::trace(json_encode(headers_list()));
			Logger::warning("QR Download requested", true);
			if ($terminate)
			{
				// clean up the application first because the file downloading could take long time
				// which may cause timeout of some resources (such as DB connection)
				ob_start();
				Yii::app()->end(0, false);
				ob_end_clean();
				echo $content;
				exit(0);
			}
			else
				echo $content;
			#echo $content;
		}
		else if ($s3data != '')
		{
			$spaceFile = Stub\common\SpaceFile::populate($s3data);
			$url	   = $spaceFile->getURL();
			Yii::app()->request->redirect($url);
		}
	}

	public function actionGetQRCode()
	{
		try
		{
			$errMsg		  = '';
			$this->checkV3Theme();
			$this->layout = 'column2';
			if (Yii::app()->user->isGuest)
			{
				$this->redirect(array('users/view'));
			}
			$this->current_page = 'QR Code';
			$this->pageTitle	= 'QR Code';
			$userId				= Yii::app()->user->getId();
			$userModel			= Users::model()->findByPk($userId);
			if ($userModel->usr_qr_code_path == '')
			{
				$ret = QrCode::processData($userId);
				if (!$ret->getStatus())
				{
					throw new Exception($ret->getMessage());
				}
			}
			$path	 = Users::getUserPathById($userId);
			$qrModel = QrCode::model()->find('qrc_ent_type=1 AND qrc_ent_id = :userid', array('userid' => $userId));
			$code	 = ($qrModel) ? $qrModel->qrc_code : '';
		}
		catch (Exception $ex)
		{
			$errMsg = $ex->getMessage();
		}
		$this->render('qrcode', array('models' => $userModel, 'qrpath' => $path, 'qrcode' => $code, 'errMsg' => $errMsg));
	}

	public function actionCaptchaVerifySignup()
	{
		$this->pageTitle = "Captcha";
		$returnset		 = new ReturnSet();

		$request = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType());

		if ($this->pageRequest == null)
		{
			$rData			   = Yii::app()->request->getParam("rdata");
			$this->pageRequest = BookFormRequest::createInstance($rData);
		}
		$objPage	  = $this->pageRequest;
		$att		  = BookFormRequest::decryptData($rData);
		$objCttVerify = $att->contactVerifications[1];
		$users		  = Yii::app()->request->getParam("Users");
		$verifyCode	  = $users['verifyCode'];
		$userModel	  = new Users("captchaRequired");
		try
		{
			$userModel->verifyCode			= $verifyCode;
			$userModel->usr_create_platform = 1;
			if (!$userModel->validate())
			{
				throw new Exception(json_encode($userModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			$params = [
				'verifyData' => Yii::app()->request->getParam("verifyData"),
				'verifyotp'	 => $objCttVerify->otp,
				'verifyURL'	 => $this->getURL("users/processSignup")
			];
			$view	= "otpVerify";
		}
		catch (Exception $exc)
		{
			$returnset = ReturnSet::setException($exc);
			ReturnSet::renderJSONException($exc);
			$params	   = [
				'verifyData' => $contactDetails,
				'userModel'	 => $userModel,
				'verifyURL'	 => $this->getURL("users/CaptchaVerifySignup")
			];
			$view	   = "captchaVerify";
		}

		$this->renderAuto($view, $params, false, true);
	}

	/* userName verification for signin / registration */

	public function actionVerifyUserName()
	{
		$request = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_SIGNINBYPASSWORD);
		try
		{
			$returnSet = new ReturnSet();
			$returnSet->setStatus(true);
			$request   = Yii::app()->request;
			if ($this->pageRequest == null)
			{
				$rData			   = Yii::app()->request->getParam("rdata");
				$this->pageRequest = BookFormRequest::createInstance($rData);
			}
			$objPage = $this->pageRequest;
			$users	 = $request->getParam('Users');

			$isEmailOrPhone = Users::isEmailOrPhone($users['username']);

			if (!$isEmailOrPhone)
			{
				$returnSet->setStatus(false);
				$returnSet->setData(["errors" => 'Please enter valid email/phone number']);
				Logger::create("UsersController:: not isEmailOrPhone", CLogger::LEVEL_INFO);
				goto end;
				//	throw new Exception("Sorry, You are not registered with us and please enter valid username", ReturnSet:://ERROR_NO_RECORDS_FOUND);
			}

			($isEmailOrPhone['type'] != 1) ? ($isEmailOrPhone['number']						= $isEmailOrPhone['value']) : ($isEmailOrPhone['email']						= $isEmailOrPhone['value']);
			$usrDt											= \Beans\contact\Person::setBasicInfoFromData($isEmailOrPhone);
			$travellerCookie								= new CHttpCookie('travellerCookie', $usrDt);
			Yii::app()->request->cookies['travellerCookie'] = $travellerCookie;

			$userModel = new Users("userLoginEmailPhone");
			$userModel->setAttributes($users);
			Logger::trace("UsersController::VerifyUserName :: " . $users['username']);
			$ref	   = Yii::app()->request->getParam("ref", null);
			if (!$userModel->validate())
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors(Filter::getNestedValues($userModel->getErrors()));
				goto end;
			}

			$userModel->setScenario("userLogin");

			if ($userModel->validate())
			{
				goto validationSuccess;
			}


			Logger::create("UsersController::not validate :: " . json_encode($userModel->getErrors()), CLogger::LEVEL_INFO);
			$arrVerifyData	   = [];
			$isCaptchaVerify   = 0;
			$verifyURL		   = '';
			//throw new Exception(json_encode($userModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			//else
			//{
			$this->pageRequest = BookFormRequest::createInstance();
			if ($userModel->usernameType == Stub\common\ContactVerification::TYPE_EMAIL)
			{
				Logger::create("UsersController:: not isEmailOrPhone type 1", CLogger::LEVEL_INFO);
				$objEmailContact = $this->pageRequest->getContact($isEmailOrPhone['type'], $userModel->usr_email);
				Contact::verifyOTP($objEmailContact);
				$arrVerifyData	 = ["type" => $objEmailContact->type, "value" => $objEmailContact->value, 'otp' => $objEmailContact->otp];
				$otpObj			 = $objEmailContact;
			}
			else
			{
				Logger::create("UsersController:: not isEmailOrPhone type else", CLogger::LEVEL_INFO);
				$objPhoneContact	 = $this->pageRequest->getContact($isEmailOrPhone['type'], $userModel->getFullMobileNumber());
				$this->pageRequest->updatePostData();
				$userModel->scenario = 'captchaRequired';
				$code				 = $userModel->usr_country_code;
				$number				 = $userModel->usr_mobile;
				$canSendSMS			 = ContactPhone::checkTosendSMS($code, $number, SmsLog::SMS_LOGIN_REGISTER);
				if ($code != 91 || $canSendSMS == false)
				{
					Logger::create("UsersController::checkTosendSMS code {$code} canSendSMS {$canSendSMS}", CLogger::LEVEL_INFO);
					$isCaptchaVerify = 1;
					$arrVerifyData	 = ["type" => $objPhoneContact->type, "value" => $objPhoneContact->value];
					$verifyData		 = Yii::app()->JWT->encode($arrVerifyData);
					$verifyData		 = Yii::app()->JWT->encode($arrVerifyData);
					goto captcha;
				}

				$smstextType   = "webOTP";
				Contact::verifyOTP($objPhoneContact, $canSendSMS, $smstextType);
				Logger::create("Contact::verifyOTP", CLogger::LEVEL_INFO);
				$arrVerifyData = ["type" => $objPhoneContact->type, "value" => $objPhoneContact->value, "isSendSMS" => $objPhoneContact->isSendSMS, 'otp' => $objPhoneContact->otp];
				$otpObj		   = $objPhoneContact;
			}
			$otpObjectEnp = Yii::app()->JWT->encode($otpObj);
			$verifyData	  = Yii::app()->JWT->encode($arrVerifyData);

			captcha:
			if ($isCaptchaVerify)
			{
				$this->renderAuto("captchaVerify", ["verifyData" => $verifyData, "userModel" => $userModel, 'rdata' => $this->pageRequest->getEncrptedData()]);
			}
			else
			{
				$this->renderAuto("signUpByOtp", ["otpObj" => $otpObjectEnp, "verifyData" => $verifyData, "userModel" => $userModel, 'rdata' => $this->pageRequest->getEncrptedData()]);
			}
			Yii::app()->end();

			validationSuccess:
			$contactId = $userModel->usr_contact_id;
			$userId	   = ContactProfile::getUserId($contactId);
			Logger::create("ContactProfile::getUserId " . $contactId, CLogger::LEVEL_INFO);
			if (!$userId)
			{
				$userId = Users::getByContactId($contactId);
				Logger::create("Users::getByContactId " . $userId, CLogger::LEVEL_INFO);
			}

			if (!$userId)
			{
				$userModel = Users::createbyContact($contactId);
				Logger::create("Users::createbyContact " . $contactId, CLogger::LEVEL_INFO);
			}
			else
			{
				$userModel = Users::model()->findByPk($userId);
			}
			if (!$userModel)
			{
				Logger::create("Users::not userModel ", CLogger::LEVEL_INFO);
				throw new Exception("Sorry, You are not registered with us", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$isEmailOrPhone = Users::isEmailOrPhone($users['username']);
			if ($isEmailOrPhone['type'] != 1)
			{
				Filter::parsePhoneNumber($users['username'], $code, $number);
			}

			$returnSet->setData(
					[
						'userName'		=> $users['username'],
						'consumerName'	=> ($userModel->usr_name != " ") ? (ucfirst($userModel->usr_name)) : ("to aaocab"),
						'isNewUser'		=> 0,
						'userNameCode'	=> $isEmailOrPhone['phCode'],
						'userNamePhone' => $isEmailOrPhone['phNumber'],
						'type'			=> $isEmailOrPhone['type'],
						'ref'			=> $ref
			]);
			Logger::create("Users:: 4907 " . json_encode($returnSet->getData()), CLogger::LEVEL_INFO);
		}
		catch (Exception $exc)
		{
			Logger::create("Exception:: 4911 " . $exc->getMessage(), CLogger::LEVEL_INFO);
			$returnSet = ReturnSet::setException($exc);
		}
		stuck:
		end:

		echo json_encode($returnSet);
		Yii::app()->end();
	}

	public function actionVerifyPassword()
	{
		$request = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_SIGNINBYPASSWORD);
		try
		{
			$returnSet = new ReturnSet();
			$request   = Yii::app()->request;
			if ($this->pageRequest == null)
			{
				$rData			   = Yii::app()->request->getParam("rdata");
				$this->pageRequest = BookFormRequest::createInstance($rData);
			}
			$ref = Yii::app()->request->getParam("ref", null);

			$objPage   = $this->pageRequest;
			$users	   = $request->getParam('Users');
			$userModel = new Users("userLogin");
			$userModel->setAttributes($users);

			if (!$userModel->validate())
			{
				throw new Exception(json_encode($userModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			$isEmailOrPhone = Users::isEmailOrPhone($users['username']);
			if (!$isEmailOrPhone)
			{
				$returnSet->setStatus(false);
				$returnSet->setData(["errors" => 'Please enter valid email/phone number']);
				throw new Exception("Sorry, You are not registered with us and please enter valid username", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			if ($isEmailOrPhone['type'] != 1)
			{
				Filter::parsePhoneNumber($users['username'], $code, $number);
				$vpPhone = $code . $number;
			}
			else
			{
				$vpEmail = $users['username'];
			}
			$contactId = Contact::getByEmailPhone($vpEmail, $vpPhone, false);
			//  $contactId = $userModel->usr_contact_id;


			$userId = ContactProfile::getUserId($contactId);
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

			$userIdentity = new UserIdentity($users['username'], md5($users['new_password']));
			$userIdentity->setEntityID($userModel->user_id);
			$userIdentity->setUserType(UserInfo::TYPE_CONSUMER);

			if (!$userIdentity->authenticate())
			{
				throw new Exception("Sorry, authentication failed", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$userModel->loginIdentity($userIdentity);

			if ($ref == 'vendorAttach')
			{
				$returnUrl = Yii::app()->createUrl('/vendor/attach');
				$returnSet->setData(['returnUrl' => $returnUrl]);
			}

			$returnUrl = Yii::app()->user->getReturnUrl();
			if ($returnUrl != '')
			{
				$returnSet->setData(['returnUrl' => $returnUrl]);
			}
			$returnSet->setStatus(true);
		}
		catch (Exception $exc)
		{
			$returnSet = ReturnSet::setException($exc);
		}

		echo json_encode($returnSet);
		Yii::app()->end();
	}

	public function actionSignupOTPNew()
	{

		$request = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_SIGNUP);

		$isEmail		   = false;
		$this->pageRequest = BookFormRequest::createInstance();
		$contactData	   = Yii::app()->request->getParam('Contact');
		$userData		   = Yii::app()->request->getParam('Users');
		$phoneData		   = Yii::app()->request->getParam('ContactPhone');
		$emailData		   = Yii::app()->request->getParam('ContactEmail');
		$curOtp			   = $request->getParam('otp');
		$data			   = ($request->getParam('verifyData')) ? (Yii::app()->JWT->decode($request->getParam('verifyData'))) : '';
		$otpObject		   = ($request->getParam('otpObject')) ? (Yii::app()->JWT->decode($request->getParam('otpObject'))) : '';

		$rData			   = Yii::app()->request->getParam("rdata");
		$this->pageRequest = BookFormRequest::createInstance($rData);
		$objPage		   = $this->pageRequest;
		try
		{
			$ref = Yii::app()->request->getParam("ref", null);

			$returnset = new ReturnSet();
			if (!$data)
			{
				throw new Exception("Invalid Request", ReturnSet::ERROR_INVALID_DATA);
			}

			$signupObj = new \Stub\consumer\SignUpRequest();
			$obj	   = $signupObj->setModelData($contactData, $emailData, $phoneData, $userData);

			if ($obj->profile->primaryEmail->value)
			{
				$isEmail = Filter::validateEmail($obj->profile->primaryEmail->value, true);
				if (!$isEmail)
				{
					throw new Exception("Please enter valid email address", ReturnSet::ERROR_VALIDATION);
				}
			}
			$phNumber = $obj->profile->primaryContact->code . $obj->profile->primaryContact->number;
			$isPhone  = Filter::processPhoneNumber($obj->profile->primaryContact->number, $obj->profile->primaryContact->code);
			if (!$isPhone)
			{
				throw new Exception("Please enter valid phone number", ReturnSet::ERROR_VALIDATION);
			}

			if ($isEmail)
			{
				$emailModel					   = new ContactEmail();
				$type						   = Stub\common\ContactVerification::TYPE_EMAIL;
				$emailModel->eml_email_address = $obj->profile->primaryEmail->value;
				$value						   = $emailModel->eml_email_address;
				$contactId					   = Contact::getByEmailPhone($value);
				if ($contactId != '')
				{
					if ($otpObject->type == $type)
					{
						$ex = new Exception("Sorry, this email is already registered with us", ReturnSet::ERROR_VALIDATION);
						Logger::warning($ex, true);
						throw $ex;
					}
				}
				$objEmailContact = $this->pageRequest->getContact($type, $value);
				if ($otpObject->type == $type)
				{
					$objEmailContact->otp		   = $otpObject->otp;
					$objEmailContact->otpValidTill = $otpObject->otpValidTill;
					$objEmailContact->otpRetry	   = $otpObject->otpRetry;
					$objEmailContact->otpLastSent  = $otpObject->otpLastSent;
					$objEmailContact->status	   = $otpObject->status;
					$objEmailContact->captcha	   = $otpObject->captcha;
					$objEmailContact->isSendSMS	   = $otpObject->isSendSMS;
				}
			}

			if ($isPhone)
			{
				$phoneModel = new ContactPhone();
				$type		= Stub\common\ContactVerification::TYPE_PHONE;
				Filter::parsePhoneNumber($isPhone, $code, $phone);

				$phoneModel->phn_phone_country_code = $code;
				$phoneModel->phn_phone_no			= $phone;

				$value	   = "+" . $code . $phone;
				$contactId = Contact::getByEmailPhone('', $value);
				if ($contactId != '')
				{
					if ($otpObject->type == $type)
					{
						$ex = new Exception("Sorry, this phone is already registered with us", ReturnSet::ERROR_VALIDATION);
						Logger::warning($ex, true);
						throw $ex;
					}
				}
				$objPhoneContact = $this->pageRequest->getContact($type, $value);
				if ($otpObject->type == $type)
				{

					$objPhoneContact->otp		   = $otpObject->otp;
					$objPhoneContact->otpValidTill = $otpObject->otpValidTill;
					$objPhoneContact->otpRetry	   = $otpObject->otpRetry;
					$objPhoneContact->otpLastSent  = $otpObject->otpLastSent;
					$objPhoneContact->status	   = $otpObject->status;
					$objPhoneContact->captcha	   = $otpObject->captcha;
					$objPhoneContact->isSendSMS	   = $otpObject->isSendSMS;
				}
			}

			$this->pageRequest->signupRequest = $obj;
			$objSignUp						  = $objPage->signupRequest;
			$objProfile						  = $objSignUp->profile;

			$objEmailContact = $objPage->getContact(Stub\common\ContactVerification::TYPE_EMAIL, $objProfile->primaryEmail->value);
			$objPhoneContact = $objPage->getContact(Stub\common\ContactVerification::TYPE_PHONE, $objProfile->primaryContact->getFullNumber());

			if ($objEmailContact->verifyOTP($curOtp) && !$objEmailContact->isOTPActive())
			{
				throw new Exception("OTP Expired. Please enter new OTP.", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			if ($objPhoneContact->verifyOTP($curOtp) && !$objPhoneContact->isOTPActive())
			{
				throw new Exception("OTP Expired. Please enter new OTP.", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			if (!$objPhoneContact->verifyOTP($curOtp) && !$objEmailContact->verifyOTP($curOtp))
			{
				throw new Exception("OTP Mistmached. Please try again", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			if ($objPhoneContact->verifyOTP($curOtp))
			{
				$objProfile->primaryContact->isVerified = true;
			}

			if ($objEmailContact->verifyOTP($curOtp))
			{
				$objProfile->primaryEmail->isVerified = true;
			}

			$cttModel = $objProfile->getContactModel();

			// check for reverse contact exist or not
//                       if ($ref != 'vendorAttach')
//            {
			if ($objProfile->primaryEmail->value != null && $objProfile->primaryContact->number != null && $ref != 'vendorAttach')
			{
				if ($otpObject->type == 1)
				{
					$existValue	  = "+" . $objProfile->primaryContact->code . $objProfile->primaryContact->number;
					$existType	  = "Phone Number";
					$existTypeInt = 2;
					$errorCode	  = ReturnSet::ERROR_PHONEEXIST;
					$exitstRecord = ContactPhone::getByPhone($objProfile->primaryContact->getFullNumber());
				}
				else
				{
					$existValue	  = $objProfile->primaryEmail->value;
					$existType	  = "Email Address";
					$existTypeInt = 1;
					$errorCode	  = ReturnSet::ERROR_EMAILEXIST;
					$exitstRecord = ContactEmail::getByEmail($objProfile->primaryEmail->value);
				}

				foreach ($exitstRecord as $contactRow)
				{
					$existContactId = $contactRow['ctt_id'];
				}
				if ($existContactId > 0)
				{

//                $existArr =["value"=>$existValue,"contact"=>$existContactId];
//                $returnset->set($existArr,true);  
					$ex = new Exception("This {$existType} {$existValue} is already registered with us, click  continue to login with this existing account or press cancel to change {$existType} and create a new account .", $errorCode);
					Logger::warning($ex, true);
					throw $ex;
				}
				//  }
			}

			$returnSet = $cttModel->create(true, UserInfo::TYPE_CONSUMER);
			if (!$returnSet->isSuccess())
			{
				$ex = $returnSet->getException();
				Logger::warning($ex, true);
				throw $ex;
			}
			$sendPassword = 1;
			$userModel	  = Users::createbyContact($cttModel->ctt_id, $sendPassword);

			$valueToVerified = ($otpObject->type == 1) ? ($obj->profile->primaryEmail->value) : ($obj->profile->primaryContact->number);
			$dataArr		 = ['otp' => $otpObject->otp, 'type' => $otpObject->type, 'value' => $valueToVerified];
			Contact::verifyInfo(json_decode(json_encode($dataArr), FALSE), $cttModel->ctt_id, $curOtp);

			if ($userModel)
			{
				if ($objSignUp->referredCode != '')
				{
					Users::processReferralCode($userModel, $objSignUp->referredCode);
				}
				$identity		  = new UserIdentity($userModel->user_email, null);
				$identity->userId = $userModel->user_id;
				if ($identity->authenticate())
				{
					Yii::app()->user->login($identity);
					$this->createLog($identity);
					$returnset->setStatus(true);
					if ($ref == 'vendorAttach')
					{
						$this->redirect('/vendor/attach');
//						$returnSet->setData(['returnUrl' => $returnUrl]);
					}
					$this->renderAuto("otpVerified");
					Yii::app()->end();
				}
			}
		}
		catch (Exception $exc)
		{
			$returnSet = ReturnSet::setException($exc);
			$view	   = "signin";
			if ($exc->getCode() == ReturnSet::ERROR_VALIDATION)
			{
				$message = $exc->getMessage();
				$returnSet->setErrors($message);
				echo json_encode($returnSet);
				Yii::app()->end();
			}
			else if ($exc->getCode() == ReturnSet::ERROR_EMAILEXIST || $exc->getCode() == ReturnSet::ERROR_PHONEEXIST)
			{
				$returnSet->setData(["rdata" => $rdata, "signUpdata" => $objSignUp, "otpObj" => $otpObject, "existContactType" => $existTypeInt, "verifyData" => $data]);
				echo json_encode($returnSet);
				Yii::app()->end();
			}
			else
			{
				$returnSet = ReturnSet::renderJSONException($exc);
			}
			$params = $returnSet;
		}
//		result:
		$this->renderAuto($view, $params, false, true);

		echo json_encode($returnset);
		Yii::app()->end();
	}

	public function actionCaptchaVerifyNew()
	{
		/** @var HttpRequest $request */
		$this->pageTitle = "Captcha";
		$returnset		 = new ReturnSet();
		$request		 = Yii::app()->request;

		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_CAPTCHA_VERIFY);

		if ($this->pageRequest == null)
		{
			$rData			   = Yii::app()->request->getParam("rdata");
			$this->pageRequest = BookFormRequest::createInstance($rData);
		}
		Yii::app()->request->getParam("rdata");

		$objPage	  = $this->pageRequest;
		$att		  = BookFormRequest::decryptData($rData);
		$objCttVerify = $att->contactVerifications[0];

		$users		= Yii::app()->request->getParam("Users");
		$verifyCode = $users['verifyCode'];

		$userModel = new Users("captchaRequired");
		try
		{
			$userModel->verifyCode			= $verifyCode;
			$userModel->usr_create_platform = 1;
//$userModel->usr_country_code= 
			if (!$userModel->validate())
			{
				throw new Exception(json_encode($userModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			$contactDetails = Yii::app()->JWT->encode([["type" => $objCttVerify->type, "value" => $objCttVerify->value]]);

			Filter::parsePhoneNumber($objCttVerify->value, $code, $phone);
			$userModel->usr_country_code = $code;
			$userModel->usr_mobile		 = $phone;
			Contact::verifyOTP($objCttVerify);

			$objPage->contactVerifications[0] = $objCttVerify;
			//$otpObjectEnp						 = Yii::app()->JWT->encode($otpObj);
			$objPage->updatePostData();

			$params = [
				'userModel'	 => $userModel,
				'fullNumber' => $objCttVerify->value,
				'verifyData' => Yii::app()->JWT->encode($objCttVerify), //$contactDetails,
				'verifyotp'	 => $objCttVerify->otp,
				'verifyURL'	 => $this->getURL("users/signupOTPNew")
			];
			$view	= "signUpByOtp";
//            	$this->renderAuto("signUpByOtp", 
//                    ["otpObj" => $otpObjectEnp,
//                        "verifyData" => $verifyData,
//                        "userModel" => $userModel,
//                        'rdata'=> $this->pageRequest->getEncrptedData()]);
		}
		catch (Exception $exc)
		{
			$returnSet = ReturnSet::setException($exc);
			ReturnSet::renderJSONException($exc);
			$params	   = [
				'verifyData' => $contactDetails,
				'userModel'	 => $userModel,
				'verifyURL'	 => $this->getURL("users/captchaVerifyNew")
			];
			$view	   = "captchaVerify";
		}

		$this->renderAuto($view, $params, false, true);
	}

	public function actionDeactive()
	{
		$rData = Yii::app()->request->getParam("rdata");
		if (!Yii::app()->user->isGuest && $rData == '')
		{
			$userId = UserInfo::getUserId();
			goto skipAppLogin;
		}

		$rdata	 = str_replace(' ', '+', $rData);
		$jsonObj = Filter::decryptedJsonObj($rdata);
		$jwt	 = $jsonObj->jwtoken;

		if ($rData == null || $jwt == null)
		{
			Yii::app()->user->setReturnUrl(Yii::app()->request->getUrl());
			$this->redirect(array('/signin'));
		}
		$res	   = JWTokens::validateAppToken($jwt);
		$authToken = $res->token;
		/* @var $appModel AppTokens */
		$appModel  = AppTokens::model()->getByToken($authToken);
		if (!$appModel)
		{
			throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
		}


		$userId	 = Users::loginByAppmodel($appModel);
		/* @var $model Users */
		skipAppLogin:
		$model	 = Users::model()->findByPk($userId);
		$message = '';
		if (!empty($_REQUEST['Users']))
		{
			try
			{
				$userData = Yii::app()->request->getParam("Users");
				$reason	  = $userData['usr_deactivate_reason'];

				$returnSet = Users::deactivate($userId, $reason, UserInfo::TYPE_CONSUMER);

				if (!$returnSet->isSuccess())
				{
					throw new Exception(json_encode($returnSet->getMessage()), $returnSet->getErrorCode());
				}
				if (isset($appModel))
				{
					$appModel->apt_status = 0;
					$appModel->save();
				}
				$message = "User deleted successfully";
				Yii::app()->user->logout();
				$this->redirect(['users/deactiveV1', "userId" => $userId, 'message' => $message]);
			}
			catch (Exception $ex)
			{
				$model->addError("usr_deactivate_reason", $ex->getMessage());
			}
		}

		$this->renderPartial('deactiveUser', ['data' => $model, 'model' => $model, 'message' => $message]);
	}

	public function actionPartnerDeactive()
	{
		$this->layout = 'head';
		$rData		  = Yii::app()->request->getParam("rdata");
		if (!Yii::app()->user->isGuest && $rData == '')
		{
			$userId = UserInfo::getUserId();
			goto skipAppLogin;
		}

		$rdata	 = str_replace(' ', '+', $rData);
		$jsonObj = Filter::decryptedJsonObj($rdata);
		$jwt	 = $jsonObj->jwtoken;

		if ($rData == null || $jwt == null)
		{
			Yii::app()->user->setReturnUrl(Yii::app()->request->getUrl());
			$this->redirect(array('/signin'));
		}
		$res	   = JWTokens::validateAppToken($jwt);
		$authToken = $res->token;
		/* @var $appModel AppTokens */
		$appModel  = AppTokens::model()->getByToken($authToken);
		if (!$appModel)
		{
			throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
		}

		$userId = Users::loginByAppmodel($appModel);
		/* @var $model Users */
		skipAppLogin:

		try
		{
			$model = Users::model()->findByPk($userId);
			if (!$model)
			{
				$model = new Users();
				$model->addError("bkg_id", "User does not exist or already deactivated");
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$message	  = '';
			$errorMessage = null;
			if (Yii::app()->request->isAjaxRequest)
			{
				$userData  = Yii::app()->request->getParam("Users");
				$reason	   = $userData['usr_deactivate_reason'];
				$returnSet = Users::deactivate($userId, $reason, UserInfo::TYPE_VENDOR);
				echo json_encode($returnSet);
				if ($returnSet->getStatus() == true)
				{
					Yii::app()->user->logout();
				}
				Yii::app()->end();
			}
		}
		catch (Exception $ex)
		{

			if ($model->hasErrors())
			{
				foreach ($model->getErrors() as $attribute => $errors)
				{
					$result[CHtml::activeId($model, $attribute)] = $errors;
				}
			}
			$errorMessage = $ex->getMessage();
			if (Yii::app()->request->isAjaxRequest)
			{
				$model->addError("usr_deactivate_reason", $ex->getMessage());
				Yii::app()->end();
			}
		}

		if ($model != "")
		{
			$this->renderPartial('deactivePartner', ['data' => $model, 'model' => $model, 'message' => $message, 'error' => $errorMessage]);
		}
	}

	public function actionPartnerDeactiveV1()
	{
		$entityId	= Yii::app()->request->getParam("entityId");
		$entityType = Yii::app()->request->getParam("entityType");
		$message	= Yii::app()->request->getParam("message");

		$model = Users::model()->findByPk($userId);

		$this->renderPartial('deactiveUserV1', ['data' => $model, 'message' => $message]);
	}

	public function actionDeactiveV1()
	{
		$userId	 = Yii::app()->request->getParam("userId");
		$message = Yii::app()->request->getParam("message");

		$model = Users::model()->findByPk($userId);

		$this->renderPartial('deactiveUserV1', ['data' => $model, 'message' => $message]);
	}

	public function actionCreateOTPObj()
	{
		$request			 = Yii::app()->request;
		$type				 = $request->getParam('existContactType');
		$signUpObj			 = $request->getParam('signUpDt');
		$newContactComponent = $request->getParam('otpObj');
		$notContinueWidExist = $request->getParam('notContinueWidExist');
		$obj				 = json_decode($signUpObj);

		$this->pageRequest = BookFormRequest::createInstance();

		// $verifyData          = Yii::app()->request->getParam("verifyData");

		$objPage			= $this->pageRequest;
		$objSignUp->profile = $obj->profile;

		$userModel					 = new Users();
		$userModel->usr_name		 = $objSignUp->profile->firstName;
		$userModel->usr_lname		 = $objSignUp->profile->lastName;
		$userModel->usr_email		 = $objSignUp->profile->primaryEmail->value;
		$userModel->usr_country_code = $objSignUp->profile->primaryContact->code;
		$userModel->usr_mobile		 = $objSignUp->profile->primaryContact->number;
		try
		{
			$returnSet	   = new ReturnSet();
			$arrVerifyData = [];
			if ($notContinueWidExist == 1)
			{
				$otpObjectEnp	= Yii::app()->JWT->encode($objContact);
				$existingOTPobj = json_decode($newContactComponent);

				$objContact				  = $this->pageRequest->getContact($existingOTPobj->type, $existingOTPobj->value);
				$objContact->otp		  = $existingOTPobj->otp;
				$objContact->isSendSMS	  = $existingOTPobj->isSendSMS;
				$objContact->otpValidTill = $existingOTPobj->otpValidTill;
				$objContact->isSendSMS	  = $existingOTPobj->isSendSMS;
				$otpObjectEnp			  = Yii::app()->JWT->encode($objContact);
				// $arrVerifyData       = ["otpValidTill" => $existingOTPobj->otpValidTill,"type" => $existingOTPobj->type, "value" => $existingOTPobj->value, "isSendSMS" => $existingOTPobj->isSendSMS, 'otp' => $existingOTPobj->otp];
				$this->renderAuto("signUpByOtp", ['notContinueWidExist' => $notContinueWidExist, "otpType" => $existingOTPobj->type, "otp" => $existingOTPobj->otp, "otpObj" => $otpObjectEnp, "verifyData" => Yii::app()->JWT->encode($request->getParam('verifyData')), "userModel" => $userModel, 'rdata' => $this->pageRequest->getEncrptedData()]);
				Yii::app()->end();
			}
			else
			{
				if ($type == 1)
				{
					//Logger::create("UsersController:: not isEmailOrPhone type 1", CLogger::LEVEL_INFO);
					$objEmailContact	 = $this->pageRequest->getContact(1, $objSignUp->profile->primaryEmail->value);
					Contact::verifyOTP($objEmailContact);
					$arrVerifyData		 = ["type" => $objEmailContact->type, "value" => $objEmailContact->value, 'otp' => $objEmailContact->otp];
					$otpObj				 = $objEmailContact;
					$userModel->username = $objSignUp->profile->primaryEmail->value;
					$objPage->updatePostData();
				}
				else
				{
					//Logger::create("UsersController:: not isEmailOrPhone type else", CLogger::LEVEL_INFO);
					$phoneNo		 = "+" . $objSignUp->profile->primaryContact->code . $objSignUp->profile->primaryContact->number;
					$objPhoneContact = $this->pageRequest->getContact(2, $phoneNo);

					//$userModel->scenario = 'captchaRequired';
					Filter::parsePhoneNumber($phoneNo, $code, $number);
					$canSendSMS = ContactPhone::checkTosendSMS($code, $number, SmsLog::SMS_LOGIN_REGISTER);
					if ($canSendSMS == false && $code != 91)
					{
						throw new Exception("International sms limit over.", ReturnSet::ERROR_NO_RECORDS_FOUND);
					}

					Contact::verifyOTP($objPhoneContact, $canSendSMS);
					//  Logger::create("Contact::verifyOTP", CLogger::LEVEL_INFO);
					$arrVerifyData		 = ["type" => $objPhoneContact->type, "value" => $objPhoneContact->value, "isSendSMS" => $objPhoneContact->isSendSMS, 'otp' => $objPhoneContact->otp];
					$otpObj				 = $objPhoneContact;
					$userModel->username = $phoneNo;
					$objPage->updatePostData();
				}
			}

//            else{
//                 $vd = json_decode($request->getParam('verifyData'));
//                $cancelType = $vd->type;
//                $userModel->username = $vd->value;
//                if($cancelType == 1)
//                {
//                     $userModel->usr_email  = $vd->value;
//                }else{
//                    $userModel->usr_mobile  = $vd->value;
//                }
//                $this->renderAuto("signUpByOtp", ["denyToMergeContact" => 1,"verifyData" => $request->getParam('verifyData'), "userModel" => $userModel, 'rdata'=> $this->pageRequest->getEncrptedData()]);
//			Yii::app()->end();
//                $this->renderAuto("signUpByOtp", ["denyToMergeContact" => 1,"verifyData" => $request->getParam('verifyData'), "userModel" => $userModel, 'rdata'=> $this->pageRequest->getEncrptedData()]);
//			Yii::app()->end();
//            }


			$this->pageRequest->updatePostData();
			$otpObjectEnp = Yii::app()->JWT->encode($otpObj);
			$verifyData	  = Yii::app()->JWT->encode([$arrVerifyData]);

			$userModel->usr_country_code = $objSignUp->profile->primaryContact->code;
			$userModel->usr_mobile		 = $objSignUp->profile->primaryContact->number;
			$userModel->usr_email		 = $objSignUp->profile->primaryEmail->value;
			$userModel->setScenario("userLogin");
			if ($userModel->validate())
			{
				$this->renderAuto("signUpByOtp", ["newContactComponent" => $newContactComponent, "verifyData" => $verifyData, "otpObj" => $otpObjectEnp, "userModel" => $userModel, 'rdata' => $this->pageRequest->getEncrptedData()]);
				Yii::app()->end();
			}

			$returnSet->setData(
					[
						'userName'			  => $objEmailContact->value,
						'otpObject'			  => $otpObjectEnp,
						//'isCaptchaVerify'	 => $isCaptchaVerify,
						'rdata'				  => $this->pageRequest->getEncrptedData(),
						'userNameCode'		  => $objSignUp->profile->primaryContact->code,
						'userNamePhone'		  => $objSignUp->profile->primaryContact->number,
						'isNewUser'			  => 1,
						'verifyData'		  => $verifyData,
						'verifyURL'			  => $verifyURL,
						// 'otpPh'               => ($objPhoneContact->otp != '') ? ($objPhoneContact->otp) : '',
						//  'otpEml'              => ($objEmailContact->otp != '') ? ($objEmailContact->otp) : '',
						'userType'			  => $type,
						'newContactComponent' => $newContactComponent
			]);

			$returnSet->setStatus(true);
		}
		catch (Exception $exc)
		{
			Logger::create("Exception:: 4911 " . $exc->getMessage(), CLogger::LEVEL_INFO);
			$returnSet = ReturnSet::setException($exc);
		}
		echo json_encode($returnSet);
		Yii::app()->end();
	}

	public function actionResendOtpForForgotPassword()
	{
		//VisitorTrack::track(CJSON::encode($_REQUEST), Filter::method());

		$this->pageTitle = "Resend OTP";
		$returnset		 = new ReturnSet();
		$request		 = Yii::app()->request;

		//$otpValidTill  = $request->getParam('otpValidTill');
		$verifyData	   = $request->getParam('verifyData');
		$arrVerifyData = Yii::app()->JWT->decode($verifyData);
		try
		{
			$request = Yii::app()->request;
			if ($this->pageRequest == null)
			{
				$rData			   = Yii::app()->request->getParam("rdata");
				$this->pageRequest = BookFormRequest::createInstance($rData);
			}
			$objPage = $this->pageRequest;
			//  $att          = BookFormRequest::decryptData($rData);
			//  $att          = BookFormRequest::decryptData($arrVerifyData);
			//  $objCttVerify = $att->contactVerifications[0];

			foreach ($arrVerifyData as $data)
			{
				$objCttVerify = $objPage->getContact($data->type, $data->value);

				if ($objCttVerify->otpRetry >= 3)
				{
					throw new Exception("Time exceed you can send it later", ReturnSet::ERROR_FAILED);
				}
//                if ($otpValidTill > time())
//                {
//                    throw new Exception("OTP not send", ReturnSet::ERROR_FAILED);
//                }

				if ($data->type == 1)
				{
					// $objEmailContact = $this->pageRequest->getContact($isEmailOrPhone['type'], $isEmailOrPhone['value']);
					$objEmailContact = Contact::verifyOTP($objCttVerify, true, null, false);

					//  Contact::verifyOTP($objEmailContact, true, null, false);
					$arrVerifyData	= ["type"		   => $objEmailContact->type,
						"value"		   => $objEmailContact->value,
						'otp'		   => $objEmailContact->otp, 'otpValidTill' => $objEmailContact->otpValidTill, 'otpLastSent'  => $objEmailContact->otpLastSent];
					$otpObj			= $objEmailContact;
					$arrTime		= ['otpValidTill' => $objEmailContact->otpValidTill,
						'otpLastSent'  => $objEmailContact->otpLastSent,
						"type"		   => $objEmailContact->type,
						"value"		   => $objEmailContact->value];
					$email			= $objEmailContact->value;
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

					Filter::parsePhoneNumber($objCttVerify->value, $code, $number);
					$canSendSMS		 = ContactPhone::checkTosendSMS($code, $number, SmsLog::SMS_FORGET_PASSWORD);
					$smstextType	 = "webOTP";
					$smsLogType		 = SmsLog::SMS_FORGET_PASSWORD;
					$objPhoneContact = Contact::verifyOTP($objCttVerify, $canSendSMS, $smstextType, false, $smsLogType);
					$arrVerifyData	 = ["type"		   => $objPhoneContact->type, "value"		   => $objPhoneContact->value,
						"isSendSMS"	   => $objPhoneContact->isSendSMS, 'otp'		   => $objPhoneContact->otp, 'otpValidTill' => $objPhoneContact->otpValidTill, 'otpLastSent'  => $objPhoneContact->otpLastSent];
					$arrTime		 = ['otpValidTill' => $objPhoneContact->otpValidTill,
						'otpLastSent'  => $objPhoneContact->otpLastSent,
						"type"		   => $objEmailContact->type,
						"value"		   => $objEmailContact->value];
					$otpObj			 = $objPhoneContact;
					$isSend			 = $objPhoneContact->isSendSMS;
				}
			}
			$contactDetails = Yii::app()->JWT->encode([$arrVerifyData]);
			$objPage->updatePostData();
			Filter::removeNull($objPage);
//                =================
//                
//            
//        echo CJSON::encode(array('status' => $isSend,'verifyData'=>$contactDetails,
//            'verifyValidity'=>$arrTime,
//            'rdata'=> $this->pageRequest->getEncrptedData(),'typeUsr'=>$typeUsr,'typeID'=>$isEmailOrPhone['value']));
//      
//                
//                ================



			if ($objPage)
			{
				$returnset->setStatus(true);
				$returnset->setData(['status'		 => $isSend,
					'verifyData'	 => $contactDetails,
					'verifyValidity' => $arrTime,
					'rdata'			 => $this->pageRequest->getEncrptedData(),
					'typeUsr'		 => $typeUsr,
					'typeID'		 => $isEmailOrPhone['value']]);
			}
		}
		catch (Exception $e)
		{
			$returnset = ReturnSet::setException($e);
		}
		echo json_encode($returnset);
		Yii::app()->end();
	}

	public function actionCaptchaskiplogin()
	{
		$verifyCode = Yii::app()->request->getParam('Users')['verifyCode'];
		$userModel	= new Users();
		if ($verifyCode != '')
		{
			$userModel = new Users("captchaRequired");
			try
			{
				$userModel->verifyCode			= $verifyCode;
				$userModel->usr_create_platform = 1;
				if (!$userModel->validate())
				{
					throw new Exception(json_encode($userModel->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
				echo json_encode(['success' => true]);
				Yii::app()->end();
			}
			catch (Exception $e)
			{
				echo json_encode(['success' => false, 'error' => 'Captcha does not match!']);
				Yii::app()->end();
			}
		}
		else
		{
			$sessSkipLoginCnt = Yii::app()->session['_gz_skip_login_count'];
			$skipLoginCntVal  = 1;
			if ($sessSkipLoginCnt > 0)
			{
				if (Yii::app()->user->getState('skipLoginSessionTimeout') < time())
				{
					$this->setInitialSkipLoginCnt();
				}
				else
				{
					$sessSkipLoginCnt							= $sessSkipLoginCnt + 1;
					Yii::app()->session['_gz_skip_login_count'] = $sessSkipLoginCnt;
					$skipLoginCntVal							= $sessSkipLoginCnt;
				}
			}
			else
			{
				$this->setInitialSkipLoginCnt();
			}
			$skipLoginLimit = json_decode(Config::get('quote.guest'))->captchaLimit;

			if ($skipLoginCntVal > $skipLoginLimit)
			{
				goto renderCaptcha;
			}
			echo json_encode(['success' => true, 'allowQuote' => true]);
			Yii::app()->end();
		}
		renderCaptcha:
		$this->renderAuto('captcha', ['userModel' => $userModel], false, true);
	}

	public function setInitialSkipLoginCnt()
	{
		$resetTime									= json_decode(Config::get('quote.guest'))->resetTime;
		Yii::app()->user->setState('skipLoginSessionTimeout', time() + ($resetTime * 60));
		Yii::app()->session['_gz_skip_login_count'] = 1;
	}
}
