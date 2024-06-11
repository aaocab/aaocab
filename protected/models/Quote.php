<?php

abstract class DynamicPriceAvailability
{

	const Unknown		 = 0;
	const NotAvailable = 1;
	const Available	 = 2;
}

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
class Quote
{

	public $key				= false;
	public $cabQuotes		= [];
	public $vehicleModelId	= 0;
	public $skuId			= 0;
	public $classId			= 0;
	public $sourceCity		= 0;
	public $destinationCity = 0;

	/** @var RouteDistance */
	public $routeDistance;
	public $sourceQuotation			= 0;
	public $isB2Cbooking			= true;
	public $isDynamicSurgeAvialable = DynamicPriceAvailability::Unknown;
	public $dynamicSurgeFactor		= 0;
	public $isManuualSurgeAvialable = DynamicPriceAvailability::Unknown;
	public $manuualSurgeFactor		= 0;
	public static $updateCounter	= false;

	/** @var RouteDuration */
	public $routeDuration;

	/** @var RouteRates */
	public $routeRates;

	/** @var PriceRule */
	public $priceRule;
	public $matchedRoute, $cabType, $tripType, $platform, $partnerId, $pickupDate, $returnDate,
			$quoteDate, $packageID, $packageName, $flexxi_type, $flexxiRates, $noofseat, $flexxi_base_amount, $usedParentCabPrice	 = false;
	public $applyPromo			 = false;
	public $gozoNow				 = false;
	public $useHyperLocation	 = true;
	public $gozoNowVendorAmount	 = 0;
	public $processedTripType;
	public $servingRoute		 = [];
	public $success				 = true;
	public $showErrorQuotes		 = false;
	public $errorCode			 = 0;
	public $errorText			 = null;
	public $rateAddedPackageOnly = false;
	public $minRequiredKms		 = 0;
	public $isconvertedToDR		 = 0; // this is used only for price calculation of kayak day rental converted trip
	public $suggestedPrice		 = 0;
	public $additionalMarkup	 = false;

	/** @var BookingRoute[] */
	public $routes				  = [];
	public $toCities			  = [];
	public $specialNeeds		  = [
		'1' => 'Need carrier',
		'2' => 'Female only passengers',
		'3' => 'Elderly passengers',
		'4' => 'Other requests'
	];
	public $tripList			  = [
		'1' => 'One Way',
		'2' => 'Round Trip',
		'3' => 'Multi-City Trip',
		'4' => 'Transfer Trip'
	];
	public $includeAirportCharges = true;
	public $catypeArr			  = [];
	public $catypeArrIncFlexxi	  = [];
	public $disMultipleFP		  = [1 => 1.15, 2 => 1.1, 3 => 1];
	public $disMultipleFS		  = [1 => 1.25, 2 => 1.2, 3 => 1.1];
	private $excludedCabTypes	  = [];

	//quote_platform
	const Platform_User		  = 1;
	const Platform_Admin		  = 2;
	const Platform_App		  = 3;
	const Platform_Agent		  = 4;
	const Platform_System		  = 0;
	const Platform_Partner_Spot = 5;
	const Platform_Bot		  = 6;

	/**
	 *  @param mixed $model Booking | BookingTemp 
	 */
	public static function populateFromModel($model, $cabType = null, $checkBestRate = false, $includeNightAllowance = true, $isAllowed = false, $gNowVendorAmount = 0, $applyPromo = false)
	{
		/*
		 *  Get blocked location data
		 *  @var $placeObj \Stub\common\Place */
		$pickupPlaceObj		   = Stub\common\Place::init($model->bookingRoutes[0]->brt_from_latitude, $model->bookingRoutes[0]->brt_from_longitude);
		$pickupBlockedLocation = BlockedLocations::getBlockedLocation($pickupPlaceObj);

		$dropoffPlaceObj		= Stub\common\Place::init($model->bookingRoutes[0]->brt_to_latitude, $model->bookingRoutes[0]->brt_to_longitude);
		$dropoffBlockedLocation = BlockedLocations::getBlockedLocation($dropoffPlaceObj);

		if ($pickupBlockedLocation || $dropoffBlockedLocation)
		{
			throw new Exception(json_encode("No cabs available from this location"), ReturnSet::ERROR_VALIDATION);
		}


		$errors = BookingRoute::validateRoutes($model->bookingRoutes, $model->bkg_booking_type, null, $model->bkg_agent_id);

		if (!empty($errors))
		{
			throw new Exception(json_encode($errors), ReturnSet::ERROR_VALIDATION);
		}

		/** @var BookingTrail $bkgTrail */
		$quote					 = new static();
		$quote->minRequiredKms	 = $model->requiredKMs;
		$quote->additionalMarkup = $model->additionalMarkup;
		$quote->routes			 = $model->bookingRoutes;
		if ($model->newBookingRoutes != '')
		{
			$quote->routes = $model->newBookingRoutes;
		}
		$quote->quoteDate  = $model->bkg_create_date;
		$quote->pickupDate = $model->bkg_pickup_date;
		//$quote->sourceQuotation = $model->bkgTrail->bkg_platform;
		$quote->tripType   = $model->bkg_booking_type;
		$quote->partnerId  = $model->bkg_agent_id;

		if ($model instanceof Booking)
		{
			$platform = $model->bkgTrail->bkg_platform;
		}
		if ($model instanceof BookingTemp)
		{
			$platform = $model->bkg_platform;
		}

		$quote->platform = ($platform != '') ? $platform : $model->platform;
		#$quote->platform = $model->platform;

		if ($model->bkg_trip_distance > 0 && $quote->minRequiredKms == 0)
		{
			$quote->minRequiredKms = $model->bkg_trip_distance;
		}

		$quote->isconvertedToDR = $model->isconvertedToDR;

		if (in_array($quote->tripType, [2,3,9,10,11]) && $quote->partnerId == 18190)
		{
			$quote->useHyperLocation = false;
		}
		
		if ($quote->tripType == 5)
		{
			$quote->packageID = $model->bkg_package_id;
		}
		Quote::$updateCounter = true;
		$quote->flexxi_type	  = 1;
		$quote->applyPromo	  = $applyPromo;
		$partnerId			  = Yii::app()->params['gozoChannelPartnerId'];
		$quote->partnerId	  = ($model->bkg_agent_id == null) ? $partnerId : $model->bkg_agent_id;
		$quote->setCabTypeArr();

		$cabModel = SvcClassVhcCat::getCabModelById($cabType);
		if ($cabModel > 0)
		{
			$quote->vehicleModelId = $cabModel;
		}
		$priceSurge = true;
//		$spiceId	 = Config::get('spicejet.partner.id');
//		$sugerboxId	 = Config::get('sugerbox.partner.id');
//		if (($quote->partnerId == $spiceId || $quote->partnerId == $sugerboxId) && $quote->tripType == 12)
//		{
//			$priceSurge = true;
//		}
		if ($quote->partnerId == Config::get('transferz.partner.id'))
		{
			$priceSurge = false;
		}

		$isGozoNow = 0;
		if ($model instanceof Booking)
		{
			$isGozoNow = $model->bkgPref->bkg_is_gozonow;
		}
		if ($model instanceof BookingTemp)
		{
			Logger::trace("populateQuote instanceof:model " . json_encode($model->getAttributes()));
			$isGozoNow = $model->bkg_is_gozonow;
		}

		if ($isGozoNow == 1)
//		if ($model->bkgPref->bkg_is_gozonow == 1)
		{
			$quote->gozoNow				= true;
			$quote->gozoNowVendorAmount = $gNowVendorAmount;
			if ($cabType == null)
			{
				$quote->catypeArr = SvcClassVhcCat::getCabListGNowQuote();
			}
		}
		$quotes = $quote->getQuote($cabType, $priceSurge, $includeNightAllowance, $checkBestRate, $isAllowed);
		//Logger::writeToConsole("==QUOTE EXECUTED==");
		if (!$quote->success)
		{
			//	Logger::trace("Quotes Object: " . json_encode(Filter::removeNull($quotes)));
			//	Logger::trace("Quote Object: " . json_encode(Filter::removeNull($quote)));
			if (($code = $quote->errorCode) == '')
			{
				$code = ReturnSet::ERROR_UNKNOWN;
			}
			if (($msg = $quote->errorText) == '')
			{
				$msg = "Unknown Error";
			}
			if (($msg = $quote->errorText) == 'Cab type not supported for this route.')
			{
				$msg = "Sold out";
			}
			throw new Exception($msg, $code);
		}
		if ($isGozoNow == 0 && $cabType != null && $quotes[$cabType]->gozoNow)
		{
			$isGozoNow = 1;
			if ($model instanceof Booking)
			{
				$model->bkgPref->bkg_is_gozonow = $isGozoNow;
			}
			if ($model instanceof BookingTemp)
			{
				$model->bkg_is_gozonow = $isGozoNow;
			}
		}

		//Logger::writeToConsole("==QUOTES RETURNED==");

		return $quotes;
	}

	public function tripList()
	{
		$tripList = [
			1 => strtoupper('One Way'),
			2 => strtoupper('Round Trip'),
			3 => strtoupper('Multi-City Trip'),
			4 => strtoupper('Transfer Trip'),
		];
		asort($tripList);
		return $tripList;
	}

	public function calculateFlexxiFare($cabType, $noOfSeats = '')
	{
		$svcVct = $cabType;

		if ($this->cabType == VehicleCategory::SEDAN_ECONOMIC)
		{
			$flexxiFare		 = [];
			$extraAmount	 = 0;
			$cabData		 = VehicleTypes::model()->getMasterCarDetails();
			$cabType		 = SvcClassVhcCat::model()->findByPk($cabType)->scv_vct_id;
			//print_r($cabData);
			$totSeats		 = $cabData[$cabType]['vct_capacity'];
			$tollTax		 = $this->routeRates->tollTaxAmount;
			$stateTax		 = $this->routeRates->stateTax;
			$driverAllowance = $this->routeRates->driverAllowance;

			if ($this->flexxi_base_amount > 0 && $this->flexxi_type != 1)
			{
				if ($this->routeRates->baseAmount > $this->flexxi_base_amount)
				{
					$extraAmount = $this->routeRates->baseAmount - $this->flexxi_base_amount;
				}
				$this->routeRates->baseAmount = $this->flexxi_base_amount;
			}


			for ($i = 1; $i < $totSeats; $i++)
			{
				// promoter fare
				$tax_rate		 = BookingInvoice::getGstTaxRate($this->partnerId, $this->tripType);
				$fpBaseFare		 = ROUND(($this->routeRates->baseAmount / $totSeats) * $i * $this->disMultipleFP[$i]);
				$fpToll			 = ROUND(($tollTax / $totSeats) * $i);
				$fpState		 = ROUND(($stateTax / $totSeats) * $i);
				$fpDA			 = ROUND(($driverAllowance / $totSeats) * $i);
				$fpGst			 = ROUND(($fpBaseFare + $fpToll + $fpState + $fpDA) * $tax_rate / 100, 0);
				$fpTotal		 = $fpBaseFare + $fpToll + $fpState + $fpDA + $fpGst;
				$saveAmountPromo = $this->routeRates->baseAmount - $fpBaseFare;

				//subscriber fare
				$fsBaseFare	  = ROUND(($this->routeRates->baseAmount / $totSeats) * $i * $this->disMultipleFS[$i]) + $extraAmount;
				$fsToll		  = ROUND(($tollTax / $totSeats) * $i);
				$fsState	  = ROUND(($stateTax / $totSeats) * $i);
				$fsDA		  = ROUND(($driverAllowance / $totSeats) * $i);
				$fsGst		  = ROUND(($fsBaseFare + $fsToll + $fsState + $fsDA) * $tax_rate / 100, 0);
				$fsTotal	  = $fsBaseFare + $fsToll + $fsState + $fsDA + $fsGst;
				$fssaveamount = $this->routeRates->baseAmount - $fsBaseFare;

				$flexxiFare[$i] = [
					'flexxiBaseAmount'		=> $fpBaseFare,
					'flexxiTollTax'			=> $fpToll,
					'flexxiStateTax'		=> $fpState,
					'flexxiDriverAllowance' => $fpDA,
					'flexxiGst'				=> $fpGst,
					'flexxipayble'			=> $fpTotal,
					'fpsaved'				=> $saveAmountPromo,
					'subsBaseAmount'		=> $fsBaseFare,
					'subsTollTax'			=> $fsToll,
					'subsStateTax'			=> $fsState,
					'subsDriverAllowance'	=> $fsDA,
					'subsGst'				=> $fsGst,
					'subscriberpayble'		=> $fsTotal,
					'fssaved'				=> $fssaveamount,
				];
			}

			$this->flexxi_base_amount = $this->routeRates->baseAmount;
			if ($noOfSeats != '')
			{
				if ($noOfSeats == 4)
				{
					$this->routeRates->baseAmount  = $this->flexxi_base_amount;
					$this->routeRates->gst		   = ROUND(($this->routeRates->baseAmount + $this->routeRates->stateTax + $this->routeRates->tollTaxAmount + $this->routeRates->driverAllowance + $this->routeRates->parkingAmount) * $tax_rate / 100, 0);
					$this->routeRates->totalAmount = $this->routeRates->baseAmount + $this->routeRates->gst + $this->routeRates->getAllowanceAndTaxes();
				}
				else
				{
					$this->routeRates->stateTax		   = ($this->flexxi_type == 1) ? $flexxiFare[$noOfSeats]['flexxiStateTax'] : $flexxiFare[$noOfSeats]['subsStateTax'];
					$this->routeRates->tollTaxAmount   = ($this->flexxi_type == 1) ? $flexxiFare[$noOfSeats]['flexxiTollTax'] : $flexxiFare[$noOfSeats]['subsTollTax'];
					$this->routeRates->driverAllowance = ($this->flexxi_type == 1) ? $flexxiFare[$noOfSeats]['flexxiDriverAllowance'] : $flexxiFare[$noOfSeats]['subsDriverAllowance'];
					$this->routeRates->baseAmount	   = ($this->flexxi_type == 1) ? $flexxiFare[$noOfSeats]['flexxiBaseAmount'] : $flexxiFare[$noOfSeats]['subsBaseAmount'];
					$this->routeRates->gst			   = ($this->flexxi_type == 1) ? $flexxiFare[$noOfSeats]['flexxiGst'] : $flexxiFare[$noOfSeats]['subsGst'];
					$this->routeRates->totalAmount	   = $this->routeRates->baseAmount + $this->routeRates->gst + $this->routeRates->getAllowanceAndTaxes();
				}
			}

			$this->flexxiRates = $flexxiFare;
			$this->cabType	   = ($svcVct == VehicleCategory::SHARED_SEDAN_ECONOMIC) ? VehicleCategory::SHARED_SEDAN_ECONOMIC : $this->cabType;
		}
	}

	public function getRates($checkNearestRoute, $cabType)
	{
		Logger::setCategory("trace.models.Quote.getRates");
		$route = $this->routes[0];
		Logger::info('==============Quote::getRates STARTED===============' . $route->brt_from_city_id . "-" . $route->brt_to_city_id);
		if ($this->tripType == 5)
		{
			$res = Package::model()->getPackageRates($this->packageID, $cabType, $this->pickupDate);
			goto nextStep;
		}
		$res = Route::model()->getRouteRates($route->brt_from_city_id, $route->brt_to_city_id, $cabType, $checkNearestRoute);
		//Logger::trace("Route::model()->getRouteRates: " . json_encode($res));
		//Logger::info("Route::model()->getRouteRates: cabtype:" . $cabType . " :" . json_encode($res));
		nextStep:
		if ($res)
		{
			goto end;
		}
		$res = $this->getNearestRates($cabType);
		if ($res)
		{
			goto end;
		}
		$res = $this->getFromParentRates($checkNearestRoute, $cabType);
		if ($res != null)
		{
			$this->usedParentCabPrice = true;
			goto end;
		}

		//   $res = $this->getNearestRates($cabType);

		end:
		Logger::info('==============Quote::getRates END===============');

		return $res;
	}

	public function getNearestRates($cabType)
	{
		$route = $this->routes[0];
		$res   = Route::getNearestRates($route->brt_from_city_id, $route->brt_to_city_id, $cabType);
		Logger::info("Route::getNearestRates fcity-tcity: " . $route->brt_from_city_id . "-" . $route->brt_to_city_id . " cabtype: " . $cabType);
		Logger::info("Route::getNearestRates results: " . json_encode($res));
//        if ($res)
//        {
//            goto end;
//        }
//
//        $res = $this->getParentNearestRates($cabType);
//        if ($res != null)
//        {
//            $this->usedParentCabPrice = true;
//            goto end;
//        }
//        end:
		return $res;
	}

	public function getParentNearestRates($cabType)
	{
		$svcModel = SvcClassVhcCat::model()->findByPk($cabType);

		if (!$svcModel || $svcModel->scv_parent_id <= 0)
		{
			goto end;
		}
		$parentCabType = $svcModel->scv_parent_id;
		$res		   = $this->getNearestRates($parentCabType);
		if ($res)
		{
			$toCityId = (in_array($this->tripType, [9, 10, 11]) || $this->routes[count($this->routes) - 1]->brt_to_city_id == "") ? $this->routes[0]->brt_from_city_id : $this->routes[count($this->routes) - 1]->brt_to_city_id;
			if ($this->tripType == 5)
			{
				$res['base_prt_vendor_amt'] = $res['prt_vendor_amt'];
				$res['prt_vendor_amt']		= ServiceClassRule::getRateWithMarkUp($this->routes[0]->brt_from_city_id, $cabType, $res['prt_vendor_amt']);
				goto end;
			}
			$res['base_rte_vendor_amount'] = (isset($res['base_rte_vendor_amount']) && $res['base_rte_vendor_amount'] > 0) ? $res['base_rte_vendor_amount'] : $res['rte_vendor_amount'];
			$otherCharges				   = $res['rte_toll_tax'] + $res['rte_state_tax'];
			$baseAmount					   = $res['rte_vendor_amount'] - $otherCharges;

			$res['rte_vendor_amount'] = ServiceClassRule::getRateWithMarkUp($this->routes[0]->brt_from_city_id, $toCityId, $cabType, $baseAmount) + $otherCharges;
		}
		end:
		return $res;
	}

	public function getFromParentRates($checkNearestRoute, $cabType)
	{
		$svcModel = SvcClassVhcCat::model()->findByPk($cabType);

		if (!$svcModel || $svcModel->scv_parent_id <= 0)
		{
			goto end;
		}
		$parentCabType = $svcModel->scv_parent_id;
		$res		   = $this->getRates($checkNearestRoute, $parentCabType);
		if ($res)
		{
			$toCityId = (in_array($this->tripType, [9, 10, 11]) || $this->routes[count($this->routes) - 1]->brt_to_city_id == "") ? $this->routes[0]->brt_from_city_id : $this->routes[count($this->routes) - 1]->brt_to_city_id;
			if ($this->tripType == 5)
			{
				$res['base_prt_vendor_amt'] = $res['prt_vendor_amt'];
				$res['prt_vendor_amt']		= ServiceClassRule::getRateWithMarkUp($this->routes[0]->brt_from_city_id, $cabType, $res['prt_vendor_amt']);
				goto end;
			}
			$res['base_rte_vendor_amount'] = (isset($res['base_rte_vendor_amount']) && $res['base_rte_vendor_amount'] > 0) ? $res['base_rte_vendor_amount'] : $res['rte_vendor_amount'];
			$otherCharges				   = $res['rte_toll_tax'] + $res['rte_state_tax'];
			$baseAmount					   = $res['rte_vendor_amount'] - $otherCharges;
			Logger::info("Quote::getFromParentRates before markup rte_vendor_amount: " . $res['rte_vendor_amount']);
			$res['rte_vendor_amount']	   = ServiceClassRule::getRateWithMarkUp($this->routes[0]->brt_from_city_id, $toCityId, $cabType, $baseAmount) + $otherCharges;
			Logger::info("Quote::getFromParentRates after markup rte_vendor_amount: " . $res['rte_vendor_amount']);
		}
		end:
		return $res;
	}

