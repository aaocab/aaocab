<?php

class Paynimo extends CComponent
{
	public $requestType			 = "";
	public $merchantCode		 = "";
	public $merchantTxnRefNumber = "";
	public $amount				 = "";
	public $accountNo			 = "";
	public $currencyCode		 = "";
	public $returnURL			 = "";
	public $s2SReturnURL		 = "";
	public $txnDate				 = "";
	public $mobileNumber		 = "";
	public $customerName		 = "";
	public $webServiceLocator	 = "NA";
	public $shoppingCartDetails	 = '';
	public $key;
	public $iv;
	public $IV;
	public $currDate;
	public $timeOut				 = 30;
	public $view				 = 'paynimo';
	public $ITC;
	public $bankCode;
	public $custId;
	public $cardId;
	public $TPSLTxnID;

	function __construct()
	{
		$this->key				 = Yii::app()->params['paynimo']['key'];
		$this->iv				 = Yii::app()->params['paynimo']['iv'];
		$this->merchantCode		 = Yii::app()->params['paynimo']['merchantCode'];
		$this->requestType		 = 'T';
		$this->currDate			 = date('d-m-Y');
		$this->webServiceLocator = 'https://payments.paynimo.com/PaynimoProxy/services/TransactionLiveDetails?wsdl';
		$this->returnURL		 = Yii::app()->createAbsoluteUrl('paynimo/response');
		$this->currencyCode		 = 'INR';
	}

	public function getParam()
	{
		$transactionRequestBean							 = new TransactionRequestBean();
		$transactionRequestBean->merchantCode			 = $this->merchantCode;
		$transactionRequestBean->accountNo				 = $this->accountNo;
		$transactionRequestBean->ITC					 = $this->ITC;
		$transactionRequestBean->mobileNumber			 = $this->mobileNumber;
		$transactionRequestBean->customerName			 = $this->customerName;
		$transactionRequestBean->requestType			 = $this->requestType;
		$transactionRequestBean->merchantTxnRefNumber	 = $this->merchantTxnRefNumber;
		$transactionRequestBean->amount					 = $this->amount;
		$transactionRequestBean->currencyCode			 = $this->currencyCode;
		$transactionRequestBean->returnURL				 = $this->returnURL;
		$transactionRequestBean->s2SReturnURL			 = $this->s2SReturnURL;
		$transactionRequestBean->shoppingCartDetails	 = $this->shoppingCartDetails;
		$transactionRequestBean->txnDate				 = $this->currDate;
		$transactionRequestBean->bankCode				 = $this->bankCode;
		$transactionRequestBean->TPSLTxnID				 = $this->TPSLTxnID;
		$transactionRequestBean->custId					 = $this->custId;
		$transactionRequestBean->cardId					 = $this->cardId;
		$transactionRequestBean->key					 = $this->key;
		$transactionRequestBean->iv						 = $this->iv;
		$transactionRequestBean->webServiceLocator		 = $this->webServiceLocator;
		$transactionRequestBean->MMID					 = '';
		$transactionRequestBean->OTP					 = '';
		$transactionRequestBean->cardName				 = '';
		$transactionRequestBean->cardNo					 = '';
		$transactionRequestBean->cardCVV				 = '';
		$transactionRequestBean->cardExpMM				 = '';
		$transactionRequestBean->cardExpYY				 = '';
		$transactionRequestBean->timeOut				 = $this->timeOut;
		return $transactionRequestBean;
	}

	public function initiateRequest($payRequest)
	{
		$this->merchantTxnRefNumber	 = $payRequest->transaction_code;
		$this->mobileNumber			 = $payRequest->mobile;
		$this->customerName			 = $payRequest->name;
		$this->amount				 = $payRequest->trans_amount;
		$this->TPSLTxnID			 = 'TXN00' . $this->merchantTxnRefNumber;
		$this->shoppingCartDetails	 = 'FIRST_' . $payRequest->trans_amount . '_0.0';
		$this->bankCode              = $payRequest->bankcode;
		$transactionRequestBean		 = $this->getParam();
		$url						 = $transactionRequestBean->getTransactionToken();
		return $url;
	}

