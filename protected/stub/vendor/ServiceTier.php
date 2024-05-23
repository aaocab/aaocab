<?php

namespace Stub\vendor;

/**
 * vendor Pending request
 *
 *
 */
class serviceTier
{

	public $id;
	public $name;


	public function setTier($data)
	{
		foreach ($data as $tier)
		{
			$object				 = new \Stub\vendor\serviceTier();
			$object->fillData($tier);
		$tierList[]	 = $object;
		}
		return $tierList;
	}


	public function fillData($row)
	{

		$this->id			 = (int) $row->id;
		$this->name			 = $row->name;
		
	}
}