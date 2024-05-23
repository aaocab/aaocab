<?php

namespace Stub\mmt;

class ExtraCharge
{

	public $amount, $is_included_in_base_fare, $is_included_in_grand_total;
	public $applicable_time_from, $applicable_time_till;
	public $free_waiting_time, $is_applicable;

	public static function setCharges($flag, $amount, $tripType=null, $airportEntryFeeFlag = null)
	{

		$obj		 = new ExtraCharge();
		$obj->amount = ($amount ? (int) $amount : 0);

		if ($tripType == 12)
        {
			if ($airportEntryFeeFlag == 1 && $flag == 1)
			{
				$obj->is_included_in_base_fare	 = false;
				$obj->is_included_in_grand_total = true;
			}
			else
			{
				$obj->is_included_in_base_fare	 = true;
				$obj->is_included_in_grand_total = true;
			}
		}
        else
        {
            if ($amount == 0 && $flag == 1)
            {
                $obj->is_included_in_base_fare = true;
            }
            else
            {
                $obj->is_included_in_base_fare = false;
            }
            $obj->is_included_in_grand_total = (bool) $flag;
        }
		return $obj;
	}

	public function setNightCharges($flag, $amount, $from, $to, $tripType=null)
	{
		$obj						 = self::setCharges($flag, $amount, $tripType);
		$obj->applicable_time_from	 = ((int) $from > 22 ? 22 : (int) $from);
		$obj->applicable_time_till	 = ((int) $to < 6 ? 6 : (int) $to);
		return $obj;
	}

	public function setWaitingCharges($flag, $amount, $time)
	{
		$obj					 = self::setCharges($flag, $amount);
		$obj->free_waiting_time	 = (int) $time;
		return $obj;
	}

    public function getNightCharges($model, $from, $to)
	{
		$model->bkg_night_pickup_included = (int) $this->is_included_in_grand_total;
		
		$pickupTimeInHrs = date('H', strtotime($from));
        $pickupTimeInMin = date('i', strtotime($from));
        $pickupTime      = $pickupTimeInMin > 0 ? ($pickupTimeInHrs + 1) : $pickupTimeInHrs;
        if(($pickupTime >= 22 || $pickupTime <= 6))
        {
            $model->bkg_night_pickup_included = 1;
        }
		
		$dropoffTimeInHrs = date('H', strtotime($to));
        $dropoffTimeInMin = date('i', strtotime($to));
        $dropoffTime      = $dropoffTimeInMin > 0 ? ($dropoffTimeInHrs + 1) : $dropoffTimeInHrs;
        if(($dropoffTime >= 22 || $dropoffTime <= 6))
        {
            $model->bkg_night_drop_included = 1;
        }
		if(($dropoffTime >= 22 || $dropoffTime <= 6) && ($pickupTime >= 22 || $pickupTime <= 6))
        {
			$model->bkg_night_pickup_included = 1;
            $model->bkg_night_drop_included = 1;
        }
        $model->bkg_driver_allowance_amount = $this->amount;
		return $model;
	}

	public function getTollCharges($model)
	{
		$model->bkg_is_toll_tax_included = (int) $this->is_included_in_grand_total;
		$model->bkg_toll_tax			 = $this->amount;
		return $model;
	}

	public function getStateTaxCharges($model)
	{
		$model->bkg_is_state_tax_included	 = (int) $this->is_included_in_grand_total;
		$model->bkg_state_tax				 = $this->amount;
		return $model;
	}

	public function getParkingCharges($model)
	{
		$model->bkg_is_parking_included	 = (int) $this->is_included_in_grand_total;
		$model->bkg_parking_charge		 = $this->amount;
		return $model;
	}

	public function getWaitingCharges($model)
	{
		$model->bkg_trip_waiting_charge = (int) $this->is_included_in_grand_total;
		return $model;
	}

    public function getAirportEntryCharges($model)
	{
	     $model->bkg_is_airport_fee_included = (int) $this->is_included_in_grand_total;
         $model->bkg_airport_entry_fee       = $this->amount; 
	}

	public function getToll($model)
	{
		$this->amount = $model->bkgInvoice->bkg_extra_toll_tax;
	}

	public function getStateTax($model)
	{
		$this->amount = $model->bkgInvoice->bkg_extra_state_tax;
	}

	public function getParking($model)
	{
		$this->amount = $model->bkgInvoice->bkg_parking_charge;
	}

	public function getWaiting($model)
	{
		$this->amount = $model->bkgInvoice->bkg_trip_waiting_charge;
	}

}
