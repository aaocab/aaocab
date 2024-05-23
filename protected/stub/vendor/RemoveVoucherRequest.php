<?php

namespace Stub\vendor;

class RemoveVoucherRequest
{
	public $appId;
	public $bookingId;
	public $status;

	/** @var \Stub\common\Document $odometer */
	public $odometer;

	/**
	 * @param \BookingPayDocs $model
	 * @param \$model
	 */
	public function getModel($model = null)
	{
		$model					 = $this->init();
		$paydocs				 = new \BookingPayDocs();
		$paydocs->bpay_bkg_id	 = (int) $this->bookingId;
		$paydocs->bpay_checksum	 = $this->odometer->checksum;
		$paydocs->bpay_status	 = $this->status;
		$model->payDocModel		 = $paydocs;
		return $model;
	}

	/**
	 * This function is used for initializing the default model
	 * @return \BookingTrackLog
	 */
	public function init()
	{
		$model					 = new \BookingTrackLog();
		$model->btl_appsync_id	 = $this->appId;
		return $model;
	}

}
