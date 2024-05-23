<?php
include_once(dirname(__FILE__) . '/BaseController.php');
class DriversController extends BaseController
{
	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter',
				'bypass' => false),
			array(
				'CHttpCacheFilter + country',
				'lastModified' => $this->getLastModified(),
			),
		);
	}
	function getLastModified()
	{
		$date = new DateTime('NOW');
		$date->sub(new DateInterval('PT50S'));
		return $date->format('Y-m-d H:i:s');
	}
	public function actionMyenc()
	{
		$drvId	 = '27506';
		$vhcId	 = '24164';		
	    echo $c	 = DriverCabDocs::model()->createHash($drvId, $vhcId);
	}
  function codeUnhash($dcCode)
	{
		$strDrv	 = substr($dcCode, 0, 5);
		$strVhc	 = substr($dcCode, 5, 5);
		$dTempId = Yii::app()->shortHash->unhash($strDrv);
		$vTempId = Yii::app()->shortHash->unhash($strVhc);
		$drvId	 = substr($dTempId, 0,-1);
		$vhcId	 = substr($vTempId, 0,-1);
		if ($drvId == $dTempId && $vhcId == $vTempId)
		{
			return false;
		}
		return ['drvId' => $drvId, 'vhcId' => $vhcId];
	}
	/* depricated */
