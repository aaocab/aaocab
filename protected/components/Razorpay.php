<?php

class Razorpay extends CComponent
{

	public $api_live = false;
	public $key;
	public $secret;
	public $view	 = 'razorpay';
	public $apgCode;

	public function init()
	{
		$config			 = Yii::app()->params['razorpay'];
		$this->api_live	 = $config['api_live'];
		$this->key		 = $config['key'];
		$this->secret	 = $config['secret'];
	}

	public function initiateRequest($payRequest)
	{
		try
		{
			$success		 = false;
			$api			 = new Razorpay\Api\Api($this->key, $this->secret);
			$order			 = $api->order->create([
				'receipt'			 => $payRequest->transaction_code,
				'amount'			 => ($payRequest->trans_amount * 100),
				'currency'			 => 'INR',
				'payment_capture'	 => '1'
			]);
//			$order			 = '{"id": "order_InZu9y509jCNV6","status": "created"}'; //testline
//			$order			 = json_decode($order, false); //testline
			$paymentGateway	 = PaymentGateway::model()->getByCode($payRequest->transaction_code);
			if ($order->status == "created" && $order->id != "" && $paymentGateway != "")
			{
				$paymentGateway->apg_pre_txn_id = $order->id;
				if ($paymentGateway->save())
				{
					$success = true;
				}
			}
		}
		catch (Exception $e)
		{
			Logger::create("Razorpay->initiateRequest   ::  Exception   : " . $e->getMessage());
			return ['success'=>false,'error'=>[$e->getMessage()]];
		}
		$param_list['order_id']		 = $order->id;
		$param_list['amount']		 = $payRequest->trans_amount * 100;
		$param_list['currency']		 = 'INR';
		$param_list['name']			 = $payRequest->name;
		$param_list['email']		 = $payRequest->email;
		$param_list['contact']		 = $payRequest->mobile;
		$param_list['trnsCode']		 = $payRequest->transaction_code;
		$param_list['key']			 = $this->key;
		$param_list['callbackUrl']	 = Yii::app()->createAbsoluteUrl('razorpay/response');
		$param_list['success']		 = $success;
		$param_list['productinfo']	 = $payRequest->description;
		$param_list['description']	 = $payRequest->description;
		return $param_list;
	}

	public function parseResponse($postData)
	{
		$postData	 = json_decode($postData);
		$statusCode	 = 0;
		$statusType	 = 0;
		$message	 = "Payment Cancelled";
		try
		{
			if ($postData->error != '')
			{
				$orderId		 = $postData->error->metadata->order_id;
				$paymentId		 = $postData->error->metadata->payment_id;
				$responseCode	 = $postData->error->code;
				$message		 = $postData->error->description;
				$statusCode		 = 2;
				$statusType		 = 2;
				if ($paymentId)
				{
					$apgModel = PaymentGateway::model()->find('apg_txn_id=:txn_id', ['txn_id' => $paymentId]);
				}
				if (!$apgModel)
				{
					$apgModel = PaymentGateway::model()->find('apg_pre_txn_id=:txn_id', ['txn_id' => $orderId]);
				}
				if (empty($orderId) && !empty($paymentId) && empty($apgModel))
				{
					$api	 = new Razorpay\Api\Api($this->key, $this->secret);
					$orderId = $api->payment->fetch($paymentId)->order_id;
					if (!empty($orderId))
					{
						$apgModel = PaymentGateway::model()->find('apg_pre_txn_id=:txn_id', ['txn_id' => $orderId]);
					}
				}
			}
			else if ($postData->razorpay_signature != "" && $postData->razorpay_payment_id != "" && $postData->razorpay_order_id != "")
			{
				$orderId	 = $postData->razorpay_order_id;
				$paymentId	 = $postData->razorpay_payment_id;

				$apgModel = PaymentGateway::model()->find('apg_pre_txn_id=:txn_id', ['txn_id' => $orderId]);
				if (empty($apgModel))
				{
					throw new Exception("Signature Mismatched.");
				}
				$api			 = new Razorpay\Api\Api($this->key, $this->secret);
				$attributes		 = array('razorpay_signature' => $postData->razorpay_signature, 'razorpay_payment_id' => $paymentId, 'razorpay_order_id' => $apgModel->apg_pre_txn_id);
				$api->utility->verifyPaymentSignature($attributes);
				$statusCode		 = 1;
				$statusType		 = 2;
				$message		 = "Payment Success";
				$responseCode	 = null;

				$payResponse = $this->getPaymentStatus($apgModel);
				return $payResponse;
			}
		}
		catch (Exception $e)
		{
			$message		 = $e->getMessage();
			$responseCode	 = null;
			$statusCode		 = 0;
			$statusType		 = 1;
		}
		$payResponse						 = new PaymentResponse();
		$payResponse->payment_type			 = PaymentType::TYPE_RAZORPAY;
		$payResponse->transaction_code		 = $apgModel->apg_code;
		$payResponse->response_code			 = $responseCode;
		$payResponse->payment_code			 = $paymentId;
		$payResponse->mode					 = 2;
		$payResponse->response				 = json_encode($postData);
		$payResponse->message				 = $message;
		$payResponse->payment_status		 = $statusCode;
		$payResponse->payment_status_type	 = $statusType;
		return $payResponse;
	}

