<?php

namespace Stub\common;

/**
 * @property Cities $location
 * @property Email $primaryEmail
 * @property Phone $primaryContact
 * @property Phone $alternateContact
 * @property Document $Licence
 * 
 * */
class Person
{

	public $id;
	public $firstName, $lastName, $userName, $elseFirstName, $elseLastName;
	public $pincode, $address, $address1, $gender;
	public $email, $city, $fullName, $state, $country, $contactNo, $owner, $language;
	public $companyName, $gstin;
	//public $drivingLicence,$pan;

	/** @var \Stub\common\Cities $location */
	public $location;

	/** @var \Stub\common\Phone $primaryContact  */
	public $primaryContact;

	/** @var \Stub\common\Phone $alternateContact */
	public $alternateContact;

	/** @var \Stub\common\Email $primaryEmail */
	public $primaryEmail;
	public $documents;

	/** @var \Stub\common\Document $Licence */
	public $Licence;
	public $Pan;

	/**
	 * This function is user for set contact mode data
	 * @param \Contact $model
	 * @return \Contact
	 */
	public function init(\Contact $model = null)
	{
		if ($model == null)
		{
			$model = new \Contact;
		}
		if ($this->id > 0)
		{
			$model = \Contact::model()->findByPk($this->id);
		}
		$model->ctt_first_name	 = $this->firstName;
		$model->ctt_last_name	 = $this->lastName;
		if ($this->city > 0)
		{
			$model->ctt_city = $this->city;
		}
		$documents				 = new \Stub\common\Documents;
		$documents->setContactModel($model, $this->documents);
		//Contact Details
		$emails					 = [];
		$phones					 = [];
		array_push($emails, $this->getContactEmail());
		array_push($phones, $this->getContactPhone());
		$model->contactPhones	 = $phones;
		$model->contactEmails	 = $emails;
		$model->ctt_created_date = new \CDbExpression('now()');

		return $model;
	}

	public function getModel(\BookingUser $model = null)
	{
		/* @var $model BookingUser */
		if ($model == null)
		{
			$model = new \BookingUser();
		}
		$model->bkg_user_fname		 = $this->firstName;
		$model->bkg_user_lname		 = $this->lastName;
		$model->bkg_user_email		 = $this->email;
		$model->bkg_bill_address	 = $this->address;
		$model->bkg_bill_company	 = $this->companyName;
		$model->bkg_bill_gst		 = $this->gstin;

		$model->bkg_country_code	 = (int) $this->primaryContact->code;
		$model->bkg_contact_no		 = $this->primaryContact->number;
		$model->bkg_alt_country_code = $this->alternateContact->code;
		$model->bkg_alt_contact_no	 = $this->alternateContact->number;
		return $model;
	}

	public function getTempModel(\BookingTemp $model = null)
	{
		/* @var $model BookingUser */
		if ($model == null)
		{
			$model = new \BookingTemp();
		}
		$model->bkg_user_name	 = $this->firstName;
		$model->bkg_user_lname	 = $this->lastName;
		$model->bkg_user_email	 = $this->email;
		if ($this->primaryContact != null)
		{
			$model->bkg_country_code = (int) $this->primaryContact->code;
			$model->bkg_contact_no	 = $this->primaryContact->number;
		}
		if ($this->alternateContact != '')
		{
			$model->bkg_alt_country_code = $this->alternateContact->code;
			$model->bkg_alt_contact_no	 = $this->alternateContact->number;
		}
		return $model;
	}

	public function setModelData(\BookingUser $model = null, $maskNumber = true)
	{
		if ($model == null)
		{
			return $this;
		}
		$this->firstName				 = ($model->bkg_user_fname != "" ? $model->bkg_user_fname : "");
		$this->lastName					 = ($model->bkg_user_lname != "" ? $model->bkg_user_lname : "");
		$this->email					 = ($model->bkg_user_email != "" ? $model->bkg_user_email : "");
		$this->primaryContact->code		 = ($model->bkg_country_code != "" ? $model->bkg_country_code : 0);
		//$this->primaryContact->number	 = ($model->bkg_contact_no!=""?$model->bkg_contact_no:"");
		$bkg_contact_no					 = ($model->bkg_contact_no != "") ? $model->bkg_contact_no : "";
		$this->primaryContact->number	 = $bkg_contact_no;
		if ($model->bkg_contact_no != '' && $maskNumber)
		{
			$timeDiff						 = \Filter::getTimeDiff($model->buiBkg->bkg_pickup_date, null);
			$this->primaryContact->number	 = \BookingPref::getCustomerNumber($model->buiBkg, $bkg_contact_no, $maskNumber);
			$this->primaryContact->number	 = (($timeDiff) < 120) ? $bkg_contact_no : $this->primaryContact->number;
		}
		if ($model->bkg_alt_contact_no != '')
		{
			$this->alternateContact->code	 = ($model->bkg_alt_country_code != "" ? $model->bkg_alt_country_code : "");
			$this->alternateContact->number	 = ($model->bkg_alt_contact_no != "" ? $model->bkg_alt_contact_no : "");
		}
		return $this;
	}

