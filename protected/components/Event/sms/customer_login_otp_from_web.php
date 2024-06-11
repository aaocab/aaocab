<?php

try
{
	$smsLogType	 = $arrayData['smsLogType'];
	$otp		 = $arrayData['otp'];
	$smsTextType = $arrayData['smsTextType'];
	if ($smsTextType != null)
	{
		$dltId = smsWrapper::DLT_APP_OTP_TEMPID;
		if ($smsLogType == SmsLog::SMS_FORGET_PASSWORD)
		{
			$msg = $otp . ' is your Gozo OTP for Forgot Password. Do not share it with anyone. @www.aaocab.com #' . $otp;
		}
		else
		{
			$msg = $otp . ' is your Gozo OTP for login. Do not share it with anyone. @www.aaocab.com #' . $otp;
			
		}
		
	}
	else
	{
		$dltId = smsWrapper::DLT_OTP_TEMPID;
		if ($smsLogType == SmsLog::SMS_FORGET_PASSWORD)
		{
			$msg = $otp . ' is your Gozo OTP for Forgot Password. Do not share it with anyone - aaocab';
		}
		else
		{
			$msg = $otp . ' is your Gozo OTP for login. Do not share it with anyone.';
		}
	}
    //todo :  need to dynamic dltId 
    if ($smsLogType == SmsLog::SMS_FORGET_PASSWORD)
    {
        $dltId = $arrayData['dltId'];
    }
	$arrSmsData['content']	 = $msg;
	$arrSmsData['dltId']	 = $dltId;
	$templateName			 = 'customer_login_otp_from_web';
	echo json_encode(array('type' => TemplateMaster::SEQ_SMS_CODE, 'templateName' => $templateName, 'data' => $arrSmsData, 'status' => true));
}
catch (Exception $ex)
{

	echo json_encode(array('type' => TemplateMaster::SEQ_SMS_CODE, 'templateName' => '', 'data' => [], 'status' => false));
}