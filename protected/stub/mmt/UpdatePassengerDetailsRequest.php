<?php

namespace Stub\mmt;
/**
 * Description of UpdatePassengerDetailsRequest
 *
 * @author Subhradip
 */
class UpdatePassengerDetailsRequest
{
	public $partner_reference_number;
	public $order_reference_number;
	
	/** @var \Stub\mmt\Person $passenger */
	public $passenger;

	public function getModel($model = null)
	{
		if($this->partner_reference_number > 0)
		{
			$model = \Booking::model()->findByPk($this->partner_reference_number);
			$model->bkg_agent_ref_code	 = $this->order_reference_number;
			$model->bkgUserInfo          = $this->passenger->getModel();
		}

		return $model;
	}
}
