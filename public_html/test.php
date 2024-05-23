<?php
exit();
echo "<pre>";
echo "<br>SERVER------------<br>";
print_r($_SERVER);

if (function_exists('getallheaders'))
{
	$headers = getallheaders();
	#Logger::error('getAuthorizationHeader - getallheaders');
	#Logger::error(json_encode($headers));
	
	echo "<br>getallheaders------------<br>";
	print_r($headers);
}
else if (function_exists('apache_request_headers'))
{
	$headers = apache_request_headers();
	#Logger::error('getAuthorizationHeader - apache_request_headers');
	#Logger::error(json_encode($headers));
	
	echo "<br>apache_request_headers------------<br>";
	print_r($headers);
}

if (isset($headers['Authorization']))
{
	$auth_array = explode(" ", $headers['Authorization']);
	if (isset($auth_array[1]))
	{
		$header = base64_decode($auth_array[1]);
	}
	
	echo "<br>Authorization------------<br>";
	print_r($header);
	#Logger::error('getAuthorizationHeader - Authorization');
	#Logger::error(json_encode($auth_array));
	#Logger::error($header);
}