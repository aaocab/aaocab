<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;

/**
 * Description of Documents
 *
 * @author Admin
 * @property Document $drivingLicense;
 * @property Document $PAN;
 * @property Document $aadhaar;
 * @property Document $voterCard;
 * @property Document $digitalAgreement;
 * @property Document $scanAgreement;
 * @property Document $mou;

 */
class Documents
{

	public $Licence;
	public $Pan;
	public $Adhar;
	public $Voter;
	public $Agreement;
	public $scanAgreement;
	public $Memorandum;

    public $RcBook;
    public $PermitCertificate;
    public $FitnessCertificate;
    public $InsuranceInformation;
    public $PUCInformation;


public $id,$type,$status;
	
	public function setContactModel(\Contact $cttModel, $doc)
	{
	   
		if(!empty($doc->Licence))
		{
		     
			$cttModel->setLicenseData($doc->Licence);
		}
		if($doc->Pan != null)
		{
		    
			$cttModel->setPanData($doc->Pan);
		}
		if($doc->Adhar != null)
		{
			$cttModel->setAadharData($doc->Adhar);
		}
		if($doc->Voter != null)
		{
			$cttModel->setVoterData($doc->Voter);
		}
	}
      /**
	 * 
	 * @param \VehicleDocs $model
	 * @return $this
	 */
	public function setData($model, $message)
	{
		if ($model == null)
		{
			$model = new \Document();
		}
		//$doc			 = \Document::documentType();
		$this->id	 = (int) $model->doc_id;
		$this->type	 = (int) $model->doc_type;
		$this->status	 = (int) ($model->doc_active == 1) ? true : false;
		$this->remarks	 =  $message;
	}
    
   
	
}
