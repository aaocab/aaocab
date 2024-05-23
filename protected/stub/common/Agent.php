<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;

/**ssssss
 * Description of Vendor
 *
 * @author Abhishek Khetan
 */
class Agent extends Person
{

	public $id, $code, $uniqueName, $type,$approveStatus, $apiKey;

	/** @var \Stub\common\Person $contact */
	public $contact;

	/** @var \Stub\common\Business $business */
	public $business;

	/**
	 * 
	 * @param \Agents $model
	 * @return $this
	 */
	public function setData(\Agents $model = null)
	{
		if ($model == null)
		{
			$model = \Agents::model()->findByPk($this->id);
		}
		$this->id			 = $model->agt_id;
		$this->code			 = $model->agt_code;
		$this->uniqueName	 = $model->agt_fname;
		$this->approveStatus = $model->agt_active ;
        $this->apiKey        = $model->agt_api_key;
		$contact			 = $model->agtContact;
        $this->contact = new \Stub\common\Person();
        $this->contact->setContactData($model->agtContact);
		return $this;
	}
	
	public function allData($row)
	{
		
		$this->id	 = (int) $row['agt_id'];
		$this->text	 = $row['agt_fname'].' '.$row['agt_lname'];
	}

	public function getAgtData($dataArr)
	{
		
		foreach ($dataArr as $row)
		{
			
			$obj				 = new \Stub\common\Agent();
			$obj->allData($row);
			$this->dataList[]	 = $obj;
		}
		return $this->dataList;
	}
}
