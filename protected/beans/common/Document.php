<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Document
 *
 * @author Dev
 *
 * @property integer $id
 * @property string $frontImageUrl
 * @property string $backImageUrl
 * @property string $verifiedStatus
 * @property integer $documentType
 * @property string $verifiedDate
 * @property string $verifiedBy 
 * @property string $createDate
 * @property string $value
 * @property string $verifiedDate
 * @property string $issueDate
 * @property string $issuedBy
 * @property string $expiryDate
 * @property string $remarks
 * @property string $syncId
 * @property string $bookingId
 * @property string $type
 * @property string $status
 * @property \Beans\common\Cab $vehicle
 */

namespace Beans\common;

class Document
{

	public $id;
	public $documentType;
//new version
	public $label;
	public $url;
	public $statusLabel;
//old version
	public $frontImageUrl;
	public $backImageUrl;
//
	public $verifiedStatus;
	public $verifiedDate;
	public $verifiedBy;
	public $createDate;
	public $value;
	public $issueDate;
	public $issuedBy;
	public $expiryDate;
	public $remarks;
	public $syncId;
	public $bookingId;

	/** @var \Beans\common\Cab $vehicle */
	public $vehicle;
	public $cttId;
	public $type;
	public $status;

	public function setList($docs)
	{
		$data = [];
		foreach ($docs as $res)
		{
			$obj	 = new Document();
			$obj->setData($res);
			$data[]	 = $obj;
		}
		return $data;
	}

	public function setData($item)
	{
		$this->id				 = (int) $item->doc_id;
		$this->frontImageUrl	 = ((empty($item->doc_front_s3_data)) || ($item->doc_front_s3_data == '{}')) ? $item->doc_file_front_path : $item->doc_front_s3_data;
		$this->backImageUrl		 = ((empty($item->doc_back_s3_data)) || ($item->doc_back_s3_data == '{}')) ? $item->doc_file_back_path : $item->doc_back_s3_data;
		$this->verifiedStatus	 = (int) $item->doc_status;
		$this->documentType		 = (int) $item->doc_type;
		$this->verifiedDate		 = $item->doc_approved_at;
		$this->verifiedBy		 = (int) $item->doc_approved_by;
		$this->createDate		 = $item->doc_created_at;
		$this->value			 = $item->docType[$item->doc_type];
		$this->remarks			 = $item->doc_remarks;
		#$this->issueDate				= $item->doc_temp_approved_at;
		#$this->issuedBy					= $item->doc_temp_approved;
	}

	public function setDataDoc(\Document $model, $source = null)
	{

		if ($model == null)
		{
			$model = new \Document();
		}
		$this->id			 = (int) $model->doc_id;
		$this->documentType	 = (int) $model->doc_type;
		$this->isAvailable	 = (int) $model->doc_id;
		if ($source == 'contact')
		{
			//$this->frontPath = \AttachmentProcessing::ImagePath($model->doc_file_front_path);
			//$this->backPath	 = \AttachmentProcessing::ImagePath($model->doc_file_back_path);
			$this->frontPath = \Document::getDocPathById($model->doc_id, 1);
			if ($model->doc_file_back_path != '')
			{
				$this->backPath = \Document::getDocPathById($model->doc_id, 2);
			}
		}
		else
		{
			$this->frontPath = $model->doc_file_front_path;
			$this->backPath	 = $model->doc_file_back_path;
		}
		if ($model->doc_approved_at != "")
		{
			$this->approveDate	 = date("Y-m-d", strtotime($model->doc_approved_at));
			$this->approveTime	 = date("H:i:s", strtotime($model->doc_approved_at));
		}
		$this->isApproved	 = (int) $model->doc_status;
		$this->createdDate	 = date("Y-m-d", strtotime($model->doc_created_at));
		$this->createdTime	 = date("H:i:s", strtotime($model->doc_created_at));
		$this->remarks		 = $model->doc_remarks;
		return $this;
	}

	public function setListV1($docs)
	{
		$data = [];
		foreach ($docs as $res)
		{

			//$frontImageUrl = ((empty($res->doc_front_s3_data)) || ($res->doc_front_s3_data == '{}')) ? $res->doc_file_front_path : $res->doc_front_s3_data;

			if ((empty($res->doc_front_s3_data)) || ($res->doc_front_s3_data == '{}'))
			{
				$frontImageUrl	 = $res->doc_file_front_path;
				$basePath		 = \Yii::app()->params['fullAPIBaseURL'];
			}
			else
			{
				$frontImageUrl	 = $res->doc_front_s3_data;
				$basePath		 = '';
			}
			if ($frontImageUrl)
			{
				$docRowObj = (object) \Document::docTypeToFaceType($res->doc_type, 1);

				$obj = new Document();

				$frontImageUrl = $basePath . \Document::getS3DocPathById($res->doc_id, 1);

				$obj->setDataV1($res, $frontImageUrl, $docRowObj);
				$data[] = $obj;
			}
			//$backImageUrl = ((empty($res->doc_back_s3_data)) || ($res->doc_back_s3_data == '{}')) ? $res->doc_file_back_path : $res->doc_back_s3_data;
			if ((empty($res->doc_back_s3_data)) || ($res->doc_back_s3_data == '{}'))
			{
				$backImageUrl	 = $res->doc_file_back_path;
				$basePath		 = \Yii::app()->params['fullAPIBaseURL'];
			}
			else
			{
				$backImageUrl	 = $res->doc_back_s3_data;
				$basePath		 = '';
			}
			if ($backImageUrl)
			{
				$docRowObj = (object) \Document::docTypeToFaceType($res->doc_type, 2);

				$obj			 = new Document();
				$backImageUrl	 = $basePath . \Document::getS3DocPathById($res->doc_id, 2);
				$obj->setDataV1($res, $backImageUrl, $docRowObj);
				$data[]			 = $obj;
			}
		}
		return $data;
	}

