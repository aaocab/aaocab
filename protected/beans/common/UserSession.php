<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserSession
 *
 * @author Dev
 * 
 * @property integer $id
 * @property integer $loginType
 * @property \Beans\Driver $driver
 * @property \Beans\Vendor $vendor
 * @property \Beans\common\User $consumer
 * @property string $loginDate 
 * @property \Beans\common\DeviceInfo $device
 * @property string $validTill 
 * @property string $missingData
 */

namespace Beans\common;

class UserSession
{

	public $id;
	public $userTypes;
	public $loginType;  // OTP/Social/Password

	/** @var \Beans\common\User $consumer */
	public $consumer;

	/** @var \Beans\contact\Person $contact */
	public $contact;

	/** @var \Beans\Vendor $vendor */
	public $vendor;

	/** @var \Beans\Driver $driver */
	public $driver;
	public $loginDate;
	public $validTill;

	/**
	 * 
	 * @param type $loginType
	 * @param type $contactData
	 */
	/* @deprecated
	 * new function setLoginResponseV1
	 */
	public function setLoginResponse($contactData)
	{
		if ($loginType > 0)
		{
			$this->loginType = $loginType;
		}
		$this->setProfile($contactData);
	}

	public function setLoginResponseV1($contactData)
	{

		$vndId			 = $contactData['cr_is_vendor'];
		$drvId			 = $contactData['cr_is_driver'];
		$cttId			 = $contactData['cr_contact_id'];
		$userId			 = $contactData['cr_is_consumer'];
		$this->userTypes = \ContactProfile::getEntityListByData($contactData);
		if (empty($cttId))
		{
			throw new \Exception("Invalid User : ", \ReturnSet::ERROR_INVALID_DATA);
		}
		$contactInfo	 = new \Beans\contact\Person();
		$obj			 = $contactInfo->setBasicInfo($cttId);
		$this->contact	 = $obj;
		if ($vndId != '')
		{
			$model			 = \Vendors::model()->findByPk($vndId);
			$vndObj			 = new \Beans\Vendor();
			$obj			 = $vndObj->setDataByModelV1($model);
			$this->vendor	 = $obj;
		}
		if ($drvId != '')
		{
			$drvObj			 = new \Beans\Driver();
			$drvObj->setProfileDataV1($drvId);
			$this->driver	 = $drvObj;
		}
	}

	/* @deprecated
	 * new function setProfileV1
	 */

	public function setProfile($contactData)
	{

		$vndId	 = $contactData['cr_is_vendor'];
		$drvId	 = $contactData['cr_is_driver'];
		$cttId	 = $contactData['cr_contact_id'];
		$userId	 = $contactData['cr_is_consumer'];
		if (empty($cttId))
		{
			throw new \Exception("Invalid User : ", \ReturnSet::ERROR_INVALID_DATA);
		}

		$drvActive		 = $contactData['drv_active'];
	$vndActive		 = $contactData['vnd_active'];

		if ($userId != '')
		{
			$usrObj			 = new \Beans\common\User();
			$usrObj->setDataByContact($userId, $cttId);
			$this->consumer	 = $usrObj;
		}
		if ($vndId != '')
		{
			$vndObj			 = new \Beans\Vendor();
			$vndObj->setData($vndId, $cttId);
			$this->vendor	 = $vndObj;
		}
		if ($drvId > 0)
		{
			$drvObj			 = new \Beans\Driver();
			$drvObj->setData($drvId, $cttId);
			$this->driver	 = $drvObj;
		}
	}

	/**
	 * 
	 * @param type $contactData
	 * @throws Exception
	 */
	public function setProfileV1($contactData)
	{

		$vndId			 = $contactData['cr_is_vendor'];
		$drvId			 = $contactData['cr_is_driver'];
		$cttId			 = $contactData['cr_contact_id'];
		$this->userTypes = \ContactProfile::getEntityListByData($contactData);
		if (empty($cttId))
		{
			throw new \Exception("Invalid User : ", \ReturnSet::ERROR_INVALID_DATA);
		}
		$contactInfo	 = new \Beans\contact\Person();
		$contactInfo->setDataByIdV1($cttId);
		$this->contact	 = $contactInfo;
		if ($vndId > 0)
		{
			$vndObj			 = new \Beans\Vendor();
			$vndObj->setProfileDataV1($vndId);
			$this->vendor	 = $vndObj;
		}
		if ($drvId > 0)
		{
			$drvObj			 = new \Beans\Driver();
			$drvObj->setProfileDataV1($drvId);
			$this->driver	 = $drvObj;
		}
	}

	/**
	 * 
	 * @param type $contactData
	 * @throws Exception
	 */
	public function setUserProfile($contactData)
	{
		$cttId	 = $contactData['cr_contact_id'];
		$userId	 = $contactData['cr_is_consumer'];
		if (empty($cttId))
		{
			throw new \Exception("Invalid User : ", \ReturnSet::ERROR_INVALID_DATA);
		}

		if ($userId != '')
		{
			$usrObj			 = new \Beans\common\User();
			$usrObj->setDataByContact($userId, $cttId);
			$this->consumer	 = $usrObj;
		}
	}

	public function setBasicUserProfile($contactData)
	{
		$cttId			 = $contactData['cr_contact_id'];
		$this->consumer	 = \Beans\contact\Person::setBasicInfo($cttId);
	}

	public function setTempUserProfile($data)
	{
		$this->consumer = \Beans\contact\Person::setBasicInfoFromData($data);
	}

	public function setProfileOld($contactData)
	{

		$vndId	 = $contactData['cr_is_vendor'];
		$drvId	 = $contactData['cr_is_driver'];
		$cttId	 = $contactData['cr_contact_id'];
		$userId	 = $contactData['cr_is_consumer'];
		if (empty($cttId))
		{
			throw new \Exception("Invalid User : ", \ReturnSet::ERROR_INVALID_DATA);
		}

		if ($userId != '')
		{
			$usrObj			 = new \Beans\common\User();
			$usrObj->setDataByContact($userId, $cttId);
			$this->consumer	 = $usrObj;
		}
		if ($vndId != '')
		{
			$vndObj			 = new \Beans\Vendor();
			$vndObj->setData($vndId, $cttId);
			$this->vendor	 = $vndObj;
		}
		if ($drvId > 0)
		{
			$drvObj			 = new \Beans\Driver();
			$drvObj->setData($drvId, $cttId);
			$this->driver	 = $drvObj;
		}
	}

}
