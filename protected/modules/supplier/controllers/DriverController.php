<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class DriverController extends BaseController
{

	public $layout = 'main';
	public $email_receipient, $pageTitle1, $pageDesc;

	public function filters()
	{
		return array(
//            array(
//                'application.filters.HttpsFilter + create',
//                'bypass' => false),
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
			array(
				'RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'
			),
		);
	}

	public function actions()
	{
		return array(
			'REST.' => 'RestfullYii.actions.ERestActionProvider',
		);
	}

	public function accessRules()
	{
		return array(
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('list','edit','detach','driverphone'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('test',
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array(),
				'users'		 => array('admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
				'deniedCallback' => function() { Yii::app()->controller->redirect(array ('/supplier/user/signin')); }

			),
		);
	}

	public function actionList()
	{
		$model = new Drivers('abcdjd');
		$model->drv_approved = null;
		$vndId = Yii::app()->user->getEntityID();
		if (!$vndId)
		{
			throw new Exception("You are not a vendor.", ReturnSet::ERROR_UNAUTHORISED);
		}
		if (isset($_REQUEST['Drivers']))
		{
			$arrSearch = Yii::app()->request->getParam('Drivers');
			$model->drv_name2 = $arrSearch['drv_name2'];
			$model->drv_phone2 = $arrSearch['drv_phone2'];
			$model->drv_email2 = $arrSearch['drv_email2'];
			$model->drv_approved = $arrSearch['drv_approved'];
		}
		$dataProvider	 = Drivers::getAllLstByVendor($vndId, '', true,false,$model);
		$dataProvider->getPagination()->params = array_filter($_GET + $_POST);
        $dataProvider->getSort()->params       = array_filter($_GET + $_POST);

		$this->render('list',['model'=>$model,'dataProvider'=>$dataProvider]);
	}

	public function actionEdit()
	{
		$this->render('edit',['model'=>$model,'dataProvider'=>$dataProvider]);
	}

	public function actionDetach()
	{
		$this->render('detach',['model'=>$model,'dataProvider'=>$dataProvider]);
	}

	public function actionDriverphone()
	{
		$drvId = Yii::app()->request->getParam('id');
		$model = Drivers::model()->findById($drvId);
		$phone = '';
		if($model->drvContact->ctt_id>0)
		{
			$phone = ContactPhone::model()->getContactPhoneById($model->drvContact->ctt_id);
		}
		echo json_encode(['success'=>true,'phone'=>$phone]);
		Yii::app()->end();
	}
}
