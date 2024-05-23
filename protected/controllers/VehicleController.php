<?php

include_once(dirname(__FILE__) . '/BaseController.php');

class VehicleController extends BaseController
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $newHome				 = '';
	public $layout				 = '//layouts/head1';
	public $fileatt;
	public $email_receipient;
	public $pageHeader			 = '';
	public $showProfileComplete	 = true;

	public function filters()
	{
		return array(
			array(
				'application.filters.HttpsFilter',
				'bypass' => false),
			//'postOnly + agentjoin,vendorjoin',
			'postOnly + agentjoin',
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

	public function actionInfo()
	{
		$request = Yii::app()->request;
		$vhcData = $request->getParam("Vehicles");

		$vhcModel = new Vehicles('vendorAttach');

		$vhcId = $this->getVehicleId();
		if ($vhcData['vhc_id'] > 0 && $vhcId != $vhcData['vhc_id'])
		{
			throw new Exception("Some error in data");
		}
		if ($vhcId > 0)
		{
			$vhcModel	 = Vehicles::model()->findByPk($vhcId);
			$vDocModels	 = VehicleDocs::getByVehicleId($vhcId);

			$insuranceDocModel		 = $vDocModels[1];
			$licenceFPlateDocModel	 = $vDocModels[2];
			$licenceRPlateDocModel	 = $vDocModels[3];
			$rcFrontDocModel		 = $vDocModels[5];
			$permitDocModel			 = $vDocModels[6];
			$cabFrontImageModel		 = $vDocModels[8];
			$cabRearImageModel		 = $vDocModels[9];
			$rcBackDocModel			 = $vDocModels[13];
		}
		$vndId		 = $this->getVendorId();
		$vndModel = Vendors::model()->findByPk($vndId);
		$this->render('info', array(
			'vhcModel'				 => $vhcModel,
			'licenceFPlateDocModel'	 => $licenceFPlateDocModel,
			'licenceRPlateDocModel'	 => $licenceRPlateDocModel,
			'cabFrontImageModel'	 => $cabFrontImageModel,
			'cabRearImageModel'		 => $cabRearImageModel,
			'permitDocModel'		 => $permitDocModel,
			'rcFrontDocModel'		 => $rcFrontDocModel,
			'rcBackDocModel'		 => $rcBackDocModel,
			'insuranceDocModel'		 => $insuranceDocModel,
			'vndId'					 => $vndId,
			'vndModel'				 => $vndModel
		));
	}

	public function actionValidatetransport()
	{
		$request = Yii::app()->request;
		$vhcData = $request->getParam("Vehicles");

		$vhcModel	 = new Vehicles('vendorAttach');
		$vhcId		 = $this->getVehicleId();
		$vndId		 = $this->getVendorId();
		if ($request->isPostRequest)
		{
			if ($vhcData['vhc_id'] > 0 && $vhcId != $vhcData['vhc_id'])
			{
				throw new Exception("Some error in data");
			}
			if ($vhcData['vhc_id'] > 0)
			{
				$vhcModel = Vehicles::model()->findByPk($vhcData['vhc_id']);
			}
			else
			{
				unset($vhcData['vhc_id']);
			}

			$cttId							 = \ContactProfile::getByEntityId($vndId, UserInfo::TYPE_VENDOR);
			$cttModel						 = Contact::model()->findByPk($cttId);
			$vhcModel->attributes			 = $vhcData;
			$vhcModel->vhc_owned_or_rented	 = 1;
			$vhcModel->vhc_reg_owner		 = $cttModel->ctt_first_name;
			$vhcModel->vhc_reg_owner_lname	 = $cttModel->ctt_last_name;
			$vhcModel1						 = clone $vhcModel;
			$vhcModel1->scenario			 = "vendorAttach";
			if ($vhcModel1->validate())
			{
				$dataArr = $vhcModel->addVehicle_V2($vhcData, $vndId, $vhcModel);
			}
			elseif ($vhcModel1->hasErrors())
			{
				$result = [];
				foreach ($vhcModel1->getErrors() as $attribute => $error)
				{
					$result[] = $error;
				}
				echo CJSON::encode(['success' => false, 'errors' => $result]);
			}
			//$errors = $vhcModel->getErrors();

			if (Yii::app()->request->isAjaxRequest)
			{
				if ($vhcModel->hasErrors())
				{
					$result = [];
					foreach ($vhcModel->getErrors() as $attribute => $error)
					{
						$result[] = $error;
					}
					echo CJSON::encode(['success' => false, 'errors' => $result]);
				}
				else
				{
					$url = Yii::app()->createUrl('vehicle/info');
					echo CJSON::encode(['success' => true, 'url' => $url, 'message' => json_encode($dataArr),]);
				}
				Yii::app()->end();
			}
		}
	}

	public function actionUploaddoc()
	{
		$request = Yii::app()->request;
		$vhcData = $request->getParam('Vehicles');

		try
		{
			$vhcId = $this->getVehicleId();
			if ($vhcData['vhc_id'] > 0 && $vhcId != $vhcData['vhc_id'])
			{
				throw new Exception("Some error in data", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}
			$vhcModel = Vehicles::model()->findByPk($vhcId);

			$vhcModel->attributes = array_filter($vhcData);

			$docTypeArr = Vehicles::getFieldListByDoc();

			$userInfo = UserInfo::getInstance();
			foreach ($vhcData as $fieldName => $val)
			{
				$docType = $docTypeArr[$fieldName];
				if (!$docType)
				{
					continue;
				}
				$uploadedFile = CUploadedFile::getInstance($vhcModel, $fieldName);
				if ($uploadedFile)
				{
					$success = VehicleDocs::saveDoc($uploadedFile, $vhcId, $docType, $userInfo);
					if (!$success)
					{
						throw new Exception("Could not update Vehicle Log. (" . json_encode($vhcModel->getErrors()) . ")");
					}
				}
			}
			if ($success)
			{
				$vhcModel->vhc_active		 = 1;
				$vhcModel->vhc_modified_at	 = new CDbExpression('NOW()');

				$success = $vhcModel->save();
			}
		}
		catch (Exception $ex)
		{
			ReturnSet::setException($ex);
		}

		$this->redirect(['info']);
	}

	public function getVehicleId($strictCheck = false)
	{

		$vndId = $this->getVendorId($strictCheck);

		$vhcId = VendorVehicle::getLinkedVehicles($vndId);
		return $vhcId;
	}

	public function getVendorId($strictCheck = true)
	{
		$userId = UserInfo::getUserId();

		$contactData = ContactProfile::getEntitybyUserId($userId);
		if ((empty($contactData) || !$contactData['cr_is_vendor'] ) && $strictCheck)
		{
			throw new Exception("Unable to link user", ReturnSet::ERROR_FAILED);
		}
		$vndId = $contactData['cr_is_vendor'];
		if (!$vndId)
		{
			$this->redirect('/vendor/attach');
		}
		return $vndId;
	}

}