	public function calculateOneWay($includeNightAllowance = true, $checkNearestRoute = true)
	{
		$key	 = "Quote::calculateOneWay  for CAB {$this->cabType} Date: " . $this->routes[0]->brt_pickup_datetime;
		Logger::beginProfile($key);
		/* @var $route BookingRoute */
		$success = false;
		$route	 = $this->routes[0];
		$pid	 = ($this->partnerId ) ? $this->partnerId : Yii::app()->params['gozoChannelPartnerId'];
		$res	 = $this->getRates($checkNearestRoute, $this->cabType);
		if ($res)
		{
			$this->rateId	 = $res['rte_id']; //128795
			//Logger::trace("Quote->getRates: " . json_encode($res));
			/* @var $priceRule PriceRule */
			$this->priceRule = PriceRule::getByCity($route->brt_from_city_id, $this->tripType, $this->cabType, $this->destinationCity);
			if (!$this->priceRule)
			{
				throw new Exception("City or Cab Type not supported", 102);
			}

			$this->servingRoute['start']  = $res['start'];
			$this->servingRoute['end']	  = $res['end'];
			$this->servingRoute['pickup'] = $route->brt_from_city_id;
			$this->servingRoute['drop']	  = $route->brt_to_city_id;
			$this->toCities				  = [$this->servingRoute['drop'], $res['end']];

			$extraGarageDistance = $res['extraDistance'];
			$extraEndDistance	 = $extraGarageDistance - $res['extraStartDistance'];
			$row				 = Vendors::getCountByCity($route->brt_from_city_id);

			if (($row['cnt'] >= 2 && $extraGarageDistance > 0 && $row['distance'] < $res['extraStartDistance']))
			{
				$extraGarageDistance = $extraGarageDistance - $res['extraStartDistance'] + $row['distance'];
			}
			else if ($route->brtFromCity->cty_is_airport && $extraGarageDistance > 0)
			{
				$extraGarageDistance = $extraGarageDistance - $res['extraStartDistance'];
			}
			$row = Vendors::getCountByCity($route->brt_to_city_id);
			if ($row['cnt'] >= 2 && $extraGarageDistance > 0 && $row['distance'] < $extraEndDistance)
			{
				$extraGarageDistance = $extraGarageDistance - $extraEndDistance + $row['distance'];
			}
			elseif (($route->brtToCity->cty_is_airport) && $extraGarageDistance > 0)
			{
				$extraGarageDistance = $extraGarageDistance - $extraEndDistance;
			}
			$extraGarageDistance = max([$extraGarageDistance, 0]);

			$route->calculateDistance();
			$tripDistance	= $route->brt_trip_distance;
			$quotedDistance = max([$tripDistance, $res['quotedDistance']]);

			//Logger::trace("trip distance====" . $tripDistance . "====quoted distance===" . $quotedDistance);
			if ($quotedDistance >= $res['rateDistance'])
			{
				$extraDistance = max([$quotedDistance - $res['rateDistance'], 0]);
			}
			else
			{
				$extraDistance = max([$res['rateDistance'] - $quotedDistance, 0]);
			}
			$extraRequiredDistance = max([0, $this->minRequiredKms - $quotedDistance]);
			$quotedDistance		   = $quotedDistance + $extraRequiredDistance;
			$extraDistance		   += $extraRequiredDistance;

			$extraGarageFare   = round($extraGarageDistance * max([$this->priceRule->prr_rate_per_km * 0.8, 5]));
			$extraDistanceFare = $extraDistance * $this->priceRule->prr_rate_per_km;
			Logger::info("extraDistance: " . $extraDistance . " extraDistanceFare:" . $extraDistanceFare);
			if ($this->usedParentCabPrice && $this->cabQuotes != [])
			{
				$this->routeDistance = $this->cabQuotes[array_key_first($this->cabQuotes)]->routeDistance;
				$this->routeDuration = $this->cabQuotes[array_key_first($this->cabQuotes)]->routeDuration;
				goto step2;
			}
			$this->routeDistance->quotedDistance = $quotedDistance;
			$this->routeDistance->tripDistance	 = $tripDistance;
			$this->routeDistance->totalGarage	 = $quotedDistance + $extraGarageDistance;
			$this->routeDistance->totalRunning	 = $tripDistance + $extraGarageDistance;
			$routeDesc							 = [];
			$routeDesc[]						 = $route->brtFromCity->cty_name;
			$routeDesc[]						 = $route->brtToCity->cty_name;
			$this->routeDistance->routeDesc		 = $routeDesc;

			$endDate							  = new DateTime($route->brt_pickup_datetime);
			$endDate->add(new DateInterval('PT' . $route->brt_trip_duration . 'M'));
			$routeDuration						  = new RouteDuration();
			$routeDuration->fromDate			  = $route->brt_pickup_datetime;
			$routeDuration->toDate				  = $endDate->format('Y-m-d H:i:s');
			$routeDuration->calculate();
			$this->routeDuration				  = $routeDuration;
			$this->routeDuration->tripDuration	  = $res['rut_estm_time'];
			$this->routeDuration->garageTimeStart = $res['startTime'];
			$this->routeDuration->garageTimeEnd	  = $res['endTime'];
			step2:
			$prtRes								  = PartnerRate::model()->getRates($route->brt_from_city_id, $route->brt_to_city_id, $this->cabType, $pid);

			if ($prtRes)
			{
				$res['prt_id']							 = $prtRes['prt_id'];
				$res['rte_toll_tax']					 = $prtRes['prt_toll_tax'];
				$res['rte_state_tax']					 = $prtRes['prt_state_tax'];
				$res['rte_vendor_amount']				 = $prtRes['prt_vendor_amount'];
				$res['rte_total_amount']				 = $prtRes['prt_total_amount'];
				$diffMarkup								 = $res['rte_vendor_amount'] - $res['base_prt_vendor_amt'];
				$this->routeRates->tollTaxAmount		 = $res['rte_toll_tax'];
				$this->routeRates->stateTax				 = $res['rte_state_tax'];
				$this->routeRates->partnerFixedAmount	 = true;
				$this->routeRates->includeNightAllowance = false;
				$this->routeRates->applySurge			 = ($prtRes['prt_apply_surge'] == 1);
				$this->routeRates->calculateSellBaseFromTotal($prtRes['prt_total_amount'], $this->partnerId, $this->tripType);
				$extraGarageFare						 = 0;
				$this->routeRates->baseAmount			 = $this->routeRates->fixedBaseAmount + round($extraDistance * $this->routeRates->ratePerKM);
				Logger::info("PartnerRate   baseAmount: " . $this->routeRates->baseAmount);
			}
			else
			{
				$diffMarkup = $res['rte_vendor_amount'] - $res['base_rte_vendor_amount'];
			}
			Logger::info("extraDistanceFare: fcity-tcity: " . $route->brt_from_city_id . "-" . $route->brt_to_city_id . " " . $extraDistanceFare);
			$this->routeRates->classMarkup				= $diffMarkup;
			$this->routeRates->parentCost				= $res['base_rte_vendor_amount'];
			$this->routeRates->vendorAmount				= $res['rte_vendor_amount'] + $extraGarageFare + $extraDistanceFare;
			$this->routeRates->oldVendorAmount			= $res['rte_vendor_amount'];
			$this->routeRates->tollTaxAmount			= $res['rte_toll_tax'];
			$this->routeRates->stateTax					= $res['rte_state_tax'];
			$this->routeRates->ignoreDayDriverAllowance = true;
			$this->routeRates->isTollIncluded			= 1;
			$this->routeRates->isStateTaxIncluded		= 1;
			$this->routeRates->rateMarkup				= $res['rte_minimum_markup'];
			$this->routeRates->costPerKM				= $this->priceRule->prr_rate_per_km;
			$this->routeRates->ratePerKM				= $this->priceRule->prr_rate_per_km_extra;
			$this->routeRates->extraPerMinCharge		= $this->priceRule->prr_rate_per_minute_extra;
			//$this->routeRates->extraPerMin               = $this->priceRule->prr_rate_per_minute;

			if ($this->routeRates->partnerFixedAmount)
			{
				$this->routeRates->isTollIncluded	  = $prtRes['prt_is_toll_included'];
				$this->routeRates->isStateTaxIncluded = $prtRes['prt_is_state_included'];
				$this->routeRates->rateMarkup		  = 0;
				$this->routeRates->addAirportCharges($route, $this->includeAirportCharges);
			}
			else
			{
				$this->routeRates->addAirportCharges($route, $this->includeAirportCharges);
//				$pickupCharge	 = CitiesStats::getAirportEntryCharge($this->sourceCity, 1);	
//				$is_airport_fee_included = ($pickupCharge>0)?1:0;
//				$this->routeRates->addAirportCharges($route,$is_airport_fee_included);
			}

			$this->routeRates->calculate($this);
			Logger::info("Final base fare: " . $this->routeRates->baseAmount);
			if ($this->routeRates->minBaseAmount > 0)
			{
				Logger::info("minBaseAmount1: " . $this->routeRates->minBaseAmount);
				Logger::info("maxBaseAmount1: " . $this->routeRates->maxBaseAmount);
			}
			Logger::unsetCategory("trace.models.Quote.getRates");
			if ($this->tripType == 4 || $this->tripType == 14)
			{
				$this->routeDistance->tripDistance = max([$this->routeDistance->tripDistance, $this->priceRule->prr_min_km]);
			}

			$success = true;
			Logger::trace("Quote->calculateOneway routeRates vendorAmount: " . json_encode($this->routeRates->vendorAmount));
		}

		Logger::endProfile($key);
		return $success;
	}

	public function calculatePackage($includeNightAllowance = true, $rateAddedonly = false)
	{
		/* @var $route BookingRoute */
		$route			   = $this->routes;
		$checkNearestRoute = false;
		$res			   = $this->getRates($checkNearestRoute, $this->cabType);
		$success		   = false;
		if ($res['pck_id'] && (!$rateAddedonly || $res['prt_package_rate'] > 0))
		{
			$this->priceRule = PriceRule::getByCity($res['firstFromCity'], $this->tripType, $this->cabType, $this->destinationCity);
			if (!$this->priceRule)
			{
				throw new Exception("City or Cab Type not supported", ReturnSet::ERROR_INVALID_DATA);
			}
			$this->servingRoute['start']  = $res['firstFromCity'];
			$this->servingRoute['end']	  = $res['lastToCity'];
			$this->servingRoute['pickup'] = $res['firstFromCity'];
			$this->servingRoute['drop']	  = $res['lastToCity'];
			$this->toCities				  = [$this->servingRoute['drop'], $res['lastToCity']];

			//$extraDistanceFare				 = $res['extraDistance'] * $this->priceRule->prr_rate_per_km;
			$this->routeDistance->quotedDistance = $res['pck_km_included'];
			$this->routeDistance->tripDistance	 = $res['pck_km_included'];
			$this->routeDistance->totalGarage	 = $res['pck_km_included'] + 0;
			$this->routeDistance->totalRunning	 = $res['pck_km_included'] + 0;
			$routeDesc							 = [];
			$routeDesc[]						 = $route[0]->brtFromCity->cty_name;
			foreach ($route as $rt)
			{
				$routeDesc[] = $rt->brtToCity->cty_name;
			}
//			$routeDesc[]					 = $route[count($route) - 1]->brtToCity->cty_name;
			$this->routeDistance->routeDesc = $routeDesc;
			$durationDetail					= PackageDetails::model()->getTotalDuration($this->packageID);

			$totDuration = max([$res['pck_min_included'], $durationDetail['totDuration']]);

			$pickDateTime  = $route[0]->brt_pickup_datetime;
			$startDateTime = new DateTime($pickDateTime);

			$endDate = $startDateTime->add(new DateInterval('PT' . $totDuration . 'M'));

			$routeDuration			 = new RouteDuration();
			$routeDuration->fromDate = $pickDateTime;
			$routeDuration->toDate	 = $endDate->format('Y-m-d H:i:s');
			$routeDuration->calculate();

			$this->routeDuration			   = $routeDuration;
			$this->routeDuration->tripDuration = $totDuration;
			//$this->routeDuration->garageTimeStart		 = $res['startTime'];
			//$this->routeDuration->garageTimeEnd			 = $res['endTime'];
			if ($res['prt_package_rate'] > 0)
			{
				$success									= true;
				$this->routeRates->vendorAmount				= $res['prt_vendor_amt'] | 0;
				$this->routeRates->tollTaxAmount			= $res['prt_toll_tax'] | 0;
				$this->routeRates->stateTax					= $res['prt_state_tax'] | 0;
				$this->routeRates->driverAllowance			= $res['prt_driver_allowance'] | 0;
				$this->routeRates->parkingAmount			= $res['prt_parking'] | 0;
				//$this->routeRates->rateMarkup				 = $res['rte_minimum_markup'];
				$this->routeRates->ignoreDayDriverAllowance = true;
				$this->routeRates->costPerKM				= $this->priceRule->prr_rate_per_km;
				$this->routeRates->ratePerKM				= $res['prt_rate_per_km'];
				$this->routeRates->extraPerMinCharge		= $this->priceRule->prr_rate_per_km_extra;
				//$this->routeRates->extraPerMin               = $this->priceRule->prr_rate_per_minute;
				if ($res['prt_isIncluded'] == 1)
				{
					$taxes								  = $this->routeRates->tollTaxAmount + $this->routeRates->stateTax;
					$this->routeRates->isTollIncluded	  = 1;
					$this->routeRates->isStateTaxIncluded = 1;
				}
				else
				{
					$taxes = 0;
				}

				$driverAllowance = $this->routeRates->driverAllowance | 0;

				if ($res['prt_isParkingIncluded'] == 1)
				{
					$this->routeRates->isParkingIncluded = 1;
					$parking							 = $this->routeRates->parkingAmount;
				}
				else
				{
					$parking = 0;
				}

				$basefare					  = $res['prt_package_rate'] - ($taxes + $parking + $driverAllowance);
				$this->routeRates->baseAmount = $basefare;
				$this->routeRates->packageID  = $this->packageID;
			}
			else
			{
				throw new Exception("Rate not added for this package.", ReturnSet::ERROR_INVALID_DATA);
			}
			$this->routeRates->calculate($this);
			$this->routeDuration->validatePickupTime($this);
			Logger::profile("validatePickupTime");
		}
		else
		{
			throw new Exception("Rate not added for this package.", ReturnSet::ERROR_INVALID_DATA);
		}

		return $success;
	}

	public function calculatePartnerAT()
	{
		/* @var $route BookingRoute */
		$route			= $this->routes[0];
		$atTransferType = 0;
		if ($route->brtFromCity->cty_is_airport == 1)
		{
			$atTransferType = 1;
			$airportId		= $route->brt_from_city_id;
		}
		if ($route->brtToCity->cty_is_airport == 1)
		{
			$atTransferType = 2;
			$airportId		= $route->brt_to_city_id;
		}
		if ($atTransferType == 0 || $airportId == null)
		{
			return false;
		}

		$tripDistance	= $route->brt_trip_distance;
		$quotedDistance = max([0, $this->minRequiredKms, $tripDistance]);
		Logger::trace("trip distance =>" . $quotedDistance . "from city" . $route->brt_from_city_id . "to_city" . $route->brt_to_city_id);

		$spiceId	= Config::get('spicejet.partner.id');
		$sugerboxId = Config::get('sugerbox.partner.id');
		if (($this->partnerId == $spiceId || $this->partnerId == $sugerboxId) && $this->minRequiredKms > 0)
		{
			$quotedDistance = min([$this->minRequiredKms, $tripDistance]);
		}


		$patRes	 = PartnerAirportTransfer::getRates($this->partnerId, $airportId, $atTransferType, $this->cabType, $quotedDistance);
		$success = false;

		if ($patRes['pat_id'])
		{
			Logger::trace("partner airport id" . $patRes['pat_id']);
			$is_airport_fee_included = ($patRes['is_airport_fee_included'] == 1);
			$kmInData				 = $patRes['pat_minimum_km'];

			$extraKmRate = $patRes['pat_extra_per_km_rate'];

			$extraDistance	   = max([$quotedDistance - $kmInData, 0]);
			$extraDistanceFare = $extraDistance * $extraKmRate;

//			$this->routeRates->calculateSellBaseFromTotal($patRes['pat_total_fare']);
//			$fixedBasePrice	 = $this->routeRates->fixedBaseAmount;
//			$basefare		 = $fixedBasePrice + $extraDistanceFare;
			$this->routeRates->addAirportCharges($route, $is_airport_fee_included);
			$totalfare = $patRes['pat_total_fare'] + $extraDistanceFare;
			$this->routeRates->calculateSellBaseFromTotal($totalfare, $this->partnerId, $this->tripType);
			$basefare  = $this->routeRates->fixedBaseAmount;

			$this->routeDistance->quotedDistance = $quotedDistance;
			$this->servingRoute['start']		 = $route->brt_from_city_id;
			$this->servingRoute['end']			 = $route->brt_to_city_id;
			$this->servingRoute['pickup']		 = $route->brt_from_city_id;
			$this->servingRoute['drop']			 = $route->brt_to_city_id;
			$this->toCities						 = [$this->servingRoute['drop'], $this->servingRoute['drop']];
			$this->routeDistance->tripDistance	 = $quotedDistance;
			$this->routeDistance->totalGarage	 = $quotedDistance;
			$this->routeDistance->totalRunning	 = $quotedDistance;
			$routeDesc							 = [];
			$routeDesc[]						 = $route->brtFromCity->cty_name;
			$routeDesc[]						 = $route->brtToCity->cty_name;
			$this->routeRates->ratePerKM		 = $extraKmRate;
			//$staxrate							 = Filter::getServiceTaxRate();
			$staxrate							 = BookingInvoice::getGstTaxRate($this->partnerId, $this->tripType);
			$this->routeRates->costPerKM		 = round(($extraKmRate / ( 1 + (0.01 * $staxrate))), 0);
			$this->routeDistance->routeDesc		 = $routeDesc;

			$this->routeRates->partnerFixedAmount		= true;
			$this->routeRates->isStateTaxIncluded		= 1;
			$this->routeRates->isTollIncluded			= 1;
			$this->routeRates->isNightAllowanceIncluded = 1;
			$this->routeRates->isDayAllowanceIncluded	= 1;
			$this->routeRates->isNightDropIncluded		= 1;
			$this->routeRates->isNightPickupIncluded	= 1;
			$this->routeRates->driverNightAllowance		= 0;

			$vendorAmount = round($patRes['pat_vendor_amount'] + ($extraDistanceFare * 0.8));

			$pickDateTime			 = $route->brt_pickup_datetime;
			$endDate				 = new DateTime($pickDateTime);
			$endDate->add(new DateInterval('PT' . $route->brt_trip_duration . 'M'));
			$routeDuration			 = new RouteDuration();
			$routeDuration->fromDate = $pickDateTime;
			$routeDuration->toDate	 = $endDate->format('Y-m-d H:i:s');
			$routeDuration->calculate();
			$this->routeDuration	 = $routeDuration;

			$this->routeDuration->tripDuration = $route->brt_trip_duration;

			$this->routeRates->vendorAmount = $vendorAmount | 0;

			$this->routeRates->baseAmount	 = $basefare;
			/**
			 * This function is used for calculating partner airport charges
			 */
			$this->suggestedPrice			 = 2;
			$this->routeRates->checkBestRate = false;
			$recalculateBaseAmount			 = false;
			$this->routeRates->calculate($this, $recalculateBaseAmount);

			$success = true;
		}


		return $success;
	}

