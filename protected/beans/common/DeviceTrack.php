<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Beans\common;

/**
 * Description of DeviceTrack
 *
 * @author Dev
 * 
 * @property \Beans\common\DeviceInfo $device
 * @property \Beans\common\Coordinates $coordinates
 */
class DeviceTrack
{

	/** @var \Beans\common\DeviceInfo $device */
	public $device;

	/** @var \Beans\common\Coordinates $coordinates */
	public $coordinates;


	//put your code here
	
	public function getDeviceData(\BookingTrackLog $model = null, $data)
	{
        /** @var BookingTrackLog $model */
        if ($model == null)
        {
            $model = new \BookingTrackLog();
        }

        return $model->btl_device_info = \CJSON::encode($data);
    }

	public function setData($data)
	{
		$this->coordinates->lat = round($data['bkg_pickup_lat'], 4);
		$this->coordinates->lng = round($data['bkg_pickup_long'], 4);
		return $this;
	}
}
