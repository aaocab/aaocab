<?php

namespace Stub\common;

/**
 * Description of PaymentGateway
 *
 * @author Dev
 */
class PaymentGateway
{

	public $key;
	public $txnId;
	public $merchantId;
	public $serviceProvider;
	public $successUrl;
	public $failureUrl;
	public $checksum;
	public $description;
	public $action;
	public $method;
	public $amount;

	public $name;
	public $secret;
	public $image;
	public $currency;
	public $paymentCapture;
	public $orderId;
	public $encData;

	public function setData($model)
	{
		$this->key				 = $model->key;
		$this->txnId			 = (int) $model->txnid;
		$this->merchantId		 = (int) $model->merchant_id;
		$this->serviceProvider	 = $model->service_provider;
		$this->amount			 = strval($model->amount);
		$this->successUrl		 = $model->surl;
		$this->failureUrl		 = $model->furl;
		$this->checksum			 = $model->hash;
		$this->description		 = $model->productinfo;
		$this->action			 = $model->action;
		$this->method			 = $model->method;
		$this->orderId           = $model->order_id; // order_id
		$this->secret            = $model->secret;
		if($model->secret!="" && $model->key!="")
		{
            $MCryptSecurity = new \MCryptSecurity();
			$json = json_encode(['key'=>$model->key,'secret'=>$model->secret]);
			$dataEncsecret = $MCryptSecurity->encrypt($json);
			$this->encData = base64_encode($dataEncsecret);
		}
		$this->name              = "aaocab";
		$this->image             = "http://www.aaocab.com/images/gozo-white-cabs.svg";
		$this->currency          = $model->currency;
		$this->paymentCapture    = 1;
		$this->email             = (!$model->email)?"":$model->email;
		$this->contact           = (!$model->contact)?"":$model->contact;
		return $this;
	}

	public function setRazorPayData($model)
	{
		$this->key   = $model->key;
		$this->orderId = $model->order_id; // order_id
		$this->secret  = $model->secret;
		$this->name = "aaocab";
		$this->description		 = $model->productinfo;
		$this->image             = "http://www.aaocab.com/images/gozo-white.svg";
		$this->currency          = $model->currency;
		$this->amount			 = $model->amount;
		$this->paymentCapture    = 1;
		$this->email             = (!$model->email)?"":$model->email;
		$this->contact           = (!$model->contact)?"":$model->contact;
		return $this;
	}

}