	public function calculateLocalTransferPackage()
	{
		/* @var $route BookingRoute */
		$route		  = $this->routes[0];
		$transferType = 0;
		if (in_array($route->brtFromCity->cty_poi_type, [1, 2]))
		{
			$transferType = 1;
			$poiCityId	  = $route->brt_from_city_id;
			$ctyType	  = $route->brtFromCity->cty_poi_type;
		}
		if (in_array($route->brtToCity->cty_poi_type, [1, 2]))
		{
			$transferType = 2;
			$poiCityId	  = $route->brt_to_city_id;
			$ctyType	  = $route->brtToCity->cty_poi_type;
		}
		if ($transferType == 0 || $poiCityId == null || $ctyType == 0)
		{
			return false;
		}

		$tripDistance	= $route->brt_trip_distance;
		$quotedDistance = max([0, $this->minRequiredKms, $tripDistance]);

		$ltpRes	 = LocalTransferPackage::getRates($this->partnerId, $poiCityId, $transferType, $this->cabType, $quotedDistance, $ctyType);
		$success = false;
		if ($ltpRes['ltp_id'])
		{
			$kmInData = $ltpRes['ltp_minimum_km'];

			$extraKmRate = $ltpRes['ltp_extra_per_km_rate'];

			$extraDistance	   = max([$quotedDistance - $kmInData, 0]);
			$extraDistanceFare = $extraDistance * $extraKmRate;

			$this->routeRates->parkingAmount = ($ltpRes['ltp_parking_included'] == 1) ? $ltpRes['ltp_parking_charge'] : 0;
			$totalfare						 = $ltpRes['ltp_total_fare'] + $extraDistanceFare;
			$this->routeRates->calculateSellBaseFromTotal($totalfare, $this->partnerId, $this->tripType);
			$basefare						 = $this->routeRates->fixedBaseAmount;

			$this->routeDistance->quotedDistance = $quotedDistance;
			$this->servingRoute['start']		 = $route->brt_from_city_id;
			$this->servingRoute['end']			 = $route->brt_to_city_id;
			$this->servingRoute['pickup']		 = $route->brt_from_city_id;
			$this->servingRoute['drop']			 = $route->brt_to_city_id;
			$this->toCities						 = [$this->servingRoute['drop'], $this->servingRoute['drop']];
			$this->routeDistance->tripDistance	 = $quotedDistance;
			$this->routeDistance->totalGarage	 = $quotedDistance;
			$this->routeDistance->totalRunning	 = $quotedDistance;
			$routeDesc							 = [];
			$routeDesc[]						 = $route->brtFromCity->cty_name;
			$routeDesc[]						 = $route->brtToCity->cty_name;
			$this->routeRates->ratePerKM		 = $extraKmRate;
			//$staxrate							 = Filter::getServiceTaxRate();
			$staxrate							 = BookingInvoice::getGstTaxRate($this->partnerId, $this->tripType);
			$this->routeRates->costPerKM		 = round(($extraKmRate / ( 1 + (0.01 * $staxrate))), 0);
			$this->routeDistance->routeDesc		 = $routeDesc;

			$this->routeRates->partnerFixedAmount		= true;
			$this->routeRates->isStateTaxIncluded		= 1;
			$this->routeRates->isTollIncluded			= 1;
			$this->routeRates->isNightAllowanceIncluded = 1;
			$this->routeRates->isDayAllowanceIncluded	= 1;
			$this->routeRates->isNightDropIncluded		= 1;
			$this->routeRates->isNightPickupIncluded	= 1;
			$this->routeRates->driverNightAllowance		= 0;

			$vendorAmount = round($ltpRes['ltp_vendor_amount'] + ($extraDistanceFare * 0.8));

			$pickDateTime			 = $route->brt_pickup_datetime;
			$endDate				 = new DateTime($pickDateTime);
			$endDate->add(new DateInterval('PT' . $route->brt_trip_duration . 'M'));
			$routeDuration			 = new RouteDuration();
			$routeDuration->fromDate = $pickDateTime;
			$routeDuration->toDate	 = $endDate->format('Y-m-d H:i:s');
			$routeDuration->calculate();
			$this->routeDuration	 = $routeDuration;

			$this->routeDuration->tripDuration = $route->brt_trip_duration;

			$this->routeRates->vendorAmount = $vendorAmount | 0;

			$this->routeRates->baseAmount		 = $basefare;
			/**
			 * This function is used for calculating partner airport charges
			 */
			$this->routeRates->parkingAmount	 = ($ltpRes['ltp_parking_included'] == 1) ? $ltpRes['ltp_parking_charge'] : 0;
			$this->routeRates->isParkingIncluded = $ltpRes['ltp_parking_included'];
			$this->suggestedPrice				 = 2;
			$this->routeRates->checkBestRate	 = false;
			$recalculateBaseAmount				 = false;
			$this->routeRates->calculate($this, $recalculateBaseAmount);

			$success = true;
		}


		return $success;
	}

	public function generateItineraryKey()
	{
		if ($this->key)
		{
			return $this->key;
		}

		$this->key = md5(json_encode(Filter::removeNull(Stub\common\Itinerary::setModelsData($this->routes))));
	}

	public function filterCabType($cabType)
	{
		$this->excludedCabTypes = SvcClassVhcCat::getExcludedCabTypes($this->routes[0]->brt_from_city_id, $this->routes[count($this->routes) - 1]->brt_to_city_id);

		$cabTypeList   = (is_array($cabType)) ? $cabType : $this::getCabTypeArr();
		$availableList = array_diff($cabTypeList, $this->excludedCabTypes);
		return $availableList;
	}

	public function isCabTypeSupported($cabType)
	{
		$success				= true;
		$this->excludedCabTypes = SvcClassVhcCat::getExcludedCabTypes($this->routes[0]->brt_from_city_id, $this->routes[count($this->routes) - 1]->brt_to_city_id);

		$cabTypes = ServiceClassRule::filterCabsByRule($this->sourceCity, $this->destinationCity, $this->tripType, [$cabType]);

		//	if (!in_array($cabType, $cabTypes))
		if (in_array($cabType, $this->excludedCabTypes) || !in_array($cabType, $cabTypes))
		{
			$success		 = $this->success	 = false;
			$this->errorCode = ReturnSet::ERROR_NO_RECORDS_FOUND;
			$this->errorText = "Cab type not supported for this route.";
		}

		#Logger::info('success == ' . var_export($success));
		return $success;
	}

	/** @param BookingRoute[] $brtRoutes
	 * @return Quote[]
	 */
	public function getQuote($cabs = '', $priceSurge = true, $includeNightAllowance = true, $checkBestRate = false, $isAllowed = false)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		$this->sourceCity	   = $this->routes[0]->brt_from_city_id;
		$this->destinationCity = end($this->routes)->brt_to_city_id;
		if ($this->destinationCity == '')
		{
			$this->destinationCity = 0;
		}
		if ($cabs > 0 && !is_array($cabs))
		{
			$cabTypes = [$cabs];
			$this->isCabTypeSupported($cabs);
		}
		else
		{
			if ($this->platform == Quote::Platform_Admin)
			{
				$cabTypes = (is_array($cabs)) ? $cabs : $this::getCabTypeArr();
			}
			else
			{
				$cabTypes = $this->filterCabType($cabs);
				$cabTypes = ServiceClassRule::filterCabsByRule($this->sourceCity, $this->destinationCity, $this->tripType, $cabTypes);
			}
		}
		if (count($cabTypes) == 0)
		{
			$success		 = $this->success	 = false;
			$this->errorCode = ReturnSet::ERROR_NO_RECORDS_FOUND;
			$this->errorText = "Cab type not supported for this route.";
		}
		$this->validateRoundTrip();

		foreach ($cabTypes as $cabType)
		{
			Logger::beginProfile("Quote For CabType: " . $cabType);
			$key = md5(serialize($this->routes) . "_" . $this->tripType . "_" . $cabType . "_" . $priceSurge . "_" . $includeNightAllowance . "_" . $checkBestRate . "_" . $this->gozoNowVendorAmount);
			if (isset($GLOBALS[$key]))
			{
				$model = $GLOBALS[$key];
				goto CabQuotes;
			}
			$model = $this->processCabType($cabType, $priceSurge, $includeNightAllowance, $checkBestRate);

			$sql		  = "SELECT svc.scv_scc_id FROM svc_class_vhc_cat svc
					-- INNER JOIN service_class scc ON svc.scv_scc_id=scc_id
					WHERE scv_id=:cabId";
			$serviceClass = DBUtil::queryScalar($sql, DBUtil::SDB(), ["cabId" => $cabType]);

			if (!in_array($this->tripType, array(9, 10, 11)))
			{
				goto skipDRCityCheck;
			}
			$result = Vendors::getCountByCity($this->routes[0]->brt_from_city_id);
			if (!$result)
			{
				$model->success	  = false;
				$model->errorCode = ReturnSet::ERROR_REQUEST_CANNOT_PROCEED;
				$model->errorText = "Trip type not supported for this city.";
			}

			skipDRCityCheck:
			$maxCNGLimit = (in_array($this->tripType, [2, 3])) ? 1500 : 1200;

			if (in_array($this->routes[0]->brt_from_city_id, [471404, 451244, 32005, 30366]))
			{
				$maxCNGLimit = 3000;
			}

			$distance = max([$model->routeDistance->totalRunning, $model->routeDistance->quotedDistance]);
			if ($distance > $maxCNGLimit && $serviceClass == 6 && $model->success)
			{
				$model->success	  = false;
				$model->errorCode = ReturnSet::ERROR_REQUEST_CANNOT_PROCEED;
				$model->errorText = "Cab type not supported for this route. (Maximum limit exceeded for CNG)";
			}
			$GLOBALS[$key] = $model;

			CabQuotes:

			if ($model->success || $this->showErrorQuotes)
			{
				$this->cabQuotes[$model->skuId] = $model;
				$this->routeDistance			= $model->routeDistance;

				$this->routeDuration = $model->routeDuration;
			}
			Logger::endProfile("Quote For CabType: " . $cabType);
		}


		//print_r($this->routes[0]->brt_trip_distance);exit;
		//Logger::trace("from city===" . $this->routes[0]->brt_from_city_id . "====to city======" . $this->routes[count($this->routes) - 1]->brt_to_city_id);
		//Logger::trace("trip distance======>" . $this->routes[0]->brt_trip_distance);

		$tableName = "nearest_route_" . $this->sourceCity . "_" . $this->destinationCity;
		DBUtil::dropTempTable($tableName);
		$tableName = "GarageRoute_{$this->sourceCity}_{$this->destinationCity}";
		DBUtil::dropTempTable($tableName);
		if ($this->suggestedPrice != 1)
		{
			QuotesDataCreated::model()->setData($this, $cabs);
		}
		if ($isAllowed)
		{
			// booking confirm request saved for Iread Start
			$IReadBooking  = new Stub\common\IReadBooking();
			$IReadBooking  = $IReadBooking->setData($this, $cabs);
			$IReadresponse = Filter::removeNull($IReadBooking);
			IRead::setQuoteRequest($IReadresponse);
			// booking confirm requst saved for Iread Ends
		}
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
		if ($cabs > 0 && !is_array($cabs))
		{
			QuotesSituation::setQuoteData($this, $cabs);
			QuotesZoneSituation::setQuoteData($this, $cabs);
		}
		if ($this->sourceCity == 44859)
		{
			Logger::unsetCategory("info.models.Quote.testbooking");
		}
		return $this->cabQuotes;
	}

	/**
	 * validate if it is multi-city or round-trip
	 */
	public function validateRoundTrip()
	{
		if ($this->tripType != 3)
		{
			return;
		}
		$routes	   = $this->routes;
		$startCity = $routes[0]->brt_from_city_id;
		$endCity   = $routes[count($routes) - 1]->brt_to_city_id;

		if ($startCity == $endCity)
		{
			$this->tripType = 2;
			return;
		}

		$rutModel = Route::model()->populate($startCity, $endCity);
		if ($rutModel)
		{
			$distance = ($rutModel->rut_actual_distance == '') ? $rutModel->rut_estm_distance : $rutModel->rut_actual_distance;
			if ($distance < 40)
			{
				$this->tripType = 2;
			}
		}
	}

	public function processCabType($cabType, $priceSurge, $includeNightAllowance, $checkBestRate)
	{
		try
		{
			$isFixedPrice							  = false;
			$model									  = clone $this;
			$model->success							  = true;
			$model->isCabTypeSupported($cabType);
			$model->processedTripType				  = $this->tripType;
			$model->routeDistance					  = new RouteDistance();
			$model->routeDistance->hyperLocation	  = $model->useHyperLocation;
			//$model->routeDuration			             = new RouteDuration();
			$model->routeRates						  = new RouteRates();
			$model->routeRates->includeNightAllowance = $includeNightAllowance;
			$model->routeRates->applySurge			  = $priceSurge;
			$model->routeRates->checkBestRate		  = $checkBestRate;
			$model->noofseat						  = $this->noofseat;
			$model->skuId							  = $cabType;
			$svcModel								  = SvcClassVhcCat::model()->findByPk($model->skuId);

			$addons					 = AddonServiceClassRule::getIdsByCityClassId($this->routes[0]->brt_from_city_id, $svcModel->scv_scc_id);
			$model->applicableAddons = $addons;
			$model->className		 = $svcModel->scc_ServiceClass->scc_label;
			$model->classId			 = $svcModel->scc_ServiceClass->scc_id;
			if ($svcModel->scv_model > 0)
			{
				$model->vehicleModelId = $svcModel->scv_model;
				$row				   = SvcClassVhcCat::getModelServiceClass($svcModel->scv_id);
				$cabType			   = $svcModel->scv_id;
			}
			$model->cabType = ($cabType == VehicleCategory::SHARED_SEDAN_ECONOMIC) ? VehicleCategory::SEDAN_ECONOMIC : $cabType;

			if ($this->packageID != '')
			{
				$model->pckageID	= $this->packageID;
				$model->packageName = $this->packageName;
				$model->returnDate	= $this->returnDate;
			}
			$tripType = $this->tripType;
			if ($this->tripType == 12 || $this->tripType == 4 || $tripType == 4)
			{
				$model->partnerId = ($model->partnerId > 0) ? $model->partnerId : Yii::app()->params['gozoChannelPartnerId'];
				$isFixedPrice	  = $model->calculatePartnerAT();
				$model->tripType  = ($isFixedPrice == true ? 12 : 4);
//				if (!$isFixedPrice)
//				{
//					$model->tripType = 4;
//				}
			}
			if ($this->tripType == 15)
			{
				$isFixedPrice = $model->calculateLocalTransferPackage();
			}
			if ($this->tripType == 1)
			{
				$isFixedPrice = $model->calculateOneWay($includeNightAllowance, true);
			}

			if ($this->tripType == 5)
			{
				$isFixedPrice = $model->calculatePackage($includeNightAllowance, $this->rateAddedPackageOnly);
			}


			if ((!$isFixedPrice && $this->tripType != 5) || $this->tripType == 14)
			{
				$model->calculateRules();
			}

			if (($this->flexxi_type == 1 || $this->flexxi_type == 2) && $this->tripType == 1)
			{
				$model->calculateFlexxiFare($cabType, $this->noofseat);
			}
		}
		catch (Exception $e)
		{
			$model->success	  = false;
			$model->errorCode = $e->getCode();
			$model->errorText = $e->getMessage();
			Logger::info($e->getCode() . ": " . $e->getMessage());
		}
		return $model;
	}

	/*
	 * Not in use for now. Need to recheck before use as select tier model implemented
	 */

	public function filterCabsByArea($from = NULL, $to = NULL, $tripType = NULL, $scvId = NULL)
	{
		$excludedCabTypes = BookingSub::getexcludedCabTypes($from, $to);
		if ($scvId == 'catypeArrIncFlexxi')
		{
			$defaultCabTypes = $this->getCabTypeArr();
			goto skipDefaultCabType;
		}
		if ($scvId > 0 && !is_array($scvId))
		{
			$defaultCabTypes = [$scvId];
			goto skipDefaultCabType;
		}
		$defaultCabTypes = $this->getCabTypeArr();

		skipDefaultCabType:
		$svcVctIds = array_diff($defaultCabTypes, $excludedCabTypes);
		return $svcVctIds;
	}

	public function calculateRules()
	{
		Logger::beginProfile("Quote::calculateRules for CAB {$this->cabType}, From City Id: {$this->routes[0]->brt_from_city_id}, To city Id: {$this->routes[count($this->routes) - 1]->brt_to_city_id}");
		$this->priceRule = PriceRule::getByCity($this->sourceCity, $this->tripType, $this->cabType, $this->destinationCity);
		if (!$this->priceRule)
		{
			throw new Exception("City or Cab Type not supported for City: {$this->routes[0]->brt_from_city_id}, Trip: {$this->tripType}, Cab: {$this->cabType}", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}

		if ($this->tripType != 5)
		{
			$this->routeDistance->calculateDistance($this);
		}

		$this->priceRule->processRule($this);

		// $this->routeRates->checkAirportCharges($this->routes[0]);

		if ($this->routeRates->partnerFixedAmount)
		{
			$this->routeRates->checkAirportCharges($this->routes[0]);
		}
		else
		{
			$pickupCharge			 = CitiesStats::getAirportEntryCharge($this->sourceCity, 1);
			$is_airport_fee_included = ($pickupCharge > 0) ? 1 : 0;
			$this->routeRates->addAirportCharges($this->routes[0], $is_airport_fee_included);
		}


		if (in_array($this->tripType, [1, 3]))
		{
			$quote = new Static();
			if ($this->minRequiredKms > 0)
			{
				$quote->minRequiredKms = $this->minRequiredKms;
			}
			$quote->routes		  = Route::addNeareastReturnRoute($this->routes);
			$quote->routeDistance = new RouteDistance();
			$quote->routeRates	  = new RouteRates();
			$quote->tripType	  = 2;
			$quote->cabType		  = $this->cabType;
			$quote->priceRule	  = PriceRule::getByCity($this->sourceCity, $quote->tripType, $this->cabType, $this->destinationCity);
			Logger::trace("Quote::calculateRules (TripCheck: Round): " . $quote->priceRule->prr_id . " CabType: " . $this->cabType);
			$quote->routeDistance->calculateDistance($quote);

			$quote->routeDistance->tripDistance = $this->routeDistance->quotedDistance;

			// if($this->tripType!=5){
			$quote->priceRule->processRule($quote);
			if ($this->routeRates->vendorBaseFare > $quote->routeRates->vendorBaseFare)
			{
				$this->processedTripType = $quote->tripType;
				if ($this->tripType != 1)
				{
					$this->routeDistance->quotedDistance = $quote->routeDistance->quotedDistance;
				}


				$this->routeRates				   = $quote->routeRates;
				$this->priceRule->basePriceRule	   = $quote->priceRule->basePriceRule;
				$this->routeDuration->calendarDays = $quote->routeDuration->calendarDays;
				$this->routeDuration->pickupTime   = $quote->routeDuration->pickupTime;
				$this->routeDuration->dropTime	   = $quote->routeDuration->dropTime;
				Logger::trace("Quote::calculateRules (TripCheck: Round): used" . $quote->priceRule->prr_rate_per_km . " CabType: " . $this->cabType);
			}
		}
		if ($this->priceRule->basePriceRule != null && $this->priceRule->prr_id != $this->priceRule->basePriceRule->prr_id)
		{
			$quote						   = clone $this;
			$quote->routeRates			   = clone $this->routeRates;
			$quote->priceRule			   = $this->priceRule->basePriceRule;
			$quote->cabType				   = $quote->priceRule->prr_cab_type;
			$quote->calculateRules();
			$this->routeRates->classMarkup = round($this->routeRates->vendorBaseFare - $quote->routeRates->vendorBaseFare);
			$this->routeRates->parentCost  = $quote->routeRates->vendorBaseFare;
		}
		$this->routeRates->calculate($this);
		Logger::info("baseAmount: " . $this->routeRates->baseAmount . " totalAmount: " . $this->routeRates->totalAmount);
		//	$this->routeDuration->validatePickupTime($this);
		Logger::endProfile("Quote::calculateRules for CAB {$this->cabType}, From City Id: {$this->routes[0]->brt_from_city_id}, To city Id: {$this->routes[count($this->routes) - 1]->brt_to_city_id}");
	}

	public function forwardPickupDate($days)
	{
		foreach ($this->routes as $route)
		{
			$route->brt_pickup_datetime = date('Y-m-d H:i:s', strtotime($route->brt_pickup_datetime . " +$days day"));
		}
	}

	/**
	 * @return array returns arr of cab types for price quotation
	 */
	public function getCabTypeArr()
	{
		return $this->catypeArr;
	}

	public function setCabTypeArr($platform = NULL, $incFlexxi = false)
	{
		$this->catypeArr = [];
		$arr			 = SvcClassVhcCat::getCabListQuote($this->platform);

		$this->catypeArr = $arr;
	}

	public function getAddonRates()
	{
		$rates	= [];
		$addons = explode(',', $this->applicableAddons);
		foreach ($addons as $addon)
		{
			$routeRates	   = clone $this->routeRates;
			$routeRates->applyAddon($addon);
			$rates[$addon] = $routeRates;
		}
		return $rates;
	}

	/**
	 * This function is used to categorize quotes array into [categoryId][classId] format
	 * @param type $quotesArr array of Quote objects of multiple category classes
	 * @return array of quote objects
	 */
	public static function categoriseByClassCategory($quotesArr)
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		try
		{
			$vctQuotesArr = [];
			$scvIds		  = implode(",", array_keys($quotesArr));
			$rows		  = SvcClassVhcCat::getClassesByIds($scvIds);
			if (!$rows)
			{
				return false;
			}
			foreach ($rows as $row)
			{
				$clsQuotes = [];
				$sccIds	   = explode(',', $row['sccIds']);
				$vctId	   = $row['vctId'];
				foreach ($sccIds as $sccId)
				{
					$scvId = SvcClassVhcCat::getSvcClassIdByVehicleCat($vctId, $sccId);
					$quote = $quotesArr[$scvId];
					if ($quote == null)
					{
						continue;
					}
					$clsQuotes[$sccId] = $quotesArr[$scvId];
				}
				$vctQuotesArr[$vctId] = $clsQuotes;
			}
			return $vctQuotesArr;
		}
		catch (Exception $e)
		{
			Logger::info($e->getCode() . ": " . $e->getMessage());
			return false;
		}
	}
}

class RouteDistance
{

	public $startDistance, $endDistance, $tripDistance, $garageEndDistance,
			$totalRunning, $totalGarage, $quotedDistance, $routeDesc, $chargeableDistance;
	public $hyperLocation = true;

