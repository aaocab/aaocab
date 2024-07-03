<?php

/**
 * This is the model class for table "DynamicSurge".
 *
 * The followings are the available columns in table 'DynamicSurge':
 * The followings are the available model relations:
 */
class DynamicSurge
{

	const Counter_DDBP			 = 1;
	const Counter_Manual			 = 2;
	const Counter_Both			 = 3;
	const CounterType_Quote		 = 1;
	const CounterType_Confirm		 = 2;
	const Counter_Manual_Booking	 = 4;
	CONST Type_Default			 = 0;
	CONST Type_Routes				 = 1;
	CONST Type_ZoneRoutes			 = 2;
	CONST Type_ZoneStates			 = 3;
	CONST Type_Zones				 = 4;
	CONST Type_States				 = 5;

	private $_tableName;
	private $_pickupDate;
	private $_fromCity;
	private $_toCity;
	private $_fromZone;
	private $_toZone;
	private $_toState;
	private $_tripType;

	/* @var $dprRoutes, $dprZoneRoutes, $dprZonesStates, $dprZones, $dprStates DynamicPriceRow */
	public $dprDefault, $dprRoutes, $dprZoneRoutes, $dprZonesStates, $dprZones, $dprStates;
	public $type;

	/** @var $dprApplied DynamicPriceRow */
	public $dprApplied;
	public $bucketRow;
	public $zoneBucketRow;
	public $globalFlag;
	public $routeFlag;

	public function __construct()
	{
		$this->dprDefault		 = new DynamicPriceRow();
		$this->dprRoutes		 = new DynamicPriceRow();
		$this->dprZoneRoutes	 = new DynamicPriceRow();
		$this->dprZonesStates	 = new DynamicPriceRow();
		$this->dprZones			 = new DynamicPriceRow();
		$this->dprStates		 = new DynamicPriceRow();
	}

