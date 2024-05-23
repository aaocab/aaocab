<?php

$cachePath = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR . 'cache');
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'id' => 'Gozo Staging',
    // application components
    'components' => array(
        'cache' => array(
            'class' => 'CFileCache',
            'cachePath' => $cachePath . DIRECTORY_SEPARATOR . 'sandbox',
        ),
        /* Local Connection String
         * */
        'db' => array(
            'connectionString' => 'mysql:host=127.0.0.1;dbname=gozo_stage1',
            'initSQLs' => array(
                "SET time_zone = '+5:30'"
            ),
            'emulatePrepare' => true,
            'username' => 'gozostage',
            'password' => 'g0z0$tage123',
            'charset' => 'utf8',
            'tablePrefix' => 'imp_'
        ),
    ),
    'params' => array(
        'sendSMS' => false,
        'sendEmail' => false,
        'sendNotifications' => true,
        'demoNumber' => '',
        'https' => false,
    ),
);
