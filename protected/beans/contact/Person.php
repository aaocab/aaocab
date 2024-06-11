<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Person
 *
 * @author Dev
 * 
 * @property integer $id
 * @property string $firstName
 * @property string $lastName
 * @property \Beans\contact\Email[] $email
 * @property \Beans\contact\Phone[] $phone
 * @property \Beans\common\Location $address
 * @property \Beans\common\City $city
 * @property string $birthDate
 * @property string $gender
 * @property string $aadhaar
 * @property string $pan
 * @property string $dlNumber
 * @property string $dlExpiryDate
 * @property string $passportNumber
 * @property string $passportExpiry
 * @property \Beans\common\AccountInfo[] $accountInfo
 * @property \Beans\common\Document[] $documents
 */

namespace Beans\contact;

class Person extends \Beans\contact\Contact
{

	public $id;
	public $firstName;
	public $lastName;
	public $profileImageUrl;

	/** @var \Beans\contact\Email[] $email */
	public $email;

	/** @var \Beans\contact\Phone[] $phone */
	public $phone;

	/** @var \Beans\common\Location $address */
	public $address;

	/** @var \Beans\common\City $city */
	public $city;
	public $birthDate;
	public $gender;
	public $aadhaar;
	public $voter;
	public $pan;
	public $dlNumber;
	public $dlExpiryDate;
	public $dlIssuingState;
	public $dlIssuingDate;
	public $passportNumber;
	public $passportExpiry;

	/** @var \Beans\common\AccountInfo[] $accountInfo */
	public $accountInfo;

	/** @var \Beans\common\Document[] $documents */
	public $documents;

	/** @var \Beans\common\ValueObject $preferredLanguage */
	public $preferredLanguage;

	public function setDataById($cttId, $hideDetails = false)
	{
		$data			 = \Contact::getDetails($cttId);
		$this->firstName = $data['ctt_first_name'];
		$this->lastName	 = $data['ctt_last_name'];

		$objPhn		 = new \Beans\contact\Phone();
		$this->phone = $objPhn->setByContactId($cttId);
		if (!$hideDetails)
		{
			$objEmail	 = new \Beans\contact\Email();
			$this->email = $objEmail->setByContactId($cttId);
			if ($data['ctt_address'] != '')
			{
				$this->address = \Beans\common\Location::setAddress($data);
			}
			if ($data['ctt_city'] > 0)
			{
				$this->city = \Beans\common\City::setDetail($data);
			}
			$this->aadhaar		 = $data['ctt_aadhaar_no'];
			$this->voter		 = $data['ctt_voter_no'];
			$this->pan			 = $data['ctt_pan_no'];
			$this->dlNumber		 = $data['ctt_license_no'];
			$this->dlExpiryDate	 = $data['ctt_license_exp_date'];
			/* $this->dlIssuingState	 = \States::model()->getNameById($data['ctt_dl_issue_authority']);
			  if($data['ctt_dl_issue_authority'] != "")
			  {
			  $state->text =\States::model()->getNameById($data['ctt_dl_issue_authority']);
			  $this->dlIssuingState = \Beans\common\State::setStateIdName($state);
			  } */
			if (trim($data['ctt_bank_account_no']) != '')
			{
				$this->accountInfo[] = \Beans\common\AccountInfo::setData($data);
			}
			$objDocument	 = new \Beans\common\Document();
			$this->documents = $objDocument->setByContactIdV1($cttId);

			$prefLanguage			 = $data['ctt_preferred_language'] | 0;
			$langArr				 = \Contact::languageList($prefLanguage);
			$this->preferredLanguage = \Beans\common\ValueObject::setIdlabel($langArr['id'], $langArr['text'], $langArr['val']);
		}
	}

	public function setDataByIdV1($cttId, $hideDetails = false)
	{
		$data			 = \Contact::getDetails($cttId);
		$this->firstName = $data['ctt_first_name'];
		$this->lastName	 = $data['ctt_last_name'];
		if ($data['ctt_profile_path'] != '')
		{
			$this->profileImageUrl = \Yii::app()->params['fullAPIBaseURL'] . \AttachmentProcessing::ImagePath($data['ctt_profile_path']);
		}
		$objPhn		 = new \Beans\contact\Phone();
		$this->phone = $objPhn->setByContactId($cttId);
		if (!$hideDetails)
		{
			$objEmail	 = new \Beans\contact\Email();
			$this->email = $objEmail->setByContactId($cttId);
			if ($data['ctt_address'] != '')
			{
				$this->address = \Beans\common\Location::setAddress($data);
			}
			$this->aadhaar	 = $data['ctt_aadhaar_no'];
			$this->voter	 = $data['ctt_voter_no'];
			$this->pan		 = $data['ctt_pan_no'];
			$this->dlNumber	 = $data['ctt_license_no'];

			$this->dlExpiryDate	 = $data['ctt_license_exp_date'];
			$this->dlIssuingDate = $data['ctt_license_issue_date'];
			if ($data['ctt_dl_issue_authority'] != "")
			{
				$state->id				 = $data['ctt_dl_issue_authority'];
				$state->text			 = \States::model()->getNameById($data['ctt_dl_issue_authority']);
				$this->dlIssuingState	 = \Beans\common\State::setStateIdName($state);
			}

			if (trim($data['ctt_bank_account_no']) != '')
			{
				$this->accountInfo[] = \Beans\common\AccountInfo::setData($data);
			}
			$objDocument			 = new \Beans\common\Document();
			$this->documents		 = $objDocument->setByContactIdV1($cttId);
			$prefLanguage			 = $data['ctt_preferred_language'] | 0;
			$langArr				 = \Contact::languageList($prefLanguage);
			$this->preferredLanguage = \Beans\common\ValueObject::setIdlabel($langArr['id'], $langArr['text'], $langArr['val']);
		}
	}

