<?php

namespace Beans\common;

/*
 * @property integer $wallet
 * @property \Beans\transaction\AdvanceSlabs[] $advanceSlabs
 */

class WalletPayment extends \Beans\transaction\Payment
{

	public $wallet;
	public $bookingId;
	public $statusCode;
	public $isCash;

	/** @var \Beans\transaction\AdvanceSlabs $appliedAdvanceSlab */
	public $appliedAdvanceSlab;

	/** @var \Beans\common\Promotions $promoDetails */
	public $promoDetails;

	/** @var \Stub\common\Fare $fare */
	public $fare;

	/**
	 * @param integer $referenceId
	 * @param integer $paymentType
	 * @param integer $method
	 * @param integer $amount
	 * @param \BookingUser $modelUser
	 * @param \BookingInvoice $model
	 * @return boolean|\Beans\common\WalletPayment
	 */
	public function setData($referenceId, $paymentType = 1, $method = 21, $amount, \BookingUser $modelUser = null, \BookingInvoice $model = null)
	{
		if ($referenceId == null || $amount == 0 || $model->biv_id == null)
		{
			return false;
		}
		$bkgModel			 = $model->bivBkg;
		$result				 = $bkgModel->getBookingCodeStatus();
		$obj				 = new WalletPayment();
		$obj->refId			 = $referenceId;
		$obj->paymentType	 = $paymentType;
		$obj->statusCode	 = (int) $result['code'];
		$obj->method		 = $method;
		$obj->amount		 = $amount;
		if ($modelUser->bkg_user_id > 0)
		{
			$obj->billing	 = new \Beans\common\BillingDetails();
			$obj->billing	 = \Beans\common\BillingDetails::setData($modelUser);
			//$obj->billing	 = \Beans\contact\Person::setTravellerInfoByModel($modelUser);
		}
		if ($model->bkg_discount_amount > 0)
		{
			$obj->promoDetails	 = new \Beans\common\Promotions();
			$obj->promoDetails	 = \Beans\common\Promotions::populateData($model);
		}
		return $obj;
	}

	/**
	 * @param \BookingInvoice $model
	 * @param integer $isSelected
	 * @param integer $selectPercentage
	 * @param integer $walletUsed
	 * @return boolean|\Beans\common\WalletPayment
	 */
	public function setModelData(\BookingInvoice $model, $isSelected = 1, $selectPercentage = 25, $walletUsed = 0)
	{
		if ($model == null)
		{
			return false;
		}

		$bkgModel			 = $model->bivBkg;
		$result				 = $bkgModel->getBookingCodeStatus();
		$obj				 = new WalletPayment();
		$obj->bookingId		 = (int) $model->biv_bkg_id;
		$obj->refId			 = (int) $model->biv_bkg_id;
		$obj->statusCode	 = (int) $result['code'];
		$slabObj			 = new \Beans\transaction\AdvanceSlabs();
		$slabObj->isSelected = $isSelected;
		$slabObj->percentage = $selectPercentage;

		$obj->fare = new \Stub\common\Fare();
		$obj->fare->setWalletData($model, $slabObj, $walletUsed);

		$obj->wallet = $walletUsed;
		return $obj;
	}

	/**
	 * 
	 * @param \BookingInvoice $model
	 * @param integer $isCash
	 * @return \Beans\common\WalletPayment
	 */
	public static function setDataForCash(\BookingInvoice $model, $isCash = 0)
	{
		$bkgModel		 = $model->bivBkg;
		$result			 = $bkgModel->getBookingCodeStatus();
		$obj			 = new WalletPayment();
		$obj->bookingId	 = (int) $model->biv_bkg_id;
		$obj->statusCode = (int) $result['code'];
		return $obj;
	}

}

?>