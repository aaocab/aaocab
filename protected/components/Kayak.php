<?php

class Kayak extends CComponent
{
	function initiate($request)
	{
		try
		{
			$url		 = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . Yii::app()->createUrl('site/unauthorized');
			$apiKey		 = $request->getParam('apikey');
			$agentModel	 = Agents::model()->findByApiKey($apiKey);
			//$_SERVER['HTTP_REFERER'] = "http://localhost:82/"; //remove this before commit
			//if($_SERVER['HTTP_REFERER']=='' || $agentModel == null)
			if ($agentModel == null)
			{
				header("Location: " . $url);
				Yii::app()->end();
			}
			$partnerSettings = PartnerSettings::getValueById($agentModel['agt_id']);
			$referrals		 = $partnerSettings['pts_referral_urls'];
			if ($referrals == null)
			{
				goto mapReq;
			}
			$arrReferrals = json_decode($referrals);
			if (!in_array($_SERVER['HTTP_REFERER'], $arrReferrals))
			{
				header("Location: " . $url);
				Yii::app()->end();
			}
			mapReq:
			$agtId	 = $agentModel['agt_id'];
			$model	 = self::map($request, $agtId);
			return $model;
		}
		catch (Exception $ex)
		{
			$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . Yii::app()->createUrl('site/badrequest');
			header("Location: " . $url);
			Yii::app()->end();
		}
	}

	function map($request, $agtId = '')
	{
		$model						 = new BookingTemp();
		$model->bkg_booking_type	 = $request->getParam('triptype');
		$model->bkg_vehicle_type_id	 = $request->getParam('skuid');
		$model->bkg_pickup_date		 = ($request->getParam('sdate') != "" && $request->getParam('stime') != "") ? $request->getParam('sdate') . " " . $request->getParam('stime') : "";
		$model->bkg_return_date		 = ($request->getParam('edate') != "" && $request->getParam('etime') != "") ? $request->getParam('edate') . " " . $request->getParam('etime') : "";
		$model->bkg_return_date_date = ($model->bkg_return_date != "") ? DateTimeFormat::DateTimeToDatePicker($model->bkg_return_date) : "";
		$model->bkg_return_date_time = ($model->bkg_return_date != "") ? DateTimeFormat::DateTimeToTimePicker($model->bkg_return_date) : "";
		$model->bkg_agent_id		 = $agtId; //36006;
		$model->bkg_partner_ref_id	 = $request->getParam('kayakclickid');
		$flat						 = $request->getParam('flat');
		$flong						 = $request->getParam('flong');
		$tlat						 = $request->getParam('tlat');
		$tlong						 = $request->getParam('tlong');
		self::mapRoutes($model, $flat, $flong, $tlat, $tlong);
		if(Config::get('kayak.convertTripType') == 1)
		{
		  $model = self::convertTriptype($model);
		}
		return $model;
	}

	function mapRoutes(&$model, $flat, $flong, $tlat = '', $tlong = '')
	{
		$fcityRoute1 = Cities::getCityByLatLng($flat, $flong);
		$tcityRoute1 = Cities::getCityByLatLng($tlat, $tlong);
		$tcityRoute1 = ($tcityRoute1 != '') ? $tcityRoute1 : $fcityRoute1;

		if ($fcityRoute1 == $tcityRoute1)
		{
			$model->bkg_booking_type = 2;
		}
		else
		{
			$model->bkg_booking_type = 3;
		}
		//1st route
		$model->bkg_from_city_id			 = $fcityRoute1;
		$model->bkg_to_city_id				 = $tcityRoute1;
		$params[0]['brt_from_city_id']		 = $fcityRoute1;
		$params[0]['brt_to_city_id']		 = $tcityRoute1;
		$params[0]['brt_pickup_datetime']	 = $model->bkg_pickup_date;
		$params[0]['brt_from_latitude']		 = $flat;
		$params[0]['brt_from_longitude']	 = $flong;
		$params[0]['brt_to_latitude']		 = ($tlat != '') ? $tlat : $flat;
		$params[0]['brt_to_longitude']		 = ($tlong != '') ? $tlong : $flong;

		//2nd route
		$fcityRoute2						 = $tcityRoute1;
		$tcityRoute2						 = $tcityRoute1;
		$flatRoute2							 = ($tlat != '') ? $tlat : $flat;
		$flongRoute2						 = ($tlong != '') ? $tlong : $flong;
		$tlatRoute2							 = ($tlat != '') ? $tlat : $flat;
		$tlongRoute2						 = ($tlong != '') ? $tlong : $flong;
		$params[1]['brt_from_city_id']		 = $fcityRoute2;
		$params[1]['brt_to_city_id']		 = $tcityRoute2;
		$duration							 = Route::model()->getRouteDurationbyCities($fcityRoute2, $tcityRoute2);
		$params[1]['brt_pickup_datetime']	 = date('Y-m-d H:i:s', strtotime($model->bkg_return_date . ' -' . $duration . ' minute'));
		$params[1]['brt_from_latitude']		 = $flatRoute2;
		$params[1]['brt_from_longitude']	 = $flongRoute2;
		$params[1]['brt_to_latitude']		 = $tlatRoute2;
		$params[1]['brt_to_longitude']		 = $tlongRoute2;
		$params[1]['brt_return_date_date']	 = DateTimeFormat::DateTimeToDatePicker($model->bkg_return_date);
		$params[1]['brt_return_date_time']	 = DateTimeFormat::DateTimeToTimePicker($model->bkg_return_date);

		$model->bookingRoutes = $model->setRoutes($params);
	}

