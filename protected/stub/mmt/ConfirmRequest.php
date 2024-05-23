<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\mmt;

/**
 * Description of Confirm Request
 *
 * @author Admin
 */

class ConfirmRequest
{
	public $partner_reference_number;
	public $order_reference_number;
	public $total_fare;
	public $booking_gst;
	public $amount_to_be_collected;
	
	/** @var \Stub\mmt\Person $passenger */
	public $passenger;

	public function getModel($model = null)
	{
		if($this->partner_reference_number > 0)
		{
			$model = \Booking::model()->findByPk($this->partner_reference_number);
			$model->bkg_agent_ref_code	 = $this->order_reference_number;
			
		}

		return $model;
	}
}