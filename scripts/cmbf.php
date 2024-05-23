#!/usr/bin/php -q
<?php
/*
  FileName : elision_integration_gozocab.agi
  Created By : Puja Gediya
  Date : 31/01/2017
  Discription : Take Dynamic value from dialplan based on did call clients api and based on responce perform different opration
 */
#error_reporting(E_ALL);
#ini_set("display_errors",1);
set_time_limit(30);

require('/var/lib/asterisk/agi-bin/phpagi/phpagi.php');

require('/srv/www/htdocs/vicidial/dbconnect_mysqli.php');

require('/srv/www/htdocs/scripts/api_config.php');
$agi			 = new AGI();
//$agi = new AGI();
$agi->verbose("START");



function initNewCall($agi)
{
	$callerNumber	 = $agi->request['agi_callerid'];
	$result			 = UserNumber($callerNumber, $agi);

	if ($result)
	{
		$userId	 = $result["consumerId"];
		$data	 = setNewFollowup($callerNumber, $userId, $agi);
	}
	else
	{
		PlayUserIVR($callerNumber);
	}
}

function initExistingCall($agi)
{
	$callerNumber	 = $agi->request['agi_callerid'];
	$result			 = UserNumber($callerNumber, $agi);

	if ($result)
	{
		$userId			 = $result["consumerId"];
		//$agi->stream_file('Ask-BookingID-English-Hindi-8khz');
		$resultStream	 = $agi->get_data('Ask-BookingID-English-Hindi-8khz', 20000, 7);

		$bkgId = $resultStream['result'];

		$agi->verbose("You entered $bkgId");

		$data = setExistingFollowup($callerNumber, $bkgId, $userId, $agi);
	}
	else
	{
		PlayUserIVR($callerNumber);
	}
}

function PlayUserIVR($callerNumber, $agi)
{
	$agi->answer();
	$result = $agi->get_data('Ask-BookingID-English-Hindi-8khz', 20000, 1);

	$keys = $result['result'];
	if ($keys == "1")//new user
	{
		$data = setNewFollowup($callerNumber, null, $agi);
	}
	else //existing user asking for registered number
	{
		$result	 = $agi->get_data('Ask-BookingID-English-Hindi-8khz', 30000, 12);
		$keys	 = $result['result'];
		$agi->verbose("You entered $keys");
		$result	 = UserNumber($keys, $agi);
		if ($result)
		{
			$userId	 = $result["consumerId"];
			$data	 = setNewFollowup($callerNumber, $userId, $agi);
		}
		else
		{
			$agi->stream_file(''); //user not registered
			initNewCall($agi);
		}
	}

	//CallAPI($callerType,'9593575493',$agi,$keys); # for customer->driver

	CallAPI($callerType, $phone_number, $agi, $keys, $sip);  # for driver->customer
}

$UNIQUEID = trim($agi->request['agi_uniqueid']);
$agi->verbose($UNIQUEID);

function CallAPI($urlPath, $params, $agi)
{
	require_once '/srv/www/htdocs/scripts/api_config.php';
	$agi->verbose("CURL URL: " . $urlPath, 3);
	$agi->verbose("CURL PARAMS: " . json_encode($params), 3);
	// create curl resource
	$queryString = http_build_query($params);
	$api_url	 .= $urlPath . "?" . $queryString;
	$ch			 = curl_init();

	curl_setopt($ch, CURLOPT_URL, $api_url);

	//return the transfer as a string
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSLVERSION, 6);
	//	curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

	$agi->verbose("CURL START", 3);
	$output = curl_exec($ch);
	curl_close($ch);

	if (curl_errno($ch))  //catch if curl error exists and show it
	{
		$agi->verbose('Curl error: ' . curl_error($ch), 1);
		return false;
	}
	$response = json_decode($output, true);
	$agi->verbose($output, 1);
	$agi->verbose("CURL END", 3);
	return $response;
}

function verifyEntityNumber($agi, $number, $entity = 0)
{
	$params					 = [];
	$params["callerNumber"]	 = $number;
	$params["checkEntity"]	 = $entity;
	$urlPath				 = "/api/dialer/getContactProfile";

	$response = CallAPI($urlPath, $params, $agi);

	if (!$response)
	{
		return null;
	}

	if ($response["success"])
	{
		$data = $response["data"];
	}
	else
	{
		$errorCode = $response['errorCode'];
		switch ($errorCode)
		{
			case 105://["Invalid phone number"]
				break;
			case 106:// 
				switch ($entity)
				{
					case 0://["number is not registered"]
						break;
					case 1://["consumer is not registered"]
						break;
					case 2://["vendor is not registered"]
						break;
					case 3://["driver is not registered"]
						break;
					default ://["invalid option"]
						break;
				}
				break;
			case 107://["invalid option"]
				break;
		}
		return false;
	}

	return $data;
}

function UserNumber($number, $agi)
{
	return verifyEntityNumber($agi, $number, 1);
}

function VendorNumber($number, $agi)
{
	return verifyEntityNumber($agi, $number, 2);
}

