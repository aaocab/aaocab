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
		parent::init();
		\Sentry\init(['dsn' => 'https://360974a3033b4f4cae3f248fc3117c61@sentry.gozo.cab/2', 'environment' => APPLICATION_ENV]);
	}

	/**
	 * Sends log messages to syslog.
	 * @param array $logs list of log messages.
	 */
	protected function processLogs($logs)
	{
		static $syslogLevels = array(
			CLogger::LEVEL_TRACE	 => \Sentry\Breadcrumb::LEVEL_DEBUG,
			CLogger::LEVEL_WARNING	 => \Sentry\Breadcrumb::LEVEL_WARNING,
			CLogger::LEVEL_ERROR	 => \Sentry\Breadcrumb::LEVEL_ERROR,
			CLogger::LEVEL_INFO		 => \Sentry\Breadcrumb::LEVEL_INFO,
			CLogger::LEVEL_PROFILE	 => \Sentry\Breadcrumb::LEVEL_INFO,
		);

		foreach ($logs as $log)
		{
			\Sentry\addBreadcrumb(new \Sentry\Breadcrumb($syslogLevels[$log[1]], \Sentry\Breadcrumb::TYPE_DEFAULT, $log[2], $log[0]));
		}
	}

	public static function captureException(Exception $e)
	{
		\Sentry\SentrySdk::getCurrentHub()->captureEvent($payload);
	}

}
