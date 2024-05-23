<?php

list($usec, $sec) = explode(" ", microtime());
$time = ((float) $usec + (float) $sec);
define('TIME', $time);
error_reporting(E_ERROR);
require_once('environment.php');
require_once($yii);

\Sentry\init(['dsn' => 'https://f09805aef0fd481d8311048e9281e2e7@sentry1.gozo.cab/2', 
	'error_types' => E_ERROR | E_WARNING | E_PARSE,
	'environment' => APPLICATION_ENV]);
// change the following paths if necessary

$configCommon		 = include_once(APPLICATION_PATH . '/config/common.php');
$configServer		 = include_once(APPLICATION_PATH . '/config/main.' . APPLICATION_ENV . '.php');
$configMain			 = include_once(APPLICATION_PATH . '/config/command.php');
$configMain			 = CMap::mergeArray($configCommon, $configMain);
$config				 = CMap::mergeArray($configMain, $configServer);
$configInstancePath	 = APPLICATION_PATH . '/config/main.' . APPLICATION_ENV . '.instance.php';
if (file_exists($configInstancePath))
{
	$configInstance	 = include_once($configInstancePath);
	$config			 = CMap::mergeArray($config, $configInstance);
}
if (MAINTENANCE_FLAG == '0')
{
	Yii::createConsoleApplication($config)->run();
}
else
{

}
