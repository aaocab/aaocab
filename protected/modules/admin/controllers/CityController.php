<?php

class CityController extends Controller
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
			['allow', 'actions' => ['add', 'create'], 'roles' => ['cityAdd']],
			['allow', 'actions' => ['add', 'showlog'], 'roles' => ['']],
			['allow', 'actions' => ['list'], 'roles' => ['cityList']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('cityfromstate', 'del', 'destination',
					'checkvehiclestatus', 'adddestination', 'linkapproval', 'placeapproval', 'json', 'checkcityname', 'updateLatLongByAddress', 'updateRouteDistTime', 'ajaxadd', 'cityname','getCityList','checkDuplicateCity', 'getDetailsByAddress', 'searchRouteByAddress'),
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
	
	public function actionAdd()
	{
		$this->pageTitle = "Add City";
		$ctyid			 = Yii::app()->request->getParam('ctyid');
		$model			 = new Cities();

		if ($ctyid > 0)
		{
			$this->pageTitle = "Edit City";
			$model			 = Cities::model()->findByPk($ctyid);
			$model->cty_zones     = ZoneCities::model()->findZoneByCity($ctyid);
		}

		if (isset($_POST['Cities']))
		{
			$postData						 = $_POST['Cities'];
			$postData['placeId']			 = Yii::app()->request->getParam('cty_place_id');
			$postData['lat']				 = Yii::app()->request->getParam('lat');
			$postData['long']				 = Yii::app()->request->getParam('long');
			$postData['ctyid']				 = Yii::app()->request->getParam('ctyid');
			$postData['cty_keyword_names']	 = Yii::app()->request->getParam('cty_keyword_names');
			$postData['cty_poi_type']	 = $_POST['Cities']['cty_poi_type'];
			try
			{
		        $model = Cities::add($postData);
				if ($model->getErrors() == [])
				{
					$model->cty_zones     = ZoneCities::model()->findZoneByCity($model->cty_id);
					Yii::app()->user->setFlash('success', "Data Saved Successfully !!!");
				}
			}
			catch (Exception $ex)
			{
				echo "Some Error Ocuured (" . $ex->getMessage() . ")";
				Yii::app()->end();
			}
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('add', array('model' => $model, 'isNew' => $ftype), false, $outputJs);
	}

	public function actionShowlog()
	{
		$ctyid		 = Yii::app()->request->getParam('ctyid');
		$logList	 = Cities::model()->getCityLog($ctyid);
		$modelList	 = new CArrayDataProvider($logList, array('pagination' => array('pageSize' => 10),));
		$models		 = $modelList->getData();
		$this->renderPartial('showlog', array('lmodel' => $models, 'usersList' => $modelList), false, true);
	}

	public function actionList($qry = [])
	{
		$this->pageTitle = "City List";
		$pageSize		 = Yii::app()->params['listPerPage'];

		$model = new Cities('search');
		if (isset($_REQUEST['Cities']))
		{
			$model->attributes = Yii::app()->request->getParam('Cities');
		}
		$dataProvider = $model->getList();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('list', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'qry'			 => $qry));
	}

	public function actionCreate()
	{
		$model = new Cities('insert');

		if (isset($_REQUEST['Cities']))
		{
			$result				 = CActiveForm::validate($model);
			$arr				 = Yii::app()->request->getParam('Cities');
			$model->attributes	 = $arr;
			if ($result == '[]')
			{
				$model1 = Cities::model()->getbyNamenStateid($arr['cty_name'], $arr['cty_state_id']);
				if ($model1)
				{
					echo 'false';
				}
				else
				{
					$model->save();
				}
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				echo $result;
				Yii::app()->end();
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('create', array('model' => $model), false, $outputJs);
	}

	public function actionCheckcityname()
	{
		$state	 = Yii::app()->request->getParam('state');
		$city	 = Yii::app()->request->getParam('city');
		$id      = Yii::app()->request->getParam('id') | 0;
		$model	 = Cities::model()->getbyNamenStateid($city, $state);
		if ($model != '' && count($model) > 0)
		{
		   if($id > 0 && $model->cty_id == $id)
		   {
			  echo json_encode(['success' => false]);
			  Yii::app()->end();
		   }
		   echo json_encode(['success' => true]);
		}
		else
		{
			echo json_encode(['success' => false]);
		}
		Yii::app()->end();
	}

	public function actionCheckDuplicateCity()
	{
		$city		 = Yii::app()->request->getParam('city');
		$state		 = Yii::app()->request->getParam('state');
		$statecode	 = Yii::app()->request->getParam('statecode');
		$latitude	 = Yii::app()->request->getParam('cLat');
		$longitude	 = Yii::app()->request->getParam('cLong');
		$placeId	 = Yii::app()->request->getParam('placeid');
		$types		 = Yii::app()->request->getParam('types');
		$success	 = true;
		/* @var $place \Stub\common\Place */
		$placeObj	 = \Stub\common\Place::initGoogleRoute($latitude, $longitude, $placeid, '', $city);
		if (in_array('airport', $types))
		{
			$model = Cities::getNearestAirport($placeObj);
			goto check;
		}
		$model = Cities::findByPlaceId($placeObj);
		if ($model)
		{
			$success = false;
			goto result;
		}
		$model = Cities::getByName($city, $state, $statecode, $placeObj->coordinates);
		check:
		if ($model)
		{
			$success = false;
		}

		result:
		echo json_encode(['success' => $success]);
	}

	public function actionUpdateLatLongByAddress()
	{
		$success			 = false;
		$params				 = $_GET;
		$cityGarageAddress	 = Yii::app()->request->getParam('cityGarageAddress');
		$model				 = GoogleMapAPI::getInstance()->getLatLong($cityGarageAddress);
		if (count($model) > 0)
		{
			$success = true;
		}
		$data = $params + ['model' => $model];
		echo CJSON::encode(['success' => $success, 'data' => $data]);
		Yii::app()->end();
	}

	public function actionUpdateRouteDistTime()
	{
		$success	 = false;
		$params		 = $_GET;
		$fromCity	 = Yii::app()->request->getParam('fromCity');
		$toCity		 = Yii::app()->request->getParam('toCity');
		$model		 = Cities::model()->getDistTimeByFromCityAndToCity($fromCity, $toCity);
		if (count($model) > 0)
		{
			$success = true;
		}
		$data = $model;
		echo CJSON::encode(['success' => $success, 'data' => $data]);
	}

	public function actionJson()
	{
		$driverJson = Cities::model()->getJSON();
		echo $driverJson;
		Yii::app()->end();
	}

	public function actionAjaxadd()
	{
		$state	 = Yii::app()->request->getParam('state');
		$city	 = Yii::app()->request->getParam('city');
		$model	 = new Cities('insert');

		if ($state != '' && $city != '')
		{
			$model->cty_name	 = $city;
			$model->cty_state_id = $state;

			if ($model->validate())
			{
				$model1 = Cities::model()->getbyNamenStateid($city, $state);
				if ($model1)
				{
					echo 'false';
				}
				else
				{
					$status = $model->save();
					echo $status;
				}
			}
		}
		Yii::app()->end();
	}

	public function actionGetnames()
	{
		$success		 = false;
		$params			 = $_GET;
		$fcity			 = Yii::app()->request->getParam('fromCity');
		$tcity			 = Yii::app()->request->getParam('toCity');
		$fcitydetails	 = Cities::model()->getDetails($fcity);
		$tcitydetails	 = Cities::model()->getDetails($tcity);

		if (count($fcitydetails) > 0)
		{
			$success = true;
		}

		$data = $params + ['fcityname'			 => $fcitydetails->cty_name,
			'tcityname'			 => $tcitydetails->cty_name,
			'fcity_statename'	 => $fcitydetails->ctyState->stt_name,
			'tcity_statename'	 => $tcitydetails->ctyState->stt_name];

		echo CJSON::encode(['success' => $success, 'data' => $data]);
	}

	public function actionSelectcities()
	{
		$success	 = false;
		$params		 = $_GET;
		$rt_id		 = Yii::app()->request->getParam('routeId'); //$_GET['rt_id'];
		$route		 = Route::model()->findByPk($rt_id);
		$fcity		 = $route->rut_from_city_id;
		$tcity		 = $route->rut_to_city_id;
		$fcityname	 = Cities::getName($fcity);
		$tcityname	 = Cities::getName($tcity);
		if (count($route) > 0)
		{
			$success = true;
		}
		$data = $params + ['fcity' => $fcity, 'tcity' => $tcity, 'fcityname' => $fcityname, 'tcityname' => $tcityname];
		echo CJSON::encode(['success' => $success, 'data' => $data]);
	}

	public function actionCityname()
	{
		$id			 = Yii::app()->request->getParam('id');
		$cityname	 = Cities::model()->findByPk($id)->cty_name;
		$cityname	 = str_replace(" ", "", $cityname);
		$cityname	 = strtolower($cityname);
		if ($cityname != '')
		{
			$success = 1;
		}
		else
		{
			$success = 0;
		}
		echo json_encode(['success' => $success, 'cityname' => $cityname]);
	}

	public function actionGetcitydetails()
	{
		$Id		 = Yii::app()->request->getParam('Id');
		$cityBox = Yii::app()->request->getParam('cityBox');
		$data	 = Cities::model()->getCityDetailsById($Id, $cityBox);
		echo json_encode($data);
	}

	public function actionLinkapproval()
	{
		$this->pageTitle = "City Link Approval";
		$link_id		 = Yii::app()->request->getParam('link_id');
		$link_approve	 = Yii::app()->request->getParam('link_approve');
		if ($link_id != '' && $link_approve != '')
		{
			$model				 = CityLinks::model()->findByPk($link_id);
			$model->cln_status	 = $link_approve;
			$model->save();
			$this->redirect(array('linkapproval'));
		}
		$cityLinksModel					 = new CityLinks();
		$cityLinksModel->cln_status		 = 0;
		$cityLinksModel->cln_category	 = 1;
		$dataProvider					 = $cityLinksModel->search();
		$this->render('linkapproval', ['dataProvider' => $dataProvider]);
	}

	public function actionPlaceapproval()
	{
		$this->pageTitle = "City Place Approval";
		$place_id		 = Yii::app()->request->getParam('place_id');
		$place_approve	 = Yii::app()->request->getParam('place_approve');
		if ($place_id != '' && $place_approve != '')
		{
			$model				 = CityPlaces::model()->findByPk($place_id);
			$model->cpl_status	 = $place_approve;
			$model->save();
			$this->redirect(array('placeapproval'));
		}
		$dataProvider = CityPlaces::model()->search();
		$this->render('placeapproval', ['dataProvider' => $dataProvider]);
	}

	public function actionDestination()
	{
		$this->pageTitle = "City Popular Destinations";
		$cityLinksModel	 = new CityLinks();
		if (isset($_REQUEST['CityLinks']))
		{
			$cityLinksModel->attributes = $_REQUEST['CityLinks'];
		}
		$cityLinksModel->cln_category	 = 2;
		$cityLinksModel->cln_status		 = 1;
		$dataProvider					 = $cityLinksModel->search();

		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('citydestination', ['dataProvider' => $dataProvider, 'model' => $cityLinksModel]);
	}

	public function actionAdddestination()
	{
		$this->pageTitle = "City Popular Destinations";
		$cityLinksModel	 = new CityLinks();

		if (isset($_POST['CityLinks']))
		{
			$cityLinksModel->attributes		 = $_POST['CityLinks'];
			$cityLinksModel->cln_user_id	 = Yii::app()->user->getId();
			$cityLinksModel->cln_user_ip	 = \Filter::getUserIP();
			$cityLinksModel->cln_datetime	 = new CDbExpression('NOW()');
			$cityLinksModel->cln_status		 = 1;
			$cityLinksModel->cln_category	 = 2;
			$cityLinksModel->cln_user_type	 = 2;
			$cityLinksModel->save();
			echo json_encode(['success' => true]);
			Yii::app()->end();
			//$this->redirect(array('destination'));
		}

		$this->renderPartial('adddestination', ['model' => $cityLinksModel], false, true);
	}
	
	public function actionGetCityList()
	{
		$areaArr	 = Cities::getAllCityListDrop();
		echo $areaArr;
		Yii::app()->end();
	}

    public function actionGetDetailsByAddress()
    {
        $this->pageTitle = "Google Address";
		$model	 = BookingRoute::model()->find('brt_active = 1');
        $bkgmodel				 = new Booking('admininsert');
		$this->render('getGoogleAddress', ['model' => $model, 'bkgmodel' => $bkgmodel]);
    }

    public function actionSearchRouteByAddress()
    {
        $request = Yii::app()->request;
        $latitude = $request->getParam('latitude', null);
        $longitude = $request->getParam('longitude', null);

        if($latitude != '' && $longitude != '')
        {
            $data = LatLong::addByPlace(\Stub\common\Place::init($latitude, $longitude));
            $cityId = $data[0]->ltg_city_id;
            $cityName = Cities::getName($cityId);
            $cityFullName = Cities::getFullName($cityId);
            $allZone = Zones::getByCityId($cityId);
            $getZone = Zones::getNameByCityId($allZone);
        }
        $outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
        $this->$method('searchRouteAddress', array('cityFullName' => $cityFullName, 'cityName' => $cityName, 'allZone' => $getZone), false, $outputJs);
       }
}
