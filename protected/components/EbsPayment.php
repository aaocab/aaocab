<?php

Yii::import("ext.yiiebs.CryptRC4");

class EbsPayment extends CComponent
{

	public $api_live	 = false;
	public $ACCOUNT_ID	 = "";
	public $SECRET		 = "";
	public $RETURN_URL	 = "";
	public $CANCEL_URL	 = "";
	private $endpoint;
	private $mode; // TEST | LIVE
	///////Response state
	public $responseCode;  /// Response from payment gateway '0' means sucess
	public $responseMessage; // Response message from payment gateway
	public $referenceNumber;  // the merchant reference number that was passed with request
	public $gateway_transaction_id; // unique transaction id generated for this request by payment gateway
	public $api_url		 = "https://api.secure.ebs.in/api";
	public $status_url	 = "https://api.secure.ebs.in/api/1_0";
	public $view		 = 'ebs';

	function __construct($RETURN_URL, $CANCEL_URL = null, $ACCOUNT_ID = null, $SECRET = null, $mode = null)
	{

//		 $RETURN_URL = Yii::app()->createAbsoluteUrl("/ebs/response");
//		$CANCEL_URL = Yii::app()->createAbsoluteUrl("/ebs/response", array());


		if (!$ACCOUNT_ID)
		{
			$this->ACCOUNT_ID	 = Yii::app()->params['ebs']['account_id'];
			$this->SECRET		 = Yii::app()->params['ebs']['secret'];
			$this->mode			 = Yii::app()->params['ebs']['mode'];
		}
		else
		{
			$this->ACCOUNT_ID	 = $ACCOUNT_ID;
			$this->SECRET		 = $SECRET;
			$this->mode			 = $mode;
		}


		$this->RETURN_URL = $RETURN_URL . "?DR={DR}";

		if (!$CANCEL_URL)
		{
			$this->CANCEL_URL = $RETURN_URL . "?DR={DR}";
		}
		else
		{
			$this->CANCEL_URL = $CANCEL_URL . "?DR={DR}";
		}


		$this->endpoint = "https://secure.ebs.in/pg/ma/payment/request";
	}

//	function init()
//	{
//
//		$RETURN_URL = Yii::app()->createAbsoluteUrl("/ebs/response");
//
//		$CANCEL_URL = Yii::app()->createAbsoluteUrl("/ebs/response", array());
//
//		$this->ACCOUNT_ID	 = Yii::app()->params['ebs']['account_id'];
//		$this->SECRET		 = Yii::app()->params['ebs']['secret'];
//		$this->mode			 = Yii::app()->params['ebs']['mode'];
//
//
//
//		$this->RETURN_URL = $RETURN_URL . "?DR={DR}";
//
//		if (!$CANCEL_URL)
//		{
//			$this->CANCEL_URL = $RETURN_URL . "?DR={DR}";
//		}
//		else
//		{
//			$this->CANCEL_URL = $CANCEL_URL . "?DR={DR}";
//		}
//
//
//		$this->endpoint = "https://secure.ebs.in/pg/ma/payment/request";
//	}

	public function parseResponse($responseArr)
	{
		$method		 = "parseResponse ";
		$status		 = 0;
		$final		 = array();
		$secret_key	 = $this->SECRET;  // Your Secret Key
		if (isset($responseArr['DR']))
		{
			$DR			 = preg_replace("/\s/", "+", $responseArr['DR']);
			include 'CryptRC4.php';
			$rc4		 = new CryptRC4($secret_key);
			$QueryString = $DR;
			$QueryString = base64_decode($DR, false);
			$rc4->decrypt($QueryString);
			$QueryString = explode('&', $QueryString);
			foreach ($QueryString as $param)
			{
				$param				 = explode('=', $param);
				$final[$param[0]]	 = urldecode($param[1]);
			}
		}
		$payResponse = $this->getTransactionStatus($final);
		return $payResponse;
	}

	public function getPaymentFormParams($reference_number, $amount, $name, $address, $city, $state, $country, $postal_code, $phone)
	{
		$params = array();

		$params['$reference_number'] = $reference_number;
		$params['$amount']			 = $amount;
		$params['$name']			 = $name;
		$params['$address']			 = $address;
		$params['$city']			 = $city;
		$params['$state']			 = $state;
		$params['$country']			 = $country;
		$params['$postal_code']		 = $postal_code;
		$params['$phone']			 = $phone;

		$ebs_post = $this->getParams($params);
		return $ebs_post;
	}

