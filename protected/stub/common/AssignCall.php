<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;

/**
 * Description of AssignCall
 *
 * @author Suvajit
 */
class AssignCall
{

	public $id, $call_id, $call_type, $call_sync_id, $call_record, $call_assigntime, $call_type_name;
	public $contact;
	public $type, $type_name, $sync_id, $record, $assigntime;

	/**
	 * 
	 * @param \CallStatus $model
	 * @param type $refId
	 * @param type $refType
	 * @return $this
	 */
	public function setData(\CallStatus $model, $refId, $refType)
	{
		$call_type_name			 = \AssignLog::getCallTypeName($refId, $refType);
		$this->call_id			 = (int) $refId;
		$this->call_type		 = (int) $refType;
		$this->call_type_name	 = $call_type_name;
		$this->call_sync_id		 = $model->cst_id;
		$this->call_record		 = (int) $model->cst_ref_record;
		$this->call_assigntime	 = $model->cst_created;
		$this->contact			 = new \Stub\common\Person();
		$this->contact->setLeadData($refId, $refType);
		return $this;
	}

	/**
	 * 
	 * @param \CallStatus $model
	 * @return $this
	 */
	public function setCallData(\CallStatus $model, \ServiceCallQueue $serviceModel)
	{
		$callTypeName	 = \ServiceCallQueue::getCallTypeName($model->cst_lead_id);
		$this->id		 = (int) $model->cst_lead_id;
		if ($serviceModel->scq_related_bkg_id != null && $serviceModel->scq_follow_up_queue_type == 1)
		{
			$this->id = (int) $serviceModel->scq_related_bkg_id;
		}
		else if ($serviceModel->scq_related_lead_id != null && $serviceModel->scq_follow_up_queue_type == 1)
		{
			$this->id = (int) $serviceModel->scq_related_lead_id;
		}
		$this->type			 = (int) $model->cst_type;
		$this->type_name	 = $callTypeName;
		$this->sync_id		 = $model->cst_id;
		$this->record		 = (int) $model->cst_ref_record;
		$this->assigntime	 = $model->cst_created;
		return $this;
	}

}