	public function calculate($baseFare, $fromCity, $toCity, $pickupDate, $tripType)
	{
		$log			 = [];
		$this->_fromCity = $fromCity;
		$this->_toCity	 = $toCity;
		$this->_fromZone = Zones::model()->getByCityId($fromCity);
		$this->_toZone	 = Zones::model()->getByCityId($toCity);
		$this->_toState	 = States::model()->getByCityId($toCity);

		$this->_pickupDate	 = date('Y-m-d', strtotime($pickupDate));
		$this->_tripType	 = $tripType;
		$this->baseFare		 = $baseFare;
		$this->isApplicable();

		$localFactor = 1;

		if (in_array($tripType, [4, 12, 9, 10, 11]))
		{
			$localFactor = 2.5;
		}

		$defaultRoutes	 = $this->getDefaultBuckets();
		$countRoutes	 = BookingSub::getCountByRoutes($this->_pickupDate, $fromCity, $toCity, $tripType);
		$rowRoutes		 = $this->getRouteBuckets();
		if (!$rowRoutes)
		{
			goto skipRoutes;
		}
		$baseCapacity				 = $rowRoutes['base_capacity'] * 5;
		$rowRoutes['base_capacity']	 = round($rowRoutes['base_capacity'] * $localFactor);
		$log["routes"]				 = ['bucket' => $rowRoutes, 'count' => $countRoutes];
		$log["routes"]["applied"]	 = true;
		$this->dprRoutes->populate($rowRoutes, $countRoutes, $baseFare, self::Type_Routes);
		$this->dprApplied			 = $this->dprRoutes;
		$this->type					 = self::Type_Routes;
		goto skipDefault;

		skipRoutes:
		$baseCapacity					 = $defaultRoutes['base_capacity'] * 5;
		$defaultRoutes['base_capacity']	 = round($defaultRoutes['base_capacity'] * $localFactor);
		$log["default"]					 = ['bucket' => $defaultRoutes, 'count' => $countRoutes];
		$this->dprDefault->populate($defaultRoutes, $countRoutes, $baseFare, self::Type_Default);
		Logger::trace("DDBP R-R BaseCapacity :" . $baseCapacity . " Count Routes: " . $countRoutes);
		$log["default"]["applied"]		 = true;
		$this->dprApplied				 = $this->dprDefault;
		$this->type						 = self::Type_Default;

		skipDefault:
//		$rowZoneZoneRoutes = $this->getZoneZoneBuckets();
//		if (!$rowZoneZoneRoutes)
//		{
//			goto skipZoneRoutes;
//		}
//		$baseCapacity						 = round($rowZoneZoneRoutes['base_capacity'] * 7);
//		$rowZoneZoneRoutes['base_capacity']	 = round($rowZoneZoneRoutes['base_capacity'] * $localFactor);
//		$countZoneRoutes					 = BookingSub::getCountByZoneRoutes($this->_pickupDate, $fromCity, $toCity, $tripType);
//		Logger::trace("DDBP Z-Z BaseCapacity :" . $baseCapacity . " Count Routes: " . $countZoneRoutes);
//
//		$log["ZoneZone"] = ['bucket' => $rowZoneZoneRoutes, 'count' => $countZoneRoutes];
//		$this->dprZoneRoutes->populate($rowZoneZoneRoutes, $countZoneRoutes, $baseFare, self::Type_ZoneRoutes);
//		if ($this->dprZoneRoutes->baseFare > $this->dprApplied->baseFare)
//		{
//			$log["ZoneZone"]["applied"]	 = true;
//			$this->dprApplied			 = $this->dprZoneRoutes;
//			$this->type					 = self::Type_ZoneRoutes;
//		}
//		skipZoneRoutes:

//		$rowZoneStateRoutes = $this->getZoneStateBuckets();
//		if (!$rowZoneStateRoutes)
//		{
//			goto skipZoneStateRoutes;
//		}
//		$baseCapacity						 = round($rowZoneStateRoutes['base_capacity'] * 2.5);
//		$rowZoneStateRoutes['base_capacity'] = round($rowZoneStateRoutes['base_capacity'] * $localFactor);
//		$countZoneStateRoutes				 = BookingSub::getCountByZoneStateRoutes($this->_pickupDate, $fromCity, $toCity, $tripType);
//		Logger::trace("DDBP Z-S BaseCapacity :" . $baseCapacity . " Count Routes: " . $countZoneRoutes);
//
//		$log["ZoneState"] = ['bucket' => $rowZoneStateRoutes, 'count' => $countZoneStateRoutes];
//
//		$this->dprZonesStates->populate($rowZoneStateRoutes, $countZoneStateRoutes, $baseFare, self::Type_ZoneStates);
//		if ($this->dprZonesStates->baseFare > $this->dprApplied->baseFare)
//		{
//			$log["ZoneState"]["applied"] = true;
//			$this->dprApplied			 = $this->dprZonesStates;
//			$this->type					 = self::Type_ZoneStates;
//		}
//
//		skipZoneStateRoutes:
//		$zones = $this->getZoneBuckets();
//		if (!$zones)
//		{
//			$zones					 = $defaultRoutes;
//			$zones['base_capacity']	 = $baseCapacity;
//		}
//
//		$countZones		 = BookingSub::getCountByZone($this->_pickupDate, $fromCity, $tripType);
//		Logger::trace("DDBP SZ BaseCapacity :" . $baseCapacity . " Count Routes: " . $countZoneStateRoutes);
//		$log["Zones"]	 = ['bucket' => $zones, 'count' => $countZones];
//		$this->dprZones->populate($zones, $countZones, $baseFare, self::Type_Zones);
//		if ($this->dprZones->baseFare > $this->dprApplied->baseFare)
//		{
//			$log["Zones"]["applied"] = true;
//			$this->dprApplied		 = $this->dprZones;
//			$this->type				 = self::Type_Zones;
//		}
//		skipZones:

		if ($this->dprApplied->baseFare > $baseFare)
		{
			Logger::info(json_encode($log));
//			Logger::pushTraceLogs();
		}

		return;
	}

	/** @return DynamicPriceRow */
	public function getDPRType($type = self::Type_Routes)
	{
		$dprObject = false;
		switch ($type)
		{
			case self::Type_States:
				$dprObject		 = $this->dprStates;
				break;
			case self::Type_ZoneStates:
				$this->bucketRow = $this->getZoneStateBuckets();
				$dprObject		 = $this->dprZonesStates;
				break;
			case self::Type_ZoneRoutes:
				$this->bucketRow = $this->getZoneZoneBuckets();
				$dprObject		 = $this->dprZoneRoutes;
				break;
			case self::Type_Zones:
				$this->bucketRow = $this->getZoneBuckets();
				$dprObject		 = $this->dprZones;
				break;
			case self::Type_Routes:
				$this->bucketRow = $this->getRouteBuckets();
				$dprObject		 = $this->dprRoutes;
				break;
			default:
				$this->bucketRow = $this->getDefaultBuckets();
				$dprObject		 = $this->dprDefault;
				break;
		}
		$dprObject->setBucketRow($this->bucketRow);
		return $dprObject;
	}