	public function parseResponse($responseArr)
	{
		$str		 = $this->reverifyStatus($responseArr);
		$payResponse = $this->setTxnResponse($str);

		return $payResponse;
	}

	public function reverifyStatus($responseArr)
	{
		if (is_array($responseArr))
		{
			$str = $responseArr['msg'];
		}
		else if (is_string($responseArr) && strstr($responseArr, 'msg='))
		{
			$outputStr	 = str_replace('msg=', '', $responseArr);
			$outputArr	 = explode('&', $outputStr);
			$str		 = $outputArr[0];
		}
		else
		{
			$str = $responseArr;
		}

		$transactionResponseBean = new TransactionResponseBean();

		$transactionResponseBean->setResponsePayload($str);
		$transactionResponseBean->key	 = $this->key;
		$transactionResponseBean->iv	 = $this->iv;

		$response		 = $transactionResponseBean->getResponsePayload();
		$txnStatusArr	 = explode("=", $response[0]);
		$txnStatusValue	 = trim($txnStatusArr[1]);
		if ($txnStatusValue == '0300')
		{
			$txnCodeArr			 = explode("=", $response[3]);
			$txnCodeValue		 = trim($txnCodeArr[1]);
			$txnPaymentCodeArr	 = explode("=", $response[5]);
			$txnPaymentCodeValue = trim($txnPaymentCodeArr[1]);
			$txnAmtArr			 = explode("=", $response[6]);
			$txnAmtValue		 = trim($txnAmtArr[1]);

			$this->requestType			 = 'S';
			$this->merchantTxnRefNumber	 = $txnCodeValue;
			$this->amount				 = $txnAmtValue;
			$this->TPSLTxnID			 = $txnPaymentCodeValue;
			$transactionRequestBean		 = $this->getParam();
			$response					 = $this->getTxnResponse($transactionRequestBean);
		}
		if ($response == '')
		{
			$response = $str;
		}
		return $response;
	}

	public function refund($pgModel)
	{
		$this->requestType			 = 'R';
		$this->merchantTxnRefNumber	 = $pgModel->apg_code;
		$this->amount				 = -1 * $pgModel->apg_amount;
		$this->TPSLTxnID			 = $pgModel->apg_merchant_ref_id;
		$transactionRequestBean		 = $this->getParam();
		$response					 = $this->getTxnResponse($transactionRequestBean);
		$payResponse				 = $this->setTxnResponse($response);
		return $payResponse;
	}

	public function getTxnResponse($transactionRequestBean)
	{
		$responseDetails = $transactionRequestBean->getTransactionToken();
		$responseDetails = (array) $responseDetails;
		$response		 = $responseDetails[0];

		if (is_string($response) && preg_match('/^msg=/', $response))
		{
			$outputStr	 = str_replace('msg=', '', $response);
			$outputArr	 = explode('&', $outputStr);
			$str		 = $outputArr[0];

			$transactionResponseBean = new TransactionResponseBean();
			$transactionResponseBean->setResponsePayload($str);
			$transactionResponseBean->setKey($this->key);
			$transactionResponseBean->setIv($this->iv);

			$response = $transactionResponseBean->getResponsePayload();
		}
		return $response;
	}

