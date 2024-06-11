<?php

namespace Stub\common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Consumer
{

	public $id;
	public $approveStatus;
	public $userProfilePic;
	public $address1;
	public $refCode;

	/** @var \Stub\common\Person $profile */
	public $profile;

	/** @var \Stub\common\Phone $primaryContact  */
	public $primaryContact;

	/**
	 *
	 * @param \Users $model
	 * @return \Users
	 */
	public function getData(\Users $model = null)
	{
		if ($model == null)
		{
			$model = new \Users();
		}
		$model->usr_name		 = $this->profile->firstName;
		$model->usr_lname		 = $this->profile->lastName;
		$model->usr_gender		 = $this->profile->gender;
		$model->usr_address1	 = $this->profile->address;
		$model->usr_zip			 = (int) $this->profile->pincode;
		$model->usr_country_code = (int) $this->profile->primaryContact->code;
		$model->usr_mobile		 = (int) $this->profile->primaryContact->number;
		$model->usr_state		 = (int) $this->profile->state;
		$model->usr_country		 = (int) $this->profile->country;
		return $model;
	}

	/**
	 *
	 * @param \Users $model
	 */
	public function setData(\Users $model)
	{
		if (!$model)
		{
			return false;
		}
		if ($model->user_id > 0)
		{
			$this->id = (int) $model->user_id;
		}
		$this->refCode							 = $model->usr_refer_code;
		$this->profile->firstName				 = $model->usr_name;
		$this->profile->lastName				 = $model->usr_lname;
		$this->profile->email					 = $model->usr_email;
		$this->profile->pincode					 = ($model->usr_zip > 0) ? (int) $model->usr_zip : null;
		$this->profile->country					 = ($model->usr_country > 0) ? (int) $model->usr_country : null;
		$this->profile->state					 = ($model->usr_state > 0) ? (int) $model->usr_state : null;
		$this->profile->address					 = $model->usr_address1;
		$this->profile->gender					 = ($model->usr_gender > 0) ? (int) $model->usr_gender : null;
		$this->profile->userProfilePic			 = \Users::getImageUrl($model->usr_profile_pic);
		$this->profile->primaryContact->code	 = ($model->usr_mobile != '') ? (int) $model->usr_country_code : null;
		$this->profile->primaryContact->number	 = ($model->usr_mobile != '') ? (int) $model->usr_mobile : null;
	}

	/**
	 * 
	 * @param Users $model
	 * @return boolean|$this
	 */
	public function setDataSet($model)
	{

		if (!$model)
		{
			return false;
		}
		if ($model->user_id > 0)
		{
			$this->id = (int) $model->user_id;
		}
		$contactId = \ContactProfile::getByEntityId($model->user_id);
		if (!$contactId)
		{
			return false;
		}
		$this->id						 = (int) $model->user_id;
		$this->refCode					 = $model->usr_refer_code;
		$this->profile->pincode			 = (int) $model->usr_zip;
		$this->profile->country			 = $model->usr_country;
		$this->profile->state			 = $model->usr_state;
		$this->profile->gender			 = $model->usr_gender;
		//$this->profile->userProfilePic	 = \Users::getImageUrl($model->usr_profile_pic);


		/* @var $cttModel \Contact */
		$cttModel	 = \Contact::model()->findByPk($contactId);
		if (!$cttModel)
		{
			return false;
		}
		$path = ($cttModel->ctt_profile_path!='') ? $cttModel->ctt_profile_path : $model->usr_profile_pic;
		$this->profile->userProfilePic = \Yii::app()->params['fullAPPBaseURL'] . \AttachmentProcessing::ImagePath($path);

		$this->profile->firstName	 = $cttModel->ctt_first_name;
		$this->profile->lastName	 = $cttModel->ctt_last_name;
		$this->profile->address		 = $cttModel->ctt_address;
		if ($contactId > 0)
		{
			/** @var $emailModel \ContactEmail  */
			$emailModel		 = \ContactEmail::model()->findByContactID($contactId);
			/** @var $phoneModel \ContactPhone  */
			$phoneModel		 = \ContactPhone::model()->findByContactID($contactId);
			$emailAddress	 = $emailModel[0]->eml_email_address;
			$phoneNumber	 = $phoneModel[0]->phn_phone_no;
			$phoneCode		 = $phoneModel[0]->phn_phone_country_code;
		}
		$this->profile->email					 = $emailAddress;
		$this->profile->primaryContact->code	 = ($phoneCode != '' && $phoneNumber != '') ? $phoneCode : 91;
		$this->profile->primaryContact->number	 = $phoneNumber;
		return $this;
	}

}
