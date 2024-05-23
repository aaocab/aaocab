<?php

class MapMyIndiaApi
{

	private static $_instance = null;
	private $apiKey;

	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	/** @return MapMyIndiaApi */
	public static function getInstance($key = null)
	{
		if (self::$_instance == null)
		{
			$api = Config::getMapMyIndiaApiKey('apikey');
			if ($key != null)
			{
				$api = $key;
			}
			self::$_instance = new MapMyIndiaApi($api);
		}
		return self::$_instance;
	}


	public  function getRouteBycoordinates($coordinateArr)
	{
		if (!$coordinateArr)
		{
			throw new CHttpException(406, "coordinates", 406);
		}

		$origin		 = $coordinateArr[0]['lng'] . ',' . $coordinateArr[0]['lat'];
		$destination = $coordinateArr[1]['lng'] . ',' . $coordinateArr[1]['lat'];
		$coordinates = $origin . ';' . $destination;

		$country		 = "in";
		$params			 = [];
		$params["steps"] = "true";
		$params["rtype"] = 1;
		$paramString = http_build_query($params);
		$url		 = "https://apis.mapmyindia.com/advancedmaps/v1/{$this->apiKey}/route_adv/driving/{$coordinates}?{$paramString}";
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
		
}

?>