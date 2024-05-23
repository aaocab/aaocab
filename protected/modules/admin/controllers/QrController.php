<?php

class QrController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = 'admin1';
	public $email_receipient;

	//public $layout = '//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete1', // we only allow deletion via POST request
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
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('qrAllocatedList', 'view', 'status', 'leadList', 'approveQr'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array(
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array(),
				'users'		 => array('admin'),
			),
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
			$ri	 = array('/list', '/entityType', '/allocation', '/activationSearch', '/otpVerification', '/activation');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});

		$this->onRest('req.post.list.render', function () {
			return $this->renderJSON($this->List());
		});
		$this->onRest('req.post.allocation.render', function () {
			return $this->renderJSON($this->Allocation());
		});
		$this->onRest('req.post.activationSearch.render', function () {
			return $this->renderJSON($this->ActSearch());
		});
		$this->onRest('req.get.entityType.render', function () {
			return $this->renderJSON($this->entityType());
		});
		$this->onRest('req.post.otpVerification.render', function () {
			return $this->renderJSON($this->sendOtp());
		});
		$this->onRest('req.post.activation.render', function () {
			return $this->renderJSON($this->activation());
		});
	}

	/**
	 * This function is used to show QRlist
	 * @return \ReturnSet
	 * @throws Exception
	 */
	public function List()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$model		 = new QrCode();
		$limit		 = 50;
		if ($data != "")
		{
			$jsonObj = CJSON::decode($data, false);
		}

		$dataProvider = $model->getList($jsonObj, $limit);

		$dataObj = new Stub\common\Qr();
		$dataObj = $dataObj->set($dataProvider);

		$response = Filter::removeNull($dataObj);
		if (!$response)
		{
			throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
		}
		$returnSet->setStatus(true);
		$returnSet->setData($response);
		return $returnSet;
	}

	/**
	 * this function is used for qr code allocation
	 * @return type
	 * @throws Exception
	 */
	public function Allocation()
	{
		$returnSet	 = new ReturnSet();
		$userInfo	 = UserInfo::model();

		$csrId = UserInfo::getUserId();

		$data	 = Yii::app()->request->rawBody;
		Logger::trace("QRCODE Data : " . $data);
		$jsonObj = CJSON::decode($data, false);

		try
		{
			if ($jsonObj->qr_id != '' && $jsonObj->entity_type != "" && $jsonObj->entity_id != "")
			{

				$qrArr = $jsonObj->qr_id;
				if (count($qrArr) > 0)
				{
					foreach ($qrArr as $qr)
					{
						$res = QrCode::allocate($jsonObj, $qr);
					}
					if ($res)
					{
						$returnSet->setStatus(true);
						$returnSet->setMessage("Allocation done successfully");
					}
				}
			}
			else
			{
				throw new Exception("invalid request", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function ActSearch()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;

		$jsonObj = CJSON::decode($data, false);

		try
		{

			$url = $jsonObj->url;
			if ($url == '')
			{
				throw new Exception("invalid request", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			#$webPrefix = "https://c.gozo.cab/";
			$webPrefix	 = Yii::app()->params['QrUrl'];
			$code		 = str_replace($webPrefix, '', $url);
			$qrModel	 = QrCode::model()->find('qrc_code=:code', ['code' => $code]);
			$id			 = $qrModel->qrc_id;
			if ($id == "")
			{
				throw new Exception("invalid request", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$dataProvider	 = QrCode::showAllocatedTo($id);
			$price			 = 25;
			if (empty($dataProvider))
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$dataObj	 = new Stub\common\Qr();
			$dataObj	 = $dataObj->showAllocatedTo($dataProvider, $price, $id);
			$response	 = Filter::removeNull($dataObj);
			if (!$response)
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}


			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function entityType()
	{
		$returnSet = new ReturnSet();
		try
		{
			$personoptions = [["id" => "1", "text" => "Customer"],
				["id" => "2", "text" => 'Vendor'],
				["id" => "3", "text" => 'Driver'],
				["id" => "4", "text" => 'Gozen'],
				["id" => "5", "text" => 'Agent'],
			];
			$returnSet->setStatus(true);
			$returnSet->setData($personoptions);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = $returnSet->setException($ex);
		}
		return $returnSet;
	}

	public function actionQrAllocatedList()
	{
		$this->pageTitle = "Qr Allocated List";
		$model			 = new QrCode('search');
		$arr			 = Yii::app()->request->getParam('QrCode');
		$model->qrStatus = 1;
		if (isset($_REQUEST['QrCode']))
		{

			$allocated				 = $arr['alloated'];
			$model->qrCode			 = $arr['qrCode'];
			$model->qrStatus		 = $arr['qrStatus'];
			$model->allocatedType	 = $arr['allocatedType'];
			switch ($model->allocatedType)
			{
				case 1:
					$model->custId	 = $allocated;
					break;
				case 2:
					$model->vendId	 = $allocated;
					break;
				case 3:
					$model->drvId	 = $allocated;
					break;
				case 4:
					$model->adminId	 = $allocated;
					break;
				case 5:
					$model->agntId	 = $allocated;
					break;
			}
			$model->gozens			 = $arr['gozens'];
			$model->allocatedDate1	 = $arr['allocatedDate1'];
			$model->allocatedDate2	 = $arr['allocatedDate2'];
			$model->activatedDate1	 = $arr['activatedDate1'];
			$model->activatedDate2	 = $arr['activatedDate2'];
			$model->qrApproveStatus	 = $arr['qrApproveStatus'];
			$model->qrc_agent_id	 = $arr['qrc_agent_id'];
		}
		if (isset($_REQUEST['export1']))
		{
			$allocated				 = $arr['export_alloated'];
			$model->qrCode			 = $arr['export_qrCode'];
			$model->qrStatus		 = $arr['export_qrStatus'];
			$model->allocatedType	 = $arr['export_allocatedType'];
			switch ($model->allocatedType)
			{
				case 1:
					$model->custId	 = $allocated;
					break;
				case 2:
					$model->vendId	 = $allocated;
					break;
				case 3:
					$model->drvId	 = $allocated;
					break;
				case 4:
					$model->adminId	 = $allocated;
					break;
				case 5:
					$model->agntId	 = $allocated;
					break;
			}
			$model->gozens			 = $arr['export_gozens'];
			$model->allocatedDate1	 = $arr['export_allocatedDate1'];
			$model->allocatedDate2	 = $arr['export_allocatedDate2'];
			$model->activatedDate1	 = $arr['export_activatedDate1'];
			$model->activatedDate2	 = $arr['export_activatedDate2'];
			$model->qrApproveStatus	 = $arr['export_qrApproveStatus'];
			$model->qrc_agent_id	 = $arr['export_qrc_agent_id'];
			header('Content-type: text/csv');
			header("Content-Disposition: attachment; filename=\"QrReport_" . date('Ymdhis') . ".csv\"");
			header("Pragma: no-cache");
			header("Expires: 0");
			$filename				 = "QrReport" . date('YmdHi') . ".csv";
			//$foldername = PUBLIC_PATH;
			$foldername				 = Yii::app()->params['uploadPath'];
			// $foldername = 'D:\Exported';
			$backup_file			 = $foldername . DIRECTORY_SEPARATOR . $filename;
			if (!is_dir($foldername))
			{
				mkdir($foldername);
			}
			if (file_exists($backup_file))
			{
				unlink($backup_file);
			}
			$command	 = true;
			$rows		 = $model->getQrList($command);
			$adminList	 = Admins::getAdminList();
			$handle		 = fopen("php://output", 'w');
			fputcsv($handle, ['Qr Code', 'Allocated By', 'Allocated To', 'Allocated On', 'Activated By', 'Activated On', 'Latitude', 'Longitude', 'Name',
				'Number', 'Scanned Count', 'Lead Count', 'Booking Count', 'Approve By', 'Approve On', 'Approval Status', 'Status', 'Contact Image Path', 'Location Image Path']);
			if (count($rows) > 0)
			{
				foreach ($rows as $row)
				{
					$rowArray = array();
					switch ($row['qrc_ent_type'])
					{
						case 1: //consumer
							$customerList	 = Users::getById($row['qrc_ent_id']);
							$allocatedTo	 = $customerList['ctt_first_name'] . ' ' . $customerList['ctt_last_name'] . " (" . "Consumer" . ")";
							break;
						case 2: //vendor
							$vendorList		 = Vendors::getById($row['qrc_ent_id']);
							$allocatedTo	 = $vendorList['vnd_name'] . " (" . "Vendor" . ")";
							break;
						case 3: //driver
							$driverList		 = Drivers::getByDriverId($row['qrc_ent_id']);
							$allocatedTo	 = $driverList['ctt_first_name'] . ' ' . $driverList['ctt_last_name'] . "(" . "Driver" . ")";
							break;
						case 4: //admin
							$allocatedTo	 = $adminList[$row['qrc_ent_id']] . " (" . "Admin" . ")";
							break;
						case 5: //agent
							$agentList		 = Agents::getById($row['qrc_ent_id']);
							$allocatedTo	 = $agentList['agt_fname'] . ' ' . $agentList['agt_lname'] . "(" . "Agent" . ")";
							break;
						default:
							break;
					}

					$rowArray['qrc_code']			 = $row['qrc_code'];
					$rowArray['qrc_allocated_by']	 = ($row['qrc_allocated_by'] != '') ? $adminList[$row['qrc_allocated_by']] : '-';
					$rowArray['qrc_ent_id']			 = $allocatedTo;
					$rowArray['qrc_allocate_date']	 = ($row['qrc_allocate_date'] != '') ? $row['allocatedDate'] : '-';
					$rowArray['qrc_activated_by']	 = ($row['qrc_activated_by'] != '') ? $adminList[$row['qrc_activated_by']] : '-';
					$rowArray['qrc_activated_date']	 = ($row['qrc_activated_date'] != '') ? $row['activatedDate'] : '-';
					$rowArray['qrc_location_lat']	 = $row['qrc_location_lat'];
					$rowArray['qrc_location_long']	 = $row['qrc_location_long'];
					$rowArray['qrc_contact_name']	 = $row['qrc_contact_name'];
					$rowArray['qrc_contact_phone']	 = $row['qrc_contact_phone'];
					$rowArray['qrc_scanned_count']	 = $row["qrc_scanned_count"];
					$rowArray['bkgTempCnt']			 = $row['bkgTempCnt'];
					$rowArray['bkgCnt']				 = $row['bkgCnt'];
					$rowArray['qrc_approved_by']	 = ($row['qrc_approved_by'] != '') ? $adminList[$row['qrc_approved_by']] : '-';
					$rowArray['qrc_approved_date']	 = ($row['qrc_approved_date'] != '') ? $row['approvedDate'] : '-';
					$rowArray['qrc_approval_status'] = $row['approvedStatus'];
					$rowArray['qrc_status']			 = $row['qrStatus'];
					$rowArray['qrc_contact_pic']	 = QrCode::getDocPathById($row['qrc_id'], 2);
					$rowArray['qrc_location_pic']	 = QrCode::getDocPathById($row['qrc_id'], 1);

					$row1 = array_values($rowArray);
					fputcsv($handle, $row1);
				}
			}
			fclose($handle);
			if (!$rows)
			{
				die('Could not take data backup: ' . mysql_error());
			}
			exit;
		}

		$dataProvider							 = $model->getQrList();
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);

		$this->render('list', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function sendOtp()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->rawBody;
		$jsonObj	 = CJSON::decode($data, false);
		try
		{
			if ($jsonObj->qr_id == '' || $jsonObj->contact_number == '')
			{
				throw new Exception("invalid request", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$contactNumber	 = $jsonObj->contact_number;
			$name			 = $jsonObj->contact_name;
			$inputOtp		 = $jsonObj->otp;
			if ($inputOtp == "")
			{
				$saveOtp = QrCode::otpSend($jsonObj->qr_id, $contactNumber, $name);

				if ($saveOtp)
				{
					$otp = ["otp" => (int) $saveOtp];

					$msg = "OTP send successfully";
					$returnSet->setData($otp);
				}
				goto end;
			}
			$verifyOtp = QrCode::otpVerify($jsonObj->qr_id, $inputOtp);

			if ($verifyOtp == true)
			{
				$msg = "OTP verified successfully";
			}
			else
			{
				$msg = "Wrong OTP";
			}
			end:
			$returnSet->setStatus(true);
			$returnSet->setMessage($msg);
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function activation_OLD()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->getParam('data');
		$jsonObj	 = CJSON::decode($data, false);

		try
		{
			if ($jsonObj->qr_id == "")
			{
				throw new Exception("invalid request", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$qrModel	 = QrCode::model()->findByPk($jsonObj->qr_id);
			$qrStatus	 = $qrModel['qrc_status'];
			if ($qrModel['qrc_status'] == '3')
			{
				throw new Exception("This sticker is not ready to be assigned", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$qrCode				 = QrCode::getQRCode($jsonObj->qr_id);
			#print_r($qrCode['qrc_code']);
			$uploadedLocFile	 = CUploadedFile::getInstanceByName("location_img");
			$uploadedContactFile = CUploadedFile::getInstanceByName("contact_img");
			$modeld				 = new QrCode();
			if ($uploadedLocFile != "")
			{
				$type	 = "location";
				$success = $modeld->saveQrDoc($jsonObj->qr_id, $uploadedLocFile, $type);
			}
			if ($uploadedContactFile != "")
			{
				$type	 = "contact";
				$success = $modeld->saveQrDoc($jsonObj->qr_id, $uploadedContactFile, $type);
			}
			if ($data != "")
			{
				$saveActivation = QrCode::AddActivation($jsonObj, $contactNumber, $name);
			}

			$msg	 = "Congratulations! This gozospot is now activated. Gozospot id is " . $qrCode['qrc_code'] . ". Give this to the spot Owner.";
			$msg1	 = "Any person can now come to this location and scan the gozo QR code with their phone camera.";
			$msg2	 = "When they create a booking using this QR code, the gozospot owner agent will get ₹100 credit added to their gozospot agent account. 
					We will send them weekly SMS showing how many bookings were created and their current total gozo credits earned! 
					Our team will call them to get papers and other details when are ready to send them payments for bookings created using this gozospot. 
					Payment will be sent monthly.";
			$msg3	 = "If they have any question in the future, they can goto gozocabs.com/contact and ask for a call back from us.";

			$message = array($msg, $msg1, $msg2, $msg3);
			if ($saveActivation)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage($message);
			}
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function actionView()
	{
		$this->pageTitle = "Qr View";
		$id				 = Yii::app()->request->getParam('qrId');
		$qrModel		 = QrCode::model()->findByPk($id);

		$this->renderPartial('view', array('qrModel' => $qrModel));
	}

	public function actionStatus()
	{
		$returnSet	 = new ReturnSet();
		$id			 = Yii::app()->request->getParam('qrId');
		$qrModel	 = QrCode::model()->findByPk($id);
		try
		{
			$qrModel->qrc_active = 1 - $qrModel->qrc_active;
			if ($qrModel->save())
			{
				$this->redirect('qrAllocatedList');
			}
			else
			{
				$msg = "Invalid Request";
				$returnSet->setStatus(false);
				$returnSet->setMessage($msg);
			}
		}
		catch (Exception $ex)
		{
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function activation()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->getParam('data');
		Logger::trace("QRCODE Data : " . $data);
		$arrData	 = CJSON::decode($data, true);

		try
		{
			if (!isset($arrData['qr_id']) || $arrData['qr_id'] == "")
			{
				throw new Exception("invalid request", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$qrModel = QrCode::model()->findByPk($arrData['qr_id']);

			if ($qrModel['qrc_status'] == 3)
			{
				throw new Exception("This sticker is not ready to be assigned", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			if ($qrModel['qrc_status'] == 1)
			{
				throw new Exception("This sticker is not allocated yet", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			$uploadedLocFile	 = CUploadedFile::getInstanceByName("location_img");
			$uploadedContactFile = CUploadedFile::getInstanceByName("contact_img");

			if ($uploadedLocFile != "")
			{
				$success = QrCode::saveQrDoc($arrData['qr_id'], $uploadedLocFile, "location");
			}
			if ($uploadedContactFile != "")
			{
				$success = QrCode::saveQrDoc($arrData['qr_id'], $uploadedContactFile, "contact");
			}

			$saveActivation = QrCode::model()->addActivation($arrData);

			$msg	 = "Congratulations! This gozospot is now activated. Gozospot id is " . $qrModel->qrc_code . ". Give this to the spot Owner.";
			$msg1	 = "Any person can now come to this location and scan the gozo QR code with their phone camera.";
			$msg2	 = "When they create a booking using this QR code, the gozospot owner agent will get ₹100 credit added to their gozospot agent account. 
					We will send them weekly SMS showing how many bookings were created and their current total gozo credits earned! 
					Our team will call them to get papers and other details when are ready to send them payments for bookings created using this gozospot. 
					Payment will be sent monthly.";
			$msg3	 = "If they have any question in the future, they can goto gozocabs.com/contact and ask for a call back from us.";

			$message = array($msg, $msg1, $msg2, $msg3);
			if ($saveActivation)
			{
				$returnSet->setStatus(true);
				$returnSet->setMessage($message);
			}
			else
			{
				throw new Exception("Error while activating QR Code", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
		}
		catch (Exception $ex)
		{
			Logger::trace("QRCODE Activation Failed Msg : " . $ex->getMessage());
			Logger::trace("QRCODE Activation Failed Data : " . json_encode($data));
			$returnSet->setStatus(false);
			$returnSet = ReturnSet::setException($ex);
		}
		return $returnSet;
	}

	public function actionLeadList()
	{
		$this->pageTitle = "Qr Lead List";
		$model			 = new QrCode;
		$qrId			 = Yii::app()->request->getParam('qrId');

		$dataProvider							 = $model->getLeadListByQr($qrId);
		$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
		$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);
		$this->render('leadList', array('dataProvider' => $dataProvider, 'model' => $model));
	}

	public function actionApproveQr()
	{
		$this->pageTitle = "Approve Qr";
		$request		 = Yii::app()->request;
		$btnType		 = $request->getParam('btntype');
		$qrId			 = $request->getParam('id');
		$returnSet		 = new ReturnSet();
		if (!$qrId)
		{
			throw new CHttpException(404, "Qr id not found");
		}
		$returnSet = QrCode::model()->setApprove($btnType, $qrId);
		echo CJSON::encode(array('success' => $returnSet->getStatus(), 'message' => $returnSet->getMessage() ? $returnSet->getMessage() : $this->getError($returnSet)));
		Yii::app()->end();
	}

}
