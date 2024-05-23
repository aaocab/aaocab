<?php

namespace Stub\consumer;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AdditionalRequest
{    
    /** @var \Stub\common\AdditionalInfo $addtionalInfo */
    public $additionalInfo; 
	public $sendEmail;
	public $sendSms;
	public $bookingId;

	public function getData(\Booking $model = null)
	{
		if ($model == null)
		{
			$model = \Booking::getNewInstance();
		}		
		$model->bkgAddInfo               = $this->additionalInfo->getModel();
		$model->bkg_id	                 = $this->bookingId;
		$model->bkgPref->bkg_send_email	 = (int) $this->sendEmail;
		$model->bkgPref->bkg_send_sms	 = (int) $this->sendSms;
		return $model;
	}
}
