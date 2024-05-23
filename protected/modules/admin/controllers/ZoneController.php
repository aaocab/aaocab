<?php

class ZoneController extends Controller
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
			['allow', 'actions' => ['add', 'create'], 'roles' => ['zoneAdd']],
			['allow', 'actions' => ['list', 'showlog'], 'roles' => ['zoneList']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('cityfromstate', 'del',
					'checkvehiclestatus', 'json', 'checkcityname', 'ajaxadd', 'cityname', 'getZoneList'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index', 'json', 'getnames', 'selectcities', 'volumetrend',
					'getvendorcityzone', 'getcityzone', 'getZoneList', 'manageServiceZone', 'removeServiceZone'),
				'users'		 => array('*'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionAdd($status = null)
	{
		/* edit code */
		$zonId			 = Yii::app()->request->getParam('zon_id');
		$model			 = Zones::model()->findByPk($zonId);
		$this->pageTitle = "Add Zone";
		//$vendorMedianRate				 = Zones::model()->getVendorRateByZone($zonId);
		//$model->zon_home_median_sedan	 = $vendorMedianRate['home_median_sedan'];
		//$model->zon_home_median_compact	 = $vendorMedianRate['home_median_compact'];
		//$model->zon_home_median_suv		 = $vendorMedianRate['home_median_suv'];
		$oldCityData	 = array();
		$newCityDataList = array();
		$logArray		 = [];
		$errors			 = '';
		$cityByZone		 = "";
		/* edit code end */
		if ($zonId != "")
		{
			$this->pageTitle = "Modify Zone";
			$cityList		 = ZoneCities::model()->getCityListByZoneId($zonId);
			$cityName		 = ZoneCities::model()->getCityBasedOnZoneId($zonId);
//                    $model->vnd_city = $cityName['cities']; 
			$oldCityData	 = explode(',', $cityList[0]['cities']);
			foreach ($cityList as $value)
			{
				$model->vnd_city = $value['cities'];
			}

			$cityByZone = $cityName['cities'];

			$oldData			 = $model->attributes;
			$oldData['zon_city'] = $cityByZone;
			$remark				 = $model->zon_log;
			$user				 = Yii::app()->user->getId();
		}
		else
		{
			$model = new Zones();
		}

		if (isset($_REQUEST['Zones']))
		{
			$model->attributes					 = Yii::app()->request->getParam('Zones');
			$newData							 = $model->attributes;
			$newData['zon_excluded_cabtypes']	 = implode(",", $newData['zon_excluded_cabtypes']);
			$model->scenario					 = 'zoneCityAdd';

			if (strpos(strtoupper($model->zon_name), 'Z-') !== false)
			{
				$model->zon_name = trim(strtoupper($model->zon_name));
			}
			else
			{
				$model->zon_name = trim(('Z-' . strtoupper($model->zon_name)));
			}

			$zoneName	 = $model->zon_name;
			$cabTypes	 = Yii::app()->request->getParam('Zones')['zon_excluded_cabtypes'];

			$includeCategories	 = Yii::app()->request->getParam('Zones')['zon_included_cabCategories'];
			$includetires		 = Yii::app()->request->getParam('Zones')['zon_included_cabtires'];
			$includeModels		 = Yii::app()->request->getParam('Zones')['zon_included_cabmodels'];

			if ($cabTypes != '')
			{
				$excluded_cabTypes				 = implode(',', $cabTypes);
				$model->zon_excluded_cabtypes	 = $excluded_cabTypes;
			}
			else
			{
				$model->zon_excluded_cabtypes = $cabTypes;
			}

			$model->zon_included_cabtires		 = !empty($includetires) ? implode(",", $includetires) : $model->zon_included_cabtires;
			$model->zon_included_cabCategories	 = !empty($includeCategories) ? implode(",", $includeCategories) : $model->zon_included_cabCategories;
			$model->zon_included_cabmodels		 = !empty($includeModels) ? implode(",", $includeModels) : $model->zon_included_cabmodels;
			$model->zon_hilly_factor             = Yii::app()->request->getParam('Zones')['zon_hilly_factor'];	
			
			$getDifference	 = array_diff_assoc($oldData, $newData);
			$newCityDataList = $_REQUEST['Zones']['vnd_city'];
			if (count($newCityDataList) > count($oldCityData))
			{
				$diffData = array_diff($newCityDataList, $oldCityData);
			}
			else
			{
				$diffData = array_diff($oldCityData, $newCityDataList);
			}
			if ($model->validate())
			{
				if (count($diffData) > 0)
				{
					$getDifference = array_diff_assoc($oldData, $newData);
				}
				else
				{
					unset($getDifference['zon_city']);
				}

				$remark = $model->zon_log;

				$dt		 = date('Y-m-d H:i:s');
				$user	 = Yii::app()->user->getId();

				if (is_string($remark))
				{
					$newcomm = CJSON::decode($remark);
				}
				else if (is_array($remark))
				{
					$newcomm = $remark;
				}
				if ($newcomm == false)
				{
					$newcomm = array();
				}
				if (count($getDifference) > 0)
				{
					while (count($newcomm) >= 50)
					{
						array_pop($newcomm);
					}
					array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $getDifference));
				}
				if (!$model->isNewRecord)
				{
					$model->zon_log = CJSON::encode($newcomm);
				}
				else
				{
					array_unshift($logArray, array(0 => $user, 1 => $dt));
					$model->zon_log = CJSON::encode($logArray);
				}
				$model->save();

				$arr		 = $_REQUEST['Zones']['vnd_city'];
				$newCityData = $arr;
				$resultDiff	 = array_diff($oldCityData, $newCityData);
				foreach ($resultDiff as $diff)
				{
					$delModelCity = ZoneCities::model()->getByZoneCity($model->zon_id, $diff);
					if ($delModelCity != null)
					{
						$delModelCity->delete();
					}
				}
				foreach ($arr as $value)
				{
					$modelCity1 = ZoneCities::model()->getByZoneCity($model->zon_id, $value);
					if (!$modelCity1)
					{
						$modelCity2				 = new ZoneCities();
						$modelCity2->zct_cty_id	 = $value;
						$modelCity2->zct_zon_id	 = $model->zon_id;
						$modelCity2->save();
					}
				}
				$this->redirect(array('list'));
			}
			else
			{
				$errors = $model->getErrors();
			}
			if ($errors != '')
			{
				$model->zon_name = $zoneName;
			}
		}
		$this->render('add', array('model' => $model));
	}

	public function actionList($qry = [])
	{
		$this->pageTitle = "Zone List";
		$pageSize		 = Yii::app()->params['listPerPage'];

		$model = new Zones();
		if (isset($_REQUEST['Zones']))
		{
			$model->attributes	 = Yii::app()->request->getParam('Zones');
			$searchTxt			 = $_REQUEST['Zones']['search_text'];
		}
		$dataProvider = $model->getZoneCitiesList($searchTxt);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('list', array('model' => $model, 'dataProvider' => $dataProvider, 'qry' => $qry));
	}

	public function actionShowlog()
	{
		$zoneAttr	 = Zones::model()->attributeLabels();
		$zoneid		 = Yii::app()->request->getParam('zoneid');
		$logList	 = Zones::model()->getZoneLog($zoneid);
		$modelList	 = new CArrayDataProvider($logList, array('pagination' => array('pageSize' => 5)));
		$models		 = $modelList->getData();
		$this->renderPartial('showlog', array('lmodel' => $models, 'usersList' => $modelList, 'zoneAttr' => $zoneAttr), false, true);
	}

	public function actionVolumetrend()
	{
		$this->pageTitle = "Volume Trend By Source or Destination Zone";
		$pageSize		 = Yii::app()->params['listPerPage'];
		$request		 = Yii::app()->request;
		$model			 = new Zones();
		if ($request->getParam('Zones'))
		{
			$arr						 = $request->getParam('Zones');
			$zon_id						 = ($arr['zon_id'] != '') ? $arr['zon_id'] : 0;
			$source						 = ($arr['zon_info_source'] != '') ? $arr['zon_info_source'] : 2;
			$model->zon_id				 = $zon_id;
			$model->zon_info_source		 = $source;
			$model->zon_bkg_create_date1 = $arr['zon_bkg_create_date1'];
			$model->zon_bkg_create_date2 = $arr['zon_bkg_create_date2'];
		}
		else
		{
			$model->zon_info_source		 = 2;
			$source						 = $model->zon_info_source;
			$zon_id						 = 0;
			$model->zon_bkg_create_date1 = date("Y-m-d", strtotime("-1 years")) . ' 00:00:00';
			$model->zon_bkg_create_date2 = date("Y-m-d") . ' 23:59:59';
		}
		if (isset($_REQUEST['export_zon_info_source']))
		{
			$zon_id						 = ($request->getParam('export_zon_id'));
			$source						 = ($request->getParam('export_zon_info_source'));
			$model->zon_bkg_create_date1 = ($request->getParam('export_zon_bkg_create_date1'));
			$model->zon_bkg_create_date2 = ($request->getParam('export_zon_bkg_create_date2'));
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"VolumeTrend_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename					 = "reportVolumeTrend" . date('YmdHi') . ".csv";
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
			$type	 = 'command';
			$rows	 = $model->getVolumeTrendByZone($zon_id, $source, $type);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Zone Name', 'Completed Count', 'Cancelled Count', 'GMV', 'In month']);
			if (count($rows) > 0)
			{
				foreach ($rows as $row)
				{
					$rowArray					 = array();
					$rowArray['zon_name']		 = $row['zon_name'];
					$rowArray['count_completed'] = $row['count_completed'];
					$rowArray['count_cancelled'] = $row['count_cancelled'];
					$rowArray['gmv_amount']		 = number_format($row['gmv_amount'], 2);
					$rowArray['show_date']		 = $row['show_date'];
					$row1						 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
			}
			fclose($handle);
			if (!$rows)
			{
				die('Could not take data backup: ' . mysql_error());
			}
			exit;
		}
		$dataProvider = $model->getVolumeTrendByZone($zon_id, $source);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('volumetrend', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'qry'			 => $qry)
		);
	}

	public function actionGetvendorcityzone()
	{
		$success		 = false;
		$params			 = $_GET;
		$ctyid			 = Yii::app()->request->getParam('cityId');
		$acceptedZones	 = [];
		$homeZone		 = [];
		$acceptedZones	 = Zones::model()->getNearestZonesbyCity($ctyid);
		$homeZone		 = Zones::model()->getNearestZonebyCity($ctyid);
		$hmzn			 = $homeZone['zon_id'];

		$arrCities = array();
		foreach ($acceptedZones as $record)
		{
			$arrCities[] = $record['zoneIds'];
		}

		if (count($homeZone) > 0)
		{
			$success = true;
		}
		$data = $params + ['homezone' => $hmzn, 'acczone' => $arrCities];
		echo CJSON::encode(['success' => $success, 'data' => $data]);
	}

	public function actionGetcityzone()
	{
		$success	 = false;
		$params		 = $_GET;
		$ctylat		 = Yii::app()->request->getParam('cityLatitude');
		$ctylong	 = Yii::app()->request->getParam('cityLongitude');
		$ctyZone	 = [];
		$ctyZone	 = Zones::model()->getNearestZonesbyCityLatLong($ctylat, $ctylong);
		$arrCities	 = array();
		foreach ($ctyZone as $record)
		{
			$arrCities[] = $record['zoneIds'];
		}

		if (count($ctyZone) > 0)
		{
			$success = true;
		}
		$data = $params + ['ctyzone' => $arrCities];
		echo CJSON::encode(['success' => $success, 'data' => $data]);
		Yii::app()->end();
	}

	public function actionGetZoneList()
	{
		$areaArr = Zones::model()->getJSON();
		echo $areaArr;
		Yii::app()->end();
	}

	/*
	 * This function is used to display all the related Home Service Zone with respect to perticular zoneid
	 * @param int zon_id
	 * @return view
	 */

	public function actionmanageServiceZone()
	{
		$model				 = new HomeServiceZones();
		$zoneid				 = Yii::app()->request->getParam('zon_id');
		$model->hsz_home_id	 = $zoneid;
		if (isset($_REQUEST['HomeServiceZones']))
		{
			$model->attributes	 = Yii::app()->request->getParam('HomeServiceZones');
			$status				 = $model->saveHomeServiceZone();
			Yii::app()->user->setFlash('success', 'Home Service Zone added Successfully');
			$this->refresh(); 
		}
		$serviceZoneProvider = HomeServiceZones::model()->getHomeServiceZoneById($zoneid);
		$serviceZoneProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$serviceZoneProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('servicezonelist', array('model' => $model, 'dataProvider' => $serviceZoneProvider));
	}

	/*
	 * this function is used to remove any service Zone from the grid
	 * @param int hsz_id
	 * 
	 */

	public function actionremoveServiceZone()
	{
		$hszId	 = Yii::app()->request->getParam('hsz_id');
		$status	 = HomeServiceZones::removeServiceZone($hszId);
		Yii::app()->user->setFlash('success', 'Home Service Zone Removed Successfully');
	}

}
