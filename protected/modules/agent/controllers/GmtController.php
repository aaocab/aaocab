<?php

use Booking;

include_once(dirname(__FILE__) . '/BaseController.php');

class GmtController extends BaseController
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout       = 'main';
    public $email_receipient, $useUserReturnUrl;
    public $current_page = '';

    //public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            array(
                'application.filters.HttpsFilter + create',
                'bypass' => false),
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
            array(
                'RestfullYii.filters.ERestFilter +
                REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'
            ),
        );
    }

    public function actions()
    {
        return array(
            'REST.' => 'RestfullYii.actions.ERestActionProvider',
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array(),
                'users'   => array('@'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array(
                    'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
                'users'   => array('*'),
            ),
            ['allow',
                'actions' => [],
                'users'   => ['@']
            ],
//            array('allow', // allow authenticated user to perform 'create' and 'update' actions
//                'actions' => array(
//                    'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
//                'users' => array('*'),
//            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function restEvents()
    {

        $this->onRest('req.cors.access.control.allow.methods', function () {
            return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
        });

        $this->onRest('post.filter.req.auth.user', function ($validation) {
            $pos = false;
            $arr = $this->getURIAndHTTPVerb();
            $ri  = array('/review', '/getQuote');
            foreach ($ri as $value)
            {
                if (strpos($arr[0], $value))
                {
                    $pos = true;
                }
            }
            //  return false;
            return $validation ? $validation : ($pos != false);
        });

        $this->onRest('req.get.details.render', function () {
            return $this->details();
        });

		$this->onRest('req.get.tripDetails.render', function () {
            return $this->tripDetails();
        });

        $this->onRest('req.post.request.render', function () {
            $data = Yii::app()->request->getParam('data');

            $requestType = Yii::app()->request->getParam('requestType');
            if ($data == "")
            {
                $data = Yii::app()->request->rawBody;
            }
            $data = CJSON::decode($data, true);

            if ($data['requestType'] == 'SEARCH' || $requestType == 'SEARCH')// new service for MMT
            {
                return $this->emitRest("req.post.quote.render");
            }
            if ($data['requestType'] == 'SEARCH1' || $requestType == 'SEARCH1')// new service for MMT
            {
                return $this->emitRest("req.post.quote1.render");
            }
            if ($data['requestType'] == 'CREATE' || $requestType == 'CREATE')
            {
                return $this->emitRest("req.post.hold.render");
            }
            if (data['requestType'] == 'CONFIRM' || $requestType == 'CONFIRM')
            {
                return $this->emitRest("req.post.confirm.render");
            }
            if ($data['requestType'] == 'CANCEL' || $requestType == 'CANCEL')
            {
                return $this->emitRest("req.post.cancel.render");
            }
            if ($data['requestType'] == 'UPDATEPASSENGER' || $requestType == 'UPDATEPASSENGER')
            {
                return $this->emitRest("req.post.updatePassengerDetails.render");
            }
            if ($data['requestType'] == 'REVIEW' || $requestType == 'REVIEW')
            {
                return $this->emitRest("req.post.review.render");
            }
            if ($data['requestType'] == 'REVBKGSEARCH' || $requestType == 'REVBKGSEARCH')
            {
                return $this->emitRest("req.post.reverseBooking.render");
            }
            if ($data['requestType'] == 'REVBKGUNAVAILABLE' || $requestType == 'REVBKGUNAVAILABLE')
            {
                return $this->emitRest("req.post.unavailable.render");
            }
        });

        $this->onRest('req.post.hold.render', function () {
            return $this->hold();
        });
        $this->onRest('req.post.confirm.render', function () {
            return $this->confirm();
        });
        $this->onRest('req.post.quote.render', function () {
            return $this->getQuote();
        });
        $this->onRest('req.post.cancel.render', function () {
            return $this->cancel();
        });
        $this->onRest('req.post.updatePassengerDetails.render', function () {
            return $this->updatePassengerDetails();
        });
        $this->onRest('req.post.review.render', function () {
            return $this->review();
        });
        $this->onRest('req.post.reverseBooking.render', function () {
            return $this->reverseBooking();
        });
        $this->onRest('req.post.unavailable.render', function () {
            return $this->unavailable();
        });
    }

    public function getQuote()
    {
        $data       = Yii::app()->request->rawBody;
        $jsonMapper = new JsonMapper();
        $jsonObj    = CJSON::decode($data, false);

        // Response
        $response = new Stub\mmt\Response();
        try
        {
            /** @var \Stub\mmt\QuoteRequest $obj */
            $obj = $jsonMapper->map($jsonObj, new Stub\mmt\QuoteRequest());

            /** @var Booking $model */
            $model    = $obj->getModel();

            // AgentApiTracking
            $aatType  = AgentApiTracking::TYPE_GET_QUOTE;
            $aatModel = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());
            if (!$model->validate())
            {
                throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
            }
			
			// Skip Threshold Validation For CO/B2B/Not FF
			if (in_array('CO', $model->search_tags) || in_array('B2B', $model->search_tags) || !in_array('FF', $model->search_tags))
			{
				goto skipThresholdValidation;
			}
			
			// Search Limit Validation
			$isMMTApiBlockedEnable = Config::get("isBlockedMMTApiEnabled");
			if ($isMMTApiBlockedEnable && in_array($model->bkg_booking_type, array(1, 2, 3)))
			{
				$isAllowed = MmtDataPickup::isAllowed($model->bkg_from_city_id, $model->bkg_to_city_id, $model->bkg_pickup_date, $model->bkg_booking_type);
				if ($isAllowed == 0)
				{
					//throw new \Exception('Search request limit exceeded', \ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
					$model->additionalMarkup = true;
				}
			}

			skipThresholdValidation:
			// ProcessQuote
            $model->platform = Quote::Platform_Agent;
            $quotData        = GoMmt::processQuote($model, $cabId           = 0, $processCnt      = 0, $isAllowed       = true);

            // Response
            $quoteResponse = new Stub\mmt\QuoteResponse();
            $quoteResponse->setQuoteData($quotData, $jsonObj);
            $data          = Filter::removeNull($quoteResponse);
            if (empty($quoteResponse->car_types))
            {
                Logger::trace("Quote Data:  " . json_encode($quotData));
                Logger::trace("Quote Response: " . json_encode($data));
            }
            $response->setData($data);
            Logger::profile("Success");
        }
        catch (Exception $e)
        {
            $ret = ReturnSet::setException($e);
            $response->setError($ret);
        }
		
        // Update AgentApiTracking
        if (!$aatModel)
        {
            $aatType  = AgentApiTracking::TYPE_GET_QUOTE;
            $aatModel = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());
            $status   = 2;
        }
        else
        {
            $status = 1;
        }
        $time       = Filter::getExecutionTime();
        $error_type = $response->code;
        $error_msg  = $response->error;

        if ($response->error != NULL)
        {
            $response->code = GoMmt::errorSearchMsgList($response->error);
			if($response->code == 'Internal server error')
			{
				$response->error = $response->code;
			}
        }

		if($error_type != 42000)
		{
			$aatModel->updateResponse($response, $model->bkg_id, $status, $error_type, $response->error, $time);
		}
		else
		{
			$serverError = 'Internal server error';
			$response->code = $serverError;
			$response->error = $serverError;
		}
        Logger::info("Response: " . json_encode($response));
        return $this->renderJSON([
                    'type' => 'raw',
                    'data' => $response
        ]);
    }

    public function hold()
    {
        $returnSet  = new ReturnSet();
        $data       = Yii::app()->request->rawBody;
        Logger::info("data: " . $data);
        $jsonMapper = new JsonMapper();
        $jsonObj    = CJSON::decode($data, false);
        // Response
        $response   = new Stub\mmt\Response();
        try
        {
            /** @var \Stub\mmt\HoldRequest $obj */

			$searchCount = AgentApiTracking::getCountBySearchId($jsonObj->search_id);
			if($searchCount > 20)
			{
				throw new \Exception('Too many hold request received from this search id: '. $jsonObj->search_id,  \ReturnSet::ERROR_INVALID_DATA);
			}
			
            $obj = $jsonMapper->map($jsonObj, new Stub\mmt\HoldRequest());
			$pickupTime = $obj->start_time;

            /** @var Booking $model */
            $model = $obj->getModel(null, $jsonObj);
            $aatType  = AgentApiTracking::TYPE_HOLD_BOOKING;
            $aatModel = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());
            if (!$model->validate())
            {
                throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
            }
            $cabId           = $model->bkg_vehicle_type_id;
            $mmtAmount       = $model->bkgInvoice->bkg_total_amount;
            //$model->platform = Quote::Platform_Agent;
            $quotData        = GoMmt::processHold($model, $cabId, $isAllowed       = true);

			if(!$quotData)
			{
				throw new \Exception('Sold out', \ReturnSet::ERROR_INVALID_DATA);
			}

            /** @var Quote $quote */
            $quote      = $quotData[$cabId];
            $routeRates = $quote->routeRates;

            $discount             = $quote->routeRates->discount;
            $routeRates->discount = 0;

			$serviceTaxRate = $routeRates->getGSTRate($quote->partnerId, $quote->tripType);
			$staxRate = (1 + ($serviceTaxRate / 100));

            $quotedDistance = $quote->routeDistance->quotedDistance;
            $reqDistance    = $obj->distance;
            $fixedKmRate    = GoMmt::getFixedRate($model->bkg_from_city_id, $model->bkg_booking_type, $cabId);
            if ($fixedKmRate > 0)
            {
                $ratePerKM = $fixedKmRate;
            }
            else
            {
                $ratePerKM = ($routeRates->costPerKM * 1.04 * 1.11 * $staxRate);
            }
            if ($reqDistance > $quotedDistance)
            {
                $extraDistance = $reqDistance - $quotedDistance;
                $extraFare     = $extraDistance * $ratePerKM;

                $quote->routeDistance->quotedDistance = $reqDistance;

                $routeRates->baseAmount = round($routeRates->baseAmount + $extraFare);
                $routeRates->calculateTotal();
            }
            $totalAmount = $routeRates->totalAmount;

			Logger::trace("aatId==" . $aatModel->aat_id);
			Logger::trace("hold mmt amount===" . $mmtAmount . "===gozo amount using getquote==" . $totalAmount);

            if ($mmtAmount < ($totalAmount - 2))
            {
                $response = new Stub\mmt\HoldResponse();
                $response->setIncreasePriceMsg($totalAmount);
                $response = Filter::removeNull($response);
                goto end;
            }
            $model->addNew(true);
			
			$model->bkgUserInfo->bkg_user_id = null;
			$model->bkgUserInfo->save();

			if(count($obj->stopovers) > 0)
			{
				$model->bkg_pickup_date = $pickupTime;
				$model->bkg_return_date = $obj->end_time;
				$model->update();
			}
			
            if ($discount > 0)
            {
                $model->bkgInvoice->bkg_discount_amount = $discount;
                $model->bkgInvoice->calculateTotal();
                $model->bkgInvoice->save();
            }
            if ($fixedKmRate > 0)
            {
                $model->bkgInvoice->bkg_rate_per_km_extra = $fixedKmRate;
                $model->bkgInvoice->bkg_rate_per_km       = $fixedKmRate;
                $model->bkgInvoice->save();
            }

            $totalAmount = $model->bkgInvoice->bkg_total_amount;
            $extraAmount = $mmtAmount - $totalAmount;
            if ($extraAmount > 0)
            {
                $model->bkg_trip_distance           = $quote->routeDistance->quotedDistance;
                $model->save();
                $extraAmountGST                     = round($extraAmount - $extraAmount / $staxRate);
                $extraBaseFare                      = $extraAmount - $extraAmountGST;
				/**
				 * new gst logic implementation
				 */
                $model->bkgInvoice->bkg_base_amount += $extraBaseFare;
                $model->bkgInvoice->calculateTotal();
                $model->bkgInvoice->save();
            }
            Logger::profile("Created");
            if ($model->hasErrors())
            {
                throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
            }
            $returnSet->setStatus(true);
            $userInfo = UserInfo::getInstance();

            $response = new Stub\mmt\HoldResponse();
            $response->setData($model);
            $response = Filter::removeNull($response);
            Logger::profile("Success");
        }
        catch (Exception $e)
        {
            Logger::trace("Error *************" . json_encode($e));
            $ret = ReturnSet::setException($e);
            $response->setError($ret);
            Logger::exception($e);
        }
        end:
        if (!$aatModel)
        {
            $aatType  = AgentApiTracking::TYPE_HOLD_BOOKING;
            $aatModel = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());
            $status   = 2;
        }
        else
        {
            $status = 1;
        }
        $time       = Filter::getExecutionTime();
        $error_type = $response->code;
        $error_msg  = $response->error;

        if ($response->error != NULL)
        {
            $response->code = GoMmt::errorBlockMsgList($response->error);
			if($response->code == 'Internal server error')
			{
				$response->error = $response->code;
			}
        }

		if($error_type != 42000)
		{
			$aatModel->updateResponse($response, $model->bkg_id, $status, $error_type, $response->error, $time);
		}
		else
		{
			$serverError = 'Internal server error';
			$response->code = $serverError;
			$response->error = $serverError;
		}

        Logger::info("Response: " . json_encode($response));
        return $this->renderJSON([
                    'type' => 'raw',
                    'data' => $response
        ]);
    }

    public function confirm()
    {
        $returnSet  = new ReturnSet();
        $data       = Yii::app()->request->rawBody;
        Logger::info("data: " . $data);
        $jsonMapper = new JsonMapper();
        $jsonObj    = CJSON::decode($data, false);

        $response = new Stub\mmt\Response();
        try
        {
			$transaction = DBUtil::beginTransaction();
            $obj   = $jsonMapper->map($jsonObj, new Stub\mmt\ConfirmRequest());
            /** @var Booking $model */
            $model = $obj->getModel();
			Logger::trace("booking id == " . $model->bkg_id);

            if (!$model)
            {
                throw new \Exception('Invalid request data', \ReturnSet::ERROR_INVALID_DATA);
            }
            $cnt = Booking::model()->checkDuplicateReferenceId($obj->order_reference_number,$model->bkg_agent_id);
            if ($cnt > 0)
            {
                throw new \Exception('Reference Id already exists', \ReturnSet::ERROR_INVALID_DATA);
            }
            // AgentApiTracking
            $aatType  = AgentApiTracking::TYPE_CREATE_BOOKING;
            $aatModel = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());
            $advanceAmount = (($obj->total_fare) - ($obj->amount_to_be_collected)); //Credit added by agent;
			Logger::trace("booking amount == " . $advanceAmount);

            $bkgModel = Booking::model()->findByPk($model->bkg_id);
            $bkgType  = $bkgModel->bkgSvcClassVhcCat->scv_id;
//            if (in_array($bkgType, [1, 2, 3, 5, 6, 72, 73, 74, 75]))
//            {
//                $minPerAdvAmount = round($obj->total_fare * 0.2);
//                $minAdvance      = $minPerAdvAmount - 2;
//            }
//            else
//            {
//                $minPerAdvAmount = round($obj->total_fare * 0.5);
//                $minAdvance      = $minPerAdvAmount - 2;
//            }

//			$minPerAdvAmount = round($obj->total_fare * 0.2);
//            $minAdvance      = $minPerAdvAmount - 2;
			
			$mmtTotalFare = $obj->total_fare;
			$mmtBookingGST = $obj->booking_gst;
			if($mmtBookingGST == 0)
			{
				$mmtFareWithGST = round($mmtTotalFare * 1.05);
				$mmtGST = ($mmtFareWithGST - $mmtTotalFare);
				$getFromStateId = States::model()->getByCityId($model->bkg_from_city_id);
				$getToStateId = States::model()->getByCityId($model->bkg_to_city_id);
				$minAdv = round($mmtFareWithGST * 0.2);
				if($getFromStateId == 92 || $getToStateId == 92)
				{
					$minAdv = round($mmtFareWithGST * 0.18);
				}
				$minPerAdvAmount = $minAdv - $mmtGST;
				
			}
			else
			{
				$minPerAdvAmount = round($obj->total_fare * 0.2);
			}

			$minAdvance = $minPerAdvAmount - 2;

			/* @var $isZeroPaymentAllowed GoMmt */
//			$isZeroPaymentAllowed = GoMmt::isZeroPaymentAllowed($model);
			
//			$isAllowedCity = GoMmt::isAllowedCity($model->bkg_from_city_id);
//			if($isAllowedCity == false)
//			{
				if ($minAdvance > $advanceAmount)
				{
					throw new \Exception('CONFIRM Failed: Advance amount is incorrect', \ReturnSet::ERROR_INVALID_DATA);
				}

				if ($advanceAmount == 0)
				{
					throw new \Exception('CONFIRM Failed: Advance amount is incorrect', \ReturnSet::ERROR_INVALID_DATA);
				}
			//}

            $bkgTotalAmount = $model->bkgInvoice->bkg_total_amount;

            $diffAmt = ($obj->total_fare - $bkgTotalAmount);
			Logger::trace("diffrence amount == " . $diffAmt);
            if (abs($diffAmt) == 1 || abs($diffAmt) == 2)
            {
                $model->bkgInvoice->bkg_base_amount += $diffAmt;
                $model->bkgInvoice->calculateTotal();
                $model->bkgInvoice->save();
            }
            if ($diffAmt < -2)
            {
                throw new \Exception('CONFIRM Failed: Price amount is incorrect', \ReturnSet::ERROR_INVALID_DATA);
            }

            // Validation
            $model->scenario = 'mmtConfirm';
            $error           = $model->validate();
            Logger::profile("Validated");
            if (count($model->getErrors()) > 0)
            {
                throw new Exception(json_encode($model->getErrors()), \ReturnSet::ERROR_VALIDATION);
            }

            // Confirm
            $returnSet = $model->confirm($setReconfirm = true, $sentMessage = false, $bkgId = null, $userInfo = null,$isAllowed=true);
			$success   = $returnSet->isSuccess();
            if (!$success)
            {
                throw new Exception(json_encode($returnSet->getErrors()), $returnSet->getErrorCode());
            }

            $bseRemarks     = "Booking advance amount : " . $advanceAmount;
            $additionalData = json_encode(['AdvanceAmount' => $advanceAmount]);
            BookingScheduleEvent::add($model->bkg_id, BookingScheduleEvent::PARTNER_PENDING_ADVANCE, $bseRemarks, $additionalData);

            // Update Additional Info
			Logger::profile("BookingScheduleEvent::add");
            $model->refresh();
            BookingAddInfo::updataDataMMT($model->bkg_vehicle_type_id, $model);
            Logger::profile("BookingAddInfo::updataDataMMT");

            // Response
            $confresponse   = new Stub\mmt\ConfirmResponse();
            $confresponse->setData($model);
			
            $data           = Filter::removeNull($confresponse);
            $response->setData($data);
			Logger::profile("confresponse->setData");
			DBUtil::commitTransaction($transaction);
        }
        catch (Exception $e)
        {
			DBUtil::rollbackTransaction($transaction);
            $ret = ReturnSet::setException($e);
            $response->setError($ret);
        }

        if (!$aatModel)
        {
            $aatType  = AgentApiTracking::TYPE_CREATE_BOOKING;
            $aatModel = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());
        }
        if ($success)
        {
            $status = 1;
        }
        else
        {
            $status = 2;
        }
        $time       = Filter::getExecutionTime();
        $error_type = $response->code;
        $error_msg  = $response->error;
        if ($response->error != NULL)
        {
            $response->code = GoMmt::errorConfirmMsgList($response->error);
			if($response->code == 'Internal server error')
			{
				$response->error = $response->code;
			}
        }

		if($error_type != 42000)
		{
			$aatModel->updateResponse($response, $model->bkg_id, $status, $error_type, $response->error, $time);
		}
		else
		{
			$serverError = 'Internal server error';
			$response->code = $serverError;
			$response->error = $serverError;
		}
        Logger::info("Response: " . json_encode($response));
		
        return $this->renderJSON([
                    'type' => 'raw',
                    'data' => $response
        ]);
    }

    public function cancel()
    {
        $canResp    = new Stub\mmt\CancelResponse();
        $data       = Yii::app()->request->rawBody;
        Logger::info("data: " . $data);
        $jsonMapper = new JsonMapper();
        $jsonObj    = CJSON::decode($data, false);

        // Response
        $response = new Stub\mmt\Response();

        try
        {
            $obj   = $jsonMapper->map($jsonObj, new Stub\mmt\CancelRequest());
            /** @var Booking $model */
            $model = $obj->getModel();
            if (!$model)
            {
                throw new Exception('Invalid request data', \ReturnSet::ERROR_INVALID_DATA);
            }

            // AgentApiTracking
            $aatType  = AgentApiTracking::TYPE_CANCEL_BOOKING;
            $aatModel = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());

            // Validate
            $model->setscenario('mmtCancel');
            $validated = $model->validate();
            if (!$validated)
            {
                throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
            }

            Logger::beginProfile("cancel mmt booking id : " . $model->bkg_id);

            if ($model->bkgTrack->bkg_arrived_for_pickup != 1)
            {
                $cancellationReason = $obj->cancellation_reason;
                $reasonId           = $obj->reason_id;
                $driverNoShowTime   = date('Y-m-d H:i:s', strtotime("+15 minutes", strtotime($model->bkg_pickup_date)));
                if (date('Y-m-d H:i:s') > $driverNoShowTime)
                {
                    $cancelReason       = CancelReasons::getDriverNoShowId();
                    $cancellationReason = $cancelReason['cnr_reason'];
                    $reasonId           = $cancelReason['cnr_id'];
                }
				if ($reasonId == 22)
                {
                    $scqIds = ServiceCallQueue::countQueueByBkgId($model->bkg_id, 36, 'closed');
                    if ($scqIds == 0 || $scqIds == NULL || $scqIds == '')
                    {
                        /** @var ServiceCallQueue $followupData */
                        $returnfollowupData     = ServiceCallQueue::addNoShowCBR($model->bkg_id, 1);
                        $followupData         = $returnfollowupData->getData();
                        $followupId             = $followupData['followupId']; 
                        $desc                 = "MMT cancellation request received (Driver no show)";
                        $eventid             = BookingLog::NO_SHOW;
                        BookingLog::model()->createLog($model->bkg_id, $desc, UserInfo::getInstance(), $eventid);
						$canResp->success     = false;
						throw new \Exception('Case escalated to gozo team (followup id: ' . $followupId . ")", \ReturnSet::ERROR_INVALID_DATA);
                    }
					
                }
				else
				{
					$cancelStatus	 = 1;
					$success		 = $model->canbooking($model->bkg_id, $cancellationReason, $reasonId, null, $cancelStatus);
					$canResp->success = true;
				}
				
			}
            else
            {
				$canResp->success = false;
                ServiceCallQueue::createByPartner($model);
                throw new \Exception('Driver has arrived, Trip already started', \ReturnSet::ERROR_FAILED);
               
            }

            if (!$success)
            {
                $canResp->success = false;
                throw new \Exception('Error while cancel booking', \ReturnSet::ERROR_FAILED);
            }

            $response->setData($canResp);
        }
        catch (Exception $e)
        {
            $ret = ReturnSet::setException($e);
            $response->setError($ret);

            Logger::exception($e);
        }
        if (!$aatModel)
        {
            $aatType  = AgentApiTracking::TYPE_CANCEL_BOOKING;
            $aatModel = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());
        }
        $time       = Filter::getExecutionTime();
        $error_type = $response->code;
        $error_msg  = $response->error;
        if ($success)
        {
            $status = 1;
        }
        else
        {
            $status = 2;
        }
        $aatModel->updateResponse($response, $model->bkg_id, $status, $error_type, $error_msg, $time);
        Logger::info("Response: " . json_encode($response));
        return $this->renderJSON([
                    'type' => 'raw',
                    'data' => $response
        ]);
    }

    public function details()
    {
        $agentOrderRefNo = Yii::app()->request->getParam('order_reference_number');
        $bookingId       = Yii::app()->request->getParam('partner_reference_number');
        Logger::info("params: " . json_encode($_REQUEST));

        if ($agentOrderRefNo && $agentOrderRefNo != '')
        {
            $jsonObj = '{"order_reference_number": "' . $agentOrderRefNo . '"}';
            $model   = Booking::findByOrderNo($agentOrderRefNo);
        }
        if (!$model && $bookingId && $bookingId != '')
        {
            $jsonObj = '{"order_reference_number": "' . $bookingId . '"}';
            $model   = Booking::model()->findByPk($bookingId);
        }

        $response = new Stub\mmt\BookingDetailsResponse();
        try
        {
            if (!$model)
            {
                throw new \Exception('Missing Booking', \ReturnSet::ERROR_FAILED);
            }
            $aatType  = AgentApiTracking::TYPE_GET_DETAILS;
            $aatModel = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());

            $response   = new Stub\mmt\BookingDetailsResponse();
            $response->setData($model);
            $response   = Filter::removeNull($response);
            $time       = Filter::getExecutionTime();
            $error_type = $response->code;
            $error_msg  = $response->error;
            if ($model)
            {
                $status = 1;
            }
            else
            {
                $status = 2;
            }
            $aatModel->updateResponse($response, $model->bkg_id, $status, $error_type, $error_msg, $time);
        }
        catch (Exception $ex)
        {

            $ret      = ReturnSet::setException($ex);
            $response->setMissingData();
            $response = Filter::removeNull($response);
        }

        Logger::info("Response: " . json_encode($response));

        return $this->renderJSON([
                    'type' => 'raw',
                    'data' => $response
        ]);
    }

    public function updatePassengerDetails()
    {
        $returnSet  = new ReturnSet();
        $data       = Yii::app()->request->rawBody;
        Logger::info("data: " . $data);
        $jsonMapper = new JsonMapper();
        $jsonObj    = CJSON::decode($data, false);

        $response = new Stub\mmt\Response();
        try
        {
			/** @var \Stub\mmt\UpdatePassengerDetailsRequest $obj */
            $obj   = $jsonMapper->map($jsonObj, new Stub\mmt\UpdatePassengerDetailsRequest());
            /** @var Booking $model */
            $model = $obj->getModel();
            Logger::profile("Model Retreived");
            if (!$model)
            {
                throw new \Exception('Invalid request data', \ReturnSet::ERROR_INVALID_DATA);
            }

            // AgentApiTracking
            $aatType  = AgentApiTracking::TYPE_UPDATE_PASSENGER_DETAILS;
            $aatModel = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());

            //Update Passenger Details
            $success = BookingUser::updateGmtPassengerInfo($model->bkgUserInfo, $model->bkg_id, $aatType);

			//add BookingScheduleEvent
			if(Config::get('hornok.operator.id') == $model->bkgBcb->bcb_vendor_id)
			{
			$scheduleTime = Config::get('hornok.sendcustinfo.min');
			BookingScheduleEvent::addPushTravellerDetailsEvent($model, $model->bkg_pickup_date, $scheduleTime);
			}
            // Response
            $updateresponse = new Stub\mmt\UpdatePassengerDetailsResponse();
            $updateresponse->setData($success);
            $data           = Filter::removeNull($updateresponse);
            $response->setData($data);
        }
        catch (Exception $e)
        {
            $ret = ReturnSet::setException($e);
            $response->setError($ret);
        }

        if (!$aatModel)
        {
            $aatType  = AgentApiTracking::TYPE_UPDATE_PASSENGER_DETAILS;
            $aatModel = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());
        }
        if ($success)
        {
            $status = 1;
        }
        else
        {
            $status = 2;
        }
        $time       = Filter::getExecutionTime();
        $error_type = $response->code;
        $error_msg  = $response->error;
        $aatModel->updateResponse($response, $model->bkg_id, $status, $error_type, $error_msg, $time);
        Logger::info("Response: " . json_encode($response));

        return $this->renderJSON([
                    'type' => 'raw',
                    'data' => $response
        ]);
    }

    public function review()
    {
        $returnSet  = new ReturnSet();
        $data       = Yii::app()->request->rawBody;
        Logger::info("data: " . $data);
        $jsonMapper = new JsonMapper();
        $jsonObj    = CJSON::decode($data, false);

        $response = new Stub\mmt\Response();
        try
        {
            /** @var \Stub\mmt\ReviewRequest $obj */
            $obj = $jsonMapper->map($jsonObj, new Stub\mmt\ReviewRequest());

            $ratingModel = $obj->getModel();
			
            $bkgId       = $ratingModel->rtg_booking_id;
            $model       = Booking::model()->findByPk($bkgId);

            if ($bkgId == null)
            {
                throw new Exception("invalid order_reference_number", ReturnSet::ERROR_INVALID_DATA);
            }

            // AgentApiTracking
            $aatType  = AgentApiTracking::TYPE_GET_REVIEW;
            $aatModel = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());

            if ($ratingModel)
			{
				$success = $ratingModel->save();
			}
			else
			{
				throw new Exception(json_encode($ratingModel->getErrors()), ReturnSet::ERROR_VALIDATION);
			}

			// Response
            /** @var \Stub\mmt\ReviewResponse $obj */
            $response = new Stub\mmt\ReviewResponse();
            $response->setData($success);
            $data     = Filter::removeNull($response);
            $response->setData($data);
        }
        catch (Exception $e)
        {
            Logger::warning("Failed to get data: " . $e->getMessage());
            $ret = ReturnSet::setException($e);
            $response->setError($ret);
        }
        //Update AgentApiTracking
        if (!$aatModel)
        {
            $aatType  = AgentApiTracking::TYPE_GET_REVIEW;
            $aatModel = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());
            $status   = 2;
        }
        else
        {
            $status = 1;
        }
        $time       = Filter::getExecutionTime();
        $error_type = $response->code;
        $error_msg  = $response->error;
        $aatModel->updateResponse($response, $model->bkg_id, $status, $error_type, $error_msg, $time);
        Logger::info("Response: " . json_encode($response));
        return $this->renderJSON([
                    'type' => 'raw',
                    'data' => $response
        ]);
    }

    public function reverseBooking()
    {
        $returnSet  = new ReturnSet();
        $data       = Yii::app()->request->rawBody;
        Logger::info("data: " . $data);
        $jsonMapper = new JsonMapper();
        $jsonObj    = CJSON::decode($data, false);
        //Response
        $response   = new Stub\mmt\Response();
        try
        {
			

			$userInfo		 = \UserInfo::getInstance();
			$agentId		 = $userInfo->userId;

            $cnt = Booking::model()->checkDuplicateReferenceId($jsonObj->order_reference_number,$agentId);
            if ($cnt > 0)
            {
                $model = BookingInvoice::model()->updateTRFBookingAmount($jsonObj, $agentId);
				$model->save();

				$aatType  = AgentApiTracking::TYPE_UPDATE_PASSENGER_DETAILS;
				$aatModel = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());
            }
			else
			{
				/** @var \Stub\mmt\ReverseRequest $obj */
				$obj = $jsonMapper->map($jsonObj, new Stub\mmt\ReverseRequest());

				/** @var Booking $model */
				$model = $obj->getModel();

				$aatType  = AgentApiTracking::TYPE_REVERSE_BOOKING_CREATE;
				$aatModel = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());
				if($jsonObj)
				{
					throw new Exception("sold out", ReturnSet::ERROR_INVALID_DATA);
				}
				if (!$model->validate())
				{
					throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
				Logger::profile("Created");
				$model          = $model->fbg();
				$amount         = (($obj->fare_details->total_fare) - ($obj->fare_details->amount_to_be_collected)); //Credit added by agent;
				$bseRemarks     = "Booking advance amount : " . $amount;
				$additionalData = json_encode(['AdvanceAmount' => $amount]);
				BookingScheduleEvent::add($model->bkg_id, BookingScheduleEvent::PARTNER_PENDING_ADVANCE, $bseRemarks, $additionalData);

				if ($model->hasErrors())
				{
					throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
				}
			}
            $returnSet->setStatus(true);
            $userInfo = UserInfo::getInstance();
            /** @var \Stub\mmt\ReverseResponse $response */
            $response = new Stub\mmt\ReverseResponse();
            $response->setData($model);
            $response = Filter::removeNull($response);
            Logger::profile("Success");
        }
        catch (Exception $e)
        {
            $ret = ReturnSet::setException($e);
            $response->setError($ret);
            Logger::exception($e);
        }

        if (!$aatModel)
        {
            $aatType  = AgentApiTracking::TYPE_REVERSE_BOOKING_CREATE;
            $aatModel = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());
            $status   = 2;
        }
        else
        {
            $status = 1;
        }
        $time       = Filter::getExecutionTime();
        $error_type = $response->code;
        $error_msg  = $response->error;

        $aatModel->updateResponse($response, $model->bkg_id, $status, $error_type, $error_msg, $time);

        Logger::info("Response: " . json_encode($response));
        return $this->renderJSON([
                    'type' => 'raw',
                    'data' => $response
        ]);
    }

    public function unavailable()
    {
        $returnSet  = new ReturnSet();
        $data       = Yii::app()->request->rawBody;
        Logger::info("data: " . $data);
        $jsonMapper = new JsonMapper();
        $jsonObj    = CJSON::decode($data, false);

        // Response
        $response = new Stub\mmt\Response();

        try
        {
            /** @var \Stub\mmt\UnavailableRequest $obj */
            $obj   = $jsonMapper->map($jsonObj, new Stub\mmt\UnavailableRequest());
            /** @var Booking $model */
            $model = $obj->getModel();
            if (!$model)
            {
                throw new Exception('Invalid request data', \ReturnSet::ERROR_INVALID_DATA);
            }
            // AgentApiTracking
            $aatType  = AgentApiTracking::TYPE_UNAVAILABLE_BOOKING;
            $aatModel = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());

            if ($model->bkg_status != 9)
            {
				$cancelReason		 = CancelReasons::getTFRCancelReason();
				$cancellation_reason = $cancelReason['cnr_reason'];
				$reasonId			 = $cancelReason['cnr_id'];
				$success			 = $model->canbooking($model->bkg_id, $cancellation_reason, $reasonId);
				$canResp->success	 = true;
			}
            else
            {
                $canResp->success = false;
                throw new \Exception('Booking is Already Cancelled', \ReturnSet::ERROR_FAILED);
            }
            if (!$success)
            {
                $canResp->success = false;
                throw new \Exception('Error while cancel booking', \ReturnSet::ERROR_FAILED);
            }

            $response->setData($canResp);
        }
        catch (Exception $e)
        {
            $ret = ReturnSet::setException($e);
            $response->setError($ret);

            Logger::exception($e);
        }
        //Update AgentApiTracking
        if (!$aatModel)
        {
            $aatType  = AgentApiTracking::TYPE_UNAVAILABLE_BOOKING;
            $aatModel = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());
        }
        $time       = Filter::getExecutionTime();
        $error_type = $response->code;
        $error_msg  = $response->error;
        if ($success)
        {
            $status = 1;
        }
        else
        {
            $status = 2;
        }
        $aatModel->updateResponse($response, $model->bkg_id, $status, $error_type, $error_msg, $time);
        Logger::info("Response: " . json_encode($response));
        return $this->renderJSON([
                    'type' => 'raw',
                    'data' => $response
        ]);
    }

	public function tripDetails()
    {
        $agentOrderRefNo = Yii::app()->request->getParam('order_reference_number');
        $bookingId       = Yii::app()->request->getParam('partner_reference_number');
        Logger::info("params: " . json_encode($_REQUEST));

        if ($agentOrderRefNo && $agentOrderRefNo != '')
        {
            $jsonObj = '{"order_reference_number": "' . $agentOrderRefNo . '"}';
            $model   = Booking::findByOrderNo($agentOrderRefNo);
        }
        if (!$model && $bookingId && $bookingId != '')
        {
            $jsonObj = '{"order_reference_number": "' . $bookingId . '"}';
            $model   = Booking::model()->findByPk($bookingId);
        }

        $response = new Stub\mmt\TripDetailsResponse();
        try
        {
            if (!$model)
            {
                throw new \Exception('Missing Booking', \ReturnSet::ERROR_FAILED);
            }
			
			$isValidate = Booking::checkDuraionForTripDetails($model);
			if($isValidate == false)
			{
				throw new \Exception('Booking is invalid or has been archived', \ReturnSet::ERROR_FAILED);
			}
            $aatType  = AgentApiTracking::TYPE_TRIP_DETAILS;
            $aatModel = AgentApiTracking::model()->add1($aatType, $jsonObj, $model, \Filter::getUserIP());

            $response   = new Stub\mmt\TripDetailsResponse();
            $response->setData($model);
            $response   = Filter::removeNull($response);
            $time       = Filter::getExecutionTime();
            $error_type = $response->code;
            $error_msg  = $response->error;
            if ($model)
            {
                $status = 1;
            }
            else
            {
                $status = 2;
            }
            $aatModel->updateResponse($response, $model->bkg_id, $status, $error_type, $error_msg, $time);
        }
        catch (Exception $ex)
        {

            $ret      = ReturnSet::setException($ex);
            $response->setError($ret);
            $response = Filter::removeNull($response);
        }

        Logger::info("Response: " . json_encode($response));

        return $this->renderJSON([
                    'type' => 'raw',
                    'data' => $response
        ]);
    }

}