	/**
	 * @param Quote $quoteModel 
	 */
	public function calculateDistance(&$quoteModel)
	{
		$brtRoutes		 = $quoteModel->routes;
		$distance		 = 0;
		$time			 = 0;
		$startTime		 = 0;
		$endTime		 = 0;
		$arrToCity		 = [];
		$routeUpdate	 = false;
		$airportTransfer = false;
		if ($quoteModel->tripType == 4)
		{
			$airportTransfer = true;
		}
		foreach ($brtRoutes as $brtKey => $brtRoute)
		{
			/* @var $brtRoute BookingRoute */
			$brtRoute->calculateDistance($this->hyperLocation, $airportTransfer);

			if (in_array($quoteModel->tripType, array(9, 10, 11)))
			{
				$brtRoute->brt_trip_distance = max($quoteModel->priceRule->prr_min_km, $brtRoute->brt_trip_distance);
				$brtRoute->brt_trip_duration = $quoteModel->priceRule->prr_min_duration;

				$quoteModel->routes[$brtKey]->brt_trip_distance = $brtRoute->brt_trip_distance;
				$quoteModel->routes[$brtKey]->brt_trip_duration = $brtRoute->brt_trip_duration;
				$quoteModel->routes[$brtKey]->est_date			= date('Y-m-d H:i:s', strtotime($brtRoute->brt_pickup_datetime . '+ ' . $brtRoute->brt_trip_duration . ' minute'));
			}
			if ($quoteModel->routes[$brtKey]->brt_from_city_id != $brtRoute->brt_from_city_id || $quoteModel->routes[$brtKey]->brt_to_city_id != $brtRoute->brt_to_city_id)
			{
				$quoteModel->routes[$brtKey]->brt_from_city_id = $brtRoute->brt_from_city_id;
				$quoteModel->routes[$brtKey]->brt_to_city_id   = $brtRoute->brt_to_city_id;
				$routeUpdate								   = true;
			}
			$arrToCity[] = $brtRoute->brt_to_city_id;
			$distance	 += $brtRoute->brt_trip_distance;
			$time		 += $brtRoute->brt_trip_duration;
			$routeDesc[] = $brtRoute->brtFromCity->cty_name;
		}
		Logger::info("Route Distance Calculated");
		if ($routeUpdate)
		{
			$model->updateRoutes($quoteModel->routes);
		}
		$quoteModel->toCities = $arrToCity;
		$this->tripDistance	  = max([$distance, 0]);
		$startRoute			  = $brtRoutes[0];
		/** @var BookingRoute $lastRoute */
		$lastRoute			  = $brtRoutes[count($brtRoutes) - 1];
		$pickupCity			  = $startRoute->brt_from_city_id;
		$pickupAddress		  = $startRoute->brt_from_location;
		$startTripDate		  = $startRoute->brt_pickup_datetime;
		if ($lastRoute->brt_return_date_date == NULL || $lastRoute->brt_return_date_date == '1970-01-01')
		{
			$endDate = new DateTime($lastRoute->brt_pickup_datetime);
			if ($lastRoute->brt_trip_duration)
			{
				$endDate->add(new DateInterval('PT' . $lastRoute->brt_trip_duration . 'M'));
			}
			$endTripDate = $endDate->format('Y-m-d H:i:s');
		}
		else
		{
			$endTripDate = $lastRoute->brt_return_datetime;
		}

		$routeDesc[] = $lastRoute->brtToCity->cty_name;
		$dropCity	 = $lastRoute->brt_to_city_id;
		$dropAddress = $lastRoute->brt_to_location;
		if (!in_array($quoteModel->tripType, array(9, 10, 11)))
		{
			goto skipLocalVendorStats;
		}
		$result = Vendors::getCountByCity($pickupCity);
		if (!$result)
		{
			$result = Vendors::getNearestCity($pickupCity);
		}
		if (!$result)
		{
			goto skipLocalVendorStats;
		}
		$sourceCity		 = $result['vnd_city'];
		$destinationCity = $result['vnd_city'];
		goto skipGarageCity;

		skipLocalVendorStats:
		$result					  = $this->getGarageCity($pickupCity, $dropCity, $quoteModel->cabType, $quoteModel->tripType);
		$sourceCity				  = $result[0]['id'];
		$destinationCity		  = $result[1]['id'];
		$destinationCity		  = ($destinationCity == null) ? $dropCity : $destinationCity;
		skipGarageCity:
		$startDistance			  = 0;
		$quoteModel->servingRoute = ['start' => $sourceCity, 'end' => $dropCity, 'pickup' => $pickupCity, 'drop' => $dropCity];
		$this->routeDesc		  = $routeDesc;

		if ($sourceCity != $pickupCity || $pickupAddress != '')
		{
			$brtModel					= new BookingRoute();
			$brtModel->brt_from_city_id = $sourceCity;
			$brtModel->brt_to_city_id	= $pickupCity;
			$brtModel->calculateDistance(false);
			$startDistance				= $brtModel->brt_trip_distance;
			if ($sourceCity == $pickupCity)
			{
				$startDistance = max([0, $startDistance - 20]);
			}
			$startTime = $brtModel->brt_trip_duration;
			if ($startDistance < 100)
			{
				$quoteModel->servingRoute['start'] = $sourceCity;
			}
		}

		$endDistance	= 0;
		$garageDistance = 0;
		if ($sourceCity != $dropCity || $dropAddress != '')
		{
			$brtModel					= new BookingRoute();
			$brtModel->brt_from_city_id = $dropCity;
			$brtModel->brt_to_city_id	= $sourceCity;
			$brtModel->calculateDistance(false);
			$endDistance				= $brtModel->brt_trip_distance;
			if ($destinationCity == $dropCity)
			{
				$endDistance = max([0, $endDistance - 20]);
			}
			$endTime		= $brtModel->brt_trip_duration;
			$garageDistance = 0;

			if ($sourceCity == $dropCity)
			{
				$garageDistance = 0;
				$endTime		= 0;
			}
			else if ($destinationCity == $sourceCity)
			{
				$quoteModel->servingRoute['end'] = $destinationCity;
				$garageDistance					 = $endDistance;
			}
			else
			{
				$brtModel					= new BookingRoute();
				$brtModel->brt_from_city_id = $dropCity;
				$brtModel->brt_to_city_id	= $destinationCity;
				$brtModel->calculateDistance(false);
				$garageDistance				= min([$brtModel->brt_trip_distance, $endDistance]);
				$endTime					= min([$brtModel->brt_trip_duration, $endTime]);

				$quoteModel->servingRoute['end'] = $destinationCity;
			}
		}

		$tripDistance					= max([$this->tripDistance, $quoteModel->minRequiredKms]);
		$this->totalRunning				= $startDistance + $tripDistance + $endDistance;
		$this->endDistance				= $endDistance;
		$this->startDistance			= $startDistance;
		$this->totalGarage				= $startDistance + $tripDistance + $garageDistance;
		$this->garageEndDistance		= $garageDistance;
		$this->quotedDistance			= $tripDistance;
		$routeDuration					= new RouteDuration();
		$routeDuration->fromDate		= $startTripDate;
		$routeDuration->toDate			= $endTripDate;
		$routeDuration->tripDuration	= $time;
		$routeDuration->garageTimeStart = $startTime;
		$routeDuration->garageTimeEnd	= $endTime;

		$routeDuration->calculate();
		if ($quoteModel->isconvertedToDR == 1)
		{
			$drMaxMinutes				 = Kayak::dayRentalMaxMinutes($quoteModel->tripType);
			$routeDuration->totalMinutes = max([$drMaxMinutes, $routeDuration->totalMinutes]);
			$routeDuration->tripDuration = max([$time, $routeDuration->totalMinutes]);
		}
		$quoteModel->routeDuration = $routeDuration;
	}

	public function calculateMinimumDriverDays()
	{
		
	}

	public function getServingCity($pickupCity)
	{
		$row		 = Vendors::getCountByCity($pickupCity);
		$servingCity = $row['vnd_city'];
		$sql		 = "
			SELECT * FROM route
			INNER JOIN route r1 ON r1.rut_from_city_id=route.rut_to_city_id AND route.rut_to_city_id=$servingCity AND route.rut_from_city_id=$dropCity
			INNER JOIN route r2 ON route.rut_from_city_id=r2.rut_to_city_id
			";
	}

	//get lat long search using last drop location
	public function getGarageCity($pickupCity, $dropCity, $cabType = 0, $tripType = 2)
	{
		Logger::beginProfile(__CLASS__ . "::" . __FUNCTION__);
		$result = $this->getNearestRoute($pickupCity, $dropCity, $cabType);
		if ($result)
		{
			return [
				0 => ['id' => $result["c2id"], "address" => $result["c2address"]],
				1 => ['id' => $result["c4id"], "address" => $result["c4address"]],
			];
		}
		if ($pickupCity != '' || $pickupCity != null)
		{
			$arrPickup			  = $this->nearestRouteCity($pickupCity);
			Logger::create("Quote getGarageCity 3:\t", CLogger::LEVEL_PROFILE);
			$arrPickup['address'] = $result['address'];
			$pickupcityid		  = $arrPickup['id'];
			if ($dropCity != '' || $dropCity != null)
			{
				$arrDrop			= $this->nearestRouteCity($dropCity, $pickupcityid, $cabType, $tripType);
				Logger::create("Quote getGarageCity 4:\t", CLogger::LEVEL_PROFILE);
				$arrDrop['address'] = $result['address'];
			}
		}
		Logger::endProfile(__CLASS__ . "::" . __FUNCTION__);
		return [0 => $arrPickup, 1 => $arrDrop];
	}

	public function getNearestRoute($pickupCity, $dropCity, $cabType = '')
	{
		$key = "$pickupCity-$dropCity-$cabType";
		$arr = Yii::app()->cache->get($key);
		if ($arr !== false)
		{
			$data = $arr;
			goto end;
		}
		if (!$arr)
		{
			$arr = [];
		}
		if ($cabType != '')
		{
			$cabType   = SvcClassVhcCat::getLowerClassId($cabType);
			$condition = " AND rate.rte_vehicletype_id IN ($cabType)";
		}

		if ($pickupCity == $dropCity)
		{
			$sql = "
                SELECT cities.cty_id as c2id, cities.cty_name as c2name, cities.cty_garage_address as c2address,
                    cities.cty_id as c4id, cities.cty_name as c4name, cities.cty_garage_address as c4address,
                        (distance * 2) as totalExtraDistance
                 FROM (SELECT route.rut_to_city_id as city, route.rut_estm_distance as distance FROM cities
                     INNER JOIN route ON cities.cty_id=route.rut_from_city_id AND rut_active=1 AND cities.cty_id=$pickupCity AND route.rut_estm_distance<40
                     INNER JOIN route r1 ON route.rut_to_city_id=r1.rut_from_city_id AND r1.rut_active=1
                     INNER JOIN rate ON r1.rut_id=rate.rte_route_id AND rate.rte_status=1 $condition GROUP BY route.rut_id HAVING count(*) >30
                   UNION
                     SELECT route.rut_to_city_id as city, route.rut_estm_distance as distance FROM cities
                     INNER JOIN route ON cities.cty_id=route.rut_from_city_id AND rut_active=1 AND cities.cty_id=$pickupCity AND route.rut_estm_distance<600
                     INNER JOIN cities tc ON tc.cty_id=route.rut_to_city_id AND tc.cty_service_active=1 AND CalcDistance(cities.cty_lat, cities.cty_long, tc.cty_lat, tc.cty_long)<=route.rut_estm_distance						 
                     INNER JOIN cities_stats ON cities_stats.cts_cty_id = route.rut_to_city_id AND cities_stats.cts_vnd_cnt >= 2
                   UNION
                    SELECT cts_cty_id AS city, 0 AS distance
					FROM cities_stats WHERE 1 AND cities_stats.cts_cty_id= $dropCity and cities_stats.cts_vnd_cnt>1) a
                    INNER JOIN cities ON cities.cty_id=a.city
                    ORDER BY totalExtraDistance LIMIT 1";

			$data = DBUtil::queryRow($sql, DBUtil::SDB(), [], 2 * 24 * 60 * 60, CacheDependency::Type_Routes);

			if (!$data)
			{
				$data = null;
			}

			Yii::app()->cache->set($key, $data, 24 * 60 * 60, new CacheDependency('routes'));
		}
		else
		{

			$sql  = "SELECT 
						fc.cty_id as c2id, 
						fc.cty_name as c2name, 
						fc.cty_garage_address as c2address,
						tc.cty_id as c4id,
						tc.cty_name as c4name, tc.cty_garage_address as c4address,
						0 as totalExtraDistance FROM route
						INNER JOIN cities fc ON rut_from_city_id=fc.cty_id
						INNER JOIN cities tc ON rut_to_city_id=tc.cty_id
						INNER JOIN rate on  rate.rte_route_id=route.rut_id $condition
						WHERE route.rut_from_city_id=$pickupCity AND route.rut_to_city_id=$dropCity AND route.rut_active=1";
			$data = DBUtil::queryRow($sql, DBUtil::SDB(), [], 2 * 24 * 60 * 60, CacheDependency::Type_Routes);

			if (!$data)
			{
				$sql  = "
				SELECT fc.cty_id as c2id, fc.cty_name as c2name, fc.cty_garage_address as c2address, tc.cty_id as c4id, 
				tc.cty_name as c4name, tc.cty_garage_address as c4address, totalExtraDistance
					FROM
					 (
						(
							SELECT r3.rut_id as new_route_id, r3.rut_name as new_route_name, r3.rut_from_city_id,  
								r3.rut_to_city_id, route.rut_name , route.rut_id,
								(route.rut_estm_distance + r2.rut_estm_distance - r3.rut_estm_distance) as totalExtraDistance, 
								r3.rut_estm_distance
							FROM route
							INNER JOIN route r2 ON route.rut_to_city_id = r2.rut_from_city_id AND route.rut_from_city_id=$pickupCity  
									AND route.rut_to_city_id=$dropCity AND r2.rut_active=1 AND r2.rut_estm_distance<=60
							INNER JOIN route r3 ON ((r3.rut_from_city_id=route.rut_from_city_id AND r2.rut_to_city_id=r3.rut_to_city_id)) 
									AND r3.rut_active=1 
							INNER JOIN rate on  rate.rte_route_id=r3.rut_id $condition
						)
						UNION
						(
							SELECT r3.rut_id, r3.rut_name, r3.rut_from_city_id, r3.rut_to_city_id, route.rut_name , route.rut_id,(route.rut_estm_distance + r1.rut_estm_distance - r3.rut_estm_distance) as totalExtraDistance, r3.rut_estm_distance
							FROM route
							INNER JOIN route r1 ON route.rut_from_city_id = r1.rut_to_city_id AND route.rut_from_city_id=$pickupCity AND  route.rut_to_city_id=$dropCity AND r1.rut_active=1 AND route.rut_active=1 AND r1.rut_estm_distance<=60
							INNER JOIN route r3 ON ((r3.rut_from_city_id=r1.rut_from_city_id AND route.rut_to_city_id=r3.rut_to_city_id))	AND r3.rut_active=1 
							INNER JOIN rate on  rate.rte_route_id=r3.rut_id $condition
						)
                     ) a
					INNER JOIN cities fc ON a.rut_from_city_id=fc.cty_id
					INNER JOIN cities tc ON a.rut_to_city_id=tc.cty_id ORDER BY totalExtraDistance ASC LIMIT 1";
				$data = DBUtil::queryRow($sql, DBUtil::SDB(), [], 2 * 24 * 60 * 60, CacheDependency::Type_Routes);
			}

			if (!$data)
			{
				$sql = "SELECT fc.cty_id as c2id, fc.cty_name as c2name, fc.cty_garage_address as c2address,
                              tc.cty_id as c4id, tc.cty_name as c4name, tc.cty_garage_address as c4address, totalExtraDistance
                        FROM (SELECT r3.rut_id as new_route_id, r3.rut_name as new_route_name, r3.rut_from_city_id, r3.rut_to_city_id, route.rut_name , route.rut_id,
                                    (route.rut_estm_distance + r1.rut_estm_distance + r2.rut_estm_distance - r3.rut_estm_distance) as totalExtraDistance, r3.rut_estm_distance
                                FROM route
								INNER JOIN route r1 ON route.rut_from_city_id = r1.rut_to_city_id AND route.rut_from_city_id=$pickupCity AND r1.rut_active=1 AND route.rut_active=1 AND r1.rut_estm_distance<=60
								INNER JOIN route r2 ON route.rut_to_city_id = r2.rut_from_city_id AND route.rut_to_city_id=$dropCity AND r2.rut_active=1 AND r2.rut_estm_distance<=60
								INNER JOIN route r3 ON ((r3.rut_from_city_id=r1.rut_from_city_id AND r2.rut_to_city_id=r3.rut_to_city_id)) AND r3.rut_active=1 
								INNER JOIN rate on  rate.rte_route_id=r3.rut_id $condition LIMIT 1
							)a
                        INNER JOIN cities fc ON a.rut_from_city_id=fc.cty_id
                        INNER JOIN cities tc ON a.rut_to_city_id=tc.cty_id ORDER BY totalExtraDistance ASC LIMIT 1
                        ";

				$data = DBUtil::queryRow($sql, DBUtil::SDB(), [], 2 * 24 * 60 * 60, CacheDependency::Type_Routes);
			}
			if (!$data)
			{
				//			$randomNumber	 = rand();
				$tempTable = "GarageRoute_{$pickupCity}_{$dropCity}";

//				DBUtil::dropTempTable($tempTable);

				$params = ["pickupCity" => $pickupCity, "dropCity" => $dropCity];

				$sqlCreate = " (INDEX {$tempTable}_index_rut_id (rut_id), INDEX {$tempTable}_index_rut_from_city_id (rut_from_city_id), INDEX {$tempTable}_index_rut_to_city_id (rut_to_city_id))
							SELECT route.rut_id, route.rut_from_city_id, route.rut_to_city_id, route.rut_name, rut_estm_distance FROM route 
							INNER JOIN cities_stats   ON cities_stats.cts_cty_id = route.rut_from_city_id
							AND rut_estm_distance < 300
							AND rut_active = 1
							AND route.rut_to_city_id=$pickupCity
							AND cities_stats.cts_vnd_cnt >= 3
							ORDER BY rut_estm_distance LIMIT 10";

				DBUtil::createTempTable($tempTable, $sqlCreate);

				$sql = "SELECT a.*, fc.cty_id as c2id, fc.cty_name as c2name, fc.cty_garage_address as c2address,
								tc.cty_id as c4id, tc.cty_name as c4name, tc.cty_garage_address as c4address 
						FROM ((SELECT xyz.rut_from_city_id as startCity, r1.rut_to_city_id as endCity,
								   xyz.rut_name as startRoute, r1.rut_name as servingRoute, r3.rut_name as endRoute,
								   (xyz.rut_estm_distance + r2.rut_estm_distance) as ExtraDistance
							FROM $tempTable as xyz
							INNER JOIN route r1 ON r1.rut_from_city_id=xyz.rut_from_city_id
							INNER JOIN rate ON rate.rte_route_id=r1.rut_id $condition
							INNER JOIN route r2 ON r2.rut_to_city_id=r1.rut_to_city_id AND r2.rut_from_city_id=:dropCity AND r2.rut_active=1
							INNER JOIN route r3 ON r3.rut_from_city_id=xyz.rut_to_city_id AND r3.rut_to_city_id=r2.rut_from_city_id AND r3.rut_active=1
							ORDER BY ExtraDistance LIMIT 1
							)
							UNION
							(
							SELECT xyz.rut_from_city_id as startCity, r2.rut_to_city_id as endCity,
								   xyz.rut_name as startRoute, r1.rut_name as servingRoute, r2.rut_name as endRoute,
								   (xyz.rut_estm_distance + r2.rut_estm_distance) as ExtraDistance
							FROM $tempTable as xyz
							INNER JOIN route r1 ON r1.rut_from_city_id=xyz.rut_to_city_id AND r1.rut_to_city_id=:dropCity
							INNER JOIN route r2 ON r2.rut_to_city_id=xyz.rut_from_city_id AND r2.rut_from_city_id=r1.rut_to_city_id
							ORDER BY ExtraDistance ASC LIMIT 1
							)) a
						INNER JOIN cities fc ON startCity=fc.cty_id
						INNER JOIN cities tc ON endCity=tc.cty_id 
						ORDER BY ExtraDistance";

				$data = DBUtil::queryRow($sql, DBUtil::SDB(), $params, 2 * 24 * 60 * 60, CacheDependency::Type_Routes);

				//		DBUtil::dropTempTable($tempTable);
			}
			if (!$data)
			{
				$data = null;
			}
			Yii::app()->cache->set($key, $data, 4 * 60 * 60, new CacheDependency('routes'));
		}
		end:
		return $data;
	}

