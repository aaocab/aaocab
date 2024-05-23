<?php

class MegaCabs extends CComponent
{
	/* Push cancellation start */

	public function callAPI($fName, $params)
	{
		$apiServerUrl	 = "https://capi.megacabs.com";
		$functionUrl	 = "/api/v1/outstation/";
		$apiKey			 = "53247267-d42c-41da-92c2-271c8f9d3a1a";

		$apiURL		 = $apiServerUrl . $functionUrl . $fName . "?apiKey=" . $apiKey;
		$postData	 = json_encode($params);

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
				$dataList["cabModel"]            = $request->cabModel;
				$dataList["otp"]                 = $request->otp;
				break;
			default:
				break;
		}
		return $dataList;
	}

}
