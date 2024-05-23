<?php

class BroadcastNotificationController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';
	public $email_receipient;
	public $pageTitle;

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
			['allow', 'actions' => ['add', 'list', 'changestatus'], 'roles' => ['broadcastNotificationAdd']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array(''),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(''),
				'users'		 => array('*'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * This function is used for send notification
	 * @return type
	 * @throws Exception
	 */
	public function actionAdd()
	{
		$returnSet		 = new ReturnSet();
		$this->pageTitle = "Self Service Notification Panel ";
		$model			 = new BroadcastNotification();
		$data			 = Yii::app()->request->getParam('BroadcastNotification');
		if ($data > 0)
		{
			try
			{
                $adminId	 = UserInfo::getUserId();
				$model->bcn_form_input	 = CJSON::encode($data);
				$date					 = DateTimeFormat::DatePickerToDate($data['bcn_date']);
				$time					 = DateTime::createFromFormat('h:i A', $data['bcn_time'])->format('H:i:00');
				$model->bcn_schedule_for = $date . " " . $time;
				$model->bcn_user_type	 = $data['bcn_user_type'];
                $model->bcn_user_id      = $adminId;
				$model->bcn_query		 = BroadcastNotification::buildNotificationQuery($data);
				if ($model->save())
				{
					$success = true;
					$msg	 = "Data Saved Successfully";
				}
                
			}
			catch (Exception $ex)
			{
				$success = false;
				$errors	 = $model->getErrors();
				Yii::app()->end();
			}
			$data			 = ['success' => $success, 'errors' => $errors, 'msg' => $msg];
			echo $jsonFormData	 = json_encode($data);
			Yii::app()->end();
		}


		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('add', array('model' => $model), false, $outputJs);
	}

	public function actionChangestatus()
	{

		$actid	 = Yii::app()->request->getParam('activateid');
		$inactid = Yii::app()->request->getParam('disableid');
		if ($actid > 0)
		{
			$model = BroadcastNotification::model()->findByPk($actid);
			if (count($model) == 1)
			{
				$model->bcn_active = 2;
				$model->save();
			}
		}
		if ($inactid > 0)
		{
			$model = BroadcastNotification::model()->findByPk($inactid);
			if (count($model) == 1)
			{
				$model->bcn_active = 1;
				$model->save();
			}
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			echo "true";
			Yii::app()->end();
		}
		$this->redirect(array('list'));
	}

	public function actionList()
	{
		$this->pageTitle = "Notification List";
		$model			 = new BroadcastNotification();
		$dataProvider	 = $model->getList();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('list', array('model' => $model, 'dataProvider' => $dataProvider));
	}

}
