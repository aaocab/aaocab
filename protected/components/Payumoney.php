<?php

class Payumoney extends CComponent
{

	public $api_live = false;
	public $merchant_key;
	public $merchant_id;
	public $merchant_salt;
	public $secret;
	public $merchant_authorization;
	public $refund_url;
	public $cancel_url;
	public $status_query_url;
	public $refund_status_url;
	public $status_detail_query_url;
	public $txn_url;
	public $boltjsSrc;
	public $view	 = 'payu';

	public function init()
	{
//		$domain			 = 
		$domain1		 = 'test';
		$domain			 = 'sandboxsecure';
		$config			 = Yii::app()->params['payu'];
		$this->api_live	 = $config['api_live'];
		$srcval			 = '';
		if ($this->api_live == true)
		{
			$domain	 = 'secure';
			$domain1 = 'www';
		}
		else
		{
			$srcval = 'sbox';
		}
		$this->boltjsSrc				 = "https://{$srcval}checkout-static.citruspay.com/bolt/run/bolt.min.js";
		//	$this->txn_url = 'https://' .$domain;
		$this->merchant_key				 = $config['merchant_key'];
		$this->merchant_id				 = $config['merchant_id'];
		$this->merchant_salt			 = $config['merchant_salt'];
		$this->merchant_authorization	 = $config['merchant_authorization'];
		$this->secret			 = $config['merchant_salt'];


		$this->refund_url				 = 'https://' . $domain1 . '.payumoney.com/payment/merchant/refundPayment';
		$this->status_query_url			 = 'https://' . $domain1 . '.payumoney.com/payment/payment/chkMerchantTxnStatus';
		$this->refund_status_url		 = 'https://' . $domain1 . '.payumoney.com/treasury/ext/merchant/getRefundDetailsByPayment';
		$this->status_detail_query_url	 = 'https://' . $domain1 . '.payumoney.com/payment/op/getPaymentResponse';

		$this->txn_url = 'https://' . $domain . '.payu.in';
	}

	public function getChecksumFromArray($posted)
	{
		$formError		 = 0;
		$hashSequence	 = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
		//$str = $this->getArray2Str($arrayList);
		$SALT			 = $this->merchant_salt;
		$PAYU_BASE_URL	 = $this->txn_url;

		if (empty($posted['hash']) && sizeof($posted) > 0)
		{
			if (
					empty($posted['key']) || empty($posted['txnid']) || empty($posted['amount']) || empty($posted['firstname']) || (empty($posted['email']) && empty($posted['phone'])) || empty($posted['productinfo']) || empty($posted['surl']) || empty($posted['furl']) || empty($posted['service_provider'])
			)
			{
				$formError = 1;
			}
			else
			{
				$hashVarsSeq = explode('|', $hashSequence);
				$hash_string = '';
				foreach ($hashVarsSeq as $hash_var)
				{
					$hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
					$hash_string .= '|';
				}
				$hash_string .= $SALT;
				$hash		 = strtolower(hash('sha512', $hash_string));
				$action		 = $PAYU_BASE_URL . '/_payment';
			}
		}
		elseif (!empty($posted['hash']))
		{
			$hash	 = $posted['hash'];
			$action	 = $PAYU_BASE_URL . '/_payment';
		}
		$posted['hash']		 = $hash;
		$posted['action']	 = $action;
		return $posted;
	}

	public function getTxnStatus($tcode)
	{
		$requestParamList['merchantTransactionIds']	 = $tcode;
		$requestParamList['merchantKey']			 = Yii::app()->payu->merchant_key;
		return $this->callAPICurl($this->status_query_url, $requestParamList);
	}

	public function initiateTxnRefund($requestParamList)
	{
		$CHECKSUM						 = $this->getchecksumFromArray($requestParamList);
		$requestParamList["CHECKSUM"]	 = urlencode($CHECKSUM);
		return $this->callAPI($this->refund_url, $requestParamList);
	}

