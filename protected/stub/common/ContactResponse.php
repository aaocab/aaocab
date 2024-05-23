<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;

/**
 * Description of ContactResponse
 *
 * @author Suvajit
 */
class ContactResponse extends Person
{
	public $id; //Contact Id
	

	public function setData($data)
	{
		$this->id = $data["ctt_id"];
	}
}