	public function setTempModelData(\BookingTemp $model)
	{
		$this->firstName = $model->bkg_user_name;
		$this->lastName	 = $model->bkg_user_lname;
		$this->email	 = $model->bkg_user_email;
		if ($this->primaryContact == null)
		{
			$this->primaryContact = new Phone();
		}

		$this->primaryContact->code		 = $model->bkg_country_code;
		$this->primaryContact->number	 = $model->bkg_contact_no;
		if ($model->bkg_alternate_contact != '')
		{
			$this->alternateContact			 = new Phone();
			$this->alternateContact->code	 = $model->bkg_alt_country_code;
			$this->alternateContact->number	 = $model->bkg_alternate_contact;
		}
		return $this;
	}

	public function setConsumerData(\Users $model)
	{
		$this->firstName = $model->usr_name;
		$this->lastName	 = $model->usr_lname;
		$this->email	 = $model->usr_email;
		return $this;
	}

	public function setPersonData(\Contact $contactModel, $agreementModel = null, $phoneNumber = '')
	{
		
		$this->id			 = (int) $contactModel->ctt_id;
		$this->firstName	 = $contactModel->ctt_first_name;
		$this->lastName		 = $contactModel->ctt_last_name;
		$this->profilePic	 = \Users::getImageUrl($contactModel->ctt_profile_path);
		$docModels			 = \Document::documentType();

		foreach ($docModels as $doc)
		{
			$docModel	 = new \Stub\common\Document();
			$document	 = "Document_" . $doc;

			//$agrementModel	 = VendorAgreement::model()->findAgreementByVndId($contactModel->ctt_first_name);
			$this->documents[$doc] = $docModel->setModelData($contactModel, $document, $agreementModel);
		}
		$this->documents = (object) $this->documents;
		// print_r($this->documents);
		$arr			 = $contactModel->getContactDetails($contactModel->ctt_id);

		$this->address	 = $arr['ctt_address'];
		$this->city		 = $arr['ctt_city'];
		$cityModel		 = new \Stub\common\Cities();
		$this->location	 = $cityModel->getIdName($this->city);
		$this->state	 = $arr['ctt_state'];
		$this->email	 = $arr['eml_email_address'];
		if (\Config::get('maskNumbersCbm') == 1 && $contactModel->isServiceCall == 1)
		{
			$this->primaryContact->code		 = (int) 91;
			$this->primaryContact->number	 = \Config::get('csrToCustomers');
		}
		else
		{
			$this->primaryContact->code		 = (int) $arr['phn_phone_country_code'];
			$this->primaryContact->number	 = $arr['phn_phone_no'];
		}
		$this->language		 = (int) $arr['ctt_preferred_language'];
		$arrPhoneByPriority	 = $contactModel::getPhoneNoByPriority($contactModel->ctt_id);
		if ($this->primaryContact->number == "")
		{
			if (\Config::get('maskNumbersCbm') == 1 && $contactModel->isServiceCall == 1)
			{
				$this->primaryContact->code		 = (int) 91;
				$this->primaryContact->number	 = \Config::get('csrToCustomers');
			}
			else
			{
				$this->primaryContact->code		 = (int) $arrPhoneByPriority['phn_phone_country_code'];
				$this->primaryContact->number	 = $arrPhoneByPriority['phn_phone_no'];
			}
		}

		if ($phoneNumber != '')
		{
			\Filter::parsePhoneNumber($phoneNumber, $code, $phone);
			$this->primaryContact->code		 = (int) $code;
			$this->primaryContact->number	 = $phone;
		}
	}

	public function SetCustomerData($model)
	{
		$this->userName					 = $model['user_name'];
		$this->primaryContact->code		 = 91;
		$this->primaryContact->number	 = $model['contact_no'];
		$this->email					 = $model['email'];
	}

	/**
	 * This function is used for mapping temp contact details
	 * @param \TempContacts $model
	 * @return \TempContacts
	 */
	public function getTempContactModel(\ContactTemp $model = null)
	{
		if ($model == null)
		{
			$model = new \ContactTemp();
		}

		$model->tmp_ctt_email		 = $this->email;
		$model->tmp_ctt_name		 = $this->firstName . " " . $this->lastName;
		$model->tmp_ctt_phn_code	 = $this->primaryContact->code;
		$model->tmp_ctt_phn_number	 = $this->primaryContact->number;
		$model->tmp_ctt_phn_otp		 = rand(1000, 9999);
		$model->tmp_ctt_profile		 = \UserInfo::TYPE_DRIVER;
		$model->tmp_ctt_license		 = $this->documents->Licence->refValue;
		$model->tmp_ctt_status		 = 1; //Unverified
		$model->tmp_ctt_expiry_time	 = time() + 4 * 60 * 60; // now +2 hour
		$model->tmp_ctt_created		 = new \CDbExpression('now()');
		$model->tmp_ctt_modified	 = new \CDbExpression('now()');

		return $model;
	}

