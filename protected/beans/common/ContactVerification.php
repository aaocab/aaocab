<?php

/**
 * Description of ContactVerification 
 * 
 * @property string $type
 * @property string $value
 * @property string $otp
 * @property string $otpValidTill
 * @property integer $otpRetry
 * @property string $otpLastSent
 * @property integer $status
 * @property string $captcha
 * @property integer $isSendSMS
 * 
 */

namespace Beans\common;

class ContactVerification
{

	const TYPE_PHONE	 = 2;
	const TYPE_EMAIL	 = 1;

	public $type;
	public $value;
	public $otp;
	public $otpValidTill;
	public $otpRetry	 = 0;
	public $otpLastSent;
	public $status		 = 0;
	public $captcha;
	public $isSendSMS	 = 0;

	public function __construct($type, $value)
	{
		$this->type	 = $type;
		$this->value = $value;
	}

	public static function create($type, $value)
	{
		$obj = new ContactVerification($type, $value);
		return $obj;
	}

	public function verifyOTP($otp)
	{
		$success = false;
		if ($this->otp == $otp && $this->isOTPActive())
		{
			$success		 = true;
			$this->status	 = 1;
		}
		else
		{
			$this->otpRetry++;
		}
		return $success;
	}

	public function isOTPActive()
	{
		return (time() < $this->otpValidTill);
	}

	public function setVerified()
	{
		$this->status = 1;
	}

	public function isVerified()
	{
		return ($this->status == 1);
	}

	/** @return \ContactPhone */
	public function getContactPhoneModel()
	{
		$phnModel = new \ContactPhone();

		\Filter::parsePhoneNumber($this->value, $code, $number);
		$phnModel->phn_phone_country_code	 = $code;
		$phnModel->phn_phone_no				 = $number;
		$phnModel->phn_is_verified			 = $this->status;
		return $phnModel;
	}

	/** @return \ContactEmail */
	public function getContactEmailModel()
	{
		$emlModel					 = new \ContactEmail();
		$emlModel->eml_email_address = $this->value;
		$emlModel->eml_is_verified	 = $this->status;
		return $emlModel;
	}

	/** @return \ContactPhone|\ContactEmail */
	public function getModel()
	{
		$model = null;
		if ($this->type == ContactVerification::TYPE_EMAIL)
		{
			$model = $this->getContactEmailModel();
		}

		if ($this->type == ContactVerification::TYPE_PHONE)
		{
			$model = $this->getContactPhoneModel();
		}

		return $model;
	}

	/** @return \ContactPhone|\ContactEmail */
	public static function getContact($type, $value)
	{
		$obj	 = new \Beans\common\ContactVerification($type, $value);
		$model	 = null;
		if ($type == ContactVerification::TYPE_EMAIL)
		{
			$model = $obj->getContactEmailModel();
		}

		if ($type == ContactVerification::TYPE_PHONE)
		{
			$model = $obj->getContactPhoneModel();
		}

		return $model;
	}

}
