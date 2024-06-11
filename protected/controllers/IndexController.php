<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class IndexController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $newHome				 = '';
	public $layout				 = '//layouts/column1';
	public $fileatt;
	public $email_receipient;
	public $pageHeader			 = '';
	public $showProfileComplete	 = true;

	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter',
				'bypass' => false),
			//'postOnly + agentjoin,vendorjoin',
			//'postOnly + agentjoin',
			array(
				'CHttpCacheFilter + country',
				'lastModified' => $this->getLastModified(),
			),
//			array(
//				'COutputCache + cities',
//				'duration'			 => 60 * 60 * 24 * 7,
//				'varyByExpression'	 => 'Filter::checkTheme()',
//				'varyByParam'		 => array('city'),
//				'varyByRoute'		 => true,
//				'requestTypes'		 => ['GET'],
//				'dependency'		 => new CacheDependency("CarRentalPage")
//			),
			array(
				'COutputCache + onewayCabs',
				'duration'			 => 60,
				'varyByExpression'	 => 'Filter::checkTheme()',
				'varyByRoute'		 => true,
				'requestTypes'		 => ['GET'],
				'dependency'		 => new CacheDependency("HomePage")
			),
		);
	}

	function getLastModified()
	{
		$date = new DateTime('NOW');
		$date->sub(new DateInterval('PT50S'));
		return $date->format('Y-m-d H:i:s');
	}

	/**
	 * Declares class-based actions.
	 */
	public function actionIndex()
	{
		#Logger::setCategory("warning.module.index.index");
		$request = Yii::app()->request;
		VisitorTrack::track(CJSON::encode($_REQUEST), $request->getRequestType(), "", BookFormRequest::URL_HOME);
		Logger::profile("Start");

		$fullUrl	 = Yii::app()->createAbsoluteUrl(Yii::app()->request->url);
		$rutId		 = Yii::app()->request->getParam('rutId');
		$refSource	 = Yii::app()->request->getParam('s');
		$fromCity	 = Yii::app()->request->getParam('fromCity');
		$toCity		 = Yii::app()->request->getParam('toCity');
		$url		 = Yii::app()->request->requestUri;
		$url_arr	 = explode("/", $url);
		Logger::profile("Request Initialized");
		$code		 = Yii::app()->request->getParam('sid');
		$location	 = Yii::app()->request->getParam('location');
		if ($code != '')
		{
			$qrDetails = QrCode::getAgentId($code);
			if ($qrDetails)
			{
//				$updatedCount								 = QrCode::updateScannedCount($qrDetails['qrc_id']);
				QrCode::updateClickCount($qrDetails['qrc_id']);
				$qrc_id										 = $qrDetails['qrc_id'];
				$objCookie									 = new CHttpCookie('gozo_qr_id', $qrc_id);
				$objCookie->expire							 = time() + 86400;
				Yii::app()->request->cookies['gozo_qr_id']	 = $objCookie;
				unset(Yii::app()->request->cookies['gozo_agent_id']);
			}
			else
			{
				unset(Yii::app()->request->cookies['gozo_qr_id']);
			}
		}
		else
		{
			unset(Yii::app()->request->cookies['gozo_qr_id']);
		}
		if (isset($_REQUEST['agent_id']))
		{
			$agent_id = $_REQUEST['agent_id'];
			if ($agent_id != '')
			{
				Yii::app()->request->cookies['gozo_agent_id'] = new CHttpCookie('gozo_agent_id', $agent_id);
			}
			else
			{
				Yii::app()->request->cookies['gozo_agent_id'] = new CHttpCookie('gozo_agent_id', NULL);
			}
		}
		end:

		Filter::setReferrer($refSource);
		//Logger::create("ACTION START: " . Filter::getExecutionTime());
		$model							 = new BookingTemp('new');
		$model->loadDefaults();
		$this->newHome					 = true;
		$ptime							 = date('h:i A', strtotime('+4 hour'));
		$this->pageTitle				 = 'Gozocabs: Book outstation cabs and local car rentals online in India';
		$this->metaKeywords				 = 'car rental, taxi service, cab booking, airport transfer, city tour, long-distance trip, One way cab services, outstation taxi, local taxi, oneway, inter city taxi service, Car Hire, Taxi Service, Cab Service, Car Rental India, Online Cab Booking, Online Taxi Booking, Local Taxi Service, Cheap Car Rental, Car Rentals India, Taxi Booking India, Online Car Rentals, Book A Taxi, Book A Cab, Car Rentals Agency India, Car Rent In India, Corporate Car Rental India, Car Rental Company In India, reliable cab service, affordable car rental';
		Logger::beginProfile("StructureData::providerDetails");
		$providerStructureMarkupData	 = StructureData::providerDetails();
		$jsonproviderStructureMarkupData = json_encode($providerStructureMarkupData, JSON_UNESCAPED_SLASHES);
		Logger::endProfile("StructureData::providerDetails");
		$GLOBALS["prefetch"]			 = "home";
		$this->render('home', array('model'								 => $model,
			'jsonproviderStructureMarkupData'	 => $jsonproviderStructureMarkupData));
	}

	public function actionCPLanding()
	{
		$id		 = Yii::app()->request->getParam('id');
		$hash	 = Yii::app()->request->getParam('hash');
		$hashID	 = Yii::app()->shortHash->hash($id);
		if ($hash == $hashID)
		{
			Yii::app()->request->cookies['gozo_agent_id'] = new CHttpCookie('gozo_agent_id', $id);
			$this->render('cp_landing', array('agent_id' => $id));
		}
	}

	public function actionConst()
	{
		$ev = BookingCab::model()->tripStatus;
		foreach ($ev as $k => $v)
		{
			$evName = str_replace(' ', '_', strtoupper($v));
			echo 'const STATUS_' . $evName . ' = ' . $k . '; <br>';
		}
	}

	public function actionAboutus()
	{

		$this->pageTitle		 = "About Us";
		$this->metaDescription	 = "Gozo Cabs is the best in price & service for outstation taxi travel in India. Book One-way, Round trips, Airport transfers, Package tours & shared taxi service";
		$type					 = Yii::app()->request->getParam('app');
		if ($type == 1)
		{
			$this->layout = "head";
		}
		$this->render('aboutus', array());
	}

	public function actionSignin()
	{

		$this->pageTitle		 = "Sign In";
		$this->metaDescription	 = "Gozo Cabs is the best in price & service for outstation taxi travel in India. Book One-way, Round trips, Airport transfers, Package tours & shared taxi service";
		$type					 = Yii::app()->request->getParam('app');
		if ($type == 1)
		{
			$this->layout = "head";
		}
		$this->render('signin', array());
	}

	public function actionAboutusapp()
	{
		$this->layout	 = 'head';
		$this->pageTitle = "About Us";

		$this->render('aboutus', array('app' => true));
	}

	public function actionWidgets()
	{
		$this->layout	 = 'head';
		$this->pageTitle = "Widgets";

		$this->render('widgets', array('app' => true));
	}

	public function actionGozonow()
	{
		if ($type == 1)
		{
			$this->layout = "head";
		}
		$this->checkV3Theme();
		$this->pageTitle = "Gozo Now";

		$this->render('gozonow', array());
	}

	public function actionFlexxi()
	{
		$this->redirect(['share']);
	}

	public function actionShare()
	{
		$this->checkV2Theme();
		$this->pageTitle = "Solo traveler? Go FLEXXI SHARE. Save money. Save the environment.";
		$this->render('goFLEXXI', array());
	}

	public function actionAirportTransfers()
	{
		$arrData = [];
		$this->checkV3Theme();

		$this->ampPageEnabled	 = 1;
		$city					 = Yii::app()->request->getParam('city');
		if ($city == 'bengaluru')
		{
			$city = 'bangalore';
		}
		$cmodel			 = Cities::model()->getByCity2($city);
		$selected_cities = array('Delhi', 'Mumbai', 'Hyderabad', 'Chennai', 'Bangalore', 'Pune', 'Goa', 'Jaipur', 'Bengaluru');
		if (in_array($cmodel['cty_name'], $selected_cities, TRUE))
		{
			$cmodel->is_luxury_city = 1;
		}
		if ($cmodel->cty_has_airport != 1)
		{
			goto noAirport;
		}
		$airport_location	 = $city . ' Airport';
		$placeObj			 = \Stub\common\Place::init($cmodel->cty_lat, $cmodel->cty_long);
		$res				 = Cities::getNearestAirports($placeObj);
		if ($res->count() == 0)
		{
			goto noAirport;
		}

		$res->next();
		$row = $res->current();

		$model				 = Cities::model()->findByPK($row["cty_id"]);
		$airport_id			 = $model->cty_id;
		$airport_name		 = $model->cty_name;
		$airport_path		 = $model->cty_alias_path;
		$model				 = new BookingTemp('Route');
		$GLOBALS['ctyName']	 = $cmodel->cty_name;
		$topCitiesKm		 = Cities::model()->getTopCitiesByKm($airport_id, 1000, 5);

		$new_city_model = Cities::model()->getByCity2($topCitiesKm[0]['cty_alias_path']);

		$localDestinationId					 = $new_city_model->cty_id;
		//start for local price
		$bookingRoute						 = new BookingRoute();
		$bookingRoute->brt_from_city_id		 = $airport_id;
		$bookingRoute->brt_to_city_id		 = $localDestinationId;
		//$duration							 = Route::model()->getRouteDurationbyCities($airport_id, $localDestinationId);
		//$bookingRoute->brt_trip_duration	 = $duration;
		$bookingRoute->brt_pickup_datetime	 = date("Y-m-d 07:00:00", strtotime("+8 day"));
		$bookingRoutes[]					 = $bookingRoute;
		$partnerId							 = Yii::app()->params['gozoChannelPartnerId'];
		$quote								 = new Quote();
		$quote->routes						 = $bookingRoutes;
		$quote->tripType					 = 4;
		$quote->suggestedPrice				 = 1;
		$quote->partnerId					 = $partnerId;
		$quote->quoteDate					 = date("Y-m-d H:i:s");
		$quote->pickupDate					 = $bookingRoute->brt_pickup_datetime;
		//$quote->setCabTypeArr(NULL, true);

			$bookingRoute->calculateDistance();
		$routeQuot = $quote->getQuote([2, 3, 73, 74]);

		$suvValuePrice		 = ($routeQuot[2]->success && $routeQuot[2]->routeRates->baseAmount > 0) ? $routeQuot[2]->routeRates->baseAmount : 0;
		$sedanValuePrice	 = ($routeQuot[3]->success && $routeQuot[3]->routeRates->baseAmount > 0) ? $routeQuot[3]->routeRates->baseAmount : 0;
		$suvEconomyPrice	 = ($routeQuot[74]->success && $routeQuot[74]->routeRates->baseAmount > 0) ? $routeQuot[74]->routeRates->baseAmount : 0;
		$sedanEconomyPrice	 = ($routeQuot[73]->success && $routeQuot[73]->routeRates->baseAmount > 0) ? $routeQuot[73]->routeRates->baseAmount : 0;

		$suvValueRateKm		 = ($routeQuot[2]->routeRates->ratePerKM > 0) ? $routeQuot[2]->routeRates->ratePerKM : 0;
		$sedanValueRateKm	 = ($routeQuot[3]->routeRates->ratePerKM > 0) ? $routeQuot[3]->routeRates->ratePerKM : 0;
		$suvEconomyRateKm	 = ($routeQuot[74]->routeRates->ratePerKM > 0) ? $routeQuot[74]->routeRates->ratePerKM : 0;
		$sedanEconomyRateKm	 = ($routeQuot[73]->routeRates->ratePerKM > 0) ? $routeQuot[73]->routeRates->ratePerKM : 0;

		$localPrice					 = [];
		$localPrice['suv']			 = min(array_filter([$suvValuePrice, $suvEconomyPrice]));
		$localPrice['sedan']		 = min(array_filter([$sedanValuePrice, $sedanEconomyPrice]));
		$localPrice['tripDistance']	 = ($routeQuot[3]->routeDistance->tripDistance > 0) ? $routeQuot[3]->routeDistance->tripDistance : 0;
		//$localPrice['tripTime']			 = $routeQuot[$cabId]->routeDuration->durationInWords;
		$localPrice['extraKmRate']	 = min(array_filter([$suvValueRateKm, $sedanValueRateKm, $suvEconomyRateKm, $sedanEconomyRateKm]));
		// top route outside
		#print_r($airportGetQuote);
		$airportGetQuote			 = Yii::app()->cache->get("airportGetQuote2__" . $cmodel->cty_id);

		if ($airportGetQuote)
		{
			$topTenRoutes = json_decode($airportGetQuote, true);
		}
		else
		{
			$topRoutes = Route::getRoutesByAirportid($cmodel->cty_id);

			$ctr			 = 0;
			$topTenRoutes	 = [];
			$pickupDate		 = date("Y-m-d 06:00:00", strtotime("+8 DAY"));

			if (count($topRoutes) > 0)
			{
				foreach ($topRoutes as $topRoute)
				{
					$route							 = [];
					$routeModel						 = new BookingRoute();
					$routeModel->brt_from_city_id	 = $airport_id;
					$routeModel->brt_to_city_id		 = $topRoute['rut_to_city_id'];
					//$distanceModel					 = Cities::model()->getDistTimeByFromCityAndToCity($airport_name, $topRoute['to_city']);

					$routeQuot = Route::getBasicOnewayQuote($routeModel->brt_from_city_id, $routeModel->brt_to_city_id);

					$suvValuePrice		 = ($routeQuot[2]->success && $routeQuot[2]->routeRates->baseAmount > 0) ? $routeQuot[2]->routeRates->baseAmount : 0;
					$sedanValuePrice	 = ($routeQuot[3]->success && $routeQuot[3]->routeRates->baseAmount > 0) ? $routeQuot[3]->routeRates->baseAmount : 0;
					$suvEconomyPrice	 = ($routeQuot[74]->success && $routeQuot[74]->routeRates->baseAmount > 0) ? $routeQuot[74]->routeRates->baseAmount : 0;
					$sedanEconomyPrice	 = ($routeQuot[73]->success && $routeQuot[73]->routeRates->baseAmount > 0) ? $routeQuot[73]->routeRates->baseAmount : 0;

					$suvValueRateKm		 = ($routeQuot[2]->routeRates->ratePerKM > 0) ? $routeQuot[2]->routeRates->ratePerKM : 0;
					$sedanValueRateKm	 = ($routeQuot[3]->routeRates->ratePerKM > 0) ? $routeQuot[3]->routeRates->ratePerKM : 0;
					$suvEconomyRateKm	 = ($routeQuot[74]->routeRates->ratePerKM > 0) ? $routeQuot[74]->routeRates->ratePerKM : 0;
					$sedanEconomyRateKm	 = ($routeQuot[73]->routeRates->ratePerKM > 0) ? $routeQuot[73]->routeRates->ratePerKM : 0;

					$topTenRoutes[$ctr]['from_city']		 = $topRoute['from_city'];
					$topTenRoutes[$ctr]['to_city']			 = $topRoute['to_city'];
					$topTenRoutes[$ctr]['brt_to_city_id']	 = $routeModel->brt_to_city_id;
					$topTenRoutes[$ctr]['rut_name']			 = $topRoute['rut_name'];
					if($topRoute['rut_estm_distance']>0)
					{
						$topTenRoutes[$ctr]['distance']		 = $topRoute['rut_estm_distance'];
					}
					else 
					{
						foreach($routeQuot as $qtVal){
							if($qtVal->routeDistance->tripDistance > 0)
							{
								$topTenRoutes[$ctr]['distance']	= $qtVal->routeDistance->tripDistance;
								break;
							} 
						}
					}
					
					$topTenRoutes[$ctr]['extraKmRate']	 = min(array_filter([$suvValueRateKm, $sedanValueRateKm, $suvEconomyRateKm, $sedanEconomyRateKm]));
					//$topTenRoutes[$ctr]['duration']			 = $routeQuot[$cabId]->routeDuration->durationInWords;
					$topTenRoutes[$ctr]['suv_price']	 = min(array_filter([$suvValuePrice, $suvEconomyPrice]));
					$topTenRoutes[$ctr]['seadan_price']	 = min(array_filter([$sedanValuePrice, $sedanEconomyPrice]));

					$topTenRoutes[$ctr]['destination_city_has_airport'] = Cities::model()->getCityhasairport($topRoute['to_city']);

					$ctr = ($ctr + 1);
				}
				Yii::app()->cache->set("airportGetQuote2__" . $cmodel->cty_id, json_encode($topTenRoutes), 60 * 60 * 24 * 7, new CacheDependency('airportGetQuote'));
			}
		}
		Logger::trace("TopTenRoutes" . json_encode($topTenRoutes));
		$this->pageTitle					 = "Book " . $cmodel->cty_name . " Airport Transfer Cabs Online";
		$this->metaDescription				 = "Hire " . $cmodel->cty_name . " airport transfer cabs from or to " . $cmodel->cty_name . " airport with Gozo cabs. Book the cheapest and best " . $cmodel->cty_name . " airport taxi service with Gozocabs.";
		$airportSchemaStructureMarkupData	 = StructureData::getAirportSchema($airport_id);
		$breadcrumbStructureMarkupData		 = StructureData::breadCrumbSchema($airport_id, '', 'airport_transfer');
		$organisationSchemaRaw				 = StructureData::getOrganisation();
		$organisationSchema					 = json_encode($organisationSchemaRaw, JSON_UNESCAPED_SLASHES);
		if ($cmodel)
		{
			$arrData = array(
				'model'								 => $model,
				'cmodel'							 => $cmodel,
				'brtModel'							 => $routeModel,
				'topTenRoutes'						 => $topTenRoutes,
				'topRoutes'							 => $topRoutes,
				'topCitiesByRegion'					 => $topCitiesByRegion,
				'topCitiesKm'						 => $topCitiesKm,
				'localPrice'						 => $localPrice,
				'airport_path'						 => $airport_path,
				'airportSchemaStructureMarkupData'	 => $airportSchemaStructureMarkupData,
				'breadcrumbStructureMarkupData'		 => $breadcrumbStructureMarkupData,
				'organisationSchema'				 => $organisationSchema,
				'count'								 => $count,
				'type'								 => 'city'
			);
		}

		noAirport:
		$this->render('airport-transfers', $arrData);
	}

	public function actionRefer()
	{
		$this->checkV2Theme();
		$this->pageTitle = "Refer Friend";
		$this->render('refer_page', array());
	}

	public function actionPricematch()
	{
		$this->checkV2Theme();
		$this->render('price_match_guarantee', array());
	}

	public function actionGo4Rs1()
	{
		$this->checkV2Theme();
		$this->render('go4Rs1', array());
	}

	public function actionMumbai()
	{
		$this->redirect('/');
	}

	public function actionTchallenge()
	{
		$this->checkV2Theme();
		$this->render('take_the_challenge', array());
	}

	public function actionTravelhappy()
	{
		$this->render('travel_happy', array());
	}

	public function actionChangeindia()
	{
		$this->checkV2Theme();
		$this->render('change_india', array());
	}

	public function actionOfferType()
	{
		$this->checkV3Theme();
		$this->render('offer-type', array());
	}

	public function actionContactus()
	{
		$this->pageTitle = "Contact Us";
		$type			 = Yii::app()->request->getParam('app');
		if ($type == 1)
		{
			$this->layout = "head";
		}
		$organisationSchemaRaw	 = StructureData::getContactUsSchema();
		$organisationSchema		 = json_encode($organisationSchemaRaw, JSON_UNESCAPED_SLASHES);

		$this->render('contactus', array(
			'organisationSchema' => $organisationSchema));
	}

	public function actionNewsroom()
	{
		$this->pageTitle = "News Room";
		$this->render('newsroom', array());
	}

	public function actionGozoCalendar()
	{
		$this->checkV2Theme();
		$this->pageTitle = "Gozo Calendar";
		$user			 = new Users();
		$success		 = false;
		$this->render('gozocalendar', array('userModel' => $user, 'success' => $success));
	}

	public function actionContestTerms()
	{
		$this->pageTitle = "Contest Terms";
		$user			 = new Users();
		$success		 = false;
		$this->render('contest-terms', array('userModel' => $user, 'success' => $success));
	}

	public function actionGozoCalendarData()
	{
		$name		 = Yii::app()->request->getParam('user_name');
		$email		 = Yii::app()->request->getParam('user_email');
		$user_name	 = explode(" ", $name);
		$first_name	 = $user_name[0];
		$last_name	 = $user_name[1];
		$user		 = Users::model()->findByEmail($email);
		if (!$user)
		{
			$user						 = new Users('insert');
			$user->usr_name				 = trim($first_name);
			$user->usr_lname			 = trim($last_name);
			$user->usr_email			 = trim($email);
			$password					 = md5(rand());
			$user->usr_password			 = $password;
			$user->repeat_password		 = $password;
			$user->usr_active			 = 1;
			$user->usr_create_platform	 = Users::Platform_Web;
			$user->usr_ip				 = \Filter::getUserIP();
			$user->usr_device			 = UserLog::model()->getDevice();
			$user->usr_created_at		 = new CDbExpression('NOW()');
			$jsonObj					 = new stdClass();
			$jsonObj->profile->firstName = trim($first_name);
			$jsonObj->profile->lastName	 = trim($last_name);
			$jsonObj->profile->email	 = trim($email);

			$returnSet				 = Contact::createContact($jsonObj, 0, UserInfo::TYPE_CONSUMER);
			$user->usr_contact_id	 = $returnSet->getData()['id'];
			$success				 = $user->insert('insert');
		}
		else
		{
			$success = true;
		}
		$data = ['success' => $success];
		echo json_encode($data);
	}

	public function actionTestimonial()
	{
		$this->checkV3Theme();
		$this->pageTitle		 = "Testimonials";
		$this->metaDescription	 = "Gozo cabs offers most comfortable and cheapest outstation cab service to all over India with utmost care for every passenger. Get 24 x 7 customer support.";
		$dataprovider			 = Ratings::model()->getTopRatings();
		$models					 = $dataprovider->getData();
		$this->render('testimonial', array('model' => $models, 'usersList' => $dataprovider->getPagination()));
	}

	public function actionCities()
	{
		$this->checkV2Theme();
		$this->ampPageEnabled	 = 1;
		$city					 = Yii::app()->request->getParam('city');

		/* @var $cmodel  Cities */
		$model	 = new BookingTemp('Route');
		$model->loadDefaults();
		$cmodel	 = Cities::model()->getByCity2($city);

		$catid = 1;

		if ($cmodel)
		{
			goto skipSearch;
		}
		$res	 = Cities::getByMatchingKeyword($city);
		$alias	 = "";
		foreach ($res as $row)
		{
			if ($alias == "")
			{
				$alias = $row["cty_alias_path"];
			}

			if ($row["cty_service_active"] == 1)
			{
				$alias = $row["cty_alias_path"];
				break;
			}
		}

		if ($alias != "")
		{
			Logger::trace("redirecting $city to $alias");
			Logger::warning("Redirecting car rental page to nearest alias", true);
			$this->redirect(["index/cities", "city" => $alias], true, 301);
		}


		throw new CHttpException(404, "City not found", ReturnSet::ERROR_NO_RECORDS_FOUND);
		skipSearch:
		$GLOBALS['ctyName'] = $cmodel->cty_name;

		$topRoutes	 = Route::model()->getRoutesByCityId($cmodel->cty_id);
		$topCitiesKm = Cities::model()->getTopCitiesByKm($cmodel->cty_id, 100, 5);

		// city wise structured data start for data markup
		$cityJsonStructureMarkupData	 = StructureData::getCarRentalSchema($cmodel->cty_id);
		$cityJsonStructureProductSchema	 = StructureData::getProductSchemaforCity($cmodel->cty_id);
		//rating and count
		$ratingCountArr					 = Ratings::getCitySummary($cmodel->cty_id);
		Logger::profile(__FILE__ . " (" . __LINE__ . ")");

		//provider structured data for markup
		#$providerStructureMarkupData		 = StructureData::providerDetails();
		#$jsonproviderStructureMarkupData	 = json_encode($providerStructureMarkupData, JSON_UNESCAPED_SLASHES);
//		$jsonproviderStructureMarkupData	 = '';
		// breadcumbwise structure data dor markup
		$cityBreadcumbStructureMarkupData = StructureData::breadCrumbSchema($cmodel->cty_id);

		$ctr			 = 0;
		$topTenRoutes	 = [];
		$pickupDate		 = date("Y-m-d 06:00:00", strtotime("+8 DAY"));

		if (count($topRoutes) > 0)
		{
			$key			 = md5(json_encode($topRoutes));
			$topTenRoutes	 = Yii::app()->cache->get($key);
			if ($topTenRoutes)
			{
				goto result;
			}
			foreach ($topRoutes as $topRoute)
			{
				$routeQuot = Route::getBasicOnewayQuote($topRoute['rut_from_city_id'], $topRoute['rut_to_city_id']);

				$compactPrice		 = ($routeQuot[1]->success && $routeQuot[1]->routeRates->baseAmount > 0) ? $routeQuot[1]->routeRates->baseAmount : 0;
				$suvPrice			 = ($routeQuot[2]->success && $routeQuot[2]->routeRates->baseAmount > 0) ? $routeQuot[2]->routeRates->baseAmount : 0;
				$sedanPrice			 = ($routeQuot[3]->success && $routeQuot[3]->routeRates->baseAmount > 0) ? $routeQuot[3]->routeRates->baseAmount : 0;
				$tempo9seaterPrice	 = ($routeQuot[7]->routeRates->baseAmount > 0) ? $routeQuot[7]->routeRates->baseAmount : 0;
				$tempo12seaterPrice	 = ($routeQuot[8]->routeRates->baseAmount > 0) ? $routeQuot[8]->routeRates->baseAmount : 0;
				$tempo15seaterPrice	 = ($routeQuot[9]->routeRates->baseAmount > 0) ? $routeQuot[9]->routeRates->baseAmount : 0;

				$compactRateKm		 = ($routeQuot[1]->routeRates->ratePerKM > 0) ? $routeQuot[1]->routeRates->ratePerKM : 0;
				$suvRateKm			 = ($routeQuot[2]->routeRates->ratePerKM > 0) ? $routeQuot[2]->routeRates->ratePerKM : 0;
				$sedanRateKm		 = ($routeQuot[3]->routeRates->ratePerKM > 0) ? $routeQuot[3]->routeRates->ratePerKM : 0;
				$tempo9seaterRateKm	 = ($routeQuot[7]->routeRates->ratePerKM > 0) ? $routeQuot[7]->routeRates->ratePerKM : 0;
				$tempo12seaterRateKm = ($routeQuot[8]->routeRates->ratePerKM > 0) ? $routeQuot[8]->routeRates->ratePerKM : 0;
				$tempo15seaterRateKm = ($routeQuot[9]->routeRates->ratePerKM > 0) ? $routeQuot[9]->routeRates->ratePerKM : 0;

				$topTenRoutes[$ctr]['rut_distance']			 = $topRoute['rut_estm_distance'];
				$topTenRoutes[$ctr]['rut_time']				 = $topRoute['rut_estm_time'];
				$topTenRoutes[$ctr]['from_city']			 = $topRoute['from_city'];
				$topTenRoutes[$ctr]['to_city']				 = $topRoute['to_city'];
				$topTenRoutes[$ctr]['rut_name']				 = $topRoute['rut_name'];
				$topTenRoutes[$ctr]['compact_price']		 = $compactPrice;
				$topTenRoutes[$ctr]['suv_price']			 = $suvPrice;
				$topTenRoutes[$ctr]['seadan_price']			 = $sedanPrice;
				$topTenRoutes[$ctr]['tempo_9seater_price']	 = $tempo9seaterPrice;
				$topTenRoutes[$ctr]['tempo_12seater_price']	 = $tempo12seaterPrice;
				$topTenRoutes[$ctr]['tempo_15seater_price']	 = $tempo15seaterPrice;
				$topTenRoutes[$ctr]['from_city_alias_path']	 = $topRoute['from_city_alias_path'];
				$topTenRoutes[$ctr]['to_city_alias_path']	 = $topRoute['to_city_alias_path'];

				$topTenRoutes[$ctr]['compact_rate_km']		 = $compactRateKm;
				$topTenRoutes[$ctr]['suv_rate_km']			 = $suvRateKm;
				$topTenRoutes[$ctr]['sedan_rate_km']		 = $sedanRateKm;
				$topTenRoutes[$ctr]['tempo9seater_rate_km']	 = $tempo9seaterRateKm;
				$topTenRoutes[$ctr]['tempo12seater_rate_km'] = $tempo12seaterRateKm;
				$topTenRoutes[$ctr]['tempo15seater_rate_km'] = $tempo15seaterRateKm;
				$topTenRoutes[$ctr]['extraKmRate']			 = min(array_filter([$compactRateKm, $suvRateKm, $sedanRateKm, $tempo9seaterRateKm, $tempo12seaterRateKm, $tempo15seaterRateKm]));
				$ctr										 = ($ctr + 1);
			}

			Yii::app()->cache->set($key, $topTenRoutes, 60 * 60 * 24 * 14, new CacheDependency('topRouteQuote'));
		}

		result:
		//**************Daily Rental ************************************//
		$dayRentalArr		 = [];
		$dailyRentalBkgArr	 = array(9, 10, 11);
		foreach ($dailyRentalBkgArr as $dailyRentalBkgType)
		{
			$routeQuot								 = Route::getBasicDailyRentalQuote($cmodel->cty_id, $dailyRentalBkgType);
			$dayRentalArr[$dailyRentalBkgType][1]	 = ($routeQuot[1]->success && $routeQuot[1]->routeRates->baseAmount > 0) ? $routeQuot[1]->routeRates->baseAmount : 0;
			$dayRentalArr[$dailyRentalBkgType][2]	 = ($routeQuot[2]->success && $routeQuot[2]->routeRates->baseAmount > 0) ? $routeQuot[2]->routeRates->baseAmount : 0;
			$dayRentalArr[$dailyRentalBkgType][3]	 = ($routeQuot[3]->success && $routeQuot[3]->routeRates->baseAmount > 0) ? $routeQuot[3]->routeRates->baseAmount : 0;
		}
		//**************Daily Rental ************************************//
		$lowest_price			 = $topTenRoutes[0]['compact_price'];
		//$this->pageTitle = "intercity cab rentals in " . $cmodel->cty_name . " from ₹".$lowest_price."";
		//$this->pageTitle = "Budget and Luxury car rental service in " . $cmodel->cty_name . " - local hourly rentals and outstation drop taxi services";
		$this->pageTitle		 = "Cab service in " . $cmodel->cty_name . " | Easy, Reliable, and Budget-Friendly";
		$this->metaDescription	 = "Reliable and affordable car rental services in " . $cmodel->cty_name . ". Our cabs are available for airport transfers, outstation one-way, round trips, multi-city trips, and local sightseeing at hourly rentals. We offer competitive fares, excellent customer service, and a variety of cabs and services to choose from. Book Now.";
		$this->metaKeywords		 = "cab service in {$cmodel->cty_name}, {$cmodel->cty_name} cab service, cab in {$cmodel->cty_name}, {$cmodel->cty_name} cab, "
				. "taxi in {$cmodel->cty_name}, {$cmodel->cty_name} taxi, airport cab service in {$cmodel->cty_name}, railway station cab service in {$cmodel->cty_name}, "
				. "local cab service in {$cmodel->cty_name}, outstation cab service in {$cmodel->cty_name}, affordable cab service in {$cmodel->cty_name}, "
				. "reliable cab service in {$cmodel->cty_name}, 24/7 cab service in {$cmodel->cty_name}, convenient cab service in {$cmodel->cty_name}, "
				. "online cab booking in {$cmodel->cty_name}, app cab booking in {$cmodel->cty_name}, cab booking in {$cmodel->cty_name}, "
				. "taxi service in {$cmodel->cty_name}, hotel transfer in {$cmodel->cty_name}, app cab service {$cmodel->cty_name}, "
				. "sedan cab service {$cmodel->cty_name}, online cab service {$cmodel->cty_name}, one-way cab service {$cmodel->cty_name}, car rental {$cmodel->cty_name}, rent a car {$cmodel->cty_name}, "
				. "car rental near me, cheap car rental {$cmodel->cty_name}, best car rental {$cmodel->cty_name}, airport car rental {$cmodel->cty_name}, airport car rental {$cmodel->cty_name}, "
				. "long-term car rental {$cmodel->cty_name}, chauffeur driven car rental {$cmodel->cty_name}, innova car rental {$cmodel->cty_name}, affordable car rental in {$cmodel->cty_name}, "
				. "car hire in {$cmodel->cty_name}, round-trip car rental in  {$cmodel->cty_name}, SUV rental in {$cmodel->cty_name}, app-based car rental in  {$cmodel->cty_name}";
		$topCitiesByRegion		 = Cities::model()->getTopCitiesByAllRegion();

		$dayRentalFAQSchema		 = StructureData::dayRentalFAQ();
		$jsonDayRentalFAQSchema	 = json_encode($dayRentalFAQSchema, JSON_UNESCAPED_SLASHES);
		Logger::profile(__FILE__ . " (" . __LINE__ . ")");

		if ($cmodel)
		{
			$catid	 = 1;
			$places	 = CityPlaces::model()->getCityplaces($cmodel->cty_id, $catid);

			if (!empty($places))
			{
				for ($p = 0; $p < count($places); $p++)
				{
					$place[] = $places[$p]->cpl_places;
				}
			}


			$cityModel	 = Cities::model()->getLatLngByCity($cmodel->cty_id);
			$placeObj	 = \Stub\common\Place::init($cityModel['lat'], $cityModel['lng']);
			$res		 = Cities::getNearestAirports($placeObj);
			$airportcity = '';
			foreach ($res as $key => $value)
			{
				if ($key == 0)
				{
					$airportcity .= $value["cty_id"];
				}
			}
			if ($airportcity)
			{
				$airport	 = Cities::model()->getFullName($airportcity);
				$airportRate = PartnerAirportTransfer::getRates(null, $airportcity, 2, 1, 7);
			}


			$priceRule	 = PriceRule::getByCity($cmodel->cty_id, 2, 72, $cmodel->cty_id);
			$ratePerKm	 = $priceRule->attributes['prr_rate_per_km'];

			$this->render('city_details', array(
				'model'								 => $model,
				'cmodel'							 => $cmodel,
				'topTenRoutes'						 => $topTenRoutes,
				'topCitiesByRegion'					 => $topCitiesByRegion,
				'topCitiesKm'						 => $topCitiesKm,
				'ratingCountArr'					 => $ratingCountArr,
				'cityJsonProductSchema'				 => $cityJsonStructureProductSchema,
				'cityJsonMarkupData'				 => $cityJsonStructureMarkupData,
				'cityBreadMarkupData'				 => $cityBreadcumbStructureMarkupData,
				'jsonproviderStructureMarkupData'	 => $jsonDayRentalFAQSchema,
				'type'								 => 'city',
				'place'								 => $place,
				'airport'							 => $airport,
				'airportRate'						 => $airportRate,
				'ratePerKM'							 => $ratePerKm,
				'dayRentalprice'					 => $dayRentalArr)
			);
		}
	}

	public function actioncityRating($city = '')
	{
		$this->checkV3Theme();
		$this->pageTitle = "City Review";
		$pageSize		 = 20;
		try
		{

			$dataCity = [];
			if ($city != '')
			{
				$cModel = Cities::model()->getByCity($city);

				$this->pageTitle = "Customer Reviews - " . $city;
			}

			if (!$cModel)
			{
				throw new CHttpException(404, "Route/City not found", 404);
			}
			else
			{
				$drRatingList = Ratings::getCityList($cModel->cty_id);

				$modelList	 = new CArrayDataProvider($drRatingList, array('pagination' => array('pageSize' => $pageSize, 'route' => 'city-rating/' . $city, 'params' => array('route' => 'default'))));
				$models		 = $modelList->getData();

				$this->render('city_rating', array('model' => $models, 'usersList' => $modelList, 'cityName' => $city));
			}
		}
		catch (Exception $e)
		{
			echo $message = $e->getMessage();
			Logger::trace("Errors.\n\t\t" . $message, CLogger::LEVEL_ERROR);
		}
	}

	public function actionTemporoutes()
	{
		$this->checkV2Theme();

		$this->ampPageEnabled	 = 1;
		$city					 = Yii::app()->request->getParam('city');

		$model	 = new BookingTemp('Route');
		$model->loadDefaults();
		$cmodel	 = Cities::model()->getByCity2($city);

		if ($cmodel)
		{
			goto skipSearch;
		}
		$res	 = Cities::getByMatchingKeyword($city);
		$alias	 = "";
		foreach ($res as $row)
		{
			if ($alias == "")
			{
				$alias = $row["cty_alias_path"];
			}

			if ($row["cty_service_active"] == 1)
			{
				$alias = $row["cty_alias_path"];
				break;
			}
		}

		if ($alias != "")
		{
			Logger::trace("redirecting $city to $alias");
			Logger::warning("Redirecting tempo rental page to nearest alias", true);
			$this->redirect(["index/temporoutes", "city" => $alias], true, 301);
		}

		skipSearch:
		//Yii::app()->cache->flush();
		$tempoGetQuote = Yii::app()->cache->get("tempoGetQuote2__" . $cmodel->cty_id);

		//$tempoGetQuote = false;
		if ($tempoGetQuote)
		{

			$topTenRoutes = json_decode($tempoGetQuote, true);
		}
		else
		{

			if ($cmodel->cty_id > 0)
			{
				$GLOBALS['ctyName'] = $cmodel->cty_name;

				$topRoutes = Route::model()->getRoutesByCityId($cmodel->cty_id);

				$topCitiesKm	 = Cities::model()->getTopCitiesByKm($cmodel->cty_id, 100, 5);
				$topTenRoutes	 = [];
				$pickupDate		 = date("Y-m-d 06:00:00", strtotime("+8 DAY"));
				$ctr			 = 0;

				if (count($topRoutes) > 0)
				{

					foreach ($topRoutes as $topRoute)
					{
						$route								 = [];
						$routeModel							 = new BookingRoute();
						$routeModel->brt_from_city_id		 = $topRoute['rut_from_city_id'];
						$routeModel->brt_to_city_id			 = $topRoute['rut_to_city_id'];
						$routeModel->brt_pickup_datetime	 = $pickupDate;
						$routeModel->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker('2017-09-14 06:00:00');
						$routeModel->brt_pickup_date_time	 = date('h:i A', strtotime($pickupDate));
						$route[]							 = $routeModel;

//                    $quote                            = Quotation::model()->getQuote($route, 1);
						$partnerId				 = Yii::app()->params['gozoChannelPartnerId'];
						$quote					 = new Quote();
						$quote->routes			 = $route;
						$quote->tripType		 = 1;
						$quote->suggestedPrice	 = 1;
						$quote->partnerId		 = $partnerId;
						$quote->quoteDate		 = date("Y-m-d H:i:s");
						$quote->pickupDate		 = $routeModel->brt_pickup_datetime;
						$quote->setCabTypeArr();
						$routeQuot				 = $quote->getQuote();

						$tempo9seaterPrice	 = ($routeQuot[7]->success && $routeQuot[7]->routeRates->baseAmount > 0) ? $routeQuot[7]->routeRates->baseAmount : 0;
						$tempo12seaterPrice	 = ($routeQuot[8]->success && $routeQuot[8]->routeRates->baseAmount > 0) ? $routeQuot[8]->routeRates->baseAmount : 0;
						$tempo15seaterPrice	 = ($routeQuot[9]->success && $routeQuot[9]->routeRates->baseAmount > 0) ? $routeQuot[9]->routeRates->baseAmount : 0;

						$tempo9seaterRateKm	 = ($routeQuot[7]->success && $routeQuot[7]->routeRates->ratePerKM > 0) ? $routeQuot[7]->routeRates->ratePerKM : 0;
						$tempo12seaterRateKm = ($routeQuot[8]->success && $routeQuot[8]->routeRates->ratePerKM > 0) ? $routeQuot[8]->routeRates->ratePerKM : 0;
						$tempo15seaterRateKm = ($routeQuot[9]->success && $routeQuot[9]->routeRates->ratePerKM > 0) ? $routeQuot[9]->routeRates->ratePerKM : 0;

						$topTenRoutes[$ctr]['rut_distance']			 = $topRoute['rut_estm_distance'];
						$topTenRoutes[$ctr]['from_city']			 = $topRoute['from_city'];
						$topTenRoutes[$ctr]['to_city']				 = $topRoute['to_city'];
						$topTenRoutes[$ctr]['rut_name']				 = $topRoute['rut_name'];
						$topTenRoutes[$ctr]['tempo_9seater_price']	 = $tempo9seaterPrice;
						$topTenRoutes[$ctr]['tempo_12seater_price']	 = $tempo12seaterPrice;
						$topTenRoutes[$ctr]['tempo_15seater_price']	 = $tempo15seaterPrice;
						$topTenRoutes[$ctr]['tempo_min_rate']		 = min($tempo9seaterRateKm, $tempo12seaterRateKm, $tempo15seaterRateKm);
						$topTenRoutes[$ctr]['from_city_alias_path']	 = $topRoute['from_city_alias_path'];
						$topTenRoutes[$ctr]['to_city_alias_path']	 = $topRoute['to_city_alias_path'];
						$ctr										 = ($ctr + 1);
					}
				}
				Yii::app()->cache->set("tempoGetQuote2__" . $cmodel->cty_id, json_encode($topTenRoutes), 345600, new CacheDependency('tempoGetQuote'));
			}
			else
			{
				throw new CHttpException(404, "City not found", 404);
			}
		}
		$count = Route::model()->countRouteCities();

		$minTempoRate = (min(array_column($topTenRoutes, 'tempo_min_rate')) > 0) ? min(array_column($topTenRoutes, 'tempo_min_rate')) : 16;

		$this->pageTitle				 = "Best priced Tempo travellers in " . $cmodel->cty_name . " - 9 to 22 seaters starting at Rs. " . $minTempoRate . "/km";
		$this->metaDescription			 = "Tempo Traveller rental with driver for hire in " . $cmodel->cty_name . " (Luxury and Non-Luxury)  8 to 20
	                              Seater starting @ Rs. " . $minTempoRate . " Per km. Get +10% off by booking 10 days ahead and paying in advance";
		$this->metaKeywords				 = " " . $cmodel->cty_name . " cheapest tempo traveller, " . $cmodel->cty_name . " mini bus, " . $cmodel->cty_name . " travel , " . $cmodel->cty_name . " group travel, " . $cmodel->cty_name . " charter bus, " . $cmodel->cty_name . " large group trip, " . $cmodel->cty_name . " tempo traveller
								 for hire, " . $cmodel->cty_name . " mini bus for hire,  book tempo traveller  in " . $cmodel->cty_name . " , tempo traveller 9 seater " . $cmodel->cty_name . " , tempo traveller 10 seater " . $cmodel->cty_name . " , tempo traveller 12 seater " . $cmodel->cty_name . " , tempo traveller 15 seater " . $cmodel->cty_name . " , tempo traveller 18 seater " . $cmodel->cty_name . " , tempo traveller 22 seater " . $cmodel->cty_name . " , tempo traveller 20 seater " . $cmodel->cty_name . " ,
";
		$providerStructureMarkupData	 = StructureData::tempoTravellerProviderDetails($cmodel->cty_name);
		$jsonproviderStructureMarkupData = json_encode($providerStructureMarkupData, JSON_UNESCAPED_SLASHES);
		if ($cmodel)
		{
			$this->render('tempo_route_details', array(
				//$this->render('tempo_route_details', array(
				'model'								 => $model,
				'cmodel'							 => $cmodel,
				'topTenRoutes'						 => $topTenRoutes,
				'topCitiesKm'						 => $topCitiesKm,
				'minTempoRate'						 => $minTempoRate,
				'count'								 => $count,
				'jsonproviderStructureMarkupData'	 => $jsonproviderStructureMarkupData)
			);
		}
	}

	public function actionOutstationroutes()
	{
		$this->checkV2Theme();

		$this->ampPageEnabled	 = 1;
		$city					 = Yii::app()->request->getParam('city');
		$model					 = new BookingTemp('Route');
		$model->loadDefaults();
		$cmodel					 = Cities::model()->getByCity2($city);
		if ($cmodel)
		{
			goto skipSearch;
		}
		$res	 = Cities::getByMatchingKeyword($city);
		$alias	 = "";
		foreach ($res as $row)
		{
			if ($alias == "")
			{
				$alias = $row["cty_alias_path"];
			}

			if ($row["cty_service_active"] == 1)
			{
				$alias = $row["cty_alias_path"];
				break;
			}
		}

		if ($alias != "")
		{
			Logger::trace("redirecting $city to $alias");
			Logger::warning("Redirecting car rental page to nearest alias", true);
			$this->redirect(["index/outstationroutes", "city" => $alias], true, 301);
		}
		throw new CHttpException(404, "City not found", ReturnSet::ERROR_NO_RECORDS_FOUND);
		skipSearch:
		if ($cmodel->cty_id > 0)
		{
			$GLOBALS['ctyName']	 = $cmodel->cty_name;
			$topRoutes			 = Route::model()->getRoutesByCityId($cmodel->cty_id);
			$topCitiesKm		 = Cities::model()->getTopCitiesByKm($cmodel->cty_id, 100, 10);
			$topTenRoutes		 = [];
			$ctr				 = 0;

			$outstationGetQuote = Yii::app()->cache->get("outstationGetQuote1" . $cmodel->cty_id);
			if ($outstationGetQuote)
			{
				goto topRouteResult;
			}
			if (count($topRoutes) > 0)
			{
				foreach ($topRoutes as $topRoute)
				{
					$routeQuot = Route::getBasicOnewayQuote($topRoute['rut_from_city_id'], $topRoute['rut_to_city_id']);

					$flexiPrice			 = ($routeQuot[11]->flexxiRates && $routeQuot[11]->flexxiRates[1]['flexxiBaseAmount'] > 0) ? $routeQuot[11]->flexxiRates[1]['flexxiBaseAmount'] : 0;
					$compactPrice		 = ($routeQuot[1]->success && $routeQuot[1]->routeRates->baseAmount > 0) ? $routeQuot[1]->routeRates->baseAmount : 0;
					$suvPrice			 = ($routeQuot[2]->success && $routeQuot[2]->routeRates->baseAmount > 0) ? $routeQuot[2]->routeRates->baseAmount : 0;
					$sedanPrice			 = ($routeQuot[3]->success && $routeQuot[3]->routeRates->baseAmount > 0) ? $routeQuot[3]->routeRates->baseAmount : 0;
//                    $tempoPrice         = ($routeQuot[4]->routeRates->baseAmount > 0) ? $routeQuot[4]->routeRates->baseAmount : 0;
					$tempo9seaterPrice	 = ($routeQuot[7]->success && $routeQuot[7]->routeRates->baseAmount > 0) ? $routeQuot[7]->routeRates->baseAmount : 0;
					$tempo12seaterPrice	 = ($routeQuot[8]->success && $routeQuot[8]->routeRates->baseAmount > 0) ? $routeQuot[8]->routeRates->baseAmount : 0;
					$tempo15seaterPrice	 = ($routeQuot[9]->success && $routeQuot[9]->routeRates->baseAmount > 0) ? $routeQuot[9]->routeRates->baseAmount : 0;

					$compactRateKm		 = ($routeQuot[1]->routeRates->ratePerKM > 0) ? $routeQuot[1]->routeRates->ratePerKM : 0;
					$suvRateKm			 = ($routeQuot[2]->routeRates->ratePerKM > 0) ? $routeQuot[2]->routeRates->ratePerKM : 0;
					$sedanRateKm		 = ($routeQuot[3]->routeRates->ratePerKM > 0) ? $routeQuot[3]->routeRates->ratePerKM : 0;
					$tempo9seaterRateKm	 = ($routeQuot[7]->routeRates->ratePerKM > 0) ? $routeQuot[7]->routeRates->ratePerKM : 0;
					$tempo12seaterRateKm = ($routeQuot[8]->routeRates->ratePerKM > 0) ? $routeQuot[8]->routeRates->ratePerKM : 0;
					$tempo15seaterRateKm = ($routeQuot[9]->routeRates->ratePerKM > 0) ? $routeQuot[9]->routeRates->ratePerKM : 0;
					//echo $compactRateKm. " - ". $suvRateKm . " - ".$sedanRateKm." - ". $tempo9seaterRateKm ." - ".$tempo12seaterRateKm." - ".$tempo15seaterRateKm;
					//echo "<br>";

					$topTenRoutes[$ctr]['rut_distance']			 = $topRoute['rut_estm_distance'];
					$topTenRoutes[$ctr]['rut_time']				 = $topRoute['rut_estm_time'];
					$topTenRoutes[$ctr]['from_city']			 = $topRoute['from_city'];
					$topTenRoutes[$ctr]['to_city']				 = $topRoute['to_city'];
					$topTenRoutes[$ctr]['rut_name']				 = $topRoute['rut_name'];
					$topTenRoutes[$ctr]['flexi_price']			 = $flexiPrice;
					$topTenRoutes[$ctr]['compact_price']		 = $compactPrice;
					$topTenRoutes[$ctr]['suv_price']			 = $suvPrice;
					$topTenRoutes[$ctr]['seadan_price']			 = $sedanPrice;
					$topTenRoutes[$ctr]['tempo_9seater_price']	 = $tempo9seaterPrice;
					$topTenRoutes[$ctr]['tempo_12seater_price']	 = $tempo12seaterPrice;
					$topTenRoutes[$ctr]['tempo_15seater_price']	 = $tempo15seaterPrice;

					$topTenRoutes[$ctr]['compact_rate_km']		 = $compactRateKm;
					$topTenRoutes[$ctr]['suv_rate_km']			 = $suvRateKm;
					$topTenRoutes[$ctr]['sedan_rate_km']		 = $sedanRateKm;
					$topTenRoutes[$ctr]['tempo9seater_rate_km']	 = $tempo9seaterRateKm;
					$topTenRoutes[$ctr]['tempo12seater_rate_km'] = $tempo12seaterRateKm;
					$topTenRoutes[$ctr]['tempo15seater_rate_km'] = $tempo15seaterRateKm;
					$topTenRoutes[$ctr]['extraKmRate']			 = min(array_filter([$compactRateKm, $suvRateKm, $sedanRateKm, $tempo9seaterRateKm, $tempo12seaterRateKm, $tempo15seaterRateKm]));
					$topTenRoutes[$ctr]['min_rate']				 = min($compactRateKm, $suvRateKm, $sedanRateKm, $tempo9seaterRateKm, $tempo12seaterRateKm, $tempo15seaterRateKm);
					$topTenRoutes[$ctr]['from_city_alias_path']	 = $topRoute['from_city_alias_path'];
					$topTenRoutes[$ctr]['to_city_alias_path']	 = $topRoute['to_city_alias_path'];
					$ctr										 = ($ctr + 1);
				}
			}

			topRouteResult:
			if ($outstationGetQuote == 'false' || $outstationGetQuote == false)
			{
				Yii::app()->cache->set("outstationGetQuote1" . $cmodel->cty_id, json_encode($topTenRoutes), 345600, new CacheDependency('outstationGetQuote'));
			}
			else
			{
				$topTenRoutes = json_decode($outstationGetQuote, true);
			}

			// city wise structured data start for data markup
			$jsonStructureMarkupData				 = StructureData::getOutstationSchema($cmodel->cty_id);
			$jsonStructureProductSchema				 = StructureData::getProductSchemaforOutstation($cmodel->cty_id);
			$outstationBreadcumbStructureMarkupData	 = StructureData::breadCrumbSchema($cmodel->cty_id, '', 'outstation-cabs');
		}
		else
		{
			throw new CHttpException(404, "City not found", 404);
		}
		#$count					 = Route::model()->countRouteCities();

		$this->pageTitle		 = "Book outstation cab with driver in " . $cmodel->cty_name;
		$this->metaDescription	 = "Book outstation cabs at an affordable price. Gozo offers cheap and best outstation AC cab service in India with driver.";
		if ($cmodel)
		{
			$this->render('outstation_route_details', array(
				'model'									 => $model,
				'cmodel'								 => $cmodel,
				'topTenRoutes'							 => $topTenRoutes,
				'topCitiesKm'							 => $topCitiesKm,
				'jsonStructureMarkupData'				 => $jsonStructureMarkupData,
				'jsonStructureProductSchema'			 => $jsonStructureProductSchema,
				'outstationBreadcumbStructureMarkupData' => $outstationBreadcumbStructureMarkupData));
		}
	}

	public function actionRoutes($route)
	{

		$routeMap = ['delhi_airport-chandigarh'	 => 'delhi-chandigarh',
			'delhi-mahakumbh'			 => 'delhi-haridwar',
			'delhi_airport-manali'		 => 'delhi-manali',
			'delhi_airport-mussoorie'	 => 'delhi-mussoorie',
			'delhi_airport-shimla'		 => 'delhi-shimla',
			'delhi_airport-rishikesh'	 => 'delhi-rishikesh',
			'delhi_airport-dehradun'	 => 'delhi-dehradun',
			'delhi_airport-ludhiana'	 => 'delhi-ludhiana',
			'delhi_airport-jaipur'		 => 'delhi-jaipur',
		];
		if (array_key_exists($route, $routeMap))
		{
			$route = $routeMap[$route];
		}
		$rModel = Route::model()->getByName($route);

		if ($rModel == null)
		{
			$this->forward('index/index');
			return;
		}
		/* @var $rModel Route */

// $model = new BookingTemp();
		$model			 = new Booking();
		$cabRate		 = Rate::model()->getCabDetailsbyCities($rModel->rutFromCity->cty_id, $rModel->rutToCity->cty_id);
		$rdesc			 = $rModel->rutFromCity->cty_name . ' <-> ' . $rModel->rutToCity->cty_name;
		$this->pageTitle = $rdesc . " cab | India's leader in outstation cab. Best service. Great reviews. Price guarantee";
		$this->pageDesc	 = $rdesc . " One Way cab services | Call +91 90518-77-000 or Book Online!";

		$model->bkg_route_id		 = $rModel->rut_id;
//  Route::model()->getRutidbyCities($model->bkg_from_city_id, $model->bkg_to_city_id);
		$model->bkg_trip_distance	 = $rModel->rut_estm_distance;
		$model->bkg_trip_duration	 = $rModel->rut_estm_time;
		if (!Yii::app()->user->isGuest)
		{
			$user		 = Yii::app()->user->loadUser();
			$usrResponse = Contact::userMappedToItems($user->user_id, 3);
			if ($usrResponse->getStatus())
			{
				$number		 = $usrResponse->getData()->phone['number'];
				$ext		 = $usrResponse->getData()->phone['ext'];
				$firstName	 = $usrResponse->getData()->phone['firstName'];
				$lastName	 = $usrResponse->getData()->phone['lastName'];
				$email		 = $usrResponse->getData()->email['email'];
			}
			$model->bkg_user_id		 = $user->user_id;
			$model->bkg_user_fname	 = $firstName;
			$model->bkg_user_lname	 = $lastName;
// $model->bkg_status = Booking::STATUS_VERIFY;
			if ($number != '')
			{
				$model->bkg_contact_no	 = $number;
				$model->bkg_country_code = $ext;
			} if ($email != '')
			{
				$model->bkg_user_email = $email;
			}
		}
		$this->render('routes', array('rmodel'		 => $rModel,
			'bmodel'		 => $model, 'route'			 => $route,
			'fcitystate'	 => $rModel->rutFromCity->cty_name . ',' . $rModel->rutFromCity->ctyState->stt_name,
			'tcitystate'	 => $rModel->rutToCity->cty_name . ',' . $rModel->rutToCity->ctyState->stt_name,
			'cabratedata'	 => $cabRate));
	}

	public function actionWhygozo()
	{
		$this->checkV2Theme();

		$this->pageTitle = "Why should you ride with Gozo?";
		$this->render('whygozo', array());
	}

	public function actionreferFriend()
	{
		$this->checkV3Theme();
		if (!Yii::app()->user->isGuest)
		{
			$this->redirect(array('/users/refer/'));
			exit();
		}
		$this->pageTitle = "Refer a friend, get cash back - it's a win-win!";
		$this->render('referFriend', array());
	}

	public function actionCareers()
	{
		$this->pageTitle = "Careers";
		$this->render('careers', array());
	}

	public function actionTerms()
	{
		$this->pageTitle		 = "Terms and Conditions";
		$this->metaDescription	 = "Gozo cabs | Terms & Conditions | Gozocabs.com";
		$model					 = Terms::model()->getText(1);
		$type					 = Yii::app()->request->getParam('app');
		if ($type == 1)
		{
			$this->layout = "head";
		}
		$this->render('term', array('model' => $model));
	}

	public function actionChannelpartner()
	{
		$this->checkV2Theme();
		$this->pageTitle = "Channel Partner";
		$this->render('channelpartner', array());
	}

	public function actionAppChannelpartner()
	{
		$this->checkV2Theme();
		$this->pageTitle = "Channel Partner";
		$this->renderPartial('channelpartner', array());
	}

	public function actionFlexxiterms()
	{
		$this->checkV2Theme();
		$this->pageTitle = "Flexxi Share Terms and Conditions";
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('flexxiterms', array());
	}

	public function actionTms()
	{
		$action		 = "render";
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");

		$this->$method('terms', array(), false, $outputJs);
	}

	public function actionTns()
	{

		$this->pageTitle = "Terms and Conditions";

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$model		 = Terms::model()->getText(1);
		$this->renderPartial('terms', array('model' => $model), false, true);

		//$this->renderPartial('terms1', array());
	}

	public function actionTnsGozoCoin()
	{
		$this->renderPartial('termsGozoCoins', array());
	}

	public function actionDiscAdv()
	{
		$this->renderPartial('termsDiscAdv', array());
	}

	public function actionDiscAdv2p5()
	{
		$this->renderPartial('termsDiscAdv', array('cashbackperc' => 2.5));
	}

	public function actionCashBackAdv()
	{
		$this->renderPartial('termsCashBackAdv', array());
	}

	public function actionCashBackAdv25()
	{
		$this->renderPartial('termsCashBackAdv', array('cashbackperc' => 25));
	}

	public function actionAppCashBackAdv()
	{
		$this->renderPartial('AppCashBackAdv', array());
	}

	public function actionTnsGozoCoins()
	{
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('termsGozoCoins', array(), false, $outputJs);
	}

	public function actionTermsvendor()
	{
		$this->checkV2Theme();
		$this->pageTitle = "Terms and Conditions";
		$model			 = Terms::model()->getText(2);
//$outputJs = Yii::app()->request->isAjaxRequest;
//$method = "render" . ($outputJs ? "Partial" : "");
		$this->renderPartial('termsvendor', array('model' => $model));
	}

	public function actionDisclaimer()
	{
		$this->pageTitle = "Disclaimer";
		$this->render('disclaimer', array());
	}

	public function actionPrivacy()
	{
		$this->pageTitle = "Privacy Policy";
		$type			 = Yii::app()->request->getParam('app');
		if ($type == 1)
		{
			$this->layout = "head";
		}
		$this->render('privacy', array());
	}

	public function actionPrivacymd()
	{
		$this->pageTitle = "Privacy Policy";
		$this->renderPartial('privacy', array());
	}

	public function actionFaqs()
	{
		$this->pageTitle = "Frequently asked questions (FAQ)";
		$getFaqCategory	 = BotFaq::getCategory();
		$this->render('faq', array('getFaqCategory' => $getFaqCategory));
	}

	public function actionDoubleBack()
	{
		$this->pageTitle		 = "DOUBLE BACK Terms and Conditions";
		$this->metaDescription	 = "Gozo cabs provides 100% service guarantee or double back on your money. More terms and details included on this page";
		$this->render('double-back', array());
	}

	public function actionDoubleBackAppView()
	{
		$this->pageTitle		 = "DOUBLE BACK Terms and Conditions";
		$this->metaDescription	 = "Gozo cabs provides 100% service guarantee or double back on your money. More terms and details included on this page";
		$this->renderPartial('double-back-app', array());
	}

	public function actionWin1day()
	{
		$this->pageTitle = "Win a free 1 day rental";
		$msg			 = "";
		if (Yii::app()->user->loadUser() != "")// need to change platform in production
		{

			$user_id	 = Yii::app()->user->loadUser()->user_id;
			$min		 = 200;
			$max		 = 500;
			$randum_coin = random_int($min, $max);

			$check_coin_approval = UserCredits::model()->addCreditsForwinaday($user_id, $randum_coin);

			if ($check_coin_approval != "")
			{
				//Yii::app()->user->setFlash('coin', $randum_coin);
				$msg = 'You are now registered. Just for registering we have added <span style="font-size:20px;" class="orange-color ">' . $randum_coin . '</span> Gozo Coins to your Gozo Cabs account. To redeem your Gozo Coins, login to the Gozo website with the same FB or Google account and create a booking. <br> You have already been registered to win a FREE 1 day trip in a Gozo*. If you win, we will be contacting you by email. ';
			}
			else
			{

				$msg = 'You have already been registered to the Win 1 day program. Only 1 entry is allowed every 30 days.';
			}
		}
		$this->render('win1day', array('message' => $msg, 'title' => $this->pageTitle));
	}

	public function actionOfficialpartner()
	{
		$this->pageTitle		 = "Are you managing an Conference, Tradeshow or some sort of Event?";
		$this->metaDescription	 = "Gozo cab offers full logistics support for your any purpose events in India. Choose the most comfortable and cheapest transportation for your event.";
		$this->render('officialPartner', array());
	}

	public function actionBusinessTravel()
	{
		$this->pageTitle		 = "Join our business travel program";
		$this->metaDescription	 = "GozoCabs offers business travel program which provides cab service to your business, partners, clients, and employees. Join now for extra discounts and benefits.";
		$this->render('businessTravel', array());
	}

	public function actionForStartups()
	{
		$this->pageTitle		 = "Our business travel program - Just for startups";
		$this->metaDescription	 = "Gozo Cabs supports startups. come partner with Gozo. Get discounted trips for your staff and you could co-market with Gozo too.";
		$this->render('forStartups', array());
	}

	public function actionYourTravelDesk()
	{
		$this->pageTitle		 = "Power your travel desk with Gozo ©";
		$this->metaDescription	 = "Power your travel needs with Gozo's travel spot technology and get access to AC taxi for your any taxi needs in over 1000 cities all over India";
		$this->render('yourTravelDesk', array());
	}

	public function actionJoinAgentNetwork()
	{
		$this->pageTitle		 = "Clients traveling in India? Idhar udhar kyu khojo, just get them a Gozo...";
		$this->metaDescription	 = "Travel agents and corporates get low B2B rates and taxi services all over India. Partner with Gozo cabs for great reach, lowest prices and best service.";
		$this->render('joinAgentNetwork', array());
	}

	public function actionBrandPartner()
	{
		$this->pageTitle		 = "Brand Partners";
		$this->metaDescription	 = "Look at some of our brand partners that trust Gozo cabs for there transportation.";
		$this->render('brandPartner', array());
	}

	public function actionpartnersTestimonials()
	{
		$this->checkV2Theme();
		$this->pageTitle		 = "Gozo Partner’s Testimonials";
		$this->metaDescription	 = "Gozo Partner’s Testimonials";
		$this->render('partnersTestimonials', array());
	}

	public function actionEarn10k()
	{
		$this->pageTitle = "Refer & Earn 10k";
		$this->render('earn10k', array());
	}

	public function actionPriceGuarantee()
	{
		$this->pageTitle		 = "Price Guarantee";
		$this->metaDescription	 = "Gozo Cabs, the leader in outstation Cab services,  offers you the best price guarantee. Find a cab cheaper than us and we will credit you the difference back.";
		$this->render('price_guarantee', array());
	}

	public function actionCorporate()
	{
		$this->pageTitle = "Corporate Refer";
		$this->render('corporate', array());
	}

	public function actionGetdestination()
	{
		$scity		 = (Yii::app()->request->getParam('source') == "") ? 0 : Yii::app()->request->getParam('source');
		$arrCities	 = Cities::model()->getJSONRateDestinationCities($scity);
		echo $arrCities;
		Yii::app()->end();
	}

	public function actionGetdestinationall()
	{
		$scity		 = (Yii::app()->request->getParam('source') == "") ? 0 : Yii::app()->request->getParam('source');
		$arrCities	 = Cities::model()->getJSONRateDestinationCitiesAll($scity);
		echo $arrCities;
		Yii::app()->end();
	}

	public function actionGetnearest()
	{
		$scity		 = (Yii::app()->request->getParam('source') == "") ? 0 : Yii::app()->request->getParam('source');
		$maxDistance = Yii::app()->request->getParam('maxdistance');
		$arrCities	 = ($maxDistance == null) ? Cities::model()->getJSONNearestAll($scity) : Cities::model()->getJSONNearestAll($scity, $maxDistance);
// $arrCities = Cities::model()->getJSONNearestAll($scity, $maxDistance);
		echo $arrCities;
		Yii::app()->end();
	}

	public function actionGetairportnearest()
	{
		$isAirport	 = true;
		$scity		 = (Yii::app()->request->getParam('source') == "") ? 0 : Yii::app()->request->getParam('source');
		$maxDistance = Yii::app()->params['airportCityRadius'];
		$arrCities	 = Cities::model()->getJSONNearestAll($scity, $maxDistance, $isAirport);

		echo $arrCities;
		Yii::app()->end();
	}

	public function actionGetairportcities()
	{
		$arrCities = Cities::model()->getJSONAirportCitiesAll();
		echo $arrCities;
		Yii::app()->end();
	}

	public function actionGetrailwaybuscities()
	{
		$arrCities = Cities::model()->getJSONRailwayBusCitiesAll();
		echo $arrCities;
		Yii::app()->end();
	}

	public function actionGetdestinationlist()
	{
		$scity		 = (Yii::app()->request->getParam('source') == "") ? 0 : Yii::app()->request->getParam('source');
		$cityList	 = CHtml::listData(Cities::model()->getRateDestinationCities($scity), 'cty_id', 'cty_name');
		echo CJSON::encode(array('citylist' => $cityList));
	}

	public function actionGetdestinationlistall()
	{
		$scity		 = (Yii::app()->request->getParam('source') == "") ? 0 : Yii::app()->request->getParam('source');
		$cityList	 = CHtml::listData(Cities::model()->getRateDestinationCitiesAll($scity), 'cty_id', 'cty_name');
		echo CJSON::encode(array('citylist' => $cityList));
	}

	public function actionGetnearestlistall()
	{
		$scity		 = (Yii::app()->request->getParam('source') == "") ? 0 : Yii::app()->request->getParam('source');
		$cityList	 = CHtml::listData(Cities::model()->getNearestCitiesDistanceListbyId($scity), 'cty_id', 'cty_name');
		echo CJSON::encode(array('citylist' => $cityList));
	}

	public function actionGetsourcelistall()
	{
		$cityList = CHtml::listData(Cities::model()->getAllCities(false), 'cty_id', 'cty_name');
		echo CJSON::encode(array('citylist' => $cityList));
	}

	public function actionGetservicelistall()
	{
		$cityList = CHtml::listData(Cities::model()->getServiceCities(), 'cty_id', 'cty_name');
		echo CJSON::encode(array('citylist' => $cityList));
	}

	public function actionGetairportpickuplist()
	{
		$cityList = CHtml::listData(Cities::model()->getAirportCities('', false), 'cty_id', 'cty_name');
		echo CJSON::encode(array('citylist' => $cityList));
	}

	public function actionRedirect()
	{
		$this->checkV2Theme();
		$refType = Yii::app()->request->getParam('reftype');
		$affId	 = Yii::app()->request->getParam('affid');
		$bkgId	 = Yii::app()->request->getParam('bkgid');
		$url	 = Yii::app()->request->getParam('url');
		$data	 = ['aft_ref_type' => $refType, 'aft_aff_id' => $affId, 'aft_bkg_id' => $bkgId, 'aft_url' => $url];
		$result	 = AffiliateTracking::model()->add($data);
		$this->redirect($url);
	}

	public function actionLanding()
	{
		$refType	 = Yii::app()->request->getParam('rt');
		$affId		 = Yii::app()->request->getParam('aff');
		$desc		 = Yii::app()->request->getParam('desc');
		$referrer	 = $_SERVER['HTTP_REFERER'];
		$data		 = ['tkg_ref_type' => $refType, 'tkg_aff_id' => $affId, 'tkg_referrer' => $referrer, 'tkg_desc' => $desc];
		$result		 = Tracking::model()->add($data);
		$this->redirect('/');
	}

	public function actionCabrate()
	{
		$this->pageTitle = "Cabs";
		$req			 = [];
		$model			 = new BookingTemp('step1');

		if (isset($_REQUEST['BookingTemp']))
		{
			$arr1 = Yii::app()->request->getParam('BookingTemp');
			if ($arr1['bkg_id'] != '')
			{
				$model = BookingTemp::model()->findbyPk($arr1['bkg_id']);
			}
			$model->attributes		 = $arr1;
			$model->bkg_from_city_id = $arr1['bkg_from_city_id'];
			$model->bkg_to_city_id	 = $arr1['bkg_to_city_id'];
			$model->bkg_route_id	 = Route::model()->getRutidbyCities($model->bkg_from_city_id, $model->bkg_to_city_id);

			if ($arr1['bkg_pickup_date_date'] != "" && $arr1['bkg_pickup_date_time'] != "")
			{
				$date					 = DateTime::createFromFormat('d/m/Y', $arr1['bkg_pickup_date_date'])->format('Y-m-d');
				$time					 = DateTime::createFromFormat('h:i A', $arr1['bkg_pickup_date_time'])->format('H:i:00');
				$model->bkg_pickup_date	 = $date . ' ' . $time;
				$model->bkg_pickup_time	 = $time;
			}
			if (!Yii::app()->user->isGuest)
			{
				$user					 = Yii::app()->user->loadUser();
				$model->bkg_user_id		 = $user->user_id;
				$model->bkg_user_name	 = $user->usr_name;
				if ($user->usr_mobile != '')
				{
					$model->bkg_contact_no	 = $user->usr_mobile;
					$model->bkg_country_code = $user->usr_country_code;
				} if ($user->usr_email != '')
				{
					$model->bkg_user_email = $user->usr_email;
				}
			}
			$model->bkg_user_ip		 = \Filter::getUserIP();
			$model->bkg_user_device	 = UserLog::model()->getDevice();
			$model->scenario		 = 'step1';
			if ($model->validate())
			{
				$model->save();
			}
			$model->bkg_booking_type = 1;
			$bktypArr				 = ['1' => 'OW', '2' => 'RT'];
			$booking_id				 = $bktypArr[$model->bkg_booking_type] . date('Y') . str_pad($model->bkg_id, 4, 0, STR_PAD_LEFT);
			$model->bkg_booking_id	 = $booking_id;
			$model->save();
			$cabRate				 = Rate::model()->getCabDetailsbyCitiesArr($model->bkg_from_city_id, $model->bkg_to_city_id);
		}
		else
		{
			$this->redirect('index');
		}
		$this->render('cabrate', array('model' => $model, 'cabratedata' => $cabRate));
	}

	/**
	 * @deprecated since version 02-10-2019
	 * @author ramala
	 */
	public function actionJourneydetails()
	{
		exit();
		$model = new BookingTemp('step3');
		if (isset($_REQUEST['bkid']) && $_REQUEST['bkid'] > 0)
		{
			$model						 = BookingTemp::model()->findbyPk(Yii::app()->request->getParam('bkid'));
			$model->bkg_vehicle_type_id	 = Yii::app()->request->getParam('cabid');
			$model->bkg_trip_distance	 = Yii::app()->request->getParam('bkg_distance');
			$model->bkg_trip_duration	 = Yii::app()->request->getParam('bkg_duration');

//$amount = Rate::model()->fetchRatebyRutnVht($model->bkg_route_id, $model->bkg_vehicle_type_id);
			$amount = Rate::model()->getRouteRatebyCitiesnVehicletype($model->bkg_from_city_id, $model->bkg_to_city_id, $model->bkg_vehicle_type_id);
			if ($model->bkg_total_amount != $amount)
			{
				$model->bkg_total_amount = $amount;
			}
			$model->scenario = 'step2';
			if ($model->validate())
			{
				$model->save();
			}
			$vmodel = VehicleTypes::model()->findbyPk($model->bkg_vehicle_type_id);
		}
		else
		{
			$this->redirect('index');
		}
		$this->render('journeydetails', array('model' => $model, 'vmodel' => $vmodel));
	}

	/**
	 * @deprecated since version 02-10-2019
	 * @author ramala
	 */
	public function actionUserdetails()
	{
		$model = new BookingTemp('step4');
		if (isset($_REQUEST['BookingTemp']))
		{
			$arr1 = Yii::app()->request->getParam('BookingTemp');
			if ($arr1['bkg_id'] != '')
			{
				$model = BookingTemp::model()->findbyPk($arr1['bkg_id']);
			}


			$uploadedFile = CUploadedFile::getInstance($model, "fileImage");

			if ($uploadedFile != '')
			{
				$crdate		 = date('YmdHis', strtotime($model->bkg_create_date));
				$fileName	 = $model->bkg_id . '_' . $crdate . '_' . $uploadedFile;

				$model->bkg_file_path = $DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $fileName;
				$uploadedFile->saveAs(PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $fileName);
			}


			$vmodel = VehicleTypes::model()->findbyPk($model->bkg_vehicle_type_id);

			$pickup1 = trim($arr1['pickup1']);
			$pickup2 = trim($arr1['pickup2']);
			$pickup3 = trim($arr1['pickup3']);

			$pickup = [];
			if ($pickup1 != '')
			{
				$pickup[] = $pickup1;
			}
			if ($pickup2 != '')
			{
				$pickup[] = $pickup2;
			}
			if ($pickup3 != '')
			{
				$pickup[] = $pickup3;
			}
			$address = implode(', ', $pickup);

// $address = $pickup1 . $pickup2 . $pickup3;
			$model->bkg_pickup_address				 = $address;
			$model->bkg_drop_address				 = $arr1['bkg_drop_address'];
			$model->bkg_instruction_to_driver_vendor = $arr1['bkg_instruction_to_driver_vendor'];

			$model->scenario = 'step3';
			if ($model->validate())
			{
				$model->save();
			}
		}
		else
		{
			$this->redirect('index');
		}

		$this->render('userdetails', array('model' => $model, 'vmodel' => $vmodel));
	}

	/**
	 * @deprecated since version 02-10-2019
	 * @author ramala
	 */
	public function actionSummary()
	{
		$model = new BookingTemp;
		if (isset($_REQUEST['BookingTemp']))
		{
			$arr1 = Yii::app()->request->getParam('BookingTemp');
			if ($arr1['countrycode'] != '')
			{
				$cid = trim($arr1['countrycode']);
			}
			if ($arr1['altcountrycode'] != '')
			{
				$acid = trim($arr1['altcountrycode']);
			}


			if ($arr1['bkg_id'] != '')
			{
				$model = BookingTemp::model()->findbyPk($arr1['bkg_id']);
			}
			$vmodel							 = VehicleTypes::model()->findbyPk($model->bkg_vehicle_type_id);
			$model->bkg_user_name			 = trim($arr1['firstname']);
			$model->bkg_user_lname			 = trim($arr1['lastname']);
			$model->bkg_country_code		 = $cid;
			$model->bkg_alt_country_code	 = $acid;
			$model->bkg_contact_no			 = trim($arr1['contactnumber']);
			$model->bkg_alternate_contact	 = trim($arr1['bkg_alternate_contact']);
			$model->bkg_user_email			 = trim($arr1['bkg_user_email']);
			$model->bkg_info_source			 = $arr1['bkg_info_source'];

			$model->scenario = 'step4';
			if ($model->validate())
			{

				$model->save();
			}
		}
		else
		{
			$this->redirect('index');
		}
		$this->render('summary', array('model' => $model, 'vmodel' => $vmodel));
	}

	public function actionSitemap_OLD()
	{
		//
		header('Content-Type: application/xml');
		$list = [];
		$this->populateSitemap($list);
		$this->renderPartial('sitemap', ['list' => $list]);
	}

	public function actionSitemap()
	{

		$this->pageTitle = "Sitemap";

		$arrRegionLimit = [1 => 150, 2 => 75, 3 => 10, 4 => 75, 5 => 30, 6 => 10, 7 => 10];

		$topRoutes = Route::getTopRouteByType(1, [], $arrRegionLimit);

		$topCities = Route::getTopRouteByType(2, [], $arrRegionLimit);

		$topAirportTransfer = Route::getTopRouteByType(3, [], $arrRegionLimit);

		$this->render('sitemap', ['topRoutes' => $topRoutes, 'topCities' => $topCities, 'topAirportTransfer' => $topAirportTransfer]);
	}

	/**
	 * @deprecated since version 02-10-2019
	 * @author ramala
	 */
	public function actionBilling()
	{

		if (isset($_REQUEST['bkid']) && $_REQUEST['bkid'] > 0 || $_REQUEST['BookingTemp']['bkg_id'])
		{
			$arr1	 = Yii::app()->request->getParam('BookingTemp');
			$model	 = BookingTemp::model()->findbyPk(Yii::app()->request->getParam('bkid'));
			if ($_REQUEST['BookingTemp']['bkg_id'])
			{
				$model = BookingTemp::model()->findbyPk($arr1['bkg_id']);
			}
			if ($_REQUEST['cabid'])
			{
				$model->bkg_vehicle_type_id = Yii::app()->request->getParam('cabid');
			}

			$vmodel = VehicleTypes::model()->findbyPk($model->bkg_vehicle_type_id);
		}
		$this->renderPartial('sideform', ['model' => $model, 'vmodel' => $vmodel]);
	}

	public function actionCountry()
	{
		/*
		  $criteria = new CDbCriteria();
		  $criteria->select = "country_phonecode";
		  $criteria->group = 'country_phonecode,country_name';
		  $criteria->group = 'country_name';
		  $criteria->order = 'country_phonecode ASC, country_name ASC';
		  $model = Countries::model()->findAll($criteria);
		 */
		$sql		 = "SELECT `id`,`country_code`,`country_name`,`country_phonecode` FROM `countries` WHERE 1 GROUP BY country_phonecode,country_name ORDER BY country_phonecode ASC, country_name ASC";
		$modelData	 = Yii::app()->db->createCommand($sql)->queryAll();
		$data		 = array();
		$arrService	 = array();
		$i			 = 1;
		/* @var  Services  */
		foreach ($modelData as $val)
		{
			$arrService[] = array('id' => $val ['id'], 'name' => $val['country_name'] . " (" . $val['country_phonecode'] . ")", 'pcode' => $val['country_phonecode'], 'order' => "$i");
// $arrService1[] = array('id' => $val ['id'], 'pcode' => $val['country_phonecode']);
			$i++;
		}
		$data			 = array();
		$data['data']	 = $arrService;

		echo CJSON::encode($data);
		Yii::app()->end();
	}

	public function actionBd()
	{
		$this->pageTitle = "Gozocabs - Booking Details Login";
		$this->render('bd', array());
	}

	public function actionBookingdetails()
	{
		$this->pageTitle = "Gozocabs - Booking Details";
		$model			 = Booking::model()->getBookingDetails(Yii::app()->request->getParam('bid'), Yii::app()->request->getParam('l_name'));
		$this->render('booking_details', array('model' => $model));
	}

	public function actionVendorAborted()
	{
		$this->checkV2Theme();
		$vndId	 = Yii::app()->request->getParam('id');
		$model	 = Vendors::model()->resetScope()->findByPk($vndId);
		if ($model->vnd_application_aborted == 0)
		{
			$model->vnd_id					 = $model->vnd_id;
			$model->vnd_application_aborted	 = 1;
			$model->update();
		}
		$this->render('vendor_aborted', array('model' => $model));
	}

	public function actionVendorjoin()
	{
//		$this->checkV2Theme();
		$this->pageTitle			 = "DCOs and Cab Operators, Attach your cab...";
		$model						 = Vendors::model()->resetScope();
		$modelVndPref				 = new VendorPref();
		$modelContPhone				 = new ContactPhone();
		$modelContEmail				 = new ContactEmail();
		$model->scenario			 = 'vendorjoin';
		$modelContPhone->scenario	 = 'vendorjoin';
		$modelContEmail->scenario	 = 'vendorjoin';
		$telegramId					 = $_REQUEST['telegramId'];
		$this->metaDescription		 = "Gozo cabs invite DCO's and Taxi operators from all over India to attach your car to Gozo vendor network. Become our vendor and maximize your revenue";

		$platform	 = 1;
		$url		 = Yii::app()->request->getUrl();
		$findUrl	 = 'local/attachnow';
		$pos		 = strpos($url, $findUrl);
		if ($pos != '' && $pos >= 0)
		{
			$platform = 2;
		}

		$this->renderAuto('vendorjoin', array('model' => $model, 'modelVndPref' => $modelVndPref, 'modelContPhone' => $modelContPhone, 'modelContEmail' => $modelContEmail, 'telegramId' => $telegramId, 'platform' => $platform));
	}

	public function actionBookingRequest()
	{
		$this->pageTitle = "CAR NEEDED , GIVE YOUR PRICE NOW";
		$hashBuvId		 = Yii::app()->request->getParam('buv_id');
		$hash			 = Yii::app()->request->getParam('hash');
		$buvId			 = Yii::app()->shortHash->unhash($hashBuvId);
		/* @var $buvModel  BookingUnregVendor */
		$buvModel		 = BookingUnregVendor::model()->findByPk($buvId);
		if ($buvModel->buvBkg->bkg_status != 2)
		{
			throw new Exception('Sorry this link has expired.', 410);
		}
		$model = UnregVendorRequest::model()->findByPk($buvModel->buv_vendor_id);
		if (!$model)
		{
			$model = new UnregVendorRequest();
		}
		else
		{
			$model->uvr_bid_amount = '';
		}
		$model->uvr_vnd_phone = $buvModel->buvUo->uo_phone;
		if (isset($_REQUEST['UnregVendorRequest']))
		{
			$success		 = false;
			$return['error'] = '';
			Logger::create('POST DATA =====>: ' . json_encode($_POST) . " - " . json_encode($_FILES) . " - " . json_encode($_GET), CLogger::LEVEL_TRACE);
			$arr1			 = Yii::app()->request->getParam('UnregVendorRequest');
			$model			 = UnregVendorRequest::model()->findByPk($arr1['uvr_id']);
			if (!$model)
			{
				$model = new UnregVendorRequest();
			}
			$model->uvr_vnd_voter_no	 = $arr1['uvr_vnd_voter_no'];
			$model->uvr_vnd_aadhaar_no	 = $arr1['uvr_vnd_aadhaar_no'];
			$model->uvr_vnd_pan_no		 = $arr1['uvr_vnd_pan_no'];
			$model->uvr_vnd_license_no	 = $arr1['uvr_vnd_license_no'];

			$licenseExpDate = DateTimeFormat::DatePickerToDate($arr1['uvr_vnd_license_exp_date1']);

			$model->uvr_vnd_license_exp_date = ($licenseExpDate != '1970-01-01') ? $licenseExpDate : NULL;

			$model->uvr_vnd_is_driver	 = $arr1['uvr_vnd_is_driver'];
			$uploadedFile1				 = CUploadedFile::getInstance($model, "uvr_vnd_voter_id_front_path");
			$uploadedFile2				 = CUploadedFile::getInstance($model, "uvr_vnd_aadhaar_front_path");
			$uploadedFile3				 = CUploadedFile::getInstance($model, "uvr_vnd_pan_front_path");
			$uploadedFile4				 = CUploadedFile::getInstance($model, "uvr_vnd_licence_front_path");

			$model->scenario = 'vendorjoin2';
			$result			 = CActiveForm::validate($model, null, false);
			if ($result == '[]')
			{
				if ($model->save())
				{

					$buvModel->buv_bid_amount	 = $arr1['uvr_bid_amount'];
					$res						 = $buvModel->save();

					$voterId	 = $_FILES['uvr_vnd_voter_id_front_path']['name'];
					$voterIdTmp	 = $_FILES['uvr_vnd_voter_id_front_path']['tmp_name'];
					$aadhaar	 = $_FILES['vnd_aadhaar_front_path']['name'];
					$aadhaarTmp	 = $_FILES['vnd_aadhaar_front_path']['tmp_name'];
					$pan		 = $_FILES['vnd_pan_front_path']['name'];
					$panTmp		 = $_FILES['vnd_pan_front_path']['tmp_name'];
					$licence	 = $_FILES['uvr_vnd_licence_front_path']['name'];
					$licenceTmp	 = $_FILES['uvr_vnd_licence_front_path']['tmp_name'];
					$folderName	 = 'unregvendor';
					if ($uploadedFile1 != '')
					{
						$type								 = 'voterid';
						$path1								 = $this->uploadAttachments($uploadedFile1, $type, $model->uvr_id, $folderName);
						$model->uvr_vnd_voter_id_front_path	 = $path1;
						$model->save();
					}
					if ($uploadedFile2 != '')
					{
						$type								 = "aadhar";
						$path1								 = $this->uploadAttachments($uploadedFile2, $type, $model->uvr_id, $folderName);
						$model->uvr_vnd_aadhaar_front_path	 = $path1;
						$model->save();
					}
					if ($uploadedFile3 != '')
					{
						$type							 = 'pan';
						$path1							 = $this->uploadAttachments($uploadedFile3, $type, $model->uvr_id, $folderName);
						$model->uvr_vnd_pan_front_path	 = $path1;
						$model->save();
					}
					if ($uploadedFile4 != '')
					{
						$type								 = 'licence';
						$path1								 = $this->uploadAttachments($uploadedFile4, $type, $model->uvr_id, $folderName);
						$model->uvr_vnd_licence_front_path	 = $path1;
						$model->save();
					}
					$success = true;
					Logger::create('RESPONSE DATA =====>: ' . json_encode($model->attributes), CLogger::LEVEL_INFO);
				}
			}
			else
			{
				$return['error'] = CJSON::decode($result);
				Logger::create('ERROR DATA =====>: ' . json_encode($result), CLogger::LEVEL_INFO);
			}
			$return['success'] = $success;
			echo CJSON::encode($return);
			Yii::app()->end();
		}

		$this->render('booking_unreg_vendorjoin', array('model' => $model, 'buvModel' => $buvModel, 'buvId' => $hashBuvId));
	}

	public function actionUnregUnsubscribe()
	{

		$hashBuvId	 = Yii::app()->request->getParam('buv_id');
		$type		 = Yii::app()->request->getParam('buvtype');
		if (isset($_REQUEST['buvtype']))
		{
			$uoId				 = Yii::app()->shortHash->unhash($hashBuvId);
			$umodel				 = UnregisterOperator::model()->findByPk($uoId);
			$umodel->uo_active	 = 0;
			if ($umodel->validate())
			{
				$umodel->save();
			}
			else
			{
				$errors = $umodel->getErrors();
			}
		}
		else
		{
			$uoId	 = Yii::app()->shortHash->unhash($hashBuvId);
			$umodel	 = UnregisterOperator::model()->findByPk($uoId);
		}
		$this->renderPartial('booking_unreg_unsubscribe', array('buvId' => $hashBuvId, 'umodel' => $umodel));
	}

	public function actionUnregvendorvalidation()
	{
		//$model		 = UnregVendorRequest::model()->resetScope();
		$buvId		 = Yii::app()->request->getParam('BuvId');
		$fname		 = Yii::app()->request->getParam('FName');
		$lname		 = Yii::app()->request->getParam('LName');
		$name		 = $fname . " " . $lname;
		$bidAmount	 = Yii::app()->request->getParam('Bidamount');
		$phone		 = Yii::app()->request->getParam('Phone');
		$email		 = Yii::app()->request->getParam('Email');
		$city		 = Yii::app()->request->getParam('City');
		$paramArray	 = ['buvId' => $buvId, 'fname' => $fname, 'lname' => $lname, 'bidAmount' => $bidAmount, 'phone' => $phone, 'email' => $email, 'city' => $city];
		$model		 = new UnregVendorRequest();
		$model->validateBidAmount($paramArray);
	}

	public function actionVendorjoinvalidation()
	{
		$model	 = Vendors::model()->resetScope();
		$fname	 = Yii::app()->request->getParam('FName');
		$lname	 = Yii::app()->request->getParam('LName');
		$name	 = $fname . " " . $lname;
		$company = Yii::app()->request->getParam('CompanyName');
		$phone	 = Yii::app()->request->getParam('Phone');
		$email	 = Yii::app()->request->getParam('Email');
		$city	 = Yii::app()->request->getParam('City');

		$cityModel								 = Cities::model()->findByPk($city);
		$model									 = new Vendors();
		$modelVendPref							 = new VendorPref();
		$modelVendDevice						 = new VendorDevice();
		$modelContact							 = new Contact();
		$modelContPhone							 = new ContactPhone();
		$modelContEmail							 = new ContactEmail();
		$modelVendStats							 = new VendorStats();
		//$model->vnd_phone				 = $phone;
		$modelContPhone->phn_phone_no			 = $phone;
		$model->first_name						 = $fname;
		$model->last_name						 = $lname;
		$model->vnd_name						 = $name;
		$model->vnd_owner						 = $name;
		//$model->vnd_email				 = $email;
		$modelContEmail->eml_email_address		 = $email;
		//$model->vnd_phone_country_code	 = $countryCode;
		$modelContPhone->phn_phone_country_code	 = $countryCode;
		$model->vnd_company						 = $company;
		$model->vnd_username					 = $email;
		if ($email == '')
		{
			$model->vnd_username = $phone;
		}
		$chars								 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$password							 = substr(str_shuffle($chars), 0, 4);
		$model->vnd_password1				 = $password;
		//$model->vnd_is_exclusive = 0;
		$modelVendPref->vnp_is_attached		 = 0;
		$model->vnd_tnc						 = 1;
		$model->vnd_tnc_id					 = 6;
		$model->vnd_tnc_datetime			 = new CDbExpression('NOW()');
		$modelVendDevice->vdc_device		 = $_SERVER['HTTP_USER_AGENT'];
		$modelVendStats->vrs_credit_limit	 = 500;
		//$model->vnd_ip_address	 = \Filter::getUserIP();
		$model->vnd_city					 = $city;
		//$model->vnd_address		 = $cityModel->cty_name;
		$modelContact->ctt_address			 = $cityModel->cty_name;
		if (isset($city) && $city != '')
		{
			$zoneData						 = Zones::model()->getNearestZonebyCity($city);
			//$model->vnd_home_zone	 = $zoneData['zon_id'];
			$modelVendPref->vnp_home_zone	 = $zoneData['zon_id'];
		}
		$model->vnd_active			 = 3;
		$model->scenario			 = 'vendorjoin';
		$modelContPhone->scenario	 = 'vendorjoin';
		$modelContEmail->scenario	 = 'vendorjoin';

		$result = CActiveForm::validate($model, null, false);

		$resultContphone = CActiveForm::validate($modelContPhone, null, false);

		if (empty(json_decode($resultContphone)))
		{
			$modelContPhone->scenario	 = 'vendoruserjoin';
			$resultContphone			 = CActiveForm::validate($modelContPhone, null, false);
		}
		$resultContEmail = CActiveForm::validate($modelContEmail, null, false);

		if (empty(json_decode($resultContEmail)))
		{
			$modelContEmail->scenario	 = 'vendoruserjoin';
			$resultContEmail			 = CActiveForm::validate($modelContEmail, null, false);
		}

		$resultContValidate = array_merge(json_decode($result, true), json_decode($resultContphone, true), json_decode($resultContEmail, true));
		//$resultContValidate =  array_merge(json_decode($result,true),json_decode($resultContphone,true));
		if ($resultContValidate["ContactPhone_phn_phone_no"][0] == 1 && $resultContValidate["ContactEmail_eml_email_address"][0] == 1)
		{
			unset($resultContValidate["ContactPhone_phn_phone_no"]);
			unset($resultContValidate["ContactEmail_eml_email_address"]);
		}
		if ($resultContValidate != '[]')
		{
			echo json_encode($resultContValidate);
		}
	}

	public function actionUnregvendorprefill()
	{
		$buvId	 = Yii::app()->request->getParam('BuvId');
		$fname	 = Yii::app()->request->getParam('FName');
		$lname	 = Yii::app()->request->getParam('LName');
		$name	 = $fname . " " . $lname;
		$address = Yii::app()->request->getParam('Address');
		$phone	 = Yii::app()->request->getParam('Phone');
		$email	 = Yii::app()->request->getParam('Email');

		$modelUnreg = UnregVendorRequest::model()->findByUsernamenEmail($email, $phone);
		if (!$modelUnreg)
		{
			echo '1';
		}
		else
		{
			echo json_encode($modelUnreg->attributes);
		}
	}

	public function actionVendorjoinvalidationdetails()
	{

		$model						 = Vendors::model()->resetScope();
		$is_dco						 = Yii::app()->request->getParam('is_dco');
		$carmodel					 = Yii::app()->request->getParam('carModel');
		$caryear					 = Yii::app()->request->getParam('carYear');
		$carnumber					 = Yii::app()->request->getParam('carNumber');
		$drivername					 = Yii::app()->request->getParam('driverName');
		$driverlicence				 = Yii::app()->request->getParam('driverLicence');
		$model						 = new Vendors();
		$model->vnd_car_model		 = $carmodel;
		$model->vnd_car_year		 = $caryear;
		$model->vnd_car_number		 = $carnumber;
		$model->vnd_driver_name		 = $drivername;
		$model->vnd_driver_license	 = $driverlicence;
		$model->scenario			 = $is_dco == 1 ? 'vendorjoindetails' : 'vendorjoindetailsnotdco';
		$result						 = CActiveForm::validate($model, null, false);
		$model->scenario			 = 'vendorjoinVehicle';
		$result1					 = CActiveForm::validate($model, null, false);
		if ($result != '[]')
		{
			echo $result;
		}
		else if ($result1 != '[]')
		{
			$results = json_decode($result1);
			if ($model->local_vehicle_year != '' && $model->local_vehicle_model != '')
			{
				$results->vehicle_year	 = $model->local_vehicle_year;
				$results->vehicle_model	 = $model->local_vehicle_model;
			}
			$vehicleDetails = json_encode($results);
			echo $vehicleDetails;
		}
	}

	public function actionGst()
	{
		$this->checkV3Theme();
		$this->pageTitle = "GST";
		$name			 = Yii::app()->request->getParam('name');
		$address		 = Yii::app()->request->getParam('address');
		$state			 = Yii::app()->request->getParam('state');
		$gstNumber		 = Yii::app()->request->getParam('gstNumber');
		$sDesc			 = Yii::app()->request->getParam('sDesc');
		$sAccount		 = Yii::app()->request->getParam('sAccount');
		$pan			 = Yii::app()->request->getParam('pan');
		$email			 = Yii::app()->request->getParam('email');
		$arn			 = Yii::app()->request->getParam('arn');
		$msg			 = '';
		if ($name != '')
		{
			$body	 = "<h3><b>GST Information Form</b></h3>" .
					"<b>Vendor Name : </b>" . $name .
					"<br/><b>Vendor Address : </b>" . $address .
					"<br/><b>Vendor State : </b>" . $state .
					"<br/><b>GST Number/ Provisional Id : </b>" . $gstNumber .
					"<br/><b>Service Description : </b>" . $sDesc .
					"<br/><b>Service Accounting Code : </b>" . $sAccount .
					"<br/><b>PAN : </b>" . $pan .
					"<br/><b>Email ID : </b>" . $email .
					"<br/><b>Application Reference Number (ARN) : </b>" . $arn;
			$mail	 = EIMailer::getInstance(EmailLog::SEND_ACCOUNT_EMAIL);
			$mail->setLayout('mail');
			$mail->setTo('info@gozocabs.com', 'Info Gozocabs');
			$mail->setBody($body);
			$mail->isHTML(true);
			$mail->setSubject('GST Information Form');
			if ($mail->sendMail(0))
			{
				$delivered = "Email sent successfully";
			}
			else
			{
				$delivered = "Email not sent";
			}
			$body		 = $mail->Body;
			$usertype	 = EmailLog::Admin;
			$subject	 = 'GST Information Form';
			$refType	 = EmailLog::REF_ADMIN_ID;
			emailWrapper::createLog('info@gozocabs.com', $subject, $body, "", $usertype, $delivered, '', $refType, '', EmailLog::SEND_SERVICE_EMAIL);
			$msg		 = 'Successfully submitted';
		}
		$this->render('gst', array('msg' => $msg));
	}

	public function actionOperatorjoin()
	{
		$this->checkV2Theme();
		$this->pageTitle = "Operator Join";
		$model			 = new Operators();
		$model->scenario = 'operatorjoin';
		if (isset($_POST['Operators']))
		{
			$arr					 = Yii::app()->request->getParam('Operators');
			$model->attributes		 = $arr;
			$model->opt_create_date	 = date('Y-m-d H:i:s');
			if ($model->validate())
			{
				$model->save();
				$emailWrapper = new emailWrapper();
				$emailWrapper->attachOperatorMail($model->opt_email, $model->opt_phone, $model->opt_company_name);
				$this->redirect(array('operatorjoin', 'msg' => '1'));
			}
			else
			{
				
			}
		}
		$this->render('operatorjoin', array('model' => $model));
	}

	public function uploadAttachments($uploadedFile, $type, $vendorId, $folderName)
	{
		$fileName	 = $vendorId . "-" . $type . "-" . date('YmdHis') . "." . pathinfo($uploadedFile, PATHINFO_EXTENSION);
		$dir		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
		if (!is_dir($dir))
		{
			mkdir($dir);
		}
		$dirFolderName = $dir . DIRECTORY_SEPARATOR . $folderName;
		if (!is_dir($dirFolderName))
		{
			mkdir($dirFolderName);
		}
		$dirByVendorId = $dirFolderName . DIRECTORY_SEPARATOR . $vendorId;
		if (!is_dir($dirByVendorId))
		{
			mkdir($dirByVendorId);
		}
		$foldertoupload	 = $dirByVendorId . DIRECTORY_SEPARATOR . $fileName;
		$extention		 = pathinfo($uploadedFile, PATHINFO_EXTENSION);
		if (strtolower($extention) == 'png' || strtolower($extention) == 'jpg' || strtolower($extention) == 'jpeg' || strtolower($extention) == 'gif')
		{
			Vehicles::model()->img_resize($uploadedFile->tempName, 1200, $dirByVendorId . DIRECTORY_SEPARATOR, $fileName);
		}
		else
		{
			$uploadedFile->saveAs($foldertoupload);
		}

		$path = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . $vendorId . DIRECTORY_SEPARATOR . $fileName;
		return $path;
	}

	public function actionOperatorjoining()
	{
		$this->pageTitle = "Gozocabs - Operator Signup";
		$countryCode	 = Yii::app()->request->getParam('countryCode');
		$ptr			 = print_r($_REQUEST);
		if (isset($_REQUEST['Operators']))
		{
			$model				 = new Operators();
			$arr				 = Yii::app()->request->getParam('Operators');
			$model->attributes	 = $arr;
			if ($model->opt_email != '')
			{
				if ($model->validate())
				{
					if ($model->save())
					{
						$msg	 = "success";
						$data	 = ['success' => true, 'msg' => $msg];
					}
					else
					{
						$msg	 = "error";
						$data	 = ['success' => false, 'msg' => $msg];
					}
				}
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				echo json_encode($data);
				Yii::app()->end();
			}
		}
	}

	public function actionAgentjoin()
	{
		$this->pageTitle		 = "Become or travel agent with Gozo. Join Gozo's travel partner family..";
		$this->metaDescription	 = "Become a Gozo agent and access India's largest fleet of taxi's for oneway or round trip outstation travel, airport transfers, package tours and shuttle service";
//        if(!Yii::app()->user->isGuest){
//            $this->redirect('/');
//        }
		$model					 = new Agents();
		if (Yii::app()->controller->action->id == 'corpjoin')
		{
			$this->pageTitle = "Join Gozo's Business Travel program";
			$model->scenario = 'corpsignup';
		}
		else
		{
			$model->scenario = 'signup';
		}
		if (isset($_POST['Agents']))
		{
			$model->attributes			 = Yii::app()->request->getParam('Agents');
			$chars						 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$password					 = substr(str_shuffle($chars), 0, 4);
			$model->agt_password		 = md5($password);
			$model->agt_username		 = $model->agt_email;
			$model->agt_approved		 = 0;
			$model->agt_commission_value = Yii::app()->params['agentDefCommissionValue'];
			$model->agt_commission		 = Yii::app()->params['agentDefCommission'];
			//$model->agt_api_key			 = md5($model->agt_email);
			if ($model->agt_city != '')
			{
				$ctyName = Cities::model()->findByPk($model->agt_city)->cty_name;
				$ctyCode = Cities::model()->findByPk($model->agt_city)->cty_code;
				if ($ctyCode != '')
				{
					$model->agt_referral_code = "AG-" . $ctyCode . "-" . time() . '-' . mt_rand(100, 999);
				}
				else
				{
					$model->agt_referral_code = "AG-" . "CITY" . "-" . time() . '-' . mt_rand(100, 999);
				}
			}
			$result			 = CActiveForm::validate($model);
			$isAlreadyExists = false;
			if ($model->resetScope()->exists('agt_email=:email OR agt_username=:username', ['email' => $model->agt_email, 'username' => $model->agt_username]))
			{
				$model->addError('agt_email', 'Email already exists.');
				$result			 = false;
				$isAlreadyExists = true;
			}
			if ($model->resetScope()->exists('agt_phone=:phone', ['phone' => $model->agt_phone]))
			{
				$model->addError('agt_phone', 'Phone already exists.');
				$result			 = false;
				$isAlreadyExists = true;
			}

			if ($result)
			{
				$userModel = Users::model()->find('usr_email=:email AND usr_mobile=:phone', ['email' => $model->agt_username, 'phone' => $model->agt_phone]);
				if (!$userModel)
				{
					$userModel = Users::model()->find('usr_email=:email', ['email' => $model->agt_username]);
				}
				if ($model->save())
				{
					$contactId = ContactProfile::getByEntityId($agtid, UserInfo::TYPE_AGENT);
					if (Yii::app()->controller->action->id == 'corpjoin')
					{
						$model->agt_type = 1;
					}
					/**
					 * add/update contact details
					 */
					$jsonObj									 = new stdClass();
					$jsonObj->profile->firstName				 = trim($model->agt_fname);
					$jsonObj->profile->lastName					 = trim($model->agt_lname);
					$jsonObj->profile->email					 = trim($model->agt_email);
					$jsonObj->profile->primaryContact->number	 = trim($model->agt_phone);
					$jsonObj->profile->primaryContact->code		 = trim($model->agt_phone_country_code);
					//$contactId									 = $model->agt_contact_id;
					if ($contactId)
					{
						Contact::modifyContact($jsonObj, $contactId, 1, UserInfo::TYPE_AGENT);
					}
					else
					{
						$returnSet				 = Contact::createContact($jsonObj, 0, UserInfo::TYPE_AGENT);
						$contactId				 = $returnSet->getData()['id'];
						$model->agt_contact_id	 = $contactId;
					}
					if ($userModel == '')
					{
						$model->agt_agent_id			 = "AGT00" . $model->agt_id;
						$userModel						 = new Users();
						$userModel->usr_contact_id		 = $contactId;
						$userModel->usr_name			 = $model->agt_fname;
						$userModel->usr_lname			 = $model->agt_lname;
						$userModel->usr_email			 = $model->agt_email;
						$userModel->usr_mobile			 = $model->agt_phone;
						$userModel->usr_password		 = $model->agt_password;
						$userModel->usr_create_platform	 = 1;
						$userModel->usr_acct_type		 = 1;
						$userModel->scenario			 = 'agentjoin';
						if (Yii::app()->controller->action->id == 'corpjoin')
						{
							$userModel->usr_corporate_id = $model->agt_id;
						}
						if ($userModel->save())
						{

							$agentUserModel					 = new AgentUsers();
							$agentUserModel->agu_agent_id	 = $model->agt_id;
							$agentUserModel->agu_user_id	 = $userModel->user_id;
							$agentUserModel->agu_role		 = 1;
							$agentUserModel->save();

							$success = $model->save();

							if ($contactId)
							{
								//Updating contact profile table
								//ContactProfile::setProfile($contactId, UserInfo::TYPE_AGENT);
								ContactProfile::updateEntity($contactId, $model->agt_id, UserInfo::TYPE_AGENT);
							}
							$emailWrapper	 = new emailWrapper();
							$emailWrapper->signupEmailAgent($model->agt_id, 1, $password);
							$emailWrapper2	 = new emailWrapper();
							$emailWrapper2->agentJoinEmail($ctyName, $model->agt_id);
							$this->redirect(['agent/users/signin', 'agtjoin' => 1]);
						}
					}
					else
					{
						$model->agt_agent_id = "AGT00" . $model->agt_id;
						$model->agt_password = $userModel->usr_password;
						$agentUserModel		 = AgentUsers::model()->find('agu_user_id=:user AND agu_agent_id=:agent', ['user' => $userModel->user_id, 'agent' => $model->agt_id]);
						if ($agentUserModel == '')
						{
							$agentUserModel = new AgentUsers();
						}
						$agentUserModel->agu_agent_id	 = $model->agt_id;
						$agentUserModel->agu_user_id	 = $userModel->user_id;
						$agentUserModel->save();
						$success						 = $model->save();
						if (Yii::app()->controller->action->id == 'corpjoin')
						{
							$this->redirect(['agent/users/signin', 'agtjoin' => 2]);
						}
						else
						{
							$emailWrapper	 = new emailWrapper();
							$emailWrapper->signupEmailAgent($model->agt_id, 2, $password); //if already registered as customer and now registered as agent
							$emailWrapper2	 = new emailWrapper();
							$emailWrapper2->agentJoinEmail($ctyName, $model->agt_id);
							$this->redirect(['agent/users/signin', 'agtjoin' => 3]);
						}
					}
				}
			}
			else
			{
				if ($isAlreadyExists)
				{
					$agtModel = Agents::model()->find('agt_email=:email AND agt_active<>0', ['email' => $model->agt_email]);
//                    if($agtModel==''){
//                       $agtModel = Agents::model()->find('agt_phone=:phone AND agt_active<>0',['phone'=>$model->agt_phone]);
//                    }

					if ($agtModel != '')
					{

						$isAlreadySentEmail = EmailLog::model()->checkIfAlreadySent($agtModel->agt_id, EmailLog::REF_AGENT_ID, EmailLog::EMAIL_AGENT_FORGOT_PASS, 8);
						if (!$isAlreadySentEmail)
						{
							$agtModel->agt_password	 = md5($password);
							$agtModel->save();
							$emailWrapper			 = new emailWrapper();
							$emailWrapper->getAgentPassChange($agtModel->agt_id, $password);
						}
						$passwordEmailSent = true;
					}
				}
			}
		}
		$this->renderAuto('agentjoin', array('model' => $model, 'verifyEmail' => $verifyEmail, 'emailToVerify' => $emailToVerify, 'passwordEmailSent' => $passwordEmailSent));
	}

	public function actionCorpjoin()
	{
		$this->actionAgentjoin();
	}

	public function actionVendorjoinsuccess()
	{
		$this->checkV2Theme();
		$this->pageTitle = "Gozocabs - Online Cab Booking | One Way | Delhi (NCR) | Chandigarh | Jaipur";
		$this->render('vendorjoinsuccess', array());
	}

	public function uploadAgentFiles($uploadedFile, $agent_id)
	{
		$fileName	 = $agent_id . "-" . 'agreement' . "-" . date('YmdHis') . "." . pathinfo($uploadedFile, PATHINFO_EXTENSION);
		$dir		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
		if (!is_dir($dir))
		{
			mkdir($dir);
		}
		$dirByAgentId = $dir . DIRECTORY_SEPARATOR . $agent_id;
		if (!is_dir($dirByAgentId))
		{
			mkdir($dirByAgentId);
		}

		$foldertoupload	 = $dirByAgentId . DIRECTORY_SEPARATOR . $fileName;
		$extention		 = pathinfo($uploadedFile, PATHINFO_EXTENSION);
		if (strtolower($extention) == 'png' || strtolower($extention) == 'jpg' || strtolower($extention) == 'jpeg' || strtolower($extention) == 'gif')
		{
			Vehicles::model()->img_resize($uploadedFile->tempName, 1200, $dirByAgentId . DIRECTORY_SEPARATOR, $fileName);
		}
		else
		{
			$uploadedFile->saveAs($foldertoupload);
		}

		$path = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $agent_id . DIRECTORY_SEPARATOR . $fileName;
		return $path;
	}

	public function actionCheckUser()
	{
		$email		 = Yii::app()->request->getParam('email');
		$contEmail	 = ContactEmail::model()->findByEmail2($email);
		echo $contEmail['cnt'];
		Yii::app()->end();
	}

	public function actionVendorjoining()
	{
		$this->pageTitle = "Gozocabs - Vendor Signup";
		$countryCode	 = Yii::app()->request->getParam('countryCode');
		if (isset($_REQUEST['Vendors']) || isset($_REQUEST['VendorPref']) || isset($_REQUEST['ContactEmail']) || isset($_REQUEST['ContactPhone']))
		{
			$arr			 = Yii::app()->request->getParam('Vendors');
			$arrVendPref	 = Yii::app()->request->getParam('VendorPref');
			$arrContEmail	 = Yii::app()->request->getParam('ContactEmail');
			$arrContPhone	 = Yii::app()->request->getParam('ContactPhone');
			$socialuserid	 = Yii::app()->request->getParam('socialuserid');
			$platform		 = Yii::app()->request->getParam('platform');

			$name	 = trim($arr['first_name'] . " " . $arr['last_name']);
			$phone	 = trim($arrContPhone['phn_phone_no']);
			$email	 = trim($arrContEmail['eml_email_address']);
			if ($name != '')
			{
				if ($email != '')
				{
					$contEmailModel = ContactEmail::model()->resetScope()->find('eml_email_address=:email and eml_active=1', ['email' => $email]);
				}

				$contPhoneModel = ContactPhone::model()->resetScope()->find('phn_phone_no=:phone and phn_active=1', ['phone' => $phone]);

				if (($contEmailModel == '' && $contPhoneModel == '') || ($contEmailModel != '' || $contPhoneModel != ''))
				{
					$arr	 = array_merge($arr, $arrVendPref, $arrContEmail, $arrContPhone);
					$result	 = Vendors::model()->setDataForVendorjoin($arr, $socialuserid, $platform);
					if ($result['success'] == true)
					{
						$msg	 = "success";
						$data	 = ['success' => true, 'msg' => $msg, 'email' => $email, 'password' => $result['password']];
					}
					else
					{
						$msg	 = "error";
						$data	 = ['success' => false, 'msg' => $msg, "errors" => CJSON::decode($result)];
					}
				}
				else
				{
					Vendors::model()->passwordResetForVendor($vendorModel, $vendorModel1);
					$msg	 = "signError";
					$data	 = ['success' => false, 'msg' => $msg];
				}
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				echo json_encode($data);
				Yii::app()->end();
			}
		}
	}

	public function actionBusinessemail()
	{
		if (isset($_REQUEST['action']))
		{
			$action = Yii::app()->request->getParam('action');
		}
		else
		{
			$action = "email";
		}
		$this->renderPartial('emailReport', array('action' => $action));
	}

	public function populateSitemap(&$list)
	{
		$routes	 = Route::model()->fetchActiveList(true);
// Add primary items here
		$list[]	 = $this->createSitemapEntry('/', [], 'daily', '1');
		$list[]	 = $this->createSitemapEntry('/users/signin', [], 'monthly', '0.7');
		$list[]	 = $this->createSitemapEntry('/users/signup', [], 'monthly', '0.7');
		$list[]	 = $this->createSitemapEntry('/index/aboutus', [], 'monthly', '0.5');
		$list[]	 = $this->createSitemapEntry('/index/faqs', [], 'monthly', '0.5');
		$list[]	 = $this->createSitemapEntry('/index/contactus', [], 'monthly', '0.8');
		$list[]	 = $this->createSitemapEntry('/index/openings', [], 'monthly', '0.5');
		$list[]	 = $this->createSitemapEntry('/index/terms', [], 'monthly', '0.5');
		$list[]	 = $this->createSitemapEntry('/index/disclaimer', [], 'monthly', '0.5');
		$list[]	 = $this->createSitemapEntry('/index/testimonial', [], 'daily', '0.7');
		$list[]	 = $this->createSitemapEntry('/blog', [], 'daily', '0.7');

		foreach ($routes as $row)
		{
			$list[] = $this->createSitemapEntry('/book-taxi/' . $row['rut_name']);
		}
	}

	public function createSitemapEntry($route, $params = [], $frequency = 'daily', $priority = '0.8')
	{
		return ['loc'		 => $this->createAbsoluteUrl($route, $params),
			'frequency'	 => $frequency,
			'priority'	 => $priority];
	}

	public function actionTermsmd()
	{
		$this->checkV2Theme();
		$this->pageTitle = "Terms and Conditions";
		$model			 = Terms::model()->getText(3);
		$this->renderPartial('termmd', array('model' => $model));
	}

	public function actionTermsagent()
	{
		$this->checkV2Theme();
		$model = Terms::model()->getText(4);
		$this->renderPartial('termagent', array('model' => $model));
	}

	public function actionTermsvendormd()
	{
		$this->checkV2Theme();
		$model = Terms::model()->getText(5);
		$this->renderPartial('termsvendorapp', array('model' => $model));
	}

	public function actionTest()
	{
		$model = Booking::model()->findByPk('16393');
		$this->render('test', array('model' => $model));
	}

	public function actionCountryname()
	{
		$criteria		 = new CDbCriteria();
//  $criteria->select = "country_phonecode";
		$criteria->group = 'country_code';
		$criteria->order = 'country_order DESC, country_name ASC';
		$model			 = Countries::model()->findAll($criteria);
		$data			 = array();
		$arrService		 = array();
		$i				 = 1;

		/* @var  Services  */
		foreach ($model as $val)
		{
			$arrService[] = array('id' => $val ['id'], 'name' => $val['country_name'] . " (" . $val['country_code'] . ")", 'pcode' => $val['country_code'], 'order' => "$i");
// $arrService1[] = array('id' => $val ['id'], 'pcode' => $val['country_phonecode']);
			$i++;
		}
		$data			 = array();
		$data['data']	 = $arrService;

		echo CJSON::encode($data);
		Yii::app()->end();
	}

	public function actionCountrynamejson()
	{
		$criteria		 = new CDbCriteria();
//  $criteria->select = "country_phonecode";
		$criteria->group = 'country_code';
		$criteria->order = 'country_order DESC, country_name ASC';
		$model			 = Countries::model()->findAll($criteria);

		$arrService		 = array();
		$i				 = 1;
		$excludeIndia	 = Yii::app()->request->getParam('excludeIndia', 0);
		foreach ($model as $val)
		{

			if ($val['country_code'] != 'IN' || $excludeIndia == 0)
			{
				$arrService[] = array('value' => $val['country_code'], 'text' => $val['country_name'] . " (" . $val['country_code'] . ")");
// $arrService1[] = array('id' => $val ['id'], 'pcode' => $val['country_phonecode']);
				$i++;
			}
		}
		$data			 = array();
		$data['data']	 = $arrService;

		echo CJSON::encode($arrService);
//	 exit;
		Yii::app()->end();
	}

	public function actionCountryindiajson()
	{
		$criteria = new CDbCriteria();
//  $model = Countries::model()->findAll($criteria);

		$arrService		 = array();
		$i				 = 1;
		$arrService		 = array('value' => 'IN', 'text' => "India (IN)");
		$data			 = array();
		$data['data']	 = $arrService;

		echo CJSON::encode($arrService);
// exit;
		Yii::app()->end();
	}

	public function actionReq()
	{
		$model = Booking::model()->appreciationMessage();
		foreach ($model as $val)
		{
			var_dump($val);
			exit();
			$bookingID		 = $model->bkg_booking_id;
			$vendorNumber	 = $model->bkgVendor->vnd_phone;
			$vendorName		 = $model->bkgVendor->vnd_name;
			$driverNumber	 = $model->bkgDriver->drv_phone;
			$driverNumber	 = $model->bkgDriver->drv_name;
			$msgCom			 = new smsWrapper();
			$msgCom->sendAppreciationMessageVendor($ext, $vendorNumber, 'Vendor', $bookingId, $vendorName, $driverName);
			$msgCom->sendAppreciationMessageDriver($ext, $driverNumber, 'Driver', $bookingId, $driverName);
		}
	}

	public function actionActivate($agent_id, $password)
	{
		echo "test====>";
		exit();
		echo $email;
		exit();

		$model				 = Agents::model()->resetScope()->findByPk($agtid);
		$model->agt_password = $password;
		$this->render('chnagePassAgent', array('model' => $model));

		$email = Yii::app()->request->getQuery('email');
		if (isset($email))
		{
			if ($email == $model->email)
			{
				$model->agt_active	 = 1;
				$model->agt_password = $password;
				$model->save();
			}
		}
		$this->render('agentjoin', array('model' => $model));
	}

	public function actionSmartMatchup()
	{
		$bookingType = 2;
		$topRoute	 = Rate::model()->getTopReportList();
		foreach ($topRoute as $key => $val)
		{
			$routeModel1						 = new BookingRoute();
			$routeModel1->brt_from_city_id		 = $val['from_city_id'];
			$routeModel1->brt_to_city_id		 = $val['to_city_id'];
			$routeModel1->brt_pickup_datetime	 = '2017-04-18 06:00:00';
			$routeModel2						 = new BookingRoute();
			$routeModel2->brt_from_city_id		 = $val['to_city_id'];
			$routeModel2->brt_to_city_id		 = $val['from_city_id'];
			$routeModel2->brt_pickup_datetime	 = '2017-04-20 13:00:00';
			$route[]							 = $routeModel1;
			$route[]							 = $routeModel2;
			$arr								 = Quotation::model()->getQuote($route, $bookingType);
			$totalRouteRates[]					 = ['source'				 => $arr['routeData']['routeDesc'][0], 'destination'			 => $arr['routeData']['routeDesc'][1]
				, 'basefareCompact'		 => $arr[1]['base_amt'], 'basefareSUV'			 => $arr[2]['base_amt'], 'basefareSedan'			 => $arr[3]['base_amt'], 'basefareTempo'			 => $arr[4]['base_amt'],
				'serviceTaxCompact'		 => $arr[1]['service_tax'], 'serviceTaxSUV'			 => $arr[2]['service_tax'], 'serviceTaxSedan'		 => $arr[3]['service_tax'], 'serviceTaxTempo'		 => $arr[4]['service_tax'],
				'driverAllowanceCompact' => $arr[1]['driverAllowance'], 'driverAllowanceSUV'	 => $arr[2]['driverAllowance'], 'driverAllowanceSedan'	 => $arr[3]['driverAllowance'], 'driverAllowanceTempo'	 => $arr[4]['driverAllowance'],
				'totalAmountCompact'	 => $arr[1]['total_amt'], 'totalAmountSUV'		 => $arr[2]['total_amt'], 'totalAmountSedan'		 => $arr[3]['total_amt'], 'totalAmountTempo'		 => $arr[4]['total_amt']];
			unset($route);
		}
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=\"RouteReport_Threeday" . date('Ymdhis') . ".csv\"");
		header("Pragma: no-cache");
		header("Expires: 0");
		$handle = fopen("php://output", 'w');
		fputcsv($handle, array("Source", "Destination", "Base Fare Compact", "Service Tax Compact", "Driver Allowance Compact", "Total Amount Compact",
			"Base Fare SUV", "Service Tax SUV", "Driver Allowance SUV", "Total Amount SUV",
			"Base Fare Sedan", "Service Tax Sedan", "Driver Allowance Sedan", "Total Amount Sedan"));
		foreach ($totalRouteRates as $totalRouteRate)
		{
			fputcsv($handle, array($totalRouteRate['source'], $totalRouteRate['destination'],
				$totalRouteRate['basefareCompact'], $totalRouteRate['serviceTaxCompact'], $totalRouteRate['driverAllowanceCompact'], $totalRouteRate['totalAmountCompact'],
				$totalRouteRate['basefareSUV'], $totalRouteRate['serviceTaxSUV'], $totalRouteRate['driverAllowanceSUV'], $totalRouteRate['totalAmountSUV'],
				$totalRouteRate['basefareSedan'], $totalRouteRate['serviceTaxSedan'], $totalRouteRate['driverAllowanceSedan'], $totalRouteRate['totalAmountSedan']));
		}
		fclose($handle);
		exit;
	}

	public function actionVerifyEmail()
	{
		$email		 = Yii::app()->request->getParam('email');
		$code		 = Yii::app()->request->getParam('code');
		$userModel	 = Users::model()->find('usr_email=:email', ['email' => $email]);
		if ($userModel != '' && $userModel->usr_verification_code == $code)
		{
			$userModel->usr_verification_code	 = '';
			$userModel->usr_email_verify		 = 1;
			if ($userModel->update())
			{
				echo json_encode(['success' => true]);
				Yii::app()->end();
			}
		}
		echo json_encode(['success' => false, 'message' => 'Invalid verification code']);
		Yii::app()->end();
	}

	public function actionAllUrls()
	{
		Route::model()->populateExisting();
	}

	public function actionToproutelist()
	{
		$scity	 = Yii::app()->request->getParam('var'); //tocity
		$pscity	 = Yii::app()->request->getParam('var2'); //fromcity
		Route::model()->popularRoute($scity, $pscity);
	}

	public function actionRoutePriceByCabType($rutId = '30366')
	{
		$priceSurge	 = TRUE;
		$rutData	 = Route::model()->getRoutePriceByCabType($rutId);

		$routesArr						 = [];
		$routeModel						 = new BookingRoute();
		$routeModel->brt_from_city_id	 = $rutData[0]['rut_from_city_id'];
		$routeModel->brt_to_city_id		 = $rutData[0]['rut_to_city_id'];
		$routeModel->brt_from_location	 = $rutData[0]['rut_from_address'];
		$routeModel->brt_to_location	 = $rutData[0]['rut_to_address'];
		$routeModel->brt_pickup_datetime = $rutData[0]['rut_modified_on'];
		$routesArr[]					 = $routeModel;
		$arr							 = Quotation::model()->getQuote($routesArr, 1, $carType, $priceSurge);
		echo "Route : " . $rutData[0]['rut_name'];
		echo "</br>";
		echo "Base Amount of " . $arr[1]['cab'] . ":  " . $arr[1]['base_amt'];
		echo "</br>";
		echo "Base Amount of " . $arr[2]['cab'] . ":  " . $arr[2]['base_amt'];
		echo "</br>";
		echo "Base Amount of " . $arr[3]['cab'] . ":  " . $arr[3]['base_amt'];
		echo "</br>";
		echo "Base Amount of " . $arr[3]['cab'] . ":  " . $arr[3]['base_amt'];
		echo "</br>";
	}

	public function actionOnewayCabs()
	{
		#Logger::beginProfile("START OnewayCabs");
		$this->layout		 = 'column1';
		$this->pageTitle	 = "One Way Cabs";
		Yii::app()->clientScript->registerLinkTag('canonical', null, 'https://www.gozocabs.com/one-way-cab');
		$GLOBALS["prefetch"] = "one-way-cab";
		Logger::profile("STEP1");
		$modelNorthRegion	 = Cities::model()->getTopCitiesByNorthRegion();
		Logger::profile("getTopCitiesByNorthRegion");
		$modelWestRegion	 = Cities::model()->getTopCitiesByWestRegion();
		Logger::profile("getTopCitiesByWestRegion");
		$modelSouthRegion	 = Cities::model()->getTopCitiesBySouthRegion();
		Logger::profile("getTopCitiesBySouthRegion");
		$modelEastRegion	 = Cities::model()->getTopCitiesByEastRegion();
		Logger::profile("getTopCitiesByEastRegion");

		//booking temp started
		$model			 = new BookingTemp('Route');
		$model->loadDefaults();
		$this->pageTitle = "The best & economical One way intercity cab services India. Book or get a quote now";
		//booking temp ended
		$this->render('one-way-cabs', array(
			'model'				 => $model,
			'modelNorthRegion'	 => $modelNorthRegion,
			'modelWestRegion'	 => $modelWestRegion,
			'modelSouthRegion'	 => $modelSouthRegion,
			'modelEastRegion'	 => $modelEastRegion,
			'model'				 => $model
		));
		#Logger::endProfile("END OnewayCabs");
	}

	//create luxary page
	public function actionLuxaryCabs()
	{
		$this->checkV2Theme();
		// $this->pageTitle= "Luxary Car Rental";
		$this->ampPageEnabled	 = 1;
		$model					 = new BookingTemp('Route');
		$model->loadDefaults();

		$selected_cities = array('delhi', 'mumbai', 'hyderabad', 'chennai', 'bangalore', 'pune', 'goa', 'jaipur', 'bengaluru');
		$city			 = Yii::app()->request->getParam('city');

		if (in_array($city, $selected_cities))
		{

			$luxary_car_types = array('audi', 'mercedes', 'jaguar', 'bmw', 'rolls_royce');
			//$c_types = VehicleTypes::model()->getcabPriceDetails($luxary_car_types);
			$this->render('luxarycabs', array('city' => ucfirst($city), 'c_types' => $c_types, 'model' => $model));
		}
		else
		{
			$redirect_url = '/amp/car-rental/' . $city;
			return $this->redirect($redirect_url);
		}
	}

	public function actionUnsubscribeemail()
	{
		//$this->layout	 = "head";
		$this->layout	 = 'column1';
		$data			 = Yii::app()->request->getParam('Unsubscribe');
		$email			 = Yii::app()->request->getParam('email');
		$hash			 = Yii::app()->request->getParam('hash');
		$type			 = Yii::app()->request->getParam('type');

//		$model			 = new Unsubscribe();
		$model	 = Unsubscribe::model()->find('usb_email=:email', ['email' => $email]);
		$success = ($model->usb_active == 1) ? 1 : 0;
		if ($model == '')
		{
			$model = new Unsubscribe();
		}

		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			if ($data > 0)
			{
				if (trim($email) != '')
				{
					$emailRecord = ContactEmail::getByEmail($email, '', '', '', 'limit 1');
				}

				$contactId = Contact::getIdByRecord($emailRecord);

				if ($contactId != "")
				{
					switch ((int) $type)
					{
						case 2:
							$id	 = ContactProfile::getVndId($contactId);
							break;
						case 3:
							$id	 = ContactProfile::getDrvId($contactId);
							break;
						default:
							$id	 = ContactProfile::getUserId($contactId);
							break;
					}
				}

				if ($id != Yii::app()->shortHash->unHash($hash) && $email != '')
				{
					throw new CHttpException(400, 'Invalid data');
				}

				$model->usb_email				 = $data['usb_email'];
				$model->usb_create_date			 = new CDbExpression('NOW()');
				$model->usb_reason				 = $data['usb_reason'];
				$model->usb_cat_promotional		 = $data['usb_cat_promotional'][0];
				$model->usb_cat_booking			 = $data['usb_cat_booking'][0];
				$model->usb_cat_transactional	 = $data['usb_cat_transactional'][0];
				$model->usb_cat_driverupdate	 = $data['usb_cat_driverupdate'][0];
				$model->usb_cat_ratings			 = $data['usb_cat_ratings'][0];
				$model->usb_cat_accountinfo		 = $data['usb_cat_accountinfo'][0];
				$model->usb_active				 = 1;
				$model->save();
				$success						 = 1;
			}
			if (isset($_POST['resub_btn']) && $_POST['unsub_email'] != "")
			{
				$model				 = Unsubscribe::model()->find('usb_email=:email', ['email' => $_POST['unsub_email']]);
				$model->usb_active	 = 0;
				$model->save();
				$success			 = 2;
			}
			DBUtil::commitTransaction($transaction);
		}
		catch (Exception $e)
		{
			$model->addError("user_id", $e->getMessage());
			$errors = $model->getErrors();
			DBUtil::rollbackTransaction($transaction);
		}

		$this->render('unsubscribe', ['model' => $model, 'email' => $email, 'success' => $success, 'errors' => $errors]);
	}

	public function actionImagetest()
	{
		$vhdid			 = Yii::app()->request->getParam('vhdid');
		$vhdModel		 = VehicleDocs::model()->findByPk($vhdid);
		$basePath		 = PUBLIC_PATH;
		$fileType		 = pathinfo($vhdModel->vhd_file, PATHINFO_EXTENSION);
		$rotateFilename	 = $basePath . $vhdModel->vhd_file; // PATH
		$degrees		 = 90;
		if ($fileType == 'png' || $fileType == 'PNG')
		{
			header('Content-type: image/png');
			$source	 = imagecreatefrompng($rotateFilename);
			$bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);
// Rotate
			$rotate	 = imagerotate($source, $degrees, $bgColor);
			imagesavealpha($rotate, true);
			imagepng($rotate, $rotateFilename);
		}
		if ($fileType == 'jpg' || $fileType == 'jpeg')
		{
			header('Content-type: image/jpeg');
			$source	 = imagecreatefromjpeg($rotateFilename);
// Rotate
			$rotate	 = imagerotate($source, $degrees, 0);
			imagejpeg($rotate, $rotateFilename);
		}

		imagedestroy($source);
		imagedestroy($rotate);
		echo json_encode(['success' => true, 'imagefile' => $vhdModel->vhd_file]);
		Yii::app()->end();
	}

	public function actionGettrans()
	{
		exit;
//	$bkgid			 = 151793;
//	/* @var $model Transactions */
//	$IncompleteRecords	 = Transactions::model()->getEmptystatus($bkgid);
//	foreach ($IncompleteRecords as $model)
//	{
//
//	    /////////////////////////////////
//	    $transCode = $model->trans_code;
//	    if ($model->trans_ptp_id == 3)
//	    {
//		$responseArr = [];
//		if ($model->trans_amount < 0 && $model->trans_mode == 1)
//		{
//		    $paramList['MID']	 = Yii::app()->paytm->merchant_id;
//		    $paramList['ORDERID']	 = $model->refundOrderCode;
//		    $paramList['REFID']	 = $transCode;
//		    $responseArr1		 = Yii::app()->paytm->getTxnRefundStatus($paramList);
//
//		    $responseArr = ($responseArr1['REFUND_LIST'][0]) ? $responseArr1['REFUND_LIST'][0] : $responseArr1;
//		}
//		if ($model->trans_amount > 0 && $model->trans_mode == 2)
//		{
//		    $paramList['MID']	 = Yii::app()->paytm->merchant_id;
//		    $paramList['ORDERID']	 = $transCode;
//		    $responseArr		 = Yii::app()->paytm->getTxnStatus($paramList);
//		}
//		Transactions::model()->updatePaymentStatus($responseArr, $transCode, $model->trans_ptp_id);
//		$transStatus = $responseArr['STATUS'];
//		echo "\t";
//		echo $transCode . ' | ' . $transStatus . "\n";
//		//exit;
//	    }
//	}
	}

	public function actionUpdateTopRoutesSavaari()
	{
		// exit;
		//Insert route info
		/* "INSERT INTO  `savaari_routes`  (`rut_id`,`source`,`destination`)

		  SELECT `rut_id`,`source`,`destination` from (SELECT  route.rut_id, IF(c1.cty_alias_name <> '', c1.cty_alias_name, c1.cty_name) AS `source`,
		  IF(c2.cty_alias_name <> '', c2.cty_alias_name, c2.cty_name) AS `destination`, totalBookingCount

		  FROM     (SELECT   DISTINCT bkg_from_city_id, bkg_to_city_id, COUNT(1) AS totalBookingCount,  max(booking.bkg_rate_per_km_extra)  extrKmCharge
		  FROM     `booking`
		  WHERE    bkg_status IN (2, 3, 5, 6, 7) AND bkg_active = 1 AND
		  booking.bkg_rate_per_km_extra IS NOT NULL AND
		  DATE(bkg_create_date) > '2016-10-01' AND bkg_pickup_date
		  BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR)
		  AND NOW()
		  AND bkg_vehicle_type_id IN (28,29,30)
		  GROUP BY bkg_from_city_id, bkg_to_city_id) b1
		  INNER JOIN `route` ON b1.bkg_from_city_id = route.rut_from_city_id AND b1.bkg_to_city_id = route.rut_to_city_id
		  INNER JOIN `cities` c1 ON `rut_from_city_id` = c1.cty_id AND c1.cty_active = 1 AND c1.cty_is_airport = 0
		  AND (  c1.cty_excluded_cabtypes IS NULL OR c1.cty_excluded_cabtypes= '' OR      NOT find_in_set(1,c1.cty_excluded_cabtypes) OR NOT find_in_set(2,c1.cty_excluded_cabtypes) OR NOT find_in_set(3,c1.cty_excluded_cabtypes))
		  INNER JOIN `cities` c2 ON `rut_to_city_id` = c2.cty_id AND c2.cty_active = 1 AND c2.cty_is_airport = 0
		  AND ( c2.cty_excluded_cabtypes IS NULL OR c2.cty_excluded_cabtypes= '' OR  NOT find_in_set(1,c2.cty_excluded_cabtypes) OR NOT find_in_set(2,c2.cty_excluded_cabtypes) OR NOT find_in_set(3,c2.cty_excluded_cabtypes))
		  INNER JOIN rate ON rate.rte_route_id = route.rut_id
		  WHERE    rut_active = 1 AND c1.cty_id <> c2.cty_id
		  GROUP BY rut_id
		  ORDER BY totalBookingCount DESC
		  LIMIT    1000) a1";
		 */

		$sql		 = 'select sr.rut_id, r.rut_from_city_id, r.rut_to_city_id from savaari_routes sr
                left join route r on r.rut_id = sr.rut_id where sr.sedan_total_amount is null';
		$resultset	 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($resultset as $result)
		{
			$route								 = [];
			$routeModel							 = new BookingRoute();
			$routeModel->brt_from_city_id		 = $result['rut_from_city_id'];
			$routeModel->brt_to_city_id			 = $result['rut_to_city_id'];
			$routeModel->brt_pickup_datetime	 = '2018-06-02 06:00:00';
			$routeModel->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker('2018-06-02 06:00:00');
			$routeModel->brt_pickup_date_time	 = date('h:i A', strtotime('2018-06-02 06:00:00'));
			$route[]							 = $routeModel;
//            $quote                            = Quotation::model()->getQuote($route, 1);
			$bookingCPId						 = Yii::app()->params['gozoChannelPartnerId'];
			$quote								 = new Quote();
			$quote->routes						 = $route;
			$quote->tripType					 = 1;
			$quote->partnerId					 = $bookingCPId;
			$quote->quoteDate					 = date("Y-m-d H:i:s");
			$quote->pickupDate					 = $route[0]->brt_pickup_datetime;
			$quote->setCabTypeArr();
			$qt									 = $quote->getQuote();
			$allowedCabs						 = [1, 2, 3];
			$rateCompact						 = $rateSuv							 = $rateSedan							 = '';
			foreach ($qt as $key => $value)
			{
				if (!in_array($key, $allowedCabs))
				{
					unset($qt[$key]);
				}
			}
			if ($qt[1]->success)
			{
				$rateCompact = " compact_total_amount = " . $qt[1]->routeRates->totalAmount . ","
						. " compact_gst = " . $qt[1]->routeRates->gst . ","
						. " compact_toll = " . (($qt[1]->routeRates->tollTaxAmount > 0) ? $qt[1]->routeRates->tollTaxAmount : 0) . ","
						. " compact_state = " . (($qt[1]->routeRates->stateTax > 0) ? $qt[1]->routeRates->stateTax : 0) . ","
						. " compact_extra_charge = " . $qt[1]->routeRates->ratePerKM . ", "
						. " compact_base_amount = " . $qt[1]->routeRates->baseAmount . ", ";
			}
			if ($qt[2]->success)
			{
				$rateSuv = " suv_total_amount = " . $qt[2]->routeRates->totalAmount . ","
						. " suv_gst = " . $qt[2]->routeRates->gst . ","
						. " suv_toll = " . (($qt[2]->routeRates->tollTaxAmount > 0) ? $qt[2]->routeRates->tollTaxAmount : 0) . ","
						. " suv_state = " . (($qt[2]->routeRates->stateTax > 0) ? $qt[2]->routeRates->stateTax : 0) . ","
						. " suv_extra_charge = " . $qt[2]->routeRates->ratePerKM . ", "
						. " suv_base_amount = " . $qt[2]->routeRates->baseAmount . ", ";
			}
			if ($qt[3]->success)
			{
				$rateSedan = " sedan_total_amount = " . $qt[3]->routeRates->totalAmount . ","
						. " sedan_gst = " . $qt[3]->routeRates->gst . ","
						. " sedan_toll = " . (($qt[3]->routeRates->tollTaxAmount > 0) ? $qt[3]->routeRates->tollTaxAmount : 0) . ","
						. " sedan_state = " . (($qt[3]->routeRates->stateTax > 0) ? $qt[3]->routeRates->stateTax : 0) . ","
						. " sedan_extra_charge = " . $qt[3]->routeRates->ratePerKM . ", "
						. " sedan_base_amount = " . $qt[3]->routeRates->baseAmount . ", ";
			}
			$rates = rtrim(trim($rateCompact . $rateSuv . $rateSedan), ',');
			if ($rates != '')
			{
				$qry = "UPDATE savaari_routes SET "
						. $rates . " where rut_id = " . $result['rut_id'];

				Yii::app()->db->createCommand($qry)->execute();
			}
		}
		echo "updated All";
	}

	public function actionBroadcastMsg()
	{
		$sql = 'SELECT DISTINCT vnd_id from vendors
					INNER JOIN app_tokens ON vendors.vnd_id=app_tokens.apt_entity_id AND app_tokens.apt_user_type=2 AND app_tokens.apt_logout IS NULL AND app_tokens.apt_last_login>DATE_SUB(NOW(), INTERVAL 3 MONTH)
					where vnd_active = 1';
		$ids = Yii::app()->db->createCommand($sql)->queryAll();

//To avoid accident please do not use mobile phones while driving. If it is urgent then first park the vehicle safely and only then use the mobile phone.";
		$message = "Dear Partner,
The complaints regarding Driver talking over phone while driving has significantly increased. As responsible service providers please ensure that the Drivers do not speak over mobile phone while driving.
";
		foreach ($ids as $id)
		{
			$payLoadData = ['EventCode' => Booking::CODE_VENDOR_BROADCAST];
			Logger::create("Data to be sent: " . $id['vnd_id'], $payLoadData, $message);

			$success = AppTokens::model()->notifyVendor($id['vnd_id'], $payLoadData, $message, "Important Notification");
			Logger::create("After sent: " . serialize($success));
			echo "Sent to vendor id:" . $id['vnd_id'];
			echo "<br><br>";
		}
	}

	public function actionCsv()
	{

		exit;
		header('Content-type: text/csv');
		header('Content-Disposition: attachment; filename="demo.csv"');
		header('Pragma: no-cache');
		header('Expires: 0');
		$file = fopen('php://output', 'w');
		fputcsv($file, array('Remarks', 'Status', 'BookingID'));

		$data = array(
			array('Data 11', 'Data 12', 'Data 13', 'Data 14', 'Data 15'),
			array('Data 21', 'Data 22', 'Data 23', 'Data 24', 'Data 25'),
			array('Data 31', 'Data 32', 'Data 33', 'Data 34', 'Data 35'),
			array('Data 41', 'Data 42', 'Data 43', 'Data 44', 'Data 45'),
			array('Data 51', 'Data 52', 'Data 53', 'Data 54', 'Data 55')
		);

		foreach ($data as $row)
		{
			fputcsv($file, $row);
		}

		exit();
	}

	public function actionFlexxiSale()
	{
		Yii::app()->theme = "";
		if (Yii::app()->request->requestUri == '/just1')
		{
			$this->redirect('/just199');
		}
		$this->pageTitle = '';
		$isFlexxi		 = $_REQUEST['isFlexxi'];
		$model			 = new FlashSale();
		$routeList		 = [];
		$flsPickupDate	 = '';
		$pickupDate		 = date('Y-m-d');
		$pickupDate		 = date('Y-m-d');
		if ($_REQUEST['FlashSale'])
		{
			$arr					 = Yii::app()->request->getParam('FlashSale');
			$model->fls_pickup_date	 = $arr['fls_pickup_date'];
			$model->fls_from_city	 = $arr['fls_from_city'];
			$model->fls_to_city		 = $arr['fls_to_city'];
			$flsPickupDate			 = $model->fls_pickup_date;
		}
		if (isset($model->fls_pickup_date) && $model->fls_pickup_date != '' && $model->fls_pickup_date != '0')
		{
			$date		 = DateTime::createFromFormat('d/m/Y', FlashSale::model()->fetchDate($model->fls_pickup_date));
			$pickupDate	 = $date->format('Y-m-d');
			$routes		 = FlashSale::model()->getList('RE1SALE', $pickupDate);
			$ctr		 = 0;
			if (count($routes) > 0)
			{
				foreach ($routes as $rut)
				{
					$flsStatus								 = FlashSale::model()->checkSaleByflsId($rut['fls_id']);
					$routeList[$ctr]['fls_id']				 = $rut['fls_id'];
					$routeList[$ctr]['fls_type']			 = $rut['fls_type'];
					$routeList[$ctr]['fls_route_id']		 = $rut['fls_route_id'];
					$routeList[$ctr]['fls_sale_start_date']	 = $rut['fls_sale_start_date'];
					$routeList[$ctr]['fls_sale_end_date']	 = $rut['fls_sale_end_date'];
					$routeList[$ctr]['fls_pickup_date']		 = $rut['fls_pickup_date'];
					$routeList[$ctr]['fls_pickup_address']	 = $rut['fls_pickup_address'];
					$routeList[$ctr]['fls_drop_address']	 = $rut['fls_drop_address'];
					$routeList[$ctr]['fls_no_of_bookings']	 = $rut['fls_no_of_bookings'];
					$routeList[$ctr]['fls_promo_id']		 = $rut['fls_promo_id'];
					$routeList[$ctr]['fls_active']			 = $rut['fls_active'];
					$routeList[$ctr]['rut_from_city_id']	 = $rut['rut_from_city_id'];
					$routeList[$ctr]['rut_to_city_id']		 = $rut['rut_to_city_id'];
					$routeList[$ctr]['from_city_name']		 = $rut['from_city_name'];
					$routeList[$ctr]['to_city_name']		 = $rut['to_city_name'];
					$routeList[$ctr]['flsStatus']			 = $flsStatus;
					$ctr++;
				}
			}
		}
		$startDate	 = date('Y-m-d H:i:s');
		$endDate	 = $date		 = date('Y-m-d H:i:s', strtotime('+5 days'));
		$userId		 = Yii::app()->user->getId();
		$userModel	 = Users::model()->findByPk($userId);
		$this->render('FlexxiSale', array(
			'model'			 => $model,
			'userModel'		 => $userModel,
			'routes'		 => $routeList,
			'startDate'		 => $startDate,
			'endDate'		 => $endDate,
			'flsPickupDate'	 => $flsPickupDate,
			'isFlexxi'		 => $isFlexxi,
			'pickupDate'	 => $pickupDate));
	}

	public function actionSuggestRoute()
	{
		$success	 = false;
		$userInfo	 = UserInfo::getInstance();
		$fromCity	 = Yii::app()->request->getParam('fromCity');
		$toCity		 = Yii::app()->request->getParam('toCity');
		try
		{
			if ($fromCity == $toCity)
			{
				$message = "Ops !!! It looks like you have selected same cities. Please select a different city and try again.";
				throw new Exception($message);
			}
			$rutId						 = Route::model()->getRutidbyCities($fromCity, $toCity);
			$model1						 = new RouteSuggestRe1();
			$model1->rsu_from_city_id	 = $fromCity;
			$model1->rsu_to_city_id		 = $toCity;
			$model1->rsu_rut_id			 = $rutId;
			$model1->rsu_user_id		 = $userInfo->userId;

			if ($model1->validate() && $model1->save())
			{
				$success = true;
				if ($success)
				{
					$emailCom = new emailWrapper();
					$emailCom->suggestRoute($rutId, $model1->rsu_user_id);
				}
				$frmCityName = $model1->rsuFromCity->cty_name;
				$toCityName	 = $model1->rsuToCity->cty_name;
				$messageSent = " Thanks for the suggestion! We don't have a ₹1 sale from " . $frmCityName . " to " . $toCityName;
			}
			else
			{
				$message = "Failed to register suggestion " . json_encode($model1->getErrors());
				throw new Exception($message);
			}
		}
		catch (Exception $ex)
		{
			$messageSent = $ex->getMessage();
		}
		$data = ['success' => $success, 'message' => $messageSent];
		echo CJSON::encode($data);
		Yii::app()->end();
	}

	public function actionFlexxiUser()
	{
		Yii::app()->theme	 = "";
		$flsId				 = Yii::app()->request->getParam('flsId');
		$flsId				 = Yii::app()->shortHash->unhash($flsId);
		$userId				 = Yii::app()->user->getId();
		$userModel			 = Users::model()->findByPk($userId);
		$flsModel			 = FlashSale::model()->findByPk($flsId);
		$baseAmount			 = FlashSale::getFlashBaseAmount();
		$this->pageTitle	 = 'You are booking a shared seat for ₹' . $baseAmount . '/-';
		$sendParams			 = ['flsId'					 => $flsId,
			'fls_pickup_date'		 => $flsModel->fls_pickup_date,
			'fls_pickup_address'	 => $flsModel->fls_pickup_address,
			'fls_sale_start_date'	 => $flsModel->fls_sale_start_date,
			'fls_sale_end_date'		 => $flsModel->fls_sale_end_date,
			'fls_drop_address'		 => $flsModel->fls_drop_address,
			'rut_estm_distance'		 => $flsModel->flsRoute->rut_estm_distance,
			'rut_actual_distance'	 => $flsModel->flsRoute->rut_actual_distance];

		$this->render('FlexxiUser', array('userModel'	 => $userModel,
			'sendParams' => $sendParams));
	}

	public function actionFlexxiQuote()
	{
		$userId		 = Yii::app()->user->getId();
		$userModel	 = Users::model()->findByPk($userId);
		if (isset($_REQUEST['Users']))
		{

			$success		 = false;
			$return['error'] = '';
			$sendParams		 = [];
			Logger::create('POST DATA =====>: ' . json_encode($_POST) . " - " . json_encode($_FILES) . " - " . json_encode($_GET), CLogger::LEVEL_TRACE);
			$arr1			 = Yii::app()->request->getParam('Users');
			$flsId			 = Yii::app()->request->getParam('flsId');
			$flsModel		 = FlashSale::model()->findByPk($flsId);
			$postParams		 = ['flsId'					 => $flsId,
				'fls_route_id'			 => $flsModel->fls_route_id,
				'fls_pickup_date'		 => $flsModel->fls_pickup_date,
				'fls_pickup_address'	 => $flsModel->fls_pickup_address,
				'fls_sale_start_date'	 => $flsModel->fls_sale_start_date,
				'fls_sale_end_date'		 => $flsModel->fls_sale_end_date,
				'fls_drop_address'		 => $flsModel->fls_drop_address,
				'rut_estm_time_min'		 => $flsModel->flsRoute->rut_estm_time_min,
				'rut_from_city_id'		 => $flsModel->flsRoute->rut_from_city_id,
				'rut_to_city_id'		 => $flsModel->flsRoute->rut_to_city_id,
				'rut_estm_distance'		 => $flsModel->flsRoute->rut_estm_distance,
				'rut_actual_distance'	 => $flsModel->flsRoute->rut_actual_distance,
				'usr_name'				 => $arr1['usr_name'],
				'usr_lname'				 => $arr1['usr_lname'],
				'usr_mobile'			 => $arr1['usr_mobile'],
				'usr_email'				 => $arr1['usr_email']];
			$result			 = Booking::model()->create($postParams);
			if ($result['success'] == true)
			{
				$hash	 = Yii::app()->shortHash->hash($result['data']['bkg_id']);
				$url	 = Yii::app()->createUrl('booking/paynow', ['id' => $result['data']['bkg_id'], 'hash' => $hash]);
			}
			else
			{
				$errors = $result['errors'];
			}
			$data = ['success' => $result['success'], 'url' => $url, 'errors' => $errors];
			echo CJSON::encode($data);
			Yii::app()->end();
		}
	}

	public function actionActiveVendorMessage()
	{
		Logger::create("command.vendor.activeVendorMsg start", CLogger::LEVEL_PROFILE);
		$data = Vendors::model()->getMissingPaperList();
		if (count($data) > 0)
		{
			$ctr = 1;
			foreach ($data as $row)
			{
				$vndId = $row['vnd_id'];
				if ($row['ctt_user_type'] == 1)
				{
					$userName = $row['contact_name'];
				}
				else
				{
					$userName = $row['ctt_business_name'];
				}
				//$userName = $row['vnd_owner'];
				$email	 = $row['vnd_email'];
				$phone	 = $row['vnd_phone'];
				$subject = 'Complete your Car and Driver paperwork today';
				if (($row['total_vehicle'] > $row['total_vehicle_approved']) || ($row['total_driver'] > $row['total_driver_approved']))
				{
					$incompleteVehicle	 = ($row['total_vehicle'] - $row['total_vehicle_approved']);
					$incompleteDriver	 = ($row['total_driver'] - $row['total_driver_approved']);
					$body				 = 'Dear ' . $row['vnd_owner'] . ',<br/><br/>';
					$body				 .= 'Your account has ' . $incompleteVehicle . ' cars and ' . $incompleteDriver . ' drivers with incomplete paperwork.';
					$body				 .= '<br/>We need you to add the relevant paperwork for these cars and drivers.';
					$body				 .= '<br/><br/>Please add the paperwork and details for the commercial car and driver today.';
					$body				 .= '<br/><br/>Always deliver 5 star service and get customers to add review for your service. The higher your rating in our system, the more bookings you will receive from the system.';
					$body				 .= '<br/><br/>Thank you,
                                <br/>Gozocab Team';
					/* var @model emailWrapper */
					$emailCom			 = new emailWrapper();
					$emailCom->paperworkDriverCarEmail($subject, $body, $userName, $email, $vndId);

					$carTxt		 = ($incompleteVehicle > 1) ? $incompleteVehicle . " Cars" : $incompleteVehicle . " Car";
					$driverTxt	 = ($incompleteDriver > 1) ? $incompleteDriver . " Drivers" : $incompleteDriver . " Driver";
					$message	 = "Your account has " . $carTxt . " and " . $driverTxt . " with incomplete paperwork.";
					$payLoadData = ['vendorId' => $vndId, 'EventCode' => Booking::CODE_MISSING_PAPERWORK];
					$success	 = AppTokens::model()->notifyVendor($vndId, $payLoadData, $message, "Pending Car and Driver paperwork.");
					echo $vndId . " -[" . $userName . "]- " . $message . "\n";
				}
				$ctr++;
			}
		}
		Logger::create("command.vendor.activeVendorMsg end", CLogger::LEVEL_PROFILE);
		//$venActive = 1;
		//Vendors::model()->missingDriverCarInformation($venActive);
		//Vendors::model()->missingDriverCarNotification($venActive);
	}

	public function actionAutoAssignment()
	{
		$check = Filter::checkProcess("autoAssignment");
		if (!$check)
		{
			return;
		}
		$recordsets = BookingVendorRequest::model()->getAutoAssignData();
		foreach ($recordsets as $value)
		{
			$vendor_amount		 = $value['bcb_vendor_amount'];
			$bcb_id				 = $value['bcb_id'];
			$profit_amount		 = $value['gozoAmount'];  // need to subtract the credits used here ...being done in  getAutoAssignData now 
			// $maxVendorAmount	 = $vendor_amount + $profit_amount;
			$maxVendorAmount	 = $vendor_amount + ($profit_amount * 0.9);
			// DSA >> change from full to only willing to give away upto 90% of the profit but will get more aggressive on giving it away. 
			// in the worst cast we're retaining 10% of the profit amount in our pocket atleast (10% of target_margin could be as little as 1-2% **worst case** )
			$maxLossVendorAmount = ROUND($maxVendorAmount * 1.02);
			$result				 = BookingVendorRequest::model()->getVendorIdAutoAssigned($vendor_amount, $bcb_id, $maxVendorAmount, $maxLossVendorAmount);
			if (!$result)
			{
				BookingPref::model()->setManualAssignment($bcb_id);
				continue;
			}
			echo "Booking ID: {$result['bvr_booking_id']} - $vendor_amount - {$result['bvr_bid_amount']} - $maxVendorAmount - {$value['MinBid']} - {$value['MaxBid']} - {$value['cntBid']} \r\n";
			$booking_id	 = $result['bvr_booking_id'];
			$remark		 = 'Auto Assigned';
			$result		 = BookingCab::model()->assignVendor($bcb_id, $result['bvr_vendor_id'], $result['bvr_bid_amount'], $remark, UserInfo::getInstance(), 1);
			if ($result['success'])
			{
				BookingVendorRequest::model()->assignVendor($bcb_id, $result['bvr_vendor_id']);
			}
			else
			{
				echo json_encode($result);
			}
		}
	}

	public function actionSendvendorreq($bkg_id = '')
	{
		//OW180733211
		$bkg_id	 = 733211;
		$check	 = Filter::checkProcess("sendvendorreq", 899);
		if (!$check)
		{
			return;
		}

		Logger::create("command.booking.requestNotification start", CLogger::LEVEL_PROFILE);
		//$acceptedRequests = Booking::model()->autoAssignTopVendor();
		$models = Booking::model()->sendVendorAutoRequest($bkg_id);
		Logger::create("command.booking.requestNotification end", CLogger::LEVEL_PROFILE);
	}

	public function actionPromotransfer()
	{
		exit;
		$arrPromo = Promotions::model()->findAll();
		/* @var  $promo Promotions */
		foreach ($arrPromo as $promo)
		{
			$promosModel							 = new Promos();
			$promosModel->prm_id					 = $promo->prm_id;
			$promosModel->prm_code					 = $promo->prm_code;
			$promosModel->prm_desc					 = $promo->prm_desc;
			$promosModel->prm_valid_from			 = $promo->prm_valid_from;
			$promosModel->prm_valid_upto			 = $promo->prm_valid_upto;
			$promosModel->prm_pickupdate_from		 = $promo->prm_valid_pickup_date_from;
			$promosModel->prm_pickupdate_to			 = $promo->prm_valid_pickup_date_to;
			$promosModel->prm_createdate_from		 = null;
			$promosModel->prm_createdate_to			 = null;
			$promosModel->prm_use_max				 = $promo->prm_use_max;
			$promosModel->prm_activate_on			 = $promo->prm_activate_on;
			$promosModel->prm_applicable_type		 = $promo->prm_applicable_type;
			$promosModel->prm_applicable_platform	 = $promo->prm_source_type;
			$promosModel->prm_applicable_user		 = $promo->prm_applicable_user_type;
			$promosModel->prm_applicable_nexttrip	 = $promo->prm_next_trip_apply;
			$promosModel->prm_used_counter			 = $promo->prm_used_counter;
			$promosModel->prm_active				 = $promo->prm_active;
			$promosModel->prm_created				 = $promo->prm_created;
			$promosModel->prm_modified				 = $promo->prm_modified;
			$promosModel->prm_log					 = $promo->prm_log;
			$promosModel->save();

			$promoCalcModel					 = new PromoCalculation();
			$promoCalcModel->pcn_promo_id	 = $promosModel->prm_id;
			$promoCalcModel->pcn_type		 = $promo->prm_type;
			$promoCalcModel->pcn_active		 = $promo->prm_active;
			$promoCalcModel->pcn_modified	 = $promo->prm_modified;
			$promoCalcModel->pcn_created	 = $promo->prm_created;
			$desc							 = "";
			if ($promo->prm_type == 1)
			{
				$desc = "type:Discount";
			}
			if ($promo->prm_type == 2)
			{
				$desc = "type:Gozocoins";
			}
			if ($promo->prm_type == 3)
			{
				$desc = "type:Discount And Gozocoins";
			}
			if ($promo->prm_type == 1 || $promo->prm_type == 3)
			{
				$promoCalcModel->pcn_value_type_cash = $promo->prm_value_type;
				$promoCalcModel->pcn_value_cash		 = $promo->prm_value;
				$desc								 .= " | value:" . $promoCalcModel->pcn_value_cash;
			}
			if ($promo->prm_type == 2 || $promo->prm_type == 3)
			{
				$promoCalcModel->pcn_value_type_coins	 = $promo->prm_value_type;
				$promoCalcModel->pcn_value_coins		 = $promo->prm_value;
				$desc									 .= " | value:" . $promoCalcModel->pcn_value_coins;
			}
			$promoCalcModel->pcn_title	 = $desc;
			$arrPlatform				 = explode(',', $promosModel->prm_applicable_platform);
			$platform					 = "";
			if (in_array(1, $arrPlatform))
			{
				$platform = " | platform:User";
			}
			if (in_array(2, $arrPlatform))
			{
				$platform = " | platform:Admin";
			}
			if (in_array(3, $arrPlatform))
			{
				$platform = " | platform:App";
			}
			if (in_array(1, $arrPlatform) && in_array(2, $arrPlatform))
			{
				$platform = " | platform:User,Admin";
			}
			if (in_array(2, $arrPlatform) && in_array(3, $arrPlatform))
			{
				$platform = " | platform:Admin,App";
			}
			if (in_array(1, $arrPlatform) && in_array(3, $arrPlatform))
			{
				$platform = " | platform:User,App";
			}
			if (in_array(1, $arrPlatform) && in_array(2, $arrPlatform) && in_array(3, $arrPlatform))
			{
				$platform = " | platform:User,Admin,App";
			}
			$desc .= $platform;

			if ($promo->prm_type == 1 || $promo->prm_type == 3)
			{
				$promoCalcModel->pcn_max_cash	 = $promo->prm_max;
				$promoCalcModel->pcn_min_cash	 = $promo->prm_min;
				$desc							 .= " | max_cash:" . $promoCalcModel->pcn_max_cash;
				$desc							 .= " | min_cash:" . $promoCalcModel->pcn_min_cash;
			}
			if ($promo->prm_type == 2 || $promo->prm_type == 3)
			{
				$promoCalcModel->pcn_max_coins	 = $promo->prm_max;
				$promoCalcModel->pcn_min_coins	 = $promo->prm_min;
				$desc							 .= " | max_coins:" . $promoCalcModel->pcn_max_coins;
				$desc							 .= " | min_coins" . $promoCalcModel->pcn_min_coins;
			}
			$promoCalcModel->pcn_desc = $desc;
			$promoCalcModel->save();

			$promoDateFilter				 = new PromoDateFilter();
			$promoDateFilter->pcd_promo_id	 = $promo->prm_id;
			$promoDateFilter->save();

			$promoEntityFilter				 = new PromoEntityFilter();
			$promoEntityFilter->pef_promo_id = $promo->prm_id;
			$promoEntityFilter->save();
		}
	}

	public function actionToprut()
	{
		$cty_id = 30595;

		$topRoutes	 = Route::model()->getRoutesByCityId($cty_id, 150);
		$pickupDate	 = Date("Y-m-d 06:00:00", strtotime("+ 20 days"));
		foreach ($topRoutes as $topRoute)
		{
			$route							 = [];
			$routeModel						 = new BookingRoute();
			$routeModel->brt_from_city_id	 = $topRoute['rut_from_city_id'];
			$routeModel->brt_to_city_id		 = $topRoute['rut_to_city_id'];

			$routeModel->brt_pickup_datetime = $pickupDate;
			if (DateTimeFormat::parseDateTime($routeModel->brt_pickup_datetime, $date, $time))
			{
				$routeModel->brt_pickup_date_date	 = $date;
				$routeModel->brt_pickup_date_time	 = $time;
			}
			$route[]			 = $routeModel;
			$partnerId			 = Yii::app()->params['gozoChannelPartnerId'];
			$quote				 = new Quote();
			$quote->routes		 = $route;
			$quote->tripType	 = 1;
			$quote->flexxi_type	 = 1;
			$quote->partnerId	 = $partnerId;
			$quote->quoteDate	 = date("Y-m-d H:i:s");
			$quote->pickupDate	 = $routeModel->brt_pickup_datetime;
			$quote->setCabTypeArr(NULL, true);
			$cabType			 = 'catypeArrIncFlexxi';
			$routeQuot			 = $quote->getQuote(3);

			$sedanPrice = ($routeQuot[3]->success && $routeQuot[3]->routeRates->baseAmount > 0) ? $routeQuot[3]->routeRates->baseAmount : 0;
			echo 'To / From ' . $topRoute['to_city'] . '  ' . $sedanPrice;
			echo "<br><br>";
		}
//	var_dump($topRoutes);
	}

	public function actionPushPartnerCabDriver()
	{
		$bkgid	 = 867284;
		$bmodel	 = Booking::model()->findByPk($bkgid);
		$success = false;
		$bmodel->bkg_bcb_id;
		if ($bmodel->bkg_agent_id == 450)
		{
			$typeAction	 = AgentApiTracking::TYPE_CAB_DRIVER_UPDATE;
			$mmtResponse = AgentMessages::model()->pushApiCall($bmodel, $typeAction);
			if ($mmtResponse->status == 1)
			{
				$success = true;
			}
		}
		return $success;
	}

	public function actionCriticalScore()
	{
		$criticalUpdate = BookingPref::model()->updateCritocalScore();
		if ($criticalUpdate)
		{
			echo $criticalUpdate . " Bookings are marked as critical ";
		}
	}

	public function actionVideoTutorial()
	{
		$videoName = Yii::app()->request->getParam('video');

		$arrVideoTutorial				 = [];
		$arrVideoTutorial['vendorjoin']	 = 'https://youtu.be/AfbwgIJN0H0';

		$videoName = trim($videoName);
		if ($videoName != '' && array_key_exists($videoName, $arrVideoTutorial))
		{
			$videoUrl = $arrVideoTutorial[$videoName];
			$this->redirect($videoUrl, true, 301);
		}
		else
		{
			throw new CHttpException(404, "Invalid Url", 404);
		}
	}

	public function actionPushNotificationRedirect()
	{
		$videoUrl = 'https://youtu.be/qWf409Iy7UY';
		$this->redirect($videoUrl, true, 301);
	}

	public function actionHelpline()
	{
		$isDeskTopTheme = Yii::app()->request->getParam('desktheme');
		if ($isDeskTopTheme == 1)
		{
			
		}
		$vndid	 = 0;
		$userId	 = UserInfo::getUserId();

		if ($userId > 0)
		{
			$umodel		 = Users::model()->findByPk($userId);
			$contactId	 = $umodel->usr_contact_id;
			$cttModel	 = Contact::model()->getByUserId($userId);
			if ($cttModel)
			{
				$contactId = $cttModel->ctt_id;
			}

//			$this->showCallbackQue();

			$entityType	 = UserInfo::TYPE_VENDOR;
			$vnd		 = ContactProfile::getEntityById($contactId, $entityType);
			$vndid		 = $vnd['id'];
		}
		$this->pageTitle = "Support Helpline";
		$this->renderPartial('helpline', array('isContactVendor' => $vndid, 'userId' => $userId));
	}

	public function showCallbackQue($refType = 0)
	{
		$userId			 = UserInfo::getUserId();
		$umodel			 = Users::model()->findByPk($userId);
		$contactId		 = $umodel->usr_contact_id;
		$haveCallback	 = FollowUps::checkActiveCallback($contactId, $refType);
		if ($haveCallback > 0)
		{

			$contactId	 = $umodel->usr_contact_id;
			$followupId	 = FollowUps::getIdByContact($contactId, $refType);
			$success	 = false;
			/** @var FollowUps $fpModel */
			$fpModel	 = FollowUps::model()->findbyPk($followupId);

//			$waitTime	 = FollowUps::calculateWaitingTimeByReftype($fpModel->fwp_ref_type);
			$queueData		 = FollowUps::getQueueNumber($fpModel->fwp_id, $fpModel->fwp_ref_type);
			$queNo			 = $queueData['queNo'];
			$waitTime		 = $queueData['waitTime'];
			$followupCode	 = $fpModel->fwp_unique_code;
			$contactNumber	 = $fpModel->fwp_contact_phone_no;
			$outputJs		 = Yii::app()->request->isAjaxRequest;
			$method			 = "render" . ($outputJs ? "Partial" : "");
			$this->$method('callbackConfirm', array(
				'success'		 => $success,
				'followupCode'	 => $followupCode,
				'followupId'	 => $followupId,
				'queNo'			 => $queNo,
				'contactNumber'	 => $contactNumber,
				'waitTime'		 => $waitTime), false, $outputJs);

			Yii::app()->end();
		}
		return true;
	}

	public function actionJoinus()
	{
		$baseURL	 = Yii::app()->params['baseURL'];
		$vendorJoin	 = $baseURL . "/join/vendor";
		$agentJoin	 = $baseURL . "/join/agent";
		$this->render('joinUs', array('vendorJoin' => $vendorJoin, 'agentJoin' => $agentJoin));
	}

	public function actionInsertContactDataIntoUser()
	{
		$sql	 = "SELECT vnd_contact_id FROM vendors
INNER JOIN booking_cab bcb ON bcb.bcb_vendor_id = vendors.vnd_id
INNER JOIN booking bkg ON bkg.bkg_bcb_id = bcb.bcb_id
WHERE vnd_active=1 AND vnd_user_id IS NULL AND bkg.bkg_status = 6 AND bkg.bkg_active=1 GROUP BY vendors.vnd_id";
		$query	 = DBUtil::queryAll($sql);
		foreach ($query as $val)
		{
			
		}
	}

	public function actionEpass()
	{
		$this->checkV2Theme();
		$success = false;
		$bkgID	 = Yii::app()->request->getParam('bkgid');
		$hash	 = Yii::app()->request->getParam('hash');
		//$bookingId	 = Yii::app()->shortHash->unHash($bkgID);
		if ($bkgID != Yii::app()->shortHash->unHash($hash))
		{
			throw new CHttpException(400, 'Invalid data. ');
		}
		$modeltrail		 = new BookingTrail();
		$modeltrail		 = BookingTrail::model()->find('btr_bkg_id=:bkg', ['bkg' => $bkgID]);
		//$modeltrail->btr_id	;
		$data			 = BookingCab::getDetailsByBkgid($bkgID);
		$uploadedFile	 = CUploadedFile ::getInstance($modeltrail, "btr_epass");
		if (isset($_POST))
		{
			if ($uploadedFile != '')
			{
				$path					 = BookingTrail::model()->uploadEpass($uploadedFile, $bkgID, 'epass');
				$modeltrail->btr_epass	 = $path;
				if ($modeltrail->save())
				{
					$success = true;
					$msg	 = "Thank you.E pass has been saved.";
				}
			}
		}
		$this->render('e-pass', array('model' => $data, 'modeltrail' => $modeltrail, 'success' => $success, 'msg' => $msg));
	}

	/*
	 * transfering or linking user with contact
	 */

	public function actionUpdateusercontact()
	{
		Logger::profile("test:Updateusercontact Started");
		$sqlCnt	 = " SELECT count(1) FROM users WHERE usr_contact_id IS NULL AND usr_active > 0  AND usr_email IS NOT NULL";
		$cnt	 = DBUtil::queryScalar($sqlCnt);

		$sql		 = " SELECT user_id, usr_email FROM users WHERE usr_contact_id IS NULL AND usr_active > 0 AND usr_email IS NOT NULL ORDER BY user_id DESC LIMIT 0,50";
		$arUsrData	 = DBUtil::queryAll($sql);
		if (empty($arUsrData))
		{
			exit();
		}

		foreach ($arUsrData as $usrData)
		{
			echo "user: " . $usrData['user_id'] . "====";
			Users::validateAndTransferContact($usrData);
		}
		//}

		Logger::profile("test:Updateusercontact Ended");
	}

	public function actionProcessbookinguser()
	{
		Logger::profile("test:Processbookinguser Started");
		$sqlCount	 = "SELECT count(1) from booking_user WHERE bkg_contact_id IS NULL AND bkg_user_id IS NOT NULL";
		$cnt		 = DBUtil::queryScalar($sqlCount);
		for ($i = 0; $i < $cnt; $i = $i + 250)
		{
			$sql		 = "SELECT bui_id, bkg_user_email FROM booking_user WHERE bkg_contact_id IS NULL AND bkg_user_id IS NOT NULL LIMIT $i,250";
			$bkgUserData = DBUtil::query($sql);
			if (empty($bkgUserData))
			{
				exit();
			}
			foreach ($bkgUserData as $bkgUsrData)
			{
				echo "bui_id: " . $bkgUsrData['bui_id'] . "====";
				$response = BookingUser::createContactFromUser($bkgUsrData);
			}
		}
		Logger::profile("test:Processbookinguser Ended");
	}

	public function actionVerifyusercontact()
	{
		Logger::profile("test:Verifyusercontact Started");

		$sqlCnt	 = "SELECT Count(1)
                    FROM users u
                    INNER JOIN booking_user bu ON bu.bkg_user_id = u.user_id
                    INNER JOIN booking bkg ON bkg.bkg_id = bu.bui_bkg_id
                    WHERE bkg.bkg_status IN (5,6,7) AND u.usr_active = 1 AND bkg.bkg_active=1";
		$cnt	 = DBUtil::queryScalar($sqlCnt);
		for ($i = 0; $i < $cnt; $i = $i + 1000)
		{
			$sql		 = "SELECT u.user_id,
                            u.usr_name,
							u.usr_lname,
							u.usr_email,
							u.usr_mobile,
							u.usr_country_code,
                            u.usr_contact_id
							FROM   users u
								   INNER JOIN booking_user bu
										   ON bu.bkg_user_id = u.user_id
								   INNER JOIN booking bkg
										   ON bkg.bkg_id = bu.bui_bkg_id
							WHERE  bkg.bkg_status IN ( 5, 6, 7 )
								   AND u.usr_active = 1
								   AND bkg.bkg_active = 1
							GROUP  BY u.user_id  LIMIT $i,1000";
			$arUsrData	 = DBUtil::query($sql);
			if (empty($arUsrData))
			{
				exit();
			}

			foreach ($arUsrData as $usrData)
			{
				echo "user: " . $usrData['user_id'] . "====";
				Users::verifyContactItem($usrData);
			}
		}
		Logger::profile("test:Verifyusercontact Ended");
	}

	public function actionCallBack()
	{
//		$this->checkForMobileTheme();
		$this->pageTitle = "New Booking Helpline";

		$model		 = new FollowUps();
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('cbkReasonList', array('model' => $model, 'success' => true));
	}

	public function actionNewBkgCallback()
	{
		$model = new FollowUps();

		$userId	 = UserInfo::getUserId();
		$refType = Yii::app()->request->getParam('reftype');
		if (!$userId)
		{
//			throw new CHttpException(403, "Login Required");
			http_response_code(403);
			Yii::app()->end();
		}
		$model->fwp_ref_type = $refType;

		if ($userId > 0 && $refType > 0)
		{
			$umodel		 = Users::model()->findByPk($userId);
			$contactId	 = ContactProfile::getByEntityId($userId);
			if ($refType == 4)
			{
				$entityType	 = UserInfo::TYPE_VENDOR;
				$vnd		 = ContactProfile::getEntityById($contactId, $entityType);
				$vndid		 = $vnd['id'];
				if (!$vndid)
				{
					echo "There is no vendor attached with your contact.";
					Yii::app()->end();
				}
			}
			$isprimary		 = true;
			$primaryPhone	 = ContactPhone::getContactNumber($contactId);

			$model->fwp_contact_phone_no = $primaryPhone;
		}
		skipall:
		$this->showCallbackQue($refType);
		$waitTime	 = FollowUps::calculateWaitingTimeByReftype($refType);
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('newBkgCallback', array('model'			 => $model,
			'umodel'		 => $umodel, 'userId'		 => $userId,
			'refType'		 => $refType, 'waitTime'		 => $waitTime, 'primaryPhone'	 => $primaryPhone), false, $outputJs);
	}

	public function actionStoreCallBackData()
	{
		$returnSet	 = new ReturnSet();
		$reqData	 = Yii::app()->request->getParam('FollowUps');
		$userId		 = UserInfo::getUserId();
		if (!$userId)
		{
			$returnSet->setMessage('Login required');
		}
		$umodel		 = Users::model()->findByPk($userId);
		$isMobile	 = Yii::app()->request->getParam('ismobile');
		$contactId	 = $umodel->usr_contact_id;
		$cttModel	 = Contact::model()->getByUserId($userId);
		if ($cttModel)
		{
			$contactId = $cttModel->ctt_id;
		}

		$success = false;
		if (isset($reqData))
		{
			try
			{
				$entityType	 = UserInfo::TYPE_CONSUMER;
				$platform	 = ($isMobile == 1) ? FollowUps::PLATFORM_WEB_MOBILE : FollowUps::PLATFORM_WEB_DESKTOP;
				$returnSet	 = FollowUps::storeCMBData($reqData, $userId, $entityType, $platform);
			}
			catch (Exception $exc)
			{
				echo $exc->getMessage();
				Yii::app()->end();
			}
		}
		if (!$returnSet->getStatus())
		{
			echo $returnSet->getMessage();
			Yii::app()->end();
		}
		$this->showCallbackQue();
	}

	public function actionCallmebackque()
	{

		$userId		 = UserInfo::getUserId();
		$umodel		 = Users::model()->findByPk($userId);
		$contactId	 = $umodel->usr_contact_id;

		$success = false;

		$queNo		 = FollowUps::countWaitingFollowupByContact($contactId);
		$waitTime	 = FollowUps::getAvgWaitingTimeByContact($contactId);

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('callbackConfirm', array('success'	 => $success, 'queNo'		 => $queNo,
			'waitTime'	 => $waitTime), false, $outputJs);

		Yii::app()->end();
	}

	public function actionRefreshCMBQue()
	{

		$userId		 = UserInfo::getUserId();
		$umodel		 = Users::model()->findByPk($userId);
		$contactId	 = $umodel->usr_contact_id;
		$queNo		 = FollowUps::countWaitingFollowupByContact($contactId);
		echo CJSON::encode(['queNo' => $queNo]);
		Yii::app()->end();
	}

	public function actionDeactivatCallBack()
	{
		$fwpId		 = Yii::app()->request->getParam('fwpId');
		$isMobile	 = Yii::app()->request->getParam('ismobile');
		$userId		 = UserInfo::getUserId();
		$umodel		 = Users::model()->findByPk($userId);
		$contactId	 = $umodel->usr_contact_id;

		$checkActive = FollowUps::checkUnAssigned($fwpId);
//		if ($checkActive > 0)
//		{
//			echo CJSON::encode(['success' => true, 'message' => 'The follow up is assigned to our representative. You will get a call soon.']);
//			Yii::app()->end();
//		}
		FollowUps::deactivateEntry($fwpId, $contactId);
//		FollowUps::deactivateAllEntry($contactId);
		if ($isMobile == 1)
		{
			echo CJSON::encode(['status' => 0]);
		}
		else
		{
			echo CJSON::encode(['success' => true, 'message' => 'The follow up is cancelled']);
		}
		Yii::app()->end();
	}

	public function actionValidatePhone()
	{
		$phone	 = Yii::app()->request->getParam('phone');
		$phone	 = trim(str_replace(' ', '', $phone));
		$success = true;
		$text	 = '';
		try
		{
			if (!Filter::validatePhoneNumber($phone))
			{
				throw new Exception('Invalid phone number');
			}
		}
		catch (Exception $exc)
		{
			$text	 = '<span style="color: red;font-size: 15px;">*</span>Invalid phone number';
			$success = false;
		}
		skipVal:

		$data = ['success' => $success, 'text' => $text];
		echo json_encode($data);
	}

	public function actionValidateBookingidForCMB()
	{
		$refid	 = Yii::app()->request->getParam('refid');
		$reftype = Yii::app()->request->getParam('reftype');
		$userId	 = UserInfo::getUserId();
		$success = true;
		$text	 = '';
		if (in_array($reftype, [2]) || ($reftype == 4 && trim($refid) != '' ))
		{
			$text = '<i class="fa fa-check"></i>';
			if (trim($refid) == '')
			{
				$success = false;
				$text	 = '<span style="color: red;font-size: 15px;">*</span> Booking id required';
				goto skipVal;
			}
			switch ((int) $reftype)
			{
				case 2:
					$bookingCode = BookingSub::getbyBookingLastDigits($refid);
					if (!$bookingCode)
					{
						$success = false;
						$text	 = '<i class="fa fa-times"></i> The booking id is invalid. Please enter correct id.';
						goto skipVal;
					}
					$bookingCode = BookingSub::getCodebyUserIdnId($userId, $refid);
					if (!$bookingCode)
					{
						$success = false;
						$text	 = '<i class="fa fa-times"></i> The booking does not belongs to the current user account';
					}
					break;
				case 4:
					$umodel		 = Users::model()->findByPk($userId);
					$contactId	 = $umodel->usr_contact_id;
					if (!$contactId)
					{
						$contactId = ContactProfile::getByUserId($userId);
					}
					$entityType	 = UserInfo::TYPE_VENDOR;
					$vnd		 = ContactProfile::getEntityById($contactId, $entityType);
					$vndid		 = $vnd['id'];
					$bookingCode = BookingSub::getCodebyVndIdnId($vndid, $refid);
					if (!$bookingCode)
					{
						$success = false;
						$text	 = '<i class="fa fa-times"></i> Wrong booking id';
					}
					break;

				default:
					break;
			}
			skipVal:
		}
		$data = ['success' => $success, 'text' => $text];
		echo json_encode($data);
	}

	public function actionVendorAuthentication()
	{
		$provider	 = $_REQUEST['fetchdata']['provider'];
		$identifier	 = $_REQUEST['fetchdata']['identifier'];
		$vndId		 = Contact::isApproved($_REQUEST['contactId']);
		// $update = Users::updateIreadVal($_REQUEST['telegramId'], $provider, $identifier);
		/** @var Vendors $vndModel */
		$vndModel	 = Vendors::model()->findByPk($vndId);
		if ($vndModel)
		{
			$getLoginDetails = Users::getSocialLoginId($provider, $identifier);
			if ($vndModel->vnd_user_id == $getLoginDetails['user_id'] && $_REQUEST['telegramId'] == $getLoginDetails['iread_id'])
			{
				$typeAction = AgentApiTracking::TYPE_TELEGRAM_VENDOR_AUTHENTICATION;
				VendorProfile::model()->pushApiCall($vndModel, $typeAction, $_REQUEST['telegramId']);
				$this->redirect('/');
			}
			else
			{
				$this->redirect('verifyVendor');
			}
		}
		$this->redirect('/');
	}

	public function actionDownloadDriverApp()
	{
		$url = "https://play.google.com/store/apps/details?id=com.gozocabs.driver";
		header("Location: " . $url);
		exit();

		/* $this->pageTitle		 = "Download Driver App";
		  $type					 = Yii::app()->request->getParam('app');
		  if ($type == 1)
		  {
		  $this->layout = "head";
		  }
		  $this->render('downloaddriverapp', array('app' => true)); */
	}

	public function actionDownloadPartnerApp()
	{
//		$jsonDownload	 = Config::get('vendor.app.download');
//		$playActive		 = $arrDownload['playstore']['active'];
//		$spaceActive	 = $arrDownload['space']['active'];
//
//		if ($playActive == 1)
//		{
//			$downloadUrl = $arrDownload['playstore']['url'];
//		}
//		elseif ($spaceActive == 1)
//		{
//			$downloadUrl = $arrDownload['space']['url'];
//		}
//		else
//		{
//			$downloadUrl = "https://play.google.com/store/apps/details?id=com.gozocabs.vendor";
//		}

		$downloadUrl = "https://gozo-files.sgp1.digitaloceanspaces.com/apps/GozoVendor4.3.110618.apk";
		#$downloadUrl = "https://play.google.com/store/apps/details?id=com.gozocabs.vendor";
		header("Location: " . $downloadUrl);
		exit();
	}

	public function actionGozoAccount()
	{
		$userId	 = UserInfo::getUserId();
		$success = false;
		$view	 = 'checkgozoaccount';
		$request = Yii::app()->request;
		$step	 = $request->getParam('step', 1);
		if (isset($_POST['checkaccount']))
		{
			$chkAccount = $request->getParam('checkaccount');

			switch ($chkAccount)
			{
				case 1:
					$this->redirect(['users/loginVO']);
					break;
				case 2:
					$this->actionCabSegmentation();
					break;
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->renderAuto($view, array('success' => $success), false, $outputJs);
		Yii::app()->end();
	}

	public function actionCabSegmentation()
	{
		$userId	 = UserInfo::getUserId();
		$success = false;
		if (isset($_POST['cabsegmentation']))
		{
			$cabsegmentation = $_POST['cabsegmentation'];
			switch ($cabsegmentation)
			{
				case 1:
					$this->renderAuto('login', array(), false, true);
				case 2:
					$this->renderAuto('servicetype', array(), false, true);
					break;
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('cabsegmentation', array(), false, true);
		exit;
	}

	public function actionQRLanding()
	{

		$code		 = Yii::app()->request->getParam('sid');
		$location	 = Yii::app()->request->getParam('location');
		if ($code != '')
		{
			$qrDetails = QrCode::getAgentId($code);

			$agentId										 = $qrDetails['qrc_agent_id'];
			Yii::app()->request->cookies['gozo_agent_id']	 = new CHttpCookie('gozo_agent_id', $agentId);
		}
	}

	public function actionsNavbar()
	{
		$this->renderPartial("users/navbarsign", [], false, true);
	}

	public function actionRefreshHomeNav()
	{
		$this->renderPartial('homeNav', [], false, true);
	}

	public function actionGettcities()
	{
		$arrCities = Cities::model()->getJSONAllCitiesbyQuery();
		echo $arrCities;
		Yii::app()->end();
	}

	public function actionMapmyIndia()
	{

		$this->renderPartial("mapmyIndia", [], false, true);
	}

	public function actionSearchplace()
	{
		$this->renderPartial("searchplace", [], false, true);
	}

	public function actionConfig()
	{
		$key	 = 'maxallowable';
		$usage	 = Config::getValue("gozocoin.promo.usage", $key);
		print_r($usage);
	}

	public function actionVc()
	{
		$row['bse_bkg_id']	 = 3147967;
		$row['bse_id']		 = 3380558;
		BookingScheduleEvent::vendorCompensationByBooking($row);
	}

	public function actionPredictions()
	{
		Autocomplete::importFromMaster();
//
//	 $excecute = DBUtil::execute("INSERT INTO autocomplete (`atc_keyword`, `atc_city_id`, `act_source`, `act_predictions`, `act_create`, `act_update`) SELECT  `atc_keyword`, `atc_city_id`, `act_source`, `act_predictions`, `act_create`, `act_update` FROM autocomplete_master;");
//		if ($excecute > 0)
//		{
//			DBUtil::execute("TRUNCATE autocomplete_master");
//		}
	}

	public function actionRAP()
	{
		$this->renderPartial("rap", [], false, true);
	}

	public function actionRDA()
	{
//https://apis.mapmyindia.com/advancedmaps/v1/a2d58bc4-15bc-49ee-bafe-38e2b49b25cf/route_adv/driving/77.131123,28.552413;77.131125,28.552415?steps=true&rtype=1
		$data = '[{"lat":28.552413,"lng":77.131123},{"lat":28.552415,"lng":77.131125}]'; //Yii::app()->request->getParam('data');
		if (!$data)
		{
			throw new CHttpException(404, "Data not found", 404);
		}
		$dataArr = CJSON::decode($data, true);
//echo "<pre>";
//print_r($dataArr);exit;
		$medium	 = 2; //($dataArr['medium']==''||$dataArr['medium']==null ) ? '' : $dataArr['medium'];

		$origin		 = $dataArr[0]['lng'] . ',' . $dataArr[0]['lat'];
		$destination = $dataArr[1]['lng'] . ',' . $dataArr[1]['lat'];
		$coordinates = $origin . ';' . $destination;
		$session	 = Filter::guidv4();
		$directions	 = RouteDirectionMaster::getDirections($coordinates, $medium, $session);

		print_r($directions);
	}

	public function actionJDS()
	{

		$aa	 = '[{"lat":28.552413,"lng":77.131123},{"lat":28.552415,"lng":77.131125}]';
		$dd	 = CJSON::decode($aa);
		echo"<pre>";
		print_r($dd['routes'][0]);
	}

	public function actionGa4()
	{
		header("access-control-allow-origin: https://www-gozocabs-com.cdn.ampproject.org");
		header("access-control-allow-credentials: true");
		header("content-type: application/json");

		echo file_get_contents(PUBLIC_PATH . DIRECTORY_SEPARATOR . 'ga4.json');
		Yii::app()->end();
	}

	public function actionDummyBooking()
	{
		$bookingId = $this->holdDummyBooking();
		$this->confirmDummyBooking($bookingId);
		$this->pushBookingsToHornok();
	}

	public function actionDummyOneWayBooking()
	{
		$bookingId = $this->holdDummyOneWayBooking();
		$this->confirmDummyBooking($bookingId);
		$this->pushBookingsToHornok("OneWay");
	}

	public function actionDummyRoundTripBooking()
	{
		$bookingId = $this->holdDummyRoundTripBooking();
		$this->confirmDummyBooking($bookingId);
		$this->pushBookingsToHornok("RoundTrip");
	}

	public function actionDummyMultiCityBooking()
	{
		$bookingId = $this->holdDummyMultiCityBooking();
		$this->confirmDummyBooking($bookingId);
		$this->pushBookingsToHornok("MultiCity");
	}

	public function holdDummyBooking()
	{
		$url	 = 'http://gozotech1.ddns.net:6192/api/cpapi/booking/hold?api=3be6a9331b8649e7c8a7f14cdfa902cf';
		$refId	 = 'TEST' . date("YmdHis");
		$date	 = date("Y-m-d", strtotime("+1 day")) . " 10:00:00";
		$date	 = urldecode(Yii::app()->request->getParam('dt', $date));
		$stDate	 = date("Y-m-d", strtotime($date));
		$stTime	 = date("H:i:s", strtotime($date));

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL				 => $url,
			CURLOPT_RETURNTRANSFER	 => true,
			CURLOPT_ENCODING		 => '',
			CURLOPT_MAXREDIRS		 => 10,
			CURLOPT_TIMEOUT			 => 0,
			CURLOPT_FOLLOWLOCATION	 => true,
			CURLOPT_HTTP_VERSION	 => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST	 => 'POST',
			CURLOPT_POSTFIELDS		 => '{
				"tnc": 1,
				"referenceId": "' . $refId . '",
				"tripType": 1,
				"cabType": 3,
				"fare": {
					"advanceReceived": 750,
					"totalAmount": 1600
				},
				"platform": {
					"deviceName": "",
					"ip": "",
					"type": ""
				},
				"apkVersion": "",
				"sendEmail": 0,
				"sendSms": 0,
				"routes": [
					{
						"startDate": "' . $stDate . '",
						"startTime": "' . $stTime . '",
						"source": {
							"address": "Bengaluru",
							"coordinates": {
								"latitude": 12.9087928999999999035708242445252835750579833984375,
								"longitude": 77.64249780000000100699253380298614501953125
							}
						},
						"destination": {
							"address": "Bengaluru airport",
							"coordinates": {
								"latitude": 13.2007713317871004932158029987476766109466552734375,
								"longitude": 77.71022796630859375
							}
						}
					}
				],
				"traveller": {
					"firstName": "Tapesh",
					"lastName": "",
					"primaryContact": {
						"code": 91,
						"number": 9876543210
					},
					"alternateContact": {
						"code": 91,
						"number": ""
					},
					"email": "kk@gmail.com",
					"companyName": "",
					"address": "",
					"gstin": ""
				},
				"additionalInfo": {
					"specialInstructions": "cab should be clean",
					"noOfPerson": 4,
					"noOfLargeBags": 0,
					"noOfSmallBags": 3,
					"carrierRequired": 0,
					"kidsTravelling": 0,
					"seniorCitizenTravelling": 0,
					"womanTravelling": 0
				}
			}',
			CURLOPT_HTTPHEADER		 => array(
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);
		curl_close($curl);

		#$response = '{"success":true,"data":{"bookingId":"QT303498131","referenceId":"TEST20230623121430","statusDesc":"Quoted","statusCode":1,"tripType":12,"tripDesc":"Airport Packages","cabType":3,"startDate":"2023-06-24","startTime":"10:00:00","totalDistance":56,"estimatedDuration":75,"id":3498128,"verification_code":"909142","routes":[{"startDate":"2023-06-24","startTime":"10:00:00","source":{"code":34786,"isAirport":0,"address":"Bengaluru","name":"Bangalore Urban","coordinates":{"latitude":12.9087929,"longitude":77.6424978}},"destination":{"code":31001,"isAirport":0,"address":"Bengaluru airport","name":"Kempegowda International Airport (blr), Bangalore","coordinates":{"latitude":13.200771331787,"longitude":77.710227966309}}}],"cabRate":{"cab":{"id":3,"type":"Sedan (Value)","category":"Sedan","sClass":"Value","instructions":["Car will be of any model in car category you choose"],"image":"http:\/\/localhost:84\/images\/cabs\/car-etios.jpg","seatingCapacity":4,"bagCapacity":2,"bigBagCapaCity":1,"isAssured":"0","cabCategory":{"id":3,"type":"Sedan","catClass":"Value","scvParent":"0","scvVehicleId":"3","scvVehicleModel":"0","scvVehicleServiceClass":"1","scvmodel":"Sedan (Value)","catRank":"2","catClassRank":"2"}},"fare":{"baseFare":1424,"driverAllowance":0,"extraPerMinCharge":0,"gst":76,"gstRate":5,"tollIncluded":1,"stateTaxIncluded":1,"stateTax":0,"vendorAmount":1209,"vendorCollected":0,"tollTax":0,"nightPickupIncluded":1,"nightDropIncluded":1,"parkingCharge":0,"parkingIncluded":0,"discount":0,"extraPerKmRate":23,"customerPaid":0,"dueAmount":1605,"totalAmount":1605,"gozoCoins":0,"promoCoins":0,"minPay":214,"minPayPercent":25,"netBaseFare":1424,"airportChargeIncluded":1,"additionalCharge":0,"advanceSlab":[{"percentage":25,"value":214,"label":"Pay (25%)"},{"percentage":50,"value":428,"label":"Pay (50%)"},{"percentage":100,"value":855,"label":"Full Payment (100%)"}],"airportFee":105,"addOnCharge":0,"extraTimeCap":30,"extraPerMinRate":0}},"partnerTransactionDetails":{"commission":0,"markup":0,"creditsUsed":0,"advance":750,"additionalAmount":0,"discount":0},"payUrl":"https:\/\/www.gozocabs.com\/bkpn\/3498128\/2Zd2G\/p\/pCI4y","isGozoNow":0}}';
		$obj = json_decode($response);
		return $obj->data->bookingId;
	}

	public function holdDummyOneWayBooking()
	{
		$url	 = 'http://gozotech1.ddns.net:6192/api/cpapi/booking/hold?api=3be6a9331b8649e7c8a7f14cdfa902cf';
		$refId	 = 'TEST' . date("YmdHis");
		$date	 = date("Y-m-d", strtotime("+1 day")) . " 10:00:00";
		$date	 = urldecode(Yii::app()->request->getParam('dt', $date));
		$stDate	 = date("Y-m-d", strtotime($date));
		$stTime	 = date("H:i:s", strtotime($date));

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL				 => $url,
			CURLOPT_RETURNTRANSFER	 => true,
			CURLOPT_ENCODING		 => '',
			CURLOPT_MAXREDIRS		 => 10,
			CURLOPT_TIMEOUT			 => 0,
			CURLOPT_FOLLOWLOCATION	 => true,
			CURLOPT_HTTP_VERSION	 => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST	 => 'POST',
			CURLOPT_POSTFIELDS		 => '{
				"tnc": 1,
				"referenceId": "' . $refId . '",
				"tripType": 1,
				"cabType": 3,
				"fare": {
					"advanceReceived": 750,
					"totalAmount": 1600
				},
				"platform": {
					"deviceName": "",
					"ip": "",
					"type": ""
				},
				"apkVersion": "",
				"sendEmail": 0,
				"sendSms": 0,
				"routes": [
					{
						"startDate": "' . $stDate . '",
						"startTime": "' . $stTime . '",
						"source": {
							"address": "Bengaluru",
							"coordinates": {
								"latitude": 12.9087928999999999035708242445252835750579833984375,
								"longitude": 77.64249780000000100699253380298614501953125
							}
						},
						"destination": {
							"address": "Bengaluru International Centre",
							"coordinates": {
								"latitude": 12.9666878,
								"longitude": 77.6306769
							}
						}
					}
				],
				"traveller": {
					"firstName": "Tapesh",
					"lastName": "",
					"primaryContact": {
						"code": 91,
						"number": 9876543210
					},
					"alternateContact": {
						"code": 91,
						"number": ""
					},
					"email": "kk@gmail.com",
					"companyName": "",
					"address": "",
					"gstin": ""
				},
				"additionalInfo": {
					"specialInstructions": "cab should be clean",
					"noOfPerson": 4,
					"noOfLargeBags": 0,
					"noOfSmallBags": 3,
					"carrierRequired": 0,
					"kidsTravelling": 0,
					"seniorCitizenTravelling": 0,
					"womanTravelling": 0
				}
			}',
			CURLOPT_HTTPHEADER		 => array(
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);
		curl_close($curl);

		#$response = '{"success":true,"data":{"bookingId":"QT303498131","referenceId":"TEST20230623121430","statusDesc":"Quoted","statusCode":1,"tripType":12,"tripDesc":"Airport Packages","cabType":3,"startDate":"2023-06-24","startTime":"10:00:00","totalDistance":56,"estimatedDuration":75,"id":3498128,"verification_code":"909142","routes":[{"startDate":"2023-06-24","startTime":"10:00:00","source":{"code":34786,"isAirport":0,"address":"Bengaluru","name":"Bangalore Urban","coordinates":{"latitude":12.9087929,"longitude":77.6424978}},"destination":{"code":31001,"isAirport":0,"address":"Bengaluru airport","name":"Kempegowda International Airport (blr), Bangalore","coordinates":{"latitude":13.200771331787,"longitude":77.710227966309}}}],"cabRate":{"cab":{"id":3,"type":"Sedan (Value)","category":"Sedan","sClass":"Value","instructions":["Car will be of any model in car category you choose"],"image":"http:\/\/localhost:84\/images\/cabs\/car-etios.jpg","seatingCapacity":4,"bagCapacity":2,"bigBagCapaCity":1,"isAssured":"0","cabCategory":{"id":3,"type":"Sedan","catClass":"Value","scvParent":"0","scvVehicleId":"3","scvVehicleModel":"0","scvVehicleServiceClass":"1","scvmodel":"Sedan (Value)","catRank":"2","catClassRank":"2"}},"fare":{"baseFare":1424,"driverAllowance":0,"extraPerMinCharge":0,"gst":76,"gstRate":5,"tollIncluded":1,"stateTaxIncluded":1,"stateTax":0,"vendorAmount":1209,"vendorCollected":0,"tollTax":0,"nightPickupIncluded":1,"nightDropIncluded":1,"parkingCharge":0,"parkingIncluded":0,"discount":0,"extraPerKmRate":23,"customerPaid":0,"dueAmount":1605,"totalAmount":1605,"gozoCoins":0,"promoCoins":0,"minPay":214,"minPayPercent":25,"netBaseFare":1424,"airportChargeIncluded":1,"additionalCharge":0,"advanceSlab":[{"percentage":25,"value":214,"label":"Pay (25%)"},{"percentage":50,"value":428,"label":"Pay (50%)"},{"percentage":100,"value":855,"label":"Full Payment (100%)"}],"airportFee":105,"addOnCharge":0,"extraTimeCap":30,"extraPerMinRate":0}},"partnerTransactionDetails":{"commission":0,"markup":0,"creditsUsed":0,"advance":750,"additionalAmount":0,"discount":0},"payUrl":"https:\/\/www.gozocabs.com\/bkpn\/3498128\/2Zd2G\/p\/pCI4y","isGozoNow":0}}';
		$obj = json_decode($response);
		return $obj->data->bookingId;
	}

	public function holdDummyRoundTripBooking()
	{
		$url	 = 'http://gozotech1.ddns.net:6192/api/cpapi/booking/hold?api=3be6a9331b8649e7c8a7f14cdfa902cf';
		$refId	 = 'TEST' . date("YmdHis");
		$date	 = date("Y-m-d", strtotime("+1 day")) . " 10:00:00";
		$date	 = urldecode(Yii::app()->request->getParam('dt', $date));

		$stDate	 = date("Y-m-d", strtotime($date));
		$stTime	 = date("H:i:s", strtotime($date));

		$date1 = date("Y-m-d H:i:s", strtotime("+1 day", strtotime($stDate . " " . $stTime)));

		$stDate1 = date("Y-m-d", strtotime($date1));
		$stTime1 = date("H:i:s", strtotime($date1));

		//$your_date = strtotime("1 day", strtotime("2016-08-24"));
		//$new_date = date("Y-m-d", $your_date);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL				 => $url,
			CURLOPT_RETURNTRANSFER	 => true,
			CURLOPT_ENCODING		 => '',
			CURLOPT_MAXREDIRS		 => 10,
			CURLOPT_TIMEOUT			 => 0,
			CURLOPT_FOLLOWLOCATION	 => true,
			CURLOPT_HTTP_VERSION	 => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST	 => 'POST',
			CURLOPT_POSTFIELDS		 => '{
				"tnc": 1,
				"referenceId": "' . $refId . '",
				"tripType": 2,
				"cabType": 3,
				"fare": {
					"advanceReceived": 4000,
					"totalAmount": 10600
				},
				"platform": {
					"deviceName": "",
					"ip": "",
					"type": ""
				},
				"apkVersion": "",
				"sendEmail": 0,
				"sendSms": 0,
				"routes": [
					{
						"startDate": "' . $stDate . '",
						"startTime": "' . $stTime . '",
						"source": {
							"address": "Bengaluru",
							"coordinates": {
								"latitude": 12.9087928999999999035708242445252835750579833984375,
								"longitude": 77.64249780000000100699253380298614501953125
							}
						},
						"destination": {
							"address": "Mahabalipuram, Tamil Nadu",
							"coordinates": {
								"latitude": 12.6223664,
								"longitude": 80.1542331
							}
						}
					},
					{
						"startDate": "' . $stDate1 . '",
						"startTime": "' . $stTime1 . '",
						"source": {
							"address": "Mahabalipuram, Tamil Nadu",
							"coordinates": {
								"latitude": 12.6223664,
								"longitude": 80.1542331
							}
						},
						"destination": {
							"address": "Bengaluru",
							"coordinates": {
								"latitude": 12.9087928999999999035708242445252835750579833984375,
								"longitude": 77.64249780000000100699253380298614501953125
							}
						}
					}
				],
				"traveller": {
					"firstName": "Tapesh",
					"lastName": "",
					"primaryContact": {
						"code": 91,
						"number": 9876543210
					},
					"alternateContact": {
						"code": 91,
						"number": ""
					},
					"email": "kk@gmail.com",
					"companyName": "",
					"address": "",
					"gstin": ""
				},
				"additionalInfo": {
					"specialInstructions": "cab should be clean",
					"noOfPerson": 4,
					"noOfLargeBags": 0,
					"noOfSmallBags": 3,
					"carrierRequired": 0,
					"kidsTravelling": 0,
					"seniorCitizenTravelling": 0,
					"womanTravelling": 0
				}
			}',
			CURLOPT_HTTPHEADER		 => array(
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);
		curl_close($curl);

		#$response = '{"success":true,"data":{"bookingId":"QT303498131","referenceId":"TEST20230623121430","statusDesc":"Quoted","statusCode":1,"tripType":12,"tripDesc":"Airport Packages","cabType":3,"startDate":"2023-06-24","startTime":"10:00:00","totalDistance":56,"estimatedDuration":75,"id":3498128,"verification_code":"909142","routes":[{"startDate":"2023-06-24","startTime":"10:00:00","source":{"code":34786,"isAirport":0,"address":"Bengaluru","name":"Bangalore Urban","coordinates":{"latitude":12.9087929,"longitude":77.6424978}},"destination":{"code":31001,"isAirport":0,"address":"Bengaluru airport","name":"Kempegowda International Airport (blr), Bangalore","coordinates":{"latitude":13.200771331787,"longitude":77.710227966309}}}],"cabRate":{"cab":{"id":3,"type":"Sedan (Value)","category":"Sedan","sClass":"Value","instructions":["Car will be of any model in car category you choose"],"image":"http:\/\/localhost:84\/images\/cabs\/car-etios.jpg","seatingCapacity":4,"bagCapacity":2,"bigBagCapaCity":1,"isAssured":"0","cabCategory":{"id":3,"type":"Sedan","catClass":"Value","scvParent":"0","scvVehicleId":"3","scvVehicleModel":"0","scvVehicleServiceClass":"1","scvmodel":"Sedan (Value)","catRank":"2","catClassRank":"2"}},"fare":{"baseFare":1424,"driverAllowance":0,"extraPerMinCharge":0,"gst":76,"gstRate":5,"tollIncluded":1,"stateTaxIncluded":1,"stateTax":0,"vendorAmount":1209,"vendorCollected":0,"tollTax":0,"nightPickupIncluded":1,"nightDropIncluded":1,"parkingCharge":0,"parkingIncluded":0,"discount":0,"extraPerKmRate":23,"customerPaid":0,"dueAmount":1605,"totalAmount":1605,"gozoCoins":0,"promoCoins":0,"minPay":214,"minPayPercent":25,"netBaseFare":1424,"airportChargeIncluded":1,"additionalCharge":0,"advanceSlab":[{"percentage":25,"value":214,"label":"Pay (25%)"},{"percentage":50,"value":428,"label":"Pay (50%)"},{"percentage":100,"value":855,"label":"Full Payment (100%)"}],"airportFee":105,"addOnCharge":0,"extraTimeCap":30,"extraPerMinRate":0}},"partnerTransactionDetails":{"commission":0,"markup":0,"creditsUsed":0,"advance":750,"additionalAmount":0,"discount":0},"payUrl":"https:\/\/www.gozocabs.com\/bkpn\/3498128\/2Zd2G\/p\/pCI4y","isGozoNow":0}}';
		$obj = json_decode($response);
		return $obj->data->bookingId;
	}

	public function holdDummyMultiCityBooking()
	{
		$url	 = 'http://gozotech1.ddns.net:6192/api/cpapi/booking/hold?api=3be6a9331b8649e7c8a7f14cdfa902cf';
		$refId	 = 'TEST' . date("YmdHis");
		$date	 = date("Y-m-d", strtotime("+1 day")) . " 10:00:00";
		$date	 = urldecode(Yii::app()->request->getParam('dt', $date));

		$stDate	 = date("Y-m-d", strtotime($date));
		$stTime	 = date("H:i:s", strtotime($date));

		$date1 = date("Y-m-d H:i:s", strtotime("+1 day", strtotime($stDate . " " . $stTime)));

		$stDate1 = date("Y-m-d", strtotime($date1));
		$stTime1 = date("H:i:s", strtotime($date1));

		$date2 = date("Y-m-d H:i:s", strtotime("+1 day", strtotime($stDate1 . " " . $stTime1)));

		$stDate2 = date("Y-m-d", strtotime($date2));
		$stTime2 = date("H:i:s", strtotime($date2));

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL				 => $url,
			CURLOPT_RETURNTRANSFER	 => true,
			CURLOPT_ENCODING		 => '',
			CURLOPT_MAXREDIRS		 => 10,
			CURLOPT_TIMEOUT			 => 0,
			CURLOPT_FOLLOWLOCATION	 => true,
			CURLOPT_HTTP_VERSION	 => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST	 => 'POST',
			CURLOPT_POSTFIELDS		 => '{
				"tnc": 1,
				"referenceId": "' . $refId . '",
				"tripType": 3,
				"cabType": 3,
				"fare": {
					"advanceReceived": 15000,
					"totalAmount": 36000
				},
				"platform": {
					"deviceName": "",
					"ip": "",
					"type": ""
				},
				"apkVersion": "",
				"sendEmail": 0,
				"sendSms": 0,
				"routes": [
					{
						"startDate": "' . $stDate . '",
						"startTime": "' . $stTime . '",
						"source": {
							"address": "Bengaluru",
							"coordinates": {
								"latitude": 12.9087928999999999035708242445252835750579833984375,
								"longitude": 77.64249780000000100699253380298614501953125
							}
						},
						"destination": {
							"address": "Mangaluru International Airport",
							"coordinates": {
								"latitude": 12.9504767,
								"longitude": 74.8695487
							}
						}
					},
					{
						"startDate": "' . $stDate1 . '",
						"startTime": "' . $stTime1 . '",
						"source": {
							"address": "Mangaluru International Airport",
							"coordinates": {
								"latitude": 12.9504767,
								"longitude": 74.8695487
							}
						},
						"destination": {
							"address": "Mahabalipuram, Tamil Nadu",
							"coordinates": {
								"latitude": 12.6223664,
								"longitude": 80.1542331
							}
						}
					},
					{
						"startDate": "' . $stDate2 . '",
						"startTime": "' . $stTime2 . '",
						"source": {
							"address": "Mahabalipuram, Tamil Nadu",
							"coordinates": {
								"latitude": 12.6223664,
								"longitude": 80.1542331
							}
						},
						"destination": {
							"address": "railway station, Rossillon Street, Railway Quarters, Gnanapuram, Visakhapatnam, Andhra Pradesh",
							"coordinates": {
								"latitude": 17.7204436,
								"longitude": 83.2817283
							}
						}
					}
				],
				"traveller": {
					"firstName": "Tapesh",
					"lastName": "",
					"primaryContact": {
						"code": 91,
						"number": 9876543210
					},
					"alternateContact": {
						"code": 91,
						"number": ""
					},
					"email": "kk@gmail.com",
					"companyName": "",
					"address": "",
					"gstin": ""
				},
				"additionalInfo": {
					"specialInstructions": "cab should be clean",
					"noOfPerson": 4,
					"noOfLargeBags": 0,
					"noOfSmallBags": 3,
					"carrierRequired": 0,
					"kidsTravelling": 0,
					"seniorCitizenTravelling": 0,
					"womanTravelling": 0
				}
			}',
			CURLOPT_HTTPHEADER		 => array(
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);
		curl_close($curl);

		#$response = '{"success":true,"data":{"bookingId":"QT303498131","referenceId":"TEST20230623121430","statusDesc":"Quoted","statusCode":1,"tripType":12,"tripDesc":"Airport Packages","cabType":3,"startDate":"2023-06-24","startTime":"10:00:00","totalDistance":56,"estimatedDuration":75,"id":3498128,"verification_code":"909142","routes":[{"startDate":"2023-06-24","startTime":"10:00:00","source":{"code":34786,"isAirport":0,"address":"Bengaluru","name":"Bangalore Urban","coordinates":{"latitude":12.9087929,"longitude":77.6424978}},"destination":{"code":31001,"isAirport":0,"address":"Bengaluru airport","name":"Kempegowda International Airport (blr), Bangalore","coordinates":{"latitude":13.200771331787,"longitude":77.710227966309}}}],"cabRate":{"cab":{"id":3,"type":"Sedan (Value)","category":"Sedan","sClass":"Value","instructions":["Car will be of any model in car category you choose"],"image":"http:\/\/localhost:84\/images\/cabs\/car-etios.jpg","seatingCapacity":4,"bagCapacity":2,"bigBagCapaCity":1,"isAssured":"0","cabCategory":{"id":3,"type":"Sedan","catClass":"Value","scvParent":"0","scvVehicleId":"3","scvVehicleModel":"0","scvVehicleServiceClass":"1","scvmodel":"Sedan (Value)","catRank":"2","catClassRank":"2"}},"fare":{"baseFare":1424,"driverAllowance":0,"extraPerMinCharge":0,"gst":76,"gstRate":5,"tollIncluded":1,"stateTaxIncluded":1,"stateTax":0,"vendorAmount":1209,"vendorCollected":0,"tollTax":0,"nightPickupIncluded":1,"nightDropIncluded":1,"parkingCharge":0,"parkingIncluded":0,"discount":0,"extraPerKmRate":23,"customerPaid":0,"dueAmount":1605,"totalAmount":1605,"gozoCoins":0,"promoCoins":0,"minPay":214,"minPayPercent":25,"netBaseFare":1424,"airportChargeIncluded":1,"additionalCharge":0,"advanceSlab":[{"percentage":25,"value":214,"label":"Pay (25%)"},{"percentage":50,"value":428,"label":"Pay (50%)"},{"percentage":100,"value":855,"label":"Full Payment (100%)"}],"airportFee":105,"addOnCharge":0,"extraTimeCap":30,"extraPerMinRate":0}},"partnerTransactionDetails":{"commission":0,"markup":0,"creditsUsed":0,"advance":750,"additionalAmount":0,"discount":0},"payUrl":"https:\/\/www.gozocabs.com\/bkpn\/3498128\/2Zd2G\/p\/pCI4y","isGozoNow":0}}';
		$obj = json_decode($response);
		return $obj->data->bookingId;
	}

	public function confirmDummyBooking($bookingId)
	{
		$url = 'http://gozotech1.ddns.net:6192/api/cpapi/booking/confirm?api=3be6a9331b8649e7c8a7f14cdfa902cf';
		#$bookingId = "QT303498128";

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL				 => $url,
			CURLOPT_RETURNTRANSFER	 => true,
			CURLOPT_ENCODING		 => '',
			CURLOPT_MAXREDIRS		 => 10,
			CURLOPT_TIMEOUT			 => 0,
			CURLOPT_FOLLOWLOCATION	 => true,
			CURLOPT_HTTP_VERSION	 => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST	 => 'POST',
			CURLOPT_POSTFIELDS		 => '{
				"bookingId": "' . $bookingId . '"
			}',
			CURLOPT_HTTPHEADER		 => array(
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		#echo $response;
	}

	public function pushBookingsToHornok($bkgType = null)
	{
		$strBookings = "Below are the booking id pushed successfully:<br>";
		/* @var $bookingList booking */
		if ($bkgType == "OneWay")
		{
			$tripType	 = 1;
			$bookingList = Booking::getDummyTrip($tripType);
		}
		else if ($bkgType == "RoundTrip")
		{
			$tripType	 = 2;
			$bookingList = Booking::getDummyTrip($tripType);
		}
		else if ($bkgType == "MultiCity")
		{
			$tripType	 = 3;
			$bookingList = Booking::getDummyTrip($tripType);
		}
		else
		{
			$tripType	 = [12, 4];
			$bookingList = Booking::getAirport($tripType);
		}
		foreach ($bookingList as $data)
		{
			$model		 = booking::model()->findByPk($data['bkg_id']);
			$operatorId	 = Operator::getOperatorId($model->bkg_booking_type);
			$objOperator = Operator::getInstance($operatorId);

			/* @var $objOperator Operator */
			$objOperator = $objOperator->holdBooking($model->bkg_id, $operatorId);

			$strBookings .= "<br>" . $model->bkg_booking_id;
		}

		echo $strBookings;
	}

	public function actionApp()
	{
		$this->checkV3Theme();
		$this->pageTitle = "App";

		$iosApp		 = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
		$android	 = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
		$iosUrl		 = "https://apps.apple.com/us/app/gozocabs/id1398759012";
		$androidUrl	 = "https://play.google.com/store/apps/details?id=com.gozocabs.client&hl=en_IN&gl=US";

		if ($iosApp == true)
		{
			header("Location: $iosUrl", true, 301);
			Yii::app()->end();
		}
		else if ($android == true)
		{
			header("Location: $androidUrl");
			Yii::app()->end();
		}
		$this->renderAuto('app', array('model' => $model, 'ios' => $iosUrl, 'android' => $androidUrl));
	}

	public function actionAmpCallback()
	{
		$this->checkV3Theme();
		$this->render('ampcallback', array());
	}

	public function actionQRCode()
	{
		$code	 = Yii::app()->request->getParam('qr_code');
		$url	 = "/";
		if ($code != '')
		{
			$url .= "?sid={$code}";
		}
		$this->redirect($url, true, 301);
		exit();
	}

	public function actionAutocompleteTransfer()
	{
		$check = Filter::checkProcess("autocomplete address");
		if (!$check)
		{
			return;
		}

		Autocomplete::importFromMaster();
	}

	public function actionVLanding()
	{

		try
		{
			$referrer	 = $_SERVER['HTTP_REFERER'];
			$pReferrer	 = parse_url($referrer);
			$refHost	 = $pReferrer["host"];
			//  if ($refHost)
			//   {

			$hostArray = array("gozocabs", "gozo.cab", "192.168.1.179");

			if (!in_array($refHost, $hostArray))
			{
				$params	 = Filter::parseTrackingParams();
				$vUrl	 = $params['url'];
				if ($vUrl)
				{
					$model = new UsersSourceTracking();
					if ($model->add())
					{
						$url = $this->getURL($vUrl);
						$this->redirect($url);
					}
				}
			}
			//  }
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
		}
	}

	public function actionOffer()
	{
		$this->renderAuto('offer-type');
	}

	public function actionCoinsReminder()
	{

		$today	 = date('Y-m-d');
		$newDate = date('Y-m-d', strtotime('+15 days', strtotime($today)));

		$sql = "SELECT cr_contact_id, SUM(ucr_value - ucr_used) as coinHave, ucr_id, ucr_value, ucr_used, user_id, MIN(ucr_validity) as ucr_validity
					FROM `contact_profile` 
					INNER JOIN users ON cr_is_consumer = user_id 
					INNER JOIN user_credits ON ucr_user_id = user_id AND ucr_status = 1 
					WHERE cr_is_consumer > 0 AND cr_status=1 AND ucr_status = 1 
					AND ucr_validity BETWEEN '{$newDate} 00:00:00' AND '{$newDate} 23:59:59' AND ucr_validity IS NOT NULL 
					GROUP BY ucr_user_id 
					HAVING coinHave >= 50";

		$result = DBUtil::query($sql, DBUtil::SDB());
		foreach ($result as $value)
		{
			$expiryDate	 = $value['ucr_validity'];
			$coin		 = $value['coinHave'];
			$contactID	 = $value['cr_contact_id'];
			$userId		 = $value['user_id'];

			$contact = Contact::getDetails($contactID);
			$email	 = $contact['eml_email_address'];
			$code	 = $contact['phn_phone_country_code'];
			$number	 = $contact['phn_phone_no'];
			$name	 = $contact['ctt_first_name'] . ' ' . $contact['ctt_last_name'];

			//	Users::notifyCoinExpiry($userId, $expiryDate, $coin, $contactID,1,1);
			if ($code && $number)
			{

				Users::notifyCoinExpiry($userId, $expiryDate, $coin, $contactID, 1, 2);
			}
			if ($email)
			{

				Users::notifyCoinExpiry($userId, $expiryDate, $coin, $contactID, 1, 3);
			}
		}
	}

	public function actionCoinRecharge()
	{
		$giftCoinAmount	 = 300;
		$userId			 = '';
		$getEligibleUser = UserCredits::getEligibleUserForRecharge($userId, $giftCoinAmount);
		foreach ($getEligibleUser as $row)
		{
			$userGozoCoin	 = ($row['coinHave'] > 0 ? $row['coinHave'] : 0);
			$addToCoin		 = 1000;
			$validity		 = date('Y-m-d H:i:s', strtotime('+12 month'));
			$maxUseType		 = 1;

			$contactID	 = $row['cr_contact_id'];
			$userId		 = $row['user_id'];

			Logger::writeToConsole($userId . " - " . $contactID);

			$userCreditsModel = UserCredits::addAmount($userId, 1, $addToCoin, "Promotional coins credited", $maxUseType, NULL, $validity);

			if ($userCreditsModel)
			{
				Logger::writeToConsole($userId . " - " . $contactID);

				$contact = Contact::getDetails($contactID);
				$email	 = $contact['eml_email_address'];
				$code	 = $contact['phn_phone_country_code'];
				$number	 = $contact['phn_phone_no'];
				$name	 = $contact['ctt_first_name'] . ' ' . $contact['ctt_last_name'];

				if ($email)
				{
					//  Users::notifyCoinRecharge($userId,$contactID, $addToCoin, 1, 2);
					Users::notifyCoinRecharge($userId, $contactID, $addToCoin, 1, 3);
					//     Users::notifyCoinRecharge($userId,$contactID, $email,$code,$number,$name, $addToCoin, 1, 3);
				}
			}
		}
	}

	public function actionTestvnd()
	{
		$vndModel	 = new Vendors();
		$record		 = $vndModel->getassignList(73472, 5, 1, 120);

		print_r($record);
		foreach ($record as $row)
		{
			echo $row['bkg_id'];
		}
	}

	public function actionreferbyFriend()
	{
		$this->checkV3Theme();
		$this->pageTitle = "Refer a friend, get cash back - it's a win-win!";
		$model			 = new Users();
		$hash			 = Yii::app()->request->getParam('hash');
		if (yii::app()->request->getPost('Users'))
		{
			try
			{
				$userRequest = (yii::app()->request->getPost('Users'));
				$name		 = $userRequest['usr_name'];
				$email		 = $userRequest['usr_email'];
				$referredId	 = $userRequest['usr_referred_id'];
				for ($i = 0; $i < count($name); $i++)
				{
					$userModel	 = Users::model()->findByPk($referredId[$i]);
					UsersReferred::add($referredId[$i], $name[$i], $email[$i]);
					$qrCode		 = QrCode::getCode($referredId[$i]);
					$emailCom	 = new emailWrapper();
					$emailCom->userReferredBy($email[$i], $name[$i], $userModel->usr_name, $qrCode);
				}

				$message = "Thank You";
			}
			catch (Exception $ex)
			{
				Logger::exception($ex);
				$message = "Some error occured";
			}
			$model->usr_referred_id	 = $referredId[0];
			$hash					 = $hash != null ? $hash : Yii::app()->shortHash->Hash(referredId[0]);
		}
		else
		{
			$model->usr_referred_id	 = $referredId[0] != null ? $referredId[0] : Yii::app()->shortHash->unHash($hash);
			$message				 = "";
			$hash					 = $hash != null ? $hash : Yii::app()->shortHash->Hash($referredId[0]);
		}
		$this->render('referbyfriend', array('model' => $model, "message" => $message, 'hash' => $hash));
	}

}
