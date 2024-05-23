<?php

namespace Stub\vendor;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Agreement 
{

	public $business;
	public $owner;
    public $bank;
	public $vendorPicStatus;
	public $vndAddress;
    public $vndAadhaarNo;
	public $vndVoterNo;
    public $vndPanNo;
    public $vndLicenseNo;
    public $vndCity;
	/** @var \Stub\common\Coordinates $coordinates */
	public $coordinates;
	/** @var \Stub\common\Cab $cab */
	

	public function setData(\Vendors $vendorModel,\Contact $contactModel)
	{
		
                //$this->business = new \Stub\common\Business();
		//$this->business->setData($contactModel);
		
		$this->owner = new \Stub\common\Person();
		$agreementModel	 = \VendorAgreement::model()->findByVndId($vendorModel->vnd_id);
		$this->owner->setPersonData($contactModel,$agreementModel);
		//$this->business->bank = new \Stub\common\Bank();
		//$this->business->bank->setData($contactModel);
	}

	public function getData($data)
	{
		$this->vendorPicStatus = $data['vendorPic'];
		$this->vndAddress      = $data['vnd_address'];
		$this->vndAadhaarNo   = $data['vnd_aadhaar_no'];
		$this->vndVoterNo     =  $data['vnd_voter_no'];
		$this->vndPanNo        = $data['vnd_pan_no'];
		$this->vndLicenseNo   =  $data['vnd_license_no'];
		$this->vndCity        = $data['vnd_city'];
		
		if ($data['digitalLat'] != null && $data['digitalLong'] != null)
		{
			$this->coordinates = new Coordinates($data['digitalLat'], $data['digitalLong']);
		}
                return $this;
	}
        public function getAgreeMentData($data,$contact_id)
        {
                
           
            $model = \Contact::model()->findByPk($contact_id);
            
           
            $model->ctt_business_name = $data->name;
            $model->ctt_address = $data->owner->address;
            $model->ctt_city = $data->owner->location->code;
            
            $model->ctt_aadhaar_no = $data->owner->documents->Aadhar->refValue;
            $model->ctt_license_no = empty($data->owner->documents->Licence->refValue) ? null : $data->owner->documents->Licence->refValue;
            $model->ctt_voter_no = empty($data->owner->documents->Voter->refValue) ? null : $data->owner->documents->Voter->refValue;
            $model->ctt_pan_no = empty($data->owner->documents->Pan->refValue) ? null : $data->owner->documents->Pan->refValue;


            return $model;
        }
	public function getDigitalData($data,$appToken,$vendorId)
        {
            
                
		//$model		 = \VendorAgreement::model()->findByVndId($vendorId);
                $model = \VendorAgreement::model()->findByVndId($vendorId);
		$model->vag_digital_flag	 = 1;
		$model->vag_digital_uuid	 = $appToken['apt_device_uuid'];
		$model->vag_digital_os		 = $appToken['apt_os_version'];
		$model->vag_digital_ip		 = $appToken['apt_ip_address'];
		$model->vag_digital_device_id    = $appToken['apt_device'];
		$model->vag_digital_ver		 = \Yii::app()->params['digitalagmtversion'];
		$model->vag_digital_lat		 = $data->owner->location->coordinates->latitude;
		$model->vag_digital_long	 = $data->owner->location->coordinates->longitude;
               
		$model->vag_active		  = 1;
		$model->vag_digital_is_email	 = 0;
		$model->vag_draft_agreement	 = NULL;
		$model->vag_digital_agreement = NULL;
              
               
               
		return $model;
           
        }

	 

}
