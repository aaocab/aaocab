<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'defaultController' => 'search/index',
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
		/* Local Connection String
		 * */
		'db' => array(
			'connectionString' => 'mysql:host=localhost;dbname=imp',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'sdrs22590',
			'charset' => 'utf8',
			'tablePrefix' => 'imp_'
		),
	),
	'params' => array(
		'braintreeapi' => array(
			'environment' => 'sandbox', //'sandbox' or 'production'
		)
	),
);
