<?php

namespace Stub\common;

class Payload
{

	public $bkgId;
	public $tripId;
	public $data;
	public $isGozoNow;

	/** @var \Stub\common\Booking $booking */
	public $booking;
	public $EventCode;

	public function setGNowNotifyData(\Booking $model, $event = null)
	{
		/** @var \Booking $model */
		$this->tripId	 = (int) $model->bkg_bcb_id;
		$this->isGozoNow = ($model->bkgPref->bkg_is_gozonow == 1) ? 1 : 0;
		$this->data		 = $this->setBookingData($model, $event);
		$this->EventCode = $event;
		return ['tripId' => $this->tripId, 'isGozoNow' => $this->isGozoNow, 'data' => $this->data, 'EventCode' => $this->EventCode];
	}

	public function setGNowRejectedNotifyData(\Booking $model, $event = null)
	{
		/** @var \Booking $model */
		$this->tripId	 = (int) $model->bkg_bcb_id;
		$this->isGozoNow = ($model->bkgPref->bkg_is_gozonow == 1) ? 1 : 0;
		$this->EventCode = $event;
		return ['tripId' => $this->tripId, 'isGozoNow' => $this->isGozoNow, 'data' => [], 'EventCode' => $this->EventCode];
	}

	public function setBookingData(\Booking $model, $event = null)
	{
		$data = new \Stub\common\Booking();
		$data->setNotificationData($model, $event);
		return $data;
	}

	public function setVendorNotifyData(\Booking $model, $event = null)
	{
		/** @var \Booking $model */
		$this->tripId	 = (int) $model->bkg_bcb_id;
		$this->isGozoNow = ($model->bkgPref->bkg_is_gozonow == 1) ? 1 : 0;
		$this->data		 = $this->setBookingData($model, $event);
		$this->EventCode = $event;

		return ['tripId' => $this->tripId, 'isGozoNow' => $this->isGozoNow, 'data' => $this->data, 'EventCode' => $this->EventCode];
	}

	public function setCustomerNotifyData(\Booking $model, $event = null)
	{
		$this->tripId	 = (int) $model->bkg_bcb_id;
		$this->bkgId	 =  $model->bkg_id;
		$this->data		 = $this->setBookingData($model, $event);
		$this->EventCode = $event;
		return ['tripId' => $this->tripId, 'bkgId' => $this->bkgId, 'EventCode' => $this->EventCode, 'data' => $this->data];
	}

}
