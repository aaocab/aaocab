<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Email
 *
 * @author Dev
 * 
 * @property integer $id
 * @property string $address
 * @property integer $isPrimary
 * @property integer $isVerified
 * @property integer $verifiedDate
 * @property string $createDate
 */

namespace Beans\contact;

class Email
{

	public $id;
	public $address;
	public $isPrimary;
	public $isVerified;
	public $verifiedDate;
	public $createDate;

	public function setList($emailDetails)
	{
		$data = [];
		foreach ($emailDetails as $res)
		{
			$obj	 = new Email();
			$obj->setData($res);
			$data[]	 = $obj;
		}
		return $data;
	}

	public function setData($item)
	{
		$this->address		 = $item['eml_email_address'];
		$this->isPrimary	 = (int) $item['eml_is_primary'];
		$this->isVerified	 = (int) $item['eml_is_verified'];
		if ($item['eml_is_verified'] == 1)
			$this->verifiedDate	 = $item['eml_verified_date'];
		$this->createDate	 = $item['eml_create_date'];
	}

	public function setByContactId($cttId)
	{
		$emailData = \ContactEmail::model()->findByContactID($cttId);
		if (sizeof($emailData) == 0)
		{
			return;
		}
		return $this->setList($emailData);
	}

	public static function setUserEmail($email)
	{
		$obj			 = new Email();
		$obj->address	 = $email;
		return $obj;
	}

	public static function getEmailModel($objEmail)
	{
		$emlModel = null;
		if ($objEmail != null && $objEmail->value != '')
		{
			$emlModel					 = new \ContactEmail();
			$emlModel->eml_email_address = $objEmail->address;
			$emlModel->eml_is_primary	 = $objEmail->isPrimary;
			if ($objEmail->isVerified == 1)
			{
				$emlModel->eml_is_verified = 1;
			}
		}
		return $emlModel;
	}

	public static function setEmailModel($objEmail)
	{
		$emlModel = null;
		if ($objEmail != null && $objEmail->address != '')
		{
			$emlModel					 = new \ContactEmail();
			$emlModel->eml_email_address = $objEmail->address;
			$emlModel->eml_is_primary	 = $objEmail->isPrimary | 0;
			if ($objEmail->isVerified == 1)
			{
				$emlModel->eml_is_verified = 1;
			}
		}
		return $emlModel;
	}

	public static function setByData($data)
	{
        if(!$data['email'])
        {
            return;
        }
		$email	 = $data['email'];
		$dataObj = [];
		if ($email != null && $email != '')
		{
			$obj			 = new Email();
			$obj->address	 = $email;
			$dataObj[]		 = $obj;
		}
		return $dataObj;
	}

	public static function setByValue($email)
	{
		$dataObj = [];
		if ($email != null && $email != '')
		{
			$obj			 = new Email();
			$obj->address	 = $email;
			$dataObj[]		 = $obj;
		}
		return $dataObj;
	}

}