	public function nearestRouteCity($cityId, $sourceCity = null, $cabType = 0, $tripType = null)
	{
		if ($cityId == null || $cityId == "")
		{
			throw new Exception("Required data missing", ReturnSet::ERROR_INVALID_DATA);
		}
		$key = "$cityId-$sourceCity-$cabType-$tripType";
		$arr = Yii::app()->cache->get($key);
		if ($arr !== false)
		{
			$data = $arr;
			goto end;
		}
		if (!$arr)
		{
			$arr = [];
		}
		$cityDetails = Cities::model()->cache(2 * 60 * 60, new CacheDependency(CacheDependency::Type_Cities))->findByPk($cityId);
		if ($cityDetails == null || $cityDetails == "")
		{
			throw new Exception("Required data missing for $cityId", ReturnSet::ERROR_INVALID_DATA);
		}
		$latitude	= $cityDetails->cty_lat;
		$longitude	= $cityDetails->cty_long;
		$latitude1	= $latitude - 0.5;
		$latitude2	= $latitude + 0.5;
		$longitude1 = $longitude - 0.5;
		$longitude2 = $longitude + 0.5;

		$condition = "";
		if ($tripType == 1)
		{
			$condition3 = " OR cty.cty_id=rut.rut_to_city_id";
		}
		if ($sourceCity != null)
		{
			$condition = " AND (rut.rut_to_city_id=$sourceCity OR rut.rut_from_city_id=$sourceCity)  ";
		}
		if ($cabType > 0)
		{
			$svcIds		= SvcClassVhcCat::model()->getParentCabWithClass($cabType);
			$strSvcIds	= implode(',', $svcIds);
			$condition1 .= " AND rte_vehicletype_id IN ($strSvcIds)";
		}

		$qry = "WITH RateCte AS (SELECT  rte_route_id  FROM rate WHERE  rte_status = 1 $condition1) 
				SELECT  
				cty.cty_id AS id,
				cty_garage_address,
				cty.cty_name AS name,
				CalcDistance(cty.cty_lat, cty.cty_long, $latitude,$longitude) AS distance
				FROM route rut INNER JOIN cities cty ON (cty.cty_id = rut.rut_from_city_id  $condition3) AND cty_active = 1  AND ( cty.cty_lat  between $latitude1 and $latitude2)	AND ( cty.cty_long   between $longitude1 and $longitude2 )
				INNER JOIN RateCte on  RateCte.rte_route_id=rut.rut_id
				WHERE 1 $condition 
				AND CalcDistance(cty.cty_lat, cty.cty_long, $latitude,$longitude) IS NOT NULL
				ORDER BY distance LIMIT 0,1";

		$data = DBUtil::queryRow($qry, DBUtil::SDB(), [], 2 * 24 * 60 * 60, CacheDependency::Type_Routes);
		if (!$data)
		{
			$data = null;
		}

		Yii::app()->cache->set($key, $data, 2 * 24 * 60 * 60, new CacheDependency('routes'));
		end:
		return $data;
	}
}

class RouteDuration
{

	public $fromDate, $toDate, $nights, $pickupTime, $dropTime, $tripDuration, $garageTimeStart, $garageTimeEnd,
			$calendarDays, $totalMinutes, $durationInWords, $extraNights, $extraNightPickupIncluded, $extraNightDropIncluded, $driverRunningDays;

	public function calculate()
	{
		$fromDate		  = $this->fromDate;
		$toDate			  = $this->toDate;
		$fromData2		  = strtotime($fromDate);
		$toDate2		  = strtotime($toDate);
		$this->pickupTime = date("His", $fromData2);
		$this->dropTime	  = date("His", $toDate2);
		$startDate		  = new DateTime(date('Y-m-d', $fromData2));
		$endDate		  = new DateTime(date('Y-m-d', $toDate2));
		$interval		  = $startDate->diff($endDate);
		$calendarDays	  = $interval->format('%a');

		$driverRunningDays = $calendarDays;
		$calendarDays++;

		if ($this->tripDuration > 0)
		{
			$cDays			   = ceil($this->tripDuration / 600);
			$driverRunningDays = max([$calendarDays, $cDays]);
		}

		$night = $calendarDays - 1;

		$seconds = $toDate2 - $fromData2;
		$minutes = round($seconds / 60);
		$minutes = ceil($minutes / 15) * 15;
		$sec	 = $minutes * 60;
		$days	 = floor($sec / 86400);
		$hours	 = floor(($sec - ($days * 86400)) / 3600);
		$thours	 = floor($sec / 3600);
		$min	 = $minutes - ($thours * 60);
		$dur	 = '';
		if ($days > 0)
		{
			$dur .= $days . " days ";
		}
		if ($hours > 0)
		{
			$dur .= $hours . " hrs ";
		}
		if ($min > 0)
		{
			$dur .= $min . " mins";
		}
		$this->nights			 = $night;
		$this->calendarDays		 = $calendarDays;
		$this->driverRunningDays = $driverRunningDays;
		$this->totalMinutes		 = $minutes;
		$this->durationInWords	 = $dur;
	}

	public function calculateRunningNight()
	{
		$totalMinutes = $this->totalMinutes + $this->garageTimeEnd;
		$totalNight	  = ceil($totalMinutes / 600);
		return $totalNight;
	}

	/** @param Quote $quoteModel */
	public function calculateNight($quoteModel)
	{
		$ruleNightStartTime	 = str_replace(":", "", $quoteModel->priceRule->prr_night_start_time);
		$ruleNightEndTime	 = str_replace(":", "", $quoteModel->priceRule->prr_night_end_time);
		$pickupTime			 = $this->pickupTime;
		$dropTime			 = $this->dropTime;
		$night				 = $this->calendarDays - 1;
		$extraNights		 = 0;
		$nightPickupIncluded = 0;
		$nightDropIncluded	 = 0;
		// in_array($quoteModel->tripType, [1, 4]) && (
//	|| $pickupTime >= $ruleNightStartTime
		Logger::trace("nightendTime: " . $ruleNightEndTime . "nightstartTime:" . $ruleNightStartTime . "nightCount:" . $night);
		if ($pickupTime < $ruleNightEndTime)
		{
			Logger::trace("time :" . $pickupTime . "ruleNightEndTime : " . $ruleNightEndTime);
			$nightPickupIncluded = 1;
			$extraNights++;
		}
		//|| $dropTime <= $ruleNightEndTime
		if ($dropTime >= $ruleNightStartTime)
		{
			$nightDropIncluded = 1;
			$extraNights++;
		}

		if ($pickupTime >= $ruleNightStartTime)
		{
			$nightPickupIncluded = 1;
		}

		if ($dropTime <= $ruleNightEndTime)
		{
			$nightDropIncluded = 1;
		}


		$this->extraNights				= $extraNights;
		$this->extraNightPickupIncluded = $nightPickupIncluded;
		$this->extraNightDropIncluded	= $nightDropIncluded;
		if ($quoteModel->priceRule->prr_day_driver_allowance > 0 && !$quoteModel->routeRates->ignoreDayDriverAllowance)
		{
			$night = 0;
		}
		$this->nights = max([$night, 0]);
		Logger::trace("Pickup Type: " . $pickupTime . "dropTyme: " . $dropTime . "night: " . $night . "extraNighr: " . $extraNights . "thisNight: " . $this->nights);
	}

	/** @param Quote $quoteModel */
	public function validatePickupTime(&$quoteModel)
	{
		$pickupTime	 = new DateTime($this->fromDate);
		$currentTime = new DateTime();
		if ($quoteModel->partnerId == 450)
		{
			$graceTime = 240 + $this->garageTimeStart;
		}

		//$graceTime	 = $quoteModel->priceRule->prr_min_pickup_duration + $this->garageTimeStart;
		else if ((in_array($quoteModel->routes[0]->brt_from_city_id, [32007, 31001]) && $quoteModel->tripType == 4) || in_array($quoteModel->tripType, [9, 10, 11]))
		{
			$graceTime = 240;
		}
		else
		{
			$graceTime = 240 + $this->garageTimeStart;
		}
		$currentTime->add(new DateInterval("PT{$graceTime}M"));
		if ($currentTime > $pickupTime)
		{
//			$response = $this->checkGozoNow($quoteModel, $graceTime);
//			if (!$response->success)
//			{

			$quoteModel->success   = false;
			$quoteModel->errorCode = 103;
			$quoteModel->errorText = "Pickup Time should be greater than " . $currentTime->format("d-M-Y H:i:s");
//			}
		}
	}

	public function checkGozoNow(&$quoteModel, $graceTime)
	{

		$response->success		  = false;
		$minTime				  = Config::getMinPickupDuration($quoteModel->partnerId, $quoteModel->tripType, 0);
		$response				  = new stdClass();
		$response->timeDifference = $minTime;
		$response->isAllowed	  = false;

		$checkGozoNowEnabled = Config::checkGozoNowEnabled();
		$diff				 = floor((strtotime($this->fromDate) - time()) / 60);
		if ($checkGozoNowEnabled && $minTime > $diff && ($quoteModel->partnerId == 1249 || null($quoteModel->partnerId) ) && $quoteModel->tripType == 1)
		{
			$gzminTime				  = Config::getMinGozoNowPickupDuration($quoteModel->tripType, 0);
			$response->timeDifference = $gzminTime;
			if ($gzminTime < $diff)
			{
				$quoteModel->gozoNow = true;
				$response->success	 = true;
			}
		}
		return $response;
	}
}

/** @property Quote $quoteModel
 * 
 * @property Surge $srgManual
 * @property Surge $srgDZPP
 * @property Surge $srgDDBP
 * @property Surge $srgDTBP
 * @property Surge $srgDDBPV2
 * @property Surge $srgDURP
 * @property Surge $srgDEBP
 * @property Surge $srgDURPV2
 * @property Surge $srgDDSBP
 * 
 * 
 *  */
class RouteRates
{

	public $baseAmount, $additionalCharge, $discount, $gst, $driverAllowance,
			$driverDayAllowance, $driverNightAllowance,
			$vendorAmount, $vendorBaseFare, $totalAmount, $oldVendorAmount,
			$partnerCommission, $partnerDiscount, $rateMarkup, $rockBaseAmount,
			$rockBottomAmount, $fixedBaseAmount,
			$baseSurge, $costPerKM,
			$ratePerKM, $surgeAmount, $surgeId, $packageID, $checkBestRate, $bestRateDate, $classMarkup, $extraPerMinCharge, $extraPerMin;
	public $ignoreDayDriverAllowance  = false;
	public $applySurge				  = true;
	public $includeNightAllowance	  = true;
	public $isNightPickupIncluded	  = 0;
	public $isNightDropIncluded		  = 0;
	public $isDayAllowanceIncluded	  = 0;
	public $isNightAllowanceIncluded  = 0;
	public $cabAvailability			  = 1;
	public $partnerFixedAmount		  = false;
	public $surgeFactorUsed			  = 0;
	public $partner_soldout			  = 0;
	public $regularBaseAmount		  = 0;
	public $differentiateSurgeAmount  = 0;
	public $airportEntryFee			  = 0;
	public $isAirportEntryFeeIncluded = 0;
	public $isAirportChargeApplicable = 0;
	public $isTollIncluded			  = 0;
	public $tollTaxAmount			  = 0;
	public $isStateTaxIncluded		  = 0;
	public $stateTax				  = 0;
	public $parkingAmount			  = 0;
	public $isParkingIncluded		  = 0;
	public $promoRow, $coinDiscount, $promoCode;
	public $addonId					  = 0;
	public $addonCharge				  = 0;
	public $parentCost				  = 0;
	public $addonCharges			  = [];

	/** @var Quote $qModel */
	private $quoteModel;
	public $bestRatesModel;
//for gozoNow
	public $minBaseAmount, $maxBaseAmount;
	public $minTotalAmount, $maxTotalAmount;
	public $minVendorAmount, $maxVendorAmount, $gnowSuggestedOfferRange;

	public function applyPromo()
	{
		Logger::beginProfile("RouteRates::applyPromo for CAB {$this->quoteModel->cabType}");

		/** @var Quote $qModel */
		$qModel								   = $this->quoteModel;
		$bkgModel							   = new Booking('new');
		$bkgModel->bkgInvoice				   = new BookingInvoice();
		$bkgModel->bkgTrail					   = new BookingTrail();
		$bkgModel->bkg_create_date			   = $qModel->quoteDate;
		$bkgModel->bkg_booking_type			   = $this->quoteModel->tripType;
		$bkgModel->bkg_pickup_date			   = $qModel->routes[0]->brt_pickup_datetime;
		$bkgModel->bkg_from_city_id			   = $qModel->routes[0]->brt_from_city_id;
		$bkgModel->bkg_to_city_id			   = $qModel->routes[count($qModel->routes) - 1]->brt_to_city_id;
		$bkgModel->bkg_vehicle_type_id		   = $qModel->cabType;
		$bkgModel->bkgInvoice->bkg_base_amount = $this->baseAmount;
		$bkgModel->bkgTrail->bkg_platform	   = $qModel->platform | 1;
		$rows								   = Promos::allApplicableCodes($bkgModel);

		if (count($rows) > 0)
		{
			$this->promoRow		= $rows->read();
			//$this->discount	 = $rows[0]['cashAmount'];
			$this->discount		= $this->promoRow['cashAmount'];
			$this->coinDiscount = $this->promoRow['coinsAmount'];
			$this->promoCode	= $this->promoRow['prm_code'];
		}
		Logger::endProfile("RouteRates::applyPromo for CAB {$this->quoteModel->cabType}");
	}

	/** @param Quote $quoteModel */
	public function checkBestFare($quoteModel)
	{
		Logger::beginProfile("RouteRates::checkBestFare for CAB {$quoteModel->cabType} {$quoteModel->pickupDate}");
		$bestRatesModel = null;
		for ($i = 0; $i <= 3; $i++)
		{
			if ($i == 0)
			{
				$qModel				= $quoteModel;
				$qModel->routeRates = $quoteModel->routeRates;
			}
			else
			{
				$qModel				= clone $quoteModel;
				$qModel->routeRates = clone $quoteModel->routeRates;
				$qModel->routes		= Filter::cloneObjectArray($quoteModel->routes);
			}

			$qModel->routeRates->checkBestRate = false;
			$qModel->forwardPickupDate($i);
			$qModel->routeRates->calculate($qModel);
			/** @var Quote $bestRatesModel */
			if ($bestRatesModel == null || $bestRatesModel->getNetBaseAmount() > $qModel->routeRates->getNetBaseAmount())
			{
				$this->bestRateDate = $qModel->routes[0]->brt_pickup_datetime;
				$bestRatesModel		= clone $qModel->routeRates;
			}
		}
		$this->bestRatesModel = $bestRatesModel;
		Logger::endProfile("RouteRates::checkBestFare for CAB {$quoteModel->cabType} {$quoteModel->pickupDate}");
	}

	/**
	 * This function is used for checking airport charges for both (pickup/drop)
	 * @param BookingRoute $route
	 * @return int $charges
	 */
	public function checkAirportCharges($route)
	{
		$isAirportPickupDrop = $route->isAirportPickDrop();
		$charges			 = false;
		if ($isAirportPickupDrop)
		{
			$charges						 = CitiesStats::getAirportEntryCharges($route->brt_from_city_id, $route->brt_to_city_id);
			$this->isAirportChargeApplicable = 1;
		}
		return $charges;
	}

	/**
	 * This function is used for adding airport charges in quotation model
	 * @param BookingRoute $route
	 */
	public function addAirportCharges($route, $includeAirportCharges = false)
	{
		$isAirport = $route->isAirportPickDrop();
		if (!$isAirport)
		{
			return;
		}

		$this->isAirportEntryFeeIncluded = ($includeAirportCharges) ? 1 : 0;
		$this->airportEntryFee			 = 0;
		$charges						 = $this->checkAirportCharges($route);
		if ($charges > 0 && $includeAirportCharges)
		{
			$this->airportEntryFee = $charges;
		}
	}

	/**
	 * @param Quote $quoteModel 
	 */
	public function calculate(&$quoteModel, $recalculateBaseAmount = true)
	{
		$this->quoteModel = $quoteModel;

//		$quoteModel->routeDuration->validatePickupTime($quoteModel);


		if ($this->quoteModel->gozoNow)
		{
			if ($this->driverAllowance == '' && $this->packageID == '' && $this->quoteModel->gozoNowVendorAmount == 0)
			{
				$this->calculateDriverAllowance();
			}

			Logger::trace("enter pre calculateGozoNow ");
			$this->calculateGozoNow($quoteModel);
			Logger::trace(" post calculateGozoNow ");
			Logger::trace(" minBaseAmount " . $this->minBaseAmount);
			goto skipGNowEnd;
		}

		$key				  = "RouteRates::calculate for CAB {$quoteModel->cabType} {$quoteModel->pickupDate} - Trip Type: {$quoteModel->tripType} " . rand(1, 999999999);
		Logger::beginProfile($key);
		$updateCounter		  = Quote::$updateCounter;
		Quote::$updateCounter = false;

		if ($this->checkBestRate)
		{
			$this->checkBestFare($quoteModel);
			Logger::endProfile($key);
			return;
		}

		$this->calculateVendorFare();
		$this->getRockBottomPrice();
		$this->regularBaseAmount = $this->rockBaseAmount;

		if ($this->driverAllowance == '' && $this->packageID == '')
		{
			$this->calculateDriverAllowance();
		}

		$fityPercentDA = round($this->driverAllowance * 0.20);
		if ($this->packageID != '')
		{
			$this->getRockBottomPricePackage();
			$fityPercentDA = 0;
		}

		if ($this->quoteModel->suggestedPrice != 1 && $this->applySurge)
		{
			if ($this->quoteModel->suggestedPrice == 2)
			{
				$this->rockBaseAmount = $this->quoteModel->routeRates->baseAmount;
			}
			$this->applySurge();
			if ($this->quoteModel->gozoNow && ($this->quoteModel->partnerId == 1249 || $this->quoteModel->partnerId == null))
			{
				$quoteModel->gozoNow = true;
				$this->calculateGozoNow($quoteModel);
				goto skipGNowEnd;
			}

			$this->baseAmount = $this->rockBaseAmount;
			//In case of airport packages surge will be apply but markup won't be apply
			if ($this->quoteModel->suggestedPrice != 2 || ($this->quoteModel->partnerId == Config::get('spicejet.partner.id')))
			{
				$this->getMarkup($quoteModel);
			}
		}

		$this->vendorAmount		= round($this->vendorBaseFare * 0.99) + $this->getAllowanceAndTaxes() - $fityPercentDA;
		$this->rockBottomAmount = $this->rockBaseAmount + $this->getAllowanceAndTaxes();
		Logger::trace("rockBaseAmount: " . $this->rockBaseAmount . " VendorAmount: $this->vendorAmount");
		Logger::info("baseAmount: " . $this->baseAmount . " VendorAmount: $this->vendorAmount");

		if (!$this->partnerFixedAmount && $recalculateBaseAmount)
		{
			$partnerModel			 = Agents::model()->resetScope()->findByPk($quoteModel->partnerId);
			$partnerMinimumBasePrice = round($partnerModel->calculateSellPrice($this->rockBaseAmount));
			$this->baseAmount		 = max([$this->baseAmount, $partnerMinimumBasePrice]); //5892
			$preDataArr				 = [];
			$preData				 = $this->additional_param;
			if ($preData != '')
			{
				$preDataArr = json_decode($preData, true);
			}
			$this->additional_param = json_encode($preDataArr + ['partnerMinimumBasePrice' => $partnerMinimumBasePrice]);
			Logger::info("not partnerFixedAmount        baseAmount: " . $this->baseAmount);
		}

		$this->calculateTotal();

		if (SvcClassVhcCat::getClassById($this->quoteModel->cabType) != ServiceClass::CLASS_ECONOMIC && $this->classMarkup > 0 && $this->parentCost > 0)
		{
			$this->vendorAmount -= round($this->classMarkup * 0.5);
		}
		//$quoteModel->applyPromo = 0;
		if ($quoteModel->applyPromo && ($quoteModel->partnerId == null || $quoteModel->partnerId == 1249))
		{
			$this->applyPromo();
		}
		$this->updateCounter($updateCounter);
		Logger::endProfile($key);
		skipGNowEnd:
	}

