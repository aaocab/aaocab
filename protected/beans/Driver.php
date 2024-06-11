<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Driver
 *
 * @author Dev
 * 
 * @property integer $id
 * @property string $code
 * @property string $imageURL
 * @property integer $rating
 * @property string $joiningDate
 * @property \Beans\common\City $homeCity
 * @property \Beans\common\ValueObject  $status
 */

namespace Beans;

class Driver extends \Beans\contact\Person
{

	public $id;
	public $code;
	public $imageURL;
	public $rating;
	public $joiningDate;

	/** @var \Beans\common\City $homeCity */
	public $homeCity;

	/** @var \Beans\common\ValueObject $status */
	public $status;
	public $preferredLanguage;
	public $driver;
	public $birthDate;

	public function setData($drvId, $cttId)
	{
		$data			 = \Drivers::getDetailsById($drvId);
		$this->id		 = (int) $drvId;
		$this->code		 = $data['drv_code'];
		$this->rating	 = $data['countRating'];
		if($data['ctt_city'] > 0)
		{
			$this->homeCity = \Beans\common\City::getData(['id' => $data['ctt_city'], 'name' => $data['city_name']]);
		}

		$drvApproved	 = $data['drv_approved'];
		$statusLabelList = \Drivers::getApproveStatusList();
		if(in_array($drvApproved, array_keys($statusLabelList)))
		{
			$statusLabel	 = $statusLabelList[$drvApproved];
			$this->status	 = \Beans\common\ValueObject::setIdlabel($drvApproved, $statusLabel);
		}

		$this->setDataById($cttId);
	}

	public function setProfileDataV1($drvId)
	{
		$data					 = \Drivers::getDetailsById($drvId);
		$this->id				 = (int) $drvId;
		$this->code				 = $data['drv_code'];
		$this->rating			 = $data['countRating'];
		$drvApproved			 = $data['drv_approved'];
		$this->address->pincode	 = $data['drv_zip'];
		$statusLabelList		 = \Drivers::getApproveStatusList();
		if(in_array($drvApproved, array_keys($statusLabelList)))
		{
			$statusLabel	 = $statusLabelList[$drvApproved];
			$this->status	 = \Beans\common\ValueObject::setIdlabel($drvApproved, $statusLabel);
		}
		$this->birthDate = $data['drv_dob'];
	}

	/** @var  \Drivers $drvModel 
	 * 
	 * @param \Drivers $drvModel
	 * @param int $drvPhone
	 * @return \Beans\Driver
	 */
	public static function setDataByModel($drvModel, $drvPhone = null, $hideDocDetails = false)
	{
		$obj		 = new Driver();
		$obj->id	 = (int) $drvModel->drv_id;
		$obj->code	 = $drvModel->drv_code;
		$obj->name	 = $drvModel->drv_name;

		$obj->rating		 = $drvModel->driverStats->drs_drv_overall_rating;
		$obj->joiningDate	 = $drvModel->drv_created;
		if($drvModel->drv_approved != null)
		{
			$statusLabel = \Drivers::getApproveStatusList($drvModel->drv_approved);
			$obj->status = \Beans\common\ValueObject::setIdlabel($drvModel->drv_approved, $statusLabel);
		}
		$cttId = \ContactProfile::getByEntityId($drvModel->drv_id, \UserInfo::TYPE_DRIVER);
		$obj->setDataByIdV1($cttId, $hideDocDetails);
		if($drvPhone != '')
		{
			$dataArr						 = [];
			$item							 = [];
			\Filter::parsePhoneNumber($drvPhone, $code, $number);
			$item['phn_phone_country_code']	 = $code;
			$item['phn_phone_no']			 = $number;
			$item['phn_full_number']		 = $code . $number;
			$dataArr[]						 = $item;
			$obj->phone						 = \Beans\contact\Phone::setList($dataArr);
		}
		return $obj;
	}

	public static function getList($dataArr, $tripId = null)
	{
		foreach($dataArr as $row)
		{
			$obj		 = new Driver();
			$row		 = (is_array($row)) ? \Filter::convertToObject($row) : $row;
			$obj->fillData($row, $tripId);
			$dataList[]	 = $obj;
		}
		return $dataList;
	}

	public function fillData($row, $tripId = null)
	{
		$this->id		 = (int) $row->drv_id;
		$this->code		 = $row->drv_code;
		$this->firstName = (!$row->ctt_first_name) ? $row->drv_name : $row->ctt_first_name;
		$this->lastName	 = $row->ctt_last_name;
		$this->dlNumber	 = $row->ctt_license_no;

		$this->approveStatus = (int) $row->drv_approved;
		if($row->drv_approved != null)
		{
			$statusLabel = \Drivers::getApproveStatusList($row->drv_approved);
			$this->status = \Beans\common\ValueObject::setIdlabel($row->drv_approved, $statusLabel);
		}
		$ratings		 = \DriverStats:: fetchRating($row->drv_id);
		$this->rating	 = round($ratings);
		if($row->ctt_id > 0)
		{
			$objPhn		 = new \Beans\contact\Phone();
			$this->phone = $objPhn->setByContactId($row->ctt_id);

			$objeml		 = new \Beans\contact\Email();
			$this->email = $objeml->setByContactId($row->ctt_id);
		}
		$cityObj = new \Beans\common\City();
		if($row->ctt_city != "")
		{
			$this->city = $cityObj->getById($row->ctt_city);
		}
	}

