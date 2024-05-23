<?php

namespace Stub\common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Agreement
 *
 * @author GOZO
 */
class VehicleDoc
{
	public $id;
	public $vhcId;
	public $vdocType;
	public $frontPath;/* path rename by frontPath for mapping document stub at the app end */
	public $expiryDate,$issueDate,$remarks,$status;

	public function setData($model)
	{
		$vhvModel		 = \Vehicles::model()->findByPk($model['vhd_vhc_id']);
		$docField		 = \VehicleDocs::vehicleDocumentDbField();
		$this->frontPath = $model['vhd_file'];
		$fieldsName		 = $docField[$model['vhd_type']];
		if ($fieldsName)
		{
			$this->expiryDate = $vhvModel->$fieldsName;

		}
		if ($model['vhd_type'] == 5)
		{
			$dop = ($vhvModel->vhc_dop != NULL) ? $vhvModel->vhc_dop : NULL;
			$dop = (date("Y-m-d", strtotime($vhvModel->vhc_dop)) != '1970-01-01') ? $vhvModel->vhc_dop : NULL;
			$this->issueDate = $dop;//$vhvModel->vhc_dop;
            //$this->vhc_dop = $vhvModel->vhc_dop;
			//$this->untilDate = $taxexp;
		}
		$this->status	 = (int)$model['vhd_status'];
		$this->vdocType	 = (int)$model['vhd_type'];	

	}

	public function setVehicleData($vehicleId)
	{
		$vdmodels	 = \VehicleDocs::model()->findAllByVhcId($vehicleId);
		$doc		 = \VehicleDocs::vehicleDocumentType();
		
		foreach ($vdmodels as $model)
		{
			if(!empty($model))
			{
			    $obj			 = new \Stub\common\VehicleDoc();
			    $obj->setData($model);

			    $type			 = $model['vhd_type'];
			    $doclist		 = $doc[$model['vhd_type']];
			    $this->$doclist	 = $obj;
			}
		
		}
		return $this;
	}
	public function setVehicleData_V2($vehicleId, $type)
	{
		$vdmodels	 = \VehicleDocs::model()->findByDocType($vehicleId,$type);
			
		foreach ($vdmodels as $model)
		{
			if(!empty($model) && $model->active > 0)
			{
			    $obj			 = new \Stub\common\VehicleDoc();
			    $obj->setData($model);
				$type			 = $model->vhd_type;
			    $doclist		 = $doc[$model->vhd_type];
			    $this->$doclist	 = $obj;
			}
		
		}
		return $this;
	}
	public function getData($arrVhcDocument, $vhcid, $userInfo)
	{
		$vhcmodel	 = \Vehicles::model()->findByPk($vhcid);

		$doc		 = \VehicleDocs::vehicleDocumentType();
		$docField	 = \VehicleDocs::vehicleDocumentDbField();
		foreach ($doc as $key => $value)
		{
			if ($arrVhcDocument->$value!='')
			{
			$isexistDoc = \VehicleDocs::model()->checkdoc($vhcid, $key);
			if ($isexistDoc)
			{
				$vhcDocModel = \VehicleDocs::model()->findByPk($isexistDoc);
			}
			else
			{
				$vhcDocModel = new \VehicleDocs();
			}
			 $frontPath	 = $arrVhcDocument->$value->frontPath;
				$checksum	 = $arrVhcDocument->$value->checksum;
				$expiryDate	 = $arrVhcDocument->$value->expiryDate;

				if ($docField[$key])
				{
					$fieldName				 = $docField[$key];
					$vhcmodel->$fieldName	 = $expiryDate;
				}

				$vhcDocModel->vhd_checksum	 = $checksum;
				$vhcDocModel->vhd_file		 = $frontPath;
				$vhcDocModel->saveDocument($vhcid, $frontPath, $userInfo, $key);
				if ($key == 5)
				{
					$issueDate			 = $arrVhcDocument->$value->issueDate;
			
					$vhcmodel->vhc_dop	 = $issueDate;
				}

				$vhcmodel->save();
			}
		}

	}
  /**
	 * 
	 * @param \VehicleDocs $model
	 * @return $this
	 */
	public function setVhcDocModelData($model, $message)
	{
		if ($model == null)
		{
			$model = new \VehicleDocs();
		}
		$doc			 = \VehicleDocs::vehicleDocumentType();
		$this->vhcId	 = (int) $model->vhd_vhc_id;
		$this->vdocType	 = (int) $model->vhd_type;
		$this->status	 = (int) ($model->vhd_status == 1) ? true : false;
		$this->remarks	 = $doc[$model->vhd_type] . $message;
	}
	public function setDocData($data,$systemChkSum)
	{
		$this->cabNumber = $data['cabNumber'];
		$this->bookingId  = (int)$data['bookingId'];
		$this->type = $data['type'];
                $this->chkSum = $systemChkSum;
	}
}
