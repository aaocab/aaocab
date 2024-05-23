<?php

class Freecharge extends CComponent
{

	public $api_live = false;
	public $merchant_id;
	public $merchantKey;
	public $secret_key;
	public $refund_url;
	public $public_key_id;
	public $public_key;
	public $status_query_url;
	public $txn_url;
	public $request_url;
	public $view	 = 'freecharge';

	function __construct()
	{
		$this->init();
	}

	public function init()
	{
		$domain			 = 'https://checkout-sandbox.freecharge.in/';
		$config			 = Yii::app()->params['freecharge'];
		$this->api_live	 = $config['api_live'];
		if ($this->api_live == true)
		{
			$domain = 'https://checkout.freecharge.in/';
		}

		$this->txn_url		 = $domain . 'api/v1/co/pay/init';
		$this->refund_url	 = $domain . 'api/v1/co/refund';
		$this->request_url	 = $domain . 'api/v1/co/transaction/status';
		$this->merchant_id	 = $config['merchant_id'];
		$this->merchantKey	 = $config['merchantKey'];
	}

	public function getTxnStatus($orderid)
	{
		$transType = [
			'1'	 => 'CANCELLATION_REFUND',
			'2'	 => 'CUSTOMER_PAYMENT'
		];

		$frc		 = new Freecharge();
		$transModel	 = PaymentGateway::model()->getByCode($orderid);

		$transParam					 = [];
		$transParam['merchantId']	 = $frc->merchant_id;
		$transParam['merchantTxnId'] = $orderid;
		$transParam['txnType']		 = $transType[$transModel->apg_mode];
		// $transParam['txnId'] = $trdArr['txnId']; //'XBFCyc9p6UVIH7_170327123814007_1';
		$paramListArr				 = Freecharge::getParams($transParam);

		$url			 = $frc->request_url;
		$resArr			 = $frc->callAPI($paramListArr, $url);
		$result			 = [];
		$result['flag']	 = false;
		if ($resArr['checksum'])
		{
			$isValidChecksum = Freecharge::verifyChecksum($resArr);
			if ($orderid == $resArr['merchantTxnId'] && $isValidChecksum == '1')
			{
				$result = $resArr;

				$result['flag'] = true;
			}
		}
		$result['txnType'] = $transParam['txnType'];
		return $result;
	}

	public function processResponse($tranResult, $responseArr = [])
	{
		$result = $this->parseResponse();

		return $result;
	}

	public function parseResponse($responseArr)
	{

		if (isset($responseArr[ptpid]))
		{
			unset($responseArr[ptpid]);
		}
		$tranResult		 = Yii::app()->request->getParam('trns');
		$isValidChecksum = Freecharge::verifyChecksum($responseArr);
		$response		 = json_encode($responseArr);
		$adtCode		 = $responseArr['merchantTxnId'];
		$resStatus		 = $responseArr['status'];
		$txnid			 = $responseArr['txnId'];
		$errorMsg		 = (isset($responseArr['errorMessage']) && $responseArr['errorMessage'] != '') ? $responseArr['errorMessage'] : '';
		$respCode		 = (isset($responseArr['errorCode']) && $responseArr['errorCode'] != '') ? $responseArr['errorCode'] : '';

		$statusCode = 0;
		if ($isValidChecksum == 1)
		{
			if (strtolower($resStatus) == 'completed')
			{
				$statusCode = 1;
			}
			if (strtolower($resStatus) == 'failed')
			{
				$statusCode = 2;
			}
		}
		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_FREECHARGE;
		$payResponse->transaction_code	 = $adtCode;
		$payResponse->response_code		 = $respCode;
		$payResponse->payment_code		 = $txnid;
		$payResponse->response			 = $response;
		$payResponse->message			 = trim($resStatus . ' ' . $errorMsg);
		$payResponse->payment_status	 = $statusCode;
		return $payResponse;
	}

	public static function getParams($json_decode)
	{

		$merchantKey	 = Yii::app()->freecharge->merchantKey;
// Remove null and empty values from the json and sort keys in alphabetical order
		$sanitizedInput	 = Freecharge::sanitizeInput($json_decode);
// Append merchant Key
		$serializedObj	 = $sanitizedInput . $merchantKey;
// Calculate Checksum for the serialized string
		$checksum		 = Freecharge::calculateChecksum($serializedObj);

		$json_decode['checksum'] = $checksum;
		return $json_decode;
	}