	/**
	 * This function is used for getting contact email
	 * @param type $data		-	Received contact details
	 * @return \ContactEmail
	 */
	public function getContactEmail()
	{
		$contactEmailModel = new \ContactEmail();

		$contactEmailModel->eml_email_address	 = $this->email;
		$contactEmailModel->eml_is_primary		 = 1;
		$contactEmailModel->eml_is_verified		 = 1;
		$contactEmailModel->eml_is_verified		 = 0;
		$contactEmailModel->eml_active			 = 1;

		return $contactEmailModel;
	}

	/**
	 * This function is used for getting the contact email model
	 * @param type $data
	 * @return \ContactPhone
	 */
	public function getContactPhone()
	{
		$phModel = new \ContactPhone();

		$phModel->phn_phone_no			 = ltrim($this->primaryContact->number, 0);
		$phModel->phn_phone_country_code = $this->primaryContact->code;
		$phModel->phn_otp				 = rand(1000, 9999);
		$phModel->phn_is_verified		 = 0;
		$phModel->phn_is_primary		 = 1;
		$phModel->phn_active			 = 1;
		return $phModel;
	}

	public function setLeadData($refId, $refType)
	{
		switch ((int) $refType)
		{
			case 3:
				$fwpModel	 = \FollowUps::model()->findByPk($refId);
				$model		 = \Contact::model()->findByPk($fwpModel->fwp_contact_id);
				$phoneNumber = '';
				if ($fwpModel->fwp_contact_phone_no != null)
				{
					$phoneNumber = $fwpModel->fwp_contact_phone_no;
				}
				if ($fwpModel->fwp_contact_type == 2)
				{
					\Filter::parsePhoneNumber($phoneNumber, $code, $phone);
					$this->primaryContact->code		 = (int) $code;
					$this->primaryContact->number	 = $phone;
				}
				else
				{
					$this->setPersonData($model, null, $phoneNumber);
				}

				break;
			case 1:
				$model	 = \BookingTemp::model()->findByPk($refId);
				$this->setTempModelData($model);
				break;
			case 2:
				$model	 = \Booking::model()->findByPk($refId);
				if ($model)
				{
					$model = \BookingUser::model()->getByBkgId($refId);
					$this->setModelData($model);
				}
				break;
			default:
				$model = \Booking::model()->findByPk($refId);
				if ($model)
				{
					$model = \BookingUser::model()->getByBkgId($refId);
					$this->setModelData($model);
				}
				if (!$model)
				{
					$model = \BookingTemp::model()->findByPk($refId);
					if (empty($model->bkg_id))
					{
						$model = \BookingUser::model()->getByBkgId($refId);
						$this->setModelData($model);
					}
					else
					{
						$this->setTempModelData($model);
					}
				}
				break;
		}
	}

	public function setAdminData($id)
	{
		$this->id	 = (int) $id;
		$model		 = \Admins::model()->findByPk($id);
		$this->name	 = $model->adm_fname . " " . $model->adm_lname;
		return $this;
	}

	public function setDrvLoginData($drvModel)
	{

		//$rating		 = DriverStats::fetchRating($drvModel->drv_id);
//		$userName	 = $drvModel->drv_name;
//		$phnNo		 = ContactPhone::model()->getContactPhoneById($drvModel->drv_contact_id);
//		$emlId		 = ContactEmail::model()->getContactEmailById($drvModel->drv_contact_id);
//		$drvCode	 = $drvModel->drv_code;
		//$drvPrefLang = $drvModel->drvContact->ctt_preferred_language;
		$model = \Contact::model()->findByPk($drvModel->drv_contact_id);
		$this->setPersonData($model);

		return $this;
	}

	/**
	 * @return \Contact
	 * */
	public function getContactModel()
	{
		$cttModel					 = new \Contact();
		$cttModel->ctt_first_name	 = $this->firstName;
		$cttModel->ctt_last_name	 = $this->lastName;

		$emlModel = \Stub\common\Email::getEmailModel($this->primaryEmail);
		if ($emlModel)
		{
			$cttModel->contactEmails	 = [];
			$emlModel->eml_is_primary	 = 1;
			$emlModels					 = [$emlModel];
			$cttModel->contactEmails	 = $emlModels;
		}

		$phnModel = \Stub\common\Phone::getPhoneModel($this->primaryContact);
		if ($phnModel)
		{
			$cttModel->contactPhones	 = [];
			$phnModel->phn_is_primary	 = 1;
			$phnModels				 = [$phnModel];
			$cttModel->contactPhones = $phnModels;
		}

		return $cttModel;
	}

}
