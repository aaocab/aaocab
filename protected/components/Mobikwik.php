<?php

class Mobikwik extends CComponent
{

	public $api_live = false;
	public $merchant_id;
	public $secret_key;
	public $refund_url;
	public $status_query_url;
	public $txn_url;
	public $view	 = 'mobikwik';

	function __construct()
	{
		$this->init();
	}

	public function init()
	{
		$domain			 = 'test';
		$config			 = Yii::app()->params['mobikwik'];
		$this->api_live	 = $config['api_live'];

		if ($this->api_live == true)
		{
			$domain = 'walletapi';
		}

		$this->secret_key		 = $config['secret_key'];
		$this->merchant_id		 = $config['merchant_id'];
		$this->txn_url			 = 'https://' . $domain . '.mobikwik.com/wallet';
		$this->status_query_url	 = 'https://' . $domain . '.mobikwik.com/checkstatus';
		$this->refund_url		 = 'https://' . $domain . '.mobikwik.com/walletrefund';
	}

	public function getParams($params = array())
	{

		$config		 = Yii::app()->params['mobikwik'];
		$secret_key	 = $config['secret_key'];
		$mid		 = $config['merchant_id'];
		$str		 = "'" . $params['cell'] . "''" . $params['email'] . "''" . $params['amount'] . "''" . $params['orderid'] . "''" . $params['redirecturl'] . "''" . $mid . "'";
		$checksum	 = $this->calculateChecksum($secret_key, $str);
		$mob_post	 = $params;
		foreach ($mob_post as $key => $value)
		{
			if ($key != 'txn_url')
			{
				if ($key == 'redirecturl')
				{
					$mob_post[$key] = $this->sanitizedURL($value);
				}
				else
				{
					$mob_post[$key] = $this->sanitizedParam($value);
				}
			}
		}
		$mob_post['mid']			 = $mid;
		$mob_post['version']		 = 2;
		$mob_post['merchantname']	 = 'GozoCabs';
		$mob_post['checksum']		 = $checksum;
		return $mob_post;
	}

