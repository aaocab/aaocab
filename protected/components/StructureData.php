<?php

use \Spatie\SchemaOrg\Schema;

class StructureData
{

	function __construct()
	{
		
	}

	/** @deprecated 
	 * new function getOrganisation
	 *  */
	public static function providerDetails()
	{

		//provider details function
		$contact['@context']			 = "http://schema.org/";
		$contact['@type']				 = "Organization";
		$contact['name']				 = 'aaocab';
		$contact['url']					 = Yii::app()->getBaseUrl(true);
		$contact['logo']				 = Yii::app()->getBaseUrl(true) . "/images/logo2_outstation.png";
		$contact['email']				 = 'mailto:info@aaocab.com';
		$contact['description']			 = "Gozo Cabs is the best rated AC cab services with driver for all India. Book in advance for cheapest prices. 24 x 7 customer support by web chat or phone. One way cab services. Round trips. Airport transfers. Package tours. Shared Taxi. Intercity Shuttle service. More comfortable &; cheaper than going by bus or train. Serving in 3000+ cities. Great Price &amp; Top Quality guaranteed.";
		$arrContactPoint				 = array();
		$arrContactPoint['@type']		 = "ContactPoint";
		$arrContactPoint['telephone']	 = "+91-90518-77-000";
		$arrContactPoint['contactType']	 = "Customer service";
		$contact['contactPoint'][]		 = $arrContactPoint;
		$arrContactPoint['@type']		 = "ContactPoint";
		$arrContactPoint['telephone']	 = "+1-650-741-4696";
		$arrContactPoint['contactType']	 = "Customer service";
		$arrSocial						 = array();
		$arrSocial[]					 = 'http://www.facebook.com/aaocab';
		$arrSocial[]					 = 'https://twitter.com/aaocab';
		$arrSocial[]					 = 'http://www.instagram.com/aaocab/';
		$arrSocial[]					 = 'https://in.linkedin.com/company/aaocab';
		$arrSocial[]					 = 'http://www.youtube.com/channel/UCzAegwLeirBkmKDxtr5tTFA/featured';
		$arrSocial[]					 = 'http://www.tripadvisor.in/Attraction_Review-g304551-d9976364-Reviews-Gozo_Cabs-New_Delhi_National_Capital_Territory_of_Delhi.html';
		$arrContactPoint['sameAs']		 = $arrSocial;
		$contact['contactPoint'][]		 = $arrContactPoint;
		return $contact;
	}

	public static function tempoTravellerProviderDetails($city)
	{
		$contact['@context']	 = "http://schema.org/";
		$contact['@type']		 = "AutoRental";
		$contact['name']		 = 'aaocab';
		$contact['pricerange']	 = "$$";
		$contact['url']			 = Yii::app()->getBaseUrl(true) . "/tempo-traveller-rental/" . $city;
		$contact['image']		 = Yii::app()->getBaseUrl(true) . "/images/logo2_outstation.png";
		$contact['email']		 = 'mailto:info@aaocab.com';
		$contact['telephone']	 = "+91-90518-77-000";
		$contact['address']		 = Config::getGozoAddress();
		return $contact;
	}

	/** @deprecated 
	 * new function dayRentalFAQ
	 *  */
	public static function dayRentalProviderDetails()
	{
		$dayRental['@context']	 = "https://schema.org";
		$dayRental['@type']		 = "FAQPage";

		$arrayEntity[0]['@type']			 = "Question";
		$arrayEntity[0]['name']				 = "Why book a day rental with aaocab?";
		$arraySubEntity['@type']			 = "Answer";
		$arraySubEntity['text']				 = "<p>Get the same high quality and great prices that you have come to expect from Gozo. Now for local city rentals too.</p>";
		$arrayEntity[0]['acceptedAnswer']	 = $arraySubEntity;

		$arrayEntity[1]['@type']			 = "Question";
		$arrayEntity[1]['name']				 = "Can I get cabs at my Disposal?";
		$arraySubEntity['@type']			 = "Answer";
		$arraySubEntity['text']				 = "<p>With aaocab Day Rentals you get cabs at your disposal for as long as you want and travel to multiple stop points with just one booking within city limits.</p>";
		$arrayEntity[1]['acceptedAnswer']	 = $arraySubEntity;

		$arrayEntity[2]['@type']			 = "Question";
		$arrayEntity[2]['name']				 = "What are the Packages available?";
		$arraySubEntity['@type']			 = "Answer";
		$arraySubEntity['text']				 = "<p>Packages start for 4 hours and can extend up to 12 hours! Also, with some nominal additional charges cabs can be retained beyond package limits</p>";
		$arrayEntity[2]['acceptedAnswer']	 = $arraySubEntity;

		$arrayEntity[3]['@type']			 = "Question";
		$arrayEntity[3]['name']				 = "What are the payment options?";
		$arraySubEntity['@type']			 = "Answer";
		$arraySubEntity['text']				 = "<p>Now go cashless and travel easy. We have multiple payment option for your hassle-free transaction.</p>";
		$arrayEntity[3]['acceptedAnswer']	 = $arraySubEntity;

		$arrayEntity[4]['@type']			 = "Question";
		$arrayEntity[4]['name']				 = "Do I need to pay surge fee or waiting charges?";
		$arraySubEntity['@type']			 = "Answer";
		$arraySubEntity['text']				 = "<p>Unlike point to point services you get a cab and driver at your disposal for the length of time of your rental. If you know you are going to have a busy day traveling around town, just book a car and driver for the day and go where you want in town</p>";
		$arrayEntity[4]['acceptedAnswer']	 = $arraySubEntity;

		$dayRental['mainEntity'] = $arrayEntity;

		return $dayRental;
	}

	public static function dayRentalFAQ()
	{

		$Ques	 = [];
		$Ans	 = [];
		$Ques[0] = "Why book a day rental with aaocab?";
		$Ques[1] = "Can I get cabs at my Disposal?";
		$Ques[2] = "What are the Packages available?";
		$Ques[3] = "What are the payment options?";
		$Ques[4] = "Do I need to pay surge fee or waiting charges?";
		$Ans[0]	 = "Get the same high quality and great prices that you have come to expect from Gozo. Now for local city rentals too.";
		$Ans[1]	 = "With aaocab Day Rentals you get cabs at your disposal for as long as you want and travel to multiple stop points with just one booking within city limits.";
		$Ans[2]	 = "Packages start for 4 hours and can extend up to 12 hours! Also, with some nominal additional charges cabs can be retained beyond package limits.";
		$Ans[3]	 = "Now go cashless and travel easy. We have multiple payment option for your hassle-free transaction.";
		$Ans[4]	 = "Unlike point to point services you get a cab and driver at your disposal for the length of time of your rental. If you know you are going to have a busy day traveling around town, just book a car and driver for the day and go where you want in town";

		$faq		 = new \Spatie\SchemaOrg\FAQPage();
		$arrayEntity = [];
		foreach ($Ques as $key => $que)
		{
			$arrayEntity[] = Schema::question()
					->name($que)
					->acceptedAnswer(
					Schema::answer()
					->text($Ans[$key])
			);
		}
		$faq->mainEntity($arrayEntity);
		return $faq;
	}

//working on it
	public static function routeFAQ($rmodel)
	{

		$fcname	 = $rmodel->rutFromCity->cty_name;
		$tcname	 = $rmodel->rutToCity->cty_name;
		$Ques	 = [];
		$Ans	 = [];
		$Ques[]	 = " Is it safe to travel from $fcname to $tcname by car?";
		$Ans[]	 = "Yes, it is safe to travel from $fcname to $tcname by road.However, as a precautionary measure, we recommend you to take your trip during the day and avoid traveling at night.";

		$Ques[]	 = " How much does $fcname to $tcname taxi cost?";
		$Ans[]	 = "For one-way cab service with aaocab from $fcname to $tcname cab fare starts from Rs.  $basePriceOW    and for round trip cab service starting from Rs   {$allQuot[1]->routeRates->ratePerKM} /Km for $fcname to $tcname. For best price on your travel date for various car rental options, please enter trip details and check.";

		$Ques[]	 = "What is the travel distance from $fcname to $tcname?";
		$Ans[]	 = "The travel distance from $fcname to $tcname is approximately   {$model->bkg_trip_distance} Kms. It takes around   {floor(($rmodel->rut_estm_time / 60))}  hours";

		$Ques[]	 = " What are the options available for $fcname to $tcname by car?";
		$Ans[]	 = "The car options available from $fcname to $tcname by car are 4 seaters AC sedan cars (Dzire, Toyota Etios, Tata Indigo or equivalent) for small group of travellers, 4 seater AC Compact cars (Indica, Swift, Alto, Ford Figo or equivalent) and 7-9 seater AC SUV cars (Inova, Mahindra Xylo, Chevrolet Tavera or equivalent) and 12 to 16 seater AC tempo traveller.";

		$Ques[]	 = " Does price includes Driver charges and Night charges?";
		$Ans[]	 = "Yes, our pricing policy is very transparent. All the prices includes distance, driver allowance, taxes, tolls etc. And if you are travelling in night then night charge would also be included.";

		$Ques[]	 = "Do I need to make payment in advance to book $fcname to $tcname cab?";
		$Ans[]	 = "Yes, You need to just pay 10%-15% advance to book your hassle free and reliable $fcname to $tcname cab service at your doorstep.";
	}

