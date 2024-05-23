<?php

class Zaakpay extends CComponent
{

	public $api_live = false;
	public $mode	 = 0;
	public $merchant_id;
	public $secret_key;
	public $refund_url;
	public $public_key_id;
	public $public_key;
	public $status_query_url;
	public $txn_url;
	public $request_url;
	public $successArr;
	public $successTransArr;
	public $successRefundArr;
	public $failedTransArr;

	function __construct()
	{
		$this->init();
	}

	public function init()
	{
		$domain			 = 'http://zaakpay-staging.cloudapp.net:8080/';
		$config			 = Yii::app()->params['zaakpay'];
		$this->api_live	 = $config['api_live'];
		// $this->merchant_id = 'b19e8f103bce406cbd3476431b6b7973';
		//  $this->secret_key = '0678056d96914a8583fb518caf42828a';
		$this->mode		 = 0;
		if ($this->api_live == true)
		{
			$this->mode	 = 1;
			$domain		 = 'https://api.zaakpay.com/';
			// $this->merchant_id = 'c829f359372b4bf68c6601c16923fc94';
			//$this->secret_key = '839f6fedb575450c96ffbaf42c12b44a';
		}
		$this->secret_key		 = $config['secret_key'];
		$this->merchant_id		 = $config['merchant_id'];
		$this->request_url		 = $domain . 'getPaymentMethods';
		$this->txn_url			 = $domain . 'transact';
		$this->status_query_url	 = $domain . 'checktransaction';
		$this->refund_url		 = $domain . 'updatetransaction';
		$this->successArr		 = ['100', '601'];
		$this->successTransArr	 = ['100', '601', '212', '228', '230', '231', '232', '233', '236', '237', '238', '239', '240', '241', '242', '245', '246', '247', '248', '249', '251', '252', '253', '254', '255', '256'];
		$this->successRefundArr	 = ['228', '230', '231', '232', '233', '236', '245', '251', '252', '253', '254', '255', '256'];
		$this->failedTransArr	 = ['102', '629', '180', '190', '211', '213', '234', '400', '201', '203'];
	}

	public function getParams($params = array())
	{

		$paramArr = [];
		foreach ($params as $key => $value)
		{
			if ($key != 'checksum')
			{
				if ($key == 'returnUrl')
				{
					$paramArr[$key] = $this->sanitizedURL(trim($value));
				}
				else
				{
					$paramArr[$key] = $this->sanitizedParam(trim($value));
				}
			}
		}
		$all = Zaakpay::paramString($paramArr);

		$checksum				 = $this->calculateChecksum($all);
		$paramArr['txn_url']	 = $this->txn_url;
		$paramArr['checksum']	 = $checksum;
		// echo $all;
		//var_dump($paramArr);
		return $paramArr;
	}

	static function verifyChecksum($checksum, $all)
	{

		$cal_checksum = Zaakpay::calculateChecksum($all);

		$bool = 0;
		if ($checksum == $cal_checksum)
		{
			$bool = 1;
		}

		return $bool;
	}

	static function sanitizedParam($param)
	{
		$pattern[0]		 = "%,%";
		$pattern[1]		 = "%#%";
		$pattern[2]		 = "%\(%";
		$pattern[3]		 = "%\)%";
		$pattern[4]		 = "%\{%";
		$pattern[5]		 = "%\}%";
		$pattern[6]		 = "%<%";
		$pattern[7]		 = "%>%";
		$pattern[8]		 = "%`%";
		$pattern[9]		 = "%!%";
		$pattern[10]	 = "%\\$%";
		$pattern[11]	 = "%\%%";
		$pattern[12]	 = "%\^%";
		$pattern[13]	 = "%=%";
		$pattern[14]	 = "%\+%";
		$pattern[15]	 = "%\|%";
		$pattern[16]	 = "%\\\%";
		$pattern[17]	 = "%:%";
		$pattern[18]	 = "%'%";
		$pattern[19]	 = "%\"%";
		$pattern[20]	 = "%;%";
		$pattern[21]	 = "%~%";
		$pattern[22]	 = "%\[%";
		$pattern[23]	 = "%\]%";
		$pattern[24]	 = "%\*%";
		$pattern[25]	 = "%&%";
		$sanitizedParam	 = preg_replace($pattern, "", $param);
		return $sanitizedParam;
	}

