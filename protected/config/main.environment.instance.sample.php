<?php

return [
	'components' => [
		'db'	 => [
			'connectionString'		 => 'mysql:host=10.130.45.237;port=3306;dbname=gozodb',
			'initSQLs'				 => array(
				"SET SESSION group_concat_max_len = 20000",
				"SET time_zone = '+5:30'"
			),
			'emulatePrepare'		 => true,
			'username'				 => 'gozodbuser',
			'password'				 => 'G0z0T!er321',
			'charset'				 => 'utf8',
			'tablePrefix'			 => 'imp_',
			'schemaCachingDuration'	 => 3600,
		],
		'db1'	 => [
			'connectionString'		 => 'mysql:host=10.130.65.78;port=3306;dbname=gozodb',
			'initSQLs'				 => array(
				"SET SESSION group_concat_max_len = 20000",
				"SET time_zone = '+5:30'"
			),
			'emulatePrepare'		 => true,
			'username'				 => 'gozodbuser',
			'password'				 => 'G0z0T!er321',
			'charset'				 => 'utf8',
			'tablePrefix'			 => 'imp_',
			'class'					 => 'CDbConnection',
			'schemaCachingDuration'	 => 3600,
		],
		'db2'	 => [
			'connectionString'		 => 'mysql:host=10.130.65.78;port=3306;dbname=gozodb',
			'initSQLs'				 => array(
				"SET SESSION group_concat_max_len = 20000",
				"SET time_zone = '+5:30'"
			),
			'emulatePrepare'		 => true,
			'username'				 => 'gozodbuser',
			'password'				 => 'G0z0T!er321',
			'charset'				 => 'utf8',
			'tablePrefix'			 => 'imp_',
			'class'					 => 'CDbConnection',
			'schemaCachingDuration'	 => 3600,
		],
		'db3'	 => [
			'connectionString'		 => 'mysql:host=10.130.65.78;port=3306;dbname=gozodb',
			'initSQLs'				 => array(
				"SET SESSION group_concat_max_len = 20000",
				"SET time_zone = '+5:30'"
			),
			'emulatePrepare'		 => true,
			'username'				 => 'gozodbuser',
			'password'				 => 'G0z0T!er321',
			'charset'				 => 'utf8',
			'tablePrefix'			 => 'imp_',
			'class'					 => 'CDbConnection',
			'schemaCachingDuration'	 => 3600,
		],
		'adb'	 => [
			'connectionString'		 => 'mysql:host=10.130.65.78;port=3306;dbname=gozo_archive',
			'initSQLs'				 => array(
				"SET SESSION group_concat_max_len = 20000",
				"SET time_zone = '+5:30'"
			),
			'emulatePrepare'		 => true,
			'username'				 => 'gozodbuser',
			'password'				 => 'G0z0T!er321',
			'charset'				 => 'utf8',
			'tablePrefix'			 => 'imp_',
			'class'					 => 'CDbConnection',
			'schemaCachingDuration'	 => 3600,
		],
	],
	'params'	 => [
	]
];
