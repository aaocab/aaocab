<?php

namespace Stub\booking;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class NotifyVendor
{

	/** @var \Stub\booking\NotificationInfo $notifications */
	public $notifications;

	/** @var \Stub\booking\VendorInfo $vendors */
	public $vendors;
	public $notifyDateTime;

	public function setData($logArr)
	{
		$vendors				 = new \Stub\booking\VendorInfo();
		$vendors->setData($logArr);
		$notifications			 = new \Stub\booking\NotificationInfo();
		$notifications->setData($logArr);
		$this->vendors			 = $vendors;
		$this->notifications	 = $notifications;
		$this->notifyDateTime	 = $logArr['notifiedDateTime'];
	}
}

class VendorInfo
{

	public $totalFound;
	public $totalNotified;
	public $sourceHomeZone, $destHomeZone;
	public $acceptedZone;

	public function setData($logArr)
	{
		$this->totalFound	 = $logArr['totalVendorFound'];
		$this->totalNotified = $logArr['totalVendorsNotified'];
		$this->sourceHomeZone	 = $logArr['totSourceHomeZone'];
		$this->destHomeZone		 = $logArr['totDestHomeZone'];
		$this->acceptedZone		 = $logArr['totAcceptedZone'];
	}

}

class NotificationInfo
{

	public $totalVendorNotified;
	public $smsCount;
	public $appCount;

	public function setData($logArr)
	{
		$this->totalVendorNotified	 = $logArr['totalVendorsNotified'];
		$this->smsCount				 = $logArr['totSmS'];
		$this->appCount				 = $logArr['totAppNotified'];
	}

}

?>