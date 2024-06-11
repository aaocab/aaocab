<?php

class EPayLater extends CComponent
{

	public $api_live = false;
	public $enckey;
	public $IV;
	public $apikey;
	public $initiate_url;
	public $txn_url;
	public $refund_url;
	public $mCode;
	public $view	 = 'epaylater';

	function __construct()
	{
		$this->init();
	}

	public function init()
	{
		$domain1		 = 'https://api-sandbox.epaylater.in:443/';
		$domain			 = 'https://payment-sandbox.epaylater.in/';
		$config			 = Yii::app()->params['epaylater'];
		$this->api_live	 = $config['api_live'];
		if ($this->api_live == true)
		{
			$domain1 = 'https://api1.epaylater.in:443/';
			$domain	 = 'https://payment.epaylater.in/';
		}
		$this->initiate_url	 = $domain . 'web/process-transaction';
		$this->txn_url		 = $domain1 . 'transaction/v2';
		$this->enckey		 = $config['aes_key'];
		$this->IV			 = $config['iv'];
		$this->mCode		 = $config['m_code'];
		$this->apikey		 = $config['apikey'];
	}

	public function initiateRequest($payRequest)
	{
		$param_list				 = [];
		$datalist				 = $this->getData($payRequest); // $this->formatPaymentData($payRequest)
		$dataJson				 = CJSON::encode($datalist);
		$checksum				 = EpayLaterEncryptDecryptUtil::createChecksum($dataJson);
		$key					 = $this->enckey;
		$iv						 = $this->IV;
		$encData				 = EpayLaterEncryptDecryptUtil::encrypt($key, $iv, $dataJson);
		$param_list['txn_url']	 = $this->initiate_url;
		$param_list['mcode']	 = $this->mCode;
		$param_list['checksum']	 = $checksum;
		$param_list['encdata']	 = $encData;

		return $param_list;
	}

	public function getData($payRequest)
	{
		$transcode	 = $payRequest->transaction_code;
		$mcode		 = $this->mCode;
		$amount		 = round($payRequest->trans_amount * 100);
		$device		 = UserLog::model()->getDevice();
		$ip			 = Filter::getUserIP();
		$callbackUrl = YII::app()->createAbsoluteUrl('payment/response?ptpid=15');
		$name		 = $payRequest->name;
		$email		 = $payRequest->email;
		$phone		 = $payRequest->mobile;

		$user = Users::model()->findByPk($payRequest->custInfo);


		if (!$this->api_live)
		{
//		$name	 = "Debabrata Gharah";
//		$email	 = "deba@epaylater.in";
//			$phone = "9886766425";
		}



		$nameArr = explode(' ', $name);
		$fname	 = '';
		$lname	 = '';
		if (count($nameArr) > 1)
		{
			foreach ($nameArr as $k => $v)
			{
				if ($k == 0)
				{
					$fname = $nameArr[0];
				}
				else
				{
					$lname .= ' ' . $v;
				}
			}
		}
		else
		{
			$fname	 = $name;
			$lname	 = $name;
		}
		$lname = trim($lname);

		$data = [
			"redirectType"						 => "WEBPAGE",
			"marketplaceOrderId"				 => "$transcode",
//			"merchantOrderId"					 => "test123",
			"mCode"								 => "$mcode",
			"callbackUrl"						 => "$callbackUrl",
			"customerEmailVerified"				 => false,
			"customerTelephoneNumberVerified"	 => true,
			"customerLoggedin"					 => true,
			"amount"							 => "$amount",
			"currencyCode"						 => "INR",
			"date"								 => DateTime::createFromFormat('U.u', microtime(true))->format("Y-m-d\TH:i:s\Z"),
			"category"							 => "TRAVEL",
			"customer"							 => [
				"firstName"			 => "$fname",
				"lastName"			 => "$lname",
				"emailAddress"		 => "$email",
				"telephoneNumber"	 => "$phone",
//				"gender"			 => "male",
//				"dob"				 => "1985-01-11"
			],
			"device"							 => [
				"deviceType"	 => "DESKTOP",
				"deviceClient"	 => "$device",
				"deviceNumber"	 => "$ip",
//				"deviceId"		 => "NULL",
//				"deviceMake"	 => "",
//				"deviceModel"	 => "",
//				"IMEINumber"	 => "",
//				"osVersion"		 => ""
			],
			"address"							 => [
				"line1"	 => "$payRequest->billingAddress",
//				"line2"		 => "Hennur Main Road",
//				"line3"		 => "",
				"city"	 => "$payRequest->city",
//				"postcode"	 => "560077"
			],
//			"location"							 => [
//				"latitude"	 => "-1.1234",
//				"longitude"	 => "1.345",
//				"accuracy"	 => "100m"
//			],
//			"merchant"							 => [
//				"marketplaceMerchantId"	 => "1001",
//				"name"					 => "Durga Traders",
//				"telephoneNumber"		 => "01244876532",
//				"address"				 => [
//					"line1"		 => "340 Brigade Road",
//					"city"		 => "bangalore",
//					"postcode"	 => "560077"]
//			],
//			"orderHistory"						 => [
//				[
//					"orderId"		 => "OR100101",
//					"amount"		 => "56000",
//					"currencyCode"	 => "INR",
//					"date"			 => "2016-09-23T11:06:46Z",
//					"category"		 => "POWER TOOLS",
//					"paymentMethod"	 => "CREDIT CARD",
//					"returned"		 => "true",
//					"returnReason"	 => "damagedProductReceived",
//					"address"		 => [
//						"line1"		 => "Apt Number",
//						"line2"		 => "Near Galleria",
//						"line3"		 => "DLF City Phase IV",
//						"city"		 => "Gurgaon",
//						"postcode"	 => "122009"
//					],
//					"device"		 => [
//						"deviceType"	 => "MOBILE",
//						"deviceClient"	 => "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36",
//						"deviceNumber"	 => "202.56.215.28",
//						"deviceId"		 => "00:15:E9:2B:99:3C",
//						"deviceMake"	 => "Apple iPhone",
//						"deviceModel"	 => "7 Plus",
//						"IMEINumber"	 => "351756051523999",
//						"osVersion"		 => "iOS 10.2.1"]
//				],
//				[
//					"orderId"		 => "OR100102",
//					"amount"		 => "78000",
//					"currencyCode"	 => "INR",
//					"date"			 => "2016-10-20T11:06:46Z",
//					"category"		 => "POWER TOOLS",
//					"paymentMethod"	 => "BANK TRANSFER",
//					"returned"		 => "false",
//					"address"		 => [
//						"line1"		 => "Apt Number",
//						"line2"		 => "Jalvayu Vihar",
//						"line3"		 => "Sector 21",
//						"city"		 => "NOIDa",
//						"postcode"	 => "201301"
//					],
//					"device"		 => [
//						"deviceType"	 => "MOBILE",
//						"deviceClient"	 => "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36",
//						"deviceNumber"	 => "202.56.215.28",
//						"deviceId"		 => "00:15:E9:2B:99:3C",
//						"deviceMake"	 => "Apple iPhone",
//						"deviceModel"	 => "7 Plus",
//						"IMEINumber"	 => "351756051523999",
//						"osVersion"		 => "iOS 10.2.1"]
//				]
////			],
			"marketplaceSpecificSection"		 => [
				"marketplaceCustomerId"	 => "$payRequest->custInfo",
				"memberSince"			 => date('Y-m-d', strtotime($user->usr_created_at))
			]
		];
		return $data;
	}

