<?php

class EaseBuzz extends CComponent
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
	public $domain_url;
	public $boltjsSrc;
	public $view	 = 'easebuzz';
	public $env;

	public function init()
	{

		$domain2 = 'pay.easebuzz.in';
		$domain1 = 'https://test';

		$config			 = Yii::app()->params['easebuzz'];
		$this->api_live	 = $config['api_live'];

		if ($this->api_live == true)
		{
			$domain1 = 'https://';
		}
		$this->env			 = ($this->api_live) ? 'prod' : 'test';
		$domain				 = $domain1 . $domain2;
		$this->domain_url	 = $domain;
		$this->merchant_key	 = $config['merchant_key'];
		$this->merchant_salt = $config['merchant_salt'];

		$this->txn_url			 = $domain . '/payment/initiateLink';
		$this->refund_url		 = $domain1 . 'dashboard.easebuzz.in/transaction/v2/refund';
		$this->status_query_url	 = $domain1 . 'dashboard.easebuzz.in/transaction/v1/retrieve';
		$this->refund_status_url = $domain1 . 'dashboard.easebuzz.in/refund/v1/retrieve';
	}

	public function getChecksumFromArray($posted)
	{
		$formError		 = 0;
		$hashSequence	 = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

		$SALT = $this->merchant_salt;

		if (empty($posted['hash']) && sizeof($posted) > 0)
		{
			if (empty($posted['key']) ||
					empty($posted['txnid']) ||
					empty($posted['amount']) ||
					empty($posted['firstname']) ||
					(empty($posted['email']) && empty($posted['phone'])) ||
					empty($posted['productinfo']) ||
					empty($posted['surl']) ||
					empty($posted['furl']))
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
			}
		}
		elseif (!empty($posted['hash']))
		{
			$hash = $posted['hash'];
		}
		$posted['hash'] = $hash;
		return $posted;
	}

	public function initiateRequest($payRequest, $redirect = false)
	{
		$success						 = false;
		$paymentGateway					 = PaymentGateway::model()->getByCode($payRequest->transaction_code);
		$contactArr						 = ['phone' => $payRequest->mobile, 'email' => $payRequest->email];
		$paymentGateway->apg_pre_txn_id	 = json_encode($contactArr);
		if ($paymentGateway->save())
		{
			$success = true;
		}

		$param_list					 = array();
		// Create an array having all required parameters for creating checksum.
		$param_list['key']			 = $this->merchant_key;
		$param_list['txnid']		 = $payRequest->transaction_code;
		$param_list['productinfo']	 = preg_replace('/[^a-zA-Z0-9-\s\|\-]/i', ' - ', $payRequest->description);
		$param_list['amount']		 = $payRequest->trans_amount;
		$param_list['firstname']	 = $payRequest->name;
		$param_list['address1']		 = $payRequest->billingAddress; //
		$param_list['city']			 = $payRequest->city;
		$param_list['state']		 = $payRequest->state;
		$param_list['country']		 = $payRequest->country;
		$param_list['zipcode']		 = $payRequest->postal;
		$param_list['email']		 = $payRequest->email; //Email ID of customer
		$param_list['phone']		 = $payRequest->mobile;
		$param_list['surl']			 = YII::app()->createAbsoluteUrl('payment/response?ptpid=23');
		$param_list['furl']			 = YII::app()->createAbsoluteUrl('payment/response?ptpid=23');
		$param_list['request_flow']	 = 'SEAMLESS';
		$param_list1				 = $this->getChecksumFromArray($param_list);
		$param_list['hash']			 = $param_list1['hash'];
		$curl_result				 = $this->curlCall($this->txn_url, $param_list);
		if (!$curl_result['status'])
		{
			return $curl_result['error_desc'];
		}

		$accesskey				 = ($curl_result['status'] === 1) ? $curl_result['data'] : null;
		$param_list['action']	 = $this->domain_url . '/pay/' . $accesskey;
		$param_list['accesskey'] = $accesskey;
		$param_list['status']	 = $curl_result['status'];
		$param_list['env']		 = $this->env;
		return $param_list;
	}

	public function parseResponse($postData)
	{
		$status		 = $postData["status"];
		$firstname	 = $postData["firstname"];
		$amount		 = $postData["amount"];
		$transCode	 = $postData["txnid"];
		$posted_hash = $postData["hash"];
		$txnid		 = $postData["easepayid"];
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

		$salt				 = Yii::app()->easebuzz->merchant_salt;
		$retHashSeq			 = $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $transCode . '|' . $key;
		$response			 = json_encode($postData);
		$responseArr		 = json_decode($response, true);
		$respCode			 = $status;
		$response_message	 = $responseArr['error_Message'];
		$statusCode			 = 0;
		$statusType			 = 0;
		$hash				 = hash("sha512", $retHashSeq);
		$status				 = strtolower($status);
		if ($hash == $posted_hash)
		{
			if ($status == 'initiated')
			{
				$response_message = $status;
			}

			if ($status == 'success')
			{
				$statusCode	 = 1;
				$statusType	 = 2;
			}
			if (in_array($status, ['failure', 'usercancelled', 'dropped', 'bounced']))
			{
				$statusCode	 = 2;
				$statusType	 = 2;
			}
		}

		$payResponse						 = new PaymentResponse();
		$payResponse->payment_type			 = PaymentType::TYPE_EASEBUZZ;
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

	public function getPaymentStatus($pgModel)
	{
		$transcode = $pgModel->apg_code;

		$payResponse = '';
		if ($pgModel->apg_mode == 1)
		{
			$payResponse = $this->getRefundStatusByTranscode($transcode, $pgModel);
		}
		else
		{
			$payResponse = $this->getPaymentStatusByTranscode($transcode, $pgModel);
		}
		return $payResponse;
	}

	/** @var PaymentGateway $pgModel */
	public function getTransQueryData($pgModel)
	{
		$key			 = $this->merchant_key;
		$txnid			 = $pgModel->apg_code;
		$amount			 = number_format((float) $pgModel->apg_amount, 1, '.', '');
		$contactDetails	 = json_decode($pgModel->apg_pre_txn_id, true); // BookingUser::getBookingBillingDetails($pgModel->apg_booking_id);
		$email			 = $contactDetails['email'];
		$phone			 = $contactDetails['phone'];
		$reqData		 = [
			'key'	 => $key,
			'txnid'	 => $txnid,
			'amount' => $amount,
			'email'	 => $email,
			'phone'	 => $phone
		];
		$hash			 = $this->getPaymentStatusHashKey($reqData);
		$reqData['hash'] = $hash;
		return $reqData;
	}

	public function getRefundData($pgModel)
	{
		$refId = $pgModel->apg_ref_id;

		$oldPgModel	 = PaymentGateway::model()->findByPk($refId);
		$oldTXNID	 = $oldPgModel->apg_txn_id;

		$refundAmount = (-1 * $pgModel->apg_amount);

		$key	 = $this->merchant_key;
		$txnid	 = $pgModel->apg_code;
		$amount	 = number_format((float) $refundAmount, 1, '.', '');

		$reqData		 = [
			'key'				 => $key,
			'merchant_refund_id' => $txnid,
			'easebuzz_id'		 => $oldTXNID,
			'refund_amount'		 => $amount
		];
		$hash			 = $this->getRefundHashKey($reqData);
		$reqData['hash'] = $hash;
		return $reqData;
	}

	public function refund($pgModel)
	{

		$url		 = $this->refund_url;
		$data		 = $this->getRefundData($pgModel);
		$response	 = $this->curlCall($url, $data);
		if (!$response['status'])
		{
			$payResponse = $this->parseRefundData($response, $pgModel);
		}
		else
		{
			$payResponse = $this->getRefundStatusByTranscode($pgModel->apg_code);
		}
		return $payResponse;
	}

	public function getRefundStatusByTranscode($transcode, $pgModel = null)
	{
		if (!$pgModel)
		{
			$pgModel = PaymentGateway::model()->getByCode($transcode);
		}
		if (!$pgModel)
		{
			return false;
		}

		$oldTransModel	 = PaymentGateway::model()->findByPk($pgModel->apg_ref_id);
		$paymentId		 = $oldTransModel->apg_txn_id;
		$payResponse	 = $this->fetchRefundStatus($paymentId, $pgModel);
		return $payResponse;
	}

	public function getPaymentStatusByTranscode($transcode, $pgModel = null)
	{
		if (!$pgModel)
		{
			$pgModel = PaymentGateway::model()->getByCode($transcode);
		}
		if (!$pgModel)
		{
			return false;
		}

		$payData	 = $this->getTransQueryData($pgModel);
		$url		 = $this->status_query_url;
		$responseArr = $this->curlCall($url, http_build_query($payData));
		$resArr		 = $responseArr['msg'];
		$payResponse = $this->parseResponse($resArr);
		return $payResponse;
	}

	public function getRefundStatusData($txnid, $paymentId)
	{
		$key = $this->merchant_key;

		$reqData		 = [
			'key'				 => $key,
			'merchant_refund_id' => $txnid,
			'easebuzz_id'		 => $paymentId,
		];
		$hash			 = $this->getRefundStatusHashKey($reqData);
		$reqData['hash'] = $hash;
		return $reqData;
	}

	public function fetchRefundStatus($paymentId, $pgModel)
	{
		$transcode	 = $pgModel->apg_code;
		$data		 = $this->getRefundStatusData($transcode, $paymentId);
		$url		 = $this->refund_status_url;
		$responseArr = $this->curlCall($url, $data);
		$payResponse = $this->parseRefundData($responseArr, $pgModel);
		return $payResponse;
	}

	function getHashSequence($type)
	{
		$hash_sequence = '';
		switch ($type)
		{
			case 'paymentStatus':
				$hash_sequence = "key|txnid|amount|email|phone";

				break;
			case 'refund':
				$hash_sequence = "key|merchant_refund_id|easebuzz_id|refund_amount";

				break;
			case 'refundStatus':
				$hash_sequence = "key|easebuzz_id";

				break;

			default:
				break;
		}
		return $hash_sequence;
	}

	function getHashKey($hash_sequence, $data)
	{
		$hash_sequence_array = explode('|', $hash_sequence);
		$hash				 = null;
		foreach ($hash_sequence_array as $value)
		{
			$hash	 .= isset($data[$value]) ? $data[$value] : '';
			$hash	 .= '|';
		}
		$SALT	 = $this->merchant_salt;
		$hash	 .= $SALT;

		return strtolower(hash('sha512', $hash));
	}

	function getPaymentStatusHashKey($data)
	{
		$hash_sequence	 = $this->getHashSequence('paymentStatus');
		$hashKey		 = $this->getHashKey($hash_sequence, $data);
		return $hashKey;
	}

	function getRefundHashKey($data)
	{
		$hash_sequence	 = $this->getHashSequence('refund');
		$hashKey		 = $this->getHashKey($hash_sequence, $data);
		return $hashKey;
	}

	function getRefundStatusHashKey($data)
	{
		$hash_sequence	 = $this->getHashSequence('refundStatus');
		$hashKey		 = $this->getHashKey($hash_sequence, $data);
		return $hashKey;
	}

	function curlCall($postUrl, $toSend)
	{
		$ch = curl_init();
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		//error_reporting(E_ALL);

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $toSend);
		curl_setopt($ch, CURLOPT_URL, $postUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		$result = curl_exec($ch);

		if (curl_errno($ch))
		{
			$cURL_error	 = curl_error($ch);
			if (empty($cURL_error))
				$cURL_error	 = 'Server Error';

			return array(
				'curl_status'	 => 0,
				'error'			 => $cURL_error
			);
		}

		$result			 = trim($result);
		$result_response = json_decode($result, true);

		return $result_response;
	}

	public function parseRefundData($refundRawArr, $pgModel)
	{
		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_EASEBUZZ;
		$payResponse->transaction_code	 = $pgModel->apg_code;
		if (empty($refundRawArr['refunds']))
		{
			goto result;
		}
		$refundAmount	 = -1 * $pgModel->apg_amount;
		$resArr			 = [];
		$resArr			 = $refundRawArr['refunds'][0];
		if ($resArr['merchant_refund_id'] != $pgModel->apg_code || $resArr['refund_amount'] != $refundAmount)
		{
			goto result;
		}
		$paymentId = $resArr['refund_id'];

		$statusCode	 = 0;
		$statusType	 = 1;
		$status		 = strtolower($resArr['refund_status']);
		if ($status == 'queued')
		{
			$statusCode	 = 0;
			$statusType	 = 1;
		}
		if (in_array($status, ['approved', 'refunded', 'accepted']))
		{
			$statusCode	 = 1;
			$statusType	 = 2;
		}
		if (in_array($status, ['failed', 'bounced', 'usercancelled', 'dropped', 'cancelledbyuser']))
		{
			$statusCode	 = 2;
			$statusType	 = 2;
		}

		$payResponse->fullResponse			 = json_encode($refundRawArr);
		$payResponse->response_code			 = 200;
		$payResponse->payment_code			 = $paymentId;
		$payResponse->response				 = json_encode($refundRawArr);
		$payResponse->message				 = trim($resArr['refund_status']);
		$payResponse->payment_status		 = $statusCode;
		$payResponse->payment_status_type	 = $statusType;
		return $payResponse;

		result:

		$payResponse->response_code			 = 100;
		$payResponse->payment_code			 = $pgModel->apg_merchant_ref_id;
		$payResponse->response				 = json_encode($refundRawArr);
		$payResponse->message				 = 'Refund Failed';
		$payResponse->payment_status		 = 2;
		$payResponse->payment_status_type	 = 2;
		return $payResponse;
	}
}
