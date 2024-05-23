<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Logger
 *
 * @author akhet
 */
class Logger
{

	private static $_prefix		 = "";
	private static $_category	 = "application";
	public static $_profileCache = [];
	private static $_categories	 = [];
	private static $_levels		 = [];

	public static function getCategory()
	{
		return self::$_category;
	}

	public static function setCategory($param)
	{
		$value = Config::get("Logger." . $param);
		if ($value)
		{
			self::setDefaultLevel($value);
		}
		else
		{
//			self::setDefaultLevel("profile");
			self::clearDefaultLevel();
		}
		$key = self::getPrefix() . $param;
		$ctr = 0;
		if (isset(self::$_categories[$key]))
		{
			$ctr = self::$_categories[$key];
		}
		self::$_categories[$key] = $ctr + 1;
		self::$_levels[$param]	 = self::$_prefix;
	}

	public static function setDefaultLevel($level)
	{
		self::$_prefix = $level;
	}

	public static function getPrefix()
	{
		$prefix = self::$_prefix;
		if (self::$_prefix != "")
		{
			$prefix .= ".";
		}
		return $prefix;
	}

	public static function clearDefaultLevel()
	{
		self::$_prefix = "";
	}

	public static function create($desc, $logLevel = CLogger::LEVEL_TRACE)
	{
		$time		 = Filter::getExecutionTime();
		$memoryUsage = round(Yii::getLogger()->memoryUsage / 1024, 2);
		$categories	 = self::$_categories;
		if (empty($categories))
		{
			$categories[] = "application";
		}
		foreach ($categories as $category => $ctr)
		{
			if ($ctr <= 0)
			{
				continue;
			}
			Yii::log("[T: {$time}] [M: {$memoryUsage}] $desc", $logLevel, $category);
		}
	}

	public static function addSentryBreadcrumb($desc, $category = null, $level = CLogger::LEVEL_INFO, $type = \Sentry\Breadcrumb::TYPE_DEFAULT, $metadata = [])
	{
		if ($category == null)
		{
			$category = self::getCategory();
		}
		$sentryLevel = Sentry\Breadcrumb::LEVEL_INFO;
		switch ($level)
		{
			case CLogger::LEVEL_PROFILE:
				$sentryLevel = Sentry\Breadcrumb::LEVEL_INFO;
				break;
			case CLogger::LEVEL_TRACE:
				$sentryLevel = Sentry\Breadcrumb::LEVEL_DEBUG;
				break;
			case CLogger::LEVEL_INFO:
				$sentryLevel = Sentry\Breadcrumb::LEVEL_INFO;
				break;
			case CLogger::LEVEL_WARNING:
				$sentryLevel = Sentry\Breadcrumb::LEVEL_WARNING;
				break;
			case CLogger::LEVEL_ERROR:
				$type		 = \Sentry\Breadcrumb::TYPE_ERROR;
				$sentryLevel = Sentry\Breadcrumb::LEVEL_ERROR;
				break;
			default:
				$sentryLevel = Sentry\Breadcrumb::LEVEL_INFO;
				break;
		}
		\Sentry\addBreadcrumb(new \Sentry\Breadcrumb($sentryLevel, $type, $category, $desc, $metadata));
	}

	public static function trace($desc)
	{
		$desc = $desc . "\n\t" . self::getLastTrace();
		Logger::addSentryBreadcrumb($desc, self::getTraceCategory(), CLogger::LEVEL_TRACE);
		self::create($desc, CLogger::LEVEL_TRACE);
	}

	public static function info($desc)
	{
		$desc = $desc . "\n\t" . self::getLastTrace();
		Logger::addSentryBreadcrumb($desc, self::getTraceCategory(), CLogger::LEVEL_INFO);
		self::create($desc, CLogger::LEVEL_INFO);
		Logger::writeToConsole("INFO:: " . $desc);
	}

	public static function profile($desc)
	{
		self::create($desc, CLogger::LEVEL_PROFILE);
	}

