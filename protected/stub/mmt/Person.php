<?php

namespace Stub\mmt;

class Person
{

	//public $firstName, $lastName;
	public $name;
	public $pincode, $address, $address1, $gender;
	public $email, $city, $fullName, $state, $country;
	public $primaryContact;
	public $alternateContact;
	public $phone_number, $country_code;

	public function getModel(\BookingUser $model = null)
	{

		/* @var $model BookingUser */
		if ($model == null)
		{
			$model = new \BookingUser();
		}
		$name					 = $this->name;
		$nameVar				 = explode(' ', $name, 2);
		$model->bkg_user_fname	 = $nameVar[0];
		$model->bkg_user_lname	 = $nameVar[1];

		$model->bkg_user_email		 = $this->email;
		$model->bkg_country_code	 = ($this->country_code == '' ? 91 : (int) $this->country_code);
		$model->bkg_contact_no		 = $this->phone_number;
		$model->bkg_alt_country_code = $this->alternateContact->code;
		$model->bkg_alt_contact_no	 = $this->alternateContact->number;
		return $model;
	}

}
