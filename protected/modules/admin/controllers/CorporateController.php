<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CorporateController
 *
 * @author ramala
 */
class CorporateController extends Controller
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
			'postOnly + delete1', // we only allow deletion via POST request
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
			['allow', 'actions' => ['delete'], 'roles' => ['userDelete']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('add', 'list', 'linkedusers', 'corporateexist'),
				'users'		 => array('@'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionAdd()
	{
		$id		 = Yii::app()->request->getParam('id');
		$model	 = new Corporate();
		if ($id != '')
		{
			$model = Corporate::model()->findByPk($id);
		}
		if (isset($_POST['Corporate']))
		{
			$model->attributes	 = $_POST['Corporate'];
			$uploadedFile1		 = CUploadedFile::getInstance($model, "crp_agreement_file");
			$uploadedFile2		 = CUploadedFile::getInstance($model, "crp_id_proof");

			if ($model->validate())
			{
				$model->attributes	 = $_POST['Corporate'];
//                 if($model->isNewRecord && $model->crp_code!=''){
//                    $model->crp_code="CRP".rand(1001, 9999)."".substr(time(), 0, 4);
//                 }
				$model->crp_code	 = strtoupper($model->crp_code);
				if ($model->save())
				{

					$folderName = "corporate";
					if ($uploadedFile1 != '')
					{
						$type						 = "agreement";
						$path						 = Corporate::model()->uploadAttachments($uploadedFile1, $type, $model->crp_id, $folderName);
						$model->crp_agreement_file	 = $path;
					}
					if ($uploadedFile2 != '')
					{
						$type				 = "IdProof";
						$path				 = Corporate::model()->uploadAttachments($uploadedFile2, $type, $model->crp_id, $folderName);
						$model->crp_id_proof = $path;
					}
					if ($model->update())
					{
						$this->redirect(['corporate/list']);
					}
				}
			}
			else
			{
				Yii::app()->user->setFlash('error', 'Error Occured');
			}
		}
		$this->render('add', ['model' => $model]);
	}

	public function actionList()
	{

		$model = new Corporate();
		if (isset($_REQUEST['Corporate']))
		{
			$model->attributes = array_filter($_REQUEST['Corporate']);
		}
		$dataProvider							 = $model->search();
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->render('list', ['dataProvider' => $dataProvider, 'model' => $model]);
	}

	public function actionLinkedusers()
	{
		$corporateId = Yii::app()->request->getParam('id');
		$model		 = new Users;
		if ($_REQUEST['Users'])
		{
			$model->attributes = $_REQUEST['Users'];
		}
		$dataProvider							 = $model->CorporateUsers($corporateId);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->render('userslist', ['dataProvider' => $dataProvider, 'model' => $model]);
	}

	public function actionCorporateExist()
	{
		$corporateCode	 = Yii::app()->request->getParam('crp_code');
		$corporateModel	 = Corporate::model()->find('crp_code=:code', ['code' => $corporateCode]);
		if ($corporateModel != '')
		{
			echo json_encode(['success' => true]);
		}
		else
		{
			echo json_encode(['success' => false]);
		}
		exit;
	}

}
