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
			['allow', 'actions' => ['leadfollow'], 'roles' => ['leadAdd', 'leadEdit']],
			['allow', 'actions' => ['assigncsr'], 'roles' => ['leadAssigncsr']],
			['allow', 'actions' => ['converttobooking'], 'roles' => ['leadConverttobooking']],
			['allow', 'actions' => ['dailyleadreport'], 'roles' => ['leadReportDaily']],
			['allow', 'actions' => ['report'], 'roles' => ['leadList']],
			array('allow', 'actions'	 => array('markread', 'locklead', 'unlocklead', 'addfollowup',
					'related', 'showcsr', 'showlog', 'markinvalid', 'converttolead', 'deactivate', 'autoAssign'
				),
				'users'		 => array('@'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
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
				$model->bkg_admin_id = Yii::app()->user->getId();
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

				$obj->success = true;
				if ($model->validate())
				{
					$model->bkg_admin_id = Yii::app()->user->getId();
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
				$userInfo		 = UserInfo::getInstance();

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
		
		$tab			 = Yii::app()->request->getParam('tab'); //$_GET['tab'];
		$searchid		 = Yii::app()->request->getParam('searchid');
		$this->pageTitle = "Lead Report";
		$model			 = new BookingTemp();

		//$model->bkg_create_date1 = date('Y-m-d');
		//$model->bkg_create_date2 = date('Y-m-d');

		$model->bkg_create_date1 = date('Y-m-d', strtotime(' -1 day'));
		$model->bkg_create_date2 = date('Y-m-d');

		if (isset($_REQUEST['BookingTemp']))
		{
			$model->attributes = Yii::app()->request->getParam('BookingTemp');


//            $qry = [];
//            $arrDate = ['bkg_create_date1', 'bkg_create_date2', 'bkg_pickup_date1', 'bkg_pickup_date2'];
			//$model->attributes = $_REQUEST['BookingTemp'];
			$model->bkg_create_date1			 = ($model->bkg_create_date1 == '') ? '' : DateTimeFormat::DatePickerToDate($model->bkg_create_date1);
			$model->bkg_create_date2			 = ($model->bkg_create_date2 == '') ? '' : DateTimeFormat::DatePickerToDate($model->bkg_create_date2);
			$model->bkg_pickup_date1			 = ($model->bkg_pickup_date1 == '') ? '' : DateTimeFormat::DatePickerToDate($model->bkg_pickup_date1);
			$model->bkg_pickup_date2			 = ($model->bkg_pickup_date2 == '') ? '' : DateTimeFormat::DatePickerToDate($model->bkg_pickup_date2);
			$model->bkg_follow_up_reminder_date1 = ($model->bkg_follow_up_reminder_date1 == '') ? '' : DateTimeFormat::DatePickerToDate($model->bkg_follow_up_reminder_date1);
		}
		if ($searchid != '')
		{
			$model->bkg_id			 = $searchid;
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
			$dataProvider[$bid]['data']							 = $model->feedbackReport();
			$dataProvider[$bid]['label']						 = $bval;
			$dataProvider[$bid]['count']						 = $dataProvider[$bid]['data']->getTotalItemCount();
			$params1['tab']										 = $bid;
			$dataProvider[$bid]['data']->getPagination()->params = $params1;
			$dataProvider[$bid]['data']->getSort()->params		 = $params1;
		}
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

			/* if ($pickupDate1 != '' && $pickupDate2 != '') {
			  $where = " AND date(bkg_create_date) BETWEEN '$fromDate' AND '$toDate'";
			  } else if ($pickupDate1 != '') {
			  $where = " AND date(bkg_pickup_date)>='$pickupDate1'";
			  } else if ($pickupDate2 != '') {
			  $where = " AND date(bkg_pickup_date)<='$pickupDate2'";
			  } */
			//------ end sql modify ---------//

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
			$sql	 = "SELECT bkg_id,v.vht_model,c1.cty_name as from_city,c2.cty_name as to_city,bkg_pickup_date,bkg_create_date,bkg_booking_type,bkg_amount,bkg_log_type,bkg_lead_source,bkg_log_comment,bkg_log_phone,bkg_log_email,bkg_contact_no,bkg_user_email,TIMESTAMPDIFF(MINUTE, bkg_create_date,CURRENT_TIMESTAMP) as minute_diff
                    FROM booking_temp LEFT JOIN cities c1 ON bkg_from_city_id=c1.cty_id LEFT JOIN cities c2 ON bkg_to_city_id=c2.cty_id LEFT JOIN vehicle_types v ON bkg_vehicle_type_id=v.vht_id WHERE bkg_active=1 " . $where . " AND ((bkg_lead_source IS NOT NULL AND bkg_lead_source<>'') OR (bkg_user_email <>'' OR bkg_contact_no <>'' ))";

			$command = Yii::app()->db->createCommand($sql . $having);
			$rows	 = $command->queryAll();
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
		$bkid	 = Yii::app()->request->getParam('booking_id');
		$logList = LeadLog::model()->getByBookingId($bkid);
		// $logListDataProvider = new CArrayDataProvider($logList, array('pagination' => array('pageSize' => $pageSize),));
		$this->renderPartial('showlog', array('lmodel' => $logList));
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
//  $bkgid = $success;
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
			$model->attributes = Yii::app()->request->getParam('BookingTemp');
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
				$model->bkg_user_device	 = UserLog::model()->getDevice();
				$status					 = $model->bkg_lead_source;

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
		$this->pageTitle	 = "Daily Lead Report";
		$model				 = new LeadLog();
		$model->blg_created1 = date('Y-m-d');
		$model->blg_created2 = date('Y-m-d');
		if (isset($_REQUEST['LeadLog']))
		{
			$model->attributes	 = $_REQUEST['LeadLog'];
			$model->executive	 = $_REQUEST['LeadLog']['executive'];
			//  if ($_REQUEST['LeadLog']['blg_created1'] != '') {
			$model->blg_created1 = $_REQUEST['LeadLog']['blg_created1'];
			//  }
			//  if ($_REQUEST['LeadLog']['blg_created2'] != '') {
			$model->blg_created2 = $_REQUEST['LeadLog']['blg_created2'];
			//   }
			if ($_REQUEST['blg_created1'] != '')
			{
				$model->blg_created1 = $_REQUEST['blg_created1'];
			}
			if ($_REQUEST['blg_created2'] != '')
			{
				$model->blg_created2 = $_REQUEST['blg_created2'];
			}
		}
		$dataProvider = $model->getDailyLeadReportCount();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST), 'pagesize' => 50]);
		$this->render('dailyreport', ['model' => $model, 'dataProvider' => $dataProvider]);
	}

	public function actionRelated()
	{
		$model			 = new BookingTemp();
		$bkid			 = Yii::app()->request->getParam('bkg_id'); // $_REQUEST['booking_id'];
		$relatedLeads	 = BookingTemp::model()->getRelatedLeads($bkid);
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
		if ($row['type'] == '1')
		{
			if ($row['csrRank'] == 0)
			{
				/* @var $model Booking */
				$model					 = Booking::model()->findByPk($row['bkg_id']);
				$model->bkg_assign_csr	 = $csr;
				$model->save();
				$admin					 = Admins::model()->findByPk($csr);
				$aname					 = $admin->adm_fname;
				//$bkgid = $success;
				$desc					 = "CSR ($aname) Auto Assigned";
				BookingLog::model()->createLog($row['bkg_id'], $desc, UserInfo::model(), BookingLog::CSR_ASSIGN, false, false);
			}
			$url = Yii::app()->createUrl("rcsr/booking/list", ["searchid" => $row['bkg_id']]);
		}
		else
		{
			if ($row['csrRank'] == 0)
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
			}
			$url = Yii::app()->createUrl("rcsr/lead/report", ["searchid" => $row['bkg_id']]);
		}
		echo json_encode(["url" => $url]);
		Yii::app()->end();
	}

}
