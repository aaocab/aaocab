<?php

namespace Stub\mmt;

class Capacity
{
    public $small;
    public $medium;
    public $large;
	
	public function setData($cabId)
	{
        /* @var $svcModel SvcClassVhcCat */
		$svcModel = \SvcClassVhcCat::model()->getVctSvcList('object', 0, 0, $cabId);
		$this->small = (int) $svcModel->vcsc_small_bag;
        $this->medium = 0;
        $this->large = 0;
	}

}
