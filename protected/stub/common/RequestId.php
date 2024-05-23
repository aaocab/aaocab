<?php

namespace Stub\common;

class RequestId
{
	/** @var \Stub\common\ContactVerification $contactVerificationSession */
	public $contactVerification;

	public $requestId;

	public function setData($sessArr, $requestId)
	{
		$this->requestId = $requestId;
		$this->requestId = new \Stub\common\ContactVerification();
		$this->requestId->setData($sessArr);
		
	}

}