//	public function actionUploaddocs()
//	{
//		 
//		$this->pageTitle = "Upload Docs";
//		$this->layout	 = 'head';
////		$vehicleId		 = Yii::app()->request->getParam('vhcid');
////		$drvId			 = Yii::app()->request->getParam('drvid');
//		$dcCode			 = Yii::app()->request->getParam('dccode');
//		$dcArr			 = $this->codeUnhash($dcCode);
//		$drvId		     = $dcArr['drvId'];
//		$vehicleId	     = $dcArr['vhcId'];
//		$uploadSuccess	 = Yii::app()->request->getParam('uploadsuccess', false);
//		$vmodel			 = Vehicles::model()->resetScope()->findByPk($vehicleId);
//		$dmodel			 = Drivers::model()->resetScope()->findById($drvId);
//		$errors1		 = [];
//		$errors2		 = [];
//		$errors			 = [];
//		$arrDriverList	 = DriverCabDocs::model()->getDriverDocsToUpload($drvId);
//		$arrVehicleList	 = DriverCabDocs::model()->getVehicleDocsToUpload($vehicleId);
//		if(isset($_POST['Vehicles']) && isset($_POST['Drivers']))
//		{
//			$driverArray		 = array();
//			$VehicleArray		 = array();
//			$userInfo			 = UserInfo::model();
//			$userInfo->userId	 = $drvId;
//			$userInfo->userType	 = UserInfo::TYPE_DRIVER;			
//			$success			 = false;
////			if (isset($_POST['Vehicles']))
////			{
//			$arr1 = Yii::app()->request->getParam('Vehicles');
//			$uploadedFile1		 = CUploadedFile::getInstance($vmodel, "vhc_insurance_proof");		
//			$uploadedFile2		 = CUploadedFile::getInstance($vmodel, "vhc_front_plate");
//			$uploadedFile3		 = CUploadedFile::getInstance($vmodel, "vhc_rear_plate");
//			$uploadedFile4		 = CUploadedFile::getInstance($vmodel, "vhc_pollution_certificate");
//			$uploadedFile5		 = CUploadedFile::getInstance($vmodel, "vhc_reg_certificate");
//			$uploadedFile6	     = CUploadedFile::getInstance($vmodel, "vhc_permits_certificate");
//			$uploadedFile7	     = CUploadedFile::getInstance($vmodel, "vhc_fitness_certificate");
//				if ($arr1['vhc_insurance_exp_date'] == '' && $arrVehicleList[0] == 1)
//				{
//					$vmodel->addError('vhc_insurance_exp_date', 'Insurance Expiry Date is required');
//				}
//				if ($uploadedFile1 == '' && $arrVehicleList[0] == 1)
//				{
//					$vmodel->addError('vhc_insurance_proof', 'Insurance Proof is not uploaded');
//				}												
//				if ($arr1['vhc_dop'] == '' && $arrVehicleList[4] == 5)
//				{
//					$vmodel->addError('vhc_dop', 'Date Of Purchase is required');
//				}				
//				if ($arr1['vhc_tax_exp_date'] == '' && $arrVehicleList[4] == 5)
//				{
//					$vmodel->addError('vhc_tax_exp_date', 'Tax Paid Upto Date is required');
//				}						
//				if ($arr1['vhc_reg_exp_date'] == '' && $arrVehicleList[4] == 5)
//				{
//					$vmodel->addError('vhc_reg_exp_date', 'Registration Expiry Date is required');
//				}
//				if ($uploadedFile5 == '' && $arrVehicleList[4] == 5)
//				{
//					$vmodel->addError('vhc_reg_certificate', 'Registration Certificate is not uploaded');
//				}
//				if ($uploadedFile6 == '' && $arrVehicleList[5] == 6)
//				{
//					$vmodel->addError('vhc_permits_certificate', 'Permits Certificate is not uploaded');
//				}
//				if ($arr1['vhc_commercial_exp_date'] == '' && $arrVehicleList[5] == 6)
//				{
//					$vmodel->addError('vhc_commercial_exp_date', 'Permits Date is required');
//				}
//				$errors1 = $vmodel->getErrors();
//				if ($vmodel->validate() && count($errors1) == 0)
//				{
//					$success = false;
//					try
//					{
//						$folderName = "vehicles";
//						if ($uploadedFile1 != '')
//						{
//							$type							 = VehicleDocs::model()->getDocType(1);
//							$path1							 = DriverCabDocs::model()->uploadAttachments($uploadedFile1, $type, $vmodel->vhc_id, $folderName);
//							$vmodel->vhc_insurance_exp_date	 = DateTimeFormat::DatePickerToDate($arr1['vhc_insurance_exp_date']);
//							$vdocs							 = new VehicleDocs();
//							$success						 = $vdocs->saveDocument($vmodel->vhc_id, $path1, $userInfo, 1);
//							array_push($VehicleArray, $vdocs->vhd_id);
//						}
//						if ($uploadedFile2 != '')
//						{
//							$type	 = VehicleDocs::model()->getDocType(2);
//							$path1	 = DriverCabDocs::model()->uploadAttachments($uploadedFile2, $type, $vmodel->vhc_id, $folderName);
//							$vdocs	 = new VehicleDocs();
//							$success = $vdocs->saveDocument($vmodel->vhc_id, $path1, $userInfo, 2);
//							array_push($VehicleArray, $vdocs->vhd_id);
//						}
//						if ($uploadedFile3 != '')
//						{
//							$type	 = VehicleDocs::model()->getDocType(3);
//							$path1	 = DriverCabDocs::model()->uploadAttachments($uploadedFile3, $type, $vmodel->vhc_id, $folderName);
//							$vdocs	 = new VehicleDocs();
//							$success = $vdocs->saveDocument($vmodel->vhc_id, $path1, $userInfo, 3);
//							array_push($VehicleArray, $vdocs->vhd_id);
//						}
//						if ($uploadedFile4 != '')
//						{
//							$type	                        = VehicleDocs::model()->getDocType(4);
//							$path1	                        = DriverCabDocs::model()->uploadAttachments($uploadedFile4, $type, $vmodel->vhc_id, $folderName);
//							$vmodel->vhc_pollution_exp_date = DateTimeFormat::DatePickerToDate($arr1['vhc_pollution_exp_date']);
//							$vdocs	 = new VehicleDocs();
//							$success = $vdocs->saveDocument($vmodel->vhc_id, $path1, $userInfo, 4);
//							array_push($VehicleArray, $vdocs->vhd_id);
//						}						
//						if ($uploadedFile5 != '')
//						{
//							$type						 = VehicleDocs::model()->getDocType(5);
//							$path1						 = DriverCabDocs::model()->uploadAttachments($uploadedFile5, $type, $vmodel->vhc_id, $folderName);
//							$vmodel->vhc_reg_exp_date	 = DateTimeFormat::DatePickerToDate($arr1['vhc_reg_exp_date']);
//							$vmodel->vhc_dop             = DateTimeFormat::DatePickerToDate($arr1['vhc_dop']);
//							$vmodel->vhc_tax_exp_date    = DateTimeFormat::DatePickerToDate($arr1['vhc_tax_exp_date']);
//							$vdocs						 = new VehicleDocs();
//							$success					 = $vdocs->saveDocument($vmodel->vhc_id, $path1, $userInfo, 5);
//							array_push($VehicleArray, $vdocs->vhd_id);
//						}
//						if ($uploadedFile6 != '')
//						{
//							$type	 = VehicleDocs::model()->getDocType(6);
//							$path1	 = DriverCabDocs::model()->uploadAttachments($uploadedFile6, $type, $vmodel->vhc_id, $folderName);
//							$vmodel->vhc_commercial_exp_date = DateTimeFormat::DatePickerToDate($arr1['vhc_commercial_exp_date']);
//							$vdocs	 = new VehicleDocs();
//							$success = $vdocs->saveDocument($vmodel->vhc_id, $path1, $userInfo, 6);
//							array_push($VehicleArray, $vdocs->vhd_id);
//						}
//						if ($uploadedFile7 != '')
//						{
//							$type	 = VehicleDocs::model()->getDocType(7);
//							$path1	 = DriverCabDocs::model()->uploadAttachments($uploadedFile7, $type, $vmodel->vhc_id, $folderName);
//							$vmodel->vhc_fitness_cert_end_date=DateTimeFormat::DatePickerToDate($arr1['vhc_fitness_cert_end_date']);
//							$vdocs	 = new VehicleDocs();
//							$success = $vdocs->saveDocument($vmodel->vhc_id, $path1, $userInfo, 7);
//							array_push($VehicleArray, $vdocs->vhd_id);
//						}		
//						    $vmodel->vhc_approved	 = 2;
//						    $success				 = $vmodel->save();
//					}
//					catch (Exception $e)
//					{
//						$vmodel->addError("vhc_id", $e->getMessage());
//					}
//				}				
////			}
////			if (isset($_POST['Drivers']))
////			{
//				$uploadedFile8		 = CUploadedFile::getInstance($dmodel, "drv_aadhaar_img_path");
//				$uploadedFile9		 = CUploadedFile::getInstance($dmodel, "drv_aadhaar_img_path2");
//				$uploadedFile10		 = CUploadedFile::getInstance($dmodel, "drv_pan_img_path");
//				$uploadedFile11		 = CUploadedFile::getInstance($dmodel, "drv_pan_img_path2");
//				$uploadedFile12		 = CUploadedFile::getInstance($dmodel, "drv_voter_id_img_path");
//				$uploadedFile13		 = CUploadedFile::getInstance($dmodel, "drv_voter_id_img_path2");
//				$uploadedFile14		 = CUploadedFile::getInstance($dmodel, "drv_licence_path");
//				$uploadedFile15		 = CUploadedFile::getInstance($dmodel, "drv_licence_path2");
//				$uploadedFile16		 = CUploadedFile::getInstance($dmodel, "drv_police_certificate");
//				$arr2			     = Yii::app()->request->getParam('Drivers');				
//				if($arr2['drv_voter_id'] == '' && $arrDriverList[1] )
//				{				
//					$dmodel->addError('drv_voter_id', 'VoterId Number  is required');
//				}
//				if ($uploadedFile12 == '' && in_array(1, $arrDriverList[1]))
//				{
//					$dmodel->addError('drv_voter_id_img_path', 'VoterId  Image is not uploaded');
//				}	
//				if($arr2['drv_pan_no'] == '' && $arrDriverList[2] )
//				{					
//					$dmodel->addError('drv_pan_no', 'PAN Card Number  is required');
//				}
//				if ($uploadedFile10 == '' && in_array(1, $arrDriverList[2]))
//				{
//					$dmodel->addError('drv_pan_img_path', 'PAN Card Image is not uploaded');
//				}				
//				if ($arr2['drv_lic_exp_date'] == '' && $arrDriverList[4])
//				{
//					$dmodel->addError('drv_lic_exp_date', 'Licence Expiry Date is required');
//				}
//				if ($uploadedFile14 == '' && in_array(1, $arrDriverList[4]))
//				{
//					$dmodel->addError('drv_licence_path', ' License Front Image is not uploaded');
//				}				
//				if ($arr2['drv_lic_number'] == '' && $arrDriverList[4])
//				{
//					$dmodel->addError('drv_lic_number', 'License Number is required');
//				}
//				if ($uploadedFile16 == '' && $arrDriverList[5])
//				{
//					$dmodel->addError('drv_police_certificate', 'Police Certificate Image is not uploaded');
//				}
//				$errors2 = $dmodel->getErrors();
//				if ($dmodel->validate() && count($errors2) == 0)
//				{
//					$success = false;
//					try
//					{
//						$folderName = "drivers";
//						if ($uploadedFile8 != '')
//						{
//							$type	 = "aadhar";
//							$path1	 =DriverCabDocs::model()->uploadAttachments($uploadedFile8, $type, $dmodel->drv_id, $folderName);
//							$dmodel->drv_aadhaar_no		 = $arr2['drv_aadhaar_no'];
//							$dDocs	 = new DriverDocs();
//							$success = $dDocs->saveDocument($dmodel->drv_id, $path1, $userInfo, $type);
//							array_push($driverArray, $dDocs->drd_id);
//						}
//						if ($uploadedFile9 != '')
//						{
//							$type	 = "aadharback";
//							$path1	 = DriverCabDocs::model()->uploadAttachments($uploadedFile9, $type, $dmodel->drv_id, $folderName);
//					
//							$dDocs	 = new DriverDocs();
//							$success = $dDocs->saveDocument($dmodel->drv_id, $path1, $userInfo, $type);
//							array_push($driverArray, $dDocs->drd_id);
//						}
//						if ($uploadedFile10 != '')
//						{
//							$type	 = "pan";
//							$path1	 = DriverCabDocs::model()->uploadAttachments($uploadedFile10, $type, $dmodel->drv_id, $folderName);
//							$dmodel->drv_pan_no		 = $arr2['drv_pan_no'];
//							$dDocs	 = new DriverDocs();
//							$success = $dDocs->saveDocument($dmodel->drv_id, $path1, $userInfo, $type);
//							array_push($driverArray, $dDocs->drd_id);
//						}						
//						if ($uploadedFile11 != '')
//						{
//							$type	 = "panback";
//							$path1	 = DriverCabDocs::model()->uploadAttachments($uploadedFile11, $type, $dmodel->drv_id, $folderName);
//							$dDocs	 = new DriverDocs();
//							$success = $dDocs->saveDocument($dmodel->drv_id, $path1, $userInfo, $type);
//							array_push($driverArray, $dDocs->drd_id);
//						}
//						if ($uploadedFile12 != '')
//						{
//							$type	 = "voterid";
//							$path1	 = DriverCabDocs::model()->uploadAttachments($uploadedFile12, $type, $dmodel->drv_id, $folderName);
//							$dmodel->drv_voter_id		 = $arr2['drv_voter_id'];
//							$dDocs	 = new DriverDocs();
//							$success = $dDocs->saveDocument($dmodel->drv_id, $path1, $userInfo, $type);
//							array_push($driverArray, $dDocs->drd_id);
//						}
//						if ($uploadedFile13 != '')
//						{
//							$type	 = "voterbackid";
//							$path1	 = DriverCabDocs::model()->uploadAttachments($uploadedFile13, $type, $dmodel->drv_id, $folderName);
//							$dDocs	 = new DriverDocs();
//							$success = $dDocs->saveDocument($dmodel->drv_id, $path1, $userInfo, $type);
//							array_push($driverArray, $dDocs->drd_id);
//						}
//						if ($uploadedFile14 != '')
//						{
//							$type						 = "license";
//							$path1						 = DriverCabDocs::model()->uploadAttachments($uploadedFile14, $type, $dmodel->drv_id, $folderName);
//							$dmodel->drv_lic_exp_date	 = DateTimeFormat::DatePickerToDate($arr2['drv_lic_exp_date']);
//							$dmodel->drv_lic_number		 = $arr2['drv_lic_number'];
//							$dDocs						 = new DriverDocs();
//							$success					 = $dDocs->saveDocument($dmodel->drv_id, $path1, $userInfo, $type);
//							array_push($driverArray, $dDocs->drd_id);
//						}
//						if ($uploadedFile15 != '')
//						{
//							$type	 = "licenseback";
//							$path1	 =DriverCabDocs::model()->uploadAttachments($uploadedFile15, $type, $dmodel->drv_id, $folderName);
//							$dDocs	 = new DriverDocs();
//							$success = $dDocs->saveDocument($dmodel->drv_id, $path1, $userInfo, $type);
//							array_push($driverArray, $dDocs->drd_id);
//						}
//						if ($uploadedFile16 != '')
//						{
//							$type	 = "policever";
//							$path1	 = DriverCabDocs::model()->uploadAttachments($uploadedFile16, $type, $dmodel->drv_id, $folderName);
//							$dDocs	 = new DriverDocs();
//							$success = $dDocs->saveDocument($dmodel->drv_id, $path1, $userInfo, $type);
//							array_push($driverArray, $dDocs->drd_id);
//						}
//						$dmodel->drv_approved	 = 2;
//						$success				 = $dmodel->save();
//					}
//					catch (Exception $e)
//					{
//						$dmodel->addError("drv_id", $e->getMessage());
//					}
//				}				 
////			}
//			$success1	 = false;
//			$errors		 = $errors1 + $errors2;
//			if ((sizeof($errors1) == 0 || sizeof($errors2) == 0) && $success)
//			{
//				$driverCabDoc = array('driverIDs'		     => $driverArray, 'vehicleIds'	 => $VehicleArray,
//				'dcd_drv_id'	 => $drvId, 'dcd_vhc_id'	 => $vehicleId, 'dcd_user_type'	 => 3,
//				'dcd_user_id'	 => $drvId);
//				$success1 = DriverCabDocs::model()->add($driverCabDoc);
//				$this->redirect(['uploaddocs', 'dccode' => $dcCode, 'uploadsuccess' => $success1]);
//			}
//		}
//		$this->render('uploaddocs', array('vmodel'	=> $vmodel, 'dmodel' => $dmodel,
//			'visibleDriverUpload'	 => $arrDriverList, 'visibleVehicleUpload'	 => $arrVehicleList, 'uploadsuccess'			 => $uploadSuccess,
//			'errors'				 => $errors));
//	}
}
