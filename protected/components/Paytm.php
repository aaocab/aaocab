<?php

class Paytm extends CComponent
{

	public $api_live		 = false;
	public $merchant_key;
	public $merchant_id;
	public $website;
	public $appwebsite;
	public $industry_type_id = 'Retail';
	public $channel_id		 = 'WEB';
	//  public $channel_app_id = 'WAP';
	public $channel_app_id	 = 'APP';
	public $refund_url;
	public $status_query_url;
	public $refund_status_query_url;
	public $txn_url;
	public $view			 = 'paytm';

	public function init()
	{
		$domain = 'pguat.paytm.com';

		if ($this->api_live == true)
		{
			$domain = 'secure.paytm.in';
		}

		$this->refund_url				 = 'https://' . $domain . '/oltp/HANDLER_INTERNAL/REFUND';
		$this->status_query_url			 = 'https://' . $domain . '/oltp/HANDLER_INTERNAL/TXNSTATUS';
		$this->refund_status_query_url	 = 'https://' . $domain . '/oltp/HANDLER_INTERNAL/getRefundStatus';
		$this->txn_url					 = 'https://' . $domain . '/oltp-web/processTransaction';


//        $newdomain = 'securegw-stage.paytm.in';
//
//        if ($this->api_live == true)
//        {
//            $newdomain = 'securegw.paytm.in';
//        }
//
//        $this->refund_url              = 'https://' . $newdomain . '/refund/HANDLER_INTERNAL/REFUND';
//        $this->status_query_url        = 'https://' . $newdomain . '/merchant-status/getTxnStatus';
//        $this->refund_status_query_url = 'https://' . $newdomain . '/refund/HANDLER_INTERNAL/getRefundStatus';
//        $this->txn_url = 'https://' . $newdomain . '/theia/processTransaction';
	}

	public function encrypt_e($input, $key)
	{
return $this->encrypt_e_openssl($input, $key);
//
//		$size	 = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
//		$input	 = $this->pkcs5_pad_e($input, $size);
//		$td		 = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
//		$iv		 = "@@@@&&&&####$$$$";
//		mcrypt_generic_init($td, $key, $iv);
//		$data	 = mcrypt_generic($td, $input);
//		mcrypt_generic_deinit($td);
//		mcrypt_module_close($td);
//		$data	 = base64_encode($data);
//		return $data;
	}

	public function decrypt_e($crypt, $key)
	{
return $this->decrypt_e_openssl($crypt, $key);
//		$crypt = base64_decode($crypt);
//
//		$td				 = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
//		$iv				 = "@@@@&&&&####$$$$";
//		mcrypt_generic_init($td, $key, $iv);
//		$decrypted_data	 = mdecrypt_generic($td, $crypt);
//		mcrypt_generic_deinit($td);
//		mcrypt_module_close($td);
//		$decrypted_data	 = $this->pkcs5_unpad_e($decrypted_data);
//		$decrypted_data	 = rtrim($decrypted_data);
//		return $decrypted_data;
	}

	public function pkcs5_pad_e($text, $blocksize)
	{
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}

	public function pkcs5_unpad_e($text)
	{
		$pad = ord($text{strlen($text) - 1});
		if ($pad > strlen($text))
			return false;
		return substr($text, 0, -1 * $pad);
	}

	public function generateSalt_e($length)
	{
		$random = "";
		srand((double) microtime() * 1000000);

		$data	 = "AbcDE123IJKLMN67QRSTUVWXYZ";
		$data	 .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
		$data	 .= "0FGH45OP89";

		for ($i = 0; $i < $length; $i++)
		{
			$random .= substr($data, (rand() % (strlen($data))), 1);
		}

		return $random;
	}

	public function checkString_e($value)
	{
		$myvalue = ltrim($value);
		$myvalue = rtrim($myvalue);
		if ($myvalue == 'null')
			$myvalue = '';
		return $myvalue;
	}

//	public function getChecksumFromArrayOLD($arrayList, $sort = 1)
//	{
//		$key = $this->merchant_key;
//
//		if ($sort != 0)
//		{
//			ksort($arrayList);
//		}
//		$str		 = $this->getArray2Str($arrayList);
//		$salt		 = $this->generateSalt_e(4);
//		$finalString = $str . "|" . $salt;
//		$hash		 = hash("sha256", $finalString);
//		$hashString	 = $hash . $salt;
//		$checksum	 = $this->encrypt_e($hashString, $key);
//		return $checksum;
//	}
/////////////New
	function getChecksumFromArray($arrayList, $sort = 1)
	{
		$key = $this->merchant_key;
		if ($sort != 0)
		{
			ksort($arrayList);
		}
		$str		 = $this->getArray2Str($arrayList);
		$salt		 = $this->generateSalt_e(4);
		$finalString = $str . "|" . $salt;
		$hash		 = hash("sha256", $finalString);
		$hashString	 = $hash . $salt;
		$checksum	 = $this->encrypt_e_openssl($hashString, $key);
		return $checksum;
	}


	
	function getChecksumFromString($str)
	{
		$key		 = $this->merchant_key;
		$salt		 = $this->generateSalt_e(4);
		$finalString = $str . "|" . $salt;
		$hash		 = hash("sha256", $finalString);
		$hashString	 = $hash . $salt;
		$checksum	 = $this->encrypt_e_openssl($hashString, $key);
		return $checksum;
	}

/////////////////

