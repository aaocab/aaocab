<?php

class AgentController extends Controller
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
			'accessControl', //perform access control for CRUD operations
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
			['allow', 'actions' => ['form', 'changepassword', 'account', 'ledgerbooking', 'linkuser', 'addnewuser', 'link', 'markuplog', 'wallet'], 'roles' => ['agentAdd', 'agentEdit']],
			['allow', 'actions' => ['ledgerbooking', 'wallet'], 'roles' => ['agentLedger']],
			['allow', 'actions' => ['list', 'approvallist', 'showLog'], 'roles' => ['agentList']],
			['allow', 'actions' => ['settings', 'addPartnerCommission'], 'roles' => ['agentSettings']],
			['allow', 'actions' => ['updatebooking', 'exportbookings', 'importbookings'], 'roles' => ['bulkAccountEdit']],
			['allow', 'actions' => ['changestatus', 'approve'], 'roles' => ['agentChangestatus']],
			array('allow', 'actions'	 => ['corporateform', 'view', 'credithistory', 'bookinghistory',
					'markuplist', 'markupadd', 'markupdelete', 'bookingpreferences',
					'regprogress', 'addtransaction', 'refundcredit', 'delete11', 'changetype', 'agentsbytype', 'bookingmsgdefaults', 'ledgerpdf', 'checkCreditLimit', 'CheckLatestCreditLimit', 'getfutureBooking', 'walletpdf', 'generateInvoice', 'addPayment', 'changeAgentType', 'documentDetails', 'notificationsDetails'],
				'users'		 => array('@'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionList()
	{
		$this->pageTitle = "Agent List";
		$model			 = new Agents('search');
		$model->agt_type = -1;
		$request		 = Yii::app()->request;
		if ($request->getParam('Agents'))
		{
			$arr				 = $request->getParam('Agents');
			$model->createDate1	 = $arr['createDate1'];
			$model->createDate2	 = $arr['createDate2'];
			$model->adm_fname	 = $arr['adm_fname'];
			$model->attributes	 = $arr;
		}

		$dataProvider = $model->resetScope()->search();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('list', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionSettings()
	{
		$this->pageTitle = "Settings";
		$agtid			 = Yii::app()->request->getParam('agtid');
		if ($agtid != "")
		{
			$model			 = Agents::model()->findByPk($agtid);
			$this->pageTitle = "Agent Settings";

			$oldData	 = $model->attributes;
			$AgentRel	 = AgentRel::model()->find('arl_agt_id=:id', ['id' => $agtid]);
			if ($AgentRel == '' || $AgentRel == null)
			{
				$AgentRel = new AgentRel();
			}
			$patSettingData	 = PartnerSettings::model()->getValueById($agtid);
			$patSettingModel = PartnerSettings::model()->findByPk($patSettingData['pts_id']);
			if ($patSettingModel == '' || $patSettingModel == null)
			{
				$patSettingModel			 = new PartnerSettings();
				$patSettingModel->pts_agt_id = $agtid;
			}
		}

		if (isset($_REQUEST['Agents']))
		{
			//$walletbalance = AccountTransactions::model()->getPartnerWalletBalance($agtid);
			$AgentRel->arl_operating_managers	 = Yii::app()->request->getParam('arl_operating_managers') ? implode(Yii::app()->request->getParam('arl_operating_managers'), ',') : '';
			$arr1								 = Yii::app()->request->getParam('Agents');
			$patSettingArr						 = Yii::app()->request->getParam('PartnerSettings');
			$model->attributes					 = $arr1;
			$newData							 = $model->attributes;
			$model->agt_approved_untill_date	 = DateTimeFormat::DatePickerToDate($model->agt_approved_untill_date);
			$model->agt_approved_by				 = UserInfo::getUserId();
			//$model->agt_payment_outstanding_wallet = ($arr1['agt_payment_outstanding_limit'] - $walletbalance['FinalReceivable']);
			$agentUpdatedData					 = Agents::model()->logModified($oldData, $newData);
			$model->agt_log						 = $model->addLog($oldData, $newData, $agentUpdatedData);
			$success							 = $model->save();
			$AgentRel->arl_agt_id				 = $agtid;
			$AgentRel->save();

			if ($patSettingArr['pts_rotating_credit_limit'] != '')
			{
				$patSettingModel->pts_rotating_credit_limit = $patSettingArr['pts_rotating_credit_limit'];
				$patSettingModel->save();
			}
			if ($patSettingArr['pts_send_invoice_to'] != '')
			{
				$patSettingModel->pts_send_invoice_to = $patSettingArr['pts_send_invoice_to'];
				$patSettingModel->save();
			}
			if ($patSettingArr['pts_generate_invoice_to'] != '')
			{
				$patSettingModel->pts_generate_invoice_to = $patSettingArr['pts_generate_invoice_to'];
				$patSettingModel->save();
			}
			if ($success)
			{
				$this->redirect(array('list'));
			}
		}

		$this->render('setting', array('model' => $model, 'AgentRel' => $AgentRel, 'patSettingModel' => $patSettingModel));
	}

	public function actionForm()
	{
		$this->pageTitle = "Add Agent";
		$agtid			 = Yii::app()->request->getParam('agtid');
		$oldData		 = false;
		if ($agtid != "")
		{
			$model			 = Agents::model()->findByPk($agtid);
			$this->pageTitle = "Edit Agent";
			$ftype			 = 'Edit';
			$model->scenario = 'agentjoin';
			$oldData		 = $model->attributes;
			$AgentRel		 = AgentRel::model()->find('arl_agt_id=:id', ['id' => $agtid]);
		}
		else
		{

			$model							 = new Agents('agentjoin');
			$model->agt_phone_country_code	 = '91';
			$ftype							 = 'Add';
		}
		$userInfo		 = UserInfo::getInstance();
		$AgentMessages	 = new AgentMessages();
		if ($AgentRel == '')
		{
			$AgentRel = new AgentRel();
		}
		$createLog = false;
		if (isset($_REQUEST['Agents']))
		{
			$arr1 = Yii::app()->request->getParam('Agents');

			$model->attributes					 = $arr1;
			$newData							 = $model->attributes;
			$model->agt_vendor_autoassign_flag	 = ($model->agt_block_autoassign_flag == 0) ? 1 : 0;
			$model->agt_otp_required			 = ($model->agt_otp_not_required == 0) ? 1 : 0;
			$model->agt_username				 = $model->agt_email;

			$model->validatorList->add(CValidator::createValidator('required', $model, 'agt_email'));
			$result = CActiveForm::validate($model);
			if ($agtid == '')
			{
				if ($model->resetScope()->exists('(agt_email=:email OR agt_username=:username) AND agt_active IN(1,2)', ['email' => $model->agt_email, 'username' => $model->agt_username]))
				{
					$model->addError('agt_email', 'Email already exists.');
					$result = ['errors' => $model->getErrors()];
				}
			}
			else
			{
				if ($model->resetScope()->exists('agt_id<>:id AND (agt_email=:email OR agt_username=:username) AND agt_active IN(1,2)', ['id' => $agtid, 'email' => $model->agt_email, 'username' => $model->agt_username]))
				{
					$model->addError('agt_email', 'Email already exists.');
					$result = ['errors' => $model->getErrors()];
				}
			}

			if ($result == '[]')
			{
				$agentUpdatedData	 = Agents::model()->logModified();
// $emailExist = Agents::model()->checkEmail($agtid,$arr1['agt_email']);
				$arrAgentMessages	 = Yii::app()->request->getParam('AgentMessages');
//				var_dump($arrAgentMessages['agt_agent_whatsapp']);
//				exit;
				$uploadedFile		 = CUploadedFile ::getInstance($model, "agt_owner_photo");
				$uploadedFile1		 = CUploadedFile:: getInstance($model, "agt_aadhar");
				$uploadedFile2		 = CUploadedFile::getInstance($model, "agt_company_add_proof");
				$uploadedFile3		 = CUploadedFile::getInstance($model, "agt_pan_card");
				$model->agt_username = $model->agt_email;
				$cty_id				 = Cities::model()->getIdByCity($arr1['agt_city']);

				if ($cty_id != '')
				{
					$model->agt_city = $cty_id;
				}
				$model->agt_is_mail_sent = 1;

				$modelCloned = clone $model;
				$modelCloned->validatorList->add(CValidator::createValidator('required', $modelCloned, 'agt_email'));
				$result		 = CActiveForm::validate($modelCloned);
				if ($result == '[]')
				{
					$contactId = ContactProfile::getByEntityId($agtid, UserInfo::TYPE_AGENT);
//					if ($contactId == '' || $contactId == NULL)
//					{
//						$contactId = $model->agt_contact_id;
//					}
					unset($model->agt_owner_photo);
					unset($model->agt_aadhar);
					unset($model->agt_company_add_proof);
					unset($model->agt_pan_card);

					/**
					 * Add/update contact details
					 */
					$jsonObj									 = new stdClass();
					$jsonObj->profile->firstName				 = trim($model->agt_fname);
					$jsonObj->profile->lastName					 = trim($model->agt_lname);
					$jsonObj->profile->email					 = trim($model->agt_email);
					$jsonObj->profile->primaryContact->number	 = trim($model->agt_phone);
					$jsonObj->profile->primaryContact->code		 = trim($model->agt_phone_country_code);
					//$contactId									 = $model->agt_contact_id;
					if ($contactId)
					{
						Contact::modifyContact($jsonObj, $contactId, 1, UserInfo::TYPE_AGENT);
					}
					else
					{
						$returnSet				 = Contact::createContact($jsonObj, 0, UserInfo::TYPE_AGENT);
						$contactId				 = $returnSet->getData()['id'];
						$model->agt_contact_id	 = $contactId;
					}

					if ($model->isNewRecord)
					{
						$chars				 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
						$password			 = substr(str_shuffle($chars), 0, 4);
						$model->agt_password = md5($password);
						$userModel			 = Users::model()->find('usr_email=:email AND usr_mobile=:phone', ['email' => $model->agt_username, 'phone' => $model->agt_phone]);
						if (!$userModel)
						{
							$userModel = Users::model()->find('usr_email=:email', ['email' => $model->agt_username]);
						}
						if ($userModel == '')
						{
							$userModel						 = new Users();
							$userModel->usr_name			 = $model->agt_fname;
							$userModel->usr_contact_id		 = $contactId;
							$userModel->usr_lname			 = $model->agt_lname;
							$userModel->usr_email			 = $model->agt_email;
							$userModel->usr_mobile			 = $model->agt_phone;
							$userModel->usr_password		 = $model->agt_password;
							$userModel->usr_create_platform	 = 1;
							$userModel->usr_acct_type		 = 1;
							$userModel->scenario			 = 'agentjoin';
							$userModel->save();
						}

						$newRecord	 = true;
						$createLog	 = true;
					}

					$model->agt_city = $cty_id;
					if ($model->isNewRecord)
					{
						$dataArray	 = array();
						$dt			 = date('Y-m-d H:i:s');
						$user		 = Yii::app()->user->getId();
						if ($model->agt_city != '')
						{
							$ctyCode = Cities::model()->findByPk($model->agt_city)->cty_code;
						}
						if ($ctyCode != '')
						{
							$model->agt_referral_code = "AG-" . $ctyCode . "-" . time() . '-' . mt_rand(100, 999);
						}
						else
						{
							$model->agt_referral_code = "AG-" . "CITY" . "-" . time() . '-' . mt_rand(100, 999);
						}
//$cty_id.mt_rand(1000,9999);
						array_unshift($dataArray, array(0 => $user, 1 => $dt));
						$model->agt_log = CJSON::encode($dataArray);
					}
					else
					{
						$model->agt_log = $model->addLog($oldData, $newData, $agentUpdatedData);
					}
					$model->agt_license_expiry_date	 = DateTimeFormat::DatePickerToDate($model->agt_license_expiry_date);
					$model->agt_booking_platform	 = 2;
					$success						 = $model->save();
					if ($success)
					{
						if ($createLog == true)
						{
							AgentLog::model()->createLog($model->agt_id, "Agent Created.", $userInfo, AgentLog::AGENT_CREATED, false, false);
						}

						$model->agt_agent_id = "AGT00" . $model->agt_id;

						if ($userModel->user_id > 0)
						{
							$agentUserModel = AgentUsers::model()->find('agu_user_id=:user AND agu_agent_id=:agent', ['user' => $userModel->user_id, 'agent' => $model->agt_id]);
							if ($agentUserModel == '')
							{
								$agentUserModel					 = new AgentUsers();
								$agentUserModel->agu_agent_id	 = $model->agt_id;
								$agentUserModel->agu_user_id	 = $userModel->user_id;
								$agentUserModel->save();
							}
						}
						$partnerSettingModel = PartnerSettings::model()->find('pts_agt_id=:agent', ['agent' => $model->agt_id]);
						if ($partnerSettingModel == '')
						{
							$partnerSettingModel							 = new PartnerSettings();
							$partnerSettingModel->pts_agt_id				 = $model->agt_id;
							$partnerSettingModel->pts_is_stop_vendor_payment = 0;
							$partnerSettingModel->pts_create_date			 = new CDbExpression('NOW()');
							$partnerSettingModel->save();
						}
						$partnerSettingModel->pts_is_payment_lock	 = $_REQUEST['Agents']['agt_payment_lock'][0];
						$partnerSettingModel->pts_extra_comm_display = $_REQUEST['Agents']['agt_extra_comm_display'][0];
						$partnerSettingModel->save();
						$partnerStatsModel							 = PartnerStats::model()->find('pts_agt_id=:agent', ['agent' => $model->agt_id]);
						if ($partnerStatsModel == '')
						{
							$partnerStatsModel				 = new PartnerStats();
							$partnerStatsModel->pts_agt_id	 = $model->agt_id;
							$partnerStatsModel->save();
						}

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
						$model->agt_payment_lock		 = $partnerSettingModel->pts_is_payment_lock;
						$model->agt_extra_comm_display	 = $partnerSettingModel->pts_extra_comm_display;
						$model->save();

						if ($contactId)
						{
							//Updating contact profile table
							//ContactProfile::setProfile($contactId, UserInfo::TYPE_AGENT);
							ContactProfile::updateEntity($contactId, $model->agt_id, UserInfo::TYPE_AGENT);
						}

						if (isset($_POST['AgentRel']))
						{
							$AgentRel->attributes				 = Yii::app()->request->getParam('AgentRel');
							$AgentRel->arl_agt_id				 = $model->agt_id;
							$AgentRel->arl_operating_managers	 = $model->agt_admin_id;

							$uploadedFile3	 = CUploadedFile::getInstance($AgentRel, "arl_voter_id_path");
							$uploadedFile4	 = CUploadedFile::getInstance($AgentRel, "arl_driver_license_path");
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
//set pending approval
						$voter = false;
						if ($AgentRel != '' && !$AgentRel->isNewRecord)
						{
							$voter = ($AgentRel->arl_voter_id_path != '') ? true : false;
						}
						if (($model->agt_is_owner_aadharcard != 0 || $model->agt_is_voter_id != 0 || $model->agt_aadhar != '' || $voter) && $model->agt_approved == 0)
						{
							$model->agt_approved = 2;
							$model->save();
						}

						if ($arrAgentMessages != '')
						{
							$arr_agent_is_email		 = $arrAgentMessages['agt_agent_email'];
							$arr_agent_is_sms		 = $arrAgentMessages['agt_agent_sms'];
							$arr_agent_is_app		 = $arrAgentMessages['agt_agent_app'];
							$arr_agent_is_whatsapp	 = $arrAgentMessages['agt_agent_whatsapp'];

							$arr_trvl_is_email		 = $arrAgentMessages['agt_trvl_email'];
							$arr_trvl_is_sms		 = $arrAgentMessages['agt_trvl_sms'];
							$arr_trvl_is_app		 = $arrAgentMessages['agt_trvl_app'];
							$arr_trvl_is_whatsapp	 = $arrAgentMessages['agt_trvl_whatsapp'];

							$arr_rm_is_email	 = $arrAgentMessages['agt_rm_email'];
							$arr_rm_is_sms		 = $arrAgentMessages['agt_rm_sms'];
							$arr_rm_is_app		 = $arrAgentMessages['agt_rm_app'];
							$arr_rm_is_whatsapp	 = $arrAgentMessages['agt_rm_whatsapp'];

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

								$AgentMessages->agt_agent_email		 = $arr_agent_is_email[$key];
								$AgentMessages->agt_agent_sms		 = $arr_agent_is_sms[$key];
								$AgentMessages->agt_agent_app		 = $arr_agent_is_app[$key];
								$AgentMessages->agt_agent_whatsapp	 = $arr_agent_is_whatsapp[$key];

								$AgentMessages->agt_trvl_email		 = $arr_trvl_is_email[$key];
								$AgentMessages->agt_trvl_sms		 = $arr_trvl_is_sms[$key];
								$AgentMessages->agt_trvl_app		 = $arr_trvl_is_app[$key];
								$AgentMessages->agt_trvl_whatsapp	 = $arr_trvl_is_whatsapp[$key];

								$AgentMessages->agt_rm_email	 = $arr_rm_is_email[$key];
								$AgentMessages->agt_rm_sms		 = $arr_rm_is_sms[$key];
								$AgentMessages->agt_rm_app		 = $arr_rm_is_app[$key];
								$AgentMessages->agt_rm_whatsapp	 = $arr_rm_is_whatsapp[$key];
								$AgentMessages->save();
							}
						}

//set pending approval
						if ($newRecord)
						{
							$emailWrapper	 = new emailWrapper();
							$emailWrapper->signupEmailAgent($model->agt_id, 1, $password);
							$emailWrapper2	 = new emailWrapper();
							$emailWrapper2->agentJoinEmail($arr1['agt_city'], $model->agt_id, $password);
						}

						$this->redirect(array('list'));
					}
				}
				else
				{
					$result = CActiveForm:: validate($model);
				}
				if (Yii::app()->request->isAjaxRequest)
				{
					echo $result;
					Yii::app()->end();
				}
			}
			else
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					echo $result;
					Yii::app()->end();
				}
			}
		}
		$partnerSettingModel = PartnerSettings::model()->find('pts_agt_id=:agent', ['agent' => $model->agt_id]);

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('form', array('model' => $model, 'AgentRel' => $AgentRel, 'AgentMessages' => $AgentMessages, 'isNew' => $ftype, 'PartnerSettingModel' => $partnerSettingModel), false, $outputJs);
	}

	public function uploadAgentFiles($uploadDocument, $agent_id)
	{
		$fileName	 = $agent_id . "-" . 'agent' . "-" . date('YmdHis') . "." . pathinfo($uploadDocument, PATHINFO_EXTENSION);
		$dir		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
		if (!is_dir($dir))
		{
			mkdir($dir);
		}
		$dirByAgentId = $dir . DIRECTORY_SEPARATOR . $agent_id;
		if (!is_dir($dirByAgentId))
		{
			mkdir($dirByAgentId);
		}

		$foldertoupload	 = $dirByAgentId . DIRECTORY_SEPARATOR . $fileName;
		$extention		 = pathinfo($uploadDocument, PATHINFO_EXTENSION);
		if (strtolower($extention) == 'png' || strtolower($extention) == 'jpg' || strtolower($extention) == 'jpeg' || strtolower($extention) == 'gif')
		{
			Vehicles::model()->img_resize($uploadDocument->tempName, 1200, $dirByAgentId . DIRECTORY_SEPARATOR, $fileName);
		}
		else
		{
			$uploadDocument->saveAs($foldertoupload);
		}

		$path = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $agent_id . DIRECTORY_SEPARATOR . $fileName;
		return $path;
	}

	public function uploadAgentDocument($uploadedFile, $agent_id)
	{
		$fileName	 = $agent_id . "-" . 'agreement' . "-" . date('YmdHis') . "." . pathinfo($uploadedFile, PATHINFO_EXTENSION);
		$dir		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
		if (!is_dir($dir))
		{
			mkdir($dir);
		}
		$dirByAgentId = $dir . DIRECTORY_SEPARATOR . $agent_id;
		if (!is_dir($dirByAgentId))
		{
			mkdir($dirByAgentId);
		}

		$foldertoupload	 = $dirByAgentId . DIRECTORY_SEPARATOR . $fileName;
		$extention		 = pathinfo($uploadedFile, PATHINFO_EXTENSION);
		if (strtolower($extention) == 'png' || strtolower($extention) == 'jpg' || strtolower($extention) == 'jpeg' || strtolower($extention) == 'gif')
		{
			Vehicles::model()->img_resize($uploadedFile->tempName, 1200, $dirByAgentId . DIRECTORY_SEPARATOR, $fileName);
		}
		else
		{
			$uploadedFile->saveAs($foldertoupload);
		}

		$path = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $agent_id . DIRECTORY_SEPARATOR . $fileName;
		return $path;
	}

	public function actionChangestatus()
	{
		$agtid		 = Yii::app()->request->getParam('agt_id');
		$agt_active	 = Yii::app()->request->getParam('agt_active');
		$success	 = false;
		if ($agt_active == 1)
		{
			$model				 = Agents::model()->resetScope()->findByPk($agtid);
			$model->agt_active	 = 2;
			$model->update();
			$success			 = true;
		}
		else if ($agt_active == 2)
		{
			$model				 = Agents::model()->resetScope()->findByPk($agtid);
			$model->agt_active	 = 1;
			$model->update();
			$success			 = true;
		}
		echo json_encode(["success" => $success]);
		Yii::app()->end();
	}

	public function actionApprove()
	{
		$agtid		 = Yii::app()->request->getParam('agt_id');
		$agt_approve = Yii::app()->request->getParam('agt_approve');
		$approve	 = Yii::app()->request->getParam('approve');
		$disapprove	 = Yii::app()->request->getParam('disapprove');
		$success	 = false;
		$model		 = Agents::model()->resetScope()->findByPk($agtid);
		$agentRel	 = AgentRel::model()->find('arl_agt_id =:id', ['id' => $agtid]);
		if ($agt_approve == 1)
		{
			if ($approve)
			{
				$model->agt_approved = 1;
				$success			 = $model->update();
				if ($success)
				{
					$agentLog					 = new AgentLog();
					$agentLog->agl_usr_ref_id	 = Yii::app()->user->getId();
					$agentLog->agl_usr_type		 = AgentLog::Admin;
					$agentLog->agl_agent_id		 = $model->agt_id;
					$agentLog->agl_desc			 = "Agent approved successfully";
					$agentLog->agl_event_id		 = AgentLog::AGENT_APPROVED;
					$agentLog->save();
				}
				if ($model->agt_type == 2 && $model->agt_commission == '')
				{
					$this->redirect(['agent/form', ['agtid' => $model->agt_id]]);
				}
				$this->redirect(['agent/list']);
			}
		}
		else if ($agt_approve == 2)
		{
			if ($disapprove)
			{
				$model->agt_approved = 2;
				$success			 = $model->update();
				if ($success)
				{
					$agentLog					 = new AgentLog();
					$agentLog->agl_usr_ref_id	 = Yii::app()->user->getId();
					$agentLog->agl_usr_type		 = AgentLog::Admin;
					$agentLog->agl_agent_id		 = $model->agt_id;
					$agentLog->agl_desc			 = "Agent rejected successfully";
					$agentLog->agl_event_id		 = AgentLog::AGENT_REJECTED;
					$agentLog->save();
				}
				$this->redirect(['agent/list']);
			}
		}
		$this->renderPartial('approve_dialog', ['model' => $model, 'agentRelModel' => $agentRel], null, true);
	}

	public function actionCorporateForm()
	{

		return;
		$this->pageTitle = "Coporate Account Registration Form";
		$id				 = Yii::app()->request->getParam('crpId');
		if ($id != '')
		{
			$model		 = Agents::model()->findByPk($id);
			$AgentRel	 = AgentRel::model()->find('arl_agt_id=:id', ['id' => $id]);
			$AgentUsers1 = AgentRequestedUsers::model()->findAll('aru_agent_id=:id', ['id' => $id]);
		}
		if ($model == '')
		{
			$model = new Agents();
		}
		$AgentMessages = new AgentMessages();
		if ($AgentRel == '')
		{
			$AgentRel = new AgentRel();
		}
// if($AgentUsers==''){
		$AgentUsers		 = new AgentRequestedUsers();
//  }
		$model->scenario = "corporatejoin";

		if (isset($_POST['Agents']))
		{

			$model->attributes	 = Yii::app()->request->getParam('Agents');
			$arrAgentMessages	 = Yii::app()->request->getParam('AgentMessages');
			$model->agt_username = $model->agt_email;
			if ($model->isNewRecord)
			{
				$model->agt_referral_code = strtoupper($model->agt_referral_code);
			}
			$model->agt_type = 1;
			if ($model->agt_crp_date != '')
			{
				$model->agt_crp_date = date('Y-m-d', strtotime(str_replace('/', '-', $model->agt_crp_date)));
			}
			else
			{
				unset($model->agt_crp_date);
			}
			if ($model->agt_depo_date != '')
			{
				$model->agt_depo_date = date('Y-m-d', strtotime(str_replace('/', '-', $model->agt_depo_date)));
			}
			else
			{
				unset($model->agt_depo_date);
			}

			$uploadedFile1	 = CUploadedFile::getInstance($model, "agt_agreement");
			$uploadedFile2	 = CUploadedFile::getInstance($model, "agt_bussiness_registration");
			$uploadedFile3	 = CUploadedFile::getInstance($model, "agt_corp_reg_form");
			$uploadedFile4	 = CUploadedFile::getInstance($model, "agt_deposit_proof");

			if ($model->validate())
			{

				if ($model->isNewRecord)
				{
					$chars				 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
					$password			 = substr(str_shuffle($chars), 0, 4);
					$model->agt_password = md5($password);
					$userModel			 = Users::model()->find('usr_email=:email AND usr_mobile=:phone', ['email' => $model->agt_username, 'phone' => $model->agt_phone]);
					if (!$userModel)
					{
						$userModel = Users::model()->find('usr_email=:email', ['email' => $model->agt_username]);
					}
					if ($userModel == '')
					{
						$userModel						 = new Users();
						$userModel->usr_name			 = $model->agt_fname;
						$userModel->usr_lname			 = $model->agt_lname;
						$userModel->usr_email			 = $model->agt_email;
						$userModel->usr_mobile			 = $model->agt_phone;
						$userModel->usr_password		 = $model->agt_password;
						$userModel->usr_create_platform	 = 1;
						$userModel->usr_acct_type		 = 1;
						$userModel->scenario			 = 'agentjoin';
						$userModel->usr_corporate_id	 = $model->agt_id;
						$userModel->save();
					}


					$model->agt_password = $userModel->usr_password;
					$agentUserModel		 = AgentUsers::model()->find('agu_user_id=:user AND agu_agent_id=:agent', ['user' => $userModel->user_id, 'agent' => $model->agt_id]);
					if ($agentUserModel == '')
					{
						$agentUserModel = new AgentUsers();
					}
					$model->agt_approved			 = 0;
					$success						 = $model->save();
					$agentUserModel->agu_agent_id	 = $model->agt_id;
					$agentUserModel->agu_user_id	 = $userModel->user_id;
					$agentUserModel->save();
					$model->agt_agent_id			 = "AGT00" . $model->agt_id;

					$emailWrapper = new emailWrapper();
					$emailWrapper->signupEmailAgent($model->agt_id, 1, $password);
				}

				$success = $model->save();

				if ($success)
				{
					if ($uploadedFile1 != '')
					{
						$model->agt_agreement = $this->uploadAgentFiles($uploadedFile1, $model->agt_id);
					}

					if ($uploadedFile2 != '')
					{
						$model->agt_bussiness_registration = $this->uploadAgentFiles($uploadedFile2, $model->agt_id);
					}

					if ($uploadedFile3 != '')
					{
						$model->agt_corp_reg_form = $this->uploadAgentFiles($uploadedFile3, $model->agt_id);
					}
					if ($uploadedFile4 != '')
					{
						$model->agt_deposit_proof = $this->uploadAgentFiles($uploadedFile4, $model->agt_id);
					}
					$success = $model->save();

					if (isset($_POST['AgentRel']))
					{
						$AgentRel->attributes	 = Yii::app()->request->getParam('AgentRel');
						$AgentRel->arl_agt_id	 = $model->agt_id;
						if ($AgentRel->validate())
						{
							$AgentRel->save();
						}
					}

					if (isset($_POST['AgentRequestedUsers']))
					{
						$arrIds		 = Yii::app()->request->getParam('AgentRequestedUsers')['aru_id'];
						$arrNames	 = Yii::app()->request->getParam('AgentRequestedUsers')['aru_name'];
						$arrEmails	 = Yii::app()->request->getParam('AgentRequestedUsers')['aru_email'];
						$arrPhones	 = Yii::app()->request->getParam('AgentRequestedUsers')['aru_phone'];
						foreach ($arrIds as $key => $value)
						{

							if ($value != '')
							{
								$agentUser = AgentRequestedUsers::model()->findByPk($value);
							}
							else
							{
								$agentUser = new AgentRequestedUsers;
							}
							if ($arrNames[$key] != '' && $arrEmails[$key] != '' && $arrPhones[$key])
							{
								$agentUser->aru_name	 = $arrNames[$key];
								$agentUser->aru_email	 = $arrEmails[$key];
								$agentUser->aru_phone	 = $arrPhones[$key];
								$agentUser->aru_agent_id = $model->agt_id;

								if ($agentUser->validate())
								{
									$agentUser->save();
								}
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

						$arr_rm_is_email = $arrAgentMessages['agt_rm_email'];
						$arr_rm_is_sms	 = $arrAgentMessages['agt_rm_sms'];
						$arr_rm_is_app	 = $arrAgentMessages['agt_rm_app'];

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

							$AgentMessages->agt_rm_email = $arr_rm_is_email[$key];
							$AgentMessages->agt_rm_sms	 = $arr_rm_is_sms[$key];
							$AgentMessages->agt_rm_app	 = $arr_rm_is_app[$key];
							$AgentMessages->save();
						}
					}

					if ($success)
					{
						$this->redirect('list');
					}
				}
			}
		}


		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('corpform', array('model' => $model, 'AgentRel' => $AgentRel, 'AgentUsers' => $AgentUsers, 'AgentUsers1' => $AgentUsers1, 'AgentMessages' => $AgentMessages), false, $outputJs);
	}

	public function actionChangePassword()
	{
		$agentId		 = Yii::app()->request->getParam('agent');
		$model			 = Agents::model()->findByPk($agentId);
		$model->scenario = "changepassword1";
		/* @var $model Agents */
		if (isset($_POST['Agents']))
		{
			$model->new_password	 = $_POST['Agents']['new_password'];
			$model->repeat_password	 = $_POST['Agents']['repeat_password'];
			if ($model->validate())
			{
				if ($model->new_password == $model->repeat_password)
				{
					$model->agt_password = md5($_POST['Agents']['new_password']);
					if ($model->update())
					{
						echo json_encode(["success" => true]);
						Yii::app()->end();
					}
				}
				else
				{
					echo json_encode(['success' => false, "err_messages" => "Passwords not matched."]);
					Yii::app()->end();
				}
			}
		}
		$this->renderPartial('change_password', ['model' => $model, 'agent' => $agentId], false, true);
	}

	public function actionApprovalList()
	{
		$this->pageTitle	 = "Agent Approval List";
		$model				 = new Agents('search');
		$model->agt_approved = '';
		if (isset($_REQUEST['Agents']))
		{
			$model->attributes = Yii::app()->request->getParam('Agents');
		}
		$dataProvider							 = $model->fetchApproveList();
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->render('approvallist', array
			('dataProvider'	 => $dataProvider, 'model'			 => $model));
	}

	public function actionView()
	{
		$agentId = Yii::app()->request->getParam('agent');

		$creditVal				 = Agents::model()->getAgentById([$agentId]);
		$agentAdjustTrans		 = AccountTransDetails::getAdjustableAmount(['agentId' => $agentId]);
		$agtData				 = (($creditVal[0]) ? $creditVal[0] : []) + $agentAdjustTrans;
		$agtData['agtPayable']	 = $agentAdjustTrans['transaction_amount'];
		$agtModel				 = Agents::model()->findByPk($agentId);
		$cttId					 = ContactProfile::getByEntityId($agtModel->agt_id, UserInfo::TYPE_AGENT);
		$cttPhone				 = ContactPhone::getContactNumber($cttId);
		$cttEmail				 = ContactEmail::findPrmryEmailByContactId($cttId);
		$outputJs				 = Yii::app()->request->isAjaxRequest;
		$method					 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('view', ['agtData' => $agtData, 'cttPhone' => $cttPhone, 'cttEmail' => $cttEmail, 'agentId' => $agentId, 'agtModel' => $agtModel], false, $outputJs);
	}

	public function actionDocumentDetails()
	{
		$agtId	 = Yii::app()->request->getParam('agtId');
		$docById = Agents::model()->getAllDocsbyAgtId($agtId);
		$this->renderPartial("documentDetails", ['docpath' => $docById], false, true);
	}

	public function actionNotificationsDetails()
	{
		$agtId = Yii::app()->request->getParam('agtId');
		$this->renderPartial("notificationsDetails", ['agentId' => $agtId], false, true);
	}

//to be change later
//    public function actionCredithistory()
//    {
//	$model		 = new Booking('search');
//	$paramArray	 = [];
//	$agentId	 = Yii::app()->request->getParam('agent');
//	if (isset($_REQUEST['Booking']))
//	{
//	    $model->attributes		 = Yii::app()->request->getParam('Booking');
//	    $paramArray			 = Yii::app()->request->getParam('Booking');
//	    $model->bkg_pickup_date1	 = $paramArray['bkg_pickup_date1'];
//	    $model->bkg_pickup_date2	 = $paramArray['bkg_pickup_date2'];
//	    $model->bkg_create_date1	 = $paramArray['bkg_create_date1'];
//	    $model->bkg_create_date2	 = $paramArray['bkg_create_date2'];
//	    $model->agt_trans_created1	 = $paramArray['agt_trans_created1'];
//	    $model->agt_trans_created2	 = $paramArray['agt_trans_created2'];
//	}
//	else
//	{
//	    $model->bkg_status		 = '';
//	    $model->bkg_pickup_date1	 = ($paramArray['bkg_pickup_date1'] == '') ? date('Y-m-d', strtotime('-1 month')) : $paramArray['bkg_pickup_date1'];
//	    $model->bkg_pickup_date2	 = ($paramArray['bkg_pickup_date2'] == '') ? date('Y-m-d', strtotime('+11 month')) : $paramArray['bkg_pickup_date2'];
//	    $model->bkg_create_date1	 = ($paramArray['bkg_create_date1'] == '') ? date('Y-m-d', strtotime('-1 month')) : $paramArray['bkg_create_date1'];
//	    $model->bkg_create_date2	 = ($paramArray['bkg_create_date2'] == '') ? date('Y-m-d') : $paramArray['bkg_create_date2'];
//	    $model->agt_trans_created1	 = ($paramArray['agt_trans_created1'] == '') ? date('Y-m-d', strtotime('-1 month')) : $paramArray['agt_trans_created1'];
//	    $model->agt_trans_created2	 = ($paramArray['agt_trans_created2'] == '') ? date('Y-m-d') : $paramArray['agt_trans_created2'];
//	}
//
//	$this->pageTitle = "Agent Credit History";
//	$agentBkgStatus	 = BookingSub::model()->getAgentActiveBookingStatusList($agentId);
//	$statusJSON	 = VehicleTypes::model()->getJSON($agentBkgStatus);
//	$dataProvider	 = AgentTransactions::model()->agentTransactionList(['agentId' => $agentId] + array_filter($model->attributes + $paramArray));
//	$outputJs	 = Yii::app()->request->isAjaxRequest;
//	$method		 = "render" . ( $outputJs ? "Partial" : "");
//
//	$this->$method('credithistory', ['dataProvider' => $dataProvider, 'model' => $model, 'statusJSON' => $statusJSON], false, $outputJs);
//    }

	public function actionBookinghistory()
	{
		$agentId	 = Yii::app()->request->getParam('agent');
		$model		 = new Booking('search');
		$paramArray	 = [];
		$btnType	 = Yii::app()->request->getParam('btnType');
		if (isset($_REQUEST['Booking']))
		{
			$model->attributes		 = Yii::app()->request->getParam('Booking');
			$paramArray				 = Yii::app()->request->getParam('Booking');
			$model->bkg_pickup_date1 = $paramArray['bkg_pickup_date1'];
			$model->bkg_pickup_date2 = $paramArray['bkg_pickup_date2'];
			$model->bkg_create_date1 = $paramArray['bkg_create_date1'];
			$model->bkg_create_date2 = $paramArray['bkg_create_date2'];
		}
		else
		{
			$model->bkg_status		 = '';
			$paramArray['bkg_pickup_date1'] = ($paramArray['bkg_pickup_date1'] == '') ? date('Y-m-d', strtotime('-1 day')) : $paramArray['bkg_pickup_date1'];
			$paramArray['bkg_pickup_date2'] = ($paramArray['bkg_pickup_date2'] == '') ? date('Y-m-d', strtotime('0 day')) : $paramArray['bkg_pickup_date2'];
			$model->bkg_pickup_date1 = $paramArray['bkg_pickup_date1'];
			$model->bkg_pickup_date2 = $paramArray['bkg_pickup_date2'];
			#$model->bkg_create_date1 = ($paramArray['bkg_create_date1'] == '') ? date('Y-m-d', strtotime('-1 month')) : $paramArray['bkg_create_date1'];
			#$model->bkg_create_date2 = ($paramArray['bkg_create_date2'] == '') ? date('Y-m-d') : $paramArray['bkg_create_date2'];
		}

		if (($btnType == 'invoice' || $btnType == 'sheet' || $btnType == 'proforma') && $model->bkg_pickup_date1 != '' && $model->bkg_pickup_date2 != '' && $agentId != '')
		{
			$requestResult = BookingInvoiceRequest::CreateRequest($model->bkg_pickup_date1, $model->bkg_pickup_date2, $agentId, $btnType, $paramArray);
		}

		$this->pageTitle = "Agent Account Details";
		$agentBkgStatus	 = BookingSub::model()->getAgentActiveBookingStatusList($agentId);
		$statusJSON		 = VehicleTypes::model()->getJSON($agentBkgStatus);
		
//		echo "<pre>";
//		print_r($model->attributes);
//		print_r($paramArray);
//		print_r(array_filter($model->attributes + $paramArray));
		
		$dataProvider	 = BookingSub::model()->listByAgent($agentId, array_filter($model->attributes + $paramArray));

		$creditVal				 = BookingSub::model()->getAgentCreditsByAgentArr([$agentId]);
		$agentAdjustTrans		 = AccountTransDetails::getAdjustableAmount(['agentId' => $agentId]);
		$agtData				 = array_merge($creditVal[0], $agentAdjustTrans); //$creditVal[0] + $agentAdjustTrans;
		$agtData['agtPayable']	 = $agentAdjustTrans['transaction_amount'];

		$dataProvider->getPagination()->params	 = array_filter($_REQUEST);
		$dataProvider->getSort()->params		 = array_filter($_REQUEST);
		$outputJs								 = Yii::app()->request->isAjaxRequest;
		$method									 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('bookinghistory', ['dataProvider' => $dataProvider, 'model' => $model, 'statusJSON1' => $statusJSON, 'agtData' => $agtData, 'requestResult' => $requestResult], false, $outputJs);
	}

	public function actionDelete11()
	{
		$agt_id			 = Yii::app()->request->getParam('agt_id');
		$agent_type		 = Yii::app()->request->getParam('agt_type');
		$agentModel		 = Agents::model()->findByPk($agt_id, 'agt_type=:type', ['type' => $agent_type]);
		$bookingModel	 = Booking::model()->find('bkg_agent_id=:agent', ['agent' => $agt_id]);
		if ($bookingModel != '' && $agentModel != '')
		{
			echo json_encode(['success' => false, 'message' => 'Unable to delete. Agent/Corporate has related data.']);
			exit;
		}
		if ($agentModel != '')
		{
			$agentModel->agt_active = 0;
			if ($agentModel->save())
			{
				$agentLog					 = new AgentLog();
				$agentLog->agl_usr_ref_id	 = Yii::app()->user->getId();
				$agentLog->agl_usr_type		 = AgentLog::Admin;
				$agentLog->agl_agent_id		 = $agentModel->agt_id;
				$agentLog->agl_desc			 = "Agent deleted successfully";
				$agentLog->agl_event_id		 = AgentLog::AGENT_DELETED;
				$agentLog->save();

				echo json_encode(['success' => true, 'message' => 'Agent/Corporate deleted successfully.']);
				exit;
			}
		}

		echo json_encode(['success' => false, 'message' => 'Error Occured.']);
		exit;
	}

	public function actionChangetype()
	{
		$agt_id	 = Yii::app()->request->getParam('agt_id');
		$model	 = Agents::model()->findByPk($agt_id);
		if (isset($_POST['Agents']))
		{
			$model->attributes	 = Yii::app()->request->getParam('Agents');
			$success			 = $model->save();
			if ($success)
			{
				$agentLog					 = new AgentLog();
				$agentLog->agl_usr_ref_id	 = Yii::app()->user->getId();
				$agentLog->agl_usr_type		 = AgentLog::Admin;
				$agentLog->agl_agent_id		 = $model->agt_id;
				if ($model->agt_type == 0)
				{
					$agentLog->agl_desc = "Agent type chaged from reseller to travel";
				}
				else
				{
					$agentLog->agl_desc = "Agent type chaged from travel to reseller";
				}
				$agentLog->agl_event_id = AgentLog::AGENT_TYPE_CHANGED;
				$agentLog->save();
			}
			$this->redirect(array('list'));
		}
		$this->renderPartial('agenttype', ['model' => $model], false, true);
	}

	public function actionChangeAgentType()
	{
		$agt_id = Yii::app()->request->getParam('agt_id');

		$type		 = Yii::app()->request->getParam('agt_type');
		$model		 = Agents::model()->findByPk($agt_id);
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('changeAgentType', array('model' => $model), FALSE, $outputJs);
	}

	public function actionAgentsbytype()
	{

		$agtId = Yii::app()->request->getParam('agt_id');
		if ($agtId > 0)
		{
			$agentModel		 = Agents::model()->findByPk($agtId);
			$modelAgentRel	 = AgentRel::model()->find('arl_agt_id=:agt_id', ['agt_id' => $agtId]);
			echo json_encode(['notifyDetails'	 => ['agt_copybooking_name'		 => $agentModel->agt_copybooking_name,
					'agt_copybooking_email'		 => $agentModel->agt_copybooking_email,
					'agt_copybooking_phone'		 => $agentModel->agt_copybooking_phone,
					'agt_phone_country_code'	 => ($agentModel->agt_phone_country_code != '') ? $agentModel->agt_phone_country_code : '91',
					//'agt_copybooking_ismail' => $agentModel->agt_copybooking_ismail,
// 'agt_copybooking_issms' => $agentModel->agt_copybooking_issms,
					'agt_trvl_sendupdate'		 => $agentModel->agt_trvl_sendupdate,
					//   'agt_trvl_isemail' => $agentModel->agt_trvl_isemail,
//  'agt_trvl_issms' => $agentModel->agt_trvl_issms,
					'agt_approved_untill_date'	 => $agentModel->agt_approved_untill_date,
					'arl_operating_managers'	 => $modelAgentRel->arl_operating_managers,
					'agt_type'					 => $agentModel->agt_type,
					'agt_commission_value'		 => $agentModel->agt_commission_value,
					'agt_commission'			 => $agentModel->agt_commission,
					'agt_vendor_autoassign_flag' => $agentModel->agt_vendor_autoassign_flag,
					'agt_payable_percentage'	 => $agentModel->agt_payable_percentage,
					'agt_name'					 => $agentModel->agt_company . '(' . $agentModel->agt_fname . ' ' . $agentModel->agt_lname . ')',
				], 'type'			 => 2]);
			Yii::app()->end();
		}
	}

	public function actionBookingmsgdefaults()
	{
		$agentId	 = Yii::app()->request->getParam('agent_id');
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

	public function actionLedgerbooking()
	{
		$this->pageTitle		 = "Agent Accounts";
		$agtId					 = Yii::app()->request->getParam('agtId');
		$transDate1				 = '';
		$transDate2				 = '';
		$model					 = new PaymentGateway();
		$model->apg_trans_ref_id = $agtId;
		$model->scenario		 = "ledgerbooking";

		$userInfo = UserInfo::getInstance();

		if (isset($_REQUEST['PaymentGateway']) && isset($_POST['addnewtransaction']) && !isset($_POST['bookingSearch']) && (trim($_REQUEST['PaymentGateway']['apg_amount'])) != "" && (trim($_REQUEST['PaymentGateway']['apg_ledger_id']) != ""))
		{
			$model->attributes				 = $_REQUEST['PaymentGateway'];
			$model->apg_date				 = ($model->apg_date != '') ? DateTimeFormat::DatePickerToDate($model->apg_date) . " " . "00:00:00" : new CDbExpression("NOW()");
			$date							 = $model->apg_date;
			$model->apg_complete_datetime	 = new CDbExpression("NOW()");
			$valArr							 = ['TRANSACTION_TYPE'	 => $model->apg_banktrans_type, 'BANK_NAME'			 => trim($_POST['PaymentGateway']['bank_name']),
				'BANK_IFSC_CODE'	 => trim($_POST['PaymentGateway']['bank_ifsc']), 'BANK_BRANCH_NAME'	 => trim($_POST['PaymentGateway']['bank_branch']),
				'CHEQUE_NUMBER'		 => trim($_POST['PaymentGateway']['bank_chq_no']),
				'CHEQUE_DATE'		 => (trim($_POST['PaymentGateway']['bank_chq_dated']) == '') ? '' : DateTimeFormat::DatePickerToDate(trim($_POST['PaymentGateway']['bank_chq_dated'])),
				'TRANSACTION_MODE'	 => $model->apg_mode, 'DESCRIPTION'		 => $model->apg_remarks];
			$arr							 = array_filter($valArr);
			$model->apg_response_details	 = json_encode($arr, true);
			$bankRefId						 = $agtId;
			$refType						 = NULL;
			$ledgerType						 = Accounting::LI_PARTNER;
			$accType						 = Accounting::AT_PARTNER;
			$bankLedgerId					 = $model->apg_ledger_id;
			$remarks						 = $model->apg_remarks;
			$paymentTypeId					 = PaymentType::model()->payentTypeFromLedger($model->apg_ledger_id);

			if($model->apg_ledger_id == Accounting::LI_PARTNERWALLET)
			{
				$checkAccess = Yii::app()->user->checkAccess("addPartnerWallet");
				if(!$checkAccess)
				{
				   $errormsg = "Your are not authorized for this action.";
				   goto skip;
				}
//				if($model->apg_ledger_id == Accounting::LI_PARTNERWALLET && $checkAccess)
//				{
//					$availableLimit = Agents::getAvailableLimit($agtId);
//					if($model->apg_amount > $availableLimit)
//					{
//					  $errormsg = "Partner effective credit limit is not available.";
//					  goto skip;
//					}
//				}
//				else
//				{
//				   $errormsg = "Your are not authorized for this action.";
//				   goto skip;
//				}
			}
			//if (in_array($bankLedgerId, [Accounting::LI_BANK, Accounting::LI_HDFC, Accounting::LI_ICICI, Accounting::LI_PAYTM, Accounting::LI_PAYU, Accounting::LI_EBS, Accounting::LI_FREECHARGE, Accounting::LI_MOBIKWIK, Accounting::LI_ZZAAKPAY]))
			if (in_array($model->apg_ledger_id, Accounting::getOnlineLedgers(false)))
			{
				$paymentGateway	 = PaymentGateway::model()->addAmountForOnlineLedger($model, $agtId, $paymentTypeId, $bankLedgerId, $accType, UserInfo::getInstance());
				$bankRefId		 = $paymentGateway->apg_id;
				$refType		 = Accounting::AT_ONLINEPAYMENT;
				$remarks		 = $paymentGateway->apg_remarks;
			}
			if ($_POST['PaymentGateway']['operator_id'] == 0)
			{
				if(in_array($bankLedgerId, [12,27]))
				{
					$model->apg_amount = -1 * $model->apg_amount;
				}
				$addPartnerAmount = AccountTransactions::model()->addAmountGozoReceiver($model, $agtId, $bankRefId, $remarks, $refType, $accType, $ledgerType, $date, $agtId);
			}
			else
			{
				$addPartnerAmount = AccountTransactions::model()->addAmountGozoPaid($model, $agtId, $bankRefId, $remarks, $refType, $accType, $ledgerType, $date);
			}
			$model			 = new PaymentGateway();
			$model->scenario = "ledgerbooking";
			skip:
		}
		$transDate1					 = $model->trans_create_date1	 = date('Y-m-d', strtotime('today - 1 days'));
		$transDate2					 = $model->trans_create_date2	 = date('Y-m-d', strtotime('today'));
		if ($_REQUEST['PaymentGateway']['trans_create_date1'] != '' && $_REQUEST['PaymentGateway']['trans_create_date2'] != '')
		{
			$transDate1					 = $_REQUEST['PaymentGateway']['trans_create_date1'];
			$transDate2					 = $_REQUEST['PaymentGateway']['trans_create_date2'];
			$model->trans_create_date1	 = $transDate1;
			$model->trans_create_date2	 = $transDate2;
			$model->apg_ledger_type_id	 = $_REQUEST['PaymentGateway']['apg_ledger_type_id'];
		}
		$recordSet							 = AccountTransDetails::transactionList($agtId, $transDate1, $transDate2, $model->apg_ledger_type_id);
		$totalRecords						 = count($recordSet);
		$agentList							 = new CArrayDataProvider($recordSet, array('pagination' => array('pageSize' => 500)));
		$agentList->getPagination()->params	 = array_filter($_GET + $_POST);
		$agentList->getSort()->params		 = array_filter($_GET + $_POST);
		$agentModels						 = $agentList->getData();
		//$agentAmount						 = AccountTransDetails::accountTotalSummary($agtId, $transDate1, $transDate2);

		$tillDate	 = (strtotime($transDate2 . " 23:59:59") > strtotime(date("Y-m-d H:i:s"))) ? date("Y-m-d H:i:s") : $transDate2 . " 23:59:59";
		$agentAmount = AccountTransDetails::accountTotalSummary($agtId, '', '', '', $tillDate);
		$agentModel	 = Agents::model()->findByPk($agtId);
		//$date		 = date('Y-m-d H:i:s', strtotime('+12 hour'));
		//$walletBalance	= AccountTransactions::checkPartnerWalletBalance($agtId, $date);
		$getBalance	 = PartnerStats::getBalance($agtId);
		$this->render('ledgerbooking', ['model' => $model, 'agentList' => $agentList, 'agentmodels' => $agentModels, 'agentAmount' => $agentAmount, 'totalRecords' => $totalRecords, 'agentModel' => $agentModel, 'getBalance' => $getBalance, 'errormsg' => $errormsg]);
	}

	public function actionRegProgress()
	{
		$this->pageTitle = "Agent Registration Progress";
		$model			 = new Agents();
		if ($_REQUEST['Agents'])
		{
			$arr							 = Yii::app()->request->getParam('Agents');
			$model->agt_is_voterid			 = ($arr['agt_is_voterid'] != '') ? $arr['agt_is_voterid'] : '';
			$model->agt_is_driver_license	 = ($arr['agt_is_driver_license'] != '') ? $arr['agt_is_driver_license'] : '';
			$model->agt_is_aadhar			 = ($arr['agt_is_aadhar'] != '') ? $arr['agt_is_aadhar'] : '';
			$model->agt_first_name			 = ($arr['agt_first_name'] != '') ? $arr['agt_first_name'] : '';
		}
		$dataProvider	 = $model->getRegistrationProgress($model->agt_is_voterid, $model->agt_is_driver_license, $model->agt_is_aadhar, $model->agt_first_name);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$type			 = 'command';
		if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == true)
		{
			$voter_id		 = Yii::app()->request->getParam('export_agt_is_voterid');
			$driver_license	 = Yii::app()->request->getParam('export_agt_is_driver_license');
			$aadhar			 = Yii::app()->request->getParam('export_agt_is_aadhar');
			$name			 = Yii::app()->request->getParam('export_agt_first_name');

			$data = $model->getRegistrationProgress($voter_id, $driver_license, $aadhar, $name, $type);

			header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename=\"RegProgress_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$handle = fopen("php://output", 'w');
			fputcsv($handle, array("Name", "Email", "City Name", "Registered", "Voter ID", "Aadhar", "License", "Trade License", "Bank Details"));
			foreach ($data as $d)
			{
				$rowArray					 = array();
				$rowArray['agt_fname']		 = $d['agt_fname'];
				$rowArray['agt_email']		 = $d['agt_email'];
				$rowArray['cty_name']		 = $d['cty_name'];
				$rowArray['agt_create_date'] = $d['agt_create_date'];
				$rowArray['voterPath']		 = $d['voterPath'];
				$rowArray['aadharPath']		 = $d['aadharPath'];
				$rowArray['driverLicense']	 = $d['driverLicense'];
				$rowArray['tradeLicense']	 = $d['tradeLicense'];
				$rowArray['bankDeatils']	 = $d['bankDeatils'];
				$row1						 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$this->render('report_reg_progress', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionLinkuser()
	{
		$agentId								 = Yii::app()->request->getParam('agt_id');
		$agentModel								 = Agents::model()->findByPk($agentId);
		$model									 = new Users();
		$dataProvider							 = $model->linkedAgentUsersByAgent($agentId);
		$dataProvider->getPagination()->params	 = array_filter($_REQUEST);
		$dataProvider->getSort()->params		 = array_filter($_REQUEST);
		$success								 = false;
		$msg									 = "";
		if ($_REQUEST['user_id'] != "" && $_REQUEST['user_id'] != null)
		{
			$userModel = Users::model()->findByPk($_REQUEST['user_id']);
			if ($userModel != '' && $agentId != '')
			{
				$agentUserModel = AgentUsers::model()->find('agu_user_id=:user AND agu_agent_id=:agent', ['user' => $userModel->user_id, 'agent' => $agentId]);
				if ($agentUserModel == '')
				{
					$msg = "Error Occurred.";
				}
				else if ($agentUserModel != '')
				{
					$agentUserModel->delete();
					$success = true;
					$msg	 = "User unlinked successfully.";
				}
			}
			else
			{
				$msg = "Error Occurred.";
			}

			echo json_encode(['success' => $success, 'msg' => $msg]);
			Yii::app()->end();
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('linkuser', ['dataProvider' => $dataProvider, 'agentModel' => $agentModel, 'model' => $model], false, true);
	}

	public function actionLink()
	{
		$agentId	 = Yii::app()->request->getParam('agt_id');
		$agentModel	 = Agents::model()->findByPk($agentId);
		$model		 = new Users();
		if (isset($_REQUEST['Users']))
		{
			$model->search_name = $_REQUEST['Users']['search_name'];
		}
		$dataProvider							 = $model->searchByNameEmailPhone($agentId);
		$dataProvider->getPagination()->params	 = array_filter($_REQUEST);
		$dataProvider->getSort()->params		 = array_filter($_REQUEST);

		if ($_REQUEST['user_id'] != "" && $_REQUEST['user_id'] != null)
		{
			$msg		 = "Error Occurred.";
			$userModel	 = Users::model()->findByPk($_REQUEST['user_id']);
			if ($userModel != '' && $agentId != '')
			{
				$agentUserModel = AgentUsers::model()->find('agu_user_id=:user AND agu_agent_id=:agent', ['user' => $userModel->user_id, 'agent' => $agentId]);
				if ($agentUserModel == '')
				{
					$agentUserModel					 = new AgentUsers();
					$agentUserModel->agu_agent_id	 = $agentId;
					$agentUserModel->agu_user_id	 = $userModel->user_id;
					$agentUserModel->agu_role		 = 2;
					if ($agentUserModel->save())
					{
						Yii::app()->user->setFlash('success', 'User linked successfully.');
						echo json_encode(['success' => true, 'msg' => '']);
						Yii::app()->end();
					}
				}
				else
				{
					$msg = "User already linked.";
				}
			}
			Yii::app()->user->setFlash('error', $msg);
			echo json_encode(['success' => false, 'msg' => $msg]);
			Yii::app()->end();
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('link', ['dataProvider' => $dataProvider, 'agentModel' => $agentModel, 'model' => $model], false, true);
	}

	public function actionAddnewuser()
	{
		$model	 = new Users();
		$agentId = Yii::app()->request->getParam('agt_id');
		if (isset($_POST['Users']))
		{
			$model->attributes			 = $_POST['Users'];
			$chars						 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$password					 = substr(str_shuffle($chars), 0, 4);
			$model->usr_password		 = md5($password);
			$model->new_password		 = $model->usr_password;
			$model->repeat_password		 = $model->usr_password;
			$model->usr_create_platform	 = Users::Platform_Admin;
			if ($model->validate())
			{
				$userModel = Users::model()->find('usr_email=:email AND usr_mobile=:phone', ['email' => $model->usr_email, 'phone' => $model->usr_mobile]);
				if (!$userModel)
				{
					$userModel = Users::model()->find('usr_email=:email', ['email' => $model->usr_email]);
				}
				if ($userModel == '')
				{
					$model->save();
					$emailWrapper = new emailWrapper();
					$emailWrapper->confirmUserAccount($model->user_id);
					echo json_encode(['success' => true, 'url' => Yii::app()->createUrl('admin/agent/linkuser', ['agt_id' => $_POST['agt_id']])]);
					exit;
				}
				else
				{
					$model->addError('usr_email', "User already exist");
				}
			}
			$agentId = $_POST['agt_id'];
			$result	 = [];
			foreach ($model->getErrors() as $attribute => $errors)
			{
				$result[CHtml::activeId($model, $attribute)] = $errors;
			}
			echo json_encode(['success' => false, 'errors' => $result]);
			exit;
		}
		$this->renderPartial('addnewuser', ['model' => $model, 'agt_id' => $agentId], false, true);
	}

	public function actionMarkupadd()
	{
		$this->pageTitle = "Add Markup";
		$id				 = Yii::app()->request->getParam('id');
		$cpid			 = Yii::app()->request->getParam('cpid');
		$model			 = new ChannelPartnerMarkup('insert');
		$oldData		 = false;
		if ($id > 0)
		{
			$model	 = ChannelPartnerMarkup::model()->findByPk($id);
			$remark	 = $model->cpm_log;
			$oldData = $model->attributes;
		}
		else if ($cpid > 0)
		{
			$model->cpm_agent_id = $cpid;
		}
		if (isset($_POST['ChannelPartnerMarkup']))
		{
			$model->attributes	 = Yii::app()->request->getParam('ChannelPartnerMarkup');
			$newData			 = $model->attributes;

//$dateTime	 = date('Y-m-d H:i:s');
//$user		 = Yii::app()->user->getId();

			if ($model->cpm_from_date != '' && $model->cpm_to_date != '')
			{
				$fromdate				 = date('Y-m-d', strtotime($model->cpm_from_date));
				$fromtime				 = date('H:i:s', strtotime('00:00:00'));
				$model->cpm_from_date	 = $fromdate . " " . $fromtime;
				$todate					 = date('Y-m-d', strtotime($model->cpm_to_date));
				$totime					 = date('H:i:s', strtotime('23:59:59'));
				$model->cpm_to_date		 = $todate . " " . $totime;
				if ($model->validate())
				{
					/* $model->cpm_from_date		 = $fromdate . " " . $fromtime;
					  $model->cpm_to_date		 = $todate . " " . $totime;
					  $model->cpm_source_zone		 = $_REQUEST['cpm_source_zone'];
					  $model->cpm_destination_zone	 = $_REQUEST['cpm_destination_zone'];
					  $new_remark			 = $model->cpm_value;
					  if ($new_remark != '')
					  {
					  if (is_string($remark))
					  {
					  $newcomm = CJSON::decode($remark);
					  if ($remark != '' && CJSON::decode($remark) == '')
					  {
					  $newcomm = array(array(0 => $user, 1 => $model->cpm_created, 2 => $remark));
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
					  while (count($newcomm) >= 5)
					  {
					  array_pop($newcomm);
					  }
					  array_unshift($newcomm, array(0 => $user, 1 => $dateTime, 2 => $new_remark));
					  $model->cpm_log = CJSON::encode($newcomm);
					  } */
					$model->cpm_log = $model->addLog($oldData, $newData);

					$model->save();
					Yii::app()->user->setFlash('success', "Markup Added Successfully");
					$this->redirect('markuplist');
				}
			}
			else
			{
				Yii::app()->user->setFlash('error', "Date range can not be blank");
			}
		}
		$this->render('markupadd', array('model' => $model));
	}

	public function actionMarkuplog()
	{
		$id			 = Yii::app()->request->getParam('id');
		$logList	 = ChannelPartnerMarkup::model()->getMarkupLog($id);
		$modelList	 = new CArrayDataProvider($logList, array('pagination' => array('pageSize' => 10),));
		$models		 = $modelList->getData();
		$this->renderPartial('markuplog', array('lmodel' => $models, 'usersList' => $modelList), false, true);
	}

	/**
	 * This function is used for fetching the markup list
	 */
	public function actionMarkuplist()
	{
		$this->pageTitle = "Channel Partner Markup List";
		$model			 = new ChannelPartnerMarkup();
		$model->scenario = "search";

		$requestInstance = Yii::app()->request;

		if (isset($_REQUEST['ChannelPartnerMarkup']))
		{
			$model->attributes			 = $requestInstance->getParam('ChannelPartnerMarkup');
			$model->cpm_source_zone		 = $requestInstance->getParam('cpm_source_zone');
			$model->cpm_destination_zone = $requestInstance->getParam('cpm_destination_zone');

			if ($model->cpm_from_date != '')
			{
				$model->cpm_from_date = DateTimeFormat::DatePickerToDate($model->cpm_from_date);
			}
			if ($model->cpm_to_date != '')
			{
				$model->cpm_to_date = DateTimeFormat::DatePickerToDate($model->cpm_to_date);
			}
		}

		//Default All fetch
		$dataProvider = $model->search();
		if ($model->cpm_from_date != '')
		{
			$model->cpm_from_date = DateTimeFormat::DateToDatePicker($model->cpm_from_date);
		}
		if ($model->cpm_to_date != '')
		{
			$model->cpm_to_date = DateTimeFormat::DateToDatePicker($model->cpm_to_date);
		}

		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$this->render('markuplist', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionMarkupdelete()
	{
		$id = Yii::app()->request->getParam('id');
		if ($id > 0)
		{
			$model				 = ChannelPartnerMarkup::model()->findByPk($id);
			$model->cpm_active	 = 0;
			$model->save();
			$this->redirect('markuplist');
		}
	}

	public function actionUpdatebooking()
	{
		$this->pageTitle = "Agent List";
		$model			 = new Booking('partnerupdate');
		if (isset($_REQUEST['Booking']))
		{
			$model->attributes = Yii::app()->request->getParam('Booking');
		}

		$dataProvider = $model->partnerBookingList();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('booklist', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionExportbookings()
	{
		$model = new Booking();
		if (isset($_REQUEST['export1']))
		{
			$arr				 = $_POST;
			$model->attributes	 = $arr;
		}

		$datval	 = date('Ymdhis');
		$rows	 = $model->partnerBookingList(true);
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename='Exported_PartnerBookings_" . $datval . ".csv'");
		header("Pragma: no-cache");
		header("Expires: 0");

		$file = fopen('php://output', 'w');
		fputcsv($file, [
			'Booking ID',
			'HashCode',
			'From City',
			'To City',
			'Pickup Date/Time',
			'Booking Date/Time',
			'Base Amount',
			'Driver Allowance',
			'Discount Amount',
			'Additional Amount',
			'Extra Charge',
			'GST',
			'Toll Tax',
			'State Tax',
			'Parking Charge',
			'Total Amount'
		]);
		foreach ($rows as $row)
		{
			$hash	 = Yii::app()->shortHash->hash($row['bkg_id']);
			$row1	 = [
				$row['bkg_booking_id'],
				$hash,
				$row['from_city'],
				$row['to_city'],
				$row['bkg_pickup_date'],
				$row['bkg_create_date'],
				$row['bkg_base_amount'],
				$row['bkg_driver_allowance_amount'],
				$row['bkg_discount_amount'],
				$row['bkg_additional_charge'],
				$row['bkg_extra_km_charge'],
				$row['bkg_service_tax'],
				$row['bkg_toll_tax'],
				$row['bkg_state_tax'],
				$row['bkg_parking_charge'],
				$row['bkg_total_amount']
			];
			fputcsv($file, $row1);
		}

		return $file;
	}

	public function actionImportbookings()
	{
		$userInfo	 = UserInfo::getInstance();
		$partnerId	 = $_POST["partnerId"];
		if ($partnerId > 0)
		{
			try
			{
				if (isset($_POST["import"]))
				{

					$processedBookingsArr	 = [];
					$errorBookings			 = [];
					$updatedBookings		 = [];
					$nochangeArr			 = [];
					$success				 = false;
					$fileName				 = $_FILES["file"]["tmp_name"];
					if ($_FILES["file"]["size"] > 0)
					{
						$file	 = fopen($fileName, "r");
						$i		 = 0;
						$count	 = 0;
						while (($getData = fgetcsv($file, 10000, ",")))
						{

							if ($count > 0)
							{
								$trans = DBUtil::beginTransaction();
								try
								{

									$bkg_booking_id = $getData[0];
									if (trim($bkg_booking_id) == '')
									{
										$success = DBUtil::commitTransaction($trans);
										continue;
									}
									$model = Booking::model()->getByCode($bkg_booking_id);

									if (!$model)
									{
										$success = DBUtil::commitTransaction($trans);
										continue;
									}
									$invModel					 = $model->bkgInvoice;
									$processedBookingsArr[]		 = $model->bkg_booking_id;
									$processingBookingPartner	 = $model->bkg_agent_id;
									$hash						 = $getData[1];
									$from_city					 = $getData[2];
									$to_city					 = $getData[3];
									$bkg_pickup_date			 = $getData[4];
									$bkg_create_date			 = $getData[5];
									$bkg_base_amount			 = $getData[6];
									$bkg_driver_allowance_amount = $getData[7];
									$bkg_discount_amount		 = $getData[8];
									$bkg_additional_charge		 = $getData[9];
									$bkg_extra_km_charge		 = $getData[10];
									$bkg_service_tax			 = $getData[11];
									$bkg_toll_tax				 = $getData[12];
									$bkg_state_tax				 = $getData[13];
									$bkg_parking_charge			 = $getData[14];
									$bkg_total_amount			 = $getData[15];

									$bkgIdUnHashed	 = Yii::app()->shortHash->unHash($hash);
									$oldInv			 = clone $invModel;

//							$newModel								 = new BookingInvoice();
									$invModel->bkg_base_amount				 = $bkg_base_amount;
									$invModel->bkg_driver_allowance_amount	 = $bkg_driver_allowance_amount;
									$invModel->bkg_discount_amount			 = $bkg_discount_amount;
									$invModel->bkg_additional_charge		 = $bkg_additional_charge;
									$invModel->bkg_extra_km_charge			 = $bkg_extra_km_charge;
									$invModel->bkg_service_tax				 = $bkg_service_tax;
									$invModel->bkg_toll_tax					 = $bkg_toll_tax;
									$invModel->bkg_state_tax				 = $bkg_state_tax;
									$invModel->bkg_parking_charge			 = $bkg_parking_charge;
									$invModel->populateAmount();
									if ($bkg_total_amount == $invModel->bkg_total_amount && $bkg_service_tax == $invModel->bkg_service_tax && $bkgIdUnHashed == $invModel->biv_bkg_id && $processingBookingPartner == $partnerId
									)
									{
										$oldData = $oldInv->attributes;
										$newData = $invModel->attributes;
										if ($invModel->save())
										{

											$getDifference		 = array_diff_assoc($newData, $oldData); // $this->getDifference($oldData, $newData);
											$getOldDifference	 = array_diff_assoc($oldData, $newData);
											if ($getOldDifference && $getDifference)
											{
												$updatedBookings[]	 = $model->bkg_booking_id;
												$changesForLog		 = " Old Values: " . $model->getModificationMSG($getOldDifference, 'log') .
														" => New Values: " . $model->getModificationMSG($getDifference, 'log');
												$logDesc			 = "Booking Modified by Bulk Upload :: ";
												$desc				 = $logDesc . $changesForLog;
												BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, BookingLog::BOOKING_MODIFIED, $oldModel, $params);
											}
											else
											{
												$nochangeArr[] = $model->bkg_booking_id;
											}
										}
									}
									else
									{
										$errorBookings[] = $model->bkg_booking_id;
									}
									$success = DBUtil::commitTransaction($trans);
									$i++;
								}
								catch (Exception $e)
								{
									DBUtil::rollbackTransaction($trans);
									throw new Exception($e->getMessage());
								}
							}
							$count++;
						}
						if ($success == true)
						{
							$totalRows	 = $i;
							$type		 = "success";
							$processed	 = implode(', ', $processedBookingsArr);
							$updated	 = implode(', ', $updatedBookings);
							$error		 = implode(', ', $errorBookings);
							$nochange	 = implode(', ', $nochangeArr);
							echo $message	 = $totalRows . "&nbsp;   Bookings uploaded.";
							echo "<br> <br>";
							echo "<u>Summary :</u> ";
							echo "<br> <br>";
							echo "Uploaded  : $processed";
							echo "<br> <br>";
							echo "<span style='color:#ff0000'>Error in data processing  : $error </span>";
							echo "<br> <br>";
							echo "Updated Successfully : $updated";
							echo "<br> <br>";
							echo "No Change : $nochange";

							echo "<br> <br>";
							echo CHtml::link("Upload more ", Yii::app()->createUrl("admin/agent/updatebooking"));
						}
						else
						{
							$type	 = "error";
							$message = "Problem in Importing CSV Data.";
							throw new Exception("Problem in Importing CSV Data ");
						}
					}
				}
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
			}
		}
		else
		{
			echo "Select the partner before uploading";
			echo "<br> <br>";
			echo CHtml::link("Upload more ", Yii::app()->createUrl("admin/agent/updatebooking"));
		}
	}

	/**
	 * @deprecated since action Showlog
	 */
	public function actionShowlog_old()
	{
		$agentAttr	 = Agents::model()->attributeLabels();
		$agtid		 = Yii::app()->request->getParam('agtid');
		$logList	 = Agents::model()->getAgentLog($agtid);
		$modelList	 = new CArrayDataProvider($logList, array('pagination' => array('pageSize' => 5)));
		$models		 = $modelList->getData();
		$this->renderPartial('log', ['lmodel' => $models, 'usersList' => $modelList, 'agentAttr' => $agentAttr], false, true);
	}

	public function actionShowlog()
	{
		$agtid									 = Yii::app()->request->getParam('agtid');
		$model									 = new Agents();
		$dataProvider							 = $model->getByAgentId($agtid);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->renderPartial('log', ['dataProvider' => $dataProvider], false, true);
	}

	public function actionLedgerpdf()
	{
		/* var $model VendorTransactions */
		$model = new PaymentGateway();
		if (isset($_REQUEST['PaymentGateway']))
		{
			$model->attributes	 = Yii::app()->request->getParam('PaymentGateway');
			$agentId			 = $_REQUEST['PaymentGateway']['apg_trans_ref_id'];
			$fromDate1			 = trim($_POST['PaymentGateway']['ven_from_date']);
			$toDate1			 = trim($_POST['PaymentGateway']['ven_to_date']);
			$fromDate			 = DateTimeFormat::DatePickerToDate($fromDate1);
			$toDate				 = DateTimeFormat::DatePickerToDate($toDate1);
			$this->GenerateLedgerPdf($agentId, $fromDate, $toDate);
		}
	}

	public function GenerateLedgerPdf($agentId, $fromDate, $toDate, $email = 0)
	{
		$data	 = [];
		$data	 = AccountTransDetails::model()->getAgentLedgerData($agentId, $fromDate, $toDate);

		$agentList					 = $data['agentList'];
		$agentAmount				 = $data['agentAmount'];
		$openingAmount				 = $data['openingAmount'];
		$company					 = $data['agentList'][0]['agt_company'];
		$email						 = $data['agentList'][0]['agt_email'];
		$phone						 = $data['agentList'][0]['agt_phone'];
		$fromDate					 = $data['fromDate'];
		$toDate						 = $data['toDate'];
		$lastPaymentReceived		 = $data['lastTransaction'];
		$date1						 = date('ymd', strtotime($fromDate));
		$date2						 = date('ymd', strtotime($toDate));
		$hash1						 = Yii::app()->shortHash->hash($date1);
		$hash2						 = Yii::app()->shortHash->hash($date2);
		$invoiceNo					 = $agentId . '-' . $hash1 . '-' . $hash2;
		$html2pdf					 = Yii::app()->ePdf->mPdf();
		$css						 = file_get_contents(Yii::getPathOfAlias('webroot.assets.css') . '/vendorpdf.css');
		$html2pdf->writeHTML($css, 1);
		$html2pdf->setAutoTopMargin	 = 'stretch';
		$html2pdf->setHTMLHeader('<table border="0" cellpadding="0" cellspacing="0" width="702" class="no-border header">
			<tr>	 
	 <td  style="float: left">
					<img src="http://www.aaocab.com/images/logo2_outstation.png" style="height: 60px"/></td>
					<td  style="text-align: right;font-size: 16pt">I N V O I C E</td>				    
	</tr></table><hr style="margin-bottom: 0  ">');
		$html2pdf->setHTMLFooter(
				'<table id="footer" style="width: 100%"> 
				<tr>
				<td style="text-align: center">
				<hr>www.aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;info@aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9051 877 000
				</td></tr></table><div style="float: right;text-align: right;font-size: 8pt">Page {PAGENO}/{nbpg}</div>');

		$html2pdf->writeHTML($this->renderPartial('generateledgerpdf', array(
					'agentList'				 => $agentList,
					'agentAmount'			 => $agentAmount,
					'openingAmount'			 => $openingAmount,
					'companyName'			 => $company,
					'email'					 => $email,
					'phone'					 => $phone,
					'fromDate'				 => $fromDate,
					'toDate'				 => $toDate,
					'lastPaymentReceived'	 => $lastPaymentReceived,
					'invoiceNo'				 => $invoiceNo
						), true));
		$filename = $agentId . '-Account Ledger-' . date('Y-m-d') . '.pdf';
//		if ($email == 1)
//		{
//		
//			$fileBasePath	 = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'agents';
//			if (!is_dir($fileBasePath))
//			{
//				mkdir($fileBasePath);
//			}
//			$filePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'agents' . DIRECTORY_SEPARATOR . $agentId;
//			if (!is_dir($filePath))
//			{
//				mkdir($filePath);
//			}
//			$file = $filePath . DIRECTORY_SEPARATOR . $filename;
//			$html2pdf->Output($file, 'F');
//			return $file;
//		}
//		else
//		{
		$html2pdf->Output($filename, 'D');
//		}
	}

	public function actionBookingpreferences()
	{

		$agtId = Yii::app()->request->getParam('agt_id');
		if ($agtId > 0)
		{
			$agentModel = Agents::model()->findByPk($agtId);
			echo json_encode(['preferences' =>
				[
					'agt_otp_required'			 => $agentModel->agt_otp_required,
					'agt_driver_app_required'	 => $agentModel->agt_driver_app_required,
					'agt_water_bottles_required' => $agentModel->agt_water_bottles_required,
					'agt_is_cash_required'		 => $agentModel->agt_is_cash_required,
					'agt_duty_slip_required'	 => $agentModel->agt_duty_slip_required,
					'agt_pref_req_other'		 => $agentModel->agt_pref_req_other
				]
			]);
			Yii::app()->end();
		}
		else
		{
			echo json_encode(['preferences' =>
				[
					'agt_otp_required'			 => 1,
					'agt_driver_app_required'	 => 1,
					'agt_water_bottles_required' => 0,
					'agt_is_cash_required'		 => 0,
					'agt_duty_slip_required'	 => 0,
					'agt_pref_req_other'		 => null
				]
			]);
			Yii::app()->end();
		}
	}

	/**
	 * @deprecated
	 * @deprecated 1.0.0
	 * @deprecated not recommended.
	 * @deprecated function replaced by AGENT::CheckLatestCreditLimit().
	 */
	public function actionCheckCreditLimit()
	{
		$agtId				 = Yii::app()->request->getParam('agtId');
		$agentCreditAmount	 = Yii::app()->request->getParam('agentCreditAmount') | 0;
		$success			 = 'true';
		$isRechargeAccount	 = AccountTransDetails::model()->checkCreditLimit($agtId, '', '', $agentCreditAmount, '', 3, false);
		if ($isRechargeAccount)
		{
			$success = 'false';
		}
		echo $success;
	}

	public function actionCheckLatestCreditLimit()
	{
		$agtId				 = Yii::app()->request->getParam('agtId');
		$agentCreditAmount	 = Yii::app()->request->getParam('agentCreditAmount') | 0;
		$success			 = true;
		$isRechargeAccount	 = AccountTransDetails::model()->checkCreditStat($agtId, $agentCreditAmount);

		if ($isRechargeAccount)
		{
			$success = false;
		}
		$data = ['success' => $success];

		echo json_encode($data);
	}

	public function actionGetfutureBooking()
	{
		$agtId	 = Yii::app()->request->getParam('agt_id');
		$sql	 = "SELECT 
						bkg.bkg_id,
						bkg.bkg_booking_id,
						bkg.bkg_agent_id,
						bkg.bkg_pickup_date,
						bkg_status,
			adt_amount
				FROM account_trans_details
            INNER JOIN account_transactions ON act_id=adt_trans_id AND adt_ledger_id = 26
            INNER JOIN booking bkg ON bkg.bkg_id = account_transactions.act_ref_id AND account_transactions.act_type = 1
            WHERE act_active=1 AND adt_active=1 AND account_transactions.act_date > DATE_ADD(NOW(), INTERVAL 12 HOUR)
             AND bkg.bkg_agent_id = $agtId
            ";

		$data = DBUtil::queryAll($sql, DBUtil::SDB());
		if ($data)
		{
			echo '<table>';
			echo '<th>Booking ID</th><th>Agent ID</th><th>Pickup Date</th><th>Credit</th><th>Status</th>';
			foreach ($data as $value)
			{

				echo '<tr>';
				echo '<td>' . $value['bkg_booking_id'] . '</td><td>&nbsp;&nbsp;&nbsp;' . $value['bkg_agent_id'] . '</td><td>' . $value['bkg_pickup_date'] . '</td><td>&nbsp;&nbsp;&nbsp;&nbsp;' . $value['adt_amount'] . '</td><td>&nbsp;&nbsp;&nbsp;&nbsp;' . $value['bkg_status'];
				echo '</tr>';
			}
			echo '</table>';
		}
		else
		{
			echo 'Future Booking not found';
		}
		exit();
	}

	public function actionWallet()
	{
		$this->pageTitle		 = "Agent Accounts";
		$agtId					 = Yii::app()->request->getParam('agtId');
		$transDate1				 = '';
		$transDate2				 = '';
		$model					 = new PaymentGateway();
		$model->apg_trans_ref_id = $agtId;
		$model->scenario		 = "ledgerbooking";

		$userInfo = UserInfo::getInstance();

		if (isset($_REQUEST['PaymentGateway']) && isset($_POST['addnewtransaction']) && !isset($_POST['bookingSearch']) && (trim($_REQUEST['PaymentGateway']['apg_amount'])) != "" && (trim($_REQUEST['PaymentGateway']['apg_ledger_id']) != ""))
		{
			$model->attributes				 = $_REQUEST['PaymentGateway'];
			$model->apg_date				 = ($model->apg_date != '') ? DateTimeFormat::DatePickerToDate($model->apg_date) . " " . "00:00:00" : new CDbExpression("NOW()");
			$date							 = $model->apg_date;
			$model->apg_complete_datetime	 = new CDbExpression("NOW()");
			$valArr							 = ['TRANSACTION_TYPE'	 => $model->apg_banktrans_type, 'BANK_NAME'			 => trim($_POST['PaymentGateway']['bank_name']),
				'BANK_IFSC_CODE'	 => trim($_POST['PaymentGateway']['bank_ifsc']), 'BANK_BRANCH_NAME'	 => trim($_POST['PaymentGateway']['bank_branch']),
				'CHEQUE_NUMBER'		 => trim($_POST['PaymentGateway']['bank_chq_no']),
				'CHEQUE_DATE'		 => (trim($_POST['PaymentGateway']['bank_chq_dated']) == '') ? '' : DateTimeFormat::DatePickerToDate(trim($_POST['PaymentGateway']['bank_chq_dated'])),
				'TRANSACTION_MODE'	 => $model->apg_mode, 'DESCRIPTION'		 => $model->apg_remarks];
			$arr							 = array_filter($valArr);
			$model->apg_response_details	 = json_encode($arr, true);
			$bankRefId						 = $agtId;
			$refType						 = NULL;
			$ledgerType						 = Accounting::LI_PARTNER;
			$accType						 = Accounting::AT_PARTNER;
			$bankLedgerId					 = $model->apg_ledger_id;
			$remarks						 = $model->apg_remarks;
			$paymentTypeId					 = PaymentType::model()->payentTypeFromLedger($model->apg_ledger_id);

			//if (in_array($bankLedgerId, [Accounting::LI_BANK, Accounting::LI_HDFC, Accounting::LI_ICICI, Accounting::LI_PAYTM, Accounting::LI_PAYU, Accounting::LI_EBS, Accounting::LI_FREECHARGE, Accounting::LI_MOBIKWIK, Accounting::LI_ZZAAKPAY]))
			if (in_array($model->apg_ledger_id, Accounting::getOnlineLedgers(false)))
			{
				$paymentGateway	 = PaymentGateway::model()->addAmountForOnlineLedger($model, $agtId, $paymentTypeId, $bankLedgerId, $accType, UserInfo::getInstance());
				$bankRefId		 = $paymentGateway->apg_id;
				$refType		 = Accounting::AT_ONLINEPAYMENT;
				$remarks		 = $paymentGateway->apg_remarks;
			}
			if ($_POST['PaymentGateway']['operator_id'] == 0)
			{
				if ($bankLedgerId == Accounting::LI_COMPENSATION)
				{
					$model->apg_amount = -1 * $model->apg_amount;
				}
				$addPartnerAmount = AccountTransactions::model()->addAmountGozoReceiver($model, $agtId, $bankRefId, $remarks, $refType, $accType, $ledgerType, $date, $agtId);
			}
			else
			{
				$addPartnerAmount = AccountTransactions::model()->addAmountGozoPaid($model, $agtId, $bankRefId, $remarks, $refType, $accType, $ledgerType, $date);
			}
			$model			 = new PaymentGateway();
			$model->scenario = "ledgerbooking";
		}
		$transDate1					 = $model->trans_create_date1	 = date('Y-m-d', strtotime('today - 2 days'));
		$transDate2					 = $model->trans_create_date2	 = date('Y-m-d', strtotime('today'));
		if ($_REQUEST['PaymentGateway']['trans_create_date1'] != '' && $_REQUEST['PaymentGateway']['trans_create_date2'] != '')
		{
			$transDate1					 = $_REQUEST['PaymentGateway']['trans_create_date1'];
			$transDate2					 = $_REQUEST['PaymentGateway']['trans_create_date2'];
			$model->trans_create_date1	 = $transDate1;
			$model->trans_create_date2	 = $transDate2;
			$model->apg_ledger_type_id	 = $_REQUEST['PaymentGateway']['apg_ledger_type_id'];
		}
		$recordSet							 = AccountTransDetails::walletTransactionList($agtId, $transDate1, $transDate2, $model->apg_ledger_type_id);
		$totalRecords						 = count($recordSet);
		$agentList							 = new CArrayDataProvider($recordSet, array('pagination' => array('pageSize' => 500)));
		$agentList->getPagination()->params	 = array_filter($_GET + $_POST);
		$agentList->getSort()->params		 = array_filter($_GET + $_POST);
		$agentModels						 = $agentList->getData();
		//$agentAmount						 = AccountTransDetails::accountTotalSummary($agtId, $transDate1, $transDate2);

		$tillDate	 = (strtotime($transDate2 . " 23:59:59") > strtotime(date("Y-m-d H:i:s"))) ? date("Y-m-d H:i:s") : $transDate2 . " 23:59:59";
		$agentAmount = AccountTransDetails::accountTotalSummary($agtId, '', '', '', $tillDate);
		$agentModel	 = Agents::model()->findByPk($agtId);
		//$date		 = date('Y-m-d H:i:s', strtotime('+12 hour'));
		//$walletBalance	= AccountTransactions::checkPartnerWalletBalance($agtId, $date);
		$getBalance	 = PartnerStats::getBalance($agtId);
		$this->render('wallet', ['model' => $model, 'agentList' => $agentList, 'agentmodels' => $agentModels, 'agentAmount' => $agentAmount, 'totalRecords' => $totalRecords, 'agentModel' => $agentModel, 'getBalance' => $getBalance]);
	}

	public function actionWalletpdf()
	{
		/* var $model VendorTransactions */
		$model = new PaymentGateway();
		if (isset($_REQUEST['PaymentGateway']))
		{
			$model->attributes	 = Yii::app()->request->getParam('PaymentGateway');
			$agentId			 = $_REQUEST['PaymentGateway']['apg_trans_ref_id'];
			$fromDate1			 = trim($_POST['PaymentGateway']['ven_from_date']);
			$toDate1			 = trim($_POST['PaymentGateway']['ven_to_date']);
			$fromDate			 = DateTimeFormat::DatePickerToDate($fromDate1);
			$toDate				 = DateTimeFormat::DatePickerToDate($toDate1);
			$this->GenerateWalletPdf($agentId, $fromDate, $toDate);
		}
	}

	public function GenerateWalletPdf($agentId, $fromDate, $toDate, $email = 0)
	{
		$data	 = [];
		$data	 = AccountTransDetails::getPartnerWalletData($agentId, $fromDate, $toDate);

		$agentList					 = $data['agentList'];
		$agentAmount				 = $data['agentAmount'];
		$openingAmount				 = $data['openingAmount'];
		$company					 = $data['agentList'][0]['agt_company'];
		$email						 = $data['agentList'][0]['agt_email'];
		$phone						 = $data['agentList'][0]['agt_phone'];
		$fromDate					 = $data['fromDate'];
		$toDate						 = $data['toDate'];
		$lastPaymentReceived		 = $data['lastTransaction'];
		$date1						 = date('ymd', strtotime($fromDate));
		$date2						 = date('ymd', strtotime($toDate));
		$hash1						 = Yii::app()->shortHash->hash($date1);
		$hash2						 = Yii::app()->shortHash->hash($date2);
		$invoiceNo					 = $agentId . '-' . $hash1 . '-' . $hash2;
		$html2pdf					 = Yii::app()->ePdf->mPdf();
		$css						 = file_get_contents(Yii::getPathOfAlias('webroot.assets.css') . '/vendorpdf.css');
		$html2pdf->writeHTML($css, 1);
		$html2pdf->setAutoTopMargin	 = 'stretch';
		$html2pdf->setHTMLHeader('<table border="0" cellpadding="0" cellspacing="0" width="702" class="no-border header">
			<tr>	 
	 <td  style="float: left">
					<img src="http://www.aaocab.com/images/logo2_outstation.png" style="height: 60px"/></td>
					<td  style="text-align: right;font-size: 16pt">I N V O I C E</td>				    
	</tr></table><hr style="margin-bottom: 0  ">');
		$html2pdf->setHTMLFooter(
				'<table id="footer" style="width: 100%"> 
				<tr>
				<td style="text-align: center">
				<hr>www.aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;info@aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9051 877 000
				</td></tr></table><div style="float: right;text-align: right;font-size: 8pt">Page {PAGENO}/{nbpg}</div>');

		$html2pdf->writeHTML($this->renderPartial('generateledgerpdf', array(
					'agentList'				 => $agentList,
					'agentAmount'			 => $agentAmount,
					'openingAmount'			 => $openingAmount,
					'companyName'			 => $company,
					'email'					 => $email,
					'phone'					 => $phone,
					'fromDate'				 => $fromDate,
					'toDate'				 => $toDate,
					'lastPaymentReceived'	 => $lastPaymentReceived,
					'invoiceNo'				 => $invoiceNo
						), true));
		$filename = $agentId . '-Wallet Ledger-' . date('Y-m-d') . '.pdf';

		$html2pdf->Output($filename, 'D');
	}

	public function actionGenerateInvoice()
	{
		$Ids = Yii::app()->request->getParam('bkIds');
		if (isset($Ids) && $Ids != '')
		{
			$pdfFiles	 = [];
			$BookingIds	 = explode(',', $Ids);
			foreach ($BookingIds as $value)
			{
				if ($value != '')
				{
					$bkgId				 = $value;
					$model				 = Booking::model()->findByPk($bkgId);
					$invoiceList		 = Booking::model()->getInvoiceByBooking($bkgId);
					$totPartnerCredit	 = AccountTransDetails::getTotalPartnerCredit($bkgId);
					$totAdvance			 = PaymentGateway::model()->getTotalAdvance($bkgId);
					$totAdvanceOnline	 = PaymentGateway::model()->getTotalOnlinePayment($bkgId);

					$html2pdf					 = Yii::app()->ePdf->mPdf();
					$css						 = file_get_contents(Yii::getPathOfAlias('webroot.assets.css') . '/vendorpdf.css');
					$html2pdf->writeHTML($css, 1);
					$html2pdf->setAutoTopMargin	 = 'stretch';

					$html2pdf->setHTMLFooter('<table id="footer" style="width: 100%"> <tr><td style="text-align: center"><hr>www.aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;info@aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9051 877 000</td></tr></table>');
					$htmlView	 = $this->renderPartial('invoice/view', array(
						'invoiceList'		 => $invoiceList,
						'totPartnerCredit'	 => $totPartnerCredit,
						'totAdvance'		 => $totAdvance,
						'totAdvanceOnline'	 => $totAdvanceOnline,
						'isPDF'				 => true,
						'isCommand'			 => false
							), true);
					$html2pdf->writeHTML($htmlView);
					$filename	 = $model->bkg_booking_id . '_' . date('Ymd', strtotime($model->bkg_pickup_date)) . '.pdf';

					$filePath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'invoice';
					//$filePath	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'uploads/invoice';
					if (!is_dir($filePath))
					{
						mkdir($filePath);
					}
					$file		 = $filePath . DIRECTORY_SEPARATOR . $filename;
					$html2pdf->Output($file, 'F');
					$pdfFiles[]	 = $filename;
					$pdfPath[]	 = $file;
				}
			}
			$zipname = 'booking_invoice.zip';
			$zip	 = new ZipArchive;
			if ($zip->open($zipname, ZipArchive::CREATE) === TRUE)
			{
				foreach ($pdfFiles as $pdfName)
				{
					//$zip->addFile($file);
					$zip->addFromString(basename($filePath . "/" . $pdfName), file_get_contents($filePath . "/" . $pdfName));
				}
				$zip->close();
			}
			///Then download the zipped file.
			header('Content-Type: application/zip');
			header('Content-disposition: attachment; filename=' . $zipname);
			//header('Content-Length: ' . filesize($zipname));
			readfile($zipname);
			unlink($zipname);
			foreach ($pdfPath as $doc)
			{
				unlink($doc);
			}
		}
	}

	public function actionAddPayment()
	{
		$this->pageTitle = "Add Payment";
		$bkgId			 = Yii::app()->request->getParam('id');
		$drvStatData	 = Yii::app()->request->getParam('DriverStats');
		$bkgInvData		 = Yii::app()->request->getParam('BookingInvoice');
//		$bkgId = '1883972';
		$model			 = Booking::model()->findByPk($bkgId);
		$drvStatArr		 = DriverStats::model()->getbyDriverId($model->bkgBcb->bcb_driver_id);
		$drvStatModel	 = DriverStats::model()->findByPk($drvStatArr['drs_id']);
		if ($drvStatModel == '' || $drvStatModel == NULL)
		{
			$drvStatsModel = new DriverStats();
		}
		if ($drvStatData != '')
		{
			$drvStatModel->drv_last_loc_lat	 = $drvStatData['drv_last_loc_lat'];
			$drvStatModel->drv_last_loc_long = $drvStatData['drv_last_loc_long'];
			$drvStatModel->save();
		}
		if ($bkgInvData != '')
		{
			$model->bkgInvoice->bkg_extra_km			 = $bkgInvData['bkg_extra_km'];
			$model->bkgInvoice->bkg_extra_km_charge		 = $bkgInvData['bkg_extra_km_charge'];
			$model->bkgInvoice->bkg_extra_min			 = $bkgInvData['bkg_extra_min'];
			$model->bkgInvoice->bkg_extra_per_min_charge = $bkgInvData['bkg_extra_per_min_charge'];
			$model->bkgInvoice->bkg_total_amount		 = $bkgInvData['bkg_total_amount'];
			$model->bkgInvoice->bkg_advance_amount		 = $bkgInvData['bkg_advance_amount'];
			$model->bkgInvoice->save();
		}
		$result	 = [];
		$result	 = CActiveForm::validate($model);
		if ($result != "[]")
		{
			$return = ['success' => false, 'errors' => CJSON::decode($result)];
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('addPayment', array('model' => $model, 'drvStatModel' => $drvStatModel, 'success' => $success), null, $outputJs);
	}

	public function actionAddPartnerCommission()
	{
		$model	 = new PartnerRuleCommission();
		$agtId	 = Yii::app()->request->getParam('agtId');
		$ruleId	 = Yii::app()->request->getParam('ruleId');
		$req	 = Yii::app()->request;
		$success = false;
		if ($ruleId > 0)
		{
			$model = PartnerRuleCommission::model()->findByPk($ruleId);
		}
		if ($req->getParam('PartnerRuleCommission'))
		{
			$arr = $req->getParam('PartnerRuleCommission');
			if ($arr['prc_commission_value'] >= 0)
			{
				$model->prc_booking_type	 = $arr['prc_booking_type'];
				$model->prc_commission_type	 = $arr['prc_commission_type'];
				$model->prc_commission_value = $arr['prc_commission_value'];
				if ($ruleId == '')
				{
					$model->prc_agent_id	 = $agtId;
					$model->prc_created_at	 = DBUtil::getCurrentTime();
				}

				if ($model->save())
				{
					$userInfo			 = UserInfo::getInstance();
					$result				 = [];
					$success			 = true;
					$result['massage']	 = "Partner Commission Rules Add Successfully";
					if ($ruleId > 0)
					{
						$result['massage'] = "Partner Commission Rules Update Successfully";
					}
					$result['success'] = $success;
					AgentLog::model()->createLog($agtId, $result['massage'], $userInfo, AgentLog::AGENT_PARTNER_COMMISSION, false, false);
				}
				else
				{
					$result				 = [];
					$result['massage']	 = 'Some Error occured';
				}
			}
			else
			{
				$result				 = [];
				$result['massage']	 = 'Commission value should be positive';
			}


			if (Yii::app()->request->isAjaxRequest)
			{
				echo json_encode($result);
				Yii::app()->end();
			}
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('addPartnerCommission', array('model' => $model, 'agtId' => $agtId, 'ruleId' => $ruleId), false, $outputJs);
	}

}
