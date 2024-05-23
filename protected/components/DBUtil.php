<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DBUtil
 *
 * @author akhet
 */
class DBUtil
{

	const ReturnType_Command	 = 1;
	const ReturnType_Query	 = 2;
	const ReturnType_Row		 = 3;
	const ReturnType_Provider	 = 4;

	private static $transStartTime	 = 0;
	private static $pctr			 = 0;
	private static $_SDB			 = null;
	private static $_SDB2			 = null;
	private static $_SDB3			 = null;
	private static $_status			 = [];

	//put your code here

	/**
	 * @return CDbConnection Master Database 
	 */
	public static function MDB()
	{
		return Yii::app()->db;
	}

	/**
	 * @return CDbConnection SLAVE Database 
	 */
	public static function SDB($checkSlave = false, $altDB = 2)
	{
		if (!$checkSlave)
		{
			self::$_SDB = Yii::app()->db1;
		}
		else
		{
			$success = DBUtil::isSlaveUpdated(Yii::app()->db1);
			if ($success)
			{
				self::$_SDB = Yii::app()->db1;
				goto end;
			}

			if ($altDB == 1)
			{
				self::$_SDB = DBUtil::MDB();
			}
			else if ($altDB == 2)
			{
				self::$_SDB = DBUtil::SDB2($checkSlave, 1);
			}
		}

		end:
		return self::$_SDB;
	}

	/**
	 * @return CDbConnection SLAVE Database 
	 */
	public static function SDB2($checkSlave = false, $altDB = 1)
	{
		if (!$checkSlave)
		{
			self::$_SDB2 = Yii::app()->db2;
		}
		else
		{
			$success = DBUtil::isSlaveUpdated(Yii::app()->db2);
			if ($success)
			{
				self::$_SDB2 = Yii::app()->db2;
				goto end;
			}

			if ($altDB == 1)
			{
				self::$_SDB2 = DBUtil::MDB();
			}
			else if ($altDB == 2)
			{
				self::$_SDB2 = DBUtil::SDB2($checkSlave, 1);
			}
		}

		end:
		return self::$_SDB2;
	}

	/**
	 * @return CDbConnection SLAVE Database 
	 */
	public static function SDB3($checkSlave = false)
	{
		if (self::$_SDB3 == null && $checkSlave)
		{
			$success = DBUtil::isSlaveRunning(Yii::app()->db3);
			if ($success === false)
			{
				self::$_SDB3 = DBUtil::SDB2();
			}
			else
			{
				self::$_SDB3 = Yii::app()->db3;
			}
		}
		else if (!$checkSlave)
		{
			self::$_SDB3 = Yii::app()->db3;
		}
		return self::$_SDB3;
	}

	/**
	 * @return CDbConnection Archive Database 
	 */
	public static function ADB()
	{
		return Yii::app()->adb;
	}

	/**
	 * @return CDbCommand 
	 */
	public static function command($sql, $db = null, $cacheDuration = null, $cacheDependency = null)
	{
		if ($db == null)
		{
			$db = self::MDB();
		}

		if ($cacheDuration != null && $cacheDependency != null)
		{
			$dependency	 = new CacheDependency($cacheDependency);
			$db			 = $db->cache($cacheDuration, $dependency);
		}

		$cdb = $db->createCommand($sql);
		return $cdb;
	}

