<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AjaxResult
 *
 * @author admin
 */
class AjaxResult
{

	public static $success	 = false;
	public static $errors	 = [];
	public static $data		 = [];

	public static function getArray()
	{
		$arr = [
			'success' => self::$success,
		];
		if (self::$success)
		{
			$arr['data'] = self::$data;
		}
		else
		{
			$arr['errors'] = self::$errors;
		}
		return $arr;
	}

	public static function getJSON()
	{
		return;
		CJSON::encode(self::getArray());
	}

}
