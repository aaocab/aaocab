<?php

namespace Beans\operator\hornok;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ChauffeurRequest
{
	/** @var \Stub\common\Phone $primaryContact  */
	public $primaryContact;

	public $firstName; 
	public $operatorDrvId;

	/**
	 * 
	 * @param type $data
	 * @return this
	 */
	public function setParams($data)
	{
		$this->firstName				 = $data->driver->firstName;
		$this->operatorDrvId			 = $data->driver->id;
		$this->primaryContact->code		 = 91;
		#$this->primaryContact->number	 = $data->driver->mobileNo;
		\Filter::parsePhoneNumber($data->driver->phone[0]->fullNumber,$code,$phnNumber);
		$this->primaryContact->number	 = $phnNumber;
		return $this;
	}

	/**
	 * 
	 * @param type $data
	 * @return 
	 */
	public static function getInstance($data)
	{
		$obj = new static();
		$obj->setParams($data);	

		$jsonData = json_encode($obj);
		return json_decode($jsonData);
	} 

}
