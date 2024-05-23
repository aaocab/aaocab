<?php

class GoogleMapAPI
{

	private static $_instance = null;
	private $apiKey;

	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	/** @return GoogleMapAPI */
	public static function getInstance($key = null)
	{
		if (self::$_instance == null)
		{
			$api = Config::getGoogleApiKey('apikey');
			if ($key != null)
			{
				$api = $key;
			}
			self::$_instance = new GoogleMapAPI($api);
		}
		return self::$_instance;
	}

	public function getLatLong($address)
	{
		$api	 = $this->apiKey;
		$url	 = "https://maps.google.com/maps/api/geocode/json?address=" . urlencode($address) . "&sensor=false&mode=driving&key=$api";
		$result	 = $this->callAPI($url, 1);
		if ($result['success'])
		{
			$outputFrom			 = $result['data'];
			$latitude			 = $outputFrom->results[0]->geometry->location->lat;
			$longitude			 = $outputFrom->results[0]->geometry->location->lng;
			$address			 = $outputFrom->results[0]->formatted_address;
			$placeId			 = $outputFrom->results[0]->place_id;
			$result['placeid']	 = $placeId;
			$result['latitude']	 = $latitude;
			$result['longitude'] = $longitude;
			$result['address']	 = $address;
			return $result;
		}
		else
		{
			return $result;
		}
	}

	public  function getAutocompleteByBounds($key, $bounds, $sessiontoken, $precision = 0)
	{
		if (!$key)
		{
			throw new CHttpException(406, "keyword not found", 406);
		}
		$isSessiontoken = Yii::app()->params['predictionWidSessiontoken'];

		$southwest	 = ($bounds['southwest']['lat'] - $precision) . ',' . ($bounds['southwest']['lng'] - $precision);
		$northeast	 = ($bounds['northeast']['lat'] + $precision) . ',' . ($bounds['northeast']['lng'] + $precision);
		
		
		$rectangle	 = $southwest . '|' . $northeast;
		$country	 = "in";

		$params							 = [];
		$params["input"]				 = $key;
		$params["locationrestriction"]	 = "rectangle:{$rectangle}";
	//	$params["types"]				 = "geocode|establishment";
		$params["components"]			 = "country:$country";
		$params["types"]				 = "geocode|establishment";
        if($isSessiontoken)
        {
		$params["sessiontoken"]			 = $sessiontoken;
        }
		$params['strictbounds']			 = true;
		$params ["key"]					 = $this->apiKey;

		$paramString = http_build_query($params);

		$url		 = "https://maps.googleapis.com/maps/api/place/autocomplete/json?{$paramString}";
		$ch			 = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$response	 = curl_exec($ch);
		$errorNo	 = curl_errno($ch);
		$error		 = curl_error($ch);
		// $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $response;
	}
	
	public  function getAutocompleteByRadius($key, $coordinates, $radius, $sessiontoken)
	{
		if (!$key)
		{
			throw new CHttpException(406, "keyword not found", 406);
		}

        $isSessiontoken = Yii::app()->params['predictionWidSessiontoken'];
        
        
		$country	 = "in";
		$params							 = [];
		$params["input"]				 = $key;
		$params["locationrestriction"]	 = "circle:{$radius}@$coordinates";
		$params["types"]				 = "geocode|establishment";
		$params["components"]			 = "country:$country";
		//$params["types"]				 = "geocode|establishment";
        if($isSessiontoken){
		$params["sessiontoken"]			 = $sessiontoken;
        }
		$params['strictbounds']			 = true;
		$params ["key"]					 = $this->apiKey;

		$paramString = http_build_query($params);

		$url		 = "https://maps.googleapis.com/maps/api/place/autocomplete/json?{$paramString}";
		$ch			 = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$response	 = curl_exec($ch);
		$errorNo	 = curl_errno($ch);
		$error		 = curl_error($ch);
		// $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $response;
	}

	public function getDistance($routes, $isIP = false)
	{
		if ($isIP)
		{
			$sroutes = array_map("trim", $routes);
		}
		else
		{
			$sroutes = array_map("urlencode", $routes);
		}
		$droutes	 = $sroutes;
		array_shift($droutes);
		array_pop($sroutes);
		$addressFrom = implode('|', $sroutes);
		$addressTo	 = implode('|', $droutes);
		$api		 = $this->apiKey;
		$url		 = 'https://maps.google.com/maps/api/distancematrix/json?units=metric';
		$data		 = "&origins=" . urlencode($addressFrom) . "&destinations=" . urlencode($addressTo) . "&sensor=false&mode=driving&key=$api";
		$map		 = $url . $data;
		$result		 = $this->callAPI($map, 2);

		if ($result['success'])
		{
			$outputFrom	 = $result['data'];
			$cnt		 = count($outputFrom->rows);
			$p			 = [];
			for ($i = 0; $i < $cnt; $i++)
			{
				$status = $outputFrom->rows[$i]->elements[$i]->status;
				if ($status != "OK")
				{
					$result['success']		 = false;
					$result['errorCode']	 = 3;
					$result['errorMessage']	 = $status;
					$p[]					 = ['time' => 0, 'dist' => 0];
				}
				else
				{
					$time	 = $outputFrom->rows[$i]->elements[$i]->duration->value;
					$dist	 = $outputFrom->rows[$i]->elements[$i]->distance->value;
					$totDist = round($dist / 1000);
					$totmins = round($time / 60);
					$p[]	 = ['time' => $totmins, 'dist' => $totDist];
				}
			}
			$result['distance'] = $p;
		}
		return $result;
	}

