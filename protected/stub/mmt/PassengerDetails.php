<?php

namespace Stub\mmt;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PassengerDetails
{
    public $name;
    public $mobile;
    public $email;

	/**
	 * This function is used to get passenger details	  
	 * @param Booking $model
     * @return [object]
	 */
	public function getData($model)
	{
		$this->name   = $model->bkgUserInfo->bkg_user_fname;
        $this->mobile = $model->bkgUserInfo->bkg_contact_no;
        $this->email  = $model->bkgUserInfo->bkg_user_email;
    }
}
