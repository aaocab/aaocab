<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;

/**
 * Description of Business
 *
 * @author Abhishek Khetan
 */
class Business extends Person
{

	public $id;
	#public $firstName ;
	#public $lastName;
	public $isActive;
	public $blockReason;
	public $name;
	public $company;
	public $type;
	public $status;
	public $email;
	public $city;
	public $state;
	public $address;
	public $businessType;
	public $businessName;
	public $location;

	#public $businessArr= ;
	public $userType;
	public $session;
	public $tncCheck;
	public $newTncId;
	public $versionCheck;
	public $rating;
	public $lavel;
	public $userId;
	public $uniqueName;
	public $sessionId;
	public $vndCode;
	public $vndId;
	public $isDco;
	public $cityId;
	public $dco;
	public $documents;
	public $vndRelTier;
	public $code;
	public $selfId;
	public $vndCompany;
	public $vendorLavel;
	public $isBussiness;

	/** @var \Stub\common\User $user */
	public $user;

	/** @var \Stub\common\Person $owner  */
	public $owner;

	/** @var \Stub\common\StatusDetails $businessStatus  */
	public $accountStatus;

	/** @var \Stub\common\Doc $memorandum  */
	public $memorandum;

	/** @var Phone $phone */
	#public $phone;

	/** @var Phone $alternatePhone */
	#public $alternatePhone;

	/**
	 * This function for initializing the contact model
	 * @param \Contact $model
	 * @return \Contact
	 */
	public function init(\Contact $model = null)
	{

		if ($model == null)
		{
			$model = new \Contact();
		}

		$model->ctt_business_name	 = $this->name;
		$model->ctt_first_name		 = $this->owner->firstName;
		$model->ctt_last_name		 = $this->owner->lastName;
		$model->ctt_address			 = $this->address;
		$model->ctt_city			 = $this->owner->city;
		$model->ctt_user_type		 = $this->userType;
		$model->ctt_business_type	 = $this->businessType;

		$model->ctt_aadhaar_no			 = $this->owner->documents->adhaar->refValue;
		$model->ctt_license_issue_date	 = empty($this->owner->documents->Licence->issueDate) ? null : $this->owner->documents->Licence->issueDate;
		$model->ctt_license_exp_date	 = empty($this->owner->documents->Licence->expiryDate) ? null : $this->documents->Licence->expiryDate;
		$model->ctt_license_no			 = empty($this->owner->documents->Licence->refValue) ? null : $this->owner->documents->Licence->refValue;
		$model->ctt_created_date		 = new \CDbExpression('now()');

		$model->isDco = empty($this->dco) ? 0 : $this->dco;

		//Contact Details
		$emails	 = [];
		$phones	 = [];

		array_push($emails, $this->getContactEmail());
		array_push($phones, $this->getContactPhone());

		$model->contactPhones	 = $phones;
		$model->contactEmails	 = $emails;

		return $model;
	}

	/**
	 * This function returns the contact model
	 * @return type
	 */
	public function getMedium()
	{
		$model = $this->init();

		//$x = $this->email;

		$model->isDco	 = empty($this->dco) ? 0 : $this->dco;
		//Contact Details
		$contacts		 = [];
		array_push($contacts, $this->getContactEmail());
		array_push($contacts, $this->getContactPhone());

		$model->contactDetails = $contacts;

		return $model;
	}

	/**
	 * This function is used for getting the contact email model
	 * @param type $data
	 * @return \ContactPhone
	 */
	public function getContactPhone()
	{
		$phModel = new \ContactPhone();

		foreach ($phModel as $value)
		{
			$value->mediumType				 = 2;
			$value->phn_phone_no			 = $this->owner->primaryContact->number;
			$value->phn_phone_country_code	 = $this->owner->primaryContact->code;

			if (empty($value->phn_otp))
			{
				$value->phn_otp = rand(1000, 9999);
			}

			$value->phn_is_verified	 = 0;
			$value->phn_is_primary	 = 1;
			$value->phn_active		 = 1;
		}

		return $value;
	}

	/**
	 * This function is used for getting contact email
	 * @param type $data		-	Received contact details
	 * @return \ContactEmail
	 */
	public function getContactEmail()
	{
		$contactEmailModel = new \ContactEmail();

		foreach ($contactEmailModel as $value)
		{
			$value->mediumType			 = 1;
			$value->eml_email_address	 = $this->owner->email;
			$value->eml_is_primary		 = 1;
			$value->eml_is_verified		 = 0;
			$value->eml_active			 = 1;
		}

		return $value;
	}

	public function setData(\Contact $model)
	{

		if ($model == null)
		{
			$model = new \Contact();
		}

		$this->id			 = (int) $model->ctt_id;
		$this->name			 = $model->getName();
		$this->businessType	 = $model->ctt_business_type;
		$this->businessName	 = $model->ctt_business_name;
		$this->address		 = $model->ctt_address;
		#$this->lastName	 = $model->ctt_last_name;
		$this->status		 = (int) $model->ctt_active;
		$cityModel			 = new \Stub\common\Cities();
		$this->location		 = $cityModel->getIdName($model->ctt_city);
		$this->email		 = \ContactEmail::getPrimaryEmail($this->id);
		$phoneModel			 = \ContactPhone::model()->findByContactID($this->id);
		$this->phone		 = new Phone($phoneModel[0]->phn_phone_country_code, $phoneModel[0]->phn_phone_no);
		//$this->city		 = (int)$model->ctt_city;
		$this->state		 = (int) $model->ctt_state;
		$this->address		 = $model->ctt_address;

		//$this->isBussiness    = $model->is_bussiness;
		//$this->user = new \Stub\common\Document();
		//$docModel				 = new \Stub\common\DocumentDetails();
		//$this->documentDetails	 = $docModel->setData($model);
		//$this->owner->getModel($model->contactOwner);
	}

