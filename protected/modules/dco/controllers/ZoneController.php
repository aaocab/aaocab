<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class ZoneController extends BaseController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */

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
		$this->onRest('req.cors.access.control.allow.methods', function () {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function ($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array();
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});
		$this->onRest('req.get.getList.render', function () {
			return $this->renderJSON($this->getList());
		});
	}

	/**
	 * This function is used to show al zone list and populate similar zones based on search query
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function getList()
	{
		$returnSet = new \ReturnSet();
		try
		{
			$dataList = false;

			$query = Yii::app()->request->getParam('qry');
			$vndId		 = $this->getVendorId(true);
			$dataList	 = Yii::app()->cache->get("zones_{$query}_dco");
			if ($dataList === false)
			{
				if (trim($query))
				{
					$zoneData = \Zones::getZones($query);
					if ($zoneData->getRowCount() == 0)
					{
						throw new Exception("No Data Found", \ReturnSet::ERROR_NO_RECORDS_FOUND);
					}
					$dataList = \Beans\common\Zones::setListByData($zoneData);
				}
				else
				{
				
					$zoneData	 = Zones::getZoneByHomeZone($vndId);
					$dataList	 = \Beans\common\Zones::getByData($zoneData);
				}
				Yii::app()->cache->set("zones_{$query}_dco", $dataList, 21600);
			}
			$returnSet->setStatus(true);
			$response = Filter::removeNull($dataList);
			$returnSet->setData($response);
		}
		catch (Exception $ex)
		{
			$returnSet = \ReturnSet::setException($ex);
		}
		return $returnSet;
	}

}
