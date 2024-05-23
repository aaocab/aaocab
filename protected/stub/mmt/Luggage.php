<?php

namespace Stub\mmt;

class Luggage
{
	/** @var \Stub\mmt\Capacity $capacity */
	public $capacity = [];
    

	public function setData($cabId)
	{
        /* @var $svcModel SvcClassVhcCat */
		$svcModel = \SvcClassVhcCat::model()->getVctSvcList('object', 0, 0, $cabId);
        
		$this->capacity = new Capacity();
		$this->capacity->setData($cabId);
        
        
        
	}

}