	public function setModel(\Contact $model)
	{
		if ($model == null)
		{
			$model = new \Contact();
		}
		$this->id		 = (int) $model->ctt_id;
		$this->name		 = $model->getName();
		$this->firstName = $model->ctt_first_name;
		$this->lastName	 = $model->ctt_last_name;
		$this->status	 = (int) $model->ctt_active;
		$this->city		 = $model->ctt_city;
		$this->state	 = $model->ctt_state;
		$this->address	 = $model->ctt_address;
	}

	/**
	 * 
	 * @param \Users $model
	 */
	public function setUserData(\Users $model = null)
	{
		if ($model == null)
		{
			$model = new Users();
		}
		$this->id		 = $model->user_id;
		$this->name		 = $model->usr_name . " " . $model->usr_lname;
		$this->firstName = $model->usr_name;
		$this->lastName	 = $model->usr_lname;
		$this->email	 = $model->usr_email;
		$this->city		 = $model->usr_city;
		$this->status	 = $model->usr_state;
	}

	/**
	 * 
	 * @param \Contact $model
	 * @return boolean|\Contact
	 */
	public function setDocumentData(\Contact $model)
	{
		if ($model == null)
		{
			return false;
		}
		$this->memorandum->referenceId = $model->ctt_memo_doc_id;
		return $this;
	}

	/**
	 * 
	 * @param \Contact $model
	 * @return $this
	 */
	public function setDigitalModel(\Contact $model, $contact = [])
	{
		$this->id			 = (int) $model->ctt_id;
		$this->name			 = $model->getName();
		$this->company		 = $model->ctt_business_name;
		$this->city			 = $model->ctt_city;
		$this->state		 = $model->ctt_state;
		$this->address		 = $model->ctt_address;
		//$this->email		 = $contact['eml_email_address'];
		$this->businessType	 = (int) $model->ctt_business_type;
		$this->userType		 = (int) $model->ctt_user_type;
		/* if ($contact['phone'] != '')
		  {
		  $this->phone->code	 = 91;
		  $this->phone->number = (int) $contact['phone'];
		  }
		  if ($contact['alternatePhone'] != '')
		  {
		  $this->alternatePhone->code		 = 91;
		  $this->alternatePhone->number	 = (int) $contact['alternatePhone'];
		  } */
		return $this;
	}

	public function setBusinessdata($userModel, $data, $contactModel)
	{
		$this->setData($contactModel);
		$this->userId		 = (int) $userModel->user_id;
		$this->isActive		 = (int) $data['isActive'];
		$this->blockReason	 = $data['blockReason'];
		//$this->vnd_id => $webUser->getEntityID();
		$this->uniqueName	 = $data['userName'];
		$this->tncCheck		 = $data['tnc_check'];
		$this->newTncId		 = $data['new_tnc_id'];
		$this->versionCheck	 = $data['versionCheck'];
		$this->sessionId	 = $data['session'];
		$this->vndCode		 = $data['vnd_code'];
		$this->vndId		 = (int) $data['vendor_id'];
		$this->accountStatus = new \Stub\common\StatusDetails();
		$this->accountStatus->setData($data);
		$this->rating		 = (string) $data['rating'];
		$this->isGozoNow     = (int) $data['isGozoNow'];
		$this->vndRelTier	 = (int) $data['vnd_rel_tier'];
		$this->lavel		 = $data['vendor_level'];
		$this->user			 = new \Stub\common\User();
		$this->user->setData($userModel);
		$agreementModel		 = \VendorAgreement::model()->findByVndId($data['vendor_id']);
		$this->owner		 = new \Stub\common\Person();
		$this->owner->setPersonData($contactModel, $agreementModel);
	}

	public function setAgreementData(\Vendors $vendorModel, \Contact $contactModel)
	{

		$this->vendorLavel		 = $vendorModel->vndContact->getName();
		$this->vnd_relation_tier = (int) $vendorModel->vnd_rel_tier;
		$this->vndCompany		 = $contactModel->ctt_business_name;
		$this->isBussiness		 = ($contactModel->ctt_user_type == '1' ? 0 : 1);
		//$this->lavel = $data['vendor_level'];
		$this->setData($contactModel);
		$agreementModel			 = \VendorAgreement::model()->findByVndId($vendorModel->vnd_id);
		$this->owner			 = new \Stub\common\Person();
		$this->owner->setPersonData($contactModel, $agreementModel);
		//$this->business->bank = new \Stub\common\Bank();
		//$this->business->bank->setData($contactModel);
	}

	public function setDriverdata($userModel, $data, $contactModel)
	{
		
		$this->code		 = $data->drv_code;
		$this->selfId	 = $data->drv_id;
		$this->setData($contactModel);
		$this->userId	 = (int) $userModel->user_id;
		$this->user		 = new \Stub\common\User();
		$this->user->setData($userModel);
		$this->owner	 = new \Stub\common\Person();
		$this->owner->setPersonData($contactModel);
	}

}