	public function updateCounter($counter = self::Counter_DDBP, $type = self::CounterType_Quote)
	{
		$isApplicable = $this->isApplicable();
		if (!$isApplicable)
		{
			return false;
		}
		if ($counter == self::Counter_DDBP)
		{
			$this->dprApplied->updateQuoteCounter();
		}

		if ($counter == self::Counter_Manual)
		{
			$this->dprApplied->updateQuoteCounter();
		}

		if ($counter == self::Counter_Both)
		{
			$this->dprApplied->updateConfirmCounter();
		}

		if ($counter == self::Counter_Manual_Booking)
		{
			$this->dprApplied->updateManualConfirmCounter();
		}
	}

	public function isApplicable()
	{
		$this->globalFlag	 = Yii::app()->params['dynamicSurge'];
		$this->routeFlag	 = Route::model()->isDDBPEnable($this->_fromCity, $this->_toCity);
		return ($this->globalFlag && $this->routeFlag);
	}

	public function getDefaultBuckets()
	{
		$sql = "SELECT * FROM dynamic_default_price_surge WHERE DATE(Date) = DATE('" . $this->_pickupDate . "')";
		$row = DBUtil::queryRow($sql, DBUtil::SDB());
		if (!$row)
		{
			$sql = "SELECT * FROM dynamic_default_price_surge WHERE DATE(Date)<=CURDATE() ORDER BY Date DESC LIMIT 0,1";
			$row = DBUtil::queryRow($sql, DBUtil::SDB());
		}
		return $row;
	}

	public function getTableName()
	{
		$dbPrefix			 = 'dynprice_';
		$this->_tableName	 = $dbPrefix . $this->_fromCity . "___" . $this->_toCity;
		return $this->_tableName;
	}

	public function getTableNameByZone()
	{
		$dbPrefix			 = 'dynprice_Z-';
		$this->_tableName	 = $dbPrefix . $this->_fromZone . "___Z-" . $this->_toZone;
		return $this->_tableName;
	}

	public function getTableNameByZoneState()
	{
		$dbPrefix			 = 'dynprice_Z-';
		$this->_tableName	 = $dbPrefix . $this->_fromZone . "___S-" . $this->_toState;
		return $this->_tableName;
	}

	public function getBuckets($tableName)
	{
		$sql = "SELECT * FROM dynamic_price_surge WHERE dps_name_ids = '" . $tableName . "' AND  Date = '" . $this->_pickupDate . "'";
		$row = DBUtil::queryRow($sql, DBUtil::SDB());
		return $row;
	}

	public function getRouteBuckets()
	{
		$params	 = ['sourceId' => $this->_fromCity, 'destId' => $this->_toCity, 'pickupDate' => $this->_pickupDate];
		$sql	 = "SELECT * FROM dynamic_price_surge WHERE dps_source_id=:sourceId AND dps_dst_id=:destId AND dps_area_type=1 AND Date=:pickupDate";
		$row	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $row;
	}

	public function getZoneStateBuckets()
	{
		DBUtil::getINStatement($this->_fromZone, $fromZoneString, $fzParam);
		$params	 = $fzParam + ['destId' => $this->_toState, 'pickupDate' => $this->_pickupDate];
		$sql	 = "SELECT * FROM dynamic_price_surge WHERE dps_source_id IN ($fromZoneString) AND dps_dst_id=:destId AND dps_area_type=3 AND Date=:pickupDate ORDER BY base_capacity DESC";
		$row	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $row;
	}

	public function getZoneZoneBuckets()
	{
		DBUtil::getINStatement($this->_fromZone, $fromZoneString, $fzParam);
		DBUtil::getINStatement($this->_toZone, $toZoneString, $tzParam);
		$params	 = ['pickupDate' => $this->_pickupDate] + $fzParam + $tzParam;
		$sql	 = "SELECT * FROM dynamic_price_surge WHERE dps_source_id IN ($fromZoneString) AND dps_dst_id IN ($toZoneString) AND dps_area_type=2 AND Date=:pickupDate ORDER BY base_capacity DESC";
		$row	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $row;
	}

