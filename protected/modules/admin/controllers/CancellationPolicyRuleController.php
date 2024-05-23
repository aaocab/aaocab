<?php

class CancellationPolicyRuleController extends Controller
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
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('add', 'list', 'changestatus','getcancelationcharge'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index',),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array('admin'),
				'users'		 => array('admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionList($qry = [])
	{
		$this->pageTitle = "Cancellation Policy Rule ";
		$model			 = new CancellationPolicyRule();
		$request		 = Yii::app()->request;
		if ($request->getParam('CancellationPolicyRule'))
		{
			$model->attributes			 = $request->getParam('CancellationPolicyRule');
			$model->cpr_service_tier	 = $request->getParam('cpr_service_tier') != "" ? implode($request->getParam('cpr_service_tier'), ',') : '';
			$model->cpr_mark_initiator	 = $request->getParam('cpr_mark_initiator') != "" ? implode($request->getParam('cpr_mark_initiator'), ',') : '';
			$model->local_cpr_charge	 = $request->getParam('CancellationPolicyRule')['local_cpr_charge'] != null ? $request->getParam('CancellationPolicyRule')['local_cpr_charge'] : "";
			$model->local_cpr_hours		 = $request->getParam('CancellationPolicyRule')['local_cpr_hours'] != null ? $request->getParam('CancellationPolicyRule')['local_cpr_hours'] : "";
		}

		$dataProvider = $model->getList();
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);

		$outputJs	 = $request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('list', array('model' => $model, 'dataProvider' => $dataProvider), null, $outputJs);
	}

	public function actionAdd()
	{
		$this->pageTitle = "Add Cancellation Policy ";
		$model			 = new CancellationPolicyRule('insert');
		$returnSet		 = new ReturnSet();
		$request		 = Yii::app()->request;
		$cpr_id			 = $request->getParam("id");
		if ($cpr_id > 0)
		{
			$this->pageTitle = "Edit Auto Cancel Rule";
			$model			 = CancellationPolicyRule::model()->findByPk($cpr_id);
		}
		$errors = [];
		if ($request->getParam('CancellationPolicyRule'))
		{
			$model->attributes			 = $request->getParam('CancellationPolicyRule');
			$model->cpr_service_tier	 = $request->getParam('cpr_service_tier') != null ? implode($request->getParam('cpr_service_tier'), ',') : '';
			$model->cpr_mark_initiator	 = $request->getParam('cpr_mark_initiator') != null ? implode($request->getParam('cpr_mark_initiator'), ',') : '';
			$model->cpr_is_working_hour	 = $request->getParam('cpr_is_working_hour') != null ? $request->getParam('cpr_mark_initiator') : 0;
			$result						 = CActiveForm::validate($model);
			if ($result == '[]')
			{
				$returnSet = $model->add();
				$this->redirect(array('CancellationPolicyRule/List'));
			}
			else
			{
				foreach (CJSON::decode($result) as $value)
				{
					array_push($errors, $value);
				}
				$returnSet->setStatus(false);
			}
		}

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('form', array('model' => $model, 'returns' => $returnSet), false, $outputJs);
	}

	public function actionChangeStatus()
	{
		$cpr_id = Yii::app()->request->getParam('id');
		if ($cpr_id > 0)
		{
			$model				 = CancellationPolicyRule::model()->findByPk($cpr_id); //Returns the data to the view
			$model->acr_status	 = $model->cpr_status == 1 ? 0 : 1;
			$success			 = $model->save();
			$data				 = ['success' => $success];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	public function actionGetCancelationCharge()
	{
		$pickupdate			 = "2020-08-07 22:00:00";
		$service_tier		 = 1;
		$initator			 = 1;
		$cancellationCharge	 = CancellationPolicyRule::model()->getCancelationCharge($pickupdate, $service_tier, $initator);
		echo $cancellationCharge;
	}

}