	public static function warning($e, $capture = false)
	{
		$traces = null;
		switch (true)
		{
			case ($e instanceof Exception):
				$desc	 = self::getExceptionString($e);
				$traces	 = $e->getTrace();
				break;
			case (is_string($e)):
				$desc	 = $e;
				$e		 = null;
				break;
			default:
				return;
		}

		Logger::addSentryBreadcrumb($desc, self::getTraceCategory(), CLogger::LEVEL_WARNING, \Sentry\Breadcrumb::TYPE_DEFAULT, self::filterTrace($traces));
		if ($capture)
		{
			\Sentry\captureMessage($desc, new Sentry\Severity(Sentry\Severity::WARNING));
		}
		$desc	 .= self::getRequestDetails();
		$desc	 .= self::getBackTrace(3);
		self::create($desc, CLogger::LEVEL_WARNING);
	}

	public static function error($e, $capture = false)
	{
		$traces = null;
		switch (true)
		{
			case ($e instanceof Exception):
				$desc	 = self::getExceptionString($e);
				$traces	 = $e->getTrace();
				break;
			case (is_string($e)):
				$desc	 = $e;
				$e		 = null;
				break;
			default:
				return;
		}

		Logger::addSentryBreadcrumb($desc, self::getTraceCategory(), CLogger::LEVEL_ERROR, \Sentry\Breadcrumb::TYPE_ERROR, self::filterTrace($traces));
		if ($capture)
		{
			\Sentry\captureMessage($desc, new Sentry\Severity(Sentry\Severity::ERROR));
		}
		$desc	 .= self::getRequestDetails();
		$desc	 .= self::getBackTrace(0, $e);
		self::create($desc, CLogger::LEVEL_ERROR);
		Logger::writeToConsole("ERROR:: " . $desc);
	}

	public static function beginProfile($desc)
	{
		$key											 = md5($desc);
		self::$_profileCache[$key]['startTime']			 = round(Yii::getLogger()->executionTime * 1000, 3);
		self::$_profileCache[$key]['startMemoryUsage']	 = round(Yii::getLogger()->memoryUsage / 1024, 2);
		self::$_profileCache[$key]['desc']				 = $desc;
	}

	public static function endProfile($desc)
	{
		$key = md5($desc);
		if (!isset(self::$_profileCache[$key]))
		{
			return;
		}
		$timeTaken			 = round(Yii::getLogger()->executionTime * 1000, 3);
		$profileTimeTaken	 = self::getProfileExecutionTime($desc);
		$memoryUsage		 = round(Yii::getLogger()->memoryUsage / 1024, 2);
		$profileMemoryUsage	 = self::getProfileMemoryUsage($desc);
		self::profile("[ET: $profileTimeTaken/$timeTaken] [MU: $profileMemoryUsage/$memoryUsage] \n\t$desc");
		unset(self::$_profileCache[$key]);
	}

	public static function getProfileExecutionTime($desc)
	{
		$key				 = md5($desc);
		$timeTaken			 = round(Yii::getLogger()->executionTime * 1000, 3);
		$profileTimeTaken	 = round($timeTaken - self::$_profileCache[$key]['startTime'], 3);
		return $profileTimeTaken;
	}

	public static function getProfileMemoryUsage($desc)
	{
		$key				 = md5($desc);
		$memoryUsage		 = round(Yii::getLogger()->memoryUsage / 1024, 2);
		$profileMemoryUsage	 = round($memoryUsage - self::$_profileCache[$key]['startMemoryUsage'], 2);
		return $profileMemoryUsage;
	}

	public static function exception(Exception $e)
	{
		$error	 = self::getExceptionString($e);
		$key	 = md5($error);
		if (!isset($GLOBALS[__CLASS__][__FUNCTION__][$key]))
		{
			$code	 = self::getErrorCode($e);
			$code	 = is_numeric($code) ? intval($code) : 0;

			switch ($code)
			{
				case $code >= 400 && $code <= 404:
				case ReturnSet::ERROR_VALIDATION:
				case ReturnSet::ERROR_UNAUTHORISED:
				case ReturnSet::ERROR_NO_RECORDS_FOUND:
				case ReturnSet::ERROR_INVALID_DATA:
				case ReturnSet::ERROR_REQUEST_CANNOT_PROCEED:
					Logger::warning($e, true);
					break;
				case ReturnSet::ERROR_SERVER:
				case ReturnSet::ERROR_NULL:
				case ReturnSet::ERROR_UNKNOWN:
				case ReturnSet::ERROR_FAILED:
				default:
					self::pushTraceLogs();
					Logger::error($e, true);
					break;
			}

			$GLOBALS[__CLASS__][__FUNCTION__][$key] = $error;
		}
	}