	public function refund($pgModel)
	{
		$refId		 = $pgModel->apg_ref_id;
		$oldPgModel	 = PaymentGateway::model()->findByPk($refId);
		$oldTXNID	 = $oldPgModel->apg_txn_id;
		$amount		 = -1 * $pgModel->apg_amount * 100;
		$reciept	 = $pgModel->apg_code;
		try
		{
			$api	 = new Razorpay\Api\Api($this->key, $this->secret);
			$result	 = $api->payment->fetch($oldTXNID)->refund(array("amount" => $amount, "speed" => "optimum", "receipt" => $reciept));
		}
		catch (Exception $e)
		{
			Logger::create("Razorpay->refund   ::  Exception   : " . $e->getMessage());
		}

		$payResponse = $this->parsefetchedResponse($result);
		return $payResponse;
	}

	public function getPaymentStatus($pgModel)
	{
		$transcode = $pgModel->apg_code;

		$refundTrans = false;
		$payResponse = '';
		if ($pgModel->apg_mode == 1)
		{
			$refPaymentModel = PaymentGateway::model()->findByPk($pgModel->apg_ref_id);
			$refundTrans	 = true;
			$paymentId		 = $refPaymentModel->apg_txn_id;
			$payResponse	 = $this->fetchRefundStatus($paymentId, $pgModel);
		}
		else
		{
			$payResponse = $this->fetchPaymentStatus($transcode);
		}

		return $payResponse;
	}

	public function fetchPaymentStatus($transcode, $paymentId = 0)
	{
		try
		{
			$isPaymentId = false;
			if ($paymentId != "")
			{
				$isPaymentId = true;
				goto chkByPaymentId;
			}
			$pgModel = PaymentGateway::model()->getByCode($transcode);
			if (empty($pgModel->apg_pre_txn_id))
			{
				throw new Exception("Reference not found");
			}

			$paymentId = $pgModel->apg_txn_id;
			if ($pgModel && $pgModel->apg_status == 2)
			{
				$paymentId = '';
			}
			chkByPaymentId:
			$api = new Razorpay\Api\Api($this->key, $this->secret);
			if (!empty($paymentId))
			{
				$result = $api->payment->fetch($paymentId);
			}
			else
			{
				$resultOrder = $api->order->fetch($pgModel->apg_pre_txn_id)->payments();
				if ($resultOrder->count == 0)
				{
					$result = $api->order->fetch($pgModel->apg_pre_txn_id);
				}

				foreach ($resultOrder->items as $key => $value)
				{
					if ($value->status == 'captured')
					{
						$result = $value;
						break;
					}
					if ($value->notes['trnsCode'] == $pgModel->apg_code && $paymentId != '')
					{
						$result = $value;
						break;
					}
					$result = $value;
				}
			}
			if ($result->status == 'authorized')
			{
				$result = $result->capture(array('amount' => $result->amount, 'currency' => $result->currency));
			}
		}
		catch (Exception $e)
		{
			Logger::create("Razorpay->initiateRequest   ::  Exception   : " . $e->getMessage());
		}

		if (!$isPaymentId)
		{
			$timeDiffMin = abs(strtotime($pgModel->apg_start_datetime) - time()) / 60;
			if ($result->status == 'created' && $timeDiffMin > 20)
			{
				$payResponse = $this->orderCreatedResponse($pgModel);
				return $payResponse;
			}
		}
		$payResponse = $this->parsefetchedResponse($result);
		return $payResponse;
	}

