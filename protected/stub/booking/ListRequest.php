<?php

namespace Stub\booking;

class ListRequest
{
	public $sort;

	public function getModel($model = null)
	{
		if ($model == null)
		{
			$model = \Booking::model();
		}
        
		return $model;
	}
}
