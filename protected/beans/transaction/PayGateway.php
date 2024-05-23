<?php

namespace Beans\transaction;

/**
 * Description of PayGateway
 *  
 *
 * @author Deepak
 * @property string $key
 * @property string $secret  
 * @property integer $method
 * @property string $name
 * @property double $amount
 * @property string $orderId
 * @property string $refCode
 * @property string $description
 * @property string $image
 * @property string $pgData
 */
class PayGateway
{
	public $key;
	public $secret;
//
	public $method;
	public $name;
	public $amount;
	public $orderId;
	public $refCode;
	public $description;
	public $image;
//
	public $encData;
	public $pgData;

	public function setEncData($model)
	{

		if ($model->secret != "" && $model->key != "")
		{
			$MCryptSecurity	 = new \MCryptSecurity();
			$json			 = json_encode(['key' => $model->key, 'secret' => $model->secret]);
			$dataEncsecret	 = $MCryptSecurity->encrypt($json);
			$model->encData	 = base64_encode($dataEncsecret);
		}

		return $model;
	}

	public static function setResponseData(\PaymentRequest $payRequest, $pgData)
	{
		$obj		 = new \Beans\transaction\PayGateway();
		$obj->setByPaymentRequest($payRequest);
		$obj->pgData =  array_filter((array)$pgData)  ;
		return $obj;
	}

	public function setByPaymentRequest(\PaymentRequest $payRequest)
	{
		$this->description	 = $payRequest->description;
		$this->amount		 = (float) $payRequest->trans_amount;
		$this->name			 = $payRequest->name;
		$this->refCode		 = $payRequest->transaction_code;
		$this->method		 = (int) $payRequest->payment_type;
		$this->image		 = "https://www.gozocabs.com/images/gozo-white.svg";
	}

}
