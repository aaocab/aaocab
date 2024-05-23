<?php

namespace Stub\mmt;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UnavailableRequest
{
	public $order_reference_number;
    public $vendor_id;
    public $partner_name;

	public function getModel($model = null)
	{
		$model = \Booking::findByOrderNo($this->order_reference_number);
		return $model;
	}
}
