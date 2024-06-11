<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class UsersController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = 'main';
	public $email_receipient, $useUserReturnUrl;
	public $current_page = '';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
//            array(
//                'application.filters.HttpsFilter + create',
//                'bypass' => false),
			'accessControl', // perform access control for CRUD operations
			//    'postOnly + delete', // we only allow deletion via POST request
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
				'actions'	 => array('additionaldetails', 'creditlimit', 'recharge', 'changepassword', 'editprofile', 'bookingmsgdefaults', 'cpagreement'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index', 'signin', 'cpagreement',
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
		$this->onRest('req.cors.access.control.allow.methods', function()
		{
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function($validation)
		{
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array('/signin', '/signup', '/newpassword', '/forgotpass', '/validateversion', '/update_tnc', '/verify_phone', '/citylist', '/serviceable_citylist', '/profileupdate', '/forgotpnass');

			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.get.signup.render', function()
		{
			$process_sync_data		 = Yii::app()->request->getParam('data');
			$data1					 = CJSON::decode($process_sync_data, true);
			$data					 = array_filter($data1);
			$data['agt_username']	 = $data['agt_email'];
			$result					 = $this->register($data);
			return $this->renderJSON(['type' => 'raw', 'data' => ['success' => $result['success'], 'errors' => $result['errors'], 'model' => $result['data']]]);
		});

		$this->onRest('req.post.signin.render', function()
		{
			$process_sync_data		 = Yii::app()->request->getParam('data');
			$data1					 = CJSON::decode($process_sync_data, true);
			$data					 = array_filter($data1);
			$data['agt_password']	 = md5($data['agt_password1']);
			$result					 = $this->loginAgent($data);
			return $this->renderJSON(['type' => 'raw', 'data' => $result]);
		});

		$this->onRest('req.get.citylist.render', function()
		{
			$cities = Cities::model()->getAllCityListforUserApp();
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'	 => true,
							'message'	 => "City List",
							'cities'	 => $cities,
						)
			]);
		});

		$this->onRest('req.get.serviceable_citylist.render', function()
		{
			$cities = Cities::model()->getServiceableCityListforUserApp();
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'	 => true,
							'message'	 => "City List",
							'cities'	 => $cities,
						)
			]);
		});

		$this->onRest('req.post.change_password.render', function()
		{
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$data				 = array_filter($data1);
			$result				 = $this->changePassword($data);
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => $result,
			]);
		});

		$this->onRest('req.get.update_tnc.render', function()
		{
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$data				 = array_filter($data1);
			$result				 = Agents::model()->updatetnc($data);
			if ($result != false)
			{
				$result = ['success' => true, 'model' => JSONUtil::convertModelToArray($result)];
			}
			else
			{
				$result = ['success' => false, 'model' => ''];
			}
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => $result,
			]);
		});

		$this->onRest('req.get.forgotpnass.render', function()
		{
			return $this->emitRest("req.get.forgotpass.render");
		});

		$this->onRest('req.get.forgotpass.render', function()
		{
			$forgot_email	 = Yii::app()->request->getParam('forgotemail');
			$code			 = Yii::app()->request->getParam('code');
			$email			 = Yii::app()->request->getParam('email');
			$status			 = false;
			if ($code != "" && $email != "")
			{
				$agentModel = Agents::model()->find('agt_email=:mail AND agt_type IN(0,2) AND agt_code_password=:code', ['mail' => $email, 'code' => $code]);
				if ($agentModel != "")
				{
					$agentModel->agt_code_password	 = "";
					$agentModel->update();
					$status							 = true;
					$vendor							 = $agentModel->agt_id;
					$message						 = "code matched successfully.";
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
						'type'	 => 'raw',
						'data'	 => ['success' => $status, 'message' => $message, 'agent' => $vendor]
			]);
		});

		$this->onRest('req.get.verify_phone.render', function()
		{
			$code	 = Yii::app()->request->getParam('code');
			$email	 = Yii::app()->request->getParam('email');
			$status	 = false;
			if ($code != "" && $email != "")
			{
				$agentModel = Agents::model()->find('agt_email=:mail AND agt_verify_phone=:code AND agt_type IN(0,2)', ['mail' => $email, 'code' => $code]);
				if ($agentModel != "")
				{
					$agentModel->agt_verify_phone	 = "verified";
					$agentModel->update();
					$status							 = true;
					$agent							 = $agentModel->agt_id;
					$message						 = "code matched successfully.";
				}
				else
				{
					$message = "code didn't match.";
				}
			}
			else
			{
				if ($email != "")
				{
					$status = $this->verifyEmail();
					if ($status)
					{
						$message = "code successfully sent to the registered email.";
					}
					else
					{
						$message = "error sending code.";
					}
				}
			}

			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => ['success' => $status, 'message' => $message, 'agent' => $agent]
			]);
		});

		$this->onRest('req.post.newpassword.render', function()
		{
			$userId		 = Yii::app()->request->getParam('agt_id');
			$newPassword = Yii::app()->request->getParam('new_password');
			$success	 = false;
			$model		 = Agents::model()->findByPk($userId);
			if ($model != '')
			{
				$agetUsers				 = AgentUsers::model()->find('agu_agent_id=:agent AND agu_role=1', ['agent' => $userId]);
				$userModel				 = Users::model()->findByPk($agetUsers->agu_user_id);
				$userModel->usr_password = md5($newPassword);
				// $model->agt_password = md5($newPassword);
				// if ($model->update() && $userModel->update()) {
				if ($userModel->update())
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

		$this->onRest('req.get.logout.render', function()
		{
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$applogout	 = AppTokens::model()->find('apt_token_id = :token', array('token' => $token));
			if ($applogout)
			{
				$applogout->apt_status	 = 0;
				$applogout->apt_logout	 = new CDbExpression('NOW()');
				$logout					 = $applogout->save();
				Yii::app()->user->logout();
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

		$this->onRest('req.post.profiledetails.render', function()
		{
			$userId		 = Yii::app()->user->getAgentId();
			$userModel	 = Agents::model()->profiledetails($userId);
			if ($userModel != "")
			{
				$success = true;
			}
			else
			{
				$success = false;
			}
			$error	 = $userModel->error;
			$data	 = JSONUtil::convertModelToArray($userModel);
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'	 => $success,
							'error'		 => $error,
							'data'		 => $data,
							'userId'	 => $userId,
						)
			]);
		});

		$this->onRest('req.get.email_sms_count.render', function()
		{
			$success = false;
			$error	 = 'Something went wrong';
			$agt_id	 = Yii::app()->user->getAgentId();
			$model	 = Agents::model()->getEmailSmsCount($agt_id);
			if ($model != '')
			{
				$success = true;
				$error	 = '';
			}
			$process_sync_data = Yii::app()->request->getParam('data');
			if ($process_sync_data != '')
			{
				$data = CJSON::decode($process_sync_data, true);
				if ($data['flag'] != '')
				{
					$model				 = Agents::model()->findByPk($agt_id);
					$model->attributes	 = $data;
					$model->scenario	 = "emailSms";
					if ($model->validate())
					{
						$success = $model->save();
						if ($success)
						{
							$model	 = Agents::model()->getEmailSmsCount($agt_id);
							$error	 = '';
						}
					}
				}
			}
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'	 => $success,
							'error'		 => $error,
							'data'		 => $model,
							'agt_id'	 => $agt_id,
						)
			]);
		});

		$this->onRest('req.get.validate.render', function()
		{
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$data				 = array_filter($data1);
			$activeVersion		 = Config::get("Version.Android.agent");//Yii::app()->params['versionCheck']['agent'];
			$id					 = Yii::app()->user->id;
			$result				 = $this->getValidationApp($data, $id, $activeVersion);
			Yii::log("validate session " . json_encode($result), CLogger::LEVEL_INFO, 'system.api.validate');
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

		$this->onRest('req.get.validateversion.render', function()
		{
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$data				 = array_filter($data1);
			$activeVersion		 = Config::get("Version.Android.agent");//Yii::app()->params['versionCheck']['agent'];

			if ($activeVersion > $data['apt_apk_version'])
			{
				$active			 = 0;
				$success		 = false;
				$msg			 = "Invalid Version";
				$sessioncheck	 = Yii::app()->params['agentappsessioncheck'];
			}
			else
			{
				$active			 = 1;
				$success		 = true;
				$msg			 = "Valid Version";
				$sessioncheck	 = '';
			}

			$result = array('active' => $active, 'success' => $success, 'message' => $msg, 'sessioncheck' => $sessioncheck);
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

		$this->onRest('req.post.profileupdate.render', function()
		{
			$success			 = false;
			$process_sync_data	 = Yii::app()->request->getParam('data');
			//Yii::log("ftghfght" . $process_sync_data . "tyjytgg" . serialize($_FILES) . "rrttrgtr" . serialize($_POST), CLogger::LEVEL_ERROR, "system.api.images");
			$data1				 = CJSON::decode($process_sync_data, true);
			$data				 = array_filter($data1);
			$agentId			 = Yii::app()->user->getAgentId();

			$agentModel = Agents::model()->findByPk($agentId);
			if ($agentModel != '')
			{
				if ($data['agt_city'] != '')
				{
					$agentModel->agt_city = (int) $data['agt_city'];
				}
				$agentModel->attributes = $data;
				try
				{
					$image	 = $_POST['image'];
					$name	 = $_POST['name'];
					$image	 = base64_decode($image);
					Yii::log("ftghfghtdgf" . $image . "tyjfghytgg" . $name, CLogger::LEVEL_ERROR, "system.api.images");
					if ($image != '')
					{
						$name		 = "agent_" . $agentId . "_" . date('Ymd_His') . $name;
						$file_path	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'uploadedFiles' . DIRECTORY_SEPARATOR . $agentId;
						if (!is_dir($file_path))
						{
							mkdir($file_path);
						}
//                        $file_name = basename($image);
						$file_name	 = basename($name);
						$f			 = $file_path;
						$file_path	 = $file_path . DIRECTORY_SEPARATOR . $file_name;
						//file_put_contents(PUBLIC_PATH . '/testFile.txt', $f . ' ==== ' . $file_name);
						file_put_contents($file_path, $image);
						Yii::log("Image Path: \n\t Temp: " . $image . "\n\t Path: " . $f, CLogger::LEVEL_INFO, 'system.api.images');
						if ($this->img_resize($file_path, 1200, $f, $file_name))
						{
							$agentModel->agt_pic_path = substr($file_path, strlen(PUBLIC_PATH));
						}
					}
				}
				catch (Exception $e)
				{
					Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
					Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
					throw $e;
				}
				$agentModel->save();
				Yii::log("image path assigned: " . json_decode($agentModel->attributes), CLogger::LEVEL_INFO, 'system.api.images');
				$success = true;

				$message = $agentModel->error;
			}
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'	 => $success,
							'message'	 => $message,
							'errors'	 => $message,
							'data'		 => $agentModel
						),
			]);
		});
	}

	public function forgotPassword()
	{
		$email	 = Yii::app()->request->getParam('forgotemail');
		$users	 = Agents::model()->find("agt_email=:email AND agt_type IN(0,2)", ['email' => $email]);
		if (count($users) > 0)
		{
			$username	 = $users->agt_fname;
			$code		 = rand(999, 9999);
			$body		 = "<p>Please copy paste this code  <span style='color: #000000;font-weight:bold'>##CODE##</span> to reset password of your aaocab Agent Account.</p><br><br>";
			$mail		 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
			$body		 = str_replace("##CODE##", $code, $body);
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
			$phone		 = $users->agt_phone;
			if ($smsWrapper->sendForgotPassCodeAgent('91', $phone, $code))
			{
				$delivered1 = "Message sent successfully";
			}
			else
			{
				$delivered1 = "Message not sent";
			}
			$body						 = $mail->Body;
			$usertype					 = EmailLog::Agent;
			$subject					 = 'Reset your Password';
			$refId						 = $users->agt_id;
			$refType					 = EmailLog::REF_AGENT_ID;
			emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
			$users->agt_code_password	 = $code;
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

	public function verifyPhone()
	{
		$email	 = Yii::app()->request->getParam('email');
		$users	 = Agents::model()->find("agt_email=:email", ['email' => $email]);
		if (count($users) > 0)
		{
			$code					 = rand(999, 9999);
			$smsWrapper				 = new smsWrapper();
			$phone					 = $users->agt_phone;
			$countrycode			 = $users->agt_phone_country_code;
			$smsWrapper->sendVerificationAgent($countrycode, $phone, $code);
			$users->agt_verify_phone = $code;
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

	public function verifyEmail()
	{
		$email	 = Yii::app()->request->getParam('email');
		$users	 = Agents::model()->find("agt_email=:email AND agt_type IN(0,2)", ['email' => $email]);
		if (count($users) > 0)
		{
			$code					 = rand(999, 9999);
			$emailWrapper			 = new emailWrapper();
			$email					 = $users->agt_email;
			$emailWrapper->sendVerificationAgent($email, $code);
			$users->agt_verify_phone = $code;
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

	public function loginAgent1($data)
	{
		$model				 = new Agents('login');
		$model->attributes	 = $data;
		$email				 = $model->agt_username;
		$password			 = $model->agt_password;
		$identity			 = new AgentIdentity($email, $password);
		if ($identity->authenticate())
		{
			$userID							 = $identity->getId();
			Yii::app()->user->login($identity);
			$sessionId						 = Yii::app()->getSession()->getSessionId();
			$id								 = Yii::app()->user->getId();
			$appTokenModel					 = new AppTokens();
			$appTokenModel->attributes		 = $data;
			$appTokenModel->apt_user_id		 = $userID;
			$appTokenModel->apt_token_id	 = $sessionId;
			$appTokenModel->apt_ip_address	 = \Filter::getUserIP();
			$appTokenModel->apt_last_login	 = new CDbExpression('NOW()');
			$appTokenModel->apt_user_type	 = 4;
			if ($appTokenModel->insert())
			{
				$success = true;
			}
			$pmodel		 = Agents::model()->findByPk($userID);
			$tmodel		 = Terms::model()->getText(4);
			$tnc_check	 = false;
			$new_tnc_id	 = $tmodel->tnc_id;
			if ($pmodel->agt_tnc_id == $tmodel->tnc_id)
			{
				$tnc_check	 = true;
				$new_tnc_id	 = '';
			}
			$modelArr	 = JSONUtil::convertModelToArray(Agents::model()->findByPk($userID));
			unset($modelArr['agt_password']);
			unset($modelArr['agt_password1']);
			$loginData	 = ['sessionId' => $sessionId, 'model' => $modelArr];
		}
		else
		{
			$success	 = false;
			$loginData	 = ['errors' => ['error' => ["Failed to login"]]];
		}
		$result = ['success' => $success, 'tnc_check' => $tnc_check, 'new_tnc_id' => $new_tnc_id] + $loginData;

		return $result;
	}

	public function loginAgent($data)
	{
		$email		 = $data['agt_username'];
		$pass		 = $data['agt_password'];
		$usrModel	 = Users::model()->findByEmail($email);
		if ($usrModel != '')
		{
			$agtUsersModel = AgentUsers::model()->findAll('agu_user_id=:user', ['user' => $usrModel->user_id]);
			if ($agtUsersModel != '')
			{
				foreach ($agtUsersModel as $key => $agtusr)
				{
					$agtModel = Agents::model()->findByPk($agtusr->agu_agent_id, 'agt_type IN(0,2)');
					if ($agtModel != '')
					{
						$agent = true;
					}
				}
			}
		}

		if ($agent)
		{
			$identity	 = new UserIdentity($email, $pass);
			$valid		 = $identity->authenticate();
			if ($valid)
			{
				if (Yii::app()->user->loginAgentUser($identity))
				{
					$agentUsersModel = AgentUsers::model()->findAll('agu_user_id=:user', ['user' => Yii::app()->user->getId()]);
					foreach ($agentUsersModel as $agentusrModel)
					{
						$agtModel = Agents::model()->findByPk($agtusr->agu_agent_id, 'agt_type IN(0,2)');
						if ($agtModel != '')
						{
							Yii::app()->user->setAgentId($agtModel->agt_id);
						}
					}
					$sessionId						 = Yii::app()->getSession()->getSessionId();
					$id								 = Yii::app()->user->getId();
					$appTokenModel					 = new AppTokens();
					$appTokenModel->attributes		 = $data;
					$appTokenModel->apt_user_id		 = $id;
					$appTokenModel->apt_token_id	 = $sessionId;
					$appTokenModel->apt_ip_address	 = \Filter::getUserIP();
					$appTokenModel->apt_last_login	 = new CDbExpression('NOW()');
					$appTokenModel->apt_user_type	 = 4;
					if ($appTokenModel->insert())
					{
						$success = true;
					}

					$tmodel		 = Terms::model()->getText(4);
					$tnc_check	 = false;
					$new_tnc_id	 = $tmodel->tnc_id;
					if ($agtModel->agt_tnc_id == $tmodel->tnc_id)
					{
						$tnc_check	 = true;
						$new_tnc_id	 = '';
					}
					$modelArr	 = JSONUtil::convertModelToArray($agtModel);
					unset($modelArr['agt_password']);
					unset($modelArr['agt_password1']);
					$loginData	 = ['sessionId' => $sessionId, 'model' => $modelArr];
				}
			}
			else
			{
				$success	 = false;
				$loginData	 = ['errors' => ['error' => ["Failed to login"]]];
			}
		}
		else
		{
			$success	 = false;
			$loginData	 = ['errors' => ['error' => ["Agent is not registered."]]];
		}

		$result = ['success' => $success, 'tnc_check' => $tnc_check, 'new_tnc_id' => $new_tnc_id] + $loginData;
		return $result;
	}

	public function changePassword($data)
	{
		$userId					 = Yii::app()->user->getId();
		$model					 = Users::model()->findByPk($userId);
		$oldPassword			 = $data['old_password'];
		$newPassword			 = $data['new_password'];
		$rePassword				 = $data['repeat_password'];
		$model->old_password	 = $oldPassword;
		$model->new_password	 = $newPassword;
		$model->repeat_password	 = $rePassword;
		$success				 = false;
		//  Yii::log("changepassword  : ", CLogger::LEVEL_INFO, 'system.api.images');
		if ($model->validate())
		{
			$model->scenario = 'change';
			if ($model->usr_password == md5($model->old_password))
			{
				$model->usr_password = md5($model->new_password);
				if ($model->save())
				{
					Users::model()->logoutByUserId($userId);
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

	public function changePassword1($data)
	{
		$userId					 = Yii::app()->user->getId();
		$model					 = Agents::model()->findByPk($userId);
		$oldPassword			 = $data['old_password'];
		$newPassword			 = $data['new_password'];
		$rePassword				 = $data['repeat_password'];
		$model->old_password	 = $oldPassword;
		$model->new_password	 = $newPassword;
		$model->repeat_password	 = $rePassword;
		$success				 = false;
		if ($model->validate())
		{
			$model->scenario = 'changepassword';
			if ($model->agt_password == md5($model->old_password))
			{
				$model->agt_password = md5($model->new_password);
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

	public function register1($data)
	{
		$model					 = new Agents();
		$model->attributes		 = $data;
		$model->agt_password1	 = $data['agt_password1'];
		$model->agt_tnc_datetime = new CDbExpression('NOW()');
		$model->agt_tnc			 = 1;
		if ($model->agt_password1 != "")
		{
			$model->agt_password = md5($model->agt_password1);
		}
		$tmodel					 = Terms::model()->getText(4);
		$model->agt_tnc_id		 = $tmodel->tnc_id;
		$model->agt_type		 = 0;
		$success				 = false;
		$errors					 = [];
		$model->agt_create_date	 = new CDbExpression('NOW()');
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
					$emailsend	 = new emailWrapper();
					$emailsend->signupEmailAgent($model->agt_id);
					$success	 = true;
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

		$result = array('success'	 => $success,
			'errors'	 => $errors,
			'data'		 => $model
		);
		return $result;
	}

	public function register($data)
	{
		$success				 = false;
		$model					 = new Agents('signup');
		$model->attributes		 = $data;
		$model->agt_tnc_datetime = new CDbExpression('NOW()');
		$model->agt_tnc			 = 1;
		if ($data['agt_password1'] != "")
		{
			$model->agt_password = md5($data['agt_password1']);
		}
		$tmodel				 = Terms::model()->getText(4);
		$model->agt_tnc_id	 = $tmodel->tnc_id;
		$model->agt_type	 = 2;
		$model->agt_approved = 0;
		if ($model->agt_city != '')
		{
			$ctyCode = Cities::model()->findByPk($model->agt_city)->cty_code;
			if ($ctyCode != '')
			{
				$model->agt_referral_code = "AG-" . $ctyCode . "-" . time() . '-' . mt_rand(100, 999);
			}
			else
			{
				$model->agt_referral_code = "AG-" . "CITY" . "-" . time() . '-' . mt_rand(100, 999);
			}
		}
		$result = $model->validate();
		if ($model->resetScope()->exists('agt_email=:email OR agt_username=:username', ['email' => $model->agt_email, 'username' => $model->agt_username]))
		{
			$model->addError('agt_email', 'Email already exists.');
			$result = false;
		}
		if ($model->resetScope()->exists('agt_phone=:phone', ['phone' => $model->agt_phone]))
		{
			$model->addError('agt_phone', 'Phone already exists.');
			$result = false;
		}

		if ($result)
		{
			$userModel = Users::model()->find('usr_email=:email AND usr_mobile=:phone', ['email' => $model->agt_username, 'phone' => $model->agt_phone]);
			if (!$userModel)
			{
				$userModel = Users::model()->find('usr_email=:email', ['email' => $model->agt_username]);
			}

			if ($model->save())
			{
				if ($userModel == '')
				{
					$model->agt_agent_id			 = "AGT00" . $model->agt_id;
					$userModel						 = new Users();
					$userModel->usr_name			 = $model->agt_fname;
					$userModel->usr_lname			 = $model->agt_lname;
					$userModel->usr_email			 = $model->agt_email;
					$userModel->usr_mobile			 = $model->agt_phone;
					$userModel->usr_password		 = $model->agt_password;
					$userModel->usr_create_platform	 = 1;
					$userModel->usr_acct_type		 = 1;
					$userModel->scenario			 = 'agentjoin';
					$userModel->save();
					$agentUserModel					 = new AgentUsers();
					$agentUserModel->agu_agent_id	 = $model->agt_id;
					$agentUserModel->agu_user_id	 = $userModel->user_id;
					$agentUserModel->save();
					$success						 = $model->save();
					$emailsend						 = new emailWrapper();
					$emailsend->signupEmailAgent($model->agt_id);
				}
				else
				{
					$model->agt_agent_id = "AGT00" . $model->agt_id;
					$model->agt_password = $userModel->usr_password;
					$agentUserModel		 = AgentUsers::model()->find('agu_agent_id=:agent AND agu_user_id=:user AND agu_role=1', ['agent' => $model->agt_id, 'user' => $userModel->user_id]);
					if ($agentUserModel == '')
					{
						$agentUserModel = new AgentUsers();
					}
					$agentUserModel->agu_agent_id	 = $model->agt_id;
					$agentUserModel->agu_user_id	 = $userModel->user_id;
					$agentUserModel->save();
					$success						 = $model->save();
				}
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

	public function getValidationApp($data, $id, $activeVersion)
	{
		if ($activeVersion > $data['apt_apk_version'])
		{
			$active	 = 1;
			$success = false;
			$msg	 = "Invalid Version";
		}
		else
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
		}
		$result = array('active' => $active, 'success' => $success, 'message' => $msg);
		return $result;
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

	public function actionIndex()
	{
		Yii::app()->request->isAjaxRequest;
		$this->layout	 = "admin1";
		$model			 = new Agents();
		$this->render('index', ['model' => $model]);
	}

	public function actionAdditionalDetails()
	{
		$this->pageTitle = "Partner Profile";
		$tab			 = 1;
		$agentId		 = Yii::app()->user->getAgentId();
		$model			 = Agents::model()->findByPk($agentId);
		$AgentRel		 = AgentRel::model()->find('arl_agt_id=:id', ['id' => $agentId]);
		if ($AgentRel == '')
		{
			$AgentRel = new AgentRel();
		}
		if (isset($_REQUEST['Agents']))
		{

			$oldData = Agents::model()->getDetailsbyId($model->agt_id);
			if ($model->agt_pan_card != '')
			{
				$isPanCardUploaded = true;
			}
			$model->attributes		 = Yii::app()->request->getParam('Agents');
			$model->agt_otp_required = ($model->agt_otp_not_required == 0) ? 1 : 0;
			if ($model->agt_copybooking_phone != '')
			{
				$model->agt_copybooking_phone = str_replace(' ', '', $model->agt_copybooking_phone);
			}
			$uploadedFile	 = CUploadedFile::getInstance($model, "agt_owner_photo");
			$uploadedFile1	 = CUploadedFile::getInstance($model, "agt_aadhar");
			$uploadedFile2	 = CUploadedFile::getInstance($model, "agt_company_add_proof");
			$uploadedFile3	 = CUploadedFile::getInstance($model, "agt_pan_card");

			$arrAgentMessages = Yii::app()->request->getParam('AgentMessages');
			if ($model->agt_license_expiry_date != '')
			{
				$agtLicense						 = DateTimeFormat::DatePickerToDate($model->agt_license_expiry_date);
				$model->agt_license_expiry_date	 = $agtLicense . " 00:00:00";
			}
			if (isset($_REQUEST['tab1submit']))
			{
				$model->scenario	 = "join1";
				$tab				 = 1;
				$isPanCardUploaded	 = true;
			}
			if (isset($_REQUEST['tab2submit']))
			{
				$model->scenario	 = "join2";
				$tab				 = 2;
				$isPanCardUploaded	 = true;
			}
			if (isset($_REQUEST['tab3submit']))
			{
				$model->scenario	 = "join3";
				$tab				 = 3;
				$isPanCardUploaded	 = true;
			}
			if (isset($_REQUEST['tab4submit']))
			{
				$model->scenario	 = "join4";
				$tab				 = 4;
				$isPanCardUploaded	 = true;
			}
			if (isset($_REQUEST['tab5submit']))
			{
				$model->scenario = "join5";
				$tab			 = 5;
				if ($uploadedFile3 != '')
				{
					$isPanCardUploaded = true;
				}
			}
			if (isset($_REQUEST['tab6submit']))
			{
				$model->scenario	 = "join6";
				$tab				 = 6;
				$isPanCardUploaded	 = true;
			}

			if ($model->validate() && $isPanCardUploaded)
			{
				unset($model->agt_owner_photo);
				unset($model->agt_aadhar);
				unset($model->agt_company_add_proof);
				unset($model->agt_pan_card);
				Yii::app()->user->setCompanyName($model->agt_company);
				$model->save();

				//upload files
				$path = '';
				if ($uploadedFile != '')
				{
					$path					 = Agents::model()->uploadAgentDocument($uploadedFile, $model->agt_id, 'photo');
					$model->agt_owner_photo	 = $path;
				}
				if ($uploadedFile1 != '')
				{
					$path				 = Agents::model()->uploadAgentDocument($uploadedFile1, $model->agt_id, 'aadhar');
					$model->agt_aadhar	 = $path;
				}
				if ($uploadedFile2 != '')
				{
					$path							 = Agents::model()->uploadAgentDocument($uploadedFile2, $model->agt_id, 'address');
					$model->agt_company_add_proof	 = $path;
				}
				if ($uploadedFile3 != '')
				{
					$path				 = Agents::model()->uploadAgentDocument($uploadedFile3, $model->agt_id, 'pan');
					$model->agt_pan_card = $path;
				}
				//upload files
				if ($model->save())
				{
					$model->refresh();
				}

				if (isset($_POST['AgentRel']))
				{
					$AgentRel->attributes	 = Yii::app()->request->getParam('AgentRel');
					$AgentRel->arl_agt_id	 = $model->agt_id;
					$uploadedFile3			 = CUploadedFile::getInstance($AgentRel, "arl_voter_id_path");
					$uploadedFile4			 = CUploadedFile::getInstance($AgentRel, "arl_driver_license_path");
					if ($AgentRel->validate())
					{
						unset($AgentRel->arl_voter_id_path);
						unset($AgentRel->arl_driver_license_path);
						if ($uploadedFile3 != '')
						{
							$path						 = Agents::model()->uploadAgentDocument($uploadedFile3, $model->agt_id, 'voter_id');
							$AgentRel->arl_voter_id_path = $path;
						}
						if ($uploadedFile4 != '')
						{
							$path								 = Agents::model()->uploadAgentDocument($uploadedFile4, $model->agt_id, 'driver_license');
							$AgentRel->arl_driver_license_path	 = $path;
						}
						if ($AgentRel->save())
						{
							$AgentRel->refresh();
						}
					}
				}

				if ($arrAgentMessages != '')
				{
					$arr_agent_is_email	 = $arrAgentMessages['agt_agent_email'];
					$arr_agent_is_sms	 = $arrAgentMessages['agt_agent_sms'];
					$arr_agent_is_app	 = $arrAgentMessages['agt_agent_app'];

					$arr_trvl_is_email	 = $arrAgentMessages['agt_trvl_email'];
					$arr_trvl_is_sms	 = $arrAgentMessages['agt_trvl_sms'];
					$arr_trvl_is_app	 = $arrAgentMessages['agt_trvl_app'];

					$arrEvents = AgentMessages::getEvents();
					foreach ($arrEvents as $key => $value)
					{
						$AgentMessages = AgentMessages::model()->getByEventAndAgent($model->agt_id, $key);
						if ($AgentMessages == '')
						{
							$AgentMessages = new AgentMessages();
						}
						$AgentMessages->agt_agent_id = $model->agt_id;
						$AgentMessages->agt_event_id = $key;

						$AgentMessages->agt_agent_email	 = $arr_agent_is_email[$key];
						$AgentMessages->agt_agent_sms	 = $arr_agent_is_sms[$key];
						$AgentMessages->agt_agent_app	 = $arr_agent_is_app[$key];

						$AgentMessages->agt_trvl_email	 = $arr_trvl_is_email[$key];
						$AgentMessages->agt_trvl_sms	 = $arr_trvl_is_sms[$key];
						$AgentMessages->agt_trvl_app	 = $arr_trvl_is_app[$key];

						$AgentMessages->save();
					}
				}
				//set pending approval
				$voter = false;
				if ($AgentRel != '' && !$AgentRel->isNewRecord)
				{
					$voter = ($AgentRel->arl_voter_id_path != '') ? true : false;
				}
				if (($model->agt_is_owner_aadharcard != 0 || $model->agt_is_voter_id != 0 || $model->agt_aadhar != '' || $voter) && $model->agt_approved == 0)
				{
					$model->agt_approved = 2;
					if ($model->save())
					{
						$model->refresh();
					}
				}
				$newData			 = Agents::model()->getDetailsbyId($model->agt_id);
				$getOldDifference1	 = array_diff_assoc($oldData, $newData);
				$getNewDifference1	 = array_diff_assoc($newData, $oldData);
				$getOldDifference	 = [];
				$getNewDifference	 = [];
				$labelArr			 = Agents::model()->attributeLabels();
				foreach ($getOldDifference1 as $key => $value)
				{
					$getOldDifference[$labelArr[$key]] = $value;
				}
				foreach ($getNewDifference1 as $key => $value)
				{
					$getNewDifference[$labelArr[$key]] = $value;
				}
				if ($tab == 6)
				{
					$this->redirect(['index/dashboard']);
				}
				$tab = $tab + 1;
			}
			else
			{
				if (!$isPanCardUploaded)
				{
					$model->addError('agt_pan_card', 'Pan Card is mandatory');
				}
			}
		}

		$this->render('addt_details', ['model' => $model, 'AgentRel' => $AgentRel, 'tab' => $tab]);
	}

	public function actionChangePassword()
	{
		$userId			 = Yii::app()->user->getId();
		$model			 = Users::model()->findByPk($userId);
		$model->scenario = "change";
		/* @var $model Users */
		if (isset($_POST['Users']))
		{
			$model->new_password	 = $_POST['Users']['new_password'];
			$model->repeat_password	 = $_POST['Users']['repeat_password'];
			$model->old_password	 = $_POST['Users']['old_password'];
			if ($model->validate())
			{

				if (md5($model->old_password) == $model->usr_password)
				{
					$model->usr_password = md5($_POST['Users']['new_password']);
					if ($model->update())
					{
						echo json_encode(["success" => true]);
						Yii::app()->end();
					}
				}
				else
				{
					echo json_encode(['success' => false, "err_messages" => "Current Password does not match"]);
					Yii::app()->end();
				}
			}
		}
		$this->renderPartial('change_password', ['model' => $model, 'agent' => $userId], false, true);
	}

	public function actionEditprofile()
	{
		$this->pageTitle = "My Profile";
		$userId			 = Yii::app()->user->getId();
		$model			 = Users::model()->findByPk($userId);
		$this->subTitle	 = ucwords($model->usr_name . " " . $model->usr_lname);
		$modelAgent		 = Agents::model()->findByPk(Yii::app()->user->getAgentId());
		$totBookings	 = Users::model()->totBookingsWithStatus($userId);
		if (isset($_POST['Users']))
		{

			if (isset($_REQUEST['form_tab_1']))
			{
				$model->attributes = Yii::app()->request->getParam('Users');
				unset($model->usr_profile_pic_path);
			}
			if (isset($_REQUEST['form_tab_2']))
			{
				$model->usr_profile_pic_path = $_REQUEST['Users']['usr_profile_pic_path'];
				$uploadedFile				 = CUploadedFile::getInstance($model, "usr_profile_pic_path");
				if ($uploadedFile != '')
				{
					$path						 = Agents::model()->uploadAgentUserDocument($uploadedFile, $model->user_id, 'profile');
					$model->usr_profile_pic_path = $path;
				}
			}
			if ($model->validate())
			{
				if ($model->save())
				{
					Yii::app()->user->setFlash('success', 'Profile Details Updated Successfully !');
					$this->redirect('editprofile');
				}
			}
		}

		if (isset($_POST['Agents']) && isset($_REQUEST['markup_submit']))
		{
			$modelAgent->agt_commission			 = $_POST['Agents']['agt_commission'];
			$modelAgent->agt_commission_value	 = $_POST['Agents']['agt_commission_value'];
			if ($modelAgent->validate())
			{
				$modelAgent->save();
				Yii::app()->user->setFlash('success', 'Settings Saved Successfully !');
				$this->redirect('editprofile');
			}
		}
		$this->render('edit_profile', ['model' => $model, 'totBookings' => $totBookings, 'modelAgent' => $modelAgent]);
	}

	public function actionSignin()
	{
		if (!Yii::app()->user->isGuest)
		{
			$this->redirect(['index/dashboard']);
		}
		$this->layout	 = "login";
		$email			 = Yii::app()->request->getParam('txtUsername', '');
		$pass			 = Yii::app()->request->getParam('txtPassword', '');
		$message		 = Yii::app()->request->getParam('message');
		$agtType		 = Yii::app()->request->getParam('agtType', '');
        $url             = Yii::app()->request->requestUri;
		$telegramId  	 = substr($url, strrpos($url, '=' )+1)."\n";
        $isTelegramId    = str_contains($url, 'telegramId');
		$status			 = Yii::app()->request->getParam('status', '');
		if ($agtType != '' && $email != '' && $pass != '')
		{
            
			$identity	 = new UserIdentity($email, md5($pass));
			$valid		 = $identity->authenticate();
			if ($valid)
			{
				if (Yii::app()->user->loginAgentUser($identity))
				{
					$agentUsersModelArr = AgentUsers::model()->findAll('agu_user_id=:user', ['user' => Yii::app()->user->getId()]);
					if (count($agentUsersModelArr) > 0)
					{
//                        if ($agtType == 1) {
//                            $agentModel = Agents::model()->findByPk($agentUsersModel->agu_agent_id, 'agt_type=1');
//                        } else if($agtType== 0){
//                            $agentModel = Agents::model()->findByPk($agentUsersModel->agu_agent_id, 'agt_type=0');
//                        }else{
//                            $agentModel = Agents::model()->findByPk($agentUsersModel->agu_agent_id, 'agt_type=2');
//                        }
						$agentModel = Agents::model()->findByPk($agtType);

						if ($agentModel != '')
						{
							Yii::app()->user->setAgentId($agentModel->agt_id);
							$company = ($agentModel->agt_company == '') ? '' : $agentModel->agt_company;
							Yii::app()->user->setCompanyName($company);
							if ($agentModel->agt_type == 1)
							{
								Yii::app()->user->setCorpCode($agentModel->agt_referral_code);
							}
							$this->createLog($identity);
							if ($agentModel->agt_company == '')
							{
								$url = Yii::app()->createUrl('agent/users/additionaldetails');
								if ($agentModel->agt_approved != 1)
								{
									$url = Yii::app()->createUrl('agent/users/additionaldetails', ['login' => 1]);
								}
								echo json_encode(['success' => true, 'url' => $url]);
								Yii::app()->end();
							}
							$url1 = Yii::app()->createUrl('agent/index/dashboard');
							if ($agentModel->agt_approved != 1)
							{
								$url1 = Yii::app()->createUrl('agent/index/dashboard', ['login' => 1]);
							}
							echo json_encode(['success' => true, 'url' => $url1]);
							Yii::app()->end();
						}
					}
				}
				else
				{
					echo json_encode(['success' => true, 'message' => 'Invalid Username/Password']);
					Yii::app()->end();
				}
			}
			else
			{
				echo json_encode(['success' => false, 'message' => 'Agent/Corporate is not registered']);
				Yii::app()->end();
			}
		}

		if ($email != '' && $pass != '' && $agtType == '')
		{
			$usrModel = Users::model()->find('usr_email=:email AND usr_password=:paswd', ['paswd' => md5($pass), 'email' => $email]);
			if ($usrModel != '')
			{
				$agtUsersModel	 = AgentUsers::model()->findAll('agu_user_id=:user', ['user' => $usrModel->user_id]);
				$arrAgentTypes	 = [];
				if ($agtUsersModel != '' && count($agtUsersModel) > 0)
                {
                    foreach ($agtUsersModel as $value)
                    {
                        $agtModel = Agents::model()->findByPk($value->agu_agent_id, 'agt_active=1');
                        if ($agtModel != '')
                        {
                            if ($isTelegramId)
                            {
                                $typeAction = AgentApiTracking::TYPE_TELEGRAM_AUTHENTICATION;
                                AgentMessages::model()->pushApiCall($agtModel, $typeAction, $telegramId);
                            }
                            $agent = true;
                            array_push($arrAgentTypes, ['id' => $agtModel->agt_id, 'type' => $agtModel->agt_type, 'company' => $agtModel->agt_company, 'name' => $agtModel->agt_fname . " " . $agtModel->agt_lname, 'agentid' => $agtModel->agt_agent_id, 'corpcode' => $agtModel->agt_referral_code]);
                        }
                    }
                }
            }
			else
			{
				$status = 'error';
			}

			if ($agent)
			{
				$agentUsers = AgentUsers::model()->findAll('agu_user_id=:user', ['user' => $usrModel->user_id]);
				if (count($agentUsers) == 1)
				{
					$this->loginWeb($email, $pass);
				}
				else if (count($agentUsers) > 1)
				{
					$status = 'errorAgentOrCorp';
				}
			}
			else
			{
				$status = 'error';
			}
		}
		$this->render('signin', array('status' => $status, 'message' => $message, 'usr' => $email, 'psw' => $pass, 'arrAgentTypes' => $arrAgentTypes));
	}

	public function createLog($identity)
	{
		$ip								 = AgentLog::getIP();
		$sessionid						 = Yii::app()->getSession()->getSessionId();
		$agtlogModel					 = new AgentLog();
		$agtlogModel->agl_usr_ref_id	 = $identity->getId();
		$agtlogModel->agl_usr_type		 = AgentLog::Agent;
		$agtlogModel->agl_agent_id		 = Yii::app()->user->getAgentId();
		$agtlogModel->agl_desc			 = "logged in/out successfully";
		$agtlogModel->agl_event_id		 = AgentLog::AGENT_LOGGEDIN;
		$agtlogModel->agl_ip			 = $ip;
		$agtlogModel->agl_session		 = $sessionid;
		$agtlogModel->agl_device_info	 = $_SERVER['HTTP_USER_AGENT'];
		$agtlogModel->save();
		return true;
	}

	public function loginWeb($email, $pass)
	{
		$identity	 = new UserIdentity($email, md5($pass));
		$valid		 = $identity->authenticate();
		if ($valid)
		{
			if (Yii::app()->user->loginAgentUser($identity))
			{
				$agentUsersModel = AgentUsers::model()->find('agu_user_id=:user', ['user' => Yii::app()->user->getId()]);
				if ($agentUsersModel != '' && $agentUsersModel->agu_agent_id)
				{
					$agtModel = Agents::model()->findByPk($agentUsersModel->agu_agent_id);
					Yii::app()->user->setAgentId($agtModel->agt_id);
					Yii::app()->user->setCompanyName($agtModel->agt_company);
					if ($agtModel->agt_type == 1)
					{
						Yii::app()->user->setCorpCode($agtModel->agt_referral_code);
					}
					$this->createLog($identity);
					if (Yii::app()->user->returnUrl != null && Yii::app()->user->returnUrl != '/')
					{
						$this->redirect(Yii::app()->user->returnUrl);
						return;
					}
					if ($agtModel->agt_company == '')
					{
						$this->redirect(['users/additionaldetails']);
					}
					$this->redirect(array("index/dashboard"));
				}
			}
			else
			{
				$status = 'error';
			}
		}
		else
		{
			$status = 'error';
		}
	}

	public function actionBookingmsgdefaults()
	{
		$agentId	 = Yii::app()->user->getAgentId();
		$notifydata	 = Yii::app()->request->getParam('notifydata', '');

		if (isset($_POST['AgentMessages']))
		{
			$arrAgentMessages	 = Yii::app()->request->getParam('AgentMessages');
			$data				 = json_encode(['success' => true, 'data' => $arrAgentMessages]);
			echo $data;
			Yii::app()->end();
		}
		$this->renderPartial('messagedefaults', ['agentId' => $agentId, 'notifydata' => $notifydata], false, true);
	}

	public function actionCreditlimit()
	{
		$corpCredit			 = Yii::app()->request->getParam('corpcredit');
		$bkgId				 = Yii::app()->request->getParam('bkg_id');
		$agentId			 = Yii::app()->user->getAgentId();
		$availableBalance	 = Agents::getAvailableLimit($agentId);
		$getBalance			 = PartnerStats::getBalance($agentId);
		$walletBalance		 = $getBalance['pts_wallet_balance'];
		$isRechargeAccount	 = 0;

		if ($walletBalance >= $corpCredit)
		{
			goto skipIssue;
		}

		$bkgModel				 = BookingTemp::model()->findByPk($bkgId);
		$pickupTimeDiffMinutes	 = Filter::getTimeDiff($bkgModel->bkg_pickup_date, Filter::getDBDateTime());
		if ($pickupTimeDiffMinutes > 720)
		{
			goto skipIssue;
		}
		$credits = $corpCredit - $walletBalance;
		if ($credits > $availableBalance)
		{
			$isRechargeAccount = 1;
		}
		$walletBalance += $credits;
		//$isRechargeAccount	 = AccountTransDetails::model()->checkCreditLimit(Yii::app()->user->getAgentId(), $routes, $bookingType, $corpCredit, $requestData, 3, false);
//		if ($isRechargeAccount)
//		{
//			$isRechargeAccount = 0;
//		}
//		else
//		{
//			$isRechargeAccount = 0;
//		}
		skipIssue:
		echo json_encode(['isRechargeAccount' => $isRechargeAccount]);
		Yii::app()->end();
	}

	public function actionRecharge()
	{
		$url			 = Yii::app()->createUrl('agent/recharge/add', []);
		$this->redirect($url);
		$this->pageTitle = "Recharge Account";
		$agentId		 = Yii::app()->user->getAgentId();
		$transcode		 = Yii::app()->request->getParam('tinfo', '');
		if (isset($_POST['recharge_amount']) && $_POST['recharge_amount'] > 0 && isset($_POST['paymentOpt']))
		{
			if ($_POST['recharge_amount'] >= 500 && $_POST['paymentOpt'] == 1)
			{
				$paymentGateway						 = new PaymentGateway();
				$paymentGateway->apg_acc_trans_type	 = Accounting::AT_PARTNER;
				$paymentGateway->apg_trans_ref_id	 = $agentId;
				$paymentGateway->apg_ptp_id			 = PaymentType::TYPE_PAYTM;
				$paymentGateway->apg_amount			 = $_POST['recharge_amount'];
				$paymentGateway->apg_remarks		 = "Payment Initiated";
				$paymentGateway->apg_ref_id			 = '';
				$paymentGateway->apg_user_type		 = UserInfo::TYPE_AGENT;
				$paymentGateway->apg_user_id		 = $agentId;
				$paymentGateway->apg_status			 = 0;
				$paymentGateway->apg_date			 = new CDbExpression("now()");
				$bankLedgerId						 = PaymentType::model()->ledgerList(PaymentType::TYPE_PAYTM);
				$transModel							 = $paymentGateway->payment($bankLedgerId);
				if ($transModel->apg_id)
				{
					$params['blg_ref_id']	 = $transModel->apg_id;
					$url					 = Yii::app()->createUrl('paytm/partnerpaymentinitiate', ['acctransid' => $transModel->apg_id]);
					$this->redirect($url);
				}
			}
			if ($_POST['recharge_amount'] >= 500 && $_POST['paymentOpt'] == 2)
			{
				$paymentGateway						 = new PaymentGateway();
				$paymentGateway->apg_acc_trans_type	 = Accounting::AT_PARTNER;
				$paymentGateway->apg_trans_ref_id	 = $agentId;
				$paymentGateway->apg_ptp_id			 = PaymentType::TYPE_PAYUMONEY;
				$paymentGateway->apg_amount			 = $_POST['recharge_amount'];
				$paymentGateway->apg_remarks		 = "Payment Initiated";
				$paymentGateway->apg_ref_id			 = '';
				$paymentGateway->apg_user_type		 = UserInfo::TYPE_AGENT;
				$paymentGateway->apg_user_id		 = $agentId;
				$paymentGateway->apg_status			 = 0;
				$paymentGateway->apg_date			 = new CDbExpression("now()");
				$bankLedgerId						 = PaymentType::model()->ledgerList(PaymentType::TYPE_PAYUMONEY);
				$transModel							 = $paymentGateway->payment($bankLedgerId);
				if ($transModel->apg_id)
				{
					$params['blg_ref_id']	 = $transModel->apg_id;
					$url					 = Yii::app()->createUrl('payu/partnerpaymentinitiate', ['acctransid' => $transModel->apg_id]);
					$this->redirect($url);
				}
			}
		}
		if ($transcode != '')
		{
			$agentTransModel = PaymentGateway::model()->getByCode($transcode);
			if ($agentTransModel->apg_status == 1)
			{
				$transinfo = "<span style='color: #32CD32'>Recharge Successful! <br>(Transaction ID: " . $agentTransModel->apg_code . ", Amount: " . abs($agentTransModel->apg_amount) . ")</span>";
			}
			else
			{
				$transinfo = "<span class='text-danger'>Recharge Failed! <br>(Transaction ID: " . $agentTransModel->apg_code . ", Amount: " . abs($agentTransModel->apg_amount) . ")</span>";
			}
		}

		$this->render("recharge", ['transinfo' => $transinfo]);
	}

	public function actionCPagreement()
	{
		$agtid			 = Yii::app()->request->getParam('agtid');
		$agtModel		 = Agents::model()->findByPk($agtid);
		$arrUserData	 = array(
			'first_name' => $agtModel->agt_fname,
			'last_name'	 => $agtModel->agt_lname,
			'email'		 => $agtModel->agt_email,
			'phone'		 => $agtModel->agt_phone,
		);
		$address		 = Config::getGozoAddress();
		$this->pageTitle = "Channel Partner Agreement";
		$this->renderPartial('agentagreement', ['data' => $arrUserData, 'address' => $address]);
	}

}
