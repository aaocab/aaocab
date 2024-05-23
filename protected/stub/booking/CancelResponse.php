<?php

namespace Stub\booking;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CancelResponse
{

	public $bookingId;
    public $orderRefId;
	public $message;
	public $cancellationCharge;
	public $refundAmount;
	public $reason;

	public $reference_number;
	public $order_reference_number;
	public $cancellation_reason;
	public $partner;
	public $cancelled_by;

	public function setData($data)
	{

		$this->bookingId			 = $data['bookingId'];
		$this->cancellationCharge	 = (int) $data['cancellationCharge'];
		$this->refundAmount			 = (int) $data['refundAmount'];
		$this->message				 = $data['message'];
		$this->errors				 = $data["errors"];
	}

	public function setPushData($data)
	{

		$this->bookingId			 = $data->bookingId;
        $this->orderRefId            = $data->orderRefId;
		$this->cancellationCharge	 = (int) $data->cancellationCharge;
		$this->refundAmount			 = (int) $data->refundAmount;
		$this->message				 = $data->cancellationReason;
		$this->reason				 = $data->cancelReasonDesc;
	}
	
	public function setSpicejetPushData($data)
	{
		$this->reference_number = $data->bookingId;
		$this->order_reference_number = $data->orderRefId;
		$this->cancellation_reason = $data->cancellationReason;
		$this->partner = 'GOZO CABS';
		$this->cancelled_by = 'PARTNER';
	}

}