	/** @var \BookingUser $bkgUserInfo */
	public static function setTravellerInfoByModel($bkgUserInfo, $showCustomerNumber = 1)
	{
		$obj			 = new Person();
		$obj->firstName	 = $bkgUserInfo->bkg_user_fname;
		$obj->lastName	 = $bkgUserInfo->bkg_user_lname;
		if ($showCustomerNumber == 0)
		{
			$maskingNumber	 = \Yii::app()->params['driverToCustomer'];
			$obj->phone[]	 = \Beans\contact\Phone::setUserPhone(['code' => '', 'number' => $maskingNumber]);
			goto skip;
		}
		$obj->email[] = \Beans\contact\Email::setUserEmail($bkgUserInfo->bkg_user_email);

		$obj->phone[] = \Beans\contact\Phone::setUserPhone(['code' => $bkgUserInfo->bkg_country_code, 'number' => $bkgUserInfo->bkg_contact_no]);
		skip:

		return $obj;
	}

	public static function setBasicInfo($cttId)
	{
		$obj			 = new Person();
		$data			 = \Contact::getDetails($cttId);
		$obj->firstName	 = $data['ctt_first_name'];
		$obj->lastName	 = $data['ctt_last_name'];
		$objPhn			 = new \Beans\contact\Phone();
		$obj->phone[]	 = \Beans\contact\Phone::setUserPhone($data);
//		$obj->phone		 = \Beans\contact\Phone::setObjPhone($objPhn);
		$objEmail		 = new \Beans\contact\Email();
		$obj->email		 = $objEmail->setByContactId($cttId);
		$prefLanguage	 = $data['ctt_preferred_language'] | 0;
		$langArr		 = \Contact::languageList($prefLanguage);

		$obj->preferredLanguage = \Beans\common\ValueObject::setIdlabel($langArr['id'], $langArr['text'], $langArr['val']);
		return $obj;
	}

	public static function setBasicInfoFromData($data)
	{
		$obj			 = new Person();
		$obj->firstName	 = $data['fname'];
		$obj->lastName	 = $data['lname'];

		$obj->phone	 = \Beans\contact\Phone::setByData($data);
		$obj->email	 = \Beans\contact\Email::setByData($data);
		return $obj;
	}

	/**
	 * @return \Contact
	 * */
	public function getContactModel()
	{
		$cttModel					 = new \Contact();
		$cttModel->ctt_first_name	 = $this->firstName;
		$cttModel->ctt_last_name	 = $this->lastName;
		$emlModel					 = \Beans\contact\Email::setEmailModel($this->email[0]);
		if ($emlModel)
		{
			$cttModel->contactEmails	 = [];
			$emlModel->eml_is_primary	 = 1;
			$emlModels					 = [$emlModel];
			$cttModel->contactEmails	 = $emlModels;
		}

		$phnModel = \Beans\contact\Phone::setPhoneModel($this->phone[0]);
		if ($phnModel)
		{
			$cttModel->contactPhones	 = [];
			$phnModel->phn_is_primary	 = 1;
			if ($this->primaryContact->isVerified == 1)
			{
				$phnModel->phn_is_verified = 1;
			}
			$phnModels				 = [$phnModel];
			$cttModel->contactPhones = $phnModels;
		}
		return $cttModel;
	}

	public function getdata($reqObj)
	{
		if ($reqObj->id > 0)
		{
			$contactId			 = \ContactProfile::getByDrvId($reqObj->id);
			$model				 = \Contact::model()->findByPk($contactId);
			$model->ctt_driver	 = $reqObj->id;
		}
		else
		{
			$model = new \Contact;
		}
		$model->ctt_first_name	 = $reqObj->firstName;
		$model->ctt_last_name	 = $reqObj->lastName;
		if ($this->city > 0)
		{
			$model->ctt_city = $this->city;
		}
		$documents				 = new \Beans\common\Document;
		$documents->setContactModel($model, $reqObj->documents[0]);
		//Contact Details
		$emails					 = [];
		$phones					 = [];
		array_push($emails, $this->getContactEmail($reqObj->email[0]));
		array_push($phones, $this->getContactPhone($reqObj->phone[0]));
		$model->contactPhones	 = $phones;
		$model->contactEmails	 = $emails;
		$model->ctt_license_no	 = str_replace(' ', '', $reqObj->dlNumber);
		$model->ctt_aadhaar_no	 = str_replace(' ', '', $reqObj->aadhaar);
		$model->ctt_created_date = new \CDbExpression('now()');
		return $model;
	}

