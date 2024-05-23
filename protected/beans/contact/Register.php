<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Register
 *
 * @author Dev
 * 
 * 
 * @property \Beans\contact\Person $profile
 * @property \Beans\common\AuthRequest $auth
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

class Register extends \Beans\contact\Person
{

	public $firstName;
	public $lastName;

	/** @var \Beans\contact\Email[] $email */
	public $email = [];

	/** @var \Beans\contact\Phone[] $phone */
	public $phone = [];

	/** @var \Beans\common\AuthRequest $auth */
	public $auth;

//	public function setData($obj, $hideDetails = false)
//	{
//		$data			 = \Contact::getDetails($cttId);
//		$this->firstName = $data['ctt_first_name'];
//		$this->lastName	 = $data['ctt_last_name'];
//
//		$objPhn		 = new \Beans\contact\Phone();
//		$this->phone = $objPhn->setByContactId($cttId);
//		if (!$hideDetails)
//		{
//			$objEmail		 = new \Beans\contact\Email();
//			$this->email	 = $objEmail->setByContactId($cttId);
//			$this->address	 = \Beans\common\Location::setAddress($data);
////		$objCity		 = new \Beans\common\City();
////		$objCity->setData($data);
//			$this->city		 = \Beans\common\City::setDetail($data);
//
//			$this->aadhaar			 = $data['ctt_aadhaar_no'];
//			$this->voter			 = $data['ctt_voter_no'];
//			$this->pan				 = $data['ctt_pan_no'];
//			$this->dlNumber			 = $data['ctt_license_no'];
//			$this->dlExpiryDate		 = $data['ctt_license_exp_date'];
//			$this->dlIssuingState	 = \States::model()->getNameById($data['ctt_dl_issue_authority']);
//			$objAccount				 = new \Beans\common\AccountInfo();
//			$objAccount->setData($data);
//			$this->accountInfo[]	 = $objAccount;
//			$objDocument			 = new \Beans\common\Document();
//			$this->documents		 = $objDocument->setByContactId($cttId);
//			$prefLanguage			 = $data['ctt_preferred_language'] | 0;
//			$langArr				 = \Contact::languageList($prefLanguage);
//
//			$this->preferredLanguage = \Beans\common\ValueObject::setIdlabel($langArr['id'], $langArr['text'], $langArr['val']);
//		}
//	}
//
//	/** @var \BookingUser $bkgUserInfo */
//	public static function setTravellerInfoByModel($bkgUserInfo)
//	{
//		$obj			 = new Person();
//		$obj->firstName	 = $bkgUserInfo->bkg_user_fname;
//		$obj->lastName	 = $bkgUserInfo->bkg_user_lname;
//		$obj->email[]	 = \Beans\contact\Email::setUserEmail($bkgUserInfo->bkg_user_email);
//		if (trim($bkgUserInfo->bkg_contact_no) != '')
//		{
//			$obj->phone[] = \Beans\contact\Phone::setUserPhone(['code' => $bkgUserInfo->bkg_country_code, 'number' => $bkgUserInfo->bkg_contact_no]);
//		}
//		return $obj;
//	}
//
//	public static function setBasicInfo($cttId)
//	{
//		$obj			 = new Person();
//		$data			 = \Contact::getDetails($cttId);
//		$obj->firstName	 = $data['ctt_first_name'];
//		$obj->lastName	 = $data['ctt_last_name'];
//
//		$objPhn		 = new \Beans\contact\Phone();
//		$obj->phone	 = $objPhn->setByContactId($cttId);
//
//		$objEmail		 = new \Beans\contact\Email();
//		$obj->email		 = $objEmail->setByContactId($cttId);
//		$prefLanguage	 = $data['ctt_preferred_language'] | 0;
//		$langArr		 = \Contact::languageList($prefLanguage);
//
//		$obj->preferredLanguage = \Beans\common\ValueObject::setIdlabel($langArr['id'], $langArr['text'], $langArr['val']);
//
//		return $obj;
//	}

	public function updateProfileData($decryptData)
	{
		$decryptObj = json_decode($decryptData, false);
		if ($decryptObj->type == \Beans\common\ContactVerification::TYPE_EMAIL)
		{
			$this->email[0]->address	 = $decryptObj->value;
			$this->email[0]->isVerified	 = true;
		}
		if ($decryptObj->type == \Beans\common\ContactVerification::TYPE_PHONE)
		{
			\Filter::parsePhoneNumber($decryptObj->value, $code, $number);
			$this->phone[0]->number		 = $number;
			$this->phone[0]->isdCode	 = $code;
			$this->phone[0]->fullNumber	 = $code . $number;
			$this->phone[0]->isVerified	 = true;
		}
	}

}
