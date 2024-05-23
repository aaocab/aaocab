<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Phone
 *
 * @author Dev
 * 
 * @property integer $id
 * @property string $isdCode
 * @property string $number
 * @property string $fullNumber
 * @property integer $isPrimary
 * @property integer $isVerified
 * @property string $verifiedDate
 * @property string $createDate
 */

namespace Beans\contact;

class Phone
{

	public $id;
	public $isdCode;
	public $number;
	public $fullNumber;
	public $isPrimary;
	public $isVerified;
	public $verifiedDate;
	public $createDate;

	public static function setList($phnDetails)
	{
		$data = [];
		foreach ($phnDetails as $res)
		{
			$obj	 = new Phone();
			$obj->setData($res);
			$data[]	 = $obj;
		}
		return $data;
	}

	public function setData($data)
	{
		$phnObj				 = (is_array($data)) ? \Filter::convertToObject($data) : $data;
		$this->isdCode		 = $phnObj->phn_phone_country_code;
		$this->number		 = $phnObj->phn_phone_no;
		$this->fullNumber	 = ($phnObj->phn_full_number != '') ? $phnObj->phn_full_number : $phnObj->phn_phone_country_code . $phnObj->phn_phone_no;
		$this->isPrimary	 = ($phnObj->phn_is_primary != '') ? (int) $phnObj->phn_is_primary : null;
		$this->isVerified	 = ($phnObj->phn_is_verified != '') ? (int) $phnObj->phn_is_verified : null;
		$this->verifiedDate	 = $phnObj->phn_verified_date;
		$this->createDate	 = $phnObj->phn_create_date;
	}

	public function setByContactId($cttId)
	{
		$phoneData = \ContactPhone::model()->findByContactID($cttId);
		if (sizeof($phoneData) == 0)
		{
			return;
		}
		return Phone::setList($phoneData);
	}

	public static function setUserPhone($phone)
	{

		$obj			 = new Phone();
		$obj->isdCode	 = $phone['code'];
		$obj->number	 = $phone['number'];
		$obj->fullNumber = $phone['code'] . $phone['number'];
		return $obj;
	}

	public static function setObjPhone($phone)
	{

		$obj			 = new Phone();
		$obj->isdCode	 = $phone->code.$phone->isdCode;
		$obj->number	 = $phone->number;
		$obj->fullNumber = $obj->isdCode . $phone->number;
		return $obj;
	}

	/**
	 *  @param Phone $objPhone 
	 *  @return \ContactPhone
	 */
	public static function setPhoneModel($objPhone)
	{
		$phnModel = null;
		if($objPhone->fullNumber == NULL)
		{
			$objPhone->fullNumber = $objPhone->isdCode . $objPhone->number;
		}
		if ($objPhone != null && $objPhone->number != '')
		{
			\Filter::parsePhoneNumber("+" . $objPhone->fullNumber, $code, $number);
			$phnModel							 = new \ContactPhone();
			$phnModel->phn_phone_country_code	 = $code;
			$phnModel->phn_phone_no				 = $number;
			if ($objPhone->isVerified == 1)
			{
				$phnModel->phn_is_verified = 1;
			}
		}
		return $phnModel;
	}

	/**
	 * 
	 * @param type $data
	 * @return \ContactPhone
	 */
	public static function setByData($data)
	{
		$phnNumber	 = \Filter::processPhoneNumber($data['number']);
		if (!$phnNumber)
		{
			return;
		}
		return self::setByNumber($phnNumber);
	}

	/**
	 * 
	 * @param type $data
	 * @return \ContactPhone
	 */
	public static function setByNumber($phnNumber)
	{
		$dataObj = [];
		if ($phnNumber != null && $phnNumber != '')
		{
			\Filter::parsePhoneNumber($phnNumber, $code, $number);

			$obj			 = new Phone();
			$obj->isdCode	 = $code;
			$obj->number	 = $number;
			$obj->fullNumber = $code . $number;
			$dataObj[]		 = $obj;
		}
		return $dataObj;
	}

	/**
	 * 
	 * @param integer $phnNumber
	 * @return \Beans\contact\Phone
	 */
	public static function setFullNumber($phnNumber)
	{
		$dataObj = [];
		if ($phnNumber != null && $phnNumber != '')
		{
			\Filter::parsePhoneNumber($phnNumber, $code, $number);
			$obj			 = new Phone();
			$obj->fullNumber = '+' . $code . $number;
			$dataObj[]		 = $obj;
		}
		return $dataObj;
	}
}
