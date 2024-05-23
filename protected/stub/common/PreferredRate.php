<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;

class PreferredRate
{

	//put your code here

	public $rateType;
	public $source;
	public $destination;
	public $cabCategory;
	public $tripType;
	public $serviceTier;
	public $amount;
	public $validDate;

	public function getModel($model = null)
	{

		/** @var $model PaymentGateway */
		if ($model == null)
		{
			$model = new \PreferredRate();
		}

		$model->rate_type = $this->rateType;

		$model->from_location_id = $this->source->code;

		$model->to_location_id = $this->destination->code;

		$model->cab_type = $this->cabCategory->id;

		$model->booking_type = $this->tripType->id;

		$model->service_tier = $this->serviceTier;

		$model->vendor_amount	 = $this->amount;
		$model->valid_until		 = $this->validDate;

		return $model;
	}

	
	public function setModel($data)
	{
		foreach ($data as $row)
		{
			$obj				 = new \Stub\common\PreferredRate();
			$obj->fillData($row);
			$this->dataList[]	 = $obj;
		}
	}

	public function fillData($row)
	{
		
		$this->cabCategory->type = $row['vehicle_lavel'];
		$this->source->name		 = $row['from_location'];
		$this->destination->name = $row['to_location'];
		$this->amount			 = (double)$row['vendor_amount'];
		$this->validDate		 = $row['valid_until'];
		$this->serviceTier		 = (int)$row['service_tier'];
		$this->tripType->name	 = \Booking::model()->booking_types[$row['booking_type']];
	}

}