	/** @deprecated  
	 * new function StructureData::breadCrumbSchema
	 * @var ClassName $variable */
	public static function breadCumbDetails($city_id, $to_city_id = "", $type)
	{

		$arrStructData				 = array();
		$arrStructData['@context']	 = "http://schema.org/";
		$arrStructData['@type']		 = "BreadcrumbList";
		$city_name					 = Cities::getName($city_id);
		$city_name					 = strtolower(str_replace(' ', '_', $city_name));
		$url						 = Yii::app()->getBaseUrl(true);
		//list element

		for ($a = 1; $a <= 3; $a++)
		{
			$arrListData			 = array();
			$arrListData['@type']	 = 'ListItem';
			$arrListData['position'] = $a;
			switch ($a)
			{
				case 1:
					$arrListData['name'] = "Home";
					$arrListData['item'] = $url;
					break;
				case 2:
					$arrListData['name'] = "Car Rental";
					$arrListData['item'] = $url . '/bknw';
					break;
				case 3:
					$arrListData['name'] = ucwords($city_name) . " Cab Service";
					$arrListData['item'] = $url . '/car-rental/' . $city_name;
					break;
			}
			$arrBreadcum[] = $arrListData;
		}
		if ($type == 'route_type')
		{
			if ($to_city_id != "")
			{
				$to_city_name	 = Cities::getName($to_city_id);
				$to_city_name	 = strtolower(str_replace(' ', '_', $to_city_name));
			}
			$arrListRoute['@type']		 = 'ListItem';
			$arrListRoute['position']	 = 4;
			$arrListRoute['name']		 = ucwords($city_name) . ' - ' . ucwords($to_city_name) . " Cab Service";
			$arrListRoute['item']		 = $url . "/book-taxi/" . $city_name . '-' . $to_city_name;
			$arrBreadcum[]				 = $arrListRoute;
		}

		$arrStructData['itemListElement'] = $arrBreadcum;
		return $arrStructData;
	}

	/**
	 * Function for getting the Structured Markup Data for breadcrumb  defined by schema.org
	 *  
	 * return json  
	 */
	public static function breadCrumbSchema($fcity_id, $tcity_id = "", $type = '')
	{
		$key = "StructureData::breadCrumbSchema{$fcity_id}_{$tcity_id}_$type";

		$breadcumbDataJson = Yii::app()->cache->get($key);
		if ($breadcumbDataJson !== false)
		{
			goto result;
		}
		$fcity_name	 = Cities::getName($fcity_id);
		$fcity_name	 = strtolower(str_replace(' ', '_', $fcity_name));
		if ($tcity_id != "")
		{
			$tcity_name	 = Cities::getName($tcity_id);
			$tcity_name	 = strtolower(str_replace(' ', '_', $tcity_name));
		}

		$url			 = Yii::app()->params['fullBaseURL'];
		$breadcrumbList	 = new \Spatie\SchemaOrg\BreadcrumbList();
		$listItem		 = [];

		$listItem[] = Schema::listItem()
				->position(1)
				->name('Home')
				->item($url);

		$listItem[] = Schema::listItem()
				->position(2)
				->name('Car Rental')
				->item($url . '/bknw');
		if ($type == 'airport_transfer')
		{
			$listItem[] = Schema::listItem()
					->position(3)
					->name(ucwords($fcity_name) . " Airport Transfer")
					->item($url . "/airport-transfer/" . $fcity_name);
		}
		else
		{
			$listItem[] = Schema::listItem()
					->position(3)
					->name(ucwords($fcity_name) . " Car Rental")
					->item($url . '/car-rental/' . $fcity_name);
		}
		if ($type == 'route_type')
		{
			$listItem[] = Schema::listItem()
					->position(4)
					->name(ucwords($fcity_name) . ' - ' . ucwords($tcity_name) . " Cab Service")
					->item($url . "/book-taxi/" . $fcity_name . '-' . $tcity_name);
		}
		if ($type == 'outstation-cabs')
		{
			$listItem[] = Schema::listItem()
					->position(4)
					->name(ucwords($fcity_name) . " Outstation Cabs")
					->item($url . "/outstation-cabs/" . $fcity_name);
		}

		$breadcrumbList->itemListElement($listItem);

		$breadcumbDataJson = json_encode($breadcrumbList, JSON_UNESCAPED_SLASHES);
		Yii::app()->cache->set($key, $breadcumbDataJson, 60 * 60 * 24 * 30, new CacheDependency("Schema"));
		result:
		return $breadcumbDataJson;
	}

	public static function getBasicOrganisation()
	{
		$baseUrl = Yii::app()->params['fullBaseURL'];
		$obj	 = Schema::organization()
				->name('aaocab')
				->url($baseUrl);
		return $obj;
	}

	/**
	 * Function for getting the Structured Markup Data for Organization defined by schema.org
	 *  
	 * return object
	 */
	public static function getOrganisation()
	{

		$baseUrl = Yii::app()->params['fullBaseURL']; //Yii::app()->getBaseUrl(true);
//		$ratingArr	 = Ratings::getOrganisationSummary();
		$obj	 = StructureData::getBasicOrganisation()
				->description("Gozo Cabs is the best rated AC cab services with driver for all India. Book in advance for cheapest prices. 24 x 7 customer support by web chat or phone. One way cab services. Round trips. Airport transfers. Package tours. Shared Taxi. Intercity Shuttle service. More comfortable &; cheaper than going by bus or train. Serving in 3000+ cities. Great Price & Top Quality guaranteed.")
				->logo($baseUrl . "/images/logo2_outstation.png")
				->address(Config::getGozoAddress())
				->aggregateRating(StructureData::getAggregateRating())
				->review(StructureData::getOrganisationReview())
				->contactPoint(
				[
					Schema::contactPoint()
					->telephone("+91-90518-77-000")
					->email('info@aaocab.com')
					->contactType('Customer service'),
//					Schema::contactPoint()
//					->contactType('Customer service')
//					->telephone("+1-650-741-4696")
//					->email('info@aaocab.com')
				]
		);

		return $obj;
	}

	/**
	 * Function for getting the Structured Markup Data for administrativeArea defined by schema.org
	 *  
	 * return object
	 */
	public static function administrativeArea($cModel)
	{
		$obj = Schema::administrativeArea()
				->address($cModel['cty_full_name'])
				->geo(
				Schema::geoShape()
				->polygon(GeoData::getPolygonTextByCity($cModel['cty_id']))
		);
		return $obj;
	}