	public function setDataV1($item, $imageUrl, $docRowObj)
	{
		$this->id = (int) $item->doc_id;

		//$filePath                 = \AttachmentProcessing::ImagePath($imageUrl);  
		$this->url				 = $imageUrl;
		$this->verifiedStatus	 = (int) $item->doc_status;
		$this->statusLabel		 = $this->getApproveStatus();
		$this->documentType		 = (int) $docRowObj->docType;
		$this->verifiedDate		 = $item->doc_approved_at;
		$this->verifiedBy		 = (int) $item->doc_approved_by;
		$this->createDate		 = $item->doc_created_at;
		$this->label			 = $docRowObj->docTypeLabel;
		$this->remarks			 = $item->doc_remarks;
	}

	public function setByContactId($cttId)
	{
		$docs = \Document::getDocModels($cttId);
		return $this->setList($docs);
	}

	public function setByContactIdV1($cttId)
	{
		$docs = \Document::getDocModels($cttId);
		return $this->setListV1($docs);
	}

	public function getApproveStatus()
	{
		$approveStatus	 = $this->verifiedStatus;
		$label			 = 'NA';
		switch ($approveStatus)
		{
			case 0:
				$label	 = 'Pending';
				break;
			case 1:
				$label	 = 'Approved';
				break;
			case 2:
				$label	 = 'Rejected';
				break;
			default:
				break;
		}
		return $label;
	}

	public function setModelDataOld(\Contact $model, $docType = \Document::Document_Voter, \VendorAgreement $agreementModel = null, $source = null)
	{
		if (!$model)
		{
			return false;
		}
		//
		switch ($docType)
		{
			case Document_Agreement:
				if ($agreementModel != null)
				{
					$this->refType	 = \Document::Document_Agreement;
					$this->refValue	 = null;
					$this->setAgreementData($agreementModel, $source);
				}
				break;
			case Document_Voter:
				if ($model->ctt_voter_doc_id > 0)
				{
					$this->id		 = (int) $model->ctt_voter_doc_id;
					$this->refValue	 = $model->ctt_voter_no;
					$this->refType	 = \Document::Document_Voter;
					$documentModel	 = \Document::getById($this->id);
					$this->setDataDoc($documentModel, $source);
				}
				break;
			case Document_Aadhar:
				if ($model->ctt_aadhar_doc_id > 0)
				{
					$this->id		 = (int) $model->ctt_aadhar_doc_id;
					$this->refValue	 = $model->ctt_aadhaar_no;
					$this->refType	 = \Document::Document_Aadhar;
					$documentModel	 = \Document::getById($this->id);
					$this->setDataDoc($documentModel, $source);
				}
				break;
			case Document_Pan:
				if ($model->ctt_pan_doc_id > 0)
				{
					$this->id		 = (int) $model->ctt_pan_doc_id;
					$this->refValue	 = $model->ctt_pan_no;
					$this->refType	 = \Document::Document_Pan;
					$documentModel	 = \Document::getById($this->id);
					if (!$documentModel)
					{
						return false;
					}
					$this->setDataDoc($documentModel, $source);
				}
				break;
			case Document_Licence:
				if ($model->ctt_license_doc_id > 0)
				{
					$this->id		 = (int) $model->ctt_license_doc_id;
					$this->refValue	 = $model->ctt_license_no;
					$this->refType	 = \Document::Document_Licence;
					if ($model->ctt_license_exp_date != "")
					{
						$this->expiryDate = date("Y-m-d", strtotime($model->ctt_license_exp_date));
					}
					if ($model->ctt_license_issue_date != "")
					{
						$this->issueDate = date("Y-m-d", strtotime($model->ctt_license_issue_date));
						$this->issueTime = date("H:i:s", strtotime($model->ctt_license_issue_date));
					}
					$documentModel = \Document::getById($this->id);
					if (!$documentModel)
					{
						return false;
					}
					$this->setDataDoc($documentModel, $source);
				}


				break;
			case Document_Memorandum:
				if ($model->ctt_memo_doc_id > 0)
				{
					$this->id		 = (int) $model->ctt_memo_doc_id;
					$this->refValue	 = null;
					$this->refType	 = \Document::Document_Memorandum;
					$documentModel	 = \Document::getById($this->id);
					$this->setDataDoc($documentModel);
				}
				break;

			case Document_PoliceVerificationCertificate:
				if ($model->ctt_police_doc_id > 0)
				{
					$this->id		 = (int) $model->ctt_police_doc_id;
					$this->refValue	 = null;
					$this->refType	 = \Document::Document_Memorandum;
					$documentModel	 = \Document::getById($this->id);
					$this->setDataDoc($documentModel, $source);
				}
				break;
		}
	}

