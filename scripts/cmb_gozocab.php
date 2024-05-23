<?php

#error_reporting(E_ALL);
#ini_set("display_errors",1);
set_time_limit(30);

require('/var/lib/asterisk/agi-bin/phpagi/phpagi.php');
require('/srv/www/htdocs/vicidial/dbconnect_mysqli.php');
require('/srv/www/htdocs/scripts/api_config.php');
$agi					 = new AGI();
$agi->verbose("START");
$UNIQUEID				 = trim($agi->request['agi_uniqueid']);
$agi->verbose($UNIQUEID);
$GLOBALS["existingCtr"]	 = 0;

function initNewCall($agi, $number = null)
{
	$agi->answer();
	$userNumber		 = $callerNumber	 = $agi->request['agi_callerid'];
	if ($number !== null)
	{
		$userNumber = $number;
	}
	try
	{
		$result	 = UserNumber($userNumber, $agi);
		$userId	 = $result["consumerId"];
		$data	 = setNewFollowup($callerNumber, $userId, $agi);
		exit;
	}
	catch (Exception $ex)
	{
		$code = $ex->getCode();
		if (in_array($code, [105, 106]))
		{
			if ($number !== null)
			{
				$agi->stream_file("Customer-Phone-Not-Found");
			}
			verifyNewExistingCustomer($agi);
			exit;
		}
		$agi->hangup();
	}
}

function initExistingCall($agi, $number = null, $bkgId = null)
{
	$agi->answer();
	$userNumber		 = $callerNumber	 = $agi->request['agi_callerid'];

	try
	{
		if ($number !== null)
		{
			$userNumber = $number;
		}
		$isOnGoingTrip = isOngoingTrip($userNumber, $bkgId, $agi);
		
		if ($bkgId == null)
		{
			$bkgId = inputBookingID($agi);
			$isOnGoingTrip = isOngoingTrip($userNumber, $bkgId, $agi);
		}
		$success = setExistingFollowup($agi, $userNumber, $bkgId, $callerNumber);
	}
	catch (Exception $ex)
	{
		$agi->verbose("existingCtr0: " . $GLOBALS["existingCtr"]);
		$code = $ex->getCode();
		$GLOBALS["existingCtr"]++;
		if ($GLOBALS["existingCtr"] > 2)
		{
			$agi->verbose("existingCtr1: " . $GLOBALS["existingCtr"]);
//			transferToExistingQueue($agi, $code);
//			return;
		}
		if (in_array($code, [201]))
		{
			transferToExistingQueue($agi, $code);
			return;
		}
		if (in_array($code, [104]))
		{
			$agi->stream_file("Booking-Not-Found");
			initExistingCall($agi, $userNumber);
			return;
		}
		if (in_array($code, [105]))
		{
			if ($number == null)
			{
				$agi->stream_file("Called-Phone-Booking-Not-Matched");
			}
			else
			{
				$agi->stream_file("Entered-Phone-Booking-Not-Matched");
			}
			$userNumber = inputCustomerPhone($agi);
			initExistingCall($agi, $userNumber, $bkgId);
			return;
		}
		$agi->hangup();
	}

	if (!$success)
	{
		$agi->verbose("existingCtr: " . $GLOBALS["existingCtr"]);
		$GLOBALS["existingCtr"]++;
		if ($GLOBALS["existingCtr"] > 3)
		{
			transferToExistingQueue($agi, $code);
			return;
		}
		initExistingCall($agi);
	}
	$agi->hangup();
}

