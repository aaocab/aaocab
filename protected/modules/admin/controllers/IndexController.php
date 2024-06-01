<?php

class IndexController extends Controller
{
   

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'adminLayout';

	// public $layoutdashboard = 'admin1';

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
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('changepassword', 'dashboard', 'dashbordold', 'logout', 'saveDialer', 'redirectPerson', 'map'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('index', 'Ghash', 'custom', 'indexRemote'),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array('admin'),
				'users'		 => array('admin'),
			),
			/*
			  array('deny', // deny all users
			  'users' => array('*'),
			  ), */
			array('allow', 'actions' => array('dashbordnew'), 'users' => ['@']),
		);
	}

	public function actionIndex($status = null)
	{
		if (!Yii::app()->user->isGuest)
		{
			$this->redirect(array('dashboard'));
		}
		$this->layout = "adminLoginLayout";

		//if($checkAccess == true)
		//{
		if (isset($_REQUEST) && $_REQUEST != null)
		{
			$email		 = Yii::app()->request->getParam('txtUsername');
			$pass		 = Yii::app()->request->getParam('txtPassword', '');
			$isRemote	 = 0;
			$identity	 = new AdminIdentity($email, $pass, $isRemote);
			$valid		 = $identity->authenticate();
			if ($valid)
			{

				if (Yii::app()->user->login($identity, 1200))
				{
					$checkAccess = Yii::app()->user->checkAccess('LeadCaller');

					if ($checkAccess == FALSE)
					{
						Yii::app()->session['isRemote'] = 0;
						$this->createLog($identity);
						if (Yii::app()->user->returnUrl != null && Yii::app()->user->returnUrl != '/')
						{
							$this->redirect(Yii::app()->user->returnUrl);
							return;
						}
						$this->redirect(array("dashboard"));
					}
					else
					{
						Yii::app()->user->logout();
					}
				}
			}
			else
			{
				$status = 'error';
			}
		}
		//}else{
		//	$status = 'error';
		//}

		$this->render('index', array(
			'status' => $status
		));
	}

	public function createLog($identity)
	{
		$ip									 = \Filter::getUserIP();
		$sessionid							 = Yii::app()->getSession()->getSessionId();
		$admlogModel						 = new AdminLog();
		$admlogModel->adm_log_in_time		 = new CDbExpression('Now()');
		$admlogModel->adm_log_ip			 = $ip;
		$admlogModel->adm_log_session		 = $sessionid;
		$admlogModel->adm_log_device_info	 = $_SERVER['HTTP_USER_AGENT'];
		$admlogModel->adm_log_user			 = $identity->getId();
		$admlogModel->save();
		return true;
	}

	public function actionRedirectPerson()
	{
		$link	 = '';
		$success = false;

		$person	 = Yii::app()->request->getParam('personType');
		$code	 = Yii::app()->request->getParam('personCode');

		if ($person == 2)
		{
			$model			 = new Vendors('search');
			$model->vnd_code = $unique_id;
			$id				 = Vendors::findByCode($code);
			if ($id != '')
			{
				$success = true;
				$link	 = Yii::app()->createUrl('admpnl/vendor/view') . '?id=' . $id;
			}
		}
		else if ($person == 3)
		{
			$model			 = new Drivers();
			$model->drv_code = $unique_id;
			$id				 = Drivers::getIdByCode($code)->drv_id;
			if ($id != '')
			{
				$success = true;
				$link	 = Yii::app()->createUrl('admpnl/driver/view') . '?id=' . $id;
			}
		}




		$result = array('success' => $success, 'link' => $link);
		echo json_encode($result);
	}

	public function actionDashboard()
	{
		if (Yii::app()->user->isGuest)
		{
			$this->redirect(array('index'));
		}
		$userid			 = Yii::app()->user->getId();
		$this->pageTitle = 'Dashboard';
		$this->layout	 = 'admin1';
		/* @var $model Booking */
		$model			 = new Booking();
		/* @var $modelsub BookingSub */
		$modelsub		 = new BookingSub();

		$escalationList			 = BookingSub::getActiveEscalations();
		$manualAssignmentList	 = BookingSub::countAssignment('manual');
		$criticalAssignmentList	 = BookingSub::countAssignment('critical');
		$riskBookingList		 = BookingSub::countRiskBooking();
		$delegatedManagerList	 = BookingSub::counterDelegatedManager();
		$accountsAttentionList	 = BookingSub::getAccountsAttention();
		$reconfirmPendingList	 = BookingSub::getCountReconfirmPending36hrs();
		$pickupOverdueList		 = BookingSub::getCountPickupOverdue();
		$completionOverdueList	 = BookingSub::getCountCompletionOverdue();
		$countMissingDrivers		 = $modelsub->getMissingDrivers();
		$countUnassignedVendors		 = $modelsub->getUnassignedVendors();
		$countMissingDriversDoc		 = BookingCab::getMissingDriverDocs();
		$countUnverifiedLeeds		 = $modelsub->getUnverifiedLeeds();
		$countVendorDocMissing		 = Vendors::model()->countVendorDocMissing();
		$countVendorBankMissing		 = Vendors::model()->countVendorBankMissing();
		$countVendorFloating24hrs	 = BookingCab::model()->getCountVendorFloating24hrs();
		$model->bcbTypeMatched		 = [0];
		$countRefundApprobvalsPending	 = BookingInvoice::counterRefundApproval();
		$countSosAlert					 = BookingTrack::countSosAlert();
		$nmiAppliedZone					 = BookingSub::getNmiAppliedZone();
		$uncommonRoutes					 = BookingPref::counterUncommonRoutes();
		$autoCancelOn					 = BookingPref::countAutoCancelFlagOn();
		$getOfflineDriverCount			 = $modelsub->getOfflineDriverCount();
		$getCountDriverNotLeftforPickup	 = $modelsub->getCountDriverNotLeftforPickup();
		$getCountAutoCancelBooking		 = $modelsub->getAutoCancelBookingCount();
		$getUrgentPickupCount		 = $modelsub->getUrgentPickupCount();
		$getUrgentAPPickupCount		 = $modelsub->getUrgentPickupCount(12);
		$getCountInternalCBR		 = ServiceCallQueue::countInternalActiveCBR();
//		$getZeroInventoryCount		 = Zones::countZeroInventory();
		$vipCustomerCount            = Tags::getVIPCount();
		$returnSet					 = array(
			'model'							 => $model,
			'escalations'					 => $escalationList,
			'manualAssignment'				 => $manualAssignmentList,
			'criticalAssignment'			 => $criticalAssignmentList,
			'riskBooking'					 => $riskBookingList,
			'delegatedManager'				 => $delegatedManagerList,
			'accountsAttention'				 => $accountsAttentionList,
			'reconfirmPending'				 => $reconfirmPendingList,
			'pickupOverdue'					 => $pickupOverdueList,
			'completionOverdue'				 => $completionOverdueList,
			'countMissingDrivers'			 => $countMissingDrivers,
			'countUnassignedVendors'		 => $countUnassignedVendors,
			'countMissingDriversDoc'		 => $countMissingDriversDoc,
			'countUnverifiedLeeds'			 => $countUnverifiedLeeds,
			'countVendorDocMissing'			 => $countVendorDocMissing,
			'countVendorBankMissing'		 => $countVendorBankMissing,
			'countVendorFloating24hrs'		 => $countVendorFloating24hrs,
			'countSosAlert'					 => $countSosAlert,
			'nmiAppliedZone'				 => $nmiAppliedZone,
			'countPendingRefundApprovals'	 => $countRefundApprobvalsPending,
			'uncommonRoutes'				 => $uncommonRoutes,
			'autoCancelOn'					 => $autoCancelOn,
			'getOfflineDriverCount'			 => $getOfflineDriverCount,
			'getCountDriverNotLeftforPickup' => $getCountDriverNotLeftforPickup,
			'getCountAutoCancelBooking'		 => $getCountAutoCancelBooking,
			'urgentPickupCount'				 => $getUrgentPickupCount,
			'urgentAPPickupCount'			 => $getUrgentAPPickupCount,
			'countAllInternalCBR'			 => $getCountInternalCBR,
			'vipCustomerCount'               => $vipCustomerCount
		);

		$outputJs	 = Yii::app()->request->isAjaxRequest;
		$method		 = "render" . ($outputJs ? "Partial" : "");
		$this->$method('dashboard_new', $returnSet, false, $outputJs);
	}

	public function actionDashboardOld()
	{
		$this->pageTitle = 'Dashboard';
		$this->layout	 = 'admin1';
		$this->render('dashboard');
	}

	public function actionLogout()
	{

		$sessionid	 = Yii::app()->getSession()->getSessionId();
		$admlogModel = new AdminLog();
		$admlogModel = $admlogModel->getLogBySession($sessionid);
		if ($admlogModel)
		{
			$admlogModel->adm_log_out_time = new CDbExpression('Now()');
			$admlogModel->update();
		}

		Yii::app()->user->logout();
		$this->redirect(array('index'));
	}

	public function actionGhash()
	{
		$pass = 'sachin1';
		echo CPasswordHelper::hashPassword($pass, 10);

		exit;
	}

	public function actionChangepassword()
	{

		$this->layout	 = 'admin1';
		$this->pageTitle = 'Change Password';
		$adminId		 = Yii::app()->user->getId();
		$model			 = Admins::model()->findByPk($adminId);
		$model->scenario = "change";
		if (isset($_REQUEST['oldpassword']) && isset($_REQUEST['newpassword']) && isset($_REQUEST['confirmpassword']))
		{
			$oldPassword			 = Yii::app()->request->getParam('oldpassword');
			$newPassword			 = Yii::app()->request->getParam('newpassword');
			$rePassword				 = Yii::app()->request->getParam('confirmpassword');
			$model->old_password	 = $oldPassword;
			$model->new_password	 = $newPassword;
			$model->repeat_password	 = $rePassword;
			$model->scenario		 = "checkPassword";
			if (!CPasswordHelper::verifyPassword($model->old_password, $model->adm_passwd))
			{
				$status	 = "error";
				$message = "Old password doesnot match";
			}
			elseif ($model->new_password != $model->repeat_password)
			{
				$status	 = "error";
				$message = "New password and Confirm Password doesnot match";
			}
			else
			{
				$model->adm_passwd				 = CPasswordHelper::hashPassword($model->new_password);
				$model->adm_last_password_change = new CDbExpression('Now()');
				if ($model->save('change'))
				{
					$status	 = "true";
					$message = "Password Changed Successfully";
				}
				else
				{
					$status			 = "error";
					$error			 = $model->getErrors();
					$errorMessage	 = $error['new_password'][0];
					$message		 = $errorMessage;
				}
			}
		}
		if ($status == 'true')
		{
			$this->actionLogout();
		}
		$this->render('changepassword', ['status' => $status, 'message' => $message]);
	}

	public function actionCustom()
	{
		$ev = PaymentType::model()->getList();
		foreach ($ev as $k => $v)
		{
			$evName = str_replace(' ', '_', strtoupper($v));
			echo 'const TYPE_' . $evName . ' = ' . $k . '; <br>';
		}
	}

	public function actionImagerotate()
	{
		$docId		 = Yii::app()->request->getParam('docid');
		$rttype		 = Yii::app()->request->getParam('rttype');
		$docType	 = Yii::app()->request->getParam('docType');
		$cttId		 = Yii::app()->request->getParam('cttId');
		$docModel	 = Document::model()->findByPk($docId);

		if ($docType == 0)
		{
			if ($docModel->doc_front_s3_data != '')
			{
				/** @var Stub\common\SpaceFile $spaceFile */
				$spaceFile		 = \Stub\common\SpaceFile::populate($docModel->doc_front_s3_data);
				$frontPath		 = $docModel->doc_file_front_path;
				$fileInArr		 = explode("/", $frontPath);
				$imagePath		 = $fileInArr[count($fileInArr) - 1];
				$path			 = Document::model()->createFolderPath($cttId, $imagePath);
				$localFilename	 = Yii::app()->basePath . $path;
				if ($spaceFile->key != NULL)
				{
					$files = $spaceFile->getFile();
					$spaceFile->getFile()->download($localFilename);
					if ($files)
					{
						$docModel->doc_file_front_path	 = $path;
						$docModel->doc_front_s3_data	 = NULL;
						$docModel->save();
					}
				}
			}
			$fileType		 = pathinfo($docModel->doc_file_front_path, PATHINFO_EXTENSION);
			$rotateFilename	 = Yii::app()->basePath . $docModel->doc_file_front_path;
		}
		else
		{
			if ($docModel->doc_back_s3_data != '')
			{
				/** @var Stub\common\SpaceFile $spaceFile */
				$spaceFile		 = \Stub\common\SpaceFile::populate($docModel->doc_back_s3_data);
				$backPath		 = $docModel->doc_file_back_path;
				$fileInArr		 = explode("/", $backPath);
				$imagePath		 = $fileInArr[count($fileInArr) - 1];
				$path			 = Document::model()->createFolderPath($cttId, $imagePath);
				$localFilename	 = Yii::app()->basePath . $path;
				if ($spaceFile->key != NULL)
				{
					$files = $spaceFile->getFile();
					$spaceFile->getFile()->download($localFilename);
					if ($files)
					{
						$docModel->doc_file_back_path	 = $path;
						$docModel->doc_back_s3_data		 = NULL;
						$docModel->save();
					}
				}
			}
			$fileType		 = pathinfo($docModel->doc_file_back_path, PATHINFO_EXTENSION);
			$rotateFilename	 = Yii::app()->basePath . $docModel->doc_file_back_path;
		}
		if ($docModel != '' && $fileType != 'pdf')
		{
			$degrees = 90;
			if ($rttype == 'right')
			{
				$degrees = 270;
			}

			if ($fileType == 'png' || $fileType == 'PNG')
			{
				header('Content-type: image/png');
				$source	 = imagecreatefrompng($rotateFilename);
				$bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);
				// Rotate
				$rotate	 = imagerotate($source, $degrees, $bgColor);
				imagesavealpha($rotate, true);
				imagepng($rotate, $rotateFilename);
			}
			if ($fileType == 'jpg' || $fileType == 'jpeg' || $fileType == 'JPG')
			{
				header('Content-type: image/jpeg');
				$source	 = imagecreatefromjpeg($rotateFilename);
				// Rotate
				$rotate	 = imagerotate($source, $degrees, 0);
				imagejpeg($rotate, $rotateFilename);
			}
			imagedestroy($source);
			imagedestroy($rotate);
			if ($docType == 0)
			{
				$picpath = Document::getDocPathById($docModel->doc_id, 1) . "?v=" . time();
			}
			else
			{
				$picpath = Document::getDocPathById($docModel->doc_id, 2) . "?v=" . time();
			}
			echo json_encode(['success' => true, 'imagefile' => $picpath]);
			Yii::app()->end();
		}
	}

	public function actionIndexRemote($status = null)
	{
		if (!Yii::app()->user->isGuest)
		{
			$this->redirect(array('dashboard'));
		}
		$this->layout = "adminLoginLayout";

		if (isset($_REQUEST) && $_REQUEST != null)
		{
			$email		 = Yii::app()->request->getParam('txtUsername');
			$pass		 = Yii::app()->request->getParam('txtPassword', '');
			$isRemote	 = 1;
			$identity	 = new AdminIdentity($email, $pass);
			$valid		 = $identity->authenticate();
			if ($valid)
			{
				if (Yii::app()->user->login($identity, 1200))
				{
					Yii::app()->session['isRemote'] = 1;
					$this->createLog($identity);

					if (Yii::app()->user->returnUrl != null && Yii::app()->user->returnUrl != '/')
					{
						$this->redirect(Yii::app()->user->returnUrl);
						return;
					}
					$this->redirect(array("dashboard"));
				}
			}
			else
			{
				$status = 'error';
			}
		}


		$this->render('index', array(
			'status' => $status
		));
	}

	public function actionImportCsvIntoMysql()
	{
		$dirPath = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'Exported';
		$ext	 = '.csv';

		foreach (glob($dirPath . '/*' . $ext) as $filenamelist)
		{
			$filename	 = $dirPath . DIRECTORY_SEPARATOR . basename($filenamelist);
			$file		 = fopen($filename, "r");
			$fName		 = explode('.', basename($filenamelist));
			while (($getData	 = fgetcsv($file, 10000, ",")) !== FALSE)
			{
				if ($getData[0] != 'Additional Surge')
				{
					$qry = "INSERT INTO dynamic_price_surge(`dps_name`, `additional_surge`, `base_capacity`, `count_booking`, `count_quotation`, `Date`, `forecast_act`, `M_000`, `M_010`, `M_020`, `M_030`, `M_040`, `M_050`, `M_060`, `M_070`, `M_080`, `M_090`, `M_100`, `M_120`, `M_140`, `M_170`, `M_200`, `M_250`, `M_300`, `manuual_count_booking`, `manuual_count_quotation`, `total_DP`, `total_SP`, `Weekday`, `Yield`) 
                            VALUES ('" . $fName[0] . "','" . $getData[0] . "','" . $getData[1] . "','" . $getData[2] . "','" . $getData[3] . "','" . $getData[4] . "','" . $getData[5] . "','" . $getData[6] . "','" . $getData[7] . "','" . $getData[8] . "','" . $getData[9] . "','" . $getData[10] . "','" . $getData[11] . "','" . $getData[12] . "','" . $getData[13] . "','" . $getData[14] . "','" . $getData[15] . "','" . $getData[16] . "','" . $getData[17] . "','" . $getData[18] . "','" . $getData[19] . "','" . $getData[20] . "','" . $getData[21] . "','" . $getData[22] . "','" . $getData[23] . "','" . $getData[24] . "','" . $getData[25] . "','" . $getData[26] . "','" . $getData[27] . "','" . $getData[28] . "')";

					Yii::app()->db->createCommand($qry)->execute();
				}
			}
		}
	}

	public function actionMergeDynamicPriceSurgeData()
	{
		$sql		 = "SELECT `dps_name`, `additional_surge`, `base_capacity`, `count_booking`, `count_quotation`, `Date`, `forecast_act`, `M_000`, `M_010`, `M_020`, `M_030`, `M_040`, `M_050`, `M_060`, `M_070`, `M_080`, `M_090`, `M_100`, `M_120`, `M_140`, `M_170`, `M_200`, `M_250`, `M_300`, `manuual_count_booking`, `manuual_count_quotation`, `total_DP`, `total_SP`, `Weekday`, `Yield` FROM dynamic_price_surge_bk";
		$recordSet	 = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($recordSet as $data)
		{
			$arr		 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
			$countArr	 = implode(',', $arr);
			$cnt		 = '[' . $countArr . ']';
			if ((($data['count_quotation'] != $cnt) || ($data['manuual_count_quotation'] != $cnt)) && ($data['Date'] != null || $data['Date'] != ''))
			{
				$qry = "UPDATE dynamic_price_surge SET dps_name = '" . $data['dps_name'] . "', additional_surge = '" . $data['additional_surge'] . "', base_capacity = '" . $data['base_capacity'] . "', Date = '" . $data['Date'] . "',forecast_act = '" . $data['forecast_act'] . "',M_000 = '" . $data['M_000'] . "', M_010 = '" . $data['M_010'] . "',M_020 = '" . $data['M_020'] . "',M_030 = '" . $data['M_030'] . "',M_040 = '" . $data['M_040'] . "',M_050 = '" . $data['M_050'] . "',M_060 = '" . $data['M_060'] . "',M_070 = '" . $data['M_070'] . "',M_080 = '" . $data['M_080'] . "',M_090 = '" . $data['M_090'] . "',M_100 = '" . $data['M_100'] . "',M_120 = '" . $data['M_120'] . "',M_140 = '" . $data['M_140'] . "',M_170 = '" . $data['M_170'] . "',M_200 = '" . $data['M_200'] . "',M_250 = '" . $data['M_250'] . "',M_300 = '" . $data['M_300'] . "',total_DP = '" . $data['total_DP'] . "',total_SP = '" . $data['total_SP'] . "',Weekday = '" . $data['Weekday'] . "',Yield = '" . $data['Yield'] . "' WHERE DATE = '" . $data['Date'] . "' AND dps_name = '" . $data['dps_name'] . "'";
				Yii::app()->db->createCommand($qry)->execute();
			}
			else
			{
				if ($data[0] != 'Additional Surge')
				{
					$qry = "INSERT INTO dynamic_price_surge(`dps_name`, `additional_surge`, `base_capacity`, `count_booking`, `count_quotation`, `Date`, `forecast_act`, `M_000`, `M_010`, `M_020`, `M_030`, `M_040`, `M_050`, `M_060`, `M_070`, `M_080`, `M_090`, `M_100`, `M_120`, `M_140`, `M_170`, `M_200`, `M_250`, `M_300`, `manuual_count_booking`, `manuual_count_quotation`, `total_DP`, `total_SP`, `Weekday`, `Yield`) 
								VALUES ('" . $data['dps_name'] . "','" . $data['additional_surge'] . "','" . $data['base_capacity'] . "','" . $data['count_booking'] . "','" . $data['count_quotation'] . "','" . $data['Date'] . "','" . $data['forecast_act'] . "','" . $data['M_000'] . "','" . $data['M_010'] . "','" . $data['M_020'] . "','" . $data['M_030'] . "','" . $data['M_040'] . "','" . $data['M_050'] . "','" . $data['M_060'] . "','" . $data['M_070'] . "','" . $data['M_080'] . "','" . $data['M_090'] . "','" . $data['M_100'] . "','" . $data['M_120'] . "','" . $data['M_140'] . "','" . $data['M_170'] . "','" . $data['M_200'] . "','" . $data['M_250'] . "','" . $data['M_300'] . "','" . $data['manuual_count_booking'] . "','" . $data['manuual_count_quotation'] . "','" . $data['total_DP'] . "','" . $data['total_SP'] . "','" . $data['Weekday'] . "','" . $data['Yield'] . "')";
					Yii::app()->db->createCommand($qry)->execute();
				}
			}
		}
	}

	public function actionGetPartnerAPIQuoteBooking()
	{
		$partnerIds	 = [1273, 454, 1624, 1153, 2711, 123];
		$pmodel		 = PartnerApiTracking::model()->countPartnerAPIQuoteBooking($partnerIds);
		if ($pmodel)
		{
			echo "record added successfully";
		}
	}

	public function actionSaveDialer()
	{
		$dialerNo						 = Yii::app()->request->getParam('dialerNo');
		Yii::app()->session['dialerNo']	 = $dialerNo;
		echo $dialerNo;
	}

	public function actionQrCode()
	{
		$this->pageTitle = 'QrCode';
		$this->layout	 = 'admin1';
		$this->render('qrcode');
	}

	public function actionMap()
	{
		$this->layout	 = 'admin1';

		$arrCtyBounds = [];
		$model = new Booking();
		
		$data = Yii::app()->request->getParam('Booking');
		if(count($data) > 0)
		{
			$model->bkg_from_city_id = $data['bkg_from_city_id'];
			$model->pickupLat = $data['pickupLat'];
			$model->pickupLon = $data['pickupLon'];
			
			$city = Cities::model()->findByPk($model->bkg_from_city_id);
			$ctyBounds = $city->cty_bounds;
			if($ctyBounds != '')
			{
				$arrCtyBounds = json_decode($ctyBounds, true);
			}
		}
		
		$this->render('map', array('model' => $model, 'arrCtyBounds' => $arrCtyBounds));
	}
}
