<?php

namespace Stub\spicejet;

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
	public $partner_name;
	public $vendor_id;
	public $reason_id;
    

    public function getModel($model = null)
    {
		$model  = \Booking::findByOrderNo($this->order_reference_number); 
		return $model;
    }

}