	public function calculateGozoNow(&$quoteModel, $recalculateBaseAmount = true)
	{
//      $this->tripType;
//		$this->stateTax				 = 0;
//		$this->isStateTaxIncluded	 = 1;
//		$this->tollTaxAmount		 = 0;
//		$this->isTollIncluded		 = 1;
//		$this->parkingAmount		 = 0;
//		$this->airportEntryFee		 = 0;

		$this->checkBestRate		  = false;
		$this->applySurge			  = true;
		$this->quoteModel->applyPromo = false;

		//GNow variables
		$minGZNowMarkup = Config::get('booking.gozoNow.quote.minMarkup');
		$maxGZNowMarkup = Config::get('booking.gozoNow.quote.maxMarkup');

		$minGZNowVal = 1 + (0.01 * $minGZNowMarkup);
		$maxGZNowVal = 1 + (0.01 * $maxGZNowMarkup);

		if ($this->quoteModel->gozoNowVendorAmount > 0)
		{
			$this->vendorBaseFare = 0; //$this->quoteModel->gozoNowVendorAmount;
			$this->vendorAmount	  = $this->quoteModel->gozoNowVendorAmount;
		}

		$key				  = "RouteRates::calculate for CAB {$quoteModel->cabType} {$quoteModel->pickupDate} - Trip Type: {$quoteModel->tripType} " . rand(1, 999999999);
		Logger::beginProfile($key);
		$updateCounter		  = Quote::$updateCounter;
		Quote::$updateCounter = false;

		$this->quoteModel		 = $quoteModel;
		$this->quoteModel->tripType;
		$this->calculateVendorFare();
		$this->getGNowRockBottomPrice();
		$this->regularBaseAmount = $this->rockBaseAmount;

		$originalVendorAmount = $this->vendorAmount;

		if ($this->quoteModel->gozoNowVendorAmount > 0)
		{
			$this->baseAmount = $this->rockBaseAmount;
			goto skipSurge;
		}


		if (!$this->surgeFactorUsed)
		{
			$this->applySurge();
			$this->calculateVendorFare();
		}

		Logger::trace("Applied GozoNow {$quoteModel->gozoNow}");
		if ($quoteModel->gozoNow)
		{
			Logger::trace("GozoNow override for cab type {$quoteModel->cabType}");
			$this->gozoNow = $quoteModel->gozoNow;
		}

		$surge = max([$this->differentiateSurgeAmount, 0]);

		Logger::info("RouteRates::calculate cabType={$quoteModel->cabType} vendorBaseFare=" . $this->vendorBaseFare);
		$this->minVendorAmount = round(min($originalVendorAmount * 0.95, $this->vendorAmount));
		$this->baseAmount	   = $this->rockBaseAmount;
		$minSurgeMarkup		   = min($surge * 0.35, $surge);
		$this->minBaseAmount   = round(min($this->regularBaseAmount + $minSurgeMarkup, $this->baseAmount));
		$this->maxBaseAmount   = round(max(($this->regularBaseAmount + 250), ($this->baseAmount), $this->regularBaseAmount * 1.1));

		$maxVendorSurge = $this->maxBaseAmount - $this->regularBaseAmount;

		$this->maxVendorAmount = round($originalVendorAmount + $maxVendorSurge * 0.90); // $vendorAmount + $vndsurge;
		if ($this->minVendorAmount > 0 && $this->maxVendorAmount > 0)
		{
			$this->gnowSuggestedOfferRange = ['minVendorAmount' => $this->minVendorAmount, 'maxVendorAmount' => $this->maxVendorAmount];
		}

		##Calculate minTotalAmount to display in range
		$minQuote = clone $this;
		$maxQuote = clone $this;

//		$minQuote->vendorAmount	 = $this->minVendorAmount;
//		$minQuote->getGNowRockBottomPrice();
		$minQuote->baseAmount = $this->minBaseAmount;
		$minQuote->calculateTotal();
		$this->minTotalAmount = $minQuote->totalAmount;

		##Calculate maxTotalAmount to display in range
//		$maxQuote->vendorAmount	 = $this->maxVendorAmount;
//		$maxQuote->getGNowRockBottomPrice();
		$maxQuote->baseAmount = $this->maxBaseAmount;
		$maxQuote->calculateTotal();
		$this->maxTotalAmount = $maxQuote->totalAmount;

		skipSurge:
		$this->calculateTotal();
		$this->updateCounter($updateCounter);
		Logger::endProfile($key);
	}

	public function applyAddon($addonId)
	{
		$this->addonCharge = Addons::getApplicableCharge($addonId, $this->baseAmount);
		$this->calculateTotal();
	}

	public function updateCounter($update = false)
	{
		if (!$update)
		{
			return;
		}

		if (in_array($this->surgeFactorUsed, [1, 3]))
		{
			$this->srgDDBP->refModel->dprApplied->updateManualQuoteCounter();
		}

		if (in_array($this->surgeFactorUsed, [2, 3]))
		{
			$this->srgDDBP->refModel->dprApplied->updateQuoteCounter();
		}
	}

	public function addMarkUpServiceClass()
	{
		if ($this->regularBaseAmount != '')
		{
			$baseFareMarkup		  = ServiceClass::getMarkUp($this->quoteModel->cabType, $this->regularBaseAmount);
			$this->rockBaseAmount = $this->regularBaseAmount + $baseFareMarkup;
			$vendorFareMarkup	  = ServiceClass::getMarkUp($this->quoteModel->cabType, $this->vendorBaseFare);
			$this->vendorBaseFare = $this->vendorBaseFare + ($vendorFareMarkup * 0.5);
		}
	}

	public function getDiscount($value)
	{
		return $this->baseAmount - $this->discount;
	}

	public function getGrossBaseAmount()
	{
		return $this->baseAmount + $this->additionalCharge + $this->addonCharge;
	}

	public function getNetBaseAmount()
	{
		return $this->getGrossBaseAmount() - $this->discount;
	}

	public function calculateTotal()
	{
		$this->calculateTax();
		$this->totalAmount = $this->getNetBaseAmount() + $this->gst + $this->getAllowanceAndTaxes();
	}

	public function getRockBottomPrice()
	{
		$rockMargin			  = Yii::app()->params['rockBottomMargin'];
		$this->rockBaseAmount = round($this->vendorBaseFare * (1 + $rockMargin / 100));
		if ($this->partnerFixedAmount)
		{
			$this->rockBaseAmount = $this->fixedBaseAmount;
		}
		$this->rockBottomAmount = $this->rockBaseAmount + $this->getAllowanceAndTaxes();
	}

	public function getGNowRockBottomMargin()
	{
		$vendorAmount = $this->vendorAmount;

		switch ($vendorAmount)
		{
			case ($vendorAmount < 0):
				$margin = 0;
				break;
			case ($vendorAmount < 1500):
				$margin = max(99, $vendorAmount * 0.08);
				break;
			default:
				$margin = max(199, $vendorAmount * 0.08);
				break;
		}
		return $margin;
	}

	public function getIntraRockBottomMargin()
	{
		$vendorAmount = $this->vendorAmount;
		$margin		  = $vendorAmount * 0.07;
		return $margin;
	}

	public function getGNowRockBottomPrice()
	{
		$vendorAmount			= $this->vendorAmount;
		//$rockMargin = $this->getGNowRockBottomMargin();
		$rockMargin				= ($this->quoteModel->tripType == 14) ? ($this->getIntraRockBottomMargin()) : ($this->getGNowRockBottomMargin());
		$this->rockBottomAmount = round($vendorAmount + $rockMargin);
		$this->rockBaseAmount	= $this->rockBottomAmount - $this->getAllowanceAndTaxes();
		if ($this->partnerFixedAmount)
		{
			$this->rockBaseAmount	= $this->fixedBaseAmount;
			$this->rockBottomAmount = $this->rockBaseAmount + $this->getAllowanceAndTaxes() + $rockMargin;
		}
	}

	public function getRockBottomPricePackage()
	{
		$rockMargin				= Yii::app()->params['rockBottomMargin'];
		$this->rockBaseAmount	= round($this->baseAmount * (1 + $rockMargin / 100));
		$this->rockBottomAmount = $this->rockBaseAmount + $this->getAllowanceAndTaxes();
	}

	public function calculateDriverAllowance()
	{
		Logger::setModelCategory(__CLASS__, __FUNCTION__);
		/* @var $routeDuration RouteDuration */
		$routeDuration = $this->quoteModel->routeDuration;

		/* @var $priceRule PriceRule */
		$priceRule = $this->quoteModel->priceRule;

		$routeDuration->calculateNight($this->quoteModel);
		$minDriverAllowance = 0;
		$kmLimit			= $priceRule->prr_driver_allowance_km_limit;
		if ($kmLimit > 0 && !$this->ignoreDayDriverAllowance)
		{
			$kmDays				= $this->quoteModel->routeDistance->tripDistance / $kmLimit;
			$minDriverAllowance = floor($kmDays) * $priceRule->prr_night_driver_allowance;
			if ($priceRule->prr_day_driver_allowance > 0)
			{
				$minDriverAllowance = ceil($kmDays) * $priceRule->prr_day_driver_allowance;
			}
		}

		$days	= $routeDuration->calendarDays;
		$nights = ($this->quoteModel->tripType == 1 && $days >= 1 && $priceRule->prr_day_driver_allowance && !$this->ignoreDayDriverAllowance) ? 0 : $routeDuration->nights;
		$days	= ($this->ignoreDayDriverAllowance) ? 0 : $days;

		$extraNights = 0;
		if ($this->includeNightAllowance)
		{
			if ($days > 0)
			{
				//$days--;
			}
			$extraNights				 = $routeDuration->extraNights;
			$this->isNightPickupIncluded = $routeDuration->extraNightPickupIncluded;
			$this->isNightDropIncluded	 = $routeDuration->extraNightDropIncluded;
		}

		$totalNights				= $nights + $extraNights;
		$this->driverDayAllowance	= $priceRule->prr_day_driver_allowance * $days;
		$this->driverNightAllowance += $priceRule->prr_night_driver_allowance * ($totalNights);
		Logger::trace("calcnight:" . $nights . "extranightcount: " . $extraNights);
		Logger::trace("driverAllowance " . $this->driverNightAllowance . " driverDayAllowance " . $this->driverDayAllowance . " days " . $days . " priceRule " . $priceRule->prr_id . " totalNight " . $totalNights . "night included " . $routeDuration->extraNightPickupIncluded . "mindriverAllowance:" . $minDriverAllowance);
		$this->driverAllowance		= ROUND(max([$minDriverAllowance, $this->driverDayAllowance + $this->driverNightAllowance]));
		Logger::unsetModelCategory(__CLASS__, __FUNCTION__);
	}

	public function getAllowanceAndTaxes()
	{
		$this->parkingAmount   = $this->parkingAmount | 0;
		$this->airportEntryFee = $this->airportEntryFee | 0;
		$allowances			   = $this->driverAllowance + $this->stateTax + $this->tollTaxAmount + $this->parkingAmount + $this->airportEntryFee;
		return $allowances;
	}

	public function calculateVendorFare()
	{
		$allowances = $this->getAllowanceAndTaxes();
		if ($this->vendorBaseFare > 0)
		{
			$this->vendorAmount = $this->vendorBaseFare + $allowances;
		}
		else if ($this->vendorAmount > 0)
		{
			$this->vendorBaseFare = $this->vendorAmount - $allowances;
		}
	}

	/** @param Quote $quoteModel */
	public function getMarkup(&$quoteModel)
	{
		if ($this->rateMarkup == 0)
		{
			$defaultMarkup = Yii::app()->params['defMarkupCab'];
			if ($quoteModel->additionalMarkup == true)
			{
				$defaultMarkup = $defaultMarkup + Config::get("mmt.additionalMarkup.value");
			}

			$this->rateMarkup = $defaultMarkup;
		}
		$this->baseAmount = round($this->rockBaseAmount * (1 + $this->rateMarkup / 100));
		$preDataArr		  = [];
		$preData		  = $this->additional_param;
		if ($preData != '')
		{
			$preDataArr = json_decode($preData, true);
		}
		$markupArr				   = [];
		$markupArr['defMarkupCab'] = $this->baseAmount;
		$cpMarkup				   = ChannelPartnerMarkup::model()->calculateMarkup($quoteModel);
		if ($cpMarkup)
		{
			$this->baseAmount	   = $cpMarkup['amount'];
			$markupArr['cpMarkup'] = $this->baseAmount;
		}
		$this->additional_param = json_encode($preDataArr + $markupArr);
	}

	public function applySurge()
	{

		$key						   = "RouteRates::applySurge for CAB {$this->quoteModel->cabType}";
		Logger::beginProfile($key);
		$overrideDDBP				   = 0;
		$overrideDZPP				   = 0;
		$overrideDEBP				   = 0;
		$overrideProfitability		   = 0;
		$overrideDDBPV2				   = 0;
		$overrideDDSBP				   = 0;
		$rockBaseAmount				   = $this->rockBaseAmount;
		$originalRockBaseAmount		   = $this->rockBaseAmount;
		$this->partner_soldout		   = 0;
		$pickupDate					   = $this->quoteModel->pickupDate;
		$this->surgeFactorUsed		   = 0;
		$rateId						   = $this->quoteModel->rateId;
		$days						   = -1;
		$positiveSurgeAmount		   = 0;
		$positiveSurge				   = new Surge();
		$positiveSurge->rockBaseAmount = $rockBaseAmount;
		$positiveSurge->type		   = 0;
		$positiveSurge->factor		   = 1;
		$this->appliedFactors		   = [0];

		$negativeSurge = clone $positiveSurge;

		$this->srgManual = Surge::apply($this->quoteModel, Surge::Type_Manual);
		if ($this->srgManual->isApplied) /// manual calculate
		{
			$priceSurgeModel	   = $this->srgManual->refModel;
			$overrideDDBP		   = $priceSurgeModel->prc_override_ds;
			$overrideDZPP		   = $priceSurgeModel->prc_override_dz;
			$overrideDEBP		   = $priceSurgeModel->prc_override_de;
			$overrideDDBPV2		   = $priceSurgeModel->prc_override_ddv2;
			$overrideDDSBP		   = $priceSurgeModel->prc_override_ddsbp;
			$overrideProfitability = $priceSurgeModel->prc_override_profitability;

			if ($this->srgManual->rockBaseAmount < $this->rockBaseAmount)
			{
				$negativeSurge = $this->srgManual;
			}
			else
			{
				$positiveSurge		 = $this->srgManual;
				$positiveSurgeAmount = $this->srgManual->surgeAmount;
			}
			$this->surgeFactorUsed = Surge::Type_Manual;
			$this->appliedFactors  = [$this->srgManual->type];
			$rockBaseAmount		   = $this->srgManual->rockBaseAmount;
		}


		$pickupTime	   = strtotime($pickupDate);
		$overrideDDSBP = 1;

//		if (!(($pickupTime >= strtotime('2022-12-24') && $pickupTime < strtotime('2022-12-27')) || ($pickupTime >= strtotime('2022-12-31') && $pickupTime < strtotime('2023-01-02'))))
//		{
		$overrideDZPP	= 1;
		$overrideDDBPV2 = 1;
		//}

		$this->srgDZPP			   = Surge::apply($this->quoteModel, Surge::Type_DZPP); //DZPP calculate
		$this->srgDZPP->isOverRide = $overrideDZPP;

		if (!$this->srgDZPP->isApplied || $this->srgDZPP->isOverRide == 1)
		{
			goto skipDZPP;
		}

		$days = Rate::lastRateUpdateDays($this->quoteModel);
		if ($days < 7) // if last rate update >=7 days
		{
			$this->srgDZPP->desc .= "=>[[R<7D]] ";
			goto skipDZPP;
		}
		if ($this->srgDZPP->rockBaseAmount < $negativeSurge->rockBaseAmount)  // if either Manual or DZPP is (negative and applying)
		{
			$this->appliedFactors		   = array_diff($this->appliedFactors, [$negativeSurge->type]);
			$negativeSurge				   = $this->srgDZPP;
			$rockBaseAmount				   = round($positiveSurge->rockBaseAmount * $this->srgDZPP->factor);
			$negativeSurge->rockBaseAmount = $rockBaseAmount;
			array_push($this->appliedFactors, $this->srgDZPP->type);
			$this->surgeFactorUsed		   = Surge::Type_DZPP;
		}
		elseif ($this->srgDZPP->rockBaseAmount > $positiveSurge->rockBaseAmount)
		{
			$this->appliedFactors		   = array_diff($this->appliedFactors, [$positiveSurge->type]);
			$positiveSurge				   = $this->srgDZPP;
			$rockBaseAmount				   = round($negativeSurge->rockBaseAmount * $this->srgDZPP->factor);
			$positiveSurge->rockBaseAmount = $rockBaseAmount;
			$positiveSurgeAmount		   = ($rockBaseAmount - $negativeSurge->rockBaseAmount);
			array_push($this->appliedFactors, $this->srgDZPP->type);
			$this->surgeFactorUsed		   = Surge::Type_DZPP;
		}

		skipDZPP:
		$this->srgDEBP			   = Surge::apply($this->quoteModel, Surge::Type_DEBP); //calculate DEBP
		$this->srgDEBP->isOverRide = $overrideDEBP;

		if (!$this->srgDEBP->isApplied || $this->srgDEBP->isOverRide == 1)
		{
			goto skipDEBP;
		}

		if ($this->srgDEBP->rockBaseAmount < $negativeSurge->rockBaseAmount)  // if either Manual or DZPP is (negative and applying)
		{
			$this->appliedFactors		   = array_diff($this->appliedFactors, [$negativeSurge->type]);
			$negativeSurge				   = $this->srgDEBP;
			$rockBaseAmount				   = round($positiveSurge->rockBaseAmount * $this->srgDEBP->factor);
			$negativeSurge->rockBaseAmount = $rockBaseAmount;
			$this->surgeFactorUsed		   = Surge::Type_DEBP;
			array_push($this->appliedFactors, $this->srgDEBP->type);
		}
		elseif ($this->srgDEBP->rockBaseAmount > $positiveSurge->rockBaseAmount)
		{
			$this->appliedFactors		   = array_diff($this->appliedFactors, [$positiveSurge->type]);
			$positiveSurge				   = $this->srgDEBP;
			$rockBaseAmount				   = round($negativeSurge->rockBaseAmount * $this->srgDEBP->factor);
			$positiveSurge->rockBaseAmount = $rockBaseAmount;
			$positiveSurgeAmount		   = ($rockBaseAmount - $negativeSurge->rockBaseAmount);
			$positiveSurge->surgeAmount	   = $positiveSurgeAmount;
			$this->surgeFactorUsed		   = Surge::Type_DEBP;
			array_push($this->appliedFactors, $this->srgDEBP->type);
		}


		skipDEBP:
		$this->srgDDBPV2			 = Surge::apply($this->quoteModel, Surge::Type_DDBPV2); // calculate DDBPv2
		$this->srgDDBPV2->isOverRide = $overrideDDBPV2;
		$isLiveDDBPV2				 = Config::get('DDBPV2_ISLIVE');

		if ($this->srgDDBPV2->isOverRide == 1 || !$isLiveDDBPV2)
		{
			goto skipDDBPV2;
		}

		if ($this->srgDDBPV2->factor != 1)  // if either Manual or DZPP is (negative and applying)
		{
			if ($positiveSurge->type != Surge::Type_DEBP && $positiveSurge->surgeAmount >= $this->srgDDBPV2->surgeAmount)
			{
				goto skipDDBPV2;
			}

			if ($positiveSurge->type != Surge::Type_DEBP && $positiveSurge->surgeAmount < $this->srgDDBPV2->surgeAmount)
			{
				$this->appliedFactors = array_diff($this->appliedFactors, [$positiveSurge->type]);
				$rockBaseAmount		  = $rockBaseAmount - $positiveSurge->surgeAmount;
				$positiveSurgeAmount  -= $positiveSurge->surgeAmount;
			}

			$rockBaseAmount = round($rockBaseAmount * $this->srgDDBPV2->factor);

			if ($this->srgDDBPV2->surgeAmount > 0)
			{
				$positiveSurgeAmount += round($rockBaseAmount - $rockBaseAmount / $this->srgDDBPV2->factor);
			}
			$this->surgeFactorUsed = Surge::Type_DDBPV2;
			array_push($this->appliedFactors, $this->srgDDBPV2->type);
		}

		$partnerSoldOutParams = Config::get('partnerSoldOut');
		if (!empty($partnerSoldOutParams))
		{
			$result				  = CJSON::decode($partnerSoldOutParams);
			$partnerSoldFactor	  = $result['partnerSoldFactor'];
			$partnerSoldPartnerId = $result['partnerSoldId'];
		}
		else
		{
			$partnerSoldFactor	  = 1.25;
			$partnerSoldPartnerId = "18190,450";
		}
		$this->partner_soldout = (($this->srgDDBPV2->factor >= $partnerSoldFactor) && in_array($this->quoteModel->partnerId, explode(",", $partnerSoldPartnerId))) ? 1 : 0;

		skipDDBPV2:
		$this->srgDURP = Surge::apply($this->quoteModel, Surge::Type_DURP); //calculate DURP

		if ($this->srgDURP->isApplied && $this->srgDURP->isApplicable && $this->srgDURP->surgeAmount > $positiveSurgeAmount)
		{
			$positiveSurge				   = $this->srgDURP;
			$rockBaseAmount				   = $rockBaseAmount + ($this->srgDURP->surgeAmount - $positiveSurgeAmount);
			$positiveSurgeAmount		   = $this->srgDURP->surgeAmount;
			$positiveSurge->surgeAmount	   = $positiveSurgeAmount;
			$positiveSurge->rockBaseAmount = $rockBaseAmount;
			$this->surgeFactorUsed		   = Surge::Type_DURP;
			$this->appliedFactors		   = [$negativeSurge->type, $this->srgDURP->type];
		}

		$this->srgDDBP			   = Surge::apply($this->quoteModel, Surge::Type_DDBP); //calculate DDBPv1
		$this->srgDDBP->isOverRide = $overrideDDBP;

		if (!$this->srgDDBP->isApplied || $this->srgDDBP->isOverRide == 1)
		{
			goto skipDDBP;
		}


		$dynamicSurge	 = $this->srgDDBP->refModel;
		$DDBPSurgeFactor = $dynamicSurge->dprApplied->factor;
		if (!$dynamicSurge->isApplicable() || $DDBPSurgeFactor == 1)
		{
			goto skipDDBP;
		}

		if ($this->srgDDBP->surgeAmount > $positiveSurgeAmount && $DDBPSurgeFactor != 0)
		{
			$positiveSurge				   = $this->srgDDBP;
			$rockBaseAmount				   = round($negativeSurge->rockBaseAmount * $DDBPSurgeFactor);
			$positiveSurgeAmount		   = round($rockBaseAmount - ($rockBaseAmount / $DDBPSurgeFactor));
			$positiveSurge->rockBaseAmount = $rockBaseAmount;
			$positiveSurge->surgeAmount	   = $positiveSurgeAmount;
			$this->surgeFactorUsed		   = Surge::Type_DDBP;
			$this->appliedFactors		   = [$negativeSurge->type, $positiveSurge->type];
		}


		skipDDBP:
		//goto skipDDSBP;
		$this->srgDDSBP				= Surge::apply($this->quoteModel, Surge::Type_DDSBP); //calculate DDSBP
		$this->srgDDSBP->isOverRide = $overrideDDSBP;
		$DDSBPSurgeFactor			= $this->srgDDSBP->factor;
		if (!$this->srgDDSBP->isApplied || $overrideDDSBP == 1 || $DDSBPSurgeFactor == 1)
		{
			goto skipDDSBP;
		}

		$rockBaseAmount					= round($rockBaseAmount * $this->srgDDSBP->factor);
		$this->srgDDSBP->rockBaseAmount = $rockBaseAmount;
		$this->srgDDSBP->surgeAmount	= round($rockBaseAmount - $rockBaseAmount / $this->srgDDSBP->factor);
		array_push($this->appliedFactors, $this->srgDDSBP->type);

		skipDDSBP:
		// below code will not work for Airport Packages
		if (in_array($this->quoteModel->processedTripType, [4, 12]))
		{
			goto skipDTBP;
		}

		$this->srgDTBP = Surge::apply($this->quoteModel, Surge::Type_DTBP); //Calculate DTBP

		if (!$this->partnerFixedAmount && $this->srgDTBP->isApplied)
		{
			//DTBP applies on top of the regular price and the "net raised amount by DTBP is always added"
			$rockBaseAmount				   = round($rockBaseAmount * $this->srgDTBP->factor);
			$this->srgDTBP->rockBaseAmount = $rockBaseAmount;
			$this->srgDTBP->surgeAmount	   = round($rockBaseAmount - $rockBaseAmount / $this->srgDTBP->factor);
			array_push($this->appliedFactors, $this->srgDTBP->type);
		}

		skipDTBP:
		$this->rockBaseAmount = $rockBaseAmount;
		if ($originalRockBaseAmount <> $rockBaseAmount && $originalRockBaseAmount > 0)
		{
			$diff							= $rockBaseAmount - $originalRockBaseAmount;
			$this->differentiateSurgeAmount = $diff;
			if ($diff > 0) // if the amount was surged up
			{
				$this->vendorBaseFare += round($diff * 0.4); //DSA changed from .7 to .3; Its OK for 30% of the raised price to go to VA directly at first. Giving 70% upfront is too much
			}
			else // if diff is zero or negative
			{
				$this->vendorBaseFare += $diff;
			}
		}

		if ($this->srgManual->isApplied && $priceSurgeModel->prc_is_available == 0)
		{
			$this->cabAvailability		 = $priceSurgeModel->prc_is_available;
			$this->quoteModel->success	 = false;
			$this->quoteModel->errorCode = 101;
			$this->quoteModel->errorText = "Cab type temporarily not available";
		}

		$isAdditionalParam = Config::get('Surge.isAdditionalParam');
		if ($isAdditionalParam)
		{
			$surgeSummary			= $this->getSummary();
			$this->additional_param = json_encode(array(
				'regular'				 => $originalRockBaseAmount,
				"srgManual"				 => $this->srgManual,
				"srgDZPP"				 => $this->srgDZPP,
				"srgDDBP"				 => $this->srgDDBP,
				"srgDDBPV2"				 => $this->srgDDBPV2,
				"srgDURP"				 => $this->srgDURP,
				"srgDEBP"				 => $this->srgDEBP,
				"srgDTBP"				 => $this->srgDTBP,
				"srgDDSBP"				 => $this->srgDDSBP,
				"surgeFactorUsed"		 => $this->appliedFactors,
				"surgeFactorDescription" => $surgeSummary,
				"rateUpdateDays"		 => $days,
				"rateId"				 => $rateId,
				"partnerSoldout"		 => $this->partner_soldout
			));
			Logger::trace("additional_param: " . $this->additional_param);
		}

		Logger::endProfile($key);
	}

