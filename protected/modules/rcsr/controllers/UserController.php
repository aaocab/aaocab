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
			['allow', 'actions' => ['delete'], 'roles' => ['userDelete']],
			['allow', 'actions' => ['list', 'details', 'linkedusers'], 'roles' => ['userList']],
			['allow', 'actions' => ['addcredits'], 'roles' => ['creditAddBooking']],
			['allow', 'actions' => ['AddCreditsUser'], 'roles' => ['creditAddCustomer']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('loginasuser', 'editinfo', 'ajaxemailcheck', 'sendvmail', 'markedbadlist', 'markedbadmessage', 'resetmarkedbad', 'sendnotification'),
				'users'		 => array('@'),
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
			$ri	 = array('/signin', '/signout');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.get.signin.render', function() {
			$result = $this->login();
			if ($result['success'] == true)
			{
				$success	 = true;
				$userId		 = Yii::app()->user->getId();
				$sessionId	 = $result['sessionId'];
				$userModel	 = Admins::model()->resetScope()->find('adm_id=:id AND adm_active IN(1)', ['id' => $userId]);
				$userName	 = $userModel->adm_fname;
				$userEmail	 = $userModel->adm_email;
				$msg		 = "Login Successful";
			}
			else
			{
				$success = false;
				$msg	 = "Invalid Username/Password";
			}
			$userId		 = Yii::app()->user->getId();
			$sessionId	 = Yii::app()->getSession()->getSessionId();
			return CJSON::encode(['success'	 => $success,
						'message'	 => $msg,
						'sessionId'	 => $sessionId,
						'userId'	 => $userId,
						'userEmail'	 => $userEmail,
						'userName'	 => $userName]);
		});



		$this->onRest('req.get.validate.render', function() {
			$process_sync_data	 = Yii::app()->request->getParam('data');
			$data1				 = CJSON::decode($process_sync_data, true);
			$data				 = array_filter($data1);
			//$id = Yii::app()->user->id;
			$tokenData			 = AppTokens::model()->getByTokenId($data['apt_token_id'], 2);
			$id					 = $tokenData['apt_user_id'];
			$result				 = $this->getValidationApp($data, $id);
			/* @var $model Admins */
			$model				 = Admins::model()->findByPk($id);
			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'	 => $result['success'],
							'message'	 => $result['message'],
							'active'	 => $result['active'],
							'data'		 => $model,
						)
			]);
		});


		$this->onRest('req.get.signout.render', function() {
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
	}

//for fetching user list
	public function actionList($status = null)
	{
		$this->pageTitle = 'Customers List';
		$pageSize		 = Yii::app()->params['listPerPage'];
		$uid			 = Yii::app()->user->getId();
		$amodel			 = Admins::model()->findById($uid);
		$locals			 = $amodel->adm_chk_local;
		$qry			 = [];
		/* @var $model Users */
		$model			 = new Users();
		if ($_REQUEST['Users'])
		{
			$arr				 = Yii::app()->request->getParam('Users');
			$model->attributes	 = $arr;
			if (trim(Yii::app()->request->getParam('searchmarkuser')))
			{
				$model->search_marked_bad = 1;
			}
			$model->search_email = $arr['search_email'];
			$model->search_name	 = $arr['search_name'];
			$model->search_phone = $arr['search_phone'];
		}

		/*
		  if (ISSET($_REQUEST['chkLocal1']))
		  {

		  $locals = (Yii::app()->request->getParam('chkLocal') == '') ? 0 : 1;
		  $locals1 = Yii::app()->request->getParam('chkLocal1');
		  $amodel->adm_chk_local = $locals;
		  $amodel->save();
		  // $render='renderPartial';
		  }

		  $qry = [];
		  $qry['locals'] = $locals;
		  $qry['searchname'] = trim(Yii::app()->request->getParam('searchname'));
		  $qry['searchemail'] = trim(Yii::app()->request->getParam('searchemail'));
		  $qry['searchphone'] = trim(Yii::app()->request->getParam('searchphone'));
		  if (trim(Yii::app()->request->getParam('searchmarkuser')))
		  {
		  $qry['searchmarkuser'] = 1;
		  }
		 */

		$dataProvider							 = $model->search1();
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->render('users', array('dataProvider' => $dataProvider, 'status' => $status, 'model' => $model, 'qry' => $qry));

//		$dataProvider = Users::model()->search1($qry);
//		$dataProvider->getPagination()->params = array_filter($_GET + $_POST);
//		$dataProvider->getSort()->params = array_filter($_GET + $_POST);
//		$this->render('users', array('dataProvider' => $dataProvider, 'status' => $status, 'qry' => $qry));
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
			$user					 = Yii::app()->user->getId();
			$new_remark				 = $arr['usr_reset_desc'];
			$succes					 = false;
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
			$userId			 = $bookingModel->bkg_user_id;
			$bkgAmt			 = $bookingModel->bkg_total_amount;
		}
		else
		{
			$userId = Yii::app()->request->getParam('user_id');
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
		$dataProvider	 = $creditModel->resetScope()->search();
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
		$this->$render('addcredit', ['model' => $userCreditsModel, 'bkgAmt' => $bkgAmt, 'bookingId' => $bookingId, 'dataProvider' => $dataProvider], false, true);
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
		$model			 = Users::model()->findByPk($user);
		$creditModel	 = new UserCredits();
		// Active Credits
		$data			 = $creditModel->getCreditsList('1', $user);
		// Pending Credits
		$data2			 = $creditModel->getCreditsList('2', $user);
		$totalBookings	 = $model->totBookingsWithStatus($user);
		$this->renderPartial('details', array('model'			 => $model,
			'dataProvider'	 => $data['dataProvider'],
			'dataProvider2'	 => $data2['dataProvider'],
			'totalAmount'	 => $data['totalAmount'],
			'totalBookings'	 => $totalBookings
				)
				, false, true);
	}

	public function actionLinkedUsers()
	{
		$phone		 = Yii::app()->request->getParam('phone');
		$email		 = Yii::app()->request->getParam('email');
		$code        = Yii::app()->request->getParam('code','91');
		$usersArr	 = [];
		$success	 = false;
		if($phone != '' || $email != '')
		{
			$usrModel = new Users();
			if($phone != '')
			{
				$usrModel->scenario = 'linkusers';
				$usrModel->usr_mobile = $phone;
				$usrModel->usr_country_code = $code;
			}
			$usrModel->usr_email = $email;
			$usrModel->usr_create_platform = 4;
			$error = CActiveForm::validate($usrModel, null, false);
			if($error == '[]')
			{
				$userModels	 = $usrModel->linkUser();
				foreach ($userModels as $key => $value)
				{
					$usersArr[$key]['id']	 = $value['user_id'];
					$usersArr[$key]['email'] = $value['usr_email'];
					$usersArr[$key]['phone'] = $value['usr_mobile'];
					$usersArr[$key]['fname'] = $value['usr_name'];
					$usersArr[$key]['lname'] = $value['usr_lname'];
				}
				$count = count($usersArr);
				if ($count > 0)
				{
					$success = true;
				}
			}
		}
		else
		{
			$error = '{"Users_usr_mobile":["Invalid Data"]}';
		}

		echo json_encode(['success' => $success, 'users' => $usersArr, 'userCount' => $count, 'error' => $error]);
		exit;
	}

	public function login()
	{
		$process_sync_data	 = Yii::app()->request->getParam('data');
		$data				 = CJSON::decode($process_sync_data, true);
		$email				 = $data['adm_username'];
		$password			 = $data['adm_password'];
		$deviceInfo			 = $data['adm_device_info'];
		$deviceID			 = $data['adm_deviceid'];
		$deviceVersion		 = $data['adm_version'];
		$apkVersion			 = $data['adm_apk_version'];
		$identity			 = new AdminIdentity($email, $password);
		if ($identity->authenticate())
		{
			$userID		 = $identity->getId();
			$userModel	 = Admins::model()->resetScope()->find('adm_id=:id AND adm_active IN (1)', ['id' => $userID]);
			Yii::app()->user->login($identity);
			$sessionId	 = Yii::app()->getSession()->getSessionId();
			$appToken	 = AppTokens::model()->findAll('apt_device_uuid=:device', array('device' => $deviceID));
			foreach ($appToken as $app)
			{
				if (count($app) > 0)
				{
					$app->apt_status = 0;
					$app->update();
				}
			}
			$appTokenModel					 = new AppTokens();
			$appTokenModel->apt_user_id		 = $userID;
			$appTokenModel->apt_token_id	 = $sessionId;
			$appTokenModel->apt_device		 = $deviceInfo;
			$appTokenModel->apt_last_login	 = new CDbExpression('NOW()');
			$appTokenModel->apt_device_uuid	 = $deviceID;
			$appTokenModel->apt_user_type	 = 6;
			$appTokenModel->apt_apk_version	 = $apkVersion;
			$appTokenModel->apt_ip_address	 = $_SERVER['REMOTE_ADDR'];
			$appTokenModel->apt_os_version	 = $deviceVersion;
			$appTokenModel->save();
			$result							 = ['success' => true, 'sessionId' => $appTokenModel->apt_token_id, 'errors' => $appTokenModel->getErrors()];
		}
		else
		{
			$result = ['success' => false];
		}
		return $result;
	}

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
}
