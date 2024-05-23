<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\mmt;

/**
 * Description of UpdatePassengerDetailsResponse
 *
 * @author Subhradip
 */
class UpdatePassengerDetailsResponse
{
	public $success;
	public $error;
	public $code;
	public $link;

	public function setData($success)
	{
		$this->success	= $success;
	}
}