function initDriverCall($agi, $number = null)
{
	$agi->answer();
	$driverNumber	 = $callerNumber	 = $agi->request['agi_callerid'];
	$queId			 = '';
	if ($number !== null)
	{
		$driverNumber = $number;
	}
	try
	{
		$isOnGoingTrip = isDriverOngoingTrip($driverNumber, $agi);
		
		verifyExistingDriver($agi);
		exit;
	}
	catch (Exception $ex)
	{
		$code = $ex->getCode();
		
		if (in_array($code, [201]))
		{
			transferToVendorQueue($agi, $code);
			return;
		}
		
		if (in_array($code, [105, 106]))
		{
			if ($number !== null)
			{
				$agi->stream_file("Gozo-Phone-Not-Found-Enter-Number");
			}
			verifyExistingDriver($agi);
			exit;
		}
		$agi->hangup();
	}
}

function initVendorCall($agi, $number = null)
{
	$agi->answer();
	$vendorNumber	 = $callerNumber	 = $agi->request['agi_callerid'];
	$queId			 = '';
	if ($number !== null)
	{
		$vendorNumber = $number;
	}
	try
	{
		$result		 = VendorNumber($vendorNumber, $agi);
		$vendorId	 = $result["vendorId"];
		$data		 = setVendorFollowup($callerNumber, $queId, $vendorId, $agi);
		exit;
	}
	catch (Exception $ex)
	{
		$code = $ex->getCode();
		if (in_array($code, [105, 106]))
		{
			if ($number !== null)
			{
				$agi->stream_file("Gozo-Phone-Not-Found-Enter-Number");
			}
			verifyExistingVendor($agi);
			exit;
		}
		$agi->hangup();
	}
}

function verifyExistingVendor($agi)
{
	$result	 = $agi->get_data('Enter-Phone', 15000, 15);
	$phone	 = $result['result'];
	$agi->verbose("Phone Number: " . $phone);
	$confirm = confirmNumber($phone, $agi);
	if ($confirm)
	{
		initVendorCall($agi, $phone);
	}
	else
	{
		verifyExistingVendor($agi);
	}
}

function verifyExistingDriver($agi)
{
	$result	 = $agi->get_data('Enter-Phone', 15000, 15);
	$phone	 = $result['result'];
	$agi->verbose("Phone Number: " . $phone);
	$confirm = confirmNumber($phone, $agi);
	if ($confirm)
	{
		initDriverCall($agi, $phone);
	}
	else
	{
		verifyExistingDriver($agi);
	}
}


function inputCustomerPhone($agi)
{
	$result	 = $agi->get_data('Enter-Phone', 15000, 15);
	$phone	 = $result['result'];
	$agi->verbose("Phone Number: " . $phone);
	$confirm = confirmNumber($phone, $agi);
	if ($confirm)
	{
		return $phone;
	}
	else
	{
		return inputCustomerPhone($agi);
	}
	exit;
}

function inputBookingID($agi)
{
	$result	 = $agi->get_data('	Last-7-Digit-Booking-Id', 15000, 7);
	$bkgId	 = $result['result'];
	$agi->verbose("Booking ID Entered: " . $bkgId);
	$confirm = confirmNumber($bkgId, $agi);
	if ($confirm)
	{
		return $bkgId;
	}
	else
	{
		return inputBookingID($agi);
	}
	exit;
}

function verifyNewExistingCustomer($agi)
{
	$agi->answer();
	$callerNumber	 = $agi->request['agi_callerid'];
	$result			 = $agi->get_data('Menu-New-Existing-Customer', 15000, 1);
	$keys			 = $result['result'];
	if ($keys == "2")//new user
	{
		$data = setNewFollowup($callerNumber, null, $agi);
		exit;
	}
	if ($keys == "1")
	{
		$result	 = $agi->get_data('Enter-Phone', 15000, 15);
		$phone	 = $result['result'];
		$agi->verbose("Phone Number: " . $phone);
		$confirm = confirmNumber($phone, $agi);
		if ($confirm)
		{
			initNewCall($agi, $phone);
		}
		else
		{
			verifyNewExistingCustomer($agi);
		}
		exit;
	}
}

