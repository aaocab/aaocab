<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DBTransaction
 *
 * @author admin
 */
class DBTransaction
{

	public static $transaction;

	//put your code here
	public static function start()
	{
		if (!self::$transaction instanceof CDbTransaction)
		{
			self::$transaction = Yii::app()->db->beginTransaction();
		}
		return self::$transaction;
	}

	public static function commit()
	{
		if (self::$transaction instanceof CDbTransaction)
		{
			self::$transaction->commit();
			self::$transaction = null;
		}
	}

	public static function rollback()
	{
		if (self::$transaction instanceof CDbTransaction)
		{
			self::$transaction->rollback();
			self::$transaction = null;
		}
	}

}
