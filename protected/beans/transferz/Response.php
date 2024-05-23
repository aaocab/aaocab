<?php
namespace Beans\transferz;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Response
{
	public $code, $error, $link;
	public $response;


	public function setError(\ReturnSet $returnSet)
	{
		$this->code = $returnSet->getErrorCode();
		
		if(!in_array($this->code, [\ReturnSet::ERROR_INVALID_DATA, \ReturnSet::ERROR_VALIDATION, \ReturnSet::ERROR_NO_RECORDS_FOUND, \ReturnSet::ERROR_UNAUTHORISED]))
		{
			$message = "Unable to process request";
		}
		else
		{
			$message =implode(',', \Filter::getNestedValues($returnSet->getErrors())) ;		
		}

		$this->error = $message;
	}

	public function setData($response)
	{
		$this->response = $response;
	}

}