	/** @param CActiveRecord $activeModel */
	public static function insertPriotiy(&$activeModel)
	{
		$success		 = false;
		$builder		 = $activeModel->getCommandBuilder();
		$table			 = $activeModel->getTableSchema();
		$data			 = $activeModel->getAttributes();
		//	$builder->ensureTable($table);
		$fields			 = array();
		$values			 = array();
		$placeholders	 = array();
		$i				 = 0;

		foreach ($data as $name => $value)
		{
			if (($column = $table->getColumn($name)) !== null && ($value !== null || $column->allowNull))
			{
				$fields[] = $column->rawName;
				if ($value instanceof CDbExpression)
				{
					$placeholders[]	 = $value->expression;
					foreach ($value->params as $n => $v)
						$values[$n]		 = $v;
				}
				else
				{
					$placeholders[]									 = CDbCommandBuilder::PARAM_PREFIX . $i;
					$values[CDbCommandBuilder::PARAM_PREFIX . $i]	 = $column->typecast($value);
					$i++;
				}
			}
		}
		if ($fields === array())
		{
			$pks = is_array($table->primaryKey) ? $table->primaryKey : array($table->primaryKey);
			foreach ($pks as $pk)
			{
				$fields[]		 = $table->getColumn($pk)->rawName;
				$placeholders[]	 = $builder->getIntegerPrimaryKeyDefaultValue();
			}
		}
		$sql = "INSERT LOW_PRIORITY INTO {$table->rawName} (" . implode(', ', $fields) . ') VALUES (' . implode(', ', $placeholders) . ')';

		$command = $builder->getDbConnection()->createCommand($sql);

		foreach ($values as $name => $value)
			$command->bindValue($name, $value);

		if (!$command->execute())
		{
			goto end;
		}

		$primaryKey = $table->primaryKey;
		if ($table->sequenceName !== null)
		{
			if (is_string($primaryKey) && $activeModel->$primaryKey === null)
				$activeModel->$primaryKey = $builder->getLastInsertID($table);
			elseif (is_array($primaryKey))
			{
				foreach ($primaryKey as $pk)
				{
					if ($activeModel->$pk === null)
					{
						$activeModel->$pk = $builder->getLastInsertID($table);
						break;
					}
				}
			}
		}
		$activeModel->setOldPrimaryKey($activeModel->getPrimaryKey());
		$activeModel->setIsNewRecord(false);
		$activeModel->setScenario('update');
		$success = true;

		end:
		return $success;
	}

	/**
	 * @return CDbDataReader
	 */
	public static function query($sql, $db = null, $params = [], $cacheDuration = null, $cacheDependency = null)
	{
		$key = "SQL: $sql \n\t [params: " . json_encode($params) . "]";
		Logger::beginProfile($key);
		try
		{
			$result = self::command($sql, $db, $cacheDuration, $cacheDependency)->query($params);
		}
		catch (Exception $e)
		{
			throw self::processException($e, $sql, $params);
		}
		Logger::endProfile($key);
		return $result;
	}

	/**
	 * Executes the SQL statement and returns all rows.
	 * @param array $params input parameters (name=>value) for the SQL execution. 
	 * @param boolean $fetchAssociative whether each row should be returned as an associated array with
	 * column names as the keys or the array keys are column indexes (0-based).
	 * @return array all rows of the query result. Each array element is an array representing a row.
	 * An empty array is returned if the query results in nothing.
	 * @throws CException execution failed
	 */
	public static function queryAll($sql, $db = null, $params = [], $fetchAssociative = true, $cacheDuration = null, $cacheDependency = null)
	{
		$key = "SQL: $sql \n\t [params: " . json_encode($params) . "]";
		Logger::beginProfile($key);
		try
		{
			$result = self::command($sql, $db, $cacheDuration, $cacheDependency)->queryAll($fetchAssociative, $params);
		}
		catch (Exception $e)
		{
			throw self::processException($e, $sql, $params);
		}
		Logger::endProfile($key);
		return $result;
	}

	/**
	 * @param string $sql query to execute
	 * @param CDbConnection $db The database connection
	 * @param array $params parameters for SQL query
	 * @param string $cacheDuration cache duration in seconds
	 * @param string $cacheDepency Dependency name
	 * @return mixed the first row (in terms of an array) of the query result, false if no result.
	 */
	public static function queryRow($sql, $db = null, $params = [], $cacheDuration = null, $cacheDependency = null)
	{
		$key = "SQL: $sql \n\t [params: " . json_encode($params) . "]";
		Logger::beginProfile($key);
		try
		{
			$result = self::command($sql, $db, $cacheDuration, $cacheDependency)->queryRow(true, $params);
		}
		catch (Exception $e)
		{
			throw self::processException($e, $sql, $params);
		}
		Logger::endProfile($key);
		return $result;
	}

	private static function processException(Exception $e, $sql = "", $params = [])
	{
		$message = $e->getMessage();
		if (!YII_DEBUG)
		{
			$message .= '.\n\tThe SQL statement executed was: ' . $sql;
			if (is_array($params) && count($params) > 0)
			{
				$message .= '\n\tPARAMS: ' . json_encode($params);
			}
		}
		$ex = new CDbException($message, $e->getCode(), $e->errorInfo);
		return $ex;
	}