	public function parseResponse($resArr)
	{
		$encdata	 = $resArr['encdata'];
		$checksum	 = $resArr['checksum'];
		$key		 = $this->enckey;
		$iv			 = $this->IV;
		$response	 = EpayLaterEncryptDecryptUtil::decrypt($key, $iv, $encdata);

		$checksumData	 = EpayLaterEncryptDecryptUtil::createChecksum($response);
		$verified		 = 0;
		if ($checksum == $checksumData)
		{
			$verified = 1;
		}

		$responseArr = json_decode($response, true);
//		  mCode => (string) aaocab
//  marketplaceOrderId => (string) 180908000048973
//  eplOrderId => (string) 631697
//  amount => (string) 26900.00
//  currencyCode => (string) INR
//  category => (string) TRAVEL
//  status => (string) Success
//  statusDesc => (string) Completed successfully
//  statusCode => (string) EPL0000

		$transCode = $responseArr['marketplaceOrderId'];

		$status = 0;
		if ($responseArr['status'] == 'Success' && $verified == 1)
		{
			$status		 = 1;
			$eplOrderId	 = $responseArr['eplOrderId'];
			$parr		 = $this->confirmPayment($transCode, $eplOrderId);
//			PaymentGateway::model()->verifyUserBySuccesTransaction($transCode);
		}
		else
		{
			$status = 2;
		}
		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_EPAYLATER;
		$payResponse->transaction_code	 = $transCode;
		$payResponse->response_code		 = $responseArr['statusCode'];
		$payResponse->payment_code		 = $responseArr['eplOrderId'];
		$payResponse->response			 = $response;
		$payResponse->message			 = $responseArr['statusDesc'];
		$payResponse->payment_status	 = $status;
		return $payResponse;
	}

