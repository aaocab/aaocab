<?php

class ContactController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';
	public $email_receipient;
	public $pageTitle;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
				'actions'	 => array('add', 'list', 'searchList', 'alternateEmail', 'alternatePhone', 'showcontact', 'resendVerificationLink', 'view', 'merge', 'mergecontact', 'duplicatecontact', 'checkphone', 'mergeduplicatecontact', 'form', 'phonevalidation', 'emailvalidation', 'checkEmail', 'CheckProfile', 'mergedetails', 'MergeList', "ApproveDoc", "UpdateContact", "MarkResolve", 'ResendEmailVerification', 'RemovePhone', 'RemoveEmail', 'existContact', 'mergecode'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index',),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array('admin'),
				'users'		 => array('admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * This function is used for contact details add and modify
	 * @throws Exception
	 */
	public function actionForm()
	{
		$this->pageTitle = "Add Contact";
		$ftype			 = 'Add';
		$request		 = Yii::app()->request;
		$cttId			 = empty($request->getParam('ctt_id')) ? 0 : $request->getParam("ctt_id");
		$type			 = trim($request->getParam('type', 0));
		$uvrid			 = $request->getParam('uvrid', NULL);
		$errors			 = [];
		/* @var $model Contact */
		$model			 = new Contact();
		if (!empty($cttId))
		{
			$this->pageTitle = "Modify Contact";
			$ftype			 = 'Modify';
			$model			 = $model->findByPk($cttId);
			$oldData		 = $model->attributes;
		}

		if ($uvrid != NULL)
		{
			$model = UnregVendorRequest::findByUnRegID($uvrid, $model);
		}

		if (!$model)
		{
			throw new Exception('Contact not found');
		}

		$returnSet = new ReturnSet();
		if (isset($_REQUEST['Contact']))
		{
			$model->addType = $type;

//			Commented out the code. Default scenario added
//			if ($model->addType == 0)
//			{
//				$model->scenario = 'contactInsUp';
//			}
			$model->attributes	 = $request->getParam('Contact');
			$newData			 = $model->attributes;
			///////////// $newData['strTags']
			$errors				 = [];
			$result				 = CActiveForm::validate($model);
			if ($result == '[]')
			{
				$model->ctt_tags		 = implode(',', $request->getParam('Contact')['strTags']);
				$model->contactEmails	 = $model->convertToEmailObjects($request->getParam('ContactEmail'));
				$model->contactPhones	 = $model->convertToPhoneObjects($request->getParam('ContactPhone'));
				$returnSet				 = $model->add(0, $oldData, $newData);
				//Model function to set bankdetail modify date update
				$model->checkBankDetailsUpdate($oldData, $newData);
				if($model->ctt_id > 0 && $model->ctt_tags!='')
				{
					Tags::updateBookingTags($model->ctt_id, $model->ctt_tags);
				}
			}
			else
			{
				foreach (CJSON::decode($result) as $value)
				{
					array_push($errors, $value);
				}
				$returnSet->setStatus(false);
			}

			if (Yii::app()->request->isAjaxRequest)
			{
				echo CJSON::encode(array('success' => $returnSet->getStatus(), 'message' => $errors, "ContacId" => $model->ctt_id, "ContactName" => $model->getName(), "ContactEmail" => $model->contactEmails[0]->eml_email_address, "ContactPhone" => $model->contactPhones[0]->phn_phone_no, "ContactLicense" => $model->ctt_license_no));

				Yii::app()->end();
			}

			if ($returnSet->getStatus())
			{

				$this->redirect(array('document/view', 'ctt_id' => $model->ctt_id));
			}
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('form', array('model' => $model, 'returns' => $returnSet, 'uvrid' => $uvrid, 'type' => $type), false, $outputJs);
	}

	public function actionList($qry = [])
	{
		$this->pageTitle = "Contact";
		$request		 = Yii::app()->request;
		$cttUsrType		 = $request->getParam('ctype');
		$vndType		 = $request->getParam('vndtype');
		$pageSize		 = Yii::app()->params['listPerPage'];
		$model			 = new Contact('search');
		if (isset($_REQUEST['Contact']))
		{
			$model->search		 = trim($_REQUEST['Contact']['search']);
			$model->strTags		 = $_REQUEST['Contact']['strTags'];
			$model->attributes	 = $request->getParam('Contact');
		}
		$dataProvider	 = $model->fetchList($cttUsrType);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('list', array('model' => $model, 'dataProvider' => $dataProvider, 'type' => $cttUsrType, 'vndtype' => $vndType, 'qry' => $qry), null, $outputJs);
	}

	public function actionAlternateEmail()
	{
		$this->pageTitle = "Alternate Email";
		$request		 = Yii::app()->request;
		$cttId			 = $request->getParam('ctt_id');
		$model			 = new ContactEmail();
		if ($_SERVER['REQUEST_METHOD'] == "POST" && $request->getPost('email_address') == null)
		{
			echo CJSON::encode(array('status' => 'failed', 'message' => array("please provide your email id")));
			Yii::app()->end();
		}
		else if ($request->getPost('email_address'))
		{
			$email_address				 = $request->getPost('email_address');
			$data						 = array();
			$model->eml_email_address	 = $email_address;
			$error						 = CActiveForm::validate($model);
			if ($error != '[]')
			{
				$data		 = array();
				$emailError	 = $model->getErrors();
				for ($i = 0; $i < count($emailError['eml_email_address']); $i++)
				{
					$data[$i] = $emailError['eml_email_address'][$i];
				}
				echo CJSON::encode(array('status' => 'failed', 'message' => $data));
			}
			else
			{
				$emailNewModel						 = new ContactEmail();
				$emailNewModel->eml_contact_id		 = $cttId;
				$emailNewModel->eml_is_primary		 = 0;
				$emailNewModel->eml_email_address	 = $email_address;
				$emailNewModel->save();
				echo CJSON::encode(array('status' => 'success', 'message' => "Alternative emailId addded successfully"));
			}
			Yii::app()->end();
		}
		$this->renderPartial('alternateemail', array('model' => $model, 'cttId' => $cttId), false, true);
	}

	public function actionAlternatePhone()
	{
		$request		 = Yii::app()->request;
		$this->pageTitle = "Alternate Phone";
		$cttId			 = $request->getParam('ctt_id');
		$model			 = new ContactPhone();
		if ($_SERVER['REQUEST_METHOD'] == "POST" && $request->getPost('phone_address') == null)
		{
			echo CJSON::encode(array('status' => 'failed', 'message' => array("please provide your  phone number")));
			Yii::app()->end();
		}
		else if ($request->getPost('phone_address'))
		{
			$phone_address		 = $request->getPost('phone_address');
			$phone_country		 = $request->getPost('phone_country');
			$model->phn_phone_no = $phone_address;
			$error				 = CActiveForm::validate($model);
			if ($error != '[]')
			{
				$data		 = array();
				$phoneError	 = $model->getErrors();
				for ($i = 0; $i < count($phoneError['phn_phone_no']); $i++)
				{
					$data[$i] = $phoneError['phn_phone_no'][$i];
				}
				echo CJSON::encode(array('status' => 'failed', 'message' => $data));
			}
			else
			{
				$phoneNewModel							 = new ContactPhone();
				$phoneNewModel->phn_contact_id			 = $cttId;
				$phoneNewModel->phn_is_primary			 = 0;
				$phoneNewModel->phn_phone_no			 = $phone_address;
				$phoneNewModel->phn_phone_country_code	 = $phone_country;
				$phoneNewModel->save();
				echo CJSON::encode(array('status' => 'success', 'message' => "Alternative phone  addded successfully"));
			}
			Yii::app()->end();
		}
		$this->renderPartial('alternatephone', array('model' => $model, 'cttId' => $cttId), false, true);
	}

	public function actionShowcontact()
	{
		$request		 = Yii::app()->request;
		$vndid			 = $request->getParam('id');
		$model			 = ContactPhone::model()->getContactNoById($vndid);
		$this->pageTitle = $model['vnd_name'];
		$str			 = trim($model['phone_no']);
		echo "Contact :  	<b style ='word-wrap: break-word;'>" . rtrim($str, ',') . "</b>";
		Yii::app()->end();
	}

	public function actionView()
	{
		$request		 = Yii::app()->request;
		$pagetitle		 = "View Contact";
		$this->pageTitle = $pagetitle;
		$cttid			 = trim($request->getParam('ctt_id'), "");
		$view			 = trim($request->getParam('view', 'view'));
		if ($cttid != '')
		{
			$model					 = Contact::model()->findByPk($cttid);
			$model->ctt_voter_no	 = trim(trim(trim($model->ctt_voter_no), 't'), 'n');
			$model->ctt_aadhaar_no	 = trim(trim(trim($model->ctt_aadhaar_no), 't'), 'n');
			$model->ctt_pan_no		 = trim(trim(trim($model->ctt_pan_no), 't'), 'n');
			$model->ctt_license_no	 = trim(trim(trim($model->ctt_license_no), 't'), 'n');
			$emailModel				 = ContactEmail::model()->findByContactID($cttid);
			$phoneModel				 = ContactPhone::model()->findByContactID($cttid);
			$docByContactId			 = Document::model()->getAllDocsbyContact($cttid);
			$userIdArr				 = Contact::model()->getUserIdByContactId($cttid);
			$isMerged				 = Contact::isContactMerged($cttid);
		}
		else
		{
			throw new CHttpException(404, 'Contact not found');
		}
		$outputJs	 = $request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('view', array('model' => $model, "isMerged" => $isMerged, 'docpath' => $docByContactId, 'emailModel' => $emailModel, 'phoneModel' => $phoneModel, 'UserIdArr' => $userIdArr, 'isAjax' => $outputJs), false, $outputJs);
	}

	public function actionMergeDetails()
	{
		$pagetitle		 = "Merge Details";
		$this->pageTitle = $pagetitle;
		$request		 = Yii::app()->request;

		$model	 = new Contact();
		$type	 = "Contact";
		if (isset($_REQUEST['Contact']))
		{
			$arr				 = array_filter($request->getParam('Contact'));
			$model->attributes	 = $arr;
		}

		$cttId			 = $request->getParam("ctt_id");
		$dataProvider	 = Contact::getDuplicateDetails($arr, $cttId);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('mergedetails', array('model' => $model, 'dataProvider' => $dataProvider), null, $outputJs);
	}

	public function actionMerge()
	{
		$request		 = Yii::app()->request;
		$pagetitle		 = "Merger Contact";
		$this->pageTitle = $pagetitle;
		$cttid			 = $request->getParam('ctt_id');
		$model			 = new Contact();
		$active			 = 11;
		if (isset($_REQUEST['Contact']))
		{
			$active					 = 1;
			$arr					 = array_filter($request->getParam('Contact'));
			$model->attributes		 = $arr;
			$model->phone_no		 = $arr['phone_no'];
			$model->name			 = $arr['name'];
			$model->email_address	 = $arr['email_address'];
		}
		$dataProvider = $model->getRelatedContact($cttid, $arr, $active);
		$this->renderPartial('merge', array('model' => $model, 'dataProvider' => $dataProvider, 'cttid' => $cttid, "active" => $active), false, true);
	}

	public function actionMergeContact()
	{
		$this->pageTitle = "Merge Contact";
		$request		 = Yii::app()->request;
		$cttid			 = $request->getParam('ctt_id');
		$mgrcttid		 = $request->getParam('mgrctt_id');
		$model			 = new Contact();
		if ($cttid != '')
		{
			$model	 = $model->findByPk($cttid);
			$oldData = $model->attributes;
		}
		if (!$model)
		{
			throw new CHttpException(404, "Conact not found");
		}
		$model->scenario = 'contactInsUp';
		if ($mgrcttid == NULL)
		{
			Yii::app()->user->setFlash('notice', "Please select at least 1 contact for merge");
			$this->redirect(array('contact/list'));
		}
		try
		{
			$modelMerge = Contact::model()->getAllContactByIds($mgrcttid);
			if (!$modelMerge)
			{
				Yii::app()->user->setFlash('notice', "No duplicate found.");
				$this->redirect(array('contact/list'));
			}
		}
		catch (Exception $ex)
		{
			Yii::app()->user->setFlash('notice', "Due to some problem with your contact.Contact merge cannot be completed");
			$this->redirect(array('contact/list'));
		}
		$returnSet	 = new ReturnSet();
		$mgrArr		 = explode(",", $mgrcttid);
		if (in_array($cttid, $mgrArr))
		{
			Yii::app()->user->setFlash('notice', "You cannot merged with same contact");
			$this->redirect(array('contact/list'));
		}
		if (isset($_REQUEST['Contact']))
		{
			if ($request->getParam('Contact')['new_ctt_id'] != "")
			{
				$mgrcttid	 = $cttid;
				$cttid		 = $request->getParam('Contact')['new_ctt_id'];
			}
			$model->addType			 = -1;
			$model->attributes		 = $request->getParam('Contact');
			$model->contactEmails	 = $model->convertToEmailObjects($request->getParam('ContactEmail'));
			$model->contactPhones	 = $model->convertToPhoneObjects($request->getParam('ContactPhone'));
			$model->ctt_user_type	 = (int) $_REQUEST['Contact']['ctt_user_type'];
			$model->ctt_account_type = (int) $_REQUEST['Contact']['ctt_account_type'];
			$returnSet				 = $model->merge($cttid, $mgrcttid, $oldData);
			if ($returnSet->getStatus())
			{
				Yii::app()->user->setFlash('success', "Contact merged done successfully");
				$this->redirect(array('contact/list'));
			}
		}
		$outputJs	 = $request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('addmerge', array('model' => $model, 'modelMerge' => $modelMerge, 'returns' => $returnSet), false, $outputJs);
	}

	public function actionduplicatecontact()
	{
		$pagetitle		 = "Duplicate Contact";
		$this->pageTitle = $pagetitle;
		$request		 = Yii::app()->request;
		$model			 = new Contact();
		$cttid			 = NULL;
		$vnd_id			 = "";
		$type			 = "contact";
		if (isset($_REQUEST['Contact']))
		{
			$arr		 = array_filter($request->getParam('Contact'));
			$contactName = $arr['contactperson'];
			if ($contactName != '')
			{
				$devideName				 = explode(" ", $contactName);
				$arr['ctt_first_name']	 = $devideName[0];
				$arr['ctt_last_name']	 = $devideName[1];
			}
			$model->attributes		 = $arr;
			$model->ctt_aadhaar_no	 = $arr['ctt_aadhaar_no'];
			$model->phone_no		 = $arr['phone_no'];
			$model->email_address	 = $arr['email_address'];
			$model->ctt_pan_no		 = $arr['ctt_pan_no'];
			$model->ctt_voter_no	 = $arr['ctt_voter_no'];
			$model->ctt_license_no	 = $arr['ctt_license_no'];
		}
		$dataProvider	 = $model->getDuplicateContact($arr, $cttid, $type, $vnd_id);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('duplicatecontact', array('model' => $model, 'dataProvider' => $dataProvider), null, $outputJs);
	}

	public function actionmergeduplicatecontact()
	{
		$this->pageTitle	 = "Merge Contact";
		$request			 = Yii::app()->request;
		$cttid				 = $request->getParam('ctt_id');
		$vnd_id				 = "";
		$type				 = "contact";
		$phone_no			 = $request->getParam('phone_no');
		$ctt_aadhaar_no		 = $request->getParam('ctt_aadhaar_no');
		$ctt_pan_no			 = $request->getParam('ctt_pan_no');
		$ctt_voter_no		 = $request->getParam('ctt_voter_no');
		$ctt_license_no		 = $request->getParam('ctt_license_no');
		$eml_email_address	 = $request->getParam('email_address');
		$model				 = Contact::model()->findByPk($cttid);
		$arr				 = [];
		if (isset($_REQUEST['Contact']))
		{
			$arr					 = array_filter($request->getParam('Contact'));
			$model->attributes		 = $arr;
			$model->phone_no		 = $arr['phone_no'];
			$model->email_address	 = $arr['email_address'];
			$model->ctt_aadhaar_no	 = $arr['ctt_aadhaar_no'];
			$model->ctt_pan_no		 = $arr['ctt_pan_no'];
			$model->ctt_voter_no	 = $arr['ctt_voter_no'];
			$model->ctt_license_no	 = $arr['ctt_license_no'];
		}
		else
		{
			$arr['phone_no']		 = $phone_no == NULL ? "" : $phone_no;
			$arr['email_address']	 = $eml_email_address == NULL ? "" : $eml_email_address;
			$arr['ctt_aadhaar_no']	 = $ctt_aadhaar_no == NULL ? "" : $ctt_aadhaar_no;
			$arr['ctt_pan_no']		 = $ctt_pan_no == NULL ? "" : $ctt_pan_no;
			$arr['ctt_voter_no']	 = $ctt_voter_no == NULL ? "" : $ctt_voter_no;
			$arr['ctt_license_no']	 = $ctt_license_no == NULL ? "" : $ctt_license_no;
			$model->email_address	 = $arr['email_address'];
			$model->phone_no		 = $arr['phone_no'];
			$model->ctt_aadhaar_no	 = $arr['ctt_aadhaar_no'];
			$model->ctt_pan_no		 = $arr['ctt_pan_no'];
			$model->ctt_voter_no	 = $arr['ctt_voter_no'];
			$model->ctt_license_no	 = $arr['ctt_license_no'];
		}
		$dataProvider	 = $model->getDuplicateContact($arr, $cttid, $type, $vnd_id);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('duplicatecontact', array('model' => $model, 'dataProvider' => $dataProvider, 'cttid' => $cttid), null, $outputJs);
	}

	public function getError($returns)
	{
		if ($returns->hasErrors())
		{

			if (count($returns->getError('contactEmails')))
			{
				$json = json_decode($returns->getError('contactEmails')[0], true);
				return $json['ContactEmail_eml_email_address'][0];
			}
			if (count($returns->getError('contactPhones')))
			{
				$json = json_decode($returns->getError('contactPhones')[0], true);
				return $json['ContactPhone_phn_phone_no'][0];
			}
			if (count($returns->getError('phn_phone_no')))
			{
				$json = json_decode($returns->getError('phn_phone_no')[0], true);
				return $json['phn_phone_no'][0];
			}
			if (count($returns->getError('eml_email_address')))
			{
				$json = json_decode($returns->getError('eml_email_address')[0], true);
				return $json['eml_email_address'][0];
			}
			if (count($returns->getError('ctt_city')))
			{
				return $returns->getError('ctt_city')[0];
			}
			if (count($returns->getError('ctt_id')))
			{
				return $returns->getError('ctt_id')[0];
			}
		}
	}

	public function actionPhoneValidation()
	{
		$request			 = Yii::app()->request;
		$phone_address		 = $request->getPost('phone_address');
		$phone_country		 = $request->getPost('phone_country');
		$model				 = new ContactPhone();
		$model->phn_phone_no = $phone_address;
		$error				 = CActiveForm::validate($model);
		$returnSet			 = new ReturnSet();
		$returnSet->setStatus(true);
		if ($error != '[]')
		{
			$returnSet->setStatus(false);
			$returnSet->setErrors($model->getErrors(), 1);
		}
		echo CJSON::encode(array('status' => $returnSet->getStatus(), 'message' => $returnSet->getStatus() ? "" : $returnSet->getError('phn_phone_no')));
		Yii::app()->end();
	}

	public function actionCheckEmail()
	{
		$returnSet = new ReturnSet();
		try
		{
			$emailArray = Yii::app()->request->getParam('email_address');

			if (!is_array($emailArray))
			{
				throw new Exception("Array required", ReturnSet::ERROR_INVALID_DATA);
			}

			$arrToString				 = implode("', '", $emailArray);
			$model						 = new ContactEmail();
			$model->eml_email_address	 = implode("', '", $emailArray);
			$returnSet					 = ContactEmail::findEmail($arrToString, 1, 1, 1);
			$error						 = CActiveForm::validate($model);
			if ($error != '[]' && $returnSet->getStatus() == false)
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors($model->getErrors(), 1);
			}
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnSet->setException($ex);
		}

		echo CJSON::encode($returnSet);
		Yii::app()->end();
	}

	public function actionCheckPhone()
	{
		$returnSet = new ReturnSet();
		try
		{
			$phone_address		 = Yii::app()->request->getParam('phone_address');
			//  $email_address            = $request->getPost('email_address');
			$model				 = new ContactPhone();
			$model->phn_phone_no = $phone_address;
			$returnSet			 = ContactPhone::findPhone($phone_address, 1, 1, 1);
			$error				 = CActiveForm::validate($model);
			if ($error != '[]' && $returnSet->getStatus() == false)
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors($model->getErrors(), 1);
			}
		}
		catch (Exception $ex)
		{
			Logger::error($ex->getMessage());
			$returnSet->setException($ex);
		}

		echo CJSON::encode($returnSet);
		Yii::app()->end();
	}

	public function actionCheckProfile()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$cttId		 = Yii::app()->request->getParam('cttid');
			$phone		 = Yii::app()->request->getParam('phone');
			$emailId	 = Yii::app()->request->getParam('email');
			$response	 = Contact::model()->verifyContact($emailId, $phone, 1);
			echo json_encode($response);
			Yii::app()->end();
		}
	}

	/**
	 * System merge flag resolution
	 */
	public function actionMergeList()
	{
		$pagetitle		 = "Merge List";
		$this->pageTitle = $pagetitle;
		$request		 = Yii::app()->request;
		$model			 = new ContactMerged();
		$type			 = "ContactMerged";
		if ($request->getParam('ContactMerged'))
		{
			$arr				 = array_filter($request->getParam('ContactMerged'));
			$model->attributes	 = $arr;
		}
		$dataProvider = ContactMerged::getList($arr);
		$this->renderAuto('mergelist', array('model' => $model, 'dataProvider' => $dataProvider), null);
	}

	/**
	 * Merge mismatch resolution
	 */
	public function actionMergeMismatch()
	{
		$pagetitle		 = "Merge Mis-match List";
		$this->pageTitle = $pagetitle;
		$request		 = Yii::app()->request;
		$model			 = new ContactMergeRemarks();
		$type			 = "ContactMergedRemarks";
		if ($request->getParam('ContactMergedRemarks'))
		{
			$arr				 = array_filter($request->getParam('ContactMergedRemarks'));
			$model->attributes	 = $arr;
		}
		$dataProvider = $model::getMisMatchList($arr);
		$this->renderAuto('mergemismatch', array('model' => $model, 'dataProvider' => $dataProvider), null);
	}

	/**
	 * Duplicate contact Merge
	 */
	public function actionApproveDoc()
	{
		$this->pageTitle = "Contact to approve";
		$contactId		 = Yii::app()->getRequest()->getParam('ctt_id');
		if ($contactId)
		{
			$dataProvider = Document::getReviewDocs($contactId);
			$this->renderAuto("approvedata", array("dataProvider" => $dataProvider), false);
		}

		//Updates primary doc data
		if (yii::app()->request->isAjaxRequest)
		{
			$request	 = yii::app()->request->getPost('contact');
			$response	 = Contact::updateData($request);
			echo $response->getStatus();
			Yii::app()->end();
		}
	}

	public function actionUpdateContact()
	{
		if (yii::app()->request->isAjaxRequest)
		{
			$returnSet	 = new ReturnSet();
			$documentId	 = Yii::app()->getRequest()->getParam('docid');
			$cttId		 = Yii::app()->getRequest()->getParam('primaryCttId');
			$dCttId		 = Yii::app()->getRequest()->getParam('duplicateCttId');
			$docModel	 = Document::model()->findByPk($documentId);
			$conModel	 = Contact::model()->findByPk($cttId);
			$status		 = Yii::app()->getRequest()->getParam('status');
			$docType	 = Yii::app()->getRequest()->getParam('docType');
			$docResponse = Document::isApproved($docModel, $docType, $status);

			if ($docResponse->getStatus() && $status == "0")
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Document rejected");
			}

			if ($status == "1")
			{
				$response = Contact::copyDocDetails($documentId, $docType, $cttId, $dCttId, $status);
				if ($response->getStatus())
				{
					$returnSet->setStatus(true);
					$returnSet->setMessage("Approved and moved to primary contact");
				}
			}

			echo json_encode($returnSet);
			Yii::app()->end();
		}
	}

	public function actionMarkResolve()
	{
		if (yii::app()->request->isAjaxRequest)
		{
			$returnSet	 = new ReturnSet();
			$cttId		 = Yii::app()->getRequest()->getParam('primaryCttId');

			$response = ContactMerged::updateData($cttId);
			if ($response)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage("Contact marked as resolved");
			}
		}

		echo json_encode($returnSet);
		Yii::app()->end();
	}

	public function actionResendVerificationLink()
	{
		if (yii::app()->request->isAjaxRequest)
		{
			$phoneNo	 = Yii::app()->request->getParam('phone'); //Yii::app()->getRequest()->getParam('phone');
			$type		 = Yii::app()->request->getParam('userType');
			$cttId		 = Yii::app()->request->getParam('contact'); //Yii::app()->getRequest()->getParam('contact');
			$userType	 = ($type == 3) ? UserInfo::TYPE_VENDOR : UserInfo::TYPE_DRIVER;
			$response	 = ContactPhone::resendVerificationLink($phoneNo, $cttId, $userType);
		}
		echo CJSON::encode($response->getMessage());
		Yii::app()->end();
	}

	public function actionResendEmailVerification()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$email			 = Yii::app()->request->getParam('email');
			$cttId			 = Yii::app()->request->getParam('contact');
			$emailWrapper	 = new emailWrapper();
			$response		 = $emailWrapper->sendVerificationLink($email, $cttId, null, 0, 0, Contact::MODIFY_CON_TEMPLATE, 0, 0, false);
			if ($response)
			{
				$msg = "Send link successfully";
			}
		}
		echo CJSON::encode($msg);
		Yii::app()->end();
	}

	public function actionRemovePhone()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$phone		 = Yii::app()->request->getParam('phone');
			$cttId		 = Yii::app()->request->getParam('contact');
			$response	 = ContactPhone::model()->removePhoneByPhone($phone, $cttId);
			if ($response)
			{
				$msg = "Phone no. removed successfully";
			}
		}
		echo CJSON::encode($msg);
		Yii::app()->end();
	}

	public function actionRemoveEmail()
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$email		 = Yii::app()->request->getParam('email');
			$cttId		 = Yii::app()->request->getParam('contact');
			$response	 = ContactEmail::model()->removeEmailByEmail($email, $cttId);
			if ($response)
			{
				$msg = "Email removed successfully";
			}
		}
		echo CJSON::encode($msg);
		Yii::app()->end();
	}

	public function actionSearchList_OLD()
	{
		$this->pageTitle = "Search Contact List";
		$request		 = Yii::app()->request;
		$model			 = new Contact('search');
		$data			 = Yii::app()->request->getParam('Contact');
		if ($data > 0)
		{
			$model->search		 = trim($data['search']);
			$model->searchtype	 = trim($data['searchtype']);
			$model->attributes	 = $request->getParam('Contact');
			$dataProvider		 = $model->fetchSearchList();
			if (count($dataProvider->data) > 0)
			{
				$dataProvider = $model->fetchSearchList($dataProvider->data);
			}
			$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
			$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		}

		$outputJs	 = $request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('searchlist', array('model' => $model, 'dataProvider' => $dataProvider, 'qry' => $qry), null, $outputJs);
	}

	public function actionSearchList()
	{
		$this->pageTitle = "Search Contact List";
		$request		 = Yii::app()->request;
		$model			 = new Contact();
		$data			 = Yii::app()->request->getParam('Contact');
		if ($data > 0)
		{
			$model->search		 = trim($data['search']);
			$model->searchtype	 = trim($data['searchtype']);
			$model->attributes	 = $request->getParam('Contact');

			$dataProvider = $model->searchList();
		}

		$this->render('searchlist', array('model' => $model, 'dataProvider' => $dataProvider, 'qry' => $qry), null, false);
	}

	public function actionMergecode()
	{
		$ctt_id		 = Yii::app()->request->getParam('ctt_id');
		$ref_code	 = Yii::app()->request->getParam('ctt_ref_code');

		$success = false;
		$model	 = Contact::model()->findByPk($ctt_id);

		if (isset($_POST['ctt_id']) && $_POST['ctt_id'] == $model->ctt_id)
		{
			if (isset($_POST['ctt_ref_code']) && trim($ref_code) != '')
			{
				$model->ctt_ref_code = (int) $ref_code;
				if ($model->save())
				{
					$userInfo	 = UserInfo::getInstance();
					$desc		 = "Contact Merged, Merged this contact ID: {$ctt_id}, with Primary ID: {$ref_code}";
					$event		 = ContactLog::CONTACT_MERGE;
					ContactLog::model()->createLog($ctt_id, $desc, $event, $userInfo);

					$desc	 = "Contact Merged, Primary Contact ID: {$ref_code}, Merged Contact ID: {$ctt_id}";
					$event	 = ContactLog::CONTACT_MERGE;
					ContactLog::model()->createLog($ref_code, $desc, $event, $userInfo);

					$success = true;
				}
				else
				{
					$result			 = [];
					$result['error'] = 'Some Error occured';
				}
				$result['success'] = $success;
			}
			else
			{
				$result				 = [];
				$result['error']	 = 'Primary Contact ID is blank';
				$result['success']	 = $success;
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				echo json_encode($result);
				Yii::app()->end();
			}
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('mergecode', array('model' => $model), FALSE, $outputJs);
	}

	public function actionExistContact()
	{
		$success		 = false;
		$msg			 = "";
		$type			 = Yii::app()->request->getParam('type');
		$bookingID		 = Yii::app()->request->getParam('bookingId');
		$bookingModel	 = Booking::model()->findByPk($bookingID);
		if ($type == 1)
		{
			$entityId	 = $bookingModel->bkgUserInfo->bkg_user_id;
			$entityType	 = UserInfo::TYPE_CONSUMER;
			$msg		 = "Customer";
		}
		else if ($type == 2)
		{
			$vndId		 = $bookingModel->bkgBcb->bcb_vendor_id;
			$entityId	 = $vndId; //Vendors::model()->findByPk($vndId)->vnd_contact_id;
			$entityType	 = UserInfo::TYPE_VENDOR;
			$msg		 = "Vendor";
		}
		else if ($type == 3)
		{
			$drvId		 = $bookingModel->bkgBcb->bcb_driver_id;
			$entityId	 = $drvId; //Drivers::model()->findByPk($drvId)->drv_contact_id;
			$entityType	 = UserInfo::TYPE_DRIVER;
			$msg		 = "Driver";
		}
		else if ($type == 5)
		{
			$agentId	 = $bookingModel->bkg_agent_id;
			$entityId	 = $agentId; //Agents::model()->findByPk($agentId)->agt_contact_id;
			$entityType	 = UserInfo::TYPE_AGENT;
			$msg		 = "Partner";
		}
		$contactId = ContactProfile::getByEntityId($entityId, $entityType);

		$success	 = ($contactId) ? true : false;
		$returnArr	 = ['success' => $success, 'contactID' => $contactId, 'msg' => $msg];
		echo json_encode($returnArr);
	}
}
