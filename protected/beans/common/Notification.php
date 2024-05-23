<?php

namespace Beans\common;

class Notification
{

	public $id;
	public $data;
	public $EventCode;
	public $payload;
	public $title;
	public $message;
	public $body;
	public $createDate;
	public $isRead;
	public $readAt;
	public $actionValue;
	public $actionAt;
	public $totalCount;
	public $pageSize;
	public $currentPage;

	/**
	 * 
	 * @model \BookingVendorRequest $bvrModel
	 * @param integer $eventCode
	 * @return \Beans\common\Notification
	 */
	public static function setNotifyCustomer($bvrModel, $eventCode = '')
	{
		$payLoadData = \Beans\booking\Offer::setData($bvrModel);

		$obj			 = new \Beans\common\Notification();
		$obj->data		 = $payLoadData;
		$obj->EventCode	 = (int) $eventCode;
		$obj->id		 = (int) $bvrModel->bvr_booking_id;
		return \Filter::removeNull($obj);
	}

	public function setDCONotifyForAdminChat(\Beans\common\Chat $chatObjData, $titleMessage = '')
	{
		$chatMsg = $chatObjData->message;
		$message = (strlen($chatMsg) > 30 ) ? substr($chatMsg, 25) . '...' : $chatMsg;

		$title = ($titleMessage == '' ) ? "New Chat Meassage" : $titleMessage;

		$eventCode = \Booking::CODE_DCO_NEW_CHAT_NOTIFIED;

		$payload = \Beans\common\Payload::setChatData($chatObjData, $eventCode);		

		$this->EventCode = $eventCode;
		$this->title	 = $title;
		$this->payload	 = $payload;
		$this->message	 = $message;
	}

	public function setNotificationBody()
	{

		$notification = [
			'title'		 => $this->title,
			'EventCode'	 => $this->EventCode,
			'body'		 => $this->message,
		];
		return $notification;
	}
}
