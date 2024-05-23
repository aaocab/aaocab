<?php

namespace Stub\consumer;

class SOSResponse
{
	public $isSMSTriggered;
	
	/** @var \Stub\common\Person $triggeredContacts */
	public $triggeredContacts;



	public function setData(\BookingTrack $model)
	{
		$this->isSMSTriggered	 = $model->bkg_sos_sms_trigger;
	}

}