	static function sanitizedURL($param)
	{
		$pattern[0]		 = "%,%";
		$pattern[1]		 = "%\(%";
		$pattern[2]		 = "%\)%";
		$pattern[3]		 = "%\{%";
		$pattern[4]		 = "%\}%";
		$pattern[5]		 = "%<%";
		$pattern[6]		 = "%>%";
		$pattern[7]		 = "%`%";
		$pattern[8]		 = "%!%";
		$pattern[9]		 = "%\\$%";
		$pattern[10]	 = "%\%%";
		$pattern[11]	 = "%\^%";
		$pattern[12]	 = "%\+%";
		$pattern[13]	 = "%\|%";
		$pattern[14]	 = "%\\\%";
		$pattern[15]	 = "%'%";
		$pattern[16]	 = "%\"%";
		$pattern[17]	 = "%;%";
		$pattern[18]	 = "%~%";
		$pattern[19]	 = "%\[%";
		$pattern[20]	 = "%\]%";
		$pattern[21]	 = "%\*%";
		$sanitizedParam	 = preg_replace($pattern, "", $param);
		return $sanitizedParam;
	}

	function calculateChecksum($all)
	{
		$secret		 = Yii::app()->zaakpay->secret_key;
		$checksum	 = hash_hmac('sha256', $all, $secret);
		return $checksum;
	}

	public function getTxnStatus($orderid)
	{

		$zaak = new Zaakpay();

		$mid	 = $zaak->merchant_id;
		$params	 = ['merchantIdentifier' => $mid, 'orderId' => $orderid, 'mode' => $zaak->mode];
		$str	 = Zaakpay::paramString($params);

		$checksum			 = $zaak->calculateChecksum($str);
		$params['checksum']	 = $checksum;
		$url				 = $zaak->status_query_url;
		//var_dump($params);
		// echo $str . "<br>";
		$outputXmlObject	 = $zaak->callApi($params, $url);
		$resJson			 = json_encode($outputXmlObject);
		$resArr				 = json_decode($resJson, true);
		$responseArr		 = $resArr['response_element'];
		$recd_checksum		 = $responseArr['checksum'];
		// var_dump($responseArr);
		$all				 = Zaakpay::paramString($responseArr);
		$isValidChecksum	 = Zaakpay::verifyChecksum($recd_checksum, $all);

		$responseArr['flag'] = false;
		if ($isValidChecksum == '1')
		{
			$responseArr['flag'] = true;
		}
		return $responseArr;
	}

	public function processResponse($responseArr = '')
	{


		// '170331195137034''100''The transaction was completed successfully.''33100''401288''CH101'
//        array(7) (
//  [orderId] => (string) 170405193218014
//  [responseCode] => (string) 100
//  [responseDescription] => (string) The transaction was completed successfully.
//  [checksum] => (string) fcd3ce3f9af79a15f0ac4c3aa63466850985f83f4e113f99b4fb6415e5aad2bb
//  [amount] => (string) 33100
//  [paymentMethod] => (string) 401288
//  [cardhashid] => (string) CH101
//)
//        $responseArr = [
//            'orderId' => '170405193218014',
//            'responseCode' => '100',
//            'responseDescription' => 'The transaction was completed successfully. ',
//            'checksum' => 'fcd3ce3f9af79a15f0ac4c3aa63466850985f83f4e113f99b4fb6415e5aad2bb',
//            'amount' => '33100',
//            'paymentMethod' => '401288',
//            'cardhashid' => 'CH101',
//        ];
		////////////////////////////obsolute//////////////////////////////////////

		$recd_checksum	 = $responseArr['checksum'];
		$all			 = Zaakpay::paramStringForPost($responseArr);

		$isValidChecksum = Zaakpay::verifyChecksum($recd_checksum, $all);

		$adtCode				 = $responseArr['orderId'];
		$resCode				 = $responseArr['responseCode'];
		$accTransDetailsModel	 = PaymentGateway::model()->getByCode($adtCode);

		$result						 = [];
		$result['bkid']				 = $accTransDetailsModel->apg_booking_id;
		$result['tinfo']			 = $adtCode;
		$response					 = json_encode($responseArr);
		$result['IS_CHECKSUM_VALID'] = 'N';
		if ($isValidChecksum === 1)
		{
			$result['IS_CHECKSUM_VALID'] = 'Y';
			if (in_array($resCode, ['100', '601', '228', '232']) && $accTransDetailsModel)
			{
				$result['success'] = true;

				$onlinePayment = 1;
				$accTransDetailsModel->successTransaction($response, 1);
			}
			else
			{
				$result['success'] = false;
				$accTransDetailsModel->udpdateResponseByCode($response, 2);
				if ($accTransDetailsModel)
				{
					$params['blg_ref_id'] = $accTransDetailsModel->apg_id;
					BookingLog::model()->createLog($accTransDetailsModel->apg_booking_id, "Online payment failed ({$accTransDetailsModel->getPaymentType()} - {$accTransDetailsModel->apg_code})", UserInfo::getInstance(), BookingLog::PAYMENT_FAILED, '', $params);
				}
			}
		}
		return $result;
	}