	public function verifychecksum_e($arrayList, $checksumvalue)
	{
		$key = $this->merchant_key;

		$arrayList	 = $this->removeCheckSumParam($arrayList);
		ksort($arrayList);
		$str		 = $this->getArray2Str($arrayList);
		$paytm_hash	 = $this->decrypt_e_openssl($checksumvalue, $key);
		$salt		 = substr($paytm_hash, -4);

		$finalString = $str . "|" . $salt;

		$website_hash	 = hash("sha256", $finalString);
		$website_hash	 .= $salt;

		$validFlag = false;
		if ($website_hash == $paytm_hash)
		{
			$validFlag = true;
		}
		return $validFlag;
	}

	///////////////new
	function verifychecksum_eFromStr($str, $checksumvalue)
	{
		$key			 = $this->merchant_key;
		$paytm_hash		 = $this->decrypt_e_openssl($checksumvalue, $key);
		$salt			 = substr($paytm_hash, -4);
		$finalString	 = $str . "|" . $salt;
		$website_hash	 = hash("sha256", $finalString);
		$website_hash	 .= $salt;
		$validFlag		 = "FALSE";
		if ($website_hash == $paytm_hash)
		{
			$validFlag = "TRUE";
		}
		else
		{
			$validFlag = "FALSE";
		}
		return $validFlag;
	}

////////////
	////////////old
	public function getArray2Str($arrayList)
	{
		$paramStr	 = "";
		$flag		 = 1;
		foreach ($arrayList as $key => $value)
		{
			if ($flag)
			{
				$paramStr	 .= $this->checkString_e($value);
				$flag		 = 0;
			}
			else
			{
				$paramStr .= "|" . $this->checkString_e($value);
			}
		}
		return $paramStr;
	}

	////////////////////new
//    function getArray2Str($arrayList) {
//        $findme = 'REFUND';
//        $findmepipe = '|';
//        $paramStr = "";
//        $flag = 1;
//        foreach ($arrayList as $key => $value) {
//            $pos = strpos($value, $findme);
//            $pospipe = strpos($value, $findmepipe);
//            if ($pos !== false || $pospipe !== false) {
//                continue;
//            }
//
//            if ($flag) {
//                $paramStr .= $this->checkString_e($value);
//                $flag = 0;
//            } else {
//                $paramStr .= "|" . $this->checkString_e($value);
//            }
//        }
//        return $paramStr;
//    }

	function getArray2StrForVerify($arrayList)
	{
		$paramStr	 = "";
		$flag		 = 1;
		foreach ($arrayList as $key => $value)
		{
			if ($flag)
			{
				$paramStr	 .= $this->checkString_e($value);
				$flag		 = 0;
			}
			else
			{
				$paramStr .= "|" . $this->checkString_e($value);
			}
		}
		return $paramStr;
	}

/////////////////////////



	public function redirect2PG($paramList, $key)
	{
		$hashString	 = $this->getchecksumFromArray($paramList);
		$checksum	 = $this->encrypt_e_openssl($hashString, $key);
	}

	public function removeCheckSumParam($arrayList)
	{
		if (isset($arrayList["CHECKSUMHASH"]))
		{
			unset($arrayList["CHECKSUMHASH"]);
		}
		return $arrayList;
	}

	public function getTxnStatus($requestParamList)
	{
		$requestParamList['MID']			 = Yii::app()->paytm->merchant_id;
		$CHECKSUM							 = $this->getchecksumFromArray($requestParamList);
		$requestParamList["CHECKSUMHASH"]	 = urlencode($CHECKSUM);
		return $this->callAPI($this->status_query_url, $requestParamList);
	}