	public static function getExceptionString(Exception $e)
	{
		$code	 = self::getErrorCode($e);
		$error	 = $code . ": " . $e->getMessage();
		return $error;
	}

	public static function getErrorCode(Exception $e)
	{
		$code = 0;
		if ($e instanceof CHttpException)
		{
			$code = $e->statusCode;
		}
		if ($code == 0 || !($e instanceof CHttpException))
		{
			$code = $e->getCode();
		}

		return $code;
	}

	public static function getRequestDetails()
	{
		$desc = "";
		if (Yii::app() instanceof CWebApplication)
		{
			$desc	 .= "\n\tURL: [" . Yii::app()->request->getRequestType() . "]" . Yii::app()->request->getRequestUri();
			$desc	 .= "\n\tUser IP: " . Filter::getUserIP();
			if (Yii::app()->request->getUrlReferrer() != "")
			{
				$desc .= "\n\tReferrer: " . Yii::app()->request->getUrlReferrer();
			}
			if (Yii::app()->request->getIsPostRequest())
			{
				$desc .= "\n\tPOST: " . json_encode($_POST);
			}
			$body = Yii::app()->request->getRawBody();
			if (trim($body) != "")
			{
				$desc .= "\n\tRawBody: " . $body;
			}
			$authCode = Yii::app()->request->getAuthorizationCode();
			if ($authCode != "")
			{
				$desc .= "\n\tAuthorization: " . $authCode;
			}

			if (UserInfo::getUserType() > 0 && UserInfo::getUserId() > 0)
			{
				$desc .= "\n\t[UserID: " . UserInfo::getUserId() . "] [UserType: " . UserInfo::getUserType() . "]";
			}
		}
		return $desc;
	}

	public static function writeToConsole($desc)
	{
		if (Yii::app() instanceof CConsoleApplication)
		{
			echo "$desc\n";
		}
	}

	/**
	 * Set a default category from module, controller and action
	 *
	 *  */
	public static function setActionCategory($module, $controller, $action)
	{
		$category = self::_getActionCategoryName($module, $controller, $action);
		self::setCategory($category);
		if (!in_array(self::$_levels[$category], ["error", "", "warning"]))
		{
			$msg	 = self::getRequestDetails();
			$msg	 = "\n------------------------------------------------------{$category} ACTION START------------------------------------------------------" . $msg;
			$method	 = (method_exists(__CLASS__, self::$_levels[$category])) ? self::$_levels[$category] : "info";
			Logger::{$method}($msg);
		}
		if (self::$_levels[$category] != "profile")
		{
			$msg = self::getRequestDetails();
			$msg = "\n------------------------------------------------------Profile {$category} ACTION START------------------------------------------------------" . $msg;
			Logger::profile($msg);
		}
		Logger::beginProfile($category);
	}

	private static function _getActionCategoryName($module, $controller, $action)
	{
		$moduleStr = ($module == "") ? "module.default." : "module." . $module . ".";
		if (in_array($action, ['REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE', 'REST.OPTIONS']))
		{
			$action	 = Yii::app()->request->getParam("id");
			$type	 = Yii::app()->request->getParam("requestType", "");
			if ($controller == "gmt" && $action == "request" && $type != "")
			{
				$action = strtolower($type);
			}

			$moduleStr = "api." . $moduleStr;
		}
		$category = $moduleStr . "controller." . $controller . "." . $action;
		return $category;
	}

