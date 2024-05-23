<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Beans\operator\hornok;

class Update
{
	
	public $orderReferenceNumber;
	public $eventType;
	public $vendor_id;
	public $trip_id;

	/** @var \Beans\contact\Person $traveller */
	public $traveller;
	
	/** @var \Beans\booking\Fare $fare */
	public $fare;





	/**
	 * 
	 * @param type $bkgId
	 * @return static
	 */
	public static function getInstance($bkgId)
	{
		$obj = new static();
		$obj->setParams($bkgId);	
		return $obj;
	}
	
	/**
	 * 
	 * @param type $bkgId
	 * @return $this
	 */
	public function setParams($bkgId)
	{
		$model						 = \Booking::model()->findByPk($bkgId);
		$this->eventType			 = "update";
		$this->orderReferenceNumber	 = $model->bkg_booking_id;
		$this->vendor_id			 = 'HOKGOZOVL09';
		$this->trip_id				 = $model->bkgBcb->bcb_vendor_ref_code;
		$this->traveller             = \Beans\contact\Person::setTravellerInfoByModel($model->bkgUserInfo);
		$this->fare					 = \Beans\booking\Fare::getModel($model->bkgInvoice, $model->bkg_status);
		return $this;
	}
}