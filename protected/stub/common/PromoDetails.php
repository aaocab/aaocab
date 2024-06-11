<?php

namespace Stub\common;

class PromoDetails
{

	//put your code here
	public $id;	// promo_id
	public $type;  // promo_type
	public $code;  // promo_code
	public $description; // promo_desc
	public $isPromoCodeUsed;   // isPromoCodeUsed
	public $promoUserType;  // prm_user_type  1 =>Booking, 2=> GiftCard
	public $isActiveStatus; // prm_active
	public $promoCodes;
	public $promos;
	public $allowNegativeAddon;

	/** @var \Stub\common\Fare $credit */
	public $credit;

	/** @var \Stub\common\Balance $amount */
	public $amount;

	public function setModelData(\Promos $model = null)
	{
		if ($model == null)
		{
			$model = new \Promos();
		}
		$this->id				 = (int) $model->prm_id;
		$this->code				 = $model->prm_code;
		$this->description		 = $model->prm_desc;
		$this->promoUserType	 = (int) $model->prm_user_type;
		$this->isActiveStatus	 = (int) $model->prm_active;
		$this->allowNegativeAddon  = (int) $model->prm_allow_negative_addon;
	}

	/**
	 * 
	 * @param array $credits
	 * @param array $promos
	 */
	public function setData($credits, $promos, $walletBalance)
	{
		$this->promoCodes		 = [];
		$this->credit			 = ($credits > 0) ? (int) $credits : null;
		$this->wallet->amount	 = ($walletBalance > 0) ? (int) $walletBalance : null;
		if (!empty($promos))
		{
			foreach ($promos as $v)
			{
				$obj				 = new \Stub\common\PromoDetails();
				$obj->setDetails($v);
				$this->promoCodes[]	 = $obj;
			}
		}
	}

	/**
	 * @param array $promos
	 * @param integer $credits
	 * @param integer $walletBalance
	 * @return $object
	 */
	public static function setDataSet($promos, $credits, $walletBalance)
	{
		$obj				 = new \Stub\common\PromoDetails();
		$obj->credit		 = ($credits > 0) ? (int) $credits : null;
		$obj->wallet->amount = ($walletBalance > 0) ? (int) $walletBalance : null;
		if (!empty($promos))
		{
			foreach ($promos as $v)
			{
				$promoObj		 = \Stub\common\PromoDetails::setPromoDetails($v);
				$obj->promos[]	 = $promoObj;
			}
		}
		return $obj;
	}

	/**
	 * 
	 * @param Array $promo
	 * @return \Stub\common\PromoDetails
	 */
	public static function setPromoDetails($promo)
	{
		$obj				 = new \Stub\common\PromoDetails();
		$obj->id			 = (int) $promo['prm_id'];
		$obj->code			 = $promo['prm_code'];
		$obj->description	 = $promo['prm_desc'];
		$obj->amount		 = (float) $promo['cashAmount'];
		return $obj;
	}

	public function setDetails($promo)
	{
		$this->id			 = (int) $promo['prm_id'];
		$this->code			 = $promo['prm_code'];
		$this->description	 = $promo['prm_desc'];
		$this->amount		 = (float) $promo['cashAmount'];
		return $this;
	}

}
