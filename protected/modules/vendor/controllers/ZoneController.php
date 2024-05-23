<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class ZoneController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout		 = 'column1';
	public $email_receipient, $useUserReturnUrl;
	public $current_page = '';

	//public $layout = '//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter + create',
				'bypass' => false),
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

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array(),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array(),
				'users'		 => array('admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function restEvents()
	{
		$this->onRest('req.cors.access.control.allow.methods', function() {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array('/zoneList', '/allZoneList');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});
		$this->onRest('req.get.zoneList.render', function() {
			return $this->renderJSON($this->zoneList());
		});
		$this->onRest('req.post.allZoneList.render', function () {
			return $this->allZoneList();
		});
	}

	public function zoneList()
	{
		$returnSet		 = new ReturnSet();
		$model			 = new Zones();
		$query			 = Yii::app()->request->getParam('q');
		$dataProvider	 = $model->getZoneList($query);
		$dataObj		 = new Stub\common\Zone();
		$dataObj		 = $dataObj->setZone($dataProvider);
		#print_r($dataObj);
		$response		 = Filter::removeNull($dataObj);
		if (!$response)
		{
			throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}
		$returnSet->setStatus(true);
		$returnSet->setData($response);
		return $returnSet;
	}

	/**
	 * This function is used to show al zone list and populate similar zones based on search query
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function allZoneList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$zoneData	 = false;
			$data		 = Yii::app()->request->rawBody;
			$jsonObj	 = CJSON::decode($data, false);
			$zoneData	 = Yii::app()->cache->get("zones_{$jsonObj->qry}_{$jsonObj->id}");
			if ($zoneData === false)
			{
				$zoneData = Zones::getAllZoneList($jsonObj->qry, $jsonObj->id);
				Yii::app()->cache->set("zones_{$jsonObj->qry}_{$jsonObj->id}", $zoneData, 21600);
			}
			$dataObj	 = new Stub\common\Zone();
			$dataObj	 = $dataObj->mapZone($zoneData);
			$response	 = Filter::removeNull($dataObj);
			if (!$response)
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $this->renderJSON($returnSet);
	}

}
