<?php

namespace Stub\driver;



/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class LoginResponse
{

/** @var \Stub\common\Driver $driver */
	public $driver;
	
/** @var \Stub\common\Person $user */
	public $user;

public $bookingId;


	public function setData($drvModel)
	{

		$this->user = new \Stub\common\Person();
		$this->user->setDrvLoginData($drvModel);

		$this->driver = new \Stub\common\Driver();
		$this->driver->setData($drvModel);
	}

}
?>