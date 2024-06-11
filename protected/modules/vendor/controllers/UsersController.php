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
	public $token		 = '';

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
				'actions'	 => array('signin', 'VendorAuth',
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
			$ri	 = array( '/vendorsignin',  '/social_login', '/signin', '/signin1',
				 '/validateversion', '/devicetokenfcm', 'checkvalidateversion',
				'/check_tnc', '/joining',    '/tireUpgrade',
				 '/joining1', '/forgotpass', '/newpassword', '/social_linking', '/reg_user_linking', '/vendorlogout',
				  '/penaltyrate', '/getAgentCommision', '/validateApp', '/regUserLinking', '/socialLinking', "/socialLink", "/validateUser", );
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
		 *  Old Service :  social_login ( METHOD POST )
		 *  New Service :  signin ( METHOD POST )
		 */
		$this->onRest('req.post.signin.render', function () {
			return $this->renderJSON($this->signIn());
		});
		/*
		 * old service : reg_user_linking  (  method : POST  )
		 * new service : regUserLinking	 (  method : POST )
		 */
		$this->onRest('req.post.regUserLinking.render', function () {
			return $this->renderJSON($this->regUserLinking());
		});

		/*		 * Old service : update_tnc  (  method : POST  )
		 * new service : updateTncDetails	 (  method : POST )
		 */
		$this->onRest('req.post.updateTncDetails.render', function () {
			return $this->renderJSON($this->updateTncDetails());
		});

		/*
		 * old service : regUserLinking  (  method : POST  )
		 * new service : validateUser	 (  method : POST )
		 */
		$this->onRest('req.post.validateUser.render', function () {
			return $this->renderJSON($this->validateUser());
		});

		/*
		 * old service : update_tnc  (  method : POST  )
		 * new service : updateTnc	 (  method : POST )
		 */
		$this->onRest('req.post.updateTnc.render', function () {
			return $this->renderJSON($this->updateTnc());
		});

		$this->onRest('req.post.socialLinking.render', function () {
			return $this->renderJSON($this->socialLinking());
		});

		$this->onRest('req.post.socialLink.render', function () {
			return $this->renderJSON($this->socialLink());
		});

		/*
		 * old service : validate ( METHOD POST )  and validateversion ( METHOD POST )  
		 * merged to 
		 * new service : validateApp ( METHOD POST )
		 */
		$this->onRest('req.post.validateApp.render', function () {
			return $this->renderJSON($this->validateApp());
		});
		/*
		 * old service : devicetokenfcm  (  method : GET  )
		 * new service : registerFcm	 (  method : POST )
		 */
		$this->onRest('req.post.registerFcm.render', function () {
			return $this->renderJSON($this->registerToFcm());
		});
		/*
		 * old service : agreementInfo  (  method : GET  )
		 * new service : agreementInformation	 (  method : GET )
		 */
		$this->onRest('req.get.agreementInformation.render', function () {
			return $this->renderJSON($this->agreementInformation());
		});
		/**
		 * old service  :editAgreementInformation  
		 * new Service  :editAgreementInformation
		 */
		$this->onRest('req.post.editAgreementInformation.render', function () {
			return $this->renderJSON($this->editAgreementInfoNew());
		});

		/**
		 * old service  :infoDetails  
		 * new Service  :vendorInfoDetails
		 */
		$this->onRest('req.get.vendorInfoDetails.render', function () {
			return $this->renderJSON($this->vendorInfoDetails());
		});

		$this->onRest('req.post.validateJWT.render', function () {
			return $this->renderJSON($this->validateJWT());
		});
		$this->onRest('req.post.generateJWT.render', function () {
			return $this->renderJSON($this->generateJWT());
		});
		$this->onRest('req.post.vendorsignin.render', function () {
			Logger::create('5 validateversion ');
			//$obj	 = UserInfo::getInstance();
			//$userId	 = Yii::app()->user->getId();
			$result = $this->loginvendor();

			if ($result == true)
			{
				$success	 = true;
				$userId		 = Yii::app()->user->getId();
				$vendorId	 = Yii::app()->user->getEntityID();
				$sessionId	 = Yii::app()->getSession()->getSessionId();
				$userModel	 = Vendors::model()->resetScope()->find('vnd_id=:id AND vnd_active IN(1,3)', ['id' => $vendorId]);
				$userData	 = Users::model()->findByPk($userId);
				$tmodel		 = Terms::model()->getText(5);
				$tnc_check	 = false;
				$new_tnc_id	 = $tmodel->tnc_id;
				if ($userModel->vnd_tnc_id == $tmodel->tnc_id)
				{
					$tnc_check	 = true;
					$new_tnc_id	 = '';
				}
				$vndPhone	 = ContactPhone::model()->getContactPhoneById($userModel->vnd_contact_id);
				$vndEmail	 = ContactEmail::model()->getContactEmailById($userModel->vnd_contact_id);
				$userName	 = $userModel->vnd_name;
				$userPhone	 = $vndPhone;
				$userEmail	 = $vndEmail;
				$ownerName	 = $userModel->getName();
				$is_approve	 = Vendors::model()->isApproved($userModel->vnd_id, 0);
				$is_message	 = Vendors::model()->isApproved($userModel->vnd_id, 1);

				//$documentUpload	 = VendorDocs::model()->checkDocumentUpload($userModel->vnd_id);
				$documentUpload	 = Document::model()->checkDocumentUpload($userModel->vnd_id);
				$agreementUpload = VendorAgreement::model()->findAgreementStatusByVndId($userModel->vnd_id);
				$versionCheck	 = VendorAgreement::model()->checkVersionStatusByVndId($userModel->vnd_id);
				$rate			 = VendorStats::fetchRating($userId);
				$msg			 = "Login Successful";

				$sessionId	 = Yii::app()->getSession()->getSessionId();
				$session_arr = explode(",", $sessionId);
				//$sessionId = $session_arr[0];
				if (count($session_arr) > 1)
				{
					$sessionId = $session_arr[0];
				}
				else
				{
					$sessionId = Yii::app()->getSession()->getSessionId();
				}
				// show contact details
				$vndContact				 = Contact::model()->findByPk($userModel->vnd_contact_id);
				//print_r($vndContact);
				$bussiness_status_type	 = ($vndContact['ctt_user_type'] == '1' ? '0' : '1');
				if ($bussiness_status_type == 0)
				{
					$vendorLavel = $vndContact['ctt_first_name'] . ' ' . $vndContact['ctt_last_name'];
				}
				else
				{
					$vendorLavel = $vndContact['ctt_business_name'];
				}
			}
			else
			{
				$success = false;
				$msg	 = "Invalid Username/Password";
			}

			//$vondorId		 = Yii::app()->user->getId();

			$activeVersion = Config::get("Version.Android.vendor"); // Yii::app()->params['versionCheck']['vendor'];
			// echo  $is_app = trim($is_approve);


			$response = ['success'		 => $success,
				'userPhone'		 => $userPhone,
				'is_approve'	 => $is_approve,
				'message'		 => $msg,
				'sessionId'		 => $sessionId,
				'user_id'		 => $userId,
				"vnd_id"		 => $vendorId,
				'userEmail'		 => $userEmail,
				'userName'		 => $userName,
				'tnc_check'		 => $tnc_check,
				'new_tnc_id'	 => $new_tnc_id,
				'versionCheck'	 => $versionCheck,
				'version'		 => $activeVersion,
				'is_message'	 => $is_message,
				'rating'		 => $rate,
				'vendor_level'	 => $vendorLavel
			];

			Logger::create("Response params :: " . json_decode($response), CLogger::LEVEL_INFO);
			return CJSON::encode($response);
//    return $this->renderJSON(['success' => $success, 'message' => $msg, 'sessionId' => $sessionId, 'userId' => $userId, 'userName' => '']);
		});

		/*
		 * @deprecated
		 */
		//NEW social_linking
		$this->onRest('req.post.social_linking1.render', function () {
			$url				 = "http://localhost:82/vendor/users/social_linking ";
			Logger::create('Request URL social_linking : ' . $url, CLogger::LEVEL_TRACE);
			$process_sync_data	 = Yii::app()->request->getParam('data');
			Logger::create('Request DATA social_linking : ' . $process_sync_data, CLogger::LEVEL_TRACE);
			//$process_sync_data   = '{"vnd_id":"23451","data":"{\"id\":\"1013949392994276306999988888333177\",\"email\":\"testgozo19011111@gmail.com\",\"familyName\":\"test\",\"givenName\":\"gozo\",\"displayName\":\"gozo test\",\"grantedScopes\":\"[https:\\\/\\\/www.googleapis.com\\\/auth\\\/userinfo.profile, https:\\\/\\\/www.googleapis.com\\\/auth\\\/userinfo.email, openid, profile, email]\"}","provider":"Google"}';					
			//$process_sync_data = '{"vnd_id":"34158","data":"{\"id\":\"3456754344\",\"email\":\"sudhangshu@aaocab.in\",\"familyName\":\"test\",\"givenName\":\"gozo\",\"displayName\":\"gozo test\",\"grantedScopes\":\"[https:\\\/\\\/www.googleapis.com\\\/auth\\\/userinfo.profile, https:\\\/\\\/www.googleapis.com\\\/auth\\\/userinfo.email, openid, profile, email]\"}","provider":"Facebook"}';

			$data1			 = CJSON::decode($process_sync_data, true);
			$vndId			 = $data1['vnd_id'];
			$appUserModel	 = Vendors::model()->findByPk($vndId);
			$vnd_user_id	 = $appUserModel->vnd_user_id;
			$provider		 = $data1['provider'];
			$sync_Data		 = $data1['data'];
			$flag1			 = 'vendor-app';
			$vnd_contact_id	 = $appUserModel->vnd_contact_id;

			if ($vnd_user_id != '')
			{
				$response = Users::model()->linkExistingAppUser($vnd_user_id, $provider, $sync_Data, $flag1, $vnd_contact_id);
			}
			else
			{
				$result = Users::model()->linkAppUser($vnd_user_id, '', '1', $provider, $sync_Data, $flag1);

				if ($result['success'])
				{
					$isExistVendor = Vendors::model()->checkExistingVendor($result['user_id']);
					if ($isExistVendor > 0)
					{
						$result['success']	 = false;
						$result['msg']		 = 'This user already linked with an another vendor';
					}
					else
					{
						$appUserModel->vnd_user_id	 = $result['user_id'];
						$appUserModel->save();
						$contactEmail				 = ContactEmail::model()->findEmailIdByEmail($result['email']);
						if (count($contactEmail) == 0)
						{

							$email		 = $result['email'];
							$sql		 = "INSERT INTO contact_email (`eml_contact_id`,`eml_email_address`,`eml_is_verified`,`eml_is_primary`,`eml_active`) VALUES ('$vnd_contact_id','$email',1,0,1)";
							$resultRow	 = Yii::app()->db->createCommand($sql)->execute();
						}
					}
				}
				$response = ['success' => $result['success'], 'msg' => $result['msg']];
			}

			Logger::create("Response DATA social_linking =>" . CJSON::encode($response), CLogger::LEVEL_TRACE);

			return CJSON::encode($response);
		});

		$this->onRest('req.post.social_login.render', function () {
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			Logger::create("Request params :: " . json_encode($data1), CLogger::LEVEL_INFO);
			$provider			 = $data1['provider'];
			$processSyncdata	 = $data1['data'];
			$deviceData1		 = $data1['devicedata'];
			$result				 = Vendors::model()->socialVendorlogin($provider, $processSyncdata, $deviceData1);
			return $result;
		});

		$this->onRest('req.get.status_details.render', function () {
			$securityFlag		 = 0;
			$securityAmount		 = "";
			$outstandingFlag	 = 0;
			$outstandingMsg		 = "";
			$flag				 = 0;
			$msg				 = "";
			
			$vendorId			 = UserInfo::getEntityId();
			$obj	 = UserInfo::getInstance();
			Logger::trace("UserInfo*********" . json_encode($obj).'vendorId'.$vendorId);
		
			$userId				 = UserInfo::getUserId();
			$errors				 = '';
			$arr				 = Vendors::model()->getAllStatusByVnd($vendorId);
			Logger::trace("Data *******: " . json_encode($arr));
			$result				 = Vendors::model()->getDetails($vendorId);
			//$arr['is_doc']		 = ($arr['is_doc'] == 1 && $arr['vnd_row']['cout_doc_rejected'] == 0) ? 1 : 0;
			unset($arr['vnd_row']);
			$arr['is_message']	 = Vendors::model()->isApproved($vendorId, 1);
			$success			 = (count($arr) > 0) ? true : false;
			if ($arr['vnd_social_link'] == false)
			{

				$arr['linking_required'] = false; //true
			}

			$isGozoNow = VendorPref::getGNowNotificationStatus($vendorId);
			$jsonval = Yii::app()->request->getParam('data');
			if ($jsonval)
			{
				
				$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
				AppTokens::updateDeviceSettings($token, $jsonval);
				Logger::trace("RequestData*********" . $vendorId);
			}

			$arr['isGozoNow']		 = $isGozoNow;
			$vendorModel = Vendors::model()->findByPk($vendorId);
			$prefModel				 = $vendorModel->vendorPrefs;
			$requiredSDAmount		 = $prefModel->vnp_min_sd_req_amt;
			$statModel				 = $vendorModel->vendorStats;
			$currentSecurityAmount	 = $statModel->vrs_security_amount;
			if ($currentSecurityAmount < $requiredSDAmount)
			{
				$securityFlag	 = 1;
				$securityAmount	 = $requiredSDAmount-$currentSecurityAmount;
				$securityMsg = "Please pay your seurity amount ₹".$securityAmount ;
			}

			$outstandingBalence = $statModel->vrs_outstanding;
			$withdrawbleBalence = $statModel->vrs_withdrawable_balance;
			if ($outstandingBalence > 0 && $withdrawbleBalence < 1)
			{
				$outstandingFlag = 1;
				$outstandingMsg	.= "Please pay your outstanding balance ₹".$outstandingBalence ;
			}
		if($securityFlag ==1 || $outstandingFlag == 1)
		{
			$flag =1;
			if($securityFlag ==1 && $outstandingFlag == 1)
			{
				$message = "Please pay your seurity amount ₹$securityAmount"." and outstanding balence ₹".$outstandingBalence . " to increase the chance of winning bid.";
			}
			else
			{
			$message = $securityMsg.$outstandingMsg.' to increase the chance of winning bid.';
			}
		}
		
			
			$arr['securityFlag']=$securityFlag;
			$arr['securityAmount']=$securityAmount;
			$arr['outstandingFlag']=$flag;
			$arr['outstandingMsg']=$message;
			
			if($arr['is_car']==0 || $arr['is_driver']==0 ||$arr['is_document'] == 0 )
			{
					Logger::pushTraceLogs();
			}
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => ['success' => $success, 'message' => $arr, 'errors' => $errors, 'result' => $result]
			]);
		});

		$this->onRest('req.post.reg_user_linking.render', function () {
			$process_sync_data = Yii::app()->request->getParam('data');
			//$process_sync_data   = '{"email":"kshitijgupta00999@gmail.com","phoneno":"9898323245","userotp":""}';			

			$data1	 = CJSON::decode($process_sync_data, true);
			$email	 = $data1['email'];
			$phoneno = $data1['phoneno'];
			$userOtp = $data1['userotp'];

			//Logger::create("Request =>".$process_sync_data, CLogger::LEVEL_TRACE);
			//$userId = Users::model()->linkUserid($email,$phoneno);
			$vndId = Contact::model()->linkContactId($email, $phoneno);
			// $vndModel = Vendors::model()->findByVendorContactID($contactId);


			if (count($vndId) == 0)
			{
				$success = false;
				$message = "Not a Register Vendor";
			}
			else if (count($vndId) > 1)
			{
				$success = false;
				$message = "Already linked with other user.";
			}
			else
			{
				if ($vndId[0]['vnd_id'] != "")
				{
					if ($userOtp != "")
					{
						$linkStatus = VendorPref::model()->verifyUserLinkByOTP($vndId[0]['vnd_id'], $userOtp);
						if ($linkStatus['success'])
						{
							$success	 = true;
							$vendorId	 = $vndId[0]['vnd_id'];
						}
						else
						{
							$errors	 = $linkStatus['errors'];
							$success = false;
						}
					}
					else
					{
						$linkotp = VendorPref::model()->sendLinkOtp($vndId[0]['vnd_id']);
						if ($linkotp > 0)
						{
//							$emailModel	 = new emailWrapper();
//							$emailModel->SendLinkOtp($vndId[0]['vnd_id'], $email, $linkotp);
//
//							//whatsapplog
////							$response = WhatsappLog::attachVendorSocialAccount($vndId[0]['vnd_id'], $phoneno, $linkotp);
////							if($response['status'] == 3)
////							{
//								$smsModel	 = new smsWrapper();
//								$smsModel->sendLinkOtp($vndId[0]['vnd_id'], $phoneno, $ext, $linkotp);
////							}
							Vendors::notifyAttachVendorSocialAccount($vndId[0]['vnd_id'], $email, $phoneno, $linkotp);
							$success	 = true;
							$vendorId	 = $vndId[0]['vnd_id'];
							$message	 = "OTP send Successfully";
						}
					}
				}
				else
				{
					$success = false;
					$message = "Not a Register Vendor";
				}
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => ['success' => $success, 'vendorid' => $vendorId, 'message' => $message, 'errors' => $errors]
			]);
		});

		$this->onRest('req.post.signinold.render', function () {
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$data				 = array_filter($data1);
			$result				 = $this->login($data);
			return $this->renderJSON(['type' => 'raw', 'data' => $result]);
		});
		$this->onRest('req.post.devicetokenfcm.render', function () {

			Logger::create('6 device token fcm ', CLogger::LEVEL_TRACE);
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$data				 = array_filter($data1);
			$token				 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$appToken1			 = AppTokens::model()->find('apt_device_token = :token', array('token' => $data['apt_device_token']));
			if ($appToken1 != '')
			{
				$appToken1->apt_status = 0;
				$appToken1->update();
			}

			$appToken = AppTokens::model()->find('apt_token_id = :token', array('token' => $token));
			if (!$appToken)
			{
				$appToken = new AppTokens();
			}
			$appToken->apt_device_token	 = $data['apt_device_token'];
			$appToken->apt_device_uuid	 = $data['apt_device_uuid'];
			$appToken->scenario			 = 'fcm';
			$appToken->apt_user_type	 = 2;
			$success					 = $appToken->save();
			Yii::log("device token : " . $data['apt_device_token'], CLogger::LEVEL_INFO);
			return $this->renderJSON(['type' => 'raw', 'data' => ['success' => $success, 'errors' => $appToken->getErrors()]]);
		});
		/* ========================================================================== */

		$this->onRest('req.post.vendorchangepassword.render', function () {
			//check vendor authentication services 

			Logger::create('7 vendor change password ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{
				$vendorId	 = UserInfo::getEntityId();
				$userId		 = UserInfo::getUserId();
				$status		 = $this->changeVendorPassword();
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $status['status'],
					'message'	 => $status['message']
					, 'data'		 => Yii::app()->getSession()->getSessionId()
				),
			]);
		});

		/* ========================================================================= */
		$this->onRest('req.get.render', function () {
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$data				 = array_filter($data1);
			$result				 = $this->changePassword($data);
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $result,
			]);
		});
		/* ========================================================================= */

		$this->onRest('req.post.vendorlogout.render', function () {

			Logger::create('8 vendor logout ', CLogger::LEVEL_TRACE);

			//$device_token = Yii::app()->request->getParam('apt_device_token');
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$device_token		 = $data1['apt_device_token'];
			$token				 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result				 = Vendors::model()->authoriseVendor($token);

			if ($result == true)
			{
				$token = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);

				$applogout = AppTokens::model()->find('apt_token_id = :token', array('token' => $token));

				if ($applogout)
				{
					$applogout->apt_status	 = 0;
					$applogout->apt_logout	 = new CDbExpression('NOW()');
					$logout				 = $applogout->save();
					Yii::app()->user->logout();
				}
				if ($device_token != '' && $device_token != NULL)
				{
					$applogout1 = AppTokens::model()->findAll('apt_device_token=:token_device', array('token_device' => $device_token));

					foreach ($applogout1 as $value)
					{
						if ($value)
						{
							$value->apt_status	 = 0;
							$value->apt_logout	 = new CDbExpression('NOW()');
							$logout				 = $value->save();
						}
					}
				}
			}

			if ($logout)
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

		/* ===================================================================================== */
		$this->onRest('req.post.logout.render', function () {
			$userId				 = Yii::app()->user->getId();
			//$device_token	 = Yii::app()->request->getParam('apt_device_token');
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$device_token		 = $data1['apt_device_token'];
			$sessionId			 = Yii::app()->getSession()->getSessionId();
			$applogout			 = AppTokens::model()->find('apt_token_id = :token || apt_user_id = :userid', array('token' => $sessionId, 'userid' => $userId));
			if ($applogout)
			{
				$applogout->apt_status	 = 0;
				$applogout->apt_logout	 = new CDbExpression('NOW()');
				$logout					 = $applogout->save();
				Yii::app()->user->logout();
			}
			if ($device_token != '' && $device_token != NULL)
			{
				$applogout1 = AppTokens::model()->findAll('apt_device_token=:token_device', array('token_device' => $device_token));
				foreach ($applogout1 as $value)
				{
					if ($value)
					{
						$value->apt_status	 = 0;
						$value->apt_logout	 = new CDbExpression('NOW()');
						$logout				 = $value->save();
					}
				}
			}


			if ($logout)
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
		/* ===================================================================================== */

		$this->onRest('req.post.validate.render', function () {
			//
			$headerStringValue = $_SERVER['HTTP_X-REST-TOKEN'];

			$success = false;
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);

			if ($result == true)
			{
				$vendorId			 = UserInfo::getEntityId();
				$userId				 = UserInfo::getUserId();
				//Logger::create('VENDORID:' . $vendorId, CLogger::LEVEL_TRACE);
				$process_sync_data	 = Yii::app()->request->getParam('data');

				Logger::trace("Request for validateversion : " . $process_sync_data);

				//$process_sync_data = '{"apt_os_version":21,"apt_ip_address":"","apt_token_id":"8p3hFJ1tFDGcGs4pJ,a0z1"}';
				$data1			 = CJSON::decode($process_sync_data, true);
				$data			 = array_filter($data1);
				$id				 = $vendorId;
				$tokenData		 = AppTokens::model()->getByTokenId($data['apt_token_id'], 2);
				$documentUpload	 = $agreementUpload = $versionCheck	 = 0;
				$result			 = $this->getValidationApp($data, $id);
				//Logger::create('RESULT2' . $result['success'], CLogger::LEVEL_TRACE);
				if ($result['success'] == true)
				{
					/* @var $model Vendors */
					$model				 = Vendors::model()->findByPk($vendorId);
					$userModel			 = Users::model()->findByPk($userId);
					$contactDetails		 = Contact::model()->getContactDetails($model->vnd_contact_id);
					$vndUserName		 = $model->vndUser->usr_name . ' ' . $model->vndUser->usr_lname;
					$vndContactPerson	 = ($contactDetails['ctt_user_type'] == 1) ? $contactDetails['ctt_first_name'] . ' ' . $contactDetails['ctt_last_name'] : $contactDetails['ctt_business_name'];
					$vndCompany			 = ($contactDetails['ctt_user_type'] == 2) ? $contactDetails['ctt_business_name'] : '';

					if ($contactDetails['ctt_owner_id'] > 0)
					{
						$contModel	 = Contact::model()->findByPk($contactDetails['ctt_owner_id']);
						$vndOwner	 = $contModel->contactOwner->ctt_first_name . ' ' . $contModel->contactOwner->ctt_last_name;
					}
					$data			 = array('vnd_id'				 => $model->vnd_id,
						'user_id'				 => $userId,
						'vnd_name'				 => $model->vnd_name,
						'vnd_username'			 => $vndUserName,
						'vnd_phone'				 => $contactDetails['phn_phone_no'],
						'vnd_phone2'			 => '',
						'vnd_land_phone'		 => '',
						'vnd_land_phone2'		 => '',
						'vnd_email'				 => $contactDetails['eml_email_address'],
						'vnd_email2'			 => '',
						'vnd_contact_person'	 => $vndContactPerson,
						'vnd_preferred_time'	 => $model->vendorPrefs->vnp_preferred_time_slots,
						'vnd_phone_country_code' => $contactDetails['phn_phone_country_code'],
						'vnd_contact_number'	 => '',
						'vnd_alt_contact_number' => '',
						'vnd_address'			 => $contactDetails['ctt_address'],
						'vnd_route_served'		 => '',
						'vnd_company'			 => $vndCompany,
						'isActive'				 => (int) $model->vnd_active,
						'vnd_owner'				 => $vndOwner);
					$arr			 = Vendors::model()->getAllStatusByVnd($model->vnd_id);
					//$documentUpload     = VendorDocs::model()->checkDocumentUpload($model->vnd_id);
					//$agreementUpload = VendorAgreement::model()->findAgreementStatusByVndId($model->vnd_id);
					$versionCheck	 = VendorAgreement::model()->checkVersionStatusByVndId($model->vnd_id);
					$is_approve		 = Vendors::model()->isApproved($model->vnd_id, 0);
					$is_message		 = Vendors::model()->isApproved($model->vnd_id, 1);
					//$blockReason   = ($model->vnd_active == 2)?VendorsLog::getBlockReason($model->vnd_id):'';
					$blockReason	 = "Your account is blocked. Please contact the vendor team for any support";
				}
			}
			else
			{
				$result['success'] = $success;
			}
			Logger::trace("validate Response****************" . json_encode($data));
			//Logger::create('SUCCESSS'.$result['success'].'DATA'. json_encode($data), CLogger::LEVEL_TRACE);
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'			 => $result['success'],
					'message'			 => $result['message'],
					'active'			 => $result['active'],
					'data'				 => $data,
					'documentUpload'	 => $arr['is_doc'],
					'agreementUpload'	 => $arr['is_agmt'],
					'versionCheck'		 => $versionCheck,
					'is_approve'		 => $is_approve | 0,
					'is_message'		 => $is_message,
					'blockReason'		 => $blockReason
				)
			]);
		});

		$this->onRest('req.post.validateversion.render', function () {
			$process_sync_data = Yii::app()->request->getParam('data');

			//Logger::create("Request validateversion for vendor :  " . $process_sync_data);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			Logger::trace("Validateversion Request*********" . $process_sync_data);
			$data1			 = CJSON::decode($process_sync_data, true);
			$data			 = array_filter($data1);
			$activeVersion	 = Config::get("Version.Android.vendor"); 
			
			$promo =0;
			$forceUpdate =1;
			$storeUrl ="";
			$content ="";
			if ($result == true)
			{
				$userId	 = UserInfo::getUserId();
				$vendorId = UserInfo::getEntityId();
				if($userId >0)
				{
					//$dcoFlag = Vendors::isDco($userId);
					$model				 = Vendors::model()->findByPk($vendorId);
					$promo =1;
					$content ="Dear Operator,\nThank you for serving Gozo customers in the past. We have got a great news for you. There is a dedicated PARTNER+ app that you can download using the link below which is developed just for you. There are many bookings that are waiting to be served. Please install the new app and start serving bookings.";
					$storeUrl = Config::get("dco.app.download.url");
					$dcoFlag = $model->vnd_is_dco;	
					$contactData = ContactProfile::getEntitybyUserId($userId);
					if ($contactData['cr_is_vendor'] > 0 && $contactData['cr_is_driver']<1)
					{
						$onlyVendor =1;
					}
					if($dcoFlag == 1 || $onlyVendor == 1 )
					{
						$forceUpdate =1; // temporary modification
					}
					
				}
			}
			
		//Yii::app()->params['versionCheck']['vendor'];

			if (version_compare($data['apt_apk_version'], $activeVersion) < 0)
			{
				$active			 = 0;
				$success		 = false;
				$msg			 = "Invalid Version";
				$sessioncheck	 = Yii::app()->params['vendorappsessioncheck'];
			}
			else
			{
				$active			 = 1;
				$success		 = true;
				$msg			 = "Valid Version";
				$sessioncheck	 = '';
			}
			
			$url = Yii::app()->params['fullBaseURL'];
			$result = array('active' => $active, 'success' => $success, 'message' => $msg, 'sessioncheck' => $sessioncheck);
			Logger::trace("Validateversion*************" . json_encode($result));
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $result['success'],
					'message'		 => $result['message'],
					'active'		 => $result['active'],
					'sessioncheck'	 => $result['sessioncheck'],
					'version'		 => $activeVersion,
					'promoDco'       => (int)$promo,
					'promoContent'   => $content,
					'forceUpdate'    => $forceUpdate,
					'playStoreUrl'   => $storeUrl,
					'promoContent'   => $content
				)
			]);
		});
		$this->onRest('req.post.checkvalidateversion.render', function () {
			$process_sync_data = Yii::app()->request->getParam('data');

			//Logger::create("Request validateversion for vendor :  " . $process_sync_data);
			Logger::trace("Validateversion Request*********" . $process_sync_data);
			$data1			 = CJSON::decode($process_sync_data, true);
			$data			 = array_filter($data1);
			$activeVersion	 = Config::get("Version.Android.vendor"); //Yii::app()->params['versionCheck']['vendor'];

			if (version_compare($data['apt_apk_version'], $activeVersion) < 0)
			{
				$active			 = 0;
				$success		 = false;
				$msg			 = "Invalid Version";
				$sessioncheck	 = Yii::app()->params['vendorappsessioncheck'];
			}
			else
			{
				$active			 = 1;
				$success		 = true;
				$msg			 = "Valid Version";
				$sessioncheck	 = '';
			}
			$result = array('active' => $active, 'success' => $success, 'message' => $msg, 'sessioncheck' => $sessioncheck);
			Logger::trace("Validateversion*************" . json_encode($result));
			
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $result['success'],
					'message'		 => $result['message'],
					'active'		 => $result['active'],
					'sessioncheck'	 => $result['sessioncheck'],
					'version'		 => $activeVersion,
				)
			]);
		});

		#deprecated
		#new function updateTnc
		$this->onRest('req.post.update_tnc.render', function () {

			Logger::create('update_tnc ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{
				$vendorId			 = UserInfo::getEntityId();
				$userId				 = UserInfo::getUserId();
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$data				 = array_filter($data1);
				$userId				 = Yii::app()->user->getId();
				$result				 = Vendors::model()->updatetnc($data, $userId);
				if ($result != false)
				{
					$result = ['success' => true, 'model' => JSONUtil::convertModelToArray($result)];
				}
				else
				{
					$result = ['success' => false, 'model' => ''];
				}
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $result,
			]);
		});
		$this->onRest('req.post.getAgentCommision.render', function () {
			$returnSet	 = new ReturnSet();
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check		 = Vendors::model()->authoriseVendor($token);
			if ($check)
			{
				$returnSet->setStatus(true);
				$agtCommission	 = Yii::app()->params['vendorDriverSalesCommission'];
				$data			 = ['agt_com' => $agtCommission];
				$returnSet->setData($data);
			}
			return $this->renderJSON([
				'data' => $returnSet]);
		});

		$this->onRest('req.get.agreementinfo.render', function () {

			Logger::create('13 agreement info ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);

			if ($result == true)
			{
				$success	 = false;
				$vendorId	 = UserInfo::getEntityId();

				//$vendorId	 = Yii::app()->request->getParam('vnd_id');
				/* @var $model Vendors */
				$model = Vendors::model()->findByPk($vendorId);

				$success				 = ($model->vnd_id) ? true : false;
				$dataSet				 = $model->getAttributes();
				$dataSet				 = $model->getApiMapping($dataSet);
				$contactId				 = $model['attributes']['vnd_contact_id'];
				$vndContact				 = Contact::model()->findByPk($contactId);
				//print_r($vndContact);
				$bussiness_status_type	 = ($vndContact['ctt_user_type'] == '1' ? '0' : '1');
				
				if ($bussiness_status_type == 0)
				{
					$vendorLavel = $vndContact['ctt_first_name'] . ' ' . $vndContact['ctt_last_name'];
				}
				else
				{
					$vendorLavel = $vndContact['ctt_business_name'];
				}
				$dataSet['is_bussiness'] = $bussiness_status_type;
				$dataSet['is_bussiness'] = $bussiness_status_type;
				$dataSet['vendorLavel']	 = $vendorLavel;
				//print_r($dataSet);
				unset($dataSet['vnd_log']);
				$errors					 = [];
				$vendorDocs				 = Document::model()->findAllByVndId1($vendorId);

				if (count($vendorDocs) > 0)
				{
					if ($vendorDocs['digitalAgreementPath'] != '')
					{
						$agreementPath = $vendorDocs['digitalAgreementPath'];
					}
					else
					{
						$agreementPath = $vendorDocs['agreementPath'];
					}
					$dataSet['vnd_agreement_file_link'] = $agreementPath;

					$dataSet['vnd_voter_id_path'] = $vendorDocs['voterFontPath'];

					$dataSet['vnd_voter_id_back_path'] = $vendorDocs['voterBackPath'];

					$dataSet['vnd_aadhaar_path'] = $vendorDocs['aadherFontPath'];

					$dataSet['vnd_aadhaar_back_path'] = $vendorDocs['aadherBackPath'];

					$dataSet['vnd_pan_path'] = $vendorDocs['panFontPath'];

					$dataSet['vnd_pan_back_path'] = $vendorDocs['panBackPath'];

					$dataSet['vnd_licence_path'] = $vendorDocs['licenceFontPath'];

					$dataSet['vnd_licence_back_path'] = $vendorDocs['licenceBackPath'];

					$dataSet['vnd_memorandum_path'] = $vendorDocs['memoFontPath'];

					$dataSet['license_no'] = $vndContact['ctt_license_no'];
				}
			}
			else
			{
				$success = 'false';
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => $dataSet,
				)
			]);
		});

		$this->onRest('req.get.editinfo.render', function () {

			Logger::create('9 editinfo ', CLogger::LEVEL_TRACE);
			$errors	 = 'data not found';
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			$success = false;
			if ($result == true)
			{
				$vendorId = UserInfo::getEntityId();
				if ($vendorId > 0)
				{
					$data					 = Vendors::model()->getViewDetailbyId($vendorId);
					$alternateContactList	 = Vendors::model()->getContactByVndId($vendorId);
					foreach ($alternateContactList AS $value)
					{
						$alternateContact[] = $value['vnd_contact_number'];
					}
					$data['vnd_alt_contact_number']		 = implode(' , ', $alternateContact);
					$success							 = ($data['vnd_id'] > 0) ? true : false;
					$errors								 = ($data['vnd_id'] > 0) ? '' : $errors;
					$data['vnd_agreement_file_link']	 = '';
					$data['vnd_voter_id_path']			 = '';
					$data['vnd_voter_id_back_path']		 = '';
					$data['vnd_aadhaar_path']			 = '';
					$data['vnd_aadhaar_back_path']		 = '';
					$data['vnd_pan_path']				 = '';
					$data['vnd_pan_back_path']			 = '';
					$data['vnd_licence_path']			 = '';
					$data['vnd_licence_back_path']		 = '';
					$data['vnd_firm_attach']			 = '';
					$data['vnd_agreement_status']		 = '';
					$data['vnd_voter_id_status']		 = '';
					$data['vnd_voter_id_back_status']	 = '';
					$data['vnd_aadhaar_status']			 = '';
					$data['vnd_aadhaar_back_status']	 = '';
					$data['vnd_pan_status']				 = '';
					$data['vnd_pan_back_status']		 = '';
					$data['vnd_licence_status']			 = '';
					$data['vnd_licence_back_status']	 = '';
					$data['vnd_firm_status']			 = '';
					$data['rating']						 = '';
					//$data['vnd_photo_path']				 = '';

					$venDocs = Document::model()->findAllByVndId($vendorId);

					if (count($venDocs) > 0)
					{
						//echo  $venDocs['adhfile_front'];exit;   
						$vagDigitalSign					 = VendorAgreement::getPathById($venDocs[0]['vag_id'], VendorAgreement::DIGITAL_AGREEMENT);
						$vagSoftPath					 = VendorAgreement::getPathById($venDocs[0]['vag_id'], VendorAgreement::SOFT_PATH);
						$agreement_file					 = ($venDocs[0]['vag_soft_path'] != '') ? $vagSoftPath : $vagDigitalSign;
						$data['vnd_agreement_file_link'] = $agreement_file;
						$data['vnd_agreement_status']	 = (int) $venDocs[0]['vd_agmt_status'];
						$data['vnd_voter_id_path']		 = $venDocs[0]['votfile_front'];
						if (substr_count($venDocs[0]['votfile_front'], "contact") > 0)
						{
							$data['vnd_voter_id_path'] = Document::getDocPathById($venDocs[0]['voter_docid'], 1);
							//$data['vnd_voter_id_path'] = parse_url(AttachmentProcessing::ImagePath($venDocs[0]['votfile_front']), PHP_URL_PATH);
						}

						$data['vnd_voter_id_status'] = ($venDocs[0]['votfile_front'] == null) ? '' : $venDocs[0]['votfile_status'];

						$data['vnd_voter_id_back_path'] = $venDocs[0]['votfile_back'];
						if (substr_count($venDocs[0]['votfile_back'], "contact") > 0)
						{
							$data['vnd_voter_id_back_path'] = Document::getDocPathById($venDocs[0]['voter_docid'], 2);
							//$data['vnd_voter_id_back_path'] = parse_url(AttachmentProcessing::ImagePath($venDocs[0]['votfile_back']), PHP_URL_PATH);
						}
						$data['vnd_voter_id_back_status']	 = ($venDocs[0]['votfile_back'] == null) ? '' : $venDocs[0]['votfile_status'];
						$data['voter_remarks']				 = $venDocs[0]['voter_remarks'];

						$data['vnd_aadhaar_path'] = $venDocs[0]['adhfile_front'];
						if (substr_count($venDocs[0]['adhfile_front'], "contact") > 0)
						{
							$data['vnd_aadhaar_path'] = Document::getDocPathById($venDocs[0]['adhid_docid'], 1);
							//$data['vnd_aadhaar_path'] = parse_url(AttachmentProcessing::ImagePath($venDocs[0]['adhfile_front']), PHP_URL_PATH);
						}


						$data['vnd_aadhaar_status'] = ($venDocs[0]['adhfile_front'] == null) ? '' : $venDocs[0]['adhfile_status'];

						$data['vnd_aadhaar_back_path'] = $venDocs[0]['adhfile_back'];
						if (substr_count($venDocs[0]['adhfile_back'], "contact") > 0)
						{
							$data['vnd_aadhaar_back_path'] = Document::getDocPathById($venDocs[0]['adhid_docid'], 2);
							//$data['vnd_aadhaar_back_path'] = parse_url(AttachmentProcessing::ImagePath($venDocs[0]['adhfile_back']), PHP_URL_PATH);
						}

						$data['vnd_aadhaar_back_status'] = ($venDocs[0]['adhfile_back'] == null) ? '' : $venDocs[0]['adhfile_status'];
						$data['aadhar_remarks']			 = $venDocs[0]['aadhar_remarks'];

						$data['vnd_pan_path'] = $venDocs[0]['panfile_front'];
						if (substr_count($venDocs[0]['panfile_front'], "contact") > 0)
						{
							$data['vnd_pan_path'] = Document::getDocPathById($venDocs[0]['pan_docid'], 1);
							//$data['vnd_pan_path'] = parse_url(AttachmentProcessing::ImagePath($venDocs[0]['panfile_front']), PHP_URL_PATH);
						}

						$data['vnd_pan_status'] = ($venDocs[0]['panfile_front'] == null) ? '' : $venDocs[0]['panfile_status'];

						$data['vnd_pan_back_path'] = $venDocs[0]['panfile_back'];
						if (substr_count($venDocs[0]['panfile_back'], "contact") > 0)
						{
							$data['vnd_pan_back_path'] = Document::getDocPathById($venDocs[0]['pan_docid'], 2);
							//$data['vnd_pan_back_path'] = parse_url(AttachmentProcessing::ImagePath($venDocs[0]['panfile_back']), PHP_URL_PATH);
						}
						$data['vnd_pan_back_status'] = ($venDocs[0]['panfile_back'] == null) ? '' : $venDocs[0]['panfile_status'];
						$data['pan_remarks']		 = $venDocs[0]['pan_remarks'];

						$data['vnd_licence_path'] = $venDocs[0]['licfile_front'];
						if (substr_count($venDocs[0]['licfile_front'], "contact") > 0)
						{
							$data['vnd_licence_path'] = Document::getDocPathById($venDocs[0]['lic_docid'], 1);
							//$data['vnd_licence_path'] = parse_url(AttachmentProcessing::ImagePath($venDocs[0]['licfile_front']), PHP_URL_PATH);
						}
						$data['vnd_licence_status'] = ($venDocs[0]['licfile_front'] == null) ? '' : $venDocs[0]['licfile_status'];

						$data['vnd_licence_back_path'] = $venDocs[0]['licfile_back'];
						if (substr_count($venDocs[0]['licfile_back'], "contact") > 0)
						{
							$data['vnd_licence_back_path'] = Document::getDocPathById($venDocs[0]['lic_docid'], 2);
							//$data['vnd_licence_back_path'] = parse_url(AttachmentProcessing::ImagePath($venDocs[0]['licfile_back']), PHP_URL_PATH);
						}
						$data['vnd_licence_back_status'] = ($venDocs[0]['licfile_back'] == null) ? '' : $venDocs[0]['licfile_status'];
						$data['license_remarks']		 = $venDocs[0]['license_remarks'];

						$data['vnd_firm_attach'] = $venDocs[0]['memofile_front'];
						if (substr_count($venDocs[0]['memofile_front'], "contact") > 0)
						{
							$data['vnd_firm_attach'] = Document::getDocPathById($venDocs[0]['memoid_docid'], 1);
							//$data['vnd_firm_attach'] = parse_url(AttachmentProcessing::ImagePath($venDocs[0]['memofile_front']), PHP_URL_PATH);
						}

						$data['vnd_firm_status'] = ($venDocs[0]['memofile_front'] == null) ? '' : $venDocs[0]['memofile_status'];
						$data['memo_remarks']	 = $venDocs[0]['memo_remarks'];
					}

					// ['0'=> 'Rejected or Upload','1'=> 'Approved','2'=> 'Pending Approval']

					$is_on_file = 0;
					if ($data['vnd_agreement_file_link'] != '' || $data['vnd_agreement_status'] != '')
					{
						switch ($data['vnd_agreement_status'])
						{
							case 0:
								$is_on_file	 = 2;
								break;
							case 1:
								$is_on_file	 = 2;
								break;
							case 2:
								$is_on_file	 = 0;
								break;
						}
					}
					$data['is_on_file'] = (int) $is_on_file;
					if ($vendorId > 0)
					{
						$data['rating'] = VendorStats::fetchRating($vendorId);
					}
					$data['vnd_photo_path'] = $venDocs[0]['vnd_photo_path'];
					if (substr_count($data['vnd_photo_path'], "contact") > 0)
					{
						$data['vnd_photo_path'] = parse_url(AttachmentProcessing::ImagePath($data['vnd_photo_path']), PHP_URL_PATH);
					}
					$data['is_bussiness'] = ($data['ctt_user_type'] == '1' ? 0 : 1);
					if ($data['business_type'] == 1)
					{
						$btype = "Sole Propitership";
					}
					else if ($data['business_type'] == 2)
					{
						$btype = "Partner";
					}
					else if ($data['business_type'] == 3)
					{
						$btype = "Private Limited";
					}
					else if ($data['business_type'] == 4)
					{
						$btype = "Limited";
					}
					else
					{
						$btype = "";
					}
					$data['bussiness_type']	 = $btype;
					$data['first_name']		 = $data['ctt_first_name'];
					$data['last_name']		 = $data['ctt_last_name'];
					$data['vnd_id']			 = $vendorId;
				}
			}
			else
			{
				$errors = 'Unauthorised vendor';
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => $data,
				)
			]);
		});

		$this->onRest('req.post.editagreementinfo.render', function () {



			//check vendor authentication services 
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);

			if ($result == true)
			{
				$vendorId	 = UserInfo::getEntityId();
				$userId		 = UserInfo::getUserId();

				$success			 = false;
				$errors				 = [];
				$process_sync_data	 = Yii::app()->request->getParam('data');
				Logger::create("editagreementinfo data: " . $process_sync_data);
				$data1				 = CJSON::decode($process_sync_data, true);
				$vendorPic			 = $data1['vendorPic'];

				$data = CJSON::decode($data1['data']);
				//$vnd_rel_tier	 = $data['vnd_rel_tier'];

				$vndDigital		 = $_FILES['vnd_digital_sign']['name'];
				$vndDigitalTmp	 = $_FILES['vnd_digital_sign']['tmp_name'];
//				if ($vendorPic == 1 || $vendorPic == 2)
//				{
//					$data = CJSON::decode($process_sync_data, true);			
//				}


				$model = Vendors::model()->findByPk($vendorId);

				if ($vendorPic == 2)
				{
					$contact_id	 = $model->vnd_contact_id;
					$transaction = DBUtil::beginTransaction();
					try
					{
						$model->scenario	 = 'dataagreementupdate';
						$model->vnd_rel_tier = 0;
						if ($model->validate())
						{
							$model->save();

							$cttmodel					 = Contact::model()->findByPk($contact_id);
							$cttmodel->ctt_address		 = $data['vnd_address'];
							$cttmodel->ctt_aadhaar_no	 = $data['vnd_aadhaar_no'];
							$cttmodel->ctt_voter_no		 = $data['vnd_voter_no'];
							$cttmodel->ctt_pan_no		 = $data['vnd_pan_no'];
							$cttmodel->ctt_license_no	 = $data['vnd_license_no'];
							$cttmodel->ctt_city			 = $data['vnd_city'];
							$cttmodel->update();

							$success = true;
							DBUtil::commitTransaction($transaction);
						}
						else
						{
							$errors = $model->getErrors();
						}
						if ($success == true)
						{
							//Logger::create('SUCCESS =====> : ' . "Vendor : " . $model->vnd_name . " Update", CLogger::LEVEL_INFO);
							//DBUtil::commitTransaction($transaction);
						}
					}
					catch (Exception $ex)
					{
						DBUtil::rollbackTransaction($transaction);
						Logger::create("Vendor details not saved.\n\t\t" . $e->getMessage(), CLogger::LEVEL_ERROR);
					}
				}
				if ($vendorPic == 1 && $vndDigital != '')
				{

					$transaction = DBUtil::beginTransaction();
					try
					{
						//$digitalLat	 = Yii::app()->request->getParam('digitalLat');
						//$digitalLong = Yii::app()->request->getParam('digitalLong');
						$digitalLat	 = $data1['digitalLat'];
						$digitalLong = $data1['digitalLong'];
						$appToken	 = AppTokens::model()->getByUserTypeAndUserId($userId, 2);

						$type	 = 'digital_sign';
						//echo $vndDigital."AAA".$vndDigitalTmp."BBB".$vendorId."CCCC".$type."DDD".$model->vnd_contact_id;exit;
						//$result2	 = $this->saveVendorImage($agmt2, $agmt2_tmp, $vendorId,$model->vnd_contact_id, $type);
						$result2 = Document::model()->saveVendorImage($vndDigital, $vndDigitalTmp, $vendorId, $model->vnd_contact_id, $type);
						$path1	 = str_replace("\\", "\\\\", $result2['path']);

						if (VendorAgreement::model()->updateSignature($vendorId, $path1))
						{
							/* @var $model VendorAgreement */
							$mdoelDig						 = VendorAgreement::model()->findByVndId($vendorId);
							$mdoelDig->vag_digital_flag		 = 1;
							$mdoelDig->vag_digital_uuid		 = $appToken['apt_device_uuid'];
							$mdoelDig->vag_digital_os		 = $appToken['apt_os_version'];
							$mdoelDig->vag_digital_ip		 = $appToken['apt_ip_address'];
							$mdoelDig->vag_digital_device_id = $appToken['apt_device'];
							$mdoelDig->vag_digital_ver		 = Yii::app()->params['digitalagmtversion'];
							$mdoelDig->vag_digital_lat		 = $digitalLat;
							$mdoelDig->vag_digital_long		 = $digitalLong;
							$mdoelDig->vag_active			 = 1;
							$mdoelDig->vag_digital_is_email	 = 0;
							$mdoelDig->vag_draft_agreement	 = NULL;
							$mdoelDig->vag_digital_agreement = NULL;

							if ($mdoelDig->save())
							{

								if ($model->vendorPrefs->vnp_is_freeze == 2)
								{
									$model->vendorPrefs->vnp_is_freeze = 0;
									//$model->save();
									$model->vendorPrefs->save();
								}
								$success = true;
							}
							else
							{
								$errors = $model->getErrors();
							}
							if ($success == true)
							{
								DBUtil::commitTransaction($transaction);
								Logger::create('SUCCESS =====> : ' . "Vendor : " . $mdoelDig->vagVnd->vnd_name . " ( " . $digitalLat . " - " . $digitalLong . " )", CLogger::LEVEL_INFO);
								//VendorsLog::model()->createLog($vendorId, "Digital Agreement Created. (Lat : $digitalLat , Long : $digitalLong)", UserInfo::model(), VendorsLog::VENDOR_DIGITAL_AGREEMENT_APPROVE, false, false);
							}
						}
					}
					catch (Exception $e)
					{
						DBUtil::rollbackTransaction($transaction);
						Logger::create("Digital agreement not saved.\n\t\t" . $e->getMessage(), CLogger::LEVEL_ERROR);
					}
				}
				$dataSet = ['vnd_id' => (int) $vendorId];
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
					'data'		 => $dataSet,
				)
			]);
		});

		$this->onRest('req.post.edit_new1.render', function () {


			Logger::create('11 edit new ', CLogger::LEVEL_TRACE);
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{
				$success			 = false;
				$errors				 = 'Something went wrong while uploading';
				$process_sync_data	 = Yii::app()->request->getParam('data');
				//$process_sync_data	 = "{\"voter_id_name\":\"20190504_123037_0.9216499155755714.jpg\",\"doc_type\":\"5\",\"doc_subtype\":\"license\",\"vendorPic\":\"1\"}";
				$data1				 = CJSON::decode($process_sync_data, true);
				Logger::create("Request1: " . $process_sync_data);
				$vendorPic			 = $data1['vendorPic'];
				$agmt_req_id		 = $data1['req_id'];
				$total_agmt_img_no	 = $data1['total_img_no'];
				$agmt_file1_img_no	 = $data1['file1_img_no'];
				$agmt_file2_img_no	 = $data1['file2_img_no'];

				$doc_type	 = $data1['doc_type'];
				$doc_subtype = $data1['doc_subtype'];

				$data				 = $data1['data'];
				$photo				 = $_FILES['photo']['name'];
				$photo_tmp			 = $_FILES['photo']['tmp_name'];
				$agmt_file1_img_no	 = $agmt_file2_img_no	 = 0;
				$agmt1				 = $_FILES['agreement1']['name'];
				$agmt1_tmp			 = $_FILES['agreement1']['tmp_name'];
				$agmt1_size			 = $_FILES['agreement1']['size'];
				$agmt2				 = $_FILES['agreement2']['name'];
				$agmt2_tmp			 = $_FILES['agreement2']['tmp_name'];
				$agmt2_size			 = $_FILES['agreement2']['size'];
				$agmt_file			 = $_FILES['agreement_file']['name'];
				$agmt_file_tmp		 = $_FILES['agreement_file']['tmp_name'];
				$agmt_file_size		 = $_FILES['agreement_file']['size'];
				$data				 = CJSON::decode($process_sync_data, true);
				$vendorId			 = UserInfo::getEntityId();
				Yii::log("Vendor Id : " . $vendorId, CLogger::LEVEL_INFO);
				$evtList			 = VendorsLog::model()->eventList();
				$userInfo			 = UserInfo::getInstance();
				$userInfo->userType	 = UserInfo::TYPE_VENDOR;
				$userId				 = $userInfo->getUserId();
				try
				{
					$model = Vendors::model()->findByPk($vendorId);
					if ($vendorPic == 0)
					{
						$oldData			 = Vendors::model()->getDetailsbyId($vendorId);
						$model->scenario	 = 'update';
						$model->attributes	 = $data;
						if ($model->validate())
						{
							$success					 = $model->save();
							$contact_id					 = $oldData['vnd_contact_id'];
							$contactModel				 = Contact::model()->findByPk($contact_id);
							$contactModel->scenario		 = 'update';
							$data['ctt_address']		 = $data['data']['vnd_address'];
							//	$data['ctt_business_name']	 = $data['vnd_company'];
							//$contactModel->address	 = $data;
							$contactModel->attributes	 = $data;
							$success					 = $contactModel->save();
							$errors						 = [];
							$newData					 = Vendors::model()->getDetailsbyId($vendorId);
							$getOldDifference			 = array_diff_assoc($oldData, $newData);
							$getNewDifference			 = array_diff_assoc($newData, $oldData);
							$changesForLog				 = " Old Values: " . Vendors::model()->getModificationMSG($getOldDifference) .
									" :: New Values: " . Vendors::model()->getModificationMSG($getNewDifference);
							$eventId					 = VendorsLog::VENDOR_EDIT;
							$logDesc					 = $evtList[$eventId];
							$desc						 = $logDesc . $changesForLog;
							VendorsLog::model()->createLog($vendorId, $desc, $userInfo, $eventId, false, false);
						}
						else
						{
							$errors = $model->getErrors();
							throw new Exception("Vendor update failed.\n\t\t " . json_encode($errors));
						}
					}
					else if ($vendorPic == 1)
					{

						$ctr = 0;
						if ($agmt1 != '' || $agmt2 != '')
						{

							$app_row = AppTokens::model()->getByUserTypeAndUserId($userId, 2);

							$deviceId	 = $app_row['apt_device'];
							$today		 = date("Y-m-d H:i:s");

							if (($total_agmt_img_no >= $agmt_file1_img_no))
							{
								if ($agmt1 != '')
								{
									$type = 'agreement';

									$result1	 = Document::model()->saveVendorImage($agmt1, $agmt1_tmp, $vendorId, $model->vnd_contact_id, $type);
									$path1		 = str_replace("'\'", "\\\\", $result1['path']);
									$sql		 = "INSERT INTO `vendor_agmt_docs` (`vd_vnd_id`, `vd_agmt_img_no`, `vd_agmt_req_id`, `vd_agmt_device_id`, `vd_agmt`, `vd_agmt_status`, `vd_agmt_date`)
                                    VALUES ('$vendorId', '$agmt_file1_img_no', '$agmt_req_id', '$deviceId', '$path1', '0', '$today')";
									$recorset	 = Yii::app()->db->createCommand($sql)->execute();

									if ($recorset)
									{
										$success = true;
										$errors	 = "";
									}
									$ctr++;
								}
							}



							if (($total_agmt_img_no >= $agmt_file2_img_no))
							{
								if ($agmt2 != '')
								{
									$type		 = 'agreement';
									$result2	 = Document::model()->saveVendorImage($agmt2, $agmt2_tmp, $vendorId, $model->vnd_contact_id, $type);
									$path1		 = str_replace("'\'", "\\\\", $result2['path']);
									$sql		 = "INSERT INTO `vendor_agmt_docs` (`vd_vnd_id`, `vd_agmt_img_no`, `vd_agmt_req_id`, `vd_agmt_device_id`, `vd_agmt`, `vd_agmt_status`, `vd_agmt_date`)
                                    VALUES ('$vendorId', '$agmt_file2_img_no', '$agmt_req_id', '$deviceId', '$path1', '0', '$today')";
									$recorset	 = Yii::app()->db->createCommand($sql)->execute();
									if ($recorset)
									{
										$success = true;
										$errors	 = "";
									}
									$ctr++;
								}
							}
							$updateCount = VendorAgmtDocs::model()->updateStatusByVndReqId($vendorId, $agmt_req_id, $total_agmt_img_no);
							if ($updateCount > 0)
							{
								$modelDigital				 = VendorAgreement::model()->findByVndId($vendorId);
								$modelDigital->vag_soft_flag = 2;
								$modelDigital->save();
							}
							else
							{
								throw new Exception("Document images( Agreement ) creation failed.\n\t\t");
							}
						}
						if ($photo != '')
						{
							$type	 = 'profile';
							$result2 = Document::model()->saveVendorImage($photo, $photo_tmp, $vendorId, $model->vnd_contact_id, $type);

							$modelDocPhoto = new Document();

							$path2		 = str_replace("\\", "\\\\", $result2['path']);
							$qry2		 = "UPDATE contact SET ctt_profile_path = '" . $path2 . "' WHERE ctt_id = " . $model->vnd_contact_id;
							$recorset2	 = Yii::app()->db->createCommand($qry2)->execute();
							if ($recorset2)
							{
								$success = true;
								$errors	 = [];
								$eventid = VendorsLog::VENDOR_PROFILE_UPLOAD;
								$logDesc = $evtList[$eventid];
								VendorsLog::model()->createLog($vendorId, $logDesc, UserInfo::getInstance(), $eventid, false, false);
							}
						}

						if ($doc_subtype == 'voter_id')
						{
							$type = 'voterid';
							if ($model->vndContact->ctt_voter_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->vndContact->ctt_voter_doc_id);
							}

							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("Voter Id already exists.\n\t\t");
							}
							else
							{
								if ($model->vndContact->ctt_voter_doc_id != "")
								{
									$modelDocVoterFront = Document::model()->findByPk($model->vndContact->ctt_voter_doc_id);
									//$modelDocVoterFront->doc_status = 0;
								}
								else
								{
									$modelDocVoterFront = new Document();
								}

								$modelDocVoterFront->isDocsApp					 = true;
								$modelDocVoterFront->local_doc_file_front_path	 = $doc_subtype;
								$modelDocVoterFront->entity_id					 = $model->vnd_contact_id;
								$modelDocVoterFront->doc_type					 = $doc_type;
								$modelDocVoterFront->add();
								Contact::model()->updateContact($modelDocVoterFront->doc_id, $modelDocVoterFront->doc_type, $model->vnd_contact_id, '');
								$success										 = $model->saveDocument($vendorId, $modelDocVoterFront->doc_file_front_path, $userInfo, $type);
								if ($success)
								{

									Logger::create('SUCCESS =====> : ' . "Voter Id : " . $modelDocVoterFront->doc_file_front_path, CLogger::LEVEL_INFO);
									$errors = "";
								}
								else
								{
									$errors = ("Voter Id creation failed.\n\t\t" . json_encode($modelDocVoterFront->getErrors()));
								}
							}
						}
						if ($doc_subtype == 'voter_back_id')
						{
							$type = 'voterbackid';
							if ($model->vndContact->ctt_voter_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->vndContact->ctt_voter_doc_id);
							}
							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("Voter Back-Id already exists.\n\t\t");
							}
							else
							{
								if ($model->vndContact->ctt_voter_doc_id != "")
								{
									$modelDocVoterBack = Document::model()->findByPk($model->vndContact->ctt_voter_doc_id);
									//$modelDocVoterBack->doc_status = 0;
								}
								else
								{
									$modelDocVoterBack = new Document();
								}

								$modelDocVoterBack->isDocsApp				 = true;
								$modelDocVoterBack->local_doc_file_back_path = $doc_subtype;
								$modelDocVoterBack->entity_id				 = $model->vnd_contact_id;
								$modelDocVoterBack->doc_type				 = $doc_type;
								$modelDocVoterBack->add();
								Contact::model()->updateContact($modelDocVoterBack->doc_id, $modelDocVoterBack->doc_type, $model->vnd_contact_id, '');
								$success									 = $model->saveDocument($vendorId, $modelDocVoterBack->doc_file_back_path, $userInfo, $type);
								if ($success)
								{
									Logger::create('SUCCESS =====> : ' . "Voter Back Id : " . $modelDocVoterBack->doc_file_back_path, CLogger::LEVEL_INFO);
									$errors = "";
								}
								else
								{
									$errors = ("Voter Back creation failed.\n\t\t" . json_encode($modelDocVoterBack->getErrors()));
								}
							}
						}

						if ($doc_subtype == 'aadhaar')
						{
							$type = 'adhar';
							if ($model->vndContact->ctt_aadhar_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->vndContact->ctt_aadhar_doc_id);
							}
							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("Aadhar Id already exists.\n\t\t");
							}
							else
							{
								if ($model->vndContact->ctt_aadhar_doc_id != "")
								{
									$modelDocAadharFront = Document::model()->findByPk($model->vndContact->ctt_aadhar_doc_id);
									//$modelDocAadharFront->doc_status = 0;
								}
								else
								{
									$modelDocAadharFront = new Document();
								}

								$modelDocAadharFront->isDocsApp					 = true;
								$modelDocAadharFront->local_doc_file_front_path	 = $doc_subtype;
								$modelDocAadharFront->entity_id					 = $model->vnd_contact_id;
								$modelDocAadharFront->doc_type					 = $doc_type;
								$modelDocAadharFront->add();
								Contact::model()->updateContact($modelDocAadharFront->doc_id, $modelDocAadharFront->doc_type, $model->vnd_contact_id, '');
								$success										 = $model->saveDocument($vendorId, $modelDocAadharFront->doc_file_front_path, $userInfo, $type);
								if ($success)
								{
									Logger::create('SUCCESS =====> : ' . "Aadhar Id : " . $modelDocAadharFront->doc_file_front_path, CLogger::LEVEL_INFO);
									$errors = "";
								}
								else
								{
									$errors = ("Aadhar Id creation failed.\n\t\t");
								}
							}
						}
						if ($doc_subtype == 'aadhaar_back')
						{
							$type = 'adharback';
							if ($model->vndContact->ctt_aadhar_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->vndContact->ctt_aadhar_doc_id);
							}
							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("Aadhar Back-Id already exists.\n\t\t");
							}
							else
							{
								if ($model->vndContact->ctt_aadhar_doc_id != "")
								{
									$modelDocAadharBack = Document::model()->findByPk($model->vndContact->ctt_aadhar_doc_id);
									//$modelDocAadharBack->doc_status = 0;
								}
								else
								{
									$modelDocAadharBack = new Document();
								}

								$modelDocAadharBack->isDocsApp					 = true;
								$modelDocAadharBack->local_doc_file_back_path	 = $doc_subtype;
								$modelDocAadharBack->entity_id					 = $model->vnd_contact_id;
								$modelDocAadharBack->doc_type					 = $doc_type;
								$modelDocAadharBack->add();
								Contact::model()->updateContact($modelDocAadharBack->doc_id, $modelDocAadharBack->doc_type, $model->vnd_contact_id, '');
								$success										 = $model->saveDocument($vendorId, $modelDocAadharBack->doc_file_back_path, $userInfo, $type);
								if ($success)
								{
									Logger::create('SUCCESS =====> : ' . "Aadhar Back Id : " . $modelDocAadharBack->doc_file_back_path, CLogger::LEVEL_INFO);
									$errors = "";
								}
								else
								{
									$errors = ("Aadhar Back creation failed.\n\t\t");
								}
							}
						}
						if ($doc_subtype == 'pan')
						{
							$type = 'pan';
							if ($model->vndContact->ctt_pan_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->vndContact->ctt_pan_doc_id);
							}
							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("Pan-Id already exists.\n\t\t");
							}
							else
							{
								if ($model->vndContact->ctt_pan_doc_id != "")
								{
									$modelDocPanFront = Document::model()->findByPk($model->vndContact->ctt_pan_doc_id);
									//$modelDocPanFront->doc_status = 0;
								}
								else
								{
									$modelDocPanFront = new Document();
								}

								$modelDocPanFront->isDocsApp				 = true;
								$modelDocPanFront->local_doc_file_front_path = $doc_subtype;
								$modelDocPanFront->entity_id				 = $model->vnd_contact_id;
								$modelDocPanFront->doc_type					 = $doc_type;
								$modelDocPanFront->add();
								Contact::model()->updateContact($modelDocPanFront->doc_id, $modelDocPanFront->doc_type, $model->vnd_contact_id, '');
								$success									 = $model->saveDocument($vendorId, $modelDocPanFront->doc_file_front_path, $userInfo, $type);

								if ($success)
								{
									Logger::create('SUCCESS =====> : ' . "Pan Id : " . $modelDocPanFront->doc_file_front_path, CLogger::LEVEL_INFO);
									$errors = "";
								}
								else
								{
									$errors = ("Pan-Id creation failed.\n\t\t" . json_encode($modelDocPanFront->getErrors()));
								}
							}
						}
						if ($doc_subtype == 'pan_back')
						{
							$type = 'panback';
							if ($model->vndContact->ctt_pan_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->vndContact->ctt_pan_doc_id);
							}


							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("Pan Back-Id already exists.\n\t\t");
							}
							else
							{
								if ($model->vndContact->ctt_pan_doc_id != "")
								{
									$modelDocPanBack = Document::model()->findByPk($model->vndContact->ctt_pan_doc_id);
									//$modelDocPanBack->doc_status = 0;
								}
								else
								{
									$modelDocPanBack = new Document();
								}

								$modelDocPanBack->isDocsApp					 = true;
								$modelDocPanBack->local_doc_file_back_path	 = $doc_subtype;
								$modelDocPanBack->entity_id					 = $model->vnd_contact_id;
								$modelDocPanBack->doc_type					 = $doc_type;
								$modelDocPanBack->add();
								Contact::model()->updateContact($modelDocPanBack->doc_id, $modelDocPanBack->doc_type, $model->vnd_contact_id, '');
								$success									 = $model->saveDocument($vendorId, $modelDocPanBack->doc_file_back_path, $userInfo, $type);
								if ($success)
								{
									Logger::create('SUCCESS =====> : ' . "Pan Back Id : " . $modelDocPanBack->doc_file_back_path, CLogger::LEVEL_INFO);
									$errors = "";
								}
								else
								{
									$errors = ("Pan Back creation failed.\n\t\t");
								}
							}
						}
						if ($doc_subtype == 'license')
						{
							$type = 'license';

							if ($model->vndContact->ctt_license_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->vndContact->ctt_license_doc_id);
							}

							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("License Front already exists.\n\t\t");
							}
							else
							{
								if ($model->vndContact->ctt_license_doc_id != "")
								{
									$modelDocLicenseFront = Document::model()->findByPk($model->vndContact->ctt_license_doc_id);
									//$modelDocLicenseFront->doc_status = 0;
								}
								else
								{
									$modelDocLicenseFront = new Document();
								}
								$modelDocLicenseFront->isDocsApp				 = true;
								$modelDocLicenseFront->local_doc_file_front_path = $doc_subtype;
								$modelDocLicenseFront->entity_id				 = $model->vnd_contact_id;
								$modelDocLicenseFront->doc_type					 = $doc_type;
								$modelDocLicenseFront->add();
								Contact::model()->updateContact($modelDocLicenseFront->doc_id, $modelDocLicenseFront->doc_type, $model->vnd_contact_id, '');
								$success										 = $model->saveDocument($vendorId, $modelDocLicenseFront->doc_file_front_path, $userInfo, $type);
								if ($success)
								{
									Logger::create('SUCCESS =====> : ' . "License Id : " . $modelDocLicenseFront->doc_file_front_path, CLogger::LEVEL_INFO);
									$errors = "";
								}
								else
								{
									$errors = ("License creation failed.\n\t\t");
								}
							}
						}
						if ($doc_subtype == 'license_back')
						{
							$type = 'licenseback';
							if ($model->vndContact->ctt_license_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->vndContact->ctt_license_doc_id);
							}

							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("License Back already exists.\n\t\t");
							}
							else
							{
								if ($model->vndContact->ctt_license_doc_id != "")
								{
									$modelDocLicenseBack = Document::model()->findByPk($model->vndContact->ctt_license_doc_id);
									//$modelDocLicenseBack->doc_status = 0;
								}
								else
								{
									$modelDocLicenseBack = new Document();
								}

								$modelDocLicenseBack->isDocsApp					 = true;
								$modelDocLicenseBack->local_doc_file_back_path	 = $doc_subtype;
								$modelDocLicenseBack->entity_id					 = $model->vnd_contact_id;
								$modelDocLicenseBack->doc_type					 = $doc_type;
								$modelDocLicenseBack->add();
								Contact::model()->updateContact($modelDocLicenseBack->doc_id, $modelDocLicenseBack->doc_type, $model->vnd_contact_id, '');
								$success										 = $model->saveDocument($vendorId, $modelDocLicenseBack->doc_file_back_path, $userInfo, $type);
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

						if ($doc_subtype == 'memorandum')
						{
							$type = 'memorandum';
							if ($model->vndContact->ctt_memo_doc_id != "")
							{
								$checkApprove = Document::model()->checkApproveDocById($model->vndContact->ctt_memo_doc_id);
							}

							if ($checkApprove == 1)
							{
								$success = false;
								$errors	 = ("Trade License already exists.\n\t\t");
							}
							else
							{
								if ($model->vndContact->ctt_memo_doc_id != "")
								{
									$modelDocMemo = Document::model()->findByPk($model->vndContact->ctt_memo_doc_id);
									//$modelDocMemo->doc_status = 0;
								}
								else
								{
									$modelDocMemo = new Document();
								}

								$modelDocMemo->isDocsApp				 = true;
								$modelDocMemo->local_doc_file_front_path = $doc_subtype;
								$modelDocMemo->entity_id				 = $model->vnd_contact_id;
								$modelDocMemo->doc_type					 = $doc_type;
								$modelDocMemo->add();
								Contact::model()->updateContact($modelDocMemo->doc_id, $modelDocMemo->doc_type, $model->vnd_contact_id, '');
								$success								 = $model->saveDocument($vendorId, $modelDocMemo->doc_file_front_path, $userInfo, $type);
								if ($success)
								{
									Logger::create('SUCCESS =====> : ' . "Memorandum Id : " . $modelDocMemo->doc_file_front_path, CLogger::LEVEL_INFO);
									$errors = "";
								}
								else
								{
									$errors = ("Trade License creation failed.\n\t\t");
								}
							}
						}
					}
					else if ($vendorPic == 2)
					{
						if ($agmt_file != '' && $agmt_file_tmp != '')
						{
							$type	 = 'agreement';
							$path1	 = str_replace("\\", "\\\\", $result1['path']);
							$success = $model->saveDocument($vendorId, $path1, UserInfo::getInstance(), $type);
							if ($success)
							{
								$errors = [];
								Logger::create('SUCCESS =====> : ' . "Agreement : " . $path1, CLogger::LEVEL_INFO);
							}
							else
							{
								throw new Exception("Agreements creation failed.\n\t\t" . json_encode($modelVenDoc->getErrors()));
							}
						}
					}
				}
				catch (Exception $e)
				{
					Logger::create("Vendor details or document not saved.\n\t\t" . $e->getMessage(), CLogger::LEVEL_ERROR);
				}
				unset($model->vnd_password);
				unset($model->vnd_accepted_zone);
				unset($model->vnd_log);
			}//end if brace
			else
			{
				$success = false;
				$error	 = 'Unauthorised vendor';
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $success,
					'errors'		 => $errors,
					'data'			 => $model,
					'vnd_id'		 => $vendorId,
					'file1_img_no'	 => $agmt_file1_img_no,
					'file2_img_no'	 => $agmt_file2_img_no
				),
			]);
		});

		$this->onRest('req.post.edit_new.render', function () {

			Logger::create('11 edit new ', CLogger::LEVEL_TRACE);

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
			Logger::create("Request1: " . $process_sync_data);
			//$process_sync_data = "{\"data\":{\"vnd_id\":\"34080\"},\"total_img_no\":\"1\",\"vendorPic\":\"1\",\"req_id\":\"01K6BA\"}";
			$processFile1		 = Yii::app()->request->getParam('file1_img_no');
			$processFile2		 = Yii::app()->request->getParam('file2_img_no');
			$transaction		 = DBUtil::beginTransaction();
			try
			{
				$result		 = $this->editVendorDetails($process_sync_data, $processFile1, $processFile2, $returnSet);
				$returnSet	 = $result['returnSet'];
				$model		 = $result['model'];
				if ($returnSet->isSuccess())
				{
					DBUtil::commitTransaction($transaction);
					goto resultResponse;
				}
			}
			catch (Exception $e)
			{
				Logger::create("Vendor details or document not saved.\n\t\t" . $e->getMessage(), CLogger::LEVEL_ERROR);
				DBUtil::rollbackTransaction($transaction);
				$returnSet->setStatus(false);
				$returnSet->setErrors($e->getMessage());
			}
			resultResponse:
			unset($model->vnd_password);
			unset($model->vnd_accepted_zone);
			unset($model->vnd_log);
			Logger::create("Result Data=>" . json_encode($result), CLogger::LEVEL_TRACE);
			if ($returnSet->getErrors() == NULL)
			{
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $returnSet->isSuccess(),
					'data'			 => $model,
					'vnd_id'		 => $result['vendorId'],
					'file1_img_no'	 => $result['agmt_file1_img_no'],
					'file2_img_no'	 => $result['agmt_file2_img_no']
				),
			]);
			}
			else
			{
				return $this->renderJSON([
							'type'	 => 'raw',
							'data'	 => array(
								'success'		 => $returnSet->isSuccess(),
								'errors'		 => array($returnSet->getErrors()),
								'data'			 => $model,
								'vnd_id'		 => $result['vendorId'],
								'file1_img_no'	 => $result['agmt_file1_img_no'],
								'file2_img_no'	 => $result['agmt_file2_img_no']
							),
				]);
			}
		});

		$this->onRest('req.post.edit.render', function () {
			
			try
			{
				$vendorId			 = UserInfo::getEntityId();
				$success			 = false;
				$errors				 = 'Something went wrong while uploading';
				$process_sync_data	 = Yii::app()->request->getParam('data');
				Logger::info("Required data req.post.edit.render : " . $process_sync_data);
				//$process_sync_data	 = '{"data":"{\"vnd_id\":\"23451\",\"vnd_accepted_zone\":\"5,8,10,11,12,15,19,24,28,29,30,32,33,79,80,81,111,354,382,383,384,387,388,391,397,414,415,418,420,425,432,438,445,495\",\"vnd_booking_type\":\"2\",\"vnd_home_zone\":\"Z-CHANDIGARH\",\"vnd_sedan_count\":\"1\",\"vnd_suv_count\":\"\",\"vnd_account_type\":\"0\",\"vnd_bank_ifsc\":\"\",\"vnd_beneficiary_name\":\"\",\"vnd_pan_no\":\"BSIPS8511B\",\"vnd_aadhaar_no\":\"664412087928\",\"vnd_voter_no\":\"5584258225\",\"vnd_license_no\":\"\",\"vnd_license_exp_date\":\"2019-03-25\",\"vnd_license_issue_auth\":\"105\"}","vendorPic":"0"}';
				$data1		 = CJSON::decode($process_sync_data, true);
				$data		 = CJSON::decode($data1['data']);
				$vendorPic	 = $data1['vendorPic'];
				$evtList	 = VendorsLog::model()->eventList();
				$user_id	 = Yii::app()->user->getId();
				$model		 = Vendors::model()->findByPk($vendorId);
				
				if($model->vnd_active == 0)
				{
					throw new Exception("Inactive vendor unable to edit data.", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}
				$contact_id = ContactProfile::getByEntityId($vendorId, UserInfo::TYPE_VENDOR);
				if ($vendorPic == 0)
				{

					$oldData			 = Vendors::model()->getDetailsbyId($vendorId);
					$model->scenario	 = 'update';
					$model->attributes	 = $data;
					if ($model->validate())
					{
						
						if($data['vnd_id']=="" || $data['vnd_id'] == 0)
						{
							throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
						}
						if($data['vnd_license_exp_date']=="" || $data['vnd_license_no'] == "" )
						{
							throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
						}
						
						$success	 = $model->save();
						if($success==false)
						{
							throw new Exception("Invalid Data.", ReturnSet::ERROR_INVALID_DATA);
						}
						$successcontact = Contact::model()->updateContactDocNumber($contact_id, $data);
						
						$successPref = VendorPref::model()->updateVendorPref($vendorId, $data);
					}
					$errors	 = $model->getErrors();
					$newData = Vendors::model()->getDetailsbyId($vendorId);

					$getOldDifference	 = array_diff_assoc($oldData, $newData);
					$getNewDifference	 = array_diff_assoc($newData, $oldData);

					$changesForLog	 = " Old Values: " . Vendors::model()->getModificationMSG($getOldDifference) .
							" :: New Values: " . Vendors::model()->getModificationMSG($getNewDifference);
					$eventid		 = VendorsLog::VENDOR_EDIT;
					$logDesc		 = $evtList[$eventid];
					$desc			 = $logDesc . $changesForLog;
					VendorsLog::model()->createLog($vendorId, $desc, UserInfo::getInstance(), $eventid, false, false);
				}
					
					if ($memorandum != '')
					{
						$type			 = 'memorandum';
						$result1		 = Document::model()->saveVendorImage($memorandum, $memorandum_tmp, $vendorId, $contact_id, $type);
						$modelDocMemo	 = new Document();
						//$result1							 = $modelDoc->uploadDocument($model->vnd_contact_id, $type, $memorandum, "");
						//$result1['path']					 = $result1[0];
						$path1			 = str_replace("\\", "\\\\", $result1['path']);
						//$qry1		 = "UPDATE vendors SET vnd_firm_attach = '" . $path1 . "' WHERE vnd_id = " . $vendorId;
						//$recorset1	 = Yii::app()->db->createCommand($qry1)->execute();
						if (!empty($model->vndContact->ctt_memo_doc_id))
						{
							$qry2		 = "UPDATE document SET doc_file_front_path = '" . $path1 . "' WHERE doc_id = " . $model->vndContact->ctt_memo_doc_id;
							// echo $qry2;exit;
							$recorset1	 = Yii::app()->db->createCommand($qry2)->execute();
						}
						else
						{
							$modelDocMemo->doc_file_front_path	 = $result1['path'];
							$modelDocMemo->doc_type				 = 6;
							$modelDocMemo->save();
							$qry1								 = "UPDATE contact SET ctt_memo_doc_id = '" . $modelDocMemo->doc_id . "' WHERE ctt_id = " . $contact_id;
							$recorset1							 = Yii::app()->db->createCommand($qry1)->execute();
						}

						if ($recorset1)
						{
							$success = true;
							$errors	 = [];
							$eventid = VendorsLog::VENDOR_MEMORANDUM_UPLOAD;
							$logDesc = $evtList[$eventid];
							VendorsLog::model()->createLog($vendorId, $logDesc, UserInfo::getInstance(), $eventid, false, false);
						}
					}
					if ($photo != '')
					{
						$type			 = 'profile';
						$result2		 = Document::model()->saveVendorImage($photo, $photo_tmp, $vendorId, $contact_id, $type);
						$modelDocPhoto	 = new Document();
						//$result2		 = $modelDocPhoto->uploadDocument($model->vnd_contact_id, $type, $photo, "");
						$path2			 = str_replace("\\", "\\\\", $result2['path']);
						//$qry2		 = "UPDATE vendors SET vnd_photo_path = '" . $path2 . "' WHERE vnd_id = " . $vendorId;
						//$recorset2	 = Yii::app()->db->createCommand($qry2)->execute();
						//echo $path2;exit;
						$qry2			 = "UPDATE contact SET ctt_profile_path = '" . $path2 . "' WHERE ctt_id = " . $contact_id;
						$recorset2		 = Yii::app()->db->createCommand($qry2)->execute();

						if ($recorset2)
						{
							$success = true;
							$errors	 = [];
							$eventid = VendorsLog::VENDOR_PROFILE_UPLOAD;
							$logDesc = $evtList[$eventid];
							VendorsLog::model()->createLog($vendorId, $logDesc, UserInfo::getInstance(), $eventid, false, false);
						}
					}
					if ($voter_id != '')
					{
						$type			 = 'voterid';
						$result3		 = Document::model()->saveVendorImage($voter_id, $voter_id_tmp, $vendorId, $contact_id, $type);
						$modelDocVoter	 = new Document();
						//$result3							 = $modelDocVoter->uploadDocument($model->vnd_contact_id, $type, $voter_id, "");
						//$result3['path']					 = $result3[0];
						$path3			 = str_replace("\\", "\\\\", $result3['path']);

						if (!empty($model->vndContact->ctt_voter_doc_id))
						{
							$qry2		 = "UPDATE document SET doc_file_front_path = '" . $path3 . "' WHERE doc_id = " . $model->vndContact->ctt_voter_doc_id;
							$recorset3	 = Yii::app()->db->createCommand($qry2)->execute();
						}
						else
						{
							$modelDocVoter->doc_file_front_path	 = $result3['path'];
							$modelDocVoter->doc_type			 = 2;
							$modelDocVoter->save();
							$qry3								 = "UPDATE contact SET ctt_voter_doc_id = '" . $modelDocVoter->doc_id . "' WHERE ctt_id = " . $contact_id;
							$recorset3							 = Yii::app()->db->createCommand($qry3)->execute();
						}

						//$qry3		 = "UPDATE vendors SET vnd_voter_id_path = '" . $path3 . "' WHERE vnd_id = " . $vendorId;
						//$recorset3	 = Yii::app()->db->createCommand($qry3)->execute();
						if ($recorset3)
						{
							$success = true;
							$errors	 = [];
							$eventid = VendorsLog::VENDOR_VOTERID_UPLOAD;
							$logDesc = $evtList[$eventid];
							VendorsLog::model()->createLog($vendorId, $logDesc, UserInfo::getInstance(), $eventid, false, false);
						}
					}
					if ($aadhaar != '')
					{
						$type			 = 'adhar';
						$result4		 = Document::model()->saveVendorImage($aadhaar, $aadhaar_tmp, $vendorId, $model->vnd_contact_id, $type);
						$modelDocAdhar	 = new Document();
						//$result4							 = $modelDocAdhar->uploadDocument($model->vnd_contact_id, $type, $aadhaar, "");
						//$result4['path']					 = $result4[0];
						$path4			 = str_replace("\\", "\\\\", $result4['path']);
						//$qry4		 = "UPDATE vendors SET vnd_aadhaar_path = '" . $path4 . "' WHERE vnd_id = " . $vendorId;
						//$recorset4	 = Yii::app()->db->createCommand($qry4)->execute();
						if (!empty($model->vndContact->ctt_aadhar_doc_id))
						{
							$qry2		 = "UPDATE document SET doc_file_front_path = '" . $path4 . "' WHERE doc_id = " . $model->vndContact->ctt_aadhar_doc_id;
							$recorset4	 = Yii::app()->db->createCommand($qry2)->execute();
						}
						else
						{
							$modelDocAdhar->doc_file_front_path	 = $result4['path'];
							$modelDocAdhar->doc_type			 = 3;
							$modelDocAdhar->save();
							$qry4								 = "UPDATE contact SET ctt_aadhar_doc_id = '" . $modelDocAdhar->doc_id . "' WHERE ctt_id = " . $contact_id;
							$recorset4							 = Yii::app()->db->createCommand($qry4)->execute();
						}

						if ($recorset4)
						{
							$success = true;
							$errors	 = [];
							$eventid = VendorsLog::VENDOR_AADHAAR_UPLOAD;
							$logDesc = $evtList[$eventid];
							VendorsLog::model()->createLog($vendorId, $logDesc, UserInfo::getInstance(), $eventid, false, false);
						}
					}
					if ($pan != '')
					{
						$type		 = 'pan';
						$result5	 = Document::model()->saveVendorImage($pan, $pan_tmp, $vendorId, $contact_id, $type);
						$modelDocPan = new Document();
						//$result5		 = $modelDocPan->uploadDocument($model->vnd_contact_id, $type, $pan, "");
						//$result5['path'] = $result5[0];
						$path5		 = str_replace("\\", "\\\\", $result5['path']);

						//$qry5		 = "UPDATE vendors SET vnd_pan_path = '" . $path5 . "' WHERE vnd_id = " . $vendorId;
						//$recorset5	 = Yii::app()->db->createCommand($qry5)->execute();
						if (!empty($model->vndContact->ctt_pan_doc_id))
						{
							$qry2		 = "UPDATE document SET doc_file_front_path = '" . $path5 . "' WHERE doc_id = " . $model->vndContact->ctt_pan_doc_id;
							$recorset5	 = Yii::app()->db->createCommand($qry2)->execute();
						}
						else
						{
							$modelDocPan->doc_file_front_path	 = $result5['path'];
							$modelDocPan->doc_type				 = 4;
							$modelDocPan->save();
							$qry5								 = "UPDATE contact SET ctt_pan_doc_id = '" . $modelDocPan->doc_id . "' WHERE ctt_id = " . $contact_id;
							$recorset5							 = Yii::app()->db->createCommand($qry5)->execute();
						}

						if ($recorset5)
						{
							$success = true;
							$errors	 = [];
							$eventid = VendorsLog::VENDOR_PAN_UPLOAD;
							$logDesc = $evtList[$eventid];
							VendorsLog::model()->createLog($vendorId, $logDesc, UserInfo::getInstance(), $eventid, false, false);
						}
					}
					if ($licence != '')
					{
						$type			 = 'license';
						$modelDocLicense = new Document();
						$result6		 = Document::model()->saveVendorImage($licence, $licence_tmp, $vendorId, $contact_id, $type);
						//$result6								 = $modelDoc->uploadDocument($model->vnd_contact_id, $type, $memorandum, "");
						//$result6['path']						 = $result6[0];
						$path6			 = str_replace("\\", "\\\\", $result6['path']);
						//$qry6		 = "UPDATE vendors SET vnd_licence_path = '" . $path6 . "' WHERE vnd_id = " . $vendorId;
						//$recorset6	 = Yii::app()->db->createCommand($qry6)->execute();
						if (!empty($model->vndContact->ctt_license_doc_id))
						{
							$qry2		 = "UPDATE document SET doc_file_front_path = '" . $path6 . "' WHERE doc_id = " . $model->vndContact->ctt_license_doc_id;
							$recorset6	 = Yii::app()->db->createCommand($qry2)->execute();
						}
						else
						{
							$modelDocLicense->doc_file_front_path	 = $result6['path'];
							$modelDocLicense->doc_type				 = 5;
							$modelDocLicense->save();
							$qry6									 = "UPDATE contact SET ctt_license_doc_id = '" . $modelDocLicense->doc_id . "' WHERE ctt_id = " . $contact_id;
							$recorset6								 = Yii::app()->db->createCommand($qry6)->execute();
						}

						if ($recorset6)
						{
							$success = true;
							$errors	 = [];
							$eventid = VendorsLog::VENDOR_LICENSE_UPLOAD;
							$logDesc = $evtList[$eventid];
							VendorsLog::model()->createLog($vendorId, $logDesc, UserInfo::getInstance(), $eventid, false, false);
						}
					}
					
					$model->vnd_password		 = '';
					$model->vnd_accepted_zone	 = '';
					$model->vnd_log				 = '';
			}
			catch (Exception $ex)
			{
				
				$msg = $ex->getMessage();
				throw new Exception($msg, ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::create("edit data:" . $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'	 => $success,
							'errors'	 => $errors,
							'data'		 => $model,
							'vnd_id'	 => $vendorId,
						),
			]));
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => $model,
					'vnd_id'	 => $vendorId,
				),
			]);
		});

		$this->onRest('req.post.joining1.render', function () {
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($process_sync_data, true);
			if ($data['name'] != '')
			{
				if ($data['email'] != '')
				{
					$vendorModel = Vendors::model()->resetScope()->find('vnd_email=:email', ['email' => $data['email']]);
				}
				$vendorModel1	 = Vendors::model()->resetScope()->find('vnd_phone=:phone', ['phone' => $data['phone']]);
				$city			 = $data['city'];
				if ($vendorModel1 == '' && $vendorModel == '')
				{
					$cityModel						 = Cities::model()->findByPk($city);
					$model							 = new Vendors();
					$model->vnd_phone				 = $data['phone'];
					$model->vnd_name				 = $data['name'] . "-" . strtolower($cityModel->cty_name) . "-" . $data['company'];
					$model->vnd_owner				 = $data['name'];
					$model->vnd_address				 = $cityModel->cty_name;
					$model->vnd_email				 = $data['email'];
					$model->vnd_phone_country_code	 = $data['countryCode'];
					$model->vnd_company				 = $data['company'];
					$model->vnd_username			 = $data['email'];
					if ($data['email'] == '')
					{
						$model->vnd_username = $data['phone'];
					}
					$chars					 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
					$password				 = substr(str_shuffle($chars), 0, 4);
					$model->vnd_password1	 = $password;
					$model->vnd_is_exclusive = 0;
					$model->vnd_tnc			 = 1;
					$model->vnd_tnc_id		 = 6;
					$model->vnd_tnc_datetime = new CDbExpression('NOW()');
					$model->vnd_device		 = $data['device'];
					$model->vnd_ip_address	 = \Filter::getUserIP();
					$model->vnd_city		 = $city;
					if (isset($city) && $city != '')
					{
						$zoneData				 = Zones::model()->getNearestZonebyCity($city);
						$model->vnd_home_zone	 = $zoneData['zon_id'];
					}
					$model->vnd_active = 3;
					if ($model->save())
					{
						/* @var $modelAgmt VendorAgreement */
						$modelAgmt = VendorAgreement::model()->findByVndId($model->vnd_id);
						if (!$modelAgmt)
						{
							$modelAgmt2				 = new VendorAgreement();
							$modelAgmt2->vag_vnd_id	 = $model->vnd_id;
							$modelAgmt2->vag_active	 = 0;
							$modelAgmt2->save();
						}
						$desc = "New Vendor created";
						VendorsLog::model()->createLog($model->vnd_id, $desc, BookingLog::Vendor, $model->vnd_id, VendorsLog::VENDOR_CREATED, false, false);

						$body	 = "<h3><b>New Vendor Signed Up</b></h3>" .
								"<b>Vendor Name : </b>" . $data['name'] .
								"<br/><b>Company Name : </b>" . $data['company'] .
								"<br/><b>Vendor Phone : </b>" . $data['phone'] .
								"<br/><b>Vendor Email : </b>" . $data['email'] .
								"<br/><b>Vendor City : </b>" . $cityModel->cty_name;
						$mail	 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
						$mail->setLayout('mail');
						$mail->setFrom('info@aaocab.com', 'Info aaocab');
						$mail->setTo(array('team@aaocab.in' => 'Team aaocab', 'info@aaocab.com' => 'Info aaocab'));
						$mail->setBody($body);
						$mail->setSubject("New vendor signed up");
						if ($mail->sendMail())
						{
							$delivered = "Email sent successfully";
						}
						else
						{
							$delivered = "Email not sent";
						}
						$usertype	 = EmailLog::Admin;
						$email1		 = 'team@aaocab.in';
						$email2		 = 'info@aaocab.com';
						$subject	 = 'New vendor signed up';
						$emailObj	 = new emailWrapper();
						$emailObj->createLog($email1, $subject, $body, "", $usertype, $delivered, '', '', '', EmailLog::SEND_ACCOUNT_EMAIL);
						$emailObj->createLog($email2, $subject, $body, "", $usertype, $delivered, '', '', '', EmailLog::SEND_ACCOUNT_EMAIL);
						$emailObj->attachTaxiMail($model->vnd_id, $password);
						$msg		 = "Thanks for your interest in joining Gozo. We have sent you an email with instructions for the next step.";
						$data		 = ['success' => true, 'msg' => $msg];
					}
					else
					{
						$msg	 = "Error occured.";
						$data	 = ['success' => false, 'msg' => $msg];
					}
				}
				else
				{
					if ($vendorModel != '')
					{
						if ($vendorModel->vnd_email != "")
						{
							$chars						 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
							$password					 = substr(str_shuffle($chars), 0, 4);
							$vendorModel->vnd_password1	 = $password;
							$vendorModel->save();
							$emailObj					 = new emailWrapper();
							$emailObj->attachTaxiMail($vendorModel->vnd_id, $password);
						}
					}
					else if ($vendorModel1 != '')
					{
						if ($vendorModel1->vnd_email != "")
						{
							$chars						 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
							$password					 = substr(str_shuffle($chars), 0, 4);
							$vendorModel1->vnd_password1 = $password;
							$vendorModel1->save();
							$emailObj					 = new emailWrapper();
							$emailObj->attachTaxiMail($vendorModel1->vnd_id, $password);
						}
					}
					$msg	 = "Thank you. You have already signed up for our netowrk. We will send you the instructions email again.";
					$data	 = ['success' => true, 'msg' => $msg];
				}
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'data' => $data
				),
			]);
		});

		$this->onRest('req.post.joining.render', function () {
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($process_sync_data, true);
			if ($data['name'] != '')
			{
				if ($data['email'] != '')
				{
					$vendorModel = Vendors::model()->resetScope()->find('vnd_email=:email', ['email' => $data['email']]);
				}
				$vendorModel1 = Vendors::model()->resetScope()->find('vnd_phone=:phone', ['phone' => $data['phone']]);
				if ($vendorModel1 == '' && $vendorModel == '')
				{
					$model							 = new Vendors();
					$model->vnd_phone				 = $data['phone'];
					$model->vnd_name				 = $data['name'] . "-" . $data['city'] . "-" . $data['company'];
					$model->vnd_owner				 = $data['name'];
					$model->vnd_address				 = $data['city'];
					$model->vnd_email				 = $data['email'];
					$model->vnd_phone_country_code	 = $data['countryCode'];
					$model->vnd_company				 = $data['company'];
					$model->vnd_username			 = $data['email'];
					if ($data['email'] == '')
					{
						$model->vnd_username = $data['phone'];
					}
					$chars					 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
					$password				 = substr(str_shuffle($chars), 0, 4);
					$model->vnd_password1	 = $password;
					$model->vnd_is_exclusive = 0;
					$model->vnd_tnc			 = 1;
					$model->vnd_tnc_id		 = 6;
					$model->vnd_tnc_datetime = new CDbExpression('NOW()');
					$model->vnd_device		 = $data['device'];
					$model->vnd_ip_address	 = \Filter::getUserIP();
					$cty_id					 = Cities::model()->getIdByCity($data['city']);
					if ($cty_id != '')
					{
						$model->vnd_city = $cty_id;
					}
					$model->vnd_active = 3;
					if ($model->save())
					{
						$body	 = "<h3><b>New Vendor Signed Up</b></h3>" .
								"<b>Vendor Name : </b>" . $data['name'] .
								"<br/><b>Company Name : </b>" . $data['company'] .
								"<br/><b>Vendor Phone : </b>" . $data['phone'] .
								"<br/><b>Vendor Email : </b>" . $data['email'] .
								"<br/><b>Vendor City : </b>" . $data['city'];
						$mail	 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
						$mail->setLayout('mail');
						$mail->setFrom('info@aaocab.com', 'Info aaocab');
						$mail->setTo(array('team@aaocab.in' => 'Team aaocab', 'info@aaocab.com' => 'Info aaocab'));
						$mail->setBody($body);
						$mail->setSubject("New vendor signed up");
						if ($mail->sendMail())
						{
							$delivered = "Email sent successfully";
						}
						else
						{
							$delivered = "Email not sent";
						}
						$usertype	 = EmailLog::Admin;
						$email1		 = 'team@aaocab.in';
						$email2		 = 'info@aaocab.com';
						$subject	 = 'New vendor signed up';
						$emailObj	 = new emailWrapper();
						$emailObj->createLog($email1, $subject, $body, "", $usertype, $delivered, '', '', '', EmailLog::SEND_ACCOUNT_EMAIL);
						$emailObj->createLog($email2, $subject, $body, "", $usertype, $delivered, '', '', '', EmailLog::SEND_ACCOUNT_EMAIL);
						$emailObj->attachTaxiMail($model->vnd_id, $password);
						$msg		 = "Thanks for your interest in joining Gozo. We have sent you an email with instructions for the next step.";
						$data		 = ['success' => true, 'msg' => $msg];
					}
					else
					{
						$msg	 = "Error occured.";
						$data	 = ['success' => false, 'msg' => $msg];
					}
				}
				else
				{
					if ($vendorModel != '')
					{
						if ($vendorModel->vnd_email != "")
						{
							$chars						 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
							$password					 = substr(str_shuffle($chars), 0, 4);
							$vendorModel->vnd_password1	 = $password;
							$vendorModel->save();
							$emailObj					 = new emailWrapper();
							$emailObj->attachTaxiMail($vendorModel->vnd_id, $password);
						}
					}
					else if ($vendorModel1 != '')
					{
						if ($vendorModel1->vnd_email != "")
						{
							$chars						 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
							$password					 = substr(str_shuffle($chars), 0, 4);
							$vendorModel1->vnd_password1 = $password;
							$vendorModel1->save();
							$emailObj					 = new emailWrapper();
							$emailObj->attachTaxiMail($vendorModel1->vnd_id, $password);
						}
					}
					$msg	 = "Thank you. You have already signed up for our netowrk. We will send you the instructions email again.";
					$data	 = ['success' => true, 'msg' => $msg];
				}
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'data' => $data,
				),
			]);
		});

		$this->onRest('req.post.check_tnc.render', function () {

			Logger::create('3 check_tnc ', CLogger::LEVEL_TRACE);
			//$username			 = Yii::app()->request->getParam('vnd_username');
			//$apt_device_token	 = Yii::app()->request->getParam('apt_device_token');
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$username			 = $data1['vnd_username'];
			$apt_device_token	 = $data1['apt_device_token'];
			//$username = 'ramla@yahoo.com';
			// $apt_device_token ='d6f-lb00VDg:APA91bH0u6VKQTFGJB0v77MhubJ9te0mE-U-JlMTt-0MpTClnCRaPWfUgriRvYQ7hXk66-y_TFLVj9xyVVBKI7jQODcZ9VTU9zkyA4MSeWaUsaA3ntzzdMhfhEhmBQW0N4_69dpjE4xU';
			//$userModel = Vendors::model()->findByEmail($username);
			$userModel			 = Users::model()->getByEmail($username);
			$vendorModel		 = Vendors::model()->find('vnd_user_id=:id', ['id' => $userModel->user_id]);
			if ($userModel != '')
			{
				$result = ['success' => true, 'vnd_tnc' => $vendorModel->vnd_tnc, 'apt_device_token' => $apt_device_token];
			}
			else
			{
				$result = ['success' => false, 'vnd_tnc' => '', 'apt_device_token' => $apt_device_token];
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $result,
			]);
		});

		$this->onRest('req.get.penaltyrate.render', function () {
			$success	 = false;
			Logger::create('16 info details ', CLogger::LEVEL_TRACE);
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result		 = Vendors::model()->authoriseVendor($token);
			$returnSet	 = array('success' => $success, 'errors' => 'Vendor Unauthorised');
			if ($result == true)
			{
				$penaltyArray	 = [];
				$reasons		 = Yii::app()->params['PenaltyReason'];
				$amount			 = Yii::app()->params['PenaltyAmount'];
				$ctr			 = 0;
				foreach ($reasons as $r)
				{
					$penaltyArray[$ctr]['reason'] = $r;
					$ctr++;
				}
				$success	 = true;
				$returnSet	 = array('success' => $success, 'data' => $penaltyArray);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet
			]);
		});

		$this->onRest('req.get.infoDetails.render', function () {

			Logger::create('16 info details ', CLogger::LEVEL_TRACE);
			$success = false;
			$errors	 = 'Something went wrong';
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Vendors::model()->authoriseVendor($token);
			if ($result == true)
			{
				$vendorId	 = UserInfo::getEntityId();
				$model		 = Vendors::model()->infoDetails($vendorId);
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
					'data'		 => $model,
				)
			]);
		});

		/**
		 * @deprecated 03/12/2019
		 */
		$this->onRest('req.post.forgotpass.render', function () {

			Logger::create('14 forgot password ', CLogger::LEVEL_TRACE);
			/* $forgot_email	 = Yii::app()->request->getParam('forgotemail');
			  $code			 = Yii::app()->request->getParam('code');
			  $email			 = Yii::app()->request->getParam('email');
			  $newPassword	 = Yii::app()->request->getParam('newPassword'); */
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$forgot_email		 = $data1['forgotemail'];
			$code				 = $data1['code'];
			$email				 = $data1['email'];
			$newPassword		 = $data1['newPassword'];
			$status				 = false;
			if ($code != "" && $email != "")
			{

				$forgotPass	 = Users::model()->forgotPassword($email, $code, $newPassword);
				$message	 = $forgotPass['message'];
				$status		 = $forgotPass['status'];
				$user_id	 = $forgotPass['user_id'];
			}
			else
			{
				$status = $this->forgotPassword();
				if ($status)
				{
					$message = "code successfully sent to the given email ID.";
				}
				else
				{
					$message = "error sending message.";
				}
			}


			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => ['success' => $status, 'message' => $message, 'user_id' => $user_id]
			]);
		});

		$this->onRest('req.post.newpassword.render', function () {

			Logger::create('15 new password ', CLogger::LEVEL_TRACE);
			//$newPassword = Yii::app()->request->getParam('new_password');
			//$userId		 = Yii::app()->request->getParam('user_id');
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$newPassword		 = $data1['new_password'];
			$userId				 = $data1['user_id'];
			$success			 = false;
			$model				 = Users::model()->findByPk($userId);
			if ($model != '')
			{
				$model->usr_password = md5($newPassword);
				if ($model->update())
				{
					$success = true;
					$message = "password changed successfully.";
				}
				else
				{
					$success = false;
					$message = "error occured while changing password.";
				}
			}
			else
			{
				$success = false;
				$message = "error occured while changing password.";
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => ['success' => $success, 'message' => $message, 'errors' => $errors]
			]);
		});

		$this->onRest('req.post.upgradeTire.render', function () {
			Logger::create('upgradeTire Info ', CLogger::LEVEL_TRACE);
			$token				 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result				 = Vendors::model()->authoriseVendor($token);
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($process_sync_data, true);
			$tier				 = $data['tire'];
			$returnSet			 = ['success' => false, 'errors' => 'Unauthorized user', 'errorCode' => 401];
			Logger::create("Request Data : " . json_encode($data) . " Vendor Id : " . UserInfo::getEntityId(), CLogger::LEVEL_INFO);
			if ($result == true)
			{
				$userInfo	 = UserInfo::getInstance();
				Logger::create("User Data : " . json_encode($userInfo) . " User Type : " . UserInfo::getUserType(), CLogger::LEVEL_INFO);
				$vendorId	 = UserInfo::getEntityId();
				$returnSet	 = Vendors::model()->updateTire($vendorId, $tier, $userInfo);
			}
			Logger::create("Response Data : " . json_encode($returnSet), CLogger::LEVEL_INFO);
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet
			]);
		});

		$this->onRest('req.post.bookingCount.render', function () {
			$returnSet	 = new ReturnSet();
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result		 = Vendors::model()->authoriseVendor($token);
			try
			{
				if ($result)
				{
					$vendorID			 = Yii::app()->user->getEntityId();
					$data				 = array();
					$totalBookings		 = count(BookingVendorRequest::model()->getRequestedListNew1($vendorID, '', 0, 0, '', null, 2));
					$drvAssignmentPend	 = VendorStats::getDriverAssignmentPending($vendorID);
					$overDueCount		 = VendorStats::getBookingOverDue($vendorID);
					$data				 = ['total_bookings' => $totalBookings, 'driver_assignment_pending' => $drvAssignmentPend, 'overdue_booking_count' => $overDueCount];
					$returnSet->setStatus(true);
					$returnSet->setData($data);
				}
				else
				{
					throw new Exception("Unauthorised Vendor", $returnSet::UNAUTHORISED_VENDOR);
				}
			}
			catch (Exception $ex)
			{
				$returnSet = $returnSet->setException($ex);
			}

			return $this->renderJSON([
				'data' => $returnSet]);
		});

		$this->onRest('req.post.updateLastLocationNew.render', function () {
			return $this->renderJSON($this->updateLastLocationNew());
		});
	}

	// vendor login new
	public function loginvendor()
	{
		$email			 = Yii::app()->request->getParam('username');
		$password		 = Yii::app()->request->getParam('password');
		$deviceID		 = Yii::app()->request->getParam('deviceid');
		$deviceVersion	 = Yii::app()->request->getParam('version');
		$apkVersion		 = Yii::app()->request->getParam('apk_version');
		$ipAddress		 = \Filter::getUserIP();
		$deviceInfo		 = Yii::app()->request->getParam('device_info');
		$appDeviceToken	 = Yii::app()->request->getParam('apt_device_token');
		// $email          = 'ramla@yahoo.com';
		// $password       = 1234;
		// $type = Yii::app()->request->getParam('vnd_type', '0');
		$requestParams	 = "Email : " . $email . " Password : " . $password . " DeviceId : " . $deviceID . " DeviceVersion : " . $deviceVersion . " ApkVersion : " . $apkVersion . " IpAddress : " . $ipAddress . " DeviceInfo : " . $deviceInfo . " AppDeviceToken : " . $appDeviceToken;
		Logger::create("Request params :: " . $requestParams, CLogger::LEVEL_TRACE);

		$identity = new UserIdentity($email, md5($password));

		//Logger::create("Identity :: ".json_encode($identity), CLogger::LEVEL_TRACE);
		if ($identity->authenticate())
		{
			$userID		 = $identity->getId();
			Logger::create("Request params :: " . $userID, CLogger::LEVEL_TRACE);
			//echo $vendor_id	 = $identity->entityId;
			//exit;
			//Drivers::model()->resetScope()->findById($drvId);
			$userModel	 = Vendors::model()->resetScope()->find('vnd_user_id=:userID AND vnd_active IN(1,3)', ['userID' => $userID]);
			$identity->setEntityID($userModel->vnd_id);
			$vendor_id	 = $identity->entityId;

			if ($userModel->vnd_tnc == 0)
			{
				$userModel->vnd_tnc_datetime = new CDbExpression('NOW()');
				$userModel->vnd_tnc			 = 1;
				$tmodel						 = Terms::model()->getText(5);
				$userModel->vnd_tnc_id		 = $tmodel->tnc_id;
				$userModel->scenario		 = 'updatetnc';
				$result						 = CActiveForm::validate($userModel);

				if ($result == '[]')
				{
					$userModel->save();
				}
			}



			/* @var $webUser GWebUser */
			$webUser = Yii::app()->user;
			$webUser->login($identity);
			$webUser->setUserType(UserInfo::TYPE_CONSUMER);

			//$sessionId	 = Yii::app()->getSession()->getSessionId();
			$sessionId	 = Yii::app()->getSession()->getSessionId();
			$session_arr = explode(",", $sessionId);
			//$sessionId = $session_arr[0];
			if (count($session_arr) > 1)
			{
				$sessionId = $session_arr[0];
			}
			else
			{
				$sessionId = Yii::app()->getSession()->getSessionId();
			}
			// exit;
			$appToken = AppTokens::model()->findAll('apt_device_uuid=:device', array('device' => $deviceID));

			foreach ($appToken as $app)
			{
				if (count($app) > 0)
				{
					$app->apt_status = 0;
					$app->update();
				}
			}
			if ($appDeviceToken != '')
			{
				$appToken1 = AppTokens::model()->find('apt_device_token=:device', array('device' => $appDeviceToken));
				if (count($appToken1) > 0)
				{
					$appToken1->apt_status = 0;
					$appToken1->update();
				}
			}
			$appTokenModel					 = new AppTokens();
			$appTokenModel->apt_user_id		 = $webUser->getId();
			$appTokenModel->apt_entity_id	 = $webUser->getEntityID();
			$appTokenModel->apt_token_id	 = $sessionId;
			$appTokenModel->apt_device		 = $deviceInfo;
			$appTokenModel->apt_last_login	 = new CDbExpression('NOW()');
			$appTokenModel->apt_device_uuid	 = $deviceID;
			$appTokenModel->apt_user_type	 = 2;
			$appTokenModel->apt_apk_version	 = $apkVersion;
			$appTokenModel->apt_ip_address	 = $ipAddress;
			$appTokenModel->apt_os_version	 = $deviceVersion;
			$appTokenModel->apt_device_token = $appDeviceToken;

			$appTokenModel->insert();
			Yii::log('vendor login ' . json_encode($appTokenModel), CLogger::LEVEL_INFO);

			$success = true;
		}
		else
		{
			//Logger::create("Invalid Username/Password111" , CLogger::LEVEL_TRACE);
			$success = false;
			$msg	 = "Invalid Username/Password";
		}
		return $success;
	}

	public function generateAgreementPdf()
	{
		echo "aaaaaaaaaaaaaaaaaaa";
		exit();
	}

	public function changeVendorPassword()
	{
		//$userId = Yii::app()->user->getId();
		$userId = UserInfo::getUserId();
		// $userId2 = Yii::app()->request->getParam('userid');

		$model = Users::model()->findByPk($userId);

		//$oldPassword = Yii::app()->request->getParam('old_password');
		//$newPassword = Yii::app()->request->getParam('new_password');
		//$rePassword = Yii::app()->request->getParam('repeat_password');
		$process_sync_data	 = Yii::app()->request->getParam('data');
		$data1				 = CJSON::decode($process_sync_data, true);
		$oldPassword		 = $data1['old_password'];
		$newPassword		 = $data1['new_password'];
		$rePassword			 = $data1['repeat_password'];

		$result = Users::model()->changePassword($userId, $oldPassword, $newPassword, $rePassword);

		return $result;
	}

	/* =============================================================================================== */

	public function login($data)
	{
		$model				 = new Vendors('login');
		$model->attributes	 = $data;
		$email				 = $model->vnd_username;
		$password			 = md5($model->vnd_password1);
		$identity			 = new VendorIdentity($email, $password);
		if ($identity->authenticate())
		{
			$userID							 = $identity->getId();
			Yii::app()->user->login($identity);
			$sessionId						 = Yii::app()->getSession()->getSessionId();
			$appTokenModel					 = new AppTokens();
			$appTokenModel->attributes		 = $data;
			$appTokenModel->apt_user_id		 = $userID;
			$appTokenModel->apt_token_id	 = $sessionId;
			$appTokenModel->apt_ip_address	 = \Filter::getUserIP();
			$appTokenModel->apt_last_login	 = new CDbExpression('NOW()');
			$appTokenModel->apt_user_type	 = 2;
			$appTokenModel->insert();
			$success						 = true;
			$loginData						 = ['sessionId' => $sessionId, 'model' => JSONUtil::convertModelToArray(Vendors::model()->findByPk($userID))];
		}
		else
		{
			$success	 = false;
			$loginData	 = ['errors' => ['error' => ["Failed to login"]]];
		}
		$result = ['success' => $success] + $loginData;

		return $result;
	}

	public function changePassword()
	{
		$userId					 = Yii::app()->user->getId();
		$model					 = Vendors::model()->findByPk($userId);
		$oldPassword			 = $data['old_password'];
		$newPassword			 = $data['new_password'];
		$rePassword				 = $data['repeat_password'];
		$model->old_password	 = $oldPassword;
		$model->new_password	 = $newPassword;
		$model->repeat_password	 = $rePassword;
		$success				 = false;
		if ($model->validate())
		{
			$model->scenario = 'change';
			if ($model->vnd_password == md5($model->old_password))
			{
				$model->vnd_password = md5($model->new_password);
				if ($model->save())
				{
					$success = true;
					$message = 'Password Changed';
				}
				else
				{
					$success = false;
					$message = 'Password Not Changed';
					$errors	 = $model->getErrors();
				}
			}
			else
			{
				$success = false;
				$message = 'Old Password not matching';
				$errors	 = $model->getErrors();
			}
		}

		$result = array(
			'message'	 => $message,
			'success'	 => $success,
			'errors'	 => $errors);
		return $result;
	}

	/* =============================================================================================== */

	public function getValidationApp($data, $id)
	{
		if ($id != '')
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
		$result = array('active' => $active, 'success' => $success, 'message' => $msg);
		return $result;
	}

	public function forgotPassword()
	{
		$email	 = Yii::app()->request->getParam('forgotemail');
		//$users	 = Vendors::model()->find("vnd_email=:email", ['email' => $email]);
		$users	 = Users::model()->find("usr_email=:email", ['email' => $email]);

		$vendors = Vendors::model()->find("vnd_user_id=:user_id", ['user_id' => $users->user_id]);
		if (count($users) > 0)
		{
			$username	 = $users->usr_name;
			$code		 = rand(999, 9999);

			$body	 = "<p>Please copy paste this code  <span style='color: #000000;font-weight:bold'>##CODE##</span> to reset password of your aaocab Vendor Account.</p><br><br>";
			$mail	 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
			$body	 = str_replace("##CODE##", $code, $body);
			$mail->setLayout('mail');
			$mail->setTo($email, $username);
			$mail->setBody($body);
			$mail->isHTML(true);
			$mail->setSubject('Reset your Password');
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$smsWrapper	 = new smsWrapper();
			//$phone		 = $users->vnd_phone;
			$phone		 = $users->usr_mobile;

			if ($smsWrapper->sendForgotPassCodeVendor('91', $phone, $code))
			{
				$delivered1 = "Message sent successfully";
			}
			else
			{
				$delivered1 = "Message not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Vendor;
			$subject	 = 'Reset your Password';
			//$refId						 = $users->vnd_id;

			$refId							 = $users->user_id;
			$refType						 = EmailLog::REF_VENDOR_ID;
			emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
			//echo $users->user_id;
			//$vendors = Vendors::model()->findByPk($vendors['attributes']['vnd_id']);
			// add data in user table
			$users->usr_verification_code	 = $code;
			if ($users->update())
			{
				$status = true;
			}
			else
			{
				$status = false;
			}
		}
		else
		{
			$status = false;
		}

		return $status;
	}

	function img_resize($tmpname, $size, $save_dir, $save_name, $maxisheight = 0)
	{
		$arr		 = array();
		$save_dir	 .= ( substr($save_dir, -1) != DIRECTORY_SEPARATOR) ? DIRECTORY_SEPARATOR : "";
		$arr[1]		 = $save_dir;
		$gis		 = getimagesize($tmpname);
		$arr[21]	 = $tmpname;
		$type		 = $gis[2];
		$arr[2]		 = $gis;
		switch ($type)
		{
			case "1": $imorig	 = imagecreatefromgif($tmpname);
				break;
			case "2": $imorig	 = imagecreatefromjpeg($tmpname);
				break;
			case "3": $imorig	 = imagecreatefrompng($tmpname);
				break;
			default: $imorig	 = imagecreatefromjpeg($tmpname);
		}

		$x	 = imagesx($imorig);
		$y	 = imagesy($imorig);

		$woh = (!$maxisheight) ? $gis[0] : $gis[1];

		if ($woh <= $size)
		{
			$aw	 = $x;
			$ah	 = $y;
		}
		else
		{
			if (!$maxisheight)
			{
				$aw	 = $size;
				$ah	 = $size * $y / $x;
			}
			else
			{
				$aw	 = $size * $x / $y;
				$ah	 = $size;
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

	public function editVendorDetails($data1, $processFile1, $processFile2, $returnSet)
	{
		$returnSet->setStatus(true);
		$data1				 = CJSON::decode($data1, true);
		$agmt_file1_img_no	 = $agmt_file2_img_no	 = 0;
		$agmt_file1_img_no	 = CJSON::decode($processFile1, true);
		$agmt_file2_img_no	 = CJSON::decode($processFile2, true);
		Logger::create("file no1: " . $agmt_file1_img_no, CLogger::LEVEL_TRACE);
		Logger::create("file no2: " . $agmt_file2_img_no, CLogger::LEVEL_TRACE);
		$photo				 = $_FILES['photo']['name'];
		$photo_tmp			 = $_FILES['photo']['tmp_name'];
		$agmt1				 = $_FILES['agreement1']['name'];
		$agmt1_tmp			 = $_FILES['agreement1']['tmp_name'];
		$agmt2				 = $_FILES['agreement2']['name'];
		$agmt2_tmp			 = $_FILES['agreement2']['tmp_name'];
		$agmt_file			 = $_FILES['agreement_file']['name'];
		$agmt_file_tmp		 = $_FILES['agreement_file']['tmp_name'];
		$vendorPic			 = $data1['vendorPic'];
		$agmt_req_id		 = $data1['req_id'];
		$total_agmt_img_no	 = $data1['total_img_no'];
		$doc_type			 = $data1['doc_type'];
		$doc_subtype		 = $data1['doc_subtype'];
		$vendorId			 = UserInfo::getEntityId();
		$evtList			 = VendorsLog::model()->eventList();
		$userInfo			 = UserInfo::getInstance();
		$userInfo->userType	 = UserInfo::TYPE_VENDOR;
		$model				 = Vendors::model()->findByPk($vendorId);
		$oldData			 = Vendors::model()->getDetailsbyId($vendorId);
		$contact_id			 = $oldData['vnd_contact_id'];
		$contactModel		 = Contact::model()->findByPk($contact_id);
		if ($vendorPic == 0)
		{
			$model->scenario	 = 'update';
			$model->attributes	 = $data1;
			if (!$model->validate())
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("Vendor update failed.\n\t\t " . json_encode($errors));
				return ["returnSet" => $returnSet, 'model' => $model, 'vendorId' => $vendorId, 'agmt_file1_img_no' => $agmt_file1_img_no, 'agmt_file2_img_no' => $agmt_file2_img_no];
			}
			$contactModel->scenario		 = 'update';
			$data1['ctt_address']		 = $data1['data']['vnd_address'];
			$contactModel->attributes	 = $data1;
			if (!$contactModel->save())
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors($contactModel->getErrors());
				return ["returnSet" => $returnSet, 'model' => $model, 'vendorId' => $vendorId, 'agmt_file1_img_no' => $agmt_file1_img_no, 'agmt_file2_img_no' => $agmt_file2_img_no];
			}
			if (!$model->save())
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors($model->getErrors());
				return ["returnSet" => $returnSet, 'model' => $model, 'vendorId' => $vendorId, 'agmt_file1_img_no' => $agmt_file1_img_no, 'agmt_file2_img_no' => $agmt_file2_img_no];
			}
			$newData			 = Vendors::model()->getDetailsbyId($vendorId);
			$getOldDifference	 = array_diff_assoc($oldData, $newData);
			$changesForLog		 = " Old Values: " . Vendors::model()->getModificationMSG($getOldDifference);
			$eventId			 = VendorsLog::VENDOR_EDIT;
			$logDesc			 = $evtList[$eventId];
			$desc				 = $logDesc . $changesForLog;
			VendorsLog::model()->createLog($vendorId, $desc, $userInfo, $eventId, false, false);
		}
		else if ($vendorPic == 1)
		{
			if ($agmt1 != '' || $agmt2 != '')
			{
				$recordset	 = '';
				$recordset	 = VendorAgmtDocs::model()->updateVendorAgreement($agmt1, $agmt1_tmp, $vendorId, $agmt_file1_img_no, $agmt_req_id, $agmt2, $agmt2_tmp, $agmt_file2_img_no, $total_agmt_img_no);

				if ($recordset)
				{
					$updateCount = VendorAgmtDocs::model()->updateStatusByVndReqId($vendorId, $agmt_req_id, $total_agmt_img_no);
					if (!$updateCount > 0)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors("Document images( Agreement ) creation failed.\n\t\t");
						return ["returnSet" => $returnSet, 'model' => $model, 'vendorId' => $vendorId, 'agmt_file1_img_no' => $agmt_file1_img_no, 'agmt_file2_img_no' => $agmt_file2_img_no];
					}
					$modelDigital				 = VendorAgreement::model()->findByVndId($vendorId);
					$modelDigital->vag_soft_flag = 2;
					if (!$modelDigital->save())
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors($modelDigital->getErrors());
						return ["returnSet" => $returnSet, 'model' => $model, 'vendorId' => $vendorId, 'agmt_file1_img_no' => $agmt_file1_img_no, 'agmt_file2_img_no' => $agmt_file2_img_no];
					}
				}
			}
			if ($photo != '')
			{
				$type							 = 'profile';
				$result2						 = Document::model()->saveVendorImage($photo, $photo_tmp, $vendorId, $model->vnd_contact_id, $type);
				$contactModel->scenario			 = 'update';
				$contactModel->ctt_profile_path	 = $result2['path'];
				if (!$contactModel->save())
				{
					$returnSet->setStatus(false);
					$returnSet->setErrors($contactModel->getErrors());
					return ["returnSet" => $returnSet, 'model' => $model, 'vendorId' => $vendorId, 'agmt_file1_img_no' => $agmt_file1_img_no, 'agmt_file2_img_no' => $agmt_file2_img_no];
				}
				$errors	 = [];
				$eventid = VendorsLog::VENDOR_PROFILE_UPLOAD;
				$logDesc = $evtList[$eventid];
				VendorsLog::model()->createLog($vendorId, $logDesc, UserInfo::getInstance(), $eventid, false, false);
			}
			if ($doc_type != '')
			{
				$success = Document::model()->updateVendorDoc($model, $photo, $photo_tmp, $doc_type, $doc_subtype);
				if (!$success->getStatus())
				{
					$errors = $success->getErrors();
//					throw new Exception($getErrors);
					$returnSet->setStatus(false);
					$returnSet->setErrors($errors);
					return ["returnSet" => $returnSet, 'model' => $model, 'vendorId' => $vendorId, 'agmt_file1_img_no' => $agmt_file1_img_no, 'agmt_file2_img_no' => $agmt_file2_img_no];
				}
			}
		}
		Logger::create("Return Data =>" . json_encode(["returnSet" => $returnSet, 'model' => $model, 'vendorId' => $vendorId, 'agmt_file1_img_no' => $agmt_file1_img_no, 'agmt_file2_img_no' => $agmt_file2_img_no]), CLogger::LEVEL_TRACE);
		return ["returnSet" => $returnSet, 'model' => $model, 'vendorId' => $vendorId, 'agmt_file1_img_no' => $agmt_file1_img_no, 'agmt_file2_img_no' => $agmt_file2_img_no];
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function signIn()
	{
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		Logger::trace("<===Request===>" . $data);

		$transaction = DBUtil::beginTransaction();
		try
		{
			/* @var $usrInfo UserInfo */
			#$usrInfo = UserInfo::getInstance();
			$remarks = "This app has been discontinued. Please install & use Gozo Partner+ app from Google play store.";
			throw new Exception($remarks, ReturnSet::ERROR_UNAUTHORISED);
			if (!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}

			if (!empty($token))
			{
				$deactivateToken = " UPDATE app_tokens SET apt_status = 0 WHERE apt_token_id = '$token'";
				DBUtil::command($deactivateToken)->execute();
			}

			//Logger::create("Request : " . $data, CLogger::LEVEL_INFO);
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			/** @var \Stub\common\Auth $obj */
			$obj		 = $jsonMapper->map($jsonObj, new \Stub\common\Auth());
			$authModel	 = $obj->getSocialModel();

			$userModel = $authModel->getUserModel();
			if (!$userModel)
			{
				$userModel = Users::login($authModel, true);
			}

			if (!$userModel)
			{
				throw new Exception("Unable to authenticate user", ReturnSet::ERROR_FAILED);
			}

			$deviceData	 = $obj->device;
			$data		 = Vendors::model()->vendorLogin($userModel, $deviceData);

			if ($data['isActive'] == 2)
			{
				$remarks = "Your account is blocked. Please contact the vendor team for any support";
				throw new Exception($remarks, ReturnSet::ERROR_UNAUTHORISED);
			}
			$contactModel	 = Contact::model()->findByPk($data['vendor_contact_id']);
			$authResponse	 = new \Stub\common\Business();
			$authResponse->setBusinessdata($userModel, $data, $contactModel);

			$response = Filter::removeNull($authResponse);
			if ($response)
			{
				$returnSet->setStatus(true);
				$returnSet->setData($response);
				Logger::trace("<===Response===>" . json_encode($response));
			}

			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}

		return $returnSet;
	}

	/**
	 * This function is used for verifying the user with there email and phone number as 
	 * request parameter.
	 * 
	 * @return type
	 * @throws Exception
	 */
	public function validateUser()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;

		try
		{
			if (empty($data))
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}

			$jsonObj = CJSON::decode($data, false);

			$row = Vendors::getByEmailPhone($jsonObj->email, $jsonObj->primaryContact->number);
