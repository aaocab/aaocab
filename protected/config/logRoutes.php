<?php

return array(
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'error, warning',
		'categories' => 'system.*',
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'error',
		'logFile'	 => 'gozo.googleAPI.log',
		'categories' => 'gozo.googleAPI.*',
	],
	/** DEFAULT API START * */
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'warning, error',
		'logFile'	 => 'api.module.log',
		'categories' => ['api.module.*', 'error.api.module.*', 'warning.api.module.*'],
		'except'	 => ['api.module.agent.controller.gmt.*', 'warning.api.module.agent.controller.gmt.*', 'error.api.module.agent.controller.gmt.*',
			'api.module.vendor.controller.*', 'warning.api.module.vendor.controller.*', 'error.api.module.vendor.controller.*',
			'api.module.conapp.controller.*', 'warning.api.module.conapp.controller.*', 'error.api.module.conapp.controller.*',
			'api.module.driver.controller.*', 'warning.api.module.driver.controller.*', 'error.api.module.driver.controller.*',
			'api.module.admin.controller.*', 'warning.api.module.admin.controller.*', 'error.api.module.admin.controller.*'
		],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'trace, info, warning, error',
		'logFile'	 => 'api.module.trace.log',
		'categories' => ['trace.api.module.*'],
		'except'	 => ['trace.api.module.agent.controller.gmt.*', 'trace.api.module.vendor.controller.*',
			'trace.api.module.conapp.controller.*', 'trace.api.module.driver.controller.*', 'trace.api.module.admin.controller.*']
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'info, warning, error',
		'logFile'	 => 'api.module.info.log',
		'categories' => ['info.api.module.*'],
		'except'	 => ['info.api.module.agent.controller.gmt.*', 'info.api.module.vendor.controller.*',
			'info.api.module.conapp.controller.*', 'info.api.module.driver.controller.*', 'info.api.module.admin.controller.*']
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'profile, error',
		'logFile'	 => 'api.module.profile.log',
		'categories' => ['profile.api.module.*'],
		'except'	 => ['profile.api.module.agent.controller.gmt.*', 'profile.api.module.vendor.controller.*',
			'profile.api.module.conapp.controller.*', 'profile.api.module.driver.controller.*', 'profile.api.module.admin.controller.*']
	],
	/** DEFAULT API END * */
	/** API GMT START * */
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'warning, error',
		'logFile'	 => 'api.module.agent.gmt.log',
		'categories' => ['api.module.agent.controller.gmt.*'],
		'except'	 => ['api.module.agent.controller.gmt.search', 'warning.api.module.agent.controller.gmt.search',
			'error.api.module.agent.controller.gmt.search',
			'api.module.agent.controller.gmt.create', 'warning.api.module.agent.controller.gmt.create',
			'error.api.module.agent.controller.gmt.create',
			'api.module.agent.controller.gmt.confirm', 'warning.api.module.agent.controller.gmt.confirm',
			'error.api.module.agent.controller.gmt.confirm'
		]
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'trace, info, warning, error',
		'logFile'	 => 'api.module.agent.gmt.trace.log',
		'categories' => ['trace.api.module.agent.controller.gmt.*'],
		'except'	 => ['trace.api.module.agent.controller.gmt.search', 'trace.api.module.agent.controller.gmt.create', 'trace.api.module.agent.controller.gmt.confirm']
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'info, warning, error',
		'logFile'	 => 'api.module.agent.gmt.info.log',
		'categories' => ['info.api.module.agent.controller.gmt.*'],
		'except'	 => ['info.api.module.agent.controller.gmt.search', 'info.api.module.agent.controller.gmt.create', 'info.api.module.agent.controller.gmt.confirm']
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'profile, error',
		'logFile'	 => 'api.module.agent.gmt.profile.log',
		'categories' => ['profile.api.module.agent.controller.gmt.*'],
		'except'	 => ['profile.api.module.agent.controller.gmt.search', 'profile.api.module.agent.controller.gmt.create', 'profile.api.module.agent.controller.gmt.confirm']
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'warning, error',
		'logFile'	 => 'api.module.agent.gmt.search.log',
		'categories' => ['api.module.agent.controller.gmt.search', 'warning.api.module.agent.controller.gmt.search',
			'error.api.module.agent.controller.gmt.search'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'trace, info, warning, error',
		'logFile'	 => 'api.module.agent.gmt.search.trace.log',
		'categories' => ['trace.api.module.agent.controller.gmt.search'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'info, warning, error',
		'logFile'	 => 'api.module.agent.gmt.search.info.log',
		'categories' => ['info.api.module.agent.controller.gmt.search'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'profile, error',
		'logFile'	 => 'api.module.agent.gmt.search.profile.log',
		'categories' => ['profile.api.module.agent.controller.gmt.search'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'warning, error',
		'logFile'	 => 'api.module.agent.gmt.create.log',
		'categories' => ['api.module.agent.controller.gmt.create', 'warning.api.module.agent.controller.gmt.create',
			'error.api.module.agent.controller.gmt.create'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'trace, info, warning, error',
		'logFile'	 => 'api.module.agent.gmt.create.trace.log',
		'categories' => ['trace.api.module.agent.controller.gmt.create'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'info, warning, error',
		'logFile'	 => 'api.module.agent.gmt.hold.info.log',
		'categories' => ['info.api.module.agent.controller.gmt.create'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'profile, error',
		'logFile'	 => 'api.module.agent.gmt.hold.profile.log',
		'categories' => ['profile.api.module.agent.controller.gmt.create'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'warning, error',
		'logFile'	 => 'api.module.agent.gmt.confirm.log',
		'categories' => ['api.module.agent.controller.gmt.confirm', 'warning.api.module.agent.controller.gmt.confirm',
			'error.api.module.agent.controller.gmt.confirm'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'trace, info, warning, error',
		'logFile'	 => 'api.module.agent.gmt.confirm.trace.log',
		'categories' => ['trace.api.module.agent.controller.gmt.confirm'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'info, warning, error',
		'logFile'	 => 'api.module.agent.gmt.confirm.info.log',
		'categories' => ['info.api.module.agent.controller.gmt.confirm'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'profile, error',
		'logFile'	 => 'api.module.agent.gmt.confirm.profile.log',
		'categories' => ['profile.api.module.agent.controller.gmt.confirm'],
	],
	/** API GMT END * */
	/** ADMIN MODULE START * */
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'warning, error',
		'logFile'	 => 'admin.controller.log',
		'categories' => ['module.admin.controller.*', 'warning.module.admin.controller.*', 'error.module.admin.controller.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'trace, info, warning, error',
		'logFile'	 => 'admin.controller.trace.log',
		'categories' => ['trace.module.admin.controller.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'info, warning, error',
		'logFile'	 => 'admin.controller.info.log',
		'categories' => ['info.module.admin.controller.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'profile, error',
		'logFile'	 => 'admin.controller.profile.log',
		'categories' => ['profile.module.admin.controller.*'],
	],
	/** ADMIN MODULE END * */
	/** VENDOR API MODULE START * */
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'warning, error',
		'logFile'	 => 'api.vendor.log',
		'categories' => ['api.module.vendor.controller.*', 'warning.api.module.vendor.controller.*', 'error.api.module.vendor.controller.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'trace, info, warning, error',
		'logFile'	 => 'api.vendor.trace.log',
		'categories' => ['trace.api.module.vendor.controller.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'info, warning, error',
		'logFile'	 => 'api.vendor.info.log',
		'categories' => ['info.api.module.vendor.controller.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'profile, error',
		'logFile'	 => 'api.vendor.profile.log',
		'categories' => ['profile.api.module.vendor.controller.*'],
	],
	/** VENDOR API MODULE END * */
	/** DRIVER API MODULE START * */
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'warning, error',
		'logFile'	 => 'api.driver.log',
		'categories' => ['api.module.driver.controller.*', 'warning.api.module.driver.controller.*', 'error.api.module.driver.controller.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'trace, info, warning, error',
		'logFile'	 => 'api.driver.trace.log',
		'categories' => ['trace.api.module.driver.controller.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'info, warning, error',
		'logFile'	 => 'api.driver.info.log',
		'categories' => ['info.api.module.driver.controller.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'profile, error',
		'logFile'	 => 'api.driver.profile.log',
		'categories' => ['profile.api.module.driver.controller.*'],
	],
	/** DRIVER API MODULE END * */
	/** CONAPP API MODULE START * */
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'warning, error',
		'logFile'	 => 'api.conapp.log',
		'categories' => ['api.module.conapp.controller.*', 'warning.api.module.conapp.controller.*', 'error.api.module.conapp.controller.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'trace, info, warning, error',
		'logFile'	 => 'api.conapp.trace.log',
		'categories' => ['trace.api.module.conapp.controller.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'info, warning, error',
		'logFile'	 => 'api.conapp.info.log',
		'categories' => ['info.api.module.conapp.controller.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'profile, error',
		'logFile'	 => 'api.conapp.profile.log',
		'categories' => ['profile.api.module.conapp.controller.*'],
	],
	/** CONAPP API MODULE END * */
	/** ADMIN API MODULE START * */
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'warning, error',
		'logFile'	 => 'api.admin.log',
		'categories' => ['api.module.admin.controller.*', 'warning.api.module.admin.controller.*', 'error.api.module.admin.controller.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'trace, info, warning, error',
		'logFile'	 => 'api.admin.trace.log',
		'categories' => ['trace.api.module.admin.controller.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'info, warning, error',
		'logFile'	 => 'api.admin.info.log',
		'categories' => ['info.api.module.admin.controller.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'profile, error',
		'logFile'	 => 'api.admin.profile.log',
		'categories' => ['profile.api.module.admin.controller.*'],
	],
	/** ADMIN API MODULE END * */

/** DCO API MODULE START * */
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'warning, error',
		'logFile'	 => 'api.dco.log',
		'categories' => ['api.module.dco.controller.*', 'warning.api.module.dco.controller.*', 'error.api.module.dco.controller.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'trace, info, warning, error',
		'logFile'	 => 'api.dco.trace.log',
		'categories' => ['trace.api.module.dco.controller.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'info, warning, error',
		'logFile'	 => 'api.dco.info.log',
		'categories' => ['info.api.module.dco.controller.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'profile, error',
		'logFile'	 => 'api.dco.profile.log',
		'categories' => ['profile.api.module.dco.controller.*'],
	],
	/** ADMIN API MODULE END * */

	/** DEFAULT ALL MODULE START * */
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'warning, error',
		'logFile'	 => 'module.controller.log',
		'categories' => ['module.*', 'warning.module.*', 'error.module.*'],
		'except'	 => ['module.conapp.controller.*', 'warning.module.conapp.controller.*', 'error.module.conapp.controller.*',
			'module.driver.controller.*', 'warning.module.driver.controller.*', 'error.module.driver.controller.*',
			'module.vendor.controller.*', 'warning.module.vendor.controller.*', 'error.module.vendor.controller.*',
			'module.admin.controller.*', 'warning.admin.conapp.controller.*', 'error.admin.conapp.controller.*',
			'module.default.controller.site.*'
		]
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'trace, info, warning, error',
		'logFile'	 => 'module.controller.trace.log',
		'categories' => ['trace.module.*'],
		'except'	 => ['trace.module.conapp.controller.*', 'trace.module.driver.controller.*', 'trace.module.vendor.controller.*',
			'trace.module.admin.controller.*', 'trace.default.controller.site.*'
		],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'info, warning, error',
		'logFile'	 => 'module.controller.info.log',
		'categories' => ['info.module.*'],
		'except'	 => ['info.module.conapp.controller.*', 'info.module.driver.controller.*', 'info.module.vendor.controller.*',
			'info.module.admin.controller.*', 'info.module.default.controller.site.*'
		],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'profile',
		'logFile'	 => 'module.controller.profile.log',
		'categories' => ['profile.module.*'],
		'except'	 => ['profile.module.conapp.controller.*', 'profile.module.driver.controller.*', 'profile.module.vendor.controller.*',
			'profile.module.admin.controller.*', 'profile.module.default.controller.site.*'
		],
	],
	/** DEFAULT ALL MODULE END * */
	/** Quote MODELS START * */
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'warning, error',
		'logFile'	 => 'models.Quote.log',
		'categories' => ['models.Quote.*', 'warning.models.Quote.*', 'error.models.Quote.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'trace, info, warning, error',
		'logFile'	 => 'models.Quote.trace.log',
		'categories' => ['trace.models.Quote.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'info, warning, error',
		'logFile'	 => 'models.Quote.info.log',
		'categories' => ['info.models.Quote.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'profile, error',
		'logFile'	 => 'models.Quote.profile.log',
		'categories' => ['profile.models.Quote.*'],
	],
	/** Quote MODELS END * */
	/** ServiceCallQueue MODELS START * */
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'warning, error',
		'logFile'	 => 'models.ServiceCallQueue.log',
		'categories' => ['models.ServiceCallQueue.*', 'warning.models.ServiceCallQueue.*', 'error.models.ServiceCallQueue.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'trace, info, warning, error',
		'logFile'	 => 'models.ServiceCallQueue.trace.log',
		'categories' => ['trace.models.ServiceCallQueue.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'info, warning, error',
		'logFile'	 => 'models.ServiceCallQueue.info.log',
		'categories' => ['info.models.ServiceCallQueue.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'profile, error',
		'logFile'	 => 'models.ServiceCallQueue.profile.log',
		'categories' => ['profile.models.ServiceCallQueue.*'],
	],
	/** ServiceCallQueue MODELS END * */
	/** DEFAULT MODELS START * */
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'warning, error',
		'logFile'	 => 'models.log',
		'categories' => ['models.*', 'warning.models.*', 'error.models.*'],
		'except'	 => ['models.Quote.*', 'warning.models.Quote.*', 'error.models.Quote.*']
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'trace, info, warning, error',
		'logFile'	 => 'models.trace.log',
		'categories' => ['trace.models.*'],
		'except'	 => ['trace.models.Quote.*']
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'info, warning, error',
		'logFile'	 => 'models.info.log',
		'categories' => ['info.models.*'],
		'except'	 => ['info.models.Quote.*']
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'profile, error',
		'logFile'	 => 'models.profile.log',
		'categories' => ['profile.models.*'],
		'except'	 => ['profile.models.Quote.*']
	],
	/** DEFAULT MODELS END * */
	/** DEFAULT COMMAND START * */
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'warning, error',
		'logFile'	 => 'command.log',
		'categories' => ['command.*', 'warning.command.*', 'error.command.*'],
		'except'	 => ['command.booking.*', 'warning.command.booking.*', 'error.command.booking.*']
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'trace, info, warning, error',
		'logFile'	 => 'command.trace.log',
		'categories' => ['trace.command.*'],
		'except'	 => ["trace.command.booking.*"]
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'info, warning, error',
		'logFile'	 => 'command.info.log',
		'categories' => ['info.command.*'],
		'except'	 => ["info.command.booking.*"]
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'profile, error',
		'logFile'	 => 'command.profile.log',
		'categories' => ['profile.command.*'],
		'except'	 => ["profile.command.booking.*"]
	],
	/** DEFAULT COMMAND END * */
	/** BOOKING COMMAND START * */
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'warning, error',
		'logFile'	 => 'command.booking.log',
		'categories' => ['command.booking.*', 'warning.command.booking.*', 'error.command.booking.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'trace, info, warning, error',
		'logFile'	 => 'command.booking.trace.log',
		'categories' => ['trace.command.booking.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'info, warning, error',
		'logFile'	 => 'command.booking.info.log',
		'categories' => ['info.command.booking.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'profile, error',
		'logFile'	 => 'command.booking.profile.log',
		'categories' => ['profile.command.booking.*'],
	],
	/** BOOKING COMMAND END * */
	/** Site Controller START * */
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'error',
		'logFile'	 => 'site.controller.log',
		'categories' => ['module.default.controller.site.*', 'warning.module.default.controller.site.*', 'module.default.controller.site.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'trace, info, warning, error',
		'logFile'	 => 'site.controller.log',
		'categories' => ['trace.module.default.controller.site.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'info, warning, error',
		'logFile'	 => 'command.booking.info.log',
		'categories' => ['info.module.default.controller.site.*'],
	],
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'profile, error',
		'logFile'	 => 'site.controller.log',
		'categories' => ['profile.module.default.controller.site.*'],
	],
	/** Site Controller END * */
	/** Site Controller ERROR 400 START * */
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'warning',
		'logFile'	 => 'site.controller.warning.log',
		'categories' => ['module.default.controller.site.*'],
	],
		/** Site Controller ERROR 400 END * */
	[
		'class'		 => 'CFileLogRoute',
		'levels'	 => 'warning',
		'logFile'	 => 'config.security.log',
		'categories' => ['config.security.*'],
	],
);


