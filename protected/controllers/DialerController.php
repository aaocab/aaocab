<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class DialerController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = 'column1';
	public $newHome		 = '';
	public $email_receipient, $useUserReturnUrl;
	public $current_page = '';

//public $layout = '//layouts/column2';

	/**
	 * @return array action filters
	 */
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

//    public function behaviors() {
//        return array(
//            'seo' => array('class' => 'application.components.SeoControllerBehavior'),
//        );
//    }
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
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('view'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index', 'callstatus', 'calllist',
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS', 'atirudram2017'),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array('admin'),
				'users'		 => array('admin'),
			),
			['allow', 'actions' => [''], 'users' => ['*']],
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
			$ri	 = array('/transaction', '/getnumber', '/callBackNumber', '/setFollowup',
				'/setNewFollowup', '/setExistingFollowup', '/setNewVendorFollowup',
				'/getContactProfile', '/setVendorFollowup', 'checkOngoingTrip', 'checkDriverOngoingTrip');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.get.getnumber.render', function () {
			$data1		 = Yii::app()->request->rawBody;
			$req		 = [];
			$defApiKey	 = Yii::app()->params['dialerApiKey'];
			$testMode	 = false;
			if ($data1 == "")
			{
				$req['apiKey']		 = Yii::app()->request->getParam('apiKey');
				$req['callerNumber'] = Yii::app()->request->getParam('callerNumber');
				$req['callerType']	 = Yii::app()->request->getParam('callerType');
				$req['tripID']		 = Yii::app()->request->getParam('tripID', 0);
				$req['bkgID']		 = Yii::app()->request->getParam('bkgID', 0);
				$req['bookingID']	 = Yii::app()->request->getParam('bookingID', '') . Yii::app()->request->getParam('bookingId', '');
			}
			else
			{
				$req = json_decode($data1, true);
			}
			Logger::create("Data received: " . json_encode($req));
			if ($req['apiKey'] !== $defApiKey && !$testMode)
			{

				$success = false;
				$data	 = [
					'errorMessage'	 => 'Unauthorised access',
					'respCode'		 => 0
				];
			}
			else
			{
				$clrTypeArr		 = [1 => 'Customer', 2 => 'Driver'];
				$numTypeArr		 = [1 => 'fullContactNo', 2 => 'bcb_driver_phone'];
				$reqTypeArr		 = [1 => 2, 2 => 1];
				$result			 = Dialers::model()->dialerCheck($req, $testMode);
				$customerSupport = '03366283902';
				if (in_array($req['callerType'], [1, 2]))
				{
					$callerType		 = $req['callerType'];
					$reqCallerType	 = $reqTypeArr[$callerType];
					$cntRes			 = count($result);
					if ($req['callerType'] == 1)
					{
						$cntRes1 = 0;
						$key	 = 0;
						foreach ($result as $k => $v)
						{
							if ($v['bcb_driver_phone'] > 0)
							{
								$cntRes1++;
								$key = $k;
							}
						}
						if ($cntRes1 == 1)
						{
							$cntRes		 = 1;
							$result[0]	 = $result[$key];
						}
						if ($cntRes1 == 0)
						{
							if ($cntRes != 1)
							{
								$cntRes = 0;
							}
						}
					}
					if ($cntRes == 0)
					{
						$success				 = false;
						$data['errorMessage']	 = 'No result found.';
						$data['respCode']		 = 101;
						if ($req['bookingID'] != '' || $req['bkgID'] > 0 || $req['tripID'] > 0)
						{
							$data['errorMessage']	 = 'Booking ID not found. Forwarding to customer care.';
							$data['Number']			 = $customerSupport;
							$data['respCode']		 = 110;
						}
					}
					if ($cntRes == 1)
					{
						$bookingStatusList = [2 => 'Recieved', 3 => 'Confirmed', 5 => 'Active', 6 => 'Completed'];

						$numberType		 = $numTypeArr[$reqCallerType];
						$requestedNumber = $result[0][$numberType];
						$numberRaw		 = (($requestedNumber == null || $requestedNumber == '' || $callerType == 2) && in_array($result[0]['bkg_status'], [2, 3]) ) ?
								$customerSupport : $requestedNumber;
						Filter::parsePhoneNumber($numberRaw, $code, $numberPhone);
						$number			 = '00' . $code . $numberPhone;
						$bookingStatus	 = '';
						$bkgStatus		 = $result[0]['bkg_status'];
						$respCode		 = 100;
						if (in_array($bkgStatus, [2, 3]) && $callerType == 2)
						{
							$data	 = [
								'Number'		 => $number,
								'errorMessage'	 => 'Unauthorised request',
								'respCode'		 => 105
							];
							$success = true;
						}
						else
						{
							if (in_array($bkgStatus, [2, 3]) && $callerType == 1)
							{
								$bookingStatus	 = 'Driver not assigned. Forwarding to customer care.';
								$reqCallerType	 = 0;
								$respCode		 = 103;
							}
							elseif ($result[0]['durationActive'] == 1 || $bkgStatus == 5)
							{
								$bookingStatus = 'Active';
							}
							elseif ($result[0]['afterActive'] == 1 || $bkgStatus == 6)
							{
								$bookingStatus = 'Completed';
							}
							else
							{
								$bookingStatus = 'Invalid data';
							}
							$data	 = [
								'bookingID'										 => $result[0]['bkg_booking_id'],
								trim($clrTypeArr[$reqCallerType] . ' Number')	 => $number,
								'bookingStatus'									 => $bookingStatus,
								'respCode'										 => $respCode
							];
							$success = true;
						}
					}
					if ($cntRes > 1)
					{
						$success = false;
						$data	 = [
							'errorMessage'	 => 'Multiple records found',
							'respCode'		 => 102
						];
					}
				}
				else
				{
					$success = false;
					$data	 = [
						'errorMessage'	 => 'Callertype not mentioned properly',
						'respCode'		 => 104
					];
					$cntRes	 = 0;
				}
			}
			Logger::create("Data sent: ($success) " . json_encode($data));
			return $this->renderJSON(
					[
						'type'	 => 'raw',
						'data'	 => array(
							'success'	 => $success,
							'data'		 => $data,
						// 'cntRes'	 => $cntRes
						),
					]
			);
		});

		$this->onRest('req.get.callBackNumber.render', function () {
			$defApiKey			 = Yii::app()->params['dialerApiKey'];
			$testMode			 = false;
			$req				 = [];
			$req['apiKey']		 = Yii::app()->request->getParam('apiKey');
			$req['callerNumber'] = Yii::app()->request->getParam('callerNumber');
			$req['followupId']	 = Yii::app()->request->getParam('followupId');
			if ($req['apiKey'] !== $defApiKey && !$testMode)
			{
				$success = false;
				$data	 = [
					'errorMessage'	 => 'Unauthorised access',
					'respCode'		 => 0
				];
			}
			else
			{
				if (!empty($req['callerNumber']))
				{
					$csrId	 = Admins::getId(trim($req['callerNumber']));
					$result	 = ServiceCallQueue::getCallerNumber($csrId);
				}
				else if (!empty($req['followupId']))
				{
					$details = ServiceCallQueue::detail($req['followupId']);
					$result	 = "";
					if (!$details)
					{
						$result = $details;
					}
					else
					{
						$result										 = [];
						$result['scq_id']							 = $details['scq_id'];
						$result['scq_to_be_followed_up_with_value']	 = $details['scq_to_be_followed_up_with_value'];
						$result['scq_to_be_followed_up_with_type']	 = $details['scq_to_be_followed_up_with_type'];
					}
				}
				else
				{
					$success				 = false;
					$data['errorMessage']	 = 'Invalid Data.';
					$data['respCode']		 = 104;
					goto skip;
				}
				if (!$result)
				{
					$success				 = false;
					$data['errorMessage']	 = 'No result found.';
					$data['respCode']		 = 101;
				}
				else if (count($result) == 3)
				{
					if ($result['scq_to_be_followed_up_with_type'] == 2)
					{
						Filter::parsePhoneNumber(trim($result['scq_to_be_followed_up_with_value']), $code, $numberPhone);
						$number = '00' . $code . $numberPhone;
					}
					else
					{
						$arrPhoneByPriority	 = Contact::getPhoneNoByPriority(trim($result['scq_to_be_followed_up_with_value']));
						Filter::parsePhoneNumber(trim($arrPhoneByPriority['phn_phone_no']), $code, $numberPhone);
						$number				 = '00' . $code . $numberPhone;
					}
					$data	 = [
						'Number'	 => $number,
						'respCode'	 => 100
					];
					$success = true;
				}
				else if (count($result) > 3)
				{
					$success = false;
					$data	 = [
						'errorMessage'	 => 'Multiple records found',
						'respCode'		 => 102
					];
				}
			}
			skip:
			return $this->renderJSON(
					[
						'type'	 => 'raw',
						'data'	 => array(
							'success'	 => $success,
							'data'		 => $data
						),
					]
			);
		});

		$this->onRest('req.get.setFollowup.render', function () {
			exit; //holding
			return $this->renderJSON($this->setFollowup());
		});
		$this->onRest('req.get.setNewFollowup.render', function () {
			return $this->renderJSON($this->setNewFollowup());
		});
		$this->onRest('req.get.setExistingFollowup.render', function () {
			return $this->renderJSON($this->setExistingFollowup());
		});
		$this->onRest('req.get.setVendorFollowup.render', function () {
			return $this->renderJSON($this->setVendorFollowup());
		});

		$this->onRest('req.get.getContactProfile.render', function () {
			return $this->renderJSON($this->getContactProfile());
		});
		$this->onRest('req.get.setNewVendorFollowup.render', function () {
			return $this->renderJSON($this->setNewVendorFollowup());
		});

		$this->onRest('req.get.checkOngoingTrip.render', function () {
			return $this->renderJSON($this->checkOngoingTrip());
		});
		$this->onRest('req.get.checkDriverOngoingTrip.render', function () {
			return $this->renderJSON($this->checkDriverOngoingTrip());
		});

		$this->onRest('req.get.callBackNumber.render', function () {
			$defApiKey			 = Yii::app()->params['dialerApiKey'];
			$testMode			 = false;
			$req				 = [];
			$req['apiKey']		 = Yii::app()->request->getParam('apiKey');
			$req['callerNumber'] = Yii::app()->request->getParam('callerNumber');
			$req['followupId']	 = Yii::app()->request->getParam('followupId');
			if ($req['apiKey'] !== $defApiKey && !$testMode)
			{
				$success = false;
				$data	 = [
					'errorMessage'	 => 'Unauthorised access',
					'respCode'		 => 0
				];
			}
			else
			{
				if (!empty($req['callerNumber']))
				{
					$csrId	 = Admins::getId(trim($req['callerNumber']));
					$result	 = ServiceCallQueue::getCallerNumber($csrId);
				}
				else if (!empty($req['followupId']))
				{
					$details = ServiceCallQueue::detail($req['followupId']);
					$result	 = "";
					if (!$details)
					{
						$result = $details;
					}
					else
					{
						$result										 = [];
						$result['scq_id']							 = $details['scq_id'];
						$result['scq_to_be_followed_up_with_value']	 = $details['scq_to_be_followed_up_with_value'];
						$result['scq_to_be_followed_up_with_type']	 = $details['scq_to_be_followed_up_with_type'];
					}
				}
				else
				{
					$success				 = false;
					$data['errorMessage']	 = 'Invalid Data.';
					$data['respCode']		 = 104;
					goto skip;
				}
				if (!$result)
				{
					$success				 = false;
					$data['errorMessage']	 = 'No result found.';
					$data['respCode']		 = 101;
				}
				else if (count($result) == 3)
				{
					if ($result['scq_to_be_followed_up_with_type'] == 2)
					{
						Filter::parsePhoneNumber(trim($result['scq_to_be_followed_up_with_value']), $code, $numberPhone);
						$number = '00' . $code . $numberPhone;
					}
					else
					{
						$arrPhoneByPriority	 = Contact::getPhoneNoByPriority(trim($result['scq_to_be_followed_up_with_value']));
						Filter::parsePhoneNumber(trim($arrPhoneByPriority['phn_phone_no']), $code, $numberPhone);
						$number				 = '00' . $code . $numberPhone;
					}
					$data	 = [
						'Number'	 => $number,
						'respCode'	 => 100
					];
					$success = true;
				}
				else if (count($result) > 3)
				{
					$success = false;
					$data	 = [
						'errorMessage'	 => 'Multiple records found',
						'respCode'		 => 102
					];
				}
			}
			skip:
			return $this->renderJSON(
					[
						'type'	 => 'raw',
						'data'	 => array(
							'success'	 => $success,
							'data'		 => $data
						),
					]
			);
		});
	}

	public function actionIndex()
	{
		echo "Index";
		exit;
		$this->redirect('/');
	}

	public function actionCallstatus()
	{
		$cst_id					 = Yii::app()->request->getParam('cst_id');
		$cst_lead_id			 = Yii::app()->request->getParam('cst_lead_id');
		$cst_phone_code			 = Yii::app()->request->getParam('cst_phone_code');
		$cst_phone				 = Yii::app()->request->getParam('cst_phone');
		$cst_did				 = Yii::app()->request->getParam('cst_did');
		$cst_group				 = Yii::app()->request->getParam('cst_group');
		$cst_agent_name			 = Yii::app()->request->getParam('cst_agent_name', '');
		$cst_recording_file_name = Yii::app()->request->getParam('cst_recording_file_name', '');
		$cst_camp				 = Yii::app()->request->getParam('cst_campaign');
		$cst_status				 = Yii::app()->request->getParam('cst_status', 2);

		$sql = "
		INSERT INTO `call_status`(`cst_id`, `cst_lead_id`, `cst_phone_code`, `cst_phone`, `cst_did`,
		`cst_agent_name`, `cst_recording_file_name`,
		`cst_group`, `cst_camp`,`cst_status`) 
		VALUES ('$cst_id','$cst_lead_id','$cst_phone_code','$cst_phone','$cst_did',
			'$cst_agent_name','$cst_recording_file_name',
				'$cst_group','$cst_camp',$cst_status) 
		ON DUPLICATE KEY UPDATE cst_status='$cst_status'
		";
		Yii::app()->db->createCommand($sql)->execute();
	}

	public function actionCalllist()
	{

		$this->pageTitle = "Call Status List";

		$model				 = new CallStatus('search');
		$model->cst_status	 = null;
		$arr				 = [];
		if (isset($_REQUEST['CallStatus']))
		{
			$arr				 = Yii::app()->request->getParam('CallStatus');
			$model->attributes	 = array_filter($arr);
		}
		$dataProvider = $model->getList(array_filter($arr));

		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$this->render('calllist1', array(
			'model'			 => $model,
			'dataProvider'	 => $dataProvider,
		));
	}

	public function setFollowup()
	{
		$data1		 = Yii::app()->request->rawBody;
		$req		 = [];
		$defApiKey	 = Yii::app()->params['dialerApiKey'];
		$testMode	 = true;
		if ($data1 == "")
		{
			$req['apiKey']		 = Yii::app()->request->getParam('apiKey');
			$req['callerNumber'] = Yii::app()->request->getParam('callerNumber');
			$req['callType']	 = Yii::app()->request->getParam('callType');
			$req['bkgID']		 = Yii::app()->request->getParam('bkgID', 0);
		}
		else
		{
			$req = json_decode($data1, true);
		}

		$returnSet = new ReturnSet();
		try
		{
			Logger::create("Data received: " . json_encode($req));
			if ($req['apiKey'] !== $defApiKey && !$testMode)
			{
				throw new Exception('Unauthorised access', ReturnSet::ERROR_UNAUTHORISED);
			}
			else
			{
				$returnSet = FollowUps::saveCMBCallData($req);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	function setVendorFollowup()
	{
		$data1		 = Yii::app()->request->rawBody;
		$req		 = [];
		$defApiKey	 = Yii::app()->params['dialerApiKey'];
		$testMode	 = true;
		if ($data1 == "")
		{
			$req['apiKey']		 = Yii::app()->request->getParam('apiKey');
			$req['callerNumber'] = Yii::app()->request->getParam('callerNumber');
			$req['vendorId']	 = Yii::app()->request->getParam('vendorId');
		}
		else
		{
			$req = json_decode($data1, true);
		}

		$returnSet = new ReturnSet();
		try
		{
			Logger::create("Data received: " . json_encode($req));
			if ($req['apiKey'] !== $defApiKey && !$testMode)
			{
				throw new Exception('Unauthorised access', ReturnSet::ERROR_UNAUTHORISED);
			}
			else
			{
				$returnSet = ServiceCallQueue::setVendorCallBackData($req);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	function setNewFollowup()
	{
		$data1		 = Yii::app()->request->rawBody;
		$req		 = [];
		$defApiKey	 = Yii::app()->params['dialerApiKey'];
		$testMode	 = true;
		if ($data1 == "")
		{
			$req['apiKey']		 = Yii::app()->request->getParam('apiKey');
			$req['callerNumber'] = Yii::app()->request->getParam('callerNumber');
			$req['userId']		 = Yii::app()->request->getParam('userId');
		}
		else
		{
			$req = json_decode($data1, true);
		}

		$returnSet = new ReturnSet();
		try
		{
			Logger::create("Data received: " . json_encode($req));
			if ($req['apiKey'] !== $defApiKey && !$testMode)
			{
				throw new Exception('Unauthorised access', ReturnSet::ERROR_UNAUTHORISED);
			}
			else
			{
				$returnSet = ServiceCallQueue::setNewCallBackData($req);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	function setExistingFollowup()
	{
		$data1		 = Yii::app()->request->rawBody;
		$req		 = [];
		$defApiKey	 = Yii::app()->params['dialerApiKey'];
		$testMode	 = true;
		if ($data1 == "")
		{
			$req['apiKey']			 = Yii::app()->request->getParam('apiKey');
			$req['callerNumber']	 = Yii::app()->request->getParam('callerNumber');
			$req['userNumber']		 = Yii::app()->request->getParam('userNumber');
			$req['bkgID']			 = Yii::app()->request->getParam('bkgID', 0);
			$req['skipValidation']	 = Yii::app()->request->getParam('skipValidation', 0);
		}
		else
		{
			$req = json_decode($data1, true);
		}

		$returnSet = new ReturnSet();
		try
		{
			Logger::create("Data received: " . json_encode($req));
			if ($req['apiKey'] !== $defApiKey && !$testMode)
			{
				throw new Exception('Unauthorised access', ReturnSet::ERROR_UNAUTHORISED);
			}
			else
			{
				$returnSet = ServiceCallQueue::setExistingCallBackData($req);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	function setNewVendorFollowup()
	{
		$data1		 = Yii::app()->request->rawBody;
		$req		 = [];
		$defApiKey	 = Yii::app()->params['dialerApiKey'];
		$testMode	 = true;
		if ($data1 == "")
		{
			$req['apiKey']		 = Yii::app()->request->getParam('apiKey');
			$req['callerNumber'] = Yii::app()->request->getParam('callerNumber');
		}
		else
		{
			$req = json_decode($data1, true);
		}

		$returnSet = new ReturnSet();
		try
		{
			Logger::create("Data received: " . json_encode($req));
			if ($req['apiKey'] !== $defApiKey && !$testMode)
			{
				throw new Exception('Unauthorised access', ReturnSet::ERROR_UNAUTHORISED);
			}
			else
			{
				$returnSet = FollowUps::setNewVendorFollowup($req);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	function getContactProfile()
	{
		$data1		 = Yii::app()->request->rawBody;
		$req		 = [];
		$defApiKey	 = Yii::app()->params['dialerApiKey'];
		$testMode	 = true;
		if ($data1 == "")
		{
			$req['apiKey']		 = Yii::app()->request->getParam('apiKey');
			$req['callerNumber'] = Yii::app()->request->getParam('callerNumber');
			$req['checkEntity']	 = Yii::app()->request->getParam('checkEntity'); //0=>default,1=>user,2=>vendor,3=>driver
		}
		else
		{
			$req = json_decode($data1, true);
		}

		$returnSet = new ReturnSet();
		try
		{
			Logger::create("Data received: " . json_encode($req));
			if ($req['apiKey'] !== $defApiKey && !$testMode)
			{
				throw new Exception('Unauthorised access', ReturnSet::ERROR_UNAUTHORISED);
			}
			else
			{
				$callerNumber	 = $req['callerNumber'];
				$checkEntity	 = $req['checkEntity'] | 0;
				$phone			 = $callerNumber;
				$phone			 = trim(str_replace(' ', '', $phone));
//				$phone			 = preg_replace('/[^0-9\-]/', '', $phone);
				$phone			 = preg_replace('/[^0-9+]/', '', $phone);
				if (!Filter::validatePhoneNumber($phone))
				{
					throw new Exception('Invalid phone number', ReturnSet::ERROR_INVALID_DATA);
				}
				$contactData = ContactProfile::getProfilebyPhone($callerNumber);

				switch ($checkEntity)
				{
					case 0:
						break;
					case 1:
						if (!$contactData['cr_is_consumer'])
						{
							throw new Exception('consumer is not registered', ReturnSet::ERROR_NO_RECORDS_FOUND);
						}
						break;
					case 2:
						if (!$contactData['cr_is_vendor'])
						{
							throw new Exception('vendor is not registered', ReturnSet::ERROR_NO_RECORDS_FOUND);
						}
						break;
					case 3:
						if (!$contactData['cr_is_driver'])
						{
							throw new Exception('driver is not registered', ReturnSet::ERROR_NO_RECORDS_FOUND);
						}
						break;
					default:
						throw new Exception('invalid option', ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
				}
				if (!$contactData)
				{
					throw new Exception('number is not registered', ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
				$returnSet->setStatus(true);
				$contactArr['contactId']	 = $contactData['cr_contact_id'];
				$contactArr['vendorId']		 = $contactData['cr_is_vendor'];
				$contactArr['consumerId']	 = $contactData['cr_is_consumer'];
				$contactArr['driverId']		 = $contactData['cr_is_driver'];
				$returnSet->setData($contactArr);
			}
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	function checkOngoingTrip()
	{
		$data1		 = Yii::app()->request->rawBody;
		$req		 = [];
		$defApiKey	 = Yii::app()->params['dialerApiKey'];
		$testMode	 = true;
		if ($data1 == "")
		{
			$req['apiKey']		 = Yii::app()->request->getParam('apiKey');
			$req['callerNumber'] = Yii::app()->request->getParam('callerNumber', '');
			$req['bkgID']		 = Yii::app()->request->getParam('bkgID', '');
		}
		else
		{
			$req = json_decode($data1, true);
		}

		$returnSet = new ReturnSet();
		try
		{
//			Logger::beginProfile('DialerCheckOngoingTrip');
			Logger::create("Data received: " . json_encode($req));
			if ($req['apiKey'] !== $defApiKey && !$testMode)
			{
				throw new Exception('Unauthorised access', ReturnSet::ERROR_UNAUTHORISED);
			}
			else
			{
				$bkgID		 = trim($req['bkgID']);
				$bookingId	 = null;
				if (strlen($bkgID) > 4)
				{
					$bookingId = BookingSub::getbyBookingLastDigits($bkgID, 1);
				}
				$phone = $req['callerNumber'];

				$phone				 = trim(str_replace(' ', '', $phone));
				$phone				 = preg_replace('/[^0-9+]/', '', $phone);
//				if (!Filter::validatePhoneNumber($phone))
//				{
//				//	throw new Exception('Invalid phone number', ReturnSet::ERROR_INVALID_DATA);
//				}
				$hasOngoingBooking	 = BookingSub::checkCustomerExistingOngoingBooking($phone, $bookingId);
				$status				 = (bool) ($hasOngoingBooking);
				$returnSet->setStatus($status);
			}
//			Logger::pushProfileLogs();
//			Logger::endProfile('DialerCheckOngoingTrip');
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	function checkDriverOngoingTrip()
	{
		$data1		 = Yii::app()->request->rawBody;
		$req		 = [];
		$defApiKey	 = Yii::app()->params['dialerApiKey'];
		$testMode	 = true;
		if ($data1 == "")
		{
			$req['apiKey']		 = Yii::app()->request->getParam('apiKey');
			$req['callerNumber'] = Yii::app()->request->getParam('callerNumber');
			$req['bkgID']		 = Yii::app()->request->getParam('bkgID', '');
		}
		else
		{
			$req = json_decode($data1, true);
		}

		$returnSet = new ReturnSet();
		try
		{
//			Logger::beginProfile('DialercheckDriverOngoingTrip');
			Logger::create("Data received: " . json_encode($req));
			if ($req['apiKey'] !== $defApiKey && !$testMode)
			{
				throw new Exception('Unauthorised access', ReturnSet::ERROR_UNAUTHORISED);
			}
			else
			{
				$bkgID		 = trim($req['bkgID']);
				$bookingId	 = null;
				if (strlen($bkgID) > 4)
				{
					$bookingId = BookingSub::getbyBookingLastDigits($bkgID, 1);
				}
				$phone	 = $req['callerNumber'];
				$phone	 = trim(str_replace(' ', '', $phone));
				$phone	 = preg_replace('/[^0-9+]/', '', $phone);
//				if (!Filter::validatePhoneNumber($phone))
//				{
//					throw new Exception('Invalid phone number', ReturnSet::ERROR_INVALID_DATA);
//				}
				$hasOngoingBooking	 = BookingSub::checkDriverExistingOngoingBooking($phone,$bookingId);
				$status				 = (bool) ($hasOngoingBooking);
				$returnSet->setStatus($status);
			}
//			Logger::pushProfileLogs();
//			Logger::endProfile('DialercheckDriverOngoingTrip');
		}
		catch (Exception $ex)
		{
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

}
