<?php

define("JS_URL", "/js/");
define("CSS_URL", "https://css.gozocabs.com");
define("IMAGE_URL", "https://images.gozocabs.com");
define("ASSETS_URL", "/assets/");
define("APP_ASSETS", "/res/app-assets/");
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	// application components
	'runtimePath'	 => '/runtime/',
	'components'	 => array(
		'paytm'	 => array(
			'class'				 => 'application.components.Paytm',
			'api_live'			 => true,
			'merchant_key'		 => 'SWw7BIL!WfdnZ1Q2',
			'merchant_id'		 => 'GozoTe20851626521168',
			'website'			 => 'Gozoweb',
			'appwebsite'		 => 'Gozowap',
			'channel_app_id'	 => 'WAP',
			'industry_type_id'	 => 'Retail120',
		),
		'cache'	 => [
			'class'		 => 'system.caching.CFileCache',
			'cachePath'	 => '/runtime/cache/',
		]
	),
	'params'		 => array(
		'enableTracking'		 => true,
		'ebs'					 => [
			'mode' => 'LIVE'
		],
//		'googleApiKey'			 => 'AIzaSyBR2PY1_R8tavxIUwuYj7_kpMkqmcgTMYI',
//		'googleBrowserApiKey'	 => 'AIzaSyAUwr2jsBsWxcq0YIoI8UO3yGVWgPZv1Dk',
		'googleApiKey'			 => 'AIzaSyCqR35KdPZlwpZvKMzKItFMCgvUB4dPgkI',
		'googleBrowserApiKey'	 => 'AIzaSyAX-QHcLBUU5uuiD5QJ1HVrH8J4r8-ejZg',
		'PickupAlertEmail'		 => 'callcenter@gozocabs.in',
		'https'					 => true,
		'uploadPath'			 => '/var/lib/mysql-files',
		'domain'				 => '.gozocabs.com',
		'mobikwik'				 => array(
			'environment'	 => 'production', //'sandbox' or 'production'
			'api_live'		 => true,
			'merchant_id'	 => 'MBK30554',
			'secret_key'	 => '4gGLnHYvCa4Ah0LxrP6nVnFrU1aH'
		),
		'payu'					 => array(
			'class'					 => 'application.components.Payumoney',
			'api_live'				 => true,
			'merchant_authorization' => 'B/haUFcS31Cyh7Uv0U0Yo8x/X1KahrCsQjukInqCh3s=',
			'merchant_key'			 => 'tHzHiOJC',
			'merchant_id'			 => '5518829',
			'merchant_salt'			 => 'vZ4Ra83vxC',
		),
		'razorpay'				 => array(
			'class'		 => 'application.components.Razorpay',
			'api_live'	 => true,
			'key'		 => 'rzp_live_R0tCj4u9gGq66O',
			'secret'	 => 'u0nnqPA3SyJAgJMQyuLmQjQq',
		),
		'easebuzz'			 => array(
			'api_live'		 => true,
			'merchant_key'	 => 'A10Q2HUSHY',
			'merchant_salt'	 => 'GGTSP40HXZ',
		),
		'freecharge'			 => array(
			'environment'	 => 'production',
			'api_live'		 => true,
			'merchant_id'	 => 'XBFCyc9p6UVIH7',
			'merchantKey'	 => 'b4139a2c-0efb-4107-860d-16287a84f034',
		),
		'lazypay'				 => array(
			'api_live'	 => true,
			'accessKey'	 => 'PORXP45355PQTMTUJPV1',
			'secretKey'	 => '74f25bfdd9d0a62cb41825ac77c681231c31b5bd',
		),
		'epaylater'				 => array(
			'api_live'	 => true,
			'apikey'	 => 'secret_87c66516-f5e5-42e6-9563-0bc8d3c876c5-fc8b1d1c-86f8-45cb',
			'iv'		 => 'E85D5C404FC82759',
			'aes_key'	 => 'CC1939673D2F1F782E1B08F578E2F715',
			'm_code'	 => 'GOZOCABS',
		),
		'zaakpay'				 => array(
			'environment'	 => 'production', //'sandbox' or 'production'
			'api_live'		 => true,
			'merchant_id'	 => 'c829f359372b4bf68c6601c16923fc94',
			'secret_key'	 => '839f6fedb575450c96ffbaf42c12b44a'
		),
		//For Live
		'paynimo'				 => [
			'merchantCode'	 => 'L447481',
			'key'			 => '9367357513UIECVH',
			'iv'			 => '9045559468XAJTRH',
		],
		'icici'					 => array(
			'api_live'	 => true,
			'corpid'	 => 'GOZOTECH31082017',
			'userid'	 => 'ANKANPAU',
			'accnumber'	 => '105605003776',
			'apikey'	 => 'dd189aa360224f1aba5a10210bfc6e54',
//Common
			'aggrid'	 => 'CUST0286',
			'aggrname'	 => 'GOZO',
			'urn'		 => 'SR186122666',
		),
		'braintreeapi'			 => array(
			'environment'	 => 'production', //'sandbox' or 'production'
			'merchant_id'	 => '5jbmxck64vsfzt3h',
			'public_key'	 => '7yy4c3b4zvm6w4sd',
			'private_key'	 => '74bdfcd9c7595c09390539dce5de8da6',
			'clientside_key' => 'MIIBCgKCAQEAp2GOqMRwa0+dR5GmRUcaL1tp3jaT06fhb3TZXNbt0B1YDrxENcuuDoJeVtshguB0uBdJUFx9Hy8wYNJVLzhg4wCRkEIcAyzm/+5SAfzJjetBYShvl1UuDa4eXySU2S5eFbCKqJlJ0m/AUZVufM8VA96uXynQ5AYJNsJeCSYKyW0O/0Ca7IuSZRwDF3ZktyMfxDTeXuMqi2DaAR5v0TDlpxTi4jCC7Nnr7sbDobpYRH0VihDKpnwv5cdKYW38JI2Zt4IgUVz/wMWpuUgKMfCQkw6wQ9vP22XIP/Q+y4OQxj3nXE735JZ3yYhFaM5p7ABSJ45Cu1a/FIf6RmgmcNxbZwIDAQAB',),
		'google'				 => array(
			'apikey'		 => array(
				'0'	 => 'AIzaSyBR2PY1_R8tavxIUwuYj7_kpMkqmcgTMYI', //hale-aurora-289817
				'1'	 => 'AIzaSyCqR35KdPZlwpZvKMzKItFMCgvUB4dPgkI', //gozocabs-1159
				'2'	 => 'AIzaSyDIG9NzYdRMjlpT83yyvPiY_MszbkZjW4E', //crucial-bonsai-196011
				//'3'	 => 'AIzaSyAAUZ_f3QMu8ZUQXZaDIfeaVP7Saj0FKTc', //delta-tuner-265308
			),
			'browserapikey'	 => array(
				'0' => 'AIzaSyAUwr2jsBsWxcq0YIoI8UO3yGVWgPZv1Dk', //hale-aurora-289817
				'1' => 'AIzaSyDSnSYwrDs3LysZFAnoC6gNtdZbUYnqOW0', //gozocabs-1159
				//'2' => 'AIzaSyBh1GtbR2qmNDTyX9pI8lNRDt_EXfez98k', //crucial-bonsai-196011
				//'3' => 'AIzaSyAX-QHcLBUU5uuiD5QJ1HVrH8J4r8-ejZg', //delta-tuner-265308
			),
		),
	),
);