	public function getTxnStatusbyOrderid($orderid)
	{
		$paramList				 = [];
		$paramList['MID']		 = Yii::app()->paytm->merchant_id;
		$paramList['ORDERID']	 = $orderid;
		return $this->callAPI($this->status_query_url, $paramList);
	}

	public function initiateTxnRefund($requestParamList)
	{
//        $CHECKSUM = $this->getchecksumFromArray($requestParamList,0);
//        $requestParamList["CHECKSUM"] = urlencode($CHECKSUM);
//        return $this->callAPI($this->refund_url, $requestParamList);

		$CHECKSUM						 = $this->getRefundChecksumFromArray($requestParamList, 1);
		$requestParamList["CHECKSUM"]	 = urlencode($CHECKSUM);
		return $this->callNewAPI($this->refund_url, $requestParamList);
	}

	public function getTxnRefundStatus($requestParamList)
	{
		$CHECKSUM							 = $this->getchecksumFromArray($requestParamList);
		$requestParamList["CHECKSUMHASH"]	 = urlencode($CHECKSUM);
		return $this->callRefundAPI($this->refund_status_query_url, $requestParamList);
	}

	public function callAPI($apiURL, $requestParamList)
	{
		$jsonResponse		 = "";
		$responseParamList	 = array();
		$JsonData			 = json_encode($requestParamList);
		$JsonData1			 = str_replace('"', '%22', $JsonData);
		$postData			 = 'JsonData=' . $JsonData1;
		$ch					 = curl_init($apiURL);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($postData))
		);
		$jsonResponse		 = curl_exec($ch);
		$responseParamList	 = json_decode($jsonResponse, true);

		return $responseParamList;
	}

	///////////////New

	function callNewAPI($apiURL, $requestParamList)
	{
		$jsonResponse		 = "";
		Logger::create('refundUrl: ' . $apiURL, CLogger::LEVEL_TRACE);
		$responseParamList	 = array();
		$JsonData			 = json_encode($requestParamList);
		Logger::create('requestParamList: ' . $JsonData, CLogger::LEVEL_TRACE);
		$postData			 = 'JsonData=' . $JsonData;
		Logger::create('Data to be sent: ' . $postData, CLogger::LEVEL_TRACE);
		$ch					 = curl_init($apiURL);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($postData))
		);
		$jsonResponse		 = curl_exec($ch);
		Logger::create('jsonResponse:' . $jsonResponse, CLogger::LEVEL_TRACE);
		$responseParamList	 = json_decode($jsonResponse, true);

		return $responseParamList;
	}

	function getRefundChecksumFromArray($arrayList, $sort = 1)
	{
		$key = $this->merchant_key;
		if ($sort != 0)
		{
			ksort($arrayList);
		}
		$str		 = $this->getRefundArray2Str($arrayList);
		$salt		 = $this->generateSalt_e(4);
		$finalString = $str . "|" . $salt;
		$hash		 = hash("sha256", $finalString);
		$hashString	 = $hash . $salt;
		$checksum	 = $this->encrypt_e($hashString, $key);
		return $checksum;
	}

	function getRefundArray2Str($arrayList)
	{
		$findmepipe	 = '|';
		$paramStr	 = "";
		$flag		 = 1;
		foreach ($arrayList as $key => $value)
		{
			$pospipe = strpos($value, $findmepipe);
			if ($pospipe !== false)
			{
				continue;
			}
			if ($flag)
			{
				$paramStr	 .= $this->checkString_e($value);
				$flag		 = 0;
			}
			else
			{
				$paramStr .= "|" . $this->checkString_e($value);
			}
		}
		return $paramStr;
	}

	function callRefundAPI($refundApiURL, $requestParamList)
	{
		Logger::create('refundStatusUrl: ' . $refundApiURL, CLogger::LEVEL_TRACE);
		$jsonResponse		 = "";
		$responseParamList	 = array();
		$JsonData			 = json_encode($requestParamList);
		$postData			 = 'JsonData=' . ($JsonData);
		Logger::create('Data to be sent: ' . $postData, CLogger::LEVEL_TRACE);
		$ch					 = curl_init($refundApiURL);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_URL, $refundApiURL);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$headers			 = array();
		$headers[]			 = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$jsonResponse		 = curl_exec($ch);
		Logger::create('jsonResponse:' . $jsonResponse, CLogger::LEVEL_TRACE);
		$responseParamList	 = json_decode($jsonResponse, true);
		return $responseParamList;
	}

	function callNewRefundAPI($url, $paramList)
	{
		$checkSum				 = $this->getChecksumFromArray($paramList);
		$key					 = $this->merchant_key;
		$paramList["CHECKSUM"]	 = urlencode($checkSum);
		$data_string			 = 'JsonData=' . json_encode($paramList);
		$ch						 = curl_init();
// initiate curl          
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true); // tell curl you want to post something 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
// define what you want to post 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// return the output in string format 
		$headers				 = array();
		$headers[]				 = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$jsonResponse			 = curl_exec($ch);