	public function refund($transModel)
	{
		$amount = -1 * $transModel->apg_amount;

		$refModel	 = PaymentGateway::model()->findByPk($transModel->apg_ref_id);
		$refCode	 = $refModel->apg_code;
		$refAmount	 = $amount * 100.00;
		$pStatusArr	 = $this->getTxnStatus($refCode);
		if ($pStatusArr['status'] == 'agreed')
		{
			$pStatusArr = $this->confirmPayment($refCode);
		}
		$returnType = 'full';
		if ($refAmount == $pStatusArr['amount'])
		{
			$returnType = 'full';
		}
		if ($refAmount < $pStatusArr['amount'])
		{
			$returnType = 'partial';
		}


		$date	 = DateTime::createFromFormat('U.u', microtime(true))->format("Y-m-d\TH:i:s\Z");
		$url1	 = $this->txn_url;
		$data	 = [
			"marketplaceOrderId" => "$refCode",
			"returnAmount"		 => "$refAmount" . ".00",
			"refundDate"		 => "$date",
			"returnType"		 => "$returnType"
		];

		$url		 = $url1 . "/marketplaceorderid/" . $refCode . "/returned";
		$response	 = $this->callRefundApi($url, json_encode($data));

		$responseArr = json_decode($response, true);



//		array(7) (
//  [id] => (int) 632062
//  [amount] => (float) 183200
//  [currencyCode] => (string) INR
//  [date] => (string) 2018-09-14T13:36:51.918Z
//  [paylater] => (bool) true
//  [status] => (string) partially_returned
//  [marketplaceOrderId] => (string) 180914000058263
//)
//		array(5) (
//  [type] => (string) epaylater
//  [reason] => (string) operationNotAllowed
//  [code] => (string) 400
//  [message] => (string) Return amount 50,000 has to be less than original amount 23,000 for return type partial
//  [eplErrorCode] => (string) EPL1072
//)
//	{
//	"id":632164,
//	"amount":43200,
//	"currencyCode":"INR",
//	"date":"2018-09-17T09:53:44.296Z",
//	"paylater":true,
//	"status":"returned",
//	"marketplaceOrderId":"180917000058279"				
//	}



		$statusCode = 0;
		if (($responseArr['status'] == 'partially_returned' || $responseArr['status'] == 'returned') && $responseArr['paylater'])
		{
			$statusCode = 1;
		}
		else
		{
			$statusCode = 2;
		}
		$adtCode	 = $refCode;
		$resStatus	 = $responseArr['status'] . $responseArr['reason'];
		$txnid		 = $responseArr['id'];
		$errorMsg	 = $responseArr['message'];
		$respCode	 = trim($responseArr['code'] . ' ' . $responseArr['eplErrorCode']);

		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_EPAYLATER;
		$payResponse->transaction_code	 = $adtCode;
		$payResponse->response_code		 = $respCode;
		$payResponse->payment_code		 = $txnid;
		$payResponse->response			 = json_encode($responseArr);
		$payResponse->message			 = trim($resStatus . ' ' . $errorMsg);
		$payResponse->payment_status	 = $statusCode;
		return $payResponse;
	}

	public function getTxnStatus($orderid)
	{
		$transModel = PaymentGateway::model()->getByCode($orderid);
		if ($transModel->apg_mode == 1)
		{
			$orderid = PaymentGateway::model()->getCodebyid($transModel->apg_ref_id);
		}
		$url1	 = $this->txn_url;
		$url	 = $url1 . "/marketplaceorderid/" . $orderid;
		$resArr	 = $this->callAPICurl($url);
		if ($resArr['status'] == 'agreed' || $resArr['status'] == 'new')
		{

			$resArr = $this->confirmPayment($orderid);
		}
		return $resArr;
	}

	function confirmPayment($orderid, $eplid = '')
	{
		$resArr		 = [];
		$pModel		 = PaymentGateway::model()->getByCode($orderid);
		$curtime	 = Filter::getDBDateTime();
		$transTime	 = $pModel->apg_start_datetime;
		$date1		 = strtotime($curtime);
		$date2		 = strtotime($transTime);
		$diff		 = abs($date1 - $date2);
		$day		 = $diff / (60 * 60 * 24);
		if ($eplid == '')
		{
			$eplid = $pModel->apg_txn_id;
		}
		$txnUrl = $this->txn_url;
		if ($eplid != '' && $day < 7)
		{
			$url	 = $txnUrl . '/' . $eplid . '/confirmed/' . $orderid . "?delivered=true";
			$resArr	 = $this->callAPICurl($url, 'confirm');
		}
		//		array(7) (
//  [id] => (int) 632062
//  [amount] => (float) 184200
//  [currencyCode] => (string) INR
//  [date] => (string) 2018-09-14T13:36:51.918Z
//  [paylater] => (bool) true
//  [status] => (string) delivered
//  [marketplaceOrderId] => (string) 180914000058263
//)

		return $resArr;
	}