	/**
	 * 
	 * @param \VendorAgreement $model
	 * @return $this
	 */
	public function setAgreementDataold(\VendorAgreement $model)
	{
		#print_r($model);
		if ($model == null)
		{
			$model = new \VendorAgreement();
		}
		$this->id			 = (int) $model->vag_id;
		$this->documentType	 = \Document::Document_Agreement;
		if ($model->vag_approved_at != "")
		{
			$this->approveDate	 = date("Y-m-d", strtotime($model->vag_approved_at));
			$this->approveTime	 = date("H:i:s", strtotime($model->vag_approved_at));
		}
		$digitalAgreement				 = $model::getPathById($model->vag_id, \VendorAgreement::DIGITAL_AGREEMENT);
		$draftAgreement					 = $model::getPathById($model->vag_id, \VendorAgreement::DRAFT_AGREEMENT);
		$this->digitalAgreementVersion	 = $model->vag_digital_ver;
		$this->digitalAgreement			 = $digitalAgreement;
		$this->draftAgreement			 = $draftAgreement;
		if ($model->vag_digital_date != "")
		{
			$this->digitalAgreementDate	 = date("Y-m-d", strtotime($model->vag_digital_date));
			$this->digitalAgreementTime	 = date("H:i:s", strtotime($model->vag_digital_date));
		}
		$this->isDigitalAgreement = (int) $model->vag_digital_flag;
		if ($model->vag_soft_date != "")
		{
			$this->softAgreementDate = date("Y-m-d", strtotime($model->vag_soft_date));
			$this->softAgreementTime = date("H:i:s", strtotime($model->vag_soft_date));
		}
		$this->issoftAgreement = (int) $model->vag_soft_flag;
		if ($model->vag_soft_exp_date != "")
		{
			$this->expiryDate	 = date("Y-m-d", strtotime($model->vag_soft_exp_date));
			$this->expiryTime	 = date("H:i:s", strtotime($model->vag_soft_exp_date));
		}
		return $this;
	}

	public function getDigitalData($data, $appToken, $vendorId)
	{
		//$model		 = \VendorAgreement::model()->findByVndId($vendorId);
		$model							 = \VendorAgreement::model()->findByVndId($vendorId);
		$model->vag_digital_flag		 = 1;
		$model->vag_digital_uuid		 = $appToken['apt_device_uuid'];
		$model->vag_digital_os			 = $appToken['apt_os_version'];
		$model->vag_digital_ip			 = $appToken['apt_ip_address'];
		$model->vag_digital_device_id	 = $appToken['apt_device'];
		$model->vag_digital_ver			 = \Yii::app()->params['digitalagmtversion'];
		$model->vag_digital_lat			 = $data->owner->location->coordinates->latitude;
		$model->vag_digital_long		 = $data->owner->location->coordinates->longitude;

		$model->vag_active				 = 1;
		$model->vag_digital_is_email	 = 0;
		$model->vag_draft_agreement		 = NULL;
		$model->vag_digital_agreement	 = NULL;
		$model->vag_approved			 = 2;
		return $model;
	}

	public function setContactModel(\Contact $cttModel, $doc)
	{
		if (!empty($doc->Licence))
		{
			$cttModel->setLicenseData($doc->Licence);
		}
		if ($doc->Pan != null)
		{
			$cttModel->setPanData($doc->Pan);
		}
		if ($doc->Adhar != null)
		{
			$cttModel->setAadharData($doc->Adhar);
		}
		if ($doc->Voter != null)
		{
			$cttModel->setVoterData($doc->Voter);
		}
	}

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

	public function setCabDocData($row, $docTypeLabel, $expDate = null)
	{
		$basePath				 = \Yii::app()->params['fullAPIBaseURL'];
		$this->id				 = (int) $row->vhd_id;
		$this->url				 = $basePath . \VehicleDocs::getDocPathById($row->vhd_id);
		$this->verifiedStatus	 = (int) $row->vhd_status;
		$this->statusLabel		 = $this->getApproveStatus();
		$this->documentType		 = (int) $row->vhd_type;
		if ($row->vhd_approve_by > 0)
		{
			$this->verifiedDate	 = $row->vhd_appoved_at;
			$this->verifiedBy	 = (int) $row->vhd_approve_by;
		}
		$this->createDate	 = $row->vhd_created_at;
		$this->label		 = $docTypeLabel;
		$this->remarks		 = $row->vhd_remarks;
		if($expDate){
		$this->expiryDate	 = $expDate;
		}
	}

	public function getVehicleId()
	{
		return $this->vehicle->id;
	}
}