	/**
	 * Executes the SQL statement and returns the value of the first column in the first row of data.
	 * This is a convenient method of {@link query} when only a single scalar
	 * value is needed (e.g. obtaining the count of the records).
	 * @param array $params input parameters (name=>value) for the SQL execution. This is an alternative
	 * to {@link bindParam} and {@link bindValue}. If you have multiple input parameters, passing
	 * them in this way can improve the performance. Note that if you pass parameters in this way,
	 * you cannot bind parameters or values using {@link bindParam} or {@link bindValue}, and vice versa.
	 * Please also note that all values are treated as strings in this case, if you need them to be handled as
	 * their real data types, you have to use {@link bindParam} or {@link bindValue} instead.
	 * @return mixed the value of the first column in the first row of the query result. False is returned if there is no value.
	 * @throws CException execution failed
	 */
	public static function queryScalar($sql, $db = null, $params = [], $cacheDuration = null, $cacheDependency = null)
	{
		$key = "SQL: $sql \n\t [params: " . json_encode($params) . "]";
		Logger::beginProfile($key);
		try
		{
			$result = self::command($sql, $db, $cacheDuration, $cacheDependency)->queryScalar($params);
		}
		catch (Exception $e)
		{
			throw self::processException($e, $sql, $params);
		}
		Logger::endProfile($key);
		return $result;
	}

	/**
	 * Executes the SQL statement.
	 * This method is meant only for executing non-query SQL statement.
	 * No result set will be returned.
	 * @param array $params input parameters (name=>value) for the SQL execution. This is an alternative
	 * to {@link bindParam} and {@link bindValue}. If you have multiple input parameters, passing
	 * them in this way can improve the performance. Note that if you pass parameters in this way,
	 * you cannot bind parameters or values using {@link bindParam} or {@link bindValue}, and vice versa.
	 * Please also note that all values are treated as strings in this case, if you need them to be handled as
	 * their real data types, you have to use {@link bindParam} or {@link bindValue} instead.
	 * @return integer number of rows affected by the execution.
	 * @throws CDbException execution failed
	 */
	public static function execute($sql, $params = [])
	{
		$key = "SQL: $sql \n\t [params: " . json_encode($params) . "]";
		Logger::beginProfile($key);
		try
		{
			$result = self::command($sql, DBUtil::MDB())->execute($params);
		}
		catch (Exception $e)
		{
			Logger::trace($sql . "\n Params: " . json_encode($params));
			throw self::processException($e, $sql, $params);
		}
		Logger::endProfile($key);
		return $result;
	}

	public static function createTempTable($tableName, $sql, $db = null)
	{
		if ($db == null)
		{
			$db = DBUtil::SDB();
		}

		$sqlCreate	 = "CALL prcCreateTempTable(:tableName, :sql)";
		$params		 = ["tableName" => $tableName, "sql" => $sql];
		$key		 = "SQL: $sqlCreate \n\t [params: " . json_encode($params) . "]";
		Logger::beginProfile($key);
		try
		{
			$result = self::command($sqlCreate, $db)->execute($params);
		}
		catch (Exception $e)
		{
			throw self::processException($e, $sqlCreate, $params);
		}
		Logger::endProfile($key);
		return $result;
	}

	public static function dropTempTable($tableName, $db = null)
	{
		if ($db == null)
		{
			$db = DBUtil::SDB();
		}

		$sqlDrop = "CALL prcDropTempTable(:tableName)";
		$params	 = ["tableName" => $tableName];
		$key	 = "SQL: $sqlDrop \n\t [table: " . $tableName . "]";
		Logger::beginProfile($key);
		try
		{
			$result = self::command($sqlDrop, $db)->execute($params);
		}
		catch (Exception $e)
		{
			throw self::processException($e, $sqlDrop, $params);
		}
		Logger::endProfile($key);
		return $result;
	}

