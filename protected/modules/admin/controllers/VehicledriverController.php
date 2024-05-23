<?php

class VehicledriverController extends Controller
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
				'actions'	 => array('add', 'cityfromstate'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index', 'add'),
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

	public function actionAdd($status = null)
	{
		exit;
		/*NOT USED*/
		$drvid	 = $_GET['drvid'];
		$model	 = Drivers::model()->findById($drvid);
		if ($model == "")
		{
			$model = new Drivers;
		}
		else
		{
			$model->drv_modified = new CDbExpression('NOW()');
			$model->chk			 = $model->drv_bg_checked;
		}
		if (isset($_POST['Drivers']))
		{
			$model->attributes		 = $_POST['Drivers'];
			$model->drv_ip			 = trim(\Filter::getUserIP());
			$model->drv_device		 = $_SERVER['HTTP_USER_AGENT'];
			$model->drv_doj			 = ($_POST['Drivers']['drv_doj'] != "") ? date('Y-m-d', strtotime($_POST['Drivers']['drv_doj'])) : "";
			$model->drv_lic_exp_date = ($_POST['Drivers']['drv_lic_exp_date'] != "") ? date('Y-m-d', strtotime($_POST['Drivers']['drv_lic_exp_date'])) : "";
			$checked				 = $_POST['Drivers']['chk'];
			$chkvalue				 = implode(',', array_values($checked));
			$model->drv_bg_checked	 = $chkvalue;

			$model->save();
			$status = "added";
			$this->redirect(array('list'));
		}
		$this->render('add', array('model' => $model));
	}

	public function actionCityfromstate()
	{

		$stateId	 = Yii::app()->request->getParam('id');
		$cityList	 = CHtml::listData(Cities::model()->findAll(array("condition" => "cty_state_id = $stateId")), 'cty_id', 'cty_name');
		echo CJSON::encode(array('citylist' => $cityList));
	}

}
