<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Dev
 * 
 * @property integer $id
 * @property string $fname
 * @property string $lname
 */

namespace Beans\common;

class User extends \Beans\contact\Contact
{

	/** @var \Users $model */
	public function setData($model)
	{
		$this->id		 = (int) $model->user_id;
		$this->firstName = $model->usr_name;
		$this->lastName	 = $model->usr_lname;
	}

	public function setDataByContact($userId, $cttId)
	{
		$data			 = \Contact::model()->getContactDetails($cttId);
		$this->id		 = (int) $userId;
		$this->firstName = $data['ctt_first_name'];
		$this->lastName	 = $data['ctt_last_name'];
		$objPhn			 = new \Beans\contact\Phone();
		$this->phone	 = $objPhn->setByContactId($cttId);

		$objEmail		 = new \Beans\contact\Email();
		$this->email	 = $objEmail->setByContactId($cttId);
		$prefLanguage	 = $data['ctt_preferred_language'] | 0;
		$langArr		 = \Contact::languageList($prefLanguage);

		$this->preferredLanguage = \Beans\common\ValueObject::setIdlabel($langArr['id'], $langArr['text'], $langArr['val']);
	}
	
	
	public function setAgreementData(\Vendors $vendorModel, \Contact $contactModel)
	{

		$this->vendorLavel		 = $vendorModel->vndContact->getName();
		$this->vnd_relation_tier = (int) $vendorModel->vnd_rel_tier;
		$this->vndCompany		 = $contactModel->ctt_business_name;
		$this->isBussiness		 = ($contactModel->ctt_user_type == '1' ? 0 : 1);
		$ObjUser			      = new \Beans\contact\Person();
		$ObjUser->setDataByIdV1($contactModel->ctt_id);
		$this->Person = $ObjUser;
		//$contactUser	         = \ContactProfile::getCodeByCttId($contactModel->ctt_id);
		$contactUserId	         = $contactUser['cr_is_consumer'];
		$this->setDataByContact($contactUserId,$contactModel->ctt_id);
		//$agreementModel			 = \VendorAgreement::model()->findByVndId($vendorModel->vnd_id);
		$this->$ObjOwner			 = new \Beans\contact\Person();
		$this->$ObjOwner->setDataByIdV1($contactModel->ctt_id);
		$basePath		 = \Yii::app()->params['fullAPIBaseURL'];
		$this->agreementUrl =  $basePath ."/admpnl/vendor/operatorAgreement?vendorId=".$vendorModel->vnd_id;
	}
	
	
	
	public function getStatusDetails($statusData)
	{
		
		$userInfo	 = \UserInfo::getInstance();
		$contactData = \ContactProfile::getEntitybyUserId($userInfo->userId);
		$obj->userTypes = \ContactProfile::getEntityListByData($contactData);
		if($statusData['vendorId']>0)
		{
			$obj->gnowNotificationStat = (int)\VendorPref::getGNowNotificationStatus($statusData['vendorId']);
		}
		
		$obj->vendorId = ($statusData['vendorId']>0?$statusData['vendorId']:null);
		$obj->driverId = ($statusData['driverId']>0?$statusData['driverId']:null);
		$obj->freeze = $statusData['is_freeze'];
		$obj->requiredData = \Beans\common\User::setRequiredData($statusData);
		
		return $obj;
	}
	
	
	
	public function setRequiredData($statusData)
	{
		
		foreach($statusData  as $key => $type)
		{
			
			$type	 = $key;
			#echo $type;
			switch ($type)
			{
				case "is_document":
				$arr [] =\Beans\common\User::isDocumentRequired($statusData);
				
				break;
				case "is_license":
				$arr [] =\Beans\common\User::isLicenseRequired($statusData);
				break;
				case "is_agreement":
				$arr [] =\Beans\common\User::isAgreementRequired($statusData);
				break;
				case "is_driver":
				$arr [] =\Beans\common\User::isDrvRequired($statusData);
				break;
				case "is_car":
				$arr [] =\Beans\common\User::isCabRequired($statusData);
				break;
				case "securityFlag":
				$arr [] =\Beans\common\User::isSecurityRequired($statusData);
				break;
				
			}
		}
		$arr = array_values(array_filter($arr));
		return $arr;
	}
	
	public function isDocumentRequired($statusData)
	{
		if($statusData['is_document'] == 0 && $statusData['vendorId']>0)
		{
		$data->type		 = array("AD","VT","LC","PN");
		$data->required	 = false;
		$data->message	 = "";
		}

		return $data;
	}
	public function isLicenseRequired($statusData)
	{
		if($statusData['is_license'] == 0 && $statusData['driverId']>0)
		{
		$data->type		 = array("DRVLC");
		$data->required	 = false;
		$data->message	 = "";
		}
		return $data;
	}
	public function isAgreementRequired($statusData)
	{
		if($statusData['is_agreement'] == 0 && $statusData['vendorId']>0)
		{
		$data->type		 = array("AG");
		$data->required	 = true;
		$data->message	 = "";
		}
		return $data;
	}
	public function isCabRequired($statusData)
	{
		if($statusData['is_car'] == 0 && $statusData['vendorId']>0)
		{
			$data->type		 = array("CAR");
			$data->required	 = false;
			$data->message	 = "";
		}
		return $data;
	}
	public function isDrvRequired($statusData)
	{
		
		if($statusData['is_driver'] == 0 && $statusData['vendorId']>0)
		{
			$data->type		 = array("DRV");
			$data->required	 = false;
			$data->message	 = "";
		}

		return $data;
	}
	
	public function isSecurityRequired($statusData )
	{
		if($statusData['securityFlag'] == 1 && $statusData['vendorId']>0)
		{
		$data->type		 = array("SA");
		$data->required	 = false;
		$data->message	 = $statusData['message'];
		}
		return $data;
	}
}
