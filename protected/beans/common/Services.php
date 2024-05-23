<?php

namespace Beans\common;

class Services
{

	public $localBusiness;
	public $outstationBusiness;
	public $shortNoticeBooking;
	public $tempoTraveller;

	public function setServiceData($data)
	{
		$this->localBusiness		 = new \Beans\common\LocalBusiness();
		$this->localBusiness->setData($data);
		$this->outstationBusiness	 = new \Beans\common\OutstationBusiness();
		$this->outstationBusiness->setData($data);
		$this->shortNoticeBooking	 = ($data['vnp_lastmin_booking'] == 1) ? (int) $data['vnp_lastmin_booking'] : 0;
		$this->tempoTraveller		 = ($data['vnp_tempo_traveller'] == 1) ? (int) $data['vnp_tempo_traveller'] : 0;
	}

}
