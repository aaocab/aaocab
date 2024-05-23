<?php

namespace Stub\spicejet;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UpdateRequest
{
    public $key;
	public $vendor_id;
	public $partner_name;
	public $order_reference_number;
	public $type;
	public $destination_name;
	public $destination_city;
	public $destination_latitude;
	public $destination_longitude;
    

    public function getModel($model = null)
    {
        $model  = \Booking::findByOrderNo($this->order_reference_number); 
		$route = $this->getRouteModel();

		$routes[] = $route;
		$model->bookingRoutes	 = $routes;
		$model->bkg_drop_address = $this->destination_name. "". $this->destination_city;
		$model->bkg_dropup_lat   = $this->destination_latitude;
		$model->bkg_dropup_long  = $this->destination_longitude;
        return $model;
    }

	public function getRouteModel(\BookingRoute $model = null)
	{
		if ($model == null)
		{
			$model = new \BookingRoute();
		}
		$model->brt_from_latitude = (float) $this->source_latitude;
		$model->brt_from_longitude = (float) $this->source_longitude;
		$model->brt_to_location = $this->destination_name. ', '. $this->destination_city;
		$model->brt_to_latitude = (float) $this->destination_latitude;
		$model->brt_to_longitude = (float) $this->destination_longitude;
		return $model;
	}

}
