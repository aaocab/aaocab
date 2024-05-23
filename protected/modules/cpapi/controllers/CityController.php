<?php

class CityController extends Controller
{

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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(
					'REST.GET', 'REST.PUT', 'REST.REQUEST', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			['allow',
				'actions'	 => ['new', 'list'],
				'users'		 => ['@']
			],
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

		$this->onRest('req.post.getList.render', function() {
			return $this->renderJSON($this->getList());
		});

		$this->onRest('req.post.getSourceList.render', function() {
			return $this->renderJSON($this->getSourceList());
		});

		$this->onRest('req.post.getDestinationList.render', function() {
			return $this->renderJSON($this->getDestinationList());
		});
		$this->onRest('req.post.getAirportList.render', function() {
			return $this->renderJSON($this->getAirportList());
		});
        $this->onRest('req.post.getPackageCityList.render', function() {
			return $this->renderJSON($this->getPackageCityList());
		});
  
	}

	/** @var City $getList */
	public function getList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$key	 = "cities_getList";
			$list	 = Yii::app()->cache->get($key);
			if ($list == false)
			{
				$data = Cities::model()->list();
				if ($data != false)
				{
					$list = Stub\common\Cities::getList($data);
				}
				Yii::app()->cache->set($key, $list, 60 * 60 * 24, new CacheDependency('cityList'));
			}
			$returnSet->setStatus(true);
			$returnSet->setData($list);
		}
		catch (Exception $e)
		{
			throw new Exception("Unknown exception");
			$returnSet = $returnSet->setException($e);
			Logger::exception($e);
		}
		return $returnSet;
	}

	//Get Source Cities
	public function getSourceList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$key	 = "cities_getSourceList";
			$list	 = Yii::app()->cache->get($key);
			if ($list == false)
			{
				$data = Cities::model()->getSource();
				if ($data != false)
				{
					$list = Stub\common\Cities::getList($data);
				}
				Yii::app()->cache->set($key, $list, 60 * 60 * 24, new CacheDependency('cityList'));
			}
			$returnSet->setStatus(true);
			$returnSet->setData($list);
		}
		catch (Exception $e)
		{
			throw new Exception("Unknown exception");
			$returnSet = $returnSet->setException($e);
			Logger::exception($e);
		}
		return $returnSet;
	}

	//Get Destination Cities
	public function getDestinationList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$key	 = "cities_getDestinationList";
			$list	 = Yii::app()->cache->get($key);
			if (false == false)
			{
				$data = Cities::getDestinationList();
				if ($data != false)
				{
					$list = Stub\common\Cities::getList($data);
				}
				Yii::app()->cache->set($key, $list, 60 * 60 * 24, new CacheDependency('cityList'));
			}
			$returnSet->setStatus(true);
			$returnSet->setData($list);
		}
		catch (Exception $e)
		{
			throw new Exception("Unknown exception");
			$returnSet = $returnSet->setException($e);
			Logger::exception($e);
		}
		return $returnSet;
	}

    //Get Airport Cities
	public function getAirportList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data	 = Yii::app()->request->rawBody;  //'{"qry":"Del"}';
			$jsonObj = CJSON::decode($data, false);
			$data	 = Cities::model()->getJSONAirportCitiesAll($jsonObj->qry, $status = 1);

			if ($data != false)
			{
				$obj	 = new \Stub\common\AirportCities();
				$list	 = $obj->getData($data);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($list);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
			Logger::exception($ex);
		}
		return $returnSet;
	}
    public function getPackageCityList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data	 = Yii::app()->request->rawBody;
			$jsonObj = CJSON::decode($data, false);
			$data	 = Cities::getJSONCitiesPackage($jsonObj->qry, $jsonObj->id);
			$data	 = json_decode($data);
			foreach ($data as $d)
			{
				$list[] = array('id' => (int) $d->id, 'name' => $d->text);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($list);
		}
		catch (Exception $e)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($e);
			Logger::exception($e);
		}
		return $returnSet;
	}

}