	public function getZoneBuckets()
	{
		DBUtil::getINStatement($this->_fromZone, $fromZoneString, $fzParam);
		$params	 = ['pickupDate' => $this->_pickupDate] + $fzParam;
		$sql	 = "SELECT * FROM dynamic_price_surge WHERE dps_source_id IN ($fromZoneString) AND dps_area_type=4 AND Date=:pickupDate ORDER BY base_capacity DESC";
		$row	 = DBUtil::queryRow($sql, DBUtil::SDB(), $params);
		return $row;
	}

	public function updateBookingCount($bkgId)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$bpfModel			 = BookingPriceFactor::model()->getByBookingID($bkgId);
		$bkgModel			 = $bpfModel->pfBkg;
		$flag				 = $bpfModel->bkg_surge_applied;
		$this->_fromCity	 = $bkgModel->bkg_from_city_id;
		$this->_toCity		 = $bkgModel->bkg_to_city_id;
		$this->_fromZone	 = Zones::model()->getByCityId($bkgModel->bkg_from_city_id);
		$this->_toZone		 = Zones::model()->getByCityId($bkgModel->bkg_to_city_id);
		$this->_toState		 = States::model()->getByCityId($bkgModel->bkg_to_city_id);
		$this->_pickupDate	 = date('Y-m-d', strtotime($bkgModel->bkg_pickup_date));
		$this->_tripType	 = $bkgModel->bkg_booking_type;
		$factorTypeZone		 = $bkgModel->bkgPf->bkg_ddbp_factor_type;

		if ($factorTypeZone == 0)
		{
			return;
		}
		$this->dprApplied = $this->getDPRType($factorTypeZone);

		if (in_array($flag, [2, 3]))
		{
			$this->dprApplied->updateConfirmCounter();
		}
		if (in_array($flag, [1, 3]))
		{
			$this->dprApplied->updateManualConfirmCounter();
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
	}

