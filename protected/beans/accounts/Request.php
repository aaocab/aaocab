<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Request
 *
 * @author Deepak
 * 
 * 
 * @property \Beans\common\DateRange $dateRange
 * @property \Beans\common\PageRef $pageRef 
 * 
 */

namespace Beans\accounts;

class Request
{

	/** @var \Beans\common\DateRange $dateRange */
	public $dateRange;

	/** @var \Beans\common\PageRef $pageRef */
	public $pageRef;

	public function getRequest()
	{
		$obj			 = new AccountLedgerDetails();
		$obj->dateRange	 = new \Beans\common\DateRange();
		$obj->dateRange	 = $this->dateRange;
		$size			 = 50;
		$obj->pageRef	 = \Beans\common\PageRef::getDefault($this->pageRef, $size);
		return $obj;
	}

}
