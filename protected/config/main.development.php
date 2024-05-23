<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    // preloading 'log' component
    'modules' => array(
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'MindScale',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
            'generatorPaths' => array('bootstrap.gii'),
        ),
    ),
    // application components
    'components' => array(
		'cache' => array(
			'class' => 'CWinCache',
		),
        /* Local Connection String
         * */
        'db' => array(
            'initSQLs' => array(
                "SET time_zone = '+5:30'"
            ),
            'connectionString' => 'mysql:host=127.0.0.1;dbname=gozo_60801',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'sdrs22590',
            'charset' => 'utf8',
            'tablePrefix' => 'lc_',
   //     	'schemaCacheID' => 'cache',
    //    	'schemaCachingDuration' => 3600
        ),
    ),
    'params' => array(
        'sendSMS' => false,
		'sendAppNotification' => false,
        'demoNumber' => '',
		'demoMail' => 'deepesh@gozocabs.com',
        'PickupAlertEmail' => 'abhishek@epitech.in',
        'https' => false,
    ),
);