	public function setTxnResponse($response)
	{
		$responseArr	 = explode("|", $response);
		$txnStatusArr	 = explode("=", $responseArr[0]);
		$txnMsgArr		 = explode("=", $responseArr[1]);

		$resCode = trim($txnStatusArr[1]);
		$status	 = 0;

		if ($resCode == '0400' || $resCode == '0401' || $resCode == '0402' || $resCode == '0300')
		{
			$status = 1;
		}
		else if ($resCode == '0392' || $resCode == '0395' || $resCode == '0397' || $resCode == '0399' || $resCode == '0499' || $resCode == '9999')
		{
			if ($txnMsgValue == '')
			{
				$txnErrMsgArr	 = explode("=", $response[2]);
				$txnMsgValue	 = trim($txnErrMsgArr[1]);
			}
			$status = 2;
		}
		$txnCodeArr			 = explode("=", $responseArr[3]);
		$txnCodeValue		 = trim($txnCodeArr[1]);
		$txnPaymentCodeArr	 = explode("=", $responseArr[5]);
		$txnPaymentCodeValue = trim($txnPaymentCodeArr[1]);

		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_PAYNIMO;
		$payResponse->transaction_code	 = $txnCodeValue;
		$payResponse->response_code		 = $resCode;
		$payResponse->payment_code		 = $txnPaymentCodeValue;
		$payResponse->response			 = json_encode($responseArr);
		$payResponse->message			 = trim($txnMsgArr[1]);
		$payResponse->payment_status	 = $status;
		return $payResponse;
	}

	public function getPaymentStatus($pgModel)
	{
		$this->requestType			 = 'O';
		$this->merchantTxnRefNumber	 = $pgModel->apg_code;
		$this->amount				 = $pgModel->apg_amount;
		$this->TPSLTxnID			 = $pgModel->apg_merchant_ref_id;
		$this->currDate				 = date('d-m-Y', strtotime($pgModel->apg_date));
		$transactionRequestBean		 = $this->getParam();

		$response	 = $this->getTxnResponse($transactionRequestBean);
		$payResponse = $this->setTxnResponse($response);

		return $payResponse;
	}

}

class RequestValidate
{

	const BLANK_REQUEST_TYPE	 = 'ERROR008';
	const INVALID_REQUEST_TYPE = 'ERROR002';
	const BLANK_MER_CODE		 = 'ERROR027';
	const INVALID_KEY			 = 'ERROR067';
	const INVALID_IV			 = 'ERROR068';
	const BLANK_PG_RESPONSE	 = 'ERROR069';

	protected $requestTypes = array('T', 'S', 'O', 'R', 'TIC', 'TIO', 'TWC', 'TRC', 'TCC', 'TWI');

	public function validateRequestParam($requestParams = array())
	{
		if (!isset($requestParams['pReqType']) || $this->isBlankOrNull($requestParams['pReqType']))
		{
			return self::BLANK_REQUEST_TYPE;
		}
		else if (!in_array($requestParams['pReqType'], $this->requestTypes))
		{
			return self::INVALID_REQUEST_TYPE;
		}
		if (!isset($requestParams['pMerCode']) || $this->isBlankOrNull($requestParams['pMerCode']))
		{
			return self::BLANK_MER_CODE;
		}
		if (!isset($requestParams['pEncKey']) || $this->isBlankOrNull($requestParams['pEncKey']))
		{
			return self::INVALID_KEY;
		}
		if (!isset($requestParams['pEncIv']) || $this->isBlankOrNull($requestParams['pEncIv']))
		{
			return self::INVALID_IV;
		}
		return false;
	}

	public function validateResponseParam($responseParams = array())
	{
		if (!isset($responseParams['pRes']) || $this->isBlankOrNull($responseParams['pRes']))
		{
			return self::BLANK_PG_RESPONSE;
		}
		if (!isset($responseParams['pEncKey']) || $this->isBlankOrNull($responseParams['pEncKey']))
		{
			return self::INVALID_KEY;
		}
		if (!isset($responseParams['pEncIv']) || $this->isBlankOrNull($responseParams['pEncIv']))
		{
			return self::INVALID_IV;
		}

		return false;
	}

	public function isBlankOrNull($param = null)
	{
		if (empty($param) || $param == "NA")
		{
			return true;
		}
		return false;
	}

}

class TransactionRequestBean extends RequestValidate
{