	/**
	 * @param array|string $inParams string should be comma separated 
	 * @param string $bindString parameterized variable for statement query will stored in variable 
	 * @param array $params Array will be filled with corresponding parameterized variable
	 * @return bool|array return false if failed else $params will be returned
	 */
	public static function getINStatement($inParams, &$bindString, &$params)
	{
		if (is_array($inParams))
		{
			$arr = $inParams;
		}
		else if (is_string($inParams) || is_numeric($inParams))
		{
			$arr = array_map('trim', explode(",", $inParams));
		}
		else
		{
			return false;
		}
		$bindParams = [];
		foreach ($arr as $val)
		{
			$paramStr				 = ":gp" . self::$pctr++;
			$bindParams[$paramStr]	 = $val;
		}
		$bindString	 = implode(",", array_keys($bindParams));
		$params		 = $bindParams;
		return $params;
	}

	/**
	 * @param string $likeParams string should be comma separated 
	 * @param string $bindString parameterized variable for statement query will stored in variable 
	 * @param array $params Array will be filled with corresponding parameterized variable
	 * @return bool|string return false if failed else $params will be returned
	 */
	public static function getLikeStatement($likeParams, &$bindString, &$params, $usePrefix = "%", $useSuffix = "%")
	{
		if (!is_string($likeParams))
		{
			return false;
		}

		$paramStr	 = ":gp" . self::$pctr++;
		$bindString	 = "CONCAT(";
		if ($usePrefix != "")
		{
			$bindString .= "'{$usePrefix}', ";
		}
		$bindString .= $paramStr;
		if ($useSuffix != "")
		{
			$bindString .= ", '{$useSuffix}'";
		}
		$bindString .= ")";

		$params				 = [];
		$params[$paramStr]	 = $likeParams;
		return $params;
	}

	/**
	 * @return CDbTransaction 
	 */
	public static function beginTransaction($db = null)
	{
		if ($db == null)
		{
			$db = self::MDB();
		}

		$trans		 = null;
		$isActive	 = $db->getCurrentTransaction();
		if (!$isActive)
		{
			$trans = $db->beginTransaction();
			Logger::beginProfile("DBUtil::Transaction");
		}
		return $trans;
	}

	/** @param CDbTransaction $transaction */
	public static function commitTransaction($transaction)
	{
		$success = false;
		if ($transaction != null)
		{
			$transaction->commit();
			$success			 = true;
			$profileTimeTaken	 = Logger::getProfileExecutionTime("DBUtil::Transaction");
			if ($profileTimeTaken > 20000)
			{
				Logger::pushProfileLogs();
			}
			Logger::endProfile("DBUtil::Transaction");
		}
		return $success;
	}

	/** @param CDbTransaction $transaction */
	public static function rollbackTransaction($transaction)
	{
		$success = false;
		if ($transaction != null && $transaction->active)
		{
			$transaction->rollback();
			self::$transStartTime	 = 0;
			$success				 = true;
			$profileTimeTaken		 = Logger::getProfileExecutionTime("DBUtil::Transaction");
			if ($profileTimeTaken > 20000)
			{
				Logger::pushProfileLogs();
			}
			Logger::endProfile("DBUtil::Transaction");
		}
		return $success;
	}

	public static function getCurrentTime()
	{
		$now = self::MDB()->createCommand()->select(new CDbExpression("now()"))->queryScalar();
		return $now;
	}

	public static function addWorkingMinutes($minutes, $startTime = null)
	{
		if ($startTime == null)
		{
			$startTime = new CDbExpression("NOW()");
		}
		$time = "'$startTime'";
		if ($startTime instanceof CDbExpression)
		{
			$time = $startTime->expression;
		}
		$sql = "SELECT addWorkingMinutes(:minutes, $time) FROM dual";
		$res = self::SDB()->createCommand($sql)->queryScalar(['minutes' => $minutes]);
		return $res;
	}

	public static function CalcWorkingHour($fromDate, $toDate)
	{
		$fromTime	 = "'$fromDate'";
		$toTime		 = "'$toDate'";
		$res		 = 0;
		if ($fromDate instanceof CDbExpression)
		{
			$fromTime = $fromDate->expression;
		}
		if ($toTime)
		{
			$sql = "SELECT CalcWorkingHour($fromTime, $toTime) FROM dual";
			$res = self::SDB()->createCommand($sql)->queryScalar();
		}
		return $res;
	}