function confirmNumber($number, $agi)
{
	$agi->stream_file("Number-Entered");
	$agi->say_digits($number);

	$result	 = $agi->get_data('Confirm-Number', 15000, 1);
	$keys	 = $result['result'];
	$success = ($keys == "1");
	return $success;
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

function initNewVendorCall($agi, $number = null)
{
	$agi->answer();
	$userNumber		 = $callerNumber	 = $agi->request['agi_callerid'];
	if ($number !== null)
	{
		$userNumber = $number;
	}
	try
	{
		$data = setNewVendorFollowup($callerNumber, $agi);
		exit;
	}
	catch (Exception $ex)
	{
		$code = $ex->getCode();
		if (in_array($code, [105, 106]))
		{
			if ($number !== null)
			{
				$agi->stream_file("Customer-Phone-Not-Found");
			}
			verifyNewExistingCustomer($agi);
			exit;
		}
		$agi->hangup();
	}
}

function CallAPI($urlPath, $params, $agi)
{
	global $base_url;

	$agi->verbose("CURL PARAMS: " . json_encode($params), 3);
	// create curl resource
	$queryString = http_build_query($params);
	$api_url	 = $base_url . $urlPath . "?" . $queryString;
	$agi->verbose("CURL URL: " . $api_url, 3);
	$ch			 = curl_init();
	curl_setopt($ch, CURLOPT_URL, $api_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSLVERSION, 6);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

	$output = curl_exec($ch);
	curl_close($ch);

	if (curl_errno($ch))  //catch if curl error exists and show it
	{
		$agi->verbose('Curl error: ' . curl_error($ch), 1);
		return false;
	}
	$response = json_decode($output, true);
	$agi->verbose($output, 2);
	return $response;
}

function verifyEntityNumber($agi, $number, $entity = 0)
{
	$params					 = [];
	$params["callerNumber"]	 = $number;
	$params["checkEntity"]	 = $entity;
	$urlPath				 = "/dialer/getContactProfile";

	$response = CallAPI($urlPath, $params, $agi);
	return $response;
}

function EntityNumber($type, $number, $agi)
{
	$result = verifyEntityNumber($agi, $number, $type);

	$success = $result["success"];

	if ($success)
	{
		return $result["data"];
	}
	else
	{
		$errorMessage	 = json_encode($result["errors"]);
		$errorCode		 = $result["errorCode"];
		throw new Exception($errorMessage, $errorCode);
	}
}

function VendorNumber($number, $agi)
{
	return EntityNumber(2, $number, $agi);
}

function UserNumber($number, $agi)
{
	return EntityNumber(1, $number, $agi);
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

function playQueueInfo($agi, $data)
{
	$agi->verbose(json_encode($data), 2);
	$queueNumber = $data['queNo'];
	$waitTime	 = $data['waitTime'];
	$agi->answer();
	$agi->stream_file("Request-Accepted-Caller-Number");
	$agi->say_number($queueNumber);
	$agi->stream_file("Estimated-Call-Back-Time");
	$agi->say_number($waitTime);
	$agi->stream_file("Estimated-Minutes");
	$agi->stream_file("Estimated-Call-Back-Time-Hindi");
	$agi->say_number($waitTime);
	$agi->stream_file("Estimated-Minutes");
}

/*
 * if only number is supplied, followup will be created for the number. it will check contact id associted.
 * if id (userid) is supplied, followup will be created for the userId and call back will come on the number.
 */

function isOngoingTrip($number, $bkgId, $agi)
{
	$params					 = [];
	$params["callerNumber"]	 = $number;
	if($bkgId != null)
	{
		$params["bkgID"]	 = $bkgId;
	}

	$urlPath = "/dialer/checkOngoingTrip";

	$response = CallAPI($urlPath, $params, $agi);

	if ($response['success'])
	{
		$errorMessage	 = "Ongoing trip in progress";
		$errorCode		 = 201;
		throw new Exception($errorMessage, $errorCode);
	}
	else
	{
		return $response['success'];
	}
	
}

function isDriverOngoingTrip($number, $agi)
{
	$params					 = [];
	$params["callerNumber"]	 = $number;

	$urlPath = "/dialer/checkDriverOngoingTrip";

	$response = CallAPI($urlPath, $params, $agi);

	if ($response['success'])
	{
		$errorMessage	 = "Ongoing trip in progress";
		$errorCode		 = 201;
		throw new Exception($errorMessage, $errorCode);
	}
	else
	{
		return $response['success'];
	}
	
}


function setNewFollowup($number, $userId = null, $agi)
{
	$params					 = [];
	$params["callerNumber"]	 = $number;
	if ($userId > 0)
	{
		$params['userId'] = $userId;
	}

	$urlPath = "/dialer/setNewFollowup";

	$response = CallAPI($urlPath, $params, $agi);

	if ($response['success'])
	{
		$data = $response['data'];
		playQueueInfo($agi, $data);
	}
	else
	{
		$errorMessage	 = json_encode($response["errors"]);
		$errorCode		 = $response["errorCode"];
		throw new Exception($errorMessage, $errorCode);
	}
	$agi->hangup();
}

function setExistingFollowup($agi, $number, $bkgId, $callerNumber)
{
	$params					 = [];
	$params["callerNumber"]	 = $callerNumber;
	$params["userNumber"]	 = $number;
	$params['bkgID']		 = $bkgId;

	$urlPath = "/dialer/setExistingFollowup";

	$response = CallAPI($urlPath, $params, $agi);

	if ($response['success'])
	{
		$data = $response['data'];
		playQueueInfo($agi, $data);
	}
	else
	{
		$errorMessage	 = json_encode($response["errors"]);
		$errorCode		 = $response["errorCode"];
		throw new Exception($errorMessage, $errorCode);
	}
	$agi->hangup();
}

function setVendorFollowup($number, $queId = '', $vendorId = null, $agi)
{
	$params					 = [];
	$params["callerNumber"]	 = $number;
	$params["queId"]		 = $queId;
	if ($vendorId > 0)
	{
		$params['vendorId'] = $vendorId;
	}

	$urlPath = "/dialer/setVendorFollowup";

	$response = CallAPI($urlPath, $params, $agi);

	if ($response['success'])
	{
		$data		 = $response['data'];
		$queueNumber = $data['queNo'];
		$waitTime	 = $data['waitTime'];

		$agi->verbose(json_encode($data), 2);
		$agi->stream_file("Request-Accepted-Caller-Number");
		$agi->say_number($queueNumber);
		$agi->stream_file("Estimated-Call-Back-Time");
		$agi->say_number($waitTime);
		$agi->stream_file("Estimated-Minutes");
		$agi->stream_file("Estimated-Call-Back-Time-Hindi");
		$agi->say_number($waitTime);
		$agi->stream_file("Estimated-Minutes");
	}
	else
	{
		
	}
	$agi->hangup();
	return $response;
}


function setDriverFollowup($agi, $number, $queId = '', $driverId = null)
{
	$params					 = [];
	$params["callerNumber"]	 = $number;
	$params["queId"]		 = $queId;
	if ($driverId > 0)
	{
		$params['vendorId'] = $driverId;
	}

	$urlPath = "/dialer/setDriverFollowup";

	$response = CallAPI($urlPath, $params, $agi);

	if ($response['success'])
	{
		$data		 = $response['data'];
		$queueNumber = $data['queNo'];
		$waitTime	 = $data['waitTime'];

		$agi->verbose(json_encode($data), 2);
		$agi->stream_file("Request-Accepted-Caller-Number");
		$agi->say_number($queueNumber);
		$agi->stream_file("Estimated-Call-Back-Time");
		$agi->say_number($waitTime);
		$agi->stream_file("Estimated-Minutes");
		$agi->stream_file("Estimated-Call-Back-Time-Hindi");
		$agi->say_number($waitTime);
		$agi->stream_file("Estimated-Minutes");
	}
	else
	{
		
	}
	$agi->hangup();
	return $response;
}

/*
 * if only number is supplied, followup will be created for the number. it will check contact id associted.
 *  
 */

function setNewVendorFollowup($number, $agi)
{
	$params					 = [];
	$params["callerNumber"]	 = $number;

	$urlPath = "/dialer/setNewVendorFollowup";

	$response = CallAPI($urlPath, $params, $agi);

	if ($response['success'])
	{
		$data = $response['data'];
		playQueueInfo($agi, $data);
	}
	else
	{
		
	}
	$agi->hangup();
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
	$result = $agi->exec_dial("SIP", "$sip/$dest_number", 25, "Ttor");
	return $result;
}

function MonitorCall($agi, $did, $phone, $prefix)
{
	require('/srv/www/htdocs/vicidial/dbconnect_mysqli.php');
	$UNIQUEID = trim($agi->request['agi_uniqueid']);
	$agi->verbose(json_encode($agi->request));

	$FileName = $phone . '_' . date('Ymd-His');
	if ($prefix != "")
	{
		$FileName = $prefix . "_" . $FileName;
	}
	$file = $agi->exec("Monitor", "wav," . $FileName . ",m");

	$sql = "INSERT INTO elision_recording_log (uniqueid, did, src_phone_number, recording_filename, call_datetime, call_type)	
				VALUES ('" . $UNIQUEID . "','" . $did . "','" . $phone . "','" . $FileName . "','" . date('Y-m-d H:i:s') . "','INBOUND')";
	mysqli_query($link, $sql);
	return $FileName;
}

function getLeadNumber($agi, $number = null, $leadId = 0)
{
	$params					 = [];
	$params["callerNumber"]	 = $number;
	if ($leadId > 0)
	{
		$params['scqId'] = $leadId;
	}

	$urlPath = "/dialer/setNewFollowup";

	$response = CallAPI($urlPath, $params, $agi);

	if ($response['success'])
	{
		$data = $response['data'];
	}
	else
	{
		throw new Exception("", $response["errorCode"]);
	}
	return $data;
}

function initOpsCall($agi, $leadId = null)
{
	$agi->answer();
	$userNumber		 = $callerNumber	 = $agi->request['agi_callerid'];
	try
	{
		$data = getLeadNumber($agi, $userNumber, $leadId);
		MonitorCall($agi, $did, $phone, $prefix);
		MakeCall($dest_number, $agi);
		exit;
	}
	catch (Exception $ex)
	{
		$code = $ex->getCode();
		if (in_array($code, [105, 106]))
		{
			if ($number !== null)
			{
				$agi->stream_file("Customer-Phone-Not-Found");
			}
			verifyNewExistingCustomer($agi);
			exit;
		}
		$agi->hangup();
	}
}

function transferToVendorQueue($agi, $respcode)
{
	$agi->verbose("respcode: " . $respcode);
	$agi->answer();
	$agi->verbose("respcode: " . $respcode);
	UpdateLog($respcode, $agi);

	$agi->exec("AGI", "agi-VDAD_ALL_inbound.agi,CID-----LB-----gozo_vendor-----gozo-3--------------------998-----1-----001------------------------------");
}

function transferToExistingQueue($agi, $respcode)
{
	$agi->verbose("respcode: " . $respcode);
	$agi->answer();
	$agi->verbose("respcode: " . $respcode);
	UpdateLog($respcode, $agi);
	if (in_array($respcode,['104','105']))
	{
		$agi->stream_file('NoMatch-BookingID-Hindi-English-8khz');
	}

	$agi->exec("AGI", "agi-VDAD_ALL_inbound.agi,CID-----LB-----gozo_existing-----gozo-3--------------------998-----1-----001------------------------------");
}
