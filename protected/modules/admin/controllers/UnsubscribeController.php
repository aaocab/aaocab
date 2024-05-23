<?php

class UnsubscribeController extends Controller
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
			['allow', 'actions' => ['add', 'create', 'delete']],
			['allow', 'actions' => ['list']],
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionAdd($status = null)
	{
		$this->pageTitle = "Unsubscribe Add";
		/* @var $model Unsubscribe */

		$usbId = Yii::app()->request->getParam('usb_id');
		if ($usbId != '')
		{
			$model = Unsubscribe::model()->findByPk($usbId);
		}
		else
		{
			$model = new Unsubscribe();
		}
		if (isset($_POST['Unsubscribe']))
		{
			$model->attributes = Yii::app()->request->getParam('Unsubscribe');
			if ($model->validate())
			{
				$model->save();
				$this->redirect(array('list'));
			}
			else
			{
				$errors = $model->getErrors();
			}
		}
		$this->render('unsubscribe_add', array('model' => $model));
	}

	public function actionList($qry = [])
	{
		$this->pageTitle = "Unsubscribe List";
		$pageSize		 = Yii::app()->params['listPerPage'];
		$model			 = new Unsubscribe();
		$dataProvider	 = $model->getAll();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('unsubscribe_list', array('model' => $model, 'dataProvider' => $dataProvider, 'qry' => $qry));
	}

	public function actionDelete()
	{
		$model	 = new Unsubscribe();
		$usbId	 = Yii::app()->request->getParam('usb_id');
		if ($usbId != '')
		{
			$model->updateActive($usbId);
		}
		$this->render('unsubscribe_list', array('model' => $model));
	}

}
