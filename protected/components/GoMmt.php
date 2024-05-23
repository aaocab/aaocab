<?php

class GoMmt extends CComponent
{

    public $liveAPIUrl    = 'https://cabs-internal.makemytrip.com/';
    public $sandboxAPIUrl = 'http://pp1dispatchtracking.goibibo.com'; //'https://pp1dispatchtracking.goibibo.com/';
    public $devAPIUrl     = 'http://pp1dispatchtracking.goibibo.com';
    public static $SALT   = 'pQXex89f0G';


    /**
     * error code mapping with MMT error code for search API
     * @var $errorMessage
     * @return array
     */
    public static function errorSearchMsgList($errorMessage)
    {
		$errorMessage = self::errorMsgList($errorMessage);
        $errorMsglist = [
            "You are expected to arrive"                                                                                => "PARTNER_SEARCH_INVALID_TIME",
            "Departure time should"                                                                                     => "PARTNER_SEARCH_INVALID_TIME",
            "Source City cannot be blank.,Destination City cannot be blank."                                            => "PARTNER_SEARCH_INVALID_LOCATION",
            "Destination cannot be blank.,Please select destination city,Source cannot be blank.,Required data missing" => "PARTNER_SEARCH_INVALID_LOCATION",
            "Destination City cannot be blank."                                                                         => "PARTNER_SEARCH_INVALID_LOCATION",
            "Please select valid destination city,Please select valid source city"                                      => "PARTNER_SEARCH_INVALID_LOCATION",
            "Please select valid source city,Please select valid destination city"                                      => "PARTNER_SEARCH_INVALID_LOCATION",
            "Source City cannot be blank."                                                                              => "PARTNER_SEARCH_INVALID_LOCATION",
            "Please select valid source city"                                                                           => "PARTNER_SEARCH_INVALID_LOCATION",
            "No cabs available for this route"                                                                          => "PARTNER_SEARCH_INVENTORY_UNAVAILABLE",
            "Sold out"																									=> "PARTNER_SEARCH_INVENTORY_UNAVAILABLE",
			"Search request limit exceeded"																				=> "PARTNER_SEARCH_INVENTORY_UNAVAILABLE",
            "Internal server error"                                                                                     => "Internal server error",
			"Package"																									=> "PARTNER_SEARCH_INVENTORY_UNAVAILABLE",
			"Cab type not supported for this route."																	=> "PARTNER_SEARCH_INVENTORY_UNAVAILABLE"
        ];
        if ($errorMessage != '')
        {
            return $errorMsglist[$errorMessage];
        }
        asort($errorMsglist);
        return $errorMsglist;
    }

    /**
     * error code mapping with MMT error code for Block API
     * @var $errorMessage
     * @return array
     */
    public static function errorBlockMsgList($errorMessage)
    {
		$errorMessage = self::errorMsgList($errorMessage);
        $errorMsglist = [
            "Departure time should"                  => "PARTNER_BLOCK_INVALID_TIME",
            "No cabs available for this route"       => "PARTNER_BLOCK_INVENTORY_UNAVAILABLE",
            "Sold out"								 => "PARTNER_BLOCK_INVENTORY_UNAVAILABLE",
            "BLOCK Failed: Prices have increased"    => "PARTNER_BLOCK_PRICE_INCR",
            "Internal server error"					 => "Internal server error",
			"Package"								 => "PARTNER_BLOCK_INVENTORY_UNAVAILABLE",    
			"Cab type not supported for this route." => "PARTNER_SEARCH_INVENTORY_UNAVAILABLE"
        ];
        if ($errorMessage != '')
        {
            return $errorMsglist[$errorMessage];
        }
        asort($errorMsglist);
        return $errorMsglist;
    }

    /**
     * error code mapping with MMT error code for Confirm API
     * @var $errorMessage
     * @return array
     */
    public static function errorConfirmMsgList($errorMessage)
    {
		$errorMessage = self::errorMsgList($errorMessage);
        $errorMsglist = [
            "Reference Id already exists"                 => "Reference Id already exists",
            "Booking already confirmed"                   => "Booking already confirmed",
            "CONFIRM Failed: Advance amount is incorrect" => "CONFIRM Failed: Advance amount is incorrect",
            "CONFIRM Failed: Price amount is incorrect"   => "CONFIRM Failed: Price amount is incorrect",
            "Internal server error"                => "Internal server error"
        ];
        if ($errorMessage != '')
        {
            return $errorMsglist[$errorMessage];
        }
        asort($errorMsglist);
        return $errorMsglist;
    }

