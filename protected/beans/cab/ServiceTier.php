<?php

namespace Beans\cab;

/**
 * serviceTier
 *
 * @property string $id
 * @property string $name
 */
class ServiceTier
{

	public $id;
	public $name;

	public function setTier($data)
	{
		foreach ($data as $tier)
		{
			$object		 = new \Beans\cab\ServiceTier();
			$object->fillData($tier);
			$tierList[]	 = $object;
		}
		return $tierList;
	}

	public function fillData($row)
	{

		$this->id	 = (int) $row->id;
		$this->name	 = $row->name;
	}

}
