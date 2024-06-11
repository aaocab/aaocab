<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class OperatorController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $newHome				 = '';
	public $layout				 = '//layouts/head1';
	public $fileatt;
	public $email_receipient;
	public $pageHeader			 = '';
	public $showProfileComplete	 = true;

	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter',
				'bypass' => false),
			//'postOnly + agentjoin,vendorjoin',
			'postOnly + agentjoin',
			array(
				'CHttpCacheFilter + country',
				'lastModified' => $this->getLastModified(),
			),
		);
	}

	public function accessRules()
	{
		return array(
			['allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array(),
				'users'		 => array('@'),
			],
			['allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(),
				'users'		 => array('*'),
			],
			['allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array(),
				'users'		 => array('admin'),
			],
			['allow',
				'actions'	 => [],
				'users'		 => ['*']],
			['deny', // deny all users
				'users' => array('*'),
			],
		);
	}

	function getLastModified()
	{
		$date = new DateTime('NOW');
		$date->sub(new DateInterval('PT50S'));
		return $date->format('Y-m-d H:i:s');
	}

	public function actionRegister()
	{
		$request	 = Yii::app()->request;
		$contact	 = $request->getParam("Contact");
		$isDCO		 = $request->getParam('isDCO');
		$formType	 = $request->getParam('formType');
		$errorMsg	 = $request->getParam('errorMsg');

		$userInfo = \UserInfo::getInstance();
		if ($userInfo->userId == '')
		{

			echo ("Session logged out.");
			$this->redirect('/vendor/attach');
			Yii::app()->end();
		}
		$contactDataProfile = \ContactProfile::getEntitybyUserId($userInfo->userId);

		$cttModel	 = \Contact::model()->findByPk($contactDataProfile['primaryContact']);
		$contactData = \ContactProfile::getProfileByCttId($contactDataProfile['primaryContact']);

		/** @var VendorPref $vndPref */
		$vndPref	 = new VendorPref();
		$uploadDone	 = 0;
		try
		{
			if ($contactData['cr_is_vendor'] > 0)
			{
				$vndId		 = $contactData['cr_is_vendor'];
				/** @var \Vendors $vndModel */
				$vndModel	 = \Vendors::model()->findByPk($vndId);
				if (($vndModel->vnd_is_dco == null || !$vndModel->vnd_is_dco) && $isDCO > 0)
				{
					$vndModel->vnd_is_dco	 = ($isDCO == 1) ? 1 : 0;
					$vndModel->vnd_cat_type	 = ($isDCO == 1) ? 1 : 2;
					$vndModel->save();
				}
				$isDCO = $vndModel->vnd_is_dco;

				$vndPref = $vndModel->vendorPrefs;

				$vhcId	 = $this->getVehicleId(false);
				$hasDiff = [];
				if ($vhcId > 0)
				{
					$valDocList	 = [1, 2, 3, 5, 6, 8, 9];
					$vDocModels	 = VehicleDocs::getByVehicleId($vhcId);
					$docTypeArr	 = array_keys($vDocModels);

					$hasDiff = array_diff($valDocList, $docTypeArr);
					if (!$hasDiff)
					{
						$uploadDone = 1;
					}
				}
			}

			if ($request->isPostRequest)
			{

				$cttModel->attributes = $contact;

				$emlModel = Yii::app()->request->getParam("contactEmails");
				if ($emlModel && $emlModel['eml_email_address'])
				{
					$cttModel->contactEmails	 = [];
					$emlModel->eml_is_primary	 = 1;
					$emlModels					 = [$emlModel];
					$cttModel->contactEmails	 = $emlModels;

					$returnSet = ContactEmail::model()->addNew($cttModel->ctt_id, $emlModel['eml_email_address'], SocialAuth::Eml_aaocab, $emlModel->eml_is_primary, 1);
					if ($returnSet->isSuccess())
					{
						ContactEmail::setPrimaryEmail($cttModel->ctt_id);
					}
				}
				$cttModel->save();

				if (Yii::app()->request->isAjaxRequest)
				{
					//	$obj->data = json_decode(['success'=>true,'message'=>'saved']);
					echo CJSON::encode(['success' => true, 'message' => 'saved']);
					Yii::app()->end();
				}
			}

//		$docFieldArr		 = Document::getFieldByType();
			/** @var Contact $cttModel */
			$docLicenseModel	 = new Document();
			$docAdharModel		 = new Document();
			$docVoterModel		 = new Document();
			$docPoliceVerModel	 = new Document();
			$docPANModel		 = new Document();

			if (trim($cttModel->ctt_license_no) != '' && $cttModel->ctt_license_doc_id > 0)
			{
				$docLicenseModel = Document::model()->findByPK($cttModel->ctt_license_doc_id);
			}


			if ($docLicenseModel->doc_id > 0 &&
					(($contactData['cr_is_driver'] == '' && $isDCO == 1) || $contactData['cr_is_vendor'] == '' ))
			{
				$returnSet = $cttModel->registerDCO($isDCO, 1);
				if (!$returnSet->getStatus())
				{
					$errorMsg = $returnSet->getMessage();
					if ($formType == 'opserv')
					{
						$formType = 'bi';
					}
//				else
//				{
//					$formType = 'lic';
//				}
				}
				$dataArr = $returnSet->getData();
			}
			if (trim($cttModel->ctt_pan_no) != '' && $cttModel->ctt_pan_doc_id > 0)
			{
				$docPANModel = Document::model()->findByPK($cttModel->ctt_pan_doc_id);
			}
			if (trim($cttModel->ctt_aadhaar_no) != '' && $cttModel->ctt_aadhar_doc_id > 0)
			{
				$docAdharModel = Document::model()->findByPK($cttModel->ctt_aadhar_doc_id);
			}
			if (trim($cttModel->ctt_voter_no) != '' && $cttModel->ctt_voter_doc_id > 0)
			{
				$docVoterModel = Document::model()->findByPK($cttModel->ctt_voter_doc_id);
			}
			if ($cttModel->ctt_police_doc_id > 0)
			{
				$docPoliceVerModel = Document::model()->findByPK($cttModel->ctt_police_doc_id);
			}
		}
		catch (Exception $ex)
		{
			ReturnSet::setException($ex);
			$cttModel->addError('ctt_id', $e->getMessage());
			$errorMsg = $e->getMessage();
		}
//		$cttModel->scenario = "validateRegister";
		$this->render('register', array(
			'cttModel'			 => $cttModel,
			'docVoterModel'		 => $docVoterModel,
			'docAdharModel'		 => $docAdharModel,
			'docLicenseModel'	 => $docLicenseModel,
			'docPANModel'		 => $docPANModel,
			'docPoliceVerModel'	 => $docPoliceVerModel,
			'isDCO'				 => $isDCO,
			'contactData'		 => $contactData,
			'vndPref'			 => $vndPref,
			'vndModel'			 => $vndModel,
			'uploadDone'		 => $uploadDone,
			'formType'			 => $formType,
			'errorMsg'			 => $errorMsg
		));
	}

	public function actionBasicinfo()
	{
		$this->redirect(['register']);
		exit;
		$request = Yii::app()->request;
		$contact = $request->getParam("Contact");
		$isDCO	 = $request->getParam('isDCO');

		$userInfo = \UserInfo::getInstance();
		if ($userInfo->userId == '')
		{
			echo ("Session logged out.");
			Yii::app()->end();
		}
		$contactData = \ContactProfile::getEntitybyUserId($userInfo->userId);

		$cttModel = \Contact::model()->findByPk($contactData['cr_contact_id']);
		if ($contactData['cr_is_vendor'] > 0)
		{
			$vndId		 = $contactData['cr_is_vendor'];
			/** @var \Vendors $vndModel */
			$vndModel	 = \Vendors::model()->findByPk($vndId);
			$isDCO		 = $vndModel->vnd_is_dco;
		}


//		$docFieldArr		 = Document::getFieldByType();
		/** @var Contact $cttModel */
		$this->render('basicInfo', array(
			'cttmodel'	 => $cttModel,
			'isDCO'		 => $isDCO
		));
	}

	public function actionBasicInfomini()
	{
		$request = Yii::app()->request;
		$cttId	 = $request->getParam("cttId", 0);
		$isDCO	 = $request->getParam('isDCO');

		$userInfo = \UserInfo::getInstance();
		if ($userInfo->userId == '')
		{
			echo ("Session logged out.");
			exit;
		}

		$cttModel = \Contact::model()->findByPk($cttId);
		$this->renderPartial('basicInfomini', ['isDCO' => $isDCO, 'cttModel' => $cttModel], false, true);
		Yii::app()->end();
	}

	public function actionValidatebasic()
	{
		$request	 = Yii::app()->request;
		$contact	 = $request->getParam("Contact");
		$isDCO		 = $request->getParam('isDCO');
		$formType	 = $request->getParam('formType');

		$userInfo	 = \UserInfo::getInstance();
		$contactData = \ContactProfile::getEntitybyUserId($userInfo->userId);
		/** @var \Contact $cttModel */
		$cttModel	 = \Contact::model()->findByPk($contactData['cr_contact_id']);
		$cttId		 = $cttModel->ctt_id;
		if ($request->isPostRequest)
		{

			$emlModel	 = Yii::app()->request->getParam("contactEmails");
			$phnModel	 = Yii::app()->request->getParam("contactPhone");

			if ($phnModel && $phnModel['phn_phone_no'])
			{
				$cttModel->contactPhones	 = [];
				$phnModel->phn_is_primary	 = 1;
				$phnModels					 = [$phnModel];
				$cttModel->contactPhones	 = $phnModels;
			}

			$cttModel->scenario		 = "validateRegister";
			$cttModel->attributes	 = $contact;

			if (!$cttModel->validate() && $cttModel->hasErrors())
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					$result = [];
//					$result	 = $cttModel->getErrors();
					foreach ($cttModel->getErrors() as $attribute => $error)
					{
						$result[] = $error;
					}
					echo CJSON::encode(['success' => false, 'errors' => $result]);
					Yii::app()->end();
				}
			}


			$transaction = DBUtil::beginTransaction();
			if ($emlModel && $emlModel['eml_email_address'])
			{
				$cttModel->contactEmails	 = [];
				$emlModel->eml_is_primary	 = 1;
				$emlModels					 = [$emlModel];
				$cttModel->contactEmails	 = $emlModels;

				$returnSet = ContactEmail::model()->addNew($cttModel->ctt_id, $emlModel['eml_email_address'], SocialAuth::Eml_aaocab, $emlModel->eml_is_primary, 1);
				if ($returnSet->isSuccess())
				{
					ContactEmail::setPrimaryEmail($cttModel->ctt_id);
				}
			}
			if ($phnModel && $phnModel['phn_phone_no'])
			{
				$cttModel->contactPhones	 = [];
				$phnModel->phn_is_primary	 = 1;
				$phnModels					 = [$phnModel];
				$cttModel->contactPhones	 = $phnModels;

				$value	 = $phnModel['phn_phone_no'];
				$isPhone = Filter::parsePhoneNumber($value, $code, $number);
				if (!$isPhone)
				{
					throw new Exception("Please enter valid phone number", ReturnSet::ERROR_VALIDATION);
				}
				$primaryPhone	 = ContactPhone::validatePrimary($cttId);
				$returnSet		 = ContactPhone::model()->add($cttId, $number, 1, $code, 1, $primaryPhone);
				if ($returnSet->isSuccess())
				{
					ContactPhone::setPrimaryPhone($cttModel->ctt_id);
				}
			}
			$cttModel->save();

			$returnSet	 = $cttModel->registerDCO($isDCO, 1);
			$dataArr	 = $returnSet->getData();
			$vndId		 = $dataArr['vendor'];
			$userId		 = $userInfo->userId;
			$rowsUpdated = AppTokens::updateEntityByUserId($userId, $vndId);

			DBUtil::commitTransaction($transaction);
			if (Yii::app()->request->isAjaxRequest)
			{
				$url = Yii::app()->createUrl('operator/register', ['formType' => $formType]);
				echo CJSON::encode(['success' => true, 'url' => $url, 'message' => json_encode($dataArr),]);
				Yii::app()->end();
			}
		}
	}

	public function actionDrvlicense()
	{
		$request = Yii::app()->request;
		$contact = $request->getParam("Contact");
		$isDCO	 = $request->getParam('isDCO');

		$userInfo = \UserInfo::getInstance();
		if ($userInfo->userId == '')
		{
			throw Exception("Session logged out");
		}
		$contactData = \ContactProfile::getEntitybyUserId($userInfo->userId);

		$cttModel = \Contact::model()->findByPk($contactData['cr_contact_id']);
		if ($contactData['cr_is_vendor'] > 0)
		{
			$vndId		 = $contactData['cr_is_vendor'];
			/** @var \Vendors $vndModel */
			$vndModel	 = \Vendors::model()->findByPk($vndId);
			$isDCO		 = $vndModel->vnd_is_dco;
		}


//		$docFieldArr		 = Document::getFieldByType();
		/** @var Contact $cttModel */
		$docLicenseModel = new Document();

		if (trim($cttModel->ctt_license_no) != '' && $cttModel->ctt_license_doc_id > 0)
		{
			$docLicenseModel = Document::model()->findByPK($cttModel->ctt_license_doc_id);
		}

		$this->render('licenseDoc', array(
			'cttmodel'			 => $cttModel,
			'docLicenseModel'	 => $docLicenseModel,
			'isDCO'				 => $isDCO
		));
	}

	public function actionLandingpage()
	{
		$rData = Yii::app()->request->getParam("rdata");

		$rdata = str_replace(' ', '+', $rData);

		$jsonObj = self::decryptData($rdata);
		$jwt	 = $jsonObj->jwtoken;

		$res		 = JWTokens::validateAppToken($jwt);
		$authToken	 = $res->token;

		$appModel = AppTokens::model()->getByToken($authToken);
		if (!$appModel)
		{
			throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
		}

		$userId = Users::loginByAppmodel($appModel);
		if ($userId > 0)
		{
			$this->redirect(['register']);
		}
	}

	public static function createInstance($encryptedData = null)
	{
		$obj = new static();
		if ($encryptedData == null)
		{
			goto end;
		}

		$jsonObj = self::decryptData($encryptedData);

		$jsonMapper	 = new JsonMapper();
		/** @var BookFormRequest $obj */
		$obj		 = $jsonMapper->map($jsonObj, $obj);

		end:
		return $obj;
	}

	public static function decryptData($data)
	{
		$jsonData = \Filter::decrypt($data);

		$jsonMapper	 = new \JsonMapper();
		$jsonObj	 = \CJSON::decode($jsonData, false);
		$jsonObj	 = \Filter::removeNull($jsonObj);

		return $jsonObj;
	}

	public function updatePostData()
	{
		$_POST['rdata'] = $this->getEncrptedData();
	}

	public function actionUploaddoc()
	{
		$request	 = Yii::app()->request;
		$contact	 = $request->getParam("Contact");
		$documents	 = $request->getParam('Document', null);
		$isDCO		 = $request->getParam('isDCO');
		$formType	 = $request->getParam('formType');
//		$docType	 = $request->getParam('doc_type');

		$cttId = $contact['ctt_id'];

		$contProfileModel = ContactProfile::model()->findByContactId($cttId);

		if ($contProfileModel->cr_is_vendor > 0)
		{
			$vndId		 = $contProfileModel->cr_is_vendor;
			/** @var \Vendors $vndModel */
			$vndModel	 = \Vendors::model()->findByPk($vndId);
			$isDCO		 = $vndModel->vnd_is_dco;
		}

		$success = Document::uploadDocumentByContact($documents, $contact);
		//check applicable to ready for approve or not.
		if($vndId>0)
		{
			Vendors::modifyReadytoApprove($vndId);
		}
		if (!$success)
		{
			return false;
		}
		$formArr = ['register'];
		if ($formType != null)
		{
			$formArr['formType'] = $formType;
		}

		$this->redirect($formArr);
	}

	public function actionUploadlicense()
	{
		
		$request	 = Yii::app()->request;
		$contact	 = $request->getParam("Contact");
		$documents	 = $request->getParam('Document', null);
		$isDCO		 = $request->getParam('isDCO');
		$formType	 = $request->getParam('formType');

		$cttId = $contact['ctt_id'];
		if ($cttId > 0)
		{
			$cttModel = \Contact::model()->findByPk($cttId);
		}
		else
		{
			$this->redirect(['register']);
		}

		$contProfileModel = ContactProfile::model()->findByContactId($cttId);

		if ($contProfileModel->cr_is_vendor > 0)
		{
			$vndId		 = $contProfileModel->cr_is_vendor;
			/** @var \Vendors $vndModel */
			$vndModel	 = \Vendors::model()->findByPk($vndId);
			$isDCO		 = $vndModel->vnd_is_dco;
		}
		else
		{
			$this->redirect(['register']);
		}
		$errorMsg			 = "";
		$cttLicense			 = ($contact['ctt_license_no'] != '') ? $contact['ctt_license_no'] : $cttModel->ctt_license_no;
		$cttLicenseExpDate	 = ($contact['ctt_license_exp_date'] != '') ? $contact['ctt_license_exp_date'] : $cttModel->ctt_license_exp_date;
		if (!$cttLicense)
		{
			$errorMsg	 = "License No is mandatory";
			$success	 = false;
			goto skipAll;
		}


		foreach ($documents as $key => $document)
		{
			$document['doc_type']	 = $key;
			$prefixDocType			 = $key;
			$success				 = Document::uploadDocumentByContact($document, $contact, $prefixDocType);
		}
		skipAll:
		if (!$success)
		{
			$formType = 'lic';
		}

		if($vndId>0)
		{
			Vendors::modifyReadytoApprove($vndId);
		
		}
		$this->redirect(['register', 'formType' => $formType, 'errorMsg' => $errorMsg]);
	}

	public function actionUploadselfie()
	{
		$request		 = Yii::app()->request;
		$contact		 = $request->getParam("Contact");
		$cttId			 = $contact['ctt_id'];
		$formType		 = $request->getParam('formType');
		/** @var \Contact $contactModel */
		$contactModel	 = \Contact::model()->findByPk($cttId);

		$profileImage = CUploadedFile::getInstance($contactModel, "ctt_profile_path");
		if ($profileImage != "" && !Filter::checkImage($profileImage->getType()))
		{
			$errorMsg	 = "File/image type not supported for selfie";
			$formType	 = 'selfie';
			goto skipAll;
		}
		$transaction = DBUtil::beginTransaction();

		if ($profileImage != "")
		{
			$maxWidth						 = 800;
//			$onlyImage						 = true;
			$path							 = Document::upload($cttId, "profile", $profileImage, $maxWidth);
			$contactModel->ctt_profile_path	 = $path;
			$contactModel->save();
		}

		DBUtil::commitTransaction($transaction);
		skipAll:
		$this->redirect(['register', 'formType' => $formType, 'errorMsg' => $errorMsg]);
	}

	public function actionSelfiewithid()
	{
		$request = Yii::app()->request;
		$contact = $request->getParam("Contact");
		$isDCO	 = $request->getParam('isDCO');

		$userInfo = \UserInfo::getInstance();
		if ($userInfo->userId == '')
		{
			throw Exception("Session logged out");
		}
		$contactData = \ContactProfile::getEntitybyUserId($userInfo->userId);

		$cttModel = \Contact::model()->findByPk($contactData['cr_contact_id']);

		$this->render('selfiedoc', array(
			'cttmodel'	 => $cttModel,
			'isDCO'		 => $isDCO
		));
	}

	public function actionAadhar()
	{
		$request = Yii::app()->request;
		$contact = $request->getParam("Contact");
		$isDCO	 = $request->getParam('isDCO');

		$userInfo = \UserInfo::getInstance();
		if ($userInfo->userId == '')
		{
			throw Exception("Session logged out");
		}
		$contactData = \ContactProfile::getEntitybyUserId($userInfo->userId);

		$cttModel = \Contact::model()->findByPk($contactData['cr_contact_id']);
		if ($contactData['cr_is_vendor'] > 0)
		{
			$vndId		 = $contactData['cr_is_vendor'];
			/** @var \Vendors $vndModel */
			$vndModel	 = \Vendors::model()->findByPk($vndId);
			$isDCO		 = $vndModel->vnd_is_dco;
		}


		$docAdharModel = new Document();

		if (trim($cttModel->ctt_aadhaar_no) != '' && $cttModel->ctt_aadhar_doc_id > 0)
		{
			$docAdharModel = Document::model()->findByPK($cttModel->ctt_aadhar_doc_id);
		}


		$this->render('aadharDoc', array(
			'cttmodel'		 => $cttModel,
			'docAdharModel'	 => $docAdharModel,
			'isDCO'			 => $isDCO
		));
	}

	public function actionPoliceveridoc()
	{
		$request = Yii::app()->request;
		$contact = $request->getParam("Contact");
		$isDCO	 = $request->getParam('isDCO');

		$userInfo = \UserInfo::getInstance();
		if ($userInfo->userId == '')
		{
			throw Exception("Session logged out");
		}
		$contactData = \ContactProfile::getEntitybyUserId($userInfo->userId);

		$cttModel = \Contact::model()->findByPk($contactData['cr_contact_id']);
		if ($contactData['cr_is_vendor'] > 0)
		{
			$vndId		 = $contactData['cr_is_vendor'];
			/** @var \Vendors $vndModel */
			$vndModel	 = \Vendors::model()->findByPk($vndId);
			$isDCO		 = $vndModel->vnd_is_dco;
		}


		$docPoliceVerModel = new Document();

		if ($cttModel->ctt_police_doc_id > 0)
		{
			$docPoliceVerModel = Document::model()->findByPK($cttModel->ctt_police_doc_id);
		}
		$this->render('policeVeriDoc', array(
			'cttmodel'			 => $cttModel,
			'docPoliceVerModel'	 => $docPoliceVerModel,
			'isDCO'				 => $isDCO
		));
	}

	public function actionOperatortype()
	{
		$request = Yii::app()->request;
		$isDCO	 = $request->getParam('isDCO');

		$userInfo = \UserInfo::getInstance();
		if ($userInfo->userId == '')
		{
			throw Exception("Session logged out");
		}
		$contactData = \ContactProfile::getEntitybyUserId($userInfo->userId);

		if ($contactData['cr_is_vendor'] > 0)
		{
			$vndId		 = $contactData['cr_is_vendor'];
			/** @var \Vendors $vndModel */
			$vndModel	 = \Vendors::model()->findByPk($vndId);
			$isDCO		 = $vndModel->vnd_is_dco;
		}

		$this->render('operatorType', array(
			'isDCO' => $isDCO
		));
	}

	public function actionUpdateServices()
	{
		$request	 = Yii::app()->request;
		$contact	 = $request->getParam("Contact");
		$vndPref	 = $request->getParam("VendorPref");
		$formType	 = $request->getParam('formType');
		$cttId		 = $contact['ctt_id'];
		/** @var \Contact $contactModel */
		$userInfo	 = \UserInfo::getInstance();
		if ($userInfo->userId == '')
		{
			throw Exception("Session logged out");
		}
		$contactData = \ContactProfile::getEntitybyUserId($userInfo->userId);

		if ($contactData['cr_is_vendor'] > 0)
		{
			$vndId			 = $contactData['cr_is_vendor'];
			/** @var \Vendors $vndModel */
			$vndModel		 = \Vendors::model()->findByPk($vndId);
			$vndPrefModel	 = $vndModel->vendorPrefs();
		}

		if ($request->isPostRequest && isset($vndPref))
		{
			$vndPrefModel->vnp_daily_rental	 = isset($vndPref['vnp_daily_rental']) ? 1 : 0;
			$vndPrefModel->vnp_airport		 = isset($vndPref['vnp_daily_rental']) ? 1 : 0;

			$vndPrefModel->vnp_oneway		 = isset($vndPref['vnp_oneway']) ? 1 : 0;
			$vndPrefModel->vnp_round_trip	 = isset($vndPref['vnp_oneway']) ? 1 : 0;
			$vndPrefModel->vnp_multi_trip	 = isset($vndPref['vnp_oneway']) ? 1 : 0;

			$vndPrefModel->save();

			if (Yii::app()->request->isAjaxRequest)
			{
				$url = Yii::app()->createUrl('operator/register');
				echo CJSON::encode(['success' => true, 'url' => $url]);
				Yii::app()->end();
			}
		}

		$this->redirect(['register', 'formType' => $formType]);
	}

	public function getVehicleId($strictCheck = true)
	{
		$vndId = $this->getVendorId($strictCheck);
		if (!$vndId)
		{
			$this->redirect('/vendor/attach');
		}
		$vhcId = VendorVehicle::getLinkedVehicles($vndId);
		return $vhcId;
	}

	public function getVendorId($strictCheck = true)
	{
		$userId = UserInfo::getUserId();
		if (empty($userId) || !$userId)
		{
			$this->redirect('/vendor/attach');
			//	throw new Exception("Error in login", ReturnSet::ERROR_FAILED);
		}
		$contactData = ContactProfile::getEntitybyUserId($userId);
		if ((empty($contactData) || !$contactData['cr_is_vendor']) && $strictCheck)
		{
			$this->redirect('/vendor/attach');
			//	throw new Exception("Unable to link user", ReturnSet::ERROR_FAILED);
		}
		$vndId = $contactData['cr_is_vendor'];
		return $vndId;
	}

	public function actionAttach()
	{

		$userInfo = \UserInfo::getInstance();
		if ($userInfo->userId == '')
		{
			echo "Session logged out";
		}
		else
		{
			echo "logged in";
		}

		exit;
		$requestInstance = Yii::app()->request;

		//This function is used for updating and inserting new mappings
	}

}
