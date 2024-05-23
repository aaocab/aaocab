<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class UsersController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'column1';
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
				'users' => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array(
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users' => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions' => array(),
				'users' => array('admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function restEvents()
	{
		$this->onRest('req.cors.access.control.allow.methods', function() {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri = array('/signin', '/signup', '/newpassword', '/logout', '/profile_update', '/validate', '/validateversion', '/forgotpass');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.get.signup.render', function() {

			$process_sync_data = Yii::app()->request->getParam('data');
			$data1 = CJSON::decode($process_sync_data, true);
			$data = array_filter($data1);
			$data['vnd_username'] = $data['vnd_email'];
			$result = $this->register($data);
			if ($result['success'] == true)
			{
				$loginResult = $this->loginMeterdown($data);
				$data = ['login' => $loginResult, 'model' => JSONUtil::convertModelToArray($result['data'])];
			}
			else
			{
				$data = ['errors' => $result['errors']];
			}
			return $this->renderJSON(['type' => 'raw', 'data' => ['success' => $result['success'],] + $data,]);
		});

		$this->onRest('req.get.signin.render', function() {
			$process_sync_data = Yii::app()->request->getParam('data');
			$data1 = CJSON::decode($process_sync_data, true);
			// $data1=["vnd_username"=>"anuvaitd@gmail.com","vnd_password1"=>"weryut"];
			$data = array_filter($data1);
			$result = $this->loginMeterdown($data);
			return $this->renderJSON(['type' => 'raw', 'data' => $result]);
		});

		$this->onRest('req.get.forgotpass.render', function() {

			$forgot_email = Yii::app()->request->getParam('forgotemail');
			$code = Yii::app()->request->getParam('code');
			$email = Yii::app()->request->getParam('email');
			$status = false;
			if ($code != "" && $email != "")
			{
				$vendorModel = Vendors::model()->find("vnd_email=:mail AND vnd_code_password=:code", ['mail' => $email, 'code' => $code]);
				if ($vendorModel != "")
				{
					$status = true;
					$vendorModel->vnd_code_password = "";
					$vendorModel->update();
					$vendor = $vendorModel->vnd_id;
					$message = "code matched successfully.";
				}
				else
				{
					$message = "code don't matched.";
				}
			}
			else
			{

				if ($forgot_email != "")
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
			}

			return $this->renderJSON([
						'type' => 'raw',
						'data' => ['success' => $status, 'message' => $message, 'vendor' => $vendor]
			]);
		});

		$this->onRest('req.get.newpassword.render', function() {
			$userId = Yii::app()->request->getParam('vnd_id');
			$newPassword = Yii::app()->request->getParam('new_password');
			$success = false;
			$model = Vendors::model()->findByPk($userId);
			if ($model != '')
			{
				$model->vnd_password = md5($newPassword);
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
						'type' => 'raw',
						'data' => ['success' => $success, 'message' => $message, 'errors' => $errors]
			]);
		});
		$this->onRest('req.get.logout.render', function() {
			$userId = Yii::app()->user->getId();
			$sessionId = Yii::app()->getSession()->getSessionId();
			// $token = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$applogout = AppTokens::model()->find('apt_token_id = :token || apt_user_id = :userid', array('token' => $sessionId, 'userid' => $userId));
			if ($applogout)
			{
				$applogout->apt_status = 0;
				$applogout->apt_logout = new CDbExpression('NOW()');
				$logout = $applogout->save();
				Yii::app()->user->logout();
			}
			if ($logout)
			{
				$data = [
					'success' => true,
					'message' => "User logged out successfully"];
			}
			else
			{
				$data = [
					'success' => false,
					'errors' => ['error' => ['Error in logout']]];
			}
			return $this->renderJSON([
						'type' => 'raw',
						'data' => $data
			]);
		});
		$this->onRest('req.get.profile_update.render', function() {
			$process_sync_data = Yii::app()->request->getParam('data');
			$data1 = CJSON::decode($process_sync_data, true);
			//$data1=["vnd_email"=>"anuvaitd@gmail.com","vnd_name"=>"Test","vnd_company"=>"Test","vnd_phone"=>"1234567890","apt_device"=>"motorola XT1068","apt_os_version"=>21,"apt_device_uuid"=>"353326060717255","apt_apk_version"=>"1.0"];
			$data = array_filter($data1);
			$result = $this->updateProfile($data);
			if ($result['success'] == true)
			{
				$data = ['success' => $result['success'], 'data' => $result['data']];
			}
			else
			{
				$data = ['success' => $result['success'], 'errors' => $result['errors']];
			}
			return $this->renderJSON(['type' => 'raw', 'data' => $data]);
		});

		$this->onRest('req.get.validate.render', function() {
			$process_sync_data = Yii::app()->request->getParam('data');
			$data1 = CJSON::decode($process_sync_data, true);
			$data = array_filter($data1);
			$activeVersion = Yii::app()->params['meterdownappversion'];
			$id = Yii::app()->user->id;
			$result = $this->getValidationApp($data, $id, $activeVersion);
			$model = Vendors::model()->findByPk(Yii::app()->user->id);
			return $this->renderJSON([
						'type' => 'raw',
						'data' => array(
							'success' => $result['success'],
							'message' => $result['message'],
							'active' => $result['active'],
							'version' => $activeVersion,
							'data' => $model,
						)
			]);
		});

		$this->onRest('req.get.validateversion.render', function() {
			$process_sync_data = Yii::app()->request->getParam('data');
			$data1 = CJSON::decode($process_sync_data, true);
			$data = array_filter($data1);
			$activeVersion = Yii::app()->params['meterdownappversion'];

			if ($activeVersion > $data['apt_apk_version'])
			{
				$active = 0;
				$success = false;
				$msg = "Invalid Version";
				$sessioncheck = Yii::app()->params['meterdownappsessioncheck'];
			}
			else
			{
				$active = 1;
				$success = true;
				$msg = "Valid Version";
				$sessioncheck = '';
			}



			$result = array('active' => $active, 'success' => $success, 'message' => $msg, 'sessioncheck' => $sessioncheck);
			return $this->renderJSON([
						'type' => 'raw',
						'data' => array(
							'success' => $result['success'],
							'message' => $result['message'],
							'active' => $result['active'],
							'sessioncheck' => $result['sessioncheck'],
							'version' => $activeVersion,
						)
			]);
		});
	}

	public function forgotPassword()
	{

		$email = Yii::app()->request->getParam('forgotemail');
		$users = Vendors::model()->find("vnd_email=:email", ['email' => $email]);
		if (count($users) > 0)
		{
			$username = $users->vnd_name;
			$code = rand(999, 9999);
			$body = "<p>Please copy paste this code  <span style='color: #000000;font-weight:bold'>##CODE##</span> to reset password of your MeterDown account.</p><br><br>";
			$mail = new EIMailer();
			$body = str_replace("##CODE##", $code, $body);
			$mail->setLayout('meterdownmail');
			$mail->setTo($email, $username);
			$mail->setBody($body);
			$mail->isHTML(true);
			$mail->setSubject('Reset your Password');
			if ($mail->sendMeterDownEmail())
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$smsWrapper = new smsWrapper();
			$phone = $users->vnd_phone;
			if ($smsWrapper->sendForgotPassCode('91', $phone, $code))
			{
				$delivered1 = "Message sent successfully";
			}
			else
			{
				$delivered1 = "Message not sent";
			}
			$body = $mail->Body;
			$usertype = EmailLog::MeterDown;
			$subject = 'Reset your Password';
                        $refType = EmailLog::REF_VENDOR_ID;
                        $refId = $users->vnd_id;
			emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered,'', $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
			$users->vnd_code_password = $code;
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

	public function loginMeterdown($data)
	{
		$model = new Vendors('login');
		$model->attributes = $data;
		$email = $model->vnd_username;
		$password = md5($model->vnd_password1);
		//$type = $model->vnd_type;
		$identity = new MeterdownIdentity($email, $password);

		if ($identity->authenticate())
		{
			$userID = $identity->getId();
			Yii::app()->user->login($identity);
			$sessionId = Yii::app()->getSession()->getSessionId();

			$appTokenModel = new AppTokens();
			$appTokenModel->attributes = $data;
			$appTokenModel->apt_user_id = $userID;
			$appTokenModel->apt_token_id = $sessionId;
			$appTokenModel->apt_ip_address = \Filter::getUserIP();
			$appTokenModel->apt_last_login = new CDbExpression('NOW()');
			$appTokenModel->apt_user_type = 3;
			$appTokenModel->insert();
			$success = true;
			$loginData = ['sessionId' => $sessionId, 'model' => JSONUtil::convertModelToArray(Vendors::model()->findByPk($userID))];
		}
		else
		{
			$success = false;
			$loginData = ['errors' => ['error' => ["Failed to login"]]];
		}
		$result = ['success' => $success] + $loginData;

		return $result;
	}

	public function register($data)
	{
		$model = new Vendors('meterdowninsert');
		$model->attributes = $data;
		$model->vnd_tnc_datetime = new CDbExpression('NOW()');
		if ($model->vnd_route_served != '')
		{
			$route_served = implode(',', $model->vnd_route_served);
			$model->vnd_route_served = $route_served;
		}
		if ($model->vnd_password1 != "")
		{
			$model->vnd_password = md5($model->vnd_password1);
		}
		$tmodel = Terms::model()->getText(3);
		$model->vnd_tnc_id = $tmodel->tnc_id;
		$model->vnd_type = 1;
		$success = false;
		$errors = [];
		if ($model->validate())
		{
			try
			{
				$reg = $model->save();
				if (!$reg)
				{
					$errors = $model->getErrors();
				}
				else
				{
					$emailsend = new emailWrapper();
					$emailsend->meterdownSignup($model->vnd_id, $model->vnd_password1);
					$success = true;
				}
			}
			catch (Exception $e)
			{
				$errors = [$e->getMessage()];
			}
		}
		else
		{
			$errors = $model->getErrors();
		}

		$result = array('success' => $success,
			'errors' => $errors,
			'data' => $model
		);
		return $result;
	}

	public function updateProfile($data)
	{
		$userId = Yii::app()->user->getId();
		// $userId2 = Yii::app()->request->getParam('userid');
		$model = Vendors::model()->findByPk($userId);
		if ($model)
		{
			$data['vnd_username'] = $data['vnd_email'];
			$model->scenario = 'meterdownupdate';
			$model->attributes = $data;
			if ($model->vnd_route_served != '')
			{
				$route_served = implode(',', $model->vnd_route_served);
				$model->vnd_route_served = $route_served;
			}
			if ($model->vnd_password1 != "")
			{
				$model->vnd_password = md5($model->vnd_password1);
			}
			$success = false;
			$errors = [];
			if ($model->validate())
			{
				try
				{
					$reg = $model->save();
					if (!$reg)
					{
						$errors = $model->getErrors();
					}
					else
					{
						$success = true;
					}
				}
				catch (Exception $e)
				{
					$errors = [$e->getMessage()];
				}
			}
			else
			{
				$errors = $model->getErrors();
			}
		}
		else
		{
			$errors = ['error' => ['You are not logged in.']];
		}
		$result = array(
			'success' => $success,
			'errors' => $errors,
			'data' => $model
		);
		return $result;
	}

	public function getValidationApp($data, $id, $activeVersion)
	{
		if ($activeVersion > $data['apt_apk_version'])
		{
			$active = 1;
			$success = false;
			$msg = "Invalid Version";
		}
		else
		{
			if ($id != '')
			{
				$validate = AppTokens::model()->getAppValidations($data, $id);
				$active = 2;
				$success = true;
				$msg = "Validation Done";
			}
			else
			{
				$active = 3;
				$success = false;
				$msg = "Invalid User";
			}
		}
		$result = array('active' => $active, 'success' => $success, 'message' => $msg);
		return $result;
	}

}
