<?php


namespace Stub\mmt;

/**
 * Description of Location
 *
 * @author Admin
 */
class Location 
{

	public $type, $latitude, $longitude, $place_id, $address;

	/** @var \Stub\common\Coordinates $coordinates */
	public $coordinates;
	
	/**
	 * @return \Cities */
	public function getCityModel()
	{
		$model = \Cities::model()->getCtyIdWithBound($this->latitude, $this->longitude, 0);
		return $model;
	}
    
    public function setData($model, $eventId)
    {
        $triplogDetails	 = \BookingTrackLog::model()->getdetailByEvent($model->btk_bkg_id, $eventId);
        $coordinate		 = explode(',', $triplogDetails['btl_coordinates']);
        
        $this->type = "Point";
		
		if ($coordinate[0] != null && $coordinate[1] != null)
		{
			$this->coordinates->latitude	 = (float)$coordinate[0];
		    $this->coordinates->longitude	 = (float)$coordinate[1];
		}
    }

}