	public function callAPI($apiURL, $requestParamList)
	{

		$jsonResponse		 = "";
		$responseParamList	 = array();
		$JsonData			 = json_encode($requestParamList);
		$postData			 = 'JsonData=' . $JsonData;
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

	function curlCall($postUrl, $toSend)
	{
		$toSend['merchantKey']	 = Yii::app()->payu->merchant_key;
		$auth					 = Yii::app()->payu->merchant_authorization;
		$ch						 = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $toSend);
		curl_setopt($ch, CURLOPT_URL, $postUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('authorization:' . $auth));
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$out					 = curl_exec($ch);
		$responseParamList		 = json_decode($out, true);
		return $responseParamList;
	}

	public function parseResponse($postData)
	{

		$status		 = ($postData["status"] == '') ? $postData["txnStatus"] : $postData["status"];
		$firstname	 = $postData["firstname"];
		$amount		 = $postData["amount"];
		$transCode	 = $postData["txnid"];
		$posted_hash = $postData["hash"];
		$txnid		 = $postData["payuMoneyId"];
		$key		 = $postData["key"];
		$productinfo = $postData["productinfo"];
		$email		 = $postData["email"];
		$modeStr	 = $postData["mode"];
		switch (strtolower($modeStr))
		{
			case 'cc':
				$mode = PaymentResponse::TYPE_CC;
				break;

			case 'dc':
				$mode = PaymentResponse::TYPE_DC;
				break;

			case 'nb':
				$mode = PaymentResponse::TYPE_NB;
				break;

			case 'upi':
				$mode = PaymentResponse::TYPE_UPI;
				break;

			case 'cash':
				$mode = PaymentResponse::TYPE_CASH;
				break;

			case 'emi':
				$mode = PaymentResponse::TYPE_EMI;
				break;

			case 'ivr':
				$mode = PaymentResponse::TYPE_IVR;
				break;

			case 'cod':
				$mode = PaymentResponse::TYPE_COD;
				break;

			case 'clemi':
				$mode	 = PaymentResponse::TYPE_CLEMI;
				break;
			default :
				$mode	 = 0;
		}

		if (isset($postData['txnMessage']))
		{
			$postData['message'] = $postData["txnStatus"] . ', ' . $postData['txnMessage'];
		}

		$salt				 = Yii::app()->payu->merchant_salt;
		$retHashSeq			 = $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $transCode . '|' . $key;
		$response			 = json_encode($postData);
		$responseArr		 = json_decode($response, true);
		$respCode			 = $responseArr['error'];
		$errorMsg1			 = ($responseArr['error_Message'] == '') ? '' : ', ' . $responseArr['error_Message'];
		$errorMsg			 = ($responseArr['result'][0]['status']) ? $responseArr['result'][0]['status'] : $responseArr['message'];
		$response_message	 = $responseArr['field9'] . $errorMsg . $errorMsg1 . $responseArr['DESCRIPTION'];
		$statusCode			 = 0;
		$statusType			 = 0;
		$hash				 = hash("sha512", $retHashSeq);
		if ($hash == $posted_hash)
		{
			if ($status == 'success')
			{
				$statusCode	 = 1;
				$statusType	 = 2;
			}
			if ($status == 'failure' || $status == 'FAILED')
			{
				$statusCode	 = 2;
				$statusType	 = 2;
			}
		}
		elseif ($status == 'CANCEL')
		{
			$statusCode	 = 2;
			$statusType	 = 1;
		}

		$payResponse						 = new PaymentResponse();
		$payResponse->payment_type			 = PaymentType::TYPE_PAYUMONEY;
		$payResponse->transaction_code		 = $transCode;
		$payResponse->response_code			 = $respCode;
		$payResponse->payment_code			 = $txnid;
		$payResponse->mode					 = $mode;
		$payResponse->response				 = $response;
		$payResponse->message				 = str_replace('|', '-', $response_message);
		$payResponse->payment_status		 = $statusCode;
		$payResponse->payment_status_type	 = $statusType;
		return $payResponse;
	}