	public static function getDDBPV2(Quote &$quoteModel)
	{
		try
		{
			$scv_id					 = $quoteModel->cabType;
			$tripType				 = $quoteModel->tripType;
			$fromCity				 = $quoteModel->sourceCity;
			$toCity					 = $quoteModel->destinationCity;
			$pickupDate				 = $quoteModel->pickupDate;
			$distance				 = $quoteModel->routeDistance->quotedDistance;
			$lastModifiedRateDate	 = Rate::getlastUpdated($fromCity, $toCity, $scv_id, $tripType);
			$days					 = (Filter::getTimeDiff(date("Y-m-d H:i:s"), $lastModifiedRateDate) / 1440);
			if ($days > 7)
			{
				$rowIdentifier		 = DynamicZoneSurge::getRowIdentifier($fromCity, $toCity, $scv_id, $tripType);
				$demandIdentifier	 = DynamicZoneSurge::getDemandIdentifier($fromCity, $tripType);
				$cityIdentifier		 = DynamicZoneSurge::getCityIdentifier($fromCity, $toCity);
				$vndGoingRate		 = DELIVEREDVATREND::getAvgVendorGoingRate($rowIdentifier) * $distance;
				$vndQuotedGoingRate	 = DELIVEREDVATREND::getAvgQuotedVendorGoingRate($rowIdentifier) * $distance;
				$vndAskingPerKm		 = BidSense::getAvgVendorAskingRate($rowIdentifier);
				$vndCostPerDistance	 = 0;
				$upperGuardRail		 = -1;
				$vndAskingRate		 = $vndAskingPerKm * $distance;
				if ($tripType == 1 && $cityIdentifier != null)
				{
					$vndCostPerDistance	 = TravelStatsOw::getVendorCostPerDistanceOW($cityIdentifier);
					$upperGuardRail		 = 0.70 * $vndAskingPerKm + ($vndCostPerDistance != null && $vndCostPerDistance > 0) ? (0.30 * $vndCostPerDistance) : 0;
					$vndAskingRate		 = ($upperGuardRail < $vndAskingPerKm) && $vndAskingPerKm > 0 ? ($upperGuardRail * $distance) : ($vndAskingPerKm * $distance);
				}
				else if (in_array($tripType, array("4", "12")) && $cityIdentifier != null)
				{
					$vndCostPerDistance	 = TravelStatsAp::getVendorCostPerDistanceAP($cityIdentifier);
					$upperGuardRail		 = 0.70 * $vndAskingPerKm + (0.30 * ($vndCostPerDistance != null && $vndCostPerDistance > 0) ? $vndCostPerDistance : 0);
					$vndAskingRate		 = ($upperGuardRail < $vndAskingPerKm) && $vndAskingPerKm > 0 ? ($upperGuardRail * $distance) : ($vndAskingPerKm * $distance);
				}
				else if (in_array($tripType, array("9", "10", '11')) && $cityIdentifier != null)
				{
					$vndCostPerDistance	 = TravelStatsDr::getVendorCostPerDistanceDR($cityIdentifier);
					$upperGuardRail		 = 0.70 * $vndAskingPerKm + 0.30 * ($vndCostPerDistance != null && $vndCostPerDistance > 0) ? $vndCostPerDistance : 0;
					$vndAskingRate		 = ($upperGuardRail < $vndAskingPerKm) && $vndAskingPerKm > 0 ? ($upperGuardRail * $distance) : ($vndAskingPerKm * $distance);
				}
				$deliveryCount		 = QuotesZoneSituation::getDevliveryCount($demandIdentifier, $pickupDate);
				$regularBaseAmt		 = $vndQuotedGoingRate;
				$goingRegularRatio	 = ($regularBaseAmt == 0 || $regularBaseAmt == null) ? 0 : round(($vndGoingRate / $regularBaseAmt), 2);
				$askingGoingRatio	 = ($vndGoingRate == 0 || $vndGoingRate == null) ? 0 : round(($vndAskingRate / $vndGoingRate), 2);
				if ($goingRegularRatio == 0)
				{
					$goingRegularRatio = 1;
				}
				else if ($goingRegularRatio < 1)
				{
					$goingRegularRatio = round($goingRegularRatio + ( (1 - $goingRegularRatio ) / 2 ), 2);
				}
				if ($askingGoingRatio == 0)
				{
					$askingGoingRatio = 1;
				}
				else if ($askingGoingRatio < 1)
				{
					$askingGoingRatio = round($askingGoingRatio + ( ( 1 - $askingGoingRatio ) / 2 ), 2);
				}
				$minTargetSurge					 = min($goingRegularRatio, 1.35) * min($askingGoingRatio, 1.35);
				$quoteModel->DDBPV2finalCapSurge = 0;
				if ($minTargetSurge > 1.2)
				{
					$minTargetSurge					 = ((0.6 * $goingRegularRatio) + ($askingGoingRatio * 0.40));
					$quoteModel->DDBPV2finalCapSurge = ((0.6 * $goingRegularRatio) + ($askingGoingRatio * 0.40));
				}
				$quoteModel->DDBPV2lastUpdateDay		 = $days;
				$quoteModel->DDBPV2vndCostPerDistance	 = $vndCostPerDistance;
				$quoteModel->DDBPV2cityIdentifier		 = $cityIdentifier;
				$quoteModel->DDBPV2upperGuardRail		 = $upperGuardRail;
				$quoteModel->DDBPV2vndAskingPerKm		 = $vndAskingPerKm;
				$quoteModel->DDBPV2rowIdentifier		 = $rowIdentifier;
				$quoteModel->DDBPV2quotedDistance		 = $distance;
				$quoteModel->DDBPV2vndGoingRate			 = $vndGoingRate;
				$quoteModel->DDBPV2vndAskingRate		 = $vndAskingRate;
				$quoteModel->DDBPV2pickupCount			 = $deliveryCount['pickupCount'];
				$quoteModel->DDBPV2initcapacity			 = $deliveryCount['capacity'];
				$quoteModel->DDBPV2rockBaseAmount		 = $regularBaseAmt;
				$quoteModel->DDBPV2goingRegularRatio	 = $goingRegularRatio;
				$quoteModel->DDBPV2askingGoingRatio		 = $askingGoingRatio;
				$quoteModel->DDBPV2minTargetSurge		 = $minTargetSurge;
				$extraSurge								 = 1;
				$DDBPV2Factor							 = Config::get('DDBPV2Factor');
				if (!empty($DDBPV2Factor))
				{
					$result					 = CJSON::decode($DDBPV2Factor);
					$step_size_count_based	 = $result['step_size_count_based'];
					$step_size_5_pre_based	 = $result['step_size_5_pre_based'];
					$step_size_25_pre_based	 = $result['step_size_25_pre_based'];
					$step_size_50_pre_based	 = $result['step_size_50_pre_based'];
					$step_size_100_pre_based = $result['step_size_100_pre_based'];
					$min_capacity			 = $result['min_capacity'];
					$step_count				 = $result['step_count'];
					if ($deliveryCount['capacity'] == 0)
					{
						$zones							 = ZoneCities::getZonesByCity($fromCity);
						$countZone						 = Vendors::getHomeZonesCount($zones);
						$deliveryCount['capacity']		 = $countZone > 0 ? min(max($countZone, 10), $min_capacity) : $min_capacity;
						$quoteModel->DDBPV2finalcapacity = $countZone > 0 ? min(max($countZone, 10), $min_capacity) : $min_capacity;
					}
					if ($quoteModel->routeRates->srgDEBP->factor >= $result['debp_surge_factor_limit'] && $quoteModel->routeRates->srgDEBP->isApplied == 1 && $quoteModel->routeRates->srgDEBP->isApplicable == 1 && $quoteModel->routeRates->srgDEBP->isOverRide == 0)
					{
						$quoteModel->DDBPV2OriginalCapacity	 = $deliveryCount['capacity'];
						$deliveryCount['capacity']			 = ceil($deliveryCount['capacity'] * $result['reduce_capacity']);
						//drop the capacity if its a big event (defined by debp_surge_factor_limit). The capacity reduction is defined by reduce_capacity
					}
					$normalized_count_increase					 = max(0, ($deliveryCount['pickupCount'] - max($deliveryCount['capacity'], $min_capacity)));
					$normalized_increase_per					 = (($normalized_count_increase) / max($deliveryCount['capacity'], $min_capacity)) * 100;
					$surge_basis_count							 = ($deliveryCount['pickupCount'] > $deliveryCount['capacity'] ? ($normalized_count_increase / $step_count) : 0) * $step_size_count_based;
					$surge_basis_5_Per_steps					 = $normalized_increase_per >= 5 && $normalized_increase_per < 25 ? ($normalized_increase_per) * $step_size_5_pre_based : 0;
					$surge_basis_25_Per_steps					 = $normalized_increase_per >= 25 && $normalized_increase_per < 50 ? ($normalized_increase_per) * $step_size_25_pre_based : 0;
					$surge_basis_50_Per_steps					 = $normalized_increase_per >= 50 && $normalized_increase_per < 100 ? ($normalized_increase_per) * $step_size_50_pre_based : 0;
					$surge_basis_100_Per_steps					 = $normalized_increase_per >= 100 ? ($normalized_increase_per) * $step_size_100_pre_based : 0;
					$extraSurge									 = $normalized_count_increase > 0 ? max($surge_basis_5_Per_steps, $surge_basis_25_Per_steps, $surge_basis_50_Per_steps, $surge_basis_100_Per_steps, $surge_basis_count) : 0;
					$quoteModel->DDBPV2normalized_count_increase = $normalized_count_increase;
					$quoteModel->DDBPV2normalized_increase_per	 = $normalized_increase_per;
					$quoteModel->DDBPV2surge_basis_count		 = $surge_basis_count;
					$quoteModel->DDBPV2surge_basis_5_Per_steps	 = $surge_basis_5_Per_steps;
					$quoteModel->DDBPV2surge_basis_25_Per_steps	 = $surge_basis_25_Per_steps;
					$quoteModel->DDBPV2surge_basis_50_Per_steps	 = $surge_basis_50_Per_steps;
					$quoteModel->DDBPV2surge_basis_100_Per_steps = $surge_basis_100_Per_steps;
				}
				$totalSurge						 = $extraSurge > 0 ? (1 + ($extraSurge / 100)) * $minTargetSurge : 1 * $minTargetSurge;
				$quoteModel->DDBPV2totalSurge	 = $totalSurge;
				return $totalSurge;
			}
			else
			{
				return 1;
			}
		}
		catch (Exception $ex)
		{
			return 1;
		}
	}

}

