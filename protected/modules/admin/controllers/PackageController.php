<?php

class PackageController extends Controller
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
			'accessControl', //perform access control for CRUD operations
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
			['allow', 'actions' => [ 'addPackageDetails', 'changestatus', 'updatePackage', 'attractions', 'packageTitle'], 'roles' => ['agentAdd', 'agentEdit']],
			['allow', 'actions' => ['list', 'approvallist'], 'roles' => ['packageList']],
            ['allow', 'actions' => ['form','uploadpic','editdesc','editroutedesc','showlist'], 'roles' => ['packageAdd']],
            ['allow', 'actions' => ['addrate'], 'roles' => ['packageAddRate']],
            ['allow', 'actions' => ['block'], 'roles' => ['packageBlock']],
            ['allow', 'actions' => ['del','delrate'], 'roles' => ['packageDelete']],
			['allow', 'actions' => ['settings'], 'roles' => ['agentSettings']],
			['allow', 'actions' => ['changestatus', 'approve'], 'roles' => ['agentChangestatus']],
			array('allow', 'actions'	 => ['getDistance', 'addPackageDetails', 'corporateform', 'view', 'credithistory', 'bookinghistory',
					'markuplist', 'markupadd', 'markupdelete', 'getdistduration',
					'regprogress', 'addtransaction', 'refundcredit', 'delete11', 'changetype', 'agentsbytype', 'bookingmsgdefaults', 'assignDropoffTime', 'attractions', 'assignPackageDtTime'
					
					],
				'users'		 => array('@'),
			),
			array('deny', // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionForm()
	{
		$this->pageTitle = "Add Package";
		$pck_id			 = Yii::app()->request->getParam('pck_id');
		$model			 = new Package();
		$models			 = new PackageDetails();
		if ($pck_id != "")
		{
			
			$model			 = Package::model()->findByPk($pck_id);
			$this->pageTitle = "Edit Package";
//			$models			 = PackageDetails::model()->findByPk($pck_id);
		}

		if (isset($_REQUEST['Package']))
		{
			
			$totNight				 = '';
			$totDay					 = '';
			$autoPackageName		 = "";
			$model->attributes		 = Yii::app()->request->getParam('Package');
			$model->pck_created_by	 = Yii::app()->user->getId();
            $pck_name                = $model->attributes['pck_name'];
            $pck_url_name           = str_replace(' ', '-', $pck_name);
            $pck_url_name           = preg_replace('/[^A-Za-z0-9\-]/', '', $pck_url_name);
            $model->pck_url	        = strtolower(preg_replace('/-{2,}/','-',$pck_url_name));
			$packageDetails1 = Yii::app()->request->getParam('PackageDetails');
			$packageDetails	 = [];
			foreach ($packageDetails1 as $value)
			{
				$packageDetails[] = $value;
			}
			$model->packageDetails	 = $packageDetails;
			$result					 = CActiveForm::validate($model, null, false);
//            $errorMessages =[];
			$errorMessages			 = '';
			if ($result == '[]')
			{
				$model->save();
				$packageID = $model->pck_id;
				foreach ($packageDetails as $key => $value)
				{


					$totNight								 = $totNight + $value['pcd_night_serial'];
					$totDay									 = $totDay + $value['pcd_day_serial'];
					$packageDetailModel						 = new PackageDetails();
					$packageDetailModel->pcd_sequence		 = $value['pcd_sequence'];
					$packageDetailModel->pcd_day_serial		 = $value['pcd_day_serial'] | 0;
					$packageDetailModel->pcd_night_serial	 = $value['pcd_night_serial'] | 0;
					$packageDetailModel->pcd_from_city		 = $value['pcd_from_city'];
					$packageDetailModel->pcd_from_location	 = $value['pcd_from_location'];
					$packageDetailModel->pcd_to_city		 = $value['pcd_to_city'];
					$packageDetailModel->pcd_to_location	 = $value['pcd_to_location'];
					$packageDetailModel->pcd_description	 = $value['pcd_description'];
					//$packageDetailModel->pcd_trip_distance = $rutModel->rut_estm_distance;
					$packageDetailModel->pcd_trip_distance	 = $value['pcd_trip_distance'];
					$packageDetailModel->pcd_trip_duration	 = $value['pcd_trip_duration'];
					$packageDetailModel->pcd_pck_id			 = $packageID;
					$packageDetailModel->save();
					$fromCtyName							 = Cities::model()->findByPk($value['pcd_from_city'])->cty_name;
					$toCtyName								 = Cities::model()->findByPk($value['pcd_to_city'])->cty_name;
					if ($value['pcd_night_serial'] != "")
					{
						$nightCount = '(' . $value['pcd_night_serial'] . 'N' . ')';
					}
					else
					{
						$nightCount = '';
					}
					if ($key != 0)
					{
						$fromCtyName = '';
					}
					$day = $value['pcd_day_serial'];

					$pckName		 = $autoPackageName . $fromCtyName . '-' . $toCtyName . $nightCount;
					$autoPackageName = $pckName . '-';
				}
				$autoPackageName		 .= '-' . $day . 'D';
				$model->pck_no_of_days	 = $day;
				$model->pck_no_of_nights = $totNight;
				$model->pck_auto_name	 = $autoPackageName;
              
                
				$model->save();
				if (Yii::app()->request->isAjaxRequest)
				{
					$data = ['success' => true, 'message' => 'Package saved successfully '];
					echo json_encode($data);
					Yii::app()->end();
				}
			}
			else
			{
				if (Yii::app()->request->isAjaxRequest)
				{
					$result11 = [];

					foreach ($model->getErrors() as $attribute => $errors)
					{
						$result11[CHtml::activeId($model, $attribute)]	 = $errors;
						$error											 .= implode(', ', $errors);
					}
					$data = ['success' => false, 'errors' => $result11, 'error' => $error];
					echo json_encode($data);
					Yii::app()->end();
				}
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('form', array('model' => $model), false, $outputJs);
	}

	public function actionAddPackageDetails()
	{

		$scity		 = Yii::app()->request->getParam('scity'); //tocity
		$pscity		 = Yii::app()->request->getParam('pscity'); //fromcity
		$index		 = Yii::app()->request->getParam('index');
		$serial		 = Yii::app()->request->getParam('serial');
		$distance	 = Yii::app()->request->getParam('distance');
		$rutModel	 = Route::model()->getbyCities($pscity, $scity);
		if (!$rutModel)
		{
			$result1 = Route::model()->populate($pscity, $scity);
			if ($result1['success'])
			{
				$rutModel = $result1['model'];
			}
		}
		$rutModel->rut_estm_time;
		$model						 = PackageDetails::model();
		$model->pcd_trip_duration	 = $rutModel->rut_estm_time;
		$model->pcd_trip_distance	 = $rutModel->rut_estm_distance;
		//$model->pcd_trip_distance	 = $distance;

		$this->renderPartial('addPackageDetails', ['model' => $model, 'sourceCity' => $scity, 'previousCity' => $pscity, 'btype' => $btype, 'index' => $index], false, true);
	}

	public function actionList()
	{
		$this->pageTitle = "Packages";
		$pageSize		 = Yii::app()->params['listPerPage'];

		$model	 = new Package('search');
		$qry	 = [];
		if (isset($_REQUEST['Package']))
		{
			$model->attributes = Yii::app()->request->getParam('Package');
            $qry['from_city'] = $model->from_city = Yii::app()->request->getParam('Package')['from_city'];
			$qry['packageName']		 = ($model->pck_name != '') ? $model->pck_name : '';
			$qry['packageautoname']	 = ($model->pck_auto_name != '') ? $model->pck_auto_name : '';
            $qry['noofnights'] = ($model->pck_no_of_nights!='' && $model->pck_no_of_nights!=0)?$model->pck_no_of_nights:'';
            $qry['noofdays'] = ($model->pck_no_of_days!='' && $model->pck_no_of_days!=0)?$model->pck_no_of_days:'';
            $qry['zoneId'] =  $model->zoneId = Yii::app()->request->getParam('Package')['zoneId'];
		}
		$dataProvider = $model->getList($qry);
		$dataProvider->setSort(['params' => array_filter($_GET + $_POST)]);
		$dataProvider->setPagination(['params' => array_filter($_GET + $_POST)]);
		$this->render('list', array('model'			 => $model,
			'dataProvider'	 => $dataProvider,
			'qry'			 => $qry));
	}

	public function actionShowlist()
	{
		$this->pageTitle = "Package Details";
		$pck_id			 = Yii::app()->request->getParam('pck_id');
		$resultset		 = PackageDetails::model()->resetScope()->getDetailsById($pck_id, true);
		$this->renderPartial('showlist', array('resultset' => $resultset), false, true);
	}

	public function actionUploadpic()
	{
		$this->pageTitle = "Upload Images for Package";
		$pck_id			 = Yii::app()->request->getParam('pck_id');
		$resultset		 = Package::model()->resetScope()->getByPackageId($pck_id);
		$model			 = new PackageImages();

		if (isset($_REQUEST['PackageImages']))
		{
			$image_file		 = $_FILES['PackageImages']['name']['pci_images'];
			$image_file_tmp	 = $_FILES['PackageImages']['type']['pci_images'];

			$arr1				 = Yii::app()->request->getParam('PackageImages');
			$model->attributes	 = $arr1;
			$uploadedFile		 = CUploadedFile::getInstance($model, "pci_images");
			if ($uploadedFile != '')
			{
				$path					 = $this->uploadPackegesImage($uploadedFile, $pck_id);
				$model->pci_images		 = $path;
				$model->pci_image_type	 = 1;
				$model->pci_pck_id		 = $pck_id;
			}

			if ($model->validate())
			{
				$model->save();
				$this->redirect('list');
			}
		}


		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('uploadpic', array('resultset' => $resultset, 'model' => $model), false, $outputJs);
	}

	public function uploadPackegesImage($uploadedFile, $type = 'image')
	{
		$folderName	 = 'packageImage';
		$fileName	 = $type . "-" . date('YmdHis') . "." . pathinfo($uploadedFile, PATHINFO_EXTENSION);
		$dir		 = PUBLIC_PATH . DIRECTORY_SEPARATOR . $folderName;
		if (!is_dir($dir))
		{
			mkdir($dir);
		}
		$dirByAlbumId = $dir;
		if (!is_dir($dirByAlbumId))
		{
			mkdir($dirByAlbumId);
		}

		$foldertoupload	 = $dirByAlbumId . DIRECTORY_SEPARATOR . $fileName;
		$extention		 = pathinfo($uploadedFile, PATHINFO_EXTENSION);
		if (strtolower($extention) == 'png' || strtolower($extention) == 'jpg' || strtolower($extention) == 'jpeg' || strtolower($extention) == 'gif')
		{
			Vehicles::model()->img_resize($uploadedFile->tempName, 1200, $dirByAlbumId . DIRECTORY_SEPARATOR, $fileName);
		}
		else
		{
			$uploadedFile->saveAs($foldertoupload);
		}

		$path = DIRECTORY_SEPARATOR . $folderName . DIRECTORY_SEPARATOR . $fileName;
		return $path;
	}

	public function actionEditdesc()
	{
		$this->pageTitle = "Edit Package Name and Description";
		$pck_id			 = Yii::app()->request->getParam('pck_id');
		$model			 = Package::model()->resetScope()->findByPk($pck_id);


		if (isset($_REQUEST['Package']))
		{
           
			$arr1				 = Yii::app()->request->getParam('Package');
			$model->attributes	 = $arr1;
            $pck_name             = $model->attributes['pck_name'];
            $pck_url_name         = str_replace(' ', '-', $pck_name);
            $pck_url_name         = preg_replace('/[^A-Za-z0-9\-]/', '', $pck_url_name);
            $model->pck_url	      = strtolower(preg_replace('/-{2,}/','-',$pck_url_name));
			if ($model->validate())
			{
				$model->save();
				$this->redirect('list');
			}
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('editdesc', array('model' => $model), false, $outputJs);
	}

	public function actionEditroutedesc()
	{
		$this->pageTitle = "Edit Package Route/Day Description";
		$pck_id			 = Yii::app()->request->getParam('pck_id');
		$detailModels	 = PackageDetails::model()->getDetails($pck_id);

		if (isset($_REQUEST['PackageDetails']))
		{
			$arrPackageDetails = Yii::app()->request->getParam('PackageDetails');
			foreach ($arrPackageDetails as $pcdid => $detailModelVal)
			{
				$detailModel = PackageDetails::model()->findByPk($pcdid);
				if ($detailModel && $detailModel->pcd_description != $detailModelVal['pcd_description'])
				{
					$detailModel->pcd_description = $detailModelVal['pcd_description'];
					$detailModel->save();
				}
			}
			$this->redirect('list');
		}
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");
		$this->$method('editroutedesc', array('detailModels' => $detailModels), false, $outputJs);
	}

	public function actionAddRate()
	{
		$this->pageTitle = "Add Rate";

		$cab			 = $_REQUEST['PackageRate']['prt_package_cab_type'];
		$pck_id			 = Yii::app()->request->getParam('pck_id');
		$prt_id			 = Yii::app()->request->getParam('prt_id');
		$packageModel	 = Package::model()->getPackage($pck_id);
		$packagemodel	 = Package::model()->findByPk($pck_id);
		$model			 = new PackageRate();
		$outputJs		 = Yii::app()->request->isAjaxRequest;
		$method			 = "render" . ( $outputJs ? "Partial" : "");
		$exist			 = $model->getDuplicate($cab, $pck_id);

		$Arrmulticity	 = [];
		$routeModel		 = $packagemodel->packageDetails;
		$pickupDtTime	 = date('Y-m-d 10:00:00', strtotime('+5 Days'));
		$multijsondata	 = BookingRoute::model()->setTripRouteInfo($routeModel, 5, $pickupDtTime);

		if ($multijsondata != '')
		{
			$packagemodel->packageJsonData = $multijsondata;
		}
		//$_REQUEST;

		if ($prt_id != "")
		{
			$model = PackageRate::model()->findByPk($prt_id);
		}
		else
		{
			$model = new PackageRate();
		}


		if (isset($_REQUEST['PackageRate']))
		{

			$model->attributes		 = Yii::app()->request->getParam('PackageRate');
			$model->prt_pck_id		 = $pck_id;
			$model->prt_isIncluded	 = $_REQUEST['PackageRate']['prt_isIncluded'];
			if (!$exist)
			{
				$model->save();
			}
			else
			{
				if ($prt_id == '')
				{
					$msg = "Package rate is already exist for this cab type";
				}
				else
				{
					$model->save();
				}
			}
		}

		$dataProvider = $model->getList($pck_id, $prt_id);
		$this->$method('addRate', array('model' => $model, 'packageModel' => $packageModel, 'dataProvider' => $dataProvider, 'packagemodel' => $packagemodel, 'msg' => $msg), false, $outputJs);
	}

	public function actionChangestatus()
	{

		$pckid			 = Yii::app()->request->getParam('pck_id');
		$pck_active		 = Yii::app()->request->getParam('pck_active');
		$pck_approved_on = Yii::app()->request->getParam('pck_approved_on');

		$success	 = false;
		$userInfo	 = UserInfo::getInstance();
		if ($pck_active == 1)
		{
			$model = Package::model()->resetScope()->findByPk($pckid);

			$model->pck_approved_by	 = Yii::app()->user->getId();
			$model->pck_active		 = 2;
			if ($model->update())
			{
				$success = true;
			}
		}
		else if ($pck_active == 2)
		{
			$model					 = Package::model()->resetScope()->findByPk($pckid);
			$model->pck_approved_by	 = Yii::app()->user->getId();
			$model->pck_approved_on	 = date('Y-m-d H:i:s');
			$model->pck_active		 = 1;
			if ($model->update())
			{
				$success = true;
			}
		}
		else if ($pck_active == 3)
		{
			$model					 = Package::model()->resetScope()->findByPk($pckid);
			$model->pck_approved_by	 = Yii::app()->user->getId();
			$model->pck_approved_on	 = date('Y-m-d H:i:s');
			$model->pck_active		 = 1;
			if ($model->update())
			{

				$success = true;
			}
		}
		echo json_encode(["success" => $success]);
		Yii::app()->end();
	}

	public function actionBlock()
	{
		$pckid		 = Yii::app()->request->getParam('pck_id');
		$pck_active	 = Yii::app()->request->getParam('pck_active');
		$success	 = false;
		$user_id	 = Yii::app()->user->getId();
		if ($pck_active == 1)
		{
			$model					 = Package::model()->resetScope()->findByPk($pckid);
			$model->pck_approved_by	 = 0;
			$model->pck_approved_on	 = date('Y-m-d H:i:s');
			$model->pck_active		 = 2;
			if ($model->update())
			{
//                $event_id = PackageLog::Package_INACTIVE;
//                $desc     = "Package is Blocked.";
//                PackageLog::model()->createLog($model->pck_id, $desc, $userInfo, $event_id, false, false);
				$success = true;
			}
		}
		else if ($pck_active == 2)
		{
			$model				 = Package::model()->resetScope()->findByPk($pckid);
			$model->pck_active	 = 1;
			if ($model->update())
			{

				$success = true;
			}
		}

		echo json_encode(["success" => $success]);
		Yii::app()->end();
	}

	public function actionDel()
	{
		$id = Yii::app()->request->getParam('pck_id');
		if ($id != '')
		{
			$model				 = Package::model()->findByPk($id);
			$pckdet				 = PackageDetails::model()->updatePackageDetails($id);
			$pckrate			 = PackageRate::model()->updatePackageRate($id);
			$model->pck_active	 = 0;
			$model->save();
		}
		$this->redirect(array('list'));
	}

	public function actionDelrate()
	{
		$id	 = Yii::app()->request->getParam('prt_id');
		$pid = Yii::app()->request->getParam('pck_id');
		if ($id != '')
		{
			$model				 = PackageRate::model()->findByPk($id);
			$model->prt_status	 = 0;
			$model->save();
		}

		$this->redirect(array('addRate', 'pck_id' => $pid));
	}

	public function actionUpdatePackage()
	{
		$packageID			 = Yii::app()->request->getParam('packageID');
		$pcd_to_location	 = Yii::app()->request->getParam('tolocation');
		$pcd_from_location	 = Yii::app()->request->getParam('fromlocation');
		$pickupDt			 = Yii::app()->request->getParam('pickupDt');
		$pickupTime			 = Yii::app()->request->getParam('pickupTime');
		$formatPickUpDt		 = DateTimeFormat::DatePickerToDate($pickupDt);
		$formatPickUpTime	 = date('H:i:s', strtotime($pickupTime));
		$pickupDtTime		 = $formatPickUpDt . ' ' . $formatPickUpTime;

		$packageDetails = PackageDetails::model()->getDetails($packageID);

		$count = count($packageDetails);
		foreach ($packageDetails as $key => $value)
		{
			if ($key == 0)
			{
				$date = date('Y-m-d H:i:s', strtotime($pickupDtTime));
			}
			else
			{
				$serialDay			 = $packageDetails[$key]['pcd_day_serial'];
				$paackageDaySerial	 = ($serialDay - 1) . " days";
				$date				 = date('Y-m-d H:i:s', strtotime($pickupDtTime . ' + ' . $paackageDaySerial));
			}


			$packageDetails[$key]['date']		 = $date;
			$packageDetails[$key]['pickup_date'] = date('d/m/Y', strtotime($date));
			$packageDetails[$key]['pickup_time'] = date('h:i A', strtotime($date));
		}
		$packageDetails[0]['pcd_from_location']			 = $pcd_from_location;
		$packageDetails[$count - 1]['pcd_to_location']	 = $pcd_to_location;

		$packageDetails[0]['pickup_address']		 = $pcd_from_location;
		$packageDetails[$count - 1]['drop_address']	 = $pcd_to_location;

		echo json_encode(['success' => true, 'packageModel' => $packageDetails]);
	}

	public function actionAssignPackageDtTime()
	{
		$pcd_to_location	 = Yii::app()->request->getParam('tolocation');
		$pcd_from_location	 = Yii::app()->request->getParam('fromlocation');
		$pckageID			 = Yii::app()->request->getParam('pckageID');
		$pickupDt			 = Yii::app()->request->getParam('pickupDt');
		$pickupTime			 = Yii::app()->request->getParam('pickupTime');
		$formatPickUpDt		 = DateTimeFormat::DatePickerToDate($pickupDt);
		$formatPickUpTime	 = date('H:i:s', strtotime($pickupTime));

		$pickupDtTime	 = $formatPickUpDt . ' ' . $formatPickUpTime;
		$packageDetails	 = PackageDetails::model()->getDetails($pckageID);

		$packagemodel = Package::model()->findByPk($pckageID);

		$routeModel			 = $packagemodel->packageDetails;
		$multijsondata		 = BookingRoute::model()->setTripRouteInfo($routeModel, 5, $pickupDtTime);
		$paackageDaySerial	 = '';

		$count = count($packageDetails);

		foreach ($packageDetails as $key => $value)
		{
			if ($key == 0)
			{
				$date = date('Y-m-d H:i:s', strtotime($pickupDtTime));
			}
			else
			{
				$serialDay			 = $value['pcd_day_serial'];
				$paackageDaySerial	 = ($serialDay - 1) . " days";
				$date				 = date('Y-m-d H:i:s', strtotime($pickupDtTime . ' + ' . $paackageDaySerial));
			}
			$packageDetails[$key]['date']		 = $date;
			$packageDetails[$key]['pickup_date'] = date('d/m/Y', strtotime($date));
			$packageDetails[$key]['pickup_time'] = date('h:i A', strtotime($date));
			$packageDetails[$key]['pickup_city'] = $value['pcd_from_city'];
			$packageDetails[$key]['drop_city']	 = $value['pcd_to_city'];
		}
		$packageDetails[0]['pcd_from_location']			 = $pcd_from_location;
		$packageDetails[$count - 1]['pcd_to_location']	 = $pcd_to_location;
		$packageDetails[0]['pickup_address']			 = $pcd_from_location;
		$packageDetails[$count - 1]['drop_address']		 = $pcd_to_location;
		echo json_encode(['success' => true, 'packageModel' => $multijsondata, 'multijsondata' => $multijsondata]);
	}

	public function actionAssignDropoffTime()
	{
		$noOfNight	 = Yii::app()->request->getParam('night') . " days";
		$date		 = Yii::app()->request->getParam('date');
		$dropdate	 = date('Y-m-d H:i:s', strtotime($date . ' + ' . $noOfNight));
		echo json_encode(['success' => true, 'dropdate' => $dropdate]);
	}

	public function actionAttractions()
	{
		$this->pageTitle = "Add Tourist Attractions";
		$pck_id			 = Yii::app()->request->getParam('pck_id');

		$model		 = new PackageImages();
		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ( $outputJs ? "Partial" : "");

		$this->$method('attractions', array('model' => $model, 'dataProvider' => $dataProvider), false, $outputJs);
	}

	public function actionPackageTitle()
	{
		$packageData	 = Yii::app()->request->getParam('multijson');
		$json			 = json_decode($packageData, true);
		$autoPackageName = "";
		$totNight=0;
		foreach ($json as $k => $val)
		{
			$totNight +=$val['pcd_night_serial'];
			$fromCtyName = Cities::model()->findByPk($val['pickup_city'])->cty_name;
			$toCtyName	 = Cities::model()->findByPk($val['drop_city'])->cty_name;
			$nightCount	 = '(' . $val['pcd_night_serial'] . 'N' . ')';
			if ($k != 0)
			{
				$fromCtyName = '';
			}

			$pckName		 = $autoPackageName . $fromCtyName . '-' . $toCtyName . $nightCount;
			$autoPackageName = $pckName . '-';
			$lastDay		 = $val['day'];
		}
		$autoPackageName .= '-[' . $lastDay . 'D'.$totNight.'N]';
		echo json_encode(['success' => true, 'packageName' => $autoPackageName]);
	}

	public function actionGetDistance()
	{
		$pickupCity	 = Yii::app()->request->getParam('pickupcity');
		$dropCity	 = Yii::app()->request->getParam('dropcity');
		$rutModel	 = Route::model()->getbyCities($pickupCity, $dropCity);
		if (!$rutModel)
		{
			$result1 = Route::model()->populate($pickupCity, $dropCity);
			if ($result1['success'])
			{
				$rutModel = $result1['model'];
			}
		}
		echo json_encode(['distance' => $rutModel->rut_estm_distance]);
	}

	public function actionGetdistduration()
	{
		$routeArr	 = Yii::app()->request->getParam('jsonArrMul');
		$distance	 = 0;
		$duration	 = 0;
		if ($routeArr != '' && count($routeArr) > 0)
		{
			$noFactor	 = 0;
			$noFactor1	 = 0;
			foreach ($routeArr as $key => $route)
			{

				$rutDuration = $route['duration'];
				$rutDistance = $route['distance'];
				$result		 = Route::model()->populate($route['pickup_city'], $route['drop_city']);
				if ($result['success'])
				{
					$rutModel	 = $result['model'];
					$durVal		 = max([$rutModel->rut_estm_time, $rutDuration]);
					$distVal	 = max([$rutModel->rut_estm_distance, $rutDistance]);
					$durationVal = ($noFactor == 0) ? $duration + $durVal : max([$duration + $durVal, (($noFactor + $noFactor1) * 24 * 60) + $durVal]);
					$duration	 = max($durVal, $durationVal);
					$noFactor	 = $route['night'];
					$noFactor1	 = $route['day'] - 1;
					$distance	 += $distVal;
				}
			}
		}
		echo json_encode(['distance' => $distance, 'duration' => $duration]);
	}
  

}
