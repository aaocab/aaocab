<?php

class NotificationController extends Controller
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
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('list','send'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index',),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array('admin'),
				'users'		 => array('admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	/*
	 * This action is used for List down all the notification log entries with filters
	 * return view
	 */

	public function actionList()
	{
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
			"model"			 => $model,
		));
	}

	public function actionSend()
	{
		$model = new NotificationLog();
		$this->render("add", [
			"model" => $model,
				]
		);
	}

}
