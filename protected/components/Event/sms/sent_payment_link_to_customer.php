<?php
try
{
		$success = true;
		$bkgId  = trim($id);
		$model = Booking::model()->findByPk($bkgId);
		if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_sms == 0))
		{
			$success = false;
		}
		if($success==false)
		{
			goto skipAll;
		}
		$sms		 = new Messages();
		$booking_id	 = $model->bkg_booking_id;
		$advance	 = 0;
		if ($model->bkgInvoice->bkg_advance_amount > 0)
		{
			$advance = round($model->bkgInvoice->bkg_advance_amount);
			$due	 = round($model->bkgInvoice->bkg_due_amount);
		}

		$maxTimeAdvPay = date('d/m/Y h:i A', strtotime($model->bkgTrail->bkg_payment_expiry_time));

		$hash		 = Yii::app()->shortHash->hash($bkgid);
		$url		 = 'aaocab.com' . BookingUser::getPaymentLinkByPhone($model->bkg_id);
		$url		 = Filter::shortUrl($url);
		$amountStr	 = '';
		if ($advance > 0)
		{
			if ($model->bkgInvoice->bkg_credits_used > 0)
			{
				$strCredit = ', Gozo Coins Used: ' . $model->bkgInvoice->bkg_credits_used;
			}
			$amountStr = ' Advance Paid: Rs.' . $advance . $strCredit . ', Amount due: Rs.' . $due;
		}
		else
		{
			if ($model->bkgInvoice->bkg_credits_used > 0)
			{
				$amountStr = ' Gozo Coins Used: ' . $model->bkgInvoice->bkg_credits_used . ', Amount due Rs.' . round($model->bkgInvoice->bkg_due_amount);
			}
			else
			{
				$amountStr = ' Amount payable Rs.' . $model->bkgInvoice->bkg_total_amount . ' - aaocab';
			}
		}
		if ($model->bkgInvoice->bkg_corporate_credit > 0)
		{
			$amountStr = ' Amount due ' . round($model->bkgInvoice->bkg_due_amount);
		}

		if ($model->bkgInvoice->bkg_convenience_charge > 0 && ($model->bkgInvoice->bkg_corporate_credit == '' || $model->bkgInvoice->bkg_corporate_credit == 0))
		{
			$Model11	 = clone $model->bkgInvoice;
			$Model11->calculateConvenienceFee(0);
			$Model11->calculateTotal();
			$minamount	 = $Model11->calculateMinPayment();
			$amountStr .= ". Waive off COD fee (Rs." . $model->bkgInvoice->bkg_convenience_charge . ") by paying Rs." . $minamount . " in advance before " . $maxTimeAdvPay . " to save upto 50%";
		}

		if ($minPayExtra > 0)
		{
			$amountStr = "For rescheduling minimum amount payable is Rs." . $minPayExtra;
		}
		//changes
		$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 2);
		if ($response->getStatus())
		{
			$contactNo	 = $response->getData()->phone['number'];
			$countryCode = $response->getData()->phone['ext'];
			$userName	 = $response->getData()->phone['userName'];
		}
		$msg		 = 'Dear ' . $userName . ', for your Booking ID: ' . $booking_id . '.' . $amountStr . ',Click ' . $url . ' make the payment.';

	
		$templateName			 = 'sent_payment_link_to_customer';
		$arrSmsData['content']	 = $msg;
		echo json_encode(array('type' => TemplateMaster::SEQ_SMS_CODE, 'templateName' => $templateName, 'data' => $arrSmsData, 'status' => $success));
} 
catch (Exception $ex) 
{
	skipAll:	
	echo json_encode(array('type' => TemplateMaster::SEQ_SMS_CODE, 'templateName' => '', 'data' => [], 'status' => false));
}
