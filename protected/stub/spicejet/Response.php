<?php
namespace Stub\spicejet;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Response
{
	public $code, $error;
	public $response;


	public function setError(\ReturnSet $returnSet)
	{
		$this->code = $returnSet->getErrorCode();
		$message =implode(',', \Filter::getNestedValues($returnSet->getErrors())) ;

		$this->error = $message;
	}

	public function setData($response)
	{
		$this->response = $response;
	}

}

