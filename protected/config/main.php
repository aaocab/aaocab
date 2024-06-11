<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
Yii::setPathOfAlias('booster', dirname(__FILE__) . DIRECTORY_SEPARATOR . '../extensions/yiibooster');
Yii::setPathOfAlias('publicpath', PUBLIC_PATH);
return array(
	'basePath'			 => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'name'				 => 'aaocab',
	'defaultController'	 => 'index/index',
	'language'			 => 'en_in',
	'localeDataPath'	 => realpath(__DIR__ . '/../locale/data'),
	// preloading 'log' component
	'preload'			 => array('bootstrap', 'log', 'input'),
	// autoloading model and component classes
	'import'			 => array(
		'application.models.*',
		'booster.helpers.*',
		'application.components.*',
		'ext.NLSClientScript',
		'ext.YiiMailer.YiiMailer',
	),
	// application components
	'components'		 => array(
		'themeManager'	 => [
			'basePath'	 => APPLICATION_PATH . '/themes',
			'baseUrl'	 => '/themes'
		],
		'assetManager'	 => array(
			'baseUrl' => ASSETS_URL,
		),
		'input'			 => array(
			'class' => 'CmsInput',
		),
		'request'		 => array(
			'class'					 => 'HttpRequest',
			'enableCsrfValidation'	 => true,
			'noCsrfValidationRoutes' => array('api/*', 'bot/*', 'paytm/*', 'rbac/*', 'ebs/*', 'payu/*','easebuzz/*',
				'mobikwik/*', 'lazypay/*', 'epaylater/*', 'zaakpay/*', 'drivers/*', 'shuttle/*', 'booking/summaryadditionalinfo', 'booking/finalPay',
				'payment/*', 'freecharge/*', 'rgrpt', 'xyz/*', 'lookup/*', 'agent/*/REST.POST', 'track/whatsappNotificationHook'),
			'enableCookieValidation' => true,
			'csrfCookie'			 => array(
				'httpOnly'	 => true,
				'secure'	 => true,
			),
		),
		'mobileDetect'	 => array(
			'class' => 'ext.MobileDetect.MobileDetect'
		),
		"liveChat"		 => array
			(
			"class" => "ext.liveChat.liveChat"
		),
		"JWT"			 => array
			(
			"class"	 => "ext.JWT.JWT",
			"key"	 => "@Ki/vKEmd0(<s1&M5D2)TSqH7s_|yPZBK8|Weaf4mDl4d1.`jlQ0NQFVR1+B2G.",
		),
//        'apns' => array(
//            'class' => 'ext.apns-gcm.YiiApns',
//            'environment' => 'production',
//            'pemFile' => dirname(__FILE__) . '/apnssert/aps_user_production.pem',
//            'dryRun' => false, // setting true will just do nothing when sending push notification
//            // 'retryTimes' => 3,
//            'options' => array(
//                'sendRetryTimes' => 5
//            ),
//        ),
		'user'			 => array(
			'class'				 => 'application.components.ClientWebUser',
			'loginUrl'			 => array('users/signin'),
			// enable cookie-based authentication
			'allowAutoLogin'	 => true,
			'autoRenewCookie'	 => true
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
				'bootstrap.js'			 => array(
					'baseUrl'			 => ASSETS_URL,
					'js'				 => array('js/bootstrap.min.js'),
					'depends'			 => array('jquery'),
					'coreScriptPosition' => CClientScript::POS_HEAD
				),
				'bootbox'				 => array(
					'baseUrl'			 => ASSETS_URL . 'plugins/bootbox/',
					'js'				 => array('bootbox.min.js'),
					'depends'			 => array('jquery'),
					'coreScriptPosition' => CClientScript::POS_HEAD
				),
				'jqueryui'				 => array(
					'baseUrl'			 => ASSETS_URL,
					'js'				 => array('plugins/jquery-ui/jquery-ui-no-conflict.min.js'),
					'css'				 => array('js/jqueryui.css'),
					'coreScriptPosition' => CClientScript::POS_HEAD,
					'depends'			 => 'bootstrap-noconflict'
				),
				'bootstrap-noconflict'	 => array(
					'baseUrl'	 => APP_ASSETS,
					'js'		 => array('js/bootstrap-noconflict.js'),
					'depends'	 => array('bootstrap.js'),
				),
				'select2'				 => array('baseUrl'	 => ASSETS_URL . 'plugins/form-select2/',
					'js'		 => array('select2.min.js'),
					'css'		 => array('select2.css'),
					'depends'	 => array('jquery'),)
			),
		),
		'clientScript'	 => array(
			'class'					 => 'ext.NLSClientScript',
			//		'serverBaseUrl' => ASSETS_URL,
//'excludePattern' => '/\.tpl/i', //js regexp, files with matching paths won't be filtered is set to other than 'null'
//'includePattern' => '/res\/app-assets/', //js regexp, only files with matching paths will be filtered if set to other than 'null'
			'mergeJs'				 => true, //def:true
			'compressMergedJs'		 => true, //def:false
			'mergeCss'				 => true, //def:true
			'compressMergedCss'		 => true, //def:false
			'mergeCssExcludePattern' => '/(maps\.googleapis\.com)|(fontawesome)/', //won't merge js files with matching names
			'mergeJsExcludePattern'	 => '/^(http:\/\/|https:\/\/)(?!' . str_replace(".", "\.", $_SERVER["HTTP_HOST"]) . '|maps\.googleapis\.com)(?:.*\/)|(app-assets\/js\/scripts\/)|(fontawesome)/', //won't merge js files with matching names
			//	'mergeJsIncludePattern'	 => '/(https:\/\/localhost)/', //won't merge js files with matching names
			'mergeIfXhr'			 => false, //def:false, if true->attempts to merge the js files even if the request was xhr (if all other merging conditions are satisfied)
			'downloadCssResources'	 => true,
			'appVersion'			 =>  '2',
			'mergeAbove'			 => 1, //def:1, only "more than this value" files will be merged,
			'curlTimeOut'			 => 10, //def:10, see curl_setopt() doc
			'curlConnectionTimeOut'	 => 10, //def:10, see curl_setopt() doc
			'scriptMap'				 => [
				'jquery.min.js'						 => ASSETS_URL . 'js/main.min.js?v=1',
				'bootstrap.min.js'					 => ASSETS_URL . 'js/main.min.js?v=1',
				'bootbox.min.js'					 => ASSETS_URL . 'js/main.min.js?v=1',
				'bootstrap-noconflict.js'			 => ASSETS_URL . 'js/main.min.js?v=1',
				'notify.min.js'						 => ASSETS_URL . 'js/main.min.js?v=1',
				'jquery.cookie.js'					 => ASSETS_URL . 'js/main.min.js?v=1',
				'bootstrap-datepicker.min.js'		 => ASSETS_URL . 'js/main.min.js?v=1',
				'bootstrap-datepicker-noconflict.js' => ASSETS_URL . 'js/main.min.js?v=1',
				'jquery.yiiactiveform.js'			 => ASSETS_URL . 'js/main.min.js?v=1',
				'selectize.js'						 => ASSETS_URL . 'js/main.min.js?v=1',
				'selectize.min.js'					 => ASSETS_URL . 'js/main.min.js?v=1',
				'jquery.jcarousellite.1.9.3.js'		 => ASSETS_URL . 'js/main.min.js?v=1',
				'ddscrollbox.js'					 => ASSETS_URL . 'js/main.min.js?v=1',
			],
			'packages'				 => array(
				'jquery'				 => array(
					'baseUrl'			 => ASSETS_URL,
					'js'				 => array('js/jquery.min.js'),
					'coreScriptPosition' => CClientScript::POS_HEAD
				),
				'jqueryV3'				 => array(
					'baseUrl'			 => ASSETS_URL,
					'js'				 => array('js/jquery.min.js'),
					'coreScriptPosition' => CClientScript::POS_HEAD
				),
				'jqueryui'				 => array(
					'baseUrl'			 => ASSETS_URL,
					'js'				 => array('plugins/jquery-ui/jquery-ui-no-conflict.min.js'),
					'css'				 => array('js/jqueryui.css'),
					'coreScriptPosition' => CClientScript::POS_HEAD,
					'depends'			 => 'bootstrap-noconflict'
				),
				'bootstrap-noconflict'	 => array(
					'baseUrl'	 => APP_ASSETS,
					'js'		 => array('js/bootstrap-noconflict.js'),
					'depends'	 => array('bootstrap.js'),
				),
//				'web'		 => array(
//					'baseUrl'			 => ASSETS_URL,
//					'css'				 => array('css/bootstrap.min.css'),
//					'coreScriptPosition' => CClientScript::POS_BEGIN
//				),
//				'webEnd'	 => array(
//					'baseUrl'			 => ASSETS_URL,
//					'css'				 => array('css/hover.css?v=1', 'css/animate.css', 'css/component.css', 'css/newstyle.css?v=2'),
//					'coreScriptPosition' => CClientScript::POS_END
//				),
				'webVendor'				 => array(
					'baseUrl'			 => APP_ASSETS,
					'css'				 => array(
						'vendors/css/extensions/swiper.min.css',
					),
					'js'				 => array(
						'js/bootstrap.min.js', 'js/jquery_cookie.js',
						'js/lazyload.min.js',
						'js/bootbox.min.js',
//						'vendors/js/vendors.min.js',
						'vendors/js/blockui/blockui.min.js',
//						'vendors/js/ui/jquery.sticky.js',
						'vendors/js/extensions/toastr.min.js',
						'vendors/js/extensions/swiper.min.js'
					),
					'coreScriptPosition' => CClientScript::POS_HEAD,
					"depends"			 => ["webV3"]
				),
				'uiControls'			 => array(
					'baseUrl'			 => APP_ASSETS,
					'css'				 => array(
						'selectize/dist/css/selectize.css',
						'selectize/dist/css/selectize.bootstrap4.css',
					),
					'js'				 => array(
						'js/jquery-ui.min.js',
						'selectize/dist/js/standalone/selectize.min.js',
						'jquery-ui/jquery-ui-no-conflict.min.js',
						'js/jquery.timepicker.min.js'
					),
					'coreScriptPosition' => CClientScript::POS_HEAD,
					"depends"			 => ["webV3End"]
				),
				'fonts'					 => array(
					'baseUrl'			 => APP_ASSETS,
					'coreScriptPosition' => CClientScript::POS_HEAD
				),
				'webV3'					 => array(
					'baseUrl'			 => APP_ASSETS,
					'css'				 => array('css/bootstrap.css', 'css/bootstrap-extended.css'),
					'js'				 => array(
					),
					'coreScriptPosition' => CClientScript::POS_HEAD,
					"depends"			 => ["jqueryV3"]
				),
				'webV3End'				 => array(
					'baseUrl'			 => APP_ASSETS,
					'css'				 => array(
						'css/plugins/extensions/swiper.css',
						'vendors/css/extensions/toastr.css',
					),
					'js'				 => array('js/lozad.min.js'),
					'coreScriptPosition' => CClientScript::POS_HEAD,
					"depends"			 => ["webV3"]
				),
				'web'					 => array(
					'baseUrl'			 => ASSETS_URL,
					'css'				 => array('css/bootstrap.min.css'),
					'coreScriptPosition' => CClientScript::POS_HEAD
				),
				'webEnd'				 => array(
					'baseUrl'			 => ASSETS_URL,
					'css'				 => array('css/hover.css?v=1', 'css/animate.css', 'css/component.css'),
					'coreScriptPosition' => CClientScript::POS_END
				),
				'style'					 => array(
					'baseUrl'			 => ASSETS_URL,
					'css'				 => array('css/bootstrap.min.css', 'css/modern.min.css'),
					'js'				 => array('js/jquery.min.js', 'plugins/jquery-ui/jquery-ui-no-conflict.min.js', 'js/modern.min.js?v=1'),
					'coreScriptPosition' => CClientScript::POS_HEAD
				),
			),
		),
		// Handling Session
		'session'		 => array(
//'savePath'	 => dirname(__FILE__) . '/../runtime/sess/',
			'savePath'		 => '/runtime/sess/',
			'timeout'		 => 86400,
			'cookieParams'	 => array(
				'httponly'	 => true,
				'secure'	 => true,
			),
		),
		'errorHandler'	 => array(
// use 'site/error' action to display errors
			'errorAction' => 'site/error',
		),
	),
	// application-level parameters that can be accessed