class DynamicPriceRow
{

	public $baseFare;
	public $surgeValue;
	public $factor;
	public $bucketRow;
	public $baseCapacity;
	public $bucket;
	public $countBooking;
	public $type;

	/**
	 * @param int $type {1=>Routes, 2=} */
	public function populate($row, $count, $baseFare, $type)
	{
		$this->type		 = $type;
		$this->bucketRow = $row;

		$this->countBooking	 = $count;
		$this->baseCapacity	 = $this->bucketRow['base_capacity'];
		$d1					 = $this->bucketRow['manuual_count_quotation'];

		$medianBucketVal	 = [0 => 'M_000', 10 => 'M_010', 20 => 'M_020', 30 => 'M_030', 40 => 'M_040', 50 => 'M_050', 60 => 'M_060', 70 => 'M_070', 80 => 'M_080', 90 => 'M_090', 100 => 'M_100', 110 => 'M_100', 120 => 'M_120', 130 => 'M_120', 140 => 'M_140', 150 => 'M_140', 160 => 'M_150', 170 => 'M_170', 180 => 'M_170', 190 => 'M_170', 200 => 'M_200', 210 => 'M_200', 220 => 'M_200', 230 => 'M_200', 240 => 'M_200', 250 => 'M_250', 260 => 'M_250', 270 => 'M_250', 280 => 'M_250', 290 => 'M_250', 300 => 'M_300', 310 => 'M_300', 320 => 'M_300', 330 => 'M_300', 340 => 'M_300', 350 => 'M_300'];
		$surgeCal			 = max([(($this->countBooking) / $this->baseCapacity) - 1, 0]);
		$calculateSurgeCal	 = floor($surgeCal * 10);
		$countSurgeVal[]	 = (int) $calculateSurgeCal * 10;
		$countMedianBucket	 = array_keys($medianBucketVal);
		$matchedMedianVal	 = array_intersect($countSurgeVal, $countMedianBucket);

		$this->bucket		 = $medianBucketVal[$matchedMedianVal[0]];
		$bucketFactor		 = $this->bucketRow[$this->bucket];
		$this->factor		 = max([$bucketFactor, 1]);
		$this->baseFare		 = ROUND($baseFare * $this->factor);
		$this->surgeValue	 = $this->baseFare - $baseFare;
		Logger::trace("DDBP TEST Factor  :" . $this->factor);
	}

