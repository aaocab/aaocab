<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class UserController extends BaseController
{

	public $layout = 'head';
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
				'actions'	 => array('dashboard','signout'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('signin','verifyotp','test','resendotp',
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array(),
				'users'		 => array('admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
				'deniedCallback' => function() { Yii::app()->controller->redirect(array ('/supplier/user/signin')); }
			),
		);
	}

	public function actionSignin()
	{
		$username = Yii::app()->request->getParam('username');
		if($username!='')
		{
			try
			{
				$isEmail	 = Filter::validateEmail($username);
				$userEmail = $username;
				$userPhone = $username;
				$isPhone	 = false;

				if (!$isEmail)
				{
					$isPhone = Filter::processPhoneNumber($username);
					$userEmail = '';
				}
				else
				{
					$userPhone = '';
				}

				if (!$isEmail && !$isPhone)
				{
					throw new Exception("Please enter valid email/phone.", ReturnSet::ERROR_INVALID_DATA);
				}

				$contactId = \Contact::getByEmailPhone($userEmail, $userPhone);
				if ($contactId == '')
				{
					throw new Exception("Sorry, this contact is not registered with us", ReturnSet::ERROR_NO_RECORDS_FOUND);
				}

				$contactData	 = ContactProfile::getPrimaryEntitiesByContact($contactId);

				$vndId		 = $contactData['cr_is_vendor'];
				if ($vndId > 0)
				{
					$vndStatus = Vendors::model()->findByPk($vndId)->vnd_active;
					if ($vndStatus == 2)
					{
						throw new Exception("Your account is blocked.", ReturnSet::ERROR_VALIDATION);
					}
					if ($vndStatus == 0)
					{
						throw new Exception("Your account has been deleted.", ReturnSet::ERROR_VALIDATION);
					}
				}
				else
				{
					throw new Exception("No operator/supplier associated with this account.", ReturnSet::ERROR_VALIDATION);
				}

				$type	 = ($isEmail) ? 1 : (($isPhone) ? 2 : 0);
				$value	 = $username;
				$ctVerify	 = new \Beans\common\ContactVerification($type, $value);

				$ctVerify->getModel();

				$otp			 = rand(1001, 9999);
				$otpType		 = $ctVerify->type;
				$inputValue	 = $ctVerify->value;

				$isSend = $this->dispatchOTP($otp, $otpType, $inputValue, 0, Booking::Platform_Supplier);

				if (!$isSend)
				{
					throw new Exception('Sorry, unable to send  OTP', ReturnSet::ERROR_FAILED);
				}
					$pageOtp = true;
			}
			catch(Exception $e)
			{
				$errors = $e->getMessage();
				$pageOtp = false;
			}
			$contact  = Contact::model()->findByPk($contactId);

			if($type == 2)
			{
				$len = strlen($username);
				$last_three = substr($username, -3);

				$masked_username = "";
				for ($i = 0; $i < $len - 3; $i++) {
				  $masked_username .= "x";
				}
				$masked_username .= $last_three;
			}
			else
			{
					list($email, $domain) = explode("@", $username);

					$masked_username = substr($email, 0, 3) . str_repeat("*", strlen($email) - 3);

					$masked_username = $masked_username . "@" . $domain;
			}
			$dataArr = ['otp' => $otp, 'type' => $otpType, 'value' => $inputValue];
			//$encryptedData = Filter::encrypt($dataArr);
			$encryptedData = Yii::app()->JWT->encode($dataArr);
			$this->renderAuto('signin',['pageOtp'=>$pageOtp,'errors'=>$errors,'testOtp'=>'','encryptedData'=>$encryptedData,'type'=>$type,'name'=>$contact->ctt_first_name." ".$contact->ctt_last_name,'masked_username'=>$masked_username]);
			Yii::app()->end();
		}

		
		if (Yii::app()->user->isGuest)
		{
			$this->renderAuto('signin',['signinPage1'=>true]);
		}
		else
		{
			$this->redirect('dashboard');
		}	
	}

	public function actionVerifyotp()
	{
		$encryptedData = Yii::app()->request->getParam('verifyNo');
		$otp1 = trim(Yii::app()->request->getParam('otp1'));
		$otp2 = trim(Yii::app()->request->getParam('otp2'));
		$otp3 = trim(Yii::app()->request->getParam('otp3'));
		$otp4 = trim(Yii::app()->request->getParam('otp4'));

		if($encryptedData!='' && $otp1 >= 0 && $otp2 >= 0 && $otp3 >= 0 && $otp4 >= 0)
		{
			try{
				//$decryptData = Filter::decrypt($encryptedData);
				$decryptData = Yii::app()->JWT->decode($encryptedData);
				$decryptArr	 = $decryptData;
				$otpSent	 = $decryptArr->otp;  //otpDecrypted
				$otpEntered = $otp1.$otp2.$otp3.$otp4;
				if ($otpSent != $otpEntered)
				{
					throw new Exception("OTP Mistmached. Please try again", ReturnSet::ERROR_INVALID_DATA);
				}
				$validate = true;
				$userEmail = '';
				$userPhone = $decryptArr->value;
				if ($decryptArr->type == 1)
				{
					$userEmail = $decryptArr->value;
					$userPhone = '';
				}

				$contactId = \Contact::getByEmailPhone($userEmail, $userPhone);

				\Contact::markVerified($contactId, $decryptArr->type, $decryptArr->value);

				if (!$validate && !$contactId)
				{
					return false;
				}
				if (!$contactId)
				{
					throw new Exception("Sorry, we couldn't find this data in our records", ReturnSet::ERROR_INVALID_DATA);
				}

				$userModel		 = Users::createbyContact($contactId);
				$userModel->username = $decryptArr->value;
				if (!$userModel)
				{
					throw new Exception("Unable to login.", ReturnSet::ERROR_VALIDATION);
				}
				$identity = $this->authenticateUser($userModel);
				if (!$identity)
				{
					throw new Exception("Unable to get user identity.", ReturnSet::ERROR_VALIDATION);
				}
				$contactData	 = ContactProfile::getPrimaryEntitiesByContact($contactId);

				$vndId		 = $contactData['cr_is_vendor'];
				Yii::app()->user->login($identity);
				Yii::app()->user->setEntityID($vndId);
				Yii::app()->user->setPlatform(Users::Platform_Supplier);
			}
			catch(Exception $e)
			{
				echo json_encode(['success'=>false,'errors'=>$e->getMessage()]);
				Yii::app()->end();
			}
			echo json_encode(['success'=>true,'url'=>Yii::app()->createUrl('supplier/user/dashboard')]);
			Yii::app()->end();
		}

	}

	public function dispatchOTP($otp, $otpType, $inputValue, $isCerf = 0, $platform = Booking::Platform_App)
	{

		$isSend = false;

		switch ($otpType)
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

	private function authenticateUser($userModel)
	{
		$passWord			 = $userModel->usr_password;
		$username			 = ($userModel->username) ? $userModel->username : $userModel->usr_email;
		$identity			 = new UserIdentity($username, $passWord);
		$identity->userId	 = $userModel->user_id;
		if (!$identity->authenticate())
		{
			throw new Exception("Unable to authenticate", 400);
		}
		return $identity;
	}

	public function actionSignout()
	{
		Yii::app()->user->logout();
		$this->redirect('signin');
	}

	public function actionDashboard()
	{
		$this->layout = 'main';
		$cntBookings = BookingSub::getCountBookingsByVendor(Yii::app()->user->getEntityID());
		$this->render('dashboard',['cntBookings'=>$cntBookings]);
	}

	public function actionResendotp()
	{
		$encryptedData = Yii::app()->request->getParam('verifyNo');
		if($encryptedData!='')
		{
			$decryptData = Yii::app()->JWT->decode($encryptedData);
			$otp			 = rand(1001, 9999);
			$isSend = $this->dispatchOTP($otp, $decryptData->type, $decryptData->value, 0, Booking::Platform_Supplier);
			if (!$isSend)
			{
				throw new Exception('Sorry, unable to send  OTP', ReturnSet::ERROR_FAILED);
			}
			$dataArr = ['otp' => $otp, 'type' => $decryptData->type, 'value' => $decryptData->value];
			$encryptedData = Yii::app()->JWT->encode($dataArr);
			echo json_encode(['success'=>true,'encryptedData'=>$encryptedData,'testOtp'=>$otp]);
			Yii::app()->end();
		}
		echo json_encode(['success'=>false,'errors'=>'unable to resend otp']);
		Yii::app()->end();
	}

}