// execute 
		$responseParamList		 = json_decode($jsonResponse, true);
		return $responseParamList;
//        $info = curl_getinfo($ch);
//        return info;
	}

	public function parseResponse($postData)
	{
		$transCode	 = $postData['ORDERID'];
		$paramList	 = [];

		$paramList['ORDERID']	 = $transCode;
		$responseArr			 = Yii::app()->paytm->getTxnStatus($paramList);
		$response				 = json_encode($responseArr);

		$param_list		 = $postData;
		$paytm_checksum	 = isset($postData["CHECKSUMHASH"]) ? $postData["CHECKSUMHASH"] : ""; //Sent by Paytm pg
		//verify checksum
		$isValidChecksum = Yii::app()->paytm->verifychecksum_e($param_list, $paytm_checksum);

		$status = 0;

		if ($responseArr['RESPCODE'] == '01' && $responseArr['STATUS'] == 'TXN_SUCCESS')
		{
			$status = 1;
		}
		if ($responseArr['STATUS'] == 'TXN_FAILURE' || $responseArr['RESPCODE'] == 331 || (isset($responseArr['ErrorCode']) && $responseArr['ErrorCode'] > 0))
		{
			$status = 2;
		}

		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_PAYTM;
		$payResponse->transaction_code	 = $responseArr['ORDERID'];
		$payResponse->response_code		 = $responseArr['RESPCODE'];
		$payResponse->payment_code		 = $responseArr['TXNID'];
		$payResponse->response			 = $response;
		$payResponse->message			 = $responseArr['RESPMSG'];
		$payResponse->payment_status	 = $status;
		return $payResponse;
	}

	public function initiateRequest($payRequest)
	{
		/*        @var $payRequest PaymentRequest   */
		$booking_info					 = $payRequest->custInfo;
		$param_list						 = array();
		// Create an array having all required parameters for creating checksum.
		$param_list['MID']				 = Yii::app()->paytm->merchant_id;
		$param_list['REQUEST_TYPE']		 = 'DEFAULT';
		$param_list['ORDER_ID']			 = $payRequest->transaction_code;
		$param_list['CUST_ID']			 = date('His') . '-' . $booking_info;
		$param_list['INDUSTRY_TYPE_ID']	 = Yii::app()->paytm->industry_type_id;
		$param_list['CHANNEL_ID']		 = Yii::app()->paytm->channel_id;
		$param_list['TXN_AMOUNT']		 = $payRequest->trans_amount;
		$param_list['WEBSITE']			 = Yii::app()->paytm->website;
		$param_list['MOBILE_NO']		 = $payRequest->mobile; //Mobile number of customer
		$param_list['EMAIL']			 = $payRequest->email; //Email ID of customer
		$param_list['CALLBACK_URL']		 = YII::app()->createAbsoluteUrl('paytm/response');

		$checkSum = Yii::app()->paytm->getChecksumFromArray($param_list);

		$param_list['CHECKSUMHASH'] = $checkSum;
		return $param_list;
		//paytm processing begin 
	}

	public function getPaymentStatus($pgModel)
	{
//		$transModel	 = PaymentGateway::model()->getByCode($transcode);
		$transcode = $pgModel->apg_code;
		if ($pgModel->apg_amount < 0 && $pgModel->apg_mode == 1)
		{
			$paramList['MID']	 = Yii::app()->paytm->merchant_id;
			$oldTransModel		 = PaymentGateway::model()->findByPk($pgModel->apg_ref_id);
			$refCheck			 = false;
			if (!$oldTransModel)
			{
				$txnid			 = $pgModel->apg_merchant_ref_id;
				$apgModel		 = PaymentGateway::model()->getByTxnId($txnid, 2);
				$oldTransModel	 = $apgModel[0];
				$refCheck		 = true;
			}

			$paramList['ORDERID']	 = $oldTransModel->apg_code;
			$paramList['REFID']		 = $transcode;
			$responseArr1			 = Yii::app()->paytm->getTxnRefundStatus($paramList);

			if (!$responseArr1 && !$refCheck)
			{
				$txnid					 = $pgModel->apg_merchant_ref_id;
				$apgModel				 = PaymentGateway::model()->getByTxnId($txnid, 2);
				$oldTransModel			 = $apgModel[0];
				$paramList['ORDERID']	 = $oldTransModel->apg_code;
				$paramList['REFID']		 = $transcode;
				$responseArr1			 = Yii::app()->paytm->getTxnRefundStatus($paramList);
			}

			$responseArr = ($responseArr1['REFUND_LIST'][0]) ? $responseArr1['REFUND_LIST'][0] : $responseArr1;
		}
		if ($pgModel->apg_amount > 0 && $pgModel->apg_mode == 2)
		{
			$paramList['MID']		 = Yii::app()->paytm->merchant_id;
			$paramList['ORDERID']	 = $transcode;
			$responseArr			 = Yii::app()->paytm->getTxnStatus($paramList);
		}


		$transStatus = trim($responseArr['STATUS'] . ' ' . $responseArr['RESPMSG']);

		$resCode = $responseArr['RESPCODE'];
		$status	 = 0;

		if ($responseArr['STATUS'] == 'TXN_SUCCESS')
		{
			$status = 1;
		}
		if ($responseArr['STATUS'] == 'TXN_FAILURE' || $responseArr['RESPCODE'] == 331 || (isset($responseArr['ErrorCode']) && $responseArr['ErrorCode'] > 0))
		{
			$status = 2;
		}
		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_PAYTM;
		$payResponse->transaction_code	 = $transcode;
		$payResponse->response_code		 = $resCode;
		$payResponse->payment_code		 = $responseArr['TXNID'];
		$payResponse->response			 = json_encode($responseArr);
		$payResponse->message			 = $transStatus;
		$payResponse->payment_status	 = $status;
		return $payResponse;
	}

	public function refund($transModel)
	{
		$paramList					 = array();
		$oldtxnid					 = PaymentGateway::model()->getTXNIDbyid($transModel->apg_ref_id);
		$amount						 = -1 * $transModel->apg_amount;
		$paramList['MID']			 = Yii::app()->paytm->merchant_id;
		$paramList['ORDERID']		 = PaymentGateway::model()->getCodebyid($transModel->apg_ref_id);
		$paramList['TXNTYPE']		 = 'REFUND';
		$paramList['REFUNDAMOUNT']	 = trim($amount . '');
		$paramList['TXNID']			 = $oldtxnid;
		$paramList['REFID']			 = $transModel->apg_code;
		$responseRefundArr			 = Yii::app()->paytm->initiateTxnRefund($paramList);

		// $response1 = json_encode($responseRefundArr);
		//verify response
		$paramListRes			 = [];
		$paramListRes['MID']	 = Yii::app()->paytm->merchant_id;
		$paramListRes['ORDERID'] = $paramList['ORDERID'];
		$paramListRes['REFID']	 = $transModel->apg_code;
		$responseArr1			 = Yii::app()->paytm->getTxnRefundStatus($paramListRes);
		//  $responseArr = $responseArr1['REFUND_LIST'][0];
		// $responseArr =$responseRefundArr;
		$responseArr			 = ($responseArr1['REFUND_LIST'][0]) ? $responseArr1['REFUND_LIST'][0] : $responseArr1;




		$transStatus = trim($responseArr['STATUS'] . ' ' . $responseArr['RESPMSG']);

		$resCode = $responseArr['RESPCODE'];
		$status	 = 0;

		if ($responseArr['STATUS'] == 'TXN_SUCCESS')
		{
			$status = 1;
		}
		if ($responseArr['STATUS'] == 'TXN_FAILURE' || $responseArr['RESPCODE'] == 331 || (isset($responseArr['ErrorCode']) && $responseArr['ErrorCode'] > 0))
		{
			$status = 2;
		}
		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_PAYTM;
		$payResponse->transaction_code	 = $transModel->apg_code;
		$payResponse->response_code		 = $resCode;
		$payResponse->payment_code		 = $responseArr['TXNID'];
		$payResponse->response			 = json_encode($responseArr);
		$payResponse->message			 = $transStatus;
		$payResponse->payment_status	 = $status;
		return $payResponse;
	}
	function encrypt_e_openssl($input, $ky)
	{
		$iv		 = "@@@@&&&&####$$$$";
		$data	 = openssl_encrypt($input, "AES-128-CBC", $ky, 0, $iv);
		return $data;
	}

	function decrypt_e_openssl($crypt, $ky)
	{
		$iv		 = "@@@@&&&&####$$$$";
		$data	 = openssl_decrypt($crypt, "AES-128-CBC", $ky, 0, $iv);
		return $data;
	}


}