	public static function setDataForGNow($drvId, $phone)
	{
		$obj				 = new Driver();
		$obj->id			 = (int) $drvId;
		$dataArr			 = [];
		\Filter::parsePhoneNumber($phone, $code, $number);
		$dataArr['code']	 = $code;
		$dataArr['number']	 = $number;
		$obj->phone[]		 = \Beans\contact\Phone::setUserPhone($dataArr);
		return \Filter::removeNull($obj);
	}

	public function setByContact($cttId, $hideDetails = true)
	{
		$obj		 = new Driver();
		$data		 = \Drivers::getDefaultByContact($cttId);
		$obj->id	 = (int) $data['drv_id'];
		$obj->code	 = $data['drv_code'];
		$statusLabel = \Drivers::getApproveStatusList($data['drv_approved']);
		$obj->status = \Beans\common\ValueObject::setIdlabel($data['drv_approved'], $statusLabel);
		$obj->setDataByIdV1($cttId, $hideDetails);
		return \Filter::removeNull($obj);
	}

	public static function setByStatusByData($data)
	{
		$obj		 = new Driver();
		$obj->id	 = (int) $data['drv_id'];
		$obj->code	 = $data['drv_code'];
		$statusLabel = \Drivers::getApproveStatusList($data['drv_approved']);
		$obj->status = \Beans\common\ValueObject::setIdlabel($data['drv_approved'], $statusLabel);
		$obj->phone	 = \Beans\contact\Phone::setByNumber($data['drv_phone']);
		$obj->email	 = \Beans\contact\Email::setByValue($data['drv_email']);
		return \Filter::removeNull($obj);
	}

	public function setProfile($drvId)
	{
		$cttId			 = \ContactProfile::getByDrvId($drvId);
		$contactInfo	 = new \Beans\contact\Person();
		$contactInfo->setDataByIdV1($cttId);
		$this->contact	 = $contactInfo;
		if($drvId > 0)
		{
			$drvObj			 = new \Beans\Driver();
			$drvObj->setProfileDataV1($drvId);
			$this->driver	 = $drvObj;
		}
	}

	public function getDetails($data)
	{
		$driverId									 = $data->driver->id;
		$model										 = \Drivers::model()->findByPk($driverId);
		$data										 = $data->contact;
		$model->drvContact->ctt_first_name			 = $data->firstName;
		$model->drvContact->ctt_last_name			 = $data->lastName;
		$emlModel									 = \Beans\contact\Email::setEmailModel($data->email[0]);
		$model->drvContact->contactEmails			 = $emlModel;
		$phoneModel									 = \Beans\contact\Phone::setPhoneModel($data->phone[0]);
		$model->drvContact->contactPhones			 = $phoneModel;
		//$model->drvContact->ctt_preferred_language = $data->preferredLanguage->id;
		$model->drvContact->ctt_aadhaar_no			 = $data->aadhaar;
		$model->drvContact->ctt_voter_no			 = $data->voter;
		$model->drvContact->ctt_license_no			 = $data->dlNumber;
		$model->drvContact->ctt_license_exp_date	 = $data->dlExpiryDate;
		$model->drvContact->ctt_license_issue_date	 = $data->dlIssuingDate;
		$model->drvContact->ctt_dl_issue_authority	 = $data->dlIssuingState;
		$model->drv_dob								 = $data->dob;
		\Beans\common\Location::setLocationModel($model, $data->address);
		return $model;
	}

	public function setDocumentData($data)
	{
		$model->id			 = $data->drv_id;
		$model->docType		 = $data->doc_type;
		$model->docSubType	 = $data->doc_subtype;

		return $model;
	}

	/**
	 * 
	 * @param integer $drvId
	 * @param integer $phone
	 * @return boolean
	 */
	public static function setDataForOffer($drvId, $phone)
	{
		$obj	 = new Driver();
		/* @var $model \Drivers */
		$model	 = \Drivers::model()->findByPk($drvId);
		if(!$model)
		{
			return false;
		}
		$obj->id	 = (int) $model->drv_id;
		$obj->code	 = $model->drv_code;
		$rating		 = $model->driverStats->drs_drv_overall_rating;
		if($rating == null || $rating == 0)
		{
			$rating = 5;
		}
		$obj->rating		 = (float) $rating;
		$dataArr			 = [];
		$dataArr['number']	 = $phone;
		$obj->phone			 = \Beans\contact\Phone::setFullNumber($dataArr['number']);
		return \Filter::removeNull($obj);
	}

	public function setTransactionData($row)
	{
		$this->booking_id		 = $row['booking_id'];
		$this->drv_trans_date	= $row['drv_trans_date'];
		$this->drv_createdate	 = $row['drv_createdate'];
		$this->drv_bonus_amount	 = -1 * ($row['drv_bonus_amount']);
		$this->drv_remarks		 = $row['drv_remarks'];
		$this->adm_name			 = $row['adm_name'];
		$this->ledgerNames		 = $row['ledgerNames'];
		$this->openBalance		 = $row['openBalance'];
		$this->runningBalance	 = -1 * ($row['runningBalance']);
		//return $data;
	}

	public function getTransactionList($dataArr)
	{
		$data = 0;
		foreach ($dataArr as $row)
		{
			
			$obj	 = new \Beans\driver;
			$obj->setTransactionData($row);
			$this->dataList[]	 = $obj;
		}
	}
	
	
}