//                        echo $jsonObj->email;
//			$getrow	 = Contact::getByEmail($jsonObj->email, $jsonObj->primaryContact->number);
//                        foreach ($getrow as $value)
//		{
//			echo $ctt_id[] = $value['ctt_id'];
//		}

			if (!$row)
			{
				//$returnSet->setStatus(false);
				//$returnSet->setMessage("Account not linked with given contact details");
				//goto skipAll;
				//$msg = "Account not linked with given contact details";
				$msg = "Sorry, the provided email address and phone number do not match any partner account registered with us. Please double-check your information or contact support for assistance.";
				throw new Exception("Sorry, the provided email address and phone number do not match any partner account registered with us. Please double-check your information or contact support for assistance.", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			if ($row["eml_is_verified"] != 1 || $row["phn_is_verified"] != 1)
			{
				$msg = "You haven't verified your email/phone number. Please verify your account details to link your social account";
				throw new Exception("You haven't verified your email/phone number. Please verify your account details to link your social account", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			$vendorId = $row['vndId'];
			if (!empty($jsonObj->userotp))
			{
				goto skipOTPCreate;
			}
			$linkotp = VendorPref::model()->sendLinkOtp($vendorId);

			if (empty($linkotp))
			{
				$msg = "No Data Found";
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			//Send OTP in Email
			$emailModel = new emailWrapper();
			$emailModel->SendLinkOtp($vendorId, $jsonObj->email, $linkotp);

			//whatsapplog
//			$response = WhatsappLog::attachVendorSocialAccount($vndId[0]['vnd_id'], $phoneno, $linkotp);
//			if($response['status'] == 3)
//			{
				//Send OTP in SMS
				$smsModel = new smsWrapper();
				$smsModel->sendLinkOtp($vendorId, $jsonObj->primaryContact->number, $jsonObj->primaryContact->code, $linkotp);
//			}

			$returnSet->setStatus(true);
			$returnSet->setMessage("OTP send Successfully");
			goto skipAll;

			skipOTPCreate:
			//Verify OTP of the user
			$linkStatus = VendorPref::model()->verifyUserLinkByOTP($vendorId, $jsonObj->userotp);
			if (!$linkStatus["success"])
			{
				$returnSet->setStatus(false);
				$returnSet->setMessage("Incorrect OTP");
				goto skipAll;
			}

			$jsonMapper	 = new JsonMapper();
			/** @var \Stub\vendor\AuthRequest $obj */
			$obj		 = $jsonMapper->map($jsonObj, new \Stub\common\Auth());
			$deviceData	 = $obj->device;
			$aptModel	 = AppTokens::addToken($vendorId, $deviceData);

			$result = [
				"vndId"		 => (int) $vendorId,
				"sessionId"	 => $aptModel->getData()
			];

			$returnSet->setStatus(true);
			$returnSet->setData($result);
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		skipAll:
		if ($msg != "")
		{
			$returnSet->setMessage($msg);
		}
		return $returnSet;
	}

	/**
	 *
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function vendorInfoDetails()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$result		 = Vendors::model()->authoriseVendor($token);
		try
		{
			$vendorId	 = UserInfo::getEntityId();
			$userId		 = UserInfo::getUserId();
			if (!$userId)
			{
				throw new Exception("Invalid User Id.", ReturnSet::ERROR_UNAUTHORISED);
			}
			if (!$vendorId)
			{
				throw new Exception("Invalid Vendor Id.", ReturnSet::ERROR_UNAUTHORISED);
			}
			$model = Vendors::model()->infoDetails($vendorId);
			if (!$model)
			{
				throw new Exception("No Record Found.", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			/** @var \Stub\vendor\InfoResponse $obj */
			$response	 = new \Stub\vendor\InfoResponse();
			$response->setData($model, $vendorId);
			$response	 = Filter::removeNull($response);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function regUserLinking()
	{

		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;

		Logger::create('POST DATA =====>: ' . $data, CLogger::LEVEL_TRACE);

		if (!$data)
		{
			throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
		}
		$transaction = DBUtil::beginTransaction();
		try
		{
			$jsonObj = CJSON::decode($data, false);
			$vndId	 = Contact::model()->linkContactId($jsonObj->email, $jsonObj->primaryContact->number);
			if (!$vndId)
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			else if (count($vndId) > 1)
			{
				throw new Exception("Already linked with other user.", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				#$returnSet->setMessage("");
			}
			else
			{
				if ($jsonObj->userotp != "")
				{
					$linkStatus = VendorPref::model()->verifyUserLinkByOTP($vndId[0]['vnd_id'], $jsonObj->userotp);

					if ($linkStatus['success'])
					{
						DBUtil::commitTransaction($transaction);
						$returnSet->setStatus(true);
						$data = ['vndId' => (int) $vndId[0]['vnd_id']];

						$returnSet->setStatus(true);
						$returnSet->setData($data);
					}
					else
					{
						$returnSet->setStatus(false);
						$returnSet->setMessage("Incorrect OTP");
					}
				}
				else
				{
					$linkotp = VendorPref::model()->sendLinkOtp($vndId[0]['vnd_id']);
					DBUtil::commitTransaction($transaction);
					if ($linkotp > 0)
					{
						$emailModel	 = new emailWrapper();
						$emailModel->SendLinkOtp($vndId[0]['vnd_id'], $jsonObj->email, $linkotp);
						//whatsapplog
//						$response = WhatsappLog::attachVendorSocialAccount($vndId[0]['vnd_id'], $phoneno, $linkotp);
//						if($response['status'] == 3)
//						{
							$smsModel	 = new smsWrapper();
							$smsModel->sendLinkOtp($vndId[0]['vnd_id'], $jsonObj->primaryContact->number, $jsonObj->primaryContact->code, $linkotp);
//						}
						$success	 = true;
						$returnSet->setStatus(true);
						$returnSet->setMessage("OTP send Successfully");
					}
					else
					{
						throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
					}
				}
			}
		}
		catch (Exception $ex)
		{
			$returnSet = $returnSet->setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	public function updateTncDetails()
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$vendorId	 = UserInfo::getEntityId();
			$userId		 = UserInfo::getUserId();
			if (!$vendorId)
			{
				throw new Exception("Unauthorised Vendor.", ReturnSet::ERROR_UNAUTHORISED);
			}
			if (!$userId)
			{
				throw new Exception("Unauthorised User.", ReturnSet::ERROR_UNAUTHORISED);
			}
			$jsonObj				 = CJSON::decode($data, true);
			$jsonObj['new_tnc_id']	 = $jsonObj['newTncId'];
			$response				 = Vendors::model()->updatetnc($jsonObj, $vendorId);

			if (!$response)
			{
				throw new Exception("Unable to Saved Data. ", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$returnSet->setData(['vendorTncId' => $response['vnd_tnc_id'], 'vendorTnc' => $response['vnd_tnc']]);
			$returnSet->setMessage("Saved Data.");
			$returnSet->setStatus(true);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
			DBUtil::rollbackTransaction($transaction);
		}
		return $returnSet;
	}

	public function socialLinking()
	{
		$returnSet	 = new ReturnSet();
		$vndId		 = Yii::app()->request->getParam('vndId');
		$data		 = Yii::app()->request->getParam('data');
		if (!$vndId || !$data)
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
			$token		 = $obj->accessToken;
			$contactId	 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
			$userId		 = ContactProfile::getUserId($contactId);
			$flag1		 = 'vendor-app';
			if ($contactId == "")
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_FAILED);
			}

			if ($userId != '')
			{
				$oAuthModel = $authModel->linkUser($token, $userId);
			}
			else
			{
				$oAuthModel = $authModel->linkContact($token, $contactId);
			}

			if (!$oAuthModel)
			{
				throw new Exception("Failed to link social account", ReturnSet::ERROR_FAILED);
			}

			$returnSet->setStatus(true);
			$returnSet->setMessage("Account Linked Successfully");
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}

		return $returnSet;
	}

	/**
	 * This function is used for vendor social linking
	 * @return type
	 * @throws Exception
	 */
	public function socialLink()
	{
		$returnSet	 = new ReturnSet();
		$transaction = DBUtil::beginTransaction();
		try
		{
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$data	 = Yii::app()->request->rawBody;
			//Logger::trace("Vendor Id*********" . $data);

			/* @var $usrInfo UserInfo */
			$usrInfo = UserInfo::getInstance();

			if (empty($data))
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}

			$appToken = AppTokens::model()->getByToken($token);

			if (!$appToken)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$appToken->apt_last_login = new CDbExpression('NOW()');
			$appToken->save();

			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);

			/** @var \Stub\common\Auth $obj */
			$obj = $jsonMapper->map($jsonObj, new \Stub\common\Auth());

			$authModel = $obj->getSocialModel();

			$vendorModel = Vendors::model()->resetScope()->find('vnd_id=:id AND vnd_active>0', ['id' => $appToken->apt_entity_id]);

			$contactId = ContactProfile::getByEntityId($vendorModel->vnd_id, UserInfo::TYPE_VENDOR);

			$oAuthModel = $authModel->linkContact($obj->accessToken, $contactId);

			$appToken->apt_user_id	 = $oAuthModel->user_id;
			$sucess					 = $appToken->save();
			if (!$sucess)
			{
				throw new Exception(json_encode($appToken->getErrors), ReturnSet::ERROR_VALIDATION);
			}

			$returnSet->setStatus(true);
			$returnSet->setMessage("Social account linked successfully");
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
			DBUtil::rollbackTransaction($transaction);
			Logger::create("Errors : " . json_encode($returnSet), CLogger::LEVEL_INFO);
		}
		Logger::trace("<===Response===>" . json_encode($returnSet));
		return $returnSet;
	}

	/**
	 * 
	 * @return array
	 * @throws Exception
	 */
	public function validateApp()
	{

		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		try
		{
			if (!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);
			/* @var $obj Stub\common\Platform */
			$obj		 = $jsonMapper->map($jsonObj, new \Stub\common\Platform());

			/* @var $userModel AppTokens */
			$userModel		 = $obj->getAppToken();
			$vendorId		 = \UserInfo::getEntityId();
			$userId			 = \UserInfo::getUserId();
			$activeVersion	 = Config::get("Version.Android.vendor"); ////Yii::app()->params['versionCheck']['vendor']; // coming from common.php

			/* @var $resultSet AppTokens */

			//$resultSet	 = AppTokens::model()->verify($userModel->apt_token_id, $userModel->apt_apk_version,$userId,$activeVersion);
			$resultSet = AppTokens::model()->verifiApp($userModel, $userId, $activeVersion);
			if ($resultSet['success'] == true)
			{
				$tokenModel					 = AppTokens::model()->getByToken($userModel->apt_token_id);
				$tokenModel->apt_last_login	 = new CDbExpression('NOW()');
				if (!$tokenModel->save())
				{
					throw new Exception("Unable to save. ", ReturnSet::ERROR_VALIDATION);
				}
			}

			$model			 = Vendors::model()->findByPk($vendorId);
			/* @var $authResponse Stub\vendor\ValidateResponse */
			$authResponse	 = new \Stub\vendor\ValidateResponse();
			$authResponse->setData($userModel, $model, $userId, $resultSet);
			$response		 = Filter::removeNull($authResponse);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
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
	 * @return type
	 * @throws Exception
	 */
	public function registerToFcm()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody;
			if (!$data)
			{
				throw new Exception("Invalid Request.", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);

			/* @var $obj JsonMapper */
			$obj		 = $jsonMapper->map($jsonObj, new \Stub\common\Platform());
			/* @var $userModel AppTokens */
			$userModel	 = $obj->getAppToken();
			$userInfo	 = UserInfo::getInstance();
			$checkFcm	 = true;
			#$webUser	 = Yii::app()->user;
			/* @var $model AppTokens */
			$model		 = AppTokens::Add($userInfo->getUserId(), 2, $userInfo->getEntityId(), $obj, $checkFcm);

			if (!$model)
			{
				throw new Exception("Unable to save device token data.", ReturnSet::ERROR_VALIDATION);
			}
			$returnSet->setStatus(true);
			$returnSet->setMessage('Device token saved successfully.');
		}
		catch (Exception $ex)
		{
			$returnSet = $returnSet->setException($ex);
		}

		return $returnSet;
	}

	public function agreementInformation()
	{
		$returnSet = new ReturnSet();
		try
		{
			$vendorId		 = UserInfo::getEntityId();
			$vendorModel	 = Vendors::model()->findByPk($vendorId);
			$contactId       = ContactProfile::getByVendorId($vendorId);
			$contactModel	 = Contact::model()->findByPk($contactId);
			//$contactModel	 = Contact::model()->findByPk($vendorModel->vnd_contact_id);
			$agrResponse	 = new \Stub\common\Business();
			$agrResponse->setAgreementData($vendorModel, $contactModel);
			$response		 = Filter::removeNull($agrResponse);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch (Exception $ex)
		{
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function editAgreementInfoNew()
	{
		$returnSet	 = new ReturnSet();
		$data		 = $data		 = Yii::app()->request->getParam('data');

		#$transaction = DBUtil::beginTransaction();
		try
		{
			if (!$data)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonMapper	 = new JsonMapper();
			$jsonObj	 = CJSON::decode($data, false);

			$vendorId		 = UserInfo::getEntityId();
			$userId			 = UserInfo::getUserId();
			$model			 = Vendors::model()->findByPk($vendorId);
			$vendorPic		 = $jsonObj->vendorPic;
			$vndDigital		 = $_FILES['vnd_digital_sign']['name'];
			$vndDigitalTmp	 = $_FILES['vnd_digital_sign']['tmp_name'];
			if ($vendorPic == 2)
			{

				$contact_id = $model->vnd_contact_id;

				$model->scenario	 = 'dataagreementupdate';
				$model->vnd_rel_tier = 0;
				if ($model->validate())
				{
					$model->save();
				}
				#$contactModel->scenario = 'update';
				$agrResponse	 = new \Stub\vendor\Agreement();
				$contactModel	 = $agrResponse->getAgreeMentData($jsonObj, $contact_id);
				// $stat = Contact::model()->updateAgrementData($contactModel,$contact_id);

				if ($contactModel->update())
				{
					$returnSet->setStatus(true);
					$returnSet->setMessage("Agreement updated successfully");
				}
			}

			if ($vendorPic == 1 && $vndDigital != '')
			{

				#$transaction = DBUtil::beginTransaction();
				#$digitalLat = $data1['digitalLat'];
				#$digitalLong = $data1['digitalLong'];
				$appToken = AppTokens::model()->getByUserTypeAndUserId($userId, 2);

				$type = 'digital_sign';

				$result2 = Document::model()->saveVendorImage($vndDigital, $vndDigitalTmp, $vendorId, $model->vnd_contact_id, $type);
				$path1	 = str_replace("\\", "\\\\", $result2['path']);

				if (VendorAgreement::model()->updateSignature($vendorId, $path1))
				{

					$appToken	 = AppTokens::model()->getByUserTypeAndUserId($userId, 2);
					$digitalRes	 = new \Stub\vendor\Agreement();
					$modelDig	 = $digitalRes->getDigitalData($jsonObj, $appToken, $vendorId);
					if ($modelDig->update())
					{

						if ($model->vendorPrefs->vnp_is_freeze == 2)
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
			$check = VendorAgreement::model()->checkStatus($vendorId);
		}
		catch (Exception $ex)
		{
			$returnSet = $returnSet->setException($ex);
		}

		return $returnSet;
	}

	/** using for user signin */
	public function actionSignin()
	{
		$this->layout								 = "login";
		$telegramId									 = Yii::app()->request->getParam('telegramId');
		$isVendor									 = Yii::app()->request->cookies['isVendor']	 = new CHttpCookie('isVendor', 1);
		$ireadId									 = Yii::app()->request->cookies['telegramId']	 = new CHttpCookie('telegramId', $telegramId);
		$this->render('signin');
	}

	public function generateJWT()
	{
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$returnSet	 = new ReturnSet();
		try
		{
			$JWToken = JWTokens::generateToken($token);

			$returnSet->setData($JWToken);
			$returnSet->setStatus(true);
		}
		catch (Exception $ex)
		{
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function validateJWT()
	{
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$returnSet	 = new ReturnSet();
		try
		{
			$rawdata = Yii::app()->request->rawBody;
			if (!$rawdata)
			{
				throw new Exception("No data received", ReturnSet::ERROR_NULL);
			}
			$data	 = CJSON::decode($rawdata, true);
			$JWToken = $data['data'];

			$tokenData = JWTokens::validateAppToken($JWToken);

			$returnSet->setStatus(true);
			$returnSet->setData($tokenData);
		}
		catch (Exception $ex)
		{
			$returnSet = $returnSet::setException($ex);
		}
		return $returnSet;
	}

	public function updateLastLocationNew()
	{
		$returnSet = new ReturnSet();
		$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$data	 = Yii::app()->request->rawBody;
		try
		{
			if ($data)
			{
				$jsonObj = CJSON::decode($data, true);
				$success = VendorStats::model()->updateLastLocation($jsonObj);
				if ($success != false)
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
		catch (Exception $e)
		{
			$returnSet = $returnSet->setException($e);
		}

		return $returnSet;
	}

}
