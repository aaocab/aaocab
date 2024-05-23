<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\consumer;

class ProfiledetailsResponse extends \Stub\common\Consumer
{

	/**
	 * 
	 * @param \Users $model
	 */
	public function setModel(\Users $model)
	{
		$this->setData($model);
	}

	/**
	 * 
	 * @param \Users $model
	 * @param integer $contactId
	 * @return boolean
	 */
	public function setModelData(\Users $model, $cttId = 0)
	{
		if (!$model)
		{
			return false;
		}
		if ($model->user_id > 0)
		{
			$this->id = (int) $model->user_id;
		}
		if ($cttId > 0)
		{
			/** @var $emailModel \ContactEmail  */
			$emailModel	 = \ContactEmail::model()->findByContactID($cttId);
			/** @var $phoneModel \ContactPhone  */
			$phoneModel	 = \ContactPhone::model()->findByContactID($cttId);
		}
		$emailAddress	 = ($emailModel[0]->eml_email_address != '') ? $emailModel[0]->eml_email_address : $model->usr_email;
		$phoneNumber	 = ($phoneModel[0]->phn_phone_no != '') ? $phoneModel[0]->phn_phone_no : $model->usr_mobile;
		$phoneCode		 = ($phoneModel[0]->phn_phone_country_code != '') ? $phoneModel[0]->phn_phone_country_code : $model->usr_country_code;

		$this->refCode							 = $model->usr_refer_code;
		$this->profile->firstName				 = $model->usr_name;
		$this->profile->lastName				 = $model->usr_lname;
		$this->profile->email					 = $emailAddress;
		$this->profile->pincode					 = ($model->usr_zip > 0) ? (int) $model->usr_zip : null;
		$this->profile->country					 = ($model->usr_country > 0) ? (int) $model->usr_country : null;
		$this->profile->state					 = ($model->usr_state > 0) ? (int) $model->usr_state : null;
		$this->profile->address					 = $model->usr_address1;
		$this->profile->gender					 = ($model->usr_gender > 0) ? (int) $model->usr_gender : null;
		$this->profile->userProfilePic			 = \Users::getImageUrl($model->usr_profile_pic);
		$this->profile->primaryContact->code	 = $phoneCode;
		$this->profile->primaryContact->number	 = $phoneNumber;
	}

}