	public static function getSuggestedTrips($leadId,$defleadId=0)
	{
		$model = BookingTemp::model()->findByPk($leadId);
		$pickupDateTime = new DateTime($model->bkg_pickup_date);
		$pickupDate = $pickupDateTime->format('Y-m-d');
		$returnDateTime = new DateTime($model->bkg_return_date);
		$returnDate = $returnDateTime->format('Y-m-d');

		$quoteArr = [];
		//default quote
		if($defleadId > 0)
		{
			$defModel = BookingTemp::model()->findByPk($defleadId);
			if($defModel!='' && $defModel->bkg_booking_type!=$model->bkg_booking_type)
			{
				$defModel->getRoutes();
				if(in_array($defModel->bkg_booking_type, [1, 10, 11]))
				{
					unset($defModel->bookingRoutes[1]);
				}
				$quotData = Quote::populateFromModel($defModel,$defModel->bkg_vehicle_type_id);
				$response	 = new \Stub\booking\QuoteResponse();
				$response->setData($quotData);
				$response->defLeadId = $defModel->bkg_id;
				$quoteArr[$defModel->bkg_booking_type] = $response;
			}
		}
		//trip types available
		$tripTypeArr = [];
		if($model->bkg_from_city_id == $model->bkg_to_city_id && $pickupDate == $returnDate){
			$tripTypeArr = [10,11];
			if(in_array($model->bkg_booking_type, [10,11]))
			{
				array_push($tripTypeArr, 2);
			}
		}
		if($model->bkg_from_city_id != $model->bkg_to_city_id && $pickupDate == $returnDate){
			$tripTypeArr = [1];
			if(in_array($model->bkg_booking_type, [1,3]))
			{
				array_push($tripTypeArr, 3);
			}
		}
		if($model->bkg_from_city_id == $model->bkg_to_city_id && $pickupDate != $returnDate){
			$tripTypeArr = [2];
		}
		if($model->bkg_from_city_id != $model->bkg_to_city_id && $pickupDate != $returnDate){
			$tripTypeArr = [3];
		}
	
		$key = array_search($model->bkg_booking_type, $tripTypeArr);
		if (false !== $key) 
		{
			 unset($tripTypeArr[$key]);
		}
		foreach ($tripTypeArr as $triptype)
		{
			$model->bkg_booking_type = $triptype;
			$model->getRoutes();
			if(in_array($triptype, [1, 10, 11]))
			{
				unset($model->bookingRoutes[1]);
			}
			$quotData = Quote::populateFromModel($model,$model->bkg_vehicle_type_id);
			$response	 = new \Stub\booking\QuoteResponse();
			$response->setData($quotData);
			$quoteArr[$triptype] = $response;
		}
		return $quoteArr;
	}

	public static function convertTriptype($model)
	{
		$pickupDateTime = new DateTime($model->bkg_pickup_date);
		$pickupDate = $pickupDateTime->format('Y-m-d');
		$returnDateTime = new DateTime($model->bkg_return_date);
		$returnDate = $returnDateTime->format('Y-m-d');
		//$interval= $returnDate->diff($pickupDate); 
		$interval= $pickupDateTime->diff($returnDateTime); 
		$hours = ($interval->days * 24) + $interval->h;

		if($model->bkg_from_city_id == $model->bkg_to_city_id && $pickupDate == $returnDate && $hours <= 8)
		{
			$model->bkg_booking_type = 10; //day rental 8-80
		}
		else if($model->bkg_from_city_id == $model->bkg_to_city_id && $pickupDate == $returnDate && $hours > 8 && $hours <= 12)
		{
			$model->bkg_booking_type = 11; //day rental 12-120
		}
		else if($model->bkg_from_city_id != $model->bkg_to_city_id && $pickupDate == $returnDate && $hours <= 16)
		{
			$model->bkg_booking_type = 1;
		}
		$routes = $model->bookingRoutes;
		$rCount = count($routes);
		$duration							 = \Route::model()->getRouteDurationbyCities($routes[$rCount - 1]->brt_from_city_id, $routes[$rCount - 1]->brt_to_city_id);
		$routes[$rCount - 1]->brt_pickup_datetime = date('Y-m-d H:i:s', strtotime($model->bkg_return_date . ' -' . $duration . ' minute'));

		if(in_array($model->bkg_booking_type, [1,10,11]) && $rCount > 1)
		{
			array_pop($routes);
		}
		$model->bookingRoutes = $routes;
		if(in_array($model->bkg_booking_type, [10,11]))
		{
			$model->isconvertedToDR = 1;
			$routes[0]->brt_return_datetime = $model->bkg_return_date;
			$routes[0]->parseReturnDateTime($model->bkg_return_date);
		}
		return $model;
	}

	public static function dayRentalMaxMinutes($tripType)
	{
		$drMaxMinutes = 0;
		if($tripType==10)
		{
			$drMaxMinutes = 8*60;
		}
		if($tripType==11)
		{
			$drMaxMinutes = 12*60;
		}
		return $drMaxMinutes;
	}
}
