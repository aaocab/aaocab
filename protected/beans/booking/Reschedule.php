<?php

namespace Beans\booking;

class Reschedule extends \Beans\transaction\Payment
{

	public $pickUpDate;
	public $pickUpTime;
	public $charge;
	public $message;
	public $cancelDesc;
	public $cancelCode;
	public $minPaymentReq;
	public $minPaymentDue;
	public $refundFromExisting;
	public $isConfirm;

	/** @var \Beans\transaction\AdvanceSlabs $appliedAdvanceSlab */
	public $appliedAdvanceSlab;

	/** @var \Beans\Booking $booking */
	public $booking;

	/** @var \Stub\common\Fare $fare */
	public $fare;

	/**
	 * 
	 * @param JSON $obj
	 * @param integer $charge
	 * @param array $params
	 * @return boolean|\Beans\booking\Reschedule
	 */
	public static function getData($obj, $charge = null, $params = [])
	{
		$id		 = $obj->booking->id;
		$model	 = \Booking::model()->findByPk($id);
		if (!$model)
		{
			return false;
		}
		$resObj				 = new Reschedule();
		$resObj->pickUpDate	 = $obj->pickUpDate;
		$resObj->pickUpTime	 = $obj->pickUpTime;

		$resObj->cancelDesc			 = $params['cancelDesc'];
		$resObj->cancelCode			 = $params['cancelCode'];
		$resObj->minPaymentReq		 = $params['minPaymentReq'];
		$resObj->minPaymentDue		 = $params['minPaymentDue'];
		$resObj->refundFromExisting	 = $params['refundFromExisting'];
		$resObj->totalAmount		 = $params['totalAmount'];
		$resObj->charge				 = $charge;
		if ($charge > 0)
		{
			$resObj->message = "*reschedule/cancellation charge of " . \Filter::moneyFormatter($charge) . " will be applied in this booking and rest of the advance will be transferred to the new booking";
		}
		return $resObj;
	}

	/**
	 * 
	 * @param JSON $obj
	 * @param \BookingInvoice $model
	 * @param Array $params
	 * @return \Beans\booking\Reschedule
	 */
	public static function getModelData($obj, \BookingInvoice $model, $params = [])
	{
		$slabObj			 = null;
		$resObj				 = new Reschedule();
		$resObj->refId		 = (int) ($model->biv_bkg_id > 0) ? $model->biv_bkg_id : $params['refId'];
		$resObj->pickUpDate	 = $obj->pickUpDate;
		$resObj->pickUpTime	 = $obj->pickUpTime;

		$resObj->cancelDesc			 = $params['cancelDesc'];
		$resObj->cancelCode			 = $params['cancelCode'];
		$resObj->minPaymentReq		 = $params['minPaymentReq'];
		$resObj->minPaymentDue		 = $params['minPaymentDue'];
		$resObj->refundFromExisting	 = $params['refundFromExisting'];
		$resObj->totalAmount		 = $params['totalAmount'];
		if ($params['minPaymentDue'] > 0)
		{
			$slabObj			 = new \Beans\transaction\AdvanceSlabs();
			$slabObj->isSelected = 1;
			if ($params['minPaymentDue'] > 0)
			{
				$slabObj->value = $params['minPaymentDue'];
			}
			$slabObj->percentage = 25;
			$resObj->fare = new \Stub\common\Fare();
			$resObj->fare->setWalletData($model, $slabObj);
		}

		return $resObj;
	}

	/**
	 * 
	 * @param integer $obj
	 * @return boolean|model
	 */
	public static function setBookingData($obj)
	{
		$id		 = $obj->booking->id;
		$model	 = \Booking::model()->findByPk($id);
		if (!$model)
		{
			return false;
		}
		return $model;
	}

}
