<?php

namespace Stub\common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DocumentDetails
 *
 * @author Roy
 */
class Doc
{

	//put your code here

	public $referenceNumber;
	public $referenceId;
	public $docExpDate;
	public $docExpTime;
	public $docIssueDate;
	public $docIssueTime;

	/**
	 * 
	 * @param \Contact $model
	 * @return $this
	 */
	public function setData(\Contact $model)
	{
		if ($model->ctt_voter_doc_id > 0)
		{
			$this->referenceId		 = $model->ctt_voter_doc_id;
			$this->referenceNumber	 = $model->ctt_voter_no;
		}
		else if ($model->ctt_aadhar_doc_id > 0)
		{
			$this->referenceId		 = $model->ctt_aadhar_doc_id;
			$this->referenceNumber	 = $model->ctt_aadhaar_no;
		}
		else if ($model->ctt_pan_doc_id > 0)
		{
			$this->referenceId		 = $model->ctt_pan_doc_id;
			$this->referenceNumber	 = $model->ctt_pan_no;
		}
		else if ($model->ctt_license_doc_id > 0)
		{
			$this->referenceId		 = $model->ctt_license_doc_id;
			$this->referenceNumber	 = $model->ctt_license_no;
			$this->licenseExpDate	 = date("Y-m-d", strtotime($model->ctt_license_exp_date));
			$this->licenseExpTime	 = date("H:i:s", strtotime($model->ctt_license_exp_date));
			$this->licenseIssueDate	 = date("Y-m-d", strtotime($model->ctt_license_issue_date));
			$this->licenseIssueTime	 = date("H:i:s", strtotime($model->ctt_license_issue_date));
		}
		return $this;
	}

}