	public function getPaymentForm($reference_number, $amount, $name, $address, $city, $state, $country, $postal_code, $phone)
	{
		$params = array();

		$params['reference_number']	 = $reference_number;
		$params['$amount']			 = $amount;
		$params['$name']			 = $name;
		$params['$address']			 = $address;
		$params['$city']			 = $city;
		$params['$state']			 = $state;
		$params['$country']			 = $country;
		$params['$postal_code']		 = $postal_code;
		$params['$phone']			 = $phone;


		$ebs_post = $this->getParams($params);

		$content = Yii::app()->controller->renderPartial("//ebs/demo/paymentForm", array('ebs_post' => $ebs_post), false);

		return $content;
	}

	public function getParams($params = array())
	{
		$config						 = Yii::app()->params['ebs'];
		$ebs_gateway				 = $this->endpoint;
		$account_id					 = $config['account_id'];
		$return_url					 = $this->RETURN_URL;
		$mode						 = $this->mode;
		$secret_key					 = $config['secret'];
		$reference_no				 = $params['reference_no'];
		$amount						 = $params['amount'];
		$hash						 = $secret_key . "|" . $account_id . "|" . $amount . "|" . $reference_no . "|" . $return_url . "|" . $mode;
		$secure_hash				 = md5($hash);
		$ebs_post					 = array();
		$ebs_post['page_id']		 = 4516;
		$ebs_post['channel']		 = 0;
		$ebs_post['reference_no']	 = $params['reference_no'];
		$ebs_post['ebs_gateway']	 = $ebs_gateway;
		$ebs_post['account_id']		 = $account_id;
		$ebs_post['return_url']		 = $return_url;
		$ebs_post['mode']			 = $mode;
		$ebs_post['currency']		 = 'INR';
		$ebs_post['payment_mode']	 = $params['payment_mode'];
		$ebs_post['amount']			 = $params['amount'];
		$ebs_post['hash']			 = $hash;
		$ebs_post['secure_hash']	 = $secure_hash;
		$ebs_post['description']	 = $params['description'];
		$ebs_post['name']			 = $params['name'];
		$ebs_post['address']		 = $params['address'];
		$ebs_post['city']			 = $params['city'];
		$ebs_post['state']			 = $params['state'];
		$ebs_post['country']		 = $params['country'];
		$ebs_post['postal_code']	 = $params['postal_code'];
		$ebs_post['phone']			 = $params['phone'];
		$ebs_post['email']			 = $params['email'];
		return $ebs_post;
	}

	public function getTxnStatus($ebsPost)
	{
		//$ebsPost=$this->getParams($requestParamList);
		//$ebsPost['ebs_gateway']=$this->api_url.'/status';
		$ebsPost['Action']		 = 'status';
		$ebsPost['AccountID']	 = $this->ACCOUNT_ID;
		$ebsPost['SecretKey']	 = $this->SECRET;
		return $this->callAPI($this->api_url . '/status', $ebsPost);
	}

	public function getPaymentStatus($pgModel)
	{
		$transcode = $pgModel->apg_code;


		if ($pgModel->apg_mode == 1)
		{
			$transcode = PaymentGateway::model()->getCodebyRefid($pgModel->apg_ref_id);
		}

		$responseArr = $this->getTxnStatus(['RefNo' => $transcode]);
		$payResponse = $this->getTransactionStatus($responseArr);
		return $payResponse;
	}

	public function getTransactionStatus($responseArr)
	{
		$this->responseCode				 = $responseArr['ResponseCode'] . $responseArr['errorCode'];
		$this->responseMessage			 = $responseArr['ResponseMessage'] . $responseArr['status'] . $responseArr['error'];
		$this->referenceNumber			 = $responseArr['MerchantRefNo'] . $responseArr['referenceNo'];
		$this->gateway_transaction_id	 = $responseArr['TransactionID'] . $responseArr['transactionId'];
		if (!$responseArr || count($responseArr) == 0 || $this->responseCode == 2 || $this->responseCode == 4 || $this->responseMessage == 'Incompleted' || $this->responseMessage == 'Failed' || $this->responseMessage == 'Cancelled' || $this->responseMessage == 'AuthFailed' || $this->responseMessage == 'Failed')
		{
			$this->responseCode		 = "500";
			$this->responseMessage	 .= " Payment failure";
			$status					 = 2;
		}
		if ($this->responseCode == '0' || $this->responseMessage == 'Captured' || $this->responseMessage == 'Refunded' || $this->responseMessage == 'Authorized' || $this->responseMessage == 'Processing')
		{
			$this->responseMessage	 .= " Payment success - Transaction Id:" . $this->gateway_transaction_id;
			$status					 = 1;
		}
		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_EBS;
		$payResponse->transaction_code	 = $this->referenceNumber;
		$payResponse->response_code		 = $this->responseCode;
		$payResponse->payment_code		 = $this->gateway_transaction_id;
		$payResponse->response			 = json_encode($responseArr);
		$payResponse->message			 = $this->responseMessage;
		$payResponse->payment_status	 = $status;
		return $payResponse;
	}

