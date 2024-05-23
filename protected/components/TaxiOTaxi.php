<?php

class TaxiOTaxi extends CComponent
{

	public static function postSupply($cavId)
	{
		/** @var CabAvailabilities $model */
		$apiURL = 'http://www.taxiotaxi.com/api/victoria/cabAvailability?api=UXVFya4Ov64qsuAVAx81xU9AAweaUE9x';

		$resData			 = CabAvailabilities::getDetails($cavId);
		$routeRates			 = CabAvailabilities::calculateQuoteRate($resData);
		$totAmount			 = $routeRates->totalAmount;
		$requestParamList	 = [
			'operatorRefId'	 => $resData['cav_id'],
			'fromCityId'	 => $resData['cav_from_city'],
			'toCityId'		 => $resData['cav_to_cities'],
			'startDatetime'	 => $resData['cav_date_time'],
			'expireDatetime' => $resData['cav_expire'],
			'amount'		 => $totAmount,
			'cabNumber'		 => $resData['vhc_number'],
			'cabMake'		 => $resData['vht_make'],
			'cabModel'		 => $resData['vht_model'],
			'cabType'		 => $resData['cabType'],
		];

		$jsonData			 = json_encode($requestParamList);
		$ch					 = curl_init($apiURL);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json'
				)
		);
		$jsonResponse		 = curl_exec($ch);
		$responseParamList	 = json_decode($jsonResponse, true);
//		var_dump($responseParamList);
		$response			 = $responseParamList["success"];
		return $response;
	}

	public static function deativateAvailablity($cavId)
	{
		/** @var CabAvailabilities $model */
		$apiURL = 'http://www.taxiotaxi.com/api/victoria/deativateAvailablity?api=UXVFya4Ov64qsuAVAx81xU9AAweaUE9x';

		$requestParamList	 = ['operatorRefId' => $cavId];
		$jsonData			 = json_encode($requestParamList);
		$ch					 = curl_init($apiURL);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json'
				)
		);
		$jsonResponse		 = curl_exec($ch);
		$responseParamList	 = json_decode($jsonResponse, true);
//		var_dump($responseParamList);
		$response			 = $responseParamList["success"];
		return $response;
	}

}
