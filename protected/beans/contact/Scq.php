<?php

namespace Beans\contact;

class Scq
{

	/** @var Phone $phone */
	public $phone;
	public $desc;
	public $queType;
	public $refId;
	public $refType;   // [1 => 'Booking', 2 => 'Trip', 3 => 'Transaction'];
	public $entityId;
	public $entityType;
//old
	public $scq_type;
	public $ref_id;
	public $transactionId;
//
	//Response
	public $id;
	public $queNo;
	public $queCode;
	public $waitTime;
	public $message;
	public $active;

	public function getRequest()
	{
		$obj			 = new Scq();
		$obj->desc		 = $this->desc;
		$obj->phone		 = $this->phone;
		$obj->scq_type	 = $this->scq_type . $this->scq_id;
		$obj->ref_id	 = $this->ref_id;

		$obj->queType	 = $this->queType . $this->scq_type . $this->scq_id;
		$obj->refId		 = $this->refId . $this->ref_id;
		return $obj;
	}

	/**
	 * @param ServiceCallQueue $model
	 * @param string $message
	 * @return \Beans\contact\Scq
	 * @throws \Exception
	 */
	public static function setResponse($model, $message = '')
	{
		if (!$model)
		{
			throw new \Exception("Error in service call queue. ", \ReturnSet::ERROR_NO_RECORDS_FOUND);
		}
		$obj				 = new Scq();
		$obj->id			 = (int) $model->scq_id;
		$obj->queNo			 = (int) $model->scq_queue_no;
		$obj->followupCode	 = $model->scq_unique_code;
		$obj->waittime		 = $model->scq_waittime;
		$obj->active		 = $model->scq_active;
		$obj->message		 = ($message != '') ? $message : null;
		return $obj;
	}

	/**
	 * @param Array $data
	 * @param string $message
	 * @return \Beans\contact\Scq
	 * @throws \Exception
	 */
	public static function setResponseData($data, $message = '')
	{
		if (count($data) == 0)
		{
			throw new \Exception("Error in service call queue. ", \ReturnSet::ERROR_NO_RECORDS_FOUND);
		}
		$obj				 = new Scq();
		$obj->id			 = (int) ($data['followupId'] > 0) ? $data['followupId'] : null;
		$obj->queNo			 = (int) ($data['queNo'] != null) ? $data['queNo'] : null;
		$obj->followupCode	 = $data['followupCode'];
		$obj->waitTime		 = $data['waitTime'];
		$obj->active		 = $data['active'];
		$obj->message		 = ($message != '') ? $message : null;
		return $obj;
	}

	public static function setPenaltyDisputeData()
	{

		$obj				 = new Scq();
		$obj->desc			 = $this->desc;
		$obj->transactionId	 = $this->transactionId;
		return $obj;
	}

	public function setPhone($phoneNo)
	{
//		\Filter::parsePhoneNumber($phoneNo->code . $phoneNo->number, $code, $number);
//		$this->phone = new Phone();
		$phone		 = \Beans\contact\Phone::setByNumber($phoneNo->code . $phoneNo->number);
		$this->phone = $phone[0];
	}

	public function setObjPhone($objPhoneNumber)
	{
//		\Filter::parsePhoneNumber($objPhoneNumber->phn_full_number, $code, $number);
//		$this->phone = new Phone();
		$phone		 = \Beans\contact\Phone::setByNumber($objPhoneNumber->phn_full_number);
		$this->phone = $phone[0];
	}

}
