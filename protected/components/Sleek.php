<?php

class Sleek extends CComponent
{
	/* Push Cancellation Alert */
	public function callAPI($fName, $param)
	{
//		$apiServerUrl	 = "https://cab-engine-prod-api.udchalo.com";
//		$functionUrl	 = "/api/outstation/";
//		$apiKey			 = "R0FI1Y4uA7y27WPvuVyU";
//https://alltaxitravel.com/beta/webservices/getBookingTripStartInfo/api_key/cbba9df8-6a63-4562-80f9-a882ca3f7b23



	    $apiServerUrl	 = "https://alltaxitravel.com/";
		$functionUrl	 = "webservices/getBookingDriverJourneyInfo";
		$apiKey			 = "cbba9df8-6a63-4562-80f9-a882ca3f7b23";
		$apiURL		 = $apiServerUrl . $functionUrl . $fName . "/api_key/" . $apiKey;
		$postData	 = json_encode($param);

		$ch					 = curl_init($apiURL);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($postData))
		);
		$jsonResponse		 = curl_exec($ch);
		$responseParamList	 = json_decode($jsonResponse, true);
		return $responseParamList;
	}

	public function parseResponse($responseParamList)
	{
		$response			 = new PartnerResponse();
		$response->status	 = 2;
		if ((isset($responseParamList['success']) && $responseParamList['success'] == true) || (isset($responseParamList['status']) && $responseParamList['status'] == 200))
		{
			$response->status = 1;
		}
		$response->response = json_encode($responseParamList);
		return $response;
	}

	public function initiateRequest($request)
	{

		$model	 = Booking::model()->findByPk($request->bkgId);
		$agentId = $model->bkg_agent_id;
		//$dataList			 = $this->setApiData($request);
		switch ($request->bookingStatus)
		{
			case "Driver Cab Details":
				$type		 = "cabDriverUpdate";
				$dataList	 = new Stub\booking\GetDetailsResponse();
				$dataList->setCabDriver($model);

				break;
			case "Booking Trip Start":
				$type		 = "tripStart";
				$dataList	 = new Stub\common\PartnerDrvStatPush();
				$dataList->setData($request);

				break;
			case "Booking Trip Stop":
				$type		 = "tripEnd";
				$dataList	 = new Stub\common\PartnerDrvStatPush();
				$dataList->setData($request);

				break;
			case "Booking cancelled":
				$type		 = "tripCancel";
//				$dataList	 = new Stub\common\PartnerDrvStatPush();
//				$dataList->setData($request);
				//$dataList	 = new Stub\booking\GetDetailsResponse();
				//$dataList->setCancel($model);
				$dataList	 = new Stub\booking\CancelResponse();
				$dataList->setPushData($request);

				break;
			default:
				break;
		}

		$getdataList = Filter::removeNull($dataList);

		$arrResponse		 = [];
		$arrResponse['type'] = $type;
		$arrResponse['data'] = $getdataList;


		$patModel			 = PartnerApiTracking::add($request->typeAction, $arrResponse, $agentId, $model, $model->bkg_pickup_date, $request->pid);
		$responseParamList	 = $this->callAPI($request->fName, $arrResponse);
		$response			 = $this->parseResponse($responseParamList);
		$patModel->updateData(json_decode($response->response), $response->status, $model->bkg_id);
		return $response;
	}

}
