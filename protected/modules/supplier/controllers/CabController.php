<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class CabController extends BaseController
{

	public $layout = 'main';
	public $email_receipient, $pageTitle1, $pageDesc;

	public function filters()
	{
		return array(
//            array(
//                'application.filters.HttpsFilter + create',
//                'bypass' => false),
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

	public function accessRules()
	{
		return array(
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array('list','edit','detach','add','checkexisting'),
				'users'		 => array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'	 => array('test',
					'REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS'),
				'users'		 => array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'	 => array(),
				'users'		 => array('admin'),
			),
			array('deny', // deny all users
				'users' => array('*'),
				'deniedCallback' => function() { Yii::app()->controller->redirect(array ('/supplier/user/signin')); }

			),
		);
	}

	public function actionList()
	{
		$model = new Vehicles('abcdjd');
		$model->vhc_approved = null;
		$vndId = Yii::app()->user->getEntityID();
		if (!$vndId)
		{
			throw new Exception("You are not a vendor.", ReturnSet::ERROR_UNAUTHORISED);
		}
		if (isset($_REQUEST['Vehicles']))
		{
			$model->attributes = Yii::app()->request->getParam('Vehicles');
		}
		$dataProvider	 = Vehicles::getCabListByVendor($vndId, $model->vhc_number, true,false,$model);
		$dataProvider->getPagination()->params = array_filter($_GET + $_POST);
        $dataProvider->getSort()->params       = array_filter($_GET + $_POST);

		$this->render('list',['model'=>$model,'dataProvider'=>$dataProvider]);
	}

	public function actionAdd()
	{
		$this->pageTitle = "Edit Vehicle";
		$vehicleId		 = Yii::app()->request->getParam('veditid');
		$model			 = Vehicles::model()->findByPk($vehicleId);
		$vndId = Yii::app()->user->getEntityID();
		$oldDocsData	 = array();
		$newDocsData	 = array();
		$vhcLog			 = 0;
		$request		 = Yii::app()->request;
		if ($model == '')
		{
			$this->pageTitle = "Add Vehicle";
			$model			 = new Vehicles();
			$isNew			 = true;
		}
		else
		{
			$modelVendor = Vendors::model()->findByPk($model->vhc_vendor_id1);
			if ($modelVendor != '')
			{
				$model->vnd_name = $modelVendor->vnd_name;
			}
			$isNew	 = false;
			$vhcLog	 = 1;
		}
		$model->scenario = 'supplierUpdate';
		if ($request->getPost('Vehicles'))
		{

			$arr1					 = Yii::app()->request->getParam('Vehicles');
			$arr1['vhc_vendor_id1']  = $vndId;
			
			$oldData				 = $model->attributes;
			$model->oldAttributes	 = $model->attributes;

			$vndModel = Vendors::model()->findByPk($vndId);
			if (in_array($vndModel->vnd_active, [0, 2, 3, 4]))
			{
				$activeList	 = $vndModel->vendorStatus;
				$status		 = $activeList[$vndModel->vnd_active];

				throw new Exception("Your account is in $status status.", ReturnSet::ERROR_UNAUTHORISED);
			}


			if ($arr1['vhc_id'] > 0)
			{
				$model			 = Vehicles::model()->findById($arr1['vhc_id']);
				$model->scenario = 'supplierUpdate';
			}
			$model->attributes = array_filter($arr1);

			$model->applypostdata($request);

			if ($model->vhcStat != '')
			{
				$model->vhcStat->save();
			}

			$model->vhc_active = 1;

			$uploadedFile1	 = CUploadedFile::getInstance($model, "vhc_insurance_proof");
			$uploadedFile2	 = CUploadedFile::getInstance($model, "vhc_front_plate");
			$uploadedFile3	 = CUploadedFile::getInstance($model, "vhc_rear_plate");
			$uploadedFile4	 = CUploadedFile::getInstance($model, "vhc_pollution_certificate");
			$uploadedFile5	 = CUploadedFile::getInstance($model, "vhc_reg_certificate");
			$uploadedFile6	 = CUploadedFile::getInstance($model, "vhc_permits_certificate");
			$uploadedFile7	 = CUploadedFile::getInstance($model, "vhc_fitness_certificate");
			$uploadedFile8	 = CUploadedFile::getInstance($model, "vhc_back_reg_certificate");
			$transaction	 = Yii::app()->db->beginTransaction();
			if ($model->validate())
			{
				try
				{
					$model->attributes			 = array_filter($_POST['Vehicles']);
					$model->vhc_owned_or_rented	 = $_POST['Vehicles']['vhc_owned_or_rented'];
					$model->applypostdata($request);

					$tempInsuranceApprove = $tempRegCertificateApprove = 0;
					if ($request->getPost('Vehicles')['vhc_temp_insurance_approved'][0] == 1)
					{
						$tempInsuranceApprove = 1;
					}

					if ($request->getPost('Vehicles')['vhc_temp_reg_certificate_approved'][0] == 1 || $request->getPost('Vehicles')['vhc_back_temp_reg_certificate_approved'][0] == 1)
					{
						$tempRegCertificateApprove = 1;
					}

					$model->vhc_active		 = 1;
					$model->vhc_modified_at	 = new CDbExpression('NOW()');
					if (isset($model->vhc_trip_type) && $model->vhc_trip_type != '')
					{
						$model->vhc_trip_type = implode(',', $model->vhc_trip_type);
					}
					$success	 = $model->save();
					$userInfo	 = UserInfo::getInstance();

					if ($success)
					{

						$codeArr = Filter::getCodeById($model->vhc_id, "car");
						if ($codeArr['success'] == 1)
						{
							$model->vhc_code = $codeArr['code'];
							$model->save();
						}
						$model = Vehicles::model()->findByPk($model->vhc_id);
						if ($arr1['vhc_vendor_id1'] > 0)
						{
							$model				 = Vehicles::model()->findByPk($model->vhc_id);
							$data				 = ['vendor' => $arr1['vhc_vendor_id1'], 'vehicle' => $model->vhc_id];
							$linked				 = VendorVehicle::model()->checkAndSave($data);
							$vendorUnlink		 = VendorVehicle::model()->unlinkOther($model->vhc_id, $arr1['vhc_vendor_id1']); //inactive other vendor car if new vendor added
							$vendorVehicleModel	 = VendorVehicle::model()->findByVndVhcId($arr1['vhc_vendor_id1'], $model->vhc_id);
							if ($vendorVehicleModel)
							{

								if ($request->getPost('Vehicles')['vhc_owned_or_rented'] != "")
								{
									$vendorVehicleModel->vvhc_owner_or_not = $request->getPost('Vehicles')['vhc_owned_or_rented'];
								}
								$vendorVehicleModel->vvhc_active = 1;
								$vendorVehicleModel->save();
							}
						}
						//LOU 
								/* @var $objCab \Beans\common\Cab */
					if($model->vhc_owned_or_rented == 2 && $model->vhc_id == '')
					{
						$jsonMapper	 = new JsonMapper();
						$jsonObj->id = $model->vhc_id;
						$jsonObj->isOwner = 2;
						$jsonObj->owner->firstName = $model->lou_owner_name = $request->getPost('Vehicles')['lou_owner_name'];
						$jsonObj->owner->lastName = $model->lou_owner_surname = $request->getPost('Vehicles')['lou_owner_surname'];
						$jsonObj->owner->email[0]->address = $model->lou_email = $request->getPost('Vehicles')['lou_email'];
						$jsonObj->owner->phone[0]->isdCode = '91';
						$jsonObj->owner->phone[0]->number = $model->lou_phone = $request->getPost('Vehicles')['lou_phone'];
						$jsonObj->owner->pan =  $model->lou_pan = $request->getPost('Vehicles')['lou_pan'];
						$jsonObj->owner->dlNumber = $model->lou_drv_licence = $request->getPost('Vehicles')['lou_drv_licence'];
						$jsonObj->LOU->validTill  = $model->lou_auth_end_date = ($request->getPost('Vehicles')['lou_auth_end_date']!='')?DateTimeFormat::DatePickerToDate($request->getPost('Vehicles')['lou_auth_end_date']):'';

						if(!$model->validateLOUdata())
						{
							$success = false;
							$transaction->rollback();
							goto endofaction;
						}
						$objCab			 = $jsonMapper->map($jsonObj, new Beans\common\Cab());
						$objOwner		 = $jsonMapper->map($jsonObj->owner, new Beans\contact\Person());
						$objCab->owner	 = $objOwner;
						$vhcId			 = $objCab->id;
						
						$licenseNo	 = trim($objCab->owner->dlNumber);
						$pan		 = trim($objCab->owner->pan);

						if ($licenseNo && $pan)
						{
							$cttOwnerId = \Contact::getIdByLicensePan($pan, $licenseNo);
						}
						if ($cttOwnerId)
						{
							$objCab->owner->id = $cttOwnerId;
						}

						if (trim($model->vhc_reg_owner) == '')
						{
							$model->vhc_reg_owner		 = $objCab->owner->firstName;
							$model->vhc_reg_owner_lname	 = $objCab->owner->lastName;
						}

						$dataArr = $model->addVehicle_V2([], $vndId, $model);

						$success = $dataArr['success'];
						if (!$success && $dataArr['errors'] != '')
						{
							throw new Exception(json_encode([$dataArr['errors']]), ReturnSet::ERROR_VALIDATION);
						}

						$vendorVehicleModel = VendorVehicle::model()->findByVndVhcId($vndId, $vhcId);

						$linkId = ($vendorVehicleModel->vvhc_id > 0) ? $vendorVehicleModel->vvhc_id : null;

						/* @var $objCab \Beans\common\Cab */
						$modelVvhc = $objCab->setLOUData($model, $vndId, $linkId);

						$modelVvhc->scenario = 'updateUnderTaking';
						if ($modelVvhc->validate())
						{
							if ($modelVvhc->vvhcVhc->save())
							{
								if (!$modelVvhc->save())
								{
									$errors = $modelVvhc->getErrors();
									throw new Exception(CJSON::encode($errors), ReturnSet::ERROR_VALIDATION);
								}
							}
						}
					}
						//lou
						
						//file upload section
						if ($uploadedFile1 != '')
						{
							$successUploadFile = VehicleDocs::saveDoc($uploadedFile1, $model->vhc_id, 1, $userInfo);
						}
						if ($uploadedFile2 != '')
						{
							$successUploadFile = VehicleDocs::saveDoc($uploadedFile2, $model->vhc_id, 2, $userInfo);
						}
						if ($uploadedFile3 != '')
						{
							$successUploadFile = VehicleDocs::saveDoc($uploadedFile3, $model->vhc_id, 3, $userInfo);
						}
						if ($uploadedFile4 != '')
						{
							$successUploadFile = VehicleDocs::saveDoc($uploadedFile4, $model->vhc_id, 4, $userInfo);
						}
						if ($uploadedFile5 != '')
						{
							$successUploadFile = VehicleDocs::saveDoc($uploadedFile5, $model->vhc_id, 5, $userInfo);
						}
						if ($uploadedFile6 != '')
						{
							$successUploadFile = VehicleDocs::saveDoc($uploadedFile6, $model->vhc_id, 6, $userInfo);
						}
						if ($uploadedFile7 != '')
						{
							$successUploadFile = VehicleDocs::saveDoc($uploadedFile7, $model->vhc_id, 7, $userInfo);
						}
						if ($uploadedFile8 != '')
						{
							$successUploadFile = VehicleDocs::saveDoc($uploadedFile8, $model->vhc_id, 13, $userInfo);
						}

						if ($successUploadFile)
						{
							$model->vhc_modified_at	 = new CDbExpression('NOW()');
							$success					 = $model->save();
							$model->pendingApproval($model->vhc_id, $userInfo);
						}
						//file upload section
						$newData				 = $model->attributes;
						$getOldDifferenceDocs	 = array_diff_assoc($oldDocsData, $newDocsData);
						if ($oldData['vhc_approved'] == 1)
						{
							$model->vhc_approved = 1;
						}
						if ($model->validate())
						{
							$model->vhc_modified_at	 = new CDbExpression('NOW()');
							$success				 = $model->save();

							if ($model->vhc_id != '')
							{
								$getOldDifference	 = array_diff_assoc($oldData, $newData);
								$changesForLog		 = "<br> Old Values: " . $this->getModificationMSG($getOldDifference, false);
								$changesForLog		 .= "<br>" . $this->getModificationMSG($getOldDifferenceDocs, false);
								$desc				 = "Cab modified | ";
								$desc				 .= $changesForLog;
								$event_id			 = VehiclesLog::VEHICLE_MODIFIED;
							}
							else
							{
								$desc		 = "Vehicle created |";
								$event_id	 = VehiclesLog::VEHICLE_CREATED;
								//check and insert in vehicle stat table
								$linked		 = VehicleStats::model()->checkAndSave($model->vhc_id);
							}
							VehiclesLog::model()->createLog($model->vhc_id, $desc, $userInfo, $event_id, false, false);
							if (!$success)
							{
								throw new Exception("Could not update Vehicle Log. (" . json_encode($model->getErrors()) . ")");
							}
							$model = Vehicles::model()->findByPk($model->vhc_id);
						}
					}
					$transaction->commit();
				}
				catch (Exception $e)
				{
					$success = false;
					$model->addError("vhc_id", $e->getMessage());
					$transaction->rollback();
				}
			}
			endofaction:
			if ($success)
			{
				if ($arr1['vhc_vendor_id1'] > 0)
				{
					VendorStats::model()->updateCarTypeCount($arr1['vhc_vendor_id1']);
				}
				$this->redirect(array('list'));
				Yii::app()->user->setFlash('success', 'Vehicle details updated successfully');
			}
			else
			{
				$errMessage = "";
				Yii::app()->user->setFlash('error', 'Error Occurred!<br/>');
				foreach ($model->getErrors() as $attribute => $errors)
				{
					foreach ($errors as $errVal)
					{
						 $errMessage .= $errVal."<br>";
					}
				}
				if($errMessage!=''){
				Yii::app()->user->setFlash('error', $errMessage . "<br/>");
				}
			}
		}
		$docModel = VehicleDocs::model()->findAllActiveDocByVhcId($model->vhc_id);

		$model->vehicleDocs = $docModel;
		//$model->vhc_type = $model->vhcType->vht_car_type; //Commented out this line.
		$this->render('add', array('model' => $model, 'isNew' => $isNew,'vndId'=>$vndId));
	}

	public function actionEdit()
	{
		$this->render('edit',['model'=>$model,'dataProvider'=>$dataProvider]);
	}

	public function actionDetach()
	{
		$this->render('detach',['model'=>$model,'dataProvider'=>$dataProvider]);
	}

	public function actionCheckexisting()
	{
		$vndid		 = Yii::app()->request->getParam('vndid');
		$vhcnumber	 = Yii::app()->request->getParam('vhcnumber');

		$qry1	 = [
			'vhc_vendor_id1' => $vndid,
			'vhc_number'	 => $vhcnumber,
		];
		$qry	 = array_filter($qry1);
		$cnt	 = count($qry);
		$found	 = Vehicles::model()->checkExisting($qry);
		$vhc	 = $found[0];
		if (!$vhc['vhc_id'])
		{
			$vhc['vhc_id'] = '0';
		}
		else
		{
			$vhc += Vehicles::model()->getDetailListbyId($vhc['vhc_id']);
			//$vhc = $vhc +
		}
		//  $vhc['vcount'] = $cnt;
		$v = [];
		if ($vhc['vendorids'])
		{
			$v = explode(',', $vhc['vendorids']);
			if (in_array($vndid, $v))
			{
				$vhc['this_vendor'] = 1;
			}
			else
			{
				$vhc['this_vendor'] = 0;
			}
		}
		$data	 = array_diff($vhc, ['']);
		$dataVal = CJSON::encode($data);
		echo $dataVal;
		Yii::app()->end();
	}

	public function getModificationMSG($diff, $user = false)
	{
		$msg = '';
		if (count($diff) > 0)
		{
			if ($diff ['vhc_number'])
			{
				$msg .= ' Vehicle Number: ' . $diff['vhc_number'] . ',';
			}
			if ($diff ['vhc_year'])
			{
				$msg .= ' Vehicle Year: ' . $diff['vhc_year'] . ',';
			}
			if ($diff['vhc_has_cng'] === '1')
			{
				$cngStatus	 = 'YES';
				$msg		 .= ' Vehicle CNG: ' . $cngStatus . ',';
			}
			elseif ($diff['vhc_has_cng'] === '0')
			{
				$cngStatus	 = "NO";
				$msg		 .= ' Vehicle CNG: ' . $cngStatus . ',';
			}
			if ($diff['vhc_has_electric'] === '1')
			{
				$elctricStatus	 = 'YES';
				$msg			 .= ' Vehicle Elctric: ' . $elctricStatus . ',';
			}
			elseif ($diff['vhc_has_electric'] === '0')
			{
				$elctricStatus	 = "NO";
				$msg			 .= ' Vehicle Elctric: ' . $elctricStatus . ',';
			}

			if ($diff['vhc_has_rooftop_carrier'] === '1')
			{
				$roofTopCarrierStatus	 = 'YES';
				$msg					 .= ' Vehicle Rooftop Carrier: ' . $roofTopCarrierStatus . ',';
			}
			elseif ($diff['vhc_has_rooftop_carrier'] === '0')
			{
				$roofTopCarrierStatus	 = "NO";
				$msg					 .= ' Vehicle Rooftop Carrier: ' . $roofTopCarrierStatus . ',';
			}

			if ($diff['vhc_color'])
			{
				$msg .= ' Vehicle Color: ' . $diff['vhc_color'] . ',';
			}
			if ($diff['vhc_type_id'])
			{
				$vhtModel	 = VehicleTypes::model()->findByPk($diff['vhc_type_id']);
				$vehicle	 = ($vhtModel->vht_make . " " . $vhtModel->vht_model);
				$msg		 .= ' Vehicle Type: ' . $vehicle . ',';
			}
			if ($diff['vhc_is_attached'])
			{
				$exclusiveStatus = ($diff['vhc_is_attached'] == 1) ? 'Yes' : 'No';
				$msg			 .= ' Is exclusive to Gozo: ' . $exclusiveStatus . ',';
			}
			if ($diff['vhc_is_commercial'])
			{
				$commercialStatus	 = ($diff['vhc_is_commercial'] == 1) ? 'Yes' : 'No';
				$msg				 .= ' Is Commercial: ' . $commercialStatus . ',';
			}

			if ($diff ['vhc_insurance_exp_date'])
			{
				$msg .= ' Insurance exp date: ' . $diff['vhc_insurance_exp_date'] . ',';
			}
			if ($diff ['vhc_pollution_exp_date'])
			{
				$msg .= ' Pollution certificate exp date: ' . $diff['vhc_pollution_exp_date'] . ',';
			}
			if ($diff ['vhc_reg_exp_date'])
			{
				$msg .= ' Registration exp date: ' . $diff['vhc_reg_exp_date'] . ',';
			}
			if ($diff['vhc_trip_type'])
			{

				$msg .= ' Trip Type : ' . Vehicles::getType($diff['vhc_trip_type']) . ',';
			}

			if ($diff ['vhc_commercial_exp_date'])
			{
				$msg .= ' Commercial Permit exp date: ' . $diff['vhc_commercial_exp_date'] . ',';
			}
			if ($diff ['vhc_fitness_cert_end_date'])
			{
				$msg .= ' FITNESS exp date: ' . $diff['vhc_fitness_cert_end_date'] . ',';
			}
			if ($diff['vhc_approved'] <> '')
			{
				switch ($diff['vhc_approved'])
				{
					case 0;
						$approveStatus	 = 'Not Verified';
						break;
					case 1;
						$approveStatus	 = 'Approved';
						break;
					case 2;
						$approveStatus	 = 'Pending Approval';
						break;
					case 3;
						$approveStatus	 = 'Rejected';
						break;
				}
				//$approveStatus = ($diff['vhc_approved']==1) ? 'Yes':'No';
				$msg .= ' Is Approved: ' . $approveStatus . ',';
			}
			if ($diff['vhc_owned_or_rented'])
			{
				$ownedrentedStatus	 = ($diff['vhc_owned_or_rented'] == 1) ? ' Yes' : 'No';
				$msg				 .= ' Vehicle owned or rented: ' . $ownedrentedStatus . ',';
			}
			if ($diff['insuranceFile'])
			{
				$msg .= ' Insurance : ' . $diff['insuranceFile'] . ',';
			}
			if ($diff['frontLicenseFile'])
			{
				$msg .= ' Front License : ' . $diff['frontLicenseFile'] . ',';
			}
			if ($diff['rearLicenseFile'])
			{
				$msg .= ' Rear License : ' . $diff['rearLicenseFile'] . ',';
			}
			if ($diff['pollutionFile'])
			{
				$msg .= ' Pollution under control : ' . $diff['pollutionFile'] . ',';
			}
			if ($diff['registrationFile'])
			{
				$msg .= ' Registration certificate : ' . $diff['registrationFile'] . ',';
			}
			if ($diff['permitFile'])
			{
				$msg .= ' Commercial permits : ' . $diff['permitFile'] . ',';
			}
			if ($diff['fitnessFile'])
			{
				$msg .= ' Fitness certificate : ' . $diff['fitnessFile'] . ',';
			}

			$msg = rtrim($msg, ',');
		}
		return $msg;
	}
}
