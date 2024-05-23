<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



include_once(dirname(__FILE__) . '/BaseController.php');

class VendorsController extends BaseController
{

	public $newHome	 = '';
	public $layout	 = '//layouts/column1';
	public $afterVal = '';
	public $email_receipient;

	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter',
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
		return array
			(
			array
				("allow", // allow all users to perform "index" and "view" actions
				"actions"	 => array('attach'),
				"users"		 => array("@"),
			),
			array
				("allow", // allow authenticated user to perform "create" and "update" actions
				"actions"	 => array(),
				"users"		 => array("*"),
			),
			array
				("allow", // allow admin user to perform "admin" and "delete" actions
				"actions"	 => array(),
				"users"		 => array("*"),
			),
			array
				("deny", // deny all users
				"users" => array("*"),
			),
		);
	}

	public function actionAttach()
	{
		$userInfo = \UserInfo::getInstance();
		if ($userInfo->userId == '')
		{
			$returnUrl = $this->getURL('vendor/attach');
			Yii::app()->user->getReturnUrl($returnUrl);
			$this->redirect("vendors/signin", ['vAttach' => $returnUrl]);
		}
		else
		{
			$this->redirect("operator/register");
		}
		exit;
	}

	public function getEncrptedData()
	{
		$data = json_encode(\Filter::removeNull($this));
		return \Filter::encrypt($data);
	}

	public function actionSignin()
	{
		$this->layout	 = 'signin';
		$this->pageTitle = "Login";
		$step			 = 2;
		$view			 = "signin";

		/** @var HttpRequest $request */
		$request	 = Yii::app()->request;
		$showPhone	 = $request->getParam("phone", 0);
		$returnUrl	 = $this->getURL('vendors/attach');
		if (UserInfo::isLoggedIn() && $showPhone == 0)
		{
			$this->redirect($returnUrl);
		}

		try
		{
			$userModel		 = new Users("userLogin");
			$contactModel	 = new Contact();
			$phoneModel		 = new ContactPhone();
			$emailModel		 = new ContactEmail();
			$params			 = [
				"userModel"		 => $userModel,
				'contactModel'	 => $contactModel,
				'phoneModel'	 => $phoneModel,
				'emailModel'	 => $emailModel,
				"showPhone"		 => $showPhone
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
			$params['hasErrors']	 = true;
			$params['errorMessage']	 = $e->getMessage();
		}
		view:
		$this->renderAuto($view, $params, false, true);
	}

	public function actionOtpVerify()
	{
		/** @var HttpRequest $request */
		$request = Yii::app()->request;

		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType());
		$this->layout	 = 'column_booking';
		$this->pageTitle = "OTP verify";
		$returnset		 = new ReturnSet();

		$objPage = $this->getRequestData();
		$signup	 = $request->getParam('signup', 1);

		$params = [];
		try
		{
			$returnUrl = Yii::app()->user->getReturnUrl();
			if ($returnUrl == '')
			{
				$returnUrl = Yii::app()->createUrl('vendor/attach', $params);
			}

			$verifyData	 = $request->getParam('verifyData');
			$data		 = Yii::app()->JWT->decode($verifyData);
			$curOtp		 = $request->getParam('otp');

			if (!$data)
			{
				throw new Exception("Invalid Request", ReturnSet::ERROR_INVALID_DATA);
			}

			$objCttVerify = $objPage->getContact($data->type, $data->value);

			if ($objCttVerify->isVerified())
			{
				
			}

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
			$createIfNotExist	 = ($signup == 2);
			$contactId			 = Contact::getByEmailPhone($sessEmail, $sessPhone, $createIfNotExist);
			if (!$contactId)
			{
				throw new Exception("Sorry, we couldn't find this data in our records", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			$userModel = Users::createbyContact($contactId);
			if (count($userModel) > 0)
			{
				$identity			 = new UserIdentity($userModel->usr_name, null);
				$identity->userId	 = $userModel->user_id;
				if ($identity->authenticate())
				{
					$objPage->clearContact();
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
}
