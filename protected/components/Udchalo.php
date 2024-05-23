<?php

class Udchalo extends CComponent
{
	/* Push Cancellation Alert */
	public function callAPI($fName, $param)
	{
		$apiServerUrl	 = "https://cab-engine-prod-api.udchalo.com";
		$functionUrl	 = "/api/outstation/";
		$apiKey			 = "R0FI1Y4uA7y27WPvuVyU";

		$apiURL		 = $apiServerUrl . $functionUrl . $fName . "?apikey=" . $apiKey;
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
		$model				 = Booking::model()->findByPk($request->bkgId);
		$agentId             = $model->bkg_agent_id;
		$dataList			 = $this->setApiData($request);
		$patModel			 = PartnerApiTracking::add($request->typeAction, $dataList, $agentId, $model, $model->bkg_pickup_date, $request->pid);
		$responseParamList	 = $this->callAPI($request->fName, $dataList);
		$response			 = $this->parseResponse($responseParamList);
		$patModel->updateData(json_decode($response->response), $response->status, $model->bkg_id);
		return $response;
	}

	public function setApiData($request)
	{
		Logger::create("SET API DATA 1 ==>".serialize($dataList), CLogger::LEVEL_TRACE);
		$dataList["bookingId"]			 = $request->bookingId;
		$dataList["bookingStatus"]		 = $request->bookingStatus;
		$dataList["bookingStatusCode"]	 = $request->bookingStatusCode;
		switch ($request->bookingStatus)
		{
			case "Completed":
				$dataList["tripEndDateTime"]	 = $request->tripEndDateTime;
				$dataList["totalDistanceInKm"]	 = $request->totalDistanceInKm;
				$dataList["totalTimeInMins"]	 = $request->totalTimeInMins;
				$dataList["baseFare"]			 = $request->baseFare;
				$dataList["cgst"]				 = $request->cgst;
				$dataList["sgst"]				 = $request->sgst;
				$dataList["tollTax"]			 = $request->tollTax;
				$dataList["totalAmount"]		 = $request->totalAmount;
				break;
			case "Cancelled":
				$dataList["cancelReasonId"]		 = $request->cancelReasonId;
				$dataList["cancellationReason"]	 = $request->cancellationReason;
				$dataList["cancellationCharge"]	 = $request->cancellationCharge;
				$dataList["refundAmount"]		 = $request->refundAmount;
				$dataList["cancelledBy"]		 = "Customer";
				break;
			case "Driver allocated":
				$dataList["driverName"]			 = $request->driverName;
				$dataList["driverMobile"]		 = $request->driverMobile;
				$dataList["cabNo"]				 = $request->cabNo;
				$dataList["cabModel"]			 = $request->cabModel;
				$dataList["otp"]				 = $request->otp;
				break;
			default:
				break;
		}
		Logger::create("DATALIST ==>".serialize($dataList), CLogger::LEVEL_TRACE);
		return $dataList;
	}

}
