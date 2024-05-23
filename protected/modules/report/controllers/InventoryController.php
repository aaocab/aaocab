<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class InventoryController extends Controller
{

	public $layout = 'admin1';

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + flush', // we only allow deletion via POST request
		);
	}

	public function accessRules()
	{
		return array(
			['allow', 'actions' => ['inventoryShortage', 'zoneCsv'], 'roles' => ['Vendor']],
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index'),
				'users'		 => array('*'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionInventoryShortage()
	{
		$row = Report::getRoleAccess(58);
		if ($row['rpt_roles'] != null)
		{
			$roleAccess = Filter::checkACL($row['rpt_roles']);
			if (!$roleAccess)
			{
				throw new CHttpException(403, "You are not authorized to perform this action", ReturnSet::ERROR_UNAUTHORISED);
			}
		}
		$this->pageTitle	 = "Inventory Shortage Report";
		$model				 = new Booking();
		$model->zero_percent = 1;
		if (isset($_REQUEST['Booking']))
		{
			$arr						 = Yii::app()->request->getParam('Booking');
			$model->bkg_pickup_date1	 = $arr['bkg_pickup_date1'];
			$model->bkg_pickup_date2	 = $arr['bkg_pickup_date2'];
			$model->bkg_create_date1	 = $arr['bkg_create_date1'];
			$model->bkg_create_date2	 = $arr['bkg_create_date2'];
			$model->bkg_cancel_id		 = $arr['bkg_cancel_id'];
			$model->dem_sup_misfireCount = $arr['dem_sup_misfireCount'];
			$model->total_completedCount = $arr['total_completedCount'];
			$model->zero_percent		 = ($arr['zero_percent'] == 'on') ? 1 : 0;
		}
		if (!$model->dem_sup_misfireCount)
		{
			$model->dem_sup_misfireCount = 10;
		}
		if ($arr['bkg_create_date1'] == '')
		{
			$model->bkg_create_date1 = "";
			$model->bkg_create_date2 = "";
		}
		if ($arr['bkg_pickup_date1'] == '')
		{
			$model->bkg_pickup_date1 = date('Y-m-d', strtotime("first day of this month"));
			$model->bkg_pickup_date2 = date('Y-m-d', strtotime("last day of this month"));
		}
		if ($arr['bkg_cancel_id'] == '')
		{
			$model->bkg_cancel_id = '9,17';
		}

		if (isset($_REQUEST['bkg_pickup_date2']) && $_REQUEST['bkg_pickup_date1'])
		{
			$model->bkg_pickup_date1	 = Yii::app()->request->getParam('bkg_pickup_date1');
			$model->bkg_pickup_date2	 = Yii::app()->request->getParam('bkg_pickup_date2');
			$model->bkg_create_date1	 = Yii::app()->request->getParam('bkg_create_date1');
			$model->bkg_create_date2	 = Yii::app()->request->getParam('bkg_create_date2');
			$model->bkg_cancel_id		 = explode(",", Yii::app()->request->getParam('bkg_cancel_id'));
			$model->dem_sup_misfireCount = Yii::app()->request->getParam('dem_sup_misfireCount');
			$model->total_completedCount = Yii::app()->request->getParam('total_completedCount');
			$model->zero_percent		 = Yii::app()->request->getParam('zero_percent');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"InventoryShortageReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename					 = "InventoryShortageReport_" . date('Ymdhis') . ".csv";
			$foldername					 = Yii::app()->params['uploadPath'];
			$backup_file				 = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows	 = BookingSub::model()->getInventorySortage($model, DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['From Zone', 'To Zone', 'Count Of booking with DemSup_misfire', 'Count of booking with Need more supply', 'Count of booking with selected cancelletions reasons', 'Total(NMI+DemSup+Cancel)', 'Total Completed', 'Percentage']);

			foreach ($rows as $row)
			{
				$rowArray				 = array();
				$rowArray['fzoneName']	 = $row['fzoneName'];
				$rowArray['tzoneName']	 = $row['tzoneName'];
				$rowArray['cntdemsup']	 = $row['cntdemsup'];
				$rowArray['cntnmi']		 = $row['cntnmi'];
				$rowArray['cntreason']	 = $row['cntreason'];
				$rowArray['tot']		 = $row['tot'];
				$rowArray['complete']	 = $row['complete'];
				$rowArray['percentage']	 = $row['percentage'];
				$row1					 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}


		$dataProvider		 = BookingSub::model()->getInventorySortage($model);
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$getInventoryZone	 = InventoryRequest::model()->getZoneCount();
		$outputJs			 = Yii::app()->request->isAjaxRequest;
		$method				 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('inventory_sortage', array('model' => $model, 'dataProvider' => $dataProvider, 'countZone' => $getInventoryZone, 'roles' => $row), false, $outputJs);
	}

}
