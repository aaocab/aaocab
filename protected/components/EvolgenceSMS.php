<?php

class EvolgenceSMS
{

//    public static function sendMessage1($number, $template, $data)
//    {
//
//    }
	public static function sendMessage($ext, $num1, $data)
	{
		if ($ext != '91')
		{
			return false;
		}
		$sendSMS	 = Yii::app()->params['sendSMS'];
		$demoNumber	 = isset(Yii::app()->params['demoNumber']) ? Yii::app()->params['demoNumber'] : '';
		$num		 = str_replace('-', '', $num1);
		$number		 = ($demoNumber == '') ? $num : $demoNumber;
		if (!$sendSMS)
		{
			return;
		}


		$user		 = "gozotrans";
		$password	 = "welcome123";
		$senderid	 = "GOZOCB";
		$type		 = "smsquicksend";
		$url		 = "http://login.spearuc.com/MOBILE_APPS_API/sms_api.php?type=smsquicksend";
		$message	 = urlencode($data);

		$ch = curl_init();
		if (!$ch)
		{
			die("Couldn't initialize a cURL handle");
		}
		$ret = curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "type=$type&user=$user&pass=$password&sender=$senderid&to_mobileno=$num1&sms_text=$message");
		$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//If you are behind proxy then please uncomment below line and provide your proxy ip with port.
		// $ret = curl_setopt($ch, CURLOPT_PROXY, "PROXY IP ADDRESS:PORT");

		$curlresponse = curl_exec($ch);

		// execute
		if (curl_errno($ch))
		//echo 'curl error : ' . curl_error($ch);
			if (empty($ret))
			{
				// some kind of an error happened
				die(curl_error($ch));
				curl_close($ch);
				// close cURL handler
			}
			else
			{
				$info = curl_getinfo($ch);
				curl_close($ch);
				// close cURL handler
				//echo "<br>";
				//echo $curlresponse;
				//echo "Message Sent Succesfully" ;
			}
		return $curlresponse;
	}

}