	private $tilda = "~";
	private $separator = "|";
	private $requestType = "";
	private $merchantCode = "";
	private $merchantTxnRefNumber = "";
	private $ITC = "";
	private $amount = "";
	private $accountNo = "";
	private $currencyCode = "";
	private $uniqueCustomerId = "";
	private $returnURL = "";
	private $s2SReturnURL = "";
	private $TPSLTxnID = "";
	private $shoppingCartDetails = "";
	private $txnDate = "";
	private $email = "";
	private $mobileNumber = "";
	private $socialMediaIdentifier = "";
	private $bankCode = "";
	private $customerName = "";
	private $reqst = null;
	private $webServiceLocator = "NA";
	private $MMID = "";
	private $OTP = "";
	private $key;
	private $iv;
	static $mkd;
	private $blockSize = 128;
	private $mode = "cbc";
	private $logPath = "";
	static $currDate;
	private static $rqst_kit_vrsn = 1;
	private $custId = "";
	private $cardId = "";
	private $cardNo = "";
	private $cardName = "";
	private $cardCVV = "";
	private $cardExpMM = "";
	private $cardExpYY = "";
	private $timeOut = 30;

	public function __set($field, $value)
	{
		$this->$field = $value;
	}

	public function __get($variable)
	{
		return $this->$variable;
	}

	public function getTilda()
	{
		return $this->tilda;
	}

	public function getSeparator()
	{
		return $this->separator;
	}

