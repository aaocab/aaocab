<?php

class Lazypay extends CComponent
{

	public $api_live = false;
	public $accessKey;
	public $secretKey;
	public $initiate_url;
	public $enquiry_url;
	public $refund_url;
	public $eligibility_url;
	public $view	 = 'lazypay';

	function __construct()
	{
		$this->init();
	}

	public function init()
	{
		$domain			 = 'https://sboxapi.lazypay.in/';
		$config			 = Yii::app()->params['lazypay'];
		$this->api_live	 = $config['api_live'];
		if ($this->api_live == true)
		{
			$domain = 'https://api.lazypay.in/';
		}
		$this->eligibility_url	 = $domain . 'api/lazypay/v2/payment/eligibility';
		$this->initiate_url		 = $domain . 'api/lazypay/v2/payment/initiate';
		$this->refund_url		 = $domain . 'api/lazypay/v0/refund';
		$this->enquiry_url		 = $domain . 'api/lazypay/v0/enquiry';
		$this->secretKey		 = $config['secretKey'];
		$this->accessKey		 = $config['accessKey'];
	}

	public function initiateRequest($payRequest)
	{
		$param_list		 = $this->getData($payRequest);
		$eligiblityStr	 = $this->checkEligibility($param_list);
		$eligiblityArr	 = json_decode($eligiblityStr, true);
		$responseArr	 = [];
		if ($eligiblityArr['eligibility'])
		{
			$valArr					 = "merchantAccessKey=" . $this->accessKey . "&transactionId=" . $param_list['txnid'] . "&amount=" . $param_list['amount'];
			$param_list['signature'] = $this->getSignature($valArr);
			$param_list['data']		 = json_encode(Yii::app()->lazypay->formatPaymentData($param_list));
			$response				 = $this->callApi($param_list, $this->initiate_url);
			$responseArr			 = json_decode($response, true);
		}
		else
		{
			$responseArr['TxId']	 = $param_list['txnid'];
			$responseArr['TxMsg']	 = $eligiblityArr['message'] . ". Eligibility failed.";
			$responseArr['txn_url']	 = YII::app()->createAbsoluteUrl('lazypay/response');
		}
		return $responseArr;
	}

	public function getData($payRequest)
	{
		$param_list['txnid']		 = $payRequest->transaction_code;
		$param_list['productinfo']	 = $payRequest->description;
		$param_list['amount']		 = $payRequest->trans_amount;
		$param_list['firstname']	 = $payRequest->name;
		$param_list['address']		 = $payRequest->billingAddress;
		$param_list['city']			 = $payRequest->city;
		$param_list['state']		 = $payRequest->state;
		$param_list['country']		 = $payRequest->country;
		$param_list['zipcode']		 = $payRequest->postal;
		$param_list['email']		 = $payRequest->email; //Email ID of customer
		$param_list['phone']		 = $payRequest->mobile;
		$param_list['returnUrl']	 = YII::app()->createAbsoluteUrl('lazypay/response');
		$param_list['notifyUrl']	 = YII::app()->createAbsoluteUrl('lazypay/response');
		$param_list['source']		 = 'Gozocabs';
		return $param_list;
	}

	public function checkEligibility($param_list)
	{

		$mobile					 = $param_list["phone"];
		$email					 = $param_list['email'];
		$orderAmount			 = $param_list['amount'];
		$valArr					 = $mobile . $email . $orderAmount . "INR";
		$signature				 = $this->getSignature($valArr);
		$reqData['signature']	 = $signature;
		$reqData['data']		 = json_encode(Yii::app()->lazypay->formatEligibilityData($param_list));
		$response				 = $this->callApi($reqData, $this->eligibility_url);

		$responseArr = json_decode($response, true);
		$data		 = json_encode([
			'message'		 => $responseArr['message'] . $responseArr['reason'],
			'eligibility'	 => $responseArr['txnEligibility']
		]);
		return $data;
	}

