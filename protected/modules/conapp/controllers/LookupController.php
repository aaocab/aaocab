<?php

class LookupController extends Controller
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
			array(
				'CHttpCacheFilter + citylist1',
				'cacheControl' => "max-age=26400, public",
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
		$this->onRest('req.cors.access.control.allow.methods', function () {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function ($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array('/nearestCityList', '/airportCities', '/cityBounds', '/cityList', '/shuttlePickupCityList', '/shuttleDropCityList',
				'/nearestAirportCities', '/stateList', '/countryList', '/dayRentalCityList', '/packageCityList');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.post.cityList.render', function () {
			return $this->cityList();
		});

		$this->onRest('req.post.dayRentalCityList.render', function () {
			return $this->dayRentalCityList();
		});

		$this->onRest('req.post.nearestCityList.render', function () {
			header('Cache-Control: max-age=14400, public', true);
			return $this->nearestCityList();
		});

		$this->onRest('req.post.airportCities.render', function () {
			header('Cache-Control: max-age=28800, public', true);
			return $this->airportCities();
		});

		$this->onRest('req.post.cityBounds.render', function () {
			header('Cache-Control: max-age=28800, public', true);
			return $this->renderJSON($this->cityBounds());
		});

		$this->onRest('req.get.nearestAirportCities.render', function () {
			header('Cache-Control: max-age=28800, public', true);
			return $this->nearestAirportcities();
		});
		$this->onRest('req.get.stateList.render', function () {
			header('Cache-Control: max-age=28800, public', true);
			return $this->stateList();
		});
		$this->onRest('req.get.countryList.render', function () {
			header('Cache-Control: max-age=28800, public', true);
			return $this->countryList();
		});

		$this->onRest('req.post.packageCityList.render', function () {
			return $this->renderJSON($this->packageCityList());
		});

		$this->onRest('req.post.shuttlePickupCityList.render', function () {
			return $this->renderJSON($this->shuttlePickupCityList());
		});

		$this->onRest('req.post.shuttleDropCityList.render', function () {
			return $this->renderJSON($this->shuttleDropCityList());
		});
	}

	/**
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function nearestAirportcities()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data = Yii::app()->request->rawBody;
			if ($data == null)
			{
				throw new Exception("Invalid Request: ", ReturnSet::ERROR_INVALID_DATA);
			}
			$jsonObj	 = CJSON::decode($data, false);
			$response	 = Cities::model()->getJSONNearestAll($jsonObj->cityId, $jsonObj->airportCityRadius, $isAirport	 = 1, $jsonObj->queryStr, '', $status		 = 1);
			if (!$response)
			{
				throw new Exception("No records found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			foreach ($response as $res)
			{
				$responseSet[] = array("id" => (int) $res['id'], "text" => $res['text']);
			}

			$returnSet->setStatus(true);
			$returnSet->setData($responseSet);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $this->renderJSON($returnSet);
	}

	/**
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function cityList()
	{
		header('Cache-Control: max-age=14400, public', true);
		$returnSet = new ReturnSet();
		try
		{
			$dataCities	 = false;
			$data		 = Yii::app()->request->rawBody;
			$jsonObj	 = CJSON::decode($data, false);
			
			$dataCities	 =Yii::app()->cache->get("lookupConAppCitylist_{$jsonObj->qry}_{$jsonObj->id}");
			if ($dataCities === false)
			{
				$dataCities = Cities::model()->getJSONCities($jsonObj->qry, $jsonObj->id);
				Yii::app()->cache->set("lookupConAppCitylist_{$jsonObj->qry}_{$jsonObj->id}", $dataCities, 21600);
			}
			//$dataCities	 = Cities::model()->getJSONSourceCities($jsonObj->qry, $jsonObj->id, 1);

			$returnSet->setStatus(true);
			$returnSet->setData($dataCities);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $this->renderJSON($returnSet);
	}

	public function dayRentalCityList()
	{
		header('Cache-Control: max-age=14400, public', true);
		$returnSet = new ReturnSet();
		try
		{
			$dataCities	 = false;
			$data		 = Yii::app()->request->rawBody;  // {"q":"", "city":""} {"qry":"Del", "id":""}
			$jsonObj	 = CJSON::decode($data, false);
			$query		 = $jsonObj->qry;
			$cityId		 = $jsonObj->id;
			$dataCities	 = Cities::model()->getJSONSourceCitiesDR($query, $cityId, 0, true);
			$dataCities	 = json_decode($dataCities);
			$cities		 = [];
			$ctr		 = 0;
			foreach ($dataCities as $da)
			{
				$cities[$ctr]['id']		 = (int) $da->id;
				$cities[$ctr]['name']	 = $da->text;
				$ctr++;
			}
			Logger::create("Response : " . json_encode($cities), CLogger::LEVEL_INFO);
			$returnSet->setStatus(true);
			$returnSet->setData($cities);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $this->renderJSON($returnSet);
	}

	/**
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function countryList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data		 = Yii::app()->request->rawBody;
			//$data      = '';
			$jsonObj	 = CJSON::decode($data, false);
			$response	 = Countries::getCountryList();

			foreach ($response as $res)
			{
				$responseSet[] = array("id" => (int) $res['id'], "name" => $res['country_name'], "code" => $res['country_phonecode']);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($responseSet);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $this->renderJSON($returnSet);
	}

	/**
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function nearestCityList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data		 = Yii::app()->request->rawBody;
			//$data      = '{"id":30804,"qry":"Del"}';
			$jsonObj	 = CJSON::decode($data, false);
			$limit		 = " LIMIT 100 ";
			$response	 = Cities::getNearestcityList($jsonObj->id, $jsonObj->qry, $limit, 1);
			foreach ($response as $res)
			{
				$responseSet[] = array("id" => (int) $res['id'], "name" => $res['text']);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($responseSet);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $this->renderJSON($returnSet);
	}

	/**
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function airportCities()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data	 = Yii::app()->request->rawBody;  //'{"qry":"Del"}';
			$jsonObj = CJSON::decode($data, false);
			$data	 = Cities::model()->getJSONAirportCitiesAll($jsonObj->qry, $status	 = 1);
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
		}
		return $this->renderJSON($returnSet);
	}

	/**
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function cityBounds()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data		 = Yii::app()->request->rawBody;   // {"airportCityId":0,"cityId":32023}
			$jsonObj	 = CJSON::decode($data, false);
			$cityId		 = ($jsonObj->airportCityId > 0) ? $jsonObj->airportCityId : $jsonObj->cityId;
			$status		 = 1;
			$response	 = Cities::getCityBoundByLatLong($cityId, $status);

			
			$precision = 0.15;
			$response['northeast']['lat']	 = strval($response['northeast']['lat'] + $precision);
			$response['northeast']['lng']	 = strval($response['northeast']['lng'] + $precision);
			$response['southwest']['lat']	 = strval($response['southwest']['lat'] - $precision);
			$response['southwest']['lng']	 = strval($response['southwest']['lng'] - $precision);
			$returnSet->setStatus(true);
			$returnSet->setData($response);
			
			#test
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @return ReturnSet
	 * @throws Exception
	 */
	public function stateList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data	 = Yii::app()->request->rawBody;
			//$data      = '{"id":99}';
			$jsonObj = CJSON::decode($data, false);
			//$jsonObj->id = 99;
			$data	 = States::getStateListByCountryId($jsonObj->id);

			if ($data != false)
			{
				$obj	 = new \Stub\common\State();
				$list	 = $obj->getList($data);
			}

			$returnSet->setStatus(true);
			$returnSet->setData($list);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $this->renderJSON($returnSet);
	}

	/**
	 * 
	 * @return ReturnSet
	 */
	public function packageCityList()
	{
		header('Cache-Control: max-age=14400, public', true);
		$returnSet = new ReturnSet();
		try
		{
			$dataCities	 = false;
			$data		 = Yii::app()->request->rawBody; //  {"qry":"", "id":""}
			$jsonObj	 = CJSON::decode($data, false);
			$query		 = $jsonObj->qry;
			$city		 = $jsonObj->id;
			$dataCities	 = Cities::model()->getJSONCitiesPackage($query, $city);
			$dataCities	 = json_decode($dataCities);
			foreach ($dataCities as $da)
			{
				$responseData[] = array('id' => (int) $da->id, 'name' => $da->text);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($responseData);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return ($returnSet);
	}

	/**
	 * 
	 * @return ReturnSet
	 */
	public function shuttlePickupCityList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data		 = Yii::app()->request->rawBody; // {"qry":"", "id":"", "pickupDate":""}
			$jsonObj	 = CJSON::decode($data, false);
			$query		 = $jsonObj->qry;
			$cityId		 = $jsonObj->id;
			$pickupDate	 = ($jsonObj->pickupDate != '') ? $jsonObj->pickupDate : '';
			$citiesList	 = Cities::model()->getJSONShuttleSourceCities($query, $cityId, $pickupDate, true);

			foreach ($citiesList as $key => $value)
			{
				$responseData[] = array("id" => (int) $key, "name" => $value);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($responseData);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	/**
	 * 
	 * @return ReturnSet
	 */
	public function shuttleDropCityList()
	{
		$returnSet = new ReturnSet();
		try
		{
			$data		 = Yii::app()->request->rawBody;  // {"id":"", "pickupDate":""}
			$jsonObj	 = CJSON::decode($data, false);
			$cityId		 = $jsonObj->id;
			$pickupDate	 = ($jsonObj->pickupDate != '') ? $jsonObj->pickupDate : '';
			$arr		 = ['fcityVal' => $pickupDate, 'fcityVal' => $cityId];
			$citiesList	 = Cities::model()->getJSONShuttledest($arr, true);
			foreach ($citiesList as $key => $value)
			{
				$responseData[] = array("id" => (int) $key, "name" => $value);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($responseData);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

}
