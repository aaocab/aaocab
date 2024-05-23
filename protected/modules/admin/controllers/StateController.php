<?php

class StateController extends Controller
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
			['allow', 'actions' => ['add', 'create']],
			['allow', 'actions' => ['add']],
			['allow', 'actions' => ['list']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('cityfromstate', 'del', 'destination',
					'checkvehiclestatus', 'adddestination', 'linkapproval', 'placeapproval', 'json', 'checkcityname', 'updateLatLongByAddress', 'updateRouteDistTime', 'ajaxadd', 'cityname','getStateList'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index', 'json', 'getnames', 'selectcities', 'getcitydetails'),
				'users'		 => array('*'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionList($qry = [])
	{
		$this->pageTitle = "State List";
		$pageSize		 = Yii::app()->params['listPerPage'];

		$model = new States('search');
		if (isset($_REQUEST['States']))
		{
			$model->attributes = Yii::app()->request->getParam('States');
		}
		$dataProvider = $model->getList();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('list', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'qry'			 => $qry));
	}
	
	public function actionGetStateList()
	{
		$areaArr	 = States::model()->getJSON();
		echo $areaArr;
		Yii::app()->end();
	}

}