	/**
	 * Function for getting the Structured Markup Data as defined by schema.org
	 * @param $rModel
	 * @param $routeQuot
	 */
	public static function getSchemaForRoute($rModel)
	{

		$key		 = "getSchemaForRoute{$rModel->rut_id}";
		$taxiService = Yii::app()->cache->get($key);
		if ($taxiService !== false)
		{
			goto result;
		}
		// Route Rating
		//$arrRouteRating = Ratings::getRouteSummary($rModel->rut_from_city_id, $rModel->rut_to_city_id);

		$rutFromCity = $rModel->rutFromCity;
		$fcitydata	 = Cities::getCityShortDetailbyid($rModel->rut_from_city_id);
		/** @var cities $rutFromCity */
		// Main Node
		$taxiService = new \Spatie\SchemaOrg\TaxiService();
		$baseUrl	 = Yii::app()->params['fullBaseURL'];
		$taxiService->name($rutFromCity->cty_name . " To " . $rModel->rutToCity->cty_name . " Taxi");
		$image		 = new \Spatie\SchemaOrg\ImageObject();
		$image->url($baseUrl . "/images/car-rental.jpg")->width("300")->height("300");
		$taxiService
				->aggregateRating(StructureData::getAggregateRating()->itemReviewed(StructureData::getBasicOrganisation()))
				->provider(StructureData::getOrganisation())
				->image($image)
				->areaServed(StructureData::administrativeArea($fcitydata));

		Yii::app()->cache->set($key, $taxiService, 24 * 15 * 60 * 60, new CacheDependency("Schema"));

		result:
		return $taxiService;
	}

	/**
	 * Function for getting the Structured Markup Data as defined by schema.org
	 * @param $cityId
	 * @param $schemaDesc
	 */
	public static function getSchemaForCity($cityId, $schemaDesc)
	{
		$key		 = "StructureData::getSchemaForCity{$cityId}_{$schemaDesc}";
		$taxiService = Yii::app()->cache->get($key);
		if ($taxiService !== false)
		{
			goto result;
		}

		$cityModel	 = Cities::getCityShortDetailbyid($cityId); //Cities::model()->findByPk($cityId);
		/** @var Cities $cityModel */
		$cty_name	 = $cityModel['cty_name'];

		// Main Node
		$taxiService = new \Spatie\SchemaOrg\TaxiService();

		$taxiService->name($cty_name . " $schemaDesc");

		$taxiService
				->provider(StructureData::getOrganisation())
				->areaServed(StructureData::administrativeArea($cityModel));
		Yii::app()->cache->set($key, $taxiService, 24 * 15 * 60 * 60, new CacheDependency("Schema"));

		result:
		return $taxiService;
	}

	/**
	 * Function for getting the Structured Markup Data for "book-taxi" pages as defined by schema.org
	 * @param $rModel Route
	 * @return json
	 */
	public static function getRouteServiceOfferSchema($rModel)
	{
		Logger::setCategory('models.StructuredData.getCarRentalSchema');
		Logger::profile("  start getRouteServiceOfferSchema");
		$key		 = "StructureData::getRouteServiceOfferSchema{$rModel->rut_id}";
		$taxiService = Yii::app()->cache->get($key);
		if ($taxiService !== false)
		{
			goto result;
		}

		// Vehicle Model Types
		$allowedCabCategories	 = [1, 2, 3];
		$arrVehicleModels		 = VehicleTypes::getMasterDetails($allowedCabCategories);

		$tripType		 = [1 => "One Way", 2 => "Round Trip"];
		$markup			 = 1.15;
		$tripTypeKeys	 = array_keys($tripType);
		$prrData		 = PriceRule::getByCityCabTripType($rModel->rut_from_city_id, $allowedCabCategories, $tripTypeKeys);
		$itemOffered	 = [];
		$offerCatalog	 = [];
		foreach ($tripType as $tKey => $tripName)
		{
			$itemOffered = [];
			foreach ($allowedCabCategories as $cabKey)
			{
//				$prrate			 = PriceRule::getByCity($rModel->rut_from_city_id, $tKey, $cabKey);
				$prrate			 = $prrData[$cabKey][$tKey];
				$raw_ratePerKm	 = $prrate['prr_rate_per_km'] * $markup;
				$ratePerKm		 = (round($raw_ratePerKm * 2)) / 2;
//				$ratePerKm		 = $prrate['prr_rate_per_km_extra'];
				$vctData		 = $arrVehicleModels[$cabKey];
				$itemOffered[]	 = Schema::offer()->name('Cab Service')
						->itemOffered(
								Schema::car()
								->vehicleSeatingCapacity($vctData['vct_capacity'])
								->category($vctData['vct_label'])
								->model($vctData['vct_desc']))
						->priceSpecification(Schema::priceSpecification()
						->price($ratePerKm)
						->priceCurrency("INR")
						->eligibleQuantity(
								Schema::quantitativeValue()
								->unitText("KM")));
			}
			$offerCatalog [] = Schema::offerCatalog()
					->name($tripName)
					->itemListElement(
					$itemOffered
			);
		}
		$taxiService = StructureData::getSchemaForRoute($rModel);

		$taxiService->hasOfferCatalog(
				Schema::offerCatalog()
						->itemListElement($offerCatalog)
		);

		Yii::app()->cache->set($key, $taxiService, 24 * 15 * 60 * 60, new CacheDependency("Schema"));

		result:
		Logger::profile("  end getRouteServiceOfferSchema");
		Logger::unsetCategory('models.StructuredData.getCarRentalSchema');

		return json_encode($taxiService, JSON_UNESCAPED_SLASHES);
	}

	/**
	 * Function to build the Structured Markup Data for Car Rental as defined by schema.org
	 * @param $fcity 
	 */
	public static function getCarRentalSchema($fcity)
	{
		Logger::setCategory('models.StructuredData.getCarRentalSchema');
		Logger::profile("  start");
		$key	 = "StructureData::getCarRentalSchema_{$fcity}";
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			$taxiService = $data;
			goto result;
		}
		// Vehicle Model Types
		$allowedCabCategories	 = [1, 2, 3];
		$arrVehicleModels		 = VehicleTypes::getMasterDetails($allowedCabCategories);

		$tripType1	 = [
			1	 => "One Way",
			2	 => "Round Trip"];
		$tripType2	 = [
			'9'	 => 'Day Rental(4hr-40km)',
			'10' => 'Day Rental(8hr-80km)',
			'16' => 'Day Rental(10hr-100km)',
			'11' => 'Day Rental(12hr-120km)'
		];

		$markup			 = 1.15;
		$itemOffered	 = [];
		$offerCatalog	 = [];
		$tripType		 = array_keys($tripType1 + $tripType2);
		$prrData		 = PriceRule::getByCityCabTripType($fcity, $allowedCabCategories, $tripType);
		foreach ($tripType1 as $tKey => $tripName)
		{
			$itemOffered = [];
			foreach ($allowedCabCategories as $cabKey)
			{
				$prrate			 = $prrData[$cabKey][$tKey];
				$raw_ratePerKm	 = $prrate['prr_rate_per_km'] * $markup;
				$ratePerKm		 = (round($raw_ratePerKm * 2)) / 2;
//				$ratePerKm		 = $prrate['prr_rate_per_km_extra'];
				$vctData		 = $arrVehicleModels[$cabKey];
				$itemOffered[]	 = Schema::offer()->name('Cab Service')
						->itemOffered(
								Schema::car()
								->vehicleSeatingCapacity($vctData['vct_capacity'])
								->category($vctData['vct_label'])
								->model($vctData['vct_desc']))
						->priceSpecification(Schema::priceSpecification()
						->price($ratePerKm)
						->priceCurrency("INR")
						->eligibleQuantity(
								Schema::quantitativeValue()
								->unitText("KM")));
			}
			$offerCatalog [] = Schema::offerCatalog()
					->name($tripName)
					->itemListElement(
					$itemOffered
			);
		}

