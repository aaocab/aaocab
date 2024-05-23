<?php

/* Sample response
  $response			 = '{
  "requestId": "",
  "service": "AccountStatement",
  "encryptedKey": "IUD7lpOxUqOhCWTEsS1q7JviqtUcY/YF/C2LdKDQvGBSBcPde93YwJ7wzGSmj8Mn42leSEKISnrfh7F7Xyt+N0AYu8j33QUeTr7T8u3i7hB7sG5PkwCUFrq2tcZxBhJWl9JGLony+bMP3qiToLL4yAw+vZ40RbDUj4AhwPEmiGO/+SP89LY3UJcqBXxWffxc8yTUbi5QJbx4qCxmlHsizsNg7Mjpj8Vf51IVVlZCWGvTp4y1LfS5NUEZlNBl8m0OrJyfhzT5yKI9CPBRBV6DrjHzEgpRi486ZRkAvITa2swtvG1SLQbpzRW9AKGkpIAo1+lC2K3gAUn66Vhh2Odf4z9ooiQ8mVi3761G6afUHRA0IU/aPY1rm88gX7/Tgfk51nXvKLIqOuxDH9KEg02LiUisH9IV/UROCLPPdpoVRGrP4tbJ3UNzF2I+GSpxGHkewlnTU9TF3NJE0LH9KbgpQCNbN06GWw2PqFVbY+qHYul1mVq5GQ3vHq3qHUqkziL2kdnxSqoIJvsxFMHk6kXU3zlWnwSRzxvetmG094SArIREU2qH2b+bRx5rFUv8ub0LRE5Ur+BSYkmWc0e8rge7dFGwB0RyxeJeHnOHCdNMeNK/NJIaugqPIsA/SkEquEPXy3pmAlUHWWeTGz67P4OrWwkdNk8c8NAhgqcm1mCrDR8=",
  "oaepHashingAlgorithm": "NONE",
  "iv": "",
  "encryptedData": "dLYj3GHaf9cmyFZ35dckeQ+73LEHNCAslVZ5q8xwqO8MYeO5P1NOf+FZjMHx7ZcR+FHcffs1smnSmTma3QyLIb3Um3DYScr8Su5wnWpPmuJTqTUi9G/oPqqxpYfEasRi27gRJVUlKw2qMcXGG2tAWEO0Ulo6nXxEjddAnNqZOXc8T9a8ujkh9wHfLK7rDOMIJAinWCEFOgpfGu7w7k1oof8/kruGehEe5dcpVd+KhHVdrGU/dChOEAGJXLddHHsO95vT1Jjm9HQO4ETCsOCMbbzul64X8sXLOraePkcrRAE=",
  "clientInfo": "",
  "optionalParam": ""
  }'; */

class ICICIIB extends CComponent
{

	public $api_live = false;
	public $registration_url;
	public $registration_status_url;
	public $transaction_query_url;
	public $account_statement_url;
	public $txn_url;
	public $balance_enquiry_url;
	public $view	 = 'payform';
	public $aggrid, $aggrname, $corpid, $userid, $urn, $accnumber, $apikey;
	public $public_key_path, $private_dec_key_path;
//UPI Config
	public $merchantId;
	public $vpa;
	public $UPI_collectPay, $UPI_QR, $UPI_callbackStatus, $UPI_transactionStatus, $UPI_refund;

//upi_rsa_apikey.cer
//Enter RTG for RTGS, RGS for NEFT ,IFS for IMPS ,OWN for Own to Own & TPA for Own to external payments.  For Virtual A/c payments Txn_type should be "VAP" & "RGS" and IFSC should be ICIC0000103, ICIC0000104 & ICIC0000106 depending on the client codes created for the service of virtual account number based collection. This is communicated during setup of this service for any client. IMPS & RTGS txn will not allowed for virtual payments.

	const payType = ['RTG'	 => 'RTGS', //For virtual
		'RGS'	 => 'NEFT',
		'IFS'	 => 'IMPS',
		'OWN'	 => 'Self',
		'TPA'	 => 'Third Party Payment'];