	/**
	 * 
	 * @param type $errorMessage
	 * @return string
	 */
	public static function errorMsgList($errorMessage)
	{
		if (str_contains($errorMessage, 'CDbCommand failed to execute'))
        {
            $errorMessage = "Internal server error";
        }
		return $errorMessage;
	}

    public function initiateRequest($partnerRequest)
    {


        $mmtResponse = false;
        $model       = Booking::model()->findByPk($partnerRequest->bkgId);
        $dataList    = $this->setApiData($partnerRequest);
        $aatModel    = AgentApiTracking::model()->add1($partnerRequest->typeAction, $dataList, $model, Filter::getUserIP());
        $time        = Filter::getExecutionTime();

        for ($count = 0; $count < 2 && !$mmtResponse; $count++)
        {
            $responseParamList = $this->callAPI($dataList);
            $partnerResponse   = $this->parseResponse($responseParamList, $partnerRequest);
            $mmtResponse       = ($partnerResponse->status == 1) ? true : false;
        }

        $time      = Filter::getExecutionTime() - $time;
        $error_msg = '';

        if (!$mmtResponse)
        {
            if ($partnerResponse->response)
            {
                $errResponse = json_decode($partnerResponse->response);
                $error_msg   = $errResponse->error;
            }
        }

        $aatModel->updateResponse($partnerResponse, $model->bkg_id, $partnerResponse->status, null, $error_msg, $time);

        return $partnerResponse;
    }

