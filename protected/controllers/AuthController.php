<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once(dirname(__FILE__) . '/BaseController.php');

class AuthController extends BaseController
{

	public $newHome		 = '';
	public $layout		 = '//layouts/column1';
	public $pageHeader	 = '';

	public function filters()
	{
		return array
			(
			array
				(
				'application.filters.HttpsFilter',
				'bypass' => false
			),
		);
	}

	/**
	 * This function is used for generating auth token for a 
	 * device and its user
	 */
	public static function actionGetAuthToken()
	{
		$userId			 = 123;
		$deviceInfo		 = "Android";
		$deviceId		 = "dskso21e21j";
		$deviceVersion	 = "1.10";

		//This payload is being used for generating the auth token
		$payLoad = array
			(
			"userId"			 => $userId,
			"deviceInfo"		 => $deviceInfo,
			"deviceId"			 => $deviceId,
			"deviceOsVersion"	 => $deviceVersion
		);

		$validity	 = 24 * 60 * 60;
		$token		 = Yii::app()->JWT->generateToken($payLoad, $validity);
		echo $token;
		//print_r($token, true);
		exit;
	}

	/**
	 * This function is used for decoding the auth token
	 */
	public function actionDecodeAuthToken()
	{
		$key	 = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ3ZWJzaXRlTGluayI6Imh0dHBzOlwvXC93d3cuZ296b2NhYnMuY29tXC8iLCJkYXRhIjp7InVzZXJJZCI6MTIzLCJkZXZpY2VJbmZvIjoiQW5kcm9pZCIsImRldmljZUlkIjoiZHNrc28yMWUyMWoiLCJkZXZpY2VPc1ZlcnNpb24iOiIxLjEwIiwiY3JlYXRlZERhdGVUaW1lIjoiMjAyMi0wMS0xMyAwMzo0MDowNXBtIiwiaXNzdWVkVGltZSI6MTY0MjA2ODYwNSwiZXhwaXJ5VGltZSI6MTY0MjA3MjIwNX0sInJhbmRvbU51bWJlciI6NjU2NDY1NDg0fQ.sfEPHeWslNpVY-r6MDNsViDnx5nZL9QPZCaWpD7UEnY";
		$key1	 = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ3ZWJzaXRlTGluayI6Imh0dHBzOlwvXC93d3cuZ296b2NhYnMuY29tXC8iLCJkYXRhIjp7InVzZXJJZCI6MTIzLCJjcmVhdGVkRGF0ZVRpbWUiOiIyMDE5LTA5LTIxIDAzOjIyOjA5cG0iLCJleHBpcnlUaW1lIjoxNTY5MDYzMTI5LCJpc3N1ZWRUaW1lIjoxNTY5MDU5NTI5fSwicmFuZG9tTnVtYmVyIjo2NTY0NjU0ODR9.Hf1wmpgOuuWiCmB8SmSoWw-aX7KHZdJzQPFeo5mdRjg";

		$tokenData = Yii::app()->JWT->decodeToken($key);

		echo print_r($tokenData, true);
		exit;
	}

}

?>
