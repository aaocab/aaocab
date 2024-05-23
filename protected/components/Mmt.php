<?php

class Mmt extends CComponent
{

	public function initiateRequest($partnerRequest)
	{
		$mmtResponse = false;
		$model		 = Booking::model()->findByPk($partnerRequest->bkgId);
		$dataList	 = $this->setApiData($partnerRequest);
		$aatModel	 = AgentApiTracking::model()->add($partnerRequest->typeAction, $dataList, $model, Filter::getUserIP());
		for ($count = 0; $count < 2 && !$mmtResponse; $count++)
		{
			$responseParamList	 = $this->callAPI($dataList);
			$partnerResponse	 = $this->parseResponse($responseParamList);
			$mmtResponse		 = ($partnerResponse->status == 1) ? true : false;
		}
		$time = Filter::getExecutionTime();
		$aatModel->updateResponse($partnerResponse, $model->bkg_id, $partnerResponse->status, $error_type, $error_msg, $time);
		return $partnerResponse;
	}

	public function setApiData($partnerRequest)
	{
		$dataList['type']				 = $partnerRequest->type;
		$dataList['booking_id']			 = $partnerRequest->mmtBookingId;
		$dataList['vendor_booking_id']	 = $partnerRequest->bkgId;


		switch ($partnerRequest->type)
		{
			case "tripStart":
				$dataList['trip_start_time'] = $partnerRequest->tripStartTime;
				break;
			case "vendorTripRequest":
				$dataList['odometer_start_reading'] = $partnerRequest->odometer_start_reading."";
				$dataList['trip_start_timestamp'] = $partnerRequest->tripStartTime;
				break;
			case "driverDetail":
				$dataList['cab_number']		 = $partnerRequest->cabNo;
				$dataList['driver_name']	 = $partnerRequest->driverName;
				$dataList['driver_mobile']	 = $partnerRequest->driverMobile;
				$dataList['otp']			 = $partnerRequest->otp;
				break;
			default:
				break;
		}
		return $dataList;
	}
 
	public function parseResponse($responseParamList)
	{
		$responseList	 = json_decode($responseParamList, true);
		$partnerResponse = new PartnerResponse();
		if (isset($responseList['success']) && $responseList['success'] == true)
		{
			$partnerResponse->status = 1;
		}
		else if (isset($responseList['vendor_response']) && $responseList['vendor_response']['is_success'] == true)
		{
			$partnerResponse->status = 1;
		}
		else if (isset($responseList['status']) && $responseList['status'] == 'success')
		{
			$partnerResponse->status = 1;
		}
		else
		{
			$partnerResponse->status = 2;
		}
		$partnerResponse->response = json_encode($responseList);
		return $partnerResponse;
	}

	public function callApi($arrData)
	{
		$apiURL = 'https://cabs-internal.makemytrip.com/updateCabDriverDetail';
		if ($arrData['type'] =='vendorTripRequest')
		{
			$apiURL = 'https://cabs-internal.makemytrip.com/updateVendorTripDetails';
		}

		$ch				 = curl_init($apiURL);
		$jsonData		 = json_encode($arrData);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'auth-id: GOZO',
			'auth-token: f2c8adff-5f4b-4e54-98fe-678129329ad9')
		);
		$jsonResponse	 = curl_exec($ch);
		$GLOBALS['MMT_CURL_ERRNO'] = curl_errno($ch);
		$GLOBALS['MMT_CURL_HTTPCODE'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	//	print_r($jsonResponse);
		return $jsonResponse;
	}

}
