<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Chat
 *
 * @author Deepak
 *
 * @property integer $id   
 * @property \Beans\common\ChatRoom $room
 * @property string $message
 * @property integer $entityId
 * @property integer $entityType
 * @property integer $visibleTo[] 
 * @property integer $status
 * @property integer $isRead
 * @property integer $type
 * @property string $sendDate
 * 
 */

namespace Beans\common;

class Chat
{

	public $id;

	/** @var \Beans\common\ChatRoom $room */
	public $room;
	public $message;
	public $messageCategory;
	public $entityId;
	public $entityType;

	/** @var \Beans\contact\Person $sender */
	public $sender;
	public $senderType;
	public $visibleTo; //[entityList]
	public $status;
	public $isRead;

	/** @var \Beans\common\ValueObject $type */
	public $type;
	public $sendDate;

	public function getFromModelData($data)
	{
		$this->id				 = (int) $data['chl_id'];
		$this->message			 = $data['chl_msg'];
		$this->entityId			 = (int) $data['chl_ref_id'];
		$this->entityType		 = (int) $data['chl_ref_type'];
		$this->isRead			 = (int) $data['chl_admin_is_read'];
		$this->sendDate		 = $data['chl_created'];
		$this->sender			 = new \Beans\contact\Person();
		$this->sender->id		 = (int) $data['chl_ref_id'];
		$this->sender->firstName		 = $data['ref_name'];
		$this->senderType		 = $data['ref_type'];
		$this->messageCategory	 = $data['display_name'];
		if($data['chl_type'])
		{
			$chatTypeList	 = \ChatLog::chatType;
			$this->type		 = \Beans\common\ValueObject::setIdlabel($data['chl_type'], $chatTypeList[$data['chl_type']]);
		}
	}

	/**
	 * 
	 * @param int $chlId
	 */
	public function getById($chlId)
	{
		/** @var \ChatLog $chlModel */
		$chlModel = \ChatLog::model()->findByPk($chlId);
		$this->getByModel($chlModel);
	}

	/**
	 * 
	 * @param \ChatLog $chlModel
	 */
	public function getByModel(\ChatLog $chlModel)
	{
		$this->id			 = (int) $chlModel->chl_id;
		$this->message		 = $chlModel->chl_msg;
		$this->entityId		 = (int) $chlModel->chl_ref_id;
		$this->entityType	 = (int) $chlModel->chl_ref_type;
		$this->isRead		 = (int) $chlModel->chl_admin_is_rea;
		$this->sendDate	 = $chlModel->chl_created;
		$this->sender		 = new \Beans\contact\Person();
		$this->sender->id	 = (int) $chlModel->chl_ref_id;
		$chatTypeList		 = \ChatLog::chatType;
		$this->type			 = \Beans\common\ValueObject::setIdlabel($chlModel->chl_type, $chatTypeList[$chlModel->chl_type]);
		$this->room			 = new ChatRoom();
		$this->room->getByModel($chlModel->chlCht);
	}

	public static function setNotificationData($data)
	{
		$obj			 = new Chat();
		$obj->id		 = (int) $data['chl_id'];
		$obj->message	 = $data['chl_msg'];
		$obj->sendDate	 = $data['chl_created'];
		$obj->senderType = $data['ref_type'];
		$obj->room		 = new ChatRoom();
		$obj->room->setNotificationData($data);
		return  $obj ; 
	}
}
