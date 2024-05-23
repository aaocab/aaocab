<?php

namespace Stub\mmt;

class Amenities
{
	/** @var \Stub\mmt\Luggage $luggage */
	public $luggage;
    public $pax;
	public function setCabType($cabId)
	{
        /* @var $svcModel SvcClassVhcCat */
		$svcModel = \SvcClassVhcCat::model()->getVctSvcList('object', 0, 0, $cabId);
		$this->luggage = new Luggage();
		$this->luggage->setData($cabId);
        $this->pax = (int) $svcModel->vct_capacity;
	}

}
