<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\mmt;

/**
 * Description of HoldRequest
 *
 * @author Admin
 * @property Fare $fare_details
 */
class HoldRequest extends QuoteRequest
{

	public $refId;
	public $totalAmount;
	public $fare_details;

	public function getModel($model = null, $jsonObj)
	{
		$obj = parent::getModel($model, $jsonObj);

        $obj->bkgInvoice->bkg_total_amount = $this->fare_details->total_fare;
        $obj->bkg_agent_ref_code           = $this->refId;
        
        return $obj;
    }

}
