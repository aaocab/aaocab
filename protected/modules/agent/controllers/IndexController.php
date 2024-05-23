<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class IndexController extends BaseController
{

	public $layout = 'admin1';
	public $email_receipient, $pageTitle1, $pageDesc;

	public function filters()
	{
		return array(
//            array(
//                'application.filters.HttpsFilter + create',
//                'bypass' => false),
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

	public function accessRules()
	{
		return array(
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('dashboard'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index', 'forgotpassword', 'newpassword', 'logout','selectAddress',
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
			$ri	 = array('/updatecheck');

			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.get.updatecheck.render', function()
		{
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$data				 = array_filter($data1);
			
			$activeVersion		 = Config::get("Version.Android.agent");//Yii::app()->params['versionCheck']['agent'];
			$id					 = Yii::app()->user->id;
			$result				 = $this->getValidationApp($data, $id, $activeVersion);
			$model				 = Agents::model()->findByPk(Yii::app()->user->id);
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'	 => $result['success'],
							'message'	 => $result['message'],
							'active'	 => $result['active'],
							'version'	 => $activeVersion,
							'data'		 => $model,
						)
			]);
		});
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

	public function actionIndex($status = null)
	{
		$this->layout = "login";
		if (!Yii::app()->user->isGuest)
		{
			$this->redirect(array("dashboard"));
		}
		if (Yii::app()->user->isGuest)
		{
			$this->redirect(['users/signin']);
		}
	}

	public function actionLogout()
	{
		$sessionid	 = Yii::app()->getSession()->getSessionId();
		$agtlogModel = AgentLog::model()->getLogBySession($sessionid);
		if ($agtlogModel)
		{
			$agtlogModel->agl_logout_time = new CDbExpression('Now()');
			$agtlogModel->update();
		}
		Yii::app()->user->logout();
		Yii::app()->getSession()->destroy();
		Yii::app()->user->clearStates();
		$this->redirect(array('index'));
	}

	public function actionForgotpassword()
	{
		$this->layout	 = "login";
		$forgot_email	 = trim(Yii::app()->request->getParam('forgotemail'));
		$code			 = trim(Yii::app()->request->getParam('code'));
		$email			 = Yii::app()->request->getParam('email');
		$matchcode		 = Yii::app()->request->getParam('matchcode');
		$status			 = false;
		if ($code != "" && $email != "")
		{
			$userModel = Users::model()->find('usr_email=:mail AND usr_verification_code=:code', ['mail' => $email, 'code' => $code]);

			if ($userModel != "")
			{
				$userModel->usr_verification_code	 = "";
				$userModel->update();
				$status								 = true;
				$agt_id								 = $userModel->user_id;
				$message							 = "Code matched successfully.";
				$this->render('newpassword', array('message' => $message, 'success' => $status, 'agt_id' => $agt_id));
			}
			else
			{
				$message = "Code not verified.";
				$this->render('forgotpasscode', array('message' => $message));
			}

			//$this->render('newpassword', array('message' => $message,'success'=>$status, 'agt_id' => $agt_id));
		}
		else
		{
			if ($forgot_email != "")
			{
				$status = $this->forgotPassword();
				if ($status)
				{
					$message = "Code successfully sent to the registered Email ID.";
				}
				else
				{
					$message = "Email not verified.";
				}
			}
			//if ($matchcode == 1) {
			// $message = "";
			$this->render('forgotpasscode', array('message' => $message));
			// }
		}
		//  $this->render('forgotpasscode', array('message' => $message));
	}

	public function actionNewpassword()
	{
		$this->layout	 = "login";
		$userId			 = Yii::app()->request->getParam('agtid');
		$newPassword	 = Yii::app()->request->getParam('newPassword');
		$repeatPassword	 = Yii::app()->request->getParam('repeatPassword');
		$success		 = false;
		if ($newPassword != '')
		{
			if ($newPassword === $repeatPassword)
			{
				$model = Users::model()->findByPk($userId);
				if ($model != '')
				{
					$model->usr_password = md5($newPassword);
					if ($model->update())
					{
						$success = true;
						$message = "Password changed successfully.";
					}
					else
					{
						$success = false;
						$message = "Error occured while changing password.";
					}
				}
				else
				{
					$success = false;
					$message = "Error occured while changing password.";
				}
			}
			else
			{
				$success = false;
				$message = "Passwords not maching.";
				$this->render('newpassword', array('message' => $message, 'success' => $status, 'agt_id' => $userId));
				Yii::app()->end();
			}
			$this->redirect(array('index', 'message' => $message));
		}
		// $this->render('newpassword', array('message' => $message, 'success' => $status, 'agt_id' => $userId));
	}

	public function actionDashboard()
	{
		$this->pageTitle = 'Upcoming Bookings - Dashboard';
		$this->layout	 = "main";
		$models			 = BookingSub::model()->getUpcomingBookingsByAgent(Yii::app()->user->getAgentId());
		$this->render('dashboard', array('models' => $models));
	}

	public function forgotPassword()
	{
		$email	 = Yii::app()->request->getParam('forgotemail');
		$users	 = Users::model()->find("usr_email=:email", ['email' => $email]);
		if (count($users) > 0)
		{
			$username	 = $users->usr_name;
			$code		 = rand(999, 9999);
			$body		 = "<p>Please copy paste this code  <span style='color: #000000;font-weight:bold'>##CODE##</span> to reset password of your Gozocabs Agent Account.</p><br><br>";
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
			$phone		 = $users->usr_mobile;
			if ($smsWrapper->sendForgotPassCodeAgent('91', $phone, $code))
			{
				$delivered1 = "Message sent successfully";
			}
			else
			{
				$delivered1 = "Message not sent";
			}
			$body							 = $mail->Body;
			$usertype						 = EmailLog::Agent;
			$subject						 = 'Reset your Password';
			$refId							 = $users->user_id;
			$refType						 = EmailLog::REF_AGENT_ID;
			emailWrapper::createLog($email, $subject, $body, "", $usertype, $delivered, '', $refType, $refId, EmailLog::SEND_SERVICE_EMAIL);
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
	

}
