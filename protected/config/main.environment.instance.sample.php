<?php

return [
	'components' => [
		'db'	 => [
			'connectionString'	 => 'mysql:host=127.0.0.1;dbname=aaodb;port=3306',
			'initSQLs'				 => array(
				"SET SESSION group_concat_max_len = 20000",
				"SET time_zone = '+5:30'"
			),
			'emulatePrepare'		 => true,
			'username'				 => 'sdrs22590',
			'password'				 => '',
			'charset'				 => 'utf8',
			'tablePrefix'			 => 'imp_',
			'schemaCachingDuration'	 => 3600,
		],
		'db1'	 => [
			'connectionString'	 => 'mysql:host=127.0.0.1;dbname=aaodb;port=3306',
			'initSQLs'				 => array(
				"SET SESSION group_concat_max_len = 20000",
				"SET time_zone = '+5:30'"
			),
			'emulatePrepare'		 => true,
			'username'				 => 'sdrs22590',
			'password'				 => '',
			'charset'				 => 'utf8',
			'tablePrefix'			 => 'imp_',
			'class'					 => 'CDbConnection',
			'schemaCachingDuration'	 => 3600,
		],
		'db2'	 => [
			'connectionString'	 => 'mysql:host=127.0.0.1;dbname=aaodb;port=3306',
			'initSQLs'				 => array(
				"SET SESSION group_concat_max_len = 20000",
				"SET time_zone = '+5:30'"
			),
			'emulatePrepare'		 => true,
			'username'				 => 'sdrs22590',
			'password'				 => '',
			'charset'				 => 'utf8',
			'tablePrefix'			 => 'imp_',
			'class'					 => 'CDbConnection',
			'schemaCachingDuration'	 => 3600,
		],
		'db3'	 => [
			'connectionString'	 => 'mysql:host=127.0.0.1;dbname=aaodb;port=3306',
			'initSQLs'				 => array(
				"SET SESSION group_concat_max_len = 20000",
				"SET time_zone = '+5:30'"
			),
			'emulatePrepare'		 => true,
			'username'				 => 'sdrs22590',
			'password'				 => '',
			'charset'				 => 'utf8',
			'tablePrefix'			 => 'imp_',
			'class'					 => 'CDbConnection',
			'schemaCachingDuration'	 => 3600,
		],
		'adb'	 => [
			'connectionString'	 => 'mysql:host=127.0.0.1;dbname=aaodb;port=3306',
			'initSQLs'				 => array(
				"SET SESSION group_concat_max_len = 20000",
				"SET time_zone = '+5:30'"
			),
			'emulatePrepare'		 => true,
			'username'				 => 'sdrs22590',
			'password'				 => '',
			'charset'				 => 'utf8',
			'tablePrefix'			 => 'imp_',
			'class'					 => 'CDbConnection',
			'schemaCachingDuration'	 => 3600,
		],
	],
	'params'	 => [
	]
];
