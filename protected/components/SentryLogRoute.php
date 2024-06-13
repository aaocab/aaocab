<?php

/**
 * CSysLogRoute class file.
 *
 * @author miramir <gmiramir@gmail.com>
 * @author resurtm <resurtm@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2014 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CSysLogRoute dumps log messages to syslog.
 *
 * @author miramir <gmiramir@gmail.com>
 * @author resurtm <resurtm@gmail.com>
 * @package system.logging
 * @since 1.1.16
 */
class SentryLogRoute extends CLogRoute
{

	/**
	 * @var string syslog identity name.
	 */
	public $identity;
	public $logFile;
	public $eventArray = [];

	/**
	 * Initializes the route.
	 * This method is invoked after the route is created by the route manager.
	 */
	public function init()
	{
		
	}

	/**
	 * Sends log messages to syslog.
	 * @param array $logs list of log messages.
	 */
	protected function processLogs($logs)
	{
		
	}

	public static function captureException(Exception $e)
	{
		
	}

}
