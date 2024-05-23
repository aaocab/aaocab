<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Beans\transaction;

/**
 * Description of Payment
 *
 * @author Deepak
 * @property integer $refId
 * @property integer $paymentType
 * @property float $amount
 * @property integer $method
 * 
 */
class Payment
{

	//put your code here

	public $refId; //entityId
	public $paymentType; //ledgerType 
	public $amount; //Amount
	public $method; //PaymentType id

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
		$model->apg_acc_trans_type	 = (int) $this->paymentType;
		$model->apg_ptp_id			 = (int) $this->method;
		$model->apg_amount			 = (float) $this->amount;
		return $model;
	}

}
