<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Beans\transferz;

/**
 * Description of TransferzPendingRequest
 *
 * @author Ankesh
 */
class AcceptOffer
{
	public $meetingPointId, $type;

	public function setData($jsonObj)
	{	
//		if($reAccept == true)
//		{
//			$jsonObj	 = \CJSON::decode($jsonObj, false);
//		}
		$meetingPointid = $jsonObj->meetingPoints[0]->id;
		$this->meetingPointId = $meetingPointid;
		$this->type = $jsonObj->meetingPoints[0]->type;
	}
}