function DriverNumber($number, $agi)
{
	return verifyEntityNumber($agi, $number, 3);
}

function setFollowup($params, $agi)
{
	$number	 = $params['callerNumber'];
	$data	 = UserNumber($number, $agi);
	if (!$data)
	{
		return false;
	}
	$id			 = $data['consumerId'];
	$response	 = setNewFollowup($number, $id, $agi);

	return $response;
}

/*
 * if only number is supplied, followup will be created for the number. it will check contact id associted.
 * if id (userid) is supplied, followup will be created for the userId and call back will come on the number.
 */

function setNewFollowup($number, $userId = null, $agi)
{
	$params					 = [];
	$params["callerNumber"]	 = $number;
	if ($userId > 0)
	{
		$params['userId'] = $userId;
	}

	$urlPath = "/api/dialer/setNewFollowup";

	$response = CallAPI($urlPath, $params, $agi);

	if ($response['success'])
	{
		$data		 = $response['data'];
		$queueNumber = $data['queNo'];
		$waitTime	 = $data['waitTime'];

		$agi->verbose(json_encode($data), 2);
		$agi->stream_file("Estimated-Wait-Time");
		$agi->say_number($waitTime);
		$agi->stream_file("Estimated-Minutes");
		$agi->stream_file("Estimated-Wait-Time-Hindi");
		$agi->say_number($waitTime);
		$agi->stream_file("Estimated-Minutes");
		$agi->stream_file("Caller-Number");
		$agi->say_number($queueNumber);
	}
	else
	{
		
	}
	$agi->hangup();
	return $response;
}

function setExistingFollowup($number, $bkgId, $userId = null, $agi)
{
	$params					 = [];
	$params["callerNumber"]	 = $number;
	if ($userId > 0)
	{
		$params['userId'] = $userId;
	}
	$params['bkgId'] = $bkgId;
	$urlPath		 = "/api/dialer/setNewFollowup";

	$response = CallAPI($urlPath, $params, $agi);

	if ($response['success'])
	{
		$data		 = $response['data'];
		$queueNumber = $data['queNo'];
		$waitTime	 = $data['waitTime'];

		$agi->verbose(json_encode($data), 2);
		$agi->stream_file("Your request has been accepted");
		$agi->stream_file("You are caller number ");
		$agi->say_number($queueNumber);
		$agi->stream_file("Your expected wait time is ");
		$agi->say_number($waitTime);
		$agi->stream_file("minutes ");
	}
	else
	{
		
	}
	$agi->hangup();
	return $response;
}

function UpdateLog($dest_number, $agi)
{
	global $link;
	$UNIQUEID	 = $agi->request['agi_uniqueid'];
	$agi->verbose("UpdateLog::UNIQUEID: $UNIQUEID");
	$usql		 = "UPDATE elision_recording_log SET dest_phone_number ='" . $dest_number . "' WHERE uniqueid='" . $UNIQUEID . "'";
	$agi->verbose($usql);
	mysqli_query($link, $usql);
}

function MakeCall($dest_number, $agi, $sip = 'gozo')
{
	UpdateLog($dest_number, $agi);
	$agi->verbose($dest_number);
	$result = $agi->exec("DIAL", "SIP/$sip/$dest_number,30,Ttor");
	return $result;
}

function PlayIVR($respcode, $agi, $callerType, $phone_number, $dest_number, $sip)
{
	if ($respcode == '101')
	{
		UpdateLog('101', $agi);
		$agi->answer();

		//$agi->stream_file('Ask-BookingID-English-Hindi-8khz');
		$result = $agi->get_data('Ask-BookingID-English-Hindi-8khz', 20000, 6);

		$keys = $result['result'];

		$agi->verbose("You entered $keys");

		//CallAPI($callerType,'9593575493',$agi,$keys); # for customer->driver

		CallAPI($callerType, $phone_number, $agi, $keys, $sip);  # for driver->customer
	}
	if ($respcode == '110')
	{
		UpdateLog('110', $agi);
		$agi->answer();

		$agi->stream_file('NoMatch-BookingID-Hindi-English-8khz');
		$agi->exec("AGI", "agi-VDAD_ALL_inbound.agi,CID-----LB-----gozo_existing-----gozo-3--------------------998-----1-----001------------------------------");
	}

	if ($respcode == '111')
	{
		UpdateLog('111', $agi);
		$agi->answer();
		$agi->exec("AGI", "agi-VDAD_ALL_inbound.agi,CID-----LB-----gozo_existing-----gozo-3--------------------998-----1-----001------------------------------");
	}


	if ($respcode == '102')
	{
		UpdateLog('102', $agi);
		$agi->answer();

		//	$agi->stream_file('Ask-BookingID-English-Hindi-8khz');
		$result = $agi->get_data('Ask-BookingID-English-Hindi-8khz', 20000, 6);

		$keys = $result['result'];

		$agi->verbose("You entered $keys");

		//CallAPI($callerType,'9593575493',$agi,$keys); # for customer->driver

		CallAPI($callerType, $phone_number, $agi, $keys, $sip);  # for driver->customer
	}
}

initNewCall($agi);
