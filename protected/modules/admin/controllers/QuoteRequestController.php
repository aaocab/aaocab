<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class QuoteRequestController extends Controller
{
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

	public function accessRules()
	{
		return array(
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('create', 'list', 'detail'),
				'users'		 => array('@'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function loadModel($id)
	{
		$model = CustomQuote::model()->findByPk($id);
		if ($model === null)
		{
			throw new CHttpException(404, 'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * This function is used for adding quote request
	 */
	public function actionCreate()
	{
		$this->pageTitle = "Add Quote Request";
		$model			 = new CustomQuote();
		$cqt_id			 = Yii::app()->request->getParam('qotid');
		if ($cqt_id > 0)
		{
			$model = $this->loadModel($cqt_id);
		}
		$success = false;
		if (isset($_POST['CustomQuote']))
		{
			$arr = Yii::app()->request->getParam('CustomQuote');
			if ($arr['cqt_id'] > 0)
			{
				unset($arr['cqt_id']);
//				$model				 = $this->loadModel($arr['cqt_id']);
				$model->isNewRecord	 = true;
				$model->cqt_id		 = null;
				$model->cqt_created	 = new CDbExpression('NOW()');
			}

			$model->attributes			 = $arr;
			$date						 = DateTimeFormat::DatePickerToDate($model->cqt_pickup_date_date);
			$time						 = date('H:i:00', strtotime($model->cqt_pickup_date_time));
			$model->cqt_pickup_date		 = $date . ' ' . $time;
			$model->cqt_user_entity_type = UserInfo::getUserType();
			$model->cqt_user_entity_id	 = UserInfo::getUserId();

			if ($model->validate())
			{
				$model->save();
				$success = true;
			}
		}


		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('create', ['model' => $model, 'success' => $success], false, $outputJs);
	}

	public function actionList()
	{
		$this->pageTitle = 'Quote Request List';
		$model			 = new CustomQuote();
		$qry			 = [];
		$qry			 = Yii::app()->request->getParam('CustomQuote');
		if (isset($qry))
		{
			$qry['regionId']	 = $qry['source_region'];
			$model->attributes	 = $qry;
		}
		$dataProvider = CustomQuote::fetchList($qry);

		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$this->render('list', ['model' => $model, 'dataProvider' => $dataProvider]);
	}

	public function actionDetail()
	{
		$cqt_id	 = Yii::app()->request->getParam('qotid');
		$data	 = VendorQuote::getQuotesByRequest($cqt_id);
		$this->renderPartial('detail', ['data' => $data], false, true);
	}
}
