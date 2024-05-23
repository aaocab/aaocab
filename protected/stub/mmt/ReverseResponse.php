<?php

namespace Stub\mmt;

/**
 * Description of ReverseResponse
 *
 * @author Gozo
 */
class ReverseResponse
{

	public $response;
	public $reference_number;
	public $verification_code;
	public $success;
	public $code;
	public $error;
	

	/** @param \Booking $model */
	public function setData($model)
	{
		$this->response->success				 = true;
        $this->error = NULL;
		$this->code = NULL;
	}
}