	public function formatEligibilityData($param_list)
	{


		$userDetails	 = [
			"mobile" => $param_list["phone"],
			"email"	 => $param_list['email'],
//			"firstName"	 => $fname,
//			"lastName"	 => $lname
		];
		$amount			 = [
			"value"		 => $param_list['amount'],
			"currency"	 => "INR"
		];
		$address		 = [
			"street1"	 => $param_list['address'],
			"city"		 => $param_list['city'],
			"state"		 => $param_list['state'],
			"country"	 => "IND",
			"zip"		 => $param_list['zipcode']
		];
		$productInfo[]	 = [
			"productId"		 => $param_list['productinfo'],
			"description"	 => "desc",
			"attributes"	 => ["size" => "32", "color" => "blue"],
			"imageUrl"		 => "www.google.com",
			"shippable"		 => true,
			"skus"			 => [[
			"skuId"		 => "sku1",
			"price"		 => 10,
			"attributes" => [
				"size"	 => "30",
				"color"	 => "32"
			]
				]]
		];
		$customParams	 = [
			"IPaddress"	 => Filter::getUserIP(),
			"DeviceInfo" => UserLog::model()->getDevice(),
		];
//    "previousTransactionCount": "10",
//    "onboardingdate": "20-05-2009",
//    "usersignupdetails": "Google",
//    "IPaddress": "10.10.10.10",
//    "UserAgent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36",
//    “DeviceInfo”: “DeviceName|DeviceModel|SystemName|SystemVersion|DeviceScreensize|identifierForVendor(UUID)|DeviceId”
//  }
		$valArr			 = [
			"userDetails"		 => $userDetails,
			"amount"			 => $amount,
			"source"			 => $param_list['source'],
//			"address"			 => $address,
			"productSkuDetails"	 => $productInfo,
			"customParams"		 => $customParams
		];

		return $valArr;   // = json_encode($valArr);
	}

	public function formatPaymentData($param_list)
	{

		$userDetails	 = [
			"mobile" => $param_list["phone"],
			"email"	 => $param_list['email'],
		];
		$address		 = [
			"street1"	 => $param_list['address'],
			"city"		 => $param_list['city'],
			"state"		 => $param_list['state'],
			"country"	 => "IND",
			"zip"		 => $param_list['zipcode']
		];
		$amount			 = [
			"value"		 => $param_list['amount'],
			"currency"	 => "INR"
		];
		$productInfo[]	 = [
			"productId"		 => $param_list['productinfo'],
			"description"	 => "desc",
			"attributes"	 => ["size" => "32", "color" => "blue"],
			"imageUrl"		 => "www.google.com",
			"shippable"		 => true,
			"skus"			 => [[
			"skuId"		 => "sku1",
			"price"		 => 10,
			"attributes" => [
				"size"	 => "30",
				"color"	 => "32"
			]
				]]
		];
		$customParams	 = [
			"IPaddress"	 => Filter::getUserIP(),
			"DeviceInfo" => UserLog::model()->getDevice(),
		];
		$valArr			 = [
			"userDetails"		 => $userDetails,
			"amount"			 => $amount,
			"merchantTxnId"		 => $param_list['txnid'],
			"returnUrl"			 => $param_list['returnUrl'],
			"notifyUrl"			 => $param_list['notifyUrl'],
			"source"			 => $param_list['source'],
			"isRedirectFlow"	 => true,
//			"address"			 => $address,
			"productSkuDetails"	 => $productInfo,
			"customParams"		 => $customParams
		];

		return $valArr;
	}

	function getSignature($all)
	{
		$secret_key	 = $this->secretKey;
		$hash		 = hash_hmac('sha1', $all, $secret_key, FALSE);
		return $hash;
	}

