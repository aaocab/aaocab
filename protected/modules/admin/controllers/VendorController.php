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
				'actions'	 => array('merge', 'mergevendors', 'AgreementApprovedoc', 'AgreementShowdoc', 'sociallist', 'louList', 'viewloudetails', 'changestatusloulist', 'unlinksocialaccount', 'duplicatevendor', 'mergeduplicatevendor', 'safedelete', "linkuser", "link", "tmpRating"),
				'users'		 => array('@'),
			),
			['allow', 'actions' => ['add', 'view', 'zonecity', 'safedelete', 'UpdateDetails', 'bidlog', 'show'], 'roles' => ['vendorAdd', 'vendorEdit']],
			['allow', 'actions' => ['joining', 'approve'], 'roles' => ['vendorJoininglist', 'vendorApprove']],
			['allow', 'actions'	 => ['list', 'view', 'viewOld', 'showcontact', 'vendorlog', 'addremark', 'sendCustomMessage', 'administrativefreeze', 'regionperf',
					'search', 'bookingrequest', 'showlog', 'listlogdetails', 'searchlist', 'drilldown', 'vendoraccount',
					'updatecredit', 'account', 'invoice', 'invoicepdf', 'generateinvoicepdf', 'generatevendorpdf',
					'ledgerpdf', 'generateledgerpdf', 'generatepdf', 'listvendoraccount', 'accounts', 'addamount',
					'vendoraccounts', 'markedbadlist', 'resetmarkedbad', 'vendorrouterate', 'vendorlist', 'bulkinvoice',
					'missingAlertTest', 'accountStatement', 'accountstatementpdf', 'changecod', 'updateStats', 'assignment', 'updateDoc', 'agreementApprovalList', 'agreementShowPic',
					'docapprovallist', 'showdocimg', 'DelVendor', 'showdoc', 'approvedocimg', 'listDoc', 'create', 'assigncontact', 'addcontact', 'checkuser',
					'rejectDoc', 'strength', 'packageslist', 'editPackages', 'sentPackages', 'receivedPackages', 'delPackages', 'rejectDoc', 'reduce', 'penalty', 'profile', 'vendorTripDetails', 'vendorVehicleDetails', 'vendorDriverDetails', 'vendorAccountDetails', 'vendorZoneDetails', 'vendorProfileStrength'
					, 'vendorBiddingLog', 'vendorPenalty', 'vendorViewLog', 'vendorContactViewLog', 'vendorScqDetails', 'vendorNotificationLog', 'vendorDocuments', 'getCoinDetails', 'penaltyRules', 'vendorRatingList'], 'roles'		 => ['vendorList']],
			['allow', 'actions' => ['paymentstosend', 'paytransfer'], 'roles' => ['VendorPaymentsToSend']],
			['allow', 'actions' => ['block', 'changeassign', 'freeze'], 'roles' => ['vendorChangestatus', 'vendorBlockStatus']],
			['allow', 'actions' => ['updatecredit'], 'roles' => ['vendorEdit']],
			['allow', 'actions' => ['changestatus'], 'roles' => ['vendorUnBlockStatus']],
			['allow', 'actions' => ['del'], 'roles' => ['vendorDelete']],
			['allow', 'actions' => ['BroadcastMessage', 'showverifylink', 'listtoapprove', 'reject', 'revert', 'unregvendorlist', 'UvrVendorDetails', 'Uvrdelete', 'UvrVendorApprove', 'updateRelationshipManager', 'vendorDetails', 'exportTierCount'], 'users' => ['@']],
			['allow', 'actions'	 => ['generateAgreementForVendor', 'generateSoftCopyForVendor', 'generateInvoiceForVendor', 'unsetOrientation',
					'generateLedgerForVendor', 'changecod', 'updateStats', 'regProgress', 'relationshipTier', 'operatorAgreement', 'uploadDoc', 'showDuplicateUser', 'getlockamount', 'ViewMetrics', 'refreshVendorAccount', 'boostDependency'], 'users'		 => ['*']],
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

	/**
	 * This function is used for adding vendor from OPS App
	 * @return type
	 * @throws Exception
	 */
	public function addContacts()
	{
		$returnSet = new ReturnSet();
		try
		{

			$data = Yii::app()->request->rawBody;

			$receivedData = CJSON::decode($data, false);
			if (empty($receivedData))
			{
				throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			};

			$jsonMapper			 = new JsonMapper();
			$tmpContactStub		 = new Stub\common\Business();
			/** @var JsonMapper $obj */
			$obj				 = $jsonMapper->map($receivedData, $tmpContactStub);
			$contactMediumModel	 = $obj->getMedium();
			/** @var Contact $contactMediumModel */
			$returnSet			 = $contactMediumModel->addContacts();
		}
		catch (Exception $ex)
		{
			$errors = $ex->getMessage();
			Logger::create("Contact Details not saved. -->" . $errors, CLogger::LEVEL_ERROR);
		}

		skipAll:
		return $returnSet;
	}

	public function actionAdd()
	{
		$returnSet		 = new ReturnSet();
		$this->pageTitle = "Add Vendor";
		$vndId			 = Yii::app()->request->getParam('agtid');
		$newvendor		 = Yii::app()->request->getParam('newvendor');
		$type			 = Yii::app()->request->getParam('type', '');

		$model					 = new Vendors('contactupdate');
		$model->vendorPrefs		 = new VendorPref('updateHomeZone');
		$model->vendorStats		 = new VendorStats();
		$model->vendorDevices	 = new VendorDevice();

		try
		{

			if ($type == 'unreg')
			{
				$this->pageTitle = "Add Unregister Vendor";
				$uvrmodel		 = UnregVendorRequest::model()->findByPk($newvendor);
				$arrContacts	 = Contact::model()->findDuplicateContacts($uvrmodel->uvr_vnd_email, $uvrmodel->uvr_vnd_phone, $uvrmodel->uvr_vnd_license_no, $uvrmodel->uvr_vnd_pan_no, $uvrmodel->uvr_vnd_aadhaar_no, $uvrmodel->uvr_vnd_voter_no);
				// $uvrmodel->uvr_vnd_license_no, $uvrmodel->uvr_vnd_pan_no, $uvrmodel->uvr_vnd_aadhaar_no, $uvrmodel->uvr_vnd_voter_no
//                                $arrContacts	 = Contact::model()->getByEmail($uvrmodel->uvr_vnd_email,$uvrmodel->uvr_vnd_phone);
//				
//                                if ($arrContacts->getRowCount() > 0)
//                                {
//                                    $contactID ;
//                                }
				if ($arrContacts != NULL)
				{
					$model->vnd_contact_id	 = $arrContacts[0]['ctt_id'];
					$model->vnd_contact_name = $uvrmodel->uvr_vnd_name . ' ' . $uvrmodel->uvr_vnd_lname;
				}
				$model->vnd_username = $uvrmodel->uvr_vnd_username;
				$model->vnd_name	 = $uvrmodel->uvr_vnd_name . " " . $uvrmodel->uvr_vnd_lname;
				$model->vnd_uvr_id	 = $uvrmodel->uvr_id;
				$model->vnd_active	 = 3;
			}
			if ($vndId > 0)
			{
				$this->pageTitle = "Edit Vendor";
				$model			 = Vendors::model()->findByPk($vndId);
				if (empty($model))
				{
					$model = Vendors::model()->resetScope()->findByPk($vndId);
				}
				if ($model == null)
				{
					$this->redirect('/aaohome/vendor/list');
				}

				$model->scenario				 = 'contactupdate';
				$model->vendorStats->scenario	 = 'update';
				$model->vendorPrefs->scenario	 = 'updateHomeZone';
				$contactId						 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
				$model->vndContact				 = Contact::model()->findByPk($contactId);

				$model->vnd_contact_name = ($model->vndContact->ctt_user_type == 1) ? $model->vndContact->ctt_first_name . ' ' . $model->vndContact->ctt_last_name : ($model->vndContact->ctt_business_name == NULL ? "" : $model->vndContact->ctt_business_name);
				$oldData['vendor']		 = $model->attributes;
				$oldData['stats']		 = $model->vendorStats->attributes;
				$oldData['pref']		 = $model->vendorPrefs->attributes;

				if ($model->vendorPrefs->vnp_accepted_zone == "" || $model->vendorPrefs->vnp_accepted_zone == NULL)
				{
					$model->vendorPrefs->vnp_accepted_zone = implode(', ', array_column(Zones::getZoneByHomeZone($vndId), 'id'));
				}
			}
			if ($type == "approve")
			{
				$this->pageTitle	 = "Approve Vendor";
				$model->vnd_active	 = 1;
				//$model->vnd_cat_type	 = ($model->vnd_cat_type == '') ? 2 : 1;
			}
			$modifyOrApprove = ($type == 'approve') ? "Approve" : "Modify";
			$isNew			 = ($model->isNewRecord) ? "Add" : $modifyOrApprove;

			if (isset($_REQUEST['Vendors']) || isset($_REQUEST['VendorStats']) || isset($_REQUEST['VendorPref']) || isset($_REQUEST['vnp_accepted_zone']))
			{
				$model->attributes				 = Yii::app()->request->getParam('Vendors');
				$model->vendorPrefs->attributes	 = Yii::app()->request->getParam('VendorPref');
				if (($model->vendorPrefs->vnp_oneway == 0 && $oldData['pref']['vnp_oneway'] == -1) || ($model->vendorPrefs->vnp_oneway == 0 && $oldData['pref']['vnp_oneway'] == 1) || ($model->vendorPrefs->vnp_oneway == 0 && $vndId == null))
				{
					$model->vendorPrefs->vnp_oneway = -1;
				}
				if (($model->vendorPrefs->vnp_round_trip == 0 && $oldData['pref']['vnp_round_trip'] == -1) || ($model->vendorPrefs->vnp_round_trip == 0 && $oldData['pref']['vnp_round_trip'] == 1) || ($model->vendorPrefs->vnp_round_trip == 0 && $vndId == null))
				{
					$model->vendorPrefs->vnp_round_trip	 = -1;
					$model->vendorPrefs->vnp_multi_trip	 = -1;
				}
				$model->vendorPrefs->vnp_multi_trip = $model->vendorPrefs->vnp_round_trip;
				if (($model->vendorPrefs->vnp_airport == 0 && $oldData['pref']['vnp_airport'] == -1) || ($model->vendorPrefs->vnp_airport == 0 && $oldData['pref']['vnp_airport'] == 1) || ($model->vendorPrefs->vnp_airport == 0 && $vndId == null))
				{
					$model->vendorPrefs->vnp_airport = -1;
				}
				if (($model->vendorPrefs->vnp_package == 0 && $oldData['pref']['vnp_package'] == -1) || ($model->vendorPrefs->vnp_package == 0 && $oldData['pref']['vnp_package'] == 1) || ($model->vendorPrefs->vnp_package == 0 && $vndId == null))
				{
					$model->vendorPrefs->vnp_package = -1;
				}
				if (($model->vendorPrefs->vnp_flexxi == 0 && $oldData['pref']['vnp_flexxi'] == -1) || ($model->vendorPrefs->vnp_flexxi == 0 && $oldData['pref']['vnp_flexxi'] == 1) || ($model->vendorPrefs->vnp_flexxi == 0 && $vndId == null))
				{
					$model->vendorPrefs->vnp_flexxi = -1;
				}
				if (($model->vendorPrefs->vnp_daily_rental == 0 && $oldData['pref']['vnp_daily_rental'] == -1) || ($model->vendorPrefs->vnp_daily_rental == 0 && $oldData['pref']['vnp_daily_rental'] == 1) || ($model->vendorPrefs->vnp_daily_rental == 0 && $vndId == null))
				{
					$model->vendorPrefs->vnp_daily_rental = -1;
				}
				if (($model->vendorPrefs->vnp_lastmin_booking == 0 && $oldData['pref']['vnp_lastmin_booking'] == -1) || ($model->vendorPrefs->vnp_lastmin_booking == 0 && $oldData['pref']['vnp_lastmin_booking'] == 1) || ($model->vendorPrefs->vnp_lastmin_booking == 0 && $vndId == null))
				{
					$model->vendorPrefs->vnp_lastmin_booking = -1;
				}
				if (($model->vendorPrefs->vnp_tempo_traveller == 0 && $oldData['pref']['vnp_tempo_traveller'] == -1) || ($model->vendorPrefs->vnp_tempo_traveller == 0 && $oldData['pref']['vnp_tempo_traveller'] == 1) || ($model->vendorPrefs->vnp_tempo_traveller == 0 && $vndId == null))
				{
					$model->vendorPrefs->vnp_tempo_traveller = -1;
				}


				$model->vendorStats->attributes	 = Yii::app()->request->getParam('VendorStats');
				$vendorStatArr					 = Yii::app()->request->getParam('VendorStats');
				if ($vendorStatArr['vrs_security_receive_date1'] == "")
				{
					$model->vendorStats->vrs_security_receive_date = NULL;
				}
				if ($model->isNewRecord)
				{
					$model->vendorStats->vrs_dependency	 = 65;
					$model->vnd_active					 = 3;
				}
				if ($type == 'approve')
				{
					$model->vendorStats->vrs_dependency = 65;
					if ($model->vendorStats->vrs_first_approve_date == null)
					{
						$model->vendorStats->vrs_first_approve_date = date("Y-m-d H:i:s");
					}
					$model->vendorStats->vrs_last_approve_date	 = date("Y-m-d H:i:s");
					$model->vendorPrefs->vnp_min_sd_req_amt		 = 2000;
				}

				$model->vendorPrefs->vnp_accepted_zone	 = (!empty($_REQUEST['vnp_accepted_zone'])) ? implode($_REQUEST['vnp_accepted_zone'], ',') : '';
				$model->vendorPrefs->vnp_excluded_cities = Yii::app()->request->getParam('vnp_excluded_cities');
				$agreement_file							 = $_FILES['Vendors']['name']['vnd_agreement_file_link'];
				$agreement_file_tmp						 = $_FILES['Vendors']['type']['vnd_agreement_file_link'];

				$vnp_home_zone = ($model->vendorPrefs->vnp_home_zone != '') ? $model->vendorPrefs->vnp_home_zone : '';

				$returnSet = $model->saveData($oldData, $agreement_file, $agreement_file_tmp, $type);
				if (Yii::app()->request->isAjaxRequest)
				{
					echo CJSON::encode(array('success' => $returnSet->getStatus(), 'errors' => $returnSet->getErrors()));
					Yii::app()->end();
				}
				if ($returnSet->getStatus())
				{
					if ($type == 'unreg')
					{
						$this->redirect(array('vendor/listtoapprove'));
					}
					else
					{
						$this->redirect(array('list'));
					}
				}
			}
		}
		catch (Exception $ex)
		{
			$message = $ex->getMessage();
			$model->addError('vnd_name', $message);
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('add', array('model'			 => $model,
			'uvrcontact'	 => $uvrmodel,
			'modelVendPref'	 => $model->vendorPrefs,
			'modelVendStats' => $model->vendorStats,
			'isNew'			 => $isNew,
			'type'			 => $type,
			'message'		 => $message), false, $outputJs);
	}

	public function actionLinkuser()
	{
		$vndId									 = Yii::app()->request->getParam('vndId');
		$vendorModel							 = Vendors::model()->findByPk($vndId);
		$model									 = new Users();
		$dataProvider							 = $model->linkedVendorId($vndId);
		$dataProvider->getPagination()->params	 = array_filter($_REQUEST);
		$dataProvider->getSort()->params		 = array_filter($_REQUEST);
		$success								 = false;
		$msg									 = "";
		if ($_REQUEST['user_id'] != "" && $_REQUEST['user_id'] != null)
		{
			$userModel = Users::model()->findByPk($_REQUEST['user_id']);
			if ($userModel != '' && $vndId != '')
			{
				$vendorUserModel = Vendors::model()->find('vnd_user_id=:user AND vnd_id=:agent', ['user' => $userModel->user_id, 'agent' => $vndId]);
				if ($vendorUserModel == '')
				{
					$msg = "Error Occurred.";
				}
				else if ($vendorUserModel != '')
				{
					$updateVendorModel				 = Vendors::model()->findByPk($vndId);
					$updateVendorModel->vnd_user_id	 = $userModel->user_id;

					if ($updateVendorModel->save())
					{
						$success = true;
						$msg	 = "User unlinked successfully.";
					}
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
		$this->$method('linkuser', ['dataProvider' => $dataProvider, 'agentModel' => $vendorModel, 'model' => $model], false, true);
	}

	public function actionLink()
	{
		$vndId		 = Yii::app()->request->getParam('vndId');
		$vendorModel = Vendors::model()->findByPk($vndId);
		$model		 = new Users();
		if (isset($_REQUEST['Users']))
		{
			$model->search_name = $_REQUEST['Users']['search_name'];
		}
		$dataProvider							 = $model->searchByNameEmailPhone($vndId);
		$dataProvider->getPagination()->params	 = array_filter($_REQUEST);
		$dataProvider->getSort()->params		 = array_filter($_REQUEST);

		if ($_REQUEST['user_id'] != "" && $_REQUEST['user_id'] != null)
		{
			$msg		 = "Error Occurred.";
			$userModel	 = Users::model()->findByPk($_REQUEST['user_id']);
			if ($userModel != '' && $vndId != '')
			{
				$vendorUserModel = Vendors::model()->find('vnd_user_id=:user AND vnd_id=:agent', ['user' => $userModel->user_id, 'agent' => $vndId]);
				if ($vendorUserModel == '')
				{
					$updateVendorModel				 = Vendors::model()->findByPk($vndId);
					$updateVendorModel->vnd_user_id	 = $userModel->user_id;

					if ($updateVendorModel->save())
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
		$this->$method('link', ['dataProvider' => $dataProvider, 'agentModel' => $vendorModel, 'model' => $model], false, true);
	}

	public function actionUpdateRelationshipManager()
	{
		$this->pageTitle = "Update Relationship Manager";
		$model			 = new Vendors();
		$res			 = false;
		$error			 = 0;

		if ($_POST)
		{
			$oldRmId = Yii::app()->request->getParam('relationshipManager');
			$newRmId = Yii::app()->request->getParam('newRelationshipManager');

			if ($oldRmId == "" || $newRmId == "")
			{
				$error = 1;
				goto skipUpdate;
			}
			if (!Yii::app()->user->checkAccess('updateVendorManager'))
			{
				$error = 2;
				goto skipAccess;
			}
			$res = $model->updateRelationshipManager($oldRmId, $newRmId);

			if ($res == false)
			{
				$message = '<div class="alert alert-danger">Sorry there was an error sending your message. Please try again later</div>';
			}
			else
			{
				$message = '<div class="alert alert-success">Information  updated successfully.</div>';
			}

			skipUpdate:
			if ($res == false && $error == 1)
			{
				$message = '<div class="alert alert-danger">Select relationship manager.</div>';
			}
			skipAccess:
			if ($res == false && $error == 2)
			{
				$message = '<div class="alert alert-danger">Unauthorised Access.</div>';
			}
		}
		$this->render('update_relationship_manager', array('model' => $model, 'res' => $res, 'message' => $message));
	}

	public function actionListDoc()
	{
		$agtid			 = Yii::app()->request->getParam('agtid');
		$model			 = Vendors::model()->findByPk($agtid);
		$docList		 = VendorDocs::model()->findAllByVndId($model->vnd_id, false);
		$name			 = ($model->vnd_owner != '') ? $model->vnd_owner : $model->vnd_name;
		$this->pageTitle = $name . ' - Documents';
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . ( $outputJs ? "Partial" : "");
		$this->render('doclist', array('model' => $model, 'dataProvider' => $docList, 'vndId' => $agtid), false, $outputJs);
	}

	public function actionList()
	{
		$this->pageTitle					 = "Vendor List";
		$cttid								 = Yii::app()->request->getParam('cttid', 0);
		$source								 = Yii::app()->request->getParam('source');
		$model								 = new Vendors('search');
		$model->vendorPrefs					 = new VendorPref('search');
		$model->vendorStats					 = new VendorStats('search');
		$model->vndContact					 = new Contact('search');
		$model->vndContact->contactEmails	 = new ContactEmail('search');
		$model->vndContact->contactPhones	 = new ContactPhone('search');
		$model->vnd_source					 = Yii::app()->request->getParam('source', '');
		$model->vnd_platform				 = 1;

		$vhtModel	 = new VehicleTypes();
		$bkgModel	 = new Booking();

		if ($model->vnd_source == 221)
		{
			$this->pageTitle = "Vendor List ( Vendors ready for approval )";
		}
		if ($model->vnd_source == 232)
		{

			$this->pageTitle = "Vendor List ( Payment Locked Vendors )";
		}
		$pageNum = Yii::app()->request->getParam('page_no');
		$qry	 = [];
		if (isset($_REQUEST['Vendors']) || isset($_REQUEST['VendorPref']) || isset($_REQUEST['VendorStats']) || isset($_REQUEST['Contact']))
		{
			$model->attributes								 = Yii::app()->request->getParam('Vendors');
			$model->vendorPrefs->attributes					 = Yii::app()->request->getParam('VendorPref');
			$model->bkgtypes								 = Yii::app()->request->getParam('Vendors')['bkgtypes'];
			$model->vendorStats->attributes					 = Yii::app()->request->getParam('VendorStats');
			$model->vndContact->attributes					 = Yii::app()->request->getParam('Contact');
			$model->vndContact->contactEmails->attributes	 = Yii::app()->request->getParam('ContactEmail');
			$model->vndContact->contactPhones->attributes	 = Yii::app()->request->getParam('ContactPhone');

			$model->vnd_vehicle_type	 = Yii::app()->request->getParam('VehicleTypes')['vht_id'];
			$vnd_security_paid			 = Yii::app()->request->getParam('Vendors')['vnd_security_paid'];
			$model->vnd_security_paid	 = $vnd_security_paid;
			$qry['vnd_security_paid']	 = $vnd_security_paid;
			$model->vnd_bkg_agent_id	 = Yii::app()->request->getParam('Booking')['bkg_agent_id'];
			$model->vnd_service_class	 = Yii::app()->request->getParam('Vendors')['vnd_service_class'];
			$model->vnd_vehicle_category = Yii::app()->request->getParam('Vendors')['vnd_vehicle_category'];
			$model->vnd_platform		 = Yii::app()->request->getParam('Vendors')['vnd_platform'];
		}
		else
		{
			$model->vnd_status = ($model->vnd_source != 221) ? 1 : '';
		}
		$qry['vendorSource'] = ($model->vnd_source != '') ? $model->vnd_source : '';
		if (trim(Yii::app()->request->getParam('searchdlmismatch')))
		{
			$qry['searchdlmismatch'] = 2;
		}
		if (trim(Yii::app()->request->getParam('searchpanmismatch')))
		{
			$qry['searchpanmismatch'] = 2;
		}

		if ((trim(Yii::app()->request->getParam('searchvndpaymentlock'))) || ($model->vnd_source == 232))
		{
			$qry['searchvndpaymentlock'] = 1;
		}
		$hideMycall = false;
		if ($source == 'mycall')
		{
			$model->vndContact->ctt_id	 = $cttid;
			$hideMycall					 = true;
		}

		$dataProvider = $model->getList(false, $qry);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$this->renderAuto('list', array('dataProvider' => $dataProvider, 'model' => $model, 'vhtModel' => $vhtModel, 'bkgModel' => $bkgModel, 'hideMycall' => $hideMycall));
	}

	public function actionVendorlog()
	{

		$vModels = Vendors::model()->findAll();
		/* @var $vModels Vendors  */
		foreach ($vModels as $model)
		{
			$log = json_decode($model->vnd_log);
			if ($log != '')
			{
				echo "<pre>";
				echo $model->vnd_id . " - " . $model->vnd_name;
				echo "<br>";
				print_r($log);
				echo "<br>";
				exit();
			}
		}

		echo "aaaaaaaaaaaaaaa";
		echo "<pre>";
		print_r($data);
		exit();
	}

	public function actionSearchlist()
	{
		$this->pageTitle = 'Vendor Search List';
		$pageSize		 = Yii::app()->params['listPerPage'];
		$model			 = new Vendors('search');
		if (isset($_REQUEST['Vendors']))
		{
			$model->attributes = Yii::app()->request->getParam('Vendors');
		}
		$dataProvider							 = $model->searchfetchlist();
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->render('searchlist', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionJoining($qry = [])
	{
		$this->pageTitle = "Vendor Joining";
		$pageSize		 = Yii::app()->params['listPerPage'];
		$model			 = new VendorJoining('search');

		if (isset($_REQUEST['VendorsJoining']))
		{
			$model->attributes = Yii::app()->request->getParam('VendorJoining');
		}
		$dataProvider							 = $model->search();
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);

		$this->render('joining', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionJson()
	{
		echo $data = Vendors::model()->getJSON();
		Yii::app()->end();
	}

	/**
	 * @deprecated 2019-12-16 added actionDel
	 * @author ramala
	 */
	public function actionDelOld()
	{

		try
		{
			$success = false;
			$message = '';
			$vndId	 = Yii::app()->request->getParam('vndid');
			$vndRow	 = VendorStats::model()->getBookingByVendorID($vndId);
			if ($vndRow['coutBooking'] > 0 || $vndRow['coutTrans'] > 0)
			{

				throw new Exception("can not delete this vendor!");
			}
			$model				 = Vendors::model()->findByPk($vndId);
			$model->vnd_active	 = 0;
			if ($model->save())
			{
				$userInfo	 = UserInfo::getInstance();
				$event_id	 = VendorsLog::VENDOR_DELETED;
				$message	 = "Vendor is Deleted.";
				VendorsLog::model()->createLog($model->vnd_id, $message, $userInfo, $event_id, false, false);
			}
			else
			{
				throw new Exception("Validate Errors => " . json_encode($model->getErrors()));
			}
			$success = true;
		}
		catch (Exception $ex)
		{
			$message = $ex->getMessage();
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			echo $message;
			Yii::app()->end();
		}
	}

	public function actionChangestatus()
	{
		$agtid		 = Yii::app()->request->getParam('vnd_id');
		$vnd_active	 = Yii::app()->request->getParam('vnd_active');
		$success	 = false;
		$userInfo	 = UserInfo::getInstance();

		$checkVendorBlockAccess		 = Yii::app()->user->checkAccess('vendorBlockStatus');
		$checkVendorUnBlockAccess	 = Yii::app()->user->checkAccess('vendorUnBlockStatus');
		if ($vnd_active == 1 && $checkVendorBlockAccess)
		{
			$model				 = Vendors::model()->resetScope()->findByPk($agtid);
			$model->vnd_active	 = 2;
			if ($model->update())
			{
				$event_id	 = VendorsLog::VENDOR_INACTIVE;
				$desc		 = " Vendor is Blocked.";
				VendorsLog::model()->createLog($model->vnd_id, $desc, $userInfo, $event_id, false, false);
				$success	 = true;
			}
			else
			{
				$success = false;
			}
		}
		else if ($vnd_active == 2 && $checkVendorUnBlockAccess)
		{
			$model				 = Vendors::model()->resetScope()->findByPk($agtid);
			$model->vnd_active	 = 1;
			if ($model->update())
			{
				$event_id	 = VendorsLog::VENDOR_ACTIVE;
				$desc		 = "Vendor is Active/Unblocked.";
				VendorsLog::model()->createLog($model->vnd_id, $desc, $userInfo, $event_id, false, false);
				$success	 = true;
			}
			else
			{
				$success = false;
			}
		}
		echo json_encode(["success" => $success]);
		Yii::app()->end();
	}

	public function actionChangeassign()
	{
		$agtid		 = Yii::app()->request->getParam('vnd_id');
		$agtAssign	 = Yii::app()->request->getParam('vnd_is_freeze');
		$success	 = false;
		$userInfo	 = UserInfo::getInstance();
		if ($agtAssign == 1)
		{
			$model					 = Vendors::model()->resetScope()->findByPk($agtid);
			$model->vnd_is_freeze	 = 0;
			if ($model->save())
			{
				$event_id	 = VendorsLog::VENDOR_UNFREEZE;
				$desc		 = "Vendor is Unfreeze.";
				VendorsLog::model()->createLog($model->vnd_id, $desc, $userInfo, $event_id, false, false);
				$model->save();
				$success	 = true;
			}
			else
			{
				$success = false;
			}
		}
		else if ($agtAssign == 0)
		{
			$model					 = Vendors::model()->resetScope()->findByPk($agtid);
			$model->vnd_is_freeze	 = 1;
			if ($model->save())
			{
				$event_id	 = VendorsLog::VENDOR_FREEZE;
				$desc		 = "Vendor is Freeze.";
				VendorsLog::model()->createLog($model->vnd_id, $desc, $userInfo, $event_id, false, false);
				$model->save();
				$success	 = true;
			}
			else
			{
				$success = false;
			}
		}
		echo json_encode(["success" => $success]);
		Yii::app()->end();
	}

	public function actionFreeze()
	{
		$vndId				 = Yii::app()->request->getParam('vnd_id');
		$vndIsFreeze		 = Yii::app()->request->getParam('vnp_is_freeze');
		$userInfo			 = UserInfo::getInstance();
		/* @var $model Vendors */
		$model				 = Vendors::model()->resetScope()->findByPk($vndId);
		$modelPref			 = VendorPref::model()->resetScope()->find('vnp_vnd_id=:id', ['id' => $vndId]);
		$commentText		 = ($modelPref->vnp_is_freeze > 0) ? 'Add comments on why the vendor is being frozen. What actions are needed before unfreezing them?' : 'Add comments on why the vendor is being not frozen. What actions are needed before freezing them?';
		/* @var $logModel VendorsLog */
		$logModel			 = new VendorsLog();
		$logModel->scenario	 = 'updateFreeze';
		$success			 = false;
		if (isset($_POST['VendorsLog']))
		{
			$logModel->attributes	 = Yii::app()->request->getParam('VendorsLog');
			$arr					 = $logModel->attributes;
			switch ($vndIsFreeze)
			{
				case 0:
					$modelPref->vnp_is_freeze	 = 1;
					$eventId					 = VendorsLog::VENDOR_FREEZE;

					break;
				case 1:
					$modelPref->vnp_is_freeze	 = 0;
					$eventId					 = VendorsLog::VENDOR_UNFREEZE;
					break;
			}
			if ($modelPref->save())
			{
				VendorsLog::model()->createLog($arr['vlg_vnd_id'], $arr['vlg_desc'], $userInfo, $eventId, false, false);
				$success = true;
			}
			else
			{
				$success = false;
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => true];
				echo json_encode($data);
				Yii::app()->end();
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('freeze', array('model'		 => $model, 'modelpref'	 => $modelPref,
			'logModel'	 => $logModel,
			'comment'	 => $commentText), FALSE, $outputJs);
	}

	public function actionApprove()
	{
		$id = Yii::app()->request->getParam('id');
		if ($id != '')
		{
			$model						 = VendorJoining::model()->findByPk($id);
			$model1						 = new Vendors();
			$model1->vnd_phone			 = $model->phone;
			$model1->vnd_name			 = $model->name . "-" . $model->city . "-" . $model->company;
			$model1->vnd_address		 = $model->city;
			$model1->vnd_email			 = $model->email;
			$model1->vnd_company		 = $model->company;
			$model1->vnd_username		 = $model->email;
			$model1->vnd_password1		 = $model->name . "123";
			$model1->vnd_is_exclusive	 = 0;
			$model1->save();
			$model->delete();
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			echo "true";
			Yii::app()->end();
		}
		$this->redirect(array('list'));
	}

	public function actionCheckUser()
	{
		$cttId = Yii::app()->request->getParam('cttid');

		$vndId = Yii::app()->request->getParam('vndid');
		if ($vndId != '')
		{
			$vndModel = Vendors::model()->findByPk($vndId);
			if ($vndModel->vnd_contact_id == $cttId)
			{
				$contactId	 = ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
				$contactId	 = ($contactId == '') ? $vndModel->vnd_contact_id : $contactId;
				$vnddrvexist = Drivers::model()->checkVendorAndDriverContactExist($vndId, $contactId, $cttId);
			}
		}
		$driverIsExist = Drivers::model()->checkDuplicateContactByDriver($cttId);

		$vendIsExist	 = Vendors::model()->checkDuplicateContactByVendor($cttId);
		//echo $vendIsExist;
		$contactDetails	 = Contact::model()->getContactDetails($cttId);
		$contactEmail	 = $contactDetails['eml_email_address'];
		$contactPhone	 = $contactDetails['phn_phone_no'];
		$vendUserIsExist = Vendors::model()->checkDuplicateUserByVendor($cttId);
		$arrJSON		 = array("contid" => $vendIsExist, "drvcontid" => $driverIsExist, "vnddrvexist" => $vnddrvexist, "userid" => $vendUserIsExist, "contemail" => $contactEmail, "contphone" => $contactPhone, "contlicense" => $contactDetails['ctt_license_no']);
		echo $data			 = CJSON::encode($arrJSON);
		Yii::app()->end();
	}

	public function actionZonecity()
	{
		$zone_id	 = Yii::app()->request->getParam('zoneid');
		$arrZones	 = array_filter(explode(',', $zone_id), function ($value) {
			return (trim($value) != "");
		});
		$cityList	 = ZoneCities::model()->getCityByZone(implode(',', $arrZones));
		$arrJSON	 = [];
		foreach ($cityList as $clist)
		{
			$arrJSON[] = array("id" => $clist["cty_id"], "text" => $clist["cty_name"]);
		}

		echo $data = CJSON::encode($arrJSON);
		Yii::app()->end();
	}

	public function actionShowlog()
	{
		$vndId									 = Yii::app()->request->getParam('vndid');
		$viewType								 = Yii::app()->request->getParam('view');
		$model									 = new VendorsLog();
		$dataProvider							 = $model->getByVendorId($vndId, $viewType);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);

		$this->renderPartial('showlog', array('model' => $model, 'dataProvider' => $dataProvider), false, true);
	}

	public function actionShowDuplicateUser()
	{
		$vndId									 = Yii::app()->request->getParam('vndid');
		$userId									 = Yii::app()->request->getParam('userid');
		//$viewType = Yii::app()->request->getParam('view');
		$model									 = new Vendors();
		$dataProvider							 = $model->getDuplicateUserByVendor($vndId, $userId);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);

		$this->renderPartial('showduplicateuser', array('model' => $model, 'dataProvider' => $dataProvider), false, true);
	}

	public function actionShowcontact()
	{
		$vndid			 = Yii::app()->request->getParam('id');
		$model			 = Vendors::model()->findByPk($vndid);
		$this->pageTitle = $model->vnd_name;
		$str			 = trim($model->vnd_phone . ", " . $model->vnd_alt_contact_number);
		echo "Contact :  	<b>" . rtrim($str, ',') . "</b>";
		Yii::app()->end();
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

	public function actionVendoraccount()
	{
		if (isset($_REQUEST['PaymentGateway']) && $_REQUEST['PaymentGateway']['apg_trans_ref_id'] != null)
		{
			$vndId			 = $_REQUEST['PaymentGateway']['apg_trans_ref_id'];
			$ven_from_date	 = trim($_REQUEST['PaymentGateway']['ven_from_date']);
			$ven_to_date	 = trim($_REQUEST['PaymentGateway']['ven_to_date']);
		}
		else
		{
			$vndId			 = Yii::app()->request->getParam('vnd_id');
			$ven_from_date	 = Yii::app()->request->getParam('ven_from_date');
			$ven_to_date	 = Yii::app()->request->getParam('ven_to_date');
		}
		if ($ven_from_date != '' || $ven_to_date != '')
		{
			$dateFromDate	 = date('Y-m-d', strtotime(str_replace('/', '-', $ven_from_date)));
			$dateTodate		 = date('Y-m-d', strtotime(str_replace('/', '-', $ven_to_date)));
		}
		else
		{
			$dateFromDate	 = date('Y-m-d', strtotime("-7 days"));
			$dateTodate		 = date('Y-m-d');
		}
		$this->pageTitle = "Accounts Panel :: Vendor Drill Down";
		try
		{
			if (!is_numeric($vndId))
			{
				throw new CHttpException(400, 'Provide correct vendor id');
			}


			$operatorId = Vendors::getPrimaryId($vndId);

			$record = Vendors::model()->getDrillDownInfo($operatorId);

			/* var $model PaymentGateway */
			$model			 = new PaymentGateway();
			$dateDiffDays	 = round(abs(strtotime($dateFromDate) - strtotime($dateTodate)) / 86400);
			if ($dateDiffDays == '7')
			{
				$model->ven_date_type = '1';
			}
			else
			{
				$model->ven_date_type = '2';
			}
			$model->ven_from_date		 = DateTimeFormat::DateToLocale($dateFromDate);
			$model->ven_to_date			 = DateTimeFormat::DateToLocale($dateTodate);
			$model->apg_ledger_type_id	 = $_REQUEST['PaymentGateway']['apg_ledger_type_id'];
			$model->scenario			 = 'vendor_notes';
			$model1						 = clone $model;

			if (isset($_POST['PaymentGateway']))
			{
				$model->attributes	 = Yii::app()->request->getParam('PaymentGateway');
				$tripid				 = Yii::app()->request->getParam('PaymentGateway')['trip_id'];
				if ($tripid == null)
				{
					$tripid = $operatorId;
				}
				$model->apg_ledger_id = ($_POST['PaymentGateway']['apg_ledger_id_1'] != '') ? $_POST['PaymentGateway']['apg_ledger_id_1'] : $_POST['PaymentGateway']['apg_ledger_id_2'];
				if ($model->apg_date != '')
				{
					$date			 = DateTimeFormat::DatePickerToDate($model->apg_date);
					$time			 = date('H:i:s');
					$model->apg_date = $date . " " . $time;
				}
				else
				{
					$model->apg_date = date('Y-m-d H:i:s');
				}
				if ($model->validate())
				{
					$data			 = ['success' => true];
					$model->apg_id	 = 0;
					$bankRefId		 = NULL;
					$refType		 = NULL;
					$ledgerType		 = Accounting::LI_OPERATOR;
					$accType		 = Accounting::AT_OPERATOR;
					$paymentTypeId	 = PaymentType::model()->payentTypeFromLedger($model->apg_ledger_id);
					if (in_array($model->apg_ledger_id, Accounting::getOnlineLedgers(false)))
					{
						$paymentGateway	 = PaymentGateway::model()->addAmountForOnlineLedger($model, $operatorId, $paymentTypeId, $bankLedgerId, $accType, UserInfo::getInstance());
						$bankRefId		 = $paymentGateway->apg_id;
						$refType		 = Accounting::AT_ONLINEPAYMENT;
						$remarks		 = $model->apg_remarks;
					}
					$remarks = $model->apg_remarks;
					if ($_POST['PaymentGateway']['apg_ledger_id_1'] != '' && $_POST['PaymentGateway']['apg_type'] == 1)
					{
						$addVendorAmount = AccountTransactions::model()->addAmountGozoPaid($model, $operatorId, $bankRefId, $remarks, $refType, $accType, $ledgerType, $date);
					}
					if ($_POST['PaymentGateway']['apg_ledger_id_2'] != '' && $_POST['PaymentGateway']['apg_type'] == 2)
					{
						$addVendorAmount = AccountTransactions::model()->addAmountGozoReceiver($model, $operatorId, $bankRefId, $remarks, $refType, $accType, $ledgerType, $date, $tripid);
					}
				}
				else
				{
					$model1 = $model;
				}
			}
			$date1						 = $dateFromDate;
			$date2						 = $dateTodate;
			$vendorModels				 = AccountTransDetails::vendorTransactionList($operatorId, $date1, $date2, '1', '', $model->apg_ledger_type_id, 'data');
			$vendorModels->setSort(['params' => array_filter($_GET + $_POST)]);
			$vendorModels->setPagination(['params' => array_filter($_GET + $_POST)]);
			$vendorAmount				 = AccountTransDetails::model()->calAmountByVendorId($operatorId, '', $ven_to_date);
			$model1->apg_trans_ref_id	 = $operatorId;
		}
		catch (Exception $ex)
		{
			$errors = $ex->getMessage();
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('report_account', array('vendorModels'	 => $vendorModels,
			'vendorAmount'	 => $vendorAmount,
			'record'		 => $record,
			'model'			 => $model1,
			'agtId'			 => $operatorId,
			'dateFromDate'	 => $dateFromDate,
			'dateTodate'	 => $dateTodate,
			'dateDiffDays'	 => $dateDiffDays,
			'errors'		 => $errors
				), false, $outputJs);
	}

	public function actionUpdateCredit()
	{
		$success	 = false;
		$id			 = Yii::app()->request->getParam("pk");
		$name		 = Yii::app()->request->getParam("name");
		$value		 = Yii::app()->request->getParam("value");
		$model		 = Vendors::model()->resetScope()->findByPk($id);
		$statModel	 = VendorStats::model()->findByPk($model->vendorStats->vrs_id);
		if ($model)
		{
			$oldData					 = $statModel->attributes;
			$oldCreditLimit				 = $statModel->$name;
			$statModel->vrs_credit_limit = $value;
			if ($value != $oldCreditLimit)
			{
				if ($statModel->update())
				{
					$newData			 = $statModel->attributes;
					$userInfo			 = UserInfo::getInstance();
					$eventId			 = VendorsLog::VENDOR_MODIFIED;
					$getOldDifference	 = array_diff_assoc($oldData, $newData);
					$desc				 = "Vendor modified |";
					$changesForLog		 = " Old Values: " . $model->getModificationMSG($getOldDifference, false);
					$desc				 .= $changesForLog;
					VendorsLog::model()->createLog($model->vnd_id, $desc, $userInfo, $eventId, false, false);
				}
			}
			$success = true;
		}
		else
		{
			header('HTTP/1.0 400 Bad Request', true, 400);
			echo "Sorry, Couldn't update";
		}
		Yii::app()->end();
	}

	public function actionInvoice()
	{
		$this->pageTitle = "GOZO Invoices";
		$vendorId		 = Yii::app()->request->getParam('vnd_id');

		if ($vendorId > 0)
		{
			$model				 = Vendors::model()->findByPk($vendorId);
			$model->scenario	 = 'invoice';
			$model->from_date	 = date("d/m/Y", strtotime(date('Y-m-1')));
			$model->to_date		 = date("d/m/Y", strtotime("NOW"));
			if ($_REQUEST['Vendors'])
			{
				$model->attributes	 = $_REQUEST['Vendors'];
				$vendorId			 = ($model->vnd_id != '') ? $model->vnd_id : $vendorId;
				$submit				 = trim($_POST['submit']);
				if ($submit == "2")
				{
					$this->forward('vendor/invoicepdf');
				}
			}
			$record			 = Vendors::model()->getDrillDownInfo($vendorId);
			$dataProvider	 = Booking::model()->getInvoiceData($vendorId, $model->from_date, $model->to_date, 1);
			$openingAmount	 = AccountTransDetails::model()->calAmountByVendorId($vendorId, '', '', $model->from_date);
			$adjustAmount	 = AccountTransDetails::getAdjustableAmountVendor($vendorId, $model->from_date, $model->to_date);
			$this->render('report_invoice', array('model'			 => $model,
				'dataProvider'	 => $dataProvider,
				'openingAmount'	 => $openingAmount,
				'adjustAmount'	 => $adjustAmount,
				'record'		 => $record,
				'vendorId'		 => $vendorId));
		}
		else
		{
			$this->render('report_invoice', array('vendorId' => ''));
		}
	}

	public function actionInvoicepdf($email = 0)
	{
		/* var $model Vendors */
		if (isset($_REQUEST['Vendors']))
		{
			$vendorId			 = Yii::app()->request->getParam('vnd_id');
			$model				 = Vendors::model()->findByPk($vendorId);
			$model->attributes	 = Yii::app()->request->getParam('Vendors');
			$this->GenerateInvoicePdf($vendorId, $model->from_date, $model->to_date);
		}
	}

	public function actionLedgerpdf($email = 0)
	{
		/* var $model VendorTransactions */
		$model = new PaymentGateway();
		if (isset($_REQUEST['PaymentGateway']))
		{
			$model->attributes	 = Yii::app()->request->getParam('PaymentGateway');
			$vendorId			 = $_REQUEST['PaymentGateway']['apg_trans_ref_id'];
			$fromDate			 = trim($_POST['PaymentGateway']['ven_from_date']);
			$toDate				 = trim($_POST['PaymentGateway']['ven_to_date']);
			$this->GenerateLedgerPdf($vendorId, $fromDate, $toDate);
		}
	}

	public function actionOperatorAgreement()
	{
		$vendorId	 = Yii::app()->request->getParam('vendorId');
		$ds			 = Yii::app()->request->getParam('ds');
		$model		 = Vendors::model()->findByPk($vendorId);
		$address	 = Config::getGozoAddress(Config::Corporate_address, true);
		if ($model->vnd_id != '')
		{
			$agmtModel	 = VendorAgreement::model()->findByVndId($model->vnd_id);
			$ctymodel	 = '';
			if ($model->vnd_contact_id != '')
			{
				$contactId		 = ContactProfile::getByEntityId($model->vnd_id, UserInfo::TYPE_VENDOR);
				$contactId		 = ($contactId == '') ? $model->vnd_contact_id : $contactId;
				$contactModel	 = Contact::model()->findByPk($contactId);
				$name			 = $contactModel->getName();
				$vndEmail		 = ContactEmail::model()->getContactEmailById($contactId);
				$vndPhone		 = ContactPhone::model()->getContactPhoneById($contactId);
			}


			if ($model != "" && $agmtModel != "" && $contactModel != "")
			{
				$data				 = array_merge($model->getAttributes(), $agmtModel->getAttributes(), $contactModel->getAttributes());
				$data['name']		 = $name;
				$data['vnd_email']	 = $vndEmail;
				$data['vnd_phone']	 = $vndPhone;
				if ($model->vnd_rel_tier == 1)
				{
					$data['vnd_relation_tier'] = 'Tier 1 (Gold Level)';
				}
				else if ($model->vnd_rel_tier == 0)
				{
					$data['vnd_relation_tier'] = 'Tier 2 (Silver Level)';
				}
				$data['city_name']			 = Cities::getName($contactModel->ctt_city);
				$data['vnd_firm_type_name']	 = Vendors::model()->getFirmByFirmId($contactModel->ctt_user_type);
				$data['vnd_city_name']		 = $ctymodel->cty_name;
				$data['digital_signed']		 = $ds;
				$data['host']				 = Yii::app()->params['host'];
				$data['ctt_user_type']		 = $contactModel->ctt_user_type;
				$data['ctt_business_name']	 = $contactModel->ctt_business_name;
				switch ($contactModel->ctt_user_type)
				{
					case 1:
						$data['vnd_firm_txt']	 = 'OWNER';
						break;
					case 2:
						$data['vnd_firm_txt']	 = 'BUSINESS';
						break;
				}
				$this->renderPartial('generate_agreement_pdf', array(
					'data'		 => $data, 'address'	 => $address), false, false);
			}
		}
	}

	public function actionRelationshipTier()
	{
		$type = Yii::app()->request->getParam('type');
		$this->renderPartial('vendor_relationship_tier', array(
			'type' => $type), false, false);
	}

	public function actionGenerateAgreementForVendor()
	{
		$vendorId	 = urldecode(Yii::app()->request->getParam('vendorId'));
		$ds			 = urldecode(Yii::app()->request->getParam('ds'));
		$email		 = Yii::app()->request->getParam('email');
		$model		 = Vendors::model()->findByPk($vendorId);
		if ($model->vnd_id != '')
		{
			if ($model->vnd_contact_id != '')
			{
				$contactId		 = ContactProfile::getByEntityId($model->vnd_id, UserInfo::TYPE_VENDOR);
				$contactId		 = ($contactId == '') ? $model->vnd_contact_id : $contactId;
				$contactModel	 = Contact::model()->findByPk($contactId);
				$name			 = $contactModel->getName();
				$vndEmail		 = ContactEmail::model()->getContactEmailById($contactId);
				$vndPhone		 = ContactPhone::model()->getContactPhoneById($contactId);
			}
			$agmtModel			 = VendorAgreement::model()->findByVndId($model->vnd_id);
			//$data = $model->getAttributes();
			$data				 = array_merge($model->getAttributes(), $agmtModel->getAttributes(), $contactModel->getAttributes());
			$data['name']		 = $name;
			$data['vnd_email']	 = $vndEmail;
			$data['vnd_phone']	 = $vndPhone;
			if ($model->vnd_rel_tier == 1)
			{
				$data['vnd_relation_tier'] = 'Tier 1 (Gold Level)';
			}
			else if ($model->vnd_rel_tier == 2)
			{
				$data['vnd_relation_tier'] = 'Tier 2 (Silver Level)';
			}
			$data['city_name']			 = Cities::getName($contactModel->ctt_city);
			$data['vnd_firm_type_name']	 = Vendors::model()->getFirmByFirmId($contactModel->ctt_user_type);
			$data['vnd_city_name']		 = $ctymodel->cty_name;
			$data['digital_signed']		 = $ds;
			$data['host']				 = Yii::app()->params['host'];
			$data['ctt_user_type']		 = $contactModel->ctt_user_type;
			$data['ctt_business_name']	 = $contactModel->ctt_business_name;
			switch ($contactModel->ctt_user_type)
			{
				case 1:
					$data['vnd_firm_txt']	 = 'OWNER';
					break;
				case 2:
					$data['vnd_firm_txt']	 = 'BUSINESS';
					break;
			}
		}

		$html2pdf					 = Yii::app()->ePdf->mPdf();
		$css						 = file_get_contents(Yii::getPathOfAlias('webroot.assets.css') . '/vendorpdf.css');
		$html2pdf->writeHTML($css, 1);
		$html2pdf->setAutoTopMargin	 = 'stretch';
		$html2pdf->setHTMLHeader('<table border="0" cellpadding="0" cellspacing="0" width="702" class="no-border header">
                    <tbody>
						<tr>
				<td style="text-align: left"><br><span style="font-weight: bold; font-size: 18pt">Gozo Technologies Private Limited</span><br>
					<span style="text-transform: uppercase; font-size: 16px; font-weight: bold; color: #a8a8a8;">Taxi Operator Agreement</span>
				</td>
				<td style="text-align: right">
					<img src="http://www.aaocab.com/images/logo6.png" style="height: 60px"/></td>
			</tr>
		</tbody>
	</table>
	<hr>');
		$html2pdf->setHTMLFooter('<table id="footer" style="width: 100%"> <tr><td style="text-align: left"><hr>Gozo - Taxi Operator Agreement&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;Rev 37&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;Published 01-Feb-20</td></tr></table>');
		$html2pdf->writeHTML($this->renderPartial('generate_agreement_pdf', array(
					'model'	 => $model,
					'data'	 => $data,
						), true));
		if ($email == 1)
		{
			$filename		 = $vendorId . '-agreement-' . date('Y-m-d') . '.pdf';
			$fileBasePath	 = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'vendors';
			if (!is_dir($fileBasePath))
			{
				mkdir($fileBasePath);
			}
			$filePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . $vendorId;
			if (!is_dir($filePath))
			{
				mkdir($filePath);
			}
			$file = $filePath . DIRECTORY_SEPARATOR . $filename;
			$html2pdf->Output($file, 'F');
			echo $file;
			Yii::app()->end();
		}
		else
		{
			$html2pdf->Output();
		}
	}

	public function actionGenerateSoftCopyForVendor()
	{
		$host						 = Yii::app()->params['host'];
		$baseURL					 = Yii::app()->params['fullBaseURL'];
		$vendorId					 = urldecode(Yii::app()->request->getParam('vendorId'));
		$reqId						 = urldecode(Yii::app()->request->getParam('reqId'));
		$email						 = Yii::app()->request->getParam('email');
		/* @var $model Vendors */
		$model						 = Vendors::model()->findByPk($vendorId);
		$agmtDocs					 = VendorAgmtDocs::model()->findByVndReqId($vendorId, $reqId);
		/* @var $html2pdf mPDF */
		$html2pdf					 = Yii::app()->ePdf->mPdf();
		$css						 = file_get_contents(Yii::getPathOfAlias('webroot.assets.css') . '/vendorpdf.css');
		$html2pdf->writeHTML($css, 1);
		$html2pdf->setAutoTopMargin	 = 'pad';
		$html2pdf->SetMargins(5, 5, 5);
		$html2pdf->setHTMLHeader('');
		$html2pdf->setHTMLFooter('');
		$html2pdf->writeHTML($this->renderPartial('generate_soft_copy_pdf', array(
					'model'		 => $model,
					'agmtDocs'	 => $agmtDocs,
					'host'		 => $host,
					'baseURL'	 => $baseURL
						), true));

		$filename		 = $vendorId . '-agreement-' . $reqId . '-' . date('Y-m-d') . '.pdf';
		$fileBasePath	 = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'vendors';
		if (!is_dir($fileBasePath))
		{
			mkdir($fileBasePath);
		}
		$filePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . $vendorId;
		if (!is_dir($filePath))
		{
			mkdir($filePath);
		}
		$file		 = $filePath . DIRECTORY_SEPARATOR . $filename;
		$softCopy	 = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . $vendorId . DIRECTORY_SEPARATOR . $filename;
		if ($softCopy != '')
		{
			//VendorDocs::model()->updateExistingByIdType($model->vnd_id, 1);
			//Document::model()->updateExistingByIdType($model->vnd_id, $contactId,  1);
			/* @var $modeld VendorDocs */
//			$modeld					 = new VendorDocs();
			//$modeld		= new VendorAgreement();
//			$modeld->vd_type		 = 1;
//			$modeld->vd_file		 = $softCopy;
//			$modeld->vd_vnd_id		 = $model->vnd_id;
//			$modeld->vd_approved_at	 = NULL;
//			$modeld->vd_approved_by	 = NULL;
//			if ($modeld->save())
//			{
			/* @var $modelAgmt VendorAgreement */
			$modelAgmt = VendorAgreement::model()->findByVndId($model->vnd_id);
			if (!$modelAgmt)
			{
				$modelAgmt2					 = new VendorAgreement();
				$modelAgmt2->vag_vnd_id		 = $model->vnd_id;
				$modelAgmt2->vag_soft_date	 = date('Y-m-d H:i:s');
				$modelAgmt2->vag_soft_path	 = $softCopy;
				$modelAgmt2->vag_soft_flag	 = 1;
				$modelAgmt2->vag_soft_ver	 = Yii::app()->params['digitalagmtversion'];
				$modelAgmt2->save();
			}
			else
			{
				$modelAgmt->vag_soft_path	 = $softCopy;
				$modelAgmt->vag_soft_date	 = date('Y-m-d H:i:s');
				$modelAgmt->vag_soft_flag	 = 1;
				$modelAgmt->vag_soft_ver	 = $modelAgmt->vag_digital_ver;
				$modelAgmt->save();
			}
			$success					 = true;
			$errors						 = [];
			$logDesc					 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_AGREMENT_UPLOAD);
			VendorsLog::model()->createLog($model->vnd_id, $logDesc, UserInfo::getInstance(), VendorsLog::VENDOR_FILE_UPLOAD, false, false);
//			}
			$model->vnd_agreement_date	 = date('Y-m-d H:i:s');
			if ($model->save())
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
		$html2pdf->Output($file, 'F');
		Yii::app()->end();
	}

	public function actionGenerateInvoiceForVendor()
	{
		$vendorId					 = urldecode(Yii::app()->request->getParam('vendorId'));
		$fromDate					 = urldecode(Yii::app()->request->getParam('fromDate'));
		$toDate						 = urldecode(Yii::app()->request->getParam('toDate'));
		$email						 = Yii::app()->request->getParam('email');
		$data						 = Vendors::model()->getInvoicePdf($vendorId, $fromDate, $toDate);
		$adjustAmount				 = AccountTransDetails::getAdjustableAmountVendor($vendorId, $fromDate, $toDate);
		$dataList					 = $data['dataList'];
		$record						 = $data['record'];
		$fromDate					 = $data['fromDate'];
		$toDate						 = $data['toDate'];
		$html2pdf					 = $data['pdf'];
		$address					 = Config::getGozoAddress(Config::Corporate_address, true);
		$openingAmount				 = AccountTransDetails::model()->calAmountByVendorId($vendorId, '', '', $fromDate);
		$css						 = file_get_contents(Yii::getPathOfAlias('webroot.assets.css') . '/vendorpdf.css');
		$html2pdf->writeHTML($css, 1);
		$html2pdf->setAutoTopMargin	 = 'stretch';
		$html2pdf->setHTMLHeader('<table border="0" cellpadding="0" cellspacing="0" width="702" class="no-border header">
                    <tbody>
						<tr>
							<td style="text-align: left"><img src="http://www.aaocab.com/images/print-logo.png" style="height: 60px"/><br><span style="font-weight: normal; font-size: 12pt">Gozo Technologies Private Limited</span></td>
							<td style="text-align: right"><table class="borderless"><tr><td style="text-align: left; font-size: 10pt">
									<strong>Corporate Office:</strong><br>
									' . $address . '
										</td></tr></table></td>
						</tr></tbody></table><hr>');
		$html2pdf->setHTMLFooter('<table id="footer" style="width: 100%"> <tr><td style="text-align: center"><hr>www.aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;info@aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9051 877 000</td></tr></table>');
		$html2pdf->writeHTML($this->renderPartial('generateinvoicepdf', array(
					'dataList'		 => $dataList,
					'adjustAmount'	 => $adjustAmount,
					'openingAmount'	 => $openingAmount,
					'record'		 => $record,
					'fromDate'		 => $fromDate,
					'toDate'		 => $toDate
						), true));

		if ($email == 1)
		{
			$filename		 = $vendorId . '-invoice-' . date('Y-m-d') . '.pdf';
			$fileBasePath	 = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'vendors';
			if (!is_dir($fileBasePath))
			{
				mkdir($fileBasePath);
			}
			$filePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . $vendorId;
			if (!is_dir($filePath))
			{
				mkdir($filePath);
			}
			$file = $filePath . DIRECTORY_SEPARATOR . $filename;
			$html2pdf->Output($file, 'F');
			echo $file;
			Yii::app()->end();
		}
		else
		{
			$html2pdf->Output();
		}
	}

	public function actionGenerateLedgerForVendor()
	{
		$vendorId					 = urldecode(Yii::app()->request->getParam('vendorId'));
		$fromDate					 = urldecode(Yii::app()->request->getParam('fromDate'));
		$toDate						 = urldecode(Yii::app()->request->getParam('toDate'));
		$email						 = Yii::app()->request->getParam('email');
		$data						 = AccountTransDetails::model()->getLedgerData($vendorId, $fromDate, $toDate);
		$vendorList					 = $data['vendorList'];
		$vendorAmount				 = $data['vendorAmount'];
		$openingAmount				 = $data['openingAmount'];
		$record						 = $data['record'];
		$fromDate					 = $data['fromDate'];
		$toDate						 = $data['toDate'];
		$html2pdf					 = $data['pdf'];
		$css						 = file_get_contents(Yii::getPathOfAlias('webroot.assets.css') . '/vendorpdf.css');
		$address					 = Config::getGozoAddress(Config::Corporate_address, true);
		$html2pdf->writeHTML($css, 1);
		$html2pdf->setAutoTopMargin	 = 'stretch';
		$html2pdf->setHTMLHeader('<table border="0" cellpadding="0" cellspacing="0" width="702" class="no-border header">
                  <tbody>
                  <tr>
                  <td style="text-align: left"><img src="http://www.aaocab.com/images/print-logo.png" style="height: 60px"/><br><span style="font-weight: normal; font-size: 12pt">Gozo Technologies Private Limited</span></td>
                  <td style="text-align: right"><table class="borderless"><tr><td style="text-align: left; font-size: 10pt">
                  <strong>Corporate Office:</strong><br>
					' . $address . '
                  </td></tr></table></td>
                  </tr></tbody></table><hr>');
		$html2pdf->setHTMLFooter('<table id="footer" style="width: 100%"> <tr><td style="text-align: center"><hr>www.aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;info@aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9051 877 000</td></tr></table>');
		$html2pdf->writeHTML($this->renderPartial('generateledgerpdf', array(
					'vendorList'	 => $vendorList,
					'vendorAmount'	 => $vendorAmount,
					'openingAmount'	 => $openingAmount,
					'record'		 => $record,
					'fromDate'		 => $fromDate,
					'toDate'		 => $toDate
						), true));
		if ($email == 1)
		{
			$filename		 = $vendorId . '-ledger-' . date('Y-m-d') . '.pdf';
			$fileBasePath	 = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'vendors';
			if (!is_dir($fileBasePath))
			{
				mkdir($fileBasePath);
			}
			$filePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . $vendorId;
			if (!is_dir($filePath))
			{
				mkdir($filePath);
			}
			$file = $filePath . DIRECTORY_SEPARATOR . $filename;
			$html2pdf->Output($file, 'F');
			echo $file;
			Yii::app()->end();
		}
		else
		{
			$html2pdf->Output();
		}
	}

	public function GenerateInvoicePdf($vendorId, $fromDate, $toDate, $email = 0)
	{

		$data						 = Vendors::model()->getInvoicePdf($vendorId, $fromDate, $toDate);
		$adjustAmount				 = AccountTransDetails::getAdjustableAmountVendor($vendorId, $fromDate, $toDate);
		$dataList					 = $data['dataList'];
		$record						 = $data['record'];
		$fromDate					 = $data['fromDate'];
		$toDate						 = $data['toDate'];
		$html2pdf					 = $data['pdf'];
		$address					 = Config::getGozoAddress(Config::Corporate_address, true);
		$openingAmount				 = AccountTransDetails::model()->calAmountByVendorId($vendorId, '', '', $fromDate);
		$css						 = file_get_contents(Yii::getPathOfAlias('webroot.assets.css') . '/vendorpdf.css');
		$html2pdf->writeHTML($css, 1);
		$html2pdf->setAutoTopMargin	 = 'stretch';
		$html2pdf->setHTMLHeader('<table border="0" cellpadding="0" cellspacing="0" width="702" class="no-border header">
                    <tbody>
						<tr>
							<td style="text-align: left"><img src="http://www.aaocab.com/images/print-logo.png" style="height: 60px"/><br><span style="font-weight: normal; font-size: 12pt">Gozo Technologies Private Limited</span></td>
							<td style="text-align: right"><table class="borderless"><tr><td style="text-align: left; font-size: 10pt">
									<strong>Corporate Office:</strong><br>
									' . $address . '
										</td></tr></table></td>
						</tr></tbody></table><hr>');
		$html2pdf->setHTMLFooter('<table id="footer" style="width: 100%"> <tr><td style="text-align: center"><hr>www.aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;info@aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9051 877 000</td></tr></table>');
		$html2pdf->writeHTML($this->renderPartial('generateinvoicepdf', array(
					'dataList'		 => $dataList,
					'adjustAmount'	 => $adjustAmount,
					'openingAmount'	 => $openingAmount,
					'record'		 => $record,
					'fromDate'		 => $fromDate,
					'toDate'		 => $toDate
						), true));

		if ($email == 1)
		{
			$filename		 = $vendorId . '-invoice-' . date('Y-m-d') . '.pdf';
			$fileBasePath	 = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'vendors';
			if (!is_dir($fileBasePath))
			{
				mkdir($fileBasePath);
			}
			$filePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . $vendorId;
			if (!is_dir($filePath))
			{
				mkdir($filePath);
			}
			$file = $filePath . DIRECTORY_SEPARATOR . $filename;
			$html2pdf->Output($file, 'F');
			return $file;
		}
		else
		{
			$html2pdf->Output();
		}
	}

	public function GenerateLedgerPdf($vendorId, $fromDate, $toDate, $email = 0)
	{

		$data = AccountTransDetails::model()->getLedgerData($vendorId, $fromDate, $toDate);

		$vendorList					 = $data['vendorList'];
		$vendorAmount				 = $data['vendorAmount'];
		$openingAmount				 = $data['openingAmount'];
		$record						 = $data['record'];
		$fromDate					 = $data['fromDate'];
		$toDate						 = $data['toDate'];
		$html2pdf					 = $data['pdf'];
		$address					 = Config::getGozoAddress(Config::Corporate_address, true);
		$css						 = file_get_contents(Yii::getPathOfAlias('webroot.assets.css') . '/vendorpdf.css');
		$html2pdf->writeHTML($css, 1);
		$html2pdf->setAutoTopMargin	 = 'stretch';
		$html2pdf->setHTMLHeader('<table border="0" cellpadding="0" cellspacing="0" width="702" class="no-border header">
                  <tbody>
                  <tr>
                  <td style="text-align: left"><img src="http://www.aaocab.com/images/print-logo.png" style="height: 60px"/><br><span style="font-weight: normal; font-size: 12pt">Gozo Technologies Private Limited</span></td>
                  <td style="text-align: right"><table class="borderless"><tr><td style="text-align: left; font-size: 10pt">
                  <strong>Corporate Office:</strong><br>
					' . $address . '
                  </td></tr></table></td>
                  </tr></tbody></table><hr>');
		$html2pdf->setHTMLFooter('<table id="footer" style="width: 100%"> <tr><td style="text-align: center"><hr>www.aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;info@aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9051 877 000</td></tr></table>');
		$html2pdf->writeHTML($this->renderPartial('generateledgerpdf', array(
					'vendorList'	 => $vendorList,
					'vendorAmount'	 => $vendorAmount,
					'openingAmount'	 => $openingAmount,
					'record'		 => $record,
					'fromDate'		 => $fromDate,
					'toDate'		 => $toDate
						), true));
		if ($email == 1)
		{
			$filename		 = $vendorId . '-ledger-' . date('Y-m-d') . '.pdf';
			$fileBasePath	 = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'vendors';
			if (!is_dir($fileBasePath))
			{
				mkdir($fileBasePath);
			}
			$filePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . $vendorId;
			if (!is_dir($filePath))
			{
				mkdir($filePath);
			}
			$file = $filePath . DIRECTORY_SEPARATOR . $filename;
			$html2pdf->Output($file, 'F');
			return $file;
		}
		else
		{
			$html2pdf->Output();
		}
	}

	public function actionBulkInvoice()
	{
		$this->pageTitle						 = "Generate Bulk Invoices And Account Statements";
		/* var $model AccountTransDetails */
		$model									 = new AccountTransDetails();
		$pagination								 = 50;
		$dataProvider							 = AccountTransDetails::bulkReport($pagination);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->render('report_bulk_venodor_invoice', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'fromDate'		 => $fromDate,
			'toDate'		 => $toDate));
	}

	public function actionGeneratevendorpdf()
	{
		$vndIds	 = Yii::app()->request->getParam('vndIds');  // $_POST['vndIds'];
		$date1	 = Yii::app()->request->getParam('vndFromDate');   // $_POST['vndFromDate'];
		$date2	 = Yii::app()->request->getParam('vndToDate');  // $_POST['vndToDate'];
		$invoice = Yii::app()->request->getParam('vndInvoice');  // $_POST['vndToDate'];

		/* var $model AccountTransDetails */
		$model				 = new AccountTransDetails();
		$model->attributes	 = Yii::app()->request->getParam('AccountTransDetails');
		$arr				 = $model->attributes;
		$records			 = $model->vendorAccount($date1, $date2, $vndIds, 'invoice');
		$ctr				 = 1;
		$deliveredSent		 = 0;
		$deliveredNotSent	 = 0;

		if (count($records) > 0)
		{
			foreach ($records as $rec)
			{
				$vendorId	 = $rec['vnd_id'];
				$ledgerLink	 = Yii::app()->createAbsoluteUrl('aaohome/vendor/generateLedgerForVendor?vendorId=' . urlencode($vendorId) . '&fromDate=' . urlencode($date1) . '&toDate=' . urlencode($date2) . '&email=1');
				$fileArray	 = [0 => ['URL' => $ledgerLink]];
				if ($invoice == 1)
				{
					$invoiceLink = Yii::app()->createAbsoluteUrl('aaohome/vendor/generateInvoiceForVendor?vendorId=' . urlencode($vendorId) . '&fromDate=' . urlencode($date1) . '&toDate=' . urlencode($date2) . '&email=1');
					$fileArray	 = [0 => ['URL' => $ledgerLink], 1 => ['URL' => $invoiceLink]];
				}
				$attachments	 = json_encode($fileArray);
				$vendorAmount	 = $rec['current_payable'];
				$body			 = 'Dear ' . $rec['vnd_name'] . ',<br/><br/>
                                Attached attached invoice statement from ' . $date1 . ' to ' . $date2 . '.<br>';
				if (isset($vendorAmount) && $vendorAmount > 0)
				{
					$body .= 'Your payment for Rs. ' . $vendorAmount . '  is due immediately.';
				}
				$body	 .= '<br/><br/>Please note our bank details included below. Send all payments to the below address and intimate us with the details of your payment at accounts@aaocab.in';
				$body	 .= '<br/><br/>Beneficiary Name: <b>Gozo Technologies Private Limited</b>';
				$body	 .= '<br/>Bank: <b>HDFC BANK LTD</b>';
				$body	 .= '<br/>Branch: <b>Badshahpur, Gurgaon</b>';
				$body	 .= '<br/>A/c number: <b>50200020818192</b>';
				$body	 .= '<br/>IFSC Code: <b>HDFC0001098</b>';
				$body	 .= '<br/><br/>For all queries please write to accounts@aaocab.in <mailto:accounts@aaocab.in>';
				$body	 .= '<br/><br/>Thank you,';
				$body	 .= '<br/>Team aaocab';

				$subject	 = 'Gozo Invoice for ' . $rec['vnd_name'] . ' from ' . $date1 . ' to ' . $date2 . '';
				$emailCom	 = new emailWrapper();
				$emailCom->vendorInvoiceEmail($subject, $body, $rec['eml_email_address'], $ledgerPdf, $invoicePdf, $vendorId, $attachments, EmailLog::EMAIl_VENDOR_INVOICE);
				$ctr		 = ($ctr + 1);
			}
		}

		$vndIdsArr = explode(',', $vndIds);
		if (count($vndIdsArr) > 0)
		{
			$data = ['success' => true];
			echo json_encode($data);
			Yii::app()->end();
		}
		else
		{
			$data = ['success' => false];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	public function actionGeneratepdf()
	{
		/* var $model VendorTransactions */
		$model = new VendorTransactions();
		if (isset($_REQUEST['VendorTransactions']))
		{
			$model->attributes	 = Yii::app()->request->getParam('VendorTransactions');
			$arr				 = $model->attributes;
			$fromDate			 = trim($_POST['VendorTransactions']['ven_from_date']);
			$toDate				 = trim($_POST['VendorTransactions']['ven_to_date']);
			$vendorId			 = trim($arr['trans_vendor_id']);
			$data				 = $model->getLedgerData($vendorId, $fromDate, $toDate);

			$vendorList		 = $data['vendorList'];
			$vendorAmount	 = $data['vendorAmount'];
			$openingAmount	 = $data['openingAmount'];
			$record			 = $data['record'];
			$fromDate		 = $data['fromDate'];
			$toDate			 = $data['toDate'];
			$html2pdf		 = $data['pdf'];
			$address		 = Config::getGozoAddress(Config::Corporate_address, true);

			$css						 = file_get_contents(Yii::getPathOfAlias('webroot.assets.css') . '/vendorpdf.css');
			$html2pdf->writeHTML($css, 1);
			$html2pdf->setAutoTopMargin	 = 'stretch';
			$html2pdf->setHTMLHeader('<table border="0" cellpadding="0" cellspacing="0" width="702" class="no-border header">
                    <tbody>
						<tr>
							<td style="text-align: left"><img src="http://www.aaocab.com/images/print-logo.png" style="height: 60px"/><br><span style="font-weight: normal; font-size: 12pt">Gozo Technologies Private Limited</span></td>
							<td style="text-align: right"><table class="borderless"><tr><td style="text-align: left; font-size: 10pt">
									<strong>Corporate Office:</strong><br>
									' . $address . '
										</td></tr></table></td>
						</tr></tbody></table><hr>');
			$html2pdf->setHTMLFooter('<table id="footer" style="width: 100%"> <tr><td style="text-align: center"><hr>www.aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;info@aaocab.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9051 877 000</td></tr></table>');
			$html2pdf->writeHTML($this->renderPartial('generatepdf', array(
						'vendorList'	 => $vendorList,
						'vendorAmount'	 => $vendorAmount,
						'openingAmount'	 => $openingAmount,
						'record'		 => $record,
						'fromDate'		 => $fromDate,
						'toDate'		 => $toDate
							), true));
			$html2pdf->Output();
		}
	}

	public function actionListvendoraccount()
	{
		$this->pageTitle = "Accounts Panel :: Vendor Accounts";

		$model = new PaymentGateway();
		if (isset($_REQUEST['PaymentGateway']))
		{
			$model->attributes	 = Yii::app()->request->getParam('PaymentGateway');
			$fromDate			 = trim($_REQUEST['PaymentGateway']['ven_from_date']);
			$toDate				 = trim($_REQUEST['PaymentGateway']['ven_to_date']);
			$venId				 = trim($_REQUEST['PaymentGateway']['apg_trans_ref_id']);
		}
		else
		{
			$fromDate	 = '';
			$toDate		 = '';
			$venId		 = '';
		}
		$vendorId								 = ($_REQUEST['PaymentGateway']['apg_trans_ref_id'] != '') ? $_REQUEST['PaymentGateway']['apg_trans_ref_id'] : $venId;
		$dataProvider							 = AccountTransDetails::model()->vendorAccount($fromDate, $toDate, $vendorId);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->render('report_list_vendoraccount', array(
			'dataProvider'	 => $dataProvider,
			'model'			 => $model,
			'fromDate'		 => $fromDate,
			'toDate'		 => $toDate,
			'vendorId'		 => $vendorId));
	}

	public function actionBroadcastMessage()
	{
		$ids		 = Yii::app()->getRequest()->getParam('vnd_id');
		$message	 = Yii::app()->getRequest()->getParam('message');
		//$peices	 = explode(',', $ids);
		$vndlists	 = Vendors::getVendorsByIds($ids);
		$ext		 = '91';
		foreach ($vndlists as $val)
		{
			//$model3	 = Vendors::model()->findByPk($id);
			$number	 = $val['phn_phone_no'];
			//$name	 = $model3->getName();
			//$name = ($val['vnd_firm_type']== 1)?$val['vnd_owner']:(($val['vnd_firm_type']== 2)?$val['vnd_company']:$val['vnd_owner']);
			//$name = ($val['vnd_firm_type']== 1)?$val['ctt_first_name'].' '.$val['ctt_last_name']:(($val['vnd_firm_type']== 2)?$val['ctt_business_name']:$val['ctt_first_name'].' '.$val['ctt_last_name']);
			$name	 = $val['vnd_name'];
			$msgCom	 = new smsWrapper();
			if ((Yii::app()->getRequest()->getParam('sms')) == 'true')
			{
				$msgCom->informUpdateToVendor($ext, $number, $messageType, $message, $name);
			}
			if ((Yii::app()->getRequest()->getParam('email')) == 'true')
			{
				$emailCom = new emailWrapper();
				//$emailCom->vendorUpdate($id, $message, $messageType);
				$emailCom->vendorUpdate($val['vnd_id'], $message, $messageType);
			}
			if ((Yii::app()->getRequest()->getParam('app')) == 'true')
			{
				$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
				//$success	 = AppTokens::model()->notifyVendor($id, $payLoadData, $message, "Important Notification");
				$success	 = AppTokens::model()->notifyVendor($val['vnd_id'], $payLoadData, $message, "Important Notification");
			}
		}
		$this->redirect(array('list'));
	}

	public function actionMarkedbadlist()
	{
		$vendorId		 = Yii::app()->request->getParam('vnd_id');
		/* var $model Vendors */
		$model			 = new Vendors();
		$dataProvider	 = $model->markedBadListByVendorId($vendorId);
		$this->renderPartial('markedbadlist', array('model'			 => $model,
			'dataProvider'	 => $dataProvider, 'vendorId'		 => $vendorId), false, true);
	}

	public function actionResetmarkedbad()
	{
		$refId				 = Yii::app()->request->getParam('refId');
		/* var $model Drivers */
		$agtModel			 = Vendors::model()->findByPk($refId);
		$old_markbad_count	 = $agtModel->vendorStats->vrs_mark_vend_count;
		//$remark				 = $agtModel->vnd_log;
		//$agtModel->scenario	 = 'reset';
		if (isset($_POST['Vendors']))
		{
			$arr		 = Yii::app()->request->getParam('Vendors');
			$agtModel->resetScope();
			$new_remark	 = $arr['vnd_reset_desc'];
			$userInfo	 = UserInfo::getInstance();
			$success	 = false;
			if ($new_remark != '')
			{
				try
				{
					$agtModel->vendorStats->vrs_mark_vend_count = 0;
					if ($agtModel->vendorStats->save())
					{
						VendorsLog::model()->createLog($agtModel->vnd_id, $new_remark, $userInfo, VendorsLog::VENDOR_REMARK_ADDED, false, false);
						$success = true;
					}
					else
					{
						$success = false;
						$errors	 = $agtModel->vendorStats->getErrors();
						echo $errors;
					}
				}
				catch (Exception $e)
				{
					echo $e;
				}
			}
			else
			{
				$errors = "Please write some descriptions";
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => $success];
				echo json_encode($data);
				Yii::app()->end();
			}
			$this->redirect(array('list', 'tab' => $tab));
		}
		$this->renderPartial('resetmarkedbad', array('refId' => $refId, 'agtModel' => $agtModel), false, true);
	}

	public function actionBookingRequest()
	{
		$model = new Vendors('search');
		if (isset($_REQUEST['Vendors']))
		{
			$model->attributes = Yii::app()->request->getParam('Vendors');
		}

		$dataProvider = $model->resetScope()->search();
		$this->render('booking_request', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionVendorrouterate()
	{
		$vndId		 = Yii::app()->request->getParam('vndId');
		$model		 = new Vendors();
		$routeList	 = $model->rateZoneByRoute($vndId);

		/* var $model  VendorRoutesRate */
		$model2 = new VendorRoutesRate();

		if (isset($_POST['VendorRoutesRate']))
		{
			$model2->attributes	 = Yii::app()->request->getParam('VendorRoutesRate');
			$arr				 = $model2->attributes;

			$route				 = array();
			$route['route_name'] = $_REQUEST['VendorRoutesRate']['vnrr_name'];
			$route['route_rate'] = $_REQUEST['VendorRoutesRate']['vnrr_rate'];
			$route['route_id']	 = $_REQUEST['vnrr_route_id'];

			$agtId		 = $_REQUEST['vnrr_vendor_id'];
			$routeRate	 = array();
			$ctr		 = 0;
			foreach ($route['route_name'] as $name)
			{
				$routeRate[$ctr]['vnrr_name']		 = $name;
				$routeRate[$ctr]['vnrr_vendor_id']	 = $agtId;
				$ctr								 = ($ctr + 1);
			}
			$ctr = 0;
			foreach ($route['route_rate'] as $rate)
			{
				$routeRate[$ctr]['vnrr_rate']	 = $rate;
				$ctr							 = ($ctr + 1);
			}
			$ctr = 0;
			foreach ($route['route_id'] as $routeId)
			{
				$routeRate[$ctr]['vnrr_route_id']	 = $routeId;
				$ctr								 = ($ctr + 1);
			}

			foreach ($routeRate as $rate)
			{


				$model_rate					 = new VendorRoutesRate();
				$model_rate->vnrr_vendor_id	 = $rate['vnrr_vendor_id'];
				$model_rate->vnrr_route_id	 = $rate['vnrr_route_id'];
				$model_rate->vnrr_name		 = $rate['vnrr_name'];
				$model_rate->vnrr_rate		 = $rate['vnrr_rate'];

				$model_rate->save();
			}

			$this->redirect(array('list'));
		}

		$this->renderPartial('vendor_route_rate', array('agtId'		 => $agtId,
			'model'		 => $model,
			'model2'	 => $model2,
			'routeList'	 => $routeList), false, true);
	}

	public function actionShowverifylink()
	{
		$vendorId = Yii::app()->request->getParam('vndid');
		//  emailWrapper::vendorVerifyCabDriverLink($vendorId);
		echo json_encode(['success' => true, 'url' => Yii::app()->createAbsoluteUrl('vendor/vehicle/vehiclelist', ['id' => $vendorId, 'code' => Yii::app()->shortHash->hash($vendorId)])]);
	}

	public function actionListtoapprove()
	{

		$cttid								 = Yii::app()->request->getParam('cttid', 0);
		$source								 = Yii::app()->request->getParam('source');
		$this->pageTitle					 = "Vendors to approve";
		$model								 = new Vendors('search');
		$model->vendorPrefs					 = new VendorPref('search');
		$model->vnd_is_nmi					 = 1;
		$model->vndContact					 = new Contact('search');
		$model->vndContact->contactEmails	 = new ContactEmail('search');
		$model->vndContact->contactPhones	 = new ContactPhone('search');
		$vhtModel							 = new VehicleTypes();
		$model->vnd_active					 = 3;

		$request = Yii::app()->request;
		if ($request->getParam('Vendors'))
		{
			$model->vnd_is_nmi	 = $request->getParam('Vendors')['vnd_is_nmi'] == 1 ? 1 : 0;
			$model->vnd_active	 = $request->getParam('vnd_active') == 2 ? 2 : 3;
			if ($request->getParam('Vendors')['vnd_is_nmi'] == null)
			{
				$model->vnd_is_nmi = Yii::app()->session['vnd_is_nmi'];
			}
			$model->attributes								 = $request->getParam('Vendors');
			$model->vendorPrefs->attributes					 = Yii::app()->request->getParam('VendorPref');
			$model->vndContact->attributes					 = $request->getParam('Contact');
			$model->vndContact->contactEmails->attributes	 = $request->getParam('ContactEmail');
			$model->vndContact->contactPhones->attributes	 = $request->getParam('ContactPhone');

			$model->vnd_vehicle_type = Yii::app()->request->getParam('VehicleTypes')['vht_id'];
		}
		$showListOnly = false;
		if ($source == 'mycall')
		{
			$model->vndContact->ctt_id	 = $cttid;
			$showListOnly				 = true;
		}
		$dataProvider							 = $model->resetScope()->listtoapprove();
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->renderAuto('listtoapprove', array('model' => $model, 'vhtModel' => $vhtModel, 'dataProvider' => $dataProvider, 'showListOnly' => $showListOnly));
	}

	public function actionUnregVendorList()
	{
		$this->pageTitle			 = "3rd Party Vendors List";
		$model						 = new UnregVendorRequest('search');
		$model->uvr_vnd_is_driver	 = ['0'];
		if (isset($_REQUEST['UnregVendorRequest']))
		{
			$model->attributes = Yii::app()->request->getParam('UnregVendorRequest');
		}
		$dataProvider = $model->fetchlist();
		$this->render('unregvendorlist', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionSafedelete()
	{
		$vndId = Yii::app()->request->getParam('agtid');
		if ($vndId != '')
		{
			$model				 = Vendors::model()->findByPk($vndId);
			$model->vnd_active	 = 0;
			if ($model->save())
			{
				$userInfo = UserInfo::getInstance();
				VendorsLog::model()->createLog($vndId, "Vendor deleted manually.", $userInfo, VendorsLog::VENDOR_DELETED, false, false);
				$this->redirect(array('list'));
			}
		}
	}

	public function actionUvrVendorDetails()
	{
		$this->pageTitle = "3rd Party Vendor Details";
		$uvrId			 = Yii::app()->request->getParam('uvr_id');
		$model			 = UnregVendorRequest::model()->findByPk($uvrId);

		$this->render('unregVendordetails', array('model' => $model), false, true);
	}

	public function actionUvrVendorApprove()
	{
		$this->pageTitle = "3rd Party Vendor Details";
//		$vendor			 = Yii::app()->request->getParam('uvrvendor');
		//$status			 = Yii::app()->request->getParam('status', null);
		//$flag			 = Yii::app()->request->getParam('flag', 0);
		//$model			 = Vendors::model()->resetScope()->findByPk($vendor);
		//$model->scenario = 'update';
		//$ftype			 = 'Approve';
		//$userInfo		 = UserInfo::getInstance();
		//if (isset($_REQUEST['Vendors']))
		//{
		//}
//		$isAjaxRequest	 = Yii::app()->request->isAjaxRequest;
//		$method			 = "render" . ( $isAjaxRequest ? "Partial" : "");
		$this->render('unregVendordetails', array(), false, true);
	}

	public function actionUvrdelete()
	{

		$id = Yii::app()->request->getParam('uvr_id');
		if ($id != '')
		{
			$model = UnregVendorRequest::model()->findByPk($id);
			if (count($model) == 1)
			{

				$model->uvr_active = 0;
				$model->update();
			}
		}
		$this->redirect(array('unregvendorlist'));
	}

	public function uploadVendorFiles($uploadedFile, $vendor_id, $type = 'agreement')
	{
		$fileName	 = $vendor_id . "-" . $type . "-" . date('YmdHis') . "." . pathinfo($uploadedFile, PATHINFO_EXTENSION);
		$dir		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
		if (!is_dir($dir))
		{
			mkdir($dir);
		}
		$dirByVendorId = $dir . DIRECTORY_SEPARATOR . $vendor_id;
		if (!is_dir($dirByVendorId))
		{
			mkdir($dirByVendorId);
		}

		$foldertoupload	 = $dirByVendorId . DIRECTORY_SEPARATOR . $fileName;
		$extention		 = pathinfo($uploadedFile, PATHINFO_EXTENSION);
		if (strtolower($extention) == 'png' || strtolower($extention) == 'jpg' || strtolower($extention) == 'jpeg' || strtolower($extention) == 'gif')
		{
			Vehicles::model()->img_resize($uploadedFile->tempName, 1200, $dirByVendorId . DIRECTORY_SEPARATOR, $fileName);
		}
		else
		{
			$uploadedFile->saveAs($foldertoupload);
		}

		$path = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $vendor_id . DIRECTORY_SEPARATOR . $fileName;
		return $path;
	}

	/**
	 * @deprecated since version 14-10-2019
	 * @author ramala as per sudipta roy
	 */
	public function actionMissingAlertTest()
	{
		$mailType = Yii::app()->request->getParam('mailType');
		Booking::model()->testMissingAlert($mailType);
	}

	public function actionlistlogdetails()
	{

		$refId			 = Yii::app()->request->getParam('refId');
		$eventId		 = Yii::app()->request->getParam('eventId');
		/* @var $model VendorsLog */
		$model			 = new VendorsLog();
		$dataProvider	 = $model->getLogDetailsByRefId($refId, $eventId);
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . (($outputJs) ? "Partial" : "");
		$methodUrl		 = '';
		switch ($eventId):
			case '8':
				$methodUrl	 = 'logdetailsSms';
				break;
			case '9':
				$methodUrl	 = 'logdetailsEmail';
				break;
		endswitch;
		$this->$method($methodUrl, array(
			'dataProvider'	 => $dataProvider,
			'refId'			 => $refId), false, $outputJs);
	}

	public function actionView()
	{
		$this->pageTitle = 'Vendor Details';
		$vnd_id			 = Yii::app()->request->getParam('id');
		$vndCode		 = Yii::app()->request->getParam('code');
		$view			 = Yii::app()->request->getParam('view', 'view');
		if ($vndCode != '')
		{
			$vnd	 = Vendors::model()->getIdByCode($vndCode);
			$vnd_id	 = $vnd['vnd_id'];
		}

		$relVendorList	 = Vendors::getRelatedIds($vnd_id);
		$primaryVnd		 = Vendors::getPrimaryByIds($relVendorList);
		$vndId			 = $primaryVnd['vnd_id'];
		$models			 = Vendors::model()->findByPk($vndId);
		if ($models != null)
		{
			// Incase when vendor is merged and current vendor id is not primary vendor
			if ($models->vnd_ref_code != $vndId)
			{
				$vndId	 = $models->vnd_ref_code;
				$models	 = Vendors::model()->findByPk($vndId);
			}

			$data = Vendors::showViewDetails($vndId);
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method($view, array(
			'relVendorList'	 => $relVendorList,
			'model'			 => $models,
			'data'			 => $data,
			'isAjax'		 => $outputJs
				), false, $outputJs);
	}

	/*
	 * Depricated
	 */

	public function actionViewOld()
	{
		$this->pageTitle = 'Vendor Details';
		$vndid			 = Yii::app()->request->getParam('id');
		$vndCode		 = Yii::app()->request->getParam('code');
		$view			 = Yii::app()->request->getParam('view', 'view');

		if ($vndCode != '')
		{
			$vnd	 = Vendors::model()->getIdByCode($vndCode);
			$vndid	 = $vnd['vnd_id'];
		}



		$models = Vendors::model()->findByPk($vndid);
		if ($models != null)
		{
			$data			 = Vendors::model()->getViewDetailbyId($vndid);
			$vndCode		 = Vendors::model()->getCodebyid($vndid);
			//$data['vnd_agreement_file_link'] = $data['vnd_voter_id_path']		 = $data['vnd_voter_id_back_path']	 = $data['vnd_aadhaar_path']		 = $data['vnd_aadhaar_back_path']	 = '';
			//$data['vnd_pan_path']			 = $data['vnd_pan_back_path']		 = $data['vnd_licence_path']		 = $data['vnd_licence_back_path']	 = $data['vnd_firm_attach']		 = '';
			$data['vndCode'] = $vndCode;
			$qry['vndid']	 = $vndid;
			$dataProvider	 = Vendors::getCollectionReport($qry);
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method($view, array(
			'model'			 => $models,
			'data'			 => $data,
			'dataProvider'	 => $dataProvider,
			'isAjax'		 => $outputJs
				), false, $outputJs);
	}

	public function uploadVendorDocument($vndid, $vdType, $uploadedPhoto)
	{
		$fileName	 = $vndid . "-" . $vdType . "-" . date('YmdHis') . "." . pathinfo($uploadedPhoto, PATHINFO_EXTENSION);
		$dir		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
		if (!is_dir($dir))
		{
			mkdir($dir);
		}

		$dirByVendorId = $dir . DIRECTORY_SEPARATOR . 'vendors';
		if (!is_dir($dirByVendorId))
		{
			mkdir($dirByVendorId);
		}

		$vendorId = $dirByVendorId . DIRECTORY_SEPARATOR . $vndid;
		if (!is_dir($vendorId))
		{
			mkdir($vendorId);
		}
		$foldertoupload	 = $vendorId . DIRECTORY_SEPARATOR . $fileName;
		$extention		 = pathinfo($uploadedPhoto, PATHINFO_EXTENSION);
		if (strtolower($extention) == 'png' || strtolower($extention) == 'jpg' || strtolower($extention) == 'jpeg' || strtolower($extention) == 'gif')
		{
			Vehicles::model()->img_resize($uploadedPhoto->tempName, 1200, $vendorId . DIRECTORY_SEPARATOR, $fileName);
		}
		else
		{
			$uploadedPhoto->saveAs($foldertoupload);
		}
		$path = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . $vndid . DIRECTORY_SEPARATOR . $fileName;
		return $path;
	}

	public function actionUpdateDoc()
	{
		$vd_id = Yii::app()->request->getParam('vd_id');

		$vd_status = Yii::app()->request->getParam('vd_status');

		if ($vd_status == 1 || $vd_status == 2)
		{
			/* @var $modeld VendorDocs */
			$modeld					 = VendorDocs::model()->findByPk($vd_id);
			$modeld->vd_status		 = $vd_status;
			$modeld->vd_approved_by	 = ($vd_status == 1) ? Yii::app()->user->getId() : NULL;
			$modeld->vd_approved_at	 = ($vd_status == 1) ? date("Y-m-d H:i:s") : NULL;
			$retrunVal				 = '';
			$event_id				 = 0;
			if ($modeld->save())
			{
				switch ($modeld->vd_type)
				{
					case 1:
						$fileType = "#agreement";
						if ($modeld->vd_status == 1)
						{
							$event_id	 = VendorsLog::VENDOR_DOC_APPROVE;
							$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_AGREEMENT_APPROVE);
						}
						else if ($modeld->vd_status == 2)
						{
							$event_id	 = VendorsLog::VENDOR_AGREEMENT_REJECT;
							$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_AGREEMENT_REJECT);
						}
						break;
					case 2:
						if ($modeld->vd_sub_type == 1)
						{
							$fileType = "#voterid";
							if ($modeld->vd_status == 1)
							{
								$event_id	 = VendorsLog::VENDOR_DOC_APPROVE;
								$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_VOTERID_APPROVE);
							}
							else if ($modeld->vd_status == 2)
							{
								$event_id	 = VendorsLog::VENDOR_VOTERID_REJECT;
								$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_VOTERID_REJECT);
							}
						}
						else if ($modeld->vd_sub_type == 2)
						{
							$fileType = "#voterbackid";
							if ($modeld->vd_status == 1)
							{
								$event_id	 = VendorsLog::VENDOR_DOC_APPROVE;
								$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_VOTERID_BACK_APPROVE);
							}
							else if ($modeld->vd_status == 2)
							{
								$event_id	 = VendorsLog::VENDOR_VOTERID_BACK_APPROVE;
								$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_VOTERID_BACK_APPROVE);
							}
						}
						break;
					case 3:
						if ($modeld->vd_sub_type == 1)
						{
							$fileType = "#aadhaarid";
							if ($modeld->vd_status == 1)
							{
								$event_id	 = VendorsLog::VENDOR_DOC_APPROVE;
								$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_AADHAAR_APPROVE);
							}
							else if ($modeld->vd_status == 2)
							{
								$event_id	 = VendorsLog::VENDOR_AADHAAR_REJECT;
								$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_AADHAAR_REJECT);
							}
						}
						else if ($modeld->vd_sub_type == 2)
						{
							$fileType = "#aadhaarbackid";
							if ($modeld->vd_status == 1)
							{
								$event_id	 = VendorsLog::VENDOR_DOC_APPROVE;
								$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_AADHAAR_BACK_APPROVE);
							}
							else if ($modeld->vd_status == 2)
							{
								$event_id	 = VendorsLog::VENDOR_AADHAAR_BACK_REJECT;
								$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_AADHAAR_BACK_REJECT);
							}
						}
						break;
					case 4:
						if ($modeld->vd_sub_type == 1)
						{
							$fileType = "#panid";
							if ($modeld->vd_status == 1)
							{
								$event_id	 = VendorsLog::VENDOR_DOC_APPROVE;
								$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_PAN_APPROVE);
							}
							else if ($modeld->vd_status == 2)
							{
								$event_id	 = VendorsLog::VENDOR_PAN_REJECT;
								$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_PAN_REJECT);
							}
						}
						else if ($modeld->vd_sub_type == 2)
						{
							$fileType = "#panbackid";
							if ($modeld->vd_status == 1)
							{
								$event_id	 = VendorsLog::VENDOR_DOC_APPROVE;
								$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_PAN_BACK_APPROVE);
							}
							else if ($modeld->vd_status == 2)
							{
								$event_id	 = VendorsLog::VENDOR_PAN_BACK_REJECT;
								$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_PAN_BACK_REJECT);
							}
						}
						break;
					case 5:
						if ($modeld->vd_sub_type == 1)
						{
							$fileType = "#licence";
							if ($modeld->vd_status == 1)
							{
								$event_id	 = VendorsLog::VENDOR_DOC_APPROVE;
								$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_LICENSE_APPROVE);
							}
							else if ($modeld->vd_status == 2)
							{
								$event_id	 = VendorsLog::VENDOR_LICENSE_REJECT;
								$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_LICENSE_REJECT);
							}
						}
						else if ($modeld->vd_sub_type == 2)
						{
							$fileType = "#licenceback";
							if ($modeld->vd_status == 1)
							{
								$event_id	 = VendorsLog::VENDOR_DOC_APPROVE;
								$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_LICENSE_BACK_APPROVE);
							}
							else if ($modeld->vd_status == 2)
							{
								$event_id	 = VendorsLog::VENDOR_LICENSE_BACK_REJECT;
								$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_LICENSE_BACK_REJECT);
							}
						}
						break;
					case 6:
						$fileType = "#memorandum";
						if ($modeld->vd_status == 1)
						{
							$event_id	 = VendorsLog::VENDOR_DOC_APPROVE;
							$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_AGREEMENT_APPROVE);
						}
						else if ($modeld->vd_status == 2)
						{
							$event_id	 = VendorsLog::VENDOR_AGREEMENT_REJECT;
							$desc		 = VendorsLog::model()->getEventByEventId(VendorsLog::VENDOR_AGREEMENT_REJECT);
						}
						break;
				}
				$userInfo	 = UserInfo::getInstance();
				VendorsLog::model()->createLog($modeld->vd_vnd_id, $desc, $userInfo, $event_id, false, false);
				$retrunVal	 = $fileType . "~" . $modeld->vd_status . "~" . $modeld->vd_remarks;
			}
		}
		else
		{
			/* @var $modeld VendorDocs */
			$modeld = VendorDocs::model()->findByPk($vd_id);
			switch ($modeld->vd_type)
			{
				case 1:
					$fileType = "#agreement";
					break;
				case 2:
					if ($modeld->vd_sub_type == 1)
					{
						$fileType = "#voterid";
					}
					else if ($modeld->vd_sub_type == 2)
					{
						$fileType = "#voterbackid";
					}
					break;
				case 3:
					if ($modeld->vd_sub_type == 1)
					{
						$fileType = "#aadhaarid";
					}
					else if ($modeld->vd_sub_type == 2)
					{
						$fileType = "#aadhaarbackid";
					}
					break;
				case 4:
					if ($modeld->vd_sub_type == 1)
					{
						$fileType = "#panid";
					}
					else if ($modeld->vd_sub_type == 2)
					{
						$fileType = "#panbackid";
					}
					break;
				case 5:
					if ($modeld->vd_sub_type == 1)
					{
						$fileType = "#licence";
					}
					else if ($modeld->vd_sub_type == 2)
					{
						$fileType = "#licenceback";
					}
					break;
				case 6:
					$fileType = "#memorandum";
					break;
			}
			$retrunVal = $fileType . "~" . '3';
		}
		echo $retrunVal;
	}

	public function actionRejectDoc()
	{
		$vd_id			 = Yii::app()->request->getParam('vd_id');
		$vd_status		 = Yii::app()->request->getParam('vd_status');
		$success		 = false;
		$dmodel			 = VendorDocs::model()->findByPk($vd_id);
		$model			 = new VendorDocs();
		$model->scenario = 'reject';
		if (isset($_POST['VendorDocs']))
		{
			$model->attributes	 = Yii::app()->request->getParam('VendorDocs');
			$arr				 = $model->attributes;
			$user_id			 = Yii::app()->user->getId();
			$user_info			 = UserInfo::getInstance();
			$returnData			 = $model->rejectDocument($vd_id, $arr['vd_remarks'], $user_info);
			if (Yii::app()->request->isAjaxRequest)
			{
				$remarks = '<i>' . $arr['vd_remarks'] . '</i>';
				$data	 = ['success' => $returnData['success'], 'file_type' => $returnData['fileType'], 'status' => $vd_status, 'remarks' => $arr['vd_remarks']];
				echo json_encode($data);
				Yii::app()->end();
			}
		}
		$this->renderPartial('rejectremarks', array('vd_id'		 => $vd_id,
			'vd_status'	 => $vd_status,
			'model'		 => $model,
			'dmodel'	 => $dmodel), false, true);
	}

	public function actionRevert()
	{
		$vndId			 = Yii::app()->request->getParam('vndid');
		$reason			 = Yii::app()->request->getParam('reason');
		$reason_other	 = Yii::app()->request->getParam('reason_other');

		if (isset($_REQUEST['vnd_delete_other']))
		{
			$result = Vendors::revertVendor($vndId, $_REQUEST['vnd_delete_other']);

			if (Yii::app()->request->isAjaxRequest)
			{
				echo json_encode($result);
				Yii::app()->end();
			}
		}
		$this->renderPartial('revertvendor', array('vndid' => $vndId, 'reason' => $reason, 'reason_other' => $reason_other));
	}

	public function actionBlock()
	{
		$vnd_id	 = Yii::app()->request->getParam('vnd_id');
		//$vnd_active = Yii::app()->request->getParam('vnd_active');
		$reason	 = Yii::app()->request->getParam('vnd_reason');

		$success = false;
		$model	 = Vendors::model()->resetScope()->findByPk($vnd_id);

		if (isset($_POST['vnd_id']) && $_POST['vnd_id'] == $model->vnd_id && $model->vnd_active == 1)
		{
			if (isset($_POST['vnd_reason']) && trim($reason) != '')
			{
				$chkopt				 = Yii::app()->request->getParam('chkopt');
				$ven_id				 = $model->vnd_id;
				$model->vnd_active	 = 2;
				// $success = true;
				if ($model->update())
				{
					Vendors::model()->unAssignBlockVendor($ven_id);
					$event_id	 = VendorsLog::VENDOR_INACTIVE;
					$desc		 = "Vendor is Blocked. Reason: " . trim($reason);
					$userInfo	 = UserInfo::getInstance();
					VendorsLog::model()->createLog($ven_id, $desc, $userInfo, $event_id);
					$success	 = true;

					if ($chkopt && $chkopt[0] == 1)
					{
						$ext	 = '91';
						$number	 = ContactPhone::getContactPhoneById($model->vnd_contact_id);

						$success = Vendors::notifyToAccountBlocked($ven_id);

						//$number		 = $model->vnd_phone;
						/*
						  $response	 = WhatsappLog::accountBlocked($ven_id, $model->vnd_contact_id);
						  if($response['status'] == 3)
						  {
						  $msgCom		 = new smsWrapper();
						  $message	 = 'You are inactive.';
						  $name		 = $model->vndContact->getName();
						  //$name		 = $model->vnd_owner;
						  $msgCom->informVendorOnBlocknFreezed($ext, $number, $message, $name, $ven_id);
						  }
						  //App Notification
						  $title		 = "Important Notification";
						  $payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
						  $success	 = AppTokens::model()->notifyVendor($ven_id, $payLoadData, $message, $title);
						 */
					}
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
				$result['error']	 = 'Please give the reason to block this vendor';
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
		$this->$method('blockreason', array('model' => $model), FALSE, $outputJs);
	}

	public function actionAddremark()
	{
		$vnd_id = Yii::app()->request->getParam('vnd_id');

		$reason = Yii::app()->request->getParam('vnd_remark');

		$success = false;
		$model	 = Vendors::model()->resetScope()->findByPk($vnd_id);

		if (isset($_POST['vnd_id']) && $_POST['vnd_id'] == $model->vnd_id)
		{
			if (isset($_POST['vnd_remark']) && trim($reason) != '')
			{
				// $success = true;
				if ($model->update())
				{
					$event_id	 = VendorsLog::VENDOR_REMARK_ADDED;
					$desc		 = "Remarks : " . trim($reason);
					$userInfo	 = UserInfo::getInstance();
					VendorsLog::model()->createLog($model->vnd_id, $desc, $userInfo, $event_id);
					$success	 = true;
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
				$result['error']	 = 'Remarks is blank';
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
		$this->$method('addremark', array('model' => $model), FALSE, $outputJs);
	}

	public function actionSendCustomMessage()
	{
		$vnd_id	 = Yii::app()->request->getParam('vnd_id');
		$model	 = Vendors::model()->findByPk($vnd_id);
		$success = false;

		if (isset($_POST['vnd_id']) && $_POST['vnd_id'] == $model->vnd_id)
		{
			$arrVend		 = Yii::app()->request->getParam('Vendors');
			$contactId		 = ContactProfile::getByEntityId($model->vnd_id, UserInfo::TYPE_VENDOR);
			$objPhoneNumber	 = ContactPhone::getPrimaryNumber($contactId);
			$email			 = ContactEmail::getPrimaryEmail($contactId);
			if ($arrVend['vnd_email'] > 0 || $arrVend['vnd_notification'] > 0 || $arrVend['vnd_sms'] > 0)
			{
				if ($arrVend['vnd_sms'] > 0)
				{
					if ($objPhoneNumber)
					{
						$countryCode = $objPhoneNumber->getCountryCode();
						$number		 = $objPhoneNumber->getNationalNumber();
					}
					$smsModel			 = new smsWrapper();
					$smsModel->sendLinkVendor($model->vnd_id, $number, $countryCode, $arrVend['vnd_message']);
					$result['success']	 = true;
				}
				if ($arrVend['vnd_notification'] > 0)
				{
					$payLoadData		 = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
					AppTokens::model()->notifyVendor($model->vnd_id, $payLoadData, $arrVend['vnd_message'], $arrVend['vnd_subject']);
					$result['success']	 = true;
				}
				if ($arrVend['vnd_email'] > 0)
				{
					if ($email != '')
					{
						$emailwrapper		 = new emailWrapper();
						$emailwrapper->sendCustomEmail($model->vnd_id, $email, $arrVend['vnd_message'], $arrVend['vnd_subject']);
						$result['success']	 = true;
					}
					else
					{
						$result				 = [];
						$result['error']	 = 'This vendor have not any mail id';
						$result['success']	 = false;
					}
				}
			}
			else
			{
				$result				 = [];
				$result['error']	 = 'Please check atleast one checkbox';
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
		$this->$method('sendLink', array('model' => $model), FALSE, $outputJs);
	}

	public function actionAdministrativefreeze()
	{
		$vndId			 = Yii::app()->request->getParam('vnd_id');
		$vndFrozen		 = Yii::app()->request->getParam('vnp_is_freeze');
		$reason			 = Yii::app()->request->getParam('vnd_reason');
		$success		 = false;
		$userInfo		 = UserInfo::getInstance();
		$model			 = Vendors::model()->resetScope()->findByPk($vndId);
		$modelPref		 = VendorPref::model()->resetScope()->find('vnp_vnd_id=:id', ['id' => $vndId]);
		$freezeStatus	 = ($modelPref->vnp_is_freeze == 0) ? 'freeze' : 'unfreeze';
		if (isset($_POST['vnd_id']) && $_POST['vnd_id'] == $model->vnd_id)
		{
			try
			{
				if (isset($_POST['vnd_reason']) && trim($reason) != '')
				{
					$chkopt = Yii::app()->request->getParam('chkopt');

					$success = Vendors::model()->freezeVendor($vndId, Vendors::FR_MANUAL_FREEZE, $chkopt, $reason);
					$result	 = [];
					//\Sentry\captureMessage('check freeze update status - ' . $success, null);

					if ($success)
					{
						$result['success'] = $success;
					}
					else
					{

						$result['error'] = 'Some Error occured';
						throw new Exception(json_encode($result['error']), 1);
					}
				}
				else
				{
					$result				 = [];
					$result['error']	 = "Please give the reason to $freezeStatus this vendor";
					$result['success']	 = $success;
				}
				if (Yii::app()->request->isAjaxRequest)
				{
					echo json_encode($result);
					Yii::app()->end();
				}
			}
			catch (Exception $e)
			{
				$returnSet = ReturnSet::setException($e);
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('admfreeze', array('model' => $model, 'modelpref' => $modelPref), FALSE, $outputJs);
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

	public function actionRegProgress()
	{
		$this->pageTitle = "Vendor Registration Progress";
		$model			 = new Vendors();
		$request		 = Yii::app()->request;
		$showListOnly	 = false;
		$source			 = $request->getParam('source');
		$vnd_id			 = $request->getParam('vnd_id');
		if ($source == 'mycall')
		{
			$model->vnd_id	 = $vnd_id;
			$showListOnly	 = true;
		}
		if ($request->getParam('Vendors'))
		{
			$arr = $request->getParam('Vendors');

			$region					 = ($arr['vnd_region'] != '') ? $arr['vnd_region'] : '';
			$zone					 = ($arr['vnd_zone'] != '') ? $arr['vnd_zone'] : '';
			$model->vnd_region		 = $region;
			$model->vnd_zone		 = $zone;
			$model->vnd_is_loggedin	 = ($arr['vnd_is_loggedin'] != '') ? $arr['vnd_is_loggedin'] : '';
			$model->vnd_is_voterid	 = ($arr['vnd_is_voterid'] != '') ? $arr['vnd_is_voterid'] : '';
			$model->vnd_is_pan		 = ($arr['vnd_is_pan'] != '') ? $arr['vnd_is_pan'] : '';
			$model->vnd_is_aadhar	 = ($arr['vnd_is_aadhar'] != '') ? $arr['vnd_is_aadhar'] : '';
			$model->vnd_is_license	 = ($arr['vnd_is_license'] != '') ? $arr['vnd_is_license'] : '';
			$model->vnd_is_agreement = ($arr['vnd_is_agreement'] != '') ? $arr['vnd_is_agreement'] : '';
			$model->vnd_operator	 = ($arr['vnd_operator'] != '') ? $arr['vnd_operator'] : '';
			$model->vnd_is_bank		 = ($arr['vnd_is_bank'] != '') ? $arr['vnd_is_bank'] : '';
			//$model->vnd_is_approve	 = ($arr['vnd_is_approve'] != '') ? $arr['vnd_is_approve'] : '';
			$model->vnd_city		 = ($arr['vnd_city'] != '') ? $arr['vnd_city'] : '';
			$model->vnd_vehicle_type = ($arr['vnd_car_model'] != '') ? $arr['vnd_car_model'] : '';
			$model->vnd_email		 = ($arr['vnd_email'] != '') ? $arr['vnd_email'] : '';
			$model->vnd_phone		 = ($arr['vnd_phone'] != '') ? $arr['vnd_phone'] : '';

			$model->vnd_create_date1 = ($arr['vnd_create_date1'] != '') ? DateTimeFormat::DatePickerToDate($arr['vnd_create_date1']) : '';
			$model->vnd_active		 = ($arr['vnd_active'] != '') ? $arr['vnd_active'] : '';
			$model->vnd_is_nmi		 = ($arr['vnd_is_nmi'] != '') ? $arr['vnd_is_nmi'] : '';
		}
		if (isset($_REQUEST['export1']) && $_REQUEST['export1'] == true)
		{
			$region					 = ($_REQUEST['export_vnd_region'] != '') ? $_REQUEST['export_vnd_region'] : '';
			$zone					 = ($_REQUEST['export_vnd_zone'] != '') ? $_REQUEST['export_vnd_zone'] : '';
			$args					 = array('region' => $region, 'zone' => $zone);
			$model->vnd_is_loggedin	 = ($_REQUEST['export_vnd_is_loggedin'] != '') ? $_REQUEST['export_vnd_is_loggedin'] : '';
			$model->vnd_is_voterid	 = ($_REQUEST['export_vnd_is_voterid'] != '') ? $_REQUEST['export_vnd_is_voterid'] : '';
			$model->vnd_is_pan		 = ($_REQUEST['export_vnd_is_pan'] != '') ? $_REQUEST['export_vnd_is_pan'] : '';
			$model->vnd_is_aadhar	 = ($_REQUEST['export_vnd_is_aadhar'] != '') ? $_REQUEST['export_vnd_is_aadhar'] : '';
			$model->vnd_is_license	 = ($_REQUEST['export_vnd_is_license'] != '') ? $_REQUEST['export_vnd_is_license'] : '';
			$model->vnd_is_agreement = ($_REQUEST['export_vnd_is_agreement'] != '') ? $_REQUEST['export_vnd_is_agreement'] : '';
			$model->vnd_operator	 = ($_REQUEST['export_vnd_operator'] != '') ? $_REQUEST['export_vnd_operator'] : '';
			$model->vnd_is_bank		 = ($_REQUEST['export_vnd_is_bank'] != '') ? $_REQUEST['export_vnd_is_bank'] : '';
			//$model->vnd_is_approve	 = ($_REQUEST['export_vnd_is_approve'] != '') ? $_REQUEST['export_vnd_is_approve'] : '';
			$model->vnd_city		 = ($_REQUEST['export_vnd_city'] != '') ? $_REQUEST['export_vnd_city'] : '';
			$model->vnd_vehicle_type = ($_REQUEST['export_vnd_car_model'] != '') ? $_REQUEST['export_vnd_car_model'] : '';
			$model->vnd_email		 = ($_REQUEST['export_vnd_email'] != '') ? $_REQUEST['export_vnd_email'] : '';
			$model->vnd_phone		 = ($_REQUEST['export_vnd_phone'] != '') ? $_REQUEST['export_vnd_phone'] : '';
			$model->vnd_create_date1 = ($_REQUEST['export_vnd_create_date1'] != '') ? date("Y-m-d", strtotime($_REQUEST['export_vnd_create_date1'])) : '';
			$model->vnd_active		 = ($_REQUEST['export_vnd_active'] != '') ? $_REQUEST['export_vnd_active'] : '';
			$model->vnd_is_nmi		 = ($_REQUEST['export_vnd_is_nmi'] != '') ? $_REQUEST['export_vnd_is_nmi'] : '';
			$data					 = $model->getRegistrationProgress('command', $args);
			$model->getRegistrationProgressCSVReport($data);
		}
		$args			 = array('region' => $region, 'zone' => $zone);
		$dataProvider	 = $model->getRegistrationProgress('', $args);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->renderAuto('report_reg_progress', array('dataProvider' => $dataProvider, 'model' => $model, 'showListOnly' => $showListOnly));
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

	public function actionChangecod()
	{
		$vndId		 = Yii::app()->request->getParam('vnd_id');
		$vndCod		 = Yii::app()->request->getParam('vnd_cod');
		$freezeType	 = Vendors::FR_COD_FREEZE;
		$success	 = Vendors::model()->freezeVendor($vndId, $freezeType, $vndCod);
		echo json_encode(["success" => $success]);
		Yii::app()->end();
	}

	public function actionUpdateStats()
	{
		$vndId = Yii::app()->request->getParam('vnd_id');

		echo json_encode(["success" => $success]);
		Yii::app()->end();
	}

	public function saveVendorImage($image, $imagetmp, $vendorId, $type)
	{
		try
		{
			$path = "";
			if ($image != '')
			{
				$image	 = $vendorId . "-" . $type . "-" . date('YmdHis') . "." . $image;
				$dir	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
				if (!is_dir($dir))
				{
					mkdir($dir);
				}
				$dirFolderName = $dir . DIRECTORY_SEPARATOR . 'vendors';
				if (!is_dir($dirFolderName))
				{
					mkdir($dirFolderName);
				}
				$dirByVendorId = $dirFolderName . DIRECTORY_SEPARATOR . $vendorId;
				if (!is_dir($dirByVendorId))
				{
					mkdir($dirByVendorId);
				}
				$file_path	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'vendors' . DIRECTORY_SEPARATOR . $vendorId;
				$file_name	 = basename($image);
				$f			 = $file_path;
				$file_path	 = $file_path . DIRECTORY_SEPARATOR . $file_name;
				file_put_contents(PUBLIC_PATH . '/testFile.txt', $f . ' ==== ' . $file_name);
				Yii::log("Image Path: \n\t Temp: " . $imagetmp . "\n\t Path: " . $f, CLogger::LEVEL_INFO, 'system.api.images');
				if (Vehicles::model()->img_resize($imagetmp, 1500, $f, $file_name))
				{
					$path	 = substr($file_path, strlen(PUBLIC_PATH));
					$result	 = ['path' => $path];
				}
			}
		}
		catch (Exception $e)
		{
			Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
			Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
			throw $e;
		}
		return $result;
	}

	public function actionDocapprovallist()
	{
		$model			 = new Document();
		$modelVendor	 = new Vendors();
		$this->pageTitle = "Vendor Pending Doc Approval";
		$arr			 = [];
		$contactId		 = Yii::app()->getRequest()->getParam('ctt_id');
		if ($contactId != "")
		{
			$Vendor				 = Vendors::model()->findByVendorContactID($contactId);
			$modelVendor->vnd_id = $Vendor->vnd_id;
			$arrVen['vnd_id']	 = $Vendor->vnd_id;
		}
		if (isset($_REQUEST['Document']))
		{
			$arr				 = Yii::app()->request->getParam('Document');
			$arrVen				 = Yii::app()->request->getParam('Vendors');
			$model->doc_type	 = $arr['doc_type'];
			$model->contactname	 = $arr['contactname'];
			$modelVendor->vnd_id = $arrVen['vnd_id'];
		}
		$dataProvider							 = $model->getUnapproved($arr, $arrVen);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$outputJs								 = Yii::app()->request->isAjaxRequest;
		$method									 = "render" . ( $outputJs ? "Partial" : "");
		$this->render('docapprovallist', array('modelVendor' => $modelVendor, 'model' => $model, 'dataProvider' => $dataProvider), false, $outputJs);
	}

	public function actionShowdocimg()
	{
		$vdid	 = Yii::app()->request->getParam('vdid');
		$vmodel	 = VendorDocs::model()->findByPk($vdid);
		$model	 = Vendors:: model()->resetScope()->findByPk($vmodel->vd_vnd_id);

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('picshow', ['vmodel' => $vmodel, 'model' => $model], false, $outputJs);
	}

	public function actionShowdoc()
	{

		$vndId		 = Yii::app()->request->getParam('vndId');
		$vndType	 = Yii::app()->request->getParam('vndType');
		$vndSubType	 = Yii::app()->request->getParam('vndSubType');
		$model		 = Vendors::model()->findByPk($vndId);
		$vmodel		 = new VendorDocs();
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('showdoc', ['model' => $model, 'vmodel' => $vmodel], false, $outputJs);
	}

	public function actionApprovedocimg()
	{
		$btntype	 = Yii::app()->request->getParam('btntype');
		$vdDocs		 = Yii::app()->request->getParam('VendorDocs');
		$vdid		 = $vdDocs['vd_id'];
		$dmodel		 = VendorDocs::model()->resetScope()->findByPk($vdid);
		$oldDocData	 = $dmodel->attributes;
		$userInfo	 = UserInfo::getInstance();
		$fileType	 = '';
		$fileType1	 = [];
		if ($dmodel)
		{
			$vndModel	 = Vendors::model()->resetScope()->findByPk($dmodel->vd_vnd_id);
			$oldVndData	 = $vndModel->attributes;
			$vnd		 = Yii::app()->request->getParam('Vendors');
			if ($btntype == 'approve')
			{
				$dmodel->vd_status	 = 1;
				$dmodel->scenario	 = 'approve';
				$action				 = "approved";
			}
			else if ($btntype == 'problem')
			{
				$dmodel->vd_status	 = 2;
				$dmodel->scenario	 = 'reject';
				$action				 = "disapproved";
			}
			else
			{
				throw new CHttpException(400, 'Invalid data');
			}
			$vndChange = 0;

			$fileType = '';
			switch ($dmodel->vd_type)
			{
				case 1:
					$event_id	 = ($btntype == 'approve') ? VendorsLog::VENDOR_AGREEMENT_APPROVE : VendorsLog::VENDOR_AGREEMENT_REJECT;
					$fileType	 = "#agreement";
					if ($vnd['vnd_agreement_date'] != '' && $vndModel->vnd_agreement_date != DateTimeFormat::DatePickerToDate($vnd['vnd_agreement_date']))
					{
						$vndChange++;
						$fileType						 = '#Agreement';
						$vndModel->vnd_agreement_date	 = DateTimeFormat::DatePickerToDate($vnd['vnd_agreement_date']);
					}
					break;
				case 2:
					if ($dmodel->vd_sub_type == 1)
					{
						$event_id	 = ($btntype == 'approve') ? VendorsLog::VENDOR_VOTERID_APPROVE : VendorsLog::VENDOR_VOTERID_REJECT;
						$fileType	 = "#Voter(Front)";
					}
					if ($dmodel->vd_sub_type == 2)
					{
						$event_id	 = ($btntype == 'approve') ? VendorsLog::VENDOR_VOTERID_BACK_APPROVE : VendorsLog::VENDOR_VOTERID_BACK_REJECT;
						$fileType	 = "#Voter(Back)";
					}
					if ($vnd['vnd_voter_no'] != '' && ($vndModel->vnd_voter_no != $vnd['vnd_voter_no']))
					{
						$vndChange++;
						$vndModel->vnd_voter_no = $vnd['vnd_voter_no'];
					}
					break;
				case 3:
					if ($dmodel->vd_sub_type == 1)
					{
						$event_id	 = ($btntype == 'approve') ? VendorsLog::VENDOR_AADHAAR_APPROVE : VendorsLog::VENDOR_AADHAAR_REJECT;
						$fileType	 = "#Aadhaar(Front)";
					}
					if ($dmodel->vd_sub_type == 2)
					{
						$event_id	 = ($btntype == 'approve') ? VendorsLog::VENDOR_AADHAAR_BACK_APPROVE : VendorsLog::VENDOR_AADHAAR_BACK_REJECT;
						$fileType	 = "#Aadhaar(Back)";
					}
					if ($vnd['vnd_aadhaar_no'] != '' && ($vndModel->vnd_aadhaar_no != $vnd['vnd_aadhaar_no']))
					{
						$vndChange++;
						$vndModel->vnd_aadhaar_no = $vnd['vnd_aadhaar_no'];
					}
					break;
				case 4:

					if ($dmodel->vd_sub_type == 1)
					{
						$event_id	 = ($btntype == 'approve') ? VendorsLog::VENDOR_PAN_APPROVE : VendorsLog::VENDOR_PAN_REJECT;
						$fileType	 = "#PAN(Front)";
					}
					if ($dmodel->vd_sub_type == 2)
					{
						$event_id	 = ($btntype == 'approve') ? VendorsLog::VENDOR_PAN_BACK_APPROVE : VendorsLog::VENDOR_PAN_BACK_REJECT;
						$fileType	 = "#PAN(Back)";
					}

					if ($vnd['vnd_pan_no'] != '' && ($vndModel->vnd_pan_no != $vnd['vnd_pan_no']))
					{
						$vndChange++;
						$vndModel->vnd_pan_no = $vnd['vnd_pan_no'];
					}
					break;
				case 5:

					if ($vnd['vnd_license_no'] != '' && ($vndModel->vnd_license_no != $vnd['vnd_license_no']))
					{
						$vndChange++;
						$vndModel->vnd_license_no = $vnd['vnd_license_no'];
					}
					if ($dmodel->vd_sub_type == 1)
					{
						$event_id	 = ($btntype == 'approve') ? VendorsLog::VENDOR_LICENSE_APPROVE : VendorsLog::VENDOR_LICENSE_REJECT;
						$fileType1[] = "#Licence(Front)";
					}
					if ($dmodel->vd_sub_type == 2)
					{
						$event_id	 = ($btntype == 'approve') ? VendorsLog::VENDOR_LICENSE_BACK_APPROVE : VendorsLog::VENDOR_LICENSE_BACK_REJECT;
						$fileType1[] = "#Licence(Back)";
					}
					if ($vnd['vnd_license_exp_date'] != '' && $vndModel->vnd_license_exp_date != DateTimeFormat::DatePickerToDate($vnd['vnd_license_exp_date']))
					{
						$vndChange++;
						$fileType1[]					 = '#LicenceExpiryDate';
						$vndModel->vnd_license_exp_date	 = DateTimeFormat::DatePickerToDate($vnd['vnd_license_exp_date']);
					}
					if (sizeof($fileType1) > 0)
					{
						$fileType = implode(' and ', $fileType1);
					}
					break;
				case 6:
					$event_id	 = ($btntype == 'approve') ? VendorsLog::VENDOR_MEMORANDUM_APPROVE : VendorsLog::VENDOR_MEMORANDUM_REJECT;
					$fileType	 = "#Memorandum";
					break;
			}

			if ($vndChange > 0)
			{
				if ($vndModel->save())
				{
					$newVndData			 = $vndModel->attributes;
					$descLog			 = "Modified $fileType of the vendor on $action";
					$getOldDifferenceVnd = array_diff_assoc($oldVndData, $newVndData);
					$getNewDifferenceVnd = array_diff_assoc($newVndData, $oldVndData);
					$change				 = $vndModel->getModificationMSG($getOldDifferenceVnd, false);
					$changeNew			 = $vndModel->getModificationMSG($getNewDifferenceVnd, false);
					if ($change != '')
					{
						$changesForVndLog	 = "<br> Old Values: " . $change;
						$descLog			 .= $changesForVndLog;
					}
					else if ($changeNew != '')
					{
						$changesForVndLog	 = "<br> New Values: " . $changeNew;
						$descLog			 .= $changesForVndLog;
					}

					VendorsLog::model()->createLog($vndModel->vnd_id, $descLog, $userInfo, $event_id, false, false);
					$success = true;
				}
				else
				{
					$success = false;
				}
			}
			$remarks				 = trim($vdDocs['vd_remarks']);
			$newDocData				 = $dmodel->attributes;
			$dmodel->vd_remarks		 = $remarks;
			$dmodel->vd_approved_at	 = new CDbExpression('NOW()');
			$dmodel->vd_approved_by	 = $user_id;
			$result1				 = CActiveForm::validate($dmodel);
//$return = ['success' => false];
			$success				 = false;
			if ($result1 == '[]')
			{
				$transaction = Yii::app()->db->beginTransaction();
				try
				{
					$success	 = $dmodel->save();
					$remarkAdded = ($remarks != '') ? "($remarks)" : '';
					$vhc_id		 = $dmodel->vd_vnd_id;

					$desc				 = "The document for $fileType of the vendor is $action $remarkAdded";
					$getOldDifferenceDoc = array_diff_assoc($oldDocData, $newDocData);
					$changes			 = $vndModel->getModificationMSG($getOldDifferenceDoc, false);
					if ($changes != '')
					{
						$changesForDocLog	 = "<br> Old Values: " . $changes;
						$desc				 .= $changesForDocLog;
					}

					VendorsLog::model()->createLog($vhc_id, $desc, $userInfo, $event_id, false, false);
					$transaction->commit();
					if (Yii::app()->request->isAjaxRequest)
					{
						$data = ['success' => true];
						echo json_encode($data);
						Yii::app()->end();
					}
				}
				catch (Exception $e)
				{
					$dmodel->addError("bkg_id", $e->getMessage());
					$transaction->rollback();
				}

				$success = true;
			}
			else
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					$result = [];
					foreach ($model->getErrors() as $attribute => $errors)
					{
						$result[CHtml::activeId($model, $attribute)] = $errors;
					}
					$data = ['success' => $success, 'errors' => $result];
					echo json_encode($data);
					Yii::app()->end();
				}
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('picshow', ['dmodel' => $dmodel], false, $outputJs);
// exit;
//$this->render('picshow', ['dmodel' => $dmodel], false, $outputJs);
	}

	public function actionUpdateOutstanding()
	{
		VendorStats::model()->updateVendorOutstandingCron();
	}

	public function actionMerge()
	{
		$pagetitle		 = "Merger Vendor";
		$this->pageTitle = $pagetitle;
		$cttid			 = Yii::app()->request->getParam('ctt_id');
		$model			 = new Contact();
		$Vendor			 = Vendors::model()->findByVendorContactID($cttid);
		$active			 = 11;
		if (isset($_REQUEST['Contact']))
		{
			$active					 = 1;
			$arr					 = array_filter(Yii::app()->request->getParam('Contact'));
			$model->attributes		 = $arr;
			$model->phone_no		 = $arr['phone_no'];
			$model->name			 = $arr['name'];
			$model->email_address	 = $arr['email_address'];
		}
		$dataProvider = Vendors::model()->getRelatedContact($cttid, $arr, $active);
		$this->renderPartial('merge', array('model' => $model, 'dataProvider' => $dataProvider, 'cttid' => $cttid, 'vendorId' => $Vendor->vnd_id, 'active' => $active), false, true);
	}

	public function actionMergeVendors()
	{
		$this->pageTitle = "Merger Vendor";
		$vndid			 = Yii::app()->request->getParam('agtid');
		$mgrvndId		 = Yii::app()->request->getParam('mgrvnd_id');
		$vndExcludedCity = Yii::app()->request->getParam('vnp_excluded_cities');
		$oldData		 = false;
		$type			 = '';
		$type			 = Yii::app()->request->getParam('type');
		if ($mgrvndId == NULL)
		{
			Yii::app()->user->setFlash('notice', "Please provide merged vendor id");
			$this->redirect(array('vendor/list'));
			exit();
		}

		$duplicateIds = explode(",", $mgrvndId);
		if (in_array($vndid, $duplicateIds))
		{
			Yii::app()->user->setFlash('notice', "You cannot merged with same vendor id");
			$this->redirect(array('vendor/list'));
			exit();
		}
		try
		{
			$model				 = Vendors::model()->findByPk($vndid);
			$modelVendPref		 = VendorPref::model()->find('vnp_vnd_id=:id', ['id' => $vndid]);
			$modelVendStats		 = VendorStats::model()->find('vrs_vnd_id=:id', ['id' => $vndid]);
			//$modelVendDevice	 = VendorDevice::model()->find('vdc_vnd_id=:id', ['id' => $vndid]);
			$modelVendorMerge	 = Vendors::model()->findAllByAttributes(array("vnd_id" => $duplicateIds));
			foreach ($modelVendorMerge as $models)
			{
				$rowsVendorMerge[] = array_filter($models->attributes);
			}
			$modelVendPrefMerge = VendorPref::model()->findAllByAttributes(array("vnp_vnd_id" => $duplicateIds));
			foreach ($modelVendPrefMerge as $models)
			{
				$rowsVendPrefMerge[] = array_filter($models->attributes);
			}
			$modelVendStatsMerge = VendorStats::model()->findAllByAttributes(array("vrs_vnd_id" => $duplicateIds));
			foreach ($modelVendStatsMerge as $models)
			{
				$rowsVendStatsMerge[] = array_filter($models->attributes);
			}
			$modelVendArgMerge = VendorAgreement::model()->findAllByAttributes(array("vag_vnd_id" => $duplicateIds));
			foreach ($modelVendArgMerge as $models)
			{
				$rowsVendArgMerge[] = array_filter($models->attributes);
			}
			$mgrcttid = "";
			for ($i = 0; $i < count($modelVendorMerge); $i++)
			{
				if ($i == 0)
				{
					$mgrcttid .= $modelVendorMerge[$i]['attributes']['vnd_contact_id'];
				}
				else
				{
					$mgrcttid .= "," . $modelVendorMerge[$i]['attributes']['vnd_contact_id'];
				}
			}
			if ($mgrcttid == "")
			{
				Yii::app()->user->setFlash('notice', "Due to some problem with your contact.Vendor merge cannot be completed");
				$this->redirect(array('vendor/list'));
				exit();
			}
			$modelContactMerge			 = Contact::model()->getAllContactByIds($mgrcttid);
			Logger::create("Request : " . CJSON::encode($modelContactMerge), CLogger::LEVEL_INFO);
			$oldData					 = $model->attributes;
			$oldDataStats				 = $modelVendStats->attributes;
			$oldDataPref				 = $modelVendPref->attributes;
			$model->scenario			 = 'update';
			//$model->scenario			 = 'contactupdate';
			$modelVendStats->scenario	 = 'update';
			$ftype						 = 'Modify';
			if ($model->vndContact->ctt_user_type == 1)
			{
				$model->vnd_contact_name = $model->vndContact->ctt_first_name . ' ' . $model->vndContact->ctt_last_name;
			}
			else
			{
				$model->vnd_contact_name = $model->vndContact->ctt_business_name;
			}
			if (isset($_REQUEST['Vendors']) || isset($_REQUEST['VendorStats']) || isset($_REQUEST['VendorPref']) || isset($_REQUEST['vnp_accepted_zone']))
			{
				Logger::create("test Request data : ", CLogger::LEVEL_INFO);
				$agreement_file		 = $_FILES['Vendors']['name']['vnd_agreement_file_link'];
				$agreement_file_tmp	 = $_FILES['Vendors']['type']['vnd_agreement_file_link'];
				$arr				 = Yii::app()->request->getParam('Vendors');

				$arrVendPref									 = Yii::app()->request->getParam('VendorPref');
				$arrVendStats									 = Yii::app()->request->getParam('VendorStats');
				$accepted_zone									 = (!empty($_REQUEST['vnp_accepted_zone'])) ? implode($_REQUEST['vnp_accepted_zone'], ',') : '';
				$arrVendPref['vnp_accepted_zone']				 = $accepted_zone;
				$model->vendorPrefs->attributes					 = $arrVendPref;
				$model->vendorStats->attributes					 = $arrVendStats;
				$model->vendorPrefs->vnp_excluded_cities		 = $vndExcludedCity;
				$model->vendorStats->vrs_security_receive_date	 = ($arrVendStats['vrs_security_receive_date1'] == '') ? null : DateTimeFormat::DatePickerToDate($arrVendStats['vrs_security_receive_date1']);

				$success = $model->saveMergeData($oldData, $oldDataStats, $oldDataPref, $arr, $agreement_file, $agreement_file_tmp, $type);
				if ($success == true)
				{
					VendorsLog::model()->createLog($model->vnd_id, "Vendor	Merge :  $mgrvndId  is merged with $model->vnd_id", UserInfo::getInstance(), VendorsLog::VENDOR_MERGE, false, false);
					Contact::model()->updateContactDetails($mgrcttid);
					Vendors::model()->updateVendorMerge($mgrvndId, $model->vnd_id);
					VendorStats::transferSecurityAmount($duplicateIds, $model->vnd_id);
					AccountTransactions::transferVendorsBalance($duplicateIds, $vndid);
					Yii::app()->user->setFlash('success', "Vendor merged successfully");
					$this->redirect(array('vendor/list'));
				}
				else
				{
					if (Yii::app()->request->isAjaxRequest)
					{
						Yii::app()->end();
					}
				}
			}
		}
		catch (Exception $ex)
		{
			Logger::exception($ex);
			$model	 = new Vendors();
			$message = $ex->getMessage();
		}
		//$outputJs	 = Yii::app()->request->isAjaxRequest;
		//$method		 = "render" . ($outputJs ? "Partial" : "");
		Logger::create("Request1 : " . CJSON::encode($rowsVendorMerge), CLogger::LEVEL_INFO);
		Logger::create("Request2 : " . CJSON::encode($rowsVendPrefMerge), CLogger::LEVEL_INFO);
		Logger::create("Request3 : " . CJSON::encode($rowsVendStatsMerge), CLogger::LEVEL_INFO);
		Logger::create("Request5 : " . CJSON::encode($rowsVendArgMerge), CLogger::LEVEL_INFO);
		$this->renderAuto('addmerge', array(
			'model'					 => $model,
			'modelVendPref'			 => $model->vendorPrefs,
			'modelVendStats'		 => $model->vendorStats,
			'modelContactMerge'		 => $modelContactMerge,
			'modelVendorMerge'		 => $modelVendorMerge,
			'modelVendPrefMerge'	 => $modelVendPrefMerge,
			'modelVendStatsMerge'	 => $modelVendStatsMerge,
			'modelVendArgMerge'		 => $modelVendArgMerge,
			'rowsVendorMerge'		 => CJSON::encode($rowsVendorMerge),
			'rowsVendPrefMerge'		 => CJSON::encode($rowsVendPrefMerge),
			'rowsVendStatsMerge'	 => CJSON::encode($rowsVendStatsMerge),
			'rowsVendArgMerge'		 => CJSON::encode($rowsVendArgMerge),
			'isNew'					 => $ftype,
			'type'					 => $type,
			'message'				 => $message), null);
	}

	public function actionAgreementShowPic()
	{
		$pagetitle		 = "Show Agreement";
		$this->pageTitle = $pagetitle;
		$ctt_id			 = Yii::app()->request->getParam('ctt_id');
		$vnd_id			 = Yii::app()->request->getParam('vnd_id');
		$model			 = Contact::model()->findByPk($ctt_id);

		$docModel	 = VendorAgreement::model()->findByVndId($vnd_id);
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('agreementshowpic', ['model' => $model, 'docModel' => $docModel], false, $outputJs);
	}

	public function actionAgreementShowdoc()
	{
		$pagetitle		 = "Show Agreement";
		$this->pageTitle = $pagetitle;
		$ctt_id			 = Yii::app()->request->getParam('ctt_id');
		$vnd_id			 = Yii::app()->request->getParam('vnd_id');
		$model			 = Contact::model()->findByPk($ctt_id);

		$docModel	 = VendorAgreement::model()->findByVndId($vnd_id);
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('agreementpicshow', ['model' => $model, 'docModel' => $docModel], false, $outputJs);
	}

	public function actionAgreementApprovedoc()
	{
		$pagetitle		 = "Approve Document";
		$this->pageTitle = $pagetitle;
		$btntype		 = Yii::app()->request->getParam('btntype');
		$vnd_id			 = Yii::app()->request->getParam('vnd_id');
		$vendorAgreement = Yii::app()->request->getParam('VendorAgreement');
		$userInfo		 = UserInfo::getInstance();
		$transaction	 = Yii::app()->db->beginTransaction();
		try
		{
			$event_id	 = ($btntype == 'approve') ? VendorsLog::VENDOR_AGREEMENT_APPROVE : VendorsLog::VENDOR_AGREEMENT_REJECT;
			$fileType	 = "#agreement";
			if ($btntype == 'approve')
			{
				$action = "approved";
			}
			else if ($btntype == 'problem')
			{
				$action = "disapproved";
			}
			$descLog				 = "Modified $fileType of the vendor on $action";
			VendorsLog::model()->createLog($vendorAgreement['vag_vnd_id'], $descLog, $userInfo, $event_id, false, false);
			$modelvendorAgreement	 = VendorAgreement::model()->findByPk($vendorAgreement['vag_id']);
			if (empty($vendorAgreement['vag_soft_date']))
			{
				$vendorAgreement['vag_soft_date'] = $vendorAgreement['vag_soft_date'];
			}
			else
			{
				$cls_date							 = DateTime::createFromFormat('d/m/Y', $vendorAgreement['vag_soft_date']);
				$vendorAgreement['vag_soft_date']	 = $cls_date->format('Y-m-d');
			}
			if ($btntype == 'approve')
			{
				$modelvendorAgreement->vag_soft_flag	 = 1;
				$modelvendorAgreement->vag_approved		 = 1;
				$modelvendorAgreement->vag_approved_by	 = $userInfo->userId;
				$modelvendorAgreement->vag_approved_at	 = date("Y-m-d H:i:s");
				$modelvendorAgreement->vag_remarks		 = $vendorAgreement['vag_remarks'];
				$modelvendorAgreement->vag_soft_date	 = $vendorAgreement['vag_soft_date'];
				$status									 = true;
				$message								 = "Agreement approved";
			}
			else
			{
				$modelvendorAgreement->vag_soft_flag	 = 2;
				$modelvendorAgreement->vag_approved		 = 3;
				$modelvendorAgreement->vag_approved_by	 = $userInfo->userId;
				$modelvendorAgreement->vag_approved_at	 = date("Y-m-d H:i:s");
				$modelvendorAgreement->vag_remarks		 = $vendorAgreement['vag_remarks'];
				$modelvendorAgreement->vag_soft_date	 = $vendorAgreement['vag_soft_date'];
				$status									 = true;
				if ($modelvendorAgreement->vag_digital_flag == 1)
				{
					$modelvendorAgreement->vag_digital_flag = 0;
				}
				$message = "Agreement rejected";
			}
			$modelvendorAgreement->save();
			DBUtil::commitTransaction($transaction);
			echo CJSON::encode(array('status' => $status, 'message' => $message));
		}
		catch (Exception $ex)
		{
			echo CJSON::encode(array('status' => $status, 'message' => $ex));
			DBUtil::rollbackTransaction($transaction);
		}
		Yii::app()->end();
	}

	public function actionLouList()
	{
		$pagetitle		 = "Lou List";
		$this->pageTitle = $pagetitle;
		$model			 = new VendorVehicle();
		$dataProvider	 = NULL;
		$request		 = Yii::app()->request;
		if ($model)
		{
			$model->attributes				 = $request->getParam('VendorVehicle');
			$approve						 = $request->getParam('VendorVehicle')['louStatusType'];
			$model->search					 = $request->getParam('VendorVehicle')['search'];
			$model->vvhc_lou_approve_date1	 = $request->getParam('VendorVehicle')['vvhc_lou_approve_date1'];
			$model->vvhc_lou_approve_date2	 = $request->getParam('VendorVehicle')['vvhc_lou_approve_date2'];
			$model->searchVehicleNumber		 = $request->getParam('VendorVehicle')['searchVehicleNumber'];
			$model->vvhc_lou_approved		 = $approve;
			$date1							 = $model->vvhc_lou_approve_date1;
			$date2							 = $model->vvhc_lou_approve_date2;
			$statusArr						 = ($model->vvhc_lou_approved);
			foreach ($statusArr as $key => $value)
			{
				$arrValues[] = $value;
				$louStatus	 = implode(', ', $arrValues);
			}
			$dataProvider							 = VendorVehicle::model()->getLouList($model->search, $date1, $date2, $louStatus, $model->searchVehicleNumber);
			$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
			$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		}
		$outputJs	 = $request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('loulist', array('model' => $model, 'dataProvider' => $dataProvider), null, $outputJs);
	}

	public function actionAgreementApprovalList()
	{
		$pagetitle		 = "Agreement Approval List";
		$this->pageTitle = $pagetitle;
		$model			 = new VendorAgreement();
		$dataProvider	 = NULL;
		$request		 = Yii::app()->request;
		$arr			 = [];
		if ($request->getParam('VendorAgreement'))
		{
			$arr			 = $request->getParam('VendorAgreement');
			$searchVndName	 = $arr['vag_vnd_id'];
		}
		$data									 = $model->getAgreementList($searchVndName);
		$dataProvider							 = $data[0];
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$outputJs								 = $request->isAjaxRequest;
		$method									 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('agreementapprovallist', array('model' => $model, 'dataProvider' => $dataProvider), null, $outputJs);
	}

	public function actionViewLouDetails()
	{
		$success			 = false;
		$message			 = "";
		$model				 = new VendorVehicle();
		$pageTiltle			 = "View Lou Details";
		$this->pageTitle	 = $pageTitle;
		$vvhc_id			 = Yii::app()->request->getParam('vvhc_id');
		$model				 = VendorVehicle::model()->findByPk($vvhc_id);
		$vvhc_lou_approved	 = Yii::app()->request->getParam('vvhc_lou_approved');
		if ($vvhc_lou_approved != NULL)
		{
			$userInfo			 = UserInfo::getInstance();
			$userInfo->userId	 = UserInfo::getUserId();

			if ($model->vvhc_owner_license_id != '')
			{
				$documentModel = Document::model()->findByPk($model->vvhc_owner_license_id);
			}
			else
			{
				$documentModel = Document::model()->findByPk($model->vvhc_owner_pan_id);
			}
			if ($vvhc_lou_approved == 1)
			{
				$docStatus = Document::model()->isLouApproved($documentModel, $vvhc_lou_approved);
			}
			else if ($vvhc_lou_approved == 2)
			{
				$status = VendorVehicle::updateLouStatusByVndVhcId($model->vvhc_id, $vvhc_lou_approved, $userInfo->userId, $model->vvhc_vnd_id, $model->vvhc_vhc_id);
			}
			if ($docStatus)
			{
				$status = VendorVehicle::updateLouStatusByVndVhcId($model->vvhc_id, $vvhc_lou_approved, $userInfo->userId, $model->vvhc_vnd_id, $model->vvhc_vhc_id);
			}
			else
			{
				$message = "You dont have DL or Pan.";
				$data	 = ['success' => false, 'message' => $message];
				if (Yii::app()->request->isAjaxRequest)
				{
					echo $data;
					Yii::app()->end();
				}
			}
			if (!$status)
			{
				$message = "Lou Status - not Updated successfully";
				$data	 = ['success' => false, 'message' => $message];
				if (Yii::app()->request->isAjaxRequest)
				{
					echo $data;
					Yii::app()->end();
				}
			}
			else
			{
				if ($vvhc_lou_approved == 1)
				{
					$message = "Lou Status - Approved successfully";
				}
				else if ($vvhc_lou_approved == 0)
				{
					$message = "Lou Status - Pending successfully";
				}
				else
				{
					$message = "Lou Status - Rejected successfully";
				}
				$success = true;
				$data	 = ['success' => true, 'message' => $message];
				if (Yii::app()->request->isAjaxRequest)
				{
					echo $data;
					Yii::app()->end();
				}
				$this->redirect(array('loulist'));
			}
			VendorsLog::model()->createLog($model->vvhc_vnd_id, "Vendor Lou status changed from vendors having $model->vvhc_vnd_id", $userInfo, VendorsLog::VENDOR_SOCIAL_UNLINK, false, false);
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('viewloudetails', array('model'		 => $model,
			'message'	 => $message, 'success'	 => $success), null, $outputJs);
	}

	public function actionSociallist()
	{
		$pagetitle		 = "Social Link Listing";
		$this->pageTitle = $pagetitle;
		$model			 = new Users();
		$dataProvider	 = NULL;
		$request		 = Yii::app()->request;
		if ($request->getParam('Users'))
		{
			$model->search	 = $request->getParam('Users')['search'];
			$model->email	 = $request->getParam('Users')['email'];

			if ($model->search != NULL && $model->email != NULL)
			{
				$modelUser = Users::model()->getUserIdBySocialEmail($model->email);
				if ($modelUser != NULL)
				{
					$dataProvider = $model->getSocialList($modelUser);
				}
			}
			else if ($model->search != NULL)
			{
				$dataProvider = $model->getSocialList();
			}
			else if ($model->email != NULL)
			{
				$modelUser = Users::model()->getUserIdBySocialEmail($model->email);
				if ($modelUser != NULL)
				{
					$dataProvider = $model->getSocialList($modelUser);
				}
			}
			else
			{
				$dataProvider = $model->getSocialList();
			}
		}
		else
		{
			$dataProvider = $model->getSocialList();
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('socialllinklist', array('model' => $model, 'dataProvider' => $dataProvider), null, $outputJs);
	}

	public function actionUnlinkSocialAccount()
	{
		$from			 = Yii::app()->request->getParam('from');
		$model			 = new Vendors();
		$pagetitle		 = "Social Link Listing";
		$this->pageTitle = $pagetitle;
		$vnd_id			 = Yii::app()->request->getParam('vnd_id');
		$type			 = Yii::app()->request->getParam('type');
		$contactId		 = ContactProfile::getByVendorId($vnd_id);
		if ($contactId != NULL)
		{
			$userId = ContactProfile::getUserId($contactId);

			$from		 = Yii::app()->request->getParam('from');
			$email		 = Yii::app()->request->getParam('email');
			$profile	 = Yii::app()->request->getParam('profile');
			$identifier	 = Yii::app()->request->getParam('identifier');

			$res = ContactEmail::model()->unlink($identifier, $contactId);

			if ($userId != NULL)
			{
				$vendorPref = VendorPref::model()->updateVendorPrefByVendorId($vnd_id);

				//$unlinkvendorUser  = Vendors::unlinkUser($vnd_id,$userId);
				//$unlinkContactUser = ContactProfile::unlinkUser($contactId);

				$impAuth	 = SocialAuth::unlink($userId, $profile, $identifier);
				Users::model()->logoutByUserId($userId);
				$userInfo	 = UserInfo::getInstance();
				VendorsLog::model()->createLog($vnd_id, "Vendor social account removed from vendors having $vnd_id", $userInfo, VendorsLog::VENDOR_SOCIAL_UNLINK, false, false);
			}
		}
		//Yii::app()->user->setFlash('success', "Social account unlink successfully");
		Yii::app()->user->setFlash('error', "Cannot unlink social account");
		if ($type == '1')
		{
			echo json_encode(['success' => false, 'message' => "Social link unlinked successfully "]);
		}
		else
		{
			if ($from == "users")
			{
				$this->redirect(array('user/sociallist'));
			}
			$this->redirect(array('vendor/sociallist'));
		}
	}

	public function actionDuplicateVendor()
	{
		$pagetitle		 = "Duplicate Vendors";
		$this->pageTitle = $pagetitle;
		$model			 = new Contact();
		$cttid			 = NULL;
		$vnd_id			 = "";
		$type			 = "vendors";
		if (isset($_REQUEST['Contact']))
		{
			$arr					 = array_filter(Yii::app()->request->getParam('Contact'));
			$model->attributes		 = $arr;
			$model->ctt_id			 = $arr['ctt_id'];
			$model->ctt_aadhaar_no	 = $arr['ctt_aadhaar_no'];
			$model->phone_no		 = $arr['phone_no'];
			$model->email_address	 = $arr['email_address'];
			$model->ctt_pan_no		 = $arr['ctt_pan_no'];
			$model->ctt_voter_no	 = $arr['ctt_voter_no'];
			$model->ctt_license_no	 = $arr['ctt_license_no'];
		}
//		$dataProvider	 = $model->getDuplicateContactV1($arr, $cttid, $type, $vnd_id);
		$dataProvider	 = $model->getDuplicateContact($arr, $cttid, $type, $vnd_id);
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('duplicatevendor', array('model' => $model, 'dataProvider' => $dataProvider), null, $outputJs);
	}

	public function actionMergeDuplicateVendor()
	{
		$pagetitle			 = "Merge Vendors";
		$this->pageTitle	 = $pagetitle;
		$cttid				 = Yii::app()->request->getParam('ctt_id');
		$vnd_id				 = Yii::app()->request->getParam('vnd_id');
		$phone_no			 = Yii::app()->request->getParam('phone_no');
		$ctt_aadhaar_no		 = Yii::app()->request->getParam('ctt_aadhaar_no');
		$ctt_pan_no			 = Yii::app()->request->getParam('ctt_pan_no');
		$ctt_voter_no		 = Yii::app()->request->getParam('ctt_voter_no');
		$ctt_license_no		 = Yii::app()->request->getParam('ctt_license_no');
		$eml_email_address	 = Yii::app()->request->getParam('email_address');
		$model				 = Contact::model()->findByPk($cttid);
		$emailModel			 = ContactEmail::model()->findByContactID($cttid);
		$phoneModel			 = ContactPhone::model()->findByContactID($cttid);
		$arr				 = [];
		$type				 = "vendors";
		if (isset($_REQUEST['Contact']))
		{
			$arr					 = array_filter(Yii::app()->request->getParam('Contact'));
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
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('duplicatevendor', array('model' => $model, 'dataProvider' => $dataProvider, 'cttid' => $cttid, 'vnd_id' => $vnd_id), null, $outputJs);
	}

//	public function updateAccountDetails($mgrArr, $vndid, $rowsVendStatsMerge)
//	{
//		if (count($mgrArr > 0))
//		{
//
//			for ($i = 0; $i < count($mgrArr); $i++)
//			{
//				$vendorAmount	 = AccountTransDetails::model()->calAmountByVendorId($mgrArr[$i], '', '');
//				$security_amount = $vendorAmount['vnd_security_amount'] != NULL ? $vendorAmount['vnd_security_amount'] : 0;
//				$operatorId		 = $mgrArr[$i];
//				$refType		 = NULL;
//				$ledgerType		 = Accounting::LI_OPERATOR;
//				$accType		 = Accounting::AT_OPERATOR;
////				if ($security_amount > 0)
////				{
////					$bankRefId								 = NULL;
////					$accTransModel							 = new AccountTransactions();
////					$accTransModel->act_amount				 = $security_amount;
////					$accTransModel->act_date				 = date('Y-m-d H:i:s');
////					$accTransModel->act_type				 = Accounting::AT_OPERATOR;
////					$accTransModel->act_ref_id				 = $operatorId;
////					$accTransModel->act_remarks				 = "SECURITY_DEPOSIT - Due to merging of vendor";
////					$accTransModel							 = $accTransModel->AddReceiptForMerge(Accounting::LI_SECURITY_DEPOSIT, $ledgerType, $bankRefId, $operatorId, $accTransModel->act_remarks, $accType, UserInfo::getInstance());
////					$modelStats								 = VendorStats::model()->findByPk($rowsVendStatsMerge[$i]['vrs_id']);
////					$modelStats->vrs_security_amount		 = 0;
////					$modelStats->vrs_security_receive_date	 = NULL;
////					$modelStats->save();
////					VendorsLog::model()->createLog($rowsVendStatsMerge[$i]['vrs_vnd_id'], "Vendor security amount set to zero due to merged", UserInfo::getInstance(), VendorsLog::VENDOR_MERGE, false, false);
////				}
//				$vendorAmount	 = AccountTransDetails::model()->calAmountByVendorId($mgrArr[$i], '', '');
//				$amount			 = $vendorAmount['vendor_amount'] != NULL ? $vendorAmount['vendor_amount'] : 0;
//				if ($amount != 0)
//				{
//					$drRefId					 = $mgrArr[$i] ;
//					$drRemarks					 = "Outstanding balance transfer from  (" . $vndid  . ")";
//					$crRemarks					 = "Outstanding balance transfer to  operator (" . $mgrArr[$i] .")";
//					$crRefId					 = $vndid;
//					$drLedgerId					 = Accounting::LI_OPERATOR;
//					$drAcctType					 = Accounting::AT_OPERATOR;
//					$crLedgerID					 = Accounting::LI_OPERATOR;
//					$crAccType					 = Accounting::AT_OPERATOR;
//					$accTransModel				 = new AccountTransactions();
//					$accTransModel->act_amount	 = $amount;
//					$accTransModel->act_date	 = new CDbExpression('NOW()');
//					$accTransModel->act_type	 = $crAccType;
//					$accTransModel->act_ref_id	 = $crRefId;
//					$accTransModel->addOutstandingBalance($drLedgerId, $crLedgerID, $drRefId, $crRefId, $drAcctType, $crAccType, $drRemarks, $crRemarks, UserInfo::getInstance());
//				}
//			}
//		}
//	}

	/*
	 * Depricated
	 */
	public function updateAccountDetails($mgrArr, $vndid)
	{
		if (count($mgrArr > 0))
		{

			for ($i = 0; $i < count($mgrArr); $i++)
			{
				$vendorAmount	 = AccountTransDetails::model()->calAmountByVendorId($mgrArr[$i], '', '', '');
				$amount			 = $vendorAmount['vendor_amount'] != NULL ? $vendorAmount['vendor_amount'] : 0;
				$amount1		 = $amount;
				if ($amount != 0)
				{

					$crRefId		 = $vndid;
					$drRefId		 = $mgrArr[$i];
					$crRemarks		 = "Balance transferred as Vendor $mgrArr[$i] is merged with $vndid";
					$drRemarks		 = "Balance transferred as Vendor $vndid is merged with $mgrArr[$i]";
					$accTransModel	 = new AccountTransactions();
					if ($amount < 0)
					{
						$accTransModel->act_amount = -1 * $amount1;
					}
					if ($amount > 0)
					{
						$accTransModel->act_amount = $amount1;
					}
					$drLedgerId					 = Accounting::LI_OPERATOR;
					$drAcctType					 = Accounting::AT_OPERATOR;
					$crLedgerID					 = Accounting::LI_OPERATOR;
					$crAccType					 = Accounting::AT_OPERATOR;
					$accTransModel->act_amount	 = $amount;
					$accTransModel->act_date	 = new CDbExpression('NOW()');
					$accTransModel->act_type	 = $crAccType;
					$accTransModel->act_ref_id	 = $crRefId;
					$accTransModel->act_remarks	 = $crRemarks;
					$accTransModel->mergeAccountBalance($drLedgerId, $crLedgerID, $drRefId, $crRefId, $drAcctType, $crAccType, $drRemarks, $crRemarks, UserInfo::getInstance(), $amount);
				}
			}
		}
	}

	/**
	 * 
	 * @throws Exception
	 */
	public function actionUnsetOrientation()
	{
		$this->pageTitle	 = "Vendor Orientation";
		$vndId				 = Yii::app()->request->getParam('vnd_id');
		$userInfo			 = UserInfo::getInstance();
		$contactData		 = Contact::model()->getNameById($vndId);
		/* @var $modelPref VendorPref */
		$modelPref			 = VendorPref::model()->resetScope()->find('vnp_vnd_id=:id', ['id' => $vndId]);
		$commentText		 = ($modelPref->vnp_is_orientation > 0) ? 'After this vendor will be approved and ready to take bookings' : '';
		/* @var $logModel VendorsLog */
		$logModel			 = new VendorsLog();
		$logModel->scenario	 = 'updateOrientation';
		if (isset($_POST['VendorsLog']))
		{
			$success	 = false;
			$transaction = DBUtil::beginTransaction();
			try
			{
				$arr					 = Yii::app()->request->getParam('VendorsLog');
				$logModel->vlg_vnd_id	 = $arr['vlg_vnd_id'];
				$logModel->vlg_desc		 = $arr['vlg_desc'];
				$orientation_type		 = $arr['vlg_orientation_type'];
				if ($orientation_type == 1)
				{
					$return	 = VendorPref::model()->unsetOrientationFlag($logModel->vlg_vnd_id, $contactData['ownername'], $userInfo, $logModel->vlg_desc);
					$success = $return['success'];
					$message = $return['message'];
				}
				else if ($orientation_type == 2)
				{
					$modelVendPref						 = VendorPref::model()->find('vnp_vnd_id=:id', ['id' => $logModel->vlg_vnd_id]);
					$modelVendPref->vnp_orientation_type = 2;
					if (!$modelVendPref->save())
					{
						$var = "Failed to save => " . json_encode($modelVendPref->getErrors());
						throw new Exception($var);
					}
					$modelVendPref->save();
					$eventId = VendorsLog::VENDOR_MODIFIED;
					$desc	 = "Vendor [ " . $contactData['ownername'] . " ] not ready to approve : " . $logModel->vlg_desc;
					VendorsLog::model()->createLog($logModel->vlg_vnd_id, $desc, $userInfo, $eventId, false, false);
					$success = true;
				}
				DBUtil::commitTransaction($transaction);
			}
			catch (Exception $ex)
			{
				$message = $ex->getMessage();
				DBUtil::rollbackTransaction($transaction);
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				$data = ['success' => $success, 'message' => $message];
				echo json_encode($data);
				Yii::app()->end();
			}
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('unset_orientation', array('vnd_id'	 => $vndId,
			'logModel'	 => $logModel,
			'comment'	 => $commentText), FALSE, $outputJs);
	}

	public function actionGetlockamount()
	{
		$vendorId					 = Yii::app()->request->getParam('vnd_id');
		$vndInfo					 = Yii::app()->db->createCommand("
		SELECT vnd_cat_type,vnd_name,vnd_active,vnp_is_freeze,vnp_cod_freeze,vrs_vnd_overall_rating FROM vendors
		INNER JOIN vendor_pref ON vnp_vnd_id = vnd_id
		INNER JOIN vendor_stats ON vrs_vnd_id = vnd_id
		WHERE vnd_id = $vendorId")->queryRow();
		echo(json_encode($vndInfo));
		echo "<br><br>";
		$tripAmount					 = AccountTransactions::getTotalVendorAmount($vendorId) | 0;
		echo "Vendor Trip Amount (used unapproved cab/driver after 10/04/19) : $tripAmount";
		$vendorRow					 = AccountTransDetails::model()->calAmountByVendorId($vendorId);
		$vendorBalance				 = -1 * $vendorRow['vendor_amount'];
		echo "<br><br>";
		echo "Vendor Running Balance(-) : $vendorBalance ";
		/*
		 * logic,Get value of Vendors existing rating = VR and round it
		 * A = % of VendorTripAmount of all bookings he has completed in the last n days.  
		 * A = [ ( [ 5 - Round(VR,0) ] 10% ) +5% ] of VendorTripAmount of all bookings he has completed in the last 
		 * [ (Round([5 - VR ],0) 1 ) + 4 ] days.
		 */
		$rating						 = ($vndInfo['vrs_vnd_overall_rating'] > 0) ? round($vndInfo['vrs_vnd_overall_rating']) : 4;
		$percentage					 = (((5 - $rating) * 0.1) + 0.05);
		$days						 = (((5 - $rating) * 1) + 4);
		$getLast5daysVendorAmount	 = AccountTransDetails::getVendorAmountforLastNDays($vendorId, $days, $percentage) | 0;
		$nextDays					 = 3;
		$getNext3daysGozoAmount		 = AccountTransDetails::getNextNdaysGozoAmount($vendorId, $nextDays);
		echo "<br><br>";
		echo "Vendor Rating : " . $rating;
		echo "<br><br>";
		echo "If (rating = 5 , 5% , 4days) , (rating = 4 , 15% , 5days) , (rating = 3 , 25% , 6days) , (rating = 2 , 35% , 7days) , (rating = 1 , 45% , 8days)";
		echo "<br><br>";
		echo ($percentage * 100) . "% of Last " . $days . " days Vendor Amount : $getLast5daysVendorAmount ";
		echo "<br><br>";
		echo "Next 3 days Gozo Amount : $getNext3daysGozoAmount ";
		$maxval						 = max([$tripAmount, $getLast5daysVendorAmount]);
		$totalSum					 = max([($maxval + $getNext3daysGozoAmount), $maxval]);

		echo "<br><br>";
		echo "TotalAmount : $totalSum ";

		$lockbalance			 = max([$totalSum - $vendorRow['vnd_security_amount'], 0]);
		echo "<br><br>";
		echo "Security Amount : " . $vendorRow['vnd_security_amount'];
		echo "<br><br>";
		echo "New Locked Balance (pending Cron update in few hours) : $lockbalance ";
		echo "<br><br>";
		echo "Existing Locked Balance : " . $vendorRow['locked_amount'];
		$Withdrawable_Balance	 = ($vendorRow['vnp_is_freeze'] != 0 || $vendorRow['vnd_active'] != 1) ? 0 : max([$vendorBalance - $lockbalance, 0]);
		echo "<br><br>";
		echo "Withdrawable Balance : $Withdrawable_Balance ";
		echo "<br><br>";
		if ($tripAmount > 0)
		{
			$relVndIds	 = Vendors::getRelatedIds($vendorId);
			echo "Locked Booking Details :";
			$bkgInfoSql	 = "SELECT bkg_booking_id bkgid,drv_approved drvapproved,drv_name,vhc_approved vhcapproved,vhc_number,bcb_vendor_amount tripAmt,bcb_lock_vendor_payment,bcb_vendor_id,bkg_pickup_date  FROM booking_cab 
INNER JOIN booking ON booking.bkg_bcb_id = booking_cab.bcb_id AND booking.bkg_active = 1 AND bkg_status IN(6,7)
LEFT JOIN drivers ON drivers.drv_id = booking_cab.bcb_driver_id AND drivers.drv_active = 1
LEFT JOIN vehicles ON vehicles.vhc_id = booking_cab.bcb_cab_id AND vehicles.vhc_active = 1
WHERE `bcb_lock_vendor_payment` =1 AND (booking.bkg_pickup_date > '2019-04-10 00:00:00') AND bcb_vendor_id IN ({$relVndIds}) ";
			$bkgInfo	 = DBUtil::query($bkgInfoSql, DBUtil::SDB());
			//echo(json_encode($bkgInfo));
			echo "<table><th>Booking ID</th><th>Driver Name</th><th>Driver Status</th><th>Cab Number</th><th>Cab Status</th><th>Trip Amount</th><th>Pickup Date</th>";
			foreach ($bkgInfo as $val)
			{
				echo "<tr>";
				echo "<td>" . $val['bkgid'] . "</td><td>" . $val['drv_name'] . "</td><td align='center'>" . $val['drvapproved'] . "</td><td>" . $val['vhc_number'] . "</td><td align='center'>" . $val['vhcapproved'] . "</td><td style='text-align:right;padding-right:10px'>" . $val['tripAmt'] . "</td><td>" . $val['bkg_pickup_date'] . "</td>";
				echo "<tr>";
			}
			echo "</table>";
		}
		exit();
	}

	public function actionboostDependency()
	{
		$vendorId	 = Yii::app()->request->getParam('vnd_id');
		$res		 = VendorStats::dependencyBoost($vendorId);
		$success	 = false;
		$msg		 = "Unable to boost dependency score";
		if ($res)
		{

			$result = VendorStats::updateDependency($vendorId);
			if ($result)
			{
				$msg	 = "Temporary dependency score added";
				$success = true;
			}
		}
		echo json_encode(['success' => $success, 'message' => $msg]);
	}

	public function actionViewMetrics()
	{
		$vendorId	 = Yii::app()->request->getParam('vnd_id');
		$data		 = VendorStats::fetchMetric($vendorId);

		$this->renderPartial('vendorMatrix', array('data' => $data), false, false);
	}

	public function editVendorDetails($data1, $processFile1, $processFile2, $returnSet)
	{
		$returnSet->setStatus(true);
		$data1				 = CJSON::decode($data1, true);
		$agmt_file1_img_no	 = $agmt_file2_img_no	 = 0;
		$agmt_file1_img_no	 = CJSON::decode($processFile1, true);
		$agmt_file2_img_no	 = CJSON::decode($processFile2, true);
		Filter::createLog("file no1: " . $agmt_file1_img_no, CLogger::LEVEL_TRACE);
		Filter::createLog("file no2: " . $agmt_file2_img_no, CLogger::LEVEL_TRACE);
		$photo				 = $_FILES['photo']['name'];
		$photo_tmp			 = $_FILES['photo']['tmp_name'];
		$agmt1				 = $_FILES['agreement1']['name'];
		$agmt1_tmp			 = $_FILES['agreement1']['tmp_name'];
		$agmt2				 = $_FILES['agreement2']['name'];
		$agmt2_tmp			 = $_FILES['agreement2']['tmp_name'];
		$agmt_file			 = $_FILES['agreement_file']['name'];
		$agmt_file_tmp		 = $_FILES['agreement_file']['tmp_name'];
		$vendorPic			 = $data1['vendorPic'];
		$agmt_req_id		 = $data1['req_id'];
		$total_agmt_img_no	 = $data1['total_img_no'];
		$doc_type			 = $data1['doc_type'];
		$doc_subtype		 = $data1['doc_subtype'];
		$vendorId			 = $data1['data']['vnd_id'];
		$evtList			 = VendorsLog::model()->eventList();
		$userInfo			 = UserInfo::getInstance();
		$userInfo->userType	 = UserInfo::TYPE_ADMIN;
		$model				 = Vendors::model()->findByPk($vendorId);
		$contact_id			 = $model->vnd_contact_id;
		$contactModel		 = Contact::model()->findByPk($contact_id);
		$phone				 = ContactPhone::model()->getContactPhoneById($contact_id);
		$email				 = ContactEmail::getPrimaryEmail($contact_id);
		if ($vendorPic == 0)
		{
			$model->scenario	 = 'update';
			$model->attributes	 = $data1;
			$model->vnd_cat_type = $data1['data']['vnd_cat_type'];
			if (!$model->validate())
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors("Vendor update failed.\n\t\t " . json_encode($errors));
				return ["returnSet" => $returnSet, 'model' => $model, 'vendorId' => $vendorId, 'agmt_file1_img_no' => $agmt_file1_img_no, 'agmt_file2_img_no' => $agmt_file2_img_no];
			}
			$contactModel->scenario			 = 'update';
			$contactModel->ctt_city			 = $data1['data']['vnd_city'];
			$contactModel->ctt_address		 = $data1['data']['vnd_address'];
			$contactModel->ctt_business_name = $data1['data']['vnd_company'];
			$contactModel->ctt_voter_no		 = $data1['data']['vnd_voter_no'];
			$contactModel->ctt_pan_no		 = $data1['data']['vnd_pan_no'];
			$contactModel->ctt_aadhaar_no	 = $data1['data']['vnd_aadhaar_no'];
			$contactModel->ctt_license_no	 = $data1['data']['vnd_license_no'];
			if ($email != $data1['data']['vnd_email'])
			{
				$emailUpdate = ContactEmail::model()->updateEmailByContactId($data1['data']['vnd_email'], $contact_id);
			}
			if ($phone != $data1['data']['vnd_phone'])
			{
				$phoneUpdate = ContactPhone::model()->updatePhoneByContactId($data1['data']['vnd_phone'], $contact_id);
			}
			if (!$contactModel->save())
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors($contactModel->getErrors());
				return ["returnSet" => $returnSet, 'model' => $model, 'vendorId' => $vendorId, 'agmt_file1_img_no' => $agmt_file1_img_no, 'agmt_file2_img_no' => $agmt_file2_img_no];
			}
			if (!$model->save())
			{
				$returnSet->setStatus(false);
				$returnSet->setErrors($model->getErrors());
				return ["returnSet" => $returnSet, 'model' => $model, 'vendorId' => $vendorId, 'agmt_file1_img_no' => $agmt_file1_img_no, 'agmt_file2_img_no' => $agmt_file2_img_no];
			}
			$newData			 = Vendors::model()->getDetailsbyId($vendorId);
			$getOldDifference	 = array_diff_assoc($oldData, $newData);
			$changesForLog		 = " Old Values: " . Vendors::model()->getModificationMSG($getOldDifference);
			$eventId			 = VendorsLog::VENDOR_EDIT;
			$logDesc			 = $evtList[$eventId];
			$desc				 = $logDesc . $changesForLog;
			VendorsLog::model()->createLog($vendorId, $desc, $userInfo, $eventId, false, false);
		}
		else if ($vendorPic == 1)
		{
			if ($agmt1 != '' || $agmt2 != '')
			{
				$userType	 = 6;
				$recordset	 = '';
				$recordset	 = VendorAgmtDocs::model()->updateVendorAgreement($agmt1, $agmt1_tmp, $vendorId, $agmt_file1_img_no, $agmt_req_id, $agmt2, $agmt2_tmp, $agmt_file2_img_no, $total_agmt_img_no, $userType);

				if ($recordset)
				{
					$updateCount = VendorAgmtDocs::model()->updateStatusByVndReqId($vendorId, $agmt_req_id, $total_agmt_img_no);
					if (!$updateCount > 0)
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors("Document images( Agreement ) creation failed.\n\t\t");
						return ["returnSet" => $returnSet, 'model' => $model, 'vendorId' => $vendorId, 'agmt_file1_img_no' => $agmt_file1_img_no, 'agmt_file2_img_no' => $agmt_file2_img_no];
					}
					$modelDigital				 = VendorAgreement::model()->findByVndId($vendorId);
					$modelDigital->vag_soft_flag = 2;
					if (!$modelDigital->save())
					{
						$returnSet->setStatus(false);
						$returnSet->setErrors($modelDigital->getErrors());
						return ["returnSet" => $returnSet, 'model' => $model, 'vendorId' => $vendorId, 'agmt_file1_img_no' => $agmt_file1_img_no, 'agmt_file2_img_no' => $agmt_file2_img_no];
					}
				}
			}
			if ($photo != '')
			{
				$type							 = 'profile';
				$result2						 = Document::model()->saveVendorImage($photo, $photo_tmp, $vendorId, $model->vnd_contact_id, $type);
				$contactModel->scenario			 = 'update';
				$contactModel->ctt_profile_path	 = $result2['path'];
				if (!$contactModel->save())
				{
					$returnSet->setStatus(false);
					$returnSet->setErrors($contactModel->getErrors());
					return ["returnSet" => $returnSet, 'model' => $model, 'vendorId' => $vendorId, 'agmt_file1_img_no' => $agmt_file1_img_no, 'agmt_file2_img_no' => $agmt_file2_img_no];
				}
				$errors	 = [];
				$eventid = VendorsLog::VENDOR_PROFILE_UPLOAD;
				$logDesc = $evtList[$eventid];
				VendorsLog::model()->createLog($vendorId, $logDesc, UserInfo::getInstance(), $eventid, false, false);
			}
			if ($doc_type != '')
			{
				$success = Document::model()->updateVendorDoc($model, $photo, $photo_tmp, $doc_type, $doc_subtype);
				if (!$success->getStatus())
				{
					$errors = $success->getErrors();
					$returnSet->setStatus(false);
					$returnSet->setErrors($errors);
					return ["returnSet" => $returnSet, 'model' => $model, 'vendorId' => $vendorId, 'agmt_file1_img_no' => $agmt_file1_img_no, 'agmt_file2_img_no' => $agmt_file2_img_no];
				}
			}
		}
		Filter::createLog("Return Data =>" . json_encode(["returnSet" => $returnSet, 'model' => $model, 'vendorId' => $vendorId, 'agmt_file1_img_no' => $agmt_file1_img_no, 'agmt_file2_img_no' => $agmt_file2_img_no]), CLogger::LEVEL_TRACE);
		return ["returnSet" => $returnSet, 'model' => $model, 'vendorId' => $vendorId, 'agmt_file1_img_no' => $agmt_file1_img_no, 'agmt_file2_img_no' => $agmt_file2_img_no];
	}

	public function actionDel()
	{
		$vndId = Yii::app()->request->getParam('vndid');

		if (isset($_REQUEST['vnd_delete_reason']))
		{
			$result = Vendors::delVendor($vndId, $_REQUEST['vnd_delete_reason'], $_REQUEST['vnd_delete_other']);

			if (Yii::app()->request->isAjaxRequest)
			{
				echo json_encode($result);
				Yii::app()->end();
			}
		}
		$this->renderPartial('delvendor', array('vndid' => $vndId));
	}

	public function actionReject()
	{
		$vndId = Yii::app()->request->getParam('vndid');

		if (isset($_REQUEST['vnd_delete_reason']))
		{
			$result = Vendors::rejectVendor($vndId, $_REQUEST['vnd_delete_reason'], $_REQUEST['vnd_delete_other']);

			if (Yii::app()->request->isAjaxRequest)
			{
				echo json_encode($result);
				Yii::app()->end();
			}
		}
		$this->renderPartial('rejectvendor', array('vndid' => $vndId));
	}

	public function actionExportTierCount()
	{
		$request				 = Yii::app()->request;
		$vnp_home_zone_export	 = $request->getParam("vnp_home_zone_export", false);
		$zonRegion_export		 = $request->getParam('zonRegion_export', false);

		$vnpModel = new VendorPref('search');

		if ($vnp_home_zone_export !== false || $zonRegion_export !== false)
		{
			$vnpModel->zonRegion	 = $zonRegion_export;
			$vnpModel->vnp_home_zone = $vnp_home_zone_export;
		}

		header('Content-type: text/csv');
		header("Content-Disposition: attachment; filename=\"VehicleTierCountByZone_" . date('Ymd_his') . ".csv\"");
		header("Pragma: no-cache");
		header("Expires: 0");
		$filename	 = "VendorCountTierList_" . date('YmdHi') . ".csv";
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
		$rows	 = $vnpModel->getVehicleTierCountByZone(DBUtil::ReturnType_Query);
		$handle	 = fopen("php://output", 'w');
		fputcsv($handle, ['Zone Name', 'Value(Vendor Count|Car Count)', 'Value+(Vendor Count|Car Count)', 'Plus(Vendor Count|Car Count)', 'Select(Vendor Count|Car Count)']);
		foreach ($rows as $row)
		{
			$rowArray					 = array();
			$rowArray['zoneName']		 = $row['zon_name'];
			$rowArray['valueTier']		 = $row['cntValueVendors'] . '/' . $row['cntValueVehicles'];
			$rowArray['valuePlusTier']	 = $row['cntValuePlusVendors'] . '/' . $row['cntValuePlusVehicles'];
			$rowArray['plusTier']		 = $row['cntPlusVendors'] . '/' . $row['cntPlusVehicles'];
			$rowArray['selecttier']		 = $row['cntSelectVendors'] . '/' . $row['cntSelectVehicles'];

			$row1 = array_values($rowArray);
			fputcsv($handle, $row1);
		}
		fclose($handle);
		exit;
	}

	public function actionVendorDetails()
	{
		$zoneId			 = Yii::app()->request->getParam('id');
		$tierId			 = Yii::app()->request->getParam('tier');
		$qry			 = [];
		$qry['zone_id']	 = trim(Yii::app()->request->getParam('id'));
		$data			 = Vendors::model()->getDetailsByZoneID($zoneId, $tierId);

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->renderPartial('vendorDetails', array('dataProvider' => $data, 'qry' => $qry), false, $outputJs);
	}

	public function actionProfile()
	{

		$vndid	 = Yii::app()->request->getParam('id');
		$vndCode = Yii::app()->request->getParam('code');
		$view	 = Yii::app()->request->getParam('view', 'profile');

		if ($vndCode != '')
		{
			$vnd	 = Vendors::model()->getIdByCode($vndCode);
			$vndid	 = $vnd['vnd_id'];
		}
		//the following line will be redirected to vendor View page using id
		$this->redirect('/aaohome/vendor/view?id=' . $vndid);
		$models	 = Vendors::model()->findByPk($vndid);
		$data	 = Vendors::model()->getViewDetailbyId($vndid);
		$vndCode = Vendors::model()->getCodebyid($vndid);

		$this->pageTitle = "Vendor Profile";
		$data['vndCode'] = $vndCode;
		$qry['vndid']	 = $vndid;

		$dataProvider	 = Vendors::getCollectionReport($qry);
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method($view, array(
			'model'			 => $models,
			'data'			 => $data,
			'dataProvider'	 => $dataProvider,
			'isAjax'		 => $outputJs
				), false, $outputJs);
	}

	public function addContactNew()
	{
		$transaction	 = DBUtil::beginTransaction();
		$returnSet		 = new ReturnSet();
		$returnSet->setStatus(true);
		$requestInstance = Yii::app()->request;
		$reciveData		 = json_decode($requestInstance->rawBody);
		if (empty($reciveData))
		{
			$returnSet->setStatus(false);
			$returnSet->setMessage("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
			goto skipAll;
		}

		$jsonMapper	 = new JsonMapper();
		$stub		 = new Stub\common\Business();
		$obj		 = $jsonMapper->map($reciveData, $stub);

		/** @var Stub\common\Business $obj */
		$contactModel	 = $obj->init();
		$returnSet		 = Vendors::addByContact($contactModel);

		skipAll:
		if ($returnSet->getStatus() == false)
		{
			DBUtil::rollbackTransaction($transaction);
		}
		else
		{
			DBUtil::commitTransaction($transaction);
		}
		return $returnSet;
	}

	public function actionPackagesList()
	{

		$this->pageTitle = "Vendor Packages";
		$model			 = new VendorPackages();
		$cityModel		 = new Cities();
		$request		 = Yii::app()->request;
		if ($request->getParam('VendorPackages'))
		{
			$arr							 = $request->getParam('VendorPackages');
			$model->search					 = $search							 = $arr['search'];
			$model->searchVehicleNumber		 = $searchVhcNo					 = $arr['searchVehicleNumber'];
			$model->vpk_status				 = $searchPackagesStatus			 = $arr['vpk_status'];
			$model->vpk_type				 = $packagesType					 = $arr['vpk_type'];
			$cityModel->cty_id				 = $city							 = Yii::app()->request->getParam('Cities')['cty_id'];
			$model->vpk_created_date1		 = $date1							 = $arr['vpk_created_date1'];
			$model->vpk_created_date2		 = $date2							 = $arr['vpk_created_date2'];
			$model->vpk_sentpackage_date1	 = $packageSentdate1				 = $arr['vpk_sentpackage_date1'];
			$model->vpk_sentpackage_date2	 = $packageSentdate2				 = $arr['vpk_sentpackage_date2'];
		}
		$provider		 = VendorPackages::model()->getList($arr, $city);
		$dataProvider	 = $provider[0];
		$count			 = $provider[1];

		$this->render('packageslist', array('dataProvider' => $dataProvider, 'count' => $count, 'model' => $model, 'cityModel' => $cityModel));
	}

	public function actionSentPackages()
	{
		$success			 = false;
		$id					 = Yii::app()->request->getParam('vpkId');
		$status				 = Yii::app()->request->getParam('status');
		$packagesSentCount	 = Yii::app()->request->getParam('packagesSentCount');
		$trackingNumber		 = Yii::app()->request->getParam('trackingNumber');
		if ($id != '')
		{
			$model = VendorPackages::model()->findByPk($id);
			if (count($model) > 0)
			{
				$model->vpk_sent_date		 = date("Y-m-d H:i:s");
				$model->vpk_received_status	 = $status;
				$model->vpk_sent_count		 = $packagesSentCount;
				$model->vpk_tracking_number	 = $trackingNumber;
				$model->vpk_sent_by			 = UserInfo::getUserId();
				if ($model->update())
				{
					$success = true;
				}
				if ($success == true)
				{
					if ($model->vpk_type == 1)
					{
						$packageType = "Sticker";
					}
					else
					{
						$packageType = "Divider";
					}
					$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BOOST_NOTIFICATION];
					$message	 = "Today we sent a package containing " . $packageType . " to you. Tracking number is " . $trackingNumber . ". Please click this Confirm when received.";
					$title		 = "Packages Send Notification";
					$result		 = AppTokens::model()->notifyVendor($model->vpk_vnd_id, $payLoadData, $message, $title);
				}
			}
		}
		$data = $success;
		if (Yii::app()->request->isAjaxRequest)
		{
			echo $data;
			Yii::app()->end();
		}
		$this->redirect(array('PackagesList'));
	}

	public function actionReceivedPackages()
	{
		$success = false;
		$id		 = Yii::app()->request->getParam('vpkId');
		$status	 = Yii::app()->request->getParam('status');
		if ($id != '')
		{
			$model = VendorPackages::model()->findByPk($id);
			if (count($model) > 0)
			{
				$model->vpk_received_date	 = date("Y-m-d H:i:s");
				$model->vpk_received_status	 = $status;
				$model->update();
				$success					 = true;
			}
		}
		$data = $success;
		if (Yii::app()->request->isAjaxRequest)
		{
			echo $data;
			Yii::app()->end();
		}
		$this->redirect(array('PackagesList'));
	}

	public function actionEditPackages()
	{
		$this->pageTitle = "Vendor Packages Edit";
		$success		 = false;
		$model			 = new VendorPackages();
		$request		 = Yii::app()->request;
		$id				 = Yii::app()->request->getParam('packageId');
		$model			 = VendorPackages::model()->findByPk($id);
		if (isset($_REQUEST['VendorPackages']))
		{
			$newData				 = Yii::app()->request->getParam('VendorPackages');
			$success				 = false;
			$sendDate				 = DateTimeFormat::DatePickerToDate($newData['packagesSentDate']);
			$sentTime				 = DateTime::createFromFormat('h:i A', $newData['packagesSentTime'])->format('H:i:00');
			$sendDateTime			 = $sendDate . ' ' . $sentTime;
			$model->vpk_sent_date	 = $sendDateTime;

			if ($newData['packagesReceivedDate'] != '' && $newData['packagesReceivedTime'] != '')
			{
				$receiveDate				 = DateTimeFormat::DatePickerToDate($newData['packagesReceivedDate']);
				$receiveTime				 = DateTime::createFromFormat('h:i A', $newData['packagesReceivedTime'])->format('H:i:00');
				$receiveDateTime			 = $receiveDate . ' ' . $receiveTime;
				$model->vpk_received_date	 = $receiveDateTime;
			}

			if ($newData['packagesSentDate'] != '' && $newData['packagesReceivedDate'] == '')
			{
				$model->vpk_received_status = 0;
			}
			else if ($newData['packagesSentDate'] != '' && $newData['packagesReceivedDate'] != '')
			{
				$model->vpk_received_status = 1;
			}
			$model->vpk_sent_count			 = $newData['vpk_sent_count'];
			$model->vpk_tracking_number		 = $newData['vpk_tracking_number'];
			$model->vpk_delivered_by_courier = $newData['vpk_delivered_by_courier'];
			$trackingNumber					 = $model->vpk_tracking_number;
			$model->scenario				 = 'updateBoost';
			$result							 = CActiveForm::validate($model);
			if ($result != "[]")
			{
				$return = ['success' => false, 'errors' => CJSON::decode($result)];
			}
			else
			{
				if ($model->update())
				{
					$success = true;
				}
				if ($success == true && $model->vpk_sent_count > 0)
				{
					if ($model->vpk_type == 1)
					{
						$packageType = "Sticker";
					}
					else
					{
						$packageType = "Divider";
					}
					$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BOOST_NOTIFICATION];
					$message	 = "Today we sent a package containing " . $packageType . " to you. Tracking number is " . $trackingNumber . ". Please click this Confirm when received.";
					$title		 = "Packages Send Notification";
					$result		 = AppTokens::model()->notifyVendor($model->vpk_vnd_id, $payLoadData, $message, $title);
				}
			}
			if ($success == true)
			{
				$this->redirect(array('PackagesList'));
			}
			else
			{
				$return = ['success' => false, 'errors' => CJSON::decode($result)];
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('editPackages', array('model' => $model, 'success' => $success), false, $outputJs);
	}

	public function actiondelPackages()
	{
		$id = Yii::app()->request->getParam('vpk_id');
		if ($id != '')
		{
			$model = VendorPackages::model()->findByPk($id);
			if (count($model) == 1)
			{
				$model->vpk_active = 0;
				$model->update();
			}
		}
		$this->redirect(array('PackagesList'));
	}

	public function actionBidlog()
	{
		$vndId									 = Yii::app()->request->getParam('vndid');
		$viewType								 = Yii::app()->request->getParam('view');
		$model									 = new BookingVendorRequest();
		$dataProvider							 = $model->getbidbyVnd($vndId);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
//Partial
		$this->render('bidlog', array('model' => $model, 'dataProvider' => $dataProvider), false, true);
	}

	public function actionStrength()
	{
		$this->pageTitle = "Partner profile strength score";
		$vndid			 = Yii::app()->request->getParam('vnd_id');
		$view			 = Yii::app()->request->getParam('view', 'strength');
		$model			 = VendorStats::model()->getbyVendorId($vndid);

		$this->render($view, array(
			'model'			 => $model,
			'data'			 => $data,
			'dataProvider'	 => $dataProvider,
			'isAjax'		 => $outputJs
				), false, $outputJs);
	}

	public function actionReduce()
	{
		$vndid	 = Yii::app()->request->getParam('vnd_id');
		$model	 = Vendors::model()->findByPk($vndid);
		if ($model->vnd_rel_tier == 1)
		{
			$model->vnd_rel_tier = 0;
		}
		if ($model->save())
		{
			echo "reduced";
		}
	}

	public function actionPenalty()
	{
		$this->pageTitle						 = "Penalty";
		$vndid									 = Yii::app()->request->getParam('vnd_id');
		$view									 = Yii::app()->request->getParam('view', 'penalty');
		$model									 = new AccountTransDetails();
		$dataProvider							 = AccountTransDetails::model()->getbyVendorId($vndid);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);

		$this->render('penalty', array('model' => $model, 'dataProvider' => $dataProvider), false, true);
//		$this->render($view, array(
//			'model'			 => $model,
//			'data'			 => $data,
//			'dataProvider'	 => $dataProvider,
//			'isAjax'		 => $outputJs
//				), false, $outputJs);
	}

	public function actionPaymentstosend()
	{
		$this->pageTitle = "Payments to send";
		$model			 = new Vendors();
		$dataProvider	 = Vendors::getPayableList();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('paylist', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionPaytransfer()
	{
		$ids		 = Yii::app()->getRequest()->getParam('vnd_ids');
		$vndlists	 = Vendors::getPayDetailsByIds($ids);

		$userInfo = UserInfo::getInstance();
		foreach ($vndlists as $val)
		{
			$transaction = DBUtil::beginTransaction();

			$bank					 = new \Stub\common\Bank();
			$bank->beneficiaryName	 = $val['beneficiary_name'];
			$bank->ifsc				 = $val['bank_ifsc'];
			$bank->accountNumber	 = $val['bank_account_no'];
			$vendorId				 = $val['vnd_id'];
			$amount					 = $val['vrs_withdrawable_balance'];
			$vndremarks				 = $val['vnd_code'];

			$uniqueId					 = round(microtime(true) * 1000) . '';
			$entityArr['entity_type']	 = 2;
			$entityArr['entity_id']		 = $vendorId;
			Vendors::processPayment($vendorId);
			$vndStats					 = VendorStats::model()->getbyVendorId($vendorId);
			$amount						 = $vndStats->vrs_withdrawable_balance;

			/* /Test data/ */
			if (!Yii::app()->icici->api_live)
			{
				$bank = ICICIIB::getTestAccountDetails($bank);

				$amount = '1';
			}


			$response = Yii::app()->icici->registerRequest($bank, $uniqueId, $amount, $entityArr, $vndremarks, $userInfo);
			if ($response)
			{
				DBUtil::commitTransaction($transaction);
			}
			else
			{
				DBUtil::rollbackTransaction($transaction);
			}
		}
		$this->redirect(['paymentstosend']);
		exit;
	}

	public function actionUpdateDetails()
	{
		$vnd_id		 = Yii::app()->request->getParam('vnd_id');
		$returnSet	 = new ReturnSet();
		if ($vnd_id > 0)
		{
			$returnSet = Vendors::model()->updateDetails($vnd_id);
			echo json_encode(['success' => $returnSet->getStatus(), 'message' => $returnSet->getMessage()]);
		}
		else
		{
			echo json_encode(['success' => false, 'message' => "Please provide your vendor Id "]);
		}
		Yii::app()->end();
	}

	public function actionShow()
	{
		Logger::create("1");
		$time				 = Filter::getExecutionTime();
		$GLOBALS['time'][1]	 = $time;
// $_REQUEST['booking_id'];

		$findReturn	 = Yii::app()->request->getParam('ret', '1');
		/* @var $model Vendors */
		$model		 = new Vendors('search');
		$phoneModel	 = new ContactPhone('search');
		if (isset($_REQUEST['Vendors']) || isset($_REQUEST['ContactPhone']))
		{
			$model->attributes		 = Yii::app()->request->getParam('Vendors');
			$phoneModel->attributes	 = Yii::app()->request->getParam('ContactPhone');
			$model->vnd_phone		 = $phoneModel->phn_phone_no;
		}
		Logger::create("2");
		$time						 = Filter::getExecutionTime();
		$GLOBALS['time'][2]			 = $time;
		//	$bkModel					 = Booking::model()->findByPk($bkid);
//	if ($bkModel->bkgAgent->agt_payment_collect_flag == 1 && ($bkModel->bkg_agent_id != NULL || $bkModel->bkg_agent_id != ""))
//	{
//	    $data = "<b>Channel Partner payment outstanding limit exceed. First collect outstanding amount from Channel Partner</b>";
//	    echo $data;
//	    Yii::app()->end();
//	}
		//	$pickupDate					 = date("Y-m-d", strtotime($bkModel->bkg_pickup_date));
//		$preAssignFlag				 = Calendar::model()->getPreAssignmentbyDate($pickupDate);
		//$manualAssignFlag			 = $bkModel->bkgPref->bkg_manual_assignment;
		$checkPreVendorAssignAccess	 = Yii::app()->user->checkAccess('preVendorAssignment');
		//$isNMIcheckedZone			 = InventoryRequest::model()->checkNMIzonebyBkg($bkid);
		$assignBlocked				 = false;
		$checkaccess				 = Yii::app()->user->checkAccess('CriticalAssignment');
		if ($checkaccess)
		{
			$assignBlocked = true;
		}
		Logger::create("4");
		$where									 = '';
		$dataProvider							 = $model->listtoapprove();
		//	$dataProvider							 = $model->fetchByPriority1($bkid, ($findReturn == '1'), $where);
		$dataProvider->getPagination()->params	 = array_filter($_REQUEST);
		$dataProvider->getSort()->params		 = array_filter($_REQUEST);
		$outputJs								 = Yii::app()->request->isAjaxRequest;
		$method									 = "render" . (($outputJs) ? "Partial" : "");
		$this->$method('show', array('dataProvider' => $dataProvider, 'bkModel' => $bkModel, 'assignBlocked' => $assignBlocked, 'bkid' => $bkid, 'bkid2' => $bkid2, 'model' => $model, 'phoneModel' => $phoneModel, 'isNMIcheckedZone' => $isNMIcheckedZone), false, true);
	}

	public function actionvendorTripDetails()
	{
		$vndId			 = Yii::app()->request->getParam('vndId');
		$mycall			 = Yii::app()->request->getParam('mycall');
		////////tripdetails tab data
		$vndTripDetails	 = Vendors::getBookingHistoryById($vndId);
		if ($mycall == 1)
		{
			$this->renderPartial('../vendor/tripDetails', ["dataProvider" => $vndTripDetails], false, true);
		}
		else
		{
			$this->renderPartial("tripDetails", ["dataProvider" => $vndTripDetails], false, true);
		}
	}

	public function actionvendorRatingList()
	{
		$vndId		 = Yii::app()->request->getParam('vndId');
		$mycall		 = Yii::app()->request->getParam('mycall');
		$isRating	 = Yii::app()->request->getParam('israting');

		////////triplist tab data
		$vndRateList = Vendors::getBookingHistoryById($vndId, $isRating);
		if ($mycall == 1)
		{
			$this->renderPartial('../vendor/ratingList', ["dataProvider" => $vndRateList], false, true);
		}
		else
		{
			$this->renderPartial("ratingList", ["dataProvider" => $vndRateList], false, true);
		}
	}

	public function actiontmpRating()
	{
		$vndId		 = Yii::app()->request->getParam('vnd_id');
		$userInfo	 = UserInfo::getInstance();
		$vndtemp	 = VendorPref::model()->tempRatings($vndId, $userInfo);
		$msg		 = "Temporary Ratings boost failed";
		if ($vndtemp == 1)
		{
			$msg = "Temporary Ratings boost added";
		}
		echo json_encode(['success' => $vndtemp, 'message' => $msg]);
	}

	public function actionrefreshVendorAccount()
	{
		$vndID = Yii::app()->request->getParam('vnd_id');
		VendorStats::updateOutstanding($vndID);
		$this->redirect('/aaohome/vendor/vendoraccount?vnd_id=' . $vndID);
	}

	public function actionvendorVehicleDetails()
	{
		$vndId	 = Yii::app()->request->getParam('vndId');
		$vndIds	 = Vendors::getRelatedIds($vndId);
		$cabData = VendorVehicle::getVehicleListByVndId($vndIds, true);
		$this->renderPartial("vehicleDetails", ["cabData" => $cabData], false, true);
	}

	public function actionvendorDriverDetails()
	{
		$vndId		 = Yii::app()->request->getParam('vndId');
		$vndIds		 = Vendors::getRelatedIds($vndId);
		$driverData	 = Drivers::getLstByVendor($vndIds);
		$this->renderPartial("driverDetails", ["driverData" => $driverData], false, true);
	}

	public function actionvendorAccountDetails()
	{
		$vndId = Yii::app()->request->getParam('vndId');

		$vndIds			 = Vendors::getRelatedIds($vndId);
		$data			 = Vendors::model()->getViewDetailbyId($vndId);
		$vendorAmount	 = AccountTransDetails::model()->calAmountByVendorId($vndId);
		$acctData		 = AccountTransDetails::getLastPaymentReceived($vndIds, '2');

		$this->renderPartial("accountDetails", ["data" => $data, 'acctData' => $acctData, "calAmount" => $vendorAmount], false, true);
	}

	public function actionvendorZoneDetails()
	{
		$vndId	 = Yii::app()->request->getParam('vndId');
		$data	 = Vendors::model()->getViewDetailbyId($vndId);
		$this->renderPartial("zoneDetails", ["data" => $data], false, true);
	}

	public function actionvendorProfileStrength()
	{
		$vndId			 = Yii::app()->request->getParam('vndId');
		$mycall			 = Yii::app()->request->getParam('mycall');
		$vndStats		 = VendorStats::model()->getbyVendorId($vndId);
		$dependencyStat	 = VendorStats::calcDependency($vndId);
		$vendorAccount	 = AccountTransDetails::model()->calAmountByVendorId($vndId);

		$this->renderPartial("profileStrength", ['vendorAccount'	 => $vendorAccount,
			"vndStats"		 => $vndStats, 'mycall'		 => $mycall,
			'dependency'	 => $dependencyStat], false, true);
	}

	public function actionvendorBiddingLog()
	{
		$vndId	 = Yii::app()->request->getParam('vndId');
		$mycall	 = Yii::app()->request->getParam('mycall');
		$bidLog	 = BookingVendorRequest::model()->getbidbyVnd($vndId);
		$this->renderPartial("biddingLog", ["bidLog" => $bidLog, 'mycall' => $mycall], false, true);
	}

	public function actionvendorPenalty()
	{
		$vndId	 = Yii::app()->request->getParam('vndId');
		$mycall	 = Yii::app()->request->getParam('mycall');
		$penalty = \AccountTransDetails::model()->getbyVendorId($vndId);
		$this->renderPartial("vndPenalty", ["penalty" => $penalty, 'mycall' => $mycall], false, true);
	}

	public function actionvendorViewLog()
	{
		$vndId	 = Yii::app()->request->getParam('vndId');
		$mycall	 = Yii::app()->request->getParam('mycall');
		$showlog = VendorsLog::model()->getByVendorId($vndId);
		$this->renderPartial("viewLog", ["showlog" => $showlog, 'mycall' => $mycall], false, true);
	}

	public function actionVendorContactViewLog()
	{
		$vndId			 = Yii::app()->request->getParam('vndId');
		$mycall			 = Yii::app()->request->getParam('mycall');
		$showContactLog	 = ContactLog::model()->getByVendorId($vndId);
		$this->renderPartial("viewContactLog", ["showContactLog" => $showContactLog, 'mycall' => $mycall], false, true);
	}

	public function actionvendorScqDetails()
	{
		$vndId		 = Yii::app()->request->getParam('vndId');
		$mycall		 = Yii::app()->request->getParam('mycall');
		$cbrDetails	 = ServiceCallQueue::model()->getCBRDetailbyId($vndId, "Vendor");
		$this->renderPartial("scqDetails", ["cbrdetails" => $cbrDetails, 'mycall' => $mycall], false, true);
	}

	public function actionvendorNotificationLog()
	{
		$vndId			 = Yii::app()->request->getParam('vndId');
		$mycall			 = Yii::app()->request->getParam('mycall');
		$notificationLog = NotificationLog::model()->getbyVendorId($vndId);
		$this->renderPartial("vndNotificationLog", ["notificationLog" => $notificationLog, 'mycall' => $mycall], false, true);
	}

	public function actionvendorDocuments()
	{
		$vndId			 = Yii::app()->request->getParam('vndId');
		$contactId		 = ContactProfile::getByVndId($vndId);
		$docByContactId	 = Document::model()->getAllDocsbyContact($contactId, 'vendor', 2);
		$agreementVendor = VendorAgreement::findAgreementByVndId($vndId);
		$this->renderPartial("vendorDocuments", ["docpath" => $docByContactId, "contactId" => $contactId, "vndId" => $vndId, "agreement" => $agreementVendor], false, true);
	}

	public function actiongetCoinDetails()
	{
		$vndId			 = Yii::app()->request->getParam('vndId');
		$vndIds			 = Vendors::getRelatedIds($vndId);
		$dataProvider	 = VendorCoins::getCoinList($vndIds);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->renderPartial('coinDetails', ['dataProvider' => $dataProvider, 'vndIds' => $vndIds], false, true);
	}

	public function actionPenaltyRules()
	{
		$dataProvider = PenaltyRules::getPenaltyRules($drvId);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->renderAuto('penaltyRules', ['dataProvider' => $dataProvider], false, true);
	}
}
