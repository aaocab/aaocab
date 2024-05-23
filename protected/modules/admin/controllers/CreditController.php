<?php

class CreditController extends Controller
{

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

	public function accessRules()
	{
		return [
			['allow', 'actions' => ['list', 'deactivate'], 'roles' => ['creditHistory']],
			//     ['allow', 'actions' => [], 'users' => ['@']],
			['deny', 'users' => ['*']]];
	}

	public function actionList()
	{
		$this->pageTitle = "Gozo Coins List";
		$model			 = new UserCredits();
		if ($_REQUEST['UserCredits'])
		{
			$model->attributes = $_REQUEST['UserCredits'];
		}
		$dataProvider = $model->resetScope()->search();
		$this->render('list', ['dataProvider' => $dataProvider, 'model' => $model], false);
	}

	public function actionDeactivate()
	{
		$success				 = false;
		$id						 = Yii::app()->request->getParam('id');
		$status					 = Yii::app()->request->getParam('status');
		$creditModel			 = UserCredits::model()->resetScope()->findByPk($id);
		$creditModel->ucr_status = $status;
		if ($creditModel->save())
		{
			$success = true;
		}
		echo json_encode(['success' => $success]);
		Yii::app()->end();
	}

}