	public function setBucketRow($row)
	{
		$this->bucketRow = $row;
	}

	private function updateCounter($field)
	{
		$quoteCount	 = $this->updateIndexCount($this->bucketRow[$field]);
		$sql		 = "UPDATE dynamic_price_surge SET $field='$quoteCount'  WHERE dps_name_ids = '{$this->bucketRow['dps_name_ids']}' AND Date = DATE('{$this->bucketRow['Date']}')";
		DBUtil::command($sql)->execute();
	}

	public function updateQuoteCounter()
	{
		$this->updateCounter('count_quotation');
	}

	public function updateConfirmCounter()
	{
		$this->updateCounter('count_booking');
	}

	public function updateManualQuoteCounter()
	{
		$this->updateCounter('manuual_count_quotation');
	}

	public function updateManualConfirmCounter()
	{
		$this->updateCounter('manuual_count_booking');
	}

	public function updateIndexCount($counters)
	{
		$date1		 = $this->bucketRow['Date'];
		$date2		 = date('Y-m-d');
		$dateDiff	 = (strtotime($date1) - strtotime($date2)) / (60 * 60 * 24);
		$index		 = 0;
		switch (true)
		{
			case $dateDiff <= 1:
				$index	 = 0;
				break;
			case $dateDiff <= 3:
				$index	 = 1;
				break;
			case $dateDiff <= 5:
				$index	 = 2;
				break;
			case $dateDiff <= 7:
				$index	 = 3;
				break;
			case $dateDiff <= 10:
				$index	 = 4;
				break;
			case $dateDiff <= 15:
				$index	 = 5;
				break;
			case $dateDiff <= 20:
				$index	 = 6;
				break;
			case $dateDiff <= 30:
				$index	 = 7;
				break;
			case $dateDiff >= 51:
				$index	 = 8;
				break;
			default:
				$index	 = 0;
		}
		$counters			 = substr(trim($counters), 1, -1);
		$arrCount			 = explode(",", $counters);
		$arrCount[$index]	 = $arrCount[$index] + 1;
		$quoteCount			 = '[' . implode(',', $arrCount) . ']';
		return $quoteCount;
	}

}
