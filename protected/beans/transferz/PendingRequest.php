<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Beans\transferz;

/**
 * Description of TransferzPendingRequest
 *
 * @author Dev
 */
class PendingRequest
{
	public $id, $preferred, $meetingPointRequired, $expiry, $status, $replacement, $hash, $code, $pickupTime, $vehicleCategory, $pickup, $dropoff, $distance;

	/** @var \Beans\transferz\Journey $journey */
	public $journey;

	/** @var \Beans\transferz\MeetingPoints[] $meetingPoints */
	public $meetingPoints;

	public function setData(\stdClass $model = null)
	{
		if ($model == null)
		{
			$model = new \TransferzOffers();
		}
		$model->trb_trz_id	 = $this->id;
		$model->replacement	 = $this->replacement;
		$model->status		 = $this->status;
		if ($this->journey == null)
		{
			$this->journey = new \Beans\transferz\Journey();
		}
		$model					 = $this->journey->getPendingBookingModel($model);
		$expiryPickupDate		 = new \DateTime($this->expiry, new \DateTimeZone($model->timeZone));
		$model->trb_expiry_date	 = $expiryPickupDate->format('Y-m-d H:i:s');
		$model->trb_quote_data	 = \CJSON::encode($this);
		return $model;
	}
	
}
