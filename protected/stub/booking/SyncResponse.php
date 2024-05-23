<?php

namespace Stub\booking;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SyncResponse
{

	public $appId;
	public $bookingId;
	public $bookingReffId;
	public $type;
	public $status;
	public $remarks;
	public $syncStatus; //1:processed;2:uploaded and not processed;3:not uploaded and not processed
	public $syncError;
	

	/** @var BookingTrackLog $eventModel */
	public function setData($response, $eventModel, $reffId = null)
	{
		/** @var \ReturnSet $response */
		$this->bookingId = $eventModel->btl_bkg_id;
		if ($reffId != null)
		{
			$this->bookingReffId = $reffId;
		}

		$this->type			 = $eventModel->btl_event_type_id;
		$this->appId		 = $eventModel->btl_appsync_id;
		$status				 = $response->getStatus();
		$this->status		 = ($status==''?'true':$status);
		$this->remarks		 = ($status) ? $response->getMessage() :  implode(', ', array_column($response->getErrors(), 0));
		$this->syncStatus	 = 1;
		$this->syncError	 = ($status) ? $response->getMessage() : $response->getErrors();
	}

}