	static function verifyChecksum($checksum, $all)
	{
		$config	 = Yii::app()->params['mobikwik'];
		$secret	 = $config['secret_key'];

		$cal_checksum = Yii::app()->mobikwik->calculateChecksum($secret, $all);

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

	function calculateChecksum($secret_key, $all)
	{
		$hash		 = hash_hmac('sha256', $all, $secret_key);
		$checksum	 = $hash;
		return $checksum;
	}

	static function getAllParams()
	{
		$mob_post = $_POST;
		foreach ($mob_post as $key => $value)
		{
			if ($key != 'txn_url')
			{
				if ($key == 'redirecturl')
				{
					$mob_post[$key] = Yii::app()->mobikwik->sanitizedURL($value);
				}
				else
				{
					$mob_post[$key] = Yii::app()->mobikwik->sanitizedParam($value);
				}
			}
		}
		return $mob_post;
	}

	public function getTxnStatus($orderid)
	{

		$config	 = Yii::app()->params['mobikwik'];
		$mid	 = $config['merchant_id'];
		$mob	 = new Mobikwik();
		//$status_query_url = $mob->status_query_url;

		$str = "'" . $mid . "''" . $orderid . "'";

		$secret_key	 = $config['secret_key'];
		$checksum	 = $mob->calculateChecksum($secret_key, $str);
		$mob_post	 = ['mid' => $mid, 'orderid' => $orderid];
		foreach ($mob_post as $key => $value)
		{
			$mob_post[$key] = $mob->sanitizedParam($value);
		}
		$mob_post['checksum']	 = $checksum;
		$mob_post['ver']		 = '2';
		$url					 = $mob->status_query_url;
		$outputXmlObject		 = $mob->callApi($mob_post, $url);
		$recievedChecksum		 = $mob->validateChecksumMobikwik(
				$mob->sanitizedParam($outputXmlObject->statuscode), $mob->sanitizedParam($outputXmlObject->orderid), $mob->sanitizedParam($outputXmlObject->refid), $mob->sanitizedParam($outputXmlObject->amount), $mob->sanitizedParam($outputXmlObject->statusmessage), $mob->sanitizedParam($outputXmlObject->ordertype)
		);

		$result			 = [];
		$result['flag']	 = false;
		if (($orderid == $outputXmlObject->orderid) &&
				($outputXmlObject->checksum == $recievedChecksum))
		{
			$result			 = json_decode(json_encode((array) $outputXmlObject), TRUE);
			$result['flag']	 = true;
		}
		return $result;
	}

	function validateChecksumMobikwik($statuscode, $orderid, $refid, $amount, $statusmessage, $ordertype, $WorkingKey = '')
	{
		$checksum_string = "'{$statuscode}''{$orderid}''{$refid}''{$amount}''{$statusmessage}''{$ordertype}'";
		$config			 = Yii::app()->params['mobikwik'];
		$secret_key		 = $config['secret_key'];
		$checksum		 = Yii::app()->mobikwik->calculateChecksum($secret_key, $checksum_string);
		return $checksum;
	}

	public function processResponse($responseArr)
	{

		////////////////////////obsolute //////////////////////////////////

		$response = json_encode($responseArr);

		/* {
		  "amount": "207.00",
		  "statusmessage": "The payment has been successfully collected",
		  "checksum": "8edc5fe1d48757db3d9e573ed76a38a8b800eb2dd6fc7709ab03c7afd6321346",
		  "mid": "MBK9002",
		  "orderid": "170314180106037",
		  "statuscode": "0",
		  "refid": "1803376834"
		  } */
		$recd_checksum	 = $responseArr['checksum'];
		$mid			 = Yii::app()->mobikwik->merchant_id;

		$str = "'" . $responseArr['statuscode'] . "''" . $responseArr['orderid'] . "''" . $responseArr['amount'] . "''" . $responseArr['statusmessage'] . "''" . $mid . "''" . $responseArr['refid'] . "'";

		$adtCode = $responseArr['orderid'];
		$resCode = $responseArr['statuscode'];

		$accTransDetailsModel		 = PaymentGateway::model()->getByCode($adtCode);
		$result						 = [];
		$result['bkid']				 = $accTransDetailsModel->apg_booking_id;
		$result['tinfo']			 = $adtCode;
		$result['IS_CHECKSUM_VALID'] = 'N';

		if ($resCode == '0' && $accTransDetailsModel)
		{
			$result['success']	 = true;
			//verify checksum
			$isValidChecksum	 = Yii::app()->mobikwik->verifyChecksum($recd_checksum, $str);
			//if checksum matched
			if ($isValidChecksum === 1)
			{
				$result['IS_CHECKSUM_VALID'] = 'Y';
				$onlinePayment				 = 1;
				//Booking::model()->updateAcctAdvance($adtCode, $response, BookingLog::Consumers, Yii::app()->user->getId(), $onlinePayment);
				$accTransDetailsModel->successTransaction($response, 1, Accounting::AT_BOOKING);
			}
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
		return $result;
	}

	public function parseResponse($responseArr)
	{



		$response = json_encode($responseArr);

		/* {
		  "amount": "207.00",
		  "statusmessage": "The payment has been successfully collected",
		  "checksum": "8edc5fe1d48757db3d9e573ed76a38a8b800eb2dd6fc7709ab03c7afd6321346",
		  "mid": "MBK9002",
		  "orderid": "170314180106037",
		  "statuscode": "0",
		  "refid": "1803376834"
		  } */
		$recd_checksum		 = $responseArr['checksum'];
		$mid				 = Yii::app()->mobikwik->merchant_id;
		$str				 = "'" . $responseArr['statuscode'] . "''" . $responseArr['orderid'] . "''" . $responseArr['amount'] . "''" . $responseArr['statusmessage'] . "''" . $mid . "''" . $responseArr['refid'] . "'";
		$isValidChecksum	 = Yii::app()->mobikwik->verifyChecksum($recd_checksum, $str);
		$adtCode			 = $responseArr['orderid'];
		$resCode			 = $responseArr['statuscode'];
		$response_message	 = trim($responseArr['status'] . ' ' . $responseArr['statusmessage']);

		$result				 = [];
		$result['success']	 = true;
		//verify checksum


		$statusCode = 0;
		if ($isValidChecksum == 1)
		{
			if ($resCode == '0')
			{
				$statusCode = 1;
			}
			if ($resCode == '1')
			{
				$statusCode = 2;
			}
		}
		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_MOBIKWIK;
		$payResponse->transaction_code	 = $adtCode;
		$payResponse->response_code		 = $resCode;
		$payResponse->payment_code		 = $responseArr['refid'];
		$payResponse->response			 = $response;
		$payResponse->message			 = $response_message;
		$payResponse->payment_status	 = $statusCode;
		return $payResponse;
	}

	public function initiateTxnRefund($rArr)
	{
		$checksum_string	 = "'" . $rArr['mid'] . "''" . $rArr['txid'] . "''" . $rArr['amount'] . "'";
		$config				 = Yii::app()->params['mobikwik'];
		$secret_key			 = $config['secret_key'];
		$checksum			 = Yii::app()->mobikwik->calculateChecksum($secret_key, $checksum_string);
		$rArr['checksum']	 = $checksum;
		$url				 = Yii::app()->mobikwik->refund_url;
		$outputXmlObject	 = Yii::app()->mobikwik->callApi($rArr, $url);
		$result				 = [];
		if ($rArr['txid'] == $outputXmlObject->txid)
		{
			$result			 = json_decode(json_encode((array) $outputXmlObject), TRUE);
			$result['flag']	 = true;
		}
		return $result;
	}

	public function refund($transModel)
	{

		$paramList	 = array();
		$amount		 = -1 * $transModel->apg_amount;


		$paramList['mid']		 = Yii::app()->mobikwik->merchant_id;
		$paramList['txid']		 = PaymentGateway::model()->getCodebyid($transModel->apg_ref_id);
		$paramList['ispartial']	 = 'yes';
		$paramList['amount']	 = trim($amount . '');


		$responseArr = Yii::app()->mobikwik->initiateTxnRefund($paramList);

		$statusCode = 0;
		if ($responseArr['flag'] && $responseArr['statuscode'] == 0)
		{
			$statusCode = 1;
		}
		else
		{
			$statusCode = 2;
		}
		$adtCode	 = $responseArr['orderid'];
		$resStatus	 = trim($responseArr['statusmessage'] . ' ' . $responseArr['status']);
		$txnid		 = $responseArr['refid'];

		$respCode = $responseArr['statuscode'];


		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_MOBIKWIK;
		$payResponse->transaction_code	 = $adtCode;
		$payResponse->response_code		 = $respCode;
		$payResponse->payment_code		 = $txnid;
		$payResponse->response			 = json_encode($responseArr);
		$payResponse->message			 = $resStatus;
		$payResponse->payment_status	 = $statusCode;
		return $payResponse;
	}

	public function callApi($arrFields, $url)
	{
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

	public function initiateRequest($payRequest)
	{
		/*        @var $payRequest PaymentRequest   */

		$mbkPayment	 = new Mobikwik();
		$mbk_post	 = $mbkPayment->getParams(
				array(
					'amount'		 => round($payRequest->trans_amount),
					'orderid'		 => $payRequest->transaction_code,
					'redirecturl'	 => YII::app()->createAbsoluteUrl('mobikwik/response'),
					'cell'			 => $payRequest->mobile,
					'email'			 => $payRequest->email,
					'txn_url'		 => $mbkPayment->txn_url
		));
		return $mbk_post;
	}

	public function getPaymentStatus($pgModel)
	{
		$transcode = $pgModel->apg_code;
		if ($pgModel->apg_mode == 2)
		{
			$transcode	 = $pgModel->apg_code;
//			if ($pgModel->apg_mode == 1)
//		{
//				$txnid			 = $pgModel->apg_merchant_ref_id;
//				$apgModel		 = PaymentGateway::model()->getByTxnId($txnid, 2);
//				$oldTransModel	 = $apgModel[0];
//				$transcode	 = $oldTransModel->apg_code;
//		}
			$responseArr = $this->getTxnStatus($transcode);
			$statusCode	 = 0;
			if ($responseArr['flag'] && $responseArr['statuscode'] == 0)
			{
				$statusCode = 1;
			}
			else
			{
				$statusCode = 2;
			}
			$adtCode	 = $responseArr['orderid'];
			$resStatus	 = $responseArr['statusmessage'];
			$txnid		 = $responseArr['refid'];

			$respCode = $responseArr['statuscode'];


			$payResponse					 = new PaymentResponse();
			$payResponse->payment_type		 = PaymentType::TYPE_MOBIKWIK;
			$payResponse->transaction_code	 = $adtCode;
			$payResponse->response_code		 = $respCode;
			$payResponse->payment_code		 = $txnid;
			$payResponse->response			 = json_encode($responseArr);
			$payResponse->message			 = $resStatus;
			$payResponse->payment_status	 = $statusCode;
			return $payResponse;
		}
		return false;
	}

}