		foreach ($tripType2 as $tKey => $tripName)
		{
			$itemOffered = [];
			foreach ($allowedCabCategories as $cabKey)
			{
				$prrate					 = $prrData[$cabKey][$tKey];
				$raw_minAmount_perHour	 = $prrate['prr_rate_per_minute'] * $markup * 60;
				$minAmount				 = (round($raw_minAmount_perHour * 2)) / 2;
				$vctData				 = $arrVehicleModels[$cabKey];
				$itemOffered[]			 = Schema::offer()->name('Cab Service')
						->itemOffered(
								Schema::car()
								->vehicleSeatingCapacity($vctData['vct_capacity'])
								->category($vctData['vct_label'])
								->model($vctData['vct_desc']))
						->priceSpecification(Schema::priceSpecification()
						->minPrice($minAmount)
						->priceCurrency("INR")
						->eligibleQuantity(
								Schema::quantitativeValue()
								->unitText("Hour"))
//						->eligibleDuration(Schema::quantitativeValue()
//								->minValue($prrate['prr_min_duration'])
//								->unitText("Minutes"))
				);
			}
			$offerCatalog [] = Schema::offerCatalog()
					->name($tripName)
					->itemListElement(
					$itemOffered
			);
		}

		$taxiService = StructureData::getSchemaForCity($fcity, 'Car Rental');

		$taxiService->hasOfferCatalog(
				Schema::offerCatalog()
						->itemListElement($offerCatalog)
		);

		Yii::app()->cache->set($key, $taxiService, 24 * 15 * 60 * 60, new CacheDependency("Schema"));