	public static function getTimeDiff($fromDate, $toDate = null)
	{
		try
		{
			$fromTime	 = "'$fromDate'";
			$toTime		 = "'$toDate'";
			if ($fromDate instanceof CDbExpression)
			{
				$fromTime = $fromDate->expression;
			}
			if ($toDate instanceof CDbExpression)
			{
				$toTime = $toDate->expression;
			}
			$sql = "SELECT TIMESTAMPDIFF(MINUTE, $fromTime, $toTime) as diff FROM dual";
			$res = self::SDB()->createCommand($sql)->queryScalar();
		}
		catch (Exception $e)
		{
			$res = false;
		}
		return $res;
	}

	public static function checkSlaveStatus($db = null)
	{

		if ($db == null)
		{
			$db = DBUtil::SDB();
		}

		if (isset(self::$_status[$db->connectionString]))
		{
			$row = self::$_status[$db->connectionString];
			goto end;
		}

		$row									 = DBUtil::queryRow("SHOW SLAVE STATUS", $db);
		self::$_status[$db->connectionString]	 = $row;

		end:
		return $row;
	}

	public static function isSlaveUpdated($db = null, $delayCheck = 10)
	{
		$slaveSyncTime = Config::get('SlaveSinkTime');
		// if slave is not running  or slave is running late by delayCheck  value
		if ((!self::isSlaveRunning($db)) || (self::slaveSyncDelay($db) >= $slaveSyncTime))
		{
			$rowProcessList		 = self::checkProcessList($db);
			$row				 = self::isSlaveRunning($db);
			$isSlaverRunning	 = ($row == false || $row["Slave_IO_Running"] == "No" || $row["Slave_SQL_Running"] == "No");
			$delayedByTime		 = $row['Seconds_Behind_Master'];
			$email				 = Config::get('SlaveSinkMail');
			$connectionString	 = $db->connectionString;
			$emailCom			 = new emailWrapper();
			$emailCom->slaveSinkMail($email, $connectionString, $isSlaverRunning, $delayedByTime, $rowProcessList);

			// sending sms for if slave is not running/ delay by certain time
			$isSendSyncSms = Config::get('isSendSyncSms');
			if ($isSendSyncSms)
			{
				$sendSyncSmsNumber = Config::get('sendSyncSmsNumber');
				smsWrapper::sendSlaveSyncSMS(91, $sendSyncSmsNumber, $connectionString, $isSlaverRunning, $delayedByTime);
			}
		}
		return (self::isSlaveRunning($db) && self::slaveSyncDelay($db) <= $delayCheck);
	}

	/** @return array|false */
	public static function isSlaveRunning($db = null)
	{
		$row = DBUtil::checkSlaveStatus($db);

		if ($row != false && ($row["Slave_IO_Running"] == "No" || $row["Slave_SQL_Running"] == "No"))
		{
			$row = false;
		}

		end:
		return $row;
	}

	/** @return integer|false */
	public static function slaveSyncDelay($db = null)
	{
		$row = DBUtil::isSlaveRunning($db);

		if ($row === false)
		{
			return false;
		}

		return $row["Seconds_Behind_Master"];
	}

	/** @return boolean */
	public static function waitForSlaveSync($db = null, $checkDelay = 0, $maxWait = 60)
	{
		$success	 = true;
		$delayedBy	 = DBUtil::slaveSyncDelay($db);

		if ($delayedBy === false)
		{
			$success = false;
			goto end;
		}

		if ($delayedBy > $checkDelay)
		{
			if ($checkDelay > $maxWait)
			{
				$success = false;
				goto end;
			}
			$wait	 = 1;
			usleep($wait * 1000);
			$success = self::waitForSlaveSync($db, $checkDelay + $wait, $maxWait);
		}

		end:
		return $success;
	}

	public static function CalcWorkingMinutes($fromDate, $toDate)
	{
		$fromTime	 = "'$fromDate'";
		$toTime		 = "'$toDate'";
		$res		 = 0;
		if ($fromDate instanceof CDbExpression)
		{
			$fromTime = $fromDate->expression;
		}
		if ($toTime)
		{
			$sql = "SELECT CalcWorkingMinutes($fromTime, $toTime) FROM dual";
			$res = self::SDB()->createCommand($sql)->queryScalar();
		}
		return $res;
	}

	public static function checkProcessList($db = null)
	{
		if ($db == null)
		{
			$db = DBUtil::SDB();
		}
		return DBUtil::queryAll("SHOW FULL PROCESSLIST", $db);
	}

}