	private static function calculateChecksum($serializedObj)
	{

		$checksum = hash('sha256', $serializedObj, false);
		return $checksum;
	}

	private static function recur_ksort(&$array)
	{
// Sort json object keys alphabetically recursively
		foreach ($array as &$value)
		{
			if (is_array($value))
			{
				Freecharge::recur_ksort($value);
			}
		}
		return ksort($array);
	}

	private static function sanitizeInput(array $json_decode)
	{
		$reqWithoutNull = array_filter($json_decode, function ($k) {
			if (is_null($k))
			{
				return false;
			}
			if (is_array($k))
			{
				return true;
			}
			return !(trim($k) == "");
		});
		Freecharge::recur_ksort($reqWithoutNull);
		$flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
		return json_encode($reqWithoutNull, $flags);
	}

	static function verifyChecksum($responseArr = [])
	{

		$resValArr = [];
		foreach ($responseArr as $key => $value)
		{
			if ($key != 'checksum')
			{
				$resValArr[$key] = $value;
			}
		}
		$str			 = Freecharge::getParams($resValArr);
		$cal_checksum	 = $str['checksum'];
		$recd_checksum	 = $responseArr['checksum'];
		$bool			 = 0;
		if ($recd_checksum == $cal_checksum)
		{
			$bool = 1;
		}
		return $bool;
	}

	public function callAPI($requestParamList, $apiURL)
	{

		$JsonData			 = json_encode($requestParamList, true);
		$postData			 = $JsonData;
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
		$responseParamList1	 = json_decode($jsonResponse, true);
		return $responseParamList1;
	}

	public function updateTxnStatus($transCode, $model)
	{
		$responseArr = $this->getTxnStatus($transCode);
		//$transModel = PaymentGateway::model()->getByCode($transCode);
		$result		 = false;
		if ($responseArr['txnType'] == 'CUSTOMER_PAYMENT')
		{
			$response = json_encode($responseArr);
			if ($responseArr['flag'] && ($responseArr['status'] == 'INITIATED' || $responseArr['status'] == 'SUCCESS' || strtolower($responseArr['status']) == 'completed'))
			{
				//$result = Booking::model()->updateAcctAdvance($transCode, $response);
				$result = $model->successTransaction($response, 1, Accounting::AT_BOOKING);
			}
			else
			{
				$result = false;
				$model->udpdateResponseByCode($response, 2);
				if ($model)
				{
					$params['blg_ref_id'] = $model->apg_id;
					BookingLog::model()->createLog($model->apg_booking_id, "Online payment failed ({$model->getPaymentType()} - {$model->apg_code})", UserInfo::getInstance(), BookingLog::PAYMENT_FAILED, '', $params);
				}
			}
		}
		if ($responseArr['txnType'] == 'CANCELLATION_REFUND')
		{
			if ($responseArr['flag'] && ($responseArr['status'] == 'INITIATED' || $responseArr['status'] == 'SUCCESS' || strtolower($responseArr['status']) == 'completed'))
			{
				//$result = Booking::model()->updateAccRefund($transCode, $response);

				$result				 = Booking::model()->updateAccRefund($model->apg_code, $response, BookingLog::Admin, Yii::app()->user->getId());
				$arrRefundedModels[] = $model;
				AccountTransactions::model()->addRefundTransaction($arrRefundedModels, 0, [], $model->apg_ref_id);
			}
			else
			{
				$result = false;
				$model->udpdateResponseByCode($response, 2);
				if ($model)
				{
					$params['blg_ref_id'] = $model->apg_id;
					BookingLog::model()->createLog($model->apg_booking_id, "Refund process failed ({$model->getPaymentType()} - {$model->apg_code})", UserInfo::getInstance(), BookingLog::REFUND_PROCESS_FAILED, '', $params);
				}
			}
		}
		$responseArr['success'] = $result;
		return $responseArr;
	}

