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

	public function setDetails($promo)
	{
		$this->id			 = (int) $promo['prm_id'];
		$this->code			 = $promo['prm_code'];
		$this->description	 = $promo['prm_desc'];
		$this->amount		 = (float) $promo['cashAmount'];
		return $this;
	}

}
