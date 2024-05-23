<?php

namespace Stub\common;

class ServiceCall
{

	public $id, $code, $queue, $type, $entityType, $team, $status, $instruction;

	/** @var \Stub\common\Phone $alternateContact  */
	public $alternateContact;

	/** @var \Stub\common\Vendor $vendor  */
	public $vendor;

	/** @var \Stub\common\Consumer $consumer  */
	public $consumer;

	/** @var \Stub\common\Admin $admin  */
	public $admin;

	/** @var \Stub\common\Driver $driver  */
	public $driver;

	/** @var \Stub\common\AssignCall $callStatus  */
	public $callStatus;

	/** @var \Stub\common\Person $contact  */
	public $contact;

	/**
	 * 
	 * @param \ServiceCallQueue $model
	 * @return $this
	 */
	public function setData(\ServiceCallQueue $scqModel)
	{
		$this->id			 = (int) $scqModel->scq_id;
		$this->code			 = $scqModel->scq_unique_code;
		$this->queue		 = $scqModel->scq_follow_up_queue_type;
		$this->entityType	 = (int) $scqModel->scq_to_be_followed_up_with_entity_type;
		if ($scqModel->scq_to_be_followed_up_by_type == 1)
		{
			$this->team = (int) $scqModel->scq_to_be_followed_up_by_id;
		}
		$this->status		 = (int) $scqModel->scq_status;
		$this->instruction	 = $scqModel->scq_creation_comments;
		
		//1:internal;0:external
		$this->type = ($scqModel->scq_to_be_followed_up_with_type == 0) ? 1 : 0;
		if ($scqModel->scq_to_be_followed_up_with_type == 2 && $scqModel->scq_to_be_followed_up_with_value > 0)
		{
			if (\Config::get('maskNumbersCbm') == 1)
			{
				$this->alternateContact->code	 = (int) 91;
				$this->alternateContact->number	 = \Config::get('csrToCustomers');
			}
			else
			{
				\Filter::parsePhoneNumber($scqModel->scq_to_be_followed_up_with_value, $code, $phone);
				$this->alternateContact->code	 = (int) $code;
				$this->alternateContact->number	 = $phone;
			}
		}

		if ($scqModel->scq_to_be_followed_up_with_entity_id > 0)
		{
			$contactId = \ContactProfile::getByEntityId($scqModel->scq_to_be_followed_up_with_entity_id, $scqModel->scq_to_be_followed_up_with_entity_type);
			if ($contactId > 0 && $contactId != null)
			{
				$this->setEntityData($contactId, $scqModel);
			}
		}
		$CallStausModel		 = \CallStatus::model()->getByRefId($scqModel->scq_id);
		$this->callStatus	 = new \Stub\common\AssignCall();
		$this->callStatus->setCallData($CallStausModel, $scqModel);
		return $this;
	}

	public function setEntityData($contactId, $scqModel)
	{
		$contactModel = \Contact::model()->findByPk($contactId);
		if ($contactModel != null)
		{
			$contactModel->isServiceCall = 1;
			$this->contact				 = new \Stub\common\Person();
			$this->contact->setPersonData($contactModel);
			if ($scqModel->scq_to_be_followed_up_with_type == 2 && $scqModel->scq_to_be_followed_up_with_value > 0)
			{
				if (\Config::get('maskNumbersCbm') == 1)
				{
					$this->contact->primaryContact->code	 = (int) 91;
					$this->contact->primaryContact->number	 = \Config::get('csrToCustomers');
				}
				else
				{
					\Filter::parsePhoneNumber($scqModel->scq_to_be_followed_up_with_value, $code, $phone);
					$this->contact->primaryContact->code	 = (int) $code;
					$this->contact->primaryContact->number	 = $phone;
				}
			}
		}
	}

	public function getRequestData($data,$scqModel)
	{
		if($data->whenToFollowUp == 3)
		{
            $scqModel->scq_follow_up_date_time	   = date('Y-m-d', strtotime(str_replace("/", "-", $data->followUpDate))) . " " . date("H:i", strtotime($data->followUpTime));  
		}
		else
		{
			$scqModel->scq_follow_up_date_time = new \CDbExpression('NOW()');
		}
		$scqModel->scq_to_be_followed_up_by_type = $data->followUpby;
		$scqModel->scq_follow_up_priority        = 2;
		if($data->followUpby == 1)
		{
			$scqModel->scq_to_be_followed_up_by_id = (int)$data->teamId;
		}
		else 
		{
			$scqModel->scq_to_be_followed_up_by_id = (int)$data->gozenId;
		}
		if ($data->isRelatedBooking == 1)
        {
            $scqModel->scq_related_bkg_id = $data->relatedBkgId;
        }
        else
        {
            $scqModel->scq_priority_score = 10;
        }
        $scqModel->scq_creation_comments  = trim($data->comments);    
		return $scqModel;        
	}

}