	function abc($transcode)
	{
		$resArr							 = [];
		//$transcode = $model->trans_code;
		$model							 = PaymentGateway::model()->getByCode($transcode);
		$url							 = Yii::app()->payu->status_query_url;
		$data['merchantKey']			 = Yii::app()->payu->merchant_key;
		$data['merchantTransactionIds']	 = $transcode;
		if ($model->apg_mode == 1)
		{
			$oldTransModel					 = Transactions::model()->findByPk($model->apg_ref_id);
			$data['merchantTransactionIds']	 = $oldTransModel->apg_code;
		}

		$auth		 = Yii::app()->payu->merchant_authorization;
		$options	 = array(
			'http' => array(
				'header'		 => "Authorization: $auth",
				'method'		 => 'POST',
				'Authorization'	 => "$auth",
				'content'		 => http_build_query($data)
			),
		);
		$context	 = stream_context_create($options);
		$response	 = file_get_contents($url, false, $context);
		$resArr		 = json_decode($response, true);
		// var_dump($resArr);
		echo 'transcode = ' . $transcode . ':: ';
		if (in_array(trim($resArr['result'][0]['status']), ['Money with Payumoney', 'Money Settled']) && $model->trans_mode == 2)
		{

			Booking::model()->updateAdvance($transcode, $response);
		}
		if (($resArr['message'] == 'Refund Initiated' || $resArr['status'] == 'Refund in progress') && $model->trans_mode == 1)
		{

			Booking::model()->updateRefund($transcode, $response);
		}
		if (in_array(trim($resArr['result'][0]['status']), ['Failed', 'Bounced', 'Under Dispute', 'Dropped', 'CancelledByUser']) || $resArr['status'] === -1)
		{
			$transModel = PaymentGateway::model()->getByCode($transcode);
			$transModel->udpdateResponseByCode($response, 2);
			if ($transModel)
			{
				$params['blg_ref_id'] = $transModel->trans_id;
				if ($transModel->trans_ref_id > 0)
				{
					BookingLog::model()->createLog($transModel->trans_booking_id, "Refund process failed ({$transModel->getPaymentType()} - {$transModel->trans_code})", UserInfo::model(), BookingLog::REFUND_PROCESS_FAILED, '', $params);
				}
				else
				{
					BookingLog::model()->createLog($transModel->trans_booking_id, "Online payment failed ({$transModel->getPaymentType()} - {$transModel->trans_code})", UserInfo::model(), BookingLog::PAYMENT_FAILED, '', $params);
				}
			}
		}
	}

	public function callAuthApi($url, $data)
	{
		$data['merchantKey'] = Yii::app()->payu->merchant_key;
		$auth				 = Yii::app()->payu->merchant_authorization;
		$options			 = array(
			'http' => array(
				'header'		 => "Authorization: $auth",
				'method'		 => 'POST',
				'Authorization'	 => "$auth",
				'content'		 => http_build_query($data)
			),
		);
		$context			 = stream_context_create($options);
		$response			 = file_get_contents($url, false, $context);
		Logger::create("PaymentGateway::PayuMoney::callAuthApi response:{$response}", CLogger::LEVEL_INFO);
		$resArr				 = json_decode($response, true);
		return $resArr;
	}

