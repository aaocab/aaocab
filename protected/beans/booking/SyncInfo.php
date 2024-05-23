<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SyncBooking
 *
 * @author Dev
 * 
 * @property integer $id
 * @property string $createDate
 * @property string $syncDate
 * @property \Beans\common\DeviceInfo $deviceInfo
 * @property string $errors
 */

namespace Beans\booking;

/**
 * @deprecated
 */
class SyncInfo
{

	public $id;
	public $createDate;
	public $syncDate;

	/** @var \Beans\common\DeviceInfo $deviceInfo */
	public $deviceInfo;
	public $errors;
	public $status;
	public $remarks;

	public static function setData($data)
	{
		$obj			 = new SyncInfo();
		$obj->id		 = $data->id;
		$obj->syncDate	 = $data->syncDate;
		return $obj;
	}

}
