<?php

class RouteController extends Controller
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
			['allow', 'actions' => ['list', 'routerate', 'showlog', 'overidedynamicrut'], 'roles' => ['routeList']],
			['allow', 'actions' => ['add'], 'roles' => ['routeAdd', 'routeEdit']],
			['allow', 'actions' => ['changestatus'], 'roles' => ['routeChangeStatus']],
			array('allow', 'actions' => array('addtype', 'routename', 'json'), 'users' => array('@')),
			array('allow', 'actions' => array('index', 'getroutename', 'populate', 'demandreport'), 'users' => array('*')),
			array('deny', 'users' => array('*')),
		);
	}

	public function actionAddtype()
	{
		$model = new Route();
		if (isset($_REQUEST['Route']))
		{
			$arr1					 = Yii::app()->request->getParam('VehicleTypes');
			$model->attributes		 = $arr1;
			$chkvalue				 = $arr1['rfuelType'];
			$model->vht_fuel_type	 = $chkvalue;
			$model->save();
			$status					 = "added";
			$this->redirect(array('list'));
		}
		$this->render('addtype', array('model'	 => $model,
			'post'	 => $_POST
		));
	}

	/**
	 * @deprecated since version 10-09-2019
	 * This function has been was used to add and edit route details
	 * New Function : actionAdd()
	 */
	public function actionAddOld()
	{
		$this->pageTitle = "Add Route";

		$rid = Yii::app()->request->getParam('rid');
		//$model = Route::model()->rateByRoutes($rid);

		$oldData = false;
		if ($rid > 0)
		{
			$this->pageTitle = "Edit Route";
			$ftype			 = 'Modify';

			$model = Route::model()->findByPk($rid);

			$hr	 = date('G', mktime(0, $model->rut_estm_time)) . " Hr";
			$min = (date('i', mktime(0, $model->rut_estm_time)) != '00') ? ' ' . date('i', mktime(0, $model->rut_estm_time)) . " min" : '';

			$model->rut_estm_time_min		 = $hr . $min;
			$model->rut_estm_distance_exp	 = $model->rut_estm_distance;

			$oldData = $model->attributes;
			//$model->rut_name = $_REQUEST['Route']['rut_name'];
		}
		else
		{
			$ftype		 = 'Add';
			$model		 = new Route();
			$modelreturn = new Route();
		}

		if (isset($_REQUEST['Route']))
		{
			$obj			 = new stdClass();
			$obj->success	 = false;
			$models			 = [];

			$arr1				 = Yii::app()->request->getParam('Route');
			$model->attributes	 = Yii::app()->request->getParam('Route');

			$cabTypes = $_REQUEST['Route']['rut_excluded_cabtypes'];
			if ($cabTypes != '')
			{
				$excluded_cabTypes				 = implode(',', $cabTypes);
				$model->rut_excluded_cabtypes	 = $excluded_cabTypes;
				$model->rut_excluded_cabtypes	 = str_replace(' ', '', $model->rut_excluded_cabtypes);
			}
			else
			{
				$model->rut_excluded_cabtypes = $cabTypes;
			}

			$model->rut_special_remarks = $arr1['rut_special_remarks'];

			$newData = $model->attributes;

			$models[]	 = $model;
			$check		 = Yii::app()->request->getParam('returncheck');

			if ($check == 1)
			{
				$modelreturn->attributes			 = Yii::app()->request->getParam('Route');
				$modelreturn->rut_special_remarks	 = $arr1['rut_special_remarks'];
				$modelreturn->rut_from_city_id		 = $model->rut_to_city_id;
				$modelreturn->rut_to_city_id		 = $model->rut_from_city_id;
				if ($modelreturn->isNewRecord)
				{
					$arr					 = Yii::app()->request->getParam('Route');
					$modelreturn->rut_name	 = $arr['rut_return_name'];
				}
				$models[] = $modelreturn;
			}
			$result = CActiveForm::validate($models, null, false);
			if ($result == '[]')
			{
				$obj->success = true;
				if ($model->scenario == 'update')
				{
					$model->rut_log = $model->addLog($oldData, $newData);
				}
				$model->save();


//                                if($_REQUEST['rut_keyword_names'] != '')
//                                {
				$keyword_list	 = [];
				$keyword_chk	 = Keywords::model()->getKeywordList();
				$RecordType		 = array_diff(explode(',', $_REQUEST['rut_keyword_names']), $keyword_chk);
				foreach ($RecordType as $val)
				{
					$modelkeyword				 = new Keywords();
					$modelkeyword->keyword_name	 = $val;
					$modelkeyword->save();
				}

				$arr_imp = explode(',', $_REQUEST['rut_keyword_names']);

				foreach ($arr_imp as $val)
				{
					$rut_keywords = Keywords::model()->getKeywordIdsByName($val);
					array_push($keyword_list, $rut_keywords[0]);
				}
				$model->rut_keyword_names	 = implode(',', $keyword_list);
				$model->rut_keyword_names	 = str_replace(' ', '', $model->rut_keyword_names);
				$model->save();
				// }
				$obj->id					 = $model->rut_id;
				if ($check == 1)
				{
					$modelreturn->save();
				}
				$success = 1;
			}

			if (Yii::app()->request->isAjaxRequest)
			{
				$obj->data = json_decode($result);
				echo CJSON::encode($obj);
				Yii::app()->end();
			}



			if ($success == 1 && $successreturn == 1)
			{
				$msg = "Successfully Added";
			}
			if ($success == 0 && $successreturn == 0)
			{
				$msg = "Already Exist";
			}
			if ($success == 0 && $successreturn == 1)
			{
				$msg = "Return Route Successfully Added";
			}
			if ($success == 1 && $successreturn == 0)
			{
				$msg = "Successfully Added Route & Return Route Already Added";
			}
			if (Yii::app()->request->isAjaxRequest)
			{
				echo true;
				Yii::app()->end();
			}
			$this->redirect(array('list', 'success' => $msg));
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('add', array('model' => $model, 'isNew' => $ftype), false, $outputJs);
	}

	/**
	 * This function is used for edit and addition of routes
	 */
	public function actionAdd()
	{
		$this->pageTitle = "Add Route"; //Sets the page title
		$requestInstance = Yii::app()->request; //Creates request instance

		$routeId = $requestInstance->getParam("rid");

		$editDataFlag = false;

		$ftype		 = "Add";
		$model		 = new Route();
		$modelreturn = new Route();

		if ($routeId > 0)
		{
			$editDataFlag = true; //Enabling the old data flag as true when data is in edit mode.

			$this->pageTitle = "Edit Route";
			$ftype			 = "Modify";

			$model = Route::model()->findByPk($routeId);

			$hr	 = date("G", mktime(0, $model->rut_estm_time)) . " Hr";
			$min = (date("i", mktime(0, $model->rut_estm_time)) != "00") ? ' ' . date("i", mktime(0, $model->rut_estm_time)) . " min" : '';

			$model->rut_estm_time_min		 = $hr . $min;
			$model->rut_estm_distance_exp	 = $model->rut_estm_distance;

			$oldData = $model->attributes;
			//$model->rut_name = $_REQUEST['Route']['rut_name'];
		}

		//Checks whether POST request has been received or not
		if (!$requestInstance->isPostRequest || empty($requestInstance->getParam("Route")))
		{
			goto skipAllCode;
		}

		$receivedRouteDetails = $requestInstance->getParam("Route"); //Received details from ajax form post

		/**
		 * 1 => Add return route
		 * 0 => Skip return route addition
		 */
		$addReturnRouteCheck = $requestInstance->getParam("returncheck");

		$toCityId	 = $receivedRouteDetails["rut_to_city_id"];
		$fromCityId	 = $receivedRouteDetails["rut_from_city_id"];

		//Deafult message success message
		$successMessage = array
			(
			0	 => "Already Exist",
			1	 => "Return Route Successfully Added",
			2	 => "Successfully Added Route & Return Route Already Added",
			3	 => "Successfully Added",
			4	 => "Successfully updated the records"
		);

		$responseObject			 = new stdClass();
		$responseObject->success = false;

		if (!$editDataFlag)
		{
			$isStartRouteExists	 = Route:: checkRouteExists($toCityId, $fromCityId, 1, "boolean"); //Checks whether this route exists or not
			$isReturnRouteExists = Route:: checkRouteExists($toCityId, $fromCityId, 2, "boolean"); //Checks whether its respective return route exists or not

			/**
			 * Case 1: Both the route exists can't add further
			 * Case 2: Start Route don't exists but Return route exists
			 * Case 3: Start Route exists but Return route doesn't exists and also add return route check is enabled
			 * Case 4: Both route don't exists
			 */
			//Cass 1:11
			if ($isReturnRouteExists && $isStartRouteExists)
			{
				$responseObject->message = $successMessage[0];
				goto skipCheckAndReturn;
			}

			//Case 2:01
			if (!$isStartRouteExists && $isReturnRouteExists)
			{
				$exculedCabTypesArray	 = !empty($receivedRouteDetails["rut_excluded_cabtypes"]) ? implode(",", $receivedRouteDetails["rut_excluded_cabtypes"]) : "";
				$includedCabCategories	 = !empty($receivedRouteDetails["rut_included_cabCategories"]) ? implode(",", $receivedRouteDetails["rut_included_cabCategories"]) : "";
				$includedCabtires		 = !empty($receivedRouteDetails["rut_included_cabtires"]) ? implode(",", $receivedRouteDetails["rut_included_cabtires"]) : "";
				$includedCabModels		 = !empty($receivedRouteDetails["rut_included_cabmodels"]) ? implode(",", $receivedRouteDetails["rut_included_cabmodels"]) : "";



				if (!empty($receivedRouteDetails["rut_keyword_names"]))
				{
					Keywords::validateAndAddKeywords($receivedRouteDetails["rut_keyword_names"]);
					$keywordIds = Keywords::getKeyWordIdList($receivedRouteDetails["rut_keyword_names"]);
				}

				$newRoute = new Route();

				$newRoute->rut_name						 = $receivedRouteDetails["rut_name"];
				$newRoute->rut_special_remarks			 = $receivedRouteDetails["rut_special_remarks"];
				$newRoute->rut_from_city_id				 = $fromCityId;
				$newRoute->rut_to_city_id				 = $toCityId;
				$newRoute->rut_actual_distance			 = $receivedRouteDetails["rut_actual_distance"];
				$newRoute->rut_actual_time				 = $receivedRouteDetails["rut_actual_time"];
				$newRoute->rut_estm_distance			 = $receivedRouteDetails["rut_estm_distance_exp"];
				$newRoute->rut_estm_time				 = $receivedRouteDetails["rut_estm_time"];
				$newRoute->rut_excluded_cabtypes		 = $exculedCabTypesArray; //This are all new svc_id
				$newRoute->rut_keyword_names			 = $keywordIds;
				$newRoute->rut_is_promo_code_apply		 = $receivedRouteDetails["rut_is_promo_code_apply"];
				$newRoute->rut_is_promo_gozo_coins_apply = $receivedRouteDetails["rut_is_promo_gozo_coins_apply"];
				$newRoute->rut_is_cod_apply				 = $receivedRouteDetails["rut_is_cod_apply"];
				$newRoute->rut_info						 = $receivedRouteDetails["rut_info"];
				$newRoute->rut_special_remarks			 = $receivedRouteDetails["rut_special_remarks"];
				$newRoute->rut_active					 = 1;
				$newRoute->rut_included_cabCategories	 = $includedCabCategories;
				$newRoute->rut_included_cabtires		 = $includedCabtires;
				$newRoute->rut_included_cabmodels		 = $includedCabModels;
				$newRoute->save();

				$responseObject->message = $successMessage[2];
				goto skipCheckAndReturn;
			}

			//Case 3:10
			if (($isStartRouteExists && !$isReturnRouteExists) && $addReturnRouteCheck)
			{
				$exculedCabTypesArray	 = !empty($receivedRouteDetails["rut_excluded_cabtypes"]) ? implode(",", $receivedRouteDetails["rut_excluded_cabtypes"]) : "";
				$includedCabCategories	 = !empty($receivedRouteDetails["rut_included_cabCategories"]) ? implode(",", $receivedRouteDetails["rut_included_cabCategories"]) : "";
				$includedCabtires		 = !empty($receivedRouteDetails["rut_included_cabtires"]) ? implode(",", $receivedRouteDetails["rut_included_cabtires"]) : "";
				$includedCabModels		 = !empty($receivedRouteDetails["rut_included_cabmodels"]) ? implode(",", $receivedRouteDetails["rut_included_cabmodels"]) : "";
				if (!empty($receivedRouteDetails["rut_keyword_names"]))
				{
					Keywords::validateAndAddKeywords($receivedRouteDetails["rut_keyword_names"]);
					$keywordIds = Keywords::getKeyWordIdList($receivedRouteDetails["rut_keyword_names"]);
				}

				$returnRouteName = $receivedRouteDetails["rut_return_name"];
				if (empty($returnRouteName))
				{
					$fromCityName	 = Route::getRouteName($toCityId);
					$toCityName		 = Route::getRouteName($fromCityId);

					$returnRouteName = $toCityName . "-" . $fromCityName;
				}

				$newRoute = new Route();

				$newRoute->rut_name						 = $returnRouteName;
				$newRoute->rut_special_remarks			 = $receivedRouteDetails["rut_special_remarks"];
				$newRoute->rut_from_city_id				 = $toCityId;
				$newRoute->rut_to_city_id				 = $fromCityId;
				$newRoute->rut_actual_distance			 = $receivedRouteDetails["rut_actual_distance"];
				$newRoute->rut_actual_time				 = $receivedRouteDetails["rut_actual_time"];
				$newRoute->rut_estm_distance			 = $receivedRouteDetails["rut_estm_distance_exp"];
				$newRoute->rut_estm_time				 = $receivedRouteDetails["rut_estm_time"];
				$newRoute->rut_excluded_cabtypes		 = $exculedCabTypesArray; //This are all new svc_id
				$newRoute->rut_keyword_names			 = $keywordIds;
				$newRoute->rut_is_promo_code_apply		 = $receivedRouteDetails["rut_is_promo_code_apply"];
				$newRoute->rut_is_promo_gozo_coins_apply = $receivedRouteDetails["rut_is_promo_gozo_coins_apply"];
				$newRoute->rut_is_cod_apply				 = $receivedRouteDetails["rut_is_cod_apply"];
				$newRoute->rut_info						 = $receivedRouteDetails["rut_info"];
				$newRoute->rut_special_remarks			 = $receivedRouteDetails["rut_special_remarks"];
				$newRoute->rut_active					 = 1;
				$newRoute->rut_included_cabCategories	 = $includedCabCategories;
				$newRoute->rut_included_cabtires		 = $includedCabtires;
				$newRoute->rut_included_cabmodels		 = $includedCabModels;
				$newRoute->save();

				$responseObject->message = $successMessage[1];
				goto skipCheckAndReturn;
			}

			//Case 4:00
			if (!$isStartRouteExists && !$isReturnRouteExists)
			{
				$exculedCabTypesArray	 = !empty($receivedRouteDetails["rut_excluded_cabtypes"]) ? implode(",", $receivedRouteDetails["rut_excluded_cabtypes"]) : "";
				$includedCabCategories	 = !empty($receivedRouteDetails["rut_included_cabCategories"]) ? implode(",", $receivedRouteDetails["rut_included_cabCategories"]) : "";
				$includedCabtires		 = !empty($receivedRouteDetails["rut_included_cabtires"]) ? implode(",", $receivedRouteDetails["rut_included_cabtires"]) : "";
				$includedCabModels		 = !empty($receivedRouteDetails["rut_included_cabmodels"]) ? implode(",", $receivedRouteDetails["rut_included_cabmodels"]) : "";
				if (!empty($receivedRouteDetails["rut_keyword_names"]))
				{
					Keywords::validateAndAddKeywords($receivedRouteDetails["rut_keyword_names"]);
					$keywordIds = Keywords::getKeyWordIdList($receivedRouteDetails["rut_keyword_names"]);
				}

				//Start
				$newStartRoute = new Route();

				$newStartRoute->rut_name						 = $receivedRouteDetails["rut_name"];
				$newStartRoute->rut_special_remarks				 = $receivedRouteDetails["rut_special_remarks"];
				$newStartRoute->rut_from_city_id				 = $fromCityId;
				$newStartRoute->rut_to_city_id					 = $toCityId;
				$newStartRoute->rut_actual_distance				 = $receivedRouteDetails["rut_actual_distance"];
				$newStartRoute->rut_actual_time					 = $receivedRouteDetails["rut_actual_time"];
				$newStartRoute->rut_estm_distance				 = $receivedRouteDetails["rut_estm_distance_exp"];
				$newStartRoute->rut_estm_time					 = $receivedRouteDetails["rut_estm_time"];
				$newStartRoute->rut_excluded_cabtypes			 = $exculedCabTypesArray; //This are all new svc_id
				$newStartRoute->rut_keyword_names				 = $keywordIds;
				$newStartRoute->rut_is_promo_code_apply			 = $receivedRouteDetails["rut_is_promo_code_apply"];
				$newStartRoute->rut_is_promo_gozo_coins_apply	 = $receivedRouteDetails["rut_is_promo_gozo_coins_apply"];
				$newStartRoute->rut_is_cod_apply				 = $receivedRouteDetails["rut_is_cod_apply"];
				$newStartRoute->rut_info						 = $receivedRouteDetails["rut_info"];
				$newStartRoute->rut_special_remarks				 = $receivedRouteDetails["rut_special_remarks"];
				$newStartRoute->rut_active						 = 1;
				$newStartRoute->rut_included_cabCategories		 = $includedCabCategories;
				$newStartRoute->rut_included_cabtires			 = $includedCabtires;
				$newStartRoute->rut_included_cabmodels			 = $includedCabModels;
				$newStartRoute->save();

				if ($addReturnRouteCheck)
				{
					$returnRouteName = $receivedRouteDetails["rut_return_name"];
					if (empty($returnRouteName))
					{
						$fromCityName	 = Route::getRouteName($toCityId);
						$toCityName		 = Route::getRouteName($fromCityId);

						$returnRouteName = $toCityName . "-" . $fromCityName;
					}

					$newReturnRoute = new Route();

					$newReturnRoute->rut_name						 = $returnRouteName;
					$newReturnRoute->rut_special_remarks			 = $receivedRouteDetails["rut_special_remarks"];
					$newReturnRoute->rut_from_city_id				 = $toCityId;
					$newReturnRoute->rut_to_city_id					 = $fromCityId;
					$newReturnRoute->rut_actual_distance			 = $receivedRouteDetails["rut_actual_distance"];
					$newReturnRoute->rut_actual_time				 = $receivedRouteDetails["rut_actual_time"];
					$newReturnRoute->rut_estm_distance				 = $receivedRouteDetails["rut_estm_distance_exp"];
					$newReturnRoute->rut_estm_time					 = $receivedRouteDetails["rut_estm_time"];
					$newReturnRoute->rut_excluded_cabtypes			 = $exculedCabTypesArray; //This are all new svc_id
					$newReturnRoute->rut_keyword_names				 = $keywordIds;
					$newReturnRoute->rut_is_promo_code_apply		 = $receivedRouteDetails["rut_is_promo_code_apply"];
					$newReturnRoute->rut_is_promo_gozo_coins_apply	 = $receivedRouteDetails["rut_is_promo_gozo_coins_apply"];
					$newReturnRoute->rut_is_cod_apply				 = $receivedRouteDetails["rut_is_cod_apply"];
					$newReturnRoute->rut_info						 = $receivedRouteDetails["rut_info"];
					$newReturnRoute->rut_special_remarks			 = $receivedRouteDetails["rut_special_remarks"];
					$newReturnRoute->rut_active						 = 1;
					$newReturnRoute->rut_included_cabCategories		 = $includedCabCategories;
					$newReturnRoute->rut_included_cabtires			 = $includedCabtires;
					$newStartRoute->rut_included_cabmodels			 = $includedCabModels;
					$newReturnRoute->save();
				}

				$responseObject->message = $successMessage[3];
				goto skipCheckAndReturn;
			}
		}
		//If Edit route details are being edited
		else
		{
			$routeModel				 = Route::model()->findByPk($routeId);
			$routeModel->scenario;
			$exculedCabTypesArray	 = !empty($receivedRouteDetails["rut_excluded_cabtypes"]) ? implode(",", $receivedRouteDetails["rut_excluded_cabtypes"]) : "";
			$includedCabCategories	 = !empty($receivedRouteDetails["rut_included_cabCategories"]) ? implode(",", $receivedRouteDetails["rut_included_cabCategories"]) : "";
			$includedCabtires		 = !empty($receivedRouteDetails["rut_included_cabtires"]) ? implode(",", $receivedRouteDetails["rut_included_cabtires"]) : "";
			$includedCabModels		 = !empty($receivedRouteDetails["rut_included_cabmodels"]) ? implode(",", $receivedRouteDetails["rut_included_cabmodels"]) : "";
			if (!empty($receivedRouteDetails["rut_keyword_names"]))
			{
				Keywords::validateAndAddKeywords($receivedRouteDetails["rut_keyword_names"]);
				$keywordIds = Keywords::getKeyWordIdList($receivedRouteDetails["rut_keyword_names"]);
			}

			$routeModel->rut_name						 = $receivedRouteDetails["rut_name"];
			$routeModel->rut_special_remarks			 = $receivedRouteDetails["rut_special_remarks"];
			$routeModel->rut_from_city_id				 = $fromCityId;
			$routeModel->rut_to_city_id					 = $toCityId;
			$routeModel->rut_actual_distance			 = $receivedRouteDetails["rut_actual_distance"];
			$routeModel->rut_actual_time				 = $receivedRouteDetails["rut_actual_time"];
			$routeModel->rut_estm_distance				 = $receivedRouteDetails["rut_estm_distance"];
			$routeModel->rut_estm_time					 = $receivedRouteDetails["rut_estm_time"];
			$routeModel->rut_excluded_cabtypes			 = $exculedCabTypesArray; //This are all new svc_id
			$routeModel->rut_keyword_names				 = $keywordIds;
			$routeModel->rut_is_promo_code_apply		 = $receivedRouteDetails["rut_is_promo_code_apply"];
			$routeModel->rut_is_promo_gozo_coins_apply	 = $receivedRouteDetails["rut_is_promo_gozo_coins_apply"];
			$routeModel->rut_is_cod_apply				 = $receivedRouteDetails["rut_is_cod_apply"];
			$routeModel->rut_info						 = $receivedRouteDetails["rut_info"];
			$routeModel->rut_special_remarks			 = $receivedRouteDetails["rut_special_remarks"];
			$routeModel->rut_included_cabCategories		 = $includedCabCategories;
			$routeModel->rut_included_cabtires			 = $includedCabtires;
			$routeModel->rut_included_cabmodels			 = $includedCabModels;
			$routeModel->rut_log						 = Route::model()->addLog($oldData, $routeModel->attributes);
			if ($routeModel->hasErrors())
			{
				goto skipCheckAndReturn;
			}
			$routeModel->save();


			//Adds the return route if add return check is enabled
			if ($addReturnRouteCheck)
			{
				$returnRouteId = Route:: checkRouteExists($toCityId, $fromCityId, 2, "value"); //Finds the return route id

				$returnRouteModel = Route::model()->findByPk($returnRouteId);

				$exculedCabTypesArray	 = !empty($receivedRouteDetails["rut_excluded_cabtypes"]) ? implode(",", $receivedRouteDetails["rut_excluded_cabtypes"]) : "";
				$includedCabCategories	 = !empty($receivedRouteDetails["rut_included_cabCategories"]) ? implode(",", $receivedRouteDetails["rut_included_cabCategories"]) : "";
				$includedCabtires		 = !empty($receivedRouteDetails["rut_included_cabtires"]) ? implode(",", $receivedRouteDetails["rut_included_cabtires"]) : "";
				$includedCabModels		 = !empty($receivedRouteDetails["rut_included_cabmodels"]) ? implode(",", $receivedRouteDetails["rut_included_cabmodels"]) : "";
				/**
				 * Checks whether keywords are new or old. 
				 * The new keywords are being added in the keyword list.
				 */
				if (!empty($receivedRouteDetails["rut_keyword_names"]))
				{
					Keywords::validateAndAddKeywords($receivedRouteDetails["rut_keyword_names"]);
					$keywordIds = Keywords::getKeyWordIdList($receivedRouteDetails["rut_keyword_names"]);
				}

				/**
				 * Checks whether return route name exists or not
				 * If not it creates the route name based on city ids
				 */
				$returnRouteName = $receivedRouteDetails["rut_return_name"];
				if (empty($returnRouteName))
				{
					$fromCityName	 = Route::getRouteName($toCityId);
					$toCityName		 = Route::getRouteName($fromCityId);

					$returnRouteName = $toCityName . "-" . $fromCityName;
				}

				$returnRouteModel->rut_name						 = $returnRouteName;
				$returnRouteModel->rut_special_remarks			 = $receivedRouteDetails["rut_special_remarks"];
				$returnRouteModel->rut_from_city_id				 = $toCityId;
				$returnRouteModel->rut_to_city_id				 = $fromCityId;
				$returnRouteModel->rut_actual_distance			 = $receivedRouteDetails["rut_actual_distance"];
				$returnRouteModel->rut_actual_time				 = $receivedRouteDetails["rut_actual_time"];
				$returnRouteModel->rut_estm_distance			 = $receivedRouteDetails["rut_estm_distance"];
				$returnRouteModel->rut_estm_time				 = $receivedRouteDetails["rut_estm_time"];
				$returnRouteModel->rut_excluded_cabtypes		 = $exculedCabTypesArray; //This are all new svc_id
				$returnRouteModel->rut_keyword_names			 = $keywordIds;
				$returnRouteModel->rut_is_promo_code_apply		 = $receivedRouteDetails["rut_is_promo_code_apply"];
				$returnRouteModel->rut_is_promo_gozo_coins_apply = $receivedRouteDetails["rut_is_promo_gozo_coins_apply"];
				$returnRouteModel->rut_is_cod_apply				 = $receivedRouteDetails["rut_is_cod_apply"];
				$returnRouteModel->rut_included_cabCategories	 = $includedCabCategories;
				$returnRouteModel->rut_included_cabtires		 = $includedCabtires;
				$returnRouteModel->rut_included_cabmodels		 = $includedCabModels;
				$returnRouteModel->rut_log						 = Route::model()->addLog($oldData, $receivedRouteDetails);

				$returnRouteModel->save();
			}

			$responseObject->message = $successMessage[4];
			goto skipCheckAndReturn;
		}

		skipCheckAndReturn:
		if (Yii::app()->request->isAjaxRequest)
		{
			$responseObject->data = $receivedRouteDetails;
			echo CJSON::encode($responseObject);
			Yii::app()->end();
		}

		$this->redirect(array("list"));

		skipAllCode:

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('add', array('model' => $model, 'isNew' => $ftype), false, $outputJs);
	}

	public function actionList()
	{
		$this->pageTitle = "Route List";
		$model = new Route('search');
		
		if (isset($_REQUEST['Route']))
		{
			$arr						 = Yii::app()->request->getParam('Route');
			$model->attributes			 = $arr;
			$model->rut_route_city_id	 = $arr['rut_route_city_id'];
			$model->rut_source_zone		 = $arr['rut_source_zone'];
			$model->rut_destination_zone = $arr['rut_destination_zone'];
		}
		$dataProvider = $model->fetchList();
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('list', array('model' => $model, 'dataProvider' => $dataProvider));
	}

	public function actionShowlog()
	{
		$rutid		 = Yii::app()->request->getParam('rutid');
		$logList	 = Route::model()->getRutLog($rutid);
		$modelList	 = new CArrayDataProvider($logList, array('pagination' => array('pageSize' => 10),));
		$models		 = $modelList->getData();
		$this->renderPartial('showlog', array('lmodel' => $models, 'usersList' => $modelList), false, true);
	}

	public function actionChangestatus()
	{

		$actid	 = Yii::app()->request->getParam('activateid');
		$inactid = Yii::app()->request->getParam('disableid');
		if ($actid > 0)
		{
			$model = Route::model()->findByPk($actid);
			if (count($model) == 1)
			{
				$model->rut_active = 2;
				$model->save();
			}
		}
		if ($inactid > 0)
		{
			$model = Route::model()->resetScope()->find('rut_id=:id', array(':id'=>$inactid));
			if (count($model) == 1)
			{
				$model->rut_active = 1;
				$model->save();
			}
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			echo "true";
			Yii::app()->end();
		}
		$this->redirect(array('list'));
	}

	public function actionRoutename()
	{
		$success = false;
		$params	 = $_GET;
		$city1	 = Yii::app()->request->getParam('city1');
		$city2	 = Yii::app()->request->getParam('city2');
		if ($city1 != '' && $city2 != '')
		{
			$duplicatecheck = Route::model()->find('rut_to_city_id=:tocity AND rut_from_city_id=:fcity', array('tocity' => $city2, 'fcity' => $city1));
			if (count($arr) > 0)
			{
				$success = true;
			}
			if ($duplicatecheck == '')
			{
				$city_name1		 = Cities::getName($city1);
				$city_name1		 = strtolower($city_name1);
				$city_name1		 = preg_replace('/[^a-zA-Z0-9_)]/', '_', $city_name1);
				$city_name1		 = str_replace(')', '', $city_name1);
				$city_name1		 = str_replace('___', '_', $city_name1);
				$city_name1		 = str_replace('__', '_', $city_name1);
				$city_name2		 = Cities::getName($city2);
				$city_name2		 = strtolower($city_name2);
				$city_name2		 = preg_replace('/[^a-zA-Z0-9_)]/', '_', $city_name2);
				$city_name2		 = str_replace(')', '', $city_name2);
				$city_name2		 = str_replace('___', '_', $city_name2);
				$city_name2		 = str_replace('__', '_', $city_name2);
				$route			 = $city_name1 . "-" . $city_name2;
				$return_route	 = $city_name2 . "-" . $city_name1;
				$success		 = 1;
			}
			else
			{
				$success = 0;
			}
		}
		else
		{
			$success = 5;
		}
		$data = $params + ['route' => $route, 'return_route' => $return_route];

		echo CJSON::encode(['success' => $success, 'data' => $data]);
	}

	public function actionGetroutename()
	{
		$success		 = false;
		$params			 = $_GET;
		$fcity			 = Yii::app()->request->getParam('fromCity');
		$tcity			 = Yii::app()->request->getParam('toCity');
		$bkgtype		 = Yii::app()->request->getParam('bookingType', 1);
		$pickLocation	 = Yii::app()->request->getParam('pickupAddress');
		$dropLocation	 = Yii::app()->request->getParam('dropAddress');
		$pickup_date	 = Yii::app()->request->getParam('pickupDate');
		$pickup_time	 = Yii::app()->request->getParam('pickupTime');

		$model		 = Route::model()->getbyCities($fcity, $tcity);
		$rutid		 = $model->rut_id;
		$rutname	 = $model->rut_name;
		$date		 = DateTimeFormat::DatePickerToDate($pickup_date);
		$time		 = DateTime::createFromFormat('h:i A', $pickup_time)->format('H:i:00');
		$datetime	 = $date . ' ' . $time;

		$Arrmulticity[0] = ["pickup_city" => $fcity, "drop_city" => $tcity, "pickup_address" => $pickLocation, "drop_address" => $dropLocation, "date" => $datetime];

		$multijsondata	 = json_encode($Arrmulticity);
		$arrjsondata	 = json_decode($multijsondata);

		$arr = Quotation::model()->calculateDistance($bkgtype, $arrjsondata);

		$distance	 = $arr['calDistance'] - $arr['totalExtraDist'];
		$duration	 = $arr['calculateTime'];

		if (count($arr) > 0)
		{
			$success = true;
		}
		//echo CJSON::encode(['success' => $success, 'error' => $errors, 'rutid' => $rutid, 'distance' => $distance, 'duration' => $duration,'est_info'=>$arr]);
		$data = $params + ['bookingTypeId' => $bkgtype, 'distance' => $distance, 'duration' => $duration, 'est_info' => $arr];
		echo CJSON::encode(['success' => $success, 'data' => $data]);
	}

	public function actionJson()
	{
		echo $data = Route::model()->getJSON();
		Yii::app()->end();
	}

	Public function actionRouterate()
	{

		$this->renderPartial('routerate', array('rutid' => $rutid), false, true);
	}

	public function actionPopulate()
	{
		Route::model()->populateRoutes();
	}

	public function actionDemandReport()
	{
		$this->pageTitle = "Demand Report";
		$model			 = new Booking;
		$routeModel		 = new Route;
		if (isset($_REQUEST['Booking']))
		{
			$arr				 = Yii::app()->request->getParam('Booking');
			$date				 = $arr['bkg_pickup_date'];
			$region				 = implode(',', $arr['bkg_region']);
			$sourcezone			 = implode(',', $arr['sourcezone']);
			$destinationzone	 = implode(',', $arr['destinationzone']);
			$bkg_vehicle_type_id = implode(',', $arr['bkg_vehicle_type_id']);
		}
		else
		{
			$date = DateTimeFormat::DateToLocale(date('Y-m-d'));
		}
		$model->bkg_pickup_date		 = $date;
		$model->bkg_region			 = $region;
		$model->sourcezone			 = $sourcezone;
		$model->destinationzone		 = $destinationzone;
		$date						 = DateTimeFormat::DatePickerToDate($date);
		$model->bkg_vehicle_type_id	 = $bkg_vehicle_type_id;

		$dataProvider = $routeModel->demandReport($date, $region, $sourcezone, $destinationzone, $bkg_vehicle_type_id);

		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);

		$type = 'command';
		if ($_REQUEST['export'] == true)
		{
			$fromDate = DateTimeFormat::DatePickerToDate(Yii::app()->request->getParam('export_from'));

			$expRegion		 = Yii::app()->request->getParam('bkg_region2');
			$expSource		 = Yii::app()->request->getParam('sourcezone2');
			$expDestination	 = Yii::app()->request->getParam('destinationzone2');
			$expVtype		 = Yii::app()->request->getParam('bkg_vehicle_type_id2');

			$data = $routeModel->demandReport($fromDate, $expRegion, $expSource, $expDestination, $expVtype, $type);

			header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename=\"RouteDemandReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$handle = fopen("php://output", 'w');
			fputcsv($handle, array("Date", "From Zone", "To Zone", "UP COUNT", "DOWN COUNT", "UP Confirmed", "DOWN Confirmed"));
			foreach ($data as $row)
			{
				$rowArray						 = array();
				$rowArray['bkg_pickup_date']	 = $row['bkg_pickup_date'];
				$rowArray['from_zone']			 = $row['from_zone'];
				$rowArray['to_zone']			 = $row['to_zone'];
				$rowArray['bkg_vehicle_type_id'] = $row['bkg_vehicle_type_id'];
				$rowArray['up_count']			 = $row['up_count'];
				$rowArray['down_count']			 = $row['down_count'];
				$rowArray['up_confirmed']		 = $row['up_confirmed'];
				$rowArray['down_confirmed']		 = $row['down_confirmed'];
				$row1							 = array_values($rowArray);
				fputcsv($handle, $row1);
			}
			fclose($handle);
			Yii::log("After IN TO OUT FILE query " . $sql, CLogger::LEVEL_INFO, 'system.api.images');
			if (!$row1)
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

		$this->render('demandreport', array('model'			 => $model,
			'dataProvider'	 => $dataProvider)
		);
	}

	public function actionOveridedynamicrut()
	{

		$actid	 = Yii::app()->request->getParam('activateDynamucRutId');
		$inactid = Yii::app()->request->getParam('disableDynamucRutId');
		if ($actid > 0)
		{
			$model = Route::model()->findByPk($actid);
			if (count($model) == 1)
			{
				$model->rut_override_dr = 2;
				$model->update();
			}
		}
		if ($inactid > 0)
		{
			$model = Route::model()->findByPk($inactid);
			if (count($model) == 1)
			{
				$model->rut_override_dr = 1;
				$model->update();
			}
		}
		if (Yii::app()->request->isAjaxRequest)
		{
			echo "true";
			Yii::app()->end();
		}
		$this->redirect(array('list'));
	}

}
