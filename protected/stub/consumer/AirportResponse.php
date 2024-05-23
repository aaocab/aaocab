<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Stub\consumer;
/**
 * Description of AirportResponse
 *
 * @author Aman
 */
class AirportResponse
{
	public $triptype ;
		
	/** @var \Stub\common\Itinerary[] $routes */
	public $routes;
	
	public function setData($model)
	{
		$this->triptype = (int) $model->bkg_booking_type;
		$routes = $model->bookingRoutes;
        foreach ($routes as $route)
        {
            $itinerary      = new \Stub\common\Itinerary();
            $itinerary->setModelData($route);
            $this->routes[] = $itinerary;
        }
	}
}
