<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class VehicleController extends BaseController
{

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
			array('allow', // allow all users to perform 'index' and 'view' actions
				'actions'	 => array(),
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
		$this->onRest('req.cors.access.control.allow.methods', function() {
			return ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']; //List of allowed http methods (verbs)
		});

		$this->onRest('post.filter.req.auth.user', function($validation) {
			$pos = false;
			$arr = $this->getURIAndHTTPVerb();
			$ri	 = array('/edit');
			foreach ($ri as $value)
			{
				if (strpos($arr[0], $value))
				{
					$pos = true;
				}
			}
			return $validation ? $validation : ($pos != false);
		});


		$this->onRest('req.post.edit.render', function() {

			$token	 = $this->emitRest(ERestEvent::REQ_AUTH_USERNAME);
			$check	 = Drivers::model()->authoriseDriver($token);
			if ($check)
			{
				$oldData = $newData = false;
				$success = false;
				$errors	 = '';

				/*
				  $process_sync_data		    = Yii::app()->request->getParam('data');
				  //$process_sync_data		 = '{"vhc_id":"29376","vhc_type_id":"88","vhc_number":"RO 56 ROY 3456","vhc_year":"2014","vhc_color":"red","vhc_reg_owner":"abhi","vhc_insurance_exp_date":"2018-10-18","vhc_tax_exp_date":"2018-11-15","vhc_dop":"2018-11-15 00:00:00","vhc_owned_or_rented":"0","vhc_is_attached":"0","vhc_pollution_exp_date":"2018-10-31","vhc_reg_exp_date":"2019-02-28","vhc_commercial_exp_date":"2018-10-18","vhc_fitness_cert_end_date":"2018-09-14"}';
				  $vehiclePic			        = Yii::app()->request->getParam('vehiclePic');
				  //$vehiclePic			 = 1;
				 */

				/* New
				 * {     "vehiclePic" : 1,     "data" : {"vhc_id":"29376","vhc_type_id":"88","vhc_number":"RO 56 ROY 3456","vhc_year":"2014","vhc_color":"red","vhc_reg_owner":"abhi","vhc_insurance_exp_date":"2018-10-18","vhc_tax_exp_date":"2018-11-15","vhc_dop":"2018-11-15 00:00:00","vhc_owned_or_rented":"0","vhc_is_attached":"0","vhc_pollution_exp_date":"2018-10-31","vhc_reg_exp_date":"2019-02-28","vhc_commercial_exp_date":"2018-10-18","vhc_fitness_cert_end_date":"2018-09-14"}     }
				 */



				$wholeData1			 = Yii::app()->request->getParam('data');
				Logger::create("VEHICLE EDIT =>" . $wholeData1, CLogger::LEVEL_TRACE);
				$wholeData			 = CJSON::decode($wholeData1, true);
				$process_sync_data	 = json_encode($wholeData['data']);

				//print'<pre>';print_r($wholeData);exit;
				$vehiclePic = $wholeData['vehiclePic'];


				$insurance					 = $_FILES['insurance']['name'];
				$insurance_tmp				 = $_FILES['insurance']['tmp_name'];
				$front_plate				 = $_FILES['front_plate']['name'];
				$front_plate_tmp			 = $_FILES['front_plate']['tmp_name'];
				$rear_plate					 = $_FILES['rear_plate']['name'];
				$rear_plate_tmp				 = $_FILES['rear_plate']['tmp_name'];
				$pollution_certificate		 = $_FILES['pollution_certificate']['name'];
				$pollution_certificate_tmp	 = $_FILES['pollution_certificate']['tmp_name'];
				$reg_certificate			 = $_FILES['reg_certificate']['name'];
				$reg_certificate_tmp		 = $_FILES['reg_certificate']['tmp_name'];
				$permit_certificate			 = $_FILES['permit_certificate']['name'];
				$permit_certificate_tmp		 = $_FILES['permit_certificate']['tmp_name'];
				$fitness_certificate		 = $_FILES['fitness_certificate']['name'];
				$fitness_certificate_tmp	 = $_FILES['fitness_certificate']['tmp_name'];
				$data						 = CJSON:: decode($wholeData['data'], true);
				$userInfo					 = UserInfo::getInstance();
				// echo $data['vhc_id'];
				// print'<pre>';print_r($data);exit;
				//$driverId	 = $userInfo->userId;
				$driverId					 = UserInfo::getEntityId();

				$vehicleId	 = $data['vhc_id'];
				$model		 = Vehicles::model()->findById($data['vhc_id']);
				$oldData	 = $model->attributes;
				$newData	 = $data;
				//////////new code//////////
				Logger::create("Request: " . json_encode($_POST + $_GET + $_FILES), CLogger::LEVEL_TRACE);
				try
				{
					$transaction = DBUtil::beginTransaction();

					if ($data['vhc_id'] > 0)
					{
						$model				 = Vehicles::model()->findByPk($data['vhc_id']);
						$model->attributes	 = $data;
						if ($model->validate())
						{
							$model->save();

							// insurance upload
							if ($insurance != '')
							{
								$checkApprove = VehicleDocs::model()->checkApproveDocByVhcId($vehicleId, 1);
								if ($checkApprove <> 1)
								{
									$type	 = VehicleDocs::model()->getDocType(1);
									$result1 = $this->saveVehicleImage($insurance, $insurance_tmp, $vehicleId, $type);
									$path1	 = str_replace("\\", "\\\\", $result1['path']);
									$modeld	 = new VehicleDocs();
									$success = $modeld->saveDocument($vehicleId, $path1, $userInfo, 1);
									if ($success)
									{
										$errors = [];
										Logger::create('SUCCESS =====> : ' . "CAR insurance Id:" . $vehicleId . " - " . $path1, CLogger::LEVEL_INFO);
									}
									else
									{
										$getErrors = "Insurance not uploaded. Please upload.";
										throw new Exception($getErrors);
									}
								}
							}

							// front license upload
							if ($front_plate != '')
							{
								$checkApprove = VehicleDocs::model()->checkApproveDocByVhcId($vehicleId, 2);
								if ($checkApprove <> 1)
								{

									$type	 = VehicleDocs::model()->getDocType(2);
									$result1 = $this->saveVehicleImage($front_plate, $front_plate_tmp, $vehicleId, $type);
									$path1	 = str_replace("\\", "\\\\", $result1['path']);
									$modeld	 = new VehicleDocs();
									$success = $modeld->saveDocument($vehicleId, $path1, $userInfo, 2);
									if ($success)
									{
										$errors = [];
										Logger::create('SUCCESS =====> : ' . "CAR front license Id:" . $vehicleId . " - " . $path1, CLogger::LEVEL_INFO);
									}
									else
									{
										$getErrors = "Front license not uploaded. Please upload.";
										throw new Exception($getErrors);
									}
								}
							}

							// rear license upload
							if ($rear_plate != '')
							{
								$checkApprove = VehicleDocs::model()->checkApproveDocByVhcId($vehicleId, 3);
								if ($checkApprove <> 1)
								{
									$type	 = VehicleDocs::model()->getDocType(3);
									$result1 = $this->saveVehicleImage($rear_plate, $rear_plate_tmp, $vehicleId, $type);
									$path1	 = str_replace("\\", "\\\\", $result1['path']);
									$modeld	 = new VehicleDocs();
									$success = $modeld->saveDocument($vehicleId, $path1, $userInfo, 3);
									if ($success)
									{
										$errors = [];
										Logger::create('SUCCESS =====> : ' . "CAR rear license Id:" . $vehicleId . " - " . $path1, CLogger::LEVEL_INFO);
									}
									else
									{
										$getErrors = "Rear license not uploaded. Please upload.";
										throw new Exception($getErrors);
									}
								}
							}

							// PUC upload
							if ($pollution_certificate != '')
							{
								$checkApprove = VehicleDocs::model()->checkApproveDocByVhcId($vehicleId, 4);
								if ($checkApprove <> 1)
								{
									$type	 = VehicleDocs::model()->getDocType(4);
									$result1 = $this->saveVehicleImage($pollution_certificate, $pollution_certificate_tmp, $vehicleId, $type);
									$path1	 = str_replace("\\", "\\\\", $result1['path']);
									$modeld	 = new VehicleDocs();
									$success = $modeld->saveDocument($vehicleId, $path1, $userInfo, 4);
									if ($success)
									{
										$errors = [];
										Logger::create('SUCCESS =====> : ' . "CAR PUC Id:" . $vehicleId . " - " . $path1, CLogger::LEVEL_INFO);
									}
									else
									{
										$getErrors = "PUC not uploaded. Please upload.";
										throw new Exception($getErrors);
									}
								}
							}

							// RC upload
							if ($reg_certificate != '')
							{
								$checkApprove = VehicleDocs::model()->checkApproveDocByVhcId($vehicleId, 5);
								if ($checkApprove <> 1)
								{
									$type	 = VehicleDocs::model()->getDocType(5);
									$result1 = $this->saveVehicleImage($reg_certificate, $reg_certificate_tmp, $vehicleId, $type);
									$path1	 = str_replace("\\", "\\\\", $result1['path']);
									$modeld	 = new VehicleDocs();
									$success = $modeld->saveDocument($vehicleId, $path1, $userInfo, 5);
									if ($success)
									{
										$errors = [];
										Logger::create('SUCCESS =====> : ' . "CAR registration certificate Id:" . $vehicleId . " - " . $path1, CLogger::LEVEL_INFO);
									}
									else
									{
										$getErrors = "Registration certificate not uploaded. Please upload.";
										throw new Exception($getErrors);
									}
								}
							}

							// permit upload
							if ($permit_certificate != '')
							{
								$checkApprove = VehicleDocs::model()->checkApproveDocByVhcId($vehicleId, 6);
								if ($checkApprove <> 1)
								{
									$type	 = VehicleDocs::model()->getDocType(6);
									$result1 = $this->saveVehicleImage($permit_certificate, $permit_certificate_tmp, $vehicleId, $type);
									$path1	 = str_replace("\\", "\\\\", $result1['path']);
									$modeld	 = new VehicleDocs();
									$success = $modeld->saveDocument($vehicleId, $path1, $userInfo, 6);
									if ($success)
									{
										$errors = [];
										Logger::create('SUCCESS =====> : ' . "CAR permit Id:" . $vehicleId . " - " . $path1, CLogger::LEVEL_INFO);
									}
									else
									{
										$getErrors = "Permit not uploaded. Please upload.";
										throw new Exception($getErrors);
									}
								}
							}

							// fitness_certificate upload
							if ($fitness_certificate != '')
							{
								$checkApprove = VehicleDocs::model()->checkApproveDocByVhcId($vehicleId, 7);
								if ($checkApprove <> 1)
								{
									$type	 = VehicleDocs::model()->getDocType(7);
									$result1 = $this->saveVehicleImage($fitness_certificate, $fitness_certificate_tmp, $vehicleId, $type);
									$path1	 = str_replace("\\", "\\\\", $result1['path']);
									$modeld	 = new VehicleDocs();
									$success = $modeld->saveDocument($vehicleId, $path1, $userInfo, 7);
									if ($success)
									{
										$errors = [];
										Logger::create('SUCCESS =====> : ' . "CAR fitness Id:" . $vehicleId . " - " . $path1, CLogger::LEVEL_INFO);
									}
									else
									{
										$getErrors = "Fitness certificate not uploaded. Please upload.";
										throw new Exception($getErrors);
									}
								}
							}
							$success = DBUtil::commitTransaction($transaction);
						}
						else
						{
							$getErrors = $model->getErrors();
							throw new Exception("Validate Errors : " . $getErrors);
						}
					}
					else
					{
						$getErrors = $model->getErrors();
						throw new Exception("Vehicle Id is required : " . $getErrors);
					}
				}
				catch (Exception $ex)
				{
					DBUtil::rollbackTransaction($transaction);
					Logger::create('FALIURE ===========>: [' . $getErrors . ']', CLogger::LEVEL_ERROR);
					$errors = $getErrors;
				}

				Logger::create("Response: " . json_encode($model->attributes) . " Errors : " . $errors . " Success : " . $success, CLogger::LEVEL_INFO);
			}
			else
			{
				$errors	 = 'Unauthorised Driver';
				$success = false;
			}

			return $this->renderJSON([
						'type'	 => 'raw',
						'data'	 => array(
							'success'	 => $success,
							'errors'	 => $errors,
							'data'		 => ['vhc_id' => $model->vhc_id]
						),
			]);
		});

		$this->onRest('req.get.getlist.render', function() {
			return $this->getList();
		});
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
			if ($diff['vhc_color'])
			{
				$msg .= ' Vehicle Color: ' . $diff['vhc_color'] . ',';
			}
			if ($diff['vhc_type_id'])
			{
				$vhtModel	 = VehicleTypes::model()->findByPk($diff['vhc_type_id']);
				$vehicle	 = ($vhtModel->vht_make . " " . $vhtModel->vht_model);
				$msg		 .= ' Type: ' . $vehicle . ',';
			}
			/* if($diff['vhc_is_attached'])
			  {
			  $exclusiveStatus = ($diff['vhc_is_attached']==1)? 'Yes':'No';
			  $msg.=' Is exclusive to Gozo: ' . $exclusiveStatus . ',';
			  }
			  if($diff['vhc_is_commercial'])
			  {
			  $commercialStatus = ($diff['vhc_is_commercial']==1) ? 'Yes':'No';
			  $msg.=' Is Commercial: ' . $commercialStatus . ',';
			  }
			  if($diff['vhc_approved'])
			  {
			  $approveStatus = ($diff['vhc_approved']==1) ? 'Yes':'No';
			  $msg.=' Is Approved: ' . $approveStatus . ',';
			  } */
			if ($diff['vhc_owned_or_rented'])
			{
				$ownedrentedStatus	 = ($diff['vhc_owned_or_rented'] == 1) ? ' Yes' : 'No';
				$msg				 .= ' Ownership Status: ' . $ownedrentedStatus . ',';
			}
			if ($diff['insuranceFile'])
			{
				$msg .= ' : ' . $diff['insuranceFile'] . ',';
			}
			if ($diff['frontLicenseFile'])
			{
				$msg .= ' : ' . $diff['frontLicenseFile'] . ',';
			}
			if ($diff['rearLicenseFile'])
			{
				$msg .= ' : ' . $diff['rearLicenseFile'] . ',';
			}
			if ($diff['pollutionFile'])
			{
				$msg .= ' : ' . $diff['pollutionFile'] . ',';
			}
			if ($diff['registrationFile'])
			{
				$msg .= ' : ' . $diff['registrationFile'] . ',';
			}
			if ($diff['permitFile'])
			{
				$msg .= ' : ' . $diff['permitFile'] . ',';
			}
			if ($diff['fitnessFile'])
			{
				$msg .= ' : ' . $diff['fitnessFile'] . ',';
			}
			$msg = rtrim($msg, ',');
		}
		return $msg;
	}

	public function saveVehicleImage($image, $imagetmp, $vehicleId, $type)
	{
		try
		{
			$path = "";
			if ($image != '')
			{
				$image	 = $vehicleId . "-" . $type . "-" . date('YmdHis') . "." . $image;
				$dir	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments';
				if (!is_dir($dir))
				{
					mkdir($dir);
				}
				$dirFolderName = $dir . DIRECTORY_SEPARATOR . 'vehicles';
				if (!is_dir($dirFolderName))
				{
					mkdir($dirFolderName);
				}
				$dirByVehicleId = $dirFolderName . DIRECTORY_SEPARATOR . $vehicleId;
				if (!is_dir($dirByVehicleId))
				{
					mkdir($dirByVehicleId);
				}
				$file_path	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'vehicles' . DIRECTORY_SEPARATOR . $vehicleId;
				$file_name	 = basename($image);
				$f			 = $file_path;
				$file_path	 = $file_path . DIRECTORY_SEPARATOR . $file_name;
				file_put_contents(PUBLIC_PATH . '/testFile.txt', $f . ' ==== ' . $file_name);
				Yii::log("Image Path: \n\t Temp: " . $imagetmp . "\n\t Path: " . $f, CLogger::LEVEL_INFO, 'system.api.images');
				if (Vehicles::model()->img_resize($imagetmp, 1200, $f, $file_name))
				{
					$path	 = substr($file_path, strlen(PUBLIC_PATH));
					$result	 = ['path' => $path];
				}
			}
		}
		catch (Exception $e)
		{
			Yii::log("Exception thrown: \n\t" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, "system.api.images");
			Yii::log("Data Received: \n\t" . serialize(filter_input_array(INPUT_POST)), CLogger::LEVEL_ERROR, "system.api.images");
			throw $e;
		}
		return $result;
	}

	public function getList()
	{
		$returnSet	 = new ReturnSet();
		$data		 = Yii::app()->request->getParam('search_txt', '');
		$vndId		 = Yii::app()->request->getParam('vndId', 0);
		try
		{
			$drvId = UserInfo::getEntityId();
			if (!$drvId)
			{
				throw new Exception("Unauthorised", ReturnSet::ERROR_UNAUTHORISED);
			}
			if ($vndId == 0)
			{
				throw new Exception("No Vendor Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$cabData	 = Vehicles::getListByVendor($vndId, trim($data));
			$vehicle	 = new Stub\common\Vehicle();
			$vehicle->getList($cabData);
			$response	 = Filter::removeNull($vehicle);
			if (!$response)
			{
				throw new Exception("No Data Found", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$returnSet->setStatus(true);
			$returnSet->setData($response);
		}
		catch (Exception $e)
		{
			$returnSet = ReturnSet::setException($e);
		}
		return $this->renderJSON($returnSet);
	}

}
