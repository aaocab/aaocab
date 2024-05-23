<?php

class BankConnect extends CComponent
{

	public $api_live = false;
	public $api_key;
	public $api_secret;
	public $status_query_url;
	public $txn_url;
	public $view	 = 'bkonnect';
	public $layer_js_url;
	public $layer_host;

	public function init()
	{
//$this->api_live  = true; 
		$this->layer_host	 = 'http://localhost:83';
		$this->api_key		 = '6e0c1780-b0b8-11ea-85d4-dd6f6c6fd9a4';
		$this->api_secret	 = 'e7a9c74f91c97ba0eab828125d94e9486a1725d8';
		$this->layer_js_url	 = 'https://sandbox-payments.open.money/layer';


		$domain = 'https://sandbox-icp-api.bankopen.co/';


		if ($this->api_live == true)
		{
			$this->api_key		 = '8ef83d20-a63e-11ea-a753-333deb719c25';
			$this->api_secret	 = '837c0d8b5c6817848931d0525e21b9d684724173';
			$domain				 = 'https://icp-api.bankopen.co/';
			$this->layer_js_url	 = 'https://payments.open.money/layer';
		}


		$this->txn_url = $domain . '/api/payment_token';
	}

	public function getAuthToken()
	{
		$accessKey	 = $this->api_key;
		$secretKey	 = $this->api_secret;
		return $accessKey . ':' . $secretKey;
	}

	public function initiateRequest($payRequest)
	{
		$param_list = array();




		$param_list['mtx']				 = $payRequest->transaction_code;
		$param_list['amount']			 = $payRequest->trans_amount;
		$param_list['email_id']			 = $payRequest->email; //Email ID of customer
		$param_list['contact_number']	 = $payRequest->mobile;
		$param_list['currency']			 = 'INR';
		$param_list['udf']				 = json_encode(['transcode' => $payRequest->transaction_code]);
		$url							 = $this->txn_url;
		$response						 = $this->callAPI($param_list, $url);

//		$response						 = '{
//  "amount": "10.00",
//  "currency": "INR",
//  "mtx": "200623044916",
//  "attempts": 0,
//  "id": "sb_pt_BRnT1XhNFvGSwo8",
//  "entity": "payment_token",
//  "status": "created",
//  "customer": {
//    "contact_number": "9876543210",
//    "email_id": "deepak@gmail.com",
//    "id": "sb_cs_BRnTynnnKvSvki4",
//    "entity": "customer"
//  }
//}';

		$respObj = json_decode($response);
		if ($respObj->status == 'created')
		{
			$transcode = $respObj->udf->transcode;
			return ['success' => true, 'data' => ['token' => $respObj->id, "accesskey" => $this->api_key, 'transcode' => $transcode]];
		}
//		echo $response;
		exit;
	}

	public function parseResponse($postData)
	{
		$payResponse = $this->getTxnStatus($postData['payment_token_id']);
		return $payResponse;
	}

	public function getTxnStatus($ptoken)
	{
		$url	 = $this->txn_url;
		$auth	 = $this->getAuthToken();
		$curl	 = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL				 => $url . "/$ptoken/payment",
			CURLOPT_RETURNTRANSFER	 => true,
			CURLOPT_ENCODING		 => "",
			CURLOPT_MAXREDIRS		 => 10,
			CURLOPT_TIMEOUT			 => 0,
			CURLOPT_FOLLOWLOCATION	 => true,
			CURLOPT_HTTP_VERSION	 => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST	 => "GET",
			CURLOPT_HTTPHEADER		 => array(
				"authorization: Bearer $auth"
			),
		));

		$response = curl_exec($curl);
		Logger::create('getTxnStatus : ' . $response, CLogger::LEVEL_TRACE);

		curl_close($curl);


		$postData = json_decode($response, true);

		$status = $postData['status'];

		$message	 = 'Pending';
		$respcode	 = 202;
		if ($status == 'captured')
		{
			$statusCode	 = 1;
			$message	 = 'Payment Successful';
			$respcode	 = 200;
		}
		if ($status == 'cancelled')
		{
			$statusCode	 = 2;
			$message	 = 'Payment Failed';
			$respcode	 = 400;
		}
		Logger::create('parse response : ' . json_encode($postData), CLogger::LEVEL_TRACE);

		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_BANKCONNECT;
		$payResponse->transaction_code	 = $postData['mtx'];
		$payResponse->response_code		 = $respcode;
		$payResponse->payment_code		 = $postData['payment_token']['id'];
		$payResponse->response			 = $response;
		$payResponse->message			 = $message;
		$payResponse->payment_status	 = $statusCode;
		return $payResponse;
	}

	public function callAPI($paramList, $url)
	{
		$auth	 = $this->getAuthToken();
		$curl	 = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL				 => $url,
			CURLOPT_RETURNTRANSFER	 => true,
			CURLOPT_ENCODING		 => "",
			CURLOPT_MAXREDIRS		 => 10,
			CURLOPT_TIMEOUT			 => 0,
			CURLOPT_FOLLOWLOCATION	 => true,
			CURLOPT_HTTP_VERSION	 => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST	 => "POST",
			CURLOPT_POSTFIELDS		 => json_encode($paramList),
			CURLOPT_HTTPHEADER		 => array(
				"Accept: */*",
				"Authorization: Bearer $auth",
				"Content-Type: application/json"
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

}