	public function initiateTxnRefund($params)
	{

		$zaak = new Zaakpay();

		$str				 = Zaakpay::paramString($params);
		$params['checksum']	 = $zaak->calculateChecksum($str);
		$url				 = $zaak->refund_url;
		$outputXmlObject	 = $zaak->callApi($params, $url);
		$resArr				 = json_decode(json_encode($outputXmlObject), true);
		$responseArr		 = $resArr['response_element'];




//184	Update Desired blank.//
//185	Update Desired not Valid.//
//186	Update Reason blank.//
//187	Update Reason Not Valid.//
//189	Checksum was blank.//
//190	OrderId either not Processed or Rejected.
//201	Transaction cannot be refunded.
//203	Transaction status could not be updated try again.
//229	Transaction cannot be captured.
//230	Transaction Refund Initiated
//242	Transaction captured successfully.
//243	Transaction cancelled successfully.
//245	Transaction Partial Refund Initiated







		return $responseArr;
	}

	public function callApi($arrFields, $url)
	{

//        array(6) (
//  [merchantIdentifier] => (string) c829f359372b4bf68c6601c16923fc94
//  [orderId] => (string) 170416085526009
//  [mode] => (int) 0
//  [updateDesired] => (string) 14
//  [updateReason] => (string) Refund of 500
//  [checksum] => (string) 7b6c25ab51deafe55587deaea422c590c0803bf8f83ec84b9d8fdf612f4d09fe
//)


		$fieldStr = '';
		foreach ($arrFields as $key => $val)
		{
			$fieldStr .= '&' . $key . '=' . $val;
		}
		$fields = ltrim($fieldStr, '&');
		if (!function_exists('curl_init'))
		{
			die('Sorry cURL is not installed!');
			error_log("curl check");
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$outputXml		 = curl_exec($ch);
		curl_close($ch);
		// error_log("The response received is = " . $outputXml);
		$outputXmlObject = simplexml_load_string($outputXml);
		return $outputXmlObject;
	}

	public function paramString($params = [])
	{
		//$param=$_POST;
		unset($params['checksum']);
		// unset($params['amount']);


		$all = '';
		foreach ($params as $key => $value)
		{
			if ($key != 'checksum')
			{
				$all .= "'";
				if ($key == 'returnUrl')
				{
					$all .= Zaakpay::sanitizedURL($value);
				}
				else
				{
					$all .= Zaakpay::sanitizedParam($value);
				}
				$all .= "'";
			}
		}
		return $all;
	}

	public function paramStringForPost($params = [])
	{
		//$param=$_POST;
		unset($params['checksum']);
		$all = '';
		foreach ($params as $key => $value)
		{
			if ($key != 'checksum')
			{
				$all .= "'" . $value . "'";
			}
		}
		return $all;
	}

	public function updateTxnStatus($tCode, $model)
	{
		$responseArr = Zaakpay::getTxnStatus($tCode);



//        array(6) (
//  [merchantid] => (string) c829f359372b4bf68c6601c16923fc94
//  [orderid] => (string) 170417131943046
//  [responsecode] => (string) 228
//  [description] => (string) Transaction has been captured.
//  [checksum] => (string) c4fe9ab3c072b83cab17f5612fb92be3738106de2c7bc466fdcb16538a21e85d
//  [flag] => (bool) true
//)
//
//        array(6) (
//  [merchantid] => (string) c829f359372b4bf68c6601c16923fc94
//  [orderid] => (string) 170417122934039
//  [responsecode] => (string) 190
//  [description] => (string) OrderId either not processed or rejected.
//  [checksum] => (string) c0c11efb19f54aa66815fc8413582212bb9aaf5ba0bfc86cae0e08f9fa6b1ff2
//  [flag] => (bool) true
//)
//





		$recd_checksum	 = $responseArr['checksum'];
		$all			 = Zaakpay::paramStringForPost($responseArr);

		$isValidChecksum = Zaakpay::verifyChecksum($recd_checksum, $all);

		$transCode					 = $responseArr['orderid'];
		$resCode					 = $responseArr['responsecode'];
		//$transModel = PaymentGateway::model()->getByCode($transCode);
		$result						 = [];
		$result['bkid']				 = $model->apg_booking_id;
		$result['tinfo']			 = $transCode;
		$response					 = json_encode($responseArr);
		$result['IS_CHECKSUM_VALID'] = 'N';
		$refundSuccArr				 = Yii::app()->zaakpay->successRefundArr;
		$successTransArr			 = Yii::app()->zaakpay->successTransArr;
		$failedTransArr				 = Yii::app()->zaakpay->failedTransArr;
		$responseArr['success']		 = false;
		$responseArr['txnType']		 = '';
		if ($model)
		{
			if ($model->apg_mode == '1')
			{
				$responseArr['txnType'] = 'Refund';
				if ($isValidChecksum == '1')
				{
					$result['IS_CHECKSUM_VALID'] = 'Y';
					if (in_array($responseArr['responsecode'], $refundSuccArr))
					{
						//$result = Booking::model()->updateAccRefund($transCode, $response);

						$result				 = Booking::model()->updateAccRefund($model->apg_code, $response, BookingLog::Admin, Yii::app()->user->getId());
						$arrRefundedModels[] = $model;
						AccountTransactions::model()->addRefundTransaction($arrRefundedModels, 0, [], $model->apg_ref_id);

						$responseArr['success'] = true;
					}
					if (in_array($responseArr['responsecode'], $failedTransArr))
					{

						$responseStatusArr			 = Zaakpay::getTxnStatus($transCode);
						$responseArr['description']	 = $responseArr['description'] . ' ' . $responseStatusArr['description'];
						$model->udpdateResponseByCode($response, 2);
						$params['blg_ref_id']		 = $model->apg_id;
						BookingLog::model()->createLog($model->apg_booking_id, "Refund process failed ({$model->getPaymentType()} - {$model->apg_code})", UserInfo::model(), BookingLog::REFUND_PROCESS_FAILED, '', $params);
					}
				}
				else
				{
					$responseArr['description']	 = 'Some error in transaction.Please Try again.';
					$response					 = json_encode($responseArr);
					$model->udpdateResponseByCode($response, AccountTransDetails::TXN_STATUS_FAILED);
					if ($model)
					{
						$params['blg_ref_id'] = $model->apg_id;
						BookingLog::model()->createLog($model->apg_booking_id, "Refund process failed ({$model->getPaymentType()} - {$model->apg_code})", UserInfo::model(), BookingLog::REFUND_PROCESS_FAILED, '', $params);
					}
				}
			}

			if ($model->apg_mode == '2')
			{
				$responseArr['txnType'] = 'Payment';
				if (in_array($resCode, $successTransArr))
				{
					$result = $model->successTransaction($response, 1);

					$responseArr['success'] = true;
				} if (in_array($responseArr['responsecode'], $failedTransArr))
				{

					$model->udpdateResponseByCode($response, 2);

					$params['blg_ref_id'] = $model->apg_id;
					BookingLog::model()->createLog($model->apg_booking_id, "Online payment failed ({$model->getPaymentType()} - {$model->apg_code})", UserInfo::model(), BookingLog::PAYMENT_FAILED, '', $params);
				}
			}

			return $responseArr;
		}
	}

}