	/**
	 * Unset a default category from module, controller and action
	 *
	 *  */
	public static function unsetActionCategory($module, $controller, $action)
	{
		$category	 = self::_getActionCategoryName($module, $controller, $action);
		$timeTaken	 = Yii::getLogger()->executionTime * 1000;

		Logger::endProfile($category);
		if (!in_array(self::$_levels[$category], ["error", "", "warning"]))
		{
			$msg	 = "\n*******************************************************{$category} ACTION DONE****************************************************************";
			$method	 = (method_exists(__CLASS__, self::$_levels[$category])) ? self::$_levels[$category] : "info";
			Logger::{$method}($msg);
		}

		if (self::$_levels[$category] != "profile")
		{
			$msg = "\n*******************************************************Profile {$category} ACTION DONE****************************************************************";
			Logger::profile($msg);
		}

		$maxTime = Config::get("profiler." . $category);
		if (!is_array(self::$_profileCache) || !array_key_exists($category, self::$_profileCache) || !array_key_exists('startTime', self::$_profileCache[$category]))
		{
			goto skipProfileLog;
		}

		$profileTimeTaken = ($timeTaken - self::$_profileCache[$category]['startTime']);
		if ($maxTime > 0 && $maxTime < $profileTimeTaken)
		{
			self::pushProfileLogs($category);
		}
		skipProfileLog:
		self::unsetCategory($category);
	}

	public static function setCommandCategory($command, $action)
	{
		$category = "command." . $command . "." . $action;
		self::setCategory($category);
		if (!in_array(self::$_levels[$category], ["error", "", "warning"]))
		{
			$msg	 = "\n------------------------------------------------------ {$category} COMMAND START------------------------------------------------------";
			$method	 = (method_exists(__CLASS__, self::$_levels[$category])) ? self::$_levels[$category] : "info";
			Logger::{$method}($msg);
		}

		Logger::beginProfile($category);
	}

	public static function unsetCommandCategory($command, $action)
	{
		$category = "command." . $command . "." . $action;
		Logger::endProfile($category);
		if (!in_array(self::$_levels[$category], ["error", "", "warning"]))
		{
			$msg	 = "\n******************************************************** {$category} COMMAND DONE*******************************************************";
			$method	 = (method_exists(__CLASS__, self::$_levels[$category])) ? self::$_levels[$category] : "info";
			Logger::{$method}($msg);
		}

		self::unsetCategory($category);
	}

	/**
	 * Set model category
	 *
	 *  */
	public static function setModelCategory($class, $function, $namespace = '')
	{
		$namespace	 = ($namespace == "") ? "" : $namespace . ".";
		$category	 = "models." . $namespace . $class . "." . $function;
		self::setCategory($category);
		if (!in_array(self::$_levels[$category], ["error", "", "warning"]))
		{
			$msg	 = self::getRequestDetails();
			$msg	 = "\n+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++{$category} START++++++++++++++++++++++++++++++++++++++++++++++++++" . $msg;
			$method	 = (method_exists(__CLASS__, self::$_levels[$category])) ? self::$_levels[$category] : "info";
			Logger::{$method}($msg);
		}
		Logger::beginProfile($category);
		if (self::$_levels[$category] != "profile")
		{
			$msg = self::getRequestDetails();
			$msg = "\n+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++profile {$category} START++++++++++++++++++++++++++++++++++++++++++++++++++" . $msg;
			Logger::profile($msg);
		}
	}

	/**
	 * unset model category
	 *
	 *  */
	public static function unsetModelCategory($class, $function, $namespace = '')
	{
		$namespace			 = ($namespace == "") ? "" : $namespace . ".";
		$category			 = "models." . $namespace . $class . "." . $function;
		$timeTaken			 = Yii::getLogger()->executionTime * 1000;
		$profileTimeTaken	 = ($timeTaken - self::$_profileCache[md5($category)]['startTime']);
		Logger::endProfile($category);
		if (!in_array(self::$_levels[$category], ["error", "", "warning"]))
		{
			$msg	 = "\n============================================================{$category} DONE==================================================";
			$method	 = (method_exists(__CLASS__, self::$_levels[$category])) ? self::$_levels[$category] : "info";
			Logger::{$method}($msg);
		}
		if (self::$_levels[$category] != "profile")
		{
			$msg = "\n============================================================Profile {$category} DONE==================================================";
			Logger::profile($msg);
		}
		$maxTime = Config::get("profiler." . $category);
		if ($maxTime > 0 && $maxTime < $profileTimeTaken)
		{
			self::pushProfileLogs($category);
		}
		self::unsetCategory($category);
	}

