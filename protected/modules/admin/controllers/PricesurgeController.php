<?php

class PricesurgeController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';

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
			//	['allow', 'actions' => ['surgeform'], 'roles' => ['pricesurge']],
			['allow', 'actions' => ['list', 'showlog','arealist'], 'roles' => ['surgeList']],
			['allow', 'actions' => ['surgeform', 'delete1', 'getNearByZone', 'getZoneArr','areasurgeform','areadelete1'], 'roles' => ['surgeUpdate']],
			['deny', 'users' => ['*']],
		);
	}

	public function actionSurgeForm12()
	{
		$this->pageTitle = "Add Price Surge";
		$id				 = Yii::app()->request->getParam('id');
		$model			 = new PriceSurge('insert');
		if ($id > 0)
		{
			$model = PriceSurge::model()->findByPk($id);

			$remark = $model->prc_log;
		}
		if (isset($_POST['PriceSurge']))
		{
			Logger::create("Price Surge : " . print_r($_POST, true), CLogger::LEVEL_TRACE);
			exit;

			$model->attributes = Yii::app()->request->getParam('PriceSurge');

			$dateTime	 = date('Y-m-d H:i:s');
			$user		 = Yii::app()->user->getId();

			if ($model->prc_from_date != '' && $model->prc_to_date != '')
			{
				$fromdate				 = date('Y-m-d', strtotime($model->prc_from_date));
				$fromtime				 = date('H:i:s', strtotime('00:00:00'));
				$model->prc_from_date	 = $fromdate . " " . $fromtime;
				$todate					 = date('Y-m-d', strtotime($model->prc_to_date));
				$totime					 = date('H:i:s', strtotime('23:59:59'));
				$model->prc_to_date		 = $todate . " " . $totime;
				if ($model->validate())
				{
					$model->prc_from_date		 = $fromdate . " " . $fromtime;
					$model->prc_to_date			 = $todate . " " . $totime;
					$model->prc_source_zone		 = $_REQUEST['prc_source_zone'];
					$model->prc_destination_zone = $_REQUEST['prc_destination_zone'];
					$new_remark					 = $model->prc_value;
					if ($new_remark != '')
					{
						if (is_string($remark))
						{
							$newcomm = CJSON::decode($remark);
							if ($remark != '' && CJSON::decode($remark) == '')
							{
								$newcomm = array(array(0 => $user, 1 => $model->prc_created, 2 => $remark));
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
						while (count($newcomm) >= 10)
						{
							array_pop($newcomm);
						}
						array_unshift($newcomm, array(0 => $user, 1 => $dateTime, 2 => $new_remark));
						$model->prc_log = CJSON::encode($newcomm);
					}


					$model->save();
					Yii::app()->user->setFlash('success', "Price Surge Added Successfully");
				}
			}
			else
			{
				Yii::app()->user->setFlash('error', "Date range can not be blank");
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('form', array('model' => $model), false, $outputJs);
	}

	/**
	 * This function is used for editing and adding price surge
	 * Case 1: If Id exists, makes the edit flag on and sends the id details to view
	 * Case 2: If Id doesn't exists, keeps the edit flag off and makes a new entry
	 */
	public function actionSurgeForm()
	{

//$includedCabCategories	 = !empty($receivedRouteDetails["rut_included_cabCategories"]) ? implode(",", $receivedRouteDetails["rut_included_cabCategories"]) : "";
//$includedCabtires		 = !empty($receivedRouteDetails["rut_included_cabtires"]) ? implode(",", $receivedRouteDetails["rut_included_cabtires"]) : "";


		$this->pageTitle = "Add Price Surge"; //Sets the page title
		$priceSurgeModel = new PriceSurge("insert"); //Model validation for price surge insert
		$requestInstance = Yii::app()->request; //Intialising the request instance


		$priceSurgeId = $requestInstance->getParam("id");

		$editFlag = false;

		if ($priceSurgeId > 0)
		{
			$editFlag		 = true;
			$priceSurgeModel = PriceSurge::model()->findByPk($priceSurgeId); //Returns the data to the view
		}

		//Checks whether POST request has been received or not
		if (!$requestInstance->isPostRequest || empty($requestInstance->getParam("PriceSurge")))
		{
			goto skipAllCode;
		}

		$priceSurgeDetails			 = $requestInstance->getParam("PriceSurge"); //Price surge details
		$priceSurgeSourceZone		 = ($requestInstance->getParam("PriceSurge")['sourceZones']) ? ($requestInstance->getParam("PriceSurge")['sourceZones']) : NULL; //$requestInstance -> getParam("prc_source_zone"); //Source Zone
		$priceSurgeDestinationZone	 = ($requestInstance->getParam("PriceSurge")['destinationZones']) ? ($requestInstance->getParam("PriceSurge")['destinationZones']) : NULL; //$requestInstance ->getParam("prc_destination_zone"); //Destination Zone
		//Response object for ajax or api call
		$responseObject				 = new stdClass();
		$responseObject->success	 = false;

		$successMessage = array
			(
			0	 => "Failed to add price surge details",
			1	 => "Successfully added the price surge details",
			2	 => "Date range cannot be blank",
			3	 => "Failed to update price surge details",
			4	 => "Successfully updated the price surge details",
		);
		//New Price surge addition
		if (!$editFlag)
		{
			if (empty($priceSurgeDetails["prc_from_date"]) || empty($priceSurgeDetails["prc_to_date"]))
			{
				$responseObject->message = $successMessage[2];
				$flashMessage			 = array
					(
					0	 => "error",
					1	 => $successMessage[2]
				);

				goto skipCheckAndReturn;
			}

			$returnState = PriceSurge::addUpdatePriceSurge($priceSurgeDetails, null, $priceSurgeSourceZone, $priceSurgeDestinationZone);

			if ($returnState)
			{
				//For Ajax
				$responseObject->message = $successMessage[1];
				//For Form Submit
				$flashMessage			 = array
					(
					0	 => "success",
					1	 => $successMessage[1]
				);
				$priceSurgeModel		 = $returnState;
			}
			else
			{
				$responseObject->message = $successMessage[0];

				$flashMessage = array
					(
					0	 => "error",
					1	 => $successMessage[0]
				);
			}
			goto skipCheckAndReturn;
		}
		//Edit Price surge
		{
			if (empty($priceSurgeDetails["prc_from_date"]) || empty($priceSurgeDetails["prc_to_date"]))
			{
				$responseObject->message = $successMessage[2];
				$flashMessage			 = array
					(
					0	 => "error",
					1	 => $successMessage[2]
				);

				goto skipCheckAndReturn;
			}

			$returnState = PriceSurge::addUpdatePriceSurge($priceSurgeDetails, $priceSurgeId, $priceSurgeSourceZone, $priceSurgeDestinationZone);

			if ($returnState)
			{
				$responseObject->message = $successMessage[4];
				$flashMessage			 = array
					(
					0	 => "success",
					1	 => $successMessage[4]
				);
				$priceSurgeModel		 = $returnState;
			}
			else
			{
				$responseObject->message = $successMessage[3];

				$flashMessage = array
					(
					0	 => "error",
					1	 => $successMessage[3]
				);
			}

			goto skipCheckAndReturn;
		}

		skipCheckAndReturn:
		Yii::app()->user->setFlash($flashMessage[0], $flashMessage[1]); //Send response on form submit

		if (Yii::app()->request->isAjaxRequest)
		{
			echo CJSON::encode($responseObject);
			Yii::app()->end();
		}

		skipAllCode:
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");

		$this->$method('form', array('model' => $priceSurgeModel), false, $outputJs);
	}

	public function actionList()
	{
		$this->pageTitle = "Price Surge List";
		$model			 = new PriceSurge();
		$model->scenario = "search";

		$dynamicSurge	 = new DynamicSurge();
		$goldenMarkup	 = new GoldenMarkup();

		$arrZones = Zones::model()->getZoneList1();
		if (isset($_REQUEST['PriceSurge']))
		{
			$arrPriceSurge		 = Yii::app()->request->getParam('PriceSurge');
			$model->attributes	 = $arrPriceSurge;
			$model->overrideType = $arrPriceSurge['overrideType'];
			$model->isGozoNow	 = isset($arrPriceSurge['isGozoNow'][0]);

			if ($model->prc_from_date != '')
			{
				$model->prc_from_date = DateTimeFormat::DatePickerToDate($model->prc_from_date);
			}
			if ($model->prc_to_date != '')
			{
				$model->prc_to_date = DateTimeFormat::DatePickerToDate($model->prc_to_date);
			}
		}
		//$dataProvider = $model->search();
		if (isset($_REQUEST['export']) && $_REQUEST['export'] == "true")
		{
			$prcFromDate					 = Yii::app()->request->getParam('prc_from_date');
			$model->prc_from_date			 = ($prcFromDate != '')?DateTimeFormat::DatePickerToDate($prcFromDate): "";
			$model->prc_source_city			 = Yii::app()->request->getParam('prc_source_city');
			$model->prc_destination_city	 = Yii::app()->request->getParam('prc_destination_city');
			$model->prc_source_zone			 = Yii::app()->request->getParam('prc_source_zone');
			$model->prc_destination_zone	 = Yii::app()->request->getParam('prc_destination_zone');
			$model->prc_source_state		 = Yii::app()->request->getParam('prc_source_state');
			$model->prc_destination_state	 = Yii::app()->request->getParam('prc_destination_state');
			$model->prc_is_available		 = Yii::app()->request->getParam('prc_is_available');
			$model->prc_vehicle_type		 = Yii::app()->request->getParam('prc_vehicle_type');
			$model->prc_trip_type			 = Yii::app()->request->getParam('prc_trip_type');
			$model->overrideType			 = Yii::app()->request->getParam('overrideType');
			$model->prc_surge_reason		 = Yii::app()->request->getParam('prc_surge_reason');
			$model->isGozoNow				 = Yii::app()->request->getParam('isGozoNow');
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"PriceSurgeList_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename	 = "PriceSurgeList_" . date('Ymdhis') . ".csv";
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
			$rows	 = $model->getList(DBUtil::ReturnType_Query);
			$handle	 = fopen("php://output", 'w');
			fputcsv($handle, ['Price Rule Id', 'From Date', 'To Date', 'Value', 'Value Type',
				'Source City', 'Destination City', 'Source Zone', 'Destination Zone', 'Vehicle Type', 'Trip Type', 'Region',
				'Source State', 'Destination State', 'Surge Reason', 'Availabile', 'Priority Score', 'Description']);
			foreach ($rows as $row)
			{
				$rowArray							 = array();
				$rowArray['prc_id']					 = $row['prc_id'];
				$rowArray['prc_from_date']			 = $row['prc_from_date'];
				$rowArray['prc_to_date']			 = $row['prc_to_date'];
				$rowArray['prc_value']				 = $row['prc_value'];
				$rowArray['prc_value_type']			 = ($row['prc_value_type'] == 1) ? "Amount" : "Percentage";
				$rowArray['prc_source_city']		 = $row['source_cty_name'];
				$rowArray['prc_destination_city']	 = $row['dest_cty_name'];
				$rowArray['prc_source_zone']		 = Zones::getNamesByIds($arrZones, $row['prc_source_zone']);
				$rowArray['prc_destination_zone']	 = Zones::getNamesByIds($arrZones, $row['prc_destination_zone']);
				if ($row["prc_vehicle_type"] > 0)
				{
					$prcVehicleType = SvcClassVhcCat::getVctSvcList("string", 0, 0, $row["prc_vehicle_type"]);
				}
				else
				{
					$prcVehicleType = "";
				}
				$rowArray['prc_vehicle_type'] = $prcVehicleType;
				if ($row["prc_trip_type"] > 0)
				{
					$prcTripType = Booking::model()->getBookingType($row["prc_trip_type"]);
				}
				else
				{
					$prcTripType = "";
				}
				$rowArray['prc_trip_type'] = $prcTripType;
				if ($row["prc_region"] > 0)
				{
					$prcRegion = States::model()->findRegionName($row["prc_region"]);
				}
				else
				{
					$prcRegion = "";
				}
				$rowArray['prc_region']				 = $prcRegion;
				$rowArray['prc_source_state']		 = $row["source_stt_name"];
				$rowArray['prc_destination_state']	 = $row["dest_stt_name"];

				if($row["prc_surge_reason"] != '' || $row["prc_surge_reason"] > 0)
				{
					$surgeReason = PriceSurge::getSurgeReason($row["prc_surge_reason"]);
				}
				else
				{
					$surgeReason = "";
				}
				$rowArray['prc_surge_reason']		 = $surgeReason;
				$rowArray['prc_is_available']		 = ($row["prc_is_available"] == 0) ? "No" : "Yes";
				$rowArray['prc_priority_score']		 = $row["prc_priority_score"];
				$rowArray['prc_desc']				 = $row[prc_desc];
				$row1								 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			exit;
		}

		$dataProvider = $model->getList();
		/* @var $dyanicSurge values */
		if ($model->prc_source_city != '' && $model->prc_destination_city != '' && $model->prc_from_date != '')
		{
			$dynamicSurge->calculate(1500, $model->prc_source_city, $model->prc_destination_city, $model->prc_from_date, $tripType		 = 1);
			$result			 = $goldenMarkup->fetchData($model->prc_source_city, $model->prc_destination_city, $model->prc_from_date, 100, NULL, NULL);
			$profitability	 = ProfitabilitySurge::fetchData($model->prc_source_city, $model->prc_destination_city, 100, NULL, NULL);
		}

		if ($model->prc_from_date != '')
		{
			$model->prc_from_date = DateTimeFormat::DateToDatePicker($model->prc_from_date);
		}
		if ($model->prc_to_date != '')
		{
			$model->prc_to_date = DateTimeFormat::DateToDatePicker($model->prc_to_date);
		}

		$pickupDate = $model->prc_from_date;
		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
//	$this->render('list', array('dataProvider' => $dataProvider, 'model' => $model));

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");

		$this->$method('list', array('model' => $model, 'dataProvider' => $dataProvider, 'pickupdate' => $pickupDate, 'dynamicSurge' => $dynamicSurge, 'goldenMarkup' => $result, 'profitability' => $profitability, 'arrZones' => $arrZones), false, $outputJs);
	}

	public function actionDelete1()
	{
		$id = Yii::app()->request->getParam('id');
		if ($id > 0)
		{
			$dateTime	 = date('Y-m-d H:i:s');
			$user		 = Yii::app()->user->getId();
			$model		 = PriceSurge::model()->findByPk($id);
			$remark		 = $model->prc_log;
			$new_remark	 = 'Deleted';
			if ($new_remark != '')
			{
				if (is_string($remark))
				{
					$newcomm = CJSON::decode($remark);
					if ($remark != '' && CJSON::decode($remark) == '')
					{
						$newcomm = array(array(0 => $user, 1 => $model->prc_created, 2 => $remark));
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
				while (count($newcomm) >= 5)
				{
					array_pop($newcomm);
				}
				array_unshift($newcomm, array(0 => $user, 1 => $dateTime, 2 => $new_remark));
				$model->prc_log = CJSON::encode($newcomm);
			}

			$model->prc_active	 = 0;
			$success			 = $model->save();
			$data				 = ['success' => $success];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

	public function actionShowlog()
	{
		$this->pageTitle = "Surge Log";
		//$pageSize	 = Yii::app()->params['listPerPage'];
		$id				 = Yii::app()->request->getParam('id', 0);
		$model			 = new PriceSurge();

//	if (isset($_REQUEST['BookingLog']))
//	{
//	    $model->attributes = Yii::app()->request->getParam('BookingLog');
//	}
		$logArr = $model->getLogbyId($id);

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('showlog', array('logArr' => $logArr), false, $outputJs);
	}

	public function actionGetNearByZone()
	{
		$zoneId = Yii::app()->request->getParam('id');

		$model	 = new Zones();
		$data	 = $model->getNearByZone($zoneId, $existids);
		echo CJSON::encode($data);
		Yii::app()->end();
	}

	public function actionGetZoneArr()
	{
		$ids	 = Yii::app()->request->getParam('ids');
		$model	 = new Zones();
		$data	 = $model->getZonesEdit($ids);
		echo CJSON::encode($data);
		Yii::app()->end();
	}


	public function actionAreaSurgeForm()
	{
		$this->pageTitle = "Add Area Surge"; //Sets the page title
		$areaSurgeModel = new AreaSurgeFactor("insert"); //Model validation for Area surge insert
		$requestInstance = Yii::app()->request; //Intialising the request instance


		$areaSurgeId = $requestInstance->getParam("id");

		$editFlag = false;

		if ($areaSurgeId > 0)
		{
			$editFlag		 = true;
			$areaSurgeModel = AreaSurgeFactor::model()->findByPk($areaSurgeId); //Returns the data to the view
		}

		//Checks whether POST request has been received or not
		if (!$requestInstance->isPostRequest || empty($requestInstance->getParam("AreaSurgeFactor")))
		{
			goto skipAllCode;
		}

		$areaSurgeDetails			 = $requestInstance->getParam("AreaSurgeFactor"); //Area surge details
		$areaSurgeDetails['asf_from_area_id']	 = ($areaSurgeDetails['asf_from_area_type'] != 3) ? $areaSurgeDetails['asf_from_area_id'] : $areaSurgeDetails['asf_area_id1'];
		$areaSurgeDetails['asf_to_area_id']		 = ($areaSurgeDetails['asf_to_area_type'] != 3) ? $areaSurgeDetails['asf_to_area_id'] : $areaSurgeDetails['asf_area_id2'];

		//Response object for ajax or api call
		$responseObject				 = new stdClass();
		$responseObject->success	 = false;

		$successMessage = array
			(
			0	 => "Failed to add area surge details",
			1	 => "Successfully added the area surge details",
			2	 => "Duplicate record cannot be added",
			3	 => "Failed to update area surge details",
			4	 => "Successfully updated the area surge details",
		);

		
		#################################### New Area surge addition #################################################
		if (!$editFlag)
		{
			$returnState = AreaSurgeFactor::addUpdateAreaSurge($areaSurgeDetails, null);

			if ($returnState)
			{
				//For Ajax
				$responseObject->message = $successMessage[1];
				//For Form Submit
				$flashMessage			 = array
					(
					0	 => "success",
					1	 => $successMessage[1]
				);
				$areaSurgeModel		 = $returnState;
			}
			else
			{
				$responseObject->message = $successMessage[0];

				$flashMessage = array
					(
					0	 => "error",
					1	 => $successMessage[0]
				);
			}
			goto skipCheckAndReturn;
		}
		################################## Edit Area surge ###########################################################
		$returnState = AreaSurgeFactor::addUpdateAreaSurge($areaSurgeDetails, $areaSurgeId);

		if ($returnState)
		{
			$responseObject->message = $successMessage[4];
			$flashMessage			 = array
				(
				0	 => "success",
				1	 => $successMessage[4]
			);
			$areaSurgeModel			 = $returnState;
		}
		else
		{
			$responseObject->message = $successMessage[3];

			$flashMessage = array
				(
				0	 => "error",
				1	 => $successMessage[3]
			);
		}

		skipCheckAndReturn:
		Yii::app()->user->setFlash($flashMessage[0], $flashMessage[1]); //Send response on form submit

		if (Yii::app()->request->isAjaxRequest)
		{
			echo CJSON::encode($responseObject);
			Yii::app()->end();
		}

		skipAllCode:
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('areaform', array('model' => $areaSurgeModel), false, $outputJs);
	}

	public function actionAreaList()
	{
		$this->pageTitle = "Area Surge List";
		$model			 = new AreaSurgeFactor();
		$model->scenario = "search";

		if (isset($_REQUEST['AreaSurgeFactor']))
		{
			$arrAreaSurge						 = Yii::app()->request->getParam('AreaSurgeFactor');
			$arrAreaSurge['asf_from_area_id']	 = ($arrAreaSurge['asf_from_area_type'] != 3) ? $arrAreaSurge['asf_from_area_id'] : $arrAreaSurge['asf_area_id1'];
			$arrAreaSurge['asf_to_area_id']		 = ($arrAreaSurge['asf_to_area_type'] != 3) ? $arrAreaSurge['asf_to_area_id'] : $arrAreaSurge['asf_area_id2'];
			$model->attributes					 = $arrAreaSurge;
		}

		$dataProvider = $model->getList();

		$dataProvider->setSort(['params' => array_filter($_REQUEST)]);
		$dataProvider->setPagination(['params' => array_filter($_REQUEST)]);
//	$this->render('list', array('dataProvider' => $dataProvider, 'model' => $model));

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");

		$this->$method('arealist', array('model' => $model, 'dataProvider' => $dataProvider), false, $outputJs);
	}

	public function actionAreaDelete1()
	{
		$id = Yii::app()->request->getParam('id');
		if ($id > 0)
		{
			$model				 = AreaSurgeFactor::model()->findByPk($id);
			$model->asf_active	 = 0;
			$success			 = $model->save();
			$data				 = ['success' => $success];
			echo json_encode($data);
			Yii::app()->end();
		}
	}

}
