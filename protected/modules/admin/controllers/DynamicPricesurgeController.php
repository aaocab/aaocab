<?php

class DynamicPricesurgeController extends Controller
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
		$this->pageTitle = "Price Surge List";
		$model			 = new DynamicPriceSurge();
		
		$searchTable = Yii::app()->request->getParam('table_name');
		if($searchTable!='')
		{
			$tbName = $searchTable;
		}
		else
		{
			$tbName = 'dynprice_delhi___agra';
		}
		
		
		$dataProvider = $model->getList($tbName);
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$getTableList = $model->getAllTableList();
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('list', array('model' => $model, 'dataProvider' => $dataProvider, 'getTableList' => $getTableList), false, $outputJs);
	}
	
	public function getJSON($arr = [])
	{
		//$carList = $this->getVehicleTypeList();
		$arrJSON = array();
		foreach ($arr as $key => $val)
		{
			$arrJSON[] = array("id" => $key, "text" => $val);
		}
		$data = CJSON::encode($arrJSON);
		return $data;
	}
	
	public function actionEdit()
	{
		
	}
	
	public function actionDynamicList()
	{
		$this->pageTitle = "Dynamic Price Surge List";
		$model			 = new PriceSurge();

		$searchTable = Yii::app()->request->getParam('table_name');
		if($searchTable!='')
		{
			$searchTable= $searchTable;
		}else{
			$searchTable = "dynprice_Agra___delhi";
		}
		$searchTable;
		$getTableList = $model->getAllTableList();
		$noOfPage = Yii::app()->request->getParam('pid');
		$dataProvider = $model->getDynamicSurgeList($noOfPage,$searchTable);
		$countQuery = $model->getDynamicSurgeCount($searchTable);
		$count =  $countQuery['tot'];
		$no_of_page = ceil($count/10); 
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('dynamiclist', array('model' => $model, 'dataProvider' => $dataProvider, 'getTableList' => $getTableList,'no_of_page'=>$no_of_page, 'searchTable'=>$searchTable), false, $outputJs);
	}
	
	public function actionDynamicPriceEdit()
	{
		echo "<pre>";
		print_r($_REQUEST);
	}

}
