<?php

class PriceruleController extends Controller
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
			['allow', 'actions' => ['add', 'edit', 'create'], 'roles' => ['priceruleAdd']],
			//['allow', 'actions' => ['add'], 'roles' => ['priceruleEdit']],
			['allow', 'actions' => ['list'], 'roles' => ['rateList']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('del', 'destination', 'form',
					'checkpricerulestatus', 'updatedesc', 'view'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index', 'json', 'areapricerule', 'areapricelist', 'getnames', 'filterdrop', 'showlog'),
				'users'		 => array('*'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	/*
	 * @deprecated Added new actionAdd()
	 * @author Ramala 2019-12-13
	 */

	public function actionAddOld1($status = null)
	{
		$pagetitle	 = "Modify Price Rule";
		$ftype		 = 'Modify';
		$prrid		 = Yii::app()->request->getParam('prrid');
		$model		 = PriceRule::model()->findByPk($prrid);
		if ($model == "")
		{
			$model		 = new PriceRule();
			$pagetitle	 = "Add Price Rule";
			$ftype		 = 'Add';
		}

		$this->pageTitle = $pagetitle;

		if (isset($_REQUEST['PriceRule']))
		{

			$arr				 = Yii::app()->request->getParam('PriceRule');
			$model->attributes	 = $arr;
			//  $model->prr_night_start_time	 = $arr['prr_night_start_time'];
			// $model->prr_night_end_time	 = $arr['prr_night_end_time'];

			if ($arr['prr_cab_desc'] == "")
			{
				$desc = [];

				$desc[] = 'Base Amount:' . $model->prr_min_base_amount;
				if (($model->prr_rate_per_km > 0))
				{
					$desc[] = 'Rate:' . $model->prr_rate_per_km . '/KM';
				}
				if (($model->prr_rate_per_km_extra > 0))
				{
					$desc[] = 'Extra Rate:' . $model->prr_rate_per_km_extra . '/KM';
				}
				if (($model->prr_min_km > 0))
				{
					$desc[] = 'Minimum:' . $model->prr_min_km . 'KM/Day';
				}
				if (($model->prr_max_km_day > 0))
				{
					$desc[] = 'Max:' . $model->prr_max_km_day . 'KM/Day';
				}
				if (($model->prr_day_driver_allowance > 0))
				{
					$desc[] = 'Day Driver Allowance:' . $model->prr_day_driver_allowance;
				}
				if (($model->prr_night_driver_allowance > 0))
				{
					$desc[] = 'Night Driver Allowance:' . $model->prr_night_driver_allowance;
				}
				if (($model->prr_driver_allowance_km_limit > 0))
				{
					$desc[] = 'Driver Allowance KM Limit:' . $model->prr_driver_allowance_km_limit;
				}
				if (($model->prr_night_start_time > 0))
				{
					$desc[] = 'Night Start Time:' . date('gA', strtotime($model->prr_night_start_time));
				}
				if (($model->prr_night_end_time > 0))
				{
					$desc[] = 'Night End Time:' . date('gA', strtotime($model->prr_night_end_time));
				}
				if (($model->prr_calculation_type > 0))
				{
					$desc[] = 'Calculation Type:' . $calculationType[$model->prr_calculation_type];
				}
				if (($model->prr_min_pickup_duration > 0))
				{
					$desc[] = 'Min Pickup Duration:' . $model->prr_min_pickup_duration . 'min';
				}
				$model->prr_cab_desc = implode(' | ', $desc);
			}

			$model->prr_night_start_time = DateTime::createFromFormat('h:i A', $arr['prr_night_start_time'])->format('H:i:00');
			$model->prr_night_end_time	 = DateTime::createFromFormat('h:i A', $arr['prr_night_end_time'])->format('H:i:00');
			// $newData = $model->attributes;
			$result						 = CActiveForm::validate($model, null, false);
			if ($result == '[]')
			{
				$model->save();
				$this->redirect(array('list'));
			}
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('add', array('model' => $model, 'isNew' => $ftype), false, $outputJs);
	}

	/*
	 * @deprecated Added new actionAdd()
	 * @author Ramala 2019-12-13
	 */

	public function actionAddOld()
	{
		$pagetitle	 = "Modify Price Rule";
		$ftype		 = 'Modify';
		$prrid		 = Yii::app()->request->getParam('prrid');
		$model		 = PriceRule::model()->findByPk($prrid);
		$oldData	 = false;
		$status		 = "";

		if ($model == "")
		{
			$model		 = new PriceRule();
			$pagetitle	 = "Add Price Rule";
			$ftype		 = 'Add';
		}
		else
		{
			$oldData = $model->attributes;
		}
		$this->pageTitle = $pagetitle;

		if (isset($_REQUEST['PriceRule']))
		{
			$arr				 = Yii::app()->request->getParam('PriceRule');
			$model->attributes	 = $arr;

			$result = CActiveForm::validate($model, null, false);

			if ($result == '[]')
			{
				$model->prr_active			 = 1;
				$model->prr_created_date	 = new CDbExpression('NOW()');
				$model->prr_night_start_time = DateTime::createFromFormat('h:i A', $arr['prr_night_start_time'])->format('H:i:s');
				$model->prr_night_end_time	 = DateTime::createFromFormat('h:i A', $arr['prr_night_end_time'])->format('H:i:s');

				if ($arr['prr_cab_desc'] == "" || $prrid != "")
				{
					$descCabModel		 = SvcClassVhcCat::model()->getVctSvcList($returnType			 = "string", 0, 0, $arr['prr_cab_type']);
					$descTripType		 = Filter::bookingTypePrefixes($distinct			 = true, $arr['prr_trip_type']);
					$model->prr_cab_desc = ('Cab - ' . $descCabModel . ' | Trip Type - ' . $descTripType);
				}
				else
				{
					$model->prr_cab_desc = $arr['prr_cab_desc'];
				}

				$newData		 = $model->attributes;
				$model->prr_log	 = $model->addLog($oldData, $newData);

				$model->save();

				if ($prrid != "")
				{
					$status = "updated successfully";
				}
				else
				{
					$status = "added successfully";
				}
			}

			$this->redirect(array('list'));
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('add', array('model' => $model, 'isNew' => $ftype, 'status' => $status), false, $outputJs);
	}

	public function actionList($qry = [])
	{
		$this->pageTitle = "Price Rule List";
		$pageSize		 = Yii::app()->params['listPerPage'];

		$model = new PriceRule('search');
		if (isset($_REQUEST['PriceRule']))
		{
			$model->attributes = Yii::app()->request->getParam('PriceRule');
		}
		$dataProvider = $model->getList();
		//$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		//$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('list', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'qry'			 => $qry));
	}

	public function actionAreapricerule()
	{

		$ftype		 = 'Modify';
		$aprid		 = Yii::app()->request->getParam('aprid');
		$aprtype	 = Yii::app()->request->getParam('aprtype', 0);
		$aprtypeid	 = Yii::app()->request->getParam('aprtypeid', 0);
		$model		 = AreaPriceRule::model()->findByPk($aprid);

		if (!$model)
		{
			$model					 = new AreaPriceRule();
			$ftype					 = 'Add';
			$model->apr_area_type	 = $aprtype;
			$model->apr_area_id		 = $aprtypeid;
		}

		$desc = '';
		switch ($model->apr_area_type)
		{
			case 1:
				$amodel	 = Zones::model()->findByPk($model->apr_area_id);
				$desc	 = 'Zone : ' . $amodel->zon_name;
				break;
			case 2:
				$amodel	 = States::model()->findByPk($model->apr_area_id);
				$desc	 = 'State : ' . $amodel->stt_name;
				break;
			case 3:
				$amodel	 = Cities::model()->findByPk($model->apr_area_id);
				$desc	 = 'City : ' . $amodel->cty_name;
				break;
		}
		if (isset($_REQUEST['AreaPriceRule']))
		{
			$arr				 = Yii::app()->request->getParam('AreaPriceRule');
			$model->attributes	 = $arr;
			$result				 = CActiveForm::validate($model, null, false);
			if ($result == '[]')
			{
				$model->save();
				$this->redirect(array('areapricelist'));
			}
		}
		$error = '';
		if ($model->apr_area_type == 0 || $model->apr_area_id == 0)
		{
			$error = 'First select Zone, State or City from their lists.';
		}

		$this->pageTitle = $ftype . " Area Price Rule";

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('areaprice', array('model' => $model, 'isNew' => $ftype, 'desc' => $desc, 'error' => $error), false, $outputJs);
	}

	public function actionFilterdrop()
	{
		$cab	 = Yii::app()->request->getParam('cab');
		$ttype	 = Yii::app()->request->getParam('ttype', 0);
		$data	 = PriceRule::model()->getDefaultJSON($cab, $ttype);
		echo $data;
		Yii::app()->end();
	}

	public function actionAreapricelist($qry = [])
	{
		$this->pageTitle = "Area Price List";


		$model = new AreaPriceRule('search');
		if (isset($_REQUEST['AreaPriceRule']))
		{
			$model->attributes = Yii::app()->request->getParam('AreaPriceRule');
		}
		$dataProvider = $model->getList();

		$this->render('areapricelist', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'qry'			 => $qry));
	}

	public function actionUpdatedesc()
	{
		$prrid = Yii::app()->request->getParam('prrid');
		PriceRule::model()->updateDescData($prrid);
	}

	public function actionAdd()
	{
		$model = new AreaPriceRule();
		if (isset($_REQUEST['findBtn']))
		{
			$model->apr_area_id		 = Yii::app()->request->getParam('areaId');
			$model->apr_area_type	 = Yii::app()->request->getParam('areaType');
			$model->apr_cab_type	 = Yii::app()->request->getParam('cabType');

			$count = AreaPriceRule::model()->checkDuplicate($model->apr_area_id, $model->apr_area_type, $model->apr_cab_type);

			if ($count > 0)
			{
				$success = false;
				$isNew	 = false;
			}
			else
			{
				$success = true;
				$isNew	 = true;
			}

			$result = ['success' => $success, 'isNew' => $isNew];
			echo json_encode($result);
			Yii::app()->end();
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('areapriceadd', array('model' => $model, 'success' => $success), false, $outputJs);
	}

	public function actionEdit()
	{
		$this->pageTitle = "Modify Price List";

		$area	 = Yii::app()->request->getParam('areaType');
		$areaId	 = Yii::app()->request->getParam('areaId');
		$areaCab = Yii::app()->request->getParam('areaCab');
		
		if ($area > 0 && $areaId > 0 && $areaCab > 0)
		{
			$models = PriceRule::getRulesByAreaAndCab($area, $areaId, $areaCab);
		}
		else
		{
			$error = 'First select Cab Type, Area Type, Zone, State or City from their lists.';
		}

		if (isset($_REQUEST['priceSave']))
		{
			$isType		 = Yii::app()->request->getParam('isType', 0);
			$areaId		 = ($isType == 0)? Yii::app()->request->getParam('areaId'): 0;
			$prrid		 = ($isType == 0)?  Yii::app()->request->getParam('prr_id'): 0;
			$tripType	 = Yii::app()->request->getParam('tripType');
			$apr_area_id = Yii::app()->request->getParam('apr_area_id');
			$cabType	 = Yii::app()->request->getParam('cabType');
			$areaType	 = Yii::app()->request->getParam('areaType');
			$userInfo	 = UserInfo::getInstance();

			if ($prrid > 0)
			{
				$model	 = PriceRule::model()->findByPk($prrid);
				$oldData = $model->attributes;
			}
			else
			{
				$model					 = new PriceRule();
				$model->prr_trip_type	 = $tripType;
				$model->prr_cab_type	 = $cabType;
				$isNew					 = true;
			}

			$model->prr_rate_per_km					 = Yii::app()->request->getParam('ratePerKm');
			$model->prr_rate_per_minute				 = Yii::app()->request->getParam('ratePerMintute');
			$model->prr_rate_per_km_extra			 = Yii::app()->request->getParam('ratePerKmExtra');
			$model->prr_rate_per_minute_extra		 = Yii::app()->request->getParam('ratePerMinExtra');
			$model->prr_min_km						 = Yii::app()->request->getParam('minKilometer');
			$model->prr_min_duration				 = Yii::app()->request->getParam('minDuration');
			$model->prr_min_base_amount				 = Yii::app()->request->getParam('minBaseAmount');
			$model->prr_min_km_day					 = Yii::app()->request->getParam('minKmDay');
			$model->prr_max_km_day					 = Yii::app()->request->getParam('maxKmDay');
			$model->prr_day_driver_allowance		 = Yii::app()->request->getParam('dayDriverAllowance');
			$model->prr_night_driver_allowance		 = Yii::app()->request->getParam('nightDriverAllowance');
			$model->prr_driver_allowance_km_limit	 = Yii::app()->request->getParam('nightDriverAllowanceKmLimit');
			$model->prr_min_pickup_duration			 = Yii::app()->request->getParam('minPickDuration');
			$model->prr_calculation_type			 = Yii::app()->request->getParam('calculationType');
			$model->prr_night_start_time			 = Yii::app()->request->getParam('nightStartTime');
			$model->prr_night_end_time				 = Yii::app()->request->getParam('nightEndTime');

			if ($model->prr_cab_desc == "")
			{
				$descCabModel		 = SvcClassVhcCat::model()->getVctSvcList($returnType			 = "string", 0, 0, $model->prr_cab_type);
				$descTripType		 = Filter::bookingTypePrefixes(true, $model->prr_trip_type);
				$model->prr_cab_desc = ('Cab - ' . $descCabModel . ' | Trip Type - ' . $descTripType);
			}

			$result		 = CActiveForm::validate($model, null, false);
			$ruleSuccess = $model->validatePriceRule();

			if ($result == '[]' && $ruleSuccess == true)
			{
				$model->prr_active		 = 1;
				$model->prr_created_date = new CDbExpression('NOW()');

				$model->prr_night_start_time = DateTime::createFromFormat('h:i A', $model->prr_night_start_time)->format('H:i:s');
				$model->prr_night_end_time	 = DateTime::createFromFormat('h:i A', $model->prr_night_end_time)->format('H:i:s');
				$newData					 = $model->attributes;
				if ($isNew && $isType == 0)
				{
					$modelSimilar = $model->checkSimilar();
					if ($modelSimilar != '')
					{
						unset($model->prr_created_date);
						$model = $modelSimilar;
					}
				}
				if ($model->save())
				{
					$prrid = $model->prr_id;
					if ($isNew)
					{
						$success = AreaPriceRule::addAreaPriceRule($tripType, $areaId, $prrid, $areaType, $apr_area_id, $cabType);
					}
				}
				$params['prl_ref_id'] = PriceRuleLog::REF_MATCH_FOUND;
				PriceRuleLog::addLog($oldData, $newData, $model->prr_id, $userInfo, $params);

				if ($prrid != "")
				{
					$status	 = "updated successfully";
					$success = true;
				}
				else
				{
					$status	 = "added successfully";
					$success = true;
				}
			}
			if ($ruleSuccess == false)
			{
				$message = "Please fill all the fields with (*) mark.";
			}

			$result = ['model' => $model, 'prrid' => $model->prr_id, 'success' => $success, 'message' => $message, 'error' => $error];
			echo json_encode($result);
			Yii::app()->end();
		}
		
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('areapriceedit', array('models' => $models, 'success' => $success), false, $outputJs);

	}

	public function actionView()
	{
		$success = false;
		$message = "";
		$prrid	 = Yii::app()->request->getParam('prrid');
		$model	 = PriceRule::model()->findByPk($prrid);
		if (!$model)
		{
			$message = 'Invalid Id.';
		}
		else
		{
			$success = true;
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->renderPartial('view', array('model' => $model, 'success' => $success), false, $outputJs);
	}

	public function actionShowlog()
	{
		$prrId			 = Yii::app()->request->getParam('prrid');
		$dataProvider          = PriceRuleLog::getDataById($prrId);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);

		$this->renderPartial('showlog', array('model' => $models, 'dataProvider' => $dataProvider), false, true);
	}

}
