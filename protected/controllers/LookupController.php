<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class LookupController extends BaseController
{

	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter + create, signin',
				'bypass' => false),
			'postOnly + delete', // we only allow deletion via POST request
			array(
				'RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'
			),
			array(
				'CHttpCacheFilter + citylist1',
				'cacheControl' => "max-age=26400, public",
			),
//            array(
//                'CHttpCacheFilter + routes',
//                'lastModified' => Route::model()->getLastModified('Y-m-d H:i:s'),
//            ),
		);
	}

	public function actions()
	{
		return array(
			'REST.' => 'RestfullYii.actions.ERestActionProvider',
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
			$ri	 = array('/citylist1', '/nearestcitylist', '/airportcities', '/nearestairportcities', '/citybounds', '/routeduration', '/getPlace', '/getPredictions', '/getLatlngByPlaceId','/serverTime');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.get.search.render', function () {

			$arrArea	 = array();
			$areaModels	 = ZipArea::model()->findAll(array("condition" => "zar_active=1", "order" => "zar_name"));
			foreach ($areaModels as $areaModel)
			{
				$arrArea[] = array('id' => 'id:' . $areaModel->zar_id, 'name' => $areaModel->zar_name);
			}
			$places = $arrPlace + $arrArea;
			return $this->renderJSON([
				'type'	 => 'raw',
				'data'	 => array(
					'success'	 => true,
					'message'	 => "Successful",
					'data'		 => array('services' => $services, 'places' => $places)),
			]);
		});

		$this->onRest('req.get.citylist1.render', function () {

			header('Cache-Control: max-age=14400, public', true);
			// Yii::app()->cache->flush();
			$query			 = Yii::app()->request->getParam('q');
			$city			 = Yii::app()->request->getParam('city');
			$datafromcity	 = Yii::app()->cache->get("lookupCitylist1_{$query}_{$city}");
			if ($datafromcity === false)
			{
				$datafromcity = Cities::model()->getJSONSourceCities($query, $city);
				Yii::app()->cache->set("lookupCitylist1_{$query}_{$city}", $datafromcity, 21600);
			}
			echo $datafromcity;
			Yii::app()->end();
		});

		$this->onRest('req.get.nearestcitylist.render', function () {
			header('Cache-Control: max-age=14400, public', true);
			$source			 = Yii::app()->request->getParam('source');
			$queryStr		 = Yii::app()->request->getParam('q');
			$limit			 = " LIMIT 100 ";
			//    $datafromcity = Yii::app()->cache->get("NearestCitylist_" . $source);
			//    if ($datafromcity === false) {
			$datafromcity	 = Cities::model()->getJSONNearestAll($source, 500, false, $queryStr, $limit);
			// Yii::app()->cache->set("NearestCitylist_" . $source, $datafromcity, 21600);
			//    }
			echo $datafromcity;
			Yii::app()->end();
		});

		$this->onRest('req.get.airportcities.render', function () {
//	    header('Cache-Control: max-age=28800, public', true);
//	    $query		 = Yii::app()->request->getParam('q');
//	    $arrCities	 = '[  {    "id": "000",    "text": "For airport transfer, please visit our website. "  }]'; //Cities::model()->getJSONAirportCitiesAll($query);
//	    echo $arrCities;
//	    Yii::app()->end();

			header('Cache-Control: max-age=28800, public', true);
			$query		 = Yii::app()->request->getParam('q');
			$arrCities	 = Cities::model()->getJSONAirportCitiesAll($query);
			echo $arrCities;
			Yii::app()->end();
		});

		$this->onRest('req.get.nearestairportcities.render', function () {
			header('Cache-Control: max-age=28800, public', true);
			$isAirport	 = true;
			$scity		 = (Yii::app()->request->getParam('source') == "") ? 0 : Yii::app()->request->getParam('source');
			$maxDistance = Yii::app()->params['airportCityRadius'];
			$query		 = Yii::app()->request->getParam('q');
			$arrCities	 = Cities::model()->getJSONNearestAll($scity, $maxDistance, $isAirport, $query);

			echo $arrCities;
			Yii::app()->end();
		});

		$this->onRest('req.get.citybounds.render', function () {
			header('Cache-Control: max-age=28800, public', true);
			$airportCityId	 = Yii::app()->request->getParam('airport_id', 0);
			$cityId			 = Yii::app()->request->getParam('city_id', 0);
			$airportCityId	 = ($airportCityId > 0) ? $airportCityId : $cityId;
			$jsonCities		 = Cities::model()->getCtyLatLongWithBound($airportCityId);
			echo $jsonCities;
			Yii::app()->end();
		});
		// created for citylist

		$this->onRest('req.get.cityList.render', function () {

			header('Cache-Control: max-age=14400, public', true);
			// Yii::app()->cache->flush();
			$query			 = Yii::app()->request->getParam('q');
			$city			 = Yii::app()->request->getParam('city');
			$datafromcity	 = Yii::app()->cache->get("lookupCitylist1_{$query}_{$city}");
			if ($datafromcity === false)
			{
				$datafromcity = Cities::model()->getJSONListCities($query, $city);
				Yii::app()->cache->set("lookupCitylist1_{$query}_{$city}", $datafromcity, 21600);
			}
			echo $datafromcity;
			Yii::app()->end();
		});
	
	
	$this->onRest('req.get.serverTime.render', function () {

			$timeNow = Filter::getDBDateTime();
			$timeArr =array("currentTime"=>$timeNow);
			echo json_encode($timeArr);
			Yii::app()->end();
		});
	}	
	public function actionVehicletypejson()
	{
		$vhtdata = VehicleTypes::model()->getVehicleTypeList1();
		$vhtjson = $this->getJSON($vhtdata);
		echo $vhtjson;
		Yii::app()->end();
	}

	public function actionRateCities()
	{
		$cities		 = Cities::model()->getRateCities();
		$arrCities	 = JSONUtil::convertModelToArray($cities, ['cities' => 'cty_id,cty_name']);
		echo CJSON::encode($arrCities);
		Yii::app()->end();
	}

	public function getJSON($arr = [])
	{
		//$carList = $this->getVehicleTypeList();
		$arrJSON = array();
		foreach ($arr as $key => $val)
		{
			$arrJSON[] = array("id" => $key, "text" => $val);
		}

		$data = CJSON::encode($arrJSON);
		return $data;
	}

	public function actionCities()
	{
		$datafromcity = Yii::app()->cache->get("lookupCities");
		if ($datafromcity === false)
		{
			$datafromcity = Cities::model()->getJSONServiceCity();
			Yii::app()->cache->set("lookupCities", $datafromcity, 21600);
		}
		echo '$cityList = ' . $datafromcity;
		Yii::app()->end();
	}

	public function actionCitylist()
	{
		echo $datafromcity = Cities::model()->getJSONRateSourceCities();
		Yii::app()->end();
	}

	public function actionCitylist1()
	{
		header('Cache-Control: max-age=14400, public', true);
		$query			 = Yii::app()->request->getParam('q');
		$city			 = Yii::app()->request->getParam('city');
		if($city == 0) 
		{
			$city = '';
		}		
		$datafromcity	 = Cities::model()->getJSONSourceCities($query, $city);

		echo $datafromcity;
		Yii::app()->end();
	}

	/**
	 * This function is used to display all the citylist dropdown in the Add/modify zone form
	 */
	public function actionCitylistzone()
	{
		header('Cache-Control: max-age=14400, public', true);
		$query			 = Yii::app()->request->getParam('q');
		$city			 = Yii::app()->request->getParam('city');
		$lat			 = Yii::app()->request->getParam('lat');
		$long			 = Yii::app()->request->getParam('long');
		$datafromcity	 = Cities::model()->getSourceCitiesforZone($query, $city, $lat, $long);
		echo $datafromcity;
		Yii::app()->end();
	}

	public function actionDayRentalCityList()
	{
		$query			 = Yii::app()->request->getParam('q');
		$city			 = Yii::app()->request->getParam('city');
		//$datafromcity = Cities::model()->getJSONSourceCities($query, $city);
		//$datafromcity = '[{"id":"30893","text":"Kolkata, West Bengal"},{"id":"30366","text":"Delhi, Delhi"},{"id":"30611","text":"Pune, Maharashtra"},{"id":"30758","text":"Chennai, Tamil Nadu"},{"id":"30474","text":"Bengaluru (Bangalore), Karnataka"},{"id":"30595","text":"Mumbai (Bombay), Maharashtra"},{"id":"30254","text":"Hyderabad, Telangana"},{"id":"31022","text":"Goa, Goa"}]';
		$datafromcity	 = Cities::model()->getJSONSourceCitiesDR($query, $city);
		echo $datafromcity;
		Yii::app()->end();
	}

	public function actionPackage()
	{
		header('Cache-Control: max-age=14400, public', true);
		$query			 = Yii::app()->request->getParam('q');
		$pckid			 = Yii::app()->request->getParam('pckid', '');
		$datafromcity	 = Yii::app()->cache->get("lookupPackage_{$query}_{$pckid}");
		if ($datafromcity === false)
		{
			$datafromcity = Package::model()->getJSONPackages($query, $pckid);
//			Yii::app()->cache->set("lookupCitylist1_{$query}_{$pckid}", $datafromcity, 21600);
		}
		echo $datafromcity;
		Yii::app()->end();
	}

	public function actionAllcitylistbyquery()
	{
		header('Cache-Control: max-age=28800, public', true);
		$query			 = Yii::app()->request->getParam('q');
		$city			 = Yii::app()->request->getParam('city');
		$airportShow	 = Yii::app()->request->getParam('apshow', 0);
		$datafromcity	 = Yii::app()->cache->get("alllookupCitylistbyQuery1_{$query}_{$city}_{$airportShow}_");
		//if ($datafromcity === false || $datafromcity == '[]')
		//{
		$datafromcity	 = Cities::model()->getJSONAllCitiesbyQuery($query, $city, $airportShow);
		Yii::app()->cache->set("alllookupCitylistbyQuery1_{$query}_{$city}_{$airportShow}_", $datafromcity, 21600);
		//}
		echo $datafromcity;
		Yii::app()->end();
	}

	public function actionAllcitylistbyState()
	{
		header('Cache-Control: max-age=28800, public', true);
		$query			 = Yii::app()->request->getParam('q');
		$city			 = Yii::app()->request->getParam('city');
		$state			 = Yii::app()->request->getParam('state', '');
		$datafromcity	 = Yii::app()->cache->get("alllookupCitylistbyState_{$query}_{$city}_{$state}_");
		//if ($datafromcity === false || $datafromcity == '[]')
		//{
		$datafromcity	 = Cities::model()->getJSONAllCitiesbyQuery($query, $city, $state);
		Yii::app()->cache->set("alllookupCitylistbyState_{$query}_{$city}_{$state}_", $datafromcity, 21600);
		//}
		echo $datafromcity;
		Yii::app()->end();
	}

	public function actionAllCabModelbyquery()
	{
		header('Cache-Control: max-age=28800, public', true);
		$query	 = Yii::app()->request->getParam('q');
		$id	 = Yii::app()->request->getParam('id');

		$data = VehicleTypes::getTypeListJson($query, $id);

		echo $data;
		Yii::app()->end();
	}

	public function actionAllStatelist()
	{
		//print_r("here");exit;
		header('Cache-Control: max-age=28800, public', true);
		$query			 = ''; //Yii::app()->request->getParam('q');
		$datafromcity	 = Yii::app()->cache->get("alllookupStatelistbyQuery1_{$query}_");
		if ($datafromcity === false)
		{
			$datafromcity = States::model()->getJSONAllStatebyQuery($query);
			Yii::app()->cache->set("alllookupStatelistbyQuery1_{$query}_", $datafromcity, 21600);
		}
		echo $datafromcity;
		Yii::app()->end();
	}

	public function actionNearestCitylist($source = 0, $q = "", $city = "")
	{
		header('Cache-Control: max-age=14400, public', true);
		$isRedirectedBooking = (Yii::app()->request->cookies['gozo_agent_id']->value == Config::get('Kayak.partner.id')?1:0);
		$citiesModel = new Cities();
		$citiesModel->isRedirectedBooking = $isRedirectedBooking;
		$datafromcity = $citiesModel->getJSONNearestAll($source, 2500, false, $q, " Limit 0,15", 0, $city);
		echo $datafromcity;
		Yii::app()->end();
	}

	public function actionRoutes()
	{
		$data1 = Route::model()->getRecommendedRoutes();
		echo '$routeList = ' . $data1;
		Yii::app()->end();
	}

	public function actionAllvendorbyquery()
	{
		$query		 = Yii::app()->request->getParam('q');
		$vnd		 = Yii::app()->request->getParam('vnd');
		$onlyActive	 = Yii::app()->request->getParam('onlyActive', 0);
		$datafromVnd = Yii::app()->cache->get("alllookupVendortbyQuery_{$query}_{$vnd}_{$onlyActive}");
		if ($datafromVnd === false)
		{
			$datafromVnd = Vendors::model()->getJSONAllVendorsbyQuery($query, $vnd, $onlyActive);
			Yii::app()->cache->set("alllookupVendortbyQuery_{$query}_{$vnd}_{$onlyActive}", $datafromVnd, 21600);
		}
		echo $datafromVnd;
		Yii::app()->end();
	}

	public function actionAlldriverbyquery()
	{
		$request	 = Yii::app()->request;
		$query		 = $request->getParam('q');
		$drv		 = $request->getParam('drv');
		$onlyActive	 = $request->getParam('onlyActive') != null ? $request->getParam('onlyActive') : 0;
		$vnd		 = $request->getParam('vnd') != null ? $request->getParam('vnd') : 0;
		echo $datafromDrv = Drivers::model()->getJSONAllDriversbyQuery($query, $drv, $onlyActive, $vnd);
		Yii::app()->end();
	}

	public function actionAllpartnerbyquery()
	{
		$query		 = Yii::app()->request->getParam('q');
		$agt		 = Yii::app()->request->getParam('agt');
		$onlyActive	 = Yii::app()->request->getParam('onlyActive', 1);
		$datafromAgt = Yii::app()->cache->get("allPartnerbyQuery_{$query}_{$agt}_{$onlyActive}");
		if ($datafromAgt === false)
		{
			$datafromAgt = Agents::model()->getJSONAllPartnersbyQuery($query, $agt, $onlyActive);
			Yii::app()->cache->set("allPartnerbyQuery_{$query}_{$agt}_{$onlyActive}", $datafromAgt, 21600, new CacheDependency('allPartnerbyQuery'));
		}
		echo $datafromAgt;
		Yii::app()->end();
	}

	public function actionTimedrop($startTime = '00:00')
	{
		header('Cache-Control: max-age=14400, public', true);
		$query			 = Yii::app()->request->getParam('q');
		$datafromcity	 = Yii::app()->cache->get("lookupTimeDrop_{$query}");
		if ($datafromcity === false)
		{
			$arrTime = Filter::getTimeDropArr($startTime);
			$data	 = CJSON::encode($arrTime);
			Yii::app()->cache->set("lookupTimeDrop_{$query}", $datafromcity, 21600);
		}
		echo $data;
//	exit;
		Yii::app()->end();
	}

	public function actionGetstatus()
	{
		$tcode	 = Yii::app()->request->getParam('tcode', 0);
//		$opt	 = Yii::app()->request->getParam('opt', 0);
//		$json	 = Yii::app()->request->getParam('json', 0);
		$pmodel	 = PaymentGateway::model()->getPaymentStatus($tcode);
		exit;
		echo "<br>";
		$tmode	 = $pmodel->apg_mode;
		if ($pmodel->apg_ptp_id == 6)
		{
			if ($opt == 1)
			{
				$url = "http://www.payumoney.com/payment/op/getPaymentResponse";
			}
			else
			{
				$url = Yii::app()->payu->status_query_url;
			}

			if ($tmode == 2)
			{
				$data['merchantTransactionIds'] = $tcode;
			}
			$data['merchantKey'] = Yii::app()->payu->merchant_key;

			$resArr = $this->callAPI($url, $data);
		}
		if ($pmodel->apg_ptp_id == 4)
		{
			$RETURN_URL	 = Yii::app()->createAbsoluteUrl("/ebs/stresponse");
			$CANCEL_URL	 = Yii::app()->createAbsoluteUrl("/ebs/stresponse", array());
			$ebsPayment	 = new EbsPayment($RETURN_URL, $CANCEL_URL);
			if ($tmode == 1)
			{
				$transcode1 = PaymentGateway::model()->getCodebyRefid($pmodel->apg_ref_id);
			}
			else
			{
				$transcode1 = $tcode;
			}
			$resArr = $ebsPayment->getTxnStatus(['RefNo' => $transcode1]);
		}

		echo "<pre>";
		if ($json == 1)
		{
			echo json_encode($resArr);
		}
		else
		{
			print_r($resArr);
		}
	}

	public function callAPI($url, $requestParamList)
	{
		$auth		 = Yii::app()->payu->merchant_authorization;
		$data_string = http_build_query($requestParamList);
		$ch			 = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_URL, $url . '?' . $data_string);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); /* return the output in string format */
		$headers	 = array("Authorization: $auth");
		$headers[]	 = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$output		 = curl_exec($ch);
		$data		 = json_decode($output, true);
		return $data;
	}

	public function curlCall($url, $data)
	{
		$auth		 = Yii::app()->payu->merchant_authorization;
		$data_string = http_build_query($data);
		$options	 = array(
			"http" => array(
				"header"		 => "Authorization:Auth $auth",
				"method"		 => "POST",
				"Authorization"	 => "$auth",
				"content"		 => http_build_query($data)
			),
		);
		$context	 = stream_context_create($options);
		$response	 = file_get_contents($url . '?' . $data_string, false, $context);

		$resArr = json_decode($response, true);
		if (!$resArr)
		{
			echo $response;
		}
		return $resArr;
	}

	public function actionMmtotp()
	{
		$sql = "SELECT bkg.*,bcb.bcb_driver_phone,
				bcb.bcb_driver_name,bcb.bcb_cab_number,bcb.bcb_driver_phone,
				btk.bkg_trip_otp,
				fcty.cty_name bkgFromCity,
				tcty.cty_name bkgToCity, blg.blg_desc, blg_created
				FROM booking bkg
				JOIN booking_log blg
				ON bkg.bkg_id = blg.blg_booking_id AND blg.blg_event_id = 97
				JOIN booking_cab bcb ON bcb.bcb_id = bkg.bkg_bcb_id
				JOIN cities fcty ON fcty.cty_id = bkg.bkg_from_city_id
				JOIN cities tcty ON tcty.cty_id = bkg.bkg_to_city_id
				JOIN booking_track btk ON  btk.btk_bkg_id = bkg.bkg_id
				WHERE bkg_status = 5 AND bkg_agent_id = 450 and blg.blg_desc <> 'Updated Successfully'
				ORDER BY blg_created DESC";

		$bmodels = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($bmodels as $bmodel)
		{
			//$oldModel = clone $bmodel;
			$notificationId	 = substr(round(microtime(true) * 1000), -5);
			$payLoadData1	 = ['bookingId'	 => $bmodel['bkg_booking_id'],
				'EventCode'	 => Booking::CODE_CABDRIVER_ASSIGNED];
			$success1		 = AppTokens::model()->notifyConsumer($bmodel['bkg_user_id'], $payLoadData1, $notificationId, "(" . $bmodel['bkg_booking_id'] . ") " . $bmodel['bkgFromCity'] . " to " . $bmodel['bkgToCity'] . " on " . $bmodel['bkg_pickup_date'], "Cab details updated " . $bmodel['bkg_booking_id']);
			if ($bmodel['bkg_agent_id'] == 450)
			{
				$bookingPref = BookingPref::model()->getByBooking($bmodel['bkg_id']);
				$mmtResponse = false;
				for ($count = 0; $count < 2 && !$mmtResponse; $count++)
				{
//									$stateZone = $bmodel->bkgFromCity->ctyState->stt_zone;
					$driver_phone = $bmodel['bcb_driver_phone'];
//                                    if (in_array($stateZone, [4, 7]) && $bmodel->bkg_country_code == 91)
					if ($bmodel['bkg_country_code'] == 91)
					{
						$driver_phone = Yii::app()->params['customerToDriver'];
					}

					$apiURL						 = 'http://www.aaocab.com/mmtproxy.php';
					$requestParamList			 = [
						"type"				 => "driverDetail",
						"booking_id"		 => "{$bmodel['bkg_agent_ref_code']}",
						"vendor_booking_id"	 => $bmodel['bkg_id'],
						"cab_number"		 => $bmodel['bcb_cab_number'],
						"driver_name"		 => $bmodel['bcb_driver_name'],
						"driver_mobile"		 => $driver_phone,
						"otp"				 => $bmodel['bkg_trip_otp']
					];
					$jsonData					 = json_encode($requestParamList);
					$ch							 = curl_init($apiURL);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json'
							)
					);
					$jsonResponse				 = curl_exec($ch);
					$responseParamList			 = json_decode($jsonResponse, true);
					$mmtResponse				 = $responseParamList["success"];
					$aatModel					 = new AgentApiTracking();
					$aatModel->aat_type			 = 9;
					$aatModel->aat_request		 = $jsonData;
					$aatModel->aat_response		 = $jsonResponse;
					$aatModel->aat_from_city	 = $bmodel['bkg_from_city_id'];
					$aatModel->aat_to_city		 = $bmodel['bkg_to_city_id'];
					$aatModel->aat_booking_id	 = $bmodel['bkg_id'];
					$aatModel->aat_pickup_date	 = $bmodel['bkg_pickup_date'];
					$aatModel->aat_booking_type	 = $bmodel['bkg_booking_type'];
					$aatModel->aat_agent_id		 = 450;
					$aatModel->aat_ip_address	 = \Filter::getUserIP();
					$aatModel->aat_created_at	 = new CDbExpression('NOW()');
					$aatModel->save();
				}
				if ($mmtResponse)
				{
					$description = 'Updated Successfully';
				}
				else
				{
					$description = 'Failed to Update';
				}
				BookingLog::model()->createLog($bmodel['bkg_id'], $description, UserInfo::model(), BookingLog::MMT_CAB_DRIVER_UPDATE, $oldModel, $params);
			}
		}
		echo "Bkgid : " . $bmodel['bkg_booking_id'] . " : " . $description;
		echo "<br>";
	}

	public function actionRoutelist()
	{
		header('Cache-Control: max-age=28800, public', true);
		$query		 = Yii::app()->request->getParam('q');
		$rut		 = Yii::app()->request->getParam('rut');
		$datarutlist = Yii::app()->cache->get("rutlist_{$query}_{$rut}");
		$datarutlist = false;
		if ($datarutlist === false)
		{
			$datarutlist = Route::model()->getJSONRoutebyQuery($query, $rut);
			Yii::app()->cache->set("rutlist_{$query}_{$rut}", $datarutlist, 21600);
		}
		echo $datarutlist;
		Yii::app()->end();
	}

	public function actionGetapidist()
	{
		$address1	 = Yii::app()->request->getParam('start');
		$address2	 = Yii::app()->request->getParam('end');
		$datarutlist = Yii::app()->cache->get("distance1_{$address1}_{$address2}");
		if ($datarutlist === false)
		{
			$fcity	 = Cities::model()->getDetails($address1);
			$tcity	 = Cities::model()->getDetails($address2);
			$lat1	 = $fcity->cty_lat;
			$lat2	 = $tcity->cty_lat;
			$lon1	 = $fcity->cty_long;
			$lon2	 = $tcity->cty_long;

			$dmxModel = DistanceMatrix::getByCoordinates(Stub\common\Place::init($lat1, $lon1), Stub\common\Place::init($lat2, $lon2));
			if ($dmxModel)
			{
				$result = ["dist" => $dmxModel->dmx_distance, "time" => $dmxModel->dmx_duration];
				Yii::app()->cache->set("distance_{$address1}_{$address2}", $result, 21600);
			}
			else
			{
				echo json_encode($dmxModel->getErrors());
				Yii::app()->end();
			}
//
//			$result = Route::model()->calculateAPIDistanceByAddress($address1, $address2);
		}
		echo json_encode($result);
		Yii::app()->end();
	}

	public function actionRouteDetails()
	{
		$fcity	 = Yii::app()->request->getParam('fcity');
		$tcity	 = Yii::app()->request->getParam('tcity');

		$duration	 = Route::model()->getRouteDurationbyCities($fcity, $tcity);
		$distance	 = Route::model()->getRouteDistancebyCities($fcity, $tcity);

		echo CJSON::encode(array('duration' => $duration, 'distance' => $distance));
		Yii::app()->end();
	}

	public function actionValidateRoutes()
	{
		$arrRouteResult	 = array();
		$arrRouteData	 = Yii::app()->request->getParam('multicitydata');
		$booking_type	 = Yii::app()->request->getParam('booking_type');

		if ($arrRouteData && is_array($arrRouteData) && count($arrRouteData) > 0)
		{
			$routesArr = [];
			foreach ($arrRouteData as $index => $data)
			{
				if ($data && is_array($data) && count($data) > 0)
				{
					if ($data['f_city_id'] > 0 && trim($data['pickup_date']) != '' && trim($data['pickup_time']) != '')
					{
						DateTimeFormat::concatDateTime($data['pickup_date'], $data['pickup_time'], $pickupTime);
						// Route
						$routeModel						 = new BookingRoute();
						$routeModel->brt_from_city_id	 = $data['f_city_id'];
						$routeModel->brt_to_city_id		 = $data['t_city_id'];
						$routeModel->brt_from_city_name	 = $data['f_city_name'];
						$routeModel->brt_to_city_name	 = $data['t_city_name'];
						$routeModel->brt_from_location	 = '';
						$routeModel->brt_to_location	 = '';
						$routeModel->brt_pickup_datetime = $pickupTime;
						$routesArr[]					 = $routeModel;
					}
				}
			}

			if (count($routesArr) > 0)
			{

				// Quote
				$quote				 = new Quote();
				$quote->routes		 = $routesArr;
				$quote->tripType	 = $booking_type;
				$quote->quoteDate	 = date("Y-m-d H:i:s");

				// RouteDistance
				$routeDistance = new RouteDistance();
				$routeDistance->calculateDistance($quote);

				if ($routesArr && is_array($routesArr) && count($routesArr) > 0)
				{

					foreach ($routesArr as $key => $value)
					{
						$estimated_pick_date = date('Y-m-d H:i:s', strtotime($value->brt_pickup_datetime . '+ ' . $value->brt_trip_duration . ' minute'));

						$start_pickup_datetime			 = new DateTime(date('Y-m-d', strtotime($routesArr[0]->brt_pickup_datetime)));
						$current_pick_datetime			 = new DateTime(date('Y-m-d', strtotime($value->brt_pickup_datetime)));
						$diffdays						 = $start_pickup_datetime->diff($current_pick_datetime)->d + 1;
						$totdiff						 = $start_pickup_datetime->diff(new DateTime(date('Y-m-d', strtotime($estimated_pick_date))))->d + 1;
						$arrData['next_pickup_date']	 = DateTimeFormat::DateTimeToDatePicker($estimated_pick_date);
						$arrData['next_pickup_time']	 = date('h:i A', strtotime($estimated_pick_date));
						$arrData['duration']			 = $value->brt_trip_duration;
						$arrData['distance']			 = $value->brt_trip_distance;
						$arrData['date']				 = $value->brt_pickup_datetime;
						$arrData['day']					 = $diffdays;
						$arrData['totday']				 = $totdiff;
						$arrData['estimated_date_next']	 = $estimated_pick_date;
						$arrData['validate_success']	 = 1;
						$arrData['pickup_cty_lat']		 = $value->brtFromCity->cty_lat;
						$arrData['pickup_cty_long']		 = $value->brtFromCity->cty_long;
						$arrData['drop_cty_lat']		 = $value->brtToCity->cty_lat;
						$arrData['drop_cty_long']		 = $value->brtToCity->cty_long;

						if (!$value->brtFromCity->cty_bounds)
						{
							$cty_lat	 = $value->brtFromCity->cty_lat;
							$cty_long	 = $value->brtFromCity->cty_long;

							$boundArr						 = [];
							$boundArr['northeast']['lat']	 = round(($cty_lat + 0.05), 6);
							$boundArr['northeast']['lng']	 = round(($cty_long + 0.05), 6);
							$boundArr['southwest']['lat']	 = round(($cty_lat - 0.05), 6);
							$boundArr['southwest']['lng']	 = round(($cty_long - 0.05), 6);
							$value->brtFromCity->cty_bounds	 = json_encode($boundArr);
						}
						if (!$value->brtToCity->cty_bounds)
						{
							$cty_lat	 = $value->brtToCity->cty_lat;
							$cty_long	 = $value->brtToCity->cty_long;

							$boundArr						 = [];
							$boundArr['northeast']['lat']	 = round(($cty_lat + 0.05), 6);
							$boundArr['northeast']['lng']	 = round(($cty_long + 0.05), 6);
							$boundArr['southwest']['lat']	 = round(($cty_lat - 0.05), 6);
							$boundArr['southwest']['lng']	 = round(($cty_long - 0.05), 6);
							$value->brtToCity->cty_bounds	 = json_encode($boundArr);
						}

						$arrData['pickup_cty_bounds']		 = json_decode($value->brtFromCity->cty_bounds);
						$arrData['drop_cty_bounds']			 = json_decode($value->brtToCity->cty_bounds);
						$arrData['pickup_cty_radius']		 = $value->brtFromCity->cty_radius | 0;
						$arrData['drop_cty_radius']			 = $value->brtToCity->cty_radius | 0;
						$arrData['pickup_cty_is_airport']	 = $value->brtFromCity->cty_is_airport | 0;
						$arrData['drop_cty_is_airport']		 = $value->brtToCity->cty_is_airport | 0;
						$arrData['pickup_cty_is_poi']		 = $value->brtFromCity->cty_is_poi | 0;
						$arrData['drop_cty_is_poi']			 = $value->brtToCity->cty_is_poi | 0;
						$arrData['pickup_cty_loc']			 = $value->brtFromCity->cty_garage_address;
						$arrData['drop_cty_loc']			 = $value->brtToCity->cty_garage_address;
						if($value->brtFromCity->cty_is_airport==1)
						{
							$arrData['pickup_cty_place_id']		 = $value->brtFromCity->cty_place_id;
						}
						if($value->brtToCity->cty_is_airport==1)
						{
							$arrData['drop_cty_place_id']		 = $value->brtToCity->cty_place_id;
						}

						$prevKey = (count($arrRouteResult) - 1);
						if (isset($arrRouteResult[$prevKey]) && $arrRouteResult[$prevKey]['estimated_date_next'] != "")
						{
							$d1	 = new DateTime($arrData['date']);
							$d2	 = new DateTime($arrRouteResult[$prevKey]['estimated_date_next']);
							if ($d1 < $d2)
							{
								$arrData['validate_success'] = 0;
							}
						}


						$arrData['pickup_cty']		 = $value->brtFromCity->cty_id;
						$arrData['drop_cty']		 = $value->brtToCity->cty_id;
						$arrData['pickup_city_name'] = $value->brt_from_city_name;
						$arrData['drop_city_name']	 = $value->brt_to_city_name;
						$arrData['pickup_date']		 = date('d/m/Y', strtotime($value->brt_pickup_datetime));
						$arrData['pickup_time']		 = date('h:i A', strtotime($value->brt_pickup_datetime));

						$arrRouteResult[] = $arrData;
					}
				}
			}
		}

		//echo "\r\n ===== arrRouteResult ===== \r\n";
		//print_r($arrRouteResult);

		echo json_encode($arrRouteResult);
		exit();
	}

	public function actionCitylistpackage1()
	{
		header('Cache-Control: max-age=14400, public', true);
		$query			 = Yii::app()->request->getParam('q');
		$city			 = Yii::app()->request->getParam('city');
		$datafromcity	 = Yii::app()->cache->get("citylistpackage_{$query}_{$city}");
		if ($datafromcity === false)
		{
			$datafromcity = Cities::model()->getJSONCitiesPackage($query, $city);
			Yii::app()->cache->set("citylistpackage_{$query}_{$city}", $datafromcity, 21600);
		}
		echo $datafromcity;
		Yii::app()->end();
	}

	public function actionGetcitybystate()
	{
		$state = $_REQUEST['state'];
		if ($state != "")
		{
			$cityModel	 = Cities::model()->findAll('cty_state_id=:state', array('state' => $state));
			$cities		 = array();
			foreach ($cityModel as $key => $value)
			{
				$cities[$value ['cty_id']] = $value['cty_name'];
			}
			echo json_encode($cities);
			Yii::app()->end();
		}
	}

	public function actionAllcabsbyquery()
	{
		$request	 = Yii::app()->request;
		$query		 = $request->getParam('q');
		$cabs		 = $request->getParam('cabs');
		$onlyActive	 = $request->getParam('onlyActive') != null ? $request->getParam('onlyActive') : 1;
		$vnd		 = $request->getParam('vnd') != null ? $request->getParam('vnd') : 0;
		echo $datafromCab = Vehicles::getJSONAllCabsbyQuery($query, $cabs, $onlyActive, $vnd);
		Yii::app()->end();
	}

	public function actionGetallcarmodelbyclass()
	{
		$srvClass	 = Yii::app()->request->getParam('val');
		$getSvcList	 = SvcClassVhcCat::model()->getVctSvcList($returnType	 = "listCategory", $srvClass);
		if (Yii::app()->request->isAjaxRequest)
		{
			echo CJSON::encode($getSvcList);
			Yii::app()->end();
		}
	}

	/**
	 * This function is used checking whether booking id has a valid booking id or not
	 */
	public function actionValidateBooking()
	{
		$refid	 = Yii::app()->request->getParam('refid');
		$reftype = Yii::app()->request->getParam('reftype');
		$userId	 = UserInfo::getUserId();
		if ($userId == 0 && $refid != '' && $reftype == 2)
		{
			$bkgModel	 = Booking::model()->find('bkg_booking_id=:bkg_booking_id', ['bkg_booking_id' => $refid]);
			$userId		 = $bkgModel->bkgUserInfo->bkg_user_id;
		}
		$success = true;
		$flag	 = 0;
		if (in_array($reftype, [2]) || ($reftype == 4 && trim($refid) != '' ))
		{
			if (trim($refid) == '')
			{
				$success = false;
				$flag	 = 1;
				goto skipVal;
			}
			switch ((int) $reftype)
			{
				case 2:
					$bookingCode = BookingSub::getCodebyUserIdnId($userId, $refid);
					break;
				case 4:
					$contactId	 = ContactProfile::getByEntityId($userId);
					$entityType	 = UserInfo::TYPE_VENDOR;
					$vnd		 = ContactProfile::getEntityById($contactId, $entityType);
					$vndid		 = $vnd['id'];
					$bookingCode = BookingSub::getCodebyVndIdnId($vndid, $refid);
					break;
				default:
					break;
			}

			if (!$bookingCode)
			{
				$success = false;
				$flag	 = 2;
			}
			skipVal:
		}
		$data = ['success' => $success, "flag" => $flag];
		echo json_encode($data);
	}

	/**
	 * This function is used checking whether phone number valid  or not
	 */
	public function actionValidatePhone()
	{
		$phone	 = Yii::app()->request->getParam('phone');
		$phone	 = trim(str_replace(' ', '', $phone));
		$success = true;
		try
		{
			if (!Filter::validatePhoneNumber($phone))
			{
				throw new Exception('Invalid phone number');
			}
		}
		catch (Exception $exc)
		{
			$success = false;
		}
		skipVal:
		$data = ['success' => $success];
		echo json_encode($data);
	}

	public function actionVendorDriverByLatLong()
	{
		$city	 = Yii::app()->request->getParam('cityId', '');
		$data	 = array();
		try
		{
			if ($city)
			{
				$key	 = "city:{$city}";
				$result	 = Yii::app()->cache->get($key);
				if ($result !== false)
				{
					goto result;
				}
				$cityDetails = Cities::model()->findbypk($city);
				$result		 = Location::getVendorDriverByLatLong($city, $cityDetails->cty_lat, $cityDetails->cty_long);
				if ($result == null)
				{
					throw new Exception("No record found", ReturnSet::ERROR_INVALID_DATA);
				}
				result:
				$data = ['status' => true, 'messsage' => "Vendor/Driver found", 'vendorCount' => $result['cntVendor'], 'vendorIds' => $result['cntVendor'] == 0 ? 0 : $result['vendorIds'], 'driverCount' => $result['cntDriver'], 'driverIds' => $result['cntDriver'] == 0 ? 0 : $result['driverIds']];
			}
			else
			{
				$data = ['status' => false, 'messsage' => "NO DATA FOUND."];
			}
		}
		catch (Exception $ex)
		{
			$data = ['status' => false, 'messsage' => $ex->getMessage()];
		}
		echo json_encode($data);
		Yii::app()->end();
	}

	public function actionSelectAddress()
	{
		$this->checkV3Theme();
		$userId				 = UserInfo::getUserId();
		$isAirport			 = Yii::app()->request->getParam('airport', 0);
		$city				 = Yii::app()->request->getParam('city');
		$callback			 = Yii::app()->request->getParam('callback', "callback");
		$widgetTextValJson	 = Yii::app()->request->getParam('widgetTextValJson', "");
		$widgetTextVal		 = Yii::app()->request->getParam('widgetTextVal', "");
		$this->renderAuto('existingAddress', ["city" => $city, "callback" => $callback, "isAirport" => $isAirport, "widgetTextValJson" => $widgetTextValJson,"widgetTextVal" => $widgetTextVal], false, true);
	}

	public function actionSelectAddressV2()
	{
		$this->checkForDesktopTheme();
		$userId				 = UserInfo::getUserId();
		$isAirport			 = Yii::app()->request->getParam('airport', 0);
		$city				 = Yii::app()->request->getParam('city');
		$callback			 = Yii::app()->request->getParam('callback', "callback");
		$widgetTextValJson	 = Yii::app()->request->getParam('widgetTextValJson', "");
		$widgetTextVal		 = Yii::app()->request->getParam('widgetTextVal', "");
		$this->renderAuto('existingAddress', ["city" => $city, "callback" => $callback, "isAirport" => $isAirport, "widgetTextValJson" => $widgetTextValJson,"widgetTextVal" => $widgetTextVal], false, true);
	}

	
	public function actionGetPlace()
	{
		$returnSet	 = new ReturnSet();
		$place		 = Yii::app()->request->getParam('place');
		$objPlace	 = json_decode(json_encode($place), false);
		//$ltgModel	 = LatLong::model()->addByPlaceId($objPlace);
		//$objPlace = \Stub\common\Place::getLatLongModel($ltgModel);
		$gplModel	 = GeoPlace::getByPlace($objPlace);
		$ltgModel	 = $gplModel->gplLatLong;
		$placeObject = \Stub\common\Place::getLatLongModel($ltgModel);
		if ($placeObject)
		{
			$returnSet->setData($placeObject);
			$returnSet->setStatus(true);
		}
		echo json_encode($returnSet);
		Yii::app()->end();
	}

	public function actionAllZonebyQuery()
	{
		$request	 = Yii::app()->request;
		$query		 = $request->getParam('q');
		$zonId		 = $request->getParam('zonId');
		echo $datafromDrv = $this->getJSONAllZonesbyQuery($query, $zonId);
		Yii::app()->end();
	}

	public function getJSONAllZonesbyQuery($query, $zonId = '')
	{
		$rows	 = Zones::getZones($query, $zonId);
		$arrZone = array();
		foreach ($rows as $row)
		{
			$arrZone[] = array("id" => $row['zon_id'], "text" => $row['zon_name']);
		}
		$data = CJSON::encode($arrZone);
		return $data;
	}

	public function actionCitylistbyState1($state = 0, $q = "", $city = "")
	{
		header('Cache-Control: max-age=14400, public', true);
		$datafromcity = Cities::model()->getJSONNearestAll($state, 2500, false, $q, " Limit 0,15", 0, $city);
		echo $datafromcity;
		Yii::app()->end();
	}

	public function actionCitylistbyState()
	{
		header('Cache-Control: max-age=14400, public', true);
		$request		 = Yii::app()->request;
		$state			 = $request->getParam('state');
		$seachTxt		 = $request->getParam('seachTxt', '');
		$cityId			 = $request->getParam('cityId', '');
		$cityJsonList	 = Yii::app()->cache->get("citylistbyState_{$state}_{$cityId}_{$seachTxt}");
		if ($cityJsonList === false && $state != "")
		{
			$cityJsonList = Cities::getJSONListbyState($state, $cityId, $seachTxt);
			Yii::app()->cache->set("citylistbyState_{$state}_{$seachTxt}", $cityJsonList, 21600);
		}
		echo $cityJsonList;
		Yii::app()->end();
	}

	public function actionGetcitybystateid()
	{
		header('Cache-Control: max-age=14400, public', true);
		$request	 = Yii::app()->request;
		$state		 = $request->getParam('state');
		$seachTxt	 = $request->getParam('seachTxt', '');
		$cityId		 = $request->getParam('cityId', '');
		$cityList	 = Yii::app()->cache->get("citybystateid_{$state}_{$cityId}_{$seachTxt}");
		if ($cityList === false && $state != "")
		{
			$cityList		 = Cities::getCityListByStateid($state, $cityId, $seachTxt);
			$cityJsonList	 = json_encode($cityList);
			Yii::app()->cache->set("citybystateid_{$state}_{$seachTxt}", $cityJsonList, 21600);
		}
		echo $cityJsonList;
		Yii::app()->end();
	}

	public function actionallReportByQuery()
	{
		$query	 = Yii::app()->request->getParam('term');
		$data	 = Report::getJSON($query);
		echo json_encode(['success' => true, 'result' => $data]);
		Yii::app()->end();
	}

	public function actionGetPredictions()
	{
        
        
        if(APPLICATION_ENV != 'development2') 
        {
            $referer  = $_SERVER['HTTP_REFERER'];
            $host     = $_SERVER['HTTP_HOST'];
            $preferer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
            $info     = "==1==" . $host . "==2==" . $preferer;
            Logger::info($info);
            if ($preferer != $host)
            {
                throw new CHttpException(403, "Valid host not found", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
            }
        }
        $data           = [];
        $predictionHint = Yii::app()->request->getParam('pval');
        $city           = Yii::app()->request->getParam('city');
        $sessiontoken   = Yii::app()->request->getParam('sessiontoken');

        if (!$city)
        {
            throw new CHttpException(406, "City not found", 406);
        }
        if (!$sessiontoken)
        {
            throw new CHttpException(406, "No session found", 406);
        }
        try
        {
            $prediction      = substr(trim($predictionHint), 0, 250);
            $predictionsJson = Autocomplete::lookupKeyword($prediction, $city, $sessiontoken, 0.15);
            $predictions     = json_decode($predictionsJson);

            $arrPredictions = array();
            if ($predictions != null || $predictions != [])
            {
                foreach ($predictions->predictions as $key => $row)
                {
                    $arrPredictions[] = array("id" => $row->place_id, "text" => $row->description);
                }
            }
            echo CJSON::encode($arrPredictions);
            Yii::app()->end();
        }
        catch (Exception $e)
        {
            Logger::error($e);
        }
    }

	public function actionGetLatlngByPlaceId()
	{
		$success	 = false;
		$coordinates = [];
		$jsonAddress = "";
		$placeID	 = Yii::app()->request->getParam('placeID');
		$sessionId	 = Yii::app()->request->getParam('sessionId');
		$rawText	 = Yii::app()->request->getParam('rawText');

		if (!$placeID)
		{
			return false;
		}
		if (!$sessionId)
		{
			throw new CHttpException(406, "Session Out", 406);
		}
		/* @var $place \Stub\common\Place */
		$place			 = new \Stub\common\Place();
		$place->place_id = $placeID;
		$model			 = LatLong::getPlace($place);
		if (!$model)
		{
			$model = LatLong::addByPlaceId_v1($place,null,$sessionId);
		}
		$place->coordinates->latitude	 = $model->ltg_lat;
		$place->coordinates->longitude	 = $model->ltg_long;
		$place->address					 = $model->ltg_locality_address;
		$place->name					 = $model->ltg_name;
		$place->alias					 = $model->ltg_alias;
		$place->bounds					 = $model->ltg_bounds;
		$address						 = $place::initCustomGoogePlace($place,$rawText);
		$success						 = true;
		$coordinates					 = ['lat' => $model->ltg_lat, 'lng' => $model->ltg_long];
		//	$html							 = $this->renderPartial('rapMap', array('lat' => $model->ltg_lat, 'lng' => $model->ltg_long), true);
		echo json_encode(['success' => $success, 'coordinates' => $coordinates, 'address' => json_encode($address)]);
		Yii::app()->end();
	}

}
