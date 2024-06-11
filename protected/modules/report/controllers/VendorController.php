<?php

class VendorController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';
	public $email_receipient;
	public $ven_date_type;
	public $ven_to_date;
	public $ven_from_date;

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
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('merge', 'mergevendors', 'AgreementApprovedoc', 'AgreementShowdoc',
					'sociallist', 'louList', 'viewloudetails', 'changestatusloulist', 'unlinksocialaccount',
					'duplicatevendor', 'mergeduplicatevendor', 'safedelete', "linkuser", "link", "tmpRating"),
				'users'		 => array('@'),
			),
			['allow', 'actions' => ['vendorweekly'], 'roles' => ['VendorWeeklyReport']],
			['allow', 'actions' => ['dormantVendor'], 'roles' => ['DormantVendorsReport']],
			['allow', 'actions' => ['profileStrength'], 'roles' => ['VendorProfileStrengthReport']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('vendorweekly', 'dormantVendor'),
				'roles'		 => array('ConfidentialReports'),
			),
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('vendorLockedPayment', 'VendorUsageReport', 'blockedVendor', 'AutoAssignmentTracking',
					'vendorWiseCountBooking', 'stickyVendorCount', 'directAcceptReport', 'louRequired',
					'vendorCancellation', 'compensation', 'dco', 'profileReport', 'lowRatingCabDriver', 'dcoLinkAttached', 'vendorCoins'),
				'roles'		 => array('GeneralReport'),
			),
			['allow', 'actions' => ['vendoraccounts', 'regionperf', 'assignment', 'gNowDisabled'], 'roles' => ['vendorList']],
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index'),
				'users'		 => array('*'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function restEvents()
	{
		$this->onRest('req.cors.access.control.allow.methods', function () {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function ($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			#$ri  = array('/list', '/listDropDown', '/edit', '/editinfo', '/editdoc', "/conAdd");
			$ri	 = array();
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.get.list.render', function () {
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Admins::model()->authorizeAdmin($token);
			if ($result)
			{
				$search_txt	 = Yii::app()->request->getParam('search_txt');
				$page_no	 = (int) Yii::app()->request->getParam('page_no');
				$page_number = ($page_no > 0) ? $page_no : 0;
				$vendorModel = Vendors::model()->getDetailsAdmin($page_number, $total_count = 0, $search_txt);
				$count		 = count(Vendors::model()->getDetailsAdmin($page_number, 0, $search_txt));
				if ($vendorModel != [])
				{
					$success = true;
					$error	 = null;
				}
				else
				{
					$success = false;
					$error	 = "Error occured while fetching list";
				}
				if ($count != 0)
				{
					$pageCount = ceil($count / 20);
				}
			}
			else
			{
				$success = false;
				$errors	 = 'You are not authorized';
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $success,
					'error'			 => $error,
					'model'			 => $vendorModel,
					'count'			 => $count,
					'total_pages'	 => $pageCount
				)
			]);
		});

		$this->onRest('req.get.listDropDown.render', function () {
			$search_txt	 = Yii::app()->request->getParam('search_txt');
			#$vendorModel = Vendors::model()->getDetailsAdminDropDown($search_txt);
			$vendorModel = Vendors::model()->getDetailsAdmin(0, 0, $search_txt);
			$count		 = count($vendorModel);
			if ($vendorModel != [])
			{
				$success = true;
				$error	 = null;
			}
			else
			{
				$success = false;
				$error	 = "Error occured while fetching list";
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'error'		 => $error,
					'model'		 => $vendorModel,
					'count'		 => $count,
				)
			]);
		});

		$this->onRest('req.get.editinfo.render', function () {
			Filter::createLog('9 editinfo ', CLogger::LEVEL_TRACE);
			$errors	 = 'data not found';
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Admins::model()->authorizeAdmin($token);
			if ($result == true)
			{
				$vendorId				 = Yii::app()->request->getParam('vnd_id');
				$data					 = Vendors::model()->getViewDetailbyId($vendorId);
				$alternateContactList	 = Vendors::model()->getContactByVndId($vendorId);
				foreach ($alternateContactList AS $value)
				{
					$alternateContact[] = $value['vnd_contact_number'];
				}
				$data['vnd_alt_contact_number']		 = implode(' , ', $alternateContact);
				$success							 = ($data['vnd_id'] > 0) ? true : false;
				$errors								 = ($data['vnd_id'] > 0) ? '' : $errors;
				$data['vnd_agreement_file_link']	 = '';
				$data['vnd_voter_id_path']			 = '';
				$data['vnd_voter_id_back_path']		 = '';
				$data['vnd_aadhaar_path']			 = '';
				$data['vnd_aadhaar_back_path']		 = '';
				$data['vnd_pan_path']				 = '';
				$data['vnd_pan_back_path']			 = '';
				$data['vnd_licence_path']			 = '';
				$data['vnd_licence_back_path']		 = '';
				$data['vnd_firm_attach']			 = '';
				$data['vnd_agreement_status']		 = '';
				$data['vnd_voter_id_status']		 = '';
				$data['vnd_voter_id_back_status']	 = '';
				$data['vnd_aadhaar_status']			 = '';
				$data['vnd_aadhaar_back_status']	 = '';
				$data['vnd_pan_status']				 = '';
				$data['vnd_pan_back_status']		 = '';
				$data['vnd_licence_status']			 = '';
				$data['vnd_licence_back_status']	 = '';
				$data['vnd_firm_status']			 = '';
				$data['rating']						 = '';

				$venDocs = Document::model()->findAllByVndId($vendorId);

				if (count($venDocs) > 0)
				{

					$agreement_file					 = ($venDocs[0]['vag_soft_path'] != '') ? $venDocs[0]['vag_soft_path'] : $venDocs[0]['vag_digital_agreement'];
					$data['vnd_agreement_file_link'] = $agreement_file;
					$data['vnd_agreement_status']	 = (int) $venDocs[0]['vd_agmt_status'];
					$data['vnd_voter_id_path']		 = $venDocs[0]['votfile_front'];
					if (substr_count($venDocs[0]['votfile_front'], "contact") > 0)
					{
						$data['vnd_voter_id_path'] = parse_url(AttachmentProcessing::ImagePath($venDocs[0]['votfile_front']), PHP_URL_PATH);
					}

					$data['vnd_voter_id_status'] = ($venDocs[0]['votfile_front'] == null) ? '' : $venDocs[0]['votfile_status'];

					$data['vnd_voter_id_back_path'] = $venDocs[0]['votfile_back'];
					if (substr_count($venDocs[0]['votfile_back'], "contact") > 0)
					{
						$data['vnd_voter_id_back_path'] = parse_url(AttachmentProcessing::ImagePath($venDocs[0]['votfile_back']), PHP_URL_PATH);
					}
					$data['vnd_voter_id_back_status']	 = ($venDocs[0]['votfile_back'] == null) ? '' : $venDocs[0]['votfile_status'];
					$data['voter_remarks']				 = $venDocs[0]['voter_remarks'];

					$data['vnd_aadhaar_path'] = $venDocs[0]['adhfile_front'];
					if (substr_count($venDocs[0]['adhfile_front'], "contact") > 0)
					{
						$data['vnd_aadhaar_path'] = parse_url(AttachmentProcessing::ImagePath($venDocs[0]['adhfile_front']), PHP_URL_PATH);
					}


					$data['vnd_aadhaar_status'] = ($venDocs[0]['adhfile_front'] == null) ? '' : $venDocs[0]['adhfile_status'];

					$data['vnd_aadhaar_back_path'] = $venDocs[0]['adhfile_back'];
					if (substr_count($venDocs[0]['adhfile_back'], "contact") > 0)
					{
						$data['vnd_aadhaar_back_path'] = parse_url(AttachmentProcessing::ImagePath($venDocs[0]['adhfile_back']), PHP_URL_PATH);
					}

					$data['vnd_aadhaar_back_status'] = ($venDocs[0]['adhfile_back'] == null) ? '' : $venDocs[0]['adhfile_status'];
					$data['aadhar_remarks']			 = $venDocs[0]['aadhar_remarks'];

					$data['vnd_pan_path'] = $venDocs[0]['panfile_front'];
					if (substr_count($venDocs[0]['panfile_front'], "contact") > 0)
					{
						$data['vnd_pan_path'] = parse_url(AttachmentProcessing::ImagePath($venDocs[0]['panfile_front']), PHP_URL_PATH);
					}

					$data['vnd_pan_status'] = ($venDocs[0]['panfile_front'] == null) ? '' : $venDocs[0]['panfile_status'];

					$data['vnd_pan_back_path'] = $venDocs[0]['panfile_back'];
					if (substr_count($venDocs[0]['panfile_back'], "contact") > 0)
					{
						$data['vnd_pan_back_path'] = parse_url(AttachmentProcessing::ImagePath($venDocs[0]['panfile_back']), PHP_URL_PATH);
					}
					$data['vnd_pan_back_status'] = ($venDocs[0]['panfile_back'] == null) ? '' : $venDocs[0]['panfile_status'];
					$data['pan_remarks']		 = $venDocs[0]['pan_remarks'];

					$data['vnd_licence_path'] = $venDocs[0]['licfile_front'];
					if (substr_count($venDocs[0]['licfile_front'], "contact") > 0)
					{
						$data['vnd_licence_path'] = parse_url(AttachmentProcessing::ImagePath($venDocs[0]['licfile_front']), PHP_URL_PATH);
					}
					$data['vnd_licence_status'] = ($venDocs[0]['licfile_front'] == null) ? '' : $venDocs[0]['licfile_status'];

					$data['vnd_licence_back_path'] = $venDocs[0]['licfile_back'];
					if (substr_count($venDocs[0]['licfile_back'], "contact") > 0)
					{
						$data['vnd_licence_back_path'] = parse_url(AttachmentProcessing::ImagePath($venDocs[0]['licfile_back']), PHP_URL_PATH);
					}
					$data['vnd_licence_back_status'] = ($venDocs[0]['licfile_back'] == null) ? '' : $venDocs[0]['licfile_status'];
					$data['license_remarks']		 = $venDocs[0]['license_remarks'];

					$data['vnd_firm_attach'] = $venDocs[0]['memofile_front'];
					if (substr_count($venDocs[0]['memofile_front'], "contact") > 0)
					{
						$data['vnd_firm_attach'] = parse_url(AttachmentProcessing::ImagePath($venDocs[0]['memofile_front']), PHP_URL_PATH);
					}

					$data['vnd_firm_status'] = ($venDocs[0]['memofile_front'] == null) ? '' : $venDocs[0]['memofile_status'];
					$data['memo_remarks']	 = $venDocs[0]['memo_remarks'];
				}

				// ['0'=> 'Rejected or Upload','1'=> 'Approved','2'=> 'Pending Approval']

				$is_on_file = 0;
				if ($data['vnd_agreement_file_link'] != '' || $data['vnd_agreement_status'] != '')
				{
					switch ($data['vnd_agreement_status'])
					{
						case 0:
							$is_on_file	 = 2;
							break;
						case 1:
							$is_on_file	 = 2;
							break;
						case 2:
							$is_on_file	 = 0;
							break;
					}
				}
				$data['is_on_file'] = (int) $is_on_file;
				if ($vendorId > 0)
				{
					$data['rating'] = VendorStats::fetchRating($vendorId);
				}
				$data['vnd_photo_path'] = $venDocs[0]['vnd_photo_path'];
				if (substr_count($data['vnd_photo_path'], "contact") > 0)
				{
					$data['vnd_photo_path'] = parse_url(AttachmentProcessing::ImagePath($data['vnd_photo_path']), PHP_URL_PATH);
				}
				$data['is_bussiness'] = ($data['ctt_user_type'] == '1' ? 0 : 1);
				if ($data['business_type'] == 1)
				{
					$btype = "Sole Propitership";
				}
				else if ($data['business_type'] == 2)
				{
					$btype = "Partner";
				}
				else if ($data['business_type'] == 3)
				{
					$btype = "Private Limited";
				}
				else if ($data['business_type'] == 4)
				{
					$btype = "Limited";
				}
				else
				{
					$btype = "";
				}
				$data['bussiness_type']	 = $btype;
				$data['first_name']		 = $data['ctt_first_name'];
				$data['last_name']		 = $data['ctt_last_name'];
				$data['vnd_id']			 = $vendorId;
			}
			else
			{
				$success = false;
				$errors	 = 'You are not authorized';
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'data'		 => $data,
				)
			]);
		});

		$this->onRest('req.post.edit.render', function () {
			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result	 = Admins::model()->authorizeAdmin($token);
			if ($result)
			{
				$success			 = false;
				$errors				 = '';
				$process_sync_data	 = Yii::app()->request->getParam('data');
				//$process_sync_data   = '{"data":{"vnd_name":"Ankesh Das","vnd_phone":"9609275499","vnd_email":"baabhishek@gmail.com","vnd_alt_contact_number":"","vnd_address":"Kolaghat ","vnd_voter_no":"6477r84994","vnd_pan_no":"637373883","vnd_aadhaar_no":"63738488","vnd_license_no":"737373737","vnd_city":"34362","vnd_company":"Gozo","vnd_firm_pan":"","vnd_firm_ccin":""},"vendorPic":"0"}';
				$data				 = CJSON::decode($process_sync_data, true);
				$vndId				 = $data['vnd_id'];
				$oldData			 = false;
				$checkAccess		 = Yii::app()->user->checkAccess('vendorAdd');
				if ($checkAccess)
				{
					/* @var $model Vendors */
					$model	 = Vendors::model()->findByPk($vndId);
					$oldData = $model->attributes;
					if ($model == '')
					{
						$transaction = DBUtil::beginTransaction();
						try
						{
							$cityName		 = '';
							$cityName		 = strtolower(Cities::getName($data['data']['vnd_city']));
							$model			 = new Vendors();
							$modelStats		 = new VendorStats();
							$modelPref		 = new VendorPref();
							$modelDevice	 = new VendorDevice();
							$modelContact	 = new Contact();

							$model->scenario			 = 'unregVendorjoin';
							$name						 = $data['data']['vnd_name'];
							$parts						 = explode(' ', $name);
							$firstname					 = $parts[0];
							$lastname					 = $parts[1];
							$emails						 = array(array('eml_email_address' => $data['data']['vnd_email'], 'eml_is_primary' => 1));
							$phones						 = array(array('phn_phone_no' => $data['data']['vnd_phone'], 'phn_is_primary' => 1));
							$modelContact->ctt_city		 = $data['data']['vnd_city'];
							$modelContact->ctt_user_type = $data['data']['user_type'];
							if ($data['data']['user_type'] == 1)
							{
								$modelContact->ctt_first_name	 = $data['data']['vnd_fname'];
								$modelContact->ctt_last_name	 = $data['data']['vnd_lname'];
							}
							else if ($data['data']['user_type'] == 2)
							{
								$modelContact->ctt_business_type = $data['data']['business_type'];
								$modelContact->ctt_business_name = $data['data']['vnd_company'];
							}
							$modelContact->ctt_voter_no		 = $data['data']['vnd_voter_no'];
							$modelContact->ctt_pan_no		 = $data['data']['vnd_pan_no'];
							$modelContact->ctt_aadhaar_no	 = $data['data']['vnd_aadhaar_no'];
							$modelContact->ctt_license_no	 = $data['data']['vnd_license_no'];
							$modelContact->ctt_address		 = $data['data']['vnd_address'];
							$modelContact->addType			 = 0;
							$modelContact->contactEmails	 = $modelContact->convertToEmailObjects($emails);
							$modelContact->contactPhones	 = $modelContact->convertToPhoneObjects($phones);
							$returnSet						 = $modelContact->add();
							if ($modelContact->save())
							{
								//vnd_is_dco(from app side) is equals to vnd_cat_type (service side): need to be updated later
								$model->vnd_cat_type	 = $data['data']['vnd_is_dco'];
								$model->vnd_name		 = ($data['data']['user_type'] == 1) ? $data['data']['vnd_fname'] . ' ' . $data['data']['vnd_lname'] : $data['data']['vnd_company'];
								$model->vnd_active		 = 3;
								$model->vnd_contact_id	 = $modelContact->ctt_id;
								$model->vnd_uvr_id		 = $data['data']['vnd_uvr_id'];

								$contEmailPhone	 = Contact::model()->getContactDetails($model->vnd_contact_id);
								$usersId		 = Users::model()->linkUserid($contEmailPhone['eml_email_address'], $contEmailPhone['phn_phone_no']);
								if ($usersId != "")
								{
									$model->vnd_user_id = $usersId;
								}
								else
								{
									Vendors::model()->createUserByVendor($model->vnd_id, $contEmailPhone, $data['vnd_password'], 2);
								}

								if ($model->validate())
								{
									if ($model->save())
									{
										$vendorId			 = $model->vnd_id;
										$codeArray			 = Filter::getCodeById($vendorId, 'vendor');
										$model->vnd_ref_code = $model->vnd_id;
										$model->vnd_code	 = $codeArray['code'];
										$model->vnd_name	 = $model->vnd_name . "-" . $codeArray['code'] . "-" . $cityName;
										$model->scenario	 = 'unregUpdateVendorJoin';
										if ($model->save())
										{
											$modelStats->vrs_vnd_id	 = $model->vnd_id;
											$modelPref->vnp_vnd_id	 = $model->vnd_id;
											$modelDevice->vdc_vnd_id = $model->vnd_id;
											$modelStats->save();
											$modelPref->save();
											$modelDevice->save();
											$desc					 = "New Vendor created";

											VendorsLog::model()->createLog($model->vnd_id, $desc, $userInfo, VendorsLog::VENDOR_CREATED, false, false);
										}
										$emailWrapper	 = new emailWrapper();
										$emailWrapper->adminVendorSignupEmail($model, $modelContact->ctt_id);
										$success		 = true;
										DBUtil::commitTransaction($transaction);
									}
									else
									{
										$msg = "Error saving  " . json_encode($model->getErrors());
										throw new Exception($msg);
									}
								}
								else
								{
									$msg = "Error saving  " . json_encode($model->getErrors());
									throw new Exception($msg);
								}
							}
							else
							{
								$model	 = null;
								$msg	 = "Phone or Email is already Registered.";
								throw new Exception(json_encode($modelContact->getErrors()), 102);
							}
						}
						catch (Exception $ex)
						{
							$errors = $ex->getMessage();
							DBUtil::rollbackTransaction($transaction);
//					throw new Exception($errors);
						}
					}
					else
					{
						$success = false;
						$errors	 = $model->getErrors();
						$msg	 = "Vendor have already signed up for our network.";
					}
				}
				else
				{
					$success = false;
					$errors	 = 'Access Denied';
					$msg	 = 'You do not have privilage to add vendor.';
				}
			}
			else
			{
				$success = false;
				$errors	 = 'Unauthorised Admin';
				$msg	 = "You are not Authorized.";
			}
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => $success,
					'errors'	 => $errors,
					'message'	 => $msg,
					'data'		 => $model,
					'vnd_id'	 => $vendorId,
				),
			]);
		});

		$this->onRest('req.post.editdoc.render', function () {
			$returnSet	 = new ReturnSet();
			$token		 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$result		 = Admins::model()->authorizeAdmin($token);
			if (!$result)
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors('You do not have access for this action.');
				goto resultResponse;
			}
			$checkAccess = Yii::app()->user->checkAccess('vendorAdd');
			if (!$checkAccess)
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors('You do not have privilage to add vendor.');
				goto resultResponse;
			}
			$returnSet->setStatus(false);
			$returnSet->setErrors('Something went wrong while uploading');
			$process_sync_data	 = Yii::app()->request->getParam('data');
			// $process_sync_data = '{"data":{"vnd_id":"43","vnd_name":"Abhishek Agarwal-agra-Agarwal Tours","vnd_phone":"7044443387","vnd_email":"akhetan2000@yahoo.com","vnd_alt_contact_number":"1234567789","vnd_address":"West Bengal ","vnd_voter_no":"63637383893","vnd_pan_no":"","vnd_aadhaar_no":"","vnd_license_no":"637383HDHDU","vnd_city":"30893","vnd_company":"Agarwal Tours","vnd_firm_type":"3","vnd_firm_pan":"PAN9231828196","vnd_firm_ccin":"67uk"},"vendorPic":"0"}';
			$processFile1		 = Yii::app()->request->getParam('file1_img_no');
			$processFile2		 = Yii::app()->request->getParam('file2_img_no');
			//$transaction = DBUtil::beginTransaction();
			try
			{
				$result		 = $this->editVendorDetails($process_sync_data, $processFile1, $processFile2, $returnSet);
				$returnSet	 = $result['returnSet'];
				$model		 = $result['model'];
				if ($returnSet->isSuccess())
				{
					//DBUtil::commitTransaction($transaction);
					goto resultResponse;
				}
			}
			catch (Exception $e)
			{
				Filter::createLog("Vendor details or document not saved.\n\t\t" . $e->getMessage(), CLogger::LEVEL_ERROR);
				//DBUtil::rollbackTransaction($transaction);
				$returnSet->setStatus(false);
				$returnSet->setErrors($e->getMessage());
			}
			resultResponse:
			unset($model->vnd_password);
			unset($model->vnd_accepted_zone);
			unset($model->vnd_log);
			Filter::createLog("Result Data=>" . json_encode($result), CLogger::LEVEL_TRACE);
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'		 => $returnSet->isSuccess(),
					'errors'		 => $returnSet->getErrors(),
					'data'			 => $model,
					'vnd_id'		 => $result['vendorId'],
					'file1_img_no'	 => $result['agmt_file1_img_no'],
					'file2_img_no'	 => $result['agmt_file2_img_no']
				),
			]);
		});
		//Adds vendor contact details from OPS APP
		$this->onRest("req.post.conAdd.render", function () {
			return $this->renderJSON($this->addContactNew());
		});
	}

	public function actionDormantVendor()
	{
		$row = Report::getRoleAccess(36);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Dormant Vendors - Report";
		$model			 = new Vendors();
		$modelPref		 = new VendorPref();
		$request		 = Yii::app()->request;
		if ($request->getParam('VendorPref') || $request->getParam('Vendors'))
		{
			$arr						 = $request->getParam('VendorPref');
			$vndarr						 = $request->getParam('Vendors');
			$modelPref->vnp_home_zone	 = $arr['vnp_home_zone'];
			$model->vnd_phone			 = $vndarr['vnd_phone'];
		}

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$modelPref->vnp_home_zone	 = Yii::app()->request->getParam('vnp_home_zone');
			$model->vnd_phone			 = Yii::app()->request->getParam('vnd_phone');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"DormantVendor_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "DormantVendor_" . date('Ymdhis') . ".csv";
			$foldername	 = Yii::app()->params['uploadPath'];
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows	 = VendorPref::model()->getDormantVendorReport($modelPref->vnp_home_zone, $model->vnd_phone, 'command');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Vendor Name', 'Phone No.', 'Vendor Home Zone', 'Last Logged In', 'Last Bidding Date',
				'Last Booking Completed On']);
			foreach ($rows as $row)
			{
				$rowArray							 = array();
				$rowArray['vnd_name']				 = $row['vnd_name'];
				$rowArray['phn_phone_no']			 = $row['phn_phone_no'];
				$rowArray['home_zone']				 = $row['home_zone'];
				$rowArray['last_login']				 = $row['last_login'];
				$rowArray['last_bidding_date']		 = $row['last_bidding_date'];
				$rowArray['last_booking_completed']	 = $row['last_booking_completed'];
				$row1								 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider							 = VendorPref::model()->getDormantVendorReport($modelPref->vnp_home_zone, $model->vnd_phone);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->render('report_dormant_vendor', array('dataProvider' => $dataProvider, 'model' => $model, 'modelPref' => $modelPref, 'roles' => $row));
	}

	public function actionVendorweekly()
	{
		$this->pageTitle = "Vendor Weekly Report";
		$model			 = new Booking;
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$arr			 = $request->getParam('Booking');
			$date1			 = $arr['bkg_create_date1'];
			$date2			 = $arr['bkg_create_date2'];
			$vendorStatus	 = $arr['bkg_vendor_status'];
			$vendorId		 = $arr['bkg_vendor'];
		}
		else
		{
			$model->bkg_pickup_date1 = date('Y-m-d');
			$model->bkg_pickup_date2 = date('Y-m-d', strtotime('+7 days'));
			$date2					 = $model->bkg_pickup_date2;
			$date1					 = $model->bkg_pickup_date1;
			$vendorStatus			 = 6;
			$vendorId				 = 0;
		}
		$model->bkg_create_date1	 = $date1;
		$model->bkg_create_date2	 = $date2;
		$model->bkg_vendor_status	 = $vendorStatus;
		$model->bkg_vendor			 = $vendorId;
