<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DateTimeFormat
{

	public static $dateOutcomeFormat	 = 'Y-m-d';
	public static $dateTimeOutcomeFormat = 'Y-m-d H:i:s';
	public static $dateIncomeFormat		 = 'yyyy-MM-dd';
	public static $dateTimeIncomeFormat	 = 'yyyy-MM-dd hh:mm:ss';

	public static function DateTimeToLocale($value)
	{
		return Yii::app()->dateFormatter->formatDateTime(
						CDateTimeParser::parse($value, self::$dateTimeIncomeFormat), 'short', 'short');
	}

	public static function LocaleToDateTime($value)
	{
		return date(self::$dateTimeOutcomeFormat, CDateTimeParser::parse($value, strtr(Yii::app()->locale->dateTimeFormat, array("{0}"	 => Yii::app()->locale->timeFormat,
					"{1}"	 => Yii::app()->locale->dateFormat))));
	}

	public function LocaleToTimeStamp($value)
	{
		return CDateTimeParser::parse($value, strtr(Yii::app()->locale->dateTimeFormat, array("{0}"	 => Yii::app()->locale->timeFormat,
					"{1}"	 => Yii::app()->locale->dateFormat)));
	}

	public static function DateToLocale($value)
	{
		return Yii::app()->dateFormatter->formatDateTime(
						CDateTimeParser::parse($value, self::$dateIncomeFormat), 'short', null);
	}

	public static function LocaleToDate($value)
	{
		return date(self::$dateOutcomeFormat, CDateTimeParser::parse($value, Yii::app()->locale->dateFormat));
	}

	public static function DateToDatePicker($value)
	{
		return date('d/m/Y', CDateTimeParser::parse($value, self::$dateIncomeFormat));
	}

	public static function DateTimeToDatePicker($value)
	{
		return date('d/m/Y', CDateTimeParser::parse($value, self::$dateTimeIncomeFormat));
	}

	public static function DateTimeToTimePicker($value)
	{
		return date('h:i a', CDateTimeParser::parse($value, self::$dateTimeIncomeFormat));
	}

	public static function DatePickerToDate($value)
	{
		return date(self::$dateOutcomeFormat, CDateTimeParser::parse($value, 'dd/MM/yyyy'));
	}

	public static function DateTimeToSQLDateTime(DateTime $date)
	{
		return $date->format("Y-m-d H:i:s");
	}

	public static function TimeToSQLTime($value, $format = 'h:i A')
	{
		return DateTime::createFromFormat($format, $value)->format('H:i:00');
	}

	public static function getHour($time)
	{
		$timestamp	 = strtotime($time);
		$hour		 = date('H', $timestamp);
		return $hour;
	}

	public static function parseDateTime($dateTime, &$date, &$time)
	{
		if ($dateTime == '')
		{
			return false;
		}
		$date	 = DateTimeFormat::DateTimeToDatePicker($dateTime);
		$time	 = DateTimeFormat::DateTimeToTimePicker($dateTime);

		return true;
	}

	public static function concatDateTime($date, $time, &$dateTime)
	{
		try
		{
			if ($date != null && $date != "")
			{
				$date1 = DateTimeFormat::DatePickerToDate($date);
			}
			if ($time != null && $time != "")
			{
				$time1 = DateTime::createFromFormat('h:i A', $time)->format('H:i:00');
			}
			if ($date1 != null && $time1 != null)
			{
				$dateTime	 = $dateTime1	 = $date1 . " " . $time1;
			}
		}
		catch (Exception $e)
		{
			Logger::warning("Invaild Date Time {$date} {$time}: \n\t".$e->getTraceAsString());
		}
		return ($dateTime1 !== null);
	}
	
	/** 
	 * @param String $sqlDateTime SQL DateTime Format
	 * @return DateTime 
	 * */	
	public static function SQLDateTimeToDateTime($sqlDateTime)
	{
		return DateTime::createFromFormat( self::$dateTimeOutcomeFormat, $sqlDateTime);
	}

	/**
	 * @param String $sqlDateTime SQL DateTime Format
	 * @return DateTime
	 * */
	public static function SQLDateTimeToLocaleDateTime($sqlDateTime)
	{
		return DateTime::createFromFormat(self::$dateTimeOutcomeFormat, $sqlDateTime)->format("d-m-Y H:i A");
	}

}
