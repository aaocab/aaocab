<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Beans\operator\hornok;

class Cancel
{
	
	public $orderReferenceNumber;
	public $eventType;
	public $reason;
	public $vendor_id;
	public $trip_id;




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
		$this->eventType			 = "cancel";
		$this->orderReferenceNumber	 = $model->bkg_booking_id;
		$this->vendor_id			 = 'HOKGOZOVL09';
		$this->trip_id				 = $model->bkgBcb->bcb_vendor_ref_code;
		$reason					     = ($model->bkg_cancel_id !='')? \CancelReasons::getReasonById($model->bkg_cancel_id) : '';
		$this->reason				 = $reason;
		return $this;
	}
}