<?php
try
{
	$bkgId	 = trim($id);
	$model	 = Booking::model()->findByPk($bkgId);
	if ($model != '' && ($model->bkgPref->bkg_blocked_msg == 1 || $model->bkgPref->bkg_send_email == 0))
	{
		throw new Exception("cannot send email", ReturnSet::ERROR_VALIDATION);
	}
	$response = Contact::referenceUserData($model->bkgUserInfo->bui_id, 1);
	if ($response->getStatus())
	{
		$email		 = $response->getData()->email['email'];
		$firstName	 = $response->getData()->email['firstName'];
	}
	$usertype	 = EmailLog::Consumers;
	$delivered	 = '';
	if ($email != '')
	{
		//$this->email_receipient	 = $email;
		$bookingId	 = Filter::formatBookingId($model->bkg_booking_id);
		//  changes
		$advance	 = 0;
		if ($model->bkgInvoice->bkg_advance_amount > 0)
		{
			$advance = round($model->bkgInvoice->bkg_advance_amount);
			$due	 = round($model->bkgInvoice->bkg_due_amount);
		}
		$hash	 = Yii::app()->shortHash->hash($bkgid);
		$getUrl	 = BookingUser::getPaymentLinkByEmail($model->bkg_id);
		$url	 = Filter::shortUrl($getUrl);
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
				$amountStr = ' Amount payable Rs.' . $model->bkgInvoice->bkg_total_amount;
			}
		}
		if ($model->bkgInvoice->bkg_corporate_credit > 0)
		{
			$amountStr = ' Amount due Rs.' . $model->bkgInvoice->bkg_due_amount;
		}
		if ($minPayExtra > 0)
		{
			$amountStr = ' For rescheduling minimum amount payable is Rs.' . $minPayExtra . '';
		}
		if ($model->bkgInvoice->bkg_convenience_charge > 0 && ($model->bkgInvoice->bkg_corporate_credit == '' || $model->bkgInvoice->bkg_corporate_credit == 0))
		{
			$Model11			 = clone $model;
			$Model11->bkgInvoice->calculateConvenienceFee(0);
			$Model11->bkgInvoice->calculateTotal();
			//$feeWithoutConvenience = $Model11->bkg_due_amount;
			$minamount			 = $Model11->bkgInvoice->calculateMinPayment();
			$minamtWithoutTax	 = $model->bkgInvoice->bkg_convenience_charge;
			$minamtWithTax		 = Filter::getServiceTax($minamtWithoutTax, $model->bkg_agent_id, $model->bkg_booking_type);
			$codFee				 = $minamtWithoutTax + $minamtWithTax;
		}

		$msgBody = 'Hello ' . $firstName .
				',<br/><br/>Thank you for choosing GozoCabs! For your Booking ID: ' . $bookingId . ',' . $amountStr .
				', please click on the following link to make an online payment.' .
				'<br/><a href="' . $url . '">' . $url . '</a>
		<br/><br/>Regards,<br/>Team Gozo';

		$subject = 'Payment link – Booking ID : ' . Filter::formatBookingId($model->bkg_booking_id);

		$templateName = null;

		$params	 = [
			'userName'	 => $firstName,
			'bookingId'	 => $bookingId,
			'amountStr'	 => $amountStr,
			'link'		 => $url];

		echo json_encode(array('type' => TemplateMaster::SEQ_EMAIL_CODE, 'templateName' => $templateName, 'subject' => $subject, 'body' => $msgBody, 'data' => $params, 'status' => true));
	}
	else
	{
		throw new Exception("cannot send email", ReturnSet::ERROR_VALIDATION);
	}
}
catch (Exception $ex)
{
	echo json_encode(array('type' => TemplateMaster::SEQ_EMAIL_CODE, 'templateName' => '', 'data' => [], 'status' => false));
}
?>