	public function callAPICurl($url, $requestParamList)
	{
		$auth		 = Yii::app()->payu->merchant_authorization;
		$data_string = http_build_query($requestParamList);
		$ch			 = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_URL, $url . '?' . $data_string);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); /* return the output in string format */
		$headers	 = array("Authorization: $auth");
		$headers[]	 = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$output		 = curl_exec($ch);
		$data		 = json_decode($output, true);
		return $data;
	}

	public function initiateRequest($payRequest)
	{
		$param_list						 = array();
		// Create an array having all required parameters for creating checksum.
		$param_list['key']				 = $this->merchant_key;
		$param_list['txnid']			 = $payRequest->transaction_code;
		$param_list['productinfo']		 = $payRequest->description;
		$param_list['amount']			 = $payRequest->trans_amount;
		$param_list['firstname']		 = $payRequest->name;
		$param_list['address1']			 = $payRequest->billingAddress; //
		$param_list['city']				 = $payRequest->city;
		$param_list['state']			 = $payRequest->state;
		$param_list['country']			 = $payRequest->country;
		$param_list['zipcode']			 = $payRequest->postal;
		$param_list['email']			 = $payRequest->email; //Email ID of customer
		$param_list['phone']			 = $payRequest->mobile;
		$param_list['surl']				 = YII::app()->createAbsoluteUrl('payu/response');
		$param_list['furl']				 = YII::app()->createAbsoluteUrl('payu/response');
		$param_list['service_provider']	 = 'payu_paisa';
		$param_list1					 = Yii::app()->payu->getChecksumFromArray($param_list);
		return $param_list1;
	}

	public function getTransactionStatus($responseArr, $refundTrans = false)
	{
		$resVal		 = $responseArr['result'][0];
		$status		 = trim($resVal['status']);
		$transCode	 = $resVal['merchantTransactionId'];
		$statusCode	 = 0;
		$statusType	 = 0;

		if (in_array($status, ['Money with Payumoney', 'Money Settled', 'Settlement in Process', 'Settlement in process']))
		{
			$statusCode	 = 1;
			$statusType	 = 2;
		}
		if (($responseArr['message'] == 'Refund Initiated' || $status == 'Full Refunded' || $status == 'Refund in progress') && $refundTrans)
		{
			$statusCode	 = 1;
			$statusType	 = 2;
		}

		if ($status == 'Failed')
		{
			$statusCode	 = 2;
			$statusType	 = 2;
		}
		if (in_array($status, ['Bounced', 'Under Dispute', 'Dropped', 'CancelledByUser', 'not started']) || $responseArr['status'] === -1)
		{
			$statusCode	 = 2;
			$statusType	 = 1;
		}


		$payResponse						 = new PaymentResponse();
		$payResponse->payment_type			 = PaymentType::TYPE_PAYUMONEY;
		$payResponse->transaction_code		 = $transCode;
		$payResponse->response_code			 = $responseArr['status'];
		$payResponse->payment_code			 = $resVal['paymentId'];
		$payResponse->response				 = json_encode($responseArr);
		$payResponse->message				 = trim($responseArr['message'] . '. ' . $status);
		$payResponse->payment_status		 = $statusCode;
		$payResponse->payment_status_type	 = $statusType;
		return $payResponse;
	}

	public function getRefundStatus($responseArr)
	{
		$resVal		 = $responseArr['result'];
		$status		 = trim($responseArr['status']);
		$transCode	 = $resVal['merchantTransactionId'];
		$statusCode	 = 0;

		if (in_array($status, ['Money with Payumoney', 'Money Settled', 'Settlement in Process', 'Settlement in process']))
		{
			$statusCode	 = 1;
			$statusType	 = 2;
		}
		if (($responseArr['message'] == 'Refund Initiated' || $status == 'Full Refunded' || $status == 'Refund in progress'))
		{
			$statusCode	 = 1;
			$statusType	 = 2;
		}
		if (in_array($status, ['Failed', 'Bounced', 'Under Dispute', 'Dropped', 'CancelledByUser', 'not started']) || $responseArr['status'] === -1)
		{
			$statusCode	 = 2;
			$statusType	 = 1;
		}

		$payResponse						 = new PaymentResponse();
		$payResponse->payment_type			 = PaymentType::TYPE_PAYUMONEY;
		$payResponse->transaction_code		 = $transCode;
		$payResponse->response_code			 = $responseArr['status'];
		$payResponse->payment_code			 = $resVal['paymentId'];
		$payResponse->response				 = json_encode($responseArr);
		$payResponse->message				 = trim($responseArr['message'] . '. ' . $status);
		$payResponse->payment_status		 = $statusCode;
		$payResponse->payment_status_type	 = $statusType;
		return $payResponse;
	}

	public function getPaymentStatus($pgModel)
	{
		$transcode = $pgModel->apg_code;

		$refundTrans = false;
		$payResponse = '';
		if ($pgModel->apg_mode == 1)
		{
			$oldTransModel	 = PaymentGateway::model()->findByPk($pgModel->apg_ref_id);
			$refundTrans	 = true;
			$paymentId		 = $oldTransModel->apg_txn_id;
			$payResponse	 = $this->fetchRefundStatus($paymentId, $pgModel);
		}
		else
		{
			$payResponse = $this->fetchPaymentStatus($transcode);
		}

		return $payResponse;
	}

	public function fetchPaymentStatus($transcode)
	{
		$url							 = Yii::app()->payu->status_query_url;
		$data['merchantKey']			 = Yii::app()->payu->merchant_key;
		$data['merchantTransactionIds']	 = $transcode;
		$auth							 = Yii::app()->payu->merchant_authorization;
		try
		{
			$curl		 = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL				 => $url . "?merchantKey=" . $data['merchantKey'] . "&merchantTransactionIds=" . $data['merchantTransactionIds'],
				CURLOPT_RETURNTRANSFER	 => true,
				CURLOPT_ENCODING		 => "",
				CURLOPT_MAXREDIRS		 => 10,
				CURLOPT_TIMEOUT			 => 30,
				CURLOPT_HTTP_VERSION	 => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST	 => "POST",
				CURLOPT_HTTPHEADER		 => array(
					"Accept: */*",
					"Accept-Encoding: gzip, deflate",
					"Authorization: $auth",
					"Cache-Control: no-cache",
					"Connection: keep-alive",
					"Content-Length: 0",
					"Host: www.payumoney.com"
				),
			));
			$response	 = curl_exec($curl);
			$err		 = curl_error($curl);
			curl_close($curl);
			if ($err)
			{
				Logger::create("Payu->getPaymentStatus    ::  Error   : " . json_encode($err));
			}
			else
			{
				Logger::create("Payu->getPaymentStatus    ::  Response    : " . $response);
				$responseArr = json_decode($response, true);
				$payResponse = $this->getTransactionStatus($responseArr);
			}
		}
		catch (Exception $e)
		{
			Logger::create("Payu->getPaymentStatus_demo   ::  Exception   : " . $e->getMessage());
		}
		return $payResponse;
	}

	public function refund($pgModel)
	{
		$refId	 = $pgModel->apg_ref_id;
		$bkgid	 = $pgModel->apg_booking_id;

		$oldPgModel	 = PaymentGateway::model()->findByPk($refId);
		$oldTXNID	 = $oldPgModel->apg_txn_id;

		$url		 = Yii::app()->payu->refund_url;

		$data['paymentId']		 = $oldTXNID;
		$data['refundAmount']	 = (-1 * $pgModel->apg_amount);

		Logger::create("PaymentGateway::PayuMoney::refund bkgId:{$bkgid} paymentId: {$oldTXNID} refundAmount:{$pgModel->apg_amount} refundUrl:{$url}", CLogger::LEVEL_INFO);
		$responseArr = Yii::app()->payu->callAuthApi($url, $data);

		Logger::create("response  : " . json_encode($responseArr));

		$payResponse = $this->getRefundStatus($responseArr);

		return $payResponse;
//			
//		$CHECKSUM						 = $this->getchecksumFromArray($requestParamList);
//		$requestParamList["CHECKSUM"]	 = urlencode($CHECKSUM);
//		return $this->callAPI($this->refund_url, $requestParamList);
	}

	public function getResponsePostService($posted)
	{

		$hashSequence	 = "key|command|var1";
		$SALT			 = $this->merchant_salt;
		$hashVarsSeq	 = explode('|', $hashSequence);
		$hash_string	 = '';
		foreach ($hashVarsSeq as $hash_var)
		{
			$hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
			$hash_string .= '|';
		}
		$hash_string		 .= $SALT;
		$hash				 = strtolower(hash('sha512', $hash_string));
		$action				 = 'https://info.payu.in/merchant/postservice.php?form=1';
		$posted['hash']		 = $hash;
		$posted['action']	 = $action;
		return $posted;
	}

	public function getRefundStatusByPayment($paymentId)
	{
		$responseArr = Yii::app()->payu->refundStatusApi($paymentId);
//		$payResponse = $this->getTransactionStatus($responseArr);
		var_dump($responseArr);
	}

	public function getRefundStatusByTranscode($transcode)
	{
		$pgModel = PaymentGateway::model()->getByCode($transcode);
		if (!$pgModel)
		{
			return false;
		}
		$oldTransModel = PaymentGateway::model()->findByPk($pgModel->apg_ref_id);

		$paymentId	 = $oldTransModel->apg_txn_id;
		$payResponse = $this->fetchRefundStatus($paymentId, $pgModel);
//		$responseArr	 = Yii::app()->payu->refundStatusApi($paymentId);
		var_dump($payResponse);
	}

	public function refundStatusApi($paymentId)
	{
		$data['merchantKey'] = Yii::app()->payu->merchant_key;
		$url				 = Yii::app()->payu->refund_status_url;
		$data['paymentId']	 = $paymentId;
		$data_string		 = http_build_query($data);

		$auth		 = Yii::app()->payu->merchant_authorization;
		$ch			 = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_URL, $url . '?' . $data_string);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); /* return the output in string format */
		$headers	 = array("Authorization: $auth");
		$headers[]	 = 'Content-Type: application/json';
		$headers[]	 = 'cache-control: no-cache';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$output		 = curl_exec($ch);
		$info		 = curl_getinfo($ch);
		if ($output === false)
		{
			// throw new Exception('Curl error: ' . curl_error($output));
			Logger::create("Curl error in getAllRefundsFromTxnIds : " . curl_error($output));
		}
		$responseParamList = json_decode($output, true);