    public function setApiData($partnerRequest)
    {
//		$dataList['type']				 = $partnerRequest->type;
//		$dataList['booking_id']			 = $partnerRequest->mmtBookingId;
//		$dataList['vendor_booking_id']	 = $partnerRequest->bkgId;

        switch ($partnerRequest->type)
        {

            case"tripStart":
                $dataList['apiUrl']                     = '/api/partner/v1/track/' . $partnerRequest->mmtBookingId . '/boarded';
                $dataList['booking_id']                 = $partnerRequest->mmtBookingId;
                $dataList['latitude']                   = $partnerRequest->lattitude;
                $dataList['longitude']                  = $partnerRequest->longitude;
                $dataList['timestamp']                  = strtotime($partnerRequest->tripStartTime) * 1000;
                $dataList['device_id']                  = "98:0C:A5:BB:CC:17";
                $dataList['face_mask']['status']        = $partnerRequest->faceMask;
                $dataList['face_mask']['url']           = $partnerRequest->faceMaskUrl;
                $dataList['infographic_card']['status'] = $partnerRequest->infographicCard;
                $dataList['infographic_card']['url']    = $partnerRequest->infographicUrl;
                $dataList['sanitization_kit']['status'] = $partnerRequest->sanitizationKit;
                $dataList['sanitization_kit']['url']    = $partnerRequest->sanitizationKitUrl;
                $dataList['arogya_setu']['status']      = $partnerRequest->arogyaSetu;

                break;
            case"tripEnd":
                $dataList['apiUrl']       = '/api/partner/v1/track/' . $partnerRequest->mmtBookingId . '/alight';
                $dataList['booking_id']   = $partnerRequest->mmtBookingId;
                $dataList['latitude']     = $partnerRequest->lattitude;
                $dataList['longitude']    = $partnerRequest->longitude;
                $dataList['timestamp']    = strtotime($partnerRequest->tripStartTime) * 1000;
                $dataList['device_id']    = "98:0C:A5:BB:CC:17";
                $dataList['extra_charge'] = $partnerRequest->extra_charge;
                $dataList['invoice_url']  = '';

                $dataList['extra_fare_breakup']['extra_travelled']['amount']               = $partnerRequest->extrakmCharge;
                $dataList['extra_fare_breakup']['extra_travelled']['items'][0]['name']     = 'Extra Travelled';
                $dataList['extra_fare_breakup']['extra_travelled']['items'][0]['amount']   = $partnerRequest->extrakmCharge;
                $dataList['extra_fare_breakup']['extra_travelled']['items'][0]['receipt']  = null;
                $dataList['extra_fare_breakup']['extra_travelled']['details']['extra_kms'] = $partnerRequest->extrakm;

                $dataList['extra_fare_breakup']['extra_time']['amount']                   = $partnerRequest->extraTimeCharge;
                $dataList['extra_fare_breakup']['extra_time']['items'][0]['name']         = 'Extra Time';
                $dataList['extra_fare_breakup']['extra_time']['items'][0]['amount']       = $partnerRequest->extraTimeCharge;
                $dataList['extra_fare_breakup']['extra_time']['items'][0]['receipt']      = null;
                $dataList['extra_fare_breakup']['extra_time']['details']['extra_minutes'] = $partnerRequest->extraMinutes;

                $dataList['extra_fare_breakup']['night_charges']['amount']              = $partnerRequest->nightCharges;
                $dataList['extra_fare_breakup']['night_charges']['items'][0]['name']    = 'Night Charges';
                $dataList['extra_fare_breakup']['night_charges']['items'][0]['amount']  = $partnerRequest->nightCharges;
                $dataList['extra_fare_breakup']['night_charges']['items'][0]['receipt'] = null;

                $dataList['extra_fare_breakup']['toll_charges']['amount']              = $partnerRequest->tollCharges;
                $dataList['extra_fare_breakup']['toll_charges']['items'][0]['name']    = 'Toll';
                $dataList['extra_fare_breakup']['toll_charges']['items'][0]['amount']  = $partnerRequest->tollCharges;
                $dataList['extra_fare_breakup']['toll_charges']['items'][0]['receipt'] = null;

                $dataList['extra_fare_breakup']['state_tax']['amount']              = $partnerRequest->stateTaxCharges;
                $dataList['extra_fare_breakup']['state_tax']['items'][0]['name']    = 'State tax';
                $dataList['extra_fare_breakup']['state_tax']['items'][0]['amount']  = $partnerRequest->stateTaxCharges;
                $dataList['extra_fare_breakup']['state_tax']['items'][0]['receipt'] = null;

                $dataList['extra_fare_breakup']['airport_entry_fee']['amount']              = $partnerRequest->airportEntryFee;
                $dataList['extra_fare_breakup']['airport_entry_fee']['items'][0]['name']    = 'Airport entry fee';
                $dataList['extra_fare_breakup']['airport_entry_fee']['items'][0]['amount']  = $partnerRequest->airportEntryFee;
                $dataList['extra_fare_breakup']['airport_entry_fee']['items'][0]['receipt'] = null;

                $dataList['extra_fare_breakup']['parking_charges']['amount']              = $partnerRequest->parkingCharges;
                $dataList['extra_fare_breakup']['parking_charges']['items'][0]['name']    = 'Parking';
                $dataList['extra_fare_breakup']['parking_charges']['items'][0]['amount']  = $partnerRequest->parkingCharges;
                $dataList['extra_fare_breakup']['parking_charges']['items'][0]['receipt'] = null;

                $dataList['extra_fare_breakup']['waiting_charges']['amount']              = $partnerRequest->waitingCharge;
                $dataList['extra_fare_breakup']['waiting_charges']['items'][0]['name']    = 'Waiting Charge';
                $dataList['extra_fare_breakup']['waiting_charges']['items'][0]['amount']  = $partnerRequest->waitingCharge;
                ;
                $dataList['extra_fare_breakup']['waiting_charges']['items'][0]['receipt'] = null;

                $dataList['extra_fare_breakup']['miscellaneous']['amount']              = 0;
                $dataList['extra_fare_breakup']['miscellaneous']['items'][0]['name']    = null;
                $dataList['extra_fare_breakup']['miscellaneous']['items'][0]['amount']  = 0;
                $dataList['extra_fare_breakup']['miscellaneous']['items'][0]['receipt'] = null;

//				$dataList['extra_travelled_km']		 = $partnerRequest->extrakm;
//				$dataList['extra_travelled_fare']	 = $partnerRequest->extrakmCharge;
//				$dataList['total_travelled_fare']	 = $partnerRequest->totalAmount;
//				$dataList['night_charges']			 = 0;

                break;
//            case"tripEnd":
//				$dataList['apiUrl']					 = '/api/partner/v1/track/' . $partnerRequest->mmtBookingId . '/alight';
//				$dataList['booking_id']				 = $partnerRequest->mmtBookingId;
//				$dataList['latitude']				 = $partnerRequest->lattitude;
//				$dataList['longitude']				 = $partnerRequest->longitude;
//				$dataList['timestamp']				 = strtotime($partnerRequest->tripStartTime) * 1000;
//				$dataList['device_id']				 = "98:0C:A5:BB:CC:17";
//				$dataList['extra_travelled_km']		 = $partnerRequest->extrakm;
//				$dataList['extra_travelled_fare']	 = $partnerRequest->extrakmCharge;
//				$dataList['total_travelled_fare']	 = $partnerRequest->totalAmount;
//				$dataList['night_charges']			 = 0;
//
//				break;
            case "leftforpickup":
                $dataList['apiUrl']     = '/api/partner/v1/track/' . $partnerRequest->mmtBookingId . '/start';
                $dataList['booking_id'] = $partnerRequest->mmtBookingId;
                $dataList['latitude']   = $partnerRequest->lattitude;
                $dataList['longitude']  = $partnerRequest->longitude;
                $dataList['timestamp']  = strtotime($partnerRequest->tripStartTime) * 1000;
                $dataList['device_id']  = "98:0C:A5:BB:CC:17";

                break;
            case "vendorTripRequest":
                $dataList['type']                           = $partnerRequest->type;
                $dataList['booking_id']                     = $partnerRequest->mmtBookingId;
                $dataList['vendor_booking_id']              = $partnerRequest->bkgId;
                $dataList['odometer_start_reading']         = $partnerRequest->odometer_start_reading . "";
                $dataList['trip_start_timestamp']           = $partnerRequest->tripStartTime;
                break;
            case "driverDetail":
                $chauffeurId                                = $partnerRequest->drvId;
                $vehicleId                                  = $partnerRequest->vhcId;
                $dataList['apiUrl']                         = '/api/partner/v1/dispatch/' . $partnerRequest->mmtBookingId . '/assign';
                $dataList['booking_id']                     = $partnerRequest->mmtBookingId;
                $dataList['chauffeur']['id']                = Yii::app()->shortHash->hash($chauffeurId);
                //$dataList['chauffeur']['id']				 = base64_encode($chauffeurId);
                //Yii::app()->getSecurityManager()->encrypt($chauffeurId, GoMmt::$SALT);
                $dataList['chauffeur']['name']              = $partnerRequest->driverName;
                $dataList['chauffeur']['mobile_number']     = $partnerRequest->driverMobile;
                $dataList['vehicle']['id']                  = Yii::app()->shortHash->hash($vehicleId);
                //$dataList['vehicle']['id']				 = base64_encode($vehicleId);
                //Yii::app()->getSecurityManager()->encrypt($vehicleId, GoMmt::$SALT);
                $dataList['vehicle']['name']                = $partnerRequest->cabName;
                $dataList['vehicle']['registration_number'] = $partnerRequest->cabNo;
                $dataList['vehicle']['vehicle_type']        = $partnerRequest->vctType;
                break;
            case "reAssign":
                $chauffeurId                                = $partnerRequest->drvId;
                $vehicleId                                  = $partnerRequest->vhcId;
                $dataList['apiUrl']                         = '/api/partner/v1/dispatch/' . $partnerRequest->mmtBookingId . '/reassign';
                $dataList['booking_id']                     = $partnerRequest->mmtBookingId;
                //$dataList['chauffeur']['id']				 = base64_encode($chauffeurId);
                $dataList['chauffeur']['id']                = Yii::app()->shortHash->hash($chauffeurId);
                $dataList['chauffeur']['name']              = $partnerRequest->driverName;
                $dataList['chauffeur']['mobile_number']     = $partnerRequest->driverMobile;
                $dataList['chauffeur']['image']             = "null";
                //$dataList['vehicle']['id']				 = base64_encode($vehicleId);
                $dataList['vehicle']['id']                  = Yii::app()->shortHash->hash($vehicleId);
                $dataList['vehicle']['name']                = $partnerRequest->cabName;
                $dataList['vehicle']['color']               = "null";
                $dataList['vehicle']['registration_number'] = $partnerRequest->cabNo;
                $dataList['vehicle']['vehicle_type']        = $partnerRequest->vctType;
                break;
            case "tripCancelled":
                $dataList['apiUrl']                         = '/api/partner/v1/dispatch/' . $partnerRequest->mmtBookingId . '/detach';
                $dataList['reason']                         = $partnerRequest->bookingStatus;
                break;
            case "noshow":
                $dataList['apiUrl']                         = '/api/partner/v1/track/' . $partnerRequest->mmtBookingId . '/not_boarded';
                $dataList['booking_id']                     = $partnerRequest->mmtBookingId;
                $dataList['latitude']                       = $partnerRequest->lattitude;
                $dataList['longitude']                      = $partnerRequest->longitude;
                $dataList['timestamp']                      = strtotime($partnerRequest->tripStartTime) * 1000;
                $dataList['reason']                         = "Customer was no-show";
                $dataList['device_id']                      = "98:0C:A5:BB:CC:17";
                break;

            case"arrived":
                $dataList['apiUrl']     = '/api/partner/v1/track/' . $partnerRequest->mmtBookingId . '/arrived';
                $dataList['booking_id'] = $partnerRequest->mmtBookingId;
                $dataList['latitude']   = $partnerRequest->lattitude;
                $dataList['longitude']  = $partnerRequest->longitude;
                $dataList['timestamp']  = strtotime($partnerRequest->tripStartTime) * 1000;
                $dataList['device_id']  = "98:0C:A5:BB:CC:17";
                break;

            case"customerDetails":
                $dataList['apiUrl']     = '/api/partner/v1/dispatch/' . $partnerRequest->mmtBookingId . '/customer_details';
                $dataList['booking_id'] = $partnerRequest->mmtBookingId;
                $dataList['methodType'] = "GET";
                break;

            case"updateLastLocation":
                $dataList['apiUrl']     = '/api/partner/v1/track/' . $partnerRequest->mmtBookingId . '/update';
                $dataList['booking_id'] = $partnerRequest->mmtBookingId;
                $dataList['latitude']   = $partnerRequest->lattitude;
                $dataList['longitude']  = $partnerRequest->longitude;
                $dataList['timestamp']  = strtotime($partnerRequest->timestamp) * 1000;
                $dataList['device_id']  = "98:0C:A5:BB:CC:17";
                break;

            case"bookingAccepted":
                $dataList['apiUrl']                   = '/api/partner/v1/dispatch/' . $partnerRequest->mmtBookingId . '/accept';
                $dataList['partner_reference_number'] = $partnerRequest->partner_reference_number;
                $dataList['order_reference_number']   = $partnerRequest->order_reference_number;
                $dataList['communication_type']       = $partnerRequest->communication_type;
                $dataList['verification_type']        = $partnerRequest->verification_type;
                $dataList['verification_code']        = $partnerRequest->verification_code;
                break;

            case"paymentDetails":
                $dataList['apiUrl']     = '/api/partner/v1/dispatch/' . $partnerRequest->mmtBookingId . '/details';
                $dataList['booking_id'] = $partnerRequest->mmtBookingId;
                $dataList['methodType'] = "GET";
                break;

            case"addPayment":
                $dataList['apiUrl']                 = '/api/partner/v1/dispatch/' . $partnerRequest->mmtBookingId . '/add_payment';
                $dataList['order_reference_number'] = $partnerRequest->order_reference_number;
                $dataList['amount_paid']            = $partnerRequest->amount_paid;
                break;

            default:
                break;
        }
        return $dataList;
    }

