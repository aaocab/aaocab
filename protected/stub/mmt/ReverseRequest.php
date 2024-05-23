<?php

namespace Stub\mmt;

/**
 * Description of CustomRequest
 *
 * @author Gozo
 * @property VehicleDetails $vehicle_details
 */
class ReverseRequest 
{
    /** @var \Stub\mmt\Location $srcPoint */
    public $source;

    /** @var \Stub\mmt\Location $destPoint */
    public $destination;
    
    /** @var \Stub\mmt\Fare $fare_details */
    public $fare_details;
    
    /** @var \Stub\mmt\VehicleDetails $vehicle_details */
    public $vehicle_details ;
	
    public $trip_type;
	public $start_time;
	public $end_time;
	public $totalAmount;
	public $search_id;
	public $is_instant_search;
	public $order_reference_number;
	public $vendor_id;
	public $partner_name;
	public $distance_booked;
    public $verification_code;
	public $search_tags       = ["EPASS","B2C","PB","B2B","FF"];
	public static $tripTypes = ['ONE_WAY' => '1', 'ROUND_TRIP' => '2']; // , 'LOCAL_RENTAL' => '3'
    public static $cabTypes  = ['hatchback' => '1', 'suv' => '2', 'sedan' => '3'];
    
	
	

	public function getModel($model = null)
	{
		if ($model == null)
        {
            $model = \Booking::getNewInstance();
            \Logger::profile("New Instance Initiated");
        }
        if (!array_key_exists($this->trip_type, self::$tripTypes))
        {
            throw new \Exception("Invalid Trip Type", \ReturnSet::ERROR_INVALID_DATA);
        }
        $tripType                = self::$tripTypes[$this->trip_type];
		$route = $this->getRouteModel();
        $model->bkg_from_city_id = $route->brt_from_city_id;
        $model->bkg_to_city_id   = $route->brt_to_city_id;
		$model->bkg_trip_duration = $route->brt_trip_duration;
		$model->bkg_pickup_address = $route->brt_from_location;
		$model->bkg_pickup_lat  = $route->brt_from_latitude;
		$model->bkg_pickup_long  = $route->brt_from_longitude;
		$model->bkg_drop_address  = $route->brt_to_location;
		$model->bkg_dropup_lat  = $route->brt_to_latitude;
		$model->bkg_dropup_long  = $route->brt_to_longitude;
        $routes[] = $route;
        if ($tripType == 2)
        {
            $obj                   = new \BookingRoute();
            $route                 = $obj->getReturnRoute($routes, $this->end_time);
            $model->bkg_to_city_id = $route->brt_to_city_id;
            $routes[]              = $route;
            \Logger::profile("Return Route Populated");
        }
        $model->bookingRoutes     = $routes;
        $model->bkg_booking_type = $tripType;
		$model->bkg_create_date  = new \CDbExpression('now()');
        $model->bkg_pickup_date  = $this->start_time;
        $model->bkg_return_date = $this->end_time;
		$model->search            = $this->search_id;
		$model->bkg_agent_ref_code = $this->order_reference_number;
		$model->bkg_trip_distance    = $this->distance_booked;
        $model->bkgTrack->bkg_trip_otp = $this->verification_code;
        $model->bkg_agent_id = 18190;
		  
		$model->bkgInvoice = $this->fare_details->populateData($model->bkgInvoice,$model->bkg_pickup_date, $model->bkg_return_date, $model->bkg_agent_id, $model->bkg_booking_type);
        
        $this->vehicle_details->getData($model);
        return $model;
    }
	
	public function getRouteModel(\BookingRoute $model = null)
    {
        if ($model == null)
        {
            $model = new \BookingRoute();
        }

        $model->brt_pickup_datetime = $this->start_time;
        $model->brt_from_location   = $this->source->address;
        $model->brt_from_latitude   = (float) $this->source->latitude;
        $model->brt_from_longitude  = (float) $this->source->longitude;
        $model->brt_from_place_id   = $this->source->place_id;
        if ($this->trip_type == 'ROUND_TRIP')
        {
            $model->brt_return_date_date = $this->end_time;
        }
        $model->brt_to_location  = $this->destination->address;
        $model->brt_to_latitude  = (float) $this->destination->latitude;
        $model->brt_to_longitude = (float) $this->destination->longitude;
        $model->brt_to_place_id  = $this->destination->place_id;
        
        $routeModel = $model->populateCities();

        if ($routeModel)
        {
            $model->brt_trip_duration = $routeModel->rut_estm_time;
        }
		if ($routeModel && $this->trip_type == 'ROUND_TRIP')
        {
            $distance  = max([$this->distance_booked, $routeModel->rut_estm_distance]);
            $model->brt_trip_distance = $distance;
        }
        return $model;
    }

}
