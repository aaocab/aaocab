<?php

namespace Stub\booking;

class SessionRequest
{

	/** @var \Stub\common\ContactVerification $contactVerificationSession */
	public $contactVerification;
	
	/** @var \stub\common\PageRequest $page */
	public $page;

	public function setData($sessArr, $requestId, $pageID)
	{
//		$cttVerificationSess		 = new \Stub\common\ContactVerificationSession();
//		$cttVerificationSess->setData($sessArr);
//		$this->contactVerification	 = $cttVerificationSess;
		$page = new \Stub\common\PageRequest();
		$page->setData($sessArr, $requestId, $pageID);
	}

	public function getData($sessValue, $page, $requestId)
	{
		$list									 = [];
		$this->page								 = $sessValue[$page];
		$this->requestId						 = $sessValue[$page][$requestId];
		$contactVerification					 = new \Stub\common\ContactVerification();
		$contactVerification->setData($this->requestId['contactVerification']);
		$this->contactVerification = $contactVerification;
	}

}
