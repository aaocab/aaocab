<?php

namespace Beans\booking;

class Offer
{

	public $id;
	public $minArrivalDuration;
	public $currentCabDistance;
	public $validDuration;
	public $createTime;
	public $status;

	/** @var \Beans\Booking $booking */
	public $booking;

	/** @var \Beans\vendor $vendor */
	public $vendor;

	/** @var \Beans\Driver $driver */
	public $driver;

	/** @var \Beans\common\Cab $cab */
	public $cab;

	/** @var \Beans\booking\Fare $fare */
	public $fare;

	/**
	 * @var \BookingVendorRequest $bidModel
	 * @return boolean
	 */
	public static function setData($bidModel)
	{
		if (!$bidModel)
		{
			return false;
		}
		$bidModel->refresh();
		$remarks				 = json_decode($bidModel->bvr_special_remarks);
		$vendorAmount			 = $bidModel['bvr_bid_amount'];
		$obj					 = new \Beans\booking\Offer();
		$obj->id				 = (int) $bidModel->bvr_id;
		$duration				 = (int) \Filter::getTimeDiffinSeconds($remarks->reachingAtTime);
		$obj->minArrivalDuration = $duration;
		$bvrAcceptedAt			 = $bidModel->bvr_accepted_at;
		$obj->validDuration		 = (int) (300 + \Filter::getTimeDiffinSeconds($bvrAcceptedAt));
		$obj->vendor			 = new \Beans\Vendor();
		$obj->vendor			 = \Beans\Vendor::setDataForOffer($bidModel->bvr_vendor_id);

		$obj->driver = new \Beans\Driver();
		if ($remarks->driverId > 0)
		{
			$obj->driver = \Beans\Driver::setDataForOffer($remarks->driverId, $remarks->driverMobile);
		}

		$obj->cab	 = new \Beans\common\Cab();
		$cabId		 = $remarks->cabId;
		if ($cabId > 0)
		{
			$cabData	 = \Vehicles::getDetailbyid($cabId);
			$obj->cab	 = \Beans\common\Cab::setDataForOffer($cabData);
		}

		$obj->booking	 = new \Beans\Booking();
		$model			 = \Booking::model()->findByPk($bidModel->bvr_booking_id);
		if ($model->bkg_id > 0)
		{
			$obj->booking->id	 = (int) $model->bkg_id;
			$obj->booking->code	 = $model->bkg_booking_id;
		}
		if ($bidModel->bvr_booking_id > 0 && $vendorAmount > 0)
		{
			$model		 = \BookingSub::getModelForGNowFromVendorAmount($bidModel->bvr_booking_id, $vendorAmount);
			$totalAmount = $model->bkgInvoice->bkg_total_amount;
			$obj->fare	 = new \Beans\booking\Fare();
			$obj->fare	 = \Beans\booking\Fare::setDataForOffer($totalAmount, $vendorAmount);
		}
		return \Filter::removeNull($obj);
	}

	/**
	 * @param array $dataSet
	 * @return array
	 */
	public static function setDataSet($dataSet)
	{

		$returnArr = [];
		foreach ($dataSet as $data)
		{
			$bvrId		 = (int) $data['bvr_id'];
			/* @var $model BookingVendorRequest */
			$model	 = \BookingVendorRequest::model()->findByPk($bvrId);
			$returnArr[]	 = \Beans\booking\Offer::setData($model);
		}
		return $returnArr;
	}

}

?>