	public function fetchRefundStatus($paymentId, $pgModel)
	{
		try
		{
			$api = new Razorpay\Api\Api($this->key, $this->secret);
			if (!empty($pgModel->apg_txn_id))
			{
				$result = $api->refund->fetch($pgModel->apg_txn_id);
			}
			else
			{
				$results = $api->payment->fetch($paymentId)->fetchMultipleRefund([]);
				foreach ($results[items] as $key => $value)
				{
					if ($value->receipt == $pgModel->apg_code)
					{
						$result = $value;
					}
				}
			}
		}
		catch (Exception $e)
		{
			Logger::create("Razorpay->initiateRequest   ::  Exception   : " . $e->getMessage());
		}
		$payResponse = $this->parsefetchedResponse($result);
		return $payResponse;
	}

	public function parsefetchedResponse($result)
	{
		$statusCode	 = 0;
		$statusType	 = 0;
		if ($result->entity == "refund")
		{
			switch ($result->status)
			{
				CASE "processed":
					$statusCode	 = 1;
					$statusType	 = 2;
					break;
				CASE "failed":
					$statusCode	 = 2;
					$statusType	 = 2;
					break;
				CASE "pending":
					$statusType	 = 1;
					break;
				default :
					$statusType	 = 1;
			}
		}
		if ($result->entity == "payment")
		{
			switch ($result->status)
			{
				CASE "created":
					$statusType	 = 1;
					break;
				CASE "authorized":
					$statusType	 = 1;
					break;
				CASE "captured":
					$statusCode	 = 1;
					$statusType	 = 2;
					break;
				CASE "refunded":
					$statusCode	 = 2;
					$statusType	 = 2;
					break;
				CASE "failed":
					$statusCode	 = 2;
					$statusType	 = 2;
					break;
				default :
					$statusType	 = 1;
			}
		}
		if ($result->entity == "order")
		{
			switch ($result->status)
			{
				CASE "created":
					$statusType	 = 1;
					break;
				CASE "attempted":
					$statusType	 = 1;
					break;
				CASE "paid":
					$statusCode	 = 1;
					$statusType	 = 2;
					break;
				default :
					$statusType	 = 1;
			}
		}
		$arr = [];
		foreach ($result as $key => $value)
		{
			$arr[$key] = $value;
			if (is_array($value) || is_object($value))
			{
				$arrInner = [];
				foreach ($value as $key1 => $val)
				{
					$arrInner[$key1] = $val;
				}
				$arr[$key] = $arrInner;
			}
		}
		$mode							 = $this->getMode($arr);
		$payResponse					 = new PaymentResponse();
		$payResponse->payment_type		 = PaymentType::TYPE_RAZORPAY;
		$payResponse->transaction_code	 = (empty($result->receipt)) ? $result->notes['trnsCode'] : $result->receipt;
		if (!$payResponse->transaction_code)
		{
			$payResponse->transaction_code = PaymentGateway::getByRzpOrderId($result->order_id);
		}
		$payResponse->response_code = $result->status;
		if ($result->entity != "order")
		{
			$payResponse->payment_code = $result->id;
		}
		$payResponse->response				 = json_encode($arr);
		$payResponse->message				 = $result->entity . " " . $result->status;
		$payResponse->payment_status		 = $statusCode;
		$payResponse->payment_status_type	 = $statusType;
		$payResponse->mode					 = $mode;
		return $payResponse;
	}

	public function orderCreatedResponse($pgModel)
	{
		$payResponse						 = new PaymentResponse();
		$payResponse->payment_type			 = PaymentType::TYPE_RAZORPAY;
		$payResponse->transaction_code		 = $pgModel->apg_code;
		$payResponse->response_code			 = "Failed";
		//$payResponse->payment_code			 = $result->id;
		$payResponse->response				 = '{"error":"payment id not found"}';
		$payResponse->message				 = "Payment Failed";
		$payResponse->payment_status		 = 2;
		$payResponse->payment_status_type	 = 2;
		return $payResponse;
	}

	public function getMode($arr)
	{
		$mode = 0;
		if ($arr['method'] != '')
		{
			$modeStr = $arr['method'];
			if ($arr['method'] == 'card')
			{
				$modeStr = $arr['card']['type'];
			}
			switch (strtolower($modeStr))
			{
				case 'credit':
					$mode = PaymentResponse::TYPE_CC;
					break;

				case 'debit':
					$mode = PaymentResponse::TYPE_DC;
					break;

				case 'netbanking':
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
				case 'wallet':
					$mode	 = PaymentResponse::TYPE_WALLET;
					break;
				default :
					$mode	 = 0;
			}
		}
		return $mode;
	}

}
