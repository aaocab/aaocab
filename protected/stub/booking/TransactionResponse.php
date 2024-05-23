<?php

namespace Stub\booking;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TransactionResponse
{

    public $bookingId;
	public $refId;
	public $paymentType; 

	/** @var \Stub\common\BillingDetails $billing */
    public $billing;

    /**
     * This function is used for setting booking details	  
     * @param type $model = Booking Model
     * @param type $type = Return type
     */
    public function setData(\Booking $model, $checkSumData)
    {
        $this->bookingId = $model->bkg_booking_id;

        $this->billing = new \Stub\common\BillingDetails();
        $this->billing->setData($model->bkgUserInfo);

        $this->transactionDetails = new \Stub\common\PaymentGateway();
        $this->transactionDetails->setData($checkSumData);
        return $this;
    }
	
	public function setModel($referenceId, $paymentType, $checkSumData, \Booking $model = null)
	{
		$this->refId		 = $referenceId;
		$this->paymentType	 = $paymentType;

		$this->billing = new \Stub\common\BillingDetails();
		$this->billing->setData($model->bkgUserInfo);
		//$this->billing->setData($model);

		$this->gatewayDetail = new \Stub\common\PaymentGateway();
		$this->gatewayDetail->setData($checkSumData);
		return $this;
	}

}
