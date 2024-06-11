<?php

use Booking;

include_once(dirname(__FILE__) . '/BaseController.php');

class BookingController extends BaseController
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout       = 'main';
    public $email_receipient, $useUserReturnUrl;
    public $current_page = '';
    public $title        = '';

//public $layout = '//layouts/column2';pre

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
                    'REST.GET', 'REST.PUT', 'REST.REQUEST', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS', 'generateInvoice'),
                'users'   => array('*'),
            ),
            ['allow',
                'actions' => ['new', 'list'],
                'users'   => ['@']
            ],
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
            #$ri	 = array('/getQuote', '/getCancellationList', '/updateBooking', '/cancelBooking', '/getDetails', '/getPackages', '/getPackageDetails', '/fbg');
            $ri  = array();
            foreach ($ri as $value)
            {
                if (strpos($arr[0], $value))
                {
                    $pos = true;
                }
            }
            return $validation ? $validation : ($pos != false);
        });

        $this->onRest('req.post.getQuote.render', function () {
            return $this->renderJSON($this->getQuote());
        });

        $this->onRest('req.post.hold.render', function () {
            return $this->renderJSON($this->hold());
        });

        $this->onRest('req.post.confirm.render', function () {
            return $this->renderJSON($this->confirm());
        });

        $this->onRest('req.post.getDetails.render', function () {
            return $this->renderJSON($this->getDetails());
        });

        $this->onRest('req.post.updateBooking.render', function () {
            return $this->renderJSON($this->updateBooking());
        });

        $this->onRest('req.post.cancelBooking.render', function () {
            return $this->renderJSON($this->cancel());
        });

        $this->onRest('req.post.getCancellationList.render', function () {
            return $this->renderJSON($this->cancellationList());
        });

        $this->onRest('req.post.getTnc.render', function () {
            return $this->renderJSON($this->getTnc());
        });

        $this->onRest('req.post.getPackages.render', function () {
            return $this->renderJSON($this->getPackages());
        });

        $this->onRest('req.post.getPackageDetails.render', function () {
            return $this->renderJSON($this->getPackageDetails());
        });

        $this->onRest('req.post.fbg.render', function () {
            return $this->renderJSON($this->fbg());
        });
    }

    public function getQuote()
    {
        Logger::profile("getQuote initiated");
        $patModel   = null;
        $returnSet  = new ReturnSet();
        $data       = Yii::app()->request->rawBody;
        $jsonMapper = new JsonMapper();
        $jsonObj    = CJSON::decode($data, false);
        try
        {
			if($jsonObj->cabType == '' || $jsonObj->cabType == null || $jsonObj->cabType == 0)
			{
				throw new Exception('Cab type not supported', ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}
            /* @var $obj Stub\booking\QuoteRequest */
            $obj   = $jsonMapper->map($jsonObj, new Stub\booking\QuoteRequest());
            /** @var Booking $model */
            $model = $obj->getModel();

            $userInfo   = UserInfo::getInstance();
            $typeAction = PartnerApiTracking::GET_QUOTE;

			Logger::profile("QuoteRequest called");
			$agentId = $userInfo->userId;
          
			$patModel   = PartnerApiTracking::add($typeAction, $jsonObj, $userInfo->userId, $model, $model->bkg_pickup_date);
			if($agentId == Config::get('Kayak.partner.id') && Config::get('kayak.convertTripType') == 1)
			{
				$model = Kayak::convertTriptype($model);
			}

            $model->scenario = "type1";
            if (!$model->validate())
            {
                throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
            }
			Logger::profile("QuoteRequest to intiated quote model");
            $model->platform       = Quote::Platform_Agent;
            $quotData              = Quote::populateFromModel($model, $model->bkg_vehicle_type_id, $checkBestRate         = false, $includeNightAllowance = true, $isAllowed             = true);
			Logger::profile("quote model called");
            $response = new Stub\booking\QuoteResponse();

            if ($model->bkg_booking_type != 7)
            {
                $quotData              = Quote::populateFromModel($model, $model->bkg_vehicle_type_id, $checkBestRate         = false, $includeNightAllowance = true, $isAllowed             = true);
                $keyArr                = reset($quotData);
                if ($quotData[key($quotData)]->routeDistance->quotedDistance > 0)
                {
                    $response->setData($quotData);
                }
                else
                {
                    //\Sentry\captureMessage(json_encode($quotData), null);
                    $returnSet->setStatus(false);
                    throw new Exception('Cab type temporarily not available', ReturnSet::ERROR_NO_RECORDS_FOUND);
                }
            }
            else
            {
                $quotData = Shuttle::populateFromModel($model);
                foreach ($quotData as $quote)
                {
                    $res                    = new Stub\booking\QuoteResponse();
                    $res->setShuttleData($quote);
                    $responsedt->dataList[] = $res;
                }
                $response = $responsedt;
                if ($response == null)
                {
                    $returnSet->setStatus(false);
                    throw new Exception('Shuttle not available on this date', ReturnSet::ERROR_NO_RECORDS_FOUND);
                }
            }
			Logger::profile("quote model processed");

            $data = Filter::removeNull($response);
            $returnSet->setStatus(true);
            $returnSet->setData($data);
			$time       = Filter::getExecutionTime();
            $patModel->updateData($returnSet, 1, null, null, null, $time);
        }
        catch (Exception $e)
        {
            $returnSet = ReturnSet::setException($e);

            if ($patModel)
            {
				$time       = Filter::getExecutionTime();
                $patModel->updateData($returnSet, 2, null, $e->getCode(), $e->getMessage(), $time);
            }
        }

        return $returnSet;
    }

    public function getMessageList()
    {
        $message    = array();
        $returnSet  = new ReturnSet();
        $data       = Yii::app()->request->rawBody;
        $jsonMapper = new JsonMapper();
        $jsonObj    = CJSON::decode($data, false);
        try
        {
            /* @var $obj Stub\booking\GetMessageRequest */
            $obj      = $jsonMapper->map($jsonObj, new Stub\booking\GetMessageRequest());
            $message  = $obj->getMessage();
            $response = new Stub\booking\GetMessageResponse();
            $response->setData($message);
            $data     = Filter::removeNull($response);
            $returnSet->setStatus(true);
            $returnSet->setData($data);
        }
        catch (Exception $e)
        {
            $returnSet = ReturnSet::setException($e);
        }
        return $returnSet;
    }

    public function hold()
	{
		$patModel	 = null;
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$jsonMapper	 = new JsonMapper();
		$jsonObj	 = CJSON::decode($data, false);
		try
		{
			if($jsonObj->cabType == '' || $jsonObj->cabType == null || $jsonObj->cabType == 0)
			{
				throw new Exception('Cab type not supported', ReturnSet::ERROR_REQUEST_CANNOT_PROCEED);
			}
			/* @var $obj Stub\booking\CreateRequest */
			$obj		 = $jsonMapper->map($jsonObj, new Stub\booking\CreateRequest());
			/** @var Booking $model */
			$userInfo	 = \UserInfo::getInstance();
			$agentId	 = $userInfo->userId;
			$model		 = $obj->getModel(null, $agentId);		
			$model->requestType = booking::HOLD_REQUEST;

			$spiceId = Config::get('spicejet.partner.id');
			$sugerboxId =  Config::get('sugerbox.partner.id');
			$mynId = Config::get('myn.partner.id');
			if (($agentId == 30228 || $agentId == $spiceId || $agentId == $sugerboxId || $agentId == $mynId) && ($model->bkg_booking_type == 12))
			{
					$model->bkgTrail->btr_stop_increasing_vendor_amount	 = 1;
			}

			$model->scenario = "type1";
            if (!$model->validate())
            {
                throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
            }

			if ($agentId == 30228)
			{
				$advanceAmount	 = $obj->fare->advanceReceived;
				$minPerAdvAmount = ceil($obj->fare->totalAmount * 0.25);
				$minPerAdvAmount = $minPerAdvAmount - 1;
				if ($minPerAdvAmount > $advanceAmount)
				{
					throw new \Exception('CONFIRM Failed: Advance amount is incorrect', \ReturnSet::ERROR_INVALID_DATA);
				}
			}

			$typeAction	 = PartnerApiTracking::CREATE_BOOKING;
			$patModel	 = PartnerApiTracking::add($typeAction, $jsonObj, $userInfo->userId, $model, $model->bkg_pickup_date);
			if($agentId == $spiceId || $agentId == $sugerboxId)
			{
				$model->bkgPref->bkg_block_autoassignment = Spicejet::setBlockAutoAssignmentStatus($obj->fare->advanceReceived);
			}
			if($agentId == $sugerboxId)
		{
			$model->bkgPref->bkg_block_autoassignment			 = 1;
		}
			if (!$model->validate())
			{
				throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
			}
			$model->scenario = 'validateData';
			$errors			 = CActiveForm::validate($model, null, false);

			$model->platform		 = Quote::Platform_Agent;
			$cpAmount				 = $model->bkgInvoice->bkg_total_amount;
			$quotData				 = Quote::populateFromModel($model, $model->bkg_vehicle_type_id, $checkBestRate			 = false, $includeNightAllowance	 = true, $isAllowed				 = true);
			$quote					 = $quotData[$model->bkg_vehicle_type_id];
			$routeRates				 = $quote->routeRates;
			$totalAmount			 = $routeRates->totalAmount;

			Logger::info("cpAmount: " . $cpAmount);
			Logger::info("total amount: " . $totalAmount);

			if ($cpAmount < ($totalAmount - 2))
			{
				Logger::info("Prices have increased");
				$returnSet->setStatus(false);
				throw new Exception("BLOCK Failed: Prices have increased", ReturnSet::ERROR_INVALID_DATA);
			}
			if ($cpAmount > $totalAmount)
			{
				$routeRates->calculateSellBaseFromTotal($cpAmount, $agentId, $model->bkg_booking_type);
				$routeRates->baseAmount = $routeRates->fixedBaseAmount;
				$routeRates->calculateTotal();
			}

			if($jsonObj->fare->mojoFare > $totalAmount)
			{
				$routeRates->calculateSellBaseFromTotal($jsonObj->fare->mojoFare, $agentId, $model->bkg_booking_type);
				$routeRates->baseAmount = $routeRates->fixedBaseAmount;
				$routeRates->calculateTotal();
			}

			if ($errors == '[]')
			{
				$model->addNew();
				
				/* @var $revisedCommission Booking */
				if($jsonObj->fare->mojoFare > $totalAmount && $jsonObj->fare->advanceReceived == 0)
				{
					$revisedCommission = Booking::revisedPartnerCommission($model, $jsonObj, $totalAmount);
				}

				if ($agentId == $spiceId || $agentId == $sugerboxId)
				{
					$model->bkgPref->bkg_driver_app_required = 0;
					$model->bkgPref->save();
				}
				$response	 = new Stub\booking\CreateResponse();
				$response->setData($model);
				$data		 = Filter::removeNull($response);
				$returnSet->setStatus(true);
				$returnSet->setData($data);
				$time       = Filter::getExecutionTime();

				$patModel->updateData($returnSet, 1, $model->bkg_id, null, null, $time);
			}
			else
			{
				$errors = $model->getErrors();
				throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
			}
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
			$time       = Filter::getExecutionTime();
			if ($patModel)
			{
				$patModel->updateData($returnSet, 2, null, $e->getCode(), $e->getMessage(), $time);
			}
		}
		return $returnSet;
	}

	public function confirm()
    {
        $patModel  = null;
        $returnSet = new ReturnSet();
        $data      = Yii::app()->request->rawBody;
        $jsonObj   = CJSON::decode($data, false);
		$spiceId = Config::get('spicejet.partner.id');
        try
        {
            $userInfo   = \UserInfo::getInstance();
            $model      = Booking::model()->getBkgIdByBookingId($jsonObj->bookingId);
            $typeAction = PartnerApiTracking::CONFIRM_BOOKING;
            $patModel   = PartnerApiTracking::add($typeAction, $jsonObj, $userInfo->userId, $model, $model->bkg_pickup_date);
            
            if ($model)
            {
                //Partner Wallet Update
                $model->refresh();

                $agtApprovedUntillDate = Agents::CheckAgentApprovedTillDate($model->bkg_agent_id);

                if (!$agtApprovedUntillDate)
                {
                    $returnSet->setStatus(false);
                    throw new Exception("Booking failed as partner is unapproved.");
                }
                if ($model->bkgInvoice->bkg_advance_amount)
                {
                    $actmodel = AccountTransactions::usePartnerWallet($model->bkg_pickup_date, $model->bkgInvoice->bkg_advance_amount, $model->bkg_id, $model->bkg_agent_id, "Partner Wallet used", UserInfo::getInstance());
                    if (!$actmodel)
                    {
                        $returnSet->setStatus(false);
                        throw new Exception("Booking failed as partner wallet balance exceeded.");
                    }
                }
				
                $model->confirm($setReconfirm = true, $sentMessage  = true, $bkgId        = null, $userInfo     = null, $isAllowed    = true);
                $response = new Stub\booking\CreateResponse();
                $response->setData($model);
                $data     = Filter::removeNull($response);
                $returnSet->setStatus(true);
                $returnSet->setData($data);
				$time       = Filter::getExecutionTime();
                $patModel->updateData($returnSet, 1, $model->bkg_id, null, null, $time);
            }
            else
            {
                $returnSet->setStatus(false);
                throw new Exception("Only unverified and quoted booking can eligible", ReturnSet::ERROR_INVALID_DATA);
            }
        }
        catch (Exception $e)
        {
            $returnSet = ReturnSet::setException($e);
            if ($patModel)
            {
                $bkgId = substr($jsonObj->bookingId, 4);
				$time       = Filter::getExecutionTime();
                $patModel->updateData($returnSet, 2, $bkgId, $e->getCode(), $e->getMessage(), $time);
            }
        }
        return $returnSet;
    }

    public function getDetails()
    {
        $returnSet = new ReturnSet();
        $data      = Yii::app()->request->rawBody;
        $jsonObj   = json_decode($data, false);
        try
        {
            $model = Booking::model()->getBkgIdByBookingId($jsonObj->bookingId);
            if (!$model)
            {
                throw new Exception("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
            }
            $typeAction = PartnerApiTracking::GET_DETAILS;
            $patModel   = PartnerApiTracking::add($typeAction, $data, $model->bkg_agent_id, $model, $model->bkg_pickup_date);
            $response   = new Stub\booking\GetDetailsResponse();
            $response->setData($model);
            $data       = Filter::removeNull($response);
            $returnSet->setStatus(true);
            $returnSet->setData($data);
        }
        catch (Exception $e)
        {
            $returnSet = ReturnSet::setException($e);
        }
        return $returnSet;
    }

    public function updateBooking()
    {
        $patModel   = null;
        $returnSet  = new ReturnSet();
        $data       = Yii::app()->request->rawBody;
        $jsonMapper = new JsonMapper();
        $jsonObj    = CJSON::decode($data, false);
        try
        {
            /* @var $obj Stub\booking\UpdateRequest */
            $obj   = $jsonMapper->map($jsonObj, new Stub\booking\UpdateRequest());
            /** @var Booking $model */
            $model = $obj->getModel();

            $userInfo   = UserInfo::getInstance();
            $bkgModel   = Booking::model()->getBkgIdByBookingId($model->bkg_booking_id);
            $returnSet  = $model->updateBookingApi($model, $bkgModel);
            $typeAction = PartnerApiTracking::UPDATE_BOOKING;
            $patModel   = PartnerApiTracking::add($typeAction, $jsonObj, $userInfo->userId, $bkgModel, $bkgModel->bkg_pickup_date);
			
        }
        catch (Exception $e)
        {
            $returnSet = ReturnSet::setException($e);
            Logger::exception($e);

            if ($patModel)
            {
                $patModel->updateData($returnSet, 2, null, $e->getCode(), $e->getMessage());
            }
        }
        return $returnSet;
    }

    public function cancel()
    {
        $returnSet = new ReturnSet();
        $data      = Yii::app()->request->rawBody;
        $jsonObj   = json_decode($data, false);

        try
        {
            $result = Booking::model()->cancelBookingApi($jsonObj);
            if ($result['success'] == true)
            {
                $returnSet->setStatus(true);
                $response = new Stub\booking\CancelResponse();
                $response->setData($result);
                $returnSet->setData($response);
            }
            else
            {
                $returnSet->setStatus(false);
                $returnSet->setErrors($result['errors'], ReturnSet::ERROR_FAILED);
            }
        }
        catch (Exception $e)
        {
            $returnSet = $returnSet->setException($e);
            Logger::exception($e);
        }
        return $returnSet;
    }

    public function cancellationList()
    {
        $returnSet = new ReturnSet();
        try
        {
            $rDetail = CancelReasons::model()->getListbyUserType(1);
            if ($rDetail != null)
            {
                foreach ($rDetail[0] as $key => $val)
                {
                    $data[] = array("id" => $key, "text" => $val, "placeholder" => $rDetail[1][$key]);
                }
                $returnSet->setStatus(true);
                $response = new Stub\booking\GetCancelListResponse();
                $response->setData($data);
                $response = Filter::removeNull($response);
                $returnSet->setData($response);
            }
            else
            {
                $returnSet->setStatus(false);
                $returnSet->setErrors("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
            }
        }
        catch (Exception $e)
        {
            $returnSet = $returnSet->setException($e);
        }
        return $returnSet;
    }

    public function getTnc()
    {
        $returnSet = new ReturnSet();
        $data      = [];
        $model     = Terms::model()->getText(1);
        $tnc       = $model->tnc_text;
        try
        {
            if ($tnc != '')
            {
                $data = ['tnc' => strip_tags(preg_replace("/[\n\r]/", " ", $tnc))];
                $returnSet->setStatus(true);
                $returnSet->setData($data);
            }
            else
            {
                $returnSet->setStatus(false);
                $returnSet->setErrors("Invalid Data", ReturnSet::ERROR_INVALID_DATA);
            }
        }
        catch (Exception $e)
        {
            $returnSet = $returnSet->setException($e);
        }
        return $returnSet;
    }

    public function getPackages()
    {
        $returnSet = new ReturnSet();
        try
        {
            $data = Yii::app()->request->rawBody;
            if (!$data)
            {
                throw new Exception('Invalid Request.', ReturnSet::ERROR_INVALID_DATA);
            }
            $jsonMapper = new JsonMapper();
            $jsonObj    = CJSON::decode($data, false);
            $arr        = ["city" => $jsonObj->fromCity, "min_nights" => $jsonObj->minNights, "max_nights" => $jsonObj->maxNights];
            $pageNumber = $jsonObj->pageNumber;
            $isApp      = true;
            $result     = Package::model()->getListtoShow('', $arr, $isApp, $pageNumber);
            if (!$result)
            {
                throw new Exception("Didn't find the package you are looking for? Just call us at 90518 77000 and we will create your package for you.", ReturnSet::ERROR_NO_RECORDS_FOUND);
            }
            $response = new \Stub\common\Package();
            $response = $response->setData($result);
            $returnSet->setStatus(true);
            $returnSet->setData($response);
        }
        catch (Exception $e)
        {
            $returnSet->setStatus(false);
            $returnSet = $returnSet->setException($e);
        }
        return $returnSet;
    }

    public function getPackageDetails()
    {
        $returnSet = new ReturnSet();
        try
        {
            $data = Yii::app()->request->rawBody;
            if (!$data)
            {
                throw new Exception('Invalid Request.', ReturnSet::ERROR_INVALID_DATA);
            }
            $jsonMapper     = new JsonMapper();
            $jsonObj        = CJSON::decode($data, false);
            $packageId      = $jsonObj->packageId;
            $pickupDate     = $jsonObj->pickupDate;
            $pickupDate     = (empty($pickupDate)) ? date("Y-m-d", strtotime('+7 days')) : $pickupDate;
            $ptimePackage   = Yii::app()->params['defaultPackagePickupTime'];
            $pickupDateTime = $pickupDate . " " . $ptimePackage;
            $packageModel   = Package::model()->findByPk($packageId);
            $routeModel     = $packageModel->packageDetails;
            $prouteDetails  = BookingRoute::model()->setTripRouteInfo($routeModel, 5, $pickupDateTime);
            if (!$prouteDetails)
            {
                throw new Exception("Package Route Not Found.", ReturnSet::ERROR_NO_RECORDS_FOUND);
            }
            $routeData = BookingRoute::model()->populateRouteByPackageId($packageId, $pickupDtTime, true);
            if (!$routeData)
            {
                throw new Exception("Route Not Found.", ReturnSet::ERROR_NO_RECORDS_FOUND);
            }
            $response      = new \Stub\common\Package();
            $packageRoutes = $response->setRouteInfo($prouteDetails);
            $returnSet->setStatus(true);
            $response      = new \Stub\common\Itinerary();
            $routes        = $response->setModelsData($routeData);
            $returnSet->setData(["packageRoutes" => $packageRoutes, "routes" => $routes]);
        }
        catch (Exception $e)
        {
            $returnSet->setStatus(false);
            $returnSet = $returnSet->setException($e);
        }
        return $returnSet;
    }

    public function fbg()
    {
        $patModel   = null;
        $returnSet  = new ReturnSet();
        $data       = Yii::app()->request->rawBody;
        $jsonMapper = new JsonMapper();
        $jsonObj    = CJSON::decode($data, false);
        try
        {
            /* @var $obj Stub\booking\FbgRequest */
            $obj      = $jsonMapper->map($jsonObj, new Stub\booking\FbgRequest());
            /** @var Booking $model */
            $userInfo = \UserInfo::getInstance();
            $agentId  = $userInfo->userId;
            $model    = $obj->getModel(null, $agentId);

            $typeAction = PartnerApiTracking::TYPE_FBG_BOOKING;
            $patModel   = PartnerApiTracking::add($typeAction, $jsonObj, $userInfo->userId, $model, $model->bkg_pickup_date);
            if (!$model->validate())
            {
                throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
            }
            $model->scenario = 'validateData';
            $errors          = CActiveForm::validate($model, null, false);

            if ($errors == '[]')
            {
                $model->fbg();

                $response = new Stub\booking\FbgResponse();
                $response->setData($model);
                $data     = Filter::removeNull($response);
                $returnSet->setStatus(true);
                $returnSet->setData($data);
				$time       = Filter::getExecutionTime();
                $patModel->updateData($returnSet, 1, $model->bkg_id, null, null, $time);
            }
            else
            {
                $errors = $model->getErrors();
                throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
            }
        }
        catch (Exception $e)
        {
            $returnSet = ReturnSet::setException($e);

            if ($patModel)
            {
				$time       = Filter::getExecutionTime();
                $patModel->updateData($returnSet, 2, null, $e->getCode(), $e->getMessage(), $time);
            }
        }
        return $returnSet;
    }

}