	public function applySurgeOld1()
	{
		$key				   = "RouteRates::applySurge for CAB {$this->quoteModel->cabType}";
		Logger::beginProfile($key);
		$overrideDDBP		   = 0;
		$overrideDZPP		   = 0;
		$overrideDEBP		   = 0;
		$overrideProfitability = 0;
		$overrideDDBPV2		   = 0;
		$rockBaseAmount		   = $this->rockBaseAmount;
		$regularSurgeAmount	   = $this->rockBaseAmount;
		$this->srgManual	   = Surge::apply($this->quoteModel, Surge::Type_Manual);
		$this->surgeFactorUsed = 0;
		$surgeDesc			   = "R";
		$days				   = -1;
		if ($this->srgManual->isApplied) /// manual calculate
		{
			$priceSurgeModel	   = $this->srgManual->refModel;
			$overrideDDBP		   = $priceSurgeModel->prc_override_ds;
			$overrideDZPP		   = $priceSurgeModel->prc_override_dz;
			$overrideDEBP		   = $priceSurgeModel->prc_override_de;
			$overrideDDBPV2		   = $priceSurgeModel->prc_override_ddv2;
			$overrideProfitability = $priceSurgeModel->prc_override_profitability;
		}

		$this->srgDZPP			   = Surge::apply($this->quoteModel, Surge::Type_DZPP);
		$this->srgDZPP->isOverRide = $overrideDZPP;

		if ((($this->srgDZPP->isApplied && $this->srgDZPP->rockBaseAmount < $this->rockBaseAmount) || ($this->srgManual->isApplied && $this->srgManual->rockBaseAmount < $this->rockBaseAmount )) && $overrideDZPP != 1)  // if either is negative and either are applying
		{
			$days = Rate::lastRateUpdateDays($this->quoteModel);
			if ($days > 14) // if last rate update >14 days
			{
				if ($this->srgManual->isApplied && $this->srgDZPP->isApplied) // if both are applied
				{
					$rockBaseAmount		   = min($this->srgDZPP->rockBaseAmount, $this->srgManual->rockBaseAmount);
					$this->surgeFactorUsed = $rockBaseAmount == $this->srgDZPP->rockBaseAmount ? 7 : 1;
					$this->rockBaseAmount  = $rockBaseAmount; // master global rockbase is overwritten now
					$surgeDesc			   .= $this->surgeFactorUsed == 7 ? "=>DZ[-]" : "=>M[-]";
				}
				else if ($this->srgManual->isApplied && !$this->srgDZPP->isApplied && $this->srgManual->rockBaseAmount < $this->rockBaseAmount)
				{
					$rockBaseAmount		   = $this->srgManual->rockBaseAmount;
					$this->surgeFactorUsed = 1;
					$this->rockBaseAmount  = $rockBaseAmount;
					$surgeDesc			   .= "=>M[-]";
				}
				else if (!$this->srgManual->isApplied && $this->srgDZPP->isApplied && $this->srgDZPP->rockBaseAmount < $this->rockBaseAmount)
				{
					$rockBaseAmount		   = $this->srgDZPP->rockBaseAmount;
					$this->surgeFactorUsed = 7;
					$this->rockBaseAmount  = $rockBaseAmount;
					$surgeDesc			   .= "=>DZ[-]";
				}
			}
			else
			{
				$surgeDesc .= "=>[[R<14D]]";
			}
		}
		else
		{
			if ($this->srgManual->isApplied)
			{
				$rockBaseAmount		   = $this->srgManual->rockBaseAmount;
				$surgeDesc			   .= "=>M[+]";
				$this->surgeFactorUsed = 1;
			}

			if ($this->srgDZPP->isApplied && $this->srgDZPP->rockBaseAmount > $rockBaseAmount && $this->srgDZPP->isApplicable && $overrideDZPP != 1) /// dzpp is applied and is increasing the value then do it
			{
				$rockBaseAmount		   = $this->srgDZPP->rockBaseAmount;
				$surgeDesc			   .= "=>DZ[+]";
				$this->surgeFactorUsed = 7;
			}
		}

		$this->srgDDBP			   = Surge::apply($this->quoteModel, Surge::Type_DDBP);
		$this->srgDDBP->isOverRide = $overrideDDBP;

		$dynamicSurge	 = $this->srgDDBP->refModel;
		$DDBPSurgeFactor = $dynamicSurge->dprApplied->factor;
		if ($this->srgDDBP->isApplied && $this->srgDDBP->rockBaseAmount > $rockBaseAmount && $overrideDDBP != 1 && $dynamicSurge->isApplicable() && $DDBPSurgeFactor != 1
		)
		{
			$this->surgeFactorUsed = 2;
			$rockBaseAmount		   = $this->srgDDBP->rockBaseAmount;
			$surgeDesc			   .= "=>DD";
		}


		$this->srgDURP = Surge::apply($this->quoteModel, Surge::Type_DURP);

		if ($this->srgDURP->isApplied && $this->srgDURP->isApplicable && $this->srgDURP->rockBaseAmount > $rockBaseAmount)
		{
			$rockBaseAmount		   = $this->srgDURP->rockBaseAmount;
			$this->surgeFactorUsed = 8;
			$surgeDesc			   .= "=>DUR";
		}

		$this->srgDEBP			   = Surge::apply($this->quoteModel, Surge::Type_DEBP);
		$this->srgDEBP->isOverRide = $overrideDEBP;

		if ($this->srgDEBP->isApplied && $this->srgDEBP->rockBaseAmount > $rockBaseAmount && $this->srgDEBP->isApplicable && $overrideDEBP != 1)
		{
			$rockBaseAmount		   = $this->srgDEBP->rockBaseAmount;
			$this->surgeFactorUsed = 9;
			$surgeDesc			   .= "=>DE";
		}

		$this->srgDDBPV2			 = Surge::apply($this->quoteModel, Surge::Type_DDBPV2);
		$this->srgDDBPV2->isOverRide = $overrideDDBPV2;
		if ($this->srgDDBPV2->isApplied && $this->srgDDBPV2->rockBaseAmount > $rockBaseAmount && $this->srgDDBPV2->isApplicable && !in_array($this->quoteModel->processedTripType, array('4', '12')) && $overrideDDBPV2 != 1)
		{
			$isLiveDDBPV2 = Config::get('DDBPV2_ISLIVE');
			if ($isLiveDDBPV2)
			{
				$rockBaseAmount		   = $this->srgDDBPV2->rockBaseAmount;
				$this->surgeFactorUsed = 10;
				$surgeDesc			   .= "=>DDv2";
			}
		}

		// below code will not work for Airport Packages
		if ($this->quoteModel->processedTripType != 12)
		{
			$this->srgDTBP = Surge::apply($this->quoteModel, Surge::Type_DTBP);

			if (!$this->partnerFixedAmount && $this->srgDTBP->isApplied)
			{
				//DTBP applies on top of the regular price and the "net raised amount by DTBP is always added"
				$this->rockBaseAmount = $rockBaseAmount + ($this->srgDTBP->rockBaseAmount - $this->rockBaseAmount);
			}
			else
			{
				$this->rockBaseAmount = $rockBaseAmount;
			}
		}
		else
		{
			$this->rockBaseAmount = $rockBaseAmount;
		}

		if ($this->regularBaseAmount <> $this->rockBaseAmount)
		{
			$diff							= $this->rockBaseAmount - $this->regularBaseAmount;
			$this->differentiateSurgeAmount = $diff;
			if ($diff > 0)
			{
				$this->vendorBaseFare += round($diff * 0.3); //DSA changed from .7 to .3; Its OK for 30% of the raised price to go to VA directly at first. Giving 70% upfront is too much
			}
			else
			{
				$this->vendorBaseFare += $diff;
			}
		}

		if ($this->srgManual->isApplied && $priceSurgeModel->prc_is_available == 0)
		{
			$this->cabAvailability		 = $priceSurgeModel->prc_is_available;
			$this->quoteModel->success	 = false;
			$this->quoteModel->errorCode = 101;
			$this->quoteModel->errorText = "Cab type temporarily not available";
		}

		$isAdditionalParam = Config::get('Surge.isAdditionalParam');
		if ($isAdditionalParam)
		{

			$this->additional_param = json_encode(array(
				'regular'				 => $regularSurgeAmount,
				"srgManual"				 => $this->srgManual,
				"srgDZPP"				 => $this->srgDZPP,
				"srgDDBP"				 => $this->srgDDBP,
				"srgDDBPV2"				 => $this->srgDDBPV2,
				"srgDURP"				 => $this->srgDURP,
				"srgDEBP"				 => $this->srgDEBP,
				"srgDTBP"				 => $this->srgDTBP,
				"surgeFactorUsed"		 => $this->surgeFactorUsed,
				"surgeFactorDescription" => $surgeDesc,
				"rateUpdateDays"		 => $days
			));
			Logger::trace("additional_param: " . $this->additional_param);
		}

		Logger::endProfile($key);
	}

	public function getSummary()
	{
		$totalFactors = [1, 2, 3, 4, 5, 6, 7, 8, 9];

		$appliedDesc = [];

		foreach ($this->appliedFactors as $type)
		{
			$srgModel = $this->getSurgeByType($type);
			if ($srgModel)
			{
				$appliedDesc[] = $srgModel->getSummary();
			}
		}

		$otherFactors = array_diff($totalFactors, $this->appliedFactors);
		$otherDesc	  = [];
		foreach ($otherFactors as $type)
		{
			$srgModel = $this->getSurgeByType($type);
			if (!$srgModel)
			{
				continue;
			}

			$otherDesc[] = $srgModel->getSummary();
		}

		$appliedSummary = "Applied: " . implode(", ", array_filter($appliedDesc));
		$otherSummary	= "Not Applied: " . implode(", ", array_filter($otherDesc));
		$summary		= "{$appliedSummary} | {$otherSummary}";
		return $summary;
	}

	/**
	 *  @var int $type 
	 * @return Surge
	 * 
	 */
	public function getSurgeByType($type)
	{
		$model = false;
		switch ($type)
		{
			case Surge::Type_DTBP:
				$model = $this->srgDTBP;
				break;
			case Surge::Type_DDBP:
				$model = $this->srgDDBP;
				break;
			case Surge::Type_DZPP:
				$model = $this->srgDZPP;
				break;
			case Surge::Type_DEBP:
				$model = $this->srgDEBP;
				break;
			case Surge::Type_DURP:
				$model = $this->srgDURP;
				break;
			case Surge::Type_DDBPV2:
				$model = $this->srgDDBPV2;
				break;
			case Surge::Type_DDSBP:
				$model = $this->srgDDSBP;
				break;
			case Surge::Type_Manual:
				$model = $this->srgManual;
				break;
			default:
				$model = false;
		}

		return $model;
	}

	public function applySurgeOld()
	{
		$overrideDDBP	 = 0;
		$manualSurge	 = 0;
		$this->srgManual = Surge::apply($this->quoteModel, Surge::Type_Manual);
		if ($this->srgManual->isApplied && $this->srgManual->surgeAmount < 0)
		{
			$manualSurge		  = 1;
			$this->rockBaseAmount = $this->srgManual->rockBaseAmount;
		}
		/* @var $priceSurgeModel PriceSurge */
		if ($this->srgManual->isApplied)
		{
			$priceSurgeModel = $this->srgManual->refModel;
			$overrideDDBP	 = $priceSurgeModel->prc_override_ds;
		}
		Logger::profile("Rates calculate after Manual");

		$this->srgDDBP = Surge::apply($this->quoteModel, Surge::Type_DDBP);
		Logger::profile("Rates calculate after DDBP");

		/* @var $dynamicSurge DynamicSurge */
		$dynamicSurge = $this->srgDDBP->refModel;

		if ($this->srgDDBP->isApplied && ($this->srgDDBP->rockBaseAmount > $this->srgManual->rockBaseAmount && $this->srgDDBP->rockBaseAmount > $this->rockBaseAmount) && $overrideDDBP != 1 && $dynamicSurge->isApplicable())
		{
			$this->surgeFactorUsed = 2 + $manualSurge;
			$this->rockBaseAmount  = $this->srgDDBP->rockBaseAmount;
		}
		else if ($this->srgManual->isApplied && $this->rockBaseAmount < $this->srgManual->rockBaseAmount)
		{
			$this->surgeFactorUsed = 1;
			$this->rockBaseAmount  = $this->srgManual->rockBaseAmount;
		}

		$this->srgDTBP = Surge::apply($this->quoteModel, Surge::Type_DTBP);
		Logger::profile("Rates calculate after DTBP");
		if (!$this->partnerFixedAmount && $this->srgDTBP->isApplied)
		{
			$this->surgeFactorUsed = 4;
			$this->rockBaseAmount  = $this->srgDTBP->rockBaseAmount;
		}

		if ($this->regularBaseAmount <> $this->rockBaseAmount)
		{
			$diff							= $this->rockBaseAmount - $this->regularBaseAmount;
			$this->differentiateSurgeAmount = $diff;
			if ($diff > 0)
			{
				$this->vendorBaseFare += round($diff * 0.4);
			}
			else
			{
				$this->vendorBaseFare += $diff;
			}
		}

		if ($this->srgManual->isApplied && $priceSurgeModel->prc_is_available == 0)
		{
			$this->cabAvailability		 = $priceSurgeModel->prc_is_available;
			$this->quoteModel->success	 = false;
			$this->quoteModel->errorCode = 101;
			$this->quoteModel->errorText = "Cab type temporarily not available";
		}
	}

	public function calculateTax()
	{
		$this->airportEntryFee = $this->airportEntryFee | 0;
		$qModel				   = $this->quoteModel;
		$staxrate			   = BookingInvoice::getGstTaxRate($qModel->partnerId, $qModel->tripType);
		$checkNewGstPickupTime = Booking::model()->checkNewGstPickupTime($qModel->routes[0]->brt_pickup_datetime);
		if ($checkNewGstPickupTime)
		{
			/* by ankesh */
			$this->gst = round((($this->getNetBaseAmount() + $this->tollTaxAmount + $this->stateTax + $this->driverAllowance + $this->parkingAmount + $this->airportEntryFee) * $staxrate * 0.01), 0);
		}
		else
		{
			$this->gst = round((($this->getNetBaseAmount() + $this->driverAllowance) * $staxrate * 0.01), 0);
		}
	}

	public function calculateSellBaseFromTotal($totalAmount, $partnerId, $tripType)
	{
		$staxrate			   = BookingInvoice::getGstTaxRate($partnerId, $tripType);
		$gst				   = $totalAmount - round(( $totalAmount / ( 1 + (0.01 * $staxrate))), 0);
		$totWithoutTax		   = $totalAmount - $this->getAllowanceAndTaxes() - $gst;
		//$staxrate		 = Filter::getServiceTaxRate();
		$this->fixedBaseAmount = $totWithoutTax;
	}

	/**
	 * 
	 * @return gstSlab
	 */
	public static function getGSTRate($partnerId, $tripType)
	{
		$gstRate = Filter::getServiceTaxRate();
		if ($partnerId != null && $partnerId != 1249)
		{
			$partnerSetting = PartnerSettings::getValueById($partnerId);
			$data			= json_decode($partnerSetting['pts_gst_rate'], true);

			$partnerData = $data[$tripType];
			$gstRate	 = ($partnerData === null) ? $gstRate : $partnerData;
		}
		return $gstRate;
	}