	public static function unsetCategory($param)
	{
		$value = Config::get("Logger." . $param);
		if ($value)
		{
			self::setDefaultLevel($value);
		}
		else
		{
			self::clearDefaultLevel();
		}
		$category = self::getPrefix() . $param;
		if (!isset(self::$_categories[$category]))
		{
			return;
		}
		self::$_categories[$category] = self::$_categories[$category] - 1;
		if (self::$_categories[$category] <= 0)
		{
			unset(self::$_levels[$param]);
			unset(self::$_categories[$category]);
		}
	}

	public static function getLastTrace($traces = null)
	{
		if ($traces == null)
		{
			$traces = debug_backtrace();
		}
		$msg	 = "";
		$trace	 = self::filterTrace($traces);

		$a = new \ReflectionClass('ReturnSet');
		try
		{
			if (empty($trace))
			{
				goto result;
			}

			if (isset($trace['class'], $trace['type'], $trace['function']))
			{
				$clsString = $trace['class'] . $trace['type'] . $trace['function'];
			}

			if (!isset($trace['file']) && $clsString != '')
			{
				$msg .= $clsString;
				goto result;
			}


			$file = str_replace(APPLICATION_PATH, "", $trace['file']);

			$msg .= $file . " (" . $trace['line'] . ")";
		}
		catch (Exception $e)
		{
			$desc = $e->getMessage();
			self::create($desc, CLogger::LEVEL_ERROR);
		}

		result:
		return $msg;
	}

	public static function getTraceCategory($traces = null)
	{
		try
		{
			$category = self::getCategory();
			if ($traces == null)
			{
				$traces = debug_backtrace();
			}
			$dir	 = str_replace(DIRECTORY_SEPARATOR, ".", APPLICATION_PATH);
			$msg	 = [];
			$trace	 = self::filterTrace($traces);

			if (isset($trace['class']))
			{
				$msg[] = $trace["class"];
				goto checkFunction;
			}

			if (isset($trace['file'], $trace['line']))
			{
				$file	 = str_replace(APPLICATION_PATH . DIRECTORY_SEPARATOR, "", $trace['file']);
				$dir	 = str_replace(DIRECTORY_SEPARATOR, ".", $file);
				$msg[]	 = $dir;
			}

			checkFunction:
			if (isset($trace["function"]))
			{
				$msg[] = $trace["function"];
			}

			$category = implode(".", $msg);
		}
		catch (Exception $exc)
		{
			$desc = $exc->getMessage();
			self::create($desc, CLogger::LEVEL_ERROR);
		}

		return $category;
	}

	public static function filterTrace($traces = null)
	{
		if ($traces == null)
		{
			$traces = debug_backtrace();
		}
		$a			 = new \ReflectionClass('ReturnSet');
		$finalTrace	 = [];
		foreach ($traces as $trace)
		{

			if (!isset($trace['class']))
			{
				goto skipClass;
			}

			if ($trace['class'] == __CLASS__)
			{
				continue;
			}

			if (!isset($trace['function']))
			{
				goto skipClass;
			}

			if ($trace['class'] == $a->getName() && $trace['function'] == "setException")
			{
				continue;
			}

			$finalTrace = $trace;
			break;
			skipClass:
			if (strpos($trace['file'], YII_PATH) !== 0 || isset($trace['file']) && $trace['file'] == __FILE__)
			{
				continue;
			}
			$finalTrace = $trace;
			break;
		}
		return $finalTrace;
	}

