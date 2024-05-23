<?php
namespace Stub\consumer;
class ProfileUpdateRequest
{
	
    /** @var \Stub\common\Person $profile */
    public $profile;
    
	public function getModel()
	{	
		/* @var $model Users */
		$model = new \Users();
        $model->usr_name             = $this->profile->firstName;
        $model->usr_lname            = $this->profile->lastName;
        $model->usr_gender           = $this->profile->gender;
        $model->usr_address1         = $this->profile->address;
        $model->usr_zip              = (int)$this->profile->pincode;
        $model->usr_country_code     = (int)$this->profile->primaryContact->code;
        $model->usr_mobile           = (int)$this->profile->primaryContact->number;
        $model->usr_state            = (int)$this->profile->state;
        $model->usr_country          = (int)$this->profile->country;
        
		return $model;
	}
}