	/* public function addMarkupCostPerKm($costPerKM)
	  {

	  } */
}

class Surge
{

	CONST Type_Manual		   = 1;
	CONST Type_DDBP		   = 2;
	CONST Type_DTBP		   = 3;
	CONST Type_Profitability = 4;
	CONST Type_DDBPV2		   = 5;
	CONST Type_DDSBP		   = 6;
	CONST Type_DZPP		   = 7;
	CONST Type_DURP		   = 8;
	CONST Type_DEBP		   = 9;

	public $factor	   = 1;
	public $rockBaseAmount, $surgeAmount, $refId, $type, $refModel, $desc;
	public $isApplied  = false;
	public $isOverRide = 0;

	/**
	 *  @return Surge 
	 */
	public static function apply(Quote &$quoteModel, $type = self::Type_Manual)
	{
		$model		 = new Surge();
		$model->type = $type;

		switch ($type)
		{
			case self::Type_DTBP:
				$model->applyDTBP($quoteModel);
				break;
			case self::Type_DDBP:
				$model->applyDDBP($quoteModel);
				break;
			case self::Type_Profitability:
				$model->applyProfitability($quoteModel);
				break;
			case self::Type_DZPP:
				$model->applyDZPP($quoteModel);
				break;
			case self::Type_DEBP:
				$model->applyDEBP($quoteModel);
				break;
			case self::Type_DURP:
				$model->applyDURP($quoteModel);
				break;
			case self::Type_DDBPV2:
				$model->applyDDBPV2($quoteModel);
				break;
			case self::Type_DDSBP:
				$model->applyDDSBP($quoteModel);
				break;
			case self::Type_Manual:
			default:
				$model->applyManual($quoteModel);
				break;
		}
		return $model;
	}

	public function applyManual(Quote &$quoteModel)
	{
		$surge = PriceSurge::model()->calculate($quoteModel);
		if ($surge)
		{
			$rockBaseAmount							 = $quoteModel->routeRates->rockBaseAmount;
			$quoteModel->routeRates->cabAvailability = $surge['prc_is_available'];
			$this->rockBaseAmount					 = $surge['amount'];
			$this->surgeAmount						 = round($surge['value']);
			$this->refId							 = $surge['prc_id'];
			$this->factor							 = round($this->rockBaseAmount / $rockBaseAmount, 2);
			$this->refModel							 = PriceSurge::model()->findByPk($this->refId);
			$this->isApplied						 = $this->factor > 0 && $this->factor != 1;
			if ($quoteModel->partnerId == 1249 || $quoteModel->partnerId == null)
			{
				$quoteModel->gozoNow = ($quoteModel->gozoNow || ($surge['prc_is_gnow_applicable'] == 1));
			}
			$this->desc = "M";
		}
	}

	public function applyProfitability(Quote &$quoteModel)
	{
		$isPBSurgeApplicable = ProfitabilitySurge::isApplicable();
		if ($isPBSurgeApplicable)
		{
			$surge1 = ProfitabilitySurge::fetchData($quoteModel->servingRoute['start'], $quoteModel->servingRoute['end'], $quoteModel->routeRates->rockBaseAmount, $quoteModel->cabType, $quoteModel->tripType);
			$surge2 = ProfitabilitySurge::fetchData($quoteModel->servingRoute['pickup'], $quoteModel->servingRoute['drop'], $quoteModel->routeRates->rockBaseAmount, $quoteModel->cabType, $quoteModel->tripType);

			if ($surge1 || $surge2)
			{
				if ($surge1)
				{
					$surge = $surge1;
				}
				else if ($surge2 && ( $surge2['totamount'] > $surge1['totamount']))
				{
					$surge = $surge2;
				}
				$this->rockBaseAmount = round($surge['totamount']);
				$this->surgeAmount	  = $this->rockBaseAmount - $quoteModel->routeRates->rockBaseAmount;
				$this->refId		  = $surge['prs_id'];
				$this->factor		  = round((100 + $surge['prs_surge']) / 100, 2);
				$this->isApplied	  = $this->factor > 0 && $this->factor != 1;
				$this->isApplicable	  = $isPBSurgeApplicable;
			}
		}
		else
		{
			$this->surgeAmount	= 0;
			$this->factor		= 1.0;
			$this->isApplied	= false;
			$this->isApplicable = $isPBSurgeApplicable;
		}
	}

	public function applyDDBP(Quote &$quoteModel)
	{
		$routeRates	  = $quoteModel->routeRates;
		$route		  = $quoteModel->routes[0];
		$dynamicSurge = new DynamicSurge();
		$dynamicSurge->calculate($routeRates->rockBaseAmount, $route->brt_from_city_id, $route->brt_to_city_id, $quoteModel->pickupDate, $quoteModel->tripType);

		$this->rockBaseAmount = $dynamicSurge->dprApplied->baseFare;
		$this->surgeAmount	  = $dynamicSurge->dprApplied->surgeValue;
		$this->refId		  = $dynamicSurge->dprApplied->bucketRow['dps_id'];
		$this->refModel		  = $dynamicSurge;
		$this->factor		  = $dynamicSurge->dprApplied->factor;
		$this->isApplied	  = ($this->factor > 0 && $this->factor != 1);

		$rockBaseAmount = $quoteModel->routeRates->rockBaseAmount;
		$this->desc		= "DD1";
	}

	public function applyDTBP(Quote &$quoteModel)
	{
		$route			 = $quoteModel->routes[0];
		$pickupTimeStamp = strtotime($route->brt_pickup_datetime);
		$pickupTimeInHrs = date('H', $pickupTimeStamp);
		$pickupTimeInMin = date('i', $pickupTimeStamp);
		$pickupTime		 = $pickupTimeInMin > 0 ? ($pickupTimeInHrs + 1) : $pickupTimeInHrs;

		$createDate		 = Filter::getDBDateTime();
		$createTimeStamp = strtotime($createDate);

		$workingMinutes = Filter::CalcWorkingMinutes($createDate, $quoteModel->pickupDate);

		/*
		  DTBP new rule (10pm -6am) and working hours less then 2 hours for IBIBO only
		  New rule apply if sameday booking after 9pm  creation and next day before 6 am we will add 4% surge on baseamount
		 */
		$resGoldenMarkup = GoldenMarkup::model()->fetchData($quoteModel->servingRoute['start'], $quoteModel->servingRoute['end'], $route->brt_pickup_datetime, $quoteModel->routeRates->rockBaseAmount, $quoteModel->cabType, $quoteModel->tripType);
		if ($resGoldenMarkup)
		{
			$this->rockBaseAmount = round($resGoldenMarkup['totamount']);
			$this->surgeAmount	  = $this->rockBaseAmount - $quoteModel->routeRates->rockBaseAmount;
			$this->refId		  = $resGoldenMarkup['glm_id'];
			$this->factor		  = round((100 + $resGoldenMarkup['glm_markup_value']) / 100, 2);
			$this->refModel		  = GoldenMarkup::model()->findByPk($resGoldenMarkup['glm_id']);
			$this->isApplied	  = ($this->factor > 0 && $this->factor != 1);
		}

		$mmtFactor = 1;

		//New rule apply if mmt booking pickup is after 60 days then we will add 10% markup 
		$extraMarkupData = Config::get('booking.mmt.extraMarkup');
		$result			 = CJSON::decode($extraMarkupData, true);

		if ($quoteModel->partnerId == 18190 && $result['isEnable'] == 1)
		{
			$effectiveMaxTime = $result['effectiveMaxTime'];
			$maxTimestamp	  = strtotime("+{$effectiveMaxTime} minute");
			$pickupTimestamp  = strtotime($quoteModel->pickupDate);

			if ($pickupTimestamp > $maxTimestamp)
			{
				$mmtFactor = $result['percentage'];
			}
		}


		$offPickupTime = strtotime(date('Y-m-d 10:00:00', strtotime("+1 day", $createTimeStamp)));
		if (date('H', $createTimeStamp) < 7)
		{
			$offPickupTime = strtotime(date('Y-m-d 10:00:00', $createTimeStamp));
		}

		$isCreatedOffTime = (date('H', $createTimeStamp) < 7 || date('H', $createTimeStamp) >= 21);

		if ($quoteModel->partnerId == 18190 && $isCreatedOffTime && $pickupTimeStamp < $offPickupTime)
		{
			$mmtFactor = 10;
		}

		if (($pickupTime >= 23 || $pickupTime <= 6 ) && ($workingMinutes <= 90) && $quoteModel->partnerId == 18190)
		{
			$mmtFactor = 15;
		}
		if ($mmtFactor != 1)
		{
			$glmFactor = 1;
			if ($this->factor <> 0)
			{
				$glmFactor = $this->factor;
			}
			$factor				  = (100 + $mmtFactor) / 100;
			$baseAmount			  = max($quoteModel->routeRates->rockBaseAmount, $this->rockBaseAmount);
			$amount				  = round($baseAmount * $factor);
			$this->rockBaseAmount = $amount;
			$this->surgeAmount	  = $this->rockBaseAmount - $quoteModel->routeRates->rockBaseAmount;
			$this->factor		  = round($factor * $glmFactor, 2);
			$this->isApplied	  = ($this->factor > 0 && $this->factor != 1);
		}

		$rockBaseAmount = $quoteModel->routeRates->rockBaseAmount;
		$this->desc		= "DT";
	}

	public function applyDZPP(Quote &$quoteModel)
	{
		$isDZPPSurgeApplicable = Config::get('Surge.DZPP');
		$flag				   = 0;
		if ($isDZPPSurgeApplicable)
		{
			$row = DynamicZoneSurge::getDZPP($quoteModel);

			if ($row && $row['dzs_dzpp'] != 1)
			{
				$rockBaseAmount = $quoteModel->routeRates->rockBaseAmount;
				$factor			= min(1.3, $row['dzs_dzpp']);
				if ($row['dzs_rate_update_days'] != null && ($row['dzs_rate_update_days'] > 30 && $row ['dzs_rate_update_days'] <= 60) && $factor > 1)
				{
					$factor = round(($factor - 1) * 0.66 + 1, 2);
				}
				else if ($row['dzs_rate_update_days'] != null && $row ['dzs_rate_update_days'] <= 30 && $factor > 1)
				{
					$factor = round(($factor - 1) * 0.33 + 1, 2);
				}
				$factor				  = max($factor, 0.9);
				$amount				  = round($rockBaseAmount * $factor);
				$this->rockBaseAmount = $amount;
				$this->surgeAmount	  = $this->rockBaseAmount - $quoteModel->routeRates->rockBaseAmount;
				$this->refId		  = $row['dzs_id'];
				$this->factor		  = $factor;
				$this->isApplied	  = $this->factor > 0 && $this->factor != 1;
				$this->isApplicable	  = true;
				$this->surgeDesc	  = $row['surgeDesc'] != null ? $row['surgeDesc'] : "90 days dzpp surge";
				$flag				  = 1;

				$this->desc = "DZ";
			}
		}
		if ($flag == 0)
		{
			$this->surgeAmount	= 0;
			$this->factor		= 1.0;
			$this->isApplied	= false;
			$this->isApplicable = false;
			$this->surgeDesc	= null;
		}
	}

	public function applyDEBP(Quote &$quoteModel)
	{
		$isDEBPSurgeApplicable = Config::get('Surge.DEBP');
		$flag				   = 0;
		if ($isDEBPSurgeApplicable)
		{
			$row = CalendarEvent::getDEBP($quoteModel);
			Logger::trace("getDEBP" . json_encode($row));
			if ($row && $row['factor'] != 1)
			{
				$rockBaseAmount		  = $quoteModel->routeRates->rockBaseAmount;
				$factor				  = $row['factor'];
				$amount				  = round($rockBaseAmount * $factor);
				$this->rockBaseAmount = $amount;
				$this->surgeAmount	  = $this->rockBaseAmount - $rockBaseAmount;
				$this->refId		  = $row['id'];
				$this->factor		  = $factor;
				$this->isApplied	  = $this->factor > 0 && $this->factor != 1;
				$this->isApplicable	  = true;
				$flag				  = 1;

				$this->desc = "DE";
			}
		}
		if ($flag == 0)
		{
			$this->surgeAmount	= 0;
			$this->factor		= 1.0;
			$this->isApplied	= false;
			$this->isApplicable = false;
		}
	}

	public function applyDURP(Quote &$quoteModel)
	{
		$route				   = $quoteModel->routes[0];
		$zones				   = ZoneCities::getZonesByCity($route->brt_from_city_id);
		$count				   = Vendors::getHomeZonesCount($zones);
		$minCount			   = in_array($quoteModel->tripType, [2, 3, 9, 10, 11]) ? 2 : 4;
		$flag				   = 0;
		$isDURPSurgeApplicable = Config::get('Surge.DURP');
		if (!$quoteModel->routeRates->isTollIncluded && $count < $minCount && $isDURPSurgeApplicable)
		{
			$checkRoute = ServedBookings::model()->find('seb_from_city_id=:fromcity AND seb_to_city_id=:tocity', ['fromcity' => $route->brt_from_city_id, 'tocity' => $route->brt_to_city_id]);
			if ($checkRoute == '' || $checkRoute == null)
			{
				$rockBaseAmount		  = $quoteModel->routeRates->rockBaseAmount;
				$this->factor		  = ($quoteModel->partnerId == 18190) ? 1.20 : 1.15;
				$flag				  = 1;
				$this->rockBaseAmount = round($rockBaseAmount * $this->factor);
				$this->surgeAmount	  = $this->rockBaseAmount - $quoteModel->routeRates->rockBaseAmount;
				$this->refId		  = 1;
				$this->isApplied	  = true;
				$this->isApplicable	  = true;

				$this->desc = "DU";
			}
		}
		if ($flag == 0)
		{
			$this->surgeAmount	= 0;
			$this->factor		= 1.0;
			$this->isApplied	= false;
			$this->isApplicable = false;
			$this->surgeDesc	= null;
		}
	}

	public function applyDURPV2(Quote &$quoteModel)
	{
		$isDURPSurgeApplicable = Config::get('Surge.DURP');
		$flag				   = 0;
		if ($isDURPSurgeApplicable)
		{
			$row = DynamicUncommonRoute::getDURP($quoteModel);
			if ($row && $row['dur_surge_factor'] != 1)
			{
				$rockBaseAmount		  = $quoteModel->routeRates->rockBaseAmount;
				$factor				  = $row['dur_surge_factor'];
				$amount				  = round($rockBaseAmount * $factor);
				$this->rockBaseAmount = $amount;
				$this->surgeAmount	  = $this->rockBaseAmount - $quoteModel->routeRates->rockBaseAmount;
				$this->refId		  = $row['dur_id'];
				$this->factor		  = $factor;
				$this->isApplied	  = true;
				$this->isApplicable	  = true;
				$flag				  = 1;

				$this->desc = "DU2";
			}
		}
		if ($flag == 0)
		{
			$this->surgeAmount	= 0;
			$this->factor		= 1.0;
			$this->isApplied	= false;
			$this->isApplicable = false;
		}
	}

	public function applyDDBPV2(Quote &$quoteModel)
	{
		$isDDBPV2SurgeApplicable = Config::get('Surge.DDBPV2');
		$flag					 = 0;
		if ($isDDBPV2SurgeApplicable)
		{
			$totalSurge = DynamicSurge::getDDBPV2($quoteModel);
			if ($totalSurge > 1)
			{
				$rockBaseAmount						   = $quoteModel->routeRates->rockBaseAmount;
				$factor								   = $totalSurge;
				$amount								   = round($rockBaseAmount * $factor);
				$this->rockBaseAmount				   = $amount;
				$this->surgeAmount					   = $this->rockBaseAmount - $quoteModel->routeRates->rockBaseAmount;
				$this->refId						   = 1;
				$this->factor						   = $factor;
				$this->isApplied					   = true;
				$this->isApplicable					   = true;
				$flag								   = 1;
				$this->DDBPV2lastUpdateDay			   = $quoteModel->DDBPV2lastUpdateDay;
				$this->DDBPV2vndCostPerDistance		   = $quoteModel->DDBPV2vndCostPerDistance;
				$this->DDBPV2cityIdentifier			   = $quoteModel->DDBPV2cityIdentifier;
				$this->DDBPV2upperGuardRail			   = $quoteModel->DDBPV2upperGuardRail;
				$this->DDBPV2vndAskingPerKm			   = $quoteModel->DDBPV2vndAskingPerKm;
				$this->DDBPV2rowIdentifier			   = $quoteModel->DDBPV2rowIdentifier;
				$this->DDBPV2quotedDistance			   = $quoteModel->DDBPV2quotedDistance;
				$this->DDBPV2vndGoingRate			   = $quoteModel->DDBPV2vndGoingRate;
				$this->DDBPV2vndAskingRate			   = $quoteModel->DDBPV2vndAskingRate;
				$this->DDBPV2pickupCount			   = $quoteModel->DDBPV2pickupCount;
				$this->DDBPV2initcapacity			   = $quoteModel->DDBPV2initcapacity;
				$this->DDBPV2OriginalCapacity		   = $quoteModel->DDBPV2OriginalCapacity;
				$this->DDBPV2rockBaseAmount			   = $quoteModel->DDBPV2rockBaseAmount;
				$this->DDBPV2goingRegularRatio		   = $quoteModel->DDBPV2goingRegularRatio;
				$this->DDBPV2askingGoingRatio		   = $quoteModel->DDBPV2askingGoingRatio;
				$this->DDBPV2minTargetSurge			   = $quoteModel->DDBPV2minTargetSurge;
				$this->DDBPV2finalCapSurge			   = $quoteModel->DDBPV2finalCapSurge;
				$this->DDBPV2finalcapacity			   = $quoteModel->DDBPV2finalcapacity;
				$this->DDBPV2extraSurge				   = $quoteModel->DDBPV2extraSurge;
				$this->DDBPV2normalized_count_increase = $quoteModel->DDBPV2normalized_count_increase;
				$this->DDBPV2normalized_increase_per   = $quoteModel->DDBPV2normalized_increase_per;
				$this->DDBPV2surge_basis_count		   = $quoteModel->DDBPV2surge_basis_count;
				$this->DDBPV2surge_basis_5_Per_steps   = $quoteModel->DDBPV2surge_basis_5_Per_steps;
				$this->DDBPV2surge_basis_25_Per_steps  = $quoteModel->DDBPV2surge_basis_25_Per_steps;
				$this->DDBPV2surge_basis_50_Per_steps  = $quoteModel->DDBPV2surge_basis_50_Per_steps;
				$this->DDBPV2surge_basis_100_Per_steps = $quoteModel->DDBPV2surge_basis_100_Per_steps;
				$this->DDBPV2totalSurge				   = $quoteModel->DDBPV2totalSurge;

				$this->desc = "DD2";
			}
		}
		if ($flag == 0)
		{
			$this->surgeAmount	= 0;
			$this->factor		= 1.0;
			$this->isApplied	= false;
			$this->isApplicable = false;
		}
	}

	public function applyDDSBP(Quote &$quoteModel)
	{
		/**
		 * RouteRates $routeRates
		 */
		$routeRates = $quoteModel->routeRates;
		$baseFare	= $routeRates->rockBaseAmount;
		$pickupDate = $quoteModel->pickupDate;
		$cabType	= $quoteModel->cabType;
		$bkgType	= $quoteModel->processedTripType;
		$fromCityId = $quoteModel->sourceCity;
		$toCityId	= $quoteModel->destinationCity;
		$vhcCatId	= SvcClassVhcCat::model()->findByPk($cabType)->scv_vct_id;

		$objDDSBP = DynamicDemandSupplySurge::model()->calculate($baseFare, $pickupDate, $fromCityId, $toCityId, $vhcCatId, $bkgType);
		if ($objDDSBP)
		{
			$this->rockBaseAmount = $objDDSBP->baseFare;
			$this->surgeAmount	  = $objDDSBP->surgeValue;
			$this->refId		  = $objDDSBP->refId;
			$this->factor		  = $objDDSBP->factor;
			$this->isApplied	  = ($this->factor > 0 && $this->factor != 1);
			$this->desc			  = "DS";
		}
	}

	public function getSummary()
	{
		$fullDesc = "";

		if (!$this->isApplied || $this->surgeAmount == 0)
		{
			goto end;
		}

		$desc		  = $this->desc;
		$operator	  = ($this->surgeAmount > 0) ? "+" : "-";
		$amount		  = $this->surgeAmount;
		$factor		  = $this->factor;
		$isOverride	  = $this->isOverRide;
		$overrideDesc = ($isOverride) ? " (O)" : "";
		$fullDesc	  = "[$desc: {$operator}₹{$amount} - {$factor}{$overrideDesc}]";

		end:
		return $fullDesc;
	}
}