	public function parseResponse($responseArr)
	{
		$transCode = $responseArr['TxId'];

		$response	 = json_encode($responseArr);
		$status		 = 0;

		if ($responseArr['TxStatus'] == 'SUCCESS')
		{
			$status = 1;
			PaymentGateway::model()->verifyUserBySuccesTransaction($transCode);
		}
		else
		{
			$status = 2;
		}

		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_LAZYPAY;
		$payResponse->transaction_code	 = $transCode;
		$payResponse->response_code		 = $responseArr['pgRespCode'];
		$payResponse->payment_code		 = $responseArr['TxRefNo'];
		$payResponse->response			 = $response;
		$payResponse->message			 = $responseArr['TxMsg'];
		$payResponse->payment_status	 = $status;
		return $payResponse;
	}

	public function refund($transModel)
	{
		$amount						 = -1 * $transModel->apg_amount;
		$paramList					 = [];
		$refCode					 = PaymentGateway::model()->getCodebyid($transModel->apg_ref_id);
		$valArr						 = "merchantAccessKey=" . $this->accessKey . "&merchantTxnId=" . $refCode . "&amount=" . $amount . ".00";
		$param_list['merchantTxnId'] = $refCode;
		$param_list['amount']		 = $amount;
		$param_list['signature']	 = $this->getSignature($valArr);
		$param_list['data']			 = json_encode(Yii::app()->lazypay->formatRefundData($param_list));
		$response					 = $this->callApi($param_list, $this->refund_url);
		$responseArr				 = json_decode($response, true);

		$statusCode = 0;
		if ($responseArr['status'] == 'REFUND_SUCCESS')
		{
			$statusCode = 1;
		}
		else
		{
			$statusCode = 2;
		}
		$adtCode	 = $refCode;
		$resStatus	 = $responseArr['respMessage'];
		$txnid		 = $responseArr['lpTxnId'];
		$errorMsg	 = $responseArr['error'] . " " . $responseArr['message'];
		$respCode	 = $responseArr['errorCode'];

		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_LAZYPAY;
		$payResponse->transaction_code	 = $adtCode;
		$payResponse->response_code		 = $respCode;
		$payResponse->payment_code		 = $txnid;
		$payResponse->response			 = json_encode($responseArr);
		$payResponse->message			 = trim($resStatus . ' ' . $errorMsg);
		$payResponse->payment_status	 = $statusCode;
		return $payResponse;
	}

	public function formatRefundData($param_list)
	{
		$amount	 = $param_list['amount'] . ".00";
		$refArr	 = ["merchantTxnId"	 => $param_list['merchantTxnId'],
			"amount"		 => [
				"value"		 => "$amount",
				"currency"	 => "INR"
			]
		];
		return $refArr;
	}

	public function getTxnStatus($orderid)
	{
		$transBool		 = [
			'1'	 => 'false',
			'2'	 => 'true'
		];
		$refTimeStamp	 = '';
		$transModel		 = PaymentGateway::model()->getByCode($orderid);
		if ($transModel->apg_mode == 1)
		{
			$orderid		 = PaymentGateway::model()->getCodebyid($transModel->apg_ref_id);
			$refTimeStamp	 = date("Y-m-d H:i:s", strtotime($transModel->apg_date));
		}

		$transParam					 = [];
		$transParam['merchantTxnId'] = $orderid;
		$transParam['isSale']		 = $transBool[$transModel->apg_mode];
		$valArr						 = "merchantAccessKey=" . $this->accessKey . "&merchantTransactionId=" . $orderid;
		$signature					 = $this->getSignature($valArr);
		$resArr						 = $this->callStatusEnquiryAPI($transParam, $signature, $transModel);
		return $resArr;
	}