	public function getRefund($req = [])
	{
		//$ebsPost=$this->getParams($requestParamList);
		//$ebsPost['ebs_gateway']=$this->api_url.'/status';
		$ebsPost['Action']		 = 'refund';
		$ebsPost['AccountID']	 = $this->ACCOUNT_ID;
		$ebsPost['SecretKey']	 = $this->SECRET;
		$ebsPost['PaymentID']	 = $req['PaymentID'];
		$ebsPost['Amount']		 = $req['Amount'] * 1;
		return $this->callAPI($this->status_url, $ebsPost);
	}

	public function callAPI($apiURL, $requestParamList)
	{
		$jsonResponse		 = "";
		//	$responseParamList = array();
		$postData			 = $requestParamList;
		$ch					 = curl_init($apiURL);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$responseParamList	 = curl_exec($ch);
		$responsexml		 = simplexml_load_string($responseParamList);
		$json				 = json_encode($responsexml);
		$resArr				 = json_decode($json, TRUE);
		return $resArr['@attributes'];
	}

	public function initiateRequest($payRequest)
	{
//        $ebsopt            = Yii::app()->request->getParam('ebsopt', 1); //Do not delete
		$ebs_post = $this->getParams(
				array(
					'payment_mode'	 => $ebsopt,
					'amount'		 => $payRequest->trans_amount,
					'reference_no'	 => $payRequest->transaction_code,
					'description'	 => $payRequest->description,
					'name'			 => $payRequest->name,
					'address'		 => $payRequest->billingAddress,
					'city'			 => $payRequest->city,
					'state'			 => $payRequest->state,
					'postal_code'	 => $payRequest->postal,
					'country'		 => $payRequest->country,
					'phone'			 => $payRequest->mobile,
					'email'			 => $payRequest->email
		));
		return $ebs_post;
	}

	public function refund($transModel)
	{


		$paramList	 = array();
		$pmtid		 = PaymentGateway::model()->getPMTIDbyid($transModel->apg_ref_id);
		$oldOrderId	 = PaymentGateway::model()->getCodebyid($transModel->apg_ref_id);
		$amount		 = -1 * $transModel->apg_amount;

		$paramList['Amount']	 = $amount;
		$paramList['PaymentID']	 = $pmtid;

		$RETURN_URL	 = Yii::app()->createAbsoluteUrl("/ebs/response");
		$ebsPayment		 = new EbsPayment($RETURN_URL);
		$responseArr = $ebsPayment->getRefund($paramList);

//	$payResponse=	$ebsPayment->getTransactionStatus($responseArr);

		$this->responseCode				 = $responseArr['ResponseCode'] . $responseArr['errorCode'];
		$this->responseMessage			 = $responseArr['ResponseMessage'] . $responseArr['status'] . $responseArr['error'];
		$this->referenceNumber			 = $responseArr['MerchantRefNo'] . $responseArr['referenceNo'];
		$this->gateway_transaction_id	 = $responseArr['TransactionID'] . $responseArr['transactionId'];


		if ($responseArr['transactionType'] == 'Processing' || $responseArr['transactionType'] == 'Refunded')
		{
			$this->responseMessage	 .= " Payment success - Transaction Id:" . $this->gateway_transaction_id;
			$status					 = 1;
		}
		else
		{
			$status = 2;
		}
		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_EBS;
		$payResponse->transaction_code	 = $this->referenceNumber;
		$payResponse->response_code		 = $this->responseCode;
		$payResponse->payment_code		 = $this->gateway_transaction_id;
		$payResponse->response			 = json_encode($responseArr);
		$payResponse->message			 = $this->responseMessage;
		$payResponse->payment_status	 = $status;
		return $payResponse;
	}

}
