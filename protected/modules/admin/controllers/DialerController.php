<?php

class DialerController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter',
				'bypass' => false),
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
			array(
				'RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'
			),
		);
	}

//    public function behaviors() {
//        return array(
//            'seo' => array('class' => 'application.components.SeoControllerBehavior'),
//        );
//    }
	public function actions()
	{
		return array(
			'REST.' => 'RestfullYii.actions.ERestActionProvider',
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
				'actions'	 => array('view'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index', 'callstatus', 'calllist', 'audioreport', 'downloadFile',
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS', 'atirudram2017'),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array('admin'),
				'users'		 => array('admin'),
			),
			['allow', 'actions' => [''], 'users' => ['*']],
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function restEvents()
	{
		$this->onRest('req.cors.access.control.allow.methods', function () {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});
	}

	public function actionIndex()
	{
		echo "Index";
		exit;
		$this->redirect('/');
	}

	public function actionCallstatus()
	{
		$cst_id					 = Yii::app()->request->getParam('cst_id');
		$cst_lead_id			 = Yii::app()->request->getParam('cst_lead_id');
		$cst_phone_code			 = Yii::app()->request->getParam('cst_phone_code');
		$cst_phone				 = Yii::app()->request->getParam('cst_phone');
		$cst_did				 = Yii::app()->request->getParam('cst_did');
		$cst_group				 = Yii::app()->request->getParam('cst_group');
		$cst_agent_name			 = Yii::app()->request->getParam('cst_agent_name', '');
		$cst_recording_file_name = Yii::app()->request->getParam('cst_recording', '');
		$cst_camp				 = Yii::app()->request->getParam('cst_campaign');
		$cst_status				 = Yii::app()->request->getParam('cst_status', 2);

		$sql = "
		INSERT INTO `call_status`(`cst_id`, `cst_lead_id`, `cst_phone_code`, `cst_phone`, `cst_did`,
		`cst_agent_name`, `cst_recording_file_name`,
		`cst_group`, `cst_camp`,`cst_status`) 
		VALUES ('$cst_id','$cst_lead_id','$cst_phone_code','$cst_phone','$cst_did',
			'$cst_agent_name','$cst_recording_file_name',
				'$cst_group','$cst_camp',$cst_status) 
		ON DUPLICATE KEY UPDATE cst_status='$cst_status', cst_recording_file_name='$cst_recording_file_name', 
				cst_modified=NOW()
		";
		//	Logger::create($sql, CLogger::LEVEL_ERROR);
		Yii::app()->db->createCommand($sql)->execute();
	}

	public function actionCalllist()
	{

		$this->pageTitle = "Call Status List";

		$model				 = new CallStatus('search');
		$model->cst_status	 = null;
		$arr				 = [];
		if (isset($_REQUEST['CallStatus']))
		{
			$arr				 = Yii::app()->request->getParam('CallStatus');
			$model->attributes	 = array_filter($arr);
		}
		$dataProvider = $model->getList(array_filter($arr));

		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$this->render('calllist', array(
			'model'			 => $model,
			'dataProvider'	 => $dataProvider,
		));
	}

	public function actionAudioreport()
	{
		$this->pageTitle = "Audio List";

		$model				 = new CallStatus('search');
		$model->cst_status	 = 3;
		$model->cst_type	 = 4;
		$arr				 = [];
		if (isset($_REQUEST['CallStatus']))
		{
			$arr				 = Yii::app()->request->getParam('CallStatus');
			$model->attributes	 = array_filter($arr);
		}
		$dataProvider = $model->getAudios(array_filter($arr));

		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
		$this->render('audioreport', array(
			'model'			 => $model,
			'dataProvider'	 => $dataProvider,
		));
	}

	public function actionDownloadFile($id)
	{
		$cstModel	 = CallStatus::model()->findByPk($id);
		$file		 = $cstModel->cst_recording_file_name;
		if ($file == "")
		{
			
		}
		if ($cstModel->cst_type == 1)
		{
			$url = "http://192.168.1.28/RECORDINGS/MP3/{$file}.mp3";
		}
		else
		{
			$filePath = Yii::app()->basePath . '/Doc/' . $file;
			Yii::app()->request->downloadFile($filePath);
			Yii::app()->end();
		}
	}

}
