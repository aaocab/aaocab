<?php

class NotificationController extends Controller
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
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('messagelist', 'list', 'whatsapplog', 'InterestedDCOTracking', 'ShowMsg', 'recdWhatsappLog', 'showWhatsappMsg'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index'),
				'users'		 => array('*'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionMessageList()
	{
		$row = Report::getRoleAccess(14);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Sms";
		$request		 = Yii::app()->request;
		$model			 = new SmsLog('search');
		$bookingId		 = $request->getParam('bookingId');
		if ($bookingId != "")
		{
			$model->booking_id = $bookingId;
		}
		if ($request->getParam('SmsLog'))
		{
			$model->attributes = $request->getParam('SmsLog');
		}
		else
		{
			$model->sendDate1	 = date("Y-m-d", strtotime("-1 day", time()));
			$model->sendDate2	 = date('Y-m-d');
		}
		$dataProvider = $model->fetchList();
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$this->render('messagelist', array('model' => $model, 'dataProvider' => $dataProvider, 'roles' => $row));
	}

	/*
	 * This action is used for List down all the notification log entries with filters
	 * return view
	 */

	public function actionList()
	{
		$row = Report::getRoleAccess(15);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Notification Log"; //Sets the page title
		$pageSize		 = Yii::app()->params["listPerPage"];
		$request		 = Yii::app()->request->getParam('NotificationLog');
		$model			 = new NotificationLog();
		if (isset($_REQUEST['NotificationLog']))
		{
			$arr					 = Yii::app()->request->getParam('NotificationLog'); //print_r($arr);die;
			$model->attributes		 = $arr;
			$model->ntl_created_on1	 = !empty($arr['ntl_created_on1']) ? date('Y-m-d', strtotime($arr['ntl_created_on1'])) . " 00:00:00" : "";
			$model->ntl_created_on2	 = !empty($arr['ntl_created_on2']) ? date('Y-m-d', strtotime($arr['ntl_created_on2'])) . " 23:59:59" : "";
			$model->ntl_entity_type	 = $arr['ntl_entity_type'];
			$model->vndid			 = $arr['vndid'];
			$model->drvid			 = $arr['drvid'];
			$model->userid			 = $arr['userid'];
			$model->admid			 = $arr['admid'];
			$model->ntl_ref_type	 = $arr['ntl_ref_type'];
		}
		else
		{
			$arr['ntl_created_on1']	 = $model->ntl_created_on1	 = date('Y-m-d', strtotime("-1 days")) . " 00:00:00";
			$arr['ntl_created_on2']	 = $model->ntl_created_on2	 = date('Y-m-d') . " 23:59:59";
			$arr['ntl_entity_type']	 = $model->ntl_entity_type	 = '';
			$arr['ntl_ref_type']	 = $model->ntl_ref_type	 = '';
		}
		$dataProvider = NotificationLog::getNotificationLogList($model);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render("list", array
			(
			"dataProvider"	 => $dataProvider,
			"qry"			 => $qry,
			"model"			 => $model, 'roles'			 => $row
		));
	}

	public function actionWhatsappLog()
	{
		$row = Report::getRoleAccess(103);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Whatsapp Log"; //Sets the page title
		$pageSize		 = Yii::app()->params["listPerPage"];
		$request		 = Yii::app()->request;
		$arr			 = $request->getParam('WhatsappLog');
		$model			 = new WhatsappLog('search');

		if ($arr)
		{
			$model->attributes			 = $arr;
			$model->phoneno				 = $model->whl_phone_number	 = $arr['phoneno'];
			$model->whl_created_on1		 = !empty($arr['whl_created_on1']) ? date('Y-m-d', strtotime($arr['whl_created_on1'])) . " 00:00:00" : "";
			$model->whl_created_on2		 = !empty($arr['whl_created_on2']) ? date('Y-m-d', strtotime($arr['whl_created_on2'])) . " 23:59:59" : "";
			$model->sendDate1			 = !empty($arr['sendDate1']) ? date('Y-m-d', strtotime($arr['sendDate1'])) . " 00:00:00" : "";
			$model->sendDate2			 = !empty($arr['sendDate2']) ? date('Y-m-d', strtotime($arr['sendDate2'])) . " 23:59:59" : "";
			$model->deliveryDate1		 = !empty($arr['deliveryDate1']) ? date('Y-m-d', strtotime($arr['deliveryDate1'])) . " 00:00:00" : "";
			$model->deliveryDate2		 = !empty($arr['deliveryDate2']) ? date('Y-m-d', strtotime($arr['deliveryDate2'])) . " 23:59:59" : "";
			$model->readDate1			 = !empty($arr['readDate1']) ? date('Y-m-d', strtotime($arr['readDate1'])) . " 00:00:00" : "";
			$model->readDate2			 = !empty($arr['readDate2']) ? date('Y-m-d', strtotime($arr['readDate2'])) . " 23:59:59" : "";
			$model->whl_ref_type		 = $arr['whl_ref_type'];
			$model->whl_ref_id			 = $arr['whl_ref_id'];
			$model->whl_status			 = $arr['whl_status'];
			$model->whl_created_by_type	 = $arr['whl_created_by_type'];
			$model->templatename		 = $arr['templatename'];
		}
		else
		{
			if ($request->getParam('bookingId') > 0)
			{
				$model->whl_ref_type = 1;
				$model->whl_ref_id	 = $request->getParam('bookingId');
			}
			else
			{
				$model->whl_created_on1	 = date('Y-m-d') . " 00:00:00";
				$model->whl_created_on2	 = date('Y-m-d') . " 23:59:59";
				$model->sendDate1		 = "";
				$model->sendDate2		 = "";
				$model->deliveryDate1	 = "";
				$model->deliveryDate2	 = "";
				$model->readDate1		 = "";
				$model->readDate2		 = "";
				$model->templatename	 = "";
			}
			$model->phoneno = "";
		}
		$dataProvider = WhatsappLog::getWhatsappLogList($model);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render("whatsapplog", array(
			"dataProvider"	 => $dataProvider, "model"			 => $model, 'roles'			 => $row
		));
	}

	public function actionInterestedDCOTracking()
	{
		$row = Report::getRoleAccess(132);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle = "Interested DCO Tracking";
		$model			 = new DcoInterestedTracking();
		$request		 = Yii::app()->request;
		if ($request->getParam('DcoInterestedTracking'))
		{
			$arr				 = $request->getParam('DcoInterestedTracking');
			$model->create_date1 = !empty($arr['create_date1']) ? date('Y-m-d', strtotime($arr['create_date1'])) . " 00:00:00" : "";
			$model->create_date2 = !empty($arr['create_date2']) ? date('Y-m-d', strtotime($arr['create_date2'])) . " 23:59:59" : "";
		}
		else
		{
			$model->create_date1 = date("Y-m-d", strtotime("-7 day", time())) . " 00:00:00";
			$model->create_date2 = date('Y-m-d') . " 23:59:59";
		}
		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true" && isset($_REQUEST['export_create_date1']) && isset($_REQUEST['export_create_date2']))
		{
			$date1		 = Yii::app()->request->getParam('export_create_date1');
			$date2		 = Yii::app()->request->getParam('export_create_date2');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"DcoInterestedTracking_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "DcoInterestedTracking_" . date('YmdHi') . ".csv";
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
			$rows	 = DcoInterestedTracking::getCountByDate($date1, $date2, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Date', 'Total Sent', 'Total Delivered', 'Total Read', 'Link Opened', 'Download Attempted', 'Logged In']);
			foreach ($rows as $data)
			{
				$rowArray					 = array();
				$rowArray['date']			 = $data['date'];
				$rowArray['sentCount']		 = $data['sentCount'];
				$rowArray['deliveredCount']	 = $data['deliveredCount'];
				$rowArray['readCount']		 = $data['readCount'];
				$rowArray['clickedCount']	 = $data['clickedCount'];
				$rowArray['downloadCount']	 = $data['downloadCount'];
				$rowArray['loginCount']		 = $data['loginCount'];
				$row1						 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}
		$dataProvider = DcoInterestedTracking::getCountByDate($model->create_date1, $model->create_date2);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('dcoDownload', array("dataProvider" => $dataProvider, 'model' => $model, 'roles' => $row));
	}

	public function actionShowMsg()
	{
		$this->pageTitle = "Show Whatsapp Message";
		$whl_id			 = Yii::app()->request->getParam('whlId');
		$model			 = WhatsappLog::model()->findByPk($whl_id);
		$message		 = "";
		if ($model && $model->whl_wht_id > 0 && $model->whl_status != 4)
		{
			$templateContent		 = WhatsappLog::getTemplateNameById($model->whl_wht_id);
			$message				 = $templateContent['wht_template_content'];
			$language				 = TemplateMaster::languageByLangCode($templateContent['wht_lang_code']);
			$messageTemplateIndex	 = json_decode($model->whl_message);
			if (preg_match_all("~\{\{\s*(.*?)\s*\}\}~", $message, $arr))
			{
				foreach ($arr[1] as $row)
				{
					$message = str_replace('{{' . $row . '}}', $messageTemplateIndex[$row - 1]->text, $message);
				}
			}
		}
		else
		{
			$message = $model->whl_message;
		}
		$this->renderPartial('showmsg', array('message' => $message, 'language' => $language, 'model' => $model), false, false);
	}

	public function actionRecdWhatsappLog()
	{
		$row = Report::getRoleAccess(139);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}

		$this->pageTitle = "Received Whatsapp Log"; //Sets the page title
		$model			 = new WhatsappLog();

		if (isset($_REQUEST['WhatsappLog']))
		{
			$arr					 = Yii::app()->request->getParam('WhatsappLog'); //print_r($arr);die;
			$model->attributes		 = $arr;
			$phoneNo				 = $arr['phoneno'];
			$model->whl_created_on1	 = !empty($arr['whl_created_on1']) ? date('Y-m-d', strtotime($arr['whl_created_on1'])) . " 00:00:00" : "";
			$model->whl_created_on2	 = !empty($arr['whl_created_on2']) ? date('Y-m-d', strtotime($arr['whl_created_on2'])) . " 23:59:59" : "";
		}
		else
		{
			$model->whl_created_on1	 = date('Y-m-d') . " 00:00:00";
			$model->whl_created_on2	 = date('Y-m-d') . " 23:59:59";
			$phoneNo				 = '';
		}
		$model->phoneno	 = $phoneNo;
		$dataProvider	 = WhatsappLog::getRecdWhatsappLogList($model);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render("recdwhatsapplog", array(
			"dataProvider"	 => $dataProvider,
			"model"			 => $model, 'roles'			 => $row
		));
	}

	public function actionShowWhatsappMsg()
	{
		$this->pageTitle = "Show Whatsapp Received and Send Message";
		$whlId			 = Yii::app()->request->getParam('whlId');
		$model			 = WhatsappLog::model()->findByPk($whlId);
		$message		 = "";
		if ($model)
		{
			$message = WhatsappLog::getTemplateNameByPhone($model->whl_phone_number);
		}
		$this->renderPartial('showwhatsappmsg', array('messages' => $message, 'model' => $model), false, false);
	}

}
