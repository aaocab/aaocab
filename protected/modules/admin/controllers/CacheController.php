<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class CacheController extends Controller
{

	public $layout = 'admin1';

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + flush', // we only allow deletion via POST request
			array(
				'RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'
			),
		);
	}

	public function accessRules()
	{
		return array(
			array('allow', // allow admin user to perform 'admin'  actions
				'actions'	 => array('list', 'resetDependency', 'refreshConfig', 'refreshQueue'),
				'roles'		 => array('6 - Developer'),
			),
			['allow', 'actions' => ['clear', 'flushlist'], 'roles' => ['ClearCache']],
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionClear($key)
	{
		if ($key == "db")
		{
			Yii::app()->db->schema->getTables();
			Yii::app()->db->schema->refresh();
			Yii::app()->db1->schema->getTables();
			Yii::app()->db1->schema->refresh();
			return;
		}
		$return				 = [];
		$return['success']	 = true;
		$datafromcity		 = Yii::app()->cache->get($key);
		if ($datafromcity !== false)
		{
			Yii::app()->cache->delete($key);
		}
		else
		{
			Yii::app()->cache->set(CacheDependency::buildCacheId($key), time());
		}
		echo json_encode($return);
		Yii::app()->end();
	}

	public function actionRefreshConfig()
	{
		$data				 = Config::getArrayList(true);
		$return				 = [];
		$return['success']	 = true;
		echo json_encode($return);
		Yii::app()->end();
	}

	public function actionRefreshQueue()
	{
		$data				 = Teams::deleteQueueCached();
		$return				 = [];
		$return['success']	 = true;
		echo json_encode($return);
		Yii::app()->end();
	}

	public function actionFlushlist()
	{
		$return				 = [];
		$return['success']	 = true;
		Yii::app()->cache->flush();
		echo json_encode($return);
		Yii::app()->end();
	}

	public function actionList()
	{
		$this->layout	 = 'admin1';
		$model			 = Lookup::model()->getList();
		$this->render('list', array('model' => $model));
	}

	public function actionResetDependency()
	{
		$dep = Yii::app()->request->getParam("dep");
		Yii::app()->cache->set(CacheDependency::buildCacheId($dep), time());
	}

}
