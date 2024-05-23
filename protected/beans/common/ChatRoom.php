<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ChatRoom
 *
 * @author Deepak
 *
 * @property integer $id 
 * @property integer $refId 
 * @property integer $refType 
 * @property \Bean\Booking $refType
 * @property integer $entityList[] ;
 * @property string $startedOn 
 * @property \Beans\common\Chat $list[] 
 * 
 */

namespace Beans\common;

class ChatRoom
{

	public $id;
	public $refId;
	public $refType;

	/** @var \Beans\Booking $booking */
	public $booking;
	public $entityList;
	public $startedOn;

	/** @var \Beans\common\Chat $list */
	public $list;

	public static function getList($list)
	{
		$room = new ChatRoom();
		$room->getFromModelData($list[0]);
		$room->getListFromModelData($list);
		return $room;
	}

	public function getFromModelData($data)
	{
		$this->id		 = (int) $data['cht_id'];
		$this->refId	 = (int) $data['cht_ref_id'];
		$this->refType	 = (int) $data['cht_ref_type'];
		if($data['bkg_booking_id'] && $data['cht_ref_type'] == 0)
		{
			$this->booking->id	 = (int) $data['cht_ref_id'];
			$this->booking->code = $data['bkg_booking_id'];
		}
		$this->startedOn = $data['cht_start_date'];
	}

	public function getListFromModelData($list)
	{
		foreach($list as $message)
		{
			$object		 = new Chat();
			$object->getFromModelData($message);
			$rowList[]	 = $object;
		}
		$this->list = $rowList;
	}

	public function getByModel($chtModel)
	{
		$this->id		 = (int) $chtModel->cht_id;
		$this->refId	 = (int) $chtModel->cht_ref_id;
		$this->refType	 = (int) $chtModel->cht_ref_type;
		$this->startedOn = $chtModel->cht_start_date;
	}

	public function setNotificationData($data)
	{
		$this->id		 = (int) $data['cht_id'];
		$this->refId	 = (int) $data['cht_ref_id'];
		$this->refType	 = (int) $data['cht_ref_type']; 
		$this->startedOn = $data['cht_start_date'];
	}
}
