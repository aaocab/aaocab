<?php

class Spicejet extends CComponent
{

	public function initiateRequest($partnerRequest)
	{
		/** @var Booking $model */
		$model	 = Booking::model()->findByPk($partnerRequest->bkgId);
		$agentId = $model->bkg_agent_id;

		switch ($partnerRequest->bookingStatus)
		{
			case "tripStart":
				$type = ($model->bkg_transfer_type == 1) ? "tripStartBooking" : "tripStartDeparture";
				$dataList	 = new Stub\common\PartnerDrvStatPush();
				$dataList->setTripStartSJData($partnerRequest);
				break;

			case "tripEnd":
				$type		 = "tripEndBooking";
				$dataList	 = new Stub\common\PartnerDrvStatPush();
				$dataList->setStopSJData($partnerRequest);
				break;

			case "leftforpickup":
				$type		 = "leftForPickup";
				$dataList	 = new Stub\common\PartnerDrvStatPush();
				$dataList->setSJpickupData($partnerRequest);
				break;

			case "driverDetail":
				$type = ($model->bkg_transfer_type == 1) ? "assignChaufferBooking" : "assignChaufferDeparture";
				$dataList	 = new Stub\spicejet\GetDetailsResponse();
				$dataList->setCabDriver($model);
				break;

			case "arrived":
				$type		 = "arrived";
				$dataList	 = new Stub\common\PartnerDrvStatPush();
				$dataList->setSJpickupData($partnerRequest);
				break;

			case "updateLastLocation":
				$type		 = "updateCabLocation";
				$dataList	 = new Stub\common\PartnerDrvStatPush();
				$dataList->setSJpickupData($partnerRequest, $flag= 1);
				break;

			case "reAssign":
				$type		 = "reassignChaufferBooking";
				$dataList	 = new Stub\spicejet\GetDetailsResponse($model);
				$dataList->setCabDriver($model);
				break;

			case "customerCancel":
				$type		 = "cancelBooking";
				$dataList	 = new Stub\booking\CancelResponse();
				$dataList->setSpicejetPushData($partnerRequest);
				break;

			default:
				break;
		}

		$getdataList		 = Filter::removeNull($dataList);
		$arrResponse = $getdataList;

		$patModel = PartnerApiTracking::add($partnerRequest->typeAction, $arrResponse, $agentId, $model, $model->bkg_pickup_date);

		$responseParamList	 = $this->callAPI($arrResponse, $type);
		$partnerResponse	 = $this->parseResponse($responseParamList);
		$patModel->updateData(json_decode($partnerResponse->response), $partnerResponse->status, $model->bkg_id);
		return $partnerResponse;
	}

	public function parseResponse($responseParamList)
	{
		$response			 = new PartnerResponse();
		$response->status	 = 2;
		$responseData = json_decode($responseParamList);
		if ((isset($responseData->response->success) && $responseData->response->success == true) || 
				(isset($responseData->response->success) && $responseData->response->success == true) || $responseData->success == true)
		{
			$response->status = 1;
		}
		$response->response = $responseParamList;
		return $response;
	}

	public function callApi($arrData, $type)
	{
		$apiServerUrl					 = "https://prodapi.mojoboxx.com/spicescreen/webapi/" . $type;
		$ch								 = curl_init($apiServerUrl);
		$jsonData						 = json_encode($arrData);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($jsonData))
		);
		$jsonResponse					 = curl_exec($ch);
		$responseParamList	 = json_decode($jsonResponse, true);

		Logger::trace('APISERVER URL: ' . $apiServerUrl);
		Logger::trace('JSON DATA: ' . $jsonData);
		Logger::trace('JSON RESPONSE: ' . $jsonResponse);

		return $jsonResponse;
	}

	/**
	 * 
	 * @param type $advance
	 * @return boolean
	 */
	public static function setBlockAutoAssignmentStatus($advance=0)
	{		
		$assignStatus = ($advance == 0) ? 1 : 0;
		return $assignStatus;	
	}
}