	function callApi($param_list, $url)
	{
		$signature	 = $param_list['signature'];
		$data		 = $param_list['data'];
		$accesskey	 = $this->accessKey;
		$curl		 = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL				 => "$url",
			CURLOPT_RETURNTRANSFER	 => true,
			CURLOPT_MAXREDIRS		 => 10,
			CURLOPT_TIMEOUT			 => 30,
			CURLOPT_CUSTOMREQUEST	 => "POST",
			CURLOPT_POSTFIELDS		 => "$data",
			CURLOPT_HTTPHEADER		 => array(
				"accesskey: $accesskey",
				"cache-control: no-cache",
				"content-type: application/json",
				"signature: $signature"
			),
		));

		$response	 = curl_exec($curl);
		$err		 = curl_error($curl);
		curl_close($curl);

		if ($err)
		{
			echo "cURL Error #:" . $err;
		}
		else
		{
			return $response;
		}
	}

	public function callStatusEnquiryAPI($transParam, $signature, $transModel)
	{
		$enqUrl = $this->enquiry_url;

		$fieldStr = '';
		foreach ($transParam as $key => $val)
		{
			$fieldStr .= '&' . $key . '=' . $val;
		}
		$fields		 = ltrim($fieldStr, '&');
		$url		 = $enqUrl . '?' . $fields;
		$accesskey	 = $this->accessKey;
		$curl		 = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL				 => "$url",
			CURLOPT_RETURNTRANSFER	 => true,
			CURLOPT_ENCODING		 => "",
			CURLOPT_MAXREDIRS		 => 10,
			CURLOPT_TIMEOUT			 => 30,
			CURLOPT_CUSTOMREQUEST	 => "GET",
			CURLOPT_HTTPHEADER		 => array(
				"accesskey: $accesskey",
				"cache-control: no-cache",
				"signature: $signature"
			),
		));

		$response	 = curl_exec($curl);
		$err		 = curl_error($curl);
		curl_close($curl);

		if ($err)
		{
			echo "cURL Error #:" . $err;
		}
		else
		{
			$responseArrVal = [];
			if ($transModel->apg_mode == 1)
			{
				$refTimeStamp	 = date("Y-m-d H:i:s", strtotime($transModel->apg_date));
				$datetime2		 = date_create($refTimeStamp);
				$responseArr	 = json_decode($response, true);
				foreach ($responseArr as $value)
				{
					$txnDateTime		 = date("Y-m-d H:i:s", strtotime($value['txnDateTime']));
					$datetime1			 = date_create($txnDateTime);
					$interval			 = date_diff($datetime1, $datetime2)->format('%i');
					if ($interval <= 4 && $interval >= 0 && ((-1 * $transModel->apg_amount) == $value['amount']) && $value['txnType']	 = "REFUND")
					{
						$responseArrVal = $value;
					}
				}
			}
			else
			{
				$responseArr				 = json_decode($response, true);
				if ($responseArr[0]['txnType']	 = 'SALE')
				{
					$responseArrVal = $responseArr[0];
				}
			}
			return $responseArrVal;
		}
	}

	public function getPaymentStatus($pgModel)
	{
		$transcode	 = $pgModel->apg_code;
		$responseArr = $this->getTxnStatus($transcode);
		$statusCode	 = 0;
		if ($responseArr['status'] == 'SUCCESS' || ($pgModel->apg_mode == 1 && $responseArr['status'] == 'REFUND_SUCCESS'))
		{
			$statusCode = 1;
		}
		if ($responseArr['status'] == 'FAIL' || $responseArr['status'] == 'CANCELLED' || $responseArr['status'] == 'IN_PROGRESS' || ($pgModel->apg_mode == 1 && $responseArr['status'] == 'REFUND_FAILED'))
		{
			$statusCode = 2;
		}

		$resStatus	 = $responseArr['status'];
		$txnid		 = $responseArr['lpTxnId'];
		$errorMsg	 = $responseArr['respMessage'];
		$respCode	 = (isset($responseArr['errorCode']) && $responseArr['errorCode'] != '') ? $responseArr['errorCode'] : '';

		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_LAZYPAY;
		$payResponse->transaction_code	 = $transcode;
		$payResponse->response_code		 = $respCode;
		$payResponse->payment_code		 = $txnid;
		$payResponse->response			 = json_encode($responseArr);
		$payResponse->message			 = trim($resStatus . ' ' . $errorMsg);
		$payResponse->payment_status	 = $statusCode;
		return $payResponse;
	}

}
