<?php

list($usec, $sec) = explode(" ", microtime());
$time					 = ((float) $usec + (float) $sec);
define('TIME', $time);
$GLOBALS['time1']		 = $time;
$GLOBALS['mailTestMode'] = false;
error_reporting(E_ERROR);
require_once('environment.php');
require_once($yii);
//\Sentry\init(['dsn' => 'https://f09805aef0fd481d8311048e9281e2e7@sentry1.gozo.cab/2', 'environment' => APPLICATION_ENV, 'max_breadcrumbs' => 30]);
list($usec, $sec) = explode(" ", microtime());
$time1					 = ((float) $usec + (float) $sec);
$time					 = $time1 - TIME;
$GLOBALS['time2']		 = $time;
// change the following paths if necessary//

$configCommon		 = include_once(APPLICATION_PATH . '/config/common.php');
$configServer		 = include_once(APPLICATION_PATH . '/config/main.' . APPLICATION_ENV . '.php');
$configMain			 = include_once(APPLICATION_PATH . '/config/main.php');
$configMain			 = CMap::mergeArray($configCommon, $configMain);
$config				 = CMap::mergeArray($configMain, $configServer);
$configInstancePath	 = APPLICATION_PATH . '/config/main.' . APPLICATION_ENV . '.instance.php';
if (file_exists($configInstancePath))
{
	$configInstance	 = include_once($configInstancePath);
	$config			 = CMap::mergeArray($config, $configInstance);
}

//Change the UrlFormat for urlManager to get if a get request is given instead of a path format one.
$app				 = Yii::createWebApplication($config);
list($usec, $sec) = explode(" ", microtime());
$time1				 = ((float) $usec + (float) $sec);
$time				 = $time1 - TIME;
$GLOBALS['time3']	 = $time;

if (isset($_GET['r']))
{
	Yii::app()->setComponents(array('urlManager' => array(
			'urlFormat'	 => 'get',
			'rules'		 => array()
		),));
}

// Allowed Hosts
if (APPLICATION_ENV == 'production')
{
	$host		 = $_SERVER["HTTP_HOST"];
	$arrHost	 = explode(".", $host);
	$count		 = count($arrHost);
	$hostName	 = $arrHost[($count - 2)] . '.' . $arrHost[($count - 1)];

	$arrAllowedDomains = ['aaocab.com', 'gozo.cab', 'aaocab.in'];
	if (!in_array($hostName, $arrAllowedDomains))
	{
		$protocol	 = "HTTP/1.0";
		if ("HTTP/1.1" == $_SERVER["SERVER_PROTOCOL"])
			$protocol	 = "HTTP/1.1";

		header("$protocol 404 Page not found", true, 404);
		die();
	}
}

if (MAINTENANCE_FLAG == '0')
{
	$app->run();
}
else
{
	$protocol	 = "HTTP/1.0";
	if ("HTTP/1.1" == $_SERVER["SERVER_PROTOCOL"])
		$protocol	 = "HTTP/1.1";

	header("$protocol 503 Service Unavailable", true, 503);
	echo "<img src='/images/under_maintenance.jpg'/>";
}