    public function parseResponse($responseParamList, $partnerRequest)
    {

        $responseList    = json_decode($responseParamList, true);
        $partnerResponse = new PartnerResponse();

        $partnerResponse->status = 2;

        if ($responseList['response'] == null)
        {
            goto end;
        }

        #if ($GLOBALS['MMT_CURL_HTTPCODE'] == 200)
        #{
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
        else if ($partnerRequest->type == 'tripCancelled' && ($responseList['error'] == null || $responseList['error'] == ''))
        {
            $partnerResponse->status = 1;
        }
        else if ($partnerRequest->type == 'driverDetail' && ($responseList['error'] == null || $responseList['error'] == ''))
        {
            $partnerResponse->status = 1;
        }
        else if ($partnerRequest->type == 'reAssign' && ($responseList['error'] == null || $responseList['error'] == ''))
        {
            $partnerResponse->status = 1;
        }
        else if ($partnerRequest->type == 'tripStart' && ($responseList['error'] == null || $responseList['error'] == ''))
        {
            $partnerResponse->status = 1;
        }
        else if ($partnerRequest->type == 'tripEnd' && ($responseList['error'] == null || $responseList['error'] == ''))
        {
            $partnerResponse->status = 1;
        }
        else if ($partnerRequest->type == 'leftforpickup' && ($responseList['error'] == null || $responseList['error'] == ''))
        {
            $partnerResponse->status = 1;
        }
        else if ($partnerRequest->type == 'noshow' && ($responseList['error'] == null || $responseList['error'] == ''))
        {
            $partnerResponse->status = 1;
        }
        else if ($partnerRequest->type == 'arrived' && ($responseList['error'] == null || $responseList['error'] == ''))
        {
            $partnerResponse->status = 1;
        }
        else if ($partnerRequest->type == 'customerDetails' && ($responseList['error'] == null || $responseList['error'] == ''))
        {
            $partnerResponse->status = 1;
        }
        else if ($partnerRequest->type == 'updateLastLocation' && ($responseList['error'] == null || $responseList['error'] == ''))
        {
            $partnerResponse->status = 1;
        }
        else if ($partnerRequest->type == 'bookingAccepted' && ($responseList['error'] == null || $responseList['error'] == ''))
        {
            $partnerResponse->status = 1;
        }
        else if ($partnerRequest->type == 'paymentDetails' && ($responseList['error'] == null || $responseList['error'] == ''))
        {
            $partnerResponse->status = 1;
        }
        else if ($partnerRequest->type == 'addPayment' && ($responseList['error'] == null || $responseList['error'] == ''))
        {
            $partnerResponse->status = 1;
        }

        end:
        $partnerResponse->response = json_encode($responseList);

        return $partnerResponse;
    }

