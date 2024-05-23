<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\documents;

/**
 *  @property \Stub\common\Value $expiryDate 
 *  @property \Stub\common\Documents1 $document
 */
class commonDocument
{
	/** @var \Stub\common\Value $expiryDate */
	public $expiryDate;
	/** @var \Stub\common\Documents1 $document */
	public $document;

	public function __construct()
	{
		$this->expiryDate = new \Stub\common\Value();
		$this->document	  = new \Stub\common\Documents1();
	}

}
