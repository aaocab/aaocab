<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class IndexController extends Controller
{

    public $layout = 'admin1';
    public $email_receipient, $pageTitle1, $pageDesc;

    //public $parner_salt = 'CPART123SALT';
    public function filters()
    {
        return array(
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
                'actions' => array('is_channel_partner', 'register_channel_partner'),
                'users'   => array('@'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('recharge_channel_partner', 'is_channel_partner', 'register_channel_partner', 'otpverify_link_agent', 'ajaxverify', 'index', 'iew', 'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
                'users'   => array('*'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array(),
                'users'   => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function restEvents()
    {
        $this->onRest('req.cors.access.control.allow.methods', function() {
            return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
        });

        $this->onRest('post.filter.req.auth.user', function($validation) {
            $pos = false;
            $arr = $this->getURIAndHTTPVerb();

            $ri = array('/is_channel_partner', '/register_channel_partner', '/recharge_channel_partner', '/otpverify_link_agent', '/generateOtpforLinking', '/verifyAgentOtp', '/channelPartnerStatus', '/saveAgentAgreement');

            foreach ($ri as $value)
            {
                if (strpos($arr[0], $value))
                {
                    $pos = true;
                }
            }
            return $validation ? $validation : ($pos != false);
        });
        /*
         * Check channel partner exist or not...
         */

        $this->onRest('req.post.is_channel_partner.render', function() {

            $arrUserData           = array();
            $user_data1            = Yii::app()->request->getParam('data');
            $data                  = json_decode($user_data1);
            $userId                = $data->user_id;
            $env                   = $data->env;
            $email                 = $data->email;
            $phone                 = $data->phone;
            $type                  = $data->type;
            $entity_id             = $data->entity_id;
            $channelPartnerAllData = AgentUsers::model()->getChannelPartners($userId);
            $channelPartnerAllData = $channelPartnerAllData;
            Logger::create("test =>" . $userId . '+' . $env . '+' . $email . '+' . $phone . '+' . $type . '+' . $entity_id, CLogger::LEVEL_TRACE);

            $count_user = count($channelPartnerAllData);
            $url        = '';
            if (!empty($channelPartnerAllData))
            {
                $success                       = true;
                $messages                      = "Channel partner details";
                $user_details                  = Users::model()->findByPk($userId);
                //$checksum_string	           = Agents :: checksumChanelPartner($userId,$user_details['usr_password'],$env,$salt);
                $checksumstring                = $userId . $user_details['usr_password'] . $env;
                $checksum                      = Users::model()->encrypt($checksumstring);
                $success                       = true;
                $channelPartner['checksum']    = $checksum;
                $channelPartner['partnerData'] = $channelPartnerAllData;
                //exit;
                $flag                          = 1;
            }
            else
            {
                //check email exist or not
                $agentUserEmail = Agents::model()->findByEmailInAll($email);
                $agentUserPhone = Agents::model()->findByPhoneInAll($phone);
                //checkUserEmail

                if (count($agentUserEmail->attributes) > 0)
                {
                    $agent_name = $agentUserEmail->attributes['agt_fname'] . '' . $agentUserEmail->attributes['agt_lname'];
                    $agent_id   = $agentUserEmail->attributes['agt_id'];
                    $success    = false;
                    $messages   = "Your mail id ( $email )already exists as a different agent $agent_name do you want to link your account with that agent";
                    $flag       = 1; // Email assotiated with other agent
                }
                else if (count($agentUserPhone) > 0)
                {
                    //print_r($agentUserPhone['agt_fname']);
                    $agent_name = $agentUserPhone['agt_fname'] . ' ' . $agentUserPhone['agt_lname'];
                    $agent_id   = $agentUserPhone['agt_id'];
                    $success    = false;
                    $messages   = "Your phone number ( $phone )already exists as a different agent $agent_name. Do you want to link your account with that agent?";
                    $flag       = 1; // Email assotiated with other agent
                }
                else
                {
                    if ($type == 'vendor')
                    {
                        $vendor_details = Vendors::model()->findByPk($entity_id);
                        $contact_id     = $vendor_details[attributes]['vnd_contact_id'];
                        if ($contact_id != "")
                        {
                            $contact_details = Contact::model()->getContactDetails($contact_id);
                            $flag            = 0;
                            $arrUserData     = array('first_name' => $contact_details['ctt_first_name'],
                                'last_name'  => $contact_details['ctt_last_name'],
                                'email'      => $contact_details['eml_email_address'],
                                'phone'      => $contact_details['phn_phone_no'],
                                'type'       => $type);
                        }
                    }
                    else
                    {
                        $driver_details = Drivers::model()->findByPk($entity_id);
                        $name           = explode(' ', $driver_details->drv_name);
                        $fname          = $name[0];
                        $lname          = "__";
                        if (array_key_exists('1', $name))
                        {
                            $lname = $name[1];
                        }
                        $flag        = 0;
                        $email       = ContactEmail::model()->getContactEmailById($driver_details->drv_contact_id);
                        $number      = ContactPhone::model()->getContactPhoneById($driver_details->drv_contact_id);
                        $arrUserData = array(
                            'first_name' => $fname,
                            'last_name'  => $lname,
                            'email'      => $email,
                            'phone'      => $number,
                            'type'       => $type
                        );
                    }
                    $success  = false;
                    $messages = "No channel partner found";
                }
                $channelPartner['partnerData'] = $arrUserData;
            }

            Logger::create("response =>" . json_encode($arrUserData), CLogger::LEVEL_TRACE);
            return $this->renderJSON([
                        'type' => 'raw',
                        'data' => array(
                            'success'   => $success,
                            'messages'  => $messages,
                            'flgExists' => $flag,
                            'data'      => $channelPartner,
                        ),
            ]);
        });
        /*
         * Registering new channel partner...
         */
        $this->onRest('req.post.register_channel_partner.render', function() {
            $user_data1      = Yii::app()->request->getParam('data');
            $user_data       = json_decode($user_data1);
            $user_id         = $user_data->user_id;
            $env             = $user_data->env;
            $errors          = [];
            $result          = [];
            $success         = "";
            $agent_form_data = array(
                'fname' => trim(strtolower($user_data->fname)),
                'lname' => trim(strtolower($user_data->lname)),
                'email' => trim(strtolower($user_data->email)),
                'phone' => trim(strtolower($user_data->phone))
            );

            /**
             * add/update contact details
             */
            $jsonObj                                  = new stdClass();
            $jsonObj->profile->firstName              = trim($user_data->fname);
            $jsonObj->profile->lastName               = trim($user_data->lname);
            $jsonObj->profile->email                  = trim($user_data->email);
            $jsonObj->profile->primaryContact->number = trim($user_data->phone);
            $jsonObj->profile->primaryContact->code   = 91;

            $returnSet = Contact::createContact($jsonObj, 0, UserInfo::TYPE_AGENT);
            $contactId = $returnSet->getData()['id'];
            $model     = new Agents();
            $userData  = Users::model()->findByPk($user_id);

            if ($contactId != '')
            {
                $userData->user_id        = $user_id;
                $userData->usr_contact_id = $contactId;
                $userData->save();
            }

            if (count($userData->attributes) > 0)
            {
                $user_previous_data = array(
                    'fname' => trim(strtolower($userData->attributes['usr_name'])),
                    'lname' => trim(strtolower($userData->attributes['usr_lname'])),
                    'email' => trim(strtolower($userData->attributes['usr_email'])),
                    'phone' => trim(strtolower($userData->attributes['usr_mobile'])),
                );
                $check              = 1;
                $prev_count         = count($user_previous_data);
                $result_intersect   = array_intersect($user_previous_data, $agent_form_data);
                if (count($result_intersect) != $prev_count)
                {
                    $check = 0;
                }

                $password                    = $userData->attributes['usr_password'];
                $user_id                     = $userData->attributes['user_id'];
                $model->agt_fname            = $agent_form_data['fname'];
                $model->agt_lname            = $agent_form_data['lname'];
                $model->agt_username         = $agent_form_data['email'];
                $model->agt_approved         = $check;
                $model->agt_phone            = $agent_form_data['phone'];
                $model->agt_email            = $agent_form_data['email'];
                $model->agt_type             = 2;
                $model->agt_commission_value = 1;
                $model->agt_commission       = 7.0;
                $model->agt_password1        = $password;

                $model->agt_contact_id = $contactId;

                $names               = Agents::model()->getAgentCompany($user_id);
                $model->agt_ref_type = $names['refType'];
                $model->agt_ref_id   = $names['refId'];
                $model->agt_company  = $names['agtCompany'];
                if ($model->save())
                {

                    $model->agt_agent_id          = "AGT00" . $model->agt_id;
                    $model->save();
                    $agentUserModel               = new AgentUsers();
                    $agentUserModel->agu_user_id  = $user_id;
                    $agentUserModel->agu_agent_id = $model->agt_id;
                    $agentUserModel->agu_role     = 1;
                    $agentUserModel->save();
                    $rest2                        = Agents::model()->updateAgentPassword($model->agt_id, $password);
                    $checksumstring               = $user_id . $password . $env;
                    $checksum                     = Users::model()->encrypt($checksumstring);
                    $result['checksum']           = $checksum;
                    $result['agent_id']           = $model->agt_id;
                    $result['agent_name']         = $agent_form_data['fname'] . '' . $agent_form_data['lname'];
                    $result['agent_credit']       = 0;
                    //Updating contact profile table
                    ContactProfile::setProfile($contactId, UserInfo::TYPE_AGENT);
                    if ($check == 1)
                    {
                        $message = 'Channel Partner added successfully.';
                    }
                    else
                    {
                        $message = 'Channel Partner added need approval.';
                    }
                    $success = true;
                }
                else
                {
                    $error   = $model->getErrors();
                    $message = $error;
                    $success = false;
                }
            }
            else
            {
                $message = 'User does not exist.';
                $success = false;
            }
            return $this->renderJSON([
                        'type' => 'raw',
                        'data' => array(
                            'success' => $success,
                            'errors'  => $errors,
                            'data'    => $result,
                            'message' => $message,
                        )
            ]);
        });
        /*
         * sent otp for existing aggent with different user id...
         */

        $this->onRest('req.post.generateOtpforLinking.render', function() {
            $data1  = Yii::app()->request->getParam('data');
            $data   = CJSON::decode($data1);
            $userId = $data['user_id'];
            $type   = $data['type'];
            $users  = Users::model()->find("user_id=:id", ['id' => $userId]);
            if (count($users) > 0)
            {
                if ($users->usr_verification_code == "")
                {
                    $code = rand(999, 9999);
                }
                else
                {
                    $code = $users->usr_verification_code;
                }
                $smsWrapper = new smsWrapper();
                $phone      = $users->usr_mobile;
                if ($type == 'vendor')
                {
                    $phone = Vendors::model()->getVendorContact($userId);
                }
                if ($type == 'driver')
                {
                    $phone = Drivers::model()->getDriverContact($userId);
                }
                $countrycode                  = $users->usr_country_code;
                $smsWrapper->sendVerificationlLinkAgent($countrycode, $phone, $code);
                $users->usr_verification_code = $code;
                if ($users->update())
                {
                    $success = true;
                    $message = "OTP sent successfully";
                }
                else
                {
                    $success = false;
                    $message = "Error occured";
                }
            }
            else
            {
                $success = false;
                $message = "Unauthorised user";
            }
            return $this->renderJSON([
                        'type' => 'raw',
                        'data' => array(
                            'success' => $success,
                            'errors'  => $errors,
                            'data'    => $result,
                            'message' => $message,
                        )
            ]);
        });

        //Check agent agreement is signed or not

        $this->onRest('req.post.channelPartnerStatus.render', function() {
            $header = array();
            $header = $_SERVER;

            $this->agentValidation($header);
            $partnerID      = $header['HTTP_X_REST_AGNTID'];
            $model          = Agents::model()->findByPk($partnerID);
            $userdata       = array();
            $success        = false;
            $url            = Yii::app()->params['fullBaseURL'] . "/agent/users/cpagreement";
            $agreementModel = AgentAgreement::model()->getByAgentID($partnerID);
            $arrUserData    = array(
                'first_name' => $model->agt_fname,
                'last_name'  => $model->agt_lname,
                'email'      => $model->agt_email,
                'phone'      => $model->agt_phone,
            );
            if (!$agreementModel || $agreementModel['aag_digital_flag'] != 1)
            {
                $success  = true;
                $isSigned = 0;
                $message  = 'Please sign the agreement first.';
                $url      = Yii::app()->params['fullBaseURL'] . "/agent/users/cpagreement?agtid=" . $partnerID;
            }
            else
            {
                $success  = true;
                $isSigned = 1;
                $url      = "";
                $message  = 'Agreement already signed.';
            }
            $result[] = $arrUserData;
            Logger::create("response => success->" . $success . " result->" . json_encode($result), CLogger::LEVEL_TRACE);
            return $this->renderJSON([
                        'type' => 'raw',
                        'data' => array(
                            'success'   => $success,
                            'errors'    => $errors,
                            'data'      => $result,
                            'message'   => $message,
                            'is_signed' => $isSigned,
                            'url'       => $url
                        )
            ]);
        });

        //Save Agent Agreemnet

        $this->onRest('req.post.saveAgentAgreement.render', function() {
            $header            = array();
            $header            = $_SERVER;
            $this->agentValidation($header);
            $data1             = Yii::app()->request->getParam('data');
            $data              = CJSON::decode($data1);
            $agtDigitalSign    = $_FILES['aag_digital_sign']['name'];
            $agtDigitalSignTmp = $_FILES['aag_digital_sign']['tmp_name'];

            if ($agtDigitalSign != '')
            {
                $agentId  = $header['HTTP_X_REST_AGNTID'];
                $userId   = $header['HTTP_X_REST_UID'];
                $agtModel = Agents::model()->findByPk($agentId);

                $transaction = DBUtil::beginTransaction();
                try
                {
                    $digitalLat  = $data['digitalLat'];
                    $digitalLong = $data['digitalLong'];
                    $digitalVer  = Yii::app()->params['digitalAgtagmtversion'];
                    $type        = 'digital_sign';

                    $agtAgreementPath = AgentAgreement::model()->saveAgreement($agtDigitalSign, $agtDigitalSignTmp, $agentId, $type);
                    $path             = str_replace("\\", "\\\\", $agtAgreementPath['path']);
                    if (AgentAgreement::model()->updateSignature($agentId, $path))
                    {
                        if (AgentAgreement::model()->updateAgreement($agentId, $digitalLat, $digitalLong, $digitalVer))
                        {
                            $success = true;
                        }
                        else
                        {
                            $errors = $model->getErrors();
                        }
                        if ($success == true)
                        {
                            $message                = "Digital signature updated";
                            $agtModel->agt_approved = Agents::model()->getAgentApprovedStatus($userId);
                            if ($agtModel->save())
                            {
                                Logger::create("SAVE Status==>" . $agtModel->save(), CLogger::LEVEL_TRACE);
                                DBUtil::commitTransaction($transaction);
                                $result = AgentAgreement::model()->emailForAgreementCopy($agentId);
                            }
                        }
                    }
                }
                catch (Exception $e)
                {
                    DBUtil::rollbackTransaction($transaction);
                    Logger::create("Digital agreement not saved.\n\t\t" . $e->getMessage(), CLogger::LEVEL_ERROR);
                }
            }
            else
            {
                $success = false;
                $errors  = 'Digital Signature not found';
            }
            return $this->renderJSON([
                        'type' => 'raw',
                        'data' => array(
                            'success' => $success,
                            'errors'  => $errors,
                            'message' => $message
                        )
            ]);
        });

        //Verify Otp

        $this->onRest('req.post.verifyAgentOtp.render', function() {
            $data1  = Yii::app()->request->getParam('data');
            $data   = CJSON::decode($data1);
            $userId = $data['user_id'];
            $env    = $data['env'];
            $users  = Users::model()->find("user_id=:id", ['id' => $userId]);
            $otp    = $data['otp'];
            if (count($users) > 0)
            {
                if ($otp == $users->usr_verification_code)
                {
                    $success     = true;
                    $message     = 'OTP verified Successfully';
                    //Link with existing agent 
                    $agent_email = $data['agent_email'];
                    $agent_phone = $data['agent_phone'];
                    $agents      = Agents::model()->find('agt_email=:email OR agt_phone=:phone', ['email' => $agent_email, 'phone' => $agent_phone]);
                    Logger::create("Agent ID==>" . $agents->agt_id, CLogger::LEVEL_TRACE);
                    $success     = Agents::model()->linkToAgent($agent_email, $agent_phone, $userId);
                    if ($success)
                    {
                        $message .= ' and linked with agent';
                    }
                    else
                    {
                        $message .= ' but failed to link';
                    }
                    $password               = $users->usr_password;
                    $checksumstring         = $userId . $password . $env;
                    $checksum               = Users::model()->encrypt($checksumstring);
                    $result['checksum']     = $checksum;
                    $result['agent_id']     = $agents->agt_id;
                    $result['agent_name']   = $agents->agt_fname . '' . $agents->agt_lname;
                    $result['agent_credit'] = 0;
                }
                else
                {
                    $success = false;
                    $message = " Invalid OTP ";
                }
            }
            else
            {
                $success = false;
                $message = "Unauthorised user";
            }
            return $this->renderJSON([
                        'type' => 'raw',
                        'data' => array(
                            'success' => $success,
                            'errors'  => $errors,
                            'result'  => $result,
                            'message' => $message,
                        )
            ]);
        });

        $this->onRest('req.post.otpverify_link_agent.render', function() {
            $userId = Yii::app()->request->getParam('user_id');

            $users = Users::model()->find("user_id=:id", ['id' => $userId]);

            $otp = Yii::app()->request->getParam('otp');

            if (count($users) > 0)
            {
                if ($otp == '')
                {
                    if ($users->usr_verification_code == "")
                    {
                        $code = rand(999, 9999);
                    }
                    else
                    {
                        $code = $users->usr_verification_code;
                    }
                    $smsWrapper = new smsWrapper();
                    $phone      = $users->usr_mobile;
                    if (trim($phone) == "")
                    {
                        $phone = Vendors::model()->getVendorContact($userId);
                    }
                    if (trim($phone) == "")
                    {
                        $phone = Drivers::model()->getDriverContact($userId);
                    }
                    $countrycode                  = $users->usr_country_code;
                    $smsWrapper->sendVerificationlLinkAgent($countrycode, $phone, $code);
                    $users->usr_verification_code = $code;
                    if ($users->update())
                    {
                        $success = true;
                        $message = "OTP sent successfully";
                    }
                    else
                    {
                        $success = false;
                        $message = "Error occured";
                    }
                    goto response;
                }
                //check agent otp
                if ($otp == $users->usr_verification_code)
                {
                    $agent_email                  = Yii::app()->request->getParam('agent_email');
                    $agent_phone                  = Yii::app()->request->getParam('agent_phone');
                    //$agents	= Agents::model()->find("agt_email=:email", ['email' => $agent_email]);
                    $agents                       = Agents::model()->find('agt_email=:email OR agt_phone=:phone', ['email' => $agent_email, 'phone' => $agent_phone]);
                    // link with other agent through
                    $agentUserModel               = new AgentUsers();
                    $agentUserModel->agu_user_id  = $userId;
                    $agentUserModel->agu_agent_id = $agents->agt_id;
                    $agentUserModel->agu_role     = 1;
                    $agentUserModel->save();
                    $users->usr_verification_code = '';
                    if ($users->update())
                    {
                        $success = true;
                        $message = "Your account has been linked with existing agent";
                    }
                    //remove otp 
                }
                else
                {
                    $success = false;
                    $message = "Invalid OTP";
                }
            }
            else
            {
                $success = false;
                $message = "Unauthorised user";
            }
            response:
            return $this->renderJSON([
                        'type' => 'raw',
                        'data' => array(
                            'success' => $success,
                            'errors'  => $errors,
                            'message' => $message,
                        )
            ]);
        });
    }

    public function getValidationApp($data, $id, $activeVersion)
    {
        if ($activeVersion > $data['apt_apk_version'])
        {
            $active  = 1;
            $success = false;
            $msg     = "Invalid Version";
        }
        else
        {
            if ($id != '')
            {
                $validate = AppTokens::model()->getAppValidations($data, $id);
                $active   = 2;
                $success  = true;
                $msg      = "Validation Done";
            }
            else
            {
                $active  = 3;
                $success = false;
                $msg     = "Invalid User";
            }
        }
        $result = array('active' => $active, 'success' => $success, 'message' => $msg);
        return $result;
    }

    public function actionIndex()
    {
        echo 'Module created';
    }

    public function agentValidation($header)
    {
        // agent validation function start
        $userValidation = Agents::model()->validateAgent($header);
        if ($userValidation != true)
        {
            echo $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success'       => false,
                    'errorMessages' => 'Unauthorised user',
                    'data'          => null
                )
            ]);
            exit;
        }
    }

}
