<?php
namespace Stub\booking;

class CreditResponse
{

	public $bookingId;
	public $message;
	
	/** @var \Stub\common\Fare $fare */
	public $fare;

	/** @var \Stub\common\Balance $balance */
	public $balance;

	public function setData(\Booking $model, $result = null, \BookingInvoice $bkgInvoice)
	{
		if ($model == null)
		{
			return false;
		}
		$this->bookingId = (int) $model->bkg_id;

		$this->fare = new \Stub\common\Fare();
		$this->fare->setInvoiceCreditData($bkgInvoice, $result);

		$this->balance = new \Stub\common\Balance();
		$this->balance->setCreditData($result);

		$this->message = $result->message;
		return $this;
	}

}
