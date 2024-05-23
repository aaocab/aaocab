<?php
include_once(dirname(__FILE__) . '/BaseController.php');

class IssueController extends BaseController
{
    public $layout		 = 'column1';
	public $newHome		 = '';
    
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
				'actions'	 => array('ReportIssue','SosTrigger'),
				'users'		 => array('@'),
			],
            ['deny', // deny all users
				'users' => array('*'),
			],
		);
	}
    
    
    public function actionSosTrigger()
    {
        $bookingId = Yii::app()->request->getParam('booking_id');
        $isSos     = Yii::app()->request->getParam('is_sos');
        $eventType = Yii::app()->request->getParam('event_type');
        $latitude  = Yii::app()->request->getParam('latitude');
        $longitude = Yii::app()->request->getParam('longitude');
        $isIssue   = Yii::app()->request->getParam('isIssue');
        
        $userInfo = UserInfo::getInstance();
        $userId = $userInfo->userId;
        
        $bkgModel = Booking::model()->findByPk($bookingId);
        
        if(in_array($eventType, [301,302]))
        {
            goto sos;
        }
        if($isSos == 1)
        {
            $data = Users::getBookingsByUserId($userId);
			if($bookingId > 0)
            {
               $isSosFlag	 = $bkgModel->bkgTrack->bkg_sos_sms_trigger;
               $sosBkgId	 = $bookingId;
            }
            /*if($data['bkg_id'] > 0)
            {
               $isSosFlag	 = $data['bkg_sos_sms_trigger'];
               $sosBkgId	 = $data['bkg_id'];
            }*/
            else
            {
                $isSosFlag = 0;
            }
            echo CJSON::encode(['isSosFlag' => $isSosFlag, 'sosBkgId' => $sosBkgId]);
            Yii::app()->end();
        }
        
        sos:
        $model = new BookingTrackLog;
        $model->btl_sync_time = date('Y-m-d H:i:s');
        $model->btl_created   = new CDbExpression('now()');
        $coordinate				 = new \Stub\common\Coordinates($latitude, $longitude);
		$model->btl_coordinates	 = $coordinate->latitude . "," . $coordinate->longitude;

		$model->btl_event_type_id	 = $eventType;
		$model->btl_event_platform	 = 1;
		$model->btl_user_type_id	 = empty($userInfo->userType) ? $userInfo->getUserType() : $userInfo->userType;
		$model->btl_user_id			 = empty($userInfo->userId) ? $userInfo->getUserId() : $userInfo->userId;
		$model->btl_bkg_id			 = $bookingId;
        $model->btl_bcb_id           = $bkgModel->bkg_bcb_id;
        
        $returnSet =  $model->SOS();
        $model->refresh();
        $sosFlag = $model->btlBkg->bkgTrack->bkg_sos_sms_trigger;
        if($sosFlag != '')
        {
             $returnSet->setData(['sosFlag' => $sosFlag]);
        }
        
        if($isIssue == 1)
        {
            $view  = "reportIssueConfirm";
            $this->renderAuto($view, array('bkgId' => $bookingId,'isIssue' => $isIssue,'eventType' => $eventType,'returnSet' => json_encode($returnSet)), false, true);
            Yii::app()->end();
        }
        echo json_encode($returnSet);
        Yii::app()->end();
    }
	
	public function actionReportIssue()
	{
        $request = Yii::app()->request;
        $rpiId     = Yii::app()->request->getParam('rpi_id'); 
        $rpiType   = Yii::app()->request->getParam('rpi_type');
        $bookingId = Yii::app()->request->getParam('booking_id');
        $rpiDetails = Yii::app()->request->getParam('ReportIssue')['report_issue_desc'];
        $repIssueModel =  new ReportIssue();
       
        $isShowIssue = ReportIssue::checkStatusToShowIssue($bookingId);
        if(!$isShowIssue)
        {
            echo json_encode(['success' => false, 'errors' => "Issue reported only for Ongoing Trip for this booking."]);
            Yii::app()->end();
        }
        
        if($rpiId != '' && $bookingId != '' && $request->isPostRequest)
        {
            $userInfo    = UserInfo::getInstance();
            $userId      = $userInfo->getUserId();
            $rpiModel    = ReportIssue::model()->findByPk($rpiId);
            
            if(($rpiModel->rpi_filename != null || $rpiModel->rpi_filename != '') && ($request->getParam('is_issue_desc') != 1))
            {
                $view = $rpiModel->rpi_filename;
                $outputJs = Yii::app()->request->isAjaxRequest;
				$this->renderAuto($view, ['model' => $rpiModel,'rpiType' => $rpiType,'bkgId' => $bookingId], false, true);
                Yii::app()->end();
            }
            
            $isIssueActive    = ServiceCallQueue::checkActiveCBRByIssueId($bookingId, $userId, $rpiModel->rpi_id, $rpiModel->rpi_queue_id);
            if($isIssueActive)
            {
                echo json_encode(['success' => false, 'followupId' => $isIssueActive, 'errors' => "This Issue already reported. You will receive a callabck shortly."]);
				Yii::app()->end();
			}    
            $userType	 = UserInfo::TYPE_CONSUMER;
            $model = new ServiceCallQueue();
            $model->scq_to_be_followed_up_with_value	 = ContactPhone::getPhoneNo($userId, $userType);
            $model->scq_follow_up_queue_type			 = $rpiModel->rpi_queue_id;//ServiceCallQueue::TYPE_REPORT_ISSUE;
            $issueDetails = ($rpiDetails != '')? ' - '.$rpiDetails:'';
            $reportIssueType   = ReportIssue::getType();
            $reportIssueArray  =    json_decode($reportIssueType, true);
            $issueType         =    $reportIssueArray[$rpiType];
            $model->scq_creation_comments				 = $issueType.' - '.$rpiModel->rpi_name.$issueDetails;
            $model->contactRequired						 = 1;
            $model->scq_to_be_followed_up_by_type        = ($rpiModel->rpi_team_id > 0)? 1: 2;
            $model->scq_to_be_followed_up_by_id          = $rpiModel->rpi_team_id;
            $model->scq_to_be_followed_up_with_entity_id = ContactProfile::getByEntityId($userId, $userType);
            $bkgCode									 = Booking::model()->getCodeById($bookingId);
            $model->scq_related_bkg_id					 = $bkgCode;
            $model->scq_additional_param                 =  json_encode(array('issueId' => $rpiModel->rpi_id));
            $model->scq_follow_up_priority               = $rpiModel->rpi_priority_level;
            $model->scq_priority_score                   = $rpiModel->rpi_priority_score;
            $returnSet							         = ServiceCallQueue::model()->create($model, $userType);
            
//          $returnFollowupArr							 = $followreturnSet->getData();
//			$followupId									 = $returnFollowupArr['followupId'];
//          if ($followupId != null)
//          {
//                $logComments			 = $model->scq_creation_comments . " | FollowUp-CODE : " . $returnFollowupArr['followupCode'] . " | QueueNo : " . $returnFollowupArr['queNo'] . " | WaitingTime : " . $returnFollowupArr['waitTime'];
//                $params['blg_ref_id']	 = $this->btl_bkg_id;
//                $params['current_user_type']     = (int) UserInfo::TYPE_CONSUMER;
//                BookingLog::model()->createLog($this->btl_bkg_id, $logComments, UserInfo::getInstance(), BookingLog::FOLLOWUP_CREATE, false, $params);
//           }
            $resultStatus = $returnSet->getStatus();
            if($resultStatus)
            {
                $view  = "reportIssueConfirm";
                $this->renderAuto($view, array('bkgId' => $bookingId,'returnSet' => json_encode($returnSet)), false, true);
                Yii::app()->end();
            }
            else
            {
               echo json_encode($returnSet);
               Yii::app()->end();
            }
            
        }
        
        $view  = "reportIssue";
        $this->renderAuto($view, array('bkgId' => $bookingId,'model' => $repIssueModel), false, true);
	}

	
}