    public function callApi($arrData)
    {


        $arrCredentials = GoMmt::getCredential();
        $arrHeaders     = GoMmt::getCurlHeaders();

        $apiURL = $arrCredentials['apiUrl'];
        if (isset($arrData['apiUrl']))
        {
            $apiURL = $apiURL . $arrData['apiUrl'];
            unset($arrData['apiUrl']);
        }
        if (isset($arrData['methodType']))
        {
            $methodType = $arrData['methodType'];
        }
        else
        {
            $methodType = "POST";
        }

        $ch       = curl_init($apiURL);
        $jsonData = json_encode($arrData);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $methodType);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeaders);

        $jsonResponse                 = curl_exec($ch);
        $GLOBALS['MMT_CURL_ERRNO']    = curl_errno($ch);
        $GLOBALS['MMT_CURL_HTTPCODE'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return $jsonResponse;
    }


    public static function getCredential()
    {
        $arrCredentials = array();

        $arrCredentials['production']['apiUrl'] = 'https://dispatchtracking.rydepro.in';
        $arrCredentials['production']['client'] = 'd01fdde9e21f7bb3d359dc7d9cab3760aa06a834ae86f05894dbfebd7934bbdf';
        $arrCredentials['production']['secret'] = '62c01376687e19e6c81561611fa925d11cd08813ca78e99c7129f979b61be9ee';

        $arrCredentials['sandbox']['apiUrl'] = 'https://pp2dispatchtracking.rydepro.in';
        $arrCredentials['sandbox']['client'] = 'fooGozo';
        $arrCredentials['sandbox']['secret'] = 'barGozo';

        $arrCredentials['development2']['apiUrl'] = 'https://pp2dispatchtracking.rydepro.in';
        $arrCredentials['development2']['client'] = 'fooGozo';
        $arrCredentials['development2']['secret'] = 'barGozo';

        $env = APPLICATION_ENV;
        if (isset($arrCredentials[$env]))
        {
            return $arrCredentials[$env];
        }
        return false;
    }

    public static function getCurlHeaders($version = 'v1')
    {
        $arrCredentials = GoMmt::getCredential();

        // For Old Version
        $arrHeaders = array('Content-Type: application/json', 'auth-id: ' . $arrCredentials['client'], 'auth-token: ' . $arrCredentials['secret']);

        // For New Version
        if ($version == 'v1')
        {
            $arrHeaders = array('Content-Type: application/json', 'Authorization:Basic ' . base64_encode($arrCredentials['client'] . ':' . $arrCredentials['secret']));
        }

        return $arrHeaders;
    }

    public static function getFixedRate($cityId, $tripType, $cabType)
    {
        $data        = self::getFixedRateList();
        $fixedKmRate = false;
        if (isset($data[$cityId][$tripType][$cabType]))
        {
            $fixedKmRate = $data[$cityId][$tripType][$cabType];
        }
        return $fixedKmRate;
    }

    public static function getFixedRateList()
    {
        $data = [
            "30366" => [
            //		"1"	 => ["1" => "", "2" => "", "3" => ""],
            //		"2"	 => ["1" => "", "2" => "", "3" => ""],
            ],
            "30384" => [
            //		"1"	 => ["1" => "", "2" => "", "3" => ""],
            //		"2"	 => ["1" => "", "2" => "", "3" => ""],
            ],
        ];

        return $data;
    }

    /**
     * @var Booking $model
     * @var $cabId
     * @var $processCnt
     * @return array
     */
    public static function processQuote($model, $cabId = 0, $processCnt = 0, $isAllowed = false)
    {

        if ($cabId <= 0)
        {
             $cabId = [1, 2, 3, 5, 6, 14, 15, 16, 72, 73, 74, 75];
			#$cabId = [1, 2, 3, 5, 6, 72, 73, 74, 75];
            if (in_array('CO', $model->search_tags))
            {
				$cabId = [14, 15, 16];
				$zoneIds = ZoneCities::getZonesByCity($model->bkg_from_city_id);
				$cabIds  = SvcClassVhcCat::getCabIds($zoneIds);
				if(!empty($cabIds))
				{
					$cabId = array_unique($cabIds);
				}
				else
				{
					throw new Exception("Sold out", ReturnSet::ERROR_NO_RECORDS_FOUND);
				}
            }
        }

        $quotData = Quote::populateFromModel($model, $cabId, false, true,$isAllowed);
        foreach ($cabId as $cabs)
        {
            if (!$quotData[$cabs]->success)
            {
                unset($quotData[$cabs]);
            }
        }
        if (empty($quotData) && $processCnt == 0)
        {
            $processCnt++;
            $quotData = self::processQuote($model, $cabId, $processCnt,$isAllowed);
        }
        if (empty($quotData) && $processCnt > 1)
        {
            throw new Exception("No cabs available for this route", ReturnSet::ERROR_NO_RECORDS_FOUND);
        }

        $freedomSale = self::calculateDiscount($model, $quotData);

        return $quotData;
    }

    /**
     * @var Booking $model
     * @var $cabId
     * @return array
     */
    public static function processHold($model, $cabId,$isAllowed = false)
    {
        $quotHoldData = self::processQuote($model, $cabId,0,$isAllowed);

        /** @var Quote $quote */
        foreach ($quotHoldData as $quote)
        {
            $quote->routeRates->baseAmount += $quote->routeRates->discount;
            $quote->routeRates->calculateTotal();
        }

        return $quotHoldData;
    }

    /** @var Booking $model */

    /** @param array $quotData */
    public static function calculateDiscount($model, $quotData)
    {
        $validateRoute = Route::getMmtC1RouteByCity($model->bookingRoutes[0]->brt_from_city_id, $model->bookingRoutes[0]->brt_to_city_id);
        $validateTime  = self::validateDiscountDuration($model);

        if ($validateRoute && $validateTime == true)
        {
            /** @var Quote $quote */
            foreach ($quotData as $quote)
            {
                $baseAmtBeforeDisc           = $quote->routeRates->baseAmount;
                $discount                    = round($baseAmtBeforeDisc * 0.1);
                $quote->routeRates->discount = $discount;
            }
        }

        return $quotData;
    }

    /** @var Booking $model */
    public static function validateDiscountDuration($model)
    {
        $duration   = false;
        $createDate = Filter::getDBDateTime();
        $pickupDate = $model->bkg_pickup_date;
        if (($createDate > '2021-07-28 00:00:00' && $createDate < '2021-08-15 23:59:59') && ($pickupDate > '2021-07-28 00:00:00' && $pickupDate < '2021-08-20 23:59:59'))
        {
            $duration = true;
        }

        return $duration;
    }


	public static function getUnfulfilledPenaltySlab($confirmDate, $pickupDate)
	{
		$slab	 = false;
		$arr	 = [];
		$arr[0]	 = ['minPickupMinute' => false, 'minutesBeforePickup' => 720, 'penaltyPercentage' => 25, 'maxPenaltyAmt' => 1000, 'penaltyPostPickup' => 50, 'maxPenaltyAmtPostPickup' => 2000];
		$arr[1]	 = ['minPickupMinute' => 720, 'minutesBeforePickup' => 360, 'penaltyPercentage' => 25, 'maxPenaltyAmt' => 1000, 'penaltyPostPickup' => 50, 'maxPenaltyAmtPostPickup' => 2000];
		$arr[2]	 = ['minPickupMinute' => 360, 'minutesBeforePickup' => 120, 'penaltyPercentage' => 25, 'maxPenaltyAmt' => 1000, 'penaltyPostPickup' => 50, 'maxPenaltyAmtPostPickup' => 2000];

		$diffMin = Filter::getTimeDiff($pickupDate, $confirmDate);

		foreach ($arr as $arrSlab)
		{
			if ($arrSlab['minPickupMinute'] == false || $arrSlab['minPickupMinute'] >= $diffMin)
			{
				$slab = $arrSlab;
			}
		}



		return $slab;
	}

	public static function getUnfulfilledPenalty($bkgModel)
	{
		// Booking
		$totalAmount = $bkgModel->bkgInvoice->bkg_total_amount;
		$pickupDate	 = $bkgModel->bkg_pickup_date;
		$confirmDate = ($bkgModel->bkgTrail->bkg_confirm_datetime == null)?$bkgModel->bkg_create_date:$bkgModel->bkgTrail->bkg_confirm_datetime;

		// Getting Slab
		$slab = self::getUnfulfilledPenaltySlab($confirmDate, $pickupDate);

		// Calculate Time
		$minutes = $slab['minutesBeforePickup'];
		$date	 = date("Y-m-d H:i:s", strtotime("-{$minutes} minutes", strtotime($pickupDate)));

		// Calculate Amount
		$amount = min(round(($totalAmount * $slab['penaltyPercentage']) / 100), $slab['maxPenaltyAmt']);

		// Calculate PostPickup Amount
		$amountPostPickup = min(round(($totalAmount * $slab['penaltyPostPickup']) / 100), $slab['maxPenaltyAmtPostPickup']);

		// Penalty Array
		$arrPenalty		 = [];
		$arrPenalty[0]	 = ['amount' => 0, 'startDate' => $confirmDate, 'endDate' => $date];
		$arrPenalty[1]	 = ['amount' => $amount, 'startDate' => $date, 'endDate' => $pickupDate];
		$arrPenalty[2]	 = ['amount' => $amountPostPickup, 'startDate' => $pickupDate, 'endDate' => false];

		return $arrPenalty;
	}

	public static function getDetachmentPenaltySlab($confirmDate, $pickupDate)
	{
		$slab	 = false;
		$arr	 = [];
		$arr[0]	 = ['minPickupMinute' => false, 'minutesBeforePickup' => 120, 'penaltyPercentage' => 10];
		$arr[1]	 = ['minPickupMinute' => 360, 'minutesBeforePickup' => 60, 'penaltyPercentage' => 10];

		$diffMin = Filter::getTimeDiff($pickupDate, $confirmDate);

		foreach ($arr as $arrSlab)
		{
			if ($arrSlab['minPickupMinute'] == false || $arrSlab['minPickupMinute'] >= $diffMin)
			{
				$slab = $arrSlab;
			}
		}

		return $slab;
	}

	public static function getDetachmentPenalty($bkgModel)
	{
		// Booking
		$totalAmount = $bkgModel->bkgInvoice->bkg_total_amount;
		$pickupDate	 = $bkgModel->bkg_pickup_date;
		$confirmDate = ($bkgModel->bkgTrail->bkg_confirm_datetime == null)?$bkgModel->bkg_create_date:$bkgModel->bkgTrail->bkg_confirm_datetime;

		// Getting Slab
		$slab = self::getDetachmentPenaltySlab($confirmDate, $pickupDate);

		// Calculate Time
		$minutes = $slab['minutesBeforePickup'];
		$date	 = date("Y-m-d H:i:s", strtotime("-{$minutes} minutes", strtotime($pickupDate)));

		// Calculate Amount
		$amount = round(($totalAmount * $slab['penaltyPercentage']) / 100);

		// Penalty Array
		$arrPenalty		 = [];
		$arrPenalty[0]	 = ['amount' => 0, 'startDate' => $confirmDate, 'endDate' => $date];
		$arrPenalty[1]	 = ['amount' => $amount, 'startDate' => $date, 'endDate' => $pickupDate];

		return $arrPenalty;
	}

	public static function getPenalty($bkgModel)
	{
		// Checking If Driver Details Pushed
		$aatId = AgentApiTracking::getLastEventByBooking($bkgModel->bkg_id, 9);

		if ($aatId > 0)
		{	
			$arrPenalty = self::getUnfulfilledPenalty($bkgModel);
		}
		else
		{
			$arrPenalty = self::getDetachmentPenalty($bkgModel);
		}

		return $arrPenalty;
	}

	/**
	 * 
	 * @var Booking $bkgModel
	 * @return boolean
	 */
	public static function isCancelChargesApplicable($bkgModel)
	{
		$cancelCharge = true;
		$aatId = AgentApiTracking::getLastEventByBooking($bkgModel->bkg_id, 9);
		if ($aatId == false)
		{
			$pickupDate	 = $bkgModel->bkg_pickup_date;
			$confirmDate = ($bkgModel->bkgTrail->bkg_confirm_datetime == null)?$bkgModel->bkg_create_date:$bkgModel->bkgTrail->bkg_confirm_datetime;
			$slab = self::getDetachmentPenaltySlab($confirmDate, $pickupDate);
			$minutes = $slab['minutesBeforePickup'];
			$diffMin = Filter::getTimeDiff($pickupDate);
			if($diffMin <= $minutes)
			{
				$cancelCharge = false;
			}
		}

		return $cancelCharge;
	}

	/**
	 * 
	 * @param Booking $model
	 * @return boolean
	 */
	public static function isZeroPaymentAllowed($model)
	{
		$result				 = false;
		$allowedAirportList	 = CJSON::decode(Config::get("mmt.isAllowed.airport"));
		$isfromAirportCity		 = in_array($model->bkg_from_city_id, $allowedAirportList);
		$isToAirportCity		 = in_array($model->bkg_to_city_id, $allowedAirportList);

		if ($model->bkg_booking_type == 12 && ($isfromAirportCity == true || $isToAirportCity == true))
		{
			$result = true;
		}
		return $result;
	}

	/**
	 * 
	 * @param type $bkgFromCity
	 * @param type $partnerId
	 * @return boolean
	 */
	public static function isAllowedCity($bkgFromCity, $partnerId)
	{
		$result			 = false;
		$allowedCityList = CJSON::decode(Config::get("isAllowed.cities"));
		$isFromCity		 = in_array($bkgFromCity, $allowedCityList);

		if ($isFromCity == true && $partnerId == 18190)
		{
			$result = true;
		}
		return $result;
	}
}
