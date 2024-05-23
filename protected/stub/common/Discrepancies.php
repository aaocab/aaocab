<?php

namespace Stub\common;

class Discrepancies
{

	public  $code;
	public $remarks;

	public static function fillData(\BookingTrackLog $model = null, $discrepencyArr = "",$distance="")
	{
		/** @var BookingTrackLog $model */
		if ($model == null)
		{
			$model = new \BookingTrackLog();
		}

		$remarksArr						 = json_encode(array_merge(["Event" => $model->btl_event_type_id], ['Location' => $discrepencyArr->remarks],['Distance'=>$distance]));
		$model->btl_is_discrepancy		 = $model->btl_is_discrepancy + $discrepencyArr->code;
		$model->btl_discrepancy_remarks	 = $remarksArr;
		return $model;
	}


}