//        $date1 = DateTimeFormat::DatePickerToDate($date1);
//        $date2 = DateTimeFormat::DatePickerToDate($date2);
		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']) && isset($_REQUEST['export_status1']) && isset($_REQUEST['export_id1']))
		{
			$arr2			 = array();
			$adminWrapper	 = new Booking();
			$fromDate		 = DateTimeFormat::DatePickerToDate($request->getParam('export_from1'));
			$toDate			 = DateTimeFormat::DatePickerToDate($request->getParam('export_to1'));
			$arr2			 = $adminWrapper->vendorReportCount($fromDate, $toDate, $request->getParam('export_status1'), $request->getParam('export_id1'));
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"vendor_weekly_above_report.csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$handle = fopen("php://output", 'w');
				fputcsv($handle, array("Total Booking", "Total Trip Days", "Total Booking Amount", "Gozo Commission Due"));
				if ($arr2 != "")
				{
					fputcsv($handle, array($arr2['b_count'], $arr2['t_days'], $arr2['b_amount'], $arr2['commission']));
				}
				fclose($handle);
				exit;
			}
		}
		if (isset($_REQUEST['export_from2']) && isset($_REQUEST['export_to2']))
		{
			$fromDate	 = DateTimeFormat::DatePickerToDate($request->getParam('export_from2'));
			$toDate		 = DateTimeFormat::DatePickerToDate($request->getParam('export_to2'));
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"VendorWeeklyReport_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "reportbooking" . date('YmdHi') . ".csv";
			$foldername	 = Yii::app()->params['uploadPath'];
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}

			$status		 = Booking::model()->getBookingStatus();
			$bookingType = Booking::model()->getBookingType();
			$status1	 = $request->getParam('export_status2');
			$vid		 = $request > getParam('export_id2');
			$type		 = 'command';
			$rows		 = $model->vendorWeeklyReport($fromDate, $toDate, $status1, $vid, $type);
			$handle		 = fopen("php://output", 'w');
			$i			 = 0;
			foreach ($rows as $row)
			{
				if ($i > 0)
				{
					$row['Status']		 = $status[$row['Status']];
					$row['Booking Type'] = $bookingType[$row['Booking Type']];
				}
				$row1 = array_values($row);
				fputcsv($handle, $row1);
				$i++;
			}
			fclose($handle);

			if (!$rows)
			{
				die('Could not take data backup: ' . mysql_error());
			}

			exit;
		}
		$dataProvider	 = $model->vendorWeeklyReport($date1, $date2, $vendorStatus, $vendorId);
		$countReport	 = $model->vendorReportCount($date1, $date2, $vendorStatus, $vendorId);
		$this->render('report_vendor_weekly', array('dataProvider' => $dataProvider, 'model' => $model, 'countReport' => $countReport));
	}

	/**
	 * Vendor Locked Payment Report
	 */
	public function actionVendorLockedPayment()
	{
		$this->pageTitle = "Vendor Locked Payment Report";

		$model	 = new BookingSub();
		$data	 = Yii::app()->request->getParam('BookingSub');
		if ($data)
		{
			$model->vnd_code = $data['vnd_code'];
		}
		else
		{
			$model->vnd_code = '';
		}

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"BlockedVendorPayments_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "BlockedVendorPayments_" . date('Ymdhis') . ".csv";
			$foldername	 = Yii::app()->params['uploadPath'];
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows	 = $model->getVendorLockedPaymentsExport();
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Booking Id', 'Trip Id', 'Agent', 'Pickup Date', 'Vendor Id', 'Vendor Name', 'Vendor Code', 'Trip Vendor Amount', 'Reason']);
			foreach ($rows as $row)
			{
				$rowArray						 = array();
				$rowArray['bkg_ids']			 = $row['bkg_ids'];
				$rowArray['bcb_id']				 = $row['bcb_id'];
				$rowArray['agt_company_names']	 = $row['agt_company_names'];
				$rowArray['bkg_pickup_dates']	 = $row['bkg_pickup_dates'];
				$rowArray['vnd_id']				 = $row['vnd_id'];
				$rowArray['vnd_name']			 = $row['vnd_name'];
				$rowArray['vnd_code']			 = $row['vnd_code'];
				$rowArray['bcb_vendor_amount']	 = $row['bcb_vendor_amount'];
				$rowArray['blg_desc']			 = $row['blg_desc'];
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}


		$dataProvider = $model->getVendorLockedPayments();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('vendorLockedPayment', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionBlockedVendor()
	{
		$row = Report::getRoleAccess(55);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Blocked Vendors - Report";
		$model			 = new VendorsLog();
		$condition		 = "";
		if (isset($_REQUEST['VendorsLog']))
		{
			$arr = Yii::app()->request->getParam('VendorsLog');

			$date1					 = $arr['vlg_create_date1'];
			$date2					 = $arr['vlg_create_date2'];
			$model->vlg_create_date1 = $date1;
			$model->vlg_create_date2 = $date2;
			$condition				 = ($date1 != '' && $date2 != '') ? " vlg1.vlg_created BETWEEN '" . $date1 . "' AND '" . $date2 . "'" : '';
		}
		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']))
		{

			$arr2			 = array();
			$adminWrapper	 = new VendorsLog();
			$fromDate		 = Yii::app()->request->getParam('export_from1');
			$toDate			 = Yii::app()->request->getParam('export_to1');
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header('Content-type: text/csv');
				header("Content-Disposition: attachment; filename=\"BlockedVendor_" . date('Ymdhis') . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$filename	 = "BlockedVendors_" . date('YmdHi') . ".csv";
				$foldername	 = Yii::app()->params['uploadPath'];
				$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
				if (!is_dir($foldername))
				{
					mkdir($foldername);
				}
				if (file_exists($backup_file))
				{
					unlink($backup_file);
				}
				$rows	 = Vendors::model()->blockedVendorExportList($fromDate, $toDate);
				$handle	 = fopen("php://output", 'w');
				fputcsv($handle, ['Region', 'Vendor Name', 'Joining Date', 'Vendor Rating', 'Blocked Date', 'Remarks']);
				foreach ($rows as $row)
				{

					$rowArray					 = array();
					$rowArray['Region']			 = $row['Region'];
					$rowArray['vnd_name']		 = $row['vnd_name'];
					$rowArray['joinDate']		 = $row['joinDate'];
					$rowArray['vnd_avg_rating']	 = $row['vnd_avg_rating'];
					$rowArray['blocked_date']	 = $row['blocked_date'];
					$rowArray['vlg_desc']		 = $row['vlg_desc'];
					$row1						 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
				fclose($handle);
				exit;
			}
		}
		$dataProvider = Vendors::model()->getBlockedVendorList($condition);
		$this->render('report_blocked_vendors', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row));
	}

	public function actionVendorWiseCountBooking()
	{
		$row = Report::getRoleAccess(62);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Vendor Wise Completed Booking - Report";
		$model			 = new Booking();
		$condition		 = "";
		if (isset($_REQUEST['Booking']))
		{
			$arr					 = Yii::app()->request->getParam('Booking');
			$model->bkg_pickup_date1 = $date1					 = $arr['bkg_pickup_date1'];
			$model->bkg_pickup_date2 = $date2					 = $arr['bkg_pickup_date2'];
		}
		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']))
		{

			$arr2			 = array();
			$adminWrapper	 = new Booking();
			$fromDate		 = Yii::app()->request->getParam('export_from1');
			$toDate			 = Yii::app()->request->getParam('export_to1');
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header('Content-type: text/csv');
				header("Content-Disposition: attachment; filename=\"CompletedBookingZone_" . date('Ymdhis') . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$filename	 = "CompletedBookingZone_" . date('YmdHi') . ".csv";
				$foldername	 = Yii::app()->params['uploadPath'];
				$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
				if (!is_dir($foldername))
				{
					mkdir($foldername);
				}
				if (file_exists($backup_file))
				{
					unlink($backup_file);
				}
				$rows	 = BookingSub::model()->getVendorWiseBookingCount($fromDate, $toDate);
				$zones	 = States::model()->getRegionByZoneId();
				$handle	 = fopen("php://output", 'w');
				fputcsv($handle, ['Region', 'Home Zone', 'Vendor Name', 'Vendor Overall Rating', 'Count of Completed Booking(90 days)', 'Count of Completed Booking(180 days)']);
				foreach ($rows as $row)
				{
					$zonid								 = array_search($row['vnp_home_zone'], array_column($zones, 'zon_id'));
					$rowArray							 = array();
					$rowArray['Region']					 = States::model()->findRegionName($zones[$zonid]['stt_zone'][0]);
					$rowArray['vnp_home_zone']			 = $zones[$zonid]['zon_name'];
					$rowArray['vnd_name']				 = $row['vnd_name'];
					$rowArray['vrs_vnd_overall_rating']	 = $row['vrs_vnd_overall_rating'];
					$rowArray['Count_90']				 = $row['Count_90'];
					$rowArray['Count_180']				 = $row['Count_180'];

					$row1 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
				fclose($handle);
				exit;
			}
		}
		$dataProvider = BookingSub::model()->getVendorWiseBookingCount($date1, $date2, 'Command');
		$this->render('vendor_wise_booking_count', array('model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row));
	}

	public function actionStickyVendorCount()
	{
		$this->pageTitle = "Sticky Vendor Count - Report";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$arr1					 = $request->getParam('Booking');
			$model->sourcezone		 = $arr1['sourcezone'];
			$zones					 = implode(",", $arr1['sourcezone']);
			$model->bkg_region		 = $arr1['bkg_region'];
			$regions				 = implode(",", $arr1['bkg_region']);
			$model->bkg_state		 = $arr1['bkg_state'];
			$states					 = implode(",", $arr1['bkg_state']);
			$model->bkg_pickup_date1 = $arr1['bkg_pickup_date1'] != null ? $arr1['bkg_pickup_date1'] : date("Y-m-d", strtotime("-7 days")) . ' 00:00:00';
			$model->bkg_pickup_date2 = $arr1['bkg_pickup_date2'] != null ? $arr1['bkg_pickup_date2'] : date("Y-m-d") . ' 23:59:59';
		}
		else
		{
			$model->bkg_pickup_date1 = date("Y-m-d", strtotime("-7 days")) . ' 00:00:00';
			$model->bkg_pickup_date2 = date("Y-m-d") . ' 23:59:59';
		}
		$dataProvider = VendorStats::model()->getStickyCount($model->bkg_pickup_date1, $model->bkg_pickup_date2, $zones, $regions, $states);
		$this->render('sticky_vendor_count_report', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionDirectAcceptReport()
	{
		$row = Report::getRoleAccess(70);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Accepted Bookings By Vendors - Report";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$arr1					 = $request->getParam('Booking');
			$model->bkg_pickup_date1 = $arr1['bkg_pickup_date1'] != null ? $arr1['bkg_pickup_date1'] : date("Y-m-d") . ' 00:00:00';
			$model->bkg_pickup_date2 = $arr1['bkg_pickup_date2'] != null ? $arr1['bkg_pickup_date2'] : date("Y-m-d") . ' 23:59:59';
		}
		else
		{
			$model->bkg_pickup_date1 = date("Y-m-d") . ' 00:00:00';
			$model->bkg_pickup_date2 = date("Y-m-d") . ' 23:59:59';
		}

		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$model->bkg_pickup_date1 = Yii::app()->request->getParam('bkg_pickup_date1');
			$model->bkg_pickup_date2 = Yii::app()->request->getParam('bkg_pickup_date2');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"DirectAcceptReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "DirectAcceptReport_" . date('Ymdhis') . ".csv";
			$foldername	 = Yii::app()->params['uploadPath'];
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows	 = VendorStats::model()->getAcceptedByVendorList($model->bkg_pickup_date1, $model->bkg_pickup_date2, 'command');
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Booking ID', 'Vendor Name', 'Vendor Amount', 'Booking Amount', 'Pickup Date', 'Assigned Date']);
			foreach ($rows as $row)
			{
				$vals		 = array_count_values(explode(',', $row['vendor_name']));
				$strvendors	 = "";
				foreach ($vals as $key => $value)
				{
					$strvendors = $strvendors . " " . $key . "(" . $value . " times)";
				}
				$rowArray						 = array();
				$rowArray['bkg_booking_id']		 = $row['bkg_booking_id'];
				$rowArray['vnd_name']			 = $row['vnd_name'];
				$rowArray['bkg_vendor_amount']	 = $row['bkg_vendor_amount'];
				$rowArray['bkg_total_amount']	 = $row['bkg_total_amount'];
				$rowArray['bkg_pickup_date']	 = $row['bkg_pickup_date'];
				$rowArray['blg_created']		 = $row['blg_created'];
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider = VendorStats::model()->getAcceptedByVendorList($model->bkg_pickup_date1, $model->bkg_pickup_date2);
		$this->render('report_direct_accepted', array('model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row));
	}

	public function actionLouRequired()
	{
		$pagetitle		 = "Cabs with LOU required";
		$this->pageTitle = $pagetitle;
		$model			 = new VendorVehicle();
		$pageSize		 = Yii::app()->params['listPerPage'];
		$data			 = Yii::app()->request->getParam('VendorVehicle');
		$dataProvider	 = VendorVehicle::model()->getLouRequiredData($data['search']);
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$outputJs		 = $request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('lourequired', array('model' => $model, 'dataProvider' => $dataProvider), null, $outputJs);
	}

	public function actionVendorCancellation()
	{
		$row = Report::getRoleAccess(73);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Vendor Cancellation - Report";
		$model			 = new Booking();
		$condition		 = "";
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$arr					 = $request->getParam('Booking');
			$model->bkg_create_date1 = $date1					 = $arr['bkg_create_date1'];
			$model->bkg_create_date2 = $date2					 = $arr['bkg_create_date2'];
		}
		if (isset($_REQUEST['export_from1']) && isset($_REQUEST['export_to1']))
		{

			$arr2			 = array();
			$adminWrapper	 = new Booking();
			$fromDate		 = Yii::app()->request->getParam('export_from1');
			$toDate			 = Yii::app()->request->getParam('export_to1');
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header('Content-type: text/csv');
				header("Content-Disposition: attachment; filename=\"CompletedBookingZone_" . date('Ymdhis') . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$filename	 = "VendorCancellation_" . date('YmdHi') . ".csv";
				$rows		 = Vendors::getVendorCancellation($date1, $date2);
				$handle		 = fopen("php://output", 'w');
				fputcsv($handle, ['Vendor Id', 'Vendor Name', 'Total Assigned', 'Total Served', 'Total Cancellations']);
				foreach ($rows as $row)
				{
					$rowArray								 = array();
					$rowArray['vendor_id']					 = $row['vendor_id'];
					$rowArray['vendor_name']				 = $row['vendor_name'];
					$rowArray['total_vendor_assigned_count'] = $row['total_vendor_assigned_count'];
					$rowArray['total_vendor_served_count']	 = $row['total_vendor_served_count'];
					$rowArray['total_vendor_cancel_count']	 = $row['total_vendor_cancel_count'] . " (" . round((($row['total_vendor_cancel_count'] * 100) / $row['total_vendor_assigned_count']), 2) . "%)";
					$row1									 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
				fclose($handle);
				exit;
			}
		}
		$dataProvider = Vendors::getVendorCancellation($date1, $date2, 'Command');
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$this->render('vendor_cancellation', array('model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row), false, true);
	}

	/*
	 * 	This function is used for General Report/ Vendor usage Report for perticular date range and vendor filter
	 */

	public function actionVendorUsageReport()
	{
		$row = Report::getRoleAccess(79);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Vendor Usage Details";
		$model			 = new Booking;
		$arr			 = Yii::app()->request->getParam('Booking');
		if (isset($_REQUEST['Booking']))
		{
			$arr					 = Yii::app()->request->getParam('Booking');
			$model->attributes		 = $arr;
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime($arr['bkg_pickup_date1'])) . " 00:00:00";
			$model->bkg_pickup_date2 = date('Y-m-d', strtotime($arr['bkg_pickup_date2'])) . " 23:59:59";
			$model->bcb_vendor_id	 = $arr['bcb_vendor_id'];
		}
		else
		{
			$arr['bkg_pickup_date1'] = $model->bkg_pickup_date1 = date('Y-m-d', strtotime("-7 days")) . " 00:00:00";
			$arr['bkg_pickup_date2'] = $model->bkg_pickup_date2 = date('Y-m-d') . " 23:59:59";
			$arr['bcb_vendor_id']	 = $model->bcb_vendor_id	 = '';
		}

		if (isset($_REQUEST['bkg_pickup_date1']) && $_REQUEST['bkg_pickup_date2'])
		{
			$fromPickupDate	 = Yii::app()->request->getParam('bkg_pickup_date1');
			$toPickupDate	 = Yii::app()->request->getParam('bkg_pickup_date2');
			$vndId			 = Yii::app()->request->getParam('bcb_vendor_id');
			$arr			 = [
				'bkg_pickup_date1'	 => $fromPickupDate,
				'bkg_pickup_date2'	 => $toPickupDate,
				'bcb_vendor_id'		 => $vndId
			];

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"VendorUsageReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "VendorUsageReport_" . date('Ymdhis') . ".csv";
			$foldername	 = Yii::app()->params['uploadPath'];
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows	 = Vendors::getVendorusageDetails($arr, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Date', 'Vendor Name', 'Not Logged In', 'Not Left', 'Not Arrived', 'Not Started', 'Not Ended']);
			foreach ($rows as $row)
			{
				$rowArray					 = array();
				$rowArray['date']			 = date("d-m-Y", strtotime($row['date']));
				$rowArray['vnd_name']		 = $row['vnd_name'];
				$rowArray['not_loggedin']	 = !empty($row['not_loggedin']) ? trim($row['not_loggedin']) : "N/A";
				$rowArray['not_left']		 = !empty($row['not_left']) ? trim($row['not_left']) : "N/A";
				$rowArray['not_arrived']	 = !empty($row['not_arrived']) ? trim($row['not_arrived']) : "N/A";
				$rowArray['not_started']	 = !empty($row['not_started']) ? trim($row['not_started']) : "N/A";
				$rowArray['not_ended']		 = !empty($row['not_ended']) ? trim($row['not_ended']) : "N/A";
				$row1						 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$dataProvider = Vendors::getVendorusageDetails($arr);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('vendorusagedetails', array('model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row));
	}

	/* this function is used for general report > regionwise Vendor App usaage report */

	public function actionRegionVendorwiseDriverAppusage()
	{
		$this->pageTitle = "Vendor and Region wise Driver App Usage Report";
		$model			 = new Booking;
		$arr			 = Yii::app()->request->getParam('Booking');
		if (isset($_REQUEST['Booking']))
		{
			$arr					 = Yii::app()->request->getParam('Booking');
			$model->attributes		 = $arr;
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime($arr['bkg_pickup_date1'])) . " 00:00:00";
			$model->bkg_pickup_date2 = date('Y-m-d', strtotime($arr['bkg_pickup_date2'])) . " 23:59:59";
			$model->bcb_vendor_id	 = $arr['bcb_vendor_id'];
			$model->bkg_region		 = $arr['bkg_region'];
		}
		else
		{
			$arr['bkg_pickup_date1'] = $model->bkg_pickup_date1 = date('Y-m-d', strtotime("-7 days")) . " 00:00:00";
			$arr['bkg_pickup_date2'] = $model->bkg_pickup_date2 = date('Y-m-d') . " 23:59:59";
			//$arr['bcb_vendor_id']	 = $model->bcb_vendor_id	 = [];
			$arr['bkg_region']		 = $model->bkg_region		 = '';
		}

		$dataProvider = BookingTrack::model()->getVendorwiseAppusageDetails($arr);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('vendorwiseappusage', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'qry'			 => $qry)
		);
	}

	public function actionVendoraccounts()
	{
		$this->pageTitle = "Today's Accounting Action List";

		$model		 = new Booking();
		$venModel	 = new PaymentGateway();
		$pageSize	 = '36';
		$setFlag	 = '1';
		$recordSet	 = $model->accountReportByFlag($setFlag);
		$bookList	 = new CArrayDataProvider($recordSet, array('pagination' => array('pageSize' => $pageSize),));
		$bookModels	 = $bookList->getData();

		$dataProvider							 = AccountTransDetails::vendorCollectionReport();
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);

		$venModel->ven_date_type = '1';
		$add_days				 = 7;
		$dateFromDate			 = (date('Y-m-d', strtotime('Today') - (24 * 3600 * $add_days)));
		$dateTodate				 = date('Y-m-d', strtotime('Today'));

		$venModel->ven_from_date = DateTimeFormat::DateToLocale($dateFromDate);
		$venModel->ven_to_date	 = DateTimeFormat::DateToLocale($dateTodate);
		$venModel->scenario		 = 'transaction_search';
		if (isset($_REQUEST['PaymentGateway']))
		{
			$venModel->attributes = $_REQUEST['PaymentGateway'];
			if ($venModel->validate())
			{
				$submit = trim($_POST['submit']);
				if ($submit == "1")
				{
					$this->forward('vendor/ledgerpdf');
				}
				if ($submit == "2")
				{
					$this->forward('vendor/listvendoraccount');
				}
			}
			else
			{
				
			}
		}
		$this->render('report_vendoraccounts', array('model'			 => $model,
			'venModel'		 => $venModel,
			'bookModels'	 => $bookModels,
			'bookingList'	 => $bookList,
			'dataProvider'	 => $dataProvider,
			'dateFromDate'	 => $dateFromDate,
			'dateTodate'	 => $dateTodate));
	}

	public function actionRegionperf()
	{

		$this->pageTitle = "Region Perf Report";
		$pageSize		 = Yii::app()->params['listPerPage'];
		$model			 = new Vendors();
		$region			 = '';
		if (isset($_REQUEST['Vendors']))
		{
			$model->attributes	 = Yii::app()->request->getParam('Vendors');
			$arr				 = $model->attributes;
			$create_date1		 = $model->vnd_create_date1;
			$create_date2		 = $model->vnd_create_date2;
			$region				 = $model->vnd_region;
		}
		else
		{
			$create_date1	 = DateTimeFormat::DateToLocale(date('Y-m-01'));
			$create_date2	 = DateTimeFormat::DateToLocale(date('Y-m-d'));
		}
		$model->vnd_create_date1 = $create_date1;
		$model->vnd_create_date2 = $create_date2;
		$model->vnd_region		 = $region;
		$create_date1			 = DateTimeFormat::DatePickerToDate($create_date1);
		$create_date2			 = DateTimeFormat::DatePickerToDate($create_date2);
		if (isset($_REQUEST['export_from']) && isset($_REQUEST['export_to']))
		{
			$fromDate	 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_from'));
			$toDate		 = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_to'));
			$region		 = Yii::app()->request->getParam('export_region');
			$data		 = $model->getRegionPerfReport('command', $fromDate, $toDate, $region);
			if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == "true")
			{
				header("Content-type: application/csv");
				header("Content-Disposition: attachment; filename=\"PerfReport_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
				header("Pragma: no-cache");
				header("Expires: 0");
				$handle = fopen("php://output", 'w');
				fputcsv($handle, array("Region", "Vendor", "Rating", "Bookings Assigned", "Bookings Assigned(advance paid)", "Bookings Assigned(post-paid/COD)", "Bookings total cancellations", "Bookings cancellations(advance)", "Bookings cancellations(COD)", "Amount", "Vendor Amount"));
				foreach ($data as $d)
				{
					$bookingAmt	 = $d['booking_amount'] > 0 ? $d['booking_amount'] : '0';
					$vendorAmt	 = $d['vendor_amount'] > 0 ? $d['vendor_amount'] : '0';
					fputcsv($handle, array($d['region'],
						$d['vnd_name'],
						$d['vnd_overall_rating'],
						$d['bookings_assigned'],
						$d['bookings_assigned_advance'],
						$d['bookings_assigned_cod'],
						$d['bookings_cancelled'],
						$d['bookings_cancelled_advance'],
						$d['booking_cancelled_cod'],
						$bookingAmt,
						$vendorAmt));
				}
				fclose($handle);
				exit;
			}
		}
		$dataProvider = $model->getRegionPerfReport('', $create_date1, $create_date2, $region);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('report_regionperf', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'qry'			 => $qry)
		);
	}

	public function actionAssignment()
	{
		$this->pageTitle = "Vendor Assignment Report";
		$model			 = new Vendors();
		$dataProvider	 = $model->getVendorAssignmentReport();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('report_assignment', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionCompensation()
	{
		$row = Report::getRoleAccess(91);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Vendor Compensation Report";
		$model			 = new Booking();
		$request		 = Yii::app()->request;
		if ($request->getParam('Booking'))
		{
			$arr					 = $request->getParam('Booking');
			$fromPickupDate			 = $arr['bkg_pickup_date1'];
			$toPickupDate			 = $arr['bkg_pickup_date2'];
			$vndId					 = $arr['bcb_vendor_id'];
			$bkgId					 = $arr['bkg_booking_id'];
			$status					 = $arr['bkg_status'];
			$fromcompensationDate	 = $arr['compensationdate1'];
			$tocompensationDate		 = $arr['compensationdate2'];

			if ($fromPickupDate == '' && $fromcompensationDate == '')
			{
				$error = "Please select pickup/ compensation date range";
				goto skipAll;
			}
		}
		else
		{
			$fromcompensationDate	 = date("Y-m-d", strtotime("-2 day", time()));
			$tocompensationDate		 = date('Y-m-d');
			$bkgType				 = [];
		}

		$model->bkg_pickup_date1	 = $fromPickupDate;
		$model->bkg_pickup_date2	 = $toPickupDate;
		$model->bcb_vendor_id		 = $vndId;
		$model->bkg_booking_id		 = $bkgId;
		$model->bkg_status			 = $status;
		$model->compensationdate1	 = $fromcompensationDate;
		$model->compensationdate2	 = $tocompensationDate;

		$params = [
			'fromPickupDate'		 => $fromPickupDate,
			'toPickupDate'			 => $toPickupDate,
			'vndId'					 => $vndId,
			'bkgId'					 => $bkgId,
			'status'				 => $status,
			'fromCompensationDate'	 => $fromcompensationDate,
			'tocompensationDate'	 => $tocompensationDate,
		];

		if ((isset($_REQUEST['bkg_pickup_date1']) && $_REQUEST['bkg_pickup_date2']) || (isset($_REQUEST['compensationdate1']) && $_REQUEST['compensationdate2']))
		{
			$fromPickupDate			 = Yii::app()->request->getParam('bkg_pickup_date1');
			$toPickupDate			 = Yii::app()->request->getParam('bkg_pickup_date2');
			$fromcompensationDate	 = Yii::app()->request->getParam('compensationdate1');
			$tocompensationDate		 = Yii::app()->request->getParam('compensationdate2');
			$bkgId					 = Yii::app()->request->getParam('bkg_booking_id');
			$vndId					 = Yii::app()->request->getParam('bcb_vendor_id');
			$status					 = Yii::app()->request->getParam('bkg_status');

			$params = [
				'fromPickupDate'		 => $fromPickupDate,
				'toPickupDate'			 => $toPickupDate,
				'vndId'					 => $vndId,
				'bkgId'					 => $bkgId,
				'status'				 => $status,
				'fromCompensationDate'	 => $fromcompensationDate,
				'tocompensationDate'	 => $tocompensationDate,
			];

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"VendorCompensationReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "VendorCompensationReport_" . date('Ymdhis') . ".csv";
			$foldername	 = Yii::app()->params['uploadPath'];
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows	 = BookingInvoice::getCompensationData($params, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Booking Id', 'Pickup Date', 'Cancel Date', 'Compensation Date', 'Vendor Name', 'Vendor Amount', 'Cancellation Charge', 'Vendor Compensation', 'Booking Status', 'Compensation Condition']);
			foreach ($rows as $row)
			{
				$rowArray						 = array();
				$rowArray['bookingId']			 = $row['bookingId'];
				$rowArray['bkg_pickup_date']	 = $row['bkg_pickup_date'];
				$rowArray['btr_cancel_date']	 = $row['btr_cancel_date'];
				$rowArray['vndCompensationDate'] = $row['vndCompensationDate'];
				$rowArray['vnd_name']			 = $row['vnd_name'];
				$rowArray['bcb_vendor_amount']	 = $row['bcb_vendor_amount'];
				$rowArray['cancelcharge']		 = $row['cancelcharge'];
				$rowArray['vndCompensation']	 = $row['vndCompensation'];
				$rowArray['bkg_status']			 = Booking::model()->getActiveBookingStatus($row['bkg_status']);
				$model							 = Booking::model()->findByPk($row['bkgId']);
				$remarks						 = '';
				if ($model->bkgBcb->bcb_trip_type != 1)
				{
					$getCustomerCancellationAmt = AccountTransactions::getCancellationCharge($model->bkg_id);
					if ($getCustomerCancellationAmt > 0)
					{
						$arr	 = BookingInvoice::calculateVendorCompensation($model);
						$remarks = $arr['remarks'];
					}
				}
				$rowArray['remarks'] = $remarks;
				$row1				 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$dataProvider = BookingInvoice::getCompensationData($params);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		skipAll:
		$this->render('vendorCompensation', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'orderby'		 => $orderby,
			'error'			 => $error, 'roles'			 => $row)
		);
	}

	public function actionProfileStrength()
	{
		$row = Report::getRoleAccess(99);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Vendor Profile Strength";
		$model			 = new Vendors();
		$request		 = Yii::app()->request;
		if ($request->getParam('Vendors'))
		{
			$arr				 = $request->getParam('Vendors');
			$model->vnd_active	 = ($arr['vnd_active'] != '') ? $arr['vnd_active'] : '';
			$model->vnd_id		 = $arr['vnd_id'];
		}

		if (isset($_REQUEST['export']) && $_REQUEST['export'])
		{
			$model->vnd_id		 = Yii::app()->request->getParam('vnd_id');
			$model->vnd_active	 = Yii::app()->request->getParam('vnd_active');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"ProfileStrength_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "ProfileStrength_" . date('Ymdhis') . ".csv";
			$foldername	 = Yii::app()->params['uploadPath'];
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows	 = $model->getProfileStrength(DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Name', 'Status', 'Is Freeze', 'Sticky Score', 'Trust Score', 'Dependency', 'Security Amount', 'Overall Rating',
				'Total Trip', 'Total Bid', 'Count Driver', 'Count Car', 'Approve Driver Count', 'Approve Car Count',
				'Docs Score', 'No Of Star', 'Denied Duty Count', 'Total Trips', 'Locked Amount', 'Withdrawable Balance',
				'Last Booking Completed', 'Total Completed 30days', 'Total vehicle 30days', 'Penalty Count',
				'Total booking', 'Vendor Margin', 'Bid Win Percentage']);
			foreach ($rows as $row)
			{
				if ($row['vnd_active'] == 1)
				{
					$statusVal = "Approved";
				}
				else if ($row['vnd_active'] == 2)
				{
					$statusVal = "Inactive";
				}
				else if ($row['vnd_active'] == 3)
				{
					$statusVal = "Pending";
				}

				$isFreeze			 = ($row['vnp_is_freeze'] == 1) ? 'Freeze | ' : '';
				$isCodFreeze		 = ($row['vnp_cod_freeze'] == 1) ? 'COD Freeze | ' : '';
				$creditLimitFreeze	 = ($row['vnp_credit_limit_freeze'] == 1) ? 'Credit Limit Freeze | ' : '';
				$lowRateFreeze		 = ($row['vnp_low_rating_freeze'] == 1) ? 'Low Rating Freeze | ' : '';
				$docPendingFreeze	 = ($row['vnp_doc_pending_freeze'] == 1) ? 'Doc Pending Freeze | ' : '';
				$isManualFreeze		 = ($row['vnp_manual_freeze'] == 1) ? 'Manual Freeze| ' : '';
				$checkFreeze		 = $isFreeze . $isCodFreeze . $creditLimitFreeze . $lowRateFreeze . $docPendingFreeze . $isManualFreeze;

				$rowArray								 = array();
				$rowArray['vnd_name']					 = $row['vnd_name'];
				$rowArray['vnd_actve']					 = $statusVal;
				$rowArray['vnp_is_freeze']				 = rtrim($checkFreeze, "| ");
				$rowArray['vrs_sticky_score']			 = $row['vrs_sticky_score'];
				$rowArray['vrs_trust_score']			 = $row['vrs_trust_score'];
				$rowArray['vrs_dependency']				 = $row['vrs_dependency'];
				$rowArray['vrs_security_amount']		 = $row['vrs_security_amount'];
				$rowArray['vrs_vnd_overall_rating']		 = $row['vrs_vnd_overall_rating'];
				$rowArray['vrs_vnd_total_trip']			 = $row['vrs_vnd_total_trip'];
				$rowArray['vrs_tot_bid']				 = $row['vrs_tot_bid'];
				$rowArray['vrs_count_driver']			 = $row['vrs_count_driver'];
				$rowArray['vrs_count_car']				 = $row['vrs_count_car'];
				$rowArray['vrs_approve_driver_count']	 = $row['vrs_approve_driver_count'];
				$rowArray['vrs_approve_car_count']		 = $row['vrs_approve_car_count'];
				$rowArray['vrs_docs_score']				 = $row['vrs_docs_score'];
				$rowArray['vrs_no_of_star']				 = $row['vrs_no_of_star'];
				$rowArray['vrs_denied_duty_cnt']		 = $row['vrs_denied_duty_cnt'];
				$rowArray['vrs_total_trips']			 = $row['vrs_total_trips'];
				$rowArray['vrs_locked_amount']			 = $row['vrs_locked_amount'];
				$rowArray['vrs_withdrawable_balance']	 = $row['vrs_withdrawable_balance'];
				$rowArray['vrs_last_bkg_cmpleted']		 = $row['vrs_last_bkg_cmpleted'];
				$rowArray['vrs_total_completed_days_30'] = $row['vrs_total_completed_days_30'];
				$rowArray['vrs_total_vehicle_30']		 = $row['vrs_total_vehicle_30'];
				$rowArray['vrs_penalty_count']			 = $row['vrs_penalty_count'];
				$rowArray['vrs_total_booking']			 = $row['vrs_total_booking'];
				$rowArray['vrs_margin']					 = $row['vrs_margin'];
				$rowArray['vrs_bid_win_percentage']		 = $row['vrs_bid_win_percentage'];
				$model									 = Booking::model()->findByPk($row['bkgId']);
				$row1									 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider = $model->getProfileStrength();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('profileStrength', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row));
	}

	public function actionDCO()
	{
		$row = Report::getRoleAccess(102);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "DCO Vendor Report";
		$model			 = new Vendors();
		$request		 = Yii::app()->request;
		if ($request->getParam('Vendors'))
		{
			$arr							 = $request->getParam('Vendors');
			$model->vnd_registered_platform	 = $arr['vnd_registered_platform'];
			$model->vnd_create_date1		 = $arr['vnd_create_date1'];
			$model->vnd_create_date2		 = $arr['vnd_create_date2'];
			$model->bkg_create_date1		 = $arr['bkg_create_date1'];
			$model->bkg_create_date2		 = $arr['bkg_create_date2'];
			if ($arr['bkg_create_date1'] == "" && $arr['bkg_create_date1'] == "" && $arr['vnd_create_date1'] == "" && $arr['vnd_create_date1'] == "")
			{
				$model->bkg_create_date1 = date("Y-m-d", strtotime("-1 day", time()));
				$model->bkg_create_date2 = date('Y-m-d');
			}
		}
		else
		{
			$model->bkg_create_date1 = date("Y-m-d", strtotime("-1 day", time()));
			$model->bkg_create_date2 = date('Y-m-d');
		}

		if (isset($_REQUEST['export']) && $_REQUEST['export'])
		{
			$registered_platform = Yii::app()->request->getParam('registered_platform');
			$fromDate			 = Yii::app()->request->getParam('fromDate');
			$toDate				 = Yii::app()->request->getParam('toDate');
			$bkgFromDate		 = Yii::app()->request->getParam('bkgFromDate');
			$bkgToDate			 = Yii::app()->request->getParam('bkgToDate');
			if ($bkgFromDate != '' && $bkgToDate != '')
			{
				$startDate	 = $bkgFromDate . $fromTime;
				$endsDate	 = $bkgToDate . $toTime;
			}
			else if ($fromDate != '' && $toDate != '')
			{
				$startDate	 = $fromDate . $fromTime;
				$endsDate	 = $toDate . $toTime;
			}
			else
			{
				$startDate	 = date("Y-m-d", strtotime("-1 day", time()));
				$endsDate	 = date('Y-m-d');
			}
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"DCOReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "DCOReport_" . date('Ymdhis') . ".csv";
			$foldername	 = Yii::app()->params['uploadPath'];
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows	 = Vendors::getDcoData($bkgFromDate, $bkgToDate, $fromDate, $toDate, $registered_platform, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Vendor Id', 'Vendor Name', 'Vendor Code', 'DCO App(Registered)', 'DCO App(Login)', 'Vendor Name', 'Vendor Create Date', 'Vendor Age (Days)', 'Vendor Status', 'COD Freeze', 'Credit Limit Freeze', 'Low Rating Freeze', 'Document Pending Freeze', 'Manual Freeze', 'Bidding Accept', 'Total Bid', 'Total Served', 'DCO Served Count']);
			foreach ($rows as $row)
			{

				$rowArray['vnd_id']				 = $row['vnd_id'];
				$rowArray['VendorName']			 = $row['VendorName'];
				$rowArray['VendorCode']			 = $row['VendorCode'];
				$rowArray['registeredPlatform']	 = $row['vnd_registered_platform'] == 1 ? "Yes" : "No";
				$rowArray['isDocAppLogin']		 = $row['apt_id'] > 0 ? "Yes" : "No";
				$rowArray['VendorName']			 = $row['VendorName'];
				$rowArray['VendorCreateDate']	 = date("Y-m-d", strtotime($row['VendorCreateDate']));
				$rowArray['VendorAge']			 = $row['VendorAge'];
				$rowArray['VendorStatus']		 = $row['VendorStatus'];
				$rowArray['CODFreeze']			 = $row['CODFreeze'];
				$rowArray['CreditLimitFreeze']	 = $row['CreditLimitFreeze'];
				$rowArray['LowRatingFreeze']	 = $row['LowRatingFreeze'];
				$rowArray['DOCPendingFreeze']	 = $row['DOCPendingFreeze'];
				$rowArray['ManualFreeze']		 = $row['ManualFreeze'];
				$rowArray['BiddingAccept']		 = $row['BiddingAccept'] >= 1 ? $row['BiddingAccept'] : 0;
				$rowArray['TotalBid']			 = $row['TotalBid'] >= 1 ? $row['TotalBid'] : 0;
				$rowArray['TotalServed']		 = $row['TotalServed'];
				$rowArray['DcoServedCnt']		 = $row['DcoServedCnt'] >= 1 ? $row['DcoServedCnt'] : 0;
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$dataProvider = Vendors::getDcoData($model->bkg_create_date1, $model->bkg_create_date2, $model->vnd_create_date1, $model->vnd_create_date2, $model->vnd_registered_platform);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('dco', array('dataProvider' => $dataProvider, 'model' => $model, 'roles' => $row));
	}

	public function actionProfileReport()
	{
		$row = Report::getRoleAccess(136);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Vendor Profile Report (Zone/ Cab Category)";
		$model			 = new Booking();
		$request		 = Yii::app()->request;

		if ($request->getParam('Booking'))
		{
			$arr = $request->getParam('Booking');

			$model->sourcezone			 = $arr['sourcezone'];
			$model->bkg_vehicle_type_id	 = $arr['bkg_vehicle_type_id'];
			$vehicleTypeId				 = implode(',', $model->bkg_vehicle_type_id);
		}
		else
		{
			$model->sourcezone = 394;
		}

		if (isset($_REQUEST['export']) && $_REQUEST['export'])
		{
			$model->sourcezone	 = Yii::app()->request->getParam('sourcezone');
			$vehicleTypeId		 = Yii::app()->request->getParam('bkg_vehicle_type_id');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"ProfileReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "ProfileReport_" . date('Ymdhis') . ".csv";
			$foldername	 = Yii::app()->params['uploadPath'];
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows	 = Vendors::getProfileReport($model->sourcezone, $vehicleTypeId, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Vendor Id', 'Vendor Name', 'Service Class', 'Home Zone', 'Vendor Status', 'Total Trip', 'Rating', 'Total Unassign Count', 'Denied Duty Cnt', 'Doc Socre', 'Approve Driver Cnt', 'Approve Car Cnt', 'Last Booking Completed']);
			foreach ($rows as $row)
			{
				$rowArray['vnd_id']						 = $row['vnd_id'];
				$rowArray['vnd_name']					 = $row['vnd_name'];
				$rowArray['scv_label']					 = $row['scv_label'];
				$rowArray['homeZone']					 = $row['homeZone'];
				$rowArray['vendor_status']				 = $row['vendor_status'];
				$rowArray['vrs_total_trips']			 = $row['vrs_total_trips'];
				$rowArray['vrs_vnd_overall_rating']		 = $row['vrs_vnd_overall_rating'];
				$rowArray['Total_Unassign_Count']		 = $row['Total_Unassign_Count'];
				$rowArray['vrs_denied_duty_cnt']		 = $row['vrs_denied_duty_cnt'];
				$rowArray['vrs_docs_score']				 = $row['vrs_docs_score'];
				$rowArray['vrs_approve_driver_count']	 = $row['vrs_approve_driver_count'];
				$rowArray['vrs_approve_car_count']		 = $row['vrs_approve_car_count'];
				$rowArray['vrs_last_bkg_cmpleted']		 = $row['vrs_last_bkg_cmpleted'];
				$row1									 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$dataProvider = Vendors::getProfileReport($model->sourcezone, $vehicleTypeId);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('profile_report', array('dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'vehicleTypeId'	 => $vehicleTypeId,
			'roles'			 => $row));
	}

	public function actionLowRatingCabDriver()
	{
		$row = Report::getRoleAccess(138);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Low rating cab and driver list";
		$model			 = new Vendors();
		$request		 = Yii::app()->request;
		if ($request->getParam('Vendors'))
		{
			$arr		 = $request->getParam('Vendors');
			$vendorId	 = $arr['vnd_id'];
		}
		else
		{
			$vendorId = '';
		}
		$model->vnd_id = $vendorId;

		if (isset($_REQUEST['export']) && $_REQUEST['export'])
		{
			$vendorId	 = Yii::app()->request->getParam('vendorId');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"LowRatingCabDriverReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "LowRatingCabDriverReport_" . date('Ymdhis') . ".csv";
			$foldername	 = Yii::app()->params['uploadPath'];
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows	 = Vendors::getLowRatingCabDriver($vendorId, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Vendor Code', 'Driver Id', 'Vhc Id']);
			foreach ($rows as $row)
			{
				$rowArray['vnd_code']	 = $row['vnd_code'];
				$rowArray['drv_id']		 = $row['drv_id'];
				$rowArray['vhc_id']		 = $row['vhc_id'];
				$row1					 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$dataProvider = Vendors::getLowRatingCabDriver($vendorId);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$this->render('low_rating_cab_driver_report', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'roles'			 => $row));
	}

	public function actionDcoLinkAttached()
	{
		$row = Report::getRoleAccess(138);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle	 = "Attached with DCO Link";
		$request			 = Yii::app()->request;
		$model				 = new Vendors();
		$vndArr['groupvar']	 = 'date';
		if ($request->getParam('Vendors'))
		{
			$vndArr				 = $request->getParam('Vendors');
			$model->from_date	 = $vndArr['from_date'];
			$model->to_date		 = $vndArr['to_date'];
			$model->vnd_zone	 = $vndArr['vnd_zone'];
			$model->vnd_region	 = $vndArr['vnd_region'];
			$model->vnd_state	 = $vndArr['vnd_state'];
		}
		$dataProvider = Vendors::getDCOLinkAttachmentData($model, $vndArr);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$this->render('dcoLinkAttached', array(
			'dataProvider'	 => $dataProvider,
			'vndArr'		 => $vndArr,
			'model'			 => $model));
	}

	public function actionGNowDisabled()
	{
		$request		 = Yii::app()->request;
		$model			 = new Vendors();
		$dataProvider	 = VendorPref::getGNowSnoozed();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('gNowDisabled', array('dataProvider' => $dataProvider));
	}

	public function actionVendorCoins()
	{
		$row = Report::getRoleAccess(143);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Vendor Coin Report";

		$model = new VendorCoins();

		$model->from_date	 = date("Y-m-01", strtotime("-3 month", time()));
		$model->to_date		 = date('Y-m-d');
		$model->groupBy		 = "month";

		$req = Yii::app()->request;
		if ($req->getParam('VendorCoins'))
		{
			$data				 = $req->getParam('VendorCoins');
			$model->from_date	 = $data['from_date'];
			$model->to_date		 = $data['to_date'];
			$model->groupBy		 = $data['groupBy'];
			$model->vndStatus		 = $data['vndStatus'];
		}

		$dataProvider = $model->getCoinDetails();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('report_vendorcoins', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionAutoAssignmentTracking()
	{
		$model	 = new Booking();
		$arr	 = Yii::app()->request->getParam('Booking');
		if ($arr == null)
		{
			$arr['autoAssignDate1']	 = date('Y-m-d', strtotime('-7 DAY'));
			$arr['autoAssignDate2']	 = date('Y-m-d');
		}
		$model->attributes		 = $arr;
		$model->confirmDate1	 = $arr['confirmDate1'];
		$model->confirmDate2	 = $arr['confirmDate2'];
		$model->autoAssignDate1	 = $arr['autoAssignDate1'];
		$model->autoAssignDate2	 = $arr['autoAssignDate2'];
		$model->bkg_pickup_date1 = $arr['bkg_pickup_date1'];
		$model->bkg_pickup_date2 = $arr['bkg_pickup_date2'];
		$arr1					 = array_filter($arr);

		if (isset($_REQUEST['export']) && $_REQUEST['export'])
		{
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"AutoAssignmentTrackingReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "AutoAssignmentTrackingReport_" . date('Ymdhis') . ".csv";
			$foldername	 = Yii::app()->params['uploadPath'];
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}

			$handle = fopen("php://output", 'w');
			fputcsv($handle, ['Booking ID', 'From City', 'To City', 'Cab Type', 'Status', 'Create Date/Time', 'Confirm Date/Time', 'Pickup Date/Time', 'Auto Assignment Date/Time', 'Actual Assignment Date/Time', 'Current Assigned Vendor', 'Proposed Critical Score', 'Current Critical Score', 'Trip Distance (KM)', 'Current Trip Amount', 'Proposed VA', 'Quoted VA', 'Old Trip Amount', 'Toll Tax', 'Total Amount', 'Current Gozo Amount', 'Proposed Gozo Amount']);

			$statusList	 = Booking::model()->getActiveBookingStatus();
			$rows		 = VendorAutoAssignmentTracking::fetchList($arr1, DBUtil::ReturnType_Query);
			foreach ($rows as $row)
			{
				$rowArray								 = [];
				$rowArray['bkg_booking_id']				 = $row['bkg_booking_id'];
				$rowArray['fromCity']					 = $row['fromCity'];
				$rowArray['toCity']						 = $row['toCity'];
				$rowArray['cabType']					 = $row['cabType'];
				$rowArray['bkg_status']					 = $statusList[$row['bkg_status']];
				$rowArray['bkg_create_date']			 = DateTimeFormat::DateTimeToLocale($row['bkg_create_date']);
				$rowArray['confirmDate']				 = DateTimeFormat::DateTimeToLocale($row['confirmDate']);
				$rowArray['bkg_pickup_date']			 = DateTimeFormat::DateTimeToLocale($row['bkg_pickup_date']);
				$rowArray['autoAssignmentTime']			 = ($row['autoAssignmentTime'] == '') ? '' : DateTimeFormat::DateTimeToLocale($row['autoAssignmentTime']);
				$rowArray['actualAssignmentTime']		 = ($row['actualAssignmentTime'] == '') ? '' : DateTimeFormat::DateTimeToLocale($row['actualAssignmentTime']);
				$rowArray['currentAssignedVendorName']	 = $row['currentAssignedVendorName'];
				$rowArray['vat_booking_critical_score']	 = $row['vat_booking_critical_score'];
				$rowArray['bkg_critical_score']			 = $row['bkg_critical_score'];
				$rowArray['bkg_trip_distance']			 = $row['bkg_trip_distance'];
				$rowArray['currentTripAmount']			 = $row['currentTripAmount'];
				$rowArray['proposedVA']					 = $row['proposedVA'];
				$rowArray['quotedVA']					 = $row['quotedVA'];
				$rowArray['oldTripAmount']				 = $row['oldTripAmount'];
				$rowArray['tollTax']					 = $row['tollTax'];
				$rowArray['totalAmount']				 = $row['totalAmount'];
				$rowArray['currentGozoAmount']			 = $row['currentGozoAmount'];
				$rowArray['proposedGozoAmount']			 = $row['proposedGozoAmount'];
				$row1									 = array_values($rowArray);

				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider = VendorAutoAssignmentTracking::fetchList($arr1);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('assignmentTracking', array('dataProvider' => $dataProvider, 'model' => $model));
	}
}
