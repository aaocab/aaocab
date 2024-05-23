<?php

namespace Stub\booking;

class ListResponse
{
	public $bookings = [];
	public function getData(\CDbDataReader $result)
	{

		foreach ($result as $res)
		{
			$booking			 = new \Stub\common\Booking();
			$this->bookings[]	 = $booking->setModelData($res);
		}      
		return $this;
	}
}
