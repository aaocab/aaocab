<?php

namespace Stub\consumer;

/**
 * 
 * @property \Stub\common\Person $profile
 * @property \Stub\common\Platform $device
 */
class SignUpRequest extends \Stub\common\Auth
{

	public $referredCode;

	/** @var \Stub\common\Person $profile */
	public $profile;

	/** @var \Stub\common\Platform $device */
	public $device;

	/**
	 * @param \Users $model
	 * @return \Users
	 */
	public function getModel($model = null)
	{
		/** @var $model Users */
		if ($model == null)
		{
			$model = new \Users();
		}
		$model->usr_password		 = $this->password;
		$model->usr_referred_code	 = $this->referredCode;
		$model->usr_name			 = $this->profile->firstName;
		$model->usr_lname			 = $this->profile->lastName;
		$model->usr_email			 = $this->profile->email;
		$model->usr_country_code	 = $this->profile->primaryContact->code;
		$model->usr_mobile			 = ($this->profile->primaryContact->number != '') ? str_replace(' ', '', $this->profile->primaryContact->number) : $this->profile->primaryContact->number;
		$this->device->getAppToken();
		return $model;
	}

	/**
	 * 
	 * @param array $contactData
	 * @param array $emailData
	 * @param array $phoneData
	 * @param array $userData
	 * @return $this
	 */
	public function setModelData($contactData, $emailData, $phoneData, $userData = null)
	{
		$this->referredCode						 = $userData['usr_referred_code'];
		$this->password							 = $userData['new_password'];
		$this->repeatpassword					 = $userData['repeat_password'];
		$this->profile							 = new \Stub\common\Person();
		$this->profile->firstName				 = $contactData['ctt_first_name'];
		$this->profile->lastName				 = $contactData['ctt_last_name'];
		$this->profile->email					 = new \Stub\common\Email();
		$this->profile->email					 = $emailData['eml_email_address'];
		$this->profile->primaryEmail->value		 = $emailData['eml_email_address'];
		$this->profile->primaryContact			 = new \Stub\common\Phone();
		$this->profile->primaryContact->code	 = $phoneData['phn_phone_country_code'];
		$this->profile->primaryContact->number	 = $phoneData['phn_phone_no'];
		return $this;
	}

	/**
	 * 
	 * @return array
	 */
	public function getApptokenModel()
	{
		return $this->device->getAppToken();
	}

	public function setModel($model)
	{
		//$this->referredCode						 = $userData['usr_referred_code'];
		$password								 = md5(srand(microtime()));
		$this->password							 = $password;
		$this->repeatpassword					 = $password;
		$this->profile->firstName				 = $model->bkg_user_fname;
		$this->profile->lastName				 = $model->bkg_user_lname;
		$this->profile->email					 = $model->bkg_user_email;
		$this->profile->primaryContact->code	 = $model->bkg_country_code;
		$this->profile->primaryContact->number	 = $model->bkg_contact_no;
		return $this;
	}

	/** 
	 * 
	 * @param \Users $model
	 * @return boolean|$this
	 */
	public function setData(\Users $model)
	{
		if ($model == null)
		{
			return false;
		}
		$this->referredCode						 = $model->usr_refer_code;
		$this->password							 = $model->usr_password;
		$this->repeatpassword					 = $model->usr_password;
		$this->profile							 = new \Stub\common\Person();
		$this->profile->firstName				 = $model->usr_name;
		$this->profile->lastName				 = $model->usr_lname;
		$this->profile->email					 = new \Stub\common\Email();
		$this->profile->email					 = $model->usr_email;
		$this->profile->primaryEmail->value		 = $model->usr_email;
		$this->profile->primaryContact			 = new \Stub\common\Phone();
		$this->profile->primaryContact->code	 = $model->usr_country_code;
		$this->profile->primaryContact->number	 = $model->usr_mobile;
		return $this;
	}

	

}
