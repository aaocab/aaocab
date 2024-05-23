<?php

namespace Stub\mmt;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CancelRequest
{

	public $partner_reference_number;
	public $order_reference_number;
	public $cancelled_by;
	public $cancellation_reason;
	public $reason_id;

	public function getModel($model = null)
	{
		if ($this->partner_reference_number > 0)
		{
			$this->reason_id = 4;
			$model = \Booking::model()->findByPk($this->partner_reference_number);
		}
		return $model;
	}

}
