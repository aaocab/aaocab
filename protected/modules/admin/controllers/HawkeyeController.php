<?php

class HawkeyeController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';

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
			//	['allow', 'actions' => ['surgeform'], 'roles' => ['pricesurge']],
			['allow', 'actions' => ['list', 'showlog', 'dynamicList', 'dynamicPriceEdit'], 'roles' => ['surgeList']],
			['allow', 'actions' => ['surgeform', 'delete1'], 'roles' => ['surgeUpdate']],
			['deny', 'users' => ['*']],
		);
	}

	public function actionList()
	{
		$this->pageTitle = "Hawkeye List";
		$model			 = new Hawkeye();
                $hpl_pickup_date1 = $_REQUEST['Hawkeye']['hpl_pickup_date1'];
                $hpl_pickup_date2 = $_REQUEST['Hawkeye']['hpl_pickup_date2'];
                if ($hpl_pickup_date1 != '' && $hpl_pickup_date2 != '') {
                 $date1 = DateTimeFormat::DatePickerToDate($hpl_pickup_date1);
                 $date2 = DateTimeFormat::DatePickerToDate($hpl_pickup_date2);
                 }
                else {
                $date1 = date('Y-m-d');
                $date2 = date('Y-m-d', strtotime("+90 days"));
                }
		if (isset($_REQUEST['Hawkeye']))
		{	
			$arr	 = Yii::app()->request->getParam('Hawkeye');			
			$from	 = $arr['fromCity'];
			$to      = $arr['toCity'];
		}
		else
		{
			$from	 = '';
			$to		 = '';
			$tempo	 = '';
		}
		$model->hpl_pickup_date1= $date1;
		$model->hpl_pickup_date2= $date2;
		$model->fromCity= $arr['fromCity'];
                $model->toCity= $arr['toCity'];
		$dataProvider	 = $model->getList($date1, $date2, $from, $to);
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('list', array('model' => $model, 'dataProvider' => $dataProvider), false, $outputJs);
	}

}