	public static function getAddressComponents($obj, $type = "country")
	{
		$compObj	 = false;
		$components	 = $obj->address_components;
		foreach ($components as $component)
		{
			if (in_array($type, $component->types))
			{
				$compObj = $component;
				break;
			}
		}
		return $compObj;
	}

	function getDrivingDistancebyLatLong($lat1, $long1, $lat2, $long2)
	{
		$key	 = 'getDrivingDistancebyLatLong_' . md5(trim($lat1)) . '_' . md5(trim($long1)) . '_' . md5(trim($lat2)) . '_' . md5(trim($long2));
		$result	 = $GLOBALS[$key];
		if ($result && $result['status'] != 'REQUEST_DENIED')
		{
			goto result;
		}
		$api			 = $this->apiKey;
		$url			 = 'https://maps.google.com/maps/api/distancematrix/json?units=metric';
		$data			 = "&origins=" . $lat1 . "," . $long1 . "&destinations=" . $lat2 . "," . $long2 . "&mode=driving&key=$api";
		$map			 = $url . $data;
		$result			 = $this->callAPI($map, 2);
		$GLOBALS[$key]	 = $result;
		Logger::trace("Distance Matrix Result: " . json_encode($result));
		result:
		if ($result['success'])
		{
			$outputFrom	 = $result['data'];
			$cnt		 = count($outputFrom->rows);
			$p			 = [];
			for ($i = 0; $i < $cnt; $i++)
			{
				$status = $outputFrom->rows[$i]->elements[$i]->status;
				if ($status != "OK")
				{
					$result['success']		 = false;
					$result['errorCode']	 = 3;
					$result['errorMessage']	 = $status;
					$p[]					 = ['time' => 0, 'dist' => 0];
				}
				else
				{
					$time	 = $outputFrom->rows[$i]->elements[$i]->duration->value;
					$dist	 = $outputFrom->rows[$i]->elements[$i]->distance->value;
					$totDist = ceil($dist / 1000);
					$totmins = ceil($time / 60);
					$p[]	 = ['time' => $totmins, 'dist' => $totDist];
				}
			}
			$result['distance'] = $p;
			if (trim($status) == "ZERO_RESULTS" && $GLOBALS['ctr'] < 1)
			{
				$GLOBALS['ctr']	 = $GLOBALS['ctr'] + 1;
				//echo "ZERO_RESULTS::START--";
				// var_dump($result);
				// echo "ZERO_RESULTS::END--";
				//sleep(10);
				$result			 = $this->getDrivingDistancebyLatLong($lat1, $long1, $lat2, $long2);
			}
			if ($result['errorCode'] == 3)
			{
				throw new Exception("Route not supported", ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}
		}
		return $result;
	}

	public function getPlace($address)
	{
		$api	 = $this->apiKey;
		"https://maps.googleapis.com/maps/api/place/autocomplete/xml?input=Amoeba&location=27.176670,78.008072&radius=50000&strictbounds&key=$api";
		$url	 = "https://maps.google.com/maps/api/geocode/json?address=" . urlencode($address) . "&sensor=false&mode=driving&key=$api";
		$result	 = $this->callAPI($url, 1);
		if ($result['success'])
		{
			$outputFrom			 = $result['data'];
			$latitude			 = $outputFrom->results[0]->geometry->location->lat;
			$longitude			 = $outputFrom->results[0]->geometry->location->lng;
			$address			 = $outputFrom->results[0]->formatted_address;
			$result['latitude']	 = $latitude;
			$result['longitude'] = $longitude;
			$result['address']	 = $address;
			return $result;
		}
		else
		{
			return $result;
		}
	}

	public static function getObjectByAddress($address)
	{
		$key	 = 'getObjectByAddress' . md5(trim($address));
		$result	 = $GLOBALS[$key];
		if ($result && $result['status'] != 'REQUEST_DENIED')
		{
			goto result;
		}
		$address		 = urlencode($address);
		$api			 = Config::getGoogleApiKey('apikey');
		$url			 = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=$api";
		Logger::trace($url);
		$result			 = GoogleMapAPI::getInstance()->callAPI($url, 1);
		$GLOBALS[$key]	 = $result;

		result:
		return $result['data'];
	}

	public static function getObjectByLatLong($lat, $long)
	{
		if ($lat == 0)
		{
			return ["success" => false];
		}
		$key	 = 'getObjectByLatLong_' . $lat . '_' . $long;
		$result	 = $GLOBALS[$key];
		if ($result && $result['status'] != 'REQUEST_DENIED')
		{
			goto result;
		}
		$api			 = Config::getGoogleApiKey('apikey');
		$url			 = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&key=$api";
		Logger::trace($url);
		$result			 = GoogleMapAPI::getInstance()->callAPI($url, 1);
		$GLOBALS[$key]	 = $result;

		result:
		return $result['data'];
	}

	public static function getAccurateObjectByLatLong($lat, $long)
	{
		$objects = self::getObjectByLatLong($lat, $long);
		$data	 = $objects->results[0];
		$diff	 = false;
		foreach ($objects->results as $obj)
		{
			$geometry	 = $obj->geometry;
			$latitude	 = $geometry->location->lat;
			$longitude	 = $geometry->location->lng;
			$diff1		 = Filter::calculateDistance($lat, $long, $latitude, $longitude);
			if (!$diff || $diff1 < $diff)
			{
				$data	 = $obj;
				$diff	 = $diff1;
			}
		}
		return $data;
	}

	public static function getObjectByPlaceId($placeId)
	{
		$key	 = 'getObjectByPlaceId_' . $placeId;
		$result	 = $GLOBALS[$key];
		if ($result && $result['status'] != 'REQUEST_DENIED')
		{
			goto result;
		}
		$api			 = Config::getGoogleApiKey('apikey');
		$url			 = "https://maps.googleapis.com/maps/api/geocode/json?place_id=$placeId&key=$api";
		Logger::trace($url);
		$result			 = GoogleMapAPI::getInstance()->callAPI($url, 1);
		$GLOBALS[$key]	 = $result;

		result:
		return $result['data'];
	}

	public static function parseByType($placeObj, $type = 'locality')
	{
		$objArr	 = self::parseByTypes($placeObj, $type);
		$obj	 = false;
		if (is_array($objArr) && count($objArr) > 0)
		{
			$obj = $objArr[0];
		}
		return $obj;
	}

	public static function parseByTypes($placeObj, $type = 'locality')
	{
		if ($placeObj->status != 'OK')
		{
			return false;
		}
		$objArr	 = [];
		$results = $placeObj->results;
		foreach ($results as $obj)
		{
			$types = $obj->types;
			if (in_array($type, $types))
			{
				$objArr[] = $obj;
			}
		}
		return $objArr;
	}

	public static function getACByType($placeObj, $type = 'locality')
	{
		if ($placeObj->status != 'OK')
		{
			return false;
		}
		$objType = false;
		$results = $placeObj->results[0]->address_components;
		foreach ($results as $key => $obj)
		{
			$types = $obj->types;
			if (in_array($type, $types))
			{
				$objType = $obj;
			}
		}
		return $objType;
	}

	public static function getLocalities($placeObj)
	{
		return self::parseByTypes($placeObj, 'locality');
	}

	public static function getLocality($placeObj)
	{
		return self::parseByType($placeObj, 'locality');
	}

	public static function getSubLocality1($placeObj)
	{
		return self::parseByType($placeObj, 'sublocality_level_1');
	}

	public static function getAdminLevel3($placeObj)
	{
		return self::parseByType($placeObj, 'administrative_area_level_3');
	}

	public static function getAdminLevel2($placeObj)
	{
		return self::parseByType($placeObj, 'administrative_area_level_2');
	}

	public static function getAdminLevel1($placeObj)
	{
		return self::parseByType($placeObj, 'administrative_area_level_1');
	}

	public static function getPOI($placeObj)
	{
		return self::parseByType($placeObj, 'point_of_interest');
	}

	public static function getAirport($placeObj)
	{
		return self::parseByType($placeObj, 'airport');
	}

	public static function getCountry($placeObj)
	{
		return self::parseByType($placeObj, 'country');
	}

	public static function getACLocality($placeObj)
	{
		return self::getACByType($placeObj, 'locality');
	}

	public static function findAdminLevel2AC($placeObj)
	{
		return self::getACByType($placeObj, 'administrative_area_level_2');
	}

	public static function getACAdminLevel1($placeObj)
	{
		return self::getACByType($placeObj, 'administrative_area_level_1');
	}

	public static function findCountryAC($placeObj)
	{
		return self::getACByType($placeObj, 'country');
	}

	public static function getAddress($placeObj)
	{
		if ($placeObj->status != 'OK')
		{
			return false;
		}
		$types	 = ['locality', 'administrative_area_level_2', 'administrative_area_level_1', 'country'];
		$address = '';
		$results = $placeObj->results[0]->address_components;
		foreach ($results as $key => $obj)
		{
			foreach ($types as $type)
			{
				if (in_array($type, $obj->types))
				{
					$address .= $obj->long_name;
					if ($type != 'country')
					{
						$address .= ',';
					}
				}
			}
		}
		return $address;
	}

	public function callAPI($url, $type, $logAPI = true,$attribute="")
	{
		if ($logAPI)
		{
		$atr_id					 = ApiTracking::add($url, $type);
		}
		$ch						 = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$response				 = curl_exec($ch);
		$errorNo				 = curl_errno($ch);
		$error					 = curl_error($ch);
		//     $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$result					 = ['success' => true];
		$GLOBALS['API'][$url]	 = $response;
		Logger::trace($response);
		Logger::trace($errorNo);
		Logger::trace($error);
		if ($atr_id > 0 && $logAPI)
		{
			if (Filter::isJSON($response))
			{
				$response = json_encode(json_decode($response));
			}
			ApiTracking::updates($atr_id, $response);
		}
		if ($errorNo)
		{
			$result['success']		 = false;
			$result['errorCode']	 = 1;
			$result['errorMessage']	 = $error;
			Logger::trace($error);
			Logger::trace($response);
			Logger::error(new Exception("Google API Failed: " . $errorNo));
		}
		else
		{
			
			$resdata = json_decode($response);
			$resdata->result;
			$status = $resdata->status;
			$resdata = ($attribute != '') ? $resdata->result : $resdata;
			if ($attribute != '' && $status == 'OK')
			{
				goto elseBlock;
			}


			if ($resdata->status == "OVER_QUERY_LIMIT" || $resdata->status != 'OK')
			{
				$result['success']		 = false;
				$result['errorCode']	 = 2;
				$result['errorMessage']	 = $resdata->status;
				Logger::trace($response);
				Logger::error(new Exception("Google API Failed: " . $resdata->status));
			}
			else
			{
				elseBlock:
				$result['data'] = $resdata;
			}
		}
		return $result;
	}

	/**
	 * old service : getObjectByPlaceId
	 * New place details api with sessionToken
	 * @param type $placeId
	 * @param type $sessiontoken
	 * @return type
	 */
	public static function getObjectBySessionPlaceId($placeId, $sessiontoken)
	{
		$key	 = 'getObjectByPlaceId_' . $placeId;
		$result	 = $GLOBALS[$key];

		if ($result && $result['status'] != 'REQUEST_DENIED')
		{
			goto result;
		}
		//$sessiontoken="";
		$params					 = [];
		$params["fields"]		 = "address_components,type,formatted_address,geometry";
		$params["place_id"]		 = $placeId;
		$params["key"]			 = Config::getGoogleApiKey('apikey');
		$params["sessiontoken"]	 = $sessiontoken;
		$paramString			 = http_build_query($params);
		$url					 = "https://maps.googleapis.com/maps/api/place/details/json?{$paramString}";
		Logger::trace("Place with session URL :" . $url);
		if ($sessiontoken == "" || $sessiontoken == null)
		{
			Logger::error(new Exception("Session Token missing for URL: " . $url));
		}
		$result = self::getInstance()->callAPI($url,3,true,$attribute ='result');
		$data = false;
		if($result['success'])
		{
			$data = $result['data'];
		}

		result:
		return $data;
	}

/**
 * UPCOMING
 * @param string $coordinates

 */
public static function getRouteBycoordinates($coordinateArr)
	{


		if (!$coordinateArr)
		{
			throw new CHttpException(406, "coordinates", 406);
		}


		$var					 = explode(";", $coordinates);
		$country				 = "in";
		$params					 = [];
		$params["origin"]		 = $coordinateArr[0]['lat'] . ',' . $coordinateArr[0]['lng'];
		$params["destination"]	 = $coordinateArr[1]['lat'] . ',' . $coordinateArr[1]['lng'];
		$params["mode"]			 = 'driving';
		$params["units"]		 = 'metric';
		$params["key"]			 = Config::getGoogleApiKey('apikey');

		$paramString = http_build_query($params);

		$url = "https://maps.googleapis.com/maps/api/directions/json?{$paramString}";

		$ch			 = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$response	 = curl_exec($ch);
		$errorNo	 = curl_errno($ch);
		$error		 = curl_error($ch);
		// $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $response;

		return $result; //$result['data'];
	}

}

?>