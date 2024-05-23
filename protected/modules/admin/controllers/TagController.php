<?php

class TagController extends Controller
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
			['allow', 'actions' => ['add']],
			['allow', 'actions' => ['list']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array(),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(),
				'users'		 => array('*'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionList($qry = [])
	{
		$this->pageTitle = "Tag List";
		$pageSize		 = Yii::app()->params['listPerPage'];

		$model	 = new Tags('search');
		$qry	 = [];
		if (isset($_REQUEST['Tags']))
		{
			$qry				 = Yii::app()->request->getParam('Tags');
			$model->attributes	 = $qry;
		}
		$dataProvider = Tags::fetchList($qry);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('list', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'qry'			 => $qry));
	}

	public function actionAdd()
	{
		$this->pageTitle = "Add Tag";
		$tagid			 = Yii::app()->request->getParam('tagid');
		$model			 = new Tags();

		if ($tagid > 0)
		{
			$this->pageTitle = "Edit Tag";
			$model			 = Tags::model()->findByPk($tagid);
		}

		if (isset($_POST['Tags']))
		{
			$postData = $_POST['Tags'];
			try
			{
				$model->attributes	 = $postData;
				$model->tag_name	 = strtoupper(trim($postData['tag_name']));
				$model->tag_desc	 = trim($postData['tag_desc']);
				if ($model->validate())
				{
					$model->save();
				}
			}
			catch (Exception $ex)
			{
				
			}
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('add', array('model' => $model), false, $outputJs);
	}
}
