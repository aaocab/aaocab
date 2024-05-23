<?php

class RateController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';
	public $email_receipient;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
			['allow', 'actions' => ['add', 'update', 'form', 'addpartnerrate', 'partneratlist', 'entry'], 'roles' => ['rateAdd', 'rateEdit']],
			['allow', 'actions' => ['partneratlist', 'list', 'list1'], 'roles' => ['rateList']],
			['allow', 'actions' => ['del', 'deletePac'], 'roles' => ['rateDelete']],
			['allow', 'actions' => ['locallist', 'addlocal', 'deleteLocal']],
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('availablevehicles', 'addpartnerat', 'showlog', 'toproutes', 'exporttoproute',
					'toprouterate', 'partnerratelist', 'smartmarkup', 'smartmarkuplist', 'includeairportfee', 'dayRentalPrice', 'showPatLog'),
				'users'		 => array('@'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * @deprecated since version 02/10/2019 
	 * new actionForm
	 */
	public function actionAdd()
	{
		exit;

		$this->pageTitle = "Add Rate";
		$rid			 = Yii::app()->request->getParam('rteid');
		if ($rid > 0)
		{
			$this->pageTitle = "Edit Rate";
			$ftype			 = 'Modify';
			$model			 = Rate::model()->findByPk($rid);
		}
		else
		{
			$ftype	 = 'Add';
			$model	 = new Rate();
		}

		if (isset($_REQUEST['Rate']))
		{

			$old_rate			 = $model->rte_amount;
			$arr				 = Yii::app()->request->getParam('Rate');
			$model->attributes	 = $arr;
			$route_id			 = $arr['rte_route_id'];
			$type_id			 = $arr['rte_vehicletype_id'];
			if ($_REQUEST['returncheck'] == 1)
			{
				$reverseroute = Route::model()->getreturnroute($route_id, $arr['rte_amount'], $type_id, $arr['rte_excl_amount']);
			}
			if ($rid > 0)
			{
				if ($arr['rte_amount'] != $old_rate)
				{
					$remark		 = $model->rte_log;
					$dt			 = date('Y-m-d H:i:s');
					$user		 = Yii::app()->user->getId();
					$new_remark	 = "rate changed";
					if ($new_remark != '')
					{
						if (is_string($remark))
						{
							$newcomm = CJSON::decode($remark);
							if ($remark != '' && CJSON::decode($remark) == '')
							{
								$newcomm = array(array(0 => $user, 1 => $model->rte_create_date, 2 => $remark, 3 => $old_rate, 4 => $arr['rte_amount']));
							}
						}
						else if (is_array($remark))
						{
							$newcomm = $remark;
						}
						if ($newcomm == false)
						{
							$newcomm = array();
						}
						while (count($newcomm) >= 50)
						{
							array_pop($newcomm);
						}
						array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $new_remark, 3 => $old_rate, 4 => $arr['rte_amount']));
						$model->rte_log = CJSON::encode($newcomm);
					}
				}
			}
			$model->save();
			$status = "added";
			$this->redirect(array('list'));
		}
		$this->render('add', array('model' => $model));
	}

	/**
	 * This function is used for rendering the route list
	 * @param type $qry
	 */
	public function actionList($qry = [])
	{
		$this->pageTitle = "Rate List"; //Sets the page title
		$pageSize		 = Yii::app()->params["listPerPage"];

		$requestInstance = Yii::app()->request;

		$model				 = new Route();
		$rateModel			 = new Rate(); //Initialized the rate model for Vehicle model relation
		$serviceClassModel	 = new ServiceClass();

		if (empty($requestInstance->getParam("Route")) || empty($requestInstance->getParam("Rate")))
		{
			goto skipToDefault;
		}

		$receivedRouteDetails	 = $requestInstance->getParam("Route");
		$receivedRateDetails	 = $requestInstance->getParam("Rate");
		$receivedSccDetails		 = $requestInstance->getParam("ServiceClass");

		$requestData = array
			(
			"fromCityId"		 => $receivedRouteDetails["rut_from_city_id"],
			"toCityId"			 => $receivedRouteDetails["rut_to_city_id"],
			"routeCityId"		 => $receivedRouteDetails["rut_route_city_id"],
			"svcId"				 => $receivedRateDetails["rte_vehicletype_id"],
			"sccId"				 => $receivedSccDetails["scc_id"],
			"sourcezone"		 => $receivedRouteDetails["rut_source_zone"],
			"destinationZone"	 => $receivedRouteDetails["rut_destination_zone"]
		);

		$dataProvider = Rate::fetchRouteDetalis($requestData);

		if (!empty($dataProvider))
		{
			$model->rut_from_city_id		 = $receivedRouteDetails["rut_from_city_id"];
			$model->rut_to_city_id			 = $receivedRouteDetails["rut_to_city_id"];
			$rateModel->rte_vehicletype_id	 = $receivedRateDetails["rte_vehicletype_id"];
			$model->rut_route_city_id		 = $receivedRouteDetails["rut_route_city_id"];
			$serviceClassModel->scc_id		 = $receivedSccDetails["scc_id"];
			$model->rut_source_zone			 = $receivedRouteDetails["rut_source_zone"];
			$model->rut_destination_zone	 = $receivedRouteDetails["rut_destination_zone"];

			goto skipToSearchResult;
		}
		else
		{
			goto skipToDefault;
		}


		skipToDefault:
		$dataProvider = Rate::fetchRouteDetalis();

		skipToSearchResult:
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$this->render("list", array
			(
			"dataProvider"		 => $dataProvider,
			"qry"				 => $qry,
			"model"				 => $model,
			"rateModel"			 => $rateModel,
			"serviceClassModel"	 => $serviceClassModel
		));
	}

	public function actionUpdate()
	{
		$success = false;
		$id		 = Yii::app()->request->getParam("pk");
		$name	 = Yii::app()->request->getParam("name");
		$value	 = Yii::app()->request->getParam("value");
		$model	 = Rate::model()->findByPk($id);
		if ($model)
		{
			$old_rate		 = $model->$name;
			$model->$name	 = $value;
			if ($value != $old_rate)
			{
				$remark		 = $model->rte_log;
				$dt			 = date('Y-m-d H:i:s');
				$user		 = Yii::app()->user->getId();
				$new_remark	 = "rate changed";
				if ($new_remark != '')
				{
					if (is_string($remark))
					{
						$newcomm = CJSON::decode($remark);
						if ($remark != '' && CJSON::decode($remark) == '')
						{
							$newcomm = array(array(0 => $user, 1 => $model->rte_create_date, 2 => $remark, 3 => $old_rate, 4 => $value));
						}
					}
					else if (is_array($remark))
					{
						$newcomm = $remark;
					}
					if ($newcomm == false)
					{
						$newcomm = array();
					}
					while (count($newcomm) >= 50)
					{
						array_pop($newcomm);
					}
					array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $new_remark, 3 => $old_rate, 4 => $value));
					$model->rte_log = CJSON::encode($newcomm);
				}
			}
			$model->save();
			$success = true;
		}
		else
		{
			header('HTTP/1.0 400 Bad Request', true, 400);
			echo "Sorry, Couldn't update";
		}
		Yii::app()->end();
	}

	public function actionDel()
	{
		$id = Yii::app()->request->getParam('rteid');
		if ($id != '')
		{
			$model				 = Rate::model()->findByPk($id);
			$model->rte_status	 = 0;
			$model->save();
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			echo "true";
			Yii::app()->end();
		}
		$this->redirect(array('list'));
	}

	//+ CODE BLOCK START        

	/**
	 * @deprecated since 24/06/2021
	 * new actionEntry
	 * @author Ramala
	 * This Action is used for Route rate list edit and render based on a particular route
	 */
	public function actionForm()
	{
		$this->pageTitle = "Route Rate List"; //Sets page title

		$request = Yii::app()->request; //Check the request instance

		$routeId = Yii::app()->request->getParam("id"); //Getting the Route Id from the URL
		$models	 = Rate::model()->getVehicleDetailsByRoute($routeId); //Fetches the vehicle details based on Route Id.

		if (!$request->isPostRequest)
		{
			goto skipPost;
		}

		$arrRates	 = $request->getParam("Rate", []);
		$returncheck = $request->getParam("returncheck");

		//Checks whether data is submitted or not.
		if (empty($arrRates))
		{
			goto skipPost;
		}

		if ($returncheck == 1)
		{
			$arr = array
				(
				"rut_id"	 => $routeId,
				"rateData"	 => $arrRates
			);

			Route::updateReturnRoute($arr);
		}

		//Loops through the rate details received from form submit
		foreach ($arrRates as $rate)
		{
			//Procced if svc_id is greater than zero. Ids with Zero will not be considered in the transaction
			if ($rate["scv_id"] <= 0)
			{
				continue;
			}

			/**
			 * Case 1: If rateId exists, update the details.
			 * Case 2: If rateId exists and vendor amount is zero mark the entry as inactive
			 * Case 3: If rateId doesn't exists, Make a new entry
			 */
			if ($rate["rte_id"] > 0)
			{
				Route::updateRatesModel($models, $rate);
			}
			else if ($rate["rte_id"] > 0 || ($rate["rte_vendor_amount"] == 0))
			{
				$updateModel			 = Rate::model()->findByPk($rate["rte_id"]);
				$updateModel->rte_status = 0;
				$updateModel->save();
			}
			else
			{
				$ratemodel						 = new Rate();
				$ratemodel->rte_vendor_amount	 = $rate["rte_vendor_amount"];
				$ratemodel->rte_route_id		 = $routeId;
				$ratemodel->rte_toll_tax		 = $rate["rte_toll_tax"];
				$ratemodel->rte_state_tax		 = $rate["rte_state_tax"];
				$ratemodel->rte_minimum_markup	 = $rate["rte_minimum_markup"];
				$ratemodel->rte_vehicletype_id	 = $rate["scv_id"];
				$ratemodel->rte_status			 = 1;
				$ratemodel->save();
			}
		}

		$this->redirect(array("route/list"));

		skipPost:
		//Renders the view onload call
		$this->render("populateVehicleDetails", ["models" => $models, "route" => $routeId]);
	}

	//- CODE BLOCK END


	public function actionShowlog()
	{
		$rteid		 = Yii::app()->request->getParam('rteid');
		$logList	 = Rate::model()->getRateLog($rteid);
		$modelList	 = new CArrayDataProvider($logList, array('pagination' => array('pageSize' => 10),));
		$models		 = $modelList->getData();
		$this->renderPartial('showlog', array('lmodel' => $models, 'usersList' => $modelList), false, true);
	}

	public function actionToproutes()
	{
		$limit	 = Yii::app()->request->getParam('size', 1000);
		$res	 = true;
		$getData = Yii::app()->db->createCommand('SELECT * FROM `savaari_routes')->queryAll();
		if (isset($_POST['Booking']))
		{
			$reqArr		 = $_POST['Booking'];
			$limit		 = $reqArr['limitval'];
			$pdate		 = $reqArr['bkg_pickup_date'];
			$pickupDate	 = DateTimeFormat::DatePickerToDate($pdate) . ' 06:00:00';
			if (count($getData) > 0)
			{
				$emptyQry = "TRUNCATE savaari_routes";
				Yii::app()->db->createCommand($emptyQry)->execute();
			}
			$topRoutesQry = '';
			if ($res)
			{
				$topRoutesQry = "INSERT INTO  `savaari_routes`  (`rut_id`,`source`,`destination`)
          SELECT `rut_id`,`source`,`destination` from (SELECT  route.rut_id, IF(c1.cty_alias_name <> '', c1.cty_alias_name, c1.cty_name) AS `source`,
          IF(c2.cty_alias_name <> '', c2.cty_alias_name, c2.cty_name) AS `destination`, totalBookingCount

          FROM     (SELECT   DISTINCT bkg_from_city_id, bkg_to_city_id, COUNT(1) AS totalBookingCount,  max(bkg_rate_per_km_extra)  extrKmCharge
          FROM     `booking` 
		    INNER JOIN booking_invoice ON booking_invoice.biv_bkg_id = booking.bkg_id
          WHERE    bkg_status IN (2, 3, 5, 6, 7) AND bkg_active = 1 AND
           bkg_rate_per_km_extra IS NOT NULL AND
          DATE(bkg_create_date) > '2016-10-01' AND bkg_pickup_date
          BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR)
          AND NOW()
          AND bkg_vehicle_type_id IN (" . VehicleCategory::COMPACT_ECONOMIC . "," . VehicleCategory::SUV_ECONOMIC . "," . VehicleCategory::SEDAN_ECONOMIC . ")
          GROUP BY bkg_from_city_id, bkg_to_city_id) b1
          INNER JOIN `route` ON b1.bkg_from_city_id = route.rut_from_city_id AND b1.bkg_to_city_id = route.rut_to_city_id
          INNER JOIN `cities` c1 ON `rut_from_city_id` = c1.cty_id AND c1.cty_active = 1 AND c1.cty_is_airport = 0
          AND (  c1.cty_excluded_cabtypes IS NULL OR c1.cty_excluded_cabtypes= ''
          OR  NOT find_in_set(" . VehicleCategory::COMPACT_ECONOMIC . ",c1.cty_excluded_cabtypes) OR NOT find_in_set(" . VehicleCategory::SUV_ECONOMIC . ",c1.cty_excluded_cabtypes) OR NOT find_in_set(" . VehicleCategory::SEDAN_ECONOMIC . ",c1.cty_excluded_cabtypes))
          INNER JOIN `cities` c2 ON `rut_to_city_id` = c2.cty_id AND c2.cty_active = 1 AND c2.cty_is_airport = 0
          INNER JOIN rate ON rate.rte_route_id = route.rut_id
          WHERE    rut_active = 1 AND c1.cty_id <> c2.cty_id
          GROUP BY rut_id
          ORDER BY totalBookingCount DESC
          LIMIT    $limit) a1";
			}
			$res1 = false;
			if ($topRoutesQry != '')
			{
				$res1 = Yii::app()->db->createCommand($topRoutesQry)->execute();
			}
			$sql = '';
			if ($res1)
			{

				$sql = 'select sr.rut_id, r.rut_from_city_id, r.rut_to_city_id from savaari_routes sr
                left join route r on r.rut_id = sr.rut_id where sr.sedan_total_amount is null';
			}
			if ($sql != '')
			{
				$resultset = Yii::app()->db->createCommand($sql)->queryAll();
			}
			$varTaxIncArr = [0 => 'No', 1 => 'Yes'];
			if ($resultset > 0)
			{
				$row = 0;
				foreach ($resultset as $result)
				{

					$route								 = [];
					$routeModel							 = new BookingRoute();
					$routeModel->brt_from_city_id		 = $result['rut_from_city_id'];
					$routeModel->brt_to_city_id			 = $result['rut_to_city_id'];
					$routeModel->brt_pickup_datetime	 = $pickupDate;
					$routeModel->brt_pickup_date_date	 = DateTimeFormat::DateTimeToDatePicker($pickupDate);
					$routeModel->brt_pickup_date_time	 = date('h:i A', strtotime($pickupDate));
					$route[]							 = $routeModel;
					$bookingCPId						 = Yii::app()->params['gozoChannelPartnerId'];
					$quote								 = new Quote();
					$quote->routes						 = $route;
					$quote->tripType					 = 1;
					$quote->partnerId					 = $bookingCPId;
					$quote->quoteDate					 = date("Y-m-d H:i:s");
					$quote->pickupDate					 = $route[0]->brt_pickup_datetime;
					$quote->setCabTypeArr();
					$qt									 = $quote->getQuote(3);
					$allowedCabs						 = [1, 2, 3];
					$rateCompact						 = $rateSuv							 = $rateSedan							 = '';
					foreach ($qt as $key => $value)
					{
						if (!in_array($key, $allowedCabs))
						{
							unset($qt[$key]);
						}
					}
					if ($qt[1]->success)
					{
						$rateCompact = " compact_total_amount = " . $qt[1]->routeRates->totalAmount . ","
								. " compact_gst = " . $qt[1]->routeRates->gst . ","
								. " compact_toll = " . (($qt[1]->routeRates->tollTaxAmount > 0) ? $qt[1]->routeRates->tollTaxAmount : 0) . ","
								. " compact_state = " . (($qt[1]->routeRates->stateTax > 0) ? $qt[1]->routeRates->stateTax : 0) . ","
								. " compact_extra_charge = " . $qt[1]->routeRates->ratePerKM . ", "
								. " compact_base_amount = " . $qt[1]->routeRates->baseAmount . ", "
								. " compact_is_toll_included = '" . (($qt[1]->routeRates->isTollIncluded == 1) ? 'Yes' : 'No') . "', "
								. " compact_is_state_included = '" . (($qt[1]->routeRates->isStateTaxIncluded == 1) ? 'Yes' : 'No') . "', ";
					}
					if ($qt[2]->success)
					{
						$rateSuv = " suv_total_amount = " . $qt[2]->routeRates->totalAmount . ","
								. " suv_gst = " . $qt[2]->routeRates->gst . ","
								. " suv_toll = " . (($qt[2]->routeRates->tollTaxAmount > 0) ? $qt[2]->routeRates->tollTaxAmount : 0) . ","
								. " suv_state = " . (($qt[2]->routeRates->stateTax > 0) ? $qt[2]->routeRates->stateTax : 0) . ","
								. " suv_extra_charge = " . $qt[2]->routeRates->ratePerKM . ", "
								. " suv_base_amount = " . $qt[2]->routeRates->baseAmount . ", "
								. " suv_is_toll_included = '" . (($qt[2]->routeRates->isTollIncluded == 1) ? 'Yes' : 'No') . "', "
								. " suv_is_state_included = '" . (($qt[2]->routeRates->isStateTaxIncluded == 1) ? 'Yes' : 'No') . "', ";
					}
					if ($qt[3]->success)
					{
						$rateSedan = " sedan_total_amount = " . $qt[3]->routeRates->totalAmount . ","
								. " sedan_gst = " . $qt[3]->routeRates->gst . ","
								. " sedan_toll = " . (($qt[3]->routeRates->tollTaxAmount > 0) ? $qt[3]->routeRates->tollTaxAmount : 0) . ","
								. " sedan_state = " . (($qt[3]->routeRates->stateTax > 0) ? $qt[3]->routeRates->stateTax : 0) . ","
								. " sedan_extra_charge = " . $qt[3]->routeRates->ratePerKM . ", "
								. " sedan_base_amount = " . $qt[3]->routeRates->baseAmount . ", "
								. " sedan_is_toll_included = '" . (($qt[3]->routeRates->isTollIncluded == 1) ? 'Yes' : 'No') . "', "
								. " sedan_is_state_included = '" . (($qt[3]->routeRates->isStateTaxIncluded == 1) ? 'Yes' : 'No') . "', ";
					}

					$rates	 = rtrim(trim($rateCompact . $rateSuv . $rateSedan), ',');
					$succ	 = false;
					if ($rates != '')
					{
						$qry	 = "UPDATE savaari_routes SET "
								. $rates . " where rut_id = " . $result['rut_id'];
						$succ	 = Yii::app()->db->createCommand($qry)->execute();
						if ($succ)
							$row++;
					}
				}
				$msg = "Total " . $row . " records uploaded";
			}
			else
			{
				$msg = "No record uploaded";
			}
		}
		$getData = Yii::app()->db->createCommand('SELECT * FROM `savaari_routes')->queryAll();
		$this->render('toproutes', ['limit' => $limit, 'dataLoaded' => count($getData), 'msg' => $msg, 'model' => new Booking()]);
	}

	public function actionExporttoproute()
	{
		echo "Exporting Routes";
		echo "<br>";

		$getData = Yii::app()->db->createCommand('SELECT * FROM `savaari_routes')->queryAll();

		if (count($getData) > 0)
		{
			header('Content-Disposition: attachment; filename="topRoutes.csv"');
			header('Pragma: no-cache');
			header('Expires: 0');
			$file = fopen('php://output', 'w');
			fputcsv($file, array('rut_id', 'source', 'destination',
				'compact_base_amount', 'compact_gst', 'compact_total_amount', 'compact_toll', 'compact_state',
				'compact_is_toll_included', 'compact_is_state_included', 'compact_extra_charge',
				'sedan_base_amount', 'sedan_gst', 'sedan_total_amount', 'sedan_toll', 'sedan_state',
				'sedan_is_toll_included', 'sedan_is_state_included', 'sedan_extra_charge',
				'suv_base_amount', 'suv_gst', 'suv_total_amount', 'suv_toll', 'suv_state',
				'suv_is_toll_included', 'suv_is_state_included', 'suv_extra_charge'));
			foreach ($getData as $result)
			{
				$rowexport = array($result['rut_id'], $result['source'], $result['destination'],
					$result['compact_base_amount'],
					$result['compact_gst'],
					$result['compact_total_amount'],
					$result['compact_toll'],
					$result['compact_state'],
					$result['compact_is_toll_included'],
					$result['compact_is_state_included'],
					$result['compact_extra_charge'],
					$result['sedan_base_amount'],
					$result['sedan_gst'],
					$result['sedan_total_amount'],
					$result['sedan_toll'],
					$result['sedan_state'],
					$result['sedan_is_toll_included'],
					$result['sedan_is_state_included'],
					$result['sedan_extra_charge'],
					$result['suv_base_amount'],
					$result['suv_gst'], $result['suv_total_amount'],
					$result['suv_toll'], $result['suv_state'],
					$result['suv_is_toll_included'],
					$result['suv_is_state_included'],
					$result['suv_extra_charge']
				);
				fputcsv($file, $rowexport);
			}
		}
	}

	public function actionToprouterate()
	{
		$rockBottomMargin	 = Yii::app()->params['rockBottomMargin'];
		$sTax				 = Filter::getServiceTaxRate();
		$defaultMarkup		 = Yii::app()->params['defMarkupCab'];
		$vhtStr				 = '';
		$selVhtStr			 = '';
		$vhtArr				 = ['compact', 'sedan', 'suv'];
		foreach ($vhtArr as $vht)
		{
			$vhtStr		 .= "rt_{$vht}.rte_toll_tax {$vht}_toll_tax,rt_{$vht}.rte_state_tax {$vht}_state_tax,
							round(round( (rt_{$vht}.rte_vendor_amount-rt_{$vht}.rte_toll_tax-rt_{$vht}.rte_state_tax)* (1 + ({$rockBottomMargin} / 100))) * ( 1 + ( GREATEST(rt_{$vht}.rte_minimum_markup, {$defaultMarkup}) / 100))) {$vht}_base,";
			$selVhtStr	 .= " {$vht}_toll_tax, {$vht}_state_tax, {$vht}_base,
							round( {$vht}_base * ($sTax / 100)) {$vht}_gst,
							({$vht}_toll_tax+ {$vht}_state_tax+ round( {$vht}_base * ((100+{$sTax})/ 100))) {$vht}_total,";
		}
		$vhtStr		 = trim(trim($vhtStr), ',');
		$selVhtStr	 = trim(trim($selVhtStr), ',');

		$sql = "
			SELECT source,destination, $selVhtStr from (			
			SELECT  route.rut_id, IF(c1.cty_alias_name <> '', c1.cty_alias_name, c1.cty_name) AS `source`,
          IF(c2.cty_alias_name <> '', c2.cty_alias_name, c2.cty_name) AS `destination`,  
          $vhtStr           
          FROM     (SELECT   DISTINCT bkg_from_city_id, bkg_to_city_id, COUNT(1) AS totalBookingCount
          FROM     `booking`
          WHERE    bkg_status IN (2, 3, 5, 6, 7) AND bkg_active = 1 AND 
          DATE(bkg_create_date) > '2016-10-01' AND bkg_pickup_date
          BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR)
          AND NOW()
          AND bkg_vehicle_type_id IN (" . VehicleCategory::COMPACT_ECONOMIC . "," . VehicleCategory::SUV_ECONOMIC . "," . VehicleCategory::SEDAN_ECONOMIC . ")
          GROUP BY bkg_from_city_id, bkg_to_city_id) b1
          INNER JOIN `route` ON b1.bkg_from_city_id = route.rut_from_city_id AND b1.bkg_to_city_id = route.rut_to_city_id
          INNER JOIN `cities` c1 ON `rut_from_city_id` = c1.cty_id AND c1.cty_active = 1 AND c1.cty_is_airport = 0
          AND (  c1.cty_excluded_cabtypes IS NULL OR c1.cty_excluded_cabtypes= '' OR NOT find_in_set(" . VehicleCategory::COMPACT_ECONOMIC . ",c1.cty_excluded_cabtypes) 
          OR NOT find_in_set(" . VehicleCategory::SUV_ECONOMIC . ",c1.cty_excluded_cabtypes) OR NOT find_in_set(" . VehicleCategory::SEDAN_ECONOMIC . ",c1.cty_excluded_cabtypes))
          INNER JOIN `cities` c2 ON `rut_to_city_id` = c2.cty_id AND c2.cty_active = 1 AND c2.cty_is_airport = 0          
          INNER JOIN rate rt_compact ON rt_compact.rte_route_id = route.rut_id AND rt_compact.rte_status = 1 AND rt_compact.rte_vehicletype_id = " . VehicleCategory::COMPACT_ECONOMIC . "
          INNER JOIN rate rt_sedan ON rt_sedan.rte_route_id = route.rut_id AND rt_sedan.rte_status = 1  AND rt_sedan.rte_vehicletype_id = " . VehicleCategory::SEDAN_ECONOMIC . "
          INNER JOIN rate rt_suv ON rt_suv.rte_route_id = route.rut_id AND rt_suv.rte_status = 1 AND rt_suv.rte_vehicletype_id = " . VehicleCategory::SUV_ECONOMIC . "
          WHERE    rut_active = 1 AND c1.cty_id <> c2.cty_id
          GROUP BY rut_id
          ORDER BY totalBookingCount DESC
          LIMIT 100)top_route_rate";

		$getData = Yii::app()->db->createCommand($sql)->queryAll();

		if (count($getData) > 0)
		{
			header('Content-Disposition: attachment; filename="topRoutes.csv"');
			header('Pragma: no-cache');
			header('Expires: 0');
			$file = fopen('php://output', 'w');
			fputcsv($file, array('source', 'destination',
				'compact_base_amount', 'compact_toll', 'compact_state', 'compact_gst', 'compact_total_amount',
				'sedan_base_amount', 'sedan_toll', 'sedan_state', 'sedan_gst', 'sedan_total_amount',
				'suv_base_amount', 'suv_toll', 'suv_state', 'suv_gst', 'suv_total_amount'));
			foreach ($getData as $result)
			{
				$rowexport = array(
					$result['source'],
					$result['destination'],
					$result['compact_base'],
					$result['compact_toll_tax'],
					$result['compact_state_tax'],
					$result['compact_gst'],
					$result['compact_total'],
					$result['sedan_base'],
					$result['sedan_toll_tax'],
					$result['sedan_state_tax'],
					$result['sedan_gst'],
					$result['sedan_total'],
					$result['suv_base'],
					$result['suv_toll_tax'],
					$result['suv_state_tax'],
					$result['suv_gst'],
					$result['suv_total'],
				);
				fputcsv($file, $rowexport);
			}
		}
	}

	public function actionAddpartnerrate()
	{

		$this->pageTitle = "Add Partner Rate";
		$rid			 = Yii::app()->request->getParam('rteid');
		$status			 = 'add';
		if ($rid > 0)
		{
			$this->pageTitle = "Edit Partner Rate";
			$ftype			 = 'Modify';
			$model			 = PartnerRate::model()->findByPk($rid);
			$status			 = 'edit';
		}
		else
		{
			$ftype	 = 'Add Partner ';
			$model	 = new PartnerRate();
		}
		if (isset($_REQUEST['PartnerRate']))
		{
			$art				 = Yii::app()->request->getParam('PartnerRate');
			$model->attributes	 = $art;

			$newData = $model->attributes;
			$result	 = CActiveForm::validate($model);
			if ($result == '[]')
			{

				$model->prt_partner_id			 = $art['prt_partner_id'];
				$model->prt_vehicletype_id		 = $art['prt_vehicletype_id'];
				$model->prt_trip_type			 = $art['prt_trip_type'];
				$model->prt_route_id			 = $art['prt_route_id'];
				$model->prt_is_toll_included	 = $art['prt_is_toll_included'];
				$model->prt_toll_tax			 = ( $art['prt_is_toll_included'] == 0 ) ? 0 : $art['prt_toll_tax'];
				$model->prt_is_state_included	 = $art['prt_is_state_included'];
				$model->prt_state_tax			 = ( $art['prt_is_state_included'] == 0 ) ? 0 : $art['prt_state_tax'];
				$model->prt_vendor_amount		 = $art['prt_vendor_amount'];
				$model->prt_total_amount		 = $art['prt_total_amount'];
				$model->prt_apply_surge			 = ($art['prt_apply_surge'] != 1) ? 0 : $art['prt_apply_surge'];
				$model->prt_night_charge		 = $art['prt_night_charge'];

				$model->save();
				$this->redirect(array('partnerratelist'));
			}
		}
		$this->render('addpartnerrate', array('model' => $model, 'status' => $status));
	}

	public function actionPartnerratelist($qry = [])
	{
		$this->pageTitle = "Partner Rate List";
		$pageSize		 = Yii::app()->params['listPerPage'];
		$model			 = new PartnerRate('search');
		if (isset($_REQUEST['PartnerRate']))
		{
			$model->attributes = Yii::app()->request->getParam('PartnerRate');
		}
		$dataProvider = $model->getList();

		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('partnerratelist', array(
			'model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'qry'			 => $qry
		));
	}

	public function actionSmartmarkup()
	{

		$this->pageTitle = "Add Smart Markup";
		//$rid = Yii::app()->request->getParam('rteid');
		$status			 = 'add';
		if ($rid > 0)
		{
			/* $this->pageTitle = "Edit Golden Markup";
			  $ftype = 'Modify';
			  $model = PartnerRate::model()->findByPk($rid);
			  $status = 'edit'; */
		}
		else
		{
			$ftype	 = 'Add Smart Markup ';
			$model	 = new GoldenMarkup();
		}
		if (isset($_REQUEST['GoldenMarkup']))
		{
			$art				 = Yii::app()->request->getParam('GoldenMarkup');
			$model->attributes	 = $art;

			$result = CActiveForm::validate($model);

			if ($result == '[]')
			{
				$model->glm_area_type			 = $art['glm_area_type'];
				$model->glm_markup_type			 = $art['glm_markup_type'];
				$model->glm_from_area			 = $art['glm_from_area'];
				$model->glm_to_area				 = $art['glm_to_area'];
				$model->glm_apply_hour_duration	 = $art['glm_apply_hour_duration'];
				$model->glm_maximum_amount		 = $art['glm_maximum_amount'];
				$model->glm_markup_value		 = $art['glm_markup_value'];

				$model->save();
				$this->redirect(array('smartmarkuplist'));
			}
		}
		$this->render('addgoldenmarkup', array('model' => $model, 'status' => $status));
	}

	public function actionSmartmarkuplist($qry = [])
	{
		$this->pageTitle = "Smart Markup List";
		$pageSize		 = Yii::app()->params['listPerPage'];
		$model			 = new GoldenMarkup('search');
		if (isset($_REQUEST['GoldenMarkup']))
		{
			$model->attributes = Yii::app()->request->getParam('GoldenMarkup');
		}
		$dataProvider = $model->getList();

		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('goldenmarkuplist', array(
			'model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'qry'			 => $qry
		));
	}

	public function actionAddpartnerat()
	{
		$patid			 = Yii::app()->request->getParam('patid', 0);
		$this->pageTitle = 'Partner Airport Transfer';
		$airportList	 = Cities::getAirportList();
		$model			 = new PartnerAirportTransfer();
		if ($patid > 0)
		{
			$model = PartnerAirportTransfer::model()->findByPk($patid);
		}

		$postdata = Yii::app()->request->getParam('PartnerAirportTransfer');
		if (isset($postdata))
		{

			if (isset($postdata['pat_id']) && $postdata['pat_id'] > 0)
			{
				$model = PartnerAirportTransfer::model()->findByPk($postdata['pat_id']);
				if (!$model)
				{
					return false;
				}
				unset($postdata['pat_id']);
			}
			$remark = $model->pat_log;

			$oldData = $model->attributes;
			unset($oldData['pat_log']);
			unset($oldData['pat_created_on']);
			unset($oldData['pat_modified_on']);
			unset($oldData['pat_active']);

			$model->attributes	 = $postdata;
			$dt					 = DBUtil::getCurrentTime();
			$user				 = UserInfo::getUserId();

			if (is_string($remark))
			{
				$newcomm = CJSON::decode($remark);
				if ($remark != '' && CJSON::decode($remark) == '')
				{
					$newcomm = array(array(0 => $user, 1 => $dt, 2 => $remark));
				}
			}
			else if (is_array($remark))
			{
				$newcomm = $remark;
			}
			if ($newcomm == false)
			{
				$newcomm = array();
			}
			while (count($newcomm) >= 50)
			{
				array_pop($newcomm);
			}

			$model->pat_modified_on = $dt;

			if (!$oldData['pat_id'] == null)
			{
				unset($oldData['pat_id']);
				$new_remark = ['Old values' => array_diff_assoc($oldData, $postdata)];
			}
			else
			{
				$new_remark = ['New Insert'];
			}
			array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $new_remark));
			$model->pat_log = CJSON::encode($newcomm);

			if ($model->save())
			{
				$this->redirect(array("partneratlist"));
			}
		}
		$this->render('addPartnerAT', array(
			'model'			 => $model,
			'airportList'	 => $airportList,
		));
	}

	public function actionPartneratlist()
	{
		$this->pageTitle = 'Partner Airport Transfer Rate List';
		$model			 = new PartnerAirportTransfer();
		$arr			 = Yii::app()->request->getParam('PartnerAirportTransfer');
		if (isset($_REQUEST['PartnerAirportTransfer']))
		{
			$model->attributes			 = $arr;
			$model->pat_partner_id		 = $arr['pat_partner_id'];
			$model->pat_city_id			 = $arr['pat_city_id'];
			$model->pat_vehicle_type	 = $arr['pat_vehicle_type'];
			$model->pat_transfer_type	 = $arr['pat_transfer_type'];
			$model->is_b2c				 = isset($arr['is_b2c'][0]);
		}

		if (!empty($_REQUEST['exportRate']))
		{
			$rows = $model::getList($arr, true);

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"RateReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$handle = fopen("php://output", 'w');
			fputcsv($handle, ['Partner', 'Airport', 'Transfer type', 'Minimum km', 'Extra rate/km', 'Cab Type', 'Vendor Amount', 'Total Fare']);
			foreach ($rows as $row)
			{
				$rowArray							 = array();
				$rowArray['partnerName']			 = ($row['partnerName'] != '')? $row['partnerName'] : 'B2C';
				$rowArray['airportName']			 = $row['airportName'];
				$rowArray['transferType']			 = $row['transferType'];
				$rowArray['pat_minimum_km']			 = $row['pat_minimum_km'];
				$rowArray['pat_extra_per_km_rate']	 = $row['pat_extra_per_km_rate'];
				$rowArray['vehicleType']			 = $row['vehicleType'];
				$rowArray['pat_vendor_amount']		 = $row['pat_vendor_amount'];
				$rowArray['pat_total_fare']			 = $row['pat_total_fare'];
				$row1								 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit();
		}

		$dataProvider							 = $model::getList($arr);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->render('patlist', [
			'model'			 => $model,
			'dataProvider'	 => $dataProvider,
		]);
	}

	public function actionDeletePac()
	{
		$id = Yii::app()->request->getParam('patid');
		if ($id != '')
		{
			$model				 = PartnerAirportTransfer::model()->findByPk($id);
			$model->pat_active	 = 0;
			$model->save();
		}
		$this->redirect(array('partneratlist'));
	}

	/**
	 * Add or update rates
	 */
	public function actionEntry()
	{
		$this->pageTitle = "Route Rate List";
		$request		 = Yii::app()->request;
		$routeId		 = $request->getParam("id");
		$newRates		 = $request->getParam("Rate", []);
		$returnCheck	 = $request->getParam("returncheck");
		$flag			 = 0;

		$models = Rate::model()->getVehicleDetailsByRoute($routeId); //Fetches the vehicle details based on Route Id.
		//Checks whether data is submitted or not.
		if (!$request->isPostRequest || empty($newRates))
		{
			goto skipPost;
		}
		$returnSet	 = new ReturnSet();
		$returnSet	 = Rate::addRates($routeId, $newRates, $models, $returnCheck);
		if ($returnSet->isSuccess())
		{
			$flag	 = 1;
			$models	 = Rate::model()->getVehicleDetailsByRoute($routeId);
//			$this->redirect(array('/admin/rate/entry', 'id' => $routeId));
		}
		else
		{
			$error = $returnSet->getErrors();
		}

		skipPost:
		//Renders the view onload call
		$this->render("populateVehicleDetails", ["models" => $models, "route" => $routeId, "error" => $error, "flag" => $flag]);
	}

	public function actionIncludeairportfee()
	{
		$patid					 = Yii::app()->request->getParam('patid', 0);
		$is_airport_fee_included = Yii::app()->request->getParam('is_airport_fee_included');
		$model					 = PartnerAirportTransfer::model()->findByPk($patid);
		$success				 = false;
		if ($is_airport_fee_included != '')
		{
			switch ($is_airport_fee_included)
			{
				case 0:
					$model->is_airport_fee_included = 1;
					break;

				case 1:
					$model->is_airport_fee_included = 0;
					break;
			}
			if ($model->save())
			{
				$success = true;
			}
		}

		$this->redirect(array('/admin/rate/partneratlist'));
	}

	public function actionDayRentalPrice()
	{
		$orderby		 = 'date';
		$model			 = new AreaPriceRule();
		$data			 = Yii::app()->request->getParam('AreaPriceRule');
		$req			 = Yii::app()->request;
		$this->pageTitle = " Day Rental Price";
		$areatype		 = AreaPriceRule::model()->areatype;
		if ($req->getParam('AreaPriceRule'))
		{
			$arr					 = $req->getParam('AreaPriceRule');
			$model->prr_trip_type	 = $arr['prr_trip_type'];
			$model->areaType		 = $arr['areaType'];
			$model->cty_state_id	 = $arr['cty_state_id'];
			$model->sourcezone		 = $arr['sourcezone'];
			$model->city_id			 = $arr['city_id'];
			$model->apr_cab_type	 = $arr['apr_cab_type'];
		}
		if (isset($_REQUEST['export_triptype']) || isset($_REQUEST['export_cabtype']) || isset($_REQUEST['export_areatype']) || isset($_REQUEST['export_stateid']) || isset($_REQUEST['export_sourcezone']) || isset($_REQUEST['export_cityid']))
		{
			$model->prr_trip_type	 = Yii::app()->request->getParam('export_triptype');
			$model->apr_cab_type	 = Yii::app()->request->getParam('export_cabtype');
			$model->areaType		 = Yii::app()->request->getParam('export_areatype');
			$model->cty_state_id	 = Yii::app()->request->getParam('export_stateid');
			$model->sourcezone		 = Yii::app()->request->getParam('export_zone');
			$model->city_id			 = Yii::app()->request->getParam('export_cityid');

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"DayRentalPrice_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "BlockedVendorPayments_" . date('Ymdhis') . ".csv";
			$foldername	 = Yii::app()->params['uploadPath'];
			$backup_file = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$rows	 = $model->getDayRentalPrice(1);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Area Type', 'Area Name', 'Cab Type', 'Trip Type', 'Rate Per Km', 'Rate Per Minute',
				'Rate Per Km Extra', 'Rate Per Minute Extra', 'Minimum Km', 'Minimum Duration',
				'Base Amount', 'Minimum Km Day', 'Maximum Km Day', 'Day Driver Allowance', 'Night Driver Allowance',
				'Driver Allowance Km Limit']);
			foreach ($rows as $row)
			{
				$rowArray									 = array();
				$rowArray['apr_area_type']					 = $areatype[$row['apr_area_type']];
				$rowArray['apr_area_name']					 = AreaPriceRule::getNameByData($row['apr_area_id'], $row['apr_area_type']);
				$rowArray['apr_cab_type']					 = SvcClassVhcCat::model()->getVctSvcList("string", 0, 0, $row['apr_cab_type']);
				$rowArray['prr_trip_type']					 = Booking::getBookingType($row['prr_trip_type']);
				$rowArray['prr_rate_per_km']				 = $row['prr_rate_per_km'];
				$rowArray['prr_rate_per_minute']			 = $row['prr_rate_per_minute'];
				$rowArray['prr_rate_per_km_extra']			 = $row['prr_rate_per_km_extra'];
				$rowArray['prr_rate_per_minute_extra']		 = $row['prr_rate_per_minute_extra'];
				$rowArray['prr_min_km']						 = $row['prr_min_km'];
				$rowArray['prr_min_duration']				 = $row['prr_min_duration'];
				$rowArray['prr_min_base_amount']			 = $row['prr_min_base_amount'];
				$rowArray['prr_min_km_day']					 = $row['prr_min_km_day'];
				$rowArray['prr_max_km_day']					 = $row['prr_max_km_day'];
				$rowArray['prr_day_driver_allowance']		 = $row['prr_day_driver_allowance'];
				$rowArray['prr_night_driver_allowance']		 = $row['prr_night_driver_allowance'];
				$rowArray['prr_driver_allowance_km_limit']	 = $row['prr_driver_allowance_km_limit'];
				$row1										 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider = $model->getDayRentalPrice();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('dayrentalprice', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionShowPatLog()
	{
		$patId	 = Yii::app()->request->getParam('patid');
		$patLog	 = PartnerAirportTransfer::model()->getLog($patId);
		$this->renderPartial('showpatlog', array('model' => $patLog), false, true);
	}

	public function actionLocalList()
	{
		$this->pageTitle = 'Local Transfer Rate List';
		$model			 = new LocalTransferPackage();
		$arr			 = Yii::app()->request->getParam('LocalTransferPackage');
		if (isset($_REQUEST['LocalTransferPackage']))
		{
			$model->attributes			 = $arr;
			$model->ltp_partner_id		 = $arr['ltp_partner_id'];
			$model->ltp_city_id			 = $arr['ltp_city_id'];
			$model->ltp_vehicle_type	 = $arr['ltp_vehicle_type'];
			$model->ltp_transfer_type	 = $arr['ltp_transfer_type'];
			$model->is_b2c				 = isset($arr['is_b2c'][0]);
		}

		if (!empty($_REQUEST['exportRate']))
		{
			$rows = $model::getList($arr, true);

			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"RateReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$handle = fopen("php://output", 'w');
			fputcsv($handle, ['Partner', 'Local Transfer', 'Transfer type', 'Minimum km', 'Extra rate/km', 'Cab Type', 'Vendor Amount', 'Total Fare']);
			foreach ($rows as $row)
			{
				$rowArray							 = array();
				$rowArray['partnerName']			 = ($row['partnerName'] != '')? $row['partnerName'] : 'B2C';
				$rowArray['localName']			     = $row['localName'];
				$rowArray['transferType']			 = $row['transferType'];
				$rowArray['ltp_minimum_km']			 = $row['ltp_minimum_km'];
				$rowArray['ltp_extra_per_km_rate']	 = $row['ltp_extra_per_km_rate'];
				$rowArray['vehicleType']			 = $row['vehicleType'];
				$rowArray['ltp_vendor_amount']		 = $row['ltp_vendor_amount'];
				$rowArray['ltp_total_fare']			 = $row['ltp_total_fare'];
				$row1								 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit();
		}

		$dataProvider							 = $model::getList($arr);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->render('localTransferList', [
			'model'			 => $model,
			'dataProvider'	 => $dataProvider,
		]);
	}

	public function actionAddLocal()
	{
		$localTransferId			 = Yii::app()->request->getParam('ltpid', 0);
		$this->pageTitle = 'Local Transfer';
		$localTransferList	 = Cities::getRailwayBusList();
		$model			 = new LocalTransferPackage();
		if ($localTransferId > 0)
		{
			$model = LocalTransferPackage::model()->findByPk($localTransferId);
		}

		$postdata = Yii::app()->request->getParam('LocalTransferPackage');
		if (isset($postdata))
		{

			if (isset($postdata['ltp_id']) && $postdata['ltp_id'] > 0)
			{
				$model = LocalTransferPackage::model()->findByPk($postdata['ltp_id']);
				if (!$model)
				{
					return false;
				}
				unset($postdata['ltp_id']);
			}
			$remark = $model->ltp_log;

			$oldData = $model->attributes;
			unset($oldData['ltp_log']);
			unset($oldData['ltp_created_on']);
			unset($oldData['ltp_modified_on']);
			unset($oldData['ltp_active']);

			$model->attributes	 = $postdata;
			$model->ltp_parking_included = $postdata['ltp_parking_included']; 
			$dt					 = DBUtil::getCurrentTime();
			$user				 = UserInfo::getUserId();

			if (is_string($remark))
			{
				$newcomm = CJSON::decode($remark);
				//if ($remark != '' && CJSON::decode($remark) == '')
				if ($remark != '')
				{
					$newcomm = array(array(0 => $user, 1 => $dt, 2 => $remark));
				}
			}
			else if (is_array($remark))
			{
				$newcomm = $remark;
			}
			if ($newcomm == false)
			{
				$newcomm = array();
			}
			while (count($newcomm) >= 50)
			{
				array_pop($newcomm);
			}

			$model->ltp_modified_on = $dt;

			if (!$oldData['ltp_id'] == null)
			{
				unset($oldData['ltp_id']);
				$new_remark = ['Old values' => array_diff_assoc($oldData, $postdata)];
			}
			else
			{
				$new_remark = ['New Insert'];
			}
			array_unshift($newcomm, array(0 => $user, 1 => $dt, 2 => $new_remark));
			$model->ltp_log = CJSON::encode($newcomm);

			if ($model->save())
			{
				$this->redirect(array("locallist"));
			}
		}
		$this->render('addLocalTransfer', array(
			'model'			 => $model,
			'localTransferList'	 => $localTransferList,
		));
	}

	public function actionDeleteLocal()
	{
		$id = Yii::app()->request->getParam('ltpid');
		if ($id != '')
		{
			$model				 = LocalTransferPackage::model()->findByPk($id);
			$model->ltp_active	 = 0;
			$model->save();
		}
		$this->redirect(array('locallist'));
	}

}