//		var_dump($responseParamList);
		return $responseParamList;
	}

	public function processRefundResponse($detailJson)
	{
		$resultDataArr	 = [];
		$detailJson		 = str_replace('[{', '', $detailJson);
		$detailJson		 = str_replace('}]', '', $detailJson);
		$detailArr		 = explode('}, {', $detailJson);
		foreach ($detailArr as $k => $detailRes)
		{
			$resValArr = explode(',', $detailRes);
			foreach ($resValArr as $value)
			{
				$data								 = explode("=", $value);
				$resultDataArr[$k][trim($data[0])]	 = trim($data[1]);
			}
		}

		return $resultDataArr;
	}

	public function fetchRefundStatus($paymentId, $pgModel)
	{
		$refundRawArr	 = $this->refundStatusApi($paymentId);
//		$demoRes		 = '{"status":0,"rows":0,"message":"Refund Details : ","result":{"PaymentId":"250597606","Total Amount":"42.0","Amount Left":"0.0","Refund Details Map":"[{Refund Amount=42.0, Refund Created On=2020-10-29 17:05:30.0, Refund Completed On=2020-10-29 17:06:30.0, RefundId=4636610, Refund Status=completed}]"},"guid":null,"sessionId":null,"errorCode":null}';
//		$refundRawArr	 = json_decode($demoRes, true);
		$payResponse	 = $this->parseRefundDataArr($refundRawArr, $pgModel);
//		var_dump($payResponse);
		return $payResponse;
	}

	public function parseRefundDataArr($refundRawArr, $pgModel)
	{

		$resultDataArr = [];
		if ($refundRawArr['result'] != null && is_array($refundRawArr['result']))
		{
			$detailJson		 = $refundRawArr['result']['Refund Details Map'];
			$resultDataArr	 = $this->processRefundResponse($detailJson);
		}
		else
		{
			goto result;
		}
		$paymentId		 = $refundRawArr['result']['PaymentId'];
		$transDate		 = $pgModel->apg_date;
		$refundStartDate = $pgModel->apg_start_datetime;
		$refundAmount	 = -1 * $pgModel->apg_amount;
		$resArr			 = [];
		foreach ($resultDataArr as $k => $data)
		{
			$timeStampDiff = (strtotime($data['Refund Created On']) - (min([strtotime($refundStartDate), strtotime($transDate)])));
			if ((($timeStampDiff < 60 && $timeStampDiff >= 0 ) || (in_array($data['Refund Status'], ['refundinprogress', 'success']))) && $refundAmount == $data['Refund Amount'])
			{
				$resArr = $data;
				break;
			}
		}
		if (sizeof($resArr) > 0)
		{

			$statusCode	 = 0;
			$status		 = $resArr['Refund Status'];

			if (in_array($status, ['completed', 'success', 'Full Refunded', 'Refund in progress', 'refundinprogress']))
			{
				$statusCode	 = 1;
				$statusType	 = 2;
			}
			if (in_array($status, ['Failed', 'Bounced', 'Under Dispute', 'Dropped', 'CancelledByUser', 'not started']))
			{
				$statusCode	 = 2;
				$statusType	 = 2;
			}


			$payResponse						 = new PaymentResponse();
			$payResponse->payment_type			 = PaymentType::TYPE_PAYUMONEY;
			$payResponse->transaction_code		 = $pgModel->apg_code;
			$payResponse->fullResponse			 = json_encode($refundRawArr);
			$payResponse->response_code			 = 200;
			$payResponse->payment_code			 = $resArr['RefundId'];
			$payResponse->response				 = json_encode($refundRawArr);
			$payResponse->message				 = trim($status);
			$payResponse->payment_status		 = $statusCode;
			$payResponse->payment_status_type	 = $statusType;
			return $payResponse;
		}
		result:
		$payResponse						 = new PaymentResponse();
		$payResponse->payment_type			 = PaymentType::TYPE_PAYUMONEY;
		$payResponse->transaction_code		 = $pgModel->apg_code;
		$payResponse->response_code			 = 100;
		$payResponse->payment_code			 = $pgModel->apg_merchant_ref_id;
		$payResponse->response				 = json_encode($refundRawArr);
		$message							 = (isset($refundRawArr['message'])) ? ' : ' . $refundRawArr['message'] : '';
		$payResponse->message				 = 'Refund Failed' . $message;
		$payResponse->payment_status		 = 2;
		$payResponse->payment_status_type	 = 2;
		return $payResponse;
	}

	public function getAllRefundsFromTxnIds($paymentId)
	{
		$data['merchantKey'] = Yii::app()->payu->merchant_key;
		$url				 = Yii::app()->payu->refund_status_url;
		$data['paymentId']	 = $paymentId;
		$data_string		 = http_build_query($data);

		$auth		 = Yii::app()->payu->merchant_authorization;
		$ch			 = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_URL, $url . '?' . $data_string);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); /* return the output in string format */
		$headers	 = array("Authorization: $auth");
		$headers[]	 = 'Content-Type: application/json';
		$headers[]	 = 'cache-control: no-cache';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$output		 = curl_exec($ch);
		$info		 = curl_getinfo($ch);
		if ($output === false)
		{
			// throw new Exception('Curl error: ' . curl_error($output));
			Logger::create("Curl error in getAllRefundsFromTxnIds : " . curl_error($output));
		}

		$responseParamList = json_decode($output, true);
//		var_dump($responseParamList);
		return $responseParamList;
	}

}
