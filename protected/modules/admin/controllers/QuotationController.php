<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class QuotationController extends Controller
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
			['allow', 'actions' => ['create', 'copybooking'], 'roles' => ['bookingAdd']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('step1', 'step2', 'step3', 'step4', 'ajaxstep1', 'cabrulelist', 'getareaname', 'addcabrule', 'status'),
				'users'		 => array('@'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function loadModel($id)
	{
		$model = Quotation::model()->findByPk($id);
		if ($model === null)
		{
			throw new CHttpException(404, 'The requested page does not exist.');
		}
		return $model;
	}

	public function actionAjaxstep1()
	{
		$pickupCty		 = Yii::app()->request->getParam('pickupCty');
		$dropCty		 = Yii::app()->request->getParam('dropCty');
		$pickupPoint	 = Yii::app()->request->getParam('pickupPoint');
		$dropPoint		 = Yii::app()->request->getParam('dropPoint');
		$date			 = Yii::app()->request->getParam('startDate');
		$qotData		 = json_decode(Yii::app()->request->getParam('qotData'));
		$tripType		 = Yii::app()->request->getParam('tripType');
		/* var $model Quotation */
		$model			 = new Quotation();
		$c1model		 = Cities::model()->findByPk($pickupCty);
		$c2model		 = Cities::model()->findByPk($dropCty);
		$ctr			 = 0;
		$success		 = false;
		$quotationStr	 = '';
		$quotationList	 = array();
		foreach ($qotData as $qot)
		{
			$quotationList[$ctr]['pickup_city']		 = (int) $qot->pickup_city;
			$quotationList[$ctr]['drop_city']		 = (int) $qot->drop_city;
			$quotationList[$ctr]['pickup_point']	 = $qot->pickup_point;
			$quotationList[$ctr]['drop_point']		 = $qot->drop_point;
			$quotationList[$ctr]['pickup_cityname']	 = $qot->pickup_cityname;
			$quotationList[$ctr]['drop_cityname']	 = $qot->drop_cityname;
			$quotationList[$ctr]['date']			 = $qot->date;
			$ctr									 = ($ctr + 1);
		}
		$model->scenario = 'insertRoute';
		if ($pickupCty != '' && $dropCty != '')
		{
			$newQuotation[]	 = ['pickup_city'		 => (int) $pickupCty,
				'drop_city'			 => (int) $dropCty,
				'pickup_point'		 => $pickupPoint,
				'drop_point'		 => $dropPoint,
				'pickup_cityname'	 => $c1model->cty_name,
				'drop_cityname'		 => $c2model->cty_name,
				'date'				 => $date];
			$quotationList	 = array_merge($quotationList, $newQuotation);
			if (count($quotationList) > 0)
			{
				$quotationStr	 .= '<div class = "row table table-bordered">
                <div class = "col-xs-12 col-md-12">
                  <div class = "col-xs-12 col-sm-3" style = "text-align: center;"><b>From City</b></div>
                  <div class = "col-xs-12 col-sm-3" style = "text-align: center;"><b>To City</b></div>
                  <div class = "col-xs-12 col-sm-3" style = "text-align: center;"><b>Date</b></div>
                  <div class = "col-xs-12 col-sm-3" style = "text-align: center;"><b>#Days</b></div>
                </div>';
				$ctr			 = 0;
				$qotCount		 = 1;
				foreach ($quotationList as $qot)
				{
					$qotCount = ($qotCount + 1);
					if ($ctr == '0')
					{
						$fromDate = $qot['date'];
					}
					if (count($quotationList) == ($ctr + 1))
					{
						$toDate = $qot['date'];
					}
					$quotationStr .= '<div class = "col-xs-12 col-md-12">
                  <div class = "col-xs-12 col-sm-3" style = "text-align: center;">' . $qot['pickup_cityname'] . '</div>
                  <div class = "col-xs-12 col-sm-3" style = "text-align: center;">' . $qot['drop_cityname'] . '</div>
                  <div class = "col-xs-12 col-sm-3" style = "text-align: center;">' . $qot['date'] . '</div>
                  <div class = "col-xs-12 col-sm-3" style = "text-align: center;"></div>
                </div>';
					if ($fromDate != '' && $toDate != '')
					{
						$tripDays = Quotation::model()->getTravelDays($fromDate, $toDate);
					}
					$ctr = ($ctr + 1);
				}
				$quotationStr	 .= '<div class = "col-xs-12 col-md-12"><div class ="col-xs-12 col-sm-1" style = "text-align: left;">&nbsp;</div><div class ="col-xs-12 col-sm-11" style = "text-align: left;"><h4>Total #days for the trip : ' . $tripDays . '</h4> </div><div>';
				$quotationStr	 .= "</div>";
			}
			$success = true;
		}
		$data = ['success'		 => $success,
			'quotation'		 => json_encode($quotationList),
			'quotationStr'	 => $quotationStr,
			'quotationCnt'	 => $qotCount];
		echo json_encode($data);
	}

	public function actionStep1()
	{

		$this->pageTitle		 = "Quotation - Step 1";
		/* var $model Quotation */
		$model					 = new Quotation();
		$model->qot_trip_type	 = 1;

		if (isset($_REQUEST['Quotation']))
		{
			$model->qot_name			 = trim($_REQUEST['Quotation']['qot_name']);
			$model->qot_phone			 = trim($_REQUEST['Quotation']['qot_phone']);
			$model->qot_email			 = trim($_REQUEST['Quotation']['qot_email']);
			$model->qot_passenger		 = trim($_REQUEST['Quotation']['qot_passenger']);
			$model->qot_luggage			 = trim($_REQUEST['Quotation']['qot_luggage']);
			$model->qot_trip_type		 = trim($_REQUEST['Quotation']['qot_trip_type']);
			$model->qot_car_type		 = implode(',', $_REQUEST['Quotation']['qot_car_type']);
			$model->qot_special_needs	 = implode(',', $_REQUEST['Quotation']['qot_special_needs']);
			$quotationData				 = array();
			$params						 = array();
			$quotationData				 = (trim($_REQUEST['quotation_data']));

			$params	 = array('name'			 => $model->qot_name,
				'phone'			 => $model->qot_phone,
				'email'			 => $model->qot_email,
				'passenger'		 => $model->qot_passenger,
				'luggage'		 => $model->qot_luggage,
				'specialNeeds'	 => $model->qot_special_needs,
				'carType'		 => $model->qot_car_type,
				'tripType'		 => $model->qot_trip_type);
			$data	 = Quotation::model()->getQuotationList($params, json_decode($quotationData));
			//$quotationData = json_decode($quotationData);
			$this->redirect(array('step2', 'data' => $data, 'params' => $params, 'quotationData' => $quotationData));
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('quotation_step1', array('model' => $model), false, $outputJs);
	}

	public function actionStep2()
	{
		$this->pageTitle = "Quotation - Step 2";
		/* var $model Quotation */
		$model			 = new Quotation();
		$params			 = Yii::app()->request->getParam('params');
		$data			 = Yii::app()->request->getParam('data');

		$quotationData = Yii::app()->request->getParam('quotationData');
		if (isset($_POST['Quotation']))
		{
			$model->scenario = "insert3";
			$result			 = CActiveForm::validate($model);
			$recordSet		 = trim($_POST);
			$arr			 = Yii::app()->request->getParam('Quotation');
			if ($model->validate())
			{
				$email			 = $arr['qot_email_txt'];
				$params			 = array('name'			 => trim($_POST['qot_name']),
					'email'			 => trim($_POST['qot_email']),
					'phone'			 => trim($_POST['qot_phone']),
					'passenger'		 => trim($_POST['qot_passenger']),
					'luggage'		 => trim($_POST['qot_luggage']),
					'tripType'		 => trim($_POST['qot_trip_type']),
					'carType'		 => trim($_POST['qot_car_type']),
					'special_needs'	 => trim($_POST['qot_special_needs']));
				$quotationData	 = json_decode(trim($_POST['quotation_data']));
				Quotation::model()->insertUpdate($params, $quotationData);

				$data = ['success' => true, 'post' => $quotationData];
			}
			else
			{
				$data = ['success' => false, 'errors' => CJSON::decode($result)];
				if ($model->hasErrors())
				{
					$success = false;
					$errors	 = $model->getErrors();
				}
			}
		}

		if (Yii::app()->request->isAjaxRequest)
		{
			echo json_encode($data);
			Yii::app()->end();
		}
		$this->render('quotation_step2', array('model'			 => $model,
			'params'		 => $params,
			'data'			 => $data,
			'quotationData'	 => $quotationData));
	}

	public function actionStep3($param)
	{
		
	}

	public function actionCabrulelist()
	{
		$pagetitle		 = "Rules for cab area wise";
		$this->pageTitle = $pagetitle;
		$model			 = new ServiceClassRule();
		$tripType		 = Filter::bookingTypes();

		$model->scr_area_type					 = Yii::app()->request->getParam('ServiceClassRule')['scr_area_type'];
		$model->scr_scv_id						 = Yii::app()->request->getParam('ServiceClassRule')['scr_scv_id'];
		$model->scr_area_id						 = Yii::app()->request->getParam('ServiceClassRule')['scr_area_id'];
		$dataProvider							 = $model->getList();
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$outputJs								 = Yii::app()->request->isAjaxRequest;
		$method									 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('cabrulelist', array('model' => $model, 'dataProvider' => $dataProvider, 'tripType' => $tripType), null, $outputJs);
	}

	public function actionGetareaname()
	{

		$areaType = Yii::app()->getRequest()->getParam('areaType');
		if ($areaType == 1)
		{
			$areaName = Zones::model()->getDopdownList();
		}
		if ($areaType == 2)
		{
			$areaName = States::model()->getDopdownList();
		}
		if ($areaType == 3)
		{
			$areaName = Cities::model()->getLookup();
		}
		if ($areaType == 4)
		{
			$areaName = States::model()->findRegionName();
		}

		echo CJSON::encode($areaName);
	}

	public function actionAddcabrule()
	{
		$this->pageTitle = "Add Cab Rules";
		$model			 = new ServiceClassRule();
		$isAllow		 = ['No', 'Yes'];
		$id				 = Yii::app()->request->getParam('id');
		if ($id > 0)
		{
			$model = ServiceClassRule::model()->findByPk($id);
		}
		if (!empty($_POST['ServiceClassRule']))
		{
			$model->attributes		 = Yii::app()->request->getParam('ServiceClassRule');
			if($model->isNewRecord)
			{
				$data					 = SvcClassVhcCat::model()->getVctIdSccIdByScvId($model['scr_scv_id']);
				$model->scr_scc_class	 = $data['scv_scc_id'];
				$model->scr_vhc_category = $data['scv_vct_id'];
				$model->scr_vht_id		 = $data['scv_model'];
				$model->scenario = 'create';
			}
			if ($model->save())
			{
				$this->redirect('cabrulelist');
				Yii::app()->user->setFlash('success', "Cab Rules added successfully.");
			}
			else
			{
				Yii::app()->user->setFlash('error', 'Cab Rules update not success<br/>');
				foreach ($model->getErrors() as $attribute => $errors)
				{
					foreach ($errors as $value)
					{
						Yii::app()->user->setFlash('error', 'Cab Rules update not success<br/>' . $value . "<br/>");
					}
				}
			}
		}
		$this->render('addcabrule',
				array('model'		 => $model,
					'isAllow'	 => $isAllow,
				), false, true);
	}

	public function actionStatus()
	{
		$id					 = Yii::app()->getRequest()->getParam('id');
		$model				 = ServiceClassRule::model()->findByPk($id);
		$model->scr_active	 = 1 - $model->scr_active;
		if ($model->save())
		{
			$success = true;
		}
		echo json_encode(['success' => $success]);
		Yii::app()->end();
	}

}
