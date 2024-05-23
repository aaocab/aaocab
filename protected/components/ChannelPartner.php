<?php

class ChannelPartner extends CComponent
{
	/* Push Cancellation Alert */
	public function callAPI($fName, $param, $agentId)
	{
//		$apiServerUrl	 = "https://cab-engine-prod-api.udchalo.com";
//		$functionUrl	 = "/api/outstation/";
//		$apiKey			 = "R0FI1Y4uA7y27WPvuVyU";
//https://alltaxitravel.com/beta/webservices/getBookingTripStartInfo/api_key/cbba9df8-6a63-4562-80f9-a882ca3f7b23

        switch ($agentId)
		{
            case 12074:
				$apiServerUrl	 = "https://alltaxitravel.com/";
                $functionUrl	 = "webservices/getBookingDriverJourneyInfo";
                $key             = "/api_key/";
                $apiKey			 = "cbba9df8-6a63-4562-80f9-a882ca3f7b23";
				break;
            case 21937:
                //https://1mpj7pwqk5.execute-api.ap-south-1.amazonaws.com/sandbox/gozo?api-key=kreELZFC54a6scvv4u7c09IlWRLXbBYL9mo4moeG
				$apiServerUrl	 = "https://1mpj7pwqk5.execute-api.ap-south-1.amazonaws.com/";
                $functionUrl	 = "sandbox/gozo";
                $key             = "?api-key=";
                $apiKey			 = "kreELZFC54a6scvv4u7c09IlWRLXbBYL9mo4moeG";
				break;
             case 3936:
				$apiServerUrl	 = "https://cab-engine-prod-api.udchalo.com";
                $functionUrl	 = "/api/outstation/";
                $key             = "vendorDriverAllocation?apikey=";
                $apiKey			 = "R0FI1Y4uA7y27WPvuVyU";
				break;
            case 30242:
				$apiServerUrl	 = "https://www.ahataxis.com/webservices/driverJourneyInfo";
                $functionUrl	 = "";
                $key             = "";
                $apiKey			 = "";
				break;
            case 22310:
				$apiServerUrl	 = "https://www.ahataxis.com/webservices/driverJourneyInfo";
                $functionUrl	 = "/api/outstation/";
                $key             = "vendorDriverAllocation?apikey=";
                $apiKey			 = "R0FI1Y4uA7y27WPvuVyU";
				break;
            case 30228:
				$apiServerUrl	 = "https://transferapi.easemytrip.com/api/partner/v12/dispatch/callback";
                $functionUrl	 = "";
                $key             = "";
                $apiKey			 = "";
				break;
            case 22311:
				$apiServerUrl	 = "http://stagingtransferrootapi.easemytrip.com/api/gozo/v12/dispatch/callback";
                $functionUrl	 = "";
                $key             = "";
                $apiKey			 = "";
				break;
            case 22312:
				$apiServerUrl	 = "https://pragati.gozo.cab/api/journey-updates";
                $functionUrl	 = "";
                $key             = "";
                $apiKey			 = "";
				break;
            case 31381:
				$apiServerUrl	 = "https://pragati.gozo.cab/api/journey-updates";
                $functionUrl	 = "";
                $key             = "";
                $apiKey			 = "";
				break;
			case 35968:
				$apiServerUrl	 = "https://bookairportprodapi.mojoboxx.com/spicescreen/webapi";
                $functionUrl	 = "";
                $key             = "";
                $apiKey			 = "";
				break;
            case Config::get('QuickRide.partner.id'):
				$apiServerUrl	 = "https://qtds.getquickride.com/taxidemandserver/rest/api/partner/v1/gozo";
                $functionUrl	 = "";
                $key             = "";
                $apiKey			 = "";
				break;
			case Config::get('sugerbox.partner.id'):
				$apiServerUrl	 = "https://apigw01.sboxdc.com/oms/v2/mobility/95/updatestatus";
                $functionUrl	 = "";
                $key             = "";
                $apiKey			 = "";
				break;
			case Config::get('globalnri.partner.id'):
				$apiServerUrl	 = 'https://app.globalnridev.in/api/v1/cab-booking/booking/push-update/dTa5SweJq9b9WHeGkPy5Rr8S3SSd8BcP';
                $functionUrl	 = "";
                $key             = "";
                $apiKey			 = "";
				break;
			default:
				break;
        }
		$apiURL		 = $apiServerUrl . $functionUrl . $key . $apiKey;
		$postData	 = json_encode($param);
        $authorization =  ($agentId == 30228) ? "EFCTIoUREW" : (($agentId == 42596) ? "Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIyOCIsImlzcyI6IlF1aWNrUmlkZSIsImlhdCI6MTY1ODczNDA0MH0.qLbbn2lflB52QtsBBAsExcbCuJCQLXjokevU06sdMYIdZDAxPx9bWtTsTXAQKM_3E9s0FVHyGHhV5Zq56OqH-A" : "");

		$ch					 = curl_init($apiURL);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
            'Authorization: ' . $authorization,
			'Content-Length: ' . strlen($postData))
		);
		$jsonResponse		 = curl_exec($ch);
		
