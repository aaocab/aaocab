<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Gozocabs',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'ext.YiiMailer.YiiMailer',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'MindScale',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'autoRenewCookie' => true
        ),
        // Handling Session
        'session' => array(
            'sessionName' => 'YiiSession',
            'class' => 'CDbHttpSession',
            'autoCreateSessionTable' => true,
            'connectionID' => 'db',
            'sessionTableName' => 'YiiSession',
            'useTransparentSessionID' => ($_POST['PHPSESSID']) ? true : false,
            'autoStart' => 'false',
            'cookieMode' => 'only',
            'timeout' => 3000,
        ),
        'facebook' => array(
            'class' => 'Facebook'
        ),
        // uncomment the following to enable URLs in path-format
        /*
          'urlManager'=>array(
          'urlFormat'=>'path',
          'rules'=>array(
          '<controller:\w+>/<id:\d+>'=>'<controller>/view',
          '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
          '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
          ),
          ),
         */
        /* 'db'=>array(
          'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
          ), */
        // uncomment the following to use a MySQL database

        /* Server Connection String
         * */
        'db' => array(
            'connectionString' => 'mysql:host=edzone:120;dbname=longo_cab',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'sdrs22590',
            'charset' => 'utf8',
            'tablePrefix' => 'lc_'
        ),
        /* Local Connection String
         *
          'db'=>array(
          'connectionString' => 'mysql:host=localhost;dbname=impind',
          'emulatePrepare' => true,
          'username' => 'root',
          'password' => '',
          'charset' => 'utf8',
          'tablePrefix' => 'imp_'
          ), */
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        /* 'log'=>array(
          'class'=>'CLogRouter',
          'routes'=>array(
          array(
          'class'=>'CWebLogRoute',  'levels'=>'trace, info, error, warning',
          ),
          array(
          'class'=>'CFileLogRoute',  'levels'=>'trace, info, error, warning',
          ),
          )
          ), */
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages

            /* array(
              'class'=>'CWebLogRoute',
              ), */
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'info@impind.com',
        'listPerPage' => 5
    ),
);
