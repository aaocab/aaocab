<?php

class AutoCancelRuleController extends Controller
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
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('add', 'list', 'changestatus'),
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

	public function actionList($qry = [])
	{
		$this->pageTitle = "Auto Cancel Rule List";
		$model			 = new AutoCancelRule();
		$request		 = Yii::app()->request;
		if ($request->getParam('AutoCancelRule'))
		{
			$model->attributes		 = $request->getParam('AutoCancelRule');
			$model->acr_service_tier = $request->getParam('acr_service_tier') ? implode($request->getParam('acr_service_tier'), ',') : '';
			$model->acr_bkg_type	 = $request->getParam('acr_bkg_type') ? implode($request->getParam('acr_bkg_type'), ',') : '';
		}

		$dataProvider = $model->getList();
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);

		$outputJs	 = $request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('list', array('model' => $model, 'dataProvider' => $dataProvider), null, $outputJs);
	}

	public function actionAdd()
	{
		$this->pageTitle = "Add Auto Cancel Rule";
		$model			 = new AutoCancelRule('insert');
		$returnSet		 = new ReturnSet();
		$request		 = Yii::app()->request;
		$acr_id			 = $request->getParam("id");
		if ($acr_id > 0)
		{
			$this->pageTitle = "Edit Auto Cancel Rule";
			$model			 = AutoCancelRule::model()->findByPk($acr_id);
		}
		$errors = [];
		if ($request->getParam('AutoCancelRule'))
		{
			$model->attributes				 = $request->getParam('AutoCancelRule');
			$model->acr_service_tier		 = $request->getParam('acr_service_tier') ? implode($request->getParam('acr_service_tier'), ',') : '';
			$model->acr_bkg_type			 = $request->getParam('acr_bkg_type') ? implode($request->getParam('acr_bkg_type'), ',') : '';
			$model->acr_auto_cancel_value	 = $request->getParam('acr_auto_cancel_value') ? implode($request->getParam('acr_auto_cancel_value'), ',') : '';
			$model->acr_auto_cancel_value	 = $model->acr_auto_cancel_value != null ? $model->acr_auto_cancel_value : 1;
			$model->acr_auto_cancel_code	 = $model->acr_auto_cancel_code != null ? $model->acr_auto_cancel_code : 35;
			$result							 = CActiveForm::validate($model);
			if ($result == '[]')
			{
				$returnSet = $model->add();
				$this->redirect(array('AutoCancelRule/List'));
			}
			else
			{
				foreach (CJSON::decode($result) as $value)
				{
					array_push($errors, $value);
				}
				$returnSet->setStatus(false);
			}
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('form', array('model' => $model, 'returns' => $returnSet), false, $outputJs);
	}

	public function actionChangeStatus()
	{
		$acr_id = Yii::app()->request->getParam('id');
		if ($acr_id > 0)
		{
			$model				 = AutoCancelRule::model()->findByPk($acr_id); //Returns the data to the view
			$model->acr_status	 = $model->acr_status == 1 ? 0 : 1;
			$success			 = $model->save();
			$data				 = ['success' => $success];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

}
