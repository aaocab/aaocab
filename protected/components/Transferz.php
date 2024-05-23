<?php

class Transferz extends CComponent
{

	public function initiateRequest($partnerRequest)
	{
		/** @var Booking $model */
		$model	 = Booking::model()->findByPk($partnerRequest->bkgId);
		
		$agentId = $model->bkg_agent_id;
		$journeyId = TransferzOffers::getJourneyIdByBkgId($model->bkg_id);
		if($model->bkgTrail->bkg_platform == 4 || $model->bkgTrail->bkg_platform == NULL || $model->bkgTrail->bkg_platform == 2)
		{
			switch ($partnerRequest->transferzStatus)
			{
				case "in-progress":
					$type		 = ($model->bkg_transfer_type == 1) ? "in-progress" : "in-progress";
					$dataList	 = new Beans\transferz\GetDetailsResponse();
					$dataList->setTransferzPickupData($partnerRequest);
					break;

				case "complete":
					$type		 = "complete";
					$dataList	 = new Beans\transferz\GetDetailsResponse();
					$dataList->setTransferzPickupData($partnerRequest);
					break;

				case "driver-underway":
					$type		 = "driver-underway";
					$dataList	 = new Beans\transferz\GetDetailsResponse();
					$dataList->setTransferzPickupData($partnerRequest);
					break;

				case "assign-driver":
					$type		 = "assign-driver";
					$dataList	 = new Beans\transferz\GetDetailsResponse();
					$dataList->assignDriver($model);
					break;

				case "driver-arrived":
					$type		 = "driver-arrived";
					$dataList	 = new Beans\transferz\GetDetailsResponse();
					$dataList->setTransferzPickupData($partnerRequest);
					break;
				case "driver-position":
					$type		 = "driver-position";
					$dataList	 = new Beans\transferz\GetDetailsResponse();
					$dataList->setTransferzPickupData($partnerRequest);
					break;	

				default:
					break;
			}
			$getdataList = Filter::removeNull($dataList, $model);
			$arrResponse = $getdataList;

			$patModel = PartnerApiTracking::add($partnerRequest->typeAction, $arrResponse, $agentId, $model, $model->bkg_pickup_date);

			if ($type == "driver-arrived" || $type == "driver-underway" || $type == "in-progress")
			{
				$url = "/journeys/" . $journeyId . "/status/" . $type;
			}
			else
			{
				$url = "/journeys/" . $journeyId . "/" . $type;
			}
			$arrData				 = array();
			$arrData['apiUrl']		 = $url;
			$arrData['methodType']	 = 'PUT';

			$responseParamList	 = $this->callAPI($arrData, $arrResponse);
			$partnerResponse	 = $this->parseResponse($responseParamList);
			if($patModel)
			{
			$patModel->updateData($partnerResponse->response, $partnerResponse->status, $model->bkg_id);
			}
		}
		else
		{
			$partnerResponse = false;
		}
		return $partnerResponse;
	}

	public function callApi($arrData = null, $data = null)
	{
		$arrCredentials	 = Transferz::getCredential();
		$arrHeaders		 = Transferz::getCurlHeaders();
		$apiURL			 = $arrCredentials['apiUrl'] . $arrData['apiUrl'];

		$jsonData			 = json_encode($data);
		$ch					 = curl_init($apiURL);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $arrData['methodType']);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeaders);
		$jsonResponse		 = curl_exec($ch);
		$responseParamList	 = json_decode($jsonResponse, true);
		
		Logger::writeToConsole('apiURL = ' . $apiURL);
		Logger::writeToConsole('arrHeaders = ' . json_encode($arrHeaders));
		Logger::writeToConsole('curlError = ' . curl_error($ch));
		Logger::writeToConsole('jsonResponse = ' . $jsonResponse);
		return $responseParamList;
	}

	public static function getCredential()
	{
		$arrCredentials = array();

		$arrCredentials = json_decode(Config::get('transferz.api.credential'), true);

		return $arrCredentials;
	}

	public static function getCurlHeaders()
	{
		$arrCredentials = Transferz::getCredential();

		$arrHeaders = array('Content-Type: application/json', 'Accept: application/json', 'X-API-Key: ' . $arrCredentials['apiKey']);

		return $arrHeaders;
	}

	/**
	 * 
	 * @param type $model
	 * @param type $data
	 * @return json
	 */
	public static function declineBooking($model, $data, $jsonObj)
	{
		$arrData				 = array();
		$arrData['apiUrl']		 = '/offers/' . $jsonObj->id . '/decline';
		$arrData['methodType']	 = 'POST';
		$arrResponse			 = Filter::removeNull($data);
		$typeAction				 = PartnerApiTracking::TYPE_DECLINE_BOOKING;

		$patModel			 = PartnerApiTracking::add($typeAction, $arrResponse, $model->bkg_agent_id, $model, $model->bkg_pickup_date);
		$objTransferz		 = new Transferz();
		//$responseParamList	 = $objTransferz->callAPI($arrData, $data);
		$time				 = Filter::getExecutionTime();
		$patModel->updateData($responseParamList, 1, $model->bkg_id, null, null, $time);
		return $responseParamList;
	}

	public function parseResponse($responseParamList)
	{
		$response			 = new PartnerResponse();
		$response->status	 = 2;
		$responseData		 = $responseParamList;
		if ($responseParamList['status'] == 1)
		{
			$response->status = 1;
		}
		$response->response = $responseParamList;
		return $response;
	}

	/**
	 * 
	 * @param type $model
	 * @param type $data
	 * @return type
	 */
	public static function acceptOffer($model, $data, $acceptOfferId)
	{
		$arrData				 = array();
		$arrData['apiUrl']		 = '/offers/' . $acceptOfferId . '/accept';
		$arrData['methodType']	 = 'POST';

		$typeAction = PartnerApiTracking::CONFIRM_BOOKING;

		$patModel = PartnerApiTracking::add($typeAction, $arrData, $model->bkg_agent_id, $model, $model->bkg_pickup_date);

		$objTransferz	 = new Transferz();
		$response		 = $objTransferz->callApi($arrData, $data);

		$time							 = Filter::getExecutionTime();
		$patModel->updateData($response, 1, $model->bkg_id, null, null, $time);
		return $response;
	}


	/**
	 * 
	 * @param type $model
	 * @param type $data
	 * @return type
	 */
	public static function getJourneyDetailsById($journeyId, $data = null)
	{
		$arrData				 = array();
		$arrData['apiUrl']		 = '/journeys/' . $journeyId;
		$arrData['methodType']	 = 'GET';

		$objTransferz			 = new Transferz();
		$response				 = $objTransferz->callApi($arrData, $data);
		return $response;
	}

}
