<?php

list($usec, $sec) = explode(" ", microtime());
$time					 = ((float) $usec + (float) $sec);
define('TIME', $time);
$GLOBALS['time1']		 = $time;
$GLOBALS['mailTestMode'] = false;
error_reporting(E_ERROR);
require_once('environment.php');
require_once($yii);
$configCommon			 = include_once(APPLICATION_PATH . '/config/common.php');
$configServer			 = include_once(APPLICATION_PATH . '/config/main.' . APPLICATION_ENV . '.php');
$configMain				 = include_once(APPLICATION_PATH . '/config/main.php');
$configMain				 = CMap::mergeArray($configCommon, $configMain);
$config					 = CMap::mergeArray($configMain, $configServer);
$configInstancePath		 = APPLICATION_PATH . '/config/main.' . APPLICATION_ENV . '.instance.php';
if (file_exists($configInstancePath))
{
	$configInstance	 = include_once($configInstancePath);
	$config			 = CMap::mergeArray($config, $configInstance);
}
if (MAINTENANCE_FLAG == '0')
{
	Yii::createWebApplication($config);
}