	public function initiateRequest($payRequest)
	{

		$frc				 = new Freecharge();
		$frc_post			 = $frc->getParams(
				array(
					'merchantId'	 => $frc->merchant_id,
					'merchantTxnId'	 => $payRequest->transaction_code,
					'amount'		 => $payRequest->trans_amount,
					'channel'		 => 'WEB',
//					'surl'			 => YII::app()->createAbsoluteUrl('freecharge/response', ['trns' => 'succ']),
					'surl'			 => YII::app()->createAbsoluteUrl('freecharge/response'),
					'currency'		 => 'INR',
//					'furl'			 => YII::app()->createAbsoluteUrl('freecharge/response', ['trns' => 'fail']),
					'furl'			 => YII::app()->createAbsoluteUrl('freecharge/response'),
					'productInfo'	 => $payRequest->description,
					'pg'			 => 'CC',
					'customerName'	 => $payRequest->name,
					'mobile'		 => $payRequest->mobile,
					'email'			 => $payRequest->email
		));
		$frc_post['txn_url'] = $frc->txn_url;
		return $frc_post;
	}

	public function getPaymentStatus($pgModel)
	{
		$transcode	 = $pgModel->apg_code;
		$responseArr = $this->getTxnStatus($transcode);



		$statusCode = 0;
		if ($responseArr['flag'] && ($responseArr['status'] == 'INITIATED' || $responseArr['status'] == 'SUCCESS' || strtolower($responseArr['status']) == 'completed'))
		{
			$statusCode = 1;
		}
		else
		{
			$statusCode = 2;
		}
		$adtCode	 = $responseArr['merchantTxnId'];
		$resStatus	 = $responseArr['status'];
		$txnid		 = $responseArr['txnId'];
		$errorMsg	 = (isset($responseArr['errorMessage']) && $responseArr['errorMessage'] != '') ? $responseArr['errorMessage'] : '';
		$respCode	 = (isset($responseArr['errorCode']) && $responseArr['errorCode'] != '') ? $responseArr['errorCode'] : '';


		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_FREECHARGE;
		$payResponse->transaction_code	 = $adtCode;
		$payResponse->response_code		 = $respCode;
		$payResponse->payment_code		 = $txnid;
		$payResponse->response			 = json_encode($responseArr);
		$payResponse->message			 = trim($resStatus . ' ' . $errorMsg);
		$payResponse->payment_status	 = $statusCode;
		return $payResponse;
	}

	public function refund($transModel)
	{
		$amount								 = -1 * $transModel->apg_amount;
		$paramList							 = [];
		$paramList['merchantId']			 = Yii::app()->freecharge->merchant_id;
		$paramList['refundMerchantTxnId']	 = $transModel->apg_code;

		$paramList['merchantTxnId'] = $transModel->apg_merchant_ref_id;


		$paramList['refundAmount']	 = $amount . '';
		$paramListArr				 = Freecharge::getParams($paramList);
		$url						 = Yii::app()->freecharge->refund_url;
		$responseArr				 = Yii::app()->freecharge->callAPI($paramListArr, $url);
		$checksumVerified			 = Yii::app()->freecharge->verifyChecksum($responseArr);


		$statusCode = 0;
		if (($responseArr['status'] == 'INITIATED' || $responseArr['status'] == 'SUCCESS' || strtolower($responseArr['status']) == 'completed' ) && $responseArr['refundedAmount'] > 0)
		{
			$statusCode = 1;
		}
		else
		{
			$statusCode = 2;
		}
		$adtCode	 = $responseArr['merchantTxnId'];
		$resStatus	 = $responseArr['status'];
		$txnid		 = $responseArr['txnId'];
		$errorMsg	 = (isset($responseArr['errorMessage']) && $responseArr['errorMessage'] != '') ? $responseArr['errorMessage'] : '';
		$respCode	 = (isset($responseArr['errorCode']) && $responseArr['errorCode'] != '') ? $responseArr['errorCode'] : '';


		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_FREECHARGE;
		$payResponse->transaction_code	 = $adtCode;
		$payResponse->response_code		 = $respCode;
		$payResponse->payment_code		 = $txnid;
		$payResponse->response			 = json_encode($responseArr);
		$payResponse->message			 = trim($resStatus . ' ' . $errorMsg);
		$payResponse->payment_status	 = $statusCode;
		return $payResponse;
	}

}
