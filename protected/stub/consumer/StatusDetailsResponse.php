<?php

namespace Stub\consumer;

class StatusDetailsResponse
{

	public $sosFlag;
	public $sosBookingId;
	public $sosContactAlert;
	public $lastBkgId;
	public $lastBookingId;
	public $showLastBookingId;
	public $isRated;
	public $route;

	public function setData($data)
	{
		$this->sosFlag			 = (int) $data['isSosFlag'];
		$this->isRated			 = (int) $data['isRated'];
		$this->sosBookingId		 = (!empty($data['sosBkgId'])) ? (int) $data['sosBkgId'] : null;
		$this->sosContactAlert	 = (!empty($data['sosContactAlert'])) ? (int) $data['sosContactAlert'] : null;
		$this->lastBkgId		 = (!empty($data['lastBkgId'])) ? (int) $data['lastBkgId'] : null;
		$this->lastBookingId	 = (!empty($data['lastBkgId'])) ? (int) $data['lastBkgId'] : null;
		$this->showLastBookingId = (!empty($data['lastBookingId'])) ? $data['lastBookingId'] : null;
		$this->isRated			 = (int) $data['isRated'];
		$this->route			 = $data['lastRoute'];
	}

}
