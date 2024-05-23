<?php

namespace Beans\contact;

class Scq1
{

	public $desc;
	public $isfollowup;

	/** @var Phone $phone */
	public $phone;
	public $code;
	public $scq_id;
	public $scq_type;
	public $ref_id;
	public $transactionId;

	public function getRequest()
	{
		$obj			 = new Scq();
		$obj->desc		 = $this->desc;
		$obj->isfollowup = $this->isfollowup;
		$obj->phone		 = $this->phone;
		$obj->scq_type	 = $this->scq_type;
		$obj->ref_id	 = $this->ref_id;
		return $obj;
	}

	public static function setData($model)
	{

		$obj				 = new Scq();
		$obj->followupId	 = (int) $model->scq_id;
		$obj->queNo			 = $model->scq_queue_no;
		$obj->followupCode	 = $model->scq_unique_code;
		$obj->waittime		 = $model->scq_waittime;
		$obj->active		 = $model->scq_active;
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
		\Filter::parsePhoneNumber($phoneNo->code . $phoneNo->number, $code, $number);
		$this->phone = new Phone();
		$phone		 = \Beans\contact\Phone::setByNumber($code . $number);
		$this->phone = $phone[0];
	}

	public function setObjPhone($objPhoneNumber)
	{
		\Filter::parsePhoneNumber($objPhoneNumber->phn_full_number, $code, $number);
		$this->phone = new Phone();
		$phone		 = \Beans\contact\Phone::setByNumber($code . $number);
		$this->phone = $phone[0];
	}

}
