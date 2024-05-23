<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define('APPLICATION_ENV', 'development2');
define('MAINTENANCE_FLAG', '0');

defined('SERVER_ID') or define('SERVER_ID', 1);

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true);

// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

define('PUBLIC_PATH', dirname(__FILE__));
define('ROOT_PATH', realpath(PUBLIC_PATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR));
define("APPLICATION_PATH", realpath(ROOT_PATH .DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR));
define("VENDOR_PATH", realpath(ROOT_PATH . '/../vendors/vendor/'));

$yii = realpath(VENDOR_PATH . DIRECTORY_SEPARATOR . 'autoload.php');
