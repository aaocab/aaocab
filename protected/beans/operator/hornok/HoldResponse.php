<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Beans\operator\hornok;

class HoldResponse
{

	public $response;
	public $tripId;
	public $msg;
	public $status;
	public $success;
	
	public static function setData($response = null, $bkgId = null)
	{
		$obj->tripId	 = $response->trip_id;
		$obj->success	 = $response->success;	
		$obj->msg		 = $response->message;	
		$obj->status	 = $response->status;	
		return $obj;
	}
}