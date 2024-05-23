<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class ContactController extends BaseController
{

	public $newHome	 = '';
	public $layout	 = '//layouts/column1';
	public $afterVal = '';
	public $email_receipient;

//	public function filters()
//	{
//		return array(
//			array(
//				'application.filters.HttpsFilter + create, signin',
//				'bypass' => false),
//			'accessControl', // perform access control for CRUD operations
//			'postOnly + delete', // we only allow deletion via POST request
//			array(
//				'RestfullYii.filters.ERestFilter +
//                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'
//			),
//		);
//	}

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
				"actions"	 => array(),
				"users"		 => array("@"),
			),
			array
				("allow", // allow authenticated user to perform "create" and "update" actions
				"actions"	 => array
					(
					"REST.GET", "REST.PUT", "REST.POST", "REST.DELETE", "REST.OPTIONS", "uploads"
				),
				"users"		 => array("*"),
			),
			array
				("allow", // allow admin user to perform "admin" and "delete" actions
				"actions"	 => array("verifyEmail", "checkLicenseNo", "createVendorSuccess", "resendVerificationLink", "verifyData", "addContactDetails", "validatePhone", "removeContact"),
				"users"		 => array("*"),
			),
			array
				("deny", // deny all users
				"users" => array("*"),
			),
		);
	}

	public function actionCreateVendorSuccess()
	{
		//echo 12;
		$this->checkForMobileTheme();
		$this->render("createVendorSucces.php");
	}

	/**
	 * This function is used for finding the email existence
	 */
	public function actionCheckEmailExistence()
	{
		$returnset = new ReturnSet();

		//Request Instance
		$requestInstance = Yii::app()->request;
		if (!$requestInstance->isAjaxRequest)
		{
			$returnset->setMessage("Unauthorized");
			goto skipAll;
		}

		$rEmailId	 = $requestInstance->getParam("emailId");
		$returnset	 = ContactEmail::checkData($rEmailId);

		skipAll:
		echo json_encode($returnset);

		exit;
	}

	/**
	 * This function is used for validating the licenseNo
	 * @param type $param
	 */
	public function actionCheckLicenseNo()
	{
		$requestInstance = Yii::app()->request;

		//This function is used for updating and inserting new mappings
		if ($requestInstance->isAjaxRequest)
		{
			$receivedData	 = $requestInstance->getParam("dataToCheck");
			$licenseNo		 = $receivedData->licenseNo;
			$return			 = Contact::checkLicenseNo($licenseNo);

			echo json_encode($return);
			exit;
		}
	}

	/**
	 * 
	 * @throws Exception
	 */
	public function actionVerifyContactId()
	{
		$returnset = new ReturnSet();
		try
		{
			$requestInstance = Yii::app()->request;
			$email			 = $requestInstance->getParam("email");
			if (empty($email))
			{
				throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
			}

			$userEmail = $email;
			if( strpos($email, ",") !== false ) 
			{
				$emailArray		 = explode(",", $email);
				$res			 = $emailArray[1];
				$userEmail		 = $emailArray[0];
			}

			$userModel = Users::model()->find('usr_email=:email', ['email' => $userEmail]);

			if (empty($userModel))
			{
				throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
			}

			$isExistVendor = Vendors::model()->checkExistingVendor($userModel->user_id);

			//Check for related profile
			if (!$isExistVendor)
			{
				$result = ContactEmail::findEmail($res, 0, 1, 1);

				$response = new stdClass();

				$response->userId		 = $userModel->user_id;
				$response->contactDetail = $result->getData();

				$returnset->setStatus(true);
				$returnset->setData($response);
			}
		}
		catch (Exception $ex)
		{
			$returnset = ReturnSet::setException($ex);
		}

		echo json_encode($returnset);
		exit;
	}

	public function actionApprove()
	{
		$urlHash		 = Yii::app()->request->getParam('id');
		$type			 = Yii::app()->request->getParam('type');
		$hashArray		 = explode('_', $urlHash);
		$contactId		 = Yii::app()->shortHash->unhash($hashArray[0]);
		$templateStyle	 = Yii::app()->shortHash->unhash($hashArray[1]);

		$model = Contact::model()->findByPk($contactId);
		switch ($templateStyle)
		{
			case Contact::NEW_CON_TEMPLATE:
				$vndId			 = Yii::app()->shortHash->unhash($hashArray[3]);
				$email			 = base64_decode($hashArray[2]);
				$templateStyle	 = Contact::NEW_CON_TEMPLATE;
				break;
			case Contact::MODIFY_CON_TEMPLATE:
				$email			 = base64_decode($hashArray[2]);
				$templateStyle	 = Contact::MODIFY_CON_TEMPLATE;
				break;
			case Contact::NOTIFY_OLD_CON_TEMPLATE:
				$tempPkId		 = Yii::app()->shortHash->unhash($hashArray[2]);
				$vndName		 = base64_decode($hashArray[3]);
				$vndId			 = Yii::app()->shortHash->unhash($hashArray[4]);

				$tempModel		 = ContactTemp::model()->findByPk($tempPkId);
				$email			 = $tempModel->tmp_ctt_email;
				$templateStyle	 = Contact::NOTIFY_OLD_CON_TEMPLATE;
				break;
			default:
				break;
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			echo json_encode($urlHash);
			Yii::app()->end();
		}
		$this->render('verifyEmail', array('model' => $model, 'tempModel' => $tempModel, 'contactId' => $contactId, 'tempPkId' => $tempPkId, 'contactEmail' => $email, 'templateStyle' => $templateStyle, 'vndName' => $vndName, 'vndId' => $vndId));
	}

	/**
	 * 
	 * @throws CHttpException
	 * @throws Exception
	 */
	public function actionVerifyEmail()
	{
		$returnSet = new ReturnSet();
		try
		{
			$contactId		 = Yii::app()->request->getParam('cttId');
			$hasContactId	 = Yii::app()->request->getParam('hasContactId');
			$isVerify		 = Yii::app()->request->getParam('isVerify');
			$templateStyle	 = Yii::app()->request->getParam('templateStyle');
			$email			 = Yii::app()->request->getParam('modifyEmail');
			$isExpireLink	 = Yii::app()->request->getParam('expireLink');
			$type			 = Contact::TYPE_EMAIL;
			$mode			 = Contact::MODE_LINK;
			if ($contactId != $hasContactId)
			{
				throw new CHttpException(400, 'Invalid Request');
			}
			/**
			 * expire email verification link
			 */
			if ($isExpireLink > 0)
			{
				$isExpire = ContactEmail::expireLink($email);
			}

			switch ($templateStyle)
			{

				case Contact::NEW_CON_TEMPLATE:
					$vndId		 = Yii::app()->request->getParam('vndId');
					$returnSet	 = Contact::verifyItem($contactId, $type, $mode, $isVerify, $email);

					if ($returnSet->getStatus() && $vndId > 0)
					{
						$drvModel		 = Drivers::model()->findByDriverContactID($contactId);
						$arr			 = ['driver' => $drvModel->drv_id, 'vendor' => $vndId];
						$isExitVendor	 = VendorDriver::model()->checkAndSave($arr);
						if ($isExitVendor)
						{
							VendorStats::model()->updateCountDrivers($vndId);
						}
						BookingCab::model()->updateVendorPayment($flag = 1, $drvModel->drv_id);
					}
					break;
				case Contact::NOTIFY_OLD_CON_TEMPLATE:
					/** @var ContactTemp $tempModel */
					$temPkId		 = Yii::app()->request->getParam('tempPkId');
					$vndId			 = Yii::app()->request->getParam('vndId');
					$vndName		 = Yii::app()->request->getParam('vendorName');
					$tempModel		 = ContactTemp::model()->findByPk($temPkId);
					$drvModel		 = Drivers::model()->findByDriverContactID($tempModel->tmp_ctt_contact_id);
					$isVerifyStatus	 = ContactTemp::model()->updateContactStatus($temPkId, $isVerify);

					$drvId = $drvModel->drv_id;
					if (empty($drvId))
					{
						$response = Drivers::model()->addDriverDetails($tempModel->tmp_ctt_contact_id, $tempModel->tmp_ctt_name);
						if ($response->getStatus())
						{
							$drvId = $response->getData();
						}
					}
					ContactProfile::setProfile($drvModel->drv_contact_id, UserInfo::TYPE_DRIVER);
					$arr			 = ['driver' => $drvId, 'vendor' => $vndId];
					$isExitVendor	 = VendorDriver::model()->checkAndSave($arr);
					if ($isExitVendor)
					{
						VendorStats::model()->updateCountDrivers($vndId);
					}
					BookingCab::model()->updateVendorPayment($flag		 = 1, $drvId);
					break;
				case Contact::MODIFY_CON_TEMPLATE:
					$returnSet	 = Contact::verifyItem($contactId, $type, $mode, $isVerify, $email);
					break;
				default:
					break;
			}
			if ($returnSet)
			{
				echo json_encode($returnSet);
				Yii::app()->end();
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
	}

	/**
	 * This function is used for triggering email link
	 */
	public function actionSendVerficationLink()
	{
		$returnSet = new ReturnSet();

		try
		{
			$contactId		 = Yii::app()->request->getParam('cttId');
			$emailAddress	 = ContactEmail::model()->getContactEmailById($contactId);
			$result			 = Contact::sendEmailVerificationLink($emailAddress, $contactId, UserInfo::TYPE_VENDOR);
		}
		catch (Exception $ex)
		{
			throw new Exception(json_encode($result->getErrors()));
		}
	}

	/** This function is used for validating Phone number
	 * @throws Exception
	 */
	public function actionVerifyPhone()
	{
		$returnSet = new ReturnSet();
		try
		{
			$hashContactId	 = Yii::app()->request->getParam('id');
			$otpHash		 = Yii::app()->request->getParam('otp');
			$contactId		 = Yii::app()->shortHash->unhash($hashContactId);
			$templateHash	 = Yii::app()->request->getParam('ts');
			$vndHash		 = Yii::app()->request->getParam('vnd');
			$templateStyle	 = Yii::app()->shortHash->unhash($templateHash);

			$model	 = Contact::model()->findByPk($contactId);
			$phoneNo = ContactPhone::model()->getContactPhoneById($contactId);
			$type	 = Contact::TYPE_PHONE;
			$mode	 = Contact::MODE_OTP;
			switch ($templateStyle)
			{
				case Contact::NEW_CON_TEMPLATE:
					$vndId			 = Yii::app()->shortHash->unhash($vndHash);
					$templateStyle	 = Contact::NEW_CON_TEMPLATE;
					break;
				case Contact::MODIFY_CON_TEMPLATE:
					$num			 = Yii::app()->request->getParam('num');
					$phoneNo		 = base64_decode($num);
					$templateStyle	 = Contact::MODIFY_CON_TEMPLATE;
					break;
				case Contact::NOTIFY_OLD_CON_TEMPLATE:

					$tempPkHash		 = Yii::app()->request->getParam('tpk');
					$vndHash		 = Yii::app()->request->getParam('v');
					$vndName		 = base64_decode($vndHash);
					$tempPkId		 = Yii::app()->shortHash->unhash($tempPkHash);
					$tempModel		 = ContactTemp::model()->findByPk($tempPkId);
					$phoneNo		 = $tempModel->tmp_ctt_phn_number;
					$templateStyle	 = Contact::NOTIFY_OLD_CON_TEMPLATE;
					break;
				default:
					break;
			}

			if (Yii::app()->request->isAjaxRequest)
			{
				$cid			 = Yii::app()->request->getParam('hash');
				$contactId		 = Yii::app()->request->getParam('cttid');
				$code			 = Yii::app()->request->getParam('code');
				$otp			 = Yii::app()->request->getParam('otp');
				$vndId			 = Yii::app()->request->getParam('vndId');
				$phone			 = Yii::app()->request->getParam('modifyPhone');
				$isExpireLink	 = Yii::app()->request->getParam('expireLink');
				if (Yii::app()->shortHash->unhash($otp) == $code && Yii::app()->shortHash->unhash($cid) == $contactId)
				{
					/**
					 *  expire phone verification link
					 */
					if ($isExpireLink > 0)
					{
						$isExpire = ContactPhone::expireLink($phone);
					}
					$returnSet = Contact::verifyItem($contactId, $type, $mode, 0, null, $phone);
					if ($returnSet->getStatus() && $vndId > 0)
					{
						$drvModel		 = Drivers::model()->findByDriverContactID($contactId);
						$arr			 = ['driver' => $drvModel->drv_id, 'vendor' => $vndId];
						$isExitVendor	 = VendorDriver::model()->checkAndSave($arr);
						if ($isExitVendor)
						{
							VendorStats::model()->updateCountDrivers($vndId);
						}
						BookingCab::model()->updateVendorPayment($flag = 1, $drvModel->drv_id);
					}
				}
				if ($returnSet)
				{
					echo json_encode($returnSet);
					Yii::app()->end();
				}
				else
				{
					echo json_encode(['success' => false]);
					Yii::app()->end();
				}
			}
		}
		catch (Exception $exc)
		{
			throw new Exception(json_encode($model->getErrors()));
		}
		$this->render('verifyPhone', array('model' => $model, 'tempModel' => $tempModel, 'conid' => $hashContactId, 'otp' => $otpHash, 'phone' => $phoneNo, 'templateStyle' => $templateStyle, 'vndName' => $vndName, 'vndId' => $vndId, 'contactId' => $contactId));
	}

	public function actionVerifyData()
	{
		if (yii::app()->request->isAjaxRequest)
		{
			$value		 = Yii::app()->request->getParam('value');
			$cttId		 = Yii::app()->request->getParam('cttid');
			$type		 = Yii::app()->request->getParam('type');
			$userType	 = UserInfo::TYPE_CONSUMER;
			$response	 = Contact::contactVerificationBySendOtp($value, $cttId, $type, $userType);
		}
		echo CJSON::encode($response);
		Yii::app()->end();
	}

	
	public function actionValidateOtp()
	{
		$returnSet = new ReturnSet();
		try
		{
			if (Yii::app()->request->isAjaxRequest)
			{
				$code		 = Yii::app()->request->getParam('code');
				$otpHash	 = Yii::app()->request->getParam('otphash');
				$contactId	 = Yii::app()->request->getParam('cttid');
				$decryptOtp	 = Filter::decrypt($otpHash);
				$decriptArr	 = json_decode($decryptOtp);

				$returnSet = Contact::verifyInfo($decriptArr, $contactId, $code);

				if ($returnSet->getStatus())
				{
					$msg = $decriptArr->value . " has been verified successfully";
				}
				$returnSet->setMessage($msg);
				$returnSet->setData($decriptArr);
			}
		}
		catch (Exception $exc)
		{
			throw new Exception(json_encode($exc->getErrors()));
		}
		echo CJSON::encode($returnSet);
		Yii::app()->end();
	}

	public function actionSetPrimaryVerifiedContact()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$value		 = Yii::app()->request->getParam('value');
			$cttId		 = Yii::app()->request->getParam('id');
			$type		 = Yii::app()->request->getParam('type');
			$userType	 = UserInfo::TYPE_CONSUMER;
			$isVerified  = Contact::checkIsVerified($value, $cttId, $type);
			if($isVerified != 1)
			{
				$response = Contact::contactVerificationBySendOtp($value, $cttId, $type, $userType);				
			}
			else
			{
				$response = Contact::setPrimaryByType($type, $value, $cttId);
			}
			echo CJSON::encode($response);
			Yii::app()->end();
		}
	}

	public function actionAddContactDetails()
	{
		$this->checkV3Theme();
		$request = Yii::app()->request;
		$outputJs	 = Yii::app()->request->isAjaxRequest;

		$cttId	 = $request->getParam('cttId');
		$type	 = $request->getParam('type');
		$model	 = Contact::model()->findByPk($cttId);
		#$error	 = '';
		$phoneModel	 = new ContactPhone('validate');
		$emailModel	 = new ContactEmail('validate');
		$returnSet	 = new ReturnSet();
		try
		{
			if ($request->isPostRequest)
			{
				$contactEmail	 = $request->getParam('ContactEmail');
				$contactPhone	 = $request->getParam('ContactPhone');

				if ($type == Stub\common\ContactVerification::TYPE_EMAIL)
				{
					$returnSet = ContactEmail::model()->addNewByContact($emailModel, $contactEmail, $cttId);
				}

				if ($type == Stub\common\ContactVerification::TYPE_PHONE)
				{
					$returnSet = ContactPhone::model()->addNewByContact($phoneModel, $contactPhone, $cttId);
				}
				echo CJSON::encode($returnSet);
		        Yii::app()->end();
				//Yii::app()->getController()->redirect(array("users/view"));
			}
		}
		catch (Exception $ex)
		{
			echo "Error: ".$ex->getMessage();
			$returnSet->setException($ex);
		}

		
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('addContact', array('model'		 => $model,
			'phoneModel' => $phoneModel,
			'emailModel' => $emailModel,
			'type'		 => $type), false, $outputJs);
	}

	public function actionremoveContact()
	{
		$request = Yii::app()->request;
		$value	 = $request->getParam('value');
		$cttId	 = $request->getParam('id');
		$type	 = $request->getParam('type');

		$response = Contact::removeDataByType($type, $value, $cttId);

		echo CJSON::encode($response);
		Yii::app()->end();
	}
}

?>