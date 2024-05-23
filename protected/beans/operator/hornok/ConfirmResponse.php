<?php

namespace Beans\operator\hornok;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ConfirmResponse
{
	public $status;
	public $message;


	public function getData($success, $message)
	{
		$this->message = $message;
		if($success == true)
		{
			$this->status		 = "confirmed";    
		}
		else
		{
			$this->status        = "failed";
		}
	}
}
