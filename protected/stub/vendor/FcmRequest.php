<?php
namespace Stub\vendor;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class FcmRequest
{
	/** @var \Stub\common\Platform $device */
	public $device;
	
	public function getModel()
	{
		return $this->device->getAppToken();  
	}
	
}