	public function init()
	{

//		$config			 = Yii::app()->params['icici'];
//		Config::getArrayList(true);
		$config			 = Config::get('3PA.CIB.icici');
		$this->api_live	 = (bool) $config['api_live'];

		$domain			 = "https://apigwuat.icicibank.com:8443";
		$publicKeypath	 = '/config/icici_cert/icici_public_cert';
		$privateKeypath	 = '/config/icici_cert/gozo_private';
		if ($this->api_live == true)
		{
//			$domain			 = 'https://api.icicibank.com:8443';
			$domain			 = 'https://apibankingone.icicibank.com';
			$publicKeypath	 = $publicKeypath . '_prod';
			$privateKeypath	 = $privateKeypath . '_prod';
		}
		$url = $domain . '/api/Corporate/CIB/v1/';

		$this->public_key_path		 = $publicKeypath . '.crt';
		$this->private_dec_key_path	 = $privateKeypath . '.key';

		$this->aggrid	 = $config['aggrid'];
		$this->aggrname	 = $config['aggrname'];
		$this->corpid	 = $config['corpid'];
		$this->userid	 = $config['userid'];
		$this->urn		 = $config['urn'];
		$this->accnumber = $config['accnumber'];
		$this->apikey	 = $config['apikey'];



		$this->registration_url			 = $url . 'Registration';
		$this->txn_url					 = $url . 'Transaction';
		$this->transaction_query_url	 = $url . 'TransactionInquiry';
		$this->account_statement_url	 = $url . 'AccountStatement';
		$this->balance_enquiry_url		 = $url . 'BalanceInquiry';
		$this->registration_status_url	 = $url . 'RegistrationStatus';



		$compApiUrl			 = ':8443/api/v1/composite-payment';
		$compositePayment	 = $domain . ':8443/api/v1/composite-payment';

/////////UPI

		$upiUrl				 = $domain . ':8443/api/MerchantAPI/UPI';
		$this->merchantId	 = 400612;
		$this->vpa			 = 'uattest0010@icici';
		$merchantId			 = $this->merchantId;

		$this->UPI_collectPay		 = $upiUrl . '/v2/CollectPay/' . $merchantId;
		$this->UPI_QR				 = $upiUrl . '/v1/QR/' . $merchantId;
		$this->UPI_callbackStatus	 = $upiUrl . '/v2/CallbackStatus/' . $merchantId;
		$this->UPI_transactionStatus = $upiUrl . '/v3/TransactionStatus/' . $merchantId;
		$this->UPI_refund			 = $upiUrl . '/v1/Refund/' . $merchantId;
	}

