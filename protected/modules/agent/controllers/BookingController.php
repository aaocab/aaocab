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
                'actions' => array('bookingquotes', 'list', 'spotfour', 'spotfive', 'spotsix', 'spot', 'summary', 'route', 'addroute', 'canbooking',
                    'addagentcredit', 'route2', 'view', 'cabratedetail', 'additionaldetail', 'invoice',
                    'finalbook', 'new', 'agtconview', 'agtfinalbook', 'createquote', 'agtroute',
                    'agtrtview', 'cabagentratedetail', 'verifybooking', 'validateagtcustinfo', 'booksummaryrefresh',
                    'quotetobook', 'credithistory', 'spotsummary', 'accountsdashboard',
                    'addremark', 'spotsummary', 'marksettled', 'ledgerbooking', 'shuttlelist', 'invoiceDownload', 'selectAddress', 'track',
                ),
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
            $ri  = array('/cabratelistnew', '/getRouteRates', '/getCurrentQuote', '/additionalDetails', 'updateBooking', '/bookingdetails', '/process3new', '/cablist', '/bookingupdatenew', '/cabratelistbycity', '/bookingconfirmstep1', '/bookinglist', '/bookingcancel', '/cab_list', '/trip_validation', '/bookingConfirmAgent', '/finalBook', '/receiveAIData', '/receiveAINewData', '/getHawkEyeList');
            foreach ($ri as $value)
            {
                if (strpos($arr[0], $value))
                {
                    $pos = true;
                }
            }
            return $validation ? $validation : ($pos != false);
        });

        $this->onRest('req.post.bookinglist.render', function () {
            $model = $this->bookingList();
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => true,
                    'data'    => $model
                ),
            ]);
        });

        $this->onRest('req.get.getRouteRates.render', function () {
            $success  = false;
            $expire   = 0;
            $fromCity = '30366'; //Yii::app()->request->getParam('fromCity');
            $toCity   = '30804'; //Yii::app()->request->getParam('toCity');
            $cabType  = 3; //Yii::app()->request->getParam('cabType');
            $list     = Route::model()->getRouteRates($fromCity, $toCity, $cabType);
            if ($list != '')
            {
                $success         = true;
                $data            = [$list];
                $sourceCity      = strstr($list['rut_name'], '-', true);
                $destinationCity = strstr($list['rut_name'], '-');
                $destinationCity = str_replace('-', '', $destinationCity);
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success'          => $success,
                    'data'             => $data,
                    'base rate'        => $list['rte_amount'],
                    'source city'      => $sourceCity,
                    'destination city' => $destinationCity,
                )
            ]);
        });

        $this->onRest('req.post.cabratelistnew.render', function () {
            $success      = false;
            $expire       = 0;
            $pickdate     = Yii::app()->request->getParam('bkg_pickup_date_date');
            $picktime     = Yii::app()->request->getParam('bkg_pickup_date_time');
            $pickup_time  = strtotime($pickdate . " " . $picktime);
            $current_time = strtotime(date("d-m-Y h:i A"));
            $hours        = ($pickup_time - $current_time) / 3600;
            if ($hours >= 2)
            {
                $success = true;
                $expire  = 1;
            }
            else
            {
                $expire = 2;
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => $success,
                    'expire'  => $expire,
                    'message' => "date time validation",
                    'data'    => $data,
                )
            ]);
        });

        $this->onRest('req.get.create.render', function () {
            $process_sync_data = Yii::app()->request->getParam('data');
            $data              = CJSON::decode($process_sync_data, true);
            $result            = $this->createBooking1($data);
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'data' => $result
                ),
            ]);
        });

        //fetch data from hawkeye software
        $this->onRest('req.post.getHawkEyeList.render', function () {
            $process_sync_data = Yii::app()->request->getRawBody();
            Logger::create("Request test ====>" . $process_sync_data . "====", CLogger::LEVEL_PROFILE);
            $data              = CJSON::decode($process_sync_data, true);
            Hawkeye::model()->getData($data);
        });

        $this->onRest('req.get.cityrouteratelist.render', function () {
            $success = false;
            $data    = [];
            $list    = Rate::model()->getRateListAgent();
            if ($list != '')
            {
                $success = true;
                $data    = ['list' => $list];
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => $success,
                    'data'    => $data,
                )
            ]);
        });

        $this->onRest('req.get.getCurrentQuote.render', function () {
            $pickupCity = Yii::app()->request->getParam('pickupCity');
            $dropCity   = Yii::app()->request->getParam('dropCity');
            $tripType   = 1;
            $pickupDate = Yii::app()->request->getParam('pickupDate');
            $pickupTime = Yii::app()->request->getParam('pickupTime');
            $routes[]   = array(
                'pickupCity'    => $pickupCity,
                'dropCity'      => $dropCity,
                'tripType'      => $tripType,
                'pickupDate'    => $pickupDate,
                'pickupTime'    => $pickupTime,
                'dropPincode'   => '',
                'dropAddress'   => '',
                'pickupPincode' => '',
                'pickupAddress' => ''
            );
            if ($routes != '')
            {
                $triptype                 = $tripType;
                $routes                   = $routes;
                $bmodel                   = Booking::model();
                $bmodel->bkg_booking_type = $tripType;
                $routeArr                 = [];
                $rCount                   = count($routes);
                $bmodel->bkg_from_city_id = $routes[0]['pickupCity'];
                $bmodel->bkg_to_city_id   = $routes[0]['dropCity'];
                $pickupDate               = $routes[0]['pickupDate'];
                $pickupTime               = $routes[0]['pickupTime'];
                $pickupDateTime           = $pickupDate . ' ' . $pickupTime;

                $routeModel                       = new BookingRoute();
                $routeModel->brt_from_city_id     = $routes[0]['pickupCity'];
                $routeModel->brt_to_city_id       = $routes[0]['dropCity'];
                $routeModel->brt_pickup_datetime  = $routes[0]['pickupDate'] . " " . $routes[0]['pickupTime'];
                $routeModel->brt_pickup_date_date = DateTimeFormat::DateTimeToDatePicker($routes[0]['pickupDate'] . " " . $routes[0]['pickupTime']);
                $routeModel->brt_pickup_date_time = date('h:i A', strtotime($routes[0]['pickupDate'] . " " . $routes[0]['pickupTime']));
                $routeModel->brt_to_location      = '';
                $routeModel->brt_from_location    = '';
                $routeModel->brt_to_pincode       = '';
                $routeModel->brt_from_pincode     = '';
                $routeArr[]                       = $routeModel;
                $bmodel->bookingRoutes            = $routeArr;
                $bmodel->setScenario('apiroutes');
                $result                           = CActiveForm::validate($bmodel, null, false);
                if ($result == '[]')
                {
                    $arr                      = [];
                    $arr[$key]['date']        = $routes[0]['pickupDate'] . " " . $routes[0]['pickupTime'];
                    $arr[$key]['drop_city']   = $routes[0]['dropCity'];
                    $arr[$key]['pickup_city'] = $routes[0]['pickupCity'];
                    $arr                      = json_encode($arr);
                    $arr                      = json_decode($arr);
                    $returnDateTime           = null;
                    if (count($routeArr) > 1)
                    {
                        $lastRoute      = $routeArr[count($routeArr) - 1];
                        $picktime       = $lastRoute->brt_pickup_datetime;
                        $routeDuration  = Route::model()->getRouteDurationbyCities($lastRoute->brt_from_city_id, $lastRoute->brt_to_city_id);
                        $returnDateTime = date('Y-m-d H:i:s', strtotime($picktime . ' + ' . $routeDuration . ' minute'));
                    }
                    $partnerId = Yii::app()->user->getId();

                    $quote                  = new Quote();
                    $quote->routes          = $routeArr;
                    $quote->tripType        = $triptype;
                    $quote->partnerId       = $partnerId;
                    $quote->quoteDate       = date('Y-m-d H:i:s');
                    $quote->pickupDate      = $routeArr[0]->brt_pickup_datetime;
                    $quote->returnDate      = $returnDateTime;
                    $quote->sourceQuotation = Quote::Platform_App;
                    $quote->setCabTypeArr();
                    $quotData               = $quote->getQuote();
                    $agtType                = Agents::model()->findByPk($partnerId)->agt_type;
                    $cabAllowed             = [1, 3, 2, 5, 6, 7, 8, 9];
                    $returnRes              = [];
                    $cablist                = [];
                    foreach ($cabAllowed as $cab)
                    {
                        $quoteRoute = $quotData[$cab];
                        if (!$quoteRoute->success)
                        {
                            unset($quotData[$cab]);
                            continue;
                        }
                        if ($agtType == 0 || $agtType == 1)
                        {
                            $arrQuote               = Agents::model()->getBaseDiscFare($quoteRoute->routeRates, $agtType, $partnerId);
                            $quoteRoute->routeRates = $arrQuote;
                        }
                        $routeRates                                     = $quoteRoute->routeRates;
                        $routeDistance                                  = $quote->routeDistance;
                        $routeDuration                                  = $quote->routeDuration;
                        $model                                          = Booking::model();
                        $model->bkgInvoice                              = BookingInvoice::model();
                        $model->bkgInvoice->bkg_gozo_base_amount        = $routeRates->baseAmount;
                        $model->bkgInvoice->bkg_base_amount             = $routeRates->baseAmount;
                        $model->bkgInvoice->bkg_driver_allowance_amount = $routeRates->driverAllowance;
                        $model->bkgInvoice->bkg_toll_tax                = $routeRates->tollTaxAmount | 0;
                        $model->bkgInvoice->bkg_state_tax               = $routeRates->stateTax | 0;
                        $model->bkgInvoice->bkg_is_toll_tax_included    = $routeRates->isTollIncluded | 0;
                        $model->bkgInvoice->bkg_is_state_tax_included   = $routeRates->isStateTaxIncluded | 0;
                        $model->bkgInvoice->bkg_night_pickup_included   = $routeRates->isNightPickupIncluded;
                        $model->bkgInvoice->bkg_night_drop_included     = $routeRates->isNightDropIncluded;
                        $model->bkg_agent_id                            = Yii::app()->user->getId();
                        $model->bkgInvoice->populateAmount(true, false, true, false, $model->bkg_agent_id);
                        $routeRates->baseAmount                         = $model->bkgInvoice->bkg_base_amount;
                        $routeRates->gst                                = $model->bkgInvoice->bkg_service_tax;
                        $routeRates->totalAmount                        = $model->bkgInvoice->bkg_total_amount;

                        $totalAmount = $routeRates->totalAmount;
                        $carmodel    = VehicleTypes::model()->getCarModel($cab, 1);
                        $res         = [
                            'cab'              => VehicleTypes::model()->getCarByCarType($cab),
                            'cabId'            => $carmodel->vht_id,
                            'cabModel'         => $carmodel->vht_model, // $quote[0]['cab_model'],
                            'baseAmount'       => $routeRates->baseAmount,
                            'serviceTax'       => $routeRates->gst,
                            'gst'              => $routeRates->gst,
                            'driverAllowance'  => $routeRates->driverAllowance,
                            'totalAmount'      => $totalAmount,
                            'ratePerKilometer' => $routeRates->ratePerKM,
                            'imagePath'        => YII::app()->createAbsoluteUrl($carmodel->vht_image),
                            'capacity'         => $carmodel->vht_capacity,
                            'bagCapacity'      => $carmodel->vht_bag_capacity,
                            'bigBagCapacity'   => $carmodel->vht_big_bag_capacity,
                            'tollTax'          => (int) $routeRates->tollTaxAmount | 0,
                            'stateTax'         => (int) $routeRates->stateTax | 0
                        ];
                        $cablist[]   = $res;
                    }
                    $arrr = [
                        'totalKilometers'    => $quote->routeDistance->quotedDistance, //$quote[0]['min_chargeable'],
                        'totalMinutes'       => $quote->routeDuration->totalMinutes, // $quote[0]['total_min'],
                        'startTripDate'      => $quote->routeDuration->fromDate, // $quote[0]['startTripDate'],
                        'endTripDate'        => $quote->routeDuration->toDate, // $quote[0]['endTripDate'],
                        'driverAllowance'    => $quotData[1]->routeRates->driverAllowance, // $quote[0]['driverAllowance'],
                        'tollTaxFlag'        => $quotData[1]->routeRates->isTollIncluded | 0, //$tollTaxFlag,
                        'stateTaxFlag'       => $quotData[1]->routeRates->isStateTaxIncluded | 0, // $stateTaxFlag,
                        'startTripCity'      => ['id'   => $routeArr[0]->brt_from_city_id,
                            'name' => Cities::getName($routeArr[0]->brt_from_city_id)],
                        'endTripCity'        => ['id'   => $routeArr[count($routeArr) - 1]->brt_to_city_id,
                            'name' => Cities::getName($routeArr[count($routeArr) - 1]->brt_to_city_id)],
                        'chargeableDistance' => $quote->routeDistance->quotedDistance];

                    $returnRes = $arrr + ['cabList' => $cablist];

                    if ($quote->success)
                    {
                        $result = ['success' => true, 'data' => $returnRes];
                    }
                    else
                    {
                        $result = ['success' => false, 'errors' => $quote->errorCode];
                    }
                }
                else
                {
                    $errors = [];
                    foreach ($bmodel->getErrors() as $key => $value)
                    {
                        $key          = $this->errorMapping($key);
                        $errors[$key] = $value;
                    }
                    $result = ['success' => false, 'errortype' => 4, 'errors' => $errors];
                }
            }
            else
            {
                $result = ['success' => false, 'message' => 'Invalid Json'];
            }

            return $this->renderJSON([
                'type' => 'raw',
                'data' => $result
            ]);
        });

        $this->onRest('req.post.getQuote.render', function () {
            $data      = Yii::app()->request->getParam('data');
            $returnSet = new ReturnSet();
            if ($data == "")
            {
                $data = Yii::app()->request->rawBody;
                $data = json_decode($data, false);
            }
            else
            {
                $data = json_decode($data);
            }
            try
            {
                if ($data != '')
                {
                    $triptype                 = $data->tripType;
                    $tripReturnDate           = $data->tripEndDate;
                    $tripReturnTime           = $data->tripEndTime;
                    $routes                   = $data->routes;
                    $bmodel                   = Booking::model();
                    $bmodel->bkg_booking_type = $triptype;
                    $routeArr                 = [];
                    $rCount                   = count($routes);
                    $bmodel->bkg_from_city_id = $routes[0]->pickupCity;
                    $bmodel->bkg_to_city_id   = $routes[$rCount - 1]->dropCity;
                    $pickupDate               = $routes[0]->pickupDate;
                    $pickupTime               = $routes[0]->pickupTime;
                    $pickupDateTime           = $pickupDate . ' ' . $pickupTime;
                    $typeAction               = 4;
                    $patModel                 = PartnerApiTracking::add($typeAction, $data, Yii::app()->user->getId(), $bmodel, $pickupDateTime);

                    if ($triptype == 2 && $tripReturnDate != '')
                    {
                        $returnDate              = $tripReturnDate;
                        $returnTime              = $tripReturnTime;
                        $dropCity                = $routes[0]->dropCity;
                        $returnDate              = $returnDate;
                        $returnTime              = date('H:i:s', strtotime($returnTime));
                        $returnDateTime          = $returnDate . ' ' . $returnTime;
                        $routeDuration           = Route::model()->getRouteDurationbyCities($dropCity, $bmodel->bkg_from_city_id);
                        $pickupDateTime2         = date('Y-m-d H:i:s', strtotime($returnDateTime . '- ' . $routeDuration . ' minute'));
                        $pickupDate2             = date('Y-m-d', strtotime($pickupDateTime2));
                        $pickupTime2             = date('H:i:s', strtotime($pickupDateTime2));
                        $bmodel->bkg_return_date = $returnDateTime;
                        // $bmodel->bkg_return_time = $returnTime;
                    }

                    $routes1 = $routes;
                    foreach ($routes as $key => $value)
                    {
                        $routeModel                   = new BookingRoute();
                        $routeModel->brt_from_city_id = $value->pickupCity;
                        $routeModel->brt_to_city_id   = $value->dropCity;
                        if ($triptype == 2 && $tripReturnDate != '' && $key == 1)
                        {
                            $routeModel->brt_pickup_datetime  = $pickupDate2 . " " . $pickupTime2;
                            $routes1[$key]->pickupDate        = $pickupDate2;
                            $routes1[$key]->pickupTime        = $pickupTime2;
                            $routeModel->brt_pickup_date_date = $pickupDate2;
                            $routeModel->brt_pickup_date_time = date('h:i A', strtotime($pickupDate2 . " " . $pickupTime2));
                        }
                        else
                        {
                            $routeModel->brt_pickup_datetime  = $value->pickupDate . " " . $value->pickupTime;
                            $routeModel->brt_pickup_date_date = DateTimeFormat::DateTimeToDatePicker($value->pickupDate . " " . $value->pickupTime);
                            $routeModel->brt_pickup_date_time = date('h:i A', strtotime($value->pickupDate . " " . $value->pickupTime));
                        }

                        $routeModel->brt_to_location    = $value->dropAddress;
                        $routeModel->brt_from_latitude  = $value->pickupLatitude;
                        $routeModel->brt_from_longitude = $value->pickupLongitude;
                        $routeModel->brt_to_latitude    = $value->dropLatitude;
                        $routeModel->brt_to_longitude   = $value->dropLongitude;
                        $routeModel->brt_from_location  = $value->pickupAddress;
                        $routeModel->brt_to_pincode     = $value->dropPincode;
                        $routeModel->brt_from_pincode   = $value->pickupPincode;

                        $routeArr[] = $routeModel;
                    }
                    $bmodel->bookingRoutes = $routeArr;
                    $errors                = BookingRoute::validateRoutes($bmodel->bookingRoutes); //CActiveForm::validate($bmodel, null, false);
                    if (!empty($errors))
                    {
                        throw new Exception(json_encode($errors), ReturnSet::ERROR_VALIDATION);
                    }
                    else
                    {
                        $arr = [];
                        foreach ($routes1 as $key => $val)
                        {
                            $arr[$key]['date']        = $val->pickupDate . " " . $val->pickupTime;
                            $arr[$key]['drop_city']   = $val->dropCity;
                            $arr[$key]['pickup_city'] = $val->pickupCity;
                        }
                        $arr = json_encode($arr);
                        $arr = json_decode($arr);

                        $returnDateTime = null;
                        if (count($routeArr) > 1)
                        {
                            $lastRoute      = $routeArr[count($routeArr) - 1];
                            $picktime       = $lastRoute->brt_pickup_datetime;
                            $routeDuration  = Route::model()->getRouteDurationbyCities($lastRoute->brt_from_city_id, $lastRoute->brt_to_city_id);
                            $returnDateTime = date('Y-m-d H:i:s', strtotime($picktime . ' + ' . $routeDuration . ' minute'));
                        }
                        $partnerId = Yii::app()->user->getId();

                        $quote                  = new Quote();
                        $quote->routes          = $routeArr;
                        $quote->tripType        = $triptype;
                        $quote->partnerId       = $partnerId;
                        $quote->quoteDate       = date('Y-m-d H:i:s');
                        $quote->pickupDate      = $routeArr[0]->brt_pickup_datetime;
                        $quote->returnDate      = $returnDateTime;
                        $quote->sourceQuotation = Quote::Platform_Agent;
                        Quote::$updateCounter   = true;
                        $quote->setCabTypeArr();
                        $quotData               = $quote->getQuote();

                        foreach ($quotData as $k => $v)
                        {
                            if ($k > 0)
                            {
                                $arrQuote1 = $quotData[$k];
                            }
                        }

                        $agtType = Agents::model()->findByPk($partnerId)->agt_type;

                        $cabAllowed = [VehicleCategory::COMPACT_ECONOMIC, VehicleCategory::SEDAN_ECONOMIC, VehicleCategory::SUV_ECONOMIC, VehicleCategory::ASSURED_DZIRE_ECONOMIC, VehicleCategory::ASSURED_INNOVA_ECONOMIC, VehicleCategory::TEMPO_TRAVELLER_9_ECONOMIC, VehicleCategory::TEMPO_TRAVELLER_12_ECONOMIC, VehicleCategory::TEMPO_TRAVELLER_15_ECONOMIC];
                        $returnRes  = [];
                        $cablist    = [];
                        foreach ($cabAllowed as $cab)
                        // for ($i = 0; $i < $count; $i++)
                        {
                            $quoteRoute = $quotData[$cab];
                            if (!$quoteRoute->success)
                            {
                                unset($quotData[$cab]);
                                continue;
                            }
                            if ($agtType == 0 || $agtType == 1)
                            {
                                $arrQuote               = Agents::model()->getBaseDiscFare($quoteRoute->routeRates, $agtType, $partnerId);
                                $quoteRoute->routeRates = $arrQuote;
                            }
                            $routeRates                                     = $quoteRoute->routeRates;
                            $routeDistance                                  = $quote->routeDistance;
                            $routeDuration                                  = $quote->routeDuration;
                            $model                                          = Booking::model();
                            $model->bkgInvoice                              = BookingInvoice::model();
                            $model->bkgInvoice->bkg_gozo_base_amount        = $routeRates->baseAmount;
                            $model->bkgInvoice->bkg_base_amount             = $routeRates->baseAmount;
                            $model->bkgInvoice->bkg_driver_allowance_amount = $routeRates->driverAllowance;
                            $model->bkgInvoice->bkg_toll_tax                = $routeRates->tollTaxAmount | 0;
                            $model->bkgInvoice->bkg_state_tax               = $routeRates->stateTax | 0;
                            $model->bkgInvoice->bkg_is_toll_tax_included    = $routeRates->isTollIncluded | 0;
                            $model->bkgInvoice->bkg_is_state_tax_included   = $routeRates->isStateTaxIncluded | 0;

                            $model->bkgInvoice->bkg_night_pickup_included = $routeRates->isNightPickupIncluded;
                            $model->bkgInvoice->bkg_night_drop_included   = $routeRates->isNightDropIncluded;

                            $model->bkg_agent_id     = Yii::app()->user->getId();
                            $model->bkgInvoice->populateAmount(true, false, true, false, $model->bkg_agent_id);
                            $routeRates->baseAmount  = $model->bkgInvoice->bkg_base_amount;
                            $routeRates->gst         = $model->bkgInvoice->bkg_service_tax;
                            $routeRates->totalAmount = $model->bkgInvoice->bkg_total_amount;

//                    $tollTaxFlag  = $quote[0]['tolltax'];
//                    $stateTaxFlag = $quote[0]['statetax'];
//                    $total_zero   = $quote[0]['total_amt'];
//                    $total_one    = $quote[1]['total_amt'];
//                    $total_two    = $quote[2]['total_amt'];

                            $totalAmount = $routeRates->totalAmount;

                            if (Yii::app()->user->getId() == 123)
                            {
                                $totalAmount = $routeRates->totalAmount - ($routeRates->tollTaxAmount | 0) - ($routeRates->stateTax | 0);
                            }
                            //$carmodel	 = VehicleTypes::model()->getCarModel($cab, 1);
                            $carInfo = SvcClassVhcCat::model()->getVctSvcList('detail', '', '', $cab);
                            $mapId   = SvcClassVhcCat::model()->vehicleCategoryMapping($carInfo[scv_id]);

                            $var = explode('/', $carInfo[vct_image]);

                            $res       = [
                                'cab'              => $carInfo[vct_label], //VehicleTypes::model()->getCarByCarType($cab),
                                'cabId'            => $mapId,
                                'cabModel'         => $carInfo[vct_desc], // $quote[0]['cab_model'],
                                'baseAmount'       => $routeRates->baseAmount,
                                'serviceTax'       => $routeRates->gst,
                                'gst'              => $routeRates->gst,
                                'driverAllowance'  => $routeRates->driverAllowance,
                                'totalAmount'      => $totalAmount,
                                'ratePerKilometer' => $routeRates->ratePerKM,
                                'imagePath'        => IMAGE_URL . '/' . $var[1] . '/' . $var[2], //YII::app()->createAbsoluteUrl($carInfo[vct_image]),
                                'capacity'         => $carInfo[vct_capacity],
                                'bagCapacity'      => $carInfo[vct_small_bag_capacity],
                                'bigBagCapacity'   => $carInfo[vct_big_bag_capacity],
                                'tollTax'          => (int) $routeRates->tollTaxAmount | 0,
                                'stateTax'         => (int) $routeRates->stateTax | 0
                            ];
                            $cablist[] = $res;
                        }


                        $arrr = [
                            'totalKilometers'    => $quote->routeDistance->quotedDistance, //$quote[0]['min_chargeable'],
                            'totalMinutes'       => $quote->routeDuration->totalMinutes, // $quote[0]['total_min'],
                            'startTripDate'      => $quote->routeDuration->fromDate, // $quote[0]['startTripDate'],
                            'endTripDate'        => $quote->routeDuration->toDate, // $quote[0]['endTripDate'],
                            'driverAllowance'    => $quotData[1]->routeRates->driverAllowance, // $quote[0]['driverAllowance'],
                            'tollTaxFlag'        => $quotData[1]->routeRates->isTollIncluded | 0, //$tollTaxFlag,
                            'stateTaxFlag'       => $quotData[1]->routeRates->isStateTaxIncluded | 0, // $stateTaxFlag,
                            'startTripCity'      => ['id'   => $routeArr[0]->brt_from_city_id,
                                'name' => Cities::getName($routeArr[0]->brt_from_city_id)],
                            'endTripCity'        => ['id'   => $routeArr[count($routeArr) - 1]->brt_to_city_id,
                                'name' => Cities::getName($routeArr[count($routeArr) - 1]->brt_to_city_id)],
                            'isNightPickup'      => $quotData[1]->routeRates->isNightPickupIncluded | 0,
                            'isNightDrop'        => $quotData[1]->routeRates->isNightDropIncluded | 0,
                            'chargeableDistance' => $quote->routeDistance->quotedDistance];

                        $returnRes = $arrr + ['cabList' => $cablist];

                        if ($quote->success)
                        {
                            $status = 1;
                            $result = ['success' => true, 'data' => $returnRes];
                        }
                        else
                        {
                            $status = 2;
                            $result = ['success' => false, 'errors' => $quote->errorCode];
                        }
                    }
                }
                else
                {
                    $status = 2;
                    $result = ['success' => false, 'message' => 'Invalid Json'];
                }
                $patModel->updateData($result, $status, $bmodel->bkg_id, $result['errortype'], $result['errors']['routes'][0]);
            }
            catch (Exception $e)
            {
                $result = $returnSet->setException($e);
            }

            return $this->renderJSON([
                'type' => 'raw',
                'data' => $result
            ]);
        });

        $this->onRest('req.post.getCancellationList.render', function () {
            $success = false;
            $data    = [];
            $rDetail = CancelReasons::model()->getListbyUserType(1);
            foreach ($rDetail[0] as $key => $val)
            {
                $data[] = array("id" => $key, "text" => $val, "placeholder" => $rDetail[1][$key]);
            }
            if ($data != '')
            {
                $success = true;
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => $success,
                    'data'    => $data,
                )
            ]);
        });

        $this->onRest('req.post.cancelBooking.render', function () {
            $result = ['success' => false, 'errortype' => '8', 'errors' => "Error In Booking Cancellation"];
            $status = 2;
            $data   = Yii::app()->request->getParam('data');
            if ($data == "")
            {
                $data = Yii::app()->request->rawBody;
            }
            $data         = CJSON::decode($data, true);
            $bookingId    = $data['bookingId'];
            $reason       = $data['reason'];
            $reasonId     = $data['reasonId'];
            $typeAction   = 8;
            $bookingModel = Booking::model()->findByBookingid($bookingId);
            $patModel     = PartnerApiTracking::add($typeAction, $data, $bookingModel->bkg_agent_id, $bookingModel, $bookingModel->bkg_pickup_date);
            if ($bookingModel != '' && $bookingModel->bkg_agent_id == Yii::app()->user->getId() && !in_array($bookingModel->bkg_status, [1, 2, 3, 5]))
            {
                $status = 2;
                $result = ['success' => false, 'errors' => "Booking already cancelled or completed"];
            }
            else
            {
                if ($bookingModel != '' && $bookingModel->bkg_agent_id == Yii::app()->user->getId())
                {
                    $tripTimeDiff = Booking::model()->getPickupDifferencebyBkgid($bookingModel->bkg_id);
                    $totalAdvance = PaymentGateway::model()->getTotalAdvance($bookingModel->bkg_id);
                    $agentModel   = Agents::model()->findByPk($bookingModel->bkg_agent_id);

                    $success = $this->canbooking1($bookingModel->bkg_id, $reason, $reasonId);

                    $refundArr          = BookingPref::model()->calculateRefund($tripTimeDiff, $bookingModel->bkgInvoice->bkg_total_amount, $totalAdvance, $agentModel->agt_cancel_rule, $bookingModel->bkg_id);
                    $refundAmount       = $refundArr['refund'];
                    $cancellationCharge = abs($refundArr['cancelCharge']);
                    $refundAmount       = ($refundAmount < 0) ? 0 : $refundAmount;
                    if ($reasonId != "" && $success)
                    {
                        $status = 1;
                        $result = ['success' => true, 'message' => "Booking cancelled successfully", 'cancellationCharge' => $cancellationCharge, 'refundAmount' => $refundAmount];
                    }
                }
            }
            $patModel->updateData($result, $status, $bookingModel->bkg_id);
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'data' => $result,
                )
            ]);
        });

        $this->onRest('req.post.updateBooking.render', function () {
            $data = Yii::app()->request->getParam('data');
            if ($data == "")
            {
                $data = Yii::app()->request->rawBody;
            }
            $data  = CJSON::decode($data, true);
            //$result = ['booking_id' => $data['bookingId'], 'response_type' => 'UPDATE_BOOKING', 'vendor_response' => ['message' => 'UPDATE Failed: Booking not found', 'is_success' => false], 'errors' => 'Booking not found'];
            $bkg   = trim($data['booking_id']);
            $bkgId = substr($bkg, -6);
            $model = Booking::model()->findByPk($bkgId);
            if ($model)
            {
                $typeAction = 9;
                $patModel   = PartnerApiTracking::add($typeAction, $data, $model->bkg_agent_id, $model, $model->bkg_pickup_date);
                $oldModel   = clone $model;
                $oldData    = Booking::model()->getDetailsbyId($bkgId);
                $cModel     = $model->bkgBcb;
                $rModel     = $model->bookingRoutes;
                if ($model != '' && $cModel != '' && $rModel != '')
                {
                    if ($data['amountPaid'] != '')
                    {
                        $currentAdvanceAmount = $data['amountPaid'] - $model->bkgInvoice->bkg_advance_amount;
                        $amount               = $currentAdvanceAmount | 0;
                        if ($amount > 0)
                        {
                            $userInfo      = UserInfo::getInstance();
                            $desc          = "Credits used";
                            $accStatus     = 1;
                            $skipBkgUpdate = false;
                            $isUpdated     = $model->updateAdvance($amount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, $userInfo, null, $desc, $skipBkgUpdate, $accStatus);
                            if (!$isUpdated)
                            {
                                $errors = implode(", ", array_values($model->getErrors()));
                                $status = 2;
                                $result = ['booking_id' => $data['booking_id'], 'response_type' => 'UPDATE_BOOKING', 'vendor_response' => ['message' => 'UPDATE Failed. ' . $errors, 'is_success' => false], 'status' => 'error', 'errors' => 'Update Failed. ' . $errors];
                            }
                            $status = 1;
                            $result = ['booking_id' => $data['booking_id'], 'response_type' => 'UPDATE_BOOKING', 'vendor_response' => ['message' => 'UPDATE Booking successful', 'is_success' => true], 'errors' => ''];
                        }
                        else
                        {
                            $status = 2;
                            $result = ['booking_id' => $data['booking_id'], 'response_type' => 'UPDATE_BOOKING', 'vendor_response' => ['message' => 'UPDATE Failed: Amount should be greater than zero', 'is_success' => false], 'status' => 'error', 'errors' => 'Amount should be greater than zero'];
                        }
                    }

                    $newData          = Booking::model()->getDetailsbyId($model->bkg_id);
                    $getDifference    = array_diff_assoc($newData, $oldData); // $this->getDifference($oldData, $newData);
                    $getOldDifference = array_diff_assoc($oldData, $newData);
                    $changesForVendor = Agents::model()->getModificationMSG($getDifference, 'vendor');
                    $changesForLog    = " Old Values: " . Agents::model()->getModificationMSG($getOldDifference, 'log');
                    $logDesc          = "Booking modified";
                    $eventid          = BookingLog::BOOKING_MODIFIED;
                    $desc             = $logDesc . $changesForLog;
                    $bkgid            = $model->bkg_id;
                    $userInfo         = UserInfo::getInstance();
                    $bookingID        = $model->bkg_booking_id;
                    BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);
                }
            }
            else
            {
                $status = 2;
                $result = ['vendor_response' => ['message' => 'UPDATE Failed: Booking not found', 'is_success' => false], 'status' => 'false', 'errortype' => '9', 'errors' => 'Booking Update Error'];
            }

            $time = Filter::getExecutionTime();

            $patModel->updateData($result, $status, $bkgId, $result['errortype'], $result['errors']);
            return $this->renderJSON([
                'type' => 'raw',
                'data' => $result
            ]);
        });

        $this->onRest('req.post.cab_list.render', function () {
            $success  = false;
            $errors   = 'Something went wrong';
            $triptype = Yii::app()->request->getParam('trip_type');
            $data     = Yii::app()->request->getParam('data');
            $data     = json_decode($data);
            $result   = $this->cab_list($data, $triptype);
            if ($result)
            {
                $success = true;
                $errors  = [];
            }
            $count = count($result);
            for ($i = 0; $i < $count; $i++)
            {
                $model                              = Booking::model();
                $model->bkg_gozo_base_amount        = $result[$i]['gozo_base_amount'];
                $model->bkg_base_amount             = $result[$i]['base_amt'];
                $model->bkg_driver_allowance_amount = $result[$i]['driverAllowance'];
                $model->bkg_toll_tax                = $result[$i]['toll_tax'];
                $model->bkg_state_tax               = $result[$i]['state_tax'];
                $model->bkg_is_toll_tax_included    = $result[$i]['tolltax'];
                $model->bkg_is_state_tax_included   = $result[$i]['statetax'];
                $model->bkg_agent_id                = Yii::app()->user->getAgentId();
                $model->populateAmount(true, false, true, false, $model->bkg_agent_id);
                $result[$i]['base_amt']             = $model->bkg_base_amount;
                $result[$i]['service_tax']          = $model->bkg_service_tax;
                $result[$i]['total_amt']            = $model->bkg_total_amount;
                $result[$i]['toll_tax']             = $model->bkg_toll_tax;
                $result[$i]['state_tax']            = $model->bkg_state_tax;
                $result[$i]['toll_tax_included']    = $model->bkg_is_toll_tax_included;
                $result[$i]['state_tax_included']   = $model->bkg_is_state_tax_included;
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => $success,
                    'errors'  => $errors,
                    'data'    => $result,
                )
            ]);
        });

        $this->onRest('req.post.trip_validation.render', function () {
            $routes = Yii::app()->request->getParam('routes');
            $routes = json_decode($routes);
            $result = Quotation::model()->tripValidation($routes);
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => $result['success'],
                    'errors'  => $result['errors'],
                )
            ]);
        });

        $this->onRest('req.post.bookingConfirmAgent.render', function () {
            $datasuccess            = [];
            $datasuccess['success'] = false;
            $process_sync_data      = Yii::app()->request->getParam('data');
            $data                   = json_decode($process_sync_data, true);
            $routes                 = Yii::app()->request->getParam('routes');
            $routes                 = json_decode($routes);
            $datasuccess            = $this->bookingConfirmAgent($data, $routes);
            $agent_id               = Yii::app()->user->getAgentId();
            $agent_model            = Agents::model()->findByPk($agent_id);
            if ($datasuccess != '')
            {
                if ($datasuccess['success'])
                {
                    $model                       = $datasuccess['data'];
                    $model                       = Booking::model()->with('bkgFromCity', 'bkgToCity', 'bkgSvcClassVhcCat')->findbyPk($model->bkg_id);
                    $hr                          = date('G', mktime(0, $model->bkg_trip_duration)) . " Hr";
                    $min                         = (date('i', mktime(0, $model->bkg_trip_duration)) != '00') ? ' ' . date('i', mktime(0, $model->bkg_trip_duration)) . " min" : '';
                    $model->trip_duration_format = $hr . $min;
                    $model->trip_distance_format = $model->bkg_trip_distance . ' Km';
                    $datareturn                  = JSONUtil::convertModelToArray($model);
                }
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => $datasuccess['success'],
                    'days'    => $datasuccess['days'],
                    'cab'     => $datasuccess['cab'],
                    'flag'    => $agent_model->agt_allow_discount,
                    'model'   => $datareturn,
                    'errors'  => $datasuccess['errors'],
                )
            ]);
        });

        $this->onRest('req.post.createBooking.render', function () {
            $returnSet         = new ReturnSet();
            $process_sync_data = Yii::app()->request->getParam('data');
            if ($process_sync_data == "")
            {
                $process_sync_data = Yii::app()->request->rawBody;
            }
            $data1 = CJSON::decode($process_sync_data, true);
            try
            {
                if (isset($data1['totalAmount']))
                {
                    $partnerTotalAmount = $data1['totalAmount'];
                }
                $triptype                 = $data1['tripType'];
                $routes                   = $data1['routes'];
                $tripReturnDate           = $data1['tripEndDate'];
                $tripReturnTime           = $data1['tripEndTime'];
                $bmodel                   = Booking::model();
                $bmodel->bkg_booking_type = $triptype;
                $routeArr                 = [];
                $rCount                   = count($routes);
                $bmodel->bkg_from_city_id = $routes[0]['pickupCity'];
                $bmodel->bkg_to_city_id   = $routes[$rCount - 1]['dropCity'];
                $bmodel->bkg_pickup_date  = $routes[0]['pickupDate'] . " " . $routes[0]['pickupTime'];
                $pickupDateTime           = $routes[0]['pickupDate'] . " " . $routes[0]['pickupTime'];
                $typeAction               = 5;
                $userInfo                 = UserInfo::getInstance();
                $patModel                 = PartnerApiTracking::add($typeAction, $data1, $userInfo->userId, $bmodel, $pickupDateTime);
                if ($triptype == 2 && $tripReturnDate != '')
                {
                    $returnDate              = $tripReturnDate;
                    $returnTime              = $tripReturnTime;
                    $dropCity                = $routes[0]['dropCity'];
                    $returnDate              = $returnDate;
                    $returnTime              = date('H:i:s', strtotime($returnTime));
                    $returnDateTime          = $returnDate . ' ' . $returnTime;
                    $routeDuration           = Route::model()->getRouteDurationbyCities($dropCity, $bmodel->bkg_from_city_id);
                    $pickupDateTime2         = date('Y-m-d H:i:s', strtotime($returnDateTime . '- ' . $routeDuration . ' minute'));
                    $pickupDate2             = date('Y-m-d', strtotime($pickupDateTime2));
                    $pickupTime2             = date('H:i:s', strtotime($pickupDateTime2));
                    $bmodel->bkg_return_date = $returnDateTime;
                    //  $bmodel->bkg_return_time = $returnTime;
                }
                $routes1 = $routes;
                foreach ($routes as $key => $value)
                {
                    $routeModel                   = new BookingRoute();
                    $routeModel->brt_from_city_id = $value['pickupCity'];
                    $routeModel->brt_to_city_id   = $value['dropCity'];
                    if ($triptype == 2 && $tripReturnDate != '' && $key == 1)
                    {
                        $routeModel->brt_pickup_datetime  = $pickupDate2 . " " . $pickupTime2;
                        $routes1[$key]['pickupDate']      = $pickupDate2;
                        $routes1[$key]['pickupTime']      = $pickupTime2;
                        $routeModel->brt_pickup_date_date = $pickupDate2;
                        $routeModel->brt_pickup_date_time = date('h:i A', strtotime($pickupTime2));
                    }
                    else
                    {
                        $pickupTimeVal                    = date('H:i:s', strtotime($value['pickupTime']));
                        $routeModel->brt_pickup_datetime  = $value['pickupDate'] . " " . $pickupTimeVal;
                        $routeModel->brt_pickup_date_date = DateTimeFormat::DateToDatePicker($value['pickupDate']);
                        $routeModel->brt_pickup_date_time = date('h:i A', strtotime($value['pickupTime']));
                    }

                    $routeModel->brt_from_latitude  = $value['pickupLatitude'];
                    $routeModel->brt_from_longitude = $value['pickupLongitude'];
                    $routeModel->brt_to_latitude    = $value['dropLatitude'];
                    $routeModel->brt_to_longitude   = $value['dropLongitude'];

                    $routeModel->brt_to_location   = $value['dropAddress'];
                    $routeModel->brt_from_location = $value['pickupAddress'];
                    $routeModel->brt_to_pincode    = $value['dropPincode'];
                    $routeModel->brt_from_pincode  = $value['pickupPincode'];
                    $routeArr[]                    = $routeModel;
                }
                $bmodel->bookingRoutes = $routeArr;
                //$bmodel->setScenario('apiroutes');
                $errors                = BookingRoute::validateRoutes($bmodel->bookingRoutes); //CActiveForm::validate($bmodel, null, false);
                if (!empty($errors))
                {
                    throw new Exception(json_encode($errors), ReturnSet::ERROR_VALIDATION);
                }
                else
                {
                    $arr = [];
                    foreach ($routes1 as $key => $val)
                    {
                        $pickupTimeVal               = date('H:i:s', strtotime($val['pickupTime']));
                        $arr[$key]['date']           = $val['pickupDate'] . " " . $pickupTimeVal;
                        $arr[$key]['drop_city']      = $val['dropCity'];
                        $arr[$key]['pickup_city']    = $val['pickupCity'];
                        $arr[$key]['drop_address']   = $val['dropAddress'];
                        $arr[$key]['drop_pincode']   = $val['dropPincode'];
                        $arr[$key]['pickup_address'] = $val['pickupAddress'];
                        $arr[$key]['pickup_pincode'] = $val['pickupPincode'];

                        $arr[$key]['pickupLatitude']  = $val['pickupLatitude'];
                        $arr[$key]['pickupLongitude'] = $val['pickupLongitude'];
                        $arr[$key]['dropLatitude']    = $val['dropLatitude'];
                        $arr[$key]['dropLongitude']   = $val['dropLongitude'];
                    }
                    $arr    = json_encode($arr);
                    $arr    = json_decode($arr);
                    $result = $this->createBooking($data1, $arr, $triptype, $partnerTotalAmount);
                    $status = 1;
                    if ($result['errors'] != '')
                    {
                        $errors = [];
                        foreach ($result['errors'] as $key => $value)
                        {
                            $key          = $this->errorMapping($key);
                            $errors[$key] = $value;
                        }
                        $status           = 2;
                        $result['errors'] = $errors;
                    }
                }

                $bkgModel = Booking::model()->getBkgIdByBookingId($result['data']['bookingId']);

                $patModel->updateData($result, $status, $bkgModel->bkg_id, $result['errortype'], $result['errors'][0]);
            }
            catch (Exception $e)
            {
                //$result = ['success' => false, 'errortype' => 5, 'errors' => 'Unable to create booking, Some data missing'];
                //Logger::create("Create booking Error log: ", json_encode($e->getMessage()), CLogger::LEVEL_TRACE);
                $result = $returnSet->setException($e);
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => $result
            ]);
        });

        $this->onRest('req.post.additionalDetails.render', function () {
            $datasuccess            = [];
            $datasuccess['success'] = false;
            $process_sync_data      = Yii::app()->request->getParam('data');
            $data                   = json_decode($process_sync_data, true);
            $routes                 = Yii::app()->request->getParam('routes');
            $routes                 = json_decode($routes);
            $datasuccess            = $this->additionaldetails($data, $routes);
            if ($datasuccess != '')
            {
                if ($datasuccess['success'])
                {
                    $model                       = $datasuccess['data'];
                    $model                       = Booking::model()->with('bkgFromCity', 'bkgToCity', 'bkgSvcClassVhcCat')->findbyPk($model->bkg_id);
                    $hr                          = date('G', mktime(0, $model->bkg_trip_duration)) . " Hr";
                    $min                         = (date('i', mktime(0, $model->bkg_trip_duration)) != '00') ? ' ' . date('i', mktime(0, $model->bkg_trip_duration)) . " min" : '';
                    $model->trip_duration_format = $hr . $min;
                    $model->trip_distance_format = $model->bkg_trip_distance . ' Km';
                    $datareturn                  = JSONUtil::convertModelToArray($model);
                }
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success'    => $datasuccess['success'],
                    'model'      => $datareturn,
                    'errors'     => $datasuccess['errors'],
                    'minPayable' => round($model->bkg_total_amount * 15 / 100),
                )
            ]);
        });

        $this->onRest('req.post.getCities.render', function () {
            $success = false;
            $data    = [];
            $list    = Cities::model()->getCityListforAgents();
            if ($list != '')
            {
                $success = true;
                $data    = ['cities' => $list];
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => $success,
                    'data'    => $data,
                )
            ]);
        });

        $this->onRest('req.post.getTnc.render', function () {
            $success = false;
            $data    = [];
            $model   = Terms::model()->getText(1);
            $tnc     = $model->tnc_text;
            if ($tnc != '')
            {
                $success = true;
                $data    = ['tnc' => $tnc];
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => $success,
                    'data'    => $data,
                )
            ]);
        });

        $this->onRest('req.get.statuslist.render', function () {
            $success    = false;
            $data       = [];
            $statuslist = Booking::model()->getBookingStatus();
            if ($statuslist != '')
            {
                $success = true;
                $data    = ['status_list' => $statuslist];
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => $success,
                    'data'    => $data,
                )
            ]);
        });

        $this->onRest('req.post.getDetails.render', function () {
            $success = false;
            $data    = ['success' => false, 'errortype' => '6', 'error' => 'Error In Booking Details'];
            $status  = 2;
            $data1   = Yii::app()->request->getParam('data');
            if ($data1 == "")
            {
                $data1 = Yii::app()->request->rawBody;
            }
            $data1      = CJSON::decode($data1, true);
            $apkVersion = $data1['apkVersion'];
            $typeAction = 6;
            $model      = Booking::model()->getDetailbyIdAgent($data1['bookingId']);
            $patModel   = PartnerApiTracking::add($typeAction, $data1, $model->bkg_agent_id, $model, $model->bkg_pickup_date);
            if ($model != '' && $model->bkg_agent_id == Yii::app()->user->getId())
            {
                $model->trip_duration_format = $model->bkg_trip_duration . ' mins';
                $model->trip_distance_format = $model->bkg_trip_distance . ' Km';
                //$data						 = $this->reverseMapping($model);
                $activeApkVersion            = Config::get("Version.Android.agent"); //Yii::app()->params['versionCheck']['agent'];
                if ($activeApkVersion <= $apkVersion)
                {
                    $data = $this->newReverseMapping($model);
                }
                else
                {
                    $data = $this->reverseMapping($model);
                }

                $agentGatewayStatus = BookingSub::model()->getAgentGatewayStatus($model->bkg_id);
                $gatewayStatus      = $agentGatewayStatus['gateway'];
                if ($gatewayStatus == 1)
                {
                    $hash            = Yii::app()->shortHash->hash($model->bkg_id);
                    $paymentLink     = $_SERVER['HTTP_HOST'] . '/bkpn/' . $model->bkg_id . '/' . $hash;
                    $note            = "2.5% will be chargeable using this system";
                    $data['payment'] = ["link" => "$paymentLink", "notes" => $note];
                }
                $status = 1;
                $data   = ['success' => true, 'data' => $data];
            }

            $patModel->updateData($data, $status, $model->bkg_id, $data['errortype'], $data['error']);
            return $this->renderJSON([
                'type' => 'raw',
                'data' => $data
            ]);
        });

        $this->onRest('req.get.cabratelistbycity.render', function () {
            $success                     = false;
            $data                        = Yii::app()->request->getParam('data');
            $bookingData                 = CJSON::decode($data, true);
            $model                       = new Booking('Route');
            $model->attributes           = $bookingData;
            $pickupDate                  = date('Y-m-d', strtotime($model->bkg_pickup_date_date));
            $date                        = $model->bkg_pickup_date_date;
            $time                        = $model->bkg_pickup_date_time;
            $model->bkg_pickup_date_date = date('d-m-Y', strtotime($model->bkg_pickup_date_date));
            $model->bkg_pickup_date_time = date('h:i A', strtotime($model->bkg_pickup_date_time));
            $model->bkg_pickup_date_date = str_replace("-", "/", $model->bkg_pickup_date_date);
            $fcityname                   = Cities::getName($model->bkg_from_city_id);
            $tcityname                   = Cities::getName($model->bkg_to_city_id);
            $citystring                  = $fcityname . ' to ' . $tcityname;
            $rModel                      = Route::model()->getbyCities($model->bkg_from_city_id, $model->bkg_to_city_id);
            if ($rModel)
            {
                $model->bkg_from_city_id  = $rModel->rut_from_city_id;
                $model->bkg_to_city_id    = $rModel->rut_to_city_id;
                $model->bkg_trip_distance = $rModel->rut_estm_distance;
                $model->bkg_trip_duration = $rModel->rut_estm_time;
            }
            $hr                          = date('G', mktime(0, $model->bkg_trip_duration)) . " Hr";
            $min                         = (date('i', mktime(0, $model->bkg_trip_duration)) != '00') ? ' ' . date('i', mktime(0, $model->bkg_trip_duration)) . " min" : '';
            $model->trip_duration_format = $hr . $min;
            $model->trip_distance_format = $model->bkg_trip_distance . ' Km';
            if (!$model->validate())
            {
                throw new CHttpException("404", "Route not found");
            }
            $cabRate = Rate::model()->getCabDetailsbyCitiesArr($model->bkg_from_city_id, $model->bkg_to_city_id);
            $count   = count($cabRate);
            for ($i = 0; $i < $count; $i++)
            {
                $gozo_base_amount = Booking::model()->getAmountExcludingTax($cabRate[$i]['cab_rate'], $model->bkg_agent_id);
                //$tax_rate			 = Filter::getServiceTaxRate();
                $tax_rate         = BookingInvoice::getGstTaxRate($model->bkg_agent_id, $model->bkg_booking_type);
                $gozo_markup      = 0;
                $agent_markup     = 0;
                $agent_id         = Yii::app()->user->getId();
                if ($agent_id)
                {
                    $agent_model   = Agents::model()->findByPk($agent_id);
                    $commisionType = $agent_model->agt_commission_value;
                    $commision     = $agent_model->agt_commission | 0;
                    if ($agent_model->agt_type == 1 || $agent_model->agt_type == 0)
                    {
                        $commision = 0;
                    }
                    $gozoCommisionType = $agent_model->agt_gozo_commission_value;
                    $gozoCommision     = $agent_model->agt_gozo_commission | 0;
                    $agent_markup      = ($commisionType == 1) ? round(($commision * $gozo_base_amount) / 100) : $commision;
                    $gozo_markup       = ($gozoCommisionType == 1) ? round(($gozoCommision * $gozo_base_amount) / 100) : $gozoCommision;
                }
                $base_amount             = $gozo_base_amount + $agent_markup + $gozo_markup;
                $service_tax             = round($base_amount * $tax_rate / 100);
                $cabRate[$i]['cab_rate'] = $base_amount + $service_tax;
            }
            $bookingModel = JSONUtil::convertModelToArray($model);
            if ($cabRate != '')
            {
                $success = true;
            }
            $bookingModel = array_filter($bookingModel);
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success'            => $success,
                    'message'            => "Cab Rate List",
                    'data'               => $data,
                    'model'              => $bookingModel,
                    'citystring'         => $citystring,
                    'date'               => $date,
                    'time'               => $time,
                    'rate'               => $cabRate,
                    'specialremark'      => $rModel->rut_special_remarks,
                    'errorsBookingModel' => $model->getErrors(),
                )
            ]);
        });

        $this->onRest('req.get.cablist.render', function () {
            $fcity    = Yii::app()->request->getParam('bkg_from_city_id');
            $tcity    = Yii::app()->request->getParam('bkg_to_city_id');
            $agent_id = Yii::app()->user->getId();
            $cabRate  = Rate::model()->getCabDetailsbyCitiesAgentArr($fcity, $tcity, $agent_id);
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => true,
                    'message' => "Cab  List",
                    'rate'    => $cabRate,
                )
            ]);
        });

        /**
         * @deprecated since version 15-10-2019
         * @author ramala
         */
        $this->onRest('req.get.showdetails.render', function () {
            $success = true;
            $errors  = [];
            $model   = $this->showdetailsService();
            if ($model->hasErrors())
            {
                $success = false;
                $errors  = $model->getErrors();
            }
            $fcityname  = Cities::getName($model->bkg_from_city_id);
            $tcityname  = Cities::getName($model->bkg_to_city_id);
            $datestring = date('jS M Y (l)', strtotime($model->bkg_pickup_date));
            $timestring = date('h:i A', strtotime($model->bkg_pickup_date));
            $data11     = JSONUtil::convertModelToArray($model);
            $vmodel     = VehicleTypes::model()->findbyPk($model->bkg_vehicle_type_id);
            $vmodeldata = JSONUtil::convertModelToArray($vmodel);
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success'    => $success,
                    'errors'     => $errors,
                    'data'       => $_GET,
                    'model'      => $data11,
                    'fcityname'  => $fcityname,
                    'tcityname'  => $tcityname,
                    'datestring' => $datestring,
                    'timestring' => $timestring,
                    'cabdetails' => $vmodeldata
                )
            ]);
        });

        $this->onRest('req.get.bookingconfirmstep1.render', function () {
            $datasuccess            = [];
            $datasuccess['success'] = false;
            $process_sync_data      = Yii::app()->request->getParam('data');
            $data                   = CJSON::decode($process_sync_data, true);
            $datasuccess            = $this->bookingConfirmStep1Service($data);
            $agent_id               = Yii::app()->user->getId();
            $agent_model            = Agents::model()->findByPk($agent_id);
            if ($datasuccess != '')
            {
                if ($datasuccess['success'])
                {
                    $model                       = $datasuccess['data'];
                    $model                       = Booking::model()->with('bkgFromCity', 'bkgToCity', 'bkgSvcClassVhcCat')->findbyPk($model->bkg_id);
                    $hr                          = date('G', mktime(0, $model->bkg_trip_duration)) . " Hr";
                    $min                         = (date('i', mktime(0, $model->bkg_trip_duration)) != '00') ? ' ' . date('i', mktime(0, $model->bkg_trip_duration)) . " min" : '';
                    $model->trip_duration_format = $hr . $min;
                    $model->trip_distance_format = $model->bkg_trip_distance . ' Km';

                    $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label = strtoupper($model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label) . " " . $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . " ( ";
                    $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc  = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc . ')';

                    $datareturn = JSONUtil::convertModelToArray($model);
                }
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => $datasuccess['success'],
                    'data'    => $_GET,
                    'model'   => $datareturn,
                    'errors'  => $datasuccess['errors'],
                    'flag'    => $agent_model->agt_allow_discount,
                )
            ]);
        });

        $this->onRest('req.get.process3new.render', function () {
            $success           = true;
            $errors            = [];
            $process_sync_data = Yii::app()->request->getParam('data');
            $data              = CJSON::decode($process_sync_data, true);
            $arrSuccess        = $this->process3newService($data);
            $model1            = $arrSuccess['model'];
            $success           = $arrSuccess['success'];
            $errors            = $arrSuccess['errors'];
            if ($success)
            {
                if ($model1 != '')
                {
                    $model                       = Booking::model()->with('bkgFromCity', 'bkgToCity', 'bkgSvcClassVhcCat')->findbyPk($model1->bkg_id);
                    $hr                          = date('G', mktime(0, $model->bkg_trip_duration)) . " Hr";
                    $min                         = (date('i', mktime(0, $model->bkg_trip_duration)) != '00') ? ' ' . date('i', mktime(0, $model->bkg_trip_duration)) . " min" : '';
                    $model->trip_duration_format = $hr . $min;
                    $model->trip_distance_format = $model->bkg_trip_distance . ' Km';

                    $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label = strtoupper($model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label) . " " . $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . " ( ";
                    $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc  = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc . ')';
                }
            }
            if ($model1 != '')
            {
                if ($model1->hasErrors())
                {
                    $success = false;
                    $errors  = $model->getErrors();
                }
            }
            $datareturn = JSONUtil::convertModelToArray($model);
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => $success,
                    'data'    => $_GET,
                    'model'   => array_filter($datareturn),
                    'errors'  => $errors,
                )
            ]);
        });

        $this->onRest('req.post.bookingdetails.render', function () {
            $result = $this->bookingdetailsService();
            $data11 = JSONUtil::convertModelToArray($result['model']);
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => $result['status'],
                    'data'    => $_GET,
                    'model'   => $data11,
                )
            ]);
        });

        $this->onRest('req.get.bookingcancel.render', function () {
            $success      = false;
            $id           = Yii::app()->request->getParam('bkg_id');
            $reason       = Yii::app()->request->getParam('reason');
            $reasonId     = Yii::app()->request->getParam('reason_id');
            $bookingModel = Booking::model()->findByPk($id);
            if ($bookingModel != '')
            {
                $bookingModel->scenario             = 'deny_vendor';
                $bookingModel->bkg_pickup_date_date = date('d-m-Y', strtotime($bookingModel->bkg_pickup_date));
                $bookingModel->bkg_pickup_date_time = date('h:i A', strtotime($bookingModel->bkg_pickup_date));
                $bookingModel->bkg_pickup_date_date = str_replace("-", "/", $bookingModel->bkg_pickup_date_date);
                if ($bookingModel->validate())
                {
                    $result = $this->canbooking1($id, $reason, $reasonId);
                    if ($reasonId != "" && $result)
                    {
                        $success = true;
                        $message = "Booking cancelled successfully";
                    }
                }
                else
                {
                    $message = "Departure time should be atleast 4 hours to cancel. please contact our customer support";
                }
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => $success,
                    'message' => $message,
                    'data'    => $success,)
            ]);
        });

        /**
         * @deprecated since version 15-10-2019
         * @author ramala
         */
        $this->onRest('req.get.bookingupdatenew.render', function () {
            $process_sync_data = Yii::app()->request->getParam('data');
            $data              = CJSON::decode($process_sync_data, true);
            $model             = Booking::model()->with('bkgFromCity', 'bkgToCity')->findByPk($data['bkg_id']);
            if ($model != '')
            {
                $model->scenario             = 'Route';
                $model->attributes           = array_filter($data);
                $model->bkg_user_email       = trim($data['bkg_user_email']);
                $pickupDate                  = date('Y-m-d', strtotime($data['bkg_pickup_date']));
                $model->bkg_pickup_date      = $data['bkg_pickup_date'];
                $model->bkg_pickup_date_date = date('d-m-Y', strtotime($model->bkg_pickup_date));
                $model->bkg_pickup_date_time = date('h:i A', strtotime($model->bkg_pickup_date));
                $model->bkg_pickup_date_date = str_replace("-", "/", $model->bkg_pickup_date_date);
                $rModel                      = Route::model()->getbyCities($model->bkg_from_city_id, $model->bkg_to_city_id);
                if ($rModel)
                {
                    $model->bkg_from_city_id  = $rModel->rut_from_city_id;
                    $model->bkg_to_city_id    = $rModel->rut_to_city_id;
                    $model->bkg_trip_distance = $rModel->rut_estm_distance;
                    $model->bkg_trip_duration = $rModel->rut_estm_time;
                }
                if (!$model->validate())
                {
                    throw new CHttpException("404", "Route not found");
                }
                $amount                      = Rate::model()->fetchRatebyRutnVht($rModel->rut_id, $model->bkg_vehicle_type_id);
                $model->bkg_gozo_base_amount = $model->getAmountExcludingTax($amount, $model->bkg_agent_id);
                $model->getAmountCalculationfromGozoBaseAmount();
                $success                     = $model->save();
                $routeModel                  = BookingRoute::model()->getByBkgid($model->bkg_id);
                $brt_id                      = $routeModel->linkBooking($model);
                $data                        = [];
                if (!$success)
                {
                    $data = ['errors' => $model->getErrors()];
                }
                else
                {
                    $model                                                    = Booking::model()->with('bkgFromCity', 'bkgToCity', 'bkgSvcClassVhcCat')->findByPk($model->bkg_id);
                    $hr                                                       = date('G', mktime(0, $model->bkg_trip_duration)) . " Hr";
                    $min                                                      = (date('i', mktime(0, $model->bkg_trip_duration)) != '00') ? ' ' . date('i', mktime(0, $model->bkg_trip_duration)) . " min" : '';
                    $model->trip_duration_format                              = $hr . $min;
                    $model->trip_distance_format                              = $model->bkg_trip_distance . ' Km';
                    $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label = strtoupper($model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label) . " " . $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . " ( ";
                    $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc  = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc . ')';
                    $datareturn                                               = JSONUtil::convertModelToArray($model);
                    $data                                                     = ['data' => ['model' => $datareturn, 'errors' => $model->getErrors()]];
                }
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                'success' => $success,
                ) + $data
            ]);
        });

        $this->onRest('req.get.send_sms_email_confirm.render', function () {
            $process_sync_data = Yii::app()->request->getParam('data');
            $data              = CJSON::decode($process_sync_data, true);
            $id                = Yii::app()->user->getId();
            $agent_model       = Agents::model()->findByPk($id);
            $bookingId         = $data['bkg_id'];
            $model             = Booking::model()->findByPk($bookingId);
            if ($model->bkg_contact_no != '' && $data['agt_customer_confirm_sms'])
            {
                $msgCom  = new smsWrapper();
                $logType = UserInfo::TYPE_SYSTEM;
                $msgCom->gotBooking($model, $logType);
            }
            if ($agent_model->agt_phone != '' && $data['agt_confirm_sms'])
            {
                $msgCom  = new smsWrapper();
                $logType = UserInfo::TYPE_SYSTEM;
                $msgCom->gotBooking($model, $logType);
            }
            if ($model->bkg_user_email != '' && $data['agt_customer_confirm_email'])
            {
                $emailCom = new emailWrapper();
                $logType  = UserInfo::TYPE_SYSTEM;
                $emailCom->gotBookingemail($model->bkg_id, $logType);
            }
            if ($agent_model->agt_email != '' && $data['agt_confirm_email'])
            {
                $emailCom = new emailWrapper();
                $logType  = UserInfo::TYPE_SYSTEM;
                $emailCom->gotBookingemail($model->bkg_id, $logType);
            }
            $model                       = Booking::model()->with('bkgFromCity', 'bkgToCity', 'bkgSvcClassVhcCat')->findbyPk($bookingId);
            $hr                          = date('G', mktime(0, $model->bkg_trip_duration)) . " Hr";
            $min                         = (date('i', mktime(0, $model->bkg_trip_duration)) != '00') ? ' ' . date('i', mktime(0, $model->bkg_trip_duration)) . " min" : '';
            $model->trip_duration_format = $hr . $min;
            $model->trip_distance_format = $model->bkg_trip_distance . ' Km';

            $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label = strtoupper($model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label) . " " . $model->bkgSvcClassVhcCat->scc_ServiceClass->scc_label . " ( ";
            $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc  = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc . ')';

            $datareturn = JSONUtil::convertModelToArray($model);
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success' => true,
                    'model'   => $datareturn,
                )
            ]);
        });

        $this->onRest('req.post.finalBook.render', function () {
            $datasuccess            = [];
            $datasuccess['success'] = false;
            $process_sync_data      = Yii::app()->request->getParam('data');
            $data                   = json_decode($process_sync_data, true);
            $datasuccess            = $this->finalBook($data);
            if ($datasuccess != '')
            {
                if ($datasuccess['success'])
                {
                    $model                       = $datasuccess['data'];
                    $model                       = Booking::model()->with('bkgFromCity', 'bkgToCity', 'bkgSvcClassVhcCat')->findbyPk($model->bkg_id);
                    $hr                          = date('G', mktime(0, $model->bkg_trip_duration)) . " Hr";
                    $min                         = (date('i', mktime(0, $model->bkg_trip_duration)) != '00') ? ' ' . date('i', mktime(0, $model->bkg_trip_duration)) . " min" : '';
                    $model->trip_duration_format = $hr . $min;
                    $model->trip_distance_format = $model->bkg_trip_distance . ' Km';
                    $model->bkg_total_amount     = $model->bkg_total_amount;
                    $datareturn                  = JSONUtil::convertModelToArray($model);
                }
            }
            return $this->renderJSON([
                'type' => 'raw',
                'data' => array(
                    'success'    => $datasuccess['success'],
                    'model'      => $datareturn,
                    'errors'     => $datasuccess['errors'],
                    'minPayable' => round($model->bkg_total_amount * 15 / 100),
                )
            ]);
        });

        $this->onRest('req.post.receiveAIData.render', function () {
            $process_sync_data = Yii::app()->request->getRawBody();
            $data              = CJSON::decode($process_sync_data, true);
            $dirPath           = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'Exported';
            $file              = $dirPath . "/ddbp_route_data.csv";
            $count             = 0;
            $handle            = fopen($file, 'w');
            fputcsv($handle, array("Row_Id", "additional_surge", "base_capacity", "count_booking", "count_quotation", "manuual_count_booking", "manuual_count_quotation", "Date", "forecast_act", "M_000", "M_010", "M_020", "M_030", "M_040", "M_050", "M_060", "M_070", "M_080", "M_090", "M_100", "M_120", "M_140", "M_170", "M_200", "M_250", "M_300", "Weekday", "total_DP", "total_SP", "Yield", "Source", "Destination"));
            foreach ($data as $priceSurge)
            {
                fputcsv($handle, array(++$count, $priceSurge['Additional Surge'], $priceSurge['Base Capacity'], $priceSurge['Count Booking'], $priceSurge['Count Quotation'], $priceSurge['Manual Count Quotation'], $priceSurge['Manual Count Booking'], date('Y-m-d', strtotime($priceSurge['Date'])), $priceSurge['Forecast-Act.'], $priceSurge['M-000'], $priceSurge['M-010'], $priceSurge['M-020'], $priceSurge['M-030'], $priceSurge['M-040'], $priceSurge['M-050'], $priceSurge['M-060'], $priceSurge['M-070'], $priceSurge['M-080'], $priceSurge['M-090'], $priceSurge['M-100'], $priceSurge['M-120'], $priceSurge['M-140'], $priceSurge['M-170'], $priceSurge['M-200'], $priceSurge['M-250'], $priceSurge['M-300'], $priceSurge['Weekday'], $priceSurge['Total DP'], $priceSurge['Total SP'], $priceSurge['Yield'], $priceSurge['Src'], $priceSurge['Dst']));
            }
            fclose($handle);
            $result = DynamicPriceSurge::model()->importCsvIntoMysql();
            if ($result == 1)
            {
                $result = DynamicPriceSurge::model()->mergeDynamicPriceSurgeData();
            }
        });

        $this->onRest('req.post.receiveAINewData.render', function () {
            $process_sync_data = Yii::app()->request->getRawBody();
            $data              = CJSON::decode($process_sync_data, true);
            $dirPath           = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'Exported';
            $file              = $dirPath . "/ddbp_route_data.csv";
            $count             = 0;
            $handle            = fopen($file, 'w');
            fputcsv($handle, array("Row_Id", "additional_surge", "base_capacity", "count_booking", "count_quotation", "manuual_count_booking", "manuual_count_quotation", "Date", "forecast_act", "M_000", "M_010", "M_020", "M_030", "M_040", "M_050", "M_060", "M_070", "M_080", "M_090", "M_100", "M_120", "M_140", "M_170", "M_200", "M_250", "M_300", "Weekday", "total_DP", "total_SP", "Yield", "Source", "Destination"));
            foreach ($data as $priceSurge)
            {
                fputcsv($handle, array(++$count, $priceSurge['Additional Surge'], $priceSurge['Base Capacity'], $priceSurge['Count Booking'], $priceSurge['Count Quotation'], $priceSurge['Manual Count Quotation'], $priceSurge['Manual Count Booking'], date('Y-m-d', strtotime($priceSurge['Date'])), $priceSurge['Forecast-Act.'], $priceSurge['M-000'], $priceSurge['M-010'], $priceSurge['M-020'], $priceSurge['M-030'], $priceSurge['M-040'], $priceSurge['M-050'], $priceSurge['M-060'], $priceSurge['M-070'], $priceSurge['M-080'], $priceSurge['M-090'], $priceSurge['M-100'], $priceSurge['M-120'], $priceSurge['M-140'], $priceSurge['M-170'], $priceSurge['M-200'], $priceSurge['M-250'], $priceSurge['M-300'], $priceSurge['Weekday'], $priceSurge['Total DP'], $priceSurge['Total SP'], $priceSurge['Yield'], $priceSurge['Src'], $priceSurge['Dst']));
            }
            fclose($handle);
            $result = DynamicPriceSurge::model()->importCsvIntoMysql();
            if ($result == 1)
            {
                $result = DynamicPriceSurge::model()->mergeDynamicPriceSurgeNewData();
            }
        });
    }

    public function canbooking1($id, $reason1, $reasonId)
    {
        $reason   = trim($reason1);
        $model    = Booking::model()->findByPk($id);
        $oldModel = clone $model;
        $userInfo = UserInfo::getInstance();
        $success  = Booking::model()->canBooking($id, $reason, $reasonId, $userInfo);
        if ($success)
        {
            $bkgid   = $success;
            $desc    = "Booking cancelled by agent.";
            $eventid = BookingLog::BOOKING_CANCELLED;
            BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);
            return true;
        }
        return false;
    }

    public function cabrateService()
    {
        $bkgid = Yii::app()->request->getParam('bkg_id');
        $model = new BookingTemp;
        if ($bkgid != '')
        {
            $model = BookingTemp::model()->findbyPk($bkgid);
        }
        $model->bkg_user_id      = Yii::app()->request->getParam('bkg_user_id');
        $model->bkg_from_city_id = Yii::app()->request->getParam('bkg_from_city_id');
        $model->bkg_to_city_id   = Yii::app()->request->getParam('bkg_to_city_id');
        $model->bkg_route_id     = Route::model()->getRutidbyCities($model->bkg_from_city_id, $model->bkg_to_city_id);
        $date1                   = Yii::app()->request->getParam('bkg_pickup_date_date');
        $time1                   = Yii::app()->request->getParam('bkg_pickup_date_time');
        if ($date1 != "" && $time1 != "")
        {
            $date                   = date('Y-m-d', strtotime($date1));
            $time                   = date('H:i:00', strtotime($time1));
            $model->bkg_pickup_date = $date . " " . $time;
            $model->bkg_pickup_time = $time;
        }
        $model->bkg_pickup_date_date     = str_replace("-", "/", $date1);
        $model->bkg_user_ip              = \Filter::getUserIP();
        $model->bkg_user_device          = "old consumer app";
        $model->bkg_user_ip              = \Filter::getUserIP();
        $cityinfo                        = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
        $model->bkg_user_city            = $cityinfo['city'];
        $model->bkg_user_country         = $cityinfo['country'];
        $model->bkg_user_device          = UserLog::model()->getDevice();
        $model->bkg_platform             = Booking::Platform_App;
        $fcityname                       = Cities::getName(Yii::app()->request->getParam('bkg_from_city_id'));
        $tcityname                       = Cities::getName(Yii::app()->request->getParam('bkg_to_city_id'));
        $fcityname                       = str_replace(" ", "%20", $fcityname);
        $tcityname                       = str_replace(" ", "%20", $tcityname);
        $from_city_geocode               = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . $fcityname . '&sensor=false');
        $to_city_geocode                 = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . $tcityname . '&sensor=false');
        $arr                             = json_decode($from_city_geocode);
        $from_lat_long                   = $arr->results[0]->geometry->location->lat . "," . $arr->results[0]->geometry->location->lng;
        $arr1                            = json_decode($to_city_geocode);
        $to_lat_long                     = $arr1->results[0]->geometry->location->lat . "," . $arr1->results[0]->geometry->location->lng;
        $geocodeFrom                     = file_get_contents('http://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $from_lat_long . '&destinations=' . $to_lat_long . '&sensor=false&units=metric&mode=driving');
        $arr_distance                    = json_decode($geocodeFrom);
        $model->trip_distance_format     = $arr_distance->rows[0]->elements[0]->distance->text;
        $model->trip_duration_format     = $arr_distance->rows[0]->elements[0]->duration->text;
        $model->bkg_trip_distance        = round(($arr_distance->rows[0]->elements[0]->distance->value) / 1000);
        $model->bkg_trip_duration        = round(($arr_distance->rows[0]->elements[0]->duration->value) / 60);
        $model->scenario                 = 'step1';
        $model->bkg_user_last_updated_on = new CDbExpression('NOW()');
        $model->save();
        $model->bkg_booking_type         = 1;
        $bktypArr                        = ['1' => 'OW', '2' => 'RT'];
        $booking_id                      = $bktypArr[$model->bkg_booking_type] . date('Y') . str_pad($model->bkg_id, 4, 0, STR_PAD_LEFT);
        $model->bkg_booking_id           = $booking_id;
        $model->bkg_user_last_updated_on = new CDbExpression('NOW()');
        $model->save();
        return $model;
    }

    public function bookingdetailsService()
    {
        $bkgid   = Yii::app()->request->getParam('bkg_id');
        $model   = Booking::model()->findbyPk($bkgid);
        $vcode   = Yii::app()->request->getParam('bkg_verification_code');
        $status1 = $model->confirmVerification($vcode);
        if ($status1)
        {
            $status = 'true';
        }
        else
        {
            $status = 'false';
        }
        $result = ['model' => $model, 'status' => $status];
        return $result;
    }

    public function showdetailsService()
    {
        $model                                  = BookingTemp::model()->findbyPk(Yii::app()->request->getParam('bkg_id'));
        $model->bkgUserInfo->bkg_user_fname     = Yii::app()->request->getParam('bkg_user_name');
        $model->bkgUserInfo->bkg_user_lname     = Yii::app()->request->getParam('bkg_user_lname');
        $model->bkgUserInfo->bkg_country_code   = Yii::app()->request->getParam('bkg_country_code');
        $model->bkgUserInfo->bkg_contact_no     = Yii::app()->request->getParam('bkg_contact_no');
        $model->bkgUserInfo->bkg_alt_contact_no = Yii::app()->request->getParam('bkg_alternate_contact');
        $model->bkgUserInfo->bkg_user_email     = Yii::app()->request->getParam('bkg_user_email');
        $model->bkgAddInfo->bkg_info_source     = Yii::app()->request->getParam('bkg_info_source');
        $model->scenario                        = 'step4';
        if ($model->validate())
        {
            $model->bkgUserInfo->bkg_user_last_updated_on = new CDbExpression('NOW()');
            $model->bkgUserInfo->save();
            $model->bkgAddInfo->save();
        }
        return $model;
    }

    public function bookingList()
    {
        $userid = Yii::app()->user->getAgentId();
        $sort   = Yii::app()->request->getParam('sort');
        $model  = Booking::model()->fetchListbyAgentforMob($userid, $sort);
        return $model;
    }

    public function bookingConfirmStep1Service($data)
    {
        $model                       = new Booking();
        $model->scenario             = "cabRate";
        $model->attributes           = array_filter($data);
        $model->bkg_user_email       = trim($data['bkg_user_email']);
        $model->bkg_booking_type     = 1;
        $model->bkg_platform         = Booking::Platform_App;
        $pickupDate                  = $model->bkg_pickup_date_date;
        $model->bkg_pickup_date      = $model->bkg_pickup_date_date . " " . $model->bkg_pickup_date_time;
        $model->bkg_pickup_time      = $model->bkg_pickup_date_time;
        $model->bkg_pickup_date_date = date('d-m-Y', strtotime($model->bkg_pickup_date_date));
        $model->bkg_pickup_date_time = date('h:i A', strtotime($model->bkg_pickup_date_time));
        $model->bkg_pickup_date_date = str_replace("-", "/", $model->bkg_pickup_date_date);
        $data                        = ['success' => false, 'data' => $model, "errors" => $model->getErrors()];
        if ($model->validate())
        {
            if ($model->bkg_id == '')
            {
                $model->bkg_id = null;
            }
            $rModel = Route::model()->getbyCities($model->bkg_from_city_id, $model->bkg_to_city_id);
            if ($rModel)
            {
                $model->bkg_from_city_id  = $rModel->rut_from_city_id;
                $model->bkg_to_city_id    = $rModel->rut_to_city_id;
                $model->bkg_trip_distance = $rModel->rut_estm_distance;
                $model->bkg_trip_duration = $rModel->rut_estm_time;
            }
            if (!$model->validate())
            {
                throw new CHttpException("404", "Route not found");
            }
            $amount                      = Rate::model()->fetchRatebyRutnVht($rModel->rut_id, $model->bkg_vehicle_type_id);
            $model->bkg_gozo_base_amount = $model->getAmountExcludingTax($amount, $model->bkg_agent_id);
            $model->getAmountCalculationfromGozoBaseAmount();
            $model->bkg_user_ip          = \Filter::getUserIP();
            $cityinfo                    = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
            $model->bkg_user_city        = $cityinfo['city'];
            $model->bkg_user_country     = $cityinfo['country'];
            $model->bkg_user_device      = UserLog::model()->getDevice();
            $model->scenario             = 'cabRate';
            $model->bkg_booking_id       = 'temp';
            $transaction                 = Yii::app()->db->beginTransaction();
            if ($model->validate())
            {
                try
                {
                    $model->bkg_user_last_updated_on = new CDbExpression('NOW()');
                    $tmodel                          = Terms::model()->getText(1);
                    $model->bkg_tnc_id               = $tmodel->tnc_id;
                    $model->bkg_tnc_time             = new CDbExpression('NOW()');
                    if (!$model->save())
                    {
                        throw new Exception("Failed to create booking", 100);
                    }
                    $booking_id            = Booking::model()->generateBookingid($model);
                    $model->bkg_booking_id = $booking_id;
                    if (!$model->save())
                    {
                        throw new Exception("Failed to create booking", 101);
                    }
                    $bkgid      = $model->bkg_id;
                    $routeModel = new BookingRoute();
                    $brt_id     = $routeModel->linkBooking($model);
                    $desc       = "Booking created by agent.";
                    $userInfo   = UserInfo::getInstance();

                    $eventid = BookingLog::BOOKING_CREATED;
                    BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid);
                    if (Yii::app()->user->isGuest)
                    {
                        $model->sendVerificationCode();
                    }
                    $transaction->commit();
                }
                catch (Exception $e)
                {
                    $model->addError('bkg_id', $e->getMessage());
                    $transaction->rollback();
                }
            }
            $success = !$model->hasErrors();
            if ($success)
            {
                $data = ['success' => true, 'data' => $model];
            }
            else
            {
                $data = ['success' => false, 'data' => $model, "errors" => $model->getErrors()];
            }
        }
        else
        {
            $data = ['success' => false, 'data' => $model, "errors" => $model->getErrors()];
        }
        return $data;
    }

    public function process3newService($data)
    {
        $model                 = Booking::model()->findbyPk($data['bkg_id']);
        $model->attributes     = $data;
        $model->bkg_user_email = trim($data['bkg_user_email']);
        $model->agentRateChange($data['old_amount']);
        //$model->bkg_agent_markup = $model->bkg_agent_markup + ($model->bkg_total_amount - $data['old_amount']);
        $model->scenario       = 'stepMobile3';
        $successArr            = ['success' => false, 'model' => $model];
        if ($model->validate())
        {
            if ($model->bkg_user_id == NULL || $model->bkg_user_id == '')
            {
                $userModel = Users::model()->linkUserByEmail($model->bkg_id, Booking::Platform_App);
                if ($userModel)
                {
                    $model->bkg_user_id = $userModel->user_id;
                    $model->save();
                }
            }
            $model->bkg_user_last_updated_on = new CDbExpression('NOW()');
            $model->bkg_status               = 2;
            $model->save();
            $routeModel                      = BookingRoute::model()->getByBkgid($model->bkg_id);
            $brt_id                          = $routeModel->linkBooking($model);
            $successArr                      = ['success' => true, 'model' => $model];
        }
        else
        {
            $successArr = ['success' => false, 'errors' => $model->errors];
        }
        return $successArr;
    }

    /**
     * 
     * @deprecated since version 15-10-2019
     * @author Suvajit Chakraborty
     */
    public function createBooking1($data, $routes)
    {
        $model                   = $this->mapping(array_filter($data));
        $model->scenario         = 'agentbooking';
        $model->bkg_booking_type = 1;
        $model->bkg_agent_id     = 39;
        $model->bkg_platform     = Booking::Platform_Agent;
        $data                    = ['success' => false, 'errors' => $model->getErrors()];
        if ($model->validate())
        {
            if ($model->bkg_id == '')
            {
                $model->bkg_id = null;
            }
            $rModel = Route::model()->getbyCities($model->bkg_from_city_id, $model->bkg_to_city_id);
            if ($rModel)
            {
                $model->bkg_from_city_id  = $rModel->rut_from_city_id;
                $model->bkg_to_city_id    = $rModel->rut_to_city_id;
                $model->bkg_route_id      = $rModel->rut_id;
                $model->bkg_trip_distance = $rModel->rut_estm_distance;
                $model->bkg_trip_duration = $rModel->rut_estm_time;
            }
            if (!$model->validate())
            {
                throw new CHttpException("404", "Route not found");
            }
            $amount      = Rate::model()->fetchRatebyRutnVht($model->bkg_route_id, $model->bkg_vehicle_type_id);
            $excl_amount = Rate::model()->fetchExclRatebyRutnVht($model->bkg_route_id, $model->bkg_vehicle_type_id);
            if ($amount > 0)
            {
                $model->bkg_amount        = $amount;
                $model->bkg_net_charge    = $amount;
                $model->bkg_vendor_amount = round($excl_amount * 0.9);
            }
            $cityinfo                = UserLog::model()->getCitynCountrycodefromIP($model->bkg_user_ip);
            $model->bkg_user_city    = $cityinfo['city'];
            $model->bkg_user_country = $cityinfo['country'];
            $model->bkg_status       = 2;
            $model->bkg_booking_id   = 'temp';
            $transaction             = Yii::app()->db->beginTransaction();
            if ($model->validate())
            {
                try
                {
                    $model->save();
                    $booking_id            = Booking::model()->generateBookingid($model);
                    $model->bkg_booking_id = $booking_id;
                    $model->save();
                    if ($model->bkg_user_id == NULL || $model->bkg_user_id == '')
                    {
                        $userModel = Users::model()->linkUserByEmail($model->bkg_id, Booking::Platform_Agent);
                        if ($userModel)
                        {
                            $model->bkg_user_id = $userModel->user_id;
                            $model->save();
                        }
                    }
                    if ($model->bkg_contact_no != '')
                    {
                        $msgCom = new smsWrapper();
                        $msgCom->gotBooking($model);
                    }
                    if ($model->bkg_user_email != '')
                    {
                        $emailCom = new emailWrapper();
                        $emailCom->gotBookingemail($model->bkg_id);
                    }
                    $model->bkg_user_last_updated_on = new CDbExpression('NOW()');
                    if (!$model->save())
                    {
                        throw new Exception("Failed to create booking", 101);
                    }
                    $bkgid    = $model->bkg_id;
                    $desc     = "Booking created by agent";
                    $userInfo = UserInfo::getInstance();

                    $eventid = BookingLog::BOOKING_CREATED;
                    BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid);
                    $transaction->commit();
                }
                catch (Exception $e)
                {
                    $model->addError('booking_id', $e->getMessage());
                    $transaction->rollback();
                }
            }
            $success = !$model->hasErrors();
            if ($success)
            {
                $model                       = Booking::model()->with('bkgFromCity', 'bkgToCity', 'bkgSvcClassVhcCat')->findbyPk($model->bkg_id);
                $hr                          = date('G', mktime(0, $model->bkg_trip_duration)) . " Hr";
                $min                         = (date('i', mktime(0, $model->bkg_trip_duration)) != '00') ? ' ' . date('i', mktime(0, $model->bkg_trip_duration)) . " min" : '';
                $model->trip_duration_format = $hr . $min;
                $model->trip_distance_format = $model->bkg_trip_distance . ' Km';
                $datareturn                  = $this->reverseMapping($model);
                $data                        = ['success' => true, 'message' => 'Booking created successfully', 'model' => array_filter($datareturn)];
            }
            else
            {
                $data = ['success' => false, 'errors' => $model->getErrors()];
            }
        }
        else
        {
            $data = ['success' => false, 'errors' => $model->getErrors()];
        }
        return $data;
    }

    public function createBooking($data, $routes, $tripType, $partnerTotalAmount = 0)
    {
        $model          = $this->mapping($data);
        //$model->scenario = 'agentbooking';
        $apkVersion     = $data['apkVersion'];
        $advanceConfirm = 0;
        if (isset($data['confirmOnAdvance']))
        {
            $advanceConfirm = $data['confirmOnAdvance'];
        }
        $model->bkg_booking_type              = $tripType;
        $userInfo                             = UserInfo::getInstance();
        $model->bkg_agent_id                  = $userInfo->userId;
        $model->bkgAddInfo->bkg_info_source   = 21; //'Agent'
        $model->bkgTrail->bkg_platform        = Booking::Platform_Agent;
        $cityinfo                             = UserLog::model()->getCitynCountrycodefromIP($model->bkgTrail->bkg_user_ip);
        $model->bkgUserInfo->bkg_user_city    = $cityinfo['city'];
        $model->bkgUserInfo->bkg_user_country = $cityinfo['country'];

        $data = ['success' => false, 'data' => $model, "errors" => $model->getErrors()];
        //$result	 = CActiveForm::validate($model);
        //check credit limit exceeded or not
        //$isRechargeAccount = AgentTransactions::model()->checkCreditLimit($model->bkg_agent_id, $routes, $model->bkg_booking_type, $data->advanceReceived, $requestData, 3);
        //if ($isRechargeAccount)
        //{
        //	$model->addError('bkg_id', "Booking failed as your credit limit exceeded, please recharge.");
        //	$data = ['success' => false, "errors" => $model->getErrors()];
        //}
        //check credit limit exceeded or not
        //if ($result == '[]' && !$isRechargeAccount)

        if ($model->bkg_id == '')
        {
            $model->bkg_id = null;
        }
        //$carType = VehicleTypes::model()->getVehicleTypeById($model->bkg_vehicle_type_id);
        $carType = SvcClassVhcCat::model()->vehicleCategoryReverseMapping($model->bkg_vehicle_type_id);
        $route   = [];
        foreach ($routes as $key => $val)
        {
            $routeModel                       = new BookingRoute();
            $routeModel->brt_from_city_id     = $val->pickup_city;
            $routeModel->brt_to_city_id       = $val->drop_city;
            $routeModel->brt_pickup_datetime  = $val->date;
            $routeModel->brt_pickup_date_date = date('d/m/Y', strtotime($val->date)); //DateTimeFormat::DateTimeToDatePicker($val->date);
            $routeModel->brt_pickup_date_time = date('h:i A', strtotime($val->date));
            $routeModel->brt_to_location      = $val->drop_address;
            $routeModel->brt_from_location    = $val->pickup_address;
            $routeModel->brt_to_pincode       = $val->drop_pincode;
            $routeModel->brt_from_pincode     = $val->pickup_pincode;

            $routeModel->brt_from_latitude  = $val->pickupLatitude;
            $routeModel->brt_from_longitude = $val->pickupLongitude;
            $routeModel->brt_to_latitude    = $val->dropLatitude;
            $routeModel->brt_to_longitude   = $val->dropLongitude;
            $route[]                        = $routeModel;
        }


        $returnDateTime = null;
        if ($model->bkg_booking_type == 2)
        {
            $lastRoute      = $route[count($route) - 1];
            $picktime       = $lastRoute->brt_pickup_datetime;
            $routeDuration  = Route::model()->getRouteDurationbyCities($lastRoute->brt_from_city_id, $lastRoute->brt_to_city_id);
            $returnDateTime = date('Y-m-d H:i:s', strtotime($picktime . ' + ' . $routeDuration . ' minute'));
        }

        $partnerId                      = $userInfo->userId;
        $quote                          = new Quote();
        $quote->routes                  = $route;
        $quote->tripType                = $model->bkg_booking_type;
        $quote->partnerId               = $partnerId;
        $quote->quoteDate               = $model->bkg_create_date;
        $quote->pickupDate              = $routeModel->brt_pickup_datetime;
        $quote->returnDate              = $returnDateTime;
        $quote->sourceQuotation         = Quote::Platform_Agent;
        $quote->isB2Cbooking            = false;
        $quote->isDynamicSurgeAvialable = DynamicPriceAvailability::Available;
        $quote->setCabTypeArr();
        $quotData                       = $quote->getQuote($carType);
        $arrQuot                        = $quotData[$carType];

        //new changes
        $routeRates = $arrQuot->routeRates;

        $routeDistance = $arrQuot->routeDistance;
        $routeDuration = $arrQuot->routeDuration;
        $agtType       = Agents::model()->findByPk($model->bkg_agent_id)->agt_type;
        if ($agtType == 0 || $agtType == 1)
        {
            $routeRates = Agents::model()->getBaseDiscFare($arrQuot->routeRates, $agtType, $model->bkg_agent_id);
            // $arrQuot  = $arrQuote;
        }
        //new changes

        if (!$arrQuot->success)
        {
            throw new Exception("Request cannot be processed", 102);
        }
        $rCount                    = count($routes);
        $model->bkg_from_city_id   = $route[0]->brt_from_city_id;
        $model->bkg_to_city_id     = $route[$rCount - 1]->brt_to_city_id;
        $model->bkg_trip_distance  = $routeDistance->quotedDistance; // $qt['routeData']['quoted_km'];
        $model->bkg_trip_duration  = (string) $routeDuration->tripDuration; // $qt['routeData']['days']['totalMin'];
        $model->bkg_pickup_address = $routes[0]->pickup_address;
        $model->bkg_drop_address   = $routes[$rCount - 1]->drop_address;

        $model->bkg_pickup_lat  = ($routes[0]->pickupLatitude != '') ? $routes[0]->pickupLatitude : NULL;
        $model->bkg_pickup_long = ($routes[0]->pickupLongitude != '') ? $routes[0]->pickupLongitude : NULL;

        foreach ($routes as $val)
        {
            $dropLat  = $val->dropLatitude;
            $dropLong = $val->dropLongitude;
        }
        $model->bkg_dropup_lat  = ($dropLat != '') ? $dropLat : NULL;
        $model->bkg_dropup_long = ($dropLat != '') ? $dropLong : NULL;

//			$model->bkg_pickup_pincode			 = $routes[0]->pickup_pincode;
//			$model->bkg_drop_pincode			 = $routes[$rCount - 1]->drop_pincode;
        $model->bkg_pickup_date                         = $routeDuration->fromDate; //  $qt['routeData']['startTripDate'];
        $model->bkgInvoice->bkg_chargeable_distance     = $routeDistance->quotedDistance; // $arrQuot['chargeableDistance'];
        $model->bkgTrack->bkg_garage_time               = $routeDuration->totalMinutes + $routeDuration->garageTimeEnd + $routeDuration->garageTimeStart;
//			$model->bkg_pickup_time				 = date('H:i:00', strtotime($routeDuration->pickupTime));
        $model->bkgInvoice->bkg_driver_allowance_amount = $routeRates->driverAllowance; //$arrQuot['driverAllowance'];
        $model->bkgInvoice->bkg_gozo_base_amount        = round($routeRates->baseAmount);
        $model->bkgInvoice->bkg_base_amount             = round($routeRates->baseAmount);
        $model->bkgInvoice->bkg_rate_per_km_extra       = round($routeRates->ratePerKM);
        $model->bkgInvoice->bkg_rate_per_km             = round($routeRates->costPerKM);

        $model->bkgInvoice->bkg_surge_differentiate_amount = $routeRates->differentiateSurgeAmount;

        $model->bkgInvoice->bkg_night_pickup_included = $routeRates->isNightPickupIncluded;
        $model->bkgInvoice->bkg_night_drop_included   = $routeRates->isNightDropIncluded;

        if ($model->bkg_agent_id != 123)
        {
            $model->bkgInvoice->bkg_toll_tax              = round($routeRates->tollTaxAmount | 0);
            $model->bkgInvoice->bkg_state_tax             = round($routeRates->stateTax | 0);
            $model->bkgInvoice->bkg_is_toll_tax_included  = $routeRates->isTollIncluded | 0;
            $model->bkgInvoice->bkg_is_state_tax_included = $routeRates->isStateTaxIncluded | 0;
            $model->bkgInvoice->bkg_vendor_amount         = round($routeRates->vendorAmount);
            $model->bkgInvoice->bkg_quoted_vendor_amount  = round($routeRates->vendorAmount);
        }
        else
        {
            $model->bkgInvoice->bkg_toll_tax  = 0;
            $model->bkgInvoice->bkg_state_tax = 0;
            if (round($routeRates->tollTaxAmount | 0) == 0 && round($routeRates->stateTax | 0) == 0)
            {
                $model->bkgInvoice->bkg_is_toll_tax_included  = $routeRates->isTollIncluded | 0;
                $model->bkgInvoice->bkg_is_state_tax_included = $routeRates->isStateTaxIncluded | 0;
            }
            else
            {
                $model->bkgInvoice->bkg_is_toll_tax_included  = 0;
                $model->bkgInvoice->bkg_is_state_tax_included = 0;
            }
            $model->bkgInvoice->bkg_vendor_amount        = (round($routeRates->vendorAmount) - round($model->bkgInvoice->bkg_toll_tax) - round($model->bkgInvoice->bkg_state_tax));
            $model->bkgInvoice->bkg_quoted_vendor_amount = (round($routeRates->vendorAmount) - round($model->bkgInvoice->bkg_toll_tax) - round($model->bkgInvoice->bkg_state_tax));
        }
        if ($model->bkg_booking_type == 2 || $model->bkg_booking_type == 3)
        {
            $returnDate                  = $routeDuration->toDate;
            $model->bkg_return_date_date = DateTimeFormat::DateTimeToDatePicker($returnDate);
            $model->bkg_return_date_time = date('H:i:00', strtotime($returnDate));
            $model->bkg_return_date      = $returnDate;
//				$model->bkg_return_time		 = date('H:i:00', strtotime($returnDate));
        }
        $model->bkgInvoice->populateAmount(true, false, true, true, $model->bkg_agent_id);
//			$model->calculateVendorAmount();
        if ($model->bkg_agent_id != '')
        {
            $agtModel = Agents::model()->findByPk($model->bkg_agent_id);
            if ($agtModel->agt_city == 30706)
            {
                $model->bkgInvoice->bkg_cgst = Yii::app()->params['cgst'];
                $model->bkgInvoice->bkg_sgst = Yii::app()->params['sgst'];
                $model->bkgInvoice->bkg_igst = 0;
            }
            else
            {
                $model->bkgInvoice->bkg_igst = Yii::app()->params['igst'];
                $model->bkgInvoice->bkg_cgst = 0;
                $model->bkgInvoice->bkg_sgst = 0;
            }
        }
        else
        {
            if ($model->bkg_from_city_id == 30706)
            {
                $model->bkgInvoice->bkg_cgst = Yii::app()->params['cgst'];
                $model->bkgInvoice->bkg_sgst = Yii::app()->params['sgst'];
                $model->bkgInvoice->bkg_igst = 0;
            }
            else
            {
                $model->bkgInvoice->bkg_igst = Yii::app()->params['igst'];
                $model->bkgInvoice->bkg_cgst = 0;
                $model->bkgInvoice->bkg_sgst = 0;
            }
        }
        $partnerTotalAmount = (float) $partnerTotalAmount;
        if ($partnerTotalAmount == 0 || $partnerTotalAmount == null)
        {
            $partnerTotalAmount = $model->bkgInvoice->bkg_total_amount;
        }
        if ($model->bkgInvoice->bkg_total_amount <= $partnerTotalAmount)
        {
            $model->bkgUserInfo->bkg_user_last_updated_on = new CDbExpression('NOW()');
            $model->scenario                              = 'cabRateAgent';
            $model->bkg_booking_id                        = 'temp';
            $difference                                   = $model->bkgInvoice->bkg_total_amount - $partnerTotalAmount;
            if ($difference > 0)
            {
                $newDifference                      = ((100 - Yii::app()->params['gst']) / 100) * $difference;
                $model->bkgInvoice->bkg_base_amount = (int) round($model->bkgInvoice->bkg_base_amount - $newDifference);
            }
            if ($difference < 0)
            {
                $newDifference                      = ((100 - Yii::app()->params['gst']) / 100) * (-1) * $difference;
                $model->bkgInvoice->bkg_base_amount = (int) round($model->bkgInvoice->bkg_base_amount + $newDifference);
            }
//				$model->populateAmount();
//				$model->calculateVendorAmount();
            $model->bkgInvoice->populateAmount(true, false, true, true, $model->bkg_agent_id);
            if ($model->validate())
            {
                $transaction = DBUtil::beginTransaction();
                try
                {
                    $sendConf = false;
                    if ($model->bkgUserInfo->bkg_user_id == NULL || $model->bkgUserInfo->bkg_user_id == '')
                    {
                        $userModel = Users::model()->linkUserByEmail($model->bkg_id, Booking::Platform_Agent);
                        if ($userModel)
                        {
                            $model->bkgUserInfo->bkg_user_id = $userModel->user_id;
                        }
                    }
                    $model->bkgUserInfo->bkg_user_last_updated_on = new CDbExpression('NOW()');
                    $tmodel                                       = Terms::model()->getText(1);
                    $model->bkgTrail->bkg_tnc_id                  = $tmodel->tnc_id;
                    $model->bkgTrail->bkg_tnc_time                = new CDbExpression('NOW()');
                    $transAmount                                  = $model->bkgInvoice->bkg_corporate_credit;
                    $model->bkgInvoice->bkg_corporate_credit      = 0;
                    if ($model->bkg_trip_distance <= 300)
                    {
                        $model->bkgPref->bkg_cng_allowed = 1;
                    }
                    $model->bkg_vehicle_type_id = $carType;
                    if (!$model->save())
                    {
                        throw new Exception("Failed to create booking", 101);
                    }
                    $booking_id                              = Booking::model()->generateBookingid($model);
                    $model->bkg_booking_id                   = $booking_id;
                    $model->bkgTrail->btr_bkg_id             = $model->bkg_id;
                    $model->bkgTrail->setPaymentExpiryTime();
                    $isRealtedBooking                        = $model->findRelatedBooking($model->bkg_id);
                    $model->bkgTrail->bkg_is_related_booking = ($isRealtedBooking) ? 1 : 0;
                    $userInfo                                = UserInfo::getInstance();
                    $model->bkgTrail->bkg_create_user_type   = $userInfo->userType;
                    $model->bkgTrail->bkg_create_user_id     = $userInfo->userId;
                    $model->bkgTrail->bkg_create_type        = BookingTrail::CreateType_Self;
                    if (!$model->save())
                    {
                        throw new Exception("Failed to create booking", 101);
                    }
                    $model->bkgInvoice->biv_bkg_id = $model->bkg_id;
                    if (!$model->bkgInvoice->save())
                    {
                        throw new Exception("Failed to create booking", 101);
                    }
                    $model->bkgPf->bpf_bkg_id = $model->bkg_id;
                    if (!$model->bkgPf->updateFromQuote($arrQuot))
                    {
                        throw new Exception("Failed to create booking", 101);
                    }
                    $model->bkgUserInfo->bui_bkg_id = $model->bkg_id;
                    if (!$model->bkgUserInfo->save())
                    {
                        throw new Exception("Failed to create booking", 101);
                    }
                    $model->bkgTrack->btk_bkg_id = $model->bkg_id;
                    if (!$model->bkgTrack->save())
                    {
                        throw new Exception("Failed to create booking", 101);
                    }
                    $model->bkgPref->bpr_bkg_id = $model->bkg_id;
                    if (!$model->bkgPref->save())
                    {
                        throw new Exception("Failed to create booking", 101);
                    }
                    $resultInvoice = CActiveForm::validate($model->bkgTrail);
                    if (!$model->bkgTrail->save())
                    {
                        throw new Exception("Failed to create booking", 101);
                    }
                    $model->bkgAddInfo->bad_bkg_id = $model->bkg_id;
                    if (!$model->bkgAddInfo->save())
                    {
                        throw new Exception("Failed to create booking", 101);
                    }
                    $bookingCab                    = new BookingCab('matchtrip');
                    $bookingCab->bcb_vendor_amount = $model->bkgInvoice->bkg_vendor_amount;
                    $bookingCab->bcb_bkg_id1       = $model->bkg_id;
                    $bookingCab->save();
                    $model->bkg_bcb_id             = $bookingCab->bcb_id;
                    $model->update();
                    foreach ($route as $rmodel)
                    {
                        $rmodel->brt_bkg_id = $model->bkg_id;
                        $rmodel->brt_bcb_id = $bookingCab->bcb_id;
                        $rmodel->save();
                    }
                    BookingRoute::model()->setBookingCabStartEndTime($bookingCab->bcb_id, $bookingCab->bcb_bkg_id1);
                    $agentsModel                       = Agents::model()->findByPk($model->bkg_agent_id);
                    $model->bkgUserInfo->bkg_crp_name  = ($agentsModel->agt_copybooking_name != '') ? $agentsModel->agt_copybooking_name : $agentsModel->agt_fname . " " . $agentsModel->agt_lname;
                    // $bookingPref->bkg_crp_send_email = $agentsModel->agt_copybooking_ismail;
                    //   $bookingPref->bkg_crp_send_sms = $agentsModel->agt_copybooking_issms;
                    $model->bkgUserInfo->bkg_crp_email = ($agentsModel->agt_copybooking_email != '') ? $agentsModel->agt_copybooking_email : '';
                    $model->bkgUserInfo->bkg_crp_phone = ($agentsModel->agt_copybooking_phone != '') ? $agentsModel->agt_copybooking_phone : '';
                    if ($agentsModel->agt_trvl_sendupdate == 1)
                    {
                        $model->bkgPref->bkg_trv_send_email = $agentsModel->agt_trvl_isemail;
                        $model->bkgPref->bkg_trv_send_sms   = $agentsModel->agt_trvl_issms;
                    }
                    else
                    {
                        $model->bkgPref->bkg_trv_send_email = 0;
                        $model->bkgPref->bkg_trv_send_sms   = 0;
                    }
                    $model->bkgPref->bkg_trip_otp_required = $agentsModel->agt_otp_required;
                    $model->bkgUserInfo->save();

                    $bkgBookingUser     = BookingUser::model()->saveVerificationOtp($model->bkg_id);
                    $model->bkgUserInfo = $bkgBookingUser;
                    $model->bkgPref->save();

                    //Update partner commission
                    $model->bkgInvoice->refresh();
                    $model->bkgInvoice->calculateDues();
                    $model->bkgInvoice->save();

                    //booking pref
                    //agentnotifydetails
                    $arrEvents = AgentMessages::getEvents();
                    foreach ($arrEvents as $key => $value)
                    {
                        $bookingMessages = BookingMessages::model()->getByEventAndBookingId($model->bkg_id, $key);
                        if ($bookingMessages == '')
                        {
                            $bookingMessages                 = new BookingMessages();
                            $bookingMessages->getMessageDefaults($model->bkg_agent_id, $key);
                            $bookingMessages->bkg_booking_id = $model->bkg_id;
                            $bookingMessages->bkg_event_id   = $key;
                            $bookingMessages->save();
                        }
                    }
                    //agentnotifydetails

                    if ($model->bkg_status == 1)
                    {
                        $model->bkgTrack = BookingTrack::model()->sendTripOtp($model->bkg_id, $sendOtp         = false);
                        $model->bkgTrack->save();
                        $logType         = UserInfo::TYPE_SYSTEM;
                        $sendConf        = true;
                        $amount          = $transAmount | 0; //Credit added by agent;
                        $desc            = "Partner Credits Used";
                        if ($amount > 0)
                        {
                            $bankLedgerID = PaymentType::model()->ledgerList(PaymentType::TYPE_AGENT_CORP_CREDIT);
                            $isUpdated    = $model->updateAdvance($amount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, UserInfo::getInstance(), null, "Partner Wallet Used");
                            if (!$isUpdated)
                            {
                                throw new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
                            }
                        }

//****************************For agent only direct confirm***************************************//
                        //if ($model->bkgInvoice->getAdvanceReceived() > 0 && $model->bkg_status == 1)
                        //{
                        //$model->confirmBooking($logType);
                        $model->confirm(true, true, $model->bkg_id);
                        //}
                    }
                    $bkgid          = $model->bkg_id;
                    $processedRoute = BookingLog::model()->logRouteProcessed($arrQuot, $bkgid);
                    $desc           = "Booking created by agent - $processedRoute";

                    $eventid = BookingLog::BOOKING_CREATED;
                    BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid);

                    DBUtil::commitTransaction($transaction);
                    if ($sendConf)
                    {
                        //   $model->sendConfirmation($logType);
                        /*
                          $emailWrapper	 = new emailWrapper();
                          $emailWrapper->gotBookingemail($model->bkg_id, $logType);
                          $emailWrapper->gotBookingAgentUser($model->bkg_id);
                          $msgCom			 = new smsWrapper();
                          $msgCom->gotBooking($model, $logType);
                         * 
                         */
                    }
                }
                catch (Exception $e)
                {
                    if ($e->getCode() == ReturnSet::ERROR_VALIDATION)
                    {
                        $model->addErrors(json_decode($e->getMessage()));
                    }
                    else
                    {
                        $model->addError('bkg_id', $e->getMessage());
                    }
                    Logger::create("Error log: ", $e->getMessage(), CLogger::LEVEL_TRACE);
                    DBUtil::rollbackTransaction($transaction);
                }
            }
            $success = !$model->hasErrors();
            if ($success)
            {
                $model                       = Booking::model()->with('bkgFromCity', 'bkgToCity', 'bkgSvcClassVhcCat')->findbyPk($model->bkg_id);
                $model->trip_duration_format = $model->bkg_trip_duration . ' mins';
                $model->trip_distance_format = $model->bkg_trip_distance . ' Km';
                $agentGatewayStatus          = BookingSub::model()->getAgentGatewayStatus($model->bkg_id);
                $gatewayStatus               = $agentGatewayStatus['gateway'];
                $activeVersion               = Config::get("Version.Android.agent"); //Yii::app()->params['versionCheck']['agent'];
                if ($activeVersion <= $apkVersion)
                {
                    $datareturn = $this->newReverseMapping($model);
                }
                else
                {
                    $datareturn = $this->reverseMapping($model);
                }
                if ($gatewayStatus == 1)
                {
                    $hash                  = Yii::app()->shortHash->hash($model->bkg_id);
                    $paymentLink           = $_SERVER['HTTP_HOST'] . '/bkpn/' . $model->bkg_id . '/' . $hash;
                    $note                  = "2.5% will be chargeable using this system";
                    $datareturn['payment'] = ["link" => "$paymentLink", "notes" => $note];
                }

                $data = ['success' => true, 'message' => 'Booking created successfully', 'data' => $datareturn, 'note' => 'Given "Total Amount" consider as a "Total Booking Amount"'];
            }
            else
            {
                $data = ['success' => false, 'errors' => $model->getErrors()];
                Logger::create("Error log: ", json_encode($model->getErrors()), CLogger::LEVEL_TRACE);
            }
        }
        else
        {
            $errors = ["status" => "error", "message" => "Booking Failed: Prices have increased"];
            $data   = ['success' => false, 'errors' => $errors];
        }

        return $data;
    }

    public function mapping($data)
    {
        $model                                    = new Booking('new');
        $model->bkgUserInfo                       = new BookingUser();
        $model->bkgInvoice                        = new BookingInvoice();
        $model->bkgTrail                          = new BookingTrail();
        $model->bkgAddInfo                        = new BookingAddInfo();
        $model->bkgPref                           = new BookingPref();
        $model->bkgTrack                          = new BookingTrack();
        $model->bkgPf                             = new BookingPriceFactor();
        $model->bookingRoutes                     = new BookingRoute();
        $model->bkgUserInfo->bkg_user_fname       = $data['customer']['firstName'];
        $model->bkgUserInfo->bkg_user_lname       = $data['customer']['lastName'];
        $model->bkgUserInfo->bkg_country_code     = $data['customer']['mobileCountryCode'];
        $model->bkgUserInfo->bkg_contact_no       = $data['customer']['mobile'];
        $model->bkgUserInfo->bkg_user_email       = $data['customer']['email'];
        $model->bkgUserInfo->bkg_alt_contact_no   = $data['customer']['alternateMobile'];
        $model->bkgUserInfo->bkg_alt_country_code = $data['customer']['alternateMobileCountryCode'];
        $model->bkg_vehicle_type_id               = $data['cabId'];
        $model->bkg_pickup_lat                    = $data['routes'][0]['pickupLatitude'];
        $model->bkg_pickup_long                   = $data['routes'][0]['pickupLongitude'];

        foreach ($data['routes'] as $val)
        {
//			$pickupLat  = $val['pickupLatitude'];
//			$pickupLong  = $val['pickupLongitude'];
            $dropLat  = $val['dropLatitude'];
            $dropLong = $val['dropLongitude'];

//			if($pickupLat=='' || $pickupLat == NULL){
//				$model->bkg_pickup_lat	 = NULL;
//			}
//			else
//			{
//				$model->bkg_pickup_lat	 = $pickupLat;
//			}
//			if($pickupLong=='' || $pickupLong==NULL)
//			{
//				$model->bkg_pickup_long	 = NULL;
//			}
//			else
//			{
//				$model->bkg_pickup_long	 = $pickupLong;
//			}
//			
//			
//			if($dropLat=='' || $dropLat == NULL){
//				$model->bkg_dropup_lat	 = NULL;
//			}
//			else
//			{
//				$model->bkg_dropup_lat	 = $pickupLat;
//			}
//			if($dropLong=='' || $dropLong==NULL)
//			{
//				$model->bkg_dropup_long	 = NULL;
//			}
//			else
//			{
//				$model->bkg_dropup_long	 = $pickupLong;
//			}
        }
        $model->bkg_dropup_lat  = $dropLat;
        $model->bkg_dropup_long = $dropLong;

        if ($data['additional']['specialInstructions'] != '')
        {
            $model->bkg_instruction_to_driver_vendor = $data['additional']['specialInstructions'];
        }
        $model->bkgTrail->bkg_user_ip     = $data['customer']['ip'];
        $model->bkgTrail->bkg_user_device = $data['customer']['device'];
        if ($data['additional']['noOfPerson'] != '')
        {
            $model->bkgAddInfo->bkg_no_person = $data['additional']['noOfPerson'];
        }
        if ($data['additional']['sendEmail'] != '')
        {
            $model->bkgPref->bkg_send_email = $data['additional']['sendEmail'];
        }
        if ($data['additional']['sendSms'] != '')
        {
            $model->bkgPref->bkg_send_sms = $data['additional']['sendSms'];
        }
        if ($data['additional']['noOfLargeBags'] != '')
        {
            $model->bkgAddInfo->bkg_num_large_bag = $data['additional']['noOfLargeBags'];
        }
        if ($data['additional']['noOfSmallBags'] != '')
        {
            $model->bkgAddInfo->bkg_num_small_bag = $data['additional']['noOfSmallBags'];
        }
        if ($data['additional']['seniorCitizenTravelling'] != '')
        {
            $model->bkgAddInfo->bkg_spl_req_senior_citizen_trvl = $data['additional']['seniorCitizenTravelling'];
        }
        if ($data['additional']['kidsTravelling'] != '')
        {
            $model->bkgAddInfo->bkg_spl_req_kids_trvl = $data['additional']['kidsTravelling'];
        }
        if ($data['additional']['womanTravelling'] != '')
        {
            $model->bkgAddInfo->bkg_spl_req_woman_trvl = $data['additional']['womanTravelling'];
        }
        if ($data['additional']['otherRequests'] != '')
        {
            $model->bkgAddInfo->bkg_spl_req_other = $data['additional']['otherRequests'];
        }
        if ($data['additional']['carrierRequired'] != '')
        {
            $model->bkgAddInfo->bkg_spl_req_carrier = $data['additional']['carrierRequired'];
        }
        if ($data['additional']['englishSpeakingDriver'] != '')
        {
            $model->bkgAddInfo->bkg_spl_req_driver_english_speaking = $data['additional']['englishSpeakingDriver'];
        }
        if ($data['additional']['hindiSpeakingDriver'] != '')
        {
            $model->bkgAddInfo->bkg_spl_req_driver_hindi_speaking = $data['additional']['hindiSpeakingDriver'];
        }
        if ($data['tnc'] != '')
        {
            $model->bkgTrail->bkg_tnc = $data['tnc'];
        }
        if ($data['advanceReceived'] != '')
        {
            $model->bkgInvoice->bkg_corporate_credit = $data['advanceReceived'];
        }
        else
        {
            $model->bkgInvoice->bkg_corporate_credit = 0;
        }
        return $model;
    }

    public function reverseMapping(Booking $model)
    {
        $bookingStatus     = '';
        $bookingStatusCode = '';
        if ($model->bkg_status == 1)
        {
            $bookingStatus     = 'Not Confirmed';
            $bookingStatusCode = "0";
        }
        if ($model->bkg_status == 2 || $model->bkg_status == 3 || $model->bkg_status == 4)
        {
            $bookingStatus     = 'Confirmed';
            $bookingStatusCode = "1";
        }
        if ($model->bkg_status == 5)
        {
            $pickup_date = date('Y-m-d H:i:s', strtotime($model->bkg_pickup_date));
            if ($pickup_date != "")
            {
                $d1 = new DateTime();
                $d2 = new DateTime($pickup_date);
                if ($d1 < $d2)
                {
                    $bookingStatus     = 'Cab Assigned';
                    $bookingStatusCode = "2";
                }
                else
                {
                    $bookingStatus     = 'Allocated';
                    $bookingStatusCode = "3";
                }
            }
        }
        if ($model->bkg_status == 6 || $model->bkg_status == 7)
        {
            $bookingStatus     = 'Completed';
            $bookingStatusCode = "4";
        }
        if ($model->bkg_status == 8)
        {
            $bookingStatus     = 'Deleted';
            $bookingStatusCode = "5";
        }
        if ($model->bkg_status == 9)
        {
            $bookingStatus     = 'Cancelled';
            $bookingStatusCode = "6";
        }
        $transModels        = AccountTransDetails::getByBookingID($model->bkg_id);
        $transactionDetails = AccountTransDetails::model()->mapping($transModels);
        $mappingVehicleType = SvcClassVhcCat::model()->vehicleCategoryMapping($model->bkgSvcClassVhcCat->scv_vct_id);
        $cabmodel           = $model->getBookingCabModel();
        $array['bookingId'] = $model->bkg_booking_id;
        $array['tripType']  = $model->bkg_booking_type;
        $array['cabId']     = $mappingVehicleType;
        $array['cab']       = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;

        $array['cabModel'] = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc;

        $array['transactions']          = $transactionDetails;
        $array['bookingStatus']         = $bookingStatus;
        $array['bookingStatusCode']     = $bookingStatusCode;
        $array['fromCityName']          = $model->bkgFromCity->cty_name;
        $array['toCityName']            = $model->bkgToCity->cty_name;
        $array['routeName']             = BookingRoute::model()->getRouteName($model->bkg_id);
        $array['estimatedTripDistance'] = $model->trip_distance_format;
        $array['estimatedTripDuration'] = $model->trip_duration_format;
        $array['firstName']             = $model->bkgUserInfo->bkg_user_fname;
        $array['lastName']              = $model->bkgUserInfo->bkg_user_lname;

        $array['pickupDate']    = date("Y-m-d", strtotime($model->bkg_pickup_date));
        $array['pickupTime']    = date("h:i A", strtotime($model->bkg_pickup_date)); //$model->bkg_pickup_time;
        $array['pickupAddress'] = $model->bkg_pickup_address;
        $array['dropAddress']   = $model->bkg_drop_address;
        if ($model->bkgUserInfo->bkg_user_email != '')
        {
            $array['customerEmail'] = $model->bkgUserInfo->bkg_user_email;
        }
        if ($model->bkgUserInfo->bkg_contact_no != '')
        {
            $array['customerMobile'] = "+" . $model->bkgUserInfo->bkg_country_code . $model->bkgUserInfo->bkg_contact_no;
        }
        if ($model->bkgUserInfo->bkg_alt_contact_no != '')
        {
            $array['customerAlternateMobile'] = "+" . $model->bkgUserInfo->bkg_alt_country_code . $model->bkgUserInfo->bkg_alt_contact_no;
        }
        $array['advance'] = $model->bkgInvoice->getAdvanceReceived();

        $array['driverAllowance']    = $model->bkgInvoice->bkg_driver_allowance_amount;
        $array['tollTax']            = (($model->bkgInvoice->bkg_toll_tax | 0) + ($model->bkgInvoice->bkg_extra_toll_tax | 0));
        $array['stateTax']           = (($model->bkgInvoice->bkg_state_tax | 0) + ($model->bkgInvoice->bkg_extra_state_tax | 0));
        $array['isTollTaxIncluded']  = $model->bkgInvoice->bkg_is_toll_tax_included;
        $array['isStateTaxIncluded'] = $model->bkgInvoice->bkg_is_state_tax_included;
        $array['baseAmt']            = $model->bkgInvoice->bkg_base_amount;
        $array['serviceTax']         = $model->bkgInvoice->bkg_service_tax;

        $array['additionalAmount'] = $model->bkgInvoice->bkg_additional_charge | 0;
        $array['commissionAmount'] = $model->bkgInvoice->bkg_agent_markup | 0;
        $array['discount']         = $model->bkgInvoice->bkg_discount_amount;

        $array['totalAmount'] = $model->bkgInvoice->bkg_total_amount;
        if ($cabmodel->bcbCab->vhc_number != '')
        {
            $vehicleModel = $cabmodel->bcbCab->vhcType->vht_model;
            if ($cabmodel->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
            {
                $vehicleModel = OperatorVehicle::getCabModelName($cabmodel->bcb_vendor_id, $cabmodel->bcb_cab_id);
            }
            $array['cabAssigned'] = $cabmodel->bcbCab->vhcType->vht_make . " " . $vehicleModel . " (" . $cabmodel->bcbCab->vhc_number . ")";
        }
        if ($cabmodel->bcb_driver_name != '')
        {
            $array['driverName'] = $cabmodel->bcb_driver_name;
        }
        if ($cabmodel->bcb_driver_phone != '')
        {
            $array['driverMobile'] = $cabmodel->bcb_driver_phone;
        }
        return $array;
    }

    public function newReverseMapping(Booking $model)
    {
        $bookingStatus     = '';
        $bookingStatusCode = '';
        if ($model->bkg_status == 1)
        {
            $bookingStatus     = 'Not Confirmed';
            $bookingStatusCode = "0";
        }
        if ($model->bkg_status == 2 || $model->bkg_status == 3 || $model->bkg_status == 4)
        {
            $bookingStatus     = 'Confirmed';
            $bookingStatusCode = "1";
        }
        if ($model->bkg_status == 5)
        {
            $pickup_date = date('Y-m-d H:i:s', strtotime($model->bkg_pickup_date));
            if ($pickup_date != "")
            {
                $d1 = new DateTime();
                $d2 = new DateTime($pickup_date);
                if ($d1 < $d2)
                {
                    $bookingStatus     = 'Cab Assigned';
                    $bookingStatusCode = "2";
                }
                else
                {
                    $bookingStatus     = 'Allocated';
                    $bookingStatusCode = "3";
                }
            }
        }
        if ($model->bkg_status == 6 || $model->bkg_status == 7)
        {
            $bookingStatus     = 'Completed';
            $bookingStatusCode = "4";
        }
        if ($model->bkg_status == 8)
        {
            $bookingStatus     = 'Deleted';
            $bookingStatusCode = "5";
        }
        if ($model->bkg_status == 9)
        {
            $bookingStatus     = 'Cancelled';
            $bookingStatusCode = "6";
        }
        $transModels                             = AccountTransDetails::getByBookingID($model->bkg_id);
        $transactionDetails                      = AccountTransDetails::model()->mapping($transModels);
        $mappingVehicleType                      = SvcClassVhcCat::model()->vehicleCategoryMapping($model->bkgSvcClassVhcCat->scv_vct_id);
        $cabmodel                                = $model->getBookingCabModel();
        $array['bookingId']                      = $model->bkg_booking_id;
        $array['bookingStatus']                  = $bookingStatus;
        $array['bookingStatusCode']              = $bookingStatusCode;
        $array['creditsUsed']                    = $model->bkgInvoice->bkg_credits_used;
        $array['traveller details']['firstName'] = $model->bkgUserInfo->bkg_user_fname;
        $array['traveller details']['lastName']  = $model->bkgUserInfo->bkg_user_lname;
        if ($model->bkgUserInfo->bkg_user_email != '')
        {
            $array['traveller details']['customerEmail'] = $model->bkgUserInfo->bkg_user_email;
        }
        if ($model->bkgUserInfo->bkg_contact_no != '')
        {
            $array['traveller details']['customerMobile'] = "+" . $model->bkgUserInfo->bkg_country_code . $model->bkgUserInfo->bkg_contact_no;
        }
        if ($model->bkgUserInfo->bkg_alt_contact_no != '')
        {
            $array['traveller details']['customerAlternateMobile'] = "+" . $model->bkgUserInfo->bkg_alt_country_code . $model->bkgUserInfo->bkg_alt_contact_no;
        }

        $array['trip details']['tripType']              = $model->bkg_booking_type;
        $array['trip details']['estimatedTripDistance'] = $model->trip_distance_format;
        $array['trip details']['estimatedTripDuration'] = $model->trip_duration_format;
        $array['trip details']['tripDescription']       = $model->bkg_booking_type == 1 ? 'One Way' : ($model->bkg_booking_type == 2) ? 'Return' : '';
        $array['trip details']['tripPickupDate']        = date("Y-m-d", strtotime($model->bkg_pickup_date));
        $array['trip details']['tripPickupTime']        = date("h:i A", strtotime($model->bkg_pickup_date)); //$model->bkg_pickup_time;

        $array['transactions'] = $transactionDetails;

        $array['cab details']['cabId']    = $mappingVehicleType;
        $array['cab details']['cab']      = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_label;
        $array['cab details']['cabModel'] = $model->bkgSvcClassVhcCat->scc_VehicleCategory->vct_desc;

        $array['route details']['fromCityName']  = $model->bkgFromCity->cty_name;
        $array['route details']['toCityName']    = $model->bkgToCity->cty_name;
        $array['route details']['routeName']     = BookingRoute::model()->getRouteName($model->bkg_id);
        $array['route details']['pickupAddress'] = $model->bkg_pickup_address;
        $array['route details']['dropAddress']   = $model->bkg_drop_address;

        $array['fair details']['advance']            = $model->bkgInvoice->getAdvanceReceived();
        $array['fair details']['driverAllowance']    = $model->bkgInvoice->bkg_driver_allowance_amount;
        $array['fair details']['tollTax']            = (($model->bkgInvoice->bkg_toll_tax | 0) + ($model->bkgInvoice->bkg_extra_toll_tax | 0));
        $array['fair details']['stateTax']           = (($model->bkgInvoice->bkg_state_tax | 0) + ($model->bkgInvoice->bkg_extra_state_tax | 0));
        $array['fair details']['isTollTaxIncluded']  = $model->bkgInvoice->bkg_is_toll_tax_included;
        $array['fair details']['isStateTaxIncluded'] = $model->bkgInvoice->bkg_is_state_tax_included;
        $array['fair details']['baseAmt']            = $model->bkgInvoice->bkg_base_amount;
        $array['fair details']['serviceTax']         = $model->bkgInvoice->bkg_service_tax;
        $array['fair details']['additionalAmount']   = $model->bkgInvoice->bkg_additional_charge | 0;
        $array['fair details']['commissionAmount']   = $model->bkgInvoice->bkg_agent_markup | 0;
        $array['fair details']['discount']           = $model->bkgInvoice->bkg_discount_amount;
        $array['fair details']['totalAmount']        = $model->bkgInvoice->bkg_total_amount;
        $array['fair details']['isNightPickup']      = $model->bkgInvoice->bkg_night_pickup_included;
        $array['fair details']['isNightDrop']        = $model->bkgInvoice->bkg_night_drop_included;

        if ($cabmodel->bcbCab->vhc_number != '')
        {
            $vehicleModel = $cabmodel->bcbCab->vhcType->vht_model;
            if ($cabmodel->bcbCab->vhc_type_id === Config::get('vehicle.genric.model.id'))
            {
                $vehicleModel = OperatorVehicle::getCabModelName($cabmodel->bcb_vendor_id, $cabmodel->bcb_cab_id);
            }
            $array['cabAssigned'] = $cabmodel->bcbCab->vhcType->vht_make . " " . $vehicleModel . " (" . $cabmodel->bcbCab->vhc_number . ")";
        }
        if ($cabmodel->bcb_driver_name != '')
        {
            $array['driverName'] = $cabmodel->bcb_driver_name;
        }
        if ($cabmodel->bcb_driver_phone != '')
        {
            $array['driverMobile'] = $cabmodel->bcb_driver_phone;
        }
        return $array;
    }

    public function errorMapping($key)
    {
        if ($key == 'bkg_booking_type')
        {
            $key = 'triptype';
        }
        if ($key == 'bkg_id' || $key == 'bkg_booking_id')
        {
            $key = 'bookingId';
        }
        if ($key == 'bkg_from_city_id' || $key == 'brt_from_city_id')
        {
            $key = 'pickupCity';
        }
        if ($key == 'bkg_to_city_id' || $key == 'brt_to_city_id')
        {
            $key = 'dropCity';
        }
        if ($key == 'bkg_contact_no')
        {
            $key = 'customerMobile';
        }
        if ($key == 'bkg_alternate_contact')
        {
            $key = 'customerAlternateMobile';
        }
        if ($key == 'bkg_user_email')
        {
            $key = 'customerEmail';
        }
        if ($key == 'bkg_pickup_address' || $key == 'brt_from_location')
        {
            $key = 'pickupAddress';
        }
        if ($key == 'bkg_drop_address' || $key == 'brt_to_location')
        {
            $key = 'dropAddress';
        }
        if ($key == 'bkg_pickup_date' || $key == 'brt_pickup_datetime')
        {
            $key = 'pickupDate';
        }
        if ($key == 'bkg_pickup_time')
        {
            $key = 'pickupTime';
        }
        if ($key == 'bkg_vehicle_type_id')
        {
            $key = 'cabModel';
        }
        if ($key == 'bkg_user_name')
        {
            $key = 'firstName';
        }
        if ($key == 'bkg_user_lname')
        {
            $key = 'lastName';
        }
        if ($key == 'bkg_route_id')
        {
            $key = 'route';
        }
        return $key;
    }

    public function bookingConfirmAgent($data, $routes)
    {
        $model                              = new Booking('new');
        $model->attributes                  = $data;
        $model->bkg_agent_id                = Yii::app()->user->getAgentId();
        $model->bkgAddInfo->bkg_info_source = 'Agent';
        $data                               = ['success' => false, 'data' => $model, "errors" => $model->getErrors()];
        $result                             = CActiveForm::validate($model);
        if ($result == '[]')
        {
            $model->bkg_platform = Booking::Platform_Agent;
            if ($model->bkg_id == '')
            {
                $model->bkg_id = null;
            }
            //$carType = VehicleTypes::model()->getVehicleTypeById($model->bkg_vehicle_type_id);
            $carType = $model->bkgSvcClassVhcCat->scv_vct_id;
            $route   = [];
            foreach ($routes as $key => $val)
            {
                $routeModel                       = new BookingRoute();
                $routeModel->brt_from_city_id     = $val->pickup_city;
                $routeModel->brt_to_city_id       = $val->drop_city;
                $routeModel->brt_pickup_datetime  = $val->date;
                $routeModel->brt_pickup_date_date = DateTimeFormat::DateTimeToDatePicker($val->date);
                $routeModel->brt_pickup_date_time = date('h:i A', strtotime($val->date));
                $routeModel->brt_to_location      = $val->drop_address;
                $routeModel->brt_from_location    = $val->pickup_address;
                $routeModel->brt_to_pincode       = $val->drop_pincode;
                $routeModel->brt_from_pincode     = $val->pickup_pincode;
                $route[]                          = $routeModel;
            }
            $qt      = Quotation::model()->getQuote($route, $model->bkg_booking_type, $model->bkg_agent_id, $carType);
            $arrQuot = $qt[$carType];
            if ($arrQuot['error'] > 0)
            {
                throw new Exception("Request cannot be processed", 102);
            }
            $rCount                             = count($routes);
            $model->bkg_from_city_id            = $qt['routeData']['pickupCity'];
            $model->bkg_to_city_id              = $qt['routeData']['dropCity'];
            $model->bkg_trip_distance           = $qt['routeData']['quoted_km'];
            $model->bkg_trip_duration           = (string) $qt['routeData']['days']['totalMin'];
            $model->bkg_pickup_address          = $routes[0]->pickup_address;
            $model->bkg_drop_address            = $routes[$rCount - 1]->drop_address;
            $model->bkg_pickup_pincode          = $routes[0]->pickup_pincode;
            $model->bkg_drop_pincode            = $routes[$rCount - 1]->drop_pincode;
            $model->bkg_pickup_date             = $qt['routeData']['startTripDate'];
            $model->bkg_chargeable_distance     = $arrQuot['chargeableDistance'];
            $model->bkg_garage_time             = $qt['routeData']['totalGarage'];
            $model->bkg_pickup_time             = date('H:i:00', strtotime($qt['routeData']['startTripDate']));
            $model->bkg_driver_allowance_amount = $arrQuot['driverAllowance'];
            $model->bkg_is_toll_tax_included    = $arrQuot['tolltax'];
            $model->bkg_is_state_tax_included   = $arrQuot['statetax'];
            $model->bkg_gozo_base_amount        = round($arrQuot['gozo_base_amount']);
            $model->bkg_base_amount             = round($arrQuot['base_amt']);
            $model->bkg_vendor_amount           = round($arrQuot['vendor_amount']);
            $model->bkg_quoted_vendor_amount    = round($arrQuot['vendor_amount']);
            $model->bkg_rate_per_km_extra       = round($arrQuot['km_rate']);
            $model->bkg_rate_per_km             = round($arrQuot['km_rate']);
            $model->bkg_toll_tax                = round($arrQuot['toll_tax']);
            $model->bkg_state_tax               = round($arrQuot['state_tax']);
            if ($model->bkg_booking_type == 2 || $model->bkg_booking_type == 3)
            {
                $model->bkg_return_date_date = DateTimeFormat::DateTimeToDatePicker($qt['routeData']['returnDate']);
                $model->bkg_return_date_time = date('H:i:00', strtotime($qt['routeData']['returnDate']));
                $model->bkg_return_date      = $qt['routeData']['returnDate'];
                // $model->bkg_return_time      = date('H:i:00', strtotime($qt['routeData']['returnDate']));
            }
            $model->populateAmount(true, false, true, false, $model->bkg_agent_id);
            $model->calculateVendorAmount();
            if ($model->bkg_agent_id != '')
            {
                $agtModel = Agents::model()->findByPk($model->bkg_agent_id);
                if ($agtModel->agt_city == 30706)
                {
                    $model->bkg_cgst = Yii::app()->params['cgst'];
                    $model->bkg_sgst = Yii::app()->params['sgst'];
                    $model->bkg_igst = 0;
                }
                else
                {
                    $model->bkg_igst = Yii::app()->params['igst'];
                    $model->bkg_cgst = 0;
                    $model->bkg_sgst = 0;
                }
            }
            else
            {
                if ($model->bkg_from_city_id == 30706)
                {
                    $model->bkg_cgst = Yii::app()->params['cgst'];
                    $model->bkg_sgst = Yii::app()->params['sgst'];
                    $model->bkg_igst = 0;
                }
                else
                {
                    $model->bkg_igst = Yii::app()->params['igst'];
                    $model->bkg_cgst = 0;
                    $model->bkg_sgst = 0;
                }
            }
            $model->bkg_user_last_updated_on = new CDbExpression('NOW()');
            $model->bkg_user_ip              = \Filter::getUserIP();
            $cityinfo                        = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
            $model->bkg_user_city            = $cityinfo['city'];
            $model->bkg_user_country         = $cityinfo['country'];
            $model->bkg_user_device          = UserLog::model()->getDevice();
            $model->scenario                 = 'cabRateAgent';
            $model->bkg_booking_id           = 'temp';
            $transaction                     = Yii::app()->db->beginTransaction();
            if ($model->validate())
            {
                try
                {
                    $model->bkg_user_last_updated_on = new CDbExpression('NOW()');
                    $tmodel                          = Terms::model()->getText(1);
                    $model->bkg_tnc_id               = $tmodel->tnc_id;
                    $model->bkg_tnc_time             = new CDbExpression('NOW()');
                    if (!$model->save())
                    {
                        throw new Exception("Failed to create booking", 101);
                    }
                    $booking_id                    = Booking::model()->generateBookingid($model);
                    $model->bkg_booking_id         = $booking_id;
                    $model->setPaymentExpiryTime();
                    $isRealtedBooking              = $model->findRelatedBooking($model->bkg_id);
                    $model->bkg_is_related_booking = ($isRealtedBooking) ? 1 : 0;
                    if (!$model->save())
                    {
                        throw new Exception("Failed to create booking", 101);
                    }
                    $bookingCab                    = new BookingCab('matchtrip');
                    $bookingCab->bcb_vendor_amount = $model->bkg_vendor_amount;
                    $bookingCab->bcb_bkg_id1       = $model->bkg_id;
                    $bookingCab->save();
                    $model->bkg_bcb_id             = $bookingCab->bcb_id;
                    $model->update();
                    foreach ($route as $rmodel)
                    {
                        $rmodel->brt_bkg_id = $model->bkg_id;
                        $rmodel->brt_bcb_id = $bookingCab->bcb_id;
                        $rmodel->save();
                    }
                    BookingRoute::model()->setBookingCabStartEndTime($bookingCab->bcb_id, $bookingCab->bcb_bkg_id1);
                    $bkgid    = $model->bkg_id;
                    $desc     = "Booking created by agent.";
                    $userInfo = UserInfo::getInstance();

                    $eventid = BookingLog::BOOKING_CREATED;
                    BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid);

                    $transaction->commit();
                }
                catch (Exception $e)
                {
                    $model->addError('bkg_id', $e->getMessage());
                    $transaction->rollback();
                }
            }
            $success = !$model->hasErrors();
            if ($success)
            {
                $data = ['success' => true, 'data' => $model, 'days' => $arrQuot['total_day'], 'cab' => $arrQuot['cab']];
            }
            else
            {
                $data = ['success' => false, 'data' => $model, "errors" => $model->getErrors(), 'days' => $arrQuot['total_day'], 'cab' => $arrQuot['cab']];
            }
        }
        else
        {
            $data = ['success' => false, 'data' => $model, "errors" => $model->getErrors(), 'days' => $arrQuot['total_day'], 'cab' => $arrQuot['cab']];
        }
        return $data;
    }

    public function additionaldetails($data, $routes)
    {
        /* @var $model Booking */
        $model                     = Booking::model()->findbyPk($data['bkg_id']);
        $model->attributes         = array_filter($data);
        $countRoutes               = count($routes);
        $model->bkg_pickup_address = $routes[0]->pickup_address;
        $model->bkg_pickup_pincode = $routes[0]->pickup_pincode;
        $model->bkg_drop_address   = $routes[($countRoutes - 1)]->drop_address;
        $model->bkg_drop_pincode   = $routes[($countRoutes - 1)]->drop_pincode;
        $model->agentRateChange();
        $model->scenario           = 'stepMobile3';
        $result                    = CActiveForm::validate($model);
        $data                      = ['success' => false, 'data' => $model, 'errors' => $result];
        if ($result == '[]')
        {
            $transaction = Yii::app()->db->beginTransaction();
            try
            {
                $routeModels = BookingRoute::model()->getAllByBkgid($model->bkg_id);
                foreach ($routes as $key => $val)
                {
                    $routeModels[$key]->brt_to_location   = $val->drop_address;
                    $routeModels[$key]->brt_from_location = $val->pickup_address;
                    $routeModels[$key]->brt_to_pincode    = $val->drop_pincode;
                    $routeModels[$key]->brt_from_pincode  = $val->pickup_pincode;
                    $routeModels[$key]->scenario          = 'rtupdate';
                    $routeModels[$key]->save();
                }
                if ($model->bkg_user_id == NULL || $model->bkg_user_id == '')
                {
                    if (Yii::app()->user->getId() > 0)
                    {
                        $model->bkg_user_id = Yii::app()->user->getId();
                    }
                    else
                    {
                        $userModel = Users::model()->linkUserByEmail($model->bkg_id, Booking::Platform_Agent);
                        if ($userModel)
                        {
                            $model->bkg_user_id = $userModel->user_id;
                        }
                    }
                }
                $model->bkg_user_last_updated_on = new CDbExpression('NOW()');
                if (!$model->save())
                {
                    throw new Exception("Failed to save data", 101);
                }
                $transaction->commit();
            }
            catch (Exception $e)
            {
                $model->addError('bkg_id', $e->getMessage());
                $transaction->rollback();
            }
            $success = !$model->hasErrors();
            if ($success)
            {
                $data = ['success' => true, 'data' => $model];
            }
            else
            {
                $data = ['success' => false, 'data' => $model, 'errors' => $model->getErrors()];
            }
        }
        else
        {
            $data = ['success' => false, 'data' => $model, 'errors' => $model->getErrors()];
        }
        return $data;
    }

    public function finalBook($data1)
    {
        $model = Booking::model()->findByPk($data1['bkg_id']);

        $id          = Yii::app()->user->getAgentId();
        $agent_model = Agents::model()->findByPk($id);
        if (!$model)
        {
            throw new CHttpException(400, 'Invalid data');
        }
        $model->attributes = $data1;
        if ($model->bkg_status == 1)
        {
            $model->bkg_status = 2;
        }
        $model->bkg_payment_expiry_time = new CDbExpression("GREATEST(DATE_ADD(NOW(), INTERVAL 1 HOUR), DATE_SUB(bkg_pickup_date, INTERVAL 12 HOUR))");
        $data                           = ['success' => false, 'data' => $model, "errors" => $model->getErrors()];
        $model->scenario                = 'tnc';
        $result                         = CActiveForm::validate($model);
        if ($result == '[]')
        {
            $transaction = Yii::app()->db->beginTransaction();
            try
            {
                if (!$model->save())
                {
                    throw new Exception("Failed to save data", 101);
                }
                if ($model->bkg_contact_no != '' && $data1['agt_customer_confirm_sms'] == 1)
                {
                    $msgCom  = new smsWrapper();
                    $logType = UserInfo::TYPE_SYSTEM;
                    $msgCom->gotBooking($model, $logType);
                }
                if ($agent_model->agt_phone != '' && $data1['agt_confirm_sms'] == 1)
                {
                    $msgCom  = new smsWrapper();
                    $logType = UserInfo::TYPE_SYSTEM;
                    $msgCom->gotBooking($model, $logType, $id);
                }
                if ($model->bkg_user_email != '' && $data1['agt_customer_confirm_email'] == 1)
                {
                    $emailCom     = new emailWrapper();
                    $logType      = UserInfo::TYPE_SYSTEM;
                    $emailCom->gotBookingemail($model->bkg_id, $logType);
                    $emailWrapper = new emailWrapper();
                    $emailWrapper->gotBookingAgentUser($model->bkg_id);
                }
                if ($agent_model->agt_email != '' && $data1['agt_confirm_email'] == 1)
                {
                    $emailCom = new emailWrapper();
                    $logType  = UserInfo::TYPE_SYSTEM;
                    $emailCom->gotBookingemail($model->bkg_id, $logType, $id);
                }
                $transaction->commit();
            }
            catch (Exception $e)
            {
                $model->addError('bkg_id', $e->getMessage());
                $transaction->rollback();
            }
            $success = !$model->hasErrors();
            if ($success)
            {
                $data = ['success' => true, 'data' => $model];
            }
            else
            {
                $data = ['success' => false, 'data' => $model, "errors" => $model->getErrors()];
            }
        }
        else
        {
            $data = ['success' => false, 'data' => $model, "errors" => $model->getErrors()];
        }
        return $data;
    }

    public function cab_list($data, $triptype)
    {
        $route = [];
        foreach ($data as $key => $val)
        {
            $routeModel                       = new BookingRoute();
            $routeModel->brt_from_city_id     = $val->pickup_city;
            $routeModel->brt_to_city_id       = $val->drop_city;
            $routeModel->brt_pickup_datetime  = $val->date;
            $routeModel->brt_pickup_date_date = DateTimeFormat::DateTimeToDatePicker($val->date);
            $routeModel->brt_pickup_date_time = date('h:i A', strtotime($val->date));
            $routeModel->brt_to_location      = $val->drop_address;
            $routeModel->brt_from_location    = $val->pickup_address;
            $routeModel->brt_to_pincode       = $val->drop_pincode;
            $routeModel->brt_from_pincode     = $val->pickup_pincode;
            $route[]                          = $routeModel;
        }
        $partnerId = Yii::app()->user->getId();

        $quote                  = new Quote();
        $quote->routes          = $route;
        $quote->tripType        = $triptype;
        $quote->partnerId       = $partnerId;
        $quote->quoteDate       = date('Y-m-d H:i:s');
        $quote->pickupDate      = $route[0]->brt_pickup_datetime;
        $quote->returnDate      = (count($route) > 1 ) ? $route[count($route) - 1]->brt_pickup_datetime : null;
        $quote->sourceQuotation = Quote::Platform_App;
        $quote->setCabTypeArr();
        $quotData               = $quote->getQuote();
        $cabAllowed             = [1, 2, 3];
        $result                 = [];
        foreach ($quotData as $cab => $quoteRoute)
        {
            if (!in_array($cab, $cabAllowed) || !$quoteRoute->success)
            {
                unset($quotData[$cab]);
                continue;
            }

            $routeDistance = $quoteRoute->routeDistance;
            $routeDuration = $quoteRoute->routeDuration;
            $routeRate     = $quoteRoute->routeRates;
            $cabmodel      = VehicleTypes::model()->getCarModel($cab, 1);
            $res           = [
                'state_tax'        => $routeRate->stateTax | 0,
                'toll_tax'         => $routeRate->tollTaxAmount | 0,
                'min_chargeable'   => $routeDistance->quotedDistance,
                'total_min'        => $routeDuration->totalMinutes,
                'cab'              => VehicleTypes::model()->getCarByCarType($cab),
                'cab_type_id'      => $cab,
                'actual_amt'       => $routeRate->totalAmount,
                'base_amt'         => $routeRate->baseAmount,
                'service_tax'      => $routeRate->gst,
                'total_amt'        => $routeRate->totalAmount,
                'quote_km'         => $routeDistance->quotedDistance,
                'total_day'        => $routeDuration->durationInWords, //$quote['3']['total_day'],
                'km_rate'          => $routeRate->ratePerKM,
                'addional_km'      => 0,
                'total_km'         => $routeDistance->quotedDistance,
                'route'            => $routeDistance->routeDesc,
                'error'            => 0,
                'image'            => $cabmodel->vht_image,
                'capacity'         => $cabmodel->vht_capacity,
                'bag_capacity'     => $cabmodel->vht_bag_capacity,
                'big_bag_capacity' => $cabmodel->vht_big_bag_capacity,
                'cab_model'        => $cabmodel->vht_model,
                'startTripDate'    => $routeDuration->fromDate, //$quote['routeData']['startTripDate'],
                'endTripDate'      => $routeDuration->toDate, //$quote['routeData']['endTripDate'],
                'driverAllowance'  => $routeRate->driverAllowance,
                'tolltax'          => $routeRate->isTollIncluded, //$quote['3']['tolltax'],
                'statetax'         => $routeRate->isStateTaxIncluded, //$quote['3']['statetax'],
                'servicetax'       => 1,
                'startTripCity'    => $route[0]->brt_from_city_id, // $quote['routeData']['pickupCity'],
                'endTripCity'      => $route[count($route) - 1]->brt_to_city_id, //$quote['routeData']['dropCity'],
                'cab_id'           => $cabmodel->vht_id
            ];
            $result[]      = $res;
        }
        return $result;
    }

    public function actionNew()
    {
        $model                   = new BookingTemp('new');
        $model->bkg_booking_type = 1;
        $rq                      = Yii::app()->request->getParam('BookingTemp');
        if ($rq != null)
        {
            $model->attributes = $rq;
            if ($_REQUEST['step'] == '0')
            {
                $this->forward('booking/route', true);
            }
        }
        $outputJs = Yii::app()->request->isAjaxRequest;
        $method   = "render" . ($outputJs ? "Partial" : "");
        $this->$method('new', array('model' => $model, 'btyp' => $model->bktyp, 'bdata' => $t1), false, $outputJs);
    }

    public function actionRoute()
    {
        $btmodel = new BookingTemp('new');
        $success = false;
        if (isset($_REQUEST['BookingTemp']))
        {
            $arr                        = Yii::app()->request->getParam('BookingTemp');
            $btmodel->attributes        = $arr;
            $transfer_type              = ($arr['bkg_transfer_type'][0]) ? $arr['bkg_transfer_type'][0] : $arr['bkg_transfer_type'];
            $btmodel->bkg_transfer_type = $transfer_type | 0;
            if ($_REQUEST['step'] == '0')
            {
                if ($arr["bkg_id"] != '' && $arr['hash'] != '')
                {
                    if ($arr["bkg_id"] != Yii::app()->shortHash->unHash($arr['hash']))
                    {
                        throw new CHttpException(400, 'Invalid data');
                    }
                    $btmodel = BookingTemp::model()->findByPk($arr["bkg_id"]);
                    if (!$btmodel)
                    {
                        throw new CHttpException(400, 'Invalid data');
                    }
                    $btmodel->setScenario('new');
                }

                $btmodel->attributes        = $arr;
                $transfer_type              = ($arr['bkg_transfer_type'][0]) ? $arr['bkg_transfer_type'][0] : $arr['bkg_transfer_type'];
                $btmodel->bkg_transfer_type = $transfer_type | 0;
                $brtModels                  = [];
                if ($btmodel->isNewRecord && $btmodel->bkg_from_city_id != "")
                {
                    $brtModel                       = new BookingRoute();
                    $brtModel->brt_from_city_id     = $btmodel->bkg_from_city_id;
                    $brtModel->brt_to_city_id       = $btmodel->bkg_to_city_id;
                    $brtModel->brt_pickup_date_date = $btmodel->bkg_pickup_date_date;
                    $brtModel->brt_pickup_date_time = $btmodel->bkg_pickup_date_time;
                    if ($btmodel->bkg_booking_type == 2)
                    {
                        $brtModel->brt_return_date_date = $btmodel->bkg_return_date_date;
                        $brtModel->brt_return_date_time = $btmodel->bkg_return_date_time;
                    }
                    $brtModels[] = $brtModel;
                    if ($btmodel->bkg_booking_type == 2)
                    {
                        $brtModel                   = new BookingRoute();
                        $brtModel->brt_from_city_id = $btmodel->bkg_to_city_id;
                        $brtModel->brt_to_city_id   = $btmodel->bkg_from_city_id;
//                        $brtModel->brt_pickup_date_date = $btmodel->bkg_return_date_date;
//                        $brtModel->brt_pickup_date_time = $btmodel->bkg_return_date_time;
                        $brtModels[]                = $brtModel;
                    }
                    $btmodel->bookingRoutes = $brtModels;
                }
                else
                {
                    $bookingRoutes = [];
                    foreach ($brtModels as $brtModel)
                    {
                        $bookingRoutes[] = array_filter($brtModel);
                    }
                    if (sizeof($bookingRoutes) > 0)
                    {
                        $btmodel->bkg_route_data = CJSON::encode($bookingRoutes);
                    }
                    $bookingRoutes2 = [];
                    $bookingRoutes1 = CJSON::decode($btmodel->bkg_route_data);
                    foreach ($bookingRoutes1 as $k => $v)
                    {
                        $bookingRoute             = new BookingRoute();
                        $bookingRoute->attributes = $v;
                        $bookingRoutes2[]         = $bookingRoute;
                    }
                    $btmodel->bookingRoutes = $bookingRoutes2;
                }
            }
            if ($arr["bkg_id"] == '' && ($btmodel->bkg_user_email == "" && $btmodel->bkg_contact_no == ""))
            {
                if (!Yii::app()->user->isGuest)
                {
                    /* @var $user Users */
                    $user                      = Yii::app()->user->loadAgentUser();
                    //   $btmodel->bkg_user_id = $agent->agt_id;
                    $btmodel->bkg_user_email   = $user->usr_email;
                    $btmodel->bkg_contact_no   = ($user->usr_mobile != '') ? str_replace(' ', '', $user->usr_mobile) : $user->usr_mobile;
                    $btmodel->bkg_country_code = '91';
                }
            }
            if ($_REQUEST['step'] == '1')
            {
                if ($btmodel->bkg_id == "")
                {
                    $btmodel->bkg_id = null;
                }
                else
                {
                    $btmodel = BookingTemp::model()->findByPk($btmodel->bkg_id);
                }
                $btmodel->attributes        = $arr;
                $transfer_type              = ($arr['bkg_transfer_type'][0]) ? $arr['bkg_transfer_type'][0] : $arr['bkg_transfer_type'];
                $btmodel->bkg_transfer_type = $transfer_type | 0;
                $arrRt                      = Yii::app()->request->getParam('BookingRoute');
                $brtModels                  = [];
                $btmodel->setScenario('multiroute1');
                foreach ($arrRt as $route)
                {
                    $rtModel             = new BookingRoute();
                    $rtModel->attributes = $route;

                    $pickupDate1                  = DateTimeFormat::DatePickerToDate($rtModel->brt_pickup_date_date);
                    $times1                       = DateTime::createFromFormat('h:i A', $rtModel->brt_pickup_date_time)->format('H:i:00');
                    $rtModel->brt_pickup_datetime = $pickupDate1 . " " . $times1;

//$result += CActiveForm::validate($rtModel, null, false);
                    $brtModels[] = $rtModel;
                    if ($i == 0)
                    {
                        $btmodel->bkg_from_city_id     = $rtModel->brt_from_city_id;
                        $btmodel->bkg_pickup_date_date = $rtModel->brt_pickup_date_date;
                        $btmodel->bkg_pickup_date_time = $rtModel->brt_pickup_date_time;
                    }

                    $rtModel->validate();
                    if ($rtModel->hasErrors())
                    {
                        $errors = $rtModel->getErrors();
                        foreach ($errors as $attribute => $error)
                        {
                            foreach ($error as $err)
                            {
//		$model->addError("bkg_id", $err);
                            }
                        }
                    }
                    if ($btmodel->bkg_booking_type == 2)
                    {

                        $rtModelRet                      = new BookingRoute();
                        $returnDate1                     = DateTimeFormat::DatePickerToDate($route['brt_return_date_date']);
                        $returntimes1                    = DateTime::createFromFormat('h:i A', $route['brt_return_date_time'])->format('H:i:00');
                        $returnDateTime                  = ($route['brt_return_date_date'] == '') ? '' : $returnDate1 . " " . $returntimes1;
                        $rtModelRet->brt_return_datetime = $returnDateTime;
                        if ($returnDateTime == '')
                        {
                            $btmodel->addError('bkg_return_datetime', 'Trip End information is needed.');
                        }
                        $retPickDate = Route::model()->getRoundtripEstimatedPickupbyReturnDateTime($route['brt_from_city_id'], $route['brt_to_city_id'], $returnDateTime);

                        $rtModelRet->brt_from_city_id     = $route['brt_to_city_id'];
                        $rtModelRet->brt_to_city_id       = $route['brt_from_city_id'];
                        $rtModelRet->brt_pickup_date_date = DateTimeFormat:: DateTimeToDatePicker($retPickDate);
                        $rtModelRet->brt_pickup_date_time = DateTimeFormat:: DateTimeToTimePicker($retPickDate);
                        $rtModelRet->brt_pickup_datetime  = $retPickDate;
                        $rtModelRet->brt_return_date_date = $route['brt_return_date_date'];
                        $rtModelRet->brt_return_date_time = $route['brt_return_date_time'];
                        $brtModels[]                      = $rtModelRet;
                        $arrRt[]                          = [
                            'brt_from_city_id'     => $route['brt_to_city_id'],
                            'brt_to_city_id'       => $route['brt_from_city_id'],
                            'brt_pickup_date_date' => DateTimeFormat:: DateTimeToDatePicker($retPickDate),
                            'brt_pickup_date_time' => DateTimeFormat:: DateTimeToTimePicker($retPickDate),
                            'brt_return_date_date' => $route['brt_return_date_date'],
                            'brt_return_date_time' => $route['brt_return_date_date'],
                        ];
                        $rtModelRet->validate();
                        if ($rtModelRet->hasErrors())
                        {
                            $errors = $rtModelRet->getErrors();
                            foreach ($errors as $attribute => $error)
                            {
                                foreach ($error as $err)
                                {
                                    $btmodel->addError("bkg_id", $err);
                                }
                            }
                        }
                    }

                    $i++;
                }
                $btmodel->bookingRoutes = $brtModels;
                if ($btmodel->bkg_booking_type != 2 && $btmodel->bkg_booking_type != 1)
                {
                    $btmodel->validateRouteTime('bkg_id');
                }
                if (!$btmodel->hasErrors())
                {
                    $cookie_name = 'gozo_mff';
                    if (isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] == 'mff')
                    {
                        $btmodel->bkg_tags = 1;
                    }
                    $btmodel->bookingRoutes = $brtModels;
                    if ($btmodel->bkg_booking_type == 2)
                    {
                        $btmodel->bkg_to_city_id = $rtModelRet->brt_to_city_id;
                    }
                    else
                    {
                        $btmodel->bkg_to_city_id = $rtModel->brt_to_city_id;
                    }
                    $models = $brtModels + [$btmodel];
                    $result = CActiveForm::validate($btmodel, null, false);
                    if ($result == '[]')
                    {
                        $btmodel->bkg_platform = Booking::Platform_Agent;

                        $user_id = (Yii::app()->user->getId() > 0) ? Yii::app()->user->getId() : '';

                        $btmodel->bkg_user_ip              = \Filter::getUserIP();
                        $cityinfo                          = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
                        $btmodel->bkg_user_city            = $cityinfo['city'];
                        $btmodel->bkg_user_country         = $cityinfo['country'];
                        $btmodel->bkg_user_device          = UserLog::model()->getDevice();
                        $btmodel->bkg_user_last_updated_on = new CDbExpression('NOW()');
                        $tmodel                            = Terms::model()->getText(1);
                        $btmodel->bkg_tnc_id               = $tmodel->tnc_id;
                        $btmodel->bkg_tnc_time             = new CDbExpression('NOW()');
                        $btmodel->bkg_booking_id           = 'temp';

                        if (Yii::app()->user->getCorpCode() != '')
                        {
                            $btmodel->bkg_agent_id = Yii::app()->user->getAgentId();
                        }
                        else
                        {
                            $btmodel->bkg_agent_id = Yii::app()->user->getAgentId();
                        }
                        $transaction = Yii::app()->db->beginTransaction();
                        $result      = CActiveForm::validate($btmodel, null, false);
                        if ($result == '[]')
                        {
                            try
                            {
                                if ($btmodel->bkg_id == "")
                                {
                                    $btmodel->bkg_id = null;
                                }
                                if (!$btmodel->save())
                                {
                                    throw new Exception("Failed to create booking", 101);
                                }

                                $booking_id              = BookingTemp::model()->generateBookingid($btmodel);
                                $btmodel->bkg_booking_id = $booking_id;
                                if (!$btmodel->save())
                                {
                                    throw new Exception("Failed to create booking", 101);
                                }

                                $bkgid = $btmodel->bkg_id;
                                $btmodel->updateRelated($bkgid);

                                $leadRouteArr = [];
                                foreach ($arrRt as $route)
                                {
                                    $rtModel                      = new BookingRoute();
                                    $rtModel->brt_bkg_id          = $bkgid;
                                    $rtModel->attributes          = $route;
                                    $pickupDate                   = DateTimeFormat::DatePickerToDate($route['brt_pickup_date_date']);
                                    $time                         = DateTime::createFromFormat('h:i A', $route['brt_pickup_date_time'])->format('H:i:00');
                                    $rtModel->brt_pickup_datetime = $pickupDate . " " . $time;
                                    $leadRouteArr[]               = array_filter($rtModel->attributes);
                                    //  $rtModel->save();
                                }
                                $leadDataArr             = CJSON::encode($leadRouteArr);
                                $btmodel->bkg_route_data = $leadDataArr;

                                $btmodel->save();
                                $desc     = "Quote generated by agent.";
                                $userInfo = UserInfo::getInstance();

                                $eventid           = BookingLog::BOOKING_CREATED;
                                LeadLog::model()->createLog($bkgid, $desc, $userInfo, '', '', $eventid);
                                $transaction->commit();
                                $GLOBALS["bkg_id"] = $btmodel->bkg_id;
                                $GLOBALS["hash"]   = Yii::app()->shortHash->hash($btmodel->bkg_id);
                                $this->forward("booking/route2", true);
                            }
                            catch (Exception $e)
                            {
                                $btmodel->addError('bkg_id', $e->getMessage());
                                $transaction->rollback();
                                $result = CActiveForm::validate($btmodel, null, false);
                            }
                        }
                    }
                    if (!$success)
                    {
                        $data = ["errors" => CJSON::decode($result)];
                    }
                }
                else
                {
                    $data = ["errors" => $btmodel->getErrors()];
                }

                $return = ['success' => $success] + $data;
                echo CJSON::encode($return);
                Yii::app()->end();
            }
        }


        if (Yii::app()->request->isAjaxRequest)
        {
            $this->renderPartial('route', array('model' => $btmodel), false, true);
        }
        else
        {
            $this->render('new', array('model' => $btmodel));
        }
    }

    public function actionAddroute()
    {

        $scity    = Yii::app()->request->getParam('scity'); //tocity
        $pscity   = Yii::app()->request->getParam('pscity'); //fromcity
        $pdate    = Yii::app()->request->getParam('pdate');
        $ptime    = Yii::app()->request->getParam('ptime');
        $btype    = Yii::app()->request->getParam('btype');
        $index    = Yii::app()->request->getParam('index');
        $rutModel = Route::model()->getbyCities($pscity, $scity);
        if (!$rutModel)
        {
            $result1 = Route::model()->populate($pscity, $scity);
            if ($result1['success'])
            {
                $rutModel = $result1['model'];
            }
        }
        $model                       = BookingRoute::model();
        $date                        = DateTimeFormat::DatePickerToDate($pdate);
        $time                        = DateTime::createFromFormat('h:i A', $ptime)->format('H:i:00');
        $dateTime                    = $date . ' ' . $time;
        $dateTime                    = new DateTime($dateTime);
        $dateTime->add(new DateInterval('PT' . $rutModel->rut_estm_time . 'M'));
        $seconds                     = $dateTime->getTimestamp();
        $rounded_seconds             = ceil($seconds / (15 * 60)) * (15 * 60);
        $dateTime->setTimestamp($rounded_seconds);
        $minTime                     = $dateTime->format('Y-m-d H:i:s');
        $date                        = DateTimeFormat::DateTimeToDatePicker($minTime);
        $time                        = $dateTime->format("h:i A");
        $model->brt_min_date         = $dateTime->format('Y-m-d');
        $model->brt_pickup_date_date = $date;
        $model->brt_pickup_date_time = $time;
        $this->renderPartial('addroute', ['model' => $model, 'sourceCity' => $scity, 'previousCity' => $pscity, 'btype' => $btype, 'index' => $index], false, true);
    }

    public function actionRoute2()
    {
        $model = new BookingTemp('new');
        if (isset($_REQUEST['step3']) && $_REQUEST ['step3'] == 3)
        {
            $model->scenario = 'new';
            $reqData         = Yii::app()->request->getParam('BookingTemp');
            if (in_array($reqData['bkg_booking_type'], [2, 3]))
            {
                $cdata             = [];
                $cdata             = CJSON::decode($reqData['preData'], true);
                $model->attributes = $cdata;
                $model->attributes = $reqData;
                $preData           = array_filter($model->attributes);
                $edata             = CJSON::encode($preData);
                $model->preData    = $edata;
                $result            = CActiveForm::validate($model);
                if ($result == '[]')
                {
                    $return['success'] = true;
                    $return['res']     = 'On' . date('dj M y', strtotime($model->bkg_pickup_date)) . ' at ' .
                        date('h:i A', strtotime($model->bkg_pickup_date));
                    $return['type']    = $model->bkg_booking_type;
                    $return['model']   = $model;
                }
                else
                {
                    $return = ['success' => false, 'errors' => CJSON::decode($result)];
                }
                if (Yii::app()->request->isAjaxRequest)
                {
                    echo CJSON::encode($return);
                    Yii:: app()->end();
                }
            }
        }
        if (isset($GLOBALS['bkg_id']))
        {
            $bkgid = $GLOBALS['bkg_id'];
        }
        $booking = Yii::app()->request->getParam("BookingTemp");

        if ($booking != null && !Yii::app()->request->isAjaxRequest)
        {
            $bkgid       = $booking['bkg_id'];
            $hash        = Yii::app()->request->getParam('hash');
            $model->hash = $hash;
            if ($bkgid != Yii::app()->shortHash->unHash($hash))
            {
                throw new CHttpException(400, 'Invalid data');
            }
        }
        if ($bkgid)
        {
            $model = BookingTemp::model()->findByPk($bkgid);
            if (!$model)
            {
                throw new CHttpException(400, 'Invalid data');
            }



            /* @var $model Booking */

//			$brtModels[] = $model->bookingRoutes;
//			$arqot = [];
//			$params = [];
//////////
            $masterVehicles = VehicleTypes::model()->getMasterCarType();
            $keys           = implode(',', array_keys($masterVehicles));

            $route_data = json_decode($model->bkg_route_data, true);
            foreach ($route_data as $k => $v)
            {
                $bookingRoute             = new BookingRoute();
                $bookingRoute->attributes = $v;
                $bookingRoutes[]          = $bookingRoute;
            }
            $bkgType   = $model->bkg_booking_type;
            $partnerId = Yii::app()->user->getAgentId();
            $arrQuot   = Quotation::model()->getQuote($bookingRoutes, $bkgType, $partnerId);
            $routeData = [];
            $cityArr   = [];
            foreach ($bookingRoutes as $bRoute)
            {
                $routeData[] = array_filter($bRoute->attributes);
                $cityArr[]   = $bRoute->brt_from_city_id;
                $cityArr[]   = $bRoute->brt_to_city_id;
            }
            $citiesInRoutes        = array_unique($cityArr);
            $model->bkg_route_data = CJSON::encode($routeData);

            $quotData = $arrQuot['routeData'];

            if ($model->bkg_booking_type == 2 && $model->bkg_return_date_date == '')
            {
                $model->bkg_return_date_date = DateTimeFormat::DateTimeToDatePicker($quotData['returnDate']);
                $model->bkg_return_date_time = date('H:i:00', strtotime($quotData['returnDate']));
                $model->bkg_return_date      = $quotData['returnDate'];
                $model->bkg_return_time      = date('H:i:00', strtotime($quotData['returnDate']));
            }

            $createDate = $model->bkg_create_date;
            $pickDate   = $bookingRoutes[0]['brt_pickup_datetime'];
            $discCond   = BookingSub::model()->getExpTimeAdvPromo($createDate, $pickDate);

            foreach ($arrQuot as $k => $v)
            {
                $arrQuot[$k]['discFare'] = '';

//                if ($discCond == 5) {
//                    $discAdv = round($arrQuot[$k]['base_amt'] * 0.05);
//                    $arrQuot[$k]['discFare'] = "ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¹" . ($arrQuot[$k]['base_amt'] - $discAdv) . "*";
//                }
//                if ($discCond == 2.5) {
//                    $discAdv = round($arrQuot[$k]['base_amt'] * 0.025);
//                    $arrQuot[$k]['discFare'] = "ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¹" . ($arrQuot[$k]['base_amt'] - $discAdv) . "*";
//                }
                if (!in_array($k, array_keys($masterVehicles)))
                {
                    unset($arrQuot[$k]);
                }
                else
                {
                    $tempCab[$k] = $v['total_amt'];
                }
            }
            array_multisort($tempCab, SORT_ASC, $arrQuot);

//$arr1 = array_values($arrQuot)[0];

            $model->bkg_trip_distance = $quotData['minimumChargeableDistance'];
            $model->bkg_trip_duration = $quotData['time'];
            // $model->bkg_trip_duration_day = $quotData['days']['actualDur'];
            $model->scenario          = 'new';
            $model->save();
            $model->hash              = Yii::app()->shortHash->hash($model->bkg_id);
//  $data['km_rate']=$arrQuot[0]['km_rate'];

            $return['success'] = true;
            $return['res']     = date('d M y h:i A', strtotime($model->bkg_pickup_date));
            $return['type']    = $model->bkg_booking_type;
        }

        $this->renderPartial('rtview', ['model'          => $model, 'quotData'       => $quotData, 'discCond'       => $discCond,
            'citiesInRoutes' => $citiesInRoutes,
            'cabratedata'    => $arrQuot], false, true);
    }

    public function actionCabratedetail()
    {
        $model = new BookingTemp('new');
        if (isset($_REQUEST['BookingTemp']))
        {

            $model->scenario = 'new';
            $reqData         = Yii::app()->request->getParam('BookingTemp');

            $bkgid = $reqData['bkg_id'];
            $hash  = $reqData['hash'];

            if ($bkgid != Yii::app()->shortHash->unHash($hash))
            {
                throw new CHttpException(400, 'Invalid data');
            }
            /* @var $model Booking */
            $model       = BookingTemp::model()->findByPk($bkgid);
            $model->hash = $hash;

            if (!$model)
            {
                throw new CHttpException(400, 'Invalid data');
            }

            $model->bkg_vehicle_type_id   = $reqData['bkg_vehicle_type_id'];
            $model->bkg_rate_per_km_extra = $reqData['bkg_rate_per_km_extra'];

            $result = CActiveForm:: validate($model);
            if ($result == '[]')
            {
                if (!$model->save())
                {
                    throw new Exception("Failed to create booking", 101);
                }
                $GLOBALS["bkg_id"] = $model->bkg_id;
                $model->hash       = Yii::app()->shortHash->hash($model->bkg_id);
                $GLOBALS["hash"]   = Yii::app()->shortHash->hash($model->bkg_id);
                $this->forward("booking/additionaldetail", true);
            }
            else
            {
                $return = ['success' => false, 'errors' => CJSON::decode($result)];
            }
            if (Yii::app()->request->isAjaxRequest)
            {
                echo CJSON::encode($return);
                Yii::app()->end();
            }
        }
    }

    public function actionAdditionaldetail()
    {

        $btmodel = new BookingTemp('new');
        $view    = Yii::app()->request->getParam('view', 'additionaldetail');
        $method  = 'renderPartial';
        if (isset($_POST['step']) && $_POST['step'] == 4)
        {
            $reqData   = Yii::app()->request->getParam('BookingTemp');
            $reqRtData = Yii::app()->request->getParam('BookingRoute');

            $bkgid = $reqData['bkg_id'];
            $hash  = $reqData['hash'];

            if ($bkgid != Yii::app()->shortHash->unHash($hash))
            {
                throw new CHttpException(400, 'Invalid data');
            }
            /* @var $btmodel Booking */
            $btmodel             = BookingTemp::model()->findByPk($bkgid);
            $btmodel->setScenario("additional");
            $btmodel->hash       = $hash;
            $btmodel->attributes = $reqData;

//
//            $uploadedFile = CUploadedFile::getInstance($model, "fileImage");
//            if ($uploadedFile != '') {
//                $crdate = date('YmdHis', strtotime($model->bkg_create_date));
//                $fileName = $model->bkg_id . '_' . $crdate . '_' . $uploadedFile;
//                $model->bkg_file_path = DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $fileName;
//                $uploadedFile->saveAs(PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $fileName);
//            }
            $brtArr = [];
            foreach ($reqRtData as $brtVal)
            {
                $brtArr[] = $brtVal;
            }
            $cntBrt                = sizeof($reqRtData);
            $btmodel->scenario     = 'cabRate';
            $fromAdditionalAddress = $toAdditionalAddress   = '';
            if ($btmodel->bkg_booking_type == 4)
            {
                if ($btmodel->bkg_transfer_type == 1)
                {
                    $toAdditionalAddress = ltrim(trim($brtArr[$cntBrt - 1]['brt_additional_to_address']) . ', ', ', ');
                }
                else
                {
                    $fromAdditionalAddress = ltrim(trim($brtArr[0]['brt_additional_from_address']) . ', ', ', ');
                }
            }

            $btmodel->bkg_pickup_address = $brtArr[0]['brt_from_location'];
            $btmodel->bkg_drop_address   = $brtArr[$cntBrt - 1]['brt_to_location'];

            if ($btmodel->bkg_user_email == '')
            {
                $btmodel->bkg_send_email = 0;
            }
            else
            {
                $btmodel->bkg_send_email = 1;
            }
            if ($btmodel->bkg_contact_no == '')
            {
                $btmodel->bkg_send_sms = 0;
            }
            else
            {
                $btmodel->bkg_send_sms = 1;
            }
            $transaction        = Yii::app()->db->beginTransaction();
            $btmodel->latlonSet = false;
            $result             = CActiveForm::validate($btmodel, null, false);
            if ($result == '[]')
            {

                $btmodel->bkg_platform = Booking::Platform_Agent;

                if ($btmodel->bkg_id == '')
                {
                    $btmodel->bkg_id = null;
                }


                $route_data = json_decode($btmodel->bkg_route_data, true);
                foreach ($route_data as $k => $v)
                {
                    $bookingRoute                    = new BookingRoute();
                    $bookingRoute->attributes        = $v;
                    $bookingRoute->brt_from_location = $fromAdditionalAddress . $brtArr[$k]['brt_from_location'] . $brtArr[$k]['brt_to_location'];
                    $bookingRoute->brt_to_location   = $toAdditionalAddress . $brtArr[$k + 1]['brt_to_location'];
                    if ($btmodel->bkg_booking_type == 4)
                    {
                        $bookingRoute->brt_from_latitude  = round($brtArr[$k]['brt_from_latitude'] . $brtArr[$k]['brt_to_latitude'], 6);
                        $bookingRoute->brt_from_longitude = round($brtArr[$k]['brt_from_longitude'] . $brtArr[$k]['brt_to_longitude'], 6);
                        $bookingRoute->brt_to_latitude    = round($brtArr[$k + 1]['brt_to_latitude'], 6);
                        $bookingRoute->brt_to_longitude   = round($brtArr[$k + 1]['brt_to_longitude'], 6);
                        $btmodel->pickupLat               = $bookingRoute->brt_from_latitude;
                        $btmodel->pickupLon               = $bookingRoute->brt_from_longitude;
                        $btmodel->dropLat                 = $bookingRoute->brt_to_latitude;
                        $btmodel->dropLon                 = $bookingRoute->brt_to_longitude;
                        $btmodel->latlonSet               = true;
                    }
                    $bookingRoutes[] = $bookingRoute;
                }
                $result = CActiveForm::validate($btmodel, null, false);
                if ($result == '[]')
                {
                    $cntRt                       = sizeof($route_data);
                    $btmodel->bkg_pickup_address = $fromAdditionalAddress . $brtArr[0]['brt_from_location'];
                    $btmodel->bkg_drop_address   = $toAdditionalAddress . $brtArr[$cntRt]['brt_to_location'];

                    $carType   = $btmodel->bkg_vehicle_type_id;
                    //  $bkgType = ($btmodel->bkg_booking_type == 4) ? 1 : $btmodel->bkg_booking_type; //treating transfers as oneway
                    $bkgType   = $btmodel->bkg_booking_type;
                    $partnerId = Yii::app()->user->getAgentId();

                    $qt = Quotation::model()->getQuote($bookingRoutes, $bkgType, $partnerId, $carType);

                    $quote                  = new Quote();
                    $quote->routes          = $bookingRoutes;
                    $quote->tripType        = $btmodel->bkg_booking_type;
                    $quote->partnerId       = $partnerId;
                    $quote->quoteDate       = $btmodel->bkg_create_date;
                    $quote->pickupDate      = $btmodel->bkg_pickup_date;
                    $quote->returnDate      = $btmodel->bkg_return_date;
                    $quote->sourceQuotation = Quote::Platform_Partner_Spot;
                    $quote->isB2Cbooking    = false;
                    $quote->setCabTypeArr();
                    $quotData               = $quote->getQuote($carType);
                    $cabData                = $quotData[$carType];

                    if ($btmodel->bkg_booking_type == 2)
                    {
                        $returnDate                    = $bookingRoutes[count($bookingRoutes) - 1]->brt_pickup_datetime;
                        $btmodel->bkg_return_date_date = DateTimeFormat::DateTimeToDatePicker($returnDate);
                        $btmodel->bkg_return_date_time = date('H:i:00', strtotime($returnDate));
                        $btmodel->bkg_return_date      = $returnDate;
                        $btmodel->bkg_return_time      = date('H:i:00', strtotime($returnDate));
                    }
                    $routesData = [];
                    foreach ($bookingRoutes as $bRoute)
                    {
                        $routesData[] = array_filter($bRoute->attributes);
                    }
                    $btmodel->bkg_route_data = CJSON::encode($routesData);

                    $btmodel->save();

                    //Converting Lead Data to Booking
                    $arrResult = Booking::model()->convertUserLeadtoBooking($btmodel);
                    if ($arrResult['success'])
                    {
                        $model = $arrResult['model'];

                        foreach ($model->bookingRoutes as $t)
                        {
                            $t->save();
                        }
                        $cabData = [];
//                        foreach ($qt as $k => $v)
//                        {
//                            if ($k > 0)
//                            {
//                                $cabData = $qt[$k];
//                            }
//                        }
                        // $routeData = $qt['routeData'];

                        if ($cabData->success)
                        {
                            $routeDistance                      = $cabData->routeDistance;
                            $routeDuration                      = $cabData->routeDuration;
                            $routeRates                         = $cabData->routeRates;
                            $amount                             = round($routeRates->baseAmount);
                            $model->bkg_gozo_base_amount        = $amount;
                            $model->bkg_base_amount             = $amount;
                            $model->bkg_trip_distance           = $routeDistance->tripDistance;
                            $model->bkg_trip_duration           = (string) $routeDuration->totalMinutes;
                            $model->bkg_driver_allowance_amount = $routeRates->driverAllowance;
                            $model->bkg_chargeable_distance     = $routeDistance->quotedDistance;
                            $model->bkg_garage_time             = $routeDuration->totalMinutes + $routeDuration->garageTimeEnd + $routeDuration->garageTimeStart;
                            $model->bkg_is_toll_tax_included    = $routeRates->isTollIncluded;
                            $model->bkg_is_state_tax_included   = $routeRates->isStateTaxIncluded;

                            $model->bkg_toll_tax  = $routeRates->tollTaxAmount;
                            $model->bkg_state_tax = $routeRates->tollTaxAmount;

                            $model->bkg_vendor_amount        = round($routeRates->vendorAmount);
                            $model->bkg_quoted_vendor_amount = round($routeRates->vendorAmount);
                            if ($model->bkg_spl_req_carrier == 1)
                            {
                                $model->bkg_additional_charge        = 150;
                                $model->bkg_additional_charge_remark = 'Carrier Requested for Rs.150';
                            }
                            else
                            {
                                $model->bkg_additional_charge        = 0;
                                $model->bkg_additional_charge_remark = '';
                            }

//                        $hike = 0;
//                        $pickupDate = date('Y-m-d', strtotime($model->bkg_pickup_date));
//                        if ($pickupDate == "2016-08-15" OR $pickupDate == "2016-08-14" OR $pickupDate == "2016-08-13") {
//                            $hike = 0.1;
//                        }

                            if ($amount > 0)
                            {
                                $model->calculateConvenienceFee(0);
                                //  $model->calculateTotal();
                                $model->populateAmount(true, false, true, true, $model->bkg_agent_id);
                                // $model->calculateVendorAmount();
                            }

                            $model->bkg_user_last_updated_on = new CDbExpression('NOW()');
                            $user_id                         = (Yii::app()->user->getId() > 0) ? Yii::app()->user->getId() : '';
                            if ($user_id == '')
                            {
                                $userModel = Users::model()->linkUserByEmail($model->bkg_id, Booking::Platform_Agent);
                                if ($userModel)
                                {
                                    $user_id = $userModel->user_id;
                                }
                            }
                            if ($user_id)
                            {
                                $model->bkg_user_id = $user_id;
                                // $usrmodel = new Users();
                                // $usrmodel->resetScope()->findByPk($user_id);
                            }

//                        if (!Yii::app()->user->isGuest) {
//                            $user = Yii::app()->user->loadUser();
//                            $model->bkg_user_id = $user->user_id;
////						$model->bkg_user_name = $user->usr_name;
////						$model->bkg_user_lname = $user->usr_lname;
//                        }
                            $model->bkg_user_ip      = \Filter::getUserIP();
                            $cityinfo                = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
                            $model->bkg_user_city    = $cityinfo['city'];
                            $model->bkg_user_country = $cityinfo['country'];
                            $model->bkg_user_device  = UserLog::model()->getDevice();
                            $model->setPaymentExpiryTime();
                            $model->scenario         = 'cabRate';

                            if ($model->validate() && !$model->hasErrors())
                            {
                                try
                                {

                                    $model->bkg_no_person            = 2;
                                    $model->bkg_user_last_updated_on = new CDbExpression('NOW()');
                                    $tmodel                          = Terms::model()->getText(1);
                                    $model->bkg_tnc_id               = $tmodel->tnc_id;
                                    $model->bkg_tnc_time             = new CDbExpression('NOW()');
                                    $isRealtedBooking                = $model->findRelatedBooking($model->bkg_id);
                                    $model->bkg_is_related_booking   = ($isRealtedBooking) ? 1 : 0;
                                    if (!$model->save())
                                    {
                                        throw new Exception("Failed to create booking", 101);
                                    }
                                    $preBrtid                      = 0;
                                    $s                             = 0;
//                            foreach ($reqRtData as $brtid => $brtVal) {
//                                $brtModel = BookingRoute::model()->findByPk($brtid);
//                                $brtModel->setScenario('rtupdate');
//                                if ($s > 0) {
//                                    $brtModel->brt_from_location = $reqRtData[$preBrtid]['brt_to_location'];
//                                    $brtModel->brt_from_pincode = $reqRtData[$preBrtid]['brt_to_pincode'];
//                                }
//                                if ($s == 0) {
//                                    $model->bkg_pickup_address = $brtVal['brt_from_location'];
//                                    $model->bkg_pickup_pincode = $brtVal['brt_from_pincode'];
//                                    $brtModel->brt_from_location = $brtVal['brt_from_location'];
//                                    $brtModel->brt_from_pincode = $brtVal['brt_from_pincode'];
//                                }
//
//                                $brtModel->brt_to_location = $brtVal['brt_to_location'];
//                                $brtModel->brt_to_pincode = $brtVal['brt_to_pincode'];
//                                $preBrtid = $brtid;
//                                $brtModel->brt_bkg_id = $model->bkg_id;
//                                $brtModel->save();
//
//                                $s++;
//                            }
                                    // $model->bkg_drop_address = $reqRtData[$brtid]['brt_to_location'];
                                    // $model->bkg_drop_pincode = $reqRtData[$brtid]['brt_to_pincode'];
                                    $model->save();
                                    $bookingCab                    = $model->getBookingCabModel();
                                    $bookingCab->bcb_vendor_amount = $model->bkg_vendor_amount;
                                    $bookingCab->bcb_bkg_id1       = $model->bkg_id;
                                    $bookingCab->save();

                                    $bkgid          = $model->bkg_id;
                                    // $desc      = "Booking created by Agent.";
                                    $processedRoute = BookingLog::model()->logRouteProcessed($cabData, $model->bkg_id);

                                    $desc = "Booking created by agent - $processedRoute";

                                    $userInfo = UserInfo::getInstance();

                                    $eventid = BookingLog::BOOKING_CREATED;
                                    BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid);

                                    $transaction->commit();
                                    $GLOBALS["bkg_id"] = $model->bkg_id;
                                    $model->hash       = Yii::app()->shortHash->hash($model->bkg_id);
                                    $GLOBALS["hash"]   = Yii::app()->shortHash->hash($model->bkg_id);
                                    $this->forward("booking/finalbook", true);
                                }
                                catch (Exception $e)
                                {
                                    $btmodel->addError('bkg_id', $e->getMessage());
                                    $model->addError('bkg_id', $e->getMessage());
                                    //	echo json_encode($model->getErrors());
                                    //	Yii::app()->end();
                                    $transaction->rollback();
                                }
                            }


                            $success = !$model->hasErrors();
                        }
                        else
                        {
                            if ($arrQuot['error'] == 1 || $arrQuot['error'] == 2)
                            {
                                $model->addError('bkg_id', 'An unknown error occured. Contact Customer care or try again.');
                            }
                            if ($arrQuot['error'] == 3)
                            {
                                $model->addError('bkg_pickup_address', 'Please provide valid address');
                            }
                            $success = false;
                        }
                        if ($success)
                        {
                            $data = ['id' => $bkgid, 'hash' => Yii::app()->shortHash->hash($bkgid)];
                        }
                        else
                        {
                            $errors = [];
                            foreach ($model->getErrors() as $attribute => $error)
                            {
                                $errors[CHtml::activeId($model, $attribute)] = $error;
                            }
                            $data = ["errors" => $errors];
                        }


                        $return['success'] = $success;
                        $return['res']     = '';
                        $return['type']    = $model->bkg_booking_type;
                        if ($success)
                        {
                            $return['data'] = $data;
                        }
                        else
                        {
                            $return["errors"] = $model->getErrors();
                        }
                    }
                    else
                    {

                        $result = CJSON::encode($arrResult['errors']);
                        $transaction->rollback();
                        //$btmodel->addError('bkg_id', 'Error');
                        //throw new CHttpException(400, 'Invalid data');
                        $return = ['success' => false, 'errors' => CJSON::decode($result)];
                    }
                }
                else
                {
                    $return = ['success' => false, 'errors' => CJSON::decode($result)];
                }
            }
            else
            {
                $return = ['success' => false, 'errors' => CJSON::decode($result)];
            }
            if (Yii::app()->request->isAjaxRequest)
            {
                echo CJSON::encode($return);
                Yii::app()->end();
            }
        }





        if (isset($_POST['step']) && $_POST['step'] == 3)
        {

            if (isset($GLOBALS['bkg_id']))
            {
                $bkgid         = $GLOBALS['bkg_id'];
                $hash          = $GLOBALS['hash'];
                $btmodel->hash = $hash;
            }

            /* @var $btmodel Booking */
            $booking = Yii::app()->request->getParam("BookingTemp");
            if ($booking != null && !Yii::app()->request->isAjaxRequest)
            {
                $bkgid         = $booking['bkg_id'];
                $hash          = Yii::app()->request->getParam('hash');
                $btmodel->hash = $hash;
                if ($bkgid != Yii::app()->shortHash->unHash($hash))
                {
                    throw new CHttpException(400, 'Invalid data');
                }
            }
            if ($bkgid)
            {
                $btmodel = BookingTemp::model()->findByPk($bkgid);
                if (!$btmodel)
                {
                    throw new CHttpException(400, 'Invalid data');
                }

                if (!Yii::app()->user->isGuest)
                {
                    $userId               = Yii::app()->user->getId();
                    $userModel            = Users::model()->findByPk($userId);
                    $btmodel->bkg_user_id = $userModel->user_id;
                    if ($userModel->usr_mobile != '' && $btmodel->bkg_contact_no == '')
                    {
                        $btmodel->bkg_contact_no   = $userModel->usr_mobile;
                        $btmodel->bkg_country_code = $userModel->usr_country_code;
                    } if ($userModel->usr_email != '' && $btmodel->bkg_user_email == '')
                    {
                        $btmodel->bkg_user_email = $userModel->usr_email;
                    }
                    $btmodel->bkg_user_name  = $userModel->usr_name;
                    $btmodel->bkg_user_name  = $userModel->usr_name;
                    $btmodel->bkg_user_lname = $userModel->usr_lname;

//$model->full_name = $model->bkg_user_name . ' ' . $model->bkg_user_lname;
                }
                $route_data = CJSON::decode($btmodel->bkg_route_data, true);
                if ($btmodel->bkg_booking_type == 4)
                {
                    if ($btmodel->bkg_transfer_type == 1)
                    {
                        $airportAddress                     = $btmodel->bkgFromCity->cty_garage_address;
                        $btmodel->bkg_pickup_address        = $airportAddress;
                        $route_data[0]['brt_from_location'] = $airportAddress;
                    }
                    if ($btmodel->bkg_transfer_type == 2)
                    {
                        $airportAddress                   = $btmodel->bkgToCity->cty_garage_address;
                        $btmodel->bkg_drop_address        = $airportAddress;
                        $route_data[0]['brt_to_location'] = $airportAddress;
                    }
                }

                foreach ($route_data as $k => $v)
                {
                    $bookingRoute             = new BookingRoute();
                    $bookingRoute->attributes = $v;
                    $bookingRoutes[]          = $bookingRoute;
                }
                $btmodel->bookingRoutes = $bookingRoutes;
            }
        }


        $btmodel->hash = $GLOBALS['hash'];

        $this->$method('conview', array('model' => $btmodel), false, true);
    }

    public function actionFinalbook()
    {
        $model = new Booking('new');
        if (isset($_POST['step']) && $_POST['step'] == 4)
        {

            if (isset($GLOBALS['bkg_id']))
            {
                $bkgid       = $GLOBALS['bkg_id'];
                $hash        = $GLOBALS['hash'];
                $model->hash = $hash;
            }

            /* @var $model Booking */
            $booking = Yii::app()->request->getParam("Booking");
            if ($booking != null && !Yii::app()->request->isAjaxRequest)
            {
                $bkgid       = $booking['bkg_id'];
                $hash        = Yii::app()->request->getParam('hash');
                $model->hash = $hash;
                if ($bkgid != Yii::app()->shortHash->unHash($hash))
                {
                    throw new CHttpException(400, 'Invalid data');
                }
            }
            $promoArr = [];
            if ($bkgid)
            {
                $model       = Booking::model()->findByPk($bkgid);
                $model->hash = Yii::app()->shortHash->hash($bkgid);
                if (!$model)
                {
                    throw new CHttpException(400, 'Invalid data');
                }

                //$model1 = clone $model;
                $model->calculateConvenienceFee(0);
                //  $model1->calculateConvenienceFee(0);
                //adv discount
                if ($model1->bkg_promo_code != '' && $model1->bkg_status == 1)
                {
                    $discount11 = Promotions::model()->getDiscount($model1, trim($model1->bkg_promo_code));
                    $promoModel = Promos::model()->getByCode($model1->bkg_promo_code);
                    if ($discount11 > 0 && $promoModel->prm_activate_on == 1)
                    {
                        if ($promoModel->prm_type == 1 && $model1->bkg_due_amount > 0)
                        {
                            $model1->bkg_discount_amount = $discount11;
                        }
                    }
                }
                //adv discount
                //  $model1->calculateTotal();
                $model->calculateTotal();

                if (!Yii::app()->user->isGuest)
                {
                    $userId             = Yii::app()->user->getId();
                    $userModel          = Users::model()->findByPk($userId);
                    $model->bkg_user_id = $userModel->user_id;
                    if ($userModel->usr_mobile != '' && $model->bkg_contact_no == '')
                    {
                        $model->bkg_contact_no   = $userModel->usr_mobile;
                        $model->bkg_country_code = $userModel->usr_country_code;
                    } if ($userModel->usr_email != '' && $model->bkg_user_email == '')
                    {
                        $model->bkg_user_email = $userModel->usr_email;
                    }
                    $model->bkg_user_name  = $userModel->usr_name;
                    $model->bkg_user_name  = $userModel->usr_name;
                    $model->bkg_user_lname = $userModel->usr_lname;
                    if ($model->bkg_user_id != '')
                    {
                        $usePromo  = ($model->bkg_promo_code == '');
                        $credits   = UserCredits::getApplicableCredits($model->bkg_user_id, $model1->bkg_base_amount, $usePromo, $model->bkg_from_city_id, $model->bkg_to_city_id);
                        $creditVal = $credits['credits'];
                    }
                }
                //   if ($model->bkg_user_id != '') {
                //  $promoAutoArr = Promotions::model()->getAutoApplyCodes($model->bkg_user_id);
//                foreach ($promoAutoArr as $key => $value) {
//                    $promoArr[$key]['prm_id'] = $value['prm_id'];
//                    $promoArr[$key]['val'] = $value['prm_code'];
//                    $promoArr[$key]['desc'] = $value['prm_desc'];
//                    if ($value['prm_type'] == 1) {
//                        $promoArr[$key]['desc'] = $value['prm_desc'] . " *<a href='#' onclick='showTcGozoCoins1()'>T&C</a> Apply";
//                    }
//                    if ($value['prm_type'] == 2) {
//                        $promoArr[$key]['desc'] = $value['prm_desc'] . " *<a href='#' onclick='showTcGozoCoins2()'>T&C</a> Apply";
//                    }
//                    $promoArr[$key]['activateOn'] = $value['prm_activate_on'];
//                }

                $discCond = BookingSub::model()->getExpTimeAdvPromo($model->bkg_create_date, $model->bookingRoutes[0]->brt_pickup_datetime);
                if ($discCond == 5)
                {
                    $promoArr[0]['prm_id']     = 50;
                    $promoArr[0]['val']        = 'GET5PDISC';
                    $promoArr[0]['desc']       = 'Pay at least 15% of total amount and get 5% instant discount' . " *<a href='#' onclick='showTcGozoCoins1()'>T&C</a> Apply";
                    $promoArr[0]['activateOn'] = 1;
                    $promoArr[0]['type']       = 1;

                    $promoArr[1]['prm_id']     = 49;
                    $promoArr[1]['val']        = 'GET50PCB';
                    $promoArr[1]['desc']       = 'Pay at least 15% of total amount and get 50% cashback' . " *<a href='#' onclick='showTcGozoCoins2()'>T&C</a> Apply";
                    $promoArr[1]['activateOn'] = 1;
                    $promoArr[1]['type']       = 2;
                }
                if ($discCond == 2.5)
                {
                    $promoArr[0]['prm_id']     = 64;
                    $promoArr[0]['val']        = 'GET2P5PDISC';
                    $promoArr[0]['desc']       = 'Pay at least 15% of total amount and get 2.5% instant discount' . " *<a href='#' onclick='showTcGozoCoins2p5()'>T&C</a> Apply";
                    $promoArr[0]['activateOn'] = 1;
                    $promoArr[0]['type']       = 1;

                    $promoArr[1]['prm_id']     = 65;
                    $promoArr[1]['val']        = 'GET25PCB';
                    $promoArr[1]['desc']       = 'Pay at least 15% of total amount and get 25% cashback' . " *<a href='#' onclick='showTcGozoCoins25()'>T&C</a> Apply";
                    $promoArr[1]['activateOn'] = 1;
                    $promoArr[1]['type']       = 2;
                }
//
                // }
            }
        }

        if (isset($_POST['step']) && $_POST['step'] == 5)
        {

            $hash = Yii::app()->request->getParam('hash');
            if (isset($_REQUEST['Booking']))
            {


                $arr   = Yii::app()->request->getParam("Booking");
                $ctype = Yii::app()->request->getParam("ctype");
                if ($arr['bkg_id'] > 0)
                {
                    $bkg_id = $arr['bkg_id'];
                    $hash   = $arr['hash'];

                    if ($bkg_id != Yii::app()->shortHash->unHash($hash))
                    {
                        throw new CHttpException(400, 'Invalid data');
                    }
                    /* @var $model Booking */
                    $model             = Booking::model()->findByPk($bkg_id);
                    $model->scenario   = 'tncAgent';
                    $reqData           = Yii::app()->request->getParam('Booking');
                    $model->attributes = $reqData;

                    $model->calculateConvenienceFee(0);

                    if ($arr['agentBkgAmountPay'] == 2)
                    {
                        //$model->bkg_corporate_credit = $arr['agentCreditAmount'];
                        $model->bkg_corporate_remunerator = 2;
                        // $model->bkg_convenience_charge;
                        //$model->bkg_due_amount = $model->bkg_total_amount - $model->bkg_advance_amount + round($model->bkg_refund_amount) - $model->bkg_credits_used - $model->bkg_vendor_collected ;
                    }
//                    if ($arr['bkg_trvl_sendupdate'] == 1) {
//                        $model->bkg_send_email = $arr['bkg_send_email'];
//                        $model->bkg_send_sms = $arr['bkg_send_sms'];
//                    }
                    //  if ($arr['bkg_trvl_sendupdate'] == 2) {
                    $model->bkg_send_email = 1;
                    $model->bkg_send_sms   = 1;
                    //  }

                    $model->calculateTotal();
                    if ($model->bkg_total_amount < $arr['agentCreditAmount'])
                    {
                        $model->addError('agentCreditAmount', 'Amount exceeding total booking amount');
                    }


                    $result = CActiveForm::validate($model);
                    if ($result == '[]')
                    {
                        $transaction = Yii::app()->db->beginTransaction();
                        if ($model->validate())
                        {
                            $model->save();
                            try
                            {
                                if ($model->bkg_status == 1 && $model->bkg_user_id > 0)
                                {
                                    $logType     = UserInfo::TYPE_AGENT;
                                    $agentsModel = Agents::model()->findByPk($model->bkg_agent_id);
                                    if ($model->bkg_agent_id > 0 && $agentsModel->agt_type == 1)
                                    {
                                        $logType = UserInfo::TYPE_CORPORATE;
                                    }
                                    if ($model != '' && Yii::app()->user->getId() != '' && $_REQUEST['creditapplied'] > 0)
                                    {
                                        $platform = UserInfo::TYPE_AGENT;
                                        //$success	 = Transactions::model()->paymentCreditsUsed($model->bkg_id, PaymentType::TYPE_GOZO_COINS, $_REQUEST['creditapplied'], $platform);
                                    }

                                    $model = Booking::model()->findByPk($bkg_id);

                                    /*
                                     *
                                     *  [agentBkgAmountPay] => (string) 2
                                      [agentCreditAmount] => (string) 5000
                                      [bkg_trvl_sendupdate] => (string) 1
                                      [bkg_send_email] => (string) 1
                                      [bkg_send_sms] => (string) 1
                                      [bkg_tnc] => (string) 1
                                     *
                                     */

                                    //customer will get discount on advance discount promo if credits used and due amount become zero after promo code applied
                                    if ($model->bkg_promo_code != '')
                                    {
                                        $discount   = Promotions::model()->getDiscount($model, trim($model->bkg_promo_code));
                                        $promoModel = Promos::model()->getByCode($model->bkg_promo_code);
                                        if ($discount > 0 && $promoModel->prm_activate_on == 1)
                                        {
                                            if ($promoModel->prm_type == 1 && $model->bkg_due_amount > 0)
                                            {
                                                $bmodel                      = clone $model;
                                                $bmodel->bkg_discount_amount = $discount;
                                                $bmodel->calculateTotal();
                                                if ($bmodel->bkg_due_amount <= 0)
                                                {
                                                    $model->bkg_discount_amount = $discount;
                                                    $model->calculateTotal();
                                                }
                                            }
                                        }
                                    }

                                    //adv discount
                                    //  if ($model->bkg_advance_amount > 0) {
//                    [agentBkgAmountPay] => (string) 1
//                    [agentCreditAmount] => (string)
//                    [bkg_copybooking_name] => (string) Ramala
//                    [bkg_copybooking_email] => (string) rnayek@gmail.com
//                    [bkg_copybooking_phone] => (string) 9191919191
//                    [bkg_copybooking_ismail] => array(1) (
//                      [0] => (string) 1
//                    )
//                    [bkg_copybooking_issms] => (string)
//                    [bkg_trvl_email] => (string) deepak@epitech.in
//                    [bkg_trvl_phone] => (string) 8981062962
//                    [bkg_trvl_sendupdate] => (string) 1
//                    [bkg_send_email] => (string) 0
//                    [bkg_send_sms] => (string) 1
//                    [bkg_send_app] => (string) 0
                                    /* @var Booking $model */
                                    $model->save();
                                    $model->confirmBooking($logType);
                                    //  }
                                    $model->bkg_corporate_credit = $arr['agentCreditAmount'];
                                    $amount                      = $model->bkg_corporate_credit | 0; //Credit added by agent;
                                    if ($amount > 0)
                                    {
                                        $desc    = "Partner Wallet Used";
                                        //AgentTransactions::model()->addBookingTransactionData($model->bkg_id, $model->bkg_agent_id, $amount, $desc);
                                        $agtcomm = $model->updateAdvance($amount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, UserInfo::getInstance(), null, $desc);
//										if (!$agtcomm)
//										{
//											throw new Exception("Booking failed as partner credit limit exceeded.");
//									}
                                    }

                                    $return['success'] = true;
                                    $return['id']      = $model->bkg_id;
                                    $return['hash']    = Yii::app()->shortHash->hash($model->bkg_id);

                                    $transaction->commit();
                                    $emailAgtArr = [];
                                    if ($arr['bkg_trvl_sendupdate'] == 1 && $arr['bkg_send_email'] == 1 && $arr['bkg_trvl_email'] != '')
                                    {
                                        $emailAgtArr['trvl'] = ['email' => $arr['bkg_trvl_email'], 'name' => $model->bkg_user_name . " " . $model->bkg_user_lname];
                                    }
                                    if ($arr['bkg_copybooking_ismail'][0] == 1 && $arr['bkg_copybooking_name'] != '' && $arr['bkg_copybooking_email'] != '')
                                    {
                                        $emailAgtArr['agent'] = ['email' => $arr['bkg_copybooking_email'], 'name' => $arr['bkg_copybooking_name']];
                                    }
                                    $emailCom = new emailWrapper();
                                    $emailCom->gotBookingemail($model->bkg_id, $logType, $model->bkg_agent_id, '', ['agentconfbook' => 1, 'emails' => $emailAgtArr]);
                                    $emailCom->gotBookingAgentUser($model->bkg_id);

                                    $smsAgtArr = [];
                                    if ($arr['bkg_send_sms'] == 1 && $arr['bkg_trvl_sendupdate'] == 1 && $arr['bkg_trvl_phone'] != '')
                                    {
                                        $smsAgtArr['trvl_sms'] = ['phone' => $arr['bkg_trvl_phone'], 'country_code' => $model->bkg_country_code];
                                    }
                                    if ($arr['bkg_copybooking_issms'][0] == 1 && $arr['bkg_copybooking_name'] != '' && $arr['bkg_copybooking_phone'] != '')
                                    {
                                        $smsAgtArr['agent_sms'] = ['phone' => $arr['bkg_copybooking_phone'], 'country_code' => '91'];
                                    }
                                    $msgCom = new smsWrapper();
                                    $msgCom->gotBooking($model, $logType, ['agentconfbook' => 1, 'phones' => $smsAgtArr]);
                                    //   $model->sendConfirmation($logType);
                                }
                            }
                            catch (Exception $e)
                            {
                                $model->addError('bkg_id', $e->getMessage());
                                $transaction->rollback();
                            }
                        }
                        $success = !$model->hasErrors();
                        if ($success)
                        {
// $return = ['id' => $model->bkg_id, 'hash' => Yii::app()->shortHash->hash($model->bkg_id)];
                            $url = '';

//                            if ($ctype == 'p1') {
//                                // $url = Yii::app()->createUrl('booking/paynow', ['id' => $model->bkg_id, 'hash' => Yii::app()->shortHash->hash($model->bkg_id)]);
//                                $url = Yii::app()->createUrl('agent/booking/summary', ['action' => 'done', 'id' => $model->bkg_id, 'hash' => Yii::app()->shortHash->hash($model->bkg_id)]);
//                            }
//                            if ($ctype == 'c1') {
//                                $url = Yii::app()->createUrl('agent/booking/summary', ['action' => 'done', 'id' => $model->bkg_id, 'hash' => Yii::app()->shortHash->hash($model->bkg_id)]);
//                            }
                            $url            = Yii::app()->createUrl('agent/booking/summary', ['action' => 'done', 'id' => $model->bkg_id, 'hash' => Yii::app()->shortHash->hash($model->bkg_id)]);
                            $return ['url'] = $url;
                        }
                        else
                        {
                            $return = ["errors" => $model->getErrors()];
                        }
                        $return ['success'] = $success;
                    }
                    else
                    {
                        $return = ['success' => false, 'errors' => CJSON::decode($result)];
                    }
                    if (Yii::app()->request->isAjaxRequest)
                    {
                        echo CJSON::encode($return);
                        Yii::app()->end();
                    }
                    Yii::app()->end();
                }
            }
        }



        $agtId                        = Yii::app()->user->getAgentId();
        $agtModel                     = Agents::model()->findByPk($agtId);
        //  $model->bkg_trvl_sendupdate = $agtModel->agt_trvl_sendupdate | 1;
        $model->bkg_copybooking_name  = $agtModel->agt_copybooking_name;
        $model->bkg_copybooking_email = $agtModel->agt_copybooking_email;
        $model->bkg_copybooking_phone = $agtModel->agt_copybooking_phone;

        //   $model->bkg_copybooking_ismail = $agtModel->agt_copybooking_ismail;
        //   $model->bkg_copybooking_issms = $agtModel->agt_copybooking_issms;
        $model->bkg_trvl_email = $model->bkg_user_email;
        $model->bkg_trvl_phone = $model->bkg_contact_no;
        $model->bkg_send_email = $agtModel->agt_trvl_isemail;
        $model->bkg_send_sms   = $agtModel->agt_trvl_issms;
        $model->bkg_send_app   = $agtModel->agt_trvl_isapp;

        $model->agentBkgAmountPay = 1;
        $model->agentCreditAmount = $model->bkg_total_amount;

        $this->renderPartial('booksummary', array('model' => $model, 'creditVal' => $creditVal, 'model1' => $model, 'promoArr' => $promoArr), false, true);
    }

    public function actionSummary()
    {
        $this->pageTitle = "";
        $id              = Yii::app()->request->getParam('id');
        $hash            = Yii::app()->request->getParam('hash');
        $actiondone      = Yii::app()->request->getParam('action');
        if ($actiondone == 'done')
        {
            $showAdditional = true;
        }
        Logger::trace("Quote to book summary" . $id);
//$transCode = Yii::app()->request->getParam('tinfo');
        $transCode = Yii::app()->request->getParam('tinfo');

        if ($id > 0)
        {
            $model = Booking::model()->findByPk($id);
        }
        if ($id != Yii::app()->shortHash->unHash($hash))
        {
            throw new CHttpException(400, 'Invalid data');
        }
        if (!$model)
        {
            throw new CHttpException(400, 'Invalid data');
        }
        $model->hash = $hash;
        $model->bkgAddInfo->setScenario("additionalInfo");
        $transId     = '';
        $succ        = '';

        if ($transCode != '')
        {
            $payment     = true;
            $transResult = PaymentGateway::model()->getTransdetailByTranscode($transCode);
            if ($transResult)
            {

                $transId    = $transResult['transId'];
                $succ       = $transResult['succ'];
                $tranStatus = $transResult['tranStatus'];
            }
        }
        Logger::trace("Quote to book transcode" . $transCode);
        if (isset($_POST['Booking']) || isset($_POST['BookingAddInfo']) || isset($_POST['BookingTrail']))
        {
            $reqData      = Yii::app()->request->getParam('Booking');
            $reqDataAdd   = Yii::app()->request->getParam('BookingAddInfo');
            $reqDataTrail = Yii::app()->request->getParam('BookingTrail');
            $reqRtData    = Yii::app()->request->getParam('BookingRoute');

            $bkgid = $reqData['bkg_id'];
            $hash  = $reqData['hash'];
            Logger::trace("Quote to Booking" . $bkgid);
            if ($bkgid != Yii::app()->shortHash->unHash($hash))
            {
                throw new CHttpException(400, 'Invalid data');
            }
            /* @var $model Booking */
            $model = Booking::model()->findByPk($bkgid);
            $model->bkgAddInfo->setScenario("additionalInfo");

            $model->attributes             = $reqData;
            $model->bkgAddInfo->attributes = $reqDataAdd;
            $model->bkgTrail->attributes   = $reqDataTrail;
            $showAdditional                = false;

            $brtArr = [];
            foreach ($reqRtData as $brtVal)
            {
                $brtArr[] = $brtVal;
            }
            $cntBrt  = sizeof($reqRtData);
            $result  = CActiveForm::validate($model, null, false);
            $result1 = '[]';
            if (isset($_POST['BookingAddInfo']))
            {
                $result1 = CActiveForm::validate($model->bkgAddInfo, null, false);
            }
            //Logger::trace("Quote to book additional info". $model->bkgAddInfo);
            if ($result == '[]' && $result1 == '[]')
            {
                $splRemark = 'Carrier Requested for Rs.150';
                if ($model->bkgAddInfo->bkg_spl_req_carrier == 1 && !strstr($model->bkgInvoice->bkg_additional_charge_remark, $splRemark))
                {
                    $model->bkgInvoice->bkg_additional_charge = $model->bkgInvoice->bkg_additional_charge + 150;

                    $model->bkgInvoice->bkg_additional_charge_remark = ($model->bkgInvoice->bkg_additional_charge_remark == '') ? $splRemark : $model->bkgInvoice->bkg_additional_charge_remark . ', ' . $splRemark;
                }
                else if ($model->bkgAddInfo->bkg_spl_req_carrier == 0 && strstr($model->bkgInvoice->bkg_additional_charge_remark, $splRemark))
                {
                    $model->bkgInvoice->bkg_additional_charge        = $model->bkgInvoice->bkg_additional_charge - 150;
                    $model->bkgInvoice->bkg_additional_charge_remark = trim(str_replace($splRemark, '', $model->bkgInvoice->bkg_additional_charge_remark));
                    $model->bkgInvoice->bkg_additional_charge_remark = rtrim($model->bkgInvoice->bkg_additional_charge_remark, ',');
                }
                //$model->calculateConvenienceFee();
                //$model->calculateTotal();
                //$model->populateAmount();
                $model->bkgInvoice->calculateAgentMarkup($model->bkg_agent_id);
                $model->bkgInvoice->calculateTotal();
                $model->bkgInvoice->calculateVendorAmount();
                //   $model->changeAgentMarkup();
                $userInfo = UserInfo::getInstance();
                $eventId  = BookingLog::REMARKS_ADDED;
                $remark   = $splRemark;
                BookingLog::model()->createLog($model->bkg_id, $remark, $userInfo, $eventId);

                // $model->sendConfirmation($userType);


                $model->bkgUserInfo->bkg_user_last_updated_on = new CDbExpression('NOW()');

                $transaction = Yii::app()->db->beginTransaction();
                try
                {
                    $preBrtid = 0;
                    $s        = 0;
                    foreach ($reqRtData as $brtid => $brtVal)
                    {
                        $brtModel = BookingRoute::model()->findByPk($brtid);
                        $brtModel->setScenario('rtupdate');
                        if ($s > 0)
                        {
                            $brtModel->brt_from_pincode = $reqRtData[$preBrtid]['brt_to_pincode'];
                        }
                        if ($s == 0)
                        {
                            //$model->bkg_pickup_pincode  = $brtVal['brt_from_pincode'];
                            $brtModel->brt_from_pincode = $brtVal['brt_from_pincode'];
                        }
                        $brtModel->brt_to_pincode = $brtVal['brt_to_pincode'];

                        $brtModel->brt_id;
                        $brtModel->brt_bkg_id;
                        $brtModel->save();
                        $preBrtid = $brtid;
                        $s++;
                    }

                    // $model->bkg_drop_pincode = $reqRtData[$brtid]['brt_to_pincode'];
                    $model->save();
                    $model->bkgAddInfo->save();
                    $model->bkgInvoice->save();
                    $model->bkgPf->save();
                    $model->bkgUserInfo->save();
                    $model->bkgTrail->save();

                    $bkgid    = $model->bkg_id;
                    $desc     = "Additional Details added to Booking by agent.";
                    $userInfo = UserInfo::getInstance();

                    $eventid = BookingLog::BOOKING_MODIFIED;
                    BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid);

                    $transaction->commit();
                    $GLOBALS["bkg_id"] = $model->bkg_id;
                    $model->hash       = Yii::app()->shortHash->hash($model->bkg_id);
                    $GLOBALS["hash"]   = Yii::app()->shortHash->hash($model->bkg_id);
                }
                catch (Exception $e)
                {
                    $model->addError('bkg_id', $e->getMessage());
                    $transaction->rollback();
                }

                $success = !$model->hasErrors();
            }
            Logger::trace("Quote to book transcode" . $transCode);
            if ($success)
            {
                $data = ['success' => $success, 'id' => $bkgid, 'hash' => Yii::app()->shortHash->hash($bkgid)];
            }
            else
            {
                $arrErrors = ($result == '[]') ? $result1 : $result;
                $data      = ['success' => $success, 'id' => $bkgid, 'hash' => Yii::app()->shortHash->hash($bkgid), 'error' => json_decode($arrErrors, true)];
            }
            Logger::trace("Quote to book success" . $success);
            if (Yii::app()->request->isAjaxRequest)
            {
                $obj->data = json_decode($data);
                echo CJSON::encode($data);
                Yii::app()->end();
            }
        }

        $agentModel = Agents::model()->findByPk($model->bkg_agent_id);
        $model->bkgInvoice->bkg_is_state_tax_included;
        $this->render('summary', ['model' => $model, 'succ' => $succ, 'isApproved' => $agentModel->agt_approved, 'transid' => $transId, 'payment' => $payment, 'showAdditional' => $showAdditional]);
    }

    public function actionList()
    {
        $this->pageTitle = "Booking History";
        $model           = new Booking();
        $submodel        = new BookingSub();
        $paramArray      = Yii::app()->request->getParam('Booking');
        $agentId         = Yii::app()->user->getAgentId();
        if (isset($_REQUEST['Booking']))
        {
            $model->attributes       = $paramArray;
            $model->bkg_pickup_date1 = $paramArray['bkg_pickup_date1'];
            $model->bkg_pickup_date2 = $paramArray['bkg_pickup_date2'];
            $model->bkg_create_date1 = $paramArray['bkg_create_date1'];
            $model->bkg_create_date2 = $paramArray['bkg_create_date2'];
        }
        else
        {
            $model->bkg_status       = '';
            $model->bkg_from_city_id = '';
            $model->bkg_to_city_id   = '';
            if ($_REQUEST['page'] < 2)
            {
                $model->bkg_pickup_date1        = $paramArray['bkg_pickup_date1'] = ($paramArray['bkg_pickup_date1'] == '') ? date('Y-m-d', strtotime('-1 month')) : $paramArray['bkg_pickup_date1'];
                $model->bkg_pickup_date2        = $paramArray['bkg_pickup_date2'] = ($paramArray['bkg_pickup_date2'] == '') ? date('Y-m-d', strtotime('+11 month')) : $paramArray['bkg_pickup_date2'];
                $model->bkg_create_date1        = $paramArray['bkg_create_date1'] = ($paramArray['bkg_create_date1'] == '') ? date('Y-m-d', strtotime('-1 month')) : $paramArray['bkg_create_date1'];
                $model->bkg_create_date2        = $paramArray['bkg_create_date2'] = ($paramArray['bkg_create_date2'] == '') ? date('Y-m-d') : $paramArray['bkg_create_date2'];
            }
            else
            {
                $model->bkg_pickup_date1 = $paramArray['bkg_pickup_date1'];
                $model->bkg_pickup_date2 = $paramArray['bkg_pickup_date2'];
                $model->bkg_create_date1 = $paramArray['bkg_create_date1'];
                $model->bkg_create_date2 = $paramArray['bkg_create_date2'];
            }
        }

        $dataProvider = $submodel->listByAgent(Yii::app()->user->getAgentId(), $paramArray, Yii::app()->user->getCorpCode(), $type         = false);
        if (isset($_REQUEST['export_from_city']) && isset($_REQUEST['export_to_city']) && isset($_REQUEST['export_search']))
        {
            $fromCity                       = Yii::app()->request->getParam('export_from_city');
            $toCity                         = Yii::app()->request->getParam('export_to_city');
            $search                         = Yii::app()->request->getParam('export_search');
            $status                         = Yii::app()->request->getParam('export_status');
            $fromDate                       = Yii::app()->request->getParam('export_from_date');
            $toDate                         = Yii::app()->request->getParam('export_to_date');
            $createFromDate                 = Yii::app()->request->getParam('create_from_date');
            $createToDate                   = Yii::app()->request->getParam('create_to_date');
            $paramArray['bkg_pickup_date1'] = $fromDate;
            $paramArray['bkg_pickup_date2'] = $toDate;
            $paramArray['bkg_create_date1'] = $createFromDate;
            $paramArray['bkg_create_date2'] = $createToDate;
            $paramArray['bkg_from_city_id'] = $fromCity;
            $paramArray['bkg_to_city_id']   = $toCity;
            $paramArray['bkg_status']       = $status;
            $paramArray['search']           = $search;
            $rows                           = $submodel->listByAgent(Yii::app()->user->getAgentId(), $paramArray, Yii::app()->user->getCorpCode(), $type                           = true);
            header('Content-type: text/csv');
            header("Content-Disposition: attachment; filename=\"Report_" . date('Ymdhis') . ".csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle                         = fopen("php://output", 'w');
            if ($agentId == Config::get('spicejet.partner.id'))
            {
                fputcsv($handle, ['Booking Referral ID', 'Gozo Bkg ID', 'Name', 'Booking Date Time', 'Pickup Date Time', 'Email', 'Phone', 'From City', 'To City', 'Amount', 'Partner Credit', 'Partner Commission', 'Partner Extra Commission', 'Commission On GST', 'Advance Paid', 'Status']);
            }
            else
            {
                fputcsv($handle, ['Booking Referral ID', 'Gozo Bkg ID', 'Name', 'Booking Date Time', 'Pickup Date Time', 'Email', 'Phone', 'From City', 'To City', 'Amount', 'Partner Credit', 'Partner Commission', 'Commission On GST', 'Advance Paid', 'Status']);
            }
            foreach ($rows as $row)
            {
                $rowArray                           = array();
                $rowArray['bkg_agent_ref_code']     = $row['bkg_agent_ref_code'];
                $rowArray['bkg_booking_id']         = $row['bkg_booking_id'];
                $rowArray['bkg_user_name']          = $row['bkg_user_fname'] . ' ' . $row['bkg_user_lname'];
                $rowArray['bkg_create_date']        = ($row['bkg_create_date'] != '' || $row['bkg_create_date'] != NULL) ? date("d/m/Y H:i:s", strtotime($row['bkg_create_date'])) : '';
                $rowArray['bkg_pickup_date']        = ($row['bkg_pickup_date'] != '' || $row['bkg_pickup_date'] != NULL) ? date("d/m/Y H:i:s", strtotime($row['bkg_pickup_date'])) : '';
                $rowArray['bkg_user_email']         = $row['bkg_user_email'];
                $rowArray['bkg_contact_no']         = $row['bkg_contact_no'];
                $rowArray['fromCities']             = $row['fromCities'];
                $rowArray['toCities']               = $row['toCities'];
                $rowArray['bkg_total_amount']       = $row['bkg_total_amount'];
                $rowArray['bkg_corporate_credit']   = $row['bkg_corporate_credit'];
                $rowArray['bkg_partner_commission'] = $row['bkg_partner_commission'];
                if ($agentId == Config::get('spicejet.partner.id'))
                {
                    $rowArray['bkg_partner_extra_commission'] = $row['bkg_partner_extra_commission'];
                }
                $rowArray['commissionOnGst']    = $row['commissionGst'];
                $rowArray['bkg_advance_amount'] = $row['bkg_advance_amount'];
                $rowArray['status']             = $row['status'];
                $row1                           = array_values($rowArray);
                fputcsv($handle, $row1);
            }
            fclose($handle);
            Yii::log("After IN TO OUT FILE query " . $sql, CLogger::LEVEL_INFO, 'system.api.images');
            if (!$rows)
            {
                Yii::log("Can not export data " . $sql, CLogger::LEVEL_INFO, 'system.api.images');
                die('Could not take data backup: ' . mysql_error());
            }
            else
            {
                Yii::log("Exported data successfully " . $sql, CLogger::LEVEL_INFO, 'system.api.images');
            }
            exit;
        }
        $this->render('list', array('dataProvider' => $dataProvider, 'model' => $model, 'agentId' => $agentId));
    }

    public function actionView()
    {
        $bookingID = Yii::app()->request->getParam('id');
        $view      = Yii::app()->request->getParam('view', 'view');

        if ($bookingID != '')
        {
            $bookModel = Booking::model()->findByPk($bookingID);
            if (Yii::app()->user->getCorpCode() != '')
            {
                if ($bookModel->bkg_agent_id != Yii::app()->user->getAgentId())
                {
                    return false;
                }
            }
            else if ($bookModel->bkg_agent_id != Yii::app()->user->getAgentId())
            {
                return false;
            }

            $oldModel             = clone $bookModel;
            $userInfo             = UserInfo::getInstance();
            $desc                 = 'Booking Viewed';
            $params['blg_active'] = 2;
            $eventId              = BookingLog::BOOKING_VIEWED;
            BookingLog::model()->createLog($bookingID, $desc, $userInfo, $eventId, $oldModel, $params);
        }


        $models            = Booking::model()->getBookingRelationalDetails($bookingID);
        $outputJs          = Yii::app()->request->isAjaxRequest;
        $method            = "render" . ($outputJs ? "Partial" : "");
        $bookingRouteModel = BookingRoute::model()->findAll('brt_bkg_id=:id', ['id' => $bookingID]);
        $this->$method($view, array('model' => $models, 'bookingRouteModel' => $bookingRouteModel, 'isAjax' => $outputJs), false, $outputJs);
    }

    public function actionCanbooking()
    {
        $bkid       = Yii::app()->request->getParam('booking_id'); //$_POST['booking_id'];
        $reasonid   = trim(Yii::app()->request->getParam('bkreason'));
        $reasonText = Yii::app()->request->getParam('bkreasontext');

        if (isset($_POST['bkreason']) && isset($_POST['bk_id']) && $reasonid != '')
        {
            $bk_id    = Yii::app()->request->getParam('bk_id'); //$_POST['bk_id'];
            $userInfo = UserInfo::getInstance();
            $model    = Booking::model()->findByPk($bk_id);
            $oldModel = clone $model;
            $bkgid    = Booking::model()->canBooking($bk_id, $reasonText, $reasonid, $userInfo);
            if ($bkgid)
            {
//  $bkgid = $success;
                $bookingModel = Booking::model()->findByPk($bkgid);
                if ($bookingModel != '' && $bookingModel->bkgUserInfo->bkg_user_id != '')
                {
                    $notificationId = substr(round(microtime(true) * 1000), -5);
                    $payLoadData    = ['bookingId' => $bookingModel->bkg_booking_id, 'EventCode' => Booking::CODE_USER_CANCEL];
                    $success        = AppTokens::model()->notifyConsumer($bookingModel->bkgUserInfo->bkg_user_id, $payLoadData, $notificationId, "(" . $bookingModel->bkg_booking_id . ") " . $bookingModel->bkgFromCity->cty_name . " to " . $bookingModel->bkgToCity->cty_name . " on " . $bookingModel->bkg_pickup_date, $bookingModel->bkg_booking_id . " booking cancelled");
                }

                $desc = "Booking cancelled manually.(Reason: " . $reasonText . ")";

                $eventid = BookingLog::BOOKING_CANCELLED;
                BookingLog::model()->createLog($bkgid, $desc, $userInfo, $eventid, $oldModel);

                $emailObj = new emailWrapper();
                $emailObj->bookingCancellationMail($bkgid);
            }

            $this->redirect(array('list'));
        }

        $bkgCode = Booking::model()->getCodeById($bkid);
        $this->renderPartial('canbooking', array('bkid' => $bkid, 'bkgCode' => $bkgCode), false, true);
    }

    public function actionAddagentcredit()
    {
        $bkid = Yii::app()->request->getParam('booking_id');

        if (isset($_POST) && isset($_POST['bk_id']))
        {
            $bk_id        = Yii::app()->request->getParam('bk_id'); //$_POST['bk_id'];
            $arr          = $_POST['Booking'];
            $creditAmount = $arr['agentCreditAmount'];
            $userInfo     = UserInfo::getInstance();
            $model        = Booking::model()->findByPk($bk_id);
            $success      = false;
            $url          = '';
            $error        = [];
            if ($model)
            {
                $totAmount = $model->bkgInvoice->bkg_corporate_credit + $creditAmount;
                if ($model->bkgInvoice->bkg_due_amount >= $creditAmount && $model->bkgInvoice->bkg_total_amount >= $totAmount)
                {

                    $success = true;
                    $amount  = $model->bkgInvoice->bkg_corporate_credit | 0; //Credit added by agent;
                    if ($creditAmount > 0)
                    {
                        $desc    = "Partner Wallet Used";
                        $remarks = "Credit amount to be added";
                        //AgentTransactions::model()->addBookingTransactionData($model->bkg_id, $model->bkg_agent_id, $creditAmount, $desc);
                        $agtcomm = $model->updateAdvance($creditAmount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, $userInfo, null, $desc);
//						if (!$agtcomm)
//						{
//							$success = false;
//							$error[] = 'Booking failed as partner credit limit exceeded.';
//					}
                    }
                    $url = Yii::app()->createUrl('agent/booking/list');
                }
                else
                {
                    $error[] = 'Amount is exceeding required';
                }
            }
            else
            {
                $error[] = 'There is some problem with the booking';
            }
            if ($success)
            {
                $desc    = "Agent added Rs.$creditAmount as credit to the booking";
                $user_id = Yii::app()->user->getId();
                $eventid = BookingLog::AGENT_CREDIT_APPLIED;
                BookingLog::model()->createLog($bk_id, $desc, $userInfo, $eventid, $oldModel);
            }


            $return = ['success' => $success, 'errors' => $error, 'url' => $url];

            if (Yii::app()->request->isAjaxRequest)
            {
                echo json_encode($return);
                Yii::app()->end();
            }
        }
        if ($bkid > 0)
        {
            $bmodel  = Booking::model()->findByPk($bkid);
            $bkgCode = Booking::model()->getCodeById($bkid);
        }

        $this->renderPartial('addagentcredit', array('bkid' => $bkid, 'bkgCode' => $bkgCode, 'model' => $bmodel), false, true);
    }

    public function actionCreatequote()
    {

        $this->pageTitle = "Create Quote";
        $agentModel      = Agents::model()->findByPk(Yii::app()->user->getAgentId());

        if ($agentModel->agt_booking_platform == 1)
        {
            $this->forward("booking/spot", true);
        }
        $model                   = new BookingTemp('new');
        $model->bkg_booking_type = 1;
        $bktemp                  = Yii::app()->request->getParam('BookingTemp');
        $bkrut                   = Yii::app()->request->getParam('BookingRoute');

        if ($_REQUEST['step'] == 'agtrtv')
        {
            $this->forward("booking/cabagentratedetail", true);
        }
        if ($_REQUEST['step'] == 'cnview')
        {
            $this->forward("booking/agtfinalbook", true);
        }

        try
        {
            //Logger::trace("Booking::Createquote===START2==");
            if ($bktemp != null && isset($_REQUEST['BookingTemp']))
            {
                $btmodel                    = new BookingTemp('new');
                $arr                        = Yii::app()->request->getParam('BookingTemp');
                $btmodel->attributes        = $arr;
                $transfer_type              = ($arr['bkg_transfer_type'][0]) ? $arr['bkg_transfer_type'][0] : $arr['bkg_transfer_type'];
                $btmodel->bkg_transfer_type = $transfer_type | 0;
                $success                    = FALSE;
                if (!Yii::app()->user->isGuest)
                {
//Logger::trace("Booking::Createquote===START3==");
                    $userId               = Yii::app()->user->getId();
                    $userModel            = Users::model()->findByPk($userId);
                    $btmodel->bkg_user_id = $userModel->user_id;
                    $contactPhone         = ContactPhone::getPrimaryNumber($userModel->usr_contact_id);
                    if ($contactPhone)
                    {
                        $btmodel->bkg_contact_no   = $contactPhone->getNationalNumber();
                        $btmodel->bkg_country_code = $contactPhone->getCountryCode();
                    }
                    $email = ContactEmail::getPrimaryEmail($userModel->usr_contact_id);
                    if ($email)
                    {
                        $btmodel->bkg_user_email = $email;
                    }
                    $btmodel->bkg_user_name  = $userModel->usr_name;
                    $btmodel->bkg_user_name  = $userModel->usr_name;
                    $btmodel->bkg_user_lname = $userModel->usr_lname;
                }

                if ($_REQUEST['step'] == 'cquote')
                {
///Logger::trace("Booking::Createquote===START4==");
                    $btmodel->attributes        = $arr;
                    $arr['bkg_booking_type'];
                    $transfer_type              = ($arr['bkg_transfer_type'][0]) ? $arr['bkg_transfer_type'][0] : $arr['bkg_transfer_type'];
                    $btmodel->bkg_transfer_type = $transfer_type | 0;
                    $arrRt                      = Yii::app()->request->getParam('BookingRoute');

                    if ($arr['bkg_booking_type'] == 4)
                    {
//Logger::trace("Booking::Createquote===START5==");
                        $routeModel = new BookingRoute();
//Logger::trace("Booking::Createquote===START51==");
                        $placeObj   = $arrRt['place'];
//Logger::trace("Booking::Createquote===START52==". json_encode($placeObj));
                        if (is_string($placeObj))
                        {
//Logger::trace("Booking::Createquote===START53==");
                            $placeObj = json_decode($placeObj);
//Logger::trace("Booking::Createquote===START54==". json_encode($placeObj));
                            if (json_last_error() !== JSON_ERROR_NONE)
                            {
                                throw new Exception("Invalid data", ReturnSet::ERROR_INVALID_DATA);
                            }
                            $jsonMapper                   = new JsonMapper();
                            $jsonMapper->bStrictNullTypes = false;
                            /** @var Stub\common\Place $obj */
                            $obj                          = $jsonMapper->map($placeObj, new Stub\common\Place());
                        }
//Logger::trace("Booking::Createquote===START55==". json_encode($obj));
                        $placeArr = Cities::getByPlace($obj);

//Logger::trace("Booking::Createquote===START6==". $placeArr->cty_id);
                        if ($btmodel->bkg_transfer_type == 1)
                        {
//Logger::trace("Booking::Createquote===START61==". $placeArr->cty_id);
                            $btmodel->brt_from_city_id = $bkrut['airport'];
                            $btmodel->brt_to_city_id   = $placeArr->cty_id;
                        }
                        else
                        {
//Logger::trace("Booking::Createquote===START62==". $placeArr->cty_id);
                            $btmodel->brt_to_city_id   = $bkrut['airport'];
                            $btmodel->brt_from_city_id = $placeArr->cty_id;
                        }
                        $toRemove = ['airport', 'place'];
                        foreach ($toRemove as $key)
                        {
                            unset($arrRt[$key]);
                        }
                    }

//Logger::trace("Booking::Createquote===START7==". json_encode($arrRt));
                    $brtModels = [];
                    $btmodel->setScenario('agentquote');
                    foreach ($arrRt as $route)
                    {
                        $rtModel             = new BookingRoute();
                        $rtModel->attributes = $route;
                        if (in_array($btmodel->bkg_booking_type, [9, 10, 11]))
                        {
                            $rtModel->brt_to_city_id = $rtModel->brt_from_city_id;
                        }
                        if ($btmodel->bkg_booking_type == 4)
                        {
                            $rtModel->brt_to_city_id   = $btmodel->brt_to_city_id;
                            $rtModel->brt_from_city_id = $btmodel->brt_from_city_id;

                            if ($btmodel->bkg_transfer_type == 1)
                            {
                                $rtModel->brt_to_location     = substr(trim($obj->address), 0, 500);
                                $rtModel->brt_to_latitude     = $obj->coordinates->latitude;
                                $rtModel->brt_to_longitude    = $obj->coordinates->longitude;
                                $rtModel->brt_from_is_airport = 1;
                            }
                            else
                            {
                                $rtModel->brt_from_location  = substr(trim($obj->address), 0, 500);
                                $rtModel->brt_from_latitude  = $obj->coordinates->latitude;
                                $rtModel->brt_from_longitude = $obj->coordinates->longitude;
                                $rtModel->brt_to_is_airport  = 1;
                            }
                            
                            $routeDuration              = Route::model()->getRouteDurationbyCities($rtModel->brt_from_city_id, $rtModel->brt_to_city_id);
                            $rtModel->brt_trip_duration = $routeDuration;
                            $distance                   = Route::model()->getRouteDistancebyCities($rtModel->brt_from_city_id, $rtModel->brt_to_city_id);
                            $rtModel->brt_trip_distance = $distance;
                        }
//Logger::trace("Booking::Createquote===START8==". json_encode($rtModel));
                        //	Logger::error(new Exception("createQuote address obj create=============: " . json_encode($obj)));

                        $pickupDate1                  = DateTimeFormat::DatePickerToDate($rtModel->brt_pickup_date_date);
                        $times1                       = DateTime::createFromFormat('h:i A', $rtModel->brt_pickup_date_time)->format('H:i:00');
                        $rtModel->brt_pickup_datetime = $pickupDate1 . " " . $times1;

                        $brtModels[] = $rtModel;
                        if ($i == 0)
                        {
                            $btmodel->bkg_from_city_id     = $rtModel->brt_from_city_id;
                            $btmodel->bkg_pickup_date_date = $rtModel->brt_pickup_date_date;
                            $btmodel->bkg_pickup_date_time = $rtModel->brt_pickup_date_time;
                        }

                        $rtModel->validate();

                        if ($rtModel->hasErrors())
                        {
//Logger::trace("Booking::Createquote===START9==". json_encode($rtModel->hasErrors()));
                            $errors = $rtModel->getErrors();
                            //	Logger::error(new Exception("createQuote rtModel validation =============: " . json_encode($rtModel)));
                            foreach ($errors as $attribute => $error)
                            {
                                foreach ($error as $err)
                                {
                                    $btmodel->addError("bkg_id", $err);
                                }
                            }
                        }
                        if (!$btmodel->hasErrors())
                        {
                            //	Logger::error(new Exception("createQuote btmodel validation =============: " . json_encode($btmodel)));
                            if ($btmodel->bkg_booking_type == 2)
                            {
                                $rtModelRet     = new BookingRoute();
                                $returnDate1    = DateTimeFormat::DatePickerToDate($route['brt_return_date_date']);
                                $returntimes1   = DateTime::createFromFormat('h:i A', $route['brt_return_date_time'])->format('H:i:00');
                                $returnDateTime = ($route['brt_return_date_date'] == '') ? '' : $returnDate1 . " " . $returntimes1;

                                if ($returnDateTime == '')
                                {
                                    $btmodel->addError('bkg_id', 'Trip End information is needed.');
                                }
                                $rtModelRet->brt_return_datetime = $returnDateTime;
                                $btmodel->bkg_return_date        = $returnDateTime;
                                $retPickDate                     = Route::model()->getRoundtripEstimatedPickupbyReturnDateTime($route['brt_from_city_id'], $route['brt_to_city_id'], $returnDateTime);

                                $rtModelRet->brt_from_city_id     = $route['brt_to_city_id'];
                                $rtModelRet->brt_to_city_id       = $route['brt_from_city_id'];
                                $rtModelRet->brt_pickup_date_date = DateTimeFormat:: DateTimeToDatePicker($retPickDate);
                                $rtModelRet->brt_pickup_date_time = DateTimeFormat:: DateTimeToTimePicker($retPickDate);
                                $rtModelRet->brt_pickup_datetime  = $retPickDate;
                                $rtModelRet->brt_return_date_date = $route['brt_return_date_date'];
                                $rtModelRet->brt_return_date_time = $route['brt_return_date_time'];
                                $brtModels[]                      = $rtModelRet;
                                $arrRt[]                          = [
                                    'brt_from_city_id'     => $route['brt_to_city_id'],
                                    'brt_to_city_id'       => $route['brt_from_city_id'],
                                    'brt_pickup_date_date' => DateTimeFormat:: DateTimeToDatePicker($retPickDate),
                                    'brt_pickup_date_time' => DateTimeFormat:: DateTimeToTimePicker($retPickDate),
                                    'brt_return_date_date' => $route['brt_return_date_date'],
                                    'brt_return_date_time' => $route['brt_return_date_time'],
                                ];
                                $rtModelRet->validate();
//Logger::trace("Booking::Createquote===START10==". json_encode($rtModelRet->errors()));
                                if ($rtModelRet->hasErrors())
                                {
                                    $errors = $rtModelRet->getErrors();
                                    foreach ($errors as $attribute => $error)
                                    {
                                        foreach ($error as $err)
                                        {
                                            $btmodel->addError("bkg_id", $err);
                                        }
                                    }
                                }
                            }
                            $i++;
                        }
                        else
                        {
                            
                        }
                    }
                    //	Logger::error(new Exception("createQuote before getAgentId "));
                    if (Yii::app()->user->getAgentId() != '')
                    {
                        if ($agentModel->agt_city == 30706)
                        {
                            $cgst = Yii::app()->params['cgst'];
                            $sgst = Yii::app()->params['sgst'];
                            $igst = 0;
                        }
                        else
                        {
                            $igst = Yii::app()->params['igst'];
                            $cgst = 0;
                            $sgst = 0;
                        }
                    }
                    else
                    {
                        if ($btmodel->bkg_from_city_id == 30706)
                        {
                            $cgst = Yii::app()->params['cgst'];
                            $sgst = Yii::app()->params['sgst'];
                            $igst = 0;
                        }
                        else
                        {
                            $igst = Yii::app()->params['igst'];
                            $cgst = 0;
                            $sgst = 0;
                        }
                    }
                    //Logger::error(new Exception("createQuote before 111111 "));
                    $btmodel->bookingRoutes = $brtModels;
                    if ($btmodel->bkg_booking_type != 2 && $btmodel->bkg_booking_type != 1)
                    {
                        $btmodel->validateRouteTime('bkg_id');
                    }
                    if (!$btmodel->hasErrors())
                    {
                        //Logger::error(new Exception("createQuote before 22222 "));
                        $btmodel->bookingRoutes = $brtModels;
                        if ($btmodel->bkg_booking_type == 2)
                        {

                            $btmodel->bkg_to_city_id = $rtModelRet->brt_to_city_id;
                        }
                        else
                        {
                            $btmodel->bkg_to_city_id = $rtModel->brt_to_city_id;
                        }
                        //   $models = $brtModels + [$btmodel];
                        $result = CActiveForm::validate($btmodel, null, false);
                        if ($result == '[]')
                        {
                            //Logger::error(new Exception("createQuote before 44 " . json_encode($result)));
                            //$masterVehicles = VehicleTypes::model()->getMasterCarType();
                            $returnType     = 'list';
                            $masterVehicles = SvcClassVhcCat::model()->getVctSvcList($returnType);

                            $partnerId              = Yii::app()->user->getAgentId();
                            $quote                  = new Quote();
                            $quote->routes          = $brtModels;
                            $quote->tripType        = $btmodel->bkg_booking_type;
                            $quote->partnerId       = $partnerId;
                            $quote->quoteDate       = $btmodel->bkg_create_date;
                            $quote->pickupDate      = $btmodel->bkg_pickup_date;
                            $quote->returnDate      = $btmodel->bkg_return_date;
                            $quote->sourceQuotation = Quote::Platform_Partner_Spot;
                            if (!$brtModels[0]->checkQuoteSession())
                            {
                                Quote::$updateCounter = true;
                                $brtModels[0]->setQuoteSession();
                            }
                            $quote->setCabTypeArr();
                            $cabs                  = [1, 2, 3, 5, 6, 14, 15, 16, 72, 73, 74, 75];
                            //$cabs = [3];
                            
                            $quotData              = $quote->getQuote($cabs, $priceSurge            = true, $includeNightAllowance = true, $checkBestRate         = false, $isAllowed             = true);
                            $routeDistance         = $quote->routeDistance;
                            $routeDuration         = $quote->routeDuration;

//			$arrQuot	 = Quotation::model()->getQuote($brtModels, $bkgType);

                            $routeData = [];
                            $cityArr   = [];
                            foreach ($brtModels as $bRoute)
                            {
                                $routeData[] = array_filter($bRoute->attributes);
                                $cityArr[]   = $bRoute->brt_from_city_id;
                                $cityArr[]   = $bRoute->brt_to_city_id;
                            }
                            $citiesInRoutes = array_unique($cityArr);

                            //$quotData = $arrQuot['routeData'];

                            if ($btmodel->bkg_booking_type == 2 && $btmodel->bkg_return_date_date == '')
                            {
                                $btmodel->bkg_return_date_date = DateTimeFormat::DateTimeToDatePicker($routeDuration->dropTime);
                                $btmodel->bkg_return_date_time = date('H:i:00', strtotime($routeDuration->dropTime));
                                $btmodel->bkg_return_date      = $routeDuration->dropTime;
                                $btmodel->bkg_return_time      = date('H:i:00', strtotime($routeDuration->dropTime));
                            }

                            if (Yii::app()->user->getAgentId() > 0)
                            {
                                $agtType = Agents::model()->findByPk(Yii::app()->user->getAgentId())->agt_type;
                            }

//                        foreach ($quotData as $k => $v)
//                        {
//                            $routeRates = $v->routeRates;
//
//
//                            if ($agtType == 0 || $agtType == 1)
//                            {
//                                $arrQuote    = Agents::model()->getBaseDiscFare($arrQuot[$k], $agtType, Yii::app()->user->getAgentId());
//                                $arrQuot[$k] = $arrQuote;
//                            }
//
//                            if (!in_array($k, array_keys($masterVehicles)))
//                            {
//                                unset($arrQuot[$k]);
//                            }
//                            else
//                            {
//                                $tempCab[$k] = $v['total_amt'];
//                            }
//
//                        }
                            //	Logger::error(new Exception("createQuote before 55 get quotData"));
                            foreach ($quotData as $k => $v)
                            {
                                $routeRates           = $v->routeRates;
                                $routeRates->discFare = '';
                                if ($agtType == 0 || $agtType == 1)
                                {
                                    $arrQuote      = Agents::model()->getBaseDiscFare($routeRates, $agtType, Yii::app()->user->getAgentId());
                                    $v->routeRates = $arrQuote;
                                }

                                if (!in_array($k, array_keys($masterVehicles)))
                                {
                                    unset($quotData[$k]);
                                }
                                else
                                {
                                    $tempCab[$k] = $routeRates->totalAmount;
                                }
                            }
//Logger::trace("Booking::Createquote===START11==");
                            $agtType = Agents::model()->findByPk(Yii::app()->user->getAgentId())->agt_type;
                            Logger::error(new Exception("createQuote before 6666 " . $agtType));
                            //array_multisort($tempCab, SORT_ASC, $arrQuot);

                            $btmodel->bkg_trip_distance = $routeDistance->quotedDistance;
                            $btmodel->bkg_trip_duration = $routeDuration->tripDuration;
                            $preRouteArr                = [];
                            $preData                    = array_filter($btmodel->attributes);
                            $edata                      = CJSON::encode($preData);
                            $btmodel->preData           = $edata;
                            foreach ($brtModels as $brtModel)
                            {
                                $preRouteArr[] = array_filter($brtModel->attributes);
                            }

                            $rutdata             = CJSON::encode($preRouteArr);
                            $btmodel->preRutData = $rutdata;
                            if (Yii::app()->user->getAgentId() > 0)
                            {
                                $btmodel->bkg_agent_id = Yii::app()->user->getAgentId();
                                $agentType             = Agents::model()->findByPk($btmodel->bkg_agent_id)->agt_type;
                            }
                            //	Logger::error(new Exception("createQuote before agtrtview 77renderPartial "));
                            //	Logger::trace("Booking::Createquote===77renderPartial==");
                            $this->renderPartial('agtrtview', ['model'          => $btmodel,
                                'quotData'       => $quote,
                                'routeRate'      => $routeRates,
                                'discCond'       => $discCond,
                                'citiesInRoutes' => $citiesInRoutes,
                                'cabratedata'    => $quotData,
                                'agtType'        => $agentType,
                                'cgst'           => $cgst,
                                'sgst'           => $sgst,
                                'igst'           => $igst]);
                            Yii::app()->end();
                        }
                        if (!$success)
                        {

                            $data = ["errors" => CJSON::decode($result)];
                        }
                    }
                    else
                    {

                        $data = ["errors" => $btmodel->getErrors()];
                    }
                    //  $data = ["errors" => $errors];

                    $return = ['success' => $success] + $data;
                    echo CJSON::encode($return);
                    Yii::app()->end();
                }
            }
            //Logger::error(new Exception("createQuote before agtrtview 88 " . $model->bktyp));
            $outputJs = Yii::app()->request->isAjaxRequest;
            $method   = "render" . ($outputJs ? "Partial" : "");
            $this->$method('createquote', array('model' => $model, 'btyp' => $model->bktyp, 'bdata' => $t1), false, $outputJs);
        }
        catch (Exception $e)
        {
            //Logger::trace("Booking::Createquote===last==" . json_encode($e));
            Logger::error(new Exception("createQuote catch exception" . json_encode($e)));
        }
    }

    public function actionAgtroute()
    {
        $bktype                     = Yii::app()->request->getParam('bktype');
        $btmodel                    = new BookingTemp('new');
        $btmodel->bkg_transfer_type = 0;
        $btmodel->bkg_booking_type  = $bktype;
        $btmodel->bookingRoutes     = [];
        $this->renderPartial('agtroute', array('model' => $btmodel), false, true);
    }

    public function actionCabagentratedetail()
    {
        $model = new BookingTemp('new');
        if (isset($_REQUEST['BookingTemp']))
        {

            $model->scenario = 'new';
            $reqData         = Yii::app()->request->getParam('BookingTemp');

            $model->bkg_vehicle_type_id   = $reqData['bkg_vehicle_type_id'];
            $model->bkg_rate_per_km_extra = $reqData['bkg_rate_per_km_extra'];
            $model->bkg_rate_per_km       = (int) $reqData['bkg_rate_per_km'];

            $cdata                 = [];
            $cdata                 = CJSON::decode($reqData['preData'], true);
            $routedata             = [];
            $routedata             = CJSON::decode($reqData['preRutData'], true);
            $model->bkg_route_data = CJSON::encode($routedata);

            $model->setRoutes($routedata);

            unset($reqData ['preData'], $reqData['vehicle_type_id'], $cdata['type']);

            $data              = $cdata + $reqData;
            $preJSOnData       = CJSON::encode($data);
            $model->attributes = $data;

            $model->bkg_pickup_time = $cdata['bkg_pickup_time'];

            $model->preData                  = $preJSOnData;
            $model->preRutData               = $reqData['preRutData'];
            $model->bkg_user_ip              = \Filter::getUserIP();
            $cityinfo                        = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
            $model->bkg_user_city            = $cityinfo['city'];
            $model->bkg_user_country         = $cityinfo['country'];
            $model->bkg_user_device          = UserLog::model()->getDevice();
            $model->bkg_user_last_updated_on = new CDbExpression('NOW()');
            $tmodel                          = Terms::model()->getText(1);
            $model->bkg_tnc_id               = $tmodel->tnc_id;
            $model->bkg_tnc_time             = new CDbExpression('NOW()');
            $model->bkg_booking_id           = 'temp';
            $model->setScenario('new');

            $result = CActiveForm::validate($model);
            if ($result == '[]')
            {
                if (!$model->save())
                {
                    throw new Exception("Failed to create booking", 101);
                }

                $model->save();
                $desc     = "Quote generated by agent.";
                $userInfo = UserInfo::getInstance();

                $eventid = BookingLog::BOOKING_CREATED;
                LeadLog::model()->createLog($model->bkg_id, $desc, $userInfo, '', '', $eventid);
                //   $transaction->commit();



                $GLOBALS["bkg_id"] = $model->bkg_id;
                $model->hash       = Yii::app()->shortHash->hash($model->bkg_id);
                $GLOBALS["hash"]   = Yii::app()->shortHash->hash($model->bkg_id);
                $this->forward("booking/agtconview", true);
            }
            else
            {
                $return = ['success' => false, 'errors' => CJSON::decode($result)];
            }
            if (Yii::app()->request->isAjaxRequest)
            {
                echo CJSON::encode($return);
                Yii::app()->end();
            }
        }
    }

    public function actionAgtconview()
    {
        $this->pageTitle = "";
        $btmodel         = new BookingTemp('new');
        $view            = Yii::app()->request->getParam('view', 'agtconview');
        $method          = 'renderPartial';

        if (isset($_POST['step']) && $_POST['step'] == 'agtrtv')
        {
            if (isset($GLOBALS['bkg_id']))
            {
                $bkgid         = $GLOBALS['bkg_id'];
                $hash          = $GLOBALS['hash'];
                $btmodel->hash = $hash;
            }
            $method         = 'render';
            /* @var $btmodel Booking */
            $booking        = Yii::app()->request->getParam("BookingTemp");
            $preBookingData = $booking;

            /*
             * array(5) (
              [preData] => (string) {"bkg_country_code":"91","bkg_alt_country_code":"91","bkg_is_approved":2,"bkg_status":1,"bkg_active":1,"bkg_booking_type":"1","bkg_from_city_id":"31893","bkg_to_city_id":"30693","bkg_pickup_time":"06:00:00","bkg_pickup_date":"2017-05-15 06:00:00","bkg_trip_distance":450,"bkg_trip_duration":600}
              [preRutData] => (string) [{"brt_status":1,"brt_active":1,"brt_from_city_id":"31893","brt_to_city_id":"30693","brt_pickup_datetime":"2017-05-15 06:00:00","brt_trip_distance":450,"brt_trip_duration":600}]
              [bkg_booking_type] => (string) 1
              [bkg_vehicle_type_id] => (string) 30
              [bkg_rate_per_km_extra] => (string) 25
              )
             */

            if ($booking != null && !Yii::app()->request->isAjaxRequest)
            {
                //$bkgid = $booking['bkg_id'];
                //$hash = Yii::app()->request->getParam('hash');
                $btmodel->hash = $hash;
                if ($bkgid != Yii::app()->shortHash->unHash($hash))
                {
                    throw new CHttpException(400, 'Invalid data');
                }
            }
            if ($bkgid)
            {
                $btmodel = BookingTemp::model()->findByPk($bkgid);
                if (!$btmodel)
                {
                    throw new CHttpException(400, 'Invalid data');
                }

                if (!Yii::app()->user->isGuest)
                {
                    $userId               = Yii::app()->user->getId();
                    $userModel            = Users::model()->findByPk($userId);
                    $btmodel->bkg_user_id = $userModel->user_id;
                    if ($userModel->usr_mobile != '' && $btmodel->bkg_contact_no == '')
                    {
                        //  $btmodel->bkg_contact_no = $userModel->usr_mobile;
                        //  $btmodel->bkg_country_code = $userModel->usr_country_code;
                    } if ($userModel->usr_email != '' && $btmodel->bkg_user_email == '')
                    {
                        //  $btmodel->bkg_user_email = $userModel->usr_email;
                    }
                    //  $btmodel->bkg_user_name = $userModel->usr_name;
                    //  $btmodel->bkg_user_name = $userModel->usr_name;
                    //  $btmodel->bkg_user_lname = $userModel->usr_lname;
//$model->full_name = $model->bkg_user_name . ' ' . $model->bkg_user_lname;
                }
                $route_data = CJSON::decode($btmodel->bkg_route_data, true);
                if ($btmodel->bkg_booking_type == 4)
                {
                    if ($btmodel->bkg_transfer_type == 1)
                    {
                        $airportAddress                     = $btmodel->bkgFromCity->cty_garage_address;
                        $btmodel->bkg_pickup_address        = $airportAddress;
                        $route_data[0]['brt_from_location'] = $airportAddress;
                    }
                    if ($btmodel->bkg_transfer_type == 2)
                    {
                        $airportAddress                   = $btmodel->bkgToCity->cty_garage_address;
                        $btmodel->bkg_drop_address        = $airportAddress;
                        $route_data[0]['brt_to_location'] = $airportAddress;
                    }
                }

                foreach ($route_data as $k => $v)
                {
                    $bookingRoute             = new BookingRoute();
                    $bookingRoute->attributes = $v;
                    $bookingRoutes[]          = $bookingRoute;
                }
                $btmodel->bookingRoutes = $bookingRoutes;
            }
        }


        $agtId                          = Yii::app()->user->getAgentId();
        $agtModel                       = Agents::model()->findByPk($agtId);
        //  $btmodel->bkg_trvl_sendupdate = ($agtModel->agt_trvl_sendupdate!='')?$agtModel->agt_trvl_sendupdate:1;
        $btmodel->bkg_copybooking_name  = ($agtModel->agt_copybooking_name != '') ? $agtModel->agt_copybooking_name : $agtModel->agt_fname . " " . $agtModel->agt_lname;
        $btmodel->bkg_copybooking_email = ($agtModel->agt_copybooking_email != '') ? $agtModel->agt_copybooking_email : $agtModel->agt_email;
        $btmodel->bkg_copybooking_phone = ($agtModel->agt_copybooking_phone != '') ? $agtModel->agt_copybooking_phone : $agtModel->agt_phone;
        $btmodel->bkg_agent_id          = $agtId;

        //  $btmodel->bkg_copybooking_ismail = $agtModel->agt_copybooking_ismail;
        //   $btmodel->bkg_copybooking_issms = $agtModel->agt_copybooking_issms;
        $btmodel->bkg_trvl_email = $btmodel->bkg_user_email;
        $btmodel->bkg_trvl_phone = $btmodel->bkg_contact_no;
        $btmodel->bkg_send_email = $agtModel->agt_trvl_isemail;
        $btmodel->bkg_send_sms   = $agtModel->agt_trvl_issms;
        $btmodel->bkg_send_app   = $agtModel->agt_trvl_isapp;
        $route_data              = CJSON::decode($btmodel->bkg_route_data, true);
        $bookingRoutes           = [];
        if ($btmodel->bkg_booking_type == 4)
        {
            if ($btmodel->bkg_transfer_type == 1)
            {
                $airportAddress                     = $btmodel->bkgFromCity->cty_garage_address;
                $btmodel->bkg_pickup_address        = $airportAddress;
                $route_data[0]['brt_from_location'] = $airportAddress;
            }
            if ($btmodel->bkg_transfer_type == 2)
            {
                $airportAddress                   = $btmodel->bkgToCity->cty_garage_address;
                $btmodel->bkg_drop_address        = $airportAddress;
                $route_data[0]['brt_to_location'] = $airportAddress;
            }
        }

        $cityArr = [];
        foreach ($route_data as $rtk => $rtv)
        {
            if ($rtk == 0)
            {
                $cityArr[$rtk] = $rtv['brt_from_city_id'];
            }
            $cityArr[$rtk + 1] = $rtv['brt_to_city_id'];
        }
        if ($btmodel->bkg_booking_type != 4)
        {
            $getAirportCities = BookingSub::model()->checkAirport($cityArr);
            if (sizeof($getAirportCities) > 0)
            {
                foreach ($getAirportCities as $k => $v)
                {
                    $airportAddress = $v['name'];
                    if ($k == 0)
                    {
                        $btmodel->bkg_pickup_address        = $airportAddress;
                        $route_data[0]['brt_from_location'] = $airportAddress;
                    }
                    else
                    {
                        if (sizeof($route_data) == $k)
                        {
                            $btmodel->bkg_drop_address = $airportAddress;
                        }
                        $route_data[$k - 1]['brt_to_location'] = $airportAddress;
                    }
                }
            }
        }

        foreach ($route_data as $k => $v)
        {
            $bookingRoute             = new BookingRoute();
            $bookingRoute->attributes = $v;
            if ($btmodel->bkg_booking_type != 4)
            {
                if ($k == 0 && $bookingRoute->brtFromCity->cty_is_airport == 1)
                {
                    $bookingRoute->brt_from_location        = $bookingRoute->brtFromCity->cty_garage_address;
                    $bookingRoute->brt_from_latitude        = $bookingRoute->brtFromCity->cty_lat;
                    $bookingRoute->brt_from_longitude       = $bookingRoute->brtFromCity->cty_long;
                    $bookingRoute->brt_from_city_is_airport = 1;
                }
                if ($bookingRoute->brtToCity->cty_is_airport == 1)
                {
                    $bookingRoute->brt_to_location        = $bookingRoute->brtToCity->cty_garage_address;
                    $bookingRoute->brt_to_latitude        = $bookingRoute->brtToCity->cty_lat;
                    $bookingRoute->brt_to_longitude       = $bookingRoute->brtToCity->cty_long;
                    $bookingRoute->brt_to_city_is_airport = 1;
                }
            }
            $bookingRoutes[] = $bookingRoute;
        }

        $btmodel->bookingRoutes = $bookingRoutes;
        //$carType				 = VehicleTypes::model()->getVehicleTypeById($btmodel->bkg_vehicle_type_id);
        $carType                = $btmodel->bkgSvcClassVhcCat->scv_id;

        $quote                          = new Quote();
        $quote->routes                  = $bookingRoutes;
        $quote->tripType                = $btmodel->bkg_booking_type;
        $quote->partnerId               = Yii::app()->user->getAgentId();
        $quote->quoteDate               = $btmodel->bkg_create_date;
        $quote->pickupDate              = $btmodel->bkg_pickup_date;
        $quote->returnDate              = $btmodel->bkg_return_date;
        $quote->sourceQuotation         = Quote::Platform_Partner_Spot;
        $quote->isDynamicSurgeAvialable = DynamicPriceAvailability::Available;
        $quote->setCabTypeArr();
        $qt                             = $quote->getQuote($carType, $priceSurge                     = true, $includeNightAllowance          = true, $checkBestRate                  = false, $isAllowed                      = true);

        $quoteData             = $qt[$carType];
        $routeRates            = $quoteData->routeRates;
        /*
         * @var $routeRates RouteRates                      */
        $btmodel->bkg_agent_id = Yii::app()->user->getAgentId();
        $agtModel              = Agents::model()->findByPk($btmodel->bkg_agent_id);

        $bkgModel    = new Booking();
        $bkgInvModel = new BookingInvoice();
        $bkgTrack    = new BookingTrack();
        $bkgAddInfo  = new BookingAddInfo();
        $bkgPf       = new BookingPriceFactor();

        if ($cabData['error'] == 0)
        {
            $arrQuote      = Agents::model()->getBaseDiscFare($quoteData->routeRates, $agtModel->agt_type, $btmodel->bkg_agent_id);
//            $cabData                               = $arrQuote;
            $routeRates    = $arrQuote;
            $routeDistance = $quoteData->routeDistance;
            $routeDuration = $quoteData->routeDuration;
            $amount        = $routeRates->baseAmount;

            $bkgModel->bkg_agent_id                   = $agtModel->agt_id;
            $bkgInvModel->bkg_gozo_base_amount        = $amount;
            $bkgInvModel->bkg_base_amount             = $routeRates->baseAmount;
            $bkgModel->bkg_trip_distance              = $routeDistance->tripDistance;
            //$routeData['minimumChargeableDistance'];
            $bkgModel->bkg_trip_duration              = $routeDuration->totalMinutes;
            //$routeData['days']['totalMin'];
            $bkgInvModel->bkg_driver_allowance_amount = $routeRates->driverAllowance;
            $bkgInvModel->bkg_chargeable_distance     = $routeDistance->quotedDistance;
            $bkgTrack->bkg_garage_time                = $routeDuration->totalMinutes + $routeDuration->garageTimeEnd + $routeDuration->garageTimeStart;
            $bkgInvModel->bkg_is_toll_tax_included    = $routeRates->isTollIncluded;
            $bkgInvModel->bkg_is_state_tax_included   = $routeRates->isStateTaxIncluded;
            $bkgInvModel->bkg_toll_tax                = $routeRates->tollTaxAmount;
            $bkgInvModel->bkg_state_tax               = $routeRates->stateTax;
            $bkgInvModel->bkg_airport_entry_fee       = $routeRates->airportEntryFee | 0;
            $bkgInvModel->bkg_is_airport_fee_included = $routeRates->isAirportEntryFeeIncluded | 0;
            $bkgInvModel->bkg_vendor_amount           = round($routeRates->vendorAmount);
            $bkgInvModel->bkg_quoted_vendor_amount    = round($routeRates->vendorAmount);

            if ($bkgAddInfo->bkg_spl_req_carrier == 1)
            {
                $bkgInvModel->bkg_additional_charge        = 150;
                $bkgInvModel->bkg_additional_charge_remark = 'Carrier Requested for Rs.150';
            }
            else
            {
                $bkgInvModel->bkg_additional_charge        = 0;
                $bkgInvModel->bkg_additional_charge_remark = '';
            }

            $bkgInvModel->bkg_advance_amount = 0;

            if ($amount > 0)
            {
                $bkgid;
                $bkgModel->bkg_agent_id     = $btmodel->bkg_agent_id;
                $bkgModel->bkg_booking_type = $btmodel->bkg_booking_type;
                $bkgModel->bkg_pickup_date  = $btmodel->bkg_pickup_date;
                $arr = ['bkg_agent_id'=>$btmodel->bkg_agent_id ,'bkg_booking_type'=>$btmodel->bkg_booking_type ,'bkg_pickup_date'=>$btmodel->bkg_pickup_date];
                
                $bkgInvModel->calculateConvenienceFee(0,$arr);
                $bkgInvModel->calculateTotal($arr);

                //$bkgModel->populateAmount();//
                $bkgInvModel->calculateVendorAmount();
                $bkgInvModel->calculateAgentMarkup($bkgModel->bkg_agent_id);
                //$bkgModel->changeAgentMarkup();//
            }
        }
        $arrAmount = array_filter($bkgModel->attributes + $bkgInvModel->attributes + $bkgTrack->attributes + $bkgAddInfo->attributes);
        unset($arrAmount['bkg_trip_type']);
        unset($arrAmount['bkg_extra_toll_tax']);
        unset($arrAmount['bkg_airport_entry_fee']);
        unset($arrAmount['bkg_is_airport_fee_included']);
        foreach ($arrAmount as $k => $v)
        {

            if ($btmodel->hasAttribute($k))
            {
                $btmodel->$k = $v;
            }
        }
        if ($btmodel->bkg_agent_id != '')
        {
            if ($agtModel->agt_city == 30706)
            {
                $btmodel->bkg_cgst = Yii::app()->params['cgst'];
                $btmodel->bkg_sgst = Yii::app()->params['sgst'];
                $btmodel->bkg_igst = 0;
            }
            else
            {
                $btmodel->bkg_igst = Yii::app()->params['igst'];
                $btmodel->bkg_cgst = 0;
                $btmodel->bkg_sgst = 0;
            }
        }
        else
        {
            if ($btmodel->bkg_from_city_id == 30706)
            {
                $btmodel->bkg_cgst = Yii::app()->params['cgst'];
                $btmodel->bkg_sgst = Yii::app()->params['sgst'];
                $btmodel->bkg_igst = 0;
            }
            else
            {
                $btmodel->bkg_igst = Yii::app()->params['igst'];
                $btmodel->bkg_cgst = 0;
                $btmodel->bkg_sgst = 0;
            }
        }
        $btmodel->bkg_advance_amount = 0;
        $btmodel->agentBkgAmountPay  = 1;
        $btmodel->agentCreditAmount  = $btmodel->bkg_total_amount;

        $btmodel->hash    = $GLOBALS['hash'];
        $btmodel->preData = $preBookingData;

        $this->render('agtconview', array('model' => $btmodel, 'invModel' => $bkgInvModel));
    }

    public function actionAgtfinalbook()
    {
        //$model = new BookingTemp('new');
        if (isset($_POST['step']) && $_POST['step'] == 'cnview')
        {

            /* @var $model Booking */
            $bookingData      = [];
            $bookingData      = Yii::app()->request->getParam("BookingTemp");
            $bookingRouteData = Yii::app()->request->getParam("BookingRoute");

            if ($bookingData['bkg_id'] > 0)
            {
                $bkgid = $bookingData['bkg_id'];
                $hash  = $bookingData['hash'];
                if ($bkgid != Yii::app()->shortHash->unHash($hash))
                {
                    throw new CHttpException(400, 'Invalid data');
                }
                $btmodel = BookingTemp::model()->findByPk($bkgid);
                if (!$btmodel)
                {
                    throw new CHttpException(400, 'Invalid data');
                }

                $brtArr = [];
                foreach ($bookingRouteData as $brtVal)
                {
                    $brtArr[] = $brtVal;
                }
                $cntBrt = sizeof($bookingRouteData);

                $fromAdditionalAddress = $toAdditionalAddress   = '';
                if ($btmodel->bkg_booking_type == 4)
                {
                    if ($btmodel->bkg_transfer_type == 1)
                    {
                        $toAdditionalAddress = ltrim(trim($brtArr[$cntBrt - 1]['brt_additional_to_address']) . ', ', ', ');
                    }
                    else
                    {
                        $fromAdditionalAddress = ltrim(trim($brtArr[0]['brt_additional_from_address']) . ', ', ', ');
                    }
                }



//				$route_data = CJSON::decode($btmodel->bkg_route_data, true);
//				$btmodel->setRoutes($route_data);
//				$cntRoutes	 = count($btmodel->bookingRoutes);
//				$routes		 = array_values($routes);
//				for ($i = 0; $i < $cntRoutes; $i++)
//				{
//					$route					 = $bookingRouteData[$i];
//					$brtRoute				 = $btmodel->bookingRoutes[$i];
//					$brtRoute->attributes	 = $route;
//					$brtRoute->from_place	 = ($i > 0) ? $routes[($i - 1)]['to_place'] : $route['from_place'];
//                    $brtRoute->to_place	 = $bookingRouteData[$cntRoutes]['to_place'];//($i == $cntRoutes) ? $routes[($i)]['to_place'] : $route['to_place'];
//					if ($brtRoute->from_place != "")
//					{
//						$brtRoute->applyPlace($brtRoute->from_place, 1);
//					}
//					if ($brtRoute->to_place != "")
//					{
//						$brtRoute->applyPlace($brtRoute->to_place, 2);
//					}
//				}

                $route_data = json_decode($btmodel->bkg_route_data, true);
                $btmodel->setRoutes($route_data);
                $routes     = $btmodel->bookingRoutes;
                $cntRoutes  = count($btmodel->bookingRoutes);
                foreach ($bookingRouteData as $key => $brtRoute)
                {
                    if (isset($brtRoute["from_place"]) && $brtRoute["from_place"] != '')
                    {
                        $routes[$key]->applyPlace($brtRoute["from_place"], 1);
                    }
                    if (isset($brtRoute["to_place"]) && $brtRoute["to_place"] != '')
                    {
                        if ($key > 0)
                        {
                            $routes[$key]->applyPlace($bookingRouteData[$key - 1]["to_place"], 1);
                        }
                        $routes[$key]->applyPlace($brtRoute["to_place"], 2);
                    }
                }
                $btmodel->setRoutes($routes);
                $btmodel->pickupLat          = $btmodel->bookingRoutes[0]->brt_from_latitude;
                $btmodel->pickupLon          = $btmodel->bookingRoutes[0]->brt_from_longitude;
                $btmodel->dropLat            = $btmodel->bookingRoutes[$cntRoutes - 1]->brt_to_latitude;
                $btmodel->dropLon            = $btmodel->bookingRoutes[$cntRoutes - 1]->brt_to_longitude;
                $btmodel->bkg_pickup_address = $btmodel->bookingRoutes[0]->brt_from_location;
                $btmodel->bkg_drop_address   = $btmodel->bookingRoutes[$cntRoutes - 1]->brt_to_location;
                $bookingRoutes               = $btmodel->bookingRoutes;
                $cntRt                       = sizeof($route_data);
                if ($btmodel->bkg_booking_type == 4 && $bkgid == '')
                {
                    $btmodel->bkg_pickup_address = $fromAdditionalAddress . $brtArr[0]['brt_from_location'];
                    $btmodel->bkg_drop_address   = $toAdditionalAddress . $brtArr[$cntRt]['brt_to_location'];
                }
                $btmodel->scenario = 'cabRate';
                $result            = CActiveForm:: validate($btmodel);
                if ($result == '[]')
                {


                    //$carType	 = VehicleTypes::model()->getVehicleTypeById($btmodel->bkg_vehicle_type_id);
                    $carType                        = $btmodel->bkg_vehicle_type_id; //$btmodel->bkgSvcClassVhcCat->scv_vct_id;
                    $partnerId                      = Yii::app()->user->getAgentId();
                    $quote                          = new Quote();
                    $quote->routes                  = $bookingRoutes;
                    $quote->tripType                = $btmodel->bkg_booking_type;
                    $quote->partnerId               = $partnerId;
                    $quote->quoteDate               = $btmodel->bkg_create_date;
                    $quote->pickupDate              = $btmodel->bkg_pickup_date;
                    $quote->returnDate              = $btmodel->bkg_return_date;
                    $quote->sourceQuotation         = Quote::Platform_Partner_Spot;
                    $quote->isB2Cbooking            = false;
                    $quote->isDynamicSurgeAvialable = DynamicPriceAvailability::Available;
                    $quote->setCabTypeArr();
                    $qt                             = $quote->getQuote($carType);

                    $quoteData = $qt[$carType];

                    $routeRates    = $quoteData->routeRates;
                    $routeDistance = $quoteData->routeDistance;
                    $routeDuration = $quoteData->routeDuration;

                    $bookingRoutes           = $quote->routes;
                    $firstRoute              = $bookingRoutes[0];
                    $lastRoute               = $bookingRoutes[(count($bookingRoutes) - 1)];
                    $model->bkg_from_city_id = $firstRoute['brt_from_city_id'];
                    $model->bkg_to_city_id   = $lastRoute['brt_to_city_id'];

                    if ($btmodel->bkg_booking_type == 2)
                    {
                        $returnDate                    = $routeDuration->toDate;
                        $btmodel->bkg_return_date_date = DateTimeFormat::DateTimeToDatePicker($returnDate);
                        $btmodel->bkg_return_date_time = date('H:i:00', strtotime($returnDate));
                        $btmodel->bkg_return_date      = $returnDate;
                        $btmodel->bkg_return_time      = date('H:i:00', strtotime($returnDate));
                    }
                    $routesData = [];
                    foreach ($bookingRoutes as $bRoute)
                    {
                        $routesData[] = array_filter($bRoute->attributes);
                    }


                    $dataArr = ['bkg_user_name', 'bkg_user_lname', 'bkg_user_email', 'bkg_country_code',
                        'bkg_contact_no', 'bkg_alt_country_code', 'bkg_alternate_contact'];

                    foreach ($dataArr as $v1)
                    {
                        $btmodel->$v1 = $bookingData[$v1];
                    }

                    $btmodel->bkg_route_data = CJSON::encode($routesData);
                    $btmodel->bookingRoutes  = $bookingRoutes;
                    $btmodel->save();
                    $preJSOnData             = CJSON::encode($bookingData);
                    $btmodel->preData        = $preJSOnData;

//                    $cabData = [];
//                    foreach ($qt as $k => $v)
//                    {
//                        if ($k > 0)
//                        {
//                            $cabData = $qt[$k];
//                        }
//                    }
//                    $routeData = $qt['routeData'];
                    $bkgModel        = new Booking();
                    $bkgInvModel     = new BookingInvoice();
                    $bkgTrackModel   = new BookingTrack();
                    $bkgAddInfoModel = new BookingAddInfo();

                    if ($quoteData->success)
                    {
                        if (Yii::app()->user->getAgentId() > 0)
                        {
                            $agtModel              = Agents::model()->findByPk(Yii::app()->user->getAgentId());
                            $routeRates            = Agents::model()->getBaseDiscFare($routeRates, $agtModel->agt_type, Yii::app()->user->getAgentId());
                            $quoteData->routeRates = $routeRates;
                        }
                        if ($bookingData['bkg_is_state_tax_included'] != NULL)
                        {
                            $routeRates->isStateTaxIncluded = 0;
                            $routeRates->stateTax           = 0;
                        }
                        if ($bookingData['bkg_is_toll_tax_included'] != NULL)
                        {
                            $routeRates->isTollIncluded = 0;
                            $routeRates->tollTaxAmount  = 0;
                        }
                        $bkgModel->bkg_agent_id                   = Yii::app()->user->getAgentId();
                        $baseAmount                               = round($routeRates->baseAmount);
                        $bkgInvModel->bkg_gozo_base_amount        = $baseAmount;
                        $bkgInvModel->bkg_base_amount             = $baseAmount;
                        $bkgModel->bkg_trip_distance              = $routeDistance->quotedDistance;
                        $bkgModel->bkg_trip_duration              = $routeDuration->totalMinutes; //$routeData['days']['totalMin'];
                        $bkgInvModel->bkg_driver_allowance_amount = $routeRates->driverAllowance;
                        $bkgInvModel->bkg_chargeable_distance     = $routeDistance->quotedDistance;
                        $bkgTrackModel->bkg_garage_time           = $routeDuration->totalMinutes + $routeDuration->garageTimeEnd + $routeDuration->garageTimeStart;
                        $bkgInvModel->bkg_is_toll_tax_included    = $routeRates->isTollIncluded | 0;
                        $bkgInvModel->bkg_is_state_tax_included   = $routeRates->isStateTaxIncluded | 0;
                        $bkgInvModel->bkg_toll_tax                = $routeRates->tollTaxAmount | 0;
                        $bkgInvModel->bkg_state_tax               = $routeRates->stateTax | 0;
                        $bkgInvModel->bkg_is_airport_fee_included = $routeRates->isAirportEntryFeeIncluded | 0;
                        $bkgInvModel->bkg_airport_entry_fee       = $routeRates->airportEntryFee;
                        $bkgInvModel->bkg_vendor_amount           = round($routeRates->vendorAmount);
                        $bkgInvModel->bkg_quoted_vendor_amount    = round($routeRates->vendorAmount);

                        $bkgInvModel->bkg_surge_differentiate_amount = $routeRates->differentiateSurgeAmount;

                        if ($bkgAddInfoModel->bkg_spl_req_carrier == 1)
                        {
                            $bkgInvModel->bkg_additional_charge        = 150;
                            $bkgInvModel->bkg_additional_charge_remark = 'Carrier Requested for Rs.150';
                        }
                        else
                        {
                            $bkgInvModel->bkg_additional_charge        = 0;
                            $bkgInvModel->bkg_additional_charge_remark = '';
                        }

                        $bkgInvModel->bkg_advance_amount = 0;

                        if ($baseAmount > 0)
                        {
                            
                            
                $bkgModel->bkg_agent_id     = $btmodel->bkg_agent_id;
                $bkgModel->bkg_booking_type = $btmodel->bkg_booking_type;
                $bkgModel->bkg_pickup_date  = $btmodel->bkg_pickup_date;
                $arr = ['bkg_agent_id'=>$btmodel->bkg_agent_id ,'bkg_booking_type'=>$btmodel->bkg_booking_type ,'bkg_pickup_date'=>$btmodel->bkg_pickup_date];
                            
                            
//							$bkgInvModel->calculateConvenienceFee(0);
//							$bkgInvModel->calculateTotal();
//							$bkgInvModel->calculateAgentMarkup($bkgModel->bkg_agent_id);
                            $bkgInvModel->populateAmount(true, false, true, true, $bkgModel->bkg_agent_id,$arr);
//							$bkgInvModel->calculateVendorAmount();
                        }
                    }

                    $arrAmount = array_filter($bkgModel->attributes + $bkgInvModel->attributes + $bkgTrackModel->attributes + $bkgAddInfoModel->attributes);
                    unset($arrAmount['bkg_trip_type']);
                    unset($arrAmount['bkg_extra_toll_tax']);
                    unset($arrAmount['bkg_is_airport_fee_included']);
                    unset($arrAmount['bkg_airport_entry_fee']);
                    foreach ($arrAmount as $k => $v)
                    {
                        if ($btmodel->hasAttribute($k))
                        {
                            $btmodel->$k = $v;
                        }
                    }
                    $btmodel->bkg_advance_amount = 0;

                    $success = !$btmodel->hasErrors();
                }
                else
                {
                    $return = ['success' => false, 'errors' => CJSON::decode($result)];
                    $this->render('agtconview', array('model' => $btmodel, 'invModel' => $bkgInvModel));
                    if (Yii::app()->request->isAjaxRequest)
                    {
                        echo CJSON::encode($return);
                        Yii::app()->end();
                    }
                    Yii::app()->end();
                }
            }
        }
        if ($btmodel->bkg_agent_id != '')
        {
            if ($agtModel->agt_city == 30706)
            {
                $btmodel->bkg_cgst = Yii::app()->params['cgst'];
                $btmodel->bkg_sgst = Yii::app()->params['sgst'];
                $btmodel->bkg_igst = 0;
            }
            else
            {
                $btmodel->bkg_igst = Yii::app()->params['igst'];
                $btmodel->bkg_cgst = 0;
                $btmodel->bkg_sgst = 0;
            }
        }
        else
        {
            if ($btmodel->bkg_from_city_id == 30706)
            {
                $btmodel->bkg_cgst = Yii::app()->params['cgst'];
                $btmodel->bkg_sgst = Yii::app()->params['sgst'];
                $btmodel->bkg_igst = 0;
            }
            else
            {
                $btmodel->bkg_igst = Yii::app()->params['igst'];
                $btmodel->bkg_cgst = 0;
                $btmodel->bkg_sgst = 0;
            }
        }


        $this->renderPartial('agtbooksummary', array('model' => $btmodel, 'invModel' => $bkgInvModel), false, true);
    }

    public function actionValidateagtcustinfo()
    {
        $bookingData      = [];
        $bookingData      = Yii::app()->request->getParam("BookingTemp");
        $bookingRouteData = Yii::app()->request->getParam("BookingRoute");

        if ($bookingData['bkg_id'] > 0)
        {
            $bkgid = $bookingData['bkg_id'];
            $hash  = $bookingData['hash'];
            if ($bkgid != Yii::app()->shortHash->unHash($hash))
            {
                throw new CHttpException(400, 'Invalid data');
            }
            $btmodel = BookingTemp::model()->findByPk($bkgid);
            if (!$btmodel)
            {
                throw new CHttpException(400, 'Invalid data');
            }
            //$route_data = CJSON::decode($btmodel->bkg_route_data, true);
            if ($btmodel->bkg_booking_type == 4)
            {
                $bookingRouteData = json_decode($btmodel->bkg_route_data, true);
                $brtArr           = [];
                foreach ($bookingRouteData as $brtVal)
                {
                    $brtArr[] = $brtVal;
                }
            }
            $cntBrt                = sizeof($bookingRouteData);
            //$btmodel->scenario		 = 'cabRate';
            $fromAdditionalAddress = $toAdditionalAddress   = '';
            $btmodel->latlonSet    = false;
            if ($btmodel->bkg_booking_type == 4)
            {
                if ($btmodel->bkg_transfer_type == 1)
                {
                    $toAdditionalAddress = ltrim(trim($brtArr[0]['brt_to_location']) . ', ', ', ');
                }
                else
                {
                    $fromAdditionalAddress = ltrim(trim($brtArr[0]['brt_from_location']) . ', ', ', ');
                }
            }

            $route_data = json_decode($btmodel->bkg_route_data, true);
            $btmodel->setRoutes($route_data);
            $cntRoutes  = count($btmodel->bookingRoutes);
            $routes     = array_values($routes);

            $routes = $btmodel->bookingRoutes;
            foreach ($bookingRouteData as $key => $brtRoute)
            {
                if (isset($brtRoute["from_place"]) && $brtRoute["from_place"] != '')
                {
                    $routes[$key]->applyPlace($brtRoute["from_place"], 1);
                }
                if (isset($brtRoute["to_place"]) && $brtRoute["to_place"] != '')
                {
                    if ($key > 0)
                    {
                        $routes[$key]->applyPlace($bookingRouteData[$key - 1]["to_place"], 1);
                    }
                    $routes[$key]->applyPlace($brtRoute["to_place"], 2);
                }
            }
            $btmodel->setRoutes($routes);
            $btmodel->bookingRoutes;
            $btmodel->pickupLat          = $btmodel->bookingRoutes[0]->brt_from_latitude;
            $btmodel->pickupLon          = $btmodel->bookingRoutes[0]->brt_from_longitude;
            $btmodel->dropLat            = $btmodel->bookingRoutes[$cntRoutes - 1]->brt_to_latitude;
            $btmodel->dropLon            = $btmodel->bookingRoutes[$cntRoutes - 1]->brt_to_longitude;
            $btmodel->bkg_pickup_address = $btmodel->bookingRoutes[0]->brt_from_location;
            $btmodel->bkg_drop_address   = $btmodel->bookingRoutes[$cntRoutes - 1]->brt_to_location;

            if ($btmodel->bkg_booking_type == 4)
            {
                $btmodel->bkg_pickup_address =$brtArr[0]['brt_from_location']; //$fromAdditionalAddress . 
                $btmodel->bkg_drop_address   = $brtArr[0]['brt_to_location'];//$toAdditionalAddress . 
            }

            $btmodel->scenario = 'cabRate';
            $result            = CActiveForm::validate($btmodel);
            if ($result == '[]')
            {
                $return = ['success' => true];
            }
            else
            {
                $return = ['success' => false, 'errors' => CJSON::decode($result)];
            }
            if (Yii::app()->request->isAjaxRequest)
            {
                echo CJSON::encode($return);
                Yii::app()->end();
            }
        }
    }

    public function actionQuotetobook()
    {
        Logger::trace("Quotetobook Entry");
        $bookingData = Yii::app()->request->getParam("BookingTemp");
        if ($bookingData['bkg_id'] > 0)
        {
             Logger::trace("Quotetobook Entry1 bookingID=========".$bookingData['bkg_id']);
            $bkgid = $bookingData['bkg_id'];
            $hash  = $bookingData['hash'];
            if ($bkgid != Yii::app()->shortHash->unHash($hash))
            {
                 Logger::error("Error1");
                throw new CHttpException(400, 'Invalid data');
            }
            $btmodel = BookingTemp::model()->findByPk($bkgid);
            if (!$btmodel)
            {
                 Logger::error("Error2");
                throw new CHttpException(400, 'Invalid data');
            }
            $bookData = CJSON::decode($bookingData['preData'], true);
            unset($bookData['bkg_id']);
            if ($bookingData['agentNotifyData'] != '' && $bookingData['agentNotifyData'] != null && $bookingData['agentNotifyData'] != 'null')
            {
                $btmodel->agentNotifyData = json_decode($bookingData['agentNotifyData'], true);
            }
            if ($bookingData['bkg_route_data'] != "")
            {
                $btmodel->bkg_route_data = $bookingData['bkg_route_data'];
            }
            $route_data    = CJSON::decode($btmodel->bkg_route_data, true);
            $bookingRoutes = [];
            $cntRut        = count($route_data);
            foreach ($route_data as $k => $v)
            {
                  
                $bookingRoute                    = new BookingRoute();
                $bookingRoute->attributes        = $v;
                $bookingRoute->brt_from_location = $v['brt_from_location'];
                $bookingRoutes[]                 = $bookingRoute;
                if ($k == 0)
                {
                    $btmodel->bkg_pickup_address = $v['brt_from_location'];
                    $btmodel->bkg_pickup_lat     = $bookingRoute->brt_from_latitude;
                    $btmodel->bkg_pickup_long    = $bookingRoute->brt_from_longitude;
                }
                if ($k == ($cntRut - 1))
                {
                    $btmodel->bkg_drop_address = $v['brt_to_location'];
                }
            }
            Logger::trace("Quotetobook Entry2 =========");
            $btmodel->bookingRoutes = $bookingRoutes;
            $carType                = $btmodel->bkg_vehicle_type_id;
//            $bkgType                = $btmodel->bkg_booking_type;
            $partnerId              = Yii::app()->user->getAgentId();
            $btmodel->bkg_agent_id  = $partnerId;
            $quote                  = new Quote();
            $quote->routes          = $bookingRoutes;
            $quote->tripType        = $btmodel->bkg_booking_type;
            $quote->partnerId       = $partnerId;
            $quote->quoteDate       = $btmodel->bkg_create_date;
            $quote->pickupDate      = $btmodel->bkg_pickup_date;
            $quote->returnDate      = $btmodel->bkg_return_date;
            $quote->sourceQuotation = Quote::Platform_Partner_Spot;
            $quote->setCabTypeArr();
            $qt                     = $quote->getQuote($carType);
            Logger::trace("Quote to book car type: " . $carType);
             Logger::trace("Quotetobook Entry3 ========="  . $carType);
            $quoteData              = $qt[$carType];

            $routeRates    = $quoteData->routeRates;
            $routeDistance = $quoteData->routeDistance;
            $routeDuration = $quoteData->routeDuration;

            if ($btmodel->bkg_booking_type == 2)
            {
                $returnDate                    = $routeDuration->toDate;
                $btmodel->bkg_return_date_date = DateTimeFormat::DateTimeToDatePicker($returnDate);
                $btmodel->bkg_return_date_time = date('H:i:00', strtotime($returnDate));
                $btmodel->bkg_return_date      = $returnDate;
                $btmodel->bkg_return_time      = date('H:i:00', strtotime($returnDate));
            }


            //Converting Lead Data to Booking
            $btmodel->agentBkgAmountPay = $bookData['agentBkgAmountPay'];
            $arrResult                  = Booking::model()->convertUserLeadtoBooking($btmodel);
   Logger::trace("Quotetobook Entry4 =========");
            if ($arrResult['success'])
            {
                $model = $arrResult['model'];
                $model->bkg_id;
                foreach ($model->bookingRoutes as $t)
                {
                    $t->save();
                }
                $cabData = [];
//                foreach ($qt as $k => $v)
//                {
//                    if ($k > 0)
//                    {
//                        $cabData = $qt[$k];
//                    }
//                }
//                $routeData = $qt['routeData'];
               Logger::trace("Quotetobook Entry5 =========" .$quoteData->success);
                
                if ($quoteData->success)
                {
                    $agtModel = Agents::model()->findByPk(Yii::app()->user->getAgentId());
                    //$model->bkg_reconfirm_flag                       = ($agtModel->agt_type == 0)?1:0; 
                    if (Yii::app()->user->getCorpCode() != '')
                    {
                        $model->bkg_agent_id = Yii::app()->user->getAgentId();
                        //$model->bkg_reconfirm_flag	 = 1;
                        if ($model->bkg_user_id != '')
                        {
                            $userModel                   = Users::model()->findByPk($model->bkg_user_id);
                            $userModel->usr_corporate_id = $model->bkg_agent_id;
                            $userModel->update();
                        }
                    }
                    else
                    {
                        $model->bkg_agent_id = Yii::app()->user->getAgentId();
                    }
                     Logger::trace("Quotetobook Entry6 AgentID =========" .$model->bkg_agent_id);
                    $agtModel                                     = Agents::model()->findByPk($model->bkg_agent_id);
                    $routeRates                                   = Agents::model()->getBaseDiscFare($routeRates, $agtModel->agt_type, $model->bkg_agent_id);
//                     $quoteData->routeRates  = $routeRates;
                    $baseAmount                                   = round($routeRates->baseAmount);
                    /* @var $model Booking */
                    $model->bkgInvoice->bkg_gozo_base_amount      = $baseAmount;
                    $model->bkgInvoice->bkg_base_amount           = $baseAmount;
                    $model->bkg_trip_distance                     = $routeDistance->quotedDistance;
                    $model->bkgInvoice->bkg_night_pickup_included = $routeRates->isNightPickupIncluded | 0;
                    $model->bkgInvoice->bkg_night_drop_included   = $routeRates->isNightDropIncluded | 0;

                    $model->bkgInvoice->bkg_surge_differentiate_amount = $routeRates->differentiateSurgeAmount;

                    $model->bkg_trip_duration                       = $routeDuration->totalMinutes;
                    $model->bkgInvoice->bkg_driver_allowance_amount = $routeRates->driverAllowance;
                    $model->bkgInvoice->bkg_chargeable_distance     = $routeDistance->quotedDistance;
                    // $model->bkg_garage_time = $cabData['totalGarage'];
                    $model->bkgInvoice->bkg_vendor_amount           = round($routeRates->vendorAmount);
                    if ($bookData['bkg_is_state_tax_included'] != null)
                    {
                         Logger::trace("Quotetobook Entry7 bkg_is_state_tax_included =========" );
                        // $model->bkg_vendor_amount = $model->bkg_vendor_amount - $cabData['state_tax'];
                        if($bookData['bkg_is_state_tax_included'] == 0)
                        {
                        $routeRates->isStateTaxIncluded = 0;
                        $routeRates->stateTax           = 0;
                        }
                    }
                    if ($bookData['bkg_is_toll_tax_included'] != null)
                    {
                         Logger::trace("Quotetobook Entry8 bkg_is_toll_tax_included =========" );
//                        $model->bkg_vendor_amount = $model->bkg_vendor_amount - $cabData['toll_tax'];
                        if($bookData['bkg_is_toll_tax_included'] == 0)
                        {
                        $routeRates->isTollIncluded = 0;
                        $routeRates->tollTaxAmount  = 0;
                        }
                    }
                    $model->bkgInvoice->bkg_is_toll_tax_included    = $routeRates->isTollIncluded | 0;
                    $model->bkgInvoice->bkg_is_state_tax_included   = $routeRates->isStateTaxIncluded | 0;
                    $model->bkgInvoice->bkg_toll_tax                = $routeRates->tollTaxAmount | 0;
                    $model->bkgInvoice->bkg_state_tax               = $routeRates->stateTax | 0;
                    $model->bkgInvoice->bkg_is_airport_fee_included = $routeRates->isAirportEntryFeeIncluded | 0;
                    $model->bkgInvoice->bkg_airport_entry_fee       = $routeRates->airportEntryFee | 0;
                    $model->bkgInvoice->bkg_quoted_vendor_amount    = round($routeRates->vendorAmount);
                    if ($model->bkgAddInfo->bkg_spl_req_carrier == 1)
                    {
                        $model->bkgInvoice->bkg_additional_charge        = 150;
                        $model->bkgInvoice->bkg_additional_charge_remark = 'Carrier Requested for Rs.150';
                    }
                    else
                    {
                        $model->bkgInvoice->bkg_additional_charge        = 0;
                        $model->bkgInvoice->bkg_additional_charge_remark = '';
                    }
                    Logger::trace("Quote to book base amount:" . $baseAmount);
                    foreach ($bookData as $bk => $bv)
                    {

                        if ($bk == 'agentBkgAmountPay' || $bk == 'agentCreditAmount' || $bk == 'bkg_copybooking_phone' || $bk == 'bkg_copybooking_country' || $bk == 'bkg_copybooking_name' || $bk == 'bkg_copybooking_email' || $bk == 'hash' || $bk == 'preData')
                        {
                            $model->$bk = $bv;
                        }
                        if ($bk == 'bkg_user_id' || $bk == 'bkg_user_email' || $bk == 'bkg_user_lname' || $bk == 'bkg_contact_no' || $bk == 'bkg_country_code' || $bk == 'bkg_alt_country_code' || $bk == 'bkg_user_name' || $bk == 'bkg_alternate_contact')
                        {
                            if ($bk == 'bkg_alternate_contact')
                            {
                                $model->bkgUserInfo->bkg_alt_contact_no = $bv;
                            }
                            else if ($bk == 'bkg_user_name')
                            {
                                $model->bkgUserInfo->bkg_user_fname = $bv;
                            }
                            else
                            {
                                $model->bkgUserInfo->$bk = $bv;
                            }
                        }
                        if ($bk == 'bkg_flight_no' || $bk == 'bkg_flight_chk')
                        {
                            $model->bkgAddInfo->$bk = $bv;
                        }

                        if ($bk == 'bkg_is_toll_tax_included' || $bk == 'bkg_is_state_tax_included')
                        {
                            $model->bkgInvoice->$bk = $bv;
                        }
                    }

                    Logger::trace("Quote to book credit amount:" . $bookData['agentCreditAmount']);

                    if ($bookData['agentBkgAmountPay'] == 2)
                    {
                        // $model->bkg_corporate_credit = $bookData['agentCreditAmount'];
                        $model->bkgInvoice->bkg_corporate_remunerator = 2;
                        // $model->bkg_convenience_charge;
                        //$model->bkg_due_amount = $model->bkg_total_amount - $model->bkg_advance_amount + round($model->bkg_refund_amount) - $model->bkg_credits_used - $model->bkg_vendor_collected ;
                    }
                    else
                    {
                        $model->bkgInvoice->bkg_corporate_remunerator = 1;
                        unset($bookData['agentCreditAmount']);
                    }
//                    if ($bookData['bkg_trvl_sendupdate'] == 1) {
//                        $model->bkg_send_email = $bookData['bkg_send_email'];
//                        $model->bkg_send_sms = $bookData['bkg_send_sms'];
//                    }
//                    if ($bookData['bkg_trvl_sendupdate'] == 2) {
//                        $model->bkg_send_email = 0;
//                        $model->bkg_send_sms = 0;
//                    }
                    $model->bkgPref->bkg_send_email = 1;
                    $model->bkgPref->bkg_send_sms   = 1;

                    $model->bkgPref->bkg_trip_otp_required = $agtModel->agt_otp_required;

                    if ($baseAmount > 0)
                    {

                        $model->bkgInvoice->populateAmount(true, false, true, true, $model->bkg_agent_id);
                    }
                    if ($model->bkg_agent_id != '')
                    {
                        $agtModel = Agents::model()->findByPk($model->bkg_agent_id);
                        if ($agtModel->agt_city == 30706)
                        {
                            $model->bkgInvoice->bkg_cgst = Yii::app()->params['cgst'];
                            $model->bkgInvoice->bkg_sgst = Yii::app()->params['sgst'];
                            $model->bkgInvoice->bkg_igst = 0;
                        }
                        else
                        {
                            $model->bkgInvoice->bkg_igst = Yii::app()->params['igst'];
                            $model->bkgInvoice->bkg_cgst = 0;
                            $model->bkgInvoice->bkg_sgst = 0;
                        }
                    }
                    else
                    {
                        if ($model->bkg_from_city_id == 30706)
                        {
                            $model->bkgInvoice->bkg_cgst = Yii::app()->params['cgst'];
                            $model->bkgInvoice->bkg_sgst = Yii::app()->params['sgst'];
                            $model->bkgInvoice->bkg_igst = 0;
                        }
                        else
                        {
                            $model->bkgInvoice->bkg_igst = Yii::app()->params['igst'];
                            $model->bkgInvoice->bkg_cgst = 0;
                            $model->bkgInvoice->bkg_sgst = 0;
                        }
                    }
                    $model->bkgUserInfo->bkg_user_last_updated_on = new CDbExpression('NOW()');
                    $user_id                                      = (Yii::app()->user->getId() > 0) ? Yii::app()->user->getId() : '';
//					if ($user_id == '')
//					{
//						$userModel = Users::model()->linkUserByEmail($model->bkg_id, Booking::Platform_Agent);
//						if ($userModel)
//						{
//							$user_id = $userModel->user_id;
//						}
//					}
//					if ($user_id)
//					{
//						$model->bkgUserInfo->bkg_user_id = $user_id;
//					}
                    Logger::trace("Quote to booking id:" . $model->bkg_id);
                    $model->bkgTrail->bkg_user_ip                 = \Filter::getUserIP();
                    $cityinfo                                     = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
                    $model->bkgUserInfo->bkg_user_city            = $cityinfo['city'];
                    $model->bkgUserInfo->bkg_user_country         = $cityinfo['country'];
                    $model->bkgTrail->bkg_user_device             = UserLog::model()->getDevice();
                    $model->bkgTrail->bkg_platform                = Booking::Platform_Partner_Spot;
                    $model->bkgTrail->setPaymentExpiryTime();
                    $model->scenario                              = 'cabRate';

                    $bkgPfModel = BookingPriceFactor::model()->find('bpf_bkg_id=:bkg_id', ['bkg_id' => $model->bkg_id]);
                    if (!$bkgPfModel)
                    {
                        $bkgPfModel = new BookingPriceFactor();
                    }

                    $bkgPfModel->bpf_bkg_id = $model->bkg_id;
                    Logger::trace("Quote to booking validate:" . $model->validate());
                    Logger::trace("Quote to booking Invoice validate:" . $model->bkgInvoice->validate());
                    if ($model->validate() && $model->bkgInvoice->validate() && $model->bkgAddInfo->validate() && $model->bkgUserInfo->validate() && $bkgPfModel->validate() && $model->bkgTrail->validate() && $model->bkgPref->validate() && !$model->hasErrors())
                    {
                        $transaction = Yii::app()->db->beginTransaction();
                        try
                        {
                            Logger::trace("Quotetobook try Entry =========" );
                            $model->bkgAddInfo->bkg_no_person             = 2;
                            $model->bkgUserInfo->bkg_user_last_updated_on = new CDbExpression('NOW()');
                            $tmodel                                       = Terms::model()->getText(1);
                            $model->bkgTrail->bkg_tnc_id                  = $tmodel->tnc_id;
                            $model->bkgTrail->bkg_tnc_time                = new CDbExpression('NOW()');
                            $isRealtedBooking                             = $model->findRelatedBooking($model->bkg_id);
                            $model->bkgTrail->bkg_is_related_booking      = ($isRealtedBooking) ? 1 : 0;
                            $userInfo                                     = UserInfo::getInstance();
                            $model->bkgTrail->bkg_create_user_type        = $userInfo->userType;
                            $model->bkgTrail->bkg_create_user_id          = $userInfo->userId;

                            $model->bkgTrail->bkg_create_type = BookingTrail::CreateType_Self;

                            if ($model->bkg_agent_id != null)
                            {
                                $isGozonow                          = $model->bkgPref->bkg_is_gozonow;
                                $svcModel                           = SvcClassVhcCat::model()->getVctSvcList('object', 0, 0, $model->bkg_vehicle_type_id);
                                $cancelRuleId                       = CancellationPolicy::getCancelRuleId($model->bkg_agent_id, $svcModel->scv_id, $model->bkg_from_city_id,
                                        $model->bkg_to_city_id, $model->bkg_booking_type, $isGozonow);
                                $model->bkgPref->bkg_cancel_rule_id = $cancelRuleId;
                                // $model->bkgPref->save();
                            }






                            if (!$model->save() || !$model->bkgInvoice->save() || !$model->bkgPref->save() || !$model->bkgAddInfo->save() || !$model->bkgUserInfo->save() || !$model->bkgTrail->save() || !$bkgPfModel->updateFromQuote($quoteData))
                            {
                                throw new Exception("Failed to create booking", 101);
                            }
                            //Update partner commission and gozoamount
                            $model->bkgInvoice->refresh();
                            $model->bkgInvoice->populateAmount(true, false, true, true, $model->bkg_agent_id);
                            $model->bkgInvoice->save();

                            $bookingRoute->clearQuoteSession();

                            //booking pref
                            $bookingPref = BookingPref::model()->getByBooking($model->bkg_id);
                            if ($bookingPref == '')
                            {
                                $bookingPref             = new BookingPref();
                                $bookingPref->bpr_bkg_id = $model->bkg_id;
                            }
                            $model->bkgUserInfo->bkg_crp_name         = $bookData['bkg_copybooking_name'];
                            $bookingPref->bkg_crp_send_email          = $bookData['bkg_copybooking_ismail'][0];
                            $bookingPref->bkg_crp_send_sms            = $bookData['bkg_copybooking_issms'][0];
                            $model->bkgUserInfo->bkg_crp_email        = $bookData['bkg_copybooking_email'];
                            $model->bkgUserInfo->bkg_crp_phone        = $bookData['bkg_copybooking_phone'];
                            $model->bkgUserInfo->bkg_crp_country_code = $bookData['bkg_copybooking_country'];
                            $bookingPref->bkg_trv_send_email          = $bookData['bkg_send_email'];
                            $bookingPref->bkg_trv_send_sms            = $bookData['bkg_send_sms'];
                            if (SvcClassVhcCat::getClassById($model->bkg_vehicle_type_id) == ServiceClass::CLASS_VALUE_CNG)
                            {
                                $bookingPref->bkg_cng_allowed = 1;
                            }
                            if (!$bookingPref->bkg_cancel_rule_id)
                            {
                                $svcModelCat                     = SvcClassVhcCat::model()->getVctSvcList('object', 0, 0, $model->bkg_vehicle_type_id);
                                $cancelRuleId                    = CancellationPolicy::getCancelRuleId($model->bkg_agent_id, $svcModelCat->scv_id, $model->bkg_from_city_id, $model->bkg_to_city_id, $model->bkg_booking_type);
                                $bookingPref->bkg_cancel_rule_id = $cancelRuleId;
                            }
                            $bookingPref->save();
                            $bookingPref->refresh();
                            $bkg_cancel_rule_id = $bookingPref->bkg_cancel_rule_id;
                            //Create traveller contact
                            $contactId          = Contact::createbyBookingUser($model->bkgUserInfo);
                            Logger::trace("Quote to booking contact:" . $contactId);
//							if ($contactId)
//							{
//								$model->bkgUserInfo->bkg_contact_id = $contactId;
//							}
                            $model->bkgUserInfo->save();
                            //booking pref
                            if ($bookingData['agentNotifyData'] != '' && $bookingData['agentNotifyData'] != null && $bookingData['agentNotifyData'] != 'null')
                            {
                                $arrAgentNotifyOpt = json_decode($bookingData['agentNotifyData'], true);

                                $arrEvents = AgentMessages::getEvents();
                                foreach ($arrEvents as $key => $value)
                                {
                                    $bookingMessages = BookingMessages::model()->getByEventAndBookingId($model->bkg_id, $key);
                                    if ($bookingMessages == '')
                                    {
                                        $bookingMessages = new BookingMessages();
                                    }
                                    $bookingMessages->getMessageDefaults($model->bkg_agent_id, $key);
                                    $bookingMessages->bkg_booking_id  = $model->bkg_id;
                                    $bookingMessages->bkg_event_id    = $key;
                                    $bookingMessages->bkg_agent_email = $arrAgentNotifyOpt['agt_agent_email'][$key];
                                    $bookingMessages->bkg_agent_sms   = $arrAgentNotifyOpt['agt_agent_sms'][$key];
                                    $bookingMessages->bkg_agent_app   = $arrAgentNotifyOpt['agt_agent_app'][$key];
                                    $bookingMessages->bkg_trvl_email  = $arrAgentNotifyOpt['agt_trvl_email'][$key];
                                    $bookingMessages->bkg_trvl_sms    = $arrAgentNotifyOpt['agt_trvl_sms'][$key];
                                    $bookingMessages->bkg_trvl_app    = $arrAgentNotifyOpt['agt_trvl_app'][$key];

                                    $bookingMessages->bkg_agent_whatsapp = $arrAgentNotifyOpt['agt_agent_whatsapp'][$key];
                                    $bookingMessages->bkg_trvl_whatsapp  = $arrAgentNotifyOpt['agt_trvl_whatsapp'][$key];

                                    //  $bookingMessages->bkg_rm_email = $arrAgentNotifyOpt['agt_rm_email'][$key];
                                    //  $bookingMessages->bkg_rm_sms = $arrAgentNotifyOpt['agt_rm_sms'][$key];
                                    //  $bookingMessages->bkg_rm_app = $arrAgentNotifyOpt['agt_rm_app'][$key];
                                    $bookingMessages->save();
                                }
                            }
                            else
                            {
                                $arrEvents = AgentMessages::getEvents();
                                foreach ($arrEvents as $key => $value)
                                {
                                    $bookingMessages = BookingMessages::model()->getByEventAndBookingId($model->bkg_id, $key);
                                    if ($bookingMessages == '')
                                    {
                                        //calling for agent booking and agent panel
                                        $bookingMessages                 = new BookingMessages();
                                        $bookingMessages->getMessageDefaults($model->bkg_agent_id, $key);
                                        $bookingMessages->bkg_booking_id = $model->bkg_id;
                                        $bookingMessages->bkg_event_id   = $key;
                                        $bookingMessages->save();
                                       
                                    }
                                }
                            }

                            Logger::trace("Quote to book vendor amount:" . $model->bkgInvoice->bkg_vendor_amount);
                            $bookingCab                    = $model->getBookingCabModel();
                            $bookingCab->bcb_vendor_amount = $model->bkgInvoice->bkg_vendor_amount;
                            $bookingCab->bcb_bkg_id1       = $model->bkg_id;
                            $bookingCab->save();
                            BookingRoute::model()->setBookingCabStartEndTime($bookingCab->bcb_id, $model->bkg_id);
                            $agentModel1                   = Agents::model()->findByPk($model->bkg_agent_id);
                            if ($model->bkg_status == 1 && $model->bkgInvoice->bkg_corporate_remunerator == 2)
                            {

                                $amount = $bookData['agentCreditAmount'];
                                if ($model->bkg_agent_id > 0)
                                {
                                    $agtModels = Agents::model()->findByPk($model->bkg_agent_id, 'agt_type = 1');
                                    if ($agtModels != '')
                                    {
                                        $amount = $model->bkgInvoice->bkg_total_amount;
                                    }
                                    $amount = $amount | 0; //Credit added by agent;
                                    if ($amount > 0)
                                    {
                                        $isUpdateAdvance = $model->updateAdvance($amount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, UserInfo::getInstance(), null, "Partner Wallet Used");
                                        if (!$isUpdateAdvance)
                                        {
                                            throw new Exception("Booking failed as partner wallet balance exceeded.");
                                        }
                                        if ($isUpdateAdvance)
                                        {
                                            Booking::model()->confirm(true, true, $model->bkg_id);
                                        }
                                    }
                                }
                            }
                            Logger::trace("Quote to book: confirmation");
                            
                             DBUtil::commitTransaction($transaction);
                            if ($agentModel1->agt_approved == 1)
                            {
                                $model->refresh();
                                $bookingMessages->refresh();
                                //$model->confirmBooking(UserInfo::TYPE_SYSTEM);
                              
                                
                                
                                $model->confirm(false, true, $model->bkg_id, $userInfo        = null, $isAllowed       = true);
                                $model->refresh();
                                $model->bkgTrack = BookingTrack::model()->sendTripOtp($model->bkg_id, $sendOtp         = false);
                                $model->bkgTrack->save();
                                $model->bkgTrack->refresh();
                            }
                            $bkgBookingUser     = BookingUser::model()->saveVerificationOtp($model->bkg_id);
                            $model->bkgUserInfo = $bkgBookingUser;
                            $return['success']  = true;
                            $return['id']       = $model->bkg_id;
                            $return['hash']     = Yii::app()->shortHash->hash($model->bkg_id);

                            if ($model->bkg_status == 2)
                            {
                                //messages

                                $emailCom = new emailWrapper();
                                $emailCom->gotBookingemail($model->bkg_id, UserInfo::TYPE_SYSTEM, $model->bkg_agent_id);
                                $emailCom->gotBookingAgentUser($model->bkg_id);
                                $msgCom   = new smsWrapper();
                                $msgCom->gotBooking($model, UserInfo::TYPE_SYSTEM);
                              //  Booking::bookingReviewOtherLinks($model->bkg_id, '', $isSchedule     = 0);
                                //messages
                            }
                            Logger::trace("Quote to book status:" . $model->bkg_status);
                            if ($model->bkgInvoice->bkg_corporate_remunerator != 2 && $agentModel1->agt_approved != 1)
                            {
                                $isAlready2Sms = SmsLog::model()->getCountVerifySms($model->bkg_id);
                                if ($isAlready2Sms <= 2)
                                {
                                    $model->bkgUserInfo->sendVerificationCode(10, true);
                                }
                            }

                            $processedRoute = BookingLog::model()->logRouteProcessed($quoteData, $model->bkg_id);
                            $desc           = "Booking created by agent - $processedRoute";
                            $eventid        = BookingLog::BOOKING_CREATED;
                            BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventid);
                            BookingPriceFactor::model()->getQuotedFactor($model->bkg_id);
                          //  $transaction->commit();
                           //  DBUtil::commitTransaction($transaction);

                            $GLOBALS["bkg_id"] = $model->bkg_id;
                            $model->hash       = Yii::app()->shortHash->hash($model->bkg_id);
                            $GLOBALS["hash"]   = Yii::app()->shortHash->hash($model->bkg_id);
                            $success           = !$model->hasErrors();
                            Logger::trace("Quote to book booking id :" . $model->bkg_id);

                            $url = Yii::app()->createUrl('agent/booking/summary', ['action' => 'done', 'id' => $model->bkg_id, 'hash' => Yii::app()->shortHash->hash($model->bkg_id)]);

                            $return ['url'] = $url;
                            Logger::trace("Quotetobook tryURL =========" .$url);
//                            echo CJSON::encode(['success' => true, 'url' => $url]);
//                            Yii::app()->end();
//
                        }
                        catch (Exception $e)
                        {
                            Logger::error($e, true);
                            Logger::exception($e);
                            Logger::trace("Quotetobook catchEntry =========" .json_encode($e->getMessage()));
                            $btmodel->addError('bkg_id', $e->getMessage());
                            $model->addError('bkg_id', $e->getMessage());
                            //	echo json_encode($model->getErrors());
                            //	Yii::app()->end();
                            $transaction->rollback();
                        }
                    }
                    else
                    {

                        $success = false;
                    }
                    Logger::trace("Quotetobook prepreFinal =========" .$success);
                    if ($success)
                    {
                           Logger::trace("Quotetobook preFinal =========" .$success."bkg_id=======================".$model->bkg_id);
                        $url            = Yii::app()->createUrl('agent/booking/summary', ['action' => 'done', 'id' => $model->bkg_id, 'hash' => Yii::app()->shortHash->hash($model->bkg_id)]);
//                        if($model->bkg_corporate_remunerator!=2){
//                             $url = Yii::app()->createUrl('agent/booking/unverifiedsummary', ['id' => $model->bkg_id, 'hash' => Yii::app()->shortHash->hash($model->bkg_id)]);
//                        }
                        $return ['url'] = $url;

                        echo CJSON::encode(['success' => true, 'url' => $url]);
                        Yii::app()->end();
                    }
                    else
                    {
                        $errors = [];
                        foreach ($model->getErrors() as $attribute => $error)
                        {
                            $errors[CHtml::activeId($model, $attribute)] = $error;
                        }
                        $data = ["errors" => $errors];
                    }


                    $return['success'] = $success;
                    $return['res']     = '';
                    $return['type']    = $model->bkg_booking_type;
                    if ($success)
                    {
                        $return['data'] = $data;
                    }
                    else
                    {
                        $return["errors"] = $model->getErrors();
                    }
                }
  Logger::trace("Quotetobook Entry final =========");
                //$route_data = CJSON::decode($btmodel->bkg_route_data, true);
            }
        }
    }

    public function actionVerifybooking()
    {
        $bkgId           = Yii::app()->request->getParam('bkid'); //$_POST['bkid'];
        $model           = Booking::model()->findByPk($bkgId);
        $oldModel        = clone $model;
        $agentId         = $model->bkg_agent_id;
        $remunerator     = $model->bkgInvoice->bkg_corporate_remunerator;
        $modelAgt        = Agents::model()->findByPk($agentId);
        $approve         = $modelAgt->agt_approved;
        $model->scenario = 'adminupdate';
        if ($approve != 1)
        {
            $result['tab']     = 1;
            $result['success'] = false;
            $result['errors']  = 'Booking verification failed as Partner is UNAPPROVED.Please contact customer support (+91) 90518-77-000 for approval to any uninterrupted services.';
            goto Result;
        }
        if ($remunerator != 2 && $approve != 1)
        {
            $isAlready2Sms = SmsLog::model()->getCountVerifySms($model->bkg_id);
            if ($isAlready2Sms <= 2)
            {
                $model->sendVerificationCode(10, true);
            }
            $result['tab']     = 1;
            $result['success'] = false;
            $result['errors']  = 'Gozo has sent an OTP to the customer by SMS and email. Customer must confirm the booking by entering the OTP at the link provided to them.';
            goto Result;
        }

        //$isRechargeAccount = AccountTransDetails::model()->checkCreditLimit($model->bkg_agent_id, '', '', $model->bkgInvoice->bkg_total_amount, '', 3, false);
        //if (!$isRechargeAccount)
        //{
        if ($model->validate())
        {
            $sendConf    = false;
            $success     = false;
            $transaction = Yii::app()->db->beginTransaction();
            try
            {
                if ($model->bkgUserInfo->bkg_user_id == '')
                {
                    $userModel = Users::model()->linkUserByEmail($model->bkg_id, Booking::Platform_Admin);
                }
                if ($userModel)
                {
                    $model->bkgUserInfo->bkg_user_id = $userModel->user_id;
                    $model->save();
                }

                if (($model->bkg_agent_id > 0 && $model->bkg_agent_id == Yii::app()->user->getAgentId()) && $model->bkg_status == 1 && $model->bkgUserInfo->bkg_user_id > 0)
                {
                    $logType = UserInfo::TYPE_SYSTEM;
                    //$success = $model->confirmBooking($logType);
                    $amount  = $model->bkgInvoice->bkg_corporate_credit | 0; //Credit added by agent;
                    if ($amount > 0)
                    {
                        $desc    = "Partner Wallet Used after verify";
                        $agtcomm = $model->updateAdvance($amount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, UserInfo::getInstance(), null, $desc);
                    }
                    $resultSet = new ReturnSet();
                    $resultSet = $model->confirm(false, false, $model->bkg_id);
                    $success   = $resultSet->isSuccess();
                    if ($success)
                    {
                        $sendConf = true;
                        if ($model->bkgInvoice->bkg_promo1_id > 0 && $model->bkgUserInfo->bkg_user_id > 0)
                        {
                            //$promoModel = Promos::model()->getByCode($model->bkgInvoice->bkg_promo1_code);
                            $promoModel = Promos::model()->findByPk($model->bkgInvoice->bkg_promo1_id);
                            if (!$promoModel)
                            {
                                throw new Exception('Invalid Promo Code');
                            }
                            $promoModel->promoCode   = $model->bkgInvoice->bkg_promo1_code;
                            $promoModel->totalAmount = $model->bkgInvoice->bkg_base_amount;
                            $promoModel->createDate  = $model->bkg_create_date;
                            $promoModel->pickupDate  = $model->bkg_pickup_date;
                            $promoModel->fromCityId  = $model->bkg_from_city_id;
                            $promoModel->toCityId    = $model->bkg_to_city_id;
                            $promoModel->userId      = $model->bkgUserInfo->bkg_user_id;
                            $promoModel->platform    = $model->bkgTrail->bkg_platform;
                            $promoModel->carType     = $model->bkg_vehicle_type_id;
                            $promoModel->bookingType = $model->bkg_booking_type;
                            $promoModel->noOfSeat    = $model->bkgAddInfo->bkg_no_person;
                            $promoModel->bkgId       = $model->bkg_id;
                            $promoModel->email       = '';
                            $promoModel->phone       = '';
                            $promoModel->imEfect     = '';

                            $discountArr = $promoModel->applyPromoCode();
                            if ($discountArr != false)
                            {
                                if ($discountArr['coins'] > 0)
                                {
                                    if (isset($discountArr['nextTripApply']) && $discountArr['nextTripApply'] == 1)
                                    {
                                        $discountArr['cash']  = 0;
                                        $discountArr['coins'] = 0;
                                    }
                                    //$promoModel = Promos::model()->getByCode($model->bkgInvoice->bkg_promo1_code);
                                    if ($discountArr['pcn_type'] == 2 && $discountArr['prm_activate_on'] != 1)
                                    {
                                        $creditModel1 = UserCredits::model()->find('ucr_type=1 AND ucr_ref_id=:bkgId AND ucr_status=2 AND ucr_user_id=:user', ['bkgId' => $model->bkg_id, 'user' => $model->bkg_user_id]);
                                        if ($creditModel1 == '' || $creditModel1 == null)
                                        {
                                            $creditModel1 = new UserCredits();
                                        }
                                        $creditModel1->ucr_user_id     = $model->bkgUserInfo->bkg_user_id;
                                        $creditModel1->ucr_value       = $discountArr['coins'];
                                        $creditModel1->ucr_desc        = 'CREDITS AGAINST PROMO';
                                        $creditModel1->ucr_type        = 1;
                                        $creditModel1->ucr_maxuse_type = Yii::app()->params['creditMaxUseType']; //3;
                                        $creditModel1->ucr_status      = 2;
                                        $creditModel1->ucr_max_use     = $creditModel1->ucr_value;
                                        $creditModel1->ucr_validity    = date('Y-m-d H:i:s', strtotime('+1 years'));
                                        $creditModel1->ucr_ref_id      = $model->bkg_id;
                                        $creditModel1->save();
                                    }
                                }
                            }
                        }
                    }


                    if ($success)
                    {
                        $desc     = "Booking verified by Agent.";
                        $eventId  = BookingLog::BOOKING_VERIFIED;
                        $userInfo = UserInfo::getInstance();

                        if ($model->bkg_agent_id > 0)
                        {
                            $agentsModel = Agents::model()->findByPk($model->bkg_agent_id);
                            if ($agentsModel->agt_type == 1)
                            {
                                $userInfo->userType = UserInfo::TYPE_CORPORATE;
                            }
                        }

                        BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId, $oldModel);
                    }
                    else
                    {
                        throw new Exception("Booking verified failed. (" . json_encode($model->getErrors()) . ")");
                    }
                }

                $result['success'] = $success;
                $result['errors']  = '';

                $transaction->commit();
                if ($sendConf)
                {
                    $logType = UserInfo::TYPE_SYSTEM;
                    $model->sendConfirmation($logType);
                }
                else
                {
                    $result['errors'] = 'Booking already verified';
                }
            }
            catch (Exception $e)
            {
                $result['success'] = $success;
                $result['errors']  = 'Error occurred while verifying. ' . $e->getMessage();
                $model->addError("bkg_id", $e->getMessage());
                $transaction->rollback();
            }
        }
        else
        {
            $result['tab']     = 1;
            $result['success'] = false;
            $result['errors']  = 'Incomplete Data';
        }
        //}
//		else
//		{
//			$result['tab']		 = 1;
//			$result['success']	 = false;
//			$result['errors']	 = 'Credit limit exceeded, please recharge your account.';
//		}

        Result:
        echo json_encode($result);
        Yii::app()->end();
    }

    //to be change later
    public function actionCredithistory()
    {
        $model      = new Booking('search');
        $paramArray = [];
        $agentId    = Yii::app()->user->getAgentId();
        if (isset($_REQUEST['Booking']))
        {
            $model->attributes         = Yii::app()->request->getParam('Booking');
            $paramArray                = Yii::app()->request->getParam('Booking');
            $model->bkg_pickup_date1   = $paramArray['bkg_pickup_date1'];
            $model->bkg_pickup_date2   = $paramArray['bkg_pickup_date2'];
            $model->bkg_create_date1   = $paramArray['bkg_create_date1'];
            $model->bkg_create_date2   = $paramArray['bkg_create_date2'];
            $model->agt_trans_created1 = $paramArray['agt_trans_created1'];
            $model->agt_trans_created2 = $paramArray['agt_trans_created2'];
        }
        else
        {
            $model->bkg_status         = '';
            $model->bkg_pickup_date1   = ($paramArray['bkg_pickup_date1'] == '') ? date('Y-m-d', strtotime('-1 month')) : $paramArray['bkg_pickup_date1'];
            $model->bkg_pickup_date2   = ($paramArray['bkg_pickup_date2'] == '') ? date('Y-m-d', strtotime('+11 month')) : $paramArray['bkg_pickup_date2'];
            $model->bkg_create_date1   = ($paramArray['bkg_create_date1'] == '') ? date('Y-m-d', strtotime('-1 month')) : $paramArray['bkg_create_date1'];
            $model->bkg_create_date2   = ($paramArray['bkg_create_date2'] == '') ? date('Y-m-d') : $paramArray['bkg_create_date2'];
            $model->agt_trans_created1 = ($paramArray['agt_trans_created1'] == '') ? date('Y-m-d', strtotime('-1 month')) : $paramArray['agt_trans_created1'];
            $model->agt_trans_created2 = ($paramArray['agt_trans_created2'] == '') ? date('Y-m-d') : $paramArray['agt_trans_created2'];
        }


        $this->pageTitle = "Partner Account Details";
        $agentBkgStatus  = BookingSub::model()->getAgentActiveBookingStatusList($agentId);
        $statusJSON      = VehicleTypes::model()->getJSON($agentBkgStatus);
        $dataProvider    = AgentTransactions::model()->agentTransactionList(['agentId' => $agentId] + array_filter($model->attributes + $paramArray));

        $this->render('credithistory', ['dataProvider' => $dataProvider, 'model' => $model, 'statusJSON' => $statusJSON]);
    }

    public function actionAccountsdashboard()
    {
        $this->pageTitle = "Accounts Dashboard";
        $model           = new Booking('search');
        $submodel        = new BookingSub();
        $agentId         = Yii::app()->user->getAgentId();
        if (isset($_REQUEST['Booking']))
        {
            $arr                     = Yii::app()->request->getParam('Booking');
            $from                    = $arr['bkg_from_city_id'];
            $to                      = $arr['bkg_to_city_id'];
            $search                  = $arr['search'];
            $status                  = $arr['bkg_status_name'];
            $model->bkg_pickup_date1 = $arr['bkg_pickup_date1'];
            $model->bkg_pickup_date2 = $arr['bkg_pickup_date2'];
            $model->bkg_create_date1 = $arr['bkg_create_date1'];
            $model->bkg_create_date2 = $arr['bkg_create_date2'];
        }
        else
        {
            $from   = '';
            $to     = '';
            $search = '';
            $status = '';
            if ($_REQUEST['page'] < 2)
            {
                $model->bkg_pickup_date1 = $arr['bkg_pickup_date1'] = ($arr['bkg_pickup_date1'] == '') ? date('Y-m-d', strtotime('-1 month')) : $arr['bkg_pickup_date1'];
                $model->bkg_pickup_date2 = $arr['bkg_pickup_date2'] = ($arr['bkg_pickup_date2'] == '') ? date('Y-m-d', strtotime('+11 month')) : $arr['bkg_pickup_date2'];
                $model->bkg_create_date1 = $arr['bkg_create_date1'] = ($arr['bkg_create_date1'] == '') ? date('Y-m-d', strtotime('-1 month')) : $arr['bkg_create_date1'];
                $model->bkg_create_date2 = $arr['bkg_create_date2'] = ($arr['bkg_create_date2'] == '') ? date('Y-m-d') : $arr['bkg_create_date2'];
            }
            else
            {
                $model->bkg_pickup_date1 = $arr['bkg_pickup_date1'];
                $model->bkg_pickup_date2 = $arr['bkg_pickup_date2'];
                $model->bkg_create_date1 = $arr['bkg_create_date1'];
                $model->bkg_create_date2 = $arr['bkg_create_date2'];
            }
        }
        $model->bkg_from_city_id = $from;
        $model->bkg_to_city_id   = $to;
        $model->search           = $search;
        $model->bkg_status_name  = $status;
        $dataProvider            = $submodel->agentAccountsDashboard($from, $to, $search, $agentId, '', $status, false, $arr);
        if (isset($_REQUEST['export_from_city']) && isset($_REQUEST['export_to_city']) && isset($_REQUEST['export_search']))
        {
            $fromCity                = Yii::app()->request->getParam('export_from_city');
            $toCity                  = Yii::app()->request->getParam('export_to_city');
            $search                  = Yii::app()->request->getParam('export_search');
            $status                  = Yii::app()->request->getParam('export_status');
            $fromDate                = Yii::app()->request->getParam('export_from_date');
            $toDate                  = Yii::app()->request->getParam('export_to_date');
            $arr['bkg_pickup_date1'] = $fromDate;
            $arr['bkg_pickup_date2'] = $toDate;
            $arr['bkg_create_date1'] = '';
            $arr['bkg_create_date2'] = '';
            $type                    = true;
            $rows                    = $submodel->agentAccountsDashboard($from, $to, $search, $agentId, '', $status, $type, $arr);
            header('Content-type: text/csv');
            header("Content-Disposition: attachment; filename=\"Report_" . date('Ymdhis') . ".csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle                  = fopen("php://output", 'w');
            if ($agentId == Config::get('spicejet.partner.id'))
            {
                fputcsv($handle, ['Gozo Bkg ID', 'Agent Ref ID', 'Create Date / Time', 'Pickup Date / Time', 'Base Fare', 'Extra charges', 'Extra Charges (Km)', 'Extra minutes', 'Extra Charges (Min)', 'Discount', 'DA', 'Toll', 'State', 'GST', 'AirportCharges', 'Total Fare', 'Agent Commission', 'Partner Extra Commission', 'Commission on GST',
                    'Advance', 'Refund', 'Status', 'Settled', 'Remarks', 'Partner extra commission']);
            }
            else
            {
                fputcsv($handle, ['Gozo Bkg ID', 'Agent Ref ID', 'Create Date / Time', 'Pickup Date / Time', 'Base Fare', 'Extra charges', 'Extra Charges (Km)', 'Extra minutes', 'Extra Charges (Min)', 'Discount', 'DA', 'Toll', 'State', 'GST', 'AirportCharges', 'Total Fare', 'Agent Commission', 'Commission on GST',
                    'Advance', 'Refund', 'Status', 'Settled', 'Remarks', 'Partner extra commission']);
            }
            foreach ($rows as $row)
            {
                $rowArray                                = array();
                $rowArray['bkg_booking_id']              = $row['bkg_booking_id'];
                $rowArray['bkg_agent_ref_code']          = $row['bkg_agent_ref_code'];
                $rowArray['bkg_create_date']             = ($row['bkg_create_date'] != '' || $row['bkg_create_date'] != NULL) ? date("d/m/Y H:i:s", strtotime($row['bkg_create_date'])) : '';
                $rowArray['bkg_pickup_date']             = ($row['bkg_pickup_date'] != '' || $row['bkg_pickup_date'] != NULL) ? date("d/m/Y H:i:s", strtotime($row['bkg_pickup_date'])) : '';
                $rowArray['bkg_base_amount']             = $row['bkg_base_amount'];
                $rowArray['bkg_additional_charge']       = $row['bkg_additional_charge'];
                $rowArray['bkg_extra_km_charge']         = ($row['bkg_extra_km_charge'] > 0) ? $row['bkg_extra_km_charge'] . "(" . $row['bkg_extra_total_km'] . " Km)" : 0;
                $rowArray['bkg_extra_min']               = $row['bkg_extra_min'];
                $rowArray['bkg_extra_total_min_charge']  = $row['bkg_extra_total_min_charge'];
                $rowArray['bkg_discount_amount']         = $row['bkg_discount_amount'];
                $rowArray['bkg_driver_allowance_amount'] = $row['bkg_driver_allowance_amount'];
                $rowArray['bkg_toll_tax']                = $row['bkg_toll_tax'];
                $rowArray['bkg_state_tax']               = $row['bkg_state_tax'];
                $rowArray['bkg_service_tax']             = $row['bkg_service_tax'];
                $rowArray['bkg_airport_entry_fee']       = $row['bkg_airport_entry_fee'];
                $rowArray['bkg_total_amount']            = $row['bkg_total_amount'];
                $rowArray['bkg_partner_commission']      = $row['bkg_partner_commission'];
                if ($agentId == Config::get('spicejet.partner.id'))
                {
                    $rowArray['bkg_partner_extra_commission'] = $row['bkg_partner_extra_commission'];
                }
                $rowArray['commissionGst']      = $row['commissionGst'];
                $rowArray['bkg_advance_amount'] = $row['bkg_advance_amount'];
                $rowArray['bkg_refund_amount']  = $row['bkg_refund_amount'];
                $rowArray['status']             = $row['status'];
                $rowArray['settled']            = $row['settled'];
                $rowArray['remarks']            = $row['remarks'];
                $row1                           = array_values($rowArray);
                fputcsv($handle, $row1);
            }
            fclose($handle);
            Yii::log("After IN TO OUT FILE query " . $sql, CLogger::LEVEL_INFO, 'system.api.images');
            if (!$rows)
            {
                Yii::log("Can not export data " . $sql, CLogger::LEVEL_INFO, 'system.api.images');
                die('Could not take data backup: ' . mysql_error());
            }
            else
            {
                Yii::log("Exported data successfully " . $sql, CLogger::LEVEL_INFO, 'system.api.images');
            }
            exit;
        }
        $this->render('accounts_dashboard', array('dataProvider' => $dataProvider, 'model' => $model, 'agentId' => $agentId));
    }

    public function actionAddremark()
    {
        $bkgId    = Yii::app()->request->getParam('bkg_id');
        $model    = Booking::model()->findByPk($bkgId);
        $logModel = new BookingLog('addmarkremark');
        $success  = true;
        $type     = 0;
        if (isset($_POST['BookingLog']))
        {
            $arr                  = $_POST['BookingLog'];
            $logModel->attributes = $arr;
            $remark               = $logModel->blg_desc;
            if ($logModel->validate())
            {
                $userInfo                         = UserInfo::getInstance();
                $eventId                          = BookingLog::REMARKS_ADDED_AGENT;
                $bkg_status                       = $model->bkg_status;
                $params                           = [];
                $params['blg_booking_status']     = $bkg_status;
                $params['blg_remark_type']        = '1';
                BookingLog::model()->createLog($logModel->blg_booking_id, $remark, $userInfo, $eventId, $oldModel, $params);
                $oldModel                         = $model;
                $model->bkgPref->bkg_account_flag = 2;
                $model->bkgPref->bkg_settled_flag = 0;
                $model->bkgPref->scenario         = 'accountflag';
                if ($model->bkgPref->save())
                {
                    $eventId                      = BookingLog::SET_ACCOUNTING_FLAG_AGENT;
                    $desc                         = "Accounting Flag has been set by agent.";
                    $params['blg_booking_status'] = $model->bkg_status;
                    BookingLog::model()->createLog($bkgId, $desc, $userInfo, $eventId, $oldModel, $params);
                    $this->redirect(array('accountsdashboard'));
                }
                else
                {
                    $success = false;
                    $type    = 2;
                }
            }
            else
            {
                $success = false;
                $type    = 1;
            }
        }
        $userId       = Yii::app()->user->getId();
        $dataProvider = BookingLog::getRemarksHistoryByAgent($bkgId, $userId);
        $outputJs     = Yii::app()->request->isAjaxRequest;
        $method       = "render" . ($outputJs ? "Partial" : "");
        $this->$method('addremark', array('model' => $model, 'logModel' => $logModel, 'success' => $success, 'type' => $type, 'dataProvider' => $dataProvider), false, $outputJs);
    }

    public function actionMarksettled()
    {
        $bkgId = Yii::app()->request->getParam('bkg_id');
        $model = Booking::model()->findByPk($bkgId);
        if ($model)
        {
            $model->bkgPref->bkg_settled_flag = 1;
            if ($model->bkgPref->save())
            {
                $desc                         = "Booking marked settled by agent.";
                $event_id                     = BookingLog::BOOKING_MARKED_SETTLED;
                $userInfo                     = UserInfo::getInstance();
                $params['blg_booking_status'] = $model->bkg_status;
                BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $event_id, false, $params);
            }
        }
        $this->redirect(array('accountsdashboard'));
    }

    public function actionLedgerbooking()
    {
        $this->pageTitle = "Partner Accounts";
        $agtId           = Yii::app()->user->getAgentId();
        $transDate1      = '';
        $transDate2      = '';
        $model           = new AccountTransDetails();
        $model->scenario = "ledgerbooking";

        if ($_REQUEST['AccountTransDetails']['trans_create_date1'] != '' && $_REQUEST['AccountTransDetails']['trans_create_date2'] != '')
        {
            $transDate1                = $_REQUEST['AccountTransDetails']['trans_create_date1'];
            $transDate2                = $_REQUEST['AccountTransDetails']['trans_create_date2'];
            $model->trans_create_date1 = $transDate1;
            $model->trans_create_date2 = $transDate2;
        }
        if (!isset($_REQUEST['AccountTransDetails']) && $_REQUEST['AccountTransDetails']['trans_create_date1'] == '' && $_REQUEST['AccountTransDetails']['trans_create_date2'] == '')
        {
            $transDate1                = $model->trans_create_date1 = date('Y-m-d', strtotime('today - 29 days'));
            $transDate2                = $model->trans_create_date2 = date('Y-m-d', strtotime('today'));
        }

        $recordSet                          = AccountTransDetails::transactionList($agtId, $transDate1, $transDate2);
        $totalRecords                       = count($recordSet);
        $agentList                          = new CArrayDataProvider($recordSet, array('pagination' => array('pageSize' => 500)));
        $agentList->getPagination()->params = array_filter($_GET + $_POST);
        $agentList->getSort()->params       = array_filter($_GET + $_POST);
        $agentModels                        = $agentList->getData();
        $tillDate                           = (strtotime($transDate2 . " 23:59:59") > strtotime(date("Y-m-d H:i:s"))) ? date("Y-m-d H:i:s") : $transDate2 . " 23:59:59";
        $agentAmount                        = AccountTransDetails::accountTotalSummary($agtId, '', '', '', $tillDate);
        $getBalance                         = PartnerStats::getBalance($agtId);
        if (isset($_REQUEST['export_from']) && isset($_REQUEST['export_to']))
        {
            $arr         = array();
            $fromDate    = Yii::app()->request->getParam('export_from');
            $toDate      = Yii::app()->request->getParam('export_to');
            $arr['data'] = AccountTransDetails::transactionList($agtId, $fromDate, $toDate);
            if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
            {
                header("Content-type: application/csv");
                header("Content-Disposition: attachment; filename=\"LedgerReport_{$fromDate}_{$toDate}_" . date('Ymdhis') . ".csv\"");
                header("Pragma: no-cache");
                header("Expires: 0");
                $handle = fopen("php://output", 'w');
                fputcsv($handle, array("Transaction Date", "Booking ID", "Partner Reference ID", "Pickup Date", "Booking Info", "Amount", "Notes", "Who", "Running Balance"));
                foreach ($arr['data'] as $req)
                {

                    $bookingInfo = $req['bookingInfo'];

                    fputcsv($handle, array($req['act_date'], $req['bookingId'], $req['bkg_agent_ref_code'], $req['bkg_pickup_date'], $bookingInfo, number_format(round($req['adt_amount'])), $req['act_remarks'], trim($req['adminName']), number_format($req['runningBalance'])));
                }
                fclose($handle);
                exit;
            }
        }

        $this->render('ledgerbooking', ['model' => $model, 'agentList' => $agentList, 'agentmodels' => $agentModels, 'agentAmount' => $agentAmount, 'totalRecords' => $totalRecords, 'getBalance' => $getBalance]);
    }

    public function actionBooksummaryrefresh()
    {
        $tollTax  = Yii::app()->request->getParam('toll');
        $stateTax = Yii::app()->request->getParam('state');
        $btmodel  = new BookingTemp('new');

        $bkgid          = Yii::app()->request->getParam('bkg_id');
        $hash           = Yii::app()->request->getParam('hash');
        $btmodel->hash  = $hash;
        $booking        = Yii::app()->request->getParam("preData");
        $preBookingData = $booking;
        if ($bkgid)
        {
            $btmodel = BookingTemp::model()->findByPk($bkgid);
            if (!$btmodel)
            {
                throw new CHttpException(400, 'Invalid data');
            }

            if (!Yii::app()->user->isGuest)
            {
                $userId               = Yii::app()->user->getId();
                $userModel            = Users::model()->findByPk($userId);
                $btmodel->bkg_user_id = $userModel->user_id;
                if ($userModel->usr_mobile != '' && $btmodel->bkg_contact_no == '')
                {
                    
                } if ($userModel->usr_email != '' && $btmodel->bkg_user_email == '')
                {
                    
                }
            }
            $route_data = CJSON::decode($btmodel->bkg_route_data, true);
            if ($btmodel->bkg_booking_type == 4)
            {
                if ($btmodel->bkg_transfer_type == 1)
                {
                    $airportAddress                     = $btmodel->bkgFromCity->cty_garage_address;
                    $btmodel->bkg_pickup_address        = $airportAddress;
                    $route_data[0]['brt_from_location'] = $airportAddress;
                }
                if ($btmodel->bkg_transfer_type == 2)
                {
                    $airportAddress                   = $btmodel->bkgToCity->cty_garage_address;
                    $btmodel->bkg_drop_address        = $airportAddress;
                    $route_data[0]['brt_to_location'] = $airportAddress;
                }
            }

            foreach ($route_data as $k => $v)
            {
                $bookingRoute             = new BookingRoute();
                $bookingRoute->attributes = $v;
                $bookingRoutes[]          = $bookingRoute;
            }
            $btmodel->bookingRoutes = $bookingRoutes;
        }


        $agtId         = Yii::app()->user->getAgentId();
        $agtModel      = Agents::model()->findByPk($agtId);
        $route_data    = CJSON::decode($btmodel->bkg_route_data, true);
        $bookingRoutes = [];
        if ($btmodel->bkg_booking_type == 4)
        {
            if ($btmodel->bkg_transfer_type == 1)
            {
                $airportAddress                     = $btmodel->bkgFromCity->cty_garage_address;
                $btmodel->bkg_pickup_address        = $airportAddress;
                $route_data[0]['brt_from_location'] = $airportAddress;
            }
            if ($btmodel->bkg_transfer_type == 2)
            {
                $airportAddress                   = $btmodel->bkgToCity->cty_garage_address;
                $btmodel->bkg_drop_address        = $airportAddress;
                $route_data[0]['brt_to_location'] = $airportAddress;
            }
        }

        $cityArr = [];
        foreach ($route_data as $rtk => $rtv)
        {
            if ($rtk == 0)
            {
                $cityArr[$rtk] = $rtv['brt_from_city_id'];
            }
            $cityArr[$rtk + 1] = $rtv['brt_to_city_id'];
        }
        if ($btmodel->bkg_booking_type != 4)
        {
            $getAirportCities = BookingSub::model()->checkAirport($cityArr);
            if (sizeof($getAirportCities) > 0)
            {
                foreach ($getAirportCities as $k => $v)
                {
                    $airportAddress = $v['name'];
                    if ($k == 0)
                    {
                        $btmodel->bkg_pickup_address        = $airportAddress;
                        $route_data[0]['brt_from_location'] = $airportAddress;
                    }
                    else
                    {
                        if (sizeof($route_data) == $k)
                        {
                            $btmodel->bkg_drop_address = $airportAddress;
                        }
                        $route_data[$k - 1]['brt_to_location'] = $airportAddress;
                    }
                }
            }
        }

        foreach ($route_data as $k => $v)
        {
            $bookingRoute             = new BookingRoute();
            $bookingRoute->attributes = $v;
            $bookingRoutes[]          = $bookingRoute;
        }

        $btmodel->bookingRoutes = $bookingRoutes;
        //$carType				 = VehicleTypes::model()->getVehicleTypeById($btmodel->bkg_vehicle_type_id);
        $carType                = $btmodel->bkgSvcClassVhcCat->scv_vct_id;

        $quote                  = new Quote();
        $quote->routes          = $bookingRoutes;
        $quote->tripType        = $btmodel->bkg_booking_type;
        $quote->partnerId       = $agtModel->agt_id;
        $quote->quoteDate       = $btmodel->bkg_create_date;
        $quote->pickupDate      = $btmodel->bkg_pickup_date;
        $quote->returnDate      = $btmodel->bkg_return_date;
        $quote->sourceQuotation = Quote::Platform_Partner_Spot;
        $quote->setCabTypeArr();
        $qt                     = $quote->getQuote($carType);
        $routeData              = $qt[$carType];

        $bkgModel        = new Booking();
        $bkgInvModel     = new BookingInvoice();
        $bkgTrackModel   = new BookingTrack();
        $bkgAddInfoModel = new BookingAddInfo();
        $bkgPf           = new BookingPriceFactor();

        $routeRates    = $routeData->routeRates;
        $routeDistance = $routeData->routeDistance;
        $routeDuration = $routeData->routeDuration;

        if ($routeData->success)
        {
            $routeRates = Agents::model()->getBaseDiscFare($routeData->routeRates, $agtModel->agt_type, $agtModel->agt_id);
//            $cabData  = $arrQuote;
            if ($tollTax == 0)
            {
                $routeRates->isTollIncluded = 0;
                $routeRates->tollTaxAmount  = 0;
            }
            if ($stateTax == 0)
            {
                $routeRates->isStateTaxIncluded = 0;
                $routeRates->stateTax           = 0;
            }
            $amount                                   = round($routeRates->baseAmount);
            $bkgModel->bkg_agent_id                   = $agtId;
            $bkgInvModel->bkg_gozo_base_amount        = $amount;
            $bkgInvModel->bkg_base_amount             = $amount;
            $bkgModel->bkg_trip_distance              = $routeDistance->quotedDistance;
            $bkgModel->bkg_trip_duration              = $routeDuration->totalMinutes;
            $bkgInvModel->bkg_driver_allowance_amount = $routeRates->driverAllowance;
            $bkgInvModel->bkg_chargeable_distance     = $routeDistance->quotedDistance;
            $bkgTrackModel->bkg_garage_time           = $routeDuration->totalMinutes + $routeDuration->garageTimeEnd + $routeDuration->garageTimeStart;
            $bkgInvModel->bkg_is_toll_tax_included    = $routeRates->isTollIncluded | 0;
            $bkgInvModel->bkg_is_state_tax_included   = $routeRates->isStateTaxIncluded | 0;
            $bkgInvModel->bkg_toll_tax                = $routeRates->tollTaxAmount | 0;
            $bkgInvModel->bkg_state_tax               = $routeRates->stateTax | 0;
            $bkgInvModel->bkg_airport_entry_fee       = $routeRates->airportEntryFee | 0;
            $bkgInvModel->bkg_vendor_amount           = round($routeRates->vendorAmount);
            $bkgInvModel->bkg_quoted_vendor_amount    = round($routeRates->vendorAmount);

            if ($bkgAddInfoModel->bkg_spl_req_carrier == 1)
            {
                $bkgInvModel->bkg_additional_charge        = 150;
                $bkgInvModel->bkg_additional_charge_remark = 'Carrier Requested for Rs.150';
            }
            else
            {
                $bkgInvModel->bkg_additional_charge        = 0;
                $bkgInvModel->bkg_additional_charge_remark = '';
            }

            $bkgInvModel->bkg_advance_amount = 0;

            if ($amount > 0)
            {
                $bkgInvModel->calculateConvenienceFee(0);
                $bkgInvModel->populateAmount(true, false, true, true, $bkgModel->bkg_agent_id);
            }
        }
        $arrAmount = array_filter($bkgModel->attributes + $bkgInvModel->attributes + $bkgPf->attributes + $bkgTrackModel->attributes + $bkgAddInfoModel->attributes);
        //$arrAmount = array_filter($bkgModel->attributes);
        //unset($arrAmount['bkg_trip_type']);
        foreach ($arrAmount as $k => $v)
        {
            $btmodel->$k = $v;
        }
        if ($btmodel->bkg_agent_id != '')
        {
            if ($agtModel->agt_city == 30706)
            {
                $btmodel->bkg_cgst = Yii::app()->params['cgst'];
                $btmodel->bkg_sgst = Yii::app()->params['sgst'];
                $btmodel->bkg_igst = 0;
            }
            else
            {
                $btmodel->bkg_igst = Yii::app()->params['igst'];
                $btmodel->bkg_cgst = 0;
                $btmodel->bkg_sgst = 0;
            }
        }
        else
        {
            if ($btmodel->bkg_from_city_id == 30706)
            {
                $btmodel->bkg_cgst = Yii::app()->params['cgst'];
                $btmodel->bkg_sgst = Yii::app()->params['sgst'];
                $btmodel->bkg_igst = 0;
            }
            else
            {
                $btmodel->bkg_igst = Yii::app()->params['igst'];
                $btmodel->bkg_cgst = 0;
                $btmodel->bkg_sgst = 0;
            }
        }
        $btmodel->bkg_advance_amount = 0;
        $btmodel->agentBkgAmountPay  = 1;
        $btmodel->agentCreditAmount  = $btmodel->bkg_total_amount;

        $btmodel->hash    = $GLOBALS['hash'];
        $btmodel->preData = $preBookingData;

        $this->renderPartial('booksummaryrefresh', ['model' => $btmodel, 'bkgInvModel' => $bkgInvModel], false, true);
    }

    public function actionSpot()
    {
        $this->pageTitle = " ";
        $this->layout    = "main";
        $step            = Yii::app()->request->getParam('step', 4);

        $model      = new Booking('spotStep4');
        $bkgInvoice = new BookingInvoice();
        $bkgUser    = new BookingUser();
        $bkgAddInfo = new BookingAddInfo();
        $bkgTrail   = new BookingTrail();
        $bkgTrack   = new BookingTrack();
        $bkgPref    = new BookingPref();
        $bkgPf      = new BookingPriceFactor();

        $model->bkg_agent_id = Yii::app()->user->getAgentId();
        $partnerId           = Yii::app()->user->getAgentId();
        if ($step == 4)
        {
            $this->render('spot4');
            Yii::app()->end();
        }

        if ($step == 15 || isset($_POST['step16ToStep15']))
        {
            if (isset($_POST['Booking']) && isset($_POST['step15submit']))
            {
                $bkgArr            = array_filter($_POST['Booking']);
                $model->scenario   = 'spotShuttle';
                $preData           = CJSON::decode($_POST['Booking']['preData']);
                $model->attributes = $bkgArr;

                if ($model->validate())
                {
                    $sltId          = $model->bkg_shuttle_id;
                    $shuttle        = Shuttle::model()->getDetailbyId($sltId);
                    $model->preData = ['preBookData' => ['bkg_shuttle_id' => $sltId, 'bkg_booking_type' => $model->bkg_booking_type]];
                    $this->render('spot16', ['model' => $model, 'shuttle' => $shuttle]);
                    Yii::app()->end();
                }
            }
            $model->bkg_booking_type = ($model->bkg_booking_type == '') ? Yii::app()->request->getParam('bookingType') : $model->bkg_booking_type;
            $this->render('spot15', ['model' => $model]);
            Yii::app()->end();
        }
        if ($step == 16 || isset($_POST['step17ToStep16']))
        {
            if (isset($_POST['Booking']) && isset($_POST['step16submit']))
            {
                $bkgArr            = array_filter($_POST['Booking']);
                $model->scenario   = 'spotShuttle';
                $preData           = CJSON::decode($_POST['Booking']['preData']);
                $model->attributes = $bkgArr;
                $no_of_seats       = $bkgArr['bkg_no_of_seats'];
                if (!isset($bkgArr['bkg_no_of_seats']))
                {
                    $model->bkg_no_of_seats = 0;
                }

                if ($model->validate() && $no_of_seats > 0)
                {
                    $no_of_seats    = $bkgArr['bkg_no_of_seats'];
                    $model->preData = ['preBookData' => ['bkg_no_of_seats' => $no_of_seats, 'bkg_shuttle_id' => $model->bkg_shuttle_id, 'bkg_booking_type' => $model->bkg_booking_type]];
                    $this->render('spot17', ['model' => $model, 'bookingUser' => $bkgUser, 'no_of_seat' => $no_of_seats]);
                    Yii::app()->end();
                }
            }
            $bkgArr                  = array_filter($_POST['Booking']);
            $model->attributes       = $bkgArr;
            $sltId                   = $model->bkg_shuttle_id;
            $model->bkg_booking_type = ($model->bkg_booking_type == '') ? Yii::app()->request->getParam('bookingType') : $model->bkg_booking_type;

            $shuttle        = Shuttle::model()->getDetailbyId($sltId);
            $model->preData = ['preBookData' => ['bkg_shuttle_id' => $sltId, 'bkg_booking_type' => $model->bkg_booking_type]];
            $this->render('spot16', ['model' => $model, 'shuttle' => $shuttle]);

            Yii::app()->end();
        }
        if ($step == 17 || isset($_POST['step18ToStep17']))
        {
            if (isset($_POST['Booking']) && isset($_POST['BookingUser']) && isset($_POST['step17submit']))
            {
                $bkgArr                     = array_filter($_POST['Booking']);
                $bkgUserArr                 = array_filter($_POST['BookingUser']);
                $contactRadio               = Yii::app()->request->getParam('contact_rad');
                $bkgUserArr['contactRadio'] = $contactRadio;
                $model->scenario            = 'spotShuttle';
                $model->attributes          = $bkgArr;
                $preData                    = CJSON::decode($_POST['Booking']['preData']);
                $no_of_seats                = $preData['preBookData']['bkg_no_of_seats'];
                $shuttleId                  = $preData['preBookData']['bkg_shuttle_id'];
                $totalBookingAmount         = Shuttle::model()->calculateTotalAmount($no_of_seats, $shuttleId);

                //$isRechargeAccount = AccountTransDetails::model()->checkCreditLimit($model->bkg_agent_id, '', '', $totalBookingAmount, '', 3, false);
                //if ($isRechargeAccount)
                //{
                //$isRechargeAccount = 1;
                //}
                //else
                //{
                //$isRechargeAccount = 0;
                //}
                $errorsArr = BookingUser::model()->validateShuttleData($bkgUserArr);
                $success   = true;
                if (count($errorsArr) > 0)
                {
                    $success = false;
                }
                if ($model->validate() && $success)
                {
                    $model->preData = ['preBookData' => $preData['preBookData'], 'userInfo' => $bkgUserArr];
                    //$this->render('spot18', ['model' => $model, 'bkgInvoice' => $bkgInvoice, 'isRechargeAccount' => $isRechargeAccount, 'bkg_total_amount' => $totalBookingAmount]);
                    $this->render('spot18', ['model' => $model, 'bkgInvoice' => $bkgInvoice, 'bkg_total_amount' => $totalBookingAmount]);
                    Yii::app()->end();
                }
            }
            $bkgArr     = array_filter($_POST['Booking']);
            $bkgUserArr = array_filter($_POST['BookingUser']);
            $preData    = CJSON::decode($_POST['Booking']['preData']);

            $model->attributes = $bkgArr;
            $model->preData    = ['preBookData' => $preData['preBookData']];
            if (isset($_POST['BookingUser']))
            {
                $model->preData = ['preBookData' => $preData['preBookData'], 'userInfo' => $bkgUserArr];
            }
            $model->bkg_shuttle_id   = $preData['preBookData']['bkg_shuttle_id'];
            $model->bkg_booking_type = ($model->bkg_booking_type == '') ? Yii::app()->request->getParam('bookingType') : $model->bkg_booking_type;
            $this->render('spot17', ['model' => $model, 'bookingUser' => $bkgUser, 'errorsArr' => $errorsArr, 'contactRadio' => $contactRadio]);
            Yii::app()->end();
        }

        if ($step == 18)
        {
            if (isset($_POST['Booking']) && ($_POST['payBy'] == 2 || $_POST['payBy'] == 1))
            {
                $model->attributes      = array_filter($_POST['Booking']);
                $preData                = CJSON::decode($_POST['Booking']['preData']);
                $model->attributes      = array_filter($preData['preBookData']);
                $model->bkg_pickup_date = $preData['preBookData']['bkg_pickup_date'];

                $agentid     = Yii::app()->user->getAgentId();
                $scount      = count($preData['userInfo']['bkg_user_fname']);
                $bkgIds      = [];
                $totAmount   = 0;
                $slt_id      = $preData['preBookData']['bkg_shuttle_id'];
                $transaction = DBUtil::beginTransaction();
                try
                {
                    for ($s = 0; $s < $scount; $s++)
                    {
                        $bkgModel                   = clone $model;
                        $bkgUser1                   = new BookingUser();
                        $bkgUser1->bkg_user_fname   = $preData['userInfo']['bkg_user_fname'][$s];
                        $bkgUser1->bkg_user_lname   = $preData['userInfo']['bkg_user_lname'][$s];
                        $bkgUser1->bkg_country_code = $preData['userInfo']['bkg_country_code'][$s];
                        $bkgUser1->bkg_contact_no   = (trim($preData['userInfo']['bkg_contact_no'][$s]) == '') ? $preData['userInfo']['bkg_contact_no'][0] : $preData['userInfo']['bkg_contact_no'][$s];
                        $bkgUser1->bkg_user_email   = (trim($preData['userInfo']['bkg_user_email'][$s]) == '') ? $preData['userInfo']['bkg_user_email'][0] : $preData['userInfo']['bkg_user_email'][$s];

                        $bkgModel->bkgUserInfo = $bkgUser1;
                        $platform              = Booking::Platform_Partner_Spot;
                        $newModel              = Booking::model()->createShuttle($bkgModel, $slt_id, $platform, $agentid);
                        $bkgIds[]              = $newModel->bkg_booking_id;
                        $totAmount             += $newModel->bkgInvoice->bkg_total_amount;
                    }
                    $resultSet = Shuttle::model()->getAvailableSeatbyId($slt_id);

                    if ($resultSet['available_seat'] >= 0)
                    {
                        DBUtil::commitTransaction($transaction);
                        $this->render('spot19', ['model' => $model, 'bkgInvoice' => $bkgInvoice, 'bkgIds' => $bkgIds]);
                        Yii::app()->end();
                    }
                    else
                    {
                        DBUtil::rollbackTransaction($transaction);
                    }
                }
                catch (Exception $e)
                {
                    $model->addError('bkg_id', $e->getMessage());
                    Logger::create("Error log: ", json_encode($e->getMessage()), CLogger::LEVEL_TRACE);
                    DBUtil::rollbackTransaction($transaction);
                }
            }
            $no_of_seats        = $preData['preBookData']['bkg_no_of_seats'];
            $shuttleId          = $preData['preBookData']['bkg_shuttle_id'];
            $totalBookingAmount = Shuttle::model()->calculateTotalAmount($no_of_seats, $shuttleId);
//			$isRechargeAccount	 = AccountTransDetails::model()->checkCreditLimit($model->bkg_agent_id, '', '', $totalBookingAmount, '', 3, false);
//			if ($isRechargeAccount)
//			{
//				$isRechargeAccount = 1;
//			}
//			else
//			{
//				$isRechargeAccount = 0;
//			}
            $model->preData     = ['preBookData' => $preData['preBookData'], 'userInfo' => $preData['userInfo']];
            //$this->render('spot18', ['model' => $model, 'bkgInvoice' => $bkgInvoice, 'isRechargeAccount' => $isRechargeAccount, 'bkg_total_amount' => $totalBookingAmount]);
            $this->render('spot18', ['model' => $model, 'bkgInvoice' => $bkgInvoice, 'bkg_total_amount' => $totalBookingAmount]);
            Yii::app()->end();
        }


        if ($step == 5 || isset($_POST['step6ToStep5']))
        {

            if (isset($_POST['Booking']) && isset($_POST['step5submit']))
            {
                $model->scenario   = 'spotStep5';
                $preData           = CJSON::decode($_POST['Booking']['preData']);
                $model->attributes = array_filter($preData['preBookData']);
                $model->preData    = array_filter($preData);
                if ($model->bkg_from_city_id != $_POST['Booking']['bkg_from_city_id'])
                {
                    $model->bkg_pickup_address = '';
                }
                if ($model->bkg_to_city_id != $_POST['Booking']['bkg_to_city_id'])
                {
                    $model->bkg_drop_address = '';
                }
                $model->attributes = $_POST['Booking'];
                if ($model->validate())
                {
                    if ($model->bkg_booking_type == 1)
                    {
                        $bookingRoutes                  = [];
                        $bookingRoute                   = new BookingRoute();
                        $bookingRoute->with('brtFromCity,brtToCity');
                        $bookingRoute->brt_from_city_id = $model->bkg_from_city_id;
                        $bookingRoute->brt_to_city_id   = $model->bkg_to_city_id;
                        $bookingRoutes[]                = array_filter($bookingRoute->attributes);
                    }
                    else if (in_array($model->bkg_booking_type, [9, 10, 11]))
                    {
                        $bookingRoutes                  = [];
                        $bookingRoute                   = new BookingRoute();
                        $bookingRoute->with('brtFromCity,brtToCity');
                        $bookingRoute->brt_from_city_id = $model->bkg_from_city_id;
                        $bookingRoute->brt_to_city_id   = $model->bkg_to_city_id;
                        $bookingRoutes[]                = array_filter($bookingRoute->attributes);
                    }
                    else
                    {
                        $bookingRoutes                  = [];
                        $bookingRoute                   = new BookingRoute();
                        $bookingRoute->with('brtFromCity,brtToCity');
                        $bookingRoute->brt_from_city_id = $model->bkg_from_city_id;
                        $bookingRoute->brt_to_city_id   = $model->bkg_to_city_id;
                        $bookingRoutes[]                = array_filter($bookingRoute->attributes);
                        $bookingRoute                   = new BookingRoute();
                        $bookingRoute->with('brtFromCity,brtToCity');
                        $bookingRoute->brt_from_city_id = $model->bkg_to_city_id;
                        $bookingRoute->brt_to_city_id   = $model->bkg_from_city_id;
                        $bookingRoutes[]                = array_filter($bookingRoute->attributes);
                        $model->bkg_to_city_id          = $model->bkg_from_city_id;
                    }

                    $model->preData = ['preBookData' => array_filter($model->attributes), 'preRutData' => $bookingRoutes];
                    $this->render('spot6', ['model' => $model]);
                    Yii::app()->end();
                }
            }
            $model->bkg_booking_type = ($model->bkg_booking_type == '') ? Yii::app()->request->getParam('bookingType') : $model->bkg_booking_type;

            if (isset($_POST['step6ToStep5']))
            {

                $model->attributes = array_filter($_POST['Booking']);
                $preData           = CJSON::decode($_POST['Booking']['preData']);
                $model->attributes = array_filter($preData['preBookData']);
                $model->preData    = array_filter($preData);
            }
            $this->render('spot5', ['model' => $model]);
            Yii::app()->end();
        }

        if ($step == 6 || isset($_POST['step7ToStep6']))
        {


            if (isset($_POST['Booking']) && isset($_POST['step6submit']))
            {

                $model->attributes = array_filter($_POST['Booking']);
                $preData           = CJSON::decode($_POST['Booking']['preData']);
                $model->preData    = CJSON::decode($_POST['Booking']['preData']);
                $preRutData        = $preData['preRutData'];
                $model->attributes = array_filter($preData['preBookData']);
                $model->scenario   = 'spotStep6';

                if ($model->bkg_booking_type == 2)
                {
                    $model->validatorList->add(CValidator::createValidator('required', $model, 'bkg_return_date_date,bkg_return_date_time'));
                    $model->validatorList->add(CValidator::createValidator('validateReturnDate', $model, 'bkg_return_date_date,bkg_return_date_time'));
                }

                if ($model->validate())
                {

                    $model->bkg_pickup_date = DateTimeFormat::DatePickerToDate($model->bkg_pickup_date_date) . " " . DateTime::createFromFormat('h:i A', $model->bkg_pickup_date_time)->format('H:i:00');
                    if ($model->bkg_booking_type == 2)
                    {
                        $model->bkg_return_date = DateTimeFormat::DatePickerToDate($model->bkg_return_date_date) . " " . DateTime::createFromFormat('h:i A', $model->bkg_return_date_time)->format('H:i:00');
                        //$model->bkg_return_time = DateTime::createFromFormat('h:i A', $model->bkg_return_date_time)->format('H:i:00');
                    }

                    $bookingRoutes    = [];
                    $bookingRoutesObj = [];

                    foreach ($preRutData as $key => $route)
                    {
                        $bookingRoute                      = new BookingRoute();
                        $bookingRoute->attributes          = $route;
                        $bookingRoute->with('brtFromCity', 'brtToCity');
                        $bookingRoute->brt_pickup_datetime = $model->bkg_pickup_date;
                        if ($key == 1)
                        {
                            $estimatedPickuptime               = Route::model()->getRoundtripEstimatedPickupbyReturnDateTime($model->bkg_from_city_id, $model->bkg_to_city_id, $model->bkg_return_date);
                            $bookingRoute->brt_pickup_datetime = $estimatedPickuptime;
                        }
                        $bookingRoutes[]    = array_filter($bookingRoute->attributes);
                        $bookingRoutesObj[] = $bookingRoute;
                    }





                    $model->bookingRoutes = $bookingRoutesObj;

                    //$masterVehicles = VehicleTypes::model()->getMasterCarType();
                    $returnType             = 'list';
                    $masterVehicles         = SvcClassVhcCat::model()->getVctSvcList($returnType);
                    $partnerId              = Yii::app()->user->getAgentId();
                    $quote                  = new Quote();
                    $quote->routes          = $bookingRoutesObj;
                    $quote->tripType        = $model->bkg_booking_type;
                    $quote->partnerId       = $partnerId;
                    $quote->quoteDate       = $model->bkg_create_date;
                    $quote->pickupDate      = $model->bkg_pickup_date;
                    $quote->returnDate      = $model->bkg_return_date;
                    $quote->sourceQuotation = Quote::Platform_Partner_Spot;
                    if (!$bookingRoutesObj[0]->checkQuoteSession())
                    {
                        Quote::$updateCounter = true;
                        $bookingRoutesObj[0]->setQuoteSession();
                    }
                    $quote->setCabTypeArr();
                    $arrQuot       = $quote->getQuote();
                    $routeDistance = $quote->routeDistance;
                    $routeDuration = $quote->routeDuration;
                    //$quotData       = $arrQuot['routeData'];					

                    $agtType = Agents::model()->findByPk(Yii::app()->user->getAgentId())->agt_type;
                    foreach ($arrQuot as $k => $v)
                    {
                        $routeRates                            = $v->routeRates;
                        $routeRates->discFare                  = '';
                        //	$routeRates->isNightPickupIncluded ;
                        //$routeRates->isNightDropIncluded ;
                        $bkgInvoice->bkg_night_pickup_included = $routeRates->isNightPickupIncluded;
                        $bkgInvoice->bkg_night_drop_included   = $routeRates->isNightDropIncluded;
                        if ($agtType == 0 || $agtType == 1)
                        {
                            $arrQuote      = Agents::model()->getBaseDiscFare($routeRates, $agtType, Yii::app()->user->getAgentId());
                            $v->routeRates = $arrQuote;
                        }
                        if (!in_array($k, array_keys($masterVehicles)))
                        {
                            unset($arrQuot[$k]);
                        }
                        else
                        {
                            $tempCab[$k] = $routeRates->totalAmount;
                        }
                    }

                    // array_multisort($tempCab, SORT_ASC, $arrQuot);
                    $model->preData = ['preBookData' => array_filter($model->attributes), 'preRutData' => $bookingRoutes];
                    $this->render('spot7', ['model' => $model, 'bkgInvoice' => $bkgInvoice, 'cabratedata' => $arrQuot]);
                    Yii::app()->end();
                }
            }

            if (isset($_POST['step7ToStep6']))
            {
                $model->attributes = array_filter($_POST['Booking']);
                $preData           = CJSON::decode($_POST['Booking']['preData']);
                $model->attributes = array_filter($preData['preBookData']);
                $model->preData    = $preData;
            }

            $this->render('spot6', ['model' => $model]);
            Yii::app()->end();
        }

        if ($step == 7 || isset($_POST['step8ToStep7']))
        {
            $model->scenario = "spotStep7";
            if (isset($_POST['Booking']) && isset($_POST['step7submit']))
            {
                $model->attributes          = array_filter($_POST['Booking']);
                $preData                    = CJSON::decode($_POST['Booking']['preData']);
                $model->preData             = $preData;
                $preRutData                 = $preData['preRutData'];
                $model->attributes          = $preData['preBookData'];
                $model->bkg_pickup_date     = $preData['preBookData']['bkg_pickup_date'];
                $model->bkg_vehicle_type_id = $_POST['Booking']['bkg_vehicle_type_id'];
                $bkgInvoice->attributes     = $preData['preBookData'];
//				$bkgInvoice->bkg_rate_per_km_extra = $_POST['BookingInvoice']['bkg_rate_per_km_extra'];
                $model->attributes          = array_filter($_POST['Booking']);
                if ($model->validate())
                {
                    $bookingRoutes    = [];
                    $bookingRoutesObj = [];

                    foreach ($preRutData as $key => $route)
                    {
                        $bookingRoute             = new BookingRoute();
                        $bookingRoute->attributes = $route;

                        if ($key == 0 && $bookingRoute->brtFromCity->cty_is_airport == 1)
                        {
                            $bookingRoute->brt_from_location        = $bookingRoute->brtFromCity->cty_garage_address;
                            $bookingRoute->brt_from_latitude        = $bookingRoute->brtFromCity->cty_lat;
                            $bookingRoute->brt_from_longitude       = $bookingRoute->brtFromCity->cty_long;
                            $bookingRoute->brt_from_city_is_airport = 1;
                        }
                        if ($bookingRoute->brtToCity->cty_is_airport == 1)
                        {
                            $bookingRoute->brt_to_location        = $bookingRoute->brtToCity->cty_garage_address;
                            $bookingRoute->brt_to_latitude        = $bookingRoute->brtToCity->cty_lat;
                            $bookingRoute->brt_to_longitude       = $bookingRoute->brtToCity->cty_long;
                            $bookingRoute->brt_to_city_is_airport = 1;
                        }



                        $bookingRoute->with('brtFromCity', 'brtToCity');
                        $bookingRoutesObj[] = $bookingRoute;
                    }
                    $model->bookingRoutes = $bookingRoutesObj;
                    $model->preData       = ['preBookData' => array_filter($model->attributes), 'preRutData' => $preRutData];
                    $this->render('spot8', ['model' => $model, 'bkgInvoice' => $bkgInvoice]);
                    Yii::app()->end();
                }
            }

            if (isset($_POST['step8ToStep7']))
            {
                $model->attributes      = array_filter($_POST['Booking']);
                $preData                = CJSON::decode($_POST['Booking']['preData']);
                $preRutData             = $preData['preRutData'];
                $model->attributes      = array_filter($preData['preBookData']);
                $bkgInvoice->attributes = array_filter($preData['preBookData']);
                $model->preData         = $preData;
                $bookingRoutes          = [];
                $bookingRoutesObj       = [];
//                if ($model->validate()) {
                foreach ($preRutData as $key => $route)
                {
                    $bookingRoute                      = new BookingRoute();
                    $bookingRoute->attributes          = $route;
                    $bookingRoute->with('brtFromCity', 'brtToCity');
                    $bookingRoute->brt_pickup_datetime = $model->bkg_pickup_date;
                    $bookingRoutes[]                   = array_filter($bookingRoute->attributes);
                    $bookingRoutesObj[]                = $bookingRoute;
                }
                $model->bookingRoutes = $bookingRoutesObj;
                //$masterVehicles		 = VehicleTypes::model()->getMasterCarType();
                $returnType           = 'list';
                $masterVehicles       = SvcClassVhcCat::model()->getVctSvcList($returnType);

                $partnerId              = Yii::app()->user->getAgentId();
                $quote                  = new Quote();
                $quote->routes          = $bookingRoutesObj;
                $quote->tripType        = $model->bkg_booking_type;
                $quote->partnerId       = $partnerId;
                $quote->quoteDate       = $model->bkg_create_date;
                $quote->pickupDate      = $model->bkg_pickup_date;
                $quote->returnDate      = $model->bkg_return_date;
                $quote->sourceQuotation = Quote::Platform_Partner_Spot;
                $quote->setCabTypeArr();
                $arrQuot                = $quote->getQuote();

                $routeDistance = $quote->routeDistance;
                $routeDuration = $quote->routeDuration;

                $agtType = Agents::model()->findByPk(Yii::app()->user->getAgentId())->agt_type;
                foreach ($arrQuot as $k => $v)
                {
                    $routeRates           = $v->routeRates;
                    $routeRates->discFare = '';
                    if ($agtType == 0 || $agtType == 1)
                    {
                        $arrQuote      = Agents::model()->getBaseDiscFare($routeRates, $agtType, Yii::app()->user->getAgentId());
                        $v->routeRates = $arrQuote;
                    }

                    if (!in_array($k, array_keys($masterVehicles)))
                    {
                        unset($arrQuot[$k]);
                    }
                    else
                    {
                        $tempCab[$k] = $routeRates->totalAmount;
                    }
                }

//                array_multisort($tempCab, SORT_ASC, $arrQuot);
            }
            $this->render('spot7', ['model' => $model, 'bkgInvoice' => $bkgInvoice, 'cabratedata' => $arrQuot, 'quotData' => $quotData]);
            Yii::app()->end();
        }

        if ($step == 8 || isset($_POST['step9ToStep8']))
        {

            if (isset($_POST['Booking']) && isset($_POST['step8submit']))
            {
                $model->attributes         = array_filter($_POST['Booking']);
                $preData                   = CJSON::decode($_POST['Booking']['preData']);
                $preRutData                = $preData['preRutData'];
                $model->preData            = $preData;
                $model->attributes         = array_filter($preData['preBookData']);
                $model->bkg_pickup_date    = $preData['preBookData']['bkg_pickup_date'];
                $fromPlace                 = json_decode($_POST['BookingRoute'][0]['from_place']);
                $toPlace                   = json_decode($_POST['BookingRoute'][0]['to_place']);
                $model->bkg_pickup_address = $fromPlace->address;
                $model->bkg_drop_address   = $toPlace->address;
                $model->bkg_pickup_lat     = $fromPlace->coordinates->latitude;
                $model->bkg_pickup_long    = $fromPlace->coordinates->longitude;
                $model->bkg_dropup_lat     = $toPlace->coordinates->latitude;
                $model->bkg_dropup_long    = $toPlace->coordinates->longitude;
                if ($model->bkg_drop_address == '' && $model->bkg_booking_type == 2)
                {
                    $model->bkg_drop_address = Cities::getName($model->bkg_to_city_id);
                }
                $model->validatorList->add(CValidator::createValidator('required', $model, 'bkg_pickup_address,bkg_drop_address'));
                $brtArr        = $_POST['BookingRoute'];
                $bookingRoutes = [];
                $k             = 0;
                foreach ($preRutData as $k => $v)
                {
                    $fromAdditionalAddress = ltrim(trim($brtArr[$k]['brt_additional_from_address']) . ', ', ', ');
                    $toAdditionalAddress   = ltrim(trim($brtArr[$k + 1]['brt_additional_to_address']) . ', ', ', ');
                    $bookingRoute          = new BookingRoute();
                    $brtFromLoc            = json_decode($brtArr[$k]['from_place']);
                    $brtToLoc              = json_decode($brtArr[$k]['to_place']);

                    $bookingRoute->attributes         = $v;
                    $bookingRoute->brt_from_location  = $fromAdditionalAddress . $brtFromLoc->address;
                    $bookingRoute->brt_to_location    = $toAdditionalAddress . $brtToLoc->address;
                    $bookingRoute->brt_from_latitude  = round($brtFromLoc->coordinates->latitude, 6);
                    $bookingRoute->brt_from_longitude = round($brtFromLoc->coordinates->longitude, 6);
                    $bookingRoute->brt_to_latitude    = round($brtToLoc->coordinates->latitude, 6);
                    $bookingRoute->brt_to_longitude   = round($brtToLoc->coordinates->longitude, 6);
                    if ($k > 0)
                    {
                        $bookingRoute->brt_from_location  = $bookingRoutes[$k - 1]['brt_to_location'];
                        $bookingRoute->brt_from_latitude  = $bookingRoutes[$k - 1]['brt_to_latitude'];
                        $bookingRoute->brt_from_longitude = $bookingRoutes[$k - 1]['brt_to_longitude'];
                    }
                    $bookingRoutes[] = array_filter($bookingRoute->attributes);
                }
                $model->pickupLat          = $fromPlace->coordinates->latitude;
                $model->pickupLon          = $fromPlace->coordinates->longitude;
                $model->dropLat            = $toPlace->coordinates->latitude;
                $model->dropLon            = $toPlace->coordinates->longitude;
                $model->bkg_pickup_address = $fromPlace->address;
                $model->bkg_drop_address   = $toPlace->address;

                $model->scenario = 'cabRateAgt';
                if ($model->validate())
                {
                    //$bookingRoutes = [];
                    foreach ($preRutData as $key => $route)
                    {
                        $bookingRoute                    = new BookingRoute();
                        $bookingRoute->attributes        = $route;
                        $bookingRoute->brt_from_location = $fromPlace->address;
                        $bookingRoute->brt_to_location   = $toPlace->address;
                        if ($key == 1)
                        {
                            $bookingRoute->brt_from_location = $_POST['BookingRoute'][1]['brt_to_location'];
                            $bookingRoute->brt_to_location   = $_POST['BookingRoute'][2]['brt_to_location'];
                        }
                        //	$bookingRoutes[] = array_filter($bookingRoute->attributes);
                    }
                    $model->preData = ['preBookData' => array_filter($model->attributes), 'preRutData' => $bookingRoutes];
                    $this->render('spot9', ['model' => $model, 'bookingUser' => $bkgUser]);
                    Yii::app()->end();
                }
                else
                {
                    $step8Error = true;
                }
            }
            if (isset($_POST['step9ToStep8']) || $step8Error)
            {
                $model->attributes      = array_filter($_POST['Booking']);
                $preData                = CJSON::decode($_POST['Booking']['preData']);
                $preRutData             = $preData['preRutData'];
                $model->attributes      = array_filter($preData['preBookData']);
                $model->bkg_pickup_date = $preData['preBookData']['bkg_pickup_date'];
                $model->preData         = $preData;
                $bookingRoutes          = [];
                $bookingRoutesObj       = [];
                foreach ($preRutData as $key => $route)
                {
                    $bookingRoute             = new BookingRoute();
                    $bookingRoute->attributes = $route;
                    $bookingRoute->with('brtFromCity', 'brtToCity');
                    $bookingRoutesObj[]       = $bookingRoute;
                }
                $model->bookingRoutes = $bookingRoutesObj;
            }
            $this->render('spot8', ['model' => $model]);
            Yii::app()->end();
        }

        if ($step == 9 || isset($_POST['step10ToStep9']))
        {
            //NEXT btn
            if (isset($_POST['Booking']) && isset($_POST['BookingUser']) && isset($_POST['step9submit']))
            {
                $preData                = CJSON::decode($_POST['Booking']['preData']);
                $preRutData             = $preData['preRutData'];
                $model->attributes      = array_filter($preData['preBookData']);
                $model->bkg_pickup_date = $preData['preBookData']['bkg_pickup_date'];
                $model->attributes      = array_filter($_POST['Booking']);
                $bkgUser->attributes    = array_filter($_POST['BookingUser']);
                if ($model->validate())
                {
                    $model->preData = ['preBookData' => array_merge(array_filter($model->attributes), $bkgUser->attributes), 'preRutData' => array_filter($preRutData)];
                    foreach ($preRutData as $key => $route)
                    {
                        $bookingRoute             = new BookingRoute();
                        $bookingRoute->attributes = $route;
                        $bookingRoute->with('brtFromCity', 'brtToCity');
                        $bookingRoutesObj[]       = $bookingRoute;
                    }
                    //$carType = VehicleTypes::model()->getVehicleTypeById($model->bkg_vehicle_type_id);
                    $carType                        = $model->bkg_vehicle_type_id;
                    $quote                          = new Quote();
                    $quote->routes                  = $bookingRoutesObj;
                    $quote->tripType                = $model->bkg_booking_type;
                    $quote->partnerId               = $partnerId;
                    $quote->quoteDate               = $model->bkg_create_date;
                    $quote->pickupDate              = $model->bkg_pickup_date;
                    $quote->returnDate              = $model->bkg_return_date;
                    $quote->isDynamicSurgeAvialable = DynamicPriceAvailability::Available;
                    $quote->sourceQuotation         = Quote::Platform_Partner_Spot;
                    $quote->setCabTypeArr();
                    $qt                             = $quote->getQuote($carType);

                    $quoteData           = $qt[$carType];
                    $routeRates          = $quoteData->routeRates;
                    /*
                     * @var $routeRates RouteRates                      */
                    $model->bkg_agent_id = Yii::app()->user->getAgentId();
                    $agtModel            = Agents::model()->findByPk($model->bkg_agent_id);
                    $arrQuote            = Agents::model()->getBaseDiscFare($quoteData->routeRates, $agtModel->agt_type, $model->bkg_agent_id);

                    $routeRates                              = $arrQuote; //$quoteData->routeRates;
                    $routeDistance                           = $quoteData->routeDistance;
                    $routeDuration                           = $quoteData->routeDuration;
                    $amount                                  = $routeRates->totalAmount - $routeRates->vendorAmount;
                    $bkgInvoice->bkg_gozo_base_amount        = $amount;
                    $bkgInvoice->bkg_base_amount             = $routeRates->baseAmount;
                    $model->bkg_trip_distance                = $routeDistance->tripDistance; // $routeData['minimumChargeableDistance'];
                    $model->bkg_trip_duration                = $routeDuration->totalMinutes;
                    $bkgInvoice->bkg_driver_allowance_amount = $routeRates->driverAllowance;
                    $bkgInvoice->bkg_chargeable_distance     = $routeDistance->quotedDistance;
                    $bkgTrack->bkg_garage_time               = $routeDuration->totalMinutes + $routeDuration->garageTimeEnd + $routeDuration->garageTimeStart; //  $cabData['totalGarage'];
                    $bkgInvoice->bkg_vendor_amount           = round($routeRates->vendorAmount);
                    $bkgInvoice->bkg_is_toll_tax_included    = $routeRates->isTollIncluded; //$cabData['tolltax'];
                    $bkgInvoice->bkg_is_state_tax_included   = $routeRates->isStateTaxIncluded; //$cabData['statetax'];
                    $bkgInvoice->bkg_toll_tax                = $routeRates->tollTaxAmount; //$cabData['toll_tax'];
                    $bkgInvoice->bkg_state_tax               = $routeRates->stateTax; //$cabData['state_tax'];
                    $bkgInvoice->bkg_quoted_vendor_amount    = round($routeRates->vendorAmount);
                    if ($bkgAddInfo->bkg_spl_req_carrier == 1)
                    {
                        $bkgInvoice->bkg_additional_charge        = 150;
                        $bkgInvoice->bkg_additional_charge_remark = 'Carrier Requested for Rs.150';
                    }
                    else
                    {
                        $bkgInvoice->bkg_additional_charge        = 0;
                        $bkgInvoice->bkg_additional_charge_remark = '';
                    }
                    if ($model->bkg_agent_id != '')
                    {
                        $agtModel = Agents::model()->findByPk($model->bkg_agent_id);
                        if ($agtModel->agt_city == 30706)
                        {
                            $bkgInvoice->bkg_cgst = Yii::app()->params['cgst'];
                            $bkgInvoice->bkg_sgst = Yii::app()->params['sgst'];
                            $bkgInvoice->bkg_igst = 0;
                        }
                        else
                        {
                            $bkgInvoice->bkg_igst = Yii::app()->params['igst'];
                            $bkgInvoice->bkg_cgst = 0;
                            $bkgInvoice->bkg_sgst = 0;
                        }
                    }
                    if ($amount > 0)
                    {
                        $bkgInvoice->calculateConvenienceFee(0);
                        $bkgInvoice->populateAmount(true, false, true, true, $model->bkg_agent_id);
                    }

//					$isRechargeAccount = AccountTransDetails::model()->checkCreditLimit($model->bkg_agent_id, '', '', $bkgInvoice->bkg_total_amount, '', 3, false);
//					if ($isRechargeAccount)
//					{
//						$isRechargeAccount = 1;
//					}
//					else
//					{
//						$isRechargeAccount = 0;
//					}
                    //$this->render('spot10', ['model' => $model, 'bkgInvoice' => $bkgInvoice, 'isRechargeAccount' => $isRechargeAccount]);
                    $this->render('spot10', ['model' => $model, 'bkgInvoice' => $bkgInvoice]);
                    Yii::app()->end();
                }
            }
            //PREV btn
            if (isset($_POST['step10ToStep9']))
            {
                $model->attributes   = array_filter($_POST['Booking']);
                $preData             = CJSON::decode($_POST['Booking']['preData']);
                $model->attributes   = array_filter($preData['preBookData']);
                $model->preData      = $preData;
                $bkgUser->attributes = array_filter($preData['preBookData']);
            }
            $this->render('spot9', ['model' => $model, 'bookingUser' => $bkgUser]);
            Yii::app()->end();
        }

        if ($step == 10)
        {
            //if(!empty($_POST)){ echo '<pre>';print_r($_POST);exit(); }

            if (isset($_POST['Booking']) && ($_POST['payBy'] == 2 || $_POST['payBy'] == 1))
            {
                $model->attributes      = array_filter($_POST['Booking']);
                $preData                = CJSON::decode($_POST['Booking']['preData']);
                $preRutData             = $preData['preRutData'];
                $model->attributes      = array_filter($preData['preBookData']);
                $model->bkg_pickup_date = $preData['preBookData']['bkg_pickup_date'];
                $bkgUser->attributes    = array_filter($preData['preBookData']);
                $model->bkg_agent_id    = Yii::app()->user->getAgentId();

                foreach ($preRutData as $key => $route)
                {
                    $bookingRoute             = new BookingRoute();
                    $bookingRoute->attributes = $route;
                    $bookingRoute->with('brtFromCity', 'brtToCity');
                    $bookingRoutesObj[]       = $bookingRoute;
                }
                //$carType = VehicleTypes::model()->getVehicleTypeById($model->bkg_vehicle_type_id);
                $carType                        = $model->bkg_vehicle_type_id;
                $quote                          = new Quote();
                $quote->routes                  = $bookingRoutesObj;
                $quote->tripType                = $model->bkg_booking_type;
                $quote->partnerId               = $partnerId;
                $quote->quoteDate               = $model->bkg_create_date;
                $quote->pickupDate              = $model->bkg_pickup_date;
                $quote->returnDate              = $model->bkg_return_date;
                $quote->sourceQuotation         = Quote::Platform_Partner_Spot;
                $quote->isDynamicSurgeAvialable = DynamicPriceAvailability::Available;
                $quote->isB2Cbooking            = false;
                $quote->setCabTypeArr();
                $qt                             = $quote->getQuote($carType);

                $quoteData  = $qt[$carType];
                $routeRates = $quoteData->routeRates;

                if ($_POST['payBy'] == 1)
                {
                    $agentId          = Yii::app()->user->getAgentId();
                    $availableBalance = Agents::getAvailableLimit($agentId);
                    $getBalance       = PartnerStats::getBalance($agentId);
                    $walletBalance    = $getBalance['pts_wallet_balance'];

                    $corpCredit = $routeRates->totalAmount;

                    if ($walletBalance >= $corpCredit)
                    {
                        goto skipIssue;
                    }

                    $pickupTimeDiffMinutes = Filter::getTimeDiff($model->bkg_pickup_date, Filter::getDBDateTime());
                    if ($pickupTimeDiffMinutes > 720)
                    {
                        goto skipIssue;
                    }
                    $credits = $corpCredit - $walletBalance;
                    if ($credits > $availableBalance)
                    {
                        $isRechargeAccount = 1;
                        goto skipProcess;
                    }
                    $walletBalance += $credits;

                    skipIssue:
                }


                $bookingRoutesObj        = $quote->routes;
                $firstRoute              = $bookingRoutesObj[0];
                $lastRoute               = $bookingRoutesObj[(count($bookingRoutesObj) - 1)];
                $model->bkg_from_city_id = $firstRoute['brt_from_city_id'];
                $model->bkg_to_city_id   = $lastRoute['brt_to_city_id'];

                $model->bkg_agent_id = Yii::app()->user->getAgentId();
                $agtModel            = Agents::model()->findByPk($model->bkg_agent_id);
                $arrQuote            = Agents::model()->getBaseDiscFare($quoteData->routeRates, $agtModel->agt_type, $model->bkg_agent_id);

                $routeRates                              = $arrQuote; //$quoteData->routeRates;
                $routeDistance                           = $quoteData->routeDistance;
                $routeDuration                           = $quoteData->routeDuration;
                $amount                                  = $routeRates->baseAmount;
                $bkgInvoice->bkg_gozo_base_amount        = $amount;
                $bkgInvoice->bkg_base_amount             = $routeRates->baseAmount;
                $model->bkg_trip_distance                = $routeDistance->tripDistance; // $routeData['minimumChargeableDistance'];
                $model->bkg_trip_duration                = $routeDuration->totalMinutes;
                $bkgInvoice->bkg_driver_allowance_amount = $routeRates->driverAllowance;
                $bkgInvoice->bkg_chargeable_distance     = $routeDistance->quotedDistance;
                $bkgTrack->bkg_garage_time               = $routeDuration->totalMinutes + $routeDuration->garageTimeEnd + $routeDuration->garageTimeStart;
                //  $cabData['totalGarage'];
                $bkgInvoice->bkg_vendor_amount           = round($routeRates->vendorAmount);
                $bkgInvoice->bkg_is_toll_tax_included    = $routeRates->isTollIncluded | 0; //$cabData['tolltax'];
                $bkgInvoice->bkg_is_state_tax_included   = $routeRates->isStateTaxIncluded | 0; //$cabData['statetax'];
                $bkgInvoice->bkg_toll_tax                = $routeRates->tollTaxAmount; //$cabData['toll_tax'];
                $bkgInvoice->bkg_state_tax               = $routeRates->stateTax; //$cabData['state_tax'];
                $bkgInvoice->bkg_quoted_vendor_amount    = round($routeRates->vendorAmount);

                $bkgInvoice->bkg_surge_differentiate_amount = $routeRates->differentiateSurgeAmount;

                if ($bkgAddInfo->bkg_spl_req_carrier == 1)
                {
                    $bkgInvoice->bkg_additional_charge        = 150;
                    $bkgInvoice->bkg_additional_charge_remark = 'Carrier Requested for Rs.150';
                }
                else
                {
                    $bkgInvoice->bkg_additional_charge        = 0;
                    $bkgInvoice->bkg_additional_charge_remark = '';
                }
                if (!$bkgPref->bkg_cancel_rule_id)
                {
                    $svcModelCat                 = SvcClassVhcCat::model()->getVctSvcList('object', 0, 0, $model->bkg_vehicle_type_id);
                    $cancelRuleId                = CancellationPolicy::getCancelRuleId($model->bkg_agent_id, $svcModelCat->scv_id, $model->bkg_from_city_id, $model->bkg_to_city_id, $model->bkg_booking_type);
                    $bkgPref->bkg_cancel_rule_id = $cancelRuleId;
                }
                $bkgPref->bkg_send_email        = 1;
                $bkgPref->bkg_send_sms          = 1;
                $bkgPref->bkg_trip_otp_required = $agtModel->agt_otp_required;

                if ($model->bkg_agent_id != '')
                {
                    $agtModel = Agents::model()->findByPk($model->bkg_agent_id);
                    if ($agtModel->agt_city == 30706)
                    {
                        $bkgInvoice->bkg_cgst = Yii::app()->params['cgst'];
                        $bkgInvoice->bkg_sgst = Yii::app()->params['sgst'];
                        $bkgInvoice->bkg_igst = 0;
                    }
                    else
                    {
                        $bkgInvoice->bkg_igst = Yii::app()->params['igst'];
                        $bkgInvoice->bkg_cgst = 0;
                        $bkgInvoice->bkg_sgst = 0;
                    }
                }
                if ($amount > 0)
                {
                    $bkgInvoice->calculateConvenienceFee(0);
                    $bkgInvoice->populateAmount(true, false, true, true, $model->bkg_agent_id);
                }
                //validate recharge amount
                //$isRechargeAccount = AccountTransDetails::model()->checkCreditLimit($model->bkg_agent_id, '', '', $bkgInvoice->bkg_total_amount, '', 3, false);
                //if ($isRechargeAccount && $_POST['payBy'] == 1 && ( $_POST['rechargeAmount'] < $bkgInvoice->bkg_total_amount || !isset($_POST['rechargeMethod']) ))
//				if ($_POST['payBy'] == 1 && ( $_POST['rechargeAmount'] < $bkgInvoice->bkg_total_amount || !isset($_POST['rechargeMethod']) ))
//				{
//					Yii::app()->end();
//				}
                $bkgUser->bkg_user_last_updated_on = new CDbExpression('NOW()');
                $user_id                           = (Yii::app()->user->getId() > 0) ? Yii::app()->user->getId() : '';
                if ($user_id == '')
                {
                    $userModel = Users::model()->linkUserByEmail($model->bkg_id, Booking::Platform_Agent);
                    if ($userModel)
                    {
                        $user_id = $userModel->user_id;
                    }
                }
                if ($user_id)
                {
                    $bkgUser->bkg_user_id = $user_id;
                }
                $bkgTrail->bkg_platform = Booking::Platform_Partner_Spot;
                if ($bkgAddInfo->bkg_info_source == '')
                {
                    $bkgAddInfo->bkg_info_source = 6;
                }

                $bkgTrail->bkg_user_ip = \Filter::getUserIP();
                $cityinfo              = UserLog::model()->getCitynCountrycodefromIP(\Filter::getUserIP());
                if ($cityinfo)
                {
                    $bkgUser->bkg_user_city    = $cityinfo['city'];
                    $bkgUser->bkg_user_country = $cityinfo['country'];
                }
                $bkgTrail->bkg_user_device = UserLog::model()->getDevice();
                $bkgTrail->setPaymentExpiryTime($model->bkg_pickup_date);
                $model->scenario           = 'cabRate';
                $bkgUser->save();
                if ($model->validate() && !$model->hasErrors())
                {
                    $transaction = DBUtil::beginTransaction();
                    try
                    {
                        // $bkgAddInfo->bkg_no_person         = 2;
                        $bkgUser->bkg_user_last_updated_on = new CDbExpression('NOW()');
                        $tmodel                            = Terms::model()->getText(1);
                        $bkgTrail->bkg_tnc_id              = $tmodel->tnc_id;
                        $bkgTrail->bkg_tnc_time            = new CDbExpression('NOW()');
                        $isRealtedBooking                  = $model->findRelatedBooking($model->bkg_id);
                        $model->bkg_status                 = 1;
                        $model->bkg_booking_id             = 'temp';
                        $bkgTrail->bkg_is_related_booking  = ($isRealtedBooking) ? 1 : 0;
                        $userInfo                          = UserInfo::getInstance();
                        $bkgTrail->bkg_create_user_type    = $userInfo->userType;
                        $bkgTrail->bkg_create_user_id      = $userInfo->userId;
                        $bkgTrail->bkg_create_type         = BookingTrail::CreateType_Self;
                        if (!$model->save())
                        {
                            throw new Exception("Failed to create booking", 101);
                        }

                        $bkgPf->bpf_bkg_id      = $model->bkg_id;
                        $bkgAddInfo->bad_bkg_id = $model->bkg_id;
                        $bkgInvoice->biv_bkg_id = $model->bkg_id;
                        $bkgPref->bpr_bkg_id    = $model->bkg_id;
                        $bkgTrack->btk_bkg_id   = $model->bkg_id;
                        $bkgTrail->btr_bkg_id   = $model->bkg_id;
                        $bkgUser->bui_bkg_id    = $model->bkg_id;
                        if (SvcClassVhcCat::getClassById($model->bkg_vehicle_type_id) == ServiceClass::CLASS_VALUE_CNG)
                        {
                            $bkgPref->bkg_cng_allowed = 1;
                        }
                        $bkgAddInfo->save();
                        $bkgInvoice->save();
                        $bkgPref->save();
                        $bkgPf->save();
                        $bkgPf->updateFromQuote($quoteData);
                        $bkgTrack->save();
                        $bkgTrail->save();
                        //Create traveller contact
                        $contactId = Contact::createbyBookingUser($bkgUser);
                        if ($contactId)
                        {
                            $bkgUser->bkg_contact_id = $contactId;
                        }
                        $bkgUser->save();
                        foreach ($bookingRoutesObj as $k => $t)
                        {
                            $t->scenario   = "asd";
                            $t->attributes = $t;
                            $t->brt_bkg_id = $model->bkg_id;
                            $t->save();
                        }
                        //	$bookingPref = BookingPref::model()->getByBooking($model->bkg_id);
                        if ($bkgPref == '')
                        {
                            $bkgPref             = new BookingPref();
                            $bkgPref->bpr_bkg_id = $model->bkg_id;
                        }
                        //Update partner commission and gozoamount
                        $bkgInvoice->refresh();
                        $bkgInvoice->calculateDues();
                        $bkgInvoice->save();

                        $bkgUser->bkg_crp_name         = $agtModel->agt_copybooking_name;
                        $bkgPref->bkg_crp_send_email   = 1;
                        $bkgPref->bkg_crp_send_sms     = 1;
                        $bkgUser->bkg_crp_email        = $agtModel->agt_copybooking_email;
                        $bkgUser->bkg_crp_phone        = $agtModel->agt_copybooking_phone;
                        $bkgUser->bkg_crp_country_code = $agtModel->agt_phone_country_code;
                        $bkgUser->save();
                        $bkgPref->save();
                        $arrEvents                     = AgentMessages::getEvents();
                        foreach ($arrEvents as $key => $value)
                        {
                            $bookingMessages = BookingMessages::model()->getByEventAndBookingId($model->bkg_id, $key);
                            if ($bookingMessages == '')
                            {
                                $bookingMessages = new BookingMessages();
                                $bookingMessages->getMessageDefaults($model->bkg_agent_id, $key);
                                if ($_POST['payBy'] == 2 && $model->bkg_status == 1)
                                {
                                    if ($key == AgentMessages::BOOKING_CONF_WITH_PAYMENTINFO || $key == AgentMessages::PAYMENT_CONFIRM || $key == AgentMessages::PAYMENT_FAILED || $key == AgentMessages::INVOICE || $key == AgentMessages::RATING_AND_REVIEWS || $key == AgentMessages::CANCEL_TRIP)
                                    {
                                        $bookingMessages->bkg_agent_email    = 1;
                                        $bookingMessages->bkg_agent_sms      = 1;
                                        $bookingMessages->bkg_agent_whatsapp = 1;
                                        $bookingMessages->bkg_trvl_email     = 1;
                                        $bookingMessages->bkg_trvl_sms       = 1;
                                        $bookingMessages->bkg_trvl_whatsapp  = 1;
                                    }
                                    if ($key == AgentMessages::BOOKING_CONF_WITHOUT_PAYMENTINFO || $key == AgentMessages::CAB_ASSIGNED)
                                    {
                                        $bookingMessages->bkg_trvl_email    = 0;
                                        $bookingMessages->bkg_trvl_sms      = 0;
                                        $bookingMessages->bkg_trvl_whatsapp = 0;
                                    }
                                }
                                $bookingMessages->bkg_booking_id = $model->bkg_id;
                                $bookingMessages->bkg_event_id   = $key;
                                $bookingMessages->save();
                            }
                        }
                        $bookingCab = $model->getBookingCabModel();
                        BookingsDataCreated::model()->setData($model->bkg_id);
                        if ($bookingCab == '')
                        {
                            $bookingCab = new BookingCab();
                        }
                        $bookingCab->bcb_vendor_amount = $bkgInvoice->bkg_vendor_amount;
                        $bookingCab->bcb_bkg_id1       = $model->bkg_id;
                        $bookingCab->save();
                        BookingRoute::model()->setBookingCabStartEndTime($bookingCab->bcb_id, $model->bkg_id);
                        $model->refresh();
                        $model->bkg_bcb_id             = $bookingCab->bcb_id;
                        $booking_id                    = $model->generateBookingid($model);
                        $model->bkg_booking_id         = $booking_id;
                        $bkgBookingUser                = BookingUser::model()->saveVerificationOtp($model->bkg_id);
                        $model->bkgUserInfo            = $bkgBookingUser;
                        $model->save();
                        $bkgTrack                      = BookingTrack::model()->sendTripOtp($model->bkg_id, false);
                        $bkgTrack->save();
                        $processedRoute                = BookingLog::model()->logRouteProcessed($quoteData, $model->bkg_id);
                        $desc                          = "Booking created by Agent - $processedRoute";
                        $eventid                       = BookingLog::BOOKING_CREATED;
                        BookingLog::model()->createLog($model->bkg_id, $desc, $userInfo, $eventid);
                        BookingPriceFactor::model()->getQuotedFactor($model->bkg_id);
                        $bookingRoute->clearQuoteSession();

                        //if ($_POST['payBy'] == 1 && !$isRechargeAccount && $model->bkg_status == 1)
                        if ($_POST['payBy'] == 1 && $model->bkg_status == 1)
                        {


                            $bkgInvoice->bkg_corporate_remunerator = 2;
                            $amount                                = $bkgInvoice->bkg_total_amount | 0;
                            $bkgInvoice->save();
                            if ($model->bkg_agent_id != '')
                            {
                                $agtModel = Agents::model()->findByPk($model->bkg_agent_id);
                                if ($agtModel->agt_approved == 1)
                                {
                                    $model->with('bkgPref', 'bkgAgent', 'bkgUserInfo', 'bkgInvoice', 'bkgAddInfo', 'bkgTrack', 'bkgTrail')->updateAdvance($amount, $model->bkg_pickup_date, PaymentType::TYPE_AGENT_CORP_CREDIT, UserInfo::getInstance(), null, "Partner Wallet Used");
                                    $model->confirm(true, true, $model->bkg_id, $userInfo  = null, $isAllowed = true);
                                }
                            }
                            /* 		
                              $model->bkg_status				 = 2;
                              $model->bkg_reconfirm_flag		 = 1;
                              $model->save();
                              $bkgTrail->bkg_confirm_user_type = $userInfo->userType;
                              $bkgTrail->bkg_confirm_user_id	 = $userInfo->userId;
                              $bkgTrail->bkg_confirm_datetime	 = new CDbExpression('NOW()');
                              $bkgTrail->bkg_confirm_type		 = BookingTrail::ConfirmType_Self;
                              $bkgTrail->save();
                              if ($model->bkg_status == 2)
                              {
                              $emailCom	 = new emailWrapper();
                              $emailCom->gotBookingemail($model->bkg_id, UserInfo::TYPE_SYSTEM, $model->bkg_agent_id);
                              $emailCom->gotBookingAgentUser($model->bkg_id);
                              $msgCom		 = new smsWrapper();
                              $msgCom->gotBooking($model, UserInfo::TYPE_SYSTEM);
                              } */
                        }



                        DBUtil::commitTransaction($transaction);

                        if ($_POST['payBy'] == 2 && $model->bkg_status == 1)
                        {
                            $isAlready2Sms = SmsLog::model()->getCountVerifySms($model->bkg_id);
                            if ($isAlready2Sms <= 2)
                            {
                                $bkgUser->sendVerificationCode(10, true);
                            }
                        }
                        //if ($isRechargeAccount && $_POST['payBy'] == 1 && $_POST['rechargeAmount'] >= $bkgInvoice->bkg_total_amount && $_POST['rechargeMethod'] == 1)
                        if ($_POST['payBy'] == 1 && $_POST['rechargeAmount'] >= $bkgInvoice->bkg_total_amount && $_POST['rechargeMethod'] == 1)
                        {
                            $paymentGateway                     = new PaymentGateway();
                            // $paymentGateway->apg_booking_id = $model->bkg_id;
                            $paymentGateway->apg_acc_trans_type = Accounting::AT_PARTNER;
                            $paymentGateway->apg_trans_ref_id   = $model->bkg_agent_id;
                            $paymentGateway->apg_ptp_id         = PaymentType::TYPE_PAYTM;
                            $paymentGateway->apg_amount         = $_POST['rechargeAmount'];
                            $paymentGateway->apg_remarks        = "Payment Initiated";
                            $paymentGateway->apg_ref_id         = '';
                            $paymentGateway->apg_user_type      = UserInfo::TYPE_AGENT;
                            $paymentGateway->apg_user_id        = Yii::app()->user->getAgentId();
                            $paymentGateway->apg_status         = 0;
                            $paymentGateway->apg_date           = new CDbExpression("now()");
                            $bankLedgerId                       = PaymentType::model()->ledgerList(PaymentType::TYPE_PAYTM);
                            $transModel                         = $paymentGateway->payment($bankLedgerId);
                            if ($transModel->apg_id)
                            {
                                $params['blg_ref_id'] = $transModel->apg_id;
                                $url                  = Yii::app()->createUrl('paytm/partnerpaymentinitiate', ['transid' => $transModel->apg_id, 'bkgid' => $model->bkg_id]);
                                $this->redirect($url);
                            }
                        }

                        //if ($isRechargeAccount && $_POST['payBy'] == 1 && $_POST['rechargeAmount'] >= $bkgInvoice->bkg_total_amount && $_POST['rechargeMethod'] == 2)
                        if ($_POST['payBy'] == 1 && $_POST['rechargeAmount'] >= $bkgInvoice->bkg_total_amount && $_POST['rechargeMethod'] == 2)
                        {
                            $paymentGateway                     = new PaymentGateway();
                            // $paymentGateway->apg_booking_id = $model->bkg_id;
                            $paymentGateway->apg_acc_trans_type = Accounting::AT_PARTNER;
                            $paymentGateway->apg_trans_ref_id   = $model->bkg_agent_id;
                            $paymentGateway->apg_ptp_id         = PaymentType::TYPE_PAYUMONEY;
                            $paymentGateway->apg_amount         = $_POST['rechargeAmount'];
                            $paymentGateway->apg_remarks        = "Payment Initiated";
                            $paymentGateway->apg_ref_id         = '';
                            $paymentGateway->apg_user_type      = UserInfo::TYPE_AGENT;
                            $paymentGateway->apg_user_id        = Yii::app()->user->getAgentId();
                            $paymentGateway->apg_status         = 0;
                            $paymentGateway->apg_date           = new CDbExpression("now()");
                            $bankLedgerId                       = PaymentType::model()->ledgerList(PaymentType::TYPE_PAYUMONEY);
                            $transModel                         = $paymentGateway->payment($bankLedgerId);
                            if ($transModel->apg_id)
                            {
                                $params['blg_ref_id'] = $transModel->apg_id;
                                $url                  = Yii::app()->createUrl('payu/partnerpaymentinitiate', ['transid' => $transModel->apg_id, 'bkgid' => $model->bkg_id]);
                                $this->redirect($url);
                            }
                        }


                        $this->render('spot11', ['model' => $model]);
                        Yii::app()->end();
                    }
                    catch (Exception $e)
                    {
                        $model->addError('bkg_id', $e->getMessage());
                        DBUtil::rollbackTransaction($transaction);
                    }
                    skipProcess:
                }
            }
            $this->render('spot10', ['model' => $model, 'bkgInvoice' => $bkgInvoice, 'isRechargeAccount' => $isRechargeAccount]);
        }
    }

    public function actionSpotsummary()
    {
        $this->pageTitle = " ";
        $this->layout    = "main";
        $bkgId           = Yii::app()->request->getParam('bkgId');
        $hash            = Yii::app()->request->getParam('hash');
        $transcode       = Yii::app()->request->getParam('tinfo');

        if ($bkgId != Yii::app()->shortHash->unHash($hash))
        {
            throw new CHttpException(400, 'Invalid data');
        }

        $agentTransModel = PaymentGateway::model()->getByCode($transcode);
        $model           = Booking::model()->with('bkgFromCity', 'bkgToCity')->findByPk($bkgId);
//		if ($agentTransModel->apg_status == 1)
//		{
//			$isRechargeAccount = 0;
//		}
//		else
//		{
//			$isRechargeAccount = 1;
//		}
        if (isset($_POST['Booking']) && ($_POST['payBy'] == 1 || $_POST['payBy'] == 2))
        {
            if ($_POST['payBy'] == 1 && $_POST['rechargeAmount'] >= $model->bkg_total_amount && $_POST['rechargeMethod'] == 1)
            {
                $paymentGateway                     = new PaymentGateway();
                // $paymentGateway->apg_booking_id = $model->bkg_id;
                $paymentGateway->apg_acc_trans_type = Accounting::AT_PARTNER;
                $paymentGateway->apg_trans_ref_id   = $model->bkg_agent_id;
                $paymentGateway->apg_ptp_id         = PaymentType::TYPE_PAYTM;
                $paymentGateway->apg_amount         = $_POST['rechargeAmount'];
                $paymentGateway->apg_remarks        = "Payment Initiated";
                $paymentGateway->apg_ref_id         = '';
                $paymentGateway->apg_user_type      = UserInfo::TYPE_AGENT;
                $paymentGateway->apg_user_id        = Yii::app()->user->getAgentId();
                $paymentGateway->apg_status         = 0;
                $paymentGateway->apg_date           = new CDbExpression("now()");
                $bankLedgerId                       = PaymentType::model()->ledgerList(PaymentType::TYPE_PAYTM);
                $transModel                         = $paymentGateway->payment($bankLedgerId);
                // $transModel = PaymentGateway::model()->initiateTrans(0, 15, $model->bkg_agent_id, PaymentType::TYPE_PAYTM, -1*$_POST['rechargeAmount'], "Partner payment initiated",'',  AccountTransactions::AgentUser, Yii::app()->user->getId());
                if ($transModel->apg_id)
                {
                    $params['blg_ref_id'] = $transModel->apg_id;
                    $url                  = Yii::app()->createUrl('paytm/partnerpaymentinitiate', ['transid' => $transModel->apg_id, 'bkgid' => $model->bkg_id]);
                    $this->redirect($url);
                }
            }
            if ($_POST['payBy'] == 1 && $_POST['rechargeAmount'] >= $model->bkg_total_amount && $_POST['rechargeMethod'] == 2)
            {
                $paymentGateway                     = new PaymentGateway();
                // $paymentGateway->apg_booking_id = $model->bkg_id;
                $paymentGateway->apg_acc_trans_type = Accounting::AT_PARTNER;
                $paymentGateway->apg_trans_ref_id   = $model->bkg_agent_id;
                $paymentGateway->apg_ptp_id         = PaymentType::TYPE_PAYUMONEY;
                $paymentGateway->apg_amount         = $_POST['rechargeAmount'];
                $paymentGateway->apg_remarks        = "Payment Initiated";
                $paymentGateway->apg_ref_id         = '';
                $paymentGateway->apg_user_type      = UserInfo::TYPE_AGENT;
                $paymentGateway->apg_user_id        = Yii::app()->user->getAgentId();
                $paymentGateway->apg_status         = 0;
                $paymentGateway->apg_date           = new CDbExpression("now()");
                $bankLedgerId                       = PaymentType::model()->ledgerList(PaymentType::TYPE_PAYUMONEY);
                $transModel                         = $paymentGateway->payment($bankLedgerId);
                //$transModel = PaymentGateway::model()->initiateTrans(0, 15, $model->bkg_agent_id, PaymentType::TYPE_PAYUMONEY, -1*$_POST['rechargeAmount'], "Partner payment initiated",'',  AccountTransactions::AgentUser, Yii::app()->user->getId());
                if ($transModel->apg_id)
                {
                    $params['blg_ref_id'] = $transModel->apg_id;
                    $url                  = Yii::app()->createUrl('payu/partnerpaymentinitiate', ['transid' => $transModel->apg_id, 'bkgid' => $model->bkg_id]);
                    $this->redirect($url);
                }
            }
            if ($_POST['payBy'] == 2)
            {
                $isAlready2Sms = SmsLog::model()->getCountVerifySms($model->bkg_id);
                if ($isAlready2Sms <= 2)
                {
                    $model->sendVerificationCode(10, true);
                }
                $this->render('spot11', ['model' => $model]);
                Yii::app()->end();
            }
        }
//		if ($isRechargeAccount == 0)
//		{
//			$this->render('spot11', ['model' => $model]);
//			Yii::app()->end();
//		}
        //$this->render('spot10', ['model' => $model, 'agentTransModel' => $agentTransModel, 'isRechargeAccount' => $isRechargeAccount]);
        $this->render('spot10', ['model' => $model, 'agentTransModel' => $agentTransModel]);
    }

    public function getModificationMSG($diff, $user)
    {
        $msg = '';
        if (count($diff) > 0)
        {
            if ($diff['consumer_name'])
            {
                $msg .= ' Customer Name: ' . $diff['consumer_name'] . ',';
            }
            if ($diff['consumer_phone'])
            {
                $msg .= ' Customer Phone: ' . $diff['consumer_phone'] . ',';
            }

            if ($diff['bkg_user_email'])
            {
                $msg .= ' Customer Email: ' . $diff['bkg_user_email'] . ',';
            }
            if ($diff['consumer_alt_phone'])
            {
                $msg .= ' Alternate Phone: ' . $diff['consumer_alt_phone'] . ',';
            }
            if ($diff['route_name'])
            {
                $msg .= ' Route: ' . $diff['route_name'] . ',';
            }
            if ($diff['booking_type'])
            {
                $msg .= ' Booking Type: ' . $diff['booking_type'] . ',';
            }
            if ($diff['pick_date'])
            {
                $msg .= ' Pickup Date/Time: ' . $diff['pick_date'] . ',';
            }
            if ($diff['return_date'])
            {
                $msg .= ' Return Date/Time: ' . $diff['return_date'] . ',';
            }
            if ($diff['bkg_pickup_address'])
            {
                $msg .= ' Pickup Address: ' . $diff['bkg_pickup_address'] . ',';
            }
            if ($diff['brt_pickup_location'])
            {
                $msg .= ' Pickup Address: ' . $diff['brt_pickup_location'] . ',';
            }

            if ($diff['bkg_drop_address'])
            {
                $msg .= ' Drop Address: ' . $diff['bkg_drop_address'] . ',';
            }
            if ($diff['bkg_additional_charge'])
            {
                $msg .= ' Additional Charge: ' . $diff['bkg_additional_charge'] . ',';
            }
            if ($diff['payable_amount'])
            {
                $msg .= ' Payable Amount: ' . $diff['payable_amount'] . ',';
            }
            if ($diff['bkg_driver_allowance_amount'])
            {
                $msg .= ' Driver allowance: ' . $diff['bkg_driver_allowance_amount'] . ',';
            }
            if ($diff['bkg_rate_per_km_extra'])
            {
                $msg .= ' Extra rate: ' . $diff['bkg_rate_per_km_extra'] . ',';
            }

            if ($user != 'consumer')
            {
                if ($diff['bkg_instruction_to_driver_vendor'])
                {
                    $msg .= ' Special Instruction: ' . $diff['bkg_instruction_to_driver_vendor'] . ',';
                }
            }
            if ($user == 'log')
            {
                if ($diff['bkg_vendor_amount'])
                {
                    $msg .= ' Vendor Amount: ' . $diff['bkg_vendor_amount'] . ',';
                }
                if ($diff['bkg_total_amount'])
                {
                    $msg .= ' Booking Amount: ' . $diff['bkg_total_amount'] . ',';
                }
                if ($diff['bkg_gozo_amount'])
                {
                    $msg .= ' Gozo Amount: ' . $diff['bkg_gozo_amount'] . ',';
                }
                if ($diff['bkg_advance_amount'])
                {
                    $msg .= ' Customer Advance: ' . round($diff['bkg_advance_amount']) . ',';
                }
                if ($diff['bkg_vendor_collected'])
                {
                    $msg .= ' Vendor Collected: ' . $diff['bkg_vendor_collected'] . ',';
                }
                if ($diff['bkg_refund_amount'])
                {
                    $msg .= ' Amount Refunded: ' . $diff['bkg_refund_amount'] . ',';
                }
                if ($diff['bkg_due_amount'])
                {
                    $msg .= ' Customer Payment due: ' . round($diff['bkg_due_amount']) . ',';
                }
                if ($diff['bkg_trip_distance'])
                {
                    $msg .= ' Kms Driven: ' . $diff['bkg_trip_distance'] . ',';
                }
                if ($diff['bkg_convenience_charge'] != '')
                {
                    $msg .= ' COD Charge: ' . round($diff['bkg_convenience_charge']) . ',';
                }
                if ($diff['bkg_driver_allowance_amount'] != '')
                {
                    $msg .= ' Driver Allowance: ' . round($diff['bkg_driver_allowance_amount']) . ',';
                }
                if ($diff['bkg_credits_used'])
                {
                    $msg .= ' Credits Used: ' . $diff['bkg_credits_used'] . ',';
                }
                if ($diff['bkg_base_amount'])
                {
                    $msg .= ' Base Amount: ' . $diff['bkg_base_amount'] . ',';
                }


                if ($diff['bkg_invoice'])
                {
                    $msg .= ' Invoice Requirement Changed,';
                }
            }
            $msg = rtrim($msg, ',');
        }
        return $msg;
    }

    public function actionGenerateInvoice()
    {
        $this->actionInvoice();
    }

    public function actionInvoice()
    {
        $bkgId = Yii::app()->request->getParam('bkgId');
        $hash  = Yii::app()->request->getParam('hash');
        $email = Yii::app()->request->getParam('email', 0);
        $isPdf = Yii::app()->request->getParam('pdf', 1);
//		if ($bkgId != Yii::app()->shortHash->unHash($hash))
//		{
//			throw new CHttpException(400, 'Invalid data. ');
//		}
        $model = Booking::model()->findByPk($bkgId);
        if ($model->bkg_status > 7)
        {
            throw new Exception('Booking not active', 401);
        }
        $invoiceList      = Booking::model()->getInvoiceByBooking($bkgId);
        $totPartnerCredit = AccountTransDetails::getTotalPartnerCredit($bkgId);
        $totAdvance       = PaymentGateway::model()->getTotalAdvance($bkgId);
        if ($isPdf == 1)
        {
            $html2pdf                   = Yii::app()->ePdf->mPdf();
            $css                        = file_get_contents(Yii::getPathOfAlias('webroot.assets.css') . '/vendorpdf.css');
            $html2pdf->writeHTML($css, 1);
            $html2pdf->setAutoTopMargin = 'stretch';

            $html2pdf->setHTMLFooter('<table id="footer" style="width: 100%"> <tr><td style="text-align: center"><hr>www.gozocabs.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;info@gozocabs.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9051 877 000</td></tr></table>');
            $htmlView = $this->renderPartial('invoice/view', array(
                'invoiceList'      => $invoiceList,
                'totPartnerCredit' => $totPartnerCredit,
                'totAdvance'       => $totAdvance,
                'isPDF'            => true
                ), true);
            $html2pdf->writeHTML($htmlView);
            if ($email == 1)
            {

                $filename     = $model->bkg_booking_id . date('Ymd', strtotime($model->bkg_pickup_date)) . '.pdf';
                $fileBasePath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'invoice';
                if (!is_dir($fileBasePath))
                {
                    mkdir($fileBasePath);
                }
                $fileBaseYearPath = $fileBasePath . DIRECTORY_SEPARATOR . date('Y', strtotime($model->bkg_pickup_date));
                if (!is_dir($fileBaseYearPath))
                {
                    mkdir($fileBaseYearPath);
                }
                $fileBaseMonthPath = $fileBaseYearPath . DIRECTORY_SEPARATOR . date('m', strtotime($model->bkg_pickup_date));
                if (!is_dir($fileBaseMonthPath))
                {
                    mkdir($fileBaseMonthPath);
                }
                $filePath = $fileBaseMonthPath . DIRECTORY_SEPARATOR . date('d', strtotime($model->bkg_pickup_date));
                if (!is_dir($filePath))
                {
                    mkdir($filePath);
                }
                $file = $filePath . DIRECTORY_SEPARATOR . $filename;
                $html2pdf->Output($file, 'D');
                echo $file;
                Yii::app()->end();
            }
            else
            {
                $html2pdf->Output();
            }
        }
        else
        {
            $this->renderPartial('invoice/view', array('invoiceList'      => $invoiceList,
                'totPartnerCredit' => $totPartnerCredit,
                'totAdvance'       => $totAdvance,
                'isPDF'            => true), false, true);
        }
    }

    public function actionInvoice1()
    {
        $bkgId = Yii::app()->request->getParam('bkgId');
        $email = Yii::app()->request->getParam('email', 0);
        $model = Booking::model()->findByPk($bkgId);
//        if ($model->bkg_agent_id != Yii::app()->user->getAgentId()) {
//            throw new Exception('Unauthorized Access to Booking', 401);
//        }
        if ($model->bkg_status > 7)
        {
            throw new Exception('Booking not active', 401);
        }
        if ($model->bkg_pickup_date < '2023-04-01')
        {
            echo $errorStr = 'Booking receipt not accessible';
            Yii::app()->end();
        }
        $invoiceList                = Booking::model()->getInvoiceByBooking($bkgId);
        $strRoute                   = BookingRoute::model()->getRouteName($bkgId);
        $html2pdf                   = Yii::app()->ePdf->mPdf();
        $css                        = file_get_contents(Yii::getPathOfAlias('webroot.assets.css') . '/vendorpdf.css');
        $html2pdf->writeHTML($css, 1);
        $html2pdf->setAutoTopMargin = 'stretch';

        $html2pdf->setHTMLFooter('<table id="footer" style="width: 100%"> <tr><td style="text-align: center"><hr>www.gozocabs.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;info@gozocabs.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9051 877 000</td></tr></table>');
        $html2pdf->writeHTML($this->renderPartial('receipt', array(
                'invoiceList' => $invoiceList,
                'strRoute'    => $strRoute,
                'isPDF'       => true
                ), true));

        if ($email == 1)
        {
            $filename     = $model->bkg_booking_id . date('Ymd', strtotime($model->bkg_pickup_date)) . '.pdf';
            $fileBasePath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'receipt';
            if (!is_dir($fileBasePath))
            {
                mkdir($fileBasePath);
            }
            $fileBaseYearPath = $fileBasePath . DIRECTORY_SEPARATOR . date('Y', strtotime($model->bkg_pickup_date));
            if (!is_dir($fileBaseYearPath))
            {
                mkdir($fileBaseYearPath);
            }
            $fileBaseMonthPath = $fileBaseYearPath . DIRECTORY_SEPARATOR . date('m', strtotime($model->bkg_pickup_date));
            if (!is_dir($fileBaseMonthPath))
            {
                mkdir($fileBaseMonthPath);
            }
            $filePath = $fileBaseMonthPath . DIRECTORY_SEPARATOR . date('d', strtotime($model->bkg_pickup_date));
            if (!is_dir($filePath))
            {
                mkdir($filePath);
            }
            $file = $filePath . DIRECTORY_SEPARATOR . $filename;
            $html2pdf->Output($file, 'F');
            echo "<a class='btn btn-primary' href='" . Yii::app()->createUrl("agent/booking/invoice", array('bkgId' => $model->bkg_id,)) . "' target='_blank'>Download as PDF</a>";
            $this->renderPartial('receipt', array('invoiceList' => $invoiceList, 'strRoute' => $strRoute), false, true);

            Yii::app()->end();
        }
        else
        {
            $html2pdf->Output();
        }
    }

    public function actionShuttlelist()
    {
        // deprecated not used
        exit;
        $fromCity               = Yii::app()->request->getParam('fromCity');
        $toCity                 = Yii::app()->request->getParam('toCity');
        $pickDate               = Yii::app()->request->getParam('pickDate');
        $arr                    = [];
        $arr['fromCity']        = $fromCity;
        $arr['toCity']          = $toCity;
        $arr['pickDate']        = ($pickDate == '') ? '' : DateTimeFormat::DatePickerToDate($pickDate);
        $showAvailableSeatsOnly = true;
        $result                 = Shuttle::model()->fetchData($arr, $showAvailableSeatsOnly);
        $this->renderPartial('shuttlelist', array('result' => $result, 'model' => $model), false, true);
    }

    public function actionInvoiceDownload()
    {
        $bkgId = Yii::app()->request->getParam('bkgId');
        $model = Booking::model()->findByPk($bkgId);
        if ($model->bkg_pickup_date < '2023-04-01')
        {
            echo $errorStr = 'Link expired';
            Yii::app()->end();
        }
        $invoiceList      = Booking::model()->getInvoiceByBooking($bkgId);
        $totPartnerCredit = AccountTransDetails::getTotalPartnerCredit($bkgId);
        $totAdvance       = PaymentGateway::model()->getTotalAdvance($bkgId);
        $totAdvanceOnline = PaymentGateway::model()->getTotalOnlinePayment($bkgId);

        $html2pdf                   = Yii::app()->ePdf->mPdf();
        $css                        = file_get_contents(Yii::getPathOfAlias('webroot.assets.css') . '/vendorpdf.css');
        $html2pdf->writeHTML($css, 1);
        $html2pdf->setAutoTopMargin = 'stretch';

        $html2pdf->setHTMLFooter('<table id="footer" style="width: 100%"> <tr><td style="text-align: center"><hr>www.gozocabs.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;info@gozocabs.com &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9051 877 000</td></tr></table>');
        $htmlView = $this->renderPartial('invoice/view', array(
            'invoiceList'      => $invoiceList,
            'totPartnerCredit' => $totPartnerCredit,
            'totAdvance'       => $totAdvance,
            'totAdvanceOnline' => $totAdvanceOnline,
            'isPDF'            => true,
            'isCommand'        => false
            ), true);
        $html2pdf->writeHTML($htmlView);
        $filename = $model->bkg_booking_id . '_' . date('Ymd', strtotime($model->bkg_pickup_date)) . '.pdf';

        $filePath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'invoice';
        //$filePath	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'uploads/invoice';
        if (!is_dir($filePath))
        {
            mkdir($filePath);
        }
        $file = $filePath . DIRECTORY_SEPARATOR . $filename;
        $html2pdf->Output($filename, 'D');
    }

    public function actionTrack()
    {
        $this->pageTitle = "Visitor Track";
        $model           = new VisitorTrack();
        $paramArray      = Yii::app()->request->getParam('VisitorTrack');

        $agentId = Yii::app()->user->getAgentId();
        if ($agentId != '34928')
        {
            throw new CHttpException(404, "Request not found");
        }

        $model->vtr_referal_url = 0;
        $model->vtr_visit_date  = date("Y-m-d");
        if (isset($_REQUEST['VisitorTrack']))
        {
            $model->attributes      = $paramArray;
            $model->vtr_visit_date  = $paramArray['vtr_visit_date'];
            $model->vtr_referal_url = $paramArray['vtr_referal_url'];
            $dataProvider           = $model->listByVisitor($paramArray);
        }
        $this->render('track', array('dataProvider' => $dataProvider, 'model' => $model));
    }

    public function actionSelectAddress()
    {

        $userId            = UserInfo::getUserId();
        $isAirport         = Yii::app()->request->getParam('airport', 0);
        $city              = Yii::app()->request->getParam('city');
        $callback          = Yii::app()->request->getParam('callback', "callback");
        $widgetTextValJson = Yii::app()->request->getParam('widgetTextValJson', "");
        $widgetTextVal     = Yii::app()->request->getParam('widgetTextVal', "");
        $this->renderAuto('existingAddress', ["city" => $city, "callback" => $callback, "isAirport" => $isAirport, "widgetTextValJson" => $widgetTextValJson, "widgetTextVal" => $widgetTextVal], false, true);
    }

}
