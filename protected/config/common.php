<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
$urlRules = require(
		dirname(__FILE__) . '/../extensions/starship/RestfullYii/config/routes.php'
		);
Yii::setPathOfAlias('booster', dirname(__FILE__) . DIRECTORY_SEPARATOR . '../extensions/yiibooster');
Yii::setPathOfAlias('Stub', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '../stub'));
Yii::setPathOfAlias('Beans', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '../beans'));
Yii::setPathOfAlias('RestfullYii', dirname(__FILE__) . DIRECTORY_SEPARATOR . '../extensions/starship/RestfullYii');
Yii::setPathOfAlias('components', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '../components'));

Yii::setPathOfAlias('UserAuth', dirname(__FILE__) . DIRECTORY_SEPARATOR . '../extensions/hoauth/models/UserOAuth.php');
return array(
	'id'		 => 'Gozocabs',
	// preloading 'log' component
	'preload'	 => array('log'),
	'timeZone'	 => 'Asia/Kolkata',
	'language'	 => 'en_in',
	// autoloading model and component classes
	'import'	 => array(
		'application.models.*',
		'application.components.*',
		'ext.BraintreeApi.*',
		'ext.hoauth.models.*',
		'ext.BraintreeApi.models.*',
		'ext.YiiMailer.YiiMailer',
		'application.modules.rights.*',
		'application.modules.rights.components.*',
		'ext.yii-selectize.YiiSelectize',
		'application.command.BaseCommand',
		'ext.MCrypt',
	),
	'modules'	 => array(
		'admin'		 => array(
			'defaultController' => 'index/index',
		),
		'report'	 => array(
			'defaultController' => 'index/index',
		),
		'vendor'	 => array(
			'defaultController' => 'index/index',
		),
		'API'		 => array(
			'defaultController' => 'index/index',
		),
		'CPAPI'		 => array(
			'defaultController' => 'index/index',
		),
		'conapp'	 => array(
			'defaultController' => 'index/index',
		),
		'dco'		 => array(
			'defaultController' => 'index/index',
		),
		'meterdown'	 => array(
			'defaultController' => 'index/index',
		),
		'driver'	 => array(
			'defaultController' => 'index/index',
		),
		'agent'		 => array(
			'defaultController' => 'index/index',
		),
		'cpapi'		 => array(
			'defaultController' => 'index/index',
		),
		'cpaa'		 => array(
			'defaultController' => 'index/index',
		),
		'rbac'		 => array(
			'class'			 => 'application.modules.rbacui.RbacuiModule',
			'userClass'		 => 'Admins',
			'userIdColumn'	 => 'adm_id',
			'userNameColumn' => 'adm_user',
			'rbacUiAdmin'	 => 'SuperAdmin',
			'rbacUiAssign'	 => 'Admin',
		),
		'rcsr'		 => array(
			'defaultController' => 'index/index',
		),
	),
	// application components
	'components' => array(
		'cache'			 => array(
			'class'			 => 'CFileCache',
			'gCProbability'	 => 0
		),
		'authManager'	 => array(
			'class'			 => 'CDbAuthManager',
			'connectionID'	 => 'db',
		),
		'apnsGcm'		 => array(
			'class'	 => 'ext.apns-gcm.YiiApnsGcm',
			// custom name for the component, by default we will use 'gcm' and 'apns'
			'gcm'	 => 'gcm',
		// 'apns' => 'apns',
		),
		'apnsGcmUser'	 => array(
			'class'	 => 'ext.apns-gcm.YiiApnsGcm',
			// custom name for the component, by default we will use 'gcm' and 'apns'
			'gcm'	 => 'gcmUser',
		// 'apns' => 'apnsUser',
		),
		'gcm'			 => array(
			'class'	 => 'ext.apns-gcm.YiiGcm',
			//'apiKey' => 'AIzaSyCx9KrRHI7YODk9oHRx1tnLo9lSuaKuY5c'
			'apiKey' => 'AIzaSyCqR35KdPZlwpZvKMzKItFMCgvUB4dPgkI'
		),
		'gcmUser'		 => array(
			'class'	 => 'ext.apns-gcm.YiiGcm',
			//'apiKey' => 'AIzaSyCx9KrRHI7YODk9oHRx1tnLo9lSuaKuY5c'
			'apiKey' => 'AIzaSyCqR35KdPZlwpZvKMzKItFMCgvUB4dPgkI'
		),
		'ePdf'			 => array(
			'class'	 => 'ext.yii-pdf.EYiiPdf',
			'params' => array(
				'mpdf'		 => array(
					'librarySourcePath'	 => 'application.vendors.mpdf60.*',
					'constants'			 => array(
						'_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
					),
					'class'				 => 'Mpdf\Mpdf', // the literal class filename to be loaded from the vendors folder
				),
				'HTML2PDF'	 => array(
					'librarySourcePath'	 => 'application.vendors.html2pdf.*',
					'class'				 => 'Spipu\Html2Pdf\Html2Pdf',
					//    'classFile'		 => 'html2pdf.class.php', // For adding to Yii::$classMap
					'defaultParams'		 => array(// More info: http://wiki.spipu.net/doku.php?id=html2pdf:en:v4:accueil
						'orientation'	 => 'P', // landscape or portrait orientation
						'format'		 => 'A4', // format A4, A5, ...
						'lang'			 => 'en', // language: fr, en, it ...
						'unicode'		 => true, // TRUE means clustering the input text IS unicode (default = true)
						'encoding'		 => 'UTF-8', // charset encoding; Default is UTF-8
						'margins'		 => array(5, 5, 5, 8), // margins by default, in order (left, top, right, bottom)
					)
				)
			),
		),
		'geocoder'		 => array(
			'class'		 => 'ext.EGeocoder.EGeocoder',
			// 'httpAdapter' => 'Socket',
			'providers'	 => array(
				array(
					'name'	 => 'GoogleMaps',
					// Please use your own api key
					'apiKey' => 'fe469eea906d1be01894ef2a7dd7a1d64fb9f412ab5ebdcxx2576c7af9ac04a014',
				),
			),
		),
		'paytm'			 => array(
			'class'		 => 'application.components.Paytm',
			'api_live'	 => false,
		),
		'mobikwik'		 => array(
			'class'		 => 'application.components.Mobikwik',
			'api_live'	 => false,
		),
		'ebs'			 => array(
			'class'		 => 'application.components.EbsPayment',
			'api_live'	 => false,
		),
		'zaakpay'		 => array(
			'class'		 => 'application.components.Zaakpay',
			'api_live'	 => false,
		),
		'freecharge'	 => array(
			'class'		 => 'application.components.Freecharge',
			'api_live'	 => false,
		),
		'lazypay'		 => array(
			'class'		 => 'application.components.Lazypay',
			'api_live'	 => false,
		),
		'epaylater'		 => array(
			'class'		 => 'application.components.EPayLater',
			'api_live'	 => false,
		),
		'payu'			 => array(
			'class'		 => 'application.components.Payumoney',
			'api_live'	 => false,
		),
		'icici'			 => array(
			'class'		 => 'application.components.ICICIIB',
			'api_live'	 => false,
		),
		'paynimo'		 => array(
			'class' => 'application.components.Paynimo',
		),
		'razorpay'		 => array(
			'class'		 => 'application.components.Razorpay',
			'api_live'	 => false,
		),
		'easebuzz'			 => array(
			'class'		 => 'application.components.EaseBuzz',
			'api_live'	 => false,
		),
		'shortHash'		 => array(
			'class'	 => 'ext.yii-short-hash.ShortHash',
			'length' => 5,
		),
		'bootstrap'		 => array(
			'class'			 => 'booster.components.Booster',
			'coreCss'		 => true,
			'jqueryCss'		 => false,
			'bootstrapCss'	 => false,
			'responsiveCss'	 => true,
			'yiiCss'		 => false,
			'enableCdn'		 => false,
			'packages'		 => array(
				'bootstrap.js'	 => array(
					'baseUrl'			 => '/assets/',
					//	'css' => array('fonts/glyphicons/css/glyphicons.min.css'),
					'js'				 => array('js/bootstrap.min.js',),
					'coreScriptPosition' => CClientScript::POS_HEAD
				),
				'bootbox'		 => array(
					'baseUrl'			 => '/assets/plugins/bootbox/',
					'js'				 => array('bootbox.min.js'),
					'coreScriptPosition' => CClientScript::POS_BEGIN
				),
				'select2'		 => array('baseUrl'	 => '/assets/plugins/form-select2/',
					'js'		 => array('select2.min.js'),
					'css'		 => array('select2.css'),
					'depends'	 => array('jquery'),)
			),
		),
		'urlManager'	 => array(
			'appendParams'		 => false,
			'useStrictParsing'	 => false,
			'showScriptName'	 => false,
			'urlFormat'			 => 'path',
			'rules'				 => include('urlRules.php'),
		),
		'errorHandler'	 => array(
// use 'site/error' action to display errors
			'errorAction' => 'site/error',
		),
		'log'			 => array(
			'class'	 => 'CLogRouter',
			'routes' => include('logRoutes.php'),
		),
	),
	// application-level parameters that can be accessed
// using Yii::app()->params['paramName']
	'params'	 => array(
		'php_bin'						 => '/opt/cpanel/ea-php74/root/usr/bin/php',
		'pushApiCall'					 => false,
		'versionCheck'					 => [
			'vendor'		 => '3.20.10707',
			'consumer'		 => '4.6.00705',
			'consumerios'	 => '1.4.00129',
			'agent'			 => '1.2.61128',
			'driver'		 => '7.23.20329',
			'ops'			 => '5.2.11027',
		],
		'checkVersion'					 => [
			'vendor'	 => ['3' => '3.20.10707'],
			'consumer'	 => ['3' => '4.6.00705', '4' => '1.4.00129'],
			'driver'	 => ['3' => '7.23.20329'],
			'ops'		 => ['3' => '5.2.11027'],
		],
		'partner'						 => [
			'balanceValidity' => [
				'validateHour'	 => 12,
				'cancelHour'	 => 4
			]
		],
		'taxes'							 => [
			'GST'		 => [
				'total'	 => 5,
				'igst'	 => 5,
				'cgst'	 => 2.5,
				'sgst'	 => 2.5,
				'city'	 => 30706,
			],
			'serviceTax' => 6,
		],
		'digitalagmtversion'			 => '190314',
		'digitalAgtagmtversion'			 => '150319',
		'autoAddressJSVer'				 => '1.29',
		'dboMaster'						 => '1',
		'dboStartDate'					 => '2023-11-01 00:00:00',
		'dboLastDate'					 => '2023-12-10 23:59:59',
		'marginTolerance'				 => [
			0	 => array('round0' => 15, 'round1' => 10, 'round2' => 0, 'round3' => -5),
			1	 => array('round0' => 15, 'round1' => 10, 'round2' => 0, 'round3' => -5),
			2	 => array('round0' => 20, 'round1' => 15, 'round2' => 10, 'round3' => 5),
			4	 => array('round0' => 28, 'round1' => 23, 'round2' => 18, 'round3' => 13),
			5	 => array('round0' => 28, 'round1' => 23, 'round2' => 18, 'round3' => 13),
			6	 => array('round0' => 15, 'round1' => 10, 'round2' => 0, 'round3' => -5),
		],
		'assignmentScore'				 => [
			'manual'	 => 0.84,
			'critical'	 => 0.90
		],
		'minPayPrecentage'				 => [
			0		 => array(
				0	 => array(0 => 25, 1 => 25, 2 => 25, 3 => 25, 4 => 25),
				9	 => array(0 => 50, 1 => 50, 2 => 50, 3 => 50, 4 => 50),
				10	 => array(0 => 50, 1 => 50, 2 => 50, 3 => 50, 4 => 50),
				11	 => array(0 => 50, 1 => 50, 2 => 50, 3 => 50, 4 => 50),
			),
			18190	 => array(
				0	 => array(0 => 30, 1 => 30, 2 => 35, 3 => 25, 4 => 40),
				9	 => array(0 => 50, 1 => 50, 2 => 50, 3 => 50, 4 => 50),
				10	 => array(0 => 50, 1 => 50, 2 => 50, 3 => 50, 4 => 50),
				11	 => array(0 => 50, 1 => 50, 2 => 50, 3 => 50, 4 => 50),
			),
		],
		'minGzNowPayPrecentage'			 => [0 => 50],
		'serviceTaxRate'				 => 6,
		'fbgCancellationInterval'		 => 60,
		'gst'							 => 5,
		'igst'							 => 5,
		'cgst'							 => 2.5,
		'sgst'							 => 2.5,
		'transactionCharges'			 => 2.5,
		'rateVendorAmount'				 => 11.5,
		'rateMultiVendorAmount'			 => 13,
		'rateRoundVendorAmount'			 => 12,
		'airportRateVendorAmount'		 => 20,
		'airportCityRadius'				 => 25,
		'rockBottomMargin'				 => 4,
		'defMarkupCab'					 => 11,
		'assuredPremiumMarkup'			 => 11,
		'gozoChannelPartnerId'			 => 1249,
		'redeemDriverBonus'				 => 100,
		'just199BaseAmount'				 => 199,
		'minWalletAmount'				 => 250,
		'defaultReturnEndTime'			 => '22:00:00',
		'defaultStartTime'				 => '00:00:00',
		'defaultEndTime'				 => '23:59:59',
		'defaultPackagePickupTime'		 => '09:00:00',
		'indiaCreditCardPaymentType'	 => 1, //[ 1=>ebs, 2=>'Zaakpay']
		'host'							 => 'www.gozocabs.com',
		'fullBaseURL'					 => 'https://www.gozocabs.com',
		'fullAPIBaseURL'				 => 'https://api.gozocabs.com',
		'driverEnglishYouTubeURL'		 => 'https://www.youtube.com/playlist?list=PLtO3n8NwlGMSl5c0NTck6az_B0q8PP5Sx&app=desktop',
		'driverHindiYouTubeURL'			 => 'https://www.youtube.com/playlist?list=PLtO3n8NwlGMQ3tUd4Dj_dpbdtgEz8yfUw&app=desktop',
		'ebs'							 => [
			'account_id' => '20442',
			'secret'	 => 'c9772076b532444f30fa2b8c44990d56',
			'mode'		 => 'TEST'
		],
		'mail'							 => [
			'info'				 => [
				'SMTPOptions'	 => array(
					'ssl' => array(
						'verify_peer'		 => FALSE,
						'verify_peer_name'	 => FALSE,
						'allow_self_signed'	 => TRUE
					)
				),
				'Mailer'		 => 'smtp',
				'Host'			 => 'notify.gozo.cab',
				'Port'			 => 587,
				'SMTPSecure'	 => 'tls',
				'SMTPAuth'		 => true,
				'From'			 => 'updates@notify.gozo.cab',
				'Username'		 => 'www-notify',
				'Password'		 => '3rIv0Jn7eWNO4Ar',
				'FromName'		 => 'Gozocabs Account Services',
			],
			'Vendor'			 => [
				'FromName' => 'Gozocabs Operator Services',
			],
			'noReplyMail'		 => [
				//	'SMTPDebug'	 => 2,
				'SMTPOptions'	 => array(
					'ssl' => array(
						'verify_peer'		 => FALSE,
						'verify_peer_name'	 => FALSE,
						'allow_self_signed'	 => TRUE
					)
				),
				'Mailer'		 => 'smtp',
				'Host'			 => 'notify.gozo.cab',
				'Port'			 => 587,
				'SMTPSecure'	 => 'tls',
				'SMTPAuth'		 => true,
				'From'			 => 'updates@notify.gozo.cab',
				'Username'		 => 'www-notify',
				'Password'		 => '3rIv0Jn7eWNO4Ar',
			],
			'noReplyMail-1'		 => [
				//		'SMTPDebug'	 => 2,
				'SMTPOptions'	 => array(
					'ssl' => array(
						'verify_peer'		 => FALSE,
						'verify_peer_name'	 => FALSE,
						'allow_self_signed'	 => TRUE
					)
				),
				'Mailer'		 => 'smtp',
				'Host'			 => 'notify.gozo.cab',
				'Port'			 => 587,
				"SMTPAutoTLS"	 => true,
				'SMTPSecure'	 => 'tls',
				'SMTPAuth'		 => true,
				'From'			 => 'updates@notify.gozo.cab',
				'Username'		 => 'www-notify',
				'Password'		 => '3rIv0Jn7eWNO4Ar'
			],
			'noReplyMail-2'		 => [
				//'SMTPDebug'	 => 2,
				'SMTPOptions'	 => array(
					'ssl' => array(
						'verify_peer'		 => FALSE,
						'verify_peer_name'	 => FALSE,
						'allow_self_signed'	 => TRUE
					)
				),
				'Mailer'		 => 'smtp',
				'Host'			 => 'notify.gozo.cab',
				'Port'			 => 587,
				"SMTPAutoTLS"	 => true,
				'SMTPSecure'	 => 'tls',
				'SMTPAuth'		 => true,
				'From'			 => 'updates@notify.gozo.cab',
				'Username'		 => 'www-notify',
				'Password'		 => '3rIv0Jn7eWNO4Ar',
			],
			'AccountServices'	 => [
				'FromName' => 'Gozocabs Account Services',
			],
			'AgentServices'		 => [
				'FromName' => 'Gozocabs Partner Support',
			],
			'ConsumerServices'	 => [
				'FromName' => 'Gozocabs Services',
			],
			'Meterdown'			 => [
				'FromName' => 'Account Services',
			],
			'Booking'			 => [
				'FromName' => 'Gozocabs Services',
			],
			'dailyMail'			 => [
				'FromName' => 'Gozo Updates',
			],
			'agreementMail'		 => [
				'FromName' => 'Gozocabs Agreement Services',
			],
		],
		'demoDomains'					 => [
			'gozocabs.com'	 => 'gozocabs.com',
			'gozocabs.in'	 => 'gozocabs.in',
			'gozo.cab'		 => 'gozo.cab',
		],
// this is used in contact page
		'sendAppNotification'			 => true,
		'sendSMS'						 => true,
		'sendMail'						 => true,
		'validateSpam'					 => true,
		'demoMail'						 => '',
		'demoNumber'					 => '',
		'demoFromMail'					 => '',
		'adminEmail'					 => 'leadership@gozocabs.in',
		'leadAboveEmail'				 => 'leads-and-above@gozocabs.in',
		'leadsonthefence'				 => 'leads-on-the-fence@gozocabs.in',
		'bookingEmail'					 => 'email-bookings@gozocabs.in',
		'cancellationEmail'				 => 'cancellation-alert@gozocabs.in',
		'adminUserEmail'				 => 'nupur@gozocabs.in',
		'dailyReportUserEmail'			 => 'sudiptaroy@gozocabs.in',
		'gozocaresEmail'				 => 'Gozocares-team@gozocabs.in',
		'gozoSOSEmail'					 => 'sos@gozocabs.in',
		'listPerPage'					 => 20,
		'driverCredit'					 => 50,
		'creditMaxUseType'				 => 3,
		'vendorGraceDays'				 => 7,
		'agentDefCommission'			 => 0,
		'agentDefCommissionValue'		 => 1,
		'vendorDriverSalesCommission'	 => 7,
		'nightStartTime'				 => '22:00:00',
		'nightEndTime'					 => '06:00:00',
		'dialerApiKey'					 => 'QuycL6lQ7jWdccCZcQ24',
		'maskNumbers'					 => false,
		'customerToDriver'				 => '03366778813/03371122003',
		'driverToCustomer'				 => '03366778814/03371122004',
		'customerToDriverforMMT'		 => '03371122003',
		'driverToCustomerforMMT'		 => '03371122004',
		'scqToCustomerforMMT'			 => '01245045105',
		'isrecordOopsApp'				 => 1,
		'creditGiftAmt'					 => 1000,
		'braintreeapi'					 => array(
			'class'			 => 'ext.BraintreeApi.BraintreeApi',
			'environment'	 => 'production', //'sandbox' or 'production'
			'merchant_id'	 => 'f2vxh7ntqng6pknb',
			'public_key'	 => 'f3xs87b2bdd5n6x2',
			'private_key'	 => 'dcddb9cd6aeade679c34d0c47f52beef',
			'clientside_key' => 'MIIBCgKCAQEA4zK63Tg1QjAaMbtyLOeqyACFARB7GjzYQ7foyGYucstmy76AVqbiR/7yG/CvTWVzGit+BtFV2X0VY4zHvT4MsM0g/E8vBS1/AO7xEzM++jisS2kxp/EfvLx4lz4FlAIeiD2OMBb3s9p58RrwT4H0XURC1RCQnfVyqpTcRmcIJvGAJlNebBQPtke/S2Ftfikyn3Q8W/mx32qaw1Ca/ZGcEm4dMejlBJYV6C7qwOFrrpEnwsZBim4iYdYFqaS+zSiNp02tu5yFCwIPe4a00E4f1YSsi9cRGxm5+ak4BHVS05S/Ue9J2Y+Toy2mxzwMqDGzKC0LTXdM6Z90U082ErpZEwIDAQAB',),
		'flightApi'						 => ['appId' => 'a815ba5a', 'appKey' => '1f5649c7eb1fdd225585bb3a3b0c99da'],
		'companyInfo'					 => ['rating' => "4.2", 'ratingCount' => "565"],
		'uberAgentId'					 => 2467,
		'dynamicSurge'					 => 1,
		'profitabilitySurge'			 => 0,
		'refundApprovalsRequired'		 => 1, /* 0=>Automatic refund by system, 1=>Automatic refund after approval by admin */
		'firebase'						 => array(
			'apiKey' => 'AAAADHwGjKY:APA91bGZdHlaMmP_zYej6qeesdcz3ivmEUujQjMZuVCGqk2043TCBEg5T-pe_mDQ0GYpxKQDQZqX2Fnmt-37YHsqMCb1cMfQ1ClxavibMI8bOc6NWDgqrQPvNH-Idfg5LYur6M2wrG9c'
		),
		//category for vendor penalty
		/* 'PenaltyReason'					 => [
		  '1'	 => 'Late arrive',
		  '2'	 => 'Not allocating CAB and Driver on time and auto-unassigned',
		  '3'	 => 'Arrive location discrepancy',
		  '4'	 => 'Booking not marked complete',
		  '5'	 => 'Cab verification image mismatch',
		  '6'	 => 'Vendor cancellation after 4 hrs of assignment',
		  '7'	 => 'Vendor cancellation after 4 hrs of assignment and  within 12 hrs of pickup',
		  '8'	 => 'Vendor cancellation after 4 hrs of assignment and within 4 hrs of pickup ',
		  '9' => 'OTP verified between >30 &< 120mins from the pickup time ',
		  '10' => 'OTP verified > 120 mins /2 hrs from the pickup time',
		  '11' => 'Cab delayed by 15-30/30-60/60-120 (minutes)',
		  '12' => 'Vendor Unassigned  < 4 hours of assignment and > 12 working hours of pickup',
		  '13' => 'Vendor Unassigned  > 4 hours of assignment and > 8 working hours of pickup',
		  '14' => 'Vendor Unassigned > 4 hours after assignment and < 8 working hours of pickup',
		  '15' => 'Vendor Unassigned > 4 hours after assignment and < 4 working hours of pickup',
		  '16' => 'Vendor Unassigned > 4 hours after assignment and < 2 working hours of pickup',
		  '17' => 'Vendor uses unregistered vehicle',
		  '18' => 'Vendor uses unregistered driver',
		  '19' => 'Vehicle information does not match',
		  '20' => 'Driver information does not match',
		  '21' => 'Customer complains no Show',
		  '22' => 'Late OTP verification',
		  '23' => 'Discrepancy between arrived and pickup location (>3 km and <= 6 km)',
		  '24' => 'Discrepancy between arrived and pickup location (> 6 km)',
		  '25' => 'Driver arrived Late (> 30 min and <= 120 min)',
		  '26' => 'Driver arrived Late (> 120 min)',
		  '27' => 'Late booking completed By app',
		  '28' => 'Trip not completed by app but booking marked completed by system or admin',

		  ], */
		'PenaltyReason'					 => [
			//'11' => 'Cab delayed by 15-30/30-60/60-120 (minutes) Rs.50/75/300',
			'12' => 'Vendor Unassigned  < 4 hours of assignment and > 12 working hours of pickup  No Penalty',
			'13' => 'Vendor Unassigned  > 4 hours of assignment and > 8 working hours of pickup Rs.500 or 25% of bkg amount (whichever is less)',
			'14' => 'Vendor Unassigned > 4 hours after assignment and < 8 working hours of pickup Rs.1000 or 50% of bkg amount (whichever is less)',
			'15' => 'Vendor Unassigned > 4 hours after assignment and < 4 working hours of pickup Rs.1500 or 75% of bkg amount (whichever is less)',
			'16' => 'Vendor Unassigned > 4 hours after assignment and < 2 working hours of pickup Rs.2000 or 100% of bkg amount (whichever is less)',
			//'23' => 'Discrepancy between arrived and pickup location (>3 km and <= 6 km) Rs.50',
			//'24' => 'Discrepancy between arrived and pickup location (> 6 km)  10% of vendor amount',
			//'27' => 'Late booking completed By app Rs.200',
			//'28' => 'Trip not completed by app but booking marked completed by system or admin Rs.200',
			'31' => 'Customer complains NO-Show Rs.2000 or 100% of bkg amount (whichever is less)',
			'35' => 'Cab verification image mismatch (Car not matching or Pictures not provided) Rs.350',
			//'37' => 'Vendor uses unregistered vehicle Rs.1000',
			//'38' => 'Vendor uses unregistered driver Rs.1000',
			'41' => 'Vehicle information does not match (Car verification rejected) Rs.350',
			'42' => 'Driver information does not match  (Complain by customer) Rs.350',
			'43' => 'Trip started but Trip not completed by app but booking marked completed by system or admin Rs.300 or 30% of vendor amount (whichever is greater)',
			'44' => 'Trip not started in vendor app. This means if he has not started, he has not completed also Rs.200 or 20% of  vendor amount (whichever is less)',
			'45' => 'On vendor direct accept booking and not assigning cab on time and getting auto un-assign by system (this is not applicable in case vendor is getting auto assigned by system) Rs.200',
			'46' => 'Cab delayed by 0-15 (minutes) No Penalty',
			'47' => 'Cab delayed by 16 minutes or more Rs.50 + Rs.2 per minute',
			'48' => 'Not marked completed Rs.200',
			'49' => 'Arrive location discrepancy Rs.200',
			'50' => 'Grace Period for System Assigned Booking is 20% of working hour or 4 working hours whichever is less. For Direct Accepted Booking 15 minutes. After Grace Period to 40% of assignment time to pickup elapsed - Rs.500 or 25% of vendor amount (whichever is less)',
			'51' => 'Between 40% - 60% of assignment to pickup elapsed- Rs.1000 or 50% of vendor amount (whichever is less)',
			'52' => 'Between 60% - 75% of assignment to pickup elapsed - Rs.1500 or 75% of vendor amount (whichever is less)',
			'53' => 'After 75% of assignment to pickup elapsed- Rs.2000 or 100% of vendor amount (whichever is less)',
			'54' => 'Cab & Driver details changed between 0-2 Hours of the pickup time. Penalty amount 300/-'
		],
		'PenaltyAmount'					 => [
			/*  '1'	 => '200',
			  '2'	 => '200',
			  '3'	 => '200',
			  '4'	 => '10% of BA',
			  '5'	 => '2000',
			  '6'	 => '100',
			  '7'	 => '500',
			  '8'	 => '1000',
			  '9'	 => '50',
			  '10' => '100', */
			'11' => '50/75/300',
			'12' => 'No Penalty',
			'13' => '500',
			'14' => '1000',
			'15' => '1500',
			'16' => '2000',
			'17' => '1000',
			'18' => '1000',
			'19' => '1000',
			'20' => '1000',
			'21' => '2000',
			'22' => '200',
			'23' => '50',
			'24' => '10% of vendor amount',
			'25' => '50',
			'26' => '100',
			'27' => '200',
			'28' => '200',
			'35' => '350',
			'41' => '350',
			'42' => '350',
			'54' => '300'
		],
		'dayRentalCities'				 => ['30893', '30366', '30611', '30758', '30474', '30595', '30254', '31022',
			'30389', '32117', '31050', '30582', '30706', '30528', '30866', '30883', '30921', '30873', '30407', '30435', '30711', '30737', '30750',
			'30726', '30746', '30428', '30819', '30826', '30556', '30804', '30443', '30836', '30874', '30360', '30741', '32014', '30501', '30523',
			'30784', '30766', '30372', '30395', '30604'],
		'tipsVal'						 => [
			'Partner level'				 => 'Top rated partners are automatically promoted to Gold status',
			'Partner rating'			 => 'Always ask every customer to give you a rating',
			'Trips completed in month'	 => '',
			'Cars in active use'		 => 'Use your own cars ONLY',
			'Stickiness percentage'		 => 'Use your own cars to improve stickiness',
			'Driver app use percentage'	 => 'Use driver app for all trips',
			'Penalty score'				 => 'Lower your penalty count, better it is for your rating. Best score is 0',
			'Dependability'				 => ''],
		'vendorMatrixkeys'				 => [
			'1'	 => 'Partner status',
			'2'	 => 'Partner level',
			'3'	 => 'Partner rating',
			'4'	 => 'Trips completed in month',
			'5'	 => 'Cars in active use',
			'6'	 => 'Stickiness percentage',
			'7'	 => 'Driver app use percentage',
			'8'	 => 'Penalty score',
			'9'	 => 'Dependability'],
		'notAllowedConProfAgents'		 => ['450', '18190'], 'partner'						 => [
			'balanceValidity' => [
				'validateHour'	 => 12,
				'cancelHour'	 => 4
			]
		],
		'covidFlag'						 => 1,
		'useUserWallet'					 => 1,
		'securityKey'					 => 'EastORWestGozoIsTheBest',
		'QrUrl'							 => 'https://c.gozo.cab/',
		'defaultClass'					 => 1,
	),
);
