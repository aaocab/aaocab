<?php

namespace Beans\common;

class Payload
{

	public $bkgId;
	public $tripId;
	public $data;
	public $isGozoNow;
	public $EventCode;

	/**
	 * 
	 * @param \Beans\booking\Offer $offerObj
	 * @param integer $event
	 * @return \Beans\common\Payload
	 */
	public static function setData($offerObj, $event = null)
	{
		$payloadObj				 = new \Beans\common\Payload();
		$payloadObj->bkgId		 = (int) $offerObj->booking->id;
		$payloadObj->data		 = (array) $offerObj;
		$payloadObj->EventCode	 = (int) $event;
		return \Filter::removeNull($payloadObj);
	}

	public static function setChatData(\Beans\common\Chat $chatMsg, $event = null)
	{
		$payloadObj	 = new Payload();
		$chatRoom	 = $chatMsg->room;
		if($chatRoom->refType === 0)
		{
			$payloadObj->bkgId = (int) $chatRoom->refId;
		}
		$payloadObj->data		 = \Filter::removeNull($chatMsg);
		$payloadObj->EventCode	 = (int) $event;
		return \Filter::removeNull($payloadObj);
	}
}