//		Logger::writeToConsole("ApiURL: ".$apiURL);
//		Logger::writeToConsole("PostData: ". json_encode($postData));
//		Logger::writeToConsole("Authorization: ".$authorization);
//		Logger::writeToConsole("Length: ". strlen($postData));
//		Logger::writeToConsole($jsonResponse);
		
		if (curl_errno($ch)) {
			$errNo = curl_errno($ch);
			$errMsg = curl_error($ch);
			Logger::writeToConsole("Curl Error (ErrNo: {$errNo}): " . $errMsg);
			Logger::error("Channel Partner Curl Error (ErrNo: {$errNo}): " . $errMsg);
		}
		
		$responseParamList	 = json_decode($jsonResponse, true);
		return $responseParamList;
	}

	public function parseResponse($responseParamList)
	{
		$response			 = new PartnerResponse();
		$response->status	 = 2;
		if ((isset($responseParamList['success']) && $responseParamList['success'] == true) || (isset($responseParamList['status']) && $responseParamList['status'] == 200) || (isset($responseParamList['response']['success']) && $responseParamList['response']['success'] == true) || (isset($responseParamList['Success']) && $responseParamList['Success'] == true) || (isset($responseParamList['status']) && $responseParamList['status'] == 1))
		{
			 $response->status = 1;
		}
		$response->response = json_encode($responseParamList);
		return $response;
	}
    
    public function intiateTelegramRequest($request)
    {
        $type     = "telegramAuthentication";
        $dataList = new Stub\telegram\AuthenticationResponse();
        $dataList->setData($request);
        $getdataList = Filter::removeNull($dataList);
        $arrResponse         = [];
        $arrResponse['type'] = $type;
        $arrResponse['data'] = $getdataList;
        $responseParamList   = $this->callAPI($request->fName, $arrResponse, 31381);
        $response            = $this->parseResponse($responseParamList);
        return $response;
    }

	public function initiateRequest($request)
	{
		$model	 = Booking::model()->findByPk($request->bkgId);
		$agentId = $model->bkg_agent_id;
		//$dataList			 = $this->setApiData($request);
		switch ($request->bookingStatus)
		{
			case "driverDetail":
				$type		 = "cabDriverUpdate";
				$dataList	 = new Stub\booking\GetDetailsResponse();
				$dataList->setCabDriver($model);

				break;
            case "leftforpickup":
				$type		 = "leftforpickup";
				$dataList	 = new Stub\common\PartnerDrvStatPush();
				$dataList->setData($request);

				break;
            case "noshow":
				$type		 = "noshow";
				$dataList	 = new Stub\common\PartnerDrvStatPush();
				$dataList->setData($request);

				break;
        case "arrived":
				$type		 = "arrived";
				$dataList	 = new Stub\common\PartnerDrvStatPush();
				$dataList->setData($request);

				break;
			case "tripStart":
				$type		 = "tripStart";
				$dataList	 = new Stub\common\PartnerDrvStatPush();
				$dataList->setData($request);

				break;
			case "tripEnd":
				$type		 = "tripEnd";
				$dataList	 = new Stub\common\PartnerDrvStatPush();
				$dataList->setTripEndData($request);

				break;
            
			case "customerCancel":
				$type		 = "tripCancel";
				$dataList	 = new Stub\booking\CancelResponse();
				$dataList->setPushData($request);
				break;

			case "updateLastLocation":
				$type		 = "updateLastLocation";
				$dataList	 = new Stub\common\PartnerDrvStatPush();
				$dataList->setData($request);
				break;
			default:
				break;
		}

		$getdataList = Filter::removeNull($dataList);

		$arrResponse		 = [];
		$arrResponse['type'] = $type;
		$arrResponse['data'] = $getdataList;
		
		// TO BE REMOVED LATER
		if($type == 'tripCancel' && $agentId == 30228 && strpos($dataList->message, 'credit limit')) // EMT
		{
			Logger::create("EMT Trip Cancel");
			Logger::pushTraceLogs();
		}

		$patModel			 = PartnerApiTracking::add($request->typeAction, $arrResponse, $agentId, $model, $model->bkg_pickup_date, $request->pid);
		$responseParamList	 = $this->callAPI($request->fName, $arrResponse, $agentId);
		$response			 = $this->parseResponse($responseParamList);
		$patModel->updateData(json_decode($response->response), $response->status, $model->bkg_id);
		return $response;
	}

	/**
	 * 
	 * @param type $bkgModel
	 * @param type $typeAction
	 */
	public function pushDataTokayak($bkgModel, $typeAction)
	{
		try
		{
			$totalAmount = $bkgModel->bkgInvoice->bkg_total_amount;
			$commission	 = $bkgModel->bkgInvoice->bkg_partner_commission;
			$partnerCode = $bkgModel->bkgAgent->agt_code;
			$bkgId		 = $bkgModel['bkg_booking_id'];
			
			$datetime = new DateTime($bkgModel['bkg_create_date'],new DateTimeZone('Asia/Kolkata'));
			$bookedOn = gmdate("Y-m-d\TH:i:s\Z", $datetime->getTimestamp());
			

			$kayakclickid	 = $bkgModel['bkg_partner_ref_id'];
			$bookingtype	 = "car";

			if ($typeAction == 11)
			{
				$requestParamList	 = [
					"partnercode"		 => $partnerCode,
					"bookingid"			 => $bkgId,
					"bookedon"			 => $bookedOn,
					"price"				 => $totalAmount,
					"currency"			 => "INR",
					"kayakcommission"	 => $commission,
					"commissioncurrency" => "INR",
					"kayakclickid"		 => $kayakclickid,
					"bookingtype"		 => $bookingtype
				];
				$url				 = "https://www.kayak.com/s/s2s/confirm?partnercode={$partnerCode}&bookingid={$bkgId}&bookedon={$bookedOn}&price={$totalAmount}&currency=INR&kayakcommission={$commission}&commissioncurrency=INR&kayakclickid={$kayakclickid}&bookingtype={$bookingtype}";
			}
			else if ($typeAction == 8)
			{
				$requestParamList	 = [
					"partnercode"	 => $partnerCode,
					"bookingid"		 => $bkgId,
					"bookedon"		 => $bookedOn,
				];
				$url				 = "https://www.kayak.com/s/s2s/cancel?partnercode=$partnerCode&bookingid=$bkgId&bookedon=$bookedOn";
			}


			$patModel = PartnerApiTracking::add($typeAction, $requestParamList, $bkgModel->bkg_agent_id, $bkgModel, $bkgModel->bkg_pickup_date);

			if(Config::get('kayak.s2s.tracking') == 1 && in_array($typeAction, [11,8]))
			{
				$ch				 = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HEADER, false);
				$jsonResponse	 = curl_exec($ch);
				curl_close($ch);

				//file_get_contents($url);

				$responseParamList = json_decode($jsonResponse, true);
				//$response			 = $this->parseResponse($responseParamList);
				//$status = json_decode($jsonResponse, true);

				$time = Filter::getExecutionTime();
				$patModel->updateData($responseParamList, $jsonResponse, $bkgModel->bkg_id, null, null, $time);
			}
		}
		catch (Exception $ex)
		{
			$err = $ex->getMessage();
		}
	}

}