// using Yii::app()->params['paramName']
	'params'			 => array(
		'script'								 => [
			'mobileB2C'	 => [
				'jquery.min.js'				 => ASSETS_URL . 'js/jquery.min.js',
				'bootstrap.min.js'			 => ASSETS_URL . 'js/jquery.min.js',
				'bootbox.min.js'			 => ASSETS_URL . 'js/jquery.min.js',
				'bootstrap-noconflict.js'	 => ASSETS_URL . 'js/jquery.min.js',
				'jquery.cookie.js'			 => ASSETS_URL . 'js/jquery_cookie.js',
				'jquery_cookie.js'			 => ASSETS_URL . 'js/jquery_cookie.js',
				'jquery.lazyload.min.js'	 => ASSETS_URL . 'js/jquery.lazyload.min.js',
				'custom.js'					 => ASSETS_URL . 'js/mobile/custom.js',
				'custom.min.js'				 => ASSETS_URL . 'js/mobile/custom.js',
				'maskFilter.js'				 => ASSETS_URL . 'js/maskFilter.js',
//				'jquery.yiiactiveform.js'			 => ASSETS_URL . 'js/mobile/main.min.js?v=1',
				'notify.min.js'				 => ASSETS_URL . 'js/mobile/custom.js',
//				'selectize.js'						 => ASSETS_URL . 'js/mobile/main.min.js?v=1',
//				'selectize.min.js'					 => ASSETS_URL . 'js/mobile/main.min.js?v=1',
//				'bootstrap-datepicker.min.js'		 => ASSETS_URL . 'js/mobile/main.min.js?v=1',
//				'bootstrap-datepicker-noconflict.js' => ASSETS_URL . 'js/mobile/main.min.js?v=1',
//				'jquery.jcarousellite.1.9.3.js'		 => ASSETS_URL . 'js/mobile/main.min.js?v=1',
//				'ddscrollbox.js'					 => ASSETS_URL . 'js/mobile/main.min.js?v=1',
//				'datepicker3.css'					 => ASSETS_URL . 'css/mobile/compressed.css',
//				'jquery.timepicker.min.css'			 => ASSETS_URL . 'css/mobile/compressed.css',
//				'selectize.css'						 => ASSETS_URL . 'css/mobile/compressed.css',
//				'selectize.bootstrap3.css'			 => ASSETS_URL . 'css/mobile/compressed.css',
//				'base.css'							 => ASSETS_URL . 'css/mobile/compressed.css',
//				'bootstrap.css'						 => ASSETS_URL . 'css/mobile/compressed.css',
			//			'framework.min.css'			 => ASSETS_URL . 'css/mobile/mobile.min.css',
//				'style.min.css?6.54'		 => ASSETS_URL . 'css/mobile/mobile.min.css',
//				'framework-store.css'				 => ASSETS_URL . 'css/mobile/compressed.css',
			],
			'adminV1'	 => [
				'jquery.min.js'						 => ASSETS_URL . 'js/main.min.js?v=1',
				'bootstrap.min.js'					 => ASSETS_URL . 'js/main.min.js?v=1',
				'bootbox.min.js'					 => ASSETS_URL . 'js/main.min.js?v=1',
				'bootstrap-noconflict.js'			 => ASSETS_URL . 'js/main.min.js?v=1',
				'notify.min.js'						 => ASSETS_URL . 'js/main.min.js?v=1',
				'jquery.cookie.js'					 => ASSETS_URL . 'js/main.min.js?v=1',
				'bootstrap-datepicker.min.js'		 => ASSETS_URL . 'js/main.min.js?v=1',
				'bootstrap-datepicker-noconflict.js' => ASSETS_URL . 'js/main.min.js?v=1',
				'jquery.yiiactiveform.js'			 => ASSETS_URL . 'js/main.min.js?v=1',
				'selectize.js'						 => ASSETS_URL . 'js/main.min.js?v=1',
				'selectize.min.js'					 => ASSETS_URL . 'js/main.min.js?v=1',
				'jquery.jcarousellite.1.9.3.js'		 => ASSETS_URL . 'js/main.min.js?v=1',
				'ddscrollbox.js'					 => ASSETS_URL . 'js/main.min.js?v=1',
			],
			'desktopV2'	 => [
				'jquery.min.js'					 => APP_ASSETS . 'js/jquery.min.js',
				'jquery.js'						 => APP_ASSETS . 'js/jquery.min.js',
				'jquery_cookie.js'				 => APP_ASSETS . 'js/jquery_cookie.js',
				'jquery.cookie.js'				 => APP_ASSETS . 'js/jquery_cookie.js',
				'jquery-ui-no-conflict.min.js'	 => APP_ASSETS . 'jquery-ui/jquery-ui-no-conflict.min.js',
				'jquery-ui.min.js'				 => APP_ASSETS . 'jquery-ui/jquery-ui-no-conflict.min.js',
				'selectize.js'					 => APP_ASSETS . 'selectize/dist/js/standalone/selectize.min.js',
				'selectize.min.js'				 => APP_ASSETS . 'selectize/dist/js/standalone/selectize.min.js',
				'bootstrap.min.js'				 => APP_ASSETS . 'js/bootstrap.min.js',
				'bootstrap.js'					 => APP_ASSETS . 'js/bootstrap.min.js'
				//'bootbox.min.js'				 => APP_ASSETS . 'js/bootbox.min.js',
				//'bootbox.all.min.js'			 => APP_ASSETS . 'js/bootbox.min.js'			
				],
			'desktopV3'	 => [
				'selectize.css'					 => APP_ASSETS . 'selectize/dist/css/selectize.css',
				'selectize.bootstrap3.css'		 => APP_ASSETS . 'selectize/dist/css/selectize.bootstrap4.css',
				'jquery.min.js'					 => APP_ASSETS . 'js/jquery.min.js',
				'jquery.js'						 => APP_ASSETS . 'js/jquery.min.js',
				'bootstrap.min.js'				 => APP_ASSETS . 'js/bootstrap.min.js',
				//		'bootstrap-noconflict.js'		 => APP_ASSETS . 'js/bootstrap.min.js',
				'bootstrap.js'					 => APP_ASSETS . 'js/bootstrap.min.js',
				'bootbox.min.js'				 => APP_ASSETS . 'js/bootbox.min.js',
				'bootbox.all.min.js'			 => APP_ASSETS . 'js/bootbox.min.js',
				//	'notify.min.js'					 => APP_ASSETS . 'js/desktopv3core.js?v=1',
				//	'lozad.min.js'					 => APP_ASSETS . 'js/desktopv3core.js?v=1',
				//	'bootstrap-noconflict.js'		 => APP_ASSETS . 'js/desktopv3core.js?v=1',
				'jquery_cookie.js'				 => APP_ASSETS . 'js/jquery_cookie.js',
				'jquery.cookie.js'				 => APP_ASSETS . 'js/jquery_cookie.js',
				//		'asidebar.jquery.js'			 => APP_ASSETS . 'js/asidebar.jquery.js',
				//		'blockui.min.js'				 => APP_ASSETS . 'js/desktopv3core.js?v=1',
				//		'maskFilter.js'					 => APP_ASSETS . 'js/desktopv3core.js?v=1',
				//		'lazyload.min.js'				 => APP_ASSETS . 'js/desktopv3core.js?v=1',
				//		'toastr.min.js'					 => APP_ASSETS . 'js/desktopv3core.js?v=1',
				//		'swiper.min.js'					 => APP_ASSETS . 'js/desktopv3core.js?v=1',
				'jquery-ui-no-conflict.min.js'	 => APP_ASSETS . 'jquery-ui/jquery-ui-no-conflict.min.js',
				'jquery-ui.min.js'				 => APP_ASSETS . 'jquery-ui/jquery-ui-no-conflict.min.js',
				'selectize.js'					 => APP_ASSETS . 'selectize/dist/js/standalone/selectize.min.js',
				'selectize.min.js'				 => APP_ASSETS . 'selectize/dist/js/standalone/selectize.min.js',
			//	'jquery.timepicker.min.js'		 => APP_ASSETS . 'js/jsuicontrol.js?v=1',
			//	'jquery.yiiactiveform.js'		 => APP_ASSETS . 'js/desktopv3core.js?v=1'
			]
		],
		'adminHTTPS'							 => true,
		'bypassIPCheck'							 => false,
		'enableTracking'						 => false,
		'customJsVersion'						 => 8.278,
		'sitecssVersion'						 => 6.133,
		'siteJSVersion'				             => 8.113,
		'imageVersion'							 => 2.19,
		'enablePayuBolt'						 => 1,
		'rptPass'								 => '0602',
		'agentappsessioncheck'					 => true,
		'consumerappsessioncheck'				 => true,
		'driverappsessioncheck'					 => true,
		'opsappsessioncheck'					 => true,
		'vendorappsessioncheck'					 => true,
		'meterdownappsessioncheck'				 => true,
		'countrycode'							 => '+91',
		'currency'								 => 'INR',
		'timeZone'								 => '+05:30',
		'zipRegex'								 => '(^\d{6}$)',
		'RestfullYii'							 => require( __DIR__ . '/rest.php'),
		// this is used in contact page
		'adminEmail'							 => 'leadership@aaocab.in',
		'perDayMinDistance'						 => 250,
		'listPerPage'							 => 20,
		'serviceTaxRate'						 => 6,
		'gst'									 => 5,
		'igst'									 => 5,
		'cgst'									 => 2.5,
		'sgst'									 => 2.5,
		'invitedAmount'							 => 250,
		'inviterAmount'							 => 250,
		'dollarToRupeeRate'						 => 60,
		'internationalNoLimitPerday'			 => 35, //35
		'sendSmsValidHr'						 => 6,
		'sendSmsValidNo'						 => 10,
		'skipBookingFailedPaymentLogDuration'	 => 600,
        'predictionWidSessiontoken'	             => true,
	),
	'modules'			 => array(
		#...
		'user' => array(
			# encrypting method (php hash function)
			'hash'					 => 'md5',
			# send activation email
			'sendActivationMail'	 => false,
			# allow access for non-activated users
			'loginNotActiv'			 => false,
			# activate user on registration (only sendActivationMail = false)
			'activeAfterRegister'	 => true,
			# automatically login from registration
			'autoLogin'				 => true,
			# registration path
			'registrationUrl'		 => array('/user/registration'),
			# recovery password path
			'recoveryUrl'			 => array('/user/recovery'),
			# login form path
			'loginUrl'				 => array('/user/login'),
			# page after login
			'returnUrl'				 => array('/user/profile'),
			# page after logout
			'returnLogoutUrl'		 => array('/user/login'),
		),
	#...
	),
);
