<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace Beans\operator\hornok;

class Hold
{
	public $orderReferenceNumber;
	public $eventType;
	public $vendor_id;

	/** @var \Beans\booking\Route[] $routes */
	public $routes;
	public $quotedDistance;
	public $quotedTime;

	/** @var \Beans\common\CabCategory $cabType */
	public $cabType;

	/** @var \Beans\booking\Fare $fare */
	public $fare;

	/** @var common\ValueObject $tripType */
	public $tripType;

	/** @var \Beans\booking\Tags[] $tags */
	public $tags;

	/** @var \Beans\booking\additionalInfo[] $trackEvents */
	public $additionalInfo;

	/** @var booking\Preferences $preferences */
	public $preferences;

	/** @var \Beans\Vendor $vendor */
	public $vendor;

	public static function getInstance($bkgId)
	{
		$obj = new static();
		$obj->setParams($bkgId);	
		return $obj;
	}

	public function setParams($bkgId)
	{
		$model						 = \Booking::model()->findByPk($bkgId);
		$this->eventType			 = "hold";
		$this->orderReferenceNumber	 = $model->bkg_booking_id;
		$this->vendor_id			 = 'HOKGOZOVL09';
		$this->routes				 = \Beans\booking\Route::setDataByBooking($model->bkg_id);
		$this->quotedDistance		 = (int) $model->bkg_trip_distance;
		$this->quotedTime			 = (int) $model->bkg_trip_duration;
		$this->cabType				 = \Beans\common\CabCategory::setSkuId($model);
		if($model->bkgInvoice->bkg_vendor_amount == $model->bkgBcb->bcb_vendor_amount)
		{
			$this->fare				 = \Beans\booking\Fare::getModel($model->bkgInvoice, $model->bkg_status);
		}
		else{
			$this->fare				 = \Beans\booking\Fare::getUpdateModel($model->bkgInvoice, $model->bkg_status, $model->bkgBcb);
		}
		$this->tripType				 = \Beans\common\ValueObject::getTypeData($model->bkg_booking_type);
		//$this->tags					 = \Beans\booking\Tags::setByModel($model);
		$this->additionalInfo		 = \Beans\booking\AdditionalInfo::setByAddInfoModel($model->bkgAddInfo);
		$this->preferences			 = \Beans\booking\Preferences::setByModelHornOk($model);
		return $this;
	}
}