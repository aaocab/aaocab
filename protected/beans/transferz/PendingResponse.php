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
class PendingResponse
{
	public $errors, $message;

	public function setData(\stdClass $model = null)
	{
		$returnSet		 = new ReturnSet();
		$this->success	 = true;
		$this->error	 = NULL;
		$this->code		 = NULL;

		return $returnSet;
	}
}
