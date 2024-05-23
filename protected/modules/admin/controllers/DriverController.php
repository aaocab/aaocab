<?php

class DriverController extends Controller
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
                'actions' => array('REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
                'users'   => array('*'),
            ),
            ['allow', 'actions' => ['add', 'add1', 'checkexisting', 'loaddriver', 'loadvendorlist', 'syncfail', 'checkProceed', 'errorView'], 'roles' => ['driverAdd']],
            ['allow', 'actions' => ['list'], 'roles' => ['driverList']],
            ['allow', 'actions' => ['del'], 'roles' => ['driverDelete']],
            ['allow', 'actions' => ['cityfromstate', 'checkvehiclestatus', 'cityfromstate1', 'doc', 'getbyvendor', 'getcontact', 'markedbadlist', 'resetmarkedbad', 'merge', 'showlog', 'view', 'updateDriverDoc', 'rejectDriverDoc', 'addtransaction', 'viewtransaction', 'accountlist', 'socialList', 'unlinkSocialAccount', 'duplicateDriver', 'mergeDuplicateDriver', 'mergeDriver', "linkuser", "link", "profile", "history", "tripDetails", 'UpdateDetails', 'DeviceHistory','PastTripDetails','VndDetails','ScqDetails','DocumentDetails','getCoinDetails','documentlog'], 'users' => ['@']],
            ['allow', 'actions' => ['BroadcastMessage'], 'users' => ['@']],
            array('allow', 'actions' => array('index', 'create', 'json', 'approve', 'freeze',
                    'approveList', 'csrApproveList', 'docapprovallist', 'showdocimg',
					'approvedocimg', 'imagerotate', 'addremark'), 'users'		 => array('@'),),
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
            $ri  = array('/list', '/checkExisting', '/edit', '/editinfo', '/admin_add_driver');
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
            $drv_ids     = Yii::app()->request->getParam('drv_ids');
            $search_txt  = Yii::app()->request->getParam('search_txt');
			#Logger::trace("Search Text : " . $search_txt);
            $page_no     = (int) Yii::app()->request->getParam('page_no');
            $page_number = ($page_no > 0) ? $page_no : 0;
            $driverModel = Drivers::model()->getDetailsAdmin($page_number, 0, $search_txt, $drv_ids);
            $res         = Drivers::model()->getDetailsAdmin($page_number, 1, $search_txt, $drv_ids);
			
            if ($res > 0)
            {
                $pageCount = ceil($res / 20);
            }
            if ($driverModel != [])
            {
                $success = true;
                $error   = null;
            }
            else
            {
                $success = false;
                $error   = "Error occured while fetching list";
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success'     => $success,
                    'error'       => $error,
                    'model'       => $driverModel,
                    'count'       => $res,
                    'total_pages' => $pageCount
                )
            ]);
        });

        $this->onRest('req.get.editinfo.render', function () {
            $success   = false;
            $errors    = 'Something went wrong';
            $driverId  = Yii::app()->request->getParam('drv_id');
            /* @var $model Drivers */
            $model     = Drivers::model()->findByPk($driverId);
            $vendorIds = VendorDriver::model()->findVndIdsByDrvId($driverId);
            if ($model->drvContact->ctt_city != '' && $model->drvContact->ctt_city != null)
            {
                $modelCity = Cities::model()->findByPk($model->drvContact->ctt_city);
            }
            if ($model->drvContact->ctt_state != '' && $model->drvContact->ctt_state != null)
            {
                $modelState = States::model()->findByPk($model->drvContact->ctt_state);
            }
            if ($model != '')
            {
                $success   = true;
                $errors    = '';
                $resDriver = $model->getApiMappingByDriver();

                $data = array(
                    'drv_id'           => $model->drv_id,
                    'drv_name'         => $model->drv_name,
                    'drv_username'     => ($model->drvUser->usr_name != '' && $model->drvUser->usr_name != null) ? $model->drvUser->usr_name : '',
                    'drv_email'        => ($resDriver['drv_email'] != '' && $resDriver['drv_email'] != null) ? $resDriver['drv_email'] : '',
                    'drv_phone'        => ($resDriver['drv_phone'] != '' && $resDriver['drv_phone'] != null) ? $resDriver['drv_phone'] : '',
                    'drv_lic_exp_date' => ($resDriver['drv_lic_exp_date'] != '' && $resDriver['drv_lic_exp_date'] != null) ? $resDriver['drv_lic_exp_date'] : '',
                    'drv_dob_date'     => ($model->drv_dob_date != '' && $model->drv_dob_date != null) ? $model->drv_dob_date : '',
                    'drv_address'      => ($resDriver['drv_address'] != '' && $resDriver['drv_address'] != null) ? $resDriver['drv_address'] : '',
                    'drv_country_code' => ($resDriver['drv_country_code'] != '' && $resDriver['drv_country_code'] != null) ? $resDriver['drv_country_code'] : '',
                    'drv_state'        => ($resDriver['drv_state'] != '' && $resDriver['drv_state'] != null) ? $resDriver['drv_state'] : '',
                    'drv_state_name'   => ($resDriver['drv_state'] != '' && $resDriver['drv_state'] != null) ? $modelState->stt_name : '',
                    'drv_city'         => ($resDriver['drv_city'] != '' && $resDriver['drv_city'] != null) ? $resDriver['drv_city'] : '',
                    'drv_city_name'    => ($resDriver['drv_city'] != '' && $resDriver['drv_city'] != null) ? $modelCity->cty_name : '',
                    'drv_zip'          => ($model->drv_zip != '' && $model->drv_zip != null) ? $model->drv_zip : '',
                    'drv_lic_number'   => ($resDriver['drv_lic_number'] != '' && $resDriver['drv_lic_number'] != null) ? $resDriver['drv_lic_number'] : '',
                    'drv_issue_auth'   => ($resDriver['drv_issue_auth'] != '' && $resDriver['drv_issue_auth'] != null) ? $resDriver['drv_issue_auth'] : '',
                    'drv_is_attached'  => ($model->drv_is_attached != '' && $model->drv_is_attached != null) ? $model->drv_is_attached : '',
                    'drv_vendor_id'    => $vendorIds
                );

                $dataDocs = Document::model()->getDocsByDrvId($model->drv_id, $model->drv_contact_id);
                unset($dataDocs['drv_voter_id'], $dataDocs['drv_voter_back_id'], $dataDocs['drv_police_ver']);
                unset($dataDocs['drv_pan_id'], $dataDocs['drv_pan_back_id']);
                unset($dataDocs['drv_licence_id'], $dataDocs['drv_licence_back_id']);
                unset($dataDocs['drv_aadhaar_id'], $dataDocs['drv_aadhaar_back_id']);
                $newData  = array_merge($data, $dataDocs);
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => $success,
                    'errors'  => $errors,
                    'data'    => $newData,
                )
            ]);
        });

        $this->onRest('req.get.checkExisting.render', function () {
            $token  = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
            $result = Admins::model()->authorizeAdmin($token);
            if ($result)
            {
                $driverModel       = '';
                $vendorIds         = '';
                $success           = false;
                $error             = '';
                $driverId          = '';
                $process_sync_data = Yii::app()->request->getParam('data');
//			$process_sync_data = '{"drv_vendor_id": "43","drv_country_code": "91","drv_phone": "9609275425",}';
                $data              = CJSON::decode($process_sync_data, true);
                Logger::create('GET DATA ===========>: ' . $data, CLogger::LEVEL_TRACE);
                $checkAccess       = Yii::app()->user->checkAccess('driverAdd');
                if ($checkAccess)
                {
                    $result = Drivers::model()->checkExistingDriver($data);
                    if ($result->getStatus())
                    {
                        $success = true;
                        $drv_ids = null;
                        $error   = [];
                        //Logger::create('FAILURE ===========>: ' . $result, CLogger::LEVEL_INFO);
                    }
                    else
                    {
                        $success = false;
                        $drv_ids = $result->getError('drv_ids');
                        $error   = $result->getError('msg');
                        //Logger::create('SUCCESS ===========>: ' . $result, CLogger::LEVEL_INFO);
                    }
                }
                else
                {
                    $success = false;
                    $drv_ids = null;
                    $error   = 'You do not have privilage to add driver.';
                }
            }
            else
            {
                $success = false;
                $errors  = 'You are not authorized';
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => $success,
                    'error'   => $error,
                    'drv_ids' => $drv_ids,
                    'data'    => $data
                )
            ]);
        });

        $this->onRest('req.post.admin_add_driver.render', function () {
            Filter::setLogCategory('trace.controller.modules.vendor.driver.vendor_add_driver');
            Filter::createLog('42 vendor_add_driver ', CLogger::LEVEL_TRACE);
            $checkAccess = Yii::app()->user->checkAccess('driverAdd');
            if ($checkAccess)
            {
                try
                {
                    $process_sync_data      = Yii::app()->request->getParam('data');
                    //$process_sync_data = '{"drv_name":"Test Driver91","drv_email":"test933@gmail.com","drv_country_code":"91","drv_phone":"8874125099","drv_lic_number":"licerno551","drv_aadhaar_number":"Adh3457"}';
                    $data                   = CJSON::decode($process_sync_data, true);
                    $vendorId               = $data['drv_vendor_id'];
                    $drvModel               = new Drivers();
                    $data['drv_vendor_id1'] = $vendorId;
                    $drvModel->isApp        = true;
                    $returnData             = $drvModel->saveInfo($data);
                    $drv_id                 = $returnData->getData()['drv_id'];
                    $success                = $returnData->getStatus();
                    $errors                 = $returnData->getError('errkey');
                    $drv_ids                = $returnData->getData()['drv_ids'];
                }
                catch (Exception $e)
                {
                    $errors = $e->getMessage();
                    Filter::createLog("Driver details not saved. -->" . $e->getMessage(), CLogger::LEVEL_ERROR);
                }
            }
            else
            {
                $success = false;
                $errors  = 'You do not have privilage to add driver.';
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => $success,
                    'errors'  => $errors,
                    'data'    => $drv_id,
                    'drv_ids' => $drv_ids
                )
            ]);
        });

        /**
         * @deprecated since version 23-12-2019
         * @author Suvajit 
         */
        $this->onRest('req.post.edit.render', function () {
            $success           = false;
            $errors            = 'Something went wrong while uploading';
            $process_sync_data = Yii::app()->request->getParam('data');
            //$process_sync_data = '{"drv_id":"0","drv_vendor_id":"6","drv_name":"Pendo","drv_email":"","drv_dob_date":"","drv_phone":"9870456123","drv_lic_exp_date":"","drv_address":"","drv_country_code":"91","drv_zip":"","drv_lic_number":"TAGS6635","drv_issue_auth":"107"}';
            $driverPic         = Yii::app()->request->getParam('driverPic');
            Logger::create('POST DATA ===========>: ' . $process_sync_data, CLogger::LEVEL_TRACE);
            $data              = CJSON::decode($process_sync_data, true);
            $checkAccess       = Yii::app()->user->checkAccess('driverEdit');
            if ($checkAccess)
            {
                $vendorId                 = ($data['drv_vendor_id'] > 0) ? $data['drv_vendor_id'] : 0;
                $driverId                 = $data['drv_id'];
                $model                    = Drivers::model()->findById($driverId);
                $modelDocs                = Document::model()->findAllByDrvId($model->drv_contact_id);
                $oldDocsData['photoFile'] = $model->drvContact->ctt_profile_path;
                $newDocsData['photoFile'] = $model->drvContact->ctt_profile_path;
                $oldData                  = $model->attributes;
                $newData                  = $data;
                $dataSet                  = $model->getApiMappingByDriver($oldData);
                $oldData                  = array_merge($oldData, $dataSet);
                $getOldDifference         = array_diff_assoc($oldData, $newData);
                $user_info                = UserInfo::getInstance();
                $transaction              = DBUtil::beginTransaction();
                try
                {
                    $model = Drivers::model()->findByPk($driverId);
                    if ($driverPic == 0)
                    {
                        if ($model == '')
                        {
                            $model = new Drivers();
                        }
                        $model->scenario                           = 'update';
                        $model->drv_name                           = $data['drv_name'];
                        $model->drv_dob_date                       = $data['drv_dob_date'];
                        $model->drv_zip                            = $data['drv_zip'];
                        $model->drvContact->ctt_address            = $data['drv_address'];
                        $model->drvContact->ctt_city               = $data['drv_city'];
                        $model->drvContact->ctt_state              = $data['drv_state'];
                        $model->drvContact->ctt_license_no         = $data['drv_lic_number'];
                        $model->drvContact->ctt_license_exp_date   = $data['drv_lic_exp_date'];
                        $model->drvContact->ctt_dl_issue_authority = $data['drv_issue_auth'];

                        ContactPhone::model()->updatePhoneByContactId($data['drv_phone'], $model->drv_contact_id);
                        ContactEmail::model()->updateEmailByContactId($data['drv_email'], $model->drv_contact_id);
                        $model->drvContact->isApp = true;
                        if ($model->save())
                        {
                            $model->drvContact->isApp                   = true;
                            $model->drvContact->addType                 = -1;
                            $model->drvContact->locale_license_exp_date = DateTimeFormat::DateToDatePicker($model->drvContact->ctt_license_exp_date);
                            $model->drvContact->save();
                            if ($data['drv_email'] != NULL)
                            {
                                $model->drvContact->contactEmails = $model->drvContact->convertToContactEmailObjects($data['drv_email']);
                                $model->drvContact->saveEmails();
                                ContactEmail::setPrimaryEmail($model->drv_contact_id);
                            }

                            $success         = true;
                            $errors          = [];
                            $modificationMsg = $this->getModificationMSG($getOldDifference, false);
                            if ($modificationMsg != '')
                            {
                                $changesForLog = "<br> Old Values: " . $modificationMsg;
                                $event_id      = DriversLog::DRIVER_MODIFIED;
                                $desc          = "Driver modified |";
                                $desc          .= $changesForLog;
                                DriversLog::model()->createLog($driverId, $desc, UserInfo::getInstance(), $event_id, false, false);
                            }
                        }
                        else
                        {
                            $success = false;
                            throw new Exception("Driver update failed.\n\t\t" . json_encode($model->getErrors()));
                        }
//					
                        if ($vendorId > 0)
                        {
                            $data1  = ['driver' => $model->drv_id, 'vendor' => $vendorId];
                            $linked = VendorDriver::model()->checkAndSave($data1);
                        }
                    }

                    if ($photo != '')
                    {
                        $type      = 'profile';
                        $result1   = $this->saveDriverImage($photo, $photo_tmp, $driverId, $type);
                        $path1     = str_replace("\\", "\\\\", $result1['path']);
                        $qry1      = "UPDATE drivers SET drv_photo_path = '" . $path1 . "' WHERE drv_id = " . $driverId;
                        $recorset1 = Yii::app()->db->createCommand($qry1)->execute();
                        if ($recorset1)
                        {
                            $success = true;
                            $errors  = [];
                        }
                    }
                    if ($success)
                    {
                        if ($data['drv_id'] > 0)
                        {
                            $dataSet          = $model->getApiMappingByDriver($oldData);
                            $oldData          = array_merge($oldData, $dataSet);
                            $getOldDifference = array_diff_assoc($oldData, $newData);
                            $changesForLog    = "<br> Old Values: " . $this->getModificationMSG($getOldDifference, false);
                            $changesForLog    .= "<br>" . $this->getModificationMSG($getOldDifferenceDocs, false);
                            $desc             = "Driver modified | ";
                            $desc             .= $changesForLog;
                            DriversLog::model()->createLog($model->drv_id, $desc, UserInfo::getInstance(), DriversLog::DRIVER_MODIFIED, false, false);
                        }
                        else
                        {
                            $desc = "Driver created |";
                            DriversLog::model()->createLog($model->drv_id, $desc, UserInfo::getInstance(), DriversLog::DRIVER_CREATED, false, false);
                        }
                        Logger::create('SUCCESS ===========>: ' . $model->drv_id . " - " . $desc, CLogger::LEVEL_INFO);

                        DBUtil::commitTransaction($transaction);
                    }
                }
                catch (Exception $e)
                {
                    DBUtil::rollbackTransaction($transaction);
                    $errors = $e->getTraceAsString();
                    Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
                    Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
                    throw $e;
                }
            }
            else
            {
                $success = false;
                $errors  = 'You do not have rights to edit driver.';
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => $success,
                    'errors'  => $errors,
                    'drv_id'  => $driverId,
                ),
            ]);
        });

        /**
         * This function is used for editing driver details from
         * OPS app
         */
        $this->onRest('req.post.editDriverDetails.render', function () {
            $returnSet = new ReturnSet();

            $token  = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
            $result = Admins::model()->authorizeAdmin($token);
            if (!$result)
            {
                $returnSet->setStatus(false);
                $returnSet->setErrors('Access Denied');
                goto resultResponse;
            }

            $checkAccess = Yii::app()->user->checkAccess("driverEdit");
            if (!$checkAccess)
            {
                $returnSet->setStatus(false);
                $returnSet->setErrors("You do not have privilage to edit driver.");
                goto resultResponse;
            }

            $process_sync_data = Yii::app()->request->getParam("data");
            $driverPic         = Yii::app()->request->getParam("driverPic");
            $data              = CJSON::decode($process_sync_data, true);
            $isFileData        = $driverPic;

            $transaction = DBUtil::beginTransaction();
            try
            {
                $driverId = $data["drv_id"];
                switch ($isFileData)
                {
                    case 0:
                        $returnSet = $this->editDriverDetails($data);
                        break;
                    case 1:
                        $returnSet = Document::model()->updateDriverDoc($data["drv_id"], $_FILES['photo']['name'], $_FILES['photo']['tmp_name'], $data1['doc_type'], $data1['doc_subtype']);
                        break;
                    default:
                        break;
                }

                if (!$returnSet->isSuccess())
                {
                    DBUtil::rollbackTransaction($transaction);
                    goto resultResponse;
                }

                DBUtil::commitTransaction($transaction);
            }
            catch (Exception $e)
            {
                DBUtil::rollbackTransaction($transaction);
                $returnSet = ReturnSet::setException($e);
            }

            resultResponse:
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => $returnSet->isSuccess(),
                    'errors'  => $returnSet->getErrors(),
                    'drv_id'  => $driverId,
                ),
            ]);
        });

        $this->onRest('req.post.editdoc.render', function () {
            Filter::setLogCategory("trace.controller.modules.vendor.driver.edit_details_doc");
            Filter::createLog('48 edit_details_doc ', CLogger::LEVEL_TRACE);

            $returnSet = new ReturnSet();
            $token     = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
            $result    = Admins::model()->authorizeAdmin($token);
            if (!$result)
            {
                $returnSet->setStatus(false);
                $returnSet->setErrors('Access Denied');
                goto resultResponse;
            }
            $checkAccessEdit = Yii::app()->user->checkAccess('driverEdit');
            $checkAccessAdd  = Yii::app()->user->checkAccess('driverAdd');
            if (!$checkAccessEdit && !$checkAccessAdd)
            {
                $returnSet->setStatus(false);
                $returnSet->setErrors('You do not have privilage to add/edit driver.');
                goto resultResponse;
            }
            $process_sync_data = Yii::app()->request->getParam('data');
            Filter::createLog("Request =>" . $process_sync_data, CLogger::LEVEL_TRACE);
            $data1             = CJSON::decode($process_sync_data, true);
            $data              = CJSON::decode($data1['data']);
            $data['vendor_id'] = UserInfo::getEntityId();
            $isFileData        = $data1['driverPic'];
            $transaction       = Filter::beginTransaction();
            try
            {
                if ($isFileData == 1)
                {
                    $returnSet = $this->editDriverDetails($data);
                }
                else if ($isFileData == 2)
                {
                    $returnSet = Document::model()->updateDriverDoc($data['drv_id'], $_FILES['photo']['name'], $_FILES['photo']['tmp_name'], $data1['doc_type'], $data1['doc_subtype']);
                }
                if (!$returnSet->isSuccess())
                {
                    Filter::rollbackTransaction($transaction);
                }
                Filter::commitTransaction($transaction);
            }
            catch (Exception $e)
            {
                Filter::rollbackTransaction($transaction);
                $returnSet->setStatus(false);
                $returnSet->setErrors($e->getMessage());
            }
            resultResponse:

            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => $returnSet->isSuccess(),
                    'errors'  => $returnSet->getErrors(),
                    'drv_id'  => $data['drv_id'],
                ),
            ]);
        });

        $this->onRest("req.post.addContact.render", function () {
            return $this->renderJSON($this->addContactNew());
        });
    }

    /**
     * This function is used for editing the driver details
     * @param type $data
     * @return type
     */
    public function editDriverDetails($data)
    {
        $returnSet = new ReturnSet();
        $returnSet->setStatus(true);

        $model        = Drivers::model()->findByPk($data['drv_id']);
        $contactModel = Contact::model()->findByPk($model->drv_contact_id);

        if ($model == "")
        {
            $model = new Drivers();
        }

        if ($contactModel == "")
        {
            $contactModel = new Contact();
        }

        $model->scenario     = "update";
        $model->drv_name     = $data["drv_name"];
        $model->drv_dob_date = $data["drv_dob_date"];
        $model->drv_zip      = $data["drv_zip"];

        $contactModel->ctt_address             = $data["drv_address"];
        $contactModel->ctt_city                = $data["drv_city"];
        $contactModel->ctt_state               = $data["drv_state"];
        $contactModel->ctt_license_no          = $data["drv_lic_number"];
        $contactModel->locale_license_exp_date = DateTimeFormat::DateToDatePicker($data["drv_lic_exp_date"]);
        $contactModel->ctt_dl_issue_authority  = $data["drv_issue_auth"];

        $model->drvContact->isApp = true;
        $contactModel->addType    = -1;

        $cModel     = new Stub\common\ContactMedium();
        $emailModel = $cModel->getEmailModel($data["drv_email"]);
        $phoneModel = $cModel->getPhoneModel($data["drv_phone"], $data["drv_country_code"]);

        $arrEmail = [];
        $arrPhone = [];
        array_push($arrEmail, $emailModel);
        array_push($arrPhone, $phoneModel);

        $contactModel->contactEmails = $arrEmail;
        $contactModel->contactPhones = $arrPhone;

        $returnSet = $contactModel->add();
        if (!$returnSet->getStatus())
        {
            return $returnSet;
        }

        $returnSet = $model->saveInfo($data);
        if (!$returnSet->isSuccess())
        {
            return $returnSet;
        }

        if ($data['vendor_id'] > 0)
        {
            $linked = VendorDriver::model()->checkAndSave(['driver' => $model->drv_id, 'vendor' => $data['vendor_id']]);
            if (!$linked)
            {

                $returnSet->setStatus(false);
                $returnSet->setErrors("Failed to link driver with vendor.");
                return $returnSet;
            }
        }

        return $returnSet;
    }

    /**
     * Sample request : {"vndId": 45,"documents":{"Licence":{"refValue":"FGHVFJHFHG"}},"email":"sk@gmail.com","firstName":"Sudhansu ","lastName":"Roy","primaryContact":{"code":91,"number":"9609275445"}}
     * This function is used for adding driver contact from vendor app.
     * 
     * @return \ReturnSet
     * @throws Exception
     */
    public function addContact()
    {
        $returnSet = new ReturnSet();
        try
        {
            $requestInstance = Yii::app()->request;
            $receivedData    = json_decode($requestInstance->rawBody);
            if (empty($receivedData))
            {
                throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
            };

            $jsonMapper         = new JsonMapper();
            $tmpContactStub     = new Stub\common\ContactMedium();
            /** @var JsonMapper $obj */
            $obj                = $jsonMapper->map($receivedData, $tmpContactStub);
            $contactMediumModel = $obj->getMedium();
            $returnSet          = $contactMediumModel->validateContactItem();

            if ($returnSet->getErrors())
            {
                $returnSet->setMessage("Failed to create driver");
                goto skipAll;
            }

            //Means contact exists
            if ($returnSet->getStatus())
            {
                goto skipAll;
            }

            /** @var Contact $contactMediumModel */
            $returnSet = $contactMediumModel->handleContact();
            if (!$returnSet->getStatus())
            {
                goto skipAll;
            }
            /** @var Drivers $contactMediumModel */
            $returnSet = $contactMediumModel->handleEntity($returnSet->getData());
            if (!$returnSet->getStatus())
            {
                goto skipAll;
            }
        }
        catch (Exception $ex)
        {
            $errors = $ex->getMessage();
            Logger::create("Contact Details not saved. -->" . $errors, CLogger::LEVEL_ERROR);
        }

        skipAll:
        return $returnSet;
    }

    public function actionAdd($status = null)
    {
        $drvid = Yii::app()->request->getParam('drvid');

        $this->pageTitle = "Add Driver";
        $model           = new Drivers('insertAdmin');
        if ($drvid != "")
        {
            $this->pageTitle         = "Edit Driver";
            $model                   = Drivers::model()->findByPk($drvid);
            $model->scenario         = 'updateAdmin';
            $model->drv_contact_name = ($model->drvContact->ctt_user_type == 1) ? $model->drvContact->ctt_first_name . ' ' . $model->drvContact->ctt_last_name : ($model->drvContact->ctt_business_name == NULL ? "" : $model->drvContact->ctt_business_name);
            $model->contactId        = $model->drv_contact_id;
        }
        if (!$model)
        {
            throw new Exception('Invalid data');
        }
        if (isset($_REQUEST['Drivers']))
        {
            $model->attributes = Yii::app()->request->getParam('Drivers');
            $result            = CActiveForm::validate($model, null, false);

            if ($result == '[]')
            {
                $returnSet = $model->saveInfo($_REQUEST['Drivers']);
                if (Yii::app()->request->isAjaxRequest)
                {
                    echo CJSON::encode(array('success' => $returnSet->getStatus()));
                    Yii::app()->end();
                }
                if ($returnSet->getStatus())
                {
                   $this->redirect(array('list'));
                }
            }
            else
            {
                echo CJSON::encode(array('success' => false, 'error' => CJSON::decode($result)));
                Yii::app()->end();
            }
        }

        $this->render('driveredit', [
            'model'  => $model,
            'isNew'  => $model->isNewRecord,
            'errors' => $model->getErrors()
        ]);
    }

    public function actionLinkuser()
    {
        $drvId                                 = Yii::app()->request->getParam('drvId');
        $driverModel                           = Drivers::model()->findByPk($drvId);
        $model                                 = new Users();
        $dataProvider                          = $model->linkedDriverId($drvId);
        $dataProvider->getPagination()->params = array_filter($_REQUEST);
        $dataProvider->getSort()->params       = array_filter($_REQUEST);
        $success                               = false;
        $msg                                   = "";
        if ($_REQUEST['user_id'] != "" && $_REQUEST['user_id'] != null)
        {
            $userModel = Users::model()->findByPk($_REQUEST['user_id']);
            if ($userModel != '' && $drvId != '')
            {
                $driverUserModel = Drivers::model()->find('drv_user_id=:user AND drv_id=:agent', ['user' => $userModel->user_id, 'agent' => $drvId]);
                if ($driverUserModel == '')
                {
                    $msg = "Error Occurred.";
                }
                else if ($driverUserModel != '')
                {
                    $updateDriverModel              = Drivers::model()->findByPk($drvId);
                    $updateDriverModel->drv_user_id = $userModel->user_id;

                    if ($updateDriverModel->save())
                    {
                        $success = true;
                        $msg     = "User unlinked successfully.";
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
        $outputJs = Yii::app()->request->isAjaxRequest;
        $method   = "render" . (($outputJs) ? "Partial" : "");
        $this->$method('linkuser', ['dataProvider' => $dataProvider, 'agentModel' => $driverModel, 'model' => $model], false, true);
    }

    public function actionLink()
    {
        $drvId       = Yii::app()->request->getParam('drvId');
        $driverModel = Drivers::model()->findByPk($drvId);
        $model       = new Users();
        if (isset($_REQUEST['Users']))
        {
            $model->search_name = $_REQUEST['Users']['search_name'];
        }
        $dataProvider                          = $model->searchByNameEmailPhone($drvId);
        $dataProvider->getPagination()->params = array_filter($_REQUEST);
        $dataProvider->getSort()->params       = array_filter($_REQUEST);

        if ($_REQUEST['user_id'] != "" && $_REQUEST['user_id'] != null)
        {
            $msg       = "Error Occurred.";
            $userModel = Users::model()->findByPk($_REQUEST['user_id']);
            if ($userModel != '' && $drvId != '')
            {
                $driverUserModel = Drivers::model()->find('drv_user_id=:user AND drv_id=:agent', ['user' => $userModel->user_id, 'agent' => $drvId]);
                if ($driverUserModel == '')
                {
                    $updateDriverModel              = Drivers::model()->findByPk($drvId);
                    $updateDriverModel->drv_user_id = $userModel->user_id;

                    if ($updateDriverModel->save())
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
        $outputJs = Yii::app()->request->isAjaxRequest;
        $method   = "render" . (($outputJs) ? "Partial" : "");
        $this->$method('link', ['dataProvider' => $dataProvider, 'agentModel' => $driverModel, 'model' => $model], false, true);
    }

    public function getModificationMSG($diff, $user = false)
    {
        $msg = '';
        if (count($diff) > 0)
        {
            if ($diff ['drv_name'])
            {
                $msg .= ' Name: ' . $diff['drv_name'] . ',';
            }

            if ($diff ['drv_phone'])
            {
                $msg .= ' Phone: ' . $diff['drv_phone'] . ',';
            }
            if ($diff ['drv_lic_number'])
            {
                $msg .= ' License #: ' . $diff['drv_lic_number'] . ',';
            }
            if ($diff['drv_lic_exp_date'])
            {
                $msg .= ' License Exp: ' . $diff['drv_lic_exp_date'] . ',';
            }
            if ($diff['drv_voter_id'])
            {
                $msg .= ' Voter ID: ' . $diff['drv_voter_id'] . ',';
            }
            if ($diff['drv_aadhaar_no'])
            {
                $msg .= ' Aadhaar: ' . $diff['drv_aadhaar_no'] . ',';
            }
            if ($diff ['drv_pan_no'])
            {
                $msg .= ' PAN: ' . $diff['drv_pan_no'] . ',';
            }
            if ($diff['drv_issue_auth'])
            {
                $msg .= 'License issued by : ' . $diff['drv_issue_auth'] . ',';
            }
            if ($diff['drv_address'])
            {
                $msg .= ' Address: ' . $diff['drv_address'] . ',';
            }
            if ($diff['drv_email'])
            {
                $msg .= ' Email: ' . $diff['drv_email'] . ',';
            }
//            if ($diff['drv_dob_date'])
//            {
//                $msg .= ' Date of Birth: ' . $diff['drv_dob_date'] . ',';
//            }
//            if ($diff['drv_doj'])
//            {
//                $msg .= ' Date of Joining: ' . $diff['drv_doj'] . ',';
//            }
            if ($diff['drv_state'])
            {
                $smodel = States::model()->findByPk($diff['drv_state']);
                $msg    .= ' State: ' . $smodel->stt_name . ',';
            }
            if ($diff['drv_city'])
            {
                $cmodel = Cities::model()->findByPk($diff['drv_city']);
                $msg    .= ' City: ' . $cmodel->cty_name . ',';
            }
            if ($diff['drv_zip'])
            {
                $msg .= ' Zip: ' . $diff['drv_zip'] . ',';
            }
            if ($diff['drv_approved'] != '')
            {
                switch ($diff['drv_approved'])
                {
                    case 0;
                        $approveStatus = 'Not Verified';
                        break;
                    case 1;
                        $approveStatus = 'Approved';
                        break;
                    case 2;
                        $approveStatus = 'Pending Approval';
                        break;
                    case 3;
                        $approveStatus = 'Rejected';
                        break;
                }
                //$approveStatus = ($diff['drv_approved']==1) ? 'Yes':'No';
                $msg .= ' Status: ' . $approveStatus . ',';
            }


//			if ($diff['photoFile'] != '')
//			{
//				$msg .= ' Driver Selfie : ' . $diff['photoFile'] . ',';
//			}
//			if ($diff['voterCardFile'] != '')
//			{
//				$msg .= ' Voter Id : ' . $diff['voterCardFile'] . ',';
//			}
//			if ($diff['panCardFile'] != '')
//			{
//				$msg .= ' Pan Card : ' . $diff['panCardFile'] . ',';
//			}
//			if ($diff['aadhaarCardFile'] != '')
//			{
//				$msg .= ' Aadhaar Card : ' . $diff['aadhaarCardFile'] . ',';
//			}
//			if ($diff['licenseFile'] != '')
//			{
//				$msg .= ' Driver License : ' . $diff['licenseFile'] . ',';
//			}
//			if ($diff['policeFile'] != '')
//			{
//				$msg .= ' Police verification certificate : ' . $diff['policeFile'] . ',';
//			}
            $msg = rtrim($msg, ',');
        }
        return $msg;
    }

    public function actionUpdateDriverDoc()
    {
        $drd_id     = Yii::app()->request->getParam('drd_id');
        $drd_status = Yii::app()->request->getParam('drd_status');
        if ($drd_status == 1 || $drd_status == 2)
        {
            $modeld                       = DriverDocs::model()->findByPk($drd_id);
            $modeld->drd_status           = $drd_status;
            $modeld->drd_approve_by       = ($drd_status == 1) ? Yii::app()->user->getId() : NULL;
            $modeld->drd_appoved_at       = ($drd_status == 1) ? date("Y-m-d H:i:s") : NULL;
            $modeld->drd_temp_approved    = 0;
            $modeld->drd_temp_approved_at = NULL;
            $modeld->save();

            $retrunVal = '';
            $event_id  = 0;
            if ($modeld->drd_type == 1 && $modeld->drd_sub_type == 1)
            {
                $fileType = "#voter";
                $doctype  = "voterid";
            }
            else if ($modeld->drd_type == 1 && $modeld->drd_sub_type == 2)
            {
                $fileType = "#voterBack";
                $doctype  = "voterbackid";
            }
            else if ($modeld->drd_type == 2 && $modeld->drd_sub_type == 1)
            {
                $fileType = "#pan";
                $doctype  = "pan";
            }
            else if ($modeld->drd_type == 2 && $modeld->drd_sub_type == 2)
            {
                $fileType = "#panBack";
                $doctype  = "panback";
            }
            else if ($modeld->drd_type == 3 && $modeld->drd_sub_type == 1)
            {
                $fileType = "#aadhar";
                $doctype  = "aadhar";
            }
            else if ($modeld->drd_type == 3 && $modeld->drd_sub_type == 2)
            {
                $fileType = "#aadharBack";
                $doctype  = "aadharback";
            }
            else if ($modeld->drd_type == 4 && $modeld->drd_sub_type == 1)
            {
                $fileType = "#dl";
                $doctype  = "license";
            }
            else if ($modeld->drd_type == 4 && $modeld->drd_sub_type == 2)
            {
                $fileType = "#dlback";
                $doctype  = "licenseback";
            }
            else if ($modeld->drd_type == 5 && $modeld->drd_sub_type == NULL)
            {
                $fileType = "#pc";
                $doctype  = "policever";
            }
            $eventLog  = DriversLog::model()->getLogByDocumentType($doctype);
            $event_id  = $eventLog['approve'];
            $desc      = DriversLog::model()->getEventByEventId($event_id);
            $userInfo  = UserInfo::getInstance();
            DriversLog::model()->createLog($modeld->drd_drv_id, $desc, $userInfo, $event_id, false, false);
            $retrunVal = $fileType . "~" . $modeld->drd_status;
        }
        else
        {
            $modeld = DriverDocs::model()->findByPk($drd_id);
            if ($modeld->drd_type == 1 && $modeld->drd_sub_type == 1)
            {
                $fileType = "#voter";
                $doctype  = "voterid";
            }
            else if ($modeld->drd_type == 1 && $modeld->drd_sub_type == 2)
            {
                $fileType = "#voterBack";
                $doctype  = "voterbackid";
            }
            else if ($modeld->drd_type == 2 && $modeld->drd_sub_type == 1)
            {
                $fileType = "#pan";
                $doctype  = "pan";
            }
            else if ($modeld->drd_type == 2 && $modeld->drd_sub_type == 2)
            {
                $fileType = "#panBack";
                $doctype  = "panback";
            }
            else if ($modeld->drd_type == 3 && $modeld->drd_sub_type == 1)
            {
                $fileType = "#aadhar";
                $doctype  = "aadhar";
            }
            else if ($modeld->drd_type == 3 && $modeld->drd_sub_type == 2)
            {
                $fileType = "#aadharBack";
                $doctype  = "aadharback";
            }
            else if ($modeld->drd_type == 4 && $modeld->drd_sub_type == 1)
            {
                $fileType = "#dl";
                $doctype  = "license";
            }
            else if ($modeld->drd_type == 4 && $modeld->drd_sub_type == 2)
            {
                $fileType = "#dlback";
                $doctype  = "licenseback";
            }
            else if ($modeld->drd_type == 5 && $modeld->drd_sub_type == NULL)
            {
                $fileType = "#pc";
                $doctype  = "policever";
            }
            $retrunVal = $fileType . "~" . '3';
        }
        echo $retrunVal;
    }

    public function actionRejectDriverDoc()
    {
        $drd_id     = Yii::app()->request->getParam('drd_id');
        $drd_status = Yii::app()->request->getParam('drd_status');
        $success    = false;
        /* @var $dmodel DriverDocs */
        $dmodel     = DriverDocs::model()->findByPk($drd_id);
        if ($dmodel->drd_type == 1 && $dmodel->drd_sub_type == 1)
        {
            $fileType = "#voter";
            $doctype  = "voterid";
        }
        else if ($dmodel->drd_type == 1 && $dmodel->drd_sub_type == 2)
        {
            $fileType = "#voterBack";
            $doctype  = "voterbackid";
        }
        if ($dmodel->drd_type == 2 && $dmodel->drd_sub_type == 1)
        {
            $fileType = "#pan";
            $doctype  = "pan";
        }
        else if ($dmodel->drd_type == 2 && $dmodel->drd_sub_type == 2)
        {
            $fileType = "#panBack";
            $doctype  = "panback";
        }
        if ($dmodel->drd_type == 3 && $dmodel->drd_sub_type == 1)
        {
            $fileType = "#aadhar";
            $doctype  = "aadhar";
        }
        else if ($dmodel->drd_type == 3 && $dmodel->drd_sub_type == 2)
        {
            $fileType = "#aadharBack";
            $doctype  = "aadharback";
        }
        if ($dmodel->drd_type == 4 && $dmodel->drd_sub_type == 1)
        {
            $fileType = "#dl";
            $doctype  = "license";
        }
        else if ($dmodel->drd_type == 4 && $dmodel->drd_sub_type == 2)
        {
            $fileType = "#dlback";
            $doctype  = "licenseback";
        }
        if ($dmodel->drd_type == 5 && $dmodel->drd_sub_type == NULL)
        {
            $fileType = "#pc";
            $doctype  = "policever";
        }
        $model           = new DriverDocs();
        $model->scenario = 'reject';
        if (isset($_POST['DriverDocs']))
        {
            $model->attributes   = Yii::app()->request->getParam('DriverDocs');
            $arr                 = $model->attributes;
            $dmodel->drd_remarks = $arr['drd_remarks'];
            $dmodel->drd_status  = $drd_status;

            $dmodel->drd_temp_approved    = 0;
            $dmodel->drd_temp_approved_at = NULL;

            if ($dmodel->save())
            {
                $eventLog = DriversLog::model()->getLogByDocumentType($doctype);
                $event_id = $eventLog['reject'];
                $userInfo = UserInfo::getInstance();
                DriversLog::model()->createLog($dmodel->drd_drv_id, $arr['drd_remarks'], $userInfo, $event_id, false, false);
                $success  = true;
            }
            if (Yii::app()->request->isAjaxRequest)
            {
                $remarks = '<i>' . $dmodel->drd_remarks . '</i>';
                $data    = ['success' => $success, 'file_type' => $fileType, 'status' => $dmodel->drd_status, 'remarks' => $remarks];
                echo json_encode($data);
                Yii::app()->end();
            }
        }
        $this->renderPartial('rejectremarks', array('drd_id'     => $drd_id,
            'drd_status' => $drd_status,
            'model'      => $model,
            'dmodel'     => $dmodel), false, true);
    }

    public function actionList($qry = [])
    {

        $this->pageTitle   = "Driver List";
        $pageSize          = Yii::app()->params['listPerPage'];
        /* $qry['searchname'] = trim(Yii::app()->request->getParam('searchname'));
          $qry['searchemail'] = trim(Yii::app()->request->getParam('searchemail'));
          $qry['searchphone'] = trim(Yii::app()->request->getParam('searchphone'));
          $qry['source'] = trim(Yii::app()->request->getParam('source'));
          $qry['vnd_id'] = trim($_REQUEST['Vendors']['vnd_id']);
          if (trim(Yii::app()->request->getParam('searchmarkdriver')))
          {
          $qry['searchmarkdriver'] = 1;
          } */
        /* @var $model Drivers */
        $model             = new Drivers();
        $model->drv_source = Yii::app()->request->getParam('source', '');
        //$model->drv_trip_type = '1';
        $vnd               = Yii::app()->request->getParam('vnd', '');
        $code              = Yii::app()->request->getParam('code', '');
        if ($vnd > 0)
        {
            $model->drv_vendor_id = $vnd;
        }
        $approve = Yii::app()->request->getParam('approve', '0');
        if ($approve > 0)
        {
            $model->drv_approved = $approve;
        }
        $model->vndlist = Yii::app()->request->getParam('vndlist', '0');
        if ($model->drv_source == 222)
        {
            $this->pageTitle = "Driver List ( Drivers ready for approval )";
        }
        if ($_REQUEST['Drivers'])
        {
            $model->drv_vendor_id = $vendorId;
        }
        if (isset($code) && $code != '')
        {
            $model->drv_name  = $code;
            $model->drv_name2 = $code;
        }

        if ($_REQUEST['Drivers'] && ($vendorId != '' || $vendorId == 0))
        {
            $arr = Yii::app()->request->getParam('Drivers');
            if (trim(Yii::app()->request->getParam('searchmarkdriver')))
            {
                $qry['searchmarkdriver'] = 1;
            }
            if (trim(Yii::app()->request->getParam('searchdlmismatch')))
            {
                $qry['searchdlmismatch'] = 2;
            }
            if (trim(Yii::app()->request->getParam('searchpanmismatch')))
            {
                $qry['searchpanmismatch'] = 2;
            }
            $licenseNo = trim(Yii::app()->request->getParam('searchLicense'));
            if ($licenseNo)
            {
                $qry['searchLicense'] = $licenseNo;
            }
            $qry['drv_approved']  = $arr['drv_approved'];
            $model->drv_approved  = $arr['drv_approved'];
            $model->drv_name      = $arr['drv_name2'];
            $model->drv_name2     = $arr['drv_name2'];
            $model->drv_phone     = $arr['drv_phone2'];
            $model->drv_phone2    = $arr['drv_phone2'];
            $model->drv_email     = $arr['drv_email2'];
            $model->drv_email2    = $arr['drv_email2'];
            $model->drv_vendor_id = $arr['drv_vendor_id'];
            if (count($arr['drv_trip_type']) > 0)
            {
                $model->drv_trip_type = implode(',', $arr['drv_trip_type']);
            }
            else
            {
                $model->drv_trip_type = '';
            }
        }

        //$dataProvider = Drivers::model()->fetchList($qry);
        $dataProvider = $model->getList($qry);

        $dataProvider->setSort(['params' => array_filter($_REQUEST)]);
        $dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
        $this->render('list', array('model' => $model, 'dataProvider' => $dataProvider, 'qry' => $qry));
    }

    public function actionShowlog()
    {
        $drv_id                                = Yii::app()->request->getParam('drvId');
        $viewType                              = Yii::app()->request->getParam('view');
        $dataProvider                          = DriversLog::model()->getByDriverId($drv_id, $viewType);
        $dataProvider->getPagination()->params = array_filter($_GET + $_POST);
        $dataProvider->getSort()->params       = array_filter($_GET + $_POST);
        $outputJs                              = Yii::app()->request->isAjaxRequest;
        $method                                = "render" . (($outputJs) ? "Partial" : "");

//Partial
        $this->$method('showlog', ['dataProvider' => $dataProvider,
            'model'        => $model], false, true);
    }

	public function actionDocumentlog()
    {
        $drv_id                                = Yii::app()->request->getParam('drvId');
        $viewType                              = Yii::app()->request->getParam('view');
        $dataProvider                          = ContactLog::model()->getByDriverId($drv_id, $viewType);
        $dataProvider->getPagination()->params = array_filter($_GET + $_POST);
        $dataProvider->getSort()->params       = array_filter($_GET + $_POST);
        $outputJs                              = Yii::app()->request->isAjaxRequest;
        $method                                = "render" . (($outputJs) ? "Partial" : "");
        $this->$method('documentlog', ['dataProvider' => $dataProvider,'model' => $model], false, true);
    }

    public function actionCityfromstate()
    {
        $stateId  = Yii::app()->request->getParam('id');
        $cityList = CHtml::listData(Cities::model()->findAll(array("condition" => "cty_state_id = $stateId")), 'cty_id', 'cty_name');
        echo CJSON::encode(array('citylist' => $cityList));
    }

    public function actionCityfromstate1()
    {
        $stateId  = Yii::app()->request->getParam('id');
        $cityList = CHtml::listData(Cities::model()->findAll(array("condition" => "cty_state_id = $stateId", "order" => "cty_name")), 'cty_id', 'cty_name');
        $data     = VehicleTypes::model()->getJSON($cityList);
        echo $data;
    }

    public function actionDel()
    {
        $id = Yii::app()->request->getParam('drvid');
        if ($id != '')
        {
            $userInfo          = UserInfo::getInstance();
            $desc              = "Driver is Deleted.";
            $model             = Drivers::model()->findByPk($id);
            $model->drv_active = 0;
            if ($model->save())
            {
                DriversLog::model()->createLog($id, $desc, $userInfo, DriversLog::DRIVER_DELETE, false, false);
            }

            //Remove 
            $updateVendorDriverMapping = " UPDATE vendor_driver SET vdrv_active = 0 WHERE vdrv_drv_id = $id AND vdrv_active = 1";
            $cnt                       = Yii::app()->db->createCommand($updateVendorDriverMapping)->execute();

            if ($cnt > 0)
            {
                DriversLog::model()->createLog($id, "Driver's vendor relationship deleted", $userInfo, DriversLog::DRIVER_DELETE, false, false);
            }
        }
        $this->redirect(array('list'));
    }

    public function actionCheckvehiclestatus()
    {
        $vhcid = Yii::app()->request->getParam('vhcid');
        $vhd   = VehicleDriver::model()->getVehiclestatus($vhcid);
        if ($vhd)
        {
            echo true;
        }
        else
        {
            echo false;
        }
    }

    public function actionCreate()
    {
        $agtid                 = Yii::app()->request->getParam('agtid');
        $model                 = new Drivers('insert');
        $model->drv_vendor_id1 = $agtid;
        if (isset($_REQUEST['Drivers']))
        {

            $arr1              = Yii::app()->request->getParam('Drivers');
            unset($arr1['drv_id']);
            $model->attributes = $arr1;
            $model->drv_ip     = trim(\Filter::getUserIP());
            $model->drv_device = $_SERVER['HTTP_USER_AGENT'];
            $model->drv_doj    = ($arr1['drv_doj'] != "") ? date('Y-m-d', strtotime($arr1['drv_doj'])) : date('Y-m-d');
            $result            = CActiveForm::validate($model);

            $modelContact           = new Contact();
            $modelContact->ctt_id   = $model->drv_contact_id;
            $modelContact->scenario = 'drivercontactinfo';
            $resultcontact          = CActiveForm::validate($modelContact, null, false);
            if ($resultcontact != '[]')
            {
                $returns['success']      = false;
                $returns['errorcontact'] = CJSON::decode($resultcontact);
                echo CJSON::encode($returns);
                Yii::app()->end();
            }

            if ($result == '[]')
            {
                $model->save();
                if ($arr1['drv_vendor_id1'] > 0)
                {
                    $model1 = Drivers::model()->checkExisting($arr1);
                    $data   = ['vendor' => $arr1['drv_vendor_id1'], 'driver' => $model1[0]['drv_id']];
                    $linked = VendorDriver::model()->checkAndSave($data);
                }
            }

            if (Yii::app()->request->isAjaxRequest)
            {
                echo $result;
                Yii::app()->end();
            }
        }
        //$model->drv_country_code = '91';
        //$model->drvContact->contactPhones->phn_phone_country_code = '91';
        $outputJs = Yii::app()->request->isAjaxRequest;
        $method   = "render" . ($outputJs ? "Partial" : "");
        $this->$method('driveredit', array('model' => $model), false, $outputJs);
    }

    public function actionJson()
    {
        $agtid = Yii::app()->request->getParam('agtid');
        if ($agtid == '')
        {
            $driverModel = Drivers::model()->orderByName()->findAll();
            $arrDriver   = array();
            foreach ($driverModel as $arr)
            {
//$countrycode=($arr->drv_country_code =='')?'91':$arr->drv_country_code;
                $arrDriver[] = array("id" => $arr->drv_id, "text" => $arr->drv_name . '(' . $arr->drv_phone . ')');
            }
            $data = CJSON::encode($arrDriver);

            echo $data;
        }
        else
        {
            $driverJson = Drivers::model()->getJSONbyVendor($agtid);
            echo $driverJson;
        }
        Yii::app()->end();
    }

    public function actionDoc()
    {
        $ID    = Yii::app()->request->getParam('drv_id');
        $model = Drivers::model()->findByPk($ID);

        $this->renderPartial('doc', array('id'    => $ID,
            'model' => $model
                ), FALSE, true);
    }

    public function actionGetbyvendor()
    {
        $vendor  = Yii::app()->request->getParam('vendor');
        $drivers = Drivers::model()->getJSONbyVendor($vendor);
        echo $drivers;
        Yii::app()->end();
    }

    public function actionGetcontact()
    {
        $drvid   = Yii::app()->request->getParam('drvid');
        $drivers = Drivers::model()->findById($drvid);
        $data    = CJSON::encode(ContactPhone::model()->findContact($drivers->drv_contact_id));
        echo $data;
        Yii::app()->end();
    }

    public function actionMarkedbadlist()
    {
        $drvId        = Yii::app()->request->getParam('drv_id');
        /* var $model Drivers */
        $model        = new Drivers();
        $dataProvider = $model->markedBadListByDriverId($drvId);
        $this->renderPartial('markedbadlist', array('model'        => $model,
            'dataProvider' => $dataProvider, 'drvId'        => $drvId), false, true);
    }

    public function actionResetmarkedbad()
    {
        $refId              = Yii::app()->request->getParam('refId');
        /* var $model Drivers */
        $drvModel           = Drivers::model()->findByPk($refId);
        $old_markbad_count  = $drvModel->drv_mark_driver_count;
        $remark             = $drvModel->drv_log;
        $drvModel->scenario = 'reset';
        if (isset($_POST['Drivers']))
        {
            $arr                  = Yii::app()->request->getParam('Drivers');
            $drvModel->attributes = $arr;
            $drvModel->resetScope();
            $dt                   = date('Y-m-d H:i:s');
            $user                 = Yii::app()->user->getId();
            $new_remark           = $arr['drv_reset_desc'];
            $success              = false;
            if ($new_remark != '')
            {
                if ($drvModel->validate())
                {
                    if ($new_remark != '')
                    {
                        if (is_string($remark))
                        {
                            $newcomm = CJSON::decode($remark);
                            if ($remark != '' && CJSON::decode($remark) == '')
                            {
                                $newcomm = array(array(0 => $user, 1 => $drvModel->vhc_created_at, 2 => $remark, 3 => $old_markbad_count));
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
                        $drvModel->drv_log = CJSON::encode($newcomm);
                        try
                        {
                            $drvModel->drv_mark_driver_count = 0;
                            if ($drvModel->save())
                            {
                                $success = true;
                            }
                            else
                            {
                                $success = false;
                                $errors  = $drvModel->getErrors();
                                echo $errors;
                            }
                        }
                        catch (Exception $e)
                        {
                            echo $e;
                        }
                    }
                }
                else
                {
                    $errors = $drvModel->getErrors();
                }
            }
            if (Yii::app()->request->isAjaxRequest)
            {
                $data = ['success' => $success];
                echo json_encode($data);
                Yii::app()->end();
            }
            $this->redirect(array('list', 'tab' => $tab));
        }
        $this->renderPartial('resetmarkedbad', array('refId' => $refId, 'drvModel' => $drvModel), false, true);
    }

    public function actionBroadcastMessage()
    {
        $ids         = Yii::app()->getRequest()->getParam('drv_id');
        $message     = Yii::app()->getRequest()->getParam('message');
        $peices      = explode(',', $ids);
        $ext         = '91';
        $messageType = '';
        foreach ($peices as $id)
        {
            $model3 = Drivers::model()->findByPk($id);
            $number = ContactPhone::model()->getContactPhoneById($model3->drv_contact_id);
            $name   = $model3->drv_name;
            $msgCom = new smsWrapper();
            if ((Yii::app()->getRequest()->getParam('sms')) == 'true')
            {
                $msgCom->informUpdateToDriver($ext, $number, $messageType, $message, $name);
            }
            if ((Yii::app()->getRequest()->getParam('email')) == 'true')
            {
                $emailCom = new emailWrapper();
                $emailCom->driverUpdate($id, $message, $messageType);
            }
            if ((Yii::app()->getRequest()->getParam('app')) == 'true')
            {
//Add code
            }
        }
        $this->redirect(array('list'));
    }

    public function actionApproveList()
    {
        $this->pageTitle = '';
        $driverModel     = new DriversInfo();
        if (isset($_REQUEST['DriversInfo']))
        {
            $driverModel->attributes = Yii::app()->request->getParam('DriversInfo');
        }
        $driverDataProvider                          = $driverModel->fetchList();
        $driverDataProvider->getPagination()->params = array_filter($_GET + $_POST);
        $driverDataProvider->getSort()->params       = array_filter($_GET + $_POST);
        $this->render('listtoapprove', array('driverDataProvider' => $driverDataProvider, 'driverModel' => $driverModel));
    }

    public function actionCsrApproveList()
    {
        $this->pageTitle = "Approved Car / Driver By Member";
        $model           = new Drivers();
        if (isset($_REQUEST['Drivers']))
        {
            $arr                      = Yii::app()->request->getParam('Drivers');
            $model->approve_from_date = $arr['approve_from_date'];
            $model->approve_to_date   = $arr['approve_to_date'];
        }
        else
        {
            $model->approve_from_date = DateTimeFormat::DateToLocale(date('Y-m-d', strtotime('-8 days')));
            $model->approve_to_date   = DateTimeFormat::DateToLocale(date('Y-m-d', strtotime('-1 day')));
        }
        $date1              = DateTimeFormat::DatePickerToDate($model->approve_from_date);
        $date2              = DateTimeFormat::DatePickerToDate($model->approve_to_date);
        $driverDataProvider = Drivers::model()->carDriverApproveList($date1, $date2);
        //$driverDataProvider->getPagination()->params = array_filter($_GET + $_POST);
        //$driverDataProvider->getSort()->params = array_filter($_GET + $_POST);
        $this->render('csrapprove', array('driverDataProvider' => $driverDataProvider, 'model' => $model));
    }

    public function actionApprove()
    {
        $vhcId            = Yii::app()->request->getParam('drvid');
        $approve          = Yii::app()->request->getParam('approve');
        $modelDriversInfo = DriversInfo::model()->findByPk($vhcId);
        if ($modelDriversInfo->drv_driver_id != '')
        {
            $modelDriver = Drivers::model()->findByPk($modelDriversInfo->drv_driver_id);
        }
        if (isset($_POST['DriversInfo']) && isset($_POST['verifysubmit']))
        {
            $modelDriversInfo                        = DriversInfo::model()->findByPk($_POST['DriversInfo']['drv_id']);
            $modelDriver                             = Drivers::model()->findByPk($modelDriversInfo->drv_driver_id);
            $modelDriver->scenario                   = "approve";
            $vnddrv                                  = $modelDriversInfo->drv_vendor_id;
            $modelDriversInfo->unsetAttributes(['drv_vendor_id']);
            $modelDriver->attributes                 = array_filter($modelDriversInfo->attributes);
            $modelDriversInfo->attributes            = $vnddrv;
            $modelDriver->drv_bg_checked             = $modelDriversInfo->drv_bg_checked;
            $modelDriver->drv_police_certificate     = $modelDriversInfo->drv_police_certificate;
            unset($modelDriver->drv_id);
            unset($modelDriver->drv_modified);
            $modelDriver->drv_ver_adrs_proof         = isset($_POST['chk1']) ? 1 : 0;
            $modelDriver->drv_ver_licence            = isset($_POST['chk2']) ? 1 : 0;
            $modelDriver->drv_ver_police_certificate = isset($_POST['chk3']) ? 1 : 0;
            $modelDriver->drv_approved               = 1;
            $modelDriver->drv_approved_by            = Yii::app()->user->getId();
            if ($modelDriver->save())
            {
                $modelDriversInfo->drv_approved = 1;
                $modelDriversInfo->update();
            }
            else
            {
                $modelDriver->getErrors();
            }
            $this->redirect(['approveList']);
        }
        if (isset($_POST['DriversInfo']) && isset($_POST['verifysave']))
        {
            $modelDriver                             = Drivers::model()->findByPk($modelDriversInfo->drv_driver_id);
            $modelDriver->drv_ver_adrs_proof         = isset($_POST['chk1']) ? 1 : 0;
            $modelDriver->drv_ver_licence            = isset($_POST['chk2']) ? 1 : 0;
            $modelDriver->drv_ver_police_certificate = isset($_POST['chk3']) ? 1 : 0;
            if ($modelDriver->drv_ver_adrs_proof == 1)
            {
                $modelDriver->drv_aadhaar_img_path  = $modelDriversInfo->drv_aadhaar_img_path;
                $modelDriver->drv_pan_img_path      = $modelDriversInfo->drv_pan_img_path;
                $modelDriver->drv_voter_id_img_path = $modelDriversInfo->drv_voter_id_img_path;
            }
            if ($modelDriver->drv_ver_licence == 1)
            {
                $modelDriver->drv_lic_number   = $modelDriversInfo->drv_lic_number;
                $modelDriver->drv_licence_path = $modelDriversInfo->drv_licence_path;
                $modelDriver->drv_lic_exp_date = $modelDriversInfo->drv_lic_exp_date;
            }
            if ($modelDriver->drv_ver_police_certificate == 1)
            {
                $modelDriver->drv_police_certificate = $modelDriversInfo->drv_police_certificate;
            }
            if ($modelDriver->drv_ver_adrs_proof == 1 && $modelDriver->drv_ver_licence && $modelDriver->drv_ver_police_certificate)
            {
                $modelDriver->drv_approved      = 1;
                $modelDriversInfo->drv_approved = 1;
            }
            else
            {
                $modelDriver->drv_approved      = 2;
                $modelDriversInfo->drv_approved = 2;
            }
            $modelDriver->drv_approved_by = Yii::app()->user->getId();
            $modelDriver->scenario        = 'addverify';
            $modelDriver->save();
            $modelDriversInfo->save();
            $this->redirect(['approveList']);
        }
        if (isset($_POST['DriversInfo']) && isset($_POST['rejectsave']))
        {
            $modelDriver                    = Drivers::model()->findByPk($modelDriversInfo->drv_driver_id);
            $modelDriver->drv_approved      = 3;
            $modelDriversInfo->drv_approved = 3;
            $modelDriver->drv_approved_by   = Yii::app()->user->getId();
            $modelDriversInfo->save();
            $modelDriver->save();
            $this->redirect(['approveList']);
        }
        $this->renderPartial('detailtoapprove', ['model' => $modelDriversInfo, 'modelDriver' => $modelDriver], false, true);
    }

    public function actionFreeze()
    {
        $drvId       = Yii::app()->request->getParam('drv_id');
        $drvIsFreeze = Yii::app()->request->getParam('drv_is_freeze');
        $userInfo    = UserInfo::getInstance();
        $checkaccess = Yii::app()->user->checkAccess('vendorChangestatus');
        /* @var $model Drivers */
        if (!$checkaccess)
        {
            $commentText = "You are not authorized for this action. Contact your operation manager.";
        }
        else
        {
            $model              = Drivers::model()->resetScope()->findByPk($drvId);
            $commentText        = ($model->drv_is_freeze > 0) ? 'Add comments on why the driver is being frozen. What actions are needed before unfreezing them?' : 'Add comments on why the driver is being not frozen. What actions are needed before freezing them?';
            /* @var $logModel DriversLog */
            $logModel           = new DriversLog();
            $logModel->scenario = 'updateFreeze';
            $success            = false;
            if (isset($_POST['DriversLog']))
            {
				try
				{
					$logModel->attributes = Yii::app()->request->getParam('DriversLog');
					$arr                  = $logModel->attributes;
					switch ($drvIsFreeze)
					{
						case 0:
							$model->drv_is_freeze = 1;
							$eventId              = DriversLog::DRIVER_FREEZE;
							break;

						case 1:
							$model->drv_is_freeze = 0;
							$eventId              = DriversLog::DRIVER_UNFREEZE;
							break;
					}
					$transaction		 = DBUtil::beginTransaction();
					if ($model->save())
					{
						if ($eventId == 5)
						{
							$model->unAssignFreezeDriver($drvId);
						}
						DriversLog::model()->createLog($arr['dlg_drv_id'], $arr['dlg_desc'], $userInfo, $eventId, false, false);
						$success = true;
						DBUtil::commitTransaction($transaction);
					}
					else
					{
						$success = false;
						throw new Exception("Request can not be proceed", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
					}
					if (Yii::app()->request->isAjaxRequest)
					{
						$data = ['success' => true];
						echo json_encode($data);
						Yii::app()->end();
					}
				}
				catch (Exception $e)
				{
					DBUtil::rollbackTransaction($transaction);
					Logger::exception($e);
					$returnset->setException($e);
				}
			}
        }
        $outputJs = Yii::app()->request->isAjaxRequest;
        $method   = "render" . ($outputJs ? "Partial" : "");
        $this->$method('freeze', array('model'       => $model,
            'logModel'    => $logModel, 'checkaccess' => $checkaccess,
            'comment'     => $commentText), FALSE, $outputJs);
    }

    public function actionCheckexisting()
    {
        $vndid     = Yii::app()->request->getParam('vndid');
        $drvname   = Yii::app()->request->getParam('drvname');
        $contactid = Yii::app()->request->getParam('ctcid');
        $qry1      = [
            'drv_vendor_id1' => $vndid,
            'drv_contact_id' => $contactid,
            'drv_name'       => $drvname
        ];
        $qry       = array_filter($qry1);
        $cnt       = count($qry);
        $found     = Drivers::model()->checkExisting($qry);
        $drv       = $found[0];
        if (!$drv['drv_id'])
        {
            unset($drv['drv_id']);
        }
        $drv['vcount'] = $cnt;
        $v             = [];
        if ($drv['vendors'])
        {
            $v = explode(',', $drv['vendors']);
            if (in_array($vndid, $v))
            {
                $drv['this_vendor'] = 1;
            }
            else
            {
                $drv['this_vendor'] = 0;
            }
        }
        $data    = array_diff($drv, ['']);
        $dataVal = CJSON::encode($data);
        echo $dataVal;
        Yii::app()->end();
    }

    public function actionLoaddriver()
    {
        $drvid   = Yii::app()->request->getParam('drvid');
        $model   = Drivers::model()->findByPk($drvid);
        $oldData = array_filter($model->attributes);
        $dataVal = CJSON::encode($oldData);
        echo $dataVal;
        Yii::app()->end();
    }

    public function actionLoadvendorlist()
    {
        $drvid = Yii::app()->request->getParam('drvid');
        $data  = VendorDriver::model()->getVendorListbyDriverid($drvid);
        $vnd   = [];
        $h     = '<div class="col-xs-12" style="background:#fff">';
        if (sizeof($data) > 0)
        {
            $h .= "<h4>Vendor assigned : </h4>";
        }
        $h .= "<ul style='padding-left:10px'>";
        foreach ($data as $val)
        {
            $h .= "<li>";
            $h .= $val['vnd_name'];
            $h .= "</li>";
        }
        $h .= "</ul></div>";

//  $dataVal = CJSON::encode($oldData);
        echo $h;
        Yii::app()->end();
    }

    /*
     * @deprecated not in use
     */

    public function actionMerge()
    {
        $drvId  = Yii::app()->request->getParam('drv_id');
        $model  = new Drivers('search');
        $active = 11;
        if (isset($_REQUEST['Drivers']))
        {
            $active            = 1;
            $arr               = array_filter(Yii::app()->request->getParam('Drivers'));
            $model->attributes = $arr;
            $model->drv_phone  = $arr['drv_phone'];
            $model->drv_email  = $arr['drv_email'];
        }
        $dataProvider = $model->getRelatedDrivers($drvId, $arr, $active);
        $this->renderPartial('merge', array('model'        => $model,
            'dataProvider' => $dataProvider, 'drvId'        => $drvId, 'active'       => $active), false, true);
    }

    public function getDrvDetails()
    {
        $drv = [
            'drv_name', 'drv_username',
            'drv_country_code', 'drv_phone', 'drv_alt_phone', 'drv_email',
            'drv_photo', 'drv_photo_path', 'drv_lic_number', 'drv_issue_auth',
            'drv_lic_exp_date', 'drv_address', 'drv_city', 'drv_state', 'drv_zip',
            'drv_aadhaar_img', 'drv_aadhaar_img_path', 'drv_pan_img', 'drv_pan_img_path',
            'drv_voter_id_img', 'drv_voter_id_img_path', 'drv_pan_no', 'drv_voter_id',
            'drv_aadhaar_no', 'drv_description', 'drv_history',
            'drv_mark_driver_count', 'drv_code_password', 'drv_licence_path',
            'drv_adrs_proof1', 'drv_adrs_proof2', 'drv_police_certificate',
            'drv_ver_adrs_proof', 'drv_ver_licence', 'drv_ver_police_certificate', 'drv_city', 'drv_state', 'drv_zip',];
        return $drv;
    }

    public function moveMergeFile($filename, $driverId, $oldpath)
    {
        //$filename = $driverId . "-" . $type . "-" . date('YmdHis') . "." . pathinfo($uploadedFile, PATHINFO_EXTENSION);


        $filenameArr = explode('-', $filename);
        $newFileName = $driverId . '-' . $filenameArr[1] . '-' . $filenameArr[2];

        $fromPath = PUBLIC_PATH . str_replace('/', DIRECTORY_SEPARATOR, $oldpath);
        $fvalarr  = explode('/', $oldpath);
        if ($fvalarr[2] > 0)
        {
            unset($fvalarr[2]);
        }
        $fromPath1 = PUBLIC_PATH . implode(DIRECTORY_SEPARATOR, $fvalarr);
        $dir       = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
        if (!is_dir($dir))
        {
            @mkdir($dir, 777, true);
        }
        $dirFolderName = $dir . DIRECTORY_SEPARATOR . 'drivers';
        if (!is_dir($dirFolderName))
        {
            @mkdir($dirFolderName, 777, true);
        }
        $dirByDriverId = $dirFolderName . DIRECTORY_SEPARATOR . $driverId;
        if (!is_dir($dirByDriverId))
        {
            @mkdir($dirFolderName, 777, true);
        }
        $foldertoupload = $dirByDriverId . DIRECTORY_SEPARATOR . $newFileName;
        $newpath        = '';
        if (file_exists($fromPath))
        {
            if (copy($fromPath, $foldertoupload))
            {
                $newpath = '\\attachments\\drivers\\' . $driverId . '\\' . $newFileName;
            }
        }
        else
        {
            if (copy($fromPath1, $foldertoupload))
            {
                $newpath = '\\attachments\\drivers\\' . $driverId . '\\' . $newFileName;
            }
        }

        return $newpath;
    }

    public function saveDriverImage($image, $imagetmp, $driverId, $type)
    {
        try
        {
            $path = "";
            if ($image != '')
            {
                $image = $driverId . "-" . $type . "-" . date('YmdHis') . "." . $image;
                $dir   = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
                if (!is_dir($dir))
                {
                    mkdir($dir);
                }
                $dirFolderName = $dir . DIRECTORY_SEPARATOR . 'drivers';
                if (!is_dir($dirFolderName))
                {
                    mkdir($dirFolderName);
                }
                $dirByVehicleId = $dirFolderName . DIRECTORY_SEPARATOR . $driverId;
                if (!is_dir($dirByVehicleId))
                {
                    mkdir($dirByVehicleId);
                }
                $file_path = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'drivers' . DIRECTORY_SEPARATOR . $driverId;
                $file_name = basename($image);
                $f         = $file_path;
                $file_path = $file_path . DIRECTORY_SEPARATOR . $file_name;
                file_put_contents(PUBLIC_PATH . '/testFile.txt', $f . ' ==== ' . $file_name);
                Yii::log("Image Path: \n\t Temp: " . $imagetmp . "\n\t Path: " . $f, CLogger::LEVEL_INFO, 'system.api.images');
                if (Vehicles::model()->img_resize($imagetmp, 1200, $f, $file_name))
                {
                    $path   = substr($file_path, strlen(PUBLIC_PATH));
                    $result = ['path' => $path];
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
	
	/*
	 * Depricated
	 */

    public function actionViewOld()
    {
        $this->pageTitle = 'Driver Details';
        $drvId           = Yii::app()->request->getParam('id');
        $drvCode         = Yii::app()->request->getParam('code');
        $view            = Yii::app()->request->getParam('view', 'view');

        if ($drvCode != '')
        {
            $drv   = Drivers::model()->getIdByCode($drvCode);
            $drvId = $drv['drv_id'];
        }


        $data     = Drivers::getDetailsById($drvId);
        $pastData = Drivers::model()->getPastTripList($drvId);
        $outputJs = Yii::app()->request->isAjaxRequest;
        $method   = "render" . ($outputJs ? "Partial" : "");
        $this->$method($view, array('data'     => $data,
            'pastData' => $pastData,
            'isAjax'   => $outputJs), false, $outputJs);
    }

    public function actionView()
    {
        $this->pageTitle = 'Driver Details';
        $drvId           = Yii::app()->request->getParam('id');
        $drvCode         = Yii::app()->request->getParam('code');
        $view            = Yii::app()->request->getParam('view', 'view');

        if ($drvCode != '')
        {
            $drv   = Drivers::model()->getIdByCode($drvCode);
            $drvId = $drv['drv_id'];
        }
        $models = Drivers::model()->findByPk($drvId);
        if ($models != null)
        {
            $drvstatModel = DriverStats::model()->getbyDriverId($drvId);
            $data         = Drivers::getDetailsById($drvId);            
            $drvStat      = DriverStats::model()->getLastLocation($drvId);
            $logedInCount = AppTokens::model()->checkDriverLastLogin($drvId);
        }
        $outputJs = Yii::app()->request->isAjaxRequest;
        $method   = "render" . ($outputJs ? "Partial" : "");
        $this->$method($view, array(
            'model'        => $models,
            'drvstatModel' => $drvstatModel,
            'data'         => $data,            
            'drvStat'      => $drvStat,
            'logedInCount' => $logedInCount,            
            'isAjax'       => $outputJs), false, $outputJs);
    }

    public function actionAddtransaction()
    {
        $this->pageTitle = 'Add Transaction';
        ////////////////

        $driverId = Yii::app()->request->getParam('id');
        $model    = new PaymentGateway();
        $model1   = clone $model;
        if (isset($_POST['PaymentGateway']))
        {
            $model->attributes = Yii::app()->request->getParam('PaymentGateway');
            //$model->apg_user_id	 = Yii::app()->user->getId();
            //$tripid				 = Yii::app()->request->getParam('PaymentGateway')['trip_id'];
            //if ($tripid == null)
            //{
            //	$tripid = $operatorId;
            //}

            $model->apg_ledger_id = ($_POST['PaymentGateway']['apg_ledger_id_1'] != '') ? $_POST['PaymentGateway']['apg_ledger_id_1'] : $_POST['PaymentGateway']['apg_ledger_id_2'];
            if ($model->apg_date != '')
            {
                $date            = DateTimeFormat::DatePickerToDate($model->apg_date);
                $time            = date('H:i:s');
                $model->apg_date = $date . " " . $time;
            }
            else
            {
                $model->apg_date = date('Y-m-d H:i:s');
            }
            if ($model->validate())
            {
                $data          = ['success' => true];
                $model->apg_id = 0;
                $bankRefId     = NULL;
                $refType       = NULL;
                $ledgerType    = Accounting::LI_DRIVER;
                $accType       = Accounting::AT_DRIVER;
                $paymentTypeId = PaymentType::model()->payentTypeFromLedger($model->apg_ledger_id);
                if (in_array($model->apg_ledger_id, Accounting::getOnlineLedgers(false)))
                {
                    $paymentGateway = PaymentGateway::model()->addAmountForOnlineLedger($model, $driverId, $paymentTypeId, $bankLedgerId, $accType, UserInfo::getInstance());
                    $bankRefId      = $paymentGateway->apg_id;
                    $refType        = Accounting::AT_ONLINEPAYMENT;
                    $remarks        = $model->apg_remarks;
                }
                $remarks = $model->apg_remarks;
                if ($_POST['PaymentGateway']['apg_ledger_id_1'] != '')
                {
                    $addDriverAmount = AccountTransactions::model()->addAmountGozoPaid($model, $driverId, $bankRefId, $remarks, $refType, $accType, $ledgerType, $date);
                }
                if ($_POST['PaymentGateway']['apg_ledger_id_2'] != '')
                {
                    $addDriverAmount = AccountTransactions::model()->addAmountGozoReceiver($model, $driverId, $bankRefId, $remarks, $refType, $accType, $ledgerType, $date, $driverId);
                }
            }
            else
            {
                $model1 = $model;
            }

            $this->redirect(array('viewtransaction', 'id' => $driverId, 'target' => '_blank'));
        }
        $model1->apg_trans_ref_id = $driverId;
        ////////////////
        $outputJs                 = Yii::app()->request->isAjaxRequest;
        $method                   = "render" . ($outputJs ? "Partial" : "");
        $this->$method('addtransaction', array(
            'model'  => $model1,
            'isAjax' => $outputJs
                ), false, $outputJs);
    }

    public function actionViewtransaction()
    {
        $this->pageTitle = 'Add Transaction';
        $driverId        = Yii::app()->request->getParam('id');
        $this->pageTitle = "Accounts Panel :: Driver Drill Down";
        //$record			 = Drivers::model()->getDrillDownInfo($driverId);

        $pageSize     = '500';
//		$date1					 = $dateFromDate;
//		$date2					 = $dateTodate;
        $recordSet    = AccountTransDetails::driverTransactionList($driverId);
        $driverList   = new CArrayDataProvider($recordSet, array('pagination' => array('pageSize' => $pageSize),));
        $driverModels = $driverList->getData();
        $driverAmount = AccountTransDetails::calBonusAmountByDriverId($driverId);

        $outputJs = Yii::app()->request->isAjaxRequest;
        $method   = "render" . ($outputJs ? "Partial" : "");
        $this->$method('viewtransaction', array(
            'driverModels' => $driverModels,
            'driverList'   => $driverList,
            'driverAmount' => $driverAmount,
            'record'       => $record,
            'model'        => $model1,
            'agtId'        => $driverId,
            'isAjax'       => $outputJs
                ), false, $outputJs);
    }

    public function actionDocapprovallist()
	{
		$this->pageTitle = "Driver Pending Doc Approval";
		
		$model			 = new Document();
		$model->doc_type = 5;
		
		$arr			 = [];
		$arr['doc_type'] = 5;
		
		$contactId		 = Yii::app()->getRequest()->getParam('ctt_id');
		if ($contactId != "")
		{
			$driver			 = Drivers::model()->findByDriverContactID($contactId);
			$model->drv_id	 = $driver->drv_id;
			$arr['drv_id']	 = $driver->drv_id;
		}
		if (isset($_REQUEST['Document']))
		{
			$arr				 = Yii::app()->request->getParam('Document');
			$model->doc_type	 = $arr['doc_type'];
			$model->contactname	 = $arr['contactname'];
			$model->drv_id		 = $arr['drv_id'];
		}
		$driverIds								 = BookingCab::getDriverList();
		$arr['driverIds']						 = $driverIds != null ? rtrim($driverIds, ",") : 0;
		$dataProvider							 = $model->getUnapprovedDriver($arr);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$outputJs								 = Yii::app()->request->isAjaxRequest;
		$method									 = "render" . ( $outputJs ? "Partial" : "");
		$this->render('docapprovallist', array('model' => $model, 'dataProvider' => $dataProvider), false, $outputJs);
	}

	public function actionShowdocimg()
    {
        $drdid    = Yii::app()->request->getParam('drdid');
        $dmodel   = DriverDocs::model()->resetScope()->findByPk($drdid);
        $model    = Drivers:: model()->resetScope()->findByPk($dmodel->drd_drv_id);
        $outputJs = Yii::app()->request->isAjaxRequest;
        $method   = "render" . ( $outputJs ? "Partial" : "");
        $this->$method('picshow', ['dmodel' => $dmodel, 'model' => $model], false, $outputJs);
    }

    public function actionApprovedocimg()
    {
        $btntype    = Yii::app()->request->getParam('btntype');
        $drdDocs    = Yii::app()->request->getParam('DriverDocs');
        $drdid      = $drdDocs['drd_id'];
        $dmodel     = DriverDocs::model()->resetScope()->findByPk($drdid);
        $oldDocData = $dmodel->attributes;
        $userInfo   = UserInfo::getInstance();
        $fileType   = '';
        $fileType1  = [];
        if ($dmodel)
        {
            $drvModel   = Drivers::model()->resetScope()->findByPk($dmodel->drd_drv_id);
            $oldDrvData = $drvModel->attributes;
            $drv        = Yii::app()->request->getParam('Drivers');
            if ($btntype == 'approve')
            {
                $dmodel->drd_status = 1;
                $dmodel->scenario   = 'approve';
                $action             = "approved";
            }
            else if ($btntype == 'problem')
            {
                $dmodel->drd_status = 2;
                $dmodel->scenario   = 'reject';
                $action             = "disapproved";
            }
            else
            {
                throw new CHttpException(400, 'Invalid data');
            }
            $drvChange = 0;
            switch ($dmodel->drd_type)
            {
                case 1:
                    $event_id = ($btntype == 'approve') ? DriversLog::DRIVER_VOTER_APPROVE : DriversLog::DRIVER_VOTER_REJECT;
                    $fileType = "#voter";
                    if ($drv['drv_voter_id'] != '' && ($drvModel->drv_voter_id != $drv['drv_voter_id']))
                    {
                        $drvChange++;
                        $drvModel->drv_voter_id = $drv['drv_voter_id'];
                    }
                    break;
                case 2:
                    $event_id = ($btntype == 'approve') ? DriversLog::DRIVER_PAN_APPROVE : DriversLog::DRIVER_PAN_REJECT;
                    $fileType = "#pan";
                    if ($drv['drv_pan_no'] != '' && ($drvModel->drv_pan_no != $drv['drv_pan_no']))
                    {
                        $drvChange++;
                        $drvModel->drv_pan_no = $drv['drv_pan_no'];
                    }
                    break;
                case 3:
                    $event_id = ($btntype == 'approve') ? DriversLog::DRIVER_AADHAR_APPROVE : DriversLog::DRIVER_AADHAR_REJECT;
                    $fileType = "#aadhaar";

                    if ($drv['drv_aadhaar_no'] != '' && ($drvModel->drv_aadhaar_no != $drv['drv_aadhaar_no']))
                    {
                        $drvChange++;
                        $drvModel->drv_aadhaar_no = $drv['drv_aadhaar_no'];
                    }
                    break;
                case 4:
                    $event_id = ($btntype == 'approve') ? DriversLog::DRIVER_DL_APPROVE : DriversLog::DRIVER_DL_REJECT;
                    $fileType = "#driverLicenceFront";
                    if ($drv['drv_lic_exp_date'] != '' && $drvModel->drv_lic_exp_date != DateTimeFormat::DatePickerToDate($drv['drv_lic_exp_date']))
                    {
                        $drvChange++;
                        $fileType1[]                = $fileType . 'ExpiryDate';
                        $drvModel->drv_lic_exp_date = DateTimeFormat::DatePickerToDate($drv['drv_lic_exp_date']);
                    }
                    if ($drv['drv_lic_number'] != '' && ($drvModel->drv_lic_number != $drv['drv_lic_number']))
                    {
                        $drvChange++;
                        $fileType1[]              = $fileType;
                        $drvModel->drv_lic_number = $drv['drv_lic_number'];
                    }
                    if (sizeof($fileType1) > 0)
                    {
                        $fileType = implode(' and ', $fileType1);
                    }
                    break;
                case 5:
                    $event_id = ($btntype == 'approve') ? DriversLog::DRIVER_PC_APPROVE : DriversLog::DRIVER_PC_REJECT;
                    $fileType = "#policeVerification";
                    break;
                case 6:
                    $event_id = ($btntype == 'approve') ? DriversLog::DRIVER_DL_BACK_APPROVE : DriversLog::DRIVER_DL_BACK_APPROVE;
                    $fileType = "#driverLicenceBack";
                    if ($drv['drv_lic_exp_date'] != '' && $drvModel->drv_lic_exp_date != DateTimeFormat::DatePickerToDate($drv['drv_lic_exp_date']))
                    {
                        $drvChange++;
                        $fileType1[]                = $fileType . 'ExpiryDate';
                        $drvModel->drv_lic_exp_date = DateTimeFormat::DatePickerToDate($drv['drv_lic_exp_date']);
                    }
                    if ($drv['drv_lic_number'] != '' && ($drvModel->drv_lic_number != $drv['drv_lic_number']))
                    {
                        $drvChange++;
                        $fileType1[]              = $fileType;
                        $drvModel->drv_lic_number = $drv['drv_lic_number'];
                    }
                    if (sizeof($fileType1) > 0)
                    {
                        $fileType = implode(' and ', $fileType1);
                    }
                    break;
            }

            if ($drvChange > 0)
            {
                if ($drvModel->save())
                {
                    $newDrvData          = $drvModel->attributes;
                    $descLog             = "Modified $fileType of the driver on $action";
                    $dataSet             = $model->getApiMappingByDriver($oldDrvData);
                    $oldDrvData          = array_merge($oldDrvData, $dataSet);
                    $getOldDifferenceDrv = array_diff_assoc($oldDrvData, $newDrvData);
                    $dataSetnew          = $model->getApiMappingByDriver($newDrvData);
                    $newDrvData          = array_merge($newDrvData, $dataSetnew);
                    $getNewDifferenceDrv = array_diff_assoc($newDrvData, $oldDrvData);
                    $change              = $this->getModificationMSG($getOldDifferenceDrv, false);
                    $changeNew           = $this->getModificationMSG($getNewDifferenceDrv, false);
                    if ($change != '')
                    {
                        $changesForDrvLog = "<br> Old Values: " . $change;
                        $descLog          .= $changesForDrvLog;
                    }
                    else if ($changeNew != '')
                    {
                        $changesForDrvLog = "<br> New Values: " . $changeNew;
                        $descLog          .= $changesForDrvLog;
                    }

                    DriversLog::model()->createLog($drvModel->drv_id, $descLog, $userInfo, $event_id, false, false);
                    $success = true;
                }
                else
                {
                    $success = false;
                }
            }
            $remarks                = trim($drdDocs['drd_remarks']);
            $newDocData             = $dmodel->attributes;
            $dmodel->drd_remarks    = $remarks;
            $dmodel->drd_appoved_at = new CDbExpression('NOW()');
            $dmodel->drd_approve_by = $user_id;
            $result1                = CActiveForm::validate($dmodel);
//$return = ['success' => false];
            $success                = false;
            if ($result1 == '[]')
            {
                $transaction = Yii::app()->db->beginTransaction();
                try
                {
                    $success     = $dmodel->save();
                    $remarkAdded = ($remarks != '') ? "($remarks)" : '';
                    $vhc_id      = $dmodel->drd_drv_id;

                    $desc                = "The document for $fileType of the car is $action $remarkAdded";
                    $getOldDifferenceDoc = array_diff_assoc($oldDocData, $newDocData);
                    $changes             = $this->getModificationMSG($getOldDifferenceDoc, false);
                    if ($changes != '')
                    {
                        $changesForDocLog = "<br> Old Values: " . $changes;
                        $desc             .= $changesForDocLog;
                    }

                    DriversLog::model()->createLog($vhc_id, $desc, $userInfo, $event_id, false, false);
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
        $outputJs = Yii::app()->request->isAjaxRequest;
        $method   = "render" . ( $outputJs ? "Partial" : "");
        $this->$method('picshow', ['dmodel' => $dmodel], false, $outputJs);
// exit;
//$this->render('picshow', ['dmodel' => $dmodel], false, $outputJs);
    }

    public function actionAccountList()
    {
        $ledgerId        = Yii::app()->request->getParam("ledgerId");
        $this->pageTitle = "Account Transaction List";
        $qry             = [];
        $date1           = '';
        $date2           = '';
        $model           = new AccountTransDetails('search');
        if (isset($_REQUEST['AccountTransDetails']))
        {
            $arr   = Yii::app()->request->getParam('AccountTransDetails');
            $date1 = DateTimeFormat::DatePickerToDate($arr['trans_date1']);
            $date2 = DateTimeFormat::DatePickerToDate($arr['trans_date2']);
            $qry   = [];
            foreach ($arr as $k => $v)
            {
                $model->$k = $v;
            }
        }
        else
        {
            $date1 = date('Y-m-d', strtotime("-90 days"));
            $date2 = date('Y-m-d');
        }
        $model->trans_date1 = DateTimeFormat::DateToLocale($date1);
        $model->trans_date2 = DateTimeFormat::DateToLocale($date2);
        $model->resetScope();
        $dataProvider       = AccountTransDetails::getdriverAccountTransactionsList($date1, $date2, $ledgerId);
        $this->render('accountlist', array('dataProvider' => $dataProvider, 'model' => $model));
    }

    public function actionSociallist()
    {
        $pagetitle       = "Social Link Listing";
        $this->pageTitle = $pagetitle;
        $model           = new Users();
        $dataProvider    = NULL;
        $request         = Yii::app()->request;
        if ($request->getParam('Users'))
        {
            $model->search = $request->getParam('Users')['search'];
            $model->email  = $request->getParam('Users')['email'];
            if ($model->search != NULL && $model->email != NULL)
            {
                $modelUser = Users::model()->getUserIdBySocialEmail($model->email);
                if ($modelUser != NULL)
                {
                    $dataProvider = $model->getSocialListDrivers($modelUser);
                }
            }
            else if ($model->search != NULL)
            {
                $dataProvider = $model->getSocialListDrivers();
            }
            else if ($model->email != NULL)
            {
                $modelUser = Users::model()->getUserIdBySocialEmail($model->email);
                if ($modelUser != NULL)
                {
                    $dataProvider = $model->getSocialListDrivers($modelUser);
                }
            }
            else
            {
                $dataProvider = $model->getSocialListDrivers();
            }
        }
        else
        {
            $dataProvider = $model->getSocialListDrivers();
        }
        $outputJs = Yii::app()->request->isAjaxRequest;
        $method   = "render" . ($outputJs ? "Partial" : "");
        $this->$method('socialllinklist', array('model' => $model, 'dataProvider' => $dataProvider), null, $outputJs);
    }

    public function actionUnlinkSocialAccount()
    {
        $model           = new Drivers();
        $pagetitle       = "Social Link Listing";
        $this->pageTitle = $pagetitle;
        $drvid           = Yii::app()->request->getParam('drvid');
        $from            = Yii::app()->request->getParam('from');
        $type            = Yii::app()->request->getParam('type');
        $model           = Drivers::model()->findByPk($drvid);

        if ($model != NULL)
        {
            $userId             = $model->drv_user_id;
            $model->drv_user_id = NULL;
            $model->save();
            $userInfo           = UserInfo::getInstance();
            Users::model()->logoutByUserId($userId);
            DriversLog::model()->createLog($drvid, "Driver social account removed from drivers having $drvid", $userInfo, DriversLog::DRIVER_SOCIAL_UNLINK, false, false);
        }
        Yii::app()->user->setFlash('success', "Social account unlink successfully");
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
            $this->redirect(array('driver/sociallist'));
        }
    }

    public function actionDuplicateDriver()
    {
        $pagetitle       = "Duplicate Driver";
        $this->pageTitle = $pagetitle;
        $model           = new Contact();
        $cttId           = NULL;
        $drvId           = "";
        $type            = "drivers";
        if (isset($_REQUEST['Contact']))
        {
            $arr                   = array_filter(Yii::app()->request->getParam('Contact'));
            $model->attributes     = $arr;
            $model->ctt_aadhaar_no = $arr['ctt_aadhaar_no'];
            $model->phone_no       = $arr['phone_no'];
            $model->email_address  = $arr['email_address'];
            $model->ctt_pan_no     = $arr['ctt_pan_no'];
            $model->ctt_voter_no   = $arr['ctt_voter_no'];
            $model->ctt_license_no = $arr['ctt_license_no'];
        }
        $dataProvider = $model->getDuplicateContact($arr, $cttId, $type, $drvId);
        $outputJs     = Yii::app()->request->isAjaxRequest;
        $method       = "render" . ($outputJs ? "Partial" : "");
        $this->$method('duplicatedriver', array('model' => $model, 'dataProvider' => $dataProvider), null, $outputJs);
    }

    public function actionMergeDuplicateDriver()
    {
        $pagetitle       = "Merge Drivers";
        $this->pageTitle = $pagetitle;
        $cttId           = Yii::app()->request->getParam('ctt_id');
        $drvId           = Yii::app()->request->getParam('drv_id');
        $phone           = Yii::app()->request->getParam('phone_no');
        $cttAadhaarNo    = Yii::app()->request->getParam('ctt_aadhaar_no');
        $cttPanNo        = Yii::app()->request->getParam('ctt_pan_no');
        $cttVoterNo      = Yii::app()->request->getParam('ctt_voter_no');
        $cttLicenseNo    = Yii::app()->request->getParam('ctt_license_no');
        $emlAddress      = Yii::app()->request->getParam('email_address');
        $model           = Contact::model()->findByPk($cttId);
        $emailModel      = ContactEmail::model()->findByContactID($cttId);
        $phoneModel      = ContactPhone::model()->findByContactID($cttId);
        $arr             = [];
        $type            = "drivers";
        if (isset($_REQUEST['Contact']))
        {
            $arr                   = array_filter(Yii::app()->request->getParam('Contact'));
            $model->attributes     = $arr;
            $model->phone_no       = $arr['phone_no'];
            $model->email_address  = $arr['email_address'];
            $model->ctt_aadhaar_no = $arr['ctt_aadhaar_no'];
            $model->ctt_pan_no     = $arr['ctt_pan_no'];
            $model->ctt_voter_no   = $arr['ctt_voter_no'];
            $model->ctt_license_no = $arr['ctt_license_no'];
        }
        else
        {
            $arr['phone_no']       = $phone == NULL ? "" : $phone;
            $arr['email_address']  = $emlAddress == NULL ? "" : $emlAddress;
            $arr['ctt_aadhaar_no'] = $cttAadhaarNo == NULL ? "" : $cttAadhaarNo;
            $arr['ctt_pan_no']     = $cttPanNo == NULL ? "" : $cttPanNo;
            $arr['ctt_voter_no']   = $cttVoterNo == NULL ? "" : $cttVoterNo;
            $arr['ctt_license_no'] = $cttLicenseNo == NULL ? "" : $cttLicenseNo;
            $model->email_address  = $arr['email_address'];
            $model->phone_no       = $arr['phone_no'];
            $model->ctt_aadhaar_no = $arr['ctt_aadhaar_no'];
            $model->ctt_pan_no     = $arr['ctt_pan_no'];
            $model->ctt_voter_no   = $arr['ctt_voter_no'];
            $model->ctt_license_no = $arr['ctt_license_no'];
        }
        $dataProvider = $model->getDuplicateContact($arr, $cttId, $type, $drvId);
        $outputJs     = Yii::app()->request->isAjaxRequest;
        $method       = "render" . ($outputJs ? "Partial" : "");
        $this->$method('duplicatedriver', array('model' => $model, 'dataProvider' => $dataProvider, 'cttid' => $cttId, 'drv_id' => $drvId), null, $outputJs);
    }

    public function actionMergeDriver()
    {
        $this->pageTitle = "Merge Driver";
        $drvId           = Yii::app()->request->getParam('drvid');
        $mdrvId          = Yii::app()->request->getParam('mdrvid');
        $mergeDriver     = new Drivers();
        $oldData         = false;
        $success         = false;
        $userInfo        = UserInfo::getInstance();
        if ($mdrvId == NULL)
        {
            Yii::app()->user->setFlash('notice', "Please provide merged driver id");
            $this->redirect(array('driver/list'));
            exit();
        }
        $mgrArr = explode(",", $mdrvId);
        if (in_array($drvId, $mgrArr))
        {
            Yii::app()->user->setFlash('notice', "You cannot merged with same driver id");
            $this->redirect(array('driver/list'));
            exit();
        }
        $modelMerge = Drivers::model()->getAllDriversByIds($mdrvId);
        $contactArr = array();
        foreach ($modelMerge as $contactId)
        {
            $contactArr[] = $contactId["drv_contact_id"];
        }

        $mgrcttId = implode(',', $contactArr);
        if ($drvId != "")
        {
            $model = Drivers::model()->findByPk($drvId);
            $cttId = $model->drv_contact_id;
            if (!$model)
            {
                throw new CHttpException(400, 'Invalid data');
            }
            $oldData         = $model->attributes;
            $dataSet         = $model->getApiMappingByDriver($oldData);
            $oldData         = array_merge($oldData, $dataSet);
            $this->pageTitle = "Merge Driver";
            $model->scenario = 'updateAdmin';
        }
        if (isset($_REQUEST['Drivers']))
        {
            $drvArr          = Yii::app()->request->getParam('Drivers');
            $newPassOnUpdate = "";
            if ($drvArr['drv_id'] > 0)
            {
                $model = Drivers::model()->findById($drvArr['drv_id']);
            }
            if ($drvArr['drv_is_uber_approved'] == '0')
            {
                $model->drv_is_uber_approved = (int) $drvArr['drv_is_uber_approved'];
            }
            $model->attributes = array_filter($drvArr);
            $newData           = $model->attributes;
            $exist             = $model->getExistingDetails();
            if ($exist['drv_id'])
            {
                $drvModel = Drivers::model()->findByPk($exist['drv_id']);
                if ($drvModel)
                {
                    $model1            = $model;
                    $model             = $drvModel;
                    $model->scenario   = 'update';
                    $arrAttr           = $model1->attributes;
                    unset($arrAttr['drv_id']);
                    $model->attributes = $arrAttr;
                }
            }
            $drvLog = 0;
            $result = CActiveForm::validate($model, null, false);
            if ($result == '[]')
            {
                if (!$model->isNewRecord)
                {
                    $drvLog         = 1;
                    $model->drv_log = $model->addLog($oldData, $newData);
                }
                $model->drv_bg_checked  = ($_POST['Drivers']['drv_bg_checked'][0] != 1) ? 0 : 1;
                $model->drv_is_attached = ($_POST['Drivers']['drv_is_attached'][0] != 1) ? 0 : 1;
                if ($_POST['Drivers']['drv_approved'][0] != 1)
                {
                    $model->drv_approved = 3;
                }
                else
                {
                    $model->drv_approved    = 1;
                    $model->drv_approved_by = $userInfo->getUserId();
                }
                $model->drv_dob_date = $_POST['Drivers']['drv_dob_date'];
                $newData             = $model->attributes;
                $success             = $model->save();
                $updateFile          = [];
                if ($success)
                {
                    $codeArr = Filter::getCodeById($model->drv_id, "driver");
                    if ($codeArr['success'] == 1)
                    {
                        $newData = $model->attributes;
                    }
                    if ($drvArr['drv_vendor_id1'] > 0)
                    {
                        $data   = ['vendor' => $drvArr['drv_vendor_id1'], 'driver' => $model->drv_id];
                        $linked = VendorDriver::model()->checkAndSave($data);
                    }

                    if (count($mgrArr) > 0)
                    {
                        for ($k = 0; $k < count($mgrArr); $k++)
                        {
                            $trans = Yii::app()->db->beginTransaction();
                            try
                            {
                                $vendors = VendorDriver::model()->getVendorListbyDriverid($mgrArr[$k]);
                                $this->updateAccountDetails($mgrArr[$k], $drvId);
                                if (sizeof($vendors) > 0)
                                {
                                    foreach ($vendors as $ven)
                                    {
                                        $arr = ['driver' => $model->drv_id, 'vendor' => $ven['vdrv_vnd_id']];
                                        VendorDriver::model()->checkAndSave($arr);
                                    }
                                }
                                $newDriver = $model->drv_id;
                                $oldDriver = $mgrArr[$k];
                                Drivers::model()->replaceDriverDetailsFromBooking($oldDriver, $newDriver);
                                if (Drivers::model()->deactivatebyId($mgrArr[$k]))
                                {
                                    $remark = $model->drv_log;
                                    $newLog = ['drv_id_merge' => 'Driver id ' . $mgrArr[$k] . ' merged and deactivated'];
                                    $dt     = new CDbExpression('NOW()');
                                    $user   = $userInfo->getUserId();
                                    if (is_string($remark))
                                    {
                                        $newcomm = CJSON::decode($remark);
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
                                    array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $newLog));
                                    $log            = CJSON::encode($newcomm);
                                    $model->drv_log = $log;
                                }
                                $merged = DriverMerged::model()->addMergedData($newDriver, $oldDriver, $user);
                                DriversLog::model()->createLog($model->drv_id, "Driver Merge :  $mdrvId  is merged with $model->drv_id", UserInfo::getInstance(), DriversLog::Driver_MERGE, false, false);
                                $trans->commit();
                            }
                            catch (Exception $e)
                            {
                                $trans->rollback();
                            }
                        }
                    }
                    $success = $model->save();
                }
                if ($success)
                {
                    Drivers::model()->updateDriverMerge($mdrvId, $model->drv_id);
                    if (!in_array($cttId, $contactArr))
                    {
                        Contact::model()->updateContactDetails($mgrcttId);
                    }
                    $return['success'] = $success;
                    if ($drvId > 0)
                    {
                        $getOldDifference = array_diff_assoc($oldData, $newData);
                        $changesForLog    = "<br> Old Values: " . $this->getModificationMSG($getOldDifference, false);
                        $desc             = "Driver modified | ";
                        $desc             .= $changesForLog;
                        DriversLog::model()->createLog($model->drv_id, $desc, $userInfo, DriversLog::DRIVER_MODIFIED, false, false);
                    }
                }
            }
            else
            {
                $return['success'] = false;
                $return['error']   = CJSON::decode($result);
            }

            if ($return['success'])
            {
                Yii::app()->user->setFlash('success', "Driver merged successfully");
                $this->redirect(array('driver/list'));
                exit();
            }
            else
            {
                Yii::app()->user->setFlash('notice', "Due to some problem Driver  merge cannot be completed");
                $this->redirect(array('driver/list'));
                exit();
            }
        }
        $this->render('addmerge', [
            'model'      => $model,
            'modelMerge' => $modelMerge,
            'isNew'      => $model->isNewRecord,
            'errors'     => $model->getErrors()
        ]);
    }

    public function updateAccountDetails($mgrDrvId, $drvdid)
    {
        $drRefId      = $mgrDrvId;
        $driverAmount = AccountTransDetails::model()->calBonusAmountByDriverId($mgrDrvId, '', '', '');
        $amount       = $driverAmount['bonus_amount'] != NULL ? $driverAmount['bonus_amount'] : 0;
        $amount1      = $amount;
        if ($amount != 0)
        {
            $crRefId       = $drvdid;
            $crRemarks     = "Adjusting accounts due to merging of driver as $mgrDrvId is merged with $drvdid";
            $drRemarks     = "Adjusting accounts due to merging of driver as $drvdid is merged with $mgrDrvId";
            $accTransModel = new AccountTransactions();
            if ($amount < 0)
            {
                $accTransModel->act_amount = -1 * $amount1;
            }
            if ($amount > 0)
            {
                $accTransModel->act_amount = $amount1;
            }
            $drLedgerId                 = Accounting::LI_DRIVER;
            $drAcctType                 = Accounting::AT_DRIVER;
            $crLedgerID                 = Accounting::LI_DRIVER;
            $crAccType                  = Accounting::AT_DRIVER;
            $accTransModel->act_amount  = $amount;
            $accTransModel->act_date    = new CDbExpression('NOW()');
            $accTransModel->act_type    = $crAccType;
            $accTransModel->act_ref_id  = $crRefId;
            $accTransModel->act_remarks = $crRemarks;
            $accTransModel->mergeAccountBalance($drLedgerId, $crLedgerID, $drRefId, $crRefId, $drAcctType, $crAccType, $drRemarks, $crRemarks, UserInfo::getInstance(), $amount);
        }
    }

    public function addContactNew()
	{
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
		$stub		 = new Stub\common\Person();
		$obj		 = $jsonMapper->map($reciveData, $stub);

		/** @var Stub\common\Person $obj */
		$contactModel	 = $obj->init();
		$returnSet		 = Drivers::addByContact($contactModel);

		if ($returnSet->getStatus())
		{
			$vndId		 = $reciveData->vndId;
			$response	 = $returnSet->getData();
			$data		 = ['vendor' => $vndId, 'driver' => $response->id];
			VendorDriver::model()->checkAndSave($data);
		}

		skipAll:
		return $returnSet;
	}

	public function actionSyncfail()
    {
        $this->pageTitle = "Process Data List";
        /* @var $model DrvUnsyncLog */
        $model           = new DrvUnsyncLog();
        $pageSize        = Yii::app()->params['listPerPage'];
        $request         = Yii::app()->request;
        if (!empty($request->getParam('bookingId')))
        {
            $search = $request->getParam('bookingId');
        }
        if ($request->getParam('DrvUnsyncLog'))
        {
            $arr           = $request->getParam('DrvUnsyncLog');
            $searchDrvName = $arr['searchDrvName'];
            $search        = $arr['search'];
        }
        $dataProvider = $model->getList($search, $searchDrvName);
        $dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
        $this->render('drvSyncLog', array('model' => $model, 'dataProvider' => $dataProvider));
    }

    public function actionCheckProceed()
    {
        $success = false;
        $id      = Yii::app()->request->getParam('dulId');
        $status  = Yii::app()->request->getParam('status');
        if ($id != '')
        {
            $model = DrvUnsyncLog::model()->findByPk($id);
            if (count($model) > 0)
            {
                $model->dul_status = 1;
                $model->update();
                $success           = true;
            }
        }
        $data = $success;
        if (Yii::app()->request->isAjaxRequest)
        {
            echo $data;
            Yii::app()->end();
        }
        $this->redirect(array('syncFail'));
    }

    public function actionErrorView()
    {

        $id       = Yii::app()->request->getParam('id');
        $viewType = Yii::app()->request->getParam('view');
        $model    = new DrvUnsyncLog();
        $data     = DrvUnsyncLog::model()->findByPk($id);
        $this->renderPartial('errorView', array('model' => $model, 'data' => $data), false, true);
    }

    public function actionProfile()
    {

        $this->pageTitle = 'Driver Details';
        $drvId           = Yii::app()->request->getParam('id');
        $view            = Yii::app()->request->getParam('view', 'profile');

        $model    = Drivers::model()->resetScope()->findByPk($drvId);
        $outputJs = Yii::app()->request->isAjaxRequest;
        $method   = "render" . ($outputJs ? "Partial" : "");
        $this->$method($view, array('data'   => $data,
            'model'  => $model,
            'isAjax' => $outputJs
                ), false, $outputJs);
    }

    public function actionHistory()
    {
        $drvId    = Yii::app()->request->getParam('drvId');
        $viewType = Yii::app()->request->getParam('view', 'history');
        $data     = Drivers::getDetailsById($drvId);
        $model    = Drivers::model()->resetScope()->findByPk($drvId);
//Partial
        $this->render('history', array(
            'data'         => $data,
            'model'        => $model,
            'dataProvider' => $dataProvider), false, true);
    }

    public function actionTripDetails()
    {
        $drvId    = Yii::app()->request->getParam('drvId');
        $viewType = Yii::app()->request->getParam('view', 'history');
        $model    = Drivers::model()->resetScope()->findByPk($drvId);
        $pastData = Drivers::model()->getPastTripList($drvId);

        $this->render('tripDetails', array(
            'pastData'     => $pastData,
            'model'        => $model,
            'dataProvider' => $dataProvider), false, true);
    }

    public function actionUpdateDetails()
    {
        $drv_id    = Yii::app()->request->getParam('drv_id');
        $returnSet = new ReturnSet();
        if ($drv_id > 0)
        {
            $returnSet = Drivers::model()->updateDetails($drv_id);
            echo json_encode(['success' => $returnSet->getStatus(), 'message' => $returnSet->getMessage()]);
        }
        else
        {
            echo json_encode(['success' => false, 'message' => "Please provide your driver Id "]);
        }
        Yii::app()->end();
    }

    public function actionDeviceHistory()
    {
        $drv_id   = Yii::app()->request->getParam('drvId');
        $viewType = Yii::app()->request->getParam('view');
        $model    = new AppTokens();
        $request  = Yii::app()->request;
        if ($request->getParam('AppTokens'))
        {
            $arr1                   = $request->getParam('AppTokens');
            $date1                  = $model->apt_last_login1 = $arr1['apt_last_login1'] != null ? $arr1['apt_last_login1'] : date("Y-m-d", strtotime("-1 month"));
            $date2                  = $model->apt_last_login2 = $arr1['apt_last_login2'] != null ? $arr1['apt_last_login2'] : date("Y-m-d");
        }
        else
        {
            $date1                  = $model->apt_last_login1 = date("Y-m-d", strtotime("-1 month"));
            $date2                  = $model->apt_last_login2 = date("Y-m-d");
        }
        $date1                                 = $date1 . " 00:00:00";
        $date2                                 = $date2 . " 23:59:59";
        $dataProvider                          = AppTokens::model()->getByDriverId($drv_id, $date1, $date2);
        $dataProvider->getPagination()->params = array_filter($_GET + $_POST);
        $dataProvider->getSort()->params       = array_filter($_GET + $_POST);
        $outputJs                              = Yii::app()->request->isAjaxRequest;
        $method                                = "render" . (($outputJs) ? "Partial" : "");

        //Partial
        $this->$method('deviceHistory', ['dataProvider' => $dataProvider,
            'model'        => $model], false, true);
    }
	
	public function actionPastTripDetails()
	{
		$drvId			 = Yii::app()->request->getParam('drvId');
		$mycall			 = Yii::app()->request->getParam('mycall');
		$pastData		 = Drivers::model()->getPastTripList($drvId);
        $pastTrip        = new CArrayDataProvider($pastData, array('pagination' => array('pageSize' => 25),));
		$this->renderPartial("drvTripDetails", ['pastData' => $pastTrip,'mycall'=>$mycall], false, true);	
	}

	public function actionVndDetails()
	{
		$drvId			 = Yii::app()->request->getParam('drvId');		
		$data            = Drivers::getDetailsById($drvId);
		$this->renderPartial("vndDetails", ['data' => $data], false, true);	
	}

	public function actionScqDetails()
	{
		$drvId			 = Yii::app()->request->getParam('drvId');		
		$mycall			 = Yii::app()->request->getParam('mycall');
		$cbrDetails		 = ServiceCallQueue::model()->getCBRDetailbyId($drvId, "Driver");
		$this->renderPartial("scqDetails", ['cbrdetails' => $cbrDetails,'mycall'=>$mycall], false, true);	
	}

	public function actionDocumentDetails()
	{
		$drvId			 = Yii::app()->request->getParam('drvId');		
		#$models			 = Drivers::model()->findByPk($drvId);
		$contactId		 = ContactProfile::getByDrvId($drvId);
		$docById         = Document::model()->getAllDocsbyContact($contactId, 'driver');
		$this->renderPartial("documentDetails", ['docpath'=> $docById], false, true);	
	}

	public function actionGetCoinDetails()
	{
		$drvId			 = Yii::app()->request->getParam('drvId');		
		$dataProvider    = DriverCoins::getCoinList($drvId);		
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->renderPartial('coinDetails', ['dataProvider' => $dataProvider,'drvId'=>$drvId], false, true);	
	}

	public function actionAddremark()
	{
		$drv_id	 = Yii::app()->request->getParam('drv_id');
		$reason	 = Yii::app()->request->getParam('drv_remark');
		$model	 = Drivers::model()->findByPk($drv_id);
		$success = false;
		if (isset($_POST['drv_id']) && $_POST['drv_id'] == $model->drv_id)
		{
			if (isset($_POST['drv_remark']) && trim($reason) != '')
			{
				if ($model->update())
				{
					$event_id	 = DriversLog::DRIVER_REMARK_ADDED;
					$desc		 = "Remarks : " . trim($reason);
					DriversLog::model()->createLog($model->drv_id, $desc, UserInfo::getInstance(), $event_id, false, false);
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

}
