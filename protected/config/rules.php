<?php

return
		['api/vendor/<controller:\w+>'									 => ['vendor/<controller>/REST.GET', 'verb' => 'GET'],
			'api/vendor/<controller:\w+>/<id:\w*>'							 => ['vendor/<controller>/REST.GET', 'verb' => 'GET'],
			'api/vendor/<controller:\w+>/<id:\w*>/<param1:\w*>'				 => ['vendor/<controller>/REST.GET', 'verb' => 'GET'],
			'api/vendor/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>' => ['vendor/<controller>/REST.GET', 'verb' => 'GET'],
			['vendor/<controller>/REST.POST', 'pattern' => 'api/vendor/<controller:\w+>', 'verb' => 'POST'],
			['vendor/<controller>/REST.POST', 'pattern' => 'api/vendor/<controller:\w+>/<id:\w+>', 'verb' => 'POST'],
			['vendor/<controller>/REST.POST', 'pattern' => 'api/vendor/<controller:\w+>/<id:\w*>/<param1:\w*>', 'verb' => 'POST'],
			['vendor/<controller>/REST.POST', 'pattern' => 'api/vendor/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>', 'verb' => 'POST'],
		] +
		['api/meterdown/<controller:\w+>'									 => ['meterdown/<controller>/REST.GET', 'verb' => 'GET'],
			'api/meterdown/<controller:\w+>/<id:\w*>'							 => ['meterdown/<controller>/REST.GET', 'verb' => 'GET'],
			'api/meterdown/<controller:\w+>/<id:\w*>/<param1:\w*>'				 => ['meterdown/<controller>/REST.GET', 'verb' => 'GET'],
			'api/meterdown/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>'	 => ['meterdown/<controller>/REST.GET', 'verb' => 'GET'],
			['meterdown/<controller>/REST.POST', 'pattern' => 'api/meterdown/<controller:\w+>', 'verb' => 'POST'],
			['meterdown/<controller>/REST.POST', 'pattern' => 'api/meterdown/<controller:\w+>/<id:\w+>', 'verb' => 'POST'],
			['meterdown/<controller>/REST.POST', 'pattern' => 'api/meterdown/<controller:\w+>/<id:\w*>/<param1:\w*>', 'verb' => 'POST'],
			['meterdown/<controller>/REST.POST', 'pattern' => 'api/meterdown/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>', 'verb' => 'POST'],
		] +
		['api/driver/<controller:\w+>'									 => ['driver/<controller>/REST.GET', 'verb' => 'GET'],
			'api/driver/<controller:\w+>/<id:\w*>'							 => ['driver/<controller>/REST.GET', 'verb' => 'GET'],
			'api/driver/<controller:\w+>/<id:\w*>/<param1:\w*>'				 => ['driver/<controller>/REST.GET', 'verb' => 'GET'],
			'api/driver/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>' => ['driver/<controller>/REST.GET', 'verb' => 'GET'],
			['driver/<controller>/REST.POST', 'pattern' => 'api/driver/<controller:\w+>', 'verb' => 'POST'],
			['driver/<controller>/REST.POST', 'pattern' => 'api/driver/<controller:\w+>/<id:\w+>', 'verb' => 'POST'],
			['driver/<controller>/REST.POST', 'pattern' => 'api/driver/<controller:\w+>/<id:\w*>/<param1:\w*>', 'verb' => 'POST'],
			['driver/<controller>/REST.POST', 'pattern' => 'api/driver/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>', 'verb' => 'POST'],
		] +
		['api/agent/<controller:\w+>'									 => ['agent/<controller>/REST.GET', 'verb' => 'GET'],
			'api/agent/<controller:\w+>/<id:\w*>'							 => ['agent/<controller>/REST.GET', 'verb' => 'GET'],
			'api/agent/<controller:\w+>/<id:\w*>/<param1:\w*>'				 => ['agent/<controller>/REST.GET', 'verb' => 'GET'],
			'api/agent/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>'	 => ['agent/<controller>/REST.GET', 'verb' => 'GET'],
			['agent/<controller>/REST.POST', 'pattern' => 'api/agent/<controller:\w+>', 'verb' => 'POST'],
			['agent/<controller>/REST.POST', 'pattern' => 'api/agent/<controller:\w+>/<id:\w+>', 'verb' => 'POST'],
			['agent/<controller>/REST.POST', 'pattern' => 'api/agent/<controller:\w+>/<id:\w*>/<param1:\w*>', 'verb' => 'POST'],
			['agent/<controller>/REST.POST', 'pattern' => 'api/agent/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>', 'verb' => 'POST'],
		] +
		[
			'api/<controller:\w+>'										 => ['<controller>/REST.GET', 'verb' => 'GET'],
			'api/<controller:\w+>/<id:\w*>'								 => ['<controller>/REST.GET', 'verb' => 'GET'],
			'api/<controller:\w+>/<id:\w*>/<param1:\w*>'				 => ['<controller>/REST.GET', 'verb' => 'GET'],
			'api/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>'	 => ['<controller>/REST.GET', 'verb' => 'GET'],
			['<controller>/REST.PUT', 'pattern' => 'api/<controller:\w+>/<id:\w*>', 'verb' => 'PUT'],
			['<controller>/REST.PUT', 'pattern' => 'api/<controller:\w+>/<id:\w*>/<param1:\w*>', 'verb' => 'PUT'],
			['<controller>/REST.PUT', 'pattern' => 'api/<controller:\w*>/<id:\w*>/<param1:\w*>/<param2:\w*>', 'verb' => 'PUT'],
			['<controller>/REST.DELETE', 'pattern' => 'api/<controller:\w+>/<id:\w*>', 'verb' => 'DELETE'],
			['<controller>/REST.DELETE', 'pattern' => 'api/<controller:\w+>/<id:\w*>/<param1:\w*>', 'verb' => 'DELETE'],
			['<controller>/REST.DELETE', 'pattern' => 'api/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>', 'verb' => 'DELETE'],
			['<controller>/REST.POST', 'pattern' => 'api/<controller:\w+>', 'verb' => 'POST'],
			['<controller>/REST.POST', 'pattern' => 'api/<controller:\w+>/<id:\w+>', 'verb' => 'POST'],
			['<controller>/REST.POST', 'pattern' => 'api/<controller:\w+>/<id:\w*>/<param1:\w*>', 'verb' => 'POST'],
			['<controller>/REST.POST', 'pattern' => 'api/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>', 'verb' => 'POST'],
			['<controller>/REST.OPTIONS', 'pattern' => 'api/<controller:\w+>', 'verb' => 'OPTIONS'],
			['<controller>/REST.OPTIONS', 'pattern' => 'api/<controller:\w+>/<id:\w+>', 'verb' => 'OPTIONS'],
			['<controller>/REST.OPTIONS', 'pattern' => 'api/<controller:\w+>/<id:\w*>/<param1:\w*>', 'verb' => 'OPTIONS'],
			['<controller>/REST.OPTIONS', 'pattern' => 'api/<controller:\w+>/<id:\w*>/<param1:\w*>/<param2:\w*>', 'verb' => 'OPTIONS'],
			'<controller:\w+>/<id:\d+>'									 => '<controller>/view',
			'<controller:\w+>/<action:\w+>/<id:\d+>'					 => '<controller>/<action>',
			'<controller:\w+>/<action:\w+>'								 => '<controller>/<action>',
			'index.php?r=<controller:\w+>/<action:\w+>'					 => '<controller>/<action>',
		]
;
