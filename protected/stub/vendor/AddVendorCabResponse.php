<?php

namespace Stub\vendor;

class AddVendorCabResponse
{

	public $id;

	/** @var \Stub\common\Vehicle $cab */
	public $cab;

	public function setModelData($vhcModel)
	{
		$this->id        = $vhcModel->vhc_id;
		$this->cab		 = new \Stub\common\Vehicle();
		$this->cab->setModelData($vhcModel);
		return $this;
	}

}