		result:
		Logger::profile("  end");
		Logger::unsetCategory('models.StructuredData.getCarRentalSchema');
		return json_encode($taxiService, JSON_UNESCAPED_SLASHES);
	}

	/**
	 * Function to build the Structured Markup Data for Car Rental as defined by schema.org
	 * @param $fcity 
	 */
	public static function getAirportSchema($fcity)
	{
		$key	 = "StructureData::getAirportSchema{$fcity}";
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			$taxiService = $data;
			goto result;
		}
		// Vehicle Model Types
		$arrVehicleModels	 = VehicleTypes::model()->getMasterCarDetails();
		$ctyExcludedCabTypes = Cities::model()->getExcludedCabTypes($fcity);

		$zonExcludedCabTypes	 = ZoneCities::model()->getExcludedCabTypes($fcity);
		$data					 = array_unique(array_merge($ctyExcludedCabTypes, $zonExcludedCabTypes));
		$generalAllowed			 = [1, 2, 3];
		$allowedCabCategories	 = array_diff($generalAllowed, $data);

		$itemOffered	 = [];
		$offerCatalog	 = [];
		foreach ($allowedCabCategories as $cabKey)
		{
			$vctData		 = $arrVehicleModels[$cabKey];
			$itemOffered[]	 = Schema::offer()->name('Airport Transfer Service')
					->itemOffered
					(
					Schema::car()
					->vehicleSeatingCapacity($vctData['vct_capacity'])
					->category($vctData['vct_label'])
					->model($vctData['vct_desc'])
			);
		}
		$taxiService	 = StructureData::getSchemaForCity($fcity, 'Airport Transfer');
		$offerCatalog [] = Schema::offerCatalog()
				->name('Airport Transfer')
				->itemListElement(
				$itemOffered
		);
		$offerCatalog [] = Schema::offerCatalog()
				->name('One Way')
				->itemListElement(
				$itemOffered
		);
		$offerCatalog [] = Schema::offerCatalog()
				->name('Round Trip')
				->itemListElement(
				$itemOffered
		);

		$taxiService->hasOfferCatalog(
				Schema::offerCatalog()
						->itemListElement($itemOffered)
		);

		Yii::app()->cache->set($key, $taxiService, 24 * 15 * 60 * 60, new CacheDependency("Schema"));

		result:
		return json_encode($taxiService, JSON_UNESCAPED_SLASHES);
	}

	/** @deprecated */
	public static function getAirportSchemaTest($fcity)
	{
		$key	 = "StructureData::getAirportSchema{$fcity}";
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			$taxiService = $data;
			goto result;
		}
		// Vehicle Model Types
		$arrVehicleModels	 = VehicleTypes::model()->getMasterCarDetails();
		$ctyExcludedCabTypes = Cities::model()->getExcludedCabTypes($fcity);

		$zonExcludedCabTypes	 = ZoneCities::model()->getExcludedCabTypes($fcity);
		$data					 = array_unique(array_merge($ctyExcludedCabTypes, $zonExcludedCabTypes));
		$generalAllowed			 = [1, 2, 3];
		$allowedCabCategories	 = array_diff($generalAllowed, $data);

		$itemOffered	 = [];
		$offerCatalog	 = [];
		foreach ($allowedCabCategories as $cabKey)
		{
			$vctData		 = $arrVehicleModels[$cabKey];
			$itemOffered[]	 = Schema::offer()
					->name('Airport Transfer Service')
					->itemOffered
					(
					Schema::car()
					->vehicleSeatingCapacity($vctData['vct_capacity'])
					->category($vctData['vct_label'])
					->model($vctData['vct_desc'])
			);
		}
		$taxiService	 = StructureData::getSchemaForCity($fcity, 'Airport Transfer');
		$offerCatalog [] = Schema::offerCatalog()
				->name('Airport Transfer')
				->itemListElement(
				$itemOffered
		);
		$offerCatalog [] = Schema::offerCatalog()
				->name('One Way')
				->itemListElement(
				$itemOffered
		);
		$offerCatalog [] = Schema::offerCatalog()
				->name('Round Trip')
				->itemListElement(
				$itemOffered
		);

		$taxiService->hasOfferCatalog(
				Schema::offerCatalog()
						->itemListElement($offerCatalog)
		);

		Yii::app()->cache->set($key, $taxiService, 24 * 15 * 60 * 60, new CacheDependency("Schema"));

		result:
		return json_encode($taxiService, JSON_UNESCAPED_SLASHES);
	}

	/**   @deprecated  */
	public static function getServiceOfferSchemaOld()
	{

		exit;
		$route								 = 'delhi-jaipur';
		$rModel								 = Route::model()->getByName($route);
		$bookingRoute						 = new BookingRoute();
		$bookingRoute->brt_from_city_id		 = $rModel->rut_from_city_id;
		$bookingRoute->brt_to_city_id		 = $rModel->rut_to_city_id;
		$bookingRoute->brt_pickup_datetime	 = date("Y-m-d 07:00:00", strtotime("+8 day"));
		$bookingRoutes[]					 = $bookingRoute;
		$partnerId							 = Yii::app()->params['gozoChannelPartnerId'];
		$booktaxigetquote					 = Yii::app()->cache->get("QuoteforBooktaxi" . $rModel->rut_id);
		if ($booktaxigetquote == false)
		{
			$quote			 = new Quote();
			$quote->routes	 = $bookingRoutes;

			$quote->tripType		 = 1;
			$quote->flexxi_type		 = 1;
			$quote->suggestedPrice	 = 1;
			$quote->partnerId		 = $partnerId;
			$quote->quoteDate		 = date("Y-m-d H:i:s");
			$quote->pickupDate		 = $bookingRoute->brt_pickup_datetime;
			$quote->setCabTypeArr([], true);
			$quote->sourceQuotation	 = Quote::Platform_User;
			$routeQuot1				 = $quote->getQuote('catypeArrIncFlexxi', false);
			foreach ($routeQuot1 as $k => $v)
			{
				if (!$v->success)
				{
					unset($routeQuot1[$k]);
				}
			}
			$booktaxigetquote = serialize($routeQuot1);
			Yii::app()->cache->set("booktaxiGetQuote13" . $rModel->rut_id, $booktaxigetquote, 604800, new CacheDependency('booktaxiGetQuote'));
		}

		$routeQuot									 = unserialize($booktaxigetquote);
		$arrStructData['hasOfferCatalog']			 = array();
		$arrStructData['hasOfferCatalog']['@type']	 = "OfferCatalog";
		$arrStructData['hasOfferCatalog']['name']	 = "Car Taxi Services";

		$arrOfferCatalog					 = array();
		$arrOfferCatalog['@type']			 = "OfferCatalog";
		$arrOfferCatalog['itemListElement']	 = array();

		// Vehicle Types/ Category
		$arrVehicleTypes = VehicleTypes::model()->getCarType();

		// Vehicle Model Types
		$arrVehicleModels = VehicleTypes::model()->getMasterCarDetails();

		// Vehicle Model Rates
		if ($routeQuot && is_array($routeQuot) && count($routeQuot) > 0)
		{
			foreach ($routeQuot as $cabKey => $baseQuot)
			{
				if (!in_array($cabKey, array_keys($arrVehicleTypes)))
				{
					continue;
				}
				// One Way Rates
				$ratePerKM = $baseQuot->routeRates->ratePerKM;

				// Round Trip Rates
				$roundTripType	 = 2;
				//	$rates			 = AreaPriceRule::model()->getRules($rModel->rutFromCity->cty_id, $cabKey);
//				if ($rates && isset($rates[$roundTripType]))
//				{
				//	$priceRule = PriceRule::model()->findByPk($rates[$roundTripType]);
				$priceRule		 = PriceRule::getByCity($rModel->rutFromCity->cty_id, $roundTripType, $cabKey);
				if ($priceRule)
				{
					$ratePerKM = $priceRule->prr_rate_per_km_extra;
				}
				//	}

				$vehicleType		 = $arrVehicleTypes[$cabKey];
				$vehicleModelData	 = $arrVehicleModels[$cabKey];

				$arrStructVehicleOptions			 = array();
				$arrStructVehicleOptions['@type']	 = "Offer";

				$arrStructVehicleOptions['priceSpecification']									 = array();
				$arrStructVehicleOptions['priceSpecification']['@type']							 = "PriceSpecification";
				$arrStructVehicleOptions['priceSpecification']['price']							 = $ratePerKM;
				$arrStructVehicleOptions['priceSpecification']['priceCurrency']					 = 'INR';
				$arrStructVehicleOptions['priceSpecification']['eligibleQuantity']				 = array();
				$arrStructVehicleOptions['priceSpecification']['eligibleQuantity']['@type']		 = "QuantitativeValue";
				$arrStructVehicleOptions['priceSpecification']['eligibleQuantity']['unitText']	 = "KM";

				$arrStructVehicleOptions['itemOffered']								 = array();
				$arrStructVehicleOptions['itemOffered']['@type']					 = "Car";
				$arrStructVehicleOptions['itemOffered']['vehicleSeatingCapacity']	 = $vehicleModelData['vct_capacity'];
				$arrStructVehicleOptions['itemOffered']['category']					 = $vehicleType;
				$arrStructVehicleOptions['itemOffered']['model']					 = $vehicleModelData['vct_desc'];

				// For Type Flexxi Resetting Values
				if ($cabKey == VehicleCategory::SHARED_SEDAN_ECONOMIC)
				{
					$arrStructVehicleOptions['priceSpecification']['price']							 = $offerRateAmt;
					$arrStructVehicleOptions['priceSpecification']['eligibleQuantity']['unitText']	 = $unitRate;
					unset($arrStructVehicleOptions['itemOffered']['vehicleSeatingCapacity']);
				}

				$arrOfferCatalog['itemListElement'][] = $arrStructVehicleOptions;
			}
		}

		$arrStructData['hasOfferCatalog']['itemListElement']	 = array();
		$arrStructData['hasOfferCatalog']['itemListElement'][]	 = $arrOfferCatalog;
		$data													 = $arrStructData;

		echo json_encode($arrStructData, JSON_UNESCAPED_SLASHES);
	}

	public static function getRouteReview($fromCity, $toCity, $limit = 2)
	{
		$ratingArrVal = Ratings::getRouteOverAllTop($fromCity, $toCity, $limit);
		return StructureData::generateReviewBody($ratingArrVal);
	}

	public static function getCityReview($fromCity, $limit = 5)
	{
		$ratingArrVal = Ratings::getCityOverAllTop($fromCity, $limit);
		return StructureData::generateReviewBody($ratingArrVal);
	}

	public static function getOrganisationReview()
	{
		$ratingArrVal = Ratings::getOverallTop(2);
		return StructureData::generateReviewBody($ratingArrVal);
	}

	public static function generateReviewBody($ratingArrVal)
	{
		$reviews = [];
		foreach ($ratingArrVal as $value)
		{
			$reviews[] = Schema::review()
					->author(Schema::person()->name(ucwords($value['user_name'])))
					->datePublished(date('Y-m-d', strtotime($value['rtg_customer_date'])))
					->reviewBody($value['rtg_customer_review'])
					->reviewRating(Schema::rating()
					->bestRating(5)
					->ratingValue($value['rtg_customer_overall'])
					->worstRating(1)
			);
		}
		return $reviews;
	}

	public static function getAggregateRating()
	{
		$aggregateRating = Schema::aggregateRating()
				//->itemReviewed(Schema::Organization()->name('aaocab'))
				->bestRating(5)
				->worstRating(1)
				->ratingValue("4.5")
				->ratingCount(1843);
		return $aggregateRating;
	}

	public static function getCityAggregateRating()
	{
		$aggregateRating = Schema::aggregateRating()
				//->itemReviewed(Schema::Organization()->name('aaocab'))
				->bestRating(5)
				->worstRating(1)
				->ratingValue("4.5")
				->ratingCount(1843);
		return $aggregateRating;
	}

	public static function workingOn($rModel)
	{
		$fcity		 = $rModel->rut_from_city_id;
		$rutFromCity = $rModel->rutFromCity;
		$baseUrl	 = Yii::app()->params['fullBaseURL'];
		$fcitydata	 = Cities::getCityShortDetailbyid($rModel->rut_from_city_id);
		/** @var cities $rutFromCity */
		// Main Node
		$product	 = new \Spatie\SchemaOrg\Product();

		$product->name($rutFromCity->cty_name . " To " . $rModel->rutToCity->cty_name . " Taxi")
				->category('One Way');

		$arrVehicleModels = VehicleTypes::model()->getMasterCarDetails();

		$allowedCabCategories	 = [1, 2, 3];
		$prrData				 = PriceRule::getByCityCabTripType($fcity, $allowedCabCategories, 1);
		$markup					 = 1.15;
//		$vctData				 = $arrVehicleModels[1];


		$offers = [];
		foreach ($allowedCabCategories as $cabKey)
		{
			$prrate			 = $prrData[$cabKey][1];
			$raw_ratePerKm	 = $prrate['prr_rate_per_km'] * $markup;
			$ratePerKm		 = (round($raw_ratePerKm * 2)) / 2;
			$vctData		 = $arrVehicleModels[$cabKey];
			$offers[]		 = Schema::offer()
					->name($vctData['vct_label'])
					->priceSpecification(Schema::priceSpecification()
					->price($ratePerKm)
					->priceCurrency("INR")
					->eligibleQuantity(
							Schema::quantitativeValue()
							->unitText("KM")));
			;
		}

		$product->offers($offers)
				//->brand(StructureData::getOrganisation())
				//->image($baseUrl . "/images/logo2_outstation.png")
				//->description("Gozo Cabs is the best rated AC cab services with driver for all India. Book in advance for cheapest prices. 24 x 7 customer support by web chat or phone. One way cab services. Round trips. Airport transfers. Package tours. Shared Taxi. Intercity Shuttle service. More comfortable &; cheaper than going by bus or train. Serving in 3000+ cities. Great Price & Top Quality guaranteed.")
				->areaServed(StructureData::administrativeArea($fcitydata))
				->review(StructureData::getRouteReview($rModel->rut_from_city_id, $rModel->rut_to_city_id))
				->aggregateRating(StructureData::getAggregateRating())

		;
		return json_encode($product, JSON_PRETTY_PRINT);
	}

	/** @var BookingRoute $brtmodel */
	public static function getProductSchemaforTrip($brtmodel, $tKey)
	{
		Logger::setCategory('models.StructuredData.getCarRentalSchema');
		Logger::profile("  start getProductSchemaforTrip");
		$cnt	 = count($brtmodel);
		$fRoute	 = $brtmodel[0];
		$tRoute	 = $brtmodel[$cnt - 1];
		$keyDesc = $fRoute->brt_from_city_id . '_' . $tRoute->brt_to_city_id . '_' . $tKey;

		$key		 = "StructureData::getProductSchemaforTrip_$keyDesc";
		$products	 = Yii::app()->cache->get($key);
		if ($products !== false)
		{
			goto result;
		}
//		$model		 = new Booking();
		$tripNameArr = Booking::model()->booking_type;
		$tVal		 = $tripNameArr[$tKey];
		$typeUrl	 = BookingTemp::model()->booking_type_url;

		$allowedCabCategories	 = [1, 2, 3];
		$fcity					 = $fRoute->brt_from_city_id;
		$tcity					 = $tRoute->brt_to_city_id;
		$prrData				 = PriceRule::getByCityCabTripType($fcity, $allowedCabCategories, [$tKey]);
		$rutdesc				 = $fRoute->brtFromCity->cty_name . " To " . $tRoute->brtToCity->cty_name;

		$fcitydata			 = Cities::getCityShortDetailbyid($fcity);
		$products			 = [];
		$baseUrl			 = Yii::app()->params['fullBaseURL'];
		$getOrganisation	 = StructureData::getOrganisation();
		//->image($baseUrl . "/images/logo2_outstation.png")
		$description		 = "Gozo Cabs is the best rated AC cab services with driver for all India. Book in advance for cheapest prices. 24 x 7 customer support by web chat or phone. One way cab services. Round trips. Airport transfers. Package tours. Shared Taxi. Intercity Shuttle service. More comfortable &; cheaper than going by bus or train. Serving in 3000+ cities. Great Price & Top Quality guaranteed.";
		$areaServed			 = StructureData::administrativeArea($fcitydata);
		$routereview		 = StructureData::getRouteReview($fcity, $tcity, 2);
		$aggregateRating	 = StructureData::getAggregateRating();
		$arrVehicleModels	 = VehicleTypes::getMasterDetails();
//		foreach ($tripType as $tKey => $tVal)
//		{
		$product			 = new \Spatie\SchemaOrg\Product();
		$product->name($rutdesc . ' ' . $tVal)
				->category($tVal);
		$markup				 = 1.15;
		$offers				 = [];

		foreach ($allowedCabCategories as $cabKey)
		{
			$prrate	 = $prrData[$cabKey][$tKey];
			$vctData = $arrVehicleModels[$cabKey];

			$priceSpecification	 = StructureData::generatePriceSpecificationBody($prrate, $tKey, $markup);
			$offers[]			 = Schema::offer()
					->name($vctData['vct_label'])
					->priceCurrency("INR")
					->priceValidUntil(date('Y-m-d', strtotime('+3 month')))
					->availability("InStock")
					->url($baseUrl . '/bknw/' . array_search($tKey, $typeUrl) . '/' . $fRoute->brtFromCity->cty_alias_path . '/' . $tRoute->brtToCity->cty_alias_path)
					->priceSpecification($priceSpecification);
		}


		$product->offers($offers);
		$product
				//->brand($getOrganisation)
				->image($baseUrl . "/images/logo2_outstation.png")
				->areaServed($areaServed)
				->review($routereview)
				->sku("InStock")
				->description($description)
				->aggregateRating($aggregateRating);
		$products[] = $product;
//		}
		Yii::app()->cache->set($key, $products, 24 * 15 * 60 * 60, new CacheDependency("Schema"));

		result:
		Logger::profile("  end");
		Logger::unsetCategory('models.StructuredData.getCarRentalSchema');

		return json_encode($products, JSON_UNESCAPED_SLASHES);
	}

	/** @var Route $rModel */
	public static function getProductSchemaforRoute($rModel)
	{
		Logger::setCategory('models.StructuredData.getCarRentalSchema');
		Logger::profile("  start getRouteServiceOfferSchema");
		$key		 = "StructureData::getProductSchemaforRoute{$rModel->rut_id}";
		$products	 = Yii::app()->cache->get($key);
		if ($products !== false)
		{
			goto result;
		}
		$tripType				 = [1 => "One Way"];
		$tripTypeKeys			 = array_keys($tripType);
		$allowedCabCategories	 = [1, 2, 3];
		$fcity					 = $rModel->rut_from_city_id;
		$prrData				 = PriceRule::getByCityCabTripType($fcity, $allowedCabCategories, $tripTypeKeys);
		$rutdesc				 = $rModel->rutFromCity->cty_name . " To " . $rModel->rutToCity->cty_name;
		$fcitydata				 = Cities::getCityShortDetailbyid($fcity);
		$products				 = [];
		$baseUrl				 = Yii::app()->params['fullBaseURL'];
		$getOrganisation		 = StructureData::getOrganisation();
		//->image($baseUrl . "/images/logo2_outstation.png")
		$description			 = "Aao cab is the best rated AC cab services with driver for all India. Book in advance for cheapest prices. 24 x 7 customer support by web chat or phone. One way cab services. Round trips. Airport transfers. Package tours. Shared Taxi. Intercity Shuttle service. More comfortable &; cheaper than going by bus or train. Serving in 3000+ cities. Great Price & Top Quality guaranteed.";
		$areaServed				 = StructureData::administrativeArea($fcitydata);
		$routereview			 = StructureData::getRouteReview($rModel->rut_from_city_id, $rModel->rut_to_city_id, 2);
		$aggregateRating		 = StructureData::getAggregateRating();
		$arrVehicleModels		 = VehicleTypes::getMasterDetails();

		$image		 = new \Spatie\SchemaOrg\ImageObject();
		$image->url($baseUrl . "/images/car-rental.jpg")->width("300")->height("300");
		$product	 = StructureData::generateProductRouteSchema($rutdesc, $rModel->rut_name, 1, $tVal, $allowedCabCategories, $prrData, $arrVehicleModels);
		$product
				//->brand($getOrganisation)
				->image($image)
				//->areaServed($areaServed)#
				->review($routereview)
				->sku("InStock")
				->description($description)
				->aggregateRating($aggregateRating);
		$products	 = $product;

		Yii::app()->cache->set($key, $products, 24 * 15 * 60 * 60, new CacheDependency("Schema"));

		result:
		Logger::profile("  end");
		Logger::unsetCategory('models.StructuredData.getCarRentalSchema');
		return json_encode($products, JSON_UNESCAPED_SLASHES);
	}

	public static function generateProductRouteSchema($rutdesc, $rutname, $tKey, $tVal, $allowedCabCategories, $prrData, $arrVehicleModels)
	{
		$product = new \Spatie\SchemaOrg\Product();
		$product->name($rutdesc . " Taxi ")
				->category($rutdesc . " Taxi");
		$markup	 = 1.15;
		$offers	 = [];
		$baseUrl = Yii::app()->params['fullBaseURL'];
		foreach ($allowedCabCategories as $cabKey)
		{
			$prrate	 = $prrData[$cabKey][$tKey];
			$vctData = $arrVehicleModels[$cabKey];

			$raw_ratePerKm	 = $prrate['prr_rate_per_km'] * $markup;
			$ratePerKm		 = (round($raw_ratePerKm * 2)) / 2;

			$priceSpecification	 = StructureData::generatePriceSpecificationBody($prrate, $tKey, $markup);
			$offers[]			 = Schema::offer()
					->name($vctData['vct_label'])
					->price($ratePerKm)
					->priceCurrency("INR")
					->priceValidUntil(date('Y-m-d', strtotime('+3 month')))
					->availability("InStock")
					->url($baseUrl . '/book-taxi/' . $rutname)
					->priceSpecification($priceSpecification);
		}

		$product->offers($offers);
		return $product;
	}

	public static function getProductSchemaforCity($city)
	{
		Logger::setCategory('models.StructuredData.getCarRentalSchema');
		Logger::profile("  start getProductSchemaforCity");
		$key		 = "StructureData::getProductSchemaforCity{$city}";
		$products	 = Yii::app()->cache->get($key);
		if ($products !== false)
		{
			goto result;
		}

		$allowedCabCategories	 = [1, 2, 3];
		$tripType				 = [1 => "One Way"];
		$tripTypeKeys			 = array_keys($tripType);
		$prrData				 = PriceRule::getByCityCabTripType($city, $allowedCabCategories, $tripTypeKeys);
		$fcitydata				 = Cities::getCityShortDetailbyid($city);
		$products				 = [];

		$baseUrl			 = Yii::app()->params['fullBaseURL'];
		$getOrganisation	 = StructureData::getOrganisation();
		//->image($baseUrl . "/images/logo2_outstation.png")
		$description		 = "Aao cab is the best rated AC cab services with driver for all India. Book in advance for cheapest prices. 24 x 7 customer support by web chat or phone. One way cab services. Round trips. Airport transfers. Package tours. Shared Taxi. Intercity Shuttle service. More comfortable &; cheaper than going by bus or train. Serving in 3000+ cities. Great Price & Top Quality guaranteed.";
		$areaServed			 = StructureData::administrativeArea($fcitydata);
		$cityreview			 = StructureData::getCityReview($city, 2);
		$aggregateRating	 = StructureData::getCityAggregateRating();
		$cty_name			 = $fcitydata['cty_name'];
		$arrVehicleModels	 = VehicleTypes::getMasterDetails();

		foreach ($tripType as $tKey => $tVal)
		{
			$product	 = StructureData::generateProductCitySchema($cty_name, $tKey, $tVal, $allowedCabCategories, $prrData, $arrVehicleModels);
			$product
					//->brand($getOrganisation)
					->image($baseUrl . "/images/car-rental.jpg")
					//->areaServed($areaServed)
					->review($cityreview)
					->sku("InStock")
					->description($description)
					->aggregateRating($aggregateRating);
			$products	 = $product;
		}

		Yii::app()->cache->set($key, $products, 24 * 15 * 60 * 60, new CacheDependency("Schema"));

		result:
		Logger::profile("  end");
		Logger::unsetCategory('models.StructuredData.getCarRentalSchema');
		return json_encode($products, JSON_UNESCAPED_SLASHES);
	}

	public static function generateProductCitySchema($cty_name, $tKey, $tVal, $allowedCabCategories, $prrData, $arrVehicleModels)
	{

		$baseUrl = Yii::app()->params['fullBaseURL'];
		$markup	 = 1.15;

		$offers = [];
		foreach ($allowedCabCategories as $cabKey)
		{
			$prrate	 = $prrData[$cabKey][$tKey];
			$vctData = $arrVehicleModels[$cabKey];

			$raw_ratePerKm	 = $prrate['prr_rate_per_km'] * $markup;
			$ratePerKm		 = (round($raw_ratePerKm * 2)) / 2;

			$priceSpecification = StructureData::generatePriceSpecificationBody($prrate, $tKey, $markup);

			$offers[] = Schema::offer()
					->name($vctData['vct_label'])
					->price($ratePerKm)
					->priceCurrency("INR")
					->priceValidUntil(date('Y-m-d', strtotime('+3 month')))
					->availability("InStock")
					->url($baseUrl . '/car-rental/' . $cty_name)
					->priceSpecification($priceSpecification);
		}
		$product = new \Spatie\SchemaOrg\Product();
		$product->name($cty_name . " Car Rental")
				->category($cty_name . " Car Rental");
		$product->offers($offers);
		return $product;
	}

	public static function generatePriceSpecificationBody($prrate, $tKey, $markup)
	{
		$priceSpecification = '';
		if (in_array($tKey, [1, 2, 3]))
		{
			$raw_ratePerKm		 = $prrate['prr_rate_per_km'] * $markup;
			$ratePerKm			 = (round($raw_ratePerKm * 2)) / 2;
			$priceSpecification	 = Schema::priceSpecification()
					->price($ratePerKm)
//					->priceCurrency("INR")
					->eligibleQuantity(
					Schema::quantitativeValue()
					->unitText("KM"));
		}

		if (in_array($tKey, [9, 10, 11]))
		{
			$raw_minAmount_perHour	 = $prrate['prr_rate_per_minute'] * $markup * 60;
			$minAmount				 = (round($raw_minAmount_perHour * 2)) / 2;
			$priceSpecification		 = Schema::priceSpecification()
					->price($minAmount)
//					->priceCurrency("INR")
					->eligibleQuantity(
					Schema::quantitativeValue()
					->unitText("Hour"));
		}
		return $priceSpecification;
	}

	public static function getContactUsSchema()
	{

		$baseUrl = Yii::app()->params['fullBaseURL']; //Yii::app()->getBaseUrl(true);
//		$ratingArr	 = Ratings::getOrganisationSummary();
		$obj	 = Schema::organization()
				->name('aaocab')
				->url($baseUrl)
//				->description("Gozo Cabs is the best rated AC cab services with driver for all India. Book in advance for cheapest prices. 24 x 7 customer support by web chat or phone. One way cab services. Round trips. Airport transfers. Package tours. Shared Taxi. Intercity Shuttle service. More comfortable &; cheaper than going by bus or train. Serving in 3000+ cities. Great Price & Top Quality guaranteed.")
				->image($baseUrl . "/images/logo2_outstation.png")
				->address(Schema::PostalAddress()
						->addressLocality("Gurgaon, Haryana")
						->postalCode("122001")
						->streetAddress(Config::getGozoAddress())
				)
				->sameAs([
					"http://www.facebook.com/aaocab/",
					"https://twitter.com/aaocab?lang=en",
					"http://www.instagram.com/aaocab/?hl=en",
					"http://www.linkedin.com/company/aaocab/"
				])
				->telephone("+91-90518-77-000")
				->email('info@aaocab.com');

		return $obj;
	}

	public static function faqSchema($rModel, $minPrice, $perKmCharge, $distance)
	{

		$key = "StructureData::faqSchema{$rModel->rut_from_city_id}_{$rModel->rut_to_city_id}";

		$fcity_name	 = Cities::getName($rModel->rut_from_city_id);
		$fcname		 = strtolower(str_replace(' ', '_', $fcity_name));
		if ($rModel->rut_to_city_id != "")
		{
			$tcity_name	 = Cities::getName($rModel->rut_to_city_id);
			$tcname		 = strtolower(str_replace(' ', '_', $tcity_name));
		}

		$url	 = Yii::app()->params['fullBaseURL'];
		$Ques	 = [];
		$Ans	 = [];
		$Ques[]	 = " Is it safe to travel from $fcname to $tcname by car?";
		$Ans[]	 = "Yes, it is safe to travel from $fcname to $tcname by road.However, as a precautionary measure, we recommend you to take your trip during the day and avoid traveling at night.";

		$Ques[]	 = " How much does $fcname to $tcname taxi cost?";
		$Ans[]	 = "For one-way cab service with aaocab from $fcname to $tcname cab fare starts from Rs.  $minPrice  and for round trip cab service starting from Rs   $perKmCharge /Km for $fcname to $tcname. For best price on your travel date for various car rental options, please enter trip details and check.";

		$Ques[]	 = "What is the travel distance from $fcname to $tcname?";
		$Ans[]	 = "The travel distance from $fcname to $tcname is approximately  $distance Kms. It takes around " . floor(($rModel->rut_estm_time / 60)) . " hours";

		$Ques[]	 = " What are the options available for $fcname to $tcname by car?";
		$Ans[]	 = "The car options available from $fcname to $tcname by car are 4 seaters AC sedan cars (Dzire, Toyota Etios, Tata Indigo or equivalent) for small group of travellers, 4 seater AC Compact cars (Indica, Swift, Alto, Ford Figo or equivalent) and 7-9 seater AC SUV cars (Inova, Mahindra Xylo, Chevrolet Tavera or equivalent) and 12 to 16 seater AC tempo traveller.";

		$Ques[]	 = " Does price includes Driver charges and Night charges?";
		$Ans[]	 = "Yes, our pricing policy is very transparent. All the prices includes distance, driver allowance, taxes, tolls etc. And if you are travelling in night then night charge would also be included.";

		$Ques[]	 = "Do I need to make payment in advance to book $fcname to $tcname cab?";
		$Ans[]	 = "Yes, You need to just pay 10%-15% advance to book your hassle free and reliable $fcname to $tcname cab service at your doorstep.";

		$faq		 = new \Spatie\SchemaOrg\FAQPage();
		$arrayEntity = [];
		foreach ($Ques as $key => $que)
		{
			$arrayEntity[] = Schema::question()
					->name($que)
					->acceptedAnswer(
					Schema::answer()
					->text($Ans[$key])
			);
		}
		$faq->mainEntity($arrayEntity);
		return $faq;
	}

	/**
	 * Function to build the Structured Markup Data for Outstation Cabs as defined by schema.org
	 * @param $fcity 
	 */
	public static function getOutstationSchema($fcity)
	{
		Logger::setCategory('models.StructuredData.getOutstationSchema');
		Logger::profile("  start");
		$key	 = "StructureData::getOutstationSchema_{$fcity}";
		$data	 = Yii::app()->cache->get($key);
		if ($data !== false)
		{
			$taxiService = $data;
			goto result;
		}
		// Vehicle Model Types
		$allowedCabCategories	 = [1, 2, 3];
		$arrVehicleModels		 = VehicleTypes::getMasterDetails($allowedCabCategories);

		$tripType = [
			1	 => "One Way",
			2	 => "Round Trip"];

		$markup			 = 1.15;
		$itemOffered	 = [];
		$offerCatalog	 = [];
		$tripTypeKeys	 = array_keys($tripType);
		$prrData		 = PriceRule::getByCityCabTripType($fcity, $allowedCabCategories, $tripTypeKeys);
		foreach ($tripType as $tKey => $tripName)
		{
			$itemOffered = [];
			foreach ($allowedCabCategories as $cabKey)
			{
				$prrate			 = $prrData[$cabKey][$tKey];
				$raw_ratePerKm	 = $prrate['prr_rate_per_km'] * $markup;
				$ratePerKm		 = (round($raw_ratePerKm * 2)) / 2;
//				$ratePerKm		 = $prrate['prr_rate_per_km_extra'];
				$vctData		 = $arrVehicleModels[$cabKey];
				$itemOffered[]	 = Schema::offer()->name('Cab Service')
						->itemOffered(
								Schema::car()
								->vehicleSeatingCapacity($vctData['vct_capacity'])
								->category($vctData['vct_label'])
								->model($vctData['vct_desc']))
						->priceSpecification(Schema::priceSpecification()
						->price($ratePerKm)
						->priceCurrency("INR")
						->eligibleQuantity(
								Schema::quantitativeValue()
								->unitText("KM")));
			}
			$offerCatalog [] = Schema::offerCatalog()
					->name($tripName)
					->itemListElement(
					$itemOffered
			);
		}

		$taxiService = StructureData::getSchemaForOutstation($fcity, 'OutStation Cabs');

		$taxiService->hasOfferCatalog(
				Schema::offerCatalog()
						->itemListElement($offerCatalog)
		);

		Yii::app()->cache->set($key, $taxiService, 24 * 30 * 60 * 60, new CacheDependency("Schema"));

		result:
		Logger::profile("  end");
		Logger::unsetCategory('models.StructuredData.getOutstationSchema');
		return json_encode($taxiService, JSON_UNESCAPED_SLASHES);
	}

	/**
	 * Function for getting the Structured Markup Data as defined by schema.org
	 * @param $cityId
	 * @param $schemaDesc
	 */
	public static function getSchemaForOutstation($cityId, $schemaDesc)
	{
		$key		 = "StructureData::getSchemaForOutstation{$cityId}_{$schemaDesc}";
		$taxiService = Yii::app()->cache->get($key);
		if ($taxiService !== false)
		{
			goto result;
		}

		$cityModel	 = Cities::getCityShortDetailbyid($cityId); //Cities::model()->findByPk($cityId);
		/** @var Cities $cityModel */
		$cty_name	 = $cityModel['cty_name'];

		// Main Node
		$taxiService = new \Spatie\SchemaOrg\TaxiService();

		$taxiService->name($cty_name . " $schemaDesc");

		$taxiService
				->provider(StructureData::getOrganisation())
				->areaServed(StructureData::administrativeArea($cityModel));
		Yii::app()->cache->set($key, $taxiService, 24 * 15 * 60 * 60, new CacheDependency("Schema"));

		result:
		return $taxiService;
	}

	public static function getProductSchemaforOutstation($city)
	{
		Logger::setCategory('models.StructuredData.getOutstationCabsSchema');
		Logger::profile("  start getProductSchemaforOutstation");
		$key		 = "StructureData::getProductSchemaforOutstation{$city}";
		$products	 = Yii::app()->cache->get($key);
		if ($products !== false)
		{
			goto result;
		}

		$allowedCabCategories	 = [1, 2, 3];
		$tripType				 = [1 => "One Way"];
		$tripTypeKeys			 = array_keys($tripType);
		$prrData				 = PriceRule::getByCityCabTripType($city, $allowedCabCategories, $tripTypeKeys);
		$fcitydata				 = Cities::getCityShortDetailbyid($city);
		$products				 = [];

		$baseUrl			 = Yii::app()->params['fullBaseURL'];
		$getOrganisation	 = StructureData::getOrganisation();
		//->image($baseUrl . "/images/logo2_outstation.png")
		$description		 = "Aao cab is the best rated AC cab services with driver for all India. Book in advance for cheapest prices. 24 x 7 customer support by web chat or phone. One way cab services. Round trips. Airport transfers. Package tours. Shared Taxi. Intercity Shuttle service. More comfortable &; cheaper than going by bus or train. Serving in 3000+ cities. Great Price & Top Quality guaranteed.";
		$areaServed			 = StructureData::administrativeArea($fcitydata);
		$cityreview			 = StructureData::getCityReview($city, 2);
		$aggregateRating	 = StructureData::getCityAggregateRating();
		$cty_name			 = $fcitydata['cty_name'];
		$arrVehicleModels	 = VehicleTypes::getMasterDetails();

		foreach ($tripType as $tKey => $tVal)
		{
			$product	 = StructureData::generateProductOutstationSchema($cty_name, $tKey, $tVal, $allowedCabCategories, $prrData, $arrVehicleModels);
			$product
					//->brand($getOrganisation)
					->image($baseUrl . "/images/car-rental.jpg")
					//->areaServed($areaServed)
					->review($cityreview)
					->sku("InStock")
					->description($description)
					->aggregateRating($aggregateRating);
			$products	 = $product;
		}

		Yii::app()->cache->set($key, $products, 24 * 30 * 60 * 60, new CacheDependency("Schema"));

		result:
		Logger::profile("  end");
		Logger::unsetCategory('models.StructuredData.getOutstationCabsSchema');
		return json_encode($products, JSON_UNESCAPED_SLASHES);
	}

	public static function generateProductOutstationSchema($cty_name, $tKey, $tVal, $allowedCabCategories, $prrData, $arrVehicleModels)
	{

		$baseUrl = Yii::app()->params['fullBaseURL'];
		$markup	 = 1.15;

		$offers = [];
		foreach ($allowedCabCategories as $cabKey)
		{
			$prrate	 = $prrData[$cabKey][$tKey];
			$vctData = $arrVehicleModels[$cabKey];

			$raw_ratePerKm	 = $prrate['prr_rate_per_km'] * $markup;
			$ratePerKm		 = (round($raw_ratePerKm * 2)) / 2;

			$priceSpecification = StructureData::generatePriceSpecificationBody($prrate, $tKey, $markup);

			$offers[] = Schema::offer()
					->name($vctData['vct_label'])
					->price($ratePerKm)
					->priceCurrency("INR")
					->priceValidUntil(date('Y-m-d', strtotime('+3 month')))
					->availability("InStock")
					->url($baseUrl . '/outstation-cabs/' . $cty_name)
					->priceSpecification($priceSpecification);
		}
		$product = new \Spatie\SchemaOrg\Product();
		$product->name($cty_name . " Outstation Cabs")
				->category($cty_name . " Outstation Cabs");
		$product->offers($offers);
		return $product;
	}

}
