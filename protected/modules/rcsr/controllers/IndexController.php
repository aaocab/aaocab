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
		'actions'	 => array('changepassword','dashboard'),
		'users'		 => array('@'),
	    ),
	    array('allow', // allow authenticated user to perform 'create' and 'update' actions
		'actions'	 => array('index', 'forgotpassword', 'newpassword', 'logout',
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


    

    public function actionIndex($status = null)
    {
     // Yii::app()->user->checkAccess('16 - LeadCaller').'puja';
	  if (!Yii::app()->user->isGuest)
        {
            $this->redirect(array('dashboard'));
        }
        $this->layout = "adminLoginLayout";

        if (isset($_REQUEST) && $_REQUEST != null)
        {
            $email    = Yii::app()->request->getParam('txtUsername');
            $pass     = Yii::app()->request->getParam('txtPassword', '');
            $isRemote = 0;
         
            $identity = new CsrIdentity($email, $pass);
            $valid    = $identity->authenticate();
            if ($valid)
            {
                if (Yii::app()->user->login($identity, 1200))
                {
                   Yii::app()->session['isRemote'] = 1;
                    $this->createLog($identity);
                    if (Yii::app()->user->returnUrl != null && Yii::app()->user->returnUrl != '/')
                    {
                        $this->redirect(Yii::app()->user->returnUrl);
                        return;
                    }
                    $this->redirect(array("lead/report"));
                }
            }
            else
            {
                $status = 'error';
            }
        }


        $this->render('index', array(
            'status' => $status
        ));
    }
public function actionChangepassword()
	{

		$this->layout	 = 'admin1';
		$this->pageTitle = 'Change Password';
		$adminId		 = Yii::app()->user->getId();
		$model			 = Admins::model()->findByPk($adminId);
		if (isset($_REQUEST['oldpassword']) && isset($_REQUEST['newpassword']) && isset($_REQUEST['confirmpassword']))
		{
			$oldPassword			 = Yii::app()->request->getParam('oldpassword');
			$newPassword			 = Yii::app()->request->getParam('newpassword');
			$rePassword				 = Yii::app()->request->getParam('confirmpassword');
			$model->old_password	 = $oldPassword;
			$model->new_password	 = $newPassword;
			$model->repeat_password	 = $rePassword;
			if (!CPasswordHelper::verifyPassword($model->old_password, $model->adm_passwd))
			{
				$status	 = "error";
				$message = "Old password doesnot match";
			}
			elseif ($model->new_password != $model->repeat_password)
			{
				$status	 = "error";
				$message = "New password and Confirm Password doesnot match";
			}
			else
			{
				$model->adm_passwd				 = CPasswordHelper::hashPassword($model->new_password);
				$model->adm_last_password_change = new CDbExpression('Now()');
				if ($model->update('change'))
				{
					$status	 = "true";
					$message = "Password Changed Successfully";
				}
				else
				{
					$status	 = "error";
					$message = "Password not changed";
				}
			}
		}
		if ($status == 'true')
		{
			$this->actionLogout();
		}
		$this->render('changepassword', ['status' => $status, 'message' => $message]);
	}
    public function actionLogout()
    {
	$sessionid	 = Yii::app()->getSession()->getSessionId();
	$agtlogModel	 = AgentLog::model()->getLogBySession($sessionid);
	if ($agtlogModel)
	{
	    $agtlogModel->agl_logout_time = new CDbExpression('Now()');
	    $agtlogModel->update();
	}
	Yii::app()->user->logout();
	$this->redirect(array('index'));
    }

    public function actionForgotpassword()
    {
	$this->layout	 = "login";
	$forgot_email	 = trim(Yii::app()->request->getParam('forgotemail'));
	$code		 = trim(Yii::app()->request->getParam('code'));
	$email		 = Yii::app()->request->getParam('email');
	$matchcode	 = Yii::app()->request->getParam('matchcode');
	$status		 = false;
	if ($code != "" && $email != "")
	{
	    $userModel = Users::model()->find('usr_email=:mail AND usr_verification_code=:code', ['mail' => $email, 'code' => $code]);

	    if ($userModel != "")
	    {
		$userModel->usr_verification_code	 = "";
		$userModel->update();
		$status					 = true;
		$agt_id					 = $userModel->user_id;
		$message				 = "Code matched successfully.";
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
	$userId		 = Yii::app()->request->getParam('agtid');
	$newPassword	 = Yii::app()->request->getParam('newPassword');
	$repeatPassword	 = Yii::app()->request->getParam('repeatPassword');
	$success	 = false;
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
        $userid = Yii::app()->user->getId();
        if (in_array($userid, [15, 9]))
        {
            $this->redirect(array("booking/list"));
        }
        $this->pageTitle            = '';
        $this->layout               = 'admin1';
        /* @var $model Booking */
        $model                      = new Booking();
        /* @var $modelsub BookingSub */
        $modelsub                   = new BookingSub();
        $sourceList                 = Admins::getDashbordSource();
        $countMissingDrivers        = $modelsub->getMissingDrivers();
        $countUnassignedVendors     = $modelsub->getUnassignedVendors();
        $countTripDrivers           = BookingCab::model()->getTripsDriver();
        $countTripVendors           = BookingCab::model()->getTripsVendor();
        $countAccountsFlag          = $modelsub->getAccountsAttention();
        $countMissingCarsDoc        = BookingCab::model()->getMissingCarDocs();
        $countMissingDriversDoc     = BookingCab::model()->getMissingDriverDocs();
        $countActiveEscalations     = $modelsub->getActiveEscalations();
        $countUnverifiedLeeds       = $modelsub->getUnverifiedLeeds();
        $countVendorDocMissing      = Vendors::model()->countVendorDocMissing();
        $countVendorBankMissing     = Vendors::model()->countVendorBankMissing();
        $countVendorUnassigned5days = BookingCab::model()->getCountVendorUnassigned5days();
        $countVendorFloating24hrs   = BookingCab::model()->getCountVendorFloating24hrs();
        $model->bcbTypeMatched      = [0];
        $matchListProvider          = $modelsub->getMatchedList($model->bcbTypeMatched);


        $countUndocumentCommercial    = $modelsub->getCountUndocumentCarsCommercial();
        $countUndocumentNonCommercial = $modelsub->getCountUndocumentCarsNonCommercial();
        $countReconfirmPending        = $modelsub->getCountReconfirmPending36hrs();
        $countNonProfitable           = $modelsub->getCountNonProfitable();
        $countVendorsForApproval      = $modelsub->countVendorsForApproval();
        $countDriversForApproval      = $modelsub->countDriversForApproval();
        $countCarsForApproval         = $modelsub->countCarForApproval();
        $countHireVendorAmount        = $modelsub->countHigherVendorAmount();

        $outputJs = Yii::app()->request->isAjaxRequest;
        $method   = "render" . ($outputJs ? "Partial" : "");
        $this->$method('dashboard_new', array(
            'model'                        => $model,
            'sourceList'                   => $sourceList,
            'countMissingDrivers'          => $countMissingDrivers,
            'countUnassignedVendors'       => $countUnassignedVendors,
            'countTripDrivers'             => $countTripDrivers,
            'countTripVendors'             => $countTripVendors,
            'countAccountsFlag'            => $countAccountsFlag,
            'countMissingCarsDoc'          => $countMissingCarsDoc,
            'countMissingDriversDoc'       => $countMissingDriversDoc,
            'countActiveEscalations'       => $countActiveEscalations,
            'countUnverifiedLeeds'         => $countUnverifiedLeeds,
            'countVendorDocMissing'        => $countVendorDocMissing,
            'countVendorBankMissing'       => $countVendorBankMissing,
            'countVendorUnassigned5days'   => $countVendorUnassigned5days,
            'countMatchList'               => count($matchListProvider->rawData),
            'countVendorFloating24hrs'     => $countVendorFloating24hrs,
            'countUndocumentCommercial'    => $countUndocumentCommercial,
            'countUndocumentNonCommercial' => $countUndocumentNonCommercial,
            'countReconfirmPending'        => $countReconfirmPending,
            'countNonProfitable'           => $countNonProfitable,
            'countVendorsForApproval'      => $countVendorsForApproval,
            'countDriversForApproval'      => $countDriversForApproval,
            'countCarsForApproval'         => $countCarsForApproval,
            'countHireVendorAmount'        => $countHireVendorAmount
                ), false, $outputJs);
    }


    public function forgotPassword()
    {
	$email	 = Yii::app()->request->getParam('forgotemail');
	$users	 = Users::model()->find("usr_email=:email", ['email' => $email]);
	if (count($users) > 0)
	{
	    $username	 = $users->usr_name;
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
	    $phone		 = $users->usr_mobile;
	    if ($smsWrapper->sendForgotPassCodeAgent('91', $phone, $code))
	    {
		$delivered1 = "Message sent successfully";
	    }
	    else
	    {
		$delivered1 = "Message not sent";
	    }
	    $body				 = $mail->Body;
	    $usertype			 = EmailLog::Agent;
	    $subject			 = 'Reset your Password';
	    $refId				 = $users->user_id;
	    $refType			 = EmailLog::REF_AGENT_ID;
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
  public function createLog($identity)
    {
        $ip                               = \Filter::getUserIP();
        $sessionid                        = Yii::app()->getSession()->getSessionId();
        $admlogModel                      = new AdminLog();
        $admlogModel->adm_log_in_time     = new CDbExpression('Now()');
        $admlogModel->adm_log_ip          = $ip;
        $admlogModel->adm_log_session     = $sessionid;
        $admlogModel->adm_log_device_info = $_SERVER['HTTP_USER_AGENT'];
        $admlogModel->adm_log_user        = $identity->getId();
        $admlogModel->save();
        return true;
    }
}
