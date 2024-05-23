<?php

class TermsController extends Controller
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
				'actions'	 => array('add', 'list', 'delterms', 'addpoints', 'listPoints', 'delpoints'),
				'users'		 => array('@'),
			),
			['allow', 'actions' => ['listPoints'], 'roles' => ['pointList']],
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

	public function actionAdd()
	{
		$this->pageTitle = "Add Terms & Conditions";
		$id				 = Yii::app()->request->getParam('tnc_id');
		$selected		 = [];
		if ($id != "")
		{
			$model			 = Terms::model()->findByPk($id);
			$this->pageTitle = "Edit Terms & Conditions";
		}
		else
		{
			$model = new Terms();
		}
		$status = "";
		if (isset($_REQUEST['Terms']))
		{
			$arr1				 = Yii::app()->request->getParam('Terms');
			$model->attributes	 = $arr1;
			$date				 = DateTimeFormat::DatePickerToDate($arr1['tnc_updated_at']);
			// $model->tnc_updated_at = ($arr1['tnc_updated_at'] == '') ? '' : DateTimeFormat::DatePickerToDate($arr1['tnc_updated_at']);

			$result					 = CActiveForm::validate($model);
			$model->tnc_updated_at	 = $date;

			if ($result == '[]')
			{
				$model->save();
				if ($id != "")
				{
					$status = "Terms & Conditions Modified Successfully";
				}
				else
				{
					$status = "Terms & Conditions  Added Successfully";
				}
			}
		}
		$this->render('add', array('model' => $model, 'status' => $status));
	}

	public function actionList()
	{
		$this->pageTitle = "Terms & Conditions";
		$model			 = new Terms();

		$dataProvider = $model->termsListing();
		$this->render('list', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionAddPoints()
	{
		$this->pageTitle = "Add Terms Points";
		$id				 = Yii::app()->request->getParam('tnp_id');
		if ($id != "")
		{
			$model			 = TncPoints::model()->findByPk($id);
			$this->pageTitle = "Edit Terms Points";
		}
		else
		{
			$model = new TncPoints();
		}
		if (!empty($_REQUEST['TncPoints']))
		{
			$arr1				 = Yii::app()->request->getParam('TncPoints');
			$model->tnp_c_type	 = $arr1['tnp_c_type'];
			$model->tnp_text	 = $arr1['tnp_text'];
			$model->tnp_active	 = 1;
			$result				 = CActiveForm::validate($model);
			if ($result == '[]')
			{
				$model->tnp_tier		 = implode(',', $arr1['tnp_tier']);
				$model->tnp_for			 = implode(',', $arr1['tnp_for']);
				$model->tnp_trip_type	 = implode(',', $arr1['tnp_trip_type']);
				$model->save();
				if ($id != "")
				{
					$status = "Terms Points Modified Successfully";
				}
				else
				{
					$status = "Terms Points Added Successfully";
				}
				$this->redirect('admin/terms/listPoints', array('status' => $status));
			}
		}

		$this->render('addPoints', ['model' => $model]);
	}

	public function actionListPoints()
	{
		$this->pageTitle = "Terms Points";
		$model			 = new TncPoints();
		if ($_REQUEST['TncPoints'])
		{
			$arr			 = $_REQUEST['TncPoints'];
			$model->tnp_for	 = $arr['tnp_for'];
		}
		$dataProvider = $model->getList($model->tnp_for);


		$this->render('listPoints', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionDelpoints()
	{
		$id = Yii::app()->request->getParam('tnp_id');
		if ($id > 0)
		{
			$model = TncPoints::model()->findByPk($id);
			if (count($model) == 1)
			{
				$model->tnp_active = 0;
				$model->save();
			}
		}
		$this->redirect(array('listPoints'));
	}

	public function actionDelterms()
	{

		$id = Yii::app()->request->getParam('tnc_id');
		if ($id != '')
		{
			$model = Terms::model()->findByPk($id);
			if (count($model) == 1)
			{

				$model->tnc_active = 0;
				$model->save();
			}
		}
		$this->redirect(array('list'));
	}

}
