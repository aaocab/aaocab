<?php

require(dirname(__FILE__) . '/BaseController.php');

class PlaceController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = 'column2';
	public $current_page = '';

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
				'actions'	 => array('create', 'update', 'index', 'view', 'deleteme', 'checkzip', 'getplacedetails'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(''),
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

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($status = null)
	{
		$this->checkV3Theme();
		$this->pageTitle	 = "My Places";
		$this->current_page	 = "my_places";
		$userId				 = Yii::app()->user->getId();

		$pageSize	 = Yii::app()->params['listPerPage'];  // define the variable to �LIMIT� the query
		$Place		 = UserPlaces::model()->getPlacesbyUser($userId);
		$userPlace	 = new CArrayDataProvider($Place, array(
			'pagination' => array('pageSize' => $pageSize),
		));
		$models		 = $userPlace->getData();
		// $userBusiness = UserBusiness::model()->findAll('user_id=:id', array('id' => Yii::app()->user->getId()));
		$this->render('view', array(
			'models'	 => $models,
			'$userPlace' => $userPlace,
			'status'	 => $status
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$this->checkV3Theme();
		$this->current_page	 = "my_places_add";
		$this->pageTitle	 = "Add Place";
		$model				 = new UserPlaces;

		if (isset($_REQUEST['UserPlaces']))
		{
			$arr1				 = Yii::app()->request->getParam('UserPlaces');
			$model->attributes	 = $arr1;

			$model->address1 = $arr1['address1'];
			$model->address2 = $arr1['address2'];
			$model->address3 = $arr1['address3'];
			$model->user_id	 = Yii::app()->user->getId();
			if ($model->validate())
			{

				$model->save();
				$this->redirect(array('place/view'));
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				echo $result;
				Yii::app()->end();
			}
		}
		$this->render('create', array('model' => $model));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->checkV3Theme();
		$this->pageTitle = "Edit Place";
		$this->current_page = "my_places_edit";

		$model	 = UserPlaces::model()->findByPk($id);
		$userid	 = Yii::app()->user->getId();

		if (isset($_REQUEST['UserPlaces']) && $userid == $model->user_id)
		{
			$arr1				 = Yii::app()->request->getParam('UserPlaces');
			$model->attributes	 = $arr1;
			$result				 = CActiveForm::validate($model);
			if ($result == "[]")
			{
				$model->address1 = $arr1['address1'];
				$model->address2 = $arr1['address2'];
				$model->address3 = $arr1['address3'];
				$model->user_id	 = Yii::app()->user->getId();
				$model->save();
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				echo $result;
				Yii::app()->end();
			}
		}
		$this->render('create', array('model' => $model,));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_REQUEST['ajax']))
			$this->redirect(isset($_REQUEST['returnUrl']) ? Yii::app()->request->getParam('returnUrl') : array('admin'));
	}

	public function actionDeleteme($id)
	{
		$deletePlace = UserPlaces::model()->find('user_place_id=:place_id', array('place_id' => $id));
		$deletePlace->delete();
		$this->redirect(array('place/view', 'status' => 'del'));
	}

	public function actionCheckzip()
	{
		$city	 = Yii::app()->request->getParam('cty');
		$zip	 = Yii::app()->request->getParam('zip');

		$data = false;
		if ($city != '')
		{
			$zipModels = ZipCodes::model()->find('zip_code=:zip AND zip_city_id=:city', array('zip' => $zip, 'city' => $city));
			if ($zipModels)
			{
				$data = true;
			}
		}

		echo CJSON::encode($data);
		Yii::app()->end();
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model				 = new UserPlaces('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_REQUEST['UserPlaces']))
			$model->attributes	 = Yii::app()->request->getParam('UserPlaces');

		$this->render('admin', array(
			'model' => $model,
		));
	}

	public function actionGetplacedetails()
	{
		//  JSONUtil::convertModelToArray($model)
		$plcid		 = (Yii::app()->request->getParam('plcid') == "") ? 0 : Yii::app()->request->getParam('plcid');
		$model		 = UserPlaces::model()->findbyPk($plcid);
		//$model = new UserPlaces();
		$addrarr3	 = [];
		$addrarr	 = [];

		if ($model->address3 != '')
		{
			$addrarr3[] = trim($model->address3);
		}


		$addrarr3[] = ($model->zip != '') ? $model->city0->cty_name . '-' . $model->zip : $model->city0->cty_name;

		$addr3 = implode(', ', $addrarr3);

		if (trim($model->address1) != '')
		{
			$addrarr[] = trim($model->address1);
		}
		if (trim($model->address2) != '')
		{
			$addrarr[] = trim($model->address2);
		}
		$addrarr[] = trim($addr3);

		$addrs = implode(', ', $addrarr);



		$arrCities = ['addrs'	 => $addrs, 'addr1'	 => $model->address1,
			'addr2'	 => $model->address2,
			'addr3'	 => $addr3
		];

		echo CJSON::encode($arrCities);
		Yii::app()->end();
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return UserPlaces the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = UserPlaces::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

}