	public static function getBackTrace($traceLevel = 0, Exception $e = null)
	{
		$msg = "";
		if (!YII_DEBUG)
		{
			if ($e instanceof Exception)
			{
				$traces = $e->getTrace();
			}
			else
			{
				$traces = debug_backtrace();
			}
			$count = 0;
			foreach ($traces as $trace)
			{
				if (isset($trace['file'], $trace['line']) && strpos($trace['file'], YII_PATH) !== 0 && $trace['file'] != __FILE__)
				{
					$file = str_replace(APPLICATION_PATH, "", $trace['file']);

					$msg .= "\n\tin " . $file . ' (' . $trace['line'] . ') ';
					if (isset($trace["class"]) && in_array($trace["class"], [__CLASS__]))
					{
						continue;
					}
					if (isset($trace["class"]) && $trace["class"] != '')
					{
						$msg .= $trace["class"] . $trace["type"];
					}
					if (isset($trace["function"]) && $trace["function"] != '')
					{
						$str = [];
						if(isset($trace['args']))
						{
							foreach ($trace['args'] as $arg)
							{
								$str[] = self::encodeValueByType($arg);
							}
						}
						$argStr	 = implode(", ", $str);
						$msg	 .= $trace["function"] . "({$argStr})";
					}
					if (++$count >= ($traceLevel) && $traceLevel > 0)
						break;
				}
			}
		}
		return $msg;
	}

	public static function encodeValueByType($arg)
	{
		$str = "";
		switch (true)
		{
			case is_array($arg):
				if (count($arg) < 10)
				{
					$val = json_encode($arg);
					if (strlen($val) > 100)
					{
						$val = substr($val, 0, 97) . " ...]";
					}
					$str = $val;
				}
				else
				{
					$str = "Array";
				}
				break;
			case is_object($arg):
				$str	 = "object(" . get_class($arg) . ")";
				break;
			default :
				$string	 = json_encode($arg);
				$str	 = (strlen($string) > 100) ? substr($string, 0, 97) . "..." : $string;
				break;
		}
		return $str;
	}

	public static function pushTraceLogs()
	{
		foreach (self::$_levels as $category => $level)
		{
			$traceLevel = "trace." . $category;
			if (array_key_exists($traceLevel, self::$_categories))
			{
				continue;
			}

			$existingCategory	 = $level . (($level == "") ? "" : ".") . $category;
			$exceptCategory		 = "profile." . $category;
			$logs				 = Yii::getLogger()->getLogs("trace,info,warning,error", $existingCategory, $exceptCategory);
			$msg				 = self::getRequestDetails();
			Yii::log("\n###################################################### PUSH TRACE LOG START {$traceLevel}  ##############################################################" . $msg, CLogger::LEVEL_TRACE, $traceLevel);
			foreach ($logs as $log)
			{
				Yii::log($log[0], $log[1], $traceLevel);
			}
			Yii::log("\n~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ PUSH TRACE LOG END {$traceLevel}  ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~", CLogger::LEVEL_TRACE, $traceLevel);
		}
	}

	public static function pushProfileLogs($allowCategory = "")
	{
		foreach (self::$_levels as $category => $level)
		{
			if ($allowCategory != "" && $category != $allowCategory)
			{
				continue;
			}

			$traceLevel = "profile." . $category;
			if (array_key_exists($traceLevel, self::$_categories))
			{
				continue;
			}

			$existingCategory	 = $level . (($level == "") ? "" : ".") . $category;
			$logs				 = Yii::getLogger()->getLogs("profile,error", $existingCategory, "");
			$msg				 = self::getRequestDetails();
			Yii::log("\n###################################################### PUSH PROFILE LOG START {$traceLevel}  ##############################################################" . $msg, CLogger::LEVEL_PROFILE, $traceLevel);
			foreach ($logs as $log)
			{
				Yii::log($log[0], $log[1], $traceLevel);
				self::addSentryBreadcrumb($log[0], $traceLevel, $log[1]);
			}
			self::addSentryBreadcrumb($msg, $traceLevel, CLogger::LEVEL_TRACE);
			\Sentry\captureMessage("Profile Logs for {$traceLevel}", new \Sentry\Severity(\Sentry\Severity::WARNING));
			Yii::log("\n~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ PUSH PROFILE LOG END {$traceLevel}  ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~", CLogger::LEVEL_PROFILE, $traceLevel);
		}
	}

	public static function profileMaxTime($maxTime)
	{
		if ($maxTime == "" || $maxTime == 0)
		{
			return;
		}
		$time = Filter::getExecutionTime();
		if ($time > $maxTime)
		{
			self::pushProfileLogs();
		}
	}

	public static function flush()
	{
		Yii::getLogger()->flush(true);
		Sentry\SentrySdk::getCurrentHub()->getClient()->flush();
	}

}