	public function getUniqueCustomerId()
	{
		return $this->uniqueCustomerId;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getSocialMediaIdentifier()
	{
		return $this->socialMediaIdentifier;
	}

	public function getReqst()
	{
		return $this->reqst;
	}

	public function getWebServiceLocator()
	{
		return $this->webServiceLocator;
	}

	public static function getMkd()
	{
		return TransactionRequestBean::$mkd;
	}

	public function getBlockSize()
	{
		return $this->blockSize;
	}

	public function getMode()
	{
		return $this->mode;
	}

	public function getLogPath()
	{
		return $this->logPath;
	}

	public static function getCurrDate()
	{
		return TransactionRequestBean::$currDate;
	}

	public static function getRqst_kit_vrsn()
	{
		return TransactionRequestBean::$rqst_kit_vrsn;
	}

	public function getTimeOut()
	{
		return $this->timeOut;
	}

	public function setTilda($tilda)
	{
		$this->tilda = $tilda;
	}

	public function setSeparator($separator)
	{
		$this->separator = $separator;
	}

	public function setUniqueCustomerId($uniqueCustomerId)
	{
		$this->uniqueCustomerId = $uniqueCustomerId;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function setSocialMediaIdentifier($socialMediaIdentifier)
	{
		$this->socialMediaIdentifier = $socialMediaIdentifier;
	}

	public function setReqst($reqst)
	{
		$this->reqst = $reqst;
	}

	public function setWebServiceLocator($webServiceLocator)
	{
		$this->webServiceLocator = $webServiceLocator;
	}

	public static function setMkd($mkd)
	{
		TransactionRequestBean::$mkd = $mkd;
	}

	public function setBlockSize($blockSize)
	{
		$this->blockSize = $blockSize;
	}

	public function setMode($mode)
	{
		$this->mode = $mode;
	}

	public function setLogPath($logPath)
	{
		$this->logPath = $logPath;
	}

	public static function setCurrDate($currDate)
	{
		TransactionRequestBean::$currDate = $currDate;
	}

	public static function setRqst_kit_vrsn($rqst_kit_vrsn)
	{
		TransactionRequestBean::$rqst_kit_vrsn = $rqst_kit_vrsn;
	}

	public function getEncryptedData()
	{
		try
		{
			$clientMetaData = "";

			if (!$this->isBlankOrNull($this->ITC))
			{
				$clientMetaData .= "{itc:" . $this->ITC . "}";
			}
			if (!$this->isBlankOrNull($this->email))
			{
				$clientMetaData .= "{email:" . $this->email . "}";
			}
			if (!$this->isBlankOrNull($this->mobileNumber))
			{
				$clientMetaData .= "{mob:" . $this->mobileNumber . "}";
			}
			if (!$this->isBlankOrNull($this->uniqueCustomerId))
			{
				$clientMetaData .= "{custid:" . $this->uniqueCustomerId . "}";
			}
			if (!$this->isBlankOrNull($this->customerName))
			{
				$clientMetaData .= "{custname:" . $this->customerName . "}";
			}

			$this->strReqst = "";
			if (!$this->isBlankOrNull($this->requestType))
			{
				$this->strReqst .= "rqst_type=" . $this->requestType;
			}

			$this->strReqst .= "|rqst_kit_vrsn=1.0." . self::$rqst_kit_vrsn;

			if (!$this->isBlankOrNull($this->merchantCode))
			{
				$this->strReqst .= "|tpsl_clnt_cd=" . $this->merchantCode;
			}

			if (!$this->isBlankOrNull($this->accountNo))
			{
				$this->strReqst .= "|accountNo=" . $this->accountNo;
			}

			if (!$this->isBlankOrNull($this->merchantTxnRefNumber))
			{
				$this->strReqst .= "|clnt_txn_ref=" . $this->merchantTxnRefNumber;
			}

			if (!$this->isBlankOrNull($clientMetaData))
			{
				$this->strReqst .= "|clnt_rqst_meta=" . (string) $clientMetaData;
			}

			if (!$this->isBlankOrNull($this->amount))
			{
				$this->strReqst .= "|rqst_amnt=" . $this->amount;
			}

			if (!$this->isBlankOrNull($this->currencyCode))
			{
				$this->strReqst .= "|rqst_crncy=" . $this->currencyCode;
			}

			if (!$this->isBlankOrNull($this->returnURL))
			{
				$this->strReqst .= "|rtrn_url=" . $this->returnURL;
			}

			if (!$this->isBlankOrNull($this->s2SReturnURL))
			{
				$this->strReqst .= "|s2s_url=" . $this->s2SReturnURL;
			}

			if (!$this->isBlankOrNull($this->shoppingCartDetails))
			{
				$this->strReqst .= "|rqst_rqst_dtls=" . $this->shoppingCartDetails;
			}

			if (!$this->isBlankOrNull($this->txnDate))
			{
				$this->strReqst .= "|clnt_dt_tm=" . $this->txnDate;
			}

			if (!$this->isBlankOrNull($this->bankCode))
			{
				$this->strReqst .= "|tpsl_bank_cd=" . $this->bankCode;
			}

			if (!$this->isBlankOrNull($this->TPSLTxnID))
			{
				$this->strReqst .= "|tpsl_txn_id=" . $this->TPSLTxnID;
			}

			if (!$this->isBlankOrNull($this->custId))
			{
				$this->strReqst .= "|cust_id=" . $this->custId;
			}

			if (!$this->isBlankOrNull($this->cardId))
			{
				$this->strReqst .= "|card_id=" . $this->cardId;
			}
			if (!$this->isBlankOrNull($this->mobileNumber))
			{
				$this->strReqst .= "|mob=" . $this->mobileNumber;
			}

			if (($this->requestType == "TWC") || ($this->requestType == "TRC") || ($this->requestType == "TIC"))
			{

				$cardInfoBuff	 = "";
				$cardInfoBuff	 .= "card_Hname=" . $this->cardName;
				$cardInfoBuff	 .= "|card_no=" . $this->cardNo;
				$cardInfoBuff	 .= "|card_Cvv=" . $this->cardCVV;
				$cardInfoBuff	 .= "|exp_mm=" . $this->cardExpMM;
				$cardInfoBuff	 .= "|exp_yy=" . $this->cardExpYY;

				$aes		 = new AES($cardInfoBuff, $this->key, $this->blockSize, $this->mode, $this->iv);
				$aes->require_pkcs5();
				$cardInfoStr = $aes->encryptHex();

				$aesObj		 = new AES($cardInfoStr, $this->key, $this->blockSize, $this->mode, $this->iv);
				$aesObj->require_pkcs5();
				$cardInfo	 = $aesObj->encryptHex();

				$this->strReqst .= "|card_details=" . $cardInfo;
			}
			else if ($this->requestType == "TCC")
			{

				$cardInfoBuff	 = "";
				$cardInfoBuff	 .= "|card_Cvv=" . $this->cardCVV;

				$aes		 = new AES($cardInfoBuff, $this->key, $this->blockSize, $this->mode, $this->iv);
				$aes->require_pkcs5();
				$cardInfoStr = $aes->encryptHex();

				$aesObj		 = new AES($cardInfoStr, $this->key, $this->blockSize, $this->mode, $this->iv);
				$aesObj->require_pkcs5();
				$cardInfo	 = $aesObj->encryptHex();

				$this->strReqst .= "|card_details=" . $cardInfo;
			}
			else if ($this->requestType == "TWI")
			{

				$impsInfoBuff	 = "";
				$impsInfoBuff	 .= "mmid=" . $this->MMID;
				$impsInfoBuff	 .= "|mob_no=" . $this->mobileNumber;
				$impsInfoBuff	 .= "|otp=" . $this->OTP;

				$aes		 = new AES($impsInfoBuff, $this->key, $this->blockSize, $this->mode, $this->iv);
				$aes->require_pkcs5();
				$impsInfoStr = $aes->encryptHex();

				$aesObj		 = new AES($impsInfoStr, $this->key, $this->blockSize, $this->mode, $this->iv);
				$aesObj->require_pkcs5();
				$impsInfo	 = $aesObj->encryptHex();

				$this->strReqst .= "|imps_details=" . $impsInfo;
			}
			else if ($this->requestType == "TIO")
			{
				$this->strReqst .= "|otp=" . $this->OTP;
			}

			$this->strReqst .= "|hash=" . sha1($this->strReqst);

			$aesObj = new AES($this->strReqst, $this->key, $this->blockSize, $this->mode, $this->iv);
			$aesObj->require_pkcs5();

			$encryptedData = $aesObj->encrypt();
		}
		catch (Exception $ex)
		{
			echo $ex->getMessage();
			return;
		}

		return $encryptedData;
	}

	public function getTransactionToken()
	{
		set_time_limit((int) $this->timeOut);
		if ($this->webServiceLocator != null && $this->webServiceLocator != "" && $this->webServiceLocator != "NA")
		{

			$params				 = array();
			$params['pReqType']	 = $this->requestType;
			$params['pMerCode']	 = $this->merchantCode;
			$params['pEncKey']	 = $this->key;
			$params['pEncIv']	 = $this->iv;

			$errorResponse = $this->validateRequestParam($params);

			if ($errorResponse)
			{
				return $errorResponse;
			}

			$encryptedData = $this->getEncryptedData();


			if (!$encryptedData)
			{
				return;
			}

			try
			{

				$postData = $encryptedData . "|" . $this->merchantCode . "~";

				$client		 = new SoapClient($this->webServiceLocator,
						array(
					"trace"		 => 1,
					"exceptions" => 1
				));
				$response	 = $client->getTransactionToken(array(
					'data' => $postData
				));
			}
			catch (Exception $ex)
			{
				echo "Error while getting transaction token : " . $ex->getMessage();
				return;
			}

			return isset($response->getTransactionTokenReturn) ? $response->getTransactionTokenReturn : NULL;
		}
		else
		{
			return "ERROR065";
		}
	}

}

class TransactionResponseBean extends RequestValidate
{

	protected $responsePayload = "";
	protected $key;
	protected $iv;
	protected $logPath = "";
	protected $blocksize = 128;
	protected $mode = "cbc";

	public function __set($field, $value)
	{
		$this->$field = $value;
	}

	public function __get($variable)
	{
		return $this->variable;
	}

	public function getLogPath()
	{
		return $this->logPath;
	}

	public function getBlocksize()
	{
		return $this->blocksize;
	}

	public function getMode()
	{
		return $this->mode;
	}

	public function setResponsePayload($responsePayload)
	{
		$this->responsePayload = $responsePayload;
	}

	public function setLogPath($logPath)
	{
		$this->logPath = $logPath;
	}

	public function setBlocksize($blocksize)
	{
		$this->blocksize = $blocksize;
	}

	public function setMode($mode)
	{
		$this->mode = $mode;
	}

	public function getResponsePayload()
	{
		try
		{
			$responseParams = array(
				'pRes'		 => $this->responsePayload,
				'pEncKey'	 => $this->key,
				'pEncIv'	 => $this->iv
			);

			$errorResponse = $this->validateResponseParam($responseParams);

			if ($errorResponse)
			{
				return $errorResponse;
			}

			$aesObj			 = new AES($this->responsePayload, $this->key, $this->blocksize, $this->mode, $this->iv);
			$aesObj->require_pkcs5();
			$decryptResponse = trim(preg_replace('/[\x00-\x1F\x7F]/', '', $aesObj->decrypt()));

			$implodedResp	 = explode("|", $decryptResponse);
			$hashCodeString	 = end($implodedResp);
			array_pop($implodedResp);

			$explodedHashValue	 = explode("=", $hashCodeString);
			$hashValue			 = trim($explodedHashValue[1]);

			$responseDataString	 = implode("|", $implodedResp);
			$generatedHash		 = sha1($responseDataString);

			if ($generatedHash == $hashValue)
			{
				return $implodedResp;
			}
			else
			{
				return 'ERROR064';
			}
		}
		catch (Exception $ex)
		{
			echo "Exception In TransactionResposeBean :" . $ex->getMessage();
			return;
		}

		return "ERROR037";
	}

}

class AES
{

	const M_CBC			 = 'cbc';
	const M_CFB			 = 'cfb';
	const M_ECB			 = 'ecb';
	const M_NOFB			 = 'nofb';
	const M_OFB			 = 'ofb';
	const M_STREAM		 = 'stream';
	const AES_CIPHER_128	 = 'aes-128';
	const AES_CIPHER_192	 = 'aes-192';
	const AES_CIPHER_256	 = 'aes-256';

	function __construct($data = null, $key = null, $blockSize = null, $mode = null, $iv)
	{
		$this->data		 = $data;
		$this->key		 = $key;
		$this->cipher	 = $blockSize;
		$this->mode		 = $mode;
		$this->IV		 = $iv;
	}

	public function __set($field, $value)
	{
		switch ($field)
		{
			case 'cipher':
				$this->$field = $this->setBlockSize($value);
				break;

			case 'mode':
				$this->$field = $this->setMode($value);
				break;

			default:
				$this->$field = $value;
				break;
		}
	}

	public function __get($variable)
	{
		if (!isset($this->$variable))
		{
			return "Variable not found";
		}
	}

	public function setBlockSize($value)
	{
		switch ($value)
		{
			case 128 :
				$cipher	 = AES::AES_CIPHER_128;
				break;
			case 192 :
				$cipher	 = AES::AES_CIPHER_192;
				break;
			case 256 :
				$cipher	 = AES::AES_CIPHER_256;
				break;
		}
		return $cipher;
	}

	public function setMode($value)
	{
		switch ($value)
		{
			case AES::M_CBC :
				$mode	 = AES::M_CBC;
				break;
			case AES::M_CFB :
				$mode	 = AES::M_CFB;
				break;
			case AES::M_ECB :
				$mode	 = AES::M_ECB;
				break;
			case AES::M_NOFB :
				$mode	 = AES::M_NOFB;
				break;
			case AES::M_OFB :
				$mode	 = AES::M_OFB;
				break;
			case AES::M_STREAM :
				$mode	 = AES::M_STREAM;
				break;
			default :
				$mode	 = AES::M_ECB;
				break;
		}
		return $mode;
	}

	public function validateParams()
	{
		if ($this->data != null && $this->key != null && $this->cipher != null)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function encrypt()
	{
		if ($this->validateParams())
		{
			$str	 = $this->data;
			$cipher	 = $this->cipher . "-" . $this->mode;

			if (in_array($cipher, openssl_get_cipher_methods()))
			{
				$ivlen = openssl_cipher_iv_length($cipher);
				if (empty($this->IV))
				{
					$this->IV = openssl_random_pseudo_bytes($ivlen);
				}
				$rt		 = openssl_encrypt($str, $cipher, $this->key, $options = 0, $this->IV);
			}
			return $rt;
		}
		else
		{
			throw new Exception('Provide valid details to get transaction token');
		}
	}

	public function decrypt()
	{
		$cipher	 = $this->cipher . "-" . $this->mode;
		$ivlen	 = openssl_cipher_iv_length($cipher);
		if (empty($this->IV))
		{
			$iv = substr($this->data, 0, $ivlen);
		}
		else
		{
			$iv = $this->IV;
		}
		$rt		 = openssl_decrypt($this->data, $cipher, $this->key, $options = 0, $iv);
		return $rt;
	}

	public function encryptHex()
	{
		$str	 = $this->data;
		$cipher	 = $this->cipher . "-" . $this->mode;

		if (in_array($cipher, openssl_get_cipher_methods()))
		{
			$ivlen = openssl_cipher_iv_length($cipher);
			if (empty($this->IV))
			{
				$iv = openssl_random_pseudo_bytes($ivlen);
			}
			else
			{
				$iv = $this->IV;
			}
			$cyper_text	 = openssl_encrypt($str, $cipher, $this->key, $options	 = 0, $iv);
			$rt			 = bin2hex($cyper_text);
		}

		return $rt;
	}

	public function decryptHex()
	{

		$cipher	 = $this->cipher . "-" . $this->mode;
		$ivlen	 = openssl_cipher_iv_length($cipher);

		if (empty($this->IV))
		{
			$iv = substr($c, 0, $ivlen);
		}
		else
		{
			$iv = $this->IV;
		}
		$hexToBinStr = self::hex2bin($this->data);
		$rt			 = openssl_decrypt($hexToBinStr, $cipher, $this->key, $options	 = 0, $iv);
		return $rt;
	}

	public static function hex2bin($hexdata)
	{
		$bindata = '';
		$length	 = strlen($hexdata);
		for ($i = 0; $i < $length; $i += 2)
		{
			$bindata .= chr(hexdec(substr($hexdata, $i, 2)));
		}
		return $bindata;
	}

	public function require_pkcs5()
	{
		$this->pad_method = 'pkcs5';
	}

	protected function pad_or_unpad($str, $ext)
	{
		if (is_null($this->pad_method))
		{
			return $str;
		}
		else
		{
			$func_name = __CLASS__ . '::' . $this->pad_method . '_' . $ext . 'pad';
			if (is_callable($func_name) && function_exists('mcrypt_get_block_size'))
			{
				$size = @mcrypt_get_block_size($this->cipher, $this->mode);
				return call_user_func($func_name, $str, $size);
			}
		}

		return $str;
	}

	protected function pad($str)
	{
		return $this->pad_or_unpad($str, '');
	}

	protected function unpad($str)
	{
		return $this->pad_or_unpad($str, 'un');
	}

	public static function pkcs5_pad($text, $blocksize)
	{
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}

	public static function pkcs5_unpad($text)
	{
		$pad = ord($text{strlen($text) - 1});
		if ($pad > strlen($text))
			return false;
		if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
			return false;
		return substr($text, 0, -1 * $pad);
	}

}
