<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\vendor;

/**
 * Description of AuthResponse
 *
 * @author Abhishek Khetan
 */
class AuthResponse
{

	public $authId;

	/** @var \Stub\common\Vendor $vendor */
	public $vendor;

	public function setData($authId, $vndId = 0)
	{
		$this->authId = $authId;
		$this->vendor = new \Stub\common\Vendor();
        $this->vendor->setModelData($vndId);
	}

}
