<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Beans\transferz;

/**
 * Description of TransferzPendingRequest
 *
 * @author Ankesh
 */
class DeclineResponse
{
	public $reason, $description;

	public function setData()
	{
		$this->reason = "FARE_TOO_LOW";
		$this->description = "";
	}

}
