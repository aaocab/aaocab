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
 * @author Roy
 */
class Document
{

	public $id;
	public $isAvailable;
	public $frontPath;
	public $backPath;
	public $isApproved;
	public $documentType;
	public $approveDate;
	public $approveTime;
	public $createdDate;
	public $createdTime;
	public $refValue;
	public $refType;
	public $expiryDate;
	public $expiryTime;
	public $issueDate;
	public $issueTime;
	public $digitalAgreementVersion;
	public $digitalAgreementDate;
	public $digitalAgreementTime;
	public $isDigitalAgreement;
	public $softAgreementVersion;
	public $softAgreementDate;
	public $softAgreementTime;
	public $issoftAgreement;
	public $digitalAgreement;
	public $draftAgreement;
	public $vendorDocumentUpload;
	public $driverDocumentUpload;
	public $vehicleDocumentUpload;
	public $checksum;
	public $eventValue;

	/**
	 * 
	 * @param \Document $model
	 * @return $this
	 */
	public function setData(\Document $model, $source = null)
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

	/**
	 * 
	 * @param \VendorAgreement $model
	 * @return $this
	 */
	public function setAgreementData(\VendorAgreement $model)
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

	/**
	 * 
	 * @param \Contact $model
	 * @param type $docType
	 * @return boolean|$this
	 */
	public function setModelData(\Contact $model, $docType = \Document::Document_Voter, \VendorAgreement $agreementModel = null, $source = null)
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
					$this->setData($documentModel, $source);
				}
				break;
			case Document_Aadhar:
				if ($model->ctt_aadhar_doc_id > 0)
				{
					$this->id		 = (int) $model->ctt_aadhar_doc_id;
					$this->refValue	 = $model->ctt_aadhaar_no;
					$this->refType	 = \Document::Document_Aadhar;
					$documentModel	 = \Document::getById($this->id);
					$this->setData($documentModel, $source);
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
					$this->setData($documentModel, $source);
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
					$this->setData($documentModel, $source);
				}


				break;
			case Document_Memorandum:
				if ($model->ctt_memo_doc_id > 0)
				{
					$this->id		 = (int) $model->ctt_memo_doc_id;
					$this->refValue	 = null;
					$this->refType	 = \Document::Document_Memorandum;
					$documentModel	 = \Document::getById($this->id);
					$this->setData($documentModel);
				}
				break;

			case Document_PoliceVerificationCertificate:
				if ($model->ctt_police_doc_id > 0)
				{
					$this->id		 = (int) $model->ctt_police_doc_id;
					$this->refValue	 = null;
					$this->refType	 = \Document::Document_Memorandum;
					$documentModel	 = \Document::getById($this->id);
					$this->setData($documentModel, $source);
				}
				break;
		}

		/*
		  if ($docType == \Document::Document_Agreement)
		  {
		  $this->referenceType	 = \Document::Document_Agreement;
		  $this->referenceNumber	 = null;
		  $this->setAgreementData($agreementModel);
		  }
		  else if ($model->ctt_voter_doc_id > 0 && $docType == \Document::Document_Voter)
		  {
		  $this->id				 = (int) $model->ctt_voter_doc_id;
		  $this->referenceNumber	 = $model->ctt_voter_no;
		  $this->referenceType	 = \Document::Document_Voter;
		  $documentModel			 = \Document::getById($this->id);
		  $this->setData($documentModel);
		  }
		  else if ($model->ctt_aadhar_doc_id > 0 && $docType == \Document::Document_Aadhar)
		  {
		  $this->id				 = (int) $model->ctt_aadhar_doc_id;
		  $this->referenceNumber	 = $model->ctt_aadhaar_no;
		  $this->referenceType	 = \Document::Document_Aadhar;
		  $documentModel			 = \Document::getById($this->id);
		  $this->setData($documentModel);
		  }
		  else if ($model->ctt_pan_doc_id > 0 && $docType == \Document::Document_Pan)
		  {
		  $this->id				 = (int) $model->ctt_pan_doc_id;
		  $this->referenceNumber	 = $model->ctt_pan_no;
		  $this->referenceType	 = \Document::Document_Pan;
		  $documentModel			 = \Document::getById($this->id);
		  $this->setData($documentModel);
		  }
		  else if ($model->ctt_license_doc_id > 0 && $docType == \Document::Document_Licence)
		  {
		  $this->id				 = (int) $model->ctt_license_doc_id;
		  $this->referenceNumber	 = $model->ctt_license_no;
		  $this->referenceType	 = \Document::Document_Licence;
		  $this->expiryDate		 = date("Y-m-d", strtotime($model->ctt_license_exp_date));
		  $this->expiryTime		 = date("H:i:s", strtotime($model->ctt_license_exp_date));
		  $this->issueDate		 = date("Y-m-d", strtotime($model->ctt_license_issue_date));
		  $this->issueTime		 = date("H:i:s", strtotime($model->ctt_license_issue_date));
		  $documentModel			 = \Document::getById($this->id);
		  $this->setData($documentModel);
		  }
		  else if ($model->ctt_memo_doc_id > 0 && $docType == \Document::Document_Memorandum)
		  {
		  $this->id				 = (int) $model->ctt_memo_doc_id;
		  $this->referenceNumber	 = null;
		  $this->referenceType	 = \Document::Document_Memorandum;
		  $documentModel			 = \Document::getById($this->id);
		  $this->setData($documentModel);
		  } */

		return $this;
	}

	/**
	 * 
	 * @param \Vendors $data
	 * @return $this
	 */
	public function setDocumentStatus($data)
	{
		$this->vendorDocumentUpload	 = $data['documentUpload'];
		$this->driverDocumentUpload	 = $data['driverDocumentUpload'];
		$this->vehicleDocumentUpload = $data['vehicleDocumentUpload'];
	}

	/**
	 * 
	 * @param \BookingPayDocs $model
	 * @return $this
	 */
	public function setDocModelData($model, $message)
	{

		if ($model == null)
		{
			$model = new \BookingPayDocs();
		}
		$row = \BookingTrackLog::model()->getAppSyncIdByBkg($model->bpay_bkg_id, $model->bpay_checksum);
		if ($row)
		{
			$this->appId = $row["appId"];
		}
		else
		{
			$row = \BookingTrackLog::model()->getdetailByEvent($model->bpay_bkg_id, $model->bpay_type);
			if ($row)
			{
				$this->appId = $row["btl_appsync_id"];
			}
		}

		$this->bookingId = (int) $model->bpay_bkg_id;
		//$this->checksum	 = $model->bpay_checksum;
		$this->type		 = (int) $model->bpay_type;
		$this->status	 = (bool) ($model->bpay_status == 1);
		$this->remarks	 = $message;
	}

	/**
	 * 
	 * @param \VendorDocs $model
	 * @return $this
	 */
//    public function setVehicleData($vehicleId)
//	{
//		$model		 = \VehicleDocs::model()->findAllByVhcId($vehicleId);
//		$docModels	 = \Document::vehicleDocumentType();
//		foreach ($docModels as $doc)
//		{
//			$docModel				 = new \Stub\common\Document();
//			$document				 = $doc;
//			$this->documents[$doc]	 = $docModel->setVDocData($model, $document);
//		}
//
//		return $this;
//
//	}
}
