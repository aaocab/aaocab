<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Documents
 *
 * @author Dev
 * 
 * @property \Beans\common\Document[] $document
 */

namespace Beans\common;

class Documents
{

	 

	/** @var \Beans\common\Document[] $document */
	public $documents;
	

	 

	public static function setCabDoc($cabId)
	{
		$vdmodels	 = \VehicleDocs::model()->findAllActiveDocByVhcId($cabId);
		$doc		 = \VehicleDocs::model()->doctypeTxt;
		$docFields	 = \VehicleDocs::vehicleDocumentDbField();
		$docExpList	 = \Vehicles::getDocumentExpiryDateById($cabId);

		$data = [];
		foreach ($vdmodels as $row)
		{
			$expDate = null;
			$obj	 = new Document();
			$row	 = (is_array($row)) ? \Filter::convertToObject($row) : $row;
			if ($docFields[$row->vhd_type] != '')
			{
				$expDate = $docExpList[$docFields[$row->vhd_type]];
			}
			$obj->setCabDocData($row, $doc[$row->vhd_type], $expDate);
			$data[] = $obj;
		}
		return $data;
	}

	public function setDocumentStatus($data)
	{
		$this->vendorDocumentUpload	 = $data['isVendor'];
		$this->driverDocumentUpload	 = $data['isCar'];
		$this->vehicleDocumentUpload = $data['isDriver'];
	}

 
}
