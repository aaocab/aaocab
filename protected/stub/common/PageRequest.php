<?php

namespace Stub\common;

/**
 * @property ContactVerification[] $contactVerifications
 * @property \Stub\booking\CreateRequest[] $createRequests
 */
class PageRequest
{

	/** @var \Stub\common\ContactVerification[] $contactVerifications */
	public $contactVerifications = [];

	/** @var \Stub\booking\CreateRequest[] $createRequests */
	public $createRequests = null;

	/** @var \Stub\common\RequestId $requestId */
	public $page;

	/** @var \Stub\booking\QuoteResponse $createQuote */
	public $createQuote = null;

//	public function setData($sessArr, $requestId, $pageID)
//	{
//		$this->page		 = $pageID;
//		$this->page		 = new RequestId();
//		$this->page->setData($sessArr, $requestId);
//	}

	public static function generateRequestId()
	{
		$id = md5(time() . "." . rand(10001, 99999));
		return $id;
	}

	public function hasRequest($requestId)
	{
		return isset($this->createRequests[$requestId]);
	}

	/** @return \Stub\booking\CreateRequest */
	public function addRequest()
	{
		if ($this->createRequests == null)
		{
			$this->createRequests = new \Stub\booking\CreateRequest();
		}
		return $this->createRequests;
	}

	public function encryptRequestData($requestId)
	{
		$obj = $this->addRequest($requestId);
	}

	public function clearRequest($requestId)
	{
		if ($requestId == null)
		{
			$this->createRequests = [];
		}
		else
		{
			unset($this->createRequests[$requestId]);
		}
		//	$this->updateSession();
	}

	/** @return \Stub\booking\QuoteResponse */
	public function addQuote()
	{
		if ($this->createQuote == null)
		{
			$this->createQuote = new \Stub\booking\QuoteResponse();
		}
		return $this->createQuote;
	}

	/**
	 * @param \Stub\booking\QuoteResponse  $quote
	 */
	public function setQuote($requestId, $quote)
	{
		$this->createQuote = $quote;
	}

	public function clearQuote($requestId)
	{
		if ($requestId == null)
		{
			$this->createQuote = null;
		}
//		else
//		{
//			unset($this->createQuote[$requestId]);
//		}
		//	$this->updateSession();
	}

	public function getContactObject()
	{
		return $this->contactVerifications[count($this->contactVerifications) - 1];
	}

	/** @return ContactVerification */
	public function getContact($type, $value)
	{
		$objCttVerify = null;
		foreach ($this->contactVerifications as $contactVerification)
		{
			if ($contactVerification->type == $type && $contactVerification->value == $value)
			{
				$objCttVerify = $contactVerification;
				break;
			}
		}

		if ($objCttVerify == null)
		{
			$objCttVerify					 = ContactVerification::create($type, $value);
			$this->contactVerifications[]	 = $objCttVerify;
		}
		return $objCttVerify;
	}

	public function clearContact($type = null, $value = null)
	{
		foreach ($this->contactVerifications as $i => $contactVerification)
		{
			if ($value == null || ($contactVerification->type == $type && $contactVerification->value == $value))
			{
				unset($this->contactVerifications[$i]);
			}
		}
	}

	/** @return static */
	public static function getInstance()
	{
		$page = \Yii::app()->session['page'];
		if ($page == null)
		{
			$obj = new PageRequest();
			goto end;
		}

		$jsonMapper	 = new \JsonMapper();
		$jsonObj	 = \CJSON::decode($page, false);
		$jsonObj	 = \Filter::removeNull($jsonObj);

		/** @var SpaceFile $obj */
		$obj = $jsonMapper->map($jsonObj, new PageRequest());

		end:
		return $obj;
	}

	/** @return static */
	public static function createInstance($encryptedData)
	{
		if ($encryptedData == null)
		{
			$obj = new PageRequest();
			goto end;
		}

		$data = \Filter::decrypt($encryptedData);

		$jsonMapper	 = new \JsonMapper();
		$jsonObj	 = \CJSON::decode($data, false);
		$jsonObj	 = \Filter::removeNull($jsonObj);

		/** @var SpaceFile $obj */
		$obj = $jsonMapper->map($jsonObj, new PageRequest());

		end:
		return $obj;
	}

	public function updateSession()
	{
		//	\Yii::app()->session['page'] = \Filter::removeNull(json_encode($this));
	}

	public function getEncrptedData()
	{
		$data = \Filter::removeNull(json_encode($this));
		return \Filter::encrypt($data);
	}

	/** @return ContactVerification */
	public function addBooking($routeJson)
	{
		$jsonMapper	 = new \JsonMapper();
		$jsonObj	 = \CJSON::decode($routeJson, false);

		/** @var $obj Stub\booking\CreateRequest */
		$obj = $jsonMapper->map($jsonObj, new \Stub\booking\CreateRequest());

		/** @var Booking $model */
		$obj->getModel();
	}

}
