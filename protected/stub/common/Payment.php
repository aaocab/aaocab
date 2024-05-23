<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;

/**
 * Description of Payment
 *
 * @author Roy
 * @property Promotions $promoDetails
 * @property BillingDetails $billing
 * @property PaymentGateway $gatewayDetails
 */
class Payment
{

	//put your code here

	public $refId;
	public $paymentType;
	public $amount;
	public $method;
	public $isDebug;

	/** @var BillingDetails $billing */
	public $billing;

	/** @var PaymentGateway $gatewayDetail */
	public $gatewayDetail;

	/** @var Promotions $promoDetails */
	public $promoDetails;

	public function getModel($model = null)
	{
		/** @var $model PaymentGateway */
		if ($model == null)
		{
			$model = new \PaymentGateway();
		}
		if ($this->paymentType == 1)
		{
			$model->apg_booking_id = (int) $this->refId;
		}
		$model->apg_trans_ref_id	 = (int) $this->refId;
		$model->apg_acc_trans_type	 = $this->paymentType;
		$model->apg_ptp_id			 = (int) $this->method;
		$model->apg_amount			 = (float) $this->amount;
		return $model;
	}

	/**
	 * 
	 * @return type
	 */
	public function getBillingData($modelType = 1)
	{
		switch ($modelType)
		{
			case 1:
				$model	 = $this->billing->getData();
				break;
			case 9:
				$model	 = $this->billing->getVoucherData();
				break;
		}
		return $model;
	}

	/**
	 * 
	 * @param integer $referenceId
	 * @param integer $paymentType
	 * @param string $checkSumData
	 * @param \BookingUser $model
	 * @param \VoucherOrder $vmodel
	 * @return $this
	 */
	public function setModel($referenceId, $paymentType, $checkSumData, \BookingUser $model = null, \VoucherOrder $vmodel = null)
	{

		$this->refId		 = $referenceId;
		$config				 = \Yii::app()->params['payu'];
		$this->isDebug		 = ($config['api_live']) ? false : true;
		$this->paymentType	 = $paymentType;

		if ($model)
		{
			$this->billing = new \Stub\common\BillingDetails();
			$this->billing->setData($model);
		}

		if ($vmodel)
		{
			$this->billing = new \Stub\common\BillingDetails();
			$this->billing->setVoucherData($vmodel);
		}

		$this->gatewayDetail = new \Stub\common\PaymentGateway();
		$this->gatewayDetail->setData($checkSumData);

		return $this;
	}

	/**
	 * 
	 * @param integer $referenceId
	 * @param integer $paymentType
	 * @param integer $method
	 * @param integer $amount
	 * @param \BookingUser $modelUser
	 * @param \BookingInvoice $modelInvoice
	 * @return boolean|\Stub\common\Payment
	 */
	public function setData($referenceId, $paymentType = 1, $method = 21, $amount, \BookingUser $modelUser = null, \BookingInvoice $modelInvoice = null)
	{

		if ($referenceId > 0 && $amount > 0 && !$modelInvoice->bivBkg)
		{
			return false;
		}
		//$obj				 = new Payment();
		$this->refId		 = $referenceId;
		$this->paymentType	 = $paymentType;
		$this->method		 = $method;
		$this->amount		 = $amount;
		if ($modelUser->bkg_user_id > 0)
		{
			$this->billing = new \Stub\common\BillingDetails();
			$this->billing->setInfo($modelUser);
		}
		if ($modelInvoice->biv_id > 0)
		{
			$this->promoDetails = new \Stub\common\Promotions();
			$this->promoDetails->populateData($modelInvoice);
		}

		return $this;
	}

}
