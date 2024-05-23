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
$agi = new AGI();
//$agi = new AGI();
$agi->verbose("START");
$did			 = $argv[1];
$phone_number	 = $argv[2];

//$phone_number = '9593575496'; # Ride is Active customer->driver
//$phone_number = '9593575493' ; # Ride is Completed customer->driver
//$phone_number = '9593575495' ; # Multiple Ride customer->driver
//$phone_number = '9593575492' ; # NO Ride customer->driver
//$phone_number = '8017504036'; # Ride is Active driver->customer
//$phone_number = '9903430854' ; # Ride is Completed driver->customer
//$phone_number = '9903430853' ; # Multiple Ride driver->customer

$prefix = '+91'; 

if (substr($phone_number, 0, strlen($prefix)) == $prefix) {
    $phone_number = substr($phone_number, strlen($prefix));
} 

$UNIQUEID = trim($agi->request['agi_uniqueid']);
$agi->verbose($UNIQUEID);



$FileName	 = $phone_number . '_' . date('Ymd-His');
$file		 = $agi->exec("Monitor", "wav," . $FileName . ",m");

$sql = "INSERT INTO elision_recording_log (uniqueid, did, src_phone_number, recording_filename, call_datetime, call_type) VALUES ('" . $UNIQUEID . "','" . $did . "','" . $phone_number . "','" . $FileName . "','" . date('Y-m-d H:i:s') . "','INBOUND')";
mysqli_query($link, $sql);


if ($did == '03371122003' || $did == '66778813')
{
	$callerType = '1';
}
if ($did == '66778814' || $did == '03371122004')
{
	$callerType = '2';
}
$sip = 'gozo';
$prefix = "";
switch ($did)
{
	case '03371122003':
		$sip = 'gozoDriverVodafone';
		break;
	case '66778813':
		$sip = 'tataSupport4';
		$agi->exec("SIPAddHeader", '"P-Preferred-Identity: <sip:66778814@10.53.48.2>"');
		break;
	case '66778814':
		$agi->exec("SIPAddHeader", '"P-Preferred-Identity:<sip:66778813@10.53.48.2>"');
		$sip = 'tataSupport3';
		break;
	case '03371122004':
	default:
		$sip = 'gozoConsumerVodafone';
		break;
}

if ($callerType != '' && $phone_number != '')
{
	CallAPI($callerType, $phone_number, $agi, '', $sip);
}

function CallAPI($callerType, $phone_number, $agi, $booking_id, $sip='gozo')
{
	require('/srv/www/htdocs/scripts/api_config.php');
	// create curl resource
	$bookingid	 = substr($booking_id, -6);
	$ch			 = curl_init();
	if ($booking_id == '')
	{
		$url = $api_url . '?callerType=' . $callerType . '&callerNumber=' . $phone_number . '&apiKey=' . $api_key;
		$agi->verbose($url);
	}
	else
	{
		$url = $api_url . '?callerType=' . $callerType . '&callerNumber=' . $phone_number . '&bookingID=' . $bookingid . '&apiKey=' . $api_key;
	}
	// set url
	curl_setopt($ch, CURLOPT_URL, $url);

	//return the transfer as a string
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSLVERSION, 6); 
//	curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	$agi->verbose("INIT");
	// $output contains the output string
	$output = file_get_contents($url);
//	$agi->verbose(curl_error($ch));

	$responce = json_decode($output, true);
	$agi->verbose("START");
	$agi->verbose($output);
	$agi->verbose("END");
	$success = ($responce['success'] == '1' ? 'success' : 'error');
	//print_r($responce) ;

	$bookingID = $responce['data']['bookingID'];
	$agi->verbose("bookingID: $bookingID");
	if ($callerType == '1')
	{
		$dest_number = $responce['data']['Driver Number'];
	}
	if ($callerType == '2')
	{
		$dest_number = $responce['data']['Customer Number'];
	}

	$bookingStatus = $responce['data']['bookingStatus'];

	$respcode = $responce['data']['respCode'];

	$data = $callerType . "|" . $phone_number . "|" . $bookingID . "|" . $dest_number . "|" . $bookingStatus;
	$agi->verbose("data: $data");
	$logquery = "INSERT INTO vicidial_api_log (`api_date` ,`api_script` ,`function` ,`agent_user` ,`value` ,`result` ,`result_reason` ,`source` ,`data` ,`run_time`)VALUES ('" . date('Y-m-d H:i:s') . "' ,'gozocab_api' , 'CallGozocabAPI', 'API' ,'" . $phone_number . "' ,'" . $success . "' ,'" . $output . "' , 'API' ,'" . $data . "' ,'0')";

	$queryresult = mysqli_query($link, $logquery);

	if ($success == 'success')
	{
		if ($bookingStatus == 'Active')
		{
			MakeCall($dest_number, $agi, $sip);
		}
		if ($bookingStatus == 'Completed')
		{
			MakeCall($dest_number, $agi, $sip);
		}
	}
	else
	{
		$errorMessage = $responce['data']['errorMessage'];
		if ($errorMessage == 'Multiple records found')
		{
			PlayIVR($respcode, $agi, $callerType, $phone_number,'',$sip);
		}
		else if ($errorMessage == 'No result found.')
		{
			PlayIVR($respcode, $agi, $callerType, $phone_number,'',$sip);
		}
		else if ($errorMessage == 'Booking ID not found. Forwarding to customer care.')
		{
			$respcode		 = '110';
			$phone_number	 = $responce['data']['Number'];
			PlayIVR($respcode, $agi, $callerType, $phone_number,'',$sip);
		}
		else
		{
			$agi->exec("AGI", "agi-VDAD_ALL_inbound.agi,CID-----LB-----gozo_existing-----gozo-3--------------------998-----1-----001------------------------------");
			UpdateLog('000000', $agi);
		}
	}
	// close curl resource to free up system resources
	curl_close($ch);
}

function UpdateLog($dest_number, $agi)
{
	global $link;
	$UNIQUEID = $agi->request['agi_uniqueid'];
	$agi->verbose("UpdateLog::UNIQUEID: $UNIQUEID");
	$usql = "UPDATE elision_recording_log SET dest_phone_number ='" . $dest_number . "' WHERE uniqueid='" . $UNIQUEID ."'";
	$agi->verbose($usql);
	mysqli_query($link, $usql);
}

function MakeCall($dest_number, $agi, $sip='gozo')
{
	UpdateLog($dest_number, $agi);
	$agi->verbose($dest_number);
	$result = $agi->exec("DIAL", "SIP/$sip/$dest_number,30,Ttor");
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
?>
