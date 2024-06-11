<?php

class SMSCountry
{

//    public static function sendMessage1($number, $template, $data)
//    {
//
//    }
	public static function sendMessage($ext, $num1, $data, $lang = Messages::MTYPE_ENGLISH)
	{
		$sendSMS	 = Yii::app()->params['sendSMS'];
		$demoNumber	 = isset(Yii::app()->params['demoNumber']) ? Yii::app()->params['demoNumber'] : '';
		$num		 = str_replace('-', '', $num1);
		$number		 = ($demoNumber == '') ? $num : $demoNumber;
		if (!$sendSMS)
		{
			return;
		}

		$user		 = "aaocab";
		$password	 = "66947994";
		$senderid	 = "GOZOCB";
		//$senderid="SMSCountry"; //Your senderid
		$messagetype = "N";
		$message	 = urlencode($data);
		if ($lang != Messages::MTYPE_ENGLISH)
		{
			$messagetype = "LNG";
			//	$message = self::utf8_to_unicode($data);
		}
		$DReports	 = "Y";
		$url		 = "http://api.smscountry.com/SMSCwebservice_bulk.aspx";
		///	echo "User=$user&passwd=$password&sid=$senderid&mobilenumber={$ext}{$number}&message=$message&mtype=$messagetype&DR=$DReports";

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
		curl_setopt($ch, CURLOPT_POSTFIELDS, "User=$user&passwd=$password&sid=$senderid&mobilenumber={$ext}{$number}&message=$message&mtype=$messagetype&DR=$DReports");
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

	public function utf8_to_unicode($str)
	{
		$unicode	 = array();
		$values		 = array();
		$lookingFor	 = 1;
		for ($i = 0; $i < strlen($str); $i++)
		{
			$thisValue = ord($str[$i]);
			if ($thisValue < 128)
			{
				$number		 = dechex($thisValue);
				$unicode[]	 = (strlen($number) == 1) ? '%u000' . $number : "%u00" . $number;
			}
			else
			{
				if (count($values) == 0)
					$lookingFor	 = ( $thisValue < 224 ) ? 2 : 3;
				$values[]	 = $thisValue;
				if (count($values) == $lookingFor)
				{
					$number		 = ( $lookingFor == 3 ) ?
							( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ) :
							( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64
							);
					$number		 = dechex($number);
					$unicode[]	 = (strlen($number) == 3) ? "%u0" . $number : "%u" . $number;
					$values		 = array();
					$lookingFor	 = 1;
				} // if
			} // if
		}
		return implode("", $unicode);
	}

}
