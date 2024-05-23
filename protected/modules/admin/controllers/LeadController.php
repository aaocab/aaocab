<?php

use UserLog;
use LeadLog;

class LeadController extends Controller
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
				'actions'	 => array('REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			['allow', 'actions' => ['leadfollow'], 'roles' => ['leadAdd', 'leadEdit']],
			['allow', 'actions' => ['assigncsr'], 'roles' => ['leadAssigncsr']],
			['allow', 'actions' => ['converttobooking'], 'roles' => ['leadConverttobooking']],
			['allow', 'actions' => ['dailyleadreport'], 'roles' => ['leadReportDaily']],
			['allow', 'actions' => ['report'], 'roles' => ['leadList']],
			array('allow', 'actions'	 => array('uploadAudio', 'markread', 'locklead', 'unlocklead', 'addfollowup',
					'related', 'showcsr', 'showlog', 'markinvalid', 'converttolead', 'deactivate', 'autoAssign', 'leadPref',
					'mycallvnd', 'mycallnewvnd', 'MyCallVendorData', 'mycalldrv', 'mycalladvocacy', 'mycallvendoradvocacy', 'mycallvndapproval', 'mycalldispatch',
					'mycallfbg', 'mycall', 'addNote', 'currentlyAssignedDetails', 'closeCall', 'closeVndCall', 'closeDrvCall', 'closeNewVndCall',
					'CloseCallAll', 'CloseMyCallVendorData', 'MyCallAgent', 'CloseMyCallAgent',
					'closeAdvCall', 'closeDispatchCall', 'closeFbgCall', 'closeVendorAdvocacyCall', 'closeVndApprovalCall', 'mycallb2bpostpickup', 'MycallB2CBookingCancel', 'CloseB2CBookingCancel',
					'closeB2BPostPickupCall', 'al', 'nc', 'newcall', 'closeBarCall', 'mycallbar', 'mycallvendorpayment', 'closeVendorPaymentCall', 'CloseVendorDuePaymentCall', 'mycallvnddueamount',
					'Mycallgozonow', 'CloseGozonow', 'CloseAutoAssignLead', 'MycallAutoAssignLead', 'MycallDocumentapproval', 'closeDocumentApprovalCall', 'AllocatedLeadByTeam'),
				'users'		 => array('@'),
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
			$ri	 = array('get');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.post.assignLead.render', function () {

			return $this->renderJSON($this->assignLeadToCsr());
		});

		$this->onRest('req.post.unassignLead.render', function () {
			return $this->renderJSON($this->unassignLeadFromCsr());
		});

		$this->onRest('req.post.getAssignedLead.render', function () {
			return $this->renderJSON($this->getAssignedLead());
		});

		$this->onRest('req.post.uploadAudio.render', function () {
			return $this->renderJSON($this->uploadAudio());
		});

		$this->onRest('req.post.leadPref.render', function () {
			return $this->renderJSON($this->leadPref());
		});
	}

	public function loadModel($id)
	{
		$model = BookingTemp::model()->findByPk($id);
		if ($model === null)
		{
			throw new CHttpException(404, 'The requested page does not exist.');
		}
		return $model;
	}

	public function actionMarkread()
	{
		$bkgid = Yii::app()->request->getParam('bkg_id');
		if ($bkgid > 0)
		{
			$model = BookingTemp::model()->findByPk($bkgid);
			if (count($model) == 1)
			{
				$model->bkg_follow_up_status = 1;
				$model->bkg_follow_up_by	 = Yii::app()->user->getId();
				$model->bkg_follow_up_on	 = date("Y-m-d H:i:s");
				$model->save();
			}
			else
			{
				throw new Exception("Could not able to mark read. (" . json_encode($model->getErrors()) . ")");
			}
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			echo "true";
			Yii::app()->end();
		}
	}

	public function actionLocklead()
	{
		$bkgid = Yii::app()->request->getParam('bkg_id');
		if ($bkgid > 0)
		{
			$model = BookingTemp::model()->findByPk($bkgid);
			if (count($model) == 1)
			{
				$model->bkg_lock_timeout = date("Y-m-d H:i:s", strtotime("+30 minutes"));
				$model->bkg_locked_by	 = Yii::app()->user->getId();
				$model->scenario		 = 'lead_lock';
				if ($model->validate())
				{
					$model->save();
					$logDesc	 = "Lead Locked";
					$bkgid		 = $model->bkg_id;
					$desc		 = $logDesc;
					$userInfo	 = UserInfo::getInstance();
					LeadLog::model()->createLog($bkgid, $desc, $userInfo);
				}
			}
			else
			{
				throw new Exception("Could not able to locked Lead. (" . json_encode($model->getErrors()) . ")");
			}
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			echo "true";
			Yii::app()->end();
		}
	}

	public function actionUnlocklead()
	{
		$bkgid = Yii::app()->request->getParam('bkg_id');
		if ($bkgid > 0)
		{
			$model	 = BookingTemp::model()->findByPk($bkgid);
			$user	 = Yii::app()->user->getId();
			if (count($model) == 1 && $user == $model->bkg_locked_by)
			{
				// $model->bkg_lock_timeout = date("Y-m-d H:i:s", strtotime("+30 minutes"));
				$model->bkg_lock_timeout = null;
				$model->bkg_locked_by	 = null;
				$model->scenario		 = 'lead_unlock';
				if ($model->validate())
				{
					$model->save();
					$logDesc	 = "Lead Unlocked";
					$bkgid		 = $model->bkg_id;
					$desc		 = $logDesc;
					$userInfo	 = UserInfo::getInstance();
					LeadLog::model()->createLog($bkgid, $desc, $userInfo);
				}
			}
			else
			{
				throw new Exception("Could not unlocked lead. (" . json_encode($model->getErrors()) . ")");
			}
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			echo "true";
			Yii::app()->end();
		}
	}

	public function actionShowcsr()
	{
		$bkid	 = Yii::app()->request->getParam('booking_id'); // $_REQUEST['booking_id'];
		/* @var $model Admins */
		$model	 = new Admins('search');
		if (isset($_REQUEST['Admins']))
		{
			$model->attributes = Yii::app()->request->getParam('Admins');
		}
		$dataProvider							 = $model->fetchListByActivity();
		$dataProvider->getPagination()->params	 = array_filter($_REQUEST);
		$dataProvider->getSort()->params		 = array_filter($_REQUEST);
		$outputJs								 = Yii::app()->request->isAjaxRequest;
		$method									 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('showcsr', array('dataProvider' => $dataProvider, 'bkid' => $bkid, 'model' => $model), false, $outputJs);
	}

	public function actionAssigncsr()
	{
		$adminid = Yii::app()->request->getParam('csrid');
		$bkid	 = Yii::app()->request->getParam('bkid');
		$bkgid	 = BookingTemp::model()->assignCSR($bkid, $adminid);
		if ($bkgid)
		{
			$admin		 = Admins::model()->findByPk($adminid);
			$aname		 = $admin->adm_fname;
			//$bkgid = $success;
			$desc		 = "Lead assigned to $aname";
			$userInfo	 = UserInfo::getInstance();
			LeadLog::model()->createLog($bkgid, $desc, $userInfo);
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => true, 'dec' => $desc];
			echo CJSON::encode($data);
			Yii::app()->end();
		}
		$this->redirect('report');
	}

	public function actionAddfollowup()
	{

		$bkgid = Yii::app()->request->getParam('bkg_id');

		if (!isset($_POST['BookingTemp']))
		{

			if ($bkgid > 0)
			{
				$model	 = BookingTemp::model()->findByPk($bkgid);
				$isNew	 = false;

				$pagetitle = 'Add Followup';
			}
		}
		$model->scenario = 'followup';
		if (isset($_POST['BookingTemp']))
		{
			$arr								 = Yii::app()->request->getParam('BookingTemp');
			$model								 = BookingTemp::model()->findbyPk($arr['bkg_id']);
			$model->scenario					 = 'followup';
			$model->attributes					 = $arr;
			$model->bkg_follow_up_reminder_date	 = $arr['bkg_follow_up_reminder_date'];
			$model->bkg_follow_up_reminder_time	 = $arr['bkg_follow_up_reminder_time'];
			$model->new_follow_up_comment		 = $arr['new_follow_up_comment'];
			if ($model->bkg_follow_up_reminder_date != "" && $model->bkg_follow_up_reminder_time != "")
			{
				$date2							 = DateTimeFormat::DatePickerToDate($model->bkg_follow_up_reminder_date);
				$time2							 = DateTime::createFromFormat('h:i A', $model->bkg_follow_up_reminder_time)->format('H:i:00');
				$model->bkg_follow_up_reminder	 = $date2 . ' ' . $time2;
			}
			$model->bkg_follow_up_by = Yii::app()->user->getId();
			$model->bkg_follow_up_on = new CDbExpression('NOW()');
			if (!in_array($model->bkg_follow_up_status, array(0, 21, 20)))
			{
				$model->bkg_assigned_to = 0;
			}

			/////
			if ($model->new_follow_up_comment != '')
			{
				$new_remark		 = $model->new_follow_up_comment;
				$prev_remark	 = $model->bkg_follow_up_comment;
				$dt				 = date('Y-m-d H:i:s');
				$user			 = Yii::app()->user->getId();
				$status			 = $model->bkg_lead_source;
				$followupStatus	 = $model->bkg_follow_up_status;
				if ($new_remark != '')
				{
					if (is_string($prev_remark))
					{
						$newcomm = CJSON::decode($prev_remark);
						if ($prev_remark != '' && CJSON::decode($prev_remark) == '')
						{
							$newcomm = array(array(0 => $user, 1 => $model->bkg_create_date, 2 => $prev_remark, 3 => $followupStatus));
						}
					}
					else if (is_array($prev_remark))
					{
						$newcomm = $prev_remark;
					}
					if ($newcomm == false)
					{
						$newcomm = array();
					}
					array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $new_remark, 3 => $status, 4 => $followupStatus));
					$model->bkg_follow_up_comment = CJSON::encode($newcomm);
				}
			}

			$results = CActiveForm::validate($model);
			if ($results == '[]')
			{
				$model->bkg_user_last_updated_on = new CDbExpression('NOW()');
				$obj->success					 = true;
				if ($model->validate())
				{
					$model->save();
				}

				$logDesc = "Follow up added";

				$bkgid			 = $model->bkg_id;
				$remarkLead		 = $new_remark;
				$followstatus	 = $model->bkg_follow_up_status;
				$desc			 = $logDesc;
				$userInfo		 = UserInfo::getInstance();

				LeadLog::model()->createLog($bkgid, $desc, $userInfo, $remarkLead, $followstatus);
				$unbkid = Yii::app()->request->getParam('booking_id');
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				$obj->data = CJSON::decode($results);
				if ($unbkid > 0)
				{
					$obj->unbkid = $unbkid;
				}
				echo CJSON::encode($obj);
				Yii::app()->end();
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				echo true;
				Yii::app()->end();
			}
		}

		$this->pageTitle = $pagetitle;
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('add_followup', array('model' => $model, 'title1' => $pagetitle, 'isNew' => $isNew, 'unbkid' => $unbkid), FALSE, $outputJs);
	}

	public function actionLeadfollow()
	{

		$bkgid	 = Yii::app()->request->getParam('bkg_id');
		$unbkid	 = Yii::app()->request->getParam('booking_id');
		if (!isset($_POST['BookingTemp']))
		{
			if ($unbkid > 0)
			{
				$model = BookingTemp::model()->getLeadbyRefBookingid($unbkid);

				if ($model == '')
				{
					$model = new BookingTemp();
				}
				$bkgmodel	 = Booking::model()->findByPk($unbkid);
				$data		 = $model->attributes;
				$data1		 = $bkgmodel->attributes;
				unset($data1['bkg_id']);
				foreach ($data1 as $attr => $val)
				{
					if ($val == null || $val == '' || $attr == 'bkg_modified_on')
					{
						unset($data[$attr]);
						unset($data1[$attr]);
					}
					else
					{
						$model->setAttribute($attr, $val);
					}
				}
				if ($model->bkg_lead_source == '')
				{
					$model->bkg_lead_source = 9; //'Unverified Booking'
				}

				$model->bkg_ref_booking_id = $unbkid;
			}
			else
			{
				/**
				 * @var $model  BookingTemp
				 * */
				if ($bkgid > 0)
				{
					$model						 = BookingTemp::model()->findByPk($bkgid);
					$isNew						 = false;
					$model->bkg_ref_booking_id	 = $unbkid;
					$pagetitle					 = 'Follow up Lead';
				}
				else
				{
					$model		 = new BookingTemp();
					$isNew		 = true;
					$pagetitle	 = 'New Lead';
				}
			}
		}
		$model->scenario = 'lead_edit';
		if (isset($_POST['BookingTemp']))
		{
			$bkgmodel = Booking::model()->findByPk($unbkid);
			if ($bkgmodel != '' && $bkgmodel->bkg_status == 13)
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					echo json_encode(['success' => false, 'errors' => [], 'msg' => 'already converted to booking']);
					Yii::app()->end();
				}
			}
			$isNew	 = false;
			$model	 = new BookingTemp();
			$arr	 = Yii::app()->request->getParam('BookingTemp');
			if ($_POST['BookingTemp']['bkg_id'] != '')
			{
				$model = BookingTemp::model()->findbyPk($arr['bkg_id']);
			}
			$model->attributes	 = $arr;
			$obj				 = new stdClass();
			$obj->success		 = false;
			if ($model->bkg_id > 0)
			{
				$oldData = BookingTemp::model()->getDetailsbyId($model->bkg_id);
			}
			else
			{
				$isNew				 = true;
				$model->bkg_status	 = 1;

				$model->bkg_platform = Booking::Platform_Admin;
				if ($model->bkg_user_ip == '')
				{
					$model->bkg_user_ip		 = \Filter::getUserIP();
					$cityinfo				 = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
					$model->bkg_user_city	 = $cityinfo['city'];
					$model->bkg_user_country = $cityinfo['country'];
					$model->bkg_user_device	 = UserLog::model()->getDevice();
				}
				$model->bkg_create_date = new CDbExpression('NOW()');
			}

			if ($model->bkg_booking_type == '2' && $model->bkg_return_date_date != "" && $model->bkg_return_date_time != "")
			{
				$date1					 = DateTimeFormat::DatePickerToDate($model->bkg_return_date_date);
				$time1					 = date('H:i:00', strtotime($model->bkg_return_date_time));
				$model->bkg_return_date	 = $date1 . ' ' . $time1;
				$model->bkg_return_time	 = $time1;
			}
			if ($model->bkg_follow_up_reminder_date != "" && $model->bkg_follow_up_reminder_time != "")
			{
				$date2	 = DateTimeFormat::DatePickerToDate($model->bkg_follow_up_reminder_date);
				$time2	 = DateTime::createFromFormat('h:i A', $model->bkg_follow_up_reminder_time)->format('H:i:00');

				$model->bkg_follow_up_reminder = $date2 . ' ' . $time2;
			}
			$model->bkg_follow_up_by = Yii::app()->user->getId();
			$model->bkg_follow_up_on = new CDbExpression('NOW()');

			/////
			if ($model->new_follow_up_comment != '')
			{
				$new_remark	 = $model->new_follow_up_comment;
				$prev_remark = $model->bkg_follow_up_comment;
				$dt			 = date('Y-m-d H:i:s');
				$user		 = Yii::app()->user->getId();
				$status		 = $model->bkg_lead_source;

				$followupStatus = $model->bkg_follow_up_status;

				if ($new_remark != '')
				{
					if (is_string($prev_remark))
					{
						$newcomm = CJSON::decode($prev_remark);
						if ($prev_remark != '' && CJSON::decode($prev_remark) == '')
						{
							$newcomm = array(array(0 => $user, 1 => $model->bkg_create_date, 2 => $prev_remark, 3 => $followupStatus));
						}
					}
					else if (is_array($prev_remark))
					{
						$newcomm = $prev_remark;
					}
					if ($newcomm == false)
					{
						$newcomm = array();
					}
					array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $new_remark, 3 => $status, 4 => $followupStatus));
					$model->bkg_follow_up_comment = CJSON::encode($newcomm);
				}
			}
			////
			$model->scenario = 'lead_edit';
			$results		 = CActiveForm::validate($model, null, false);
			if ($results == '[]')
			{
				$model->bkg_user_last_updated_on = new CDbExpression('NOW()');
				$userInfo						 = UserInfo::getInstance();
				$model->bkg_admin_id			 = $userInfo->userId;
				$obj->success					 = true;
				if ($model->validate())
				{
					$model->save();
				}
				$changesForLog	 = '';
				$logDesc		 = "Lead Created:";
				if (!$isNew)
				{
					$newData		 = BookingTemp::model()->getDetailsbyId($model->bkg_id);
					$getDifference	 = array_diff_assoc($newData, $oldData);
					$changesForLog	 = $this->getModification($getDifference, 'log');
					$logDesc		 = "Lead Modified:";
				}

				$logDesc		 = $logDesc . $changesForLog;
				$bkgid			 = $model->bkg_id;
				$remarkLead		 = $new_remark;
				$followstatus	 = $model->bkg_follow_up_status;
				$desc			 = $logDesc;

				LeadLog::model()->createLog($bkgid, $desc, $userInfo, $remarkLead, $followstatus);
				$unbkid = Yii::app()->request->getParam('booking_id');
				if ($unbkid > 0)
				{
					$bkgmodel				 = Booking::model()->findByPk($unbkid);
					$oldModel				 = clone $bkgmodel;
					$bkgmodel->bkg_status	 = 13;
					$bkgmodel->scenario		 = 'updatestatus';

					$success = $bkgmodel->update();
					if ($success)
					{
						$bkgid		 = $bkgmodel->bkg_id;
						$desc		 = "Booking converted to Lead";
						$userInfo	 = UserInfo::getInstance();
						$eventid	 = BookingLog::BOOKING_CONVERTED_TO_LEAD;
						BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);
					}
					else
					{
						throw new Exception("Could not updated lead. (" . json_encode($bkgmodel->getErrors()) . ")");
					}
				}
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				$obj->data = CJSON::decode($results);
				if ($unbkid > 0)
				{
					$obj->unbkid = $unbkid;
				}
				echo CJSON::encode($obj);
				Yii::app()->end();
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				echo true;
				Yii::app()->end();
			}
		}


		if ($bkgid > 0)
		{
			if ($model->bkg_contact_no == '' && $model->bkg_log_phone != '')
			{
				$model->bkg_contact_no = preg_replace('/[^0-9\-]/', '', str_replace(' ', '', $model->bkg_log_phone));
			}
			if ($model->bkg_user_email == '' && $model->bkg_log_email != '')
			{
				$model->bkg_user_email = $model->bkg_log_email;
			}
			if ($model->bkg_lead_source == '')
			{
				$model->bkg_lead_source = 8; //'Incomplete booking';
			}
		}
		else
		{
			$model->bkg_booking_type = 1;
		}
		$this->pageTitle = $pagetitle;
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('lead_follow', array('model' => $model, 'title1' => $pagetitle, 'isNew' => $isNew, 'unbkid' => $unbkid), FALSE, $outputJs);
	}

	public function actionReport()
	{
		$tab					 = Yii::app()->request->getParam('tab'); //$_GET['tab'];
		$userid					 = Yii::app()->request->getParam('userid');
		$mycall					 = Yii::app()->request->getParam('mycall');
		$searchid				 = Yii::app()->request->getParam('searchid');
		$this->pageTitle		 = "Lead Report";
		$model					 = new BookingTemp();
		$model->bkg_create_date1 = date('Y-m-d', strtotime(' -1 day'));
		$model->bkg_create_date2 = date('Y-m-d');

		if (isset($_REQUEST['BookingTemp']))
		{
			$model->attributes					 = Yii::app()->request->getParam('BookingTemp');
			$model->bkg_create_date1			 = ($model->bkg_create_date1 == '') ? '' : DateTimeFormat::DatePickerToDate($model->bkg_create_date1);
			$model->bkg_create_date2			 = ($model->bkg_create_date2 == '') ? '' : DateTimeFormat::DatePickerToDate($model->bkg_create_date2);
			$model->bkg_pickup_date1			 = ($model->bkg_pickup_date1 == '') ? '' : DateTimeFormat::DatePickerToDate($model->bkg_pickup_date1);
			$model->bkg_pickup_date2			 = ($model->bkg_pickup_date2 == '') ? '' : DateTimeFormat::DatePickerToDate($model->bkg_pickup_date2);
			$model->bkg_follow_up_reminder_date1 = ($model->bkg_follow_up_reminder_date1 == '') ? '' : DateTimeFormat::DatePickerToDate($model->bkg_follow_up_reminder_date1);
		}
		if ($searchid != '')
		{
			$ids	 = BookingTemp::getRelatedIds($searchid);
			$leads	 = $searchid;
			if ($ids)
			{
				$leads .= "," . $ids;
			}
			$model->ids				 = $leads;
			$model->showAssigned	 = 1;
			$model->bkg_create_date1 = '';
			$model->bkg_create_date2 = '';
		}
		$params1 = array_filter($_GET + $_POST);
		/* @var $model Booking */
		/* @var $dataProvider CActiveDataProvider */
		if (isset($params1['BookingTemp']))
		{
			$params1['BookingTemp'] = array_filter($params1['BookingTemp']);
		}
		$dataProvider	 = [];
		$tabs			 = BookingTemp::getTabCategories();
		foreach ($tabs as $bid => $bval)
		{
			$model->bkg_lead_status								 = $bid;
			$dataProvider[$bid]['data']							 = $model->feedbackReport($userid, $mycall);
			$dataProvider[$bid]['label']						 = $bval;
			$dataProvider[$bid]['count']						 = $dataProvider[$bid]['data']->getTotalItemCount();
			$params1['tab']										 = $bid;
			$dataProvider[$bid]['data']->getPagination()->params = $params1;
			$dataProvider[$bid]['data']->getSort()->params		 = $params1;
		}

		/**
		 * This section is getting used for download report
		 */
		if (isset($_REQUEST['export_from2']) && isset($_REQUEST['export_to2']))
		{
			$fromDate		 = Yii::app()->request->getParam('export_from2');
			$toDate			 = Yii::app()->request->getParam('export_to2');
			$fromCity		 = Yii::app()->request->getParam('from_city');
			$toCity			 = Yii::app()->request->getParam('to_city');
			$logType		 = Yii::app()->request->getParam('hid_log_type');
			$leadsource		 = Yii::app()->request->getParam('bkg_lead_source_txt');
			$followUpStatus	 = Yii::app()->request->getParam('hid_follow_up_status');
			$keyword		 = Yii::app()->request->getParam('hid_keyword');
			$pickupDate1	 = Yii::app()->request->getParam('pickup_date1');
			$pickupDate2	 = Yii::app()->request->getParam('pickup_date2');

			////-- Booking date add  --///
			$bookingDate1	 = Yii::app()->request->getParam('bkg_create_date1');
			$bookingDate2	 = Yii::app()->request->getParam('bkg_create_date2');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"FeedbackReport_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "reportbooking" . date('YmdHi') . ".csv";
			//$foldername = PUBLIC_PATH;
			$foldername	 = Yii::app()->params['uploadPath'];
			// $foldername = 'D:\Exported';
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

			//------ start sql modify ---------//

			if ($bookingDate1 != '' && $bookingDate2 != '')
			{
				$where = " AND date(bkg_create_date) BETWEEN '$bookingDate1' AND '$bookingDate2'";
			}
			else
			{
				$where = " AND date(bkg_create_date) BETWEEN '$fromDate' AND '$toDate'";
			}

			if ($pickupDate1 != '' && $pickupDate2 != '')
			{
				$where = " AND date(bkg_pickup_date) BETWEEN '$bookingDate1' AND '$bookingDate2'";
			}
			else
			{
				$where = " AND date(bkg_pickup_date) BETWEEN '$fromDate' AND '$toDate'";
			}

			if ($fromCity != '' && $toCity != '')
			{
				$where .= " AND (`bkg_from_city_id`='$fromCity' AND `bkg_to_city_id`='$toCity')";
			}
			else if ($fromCity != '')
			{
				$where .= " AND `bkg_from_city_id`='$fromCity'";
			}
			else if ($toCity != '')
			{
				$where .= " AND `bkg_to_city_id`='$toCity'";
			}

			if ($logType != '')
			{
				$where .= " AND `bkg_log_type`='$logType'";
			}
			if ($leadsource != '')
			{
				$where .= " AND `bkg_lead_source`='$leadsource'";
			}
			if ($followUpStatus != '')
			{
				$where .= " AND `bkg_follow_up_status`='$followUpStatus'";
			}
			if ($keyword != '')
			{
				$where .= " AND (`bkg_user_email`='$keyword' || `bkg_log_phone`='$keyword')";
			}
			$having	 = ' HAVING minute_diff > 30';
			$sql	 = "SELECT bkg_id,vct.vct_label,c1.cty_name as from_city,c2.cty_name as to_city,bkg_pickup_date,bkg_create_date,bkg_booking_type,bkg_amount,bkg_log_type,bkg_lead_source,bkg_log_comment,bkg_log_phone,bkg_log_email,bkg_contact_no,bkg_user_email,TIMESTAMPDIFF(MINUTE, bkg_create_date,CURRENT_TIMESTAMP) as minute_diff
                    FROM booking_temp 
					LEFT JOIN cities c1 ON bkg_from_city_id=c1.cty_id 
					LEFT JOIN cities c2 ON bkg_to_city_id=c2.cty_id
					INNER JOIN svc_class_vhc_cat scv ON bkg_vehicle_type_id=scv.scv_id
					INNER JOIN vehicle_category vct ON vct.vct_id=scv.scv_vct_id
					WHERE bkg_active=1 " . $where . " AND ((bkg_lead_source IS NOT NULL AND bkg_lead_source<>'') OR (bkg_user_email <>'' OR bkg_contact_no <>'' ))";

			$rows	 = DBUtil::queryAll($sql . $having);
			$handle	 = fopen("php://output", 'w');

			fputcsv($handle, ['Lead ID', 'Cab Type', 'From City', 'To City', 'Pickup Date/Time', 'Booking Date/Time', 'Booking Type', 'Amount', 'Log Type', 'Log Comment', 'Log Phone', 'Log Email', 'Contact No', 'User Email']);
			foreach ($rows as $row)
			{
				unset($row['minute_diff']);
				$row['bkg_status']		 = $status[$row['bkg_status']];
				$row['bkg_booking_type'] = $bookingType[$row['bkg_booking_type']];
				$row1					 = array_values($row);
				fputcsv($handle, $row1);
			}
			fclose($handle);

			if (!$rows)
			{
				die('Could not take data backup: ' . mysql_error());
			}

			exit;
		}

		$outputJs = Yii::app()->request->isAjaxRequest;
		if ($outputJs && $tab != '')
		{
			$this->renderPartial('grid', array('status' => $tab, 'provider' => $dataProvider[$tab], 'dataProviders' => $dataProvider, 'model' => $model), false, true);
		}
		else
		{
			$this->render('report', array('tab' => $tab, 'dataProviders' => $dataProvider, 'model' => $model));
		}
	}

	public function actionShowlog()
	{
		$bkid	 = Yii::app()->request->getParam('booking_id') == null ? 0 : Yii::app()->request->getParam('booking_id');
		$hash	 = Yii::app()->request->getParam('hash');
		if ($hash != '' && $bkid > 0)
		{
			$bkgmodel	 = BookingTemp::model()->getUserbyId($bkid);
			$userleads	 = BookingTemp::model()->getLeadbyuserid($bkgmodel['bkg_user_id'], $bkgmodel['email'], $bkgmodel['bkg_contact_no']);
			$bkid		 = $userleads;
		}
		$logList = LeadLog::model()->getByBookingId($bkid);
		$this->renderPartial('showlog', array('lmodel' => $logList, 'hash' => $hash));
	}

	public function actionMarkinvalid()
	{

		$bkid	 = Yii::app()->request->getParam('bkg_id'); //$_POST['booking_id'];
		$reason	 = trim($_POST['bkreason']);
		if (isset($_POST['bk_id']))
		{
			$bk_id	 = Yii::app()->request->getParam('bk_id'); //$_POST['bk_id'];
			$bkgid	 = BookingTemp::model()->markInvalid($bk_id, $reason);
			if ($bkgid)
			{
				$desc		 = "Booking cancelled manually.(Reason: " . $reason . ")";
				$userInfo	 = UserInfo::getInstance();

				BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);
			}

			$tab = 2;
			$this->redirect(array('report', 'tab' => $tab));
		}
		$this->renderPartial('markinvalid', array('bkid' => $bkid));
	}

	public function getModification($diff, $user)
	{
		$msg = '';
		if (count($diff) > 0)
		{
			if ($diff['consumer_name'])
			{
				$msg .= ' Customer Name: ' . $diff['consumer_name'] . ',';
			}
			if ($diff['consumer_phone'])
			{
				$msg .= ' Customer Phone: ' . $diff['consumer_phone'] . ',';
			}
			if ($diff['bkg_user_email'])
			{
				$msg .= ' Customer Email: ' . $diff['bkg_user_email'] . ',';
			}
			if ($diff['consumer_alt_phone'])
			{
				$msg .= ' Alternate Phone: ' . $diff['consumer_alt_phone'] . ',';
			}
			if ($diff['route_name'])
			{
				$msg .= ' Route: ' . $diff['route_name'] . ',';
			}
			if ($diff['booking_type'])
			{
				$msg .= ' Booking Type: ' . $diff['booking_type'] . ',';
			}
			if ($diff['pick_date'])
			{
				$msg .= ' Pickup Date/Time: ' . $diff['pick_date'] . ',';
			}
			if ($diff['return_date'])
			{
				$msg .= ' Return Date/Time: ' . $diff['return_date'] . ',';
			}
			if ($diff['bkg_pickup_address'])
			{
				$msg .= ' Pickup Address: ' . $diff['bkg_pickup_address'] . ',';
			}
			if ($diff['bkg_drop_address'])
			{
				$msg .= ' Drop Address: ' . $diff['bkg_drop_address'] . ',';
			}
			if ($diff['bkg_additional_charge'])
			{
				$msg .= ' Additional Charge: ' . $diff['bkg_additional_charge'] . ',';
			}
			if ($diff['payable_amount'])
			{
				$msg .= ' Payable Amount: ' . $diff['payable_amount'] . ',';
			}
			if ($user != 'consumer')
			{
				if ($diff['bkg_instruction_to_driver_vendor'])
				{
					$msg .= ' Special Instruction: ' . $diff['bkg_instruction_to_driver_vendor'] . ',';
				}
			}
			if ($user == 'log')
			{
				if ($diff['bkg_vendor_amount'])
				{
					$msg .= ' Vendor Amount: ' . $diff['bkg_vendor_amount'] . ',';
				}
				if ($diff['bkg_invoice'])
				{
					$msg .= ' Invoice Requirement Changed,';
				}
			}
			$msg = rtrim($msg, ',');
		}
		return $msg;
	}

	public function actionConverttobooking()
	{
		if (isset($_REQUEST['BookingTemp']))
		{
			$success = false;
			$leadid	 = 0;
			$model	 = new BookingTemp();
			$arr	 = Yii::app()->request->getParam('BookingTemp');
			if ($arr['bkg_id'] > 0)
			{
				$model = BookingTemp::model()->findByPk($arr['bkg_id']);
			}
			$model->attributes		 = Yii::app()->request->getParam('BookingTemp');
			$model->bkg_user_device	 = UserLog::model()->getDevice();
			if ($model->new_follow_up_comment != '')
			{
				$new_remark				 = $model->new_follow_up_comment;
				$prev_remark			 = $model->bkg_follow_up_comment;
				$dt						 = date('Y-m-d H:i:s');
				$user					 = Yii::app()->user->getId();
				$model->bkg_user_id		 = $user;
				$model->bkg_user_ip		 = \Filter::getUserIP();
				$cityinfo				 = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
				$model->bkg_user_city	 = $cityinfo['city'];
				$model->bkg_user_country = $cityinfo['country'];

				$status = $model->bkg_lead_source;

				$followupStatus = $model->bkg_follow_up_status;

				if ($new_remark != '')
				{
					if (is_string($prev_remark))
					{
						$newcomm = CJSON::decode($prev_remark);
						if ($prev_remark != '' && CJSON::decode($prev_remark) == '')
						{
							$newcomm = array(array(0 => $user, 1 => $model->bkg_create_date, 2 => $prev_remark, 3 => $followupStatus));
						}
					}
					else if (is_array($prev_remark))
					{
						$newcomm = $prev_remark;
					}
					if ($newcomm == false)
					{
						$newcomm = array();
					}
					array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $new_remark, 3 => $status, 4 => $followupStatus));
					$model->bkg_follow_up_comment = CJSON::encode($newcomm);
				}
			}
			$model->scenario = 'lead_convert';
			$result			 = CActiveForm::validate($model);

			if ($result == '[]')
			{
				$model->bkg_id	 = ($model->bkg_id == "") ? null : $model->bkg_id;
				$model->save();
				$success		 = true;
				$leadid			 = $model->bkg_id;
			}
			else
			{
				throw new Exception("Could not coverted  lead. (" . json_encode($model->getErrors()) . ")");
			}
			echo CJSON::encode(['success' => $success, 'leadid' => $leadid]);
			Yii::app()->end();
		}
	}

	public function actionDailyLeadReport()
	{
		$this->pageTitle = "Daily Lead Report";
		$model			 = new LeadLog();
		$model->fromDate = date('Y-m-d');
		$model->toDate	 = date('Y-m-d');
		if (isset($_REQUEST['LeadLog']))
		{
			$model->attributes	 = $_REQUEST['LeadLog'];
			$model->executive	 = $_REQUEST['LeadLog']['executive'];
			$model->fromDate	 = $_REQUEST['LeadLog']['fromDate'];
			$model->toDate		 = $_REQUEST['LeadLog']['toDate'];
			if ($_REQUEST['fromDate'] != '')
			{
				$model->fromDate = $_REQUEST['fromDate'];
			}
			if ($_REQUEST['toDate'] != '')
			{
				$model->toDate = $_REQUEST['toDate'];
			}
		}
		$countReport	 = BookingSub::model()->findZonewiseBookingCount($model->fromDate, $model->toDate);
		$returnArr		 = $model->getDailyLeadReportCount();
		$dataProvider	 = $returnArr['dataprovider'];
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST), 'pagesize' => 50]);
		$this->render('dailyreport', ['model' => $model, 'dataProvider' => $dataProvider, 'countReports' => $countReport, 'createTable' => $returnArr['createTable'], 'confirmTable' => $returnArr['confirmTable'], 'leadTable' => $returnArr['leadTable'], 'bookingFollowupTable' => $returnArr['bookingFollowupTable']]);
	}

	public function actionRelated()
	{
		$model	 = new BookingTemp();
		$bkid	 = Yii::app()->request->getParam('bkg_id'); // $_REQUEST['booking_id'];
		if ($bkid)
		{
			$relatedLeads = BookingTemp::model()->getRelatedLeads($bkid);
		}
		$dataProvider	 = $relatedLeads;
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST), 'pagesize' => 50]);
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('related', array('model' => $model, 'dataProvider' => $dataProvider), false, $outputJs);
	}

	public function actionDeactivate()
	{
		$bkid	 = Yii::app()->request->getParam('bkg_id');
		$success = false;
		$bkgid	 = BookingTemp::model()->inactivateDuplicateLeadById($bkid);
		if ($bkgid && $bkgid == $bkid)
		{
			$success = true;
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			echo $success;
			Yii::app()->end();
		}
	}

	public function actionAutoAssign()
	{
		$csr				 = Yii::app()->user->getId();
		$newAccess			 = 1;
		$highValueAccess	 = 1;
		$unverifiedAccess	 = 1;
		$leadFresher		 = Yii::app()->user->checkAccess('LeadFresher');
		$leadSenior			 = Yii::app()->user->checkAccess('LeadSenior');
		if ($leadFresher)
		{
			$newAccess			 = 0;
			$unverifiedAccess	 = 0;
			$highValueAccess	 = 0;
		}
		if ($leadSenior)
		{
			$unverifiedAccess	 = 0;
			$highValueAccess	 = 0;
		}

		$row = BookingTemp::model()->getTopLead($csr, $unverifiedAccess, $newAccess, $highValueAccess);
		if (!$row)
		{
			Logger::pushTraceLogs();
		}
		$transaction = DBUtil::beginTransaction();
		try
		{
			if ($row['type'] == '1')
			{
				if ($row['csrRank'] <= 0)
				{
					/* @var $model Booking */
					$model							 = Booking::model()->findByPk($row['bkg_id']);
					$model->bkgTrail->bkg_assign_csr = $csr;
					$model->bkgTrail->save();
					$admin							 = Admins::model()->findByPk($csr);
					$aname							 = $admin->adm_fname;
					//$bkgid = $success;
					$desc							 = "CSR ($aname) Auto Assigned";
					BookingLog::model()->createLog($row['bkg_id'], $desc, UserInfo::model(), BookingLog::CSR_ASSIGN, false, false);
					BookingSub::assignRelatedIds($row['bkg_id'], $csr);
				}
				$url = Yii::app()->createUrl("admin/booking/list", ["searchid" => $row['bkg_id'], 'related' => 1]);
			}
			else
			{
				if ($row['csrRank'] <= 0)
				{
					/* @var $model BookingTemp */
					$bkgid = BookingTemp::model()->assignCSR($row['bkg_id'], $csr);
					if ($bkgid)
					{

						$admin		 = Admins::model()->findByPk($csr);
						$aname		 = $admin->adm_fname;
						//$bkgid = $success;
						$desc		 = "Lead assigned to $aname (Auto Assign)";
						$userInfo	 = UserInfo::getInstance();
						LeadLog::model()->createLog($bkgid, $desc, $userInfo);
					}
					BookingTemp::assignRelatedIds($bkgid, $csr);
				}
				$url = Yii::app()->createUrl("admin/lead/report", ["searchid" => $row['bkg_id']]);
			}
			Logger::trace("$desc \n URL: $url");
			echo json_encode(["url" => $url]);
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			DBUtil::rollbackTransaction($transaction);
			$returnset = ReturnSet::setException($e);
			echo json_encode($returnset);
		}
		Yii::app()->end();
	}

	/**
	 * 	This function is used for assigning leads to a csr
	 */
	public function assignLeadToCsr()
	{
		$csr				 = UserInfo::getUserId();
		$teamId				 = Teams::getMultipleTeamid($csr);
		$csr				 = Yii::app()->user->getId();
		$newAccess			 = 1;
		$highValueAccess	 = 1;
		$unverifiedAccess	 = 1;
		$leadFresher		 = Yii::app()->user->checkAccess('LeadFresher');
		$leadSenior			 = Yii::app()->user->checkAccess('LeadSenior');
		if ($leadFresher)
		{
			$newAccess			 = 0;
			$unverifiedAccess	 = 0;
			$highValueAccess	 = 0;
		}
		if ($leadSenior)
		{
			$unverifiedAccess	 = 0;
			$highValueAccess	 = 0;
		}
		foreach ($teamId as $teamId)
		{
			$returnSet = ServiceCallQueue::process($csr, $teamId['tea_id'], $unverifiedAccess, $newAccess, $highValueAccess);
			if ($returnSet->getStatus())
			{
				break;
			}
		}
		return $returnSet;
	}

	public function actionAl()
	{
		$returnSet = $this->getAssignedLead();
		if ($returnSet->getStatus())
		{
			$data = $returnSet->getData();
			echo $data->call_sync_id;
		}
		else
		{
			echo $returnSet->getMessage();
		}
	}

	public function actionNc()
	{
		$returnSet = $this->assignLeadToCsr();
		if ($returnSet->getStatus())
		{
			$data = $returnSet->getData();
			echo $data->call_sync_id;
		}
		else
		{
			echo $returnSet->getMessage();
		}
	}

	public function actionNewcall()
	{
		$csr = UserInfo::getUserId();
//		$returnSet	 = AssignLog::assignTopLeadContact($csr);
		print_r($returnSet);
//		$this->assignLeadToCsr();
	}

	public function getAssignedLead()
	{
		$csr		 = UserInfo::getUserId();
		$returnSet	 = AssignLog::getAssignedLead($csr);
		return $returnSet;
	}

	public function uploadAudio()
	{

		$callTypeArr		 = [1 => 'LD', 2 => 'EB', 3 => 'VND', 4 => 'DRV'];
		$returnSet			 = new ReturnSet();
		$adminId			 = UserInfo::getUserId();
		$process_sync_data	 = Yii::app()->request->getParam('data');
		$data				 = CJSON::decode($process_sync_data, true);
		$call_type			 = $data['call_type'];
		$call_id			 = $data['call_id'];
		$call_sync_id		 = $data['call_sync_id'];
		$call_duration		 = $data['call_duration'];
		try
		{
			$userInfo			 = UserInfo::getInstance();
			$userInfo->userId	 = UserInfo::getUserId();
			$userInfo->userType	 = UserInfo::getUserType();
			$csr				 = UserInfo::getUserId();

			if ($_FILES['file_audio']['name'] == '')
			{
				throw new Exception("No file for upload", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}
			$serverId	 = Config::getServerID();
			$DS			 = DIRECTORY_SEPARATOR;
			$rootpath	 = Yii::app()->basePath . $DS . 'doc';
			$path		 = $rootpath . $DS . $serverId . $DS . 'audio' . $DS . $callTypeArr[$call_type] . $DS . $csr . $DS . $call_id . $DS;
			if (!is_dir($path))
			{
				mkdir($path, 0755, true);
			}
			$subpath	 = $DS . $serverId . $DS . 'audio' . $DS . $callTypeArr[$call_type] . $DS . $csr . $DS . $call_id . $DS;
			$allowed	 = array('mp3', 'ogg', 'flac');
			$extension	 = pathinfo(str_replace(' ', '-', $_FILES['file_audio']['name']), PATHINFO_EXTENSION);
			$fileName	 = $callTypeArr[$call_type] . "-" . $call_id . "-" . date('YmdHis') . "." . str_replace(' ', '-', $_FILES['file_audio']['name']);
			if (!in_array(strtolower($extension), $allowed))
			{
				throw new Exception("Extension not matched. extension:" . strtolower($extension), ReturnSet::ERROR_INVALID_DATA);
			}
			if (move_uploaded_file($_FILES['file_audio']['tmp_name'], $path . $fileName))
			{
				$admin							 = Admins::model()->findByPk($adminId);
				$aname							 = $admin->adm_fname . ' ' . $admin->adm_lname;
				$updateCallStatus				 = CallStatus::getByCallSyncId($call_sync_id);
				$model							 = CallStatus::model()->findByPk($updateCallStatus);
				$model->cst_recording_file_name	 = $subpath . $fileName;
				$model->cst_status				 = 3;
				$model->cst_did					 = ceil($call_duration / 60);
				$model->cst_modified			 = DBUtil::getCurrentTime();
				$model->cst_agent_name			 = $aname;
				if ($model->save())
				{

					$returnSet->setStatus(true);
					$returnSet->setMessage("Successfully uploaded");
					$response->call_sync_id = $model->cst_id;
					$returnSet->setData($response);
				}
				else
				{
					throw new Exception(CJSON::encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
			}
			else
			{
				throw new Exception("Not uploaded.", ReturnSet::ERROR_INVALID_DATA);
			}
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $returnSet;
	}

	/*	 * @deprecated
	 * This is the core function for assigning lead to a csr
	 * @param type $csrRank
	 * @param type $refType
	 * @param type $refId
	 * @param type $csr
	 */

	public function assignLead($csrRank, $refType, $refId, $csr)
	{
		switch ((int) $refType)
		{
			case 1: //1-Lead (AlgRefId)
				$assignLogModel = AssignLog::assignLD($refId, $csrRank, $csr);

				$resultLD		 = BookingTemp::model()->getUserbyId($refId);
				$custUserId		 = $resultLD["bkg_user_id"];
				$custUserEmail	 = $resultLD["email"];
				$custUserPhone	 = $resultLD['bkg_contact_no'];

				$getRelatedLeadIds	 = BookingTemp::getRelatedLeadIds($custUserId, $custUserEmail, $custUserPhone);
				$getRelatedQuoteIds	 = Booking::getRelatedIds($custUserId, $custUserEmail, $custUserPhone);

				Booking::assignedIds($getRelatedQuoteIds, $csr, $refId);
				BookingTemp::assignedIds($getRelatedLeadIds, $csr, $refId);
				FollowUps::toggle($refId, $csr);
				$callStatusModel = CallStatus::model()->create($refId, 1, 91, $resultLD['bkg_contact_no'], 1, 1);
				break;

			case 2:  //2-Booking (AlgRefId)
				$assignLogModel = AssignLog::assignQT($refId, $csrRank, $csr);

				$resultQT		 = Booking::model()->getUserbyIdNew($refId);
				$custUserId		 = $resultQT["bkg_user_id"];
				$custUserEmail	 = $resultQT["bkg_user_email"];
				$custUserPhone	 = $resultQT['bkg_contact_no'];

				$getRelatedLeadIds	 = BookingTemp::getRelatedLeadIds($custUserId, $custUserEmail, $custUserPhone);
				$getRelatedQuoteIds	 = Booking::getRelatedIds($custUserId, $custUserEmail, $custUserPhone);

				Booking::assignedIds($getRelatedQuoteIds, $csr, $refId);
				BookingTemp::assignedIds($getRelatedLeadIds, $csr, $refId);
				FollowUps::toggle($refId, $csr);
				$callStatusModel = CallStatus::model()->create($refId, 2, 91, $resultQT['bkg_contact_no'], 1, 0);
				break;

			case 3: //1-FollowUp OR CallmeBack (AlgRefId)
				$callStatusModel = FollowUps::assign($refId, $csrRank, $csr);
				break;
		}

		return $callStatusModel;
	}

	public function assignedLead($type, $bkg_id = null, $csr)
	{
		$result = [];
		if ($type == 1)
		{
			$bkgmodel = Booking::model()->findByPk($bkg_id);
			if ($bkgmodel->bkgTrail->bkg_assign_csr == $csr)
			{
				$result	 = Booking::model()->getUserbyId($bkg_id);
				$refType = 2;
				$event	 = AssignLog::ASSIGNED_BOOKING_CSR;
			}
		}
		elseif ($type == 3)
		{
			# code...
		}
		else
		{
			$tempmodel = BookingTemp::model()->findByPk($bkg_id);
			if ($tempmodel->bkg_assigned_to == $csr)
			{
				$result	 = BookingTemp::model()->getUserbyId($bkg_id);
				$refType = 1;
				$event	 = AssignLog::ASSIGNED_LEAD_CSR;
			}
		}
		if ($result != [])
		{
			$getAssignTime				 = AssignLog::model()->getAssignTime($refType, $bkg_id, $csr, $event);
			$result["last_assign_time"]	 = $getAssignTime;
			$result						 = (object) $result;
		}

		return $result;
	}

	public function leadPref()
	{
		$returnSet = new ReturnSet();
		try
		{
			$countLead = ServiceCallQueue::getCountByCsr(UserInfo::getUserId());
			$returnSet->setStatus(true);
			$returnSet->setData($countLead);
		}
		catch (Exception $e)
		{
			Logger::exception($e);
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($e);
		}
		return $returnSet;
	}

	/**
	 * This function is used for rendering MyCall Page
	 */
	public function actionMycall()
	{
		$success	 = true;
		$csr		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csr);
		$leadID		 = $assignModel["scq_id"];
		$leadType	 = (int) $assignModel['scq_ref_type'];
		//Check for not assigned
		if (empty($leadID))
		{
			goto skip;
		}
		$refType	 = 0;
		$bkgstatus	 = 0;
		switch ($assignModel['scq_follow_up_queue_type'])
		{
			case 42:
			case 43:
			case 44:
			case 45:
			case 34:
			case 16:
			case 17:
			case 20:
			case 21:
			case 1: //Lead//Quoation/new booking
				$agentId = $assignModel['scq_agent_id'] != null ? $assignModel['scq_agent_id'] : 0;
				if ($assignModel['scq_related_lead_id'] != null)
				{
					$resultLD		 = BookingTemp::model()->getUserbyId($assignModel['scq_related_lead_id'], $agentId);
					$custUserId		 = $resultLD["bkg_user_id"];
					$custUserEmail	 = $resultLD["email"];
					$custUserphn	 = $resultLD["bkg_contact_no"];
					if ($resultLD["bkg_user_id"] == '' || $resultLD["bkg_user_id"] == NULL)
					{
						$userdata				 = BookingTemp::model()->getUserbyemailphone($custUserEmail, $custUserphn);
						$custUserId				 = $userdata["user_id"];
						$resultLD["bkg_user_id"] = $userdata["user_id"];
					}
				}
				else if ($assignModel['scq_related_bkg_id'] != null)
				{
					$resultQT		 = Booking::model()->getUserbyIdNew($assignModel['scq_related_bkg_id'], $agentId);
					$bkgstatus		 = $resultQT["bkg_status"];
					$custUserId		 = $resultQT["bkg_user_id"];
					$custUserEmail	 = $resultQT["bkg_user_email"];
					$custUserphn	 = $resultQT["bkg_contact_no"];
				}
				else
				{
					if ($assignModel['scq_to_be_followed_up_with_type'] == 1)
					{
						$arrProfile				 = ContactProfile::getEntityById($assignModel['scq_to_be_followed_up_with_value'], UserInfo::TYPE_CONSUMER);
						$result["contact_id"]	 = $assignModel['scq_to_be_followed_up_with_value'];
					}
					else
					{
						$cont					 = $assignModel['scq_to_be_followed_up_with_contact'] == null ? 0 : $assignModel['scq_to_be_followed_up_with_contact'];
						$arrProfile				 = ContactProfile::getEntityById($cont, UserInfo::TYPE_CONSUMER);
						$result["contact_id"]	 = $cont;
					}
					$custUserphn = '';
					if ($assignModel['scq_to_be_followed_up_with_type'] == 2)
					{
						$custUserphn = $assignModel['scq_to_be_followed_up_with_value'];
					}
					else
					{
						$custUserphn = ContactPhone::getContactNumber($result["contact_id"]);
					}

					$cModel			 = Contact::model()->findByPk($result["contact_id"]);
					$custUserEmail	 = ContactEmail::getPrimaryEmail($result["contact_id"]);
					if (!empty($arrProfile["id"]))
					{
						$custUserId = $arrProfile['id'];
					}
					$result["bkg_user_id"]		 = $custUserId;
					$userModel					 = Users::model()->findByPk($custUserId);
					$result["bkg_user_fname"]	 = $cModel->ctt_first_name;
					$result["bkg_user_lname"]	 = $cModel->ctt_last_name;
				}
				break;

			case 53:
			case 51:
			case 2: // Existing Booking
				$resultQT		 = Booking::model()->getUserbyIdNew($assignModel['scq_related_bkg_id']);
				$bkgstatus		 = $resultQT["bkg_status"];
				$custUserId		 = $resultQT["bkg_user_id"];
				$custUserEmail	 = $resultQT["bkg_user_email"];
				$custUserphn	 = $resultQT["bkg_contact_no"];
				break;
			case 3: //Vendor attachment
				$this->forward("mycallnewvnd", true);
				break;
			case 4: // Existing Vendor  
				$this->forward("mycallvnd", true);
				break;
			case 41: // Existing Vendor  
				$this->forward("MycallB2CBookingCancel", true);
				break;

			case 39:
			case 36:
			case 5: // customer advocacy
				$this->forward("mycalladvocacy", true);
				break;

			case 6: // Driver Call back   
				$this->forward("mycalldrv", true);
				break;
			case 7: // Payments Followups
				$resultQT		 = Booking::model()->getUserbyIdNew($assignModel['scq_related_bkg_id']);
				$bkgstatus		 = $resultQT["bkg_status"];
				$custUserId		 = $resultQT["bkg_user_id"];
				$custUserEmail	 = $resultQT["bkg_user_email"];
				$custUserphn	 = $resultQT["bkg_contact_no"];
				break;

			case 11: // Plenatity Dispute  
				$this->forward("mycallvnd", true);
				break;
			case 35: // Price Issue booking
			case 24: // UpSell
			case 12: // UpSell
				$resultQT		 = Booking::model()->getUserbyIdNew($assignModel['scq_related_bkg_id']);
				$bkgstatus		 = $resultQT["bkg_status"];
				$custUserId		 = $resultQT["bkg_user_id"];
				$custUserEmail	 = $resultQT["bkg_user_email"];
				$custUserphn	 = $resultQT["bkg_contact_no"];
				break;
			case 40:
			case 37:
			case 13: // Dispacth
				$this->forward("mycallvendoradvocacy", true);
				break;
			case 14: // Dispacth
				$this->forward("mycalldispatch", true);
				break;
			case 15:
				$this->forward("mycallvndapproval", true);
				break;
			case 52:
			case 18: // B2B POST Pickup
				$this->forward("mycallb2bpostpickup", true);
				break;
			case 33:
			case 32:
			case 19: // BAR 
				$this->forward("mycallbar", true);
				break;
			case 22: // FBG
				$this->forward("mycallfbg", true);
				break;
			case 23: // Vendor Payment Request
				$this->forward("mycallvendorpayment", true);
				break;
			case 26: // vendor 	callBack
				$this->forward("mycallVendorData", true);

			case 27: // Gozo Now   
				$this->forward("mycallgozonow", true);
				break;

			case 38:
			case 28: // Agent Call Back
				$this->forward("MyCallAgent", true);
				break;
			case 29: // Auto Lead Followup
				$this->forward("MycallAutoAssignLead", true);
				break;
			case 30:
			case 31:
				$this->forward("MycallDocumentapproval", true);
				break;
			case 46: // Vendor Due Amount 
				$this->forward("Mycallvnddueamount", true);
				break;
		}
		if (in_array($bkgstatus, [2, 3, 5, 6, 7, 9]))
		{
			$teamid == 5;
		}
		$assignedLDLeads	 = BookingTemp::getActiveAssignedLeads($csr);
		$assignedQTLeads	 = ((int) $custUserId > 0) ? Booking::getActiveAssignedQuotes($custUserId, $csr) : [];
		$pendingLeads		 = BookingTemp::getCountPendingLeads($csr);
		$totalTripComplete	 = ((int) $custUserId > 0) ? Booking::getTotalTripComplete($custUserId) : [];
		$userDriverRating	 = ((int) $custUserId > 0) ? Booking::getUserDriverRating($custUserId) : [];
		$totalEnquiry		 = ((int) $custUserId > 0) ? Booking::getTotalEnquiry($custUserId) : [];
		$totalTireUsed		 = ((int) $custUserId > 0) ? Booking::getCountTireUsed($custUserId) : [];
		$cityTraveled		 = ((int) $custUserId > 0) ? Booking::getCityTraveled($custUserId) : [];
		$tab				 = Yii::app()->request->getParam('tab');
		$formHide			 = Yii::app()->request->getParam('formHide', '');
		$tabFilter			 = Yii::app()->request->getParam('tabFilter', '');
		$related			 = Yii::app()->request->getParam('related', 0);
		$model1				 = new Booking();
		$bookingStatus		 = Booking::getMyCallTabCategories();
		$leadStatus			 = $bookingStatus;
		$tabFilter			 = $leadStatus;
		$tabFilter			 = [10, 20, 30, 40, 70];
		if ($tab != '')
		{
			$tabFilter	 = [];
			$tabFilter	 = [$tab];
		}
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$dataProvider		 = [];
		$model1->bkg_status	 = null;
		if ($teamid == 5)
		{
			$statusCount = $model1->getStatusCount($csr, "2, 3, 5, 6, 7, 9", $custUserId, 'mycall');
		}
		else
		{
			$statusCount = $model1->getStatusCount($csr, '', $custUserId, 'mycall');
		}
		$statusCount[10] = $statusCount[2] + $statusCount[3] + $statusCount[5];
		$statusCount[20] = $statusCount[1] + $statusCount[15];
		$statusCount[30] = $pendingLeads[0]['cntleads'];
		$statusCount[40] = $statusCount[6] + $statusCount[7] + $statusCount[9];
		$statusCount[70] = ($leadType == 3) ? 1 : ServiceCallQueue::getCountByRefId($leadID);
		$bid			 = Yii::app()->request->getParam('bid', 10);
		skip:
		$this->renderAuto('mycall', array(
			'model'				 => $assignModel,
			'leadType'			 => $leadType,
			'result'			 => $result,
			'resultQT'			 => $resultQT,
			'resultLD'			 => $resultLD,
			'followUpModel'		 => $assignModel,
			'followUpData'		 => $fwpData,
			'oldNotes'			 => $oldNotes,
			'tab'				 => $tab,
			'leadStatus'		 => $leadStatus,
			'statusCount'		 => $statusCount,
			'dataProvider'		 => $dataProvider,
			'model1'			 => $model1,
			'tabFilter'			 => $tabFilter,
			'formHide'			 => $formHide,
			'lbid'				 => $bid,
			'success'			 => $success,
			'userDriverRating'	 => $userDriverRating,
			'assignedLDLeads'	 => $assignedLDLeads,
			'assignedQTLeads'	 => $assignedQTLeads,
			'totalEnquiry'		 => $totalEnquiry,
			'totalTripComplete'	 => $totalTripComplete,
			'totalTireUsed'		 => $totalTireUsed,
			'cityTraveled'		 => $cityTraveled,
			'cc'				 => $this,
			'teamid'			 => $teamid,
			'csr'				 => $csr), false, $outputJs);
	}

	public function actionMycallvnd()
	{
		$csr		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csr);
		$vendorid	 = 0;
		if ($assignModel['scq_to_be_followed_up_with_entity_type'] == 1)
		{
			$entityType	 = UserInfo::TYPE_CONSUMER;
			$contactId	 = ContactProfile::getByEntityId($assignModel['scq_to_be_followed_up_with_entity_id'], $entityType);
			$row		 = ContactProfile::getEntityById($contactId, UserInfo::TYPE_VENDOR);
			$vendorid	 = $row['id'];
		}
		else if ($assignModel['scq_to_be_followed_up_with_entity_type'] == 3)
		{
			$entityType	 = UserInfo::TYPE_DRIVER;
			$contactId	 = ContactProfile::getByEntityId($assignModel['scq_to_be_followed_up_with_entity_id'], $entityType);
			$row		 = ContactProfile::getEntityById($contactId, UserInfo::TYPE_VENDOR);
			$vendorid	 = $row['id'];
		}
		else
		{
			$vendorid = $assignModel['scq_to_be_followed_up_with_entity_id'];
		}

		$refVendorModel = Vendors::model()->mergedVendorId($vendorid);
		if ($refVendorModel->vnd_active == 0 || $vendorid == 0 || $vendorid == '' || $vendorid == null)
		{
			$remarks = "CBR closed by system";
			ServiceCallQueue::updateStatus($assignModel['scq_id'], 10, 0, $remarks);
			$this->autoAllocatedLead($csr, 1);
			$this->redirect(array('lead/mycall'));
			exit();
		}
		else
		{
			$vendorid = $refVendorModel->vnd_id;
		}

		$vndModel					 = Vendors::model()->getViewDetailbyId($vendorid);
		$tabFilter					 = [1 => 'Driver', 2 => 'Cabs'];
		$drvmodel					 = new Drivers();
		$vhcmmodel					 = new Vehicles();
		$qry						 = [];
		$drvmodel->drv_vendor_id	 = $vendorid;
		$vhcmmodel->vhc_vendor_id1	 = $vendorid;
		$params1					 = array_filter($_GET + $_POST);
		$dataProviders[1]			 = $drvmodel->getList($qry);
		$dataProviders[2]			 = $vhcmmodel->getList($qry);
		$tabFilterVal[]				 = [];
		foreach ($tabFilter as $i => $tabs)
		{
			$tabFilterVal[$i]			 = ['label' => $tabs];
			$tabFilterVal[$i]['count']	 = $dataProviders[$i]->getTotalItemCount();
			$dataProviders[$i]->setSort(['params' => array_filter($_REQUEST)]);
			$dataProviders[$i]->setPagination(['params' => array_filter($_REQUEST)]);
		}
		unset($tabFilterVal[0]);
		$tab		 = Yii::app()->request->getParam('tab');
		$formHide	 = Yii::app()->request->getParam('formHide', '');
		$tabFilter	 = Yii::app()->request->getParam('tabFilter', '');
		$related	 = Yii::app()->request->getParam('related', 0);
		/** @var Booking $bkgmodel */
		$bkgmodel	 = new Booking();
		$leadStatus	 = $bkgmodel->getActiveBookingStatus();
		if ($tab != '')
		{
			$tabFilter	 = [];
			$tabFilter	 = [$tab];
		}
		$outputJs					 = Yii::app()->request->isAjaxRequest;
		$bkgmodel->bcb_vendor_id	 = $vendorid;
		$bkgmodel->bkg_pickup_date1	 = '';
		$bkgmodel->bkg_pickup_date2	 = '';
		$bkgmodel->bkg_create_date1	 = date('Y-m-d', strtotime('-6 MONTH'));
		$bkgmodel->bkg_create_date2	 = date('Y-m-d');
		$statusCount				 = $bkgmodel->getStatusCount(0, '3,5,6');
		foreach ($statusCount as $k => $v)
		{
			$tabFilterVal[$k] = ['label' => $leadStatus[$k], 'count' => $v | '0'];
		}
		$this->render('mycallVnd', array(
			'result'		 => $vndModel,
			'tabFilter'		 => $tabFilter,
			'tabFilterVal'	 => $tabFilterVal,
			'model'			 => $assignModel,
			'drvmodel'		 => $drvmodel,
			'vhcmmodel'		 => $vhcmmodel,
			'vndid'			 => $vendorid,
			'statusCount'	 => $statusCount,
			'dataProviders'	 => $dataProviders,
				), false, $outputJs);
	}

	public function actionMycalldrv()
	{
		$success	 = true;
		$csr		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csr);
		$driverid	 = 0;
		if ($assignModel['scq_to_be_followed_up_with_entity_type'] == 1)
		{
			$entityType	 = UserInfo::TYPE_CONSUMER;
			$contactId	 = ContactProfile::getByEntityId($assignModel['scq_to_be_followed_up_with_entity_id'], $entityType);
			$row		 = ContactProfile::getEntityById($contactId, UserInfo::TYPE_DRIVER);
			$driverid	 = $row['id'];
		}
		else if ($assignModel['scq_to_be_followed_up_with_entity_type'] == 2)
		{
			$entityType	 = UserInfo::TYPE_VENDOR;
			$contactId	 = ContactProfile::getByEntityId($assignModel['scq_to_be_followed_up_with_entity_id'], $entityType);
			$row		 = ContactProfile::getEntityById($contactId, UserInfo::TYPE_DRIVER);
			$driverid	 = $row['id'];
		}
		else
		{
			$driverid = $assignModel['scq_to_be_followed_up_with_entity_id'];
		}

		$refDriverModel = Drivers::model()->mergedDriverId($driverid);
		if ($refDriverModel->drv_active == 0 || $driverid == 0 || $driverid == '' || $driverid == null)
		{
			$remarks = "CBR closed by system";
			ServiceCallQueue::updateStatus($assignModel['scq_id'], 10, 0, $remarks);
			$this->autoAllocatedLead($csr, 1);
			$this->redirect(array('lead/mycall'));
			exit();
		}
		else
		{
			$driverid = $refDriverModel->drv_id;
		}

		$drvModel	 = Drivers::getDetailsById($driverid);
		$tabFilter	 = [1 => 'Drivers'];
		$drvmodel	 = new Drivers();

		$qry				 = [];
		$drvmodel->drv_code	 = $drvModel['drv_code'];
		$params1			 = array_filter($_GET + $_POST);
		$dataProviders[1]	 = $drvmodel->getList($qry);
		$tabFilterVal[]		 = [];
		foreach ($tabFilter as $i => $tabs)
		{
			$tabFilterVal[$i]			 = ['label' => $tabs];
			$tabFilterVal[$i]['count']	 = $dataProviders[$i]->getTotalItemCount();
			$dataProviders[$i]->setSort(['params' => array_filter($_REQUEST)]);
			$dataProviders[$i]->setPagination(['params' => array_filter($_REQUEST)]);
		}
		unset($tabFilterVal[0]);
		$tab		 = Yii::app()->request->getParam('tab');
		$formHide	 = Yii::app()->request->getParam('formHide', '');
		$tabFilter	 = Yii::app()->request->getParam('tabFilter', '');
		$related	 = Yii::app()->request->getParam('related', 0);
		/** @var Booking $bkgmodel */
		$bkgmodel	 = new Booking();
		$leadStatus	 = $bkgmodel->getActiveBookingStatus();
		if ($tab != '')
		{
			$tabFilter	 = [];
			$tabFilter	 = [$tab];
		}
		$outputJs					 = Yii::app()->request->isAjaxRequest;
		$bkgmodel->bcb_driver_id	 = $driverid;
		$bkgmodel->bkg_pickup_date1	 = '';
		$bkgmodel->bkg_pickup_date2	 = '';
		$bkgmodel->bkg_create_date1	 = date('Y-m-d', strtotime('-6 MONTH'));
		$bkgmodel->bkg_create_date2	 = date('Y-m-d');
		$statusCount				 = $bkgmodel->getStatusCount(0, '3,5,6');
		foreach ($statusCount as $k => $v)
		{
			$tabFilterVal[$k] = ['label' => $leadStatus[$k], 'count' => $v | '0'];
		}
		$this->render('mycallDrv', array(
			'result'		 => $drvModel,
			'tabFilter'		 => $tabFilter,
			'tabFilterVal'	 => $tabFilterVal,
			'model'			 => $assignModel,
			'drvmodel'		 => $drvmodel,
			'drvid'			 => $driverid,
			'statusCount'	 => $statusCount,
			'dataProviders'	 => $dataProviders,
				), false, $outputJs);
	}

	public function actionMycallnewvnd()
	{
		$csr			 = UserInfo::getUserId();
		$this->pageTitle = 'CMB::New Vendor Attachment';
		$assignModel	 = ServiceCallQueue::fetchAssignLeads($csr);
		$refType		 = $assignModel["scq_follow_up_queue_type"];
		$this->pageTitle = $refType == 3 ? 'CMB::New Vendor Attachment' : "CMB::Vendor Approval";
		$cModel			 = Contact::model()->findByPk($assignModel["scq_to_be_followed_up_with_contact"]);
		$this->renderAuto('mycallNewVnd', array(
			'assignModel'	 => $assignModel,
			'cModel'		 => $cModel,
			'dataProvider'	 => $dataProvider,
			'csr'			 => $csr), false, $outputJs);
	}

	public function actionCurrentlyAssignedDetails()
	{

		$bkgid	 = Yii::app()->request->getParam('bkg_id') == null ? 0 : Yii::app()->request->getParam('bkg_id');
		$model	 = $bkgid > 0 ? BookingTemp::model()->getDetailsbyId($bkgid) : [];
		//$this->pageTitle = $pagetitle;	
		$this->renderPartial('currentlyAssignedDetails', array('model' => $model, 'title1' => $pagetitle));
	}

	/**
	 * This function is used for close call for a new vendor CMB
	 * @param type $csrid
	 */
	public function actionCloseNewVndCall($csrid = 0)
	{
		$success	 = true;
		$errormsg	 = '';
		$csrid		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csrid);
		if (!$assignModel)
		{
			$success	 = false;
			$errormsg	 = "No leads are avaliable for you.";
			goto skipAll;
		}
		if ($assignModel['scq_follow_up_queue_type'] == 3)
		{
			$assignedFollowUpCount = 1;

			if ($assignModel['scq_status'] == 2 || $assignModel['scq_status'] == 3)
			{
				$assignedFollowUpCount = 0;
			}
			if ($assignedFollowUpCount > 0)
			{
				$count = ServiceCallQueue::countScq($csrid);
				if ($count > 1)
				{
					$row = ServiceCallQueue::fetchAssignLeadsByStatus($csrid, 1);
//					ServiceCallQueue::UnAssignment($csrid, $row['scq_id']);
				}
				Logger::trace("cannot close call");
				$success	 = false;
				$errormsg	 = "You have a pending follow up. Please close all pending follow ups.";
				goto skipAll;
			}
		}

		$vndModel			 = new Vendors('search');
		$vndModel->vnd_id	 = $assignModel['scq_to_be_followed_up_with_entity_id'];
		$count				 = $vndModel->listtoapprove('count');
		if ($count > 0)
		{
			Logger::trace("cannot close call");
			$success	 = false;
			$errormsg	 = "Cannot close call. The vendor is still in pending state.";
			goto skipAll;
		}
		if ($assignModel['scq_id'] > 0 && $assignedFollowUpCount == 0)
		{
			$scqIds	 = ServiceCallQueue::getOriginatingfollowup($assignModel['scq_id']);
			$comment = $scqIds > 0 ? ".[call rescheduled > $scqIds]" : ".[call closed]";
			$remarks = $assignModel['scq_disposition_comments'] . " " . $comment;
			ServiceCallQueue::updateState($assignModel['scq_id'], $csrid, 2, $remarks);
			$this->autoAllocatedLead($csrid);
		}
		skipAll:
		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	/**
	 * This function is used for close call for a vendor
	 */
	public function actionCloseVndCall()
	{
		$success	 = true;
		$errormsg	 = '';
		$csrid		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csrid);

		if (!$assignModel)
		{
			$success	 = false;
			$errormsg	 = "No leads are avaliable for you.";
			goto skipAll;
		}
		$assignedFollowUpCount = 1;
		if ($assignModel['scq_status'] == 2 || $assignModel['scq_status'] == 3)
		{
			$assignedFollowUpCount = 0;
		}
		if ($assignedFollowUpCount > 0)
		{
			$count = ServiceCallQueue::countScq($csrid);
			if ($count > 1)
			{
				$row = ServiceCallQueue::fetchAssignLeadsByStatus($csrid, 1);
//				ServiceCallQueue::UnAssignment($csrid, $row['scq_id']);
			}
			$success	 = false;
			$errormsg	 = "You have a pending follow up. Please close all pending follow ups.";
			goto skipAll;
		}

		if ($assignModel['scq_id'] > 0 && $assignedFollowUpCount == 0)
		{
			$scqIds	 = ServiceCallQueue::getOriginatingfollowup($assignModel['scq_id']);
			$comment = $scqIds > 0 ? ".[call rescheduled > $scqIds]" : ".[call closed]";
			$remarks = $assignModel['scq_disposition_comments'] . " " . $comment;
			ServiceCallQueue::updateState($assignModel['scq_id'], $csrid, 2, $remarks);
			$this->autoAllocatedLead($csrid);
		}
		skipAll:

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	/**
	 * This function is used for close call for a drviver
	 */
	public function actionCloseDrvCall()
	{
		$success	 = true;
		$errormsg	 = '';
		$csrid		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csrid);

		if (!$assignModel)
		{
			$success	 = false;
			$errormsg	 = "No leads are avaliable for you.";
			goto skipAll;
		}
		$assignedFollowUpCount = 1;
		if ($assignModel['scq_status'] == 2 || $assignModel['scq_status'] == 3)
		{
			$assignedFollowUpCount = 0;
		}
		if ($assignedFollowUpCount > 0)
		{
			$count = ServiceCallQueue::countScq($csrid);
			if ($count > 1)
			{
				$row = ServiceCallQueue::fetchAssignLeadsByStatus($csrid, 1);
//				ServiceCallQueue::UnAssignment($csrid, $row['scq_id']);
			}
			$success	 = false;
			$errormsg	 = "You have a pending follow up. Please close all pending follow ups.";
			goto skipAll;
		}
		if ($assignModel['scq_id'] > 0 && $assignedFollowUpCount == 0)
		{
			$scqIds	 = ServiceCallQueue::getOriginatingfollowup($assignModel['scq_id']);
			$comment = $scqIds > 0 ? ".[call rescheduled > $scqIds]" : ".[call closed]";
			$remarks = $assignModel['scq_disposition_comments'] . " " . $comment;
			ServiceCallQueue::updateState($assignModel['scq_id'], $csrid, 2, $remarks);
			$this->autoAllocatedLead($csrid);
		}
		skipAll:

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	/**
	 * This function is used for close call for a csr
	 * @param type $userid
	 * @param type $csrid
	 * @param type $bkgid
	 */
	public function actionCloseCall($userid, $csrid, $bkgid)
	{
		$success	 = true;
		$errormsg	 = '';
		$assignModel = ServiceCallQueue::fetchAssignLeads($csrid);
		if ($userid > 0)
		{
			$assignedQT = Booking::getActiveAssignedQuotes($userid, $csrid);
		}
		else
		{
			$assignedQT = [];
		}
		$assignedLD		 = BookingTemp::getActiveAssignedLeads($csrid);
		$cntassignedQT	 = 0;
		$cntassignedLD	 = count($assignedLD);

		$assignedFollowUpCount = ServiceCallQueue::getCountByCsrId($csrid, $assignModel['scq_ref_type'], $bkgid);
		if ($assignModel['scq_status'] == 2)
		{
			$cntassignedQT	 = 0;
			$cntassignedLD	 = 0;
		}
		Logger::trace("userid : $userid :: csrid : $csrid :: bkgid : $bkgid");
		if ($cntassignedQT > 0 || $cntassignedLD > 0 || $assignedFollowUpCount > 0)
		{
			$count = ServiceCallQueue::countScq($csrid);
			if ($count > 1)
			{
				$row = ServiceCallQueue::fetchAssignLeadsByStatus($csrid, 1);
				ServiceCallQueue::UnAssignment($csrid, $row['scq_id']);
			}
			Logger::trace("cannot clase call");
			$success	 = false;
			$errormsg	 = "You have a pending quote/lead/follow up. Please close all pending quote / lead / follow ups.";
			goto skipAll;
		}
		if ($assignModel['scq_id'] > 0)
		{
			$jsonDecode		 = json_decode($assignModel['scq_additional_param']);
			$bookingTempsIds = $jsonDecode->bookingTempReleated;
			$bookingTempsIds = $assignModel['scq_related_lead_id'] != null ? $bookingTempsIds . "," . $assignModel['scq_related_lead_id'] : $bookingTempsIds;
			if ($bookingTempsIds != null)
			{
				$bookingTempsIds			 = ltrim($bookingTempsIds, ",");
				$bookingTempsIds			 = rtrim($bookingTempsIds, ",");
				$getAllActiveAssignedLeads	 = BookingTemp::getAllActiveAssignedLeads($bookingTempsIds);
				BookingTemp::markReadLead($getAllActiveAssignedLeads);
			}
			$scqIds	 = ServiceCallQueue::getOriginatingfollowup($assignModel['scq_id']);
			$comment = $scqIds > 0 ? ".[call rescheduled > $scqIds]" : ".[call closed]";
			$remarks = $assignModel['scq_disposition_comments'] . " " . $comment;
			ServiceCallQueue:: updateState($assignModel['scq_id'], $csrid, 2, $remarks);
			$this->autoAllocatedLead($csrid);
		}

		skipAll:

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	public function actionCloseCallAll($csrid, $bkgid)
	{
		$success	 = false;
		$errormsg	 = '';
		$status		 = 1;

		$assignlogModel = AssignLog::model()->find('alg_ref_id=:bkg_id AND alg_status=:status', ['bkg_id' => $bkgid, 'status' => $status]);
		if ($assignlogModel != '' || $assignlogModel == NULL)
		{
			$assignlogModel->alg_status	 = 0;
			$assignlogModel->save();
			$success					 = true;
		}

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	/**
	 * 	This function is used for unassigning leads to a csr
	 */
	public function unassignLeadFromCsr()
	{
		$csr		 = UserInfo::getUserId();
		$returnSet	 = ServiceCallQueue::processUnAssignment($csr);
		\Sentry\captureMessage(json_encode($returnSet), null);
		return $returnSet;
	}

	/**
	 * This function is used for rendering actionMycalladvocacy Page
	 */
	public function actionMycalladvocacy()
	{
		$this->pageTitle = "Gozocabs - Customer Advocacy Lead  ";
		$success		 = true;
		$csr			 = UserInfo::getUserId();
		$assignModel	 = ServiceCallQueue::fetchAssignLeads($csr);
		$leadID			 = $assignModel["scq_id"];
		$leadType		 = (int) $assignModel['scq_ref_type'];

		//Check for not assigned
		if (empty($leadID))
		{
			goto skip;
		}
		$refType			 = 0;
		$bkgstatus			 = 0;
		$resultQT			 = Booking::model()->getUserbyIdNew($assignModel['scq_related_bkg_id']);
		$bkgstatus			 = $resultQT["bkg_status"];
		$custUserId			 = $resultQT["bkg_user_id"];
		$custUserEmail		 = $resultQT["bkg_user_email"];
		$custUserphn		 = $resultQT["bkg_contact_no"];
		$totalTripComplete	 = ((int) $custUserId > 0) ? Booking::getTotalTripComplete($custUserId) : [];
		$userDriverRating	 = ((int) $custUserId > 0) ? Booking::getUserDriverRating($custUserId) : [];
		$totalEnquiry		 = ((int) $custUserId > 0) ? Booking::getTotalEnquiry($custUserId) : [];
		$totalTireUsed		 = ((int) $custUserId > 0) ? Booking::getCountTireUsed($custUserId) : [];
		$cityTraveled		 = ((int) $custUserId > 0) ? Booking::getCityTraveled($custUserId) : [];

		$tab		 = Yii::app()->request->getParam('tab');
		$formHide	 = Yii::app()->request->getParam('formHide', '');
		$tabFilter	 = Yii::app()->request->getParam('tabFilter', '');
		$related	 = Yii::app()->request->getParam('related', 0);

		$model1			 = new Booking();
		$bookingStatus	 = [
			80	 => 'Booking Details',
			70	 => 'Follow Up'
		];
		;
		$leadStatus		 = $bookingStatus;
		$tabFilter		 = $leadStatus;
		$tabFilter		 = [70];
		if ($tab != '')
		{
			$tabFilter	 = [];
			$tabFilter	 = [$tab];
		}
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$dataProvider		 = [];
		$model1->bkg_status	 = null;
		$statusCount		 = $model1->getStatusCount($csr, '', $custUserId, 'mycall');
		$statusCount [70]	 = ($leadType == 3) ? 1 : ServiceCallQueue::getCountByRefId($leadID);
		$bid				 = Yii::app()->request->getParam('bid', 10);

		skip:
		$this->renderAuto('mycalladvocacy', array(
			'model'				 => $assignModel,
			'leadType'			 => $leadType,
			'result'			 => $result,
			'resultQT'			 => $resultQT,
			'followUpModel'		 => $assignModel,
			'tab'				 => $tab,
			'leadStatus'		 => $leadStatus,
			'statusCount'		 => $statusCount,
			'dataProvider'		 => $dataProvider,
			'model1'			 => $model1,
			'tabFilter'			 => $tabFilter,
			'formHide'			 => $formHide,
			'lbid'				 => $bid,
			'success'			 => $success,
			'userDriverRating'	 => $userDriverRating,
			'totalEnquiry'		 => $totalEnquiry,
			'totalTripComplete'	 => $totalTripComplete,
			'totalTireUsed'		 => $totalTireUsed,
			'cityTraveled'		 => $cityTraveled,
			'cc'				 => $this,
			'csr'				 => $csr), false, $outputJs);
	}

	/**
	 * This function is used for close call for a customer advocacy
	 */
	public function actionCloseAdvCall()
	{
		$success	 = true;
		$errormsg	 = '';
		$csrid		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csrid);

		if (!$assignModel)
		{
			$success	 = false;
			$errormsg	 = "No leads are avaliable for you.";
			goto skipAll;
		}
		$assignedFollowUpCount = 1;
		if ($assignModel ['scq_status'] == 2 || $assignModel ['scq_status'] == 3)
		{
			$assignedFollowUpCount = 0;
		}
		if ($assignedFollowUpCount > 0)
		{
			$count = ServiceCallQueue::countScq($csrid);
			if ($count > 1)
			{
				$row = ServiceCallQueue::fetchAssignLeadsByStatus($csrid, 1);
//				ServiceCallQueue ::UnAssignment($csrid, $row['scq_id']);
			}
			$success	 = false;
			$errormsg	 = "You have a pending follow up. Please close all pending follow ups.";
			goto skipAll;
		}
		if ($assignModel ['scq_id'] > 0 && $assignedFollowUpCount == 0)
		{
			$scqIds	 = ServiceCallQueue::getOriginatingfollowup($assignModel['scq_id']);
			$comment = $scqIds > 0 ? ".[call rescheduled > $scqIds]" : ".[call closed]";
			$remarks = $assignModel['scq_disposition_comments'] . " " . $comment;
			ServiceCallQueue:: updateState($assignModel['scq_id'], $csrid, 2, $remarks);
			$this->autoAllocatedLead($csrid);
		}
		skipAll:

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	/**
	 * This function is used for rendering dispatch Page
	 */
	public function actionMycalldispatch()
	{
		$this->pageTitle = "Gozocabs - Dispatch Lead  ";
		$success		 = true;
		$csr			 = UserInfo::getUserId();
		$assignModel	 = ServiceCallQueue::fetchAssignLeads($csr);
		$leadID			 = $assignModel["scq_id"];
		$leadType		 = (int) $assignModel['scq_ref_type'];

		//Check for not assigned
		if (empty($leadID))
		{
			goto skip;
		}
		$refType			 = 0;
		$bkgstatus			 = 0;
		$resultQT			 = Booking::model()->getUserbyIdNew($assignModel['scq_related_bkg_id']);
		$bkgstatus			 = $resultQT["bkg_status"];
		$custUserId			 = $resultQT["bkg_user_id"];
		$custUserEmail		 = $resultQT["bkg_user_email"];
		$custUserphn		 = $resultQT["bkg_contact_no"];
		$totalTripComplete	 = ((int) $custUserId > 0) ? Booking::getTotalTripComplete($custUserId) : [];
		$userDriverRating	 = ((int) $custUserId > 0) ? Booking::getUserDriverRating($custUserId) : [];
		$totalEnquiry		 = ((int) $custUserId > 0) ? Booking::getTotalEnquiry($custUserId) : [];
		$totalTireUsed		 = ((int) $custUserId > 0) ? Booking::getCountTireUsed($custUserId) : [];
		$cityTraveled		 = ((int) $custUserId > 0) ? Booking::getCityTraveled($custUserId) : [];
		$tab				 = Yii::app()->request->getParam('tab');
		$formHide			 = Yii::app()->request->getParam('formHide', '');
		$tabFilter			 = Yii::app()->request->getParam('tabFilter', '');
		$related			 = Yii::app()->request->getParam('related', 0);

		$model1			 = new Booking();
		$bookingStatus	 = [
			80	 => 'Booking Details',
			70	 => 'Follow Up',
		];
		;
		$leadStatus		 = $bookingStatus;
		$tabFilter		 = $leadStatus;
		$tabFilter		 = [70];
		if ($tab != '')
		{
			$tabFilter	 = [];
			$tabFilter	 = [$tab];
		}
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$dataProvider		 = [];
		$model1->bkg_status	 = null;
		$statusCount		 = $model1->getStatusCount($csr, '', $custUserId, 'mycall');
		$statusCount [70]	 = ($leadType == 3) ? 1 : ServiceCallQueue::getCountByRefId($leadID);
		$bid				 = Yii::app()->request->getParam('bid', 10);

		skip:
		$this->renderAuto('mycalldispatch', array(
			'model'				 => $assignModel,
			'leadType'			 => $leadType,
			'result'			 => $result,
			'resultQT'			 => $resultQT,
			'followUpModel'		 => $assignModel,
			'tab'				 => $tab,
			'leadStatus'		 => $leadStatus,
			'statusCount'		 => $statusCount,
			'dataProvider'		 => $dataProvider,
			'model1'			 => $model1,
			'tabFilter'			 => $tabFilter,
			'formHide'			 => $formHide,
			'lbid'				 => $bid,
			'success'			 => $success,
			'userDriverRating'	 => $userDriverRating,
			'totalEnquiry'		 => $totalEnquiry,
			'totalTripComplete'	 => $totalTripComplete,
			'totalTireUsed'		 => $totalTireUsed,
			'cityTraveled'		 => $cityTraveled,
			'cc'				 => $this,
			'csr'				 => $csr), false, $outputJs);
	}

	/**
	 * This function is used for close call for a Dispatch
	 */
	public function actionCloseDispatchCall()
	{
		$success	 = true;
		$errormsg	 = '';
		$csrid		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csrid);

		if (!$assignModel)
		{
			$success	 = false;
			$errormsg	 = "No leads are avaliable for you.";
			goto skipAll;
		}
		$assignedFollowUpCount = 1;
		if ($assignModel ['scq_status'] == 2 || $assignModel ['scq_status'] == 3)
		{
			$assignedFollowUpCount = 0;
		}
		if ($assignedFollowUpCount > 0)
		{
			$count = ServiceCallQueue::countScq($csrid);
			if ($count > 1)
			{
				$row = ServiceCallQueue::fetchAssignLeadsByStatus($csrid, 1);
//				ServiceCallQueue ::UnAssignment($csrid, $row['scq_id']);
			}
			$success	 = false;
			$errormsg	 = "You have a pending follow up. Please close all pending follow ups.";
			goto skipAll;
		}
		if ($assignModel ['scq_id'] > 0 && $assignedFollowUpCount == 0)
		{
			$scqIds	 = ServiceCallQueue::getOriginatingfollowup($assignModel['scq_id']);
			$comment = $scqIds > 0 ? ".[call rescheduled > $scqIds]" : ".[call closed]";
			$remarks = $assignModel['scq_disposition_comments'] . " " . $comment;
			ServiceCallQueue:: updateState($assignModel['scq_id'], $csrid, 2, $remarks);
			$this->autoAllocatedLead($csrid);
		}
		skipAll:

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	/**
	 * This function is used for rendering Vendor Advocacy Page
	 */
	public function actionMycallvendoradvocacy()
	{
		$this->pageTitle = "Gozocabs - Vendor Advocacy Lead  ";
		$success		 = true;
		$csr			 = UserInfo::getUserId();
		$assignModel	 = ServiceCallQueue::fetchAssignLeads($csr);
		$leadID			 = $assignModel["scq_id"];
		$leadType		 = (int) $assignModel['scq_ref_type'];

		//Check for not assigned
		if (empty($leadID))
		{
			goto skip;
		}
		$refType			 = 0;
		$bkgstatus			 = 0;
		$resultQT			 = Booking::model()->getUserbyIdNew($assignModel['scq_related_bkg_id']);
		$bkgstatus			 = $resultQT["bkg_status"];
		$custUserId			 = $resultQT["bkg_user_id"];
		$custUserEmail		 = $resultQT["bkg_user_email"];
		$custUserphn		 = $resultQT["bkg_contact_no"];
		$totalTripComplete	 = ((int) $custUserId > 0) ? Booking::getTotalTripComplete($custUserId) : [];
		$userDriverRating	 = ((int) $custUserId > 0) ? Booking::getUserDriverRating($custUserId) : [];
		$totalEnquiry		 = ((int) $custUserId > 0) ? Booking::getTotalEnquiry($custUserId) : [];
		$totalTireUsed		 = ((int) $custUserId > 0) ? Booking::getCountTireUsed($custUserId) : [];
		$cityTraveled		 = ((int) $custUserId > 0) ? Booking::getCityTraveled($custUserId) : [];
		$tab				 = Yii::app()->request->getParam('tab');
		$formHide			 = Yii::app()->request->getParam('formHide', '');
		$tabFilter			 = Yii::app()->request->getParam('tabFilter', '');
		$related			 = Yii::app()->request->getParam('related', 0);

		$model1			 = new Booking();
		$bookingStatus	 = [
			80	 => 'Booking Details',
			70	 => 'Follow Up',
		];
		$leadStatus		 = $bookingStatus;
		$tabFilter		 = $leadStatus;
		$tabFilter		 = [70];
		if ($tab != '')
		{
			$tabFilter	 = [];
			$tabFilter	 = [$tab];
		}
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$dataProvider		 = [];
		$model1->bkg_status	 = null;
		$statusCount		 = $model1->getStatusCount($csr, '', $custUserId, 'mycall');
		$statusCount [70]	 = ($leadType == 3) ? 1 : ServiceCallQueue::getCountByRefId($leadID);
		$bid				 = Yii::app()->request->getParam('bid', 10);

		skip:
		$this->renderAuto('mycallvendoradvocacy', array(
			'model'				 => $assignModel,
			'leadType'			 => $leadType,
			'result'			 => $result,
			'resultQT'			 => $resultQT,
			'followUpModel'		 => $assignModel,
			'tab'				 => $tab,
			'leadStatus'		 => $leadStatus,
			'statusCount'		 => $statusCount,
			'dataProvider'		 => $dataProvider,
			'model1'			 => $model1,
			'tabFilter'			 => $tabFilter,
			'formHide'			 => $formHide,
			'lbid'				 => $bid,
			'success'			 => $success,
			'userDriverRating'	 => $userDriverRating,
			'totalEnquiry'		 => $totalEnquiry,
			'totalTripComplete'	 => $totalTripComplete,
			'totalTireUsed'		 => $totalTireUsed,
			'cityTraveled'		 => $cityTraveled,
			'cc'				 => $this,
			'csr'				 => $csr), false, $outputJs);
	}

	/**
	 * This function is used for close call for a Vendor Advocacy
	 */
	public function actionCloseVendorAdvocacyCall()
	{
		$success	 = true;
		$errormsg	 = '';
		$csrid		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csrid);

		if (!$assignModel)
		{
			$success	 = false;
			$errormsg	 = "No leads are avaliable for you.";
			goto skipAll;
		}
		$assignedFollowUpCount = 1;
		if ($assignModel ['scq_status'] == 2 || $assignModel ['scq_status'] == 3)
		{
			$assignedFollowUpCount = 0;
		}
		if ($assignedFollowUpCount > 0)
		{
			$count = ServiceCallQueue::countScq($csrid);
			if ($count > 1)
			{
				$row = ServiceCallQueue::fetchAssignLeadsByStatus($csrid, 1);
//				ServiceCallQueue ::UnAssignment($csrid, $row['scq_id']);
			}
			$success	 = false;
			$errormsg	 = "You have a pending follow up. Please close all pending follow ups.";
			goto skipAll;
		}
		if ($assignModel ['scq_id'] > 0 && $assignedFollowUpCount == 0)
		{
			$scqIds	 = ServiceCallQueue::getOriginatingfollowup($assignModel['scq_id']);
			$comment = $scqIds > 0 ? ".[call rescheduled > $scqIds]" : ".[call closed]";
			$remarks = $assignModel['scq_disposition_comments'] . " " . $comment;
			ServiceCallQueue:: updateState($assignModel['scq_id'], $csrid, 2, $remarks);
			$this->autoAllocatedLead($csrid);
		}
		skipAll:

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	public function actionMycallvndapproval()
	{
		$csr			 = UserInfo::getUserId();
		$assignModel	 = ServiceCallQueue::fetchAssignLeads($csr);
		$refType		 = $assignModel["scq_follow_up_queue_type"];
		$this->pageTitle = "CMB::Vendor Approval";
		$cModel			 = Contact::model()->findByPk($assignModel["scq_to_be_followed_up_with_contact"]);
		$this->renderAuto('mycallVndApproval', array(
			'assignModel'	 => $assignModel,
			'cModel'		 => $cModel,
			'csr'			 => $csr), false, $outputJs);
	}

	public function actioncloseVndApprovalCall()
	{
		$success	 = true;
		$errormsg	 = '';
		$csrid		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csrid);

		if (!$assignModel)
		{
			$success	 = false;
			$errormsg	 = "No leads are avaliable for you.";
			goto skipAll;
		}
		$assignedFollowUpCount = 1;
		if ($assignModel ['scq_status'] == 2 || $assignModel ['scq_status'] == 3)
		{
			$assignedFollowUpCount = 0;
		}
		if ($assignedFollowUpCount > 0)
		{
			$count = ServiceCallQueue::countScq($csrid);
			if ($count > 1)
			{
				$row = ServiceCallQueue::fetchAssignLeadsByStatus($csrid, 1);
//				ServiceCallQueue ::UnAssignment($csrid, $row['scq_id']);
			}
			$success	 = false;
			$errormsg	 = "You have a pending follow up. Please close all pending follow ups.";
			goto skipAll;
		}
		if ($assignModel ['scq_id'] > 0 && $assignedFollowUpCount == 0)
		{
			$scqIds	 = ServiceCallQueue::getOriginatingfollowup($assignModel['scq_id']);
			$comment = $scqIds > 0 ? ".[call rescheduled > $scqIds]" : ".[call closed]";
			$remarks = $assignModel['scq_disposition_comments'] . " " . $comment;
			ServiceCallQueue:: updateState($assignModel['scq_id'], $csrid, 2, $remarks);
			$this->autoAllocatedLead($csrid);
		}
		skipAll:

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	/**
	 * This function is used for rendering B2B Post pickup Page
	 */
	public function actionMycallB2BPostPickup()
	{
		$this->pageTitle = "Gozocabs - B2B Post pickup ";
		$success		 = true;
		$csr			 = UserInfo::getUserId();
		$assignModel	 = ServiceCallQueue::fetchAssignLeads($csr);
		$leadID			 = $assignModel["scq_id"];
		$leadType		 = (int) $assignModel['scq_ref_type'];

		//Check for not assigned
		if (empty($leadID))
		{
			goto skip;
		}
		$refType			 = 0;
		$bkgstatus			 = 0;
		$resultQT			 = Booking::model()->getUserbyIdNew($assignModel['scq_related_bkg_id']);
		$bkgstatus			 = $resultQT["bkg_status"];
		$custUserId			 = $resultQT["bkg_user_id"];
		$custUserEmail		 = $resultQT["bkg_user_email"];
		$custUserphn		 = $resultQT["bkg_contact_no"];
		$totalTripComplete	 = ((int) $custUserId > 0) ? Booking::getTotalTripComplete($custUserId) : [];
		$userDriverRating	 = ((int) $custUserId > 0) ? Booking::getUserDriverRating($custUserId) : [];
		$totalEnquiry		 = ((int) $custUserId > 0) ? Booking::getTotalEnquiry($custUserId) : [];
		$totalTireUsed		 = ((int) $custUserId > 0) ? Booking::getCountTireUsed($custUserId) : [];
		$cityTraveled		 = ((int) $custUserId > 0) ? Booking::getCityTraveled($custUserId) : [];
		$tab				 = Yii::app()->request->getParam('tab');
		$formHide			 = Yii::app()->request->getParam('formHide', '');
		$tabFilter			 = Yii::app()->request->getParam('tabFilter', '');
		$related			 = Yii::app()->request->getParam('related', 0);

		$model1			 = new Booking();
		$bookingStatus	 = [
			80	 => 'Booking Details',
			70	 => 'Follow Up',
		];
		$leadStatus		 = $bookingStatus;
		$tabFilter		 = $leadStatus;
		$tabFilter		 = [70];
		if ($tab != '')
		{
			$tabFilter	 = [];
			$tabFilter	 = [$tab];
		}
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$dataProvider		 = [];
		$model1->bkg_status	 = null;
		$statusCount		 = $model1->getStatusCount($csr, '', $custUserId, 'mycall');
		$statusCount [70]	 = ($leadType == 3) ? 1 : ServiceCallQueue::getCountByRefId($leadID);
		$bid				 = Yii::app()->request->getParam('bid', 10);

		skip:
		$this->renderAuto('mycallb2bpostpickup', array(
			'model'				 => $assignModel,
			'leadType'			 => $leadType,
			'result'			 => $result,
			'resultQT'			 => $resultQT,
			'followUpModel'		 => $assignModel,
			'tab'				 => $tab,
			'leadStatus'		 => $leadStatus,
			'statusCount'		 => $statusCount,
			'dataProvider'		 => $dataProvider,
			'model1'			 => $model1,
			'tabFilter'			 => $tabFilter,
			'formHide'			 => $formHide,
			'lbid'				 => $bid,
			'success'			 => $success,
			'userDriverRating'	 => $userDriverRating,
			'totalEnquiry'		 => $totalEnquiry,
			'totalTripComplete'	 => $totalTripComplete,
			'totalTireUsed'		 => $totalTireUsed,
			'cityTraveled'		 => $cityTraveled,
			'cc'				 => $this,
			'csr'				 => $csr), false, $outputJs);
	}

	/**
	 * This function is used for close call for a B2B Post pickup
	 */
	public function actionCloseB2BPostPickupCall()
	{
		$success	 = true;
		$errormsg	 = '';
		$csrid		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csrid);

		if (!$assignModel)
		{
			$success	 = false;
			$errormsg	 = "No leads are avaliable for you.";
			goto skipAll;
		}
		$assignedFollowUpCount = 1;
		if ($assignModel ['scq_status'] == 2 || $assignModel ['scq_status'] == 3)
		{
			$assignedFollowUpCount = 0;
		}
		if ($assignedFollowUpCount > 0)
		{
			$count = ServiceCallQueue::countScq($csrid);
			if ($count > 1)
			{
				$row = ServiceCallQueue::fetchAssignLeadsByStatus($csrid, 1);
//				ServiceCallQueue ::UnAssignment($csrid, $row['scq_id']);
			}
			$success	 = false;
			$errormsg	 = "You have a pending follow up. Please close all pending follow ups.";
			goto skipAll;
		}
		if ($assignModel ['scq_id'] > 0 && $assignedFollowUpCount == 0)
		{
			$scqIds	 = ServiceCallQueue::getOriginatingfollowup($assignModel['scq_id']);
			$comment = $scqIds > 0 ? ".[call rescheduled > $scqIds]" : ".[call closed]";
			$remarks = $assignModel['scq_disposition_comments'] . " " . $comment;
			ServiceCallQueue:: updateState($assignModel['scq_id'], $csrid, 2, $remarks);
			$this->autoAllocatedLead($csrid);
		}
		skipAll:

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	/**
	 * This function is used for rendering BAR Page
	 */
	public function actionMycallbar()
	{
		$this->pageTitle = "Gozocabs - Booking At Risk(BAR)  ";
		$success		 = true;
		$csr			 = UserInfo::getUserId();
		$assignModel	 = ServiceCallQueue::fetchAssignLeads($csr);
		$leadID			 = $assignModel["scq_id"];
		$leadType		 = (int) $assignModel['scq_ref_type'];

		//Check for not assigned
		if (empty($leadID))
		{
			goto skip;
		}
		$refType			 = 0;
		$bkgstatus			 = 0;
		$resultQT			 = Booking::model()->getUserbyIdNew($assignModel['scq_related_bkg_id']);
		$bkgstatus			 = $resultQT["bkg_status"];
		$custUserId			 = $resultQT["bkg_user_id"];
		$custUserEmail		 = $resultQT["bkg_user_email"];
		$custUserphn		 = $resultQT["bkg_contact_no"];
		$totalTripComplete	 = ((int) $custUserId > 0) ? Booking::getTotalTripComplete($custUserId) : [];
		$userDriverRating	 = ((int) $custUserId > 0) ? Booking::getUserDriverRating($custUserId) : [];
		$totalEnquiry		 = ((int) $custUserId > 0) ? Booking::getTotalEnquiry($custUserId) : [];
		$totalTireUsed		 = ((int) $custUserId > 0) ? Booking::getCountTireUsed($custUserId) : [];
		$cityTraveled		 = ((int) $custUserId > 0) ? Booking::getCityTraveled($custUserId) : [];
		$tab				 = Yii::app()->request->getParam('tab');
		$formHide			 = Yii::app()->request->getParam('formHide', '');
		$tabFilter			 = Yii::app()->request->getParam('tabFilter', '');
		$related			 = Yii::app()->request->getParam('related', 0);
		$model1				 = new Booking();
		$bookingStatus		 = [
			80	 => 'Booking Details',
			70	 => 'Follow Up',
		];
		$leadStatus			 = $bookingStatus;
		$tabFilter			 = $leadStatus;
		$tabFilter			 = [70];
		if ($tab != '')
		{
			$tabFilter	 = [];
			$tabFilter	 = [$tab];
		}
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$dataProvider		 = [];
		$model1->bkg_status	 = null;
		$statusCount		 = $model1->getStatusCount($csr, '', $custUserId, 'mycall');
		$statusCount [70]	 = ($leadType == 3) ? 1 : ServiceCallQueue::getCountByRefId($leadID);
		$bid				 = Yii::app()->request->getParam('bid', 10);

		skip:
		$this->renderAuto('mycallbar', array(
			'model'				 => $assignModel,
			'leadType'			 => $leadType,
			'result'			 => $result,
			'resultQT'			 => $resultQT,
			'followUpModel'		 => $assignModel,
			'tab'				 => $tab,
			'leadStatus'		 => $leadStatus,
			'statusCount'		 => $statusCount,
			'dataProvider'		 => $dataProvider,
			'model1'			 => $model1,
			'tabFilter'			 => $tabFilter,
			'formHide'			 => $formHide,
			'lbid'				 => $bid,
			'success'			 => $success,
			'userDriverRating'	 => $userDriverRating,
			'totalEnquiry'		 => $totalEnquiry,
			'totalTripComplete'	 => $totalTripComplete,
			'totalTireUsed'		 => $totalTireUsed,
			'cityTraveled'		 => $cityTraveled,
			'cc'				 => $this,
			'csr'				 => $csr), false, $outputJs);
	}

	/**
	 * This function is used for close call for a BAR
	 */
	public function actionCloseBarCall()
	{
		$success	 = true;
		$errormsg	 = '';
		$csrid		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csrid);

		if (!$assignModel)
		{
			$success	 = false;
			$errormsg	 = "No leads are avaliable for you.";
			goto skipAll;
		}
		$assignedFollowUpCount = 1;
		if ($assignModel ['scq_status'] == 2 || $assignModel ['scq_status'] == 3)
		{
			$assignedFollowUpCount = 0;
		}
		if ($assignedFollowUpCount > 0)
		{
			$count = ServiceCallQueue::countScq($csrid);
			if ($count > 1)
			{
//				$row = ServiceCallQueue::fetchAssignLeadsByStatus($csrid, 1);
				//ServiceCallQueue::UnAssignment($csrid, $row['scq_id']);
			}
			$success	 = false;
			$errormsg	 = "You have a pending follow up. Please close all pending follow ups.";
			goto skipAll;
		}
		if ($assignModel['scq_id'] > 0 && $assignedFollowUpCount == 0)
		{
			$scqList = ServiceCallQueue::fetchLeads($csrid);
			foreach ($scqList as $scq)
			{
				$scqIds	 = ServiceCallQueue::getOriginatingfollowup($scq['scq_id']);
				$comment = $scqIds > 0 ? ".[call rescheduled > $scqIds]" : ".[call closed]";
				$remarks = $scq['scq_disposition_comments'] . " " . $comment;
				ServiceCallQueue::updateState($scq['scq_id'], $csrid, 2, $remarks);
			}
			$this->autoAllocatedLead($csrid);
		}
		skipAll:

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	/**
	 * 	This function is used for auto  assigning leads to a csr if he/she is clock in
	 */
	public function autoAllocatedLead($csr, $isAllowed = 0)
	{
		$returnSet	 = new ReturnSet();
		$model		 = AdminProfiles::model()->getByAdminID($csr);
		if ($model->adp_auto_allocated == 1 || $isAllowed == 1)
		{
			try
			{
				$newAccess			 = 1;
				$highValueAccess	 = 1;
				$unverifiedAccess	 = 1;
				$leadFresher		 = Yii::app()->user->checkAccess('LeadFresher');
				$leadSenior			 = Yii::app()->user->checkAccess('LeadSenior');
				if ($leadFresher)
				{
					$newAccess			 = 0;
					$unverifiedAccess	 = 0;
					$highValueAccess	 = 0;
				}
				if ($leadSenior)
				{
					$unverifiedAccess	 = 0;
					$highValueAccess	 = 0;
				}
				$checkData = AdminOnoff::model()->getByAdmId($csr);
				if ($checkData ['ado_status'] == 1)
				{
					$teamId = Teams::getMultipleTeamid($csr);
					foreach ($teamId as $teamId)
					{
						$returnSet = ServiceCallQueue::process($csr, $teamId['tea_id'], $unverifiedAccess, $newAccess, $highValueAccess);
						if ($returnSet->getStatus())
						{
							break;
						}
					}
				}
			}
			catch (Exception $ex)
			{
				$returnSet->setStatus(false);
				$returnSet = ReturnSet::setException($ex);
			}
		}
		return $returnSet;
	}

	/**
	 * This function is used for rendering FBG Page
	 */
	public function actionMycallfbg()
	{
		$this->pageTitle = "Gozocabs - FBG Lead";
		$success		 = true;
		$csr			 = UserInfo::getUserId();
		$assignModel	 = ServiceCallQueue::fetchAssignLeads($csr);
		$leadID			 = $assignModel["scq_id"];
		$leadType		 = (int) $assignModel['scq_ref_type'];

		//Check for not assigned
		if (empty($leadID))
		{
			goto skip;
		}
		$refType			 = 0;
		$bkgstatus			 = 0;
		$resultQT			 = Booking::model()->getUserbyIdNew($assignModel['scq_related_bkg_id']);
		$bkgstatus			 = $resultQT["bkg_status"];
		$custUserId			 = $resultQT["bkg_user_id"];
		$custUserEmail		 = $resultQT["bkg_user_email"];
		$custUserphn		 = $resultQT["bkg_contact_no"];
		$totalTripComplete	 = ((int) $custUserId > 0) ? Booking::getTotalTripComplete($custUserId) : [];
		$userDriverRating	 = ((int) $custUserId > 0) ? Booking::getUserDriverRating($custUserId) : [];
		$totalEnquiry		 = ((int) $custUserId > 0) ? Booking::getTotalEnquiry($custUserId) : [];
		$totalTireUsed		 = ((int) $custUserId > 0) ? Booking::getCountTireUsed($custUserId) : [];
		$cityTraveled		 = ((int) $custUserId > 0) ? Booking::getCityTraveled($custUserId) : [];
		$tab				 = Yii::app()->request->getParam('tab');
		$formHide			 = Yii::app()->request->getParam('formHide', '');
		$tabFilter			 = Yii::app()->request->getParam('tabFilter', '');
		$related			 = Yii::app()->request->getParam('related', 0);

		$model1			 = new Booking();
		$bookingStatus	 = [
			80	 => 'Booking Details',
			70	 => 'Follow Up',
		];
		;
		$leadStatus		 = $bookingStatus;
		$tabFilter		 = $leadStatus;
		$tabFilter		 = [70];
		if ($tab != '')
		{
			$tabFilter	 = [];
			$tabFilter	 = [$tab];
		}
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$dataProvider		 = [];
		$model1->bkg_status	 = null;
		$statusCount		 = $model1->getStatusCount($csr, '', $custUserId, 'mycall');
		$statusCount [70]	 = ($leadType == 3) ? 1 : ServiceCallQueue::getCountByRefId($leadID);
		$bid				 = Yii::app()->request->getParam('bid', 10);

		skip:
		$this->renderAuto('mycallfbg', array(
			'model'				 => $assignModel,
			'leadType'			 => $leadType,
			'result'			 => $result,
			'resultQT'			 => $resultQT,
			'followUpModel'		 => $assignModel,
			'tab'				 => $tab,
			'leadStatus'		 => $leadStatus,
			'statusCount'		 => $statusCount,
			'dataProvider'		 => $dataProvider,
			'model1'			 => $model1,
			'tabFilter'			 => $tabFilter,
			'formHide'			 => $formHide,
			'lbid'				 => $bid,
			'success'			 => $success,
			'userDriverRating'	 => $userDriverRating,
			'totalEnquiry'		 => $totalEnquiry,
			'totalTripComplete'	 => $totalTripComplete,
			'totalTireUsed'		 => $totalTireUsed,
			'cityTraveled'		 => $cityTraveled,
			'cc'				 => $this,
			'csr'				 => $csr), false, $outputJs);
	}

	/**
	 * This function is used for close call for a Fbg
	 */
	public function actionCloseFbgCall()
	{
		$success	 = true;
		$errormsg	 = '';
		$csrid		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csrid);

		if (!$assignModel)
		{
			$success	 = false;
			$errormsg	 = "No leads are avaliable for you.";
			goto skipAll;
		}
		$assignedFollowUpCount = 1;
		if ($assignModel ['scq_status'] == 2 || $assignModel ['scq_status'] == 3)
		{
			$assignedFollowUpCount = 0;
		}
		if ($assignedFollowUpCount > 0)
		{
			$count = ServiceCallQueue::countScq($csrid);
			if ($count > 1)
			{
				$row = ServiceCallQueue::fetchAssignLeadsByStatus($csrid, 1);
//				ServiceCallQueue ::UnAssignment($csrid, $row['scq_id']);
			}
			$success	 = false;
			$errormsg	 = "You have a pending follow up. Please close all pending follow ups.";
			goto skipAll;
		}
		if ($assignModel ['scq_id'] > 0 && $assignedFollowUpCount == 0)
		{
			$scqIds	 = ServiceCallQueue::getOriginatingfollowup($assignModel['scq_id']);
			$comment = $scqIds > 0 ? ".[call rescheduled > $scqIds]" : ".[call closed]";
			$remarks = $assignModel['scq_disposition_comments'] . " " . $comment;
			ServiceCallQueue:: updateState($assignModel['scq_id'], $csrid, 2, $remarks);
			$this->autoAllocatedLead($csrid);
		}
		skipAll:
		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	/**
	 * This function is used for rendering Vendor Payment Request
	 */
	public function actionMycallvendorpayment()
	{
		$this->pageTitle = "Gozocabs - Vendor Payment Request";
		$success		 = true;
		$csr			 = UserInfo::getUserId();
		$assignModel	 = ServiceCallQueue::fetchAssignLeads($csr);
		$leadID			 = $assignModel["scq_id"];
		$leadType		 = (int) $assignModel['scq_ref_type'];

		//Check for not assigned
		if (empty($leadID))
		{
			goto skip;
		}
		$refType			 = 0;
		$bkgstatus			 = 0;
		$resultQT			 = Booking::model()->getUserbyIdNew($assignModel['scq_related_bkg_id']);
		$bkgstatus			 = $resultQT["bkg_status"];
		$custUserId			 = $resultQT["bkg_user_id"];
		$custUserEmail		 = $resultQT["bkg_user_email"];
		$custUserphn		 = $resultQT["bkg_contact_no"];
		$totalTripComplete	 = ((int) $custUserId > 0) ? Booking::getTotalTripComplete($custUserId) : [];
		$userDriverRating	 = ((int) $custUserId > 0) ? Booking::getUserDriverRating($custUserId) : [];
		$totalEnquiry		 = ((int) $custUserId > 0) ? Booking::getTotalEnquiry($custUserId) : [];
		$totalTireUsed		 = ((int) $custUserId > 0) ? Booking::getCountTireUsed($custUserId) : [];
		$cityTraveled		 = ((int) $custUserId > 0) ? Booking::getCityTraveled($custUserId) : [];
		$tab				 = Yii::app()->request->getParam('tab');
		$formHide			 = Yii::app()->request->getParam('formHide', '');
		$tabFilter			 = Yii::app()->request->getParam('tabFilter', '');
		$related			 = Yii::app()->request->getParam('related', 0);

		$model1			 = new Booking();
		$bookingStatus	 = [
			80	 => 'Booking Details',
			70	 => 'Follow Up',
		];
		;
		$leadStatus		 = $bookingStatus;
		$tabFilter		 = $leadStatus;
		$tabFilter		 = [70];
		if ($tab != '')
		{
			$tabFilter	 = [];
			$tabFilter	 = [$tab];
		}
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$dataProvider		 = [];
		$model1->bkg_status	 = null;
		$statusCount		 = $model1->getStatusCount($csr, '', $custUserId, 'mycall');
		$statusCount [70]	 = ($leadType == 3) ? 1 : ServiceCallQueue::getCountByRefId($leadID);
		$bid				 = Yii::app()->request->getParam('bid', 10);
		$vndData			 = Vendors::model()->getViewDetailbyId($assignModel['scq_to_be_followed_up_with_entity_id']);
		$calAmount			 = AccountTransDetails::model()->calAmountByVendorId($assignModel['scq_to_be_followed_up_with_entity_id']);

		skip:
		$this->renderAuto('mycallvendorpayment', array(
			'model'				 => $assignModel,
			'leadType'			 => $leadType,
			'result'			 => $result,
			'resultQT'			 => $resultQT,
			'followUpModel'		 => $assignModel,
			'tab'				 => $tab,
			'leadStatus'		 => $leadStatus,
			'statusCount'		 => $statusCount,
			'dataProvider'		 => $dataProvider,
			'model1'			 => $model1,
			'tabFilter'			 => $tabFilter,
			'formHide'			 => $formHide,
			'lbid'				 => $bid,
			'success'			 => $success,
			'userDriverRating'	 => $userDriverRating,
			'totalEnquiry'		 => $totalEnquiry,
			'totalTripComplete'	 => $totalTripComplete,
			'totalTireUsed'		 => $totalTireUsed,
			'cityTraveled'		 => $cityTraveled,
			'cc'				 => $this,
			'csr'				 => $csr,
			'vndData'			 => $vndData,
			'calAmount'			 => $calAmount,
				), false, $outputJs);
	}

	/**
	 * This function is used for close call for Vendor Payment Request
	 */
	public function actionCloseVendorPaymentCall()
	{
		$success	 = true;
		$errormsg	 = '';
		$csrid		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csrid);

		if (!$assignModel)
		{
			$success	 = false;
			$errormsg	 = "No leads are avaliable for you.";
			goto skipAll;
		}
		$assignedFollowUpCount = 1;
		if ($assignModel ['scq_status'] == 2 || $assignModel ['scq_status'] == 3)
		{
			$assignedFollowUpCount = 0;
		}
		if ($assignedFollowUpCount > 0)
		{
			$count = ServiceCallQueue::countScq($csrid);
			if ($count > 1)
			{
				$row = ServiceCallQueue::fetchAssignLeadsByStatus($csrid, 1);
//				ServiceCallQueue ::UnAssignment($csrid, $row['scq_id']);
			}
			$success	 = false;
			$errormsg	 = "You have a pending follow up. Please close all pending follow ups.";
			goto skipAll;
		}
		if ($assignModel ['scq_id'] > 0 && $assignedFollowUpCount == 0)
		{
			$comment = ServiceCallQueue::countOriginatingfollowup($assignModel['scq_id']) > 0 ? ".[call rescheduled]" : ".[call closed]";
			$remarks = $assignModel['scq_disposition_comments'] . " " . $comment;
			ServiceCallQueue:: updateState($assignModel['scq_id'], $csrid, 2, $remarks);
			$this->autoAllocatedLead($csrid);
		}
		skipAll:
		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	/**
	 * This function is used for rendering Vendor Details and its follow Up
	 */
	public function actionMyCallVendorData()
	{
		$csr		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csr);
		$vendorId	 = 0;
		if ($assignModel['scq_to_be_followed_up_with_entity_type'] == 1)
		{
			$entityType	 = UserInfo::TYPE_CONSUMER;
			$contactId	 = ContactProfile::getByEntityId($assignModel['scq_to_be_followed_up_with_entity_id'], $entityType);
			$row		 = ContactProfile::getEntityById($contactId, UserInfo::TYPE_VENDOR);
			$vendorId	 = $row['id'];
		}
		else if ($assignModel['scq_to_be_followed_up_with_entity_type'] == 2)
		{
			$entityType	 = UserInfo::TYPE_VENDOR;
			$contactId	 = ContactProfile::getByEntityId($assignModel['scq_to_be_followed_up_with_entity_id'], $entityType);
			$row		 = ContactProfile::getEntityById($contactId, UserInfo::TYPE_VENDOR);
			$vendorId	 = $row['id'];
			if (empty($row))
			{
				$vendorId = $assignModel['scq_to_be_followed_up_with_entity_id'];
			}
		}
		else
		{
			$vendorId = $assignModel['scq_to_be_followed_up_with_entity_id'];
		}
		$refVendorModel = Vendors::model()->mergedVendorId($vendorId);
		if ($refVendorModel->vnd_active == 0 || $vendorId == 0 || $vendorId == '' || $vendorId == null)
		{
			$remarks = "CBR closed by system";
			ServiceCallQueue::updateStatus($assignModel['scq_id'], 10, 0, $remarks);
			$this->autoAllocatedLead($csr, 1);
			$this->redirect(array('lead/mycall'));
			exit();
		}
		else
		{
			$vendorId = $refVendorModel->vnd_id;
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$vndModel	 = Vendors::model()->getViewDetailbyId($vendorId);
		$this->render('mycallVndData', array(
			'result' => $vndModel,
			'model'	 => $assignModel,
			'vndid'	 => $vendorId,
				), false, $outputJs);
	}

	/**
	 * This function is used for close call for Vendor Related Query
	 */
	public function actionCloseMyCallVendorData()
	{
		$success	 = true;
		$errormsg	 = '';
		$csrid		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csrid);

		if (!$assignModel)
		{
			$success	 = false;
			$errormsg	 = "No leads are avaliable for you.";
			goto skipAll;
		}
		$assignedFollowUpCount = 1;
		if ($assignModel['scq_status'] == 2 || $assignModel['scq_status'] == 3)
		{
			$assignedFollowUpCount = 0;
		}
		if ($assignedFollowUpCount > 0)
		{
			$count = ServiceCallQueue::countScq($csrid);
			if ($count > 1)
			{
				$row = ServiceCallQueue::fetchAssignLeadsByStatus($csrid, 1);
//				ServiceCallQueue::UnAssignment($csrid, $row['scq_id']);
			}
			$success	 = false;
			$errormsg	 = "You have a pending follow up. Please close all pending follow ups.";
			goto skipAll;
		}
		if ($assignModel['scq_id'] > 0 && $assignedFollowUpCount == 0)
		{
			$scqIds	 = ServiceCallQueue::getOriginatingfollowup($assignModel['scq_id']);
			$comment = $scqIds > 0 ? ".[call rescheduled > $scqIds]" : ".[call closed]";
			$remarks = $assignModel['scq_disposition_comments'] . " " . $comment;
			ServiceCallQueue::updateState($assignModel['scq_id'], $csrid, 2, $remarks);
			$this->autoAllocatedLead($csrid);
		}
		skipAll:

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	/**
	 * This function is used for rendering B2B Post pickup Page
	 */
	public function actionMycallgozonow()
	{
		$this->pageTitle = "Gozocabs - Gozo Now ";
		$success		 = true;
		$csr			 = UserInfo::getUserId();
		$assignModel	 = ServiceCallQueue::fetchAssignLeads($csr);
		$leadID			 = $assignModel["scq_id"];
		$leadType		 = (int) $assignModel['scq_ref_type'];

		//Check for not assigned
		if (empty($leadID))
		{
			goto skip;
		}
		$refType			 = 0;
		$bkgstatus			 = 0;
		$resultQT			 = Booking::model()->getUserbyIdNew($assignModel['scq_related_bkg_id']);
		$bkgstatus			 = $resultQT["bkg_status"];
		$custUserId			 = $resultQT["bkg_user_id"];
		$custUserEmail		 = $resultQT["bkg_user_email"];
		$custUserphn		 = $resultQT["bkg_contact_no"];
		$totalTripComplete	 = ((int) $custUserId > 0) ? Booking::getTotalTripComplete($custUserId) : [];
		$userDriverRating	 = ((int) $custUserId > 0) ? Booking::getUserDriverRating($custUserId) : [];
		$totalEnquiry		 = ((int) $custUserId > 0) ? Booking::getTotalEnquiry($custUserId) : [];
		$totalTireUsed		 = ((int) $custUserId > 0) ? Booking::getCountTireUsed($custUserId) : [];
		$cityTraveled		 = ((int) $custUserId > 0) ? Booking::getCityTraveled($custUserId) : [];
		$tab				 = Yii::app()->request->getParam('tab');
		$formHide			 = Yii::app()->request->getParam('formHide', '');
		$tabFilter			 = Yii::app()->request->getParam('tabFilter', '');
		$related			 = Yii::app()->request->getParam('related', 0);

		$model1			 = new Booking();
		$bookingStatus	 = [
			80	 => 'Booking Details',
			70	 => 'Follow Up',
		];
		$leadStatus		 = $bookingStatus;
		$tabFilter		 = $leadStatus;
		$tabFilter		 = [70];
		if ($tab != '')
		{
			$tabFilter	 = [];
			$tabFilter	 = [$tab];
		}
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$dataProvider		 = [];
		$model1->bkg_status	 = null;
		$statusCount		 = $model1->getStatusCount($csr, '', $custUserId, 'mycall');
		$statusCount [70]	 = ($leadType == 3) ? 1 : ServiceCallQueue::getCountByRefId($leadID);
		$bid				 = Yii::app()->request->getParam('bid', 10);

		skip:
		$this->renderAuto('mycallgozonow', array(
			'model'				 => $assignModel,
			'leadType'			 => $leadType,
			'result'			 => $result,
			'resultQT'			 => $resultQT,
			'followUpModel'		 => $assignModel,
			'tab'				 => $tab,
			'leadStatus'		 => $leadStatus,
			'statusCount'		 => $statusCount,
			'dataProvider'		 => $dataProvider,
			'model1'			 => $model1,
			'tabFilter'			 => $tabFilter,
			'formHide'			 => $formHide,
			'lbid'				 => $bid,
			'success'			 => $success,
			'userDriverRating'	 => $userDriverRating,
			'totalEnquiry'		 => $totalEnquiry,
			'totalTripComplete'	 => $totalTripComplete,
			'totalTireUsed'		 => $totalTireUsed,
			'cityTraveled'		 => $cityTraveled,
			'cc'				 => $this,
			'csr'				 => $csr), false, $outputJs);
	}

	/**
	 * This function is used for close call for a B2B Post pickup
	 */
	public function actionCloseGozonow()
	{
		$success	 = true;
		$errormsg	 = '';
		$csrid		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csrid);

		if (!$assignModel)
		{
			$success	 = false;
			$errormsg	 = "No leads are avaliable for you.";
			goto skipAll;
		}
		$assignedFollowUpCount = 1;
		if ($assignModel ['scq_status'] == 2 || $assignModel ['scq_status'] == 3)
		{
			$assignedFollowUpCount = 0;
		}
		if ($assignedFollowUpCount > 0)
		{
			$count = ServiceCallQueue::countScq($csrid);
			if ($count > 1)
			{
				$row = ServiceCallQueue::fetchAssignLeadsByStatus($csrid, 1);
//				ServiceCallQueue ::UnAssignment($csrid, $row['scq_id']);
			}
			$success	 = false;
			$errormsg	 = "You have a pending follow up. Please close all pending follow ups.";
			goto skipAll;
		}
		if ($assignModel ['scq_id'] > 0 && $assignedFollowUpCount == 0)
		{
			$scqIds	 = ServiceCallQueue::getOriginatingfollowup($assignModel['scq_id']);
			$comment = $scqIds > 0 ? ".[call rescheduled > $scqIds]" : ".[call closed]";
			$remarks = $assignModel['scq_disposition_comments'] . " " . $comment;
			ServiceCallQueue:: updateState($assignModel['scq_id'], $csrid, 2, $remarks);
			$this->autoAllocatedLead($csrid);
		}
		skipAll:

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	/**
	 * This function is used for rendering Agent Details and its follow Up
	 */
	public function actionMyCallAgent()
	{
		$success	 = true;
		$csr		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csr);
		$agentId	 = 0;
		if ($assignModel['scq_to_be_followed_up_with_entity_type'] == 1)
		{
			$entityType	 = UserInfo::TYPE_CONSUMER;
			$contactId	 = ContactProfile::getByEntityId($assignModel['scq_to_be_followed_up_with_entity_id'], $entityType);
			$row		 = ContactProfile::getEntityById($contactId, UserInfo::TYPE_AGENT);
			$agentId	 = $row['id'];
		}
		else if ($assignModel['scq_to_be_followed_up_with_entity_type'] == 5)
		{
			$entityType	 = UserInfo::TYPE_AGENT;
			$contactId	 = ContactProfile::getByEntityId($assignModel['scq_to_be_followed_up_with_entity_id'], $entityType);
			$row		 = ContactProfile::getEntityById($contactId, UserInfo::TYPE_AGENT);
			$agentId	 = $row['id'];
		}
		else
		{
			$agentId = $assignModel['scq_to_be_followed_up_with_entity_id'];
		}
		if ($agentId == 0 || $agentId == '' || $agentId == NULL)
		{
			echo 'No agent found for this contact';
			Yii::app()->end();
		}

		$outputJs = Yii::app()->request->isAjaxRequest;
		$this->render('mycallAgent', array(
			'model'		 => $assignModel,
			'agentId'	 => $agentId,
				), false, $outputJs);
	}

	/**
	 * This function is used to close call for Agent Related Query
	 */
	public function actionCloseMyCallAgent()
	{
		$success	 = true;
		$errormsg	 = '';
		$csrid		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csrid);

		if (!$assignModel)
		{
			$success	 = false;
			$errormsg	 = "No leads are avaliable for you.";
			goto skipAll;
		}
		$assignedFollowUpCount = 1;
		if ($assignModel['scq_status'] == 2 || $assignModel['scq_status'] == 3)
		{
			$assignedFollowUpCount = 0;
		}
		if ($assignedFollowUpCount > 0)
		{
			$count = ServiceCallQueue::countScq($csrid);
			if ($count > 1)
			{
				$row = ServiceCallQueue::fetchAssignLeadsByStatus($csrid, 1);
//				ServiceCallQueue::UnAssignment($csrid, $row['scq_id']);
			}
			$success	 = false;
			$errormsg	 = "You have a pending follow up. Please close all pending follow ups.";
			goto skipAll;
		}
		if ($assignModel['scq_id'] > 0 && $assignedFollowUpCount == 0)
		{
			$scqIds	 = ServiceCallQueue::getOriginatingfollowup($assignModel['scq_id']);
			$comment = $scqIds > 0 ? ".[call rescheduled > $scqIds]" : ".[call closed]";
			$remarks = $assignModel['scq_disposition_comments'] . " " . $comment;
			ServiceCallQueue::updateState($assignModel['scq_id'], $csrid, 2, $remarks);
			$this->autoAllocatedLead($csrid);
		}
		skipAll:

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	/**
	 * This function is used for rendering B2B Post pickup Page
	 */
	public function actionMycallAutoAssignLead()
	{
		$this->pageTitle = "Gozocabs - Auto Lead Followup ";
		$success		 = true;
		$csr			 = UserInfo::getUserId();
		$assignModel	 = ServiceCallQueue::fetchAssignLeads($csr);
		$leadID			 = $assignModel["scq_id"];
		$leadType		 = (int) $assignModel['scq_ref_type'];

		//Check for not assigned
		if (empty($leadID))
		{
			goto skip;
		}
		$refType			 = 0;
		$bkgstatus			 = 0;
		$resultQT			 = Booking::model()->getUserbyIdNew($assignModel['scq_related_bkg_id']);
		$bkgstatus			 = $resultQT["bkg_status"];
		$custUserId			 = $resultQT["bkg_user_id"];
		$custUserEmail		 = $resultQT["bkg_user_email"];
		$custUserphn		 = $resultQT["bkg_contact_no"];
		$totalTripComplete	 = ((int) $custUserId > 0) ? Booking::getTotalTripComplete($custUserId) : [];
		$userDriverRating	 = ((int) $custUserId > 0) ? Booking::getUserDriverRating($custUserId) : [];
		$totalEnquiry		 = ((int) $custUserId > 0) ? Booking::getTotalEnquiry($custUserId) : [];
		$totalTireUsed		 = ((int) $custUserId > 0) ? Booking::getCountTireUsed($custUserId) : [];
		$cityTraveled		 = ((int) $custUserId > 0) ? Booking::getCityTraveled($custUserId) : [];
		$tab				 = Yii::app()->request->getParam('tab');
		$formHide			 = Yii::app()->request->getParam('formHide', '');
		$tabFilter			 = Yii::app()->request->getParam('tabFilter', '');
		$related			 = Yii::app()->request->getParam('related', 0);

		$model1			 = new Booking();
		$bookingStatus	 = [
			80	 => 'Booking Details',
			70	 => 'Follow Up',
		];
		$leadStatus		 = $bookingStatus;
		$tabFilter		 = $leadStatus;
		$tabFilter		 = [70];
		if ($tab != '')
		{
			$tabFilter	 = [];
			$tabFilter	 = [$tab];
		}
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$dataProvider		 = [];
		$model1->bkg_status	 = null;
		$statusCount		 = $model1->getStatusCount($csr, '', $custUserId, 'mycall');
		$statusCount [70]	 = ($leadType == 3) ? 1 : ServiceCallQueue::getCountByRefId($leadID);
		$bid				 = Yii::app()->request->getParam('bid', 10);

		skip:
		$this->renderAuto('mycallAutoAssignLead', array(
			'model'				 => $assignModel,
			'leadType'			 => $leadType,
			'result'			 => $result,
			'resultQT'			 => $resultQT,
			'followUpModel'		 => $assignModel,
			'tab'				 => $tab,
			'leadStatus'		 => $leadStatus,
			'statusCount'		 => $statusCount,
			'dataProvider'		 => $dataProvider,
			'model1'			 => $model1,
			'tabFilter'			 => $tabFilter,
			'formHide'			 => $formHide,
			'lbid'				 => $bid,
			'success'			 => $success,
			'userDriverRating'	 => $userDriverRating,
			'totalEnquiry'		 => $totalEnquiry,
			'totalTripComplete'	 => $totalTripComplete,
			'totalTireUsed'		 => $totalTireUsed,
			'cityTraveled'		 => $cityTraveled,
			'cc'				 => $this,
			'csr'				 => $csr), false, $outputJs);
	}

	/**
	 * This function is used for close call for a B2B Post pickup
	 */
	public function actionCloseAutoAssignLead()
	{
		$success	 = true;
		$errormsg	 = '';
		$csrid		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csrid);

		if (!$assignModel)
		{
			$success	 = false;
			$errormsg	 = "No leads are avaliable for you.";
			goto skipAll;
		}
		$assignedFollowUpCount = 1;
		if ($assignModel ['scq_status'] == 2 || $assignModel ['scq_status'] == 3)
		{
			$assignedFollowUpCount = 0;
		}
		if ($assignedFollowUpCount > 0)
		{
			$count = ServiceCallQueue::countScq($csrid);
			if ($count > 1)
			{
				$row = ServiceCallQueue::fetchAssignLeadsByStatus($csrid, 1);
//				ServiceCallQueue ::UnAssignment($csrid, $row['scq_id']);
			}
			$success	 = false;
			$errormsg	 = "You have a pending follow up. Please close all pending follow ups.";
			goto skipAll;
		}
		if ($assignModel ['scq_id'] > 0 && $assignedFollowUpCount == 0)
		{
			$scqIds	 = ServiceCallQueue::getOriginatingfollowup($assignModel['scq_id']);
			$comment = $scqIds > 0 ? ".[call rescheduled > $scqIds]" : ".[call closed]";
			$remarks = $assignModel['scq_disposition_comments'] . " " . $comment;
			ServiceCallQueue:: updateState($assignModel['scq_id'], $csrid, 2, $remarks);
			$this->autoAllocatedLead($csrid);
		}
		skipAll:

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	public function actionMycallDocumentapproval()
	{
		$success	 = true;
		$csr		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csr);
		$vendorId	 = 0;
		if ($assignModel['scq_to_be_followed_up_with_entity_type'] == 1)
		{
			$entityType	 = UserInfo::TYPE_CONSUMER;
			$contactId	 = ContactProfile::getByEntityId($assignModel['scq_to_be_followed_up_with_entity_id'], $entityType);
			$row		 = ContactProfile::getEntityById($contactId, UserInfo::TYPE_VENDOR);
			$vendorId	 = $row['id'];
		}
		else if ($assignModel['scq_to_be_followed_up_with_entity_type'] == 2)
		{
			$entityType	 = UserInfo::TYPE_VENDOR;
			$contactId	 = ContactProfile::getByEntityId($assignModel['scq_to_be_followed_up_with_entity_id'], $entityType);
			$row		 = ContactProfile::getEntityById($contactId, UserInfo::TYPE_VENDOR);
			$vendorId	 = $row['id'];
		}
		else
		{
			$vendorId = $assignModel['scq_to_be_followed_up_with_entity_id'];
		}

		$refVendorModel = Vendors::model()->mergedVendorId($vendorId);
		if ($refVendorModel->vnd_active == 0 || $vendorId == 0 || $vendorId == '' || $vendorId == null)
		{
			$remarks = "CBR closed by system";
			ServiceCallQueue::updateStatus($assignModel['scq_id'], 10, 0, $remarks);
			$this->autoAllocatedLead($csr, 1);
			$this->redirect(array('lead/mycall'));
			exit();
		}
		else
		{
			$vendorId = $refVendorModel->vnd_id;
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$vndModel	 = Vendors::model()->getViewDetailbyId($vendorId);
		$this->render('mycallDocumentApproval', array(
			'result' => $vndModel,
			'model'	 => $assignModel,
			'vndid'	 => $vendorId,
				), false, $outputJs);
	}

	public function actioncloseDocumentApprovalCall()
	{
		$success	 = true;
		$errormsg	 = '';
		$csrid		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csrid);

		if (!$assignModel)
		{
			$success	 = false;
			$errormsg	 = "No leads are avaliable for you.";
			goto skipAll;
		}
		$assignedFollowUpCount = 1;
		if ($assignModel ['scq_status'] == 2 || $assignModel ['scq_status'] == 3)
		{
			$assignedFollowUpCount = 0;
		}
		if ($assignedFollowUpCount > 0)
		{
			$count = ServiceCallQueue::countScq($csrid);
			if ($count > 1)
			{
				$row = ServiceCallQueue::fetchAssignLeadsByStatus($csrid, 1);
//				ServiceCallQueue ::UnAssignment($csrid, $row['scq_id']);
			}
			$success	 = false;
			$errormsg	 = "You have a pending follow up. Please close all pending follow ups.";
			goto skipAll;
		}
		if ($assignModel ['scq_id'] > 0 && $assignedFollowUpCount == 0)
		{
			$scqIds	 = ServiceCallQueue::getOriginatingfollowup($assignModel['scq_id']);
			$comment = $scqIds > 0 ? ".[call rescheduled > $scqIds]" : ".[call closed]";
			$remarks = $assignModel['scq_disposition_comments'] . " " . $comment;
			ServiceCallQueue:: updateState($assignModel['scq_id'], $csrid, 2, $remarks);
			$this->autoAllocatedLead($csrid);
		}
		skipAll:

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	/**
	 * 	This function is used for auto  assigning leads to a csr  by team id
	 */
	public function actionAllocatedLeadByTeam()
	{
		$team		 = Yii::app()->request->getParam('team');
		$isAllowed	 = Yii::app()->request->getParam('isAllowed', 0);
		try
		{
			$csrid				 = UserInfo::getUserId();
			$teamId				 = Teams::getMultipleTeamid($csrid);
			$newAccess			 = 1;
			$highValueAccess	 = 1;
			$unverifiedAccess	 = 1;
			$leadFresher		 = Yii::app()->user->checkAccess('LeadFresher');
			$leadSenior			 = Yii::app()->user->checkAccess('LeadSenior');
			if ($leadFresher)
			{
				$newAccess			 = 0;
				$unverifiedAccess	 = 0;
				$highValueAccess	 = 0;
			}
			if ($leadSenior)
			{
				$unverifiedAccess	 = 0;
				$highValueAccess	 = 0;
			}
			foreach ($teamId as $teamId)
			{
				if ($teamId['tea_id'] == $team)
				{
					$followupQueue = 0;
					switch ($teamId['tea_id'])
					{
						case 3:
							$followupQueue		 = "3,15,30";
							break;
						case 27:
						case 28:
						case 29:
						case 30:
						case 36:
						case 4:
							$followupQueue		 = "19,32";
							$isMultipleAllowed	 = ServiceCallQueue::countScq($csrid) > 3 ? 0 : $isAllowed;
							break;
						case 41:
							$followupQueue		 = "14";
							break;
						case 48:
							$isMultipleAllowed	 = ServiceCallQueue::countScq($csrid) > 3 ? 0 : $isAllowed;
							break;
						default:
							break;
					}
					$returnSet = ServiceCallQueue::process($csrid, $teamId['tea_id'], $unverifiedAccess, $newAccess, $highValueAccess, 1, $followupQueue, $isMultipleAllowed);
					if ($returnSet->getStatus())
					{
						$this->redirect(array('lead/mycall'));
					}
				}
			}
			if ($team == 1)
			{

				$returnSet = ServiceCallQueue::process($csrid, $team, $unverifiedAccess, $newAccess, $highValueAccess, 0);
				if ($returnSet->getStatus())
				{
					$this->redirect(array('lead/mycall'));
				}
			}
			if ($team == 51)
			{

				$returnSet = ServiceCallQueue::process($csrid, $team, $unverifiedAccess, $newAccess, $highValueAccess, 1);
				if ($returnSet->getStatus())
				{
					$this->redirect(array('lead/mycall'));
				}
			}
		}
		catch (Exception $ex)
		{
			
		}
		$this->redirect(array('lead/mycall'));
	}

	/**
	 * This function is used for rendering B2B Post pickup Page
	 */
	public function actionMycallB2CBookingCancel()
	{
		$this->pageTitle = "Gozocabs - B2C Booking Cancel ";
		$success		 = true;
		$csr			 = UserInfo::getUserId();
		$assignModel	 = ServiceCallQueue::fetchAssignLeads($csr);
		$leadID			 = $assignModel["scq_id"];
		$leadType		 = (int) $assignModel['scq_ref_type'];

		//Check for not assigned
		if (empty($leadID))
		{
			goto skip;
		}
		$refType			 = 0;
		$bkgstatus			 = 0;
		$resultQT			 = Booking::model()->getUserbyIdNew($assignModel['scq_related_bkg_id']);
		$bkgstatus			 = $resultQT["bkg_status"];
		$custUserId			 = $resultQT["bkg_user_id"];
		$custUserEmail		 = $resultQT["bkg_user_email"];
		$custUserphn		 = $resultQT["bkg_contact_no"];
		$totalTripComplete	 = ((int) $custUserId > 0) ? Booking::getTotalTripComplete($custUserId) : [];
		$userDriverRating	 = ((int) $custUserId > 0) ? Booking::getUserDriverRating($custUserId) : [];
		$totalEnquiry		 = ((int) $custUserId > 0) ? Booking::getTotalEnquiry($custUserId) : [];
		$totalTireUsed		 = ((int) $custUserId > 0) ? Booking::getCountTireUsed($custUserId) : [];
		$cityTraveled		 = ((int) $custUserId > 0) ? Booking::getCityTraveled($custUserId) : [];
		$tab				 = Yii::app()->request->getParam('tab');
		$formHide			 = Yii::app()->request->getParam('formHide', '');
		$tabFilter			 = Yii::app()->request->getParam('tabFilter', '');
		$related			 = Yii::app()->request->getParam('related', 0);

		$model1			 = new Booking();
		$bookingStatus	 = [
			80	 => 'Booking Details',
			70	 => 'Follow Up',
		];
		$leadStatus		 = $bookingStatus;
		$tabFilter		 = $leadStatus;
		$tabFilter		 = [70];
		if ($tab != '')
		{
			$tabFilter	 = [];
			$tabFilter	 = [$tab];
		}
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$dataProvider		 = [];
		$model1->bkg_status	 = null;
		$statusCount		 = $model1->getStatusCount($csr, '', $custUserId, 'mycall');
		$statusCount [70]	 = ($leadType == 3) ? 1 : ServiceCallQueue::getCountByRefId($leadID);
		$bid				 = Yii::app()->request->getParam('bid', 10);

		skip:
		$this->renderAuto('mycallb2cbookingcancel', array(
			'model'				 => $assignModel,
			'leadType'			 => $leadType,
			'result'			 => $result,
			'resultQT'			 => $resultQT,
			'followUpModel'		 => $assignModel,
			'tab'				 => $tab,
			'leadStatus'		 => $leadStatus,
			'statusCount'		 => $statusCount,
			'dataProvider'		 => $dataProvider,
			'model1'			 => $model1,
			'tabFilter'			 => $tabFilter,
			'formHide'			 => $formHide,
			'lbid'				 => $bid,
			'success'			 => $success,
			'userDriverRating'	 => $userDriverRating,
			'totalEnquiry'		 => $totalEnquiry,
			'totalTripComplete'	 => $totalTripComplete,
			'totalTireUsed'		 => $totalTireUsed,
			'cityTraveled'		 => $cityTraveled,
			'cc'				 => $this,
			'csr'				 => $csr), false, $outputJs);
	}

	/**
	 * This function is used for close call for a B2B Post pickup
	 */
	public function actionCloseB2CBookingCancel()
	{
		$success	 = true;
		$errormsg	 = '';
		$csrid		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csrid);

		if (!$assignModel)
		{
			$success	 = false;
			$errormsg	 = "No leads are avaliable for you.";
			goto skipAll;
		}
		$assignedFollowUpCount = 1;
		if ($assignModel ['scq_status'] == 2 || $assignModel ['scq_status'] == 3)
		{
			$assignedFollowUpCount = 0;
		}
		if ($assignedFollowUpCount > 0)
		{
			$count = ServiceCallQueue::countScq($csrid);
			if ($count > 1)
			{
				$row = ServiceCallQueue::fetchAssignLeadsByStatus($csrid, 1);
//				ServiceCallQueue ::UnAssignment($csrid, $row['scq_id']);
			}
			$success	 = false;
			$errormsg	 = "You have a pending follow up. Please close all pending follow ups.";
			goto skipAll;
		}
		if ($assignModel ['scq_id'] > 0 && $assignedFollowUpCount == 0)
		{
			$scqIds	 = ServiceCallQueue::getOriginatingfollowup($assignModel['scq_id']);
			$comment = $scqIds > 0 ? ".[call rescheduled > $scqIds]" : ".[call closed]";
			$remarks = $assignModel['scq_disposition_comments'] . " " . $comment;
			ServiceCallQueue:: updateState($assignModel['scq_id'], $csrid, 2, $remarks);
			$this->autoAllocatedLead($csrid);
		}
		skipAll:

		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	/**
	 * This function is used for rendering Vendor Payment Request
	 */
	public function actionMycallvnddueamount()
	{
		$this->pageTitle = "Gozocabs - Vendor Due Amount Request";
		$success		 = true;
		$csr			 = UserInfo::getUserId();
		$assignModel	 = ServiceCallQueue::fetchAssignLeads($csr);
		$leadID			 = $assignModel["scq_id"];
		$leadType		 = (int) $assignModel['scq_ref_type'];

		//Check for not assigned
		if (empty($leadID))
		{
			goto skip;
		}
		$refType			 = 0;
		$bkgstatus			 = 0;
		$resultQT			 = Booking::model()->getUserbyIdNew($assignModel['scq_related_bkg_id']);
		$bkgstatus			 = $resultQT["bkg_status"];
		$custUserId			 = $resultQT["bkg_user_id"];
		$custUserEmail		 = $resultQT["bkg_user_email"];
		$custUserphn		 = $resultQT["bkg_contact_no"];
		$totalTripComplete	 = ((int) $custUserId > 0) ? Booking::getTotalTripComplete($custUserId) : [];
		$userDriverRating	 = ((int) $custUserId > 0) ? Booking::getUserDriverRating($custUserId) : [];
		$totalEnquiry		 = ((int) $custUserId > 0) ? Booking::getTotalEnquiry($custUserId) : [];
		$totalTireUsed		 = ((int) $custUserId > 0) ? Booking::getCountTireUsed($custUserId) : [];
		$cityTraveled		 = ((int) $custUserId > 0) ? Booking::getCityTraveled($custUserId) : [];
		$tab				 = Yii::app()->request->getParam('tab');
		$formHide			 = Yii::app()->request->getParam('formHide', '');
		$tabFilter			 = Yii::app()->request->getParam('tabFilter', '');
		$related			 = Yii::app()->request->getParam('related', 0);

		$model1			 = new Booking();
		$bookingStatus	 = [
			80	 => 'Booking Details',
			70	 => 'Follow Up',
		];
		$leadStatus		 = $bookingStatus;
		$tabFilter		 = $leadStatus;
		$tabFilter		 = [70];
		if ($tab != '')
		{
			$tabFilter	 = [];
			$tabFilter	 = [$tab];
		}
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$dataProvider		 = [];
		$model1->bkg_status	 = null;
		$statusCount		 = $model1->getStatusCount($csr, '', $custUserId, 'mycall');
		$statusCount [70]	 = ($leadType == 3) ? 1 : ServiceCallQueue::getCountByRefId($leadID);
		$bid				 = Yii::app()->request->getParam('bid', 10);
		$vndData			 = Vendors::model()->getViewDetailbyId($assignModel['scq_to_be_followed_up_with_entity_id']);
		$calAmount			 = AccountTransDetails::model()->calAmountByVendorId($assignModel['scq_to_be_followed_up_with_entity_id']);

		skip:
		$this->renderAuto('mycallvnddueamount', array(
			'model'				 => $assignModel,
			'leadType'			 => $leadType,
			'result'			 => $result,
			'resultQT'			 => $resultQT,
			'followUpModel'		 => $assignModel,
			'tab'				 => $tab,
			'leadStatus'		 => $leadStatus,
			'statusCount'		 => $statusCount,
			'dataProvider'		 => $dataProvider,
			'model1'			 => $model1,
			'tabFilter'			 => $tabFilter,
			'formHide'			 => $formHide,
			'lbid'				 => $bid,
			'success'			 => $success,
			'userDriverRating'	 => $userDriverRating,
			'totalEnquiry'		 => $totalEnquiry,
			'totalTripComplete'	 => $totalTripComplete,
			'totalTireUsed'		 => $totalTireUsed,
			'cityTraveled'		 => $cityTraveled,
			'cc'				 => $this,
			'csr'				 => $csr,
			'vndData'			 => $vndData,
			'calAmount'			 => $calAmount,
				), false, $outputJs);
	}

	/**
	 * This function is used for close call for Vendor Payment Request
	 */
	public function actionCloseVendorDuePaymentCall()
	{
		$success	 = true;
		$errormsg	 = '';
		$csrid		 = UserInfo::getUserId();
		$assignModel = ServiceCallQueue::fetchAssignLeads($csrid);

		if (!$assignModel)
		{
			$success	 = false;
			$errormsg	 = "No leads are avaliable for you.";
			goto skipAll;
		}
		$assignedFollowUpCount = 1;
		if ($assignModel ['scq_status'] == 2 || $assignModel ['scq_status'] == 3)
		{
			$assignedFollowUpCount = 0;
		}
		if ($assignedFollowUpCount > 0)
		{
			$count = ServiceCallQueue::countScq($csrid);
			if ($count > 1)
			{
				$row = ServiceCallQueue::fetchAssignLeadsByStatus($csrid, 1);
			}
			$success	 = false;
			$errormsg	 = "You have a pending follow up. Please close all pending follow ups.";
			goto skipAll;
		}
		if ($assignModel ['scq_id'] > 0 && $assignedFollowUpCount == 0)
		{
			$comment = ServiceCallQueue::countOriginatingfollowup($assignModel['scq_id']) > 0 ? ".[call rescheduled]" : ".[call closed]";
			$remarks = $assignModel['scq_disposition_comments'] . " " . $comment;
			ServiceCallQueue:: updateState($assignModel['scq_id'], $csrid, 2, $remarks);
			$this->autoAllocatedLead($csrid);
		}
		skipAll:
		if (Yii::app()->request->isAjaxRequest)
		{
			$data = ['success' => $success, 'errormsg' => $errormsg];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

}