	public function getContactEmail($email, $contactId = 0)
	{
		$contactEmailModel						 = new \ContactEmail();
		$contactEmailModel->eml_contact_id		 = $contactId;
		$contactEmailModel->eml_email_address	 = $email->address;
		$contactEmailModel->eml_is_primary		 = 1;
		$contactEmailModel->eml_is_verified		 = 1;
		$contactEmailModel->eml_is_verified		 = 0;
		$contactEmailModel->eml_active			 = 1;
		return $contactEmailModel;
	}

	public function getContactPhone($phone)
	{
		$phModel = new \ContactPhone();

		$phModel->phn_phone_no			 = ltrim($phone->number, 0);
		$phModel->phn_phone_country_code = $phone->code;
		$phModel->phn_otp				 = rand(1000, 9999);
		$phModel->phn_is_verified		 = 0;
		$phModel->phn_is_primary		 = 1;
		$phModel->phn_active			 = 1;
		return $phModel;
	}

	public function setdata($reqObj, $contactId = 0)
	{
		if ($contactId > 0)
		{
			$model = \Contact::model()->findByPk($contactId);
		}
		else
		{
			$model = new \Contact;
		}
		$model->ctt_first_name	 = $reqObj->firstName;
		$model->ctt_last_name	 = $reqObj->lastName;
		$model->ctt_name		 = $reqObj->firstName . '' . $reqObj->lastName;

		$documents	 = new \Beans\common\Document;
		$documents->setContactModel($model, $reqObj->documents[0]);
		//Contact Details
		$emails		 = [];
		$phones		 = [];
		array_push($emails, $this->getContactEmail($reqObj->email[0], $contactId));
		array_push($phones, $this->getContactPhone($reqObj->phone[0], $contactId));

		$model->contactPhones	 = $phones;
		$model->contactEmails	 = $emails;

		$model->ctt_license_no			 = str_replace(' ', '', $reqObj->dlNumber);
		$model->ctt_license_issue_date	 = $reqObj->dlIssuingDate;
		$model->ctt_dl_issue_authority	 = $reqObj->dlIssuingState->id;
		$model->ctt_license_exp_date	 = $reqObj->dlExpiryDate;
		$model->ctt_aadhaar_no			 = str_replace(' ', '', $reqObj->aadhaar);
		$model->ctt_created_date		 = new \CDbExpression('now()');
		$addressObj						 = $reqObj->address;
		$model->ctt_address				 = $addressObj->address;
		$model->ctt_city				 = $addressObj->city->id;
		$model->ctt_state				 = $addressObj->city->state->id;
		#\Beans\common\Location::setLocation($model,$reqObj);
		#print_r($model);exit;
		return $model;
	}

	/**
	 * 
	 * @param type $fname
	 * @param type $lname
	 * @return \Beans\contact\Person
	 */
	public static function setFirstLastName($fname, $lname = '')
	{
		$obj			 = new Person();
		$obj->firstName	 = $fname;
		$obj->lastName	 = $lname;
		return $obj;
	}

	public static function getModeldata($obj, $hasDocument = false)
	{
		if ($obj->id > 0)
		{
			$model = \Contact::model()->findByPk($obj->id);
		}
		else
		{
			$model = new \Contact;
		}
		$model->ctt_first_name	 = $obj->firstName;
		$model->ctt_last_name	 = $obj->lastName;
		if ($obj->city > 0)
		{
			$model->ctt_city = $obj->city;
		}
		if ($hasDocument)
		{
			$documents = new \Beans\common\Document;
			$documents->setContactModel($model, $obj->documents[0]);
		}
		//Contact Details
		$emails					 = [];
		$phones					 = [];
		$person					 = new Person();
		array_push($emails, $person->getContactEmail($obj->email[0]));
		array_push($phones, $person->getContactPhone($obj->phone[0]));
		$model->contactPhones	 = $phones;
		$model->contactEmails	 = $emails;
		if ($obj->dlNumber != '')
		{
			$model->ctt_license_no = str_replace(' ', '', $obj->dlNumber);
		}
		if ($obj->aadhaar != '')
		{
			$model->ctt_aadhaar_no = str_replace(' ', '', $obj->aadhaar);
		}
		if ($obj->pan != '')
		{
			$model->ctt_pan_no = str_replace(' ', '', $obj->pan);
		}
		$model->ctt_created_date = ($model->ctt_created_date == '') ? new \CDbExpression('now()') : $model->ctt_created_date;
		return $model;
	}

	public function setFullName()
	{
		return trim($this->firstName . ' ' . $this->lastName);
	}
}
