<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class ScqController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $newHome				 = '';
	public $layout				 = '//layouts/column1';
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
			'accessControl',
			//'postOnly + agentjoin,vendorjoin',
			'postOnly + agentjoin',
			array(
				'CHttpCacheFilter + country',
				'lastModified' => $this->getLastModified(),
			),
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
			['allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('ExistingBookingCallBack', 'VendorAttachmentCallBack', 'ExistingVendorCallBack', 'RefreshCMBQue'),
				'users'		 => array('@'),
			],
			['allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('Helpline', 'newBookingCallBack', 'StoreCallBackData', 'DeactivatCallBack', 'form'),
				'users'		 => array('*'),
			],
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

	/**
	 * This function is used opening the popup form for service call queue
	 */
	public function actionHelpline()
	{
		$isMobile = Yii::app()->request->getParam('ismobile');
		if($isMobile)
		{
			$this->checkForMobileTheme();
		}
		$vndid	 = 0;
		$userId	 = UserInfo::getUserId();
		if($userId > 0)
		{
			$contactId	 = ContactProfile::getByEntityId($userId);
//$this->showCallbackQue();
			$entityType	 = UserInfo::TYPE_VENDOR;
			$vnd		 = ContactProfile::getEntityById($contactId, $entityType);
			$vndid		 = $vnd['id'];
		}
		$this->pageTitle = "Support Helpline";
		$this->renderPartial('helpline', array('isContactVendor' => $vndid, 'userId' => $userId));
	}

	/**
	 * This function is used opening form for submitting data for New Booking CallBack
	 */
	public function actionnewBookingCallBack()
	{
		$isMobile = Yii::app()->request->getParam('ismobile');
		if($isMobile)
		{
			$this->checkV2Theme();
		}
		$model		 = new ServiceCallQueue();
		$userId		 = UserInfo::getUserId();
		$refType	 = 1;
		$bookingCode = '';
		$bkgStatus	 = '';
		$request	 = Yii::app()->request;
		$bookingId	 = $request->getParam('bkgId');
		if($bookingId > 0)
		{
			$bkgModel	 = Booking::model()->findByPk($bookingId);
			$bkgStatus	 = $bkgModel->bkg_status;
			$bookingCode = $bkgModel->bkg_booking_id;
			$userId		 = $bkgModel->bkgUserInfo->bkg_user_id;
		}
		else
		{
			$url	 = $tripUrl = Yii::app()->createUrl('scq/newBookingCallBack', $params);
			$phone	 = $request->getParam("userPhone");
			if(Yii::app()->user->isGuest && $phone == "" && $request->isAjaxRequest)
			{
				throw new CHttpException(401, "Login required.", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$model->scq_follow_up_queue_type = $refType;
		if($bookingCode != '')
		{
			$model->scq_related_bkg_id = $bookingCode;
		}
		$this->showCallbackQue($refType, $userId);
		if($refType == 1)
		{
			$umodel									 = Users::model()->findByPk($userId);
			$contactId								 = ContactProfile::getByEntityId($userId);
			$isprimary								 = true;
			$primaryPhone							 = ContactPhone::getContactNumber($contactId);
			$model->scq_to_be_followed_up_with_value = $primaryPhone;
		}
		$this->renderAuto('callbackRequest', array('model'			 => $model,
			'umodel'		 => $umodel, 'userId'		 => $userId,
			'refType'		 => 1, 'primaryPhone'	 => $primaryPhone, 'bkgstatus'		 => $bkgStatus), false, false);
	}

	/**
	 * This function is used opening form for submitting data for Existing Booking CallBack
	 */
	public function actionExistingBookingCallBack()
	{
		$model							 = new ServiceCallQueue();
		$userId							 = UserInfo::getUserId();
		$refType						 = 2;
		$bkgId							 = Yii::app()->request->getParam("bkgId") | "";
		$msg							 = Yii::app()->request->getParam("msg") | "";
		$model->scq_follow_up_queue_type = $refType;
		if($bkgId != "")
		{
			$model->scq_follow_up_queue_type = 51;  //reschedule booking
			$refType						 = 51;
		}
		$this->showCallbackQue($refType);
		if($refType == 2 || $refType == 51)
		{
			$umodel									 = Users::model()->findByPk($userId);
			$contactId								 = ContactProfile::getByEntityId($userId);
			$isprimary								 = true;
			$primaryPhone							 = ContactPhone::getContactNumber($contactId);
			$model->scq_to_be_followed_up_with_value = $primaryPhone;
			$bkgId									 = Yii::app()->request->getParam('bkgId');
			if($bkgId)
			{
				//$bkgBookingId = Booking::model()->getCodeById($bkgId);
				$model->scq_related_bkg_id = $bkgId; //$bkgBookingId; 
			}
		}
		if($bkgId != "")
		{
			$model->scq_related_bkg_id		 = $bkgId;
			$model->scq_creation_comments	 = $msg;
		}
		$this->renderAuto('callbackRequest', array('model'			 => $model,
			'umodel'		 => $umodel, 'userId'		 => $userId,
			'refType'		 => 2, 'primaryPhone'	 => $primaryPhone), false, false);
	}

	/**
	 * This function is used opening form for submitting data for Vendor Attachment CallBack
	 */
	public function actionVendorAttachmentCallBack()
	{
		$model							 = new ServiceCallQueue();
		$userId							 = UserInfo::getUserId();
		$refType						 = 3;
		$model->scq_follow_up_queue_type = $refType;
		$this->showCallbackQue($refType);
		if($refType == 3)
		{
			$umodel									 = Users::model()->findByPk($userId);
			$contactId								 = ContactProfile::getByEntityId($userId);
			$isprimary								 = true;
			$primaryPhone							 = ContactPhone::getContactNumber($contactId);
			$model->scq_to_be_followed_up_with_value = $primaryPhone;
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->renderAuto('callbackRequest', array('model'			 => $model,
			'umodel'		 => $umodel, 'userId'		 => $userId,
			'refType'		 => 3, 'primaryPhone'	 => $primaryPhone), false, $outputJs);
	}

	/**
	 * This function is used opening form for submitting data for Existing Vendor  CallBack
	 */
	public function actionExistingVendorCallBack()
	{
		$model							 = new ServiceCallQueue();
		$userId							 = UserInfo::getUserId();
		$refType						 = 4;
		$model->scq_follow_up_queue_type = $refType;
		$this->showCallbackQue($refType);
		if($refType == 4)
		{
			$umodel		 = Users::model()->findByPk($userId);
			$contactId	 = ContactProfile::getByEntityId($userId);
			$vnd		 = ContactProfile::getEntityById($contactId, UserInfo::TYPE_VENDOR);
			if(!$vnd['id'])
			{
				echo "There is no vendor attached with your contact.";
				Yii::app()->end();
			}
			$vmodel = Vendors::model()->findByPk($vnd['id']);
			if(!$vmodel)
			{
				echo "Your account as vendor has been deleted.Please contact to Gozo.";
				Yii::app()->end();
			}
			$isprimary								 = true;
			$primaryPhone							 = ContactPhone::getContactNumber($contactId);
			$model->scq_to_be_followed_up_with_value = $primaryPhone;
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->renderAuto('callbackRequest', array('model'			 => $model,
			'umodel'		 => $umodel, 'userId'		 => $userId,
			'refType'		 => 4, 'primaryPhone'	 => $primaryPhone), false, true);
	}

	/**
	 * This function is used checking whether you had previous created any followup or not
	 */
	public function showCallbackQue($refType = 0, $userId = '')
	{
		$isMobile = Yii::app()->request->getParam('ismobile');
		if($isMobile)
		{
			$this->checkV2Theme();
		}
		$userId			 = ($userId != '') ? $userId : UserInfo::getUserId();
		$haveCallback	 = ServiceCallQueue::checkActiveCallback($userId, $refType);
		if($haveCallback <= 0)
		{
			return false;
		}
		$followupId		 = ServiceCallQueue::getIdByUserId($userId, $refType);
		$fpModel		 = ServiceCallQueue::model()->findbyPk($followupId);
		$queueData		 = ServiceCallQueue::getQueueNumber($fpModel->scq_id, $fpModel->scq_follow_up_queue_type);
		$queNo			 = $queueData['queNo'];
		$waitTime		 = $queueData['waitTime'];
		$contactNumber	 = $fpModel->scq_to_be_followed_up_with_value;
		$followupCode	 = $fpModel->scq_unique_code;
		$this->renderAuto('callbackConfirm', array('success' => $success, 'followupCode' => $followupCode, 'followupId' => $followupId, 'queNo' => $queNo, 'contactNumber' => $contactNumber, 'waitTime' => $waitTime), false, false);
		Yii::app()->end();
	}

	/**
	 * This function is used for storing the call back data by customers
	 */
	public function actionStoreCallBackData_OLD()
	{
		$model									 = new ServiceCallQueue();
		$callQueue								 = Yii::app()->request->getParam('ServiceCallQueue');
		$model->attributes						 = $callQueue;
		$countryCode							 = ($callQueue['countrycode'] != '') ? $callQueue['countrycode'] : '91';
		$model->scq_to_be_followed_up_with_value = $countryCode . str_replace(' ', '', $model->scq_to_be_followed_up_with_value);
		$scq_related_bkg_id						 = Yii::app()->request->getParam('ServiceCallQueue')['scq_related_bkg_id'];
		$entityId								 = UserInfo::getUserId();
		if($entityId == '')
		{
			$bkgModel	 = Booking::model()->find('bkg_booking_id=:bkg_booking_id', ['bkg_booking_id' => $scq_related_bkg_id]);
			$entityId	 = $bkgModel->bkgUserInfo->bkg_user_id;
		}
		$umodel		 = Users::model()->findByPk($entityId);
		$isMobile	 = Yii::app()->request->getParam('ismobile');
		if($isMobile)
		{
			$this->checkForMobileTheme();
		}
		$success = false;
		if($model)
		{
			try
			{
				$entityType				 = UserInfo::TYPE_CONSUMER;
				$contactId				 = ContactProfile::getByEntityId($entityId, $entityType);
				$platform				 = ($isMobile == 1) ? ServiceCallQueue::PLATFORM_WEB_MOBILE : ServiceCallQueue::PLATFORM_WEB_DESKTOP;
				$model->contactRequired	 = 1;
				if($model->scq_follow_up_queue_type == 4)
				{
					$row										 = ContactProfile::getEntityById($contactId, UserInfo::TYPE_VENDOR);
					$model->scq_to_be_followed_up_with_entity_id = $row['id'];
				}
				else
				{
					$model->scq_to_be_followed_up_with_entity_id = $entityId;
				}
				$model->scq_related_bkg_id					 = $scq_related_bkg_id != null ? $scq_related_bkg_id : null;
				$model->scq_to_be_followed_up_with_contact	 = ContactProfile::getByEntityId($entityId, $entityType);
				$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
				if($model->scq_follow_up_queue_type == 1)
				{
					$csrRow						 = ServiceCallQueue::getPreferredCsr($model->scq_to_be_followed_up_with_value, $entityId);
					$model->scq_preferred_csr	 = 0;
					if(!empty($csrRow))
					{
						$model->scq_preferred_csr = $csrRow['scq_assigned_uid'];
					}
				}
				$returnSet = ServiceCallQueue::model()->create($model, $entityType, $platform);
			}
			catch(Exception $ex)
			{
				echo $ex->getMessage();
				Yii::app()->end();
			}
		}

		$this->showCallbackQue();
	}

	/**
	 * This function is used for storing the call back data by customers
	 */
	public function actionStoreCallBackData()
	{
		$model				 = new ServiceCallQueue();
		$callQueue			 = Yii::app()->request->getParam('ServiceCallQueue');
		$model->attributes	 = $callQueue;

		$refid	 = $callQueue['scq_related_bkg_id'];
		$reftype = $model->scq_follow_up_queue_type;
		$userId	 = UserInfo::getUserId();

		$isValidBookingID = Booking::isValidBookingId($refid, $reftype, $userId);
		if($isValidBookingID['success'] == false)
		{
			goto endForInvalidBooking;
		}
		$phone			 = $model->scq_to_be_followed_up_with_value;
		$isValidPhone	 = ContactPhone::validatePhoneSCQ($phone);
		if(!$isValidPhone)
		{
			$isValidPhone = false;
			goto endForInvalidPhone;
		}

		$countryCode							 = ($callQueue['countrycode'] != '') ? $callQueue['countrycode'] : '91';
		Filter::parsePhoneNumber('+' . $callQueue['scq_to_be_followed_up_with_value'], $code, $number);
		$model->scq_to_be_followed_up_with_value = $code . $number;
		$scq_related_bkg_id						 = Yii::app()->request->getParam('ServiceCallQueue')['scq_related_bkg_id'];
		$entityId								 = UserInfo::getUserId();
		if($entityId == '')
		{
			$bkgModel	 = Booking::model()->find('bkg_booking_id=:bkg_booking_id', ['bkg_booking_id' => $scq_related_bkg_id]);
			$entityId	 = $bkgModel->bkgUserInfo->bkg_user_id;
		}
		$umodel		 = Users::model()->findByPk($entityId);
		$isMobile	 = Yii::app()->request->getParam('ismobile');
		if($isMobile)
		{
			$this->checkForMobileTheme();
		}
		$success = false;
		if($model)
		{
			try
			{
				$entityType				 = UserInfo::TYPE_CONSUMER;
				$contactId				 = ContactProfile::getByEntityId($entityId, $entityType);
				$platform				 = ($isMobile == 1) ? ServiceCallQueue::PLATFORM_WEB_MOBILE : ServiceCallQueue::PLATFORM_WEB_DESKTOP;
				$model->contactRequired	 = 1;
				if($model->scq_follow_up_queue_type == 4)
				{
					$row										 = ContactProfile::getEntityById($contactId, UserInfo::TYPE_VENDOR);
					$model->scq_to_be_followed_up_with_entity_id = $row['id'];
				}
				else
				{
					$model->scq_to_be_followed_up_with_entity_id = $entityId;
				}
				$model->scq_related_bkg_id					 = $scq_related_bkg_id != null ? $scq_related_bkg_id : null;
				$model->scq_to_be_followed_up_with_contact	 = ContactProfile::getByEntityId($entityId, $entityType);
				$model->subQueue							 = ServiceCallQueue::SUB_FOLLOW_UP_REQUEST;
				if($model->scq_follow_up_queue_type == 1)
				{
					$csrRow						 = ServiceCallQueue::getPreferredCsr($model->scq_to_be_followed_up_with_value, $entityId);
					$model->scq_preferred_csr	 = 0;
					if(!empty($csrRow))
					{
						$model->scq_preferred_csr = $csrRow['scq_assigned_uid'];
					}
				}
				//  Logger::trace('ServiceCallQueue data : ' . $model->scq_to_be_followed_up_with_value);
				$returnSet = ServiceCallQueue::model()->create($model, $entityType, $platform);
			}
			catch(Exception $ex)
			{
				echo $ex->getMessage();
				Yii::app()->end();
			}
		}

		$this->showCallbackQue();

		endForInvalidPhone:
		$data = ['success' => $isValidPhone, 'type' => 'phone'];
		echo json_encode($data);
		Yii::app()->end();

		endForInvalidBooking:
		$data = ['success' => $success, "flag" => $flag, 'type' => 'booking'];
		echo json_encode($data);
		Yii::app()->end();
	}

	/**
	 * This function is used for Deactivating  Call Back by customers
	 */
	public function actionDeactivatCallBack()
	{
		$isMobile	 = Yii::app()->request->getParam('ismobile');
		$scq_id		 = Yii::app()->request->getParam('clbRef');
		$userId		 = UserInfo::getUserId();
		if($userId == '')
		{
			$scqModel	 = ServiceCallQueue::model()->findByPk($scq_id);
			$bkgId		 = $scqModel->scq_related_bkg_id;
			if($bkgId != '')
			{
				$bkgModel	 = Booking::model()->findByPk($bkgId);
				$userId		 = $bkgModel->bkgUserInfo->bkg_user_id;
			}
		}
		ServiceCallQueue::deactivateById($userId, $scq_id);
		if($isMobile == 1)
		{
			echo CJSON::encode(['status' => 0]);
			Yii::app()->end();
		}
		else
		{
			$this->redirect('/');
		}
	}

	/**
	 * This function is used for refresh  Call Back by customers
	 */
	public function actionRefreshCMBQue()
	{

		$userId		 = UserInfo::getUserId();
		$umodel		 = Users::model()->findByPk($userId);
		$contactId	 = $umodel->usr_contact_id;
		$queNo		 = ServiceCallQueue::countWaitingFollowupByContact($contactId);
		echo CJSON::encode(['queNo' => $queNo]);
		Yii::app()->end();
	}

	public function actionForm()
	{
		$this->checkV3Theme();
		$request	 = Yii::app()->Request;
		$pathname	 = basename(Yii::app()->request->pathInfo);
		$this->render('form', array('pathname' => $pathname));
	}
}