	public static function callAPICurl($params, $url)
	{
		echo 'wrong api';
		exit;
		$curl		 = curl_init();
		$paramlist	 = Filter::encodeURLinArray($params);

		curl_setopt_array($curl, array(
			CURLOPT_URL				 => $url,
			CURLOPT_RETURNTRANSFER	 => true,
			CURLOPT_ENCODING		 => "",
			CURLOPT_MAXREDIRS		 => 10,
			CURLOPT_TIMEOUT			 => 0,
			CURLOPT_FOLLOWLOCATION	 => true,
			CURLOPT_HTTP_VERSION	 => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST	 => "POST",
			CURLOPT_POSTFIELDS		 => "$paramlist",
			CURLOPT_HTTPHEADER		 => array(
				"accept:  */*",
				"content-length:  684",
				"content-type:  text/plain",
				"apikey:  9af966d076634176a1d7e412722c0e18"
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
		exit;
	}

	/** @param array $params check ICICIB::getParamKeys()
	 * @return stdClass  */
	public function transaction($params)
	{
		$url		 = self::getTransactionUrl();
		$payResponse = $this->query($params, $url);
		return $payResponse;
	}

	/** @return stdClass  */
	public function query($params, $url, $isStatement = false)
	{
		$encReq			 = $this->EncryptData($params);
		$final_request	 = json_encode($encReq);

		$responseArr = $this->callApi($url, $final_request);
		$response	 = $responseArr['response'];
		$httpcode	 = $responseArr['httpcode'];

		if ($httpcode != 200)
		{
			$decryptedResponse = $response;
		}
		else
		{
			if ($isStatement)
			{
				$decryptedResponse = $this->DecryptStatementData($response);
			}
			else
			{
				$decryptedResponse = $this->DecryptData($response);
			}
		}
		$respArr			 = json_decode($decryptedResponse);
		$respArr->httpcode	 = $httpcode;
		$resJson			 = json_encode($respArr);


		$payResponse = $this->processResponse($resJson);
		return $payResponse;
	}

	function callApi($url, $final_request)
	{
		$apikey		 = $this->apikey;
		$header		 = array(
			"accept:  */*",
//			"content-length:  684",
//			"content-type:  text/plain",
//			"x-Forwarded-For: 103.121.157.2",
//			"x-Forwarded-For: 110.225.8.237",
			"apikey: $apikey",
		);
		$curl		 = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_PORT			 => "8443",
			CURLOPT_URL				 => $url,
			CURLOPT_RETURNTRANSFER	 => true,
			CURLOPT_ENCODING		 => "",
			CURLOPT_MAXREDIRS		 => 10,
			CURLOPT_TIMEOUT			 => 120,
			CURLOPT_HTTP_VERSION	 => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST	 => "POST",
			CURLOPT_POSTFIELDS		 => $final_request,
			CURLOPT_HTTPHEADER		 => $header
		));
		$response	 = curl_exec($curl);
		$httpcode	 = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$err		 = curl_error($curl);
		curl_close($curl);
		$responseArr = ['response' => $response, 'httpcode' => $httpcode];
		return $responseArr;
	}

	function EncryptData($source)
	{
		$public_key_path = $this->public_key_path;
		$cert			 = file_get_contents(APPLICATION_PATH . $public_key_path, true);
		$pub_key		 = openssl_pkey_get_public($cert);
		openssl_public_encrypt(json_encode($source), $crypttext, $pub_key, OPENSSL_PKCS1_PADDING);
		return(base64_encode($crypttext));
	}

	function DecryptData($crypttext)
	{
		$private_key_path	 = $this->private_dec_key_path;
		$cert				 = file_get_contents(APPLICATION_PATH . $private_key_path, true);
		$private_key		 = openssl_pkey_get_private($cert);
		openssl_private_decrypt(base64_decode($crypttext), $decrypttext, $private_key);
		return $decrypttext;
	}

	function DecryptStatementData($response)
	{
		$resArr	 = json_decode($response);
		$encKey	 = $resArr->encryptedKey;
		$encData = $resArr->encryptedData;

		$data	 = base64_decode($encData);
		$iv		 = substr($data, 0, 16);
		$key	 = $this->DecryptData($encKey);

		$decryptData	 = openssl_decrypt($data, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);
		$responseData	 = substr($decryptData, 16);
		return $responseData;
	}

	function getDefaults()
	{
		$params = array(
			'AGGRID'		 => $this->aggrid, //c
			'AGGRNAME'		 => $this->aggrname, //c
			'CORPID'		 => $this->corpid, //c 'CIBNEXT',
			'USERID'		 => $this->userid, //c 'CIBTESTING6',
			'URN'			 => $this->urn, //c
			'DEBITACC'		 => $this->accnumber, //'000451000301', //c
			'ACCOUNTNO'		 => $this->accnumber, //  '000451000301', //c
			'CREDITACC'		 => '000405002777', //v
			'IFSC'			 => 'ICIC0000011', //v
			'AMOUNT'		 => '1', //v
			'CURRENCY'		 => 'INR', //c
			'REMARKS'		 => '',
			'TXNTYPE'		 => 'TPA',
			"REQUESTFROM"	 => "AGTR",
			"REQUESTTYPE"	 => "AGREG",
			"BANKID"		 => "ICI",
			'FROMDATE'		 => date('01-m-Y'), //, strtotime('PREVIOUS MONTH')),
			'TODATE'		 => date('t-m-Y'),
			'PAYEENAME'		 => 'GOZO',
			"BANKID"		 => "ICI"
		);
		return $params;
	}

	function getParamKeys()
	{
		$params = array(
			'AGGRNAME',
			'AGGRID',
			'CORPID',
			'USERID',
			'URN',
			'DEBITACC',
			'CREDITACC',
			'IFSC',
			'AMOUNT',
			'CURRENCY',
			'TXNTYPE',
			'REMARKS',
			"URN",
			"REQUESTFROM",
			"REQUESTTYPE",
			"BANKID"
		);
		return $params;
	}

	function processResponse($response)
	{

		$responseData	 = json_decode($response);
//		$status			 = (isset($responseData->success)) ? $responseData->success : $responseData->Response;
		$statusCode		 = 0;
		if ((isset($responseData->success) && $responseData->success ) || $responseData->Response === "Success" || $responseData->RESPONSE === "Success" || $responseData->RESPONSE === "SUCCESS")
		{
			$statusCode = 1;
		}
		if ((isset($responseData->success) && !$responseData->success) || strtolower($responseData->RESPONSE) === "failure" || strtolower($responseData->response) === "failure" || strtolower($responseData->Response) === "Failure")
		{
			$statusCode = 2;
		}
		if (strtolower($responseData->RESPONSE) === "failure" || strtolower($responseData->response) === "failure")
		{
			$statusCode = 2;
			if ($responseData->STATUS == "DUPLICATE")
			{
				$statusCode = 3;
			}
		}
		$message		 = $responseData->Status . $responseData->STATUS . $responseData->MESSAGE . $responseData->Message . $responseData->message;
		$responseCode	 = $responseData->response . $responseData->ResponseCode . $responseData->RESPONSECODE . $responseData->ERRORCODE;

		$payResponse				 = new PaymentResponse();
		$payResponse->response		 = $response;
		$payResponse->message		 = $message;
		$payResponse->payment_status = $statusCode;
		$payResponse->response_code	 = $responseCode;
		if ($statusCode == 1)
		{
			$payResponse->payment_code		 = $responseData->UTRNUMBER;
			$payResponse->transaction_code	 = $responseData->UNIQUEID;
			$payResponse->response_code		 = $responseData->httpcode;
		}
		$result = Filter::removeNull($payResponse);

		return $result;
	}

	function loadCommon($params)
	{
		$defArr		 = Yii::app()->icici->getDefaults();
		$paramList	 = [];
		foreach ($params as $param)
		{
			$paramList[$param] = $defArr[$param];
		}
		return $paramList;
	}

	function getTransConstArrList()
	{
		$arrConst	 = $this->getTransOtherConstArr();
		$paramList	 = Yii::app()->icici->loadCommon($arrConst);
		return $paramList;
	}

	function getTransOtherConstArr()
	{
		$arrConst		 = $this->getConstArr();
		$arrOtherConst	 = ['AGGRNAME', 'DEBITACC', 'CURRENCY'];
		return array_merge($arrConst, $arrOtherConst);
	}

	function getConstArr()
	{
		$arrConst = ['AGGRID',
			'CORPID',
			'USERID',
			'URN'];
		return $arrConst;
	}

	public static function getRegistrationUrl()
	{
		return Yii::app()->icici->registration_url;
	}

	public static function getTransactionUrl()
	{
		return Yii::app()->icici->txn_url;
	}

	public static function getTransactionInquiryUrl()
	{
		return Yii::app()->icici->transaction_query_url;
	}

	public static function getAccountStatementUrl()
	{
		return Yii::app()->icici->account_statement_url;
	}

	public static function getBalanceInquiryUrl()
	{
		return Yii::app()->icici->balance_enquiry_url;
	}

	public static function getRegistrationStatusUrl()
	{
		return Yii::app()->icici->registration_status_url;
	}

	public function demoUpi($params)
	{
		$url = $this->UPI_QR;

		$cert			 = file_get_contents(APPLICATION_PATH . '/config/icici_cert/upi_rsa_apikey.cer', true);
		$pub_key		 = openssl_pkey_get_public($cert);
		openssl_public_encrypt(json_encode($params), $crypttext, $pub_key, OPENSSL_PKCS1_PADDING);
//		return();
		$final_request	 = base64_encode($crypttext);

		$responseArr		 = $this->callApi($url, $final_request);
		$response			 = $responseArr['response'];
		echo $decryptedResponse	 = $this->DecryptData($response);
	}

	function getTransactionParamKeys()
	{
		return [
			'AGGRID',
			'AGGRNAME',
			'CORPID',
			'USERID',
			'URN',
			'UNIQUEID',
			'DEBITACC',
			'CREDITACC',
			'IFSC',
			'AMOUNT',
			'CURRENCY',
			'TXNTYPE',
			'PAYEENAME'];
	}

	function getTransactionDefaults()
	{

		$params = array(
			'AGGRID'	 => $this->aggrid,
			'AGGRNAME'	 => $this->aggrname,
			'CORPID'	 => $this->corpid,
			'USERID'	 => $this->userid,
			'URN'		 => $this->urn,
			'DEBITACC'	 => $this->accnumber,
			'CURRENCY'	 => 'INR',
//			'TXNTYPE'	 => 'TPA',
		);
		return $params;
	}

	public function parseRequest(\Stub\common\Bank $bank, $uniqueId, $amount, $remarks = '')
	{
		$PayDetails = $this->getTransactionDefaults();

		if (strtolower(substr($bank->ifsc, 0, 4)) == 'icic')
		{
			$PayDetails['IFSC']		 = 'ICIC0000011';
			$PayDetails['TXNTYPE']	 = 'TPA';
		}
		else
		{
			$PayDetails['IFSC']		 = $bank->ifsc;
			$PayDetails['TXNTYPE']	 = 'RGS';
		}


		$PayDetails['CREDITACC'] = $bank->accountNumber;
		$PayDetails['PAYEENAME'] = $bank->beneficiaryName;
		$PayDetails['UNIQUEID']	 = $uniqueId;
		$PayDetails['AMOUNT']	 = $amount;
		$remarks				 = preg_replace('/[^A-Za-z0-9]/', '', $remarks);
		$PayDetails['REMARKS']	 = substr(trim($remarks), 0, 35);
		return $PayDetails;
	}

	public function initiatePayment(\Stub\common\Bank $bank, $uniqueId, $amount, $entityArr, $remarks = '')
	{
		$PayDetails = $this->parseRequest($bank, $uniqueId, $amount, $remarks);


		$added = OnlineBanking::addNew($PayDetails, $entityArr);
		if ($added)
		{
			$uniqueId	 = $PayDetails['UNIQUEID'];
			$url		 = ICICIIB::getTransactionUrl();
			$response	 = Yii::app()->icici->transaction($PayDetails, $url);

			$updated = OnlineBanking::updateStatusByUniqueId($uniqueId, $response);
			return $response;

//			Yii::app()->end();
		}
		return false;
	}

	public function registerRequest(\Stub\common\Bank $bank, $uniqueId, $amount, $entityArr, $remarks = '', UserInfo $userInfo = null)
	{
		$PayDetails = $this->parseRequest($bank, $uniqueId, $amount, $remarks);
		if ($userInfo == null)
		{
			$userInfo = UserInfo::getInstance();
		}


		$added = OnlineBanking::addNew($PayDetails, $entityArr, $userInfo);

		return $added;
	}

	public static function getTestAccountDetails(\Stub\common\Bank $bank = null)
	{
		$config = Config::get('3PA.CIB.icici');
		if (!$config['api_live'])
		{
			$bank->beneficiaryName	 = $config['payeename'];
			$bank->ifsc				 = $config['ifsc'];
			$bank->accountNumber	 = $config['creditaccount'];
		}
		return $bank;
	}

}
