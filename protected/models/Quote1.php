<?php

/**
 * 
 *
 * The followings are the available columns in table 'quotation':
 * The followings are the available model relations:
 * @property PriceRule $priceRule
 * @property RouteDuration $routeDuration 
 * @property RouteRates $routeRates
 * @property RouteDistance $routeDistance
 * @property BookingRoute[] $routes
 */
class Quote1 extends Quote
{

	private $excludedCabTypes = [];

	//quote_platform
	const Platform_User			 = 1;
	const Platform_Admin			 = 2;
	const Platform_App			 = 3;
	const Platform_Agent			 = 4;
	const Platform_System			 = 0;
	const Platform_Partner_Spot	 = 5;

	/** @param BookingRoute[] $brtRoutes
	 * @return Quote[]
	 */
	public function getQuote($cabs = '', $priceSurge = true, $includeNightAllowance = true, $checkBestRate = false)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		if ($cabs > 0 && !is_array($cabs))
		{
			$cabTypes = [$cabs];
			$this->isCabTypeSupported($cabs);
		}
		else
		{
			$cabTypes = $this->filterCabType($cabs);
		}

		DBUtil::getINStatement($cabTypes, $bindString, $params);

		$sql	 = "SELECT DISTINCT GROUP_CONCAT(scv_id) as cabTypes FROM svc_class_vhc_cat WHERE scv_id IN ($bindString) GROUP BY scv_vct_id ORDER BY scv_scc_id, scv_id";
		$rows	 = DBUtil::query($sql, DBUtil::SDB(), $params);

		$pool		 = \components\AsyncPool\Pool::create()->withBinary('php')->sleepTime(2000)->concurrency(3)->autoload(PUBLIC_PATH . "/bootstrap.php");
	
		foreach ($rows as $row)
		{
			$cabTypes	 = explode(',', $row['cabTypes']);
			$process	 = $pool->add(function () use ($cabTypes, $priceSurge, $includeNightAllowance, $checkBestRate) {
						$this->processQuote($cabTypes, $priceSurge, $includeNightAllowance, $checkBestRate);
						return $this->cabQuotes;
					}, 1024 * 1024 * 1024 * 10)
					->then(function ($cabQuotes) {
						$this->cabQuotes += $cabQuotes;
			})->catch(function($exception){
					Logger::exception($exception);
				});
		}

		$pool->wait();
		//echo base64_encode(serialize($this));
		//print_r($this->routes[0]->brt_trip_distance);exit;
		Logger::trace("from city===" . $this->routes[0]->brt_from_city_id . "====to city======" . $this->routes[count($this->routes) - 1]->brt_to_city_id);
		Logger::trace("trip distance======>" . $this->routes[0]->brt_trip_distance);
		$tableName = "nearest_route_" . $this->routes[0]->brt_from_city_id . "_" . $this->routes[count($this->routes) - 1]->brt_to_city_id;
		DBUtil::dropTempTable($tableName);
		if ($this->suggestedPrice != 1)
		{
			QuotesDataCreated::model()->setData($this, $cabs);
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		return $this->cabQuotes;
	}

	public function processQuote($cabTypes, $priceSurge, $includeNightAllowance, $checkBestRate)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		foreach ($cabTypes as $cabType)
		{
			Logger::beginProfile("Quote For CabType: " . $cabType);
			$key = md5(serialize($this->routes) . "_" . $this->tripType . "_" . $cabType . "_" . $priceSurge . "_" . $includeNightAllowance . "_" . $checkBestRate);
			if (isset($GLOBALS[$key]))
			{
				$model = $GLOBALS[$key];
				goto CabQuotes;
			}
			$model = $this->processCabType($cabType, $priceSurge, $includeNightAllowance, $checkBestRate);

			$GLOBALS[$key] = $model;

			CabQuotes:

			if ($model->success || $this->showErrorQuotes)
			{
				$this->cabQuotes[$model->skuId]	 = $model;
				$this->routeDistance			 = $model->routeDistance;
				$this->routeDuration			 = $model->routeDuration;
			}
			Logger::endProfile("Quote For CabType: " . $cabType);
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
	}

}
