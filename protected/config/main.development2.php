<?php

define("JS_URL", "/js/");
define("CSS_URL", "/css/");
define("IMAGE_URL", "/images/");
define("ASSETS_URL", "/assets/");
define("APP_ASSETS", "/res/app-assets/");
$cachePath				 = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR . 'cache');
$GLOBALS['mailTestMode'] = false;
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	// preloading 'log' component
	//'defaultController' => 'index/index',
	'modules'	 => array(
		// uncomment the following to enable the Gii tool
		'gii' => array(
			'class'			 => 'system.gii.GiiModule',
			'password'		 => 'MindScale',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'		 => array('127.0.0.1', '::1'),
			'generatorPaths' => array('bootstrap.gii'),
		),
	),
	// application components
	'components' => array(
		'assetManager'	 => array(
			'baseUrl' => "/assets/",
		),
		'request'		 => array(
			'csrfCookie' => array(
				'httpOnly' => false,
				'secure' => false,
			),
		),
		'cache'			 => array(
			'class'		 => 'CFileCache',
			'cachePath'	 => $cachePath . DIRECTORY_SEPARATOR . 'sandbox1',
		),
		/* Local Connection String
		 * */
		'db'			 => array(
			'connectionString'	 => 'mysql:host=127.0.0.1;dbname=aaodb;port=3306',
			'emulatePrepare'	 => true,
			'username'			 => 'root',
			'password'			 => '',
			'charset'			 => 'utf8',
			'tablePrefix'		 => 'imp_',
		//        'schemaCachingDuration'=>3600,
		),
		'db1'			 => array(
			'connectionString'	 => 'mysql:host=127.0.0.1;dbname=aaodb;port=3306',
			'emulatePrepare'	 => true,
			'username'			 => 'root',
			'password'			 => '',
			'charset'			 => 'utf8',
			'tablePrefix'		 => 'imp_',
			'class'				 => 'CDbConnection'
		//        'schemaCachingDuration'=>3600,
		),
		'db2'			 => array(
			'connectionString'	 => 'mysql:host=127.0.0.1;dbname=aaodb;port=3306',
			'emulatePrepare'	 => true,
			'username'			 => 'root',
			'password'			 => '',
			'charset'			 => 'utf8',
			'tablePrefix'		 => 'imp_',
			'class'				 => 'CDbConnection'
		//        'schemaCachingDuration'=>3600,
		),
		'db3'			 => array(
			'connectionString'	 => 'mysql:host=127.0.0.1;dbname=aaodb;port=3306',
			'emulatePrepare'	 => true,
			'username'			 => 'root',
			'password'			 => '',
			'charset'			 => 'utf8',
			'tablePrefix'		 => 'imp_',
			'class'				 => 'CDbConnection'
		//        'schemaCachingDuration'=>3600,
		),
		'adb'			 => array(
			'connectionString'	 => 'mysql:host=127.0.0.1;dbname=gozo_archive;port=3306',
			'emulatePrepare'	 => true,
			'username'			 => 'root',
			'password'			 => '',
			'charset'			 => 'utf8',
			'tablePrefix'		 => 'imp_',
			'class'				 => 'CDbConnection'
		//        'schemaCachingDuration'=>3600,
		),
		'paytm'			 => array(
			'class'				 => 'application.components.Paytm',
			'api_live'			 => false,
			'merchant_key'		 => '4mOES@V5DgQWH9aA',
			'merchant_id'		 => 'GozoTe10117415031983',
			'website'			 => 'GozoTechweb',
			'appwebsite'		 => 'GozoTechwap',
			'industry_type_id'	 => 'Retail',
		),
		'log'			 => array(
			'class'	 => 'CLogRouter',
			'routes' => array(
				array(
					'class'		 => 'CFileLogRoute',
					'levels'	 => 'error, warning',
					'categories' => 'system.*',
				),
//				array(
//					'class' => 'CEmailLogRoute',
//					'levels' => 'error, warning',
//					'emails' => 'abhishek@epitech.in',
//				),
			),
		),
'clientScript'	 => array(
			'mergeJs'				 => false, //def:true
		),
		// Handling Session
		'session'		 => array(
			'savePath'	 => dirname(__FILE__) . '/../runtime/sess/',
			'timeout'	 => 86400,
			'cookieParams' => array(
				'httponly' => false,
				'secure' => false,
			),
		),
	),
	'params'	 => array(
		'fullAPIBaseURL'            => $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST'],
		'enableTracking'		 => false,
		'adminHTTPS'			 => false,
		'bypassIPCheck'			 => true,
		'apiKey'				 => 'AIzaSyCt7d6umxqoIi1WscTcyesxKHEh7owIoSk',
		'sendSMS'				 => false,
		'demoNumber'			 => '9051799911',
		'sendMail'				 => false,
		'demoMail'				 => 'puja.developer@gmail.com',
		'PickupAlertEmail'		 => 'sudiptaroy@aaocab.in',
		'listPerPage'			 => 10,
		//'googleApiKey' =>  'AIzaSyCqR35KdPZlwpZvKMzKItFMCgvUB4dPgkI',
		'googleBrowserApiKey'	 => 'AIzaSyAAUZ_f3QMu8ZUQXZaDIfeaVP7Saj0FKTc',
		'googleApiKey'			 => 'AIzaSyAAUZ_f3QMu8ZUQXZaDIfeaVP7Saj0FKTc',
		// 'googleBrowserApiKey' => 'AIzaSyDSnSYwrDs3LysZFAnoC6gNtdZbUYnqOW0',
		'domain'				 => '.localhost',
		'braintreeapi'			 => array(
			'environment'	 => 'sandbox', //'sandbox' or 'production'
			'merchant_id'	 => 'wxyqt53gzsndxvhd',
			'public_key'	 => 'h8mwsrcy5tyrr5vw',
			'private_key'	 => 'b8bd662cc687589359b6e4c08f8065fa',
			'clientside_key' => 'MIIBCgKCAQEAs8UUzjBwhlDhB4knJzRF4l+nnyR713kWPZIJ4dl4uAIIuw93tzjYAKGIBGNV3TtHnr2vfe3fPV48mKNRP5dbA1vwZqJOo/f3hRNCdR4dJ+kI8/le78XxJXtooiy8NgQ5O9IE6cnzhZ2RpE2+Dd22lIICJ0jXMfDtLN9vWZpPZZoD8G69VXsvVatftEWUbUaOCKqtkVOnOp8K83eeutZvOkD5DeMZ3k6IAa+3/16PTbTJu68nJYkMgb3MptIC0pEE2geLuKaRa4cv/OxJBCsxBndnXYz57/qKXDBpN42wyv6YZdnVpEbiuREf3+39ltWEpZPdIqFRhuCH6fq3t1nrHwIDAQAB',
		),
		'mobikwik'				 => array(
			'api_live'		 => false,
			'merchant_id'	 => 'MBK9002',
			'secret_key'	 => 'ju6tygh7u7tdg554k098ujd5468o',
		),
		'freecharge'			 => array(
			'api_live'		 => false,
			'merchantKey'	 => '1e6a7127-46ce-4149-a375-9aaeb3e33d9b',
			'merchant_id'	 => 'XBFCyc9p6UVIH7',
		),
		'lazypay'				 => array(
			'api_live'	 => false,
			'accessKey'	 => 'Y9HKLPWMIZJ3F90VMY7Y',
			'secretKey'	 => 'cda6fc854cfdc12ae0369c68f6fae24f52f67038',
		),
		'icici'					 => array(
//production
//			'api_live'	 => true,
//			'corpid'	 => 'GOZOTECH31082017',
//			'userid'	 => 'ANKANPAU',
//			'accnumber'	 => '105605003776',
//			'apikey'	 => 'dd189aa360224f1aba5a10210bfc6e54',
//UAT
			'api_live'	 => false,
			'corpid'	 => 'PRACHICIB1',
			'userid'	 => 'USER3',
			'accnumber'	 => '000451000301',
			'apikey'	 => '9af966d076634176a1d7e412722c0e18',
//Common
			'aggrid'	 => 'CUST0286',
			'aggrname'	 => 'GOZO',
			'urn'		 => 'SR186122666',
		),
		'epaylater'				 => array(
			'api_live'	 => false,
			'apikey'	 => 'secret_d6278430-6606-4432-ab6d-8924b165c8ed',
			'iv'		 => 'E85D5C404FC82759',
			'aes_key'	 => 'CC1939673D2F1F782E1B08F578E2F715',
			'm_code'	 => 'aaocab',
		),
		'payu'					 => array(
			'class'					 => 'application.components.Payumoney',
			'api_live'				 => false,
			//## for Bolt
			'merchant_authorization' => 'B/haUFcS31Cyh7Uv0U0Yo8x/X1KahrCsQjukInqCh3s=',
			'merchant_id'			 => '4937863',
			'merchant_key'			 => 'tHzHiOJC',
			'merchant_salt'			 => 'vZ4Ra83vxC',
		/* for sandbox */
//			'merchant_authorization' => 'mJfzUYBnZjmwlXM0ICL+WQFQILLTuGLKDtLcuxr1go4=',
//            'merchant_key' => 'HBwUI58I',
//            'merchant_id' => '4937863',
//            'merchant_salt' => '6AJd8fddI0',
		/* for production */
//			'merchant_key' => 'tHzHiOJC',
//			'merchant_id' => '5518829',
//			'merchant_salt' => 'vZ4Ra83vxC',
		//		'appwebsite' => 'GozoTechwap',
		//		'industry_type_id' => 'Retail',
		),
		'zaakpay'				 => array(
			'api_live'		 => false,
			'merchant_id'	 => 'b19e8f103bce406cbd3476431b6b7973',
			'secret_key'	 => '0678056d96914a8583fb518caf42828a'
//            'merchant_id' => 'c829f359372b4bf68c6601c16923fc94',
//            'secret_key' => '839f6fedb575450c96ffbaf42c12b44a'
		),
		'razorpay'				 => array(
			'class'		 => 'application.components.Razorpay',
			'api_live'	 => false,
			'key'		 => 'rzp_test_pzPn0uSGXRyU0F',
			'secret'	 => 'v1dfxZhDwkRzkDRA3i3MptcG',
		),
		'easebuzz'			 => array(
			'api_live'		 => false,
			'merchant_key'	 => '2PBP7IABZ2',
			'merchant_salt'	 => 'DAH88E3UWQ',
		),
		
		'uploadPath'			 => realpath(PUBLIC_PATH) . DIRECTORY_SEPARATOR . 'Exported',
		'google'				 => array(
			'apikey'		 => array(
				//'0' => 'AIzaSyAAUZ_f3QMu8ZUQXZaDIfeaVP7Saj0FKTc', //delta-tuner-265308
				//'0' => 'AIzaSyDghPDCwW9R5cnl_Rb4Ys5JXUA4k3XP3sk', //my-test-project
				'0' => 'AIzaSyBR2PY1_R8tavxIUwuYj7_kpMkqmcgTMYI',//new api key
			//'0' => 'AIzaSyDuXfacqrcxMriDPWCKrWjJucUM-KdfGUk', //my-test-project, gozotech1.ddns.net
			),
			'browserapikey'	 => array(
				//'0' => 'AIzaSyAAUZ_f3QMu8ZUQXZaDIfeaVP7Saj0FKTc', //delta-tuner-265308
				//'0' => 'AIzaSyDghPDCwW9R5cnl_Rb4Ys5JXUA4k3XP3sk', //my-test-project
				'0' => 'AIzaSyBR2PY1_R8tavxIUwuYj7_kpMkqmcgTMYI',//new api key
			//'0' => 'AIzaSyDuXfacqrcxMriDPWCKrWjJucUM-KdfGUk', //my-test-project, gozotech1.ddns.net
			),
		),
		'mapMyIndia'			 => array(
			'apikey'		 => array(
				'0' => '54fbfc51-e88e-4f0d-94d9-2d0195b74818', //my-test-project
			),
			'browserapikey'	 => array(
				'0' => '54fbfc51-e88e-4f0d-94d9-2d0195b74818', //my-test-project
			),
		),
		'firebase'				 => array(
			'apiKey' => 'AAAADHwGjKY:APA91bGZdHlaMmP_zYej6qeesdcz3ivmEUujQjMZuVCGqk2043TCBEg5T-pe_mDQ0GYpxKQDQZqX2Fnmt-37YHsqMCb1cMfQ1ClxavibMI8bOc6NWDgqrQPvNH-Idfg5LYur6M2wrG9c'
//			'apiKey' => 'AAAAZqv1r9w:APA91bHuP6dKvc2UFkXqqdA8aI8c6iVvh0x0r-tEv-G0ieIK-5kBRYV20KsBeNTt-fLGyV3nHpu-kkH1IXx8_1JklCsgE5CjzwqRJdyEYTMWg-thluFhJcSg_MjGZerQwzqFdAZqfEnc'
		),
	),
);