	public function callAPICurl($url, $type = 'enquiry')
	{
		$method = "";
		if ($type == 'enquiry')
		{
			$method = "GET";
		}
		if ($type == 'confirm')
		{
			$method = "PUT";
		}

		$seretKey	 = $this->apikey;
		$auth		 = "Bearer $seretKey";
		$ch			 = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_URL, trim($url));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "$method");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); /* return the output in string format */
		$headers	 = array(
			"Authorization: $auth",
			"cache-control: no-cache",
			"content-type: application/json"
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$output		 = curl_exec($ch);
		$data		 = json_decode($output, true);
		return $data;
	}

	function callRefundApi($url, $data)
	{
		$auth = $this->apikey;

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL				 => "$url",
			CURLOPT_RETURNTRANSFER	 => true,
			CURLOPT_MAXREDIRS		 => 10,
			CURLOPT_TIMEOUT			 => 30,
			CURLOPT_CUSTOMREQUEST	 => "PUT",
			CURLOPT_POSTFIELDS		 => "$data",
			CURLOPT_HTTPHEADER		 => array(
				"Authorization: Bearer $auth",
				"cache-control: no-cache",
				"content-type: application/json"
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

	public function getPaymentStatus($pgModel)
	{
		$transcode	 = $pgModel->apg_code;
		$responseArr = $this->getTxnStatus($transcode);
		if ($pgModel->apg_mode == 1)
		{

			$assumedRefundableAmount = PaymentGateway::model()->getAllowedOtherRefundAmt($pgModel->apg_ref_id, $pgModel->apg_id) | 0;
			$refundApplied			 = $pgModel->apg_amount * -100;
			if ($responseArr['amount'] != $refundApplied || ($assumedRefundableAmount * 100) < $refundApplied || $assumedRefundableAmount == 0)
			{
				$responseArr['reason']	 = 'operationNotAllowed';
				$responseArr['paylater'] = false;
				unset($responseArr['status']);
			}
		}

		$statusCode = 0;
		if (( $responseArr['status'] == 'agreed' || $responseArr['status'] == 'delivered' || ($pgModel->apg_mode == 1 && ($responseArr['status'] == 'partially_returned' || $responseArr['status'] == 'returned'))) && $responseArr['paylater'])
		{
			$statusCode = 1;
		}
		if ($responseArr['reason'] == 'operationNotAllowed' || $responseArr['reason'] == 'resourceNotFound')
		{
			$statusCode = 2;
		}

		$resStatus = $responseArr['status'];
		if ($responseArr['eplErrorCode'] != '')
		{
			$resStatus	 = $responseArr['reason'] . ' - ' . $responseArr['message'];
			$respCode	 = $responseArr['eplErrorCode'];
		}

		$txnid = $responseArr['id'];

		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_EPAYLATER;
		$payResponse->transaction_code	 = $transcode;
		$payResponse->response_code		 = $respCode;
		$payResponse->payment_code		 = $txnid;
		$payResponse->response			 = json_encode($responseArr);
		$payResponse->message			 = trim($resStatus);
		$payResponse->payment_status	 = $statusCode;
		return $payResponse;
	}

}

class EpayLaterEncryptDecryptUtil
{

	const OPENSSL_CIPHER_NAME	 = "aes-256-cbc";
	const CIPHER_KEY_LEN		 = 32;

	private static function fixKey($key)
	{
		if (strlen($key) < EpayLaterEncryptDecryptUtil::CIPHER_KEY_LEN)
		{
			return str_pad("$key", EpayLaterEncryptDecryptUtil::CIPHER_KEY_LEN, "0");
		}

		if (strlen($key) > EpayLaterEncryptDecryptUtil::CIPHER_KEY_LEN)
		{
			return substr($key, 0, EpayLaterEncryptDecryptUtil::CIPHER_KEY_LEN);
		}
		return $key;
	}

	static function encrypt($key, $iv, $data)
	{
		$encodedEncryptedData	 = base64_encode(openssl_encrypt($data, EpayLaterEncryptDecryptUtil::OPENSSL_CIPHER_NAME, EpayLaterEncryptDecryptUtil::fixKey($key), OPENSSL_RAW_DATA, $iv));
		$encryptedPayload		 = $encodedEncryptedData;
		return $encryptedPayload;
	}

	static function decrypt($key, $iv, $data)
	{
		$encrypted		 = $data;
		$decryptedData	 = openssl_decrypt(base64_decode($encrypted), EpayLaterEncryptDecryptUtil::OPENSSL_CIPHER_NAME, EpayLaterEncryptDecryptUtil::fixKey($key), OPENSSL_RAW_DATA, $iv);
		return $decryptedData;
	}

	static function createChecksum($input)
	{
		$hash = hash("sha256", utf8_encode($input));
		return strtoupper($hash);
	}

}
