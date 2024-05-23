<?php

use Shuttle;

include_once(dirname(__FILE__) . '/BaseController.php');

class ShuttleController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = 'column1';
	public $newHome		 = '';
	public $email_receipient, $useUserReturnUrl;
	public $current_page = '';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter',
				'bypass' => false),
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete,pickup,rates', // we only allow deletion via POST request
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
				'actions'	 => array('getpickupcitylist', 'getpickuploc', 'getdropcitylist',
					'getdroploc', 'getavailable'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array('admin'), 'users'		 => array('admin'),
			),
			['allow', 'actions' => ['invoice'], 'users' => ['*']],
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function restEvents()
	{
		$this->onRest('req.cors.access.control.allow.methods', function()
		{
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});


		$this->onRest('post.filter.req.auth.user', function($validation)
		{
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array();
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});
	}

	public function actionIndex1()
	{
		$this->redirect('/');
	}

	public function actionGetpickupcitylist()
	{
//		header('Cache-Control: max-age=28800, public', true);
		$query	 = Yii::app()->request->getParam('q');
		$city	 = Yii::app()->request->getParam('city');
		$dateVal = Yii::app()->request->getParam('dateVal');
		$pdate	 = ($dateVal != '') ? DateTimeFormat::DatePickerToDate($dateVal) : '';

		$datafromcity = Cities::model()->getJSONShuttleSourceCities($query, $city, $pdate);

		echo $datafromcity;
		Yii::app()->end();
	}

	public function actionGetpickuploc()
	{
		$dateVal	 = Yii::app()->request->getParam('dateVal');
		$pdate		 = ($dateVal != '') ? DateTimeFormat::DatePickerToDate($dateVal) : '';
		$fcityVal	 = Yii::app()->request->getParam('fcityVal');

		$datafromcity = Cities::model()->getJSONShuttlePickup($fcityVal, $pdate);

		echo $datafromcity;
		Yii::app()->end();
	}

	public function actionGetdropcitylist()
	{
		$arr			 = [];
		$dateVal		 = Yii::app()->request->getParam('dateVal');
		$arr['pdate']	 = ($dateVal != '') ? DateTimeFormat::DatePickerToDate($dateVal) : '';
		$arr['fcityVal'] = Yii::app()->request->getParam('fcityVal');
		$arr['fcityLoc'] = Yii::app()->request->getParam('fcityLoc');
		$datafromcity	 = Cities::model()->getJSONShuttledest($arr);
		echo $datafromcity;
		Yii::app()->end();
	}

	public function actionGetdroploc()
	{
		$arr			 = [];
		$dateVal		 = Yii::app()->request->getParam('dateVal');
		$arr['pdate']	 = ($dateVal != '') ? DateTimeFormat::DatePickerToDate($dateVal) : '';
		$arr['fcityVal'] = Yii::app()->request->getParam('fcityVal');
		$arr['fcityLoc'] = Yii::app()->request->getParam('fcityLoc');
		$arr['tcityVal'] = Yii::app()->request->getParam('tcityVal');
		$dataTocity		 = Cities::model()->getJSONShuttleDrop($arr);
		echo $dataTocity;
		Yii::app()->end();
	}

	public function actionGetavailable()
	{
		$arr			 = [];
		$dateVal		 = Yii::app()->request->getParam('dateVal');
		$arr['pdate']	 = ($dateVal != '') ? DateTimeFormat::DatePickerToDate($dateVal) : '';
		$arr['fcityVal'] = Yii::app()->request->getParam('fcityVal');
		$arr['fcityLoc'] = Yii::app()->request->getParam('fcityLoc');
		$arr['tcityVal'] = Yii::app()->request->getParam('tcityVal');
		$arr['tcityLoc'] = Yii::app()->request->getParam('tcityLoc');
		$data			 = Shuttle::model()->getJSONList($arr);
		echo $data;
		Yii::app()->end();
	}

 

}
