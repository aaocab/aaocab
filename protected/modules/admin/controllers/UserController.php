<?php

class UserController extends Controller
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
			['allow', 'actions' => ['delete'], 'roles' => ['unlinkSocialAcc']],
			['allow', 'actions' => ['delete'], 'roles' => ['userDelete']],
			['allow', 'actions' => ['deactive'], 'roles' => ['userDeactive']],
			['allow', 'actions' => ['list', 'details'], 'roles' => ['userList']],
			['allow', 'actions' => ['addcredits'], 'roles' => ['creditAddBooking']],
			['allow', 'actions' => ['AddCreditsUser'], 'roles' => ['creditAddCustomer']],
			//['allow', 'actions' => ['addvoucher','voucherlist','voucherlistbyquery'], 'roles' => ['voucherAssign']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('loginasuser', 'editinfo', 'ajaxemailcheck', 'sendvmail',
					'markedbadlist', 'markedbadmessage', 'resetmarkedbad', 'sendnotification', 'financialReport',
					'SocialList', 'UnlinkSocialAccount', 'validate', 'devicetokenfcm', 'linkedusers', 'addvoucher',
					'voucherlist', 'voucherlistbyquery', 'walletlist', 'SendResetPasswordLink', 'showwalletdetails', 'showCsrTotalTime', 'resetPasswordByPhone', 'view', 'appUsage', 'devicehistory', 'unlinkSocialAcc', 'userTripDetails', 'forcelogout',
					'GetGozoCoinDetails', 'UserWalletDetails', 'PaymentTransactionDetails', 'GetCbrDetailsDetails'),
				'users'		 => array('@'),
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
			$ri	 = array('/signin', '/signin_new', '/signout', '/devicetokenfcm', '/onoffstatus', '/validate', '/validateversion', '/asyncResponse', '/asyncResponsePartnerStats', '/asyncMedianCapacityByRowIdentifier', '/asyncBidSense', '/asyncDeliveredTrend', '/asyncTravelStatsOW', '/asyncTravelStatsDR', '/asyncTravelStatsAP', '/asynUserVehicleClassLifeTime', '/asynUserServiceClassLifeTime', '/asynUserWeekLifeTime', '/asynUserMonthLifeTime', '/asynUserAirportCitiesLifeTime', '/asynUserCitiesLifeTime', '/checkOnOffStatus', '/entityList');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.post.signin_new.render', function () {
			$result		 = $this->loginV1();
			$returnSet	 = new ReturnSet();
			if ($result['success'] == true)
			{
				$userInfo	 = UserInfo::model();
				$userId		 = $userInfo->getUserId();
				$userModel	 = Admins::model()->resetScope()->find('adm_id=:id AND adm_active IN(1)', ['id' => $userId]);
				if ($userModel->adm_opps_app_access == 1)
				{
					$success	 = true;
					$sessionId	 = $result['sessionId'];
					$msg		 = "Login Successful";
					$returnSet	 = Admins::getProfile($userId, $sessionId);
					$success	 = $returnSet->getStatus();
				}
				else
				{
					$success = false;
					$msg	 = "ACCESS DENIED. For access contact your Gozo department manager.";
				}
			}
			else
			{
				$success = false;
				$msg	 = $result['msg'];
			}
			$returnSet->setMessage($msg);
			return $this->renderJSON($returnSet);
		});

		$this->onRest('req.post.signin.render', function () {
			$result = $this->login();
			if ($result['success'] == true)
			{
				$userInfo	 = UserInfo::model();
				$userId		 = $userInfo->getUserId();
				$userModel	 = Admins::model()->resetScope()->find('adm_id=:id AND adm_active IN(1)', ['id' => $userId]);
				if ($userModel->adm_opps_app_access == 1)
				{
					$success	 = true;
					$sessionId	 = $result['sessionId'];
					$userName	 = $userModel->adm_fname;
					$userEmail	 = $userModel->adm_email;
					$msg		 = "Login Successful";
				}
				else
				{
					$success = false;
					$msg	 = "ACCESS DENIED. For access contact your Gozo department manager.";
				}
			}
			else
			{
				$success = false;
				$msg	 = "Invalid Username/Password";
			}
			$userInfo	 = UserInfo::model();
			$userId		 = $userInfo->getUserId();
			$sessionId	 = Yii::app()->getSession()->getSessionId();
			return CJSON::encode(['success'	 => $success,
				'message'	 => $msg,
				'sessionId'	 => $sessionId,
				'userId'	 => $userId,
				'userEmail'	 => $userEmail,
				'userName'	 => $userName]);
		});

		//admin login on off status check
		$this->onRest('req.post.onoffstatus.render', function () {
			$returnSet	 = new ReturnSet();
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check		 = Admins::model()->authorizeAdmin($token);
			if ($check)
			{
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data				 = CJSON::decode($process_sync_data, true);
				$adminId			 = UserInfo::getUserId();

				/* @var $model Admins */
				$model = AdminOnoff::model()->addAdminOnOffStatus($data, $adminId);

				if ($model['success'])
				{
					$returnSet->setStatus(true);
					$returnSet->setData(array("current_status" => $model['data']));
				}
				else
				{
					$returnSet->setErrors("Data not saved correctly", ReturnSet::ERROR_VALIDATION);
				}
			}
			else
			{
				$returnSet->setErrors("Unauthorized Admin", ReturnSet::ERROR_UNAURHORISED_ADMIN);
			}

			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		}); //eof

		$this->onRest('req.post.validateversion.render', function () {

			$success			 = false;
			$active				 = 0;
			$msg				 = "Invalid Version";
			$sessioncheck		 = '';
			$downloadUrl		 = Config::get("ops.app.download.url");
			$process_sync_data	 = Yii::app()->request->getParam('data');
			if (!isset($process_sync_data) || $process_sync_data == '')
			{
				$process_sync_data = Yii::app()->request->rawBody;
			}
			$data1	 = CJSON::decode($process_sync_data, true);
			$data	 = array_filter($data1);

			$activeVersion	 = Config::get("Version.Android.ops");
			$apt_apk_version = $data['apt_apk_version'];
			if (version_compare($apt_apk_version, $activeVersion) >= 0)
			{
				$active			 = 1;
				$success		 = true;
				$msg			 = "Valid Version";
				$sessioncheck	 = true;
			}
			$result = array('active' => $active, 'success' => $success, 'message' => $msg, 'sessioncheck' => $sessioncheck);
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $result['success'],
					'message'		 => $result['message'],
					'active'		 => $result['active'],
					'downloadUrl'	 => $downloadUrl,
				)
			]);
		});

		$this->onRest('req.post.validate.render', function () {

			$process_sync_data = Yii::app()->request->getParam('data');
			Logger::trace("data : " . $process_sync_data);
			if (!isset($process_sync_data) || $process_sync_data == '')
			{
				$process_sync_data = Yii::app()->request->rawBody;
			}
			$data1	 = CJSON::decode($process_sync_data, true);
			$data	 = array_filter($data1);

			$activeVersion = Config::get("Version.Android.ops");

			$tokenData	 = AppTokens::model()->getByTokenId($data['apt_token_id'], 6);
			$id			 = $tokenData['apt_user_id'];

			$result				 = $this->getValidationApp($data, $id, $activeVersion);
			$adminOnOffStatus	 = 0;
			if ($result['success'])
			{
				$model				 = Admins::model()->findByPk($id);
				$checkData			 = AdminOnoff::model()->getByAdmId($id);
				$adminOnOffStatus	 = $checkData['ado_status'] != null ? $checkData['ado_status'] : 0;
				$lastLogindetails	 = AdminOnoff::model()->getLastAdminsDetails($id);
				$lastLoginTime		 = $lastLogindetails['ado_login_confirm_time'] != null ? $lastLogindetails['ado_login_confirm_time'] : "";
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $result['success'],
					'message'		 => $result['message'],
					'active'		 => $result['active'],
					'OnOffStatus'	 => (int) $adminOnOffStatus,
					'lastLoginTime'	 => $lastLoginTime,
					'version'		 => $activeVersion,
					'data'			 => $model,
				)
			]);
		});

		$this->onRest('req.post.signout.render', function () {
			$token				 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			Logger::create('Logout Data ===========>: ' . $token, CLogger::LEVEL_TRACE);
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data				 = CJSON::decode($process_sync_data, true);
			$applogout			 = AppTokens::model()->find('apt_token_id = :token', array('token' => $token));
			if ($applogout)
			{

				$csr					 = $applogout->apt_user_id;
				$applogout->apt_status	 = 0;
				$applogout->apt_logout	 = new CDbExpression('NOW()');
				$logout					 = $applogout->save();
				Yii::app()->user->logout();
			}
			if ($logout)
			{
				ServiceCallQueue::processUnAssignment($csr);
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
		$this->onRest('req.post.devicetokenfcm.render', function () {
			$process_sync_data = Yii::app()->request->getParam('data');
			if (!isset($process_sync_data) || $process_sync_data == '')
			{
				$process_sync_data = Yii::app()->request->rawBody;
			}
			$data1			 = CJSON::decode($process_sync_data, true);
			$data			 = array_filter($data1);
			$userTokenId	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$aptDeviceToken	 = $data['apt_device_token'];
			$aptDeviceUUID	 = $data['apt_device_uuid'];

			$appToken = AppTokens::getByTokens($aptDeviceToken, $userTokenId);
			if (!$appToken)
			{
				$appToken = new AppTokens();
				if (!$userTokenId)
				{
					$appToken->apt_token_id = $userTokenId;
				}
			}
			$appToken->apt_device_token	 = $aptDeviceToken;
			$appToken->apt_device_uuid	 = $aptDeviceUUID;
			$appToken->scenario			 = 'fcm';
			$appToken->apt_user_type	 = 6;
			$appToken->apt_status		 = 1;
			$success					 = $appToken->save();
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => [
					'success'	 => $success,
					'errors'	 => $appToken->getErrors()
				]
			]);
		});

		$this->onRest('req.post.devicetokenfcm_OLD.render', function () {
			Logger::create('6 device token fcm ', CLogger::LEVEL_TRACE);
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$data				 = array_filter($data1);
			$token				 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			Logger::create('Search Token start ', CLogger::LEVEL_TRACE);
			$appToken1			 = AppTokens::model()->find('apt_device_token = :token', array('token' => $data['apt_device_token']));
			Logger::create('Search Token end ', CLogger::LEVEL_TRACE);
			if ($appToken1 != '')
			{
				$appToken1->apt_status = 0;
				$appToken1->update();
			}
			Logger::create('Search Token 2 start ', CLogger::LEVEL_TRACE);
			$appToken = AppTokens::model()->find('apt_token_id = :token', array('token' => $token));
			Logger::create('Search Token 2 end ', CLogger::LEVEL_TRACE);
			if (!$appToken)
			{
				$appToken = new AppTokens();
			}
			$appToken->apt_device_token	 = $data['apt_device_token'];
			$appToken->apt_device_uuid	 = $data['apt_device_uuid'];
			$appToken->scenario			 = 'fcm';
			$appToken->apt_user_type	 = 6;
			$success					 = $appToken->save();
			Yii::log("device token : " . $data['apt_device_token'], CLogger::LEVEL_INFO);
			Logger::create('Rendering response ', CLogger::LEVEL_TRACE);
			return $this->renderJSON(['type' => 'raw', 'data' => ['success' => $success, 'errors' => $appToken->getErrors()]]);
		});

		$this->onRest('req.get.list.render', function () {
			$returnSet	 = new ReturnSet();
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result		 = Admins::model()->authorizeAdmin($token);
			if ($result)
			{
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$searchText			 = $data1['searchText'];
				$adminId			 = UserInfo::getUserId();
				$admModel			 = Admins::model()->findByPk($adminId);
				$model				 = Booking::model()->getAdminBkgDetails($searchText, $admModel->adm_region);
				if ($model != [])
				{
					$returnSet->setStatus(true);
					$returnSet->setData($model);
				}
				else
				{
					$returnSet->setErrors("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
			}
			else
			{
				$returnSet->setErrors("Unauthorised Admin", ReturnSet::ERROR_UNAURHORISED_ADMIN);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});

		$this->onRest('req.post.bookingView.render', function () {
			$returnSet	 = new ReturnSet();
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result		 = Admins::model()->authorizeAdmin($token);
			if ($result)
			{
				$processSyncData = Yii::app()->request->getParam('data');
				$data			 = CJSON::decode($processSyncData, true);
				$tripId			 = $data['bkg_id'];
				$status			 = $data['status'];
				$model			 = BookingSub::model()->getAdmBkgDetails($tripId, $status);
				if ($model != [])
				{
					$returnSet->setStatus(true);
					$returnSet->setData($model);
					$userInfo				 = UserInfo::getInstance();
					$desc					 = 'Booking Viewed (OPS App)';
					$params['blg_active']	 = 2;
					$eventId				 = BookingLog::BOOKING_VIEWED;
					BookingLog::model()->createLog($model['bkg_id'], $desc, $userInfo, $eventId, $oldModel, $params);
				}
				else
				{
					$returnSet->setErrors("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
			}
			else
			{
				$returnSet->setErrors("Unauthorised Admin", ReturnSet::ERROR_UNAURHORISED_ADMIN);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});
		$this->onRest('req.post.showLog.render', function () {
			$returnSet	 = new ReturnSet();
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result		 = Admins::model()->authorizeAdmin($token);
			if ($result)
			{
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$userInfo			 = UserInfo::getInstance();
				$bkgId				 = $data1['bkgID'];
				$model				 = BookingLog::model()->getByBookingId($bkgId, 1);
				if ($model != '')
				{
					$returnSet->setStatus(true);
					$returnSet->setData($model);
				}
				else
				{
					$returnSet->setErrors("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
			}
			else
			{
				$returnSet->setErrors("Unauthorised Admin", ReturnSet::ERROR_UNAURHORISED_ADMIN);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});
		$this->onRest('req.post.assignVendor.render', function () {
			$returnSet	 = new ReturnSet();
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result		 = Admins::model()->authorizeAdmin($token);
			if ($result)
			{
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$userInfo			 = UserInfo::getInstance();
				$vendorId			 = $data1['vendorId'];
				$tripId				 = $data1['tripId'];
				$tripAmount			 = $data1['tripAmount'];
				$remarks			 = $data1['remarks'];
				$assignMode			 = 0;
				$returnSet1			 = BookingCab::model()->assignVendor($tripId, $vendorId, $tripAmount, $remarks, $userInfo, $assignMode);
				if ($returnSet1->isSuccess())
				{
					$returnSet->setStatus(true);
					$returnSet->setData(['message' => 'Vendor Assigned']);
				}
				else
				{
					$returnSet = $returnSet1;
				}
			}
			else
			{
				$returnSet->setErrors("Unauthorised Admin", ReturnSet::ERROR_UNAURHORISED_ADMIN);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});
		$this->onRest('req.post.delegateOmList.render', function () {
			$returnSet	 = new ReturnSet();
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result		 = Admins::model()->authorizeAdmin($token);
			if ($result)
			{
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$searchText			 = ($data1['searchText'] != '' || $data1['searchText'] != null) ? $data1['searchText'] : '';
				$model				 = Booking::model()->getDelegatedOMList($searchText);
				if ($model != [])
				{
					$returnSet->setStatus(true);
					$returnSet->setData($model);
				}
				else
				{
					$returnSet->setErrors("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
			}
			else
			{
				$returnSet->setErrors("Unauthorised Admin", ReturnSet::ERROR_UNAURHORISED_ADMIN);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});
		$this->onRest('req.post.adminProfile.render', function () {
			$returnSet	 = new ReturnSet();
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result		 = Admins::model()->authorizeAdmin($token);
			if ($result)
			{
				$adminId = UserInfo::getUserId();
				$model	 = Admins::model()->findByPk($adminId);
				$jsonObj = ["adm_fname" => $model->adm_fname, "adm_lname" => $model->adm_lname];
				if ($model != '')
				{
					$returnSet->setStatus(true);
					$returnSet->setData($jsonObj);
				}
				else
				{
					$returnSet->setErrors("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
			}
			else
			{
				$returnSet->setErrors("Unauthorised Admin", ReturnSet::ERROR_UNAURHORISED_ADMIN);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});
		$this->onRest('req.post.addRemarks.render', function () {
			$returnSet	 = new ReturnSet();
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result		 = Admins::model()->authorizeAdmin($token);
			if ($result)
			{
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data1				 = CJSON::decode($process_sync_data, true);
				$remarkText			 = $data1['remarkText'];
				$bkgId				 = $data1['bkgID'];
				$model				 = Booking::model()->findByPk($bkgId);
				if ($model != [])
				{
					$params							 = [];
					$params['blg_booking_status']	 = $model->bkg_status;
					$userInfo						 = UserInfo::getInstance();
					$eventid						 = BookingLog::REMARKS_ADDED;
					$desc							 = "[Remark_OpsApp]:" . $remarkText;
					$success						 = BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventid, $model);
					if ($success)
					{
						$returnSet->setStatus(true);
						$returnSet->setData(['message' => 'Remarks Added to Booking Log']);
					}
					else
					{
						$returnSet->setErrors("Unable to add remark to booking log.", $returnSet::ERROR_REMARK_NOT_ADDED);
					}
				}
				else
				{
					$returnSet->setErrors("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
			}
			else
			{
				$returnSet->setErrors("Unauthorised Admin", ReturnSet::ERROR_UNAURHORISED_ADMIN);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});
		$this->onRest('req.post.updateLastLocation.render', function () {
			Filter::setLogCategory("trace.controller.modules.driver.users.updateLastLocation");
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Admins::model()->authorizeAdmin($token);
			if ($check)
			{
				$process_sync_data	 = Yii::app()->request->getParam('data');
				$data				 = CJSON::decode($process_sync_data, true);
				$appToken			 = AppTokens::model()->getActiveAppTokenByDeviceUser($data['deviceId'], 6);
				if (count($appToken) > 0)
				{
					$result		 = Admins::model()->updateLastLocation($data);
					$userInfo	 = UserInfo::getInstance();
					$data['lon'] = $data['long'];
					Location::addLocation($data, $userInfo);
				}
				else
				{
					$result = false;
				}
			}
			else
			{
				$result	 = false;
				$message = 'Your not a authorised User.';
			}
			return $this->renderJSON(['type' => 'raw', 'data' => ['success' => $result]]);
		});
		$this->onRest("req.post.showCsrTotalTime.render", function () {
			return $this->renderJson($this->showCsrTotalTime());
		});
		$this->onRest("req.post.shiftOnOffStatus.render", function () {
			return $this->renderJson($this->shiftOnOffStatus());
		});
		$this->onRest("req.post.notificationLog.render", function () {
			return $this->renderJson($this->notificationLog());
		});

		/* Iread comment On */
		$this->onRest('req.post.asyncResponse.render', function () {
			Logger::info("\n***** asyncResponse start *******\n");
			$returnSet			 = new ReturnSet();
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			if ($process_sync_data != null)
			{
				if (Ireaddocs::updateAsyncIread($jsonObj->transaction_id, $process_sync_data))
				{
					try
					{
						$res = Ireaddocs::model()->findByPk($jsonObj->doc_id);
						switch ($res->ird_doc_type)
						{
							case 1:
								$model						 = Document::model()->findByPk($res->ird_doc_id);
								$model->doc_machine_output	 = json_encode($jsonObj->output);
								$model->save();
								break;
							case 2:
								$model						 = BookingPayDocs::model()->findByPk($res->ird_doc_id);
								$model->bpay_machine_output	 = json_encode($jsonObj->output);
								$model->save();
								break;
							case 3:
								$model						 = VehicleDocs::model()->findByPk($res->ird_doc_id);
								$model->vhd_machine_output	 = $jsonObj->output;
								$modelVehicleNumber			 = strtoupper(str_replace(' ', '', preg_replace('/[\W]/', '', $model->vhdVhc->vhc_number)));
								$vehicleNumber				 = strtoupper(str_replace(' ', '', preg_replace('/[\W]/', '', $jsonObj->output)));
								if ($modelVehicleNumber == $vehicleNumber && $modelVehicleNumber != null && $vehicleNumber != null)
								{
									$model->vhd_status = 1;
								}
								$response = $model->save();
								if ($response && $modelVehicleNumber == $vehicleNumber)
								{
									if ($model->vhd_type == 2)
									{
										VehiclesLog::model()->createLog($model->vhd_vhc_id, "Front license plate approved by iread", Userinfo::getInstance(), 12, false, false);
									}
									else if ($model->vhd_type == 3)
									{
										VehiclesLog::model()->createLog($model->vhd_vhc_id, "Rear license plate approved by iread", Userinfo::getInstance(), 14, false, false);
									}
								}

								break;
							default:
								break;
						}
					}
					catch (Exception $ex)
					{
						Logger::info("\n***** asyncResponse exception  " . $ex->getMessage() . "  *******\n   ");
						Logger::exception($ex);
					}
					$success = $returnSet->setStatus(true);
					$returnSet->setMessage("Data updated successfully");
				}
				else
				{
					$returnSet->setErrors("Some error occured", ReturnSet::ERROR_FAILED);
				}
			}
			else
			{
				$returnSet->setErrors("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			Logger::info("\n***** asyncResponse ends *******\n");
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});

		/* Partner stats daily update */
		$this->onRest('req.post.asyncResponsePartnerStats.render', function () {
			$returnSet			 = new ReturnSet();
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			if ($process_sync_data != null)
			{
				$partnerIds = "";
				foreach ($jsonObj->data as $value)
				{
					$partnerIds	 .= $value->partner_id . ",";
					$model		 = PartnerSettings::model()->getbyPartnerId($value->partner_id);
					if ($model)
					{
						try
						{
							$model->pts_outstation_count	 = ceil($value->outstation_count);
							$model->pts_local_count			 = ceil($value->local_count);
							$partnerRuleOutStation			 = PartnerRuleCommission::getPartnerRuleCommission($value->outstation_count, $value->partner_id, 1);
							$partnerRuleLocal				 = PartnerRuleCommission::getPartnerRuleCommission($value->local_count, $value->partner_id, 2);
							$partnerCombineArr				 = array();
							$partnerCombineArr["outstation"] = !empty($partnerRuleOutStation) ? array('isApplied' => 1, 'id' => (int) $partnerRuleOutStation['prc_id'], 'commissionType' => (int) $partnerRuleOutStation['prc_commission_type'], "commissionValue" => (float) $partnerRuleOutStation['prc_commission_value']) : array('isApplied' => 0, 'id' => 0, 'commissionType' => 0, "commissionValue" => 7);
							$partnerCombineArr["local"]		 = !empty($partnerRuleLocal) ? array('isApplied' => 1, 'id' => (int) $partnerRuleLocal['prc_id'], 'commissionType' => (int) $partnerRuleLocal['prc_commission_type'], "commissionValue" => (float) $partnerRuleLocal['prc_commission_value']) : array('isApplied' => 0, 'id' => 0, 'commissionType' => 1, "commissionValue" => 5);
							$model->pts_additional_param	 = json_encode($partnerCombineArr);
							$model->save();
						}
						catch (Exception $ex)
						{
							Logger::exception($ex);
						}
					}
				}

				if ($partnerIds != null)
				{
					$partnerIds			 = trim($partnerIds, ",");
					$outstationCount	 = 0;
					$localCount			 = 0;
					$partnerCombineArr	 = array();
					$additionalParam	 = json_encode($partnerCombineArr);
					PartnerSettings::updateAdditonalParam($outstationCount, $localCount, $additionalParam, $partnerIds, 1);
				}
				$success = $returnSet->setStatus(true);
				$returnSet->setMessage("Data updated successfully");
			}
			else
			{
				$returnSet->setErrors("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});
		$this->onRest("req.post.checkOnOffStatus.render", function () {
			return $this->renderJson($this->checkOnOffStatus());
		});

		$this->onRest("req.post.checkOnOffStatus.render", function () {
			return $this->renderJson($this->checkOnOffStatus());
		});
		$this->onRest("req.post.entityList.render", function () {
			return $this->renderJson($this->entityList());
		});

		/* Median Capacity By RowIdentifier Weekly  update */
		$this->onRest('req.post.asyncMedianCapacityByRowIdentifier.render', function () {
			Logger::warning('UserController asyncMedianCapacityByRowIdentifier', true);
			return false;

			$returnSet			 = new ReturnSet();
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			if ($process_sync_data != null)
			{
				foreach ($jsonObj->data as $value)
				{
					try
					{
						if ($value->lastRow == 0)
						{
							$sqldata = "INSERT INTO `rowIdentifier_completions_last30d` (`row_identifier`,`median_count_30`, `created_at`, `updated_at`, `active`) VALUES 
                                ('" . $value->row_identifier . "','" . $value->median_count_30 . "','" . $value->created_at . "', '" . $value->updated_at . "', '1')";
							DBUtil::execute($sqldata);
							QuotesSituation::updateRowIdentifierMedainCapacity($value->row_identifier, $value->median_count_30);

							$regionId			 = (int) substr($value->row_identifier, 1, 2);
							$fromZone			 = (int) substr($value->row_identifier, 3, 5);
							$tripType			 = (int) substr($value->row_identifier, 16, 2);
							$type				 = in_array($tripType, array("4", "9", "10", "11", "12")) ? 1 : 2;
							$param				 = array('regionId' => $regionId, 'fromZone' => $fromZone, 'tripType' => $type);
							$sql				 = "SELECT CONCAT('7','',LPAD(:regionId,2,'0'),'',LPAD(:fromZone,5,'0'),LPAD(:tripType,2,'0')) AS demandIdentifier FROM DUAL";
							$demandIdentifier	 = DBUtil::queryScalar($sql, DBUtil::SDB(), $param);
							QuotesZoneSituation::updateDemandIdentifierMedainCapacity($demandIdentifier, $value->median_count_30);
						}
						else if ($value->lastRow > 0)
						{
							// finally update  qzs_capacity by 30 to get per day capapcaity
							$sql = "UPDATE `quotes_zone_situation` SET qzs_capacity=CEIL(qzs_capacity/30) WHERE 1 AND DATE(qzs_updated_date)=CURDATE()";
							DBUtil::execute($sql);
						}
					}
					catch (Exception $ex)
					{
						Logger::exception($ex);
					}
				}
				$returnSet->setStatus(true);
				$returnSet->setMessage("Data updated successfully");
			}
			else
			{
				$returnSet->setErrors("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});

		/* Bidsense Weekly  update */
		$this->onRest('req.post.asyncBidSense.render', function () {
			$returnSet			 = new ReturnSet();
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			$flag				 = 0;
			if ($process_sync_data != null)
			{
				foreach ($jsonObj->data as $value)
				{
					try
					{
						$model = BidSense::model()->findByPk($value->id);
						if (empty($model))
						{
							$model		 = new BidSense();
							$model->id	 = $value->id;
						}
						$model->date				 = $value->date;
						$model->bidCount			 = $value->bidCount;
						$model->vendorMaxAmount		 = $value->vendorMaxAmount;
						$model->vendorMinAmount		 = $value->vendorMinAmount;
						$model->vendorAvgAmount		 = $value->vendorAvgAmount;
						$model->vendorMedianAmount	 = $value->vendorMedianAmount;
						$model->vendorPickupDate	 = $value->vendorPickupDate;
						$model->rowIdentifier		 = $value->rowIdentifier;
						$model->vendorBidBins		 = $value->vendorBidBins;
						$model->active				 = $value->active;
						$model->created_at			 = $value->created_at;
						$model->modified_at			 = $value->modified_at;
						if (!$model->save())
						{
							throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
						}
					}
					catch (Exception $ex)
					{
						Logger::exception($ex);
					}
					$flag = 1;
				}
				if ($flag == 1)
				{
					$dropQry = "DELETE FROM `bidSense` WHERE 1 AND created_at<CURDATE()";
					DBUtil::execute($dropQry);
				}
				$returnSet->setStatus(true);
				$returnSet->setMessage("Data updated successfully");
			}
			else
			{
				$returnSet->setErrors("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});

		/* Delivered Vendor Trend Weekly  update */
		$this->onRest('req.post.asyncDeliveredTrend.render', function () {
			$returnSet			 = new ReturnSet();
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			$flag				 = 0;
			if ($process_sync_data != null)
			{
				foreach ($jsonObj->data as $value)
				{
					try
					{
						$model = DELIVEREDVATREND::model()->findByPk($value->id);
						if (empty($model))
						{
							$model		 = new DELIVEREDVATREND();
							$model->id	 = $value->id;
						}
						$model->rowIdentifier			 = $value->rowIdentifier;
						$model->year					 = $value->year;
						$model->weekDate				 = $value->weekDate;
						$model->pickupDayOfWeek			 = $value->pickupDayOfWeek;
						$model->pickUpWeek				 = $value->pickUpWeek;
						$model->maxVendorAmount			 = $value->maxVendorAmount;
						$model->minVendorAmount			 = $value->minVendorAmount;
						$model->avgVendorAmount			 = $value->avgVendorAmount;
						$model->medVendorAmount			 = $value->medVendorAmount;
						$model->maxQuotedVendorAmount	 = $value->maxQuotedVendorAmount;
						$model->minQuotedVendorAmount	 = $value->minQuotedVendorAmount;
						$model->avgQuotedVendorAmount	 = $value->avgQuotedVendorAmount;
						$model->medQuotedVendorAmount	 = $value->medQuotedVendorAmount;
						$model->createDate				 = $value->createDate;
						$model->updateDate				 = $value->updateDate;
						if (!$model->save())
						{
							throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
						}
					}
					catch (Exception $ex)
					{
						Logger::exception($ex);
					}
					$flag = 1;
				}
				if ($flag == 1)
				{
					$dropQry = "DELETE FROM `DELIVERED_VA_TREND` WHERE 1 AND createDate<CURDATE()";
					DBUtil::execute($dropQry);
				}
				$returnSet->setStatus(true);
				$returnSet->setMessage("Data updated successfully");
			}
			else
			{
				$returnSet->setErrors("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});

		/* Travel Stats OW Weekly  update */
		$this->onRest('req.post.asyncTravelStatsOW.render', function () {
			$returnSet			 = new ReturnSet();
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			$flag				 = 0;
			if ($process_sync_data != null)
			{
				foreach ($jsonObj->data as $value)
				{
					try
					{
						$model = TravelStatsOw::model()->findByPk($value->tso_id);
						if (empty($model))
						{
							$model			 = new TravelStatsOw();
							$model->tso_id	 = $value->tso_id;
						}
						$model->tso_city_identifier					 = $value->tso_city_identifier;
						$model->tso_min_travel_time_180				 = $value->tso_min_travel_time_180;
						$model->tso_max_travel_time_180				 = $value->tso_max_travel_time_180;
						$model->tso_avg_travel_time_180				 = $value->tso_avg_travel_time_180;
						$model->tso_median_travel_time_180			 = $value->tso_median_travel_time_180;
						$model->tso_min_travel_time_90				 = $value->tso_min_travel_time_90;
						$model->tso_max_travel_time_90				 = $value->tso_max_travel_time_90;
						$model->tso_avg_travel_time_90				 = $value->tso_avg_travel_time_90;
						$model->tso_median_travel_time_90			 = $value->tso_median_travel_time_90;
						$model->tso_min_cost_per_duration_180		 = $value->tso_min_cost_per_duration_180;
						$model->tso_max_cost_per_duration_180		 = $value->tso_max_cost_per_duration_180;
						$model->tso_avg_cost_per_duration_180		 = $value->tso_avg_cost_per_duration_180;
						$model->tso_median_cost_per_duration_180	 = $value->tso_median_cost_per_duration_180;
						$model->tso_min_cost_per_duration_90		 = $value->tso_min_cost_per_duration_90;
						$model->tso_max_cost_per_duration_90		 = $value->tso_max_cost_per_duration_90;
						$model->tso_avg_cost_per_duration_90		 = $value->tso_avg_cost_per_duration_90;
						$model->tso_median_cost_per_duration_90		 = $value->tso_median_cost_per_duration_90;
						$model->tso_min_cost_per_distance_180		 = $value->tso_min_cost_per_distance_180;
						$model->tso_max_cost_per_distance_180		 = $value->tso_max_cost_per_distance_180;
						$model->tso_avg_cost_per_distance_180		 = $value->tso_avg_cost_per_distance_180;
						$model->tso_median_cost_per_distance_180	 = $value->tso_median_cost_per_distance_180;
						$model->tso_min_cost_per_distance_90		 = $value->tso_min_cost_per_distance_90;
						$model->tso_max_cost_per_distance_90		 = $value->tso_max_cost_per_distance_90;
						$model->tso_avg_cost_per_distance_90		 = $value->tso_avg_cost_per_distance_90;
						$model->tso_median_cost_per_distance_90		 = $value->tso_median_cost_per_distance_90;
						$model->tso_min_vnd_cost_per_distance_90	 = $value->tso_min_vnd_cost_per_distance_90;
						$model->tso_max_vnd_cost_per_distance_90	 = $value->tso_max_vnd_cost_per_distance_90;
						$model->tso_avg_vnd_cost_per_distance_90	 = $value->tso_avg_vnd_cost_per_distance_90;
						$model->tso_median_vnd_cost_per_distance_90	 = $value->tso_median_vnd_cost_per_distance_90;
						$model->tso_min_vnd_cost_per_distance_180	 = $value->tso_min_vnd_cost_per_distance_180;
						$model->tso_max_vnd_cost_per_distance_180	 = $value->tso_max_vnd_cost_per_distance_180;
						$model->tso_avg_vnd_cost_per_distance_180	 = $value->tso_avg_vnd_cost_per_distance_180;
						$model->tso_median_vnd_cost_per_distance_180 = $value->tso_median_vnd_cost_per_distance_180;
						$model->tso_min_vnd_cost_per_duration_90	 = $value->tso_min_vnd_cost_per_duration_90;
						$model->tso_max_vnd_cost_per_duration_90	 = $value->tso_max_vnd_cost_per_duration_90;
						$model->tso_avg_vnd_cost_per_duration_90	 = $value->tso_avg_vnd_cost_per_duration_90;
						$model->tso_median_vnd_cost_per_duration_90	 = $value->tso_median_vnd_cost_per_duration_90;
						$model->tso_min_vnd_cost_per_duration_180	 = $value->tso_min_vnd_cost_per_duration_180;
						$model->tso_avg_vnd_cost_per_duration_180	 = $value->tso_avg_vnd_cost_per_duration_180;
						$model->tso_median_vnd_cost_per_duration_180 = $value->tso_median_vnd_cost_per_duration_180;
						$model->tso_max_vnd_cost_per_duration_180	 = $value->tso_max_vnd_cost_per_duration_180;
						$model->tso_active							 = $value->tso_active;
						$model->tso_create_at						 = $value->tso_create_at;
						$model->tso_updated_at						 = $value->tso_updated_at;
						if (!$model->save())
						{
							throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
						}
					}
					catch (Exception $ex)
					{
						Logger::exception($ex);
					}
					$flag = 1;
				}
				if ($flag == 1)
				{
					$dropQry = "DELETE FROM `travel_stats_ow` WHERE 1 AND  travel_stats_ow.tso_create_at<CURDATE()";
					DBUtil::execute($dropQry);
				}
				$returnSet->setStatus(true);
				$returnSet->setMessage("Data updated successfully");
			}
			else
			{
				$returnSet->setErrors("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});

		/* Travel Stats AP Weekly  update */
		$this->onRest('req.post.asyncTravelStatsAP.render', function () {
			$returnSet			 = new ReturnSet();
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			$flag				 = 0;
			if ($process_sync_data != null)
			{
				foreach ($jsonObj->data as $value)
				{
					try
					{
						$model = TravelStatsAp::model()->findByPk($value->tsa_id);
						if (empty($model))
						{
							$model			 = new TravelStatsAp();
							$model->tsa_id	 = $value->tsa_id;
						}

						$model->tsa_city_identifier					 = $value->tsa_city_identifier;
						$model->tsa_min_travel_time_180				 = $value->tsa_min_travel_time_180;
						$model->tsa_max_travel_time_180				 = $value->tsa_max_travel_time_180;
						$model->tsa_avg_travel_time_180				 = $value->tsa_avg_travel_time_180;
						$model->tsa_median_travel_time_180			 = $value->tsa_median_travel_time_180;
						$model->tsa_min_travel_time_90				 = $value->tsa_min_travel_time_90;
						$model->tsa_max_travel_time_90				 = $value->tsa_max_travel_time_90;
						$model->tsa_avg_travel_time_90				 = $value->tsa_avg_travel_time_90;
						$model->tsa_median_travel_time_90			 = $value->tsa_median_travel_time_90;
						$model->tsa_min_cost_per_duration_180		 = $value->tsa_min_cost_per_duration_180;
						$model->tsa_max_cost_per_duration_180		 = $value->tsa_max_cost_per_duration_180;
						$model->tsa_avg_cost_per_duration_180		 = $value->tsa_avg_cost_per_duration_180;
						$model->tsa_median_cost_per_duration_180	 = $value->tsa_median_cost_per_duration_180;
						$model->tsa_min_cost_per_duration_90		 = $value->tsa_min_cost_per_duration_90;
						$model->tsa_max_cost_per_duration_90		 = $value->tsa_max_cost_per_duration_90;
						$model->tsa_avg_cost_per_duration_90		 = $value->tsa_avg_cost_per_duration_90;
						$model->tsa_median_cost_per_duration_90		 = $value->tsa_median_cost_per_duration_90;
						$model->tsa_min_cost_per_distance_180		 = $value->tsa_min_cost_per_distance_180;
						$model->tsa_max_cost_per_distance_180		 = $value->tsa_max_cost_per_distance_180;
						$model->tsa_avg_cost_per_distance_180		 = $value->tsa_avg_cost_per_distance_180;
						$model->tsa_median_cost_per_distance_180	 = $value->tsa_median_cost_per_distance_180;
						$model->tsa_min_cost_per_distance_90		 = $value->tsa_min_cost_per_distance_90;
						$model->tsa_max_cost_per_distance_90		 = $value->tsa_max_cost_per_distance_90;
						$model->tsa_avg_cost_per_distance_90		 = $value->tsa_avg_cost_per_distance_90;
						$model->tsa_median_cost_per_distance_90		 = $value->tsa_median_cost_per_distance_90;
						$model->tsa_min_vnd_cost_per_distance_90	 = $value->tsa_min_vnd_cost_per_distance_90;
						$model->tsa_max_vnd_cost_per_distance_90	 = $value->tsa_max_vnd_cost_per_distance_90;
						$model->tsa_avg_vnd_cost_per_distance_90	 = $value->tsa_avg_vnd_cost_per_distance_90;
						$model->tsa_median_vnd_cost_per_distance_90	 = $value->tsa_median_vnd_cost_per_distance_90;
						$model->tsa_min_vnd_cost_per_distance_180	 = $value->tsa_min_vnd_cost_per_distance_180;
						$model->tsa_max_vnd_cost_per_distance_180	 = $value->tsa_max_vnd_cost_per_distance_180;
						$model->tsa_avg_vnd_cost_per_distance_180	 = $value->tsa_avg_vnd_cost_per_distance_180;
						$model->tsa_median_vnd_cost_per_distance_180 = $value->tsa_median_vnd_cost_per_distance_180;
						$model->tsa_min_vnd_cost_per_duration_90	 = $value->tsa_min_vnd_cost_per_duration_90;
						$model->tsa_max_vnd_cost_per_duration_90	 = $value->tsa_max_vnd_cost_per_duration_90;
						$model->tsa_avg_vnd_cost_per_duration_90	 = $value->tsa_avg_vnd_cost_per_duration_90;
						$model->tsa_median_vnd_cost_per_duration_90	 = $value->tsa_median_vnd_cost_per_duration_90;
						$model->tsa_min_vnd_cost_per_duration_180	 = $value->tsa_min_vnd_cost_per_duration_180;
						$model->tsa_avg_vnd_cost_per_duration_180	 = $value->tsa_avg_vnd_cost_per_duration_180;
						$model->tsa_median_vnd_cost_per_duration_180 = $value->tsa_median_vnd_cost_per_duration_180;
						$model->tsa_max_vnd_cost_per_duration_180	 = $value->tsa_max_vnd_cost_per_duration_180;
						$model->tsa_active							 = $value->tsa_active;
						$model->tsa_create_at						 = $value->tsa_create_at;
						$model->tsa_updated_at						 = $value->tsa_updated_at;
						if (!$model->save())
						{
							throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
						}
					}
					catch (Exception $ex)
					{
						Logger::exception($ex);
					}
					$flag = 1;
				}
				if ($flag == 1)
				{
					$dropQry = "DELETE FROM `travel_stats_ap` WHERE 1 AND  travel_stats_ap.tsa_create_at<CURDATE()";
					DBUtil::execute($dropQry);
				}
				$returnSet->setStatus(true);
				$returnSet->setMessage("Data updated successfully");
			}
			else
			{
				$returnSet->setErrors("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});

		/* Travel Stats DR Weekly  update */
		$this->onRest('req.post.asyncTravelStatsDR.render', function () {
			$returnSet			 = new ReturnSet();
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			$flag				 = 0;
			if ($process_sync_data != null)
			{
				foreach ($jsonObj->data as $value)
				{
					try
					{
						$model = TravelStatsDr::model()->findByPk($value->tsdr_id);
						if (empty($model))
						{
							$model			 = new TravelStatsDr();
							$model->tsdr_id	 = $value->tsdr_id;
						}
						$model->tsdr_city_identifier					 = $value->tsdr_city_identifier;
						$model->tsdr_min_travel_time_180				 = $value->tsdr_min_travel_time_180;
						$model->tsdr_max_travel_time_180				 = $value->tsdr_max_travel_time_180;
						$model->tsdr_avg_travel_time_180				 = $value->tsdr_avg_travel_time_180;
						$model->tsdr_median_travel_time_180				 = $value->tsdr_median_travel_time_180;
						$model->tsdr_min_travel_time_90					 = $value->tsdr_min_travel_time_90;
						$model->tsdr_max_travel_time_90					 = $value->tsdr_max_travel_time_90;
						$model->tsdr_avg_travel_time_90					 = $value->tsdr_avg_travel_time_90;
						$model->tsdr_median_travel_time_90				 = $value->tsdr_median_travel_time_90;
						$model->tsdr_min_cost_per_duration_180			 = $value->tsdr_min_cost_per_duration_180;
						$model->tsdr_max_cost_per_duration_180			 = $value->tsdr_max_cost_per_duration_180;
						$model->tsdr_avg_cost_per_duration_180			 = $value->tsdr_avg_cost_per_duration_180;
						$model->tsdr_median_cost_per_duration_180		 = $value->tsdr_median_cost_per_duration_180;
						$model->tsdr_min_cost_per_duration_90			 = $value->tsdr_min_cost_per_duration_90;
						$model->tsdr_max_cost_per_duration_90			 = $value->tsdr_max_cost_per_duration_90;
						$model->tsdr_avg_cost_per_duration_90			 = $value->tsdr_avg_cost_per_duration_90;
						$model->tsdr_median_cost_per_duration_90		 = $value->tsdr_median_cost_per_duration_90;
						$model->tsdr_min_cost_per_distance_180			 = $value->tsdr_min_cost_per_distance_180;
						$model->tsdr_max_cost_per_distance_180			 = $value->tsdr_max_cost_per_distance_180;
						$model->tsdr_avg_cost_per_distance_180			 = $value->tsdr_avg_cost_per_distance_180;
						$model->tsdr_median_cost_per_distance_180		 = $value->tsdr_median_cost_per_distance_180;
						$model->tsdr_min_cost_per_distance_90			 = $value->tsdr_min_cost_per_distance_90;
						$model->tsdr_max_cost_per_distance_90			 = $value->tsdr_max_cost_per_distance_90;
						$model->tsdr_avg_cost_per_distance_90			 = $value->tsdr_avg_cost_per_distance_90;
						$model->tsdr_median_cost_per_distance_90		 = $value->tsdr_median_cost_per_distance_90;
						$model->tsdr_min_vnd_cost_per_distance_90		 = $value->tsdr_min_vnd_cost_per_distance_90;
						$model->tsdr_max_vnd_cost_per_distance_90		 = $value->tsdr_max_vnd_cost_per_distance_90;
						$model->tsdr_avg_vnd_cost_per_distance_90		 = $value->tsdr_avg_vnd_cost_per_distance_90;
						$model->tsdr_median_vnd_cost_per_distance_90	 = $value->tsdr_median_vnd_cost_per_distance_90;
						$model->tsdr_min_vnd_cost_per_distance_180		 = $value->tsdr_min_vnd_cost_per_distance_180;
						$model->tsdr_max_vnd_cost_per_distance_180		 = $value->tsdr_max_vnd_cost_per_distance_180;
						$model->tsdr_avg_vnd_cost_per_distance_180		 = $value->tsdr_avg_vnd_cost_per_distance_180;
						$model->tsdr_median_vnd_cost_per_distance_180	 = $value->tsdr_median_vnd_cost_per_distance_180;
						$model->tsdr_min_vnd_cost_per_duration_90		 = $value->tsdr_min_vnd_cost_per_duration_90;
						$model->tsdr_max_vnd_cost_per_duration_90		 = $value->tsdr_max_vnd_cost_per_duration_90;
						$model->tsdr_avg_vnd_cost_per_duration_90		 = $value->tsdr_avg_vnd_cost_per_duration_90;
						$model->tsdr_median_vnd_cost_per_duration_90	 = $value->tsdr_median_vnd_cost_per_duration_90;
						$model->tsdr_min_vnd_cost_per_duration_180		 = $value->tsdr_min_vnd_cost_per_duration_180;
						$model->tsdr_avg_vnd_cost_per_duration_180		 = $value->tsdr_avg_vnd_cost_per_duration_180;
						$model->tsdr_median_vnd_cost_per_duration_180	 = $value->tsdr_median_vnd_cost_per_duration_180;
						$model->tsdr_max_vnd_cost_per_duration_180		 = $value->tsdr_max_vnd_cost_per_duration_180;
						$model->tsdr_active								 = $value->tsdr_active;
						$model->tsdr_create_at							 = $value->tsdr_create_at;
						$model->tsdr_updated_at							 = $value->tsdr_updated_at;
						if (!$model->save())
						{
							throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
						}
					}
					catch (Exception $ex)
					{
						Logger::exception($ex);
					}
					$flag = 1;
				}
				if ($flag == 1)
				{
					$dropQry = "DELETE FROM `travel_stats_dr` WHERE 1 AND  travel_stats_dr.tsdr_create_at<CURDATE()";
					DBUtil::execute($dropQry);
				}
				$returnSet->setStatus(true);
				$returnSet->setMessage("Data updated successfully");
			}
			else
			{
				$returnSet->setErrors("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});

		/* User Cities Lifetime Stats  */
		$this->onRest('req.post.asynUserCitiesLifeTime.render', function () {
			$returnSet			 = new ReturnSet();
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			if ($process_sync_data != null)
			{
				foreach ($jsonObj->data as $value)
				{

					try
					{
						$model = UserCitiesLifetime::model()->findByPk($value->ucf_id);
						if (empty($model))
						{
							$model					 = new UserCitiesLifeTime();
							$model->ucf_id			 = $value->ucf_id;
							$model->ucf_city_count	 = $value->ucf_city_count;
						}
						else
						{
							$model->ucf_city_count = $value->ucf_city_count;
						}
						$model->ucf_user_id			 = $value->ucf_user_id;
						$model->ucf_city_id			 = $value->ucf_city_id;
						$model->ucf_create_date		 = $value->ucf_create_date;
						$model->ucf_modified_date	 = $value->ucf_modified_date;
						$model->ucf_active			 = $value->ucf_active;
						if (!$model->save())
						{
							throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
						}
					}
					catch (Exception $ex)
					{
						Logger::exception($ex);
					}
				}

				$returnSet->setStatus(true);
				$returnSet->setMessage("Data updated successfully");
			}
			else
			{
				$returnSet->setErrors("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});

		/* User Cities Lifetime Stats  */
		$this->onRest('req.post.asynUserAirportCitiesLifeTime.render', function () {
			$returnSet			 = new ReturnSet();
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			if ($process_sync_data != null)
			{
				foreach ($jsonObj->data as $value)
				{
					try
					{
						$model = UserAirportCitiesLifetime::model()->findByPk($value->uacf_id);
						if (empty($model))
						{
							$model					 = new UserAirportCitiesLifetime();
							$model->uacf_id			 = $value->uacf_id;
							$model->uacf_city_count	 = $value->uacf_city_count;
						}
						else
						{
							$model->uacf_city_count =  $value->uacf_city_count;
						}
						$model->uacf_user_id		 = $value->uacf_user_id;
						$model->uacf_city_id		 = $value->uacf_city_id;
						$model->uacf_create_date	 = $value->uacf_create_date;
						$model->uacf_modified_date	 = $value->uacf_modified_date;
						$model->uacf_active			 = $value->uacf_active;
						if (!$model->save())
						{
							throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
						}
					}
					catch (Exception $ex)
					{
						Logger::exception($ex);
					}
				}

				$returnSet->setStatus(true);
				$returnSet->setMessage("Data updated successfully");
			}
			else
			{
				$returnSet->setErrors("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});

		/* User Month Life time Stats  */
		$this->onRest('req.post.asynUserMonthLifeTime.render', function () {
			$returnSet			 = new ReturnSet();
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			if ($process_sync_data != null)
			{
				foreach ($jsonObj->data as $value)
				{
					try
					{
						$model = UserMonthLifetime::model()->findByPk($value->uml_id);
						if (empty($model))
						{
							$model					 = new UserMonthLifetime();
							$model->uml_id			 = $value->uml_id;
							$model->uml_month_count	 = $value->uml_month_count;
						}
						else
						{
							$model->uml_month_count = $value->uml_month_count;
						}
						$model->uml_user_id			 = $value->uml_user_id;
						$model->uml_month_id		 = $value->uml_month_id;
						$model->uml_create_date		 = $value->uml_create_date;
						$model->uml_modified_date	 = $value->uml_modified_date;
						$model->uml_active			 = $value->uml_active;
						if (!$model->save())
						{
							throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
						}
					}
					catch (Exception $ex)
					{
						Logger::exception($ex);
					}
				}

				$returnSet->setStatus(true);
				$returnSet->setMessage("Data updated successfully");
			}
			else
			{
				$returnSet->setErrors("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});

		/* User Week Life time Stats  */
		$this->onRest('req.post.asynUserWeekLifeTime.render', function () {
			$returnSet			 = new ReturnSet();
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			if ($process_sync_data != null)
			{
				foreach ($jsonObj->data as $value)
				{
					try
					{
						$model = UserWeekLifetime::model()->findByPk($value->uwl_id);
						if (empty($model))
						{
							$model					 = new UserWeekLifetime();
							$model->uwl_id			 = $value->uwl_id;
							$model->uwl_week_count	 = $value->uwl_week_count;
						}
						else
						{
							$model->uwl_month_count =  $value->uwl_month_count;
						}
						$model->uwl_user_id			 = $value->uwl_user_id;
						$model->uwl_week_id			 = $value->uwl_week_id;
						$model->uwl_create_date		 = $value->uwl_create_date;
						$model->uwl_modified_date	 = $value->uwl_modified_date;
						$model->uwl_active			 = $value->uwl_active;
						if (!$model->save())
						{
							throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
						}
					}
					catch (Exception $ex)
					{
						Logger::exception($ex);
					}
				}

				$returnSet->setStatus(true);
				$returnSet->setMessage("Data updated successfully");
			}
			else
			{
				$returnSet->setErrors("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});

		/* User Service Class Life Time stats */
		$this->onRest('req.post.asynUserServiceClassLifeTime.render', function () {
			$returnSet			 = new ReturnSet();
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			if ($process_sync_data != null)
			{
				foreach ($jsonObj->data as $value)
				{
					try
					{
						$model = UserServiceClassLifetime::model()->findByPk($value->uscl_id);
						if (empty($model))
						{
							$model							 = new UserServiceClassLifetime();
							$model->uscl_id					 = $value->uscl_id;
							$model->uscl_service_class_count = $value->uscl_service_class_count;
						}
						else
						{
							$model->uscl_service_class_count = $value->uscl_service_class_count;
						}
						$model->uscl_user_id			 = $value->uscl_user_id;
						$model->uscl_service_class_id	 = $value->uscl_service_class_id;
						$model->uscl_create_date		 = $value->uscl_create_date;
						$model->uscl_modified_date		 = $value->uscl_modified_date;
						$model->uscl_active				 = $value->uscl_active;
						if (!$model->save())
						{
							throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
						}
					}
					catch (Exception $ex)
					{
						Logger::exception($ex);
					}
				}

				$returnSet->setStatus(true);
				$returnSet->setMessage("Data updated successfully");
			}
			else
			{
				$returnSet->setErrors("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});

		/* User Vehicle Class Life Time Stats  */
		$this->onRest('req.post.asynUserVehicleClassLifeTime.render', function () {
			$returnSet			 = new ReturnSet();
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			if ($process_sync_data != null)
			{
				foreach ($jsonObj->data as $value)
				{
					try
					{
						$model = UserVehicleClassLifetime::model()->findByPk($value->uvcl_id);
						if (empty($model))
						{
							$model							 = new UserVehicleClassLifetime();
							$model->uvcl_id					 = $value->uvcl_id;
							$model->uvcl_vehicle_class_count = $value->uvcl_vehicle_class_count;
						}
						else
						{
							$model->uvcl_vehicle_class_count =  $value->uvcl_vehicle_class_count;
						}
						$model->uvcl_user_id			 = $value->uvcl_user_id;
						$model->uvcl_vehicle_class_id	 = $value->uvcl_vehicle_class_id;
						$model->uvcl_create_date		 = $value->uvcl_create_date;
						$model->uvcl_modified_date		 = $value->uvcl_modified_date;
						$model->uvcl_active				 = $value->uvcl_active;
						if (!$model->save())
						{
							throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
						}
					}
					catch (Exception $ex)
					{
						Logger::exception($ex);
					}
				}

				$returnSet->setStatus(true);
				$returnSet->setMessage("Data updated successfully");
			}
			else
			{
				$returnSet->setErrors("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => $returnSet,
			]);
		});
	}

	// for showing csr total logintime and login/logoff option
	public function showCsrTotalTime()
	{
		$returnSet	 = new ReturnSet();
		$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
		$result		 = Admins::model()->authorizeAdmin($token);
		if ($result)
		{
			$admin	 = UserInfo::getUserId();
			$res	 = AdminOnoff::model()->getOnlineTimeStatus($admin);
			$res	 = 2.3;
		}
		else
		{
			$result	 = false;
			$message = 'Your not a authorised User.';
		}
		$checkStatus	 = AdminOnoff::model()->chkPresentStatus($admin);
		$userStatus		 = $checkStatus;
		$currentStatus	 = (($checkStatus == 1) ? 'Online' : 'Offline');
		$returnSet->setData(['userStatus' => (int) $userStatus, 'currentStatus' => $currentStatus, 'totalLoggingTime' => $res]);
		$returnSet->setStatus(true);
		return $returnSet;
	}

	//admin Shift On off status 
	public function shiftOnOffStatus()
	{
		$returnSet = new ReturnSet();
		try
		{
			$process_sync_data	 = Yii::app()->request->rawBody;
			$jsonObj			 = CJSON::decode($process_sync_data, false);
			$jsonMapper			 = new JsonMapper();
			/** @var \Stub\common\ShiftRequest $obj */
			$obj				 = $jsonMapper->map($jsonObj, new \Stub\common\ShiftRequest());
			/** @var AdminOnoff $model */
			$model				 = $obj->getModel();
			$returnSet			 = $model->checkAlertStatus($jsonObj->alertStatus);
			if ($returnSet->isSuccess())
			{
				$csrId				 = UserInfo::getUserId();
				$lastLogindetails	 = AdminOnoff::model()->getLastAdminsDetails($csrId);
				$lastLoginTime		 = $lastLogindetails['ado_login_confirm_time'] != null ? $lastLogindetails['ado_login_confirm_time'] : "";
				$returnSet->setStatus(true);
				$returnSet->setData(array("status" => (int) $model->ado_status, 'lastLoginTime' => $lastLoginTime, "alertStatus" => (int) $jsonObj->alertStatus));
			}
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

//eof
//for fetching user list
	public function actionList($status = null)
	{
		$this->pageTitle = 'Customers List';
		$pageSize		 = Yii::app()->params['listPerPage'];
		$userInfo		 = UserInfo::model();
		$uid			 = $userInfo->getUserId();
		$amodel			 = Admins::model()->findById($uid);
		$locals			 = $amodel->adm_chk_local;
		$promoId		 = Yii::app()->request->getParam('promoId');
		$qry			 = [];
		/* @var $model Users */
		$promoUserModel	 = new PromoUsers();
		$model			 = new Users();
		$model->promo_id = $promoId;

		$UserId = Yii::app()->request->getParam('userid', 0);
		if ($UserId > 0)
		{
			$userModel			 = Users::model()->findByPk($UserId);
			$model->search_email = $userModel->usr_email;
			$model->search_phone = $userModel->usr_mobile;
		}
		if ($_REQUEST['Users'])
		{
			$arr				 = Yii::app()->request->getParam('Users');
			$model->attributes	 = $arr;
			if (trim(Yii::app()->request->getParam('searchmarkuser')))
			{
				$model->search_marked_bad = 1;
			}
			$model->search_email				 = trim($arr['search_email']);
			$model->search_name					 = trim($arr['search_name']);
			$model->search_phone				 = trim($arr['search_phone']);
			$model->category					 = trim($arr['category']);
			$model->last_booking_create_date1	 = trim($arr['last_booking_create_date1']);
			$model->last_booking_create_date2	 = trim($arr['last_booking_create_date2']);
		}
		if ($_POST['export1'] == 1)
		{
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"UsersReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "UsersReport_" . date('Ymdhis') . ".csv";
			$foldername	 = Yii::app()->params['uploadPath'];
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$resDataArr	 = $model->search1($qry		 = '', true);

			$handle = fopen("php://output", 'w');
			fputcsv($handle, ['Name', 'Phone', 'Email', 'Category', 'Phone Verified', 'Email Verified', 'SignUp Date', 'Last Booking Created', 'Account Verified']);
			foreach ($resDataArr as $row)
			{
				$rowArray	 = array();
				$usrName	 = $row["usr_name"] . ' ' . $row["usr_lname"];
				if ($row["ctt_first_name"] != "" && $row["ctt_last_name"] != "")
				{
					$usrName = $row["ctt_first_name"] . ' ' . $row["ctt_last_name"];
				}
				if ($row["phn_phone_no"] != '')
				{
					$row["phn_phone_no"] = '+' . $row["phn_phone_country_code"] . $row["phn_phone_no"];
				}
				$rowArray['usr_name']				 = $usrName;
				$rowArray['phn_phone_no']			 = $row['phn_phone_no'];
				$rowArray['eml_email_address']		 = $row['eml_email_address'];
				$rowArray['cpr_category']			 = UserCategoryMaster::model()->findByPk([$row['cpr_category']])->ucm_label;
				$rowArray['phn_is_verified']		 = ($row["phn_is_verified"] == 1) ? "Yes" : "No";
				$rowArray['eml_is_verified']		 = ($row["eml_is_verified"] == 1) ? "Yes" : "No";
				$rowArray['usr_created_at']			 = DateTimeFormat::DateTimeToLocale($row["usr_created_at"]);
				$rowArray['urs_last_trip_created']	 = ($row['urs_last_trip_created'] != '') ? DateTimeFormat::DateTimeToLocale($row['urs_last_trip_created']) : "";
				$rowArray['usr_acct_verify']		 = ($row['usr_acct_verify'] == 1) ? 'Verified' : 'Not Verified';
				$row1								 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		if ($promoId != '' || $promoId != null)
		{
			$dataProvider	 = $model->searchPromo($qry			 = '');
		}
		else
		{
			$dataProvider	 = $model->search1($qry			 = '');
		}
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		if ($promoId != '' || $promoId != null)
		{
			$this->render('linkpromousers', array('dataProvider' => $dataProvider, 'status' => $status, 'model' => $model, 'qry' => $qry, 'promoId' => $promoId, 'promoUserModel' => $promoUserModel), false, true);
		}
		else
		{
			$this->render('users', array('dataProvider' => $dataProvider, 'status' => $status, 'model' => $model, 'qry' => $qry));
		}
	}

	public function actionLoginAsUser()
	{
		global $webUser;
		$key = Yii::app()->request->getParam('user');
		if ($key != '')
		{
			$userModel	 = Users::model()->find('activation_key=:user', array('user' => $key));
			$identity	 = new UserIdentity($userModel['email'], null);
			if ($identity->authenticate())
			{
				$webUser->login($identity);
			}
			$this->redirect(array('/users/view'));
		}
		Yii::app()->end();
	}

	public function actionDelete()
	{
		$id = Yii::app()->request->getParam('id');
		if ($id != '')
		{

			Users::deactivate($userId, $reason);

			$userModel				 = Users::model()->resetScope()->findByPk($id);
			$userModel->usr_active	 = 0;
			$userModel->save();
		}
		$this->redirect(array('user/list'));
	}

	public function actionAjaxemailcheck()
	{
		$newemail	 = Yii::app()->request->getParam('newemail');
		$oldemail	 = Yii::app()->request->getParam('oldemail');

		$nuserModel	 = Users::model()->findByEmail($newemail);
		$ouserModel	 = Users::model()->findByEmail($oldemail);
		$nuid		 = $nuserModel->user_id;
		$ouid		 = $ouserModel->user_id;

		$data = array('nuserid' => $nuid, 'ouserid' => $ouid);
		die(json_encode($data));
	}

	public function actionMarkedbadlist()
	{
		$usrId			 = Yii::app()->request->getParam('user_id');
		/* var $model Users */
		$model			 = new Users();
		$dataProvider	 = $model->markedBadListByUserId($usrId);
		$this->renderPartial('markedbadlist', array('model'			 => $model,
			'dataProvider'	 => $dataProvider, 'usrId'			 => $usrId));
	}

	public function actionResetmarkedbad()
	{
		$refId				 = Yii::app()->request->getParam('refId');
		/* var $model Users */
		$usrModel			 = Users::model()->findByPk($refId);
		$old_markbad_count	 = $usrModel->usr_mark_customer_count;
		$remark				 = $usrModel->usr_log;
		$usrModel->scenario	 = 'reset';

		if (isset($_POST['Users']))
		{
			$arr					 = Yii::app()->request->getParam('Users');
			$usrModel->attributes	 = $arr;
			$usrModel->resetScope();
			$dt						 = date('Y-m-d H:i:s');

			$user		 = Yii::app()->user->getId();
			$new_remark	 = $arr['usr_reset_desc'];
			$succes		 = false;
			if ($new_remark != '')
			{
				if ($usrModel->validate())
				{
					if ($new_remark != '')
					{
						if (is_string($remark))
						{
							$newcomm = CJSON::decode($remark);
							if ($remark != '' && CJSON::decode($remark) == '')
							{
								$newcomm = array(array(0 => $user, 1 => $usrModel->vhc_created_at, 2 => $remark, 3 => $old_markbad_count));
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
						$usrModel->usr_log = CJSON::encode($newcomm);
						try
						{
							$usrModel->usr_mark_customer_count	 = 0;
							$usrModel->save();
							$succes								 = true;
						}
						catch (Exception $e)
						{
							echo $e;
						}
					}
				}
				else
				{
					$errors = $usrModel->getErrors();
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



		$this->renderPartial('resetmarkedbad', array('refId' => $refId, 'usrModel' => $usrModel), false, true);
	}

	public function actionAddCredits()
	{
		$bookingId = Yii::app()->request->getParam('booking_id');
		if ($bookingId != '' && $bookingId > 0)
		{
			$bookingModel	 = Booking::model()->findByPk($bookingId);
			$userId			 = $bookingModel->bkgUserInfo->bkg_user_id;
			$bkgAmt			 = $bookingModel->bkgInvoice->bkg_total_amount;
		}
		else
		{
			$userId = Yii::app()->request->getParam('user_id');
		}
		$isRestricted = BookingInvoice::validateDateRestriction($bookingModel->bkg_pickup_date);
		if (!$isRestricted)
		{
			echo 'Sorry, you cannot add gozo coins now.';
			Yii::app()->end();
		}

		$userCreditsModel				 = new UserCredits;
		$userCreditsModel->scenario		 = 'creditbyadmin';
		$userCreditsModel->ucr_user_id	 = $userId;
		$creditModel					 = new UserCredits();
		$creditModel->ucr_user_id		 = $userId;
		if (isset($_REQUEST['UserCredits']))
		{
			$creditModel->attributes = $_REQUEST['UserCredits'];
		}
		//	$dataProvider	 = $creditModel->resetScope()->search();
		$dataProvider	 = $creditModel->getCreditsList('1', $userId);
		$data			 = ['success' => false, 'errors' => $result];
		if (isset($_POST['UserCredits']))
		{
			$userCreditsModel->attributes = $_POST['UserCredits'];

			if ($userCreditsModel->validate())
			{
				if ($userCreditsModel->ucr_validity != '' && $userCreditsModel->ucr_type != 2)
				{
					$userCreditsModel->ucr_validity = DateTimeFormat::DatePickerToDate($_POST['UserCredits']['ucr_validity']);
				}
				else
				{
					$userCreditsModel->ucr_validity = null;
				}
				if ($userCreditsModel->ucr_type == 1 || $userCreditsModel->ucr_type == 2)
				{
					if ($bookingModel)
					{
						$userCreditsModel->ucr_ref_id = $bookingModel->bkg_id;
					}
				}
				$userCreditsModel->ucr_status = $_POST['UserCredits']['activateType'];

				if ($userCreditsModel->save())
				{
					$data = ['success' => true, 'errors' => $result];
				}
			}
			else
			{
				$result													 = [];
				foreach ($userCreditsModel->getErrors() as $attribute => $errors)
					$result[CHtml::activeId($userCreditsModel, $attribute)]	 = $errors;
				$data													 = ['success' => false, 'errors' => $result];
			}

			if (Yii::app()->request->isAjaxRequest)
			{
				echo json_encode($data);
				Yii::app()->end();
			}
			if ($data['success'])
			{
				$this->redirect(array('list', 'tab' => $tab));
			}
		}
		$render = 'render';
		if (Yii::app()->request->isAjaxRequest)
		{
			$render = 'renderPartial';
		}
		$this->$render('addcredit', ['model' => $userCreditsModel, 'bkgAmt' => $bkgAmt, 'bookingId' => $bookingId, 'dataProvider' => $dataProvider['dataProvider']], false, true);
	}

	public function actionSendnotification()
	{
		$this->pageTitle = "Send Notification";
		$ntfModel		 = new Notification();
		if (isset($_POST['Notification']))
		{
			$arr					 = Yii::app()->request->getParam('Notification');
			$ntfModel->attributes	 = $arr;
			$ntfModel->ntf_status	 = 0;
			if ($ntfModel->save())
			{
				$ntfModel->unsetAttributes(['ntf_title', 'ntf_coin_value', 'ntf_message']);
			}
		}
		$this->render('sendnotification', array('model' => $ntfModel));
	}

	public function actionDetails()
	{
		$user			 = Yii::app()->request->getParam('user');
		$model			 = Users::model()->resetScope()->findByPk($user);
		$creditModel	 = new UserCredits();
		// Active Credits
		$data			 = $creditModel->getCreditsList('1', $user);
		// Pending Credits
		$data2			 = $creditModel->getCreditsList('2', $user);
		$totalBookings	 = $model->totBookingsWithStatus($user);
		$walletBalance	 = UserWallet::model()->getBalance($user);
		$totalAmount	 = $creditModel->getTotalActiveCredits($user);
		$this->renderPartial('details',
				['model'			 => $model,
					'dataProvider'	 => $data['dataProvider'],
					'dataProvider2'	 => $data2['dataProvider'],
					'walletBalance'	 => $walletBalance,
					'totalAmount'	 => $totalAmount,
					'totalBookings'	 => $totalBookings
				], false, true);
	}

	public function actionLinkedUsers()
	{
		$error				 = '[]';
		$returnSet			 = new ReturnSet();
		$username			 = Yii::app()->request->getParam('emailphone');
		$success			 = false;
		$userModel			 = new Users("userLoginEmailPhone");
		$userModel->username = $username;
		try
		{
			$errors = CActiveForm::validate($userModel, null, false);
			if ($errors != '[]')
			{
				throw new Exception($errors, ReturnSet::ERROR_VALIDATION);
			}
			$userModel->usernameType;

			$email	 = $userModel->usr_email;
			$phone	 = $userModel->usr_country_code . $userModel->usr_mobile;

			$cttRecords = Contact::getAllLinkedByEmailPhone($email, $phone);
			if ($cttRecords)
			{
				$count	 = count($cttRecords);
				$success = ($count > 0);
			}
			if ($success)
			{
				$html = $this->renderPartial('linkUsers', array('userModels' => $cttRecords), true);
			}
		}
		catch (Exception $exc)
		{
			$returnSet	 = ReturnSet::setException($exc);
			$error		 = json_encode($returnSet->getErrors());
		}

		//catchblock:
		echo json_encode(['success' => $success, 'userInfoHtml' => $html, 'userCount' => $count, 'error' => $error, 'typeEmlPh' => $userModel->usernameType]);
		exit;
	}

	public function login()
	{
		$process_sync_data	 = Yii::app()->request->getParam('data');
		Logger::create('Login DATA ===========>: ' . $process_sync_data, CLogger::LEVEL_TRACE);
		$data				 = CJSON::decode($process_sync_data, true);
		$email				 = $data['adm_username'];
		$password			 = $data['adm_password'];
		$deviceInfo			 = $data['adm_device_info'];
		$deviceID			 = $data['adm_deviceid'];
		$deviceVersion		 = $data['adm_version'];
		$apkVersion			 = $data['adm_apk_version'];
		$deviceTokenFcm		 = $data['apt_device_token'];
		$latitude			 = $data['latitude'];
		$longitude			 = $data['longitude'];
		$identity			 = new AdminIdentity($email, $password);
		if ($identity->authenticate())
		{
			$userID = $identity->getId();
//			$userModel	 = Admins::model()->resetScope()->find('adm_id=:id AND adm_active IN (1)', ['id' => $userID]);

			$appToken = AppTokens::model()->findAll('(apt_device_uuid=:device OR apt_user_id=:user) AND apt_status=:status  ', array('device' => $deviceID, 'user' => $userID, 'status' => 1));
			foreach ($appToken as $app)
			{

				$app->apt_status = 0;
				$app->update();
			}

			Yii::app()->user->login($identity);
			$sessionId							 = Yii::app()->getSession()->getSessionId();
			$appTokenModel						 = new AppTokens();
			$appTokenModel->apt_user_id			 = $userID;
			$appTokenModel->apt_token_id		 = $sessionId;
			$appTokenModel->apt_device			 = $deviceInfo;
			$appTokenModel->apt_last_login		 = new CDbExpression('NOW()');
			$appTokenModel->apt_device_uuid		 = $deviceID;
			$appTokenModel->apt_user_type		 = 6;
			$appTokenModel->apt_apk_version		 = $apkVersion;
			$appTokenModel->apt_device_token	 = $deviceTokenFcm;
			$appTokenModel->apt_ip_address		 = $_SERVER['REMOTE_ADDR'];
			$appTokenModel->apt_os_version		 = $deviceVersion;
			$appTokenModel->apt_last_loc_lat	 = $latitude;
			$appTokenModel->apt_last_loc_long	 = $longitude;
			if ($appTokenModel->save())
			{
				$adminOnOffModel				 = new AdminOnoff();
				$adminOnOffModel->ado_admin_id	 = $userID;
				$adminOnOffModel->ado_time		 = Filter::getDBDateTime();
				$adminOnOffModel->ado_lat		 = $latitude;
				$adminOnOffModel->ado_lng		 = $longitude;
				$adminOnOffModel->ado_status	 = 1;
				$adminOnOffModel->save();
			}
			$result = ['success' => true, 'sessionId' => $appTokenModel->apt_token_id, 'errors' => $appTokenModel->getErrors()];
		}
		else
		{
			$result = ['success' => false];
		}
		return $result;
	}

	public function loginV1()
	{
		$process_sync_data	 = Yii::app()->request->rawBody;
		Logger::create('Login DATA ===========>: ' . $process_sync_data, CLogger::LEVEL_TRACE);
		$data				 = CJSON::decode($process_sync_data, true);

		$email			 = $data['userName'];
		$password		 = $data['password'];
		$device			 = $data['device'];
		$deviceInfo		 = $device['deviceName'];
		$deviceID		 = $device['uniqueId'];
		$deviceTokenFcm	 = $device['token'];
		$deviceVersion	 = $device['osVersion'];
		$apkVersion		 = $device['version'];

		$identity = new AdminIdentity($email, $password);
		if ($identity->authenticate())
		{
			$userID				 = $identity->getId();
			$isFieldExecutive	 = Admins::checkFieldExecutive($userID);
			if ($isFieldExecutive == 0)
			{
				$appToken = AppTokens::model()->findAll('(apt_device_uuid=:device OR apt_user_id=:user) AND apt_status=:status AND apt_user_type = 6 ', array('device' => $deviceID, 'user' => $userID, 'status' => 1));
				foreach ($appToken as $app)
				{
					$app->apt_status = 0;
					$app->update();
				}
			}

			Yii::app()->user->login($identity);
			$sessionId							 = Yii::app()->getSession()->getSessionId();
			$appTokenModel						 = new AppTokens();
			$appTokenModel->apt_user_id			 = $userID;
			$appTokenModel->apt_entity_id		 = $userID;
			$appTokenModel->apt_token_id		 = $sessionId;
			$appTokenModel->apt_device			 = $deviceInfo;
			$appTokenModel->apt_last_login		 = new CDbExpression('NOW()');
			$appTokenModel->apt_device_uuid		 = $deviceID;
			$appTokenModel->apt_user_type		 = 6;
			$appTokenModel->apt_apk_version		 = $apkVersion;
			$appTokenModel->apt_device_token	 = $deviceTokenFcm;
			$appTokenModel->apt_ip_address		 = $_SERVER['REMOTE_ADDR'];
			$appTokenModel->apt_os_version		 = $deviceVersion;
			$appTokenModel->apt_last_loc_lat	 = $latitude;
			$appTokenModel->apt_last_loc_long	 = $longitude;
			if ($appTokenModel->save())
			{
				$result = ['success' => true, 'sessionId' => $appTokenModel->apt_token_id, 'errors' => $appTokenModel->getErrors()];
			}
			else
			{
				$result = ['success' => false, 'msg' => 'Unable to Login.'];
			}
		}
		else
		{
			$result = ['success' => false, 'msg' => 'Invalid User Name or password.'];
		}
		return $result;
	}

	public function getValidationApp($data, $id, $activeVersion)
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
			$msg	 = "Invalid User Data";
		}
		if (version_compare($data['apt_apk_version'], $activeVersion) < 0)
		{
			$is_apk_updated	 = false;
			$version_message = "Invalid Version";
			$sessioncheck	 = Yii::app()->params['opsappsessioncheck'];
		}
		else
		{
			$is_apk_updated	 = true;
			$version_message = "Valid Version";
			$sessioncheck	 = '';
		}
		$result = array('active' => $active, 'success' => $success, 'message' => $msg, 'sessioncheck' => $sessioncheck, 'is_apk_updated' => $is_apk_updated, 'version_message' => $version_message);
		return $result;
	}

	public function actionFinancialReport()
	{
		$this->redirect(Yii::app()->homeUrl . 'admpnl/report/financialReport');
	}

//        public function createLog($identity, $deviceInfo) {
//            $sessionid = Yii::app()->getSession()->getSessionId();
//            $admlogModel = new AdminLog();
//            $admlogModel->adm_log_in_time = new CDbExpression('Now()');
//            $admlogModel->adm_log_ip = $_SERVER['REMOTE_ADDR'];
//            $admlogModel->adm_log_session = $sessionid;
//            $admlogModel->adm_log_device_info = $deviceInfo;
//            $admlogModel->adm_log_user = $identity->getId();
//            $admlogModel->save();
//            return true;
//        }


	public function actionSocialList()
	{
		$pagetitle		 = "Social Link Listing";
		$this->pageTitle = $pagetitle;
		$model			 = new Users();
		$request		 = Yii::app()->request;
		if ($request->getParam('Users'))
		{
			$model->search = $request->getParam('Users')['search'];
		}
		$dataProvider	 = $model->getSocialListUsers($arr);
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('socialllinklist', array('model' => $model, 'dataProvider' => $dataProvider), null, $outputJs);
	}

	public function actionUnlinkSocialAccount()
	{
		$model			 = new Users();
		$pagetitle		 = "Social Link Listing";
		$this->pageTitle = $pagetitle;
		$user_id		 = Yii::app()->request->getParam('user_id');
		$modelVendors	 = Vendors::model()->getAllVendorIdsByUserId($user_id);
		$modelDrivers	 = Drivers::model()->getAllDriverIdsByUserId($user_id);
		$vendorIds		 = "";
		$driverIds		 = "";
		try
		{
			$transaction = DBUtil::beginTransaction();
			if (count($modelVendors) > 0)
			{
				for ($i = 0; $i < count($modelVendors); $i++)
				{
					$modelVendor = Vendors::model()->findByPk($modelVendors[$i]['vnd_id']);
					if ($modelVendor != NULL)
					{
						$vendorIds					 .= $modelVendors[$i]['vnd_id'] . ",";
						$modelVendor->vnd_user_id	 = NULL;
						$modelVendor->save();
						$vendorPref					 = VendorPref::model()->updateVendorPrefByVendorId($modelVendors[$i]['vnd_id']);
						Users::model()->logoutByUserId($modelVendors[$i]['vnd_user_id']);
						$userInfo					 = UserInfo::getInstance();
						VendorsLog::model()->createLog($modelVendors[$i]['vnd_id'], "Vendor social account removed from vendors having $modelVendors[$i]['vnd_id']", $userInfo, VendorsLog::VENDOR_SOCIAL_UNLINK, false, false);
					}
				}
			}
			if (count($modelDrivers) > 0)
			{
				for ($i = 0; $i < count($modelDrivers); $i++)
				{
					$modelDriver = Drivers::model()->findByPk($modelDrivers[$i]['drv_id']);
					if ($modelDriver != NULL)
					{
						$driverIds					 .= $modelDrivers[$i]['drv_id'] . ",";
						$modelDriver->drv_user_id	 = NULL;
						$modelDriver->save();
						Users::model()->logoutByUserId($modelDrivers[$i]['drv_user_id']);
						$userInfo					 = UserInfo::getInstance();
						DriversLog::model()->createLog($modelDrivers[$i]['drv_id'], "Driver social account removed from driver having  $modelDrivers[$i]['drv_id']", $userInfo, DriversLog::DRIVER_SOCIAL_UNLINK, false, false);
					}
				}
			}
			$vendor	 = array();
			$model	 = Users::model()->getProfileCacheByUserId($user_id);
			for ($i = 0; $i < count($model); $i++)
			{
				$arr				 = array();
				$arr['identifier']	 = $model[$i]['identifier'];
				$dataprofiledata	 = explode('"email";', $model[$i]['profile_cache']);
				$dataprofiledata	 = explode(';', $dataprofiledata[1]);
				$dataprofiledata	 = explode(':"', $dataprofiledata[0]);
				$socialemail		 = trim($dataprofiledata[1], '"');
				$arr['socialemail']	 = $socialemail;
				$arr['userid']		 = $user_id;
				$vendor[]			 = $arr;
			}
			$vendor['vendorId']	 = trim($vendorIds, ',');
			$vendor['driverId']	 = trim($driverIds, ',');
			$desc				 = json_encode($vendor);
			$userInfo			 = UserInfo::getInstance();
			UserSocialLog::model()->createLog($user_id, $desc, $userInfo, UserSocialLog::USER_SOCIAL_UNLINK, false, false);
			Users::model()->deleteUserFromImpUserAuth($user_id);
			DBUtil::commitTransaction($transaction);
			Yii::app()->user->setFlash('success', "Social account unlink successfully");
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
		}
		$this->redirect(array('user/sociallist'));
	}

	public function actionAddVoucher()
	{
		$data			 = [];
		$this->pageTitle = "Add Voucher To Customer";
		$uid			 = Yii::app()->request->getParam('user_id');
		$model			 = new VoucherUsers();

		if (!empty($_POST['VoucherUsers']))
		{
			$arr1 = Yii::app()->request->getParam('VoucherUsers');

			$model->attributes = $arr1;
			if ($arr1['vus_valid_till'] != '')
			{
				$validTillDate			 = DateTimeFormat::DatePickerToDate($arr1['vus_valid_till']) . " 00:00:00";
				$model->vus_valid_till	 = $validTillDate;
			}
			$model->scenario = "add";
			if (trim($model->vus_vch_id))
			{
				$vouchModel				 = Vouchers::model()->findByPk($model->vus_vch_id);
				$model->vus_max_allowed	 = $vouchModel->vch_max_allowed_limit;
				$res					 = VoucherUsers::checkIfVoucherExists($model->vus_user_id, $model->vus_vch_id);
				if ($res['cnt'] > 0)
				{
					$data = ["success" => 3];
					goto endLine;
				}
				$maxAllow = VoucherUsers::countVouchers($model->vus_vch_id);
				if ($maxAllow['cnt'] >= $model->vus_max_allowed)
				{
					$data = ["success" => 4];
					goto endLine;
				}
				if ($vouchModel->vch_valid_to != "")
				{
					if (strtotime($model->vus_valid_till) > strtotime($vouchModel->vch_valid_to))
					{
						$vdate	 = date("F j, Y", strtotime($vouchModel->vch_valid_to));
						$data	 = ["success" => 5, "error" => "Validity Date should not exceed " . $vdate];
						goto endLine;
					}
				}
			}
			if ($model->save())
			{
				$data = ["success" => 1];
			}
			else
			{
				$errors = "";
				foreach ($model->errors as $v)
				{
					foreach ($v as $v1)
					{
						$errors .= $v1 . "";
					}
				}
				$data = ["success" => 0, "error" => $errors];
			}
			endLine:
			echo json_encode($data);
			Yii::app()->end();
		}
		$this->renderPartial('addvoucher', array('model' => $model, 'uid' => $uid), false, true);
	}

	public function actionVoucherList()
	{
		$outputJs								 = 1;
		$this->pageTitle						 = "Voucher List";
		$uid									 = Yii::app()->request->getParam('user_id');
		$model									 = new VoucherUsers();
		$dataProvider							 = VoucherUsers::getVoucherList($uid);
		$dataProvider->getPagination()->params	 = array_filter($_REQUEST);
		$dataProvider->getSort()->params		 = array_filter($_REQUEST);
		$success								 = false;
		$method									 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('voucherlist', ['dataProvider' => $dataProvider, 'model' => $model], false, true);
	}

	public function actionVoucherlistbyquery()
	{
		header('Cache-Control: max-age=28800, public', true);
		$query	 = Yii::app()->request->getParam('q');
		$data	 = Yii::app()->cache->get("allvoucherlistbyQuery1_{$query}_");
		if ($data === false)
		{
			$data = Vouchers::getAllVoucherForSpecificUserJSON($query);
			Yii::app()->cache->set("allvoucherlistbyQuery1_{$query}_", $data, 21600);
		}
		echo $data;
		Yii::app()->end();
	}

	public function actionWalletlist()
	{

		$this->pageTitle = 'Gozo Wallet History';
		/* var $model UserCredits */

		$dataProvider = AccountTransDetails::getWalletList();

		$this->render('walletlist', ['dataProvider' => $dataProvider]);
	}

	public function actionShowwalletdetails()
	{
		$this->pageTitle = 'Wallet Details';

		$user	 = Yii::app()->request->getParam('user', 0);
		$model	 = Users::model()->resetScope()->findByPk($user);
		if (!$model)
		{
			echo "User does not exist";
			Yii::app()->end();
		}

		$walletBallance	 = UserWallet::model()->getBalance($user);
		$lockedBallance	 = UserWallet::getLockedBalance($user);
		$dataProvider	 = UserWallet::model()->getTransHistory($user, Accounting::LI_WALLET);
		$this->render('walletDetails', [
			'dataProvider'	 => $dataProvider,
			'walletBalance'	 => $walletBallance, 'lockedBalance'	 => $lockedBallance]
		);
	}

	public function actionSendResetPasswordLink()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$msg	 = "no user found";
			$userId	 = Yii::app()->request->getParam('id');
			$agentId = Yii::app()->request->getParam('agt_id');
			if ($agentId)
			{
				$sourceType = UserInfo::TYPE_AGENT;
			}
			else
			{
				$sourceType = UserInfo::TYPE_CONSUMER;
			}
			if ($userId)
			{
				$emailWrapper = new emailWrapper();
				switch ($sourceType)
				{
					case UserInfo::TYPE_CONSUMER:
						$userType	 = EmailLog::Consumers;
						$refId		 = EmailLog::REF_USER_ID;
						break;
					case UserInfo::TYPE_AGENT:
						$userType	 = EmailLog::Agent;
						$refId		 = EmailLog::REF_AGENT_ID;
						break;
					default:
						break;
				}
				$response = $emailWrapper::sendResetPasswordLink($userId, $agentId, $userType, $refId);
				if ($response)
				{
					$msg = "Send email link successfully please check your email to change password";
				}
			}
		}
		echo CJSON::encode($msg);
		Yii::app()->end();
	}

	public function actionResetPasswordByPhone()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$msg		 = "No user found";
			$userId		 = Yii::app()->request->getParam('id');
			$usertype	 = SmsLog::Consumers;
			if ($userId)
			{
				$smsWrapper	 = new smsWrapper();
				$response	 = $smsWrapper::sendResetPasswordLink($userId, $userType);
				if ($response)
				{
					$msg = "Send link successfully please check your sms to change password";
				}
			}
		}
		echo CJSON::encode($msg);
		Yii::app()->end();
	}

	public function actionView()
	{
		$this->pageTitle = 'User Details';
		$consumerId		 = Yii::app()->request->getParam('id');
		$view			 = Yii::app()->request->getParam('view', 'view');
		$contact		 = Contact::model()->getByUserId($consumerId);
		$models			 = Users::model()->getContactViewDetails($consumerId);
		$model			 = Users::model()->resetScope()->findByPk($consumerId);
		$totalBookings	 = (!empty($model)) ? $model->totBookingsWithStatus($consumerId) : '';
		$creditModel	 = new UserCredits();
		$homeCity						 = !empty($models) && $models->ctt_city != null ? $models->ctt_city : '';
		$totalUserCitiesLifetime		 = UserCitiesLifetime::getTopCity($consumerId, $homeCity, 10);
		$totalUserAirportCitiesLifetime	 = UserAirportCitiesLifetime::getTopCity($consumerId, $homeCity, 10);
		$totalUserMonthLifetime			 = UserMonthLifetime::getTopUserMonth($consumerId);
		$totalUserWeekLifetime			 = UserWeekLifetime::getTopUserWeek($consumerId,10);
		$totalUserServiceClassLifetime	 = UserServiceClassLifetime::getTopServiceClass($consumerId,10);
		$totalUserVehicleClassLifetime	 = UserVehicleClassLifetime::getTopVehicleClass($consumerId,10);
//		$totalUserCitiesLifetime = UserCitiesLifetime::getTopCity($homeCity, 10);
//		$totalUserCitiesLifetime = UserCitiesLifetime::getTopCity($homeCity, 10);
//		$totalUserCitiesLifetime = UserCitiesLifetime::getTopCity($homeCity, 10);
//		$totalUserCitiesLifetime = UserCitiesLifetime::getTopCity($homeCity, 10);
		//Total Active Credits
		$totalAmount					 = $creditModel->getTotalActiveCredits($consumerId);
		$walletBalance					 = UserWallet::model()->getBalance($consumerId);

		//Getting the Social Details of Users
		$userIdArr	 = Users::model()->getUserSocialDetails($consumerId);
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method($view, array(
			'contact'						 => $contact,
			'model'							 => $models['userModel'],
			'bookingmodel'					 => $models['bookingModel'],
			"ongoingbooking"				 => $models['ongoingbooking'],
			'upcomingbooking'				 => $models['upcomingbooking'],
			'totalGozoCoins'				 => $totalAmount,
			'totalBookings'					 => $totalBookings,
			'userModel'						 => $model,
			'walletBalance'					 => $walletBalance,
			'UserIdArr'						 => $userIdArr,
			'totalUserCitiesLifetime'		 => $totalUserCitiesLifetime,
			'totalUserAirportCitiesLifetime' => $totalUserAirportCitiesLifetime,
			'totalUserMonthLifetime'		 => $totalUserMonthLifetime,
			'totalUserWeekLifetime'		 => $totalUserWeekLifetime,
			'totalUserServiceClassLifetime'	 => $totalUserServiceClassLifetime,
			'totalUserVehicleClassLifetime'	 => $totalUserVehicleClassLifetime,
			'isAjax'						 => $outputJs), false, $outputJs);
	}

	public function actionDeviceHistory()
	{
		$drv_id		 = Yii::app()->request->getParam('userId');
		$viewType	 = Yii::app()->request->getParam('view');
		$model		 = new AppTokens();
		$request	 = Yii::app()->request;
		if ($request->getParam('AppTokens'))
		{
			$arr1					 = $request->getParam('AppTokens');
			$date1					 = $model->apt_last_login1	 = $arr1['apt_last_login1'] != null ? $arr1['apt_last_login1'] : date("Y-m-d", strtotime("-1 month"));
			$date2					 = $model->apt_last_login2	 = $arr1['apt_last_login2'] != null ? $arr1['apt_last_login2'] : date("Y-m-d");
		}
		else
		{
			$date1					 = $model->apt_last_login1	 = date("Y-m-d", strtotime("-3 month"));
			$date2					 = $model->apt_last_login2	 = date("Y-m-d");
		}
		$date1									 = $date1 . " 00:00:00";
		$date2									 = $date2 . " 23:59:59";
		$dataProvider							 = AppTokens::model()->getByDriverId($drv_id, $date1, $date2, 'consumer');
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$outputJs								 = Yii::app()->request->isAjaxRequest;
		$method									 = "render" . (($outputJs) ? "Partial" : "");

//Partial
		$this->$method('deviceHistory', ['dataProvider'	 => $dataProvider,
			'model'			 => $model], false, true);
	}

	public function actionUnlinkSocialAcc()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$msg		 = "No user found";
			$userId		 = Yii::app()->request->getParam('id');
			$provider	 = Yii::app()->request->getParam('provider');

			if ($userId)
			{

				$response = Users::model()->deleteSocialDetails($userId, $provider);
				if ($response)
				{
					$msg = "Unlinked";
				}
				else
				{
					$msg = "No Unlinked";
				}
			}
		}
		echo CJSON::encode($msg);
		Yii::app()->end();
	}

	public function actionuserTripDetails()
	{
		$model		 = new Users;
		$userId		 = Yii::app()->request->getParam('userId');
		$searchBy	 = Yii::app()->request->getParam('searchBy', '1');

		$tripdetails = Users::model()->getTripDetailsbyUser($userId, $searchBy);
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('customerbookings', ['tripdetails' => $tripdetails, 'model' => $model, "user" => $userId], false, true);
	}

	/**
	 * This function is used to show app usage in Customer, Vendor and driver Profile
	 * param int userId
	 * param int userType
	 * return view
	 */
	public function actionappUsage()
	{
		$userId									 = Yii::app()->request->getParam('userId');
		$type									 = Yii::app()->request->getParam('userType');
		$model									 = new AppTokens();
		$dataProvider							 = AppTokens::model()->getAppUsage($userId, $type);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$outputJs								 = Yii::app()->request->isAjaxRequest;
		$method									 = "render" . (($outputJs) ? "Partial" : "");

		//Partial
		$this->$method('appusage', ['dataProvider'	 => $dataProvider,
			'model'			 => $model], false, true);
	}

	/*
	 * This function is used for Force Logout button functionality in vendor View > AppUsage tab
	 * param char aptToken
	 * return json object for success message
	 */

	public function actionforceLogout()
	{
		$apptoken = Yii::app()->request->getParam('aptToken');
		if ($apptoken != null && $apptoken != '')
		{
			$success = Users::model()->doLogout($apptoken);
			if ($success == true)
			{
				echo json_encode(['success' => true, 'message' => "Force Logout is Successful."]);
			}
			else
			{
				echo json_encode(['success' => false, 'message' => "Force Logout is not Successful."]);
			}
		}
		else
		{
			echo json_encode(['success' => false, 'message' => "No Token Found"]);
		}
		Yii::app()->end();
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

			$ntlLogList	 = NotificationLog::getDetails($usrId);
			$ntlList	 = new Stub\common\Notification();
			$ntlList->getList($ntlLogList);
			$response	 = Filter::removeNull($ntlList);
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
	 * This function is used for return last admin working/ non working status
	 */
	public function checkOnOffStatus()
	{
		$returnSet = new ReturnSet();
		try
		{
			$csrId = UserInfo::getUserId();

			if ($csrId)
			{
				$lastLogindetails				 = AdminOnoff::model()->getLastAdminsDetails($csrId);
				$lastLoginTime					 = $lastLogindetails['ado_login_confirm_time'] != null ? $lastLogindetails['ado_login_confirm_time'] : "";
				$getCountInternalCBRbyTeam		 = ServiceCallQueue::countInternalActiveCBRbyTeam();
				$getCountInternalCBRbyAdminID	 = ServiceCallQueue::countInternalActiveCBRbyAdminID();
				$returnSet->setStatus(true);
				$returnSet->setData(array("status" => (int) $lastLogindetails['ado_status'], 'lastLoginTime' => $lastLoginTime, 'CBRbyTeam' => (int) $getCountInternalCBRbyTeam, 'CBRbyAdmin' => (int) $getCountInternalCBRbyAdminID));
			}
			else
			{
				throw new Exception("Unauthorised access", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	/**
	 * This function is used to show entity
	 */
	public function entityList()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$jsonObj	 = CJSON::decode($data, false);
		try
		{
			$search_txt	 = $jsonObj->qry;
			$entity_type = $jsonObj->id;
			if ($entity_type && $search_txt)
			{

				switch ($entity_type)
				{
					case 1: //consumer
						$customerList	 = Users::getByName($search_txt);
						$dataObj		 = new Stub\common\User();
						$dataObj		 = $dataObj->setUsrData($customerList);
						break;

					case 2: // vendor
						$vendorList	 = Vendors::getByName($search_txt);
						$dataObj	 = new Stub\common\Vendor();
						$dataObj	 = $dataObj->setVndData($vendorList);
						break;
					case 3: //driver
						$driverList	 = Drivers::getByName($search_txt);

						$dataObj	 = new Stub\common\Driver();
						$dataObj	 = $dataObj->getData($driverList);
						break;
					case 4: //admin
						$adminList	 = Admins::getByName($search_txt);
						$dataObj	 = new Stub\common\Admin();
						$dataObj	 = $dataObj->getData($adminList);
						break;
					case 5: //agent
						$agentList	 = Agents::getByName($search_txt);
						$dataObj	 = new Stub\common\Agent();
						$dataObj	 = $dataObj->getAgtData($agentList);
						break;
					default:
						break;
				}
			}

			$response = Filter::removeNull($dataObj);
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

	public function actionGetGozoCoinDetails()
	{
		$consumerId	 = Yii::app()->request->getParam('userId');
		$creditModel = new UserCredits();
		$data		 = $creditModel->getCreditsList('1', $consumerId);
		// Pending Credits
		$data2		 = $creditModel->getCreditsList('2', $consumerId);
		//Total Active Credits
		$totalAmount = $creditModel->getTotalActiveCredits($consumerId);
		$this->renderPartial("gozoCoinsData", ['gozocoinsdetails' => $data['dataProvider'], 'gozocoinsdetailspending' => $data2['dataProvider'], 'totalGozoCoins' => $totalAmount], false, true);
	}

	public function actionUserWalletDetails()
	{
		$consumerId		 = Yii::app()->request->getParam('userId');
		$walletBalance	 = UserWallet::model()->getBalance($consumerId);
		$dataProvider3	 = UserWallet::model()->getTransHistoryCustomer($consumerId, Accounting::LI_WALLET);
		$this->renderPartial("showWalletData", ['dataProvider3' => $dataProvider3, 'walletBalance' => $walletBalance, 'consumerId' => $consumerId], false, true);
	}

	public function actionPaymentTransactionDetails()
	{
		$consumerId		 = Yii::app()->request->getParam('userId');
		$paymentProvider = PaymentGateway::getUserPaymentDetails($consumerId, 1);
		$this->renderPartial("paymentTransactionDetails", ['paymentProvider' => $paymentProvider], false, true);
	}

	public function actionGetCbrDetailsDetails()
	{
		$consumerId	 = Yii::app()->request->getParam('userId');
		$cbrDetails	 = ServiceCallQueue::model()->getCBRDetailbyId($consumerId, "Consumer");
		$this->renderPartial("getCbrDetails", ['cbrdetails' => $cbrDetails], false, true);
	}

	public function actionDeactive()
	{
		$userId		 = Yii::app()->request->getParam('user_id');
		$reason		 = Yii::app()->request->getParam('usr_deactivate_reason');
		/* @var $model Users */
		$model		 = Users::model()->resetScope()->findByPk($userId);
		$returnSet	 = new ReturnSet();
		$success	 = false;
		if (isset($_POST['user_id']) && $_POST['user_id'] == $model->user_id)
		{
			$result		 = [];
			$returnSet	 = Users::deactivate($userId, $reason);
			if ($returnSet->getStatus())
			{
				$result['success'] = $returnSet->getStatus();
			}
			else
			{
				$getMessage			 = json_encode($returnSet->getMessage());
				$result['error']	 = $getMessage;
				$result['success']	 = $returnSet->getStatus();
			}

			if (Yii::app()->request->isAjaxRequest)
			{
				echo json_encode($result);
				Yii::app()->end();
			}
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('deactive_form', array('model' => $model), FALSE, $outputJs);
	}

}
